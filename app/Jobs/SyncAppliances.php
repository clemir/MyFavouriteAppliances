<?php

namespace App\Jobs;

use Exception;
use App\Appliance;
use Goutte\Client;
use App\Mail\SyncFailed;
use Illuminate\Bus\Queueable;
use App\Services\ServiceRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\AppliancesCrawlerConverter;

class SyncAppliances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 120;

    public $retryAfter = 3;

    public function handle(ServiceRepository $repository)
    {
        $appliances = $repository->getAllAppliances();
        if (count($appliances) < 0) {
            Log::channel('log-sync')->error('There is a problem with appliances sync');
        } else {
            Log::channel('log-sync')->info('Begin Appliances updating');
            Appliance::where('status', true)->update(['status' => false]);
            foreach ($appliances as $appliance) {
                Appliance::updateOrCreate(
                    [
                        'model' => $appliance['model'],
                        'title' => $appliance['title'],
                    ],
                    [
                        'price' => $appliance['price'],
                        'description' => $appliance['description'],
                        'url' => $appliance['url'],
                        'image' => $appliance['image'],
                        'status' => true,
                    ]
                );
            }
            Log::channel('log-sync')->info('Finish Appliances updating');
            Cache::flush();
        }
    }

    public function failed(Exception $exception)
    {
        Mail::send(new SyncFailed());
    }
}
