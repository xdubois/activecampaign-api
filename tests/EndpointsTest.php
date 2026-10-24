<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use xdubois\ActiveCampaign\ActiveCampaignAPI;

/**
 * Verifies that every endpoint method sends the documented HTTP method, path and payload.
 */
final class EndpointsTest extends TestCase
{
    /**
     * @return iterable<string, array{callable(ActiveCampaignAPI): array<mixed>, string, string, ?string}>
     */
    public static function endpointProvider(): iterable
    {
        $data = ['x' => ['y' => 1]];
        $json = '{"x":{"y":1}}';
        $query = ['limit' => 5, 'offset' => 10];
        $qs = '?limit=5&offset=10';

        // Contacts
        yield 'contacts.create' => [fn(ActiveCampaignAPI $a) => $a->contacts()->create($data), 'POST', '/api/3/contacts', $json];
        yield 'contacts.get' => [fn(ActiveCampaignAPI $a) => $a->contacts()->get(7), 'GET', '/api/3/contacts/7', null];
        yield 'contacts.list' => [fn(ActiveCampaignAPI $a) => $a->contacts()->list($query), 'GET', '/api/3/contacts' . $qs, null];
        yield 'contacts.update' => [fn(ActiveCampaignAPI $a) => $a->contacts()->update(7, $data), 'PUT', '/api/3/contacts/7', $json];
        yield 'contacts.delete' => [fn(ActiveCampaignAPI $a) => $a->contacts()->delete(7), 'DELETE', '/api/3/contacts/7', null];
        yield 'contacts.sync' => [fn(ActiveCampaignAPI $a) => $a->contacts()->sync($data), 'POST', '/api/3/contact/sync', $json];
        yield 'contacts.bulkSync' => [fn(ActiveCampaignAPI $a) => $a->contacts()->bulkSync($data), 'POST', '/api/3/contact/bulkSync', $json];
        yield 'contacts.bulkImport' => [fn(ActiveCampaignAPI $a) => $a->contacts()->bulkImport($data), 'POST', '/api/3/import/bulk_import', $json];
        yield 'contacts.getBulkImportStatus' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getBulkImportStatus('abc'), 'GET', '/api/3/import/info?batchId=abc', null];
        yield 'contacts.merge' => [fn(ActiveCampaignAPI $a) => $a->contacts()->merge($data), 'POST', '/api/3/contact/merge', $json];
        yield 'contacts.getDeals' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getDeals(7), 'GET', '/api/3/contacts/7/deals', null];
        yield 'contacts.getNotes' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getNotes(7), 'GET', '/api/3/contacts/7/notes', null];
        yield 'contacts.getActivities' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getActivities(7, $query), 'GET', '/api/3/contacts/7/activities' . $qs, null];
        yield 'contacts.addTag' => [fn(ActiveCampaignAPI $a) => $a->contacts()->addTag(7, 3), 'POST', '/api/3/contactTags', '{"contactTag":{"contact":7,"tag":3}}'];
        yield 'contacts.removeTag' => [fn(ActiveCampaignAPI $a) => $a->contacts()->removeTag(9), 'DELETE', '/api/3/contactTags/9', null];
        yield 'contacts.removeTagById' => [fn(ActiveCampaignAPI $a) => $a->contacts()->removeTagById(7, 3), 'DELETE', '/api/3/contacts/7/tags/3', null];
        yield 'contacts.removeTagByName' => [fn(ActiveCampaignAPI $a) => $a->contacts()->removeTagByName(7, 'VIP customer'), 'DELETE', '/api/3/contacts/7/tags?tag=VIP%20customer', null];
        yield 'contacts.getTags' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getTags(7), 'GET', '/api/3/contacts/7/contactTags', null];
        yield 'contacts.updateListStatus' => [fn(ActiveCampaignAPI $a) => $a->contacts()->updateListStatus(7, 2, 1, ['sourceid' => 4]), 'POST', '/api/3/contactLists', '{"contactList":{"sourceid":4,"list":2,"contact":7,"status":1}}'];
        yield 'contacts.getListMemberships' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getListMemberships(7), 'GET', '/api/3/contacts/7/contactLists', null];
        yield 'contacts.addToAutomation' => [fn(ActiveCampaignAPI $a) => $a->contacts()->addToAutomation(7, 5), 'POST', '/api/3/contactAutomations', '{"contactAutomation":{"contact":7,"automation":5}}'];
        yield 'contacts.removeFromAutomation' => [fn(ActiveCampaignAPI $a) => $a->contacts()->removeFromAutomation(9), 'DELETE', '/api/3/contactAutomations/9', null];
        yield 'contacts.getAutomations' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getAutomations(7), 'GET', '/api/3/contacts/7/contactAutomations', null];
        yield 'contacts.getCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getCustomFieldValues(7), 'GET', '/api/3/contacts/7/fieldValues', null];
        yield 'contacts.updateCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->contacts()->updateCustomFieldValue($data), 'POST', '/api/3/fieldValues', $json];
        yield 'contacts.updateCustomFieldValueById' => [fn(ActiveCampaignAPI $a) => $a->contacts()->updateCustomFieldValueById(4, $data), 'PUT', '/api/3/fieldValues/4', $json];
        yield 'contacts.getCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->contacts()->getCustomFieldValue(4), 'GET', '/api/3/fieldValues/4', null];
        yield 'contacts.deleteCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->contacts()->deleteCustomFieldValue(4), 'DELETE', '/api/3/fieldValues/4', null];
        yield 'contacts.listAllCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->contacts()->listAllCustomFieldValues($query), 'GET', '/api/3/fieldValues' . $qs, null];

        // Deals
        yield 'deals.create' => [fn(ActiveCampaignAPI $a) => $a->deals()->create($data), 'POST', '/api/3/deals', $json];
        yield 'deals.get' => [fn(ActiveCampaignAPI $a) => $a->deals()->get(7), 'GET', '/api/3/deals/7', null];
        yield 'deals.list' => [fn(ActiveCampaignAPI $a) => $a->deals()->list($query), 'GET', '/api/3/deals' . $qs, null];
        yield 'deals.update' => [fn(ActiveCampaignAPI $a) => $a->deals()->update(7, $data), 'PUT', '/api/3/deals/7', $json];
        yield 'deals.delete' => [fn(ActiveCampaignAPI $a) => $a->deals()->delete(7), 'DELETE', '/api/3/deals/7', null];
        yield 'deals.bulkUpdateOwners' => [fn(ActiveCampaignAPI $a) => $a->deals()->bulkUpdateOwners($data), 'PATCH', '/api/3/deals/bulkUpdate', $json];
        yield 'deals.createNote' => [fn(ActiveCampaignAPI $a) => $a->deals()->createNote(7, $data), 'POST', '/api/3/deals/7/notes', $json];
        yield 'deals.updateNote' => [fn(ActiveCampaignAPI $a) => $a->deals()->updateNote(7, 2, $data), 'PUT', '/api/3/deals/7/notes/2', $json];
        yield 'deals.listPipelines' => [fn(ActiveCampaignAPI $a) => $a->deals()->listPipelines($query), 'GET', '/api/3/dealGroups' . $qs, null];
        yield 'deals.getPipeline' => [fn(ActiveCampaignAPI $a) => $a->deals()->getPipeline(7), 'GET', '/api/3/dealGroups/7', null];
        yield 'deals.createPipeline' => [fn(ActiveCampaignAPI $a) => $a->deals()->createPipeline($data), 'POST', '/api/3/dealGroups', $json];
        yield 'deals.updatePipeline' => [fn(ActiveCampaignAPI $a) => $a->deals()->updatePipeline(7, $data), 'PUT', '/api/3/dealGroups/7', $json];
        yield 'deals.deletePipeline' => [fn(ActiveCampaignAPI $a) => $a->deals()->deletePipeline(7), 'DELETE', '/api/3/dealGroups/7', null];
        yield 'deals.listStages' => [fn(ActiveCampaignAPI $a) => $a->deals()->listStages($query), 'GET', '/api/3/dealStages' . $qs, null];
        yield 'deals.getStage' => [fn(ActiveCampaignAPI $a) => $a->deals()->getStage(7), 'GET', '/api/3/dealStages/7', null];
        yield 'deals.createStage' => [fn(ActiveCampaignAPI $a) => $a->deals()->createStage($data), 'POST', '/api/3/dealStages', $json];
        yield 'deals.updateStage' => [fn(ActiveCampaignAPI $a) => $a->deals()->updateStage(7, $data), 'PUT', '/api/3/dealStages/7', $json];
        yield 'deals.deleteStage' => [fn(ActiveCampaignAPI $a) => $a->deals()->deleteStage(7), 'DELETE', '/api/3/dealStages/7', null];
        yield 'deals.moveDealsToStage' => [fn(ActiveCampaignAPI $a) => $a->deals()->moveDealsToStage(7, $data), 'PUT', '/api/3/dealStages/7/deals', $json];
        yield 'deals.listTasks' => [fn(ActiveCampaignAPI $a) => $a->deals()->listTasks($query), 'GET', '/api/3/dealTasks' . $qs, null];
        yield 'deals.getTask' => [fn(ActiveCampaignAPI $a) => $a->deals()->getTask(7), 'GET', '/api/3/dealTasks/7', null];
        yield 'deals.createTask' => [fn(ActiveCampaignAPI $a) => $a->deals()->createTask($data), 'POST', '/api/3/dealTasks', $json];
        yield 'deals.updateTask' => [fn(ActiveCampaignAPI $a) => $a->deals()->updateTask(7, $data), 'PUT', '/api/3/dealTasks/7', $json];
        yield 'deals.deleteTask' => [fn(ActiveCampaignAPI $a) => $a->deals()->deleteTask(7), 'DELETE', '/api/3/dealTasks/7', null];
        yield 'deals.listCustomFields' => [fn(ActiveCampaignAPI $a) => $a->deals()->listCustomFields($query), 'GET', '/api/3/dealCustomFieldMeta' . $qs, null];
        yield 'deals.getCustomField' => [fn(ActiveCampaignAPI $a) => $a->deals()->getCustomField(7), 'GET', '/api/3/dealCustomFieldMeta/7', null];
        yield 'deals.createCustomField' => [fn(ActiveCampaignAPI $a) => $a->deals()->createCustomField($data), 'POST', '/api/3/dealCustomFieldMeta', $json];
        yield 'deals.updateCustomField' => [fn(ActiveCampaignAPI $a) => $a->deals()->updateCustomField(7, $data), 'PUT', '/api/3/dealCustomFieldMeta/7', $json];
        yield 'deals.deleteCustomField' => [fn(ActiveCampaignAPI $a) => $a->deals()->deleteCustomField(7), 'DELETE', '/api/3/dealCustomFieldMeta/7', null];
        yield 'deals.listCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->deals()->listCustomFieldValues($query), 'GET', '/api/3/dealCustomFieldData' . $qs, null];
        yield 'deals.getCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->deals()->getCustomFieldValue(7), 'GET', '/api/3/dealCustomFieldData/7', null];
        yield 'deals.createCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->deals()->createCustomFieldValue($data), 'POST', '/api/3/dealCustomFieldData', $json];
        yield 'deals.updateCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->deals()->updateCustomFieldValue(7, $data), 'PUT', '/api/3/dealCustomFieldData/7', $json];
        yield 'deals.deleteCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->deals()->deleteCustomFieldValue(7), 'DELETE', '/api/3/dealCustomFieldData/7', null];
        yield 'deals.bulkCreateCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->deals()->bulkCreateCustomFieldValues([['dealId' => 1]]), 'POST', '/api/3/dealCustomFieldData/bulkCreate', '[{"dealId":1}]'];
        yield 'deals.bulkUpdateCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->deals()->bulkUpdateCustomFieldValues([['id' => 1]]), 'PATCH', '/api/3/dealCustomFieldData/bulkUpdate', '[{"id":1}]'];

        // Accounts
        yield 'accounts.create' => [fn(ActiveCampaignAPI $a) => $a->accounts()->create($data), 'POST', '/api/3/accounts', $json];
        yield 'accounts.get' => [fn(ActiveCampaignAPI $a) => $a->accounts()->get(7), 'GET', '/api/3/accounts/7', null];
        yield 'accounts.list' => [fn(ActiveCampaignAPI $a) => $a->accounts()->list($query), 'GET', '/api/3/accounts' . $qs, null];
        yield 'accounts.update' => [fn(ActiveCampaignAPI $a) => $a->accounts()->update(7, $data), 'PUT', '/api/3/accounts/7', $json];
        yield 'accounts.delete' => [fn(ActiveCampaignAPI $a) => $a->accounts()->delete(7), 'DELETE', '/api/3/accounts/7', null];
        yield 'accounts.bulkDelete' => [fn(ActiveCampaignAPI $a) => $a->accounts()->bulkDelete([1, 2, 3]), 'DELETE', '/api/3/accounts/bulk_delete?ids%5B%5D=1&ids%5B%5D=2&ids%5B%5D=3', null];
        yield 'accounts.createNote' => [fn(ActiveCampaignAPI $a) => $a->accounts()->createNote(7, $data), 'POST', '/api/3/accounts/7/notes', $json];
        yield 'accounts.updateNote' => [fn(ActiveCampaignAPI $a) => $a->accounts()->updateNote(7, 2, $data), 'PUT', '/api/3/accounts/7/notes/2', $json];
        yield 'accounts.addContact' => [fn(ActiveCampaignAPI $a) => $a->accounts()->addContact(7, 3, ['jobTitle' => 'CTO']), 'POST', '/api/3/accountContacts', '{"accountContact":{"jobTitle":"CTO","account":7,"contact":3}}'];
        yield 'accounts.listContactAssociations' => [fn(ActiveCampaignAPI $a) => $a->accounts()->listContactAssociations($query), 'GET', '/api/3/accountContacts' . $qs, null];
        yield 'accounts.getContactAssociation' => [fn(ActiveCampaignAPI $a) => $a->accounts()->getContactAssociation(7), 'GET', '/api/3/accountContacts/7', null];
        yield 'accounts.updateContactAssociation' => [fn(ActiveCampaignAPI $a) => $a->accounts()->updateContactAssociation(7, $data), 'PUT', '/api/3/accountContacts/7', $json];
        yield 'accounts.removeContact' => [fn(ActiveCampaignAPI $a) => $a->accounts()->removeContact(7), 'DELETE', '/api/3/accountContacts/7', null];
        yield 'accounts.listCustomFields' => [fn(ActiveCampaignAPI $a) => $a->accounts()->listCustomFields($query), 'GET', '/api/3/accountCustomFieldMeta' . $qs, null];
        yield 'accounts.getCustomField' => [fn(ActiveCampaignAPI $a) => $a->accounts()->getCustomField(7), 'GET', '/api/3/accountCustomFieldMeta/7', null];
        yield 'accounts.createCustomField' => [fn(ActiveCampaignAPI $a) => $a->accounts()->createCustomField($data), 'POST', '/api/3/accountCustomFieldMeta', $json];
        yield 'accounts.updateCustomField' => [fn(ActiveCampaignAPI $a) => $a->accounts()->updateCustomField(7, $data), 'PUT', '/api/3/accountCustomFieldMeta/7', $json];
        yield 'accounts.deleteCustomField' => [fn(ActiveCampaignAPI $a) => $a->accounts()->deleteCustomField(7), 'DELETE', '/api/3/accountCustomFieldMeta/7', null];
        yield 'accounts.listCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->accounts()->listCustomFieldValues($query), 'GET', '/api/3/accountCustomFieldData' . $qs, null];
        yield 'accounts.getCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->accounts()->getCustomFieldValue(7), 'GET', '/api/3/accountCustomFieldData/7', null];
        yield 'accounts.createCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->accounts()->createCustomFieldValue($data), 'POST', '/api/3/accountCustomFieldData', $json];
        yield 'accounts.updateCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->accounts()->updateCustomFieldValue(7, $data), 'PUT', '/api/3/accountCustomFieldData/7', $json];
        yield 'accounts.deleteCustomFieldValue' => [fn(ActiveCampaignAPI $a) => $a->accounts()->deleteCustomFieldValue(7), 'DELETE', '/api/3/accountCustomFieldData/7', null];
        yield 'accounts.bulkCreateCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->accounts()->bulkCreateCustomFieldValues([['customerAccountId' => 1]]), 'POST', '/api/3/accountCustomFieldData/bulkCreate', '[{"customerAccountId":1}]'];
        yield 'accounts.bulkUpdateCustomFieldValues' => [fn(ActiveCampaignAPI $a) => $a->accounts()->bulkUpdateCustomFieldValues([['id' => 1]]), 'PATCH', '/api/3/accountCustomFieldData/bulkUpdate', '[{"id":1}]'];

        // Custom fields
        yield 'customFields.create' => [fn(ActiveCampaignAPI $a) => $a->customFields()->create($data), 'POST', '/api/3/fields', $json];
        yield 'customFields.get' => [fn(ActiveCampaignAPI $a) => $a->customFields()->get(7), 'GET', '/api/3/fields/7', null];
        yield 'customFields.list' => [fn(ActiveCampaignAPI $a) => $a->customFields()->list($query), 'GET', '/api/3/fields' . $qs, null];
        yield 'customFields.update' => [fn(ActiveCampaignAPI $a) => $a->customFields()->update(7, $data), 'PUT', '/api/3/fields/7', $json];
        yield 'customFields.delete' => [fn(ActiveCampaignAPI $a) => $a->customFields()->delete(7), 'DELETE', '/api/3/fields/7', null];
        yield 'customFields.listGroupMembers' => [fn(ActiveCampaignAPI $a) => $a->customFields()->listGroupMembers($query), 'GET', '/api/3/groupMembers' . $qs, null];
        yield 'customFields.addToGroup' => [fn(ActiveCampaignAPI $a) => $a->customFields()->addToGroup($data), 'POST', '/api/3/groupMembers', $json];
        yield 'customFields.getFieldGroup' => [fn(ActiveCampaignAPI $a) => $a->customFields()->getFieldGroup(7), 'GET', '/api/3/groupMembers/7', null];
        yield 'customFields.updateFieldGroup' => [fn(ActiveCampaignAPI $a) => $a->customFields()->updateFieldGroup(7, $data), 'PUT', '/api/3/groupMembers/7', $json];
        yield 'customFields.deleteFromGroup' => [fn(ActiveCampaignAPI $a) => $a->customFields()->deleteFromGroup(7), 'DELETE', '/api/3/groupMembers/7', null];
        yield 'customFields.addOptions' => [fn(ActiveCampaignAPI $a) => $a->customFields()->addOptions($data), 'POST', '/api/3/fieldOption/bulk', $json];
        yield 'customFields.addToList' => [fn(ActiveCampaignAPI $a) => $a->customFields()->addToList($data), 'POST', '/api/3/fieldRels', $json];
        yield 'customFields.removeFromList' => [fn(ActiveCampaignAPI $a) => $a->customFields()->removeFromList(7), 'DELETE', '/api/3/fieldRels/7', null];

        // Custom objects
        yield 'customObjects.createSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->createSchema($data), 'POST', '/api/3/customObjects/schemas', $json];
        yield 'customObjects.createPublicSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->createPublicSchema($data), 'POST', '/api/3/customObjects/schemas/public', $json];
        yield 'customObjects.createChildSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->createChildSchema('s1', $data), 'POST', '/api/3/customObjects/schemas/s1/child', $json];
        yield 'customObjects.getSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->getSchema('s1'), 'GET', '/api/3/customObjects/schemas/s1', null];
        yield 'customObjects.listSchemas' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->listSchemas($query), 'GET', '/api/3/customObjects/schemas' . $qs, null];
        yield 'customObjects.updateSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->updateSchema('s1', $data), 'PUT', '/api/3/customObjects/schemas/s1', $json];
        yield 'customObjects.deleteSchema' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->deleteSchema('s1'), 'DELETE', '/api/3/customObjects/schemas/s1', null];
        yield 'customObjects.deleteSchemaField' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->deleteSchemaField('s1', 'f1'), 'DELETE', '/api/3/customObjects/schemas/s1/fields/f1', null];
        yield 'customObjects.createOrUpdateRecord' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->createOrUpdateRecord('s1', $data), 'POST', '/api/3/customObjects/records/s1', $json];
        yield 'customObjects.createRecord' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->createRecord('s1', $data), 'POST', '/api/3/customObjects/records/s1', $json];
        yield 'customObjects.updateRecord' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->updateRecord('s1', 'r1', ['record' => ['fields' => []]]), 'POST', '/api/3/customObjects/records/s1', '{"record":{"fields":[],"id":"r1"}}'];
        yield 'customObjects.updateRecord without wrapper' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->updateRecord('s1', 'r1', ['fields' => []]), 'POST', '/api/3/customObjects/records/s1', '{"fields":[],"record":{"fields":[],"id":"r1"}}'];
        yield 'customObjects.getRecord' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->getRecord('s1', 'r1'), 'GET', '/api/3/customObjects/records/s1/r1', null];
        yield 'customObjects.getRecordByExternalId' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->getRecordByExternalId('s1', 'ext/1'), 'GET', '/api/3/customObjects/records/s1/external/ext%2F1', null];
        yield 'customObjects.listRecords' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->listRecords('s1', $query), 'GET', '/api/3/customObjects/records/s1' . $qs, null];
        yield 'customObjects.deleteRecord' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->deleteRecord('s1', 'r1'), 'DELETE', '/api/3/customObjects/records/s1/r1', null];
        yield 'customObjects.deleteRecordByExternalId' => [fn(ActiveCampaignAPI $a) => $a->customObjects()->deleteRecordByExternalId('s1', 'e1'), 'DELETE', '/api/3/customObjects/records/s1/external/e1', null];

        // Tags
        yield 'tags.create' => [fn(ActiveCampaignAPI $a) => $a->tags()->create($data), 'POST', '/api/3/tags', $json];
        yield 'tags.get' => [fn(ActiveCampaignAPI $a) => $a->tags()->get(7), 'GET', '/api/3/tags/7', null];
        yield 'tags.list' => [fn(ActiveCampaignAPI $a) => $a->tags()->list(['search' => 'vip']), 'GET', '/api/3/tags?search=vip', null];
        yield 'tags.update' => [fn(ActiveCampaignAPI $a) => $a->tags()->update(7, $data), 'PUT', '/api/3/tags/7', $json];
        yield 'tags.delete' => [fn(ActiveCampaignAPI $a) => $a->tags()->delete(7), 'DELETE', '/api/3/tags/7', null];

        // Lists
        yield 'lists.create' => [fn(ActiveCampaignAPI $a) => $a->lists()->create($data), 'POST', '/api/3/lists', $json];
        yield 'lists.get' => [fn(ActiveCampaignAPI $a) => $a->lists()->get(7), 'GET', '/api/3/lists/7', null];
        yield 'lists.list' => [fn(ActiveCampaignAPI $a) => $a->lists()->list($query), 'GET', '/api/3/lists' . $qs, null];
        yield 'lists.update' => [fn(ActiveCampaignAPI $a) => $a->lists()->update(7, $data), 'PUT', '/api/3/lists/7', $json];
        yield 'lists.delete' => [fn(ActiveCampaignAPI $a) => $a->lists()->delete(7), 'DELETE', '/api/3/lists/7', null];

        // Webhooks
        yield 'webhooks.create' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->create($data), 'POST', '/api/3/webhooks', $json];
        yield 'webhooks.get' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->get(7), 'GET', '/api/3/webhooks/7', null];
        yield 'webhooks.list' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->list($query), 'GET', '/api/3/webhooks' . $qs, null];
        yield 'webhooks.update' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->update(7, $data), 'PUT', '/api/3/webhooks/7', $json];
        yield 'webhooks.delete' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->delete(7), 'DELETE', '/api/3/webhooks/7', null];
        yield 'webhooks.listEvents' => [fn(ActiveCampaignAPI $a) => $a->webhooks()->listEvents(), 'GET', '/api/3/webhook/events', null];

        // Users
        yield 'users.me' => [fn(ActiveCampaignAPI $a) => $a->users()->me(), 'GET', '/api/3/users/me', null];
        yield 'users.create' => [fn(ActiveCampaignAPI $a) => $a->users()->create($data), 'POST', '/api/3/users', $json];
        yield 'users.get' => [fn(ActiveCampaignAPI $a) => $a->users()->get(7), 'GET', '/api/3/users/7', null];
        yield 'users.getByEmail' => [fn(ActiveCampaignAPI $a) => $a->users()->getByEmail('a+b@example.com'), 'GET', '/api/3/users/email/a%2Bb%40example.com', null];
        yield 'users.getByUsername' => [fn(ActiveCampaignAPI $a) => $a->users()->getByUsername('john'), 'GET', '/api/3/users/username/john', null];
        yield 'users.list' => [fn(ActiveCampaignAPI $a) => $a->users()->list($query), 'GET', '/api/3/users' . $qs, null];
        yield 'users.update' => [fn(ActiveCampaignAPI $a) => $a->users()->update(7, $data), 'PUT', '/api/3/users/7', $json];
        yield 'users.delete' => [fn(ActiveCampaignAPI $a) => $a->users()->delete(7), 'DELETE', '/api/3/users/7', null];
    }

    /**
     * @param callable(ActiveCampaignAPI): array<mixed> $call
     */
    #[DataProvider('endpointProvider')]
    public function testEndpointSendsExpectedRequest(callable $call, string $method, string $path, ?string $body): void
    {
        $api = $this->createApi([self::jsonResponse(200, ['result' => 'ok'])]);

        self::assertSame(['result' => 'ok'], $call($api));

        $request = $this->lastRequest();
        self::assertSame($method, $request->getMethod());
        self::assertSame('https://account.api-us1.com' . $path, (string) $request->getUri());
        self::assertSame($body ?? '', (string) $request->getBody());
        self::assertSame('secret-token', $request->getHeaderLine('Api-Token'));
    }
}
