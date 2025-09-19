@extends('layouts.inner')

@section('title') {{'Users Control Centre'}} @endsection
 
@section('content')

 <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Users Control Centre</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Security Vault</a></li>
                                            <li class="breadcrumb-item active">Users Control Centre</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-left mb-2">
                                          <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#usersModal" id="createNewUser">
                                               <i class="fas fa-user-plus font-size-16 align-middle me-2"></i> Add
                                            </button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                              <div class="col-lg-6">
                                           <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="notification-success" style="display:none;">
                                                <i class="mdi mdi-check-all me-2"></i>
                                                    <span id="notification-success-message">User  added.</span> 
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
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">
    
                                        <table id="datatable-users" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>Employee Photo</th>
                                                <th>Employee Number</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Company</th>
                                                <th>Designation</th>
                                                <th>Department</th>
                                                <th>Employee Status</th>
                                                <th>Access Status</th>
                                                <th>Is Super Admin</th>
                                                <th>System Language</th>
                                                <th>Timezone</th>
                                                <th>Roles</th>
                                                <th>Email Verified At</th>
                                                <th>Password Changed At</th>
                                                <th>Last Login At</th>
                                                <th>Online Status</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated By</th>
                                                <th>Updated At</th>
                                                <th>Options</th>
                                            </tr>
                                            </thead>
        
        
                                            <tbody>
                                                <!-- rows appear here -->
                                            </tbody>
                                        </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                 <!-- Modal -->
                 <div class="modal fade modal-lg" data-bs-backdrop="static" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title text-white" id="modelHeading"></h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>


                                <div class="alert alert-danger print-error-msg" style="display:none">
                                      <ul></ul>
                                </div>
                               
                                <!-- form starts here -->

                                <form id="userForm" name="userForm" class="form-horizontal">
                                <input type="hidden" name="user_id" id="user_id">
                                <input type="hidden" name="created_by_code" id="created_by_code">
                                <input type="hidden" name="employee_code_" id="employee_code_">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                             
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="company_code" class="form-label">Company</label>

                                                        <select name="company_code" id="company_code" class="select2 form-control select2"  style="width:100%;"></select>

                                                        <span class="invalid-feedback" role="alert" id="company_code_alert" style="display:none;">
                                                            <strong></strong>
                                                        </span> 
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                    <label for="employee_code" class="form-label">{{ __('Employee Name') }}</label>
                                                       <select name="employee_code" id="employee_code" class="select2 form-control select2"  style="width:100%;"></select>

                                                            
                                                                <span class="invalid-feedback" role="alert" id="employee_code_alert" style="display:none;">
                                                                    <strong></strong>
                                                                </span>
                                                           
                                                    </div>
                                                </div>
                                                
                                            </div>


                                           
                                       

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                                    <input type="text" class="form-control input-mask" id="email" name="email" 
                                                        value="{{ old('email') }}" placeholder="Enter email address." required autocomplete="email" autofocus data-inputmask="'alias': 'email'" readonly>  


                                                       
                                                            <span class="invalid-feedback" role="alert" id="email_alert">
                                                                <strong></strong>
                                                            </span>
                                                          
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                      <label for="roles" class="form-label">{{ __('Roles') }}</label>
                                                         <select name="roles[]" id="roles" class="select2 form-control select2" multiple="multiple" style="width:100%;" required></select>

                                                            
                                                                <span class="invalid-feedback" role="alert" id="roles_alert" style="display:none;">
                                                                    <strong></strong>
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                                                                        
                                                <div class="col-md-12">
                                                    <div class="mb-3"> 
                                                        <div class="form-check form-checkbox-outline form-check-info">
                                                            <input class="form-check-input" type="checkbox" name="is_super_admin" id="is_super_admin">
                                                             <label class="form-check-label" for="is_super_admin">
                                                              Super Administrator <span class="fst-italic fw-lighter">Check this box if user is super administrator.</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  
                                           <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" id="saveBtnUser" value="create">Authorize Access</button>
                                            </div>
                                </form>
                                 <!-- form ends here -->
                                

                                </div>
                            </div>
                    </div>

          
        <!-- users data datatable and feautures init -->
        <script src="{{ asset('js/pages/users.init.js') }}"></script>


@endsection