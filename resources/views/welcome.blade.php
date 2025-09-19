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
			            <h1 class="mb-4 fw-bold text-white display-5">Get in shape faster, live your happy life</h1>
			            <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p>
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
		          		<h2 class="fw-bold text-success display-5 text-uppercase">A Fresh approach to healthy life</h2>
			            <h1 class="mb-4 fw-bold text-white display-5">Unlock your potential with good nutrition</h1>
			            <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p>
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
		          		<h2 class="w-bold text-white display-5 text-uppercase">Welcome Latelier Aquafitness</h2>
			            <h1 class="mb-4 fw-bold text-white display-5">You can transform health through habit change</h1>
			            <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>
	    </div>
	</div>
		
    <!--section class="ftco-section ftco-services">
    	<div class="container">
    		<div class="row">
          <div class="col-md-4 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block services-wrap text-center">
              <div class="img" style="background-image: url(images/services-1.jpg);"></div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Exercise Program</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn btn-primary btn-outline-primary">Enrol Now</a></p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block services-wrap text-center">
              <div class="img" style="background-image: url(images/services-2.jpg);"></div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Nutrition Plans</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn btn-primary btn-outline-primary">Enrol Now</a></p>
              </div>
            </div>    
          </div>
          <div class="col-md-4 d-flex services align-self-stretch px-4 ftco-animate">
            <div class="d-block services-wrap text-center">
              <div class="img" style="background-image: url(images/services-3.jpg);"></div>
              <div class="media-body p-2 mt-3">
                <h3 class="heading">Diet Program</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn btn-primary btn-outline-primary">Enrol Now</a></p>
              </div>
            </div>      
          </div>
        </div>
    	</div>
    </section-->

	@livewire('show-classes')



    <section class="ftco-section ftco-no-pt ftco-no-pb bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-md-5 p-md-5 img img-2 mt-5 mt-md-0" style="background-image: url(images/indoor-session.jpg);">
					</div>
					<div class="col-md-7 wrap-about py-4 py-md-5 ftco-animate">
	          <div class="heading-section mb-5">
	          	<div class="pl-md-5">
		          	<span class="subheading mb-2">Welcome to Latelier Aquafitness</span>
		            <h2 class="mb-2">Hello! Aquafitness is a natural way of improving your health</h2>
	            </div>
	          </div>
	          <div class="pl-md-5">
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
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


    @livewire('show-packages')

@endsection
