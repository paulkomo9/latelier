<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PackageRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\PackageService;
use App\Http\Services\UploadService;

class PackagesController extends Controller
{

    protected PackageService $packageService;
    protected UploadService $uploadService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PackageService $packageService, UploadService $uploadService)
    {
        $this->middleware(['guest.lang','check.access'])->except(['show', 'explore']);
        $this->packageService = $packageService;
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

            return view('packages.index', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Packages Index View Failed: " . $e->getMessage(), [
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
    public function store(PackageRequest $request)
    {
        try {
                $user = Auth::user();
                $package_id = $request->package_id;
                $package = $request->package;
                $description = $request->description;
                $sessions_total = $request->sessions_total;
                $validity_quantity = $request->validity_quantity;
                $validity_unit = $request->validity_unit;
                $currency = $request->currency;
                $tax_type = $request->tax_type;
                $amount = round((float) str_replace(',', '', $request->amount), 2);
                $tax = round((float) str_replace(',', '', $request->tax), 2);
                $total_amount = round((float) str_replace(',', '', $request->total_amount), 2);
                $package_status = 1;
                $created_by = $request->created_by_code ? $request->created_by_code : $user->id;
                $updated_by = $user->id;

                if($package_id) {
                    //lets get the package details

                    $criteria = [
                        'id' => $package_id,
                    ];

                    // get package data
                    $existingPackage = $this->packageService->searchPackages($criteria, 'find');
                }

                // Check if a new image/logo is uploaded, otherwise keep the existing one
                $imageUrl = $request->hasFile('image')  ? $this->uploadService->handleUpload(false, $request, 'image', 'images/packages', 's3') : ($existingPackage->package_image ?? null);

      
                $response = $this->packageService->updatecreatePackage($package_id, $package, $sessions_total, $validity_quantity, $validity_unit, $description, $currency, $tax_type, $tax, $amount, $total_amount, 
                                $imageUrl, $package_status, $created_by, $updated_by);
                

                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

            return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Store Package Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.package.update_failed')
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

                // get package data
                $package = $this->packageService->searchPackages($criteria, 'find');

                 return view('packages.show', [
                        'package' => $package
                    ]);

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Show Package Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'package_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.package.search_failed')
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

                // get package data
                $package = $this->packageService->searchPackages($criteria, 'find');

                // check if response has error and its set to true
                if (isset($package['error']) && $package['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $package['message']
                    ], 500);

                }
                
            return response()->json($package);

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Edit Package Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'package_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.package.search_failed')
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

                
                $response = $this->packageService->deletePackage($id, $currdatetime, $deleted_by, $status);

                // check if response has error and its set to true
                if (isset($response['error']) && $response['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $response['message']
                    ], 500);

                }

                return response()->json(['success' => $response['message']]);

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Soft Delete Package Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'package_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.package.deactivate_failed')
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
                        'status' => 1,                     // Filter published schedules
                        'per_page' => 12,                  // Number per page
                        'searchword' => $request->search,  // Optional if search is active
                    ];

                    $packages = $this->packageService->searchPackages($criteria, 'paginate');


                return view('packages.packages', compact('packages'));


        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Schedules sessions View Failed: " . $e->getMessage(), [
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
    public function required(Request $request)
    {
        try {
                // get the route name
                $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

                //set request-prev-route-name used when locking screen manually
                session(['request-prev-route-name'=> $routeName]);

                $isAdmin = $request->get('isAdmin');
                $permissions = $request->get('current_module_permissions', []);

            return view('packages.required');

        } catch (Throwable $e) {
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Packages Required View Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 

        }
    }



    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function displayPackages(Request $request)
    {
        try {
                $limit_data = $request->input('length');
                $start_data = $request->input('start');
                $order_column = $request->input('order.0.column');
                $order_dir = $request->input('order.0.dir');
                $search = $request->input('search.value'); 
                $draw = $request->input('draw');
                $var = $request->input('package_status');
                
                $json_data = $this->packageService->displayPackagesTableData(
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
                // Custom logging to 'packages-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/packages-controller-error.log')
                ])->error("Display Packages Failed: " . $e->getMessage(), [
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
