@extends('layouts.checkout')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <h2 class="mb-0">Checkout</h2>
            </div>
        </div>

        @php
            $item = $data; // Alias for readability
        @endphp

        <div class="row justify-content-center">
            {{-- Payment Form --}}
            <div class="col-md-6">
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-3">Payment Details</h4>

                    <form id="payment-form" action="{{ route('checkout.process', ['type' => $type, 'id' => $item->id, 'lang' => app()->getLocale()]) }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div id="card-errors" class="text-danger mb-3" role="alert"></div>

                        @php
                            $user = Auth::user();
                            $cardholdername = trim($user->firstname . ' ' . ($user->lastname ?? ''));
                        @endphp

                        <div class="mb-3">
                            <label for="card-holder-name" class="form-label">Cardholder Name</label>
                            <input id="card-holder-name" type="text" class="form-control" value="{{ $cardholdername }}" required readonly>
                        </div>

                        <div class="mb-3">
                            <label for="card-element" class="form-label">Card Details</label>
                            <div id="card-element" class="form-control" style="height:auto;"></div>
                        </div>

                        <div id="card-errors" class="text-danger mb-3" role="alert"></div>

                        <button id="card-button" class="btn btn-success w-100" type="submit"
                            data-secret="{{ $intent->client_secret ?? '' }}">
                            <i class="fa fa-credit-card me-1"></i>
                            Pay {{ $item->currency ?? 'AED' }} {{ number_format($item->total_amount, 2) }}
                        </button>
                        <div style="text-align: center; margin-top: 15px;">
                            <p style="font-size: 14px; color: #444;">
                                <span style="color: green;">🔒</span>PCI-compliant & SSL encrypted
                            </p>
                            <p class="text-muted small" >Powered By</p>

                            <a href="https://stripe.com" target="_blank">
                                <img src="{{ asset('images/stripe/Stripe_Logo_1.png') }}" alt="Stripe Logo" style="height: 40px;">
                            </a>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-md-4">
                <div class="card p-4 shadow-sm bg-light rounded">
                    <h4 class="mb-3">Order Summary</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>{{ $type === 'package' ? 'Plan:' : 'Session:' }}</strong><br>
                            {{ $item->package ?? $item->title ?? 'N/A' }}
                        </li>

                        @if ($type === 'package')
                            <li class="mb-2">
                                <strong>Sessions:</strong> {{ $item->sessions_total }}
                            </li>
                            <li class="mb-2">
                                <strong>Valid for:</strong> {{ $item->validity }}
                            </li>
                        @endif

                        <hr class="my-2 border-secondary">

                        <li class="mb-2">
                            <strong>Price:</strong><br>
                            {{ $item->currency ?? 'AED' }} {{ number_format($item->amount, 2) }}
                        </li>

                        <li class="mb-2">
                            <strong>Tax 
                                @if ($item->tax_type === 'percentage')
                                    ({{ number_format($item->tax, 2) }}%)
                                @endif
                                :
                            </strong><br>

                            @if ($item->tax_type === 'percentage')
                                {{ $item->currency ?? 'AED' }} {{ number_format($item->total_amount - $item->amount, 2) }}
                            @else
                                {{ $item->currency ?? 'AED' }} {{ number_format($item->tax, 2) }}
                            @endif
                        </li>

                        <hr class="my-2 border-secondary">

                        <li>
                            <strong>Total:</strong><br>
                            {{ $item->currency ?? 'AED' }} {{ number_format($item->total_amount, 2) }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- Stripe’s JavaScript library -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Your Stripe form logic -->
<script src="{{ asset('js/pages/checkout.init.js') }}"></script>

 <!-- spinner init -->
<script src="{{ asset('js/pages/spinner.init.js') }}"></script>

@endsection
