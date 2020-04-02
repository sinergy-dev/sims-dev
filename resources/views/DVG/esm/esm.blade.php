@extends('template.template_admin-lte')
@section('content')

<style type="text/css">
  .DTFC_LeftBodyLiner {
    overflow: hidden;
  }

  .dropbtn {
  background-color: #4CAF50;
  color: white;
  font-size: 12px;
  border: none;
  width: 140px;
  height: 30px;
  border-radius: 5px;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 140px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }

  .status-pending-claim{
        border-radius: 6%;
        background-color: #e5140d;
        text-align: center;
        width: 75px;
        height: 25px;
        color: white;
        font-size: 11px
        padding-top: 3px;
    }


  .dropdown-content .year:hover {background-color: #ddd;}

  .dropdown:hover .dropdown-content {display: block;}

  .dropdown:hover .dropbtn {background-color: #3e8e41;}

  .transparant-filter{
    background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none;
  }

  .transparant{
    background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none;
    width: 25px;
  }
</style>

  <section class="content-header">
    <h1>
      Claim Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Claim Management</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
        @endif

        @if (session('success'))
      <div class="alert alert-primary" id="alert">
          {{ session('success') }}
      </div>
        @endif

        @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-header">
        
          @if(Auth::User()->id_position == 'ADMIN')
          <button type="button" class="btn btn-success btn-sm float-right  margin-left-custom" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"></i> &nbspClaim</button>
          <button type="button" class="btn btn-warning btn-sm dropdown-toggle float-right  margin-left-custom" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-download"></i> Export
          </button>
          @endif
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{url('/downloadPdfESM')}}"> PDF </a>
            <a class="dropdown-item" href="{{url('/downloadExcelESM')}}"> EXCEL </a>
          </div>
          <select class="btn btn-sm btn-default fa pull-right" id="year_filter" style="font-size: 14px">
              @foreach($year as $data)
                @if($data->year == date("Y"))
                <option value="{{$data->year}}" selected>&#xf073 &nbsp{{date("Y")}}</option>
                @else
                <option value="{{$data->year}}">&#xf073 &nbsp{{$data->year}}</option>
                @endif
              @endforeach
          </select>

          <select class="btn btn-sm btn-default fa pull-right" id="status_filter" style="font-size: 14px">
              <option value="ADMIN">&nbsp PENDING</option>
              <option value="HRD">&nbsp HRD</option>
              <option value="FINANCE">&nbsp FINANCE</option>
              <option value="TRANSFER">&nbsp TRANSFER</option>
          </select>
            
      </div>

      <div class="box-body">
       <!--    <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs" id="myTab">
                      <li class="nav-item active" style="background-color: #dd4b39;color: white">
                          <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('Pending')">
                              PENDING
                          </a>
                      </li>
                      <li class="nav-item " style="background-color: #f39c12;color: white">
                          <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('hrd')">
                              HRD
                          </a>
                      </li>
                      <li class="nav-item " style="background-color: #5bc0de;color: white">
                          <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('finance')">
                              FINANCE
                          </a>
                      </li>
                      <li class="nav-item " style="background-color: #4cae4c;color: white">
                          <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('done')">
                              TRANSFER
                          </a>
                      </li>
                  </ul>
          <div class="tab-content">
            <div class="tab-pane active"  role="tabpanel" > -->

              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data_esm" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Date</th>
                      <th>Create Date</th>
                      <th>Personnel</th>
                      <th>Type</th>
                      <th>Description</th>
                      <th>Amount</th>
                      <th>ID Project</th>
                      <th>Remarks</th>
                      <th>Action</th>
                      <th>Progress</th>
                    </tr>
                  </thead>
                </table>
              </div>
          <!--   </div>
          </div>
        </div> -->

      </div>
    </div>

    <!-- MODAL ADD -->
<div class="modal fade" id="modalAdd" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Add Claim</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/store_esm')}}" id="modal_product" name="modal_product">
              @csrf
            <div class="form-group">
              <label for="">No</label>
              <input type="text" class="form-control" placeholder="Enter No" name="no" id="no" required>
            </div>
            <div class="form-group">
              <label for="">Date</label>
              <input type="date" class="form-control" placeholder="DD/MM/YYYY" name="date" id="date" required>
            </div>
            <div class="form-group">
              <label for="">Personnel</label>
              <select type="text" class="form-control" placeholder="Enter Personnel" name="personnel" id="personnel" style="width: 100%" required>
                @if(Auth::User()->id_division == 'TECHNICAL')
                  @foreach($owner as $data)
                    @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'PMO')
                  @foreach($owner as $data)
                    @if($data->id_division == 'PMO')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'MSM')
                  @foreach($owner as $data)
                    @if($data->id_division == 'MSM')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @endif
              </select>
            </div> 
            <div class="form-group">
              <label for="">Type</label>
              <select type="text" class="form-control" placeholder="Enter Type" name="type" id="type" required>
                <option>Allowance Staff</option>
                <option>Accomodation</option>
                <option>Entertainment</option>
                <option>Gasoline</option>
                <option>Konsumsi</option>
                <option>Money Request</option>
                <option>Other Claim</option>
                <option>Overtime</option>
                <option>Parking</option>
                <option>Pertanggung Jawaban</option>
                <option>Project Budgeting</option>
                <option>Pulsa</option>
                <option>Toll</option>
                <option>Transportation</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <input type="text" class="form-control" placeholder="Enter Description" name="description" id="description" required>
            </div> 
            <div class="form-group modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div> 
            <div class="form-group">
              <label for="">ID Project</label>
              <input type="text" class="form-control" placeholder="Enter ID Project" name="id_project" id="id_project">
            </div>
            <div class="form-group">
              <label for="">Remarks</label>
              <input type="text" class="form-control" placeholder="Enter Remarks" name="remarks" id="remarks" required>
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

