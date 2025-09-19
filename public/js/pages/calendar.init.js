$(function () {

    const locale = window.appLocale;

    /*------------------------------------------
     --------------------------------------------
     Pass Header Token
     --------------------------------------------
     --------------------------------------------
    */ 
    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
    });



    /**
     * Initialize TUI Calendar
     */
    const calendar = new tui.Calendar('#calendar', {
        defaultView: 'month',
        useCreationPopup: true,
        useDetailPopup: true,
        scheduleView: ['allday', 'time'],
        template: {
            popupEdit: function() {
                return 'Reschedule';  // Change "Edit" to "Reschedule"
            },
            popupDelete: function() {
                return 'Cancel';  // Change "Delete" to "Cancel"
            }
        }
    });

    
    /**
     * Load calendar entries
     */
    function loadCalendarEntries() {
        let start = calendar.getDateRangeStart().toDate(); 
        let end = calendar.getDateRangeEnd().toDate();

        $.ajax({
            type: "GET",
            url: `/${locale}/calendar/search/0?start=${start.toISOString()}&end=${end.toISOString()}&acc=false`,
                success: function (data) {
                    calendar.clear();
                        calendar.createSchedules(data.map(event => ({
                            id: event.id,
                            calendarId: '1',
                            title: event.title,
                            location: event.location,
                            bgColor: event.backgroundColor,
                            start: new Date(event.start_date_time.replace(' ', 'T')),
                            end: new Date(event.end_date_time.replace(' ', 'T')),
                            category: event.category,
                            color: event.color,
                            dragBackgroundColor: event.dragBackgroundColor,
                            borderColor: event.borderColor
                        })));

                    
                },
                error: function (error) {
                    //console.error("Error loading calendar entries:", error);
                }
        });
    }

    /**
     * Update Render Range
     */
    function updateRenderRange() {
        let view = calendar.getViewName();
        let currentDate = calendar.getDate().toDate();
        let start = calendar.getDateRangeStart().toDate();
        let end = calendar.getDateRangeEnd().toDate();
            
        let formatOptions = { month: 'short', day: 'numeric', year: 'numeric' };
        let rangeText = "";
        
            switch (view) {
                case "month":
                    rangeText = currentDate.toLocaleDateString("en-US", { month: "long", year: "numeric" });
                    break;
        
                case "week":
                case "2week":
                case "3week":
                    rangeText = `${start.toLocaleDateString("en-US", formatOptions)} - ${end.toLocaleDateString("en-US", formatOptions)}`;
                    break;
        
                case "day":
                    rangeText = currentDate.toLocaleDateString("en-US", formatOptions);
                    break;
        
                default:
                    rangeText = `${start.toLocaleDateString("en-US", formatOptions)} - ${end.toLocaleDateString("en-US", formatOptions)}`;
                    break;
            }
        
        $("#renderRange").text(rangeText);
    }

      /**
     * Navigation Buttons previouss
     */
    $('#prevBtn').click(() => {
        calendar.prev();
        updateRenderRange();
        loadCalendarEntries();
    });


    /**
     * Navigation Buttons previouss
     */
    $('#nextBtn').click(() => {
        calendar.next();
        updateRenderRange();
        loadCalendarEntries();
    });


     /**
     * change calendar view 
     */
    function changeCalendarView(viewType, iconClass, text, weeksCount) {
        // check if weeks count is not zero
        if(weeksCount !== 0) {    
           calendar.setOptions({month: {visibleWeeksCount: weeksCount}}, true);
        }

        calendar.changeView(viewType, true);
        $("#calendarTypeIcon").attr("class", `calendar-icon ${iconClass}`);
        $("#calendarTypeName").text(text);
        updateRenderRange(); // Update range after changing view
    }

    /**
     * When switching views, ensure checkboxes reflect the current state
     */
     function updateCheckboxStates() {
        let options = calendar.getOptions();
        $('input[value="toggle-workweek"]').prop('checked', options.week.workweek);
        $('input[value="toggle-start-day-1"]').prop('checked', options.week.startDayOfWeek === 1);
        $('input[value="toggle-narrow-weekend"]').prop('checked', options.week.narrowWeekend);
    }


    /**
     * Handle drop down menu clicks
     */
    $(".dropdown-item").on("click", function () {
        var action = $(this).data("action");

        switch (action) {
            case "toggle-daily":
                changeCalendarView("day", "ic_view_day", "Daily", 0);
                break;
            case "toggle-weekly":
                changeCalendarView("week", "ic_view_week", "Weekly", 0);
                break;
            case "toggle-monthly":
                changeCalendarView("month", "ic_view_month", "Monthly", 6);
                break;
            case "toggle-weeks2":
                changeCalendarView("month", "ic_view_week", "2 Weeks", 2);
                break;
            case "toggle-weeks3":
                changeCalendarView("month", "ic_view_week", "3 Weeks", 3);
                break;     
        }

        // Update checkbox states after view change
        updateCheckboxStates();
    });


    /**
     * Handle checkbox toggles dynamiclly
     */
    $(".tui-full-calendar-checkbox-square").change(function () {
        let action = $(this).val(); // Get the checkbox value (toggle-workweek, toggle-start-day-1, etc.)
        let isChecked = $(this).is(':checked');

        switch (action) {
            case 'toggle-workweek':
                calendar.setOptions({week: {workweek: true}}, true);
                calendar.setOptions({month: {workweek: true}}, true);
                calendar.changeView(calendar.getViewName(), true);
                break;

            case 'toggle-start-day-1':
                calendar.setOptions({
                    week: {
                        startDayOfWeek: isChecked ? 1 : 0 // Start week on Monday (1) or Sunday (0)
                    }
                });
                break;

            case 'toggle-narrow-weekend':
                calendar.setOptions({
                    week: {
                        narrowWeekend: isChecked // Make weekends narrower
                    }
                });
                break;
        }

        // Rerender calendar after changes
        calendar.render();
    });


    /**
     * Initial Load
     */
    updateRenderRange();
    loadCalendarEntries();
});