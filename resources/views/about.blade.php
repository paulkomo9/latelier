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
					<div class="col-md-5 p-md-5 img img-2 mt-5 mt-md-0" style="background-image: url('{{ asset('images/FB_IMG_1758543141425.jpg') }}');">
					</div>
					<div class="col-md-7 wrap-about py-4 py-md-5 ftco-animate">
	          <div class="heading-section mb-5">
	          	<div class="pl-md-5">
		          	<span class="subheading mb-2">Welcome to Latelier Aquafitness</span>
		            <h2 class="mb-2">Dive Into Wellness with Aquafitness!</h2>
	            </div>
	          </div>
	          <div class="pl-md-5">
							<p>L'Atelier Aquafitness is a high-end Aquafitness boutique Studio bringing to Dubai an amazing healthy lifestyle concept revolving around Aquabiking, Iyashi dome and Detox Juice .It is currently a Ladies only facility.</p>
              <p>Aquabiking is one of Europe's most popular methods for keeping fit and healthy, the process is low impact high intensity workouts involving state of the art submerged bikes in a swimming pool.</p>
							<p>L'Atelier Aquafitness has become one of the most popular Dubai's go-to fitness locations and a permanent addition to the diaries of anyone looking to stay slim and toned.</p>
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
   	<!-- ========== Why Us ========== -->
       @include('layouts.why')
    <!-- ========== Why Us End ========== -->

@endsection