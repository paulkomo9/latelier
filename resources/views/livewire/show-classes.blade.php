<section class="ftco-section {{ Request::is('/') ? 'ftco-services' : '' }}">
        <div class="container">
            <div class="row">
                @forelse ($classes as $class)
                    <div class="col-md-4 d-flex services align-self-stretch px-4 ftco-animate mb-4">
                        <div class="d-block services-wrap text-center">
                            <div class="img" style="background-image: url('{{ $class->appointment_image ?? asset('images/services-1.jpg') }}');"></div>
                            <div class="media-body p-2 mt-3">
                                <h3 class="heading">{{ $class->title ?? 'Class Title' }}</h3>

                                <p>
                                    <i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($class->start_date_time)->format('M d, Y') }}<br>
                                    <i class="fa fa-clock me-1"></i> {{ \Carbon\Carbon::parse($class->start_date_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_date_time)->format('H:i') }}<br>
                                    <i class="fa fa-map-marker-alt me-1"></i> {{ $class->location ?? 'N/A' }}<br>
                                    <i class="fa fa-users me-1"></i> Slots: {{ $class->slots - $class->slots_taken ?? 0 }} {{ __('REMAINING') }}<br>
                                    <i class="fa fa-user me-1"></i> Trainer: {{ $class->trainer_name ?? 0 }}
                                </p>

                                @php
                                    $availableSlots = $class->slots - ($class->slots_taken ?? 0);
                                @endphp

                                @if ($availableSlots > 0)
                                    <p><a href="{{ route('calendar.show', ['calendar' => $class->id, 'lang' => app()->getLocale()]) }}" class="btn btn-primary btn-outline-primary">Enroll Now</a></p>
                                @else
                                     <p>
                                        <span class="badge bg-danger fs-5 fw-bold px-3 py-2">
                                            🔴 Sold Out
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No classes available at the moment.</p>
                    </div>
                @endforelse
            </div>

        </div>
</section>

