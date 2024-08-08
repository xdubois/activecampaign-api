<?php

namespace xdubois\ActiveCampaign;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ActiveCampaignClient
{
    protected $client;
    protected $apiToken;
    protected $accountUrl;

    public function __construct(string $apiToken, string $accountUrl)
    {
        $this->apiToken = $apiToken;
        $this->accountUrl = $accountUrl;
        $this->client = new Client([
            'base_uri' => $this->accountUrl,
            'headers' => [
                'Api-Token' => $this->apiToken,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function request(string $method, string $endpoint, array $options = [])
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            // Handle the exception as needed
            return ['error' => $e->getMessage()];
        }
    }
}
