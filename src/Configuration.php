<?php

namespace xdubois\ActiveCampaign;

/**
 * Configuration class for ActiveCampaign API client
 */
class Configuration
{
    protected string $apiToken;
    protected string $accountUrl;
    protected int $timeout;
    protected int $connectTimeout;
    protected int $maxRetries;
    protected float $retryDelay;
    protected array $defaultHeaders;

    public function __construct(
        string $apiToken,
        string $accountUrl,
        int $timeout = 30,
        int $connectTimeout = 10,
        int $maxRetries = 1,
        float $retryDelay = 1.0,
        array $defaultHeaders = []
    ) {
        $this->setApiToken($apiToken);
        $this->setAccountUrl($accountUrl);
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
        $this->maxRetries = $maxRetries;
        $this->retryDelay = $retryDelay;
        $this->defaultHeaders = array_merge([
            'Content-Type' => 'application/json',
            'User-Agent' => 'ActiveCampaign-PHP-SDK/1.0.0',
        ], $defaultHeaders);
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    public function getAccountUrl(): string
    {
        return $this->accountUrl;
    }

    public function setAccountUrl(string $accountUrl): void
    {
        $this->accountUrl = rtrim($accountUrl, '/');
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

    public function getDefaultHeaders(): array
    {
        return array_merge($this->defaultHeaders, [
            'Api-Token' => $this->apiToken,
        ]);
    }

    public function setDefaultHeaders(array $headers): void
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);
    }
}