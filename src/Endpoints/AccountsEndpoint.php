<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Accounts endpoint for managing accounts, account/contact associations and account custom fields.
 *
 * @link https://developers.activecampaign.com/reference/account
 */
class AccountsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new account.
     *
     * @param array<string, mixed> $data Payload with a top-level "account" key (name required)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-an-account-new
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/accounts', ['json' => $data]);
    }

    /**
     * Retrieve a specific account.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-an-account
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/accounts/{$id}");
    }

    /**
     * List accounts with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-accounts
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/accounts', ['query' => $queryParams]);
    }

    /**
     * Update an existing account.
     *
     * @param array<string, mixed> $data Payload with a top-level "account" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-an-account-new
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/accounts/{$id}", ['json' => $data]);
    }

    /**
     * Delete an account.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-an-account
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/accounts/{$id}");
    }

    /**
     * Bulk delete accounts.
     *
     * The API expects repeated "ids[]" query parameters, which is how PHP/Guzzle
     * serialises a list under the "ids" key.
     *
     * @param int[] $ids
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-delete-accounts
     */
    public function bulkDelete(array $ids): array
    {
        $query = implode('&', array_map(static fn(int $id): string => 'ids[]=' . $id, array_values($ids)));

        return $this->request('DELETE', '/api/3/accounts/bulk_delete', ['query' => $query]);
    }

    // -------------------------------------------------------------------------
    // Notes
    // -------------------------------------------------------------------------

    /**
     * Create a note for an account.
     *
     * @param array<string, mixed> $data Payload with a top-level "note" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-an-account-note
     */
    public function createNote(int $accountId, array $data): array
    {
        return $this->request('POST', "/api/3/accounts/{$accountId}/notes", ['json' => $data]);
    }

    /**
     * Update a note for an account.
     *
     * @param array<string, mixed> $data Payload with a top-level "note" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-account-note
     */
    public function updateNote(int $accountId, int $noteId, array $data): array
    {
        return $this->request('PUT', "/api/3/accounts/{$accountId}/notes/{$noteId}", ['json' => $data]);
    }

    // -------------------------------------------------------------------------
    // Account / contact associations
    // -------------------------------------------------------------------------

    /**
     * Associate a contact with an account.
     *
     * @param array<string, mixed> $extra Optional fields (jobTitle)
     * @return array<mixed> The accountContact association
     * @link https://developers.activecampaign.com/reference/create-an-account-1
     */
    public function addContact(int $accountId, int $contactId, array $extra = []): array
    {
        return $this->request('POST', '/api/3/accountContacts', ['json' => [
            'accountContact' => array_merge($extra, ['account' => $accountId, 'contact' => $contactId]),
        ]]);
    }

    /**
     * List account/contact associations.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[account]' => 1] or ['filters[contact]' => 1]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-associations-1
     */
    public function listContactAssociations(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/accountContacts', ['query' => $queryParams]);
    }

    /**
     * Retrieve an account/contact association.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-an-association
     */
    public function getContactAssociation(int $accountContactId): array
    {
        return $this->request('GET', "/api/3/accountContacts/{$accountContactId}");
    }

    /**
     * Update an account/contact association (e.g. the job title).
     *
     * @param array<string, mixed> $data Payload with a top-level "accountContact" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-an-association-1
     */
    public function updateContactAssociation(int $accountContactId, array $data): array
    {
        return $this->request('PUT', "/api/3/accountContacts/{$accountContactId}", ['json' => $data]);
    }

    /**
     * Remove an account/contact association.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-an-association-1
     */
    public function removeContact(int $accountContactId): array
    {
        return $this->request('DELETE', "/api/3/accountContacts/{$accountContactId}");
    }

    // -------------------------------------------------------------------------
    // Custom fields (meta) and custom field values (data)
    // -------------------------------------------------------------------------

    /**
     * List all account custom field definitions.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-custom-fields-meta
     */
    public function listCustomFields(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/accountCustomFieldMeta', ['query' => $queryParams]);
    }

    /**
     * Retrieve an account custom field definition.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-custom-field-meta
     */
    public function getCustomField(int $id): array
    {
        return $this->request('GET', "/api/3/accountCustomFieldMeta/{$id}");
    }

    /**
     * Create an account custom field definition.
     *
     * @param array<string, mixed> $data Payload with a top-level "accountCustomFieldMetum" key (fieldLabel, fieldType)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-customfieldmeta
     */
    public function createCustomField(array $data): array
    {
        return $this->request('POST', '/api/3/accountCustomFieldMeta', ['json' => $data]);
    }

    /**
     * Update an account custom field definition.
     *
     * @param array<string, mixed> $data Payload with a top-level "accountCustomFieldMetum" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-custom-field
     */
    public function updateCustomField(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/accountCustomFieldMeta/{$id}", ['json' => $data]);
    }

    /**
     * Delete an account custom field definition.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-custom-field
     */
    public function deleteCustomField(int $id): array
    {
        return $this->request('DELETE', "/api/3/accountCustomFieldMeta/{$id}");
    }

    /**
     * List account custom field values.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[customerAccountId]' => 1]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-custom-field-values-2
     */
    public function listCustomFieldValues(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/accountCustomFieldData', ['query' => $queryParams]);
    }

    /**
     * Retrieve an account custom field value.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-custom-field-value
     */
    public function getCustomFieldValue(int $id): array
    {
        return $this->request('GET', "/api/3/accountCustomFieldData/{$id}");
    }

    /**
     * Create an account custom field value.
     *
     * @param array<string, mixed> $data Payload with a top-level "accountCustomFieldDatum" key (customerAccountId, customFieldId, fieldValue)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-custom-field-value
     */
    public function createCustomFieldValue(array $data): array
    {
        return $this->request('POST', '/api/3/accountCustomFieldData', ['json' => $data]);
    }

    /**
     * Update an account custom field value.
     *
     * @param array<string, mixed> $data Payload with a top-level "accountCustomFieldDatum" key (fieldValue)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-custom-field-value
     */
    public function updateCustomFieldValue(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/accountCustomFieldData/{$id}", ['json' => $data]);
    }

    /**
     * Delete an account custom field value.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-custom-field-value
     */
    public function deleteCustomFieldValue(int $id): array
    {
        return $this->request('DELETE', "/api/3/accountCustomFieldData/{$id}");
    }

    /**
     * Bulk create account custom field values.
     *
     * @param list<array<string, mixed>> $values List of {customerAccountId, customFieldId, fieldValue, fieldCurrency?}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-create-a-custom-account-field-value
     */
    public function bulkCreateCustomFieldValues(array $values): array
    {
        return $this->request('POST', '/api/3/accountCustomFieldData/bulkCreate', ['json' => $values]);
    }

    /**
     * Bulk update account custom field values.
     *
     * @param list<array<string, mixed>> $values List of {id, fieldValue, fieldCurrency?}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-update-a-custom-account-field-value
     */
    public function bulkUpdateCustomFieldValues(array $values): array
    {
        return $this->request('PATCH', '/api/3/accountCustomFieldData/bulkUpdate', ['json' => $values]);
    }
}
