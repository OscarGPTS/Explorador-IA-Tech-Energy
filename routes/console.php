<?php

use App\Jobs\RunScrapingJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scraping automático en segundo plano (cola). Requiere `queue:work` y `schedule:work`.
Schedule::job(new RunScrapingJob('news'))->everySixHours()->withoutOverlapping();
Schedule::job(new RunScrapingJob('recommendations'))->dailyAt('06:30')->withoutOverlapping();
