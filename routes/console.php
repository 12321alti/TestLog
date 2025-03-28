<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SyncPostCommentsCommand;
use App\Console\Commands\SyncUsersCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$schedule = app()->make(Schedule::class);


$schedule->command(SyncUsersCommand::class)->daily();

$schedule->command(SyncPostCommentsCommand::class)->dailyAt('01:00'); 