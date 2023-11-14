@extends('template.main')
@section('tittle')
  Draft Purchase Request
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.print.min.css" integrity="sha512-5TRYnynyaypDTPI2YvyZBI6T+MPcJM+qSke/cGQEI3Y+2TK9jBZ0xFg0V7Lff9Ik35bN0LiZ8RI3JLZmRHugVw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
@endsection
@section('content')
<section class="content">
  <div class="row">
    <!--   <div class="col-md-3">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Draggable Events</h4>
          </div>
          <div class="box-body">
            <div id="external-events">
              <div class="external-event bg-green ui-draggable ui-draggable-handle" style="position: relative;">Lunch</div>
              <div class="external-event bg-yellow ui-draggable ui-draggable-handle" style="position: relative;">Go home</div>
              <div class="external-event bg-aqua ui-draggable ui-draggable-handle" style="position: relative;">Do homework</div>
              <div class="external-event bg-light-blue ui-draggable ui-draggable-handle" style="position: relative;">Work on UI design</div>
              <div class="external-event bg-red ui-draggable ui-draggable-handle" style="position: relative;">Sleep tight</div>
              <div class="checkbox">
              <label for="drop-remove">
              <input type="checkbox" id="drop-remove">
              remove after drop
              </label>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <div class="box-header with-border">
          <h3 class="box-title">Create Event</h3>
          </div>
          <div class="box-body">
            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
              <ul class="fc-color-picker" id="color-chooser">
                <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
              </ul>
            </div>

            <div class="input-group">
              <input id="new-event" type="text" class="form-control" placeholder="Event Title" fdprocessedid="hfe7u8">
              <div class="input-group-btn">
              <button id="add-new-event" type="button" class="btn btn-primary btn-flat" fdprocessedid="yu5tbe">Add</button>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body no-padding">
            <div id="calendar" ></div>
          </div>
        </div>
      </div>
  </div>
