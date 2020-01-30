@extends('template.template_admin-lte')
@section('content')

    <section class="content-header">
        <h1>
            Calendar
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Calendar</li>
        </ol>
    </section>

    <section class="content">

        <div class="box">

            <div class="box-body">
                <div id="calendar"></div>
            </div>
        </div>

    </section>

@endsection

@section('script')

    <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    {{--  Calendar  --}}
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

    <script type="text/javascript">

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

    </script>

@endsection