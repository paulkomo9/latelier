<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\WorkflowStatusController;
use App\Http\Controllers\TrainersController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\PaymentsController;

Route::get('/', function () {
    return view('welcome');
});



/**
 * Routes should be wrapped with a prefix group:
 */
Route::group(['prefix' => '{lang}', 'middleware' => ['setlang']], function () {

        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/contact', [PageController::class, 'contact'])->name('contact');
        Route::get('/why-aquafitness', [PageController::class, 'aboutAquafitness'])->name('aquafitness.about');

        Auth::routes();

        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //Workflow Status
        Route::get('workflow-status/search/{id}', [WorkflowStatusController::class, 'search'])->name('workflow-status.search');
        Route::resource('/workflow-status', WorkflowStatusController::class)->name('*','workflow-status');

        //Currencies
        Route::get('currencies/search/{id}', [CurrenciesController::class, 'search'])->name('currencies.search');
        Route::resource('/currencies', CurrenciesController::class)->name('*','currencies');

        // Users
        Route::post('users/list',[UsersController::class, 'displayUsers'])->name('users.list');
        Route::resource('/users', UsersController::class)->name('*','users');

        //Trainers
        Route::get('trainers/search/{id}', [TrainersController::class, 'search'])->name('trainers.search');
        Route::resource('/trainers', TrainersController::class)->name('*','trainers');

        //Schedules
        Route::get('schedules/entries', [SchedulesController::class, 'entries'])->name('schedules.entries');
        Route::post('schedules/list',[SchedulesController::class, 'displaySchedules'])->name('schedules.list');
        Route::resource('/schedules', SchedulesController::class)->name('*','schedules');

        //Bookings
        Route::get('booking/confirmation/{id}', [BookingsController::class, 'confirmation'])->name('bookings.confirmation');
        Route::post('sessions/book/{id}', [BookingsController::class, 'book'])->name('sessions.book');
        Route::post('bookings/list',[BookingsController::class, 'displayBookings'])->name('bookings.list');
        Route::get('my/bookings',[BookingsController::class, 'myBookings'])->name('my.bookings.index');
        Route::resource('/bookings', BookingsController::class)->name('*','bookings');

        
        //Subscriptions
        Route::post('subscriptions/list',[SubscriptionsController::class, 'displaySubscriptions'])->name('subscriptions.list');
        Route::get('my/subscriptions',[SubscriptionsController::class, 'mySubscriptions'])->name('my.subscriptions.index');
        Route::resource('/subscriptions', SubscriptionsController::class)->name('*','subscriptions');


        //Packages
        Route::get('packages/explore', [PackagesController::class, 'explore'])->name('packages.explore');
        Route::get('packages/required', [PackagesController::class, 'required'])->name('packages.required');
        Route::post('packages/list',[PackagesController::class, 'displayPackages'])->name('packages.list');
        Route::resource('/packages', PackagesController::class)->name('*','packages');

        //Calendar
        Route::get('sessions/explore', [CalendarController::class, 'explore'])->name('sessions.explore');
        Route::get('calendar/browse', [CalendarController::class, 'browse'])->name('calendar.browse');
        Route::get('calendar/entries', [CalendarController::class, 'entries'])->name('calendar.entries');
        Route::post('calendar/list',[CalendarController::class, 'displayCalendarEntries'])->name('calendar.list');
        Route::get('calendar/search/{id}', [CalendarController::class, 'search'])->name('calendar.search');
        Route::resource('/calendar', CalendarController::class)->name('*','calendar');

        //Checkout
        Route::get('checkout/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
        Route::get('checkout/{type}/{id}', [CheckoutController::class, 'checkout'])->name('checkout.page');
        Route::post('checkout/{type}/{id}', [CheckoutController::class, 'process'])->name('checkout.process');

        //Payments 
        Route::get('my/payments',[PaymentsController::class, 'myPayments'])->name('my.payments.index');
        Route::post('payments/list',[PaymentsController::class, 'displayPayments'])->name('payments.list');
        Route::resource('/payments', PaymentsController::class)->name('*','payments');

});


