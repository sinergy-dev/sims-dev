@extends('template.main')
@section('tittle')
  Timesheet
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.print.css" media="print">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">
  <style type="text/css">
    .select2{
      width: 100%!important;
    }

    textarea{
      resize: vertical;
    }

    .disabled-day {
      pointer-events: none; /* Disable pointer events for the disabled days */
      cursor: not allowed;
    }

    .highlighted{
      background-color: blue;
    }

    .ui-datepicker-week-end {
      color: red;
    }
  </style>
@endsection
@section('content')
<section class="content-header">
    <h1>
        Timesheet
        <small>Timesheet</small>
    </h1>
    <ol class="breadcrumb">
        <!-- <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li> -->
        <li class="active"><i class="fa fa-calendar"></i> Timesheet</li>
    </ol><br>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-warning alert-dismissible" id="alertForRemaining" style="display:none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info-circle"></i> Hai <span>{name}</span>! Your mandays this month is <span>{percentage}</span>%, Happy Working!!  &#9994; <a href="{{url('timesheet/dashboard')}}" style="cursor: pointer;">See My Dashboard</a></h4>
      </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-3 col-xs-12">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Event Status Dictionary</h4>
          </div>
          <div class="box-body">
            <div id="external-events">
              <div class="external-event" style="position: relative;background-color: #3c8dbc;color: white;cursor: text;">New</div>
              <div class="external-event" style="position: relative;background-color: #00a65a;color: white; z-index: auto; left: 0px; top: 0px;cursor: text;">Done</div>
              <div class="external-event" style="position: relative;background-color: #f56954;color: white; z-index: auto; left: 0px; top: 0px;cursor: text;">Cancel</div>
              <div class="external-event" style="position: relative;background-color: #00c0ef;color: white;cursor: text;">Reschedule</div>
              <div class="external-event" style="position: relative;background-color: #f39c12;color: white;cursor: text;">Not-Done</div>
              <div class="external-event" style="position: relative;background-color: #605ca8;color: white;cursor: text;">Sick</div>
              <div class="external-event" style="position: relative;background-color: #605ca8;color: white;cursor: text;">Permite</div>
              <div class="external-event" style="position: relative;background-color: #605ca8;color: white;cursor: text;">Leaving Permite</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-9 col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <div class="pull-left">
              <a class="btn btn-sm btn-danger" id="btn_back_timesheet_spv" style="display:none"><i class="fa fa-arrow-left"></i>&nbsp&nbspMy Timesheet</a>
              &nbsp&nbsp<h3 class="box-title name_change"></h3>
            </div>
            <div class="pull-right">
              <button class="btn btn-sm bg-orange" onclick="addPermit()">Permit</button>
            </div>
          </div>
          <div class="box-body no-padding">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="ModalAddTimesheet" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Activity</h4>
            </div>
            <div class="modal-body">
            <form action="" id="modal_timesheet" name="modal_timesheet">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="" name="id_activity" id="id_activity" value="" hidden>
                      <label>Schedule*</label>
                      <select class="form-control select2" name="selectSchedule" id="selectSchedule" onchange="validateInput(this)">
                        <option></option>
                      </select>
                      <span class="help-block" style="display:none">Please select schedule!</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Date*</label>
                      <input type="text" class="form-control" id="daterange-input" name="" disabled="disabled">
                      <span class="help-block" style="display:none">Please select date!</span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Type*</label>
                  <select class="form-control select2" name="selectType" id="selectType" onchange="validateInput(this)">
                    <option></option>
                  </select>
                  <span class="help-block" style="display:none">Please select Type!</span>
                </div>

                <div class="form-group">
                  <label>PID/Lead ID</label>
                  <select class="form-control" name="selectLead" id="selectLead" placeholder="Select Project Id" onchange="validateInput(this)"><option></option></select>
                  <span class="help-block" style="display:none">Please select Lead ID!</span>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Task <small onclick="showHelp('task')"><i class="fa fa-info-circle"></i></small></label>
                      <select class="form-control" name="selectTask" id="selectTask"><option></option></select>
                      <!-- <span class="help-block" style="display:none">Please select task!</span> -->
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Phase <small onclick="showHelp('phase')"><i class="fa fa-info-circle"></i></small></label>
                      <select class="form-control" name="selectPhase" id="selectPhase"><option></option></select>
                      <!-- <span class="help-block" style="display:none">Please select phase!</span> -->
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Level <small onclick="showHelp('level')"><i class="fa fa-info-circle"></i></small></label>
                  <select class="form-control" name="selectLevel" id="selectLevel"><option></option></select>
                  <span class="help-block" style="display:none">Please select Level!</span>
                </div>

                <div class="form-group">
                  <label>Activity*</label>
                  <textarea class="form-control" name="textareaActivity" id="textareaActivity" onkeyup="validateInput(this)"></textarea> 
                  <span class="help-block" style="display:none">Please fill Activity!</span>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Duration</label>
                      <select class="form-control" name="selectDuration" id="selectDuration" onchange="validateInput(this)"><option></option></select>
                      <span class="help-block" style="display:none">Please select Duration!</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select class="form-control" name="selectStatus" id="selectStatus" onchange="validateInput(this)"><option></option></select>
                      <span class="help-block" style="display:none">Please select Status!</span>
                    </div>
                  </div>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" onclick="saveTimesheet()">Save</button>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalPermit" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Permit</h4>
            </div>
            <div class="modal-body">
            <form action="" id="modal_phase" name="modal_phase">
                @csrf
                <div class="form-group">
                  <label>Date*</label>
                  <input class="form-control" name="inputPermitDate" id="inputPermitDate" onchange="validateInput(this)"/>
                  <span class="help-block" style="display:none">Please fill Date!</span>
                </div>
                <div class="form-group">
                  <label>Permit*</label>
                  <select class="form-control select2" name="selectPermit" id="selectPermit" onchange="validateInput(this)">
                    <option></option>
                  </select>
                  <span class="help-block" style="display:none">Please fill Permit!</span>
                </div>
                <div class="form-group">
                  <label>Activity*</label>
                  <textarea class="form-control" name="textareaActivityPermit" id="textareaActivityPermit" placeholder="Enter Activity" onkeyup="validateInput(this)"></textarea>
                  <span class="help-block" style="display:none">Please fill Activity!</span>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="storePermit()">Save</button>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalInfo" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Information!</h4>
            </div>
            <div class="modal-body">
            <form action="" id="modal_info" name="modal_info">
                @csrf
                <table class="table table-striped" id="tbInfo">
                </table>
            </form>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</section> 