<!-- MODAL EDIT -->
 <div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Claim</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/edit_esm')}}" id="modalEdit" name="modalEdit">
            @csrf
            <div class="form-group">
              <label>No</label>
              <input class="form-control" id="edit_no" name="edit_no" readonly>
            </div>
     <!--        <div class="form-group">
              <label for="">Personnel</label>
              <select type="text" class="form-control" placeholder="Enter Personnel" name="edit_personnel" id="edit_personnel" style="width: 100%" required>
                @if(Auth::User()->id_division == 'TECHNICAL')
                  @foreach($owner as $data)
                    @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'PMO')
                  @foreach($owner as $data)
                    @if($data->id_division == 'PMO')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'MSM')
                  @foreach($owner as $data)
                    @if($data->id_division == 'MSM')
                      @if($data->id_company == '1' && $data->status_karyawan != 'dummy')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endif
                  @endforeach
                @endif
              </select>
            </div>  -->
            <div class="form-group">
              <label for="">Type</label>
              <input type="text" class="form-control" name="edit_type" id="edit_type" >
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <input type="text" class="form-control" name="edit_description" id="edit_description" >
            </div> 
            <div class="form-group modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control" name="edit_amountclaim" id="edit_amountclaim" >
              <i class="" aria-hidden="true">Rp.</i>
            </div> 
            <div class="form-group">
              <label for="">ID Project</label>
              <input type="text" class="form-control" name="edit_id_project" id="edit_id_project" >
            </div>
            <div class="form-group">
              <label for="">Remarks</label>
              <input type="text" class="form-control" name="edit_remarks" id="edit_remarks" >
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>

        </form>
        </div>
      </div>
    </div>
  </div>

@if(Auth::User()->id_position == 'ADMIN')
  <div class="modal fade" id="modalassignhrd" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_hrd')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_hrd_edit" name="assign_to_hrd_edit" value="" hidden>
          <input type="" id="amount_edit" name="amount_edit" value="" hidden>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@elseif(Auth::User()->id_position == 'HR MANAGER')
  <div class="modal fade" id="modalassignfinance" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_fnc')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_fnc_edit" name="assign_to_fnc_edit" value="" hidden>
          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" name="amount_edit" id="amount_edit" readonly>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          <div class="form-group modalIcon inputIconBg">
              <label for="">Revised Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount_revised" required>
              <i class="" aria-hidden="true">Rp.</i>
          </div> 
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@elseif(Auth::User()->id_division == 'FINANCE')
  <div class="modal fade" id="modalassigntransfer" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_adm')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_adm_edit" name="assign_to_adm_edit" value="" hidden>
          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" name="amount_edit" id="amount_req" readonly>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          <div class="form-group modalIcon inputIconBg">
              <label for="">Amount Transfer</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount_tf" required>
              <i class="" aria-hidden="true">Rp.</i>
          </div> 
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  </section>

