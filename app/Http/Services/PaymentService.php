<?php
namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\NotificationEvent;
use App\Models\Payments;
use App\Models\PaymentsView;
use Auth;
use Carbon\Carbon;
use Throwable;



class PaymentService 
{

    /**
     * Search Payments
     * 
     * @param array $criteria
     * @param string $method Method to execute: 'get', 'paginate', 'first', 'exists', 'find'
     * 
     * @return mixed
     */
    public function searchPayments($criteria, $method = 'get')
    {
        try {
                // If using 'find' method and 'id' is provided, return directly
                if ($method === 'find' && !empty($criteria['id'])) {
                    return PaymentsView::find($criteria['id']);
                }

                // Build query
                $query = PaymentsView::when(!empty($criteria['id']), function ($query) use ($criteria) {
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
                                                return $query->where('payment_status', '=', $criteria['status']);
                                            })
                                            ->when(!empty($criteria['searchword']), function ($query) use ($criteria) {
                                                return $query->where(function($q) use ($criteria) {
                                                    $q->where('transaction_id', 'LIKE', "%{$criteria['searchword']}%")
                                                    ->orWhere('card_brand', 'LIKE', "%{$criteria['searchword']}%");
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
                'path' => storage_path('logs/payments-service-error.log')
            ])->error("Search Payments Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'method' => $method,
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.payment.search_failed')
                        ]; 
        }
    }


      /**
     * Create/Update Payment Details
     * @param int $payment_id
     * @param string $transaction_id
     * @param string $payment_gateway_currency
     * @param float $payment_amount
     * @param float $payment_processing_fee
     * @param float $payment_tax
     * @param string $payment_message
     * @param string $balance_transaction
     * @param string $payment_charge_outcome
     * @param string $payment_method
     * @param string $last4
     * @param string $card_brand
     * @param date $payment_start_created
     * @param date $payment_end_created
     * @param int $package_id
     * @param int $payment_status
     * @param int $paid_by
     * 
     * @return array $arrResponse
     */
    public function updatecreatePackage($payment_id, $transaction_id, $payment_gateway_currency, $payment_amount, $payment_processing_fee, $payment_tax, $payment_message, $balance_transaction, 
                                $payment_charge_outcome, $payment_method, $last4, $card_brand, $payment_start_created, $payment_end_created, $package_id, $payment_status, $paid_by)
    {
        try {
                $payment = Payments::updateOrCreate([
                                        'id' => $payment_id,
                                    ],
                                    [
                                        'transaction_id' => $transaction_id,
                                        'payment_gateway_currency' => $payment_gateway_currency,
                                        'payment_amount' => $payment_amount,
                                        'payment_processing_fee' => $payment_processing_fee,
                                        'payment_tax' => $payment_tax,
                                        'payment_message' => $payment_message,
                                        'balance_transaction' => $balance_transaction,
                                        'payment_charge_outcome' => $payment_charge_outcome,
                                        'payment_method' => $payment_method,
                                        'last4' => $last4,
                                        'card_brand' => $card_brand,
                                        'payment_status' => $payment_status,
                                        'payment_start_created' => $payment_start_created,
                                        'payment_end_created' => $payment_end_created,
                                        'package_id' => $package_id,
                                        'paid_by' => $paid_by
                                    ]);


                // Determine the event type
                $action = $payment->wasRecentlyCreated ? 'created' : 'updated';
                $data_changed = $payment->wasChanged();

                //lets create a response and other details needed
                $item_affected = $payment->transaction_id;
                $response = "<strong>".$payment->transaction_id."</strong> ".Str::title(__('messages.status.' . strtolower($action)));
                $module = __('Payments');
                $isAdmin_module = true;
                $sender = $payment->paid_by;
                $reciever = null;
                $updated_at = $payment->payment_end_created; //date('j M Y h:i a',strtotime($companies->updated_at));
                $url = 'payments';
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
                            "message" => $response,
                            "payment" => $payment
                        ];

        } catch (Throwable $e) {
                // Custom logging to 'package-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/payment-service-error.log')
                ])->error("Payment Update/Creation Failed: " . $e->getMessage(), [
                    'transaction_id' => $transaction_id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.payment.update_failed')
                        ]; 

        }       
    }

