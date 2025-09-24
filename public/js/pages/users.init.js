$(function () {
    let optModal = $('#usersModal');
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
    var user_status_all = 0;

    var tableUsers = $('#datatable-users')
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
        "language": buildDataTableLanguage('users', '#all'),
        "ajax":{
            "url": "/"+locale+"/users/list",
            "dataType": "json",
            "type": "POST",
            "data":{ _token:  $('meta[name="csrf-token"]').attr('content'), user_status: user_status_all}
        },
        "columns": [
            { "data": "id", "className": "text-center vertical-center"},
            { "data": "employee_photo", "className": "text-center vertical-center"},
            { "data": "name", "className": "vertical-center" },
            { "data": "email", "className": "vertical-center" },
            { "data": "user_status", "className": "text-center  vertical-center" },
            { "data": "is_client", "className": "text-center  vertical-center" },
            { "data": "is_trainer", "className": "text-center  vertical-center" },
            { "data": "is_admin", "className": "text-center  vertical-center" },
            { "data": "online_status", "className": "text-center  vertical-center" },
            { "data": "created_at", "className": "vertical-center" }, 
            { "data": "updated_at", "className": "vertical-center" }, 
            { "data": "options", "className": "vertical-center" } 
        ],
         columnDefs: [
            {
                render: function (data, type, full, meta) {
                    
                    // Check if a photo path (S3 key) exists and is not just empty spaces
                    const hasAvatar = data && data.trim() !== "";

                    // Safely get the full name string from the current row/full (e.g., "Jane Ann Doe")
                    const name = full.name ?? '';

                    // Split the name by whitespace, remove empty chunks, and grab the first letter of each word
                    const words = name.trim().split(/\s+/); // supports optional middle names
                    let initials = words
                        .map(w => w.charAt(0).toUpperCase()) // take uppercase of first letter
                        .slice(0, 2)                         // limit to first two initials
                        .join('');

                    // If an avatar exists, construct the full image URL using the public S3 base path
                    if (hasAvatar) {
                        const imgSrc = data; // assumes global S3_BASE_URL is declared in layout
                        return `<img class="rounded-circle header-profile-user" src="${imgSrc}" alt="Avatar" style="width:60px; height:60px;">`;
                    }

                    // If no avatar, return a styled circle div with the initials instead
                    return `<div class="rounded-circle header-profile-user d-flex align-items-center justify-content-center bg-secondary text-white fw-bold me-2"
                                    style="width: 60px; height: 60px; font-size: 14px; font-weight: 700;">
                                ${initials}
                            </div>`
                
                },
                targets: 1// This should match the index of `profile_pic`
            },    
        ]	 	 	 
    });

});