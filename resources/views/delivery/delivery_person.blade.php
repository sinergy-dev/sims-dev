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
                        <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTabMessenger('today')">
                            Today
                        </a>
                    </li>
                    <li>
                      <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTabMessenger('done')">
                            Done
                        </a>
                    </li>
                    <li>
                      <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTabMessenger('requested')">
                            Plan Schedule
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active"  role="tabpanel" >
                    <div class="table-responsive">
                       <table class="table table-bordered table-striped dataTable" id="data_messenger" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Messenger Name</th>
                            <th>Activity</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                            <th>PIC</th>
                            <th>Requested By</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                       </table>
                    </div>
                  </div>
                </div>
            </div>
          </div>
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
            <form method="POST" action="{{url('/store_messenger')}}">
              @csrf

              <div class="form-group">
                  <label>Booking Date</label>
                  <div class='input-group date' id='book_date'>
                    <input type='text' class="form-control" name="book_date" required />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
              </div>

              <div class="form-group">
                <label>Booking Time</label>
                <div class='input-group date' id='datetimepicker1'>
                  <input type='text' class="form-control" name="book_time" required autocomplete="false" />
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
                <label>Available Messenger</label>
                <select class="form-control" id="messenger" name="messenger_name" style="width: 100%" required>
                </select>
              </div>

              <div class="form-group">
                <label>PIC Name</label>
                <input type="text" class="form-control" name="pic_name" placeholder="Isikan Nama PIC" required>
              </div>

              <div class="form-group">
                <label>PIC Contact</label>
                <input type="text" class="form-control" name="pic_contact" placeholder="Isikan nomor HP/Telp PIC yang dapat dihubungi" required>
              </div>

              <div class="form-group">
                <label>Lokasi Tujuan</label><br>
                  <textarea class="form-control" id="location" name="lokasi" required></textarea>
              </div>

              <div class="form-group">
                <label>Item</label><br>
                  <textarea class="form-control" id="items" name="items" required></textarea>
              </div>

              <div class="form-group">
                <label>Activity</label><br>
                  <textarea class="form-control" id="activity" name="activity" required></textarea>
              </div>

              <div class="form-group">
                <label>Note</label>
                <textarea class="form-control" type="text" id="note" name="note" required></textarea>
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

    <!--modal edit-->
    <div class="modal fade" id="modaleditmessenger" role="dialog">
      <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Edit Booking Messenger</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/update_messenger')}}">
              @csrf

              <input type="" name="id_messenger" id="id_messenger">

              <div class="form-group">
                  <label>Booking Date Before</label>
                  <div class='input-group date'>
                    <input type='text' class="form-control" id="book_date_update" readonly />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
              </div>

              <div class="form-group">
                  <label>Booking Date</label>
                  <div class='input-group date' id='book_date_edit'>
                    <input type='text' class="form-control" name="book_date_edit" id="book_date_edit" required />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
              </div>

              <div class="form-group">
                <label>Booking Time</label>
                <div class='input-group date' id='datetimepicker1_edit'>
                  <input type='text' class="form-control" name="book_time_edit" id="book_time_edit" required />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
              </div>

              <table class="table table-striped table-bordered">
               <tbody id="tb_available_edit">
                 
               </tbody>
              </table>

              <div class="form-group">
                <label>Available Messenger <span class="label label-danger">Sebelum submit jangan lupa pilih Messenger yang tersedia</span></label>
                <select class="form-control" id="messenger_edit" name="messenger_name_edit" style="width: 100%" required>
                </select>
              </div>

              <div class="form-group">
                <label>PIC Name</label>
                <input type="text" class="form-control" name="pic_name_edit" id="pic_name_edit" placeholder="Isikan Nama PIC" required>
              </div>

              <div class="form-group">
                <label>PIC Contact</label>
                <input type="text" class="form-control" name="pic_contact_edit" id="pic_contact_edit" placeholder="Isikan nomor HP/Telp PIC yang dapat dihubungi" required>
              </div>

              <div class="form-group">
                <label>Lokasi Tujuan</label><br>
                  <textarea class="form-control" id="lokasi_edit" name="lokasi_edit" required></textarea>
              </div>

              <div class="form-group">
                <label>Item</label><br>
                  <textarea class="form-control" id="items_edit" name="items_edit" required></textarea>
              </div>

              <div class="form-group">
                <label>Activity</label><br>
                  <textarea class="form-control" id="activity_edit" name="activity_edit" required></textarea>
              </div>

              <div class="form-group">
                <label>Note</label>
                <textarea class="form-control" type="text_edit" id="note_edit" name="note_edit" required></textarea>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<script type="text/javascript">
  initialMessenger();
  
  var nik = "{{Auth::User()->nik}}"
  function initialMessenger(){
    $("#data_messenger").DataTable({
          "ajax":{
            "type":"GET",
            "url":"{{url('getDataMessenger')}}",
          },
          "columns": [
            { "data": "name1" },
            { "data": "activity" },
            {
              render: function ( data, type, row ) {
                return row.book_date + ' ' + row.book_time;
              }
            },
            { "data": "lokasi" },
            {
              render: function ( data, type, row ) {
                return row.pic_name + ' ' + "(" + row.pic_contact + ')';
              }
            },
            {
              render: function ( data, type, row ) {
                return row.name2 + ' ' + "(" + row.id_division + ')';
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.nik_request == {{Auth::User()->nik}}) {
                  return '<button value="'+row.id_messenger+'" class="btn btn-sm btn-danger fa fa-trash btn-delete" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Delete" data-placement="bottom" type="button"></button>'+ ' ' +'<button class="btn btn-sm btn-warning fa fa-info btn-detail" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Klik for more detail" data-placement="bottom" type="button" value="'+row.id_messenger+'"></button>'
                }else{
                  return '<button class="btn btn-sm btn-warning fa fa-info btn-detail" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Klik for more detail" data-placement="bottom" type="button" value="'+row.id_messenger+'"></button>'
                }
                
              }
            },
            
          ],
          "info":false,
          "scrollX": false,
          "order": [[ 3, "asc" ]],
          "processing": true,
          "columnDefs": [
              { 
                "width": "5%","targets": 0,
                "width": "10%", "targets": 2,
                "width": "5%", "targets": 3,
                "width": "5%", "targets": 4,
                "width": "15%", "targets": 5,
              }
          ],
    
    })

    $('#data_messenger').on('click', '.btn-detail', function(){
        window.location.replace("{{url('/detail_delivery_person')}}/" + this.value)
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
                    table = table + '<tr>';
                    table = table + '<th >' + "Nama" + '</th>';
                    table = table + '<th >' + "Status" + '</th>';
                    table = table + '<th >' + "Sisa slot" + '</th>';
                    table = table + '</tr>';
                if (result[1] == "courier") {
                    $.each(result[0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + "5" + '</td>';
                      table = table + '</tr>'; 
                    }); 

                    $('#messenger').html(append)
                    var append = "<option>-- Select Messenger --</option>";

                    $.each(result[0], function(key, value){
                      if (value.book_date != 5) {
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                      }
                    });
                  
                    $('#messenger').html(append);
                }else{
                    $.each(result[0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + parseInt(5 - value.book_date) + '</td>';
                      table = table + '</tr>';             
                    });
                    $.each(result[1][0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + "5" + '</td>';
                      table = table + '</tr>'; 
                    }); 

                    $('#messenger').html(append)
                    var append = "<option>-- Select Messenger --</option>";

                    $.each(result[0], function(key, value){
                      if (value.book_date != 5) {
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                      }
                    });
                    $.each(result[1][0], function(key, value){
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                    });
                  
                    $('#messenger').html(append);
                }
                $('#tb_available').append(table);
              }
            })

          });
        }
      });
      
      $("#modaltambahmessenger").modal("show");
    });

    $('#data_messenger').on('click', '.edit-messenger', function(){
      $.ajax({
          type:"GET",
          url:"getMessenger",
          data:{
            id_messenger:this.value,
          },
          success: function(result){
          var disableDate = []
          $.each(result.allCutiDate,function(key,value){
            disableDate.push(moment( value).format("MM/DD/YYYY"))
          })

          var today = new Date();
          var tomorrow = new Date();
          tomorrow.setDate(today.getDate() + 1);

          console.log(result)
          $.each(result[3], function(key, value){
            $("#book_date_update").datepicker({format: 'yyyy-mm-dd'}).datepicker('setDate', value.book_date);
            $("#book_time_edit").val(value.book_time);
            $("#pic_name_edit").val(value.pic_name);
            $("#pic_contact_edit").val(value.pic_contact);
            $("#lokasi_edit").val(value.lokasi);
            $("#items_edit").val(value.item);
            $("#activity_edit").val(value.activity);
            $("#note_edit").val(value.note);
            $("#id_messenger").val(value.id_messenger);
          })

          
          $('#book_date_edit').datepicker({
            setDate: result[3].book_date,
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
                $('#tb_available_edit').empty();
                    var table = "";
                    table = table + '<tr>';
                    table = table + '<th colspan="3">' + "List Messenger" + '</th>';
                    table = table + '</tr>';
                    table = table + '<tr>';
                    table = table + '<th >' + "Nama" + '</th>';
                    table = table + '<th >' + "Status" + '</th>';
                    table = table + '<th >' + "Sisa slot" + '</th>';
                    table = table + '</tr>';
                if (result[1] == "courier") {
                    $.each(result[0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + "5" + '</td>';
                      table = table + '</tr>'; 
                    }); 

                    $('#messenger_edit').html(append)
                    var append = "<option>-- Select Messenger --</option>";

                    $.each(result[0], function(key, value){
                      if (value.book_date != 5) {
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                      }
                    });
                  
                    $('#messenger_edit').html(append);
                }else{
                    $.each(result[0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + parseInt(5 - value.book_date) + '</td>';
                      table = table + '</tr>';             
                    });
                    $.each(result[1][0], function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' + value.name + '</td>';
                      table = table + '<td>' + "Available" + '</td>';
                      table = table + '<td>' + "5" + '</td>';
                      table = table + '</tr>'; 
                    }); 

                    $('#messenger_edit').html(append)
                    var append = "<option>-- Select Messenger --</option>";

                    $.each(result[0], function(key, value){
                      if (value.book_date != 5) {
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                      }
                    });
                    $.each(result[1][0], function(key, value){
                        append = append + "<option value='"+value.nik+"'>" + value.name + "</option>";
                    });
                  
                    $('#messenger_edit').html(append);
                }
                $('#tb_available_edit').append(table);
              }
            })

          });
        }
      });

      $('#messenger_edit').select2();  

      $('#datetimepicker1_edit').datetimepicker({
        format: 'HH:mm:ss'
      })
      
      $("#modaleditmessenger").modal("show");
    });

    $('#data_messenger').on('click', '.btn-delete', function(){
      var id_messenger = this.value;
        $.ajax({
          type:"GET",
          url:"{{url('delete_messenger/')}}/"+id_messenger,
          beforeSend:function(){
            return confirm("Want to delete?") 
          },
          success: function(result){
              setTimeout(function(){
                $('#data_messenger').DataTable().ajax.url("{{url('getDataMessenger')}}").load();
              });
          }
      })
    });
    

    $('#messenger').select2();  

    $('#datetimepicker1').datetimepicker({
    format: 'HH:mm:ss'
    })
      
  
  }

  function changeTabMessenger(id){
    if (id == 'done') {
      $('#data_messenger').DataTable().ajax.url("{{url('getDataMessenger')}}?id=" + id).load();

      $('#data_messenger').on('click', '.btn-detail', function(){

        window.location.replace("{{url('/detail_delivery_person')}}/" + this.value)

      })
    }else if (id == 'requested') {
      $('#data_messenger').DataTable().ajax.url("{{url('getDataMessenger')}}?id=" + id).load();

      $('#data_messenger').on('click', '.btn-detail', function(){
        window.location.replace("{{url('/detail_delivery_person')}}/" + this.value)

      })
    }else if (id == 'today') {
      $('#data_messenger').DataTable().ajax.url("{{url('getDataMessenger')}}?id=" + id).load();

      $('#data_messenger').on('click', '.btn-detail', function(){
        window.location.replace("{{url('/detail_delivery_person')}}/" + this.value)

      })
    }
  }
  
    

  
</script>
@endsection