<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Tags endpoint for managing tags. Use ContactsEndpoint::addTag() to apply a tag to a contact.
 *
 * @link https://developers.activecampaign.com/reference/tags
 */
class TagsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new tag.
     *
     * @param array<string, mixed> $data Payload with a top-level "tag" key (tag, tagType, description)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-new-tag
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/tags', ['json' => $data]);
    }

    /**
     * Retrieve a specific tag.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-tag
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/tags/{$id}");
    }

    /**
     * List tags with optional filtering.
     *
     * @param array<string, mixed> $queryParams e.g. ['search' => 'partial'] or ['filters[tag]' => 'exact name']
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-all-tags
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/tags', ['query' => $queryParams]);
    }

    /**
     * Update an existing tag.
     *
     * @param array<string, mixed> $data Payload with a top-level "tag" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-tag
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/tags/{$id}", ['json' => $data]);
    }

    /**
     * Delete a tag.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-tag
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/tags/{$id}");
    }
}
