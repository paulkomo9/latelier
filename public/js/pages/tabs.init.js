$(function () {

    /**
     * build datatable language
     * @returns 
     */
    function buildDataTableLanguage(pageKey, tabHash) {
        // 1. Start with generic DataTables strings you exposed (or empty object)
        const base = window.translations?.dataTables || {};

        // 2. Override the two custom messages that depend on the active tab
        return Object.assign({}, base, {
            emptyTable : getEmptyStateMessage(pageKey, tabHash),
            zeroRecords: getZeroRecordsMessage()
        });
    }


    /**
     * build select 2 language
     * @returns 
     */
    function buildSelect2Language() {
        const t = window.translations.select2;
        return {
            errorLoading: () => t.error_loading,
            inputTooLong: args => t.input_too_long.replace(':over', args.input.length - args.maximum),
            inputTooShort: args => t.input_too_short.replace(':remaining', args.minimum - args.input.length),
            loadingMore: () => t.loading_more,
            maximumSelected: args => t.maximum_selected.replace(':maximum', args.maximum),
            noResults: () => t.no_results,
            searching: () => t.searching
        };
    }





    /**
     * when switching tabs nav-tabs we ensure visible databales are responsive 
     * */
    $('.nav-tabs a').on('shown.bs.tab', function (e){
        //console.log(e.target.href);
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        updateActiveTabMessages();
    });


    /**
     * when switching tabs nav-pills we ensure visible databales are responsive 
     * */
    $('.nav-pills a').on('shown.bs.tab', function (e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        updateActiveTabMessages();
    });

     
    /**
     * when switching accordions we ensure visible databales are responsive 
     * */
    $('.accordion-collapse').on('shown.bs.collapse', function (e){
        //console.log(e.target.href);
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    /**
     * Function to return zero records message (for when filtering/searching)
     */
    function getZeroRecordsMessage() {
        const message = window.translations?.zeroRecordsMessage || 'No matching records found. Try adjusting your search.';

        return `<i class="fas fa-search text-muted"></i> ${message}`;
    }


    /**
     * Function to dynamically update DataTable messages based on active tab and page
     */
    function updateActiveTabMessages(uniquePageName) {
        let activeTab = $('.nav-pills .nav-link.active, .nav-tabs .nav-link.active').attr('href');
        let tableInstance = getActiveDataTableInstance(uniquePageName, activeTab);

        if (tableInstance) {
            tableInstance.settings()[0].oLanguage.sEmptyTable = getEmptyStateMessage(uniquePageName, activeTab);
            tableInstance.settings()[0].oLanguage.sZeroRecords = getZeroRecordsMessage();
            tableInstance.draw();
        }
    }


    /**
     * Returns the appropriate empty table message with an icon.
     * Uses lazy loading: falls back if translations are not available.
     */
    function getEmptyStateMessage(uniquePageName, activeTab) {
        const messages = window.translations?.emptyStates || {
            default: 'No records available.'
        };

        const icons = {
            "system-modules": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "companies": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "company-locations": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "company-departments": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "company-designations": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "company-calendar-settings": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "company-pay-schedules": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "onboard": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "benefits-deductions": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
             "holidays": {
                "#all": '<i class="fas fa-search-minus text-danger font-size-16"></i>',
                "#active": '<i class="bx bxs-error-circle text-danger font-size-16"></i>',
                "#deactivated": '<i class="bx bx-pause-circle text-muted font-size-16"></i>'
            },
            "default": '<i class="fas fa-clipboard-list text-info font-size-16"></i>'
        };

        const tabKey = activeTab.replace('#', '');
        const message = messages[uniquePageName]?.[tabKey] || messages.default;
        const icon = icons[uniquePageName]?.[activeTab] || icons.default;

        return `${icon} ${message}`;
    }


    /**
     * Function to get the correct DataTable instance based on the active page and tab
     */
    function getActiveDataTableInstance(uniquePageName, activeTab) {
        let tables = {
            "system-modules": {
                "#all": typeof tableSystemModules !== 'undefined' ? tableSystemModules : null,
                "#active": typeof tableSystemModulesActive !== 'undefined' ? tableSystemModulesActive : null,
                "#deactivated": typeof tableSystemModulesDeactivated !== 'undefined' ? tableSystemModulesDeactivated : null
            },
            "companies": {
                "#all": typeof tableCompanies !== 'undefined' ? tableCompanies : null,
                "#active": typeof tableCompaniesActive !== 'undefined' ? tableCompaniesActive : null,
                "#deactivated": typeof tableCompaniesDeactivated !== 'undefined' ? tableCompaniesDeactivated : null
            },
            "company-locations": {
                "#all": typeof tableCompanyLocations !== 'undefined' ? tableCompanyLocations : null,
                "#active": typeof tableCompanyLocationsActive !== 'undefined' ? tableCompanyLocationsActive : null,
                "#deactivated": typeof tableCompanyLocationsDeactivated !== 'undefined' ? tableCompanyLocationsDeactivated : null
            },
            "company-departments": {
                "#all": typeof tableCompanyDepartments !== 'undefined' ? tableCompanyDepartments : null,
                "#active": typeof tableCompanyDepartmentsActive !== 'undefined' ? tableCompanyDepartmentsActive : null,
                "#deactivated": typeof tableCompanyDepartmentsDeactivated !== 'undefined' ? tableCompanyDepartmentsDeactivated : null
            },
            "company-designations": {
                "#all": typeof tableCompanyDesignations !== 'undefined' ? tableCompanyDesignations : null,
                "#active": typeof tableCompanyDesignationsActive !== 'undefined' ? tableCompanyDesignationsActive : null,
                "#deactivated": typeof tableCompanyDesignationsDeactivated !== 'undefined' ? tableCompanyDesignationsDeactivated : null
            },
            "company-calendar-settings": {
                "#all": typeof tableCompanyCalendarSettings !== 'undefined' ? tableCompanyCalendarSettings : null,
                "#active": typeof tableCompanyCalendarSettingsActive !== 'undefined' ? tableCompanyCalendarSettingsActive : null,
                "#deactivated": typeof tableCompanyCalendarSettingsDeactivated !== 'undefined' ? tableCompanyCalendarSettingsDeactivated : null
            },
            "company-pay-schedules": {
                "#all": typeof tableCompanyPaySchedules !== 'undefined' ? tableCompanyPaySchedules : null,
                "#active": typeof tableCompanyPaySchedulesActive !== 'undefined' ? tableCompanyPaySchedulesSettingsActive : null,
                "#deactivated": typeof tableCompanyPaySchedulesDeactivated !== 'undefined' ? tableCompanyPaySchedulesDeactivated : null
            },
            "onboard": {
                "#all": typeof tableOnboardEmployees !== 'undefined' ? tableOnboardEmployees : null,
                "#active": typeof tableOnboardEmployeesActive !== 'undefined' ? tableOnboardEmployeesActive : null,
                "#deactivated": typeof tableOnboardEmployeesDeactivated !== 'undefined' ? tableOnboardEmployeesDeactivated : null
            },
            "benefits-deductions": {
                "#all": typeof tableCompanyBenefitsDeductions !== 'undefined' ? tableCompanyBenefitsDeductions : null,
                "#active": typeof tableCompanyBenefitsDeductionsActive !== 'undefined' ? tableCompanyBenefitsDeductionsActive : null,
                "#deactivated": typeof tableCompanyBenefitsDeductionsDeactivated !== 'undefined' ? tableCompanyBenefitsDeductionsDeactivated : null
            },
            "holidays": {
                "#all": typeof tableCompanyHolidays !== 'undefined' ? tableCompanyHolidays : null,
                "#active": typeof tableCompanyHolidaysActive !== 'undefined' ? tableCompanyHolidaysActive : null,
                "#deactivated": typeof tableCompanyHolidaysDeactivated !== 'undefined' ? tableCompanyHolidaysDeactivated : null
            },
           
        };

        return tables[uniquePageName]?.[activeTab] || null;
    }


    // Make these functions globally accessible to other scripts
    window.updateActiveTabMessages = updateActiveTabMessages;
    //window.getEmptyStateMessage = getEmptyStateMessage;
    //window.getZeroRecordsMessage = getZeroRecordsMessage;
    window.buildDataTableLanguage = buildDataTableLanguage;
    window.buildSelect2Language = buildSelect2Language;

});