@endsection
@section('scriptImport')
  <!-- <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  {{--  Calendar  --}}
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data-10-year-range.js" integrity="sha512-QSV7x6aYfVs/XXIrUoerB2a7Ea9M8CaX4rY5pK/jVV0CGhYiGSHaDCKx/EPRQ70hYHiaq/NaQp8GtK+05uoSOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> 
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript"> 
    var nik = "{{Auth::User()->nik}}"
    if (window.location.href.split("/")[3].split("=")[1] == undefined) {
      nik = nik
    }else if (window.location.href.split("/")[3].split("?")[1].split("=")[0] == "nik"){
      nik = window.location.href.split("/")[3].split("=")[1]
    }

    var email = '', name = ''
    $.ajax({
      type:"GET",
      url:"{{url('/timesheet/getNameByNik')}}",
      data:{
        nik:nik
      },success:function(result){
        return email = result.email
      }
    })

    var calendar
    $(document).ready(function(){
      var accesable = @json($feature_item);
      console.log(accesable) 

      accesable.forEach(function(item,index){
        $("#" + item).show()
      })

      if (nik == '{{Auth::User()->nik}}') {
        $('#btn_back_timesheet_spv').hide()
      }

      if(nik == "{{Auth::User()->nik}}"){
        showAlertRemaining()
      }

      calendar = $('#calendar').fullCalendar({
        timezone:'Asia/Jakarta',
        header: {
          left: 'prev,next today myCustomButton',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        } 
      });
      loadData()
    })

    $("#btn_back_timesheet_spv").click(function(){
      window.location.href = '{{url("timesheet")}}';
      // location.reload()
    })

    var currentDate = new Date(); // Get the current date
    var tomorrow = new Date()
    tomorrow.setDate(currentDate.getDate() + 1)
    var endDate = currentDate.toLocaleDateString();

    function loadData(){
      Pace.restart();
      Pace.track(function(){
        $.ajax({
          type:"GET",
          url:"{{'/timesheet/getAllActivityByUser'}}",
          data:{
            nik:nik
          },
          success:function(results){
            console.log(results)
            Pace.restart();
            Pace.track(function(){
              $.ajax({
                type:"GET",
                url:"{{url('/getListCalendarEvent')}}",
                data:{
                  nik:nik
                },
                success:function(result){
                  var events = [], disabledDates = []
                  if (results.data.length > 0) {
                    $.each(results.data,function(idx,value){
                      if (value.remarks != null) {
                        events.push({
                          title:value.activity,
                          start:value.start_date,
                          end:value.end_date,
                          activity:value.activity,
                          remarks:value.remarks
                        })   

                        disabledDates.push(moment.utc(value.start_date, 'YYYY-MM-DD'))
                      }else{
                          events.push({
                            title:value.activity,
                            start:value.start_date,
                            // end:value.end_date,
                            end:moment(value.end_date).endOf('day'),
                            // moment(value.end_date).endOf('day')
                            id:value.id,
                            type:value.type,
                            task:value.task,
                            schedule:value.schedule,
                            pid:value.pid,
                            phase:value.phase,
                            level:value.level,
                            duration:value.duration,
                            status:value.status,
                          }) 
                            
                      }
                    })
                  }

                  var lock_activity = [{"lock_activity":results.lock_duration}]
                  var arrayData = []
                  result.items.map(item => {
                    if (item.creator != undefined) {
                      if (Object.keys(item.creator).length == 2) {
                        if (item.creator.self == true) {
                          const events = arrayData.push({
                            id:item.id,
                            title: item.summary,
                            start: item.start.dateTime || item.start.date, // Use the appropriate start date/time property from the API response
                            end: item.end.dateTime || item.end.date, // Use the appropriate end date/time property from the API response
                            activity: item.summary,
                            refer:"gcal",
                          })
                        }
                      }
                    }
                 

                    $.each(item.attendees,function(index,itemX){
                      if (itemX.responseStatus == "accepted") {
                        if (itemX.email == email) {
                          const events = arrayData.push({
                            id:item.id,
                            title: item.summary,
                            start: item.start.dateTime || item.start.date, // Use the appropriate start date/time property from the API response
                            end: item.end.dateTime || item.end.date, // Use the appropriate end date/time property from the API response
                            activity: item.summary,
                            refer:"gcal"
                          })
                        }
                      }
                    })
                  })

                  var filteredData = arrayData.filter(function(obj1) {
                    return !events.some(function(obj2) {
                      return obj1.title === obj2.title
                    });
                  });
                  var arrayCalconcatDb = events.concat(filteredData)


                  // var uniqueArray = arrayCalconcatDb.filter((obj, index, self) =>
                  //   index === self.findIndex((item) => item.refer === obj.refer)
                  // );

                  
                  return showEvents(arrayCalconcatDb,lock_activity,disabledDates)
                },
                complete:function(){
                  Pace.stop();
                }
              })
            })
          }
        })
      })
    }

    function showEvents(events,lock_activity,disabledDates){
      if (events) {
        $.ajax({
          type:"GET",
          url:"{{url('/timesheet/getNameByNik')}}",
          data:{
            nik:nik
          },success:function(result){
            $(".name_change").html('As '+ result.name + " <small>Timesheet</small>")
          }
        })

        var today = new Date(); // Get today's date
        var startOfWeek = new Date(today); // Create a new date object representing the start of the week
        if (lock_activity[0].lock_activity == 1) {
          var daysToSubtract = today.getDay(); // Add 7 to ensure we get to the first day of the next two-week period
          var incDate = 7
        }else if (lock_activity[0].lock_activity == 2) {
          var daysToSubtract = today.getDay() + 7; // Add 7 to ensure we get to the first day of the next two-week period
          var incDate = 14
        }else if(lock_activity[0].lock_activity == 3){
          var daysToSubtract = today.getDay() + 14; // Add 7 to ensure we get to the first day of the next two-week period
          var incDate = 21
        }else{
          startOfWeek.setDate(1) //lock activity 1 month
          var incDate = 30
        }

        // Set the date to the first day of the two-week or three-week period
        var startOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - daysToSubtract);
        var datesInWeek = []; // Array to store the dates in the week

        for (var i = 0; i < incDate; i++) {
          var currentDate = new Date(startOfWeek);
          currentDate.setDate(startOfWeek.getDate() + i); // Set the date to each day within the week
          
          datesInWeek.push(moment(currentDate)); // Add the date to the array
        }

        var allowedDates = datesInWeek

        var currDate = moment().startOf('day');
      }
      
      //1 week
      // startOfWeek.setDate(today.getDate() - today.getDay()); // Set the date to the first day of the week (Sunday)
      // var daysToSubtract = today.getDay(); // Add 7 to ensure we get to the first day of the next two-week period
      // var incDate = 7

      //2 week
      // var daysToSubtract = today.getDay() + 7; // Add 7 to ensure we get to the first day of the next two-week period
      // var incDate = 14


      //3 week
      // var daysToSubtract = today.getDay() + 14; // Add 7 to ensure we get to the first day of the next two-week period
      // var incDate = 21


      //1 month
      //startOfWeek.setDate(1) //lock activity 1 month
      // var incDate = 30
      calendar.fullCalendar('destroy');
      $('#calendar').fullCalendar({
        timezone: 'Asia/Jakarta',
        header: {
          left: 'prev,next today myCustomButton',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        }, 
        dayClick: function(date, jsEvent, view) {
          var position = "{{Auth::User()->id_position}}"
          if (position.includes("MANAGER") || window.location.href.split("/")[3].split("=")[1]) {
            return false
          }else{
            var clickedDate = moment(date).format("YYYY-MM-DD"); 
            // Check if the clicked date is in the allowedDates array
            var isAllowedDate = datesInWeek.some(function(date) {
              return date.isSame(clickedDate, 'day');
            });

            if (isAllowedDate) {
              var isClickedDate = moment(date)
                if (isClickedDate.isSameOrBefore(moment())) {
                  if (disabledDates.some(function(disabledDate) {
                    return date.isSame(disabledDate, 'day');
                  })) {
                    // Disable day click for the disabled dates
                    return false;
                  }else{
                    setDuration()
                    setTask()
                    setPhase()
                    setLevel()
                    setStatus()
                    setType()
                    setSchedule(date)

                    $('#selectSchedule').val('').trigger('change')
                    $('#selectType').val('').trigger('change').prop("disabled",false)
                    $('#selectLead').val('').trigger('change').prop("disabled",false)
                    $('#selectTask').val('').trigger('change').prop("disabled",false)
                    $('#selectPhase').val('').trigger('change').prop("disabled",false)
                    $('#selectLevel').val('').trigger('change').prop("disabled",false)
                    $('#textareaActivity').val('').prop("disabled",false)
                    $('#selectDuration').val('').trigger('change').prop("disabled",false)
                    $('#selectStatus').val('').trigger('change').prop("disabled",false)

                    $("#ModalAddTimesheet").modal("show")
                    $("#ModalAddTimesheet").find('.modal-footer').show()

                    $(".modal-title").text("Add Timesheet")
                    if ($("#ModalAddTimesheet").find('.modal-footer').find(".btn-warning")) {
                      $("#ModalAddTimesheet").find('.modal-footer').find(".btn-warning").removeClass("btn-warning").addClass("btn-primary").text('Save')
                    }
                    $('#daterange-input').val('').prop("disabled",false)
                    $('#selectSchedule').val('').prop("disabled",false)
            
                  }
                  
                } else {
                  Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Sorry not yet for update. Date is after the current date!',
                    confirmButtonText: 'OK'
                  }).then((result) => {
                    // Handle the user's interaction with the alert if needed
                    if (result.isConfirmed) {
                      // The user clicked the 'OK' button
                      console.log('User clicked OK');
                    }
                  });
                }
            } else {
              // Disable the day click event for disallowed dates
              return false;
            }  
          }
        },
        eventClick: function(calEvent, jsEvent, view) {
            $("#ModalAddTimesheet").find(".modal-title").prev('button').show()
            $("#ModalAddTimesheet").find('.modal-footer').find(".btn-danger").show()
            $("#id_activity").val("")
            var clickedDate = calEvent.start 
            // Check if the clicked date is in the allowedDates array
            var isAllowedDate = datesInWeek.some(function(date) {
              return date.isSame(clickedDate, 'day');
            });

            //initiate modal
            $('#selectSchedule').val('').trigger('change')
            $('#selectType').val('').trigger('change')
            $('#selectLead').val('').trigger('change')
            $('#selectTask').val('').trigger('change')
            $('#selectPhase').val('').trigger('change')
            $('#selectLevel').val('').trigger('change')
            $('#textareaActivity').val('')
            $('#selectDuration').val('').trigger('change')
            $('#selectStatus').val('').trigger('change')

            if (isAllowedDate) {
              var isClickedDate = moment(calEvent.start)
                if (isClickedDate.isSameOrBefore(moment())) {
                  // }
                  if (disabledDates.some(function(disabledDate) {
                    return calEvent.start.isSame(disabledDate, 'day');
                  })) {
                    $("#ModalInfo").modal("show")
                    $(".modal-title").text("Information")
                    $("#tbInfo").empty()
                    var append = ""
                    append = append + '<tr>'
                    append = append + '  <th>Date</th>'
                    append = append + '  <td>'+ moment(calEvent.start).format('YYYY-MM-DD')  +'</td>'
                    append = append + '</tr>'
                    append = append + '<tr>'
                    append = append + '  <th>Activity</th>'
                    append = append + '  <td>'+ calEvent.activity  +'</td>'
                    append = append + '</tr>'

                    $("#tbInfo").append(append)
                  }else{
                    setDuration()
                    setTask(calEvent.task)
                    setPhase(calEvent.phase)
                    setLevel()
                    setStatus()
                    setType()
                    setSchedule()

                    $("#ModalAddTimesheet").modal("show")
                    $(".modal-title").text("Update Timesheet")
                    $("#ModalAddTimesheet").find('.modal-footer').find(".btn-primary").removeClass("btn-primary").addClass("btn-warning").text('Update')


                    if (calEvent.refer) {
                      $('#selectSchedule').prop("disabled",true)
                      $('#selectSchedule').val('Planned').trigger('change')
                      $('#daterange-input').daterangepicker().data('daterangepicker').setStartDate(moment(calEvent.start, 'YYYY-MM-DD'))
                      $('#daterange-input').daterangepicker().data('daterangepicker').setEndDate(moment(calEvent.end, 'YYYY-MM-DD'))
                      $('#daterange-input').prop("disabled",true)    
                      $('#textareaActivity').val(calEvent.title).trigger('change') 

                      if(nik != "{{Auth::User()->nik}}"){
                        $('#selectType').prop("disabled",true)
                        $('#selectLead').prop("disabled",true)
                        $('#selectTask').prop("disabled",true)
                        $('#selectPhase').prop("disabled",true)
                        $('#selectLevel').prop("disabled",true)
                        $('#textareaActivity').prop("disabled",true)
                        $('#selectDuration').prop("disabled",true)
                        $('#selectStatus').prop("disabled",true)
                        $("#ModalAddTimesheet").find('.modal-footer').hide()
                      }else{
                        var momentDate = moment(calEvent.start); // Replace with your own moment date
                        // Get today's date
                        var today = moment();
                        // Compare the date components
                        var isSameDateToday = momentDate.isSame(today, 'day');
                        if (isSameDateToday) {
                          $('#selectType').prop("disabled",false)
                          $('#selectLead').prop("disabled",false)
                          $('#selectTask').prop("disabled",false)
                          $('#selectPhase').prop("disabled",false)
                          $('#selectLevel').prop("disabled",false)
                          $('#textareaActivity').prop("disabled",false)
                          $('#selectDuration').prop("disabled",false)
                          $('#selectStatus').prop("disabled",false)
                          $("#ModalAddTimesheet").find('.modal-footer').show()
                        }else{
                          $("#ModalAddTimesheet").find('.modal-footer').hide()
                          $('#selectType').prop("disabled",true)
                          $('#selectLead').prop("disabled",true)
                          $('#selectTask').prop("disabled",true)
                          $('#selectPhase').prop("disabled",true)
                          $('#selectLevel').prop("disabled",true)
                          $('#textareaActivity').prop("disabled",true)
                          $('#selectDuration').prop("disabled",true)
                          $('#selectStatus').prop("disabled",true)
                        }
                        // $('#selectType').prop("disabled",true)
                        // $('#selectLead').prop("disabled",true)
                        // $('#selectTask').prop("disabled",true)
                        // $('#selectPhase').prop("disabled",true)
                        // $('#selectLevel').prop("disabled",true)
                        // $('#textareaActivity').prop("disabled",true)
                      }                         
                    }else{
                      $('#selectSchedule').val(calEvent.schedule).trigger('change')
                      $('#selectSchedule').prop("disabled",true)
                      $('#daterange-input').data('daterangepicker').setStartDate(moment(calEvent.start, 'YYYY-MM-DD'));
                      $('#daterange-input').data('daterangepicker').setEndDate(moment(calEvent.end, 'YYYY-MM-DD'));
                      $('#daterange-input').prop("disabled",true)


                      //supervisor
                      if("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name','like','%SPV')->exists()}}" || "{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name','like','%MANAGER')->exists()}}"){
                        $('#selectType').prop("disabled",false)
                        $('#selectLead').prop("disabled",false)
                        $('#selectTask').prop("disabled",false)
                        $('#selectPhase').prop("disabled",false)
                        $('#selectLevel').prop("disabled",false)
                        $('#textareaActivity').prop("disabled",false)
                        $('#selectDuration').prop("disabled",false)
                        $('#selectStatus').prop("disabled",false)  
                        $("#ModalAddTimesheet").find('.modal-footer').show()
                      }else{
                        var momentDate = moment(calEvent.start); // Replace with your own moment date
                        // Get today's date
                        var today = moment();
                        // Compare the date components
                        var isSameDateToday = momentDate.isSame(today, 'day');
                        if (isSameDateToday) {
                          $('#selectDuration').prop("disabled",false)
                          $('#selectStatus').prop("disabled",false)
                          $("#ModalAddTimesheet").find('.modal-footer').show()
                        }else{
                          $("#ModalAddTimesheet").find('.modal-footer').hide()
                          $('#selectDuration').prop("disabled",true)
                          $('#selectStatus').prop("disabled",true)
                        }
                        $('#selectType').prop("disabled",true)
                        $('#selectLead').prop("disabled",true)
                        $('#selectTask').prop("disabled",true)
                        $('#selectPhase').prop("disabled",true)
                        $('#selectLevel').prop("disabled",true)
                        $('#textareaActivity').prop("disabled",true)
                      }

                      $("#id_activity").val(calEvent.id)

                      //staff
                      console.log(calEvent.pid)
                      $('#selectType').val(calEvent.type).trigger('change')
                      $('#selectLead').val(calEvent.pid).trigger('change')
                      $('#selectLevel').val(calEvent.level).trigger('change')
                      $('#textareaActivity').val(calEvent.title).trigger('change')
                      $('#selectDuration').val(calEvent.duration).trigger('change')
                      $('#selectStatus').val(calEvent.status).trigger('change')
                    }
                  }
                } else {
                  Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Sorry not yet for update. Date is after the current date!',
                    confirmButtonText: 'OK'
                  }).then((result) => {
                    // Handle the user's interaction with the alert if needed
                    if (result.isConfirmed) {
                      // The user clicked the 'OK' button
                      console.log('User clicked OK');
                    }
                  });

                }
              // Handle the selection event for allowed dates
            } else {
              if (disabledDates.some(function(disabledDate) {
                return calEvent.start.isSame(disabledDate, 'day');
              })) {
                $("#ModalInfo").modal("show")
                $(".modal-title").text("Information")
                $("#tbInfo").empty()
                var append = ""
                append = append + '<tr>'
                append = append + '  <th>Date</th>'
                append = append + '  <td>'+ moment(calEvent.start).format('YYYY-MM-DD')  +'</td>'
                append = append + '</tr>'
                append = append + '<tr>'
                append = append + '  <th>Activity</th>'
                append = append + '  <td>'+ calEvent.activity  +'</td>'
                append = append + '</tr>'

                $("#tbInfo").append(append)
              }else{
                console.log("aku klik ini")
                setDuration()
                setTask(calEvent.task)
                setPhase(calEvent.phase)
                setLevel()
                setStatus()
                setSchedule()

                $("#ModalAddTimesheet").modal("show")
                $(".modal-title").text("Detail Timesheet")
                $("#ModalAddTimesheet").find('.modal-footer').hide()
                $('#selectSchedule').prop("disabled",true)
                $('#selectType').prop("disabled",true)
                $('#selectLead').prop("disabled",true)
                $('#selectTask').prop("disabled",true)
                $('#selectPhase').prop("disabled",true)
                $('#selectLevel').prop("disabled",true)
                $('#textareaActivity').prop("disabled",true)
                $('#selectDuration').prop("disabled",true)
                $('#selectStatus').prop("disabled",true)

                if (calEvent.refer) {
                  $('#selectSchedule').val('Planned').trigger('change') 
                  $('#daterange-input').daterangepicker().data('daterangepicker').setStartDate(moment(calEvent.start, 'YYYY-MM-DD'))
                  $('#daterange-input').daterangepicker().data('daterangepicker').setEndDate(moment(calEvent.end, 'YYYY-MM-DD'))
                  $('#daterange-input').prop("disabled",true)    
                  $('#textareaActivity').val(calEvent.title).trigger('change')   
                }else{
                  $('#selectSchedule').val(calEvent.schedule).trigger('change')
                  $('#daterange-input').daterangepicker().data('daterangepicker').setStartDate(moment(calEvent.start, 'YYYY-MM-DD'))
                  $('#daterange-input').daterangepicker().data('daterangepicker').setEndDate(moment(calEvent.end, 'YYYY-MM-DD'))
                  $('#daterange-input').prop("disabled",true)
                  $('#selectType').val(calEvent.type).trigger('change')
                  $('#selectLead').val(calEvent.pid).trigger('change')
                  $('#selectTask').val(calEvent.task).trigger('change')
                  $('#selectPhase').val(calEvent.phase).trigger('change')
                  $('#selectLevel').val(calEvent.level).trigger('change')
                  $('#textareaActivity').val(calEvent.title)
                  $('#selectDuration').val(calEvent.duration).trigger('change')
                  $('#selectStatus').val(calEvent.status).trigger('change')
                }
              
                // Disable the day click event for disallowed dates
                return false;
              }
            }
        },
        dayRender: function (date, cell) {
          var currentDate = moment.utc(date);

          // Your condition to determine whether dayClick should be prevented
          if (disabledDates.some(function(disableDate) {            
              return currentDate.isSame(disableDate, 'day');
          })) {
              cell.css('background-color', '#EEE');
          }

          var todays = new Date()
          var today = moment().startOf('day'); // Get the current date
          var cellDate = moment(date).startOf('day'); // Get the date being rendered

          if (cellDate.isAfter(todays)) {
            cell.css('background-color', '#EEE'); // Set background color for days after today
            cell.addClass('disabled-day'); // Add a class to indicate disabled days
            
          }
        },
        eventRender: function (event, element, view) {
          // Change event color
          if (event.remarks != null) {
            element.css('color', '#FFFFFF');
            preventDayClick = false
            if (event.remarks == 'Sick' || event.remarks == 'Permit' || event.remarks == 'Leaving Permit') {
              element.css('background-color', '#605ca8'); // Set background color
              element.css('border-color', '#605ca8');
            }else{
              element.css('background-color', '#f94877'); // Set background color
              element.css('border-color', '#f94877');
            }
          }else if(event.refer == 'gcal'){
            element.css('background-color', 'white'); // Set background color
            element.css('border-color', '#00c0ef');
            element.css('color', '#00c0ef');
          }else{
            element.find('.fc-time').remove();
            element.css('color', '#FFFFFF');
            if (event.status == 'Done') {
              element.css('background-color', '#00a65a'); // Set background color
              element.css('border-color', '#00a65a')
            }else if (event.status == 'Cancel') {
              element.css('background-color', '#f56954'); // Set background color
              element.css('border-color', '#f56954')
            }else if (event.status == 'Reschedule') {
              element.css('background-color', '#00c0ef'); // Set background color
              element.css('border-color', '#00c0ef')
            }else if (event.status == 'Undone') {
              element.css('background-color', '#f39c12'); // Set background color
              element.css('border-color', '#f39c12')
            }
          }
          
           // Set text color
        } 
      })
      $('#calendar').fullCalendar('addEventSource', events)
      $('#calendar').fullCalendar('rerenderEvents')
  
      if (window.location.href.split("/")[3].split("?")[1] != undefined) {
         if (window.location.href.split("/")[3].split("?")[1].split("=")[0] == "id") {
          Pace.restart()
          Pace.track(function(){
            showModalEventById()
          })
        }
      }
    }

    function showModalEventById(){
      var eventId = window.location.href.split("/")[3].split("?")[1].split("=")[1]
      var eventObj = $('#calendar').fullCalendar('clientEvents', eventId)[0];

      if (eventObj.status == null) {
        setDuration()
        setTask(eventObj.task)
        setPhase(eventObj.phase)
        setLevel()
        setStatus()
        setType()
        setSchedule()

        $(".modal-title").text("Update Timesheet")
        $("#ModalAddTimesheet").find(".modal-title").prev('button').hide()
        $("#ModalAddTimesheet").find('.modal-footer').find(".btn-danger").hide()
        $("#ModalAddTimesheet").find('.modal-footer').find(".btn-primary").removeClass("btn-primary").addClass("btn-warning").text('Update')
        $("#ModalAddTimesheet").modal("show")
        
        $('#selectSchedule').val(eventObj.schedule).trigger('change')
        $('#daterange-input').data('daterangepicker').setStartDate(moment(eventObj.start));
        $('#daterange-input').data('daterangepicker').setEndDate(moment(eventObj.end));
        $('#selectSchedule').prop("disabled",true)
        $('#daterange-input').prop("disabled",true)
        $('#selectType').prop("disabled",true)
        $('#selectLead').prop("disabled",true)
        $('#selectTask').prop("disabled",true)
        $('#selectPhase').prop("disabled",true)
        $('#selectLevel').prop("disabled",true)
        $('#selectDuration').prop("disabled",false)
        $('#selectStatus').prop("disabled",false) 
        $("#id_activity").val(eventObj.id)
        //staff
        $('#selectType').val(eventObj.type).trigger('change')
        $('#selectLead').val(eventObj.pid).trigger('change')
        $('#selectLevel').val(eventObj.level).trigger('change')
        $('#textareaActivity').val(eventObj.title).trigger('change')
        $('#selectDuration').val(eventObj.duration).trigger('change')
        $('#selectStatus').val(eventObj.status).trigger('change')
      }
    }

    function addPermit(){
      $("#ModalPermit").modal('show')
      $(".modal-title").text('Add Permit')

      // $("#inputPermitDate").daterangepicker({
      //   maxDate:currentDate
      // })
      $("#inputPermitDate").datepicker({
        daysOfWeekDisabled: [0,6],
        endDate:currentDate,
        multidate: true,
      })

      $("#selectPermit").select2({
        placeholder:"Select Permit",
        data: [{
            id: 'Sick',
            text: 'Sick'
        },
        {
            id: 'Permit',
            text: 'Permit'
        }],
      })
    }

    function storePermit(){
      if ($("#inputPermitDate").val() == "") {
        $("#inputPermitDate").closest("div").find("span").show()
        $("#inputPermitDate").closest("div").addClass("has-error")
      }else if($("#selectPermit").val() == ""){
        $("#selectPermit").closest("div").find("span").show()
        $("#selectPermit").closest("div").addClass("has-error")
      }else if($("#textareaActivityPermit").val() == ""){
        $("#textareaActivityPermit").closest("div").find("span").show()
        $("#textareaActivityPermit").closest("div").addClass("has-error")
      }else{
        // var dateRangePicker = $('#inputPermitDate').data('daterangepicker');
        // var inputPermitStartDate = dateRangePicker.startDate.format('YYYY-MM-DD');
        // var inputPermitEndDate = dateRangePicker.endDate.format('YYYY-MM-DD');
        datePermit = []
        datePermit.push($("#inputPermitDate").val().split(','))
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        // formData.append("inputPermitStartDate",inputPermitStartDate)
        // formData.append("inputPermitEndDate",inputPermitEndDate)
        formData.append("nik",nik)        
        formData.append("inputDatePermit",JSON.stringify($("#inputPermitDate").val().split(',')))
        formData.append("selectPermit",$("#selectPermit").val())        
        formData.append("textareaActivityPermit",$("#textareaActivityPermit").val())   

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this Permit Activity!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Create Timesheet for Permit Successfully!',
            text: 'Click Ok to reload page',
        } 

        var postParam = 'permit'

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/storePermit",postParam)
      }
    }

    function showHelp(params){
      if (params == 'level') {
        $("#ModalAddTimesheet").find('.modal-footer').next("div").hide()

        var appendHelp = ""
        appendHelp = appendHelp + '<div class="alert alert-default alert-dismissible">'
          appendHelp = appendHelp + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
            appendHelp = appendHelp + '<div class="form-group">'
              appendHelp = appendHelp + '<label><i class="fa fa-info"></i>) Help</label>'
              appendHelp = appendHelp + '<table class="table">'
                appendHelp = appendHelp + '<tr>'
                  appendHelp = appendHelp + '<th width="20" class="bg-info">' + 'A'
                  appendHelp = appendHelp + '</th>'
                  appendHelp = appendHelp + '<td>' + 'Pekerjaan/aktivitas yang bersifat kritikal,rumit, atau pertama kali dilakukan'
                  appendHelp = appendHelp + '</td>'
                appendHelp = appendHelp + '</tr>'
                appendHelp = appendHelp + '<tr>'
                  appendHelp = appendHelp + '<th width="20" class="bg-info">' + 'B'
                  appendHelp = appendHelp + '</th>'
                  appendHelp = appendHelp + '<td>' + 'Pekerjaan/aktivitas dengan level sulit, namun sudah pernah dilakukan sebelumnya'
                  appendHelp = appendHelp + '</td>'
                appendHelp = appendHelp + '</tr>'
                appendHelp = appendHelp + '<tr>'
                  appendHelp = appendHelp + '<th width="20" class="bg-info">' + 'C'
                  appendHelp = appendHelp + '</th>'
                  appendHelp = appendHelp + '<td>' + 'Pekerjaan/aktivitas yang sudah sering dilakukan'
                  appendHelp = appendHelp + '</td>'
                appendHelp = appendHelp + '</tr>'
                appendHelp = appendHelp + '<tr>'
                  appendHelp = appendHelp + '<th width="20" class="bg-info">' + 'D'
                  appendHelp = appendHelp + '</th>'
                  appendHelp = appendHelp + '<td>' + 'Pekerjaan/aktivitas yang setiap hari dilakukan'
                  appendHelp = appendHelp + '</td>'
                appendHelp = appendHelp + '</tr>'
                appendHelp = appendHelp + '<tr>'
                  appendHelp = appendHelp + '<th width="20" class="bg-info">' + 'E'
                  appendHelp = appendHelp + '</th>'
                  appendHelp = appendHelp + '<td>' + 'Pekerjaan/aktivitas yang membutuhkan usaha sangat sedikit / effortless'
                  appendHelp = appendHelp + '</td>'
                appendHelp = appendHelp + '</tr>'
              appendHelp = appendHelp + '</table>'
            appendHelp = appendHelp + '</div>'
        appendHelp = appendHelp + '</div>'

        $("#ModalAddTimesheet").find('.modal-footer').after(appendHelp)
      }else {
        $("#ModalAddTimesheet").find('.modal-footer').next("div").hide()

        var appendHelp = ""
        $.ajax({
          type:"GET",
          url:"{{url('/timesheet/getTaskPhaseByDivisionForTable')}}",
          success:function(result){
            console.log(result)
             appendHelp = appendHelp + '<div class="alert alert-default alert-dismissible">'
                appendHelp = appendHelp + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                  appendHelp = appendHelp + '<div class="form-group">'
                    appendHelp = appendHelp + '<label><i class="fa fa-info"></i>) Help</label>'
                    appendHelp = appendHelp + '<table class="table">'
                    $.each(result,function(index,values){
                        if (index.toLowerCase() == params) {
                          $.each(values,function(idx,value){
                            appendHelp = appendHelp + '<tr>'
                              appendHelp = appendHelp + '<th width="20" class="bg-info">' + value.title
                              appendHelp = appendHelp + '</th>'
                              appendHelp = appendHelp + '<td>' + value.description
                              appendHelp = appendHelp + '</td>'
                            appendHelp = appendHelp + '</tr>'
                          })
                        }
                    })
                    appendHelp = appendHelp + '</table>'
                        appendHelp = appendHelp + '</div>'
                    appendHelp = appendHelp + '</div>'
            $("#ModalAddTimesheet").find('.modal-footer').after(appendHelp)
          }
        })
      }
    }

    function setDuration(){
      var arrDuration = []
      for (var i = 1; i <= 480; i++) {
        if (i % 5 === 0) {
          // Action to perform on every multiple of 5
          arrDuration.push({id:i,text:i+" menit"})
          $("#selectDuration").select2({
              placeholder:"Select Duration",
              data:arrDuration,
          })
        }
      }
    }

    function setLeadId(){
      if ($("#selectLead").data('select2')) {
        // Select2 is initialized, so destroy it
        $("#selectLead").select2('destroy');
        $("#selectLead").empty()
        // Set the placeholder attribute to the desired value
        $("#selectLead").attr('placeholder','Select Lead Id')
      }
      $.ajax({
        type:"GET",
        url:"{{url('/timesheet/getLeadId')}}",
        success:function(result){
          $("#selectLead").select2({
              placeholder:"Select Lead Id",
              data:result,
          })
        }
      })
    }

    function setPid(){
      if ($("#selectLead").data('select2')) {
        $("#selectLead").select2('destroy');
        $("#selectLead").empty()
        // Select2 is initialized, so destroy it
        // Set the placeholder attribute to the desired value
        $("#selectLead").attr('placeholder','Select Project Id')
      }
      $.ajax({
        type:"GET",
        url:"{{url('/timesheet/getPidByPic')}}",
        success:function(result){
          $("#selectLead").select2({
              placeholder:"Select Project Id",
              data:result,
          })
        }
      })
    }

    function setTask(val){
      $.ajax({
        type:"GET",
        url:"{{url('/timesheet/getTaskByDivision')}}",
        success:function(result){
          var selectTask =  $("#selectTask").select2({
              placeholder:"Select Task",
              data:result,
          })
          if (val != null) {
            selectTask.val(val).trigger('change')
          }else{
            selectTask
          }
          
        }
      })
      
    }

    function setPhase(val){
      $.ajax({
        type:"GET",
        url:"{{url('/timesheet/getPhaseByDivision')}}",
        success:function(result){
          var selectPhase =  $("#selectPhase").select2({
            placeholder:"Select Phase",
            data:result,
          })
          if (val != null) {
            selectPhase.val(val).trigger('change')
          }else{
            selectPhase
          }
        }
      })
    }

    function setLevel(){
      $("#selectLevel").select2({
        placeholder:"Select Level",
        data:[
          {
            id:"A",
            text:"A"
          },
          {
            id:"B",
            text:"B"
          },
          {
            id:"C",
            text:"C"
          },
          {
            id:"D",
            text:"D"
          },
          {
            id:"E",
            text:"E"
          },
        ],
      })
    }

    function setStatus(){
      $("#selectStatus").select2({
        placeholder:"Select Status",
        data:[
          {
            id:"Done",
            text:"Done"
          },
          {
            id:"Cancel",
            text:"Cancel"
          },
          {
            id:"Reschedule",
            text:"Reschedule"
          },
          {
            id:"Undone",
            text:"Not-Done"
          },
        ],
      })
    }

    function setType(){
      $("#selectType").select2({
        placeholder:"Select Type",
        data: [{
            id: 'Project',
            text: 'Project'
        },
        {
            id: 'Internal',
            text: 'Internal'
        },{
            id: 'Approach',
            text: 'Approach'
        }],
      }).on('change', function() {
        var selectedOption = $(this).val();
        // Perform action based on the selected option
        if (selectedOption === 'Project') {
          setPid()
        } else if (selectedOption === 'Internal') {
          $("#selectLead").val(null)
          if ($("#selectLead").data("select2")) {
            $("#selectLead").empty()
            $("#selectLead").select2("destroy")
          }
          $("#selectLead").next().hide()
          $("#selectLead").closest("div").removeClass("has-error")
        } else if (selectedOption === 'Approach') {
          setLeadId()
        }
      })
    }

    function setSchedule(date){
      $("#selectSchedule").select2({
        placeholder:"Select Schedule",
        data: [{
            id: 'Planned',
            text: 'Planned'
        },
        {
            id: 'Unplanned',
            text: 'Unplanned'
        }],
      }).on('change', function() {
        var selectedOption = $(this).val();
        var disabledDates = [], disDate = ''
        $.ajax({
          type:"GET",
          url:"{{url('timesheet/getHoliday')}}",
          success:function(result){
            $.each(result,function (idx,value) {
              disabledDates.push(value.start_date)
            })
            return disDate = disabledDates
          }
        })
        // Perform action based on the selected option
        if (selectedOption === 'Planned') {
          $("#selectDuration").prev("span").remove()
          $("#selectStatus").prev("span").remove()
          $("#selectDuration").val("").trigger("change")
          $("#selectStatus").val("").trigger("change")
          $("#selectDuration").prop("disabled",true)
          $("#selectStatus").prop("disabled",true)
          // Action for Option 1
          $("#daterange-input").prop("disabled",false)
          if (date) {
              $('#daterange-input').daterangepicker({
              // startDate: moment().subtract(29, 'days'),
              minDate: tomorrow,
              startDate: tomorrow,
              endDate: tomorrow,
              isInvalidDate: function(date) {
                var formattedDate = date.format('YYYY-MM-DD');
                for (var i = 0; i < disDate.length; i++) {
                  if (isDateDisabled(formattedDate, disDate[i])) {
                    return true;
                  }
                }
                return false;
              }
              // maxDate: endDate
            })
          }else{
            $('#daterange-input').daterangepicker()
          }
          

          function isDateDisabled(date, disabledDate) {
            if (disabledDate.indexOf('/') !== -1) {
              // Date range format: start_date/end_date
              var range = disabledDate.split('/');
              return (date >= range[0] && date <= range[1]);
            } else {
              // Single date format
              return (date === disabledDate);
            }
          }
          
        } else if (selectedOption === 'Unplanned') {
          $("#selectDuration").prev("label").after("<span>*</span>")
          $("#selectStatus").prev("label").after("<span>*</span>")
          $("#selectDuration").prop("disabled",false)
          $("#selectStatus").prop("disabled",false)
          $("#daterange-input").prop("disabled",true)
          $('#daterange-input').daterangepicker({
            // startDate: moment().subtract(29, 'days'),
            startDate: date,
            endDate: date,
          })
        }
      });
    }

    function validateInput(val){
      if ($(val).is("select")) {
          if (val.value != "") {
              $(val).next().next().hide()
              $(val).closest("div").removeClass("has-error")
          }
      }else{
        $("#"+val.id).next().hide()
        $("#"+val.id).closest("div").removeClass("has-error")
      }
    }

    function saveTimesheet(){
      if ($("#selectSchedule").val() == "") {
        $("#selectSchedule").closest("div").find("span").show()
        $("#selectSchedule").closest("div").addClass("has-error")
      }else if($("#daterange-input").val() == ""){
        $("#daterange-input").closest("div").find("span").show()
        $("#daterange-input").closest("div").addClass("has-error")
      }else if($("#selectType").val() == ""){
        $("#selectType").closest("div").find("span").show()
        $("#selectType").closest("div").addClass("has-error")
      }else if($("#selectType").val() == "Project"){
        if ($("#selectLead").val() == "") {
          $("#selectLead").closest("div").find("span").show()
          $("#selectLead").closest("div").addClass("has-error")
          // $("#selectLead").closest("div").find("span").text("Please select Project ID!")
          $("#selectLead").closest("div").find(".help-block").text("Please select Project ID!")          
        }else if($("#textareaActivity").val() == ""){
          $("#textareaActivity").closest("div").find("span").show()
          $("#textareaActivity").closest("div").addClass("has-error")
        }else{
          storeTimesheet()
        }
      }else if($("#selectType").val() == "Approach"){
        if ($("#selectLead").val() == "") {
          $("#selectLead").closest("div").find("span").show()
          $("#selectLead").closest("div").addClass("has-error")
          $("#selectLead").closest("div").find(".help-block").text("Please select Lead ID!")
        }else if($("#textareaActivity").val() == ""){
          $("#textareaActivity").closest("div").find("span").show()
          $("#textareaActivity").closest("div").addClass("has-error")
        }else{
          storeTimesheet()
        }
      }else if($("#textareaActivity").val() == ""){
        $("#textareaActivity").closest("div").find("span").show()
        $("#textareaActivity").closest("div").addClass("has-error")
      }else{
        if ($("#selectSchedule").val() == 'Unplanned') {
          if ($("#selectDuration").val() == '') {
            $("#selectDuration").closest("div").find("span").show()
            $("#selectDuration").closest("div").addClass("has-error")
          }else if ($("#selectStatus").val() == '') {
            $("#selectStatus").closest("div").find("span").show()
            $("#selectStatus").closest("div").addClass("has-error")
          }else{
            storeTimesheet()
          }
        }else{
          storeTimesheet()
        }
      }
      // timesheet/addTimesheet      
      function storeTimesheet(){
        var dateRangePicker = $('#daterange-input').data('daterangepicker');
        var startDate = dateRangePicker.startDate.format('YYYY-MM-DD');
        var endDate = dateRangePicker.endDate.format('YYYY-MM-DD');

        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("selectSchedule",$("#selectSchedule").val())
        formData.append("startDate",startDate)
        formData.append("endDate",endDate)    
        formData.append("selectType",$("#selectType").val())        
        formData.append("selectLead",$("#selectLead").val())        
        formData.append("selectTask",$("#selectTask").val())  
        formData.append("selectPhase",$("#selectPhase").val())        
        formData.append("selectLevel",$("#selectLevel").val())        
        formData.append("textareaActivity",$("#textareaActivity").val())        
        formData.append("selectDuration",$("#selectDuration").val())        
        formData.append("selectStatus",$("#selectStatus").val())
        formData.append("id_activity",$("#id_activity").val()) 

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this Timesheet!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Create Timesheet Succesfully!',
            text: 'Click Ok to reload page',
        } 

        var postParam = 'timesheet'

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/addTimesheet",postParam)
      }
    }

    function createPost(swalFireCustom,data,swalSuccess,url,postParam){
      var isUpdate = false
      if ($("#ModalAddTimesheet").find('.modal-footer').find(".btn-primary")) {
        isUpdate = isUpdate
        localStorage.setItem('isUpdate',isUpdate)

      }else{
        isUpdate = true
        localStorage.setItem('isUpdate',isUpdate)

      }

      Swal.fire(swalFireCustom).then((resultFire) => {
        if (resultFire.value) {
          $.ajax({
            type:"POST",
            url:"{{url('/')}}"+url,
            processData: false,
            contentType: false,
            data:data,
            beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
              })
              Swal.showLoading()
            },
            success: function(results)
            {
              Swal.fire(swalSuccess).then((result,data) => {
                if (result.value) {
                  if (postParam == 'timesheet') {
                    var newEvents = {
                      title:results.activity,
                      start:results.start_date,
                      end:moment(results.end_date).endOf('day'),
                      id:results.id,
                      type:results.type,
                      task:results.task,
                      schedule:results.schedule,
                      pid:results.pid,
                      phase:results.phase,
                      level:results.level,
                      duration:results.duration,
                      status:results.status,
                    }

                      loadData()
                      // Call the refetchEvents method to reload the events
                      if(localStorage.getItem('isUpdate') == false) {
                        $('#calendar').fullCalendar('renderEvent', newEvents, true);
                        $('#calendar').fullCalendar('refetchEvents');
                      }else{
                        $('#calendar').fullCalendar('clientEvents', results.id)[0];
                        $('#calendar').fullCalendar('removeEvents', results.id)
                        $('#calendar').fullCalendar('renderEvent', newEvents);
                        if (window.location.href.split("/")[3].split("?")[1] != undefined) {
                          if (window.location.href.split("/")[3].split("?")[1].split("=")[0] == "id") {
                            history.replaceState(null, '', "{{url('timesheet')}}")
                          }
                        }
                      }
                      // Render the updated event on the calendar
                    $("#ModalAddTimesheet").modal('hide')
                  }else{
                    var newEvents = []
                    $.each(results,function(idx,value){
                      newEvents.push({"title":value.activity,"start":value.start_date,"end":value.start_date,"id":value.id,"remarks":value.status})
                    })   

                      loadData()
                      newEvents.forEach(function(event) {  
                          $('#calendar').fullCalendar('renderEvent', event, true);
                          $('#calendar').fullCalendar('refetchEvents');
                      })              
                    $("#ModalPermit").modal('hide')
                  }
                }
              })
            }
          })
        }
      })
    }

    function showAlertRemaining(){
      $.ajax({
        type:"GET",
        url:"{{url('timesheet/getPercentage')}}",
        success:function(result){
          $("#alertForRemaining").show()
          $($("#alertForRemaining").find("span")[0]).text(result.name)
          $($("#alertForRemaining").find("span")[1]).text(result.percentage)
        }
      })
    }
    
  </script>
@endsection