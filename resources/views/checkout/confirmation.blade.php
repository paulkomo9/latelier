@extends('layouts.inner')

@section('content')
        <div class="container py-5">
            {{-- 🎉 Success Message --}}
            <div class="text-center mb-5">
                <h2 class="text-success">🎉 Congratulations!</h2>
                <p class="lead mt-3">
                    Your plan has been successfully activated. You're all set to start booking your classes!
                </p>
            </div>

            {{-- 👇 Livewire: Class List for Booking --}}
            <div class="mt-4">
                @livewire('show-classes') {{-- Replace with your actual component --}}
            </div>

            <div class="d-flex gap-2 align-items-center mt-3">
                <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-secondary">
                    More Sessions<i class="fa fa-arrow-right me-1"></i> 
                </a>
            </div>

            {{-- ℹ️ Optional Support --}}
            <div class="text-center text-muted mt-5">
                <p>Need help or have questions? <a href="mailto:support@example.com">Contact us</a> anytime.</p>
            </div>
        </div>
@endsection
