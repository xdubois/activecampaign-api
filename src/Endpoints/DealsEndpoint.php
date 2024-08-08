<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

class DealsEndpoint extends ActiveCampaignClient
{
    public function create(array $data)
    {
        return $this->request('POST', '/api/3/deals', ['json' => $data]);
    }

    public function get(int $id)
    {
        return $this->request('GET', "/api/3/deals/{$id}");
    }

    public function list(array $queryParams = [])
    {
        return $this->request('GET', '/api/3/deals', ['query' => $queryParams]);
    }

    public function update(int $id, array $data)
    {
        return $this->request('PUT', "/api/3/deals/{$id}", ['json' => $data]);
    }

    public function delete(int $id)
    {
        return $this->request('DELETE', "/api/3/deals/{$id}");
    }

    public function createNote(int $dealId, array $data)
    {
        return $this->request('POST', "/api/3/deals/{$dealId}/notes", ['json' => $data]);
    }

    public function updateNote(int $dealId, int $noteId, array $data)
    {
        return $this->request('PUT', "/api/3/deals/{$dealId}/notes/{$noteId}", ['json' => $data]);
    }
}
