$(function () {
    let optModal = $('#subscriptionsModal');
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
     * Render Subscriptions DataTables
     */
    //let all datatable erros show only on the console/on prod set to ignore
    $.fn.dataTableExt.sErrMode = "console";
    var groupColumn = 1;

    // load all datatable 
    var subscription_status_all = 0;

    var tableSubscriptions = $('#datatable-subscriptions')
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
        "language": buildDataTableLanguage('subscriptions', '#all'),
        "ajax":{
            "url": "/"+locale+"/subscriptions/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), subscription_status: subscription_status_all}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center" },
            { "data": "member_name", "className": "vertical-center" },
            { "data": "package", "className": "vertical-center" },
            { "data": "subscription_status", "className": "vertical-center" },
            { "data": "sessions_total", "className": "vertical-center" },
            { "data": "sessions_remaining", "className": "vertical-center" },
            { "data": "validity", "className": "vertical-center" },
            { "data": "purchased_on", "className": "vertical-center" },
            { "data": "expires_at",  "className": "vertical-center" },
            { "data": "purchased_by",  "className": "vertical-center" },
            { "data": "notes",  "className": "vertical-center" },
            { "data": "options", "className": "vertical-center" } 
        ],
         columnDefs: [
            { visible: false, targets: groupColumn }
        ],
        order: [[groupColumn, 'asc']],
        displayLength: 25,
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
         
                api.column(groupColumn, { page: 'current' })
                    .data()
                    .each(function (group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before(
                                    '<tr class="group"><td colspan="8">' +
                                        group +
                                        '</td></tr>'
                                );
         
                            last = group;
                        }
                    });
            }
    });

});