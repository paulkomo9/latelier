<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UsersView;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Throwable;


class UserService
{
    
    /**
     * Search Users
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchUsers($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return UsersView::find($criteria['id']);
                }

                // Build query
                $query = UsersView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                             ->when(!empty($criteria['super_admin']), function ($query) use ($criteria) {
                                                return $query->where('is_super_admin', '=', $criteria['super_admin']);
                                            })
                                            ->when(!empty($criteria['trainer']), function ($query) use ($criteria) {
                                                return $query->where('is_trainer', '=', $criteria['trainer']);
                                            })
                                            ->when(!empty($criteria['status']), function ($query) use ($criteria) {
                                                return $query->where('user_status', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('firstname', 'LIKE', "%{$criteria['searchword']}%")
                                                    ->orWhere('lastname', 'LIKE', "%{$criteria['searchword']}%");
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
                'path' => storage_path('logs/user-service-error.log')
            ])->error("Search Users Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.user.search_failed')
                        ]; 
        }
    }

    /**
     * Display Users DataTable Data
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
    public function displayUsersTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'name',
                    2 => 'email',
                    3 => 'user_status_name',
                    4 => 'is_client_name',
                    5 => 'is_trainer_name',
                    6 => 'is_super_admin_name',
                    7 => 'online_status_name',
                    8 => 'last_login_time',
                    9 => 'created_at',
                   10 => 'updated_at',
            );

        

            $isAdmin = $request->get('isAdmin',false);
            //$user = $request->user();

            $users = UsersView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status filter early (before counts)
            $users = $users->when($var !== null && $var !== 0, function ($query) use ($var) {
                return $query->where('user_status', $var);
            });

            // Fetch the totalData data after applying all scope filters
            $totalData = $users->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the bookings data after applying all scope filters
                    $users = $users->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $users =  $users->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('firstname','LIKE',"%{$search}%")
                                                                          ->orWhere('lastname','LIKE',"%{$search}%")
                                                                          ->orWhere('email','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $users->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('firstname','LIKE',"%{$search}%")
                                                                          ->orWhere('lastname','LIKE',"%{$search}%")
                                                                          ->orWhere('email','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($users->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($users as $user) {

                        $edit =  $user->id;
                        $delete =  $user->id;

                        $nestedData['id'] = $counter;
                        $nestedData['name'] = $user->firstname." ".$user->lastname;
                        $nestedData['profile_pic'] = $user->profile_pic;
                        $nestedData['email'] = $user->email;
                        $nestedData['created_at'] = Carbon::parse($user->created_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['updated_at'] = Carbon::parse($user->updated_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['online_status'] = "<span class='".$user->online_status_name_css."'>". __('messages.status.' . strtolower($user->online_status_name))."</span>";
                        $nestedData['is_client'] = "<span class='".$user->is_client_name_css."'>". __('messages.status.' . strtolower($user->is_client_name))."</span>";
                        $nestedData['is_trainer'] = "<span class='".$user->is_trainer_name_css."'>". __('messages.status.' . strtolower($user->is_trainer_name))."</span>";
                        $nestedData['is_admin'] = "<span class='".$user->is_super_admin_name_css."'>". __('messages.status.' . strtolower($user->is_super_admin_name))."</span>";
                        $nestedData['user_status'] = "<span class='".$user->user_status_name_css."'>". __('messages.status.' . strtolower($user->user_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editUser' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editUser'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deleteUser' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deleteUser'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.deactivate') ."</a>";
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