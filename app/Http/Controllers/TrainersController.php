<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\UserService;

class TrainersController extends Controller
{

    protected UserService $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware(['auth']);
        $this->userService = $userService;
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
    public function edit(string $locale, string $id)
    {
        try {
                $criteria = [
                    'id' => $id
                ];

                // get trainer data
                $trainer = $this->userService->searchUsers($criteria, 'find');

                // check if response has error and its set to true
                if (isset($trainer['error']) && $trainer['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $trainer['message']
                    ], 500);

                }
                
            return response()->json($trainer);

        } catch (Throwable $e) {
                // Custom logging to 'trainers-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/trainers-controller-error.log')
                ])->error("Edit Trainer Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'trainer_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.trainer.search_failed')
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
    public function destroy(string $id)
    {
        //
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

                //any active activity add session time
                session(['lock-expires-at' => now()->addMinutes($user->getLockoutTime())]);

                $searchword = null;
                $cleanedId = null;

                
                if($request->filled('q')) {
                     $searchword = $searchword = $request->get('q');  
                }

                if (!empty($id)) {
                    $rawIds = explode(',', $id);
                    $numericIds = array_filter(array_map('trim', $rawIds), 'is_numeric');

                    $cleanedId = count($numericIds) > 1
                        ? array_map('intval', $numericIds)
                        : (int) $numericIds[0];
                }

                $criteria = [
                    'searchword' => $searchword,
                    'id' => $cleanedId,
                    'status' => 1,
                    'trainer' => 5
                ];

                $trainers = $this->userService->searchUsers($criteria, 'get');

                 // check if response has error and its set to true
                if (isset($trainers['error']) && $trainers['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $trainers['message']
                    ], 500);

                }

            return response()->json($trainers);

        } catch (Throwable $e) {
                // Custom logging to 'trainers-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/trainers-controller-error.log')
                ])->error("Search Trainers Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.trainer.search_failed')
            ], 500);

        }     
    }
}
