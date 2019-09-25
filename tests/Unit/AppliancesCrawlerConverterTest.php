<?php

namespace Tests\Unit;

use Goutte\Client;
use Tests\TestCase;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\AppliancesCrawlerConverter;
use Illuminate\Foundation\Testing\WithFaker;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppliancesCrawlerConverterTest extends TestCase
{
    protected $history;
    /** @var MockHandler */
    protected $mock;
    protected function getGuzzle(array $responses = [], array $extraConfig = [])
    {
        if (empty($responses)) {
            $responses = [new GuzzleResponse(200, [], '<html><body><p>Hi</p></body></html>')];
        }
        $this->mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($this->mock);
        $this->history = [];
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(array_merge(array('redirect.disable' => true, 'base_uri' => '', 'handler' => $handlerStack), $extraConfig));
        return $guzzle;
    }

    public function testApplianceCrawlerConvert()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/web.html')),
        ]);

        $converter = new AppliancesCrawlerConverter($this->getCrawler($guzzle));

        $this->assertCount(20, $converter->getAll());
    }

    function testGetTitle()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/oneAppliance.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);


        $this->assertEquals(
            'Sage Oracle Touch Brushed Stainless Steel Bean To Cup Coffee Machine BES990BSS',
            $converter->getTitle($converter->getProductNode())
        );
    }

    function testGetUrl()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/oneAppliance.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);


        $this->assertEquals(
            'https://www.appliancesdelivered.ie/sage-oracle-touch-brushed-stainless-steel-bean-to-cup-coffee-machine-bes990bss/5004',
            $converter->getUrl($converter->getProductNode())
        );
    }

    function testGetModel()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/oneAppliance.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);


        $this->assertEquals(
            'BES990BSS',
            $converter->getModel($converter->getProductNode())
        );
    }

    function testGetPrice()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/oneAppliance.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);


        $this->assertEquals(
            '€2,495.95',
            $converter->getPrice($converter->getProductNode())
        );
    }

    protected function getCrawler($guzzle)
    {
        $client = new Client();
        $client->setClient($guzzle);
        return $client->request('GET', 'https://www.appliancesdelivered.ie/');
    }
}
