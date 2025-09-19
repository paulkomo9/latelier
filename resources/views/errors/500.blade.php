@extends('layouts.inner')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-4 text-danger">😓 Oops! Something went wrong</h1>

    <p class="lead mt-4">
        We're really sorry, but something broke on our end.
    </p>

    <p>
        Our team has been notified, and we're working to fix it as quickly as possible.
    </p>

    <a href="{{ url()->previous() }}" class="btn btn-outline-primary mt-4">
        🔙 Go Back
    </a>

    <a href="{{ url('/') }}" class="btn btn-primary mt-4 ms-2">
        🏠 Back to Home
    </a>
</div>
@endsection
