<?php

declare(strict_types=1);

namespace xdubois\ActiveCampaign\Tests;

use xdubois\ActiveCampaign\ActiveCampaignClient;

/**
 * Test double exposing request() and recording sleeps instead of pausing.
 *
 * @phpstan-import-type RequestOptions from ActiveCampaignClient
 */
final class RecordingEndpoint extends ActiveCampaignClient
{
    /** @var list<float> */
    public array $sleeps = [];

    /**
     * @param RequestOptions $options
     * @return array<mixed>
     */
    public function call(string $method, string $endpoint, array $options = []): array
    {
        return $this->request($method, $endpoint, $options);
    }

    protected function sleep(float $seconds): void
    {
        $this->sleeps[] = $seconds;
    }
}
