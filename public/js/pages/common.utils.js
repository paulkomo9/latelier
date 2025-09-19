
        /*------------------------------------------
          -------------------------------------------
          lets always ensure notification-success, 
          notification-warning are hidden 
          -------------------------------------------
          -------------------------------------------
        */
        $('#notification-success').hide();
        $('#notification-warning').hide();


        /*------------------------------------------
           --------------------------------------------
            Lets initiate selects params selectOption
           ---------------------------------------------
           ---------------------------------------------
        */
        function initiateSelect(selectOption, minimumInputLength, optModal, minimumResultsForSearch, placeholder, allowClear, ismulti){
                var select = selectOption.select2({
                    minimumInputLength: minimumInputLength,
                    dropdownParent: optModal,
                    minimumResultsForSearch: minimumResultsForSearch,
                    placeholder: placeholder,
                    dir: $('html').attr('dir') === 'rtl' ? 'rtl' : 'ltr',
                    language: buildSelect2Language(),
                    allowClear: allowClear,
                    multiple:ismulti,
                }); 

              return select;
        }
        

        /*-----------------------------------------
          -------------------------------------------
            lets have a dynamic function to load
            select option param selectOption
          -------------------------------------------
          -------------------------------------------
        */
        function loadSelect(selectOption, optModal, minimumResultsForSearch, minimumInputLength, placeholder, allowClear, url, isapplicable, ismulti, table){     
                        
                var select = selectOption.select2({
                    minimumInputLength: minimumInputLength,
                    dropdownParent: optModal,
                    minimumResultsForSearch: minimumResultsForSearch,
                    placeholder: placeholder,
                    dir: $('html').attr('dir') === 'rtl' ? 'rtl' : 'ltr',
                    language: buildSelect2Language(),
                    allowClear: allowClear,
                    multiple:ismulti,
                        sorter: function(data) {
                            return data.sort(function (a, b) {

                                if (a.id > b.id) {
                                    return 1;
                                }
                                if (a.id < b.id) {
                                    return -1;
                                }
                                return 0;
                            });
                        },
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {

                                //lets add the option to select all employees only if isapplicable is true
                                if(isapplicable){

                                    showAllOptions(data, table)
                                }
                                
                                return {
                                results:  $.map(data, function (item) {

                                        let columns = getTableColumnName(item, table); 

                                        return {
                                             text: columns[0],
                                            id: columns[1]
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                });

        
            return select;
        }


        /*---------------------------------------------------
          ---------------------------------------------------
          lets have a dynamic function that can get data and 
          set preselected in one function param selectOption, url
          isapplicable, ismulti, table, select_data
          ----------------------------------------------------
          ----------------------------------------------------
        */
        function populateSelect(selectOption, optModal, minimumResultsForSearch, minimumInputLength, placeholder, allowClear, url, url_, isapplicable, ismulti, table, select_data = null){
            // Set up the Select2 control
            var select = selectOption.select2({
                minimumInputLength: minimumInputLength,
                dropdownParent: optModal,
                minimumResultsForSearch: minimumResultsForSearch,
                placeholder: placeholder,
                dir: $('html').attr('dir') === 'rtl' ? 'rtl' : 'ltr',
                language: buildSelect2Language(),
                allowClear: allowClear,
                multiple:ismulti,
                    sorter: function(data) {
                        return data.sort(function (a, b) {

                            if (a.id > b.id) {
                                return 1;
                            }
                            if (a.id < b.id) {
                                return -1;
                            }
                            return 0;
                        });
                    },
                ajax: {
                    url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {

                            //lets add the option to select all options only if isapplicable is true
                            if(isapplicable){
                                showAllOptions(data, table)
                            }

                            return {
                                results:  $.map(data, function (item) {

                                        let columns = getTableColumnName(item, table);
                                      
                                        return {
                                            text: columns[0],
                                            id: columns[1]
                                        }
                                    })
                                };
                            },
                            cache: true
                }
            });

           
            // Fetch the preselected item, and add to the control
            $.ajax({
                    type: 'GET',
                    url: url_
            }).then(function (data) {

                    // create the option and append to Select2
                    if (ismulti) {

                        if (select_data && Array.isArray(select_data)) {

                            let containsZero = false;

                            // Check for flat array: ["0", "1"]
                            if (select_data.every(item => typeof item === 'string' || typeof item === 'number')) {
                                containsZero = select_data.includes("0") || select_data.includes(0);
                            }

                            // Check for nested array: [["0"], ["1"]]
                            else if (select_data.every(item => Array.isArray(item))) {
                                containsZero = select_data.some(item => item[0] == 0 || item[0] == '0');
                            }

                            // If '0' is in the array, we assume it's "All" and skip the loop
                            if (containsZero) {
                                // Add the "All" option using the helper function
                                let allData = showAllOptions([], table);

                                if (allData.length > 0) {
                                    let allOptionData = allData[0]; // The object we just pushed in showAllOptions

                                    // Extract display columns
                                    let columns = getTableColumnName(allOptionData, table);

                                    let option = new Option(columns[0], '0', true, true);
                                    select.append(option).trigger('change');
                                }
                            } else {
                                // Loop through each item if no '0' is present
                                data.forEach(function (d) {
                                    let columns = getTableColumnName(d, table);
                                    let option = new Option(columns[0], columns[1], true, true);
                                    select.append(option).trigger('change');
                                });
                            }

                        } else if (data) {
                            // Handle the case where select_data is a single value
                            let columns = getTableColumnName(data, table);
                            let option = new Option(columns[0], columns[1], true, true);
                            select.append(option).trigger('change');
                        }

                    } else {
                        // Single selection mode
                        if (data) {
                            let columns = getTableColumnName(data, table);
                            let option = new Option(columns[0], columns[1], true, true);
                            select.append(option).trigger('change');
                        }
                    }
                    
                    // manually trigger the `select2:select` event
                    select.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });

            });
    
        }

        /**
         * Reset a select2 element completely
         * @param {string} selector - jQuery selector for the element
         * @param {string} optModal - modal 
         * @param {boolean} reinit - whether to re-init select2 after clearing
         */
        function resetSelect2(selector, optModal, reinit = true) {
            let $el = $(selector);

            // clear hidden value if you have a matching hidden input with "_"
            $('#' + $el.attr('id') + '_').val('');

            // clear current select2 instance (state + options)
            $el.val(null).trigger('change');
            $el.empty().trigger('change');

            if (reinit) {
                // re-init with placeholder only
                initiateSelect($el, 1, optModal, -1, TRANSLATIONS.choose, false, false);
            }
        }




        /**
         * Dynamically adds an "All" option to the provided data array based on the table type.
         * 
         * @param {Array} data - The current dataset.
         * @param {String} table - The table identifier.
         * @returns {Array} - The updated dataset with the "All" option included.
         */
        function showAllOptions(data, table) {
            switch (table) {
                case 'gender':
                    data.push({
                        id: 0,
                        gender_name: "All Gender",
                        created_at: "",
                        updated_at: "",
                        gender_name_status: 7
                    });
                    return data;

                case 'marital-status':
                    data.push({
                        id: 0,
                        marital_status_name: "All Marital Status",
                        created_at: "",
                        updated_at: "",
                        marital_status_status: 7
                    });
                    return data;

                case 'employment-types':
                    data.push({
                        id: 0,
                        employment_type_name: "All Employment Types",
                        created_at: "",
                        updated_at: "",
                        company_employment_type_status: 7
                    });
                    return data;

                case 'working-models':
                    data.push({
                        id: 0,
                        working_model_name: "All Working Models",
                        created_at: "",
                        updated_at: "",
                        company_working_model_status: 7
                    });
                    return data;

                case 'companies':
                    data.push({
                        id: 0,
                        company_name: "All Companies",
                        created_at: "",
                        updated_at: "",
                        company_status: 7
                    });
                    return data;

                case 'departments':
                    data.push({
                        id: 0,
                        department_name: "All Departments",
                        created_at: "",
                        updated_at: "",
                        department_status: 7
                    });
                    return data;

                case 'designations':
                    data.push({
                        id: 0,
                        designation_name: "All Designations",
                        created_at: "",
                        updated_at: "",
                        designation_status: 7
                    });
                    return data;

                case 'locations':
                case 'locations-countries':
                    data.push({
                        id: 0,
                        location_name: "All Locations",
                        location_city: "All Cities",
                        country: "All Countries",
                        created_at: "",
                        updated_at: "",
                        company_locations_status: 7
                    });
                    return data;

                case 'employees':
                    data.push({
                        employee_code: 0,
                        firstname: "All",
                        lastname: "Employees",
                        created_at: "",
                        updated_at: "",
                        designation_status: 7
                    });
                    return data;

                default:
                    return data;
            }
        }




        /*------------------------------------------------------
         -------------------------------------------------------
         Lets have a function to get the table column name param
         item, table
         -------------------------------------------------------
         -------------------------------------------------------
        */
        function getTableColumnName(item, table) {
            switch (table) {
                case 'languages':
                    return [item.native_name, item.code];

                case 'companies':
                    return [item.company_name, item.company_code];

                case 'leave-categories':
                    return [item.leave_category, item.id];

                case 'leave-units':
                    return [item.unit, item.id];

                case 'leave-types':
                    return [item.type, item.id];

                case 'leave-policies':
                    return [item.policy_name, item.id];

                case 'frequency':
                    return [item.frequency, item.id];

                case 'installment-types':
                    return [item.installment_name, item.id];

                case 'qualifications':
                    return [item.qualification_name, item.id];

                case 'language-proficiency':
                    return [item.language_proficiency_name, item.id];

                case 'gender':
                    return [item.gender_name, item.id];

                case 'marital-status':
                    return [item.marital_status_name, item.id];

                case 'dependents-relations':
                    return [item.relation, item.id];

                case 'employment-types':
                    return [item.employment_type_name, item.id];

                case 'employment-categories':
                    return [item.employment_cat_name, item.id];

                case 'company-assets':
                    return [item.assets_category_name, item.id];

                case 'calendar-settings':
                    return [item.calendar_setting_name, item.id];

                case 'visa-type':
                    return [item.visa_type_name, item.id];

                case 'working-models':
                    return [item.working_model_name, item.id];

                case 'departments':
                    return [item.department_name, item.id];

                case 'designations':
                    return [item.designation_name, item.id];

                case 'overtime-status':
                    return [item.ot_status_name, item.id];

                case 'locations':
                    if (item.location_name !== undefined) {
                        return [item.location_name + ' - ' + item.location_city, item.id];
                    } else {
                        return ['', ''];
                    }

                case 'countries':
                    return [item.country_name, item.country_name];

                case 'days':
                    return [item.day, item.code];
                
                case 'adjustment-responsibilities':
                    return [item.name, item.id];

                case 'adjustment-directions':
                    return [item.name, item.id];

                case 'calculation-methods':
                    return [item.name, item.id];

                case 'value-types':
                    return [item.name, item.id];

                case 'frequency-types':
                    return [item.name, item.id];

                case 'calculation-bases':
                    return [item.name, item.id];

                case 'calculation-periods':
                    return [item.name, item.id];

                case 'processing-days':
                    return [item.day, item.code];

                case 'effective-from':
                    return [item.name, item.id];

                case 'religion':
                    return [item.name, item.id];

                case 'employees':
                    if (item.firstname !== undefined) {
                        return [item.firstname + ' ' + item.lastname, item.employee_code];
                    } else {
                        return ['', ''];
                    }

                case 'currencies':
                    return [item.currency_code, item.currency_code];

                case 'industries':
                    return [item.industry_name, item.industry_name];

                case 'timezones':
                    return [item.value, item.value];

                case 'hire-source':
                    return [item.company_hire_source, item.id];

                case 'pay-types':
                    return [item.pay_type_name, item.id];

                case 'holiday-types':
                    return [item.holiday_type_name, item.id];

                case 'shift-types':
                    return [item.shift_type_name, item.id];

                case 'company-shifts':
                    return [item.shift_name, item.id];

                case 'change-reasons':
                    return [item.change_reason_name, item.id];

                case 'payment-methods':
                    return [item.payment_method_name, item.id];

                case 'pay-schedules':
                    return [item.schedule_name, item.id];

                case 'pay-schedules-currency':
                    return [item.schedule_currency, item.schedule_currency];

                case 'benefit-deduction-types':
                    return [item.type_name, item.id];

                case 'tiering-models':
                    return [item.tiering_model_name, item.id];

                case 'benefits-deductions':
                    if (item.bene_deduct_name !== undefined) {
                        return [item.bene_deduct_name, item.id];
                    } else {
                        return ['', ''];
                    }

                case 'benefits-deductions-groups':
                    if (item.group_name !== undefined) {
                        return [item.group_code + ' - ' + item.group_name, item.id];
                    } else {
                        return ['', ''];
                    }

                case 'status':
                    return [item.status_name, item.id];

                case 'coverage-tiers':
                    return [item.coverage_tier_name, item.id];

                case 'eligibility-wait-period':
                    return [item.eligibility_wait_period_name, item.id];

                case 'eligibility':
                    return [item.eligibility_name, item.id];

                case 'travel-requests':
                    return [item.place_of_visit, item.id];

                case 'leave-balances':
                    return [item.policy_name, item.id];

                case 'modules':
                    return [item.module_name, item.id];

                case 'roles':
                    return [item.role_name, item.id];

                case 'users':
                    if (item.firstname !== undefined) {
                        return [item.firstname + ' ' + item.lastname, item.id];
                    } else {
                        return ['', ''];
                    }

                case 'trainers':
                    if (item.firstname !== undefined) {
                        return [item.firstname + ' ' + item.lastname, item.id];
                    } else {
                        return ['', ''];
                    }


                case 'pay-reports':
                    if (item.pay_report_name !== undefined) {
                        return [item.pay_report_name + ' - ' + item.pay_report_ref, item.id];
                    } else {
                        return ['', ''];
                    }

                default:
                    return ['', ''];
            }
        }



        /*------------------------------------------
         --------------------------------------------
            Lets initiate look up from lookup trait 
            select and populate
         ---------------------------------------------
         ---------------------------------------------
        */
        function populateLookup(selectOption, optModal, minimumResultsForSearch, minimumInputLength, placeholder, allowClear, url,  lookup, isapplicable, ismulti, selectdata){
            var lookup_select = selectOption.select2({
                minimumResultsForSearch: minimumResultsForSearch,
                minimumInputLength: minimumInputLength,
                dropdownParent: optModal,
                placeholder: placeholder,
                allowClear: allowClear
            });

            $.ajax({
                    type: 'GET',
                    data:{lookup:lookup},
                    url: url
            }).then(function (data){

                    data.forEach(function(d) {
                                var option = new Option(d.name, d.id, true, true);
                                lookup_select.append(option).trigger('change');
                    });

                    //manually trigger the `select2:select` event
                    lookup_select.trigger({
                         type: 'select2:select',
                         params: {
                            data: data
                         }
                    });

                if(ismulti){
                    lookup_select.val(selectdata).trigger('change');   
                } 

                      
            });        
        }

    

        /*---------------------------------------------------------
          ---------------------------------------------------------
          Check if a string is a valid numeric number
          ---------------------------------------------------------
          ---------------------------------------------------------
        */
        function isNumeric(n){
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        /**
         * Reads a numeric value safely.
         * Priority:
         *  1. Try to unmask visible field (#employee_pays, #employer_pays, etc.)
         *  2. If empty → fallback to hidden raw field (#employee_pays_raw, etc.)
         */
        function getNumericValue($field, $hidden, opts) {
            let val = Inputmask.unmask($field.val(), opts);
            if (val && !isNaN(val)) return parseFloat(val);
            val = $hidden.val();
            return parseFloat(val || 0);
        }



        /*------------------------------------------------------
          ------------------------------------------------------
          lets have a function to handle the carousel items
          ------------------------------------------------------
          ------------------------------------------------------
        */
        function itemsCarouselDisplay(num){

            let items = document.querySelectorAll('.carousel .carousel-item');

            items.forEach((el) => {
                const minPerSlide = num
                let next = el.nextElementSibling
                for (var i=1; i<minPerSlide; i++) {
                    if (!next) {
                        // wrap carousel by using first child
                        next = items[0]
                    }
                    let cloneChild = next.cloneNode(true)
                    el.appendChild(cloneChild.children[0])
                    next = next.nextElementSibling
                }
            });

        }


        /*---------------------------------------------------------
        -----------------------------------------------------------
        lets have a function to get employee compensation  param
        pay_type_id, employee_code
        -----------------------------------------------------------
        ----------------------------------------------------------- 
        */
        function getEmployeeCompensation(pay_type_id, employee_code){
            var compensation_data;

                if(pay_type_id){
                    $.ajax({
                        url: '/compensation/search/'+ pay_type_id +'/'+employee_code,
                        method: 'get',
                        dataType: 'json',
                        async: false,
                        contentType: 'application/json',
                            success: function (data) {
                                compensation_data = data;
                            },
                            error: function (ex) {
        
                            }
                    });
                } 

          return compensation_data;
        }



        /*------------------------------------------
         --------------------------------------------
            Lets have a function to print errors
         --------------------------------------------
        --------------------------------------------
        */
        function printErrorMsg (msg) {
              //      
              $.each( msg, function( key, value ) {
                  $("#"+key+"").addClass("is-invalid");
                  $("#"+key+"_alert").css('display','block');
                  $("#"+key+"_alert").html("<strong>"+value+"</strong>");
              });

        }


        /**
         * handleSingleUpload
         * @param {*} file 
         * @returns 
         */
        function handleSingleUpload(file, preview) {
            if (!file) return; 

            let reader = new FileReader();

            reader.onload = function (e) {
                if (preview.length) {
                    preview.attr("src", e.target.result).show();
                }
            };

            reader.readAsDataURL(file);
        }



        /**
         * handleDocumentUpload
         * @param {*} file 
         * @returns 
         */
        function handleDocumentUpload(file, previewContainer, previewFrame, filenameContainer) {
            if (!file) return;
        
            let reader = new FileReader();
            let fileType = file.type;
        
            if (fileType === "application/pdf") {
                reader.onload = function (e) {
                    previewFrame.attr("src", e.target.result).show();
                    previewContainer.show();
                    filenameContainer.hide();
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.hide();
                filenameContainer.text(file.name).show();
            }
        }




        /**
         * pre load document
         */
        function preloadDocument(previewFrame, previewContainer, filenameContainer, fileUrl) {
            if (!fileUrl) return;
        
            let fileType = fileUrl.split('.').pop().toLowerCase();

            console.log(fileUrl);
            
            if (fileType === "pdf") {
                previewFrame.attr("src", fileUrl).show();
                previewContainer.show();
                filenameContainer.hide();
            } else {
                previewContainer.hide();
                filenameContainer.text(fileUrl.split('/').pop()).show();
            }
        }


        

        /**
         * handleMultipleUploads - Stores files in memory and updates preview.
         * @param {FileList} files
         */
        function handleMultipleUploads(files, selectedFiles) {
            if (!files || files.length === 0) return;

            let previewContainer = $("#multiImagePreviewContainer");

            Array.from(files).forEach((file) => {
                if (!file.type.startsWith("image/")) return; // Ensure it's an image

                // Prevent duplicates by checking if file already exists
                if (selectedFiles.some((f) => f.name === file.name && f.size === file.size)) {
                    return;
                }

                selectedFiles.push(file); // Store file in memory

                let reader = new FileReader();
                reader.onload = function (e) {
                    let imgElement = $("<img>")
                        .attr("src", e.target.result)
                        .addClass("img-fluid preview-image")
                        .css({ width: "100px", margin: "5px", borderRadius: "5px", cursor: "pointer" })
                        .attr("data-file-name", file.name);

                    previewContainer.append(imgElement);
                    previewContainer.show();
                };
                reader.readAsDataURL(file);
            });
        }


        /**
         * Clears validation errors from one or more forms.
         * 
         * @param {string[]|string} forms - A single form selector or an array of selectors.
         */
        function clearFormErrors(forms) {
            const formSelectors = Array.isArray(forms) ? forms : [forms];

            formSelectors.forEach(selector => {
                const $form = $(selector);

                // Remove error classes from fields
                $form.find(".is-invalid, .invalid").removeClass("is-invalid invalid");

                // Hide and clear alert message containers
                $form.find("span.invalid-feedback[id$='_alert']").each(function () {
                    $(this).css("display", "none").html('<strong></strong>');
                });

                // Optionally hide general error blocks
                $form.find(".print-error-msg").css("display", "none").html("");
            });
        }





        /*------------------------------------------
         --------------------------------------------
            Redirect to Locked Page to Login Code
         --------------------------------------------
         --------------------------------------------
         */
        $('#sessionBtnExpiry').click(function (e) {
              const locale = window.appLocale;
              $('#sessionexpiredModal').modal('hide');
              window.location = "/"+locale+"/login/locked";
        });


          
