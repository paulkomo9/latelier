<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Schedule;

//list the schedules
Schedule::command('user-packages:check-expired')->everyFiveMinutes()->WithoutOverlapping(); // or ->hourly(), etc.
Schedule::command('calendar-entries:check-expired')->everyFiveMinutes()->WithoutOverlapping(); // or ->hourly(), etc.
