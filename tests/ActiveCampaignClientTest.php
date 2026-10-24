<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use xdubois\ActiveCampaign\ActiveCampaignClient;
use xdubois\ActiveCampaign\Exceptions\ActiveCampaignException;
use xdubois\ActiveCampaign\Exceptions\AuthenticationException;
use xdubois\ActiveCampaign\Exceptions\NotFoundException;
use xdubois\ActiveCampaign\Exceptions\RateLimitException;
use xdubois\ActiveCampaign\Exceptions\ValidationException;

final class ActiveCampaignClientTest extends TestCase
{
    /**
     * @param list<Response|\Throwable> $responses
     */
    private function createEndpoint(array $responses, int $maxRetries = 0, float $retryDelay = 0.0): RecordingEndpoint
    {
        return new RecordingEndpoint($this->createConfiguration($maxRetries, $retryDelay), null, $this->createHttpClient($responses));
    }

    public function testSuccessfulResponseIsDecoded(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(200, ['contact' => ['id' => '1']])]);

        self::assertSame(['contact' => ['id' => '1']], $endpoint->call('GET', '/api/3/contacts/1'));
    }

    public function testEmptyBodyReturnsEmptyArray(): void
    {
        $endpoint = $this->createEndpoint([new Response(200, [], '')]);

        self::assertSame([], $endpoint->call('DELETE', '/api/3/contacts/1'));
    }

    public function testScalarJsonReturnsEmptyArray(): void
    {
        $endpoint = $this->createEndpoint([new Response(200, [], 'true')]);

        self::assertSame([], $endpoint->call('GET', '/api/3/contacts/1'));
    }

    public function testInvalidJsonThrows(): void
    {
        $endpoint = $this->createEndpoint([new Response(200, [], '{not json')]);

        try {
            $endpoint->call('GET', '/api/3/contacts/1');
            self::fail('Expected exception');
        } catch (ActiveCampaignException $e) {
            self::assertStringStartsWith('Invalid JSON response:', $e->getMessage());
            self::assertSame(200, $e->getCode());
            self::assertSame('{not json', $e->getResponseBody());
            self::assertInstanceOf(\JsonException::class, $e->getPrevious());
        }
    }

    public function testRequestCarriesConfiguredHeadersAndTimeouts(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(200)]);
        $endpoint->call('POST', '/api/3/contacts', ['json' => ['contact' => ['email' => 'a@b.c']]]);

        $request = $this->lastRequest();
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://account.api-us1.com/api/3/contacts', (string) $request->getUri());
        self::assertSame('secret-token', $request->getHeaderLine('Api-Token'));
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame('application/json', $request->getHeaderLine('Accept'));
        self::assertStringStartsWith('ActiveCampaign-PHP-SDK/', $request->getHeaderLine('User-Agent'));
        self::assertSame('{"contact":{"email":"a@b.c"}}', (string) $request->getBody());

        $options = $this->lastRequestOptions();
        self::assertSame(30, $options['timeout']);
        self::assertSame(10, $options['connect_timeout']);
    }

    public function testConfigurationChangesApplyToSubsequentRequests(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(200), self::jsonResponse(200)]);
        $endpoint->call('GET', '/api/3/users/me');
        self::assertSame(30, $this->lastRequestOptions()['timeout']);

        $endpoint->getConfiguration()->setTimeout(5);
        $endpoint->getConfiguration()->setDefaultHeaders(['X-Custom' => 'yes']);
        $endpoint->call('GET', '/api/3/users/me');

        self::assertSame(5, $this->lastRequestOptions()['timeout']);
        self::assertSame('yes', $this->lastRequest()->getHeaderLine('X-Custom'));
    }

    public function testPerRequestHeadersOverrideDefaults(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(200)]);
        $endpoint->call('GET', '/api/3/users/me', ['headers' => ['Accept' => 'text/plain']]);

        self::assertSame('text/plain', $this->lastRequest()->getHeaderLine('Accept'));
        self::assertSame('secret-token', $this->lastRequest()->getHeaderLine('Api-Token'));
    }

    public function testUnauthorizedThrowsAuthenticationException(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(401, ['message' => 'Invalid API key'])], 3);

        try {
            $endpoint->call('GET', '/api/3/users/me');
            self::fail('Expected exception');
        } catch (AuthenticationException $e) {
            self::assertSame('Invalid API key', $e->getMessage());
            self::assertSame(401, $e->getCode());
            self::assertSame(401, $e->getStatusCode());
            self::assertSame('GET', $e->getContext()['method']);
            self::assertSame('https://account.api-us1.com/api/3/users/me', $e->getContext()['uri']);
            self::assertSame(['message' => 'Invalid API key'], $e->getContext()['error_data']);
        }

        self::assertCount(1, $this->history, '4xx errors must not be retried');
        self::assertSame([], $endpoint->sleeps);
    }

    public function testForbiddenThrowsAuthenticationException(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(403)]);

        $this->expectException(AuthenticationException::class);
        $endpoint->call('GET', '/api/3/users/me');
    }

    public function testNotFoundThrowsNotFoundException(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(404, ['message' => 'No Result found for Subscriber with id 99'])]);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No Result found for Subscriber with id 99');
        $endpoint->call('GET', '/api/3/contacts/99');
    }

    public function testUnprocessableEntityThrowsValidationExceptionWithErrors(): void
    {
        $errors = [
            ['title' => 'Email address already exists in the system', 'detail' => '', 'code' => 'duplicate', 'source' => ['pointer' => '/data/attributes/email']],
            ['title' => 'Phone is invalid'],
        ];
        $endpoint = $this->createEndpoint([self::jsonResponse(422, ['errors' => $errors])]);

        try {
            $endpoint->call('POST', '/api/3/contacts', ['json' => []]);
            self::fail('Expected exception');
        } catch (ValidationException $e) {
            self::assertSame($errors, $e->getValidationErrors());
            self::assertSame('Email address already exists in the system; Phone is invalid', $e->getMessage());
            self::assertSame(422, $e->getCode());
        }
    }

    public function testOtherClientErrorsThrowBaseException(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(400, ['error' => 'Bad request'])], 2);

        try {
            $endpoint->call('GET', '/api/3/contacts');
            self::fail('Expected exception');
        } catch (ActiveCampaignException $e) {
            self::assertSame(ActiveCampaignException::class, get_class($e));
            self::assertSame('Bad request', $e->getMessage());
            self::assertSame(400, $e->getCode());
        }

        self::assertCount(1, $this->history);
    }

    public function testNonJsonErrorBodyFallsBackToGuzzleMessage(): void
    {
        $endpoint = $this->createEndpoint([new Response(404, [], '<html>Not found</html>')]);

        try {
            $endpoint->call('GET', '/api/3/contacts/1');
            self::fail('Expected exception');
        } catch (NotFoundException $e) {
            self::assertStringContainsString('404', $e->getMessage());
            self::assertSame('<html>Not found</html>', $e->getResponseBody());
            self::assertArrayNotHasKey('error_data', $e->getContext());
        }
    }

    public function testRateLimitIsRetriedHonouringRetryAfter(): void
    {
        $endpoint = $this->createEndpoint([
            self::jsonResponse(429, ['message' => 'Too many requests'], ['Retry-After' => '2']),
            self::jsonResponse(200, ['ok' => true]),
        ], 1, 1.0);

        self::assertSame(['ok' => true], $endpoint->call('GET', '/api/3/contacts'));
        self::assertCount(2, $this->history);
        self::assertSame([2.0], $endpoint->sleeps);
    }

    public function testRateLimitThrowsOnceRetriesAreExhausted(): void
    {
        $endpoint = $this->createEndpoint([
            self::jsonResponse(429, [], ['Retry-After' => '1']),
            self::jsonResponse(429, [], ['Retry-After' => '3']),
        ], 1);

        try {
            $endpoint->call('GET', '/api/3/contacts');
            self::fail('Expected exception');
        } catch (RateLimitException $e) {
            self::assertSame(3, $e->getRetryAfter());
            self::assertSame(429, $e->getCode());
        }

        self::assertCount(2, $this->history);
        self::assertSame([1.0], $endpoint->sleeps);
    }

    public function testRetryAfterIsCapped(): void
    {
        $endpoint = $this->createEndpoint([
            self::jsonResponse(429, [], ['Retry-After' => '3600']),
            self::jsonResponse(200),
        ], 1);

        $endpoint->call('GET', '/api/3/contacts');

        self::assertSame([(float) ActiveCampaignClient::MAX_RETRY_DELAY_SECONDS], $endpoint->sleeps);
    }

    public function testRetryAfterHttpDateIsParsed(): void
    {
        $endpoint = $this->createEndpoint([
            self::jsonResponse(429, [], ['Retry-After' => gmdate('D, d M Y H:i:s \G\M\T', time() + 10)]),
            self::jsonResponse(200),
        ], 1);

        $endpoint->call('GET', '/api/3/contacts');

        self::assertCount(1, $endpoint->sleeps);
        self::assertGreaterThanOrEqual(8.0, $endpoint->sleeps[0]);
        self::assertLessThanOrEqual(10.0, $endpoint->sleeps[0]);
    }

    public function testRateLimitWithoutRetryAfterHasNullRetryAfter(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(429)]);

        try {
            $endpoint->call('GET', '/api/3/contacts');
            self::fail('Expected exception');
        } catch (RateLimitException $e) {
            self::assertNull($e->getRetryAfter());
        }
    }

    public function testServerErrorsUseExponentialBackoff(): void
    {
        $endpoint = $this->createEndpoint([
            self::jsonResponse(503),
            self::jsonResponse(500, ['message' => 'boom']),
            self::jsonResponse(502),
        ], 2, 0.5);

        try {
            $endpoint->call('GET', '/api/3/contacts');
            self::fail('Expected exception');
        } catch (ActiveCampaignException $e) {
            self::assertStringStartsWith('Server error: ', $e->getMessage());
            self::assertSame(502, $e->getCode());
            self::assertSame(502, $e->getStatusCode());
        }

        self::assertCount(3, $this->history);
        self::assertSame([0.5, 1.0], $endpoint->sleeps);
    }

    public function testExponentialBackoffIsCapped(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(503), self::jsonResponse(503), self::jsonResponse(200)], 2, 45.0);

        $endpoint->call('GET', '/api/3/contacts');

        self::assertSame([45.0, 60.0], $endpoint->sleeps);
    }

    public function testServerErrorMessageIsExtractedFromBody(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(500, ['message' => 'boom'])]);

        $this->expectException(ActiveCampaignException::class);
        $this->expectExceptionMessage('Server error: boom');
        $endpoint->call('GET', '/api/3/contacts');
    }

    public function testServerErrorIsRetriedThenSucceeds(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(500), self::jsonResponse(200, ['ok' => 1])], 1);

        self::assertSame(['ok' => 1], $endpoint->call('GET', '/api/3/contacts'));
        self::assertCount(2, $this->history);
    }

    public function testConnectionErrorsAreRetried(): void
    {
        $request = new Request('GET', 'https://account.api-us1.com/api/3/contacts');
        $endpoint = $this->createEndpoint([
            new ConnectException('Connection refused', $request),
            self::jsonResponse(200, ['ok' => 1]),
        ], 1);

        self::assertSame(['ok' => 1], $endpoint->call('GET', '/api/3/contacts'));
        self::assertSame([0.0], $endpoint->sleeps);
    }

    public function testConnectionErrorThrowsOnceRetriesAreExhausted(): void
    {
        $request = new Request('GET', 'https://account.api-us1.com/api/3/contacts');
        $endpoint = $this->createEndpoint([new ConnectException('Connection refused', $request)]);

        try {
            $endpoint->call('GET', '/api/3/contacts');
            self::fail('Expected exception');
        } catch (ActiveCampaignException $e) {
            self::assertSame('Connection error: Connection refused', $e->getMessage());
            self::assertSame(0, $e->getCode());
            self::assertNull($e->getStatusCode());
            self::assertInstanceOf(ConnectException::class, $e->getPrevious());
        }
    }

    public function testNoRetriesWhenMaxRetriesIsZero(): void
    {
        $endpoint = $this->createEndpoint([self::jsonResponse(500), self::jsonResponse(200)]);

        $this->expectException(ActiveCampaignException::class);
        $endpoint->call('GET', '/api/3/contacts');
    }

    public function testLegacyConstructorRequiresAccountUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RecordingEndpoint('token');
    }

    public function testLegacyConstructorBuildsConfiguration(): void
    {
        $endpoint = new RecordingEndpoint('token', 'https://account.api-us1.com/');

        self::assertSame('token', $endpoint->getConfiguration()->getApiToken());
        self::assertSame('https://account.api-us1.com', $endpoint->getConfiguration()->getAccountUrl());
        self::assertInstanceOf(\GuzzleHttp\Client::class, $endpoint->getHttpClient());
    }
}
