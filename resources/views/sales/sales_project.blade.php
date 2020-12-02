@extends('template.template_admin-lte')
@section('content')
<?php
header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
?>

<style type="text/css">
  div.dataTables_processing { z-index: 1; }

/*  .DTFC_LeftBodyLiner {
    overflow-y: hidden; // hide vertical
	  overflow-x: hidden; // hide horizontal
    opacity:0.8;
  }*/

  .DTFC_LeftBodyLiner {
    overflow: hidden;
  }

  .dataTables_length{
    display: none
  }

  .dataTables_paginate{
    display: none;
  }

  .dataTables_filter {
    display: none;
  }

  .dataTables_paging {
    display: none;
  }

  th, td { white-space: nowrap; overflow: hidden; };

  .inputWithIcon input[type=text]{
    padding-left:40px;
  }

  .inputWithIcon.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .inputWithIcon.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:10px 9px;
      border-radius:4px 0 0 4px;
    }

   .inputWithIcon{
      position:relative;
    }

   .inputWithIcon i{
      position:absolute;
      left:0;
      top:0;
      padding:9px 9px;
      color:#aaa;
      transition:.3s;
  }

</style>

<section class="content-header">
  <h1>
    ID Project
  </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">ID Project</li>
  </ol>
</section>

<section class="content">
  @if (session('success'))
  <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your PID :<h4> {{$pops->id_project}}</h4></div>
  @elseif(session('warning'))
  <div class="alert alert-warning notification-bar" id="alert"><span>Notice : </span> {{ session('warning') }}.</div>
  @elseif (session('error'))
    <div class="alert alert-danger notification-bar" id="alert"><span>Notice : </span> {{ session('error') }}.</div>
  @elseif (session('gagal'))
  <div class="alert alert-danger notification-bar" id="alert"><span>Warning : </span> {{ session('gagal') }}.</div>
  @endif

  <!--tes-->

  <div class="box">
    <div class="box-header">
        <div class="pull-right">
        </div>
    </div>

    <div class="box-body">
      <div class="nav-tabs-custom active" id="project_tab" role="tabpanel" aria-labelledby="project-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @if(Auth::User()->id_division == 'FINANCE' || Auth::User()->id_position == 'DIRECTOR')
              @if(Auth::User()->id_position == 'MANAGER')
              <li class="active"><a href="#sip" data-toggle="tab" onclick="changeTabs('sip')">SIP</a></li>
              <li><a href="#msp" data-toggle="tab" onclick="changeTabs('msp')">MSP</a></li>
              <li><a href="#request" data-toggle="tab" onclick="changeTabs('request')">ID Request</a></li>
              <li><a href="#history" data-toggle="tab" onclick="changeTabs('history')">History Request</a></li>
              @else
              <li class="active"><a href="#sip" data-toggle="tab" onclick="changeTabs('sip')">SIP</a></li>
              <li><a href="#msp" data-toggle="tab" onclick="changeTabs('msp')">MSP</a></li>
              @endif
            @else
            @endif
        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="sip">
              <div class="box-header">
                <div class="row">
                  <div class="col-md-8" id="export-table">
                    @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
                    <a href="{{action('SalesController@export')}}" class="btn btn-warning btn-flat btn-sm pull-left export" style="margin-right: 10px;width: 100px;font-size: 15px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>

                    <a href="{{action('SalesController@export_msp')}}" class="btn btn-warning btn-sm pull-left export-msp" style="margin-right: 10px;display: none;;width: 100px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    @else
                    <a href="{{action('SalesController@export')}}" style="margin-right: 10px" class="btn btn-warning btn-sm pull-left export"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>

                    <a href="{{action('SalesController@export_msp')}}" class="btn btn-warning btn-sm pull-left export-msp" style="margin-right: 10px;display: none;;width: 100px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    @endif
                    <select style="margin-right: 5px;width: 100px" class="form-control btn-primary btn-flat fa" id="year_filter">
                        <option value="{{$year_now}}">&#xf073 &nbsp{{$year_now}}</option>
                        @foreach($year_before as $years)
                          @if($years->year != $year_now)
                            <option value="{{$years->year}}">&#xf073 &nbsp{{$years->year}}</option>
                          @endif
                        @endforeach
                        
                        <!-- <option value="2019">&#xf073 &nbsp2019</option> -->
                    </select>
                  </div>
                  <div class="col-md-4" id="search-table">
                    <div class="input-group pull-right" style="margin-left: 10px">
                      <div class="input-group-btn">
	                <button type="button" id="btnShowEntryTicket" style="width: 110px" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                  Show 10 entries
	                  <span class="fa fa-caret-down"></span>
	                </button>
	                <ul class="dropdown-menu" id="selectShowEntryTicket">
	                  <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
	                  <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
	                  <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
	                  <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
	                </ul>
	              </div>
	              <input id="searchBarTicket" type="text" class="form-control" style="height: 30px" placeholder="Search Anything">
	              <span class="input-group-btn">
	                <button id="applyFilterTablePerformance" type="button" class="btn btn-default btn-sm" style="width: 40px">
	                  <i class="fa fa-fw fa-search"></i>
	                </button>
	              </span>
	                    </div>
	                  </div>
	                </div>
	              </div>

	           <div id="pid-table">
	           	  <table class="table table-bordered table-striped display" id="table-pid">
	                <thead>
	                    <tr>
	                      <th>Date</th>
	                      <th>ID Project</th>
	                      <th>Lead ID</th>
	                      <th>No. PO Customer</th>
	                      <th>No. Quotation</th>
	                      <th>Customer Name</th>
	                      <th>Project Name</th>
	                      <th>Amount IDR</th>
	                      <th>Amount Before Tax</th>
	                      <th>Note</th>
	                      <th>Invoice</th>
	                      <th>Status</th>	
	                      <th>Sales</th>
	                      <th>Action</th>
	                    </tr>
	                </thead>
	                <tfoot>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                </tfoot>
	              </table>
	           </div>
              
	           <div id="request-table" style="display:none">
	              <table class="table table-bordered table-striped display"  id="request_id" width="100%" cellspacing="0">
	                  <thead>
	                    <tr>
	                      <th>Created</th>
	                      <th>Company</th>
	                      <th>Quote No.</th>
	                      <th>Project</th>
	                      <th>Sales</th>
	                      <th>Date</th>
	                      <th>Amount</th>
	                      <th>Action</th>
	                    </tr>
	                  </thead>
	                  <tfoot>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                  </tfoot>
	              </table>
          		</div>
            </div>

        </div>
      </div>
    </div>
  </div>

  <!--modal-->
