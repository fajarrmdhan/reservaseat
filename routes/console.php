<?php

use App\Models\Reservasi;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservasi:cancel-expired-pending', function () {
    $cancelled = Reservasi::cancelExpiredPending();

    $this->info("{$cancelled} reservasi pending dibatalkan otomatis.");
})->purpose('Cancel pending reservations that passed the 30 minute tolerance');

Schedule::command('reservasi:cancel-expired-pending')->everyMinute();