    /**
     * Display Payments DataTable Data
     * @param int $limit_data
     * @param int $start_data
     * @param var $order_column
     * @param var $order_dir
     * @param string $search
     * @param int $draw
     * @param var $var
     * @param bool myacc
     * @param Request $request
     * 
     * @return array<string, string> $arrdataTable
     */
    public function displayPaymentsTableData($limit_data, $start_data, $order_column, $order_dir, $search, $draw, $var, $myacc, $request)
    {

            $columns = array( 
                    0 => 'id', 
                    1 => 'payment_reference',
                    2 => 'package',
                    3 => 'payment_amount',
                    4 => 'payment_method',
                    5 => 'last4',
                    6 => 'crad_brand',
                    7 => 'payment_status_name',
                    8 => 'payment_start_created',
                    9 => 'payment_end_created',
                   10 => 'payment_message'
            );

        

            $isAdmin = $request->get('isAdmin',false);
            $user = $request->user();


            $payments = PaymentsView::query();

            //normalize var to an integer
            $var = is_null($var) ? null : (int) $var;

            // ✅ Apply status & myacc filter early (before counts)
            $payments = $payments
                ->when(!is_null($var) && $var !== 0, function ($query) use ($var) {
                    return $query->where('payment_status', $var);
                })
                ->when($myacc, function ($query) use ($user) {
                    return $query->where('paid_by', $user->id);
                });



            // Fetch the totalData data after applying all scope filters
            $totalData = $payments->count();
            $totalFiltered = $totalData; 

            $limit = $limit_data;
            $start = $start_data;
            $order = $columns[$order_column];
            $dir = $order_dir;


            if(empty($search)) {       

                    //Fetch the bookings data after applying all scope filters
                    $payments = $payments->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();

                    
            } else {
                            
                    $payments =  $payments->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('payment_reference','LIKE',"%{$search}%")
                                                                          ->orWhere('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->offset($start)
                                                ->limit($limit)
                                                ->orderBy($order,$dir)
                                                ->get();


                    $totalFiltered = $payments->where(function($query) use ($search) {
                                                        return $query->where(function($q) use ($search) {
                                                                        $q->where('payment_reference','LIKE',"%{$search}%")
                                                                          ->orWhere('package','LIKE',"%{$search}%");               
                                                        });
                                                    }
                                                )
                                                ->count();
            }

            $data = array();

            if($payments->isNotEmpty()) {

                $counter = 1; // Start counter at 1

                    foreach ($payments as $payment) {

                        $edit =  $payment->id;
                        $delete =  $payment->id;

                        $payment_amount = $payment->payment_amount ? number_format($payment->payment_amount, 2) : "0.00";
                        $amount = $payment->amount ? number_format($payment->amount, 2) : "0.00";
                        $netAmount = $payment_amount - $amount;

                        $nestedData['id'] = $counter;
                        $nestedData['reference'] = $payment->payment_reference;
                        $nestedData['package'] = $payment->package;
                        $nestedData['amount'] = $payment->payment_gateway_currency ." ". $amount;
                        $nestedData['payment_amount'] = $payment->payment_gateway_currency ." ". $payment_amount;
                        $nestedData['fees'] = $payment->payment_gateway_currency . ' ' . number_format($netAmount, 2);


                        $nestedData['card_type'] = $payment->card_brand;
                        $nestedData['last4'] = "****".$payment->last4;
                        $nestedData['payment_method'] = strtoupper($payment->payment_method);
                        $nestedData['payment_start'] = Carbon::parse($payment->payment_start_created)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['payment_end'] = Carbon::parse($payment->payment_end_created)->locale(app()->getLocale())->translatedFormat('l jS F Y h:i a');
                        $nestedData['payment_status'] = "<span class='".$payment->payment_status_name_css."'>". __('messages.status.' . strtolower($payment->payment_status_name))."</span>";
                       

                        //generate the edit & deactivate links
                        $edit_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='editPayment' data-id='$edit' data-original-title='Edit' title='edit' class='btn btn-dark waves-effect waves-light editPayment'> <i class='mdi mdi-notebook-edit font-size-16 align-middle me-2'></i>". __('messages.buttons.edit') ."</a>";
                        $deactivate_link = "&emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='deletePayment' data-id='$delete' data-original-title='Delete' title='delete' class='btn btn-danger waves-effect waves-light deletePayment'><i class='mdi mdi-book-remove-multiple font-size-16 align-middle me-2'></i>". __('messages.buttons.cancel') ."</a>";
                        
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