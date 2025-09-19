<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Bookings;
use Illuminate\Support\Facades\Log;
use Throwable;
use Auth;

class PreventDoubleBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{

                $user = Auth::user();

                // Get the appointment ID from the route
                $appointmentId = $request->route('id');

                // Check if the user has an active booking for this appointment
                $alreadyBooked = Bookings::where('user_id', $user->id)
                    ->where('appointment_id', $appointmentId)
                    ->whereNull('deleted_at') // exclude soft deleted
                    ->whereIn('booking_status', [15, 1, 20]) // adjust to match your active statuses
                    ->exists();

                if ($alreadyBooked) {
                    return redirect()->back()->with('error', 'You have already booked this session.');
                }

                return $next($request);

        } catch (Throwable $e) {
                // Custom logging to 'prevent-double-booking-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/prevent-double-booking-error.log')
                ])->error("Prevent Double Booking Failed: " . $e->getMessage(), [
                    'user_id' => $request->user()->id ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 
            return response(view('errors.500')); 

        } 
    }
}
