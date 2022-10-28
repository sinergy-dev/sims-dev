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
            {{ $detail->title }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">DVG</li>
            <li class="active">Detail Training / Event Management</li>
        </ol>
    </section>

    <section class="content">
        <a href="{{url('/event_management')}}"><button button class="btn btn-xs btn-danger pull-left" style="width: 110px"><i class="fa fa-arrow-circle-o-left"></i>&nbsp back to Event</button></a>
        <br>
        <br>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">

                </h3>
        
                <div class="box-tools pull-right">
                    @if($detail->category == 'Internal')
                        @if($detail->date == date("Y-m-d"))
                            @if(Auth::User()->nik == optional($users_check)->nik)
                                <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Attend</button>
                            @else
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#attendee_add" style="width: 75px;" type="submit"><i class="fa fa-plus"> </i>&nbsp Attend</button>
                            @endif
                        @else
                            <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Attend</button>
                        @endif
                    @else
                        @if(Auth::User()->nik == optional($users_check)->nik)
                            <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Attend</button>
                        @else
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#attendee_add" style="width: 75px;" type="submit"><i class="fa fa-plus"> </i>&nbsp Attend</button>
                        @endif
                    @endif
                </div>
            </div>
          
            <div class="box-body">
                <h4>Attend Name List</h4>
                <table class="table table-bordered table-striped" id="event_detail_table">
                    <thead>
                        <tr>
                            <th style="width: 2%"><center>NO</center></th>
                            <th style="width: 20%"><center>Name</center></th>
                            <th><center>Summary</center></th>
                            <th style="width: 10%"><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($event_detail as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{!! nl2br(e($data->summary)) !!}</td>
                                <td><center>
                                    @if($data->nik == Auth::User()->nik)
                                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#summary_add" onclick="summary('{{$data->id_event_detail}}')" style="width: 75px;" type="button"><i class="fa fa-plus"> </i>&nbsp Summary</button>
                                    @else
                                        <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Summary</button>
                                    @endif
                                </center></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--  Event Add  --}}
        <div class="modal fade" id="attendee_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/attendee_add')}}" id="event_add_form" name="event_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Attend</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" value="{{ $detail->id }}" name="detail_id" hidden>
                                <label>Training / Workshop / Event Title</label>
                                <input type="text" class="form-control" id="event_detail_title" name="event_detail_title" value="{{ $detail->title }}" disabled>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 20%">Attend</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Summary Add  --}}
        <div class="modal fade" id="summary_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" id="summary_add_form" name="summary_add_form">
                <form method="POST" action="{{url('/summary_add')}}">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Summary</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" value="" id="summary_detail_id" name="summary_detail_id" hidden>
                                <label>Training / Workshop / Event Title</label>
                                <input type="text" class="form-control" id="event_detail_title" name="event_detail_title" value="{{ $detail->title }}" disabled>
                                <label>Summary</label>
                                @if(Auth::User()->nik == optional($detail_summary)->nik)
                                    <textarea class="form-control" name="event_summary" id="event_summary" cols="30" rows="10">{{ $detail_summary->summary }}</textarea>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 20%">Submit</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript">

        $('#event_detail_table').dataTable();

        function summary(id_event_detail){
            $('#summary_detail_id').val(id_event_detail);
        }

    </script>

@endsection