@extends('layouts.inner')

@section('content')
<section class="ftco-section bg-light">
    <div class="container">
        {{-- Page Header --}}
        <div class="row justify-content-center pb-5 mb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <span class="subheading mb-3">Price &amp; Plans</span>
                <h2>Choose Your Perfect Plans</h2>
            </div>
        </div>

        {{-- Packages Listing --}}
        <div class="row">
            @forelse ($packages as $package)
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch ftco-animate">
                    <div class="block-7 w-100">
                        <div class="text-center">
                            {{-- Package Image --}}

                            <div class="img" style="background-image: url('{{ $package->package_image ?? asset('images/services-1.jpg') }}'); height: 200px; background-size: cover; background-position: center; border-top-left-radius: 10px; border-top-right-radius: 10px;"></div>



                            <h4 class="heading-2">{{ $package->package ?? 'Untitled' }}</h4>

                            <span class="price">
                                <sup>{{ $package->currency ?? 'AED' }}</sup> <span class="number">{{ $package->amount ?? '0' }}</span>
                            </span>

                            {{-- Features from JSON --}}
                            @php
                                $features = json_decode($package->features, true);
                            @endphp

                            <ul class="pricing-text mb-5">
                                    <li><span class="fa fa-check mr-2"></span>{{ $package->sessions_total }} {{'Sessions'}}</li>
                                    <li><span class="fa fa-check mr-2"></span> {{'Valid for'}} {{ $package->validity }} </li>
                            </ul>

                            <a href="{{ route('packages.show', ['package' => $package->id, 'lang' => app()->getLocale()]) }}" class="btn btn-primary px-4 py-3">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center pt-4 pb-4">
                    <p>No packages available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
