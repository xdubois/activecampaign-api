<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Webhooks endpoint for managing webhooks.
 *
 * @link https://developers.activecampaign.com/reference/webhooks
 */
class WebhooksEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new webhook.
     *
     * @param array<string, mixed> $data Payload with a top-level "webhook" key (name, url, events, sources, listid?)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-webhook
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/webhooks', ['json' => $data]);
    }

    /**
     * Retrieve a specific webhook.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-webhook
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/webhooks/{$id}");
    }

    /**
     * List webhooks with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-a-list-of-webhooks
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/webhooks', ['query' => $queryParams]);
    }

    /**
     * Update an existing webhook.
     *
     * @param array<string, mixed> $data Payload with a top-level "webhook" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-webhook
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/webhooks/{$id}", ['json' => $data]);
    }

    /**
     * Delete a webhook.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-webhook
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/webhooks/{$id}");
    }

    /**
     * List the events a webhook can subscribe to.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-a-list-of-webhook-events
     */
    public function listEvents(): array
    {
        return $this->request('GET', '/api/3/webhook/events');
    }
}
