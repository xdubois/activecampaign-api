<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign;

use GuzzleHttp\ClientInterface;
use xdubois\ActiveCampaign\Endpoints\AccountsEndpoint;
use xdubois\ActiveCampaign\Endpoints\ContactsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomFieldsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomObjectsEndpoint;
use xdubois\ActiveCampaign\Endpoints\DealsEndpoint;
use xdubois\ActiveCampaign\Endpoints\ListsEndpoint;
use xdubois\ActiveCampaign\Endpoints\TagsEndpoint;
use xdubois\ActiveCampaign\Endpoints\UsersEndpoint;
use xdubois\ActiveCampaign\Endpoints\WebhooksEndpoint;

/**
 * Main ActiveCampaign API client.
 *
 * Provides access to the ActiveCampaign API v3 endpoints: contacts, deals,
 * accounts, custom objects, custom fields, tags, lists, webhooks and users.
 * All endpoints share a single underlying HTTP client.
 *
 * @property-read DealsEndpoint $deals
 * @property-read ContactsEndpoint $contacts
 * @property-read AccountsEndpoint $accounts
 * @property-read CustomObjectsEndpoint $customObjects
 * @property-read CustomFieldsEndpoint $customFields
 * @property-read TagsEndpoint $tags
 * @property-read ListsEndpoint $lists
 * @property-read WebhooksEndpoint $webhooks
 * @property-read UsersEndpoint $users
 */
class ActiveCampaignAPI
{
    public const VERSION = '2.0.0';

    protected Configuration $config;
    protected ClientInterface $httpClient;
    protected DealsEndpoint $deals;
    protected ContactsEndpoint $contacts;
    protected AccountsEndpoint $accounts;
    protected CustomObjectsEndpoint $customObjects;
    protected CustomFieldsEndpoint $customFields;
    protected TagsEndpoint $tags;
    protected ListsEndpoint $lists;
    protected WebhooksEndpoint $webhooks;
    protected UsersEndpoint $users;

    /**
     * @param Configuration|string $apiTokenOrConfig Configuration object or API token (legacy constructor)
     * @param string|null $accountUrl Account URL, required when the first argument is an API token
     * @param ClientInterface|null $httpClient Optional pre-configured Guzzle client shared by every endpoint
     *
     * @example Using a Configuration object (recommended):
     * ```php
     * $config = new Configuration('your-api-token', 'https://youraccount.api-us1.com');
     * $api = new ActiveCampaignAPI($config);
     * ```
     *
     * @example Using the legacy constructor:
     * ```php
     * $api = new ActiveCampaignAPI('your-api-token', 'https://youraccount.api-us1.com');
     * ```
     */
    public function __construct(
        #[\SensitiveParameter]
        Configuration|string $apiTokenOrConfig,
        ?string $accountUrl = null,
        ?ClientInterface $httpClient = null,
    ) {
        if ($apiTokenOrConfig instanceof Configuration) {
            $this->config = $apiTokenOrConfig;
        } else {
            if ($accountUrl === null) {
                throw new \InvalidArgumentException('An account URL is required when passing an API token string');
            }
            $this->config = new Configuration($apiTokenOrConfig, $accountUrl);
        }

        $this->httpClient = $httpClient ?? ActiveCampaignClient::createHttpClient($this->config);

        $this->deals = new DealsEndpoint($this->config, null, $this->httpClient);
        $this->contacts = new ContactsEndpoint($this->config, null, $this->httpClient);
        $this->accounts = new AccountsEndpoint($this->config, null, $this->httpClient);
        $this->customObjects = new CustomObjectsEndpoint($this->config, null, $this->httpClient);
        $this->customFields = new CustomFieldsEndpoint($this->config, null, $this->httpClient);
        $this->tags = new TagsEndpoint($this->config, null, $this->httpClient);
        $this->lists = new ListsEndpoint($this->config, null, $this->httpClient);
        $this->webhooks = new WebhooksEndpoint($this->config, null, $this->httpClient);
        $this->users = new UsersEndpoint($this->config, null, $this->httpClient);
    }

    public function deals(): DealsEndpoint
    {
        return $this->deals;
    }

    public function contacts(): ContactsEndpoint
    {
        return $this->contacts;
    }

    public function accounts(): AccountsEndpoint
    {
        return $this->accounts;
    }

    public function customObjects(): CustomObjectsEndpoint
    {
        return $this->customObjects;
    }

    public function customFields(): CustomFieldsEndpoint
    {
        return $this->customFields;
    }

    public function tags(): TagsEndpoint
    {
        return $this->tags;
    }

    public function lists(): ListsEndpoint
    {
        return $this->lists;
    }

    public function webhooks(): WebhooksEndpoint
    {
        return $this->webhooks;
    }

    public function users(): UsersEndpoint
    {
        return $this->users;
    }

    public function getConfiguration(): Configuration
    {
        return $this->config;
    }

    /**
     * The Guzzle client shared by all endpoints.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Legacy property access ($api->contacts) kept for backward compatibility.
     */
    public function __get(string $name): ActiveCampaignClient
    {
        return match ($name) {
            'deals' => $this->deals,
            'contacts' => $this->contacts,
            'accounts' => $this->accounts,
            'customObjects' => $this->customObjects,
            'customFields' => $this->customFields,
            'tags' => $this->tags,
            'lists' => $this->lists,
            'webhooks' => $this->webhooks,
            'users' => $this->users,
            default => throw new \InvalidArgumentException("Property '$name' does not exist"),
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, [
            'deals', 'contacts', 'accounts', 'customObjects', 'customFields', 'tags', 'lists', 'webhooks', 'users',
        ], true);
    }
}
