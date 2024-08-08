<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

class AccountsEndpoint extends ActiveCampaignClient
{
    public function create(array $data)
    {
        return $this->request('POST', '/api/3/accounts', ['json' => $data]);
    }

    public function get(int $id)
    {
        return $this->request('GET', "/api/3/accounts/{$id}");
    }

    public function list(array $queryParams = [])
    {
        return $this->request('GET', '/api/3/accounts', ['query' => $queryParams]);
    }

    public function update(int $id, array $data)
    {
        return $this->request('PUT', "/api/3/accounts/{$id}", ['json' => $data]);
    }

    public function delete(int $id)
    {
        return $this->request('DELETE', "/api/3/accounts/{$id}");
    }

    public function bulkDelete(array $ids)
    {
        return $this->request('DELETE', '/api/3/accounts/bulk_delete', ['query' => ['ids' => $ids]]);
    }

    public function createNote(int $accountId, array $data)
    {
        return $this->request('POST', "/api/3/accounts/{$accountId}/notes", ['json' => $data]);
    }

    public function updateNote(int $accountId, int $noteId, array $data)
    {
        return $this->request('PUT', "/api/3/accounts/{$accountId}/notes/{$noteId}", ['json' => $data]);
    }
}
