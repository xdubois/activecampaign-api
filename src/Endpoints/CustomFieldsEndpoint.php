<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Custom Fields endpoint for managing contact custom fields, field options,
 * field groups and field/list relationships.
 *
 * @link https://developers.activecampaign.com/reference/custom-fields
 */
class CustomFieldsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new contact custom field.
     *
     * @param array<string, mixed> $data Payload with a top-level "field" key (title, type)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-contact-custom-field
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/fields', ['json' => $data]);
    }

    /**
     * Retrieve a specific custom field.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-custom-field-contact
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/fields/{$id}");
    }

    /**
     * List custom fields with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-fields
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/fields', ['query' => $queryParams]);
    }

    /**
     * Update an existing custom field.
     *
     * @param array<string, mixed> $data Payload with a top-level "field" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-field
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/fields/{$id}", ['json' => $data]);
    }

    /**
     * Delete a custom field.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-field
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/fields/{$id}");
    }

    // -------------------------------------------------------------------------
    // Field groups (groupMembers)
    // -------------------------------------------------------------------------

    /**
     * List all field group memberships.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-all-custom-field-groups
     */
    public function listGroupMembers(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/groupMembers', ['query' => $queryParams]);
    }

    /**
     * Add a field to a field group.
     *
     * @param array<string, mixed> $data Payload with a top-level "groupMember" key (rel_id, group_id, ordernum)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/add-custom-field-to-field-group
     */
    public function addToGroup(array $data): array
    {
        return $this->request('POST', '/api/3/groupMembers', ['json' => $data]);
    }

    /**
     * Retrieve a field group membership.
     *
     * @param int $groupMemberId Group member ID
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-custom-field-to-field-group
     */
    public function getFieldGroup(int $groupMemberId): array
    {
        return $this->request('GET', "/api/3/groupMembers/{$groupMemberId}");
    }

    /**
     * Update a field group membership.
     *
     * @param int $groupMemberId Group member ID
     * @param array<string, mixed> $data Payload with a top-level "groupMember" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-custom-field-field-group
     */
    public function updateFieldGroup(int $groupMemberId, array $data): array
    {
        return $this->request('PUT', "/api/3/groupMembers/{$groupMemberId}", ['json' => $data]);
    }

    /**
     * Remove a field from a field group.
     *
     * @param int $groupMemberId Group member ID
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-custom-field-field-group
     */
    public function deleteFromGroup(int $groupMemberId): array
    {
        return $this->request('DELETE', "/api/3/groupMembers/{$groupMemberId}");
    }

    // -------------------------------------------------------------------------
    // Field options and list relationships
    // -------------------------------------------------------------------------

    /**
     * Add options to a field (bulk operation).
     *
     * @param array<string, mixed> $data Payload with a top-level "fieldOptions" list of {field, value, label?, orderid?, isdefault?}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-custom-field-options
     */
    public function addOptions(array $data): array
    {
        return $this->request('POST', '/api/3/fieldOption/bulk', ['json' => $data]);
    }

    /**
     * Make a custom field available to a list.
     *
     * @param array<string, mixed> $data Payload with a top-level "fieldRel" key (field, relid)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-custom-field-relationship-to-lists
     */
    public function addToList(array $data): array
    {
        return $this->request('POST', '/api/3/fieldRels', ['json' => $data]);
    }

    /**
     * Remove a custom field / list relationship.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-custom-field-relationship-to-lists
     */
    public function removeFromList(int $fieldRelId): array
    {
        return $this->request('DELETE', "/api/3/fieldRels/{$fieldRelId}");
    }
}
