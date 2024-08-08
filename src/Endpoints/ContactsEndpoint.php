<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

class ContactsEndpoint extends ActiveCampaignClient
{
    public function create(array $data)
    {
        return $this->request('POST', '/api/3/contacts', ['json' => $data]);
    }

    public function get(int $id)
    {
        return $this->request('GET', "/api/3/contacts/{$id}");
    }

    public function list(array $queryParams = [])
    {
        return $this->request('GET', '/api/3/contacts', ['query' => $queryParams]);
    }

    public function update(int $id, array $data)
    {
        return $this->request('PUT', "/api/3/contacts/{$id}", ['json' => $data]);
    }

    public function delete(int $id)
    {
        return $this->request('DELETE', "/api/3/contacts/{$id}");
    }

    public function sync(array $data)
    {
        return $this->request('POST', '/api/3/contact/sync', ['json' => $data]);
    }

    public function getCustomFieldValues(int $contactId)
    {
        return $this->request('GET', "/api/3/contacts/{$contactId}/fieldValues");
    }

    public function updateCustomFieldValue(array $data)
    {
        return $this->request('POST', '/api/3/fieldValues', ['json' => $data]);
    }

    public function listAllCustomFieldValues()
    {
        return $this->request('GET', '/api/3/fieldValues');
    }
}
