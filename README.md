# ActiveCampaign API PHP Package

[![Latest Version](https://img.shields.io/packagist/v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![PHP Version](https://img.shields.io/packagist/php-v/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)
[![License](https://img.shields.io/packagist/l/xdubois/activecampaign-api.svg)](https://packagist.org/packages/xdubois/activecampaign-api)

A comprehensive PHP package for interacting with the ActiveCampaign API v3. This package provides a clean, object-oriented interface to all major ActiveCampaign endpoints including **Deals**, **Contacts**, **Accounts**, **Custom Objects**, and **Custom Fields**.

## 🚀 Features

- **Complete API Coverage**: Supports all major ActiveCampaign API v3 endpoints
- **Type Safety**: Full type hints and return type declarations
- **Exception Handling**: Proper exception classes for different error scenarios
- **Rate Limiting**: Built-in rate limit handling and retry logic
- **Configuration**: Flexible configuration options for timeouts, retries, and more
- **Backward Compatibility**: Maintains compatibility with existing implementations
- **PSR Standards**: Follows PSR-4 autoloading and PSR-12 coding standards
- **Documentation**: Comprehensive PHPDoc documentation with links to official API docs

## 📋 Requirements

- PHP 7.4 or higher
- Guzzle HTTP client 7.0+
- Valid ActiveCampaign account and API credentials

## 📦 Installation

Install the package via Composer:

```bash
composer require xdubois/activecampaign-api
```

## 🔧 Configuration

### Basic Setup

```php
use xdubois\ActiveCampaign\ActiveCampaignAPI;

$api = new ActiveCampaignAPI('your-api-token', 'https://yourapp.api-us1.com');
```

### Advanced Setup with Configuration

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

## 📖 Usage Examples

### Working with Contacts

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

### Working with Custom Fields

```php
// Create a custom field
$customField = $api->customFields()->create([
    'field' => [
        'title' => 'Company Size',
        'type' => 'dropdown',
        'isRequired' => false
    ]
]);

// Update custom field value for a contact
$api->contacts()->updateCustomFieldValue([
    'fieldValue' => [
        'contact' => 123,
        'field' => 1,
        'value' => 'Enterprise'
    ]
]);

// Get custom field values for a contact
$fieldValues = $api->contacts()->getCustomFieldValues(123);
```

### Working with Deals

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

### Working with Accounts

```php
// Create an account
$account = $api->accounts()->create([
    'account' => [
        'name' => 'Acme Corporation',
        'accountUrl' => 'https://acme.com'
    ]
]);

// Bulk delete accounts
$result = $api->accounts()->bulkDelete([123, 456, 789]);
```

### Working with Custom Objects

```php
// Create a custom object schema
$schema = $api->customObjects()->createSchema([
    'schema' => [
        'slug' => 'products',
        'labels' => [
            'singular' => 'Product',
            'plural' => 'Products'
        ],
        'fields' => [
            [
                'slug' => 'name',
                'labels' => ['singular' => 'Name'],
                'type' => 'text',
                'required' => true
            ]
        ]
    ]
]);

// Create a record in the custom object
$record = $api->customObjects()->createRecord('products', [
    'record' => [
        'fields' => [
            'name' => 'Awesome Product'
        ]
    ]
]);
```

## 🚨 Error Handling

The package provides specific exception classes for different error scenarios:

```php
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;

try {
    $contact = $api->contacts()->create($invalidData);
} catch (AuthenticationException $e) {
    // Handle authentication errors (401/403)
    echo "Authentication failed: " . $e->getMessage();
} catch (ValidationException $e) {
    // Handle validation errors (422)
    echo "Validation failed: " . $e->getMessage();
    $errors = $e->getValidationErrors();
} catch (RateLimitException $e) {
    // Handle rate limiting (429)
    echo "Rate limited. Retry after: " . $e->getRetryAfter() . " seconds";
} catch (NotFoundException $e) {
    // Handle not found errors (404)
    echo "Resource not found: " . $e->getMessage();
} catch (ActiveCampaignException $e) {
    // Handle all other API errors
    echo "API error: " . $e->getMessage();
    $context = $e->getContext(); // Additional error context
}
```

## 🔄 Migration from Earlier Versions

This package maintains backward compatibility. Existing code will continue to work:

```php
// Legacy usage (still works)
$api = new ActiveCampaignAPI('token', 'https://app.api-us1.com');
$contacts = $api->contacts->list();

// New recommended usage
$api = new ActiveCampaignAPI($config);
$contacts = $api->contacts()->list();
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run with coverage:

```bash
composer test-coverage
```

## 📊 Code Quality

Check code quality with PHPStan:

```bash
composer phpstan
```

Check coding standards:

```bash
composer cs-check
```

Fix coding standards:

```bash
composer cs-fix
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🔗 Links

- [ActiveCampaign API Documentation](https://developers.activecampaign.com/reference/overview)
- [Package on Packagist](https://packagist.org/packages/xdubois/activecampaign-api)
- [Issues and Feature Requests](https://github.com/xdubois/activecampaign-api/issues)

## 📈 Changelog

### Version 1.0.0

**New Features:**
- ✅ Complete rewrite with type safety and better error handling
- ✅ Configuration class for advanced setup options
- ✅ Custom exception classes for different error scenarios  
- ✅ Rate limiting and retry logic
- ✅ Comprehensive PHPDoc documentation
- ✅ Support for all major ActiveCampaign API v3 endpoints
- ✅ Backward compatibility with existing implementations

**Improvements:**
- ✅ Better validation of API credentials and URLs
- ✅ Configurable timeouts and retry behavior
- ✅ Improved error messages with context information
- ✅ PSR-4 autoloading and PSR-12 coding standards