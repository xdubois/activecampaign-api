# ActiveCampaign API PHP Package

[![CI](https://github.com/xdubois/activecampaign-api/actions/workflows/ci.yml/badge.svg)](https://github.com/xdubois/activecampaign-api/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/packagist/v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![PHP Version](https://img.shields.io/packagist/php-v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![License](https://img.shields.io/packagist/l/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)

PHP SDK for the [ActiveCampaign API v3](https://developers.activecampaign.com/reference/overview): contacts, deals, accounts, custom objects, custom fields, tags, lists, webhooks and users.

## Features

- Contacts (incl. tags, list subscriptions, automations, custom field values, bulk import/sync), deals (incl. pipelines, stages, tasks, custom fields), accounts (incl. contact associations, custom fields), custom objects, custom fields, tags, lists, webhooks and users
- Automatic retries with exponential backoff for rate limits (429), server errors (5xx) and network failures, honouring the `Retry-After` header
- Typed exception hierarchy mapping HTTP status codes (401/403, 404, 422, 429) with the decoded error payload attached
- Works with Guzzle 7.9+ and Guzzle 8; the HTTP client can be injected, so the SDK is easy to mock in your own tests
- PHP 8.1+ with strict types, full type declarations and PHPStan level max

## Requirements

- PHP 8.1 or higher
- Guzzle 7.9+ or 8.x (installed automatically)
- An ActiveCampaign account, its API URL and an API key (Settings > Developer)

## Installation

```bash
composer require xdubois/activecampaign-api
```

Upgrading from 1.x? See the [changelog](CHANGELOG.md#200---2026-10-19) for the (few) breaking changes.

## Configuration

### Basic setup

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;

$api = new ActiveCampaignAPI('your-api-key', 'https://youraccount.api-us1.com');
```

Always use the full API URL shown in your account's Developer settings: accounts outside the US use a different host than `api-us1.com`.

### Advanced setup

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;

$config = new Configuration(
    'your-api-key',
    'https://youraccount.api-us1.com',
    timeout: 60,           // Total request timeout in seconds
    connectTimeout: 10,    // Connection timeout in seconds
    maxRetries: 3,         // Retries for 429 / 5xx / network errors
    retryDelay: 1.0,       // Base delay in seconds, doubled after each retry (Retry-After wins when present)
    defaultHeaders: ['User-Agent' => 'my-app/1.0'],
);

$api = new ActiveCampaignAPI($config);
```

The setters on `Configuration` (`setTimeout()`, `setMaxRetries()`, `setDefaultHeaders()`, ...) apply to every subsequent request.

### Bring your own Guzzle client

Pass a pre-configured `GuzzleHttp\ClientInterface` as third argument to add middleware (logging, caching, ...) or to mock the API in your tests. All endpoints share that client.

```php
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

$mock = new MockHandler([new Response(200, [], '{"contact": {"id": "1"}}')]);
$client = new Client(['handler' => HandlerStack::create($mock)]);

$api = new ActiveCampaignAPI($config, null, $client);
$api->contacts()->get(1); // => ['contact' => ['id' => '1']]
```

## Usage

Every method returns the decoded JSON response as an array and takes payloads in the exact shape documented by ActiveCampaign (including the top-level resource key such as `contact`, `deal` or `account`).

### Contacts

```php
// Create a contact
$contact = $api->contacts()->create([
    'contact' => [
        'email' => 'john@example.com',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'phone' => '+1234567890',
    ],
]);

// Get, update, delete
$contact = $api->contacts()->get(123);
$contact = $api->contacts()->update(123, ['contact' => ['firstName' => 'Jane']]);
$api->contacts()->delete(123);

// List, search and filter
$contacts = $api->contacts()->list(['email' => 'john@example.com', 'limit' => 20, 'offset' => 0]);

// Sync (create or update by email)
$contact = $api->contacts()->sync(['contact' => ['email' => 'john@example.com', 'firstName' => 'John']]);

// Tags, lists and automations
$api->contacts()->addTag(123, $tagId);
$api->contacts()->removeTagById(123, $tagId);
$api->contacts()->updateListStatus(123, $listId, 1); // 1 = subscribe, 2 = unsubscribe
$api->contacts()->addToAutomation(123, $automationId);

// Custom field values
$api->contacts()->updateCustomFieldValue(['fieldValue' => ['contact' => 123, 'field' => 5, 'value' => 'Gold']]);
$values = $api->contacts()->getCustomFieldValues(123);

// Bulk import (up to 250 contacts per call, processed asynchronously)
$batch = $api->contacts()->bulkImport(['contacts' => [['email' => 'a@example.com'], ['email' => 'b@example.com']]]);
$status = $api->contacts()->getBulkImportStatus($batch['batchId']);
```

### Deals

```php
// Pipelines and stages are needed to create a deal
$pipelines = $api->deals()->listPipelines();
$stages = $api->deals()->listStages(['filters[d_groupid]' => 1]);

$deal = $api->deals()->create([
    'deal' => [
        'title' => 'New Website Project',
        'value' => 500000, // Value in cents
        'currency' => 'usd',
        'group' => 1,      // Pipeline ID
        'stage' => 1,      // Stage ID
        'owner' => 1,      // User ID
        'contact' => 123,  // Contact ID
    ],
]);

$note = $api->deals()->createNote(456, ['note' => ['note' => 'Initial client meeting went well']]);
$task = $api->deals()->createTask(['dealTask' => ['relid' => 456, 'duedate' => '2026-11-01T10:00:00Z', 'dealTasktype' => 1]]);
$api->deals()->createCustomFieldValue(['dealCustomFieldDatum' => ['dealId' => 456, 'customFieldId' => 2, 'fieldValue' => 'Enterprise']]);
```

### Accounts, custom objects, custom fields, tags, lists, webhooks, users

```php
$account = $api->accounts()->create(['account' => ['name' => 'Acme Inc.']]);
$api->accounts()->addContact($account['account']['id'], 123, ['jobTitle' => 'CTO']);

$record = $api->customObjects()->createOrUpdateRecord('schema-id', [
    'record' => ['externalId' => 'order-1', 'fields' => [['id' => 'total', 'value' => 99]]],
]);
$record = $api->customObjects()->getRecordByExternalId('schema-id', 'order-1');

$field = $api->customFields()->create(['field' => ['title' => 'Tier', 'type' => 'dropdown']]);
$tag = $api->tags()->create(['tag' => ['tag' => 'VIP', 'tagType' => 'contact']]);
$lists = $api->lists()->list();
$webhook = $api->webhooks()->create(['webhook' => ['name' => 'Sync', 'url' => 'https://example.com/hook', 'events' => ['subscribe'], 'sources' => ['public']]]);
$me = $api->users()->me(); // handy to verify credentials
```

## Error handling

```php
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;

try {
    $contact = $api->contacts()->create($data);
} catch (AuthenticationException $e) {
    // 401/403
} catch (ValidationException $e) {
    // 422
    $errors = $e->getValidationErrors(); // [['title' => ..., 'detail' => ..., 'code' => ..., 'source' => ['pointer' => ...]], ...]
} catch (RateLimitException $e) {
    // 429, after the configured retries were exhausted
    $seconds = $e->getRetryAfter();
} catch (NotFoundException $e) {
    // 404
} catch (ActiveCampaignException $e) {
    // Server errors (5xx), network errors (status code null) and everything else
    $status = $e->getStatusCode();
    $body = $e->getResponseBody();
    $context = $e->getContext(); // status_code, method, uri, response_body, error_data, exception
}
```

Rate limits (429) and server errors (5xx) are retried automatically up to `maxRetries` times; `Retry-After` headers are honoured.

## Development

```bash
composer install
composer test      # PHPUnit
composer phpstan   # static analysis
composer cs        # coding standards (dry run); composer cs-fix applies them
composer check     # all of the above
```

## Contributing

Contributions are welcome. For major changes, please open an issue first to discuss what you would like to change, then submit a pull request.

## License

MIT - see [LICENSE](LICENSE).

## Links

- [ActiveCampaign API Documentation](https://developers.activecampaign.com/reference/overview)
- [Package on Packagist](https://packagist.org/packages/xdubois/activecampaign-api)
- [Issues and Feature Requests](https://github.com/xdubois/activecampaign-api/issues)
