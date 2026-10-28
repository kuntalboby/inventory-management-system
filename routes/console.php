<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Schedule::command('report:send-stock-pdf')->dailyAt('19:12');
//Schedule::command('report:send-stock-pdf')->daily();



Schedule::command('daily:report-generator')->dailyAt('14:00');
Schedule::command('report:send-stock-pdf')->dailyAt('14:01');
Schedule::command('backup:tables')->dailyAt('14:03');

