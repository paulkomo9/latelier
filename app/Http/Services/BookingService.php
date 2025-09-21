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


    /**
     * Display Bookings DataTable Data
     * @param int $limit_data
     * @param int $start_data
     * @param var $order_column
     * @param var $order_dir
     * @param string $search
     * @param int $draw
     * @param var $var
     * @param Request $request
     * 
     * @return array<string, string> $arrdataTable
     */
    public function displayBookingsTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'reference',
                    2 => 'title',
                    3 => 'session_date',
                    4 => 'start_time',
                    5 => 'end_time',
                    6 => 'booked_by_name',
                    7 => 'booking_status_name',
                    8 => 'trainer_name',
                    9 => 'slots',
                   10 => 'slots_taken',
                   11 => 'location',
                   12 => 'created_at',
                   13 => 'marked_by_name',
            );

        

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();

            $bookings = BookingsView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status filter early (before counts)
            $bookings = $bookings->when($var !== null && $var !== 0, function ($query) use ($var) {
                return $query->where('booking_status', $var);
            });

            // Fetch the totalData data after applying all scope filters
            $totalData = $bookings->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the bookings data after applying all scope filters
                    $bookings = $bookings->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $bookings =  $bookings->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%")
                                                                          ->orWhere('reference','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $bookings->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%")
                                                                          ->orWhere('reference','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($bookings->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($bookings as $booking) {

                        $edit =  $booking->id;
                        $delete =  $booking->id;

                        $nestedData['id'] = $counter;
                        $nestedData['reference'] = $booking->reference;
                        $nestedData['title'] = $booking->title;
                        $nestedData['start_date'] = Carbon::parse($booking->session_date)->locale(app()->getLocale())->translatedFormat('l jS F Y');
                        $nestedData['start_time'] = Carbon::parse($booking->start_time)->locale(app()->getLocale())->translatedFormat('h:i a');
                        $nestedData['end_time'] = Carbon::parse($booking->end_time)->locale(app()->getLocale())->translatedFormat('h:i a');
                        $nestedData['booked_by'] = $booking->booked_by_name;
                        $nestedData['trainer'] = $booking->trainer_name;
                        $nestedData['slots'] = $booking->slots;
                        $nestedData['slots_taken'] = $booking->slots_taken;
                        $nestedData['location'] = $booking->location;
                        $nestedData['booked_on'] = Carbon::parse($booking->created_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['attended_at'] = $booking->attended_at 
                            ? Carbon::parse($booking->attended_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a') 
                            : __('Pending');

                        $nestedData['marked_by'] = $booking->marked_by_name ? $booking->marked_by_name : __('Pending');

                        $nestedData['booking_status'] = "<span class='".$booking->booking_status_name_css."'>". __('messages.status.' . strtolower($booking->booking_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editBooking' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editBooking'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deleteBooking' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deleteBooking'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.cancel') ."</a>";
                        $assign_trainer_link = "&emsp;<a href='javascript:void(0)' data-id='$edit' title='Assign Trainer' class='btn btn-info assignTrainer'><i class='mdi mdi-account-plus'></i> Assign</a>";
                        $mark_attendance_link = "&emsp;<a href='javascript:void(0)' data-id='$edit' title='Mark Attendance' class='btn btn-success markAttendance'><i class='mdi mdi-check-circle'></i> Mark Attendance</a>";
                        $view_bookings_link = "&emsp;<a href='javascript:void(0)' data-id='$edit' title='View Bookings' class='btn btn-primary viewBookings'><i class='mdi mdi-eye'></i> View</a>";

                        //check is user has permissions to edit and show link
                        $edit_display = !$isAdmin && (!isset($permissions['edit']) || !$permissions['edit']) ? '' : $edit_link;

                        //check if user has permissions to deactivate and show link
                        $deactivate_display = !$isAdmin && (!isset($permissions['deactivate']) || !$permissions['deactivate']) ? '' : $deactivate_link;

                        $nestedData['options'] = $edit_display."".$deactivate_display;

                        $data[] = $nestedData;

                        $counter++; // Increment counter for the next record

                    }
            }

        return $arrdataTable =  array(
                    "draw"            => intval($draw),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data 
                    );

    }
}