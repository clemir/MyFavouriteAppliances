<?php

use Goutte\Client;
use App\Jobs\SyncAppliances;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('sync', function () {
    dispatch_now(new SyncAppliances);
    $this->comment('Sync ready!');
})->describe('Sync appliances delivered.');
