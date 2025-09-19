$(function () {

    let optModal = $('#schedulesModal');
    const locale = window.appLocale;
    const geoapifyApiKey = window.geoapifyApiKey;

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
     * function to initialize location autocomplete geo apify
     */
    function initialize_geo_apify() {

        const autocompleteInput = document.getElementById('autocomplete');

        const ac = new autocomplete.GeocoderAutocomplete(autocompleteInput, geoapifyApiKey, {
            placeholder: TRANSLATIONS.search,
            debounceDelay: 50,
            minLength: 1,
            lang:locale
        });

        ac.on('select', (value) => {
            if (value && value.properties) {
                $('#location_latitude').val(value.properties.lat);
                $('#location_longitude').val(value.properties.lon);
                //$('#location_city').val(value.properties.city ?? value.properties.state);
                //$('#location_province').val(value.properties.state);
                $('#location_address').val(value.properties.formatted);
                $('#location_timezone').val(value.properties.timezone.name);
            }
        });
    }

    //initialize location autocomplete 
    initialize_geo_apify();

    /**
     * clear inputs when user clear autocomplete input
     */
    $('.geoapify-close-button').on('click', function () {
        // clear inputs
        $('#location_latitude').val('');
        $('#location_longitude').val('');
        /*$('#location_city').val('');
        $('#location_province').val('');*/
        $('#location_address').val('');

    });

     /**
     * Activate (edit mode)
     *
     */
    function activateCloseButton() {
        $('.geoapify-close-button').addClass('visible');
    }


    /**
     * Deactivate (non-edit mode)
     *
     */
    function deactivateCloseButton() {
        $('.geoapify-close-button').removeClass('visible');
    }

    /**
     * Lets have a function to calculate total amount
     */
    function calculateTotals() {
         // ✅ Always pull from masked OR hidden fallback
        let amount   = getNumericValue($('#amount'), $('#amount_'), maskOpts);
        let service_fee = getNumericValue($('#service_fee'), $('#service_fee_'), maskOpts);

        const totalAmount = amount + service_fee;
        $('#total_amount').val((totalAmount).toFixed(2));

        var im = new Inputmask({
            alias: "decimal",
            groupSeparator: ",",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            prefix: "",
            placeholder: "0",
            min: 0,                // block below zero
            allowMinus: false,     // block minus sign
        });

        im.mask('#total_amount');
    }

    /**
     * delegate events to call calculate totals
     */
    $(document).on('input', '#amount, #service_fee', function(){
        //calculate totals
        calculateTotals();
    });

    $(document).on('blur', '#amount, #service_fee', function(){
        //calculate totals
        calculateTotals();
    });


    /**
     * Render Schedules DataTables
     */
    //let all datatable erros show only on the console/on prod set to ignore
    $.fn.dataTableExt.sErrMode = "console";

    // load all datatable 
    var schedule_status_all = 0;

    var tableSchedules = $('#datatable-schedules')
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
        "language": buildDataTableLanguage('schedules', '#all'),
        "ajax":{
            "url": "/"+locale+"/schedules/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), schedule_status: schedule_status_all}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center" },
            { "data": "title", "className": "vertical-center" },
            { "data": "start_date_time", "className": "vertical-center" },
            { "data": "end_date_time", "className": "vertical-center" },
            { "data": "schedule_status", "className": "text-center vertical-center" },
            { "data": "schedule_image"},
            { "data": "estimated_time",  "className": "vertical-center" },
            { "data": "slots",  "className": "vertical-center" },
            /*{ "data": "slots_taken",  "className": "vertical-center" },*/
            { "data": "trainer",  "className": "vertical-center" },
            { "data": "recurring_status", "className": "vertical-center" },
            { "data": "location", "className": "vertical-center" },
            { "data": "description", "className": "text-wrap vertical-center" }, 
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
                            let imageUrl = full.schedule_image; // already full S3 URL
                            
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
     * Click Button to Create New Schedule
     */
    $('#createNewSchedule').click(function () {

        optModal.modal('show');
     
        //lets clear the error notifications first if any
        clearFormErrors("#scheduleForm");

        $(".print-error-msg").css('display','none');
        $('#modalSchedulesHeading').html(TRANSLATIONS.addNewSchedule);

        $('#saveBtnSchedule').val("create-schedule");
        $('#saveBtnScheduleName').html(TRANSLATIONS.addSchedule);
         $('#singlePreview').attr("src", "").hide();
        $('#scheduleForm').trigger("reset");

        // Explicitly clear hidden fields
        $('#scheduleForm').find('input:hidden').val('');

        // deactivate close button near search/ autocomplete
        deactivateCloseButton();

        $('#slots').val(8);

        var im = new Inputmask({
            alias: "decimal",
            groupSeparator: ",",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            prefix: "",
            placeholder: "0.00"
        });

        im.mask('#amount');
        im.mask('#service_fee');
        im.mask('#total_amount');

        // lets set values 5,2 for status 
        var status = [5,6];
        var selectrecurringstatusOption = $('#recurring_status');
        selectrecurringstatusOption.empty().trigger('change');
        loadSelect(selectrecurringstatusOption, optModal, 'Infinity', -1, TRANSLATIONS.choose, true, '/'+locale+'/workflow-status/search/'+status, false, false, 'status');

        //lets initiliaze code to null so as to allow loading all country data
        var code = null; // load all
        var currency = 'AED'; //set default but user can change

        var selectpaymentcurrencyOption = $('#payment_currency');
        selectpaymentcurrencyOption.empty().trigger('change');
        populateSelect(selectpaymentcurrencyOption, optModal, 2, 2, TRANSLATIONS.choose, true, '/'+locale+'/currencies/search/'+code, 
                                '/'+locale+'/currencies/'+currency+'/edit', false, false, 'currencies', '');



        // lets set values 0 for tid to load all trainers 
        var tid = 0;
        var selecttraineridOption = $('#trainer_id');
        selecttraineridOption.empty().trigger('change');
        loadSelect(selecttraineridOption, optModal, 2, 2, TRANSLATIONS.choose, true, '/'+locale+'/trainers/search/'+tid, false, false, 'trainers');

    });


    /**
     * Store Schedule Code
     */
    $('#saveBtnSchedule').click(function (e) {
        e.preventDefault();
        //
        var $btn = $(this);
        var $spinner = $btn.find('.fa-spinner');

        // Show spinner and disable the button
        $spinner.show();
        $btn.prop('disabled', true);

        //lets clear the error notifications first if any
         clearFormErrors("#scheduleForm");

        let fileInput = $('#singleImageInput')[0]; // Get the file input element
        let file = fileInput.files[0];  // Get the first file from the file input

        // serialize form input into array and assign to formScheduleData
        var formScheduleData = $('#scheduleForm').serializeArray();

        var fd = new FormData();

        // append logo
        fd.append('image',file);

         // append data from formScheduleData
        formScheduleData.forEach(function (field) {
            fd.append(field.name, field.value);
        });
      
        $.ajax({
            data: fd,
            url: "/"+locale+"/schedules",
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
                    $('#scheduleForm').trigger("reset");
                    optModal.modal('hide');
                    tableSchedules.draw();
                    $('#notification-success').show();
                    $('#notification-success-message').html(data.success);
                    setTimeout(function(){$("#notification-success").hide()}, 4000);
                }     
            
            },
            error: function (data) {
                // check for error 500
                if(data.status == 500){
                    //show the error mostly error 500
                    $("#schedule-modal-warning").css('display','block');
                    $("#schedule-modal-warning").html("<strong>"+data.responseJSON.error+"</strong>");
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
     * Click Button to Edit Schedule
     */
    $('body').on('click', '.editSchedule', function () {
            var schedule_id = $(this).data('id');
            $.get("/"+locale+"/schedules" +'/' + schedule_id +'/edit', function (data) {

                if(!Object.keys(data).length){
                    //no data found record flagged as deleted;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.editSchedule);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');

                } else if (data.deleted_at !== null) {
                    // record is soft deleted
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.editSchedule);
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
                         clearFormErrors("#scheduleForm");

                        $(".print-error-msg").css('display','none');
                        $('#modalSchedulesHeading').html(TRANSLATIONS.editSchedule);
                        $('#saveBtnSchedule').val("edit-schedule");
                        $('#saveBtnScheduleName').html(TRANSLATIONS.updateSchedule);
                        
                        $('#schedule_id').val(data.id);
                        $('#location_latitude').val(data.location_latitude);
                        $('#location_longitude').val(data.location_longitude);
                        $('#location_timezone').val(data.location_timezone);
                        $('.geoapify-autocomplete-input').val(data.location);
                        $('#location_address').val(data.location);
                        $('#created_by_code').val(data.created_by);

                        $('#title').val(data.title);
                        $('#description').val(data.description);
                        $('#slots').val(data.slots);

                        // Assuming you're getting full datetime as a string
                        const startDateTime = data.start_date_time;

                        // Split into [ '2025-09-06', '07:00:00' ]
                        const [datePart, timePart] = startDateTime.split(' ');

                        // Remove seconds from time (for input[type=time] compatibility)
                        const timeWithoutSeconds = timePart.substring(0, 5);

                        // Populate the inputs
                        $('#starts_date').val(datePart);
                        $('#start_time').val(timeWithoutSeconds);

                        // for the ends date and end time
                        const endDateTime = data.end_date_time;
                        const [endDatePart, endTimePart] = endDateTime.split(' ');
                        $('#ends_date').val(endDatePart);
                        $('#end_time').val(endTimePart.substring(0, 5));


                        // activate close button near search/ autocomplete
                        activateCloseButton();

                        // lets set values 5,6 for status to get yes and no data
                        var status = [5,6];
                        var selectrecurringstatusOption = $('#recurring_status');
                        selectrecurringstatusOption.empty().trigger('change');
                        populateSelect(selectrecurringstatusOption, optModal, 'Infinity', -1, TRANSLATIONS.choose, true, '/'+locale+'/workflow-status/search/'+status, 
                                '/'+locale+'/workflow-status/'+data.recurring_status+'/edit', false, false, 'status', '');


                        // lets set values 0 for tid to get all trainers data
                        var tid = 0;
                        var selecttrainerOption = $('#trainer_id');
                        selecttrainerOption.empty().trigger('change');
                        populateSelect(selecttrainerOption, optModal, 2, 2, TRANSLATIONS.choose, true, '/'+locale+'/trainers/search/'+tid, 
                                '/'+locale+'/trainers/'+data.trainer_id+'/edit', false, false, 'trainers', '');


                        // **Preload Image in Preview**
                        if (data.schedule_image) {
                            //set imageUrl 
                            // we are now using images uploaded on s3
                            let imageUrl = data.schedule_image;
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
                let errorMessage = "Failed to fetch schedule data.";

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
     * Show Schedule Delete/Deactivate Modal
     */
    $('body').on('click', '.deleteSchedule', function () {
        var schedule_id = $(this).data("id");
            $.get("/"+locale+"/schedules" +'/' + schedule_id +'/edit', function (data) {

                if(!Object.keys(data).length){
                    //no data found record flagged as deleted;
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.deactivateSchedule);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');

                } else if (data.deleted_at !== null) {
                    // record is soft deleted
                    $('#lockedcontentModalTitle').html(TRANSLATIONS.deactivateSchedule);
                    $('#lockedcontentModalContent').html(TRANSLATIONS.recordLocked);
                    $('#lockedcontentModal').modal('show');
          
                }else{

                    if(typeof(data.id) == 'undefined'){
                        //the session has expired
                        $('#sessionexpiredModalTitle').html("Session Expired");
                        $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                        $('#sessionexpiredModal').modal('show');
          
                    }else{

                        $('#deleteScheduleModal').modal('show');
                        $(".print-error-msg").css('display','none');
                        $('#deleteScheduleModalTitle').html(TRANSLATIONS.deactivateSchedule);

                        // Set the locale if needed (e.g., 'en', 'fr', 'ar', etc.)
                        moment.locale(locale); // Change 'en' to app locale if needed

                        const start = moment(data.start_date_time).format('LL [at] LT');
                        const end = moment(data.end_date_time).format('LT');

                        const content = `${TRANSLATIONS.confirmDeactivate} <strong>${data.title}</strong> (${start} - ${end})?`;



                        $('#del_schedule_id').val(data.id);
                        $('#deleteScheduleModalContent').html(content);

                    }

                }

            }).fail(function (xhr, status, error) {
                let errorMessage = "Failed to fetch schedule data.";

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
     * Delete Deactivate Schedule
     */
    $('#deleteBtnSchedule').click(function (e) {
        e.preventDefault();
        //
        var $btn = $(this);
        var $spinner = $btn.find('.fa-spinner');

        // Show spinner and disable the button
        $spinner.show();
        $btn.prop('disabled', true);

        var schedule_id = $('#del_schedule_id').val();
        //you selected the OK button
        $.ajax({
            type: "DELETE",
            url:"/"+locale+"/schedules/"+schedule_id,
            success: function (data) {

                if(typeof(data.success) == 'undefined'){
                    //the session has expired
                    $('#deleteScheduleModal').modal('hide');
                    $('#sessionexpiredModalTitle').html("Session Expired");
                    $('#sessionexpiredModalContent').html("Your session has expired. To continue accessing our platform, please click the Login button below to log in again.");
                    $('#sessionexpiredModal').modal('show');

                }else{
                    //
                    $spinner.hide();
                    $btn.prop('disabled', false);
                    $('#deleteScheduleModal').modal('hide');
                    tableSchedules.draw();
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
                        $('#deleteScheduleModal').modal('hide');
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