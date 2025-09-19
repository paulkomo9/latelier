<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WorkflowStatus;
use Carbon\Carbon;
use Auth;
use Throwable;


class WorkflowStatusService
{

    /**
     * Search Workflow Status
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchWorkflowStatus($criteria, $method = 'get')
    {
        try {
            // If using 'find' method and 'id' is provided, return directly
            if ($method === 'find' && !empty($criteria['id'])) {
                return WorkflowStatus::find($criteria['id']);
            }

            // Build query
            $query = WorkflowStatus::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                        ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                            return $query->where(function($q) use ($criteria) {
                                                $q->where('status_name', 'LIKE', "%{$criteria['searchword']}%");
                                            });
                                        })
                                        ->whereNull('deleted_at'); //Add this to exclude soft-deleted records;;

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
                'path' => storage_path('logs/workflow-status-service-error.log')
            ])->error("Search Workflow Status Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.workflow-status.search_failed')
                        ]; 
        }
    }
    
}