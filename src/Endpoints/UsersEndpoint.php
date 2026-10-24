<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Endpoints;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Users endpoint. me() is a cheap way to verify credentials.
 *
 * @link https://developers.activecampaign.com/reference/users
 */
class UsersEndpoint extends ActiveCampaignClient
{
    /**
     * Retrieve the user the API token belongs to.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-user-loggedin
     */
    public function me(): array
    {
        return $this->request('GET', '/api/3/users/me');
    }

    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data Payload with a top-level "user" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/create-user
     */
    public function create(array $data): array
    {
        return $this->request('POST', '/api/3/users', ['json' => $data]);
    }

    /**
     * Retrieve a specific user.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-user
     */
    public function get(int $id): array
    {
        return $this->request('GET', "/api/3/users/{$id}");
    }

    /**
     * Retrieve a user by email address.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-user-email
     */
    public function getByEmail(string $email): array
    {
        return $this->request('GET', '/api/3/users/email/' . rawurlencode($email));
    }

    /**
     * Retrieve a user by username.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/get-user-username
     */
    public function getByUsername(string $username): array
    {
        return $this->request('GET', '/api/3/users/username/' . rawurlencode($username));
    }

    /**
     * List all users.
     *
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/list-all-users
     */
    public function list(array $queryParams = []): array
    {
        return $this->request('GET', '/api/3/users', ['query' => $queryParams]);
    }

    /**
     * Update an existing user.
     *
     * @param array<string, mixed> $data Payload with a top-level "user" key
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/update-user
     */
    public function update(int $id, array $data): array
    {
        return $this->request('PUT', "/api/3/users/{$id}", ['json' => $data]);
    }

    /**
     * Delete a user.
     *
     * @return array<mixed>
     * @link https://developers.activecampaign.com/reference/delete-user
     */
    public function delete(int $id): array
    {
        return $this->request('DELETE', "/api/3/users/{$id}");
    }
}
