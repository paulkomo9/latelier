<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="L'Atelier Aquafitness offers expert-led aqua fitness classes that improve flexibility, burn calories, and support joint health. Join today for a fun, low-impact workout experience." />
    <meta content="latelier" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

       <!-- DataTables CSS -->
      <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />


      <!-- Responsive datatable-->
      <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 

      <!-- DataTables Group Heading css-->
      <link href="{{ asset('css/datatable-group.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
     <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

   

    <!-- Select 2 --> 
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Patches Css-->
    <link href="{{ asset('css/patches.css') }}" id="patch-style" rel="stylesheet" type="text/css" />

    <!-- Image Upload Css-->
    <link href="{{ asset('css/image-upload.css') }}" id="upload-style" rel="stylesheet" type="text/css" />

    
    <!-- TUI Calendar CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/tui-time-picker/tui-time-picker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/tui-date-picker/tui-date-picker.min.css') }}">
    <link href="{{ asset('libs/tui-calendar/tui-calendar.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Load Font Awesome all icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"/>

      <!-- Load Material Design Icons (MDI) all icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">


    <!-- Jquery at the top advisable -->
   <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Geoapify CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@geoapify/geocoder-autocomplete@2.1.0/styles/minimal.css">

    <!-- Geoapify JS (Correct Browser Build) -->
    <script src="https://unpkg.com/@geoapify/geocoder-autocomplete@^2/dist/index.min.js"></script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MQL195R3VC"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-MQL195R3VC');
    </script>
    
   @livewireStyles

  </head>
  <body>
	<!-- ========== Nav Start ========== -->
       @include('layouts.nav')
  <!-- ========== Nav End ========== -->

  
    <!-- Start Page-content -->
        @yield('content')
    <!-- End Page-content -->



    <!-- ========== Nav Start ========== -->
        @include('layouts.footer')
    <!-- ========== Nav End ========== -->
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <!-- Locked Content/Record Modal -->
    <div class="modal fade" id="lockedcontentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="lockedcontentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                  <h5 class="modal-title text-white" id="lockedcontentModalTitle">Modal title</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!--content-->
                    <div class="modal-body" id="lockedcontentModalContent"></div>
                <!--content ends here-->

                  <div class="modal-footer bg-secondary-subtle">
                    <!--form starts here-->
                      <form id="lockedcontentForm"  class="form-horizontal">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                          <x-elusive-remove class="text-warning font-size-12 me-2" style="width: 1em; height: 1em;" /> 
                            {{ __('Dismiss') }}
                        </button>
                      </form>
                    <!--form ends here-->
                  </div>
              </div>
          </div>
    </div>
  <!-- Locked Content/Record Modal Ends Here-->


  <!-- Session Expired Modal -->
    <div class="modal fade" id="sessionexpiredModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="sessionexpiredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white" id="sessionexpiredModalTitle">Modal title</h5>
                </div>
              <!--content-->
                <div class="modal-body" id="sessionexpiredModalContent"></div>
              <!--content ends here-->
                           
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="sessionBtnExpiry" value="session">Login</button>
                </div>
            </div>
        </div>
    </div>
  <!--Session Expired Modal Ends Here-->


  <script src="{{ asset('js/jquery-migrate-3.0.1.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
  <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
  <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
  <script src="{{ asset('js/jquery.animateNumber.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
  <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('js/scrollax.min.js') }}"></script>



  <!-- Bootstrap 5 modals require the Bootstrap JS Bundle, which includes Popper-->
  <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!--moment with locale-->
  <script src="{{ asset('js/moment-with-locale.js') }}"></script>
  
  <script src="{{ asset('js/main.js') }}"></script>

  <!-- Required datatable js -->
  <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

  <!-- Buttons examples -->
  <script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

  <!-- Responsive support -->
  <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>


  <!-- Required select 2 js -->
  <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>


  <!-- TUI Calendar JS -->
     
  <script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.min.js"></script>
  <script src="{{ asset('libs/tui-dom/tui-dom.min.js') }}"></script>
  <script src="{{ asset('libs/tui-time-picker/tui-time-picker.min.js') }}"></script>
  <script src="{{ asset('libs/tui-date-picker/tui-date-picker.min.js') }}"></script>
  <script src="{{ asset('libs/tui-calendar/tui-calendar.min.js') }}"></script>


   <!-- form mask -->
   <script src="{{ asset('libs/inputmask/inputmask.min.js') }}"></script>

  


  <!-- form mask init-->
  <script src="{{ asset('js/pages/form-mask.init.js') }}"></script>


  <!-- common utils - functions - methods -->
  <script src="{{ asset('js/pages/common.utils.js') }}"></script>  

  
  @livewireScripts

  </body>
</html>