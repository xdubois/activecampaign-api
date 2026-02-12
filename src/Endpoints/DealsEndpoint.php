<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Configuration;

/**
 * Deals endpoint for managing deals in ActiveCampaign
 * 
 * @link https://developers.activecampaign.com/reference/deal
 */
class DealsEndpoint extends ActiveCampaignClient
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
     * Create a new deal
     * 
     * @param array $data Deal data
     * @return array The created deal data
     * @link https://developers.activecampaign.com/reference/create-a-deal-new
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/deals', ['json' => $data]);
    }

    /**
     * Retrieve a specific deal
     * 
     * @param int $id Deal ID
     * @return array The deal data
     * @link https://developers.activecampaign.com/reference/retrieve-a-deal
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/deals/{$id}");
    }

    /**
     * List deals with optional filtering
     * 
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of deals with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-deals
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/deals', ['query' => $queryParams]);
    }

    /**
     * Update an existing deal
     * 
     * @param int $id Deal ID
     * @param array $data Updated deal data
     * @return array The updated deal data
     * @link https://developers.activecampaign.com/reference/update-a-deal-new
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/deals/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal
     * 
     * @param int $id Deal ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-a-deal
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/deals/{$id}");
    }

    /**
     * Create a note for a deal
     * 
     * @param int $dealId Deal ID
     * @param array $data Note data
     * @return array The created note data
     * @link https://developers.activecampaign.com/reference/create-a-note
     */
    public function createNote(int $dealId, array $data): array
    {
        return $this->request('POST', "/api/3/deals/{$dealId}/notes", ['json' => $data]);
    }

    /**
     * Update a note for a deal
     * 
     * @param int $dealId Deal ID
     * @param int $noteId Note ID
     * @param array $data Updated note data
     * @return array The updated note data
     * @link https://developers.activecampaign.com/reference/update-a-note
     */
    public function updateNote(int $dealId, int $noteId, array $data): array
    {
        return $this->request('PUT', "/api/3/deals/{$dealId}/notes/{$noteId}", ['json' => $data]);
    }
}
