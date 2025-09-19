@extends('layouts.inner')

@section('content')
<!-- ===== Start of Dashboard Categories Section ===== -->
<section class="ptb80 bg-light" id="dashboard-categories">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>Welcome to Your FitBoard {{ Auth::user()->firstname }}</h2>
            <p>Quick access to everything you need</p>
        </div>

        <div class="row gy-4 justify-content-center">
            <!-- My Bookings -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-calendar-check fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">My Bookings</a>
                        <p class="text-muted">(Manage)</p>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-shopping-cart fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">My Packages</a>
                        <p class="text-muted">(Manage)</p>
                    </div>
                </div>
            </div>

            <!-- Class Schedule -->
            <!--div class="col-lg-2 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-dumbbell fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Sessions</a>
                        <p class="text-muted">(Upcoming sessions)</p>
                    </div>
                </div>
            </div-->

            <!-- Edit Profile -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-user-edit fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Edit Profile</a>
                        <p class="text-muted">(Update your info)</p>
                    </div>
                </div>
            </div>

          

            

            
            <!-- Meet Coaches -->
            <!--div class="col-lg-2 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-user-friends fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Meet Coaches</a>
                        <p class="text-muted">(Learn more)</p>
                    </div>
                </div>
            </div-->

            <!-- Messages -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-comments fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Messages</a>
                        <p class="text-muted">(Coach Q&A)</p>
                    </div>
                </div>
            </div>

              <!-- Progress Tracker -->
            <!--div class="col-lg-2 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Progress Tracker</a>
                        <p class="text-muted">(Coming soon)</p>
                    </div>
                </div>
            </div->


         

            <!-- Health Stats -->
            <!--div class="col-lg-2 col-md-4 col-sm-6">
                <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                    <div class="category-icon mb-3">
                        <i class="fas fa-heartbeat fa-2x text-info"></i>
                    </div>
                    <div class="category-info">
                        <a href="#" class="h6 d-block">Health Stats</a>
                        <p class="text-muted">(Performance)</p>
                    </div>
                </div>
            </div-->

             <!-- Manage Packages (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-box-open fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('packages.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Packages</a>
                            <p class="text-muted">(Pricing & offers)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>


            <!-- Manage Schedules (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('schedules.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Schedules</a>
                            <p class="text-muted">(Schedules, slots)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>


            <!-- Manage Classes (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fa-solid fa-calendar-days fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('calendar.entries', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Calendar Entries</a>
                            <p class="text-muted">(Sessions)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>

            <!-- Manage Bookings (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('bookings.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Bookings</a>
                            <p class="text-muted">(Check, edit, assign)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>

           
            <!-- Manage Users (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-users-cog fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('users.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Users</a>
                            <p class="text-muted">(Trainers & members)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>

             <!-- Manage User Packages (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fa-solid fa-money-check-dollar fa-2x text-primary"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('packages.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block">Manage Member's Packages</a>
                            <p class="text-muted">(Subscriptions)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>
        </div>

         

        <div class="text-center mt-5">
            <a href="#" class="btn btn-info px-4">View Full FitBoard</a>
        </div>
    </div>
</section>


<!-- ===== End of Dashboard Categories Section ===== -->

@endsection
