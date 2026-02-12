# ActiveCampaign API PHP Package

[![Latest Version](https://img.shields.io/packagist/v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![PHP Version](https://img.shields.io/packagist/php-v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![License](https://img.shields.io/packagist/l/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)

PHP SDK for the ActiveCampaign API v3, covering Deals, Contacts, Accounts, Custom Objects, and Custom Fields endpoints.

## Features

- Full coverage of ActiveCampaign API v3 endpoints (Contacts, Deals, Accounts, Custom Objects, Custom Fields)
- Configuration class with timeouts, retry logic, and rate-limit handling
- Typed exception hierarchy mapping HTTP status codes (401, 404, 422, 429)
- PHP 7.4+ with full type hints and return type declarations

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP client 7.0+
- Valid ActiveCampaign account and API credentials

## Installation

```bash
composer require xdubois/activecampaign-api
```

## Configuration

### Basic Setup

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;

$api = new ActiveCampaignAPI('your-api-token', 'https://yourapp.api-us1.com');
```

### Advanced Setup

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;

$config = new Configuration(
    'your-api-token',
    'https://yourapp.api-us1.com',
    timeout: 60,           // Request timeout in seconds
    connectTimeout: 10,    // Connection timeout in seconds
    maxRetries: 3,         // Number of retry attempts
    retryDelay: 1.0        // Delay between retries in seconds
);

$api = new ActiveCampaignAPI($config);
```

## Usage

### Contacts

```php
// Create a contact
$contact = $api->contacts()->create([
    'contact' => [
        'email' => 'john@example.com',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'phone' => '+1234567890'
    ]
]);

// Get a contact
$contact = $api->contacts()->get(123);

// Update a contact
$updatedContact = $api->contacts()->update(123, [
    'contact' => [
        'firstName' => 'Jane'
    ]
]);

// List contacts with filtering
$contacts = $api->contacts()->list([
    'limit' => 20,
    'offset' => 0,
    'search' => 'john@example.com'
]);

// Sync a contact (create or update)
$syncedContact = $api->contacts()->sync([
    'contact' => [
        'email' => 'john@example.com',
        'firstName' => 'John Updated'
    ]
]);
```

### Deals

```php
// Create a deal
$deal = $api->deals()->create([
    'deal' => [
        'title' => 'New Website Project',
        'value' => 500000, // Value in cents
        'currency' => 'USD',
        'group' => 1,      // Pipeline ID
        'stage' => 1,      // Stage ID
        'contact' => 123   // Contact ID
    ]
]);

// Add a note to a deal
$note = $api->deals()->createNote(456, [
    'note' => [
        'note' => 'Initial client meeting went well'
    ]
]);
```

## Error Handling

```php
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;

try {
    $contact = $api->contacts()->create($data);
} catch (AuthenticationException $e) {
    // 401/403
    echo "Authentication failed: " . $e->getMessage();
} catch (ValidationException $e) {
    // 422
    echo "Validation failed: " . $e->getMessage();
    $errors = $e->getValidationErrors();
} catch (RateLimitException $e) {
    // 429
    echo "Rate limited. Retry after: " . $e->getRetryAfter() . " seconds";
} catch (NotFoundException $e) {
    // 404
    echo "Resource not found: " . $e->getMessage();
} catch (ActiveCampaignException $e) {
    // All other API errors
    echo "API error: " . $e->getMessage();
    $context = $e->getContext();
}
```

## Contributing

Contributions are welcome. For major changes, please open an issue first to discuss what you would like to change, then submit a pull request.

## License

MIT - see [LICENSE](LICENSE).

## Links

- [ActiveCampaign API Documentation](https://developers.activecampaign.com/reference/overview)
- [Package on Packagist](https://packagist.org/packages/xdubois/activecampaign-api)
- [Issues and Feature Requests](https://github.com/xdubois/activecampaign-api/issues)
