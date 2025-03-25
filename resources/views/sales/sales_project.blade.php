@extends('template.main')
@section('tittle')
ID Project
@endsection
@section('head_css')
  <link rel="preload" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
  <style type="text/css">
      div.dataTables_processing { z-index: 1; }
      .DTFC_LeftBodyLiner {
        overflow: hidden;
      }
      .dataTables_length{
        display: none
      }
      .dataTables_filter {
        display: none;
      }

      th, td { white-space: nowrap; }
      div.dataTables_wrapper {
          margin: 0 auto;
      }

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
        padding:7px 4px;
        /*border-radius:4px 0 0 4px;*/
      }

     .inputWithIcon{
        position:relative;
      }

     .inputWithIcon i{
        position:absolute;
        left:0;
        top:25px;
        padding:9px 8px;
        color:#aaa;
        transition:.3s;
      }

      .nav-tabs .badge{
          position: absolute;
          top: -10px;
          right: -10px;
          background: red;
      }
  </style>
@endsection
@section('content')
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
          <li class="tabs_item" id="tabs_sip" style="display: none;"><a href="#sip" id="sip" data-toggle="tab" onclick="changeTabs('SIP')">SIP</a></li>
          <li class="tabs_item" id="tabs_msp" style="display: none;"><a href="#msp" id="msp" data-toggle="tab" onclick="changeTabs('MSP')">MSP</a></li>
          <li class="tabs_item" id="tabs_request" style="display: none;"><a href="#request" id="request" data-toggle="tab" onclick="changeTabs('request')">ID Request</a></li>
          <li class="tabs_item" id="tabs_history" style="display: none;"><a href="#history" id="history" data-toggle="tab" onclick="changeTabs('history')">History Request</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active">
              <div class="row">
                <div class="col-md-8 col-xs-12" id="export-table">
                  <div class="form-group">
                    <button id="btnExportSip" onclick="exportPID('{{action('SalesController@export')}}')" class="btn btn-warning btn-flat btn-sm pull-left export" style="margin-right: 10px;width: 100px;font-size: 15px;display: none;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</button>
                    <button id="btnExportMsp" onclick="exportPID('{{action('SalesController@export_msp')}}')" class="btn btn-warning btn-flat pull-left export-msp" style="margin-right: 10px;display: none;;width: 100px;display: none;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</button>
                    <select style="margin-right: 5px;width: 100px" class="form-control btn-primary btn-flat" id="year_filter">
                        <option value="{{$year_now}}" selected> &nbsp{{$year_now}}</option>
                        @foreach($year_before as $years)
                          @if($years->year != $year_now)
                            <option value="{{$years->year}}"> &nbsp{{$years->year}}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12" id="search-table">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-btn">
                        <button type="button" id="btnShowPID" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          Show 10 entries
                        </button>
                        <ul class="dropdown-menu">
                          <li><a href="#" onclick="$('#table-pid').DataTable().page.len(10).draw();$('#btnShowPID').html('Show 10 entries')">10</a></li>
                          <li><a href="#" onclick="$('#table-pid').DataTable().page.len(25).draw();$('#btnShowPID').html('Show 25 entries')">25</a></li>
                          <li><a href="#" onclick="$('#table-pid').DataTable().page.len(50).draw();$('#btnShowPID').html('Show 50 entries')">50</a></li>
                          <li><a href="#" onclick="$('#table-pid').DataTable().page.len(100).draw();$('#btnShowPID').html('Show 100 entries')">100</a></li>
                        </ul>
                      </div>
                      <input id="searchBarTicket" type="text" class="form-control" placeholder="Search Anything">
                      <span class="input-group-btn">
                        <button id="applyFilterTablePerformance" type="button" class="btn btn-default btn-md">
                          <i class="fa fa-fw fa-search"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                  
                </div>
              </div>

	            <div id="pid-table" style="display:none">
	           	  <table class="table table-bordered table-striped display" id="table-pid" width="100%" cellspacing="0">
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
	                      <!-- <th>Amount</th> -->
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
	                    <!-- <th></th> -->
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
        <div class="form-group inputWithIcon inputIconBg">
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
        <button class="btn btn-primary" id="btn_submit" onclick="submitRequestID()" >
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
            <div style="display:none;" id="divFinanceVP">
              <div class="form-group">
                <label for="">No. PO Customer</label>
                <input type="text" name="po_customer_edit" id="po_customer_edit" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Project Name</label>
                <textarea type="text" name="name_project_edit" id="name_project_edit" class="form-control"></textarea>
              </div>

              <div class="form-group">
                <label for="">Amount</label>
                <div class="input-group">
                  <div class="input-group-addon" style="background-color:#aaa;color:white">
                    <b><i>Rp</i></b>
                  </div>
                  <input type="text" class="form-control money" placeholder="Enter Amount" name="amount_edit" id="amount_edit">
                </div>
              </div>

              <div class="form-group">
                <label for="">Date</label>
                <input type="date" name="date_edit" id="inputDateEdit" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label class="col-sm-12">Kontrak Customer</label>
                <select name="statusKontrakCustomer" id="statusKontrakCustomer" class="form-control select2">
                  <option value="">Select Status Kontrak Customer</option>
                  <option value="UnAvailable">UnAvailable</option>
                  <option value="On Review">On Review</option>
                  <option value="On Progress">On Progress</option>
                  <option value="Done">Done</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="col-sm-6">Notes</label>
                <textarea name="notesKontrakCustomer" id="notesKontrakCustomer" class="form-control"></textarea>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label class="col-sm-12">Kontrak Vendor</label>
                <select name="statusKontrakVendor" id="statusKontrakVendor" class="form-control select2">
                  <option value="">Select Status Kontrak Vendor</option>
                  <option value="UnAvailable">UnAvailable</option>
                  <option value="On Review">On Review</option>
                  <option value="On Progress">On Progress</option>
                  <option value="Done">Done</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="col-sm-6">Notes</label>
                <textarea name="notesKontrakVendor" id="notesKontrakVendor" class="form-control"></textarea>
              </div>
            </div>

            <!-- <div class="form-group row">
              <div class="col-sm-6">
                <label class="col-sm-12">Invoice Customer</label>
                <select name="statusInvoiceCustomer" id="statusInvoiceCustomer" class="form-control select2">
                  <option value="">Select Status Kontrak Vendor</option>
                  <option value="UnAvailable">UnAvailable</option>
                  <option value="On Progress">On Progress</option>
                  <option value="Done">Done</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="col-sm-12">Notes</label>
                <textarea name="notesInvoiceCustomer" id="notesInvoiceCustomer" class="form-control"></textarea>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label class="col-sm-12">Invoice Vendor</label>
                <select name="statusInvoiceVendor" id="statusInvoiceVendor" class="form-control select2">
                  <option value="">Select Status Kontrak Vendor</option>
                  <option value="UnAvailable">UnAvailable</option>
                  <option value="On Progress">On Progress</option>
                  <option value="Done">Done</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="col-sm-12">Notes</label>
                <textarea name="notesInvoiceVendor" id="notesInvoiceVendor" class="form-control"></textarea>
              </div>
            </div> -->

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-warning"><i class="fa fa-check">&nbsp</i>Edit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="notesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Notes</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="notesDetails"></p>
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
@section('scriptImport')  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
@endsection
@section('script')
<script type="text/javascript">
  $('.money').mask('000,000,000,000,000', {reverse: true});

  if ( window.location.href.split("/")[3].split("#")[1] == 'submitIdProject') {
    $.ajax({
      type:"GET",
      url:"{{url('/salesproject/getAcceptProjectID')}}",
      data:{
        id:window.location.href.split("/")[4],
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

    $("#showRequestProjectID").modal("show")
  }

  $(document).on("click", ".notes-preview", function () {
    var fullText = $(this).data("fulltext");
    $("#notesDetails").html(fullText);
  });

  $(document).on("click", "#notesModalCustomer", function () {
    var fullText = $(this).data("fulltext");
    $("#notesDetailsCustomer").html(fullText);
  });


  $(document).ready(function(){
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()  
    })

    if ( window.location.href.split("/")[3].split("#")[1] == 'submitIdProject') {
      $.ajax({
        type:"GET",
        url:"{{url('/salesproject/getAcceptProjectID')}}",
        data:{
          id:window.location.href.split("/")[4],
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

      $("#showRequestProjectID").modal("show")
    }
  })  

  function exportPID(url){
    window.location = url + "?year=" + $("#year_filter").val();
  }

  function initiateTablePID(id,year){
    var accesable = @json($feature_item);
    if (id == "SIP") {
      if ($.fn.DataTable.isDataTable('#table-pid')) {
        $('#table-pid').DataTable().clear().destroy();
        $('#table-pid').empty();
      }

      if ((accesable.includes('amount_pid'))) {
        $("#table-pid").append('<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>')
      }
      
      $.extend($.fn.dataTable.defaults, {
          sDom: '<"top"i>rCt<"footer"><"bottom"flp><"clear">'
      });

      table = $("#table-pid").DataTable({
        fnFooterCallback: function(nRow, aaData, iStart, iEnd, aiDisplay) {
          var api = this.api();
          var size = 0;
          aaData.forEach(function(x) {
              size += (x['size']);
          });
          $('.footer').html(size);
        },
        "footerCallback": function( row, data, start, end, display ) {
          var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

          var api = this.api(),data;  
          // Remove the formatting to get integer data for summation

          // console.log(api.column(7))
          // Remove the formatting to get integer data for summation

          var total = api.column(17, {page:'current'}).data().sum();

          var filtered = api.column(17, {"filter": "applied"} ).data().sum();

          var totalpage = api.column(17).data().sum();

          var filteredgrand = api.column(18, {"filter": "applied"} ).data().sum();

          var totalpagegrand = api.column(18).data().sum();

          $( api.column( 5 ).footer() ).addClass('text-right');
          $( api.column( 5 ).footer() ).html("Total Amount");


          $( api.column( 6 ).footer() ).html(new Intl.NumberFormat('id').format(totalpage));


          $( api.column( 6 ).footer() ).html(new Intl.NumberFormat('id').format(filtered));


          $( api.column( 7 ).footer() ).html(new Intl.NumberFormat('id').format(totalpagegrand));


          $( api.column( 7 ).footer() ).html(new Intl.NumberFormat('id').format(filteredgrand));
        },
        "ajax":{
            "type":"GET",
            "url":"{{url('getPIDIndex')}}",
            "data":{
              id:id,
              year_filter:$("#year_filter").val()
            }
        },
        "columns": [
          { 
            "title":"Date",
            "data": "date" 
          },
          { 
            "title":"ID Project",
            "data": "id_project" 
          },
          {
            "title":"Lead ID", 
            "data": "lead_id" 
          },
          { // No Po
            "title":"No. PO Customer",
            render: function ( data, type, row ) {
              if (row.id_company == 1) {
                if (row.no_po_customer == null) {
                  return row.quote_number_final 
                }else if (row.no_po_customer) {
                  return row.no_po_customer
                }else{
                  return "-"
                }
              }else{
                if (row.lead_id == "MSPPO") {
                  return row.no_po_customer
                }else if (row.no_po) {
                  return row.no_po
                }else{
                  return "-"
                }
              }
            }
          },
          { // Customer Name
            "title":"Customer Name",
            render: function ( data, type, row ) {
              if (row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                return row.customer_name
              }else if (row.customer_legal_name) {
                return row.customer_legal_name
              }else{
                return "-" 
              }
            }
          },
          { 
            "title":"Project Name",
            "data": "name_project" },
          {
            "title":"Amount IDR",
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.amount_idr)
            }
          },
          {
            "title":"Amount Before Tax",
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.amount_idr_before_tax).includes(",") ? Intl.NumberFormat('id').format(row.amount_idr_before_tax) : Intl.NumberFormat('id').format(row.amount_idr_before_tax) + ",000"
            }
          },
          { // Status
            "title":"Status",
            render: function ( data, type, row ) {
              if (row.progres == null) {
                return "UnProgress";  
              }else {
                return row.progres;
              }
            }
          },
          { // Sales
            "title":"Sales",
            render: function ( data, type, row ) {
              if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                return row.sales_name;  
              }else{
                return row.name;  
              }
            }
          },
          {
            "title":"Project Type",
            "data": "project_type"
          },
          { 
            "title":"Current Phase",
            "data": "current_phase" 
          },
          { // Kontrak Customer
            "title":"Kontrak Customer",
            "data": "kontrak_customer"
          },
          { // Notes Kontrak Customer
            "title":"Notes Kontrak Customer",
            "data": "notes_kontrak_customer",
            "render": function (data, type, row) {
              if (type === "display" && data) {
                  let firstLine = data.split('<br>')[0];
                  let displayText = firstLine.length > 40 ? firstLine.substring(0, 40) + "..." : firstLine; 

                  return `<span class="notes-preview" data-toggle="modal" data-target="#notesModal" 
                        data-fulltext="${data}">
                        ${displayText}
                    </span>`;
              }
              return data;
            }
          },
          { // Kontrak Vendor
            "title":"Kontrak Vendor",
            "data": "kontrak_vendor"
          },
          { // Notes Kontrak Vendor
            "title":"Notes Kontrak Vendor",
            "data": "notes_kontrak_vendor",
            "render": function (data, type, row) {
              if (type === "display" && data) {
                  let firstLine = data.split('<br>')[0];
                  let displayText = firstLine.length > 40 ? firstLine.substring(0, 40) + "..." : firstLine; 

                  return `<span class="notes-preview" data-toggle="modal" data-target="#notesModal" 
                        data-fulltext="${data}">
                        ${displayText}
                    </span>`;
              }
              return data;
            }
          },
          { // Action
            "title":"Action",
            render: function ( data, type, row ) {
              if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                  return ''
              }else{
                return '<button class="btn btn-xs btn-warning btn-edit" style="width: 70px" value="'+row.id_pro+'"><i class="fa fa-edit"></i>&nbspEdit</button>' + ' ' + '<button class="btn btn-xs btn-danger btn-delete" value="'+row.id_pro+'" style="width: 70px"><i class="fa fa-trash"></i>&nbspDelete</button>'
              }
                              
            }
          },
          { 
            "data": "amount_idr"
          },
          { 
            "data": "amount_idr_before_tax"
          },
        ],
        "scrollX": true,
        "pageLength": 25,
        "order": [[ 1, "desc" ]],
        "processing": true,
        "language": {
          'loadingRecords': '&nbsp;',
          'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        }, 
        "scrollCollapse":true,
        fixedColumns:   {
          leftColumns: 2,
          rightColumns: 1
        },
        columnDefs: [
          {
            targets: [7],
            orderData: [17],
            className: 'text-right',
          },
          {
            targets: [8],
            orderData: [18],
            className: 'text-right'
          },
          {
            targets: [17],
            visible: false,
            searchable: false
          }
          ,{
            targets: [18],
            visible: false,
            searchable: false
          }
        ]
      });

      if (!(accesable.includes('amount_pid'))) {
        // Column Amount IDR
        var column1 = table.column(6);
        column1.visible(!column1.visible() );
        // Column Amount IDR Before Tax
        var column2 = table.column(7);
        column2.visible(!column2.visible() );
      }

      if (!(accesable.includes('btnEdit'))) {
        //action
        var column3 = table.column(16);
        column3.visible(!column3.visible());
      }
    }else{
      if ($.fn.DataTable.isDataTable('#table-pid')) {
        $('#table-pid').DataTable().clear().destroy();
        $('#table-pid').empty();
      }

      $("#table-pid").append('<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>')

      $.extend($.fn.dataTable.defaults, {
          sDom: '<"top"i>rCt<"footer"><"bottom"flp><"clear">'
      });

      table = $("#table-pid").DataTable({
        fnFooterCallback: function(nRow, aaData, iStart, iEnd, aiDisplay) {
          var api = this.api();
          var size = 0;
          aaData.forEach(function(x) {
              size += (x['size']);
          });
          $('.footer').html(size);
        },
        "footerCallback": function( row, data, start, end, display ) {
          var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

          var api = this.api(),data;
          var footer = $(this).append('<tfoot><tr></tr></tfoot>');
          this.api().columns().every(function () {
            var column = this;
            $(footer).append('<th></th>');
          });  
          // Remove the formatting to get integer data for summation

          // console.log(api.column(7))
          // Remove the formatting to get integer data for summation

          var total = api.column(12, {page:'current'}).data().sum();

          var filtered = api.column(12, {"filter": "applied"} ).data().sum();

          var totalpage = api.column(12).data().sum();

          var filteredgrand = api.column(13, {"filter": "applied"} ).data().sum();

          var totalpagegrand = api.column(13).data().sum();

          $( api.column( 6 ).footer() ).addClass('text-right');
          $( api.column( 6 ).footer() ).html("Total Amount");


          $( api.column( 7 ).footer() ).html(new Intl.NumberFormat('id').format(totalpage));


          $( api.column( 7 ).footer() ).html(new Intl.NumberFormat('id').format(filtered));


          $( api.column( 8 ).footer() ).html(new Intl.NumberFormat('id').format(totalpagegrand));


          $( api.column( 8 ).footer() ).html(new Intl.NumberFormat('id').format(filteredgrand));
        },
        "ajax":{
            "type":"GET",
            "url":"{{url('getPIDIndex')}}",
            "data":{
              id:id,
              year_filter:year,
            }
        },
        "columns": [
          { 
            "title":"Date",
            "data": "date" 
          },
          { 
            "title":"ID Project",
            "data": "id_project" 
          },
          {
            "title":"Lead ID", 
            "data": "lead_id" 
          },
          { // No Po
            "title":"No. PO Customer",
            render: function ( data, type, row ) {
              if (row.id_company == 1) {
                if (row.no_po_customer == null) {
                  return row.quote_number_final 
                }else if (row.no_po_customer) {
                  return row.no_po_customer
                }else{
                  return "-"
                }
              }else{
                if (row.lead_id == "MSPPO") {
                  return row.no_po_customer
                }else if (row.no_po) {
                  return row.no_po
                }else{
                  return "-"
                }
              }
            }
          },
          { // No Quotation
            "title":"No. Quotation",
            render: function ( data, type, row ) {
              if (row.id_company == 1) {
                return "-";
              }else{
                if (row.lead_id == "MSPQUO") {
                  return row.no_po_customer;
                }else if (row.quote_number) {
                  return row.quote_number;  
                }else{
                  return "-";  
                }
              }
            }
          },
          { // Customer Name
            "title":"Customer Name",
            render: function ( data, type, row ) {
              if (row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                return row.customer_name
              }else if (row.customer_legal_name) {
                return row.customer_legal_name
              }else{
                return "-" 
              }
            }
          },
          { 
            "title":"Project Name",
            "data": "name_project" },
          {
            "title":"Amount IDR",
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.amount_idr)
            }
          },
          {
            "title":"Amount Before Tax",
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.amount_idr_before_tax).includes(",") ? Intl.NumberFormat('id').format(row.amount_idr_before_tax) : Intl.NumberFormat('id').format(row.amount_idr_before_tax) + ",000"
            }
          },
          { // Status
            "title":"Status",
            render: function ( data, type, row ) {
              if (row.progres == null) {
                return "UnProgress";  
              }else {
                return row.progres;
              }
            }
          },
          { // Sales
            "title":"Sales",
            render: function ( data, type, row ) {
              if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                return row.sales_name;  
              }else{
                return row.name;  
              }
            }
          },
          { // Action
            "title":"Action",
            render: function ( data, type, row ) {
              if (row.lead_id == 'SIPPO2020' || row.lead_id == 'MSPQUO' || row.lead_id == 'MSPPO') {
                  return ''
              }else{
                return '<button class="btn btn-xs btn-warning btn-edit" style="width: 70px" value="'+row.id_pro+'"><i class="fa fa-edit"></i>&nbspEdit</button>' + ' ' + '<button class="btn btn-xs btn-danger btn-delete" value="'+row.id_pro+'" style="width: 70px"><i class="fa fa-trash"></i>&nbspDelete</button>'
              }
                              
            }
          },
          { 
            "data": "amount_idr"
          },
          { 
            "data": "amount_idr_before_tax"
          },
        ],
        "scrollX": true,
        "pageLength": 25,
        "order": [[ 1, "desc" ]],
        "processing": true,
        "language": {
          'loadingRecords': '&nbsp;',
          'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        }, 
        "scrollCollapse":true,
        fixedColumns:   {
          leftColumns: 2,
          rightColumns: 1
        },
        columnDefs: [
          {
            targets: [7],
            orderData: [12],
            className: 'text-right',
          },
          {
            targets: [8],
            orderData: [13],
            className: 'text-right'
          },
          {
            targets: [12] ,
            visible: false,
            searchable: false
          }
          ,{
            targets: [13] ,
            visible: false,
            searchable: false
          }
        ]
      });
    }

    $(document).ready(function() {
      $('.select2').select2({
        width: '100%' // Makes the dropdown full width
      });
    });

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
  
  var request_table = $("#request_id").DataTable({
  "footerCallback": function( row, data, start, end, display ) {
          var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

          var api = this.api(),data;
          // Remove the formatting to get integer data for summation

          var total = api.column(8, {page:'current'}).data().sum();

          var filtered = api.column(8, {"filter": "applied"} ).data().sum();

          var totalpage = api.column(8).data().sum();

          $( api.column( 5 ).footer() ).html("Total Amount");

          $( api.column( 6 ).footer() ).html(numFormat(totalpage));

          $( api.column( 6 ).footer() ).html(numFormat(filtered));

    },
  "ajax":{
    "type":"GET",
    "url":"{{url('getShowPIDReq')}}",
    },
    "columns": [
      { "data": "date_po" },
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
      {
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_pid)
        }, 
        "orderData" : [ 8 ],
      },
      {
        render: function ( data, type, row ) {
        	if (row.status == 'requested') {
        		return '<button class="btn btn-xs btn-primary btn-show" data-toggle="modal" value="'+row.id_pid+'">Show</button>'
        	}else if(row.status == 'done'){
        		return '<small class="label label-success"><i class="fa fa-clock-o"></i> Done</small>'
        	}
        	
        }
      },
      { "data": "amount_pid"},
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
    lengthChange:false,
    initComplete: function() {
      if("{{Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE'}}"){
        if (this.api().data().length) {
          $('#request').append('<span class="badge">'+ this.api().data().length +'</span>')
          $('#tabs_request').addClass('active')   
          changeTabs('request')
        }else{
          $('#tabs_sip').addClass('active')
          changeTabs('SIP')
        }
      }else{
        $('#tabs_sip').addClass('active')
        changeTabs('SIP')
      }
    },
    fixedColumns:   {
      leftColumns: 2,
    },
    columnDefs: [
      {
        targets: [8] ,
        visible: false,
        searchable: false
      },
    ]
  });	

  function submitRequestID(){
    if($("#inputCustomer").val() == ""){
      customer_name = "MSPQUO"
    } else {
      customer_name = $("#inputCustomer").val()
    }

    Swal.fire({
          title: 'Are you sure?',  
          text: "Submit Request PID",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
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
            type: "GET",
            url: "{{url('/store_sp')}}",
            data: {
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
            success: function(result) {
              Swal.showLoading()
              Swal.fire(
                  'Successfully!',
                  'Project ID Created.',
                  'success'
              ).then((result) => {
                  if (result.value) {
                    location.reload()
                    $("#showRequestProjectID").modal('hide')
                  }
              })
            }
          })          
        }
      })
  }

  $('#table-pid').on('click', '.btn-edit', function(){
  	$('#tunggu').modal('show');
    $.ajax({
      type:"GET",
      url:'{{url("getEditPID")}}',
        data:{
        id_pro:this.value,
      },
      success: function(result){
        	  $('#tunggu').modal('hide');
            $.each(result[0], function(key, value){
              $('#id_project_edit').val(value.id_project);
              $('#notesInvoiceVendor').val(value.notes_invoice_vendor.replace(/<br\s*\/?>/g, '\n'));
              $('#notesInvoiceCustomer').val(value.notes_invoice_customer.replace(/<br\s*\/?>/g, '\n'));
              $('#notesKontrakVendor').val(value.notes_kontrak_vendor.replace(/<br\s*\/?>/g, '\n'));
              $('#notesKontrakCustomer').val(value.notes_kontrak_customer.replace(/<br\s*\/?>/g, '\n'));
              $('#statusInvoiceCustomer').val(value.invoice_customer).trigger('change');
              $('#statusInvoiceVendor').val(value.invoice_vendor).trigger('change');
              $('#statusKontrakCustomer').val(value.kontrak_customer).trigger('change');
              $('#statusKontrakVendor').val(value.kontrak_vendor).trigger('change');
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
      	      else if (value.invoice == 'F') {
      	        $('#invoice_edit_f').prop('checked', true);
      	      }else if (value.invoice == 'N') {
      	        $('#invoice_edit_n').prop('checked', true);
      	      }
              if (value.date == null) {
                $("#inputDateEdit").val(value.date);
              }else{
                $("#inputDateEdit").val(value.date);
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
		// $('#tunggu').modal('show');
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
          $("#inputQuo").val(result.quote_number_final)
          if (result.date_po == null) {
            $("#inputDate").val(result.date)
          }else{
            $("#inputDate").val(result.date_po)
          }
          if (result.amount_pid == null) {
            $("#inputAmount").val(result.amount).mask('000,000,000,000', {reverse: true})
          }else{
            $("#inputAmount").val(result.amount_pid).mask('000,000,000,000', {reverse: true})
          }
          $("#inputNote").val(result.note)
        }
      })

      $("#showRequestProjectID").modal("show");
    });

    function changeTabs(id) {
      year = $("#year_filter").val()
      if (id == "SIP") {
      	$('#export-table').css("display","block")
      	$('#search-table').css("display","block")
      	$('#request-table').css("display","none")
      	$('#history-table').css("display","none")
      	$('#pid-table').css("display","block")
      	$('.export-msp').css("display","none")
        $('#search-table').find("input").val("")
      	$('.export').css("display","block")

        initiateTablePID(id,year)
      }else if(id == "MSP"){
      	$('.export-msp').css("display","block")
      	$('.export').css("display","none")
      	$('#export-table').css("display","block")
      	$('#search-table').css("display","block")
      	$('#request-table').css("display","none")
      	$('#history-table').css("display","none")
        $('#search-table').find("input").val("")
      	$('#pid-table').css("display","block")

        initiateTablePID(id,year)
      }else if (id == "request") {
      	$('#request-table').show()
      	$('#pid-table').css("display","none")
      	$('#export-table').css("display","none")
      	$('#search-table').css("display","block")
        $('#search-table').addClass("pull-right")
        $('#search-table').find("input").val("")
      	$('#request_id').DataTable().ajax.url("{{url('getShowPIDReq')}}?id="+id).load();
        request_table.search($('#searchBarTicket').val()).draw();

        $('#searchBarTicket').keyup(function(){
          request_table.search($('#searchBarTicket').val()).draw();
        })

        $('#applyFilterTablePerformance').click(function(){
          request_table.search($('#searchBarTicket').val()).draw();
        })
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
    var companyString = $('.tabs_item.active').text()
    var year = this.value

    initiateTablePID(companyString,year)
    // @if(Auth::User()->id_division == 'FINANCE')
      
    //   var companyString = $('.tabs_item.active').text()
    //   // console.log(companyString)
    //   var com_id
    //   if (companyString == "SIP") {
    //     com_id = 1
    //   }else{
    //     com_id = 2
    //   }
    //   if(companyString == "SIP" || companyString == "MSP"){
    //     $('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+companyString+"&year_filter=" + this.value).load();
    //   } 

    // @else
    //   // console.log(companyString)
    //   var com_id
    //   if (companyString == "SIP") {
    //     com_id = 1
    //   }else{
    //     com_id = 2
    //   }
    //   if(companyString == "SIP" || companyString == "MSP"){
    //     $('#table-pid').DataTable().ajax.url("{{url('getPIDIndex')}}?id="+companyString+"&year_filter=" + this.value).load();

    //     // $('#table-pid').DataTable().ajax.url("{{url('getFilterYearPID')}}?filterYear="+filterYear+"&id=" + com_id).load();
    //   } 
    //   // $('#table-pid').DataTable().ajax.url("{{url('getFilterYearPID')}}?filterYear="+filterYear).load();
    // @endif 
 })

</script>
@endsection