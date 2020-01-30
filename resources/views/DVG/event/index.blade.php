@extends('template.template_admin-lte')
@section('content')

    <style>
        /* The container */
        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }
        
        /* On mouse-over, add a grey background color */
        .container:hover input ~ .checkmark {
            background-color: #ccc;
        }
        
        /* When the checkbox is checked, add a blue background */
        .container input:checked ~ .checkmark {
            background-color: #2196F3;
        }
        
        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        
        /* Show the checkmark when checked */
        .container input:checked ~ .checkmark:after {
            display: block;
        }
        
        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        
        /* The container */
        .container-radio {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Hide the browser's default radio button */
        .container-radio input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        
        /* Create a custom radio button */
        .radiomark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 50%;
        }
        
        /* On mouse-over, add a grey background color */
        .container-radio:hover input ~ .radiomark {
            background-color: #ccc;
        }
        
        /* When the radio button is checked, add a blue background */
        .container-radio input:checked ~ .radiomark {
            background-color: #2196F3;
        }
        
        /* Create the indicator (the dot/circle - hidden when not checked) */
        .radiomark:after {
            content: "";
            position: absolute;
            display: none;
        }
        
        /* Show the indicator (dot/circle) when checked */
        .container-radio input:checked ~ .radiomark:after {
            display: block;
        }
        
        /* Style the indicator (dot/circle) */
        .container-radio .radiomark:after {
            top: 9px;
            left: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }
    </style>

    <section class="content-header">
        <h1>
            Training / Event Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">DVG</li>
            <li class="active">Training / Event Management</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {{--  <i class="fa fa-table"></i>&nbsp<b></b>  --}}
                </h3>
        
                <div class="box-tools pull-right">
                    @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'ADMIN' && Auth::User()->id_territory == 'DVG')
                        <button class="btn btn-xs btn-success" style="width: 100px;margin-right: 10px" id="btnSubmitExcel" onclick="exportExcel()" style="width: 120px;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport Excel</button>
                    @endif
                    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#event_add" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Event</button>
                </div>
            </div>
          
            <div class="box-body">
                <div class="nav-tabs-custom active" id="event_tab" role="tabpanel" aria-labelledby="event-tab">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link active" id="internal-tab" data-toggle="tab" href="#internal" role="tab" aria-controls="internal" aria-selected="true">Internal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="external-tab" data-toggle="tab" href="#external" role="tab" aria-controls="external" aria-selected="false">External</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="internal" role="tabpanel" aria-labelledby="internal-tab">
                            <table class="table table-bordered table-striped" id="event_internal_table">
                                <thead>
                                    <tr>
                                        <th style="width: 2%"><center>NO</center></th>
                                        <th><center>Training / Workshop / Event Title</center></th>
                                        <th style="width: 6%"><center>Date</center></th>
                                        <th style="width: 9%"><center>Time</center></th>
                                        <th><center>Venue</center></th>
                                        <th><center>Organizer</center></th>
                                        <th><center>Created By</center></th>
                                        <th><center>Attend</center></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach($event_data as $key => $data)
                                        @if($data->category == 'Internal')
                                            <tr id="kolom{{ $data->id }}">
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <a href="{{ url('/event_management', $data->id) }}">{{$data->title}}</a>
                                                </td>
                                                <td><center>{{ $data->date }}</center></td>
                                                <td><center>{{ $data->start_time }} - {{ $data->end_time }}</center></td>
                                                <td>{!! nl2br(e($data->venue)) !!}</td>
                                                <td>{{ $data->organizer }}</td>
                                                <td><center>{{ $data->name }}</center></td>
                                                <td>
                                                    <?php
                                                        if($data->attendee != null) {
                                                            $array = $data->attendee;
                                                            $arrays = json_decode($array);
                                                            $serialized = serialize($arrays);
                                                            $newarray = unserialize($serialized);
                                                            echo implode(', ', $newarray);
                                                            
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#event_add" onclick="copy('{{$data->title}}','{{$data->venue}}','{{$data->organizer}}')" style="width: 30px;"><i class="fa fa-copy"> </i></button>
                                                    @if($data->created_by == Auth::User()->nik)
                                                        <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#event_edit" onclick="edit('{{$data->id}}','{{$data->title}}','{{$data->date}}','{{$data->start_time}}','{{$data->end_time}}','{{$data->venue}}','{{$data->organizer}}')" style="width: 30px;"><i class="fa fa-edit"> </i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="external" role="tabpanel" aria-labelledby="external-tab">
                            <table class="table table-bordered table-striped" id="event_external_table">
                                <thead>
                                    <tr>
                                        <th style="width: 2%"><center>NO</center></th>
                                        <th><center>Training / Workshop / Event Title</center></th>
                                        <th style="width: 9%"><center>Date</center></th>
                                        <th style="width: 13%"><center>Time</center></th>
                                        <th><center>Venue</center></th>
                                        <th><center>Organizer</center></th>
                                        <th style="width: 9%"><center>Category</center></th>
                                        <th><center>Attend</center></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach($event_data as $key => $data)
                                        @if($data->category == 'External')
                                            <tr id="kolom{{ $data->id }}">
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <a href="{{ url('/event_management', $data->id) }}">{{$data->title}}</a>
                                                </td>
                                                <td><center>{{ $data->date }}</center></td>
                                                <td><center>{{ $data->start_time }} - {{ $data->end_time }}</center></td>
                                                <td>{!! nl2br(e($data->venue)) !!}</td>
                                                <td>{{ $data->organizer }}</td>
                                                <td><center>{{ $data->category }}</center></td>
                                                <td>
                                                    <?php
                                                        if($data->attendee != null) {
                                                            $array = $data->attendee;
                                                            $arrays = json_decode($array);
                                                            $serialized = serialize($arrays);
                                                            $newarray = unserialize($serialized);
                                                            echo implode(', ', $newarray);
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#event_add" onclick="copy('{{$data->title}}','{{$data->venue}}','{{$data->organizer}}')" style="width: 30px;"><i class="fa fa-copy"> </i></button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Calendar
                </h3>
            </div>

            <div class="box-body">
                <div id="calendar"></div>
            </div>
        </div>

        {{--  Event Add  --}}
        <div class="modal fade" id="event_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/event_management/store')}}" id="event_add_form" name="event_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Event Add</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Training / Event Title</label>
                                <input type="text" class="form-control" id="event_title" name="event_title" placeholder="Enter title" required>
                                {{--  <small id="emailHelp" class="form-text text-muted">Well never share your email with anyone else.</small>  --}}
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" id="event_date" name="event_date" placeholder="Enter date" required>
                            </div>
                            <div class="form-group">
                                <label>Start Time</label>
                                <div class="input-group date" id="start_t">
                                    <input type="text" class="form-control" id="start_time" name="start_time" value="00:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>End Time</label>
                                <div class="input-group date" id="end_t">
                                    <input type="text" class="form-control" id="end_time" name="end_time" value="00:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Venue</label>
                                <textarea class="form-control" id="venue" name="venue" placeholder="Enter venue" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Organizer</label>
                                <input type="text" class="form-control" id="organizer" name="organizer" placeholder="Enter organizer" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <label class="container-radio">Internal
                                    <input type="radio" name="option-radio-inline" id="internal" value="Internal" required>
                                    <span class="radiomark"></span>
                                </label>
                                <label class="container-radio">External
                                    <input type="radio" name="option-radio-inline" id="external" value="External" required>
                                    <span class="radiomark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Attend</label>
                                <label class="container">Sales
                                    <input type="checkbox" name="attendee[]" id="sales" value="Sales">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Presales
                                    <input type="checkbox" name="attendee[]" id="presales" value="Presales">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Implementation
                                    <input type="checkbox" name="attendee[]" id="implementation" value="Implementation">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Development
                                    <input type="checkbox" name="attendee[]" id="development" value="Development">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Maintenance
                                    <input type="checkbox" name="attendee[]" id="maintenance" value="Maintenance">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Other
                                    <input type="checkbox" name="attendee[]" id="other" value="Other">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 20%" id="checkBtnAdd">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Event Edit  --}}
        <div class="modal fade" id="event_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/event_management/update')}}" id="event_edit_form" name="event_edit_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Event Edit</h4>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="event_id_edit" name="event_id_edit" hidden>
                            <div class="form-group">
                                <label>Training / Event Title</label>
                                <input type="text" class="form-control" id="event_title_edit" name="event_title_edit" placeholder="Enter title" required>
                                {{--  <small id="emailHelp" class="form-text text-muted">Well never share your email with anyone else.</small>  --}}
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" id="event_date_edit" name="event_date_edit" placeholder="Enter date" required>
                            </div>
                            <div class="form-group">
                                <label>Start Time</label>
                                <div class="input-group date" id="start_t_edit">
                                    <input type="text" class="form-control" id="start_time_edit" name="start_time_edit" value="00:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>End Time</label>
                                <div class="input-group date" id="end_t_edit">
                                    <input type="text" class="form-control" id="end_time_edit" name="end_time_edit" value="00:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Venue</label>
                                <textarea class="form-control" id="venue_edit" name="venue_edit" placeholder="Enter venue" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Organizer</label>
                                <input type="text" class="form-control" id="organizer_edit" name="organizer_edit" placeholder="Enter organizer" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <label class="container-radio">Internal
                                    <input type="radio" name="option-radio-inline-edit" id="internal" value="Internal" required>
                                    <span class="radiomark"></span>
                                </label>
                                <label class="container-radio">External
                                    <input type="radio" name="option-radio-inline-edit" id="external" value="External" required>
                                    <span class="radiomark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Attend</label>
                                <label class="container">Sales
                                    <input type="checkbox" name="attendee_edit[]" id="sales_edit" value="Sales">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Presales
                                    <input type="checkbox" name="attendee_edit[]" id="presales_edit" value="Presales">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Implementation
                                    <input type="checkbox" name="attendee_edit[]" id="implementation_edit" value="Implementation">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Development
                                    <input type="checkbox" name="attendee_edit[]" id="development_edit" value="Development">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Maintenance
                                    <input type="checkbox" name="attendee_edit[]" id="maintenance_edit" value="Maintenance">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Other
                                    <input type="checkbox" name="attendee_edit[]" id="other_edit" value="Other">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 20%" id="checkBtnEdit">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

@endsection

@section('script')

    <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    {{--  Calendar  --}}
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

    <script type="text/javascript">

        $(document).ready(function () {
            $('#checkBtnAdd').click(function() {
              checked = $("input[type=checkbox]:checked").length;
        
              if(!checked) {
                alert("You must check at least one checkbox.");
                return false;
              }
            });
            
            $('#checkBtnEdit').click(function() {
                checked = $("input[type=checkbox]:checked").length;
          
                if(!checked) {
                  alert("You must check at least one checkbox.");
                  return false;
                }
            });
        });

        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events : [
                    @foreach($event_data as $events)
                    {
                        title : '{{ $events->title }}',
                        start : '{{ $events->date }}',
                    },
                    @endforeach
                ]
            })
        });

        var url = {!! json_encode(url('/')) !!}

        function exportExcel() {
            type = encodeURI()
            myUrl = url+"/getdataevent?type="+type
            location.assign(myUrl)
        }

        $('#event_internal_table').DataTable();

        $('#event_external_table').DataTable();

        $('#event_date').datepicker({
            autoclose: true
        });

        $(function () {
            $('#start_t').datetimepicker({
                format: 'HH:mm'
            });

            $('#end_t').datetimepicker({
                format: 'HH:mm'
            });

            $('#start_t_edit').datetimepicker({
                format: 'HH:mm'
            });

            $('#end_t_edit').datetimepicker({
                format: 'HH:mm'
            });
        });

        function edit(id, title, date, start_time, end_time, venue, organizer){
            $('#event_id_edit').val(id);
            $('#event_date_edit').datepicker({format: 'yyyy-mm-dd'}).datepicker('setDate', date)
            $('#start_time_edit').val(start_time.substring(0,5));
            $('#end_time_edit').val(end_time.substring(0,5));
            $('#event_title_edit').val(title);
            $('#venue_edit').val(venue);
            $('#organizer_edit').val(organizer);
        }

        function copy(title, venue, organizer){
            $('#event_title').val(title);
            $('#venue').val(venue);
            $('#organizer').val(organizer);
        }

    </script>

@endsection