<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Custom Objects endpoint for managing custom object schemas and records.
 *
 * @link https://developers.activecampaign.com/reference/custom-objects
 */
class CustomObjectsEndpoint extends ActiveCampaignClient
{
    // -------------------------------------------------------------------------
    // Schemas
    // -------------------------------------------------------------------------

    /**
     * Create a new custom object schema.
     *
     * @param array<string, mixed> $data Payload with a top-level "schema" key (slug, labels, fields, relationships)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-schema
     */
    public function createSchema(array $data): array
    {
        return $this->request('POST', '/api/3/customObjects/schemas', ['json' => $data]);
    }

    /**
     * Create a public schema.
     *
     * @param array<string, mixed> $data Payload with a top-level "schema" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-public-schema
     */
    public function createPublicSchema(array $data): array
    {
        return $this->request('POST', '/api/3/customObjects/schemas/public', ['json' => $data]);
    }

    /**
     * Create a child schema of a public schema.
     *
     * @param array<string, mixed> $data Payload with a top-level "schema" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-child-schema
     */
    public function createChildSchema(string $parentSchemaId, array $data): array
    {
        return $this->request('POST', "/api/3/customObjects/schemas/{$parentSchemaId}/child", ['json' => $data]);
    }

    /**
     * Retrieve a specific custom object schema.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-schema
     */
    public function getSchema(string $schemaId): array
    {
        return $this->request('GET', "/api/3/customObjects/schemas/{$schemaId}");
    }

    /**
     * List custom object schemas with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-schemas
     */
    public function listSchemas(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/customObjects/schemas', ['query' => $queryParams]);
    }

    /**
     * Update an existing custom object schema.
     *
     * @param array<string, mixed> $data Payload with a top-level "schema" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-schema
     */
    public function updateSchema(string $schemaId, array $data): array
    {
        return $this->request('PUT', "/api/3/customObjects/schemas/{$schemaId}", ['json' => $data]);
    }

    /**
     * Delete a custom object schema.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-schema
     */
    public function deleteSchema(string $schemaId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/schemas/{$schemaId}");
    }

    /**
     * Delete a field from a schema.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-field-1
     */
    public function deleteSchemaField(string $schemaId, string $fieldId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/schemas/{$schemaId}/fields/{$fieldId}");
    }

    // -------------------------------------------------------------------------
    // Records
    // -------------------------------------------------------------------------

    /**
     * Create or update a record (upsert matched on "id" and/or "externalId").
     *
     * @param array<string, mixed> $data Payload with a top-level "record" key (externalId, fields, relationships)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-or-update-record
     */
    public function createOrUpdateRecord(string $schemaId, array $data): array
    {
        return $this->request('POST', "/api/3/customObjects/records/{$schemaId}", ['json' => $data]);
    }

    /**
     * Create a new custom object record. Alias of createOrUpdateRecord().
     *
     * @param array<string, mixed> $data Payload with a top-level "record" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-or-update-record
     */
    public function createRecord(string $schemaId, array $data): array
    {
        return $this->createOrUpdateRecord($schemaId, $data);
    }

    /**
     * Update an existing record.
     *
     * The API has no dedicated update endpoint: records are updated through the
     * upsert endpoint, so the record ID is injected into the payload.
     *
     * @param array<string, mixed> $data Payload with a top-level "record" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-or-update-record
     */
    public function updateRecord(string $schemaId, string $recordId, array $data): array
    {
        $record = isset($data['record']) && is_array($data['record']) ? $data['record'] : $data;
        $data['record'] = array_merge($record, ['id' => $recordId]);

        return $this->createOrUpdateRecord($schemaId, $data);
    }

    /**
     * Retrieve a specific custom object record.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-a-single-record
     */
    public function getRecord(string $schemaId, string $recordId): array
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }

    /**
     * Retrieve a record by its external ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-a-single-record-using-external-id
     */
    public function getRecordByExternalId(string $schemaId, string $externalId): array
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}/external/" . rawurlencode($externalId));
    }

    /**
     * List custom object records with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-records-created-from-a-schema
     */
    public function listRecords(string $schemaId, array $queryParams = []): array
    {
        return $this->request('GET', "/api/3/customObjects/records/{$schemaId}", ['query' => $queryParams]);
    }

    /**
     * Delete a custom object record.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-record
     */
    public function deleteRecord(string $schemaId, string $recordId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/records/{$schemaId}/{$recordId}");
    }

    /**
     * Delete a record by its external ID.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-record-by-external-id
     */
    public function deleteRecordByExternalId(string $schemaId, string $externalId): array
    {
        return $this->request('DELETE', "/api/3/customObjects/records/{$schemaId}/external/" . rawurlencode($externalId));
    }
}
