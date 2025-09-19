@extends('layouts.inner')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="mb-3">👋 Hey there! A Plan is Needed to Book a Class</h2>
        <p class="lead">
            To book a class, you'll need an active plan.  
            Good news — you're just a click away from getting started!
        </p>
        <p>Choose a plan that fits your goals, and let's get moving 🚀</p>
    </div>

    {{-- Your Livewire Component --}}
    <div class="row justify-content-center">
        @livewire('show-packages')
    </div>

    <div class="text-center mt-5 text-muted">
        <p>If you already have a plan but still can't book, let us know.</p>
        <a href="mailto:support@example.com" class="text-decoration-none">📧 Contact Support</a>
    </div>
</div>

@endsection
