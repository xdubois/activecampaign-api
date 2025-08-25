<?php

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Configuration;

/**
 * Custom Objects endpoint for managing custom objects in ActiveCampaign
 * 
 * @link https://developers.activecampaign.com/reference/custom-objects
 */
class CustomObjectsEndpoint extends ActiveCampaignClient
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
     * Create a new custom object schema
     * 
     * @param array $data Schema data
     * @return array The created schema data
     * @link https://developers.activecampaign.com/reference/create-a-schema
     */
    public function createSchema(array $data): array
    {
        return $this->request('POST', '/api/3/customObjects/schemas', ['json' => $data]);
    }

    /**
     * Retrieve a specific custom object schema
     * 
     * @param string $schemaId Schema ID
     * @return array The schema data
     * @link https://developers.activecampaign.com/reference/retrieve-a-schema
     */
    public function getSchema(string $schemaId): array
    {
        return $this->request('GET', "/api/3/customObjects/schemas/{$schemaId}");
    }

    /**
     * List custom object schemas with optional filtering
     * 
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of schemas with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-schemas
     */
    public function listSchemas(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/customObjects/schemas', ['query' => $queryParams]);
    }

    /**
     * Update an existing custom object schema
     * 
     * @param string $schemaId Schema ID
     * @param array $data Updated schema data
     * @return array The updated schema data
     * @link https://developers.activecampaign.com/reference/update-a-schema
     */
    public function updateSchema(string $schemaId, array $data): array
    {
        return $this->request('PUT', "/api/3/customObjects/schemas/{$schemaId}", ['json' => $data]);
    }

    /**
     * Delete a custom object schema
     * 
     * @param string $schemaId Schema ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-a-schema
     */
    public function deleteSchema(string $schemaId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/schemas/{$schemaId}");
    }

    /**
     * Create a new custom object record
     * 
     * @param string $schemaId Schema ID
     * @param array $data Record data
     * @return array The created record data
     * @link https://developers.activecampaign.com/reference/create-a-record
     */
    public function createRecord(string $schemaId, array $data): array
    {
        return $this->request('POST', "/api/3/customObjects/records/{$schemaId}", ['json' => $data]);
    }

    /**
     * Retrieve a specific custom object record
     * 
     * @param string $schemaId Schema ID
     * @param string $recordId Record ID
     * @return array The record data
     * @link https://developers.activecampaign.com/reference/retrieve-a-record
     */
    public function getRecord(string $schemaId, string $recordId): array
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }

    /**
     * List custom object records with optional filtering
     * 
     * @param string $schemaId Schema ID
     * @param array $queryParams Optional query parameters for filtering
     * @return array List of records with pagination info
     * @link https://developers.activecampaign.com/reference/list-all-records
     */
    public function listRecords(string $schemaId, array $queryParams = []): array
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}", ['query' => $queryParams]);
    }

    /**
     * Update an existing custom object record
     * 
     * @param string $schemaId Schema ID
     * @param string $recordId Record ID
     * @param array $data Updated record data
     * @return array The updated record data
     * @link https://developers.activecampaign.com/reference/update-a-record
     */
    public function updateRecord(string $schemaId, string $recordId, array $data): array
    {
        return $this->request('PUT', "/api/3/customObjects/records/{$schemaId}/{$recordId}", ['json' => $data]);
    }

    /**
     * Delete a custom object record
     * 
     * @param string $schemaId Schema ID
     * @param string $recordId Record ID
     * @return array Confirmation of deletion
     * @link https://developers.activecampaign.com/reference/delete-a-record
     */
    public function deleteRecord(string $schemaId, string $recordId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }
}
