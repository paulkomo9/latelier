$(function () {

    let optModal = $('#packagesModal');
    const locale = window.appLocale;

    // Common Inputmask options
    const maskOpts = {
        alias: "decimal",
        groupSeparator: ",",
        autoGroup: true,
        digits: 2,
        digitsOptional: false,
        prefix: "",
        placeholder: "0.00",
        min: 0,                // block below zero
        allowMinus: false,     // block minus sign
    };

    var im = new Inputmask({
            alias: "decimal",
            groupSeparator: ",",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            prefix: "",
            placeholder: "0.00",
            allowMinus: false,
            min: 0, 
    });


    
    /**
     * Pass Header Token
     */
    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
    });


    /**
     * prevents infinite loops during uploads
     */
    let isTriggered = false; // Prevents infinite loops


    /**
     * 
     */
    $("#singleUpload").off("click").on("click", function () {
        if (!isTriggered) {
            isTriggered = true;
            $("#singleImageInput").trigger("click");
            
            // Reset flag after a short delay to avoid infinite loop
            setTimeout(() => {
                isTriggered = false;
            }, 500);
        }
    });


    /**
     * 
     */
    $("#singleUpload").off("drop").on("drop", function (e) {
        e.preventDefault();
        $(this).removeClass("dragover");

        let file = e.originalEvent.dataTransfer.files[0];
        let preview = $("#singlePreview");
        handleSingleUpload(file, preview);
    });



    /**
     * 
     */
    $("#singleUpload").on("dragover", function (e) {
        e.preventDefault();
        $(this).addClass("dragover");
    });



    /**
     * 
     */
    $("#singleUpload").on("dragleave", function () {
        $(this).removeClass("dragover");
    });


    /**
     * Prevent mutiple bindings
     */
    $("#singleImageInput").off("change").on("change", function (event) {
        let file = event.target.files[0];
        let preview = $("#singlePreview");
        handleSingleUpload(file, preview);
    });


    /**
     * Lets have a function to calculate total amount
     */
    function calculateTotals() {
        // ✅ Always pull from masked OR fallback (if masked field isn't available)
        let amount = getNumericValue($('#amount'), $('#amount_'), maskOpts);
        let tax = getNumericValue($('#tax'), $('#tax_'), maskOpts);
        let taxType = $('#tax_type').val() || 'fixed'; // Default to fixed if undefined

        let service_fee = 0;

        if (taxType === 'percentage') {
            service_fee = (amount * tax / 100);
        } else {
            service_fee = tax;
        }

        const totalAmount = amount + service_fee;

        $('#total_amount').val(totalAmount.toFixed(2));

        // Re-apply input mask
        var im = new Inputmask({
            alias: "decimal",
            groupSeparator: ",",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            prefix: "",
            placeholder: "0",
            min: 0,
            allowMinus: false,
        });

        im.mask('#total_amount');
    }


    /**
     * delegate events to call calculate totals
     */
    $(document).on('input change blur', '#amount, #tax, #tax_type', function () {
        //console.log('Input changed:', this.id);
        calculateTotals();
    });



    /**
     * Render Packages DataTables
     */
    //let all datatable erros show only on the console/on prod set to ignore
    $.fn.dataTableExt.sErrMode = "console";

    // load all datatable 
    var package_status_all = 0;

    var tablePackages = $('#datatable-packages')
    .on('xhr.dt', function ( e, settings, json, xhr ) {
              //
                //console.log(xhr);
                if(typeof(xhr.responseJSON) == 'undefined'){
                  //
                }
    })
    .on('error.dt', function ( e, settings, techNote, message ){
                // 
    })
    .DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": true,
        "language": buildDataTableLanguage('packages', '#all'),
        "ajax":{
            "url": "/"+locale+"/packages/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), package_status: package_status_all}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center" },
            { "data": "package", "className": "vertical-center" },
            { "data": "sessions_total", "className": "text-center vertical-center" },
            { "data": "validity", "className": "vertical-center" },
            { "data": "package_status", "className": "text-center vertical-center" },
            { "data": "package_image"},
            { "data": "amount",  "className": "vertical-center" },
            { "data": "tax_type",  "className": "vertical-center" },
            { "data": "tax",  "className": "vertical-center" },
            { "data": "total_amount",  "className": "vertical-center" },
            { "data": "description", "className": "vertical-center" }, 
            { "data": "created_by", "className": "vertical-center" }, 
            { "data": "created_at", "className": "vertical-center" }, 
            { "data": "updated_by", "className": "vertical-center" }, 
            { "data": "updated_at", "className": "vertical-center" }, 
            { "data": "options", "className": "vertical-center" } 
        ],
        columnDefs: [
            {
                render: function (data, type, full, meta) {
                    if (data) {
                            // Get the dynamic alt text from the `company_logo` field
                            let altText = full.title || "Title"; // Default to a generic alt if the title is missing
                            let imageUrl = full.package_image; // already full S3 URL
                            
                            // Provide fallback image path (e.g. a local asset or another hosted image)
                            let fallbackUrl = "/images/service-1.jpg"; // Change this path as needed

                            return `<div style="width: 120px; height: 80px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <img src="${imageUrl}" 
                                            alt="${altText}" 
                                            class="img-thumbnail" 
                                            style="max-height: 80px;" 
                                            onerror="this.onerror=null;this.src='${fallbackUrl}';">
                                    </div>`;
                    }

                    return `<div style="width: 120px; height: 80px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                               <img src="/images/services-1.jpg" alt="No Image" class="img-thumbnail" style="max-height: 150px;">
                            </div>`;
                },
                targets: 5 // This should match the index of `schedule image`
            },
        ]	 
    });


    /**
     * Click Button to Create New Package
     */
    $('#createNewPackage').click(function () {

        optModal.modal('show');
     
        //lets clear the error notifications first if any
        clearFormErrors("#packageForm");

        $(".print-error-msg").css('display','none');
        $('#modalPackagesHeading').html(TRANSLATIONS.addNewPackage);

        $('#saveBtnPackage').val("create-package");
        $('#saveBtnPackageName').html(TRANSLATIONS.addPackage);
        $('#singlePreview').attr("src", "").hide();
        $('#packageForm').trigger("reset");

        // Explicitly clear hidden fields
        $('#packageForm').find('input:hidden').val('');

        $('#sessions_total').val(1);
        $('#validity_quantity').val(1);
       
        im.mask('#amount');
        im.mask('#tax');
        im.mask('#total_amount');

        //lets initiliaze code to null so as to allow loading all country data
        var code = null; // load all
        var currency = 'AED'; //set default but user can change

        var selectcurrencyOption = $('#currency');
        selectcurrencyOption.empty().trigger('change');
        populateSelect(selectcurrencyOption, optModal, 2, 2, TRANSLATIONS.choose, true, '/'+locale+'/currencies/search/'+code, 
                                '/'+locale+'/currencies/'+currency+'/edit', false, false, 'currencies', '');


    });


    /**
     * Store Package Code
     */
    $('#saveBtnPackage').click(function (e) {
        e.preventDefault();
        //
        var $btn = $(this);
        var $spinner = $btn.find('.fa-spinner');

        // Show spinner and disable the button
        $spinner.show();
        $btn.prop('disabled', true);

        //lets clear the error notifications first if any
         clearFormErrors("#packageForm");

        let fileInput = $('#singleImageInput')[0]; // Get the file input element
        let file = fileInput.files[0];  // Get the first file from the file input

        // serialize form input into array and assign to formPackageData
        var formPackageData = $('#packageForm').serializeArray();

        var fd = new FormData();

        // append logo
        fd.append('image',file);


        // append data from formPackageData
        formPackageData.forEach(function (field) {
            fd.append(field.name, field.value);
        });

      
        $.ajax({
            data: fd,
            url: "/"+locale+"/packages",
            type: "POST",
            dataType: 'json',
            processData: false,  // ✅ Prevent jQuery from serializing the FormData
            contentType: false,  // ✅ Prevent jQuery from setting content type
            success: function (data) {

                if(typeof(data.success) == 'undefined'){
                    //the session has expired
                    optModal.modal('hide');
                    $('#sessionexpiredModalTitle').html("Session Expired");
                    $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                    $('#sessionexpiredModal').modal('show');
    
                }else{
                    $spinner.hide();
                    $btn.prop('disabled', false);
                    $('#packageForm').trigger("reset");
                    optModal.modal('hide');
                    tablePackages.draw();
                    $('#notification-success').show();
                    $('#notification-success-message').html(data.success);
                    setTimeout(function(){$("#notification-success").hide()}, 4000);
                }     
            
            },
            error: function (data) {
                // check for error 500
                if(data.status == 500){
                    //show the error mostly error 500
                    $("#package-modal-warning").css('display','block');
                    $("#package-modal-warning").html("<strong>"+data.responseJSON.error+"</strong>");
                    $spinner.hide();
                    $btn.prop('disabled', false);

                }else{
                    // other errors
                    if(typeof(data.responseJSON) == 'undefined' || typeof(data.responseJSON) == null){
                        //the session has expired
                        optModal.modal('hide');
                        $('#sessionexpiredModalTitle').html("Session Expired");
                        $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                        $('#sessionexpiredModal').modal('show');
            
                    }else{
                        //show errors
                        printErrorMsg(data.responseJSON.errors);
                        $spinner.hide();
                        $btn.prop('disabled', false);

                    }
                }
                
            }
        });
    });


    /**
     * Click Button to Edit Package
     */
    $('body').on('click', '.editPackage', function () {
            var package_id = $(this).data('id');
            $.get("/"+locale+"/packages" +'/' + package_id +'/edit', function (data) {

                if(!Object.keys(data).length){
                    //no data found record flagged as deleted;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.editPackage);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');

                } else if (data.deleted_at !== null) {
                    // record is soft deleted
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.editPackage);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');

                }else{
                    //
                    if(typeof(data.id) == 'undefined'){
                        //the session has expired
                        optModal.modal('hide');

                        $('#sessionexpiredModalTitle').html("Session Expired");
                        $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                        $('#sessionexpiredModal').modal('show');

                    }else{

                        optModal.modal('show');

                        //lets clear the error notifications first if any
                         clearFormErrors("#packageForm");

                        $(".print-error-msg").css('display','none');
                        $('#modalPackagesHeading').html(TRANSLATIONS.editPackage);
                        $('#saveBtnPackage').val("edit-package");
                        $('#saveBtnPackageName').html(TRANSLATIONS.updatePackage);
                        
                        $('#package_id').val(data.id);
                        $('#created_by_code').val(data.created_by);

                        $('#package').val(data.package);
                        $('#description').val(data.description);
                        $('#sessions_total').val(data.sessions_total);
                        $('#validity_quantity').val(data.validity_quantity);
                        $('#validity_unit').val(data.validity_unit);

                         //lets initiliaze code to null so as to allow loading all country data
                        var code = null; // load all
                        var currency = data.currency; //set default but user can change

                        var selectcurrencyOption = $('#currency');
                        selectcurrencyOption.empty().trigger('change');
                        populateSelect(selectcurrencyOption, optModal, 2, 2, TRANSLATIONS.choose, true, '/'+locale+'/currencies/search/'+code, 
                                                '/'+locale+'/currencies/'+currency+'/edit', false, false, 'currencies', '');

                        $('#amount').val(data.amount);
                        $('#tax').val(data.tax);
                        $('#total_amount').val(data.total_amount);

                        $('#tax_type').val(data.tax_type);

                        im.mask('#amount');
                        im.mask('#tax');
                        im.mask('#total_amount');

                        // **Preload Image in Preview**
                        if (data.package_image) {
                            //set imageUrl 
                            // we are now using images uploaded on s3
                            let imageUrl = data.package_image;
                            let fallbackImage = "/images/service-1.jpg"; // Change this path as needed

                            imageUrl ? imageUrl : fallbackImage;
                            $("#singlePreview").attr("src", imageUrl).show();

                        } else {
                            //hide preview
                            $("#singlePreview").hide(); // Hide preview if no image

                        }
  
                    }     
                }
                
            }).fail(function (xhr, status, error) {
                let errorMessage = "Failed to fetch package data.";

                try {
                    let response = JSON.parse(xhr.responseText);

                    if (response.error) {
                        errorMessage = response.error;
                    }
                } catch (e) {}

                    //error;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.error);
                    $('#lockedcontentModalContent').html(errorMessage);
                    $('#lockedcontentModal').modal('show');


            });
    });


    /**
     * Show Package Delete/Deactivate Modal
     */
    $('body').on('click', '.deletePackage', function () {
        var package_id = $(this).data("id");
            $.get("/"+locale+"/packages" +'/' + package_id +'/edit', function (data) {

                if(!Object.keys(data).length){
                    //no data found record flagged as deleted;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.deactivatePackage);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');

                } else if (data.deleted_at !== null) {
                    // record is soft deleted
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.deactivatePackage);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');
          
                }else{

                    if(typeof(data.id) == 'undefined'){
                        //the session has expired
                        $('#sessionexpiredModalTitle').html("Session Expired");
                        $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                        $('#sessionexpiredModal').modal('show');
          
                    }else{

                        $('#deletePackageModal').modal('show');
                        $(".print-error-msg").css('display','none');
                        $('#deletePackageModalTitle').html(TRANSLATIONS.deactivatePackage);

                        const content = `${TRANSLATIONS.confirmDeactivate} <strong>${data.package}</strong>?`;

                        $('#del_package_id').val(data.id);
                        $('#deletePackageModalContent').html(content);

                    }

                }

            }).fail(function (xhr, status, error) {
                let errorMessage = "Failed to fetch package data.";

                try {
                    let response = JSON.parse(xhr.responseText);

                    if (response.error) {
                        errorMessage = response.error;
                    }
                } catch (e) {}
                
                    //error;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.error);
                    $('#lockedcontentModalContent').html(errorMessage);
                    $('#lockedcontentModal').modal('show');


            });
    });



    /**
     * Delete Deactivate Package
     */
    $('#deleteBtnPackage').click(function (e) {
        e.preventDefault();
        //
        var $btn = $(this);
        var $spinner = $btn.find('.fa-spinner');

        // Show spinner and disable the button
        $spinner.show();
        $btn.prop('disabled', true);

        var package_id = $('#del_package_id').val();
        //you selected the OK button
        $.ajax({
            type: "DELETE",
            url:"/"+locale+"/packages/"+package_id,
            success: function (data) {

                if(typeof(data.success) == 'undefined'){
                    //the session has expired
                    $('#deletePackageModal').modal('hide');
                    $('#sessionexpiredModalTitle').html("Session Expired");
                    $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                    $('#sessionexpiredModal').modal('show');

                }else{
                    //
                    $spinner.hide();
                    $btn.prop('disabled', false);
                    $('#deletePackageModal').modal('hide');
                    tablePackages.draw();
                    $('#notification-success').show();
                    $('#notification-success-message').html(data.success);
                    setTimeout(function(){$("#notification-success").hide()}, 4000);
                }
                    
            },
            error: function (data) {
                // check for error 500
                if(data.status == 500){
                    //show the error mostly error 500
                    $("#delete-warning").css('display','block');
                    $("#delete-warning").html("<strong>"+data.responseJSON.error+"</strong>");
                    $spinner.hide();
                    $btn.prop('disabled', false);

                }else{

                    //
                    if(typeof(data.responseJSON) == 'undefined' || typeof(data.responseJSON) == null){
                        //the session has expired
                        $('#deletePackageModal').modal('hide');
                        $('#sessionexpiredModalTitle').html("Session Expired");
                        $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                        $('#sessionexpiredModal').modal('show');
        
                    }else{
                        //
                        printErrorMsg(data.responseJSON.errors);
                        $spinner.hide();
                        $btn.prop('disabled', false);
                    }

                }          
            }
        });
    });


   
});