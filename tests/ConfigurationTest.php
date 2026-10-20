<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use PHPUnit\Framework\TestCase;
use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;

final class ConfigurationTest extends TestCase
{
    public function testDefaults(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com/');

        self::assertSame('token', $config->getApiToken());
        self::assertSame('https://account.api-us1.com', $config->getAccountUrl());
        self::assertSame(30, $config->getTimeout());
        self::assertSame(10, $config->getConnectTimeout());
        self::assertSame(1, $config->getMaxRetries());
        self::assertSame(1.0, $config->getRetryDelay());
        self::assertSame([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'ActiveCampaign-PHP-SDK/' . ActiveCampaignAPI::VERSION,
            'Api-Token' => 'token',
        ], $config->getDefaultHeaders());
    }

    public function testNamedArguments(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com', timeout: 60, maxRetries: 3, retryDelay: 0.5);

        self::assertSame(60, $config->getTimeout());
        self::assertSame(3, $config->getMaxRetries());
        self::assertSame(0.5, $config->getRetryDelay());
    }

    public function testSetters(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com');
        $config->setApiToken('other');
        $config->setAccountUrl('  https://other.api-us1.com//  ');
        $config->setTimeout(1);
        $config->setConnectTimeout(2);
        $config->setMaxRetries(3);
        $config->setRetryDelay(4.5);

        self::assertSame('other', $config->getApiToken());
        self::assertSame('https://other.api-us1.com', $config->getAccountUrl());
        self::assertSame(1, $config->getTimeout());
        self::assertSame(2, $config->getConnectTimeout());
        self::assertSame(3, $config->getMaxRetries());
        self::assertSame(4.5, $config->getRetryDelay());
        self::assertSame('other', $config->getDefaultHeaders()['Api-Token']);
    }

    public function testCustomHeadersAreMergedAndStringified(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com', defaultHeaders: [
            'User-Agent' => 'my-app/1.0',
            'X-Int' => 42,
            'X-Bool' => true,
            'X-List' => ['a', 1],
        ]);
        $config->setDefaultHeaders(['X-Later' => 1.5]);

        $headers = $config->getDefaultHeaders();
        self::assertSame('my-app/1.0', $headers['User-Agent']);
        self::assertSame('42', $headers['X-Int']);
        self::assertSame('1', $headers['X-Bool']);
        self::assertSame(['a', '1'], $headers['X-List']);
        self::assertSame('1.5', $headers['X-Later']);
        self::assertSame('token', $headers['Api-Token']);
    }

    public function testApiTokenHeaderCannotBeOverridden(): void
    {
        $config = new Configuration('token', 'https://account.api-us1.com', defaultHeaders: ['Api-Token' => 'nope']);

        self::assertSame('token', $config->getDefaultHeaders()['Api-Token']);
    }

    public function testInvalidHeaderValueIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Configuration('token', 'https://account.api-us1.com', defaultHeaders: ['X-Obj' => new \stdClass()]);
    }
}
