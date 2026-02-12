<?php

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Base exception class for ActiveCampaign API errors
 */
class ActiveCampaignException extends \Exception
{
    protected array $context = [];

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get additional context information about the error
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set additional context information
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }
}