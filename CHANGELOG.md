# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-XX

### Added
- **Configuration Class**: New `Configuration` class for advanced setup options including timeouts, retries, and custom headers
- **Custom Exception Classes**: Specific exception classes for different error scenarios:
  - `AuthenticationException` for 401/403 errors
  - `RateLimitException` for 429 errors with retry-after support
  - `ValidationException` for 422 errors with validation details
  - `NotFoundException` for 404 errors
  - `ActiveCampaignException` as base exception with context support
- **Type Safety**: Full type hints and return type declarations for all methods
- **Rate Limiting**: Built-in rate limit handling and automatic retry logic
- **Comprehensive Documentation**: Complete PHPDoc documentation with links to official API documentation
- **Error Context**: Exception classes now provide additional context information about failures
- **URL Validation**: Automatic validation of ActiveCampaign API URLs
- **User Agent**: Default User-Agent header for better API tracking
- **Composer Scripts**: Added scripts for testing, code quality checks, and static analysis

### Changed
- **Constructor Flexibility**: Main `ActiveCampaignAPI` class now accepts either `Configuration` object or legacy string parameters
- **Protected Properties**: Endpoint properties are now protected with public getter methods
- **Enhanced Error Handling**: Much more robust error handling with specific exceptions instead of generic error arrays
- **Improved HTTP Client**: Better handling of different HTTP status codes and error scenarios
- **Method Access**: Added new method-style access (`$api->contacts()`) alongside legacy property access (`$api->contacts`)

### Improved
- **Backward Compatibility**: All existing code continues to work without changes
- **Request Retry Logic**: Intelligent retry logic that doesn't retry on client errors (4xx except 429)
- **Connection Handling**: Separate connection and request timeout configurations
- **Error Messages**: More descriptive error messages with proper context
- **Code Quality**: Follows PSR-12 coding standards and includes static analysis tools

### Security
- **Credential Validation**: API tokens and URLs are validated before use
- **Safe URL Handling**: Automatic URL normalization and validation
- **Protected Credentials**: Better encapsulation of sensitive information

### Development
- **Development Dependencies**: Added PHPUnit, PHPStan, and PHP_CodeSniffer for quality assurance
- **Autoloading**: Improved autoloading configuration with development namespace
- **Testing Infrastructure**: Foundation for comprehensive test suite

## [0.x.x] - Previous Versions

Previous versions provided basic functionality for ActiveCampaign API integration with minimal error handling and basic HTTP client functionality.

### Legacy Features
- Basic CRUD operations for Contacts, Deals, Accounts, Custom Fields, and Custom Objects
- Simple Guzzle HTTP client integration
- PSR-4 autoloading
- Basic error handling returning error arrays