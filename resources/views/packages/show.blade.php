@extends('layouts.inner')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card p-4 shadow">
                    <div class="card-body">
                        {{-- Package Image --}}
                        <div class="img mb-4" style="
                            background-image: url('{{ $package->package_image ?? asset('images/services-1.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            height: 250px;
                            border-radius: .5rem;
                        "></div>

                        {{-- Package Title and Price --}}
                        <h2 class="mb-3">{{ $package->package ?? 'Untitled Package' }}</h2>
                        <p class="h4">
                            <i class="fa fa-tag me-2"></i>
                            <strong>{{ $package->currency ?? 'AED' }} {{ $package->amount ?? '0' }}</strong>
                        </p>

                        {{-- Package Details --}}
                        <p><i class="fa fa-calendar me-2"></i> Valid for: <strong>{{ $package->validity }}</strong></p>
                        <p><i class="fa fa-list me-2"></i> Total Sessions: <strong>{{ $package->sessions_total }}</strong></p>

                        {{-- Features List --}}
                        @php
                            $features = json_decode($package->features, true);
                        @endphp

                        @if (!empty($features))
                            <div class="mb-4">
                                <h5>What’s Included</h5>
                                <ul class="list-unstyled">
                                    @foreach ($features as $feature)
                                        <li><i class="fa fa-check-circle text-success me-2"></i>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Optional Description --}}
                        @if (!empty($package->description))
                            <div class="mb-4">
                                <h5>Package Overview</h5>
                                <p>{{ $package->description }}</p>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="d-flex gap-2 align-items-center mt-4">
                            <a href="{{ route('checkout.page', ['type' => 'package', 'id' => $package->id, 'lang' => app()->getLocale()]) }}" class="btn btn-success">
                                <i class="fa fa-shopping-cart me-1"></i> Subscribe
                            </a>

                            <a href="{{ route('packages.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Back to Packages
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
