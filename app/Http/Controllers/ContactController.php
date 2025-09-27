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
use Throwable;

class ContactController extends Controller
{
    /**
     * 
     */
    public function submitForm(ContactFormRequest $request)
    {

        // Honeypot check
        if ($request->filled('phone')) {
            return back()->withErrors(['bot' => 'Suspicious activity detected.']);
        }

        // Send Email or store
        Mail::raw($request->message, function ($message) use ($request) {
            $message->to('admin@example.com')
                    ->subject("Contact Form Submission from {$request->name}")
                    ->replyTo($request->email);
        });

        return back()->with('success', 'Thanks! We received your message.');

    }
}
