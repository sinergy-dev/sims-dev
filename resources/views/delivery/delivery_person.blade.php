@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Delivery Person Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Delivery Person</li>
    <li class="active">SIP</li>
  </ol>
</section>

<section class="content">
	<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i>&nbsp<b>Delivery Person & Messenger</b></h3>

      </div>

      <div class="box-body">
        <div style="margin-bottom: 10px">
          <button class="btn btn-success btn-sm add-messenger" style="width: 150px;"><i class="fa fa-plus"></i> Booking Messenger</button>
        </div>
        
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs" id="myTab">
                    <li class="nav-item active">
                        <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('today')">
                            Today
                        </a>
                    </li>
                    <li>
                      <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('done')">
                            Done
                        </a>
                    </li>
                    <li>
                      <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('requested')">
                            Requested
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active"  role="tabpanel" >
                    <div class="table-responsive">
                       <table class="table table-bordered table-striped dataTable" id="data_messenger" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th colspan="6">Tuesday, April 7 2020</th>
                          </tr>
                          <tr>
                            <th>No</th>
                            <th>Messenger Name</th>
                            <th>Activity</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                            <th>PIC</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0?>
                          @foreach($data as $data)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>{{$data->name}}</td>
                            <td><span data-toggle="tooltip" title="Klik for detail" style="cursor: pointer" data-placement="bottom"><a href="{{url('/detail_delivery_person')}}">{{$data->activity}}</a></span></td>
                            <td>{{$data->book_date}} {{$data->book_time}}</td>
                            <td>{{$data->lokasi}}}</td>
                            <td>{{$data->pic_name}} ({{$data->pic_contact}})</td>
                            <td>
                              <button class="btn btn-sm btn-primary fa fa-edit" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="btn-edit" data-toggle="tooltip" title="Edit" data-placement="bottom" value="" type="button"></button>
                              <a href=""><button class="btn btn-sm btn-danger fa fa-trash" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" onclick="return confirm('Are you sure want to delete this?')" data-toggle="tooltip" title="Delete" data-placement="bottom" type="button"></button></a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                       </table>
                    </div>
                  </div>
                </div>
            </div>
          </div>
         <!--  <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="table-responsive">
               <table class="table table-bordered table-striped dataTable" id="data_all" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <td>Messenger Name</td>
                    <td>Status</td>
                    <td>Avaibility</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Arifin</td>
                    <td>Available</td>
                    <td>4</td>
                  </tr>
                  <tr>
                    <td>Angga</td>
                    <td>Available</td>
                    <td>5</td>
                  </tr>
                </tbody>
               </table>
            </div>
          </div> -->
        </div>
      	
      </div>

    </div>

    <!--modal add-->
    <div class="modal fade" id="modaltambahmessenger" role="dialog">
      <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Add Booking Messenger</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="" name="">
              @csrf

              <div class="form-group">
                  <label>Booking Date</label>
                  <div class='input-group date' id='book_date'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <!-- <input type="text" class="form-control" id="book_date" name="book_date" autocomplete="Off" required> -->
              </div>

              <div class="form-group">
                <label>Booking Time</label>
                <div class='input-group date' id='datetimepicker1'>
                  <input type='text' class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
              </div>

              <table class="table table-striped table-bordered">
               <tbody id="tb_available">
                 
               </tbody>
              </table>

              <div class="form-group">
                <label>Messenger Name</label>
                <select class="form-control" id="messenger" name="messenger" style="width: 100%">
                  <option>Select Messenger</option>
                  <option value="">Arifin</option>
                  <option>Angga</option>
                </select>
              </div>

              <div class="form-group">
                <label>PIC Name</label>
                <input type="text" class="form-control" name="pic_name" placeholder="Isikan Nama PIC">
              </div>

              <div class="form-group">
                <label>PIC Contact</label>
                <input type="text" class="form-control" name="pic_kontak" placeholder="Isikan nomor HP/Telp PIC yang dapat dihubungi">
              </div>

              <div class="form-group">
                <label>Lokasi Tujuan</label><br>
                  <textarea class="form-control" id="location" name="location"></textarea>
              </div>

              <div class="form-group">
                <label>Item</label><br>
                  <textarea class="form-control" id="items" name="items"></textarea>
              </div>

              <div class="form-group">
                <label>Activity</label><br>
                  <textarea class="form-control" id="activity" name="activity"></textarea>
              </div>

              <div class="form-group">
                <label>Note</label>
                <textarea class="form-control" type="text" id="note" name="note"></textarea>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-check"> </i>&nbspSubmit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
    </div>
</section>


@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script type="text/javascript">
 $('#datetimepicker1').datetimepicker({
    format: 'LT'
 })
    

  var hari_libur_nasional = []
  var hari_libur_nasional_tooltip = []
  $.ajax({
    type:"GET",
    url:"https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key={{env('GOOGLE_API_YEY')}}",
    success: function(result){
      $.each(result.items,function(key,value){
        hari_libur_nasional.push(moment( value.start.date).format("MM/DD/YYYY"))
        hari_libur_nasional_tooltip.push(value.summary)
      })
    }
  })

  $(".add-messenger").click(function(){
    $.ajax({
        type:"GET",
        url:"getDateMessenger",
        data:{
          nik:this.value,
        },
        success: function(result){
        var disableDate = []
        $.each(result.allCutiDate,function(key,value){
          disableDate.push(moment( value).format("MM/DD/YYYY"))
        })

        var today = new Date();
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);
        $('#book_date').datepicker({
          autoclose: true,
          weekStart: 1,
          daysOfWeekDisabled: [0,6],
          daysOfWeekHighlighted: [0,6],
          startDate: moment(tomorrow).format("MM/DD/YYYY"),
          datesDisabled: disableDate,
          beforeShowDay: function(date){
              var index = hari_libur_nasional.indexOf(moment(date).format("MM/DD/YYYY"))
              if(index > 0){
                return {
                  enabled: false,
                  tooltip: hari_libur_nasional_tooltip[index],
                  classes: 'hari_libur'
                };
              } else if(disableDate.indexOf(moment(date).format("MM/DD/YYYY")) > 0) {
                return {
                  enabled: false,
                  tooltip: 'Cuti Pribadi Messenger',
                };
              }
            },
        }).on('changeDate', function(e) {
          $.ajax({
            type:"GET",
            url:"{{url('getMessenger')}}",
            data:{
              tanggal:moment(e.date).format("YYYY-MM-DD"),
            },
            success: function(result){
              $('#tb_available').empty();
                  var table = "";
                  table = table + '<tr>';
                  table = table + '<th colspan="3">' + "List Messenger" + '</th>';
                  table = table + '</tr>';
              if (result[0] == "null") {
                  table = table + '<tr>';
                  table = table + '<td>' + "Tes" + '</td>';
                  table = table + '<td>' + "Available" + '</td>';
                  table = table + '<td>' + "tes" + '</td>';
                  table = table + '</tr>';  
              }else{
                  $.each(result[0], function(key, value){
                    table = table + '<tr>';
                    table = table + '<td>' + value.name + '</td>';
                    table = table + '<td>' + "Available" + '</td>';
                    table = table + '<td>' + parseInt(5 - value.book_date) + '</td>';
                    table = table + '</tr>';             
                  });
              }
              $('#tb_available').append(table);
                            
            }
          })

        });
      }
    });
    
    $("#modaltambahmessenger").modal("show");
  })

  $('#data_messenger').DataTable({
  })

  $('#messenger').select2();  
</script>
@endsection