<div class="modal fade" id="showRequestProjectID" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Request Project ID</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="inputCustomer">
        <input type="hidden" id="code_name" name="id_customer_quotation">
        <div class="form-group">
          <label for="">Quote No.</label>
          <input type="text" class="form-control select2" style="width: 100%" id="inputQuo">
        </div>
        <div class="form-group">
          <label for="">PO No.</label>
          <input type="text" class="form-control" style="width: 100%" id="inputPO">
        </div>
        <div class="form-group">
          <label for="">Project Name</label>
          <input type="text" class="form-control" style="width: 100%" id="inputProject" readonly>
        </div>
        <div class="form-group">
          <label for="">Sales</label>
          <input type="text" class="form-control select2" style="width: 100%" id="inputSales" readonly>
        </div>
        <div class="form-group">
          <label for="">Date</label>
          <input type="date" name="date" id="inputDate" class="form-control" required>
        </div>
        <div class="form-group  modalIcon inputIconBg">
          <label for="">Amount</label>
          <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="inputAmount" required>
          <i class="" aria-hidden="true" style="margin-bottom: 24px">Rp.</i>
        </div>
        <div class="form-group">
          <label for="">Note</label>
          <input type="text" placeholder="Enter Note" name="note" id="inputNote" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">
          <i class=" fa fa-times">&nbsp</i>Close
        </button>
        <button class="btn btn-primary-custom" id="btn_submit" onclick="submitRequestID()" >
          <i class="fa fa-check">&nbsp</i>Submit
        </button>
      </div>
    </div>
  </div>
</div>

