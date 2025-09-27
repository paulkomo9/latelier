@extends('layouts.inner')

@section('content')
    
    <div class="container pt-4 pb-4">

        <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{  __('Users') }}</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{  __('FitBoard') }}</a></li>
                                    <li class="breadcrumb-item active">{{  __('Users') }}</li>
                                </ol>
                            </div>

                    </div>
                </div>
            </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-6">
                <div class="text-left mb-2">
                    <button type="button" class="btn btn-primary waves-effect waves-light {{ !$isAdmin && (!isset($permissions['add']) || !$permissions['add']) ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#usersModal" id="createNewUser">
                        <x-lucide-calendar-clock   class="font-size-16 align-middle me-2" style="width: 1.5em; height: 1.5em;"/>{{  __('Add New User') }}
                    </button>    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="notification-success" style="display:none;">
                    <i class="mdi mdi-check-all me-2"></i>
                        <span id="notification-success-message">User added.</span> 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert" id="notification-warning" style="display:none;">
                    <i class="mdi mdi-alert-outline me-2"></i>
                                                  Could not add user!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-users" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>{{  __('#') }}</th>
                                        <th>{{  __('Profile Pic') }}</th>
                                        <th>{{  __('Name') }}</th>
                                        <th>{{  __('Email') }}</th>
                                        <th>{{  __('Status') }}</th>
                                        <th>{{  __('Client') }}</th>
                                        <th>{{  __('Trainer') }}</th>
                                        <th>{{  __('Admin') }}</th>
                                        <th>{{  __('Online') }}</th>
                                        <!--th>{{  __('Last Login') }}</th-->
                                        <th>{{  __('Created At') }}</th>
                                        <th>{{  __('Updated At') }}</th>
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
            <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white" id="modalUsersHeading"></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger print-error-msg" role="alert" id="user-modal-warning" style="display:none">
                            <ul></ul>
                        </div>
                                
                        <!-- form starts here -->
                            <form id="userForm" name="userForm" class="form-horizontal">
                                <input type="hidden" name="user_id" id="schedule_id" class="form-control" value="">
                                <input type="hidden" name="created_by_code" id="created_by_code" class="form-control" value="">

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="row mb-3">

                                                   <label for="firstname" class="col-md-4 col-form-label">{{ __('First Name') }}</label>
                                                    <div class="col-md-8">
                                                           <input name="firstname" id="firstname" class="form-control" autocomplete="off" required autofocus>

                                                            <span class="invalid-feedback" role="alert" id="firstname_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>
                                                </div>

                                                <div class="row mb-3">

                                                   <label for="lastname" class="col-md-4 col-form-label">{{ __('Last Name') }}</label>
                                                    <div class="col-md-8">
                                                           <input name="lastname" id="lastname" class="form-control" autocomplete="off" required>

                                                            <span class="invalid-feedback" role="alert" id="lastname_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>
                                                </div>

                                                <div class="row mb-3">

                                                   <label for="email" class="col-md-4 col-form-label">{{ __('Email Address') }}</label>
                                                    <div class="col-md-8">
                                                           <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="off">


                                                            <span class="invalid-feedback" role="alert" id="email_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_client" id="is_client" required>
                                                            <label class="form-check-label" for="is_client">
                                                                Is Client?
                                                             </label>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_trainer" id="is_trainer" required>
                                                            <label class="form-check-label" for="is_client">
                                                                Is Trainer?
                                                             </label>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_super_admin" id="is_super_admin" required>
                                                            <label class="form-check-label" for="is_super_admin">
                                                                Is Admin?
                                                             </label>
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
                                    <button type="button" class="btn btn-primary" id="saveBtnUser" value="create"> <x-elusive-plus class="text-success font-size-12" style="width: 0.8em; height: 0.8em;" /> 
                                        <span id="saveBtnUserName">{{ __('Add User') }}</span><i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                                    </button>
                                </div>
                            </form>
                        <!-- form ends here -->
                    </div>
                </div>
            </div>
        <!-- Create Update Modal ends here -->

        <!-- Delete User Modal -->
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
        <!-- Delete User Modal Ends Here-->

    </div>

    <script>
        window.appLocale = "{{ app()->getLocale() }}";
    </script>

    
    <script>
       window.uniquePageName = "users"; // Unique ID for this Blade file needed to tabs
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
            addNewUser: @json(__('Add New User')),
            editUser: @json(__('Edit User')),
            addUser: @json(__('Add User')),
            updateUser: @json(__('Update User')),
            deactivateUser: @json(__('Deactivate User')),
            confirmDeactivate: @json(__('Are you sure you want to deactivate user:')),
            recordLocked: @json(__('Record is locked. Contact the Systems Administrator.')),
            error: @json(__('Error')),
            choose: @json(__('Select..')),
            // Add as needed...
        };
    </script>

    <!-- tabs data datatable and feautures init controls features adoption at visibility -->
    <script src="{{ asset('js/pages/tabs.init.js') }}"></script>


    <!-- user init -->
   <script src="{{ asset('js/pages/users.init.js') }}"></script>

   <!-- spinner init -->
   <script src="{{ asset('js/pages/spinner.init.js') }}"></script>

@endsection