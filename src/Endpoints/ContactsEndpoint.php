<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Contacts endpoint for managing contacts in ActiveCampaign.
 *
 * @link https://developers.activecampaign.com/reference/contact
 */
class ContactsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new contact.
     *
     * @param array<string, mixed> $data Payload with a top-level "contact" key
     * @return array<mixed> The created contact
     * @link https://developers.activecampaign.com/reference/create-a-new-contact
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/contacts', ['json' => $data]);
    }

    /**
     * Retrieve a specific contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-contact
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/contacts/{$id}");
    }

    /**
     * List, search and filter contacts.
     *
     * @param array<string, mixed> $queryParams e.g. ['email' => ..., 'listid' => ..., 'tagid' => ..., 'limit' => 100]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-contacts
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/contacts', ['query' => $queryParams]);
    }

    /**
     * Update an existing contact.
     *
     * @param array<string, mixed> $data Payload with a top-level "contact" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-contact-new
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/contacts/{$id}", ['json' => $data]);
    }

    /**
     * Delete a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-contact
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/contacts/{$id}");
    }

    /**
     * Sync a contact: create it, or update it when the email already exists.
     *
     * @param array<string, mixed> $data Payload with a top-level "contact" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/sync-a-contacts-data
     */
    public function sync(array $data): array
    {
        return $this->request('POST', '/api/3/contact/sync', ['json' => $data]);
    }

    /**
     * Bulk sync (upsert by email) up to 250 contacts in one request.
     *
     * @param array<string, mixed> $data Payload with a top-level "contacts" list
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-sync-contacts
     */
    public function bulkSync(array $data): array
    {
        return $this->request('POST', '/api/3/contact/bulkSync', ['json' => $data]);
    }

    /**
     * Bulk import up to 250 contacts asynchronously.
     *
     * @param array<string, mixed> $data Payload with a top-level "contacts" list (plus optional callback, exclude_automations, ...)
     * @return array<mixed> Contains the batchId of the import
     * @link https://developers.activecampaign.com/reference/bulk-import-contacts
     */
    public function bulkImport(array $data): array
    {
        return $this->request('POST', '/api/3/import/bulk_import', ['json' => $data]);
    }

    /**
     * Retrieve the status of a bulk import batch.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-import-status-info
     */
    public function getBulkImportStatus(string $batchId): array
    {
        return $this->request('GET', '/api/3/import/info', ['query' => ['batchId' => $batchId]]);
    }

    /**
     * Merge two contacts.
     *
     * @param array<string, mixed> $data
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/merge-contacts
     */
    public function merge(array $data): array
    {
        return $this->request('POST', '/api/3/contact/merge', ['json' => $data]);
    }

    /**
     * Retrieve the deals of a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-contact-deals
     */
    public function getDeals(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/deals");
    }

    /**
     * Retrieve the notes of a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-contact-notes
     */
    public function getNotes(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/notes");
    }

    /**
     * Retrieve the activities of a contact.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-contact-activities
     */
    public function getActivities(int $contactId, array $queryParams = []): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/activities", ['query' => $queryParams]);
    }

    // -------------------------------------------------------------------------
    // Tags
    // -------------------------------------------------------------------------

    /**
     * Add a tag to a contact.
     *
     * @param int $contactId Contact ID
     * @param int $tagId Tag ID (not the tag name)
     * @return array<mixed> The contactTag association (201 created, 200 already applied)
     * @link https://developers.activecampaign.com/reference/create-contact-tag
     */
    public function addTag(int $contactId, int $tagId): array
    {
        return $this->request('POST', '/api/3/contactTags', ['json' => [
            'contactTag' => ['contact' => $contactId, 'tag' => $tagId],
        ]]);
    }

    /**
     * Remove a tag from a contact using the contactTag association ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/remove-a-contacts-tag
     */
    public function removeTag(int $contactTagId): array
    {
        return $this->request('DELETE', "/api/3/contactTags/{$contactTagId}");
    }

    /**
     * Remove a tag from a contact using the contact ID and the tag ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/remove-a-tag-from-a-contact-by-id
     */
    public function removeTagById(int $contactId, int $tagId): array
    {
        return $this->request('DELETE', "/api/3/contacts/{$contactId}/tags/{$tagId}");
    }

    /**
     * Remove a tag from a contact using the contact ID and the tag name.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/remove-a-tag-from-a-contact-by-name
     */
    public function removeTagByName(int $contactId, string $tagName): array
    {
        return $this->request('DELETE', "/api/3/contacts/{$contactId}/tags", ['query' => ['tag' => $tagName]]);
    }

    /**
     * Retrieve the tags applied to a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/contact
     */
    public function getTags(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/contactTags");
    }

    // -------------------------------------------------------------------------
    // Lists
    // -------------------------------------------------------------------------

    /**
     * Subscribe or unsubscribe a contact to/from a list.
     *
     * @param int $contactId Contact ID
     * @param int $listId List ID
     * @param int $status 1 to subscribe, 2 to unsubscribe
     * @param array<string, mixed> $extra Optional fields (sourceid, responder, sendInstantAutoresponder, ...)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-list-status-for-contact
     */
    public function updateListStatus(int $contactId, int $listId, int $status, array $extra = []): array
    {
        return $this->request('POST', '/api/3/contactLists', ['json' => [
            'contactList' => array_merge($extra, [
                'list' => $listId,
                'contact' => $contactId,
                'status' => $status,
            ]),
        ]]);
    }

    /**
     * Retrieve the list memberships of a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-contact-list-memberships
     */
    public function getListMemberships(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/contactLists");
    }

    // -------------------------------------------------------------------------
    // Automations
    // -------------------------------------------------------------------------

    /**
     * Add a contact to an automation.
     *
     * @return array<mixed> The contactAutomation association
     * @link https://developers.activecampaign.com/reference/create-new-contactautomation
     */
    public function addToAutomation(int $contactId, int $automationId): array
    {
        return $this->request('POST', '/api/3/contactAutomations', ['json' => [
            'contactAutomation' => ['contact' => $contactId, 'automation' => $automationId],
        ]]);
    }

    /**
     * Remove a contact from an automation using the contactAutomation association ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-contactautomation
     */
    public function removeFromAutomation(int $contactAutomationId): array
    {
        return $this->request('DELETE', "/api/3/contactAutomations/{$contactAutomationId}");
    }

    /**
     * Retrieve the automations a contact is in.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-contactautomations-for-contact
     */
    public function getAutomations(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/contactAutomations");
    }

    // -------------------------------------------------------------------------
    // Custom field values
    // -------------------------------------------------------------------------

    /**
     * Retrieve the custom field values of a contact.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-contact-field-values
     */
    public function getCustomFieldValues(int $contactId): array
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/fieldValues");
    }

    /**
     * Set a custom field value for a contact (creates it, or updates the existing value).
     *
     * @param array<string, mixed> $data Payload with a top-level "fieldValue" key: {contact, field, value}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-fieldvalue
     */
    public function updateCustomFieldValue(array $data): array
    {
        return $this->request('POST', '/api/3/fieldValues', ['json' => $data]);
    }

    /**
     * Update a custom field value by its ID. The payload must contain contact, field and value.
     *
     * @param array<string, mixed> $data Payload with a top-level "fieldValue" key: {contact, field, value}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-custom-field-value-for-contact
     */
    public function updateCustomFieldValueById(int $fieldValueId, array $data): array
    {
        return $this->request('PUT', "/api/3/fieldValues/{$fieldValueId}", ['json' => $data]);
    }

    /**
     * Retrieve a custom field value by its ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-fieldvalues
     */
    public function getCustomFieldValue(int $fieldValueId): array
    {
        return $this->request('GET', "/api/3/fieldValues/{$fieldValueId}");
    }

    /**
     * Delete a custom field value by its ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-fieldvalue-1
     */
    public function deleteCustomFieldValue(int $fieldValueId): array
    {
        return $this->request('DELETE', "/api/3/fieldValues/{$fieldValueId}");
    }

    /**
     * List all custom field values.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[fieldid]' => 1, 'limit' => 100]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-custom-field-values
     */
    public function listAllCustomFieldValues(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/fieldValues', ['query' => $queryParams]);
    }
}
