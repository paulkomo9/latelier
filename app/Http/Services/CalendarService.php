<?php
namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Appointments;
use App\Models\AppointmentsView;
use Auth;
use Carbon\Carbon;
use Throwable;


class CalendarService 
{

    /**
     * Search Calendar Entries
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchCalendarEntries($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return AppointmentsView::find($criteria['id']);
                }

                // Build query
                $query = AppointmentsView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                            ->when(!empty($criteria['start_date_time']) && !empty($criteria['end_date_time']), function ($query) use ($criteria) {
                                                return $query->where(function ($q) use ($criteria) {
                                                    $q->where('start_date_time', '>=', $criteria['start_date_time'])
                                                    ->where('end_date_time', '<=', $criteria['end_date_time']);
                                                });
                                            })
                                            ->when(!empty($criteria['status']), function ($query) use ($criteria) {
                                                return $query->where('appointment_status', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('location', 'LIKE', "%{$criteria['searchword']}%")
                                                      ->orWhere('title', 'LIKE', "%{$criteria['searchword']}%");
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
                'path' => storage_path('logs/calendar-service-error.log')
            ])->error("Search Calendar Entries Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.calendar.search_failed')
                        ]; 
        }
    }



    /**
     * Create/Update Calendar Entry Details
     * @param int $appointment_id
     * @param string $title
     * @param date $start_date_time
     * @param date $end_date_time
     * @param string $description
     * @param int $slots
     * @param int $slots_remaining
     * @param string $location
     * @param float $location_latitude
     * @param float $location_longitude
     * @param bool $is_all_day
     * @param string $category
     * @param string $color
     * @param string $backgroundColor
     * @param string $dragBackgroundColor
     * @param string $borderColor
     * @param bool $is_editable
     * @param int $trainer_id
     * @param string $appointment_image
     * @param int $schedule_id
     * @param int $appointment_status
     * @param int $created_by
     * @param int $updated_by
     * 
     * @return array $arrResponse
     */
    public function updatecreateCalendarEntry($appointment_id, $title, $start_date_time, $end_date_time, $description, $slots, $slots_remaining, $location, $location_latitude, $location_longitude, 
                                $is_all_day, $category, $color, $backgroundColor, $dragBackgroundColor, $borderColor, $is_editable, $trainer_id, $appointment_image, $appointment_status, $created_by, 
                                $updated_by)
    {
        try {
                $appointment = Appointments::updateOrCreate([
                                        'id' => $appointment_id,
                                    ],
                                    [
                                        'title' => $title,
                                        'start_date_time' => $start_date_time,
                                        'end_date_time' => $end_date_time,
                                        'description' => $description,
                                        'slots' => $slots,
                                        'slots' => $slots_remaining,
                                        'location' => $location,
                                        'location_latitude' => $location_latitude,
                                        'location_longitude' => $location_longitude,
                                        'is_all_day' => $is_all_day,
                                        'category' => $category,
                                        'color' => $color,
                                        'backgroundColor' => $backgroundColor,
                                        'dragBackgroundColor' => $dragBackgroundColor,
                                        'borderColor' => $borderColor,
                                        'is_editable' => $is_editable,
                                        'trainer_id' => $trainer_id,
                                        'appointment_image' => $appointment_image,
                                        'appointment_status' => $appointment_status,
                                        'created_by' => $created_by,
                                        'updated_by' => $updated_by
                                    ]);

                // Determine the event type
                $action = $appointment->wasRecentlyCreated ? 'created' : 'updated';
                $data_changed = $appointment->wasChanged();

                //lets create a response and other details needed
                $response = "<strong>".$title."</strong> ".Str::title(__('messages.status.' . strtolower($action)));


            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'calendar-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-service-error.log')
                ])->error("Calendar Entry Update/Creation Failed: " . $e->getMessage(), [
                    'title' => $title,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.calendar.update_failed')
                        ]; 

        }       
    }

    /**
     * delete Calendar Entry 
     * @param int $appointment_id
     * @param date $currdatetime
     * @param int $deleted_by
     * @param int $status
     * 
     * @return array $arrResponse
     */
    public function deleteCalendarEntry($appointment_id, $currdatetime, $deleted_by, $status)
    {
        try {
                $calendar_entry_data = Appointments::findOrFail($appointment_id);
                $title = $calendar_entry_data->title;
                $calendar_entry_data->deleted_at = $currdatetime;
                $calendar_entry_data->deleted_by = $deleted_by;
                $calendar_entry_data->appointment_status = $status;
                $calendar_entry_data->save();

                $action = 'cancelled';
                $response = "<strong>".$title."</strong> ".Str::title(__('messages.status.' . strtolower($action)));

                
            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'calendar-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-service-error.log')
                ])->error("Soft Delete Calendar Entry  Failed: " . $e->getMessage(), [
                    'schedule_id' => $appointment_id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.calendar.deactivate_failed')
                        ];

        }
    }
    

    /**
     * Sync appointments based on the schedule's date range.
     *
     * @param int $scheduleId
     * @param string $title
     * @param string $startDateTime
     * @param string $endDateTime
     * @param int $trainerId
     * @param int $created_by
     * @param int $updated_by
     * @param int $appointment_status
     * @param int $slots
     * @param string $appointment_image
     * @param string $description
     * @param array $config (colors, etc.)
     *
     * @return void
     */
    public function syncAppointmentsFromSchedule($scheduleId, $title, $startDateTime, $endDateTime, $trainerId, $created_by, $updated_by, $appointment_status, $slots, $appointment_image, $description, array $config = []): void 
    {
        try {
            $start = Carbon::parse($startDateTime)->startOfDay();
            $end = Carbon::parse($endDateTime)->startOfDay();
            $startTime = Carbon::parse($startDateTime)->format('H:i:s');
            $endTime = Carbon::parse($endDateTime)->format('H:i:s');

            // Default visual config
            $defaultConfig = [
                'category' => 'time',
                'is_all_day' => false,
                'color' => '#04050c',
                'backgroundColor' => '#f1b44c',
                'dragBackgroundColor' => '#f1b44c',
                'borderColor' => '#f1b44c',
                'is_editable' => false,
            ];

            $config = array_merge($defaultConfig, $config);

            // Delete appointments outside updated date range
            Appointments::where('schedule_id', $scheduleId)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_date_time', '<', $start)
                          ->orWhere('start_date_time', '>', $end);
                })
                ->where('appointment_status', 15)// focus on confirmed appointments
                ->delete();

            // Create new appointments for days in new range
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dayStart = Carbon::parse($date->toDateString() . ' ' . $startTime);
                $dayEnd = Carbon::parse($date->toDateString() . ' ' . $endTime);

                $exists = Appointments::where('schedule_id', $scheduleId)
                    ->whereDate('start_date_time', $dayStart->toDateString())
                    ->where('appointment_status', 15)// focus on confirmed appointments
                    ->exists();

                if (!$exists) {
                    Appointments::create([
                        'schedule_id' => $scheduleId,
                        'title' => $title,
                        'appointment_image' => $appointment_image,
                        'start_date_time' => $dayStart,
                        'end_date_time' => $dayEnd,
                        'trainer_id' => $trainerId,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'appointment_status' => $appointment_status,
                        'slots' => $slots,
                        'description' => $description,
                        'deleted_by' => null,
                        ...$config,
                    ]);
                }
            }
        } catch (Throwable $e) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/calendar-service-error.log')
            ])->error("Appointment Sync Failed: " . $e->getMessage(), [
                'schedule_id' => $scheduleId,
                'exception' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Deactivate all appointments related to a schedule.
     *
     * @param int $scheduleId
     * @param date $currdatetime
     * @param int $deleted_by
     * @param int status
     *
     * @return void
     */
    public function deactivateAppointmentsBySchedule($scheduleId, $currdatetime, $deleted_by, $status): void
    {
        try {
            Appointments::where('schedule_id', $scheduleId)
                ->whereNull('deleted_by') // Optional: Only deactivate if not already deleted
                ->update([
                    'deleted_by' => $deleted_by,
                    'deleted_at' => $currdatetime, // If you track timestamps
                    'appointment_status' => $status
                ]);
        } catch (Throwable $e) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/appointment-service-error.log')
            ])->error("Failed to deactivate appointments: " . $e->getMessage(), [
                'schedule_id' => $scheduleId,
                'exception' => $e->getTraceAsString()
            ]);
        }
    }


    /**
     * Display Calendar Entries DataTable Data
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
    public function displayCalendarEntriesTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'title',
                    2 => 'location',
                    3 => 'start_date_time',
                    4 => 'end_date_time',
                    5 => 'created_at',
                    6 => 'updated_at',
                    7 => 'estimated_time',
                    8 => 'max_enrollment',
                    9 => 'created_by_name',
                   10 => 'updated_by_name',
                   11 => 'deleted_by_name',
                   12 => 'schedule_status_name',
                   13 => 'appointment_status_name',
                );

        

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();

            $appointments = AppointmentsView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status filter early (before counts)
            $appointments = $appointments->when($var !== null && $var !== 0, function ($query) use ($var) {
                return $query->where('appointment_status', $var);
            });

            // Fetch the totalData data after applying all scope filters
            $totalData = $appointments->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the schedules data after applying all scope filters
                    $appointments = $appointments->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $appointments =  $appointments->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $appointments->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($appointments->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($appointments as $appointment) {

                        $edit =  $appointment->id;
                        $delete =  $appointment->id;

                        $nestedData['id'] = $counter;
                        $nestedData['title'] = $appointment->title;
                        $nestedData['description'] = $appointment->description;
                        $nestedData['start_date_time'] = Carbon::parse($appointment->start_date_time)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['end_date_time'] = Carbon::parse($appointment->end_date_time)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['estimated_time'] = $appointment->estimated_time." Minutes";
                        $nestedData['slots'] = $appointment->slots;
                        $nestedData['slots_taken'] = $appointment->slots_taken;
                        $nestedData['slots_remaining'] = $appointment->slots - $appointment->slots_taken;
                        $nestedData['location'] = $appointment->location;
                        $nestedData['description'] = $appointment->description;
                        $nestedData['schedule_image'] = $appointment->schedule_image;
                        $nestedData['appointment_image'] = $appointment->appointment_image;
                        $nestedData['trainer'] = $appointment->trainer_name;
                        $nestedData['created_by'] = $appointment->created_by_name;
                        $nestedData['updated_by'] = $appointment->updated_by_name;    
                        $nestedData['updated_at'] = Carbon::parse($appointment->updated_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['created_at'] = Carbon::parse($appointment->created_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');

                        $nestedData['recurring_status'] = "<span class='".$appointment->recurring_status_name_css."'>". __('messages.status.' . strtolower($appointment->recurring_status_name))."</span>";
                        $nestedData['schedule_status'] = "<span class='".$appointment->schedule_status_name_css."'>". __('messages.status.' . strtolower($appointment->schedule_status_name))."</span>";
                        $nestedData['appointment_status'] = "<span class='".$appointment->appointment_status_name_css."'>". __('messages.status.' . strtolower($appointment->appointment_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editCalendarEntry' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editCalendarEntry'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deleteCalendarEntry' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deleteCalendarEntry'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.cancel') ."</a>";

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