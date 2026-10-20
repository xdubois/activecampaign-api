<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Exception thrown when authentication with the ActiveCampaign API fails (HTTP 401/403).
 */
class AuthenticationException extends ActiveCampaignException {}
