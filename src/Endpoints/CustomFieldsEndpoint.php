<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Configuration;

/**
 * Custom Fields endpoint for managing custom fields in ActiveCampaign
 * 
 * @link https://developers.activecampaign.com/reference/custom-fields
 */
class CustomFieldsEndpoint extends ActiveCampaignClient
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
     * Create a new custom field
     * 
     * @param array $data Custom field data
     * @return array The created custom field data
     * @link https://developers.activecampaign.com/reference/create-a-field
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/fields', ['json' => $data]);
    }

    /**
     * Retrieve a specific custom field
     * 
     * @param int $id Custom field ID
     * @return array The custom field data
     * @link https://developers.activecampaign.com/reference/retrieve-a-field
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/fields/{$id}");
    }

    /**
     * List custom fields with optional filtering
     * 
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of custom fields with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-fields
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/fields', ['query' => $queryParams]);
    }

    /**
     * Update an existing custom field
     * 
     * @param int $id Custom field ID
     * @param array $data Updated custom field data
     * @return array The updated custom field data
     * @link https://developers.activecampaign.com/reference/update-a-field
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/fields/{$id}", ['json' => $data]);
    }

    /**
     * Delete a custom field
     * 
     * @param int $id Custom field ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-a-field
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/fields/{$id}");
    }

    /**
     * Add a field to a group
     * 
     * @param array $data Group member data
     * @return array The created group member data
     * @link https://developers.activecampaign.com/reference/create-a-groupmember
     */
    public function addToGroup(array $data): array
    {
        return $this->request('POST', '/api/3/groupMembers', ['json' => $data]);
    }

    /**
     * Get a field group
     * 
     * @param int $groupId Group ID
     * @return array The group data
     * @link https://developers.activecampaign.com/reference/retrieve-a-groupmember
     */
    public function getFieldGroup(int $groupId): array
    {
        return $this->request('GET', "/api/3/groupMembers/{$groupId}");
    }

    /**
     * Update a field group
     * 
     * @param int $groupId Group ID
     * @param array $data Updated group data
     * @return array The updated group data
     * @link https://developers.activecampaign.com/reference/update-a-groupmember
     */
    public function updateFieldGroup(int $groupId, array $data): array
    {
        return $this->request('PUT', "/api/3/groupMembers/{$groupId}", ['json' => $data]);
    }

    /**
     * Remove a field from a group
     * 
     * @param int $groupId Group ID
     * @return array Confirmation of removal
     * @link https://developers.activecampaign.com/reference/delete-a-groupmember
     */
    public function deleteFromGroup(int $groupId): array
    {
        return $this->request('DELETE', "/api/3/groupMembers/{$groupId}");
    }

    /**
     * Add options to a field (bulk operation)
     * 
     * @param array $data Field options data
     * @return array The created field options data
     * @link https://developers.activecampaign.com/reference/create-a-fieldoptions-bulk
     */
    public function addOptions(array $data): array
    {
        return $this->request('POST', '/api/3/fieldOption/bulk', ['json' => $data]);
    }
}
