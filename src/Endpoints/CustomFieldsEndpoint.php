<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

class CustomFieldsEndpoint extends ActiveCampaignClient
{
    public function create(array $data)
    {
        return $this->request('POST', '/api/3/fields', ['json' => $data]);
    }

    public function get(int $id)
    {
        return $this->request('GET', "/api/3/fields/{$id}");
    }

    public function list(array $queryParams = [])
    {
        return $this->request('GET', '/api/3/fields', ['query' => $queryParams]);
    }

    public function update(int $id, array $data)
    {
        return $this->request('PUT', "/api/3/fields/{$id}", ['json' => $data]);
    }

    public function delete(int $id)
    {
        return $this->request('DELETE', "/api/3/fields/{$id}");
    }

    public function addToGroup(array $data)
    {
        return $this->request('POST', '/api/3/groupMembers', ['json' => $data]);
    }

    public function getFieldGroup(int $groupId)
    {
        return $this->request('GET', "/api/3/groupMembers/{$groupId}");
    }

    public function updateFieldGroup(int $groupId, array $data)
    {
        return $this->request('PUT', "/api/3/groupMembers/{$groupId}", ['json' => $data]);
    }

    public function deleteFromGroup(int $groupId)
    {
        return $this->request('DELETE', "/api/3/groupMembers/{$groupId}");
    }

    public function addOptions(array $data)
    {
        return $this->request('POST', '/api/3/fieldOption/bulk', ['json' => $data]);
    }
}
