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

}