<?php

namespace Tests\Unit;

use App\Appliance;
use Goutte\Client;
use Tests\TestCase;
use GuzzleHttp\Middleware;
use App\Jobs\SyncAppliances;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Queue;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SyncAppliancesJobTest extends TestCase
{
    use RefreshDatabase;

    protected function getGuzzle(array $responses = [], array $extraConfig = [])
    {
        if (empty($responses)) {
            $responses = [
                new GuzzleResponse(200, [], file_get_contents(__DIR__.'/../fixtures/web.html')),
                new GuzzleResponse(200, [], file_get_contents(__DIR__.'/../fixtures/web2.html')),
                new GuzzleResponse(200, [], file_get_contents(__DIR__.'/../fixtures/dishwashers.html')),
                new GuzzleResponse(200, [], file_get_contents(__DIR__.'/../fixtures/dishwashers2.html'))
            ];
        }
        $this->mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($this->mock);
        $this->history = [];
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(array_merge(array('redirect.disable' => true, 'base_uri' => '', 'handler' => $handlerStack), $extraConfig));
        return $guzzle;
    }

    public function testDispachJob()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle());

        dispatch_now(new SyncAppliances);
        
        $this->assertCount(40, Appliance::all());
    }
}
