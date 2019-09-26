<?php

namespace App\Jobs;

use App\Appliance;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use App\Services\ServiceRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\AppliancesCrawlerConverter;

class SyncAppliances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ServiceRepository $repository)
    {
        foreach ($repository->getAllAppliances() as $appliance) {
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
