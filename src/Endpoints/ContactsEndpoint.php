<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Configuration;

/**
 * Contacts endpoint for managing contacts in ActiveCampaign
 * 
 * @link https://developers.activecampaign.com/reference/contact
 */
class ContactsEndpoint extends ActiveCampaignClient
{
    /**
     * Constructor - supports both new Configuration object and legacy parameters for backward compatibility
     */
    public function __construct($apiTokenOrConfig, ?string $accountUrl = null)
    {
        if ($apiTokenOrConfig instanceof Configuration) {
            parent::__construct($apiTokenOrConfig);
        } else {
            // Legacy constructor for backward compatibility
            parent::__construct($apiTokenOrConfig, $accountUrl);
        }
    }

    /**
     * Create a new contact
     * 
     * @param array $data Contact data
     * @return array The created contact data
     * @link https://developers.activecampaign.com/reference/create-contact
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/contacts', ['json' => $data]);
    }

    /**
     * Retrieve a specific contact
     * 
     * @param int $id Contact ID
     * @return array The contact data
     * @link https://developers.activecampaign.com/reference/get-contact
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/contacts/{$id}");
    }

    /**
     * List contacts with optional filtering
     * 
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of contacts with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-contacts
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/contacts', ['query' => $queryParams]);
    }

    /**
     * Update an existing contact
     * 
     * @param int $id Contact ID
     * @param array $data Updated contact data
     * @return array The updated contact data
     * @link https://developers.activecampaign.com/reference/update-list-status-for-contact
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/contacts/{$id}", ['json' => $data]);
    }

    /**
     * Delete a contact
     * 
     * @param int $id Contact ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-contact
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/contacts/{$id}");
    }

    /**
     * Sync a contact (create or update)
     * 
     * @param array $data Contact data
     * @return array The synced contact data
     * @link https://developers.activecampaign.com/reference/sync-a-contacts-data
     */
    public function sync(array $data): array
    {
        return $this->request('POST', '/api/3/contact/sync', ['json' => $data]);
    }

    /**
     * Get custom field values for a specific contact
     * 
     * @param int $contactId Contact ID
     * @return array Custom field values
     * @link https://developers.activecampaign.com/reference/list-all-custom-field-values
     */
    public function getCustomFieldValues(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/fieldValues");
    }

    /**
     * Update a custom field value for a contact
     * 
     * @param array $data Field value data
     * @return array The updated field value
     * @link https://developers.activecampaign.com/reference/create-a-custom-field-value
     */
    public function updateCustomFieldValue(array $data): array
    {
        return $this->request('POST', '/api/3/fieldValues', ['json' => $data]);
    }

    /**
     * List all custom field values
     * 
     * @return array All custom field values
     * @link https://developers.activecampaign.com/reference/list-all-custom-field-values
     */
    public function listAllCustomFieldValues(): array
    {
        return $this->request('GET', '/api/3/fieldValues');
    }
}
