<?php
namespace App\Http\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Schedules;
use App\Models\SchedulesView;
use Auth;
use Carbon\Carbon;
use Throwable;
use App\Http\Services\CalendarService;

class ScheduleService
{

    protected $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Search Schedules
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchSchedules($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return SchedulesView::find($criteria['id']);
                }

                // Build query
                $query = SchedulesView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                                return $query->where('schedule_status', '=', $criteria['status']);
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
                'path' => storage_path('logs/schedule-service-error.log')
            ])->error("Search Schedules Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.schedule.search_failed')
                        ]; 
        }
    }

    /**
     * Create/Update Schedule Details
     * @param int $schedule_id
     * @param string $title
     * @param date $start_date_time
     * @param date $end_date_time
     * @param string $description
     * @param int $estimated_time
     * @param int $slots
     * @param string $location
     * @param float $location_latitude
     * @param float $location_longitude
     * @param int $recurring_status
     * @param int $trainer_id
     * @param string $schedule_image
     * @param int $schedule_status
     * @param int $created_by
     * @param int $updated_by
     * 
     * @return array $arrResponse
     */
    public function updatecreateSchedule($schedule_id, $title, $start_date_time, $end_date_time, $description, $estimated_time, $slots, $location, $location_latitude, $location_longitude,
                                        $recurring_status, $trainer_id, $schedule_image, $schedule_status, $created_by, $updated_by)
    {
        try {
                $schedule = Schedules::updateOrCreate([
                                        'id' => $schedule_id,
                                    ],
                                    [
                                        'title' => $title,
                                        'start_date_time' => $start_date_time,
                                        'end_date_time' => $end_date_time,
                                        'description' => $description,
                                        'estimated_time' => $estimated_time,
                                        'slots' => $slots,
                                        'location' => $location,
                                        'location_latitude' => $location_latitude,
                                        'location_longitude' => $location_longitude,
                                        'recurring_status' => $recurring_status,
                                        'trainer_id' => $trainer_id,
                                        'schedule_image' => $schedule_image,
                                        'schedule_status' => $schedule_status,
                                        'created_by' => $created_by,
                                        'updated_by' => $updated_by
                                    ]);

                // Determine the event type
                $action = $schedule->wasRecentlyCreated ? 'created' : 'updated';
                $data_changed = $schedule->wasChanged();

                //lets create a response and other details needed
                $response = "<strong>".$title."</strong> ".Str::title(__('messages.status.' . strtolower($action)));


                if($data_changed || $action == 'created') {

                    $appointment_status = 15; //CONFIRMED
                    
                    // Sync appointments
                    $this->calendarService->syncAppointmentsFromSchedule(
                        $schedule->id,
                        $schedule->title,
                        $schedule->start_date_time,
                        $schedule->end_date_time,
                        $schedule->trainer_id,
                        $schedule->created_by,
                        $schedule->updated_by,
                        $appointment_status,
                        $slots,
                        $schedule_image,
                        $description
                    );

                }
               

            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'schedule-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedule-service-error.log')
                ])->error("Schedule Update/Creation Failed: " . $e->getMessage(), [
                    'title' => $title,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.schedule.update_failed')
                        ]; 

        }       
    }

    


    /**
     * delete Schedule 
     * @param int $schedule_id
     * @param date $currdatetime
     * @param int $deleted_by
     * @param int $status
     * 
     * @return array $arrResponse
     */
    public function deleteSchedule($schedule_id, $currdatetime, $deleted_by, $status)
    {
        try {
                $schedule_data = Schedules::findOrFail($schedule_id);
                $title = $schedule_data->title;
                $schedule_data->deleted_at = $currdatetime;
                $schedule_data->deleted_by = $deleted_by;
                $schedule_data->schedule_status = $status;
                $schedule_data->save();

                $action = 'deactivated';
                $response = "<strong>".$title."</strong> ".Str::title(__('messages.status.' . strtolower($action)));

                //sync with calendar entries cancel status 12
                $this->calendarService->deactivateAppointmentsBySchedule($schedule_id, $currdatetime, $deleted_by, 12);
                
            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'schedule-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedule-service-error.log')
                ])->error("Soft Delete Shedule  Failed: " . $e->getMessage(), [
                    'schedule_id' => $schedule_id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.schedule.deactivate_failed')
                        ];

        }
    }


    /**
     * Display Schedules DataTable Data
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
    public function displaySchedulesTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $request)
    {

            $columns = array( 
                    0 => 'start_date_time', 
                    1 => 'title',
                    2 => 'location',
                    3 => 'id',
                    4 => 'end_date_time',
                    5 => 'created_at',
                    6 => 'updated_at',
                    7 => 'estimated_time',
                    8 => 'max_enrollment',
                    9 => 'created_by_name',
                   10 => 'updated_by_name',
                   11 => 'deleted_by_name',
                   12 => 'schedule_status_name',
                );

        

           

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();

            $schedules = SchedulesView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status filter early (before counts)
            $schedules = $schedules->when($var !== null && $var !== 0, function ($query) use ($var) {
                return $query->where('schedule_status', $var);
            });

            // Fetch the totalData data after applying all scope filters
            $totalData = $schedules->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the schedules data after applying all scope filters
                    $schedules = $schedules->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $schedules =  $schedules->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $schedules->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('title','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($schedules->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($schedules as $schedule) {

                        $edit =  $schedule->id;
                        $delete =  $schedule->id;

                        $nestedData['id'] = $counter;
                        $nestedData['title'] = $schedule->title;
                        $nestedData['description'] = $schedule->description;
                        $nestedData['start_date_time'] = Carbon::parse($schedule->start_date_time)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['end_date_time'] = Carbon::parse($schedule->end_date_time)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['estimated_time'] = $schedule->estimated_time." Minutes";
                        $nestedData['slots'] = $schedule->slots;
                        $nestedData['slots_taken'] = $schedule->slots_taken;
                        $nestedData['location'] = $schedule->location;
                        $nestedData['description'] = $schedule->description;
                        $nestedData['schedule_image'] = $schedule->schedule_image;
                        $nestedData['trainer'] = $schedule->trainer_name;
                        $nestedData['created_by'] = $schedule->created_by_name;
                        $nestedData['updated_by'] = $schedule->updated_by_name;    
                        $nestedData['updated_at'] = Carbon::parse($schedule->updated_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['created_at'] = Carbon::parse($schedule->created_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');

                        $nestedData['recurring_status'] = "<span class='".$schedule->recurring_status_name_css."'>". __('messages.status.' . strtolower($schedule->recurring_status_name))."</span>";
                        $nestedData['schedule_status'] = "<span class='".$schedule->schedule_status_name_css."'>". __('messages.status.' . strtolower($schedule->schedule_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editSchedule' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editSchedule'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deleteSchedule' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deleteSchedule'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.deactivate') ."</a>";

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