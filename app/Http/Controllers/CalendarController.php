<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CalendarRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\CalendarService;
use App\Http\Services\UploadService;

class CalendarController extends Controller
{

    protected CalendarService $calendarService;
    protected UploadService $uploadService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CalendarService $calendarService, UploadService $uploadService)
    {
        $this->middleware(['guest.lang','check.access'])->except('explore', 'show');
        $this->calendarService = $calendarService;
        $this->uploadService = $uploadService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(CalendarRequest $request)
    {
        try {
                $user = Auth::user();
                $appointment_id = $request->appointment_id;
                $schedule_id = $request->schedule_id;
                $title = $request->title;
                $description = $request->description;
                $slots = $request->slots;
                $slots_remaining = $request->slots_remaining;
                $location = $request->location_address;
                $location_latitude = $request->location_latitude;
                $location_longitude = $request->location_longitude;
                $trainer_id = $request->trainer_id;
                $appointment_status = 15; //CONFIRMED
                $created_by = $request->created_by_code ? $request->created_by_code : $user->id;
                $updated_by = $user->id;

                // ⏱️ Combine dates + times
                $startDate = Carbon::createFromFormat('Y-m-d', $request->starts_date);
                $endDate = Carbon::createFromFormat('Y-m-d', $request->ends_date);
                $startTime = $request->start_time; // format: 'H:i'
                $endTime = $request->end_time;

                $startDateTime = Carbon::parse("{$startDate->toDateString()} {$startTime}"); // full start
                $endDateTime = Carbon::parse("{$endDate->toDateString()} {$endTime}");       // full end


                //
                $category = "time";
                $is_all_day = false;
                $color = "#04050c";
                $backgroundColor = "#f1b44c";
                $dragBackgroundColor = "#f1b44c";
                $borderColor = "#f1b44c";
                $is_editable = false;


                if($appointment_id) {
                    //lets get the calendar entry 

                    $criteria = [
                        'id' => $appointment_id,
                    ];

                    // get calendar entry data
                    $existingCalendarEntry = $this->calendarService->searchCalendarEntries($criteria, 'find');
                }


                  // Check if a new image/logo is uploaded, otherwise keep the existing one
                $imageUrl = $request->hasFile('image')  ? $this->uploadService->handleUpload(false, $request, 'image', 'images/calendar', 's3') : ($existingCalendarEntry->appointment_image ?? null);


                $response = $this->calendarService->updatecreateCalendarEntry($appointment_id, $title, $startDateTime, $endDateTime, $description, $slots, $slots_remaining, $location, $location_latitude, 
                                                                $location_longitude, $is_all_day, $category, $color, $backgroundColor, $dragBackgroundColor, $borderColor, $is_editable, $trainer_id, $imageUrl, 
                                                                $appointment_status, $created_by, $updated_by);
            
                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

            return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Store Calendar Entry Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.calendar.update_failed')
            ], 500);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, string $id)
    {
         try {
                $criteria = [
                    'id' => $id
                ];

                // get schedule data
                $class = $this->calendarService->searchCalendarEntries($criteria, 'find');

                 return view('calendar.show', [
                        'class' => $class
                    ]);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Show Schedule Failed: " . $e->getMessage(), [
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, string $id)
    {
        try {
                $criteria = [
                    'id' => $id
                ];

                // get calendar_entry data
                $calendar_entry = $this->calendarService->searchCalendarEntries($criteria, 'find');

                // check if response has error and its set to true
                if (isset($calendar_entry['error']) && $calendar_entry['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $calendar_entry['message']
                    ], 500);

                }
                
            return response()->json($calendar_entry);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Edit Calendar Entry Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'schedule_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.calendar.search_failed')
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
                $status = 12; //CANCELLED
                $deleted_by = $user->id;

                
                $response = $this->calendarService->deleteCalendarEntry($id, $currdatetime, $deleted_by, $status);

                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

                return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Soft Delete Calendar Entry Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'schedule_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.calendar.deactivate_failed')
            ], 500);

        }
    }




    /**
     * Search and display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, string $locale, string $id)
    {
        try {
            $user = Auth::user();
            $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

            session(['lock-expires-at' => now()->addMinutes($user->getLockoutTime())]);

            $searchname = $request->get('q');
            $cleanedId = null;

             // Remove the extra timezone information in parentheses
            $startDateTime = preg_replace('/\s*\(.*\)$/', '', $request->start);
            $endDateTime = preg_replace('/\s*\(.*\)$/', '', $request->end);
            $acc = preg_replace('/\s*\(.*\)$/', '', $request->acc);

            $only_mine = filter_var($acc, FILTER_VALIDATE_BOOLEAN); // convert string to boolean

            $user_id = $only_mine ? $user->id : null;


            if (!empty($id) && strtolower($id) !== 'null') {
                $rawIds = explode(',', $id);
                $cleaned = array_filter(array_map('trim', $rawIds), fn($v) => $v !== '');

                $cleanedId = count($cleaned) > 1 ? array_values($cleaned) : $cleaned[0] ?? null;
            }

           
            // assign values to the $criteria array
            $criteria = [
                'id' => $cleanedId,
                'searchword' => $searchname,
                'start_date_time' => Carbon::parse($startDateTime)->timezone('UTC')->format('Y-m-d H:i:s'),
                'end_date_time' => Carbon::parse($endDateTime)->timezone('UTC')->format('Y-m-d H:i:s'),
                'status' => 15,
                'user_id' => $user_id
            ];


            //dd($criteria);


            $entries = $this->calendarService->searchCalendarEntries($criteria, 'get');

            if (isset($entries['error']) && $entries['error'] === true) {
                return response()->json([
                    'error' => $entries['message']
                ], 500);
            }

            return response()->json($entries);

        } catch (Throwable $e) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/calendar-controller-error.log')
            ])->error("Search Calendar Entries Failed: " . $e->getMessage(), [
                'route' => Route::currentRouteName(),
                'user_id' => Auth::id() ?? 'N/A',
                'request' => json_encode($request->all()),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => __('messages.calendar.search_failed')
            ], 500);
        }
    }

    
    /**
     * Display a listing of the resource.
     */
    public function explore(Request $request)
    {
        try {

                    $criteria = [
                        'status' => 15,                     // Filter confirmed calendar entries
                        'per_page' => 12,                  // Number per page
                        'searchword' => $request->search,  // Optional if search is active
                    ];

                    

                    $classes = $this->calendarService->searchCalendarEntries($criteria, 'paginate');


                return view('calendar.sessions', compact('classes'));


        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Sessions View Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            //return response(view('errors.500')); 

        }
    }


      /**
     * Display a listing of the resource.
     */
    public function entries(Request $request)
    {
        try {
                // get the route name
                $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

                //set request-prev-route-name used when locking screen manually
                session(['request-prev-route-name'=> $routeName]);

                $isAdmin = $request->get('isAdmin');
                $permissions = $request->get('current_module_permissions', []);

            return view('calendar.entries', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Calendar Entries View Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 

        }
    }



    /**
     * Display a listing of the resource.
     */
    public function browse(Request $request)
    {
        try {
                // get the route name
                $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

                //set request-prev-route-name used when locking screen manually
                session(['request-prev-route-name'=> $routeName]);

                $isAdmin = $request->get('isAdmin');
                $permissions = $request->get('current_module_permissions', []);

            return view('calendar.index', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Calendar Index View Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            //return response(view('errors.500')); 

        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function displayCalendarEntries(Request $request)
    {
        try {
                $limit_data = $request->input('length');
                $start_data = $request->input('start');
                $order_column = $request->input('order.0.column');
                $order_dir = $request->input('order.0.dir');
                $search = $request->input('search.value'); 
                $draw = $request->input('draw');
                $var = $request->input('appointment_status');
                
                $json_data = $this->calendarService->displayCalendarEntriesTableData(
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
                // Custom logging to 'calendar-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/calendar-controller-error.log')
                ])->error("Display Calendar Entries Failed: " . $e->getMessage(), [
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