</section> 
<div class="modal fade" id="ModalAddTimesheet" role="dialog">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Notes</h4>
          </div>
          <div class="modal-body">
          <form action="" id="modal_notes" name="modal_notes">
              @csrf
              <div class="form-group">
                  <textarea class="form-control" name="textareaNotes" id="textareaNotes" placeholder="Enter Notes" onkeyup="validateInput(this)"></textarea>
                  <span class="help-block" style="display:none">Please fill Notes!</span>

              </div>
              <div class="form-group">
                <input type="text" class="form-control" id="daterange-input" name="">
              </div>
          </form>
          <div class="modal-footer">
              <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
              <button class="btn btn-sm btn-primary" >Send</button>
          </div>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scriptImport')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script> -->

    <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    {{--  Calendar  --}}
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<!--     <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js" integrity="sha512-ZoSa93h4HcDf7mf8idtRZ490e0QsCNyPzVwEpyON0eL/QzzKnTwK/FctuJDHlJ6HSP93aSVX7gc56UbfBb0RMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.js'></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    $.ajax({
      type:"GET",
      url:"{{url('/getListCalendarEvent')}}",
      success:function(result){
        console.log(result.items)
        // $.each(result.items,function(index,item){
        //   console.log(item.attendees != "")

        // })
        var arrayData = []
        result.items.map(item => {
          $.each(item.attendees,function(index,itemX){
            if (itemX.responseStatus == "accepted") {
              if (itemX.email == "{{Auth::User()->email}}") {
                console.log(itemX.email)
                console.log(item)
                const events = arrayData.push({
                  id:item.id,
                  title: item.summary,
                  start: item.start.dateTime || item.start.date, // Use the appropriate start date/time property from the API response
                  end: item.end.dateTime || item.end.date, // Use the appropriate end date/time property from the API response
                })
              }
            }
          })
            // item.attendees.map(items => {
            //   console.log(items)
            //   if (items.responseStatus == "accepted") {
            //     "salma salma parulian"
            //   }
            // })
        })

        return showEvents(arrayData)
        // const events = data.items.map(item => {
        //   return {
        //     title: item.summary,
        //     start: item.start.dateTime || item.start.date, // Use the appropriate start date/time property from the API response
        //     end: item.end.dateTime || item.end.date, // Use the appropriate end date/time property from the API response
        //     // Add any additional properties you want to display on FullCalendar
        //   };
        // });

        // updateEvent(events)
      }
    })

    var currentDate = new Date(); // Get the current date
    var tomorrow = new Date()
    tomorrow.setDate(currentDate.getDate() + 1)
    var endDate = currentDate.toLocaleDateString();

    $('#daterange-input').daterangepicker({
      startDate: moment().subtract(29, 'days'),
      minDate: tomorrow,
      // maxDate: endDate
    })
    
    function showEvents(events){
      var today = new Date(); // Get today's date
      var startOfWeek = new Date(today); // Create a new date object representing the start of the week
      startOfWeek.setDate(today.getDate() - today.getDay()); // Set the date to the first day of the week (Sunday)

      var datesInWeek = []; // Array to store the dates in the week

      for (var i = 0; i < 7; i++) {
        var currentDate = new Date(startOfWeek);
        currentDate.setDate(startOfWeek.getDate() + i); // Set the date to each day within the week
        
        datesInWeek.push(moment(currentDate)); // Add the date to the array
      }

      console.log("Dates in this week:", datesInWeek);

      var allowedDates = [
        moment('2023-05-15'), // Add your specific dates to this array using moment.js
        moment('2023-05-20'),
        moment('2023-05-25')
      ];

      var currDate = moment().startOf('day');
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today myCustomButton',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        // editable: false,
        droppable: false,
        // defaultView: 'basicWeek',
        // dayClick: function(date, jsEvent, view, resourceObj) {
        //   var today = new Date().toISOString().slice(0, 10);
        //   var clickedDate = date.toISOString().slice(0, 10);
        //   // alert('Date: ' + date.format());
        //   // alert('Resource ID: ' + resourceObj);
        //   if (clickedDate !== today) {
        //     // Disable day click for dates other than today
        //     return false; // Prevent the default behavior of day click
        //   }


        //   $("#ModalAddTimesheet").modal("show")
          
        // },
        selectable:true,
        dayClick: function(date, jsEvent, view) {
            console.log(allowedDates)
            var clickedDate = moment(date).format("YYYY-MM-DD"); 
            // Check if the clicked date is in the allowedDates array
            var isAllowedDate = datesInWeek.some(function(date) {
              return date.isSame(clickedDate, 'day');
            });

            if (isAllowedDate) {
              var isLatestDate = moment(date)
                if (isLatestDate.isSameOrBefore(moment())) {
                  // Handle the dayClick event for dates on or before the current date
                  // console.log('Clicked date:', clickedDate.format());
                  $("#ModalAddTimesheet").modal("show")
                } else {
                  // Date is after the current date, do nothing or show an error message
                  console.log('Date is after the current date');
                }
              // Handle the selection event for allowed dates
              console.log('Selected date:', clickedDate);
            } else {
              // Disable the day click event for disallowed dates
              return false;
            }
             
        },
        selectAllow: function(selectInfo) {
          var selectedDate = selectInfo.start;

          // Check if the selected date is within the valid range
          return selectedDate <= currentDate;
        },
        events: events,
        eventClick: function(calEvent, jsEvent, view) {
          // $("#ModalAddTimesheet").modal("show")
          var today = new Date();
          console.log(calEvent.start._d.getTime())
          console.log(today.getTime())

          var eventStart = calEvent.start._d

          var timeDiff = today.getTime() - eventStart.getTime();
          var diffInDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

          console.log(diffInDays)

          if (diffInDays <= 7) {
            console.log('Event start is within the 7-day range:', eventStart)
          } else {
            console.log('Event start is more than 7 days from today.');
          }
          

            // change the border color just for fun
            // $(this).css('border-color', 'red');

        },
        dayRender: function (date, cell) {
          var today = new Date();
          var end = new Date();

          console.log(today.getDate()+7)
          end.setDate(today.getDate()+7);
          
          // if (date.getDate() === today.getDate()) {
          //     cell.css("background-color", "red");
          // }
          
          if(date > today && date <= end) {
              cell.css("background-color", "yellow");
          }
        } 
      })
      console.log(events)
    }

    
  </script>
@endsection