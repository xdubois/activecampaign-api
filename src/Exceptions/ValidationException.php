<?php

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Exception thrown when validation of request data fails
 */
class ValidationException extends ActiveCampaignException
{
    protected array $validationErrors = [];

    public function setValidationErrors(array $errors): void
    {
        $this->validationErrors = $errors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}