<!--add project SIP-->
<div class="modal fade" id="salesproject" role="dialog">
      <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add ID Project SIP</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('store_sp')}}">
              @csrf
            <div class="form-group">
              <label for="">Lead ID</label>
                <!-- <input list="browsers" name="customer_name" id="customer_name" class="form-control">
                
                <datalist id="browsers">
                </datalist> -->
                <select name="customer_name" id="customer_name" style="width: 100%;" class="form-control cn" required>
                  <option>-- Select Lead ID --</option>
                  @foreach($lead_sp as $data)
                  <option value="{{$data->lead_id}}">
                  @if($data->pid == NULL)
                  <b>{{$data->lead_id}}</b>&nbsp {{$data->no_po}} &nbsp {{$data->opp_name}}
                  @else
                  ({{$data->pid}})&nbsp<b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
                  @endif
                  </option>
                  @endforeach
                </select>
                
              <!-- <input type="text" id="customer_name" name="customer_name" class="form-control" readonly> -->
            </div>

            <div class="form-group" hidden>
              <label for="">Sales</label>
              <input type="text" name="sales" id="sales" class="form-control" readonly>
            </div>

            <div class="form-group">
              <label for="">Date</label>
              <input type="text" name="date" id="date" class="form-control date" required>
            </div>

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money amount_pid" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true" style="margin-bottom: 24px">Rp.</i>
            </div>

            <div class="form-group">
              <label for="">Note</label>
              <textarea type="text" placeholder="Enter Note" name="note" id="note" class="form-control"></textarea>
            </div>

            <div class="form-group" style="padding-left: 25px">
              <label class="checkbox">
                <input type="checkbox" name="payungs" id="payungs" value="SP" style="width: 7px;height: 7px">
                <span>Kontrak Payung <sup>(Optional)</sup></span>
              </label>
            </div>
     
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary-custom" ><i class="fa fa-check">&nbsp</i>Submit</button>
            </div>
          </form>
          </div>
        </div>
      </div>
</div>

<!--add project MSP-->
<div class="modal fade" id="salesprojectmsp" role="dialog">
      <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add ID Project MSP</h4>
          </div>
          <div class="modal-body">
            <form method="GET" action="{{url('store_sp')}}">
              @csrf

            <div class="form-group">
              <label for="">Lead ID</label>
                <select name="customer_name" id="contact_msp" style="width: 100%" class="form-control cn" required>
                  <option>-- Select Lead ID --</option>
                  @foreach($lead_msp as $data)
                  <option value="{{$data->lead_id}}">
                  @if($data->pid == NULL)
                  <b>{{$data->lead_id}}</b>&nbsp | {{$data->no_po}} &nbsp | {{$data->opp_name}}
                  @else
                  ( {{$data->pid}} )&nbsp<b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
                  @endif
                  </option>
                  @endforeach
                </select>
                
              <!-- <input type="text" id="customer_name" name="customer_name" class="form-control" readonly> -->
            </div>

            <div class="form-group">
              <label for="">No PO</label>
              <input type="text" placeholder="Enter Note" name="po_customer" id="po_customer" class="form-control">
            </div>

            <div class="form-group" hidden>
              <label for="">Sales</label>
              <input type="text" name="sales" id="sales_msp" class="form-control" readonly>
            </div>

            <div class="form-group">
              <label for="">Date</label>
              <input type="text" name="date" id="date_msp" class="form-control date" required>
            </div>

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money amount_pid" placeholder="Enter Amount" name="amount" id="amount_msp" required>
              <i class="" aria-hidden="true" style="margin-bottom: 24px">Rp.</i>
            </div>

            <div class="form-group">
              <label for="">Note</label>
              <input type="text" placeholder="Enter Note" name="note" id="note_msp" class="form-control">
            </div>

            <div class="form-group" style="padding-left: 25px">
              <label class="checkbox">
                <input type="checkbox" style="width: 7px;height: 7px" name="payungs" id="payungs_msp" value="SP">
                <span>Kontrak Payung <sup>(Optional)</sup></span>
              </label>
            </div>
     
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary-custom"><i class="fa fa-check">&nbsp</i>Submit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
</div>

