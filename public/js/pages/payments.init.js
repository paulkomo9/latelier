$(function () {
    let optModal = $('#paymentsModal');
    const locale = window.appLocale;

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
     * Render Payments DataTables
     */
    //let all datatable erros show only on the console/on prod set to ignore
    $.fn.dataTableExt.sErrMode = "console";

    // load all datatable 
    var payments_status_all = 0;

    var tablePayments = $('#datatable-payments')
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
        "language": buildDataTableLanguage('payments', '#all'),
        "ajax":{
            "url": "/"+locale+"/payments/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), payment_status: payment_status_all}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center" },
            { "data": "reference", "className": "vertical-center" },
            { "data": "title", "className": "vertical-center" },
            { "data": "start_date", "className": "vertical-center" },
            { "data": "start_time", "className": "vertical-center" },
            { "data": "end_time", "className": "vertical-center" },
            { "data": "booked_by", "className": "vertical-center" },
            { "data": "booking_status", "className": "vertical-center" },
            { "data": "trainer",  "className": "vertical-center" },
            { "data": "slots",  "className": "vertical-center" },
            { "data": "slots_taken",  "className": "vertical-center" },
            { "data": "location", "className": "vertical-center" },
            { "data": "booked_by", "className": "vertical-center" },
            { "data": "booked_on", "className": "vertical-center" }, 
            { "data": "attended_at", "className": "vertical-center" }, 
            { "data": "marked_by", "className": "vertical-center" }, 
            { "data": "options", "className": "vertical-center" } 
        ]
    });


    // load all datatable for logged in user
    var payment_status_all = 0;
    var myacc = 1;

    var tableMyPayments = $('#datatable-my-payments')
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
        "language": buildDataTableLanguage('payments', '#all'),
        "ajax":{
            "url": "/"+locale+"/payments/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), payment_status: payment_status_all, myacc: myacc}
        },
        "columns": [
            { "data": "reference", "className": "vertical-center" },
            { "data": "package", "className": "vertical-center" },
            { "data": "amount", "className": "vertical-center" },
            { "data": "fees", "className": "vertical-center" },
            { "data": "payment_amount", "className": "vertical-center" },
            { "data": "payment_status", "className": "vertical-center" },
            { "data": "payment_method",  "className": "vertical-center" },
            { "data": "last4", "className": "vertical-center" },
            { "data": "card_type", "className": "vertical-center" },
            { "data": "payment_start", "className": "vertical-center" }, 
            { "data": "payment_end", "className": "vertical-center" }, 
            { "data": "options", "className": "vertical-center" } 
        ]
    });

});