<?php

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Exception thrown when API rate limits are exceeded
 */
class RateLimitException extends ActiveCampaignException
{
    protected ?int $retryAfter = null;

    public function setRetryAfter(?int $retryAfter): void
    {
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}