<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ScheduleRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\ScheduleService;
use App\Http\Services\UploadService;

class SchedulesController extends Controller
{

    protected ScheduleService $scheduleService;
    protected UploadService $uploadService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ScheduleService $scheduleService, UploadService $uploadService)
    {
        $this->middleware(['guest.lang', 'check.access'])->except(['show', 'explore']);
        $this->scheduleService = $scheduleService;
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
                // get the route name
                $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

                //set request-prev-route-name used when locking screen manually
                session(['request-prev-route-name'=> $routeName]);

                $isAdmin = $request->get('isAdmin');
                $permissions = $request->get('current_module_permissions', []);

            return view('schedules.index', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'schedules-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedules-controller-error.log')
                ])->error("Schedules Index View Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        try {
                $user = Auth::user();
                $schedule_id = $request->schedule_id;
                $title = $request->title;
                $description = $request->description;
                $slots = $request->slots;
                $location = $request->location_address;
                $location_latitude = $request->location_latitude;
                $location_longitude = $request->location_longitude;
                $recurring_status = $request->recurring_status;
                $trainer_id = $request->trainer_id;
                $schedule_status = 1;
                $created_by = $request->created_by_code ? $request->created_by_code : $user->id;
                $updated_by = $user->id;

                // ⏱️ Combine dates + times
                $startDate = Carbon::createFromFormat('Y-m-d', $request->starts_date);
                $endDate = Carbon::createFromFormat('Y-m-d', $request->ends_date);
                $startTime = $request->start_time; // format: 'H:i'
                $endTime = $request->end_time;

                $startDateTime = Carbon::parse("{$startDate->toDateString()} {$startTime}"); // full start
                $endDateTime = Carbon::parse("{$endDate->toDateString()} {$endTime}");       // full end

                // ⏱️ Calculate estimated time (in minutes)
                $estimated_time = $startDateTime->diffInMinutes(Carbon::parse("{$startDate->toDateString()} {$endTime}"));

                if($schedule_id) {
                    //lets get the schedule details

                    $criteria = [
                        'id' => $schedule_id,
                    ];

                    // get schedule data
                    $existingSchedule = $this->scheduleService->searchSchedules($criteria, 'find');
                }


                // Check if a new image/logo is uploaded, otherwise keep the existing one
                $imageUrl = $request->hasFile('image')  ? $this->uploadService->handleUpload(false, $request, 'image', 'images/schedules', 's3') : ($existingSchedule->schedule_image ?? null);



      
                $response = $this->scheduleService->updatecreateSchedule($schedule_id, $title, $startDateTime, $endDateTime, $description, $estimated_time, $slots, $location, 
                                                                $location_latitude, $location_longitude, $recurring_status, $trainer_id, $imageUrl, $schedule_status, $created_by, $updated_by);
            
                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

            return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'schedules-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedules-controller-error.log')
                ])->error("Store Schedule Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.schedule.update_failed')
            ], 500);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, string $id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, string $id)
    {
        try {
                $criteria = [
                    'id' => $id
                ];

                // get schedule data
                $schedule = $this->scheduleService->searchSchedules($criteria, 'find');

                // check if response has error and its set to true
                if (isset($schedule['error']) && $schedule['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $schedule['message']
                    ], 500);

                }
                
            return response()->json($schedule);

        } catch (Throwable $e) {
                // Custom logging to 'schedules-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedules-controller-error.log')
                ])->error("Edit Schedule Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'schedule_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.schedule.search_failed')
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


   
   /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, string $id)
    {
        try {
                $user = Auth::user();
                date_default_timezone_set($user->timezone);
                $currdatetime = Carbon::now();
                $status = 2; //DEACTIVATED
                $deleted_by = $user->id;

                
                $response = $this->scheduleService->deleteSchedule($id, $currdatetime, $deleted_by, $status);

                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

                return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'schedules-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedules-controller-error.log')
                ])->error("Soft Delete Schedule Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'schedule_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.schedule.deactivate_failed')
            ], 500);

        }
    }


  

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function displaySchedules(Request $request)
    {
        try {
                $limit_data = $request->input('length');
                $start_data = $request->input('start');
                $order_column = $request->input('order.0.column');
                $order_dir = $request->input('order.0.dir');
                $search = $request->input('search.value'); 
                $draw = $request->input('draw');
                $var = $request->input('schedule_status');
                
                $json_data = $this->scheduleService->displaySchedulesTableData(
                    $limit_data, 
                    $start_data, 
                    $order_column, 
                    $order_dir, 
                    $search, 
                    $draw, 
                    $var, 
                    $request); 

            return response()->json($json_data);

        } catch (Throwable $e) {
                // Custom logging to 'schedules-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/schedules-controller-error.log')
                ])->error("Display Schedules Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request'=> json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

                //return empty array 
            return response()->json([], 500);

        } 
               
    }
}
