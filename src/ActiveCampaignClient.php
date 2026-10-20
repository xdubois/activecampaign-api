<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;

/**
 * Base HTTP client for the ActiveCampaign API.
 *
 * Works with Guzzle 7.9+ and Guzzle 8: only the exception classes that exist in
 * both major versions are referenced, and no-response network failures are
 * detected through the PSR-18 NetworkExceptionInterface.
 *
 * @phpstan-type RequestOptions array{
 *     headers?: array<string, string|non-empty-list<string>>,
 *     json?: mixed,
 *     query?: array<string, mixed>|string,
 *     timeout?: int|float,
 *     connect_timeout?: int|float,
 *     http_errors?: bool,
 * }
 */
class ActiveCampaignClient
{
    /**
     * HTTP status codes that are retried (up to Configuration::getMaxRetries()).
     *
     * @var int[]
     */
    public const RETRYABLE_STATUS_CODES = [429, 500, 502, 503, 504];

    /**
     * Upper bound applied to any retry delay (Retry-After header or backoff),
     * so a misbehaving response or a large maxRetries can never block the
     * process for minutes.
     *
     * @var int
     */
    public const MAX_RETRY_DELAY_SECONDS = 60;

    protected ClientInterface $client;
    protected Configuration $config;

    /**
     * @param Configuration|string $apiTokenOrConfig Configuration object or API token (legacy constructor)
     * @param string|null $accountUrl Account URL, required when the first argument is an API token
     * @param ClientInterface|null $httpClient Optional pre-configured Guzzle client (useful for testing or sharing a client)
     */
    public function __construct(
        #[\SensitiveParameter]
        Configuration|string $apiTokenOrConfig,
        ?string $accountUrl = null,
        ?ClientInterface $httpClient = null,
    ) {
        if ($apiTokenOrConfig instanceof Configuration) {
            $this->config = $apiTokenOrConfig;
        } else {
            if ($accountUrl === null) {
                throw new \InvalidArgumentException('An account URL is required when passing an API token string');
            }
            $this->config = new Configuration($apiTokenOrConfig, $accountUrl);
        }

        $this->client = $httpClient ?? self::createHttpClient($this->config);
    }

    /**
     * Build the default Guzzle client for a configuration.
     *
     * Timeouts and headers are intentionally not baked into the client; they
     * are applied per request so Configuration setters keep working.
     */
    public static function createHttpClient(Configuration $config): Client
    {
        return new Client([
            'base_uri' => $config->getAccountUrl(),
        ]);
    }

