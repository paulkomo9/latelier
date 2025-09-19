@extends('layouts.inner')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h2 class="mb-4 text-success"><i class="fa fa-check-circle"></i> Booking Successful!</h2>
                <p>Your booking has been confirmed.</p>
                <p><strong>Reference Number:</strong> {{ $booking->reference }}</p>

                <div class="my-4">
                    <p>Show this QR code when attending the session:</p>
                    {!! QrCode::size(200)->generate($booking->reference) !!}
                </div>

                <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary mt-3">Back to Sessions</a>
            </div>
        </div>
    </div>
</section>
@endsection
