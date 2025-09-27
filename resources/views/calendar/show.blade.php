@extends('layouts.inner')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            @if(session('error'))
                <div class="alert alert-warning">
                    {{ session('error') }}
                </div>
            @endif

            <div class="col-md-10">
                <div class="card p-4 shadow">
                    <div class="card-body">
                        <div class="img mb-4" style="
                            background-image: url('{{ $class->appointment_image ?? asset('images/services-1.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            height: 250px;
                            border-radius: .5rem;
                        "></div>



                        <h2 class="mb-4">{{ $class->title }}</h2>

                        <p><i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($class->start_date_time)->format('M d, Y') }}</p>
                        <p><i class="fa fa-clock me-1"></i> {{ \Carbon\Carbon::parse($class->start_date_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_date_time)->format('H:i') }}</p>
                        <p><i class="fa fa-map-marker-alt me-1"></i> {{ $class->location ?? 'N/A' }}</p>
                        <p><i class="fa fa-users me-1"></i> Slots: {{ $class->slots - ($class->slots_taken ?? 0) }} {{ __('REMAINING') }}</p>
                        <p><i class="fa fa-user me-1"></i> Trainer: {{ $class->trainer_name ?? 'N/A' }}</p>

                        <hr>

                        <div class="mb-3">
                            <h5>Session Snapshot</h5>
                            <p>{{ $class->description ?? 'No description available.' }}</p>
                        </div>

                        @php
                            $availableSlots = $class->slots - ($class->slots_taken ?? 0);
                        @endphp

                        <div class="d-flex gap-2 align-items-center mt-3">
                            @if ($availableSlots > 0)
                                <form method="POST" action="{{ route('sessions.book', ['id' => $class->id, 'lang' => app()->getLocale()]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-bag-shopping"></i> Book This Session
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-danger fs-5 fw-bold px-3 py-2 align-self-center">
                                    🔴 Sold Out
                                </span>
                            @endif

                            <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Back to Sessions
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
