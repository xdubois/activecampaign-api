<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Deals endpoint for managing deals, pipelines, stages, tasks and deal custom fields.
 *
 * @link https://developers.activecampaign.com/reference/deal
 */
class DealsEndpoint extends ActiveCampaignClient
{
    /**
     * Create a new deal.
     *
     * @param array<string, mixed> $data Payload with a top-level "deal" key (title, value, currency, group, stage, owner, contact/account)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-deal-new
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/deals', ['json' => $data]);
    }

    /**
     * Retrieve a specific deal.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-deal
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/deals/{$id}");
    }

    /**
     * List deals with optional filtering.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-deals
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/deals', ['query' => $queryParams]);
    }

    /**
     * Update an existing deal.
     *
     * @param array<string, mixed> $data Payload with a top-level "deal" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-deal-new
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/deals/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-deal
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/deals/{$id}");
    }

    /**
     * Bulk update the owners of deals.
     *
     * @param array<string, mixed> $data
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-update-deal-owners
     */
    public function bulkUpdateOwners(array $data): array
    {
        return $this->request('PATCH', '/api/3/deals/bulkUpdate', ['json' => $data]);
    }

    // -------------------------------------------------------------------------
    // Notes
    // -------------------------------------------------------------------------

    /**
     * Create a note for a deal.
     *
     * @param array<string, mixed> $data Payload with a top-level "note" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-deal-note
     */
    public function createNote(int $dealId, array $data): array
    {
        return $this->request('POST', "/api/3/deals/{$dealId}/notes", ['json' => $data]);
    }

    /**
     * Update a note for a deal.
     *
     * @param array<string, mixed> $data Payload with a top-level "note" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-deal-note
     */
    public function updateNote(int $dealId, int $noteId, array $data): array
    {
        return $this->request('PUT', "/api/3/deals/{$dealId}/notes/{$noteId}", ['json' => $data]);
    }

    // -------------------------------------------------------------------------
    // Pipelines (deal groups)
    // -------------------------------------------------------------------------

    /**
     * List all pipelines.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-pipelines
     */
    public function listPipelines(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/dealGroups', ['query' => $queryParams]);
    }

    /**
     * Retrieve a pipeline.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-pipeline
     */
    public function getPipeline(int $id): array
    {
        return $this->request('GET', "/api/3/dealGroups/{$id}");
    }

    /**
     * Create a pipeline.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealGroup" key (title, currency)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-pipeline
     */
    public function createPipeline(array $data): array
    {
        return $this->request('POST', '/api/3/dealGroups', ['json' => $data]);
    }

    /**
     * Update a pipeline.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealGroup" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-pipeline
     */
    public function updatePipeline(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/dealGroups/{$id}", ['json' => $data]);
    }

    /**
     * Delete a pipeline.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-pipeline
     */
    public function deletePipeline(int $id): array
    {
        return $this->request('DELETE', "/api/3/dealGroups/{$id}");
    }

    // -------------------------------------------------------------------------
    // Stages
    // -------------------------------------------------------------------------

    /**
     * List all deal stages.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[d_groupid]' => 1]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-deal-stages
     */
    public function listStages(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/dealStages', ['query' => $queryParams]);
    }

    /**
     * Retrieve a deal stage.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-deal-stage
     */
    public function getStage(int $id): array
    {
        return $this->request('GET', "/api/3/dealStages/{$id}");
    }

    /**
     * Create a deal stage.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealStage" key (title, group)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-a-deal-stage
     */
    public function createStage(array $data): array
    {
        return $this->request('POST', '/api/3/dealStages', ['json' => $data]);
    }

