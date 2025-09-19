<?php
namespace App\Http\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\BookingSuccessful;
use App\Models\Bookings;
use App\Models\BookingsView;
use Auth;
use Carbon\Carbon;
use Throwable;

class BookingService
{
    
    /**
     * Search Bookings
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchBookings($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return BookingsView::find($criteria['id']);
                }

                // Build query
                $query = BookingsView::when(!empty($criteria['id']), function ($query) use ($criteria) {
                                                if (is_array($criteria['id'])) {
                                                    if (!in_array(0, $criteria['id'], true)) {
                                                        return $query->whereIn('id', $criteria['id']);
                                                    }
                                                } else {
                                                    if ((int) $criteria['id'] !== 0) {
                                                        return $query->where('id', '=', (int) $criteria['id']);
                                                    }
                                                }
                                                return $query;
                                            })
                                            ->when(!empty($criteria['status']), function ($query) use ($criteria) {
                                                return $query->where('booking_status', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('title', 'LIKE', "%{$criteria['searchword']}%")
                                                    ->orWhere('description', 'LIKE', "%{$criteria['searchword']}%");
                                                });
                                            })
                                            ->whereNull('deleted_at'); //Add this to exclude soft-deleted records;

                // Choose terminal method
                switch ($method) {
                    case 'paginate':
                        if (!empty($criteria['per_page']) && is_numeric($criteria['per_page']) && $criteria['per_page'] > 0) {
                            return $query->paginate($criteria['per_page']);
                        }
                        // fallback to get if per_page is not valid
                        return $query->get();

                    case 'first':
                        return $query->first();

                    case 'exists':
                        return $query->exists();

                    case 'get':
                    default:
                        return $query->get();
                }

        } catch (Throwable $e) {
            // Custom logging
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/booking-service-error.log')
            ])->error("Search Bookings Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.booking.search_failed')
                        ]; 
        }
    }

    
    /**
     * Create/Update Booking Details
     * @param int $booking_id
     * @param string $reference
     * @param int $user_id
     * @param int $appointment_id
     * @param int $booking_status
     * @param int $attendance_marked_by
     * @param date $attended_at
     * 
     * @return array $arrResponse
     */
    public function updatecreateBooking($booking_id, $reference, $user_id, $appointment_id, $booking_status, $attendance_marked_by, $attended_at)
    {
        try {
                $user = Auth::user();

                $booking = Bookings::updateOrCreate([
                                        'id' => $booking_id,
                                    ],
                                    [
                                        'appointment_id' => $appointment_id,
                                        'reference' => $reference,
                                        'user_id' => $user_id,
                                        'booking_status' => $booking_status,
                                        'attendance_marked_by' => $attendance_marked_by,
                                        'attended_at' => $attended_at
                                    ]);


                //dispatch successful event
                event(new BookingSuccessful($booking, $user));

            return $arrResponse = [
                            "success" => true,
                            "booking" => $booking
                        ];         

        } catch (Throwable $e) {
                // Custom logging to 'booking-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/booking-service-error.log')
                ])->error("Booking Update/Creation Failed: " . $e->getMessage(), [
                    'booking_id' => $booking_id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.booking.update_failed')
                        ]; 

        }       
    }
}