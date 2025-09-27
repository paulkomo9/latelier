<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\UserPackages;
use Carbon\Carbon;

class CheckUserPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-packages:check-expired ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription_status for expired or depleted user packages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $affectedPackages = UserPackages::where(function ($query) use ($now) {
                $query->where('expires_at', '<', $now)
                      ->orWhere('sessions_remaining', '=', 0);
            })
            ->where('subscription_status', '!=', 22)
            ->get();

        $count = 0;

        foreach ($affectedPackages as $package) {
            $package->subscription_status = 22;
            $package->save();
            $count++;
        }

        // remove this before go live
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/latelier_expired_packages.log')
        ])->info('Updated ' . $count . ' user package(s) to subscription_status 22.: ' . Carbon::now());



    }
}
