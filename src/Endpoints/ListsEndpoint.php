<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Lists endpoint for managing lists. Use ContactsEndpoint::updateListStatus() to (un)subscribe contacts.
 *
 * @link https://developers.activecampaign.com/reference/lists
 */
class ListsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new list.
     *
     * @param array<string, mixed> $data Payload with a top-level "list" key (name, stringid, sender_url, sender_reminder)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-new-list
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/lists', ['json' => $data]);
    }

    /**
     * Retrieve a specific list.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-list
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/lists/{$id}");
    }

    /**
     * List all lists with optional filtering.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[name]' => 'Newsletter', 'limit' => 100]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-all-lists
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/lists', ['query' => $queryParams]);
    }

    /**
     * Update an existing list.
     *
     * @param array<string, mixed> $data Payload with a top-level "list" key (name is always required)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-list
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/lists/{$id}", ['json' => $data]);
    }

    /**
     * Delete a list.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-list
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/lists/{$id}");
    }
}
