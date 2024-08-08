<?php

namespace xdubois\ActiveCampaign;

use xdubois\ActiveCampaign\Endpoints\DealsEndpoint;
use xdubois\ActiveCampaign\Endpoints\ContactsEndpoint;
use xdubois\ActiveCampaign\Endpoints\AccountsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomObjectsEndpoint;
use xdubois\ActiveCampaign\Endpoints\CustomFieldsEndpoint;

class ActiveCampaignAPI
{
    public $deals;
    public $contacts;
    public $accounts;
    public $customObjects;
    public $customFields;

    public function __construct(string $apiToken, string $accountUrl)
    {
        $this->deals = new DealsEndpoint($apiToken, $accountUrl);
        $this->contacts = new ContactsEndpoint($apiToken, $accountUrl);
        $this->accounts = new AccountsEndpoint($apiToken, $accountUrl);
        $this->customObjects = new CustomObjectsEndpoint($apiToken, $accountUrl);
        $this->customFields = new CustomFieldsEndpoint($apiToken, $accountUrl);
    }
}
