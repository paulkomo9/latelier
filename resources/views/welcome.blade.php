@extends('layouts.default')

@section('content')
    <div class="hero-wrap js-fullheight">
	    <div class="home-slider owl-carousel js-fullheight">
	      <div class="slider-item js-fullheight" style="background-image:url(images/latelier_n.jpg);">
	      	<div class="overlay"></div>
	        <div class="container">
	          <div class="row no-gutters slider-text align-items-center">
		          <div class="col-md-7 ftco-animate">
		          	<div class="text w-100">
		          		<h2 class="fw-bold text-white display-5 text-uppercase">Welcome to Latalier Aquafitness</h2>
			            <h1 class="mb-4 fw-bold text-white display-5">Wellness Flows Naturally with Aquafitness.</h1>
			            <p><a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary">Book a Session</a> <a href="{{ route('aquafitness.about', ['lang' => app()->getLocale()]) }}" class="btn btn-white">See the Benefits</a></p>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>

	      <div class="slider-item js-fullheight" style="background-image:url(images/latelier2_n.jpg);">
	      	<div class="overlay"></div>
	        <div class="container">
	          <div class="row no-gutters slider-text align-items-center">
		          <div class="col-md-7 ftco-animate">
		          	<div class="text w-100">
		          		<h2 class="fw-bold text-white display-5 text-uppercase">Dive Into Wellness with Aquafitness!</h2>
			            <h1 class="mb-4 fw-bold text-white display-5">Move Better. Feel Better. Aqua Better</h1>
			            <p><a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary">Book a Session</a> <a href="{{ route('aquafitness.about', ['lang' => app()->getLocale()]) }}" class="btn btn-white">See the Benefits</a></p>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>

	      <div class="slider-item js-fullheight" style="background-image:url(images/work-out-fun.jpg);">
	      	<div class="overlay"></div>
	        <div class="container">
	          <div class="row no-gutters slider-text align-items-center justify-content-end">
		          <div class="col-md-6 ftco-animate">
		          	<div class="text w-100">
		          		<h2 class="fw-bold text-white display-5 text-uppercase">Rediscover Health — The Natural Aquafitness Way.</h2>
			            <h1 class="mb-4 fw-bold text-white display-5">Strength. Balance. Wellness. All in Water.</h1>
			            <p><a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="btn btn-primary">Book a Session</a> <a href="{{ route('aquafitness.about', ['lang' => app()->getLocale()]) }}" class="btn btn-white">See the Benefits</a></p>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>
	    </div>
	</div>
		
	@livewire('show-classes')



    <section class="ftco-section ftco-no-pt ftco-no-pb bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-md-5 p-md-5 img img-2 mt-5 mt-md-0" style="background-image: url(images/FB_IMG_1758543141425.jpg);">
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
								<div class="img" style="background-image: url(images/latelier3_n.jpg);"></div>
								<div class="text pl-3">
									<h3 class="mb-0">Paul Komo</h3>
									<span class="position">C.E.0</span>
								</div>
							</div-->
						</div>
					</div>
				</div>
			</div>
	</section>

	<section class="ftco-section ftco-no-pt ftco-no-pb">
			<div class="container-fluid px-md-0">
				<!--div class="row no-gutters">
					<div class="col-md-3 d-flex align-items-stretch">
						<div class="consultation w-100 text-center px-4 px-md-5">
							<h3 class="mb-4">Healthcare Services</h3>
							<p>A small river named Duden flows by their place and supplies</p>
							<a href="#" class="btn-custom">See Services</a>
						</div>
					</div>
					<div class="col-md-6 d-flex align-items-stretch">
						<div class="consultation consul w-100 px-4 px-md-5">
							<div class="text-center">
								<h3 class="mb-4">Free Consultation</h3>
							</div>
							<form action="#" class="appointment-form">
								<div class="row">
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				    					<input type="text" class="form-control" placeholder="First Name">
				    				</div>
									</div>
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				    					<input type="text" class="form-control" placeholder="Last Name">
				    				</div>
									</div>
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				    					<div class="form-field">
		          					<div class="select-wrap">
		                      <div class="icon"><span class="fa fa-chevron-down"></span></div>
		                      <select name="" id="" class="form-control">
		                      	<option value="">Services</option>
		                        <option value="">Services 1</option>
		                        <option value="">Services 2</option>
		                      </select>
		                    </div>
				              </div>
				    				</div>
									</div>
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				    					<div class="input-wrap">
				            		<div class="icon"><span class="ion-md-calendar"></span></div>
				            		<input type="text" class="form-control appointment_date" placeholder="Date">
			            		</div>
				    				</div>
									</div>
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				    					<div class="input-wrap">
				            		<div class="icon"><span class="ion-ios-clock"></span></div>
				            		<input type="text" class="form-control appointment_time" placeholder="Time">
			            		</div>
				    				</div>
									</div>
									<div class="col-md-12 col-lg-6 col-xl-4">
										<div class="form-group">
				              <input type="submit" value="Appointment" class="btn btn-white py-2 px-4">
				            </div>
									</div>
								</div>
		    			</form>
		    	  </div>
					</div>
					<div class="col-md-3 d-flex align-items-stretch">
						<div class="consultation w-100 text-center px-4 px-md-5">
							<h3 class="mb-4">Find A Health Expert</h3>
							<p>A small river named Duden flows by their place and supplies</p>
							<a href="#" class="btn-custom">Meet our health coach</a>
						</div>
					</div>
				</div-->
			</div>
    </section>

	<!-- ========== Why Us ========== -->
       @include('layouts.why')
    <!-- ========== Why Us End ========== -->


    @livewire('show-packages')

@endsection