    public function getConfiguration(): Configuration
    {
        return $this->config;
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Make an HTTP request to the ActiveCampaign API.
     *
     * @param string $method HTTP method (GET, POST, PUT, PATCH, DELETE)
     * @param string $endpoint API endpoint path, e.g. /api/3/contacts
     * @param RequestOptions $options Guzzle request options
     * @return array<mixed> The decoded JSON response
     * @throws ActiveCampaignException When the API request fails
     */
    protected function request(string $method, string $endpoint, array $options = []): array
    {
        $options = $this->buildRequestOptions($options);
        $maxRetries = max(0, $this->config->getMaxRetries());
        $attempt = 0;

        while (true) {
            try {
                $response = $this->client->request($method, $endpoint, $options);

                return $this->handleSuccessResponse($response);
            } catch (GuzzleException $e) {
                if ($attempt >= $maxRetries || !$this->isRetryable($e)) {
                    $this->handleErrorResponse($e);
                }

                $attempt++;
                $this->sleep($this->getRetryDelay($attempt, $e));
            }
        }
    }

    /**
     * Merge the configuration (timeouts, headers) into the Guzzle request options.
     *
     * @param RequestOptions $options
     * @return RequestOptions
     */
    protected function buildRequestOptions(array $options): array
    {
        $options['headers'] = array_merge($this->config->getDefaultHeaders(), $options['headers'] ?? []);
        $options['timeout'] ??= $this->config->getTimeout();
        $options['connect_timeout'] ??= $this->config->getConnectTimeout();
        $options['http_errors'] = true;

        return $options;
    }

    /**
     * Decode a successful API response.
     *
     * @return array<mixed>
     */
    protected function handleSuccessResponse(ResponseInterface $response): array
    {
        $content = trim((string) $response->getBody());

        if ($content === '') {
            return [];
        }

        try {
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ActiveCampaignException('Invalid JSON response: ' . $e->getMessage(), $response->getStatusCode(), $e, [
                'status_code' => $response->getStatusCode(),
                'response_body' => $content,
            ]);
        }

        return is_array($data) ? $data : [];
    }

    /**
     * Convert a Guzzle exception into the matching ActiveCampaign exception.
     *
     * @throws ActiveCampaignException
     */
    protected function handleErrorResponse(GuzzleException $e): never
    {
        $message = $e->getMessage();
        $context = ['exception' => get_class($e)];

        if ($e instanceof BadResponseException) {
            $response = $e->getResponse();
            $request = $e->getRequest();
            $statusCode = $response->getStatusCode();
            $responseBody = (string) $response->getBody();

            $context['status_code'] = $statusCode;
            $context['method'] = $request->getMethod();
            $context['uri'] = (string) $request->getUri();
            $context['response_body'] = $responseBody;

            $errorData = json_decode($responseBody, true);
            if (is_array($errorData)) {
                $context['error_data'] = $errorData;
                $message = $this->extractErrorMessage($errorData) ?? $message;
            }

            switch (true) {
                case $statusCode === 401 || $statusCode === 403:
                    throw new AuthenticationException($message, $statusCode, $e, $context);
                case $statusCode === 404:
                    throw new NotFoundException($message, $statusCode, $e, $context);
                case $statusCode === 422:
                    $validationException = new ValidationException($message, $statusCode, $e, $context);
                    if (is_array($errorData) && isset($errorData['errors']) && is_array($errorData['errors'])) {
                        $validationException->setValidationErrors($errorData['errors']);
                    }
                    throw $validationException;
                case $statusCode === 429:
                    $rateLimitException = new RateLimitException($message, $statusCode, $e, $context);
                    $rateLimitException->setRetryAfter(self::parseRetryAfter($response));
                    throw $rateLimitException;
                case $statusCode >= 500:
                    throw new ActiveCampaignException("Server error: $message", $statusCode, $e, $context);
                default:
                    throw new ActiveCampaignException($message, $statusCode, $e, $context);
            }
        }

        if ($e instanceof NetworkExceptionInterface) {
            throw new ActiveCampaignException("Connection error: $message", 0, $e, $context);
        }

        throw new ActiveCampaignException("Request failed: $message", 0, $e, $context);
    }

    /**
     * Decide whether a failed request should be retried.
     *
     * Rate limits (429), server errors (5xx) and no-response network failures
     * are retried; every other client error is surfaced immediately.
     */
    protected function isRetryable(GuzzleException $e): bool
    {
        if ($e instanceof BadResponseException) {
            return in_array($e->getResponse()->getStatusCode(), static::RETRYABLE_STATUS_CODES, true);
        }

        return $e instanceof NetworkExceptionInterface;
    }

    /**
     * Seconds to wait before the given retry attempt (1-based).
     *
     * A Retry-After header, when present, wins over the exponential backoff
     * derived from Configuration::getRetryDelay(). Both are capped at
     * MAX_RETRY_DELAY_SECONDS.
     */
    protected function getRetryDelay(int $attempt, GuzzleException $e): float
    {
        if ($e instanceof BadResponseException) {
            $retryAfter = self::parseRetryAfter($e->getResponse());
            if ($retryAfter !== null) {
                return (float) min($retryAfter, static::MAX_RETRY_DELAY_SECONDS);
            }
        }

        $backoff = max(0.0, $this->config->getRetryDelay()) * (2 ** ($attempt - 1));

        return min($backoff, (float) static::MAX_RETRY_DELAY_SECONDS);
    }

    /**
     * Pause between retries. Overridable so tests do not have to sleep.
     */
    protected function sleep(float $seconds): void
    {
        if ($seconds > 0) {
            usleep((int) round($seconds * 1_000_000));
        }
    }

    /**
     * Parse a Retry-After header, given either in seconds or as an HTTP date.
     */
    protected static function parseRetryAfter(ResponseInterface $response): ?int
    {
        if (!$response->hasHeader('Retry-After')) {
            return null;
        }

        $value = trim($response->getHeaderLine('Retry-After'));
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return max(0, (int) $value);
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return max(0, $timestamp - time());
    }

    /**
     * Pull a human readable message out of an ActiveCampaign error payload.
     *
     * The API answers either with {"message": "..."} / {"error": "..."} or with
     * a JSON:API style {"errors": [{"title": "...", "detail": "..."}]} list.
     *
     * @param array<mixed> $errorData
     */
    protected function extractErrorMessage(array $errorData): ?string
    {
        foreach (['message', 'error'] as $key) {
            if (isset($errorData[$key]) && is_string($errorData[$key]) && $errorData[$key] !== '') {
                return $errorData[$key];
            }
        }

        if (isset($errorData['errors']) && is_array($errorData['errors'])) {
            $messages = [];
            foreach ($errorData['errors'] as $error) {
                if (is_string($error)) {
                    $messages[] = $error;
                } elseif (is_array($error)) {
                    $title = $error['title'] ?? $error['detail'] ?? $error['message'] ?? null;
                    if (is_string($title) && $title !== '') {
                        $messages[] = $title;
                    }
                }
            }
            if ($messages !== []) {
                return implode('; ', $messages);
            }
        }

        return null;
    }
}
