<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\WorkflowStatusService;

class WorkflowStatusController extends Controller
{

    protected WorkflowStatusService $workflowstatusService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkflowStatusService $workflowstatusService)
    {
        $this->middleware(['auth']);
        $this->workflowstatusService = $workflowstatusService;
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

                // get status data
                $status = $this->workflowstatusService->searchWorkflowStatus($criteria, 'find');

                // check if response has error and its set to true
                if (isset($status['error']) && $status['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $status['message']
                    ], 500);

                }
                
            return response()->json($status);

        } catch (Throwable $e) {
                // Custom logging to 'workflowstatus-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/workflowstatus-controller-error.log')
                ])->error("Edit Schedule Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'status_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.workflow-status.search_failed')
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
                    'id' => $cleanedId
                ];

                $workflow_status = $this->workflowstatusService->searchWorkflowStatus($criteria, 'get');

                 // check if response has error and its set to true
                if (isset($workflow_status['error']) && $workflow_status['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $workflow_status['message']
                    ], 500);

                }

            return response()->json($workflow_status);

        } catch (Throwable $e) {
                // Custom logging to 'workflow-status-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/workflow-status-controller-error.log')
                ])->error("Search Workflow Status Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'request' => json_encode($request->all()),
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.workflow-status.search_failed')
            ], 500);

        }     
    }
}
