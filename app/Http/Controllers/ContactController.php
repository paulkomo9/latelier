<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactFormRequest;
use Illuminate\Support\Facades\Mail;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use Throwable;

class ContactController extends Controller
{
    /**
     * 
     */
    public function submitForm(ContactFormRequest $request)
    {

        try {
            // Honeypot check
                if ($request->filled('phone')) {
                    return back()->withErrors(['bot' => 'Suspicious activity detected.']);
                }

                // Send Email or store
                Mail::raw($request->message, function ($message) use ($request) {
                    $message->to('info@latelieraquafitness.fit')
                            ->subject("Contact Form Submission from {$request->name}")
                            ->replyTo($request->email);
                });

            return back()->with('success', 'Thanks! We received your message.');

        } catch (Throwable $e) {
                // Custom log file: contact-controller-error.log
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/contact-controller-error.log'),
                ])->error('Contact Form Submission Failed: ' . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'guest',
                    'exception' => $e->getTraceAsString(),
                    'input' => $request->except(['_token', 'g-recaptcha-response']), // Optional: exclude sensitive fields
                ]);

            return back()->withErrors(['system' => 'Sorry, something went wrong. Please try again later.']);
            // OR: return response(view('errors.500'), 500);
        }

    }
}
