<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\UserService;

class UsersController extends Controller
{
    protected UserService $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware(['guest.lang','check.access']);
        $this->userService = $userService;
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

            return view('users.index', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'users-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/users-controller-error.log')
                ])->error("Users Index View Failed: " . $e->getMessage(), [
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }

   
   
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function displayUsers(Request $request)
    {
        try {
                $limit_data = $request->input('length');
                $start_data = $request->input('start');
                $order_column = $request->input('order.0.column');
                $order_dir = $request->input('order.0.dir');
                $search = $request->input('search.value'); 
                $draw = $request->input('draw');
                $var = $request->input('booking_status');
                
                $json_data = $this->userService->displayUsersTableData(
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
                // Custom logging to 'users-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/users-controller-error.log')
                ])->error("Display Users Failed: " . $e->getMessage(), [
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
