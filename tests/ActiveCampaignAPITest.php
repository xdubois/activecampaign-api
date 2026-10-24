<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use GuzzleHttp\Client;
use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;

final class ActiveCampaignAPITest extends TestCase
{
    public function testLegacyConstructor(): void
    {
        $api = new ActiveCampaignAPI('token', 'https://account.api-us1.com/');

        self::assertSame('token', $api->getConfiguration()->getApiToken());
        self::assertSame('https://account.api-us1.com', $api->getConfiguration()->getAccountUrl());
        self::assertInstanceOf(Client::class, $api->getHttpClient());
    }

    public function testLegacyConstructorRequiresAccountUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ActiveCampaignAPI('token');
    }

    public function testConfigurationConstructor(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com');
        $api = new ActiveCampaignAPI($config);

        self::assertSame($config, $api->getConfiguration());
        self::assertSame($config, $api->contacts()->getConfiguration());
    }

    public function testEndpointsShareTheInjectedHttpClient(): void
    {
        $client = $this->createHttpClient();
        $api = new ActiveCampaignAPI($this->createConfiguration(), null, $client);

        self::assertSame($client, $api->getHttpClient());
        self::assertSame($client, $api->contacts()->getHttpClient());
        self::assertSame($client, $api->deals()->getHttpClient());
        self::assertSame($client, $api->users()->getHttpClient());
    }

    public function testEveryEndpointSharesConfigurationAndHttpClient(): void
    {
        $client = $this->createHttpClient();
        $config = $this->createConfiguration();
        $api = new ActiveCampaignAPI($config, null, $client);

        $endpoints = [
            $api->deals(), $api->contacts(), $api->accounts(), $api->customObjects(), $api->customFields(),
            $api->tags(), $api->lists(), $api->webhooks(), $api->users(),
        ];
        self::assertCount(9, array_unique(array_map(spl_object_id(...), $endpoints)));
        foreach ($endpoints as $endpoint) {
            self::assertSame($config, $endpoint->getConfiguration());
            self::assertSame($client, $endpoint->getHttpClient());
        }
    }

    public function testLegacyPropertyAccess(): void
    {
        $api = $this->createApi();

        self::assertSame($api->deals(), $api->deals);
        self::assertSame($api->contacts(), $api->contacts);
        self::assertSame($api->accounts(), $api->accounts);
        self::assertSame($api->customObjects(), $api->customObjects);
        self::assertSame($api->customFields(), $api->customFields);
        self::assertSame($api->tags(), $api->tags);
        self::assertTrue($api->__isset('contacts'));
        self::assertFalse($api->__isset('nope'));
    }

    public function testUnknownPropertyThrows(): void
    {
        $api = $this->createApi();

        $this->expectException(\InvalidArgumentException::class);
        $api->__get('nope');
    }

    public function testEndToEndRequest(): void
    {
        $api = $this->createApi([self::jsonResponse(200, ['user' => ['id' => '1']])]);

        self::assertSame(['user' => ['id' => '1']], $api->users()->me());
        self::assertSame('https://account.api-us1.com/api/3/users/me', (string) $this->lastRequest()->getUri());
        self::assertSame('secret-token', $this->lastRequest()->getHeaderLine('Api-Token'));
    }
}
