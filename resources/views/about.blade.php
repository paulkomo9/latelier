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
							<p>L'Atelier Aquafitness is a high-end Aquafitness boutique Studio bringing to Dubai an amazing healthy lifestyle concept revolving around Aquabiking, Iyashi dome and Detox Juice .It is currently a Ladies only facility.</p>
              <p>Aquabiking is one of Europe's most popular methods for keeping fit and healthy, the process is low impact high intensity workouts involving state of the art submerged bikes in a luxury indoor swimming pool.</p>
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
	<section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center pb-5 mb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
          	<span class="subheading mb-3">Why Us</span>
            <h2>Why Choose L'Atelier Aquafitness?</h2>
          </div>
        </div>
    		<div class="row">
          <div class="col-md-3 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-first"></span>
              </div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">High-End Experience</h3>
                 <p>L'Atelier Aquafitness is a high end aqua fitness boutique bringing to Dubai a new and amazing fitness concept known in Europe as Aquabiking.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-woman"></span>
              </div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Ladies Only</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>    
          </div>
          <div class="col-md-3 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-diet-1"></span>
              </div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Low-Impact, High Intensity Workouts</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-diet"></span>
              </div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Detoxification and Regeneration</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>      
          </div>
        </div>
    	</div>
    </section>

@endsection