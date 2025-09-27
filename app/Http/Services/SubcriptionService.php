<?php
namespace App\Http\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UserPackages;
use App\Models\UserPackagesView;
use Auth;
use Carbon\Carbon;
use Throwable;


class SubcriptionService 
{
    /**
     * Search Subscriptions
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchSubscriptions($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return UserPackagesView::find($criteria['id']);
                }

                // Build query
                $query = UserPackagesView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                                return $query->where('subscription_status ', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('package', 'LIKE', "%{$criteria['searchword']}%")
                                                    ->orWhere('member_name', 'LIKE', "%{$criteria['searchword']}%");
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
                'path' => storage_path('logs/subscription-service-error.log')
            ])->error("Search Bookings Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.subscription.search_failed')
                        ]; 
        }
    }


    /**
     * Display Subscriptions DataTable Data
     * @param int $limit_data
     * @param int $start_data
     * @param var $order_column
     * @param var $order_dir
     * @param string $search
     * @param int $draw
     * @param var $var
     * @param bool $myacc
     * @param Request $request
     * 
     * @return array<string, string> $arrdataTable
     */
    public function displaySubscriptionsTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $myacc, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'member_name',
                    2 => 'package',
                    3 => 'sessions_total',
                    4 => 'validity',
                    5 => 'purchased_at',
                    6 => 'expires_at',
                    7 => 'subscription_status_name',
                    8 => 'purchased_by',
                    9 => 'notes',
                   10 => 'created_at',
            );

        

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();

            $subscriptions = UserPackagesView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

             // ✅ Apply status & myacc filter early (before counts)
            $subscriptions = $subscriptions
                ->when(!is_null($var) && $var !== 0, function ($query) use ($var) {
                    return $query->where('subscription_status', $var);
                })
                ->when($myacc, function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                });

            // Fetch the totalData data after applying all scope filters
            $totalData = $subscriptions->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the bookings data after applying all scope filters
                    $subscriptions = $subscriptions->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $subscriptions =  $subscriptions->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('member_name','LIKE',"%{$search}%")
                                                                          ->orWhere('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $subscriptions->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('member_name','LIKE',"%{$search}%")
                                                                          ->orWhere('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($subscriptions->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($subscriptions as $subscription) {

                        $edit =  $subscription->id;
                        $delete =  $subscription->id;

                        $nestedData['id'] = $counter;
                        $nestedData['member_name'] = $subscription->member_name;
                        $nestedData['package'] = $subscription->package;
                        $nestedData['sessions_total'] = $subscription->sessions_total;
                        $nestedData['sessions_remaining'] = $subscription->sessions_remaining;
                        $nestedData['validity'] = $subscription->validity;
                        $nestedData['purchased_on'] = Carbon::parse($subscription->purchased_at)->locale(app()->getLocale())->translatedFormat('l jS F Y');
                        $nestedData['expires_at'] = Carbon::parse($subscription->expires_at)->locale(app()->getLocale())->translatedFormat('l jS F Y');
                        $nestedData['purchased_by'] = $subscription->purchased_by_name;
                        $nestedData['notes'] = $subscription->notes;

                        $nestedData['subscription_status'] = "<span class='".$subscription->subscription_status_name_css."'>". __('messages.status.' . strtolower($subscription->subscription_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editSubscription' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editSubscription'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deleteSubscription' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deleteSubscription'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.cancel') ."</a>";
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


