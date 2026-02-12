<?php

namespace xdubois\ActiveCampaign;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;

/**
 * Base HTTP client class for ActiveCampaign API
 */
class ActiveCampaignClient
{
    protected Client $client;
    protected Configuration $config;

    /**
     * Constructor - supports both new Configuration object and legacy parameters for backward compatibility
     */
    public function __construct($apiTokenOrConfig, ?string $accountUrl = null)
    {
        if ($apiTokenOrConfig instanceof Configuration) {
            $this->config = $apiTokenOrConfig;
        } else {
            // Legacy constructor for backward compatibility
            $this->config = new Configuration($apiTokenOrConfig, $accountUrl);
        }

        $this->client = new Client([
            'base_uri' => $this->config->getAccountUrl(),
            'timeout' => $this->config->getTimeout(),
            'connect_timeout' => $this->config->getConnectTimeout(),
            'headers' => $this->config->getDefaultHeaders(),
        ]);
    }

    /**
     * Get the configuration object
     */
    public function getConfiguration(): Configuration
    {
        return $this->config;
    }

    /**
     * Make an HTTP request to the ActiveCampaign API
     * 
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint path
     * @param array $options Guzzle request options
     * @return array The JSON response as an associative array
     * @throws ActiveCampaignException When the API request fails
     */
    protected function request(string $method, string $endpoint, array $options = []): array
    {
        $retryCount = 0;
        $maxRetries = $this->config->getMaxRetries();

        while ($retryCount <= $maxRetries) {
            try {
                $response = $this->client->request($method, $endpoint, $options);
                return $this->handleSuccessResponse($response);
            } catch (GuzzleException $e) {
                // Don't retry on authentication or client errors (4xx except 429)
                if ($this->shouldNotRetry($e) || $retryCount >= $maxRetries) {
                    $this->handleErrorResponse($e);
                }

                $retryCount++;
                if ($retryCount <= $maxRetries) {
                    usleep((int)($this->config->getRetryDelay() * 1000000 * $retryCount));
                }
            }
        }

        // This should never be reached, but just in case
        throw new ActiveCampaignException('Maximum retry attempts exceeded');
    }

    /**
     * Handle successful API responses
     */
    protected function handleSuccessResponse(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ActiveCampaignException('Invalid JSON response: ' . json_last_error_msg());
        }

        return $data ?: [];
    }

    /**
     * Handle API error responses and throw appropriate exceptions
     * 
     * @throws ActiveCampaignException
     */
    protected function handleErrorResponse(GuzzleException $e): void
    {
        $message = $e->getMessage();
        $context = ['exception' => get_class($e)];

        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            
            $context['status_code'] = $statusCode;
            $context['response_body'] = $responseBody;

            // Try to parse error details from response
            $errorData = json_decode($responseBody, true);
            if (is_array($errorData)) {
                $context['error_data'] = $errorData;
                $message = $errorData['message'] ?? $errorData['error'] ?? $message;
            }

            // Throw specific exceptions based on status code
            switch ($statusCode) {
                case 401:
                case 403:
                    throw new AuthenticationException($message, $statusCode, $e, $context);
                case 404:
                    throw new NotFoundException($message, $statusCode, $e, $context);
                case 422:
                    $validationException = new ValidationException($message, $statusCode, $e, $context);
                    if (isset($errorData['errors'])) {
                        $validationException->setValidationErrors($errorData['errors']);
                    }
                    throw $validationException;
                case 429:
                    $rateLimitException = new RateLimitException($message, $statusCode, $e, $context);
                    // Extract retry-after header if present
                    if ($response->hasHeader('Retry-After')) {
                        $rateLimitException->setRetryAfter((int)$response->getHeaderLine('Retry-After'));
                    }
                    throw $rateLimitException;
                default:
                    throw new ActiveCampaignException($message, $statusCode, $e, $context);
            }
        }

        if ($e instanceof ServerException) {
            $statusCode = $e->getResponse()->getStatusCode();
            throw new ActiveCampaignException("Server error: $message", $statusCode, $e, $context);
        }

        if ($e instanceof ConnectException) {
            throw new ActiveCampaignException("Connection error: $message", 0, $e, $context);
        }

        // Generic exception for other Guzzle exceptions
        throw new ActiveCampaignException("Request failed: $message", 0, $e, $context);
    }

    /**
     * Determine if an exception should not be retried
     */
    protected function shouldNotRetry(GuzzleException $e): bool
    {
        if ($e instanceof ClientException) {
            $statusCode = $e->getResponse()->getStatusCode();
            // Don't retry on auth errors, not found, validation errors, etc.
            // Only retry on 429 (rate limit) and server errors
            return !in_array($statusCode, [429, 500, 502, 503, 504]);
        }

        // Retry on server errors and connection issues
        return false;
    }
}
