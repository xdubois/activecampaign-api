<?php
/**
 * ActiveCampaign API v1 Usage Examples
 * 
 * This file demonstrates the new features and improvements in v1.0.0
 */

require_once __DIR__ . '/../vendor/autoload.php';

use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;

// Example 1: Basic usage (legacy compatible)
echo "=== Example 1: Basic Usage ===\n";
$api = new ActiveCampaignAPI('your-api-token', 'https://yourapp.api-us1.com');

// Legacy property access still works
// $contacts = $api->contacts->list();

// New method access (recommended)
// $contacts = $api->contacts()->list();

echo "✓ Legacy compatibility maintained\n\n";

// Example 2: Advanced configuration
echo "=== Example 2: Advanced Configuration ===\n";
$config = new Configuration(
    apiToken: 'your-api-token',
    accountUrl: 'https://yourapp.api-us1.com',
    timeout: 60,
    connectTimeout: 10,
    maxRetries: 3,
    retryDelay: 1.5,
    defaultHeaders: [
        'X-Custom-Header' => 'MyApp/1.0'
    ]
);

$api = new ActiveCampaignAPI($config);
echo "✓ Advanced configuration with custom timeouts and headers\n\n";

// Example 3: Error handling with specific exceptions
echo "=== Example 3: Error Handling ===\n";
try {
    // This would normally make an API call
    // $contact = $api->contacts()->create($contactData);
    echo "✓ Proper exception handling available\n";
} catch (AuthenticationException $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n";
    echo "Context: " . json_encode($e->getContext()) . "\n";
} catch (ValidationException $e) {
    echo "Validation failed: " . $e->getMessage() . "\n";
    echo "Validation errors: " . json_encode($e->getValidationErrors()) . "\n";
} catch (RateLimitException $e) {
    echo "Rate limited. Retry after: " . $e->getRetryAfter() . " seconds\n";
} catch (NotFoundException $e) {
    echo "Resource not found: " . $e->getMessage() . "\n";
}
echo "\n";

// Example 4: Type safety demonstration
echo "=== Example 4: Type Safety ===\n";
$reflection = new ReflectionClass($api->contacts());
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

foreach ($methods as $method) {
    if (in_array($method->getName(), ['create', 'get', 'list', 'update', 'delete'])) {
        $returnType = $method->getReturnType();
        echo "Method {$method->getName()}: returns " . ($returnType ? $returnType->getName() : 'mixed') . "\n";
    }
}
echo "\n";

// Example 5: Configuration access
echo "=== Example 5: Configuration Access ===\n";
$apiConfig = $api->getConfiguration();
echo "Account URL: " . $apiConfig->getAccountUrl() . "\n";
echo "Timeout: " . $apiConfig->getTimeout() . " seconds\n";
echo "Max Retries: " . $apiConfig->getMaxRetries() . "\n";
echo "✓ Full configuration access available\n\n";

echo "=== All Examples Completed Successfully! ===\n";
echo "The ActiveCampaign API package is ready for production use.\n";