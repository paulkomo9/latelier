<?php
namespace App\Http\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\PaymentSuccessful;
use Auth;
use Carbon\Carbon;
use Throwable;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\BalanceTransaction;
use Stripe\Charge;
use App\Http\Services\PackageService;
use App\Http\Services\CalendarService;
use App\Http\Services\PaymentService;



class  CheckoutService 
{

    protected PackageService $packageService;
    protected CalendarService $calendarService;
     protected PaymentService $paymentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PackageService $packageService, CalendarService $calendarService, PaymentService $paymentService)
    {
        $this->packageService = $packageService;
        $this->calendarService = $calendarService;
        $this->paymentService = $paymentService;
    }

    /**
     * Prepare Checkout
     * @param string $type
     * @param int $id
     * 
     * @return array $arrResponse
     */
    public function prepareCheckout($type, $id)
    {
        try {
                $criteria = [
                    'id' => $id
                ];

                

                if ($type === 'package') {
                    // lets get package data
                    $item = $this->packageService->searchPackages($criteria, 'find');

                } elseif ($type === 'calendar') {
                    // lets get package data
                    $item = $this->calendarService->searchCalendarEntries($criteria, 'find');

                } else {
                    throw new \Exception("Invalid checkout type: $type");
                }


                Stripe::setApiKey(config('services.stripe.secret'));

                $intent = PaymentIntent::create([
                    'amount' => intval($item->total_amount * 100),
                    'currency' => strtolower($item->currency ?? 'AED'),
                    'payment_method_types' => ['card'],
                ],
                [
                    'expand' => ['payment_method'], // Expand payment method details
                ]);



                return $arrResponse = [
                            "success" => true,
                            "intent" => $intent,
                            "item" => $item,
                            "type" => $type
                        ];


        } catch (Throwable $e) {
                // Custom logging to 'checkout-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/checkout-service-error.log')
                ])->error("Prepare Checkout Failed: " . $e->getMessage(), [
                    'type' => $type,
                    'id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.checkout.update_failed')
                        ]; 

        }       
    }


    /**
     * handle Payment
     * @param Request $request
     * @param string $type
     * @param int $id
     */
    public function handlePayment($request, $type, $id)
    {
        try {
                $user = Auth::user();

                // retrieve payment intent id
                $paymentIntentId = $request->input('payment_intent_id');

                if (!$paymentIntentId) {
                    throw new \Exception("Missing payment intent id");
                }

                Stripe::setApiKey(config('services.stripe.secret'));

                // ✅ Retrieve full intent with expanded charge + details
                $intent = PaymentIntent::retrieve([
                    'id' => $paymentIntentId,
                    'expand' => [
                        'latest_charge',
                        'latest_charge.payment_method_details',
                        'latest_charge.balance_transaction'
                    ]
                ]);

                $charge = $intent->latest_charge;
                $cardDetails = $charge->payment_method_details->card ?? null;
                $balanceTransactionId = $charge->balance_transaction ?? null;

                // Basic data
                $transactionId = $intent->id;
                $currency = strtoupper($intent->currency);
                $amount = $intent->amount_received / 100;
                $outcome = $intent->outcome['seller_message'] ?? 'Payment processed';
                $message = $charge->outcome->seller_message ?? null;

               

                 // Retrieve full balance transaction details
                $balanceTransaction = null;
                $fees = 0;
                $netAmount = 0;
                $taxOnFee = 0;

               if ($balanceTransactionId) {
                     // Fees
                    $balanceTransaction = BalanceTransaction::retrieve($balanceTransactionId);
                    $fees = $balanceTransaction->fee / 100; // Convert cents to currency
                    $netAmount = $balanceTransaction->net / 100; // Net amount after Stripe fees
                }

                // Card info
                $cardBrand = $cardDetails ? ucfirst($cardDetails->brand) : 'Unknown';
                $last4 = $cardDetails->last4 ?? 'XXXX';

                // Method type
                $methodType = $intent->payment_method_types[0] ?? 'Unknown';

                // Timestamps
                $paymentStart = date('Y-m-d H:i:s', $intent->created);
                $paymentEnd = $paymentStart;


                $payment_id = null;
                $payment_status = 13; // SUCCESSFUL
                $package_id = $id;


                $response = $this->paymentService->updatecreatePackage($payment_id, $transactionId, $currency, $amount, $fees, $taxOnFee, $message, $balanceTransaction, 
                                                               $outcome, $methodType, $last4, $cardBrand, $paymentStart, $paymentEnd, $package_id, $payment_status, $user->id);


                //disppatch successful event
                event(new PaymentSuccessful($response['payment'], $user));


                 return $arrResponse = [
                            "success" => true,
                            "message" => $response
                        ];


        } catch (Throwable $e) {
                // Custom logging to 'checkout-service-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/checkout-service-error.log')
                ])->error("Handle Payment Failed: " . $e->getMessage(), [
                    'type' => $type,
                    'id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            return $arrResponse = [
                            "error" => true,
                            "message" => __('messages.checkout.update_failed')
                        ]; 

        } 
    }
}