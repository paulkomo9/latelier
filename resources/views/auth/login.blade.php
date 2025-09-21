@extends('layouts.inner')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/latelier2_n.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end">
      <div class="col-md-9 ftco-animate pb-5">
        <p class="breadcrumbs mb-2">
          <span class="mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span>
          <span>Login<i class="ion-ios-arrow-forward"></i></span>
        </p>
        <h1 class="mb-0 bread">Login</h1>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <div class="row justify-content-center pt-4 pb-4">
    <div class="col-md-8">
      <div class="card shadow-sm bg-light rounded">
        <div class="card-body">
          <form method="POST" action="{{ route('login', ['lang' => app()->getLocale()]) }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                </div>

                {{-- Password --}}
                <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                    <div class="clearfix">
                    <div class="float-end">
                        @if (Route::has('password.request'))
                        <a class="btn btn-link"
                        href="{{ route('password.request', ['lang' => app()->getLocale()]) }}">
                        {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>
                    </div>
                </div>
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password" required
                    autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                </div>

                {{-- Remember Me --}}
                <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                    </div>
                </div>
                </div>

                {{-- Login Button --}}
                <div class="row mb-3">
                    <div class="col-md-4 offset-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <x-uiw-login style="width: 1.5em; height: 1.5em;"/>
                            {{ __('Login') }}<i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                        </button>
                    </div>
                </div>

                {{-- Social Logins --}}
                <div class="row mb-3">
                    <div class="col-md-6 mx-auto text-center">
                        <h5 class="font-size-14 mb-3">Or sign in with</h5>

                        <div class="d-grid gap-2">
                        {{-- Facebook --}}
                        <a href="{{ url('auth/facebook') }}" class="btn social-btn text-start">
                            <i class="mdi mdi-facebook me-2 text-primary fs-5"></i> {{ __('Login with Facebook') }}
                        </a>

                        {{-- Google (optional) --}}
                        <a href="#" class="btn social-btn text-start">
                            <i class="mdi mdi-google me-2 text-danger fs-5"></i> {{ __('Login with Google') }}
                        </a>

                        {{-- Instagram (placeholder) --}}
                        <a href="#" class="btn social-btn text-start">
                            <i class="mdi mdi-instagram me-2 fs-5" style="color:#C13584;"></i> {{ __('Login with Instagram') }}
                        </a>
                        </div>
                    </div>
                </div>

                {{-- Signup --}}
                <div class="row">
                    <div class="col-md-4 offset-md-4 text-center">
                        <p class="mb-0">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register', ['lang' => app()->getLocale()]) }}" class="fw-medium text-primary">
                            {{ __("Signup now") }}
                            <x-gmdi-app-registration-r class="font-size-20 align-middle ms-1"
                            style="width: 1em; height: 1em;" />
                        </a>
                        </p>
                    </div>
                </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Social Login Button Styling --}}
<style>
  .social-btn {
    background-color: white;
    border: 1px solid #dee2e6;
    border-radius: 50px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    text-decoration: none;
  }

  .social-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
  }

  .social-btn i {
    font-size: 1.25rem;
  }
</style>

<!-- spinner init -->
<script src="{{ asset('js/pages/spinner.init.js') }}"></script>

@endsection
