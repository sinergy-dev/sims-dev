@extends('template.template')
@section('content')
<style type="text/css">

</style>
<div class="content-wrapper">
    <div class="container-fluid">
    	
    	<div>
    		<div id='calendar'></div>
    	</div>
	</div>
</div>

<div id="contohModal" class="modal" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title">Modal Header</h4>
		</div>
		<form method="post" action="{{url('store_task')}}">
			<div class="modal-body">
				  {{ csrf_field() }}
				  Task name:
				  <br />
				  <input type="text" name="task_name" id="task_name" class="form-control " required />
				  <br />
				  Task description:
				  <br />
				  <textarea name="description" id="description" class="form-control" required></textarea>
				  <br />
				  Task date:
				  <br />
				  <input type="text" name="task_date" class="task_date form-control" id="task_date" readonly />
				  <br />
				  <input type="submit" value="Save" class="btn btn-primary btn-block" />
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
	  </form>
	</div>
  </div>
</div>

<div id="Editmodaltask" class="modal" role="dialog">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">Modal Header</h4>
    </div>
    <form method="post" action="">
      <div class="modal-body">
          {{ csrf_field() }}
          <input type="text" name="id_task" id="id_task" class="form-control"/>
          Task name:
          <br />
          <input type="text" name="task_name_update" id="task_name_update" class="form-control"/>
          <br />
          Task description:
          <br />
          <textarea name="description_update" id="description_update" class="form-control"></textarea>
          <br />
          Task date:
          <br />
          <input type="text" name="task_date_update" class="task_date_update form-control" id="task_date_update" readonly />
          <br />
          <input type="submit" value="Update" class="btn btn-warning btn-block" /><br>      
          @csrf
      </div>
    </form>
      <div class="modal-footer"><!-- 
      <a href="{{url('/done_task')}}"><button type="submit" class="btn btn-success">Done</button></a> -->
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </div>
  </div>
</div>

<div id="eventContent" title="Event Details" style="display:none;">
    Start: <span id="startTime"></span><br>
    End: <span id="endTime"></span><br><br>
    <p id="eventInfo"></p>
    <p><strong><a id="eventLink" href="" target="_blank">Read More</a></strong></p>
</div>

@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      editable: true,
      selectable: true,
      selectHelper: true,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many event
      events : [
          @foreach($tasks as $data){
            content : '{{$data->id_task}},{{$data->task_date}}',
            title : '{{ $data->task_name }}',
            start : '{{ $data->task_date }}',
            textColor: 'white',
            color: '#007bff'
          },
          @endforeach

          @foreach($lead as $task)
          {
              title : '{{ $task->opp_name }}',
              start : '{{ $task->created_at }}',
              @if($task->result != 'OPEN')
              url : '{{ url('detail_sales', $task->lead_id) }}',
              @else
              url : '{{ url('project') }}',
              @endif
              @if ($task->result == 'OPEN')
              color: '#6f42c1'
              @elseif($task->result == '')
              color: '#f2562b'
              @elseif($task->result == 'SD')
              color: '#04dda'
              @elseif($task->result == 'TP')
              color: '#f7e127',
              textColor: 'black'
              @elseif($task->result == 'WIN')
              color: '#246d18'
              @elseif($task->result == 'LOSE')
              color: '#FF0000'
              @endif
          },
          @endforeach
      ],
	    dayClick: function(date, jsEvent, view, moment) {
        	var get_date = date.format();
          	$('#contohModal').modal('show');
	          $('.modal-backdrop').removeClass("modal-backdrop");
          	$('#task_date').val(get_date);
	    },
      eventRender: function(event, element, date) {
          element.bind('dblclick', function() {
    
          });
        },
      });

    $('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd"
    });


  });
</script>

<script src="{{asset('/js/jquery.min.js')}}"></script>
<script src="{{asset('js/moment.min.js')}}"></script>
<script src="{{asset('js/fullcalendar.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@endsection
