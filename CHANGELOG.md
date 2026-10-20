# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2026-10-19

### Changed (breaking)
- **PHP 8.1+ required**: PHP 7.4, 8.0 and 8.1 are end of life; the package now requires PHP `^8.1` and uses native union types, `match`, `never` and `#[\SensitiveParameter]`.
- **Guzzle 7.9+ or 8.x**: the dependency constraint is now `guzzlehttp/guzzle: ^7.9 || ^8.0`. Guzzle 8 (released July 2026) is fully supported, including its new exception hierarchy.
- **Retry policy**: only rate limits (429), server errors (500/502/503/504) and no-response network failures are retried. Other failures (redirect loops, request body errors, ...) are surfaced immediately instead of being retried blindly.
- **Backoff**: retries now use exponential backoff (`retryDelay`, `2 × retryDelay`, `4 × retryDelay`, ...) instead of a linear delay, and honour the `Retry-After` header sent with 429/503 responses. Every delay is capped at 60 seconds (`ActiveCampaignClient::MAX_RETRY_DELAY_SECONDS`).
- **Server errors** (5xx) now parse the JSON error body and expose `status_code`, `response_body` and `error_data` in the exception context, like client errors already did.
- **Non-JSON success bodies**: an empty body (e.g. `204 No Content`) now returns `[]` instead of throwing "Invalid JSON response"; scalar JSON bodies return `[]` instead of causing a `TypeError`.
- **Custom objects `updateRecord()`**: the API has no `PUT /customObjects/records/{schemaId}/{recordId}` endpoint. The method now uses the documented create-or-update endpoint (`POST /customObjects/records/{schemaId}`) and injects the record ID into the payload.
- **Accounts `bulkDelete()`** sends `ids[]=1&ids[]=2` as documented (previously `ids[0]=1&ids[1]=2`).
- `ActiveCampaignAPI::__get()` is now typed (`string $name` / `ActiveCampaignClient` return) and `__isset()` was added.
- The endpoint classes no longer override the constructor; the signature `(Configuration|string $apiTokenOrConfig, ?string $accountUrl = null, ?ClientInterface $httpClient = null)` is inherited from `ActiveCampaignClient`.
- `declare(strict_types=1)` was added to every file.

### Added
- **Shared, injectable HTTP client**: `ActiveCampaignAPI` now creates a single Guzzle client shared by every endpoint (instead of one client per endpoint) and accepts a pre-configured `GuzzleHttp\ClientInterface` as third constructor argument, which makes the SDK testable with Guzzle's `MockHandler`. `getHttpClient()` exposes it.
- **Live configuration**: timeouts and headers are applied per request, so `Configuration` setters (`setTimeout()`, `setDefaultHeaders()`, ...) take effect after the client has been created.
- **Exceptions**: `getStatusCode()` and `getResponseBody()` helpers on `ActiveCampaignException`; the context now also contains the request `method` and `uri`; JSON:API style `errors[].title` messages are joined into the exception message.
- `Accept: application/json` default header; the `User-Agent` version is derived from `ActiveCampaignAPI::VERSION`.
- Header values are normalised to strings (Guzzle 8 rejects non-string header values).
- **New endpoints**: `tags()`, `lists()`, `webhooks()` and `users()` (`users()->me()` is a cheap credentials check).
- **Contacts**: `bulkSync()`, `bulkImport()`, `getBulkImportStatus()`, `merge()`, `getDeals()`, `getNotes()`, `getActivities()`, `addTag()`, `removeTag()`, `removeTagById()`, `removeTagByName()`, `getTags()`, `updateListStatus()`, `getListMemberships()`, `addToAutomation()`, `removeFromAutomation()`, `getAutomations()`, `getCustomFieldValue()`, `updateCustomFieldValueById()`, `deleteCustomFieldValue()`; `listAllCustomFieldValues()` accepts query parameters.
- **Deals**: pipelines (`listPipelines()`, `getPipeline()`, `createPipeline()`, `updatePipeline()`, `deletePipeline()`), stages (`listStages()`, `getStage()`, `createStage()`, `updateStage()`, `deleteStage()`, `moveDealsToStage()`), tasks (`listTasks()`, `getTask()`, `createTask()`, `updateTask()`, `deleteTask()`), custom field definitions and values (`listCustomFields()`, `getCustomField()`, `createCustomField()`, `updateCustomField()`, `deleteCustomField()`, `listCustomFieldValues()`, `getCustomFieldValue()`, `createCustomFieldValue()`, `updateCustomFieldValue()`, `deleteCustomFieldValue()`, `bulkCreateCustomFieldValues()`, `bulkUpdateCustomFieldValues()`) and `bulkUpdateOwners()`.
- **Accounts**: contact associations (`addContact()`, `listContactAssociations()`, `getContactAssociation()`, `updateContactAssociation()`, `removeContact()`) and custom field definitions/values (same method names as deals).
- **Custom fields**: `listGroupMembers()`, `addToList()`, `removeFromList()`.
- **Custom objects**: `createOrUpdateRecord()`, `createPublicSchema()`, `createChildSchema()`, `deleteSchemaField()`, `getRecordByExternalId()`, `deleteRecordByExternalId()`.
- **Tooling**: PHPUnit test suite (184 tests, no network access needed), PHPStan at level max with strict rules, PHP CS Fixer (PER-CS 2.0) and a GitHub Actions workflow covering PHP 8.1 to 8.5 with lowest/highest dependencies and both Guzzle majors.

### Fixed
- Corrected the `@link` documentation URLs of most endpoint methods; many pointed at renamed or unrelated reference pages (for example `ContactsEndpoint::update()` linked to "Update list status for contact").
- `ActiveCampaignAPI`/`ActiveCampaignClient` now throw an `InvalidArgumentException` with a clear message when the legacy constructor is used without an account URL, instead of a `TypeError`.
- Response bodies of failed requests are read from the beginning regardless of the stream position (Guzzle 8 no longer rewinds the body when building exception messages).

## [1.0.0] - 2026-02-12

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

### Security
- **Credential Validation**: API tokens and URLs are validated before use
- **Safe URL Handling**: Automatic URL normalization and validation
- **Protected Credentials**: Better encapsulation of sensitive information
