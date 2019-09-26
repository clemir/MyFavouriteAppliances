<?php

namespace App\Services;

use Goutte\Client;

class CrawlerServiceRepository implements ServiceRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAllAppliances()
    {
        $smallAppliancesCrawler = $this->client->request('GET', config('app.url1'));
        $smallAppliances = new AppliancesCrawlerConverter($smallAppliancesCrawler);

        $dishwashersCrawler = $this->client->request('GET', config('app.url2'));
        $dishwashers = new AppliancesCrawlerConverter($dishwashersCrawler);

        return collect($smallAppliances->getAll())->merge($dishwashers->getAll());
    }
}
