@extends('layouts.inner')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-md-8">
                <div class="card p-5 shadow-sm">
                    <div class="card-body">
                        <h2 class="text-success mb-4">
                            <i class="fa fa-check-circle me-2"></i>
                            Payment Successful!
                        </h2>
                        <p class="mb-4">
                            Thank you for your purchase. Your payment has been processed successfully.
                        </p>
                        <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-1"></i> Back to Sessions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
