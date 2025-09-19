<?php

namespace App\Listeners;

use App\Events\BookingSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserPackages;

class UpdateUserPackageAfterBooking
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingSuccessful $event): void
    {
        $user = $event->user;

        // Find active package
        $activePackage = UserPackages::where('user_id', $user->id)
            ->where('sessions_remaining', '>', 0)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at') // pick the soonest-to-expire one
            ->first();

        if (!$activePackage) {
            \Log::warning("No active package found for user #{$user->id} when booking #{$event->booking->id} was made.");
            return;
        }

        // Update usage
        $activePackage->increment('sessions_used');
        $activePackage->sessions_remaining = $activePackage->sessions_total - $activePackage->sessions_used;
        $activePackage->save();
    }
}
