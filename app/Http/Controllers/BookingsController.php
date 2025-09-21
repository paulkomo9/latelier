<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\BookingService;

class BookingsController extends Controller
{

    protected BookingService $bookingService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BookingService $bookingService)
    {
        $this->middleware(['guest.lang']);
        $this->middleware(['check.plan','restrict.multi.booking'])->only('book'); 
        $this->middleware(['check.access'])->only('index','displayBookings'); 
        $this->bookingService = $bookingService;
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

            return view('bookings.index', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'bookings-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/bookings-controller-error.log')
                ])->error("Bookings Index View Failed: " . $e->getMessage(), [
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Display booking confirmation.
     */
    public function confirmation(Request $request, string $locale, string $id)
    {
        try {
            $criteria = [
                'id' => $id
            ];

            // Get booking details
            $booking = $this->bookingService->searchBookings($criteria, 'find');

            if (!$booking) {
                return redirect()->route('sessions.explore', ['lang' => $locale])
                                ->with('error', 'Booking not found.');
            }

            // Generate QR code
            $qrCode = QrCode::size(200)->generate($booking->reference);

            return view('bookings.confirmation', compact('booking', 'qrCode'));

        } catch (\Throwable $e) {
            // Log detailed error
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/bookings-controller-error.log')
            ])->error("Booking Confirmation Failed: " . $e->getMessage(), [
                'booking_id' => $id,
                'user_id' => \Auth::id() ?? 'Guest',
                'exception' => $e->getTraceAsString()
            ]);

            // Redirect with error
            return redirect()->route('sessions.explore', ['lang' => $locale])
                            ->with('error', 'Something went wrong loading your booking confirmation.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * book session.
     */
    public function book(string $locale, string $id)
    {
        try {

                $user = Auth::user();
                $booking_id = null;
                $booking_status = 15;
                $reference = 'BK' . now()->format('YmdHis') . strtoupper(Str::random(5));
                $user_id = $user->id;
                $attendance_marked_by = null;
                $attended_at = null;

                $response = $this->bookingService->updatecreateBooking(
                    $booking_id, $reference, $user_id, $id, $booking_status, $attendance_marked_by, $attended_at
                );

                if (isset($response['error']) && $response['error'] === true) {
                    return redirect()->back()->with('error', $response['message'] ?? 'Booking failed.');
                }

                return redirect()->route('bookings.confirmation', [
                    'lang' => $locale,
                    'id' => $response['booking']->id
                ]);

        } catch (Throwable $e) {
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/bookings-controller-error.log')
                ])->error("Booking Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'schedule_id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

                return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

   
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function displayBookings(Request $request)
    {
        try {
                $limit_data = $request->input('length');
                $start_data = $request->input('start');
                $order_column = $request->input('order.0.column');
                $order_dir = $request->input('order.0.dir');
                $search = $request->input('search.value'); 
                $draw = $request->input('draw');
                $var = $request->input('booking_status');
                
                $json_data = $this->bookingService->displayBookingsTableData(
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
                // Custom logging to 'bookings-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/bookings-controller-error.log')
                ])->error("Display Bookings Failed: " . $e->getMessage(), [
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
