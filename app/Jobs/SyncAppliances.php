<?php

namespace App\Jobs;

use App\Appliance;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\AppliancesCrawlerConverter;

class SyncAppliances implements ShouldQueue
{
    private $client;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $smallAppliancesCrawler = $this->client->request('GET', config('app.url1'));
        $smallAppliances = new AppliancesCrawlerConverter($smallAppliancesCrawler);

        $dishwashersCrawler = $this->client->request('GET', config('app.url2'));
        $dishwashers = new AppliancesCrawlerConverter($dishwashersCrawler);

        $appliances = collect($smallAppliances->getAll())->merge($dishwashers->getAll());

        foreach ($appliances as $appliance) {
            Appliance::updateOrCreate([
                    'model' => $appliance['model'],
                    'title' => $appliance['title'],
                ],
                [
                    'price' => $appliance['price'],
                    'description' => $appliance['description'],
                    'url' => $appliance['url'],
                    'image' => $appliance['image'],
                ]
            );
        }
    }
}
