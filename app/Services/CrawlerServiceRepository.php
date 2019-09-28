<?php

namespace App\Services;

use Goutte\Client;
use Illuminate\Support\Facades\Log;

class CrawlerServiceRepository implements ServiceRepository
{
    private $client;

    private $appliances;

    private $pageTotalQuantity;

    private $pageBaseUri;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAllAppliances()
    {
        //$this->getAppliancesFromUrl(config('app.url1'));
        $this->getAppliancesFromUrl(config('app.url2'));

        return $this->appliances;
    }

    public function getAppliancesFromUrl($url)
    {
        $this->setInformationFor($url);
        
        for ($pageNum=1; $pageNum <= $this->pageTotalQuantity; $pageNum++) {
            $page = "{$this->pageBaseUri}&page=$pageNum";
            Log::channel('log-sync')->notice("Get appliances from: $page");
            $this->getAppliancesFrom($page);
        }
    }

    public function setInformationFor($page)
    {
        $converter = $this->getConverterFrom($page);
        $this->pageTotalQuantity = $converter->getPageQuantity();
        $this->pageBaseUri = $converter->getPageBaseUrl();
    }

    public function getAppliancesFrom($page)
    {
        
        $converter = $this->getConverterFrom($page);
        $converter->getProductNodes()->each(function ($node) use ($converter) {
            $this->appliances[] = $converter->getAppliance($node);
        });
    }

    public function getConverterFrom($page)
    {
        return $this->converterFor($this->crawlerFrom($page));
    }

    public function crawlerFrom($page)
    {
        dd($this->client);
        return $this->client->request('GET', $page);
    }

    public function converterFor($crawler)
    {
        return new AppliancesCrawlerConverter($crawler);
    }
}
