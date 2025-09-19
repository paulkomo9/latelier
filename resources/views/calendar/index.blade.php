@extends('layouts.inner')

@section('content')
    
    <div class="container">

                <div class="row justify-content-center pt-4 pb-4">

                    <!-- Toolbar -->
                    <div class="mb-3" id="calendar_menu">

                        <span id="menu-navi" class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="d-sm-flex flex-wrap gap-1">
                                <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                    <button type="button" id="prevBtn" class="btn btn-primary move-day" data-action="move-prev">
                                        <i class="calendar-icon ic-arrow-line-left mdi mdi-chevron-left" data-action="move-prev"></i>
                                    </button>
                                    <button type="button" id="nextBtn" class="btn btn-primary move-day" data-action="move-next">
                                        <i class="calendar-icon ic-arrow-line-right mdi mdi-chevron-right" data-action="move-next"></i>
                                    </button>
                                </div>
                                        
                                        
                                <button type="button" class="btn btn-primary move-today mb-2" data-action="move-today">Today</button>
                            </div>
                                        
                            <h4 id="renderRange" class="render-range fw-bold pt-1 mx-3"></h4>
                                        
                            <div class="dropdown align-self-start mt-3 mt-sm-0 mb-2">
                                <button id="dropdownMenu-calendarType" class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;"></i>
                                    <span id="calendarTypeName">Monthly</span>&nbsp;
                                    <i class="calendar-icon tui-full-calendar-dropdown-arrow"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" role="menu" aria-labelledby="dropdownMenu-calendarType">
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-daily">
                                            <i class="calendar-icon ic_view_day"></i>Daily
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weekly">
                                            <i class="calendar-icon ic_view_week"></i>Weekly
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-monthly">
                                            <i class="calendar-icon ic_view_month"></i>Monthly
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weeks2">
                                            <i class="calendar-icon ic_view_week"></i>2 weeks
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-weeks3">
                                            <i class="calendar-icon ic_view_week"></i>3 weeks
                                        </a>
                                    </li>
                                    <li role="presentation" class="dropdown-divider"></li>

                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-workweek">
                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-workweek" checked="">
                                                <span class="checkbox-title"></span>Show weekends
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-start-day-1">
                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-start-day-1">
                                            <span class="checkbox-title"></span>Start Week on Monday
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a class="dropdown-item" role="menuitem" data-action="toggle-narrow-weekend">
                                            <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-narrow-weekend">
                                            <span class="checkbox-title"></span>Narrower than weekdays
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </span>
                    </div>
                                           
                                           

                    <!-- Calendar Container -->
                    <div id="calendar" style="height: 600px;"></div>

                </div>
                <!-- end row -->
				
			</div>

    <script>
        window.appLocale = "{{ app()->getLocale() }}";
    </script>


    <!-- calendar init -->
   <script src="{{ asset('js/pages/calendar.init.js') }}"></script>
  
@endsection