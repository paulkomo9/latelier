<?php

namespace App\Observers;

use App\Models\Bookings;
use App\Models\Appointments;

class BookingsObserver
{
    /**
     * Handle the Bookings "created" event.
     */
    public function created(Bookings $bookings): void
    {
        Appointments::where('id', $bookings->appointment_id)->increment('slots_taken');
    }

    /**
     * Handle the Bookings "updated" event.
     */
    public function updated(Bookings $bookings): void
    {

        if ($bookings->isDirty('appointment_id')) {
            // Recalculate both old and new
            $this->recalculateSlotsTaken($bookings->getOriginal('appointment_id'));
            $this->recalculateSlotsTaken($bookings->appointment_id);
        }
        
    }

    /**
     * Handle the Bookings "deleted" event.
     */
    public function deleted(Bookings $bookings): void
    {
        Appointments::where('id', $bookings->appointment_id)->decrement('slots_taken');
    }

    /**
     * Handle the Bookings "restored" event.
     */
    public function restored(Bookings $bookings): void
    {
        //
    }

    /**
     * Handle the Bookings "force deleted" event.
     */
    public function forceDeleted(Bookings $bookings): void
    {
        //
    }

    /**
     * Recalculate slots taken
     */
    protected function recalculateSlotsTaken($appointmentId)
    {
        $count = Bookings::where('appointment_id', $appointmentId)
                    ->whereNull('deleted_at')
                    ->count();

        Appointments::where('id', $appointmentId)->update(['slots_taken' => $count]);
    }
}
