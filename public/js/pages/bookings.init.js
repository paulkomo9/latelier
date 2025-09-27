$(function () {
    let optModal = $('#bookingsModal');
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
     * Render Bookings DataTables
     */
    //let all datatable erros show only on the console/on prod set to ignore
    $.fn.dataTableExt.sErrMode = "console";

    // load all datatable 
    var booking_status_all = 0;
    var myacc = 0;

    var tableBookings = $('#datatable-bookings')
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
        "language": buildDataTableLanguage('bookings', '#all'),
        "ajax":{
            "url": "/"+locale+"/bookings/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), booking_status: booking_status_all, myacc: myacc}
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
    var booking_status_all = 0;
    var myacc = 1;

    var tableMyBookings = $('#datatable-my-bookings')
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
        "language": buildDataTableLanguage('bookings', '#all'),
        "ajax":{
            "url": "/"+locale+"/bookings/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), booking_status: booking_status_all, myacc:myacc}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center" },
            { "data": "reference", "className": "vertical-center" },
            { "data": "title", "className": "vertical-center" },
            { "data": "start_date", "className": "vertical-center" },
            { "data": "start_time", "className": "vertical-center" },
            { "data": "end_time", "className": "vertical-center" },
            { "data": "booking_status", "className": "vertical-center" },
            { "data": "trainer",  "className": "vertical-center" },
            { "data": "location", "className": "vertical-center" },
            { "data": "booked_on", "className": "vertical-center" }, 
            { "data": "attended_at", "className": "vertical-center" }, 
            { "data": "marked_by", "className": "vertical-center" }, 
            { "data": "options", "className": "vertical-center" } 
        ]
    });

});