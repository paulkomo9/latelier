<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center pb-5 mb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <span class="subheading mb-3">Price &amp; Packages</span>
                <h2>Choose Your Perfect Package</h2>
            </div>
        </div>

        @php
        use Illuminate\Support\Str;
        @endphp

        <div class="row">
            @forelse ($packages as $package)
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch ftco-animate">
                    <div class="block-7 w-100 text-center">
                        
                        {{-- 📸 Package Image --}}
                        <div class="img" style="background-image: url('{{ $package->package_image ?? asset('images/services-1.jpg') }}'); height: 200px; background-size: cover; background-position: center; border-top-left-radius: 10px; border-top-right-radius: 10px;"></div>


                        <div class="p-3">
                            <h4 class="heading-2 mt-3">{{ $package->package }}</h4>
                
                            <span class="price">
                                <sup>{{ $package->currency ?? 'AED' }}</sup><span class="number">{{ number_format($package->amount, 2) }}</span>
                            </span>

                            

                            <ul class="pricing-text mb-4">
                                <li><span class="fa fa-check mr-2"></span>{{ $package->sessions_total }} {{ $package->sessions_total == 1 ? 'Session' : 'Sessions' }}</li>
                                <li><span class="fa fa-check mr-2"></span> {{'Valid for'}} {{ $package->validity_quantity }} {{Str::plural($package->validity_unit, $package->validity_quantity)}}</li>
                            </ul>

                            <a href="{{ route('packages.show', ['package' => $package->id, 'lang' => app()->getLocale()]) }}" class="btn btn-primary px-4 py-3">Get Started</a>
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
