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
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('my.bookings.index', ['lang' => app()->getLocale()]) }}" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">My Bookings</span>
                            <p class="text-muted">View upcoming sessions, cancel, or reschedule</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- My Packages -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('my.subscriptions.index', ['lang' => app()->getLocale()]) }}" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-shopping-cart fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">My Subcriptions</span>
                            <p class="text-muted">Track usage, expiry dates & available sessions</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- My Payment History -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="{{ route('my.payments.index', ['lang' => app()->getLocale()]) }}" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fa-solid fa-file-invoice-dollar fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">Payment History</span>
                            <p class="text-muted mb-1">View invoices and past payment records</p>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Edit Profile -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="#" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-user-edit fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">My Profile</span>
                            <p class="text-muted mb-1">Update your personal details and preferences</p>
                        </div>
                    </div>
                </a>
            </div>

     
            <!-- Messages -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="#" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-comments fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">Messages</span>
                            <p class="text-muted mb-1">Chat with your coach or support team</p>
                        </div>
                    </div>
                </a>
            </div>
            


            <!-- Performance Summary Link Card -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <a href="#" class="text-decoration-none">
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-running fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <span class="h6 d-block">Performance Summary</span>
                            <p class="text-muted mb-1">View your session stats</p>
                            <p class="text-muted small">(Calories, Heart Rate, Time)</p>
                        </div>
                    </div>
                </a>
            </div>


             <!-- Manage Packages (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fas fa-box-open fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('packages.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Packages</a>
                            <p class="text-muted small">(Pricing, Plans, Offers)</p>
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
                            <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('schedules.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Schedules</a>
                            <p class="text-muted small">(Schedules, Slots)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>


            <!-- Manage Entries (admin only) -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                @if(Auth::user()->is_super_admin == 5)
                    <div class="category text-center p-4 bg-white shadow-sm rounded h-100">
                        <div class="category-icon mb-3">
                            <i class="fa-solid fa-calendar-days fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('calendar.entries', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Calendar Entries</a>
                            <p class="text-muted small">(Sessions, Classes, Appointments)</p>
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
                            <i class="fas fa-clipboard-list fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('bookings.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Bookings</a>
                            <p class="text-muted small">(Bookings, Check-In, Cancel)</p>
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
                            <i class="fa-solid fa-money-check-dollar fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('subscriptions.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Subscriptions</a>
                            <p class="text-muted small">(Member's Subscriptions, Packages, Plans)</p>
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
                            <i class="fas fa-users-cog fa-2x text-info"></i>
                        </div>
                        <div class="category-info">
                            <a href="{{ route('users.index', ['lang' => app()->getLocale()]) }}" class="h6 d-block text-primary">Users</a>
                            <p class="text-muted small">(Trainers, Members, Clients)</p>
                        </div>
                    </div>
                @else
                    <div class="category bg-transparent border-0 h-100 invisible"></div>
                @endif
            </div>

            
        </div>

         

        <div class="text-center mt-5">
            <!--a href="#" class="btn btn-info px-4">View Full FitBoard</a-->
        </div>
    </div>
</section>


<!-- ===== End of Dashboard Categories Section ===== -->

@endsection
