@extends('template.main')
@section('tittle')
Quote Number
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <style type="text/css">
    .DTFC_LeftBodyLiner {
      overflow: hidden;
    }
    th{
      text-align: center;
    }
    td>.truncate{
      /*word-wrap: break-word; */
      word-break:break-all;
      white-space: normal;
      width:200px;    
    }
    @media screen and (max-width: 768px) {
      .btn-action-letter{
        float: left!important;
      }
    }
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Daftar Buku Admin (Quote Number)
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">Quote Number</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
        @endif

        @if (session('success'))
          <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops->quote_number}}</h4></div>
        @endif

        @if (session('sukses'))
          <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops2->quote_number}}</h4></div>
        @endif

        @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i> Quote Number</h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-2 col-xs-12">
            <div class="form-group">
              <select style="margin-right: 5px;width: 100px" class="form-control btn-primary btn-flat" id="year_filter">
                  <option value="{{$tahun}}"> &nbsp{{$tahun}}</option>
                  @foreach($year_before as $years)
                    @if($years->year != $tahun)
                      <option value="{{$years->year}}"> &nbsp{{$years->year}}</option>
                    @endif
                  @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-10 col-xs-12">
            <div class="form-group btn-action-letter" style="float:right;">
              <button type="button" class="btn btn-success btn-flat" style="width: 100px" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd Quote</button>
              @if($counts)
              <button type="button" class="btn btn-success btn-flat" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
              @else
              <button type="button" class="btn btn-success btn-flat disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
              @endif
            </div>
          </div>
        </div>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs" id="myTab">
            @foreach($status_quote as $data)
                @if($data->status_backdate == 'A')
                    <li class="nav-item active">
                        <a class="nav-link active" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')">All</a>
                @elseif($data->status_backdate == 'F')
                    <li class="nav-item">
                        <a class="nav-link" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')"> Backdate
                @endif
                        </a>
                    </li>
            @endforeach
          </ul>

          <div class="tab-content">

            <div class="tab-pane active" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-bordered nowrap table-striped dataTable data" id="data_all" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Quote Number</th>
                      <th>Position</th>
                      <!-- <th>Type of Letter</th> -->
                      <th>Month</th>
                      <th>Date</th>
                      <th class="truncate">To</th>
                      <th class="truncate">Attention</th>
                      <th class="truncate">Title</th>
                      <th class="truncate">Project</th>
                      <th class="truncate">Description</th>
                      <th>From</th>
                      <th>Division</th>
                      <th>Project ID</th>
                      <th>Project Type</th>
                      <th>Note</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!--MODAL ADD-->  
    <div class="modal fade" id="modalAdd" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content modal-md">
              <div class="modal-header">
                <h4 class="modal-title">Add Quote</h4>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{url('/quote/store')}}" id="modalAddQuote" name="modalAddQuote">
                  @csrf
                  
                  <div class="form-group">
                      <label>Position</label>
                      <select class="form-control" id="position" name="position" required>
                          <option value="">--Choose Position--</option>
                          <option value="TAM">TAM</option>
                          <option value="DIR">DIR</option>
                          <option value="MSM">MSM</option>
                          <option value="BCD">BCD</option>
                      </select>
                  </div>

                  <div class="form-group">
                    <label for="">Date*</label>
                    <input type="date" class="form-control" name="date" id="date_quote" required>
                  </div>

                  <div class="form-group">
                    <label>Customer</label>
                    <select class="form-control" id="customer_quote" name="customer_quote" required style="width: 100%">
                      @foreach($customer as $data)
                      <option value="{{$data->id_customer}}">{{$data->customer_legal_name}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                      <label>Attention</label>
                      <input class="form-control" placeholder="Enter Attention" id="attention" name="attention" >
                  </div>

                  <div class="form-group">
                      <label>Title</label>
                      <input class="form-control" placeholder="Enter Title" id="title" name="title" >
                  </div>

                  <div class="form-group">
                      <label>Project</label>
                      <input class="form-control" placeholder="Enter Project" id="project" name="project" >
                  </div>         
                  <div class="form-group">
                      <label for="">Description</label>
                      <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Division</label>
                    <select type="text" class="form-control" name="division" id="division" required>
                        <option value="">--Choose Division--</option>
                        <option>PMO</option>
                        <option>MSM</option>
                        <option>SAL</option>
                        <option>BCD</option>
                    </select>
                  </div>
                  <!-- <div class="form-group">
                    <label for="">Project ID</label>
                    <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
                  </div> -->
                  <div class="form-group" id="pid">
                    <label for="">Project ID</label>                
                    <select type="text" class="form-control select2" name="project_id" id="project_id" style="width: 100%">
                      <option value="">--Choose Project Id--</option>
                      @foreach($pid as $data)
                      <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Project Type</label>
                    <select class="form-control" id="project_type" name="project_type" required style="width: 100%">
                      <option value="">--Choose Project Type--</option>
                      <option value="Supply Only">Supply Only</option>
                      <option value="Maintenance">Maintenance</option>
                      <option value="Implementation">Implementation</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
                  </div>
              </form>
              </div>
            </div>
          </div>
    </div>

    <!-- BACKDATE -->
    <div class="modal fade" id="letter_backdate" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content modal-md">
            <div class="modal-header">
              <h4 class="modal-title">Add Quote (Backdate)</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('/store_quotebackdate')}}" id="quote_backdate" name="quote_backdate">
                @csrf
              <div class="form-group">
                <label for="">Date</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right date" name="date" id="date_backdate">
                </div>
              </div>

              <div class="form-group">
                <label>Backdate Number</label>
                <select type="text" class="form-control disabled" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" disabled>
                </select>
                <span id="errorname" style="color:red"></span>
                <span class="pull-right" style="display:none;cursor: pointer;" id="addBackdateNum"><i class="fa fa-plus"></i> backdate number</span>
              </div>
              <div class="form-group">
                <label for="">Position</label>
                <select type="text" class="form-control" name="position" id="position" required>
                    <option value="">--Choose Position--</option>
                    <option value="TAM">TAM</option>
                    <option value="DIR">DIR</option>
                    <option value="MSM">MSM</option>
                    <option value="BCD">BCD</option>
                </select>
              </div>
              <div class="form-group">
                <label>Customer</label>
                <select class="form-control" id="customer_quote_backdate" name="customer_quote_backdate" required style="width: 100%">
                  @foreach($customer as $data)
                  <option value="{{$data->id_customer}}">{{$data->customer_legal_name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="">Attention</label>
                <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention">
              </div> 
              <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title">
              </div>
              <div class="form-group">
                <label for="">Project</label>
                <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project">
              </div>
              <div class="form-group">
                <label for="">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
              </div>
              <div class="form-group">
                <label for="">Division</label>
                <select type="text" class="form-control" name="division" id="division" required>
                    <option value="">--Choose Division--</option>
                    <option>PMO</option>
                    <option>MSM</option>
                    <option>SAL</option>
                    <option>BCD</option>
                </select>
              </div>
              <!-- <div class="form-group">
                <label for="">Project ID</label>
                <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
              </div> -->
              <div class="form-group" id="pid">
                <label for="">Project ID</label>                
                <select type="text" class="form-control select2" name="project_id_backdate" id="project_id_backdate" style="width: 100%">
                  <option value="">--Choose Project Id--</option>
                  @foreach($pid as $data)
                  <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Project Type</label>
                <select class="form-control" id="project_type" name="project_type" required style="width: 100%">
                  <option value="">--Choose Project Type--</option>
                  <option value="Supply Only">Supply Only</option>
                  <option value="Maintenance">Maintenance</option>
                  <option value="Implementation">Implementation</option>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary" id="addBackdate"><i class="fa fa-check"> </i>&nbspSubmit</button>
              </div>
            </form>
            </div>
          </div>
        </div>
    </div>

    <!--MODAL EDIT-->  
    <div class="modal fade" id="modalEdit" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Edit Quote</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/quote/update')}}" id="modalEditQuote" name="modalQuote">
              @csrf
              <div class="form-group" hidden>
                  <label>Quote Number</label>
                  <input class="form-control" id="edit_quote_number" name="quote_number">
              </div>
              <div class="form-group">
                  <label>Position</label>
                  <select class="form-control" id="edit_position" name="edit_position" required>
                      <option value="TAM">TAM</option>
                      <option value="DIR">DIR</option>
                      <option value="MSM">MSM</option>
                      <option value="BCD">BCD</option>
                  </select>
              </div>

              <div class="form-group">
                <label for="">Date</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="edit_date" id="edit_date">
                </div>
              </div>
              <div class="form-group">
                  <label>To</label>
                  <select id="edit_to" name="edit_to" class="form-control" style="width:100%"></select>
                  <!-- <input class="form-control" id="edit_to" placeholder="Enter To" name="edit_to" > -->
              </div>

              <div class="form-group">
                  <label>Attention</label>
                  <input class="form-control" id="edit_attention" placeholder="Enter Attention" name="edit_attention" >
              </div>

              <div class="form-group">
                  <label>Title</label>
                  <input class="form-control" id="edit_title" placeholder="Enter Title" name="edit_title" >
              </div>

              <div class="form-group">
                  <label>Project</label>
                  <input class="form-control" id="edit_project" name="edit_project" placeholder="Enter Project">
              </div> 
              <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" id="edit_description" name="edit_description" placeholder="Enter Description"></textarea>
              </div>        
              <div class="form-group">
                  <label>Project ID</label>
                  <input class="form-control" id="edit_project_id" name="edit_project_id" placeholder="Enter Project ID">
              </div> 
              <div class="form-group">
                  <label>Note</label>
                  <input class="form-control" id="edit_note" name="edit_note" placeholder="Enter Note">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
                <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspUpdate</button> -->
              </div>
          </form>
          </div>
        </div>
      </div>
    </div>  
  </section>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#addBackdate').prop("disabled",true)
    })
    $('#customer_quote').select2();
    // $("#backdate_num").select2();
    $("#customer_quote_backdate").select2();
    $('#date_backdate').datepicker({
      autoclose: true,
    }).css('background-color','#fff');

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    var datePickerId = document.getElementById('date_quote')
    datePickerId.min = new Date().toISOString().split("T")[0];

    $('#project_id').select2({
      dropdownParent:$("#modalAdd")
    })

    $('#project_id_backdate').select2({
      dropdownParent:$("#letter_backdate")
    })

    function backdateReload(){
      $.ajax({
        url: "/get_backdate_num",
        type: "GET",
        data:{
          tanggal:$('#date_backdate').val()
        },
        success: function(result) {
          $("#backdate_num").prop("disabled",false);
          $("#errorname").css("display","none")
          $('#addBackdate').prop("disabled",false)
          $("#addBackdateNum").css("display","none")
          $("#backdate_num").select2({
            data:result.results
          })
        }
      })       
    }

    $('#date_backdate').change(function (argument) {
      errorMessage = document.getElementById('errorname');
      $.ajax({
          url: "/get_backdate_num",
          type: "GET",
          data:{
            tanggal:$('#date_backdate').val()
          },
          success: function(result) {
            console.log(result)
            if (result.results.length === 0) {
              $('#backdate_num').empty().trigger("change");
              $('#addBackdate').prop("disabled",true)
              $("#backdate_num").prop("disabled",true); 
              errorMessage.innerText = 'Backdate Number is Not Available';
              $("#addBackdateNum").css("display","block")
              $("#addBackdateNum").click(function(){
                Swal.fire({
                    title: 'Create a backdate number',  
                    text: "By pressing `OK`, the system will creating the backdate number",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'No',
                }).then((result) => {
                  if (result.value) {
                      Swal.fire({
                          title: 'Please Wait..!',
                          text: "It's sending..",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: false,
                          customClass: {
                              popup: 'border-radius-0',
                          },
                          onOpen: () => {
                              Swal.showLoading()
                          }
                      })
                      $.ajax({
                          type: "POST",
                          url: "{{url('/addBackdateNumQuote')}}",
                          data: {
                            _token: "{{ csrf_token() }}",
                            date_backdate:$("#date_backdate").val()
                          },
                          success: function(result) {
                              Swal.showLoading()
                              Swal.fire(
                                  'Successfully!',
                                  'Backdate Number have been Created.',
                                  'success'
                              ).then((result) => {
                                  if (result.value) {
                                    backdateReload()
                                  }
                              })
                          }
                      })          
                  }
                })
              })
            } else {
              console.log("ada results")
              $('#addBackdate').prop("disabled",false)
              $("#backdate_num").prop("disabled",false);
              $("#backdate_num").select2({
                data:result.results
              })             
            }
          }
      })
    })

    function edit_quote(quote_number,id_customer,attention,title,project,description,project_id,note,date,position) {
      console.log(id_customer)
      $('#modalEdit').modal('show');
      $('#edit_quote_number').val(quote_number);
      if (id_customer == "null") {
        ''
      } else {
         $.ajax({
            url: "{{url('/quote/getCustomer')}}",
            type: "GET",
            success: function(result) {
              $('#edit_to').select2({
                  placeholder:"Select Customer",
                  data:result.data
              })
              $("#edit_to").val(id_customer).trigger("change")
            }
        })
        // $('#edit_to').val(customer_legal_name);
      }
      if (attention == "null") {
        '';
      } else {
        $('#edit_attention').val(attention);
      }

      if (title == "null") {
        '';
      } else {
        $('#edit_title').val(title);
      }

      if (project == "null") {
        '';
      } else {
        $('#edit_project').val(project);
      }

      if (description == "null") {
        '';
      } else {
        $('#edit_description').val(description);
      }

      if (project_id == "null") {
        '';
      } else {
        $('#edit_project_id').val(project_id);
      }

      if (note == "null") {
        '';
      } else {
        $('#edit_note').val(note);
      }
      $('#edit_date').val(date);
      $('#edit_position').val(position);
    }

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $('#date_backdate').datepicker({
      autoclose: true,
    }).attr('readonly','readonly').css('background-color','#fff');

    initQuoTable();

    function initQuoTable() {
      $("#data_all").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdataquote')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.nik) {
                var x = '"' + data.quote_number + '","' + data.id_customer + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.project_id+ '","' +data.note+ '","' +data.date+ '","' +data.position+ '"'
                data.btn_edit = "<button class='btn btn-sm btn-primary' onclick='edit_quote(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-sm btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "quote_number","width": "20%" },
          { "data": "position" ,"width": "20%"},
          // { "data": "type_of_letter","width": "20%" },
          { "data": "month","width": "20%" },
          { "data": "date","width": "20%" },          
          {
             "render": function ( data, type, row, meta ) {
                return '<div class="truncate">' + row.customer_legal_name + '</div>'

                // if(row.id_customer == null){
                //   return '<div class="truncate"> - </div>'
                // } else {
                // }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if(row.attention == null){
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.attention + '</div>'
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.title == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.title + '</div>'                  
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.project == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.project + '</div>'                  
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.description == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.description + '</div>'                  
                }
              }
          },
          { "data": "name","width": "20%" },
          { "data": "division","width": "20%" },
          { "data": "project_id","width": "20%" },
          { "data": "project_type","width": "20%" },
          { "data": "note","width": "20%","width": "20%" },
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },
        ],
        "searching": true,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        // fixedColumns:   {
        //   leftColumns: 1,
        // },
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 20,
      })
    }

    function changetabPane(status_backdate) {
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=" + status_backdate + "&year=" + $('#year_filter').val()).load();
    }

    $("#year_filter").change(function(){
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=A&year=" + this.value).load();
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#alert").slideUp(300);
    });

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    }); 

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    $('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');

  </script>
@endsection
