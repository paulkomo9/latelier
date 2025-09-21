@extends('layouts.inner')

@section('content')
    
    <div class="container pt-4 pb-4">

         <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{  __('Bookings') }}</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{  __('FitBoard') }}</a></li>
                                    <li class="breadcrumb-item active">{{  __('Manage Bookings') }}</li>
                                </ol>
                            </div>

                    </div>
                </div>
            </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-6">
                <div class="text-left mb-2">
                    <!--button type="button" class="btn btn-primary waves-effect waves-light {{ !$isAdmin && (!isset($permissions['add']) || !$permissions['add']) ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#schedulesModal" id="createNewSchedule">
                        <x-lucide-calendar-clock   class="font-size-16 align-middle me-2" style="width: 1.5em; height: 1.5em;"/>{{  __('Add New Session') }}
                    </button-->    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="notification-success" style="display:none;">
                    <i class="mdi mdi-check-all me-2"></i>
                        <span id="notification-success-message">Booking added.</span> 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert" id="notification-warning" style="display:none;">
                    <i class="mdi mdi-alert-outline me-2"></i>
                                                  Could not add booking!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-bookings" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>{{  __('#') }}</th>
                                        <th>{{  __('Booking Ref.') }}</th>
                                        <th>{{  __('Session Title') }}</th>
                                        <th>{{  __('Date') }}</th>
                                        <th>{{  __('Start Time') }}</th>
                                        <th>{{  __('End Time') }}</th>
                                        <th>{{  __('Booked By') }}</th>
                                        <th>{{  __('Status') }}</th>
                                        <th>{{  __('Trainer') }}</th>
                                        <th>{{  __('Slots') }}</th>
                                        <th>{{  __('Booked') }}</th>
                                        <th>{{  __('Location') }}</th>
                                        <th>{{  __('Booked By') }}</th>
                                        <th>{{  __('Booked On') }}</th>
                                        <th>{{  __('Attended At') }}</th>
                                        <th>{{  __('Attendance Marked By') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- rows appear here -->
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Update Modal -->
            <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="schedulesModal" tabindex="-1" aria-labelledby="schedulesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white" id="modalSchedulesHeading"></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger print-error-msg" role="alert" id="schedule-modal-warning" style="display:none">
                            <ul></ul>
                        </div>
                                
                        <!-- form starts here -->
                            <form id="scheduleForm" name="scheduleForm" class="form-horizontal">
                                <input type="hidden" name="schedule_id" id="schedule_id" class="form-control" value="">
                                <input type="hidden" name="location_latitude" id="location_latitude" class="form-control" value="">
                                <input type="hidden" name="location_longitude" id="location_longitude" class="form-control" value="">
                                <input type="hidden" name="location_timezone" id="location_timezone" class="form-control" value="">
                                <input type="hidden" name="location_address" id="location_address" class="form-control" value="">
                                <input type="hidden" name="created_by_code" id="created_by_code" class="form-control" value="">

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="row mb-3">
                                                    <label  class="form-label">{{ __('Tag Location') }}</label>

                                                        <div id="autocomplete" class="autocomplete-container col-md-12"></div>

                                                            <span class="invalid-feedback align-right" role="alert" id="location_latitude_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                </div>

                                                <div class="row mb-3">

                                                    <div class="col-md-5">
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label">{{ __('Title') }}</label>
                                                                <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('e.g Aquafit Signature Business Bay') }}" required autocomplete="off">

                                                                    <span class="invalid-feedback" role="alert" id="title_alert" style="display:none;">
                                                                        <strong></strong>
                                                                    </span>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-md-5 mb-3">
                                                        <div class="row">
                                                            <!-- Starts -->
                                                                <div class="col-6">
                                                                    <label for="starts_date" class="form-label">{{ __('Starts') }}</label>
                                                                        <input class="form-control input-mask" 
                                                                            name="starts_date" type="date" 
                                                                            id="starts_date" 
                                                                            value="{{ old('starts_date') }}"
                                                                            required autocomplete="off">

                                                                            <span class="invalid-feedback" role="alert" id="starts_date_alert" style="display:none;">
                                                                                <strong></strong>
                                                                            </span>
                                                                </div>

                                                            <!-- Ends -->
                                                                <div class="col-6">
                                                                    <label for="ends_date" class="form-label">{{ __('Ends') }}</label>
                                                                        <input class="form-control input-mask" 
                                                                            name="ends_date" type="date" 
                                                                            id="ends_date" 
                                                                            value="{{ old('ends_date') }}"
                                                                            autocomplete="off">

                                                                            <span class="invalid-feedback" role="alert" id="ends_date_alert" style="display:none;">
                                                                                <strong></strong>
                                                                            </span>
                                                                </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-2">
                                                        <label for="recurring_status" class="form-label">{{ __('Recurring') }}</label>
                                                            <select name="recurring_status" id="recurring_status"  class="select2 form-control select2-no-overflow"  required  style="width:100%;"></select>

                                                            <span class="invalid-feedback" role="alert" id="recurring_status_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>

                                                    
                                                     
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="row">
                                                            <!-- Starts -->
                                                                <div class="col-6">
                                                                    <label for="start_time" class="form-label">{{ __('Start Time') }}</label>
                                                                       <input id="start_time" type="time" class="form-control" name="start_time" value="00:00" required>

                                                                                    <span class="invalid-feedback" role="alert" id="start_time_alert" style="display:none;">
                                                                                        <strong></strong>
                                                                                    </span> 
                                                                </div>

                                                            <!-- Ends -->
                                                                <div class="col-6">
                                                                        <label for="end_time" class="form-label">{{ __('End Time') }}</label>
                                                                            <input id="end_time" type="time" class="form-control" name="end_time" value="23:59" required>

                                                                                    <span class="invalid-feedback" role="alert" id="end_time_alert" style="display:none;">
                                                                                        <strong></strong>
                                                                                    </span> 
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="row">
                                                            <!-- Starts -->
                                                                <div class="col-3">
                                                                    <label for="slots" class="form-label">{{ __('Slots') }}</label>
                                                                       <input type="number" id="slots" name="slots"
                                                                                                                class="form-control input-mask text-start"
                                                                                                                min="0" value="1" max="9">

                                                                                    <span class="invalid-feedback" role="alert" id="slots_alert" style="display:none;">
                                                                                        <strong></strong>
                                                                                    </span> 
                                                                </div>

                                                            <!-- Ends -->
                                                                <div class="col-9">
                                                                        <label for="trainer_id" class="form-label">{{ __('Trainer') }}</label>
                                                                           <select name="trainer_id" id="trainer_id"  class="select2 form-control responsibility-id select2"  required  style="width:100%;"></select>
                                                                                    <span class="invalid-feedback" role="alert" id="trainer_id_alert" style="display:none;">
                                                                                        <strong></strong>
                                                                                    </span> 
                                                                </div>
                                                        </div>
                                                    </div>
                                                     
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-floating mb-3">
                                                            <textarea id="description" name="description" class="form-control" class="form-control" placeholder="" style="height: 60px"></textarea>
                                                            <label for="description">{{ __('Description') }}</label>

                                                            <span class="invalid-feedback" role="alert" id="description_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                            
                                <div class="modal-footer bg-secondary-subtle">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><x-elusive-remove class="text-warning font-size-12" style="width: 0.8em; height: 0.8em;" /> 
                                        {{ __('Close') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="saveBtnSchedule" value="create"> <x-elusive-plus class="text-success font-size-12" style="width: 0.8em; height: 0.8em;" /> 
                                        <span id="saveBtnScheduleName">{{ __('Add Session') }}</span><i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                                    </button>
                                </div>
                            </form>
                        <!-- form ends here -->
                    </div>
                </div>
            </div>
        <!-- Create Update Modal ends here -->

        <!-- Delete Schedule Modal -->
            <div class="modal fade" id="deleteScheduleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="deleteScheduleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="deleteScheduleModalTitle">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger print-error-msg" role="alert" id="delete-warning" style="display:none">
                            <ul></ul>
                        </div>

                        <!--content-->
                            <div class="modal-body" id="deleteScheduleModalContent">
                            </div>
                        <!--content ends here-->
                                            
                        <div class="modal-footer bg-secondary-subtle">
                            <!--form starts here-->
                                <form id="deleteScheduleForm"  class="form-horizontal">
                                    <input type="hidden" name="del_schedule_id" id="del_schedule_id" class="form-control">
                                        <div class="mb-3"> 
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                <x-elusive-remove class="text-warning font-size-12 me-2" style="width: 1em; height: 1em;" /> 
                                                    {{ __('Cancel') }}
                                            </button>

                                            <button type="button" class="btn bg-danger text-white" id="deleteBtnSchedule" value="delete"><x-uiw-delete class="text-white font-size-20 me-2" style="width: 1em; height: 1em;" /> 
                                                {{ __('Deactivate Session') }} 
                                                   <i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                                            </button>
                                        </div>
                                </form>
                            <!--form ends here-->
                        </div>
                    </div>
                </div>
            </div>
        <!-- Delete Company Modal Ends Here-->

    </div>

    <script>
        window.appLocale = "{{ app()->getLocale() }}";
    </script>


    <script>
       window.uniquePageName = "bookings"; // Unique ID for this Blade file needed to tabs
    </script>

    <script>
        window.translations = {
            zeroRecordsMessage: @json(__('messages.zero_records')),
            emptyStates:       @json(__('messages.empty_states')),
            // (optional) a block for other DataTables labels:
            dataTables: {
                processing:   @json(__('messages.datatable.processing')),
                info:         @json(__('messages.datatable.info')),
                infoEmpty:    @json(__('messages.datatable.info_empty')),
                infoFiltered: @json(__('messages.datatable.info_filtered')),
                lengthMenu:   @json(__('messages.datatable.length_menu')),
                search:       @json(__('messages.datatable.search')),
                paginate: {
                    first:    @json(__('messages.datatable.paginate.first')),
                    last:     @json(__('messages.datatable.paginate.last')),
                    next:     @json(__('messages.datatable.paginate.next')),
                    previous: @json(__('messages.datatable.paginate.previous')),
                },
                aria: {
                    sortAscending:  @json(__('messages.datatable.aria.sort_asc')),
                    sortDescending: @json(__('messages.datatable.aria.sort_desc')),
                }
            }
        };
        window.translations.select2 = @json(__('messages.select2'));
    </script>

     <script>

        const TRANSLATIONS = {
            addNewBooking: @json(__('Add New Booking')),
            editBooking: @json(__('Edit Booking')),
            addBooking: @json(__('Add Booking')),
            updateBooking: @json(__('Update Booking')),
            deactivateSchedule: @json(__('Deactivate Booking')),
            confirmDeactivate: @json(__('Are you sure you want to deactivate booking:')),
            recordLocked: @json(__('Record is locked. Contact the Systems Administrator.')),
            error: @json(__('Error')),
            choose: @json(__('Select..')),
            // Add as needed...
        };
    </script>

    <!-- tabs data datatable and feautures init controls features adoption at visibility -->
    <script src="{{ asset('js/pages/tabs.init.js') }}"></script>


    <!-- bookings init -->
   <script src="{{ asset('js/pages/bookings.init.js') }}"></script>

   <!-- spinner init -->
   <script src="{{ asset('js/pages/spinner.init.js') }}"></script>

@endsection