<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Exceptions;

/**
 * Exception thrown when validation of request data fails (HTTP 422).
 */
class ValidationException extends ActiveCampaignException
{
    /** @var array<mixed> */
    protected array $validationErrors = [];

    /**
     * @param array<mixed> $errors
     */
    public function setValidationErrors(array $errors): void
    {
        $this->validationErrors = $errors;
    }

    /**
     * The "errors" list returned by the API, e.g. [{title, detail, code, source: {pointer}}].
     *
     * @return array<mixed>
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
