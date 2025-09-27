<?php
namespace App\Http\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\NotificationEvent;
use App\Models\Packages;
use App\Models\PackagesView;
use Auth;
use Carbon\Carbon;
use Throwable;

class PackageService
{
    
    /**
     * Search Packages
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchPackages($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return PackagesView::find($criteria['id']);
                }

                // Build query
                $query = PackagesView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                                return $query->where('package_status', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('package', 'LIKE', "%{$criteria['searchword']}%");
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
                'path' => storage_path('logs/package-service-error.log')
            ])->error("Search Packages Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.package.search_failed')
                        ]; 
        }
    }


    /**
     * Create/Update Packkage Details
     * @param int $package_id
     * @param string $package
     * @param int $sessions_total
     * @param int $validity_quantity
     * @param string $validity_unit
     * @param string $description
     * @param string $currency
     * @param string $tax_type
     * @param float $tax
     * @param float $amount
     * @param float $total_amount
     * @param string $package_image
     * @param int $package_status
     * @param int $created_by
     * @param int $updated_by
     * 
     * @return array $arrResponse
     */
    public function updatecreatePackage($package_id, $package, $sessions_total, $validity_quantity, $validity_unit, $description, $currency, $tax_type, $tax, $amount, $total_amount, 
                                $package_image, $package_status, $created_by, $updated_by)
    {
        try {
                $package = Packages::updateOrCreate([
                                        'id' => $package_id,
                                    ],
                                    [
                                        'package' => $package,
                                        'sessions_total' => $sessions_total,
                                        'validity_quantity' => $validity_quantity,
                                        'validity_unit' => $validity_unit,
                                        'description' => $description,
                                        'currency' => $currency,
                                        'tax_type' => $tax_type,
                                        'tax' => $tax,
                                        'amount' => $amount,
                                        'total_amount' => $total_amount,
                                        'package_image' => $package_image,
                                        'package_status' => $package_status,
                                        'created_by' => $created_by,
                                        'updated_by' => $updated_by
                                    ]);


                // Determine the event type
                $action = $package->wasRecentlyCreated ? 'created' : 'updated';
                $data_changed = $package->wasChanged();

                //lets create a response and other details needed
                $item_affected = $package->package;
                $response = "<strong>".$package->package."</strong> ".Str::title(__('messages.status.' . strtolower($action)));
                $module = __('Packages');
                $isAdmin_module = true;
                $sender = $package->updated_by;
                $reciever = null;
                $updated_at = $package->updated_at; //date('j M Y h:i a',strtotime($companies->updated_at));
                $url = 'packages';
                $notify_client = false; //for client data changes  within the admin module
                $notify_trainer = false; //for trainer data changes  within the admin module
                $notify_admin = true;
                
                //check if $data_changed
                    /*if($data_changed || $action == 'created') {
                        //lets fire event
                        event (new NotificationEvent(
                            $action, $module, $isAdmin_module, $sender, $reciever, $item_affected, $updated_at, $url, $notify_client, $notify_trainer, $notify_admin
                        ));
                    }*/
               

            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'package-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/package-service-error.log')
                ])->error("Package Update/Creation Failed: " . $e->getMessage(), [
                    'package' => $package,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.package.update_failed')
                        ]; 

        }       
    }



    /**
     * delete Package 
     * @param int $package_id
     * @param date $currdatetime
     * @param int $deleted_by
     * @param int $status
     * 
     * @return array $arrResponse
     */
    public function deletePackage($package_id, $currdatetime, $deleted_by, $status)
    {
        try {
                $package_data = Packages::findOrFail($package_id);
                $package = $package_data->title;
                $package_data->deleted_at = $currdatetime;
                $package_data->deleted_by = $deleted_by;
                $package_data->package_status = $status;
                $package_data->save();

                $action = 'deactivated';
                $response = "<strong>".$package."</strong> ".Str::title(__('messages.status.' . strtolower($action)));
                
            return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'package-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/package-service-error.log')
                ])->error("Soft Delete Shedule  Failed: " . $e->getMessage(), [
                    'package_id' => $package_id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.package.deactivate_failed')
                        ];

        }
    }



    /**
     * Display Packages DataTable Data
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
    public function displayPackagesTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'package',
                    2 => 'sessions_total',
                    3 => 'validity',
                    4 => 'amount',
                    5 => 'tax',
                    6 => 'total_amount',
                    7 => 'created_at',
                    8 => 'updated_at',
                    9 => 'created_by_name',
                   10 => 'updated_by_name',
                   11 => 'deleted_by_name',
                   12 => 'package_status_name',
                );

        

           

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();

            $packages = PackagesView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status filter early (before counts)
            $packages = $packages->when($var !== null && $var !== 0, function ($query) use ($var) {
                return $query->where('package_status', $var);
            });

            // Fetch the totalData data after applying all scope filters
            $totalData = $packages->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the schedules data after applying all scope filters
                    $packages = $packages->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $packages =  $packages->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $packages->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($packages->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($packages as $package) {

                        $edit =  $package->id;
                        $delete =  $package->id;

                        $amount = $package->amount ? number_format($package->amount, 2) : "0.00";
                        $tax = $package->tax ? number_format($package->tax, 2) : "0.00";
                        $total_amount = $package->tax ? number_format($package->total_amount, 2) : "0.00";

                        $nestedData['id'] = $counter;
                        $nestedData['package'] = $package->package;
                        $nestedData['description'] = $package->description;
                        $nestedData['sessions_total'] = $package->sessions_total;
                        $nestedData['validity'] = $package->validity_quantity." ".Str::plural($package->validity_unit, $package->validity_quantity);
                        $nestedData['amount'] = $package->currency ." ". $amount;
                        
                        // Display tax as either "AED 5.00" or "5%"
                        if ($package->tax_type === 'percentage') {
                            $nestedData['tax'] = number_format($package->tax, 2) . '%';
                        } else {
                            $nestedData['tax'] = $package->currency . " " . number_format($package->tax, 2);
                        }

                        $nestedData['tax_type'] = ucfirst($package->tax_type); // Optional: Capitalize 'Fixed' or 'Percentage'

                        $nestedData['total_amount'] = $package->currency ." ". $total_amount;
                        $nestedData['package_image'] = $package->package_image;
                        $nestedData['created_by'] = $package->created_by_name;
                        $nestedData['updated_by'] = $package->updated_by_name;    
                        $nestedData['updated_at'] = Carbon::parse($package->updated_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['created_at'] = Carbon::parse($package->created_at)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');

                        $nestedData['package_status'] = "<span class='".$package->package_status_name_css."'>". __('messages.status.' . strtolower($package->package_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editPackage' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editPackage'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deletePackage' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deletePackage'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.deactivate') ."</a>";

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