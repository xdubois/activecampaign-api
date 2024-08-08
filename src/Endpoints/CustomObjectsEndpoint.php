<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

class CustomObjectsEndpoint extends ActiveCampaignClient
{
    public function createSchema(array $data)
    {
        return $this->request('POST', '/api/3/customObjects/schemas', ['json' => $data]);
    }

    public function getSchema(string $schemaId)
    {
        return $this->request('GET', "/api/3/customObjects/schemas/{$schemaId}");
    }

    public function listSchemas(array $queryParams = [])
    {
        return $this->request('GET', '/api/3/customObjects/schemas', ['query' => $queryParams]);
    }

    public function updateSchema(string $schemaId, array $data)
    {
        return $this->request('PUT', "/api/3/customObjects/schemas/{$schemaId}", ['json' => $data]);
    }

    public function deleteSchema(string $schemaId)
    {
        return $this->request('DELETE', "/api/3/customObjects/schemas/{$schemaId}");
    }

    public function createRecord(string $schemaId, array $data)
    {
        return $this->request('POST', "/api/3/customObjects/records/{$schemaId}", ['json' => $data]);
    }

    public function getRecord(string $schemaId, string $recordId)
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }

    public function listRecords(string $schemaId, array $queryParams = [])
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}", ['query' => $queryParams]);
    }

    public function updateRecord(string $schemaId, string $recordId, array $data)
    {
        return $this->request('PUT', "/api/3/customObjects/records/{$schemaId}/{$recordId}", ['json' => $data]);
    }

    public function deleteRecord(string $schemaId, string $recordId)
    {
        return $this->request('DELETE', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }
}
