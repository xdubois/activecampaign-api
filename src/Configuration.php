<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign;

/**
 * Configuration class for the ActiveCampaign API client.
 *
 * Timeouts, retries and headers are read on every request, so changing them
 * through the setters after the client has been created takes effect
 * immediately.
 */
class Configuration
{
    protected string $apiToken;
    protected string $accountUrl;
    protected int $timeout;
    protected int $connectTimeout;
    protected int $maxRetries;
    protected float $retryDelay;
    /** @var array<string, string|non-empty-list<string>> */
    protected array $defaultHeaders;

    /**
     * @param string $apiToken ActiveCampaign API key (Settings > Developer)
     * @param string $accountUrl Full API URL of the account, e.g. https://youraccount.api-us1.com
     * @param int $timeout Total request timeout in seconds (0 disables it)
     * @param int $connectTimeout Connection timeout in seconds (0 disables it)
     * @param int $maxRetries Number of retries for rate-limited (429), server (5xx) and network errors
     * @param float $retryDelay Base delay in seconds between retries; doubled after each attempt
     * @param array<string, mixed> $defaultHeaders Extra headers sent with every request (scalar values are cast to strings)
     */
    public function __construct(
        #[\SensitiveParameter]
        string $apiToken,
        string $accountUrl,
        int $timeout = 30,
        int $connectTimeout = 10,
        int $maxRetries = 1,
        float $retryDelay = 1.0,
        array $defaultHeaders = [],
    ) {
        $this->setApiToken($apiToken);
        $this->setAccountUrl($accountUrl);
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
        $this->maxRetries = $maxRetries;
        $this->retryDelay = $retryDelay;
        $this->defaultHeaders = array_merge([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'ActiveCampaign-PHP-SDK/' . ActiveCampaignAPI::VERSION,
        ], self::normalizeHeaders($defaultHeaders));
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function setApiToken(#[\SensitiveParameter] string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    public function getAccountUrl(): string
    {
        return $this->accountUrl;
    }

    public function setAccountUrl(string $accountUrl): void
    {
        $this->accountUrl = rtrim(trim($accountUrl), '/');
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }

    public function setConnectTimeout(int $connectTimeout): void
    {
        $this->connectTimeout = $connectTimeout;
    }

    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    public function setMaxRetries(int $maxRetries): void
    {
        $this->maxRetries = $maxRetries;
    }

    public function getRetryDelay(): float
    {
        return $this->retryDelay;
    }

    public function setRetryDelay(float $retryDelay): void
    {
        $this->retryDelay = $retryDelay;
    }

    /**
     * Headers sent with every request, including the Api-Token header.
     *
     * @return array<string, string|non-empty-list<string>>
     */
    public function getDefaultHeaders(): array
    {
        return array_merge($this->defaultHeaders, [
            'Api-Token' => $this->apiToken,
        ]);
    }

    /**
     * Merge additional headers into the default headers.
     *
     * @param array<string, mixed> $headers
     */
    public function setDefaultHeaders(array $headers): void
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, self::normalizeHeaders($headers));
    }

    /**
     * Guzzle 8 only accepts strings (or non-empty lists of strings) as header
     * values, so scalar values are cast to strings up front.
     *
     * @param array<string, mixed> $headers
     * @return array<string, string|non-empty-list<string>>
     */
    private static function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            if (is_array($value)) {
                $items = array_map(static fn(mixed $item): string => self::stringifyHeaderValue($item), array_values($value));
                if ($items === []) {
                    throw new \InvalidArgumentException("Header \"{$name}\" must not be an empty array");
                }
                $normalized[$name] = $items;
            } else {
                $normalized[$name] = self::stringifyHeaderValue($value);
            }
        }

        return $normalized;
    }

    private static function stringifyHeaderValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value) || $value instanceof \Stringable) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('Header values must be strings, scalars or arrays of strings');
    }
}
