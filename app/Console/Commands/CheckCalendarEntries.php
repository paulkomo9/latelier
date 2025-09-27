<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Appointments;
use Carbon\Carbon;

class CheckCalendarEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar-entries:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update appointment_status for expired appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $affected = Appointments::where('start_date_time', '<', $now)
            ->where('appointment_status', '!=', 22)
            ->update(['appointment_status' => 22]);

        // Optional log for testing (remove before live)
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/latelier_expired_appointments.log')
        ])->info("Updated $affected appointment(s) to appointment_status 22 at " . $now);
    }
}
