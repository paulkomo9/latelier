@extends('layouts.inner')

@section('content')
    
    <div class="container pt-4 pb-4">
        <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{  __('Packages') }}</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home', ['lang' => app()->getLocale()]) }}">{{  __('FitBoard') }}</a></li>
                                    <li class="breadcrumb-item active">{{  __('Packages') }}</li>
                                </ol>
                            </div>

                    </div>
                </div>
            </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-6">
                <div class="text-left mb-2">
                    <button type="button" class="btn btn-primary waves-effect waves-light {{ !$isAdmin && (!isset($permissions['add']) || !$permissions['add']) ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#packagesModal" id="createNewPackage">
                        <x-mdi-package-variant-closed-plus  class="font-size-16 align-middle me-2" style="width: 1.5em; height: 1.5em;"/>{{  __('Add New Package') }}
                    </button>    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="notification-success" style="display:none;">
                    <i class="mdi mdi-check-all me-2"></i>
                        <span id="notification-success-message">Package added.</span> 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert" id="notification-warning" style="display:none;">
                    <i class="mdi mdi-alert-outline me-2"></i>
                                                  Could not add package!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-packages" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>{{  __('#') }}</th>
                                        <th>{{  __('Package') }}</th>
                                        <th>{{  __('Total Sessions') }}</th>
                                        <th>{{  __('Validity') }}</th>
                                        <th>{{  __('Status') }}</th>
                                        <th>{{  __('Image') }}</th>
                                        <th>{{  __('Amount') }}</th>
                                        <th>{{  __('Tax Type') }}</th>
                                        <th>{{  __('Tax') }}</th>
                                        <th>{{  __('Total Amount') }}</th>
                                        <th>{{  __('Description') }}</th>
                                        <th>{{  __('Created By') }}</th>
                                        <th>{{  __('Created At') }}</th>
                                        <th>{{  __('Updated By') }}</th>
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
            <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="packagesModal" tabindex="-1" aria-labelledby="packagesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white" id="modalPackagesHeading"></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger print-error-msg" role="alert" id="package-modal-warning" style="display:none">
                            <ul></ul>
                        </div>
                                
                        <!-- form starts here -->
                            <form id="packageForm" name="packageForm" class="form-horizontal">
                                <input type="hidden" name="package_id" id="package_id" class="form-control" value="">
                                <input type="hidden" name="created_by_code" id="created_by_code" class="form-control" value="">

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="row mb-3">

                                                    <div class="col-md-9">
                                                        <div class="mb-3">
                                                            <label for="package" class="form-label">{{ __('Package') }}</label>
                                                                <input type="text" class="form-control" id="package" name="package" placeholder="{{ __('e.g Summer challenge - 12 classes in 4 weeks') }}" required autocomplete="off">

                                                                    <span class="invalid-feedback" role="alert" id="package_alert" style="display:none;">
                                                                        <strong></strong>
                                                                    </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                            <label for="sessions_total" class="form-label">{{ __('Sessions') }}</label>
                                                                <input type="number" id="sessions_total" name="sessions_total" class="form-control input-mask text-start" min="0" value="1" max="999">

                                                                <span class="invalid-feedback" role="alert" id="sessions_total_alert" style="display:none;">
                                                                    <strong></strong>
                                                                </span> 
                                                    </div>   
                                                     
                                                </div>
                                                
                                                

                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label for="currency" class="form-label">{{ __('Currency') }}</label>
                                                            <select name="currency" id="currency"  class="select2 form-control select2-no-overflow"  required  style="width:100%;"></select>

                                                                <span class="invalid-feedback" role="alert" id="currency_alert" style="display:none;">
                                                                    <strong></strong>
                                                                </span> 
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="amount" class="form-label">{{ __('Amount') }}</label>
                                                            <input name="amount" id="amount" class="form-control input-mask text-start"  value="0.00"  required>

                                                            <span class="invalid-feedback" role="alert" id="amount_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>

                                                     <div class="col-md-4">
                                                        <label for="tax_type" class="form-label">{{ __('Tax Type') }}</label>
                                                        <select name="tax_type" id="tax_type" class="form-control">
                                                            <option value="fixed" selected>Fixed</option>
                                                            <option value="percentage">Percentage</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="tax" class="form-label">{{ __('Tax') }}</label>
                                                            <input name="tax" id="tax" class="form-control input-mask text-start"  value="0.00"  required>

                                                            <span class="invalid-feedback" role="alert" id="tax_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>
                                                </div>

                                                <div class="row mb-3">

                                                   <label for="total_amount" class="col-md-2 col-form-label">{{ __('Total Amount') }}</label>
                                                    <div class="col-md-3">
                                                           <input name="total_amount" id="total_amount" class="form-control input-mask text-start"  value="0.00"  required readonly>

                                                            <span class="invalid-feedback" role="alert" id="total_amount_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 
                                                    </div>
                                                    <label for="validity_quantity" class="col-md-2 col-form-label">{{ __('Validity') }}</label>
                                                     <div class="col-md-2 mb-2">
                                                        <input type="number" id="validity_quantity" name="validity_quantity" class="form-control input-mask text-start" min="0" value="1" max="999">

                                                                <span class="invalid-feedback" role="alert" id="validity_quantity_total_alert" style="display:none;">
                                                                    <strong></strong>
                                                                </span> 
                                                     </div>
                                                     <div class="col-md-3">
                                                        <select name="validity_unit" id="validity_unit"  class="select2 form-control select2-no-overflow"  required  style="width:100%;">
                                                            <option value="day" selected="selected">Day(s)</option>
                                                            <option value="week">Weeks(s)</option>
                                                            <option value="month">Months(s)</option>
                                                             <option value="year">Years(s)</option>
                                                        </select>

                                                            <span class="invalid-feedback" role="alert" id="validity_unit_alert" style="display:none;">
                                                                <strong></strong>
                                                            </span> 

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

                                                
                                                <div class="row mb-3">
                                                    <div class="upload-box" id="singleUpload">
                                                        <input type="file" id="singleImageInput" accept="image/*" class="hidden-input">
                                                            {{ __('Drop an image here or click to upload package image') }}
                                                                <img id="singlePreview" class="img-thumbnail mt-2" style="ddisplay: none; max-width: 600px; max-height: 200px; object-fit: contain;">
                                                    </div>
                                                    <span class="invalid-feedback" role="alert" id="singlePreview_alert" style="display:none;">
                                                        <strong></strong>
                                                    </span>
                                                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                            
                                <div class="modal-footer bg-secondary-subtle">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><x-elusive-remove class="text-warning font-size-12" style="width: 0.8em; height: 0.8em;" /> 
                                        {{ __('Close') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="saveBtnPackage" value="create"> <x-elusive-plus class="text-success font-size-12" style="width: 0.8em; height: 0.8em;" /> 
                                        <span id="saveBtnPackageName">{{ __('Add Package') }}</span><i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                                    </button>
                                </div>
                            </form>
                        <!-- form ends here -->
                    </div>
                </div>
            </div>
        <!-- Create Update Modal ends here -->

        <!-- Delete Package Modal -->
            <div class="modal fade" id="deletePackageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="deletePackageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="deletePackageModalTitle">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger print-error-msg" role="alert" id="delete-warning" style="display:none">
                            <ul></ul>
                        </div>

                        <!--content-->
                            <div class="modal-body" id="deletePackageModalContent">
                            </div>
                        <!--content ends here-->
                                            
                        <div class="modal-footer bg-secondary-subtle">
                            <!--form starts here-->
                                <form id="deletePackageForm"  class="form-horizontal">
                                    <input type="hidden" name="del_package_id" id="del_package_id" class="form-control">
                                        <div class="mb-3"> 
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                <x-elusive-remove class="text-warning font-size-12 me-2" style="width: 1em; height: 1em;" /> 
                                                    {{ __('Cancel') }}
                                            </button>

                                            <button type="button" class="btn bg-danger text-white" id="deleteBtnPackage" value="delete"><x-uiw-delete class="text-white font-size-20 me-2" style="width: 1em; height: 1em;" /> 
                                                {{ __('Deactivate Package') }} 
                                                   <i class="fa fa-spinner fa-spin font-size-20 align-middle me-2" style="display:none;"></i>
                                            </button>
                                        </div>
                                </form>
                            <!--form ends here-->
                        </div>
                    </div>
                </div>
            </div>
        <!-- Delete Package Modal Ends Here-->

    </div>

    <script>
        window.appLocale = "{{ app()->getLocale() }}";
    </script>


    <script>
       window.uniquePageName = "packages"; // Unique ID for this Blade file needed to tabs
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
            addNewPackage: @json(__('Add New Package')),
            editPackage: @json(__('Edit Package')),
            addPackage: @json(__('Add Package')),
            updatePackage: @json(__('Update Package')),
            deactivatePackage: @json(__('Deactivate Package')),
            confirmDeactivate: @json(__('Are you sure you want to deactivate package:')),
            recordLocked: @json(__('Record is locked. Contact the Systems Administrator.')),
            error: @json(__('Error')),
            choose: @json(__('Select..')),
            // Add as needed...
        };
    </script>

    <!-- tabs data datatable and feautures init controls features adoption at visibility -->
    <script src="{{ asset('js/pages/tabs.init.js') }}"></script>


    <!-- packages init -->
   <script src="{{ asset('js/pages/packages.init.js') }}"></script>

   <!-- spinner init -->
   <script src="{{ asset('js/pages/spinner.init.js') }}"></script>

@endsection