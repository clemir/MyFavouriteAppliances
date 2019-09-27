<?php

namespace Tests\Unit;

use Goutte\Client;
use Tests\TestCase;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\AppliancesCrawlerConverter;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

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

        $this->assertCount(3, $converter->getAll());
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
            $converter->getTitle($converter->getProductNodes())
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
            $converter->getUrl($converter->getProductNodes())
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
            $converter->getModel($converter->getProductNodes())
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
            '249595',
            $converter->getPrice($converter->getProductNodes())
        );
    }

    function testGetImageUrl()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/oneAppliance.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);

        $this->assertEquals(
            'https://img.resized.co/appliancesdelivered/eyJkYXRhIjoie1widXJsXCI6XCJodHRwczpcXFwvXFxcL3MzLWV1LXdlc3QtMS5hbWF6b25hd3MuY29tXFxcL3N0b3JhZ2UuYnV5YW5kc2VsbC5pZVxcXC91cGxvYWRzXFxcLzUwMDRcXFwvNWM5Y2Y4ZWQ5NDczNS1mNWYzZjE0ZWM3NDM0MGU3ZmY5M2E3NmRjYWVkNDIwMlwiLFwid2lkdGhcIjoyNTAsXCJoZWlnaHRcIjpcIlwiLFwiZGVmYXVsdFwiOlwiaHR0cHM6XFxcL1xcXC9zMy1ldS13ZXN0LTEuYW1hem9uYXdzLmNvbVxcXC9zdG9yYWdlLmJ1eWFuZHNlbGwuaWVcXFwvYXBwbGlhbmNlcy1kZWxpdmVyZWQtbm9pbWFnZS1sZy5wbmdcIn0iLCJoYXNoIjoiYmVmY2Q2YzRkYjc2OTA1N2RmMjM2MzQ2MjM1YmUwOTkwNTg2NTA5MiJ9/sage-oracle-touch-brushed-stainless-steel-bean-to-cup-coffee-machine-bes990bss',
            $converter->getImage($converter->getProductNodes())
        );
    }

    function testGetPageQuantity()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/web.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);

        $this->assertEquals(
            2,
            $converter->getPageQuantity()
        );
    }

    function testGetPageBaseUrl()
    {
        $guzzle = $this->getGuzzle([
            new GuzzleResponse(200, [], file_get_contents('./tests/fixtures/web.html')),
        ]);

        $crawler = $this->getCrawler($guzzle);
        $converter = new AppliancesCrawlerConverter($crawler);

        $this->assertEquals(
            'https://www.appliancesdelivered.ie/search/small-appliances?sort=price_desc',
            $converter->getPageBaseUrl()
        );
    }

    protected function getCrawler($guzzle)
    {
        $client = new Client();
        $client->setClient($guzzle);
        return $client->request('GET', 'https://www.appliancesdelivered.ie/');
    }
}
