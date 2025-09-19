@extends('layouts.inner')

@section('content')


    <section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/latelier2_n.jpg') }}');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end">
          <div class="col-md-9 ftco-animate pb-5">
          	<p class="breadcrumbs mb-2"><span class="mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>About us <i class="ion-ios-arrow-forward"></i></span></p>
            <h1 class="mb-0 bread">About Us</h1>
          </div>
        </div>
      </div>
    </section>
		
    
    <section class="ftco-section ftco-no-pt ftco-no-pb bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-md-5 p-md-5 img img-2 mt-5 mt-md-0" style="background-image: url('{{ asset('images/indoor-session.jpg') }}');">
					</div>
					<div class="col-md-7 wrap-about py-4 py-md-5 ftco-animate">
	          <div class="heading-section mb-5">
	          	<div class="pl-md-5">
		          	<span class="subheading mb-2">Welcome to Latelier Aquafitness</span>
		            <h2 class="mb-2">Hello! Aqua fitness is a natural way of improving your health</h2>
	            </div>
	          </div>
	          <div class="pl-md-5">
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
							<!--div class="founder d-flex align-items-center mt-5">
								<div class="img" style="background-image: url('{{ asset('images/fitness-group-girls-doing-aerobical-excercises-swimming-pool-aqua-park-sport-leisure-activities.jpg') }}');"></div>
								<div class="text pl-3">
									<h3 class="mb-0">Paul Komo</h3>
									<span class="position">C.E.O</span>
								</div>
							</div-->
						</div>
					</div>
				</div>
			</div>
	</section>

@endsection