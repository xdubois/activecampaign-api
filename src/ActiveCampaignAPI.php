<?php

namespace xdubois\ActiveCampaign;

use xdubois\ActiveCampaign\Endpoints\DealsEndpoint;
use xdubois\ActiveCampaign\Endpoints\ContactsEndpoint;
use xdubois\ActiveCampaign\Endpoints\AccountsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomObjectsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomFieldsEndpoint;

/**
 * Main ActiveCampaign API client class
 * 
 * Provides access to all ActiveCampaign API v3 endpoints including:
 * - Deals
 * - Contacts  
 * - Accounts
 * - Custom Objects
 * - Custom Fields
 * 
 * @author Xavier Dubois <xavier@example.com>
 * @version 1.0.0
 */
class ActiveCampaignAPI
{
    protected DealsEndpoint $deals;
    protected ContactsEndpoint $contacts;
    protected AccountsEndpoint $accounts;
    protected CustomObjectsEndpoint $customObjects;
    protected CustomFieldsEndpoint $customFields;
    protected Configuration $config;

    /**
     * Constructor - supports both new Configuration object and legacy parameters for backward compatibility
     * 
     * @param Configuration|string $apiTokenOrConfig API token string or Configuration object
     * @param string|null $accountUrl Account URL (only used if first parameter is string)
     * 
     * @example Using with Configuration object (recommended):
     * ```php
     * $config = new Configuration('your-api-token', 'https://yourapp.api-us1.com');
     * $api = new ActiveCampaignAPI($config);
     * ```
     * 
     * @example Using legacy constructor (maintained for backward compatibility):
     * ```php
     * $api = new ActiveCampaignAPI('your-api-token', 'https://yourapp.api-us1.com');
     * ```
     */
    public function __construct($apiTokenOrConfig, ?string $accountUrl = null)
    {
        if ($apiTokenOrConfig instanceof Configuration) {
            $this->config = $apiTokenOrConfig;
        } else {
            // Legacy constructor for backward compatibility
            $this->config = new Configuration($apiTokenOrConfig, $accountUrl);
        }

        // Initialize all endpoint classes with the configuration
        $this->deals = new DealsEndpoint($this->config);
        $this->contacts = new ContactsEndpoint($this->config);
        $this->accounts = new AccountsEndpoint($this->config);
        $this->customObjects = new CustomObjectsEndpoint($this->config);
        $this->customFields = new CustomFieldsEndpoint($this->config);
    }

    /**
     * Get the deals endpoint for managing deals
     */
    public function deals(): DealsEndpoint
    {
        return $this->deals;
    }

    /**
     * Get the contacts endpoint for managing contacts
     */
    public function contacts(): ContactsEndpoint
    {
        return $this->contacts;
    }

    /**
     * Get the accounts endpoint for managing accounts
     */
    public function accounts(): AccountsEndpoint
    {
        return $this->accounts;
    }

    /**
     * Get the custom objects endpoint for managing custom objects
     */
    public function customObjects(): CustomObjectsEndpoint
    {
        return $this->customObjects;
    }

    /**
     * Get the custom fields endpoint for managing custom fields
     */
    public function customFields(): CustomFieldsEndpoint
    {
        return $this->customFields;
    }

    /**
     * Get the configuration object
     */
    public function getConfiguration(): Configuration
    {
        return $this->config;
    }

    // Legacy property access for backward compatibility
    public function __get(string $name)
    {
        switch ($name) {
            case 'deals':
                return $this->deals;
            case 'contacts':
                return $this->contacts;
            case 'accounts':
                return $this->accounts;
            case 'customObjects':
                return $this->customObjects;
            case 'customFields':
                return $this->customFields;
            default:
                throw new \InvalidArgumentException("Property '$name' does not exist");
        }
    }
}
