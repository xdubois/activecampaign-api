<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\RequestInterface;
use xdubois\ActiveCampaign\ActiveCampaignAPI;
use xdubois\ActiveCampaign\Configuration;

abstract class TestCase extends BaseTestCase
{
    /** @var list<array{request: RequestInterface, options: array<mixed>}> */
    protected array $history = [];

    protected MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->history = [];
        $this->mockHandler = new MockHandler();
    }

    /**
     * @param list<Response|\Throwable> $responses
     */
    protected function createHttpClient(array $responses = []): Client
    {
        $this->mockHandler->append(...$responses);
        $stack = HandlerStack::create($this->mockHandler);
        $stack->push(Middleware::tap(null, function (RequestInterface $request, array $options): void {
            $this->history[] = ['request' => $request, 'options' => $options];
        }));

        return new Client(['handler' => $stack, 'base_uri' => 'https://account.api-us1.com']);
    }

    protected function createConfiguration(int $maxRetries = 0, float $retryDelay = 0.0): Configuration
    {
        return new Configuration('secret-token', 'https://account.api-us1.com/', 30, 10, $maxRetries, $retryDelay);
    }

    /**
     * @param list<Response|\Throwable> $responses
     */
    protected function createApi(array $responses = [], ?Configuration $config = null): ActiveCampaignAPI
    {
        return new ActiveCampaignAPI($config ?? $this->createConfiguration(), null, $this->createHttpClient($responses));
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    protected static function jsonResponse(int $status, array $data = [], array $headers = []): Response
    {
        return new Response($status, $headers + ['Content-Type' => 'application/json'], json_encode($data, JSON_THROW_ON_ERROR));
    }

    protected function lastRequest(): RequestInterface
    {
        self::assertNotEmpty($this->history, 'No request was sent');

        return $this->history[count($this->history) - 1]['request'];
    }

    /**
     * @return array<mixed>
     */
    protected function lastRequestOptions(): array
    {
        self::assertNotEmpty($this->history, 'No request was sent');

        return $this->history[count($this->history) - 1]['options'];
    }
}
