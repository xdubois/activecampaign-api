<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Base exception class for ActiveCampaign API errors.
 *
 * getCode() holds the HTTP status code of the failed request (0 for network errors).
 */
class ActiveCampaignException extends \Exception
{
    /** @var array<string, mixed> */
    protected array $context = [];

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Additional context about the error: status_code, method, uri,
     * response_body, error_data (decoded body) and the Guzzle exception class.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * HTTP status code of the failed request, or null for network errors.
     */
    public function getStatusCode(): ?int
    {
        $status = $this->context['status_code'] ?? null;

        return is_int($status) ? $status : null;
    }

    /**
     * Raw response body of the failed request, if any.
     */
    public function getResponseBody(): ?string
    {
        $body = $this->context['response_body'] ?? null;

        return is_string($body) ? $body : null;
    }
}
