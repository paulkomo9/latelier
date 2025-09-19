<?php

namespace App\Listeners;

use App\Events\PaymentSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserPackages;

class CreateUserPackageAfterPayment
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
    public function handle(PaymentSuccessful $event): void
    {
        $payment = $event->payment;
        $user = $event->user;
        $package = $payment->package; // assuming a relationship

        UserPackages::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'payment_id' => $payment->id,
            'purchased_by' => $user->id,
            'sessions_total' => $package->sessions_total,
            'sessions_used' => 0,
            'sessions_remaining' => $package->sessions_total,
            'validity_quantity' => $package->validity_quantity,
            'validity_unit' => $package->validity_unit,
            'usr_pkg_status' => 21,
            'notes' => 'Created from successful payment'
        ]);
    }
}