<!--edit project-->
<div class="modal fade" id="edit_salessp" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Id Project</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_sp')}}">
            @csrf
          <input type="" name="id_project_edit" id="id_project_edit" hidden>
          <div class="form-group">
            <label for="">No. PO Customer</label>
            <input type="text" name="po_customer_edit" id="po_customer_edit" class="form-control">
          </div>

          <div class="form-group">
            <label for="">Project Name</label>
            <textarea type="text" name="name_project_edit" id="name_project_edit" class="form-control"></textarea>
          </div>

          @if(Auth::User()->id_position == 'MANAGER')
          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" placeholder="Enter Amount" name="amount_edit" id="amount_edit" required>
            <i class="" aria-hidden="true" style="margin-bottom: 24px">Rp.</i>
          </div>
          @endif

          <div class="form-group">
            <label for="">Note</label>
            <input type="text" placeholder="Enter Note" name="note_edit" id="note_edit" class="form-control">
          </div>

          <label for="">Invoice info</label><a class="check-reset">(<i class="fa  fa-refresh"></i> Reset)</a><br>

          <div style="padding-left: 20px">
            
            
            <label class="radio">
              <input type="radio" name="invoice" id="invoice_edit_f" value="F">
              <span>Done</span>
            </label>
            

            <label class="radio">
              <input type="radio" name="invoice" id="invoice_edit_h" value="H">
              <span>Setengah Bayar</span>
            </label>
          </div>

          <!-- <div class="form-group modalIcon inputIconBg">
            <label for="">Kurs To Dollar</label>
            <input type="text" class="form-control" readonly placeholder="Kurs" name="kurs_edit" id="kurs_edit">
            <i class="" aria-hidden="true">&nbsp$&nbsp </i>
          </div>   -->     
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-warning"><i class="fa fa-check">&nbsp</i>Edit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit status-->
<div class="modal fade" id="modal_status" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Progress</h4>
        </div>
        <div class="modal-body">
          <form action="{{url('/update_status_sp')}}" method="POST">
            {!! csrf_field() !!}
            <input type="text"  name="id_pro" id="id_pro_status" hidden>
            <div style="text-align: center;" class="form-group">
              <select name="status" id="status" class="form-control">
                <option value="Running">Running</option>
                <option value="Done">Done</option>
                <option value="Maintenance">Maintenance</option>
              </select>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success" type="submit"><b>Submit</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal_delete" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form action="{{url('delete_project')}}" method="GET">
            {!! csrf_field() !!}
            <input type="text"  name="id_pro" id="id_pro" hidden>
            <input type="text"  name="lead_id" id="id_delete_pro" hidden>
            <div style="text-align: center;">
              <h3>Are you sure?</h3><h3>Delete</h3>
            </div>
          <div class="modal-footer">
            <button class="btn btn-sm btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="tunggu" role="dialog">
<div class="modal-dialog modal-sm">
<!-- Modal content-->
<div class="modal-content">
    <div class="modal-body">
      <div class="form-group">
        <div class="">Sedang memproses. . .</div>
      </div>
    </div>
  </div>
