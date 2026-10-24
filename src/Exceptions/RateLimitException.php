<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Exception thrown when the API rate limit (5 requests per second per account) is exceeded (HTTP 429).
 */
class RateLimitException extends ActiveCampaignException
{
    protected ?int $retryAfter = null;

    public function setRetryAfter(?int $retryAfter): void
    {
        $this->retryAfter = $retryAfter;
    }

    /**
     * Seconds to wait before retrying, taken from the Retry-After header.
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