    /**
     * Update a deal stage.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealStage" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-deal-stage
     */
    public function updateStage(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/dealStages/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal stage.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-deal-stage
     */
    public function deleteStage(int $id): array
    {
        return $this->request('DELETE', "/api/3/dealStages/{$id}");
    }

    /**
     * Move all deals of a stage to another stage.
     *
     * @param array<string, mixed> $data Payload with a top-level "deal" key: {stage: targetStageId}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/move-deals-to-another-deal-stage
     */
    public function moveDealsToStage(int $stageId, array $data): array
    {
        return $this->request('PUT', "/api/3/dealStages/{$stageId}/deals", ['json' => $data]);
    }

    // -------------------------------------------------------------------------
    // Tasks
    // -------------------------------------------------------------------------

    /**
     * List all deal tasks.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-tasks
     */
    public function listTasks(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/dealTasks', ['query' => $queryParams]);
    }

    /**
     * Retrieve a deal task.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-task
     */
    public function getTask(int $id): array
    {
        return $this->request('GET', "/api/3/dealTasks/{$id}");
    }

    /**
     * Create a deal task.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealTask" key (relid, duedate, dealTasktype)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-task
     */
    public function createTask(array $data): array
    {
        return $this->request('POST', '/api/3/dealTasks', ['json' => $data]);
    }

    /**
     * Update a deal task.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealTask" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-task
     */
    public function updateTask(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/dealTasks/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal task.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-task
     */
    public function deleteTask(int $id): array
    {
        return $this->request('DELETE', "/api/3/dealTasks/{$id}");
    }

    // -------------------------------------------------------------------------
    // Custom fields (meta) and custom field values (data)
    // -------------------------------------------------------------------------

    /**
     * List all deal custom field definitions.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-all-custom-deal-field-meta
     */
    public function listCustomFields(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/dealCustomFieldMeta', ['query' => $queryParams]);
    }

    /**
     * Retrieve a deal custom field definition.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-custom-deal-field-meta
     */
    public function getCustomField(int $id): array
    {
        return $this->request('GET', "/api/3/dealCustomFieldMeta/{$id}");
    }

    /**
     * Create a deal custom field definition.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealCustomFieldMetum" key (fieldLabel, fieldType)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-custom-deal-field-meta
     */
    public function createCustomField(array $data): array
    {
        return $this->request('POST', '/api/3/dealCustomFieldMeta', ['json' => $data]);
    }

    /**
     * Update a deal custom field definition.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealCustomFieldMetum" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-custom-deal-field-meta
     */
    public function updateCustomField(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/dealCustomFieldMeta/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal custom field definition.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-custom-deal-field-meta
     */
    public function deleteCustomField(int $id): array
    {
        return $this->request('DELETE', "/api/3/dealCustomFieldMeta/{$id}");
    }

    /**
     * List deal custom field values.
     *
     * @param array<string, mixed> $queryParams e.g. ['filters[dealId]' => 1]
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-custom-fielddata-field-values
     */
    public function listCustomFieldValues(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/dealCustomFieldData', ['query' => $queryParams]);
    }

    /**
     * Retrieve a deal custom field value.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/retrieve-a-custom-deal-field-value
     */
    public function getCustomFieldValue(int $id): array
    {
        return $this->request('GET', "/api/3/dealCustomFieldData/{$id}");
    }

    /**
     * Create a deal custom field value.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealCustomFieldDatum" key (dealId, customFieldId, fieldValue)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-custom-deal-field-value
     */
    public function createCustomFieldValue(array $data): array
    {
        return $this->request('POST', '/api/3/dealCustomFieldData', ['json' => $data]);
    }

    /**
     * Update a deal custom field value.
     *
     * @param array<string, mixed> $data Payload with a top-level "dealCustomFieldDatum" key (fieldValue)
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-a-custom-deal-field-value
     */
    public function updateCustomFieldValue(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/dealCustomFieldData/{$id}", ['json' => $data]);
    }

    /**
     * Delete a deal custom field value.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-a-custom-deal-field-data
     */
    public function deleteCustomFieldValue(int $id): array
    {
        return $this->request('DELETE', "/api/3/dealCustomFieldData/{$id}");
    }

    /**
     * Bulk create deal custom field values.
     *
     * @param list<array<string, mixed>> $values List of {dealId, customFieldId, fieldValue, fieldCurrency?}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-create-a-custom-deal-field-value
     */
    public function bulkCreateCustomFieldValues(array $values): array
    {
        return $this->request('POST', '/api/3/dealCustomFieldData/bulkCreate', ['json' => $values]);
    }

    /**
     * Bulk update deal custom field values.
     *
     * @param list<array<string, mixed>> $values List of {id, fieldValue, fieldCurrency?}
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/bulk-update-a-custom-field-value
     */
    public function bulkUpdateCustomFieldValues(array $values): array
    {
        return $this->request('PATCH', '/api/3/dealCustomFieldData/bulkUpdate', ['json' => $values]);
    }
}