</div>
</div>

  </section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <!-- <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
  <script type="text/javascript">

  initPID();

  function changeNumberEntries(number){
    $("#btnShowEntryTicket").html('Show ' + number + ' entries <span class="fa fa-caret-down"></span>')
    $("#table-pid").DataTable().page.len( number ).draw();
  }

  function initPID(){
  var table = $("#table-pid").DataTable({
  	"footerCallback": function( row, data, start, end, display ) {
          var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

          var api = this.api(),data;  
          // Remove the formatting to get integer data for summation

          var total = api.column(7, {page:'current'}).data().sum();

          var filtered = api.column(7, {"filter": "applied"} ).data().sum();

          var totalpage = api.column(7).data().sum();

          var filteredgrand = api.column(8, {"filter": "applied"} ).data().sum();

          var totalpagegrand = api.column(8).data().sum();

          $( api.column( 6 ).footer() ).html("Total Amount");

          $( api.column( 7 ).footer() ).html(totalpage);

          $( api.column( 7 ).footer() ).html(filtered);

          $( api.column( 8 ).footer() ).html(totalpagegrand);

          $( api.column( 8 ).footer() ).html(filteredgrand);

    },
    "ajax":{
        "type":"GET",
        "url":"{{url('getPIDIndex')}}",
      },
      "columns": [
        { "data": "date" },
        { "data": "id_project" },
        { "data": "lead_id" },
        { // No Po
          render: function ( data, type, row ) {
            if (row.id_company == 1) {
              if (row.no_po_customer == null) {
                return row.quote_number_final;  
              }else{
                return row.no_po_customer;  
              }
            }else{
              if (row.lead_id == "MSPPO") {
                return row.no_po_customer;
              }else{
                return row.no_po;  
              }
            }
          }
        },
        { // No Quotation
          render: function ( data, type, row ) {
            if (row.id_company == 1) {
              return "-";
            }else{
              if (row.lead_id == "MSPQUO") {
                return row.no_po_customer;
              }else{
                return row.quote_number;  
              }
            }
          }
        },
        { // Customer Name
          render: function ( data, type, row ) {
            if (row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
              return row.customer_name;  
            }else{
              return row.customer_legal_name;  
            }
          }
        },
        { "data": "name_project" },
        { "data": "amount_idr" },
        { "data": "amount_idr_before_tax" },
        { "data": "note" },
        { // Invoice
          render: function ( data, type, row ) {
            if (row.invoice == 'H') {
              return "Setengah Bayar";  
            }else if (row.invoice == 'F') {
              return "Sudah Bayar";
            }else if (row.invoice == 'N') {
              return "Belum Bayar";
            }else{
              return "";
            }
          }
        },
        { // Status
          render: function ( data, type, row ) {
            if (row.progres == null) {
              return "UnProgress";  
            }else {
              return row.progres;
            }
          }
        },
        { // Sales
          render: function ( data, type, row ) {
            if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
              return row.sales_name;  
            }else{
              return row.name;  
            }
          }
        },
        { // Action
          render: function ( data, type, row ) {
          	if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
          		@if(Auth::User()->id_division == 'FINANCE')
            		@if(Auth::User()->id_position == 'MANAGER')
            			return '<i>No Action</i>';
            		@else
            			return '<i>No Action</i>';
              	@endif
            	@elseif(Auth::User()->id_division == 'PMO')
            		return '<button class="btn btn-xs btn-warning btn-status" style="width: 70px" value="'+row.id_pro+'"><i class="fa fa-edit"></i>&nbspEdit</button>'
            	@else
            	 return 'mbuh'; 
            	@endif  

          	}else{

          		@if(Auth::User()->id_division == 'FINANCE')
            		@if(Auth::User()->id_position == 'MANAGER')
            			return '<button class="btn btn-xs btn-warning btn-edit" style="width: 70px" value="'+row.id_pro+'"><i class="fa fa-edit"></i>&nbspEdit</button>' + ' ' + '<button class="btn btn-xs btn-danger btn-delete" value="'+row.id_pro+'" style="width: 70px"><i class="fa fa-trash"></i>&nbspDelete</button>'
            		@else
            			return '<button class="btn btn-xs btn-warning btn-edit" value="'+row.id_pro+'" style="width: 70px"><i class="fa fa-edit"></i>&nbspEdit</button>' 
            		@endif
            	@elseif(Auth::User()->id_division == 'PMO')
            		return '<button class="btn btn-xs btn-warning btn-status" style="width: 70px" value="'+row.id_pro+'"><i class="fa fa-edit"></i>&nbspEdit</button>'
            	@else
            	 return 'mbuh'; 
            	@endif  

          	}
          	                
          }
        },
      ],
      // "info":false,
      "scrollX": true,
      "pageLength": 25,
      "order": [[ 1, "desc" ]],
      "orderFixed": [[1, 'desc']],
      "processing": true,
      "language": {
        'loadingRecords': '&nbsp;',
        'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
      }, 
      "scrollCollapse":true,
      // "paging": false,
      fixedColumns:   {
        leftColumns: 2,
        rightColumns: 1
      },  
  });

  var request_table = $("#request_id").DataTable({
    "footerCallback": function( row, data, start, end, display ) {
            var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

            var api = this.api(),data;
            // Remove the formatting to get integer data for summation

            var total = api.column(6, {page:'current'}).data().sum();

            var filtered = api.column(6, {"filter": "applied"} ).data().sum();

            var totalpage = api.column(6).data().sum();

            $( api.column( 5 ).footer() ).html("Total Amount");

            $( api.column( 6 ).footer() ).html(numFormat(totalpage));

            $( api.column( 6 ).footer() ).html(numFormat(filtered));

      },
    "ajax":{
      "type":"GET",
      "url":"{{url('getShowPIDReq')}}",
      },
      "columns": [
        { "data": "created_at" },
        { "data": "code_company" },
        {
          render: function ( data, type, row ) {
          	if (row.no_po == null) {
                return row.quote_number;
            }else{
                return row.no_po;  
            }
          }
        },
        { "data": "opp_name"},
        { "data": "name"},
        {
          render: function ( data, type, row ) {
          	if (row.date_po == null) {
                return row.date;
            }else{
                return row.date_po;  
            }
          }
        },
        { "data": "amount_pid"},
        {
          render: function ( data, type, row ) {
          	if (row.status == 'requested') {
          		return '<button class="btn btn-xs btn-primary btn-show" style="width: 100%" data-toggle="modal" value="'+row.id_pid+'">Show</button>'
          	}else if(row.status == 'done'){
          		return '<small class="label label-success"><i class="fa fa-clock-o"></i>Done</small>'
          	}
          	
          }
        },
      ],
      "info":false,
      "scrollX": true,
      "pageLength": 25,
      "order": [[ 1, "desc" ]],
      "orderFixed": [[1, 'desc']],
      "processing": true,
      "language": {
          'loadingRecords': '&nbsp;',
          'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
      "paging": true,
      fixedColumns:   {
        rightColumns: 1
      }, 
	});	


    @if(Auth::User()->id_division == 'MSM')
		  // Column Amount IDR
      var column1 = table.column(7);
	    column1.visible( ! column1.visible() );
      // Column Amount IDR Before Tax
	    var column2 = table.column(8);
	    column2.visible( ! column2.visible() );
    @endif

    @if(Auth::User()->id_division == "SALES" || Auth::User()->id_division == "TECHNICAL" || Auth::User()->id_division == "MSM" || Auth::User()->id_position == "DIRECTOR")
    	// Column Action
      var column3 = table.column(13);
      column3.visible(! column3.visible());
    @endif

    $('#searchBarTicket').keyup(function(){
      table.search($('#searchBarTicket').val()).draw();
    })

    $('#applyFilterTablePerformance').click(function(){
      table.search($('#searchBarTicket').val()).draw();
    })

    $('#searchBarTicketmsp').keyup(function(){
      table.search($('#searchBarTicketmsp').val()).draw();
    })

    $('#applyFilterTablePerformancemsp').click(function(){
      table.search($('#searchBarTicketmsp').val()).draw();
    })
    
  }

  function submitRequestID(){
    if($("#inputCustomer").val() == ""){
      customer_name = "MSPQUO"
    } else {
      customer_name = $("#inputCustomer").val()
    }

    $('#tunggu').modal('show');
    $('#showRequestProjectID').modal('hide')

    $.ajax({
      type:"GET",
      url:"{{url('/store_sp')}}",
      data:{
        _token: "{{ csrf_token() }}",
        customer_name:customer_name,
        // sales:$("#inputCustomer").val(),
        date:($("#inputDate").val()),
        amount:$("#inputAmount").val(),
        note:$("#inputNote").val(),
        p_order:$("#inputPO").val(),
        quote:$("#inputQuo").val(),
        id_cus:$("#code_name").val(),
        // id_customer_quotation:$("#code_name").val(),
        // payungs:$("#inputCustomer").val(),
      },
      success:function(result){
        $('#tunggu').modal('hide');
    	  $('#showRequestProjectID').modal('hide')
        location.reload()
      }
    })
  }

  $('#table-pid').on('click', '.btn-edit', function(){
  	$('#tunggu').modal('show');
      console.log(this.value);
      $.ajax({
        type:"GET",
        url:'{{url("getEditPID")}}',
        data:{
          id_pro:this.value,
        },
        success: function(result){
        	  $('#tunggu').modal('hide');
            console.log(result)
            $.each(result[0], function(key, value){
            $('#id_project_edit').val(value.id_project);
          $('#name_project_edit').val(value.name_project);
          $('#note_edit').val(value.note);
            @if(Auth::User()->id_position == 'STAFF')
	      	$('#po_customer_edit').val(value.no_po_customer);
	      @else
	      	$('#po_customer_edit').val(value.no_po_customer);
	      	$('#amount_edit').val(value.amount_idr);
	      @endif
	      if (value.invoice == 'H') {
	        $('#invoice_edit_h').prop('checked', true);
	      }
	      else if (invoice == 'F') {
	        $('#invoice_edit_f').prop('checked', true);
	      }else if (value.invoice == 'N') {
	        $('#invoice_edit_n').prop('checked', true);
	      }
            })
          
          }
      })
      $("#edit_salessp").modal("show");
    });

    $('#table-pid').on('click', '.btn-delete', function(e){
    	Swal.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes!'
		}).then((result) => {
		  if (result.value) {
		  	var id_pro = this.value;
		  	$('#tunggu').modal('show');
	        $.ajax({
	          type:"GET",
	          url:"{{url('delete_project/')}}/"+id_pro,
	          success: function(result){
	          	$('#tunggu').modal('hide');
	          	Swal.fire(
			      'Deleted!',
			      'Your file has been deleted.',
			      'success'
			    ),
	            setTimeout(function(){
	                $('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}").load();
	            },2000);
	          }
	        })
		  }
		})
      
  })

	$('#request_id').on('click', '.btn-show', function(){
		$('#tunggu').modal('show');
    	$.ajax({
        type:"GET",
        url:"{{url('/salesproject/getAcceptProjectID')}}",
        data:{
          id:this.value,
        },
        success:function(result){
          $('#tunggu').modal('hide');
          $("#code_name").val(result.code)
          $("#inputCustomer").val(result.lead_id)
          $("#inputPO").val(result.no_po)
          $("#inputProject").val(result.opp_name)
          $("#inputSales").val(result.name)
          if (result.quote_number_final == null) {
            $("#inputQuo").val(result.quote_number)
          }else{
            $("#inputQuo").val(result.quote_number_final)
          }

          if (result.date_po == null) {
            $("#inputDate").val(result.date)
          }else{
            $("#inputDate").val(result.date_po)
          }
          if (result.amount_pid == null) {
            $("#inputAmount").val(result.amount)
          }else{
            $("#inputAmount").val(result.amount_pid)
          }
          $("#inputNote").val(result.note)
        }
      })

      $("#showRequestProjectID").modal("show");
    });

    function changeTabs(id) {
      console.log(id)
      if (id == "sip") {
      	$('#export-table').css("display","block")
      	$('#search-table').css("display","block")
      	$('#request-table').css("display","none")
      	$('#history-table').css("display","none")
      	$('#pid-table').css("display","block")
      	$('.export-msp').css("display","none")
      	$('.export').css("display","block")
        $('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+id).load();
      }else if(id == "msp"){
      	$('.export-msp').css("display","block")
      	$('.export').css("display","none")
      	$('#export-table').css("display","block")
      	$('#search-table').css("display","block")
      	$('#request-table').css("display","none")
      	$('#history-table').css("display","none")
      	$('#pid-table').css("display","block")
        $('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+id).load();
      }else if (id == "request") {
      	$('#request-table').show()
      	$('#pid-table').css("display","none")
      	$('#export-table').css("display","none")
      	$('#search-table').css("display","none")
      	$('#request_id').DataTable().ajax.url("{{url('getShowPIDReq')}}?id="+id).load();
      }else if (id == "history") {
      	$('#request-table').show()
      	$('#pid-table').css("display","none")
      	$('#export-table').css("display","none")
      	$('#search-table').css("display","none")
      	$('#request_id').DataTable().ajax.url("{{url('getShowPIDReq')}}?id="+id).load();
      }

    }   

  $("#alert").fadeTo(5000, 500).slideUp(500, function(){
  $("#alert").slideUp(300);
  });	

  $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
  }); 

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // store the currently selected tab in the hash value
  // $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  //   var id = $(e.target).attr("href").substr(1);
  //   window.location.hash = id;

  //   console.log(id);

  //   if (id == "sip") {
  //   	$('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+id).load();
  //   }else if (id == "msp") {
  //   	$('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+id).load();
  //   }else if (id == "request") {
  //   	$('#request_id').DataTable().ajax.url("{{url('getShowPIDReq')}}?id="+id).load();
  //   }else if (id == "history") {
  //   	$('#request_id').DataTable().ajax.url("{{url('getShowPIDReq')}}?id="+id).load();
  //   }
  // });

  // on load of the page: switch to the currently selected tab
  // var hash = window.location.hash;
  // $('#myTab a[href="' + hash + '"]').tab('show');

  // $('#btn_submit').click(function(){
  //     $('#tunggu').modal('show')
  //     $('#showRequestProjectID').modal('hide')
  //     setTimeout(function() {$('#tunggu').modal('hide');}, 10000);
  //  });

 $('#table-pid').on('click', '.btn-status', function(){

 	$("#id_pro_status").val(this.value);
 	$("#modal_status").modal("show");

 })

 $("#year_filter").change(function(){
  var filterYear = this.value;
    console.log(this.value)
    // $.ajax({
    //   url:"getFilterYearPID",
    //   type:"GET",
    //   data:{
    //     filterYear:this.value,
    //   },
    //   success:function(result){
        
    //     console.log(result)
    //   }
    // })
    $('#table-pid').DataTable().ajax.url("{{url('getFilterYearPID')}}?filterYear="+filterYear).load();
 })

</script>
@endsection