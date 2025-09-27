@extends('layouts.inner')

@section('content')

<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/latelier2_n.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end">
      <div class="col-md-9 ftco-animate pb-5">
        <p class="breadcrumbs mb-2">
          <span class="mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span> 
          <span>Why Aquafitness <i class="ion-ios-arrow-forward"></i></span>
        </p>
        <h1 class="mb-0 bread">Why Aquafitness?</h1>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb bg-light">
  <div class="container">
    <div class="row no-gutters">
      <div class="col-md-5 p-md-5 img img-2 mt-5 mt-md-0" style="background-image: url('{{ asset('images/latelier2_n.jpg') }}');"></div>
      <div class="col-md-7 wrap-about py-4 py-md-5 ftco-animate">
        <div class="heading-section mb-5">
          <div class="pl-md-5">
            <span class="subheading mb-2">What is Aquafitness?</span>
            <h2 class="mb-2">Fitness That Feels Good</h2>
          </div>
        </div>
        <div class="pl-md-5">
          <p>Aquafitness is a dynamic, low-impact fitness method performed in water, offering full-body conditioning with minimal strain on joints and muscles. It combines cardio, strength, and recovery — all enhanced by the natural resistance of water.</p>
          <p>Whether you’re aiming to tone up, improve circulation, boost flexibility, or recover safely, Aquafitness delivers powerful results in a gentle, spa-like setting.</p>
          <p>Perfect for women of all ages and fitness levels, it’s especially ideal for those seeking a wellness-forward approach to staying active.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center pb-5 mb-3">
      <div class="col-md-8 heading-section text-center ftco-animate">
        <span class="subheading">Why Choose Aquafitness?</span>
        <h2>Health, Elegance, and Results — All in the Water</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 d-flex services align-self-stretch px-4 ftco-animate">
        <div class="d-block text-left">
          <div class="icon d-flex justify-content-center align-items-center mb-3">
              <x-solar-stretching-bold class="font-size-20 align-middle ms-1 text-white"
                            style="width: 3em; height: 3em;" />
          </div>
          <div class="media-body">
            <h3 class="heading">Gentle on Joints</h3>
            <p>Water supports your body weight, making every move low-impact — perfect for recovery, pregnancy, or injury-sensitive fitness.</p>
          </div>
        </div>      
      </div>
      <div class="col-md-6 d-flex services align-self-stretch px-4 ftco-animate">
        <div class="d-block text-left">
          <div class="icon d-flex justify-content-center align-items-center mb-3">
            <x-fas-swimmer class="font-size-20 align-middle ms-1 text-white"
                            style="width: 3em; height: 3em;" />
          </div>
          <div class="media-body">
            <h3 class="heading">High-Calorie Burn</h3>
            <p>The water’s natural resistance challenges your muscles every second, resulting in an intense workout that feels easier than it is.</p>
          </div>
        </div>      
      </div>
      <div class="col-md-6 d-flex services align-self-stretch px-4 ftco-animate">
        <div class="d-block text-left">
          <div class="icon d-flex justify-content-center align-items-center mb-3">
             <x-fas-heart-pulse class="fw-bold font-size-20 align-middle ms-1 text-white"
                            style="width: 3em; height: 3em;" />
          </div>
          <div class="media-body">
            <h3 class="heading">Circulation & Detox</h3>
            <p>Warm water promotes blood flow and lymphatic drainage. Combined with our detox programs, you leave feeling lighter, refreshed, and radiant.</p>
          </div>
        </div>      
      </div>
      <div class="col-md-6 d-flex services align-self-stretch px-4 ftco-animate">
        <div class="d-block text-left">
          <div class="icon d-flex justify-content-center align-items-center mb-3">
             <x-solar-meditation-outline class="fw-bold font-size-20 align-middle ms-1 text-white"
                            style="width: 3em; height: 3em;" />
          </div>
          <div class="media-body">
            <h3 class="heading">A Mind-Body Escape</h3>
            <p>More than just exercise — it’s a wellness ritual. The calming rhythm of water helps reduce stress, center your mind, and elevate your mood.</p>
          </div>
        </div>      
      </div>
    </div>
  </div>
</section>

<section class="ftco-section bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Experience the Power of Water</h2>
    <p>Book your first Aquafitness session and feel the transformation in just one class.</p>
    <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary mt-3">Book a Session</a>
  </div>
</section>

@endsection