@endif

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script type="text/javascript">
  initReportTerritory();

    function initReportTerritory(){
      var table = $("#data_esm").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getESM')}}",
        },
        "columns": [
          // { "data": "name" },
          { "data": "no" },
          { "data": "date" },
          { "data": "created_at" },
          { "data": "name" },
          { "data": "type" },
          { "data": "description" },
          { "data": "amount" },
          { "data": "id_project" },
          { "data": "remarks" },
          
          { 
            render: function ( data, type, row ) {
              @if (Auth::User()->id_division == 'HR')
                if (row.status == 'HRD') {
                  return '<button class="btn btn-xs btn-warning btn-submit-finance" value="'+row.id_ems+'" style="width:60px">Submit</button>';
                }else{
                  return '<button class="btn btn-xs btn-primary disabled" style="width:60px"><i class="fa fa-edit"></i> Edit</button>'+'<button class="btn btn-xs btn-danger disabled"  style="width:60px"><i class="fa fa-trash"></i> Delete</button>'+'<button class="btn btn-xs btn-warning disabled" style="width:60px">Submit</button>';
                } 
              @elseif(Auth::User()->id_position == 'ADMIN')
                if (row.status == 'ADMIN') {
                  return '<button class="btn btn-xs btn-primary btn-edit" id="btn-edit" style="width:60px" value="'+row.id_ems+'"><i class="fa fa-edit"></i> Edit</button>'+'<button class="btn btn-xs btn-danger btn-delete" value="'+row.id_ems+'"  style="width:60px"><i class="fa fa-trash"></i> Delete</button>'+'<button class="btn btn-xs btn-warning btn-submit-hrd" value="'+row.id_ems+'" style="width:60px">Submit</button>';
                }else{
                  return '<button class="btn btn-xs btn-primary disabled" style="width:60px"><i class="fa fa-edit"></i> Edit</button>'+'<button class="btn btn-xs btn-danger disabled" style="width:60px"><i class="fa fa-trash"></i> Delete</button>'+'<button class="btn btn-xs btn-warning disabled" style="width:60px">Submit</button>';
                } 
              @elseif(Auth::User()->id_division == 'FINANCE')
                if (row.status == 'FINANCE') {
                  return '<button class="btn btn-xs btn-warning btn-submit-transfer" value="'+row.id_ems+'" style="width:60px">Submit</button>';
                }else{
                  return '<button class="btn btn-xs btn-primary disabled" style="width:60px"><i class="fa fa-edit"></i> Edit</button>'+'<button class="btn btn-xs btn-danger disabled" style="width:60px"><i class="fa fa-trash"></i> Delete</button>'+'<button class="btn btn-xs btn-warning disabled" style="width:60px">Submit</button>';
                } 
              @else
               return '<i>No Action</i>'
              @endif
              
            } 
          },
          { 
            render: function ( data, type, row ) {
                if (row.status == "ADMIN") {
                  return '<span class="label label-danger">Pending</span>';
                }else if (row.status == "HRD") {
                  return '<span class="label label-warning">HRD</span>';
                }else if (row.status == "FINANCE") {
                  return '<span class="label label-info">FINANCE</span>';
                }else if (row.status == "TRANSFER") {
                  return '<span class="label label-success">TRANSFER</span>';
                }
            } 
          },
          
        ],
        "searching": true,
        // "paging": false,
        "info":false,
        "scrollX": true,
        fixedColumns:   {
          leftColumns: 3
        },
        "processing": true,
        "columnDefs": [
            { 
              "width": "5%", "targets": 2,
              "width": "5%", "targets": 3,
              "width": "5%", "targets": 4,
              "width": "5%", "targets": 5,
              "width": "5%", "targets": 6,
              "width": "5%", "targets": 7,
              "width": "5%", "targets": 8
            }
        ],
      })

      $('#data_esm').on('click', '.btn-edit', function(){
        console.log(this.value);
        $.ajax({
          type:"GET",
          url:'{{url("getEditEsm")}}',
          data:{
            id_ems:this.value,
          },
          success: function(result){
              console.log(result)
              $('#edit_no').val(result[0].no);
              $('#edit_type').val(result[0].type);
              $('#edit_description').val(result[0].description);
              $('#edit_amountclaim').val(result[0].amount);
              $('#edit_id_project').val(result[0].id_project);
              $('#edit_remarks').val(result[0].remarks);
            }
        })
        $("#modaledit").modal("show");
      });

      $('#data_esm').on('click', '.btn-submit-hrd', function(){
        $.ajax({
          type:"GET",
          url:'{{url("getEditEsm")}}',
          data:{
            id_ems:this.value,
          },
          success: function(result){
              console.log(result)
              $('#assign_to_hrd_edit').val(result[0].id_ems);
            }
        })
        $("#modalassignhrd").modal("show");
      })

      $('#data_esm').on('click', '.btn-submit-finance', function(){
        $.ajax({
          type:"GET",
          url:'{{url("getEditEsm")}}',
          data:{
            id_ems:this.value,
          },
          success: function(result){
              console.log(result)
              $('#assign_to_fnc_edit').val(result[0].id_ems);
              $('#amount_edit').val(result[0].amount);
            }
        })
        $("#modalassignfinance").modal("show");
      })

      $('#data_esm').on('click', '.btn-submit-transfer', function(){
        $.ajax({
          type:"GET",
          url:'{{url("getEditEsm")}}',
          data:{
            id_ems:this.value,
          },
          success: function(result){
              console.log(result)
              $('#assign_to_adm_edit').val(result[0].id_ems);
              $('#amount_req').val(result[0].amount);
            }
        })
        $("#modalassigntransfer").modal("show");
      })

      $('#data_esm').on('click', '.btn-delete', function(e){
        console.log(this.value)
        var id_ems = this.value;
        $.ajax({
          type:"GET",
          url:"{{url('delete_esm/')}}/"+id_ems,
          beforeSend:function(){
            return confirm("Want to delete?") 
          },
          success: function(result){
              setTimeout(function(){
                $('#data_esm').DataTable().ajax.url("{{url('getESM')}}").load();
              },2000);
          }
        })
        $("#modalassigntransfer").modal("show");
      })

      $('#year_filter').change(function(){
        console.log(this.value)
        $('#data_esm').DataTable().ajax.url("{{url('getFilterESMbyYear')}}?year=" + this.value).load();
      })

      $('#status_filter').change(function(){
        console.log(this.value)
        $('#data_esm').DataTable().ajax.url("{{url('getFilterESMbyStatus')}}?year=" + $("#year_filter").val() + "&status=" + this.value).load();
      })
    }



  $("#personnel").select2();

  $("#edit_personnel").select2();
     
  function esm(no, personnel, type, description, amount, id_project, remarks) {
    $('#edit_no').val(no);
    $('#edit_type').val(type);
    $('#edit_description').val(description);
    $('#edit_amountclaim').val(amount);
    $('#edit_id_project').val(id_project);
    $('#edit_remarks').val(remarks);
    $('#edit_personnel').val(personnel);
  }

  function number(no, amount) {
    $('#assign_to_hrd_edit').val(no);
    $('#amount_edit').val(amount);
  }

  function number_fnc(no, amount) {
    $('#amount_edit').val(amount);
    $('#assign_to_fnc_edit').val(no);
  }

  function number_adm(no){
    $('#assign_to_adm_edit').val(no);
  }

  /*$('.money').mask('000,000,000,000,000', {reverse: true});
    $(document).ready(function() {
        $('#contact').select2();
  });*/

  $('.money').mask('000,000,000,000,000', {reverse: true});

  $("#alert").fadeTo(2000, 500).slideUp(500, function(){
       $("#alert").slideUp(300);
  });

  function show2019() {
       document.getElementById('div_2018').style.display = "none";
       document.getElementById('div_2019').style.display = "inherit";
  }

  function show2018() {
       document.getElementById('div_2018').style.display = "inherit";
       document.getElementById('div_2019').style.display = "none";
  }
</script>
@endsection