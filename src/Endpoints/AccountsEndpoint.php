<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Configuration;

/**
 * Accounts endpoint for managing accounts in ActiveCampaign
 * 
 * @link https://developers.activecampaign.com/reference/account
 */
class AccountsEndpoint extends ActiveCampaignClient
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
     * Create a new account
     * 
     * @param array $data Account data
     * @return array The created account data
     * @link https://developers.activecampaign.com/reference/create-an-account
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/accounts', ['json' => $data]);
    }

    /**
     * Retrieve a specific account
     * 
     * @param int $id Account ID
     * @return array The account data
     * @link https://developers.activecampaign.com/reference/get-an-account
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/accounts/{$id}");
    }

    /**
     * List accounts with optional filtering
     * 
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of accounts with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-accounts
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/accounts', ['query' => $queryParams]);
    }

    /**
     * Update an existing account
     * 
     * @param int $id Account ID
     * @param array $data Updated account data
     * @return array The updated account data
     * @link https://developers.activecampaign.com/reference/update-an-account
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/accounts/{$id}", ['json' => $data]);
    }

    /**
     * Delete an account
     * 
     * @param int $id Account ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-an-account
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/accounts/{$id}");
    }

    /**
     * Bulk delete multiple accounts
     * 
     * @param array $ids Array of account IDs to delete
     * @return array Confirmation of bulk deletion
     * @link https://developers.activecampaign.com/reference/bulk-delete-accounts
     */
    public function bulkDelete(array $ids): array
    {
        return $this->request('DELETE', '/api/3/accounts/bulk_delete', ['query' => ['ids' => $ids]]);
    }

    /**
     * Create a note for an account
     * 
     * @param int $accountId Account ID
     * @param array $data Note data
     * @return array The created note data
     * @link https://developers.activecampaign.com/reference/create-a-note
     */
    public function createNote(int $accountId, array $data): array
    {
        return $this->request('POST', "/api/3/accounts/{$accountId}/notes", ['json' => $data]);
    }

    /**
     * Update a note for an account
     * 
     * @param int $accountId Account ID
     * @param int $noteId Note ID
     * @param array $data Updated note data
     * @return array The updated note data
     * @link https://developers.activecampaign.com/reference/update-a-note
     */
    public function updateNote(int $accountId, int $noteId, array $data): array
    {
        return $this->request('PUT', "/api/3/accounts/{$accountId}/notes/{$noteId}", ['json' => $data]);
    }
}
