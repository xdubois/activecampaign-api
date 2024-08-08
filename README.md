# ActiveCampaign API PHP Package

This package provides a simple way to interact with the ActiveCampaign API v3 using PHP. It covers DEALS, CONTACTS, ACCOUNTS, CUSTOM OBJECTS, and CUSTOM FIELDS endpoints.

## Installation

You can install this package via Composer:

```
composer require xdubois/activecampaign-api
```

## Usage

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;

$api = new ActiveCampaignAPI('your-api-token', 'https://your-account.api-us1.com');

// Deals
$deals = $api->deals->list();
$deal = $api->deals->get(123);

// Contacts
$contacts = $api->contacts->list();
$contact = $api->contacts->create([
    'contact' => [
        'email' => 'john@example.com',
        'firstName' => 'John',
        'lastName' => 'Doe'
    ]
]);

// Custom Fields
$customFields = $api->customFields->list();
$newCustomField = $api->customFields->create([
    'field' => [
        'title' => 'New Custom Field',
        'type' => 'text',
        'isRequired' => false
    ]
]);

// Custom Field Values
$fieldValues = $api->contacts->getCustomFieldValues(123);
$api->contacts->updateCustomFieldValue([
    'fieldValue' => [
        'contact' => 123,
        'field' => 1,
        'value' => 'New Value'
    ]
]);

// Accounts
$accounts = $api->accounts->list();

// Custom Objects
$schemas = $api->customObjects->listSchemas();

// More examples...
```

Refer to the ActiveCampaign API documentation for all available endpoints and parameters.

## License

This package is open-sourced software licensed under the MIT