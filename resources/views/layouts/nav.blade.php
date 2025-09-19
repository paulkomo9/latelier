        <div class="wrap">
			<div class="container">
				<div class="row justify-content-between">
						<div class="col d-flex align-items-center">
							<p class="mb-0 phone"><span class="mailus">Phone no:</span> <a href="#">+00 1234 567</a> or <span class="mailus">email us:</span> <a href="#">info@latelieraquafitness.fit</a></p>
						</div>
						<div class="col d-flex justify-content-end">
							<div class="social-media">
				    		<p class="mb-0 d-flex">
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa-brands fa-facebook"><i class="sr-only">Facebook</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fab fa-twitter"><i class="sr-only">Twitter</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fab fa-instagram"><i class="sr-only">Instagram</i></span></a>
				    		</p>
			        </div>
						</div>
				</div>
			</div>
		</div>
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
			<div class="container">
				<a class="navbar-brand" href="/">L'Atelier<span>Aqua Fitness<i class="fa fa-leaf"></i></span></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="fa fa-bars"></span> Menu
				</button>
				<div class="collapse navbar-collapse" id="ftco-nav">
					<ul class="navbar-nav ml-auto">
                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                            <a href="{{ url('/') }}" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item {{ Request::routeIs('about') ? 'active' : '' }}">
                            <a href="{{ route('about', ['lang' => app()->getLocale()]) }}" class="nav-link">About</a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() === 'calendar.browse' ? 'active' : '' }}">
                            <a href="{{ route('calendar.browse', ['lang' => app()->getLocale()]) }}" class="nav-link">Sessions Calendar</a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() === 'sessions.explore' ? 'active' : '' }}">
                            <a href="{{ route('sessions.explore', ['lang' => app()->getLocale()]) }}" class="nav-link">Book A Session</a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() === 'packages.explore' ? 'active' : '' }}">
                            <a href="{{ route('packages.explore', ['lang' => app()->getLocale()]) }}" class="nav-link">Packages</a>
                        </li>
                        <li class="nav-item {{ Request::routeIs('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact', ['lang' => app()->getLocale()]) }}" class="nav-link">Contact</a>
                        </li>
                    </ul>


					<!-- Auth buttons (right side) -->
					@if (Route::has('login'))
						<div class="d-flex align-items-center gap-1">
							@auth
								<a href="{{ route('home', ['lang' => app()->getLocale()]) }}" class="btn btn-outline-info">Fitboard</a>
								<form id="logout-form" action="{{ route('logout', ['lang' => app()->getLocale()]) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-outline-danger">
										<i class="fas fa-sign-out-alt me-0"></i> Logout
									</button>
								</form>
							@else
								<a href="{{ route('login', ['lang' => app()->getLocale()]) }}" class="btn btn-outline-info me-2">Login</a>
								@if (Route::has('register'))
									<a href="{{ route('register', ['lang' => app()->getLocale()]) }}" class="btn btn-outline-info">Register</a>
								@endif
							@endauth
						</div>
					@endif
				</div>
		    </div>
	    </nav>
    <!-- END nav -->