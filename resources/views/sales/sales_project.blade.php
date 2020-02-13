@extends('template.template_admin-lte')
@section('content')
<?php
header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
?>

<style type="text/css">

  .DTFC_LeftBodyLiner{overflow-y:unset !important}
  .DTFC_RightBodyLiner{overflow-y:unset !important}

  .dataTables_filter {
  display: none;
  }

  .dataTables_paging {
  display: none;
  }

  table.dataTable tbody th,
  table.dataTable tbody td {
  white-space: nowrap;
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
              <li class="active"><a href="#tab_1" data-toggle="tab">SIP</a></li>
              <li><a href="#tab_2" data-toggle="tab">MSP</a></li>
              <li><a href="#tab_3" data-toggle="tab">ID Request</a></li>
              <li><a href="#tab_4" data-toggle="tab">History Request</a></li>
              @else
              <li class="active"><a href="#tab_1" data-toggle="tab">SIP</a></li>
              <li><a href="#tab_2" data-toggle="tab">MSP</a></li>
              @endif
            @else
            @endif
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="box-header">
                <div class="row">
                  <div class="col-md-8">
                    @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
                    <a href="{{action('SalesController@export')}}" class="btn btn-warning btn-sm pull-left" style="margin-right: 10px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    <!-- <button class="btn btn-sm btn-primary pull-left" hidden data-target="#salesproject" style="width: 150px;" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd ID Project</button> -->
                    @else
                    <a href="{{action('SalesController@export')}}" style="margin-right: 10px" class="btn btn-warning btn-sm pull-left"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    @endif
                  </div>
                  <div class="col-md-4">
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
              <table class="table table-bordered table-striped display nowrap row-border order-column" id="sip-data" style="width: 100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>ID Project</th>
                      <th>Lead ID</th>
                      <th>No. PO Customer</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                      <th>Amount IDR</th>
                      <th>Amount Before Tax</th>
                      @endif
                      <th>Note</th>
                      <th>Invoice</th>
                      <th>Sales</th>
                      <th>Status</th>
                      @if(Auth::User()->id_division == 'FINANCE' || Auth::User()->id_division == 'PMO')
                      <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($salessp as $data)
                    <tr>
                      <td>{{date( "d-m-Y", strtotime($data->date))}}</td>
                      <td>{{$data->id_project}}</td>
                      <td>{{$data->lead_id}}</td>
                      <td>{{$data->no_po_customer}}</td>
                      <td>
                        @if($data->lead_id == 'SIPPO2020')
                        {{$data->customer_name}}
                        @else
                        {{$data->customer_legal_name}}
                        @endif
                      </td>
                      <td>
                        @if($data->lead_id == 'SIPPO2020')
                        {{$data->name_project}}
                        @else
                        {{$data->opp_name}}
                        @endif
                      </td>
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
                      <td>
                          <i class="">{{$data->amount_idr}}</i>
                      </td>
                      <td>
                          <i class="">{{round($data->amount_idr_before_tax,2)}}</i>
                      </td>
                      @endif
                        <td>{{$data->note}}</td>
                        <td>
                          @if($data->invoice == 'H')
                            Setengah Bayar
                          @elseif($data->invoice == 'F')
                            Sudah Bayar
                          @elseif($data->invoice == 'N')
                            Belum Bayar
                          @endif
                        </td>
                        <td>
                          @if($data->lead_id == 'SIPPO2020')
                            {{$data->sales_name}}
                            @else
                            {{$data->name}}
                            @endif
                        </td>
                        <td>
                          @if($data->progres == '')
                             UnProgress
                             @else
                             {{$data->progres}}
                            @endif
                        </td>
                      @if(Auth::User()->id_division == 'FINANCE')
                      <td>
                          @if(Auth::User()->id_position == 'MANAGER')
                          <button class="btn btn-xs btn-warning" style="width: 70px" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->name_project}}','{{$data->amount_idr}}','{{$data->note}}','{{$data->invoice}}','{{$data->lead_id}}','{{$data->opp_name}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                          <button class="btn btn-xs btn-danger" style="width: 70px" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                          @else
                          <button class="btn btn-xs btn-warning" style="width: 70px" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->name_project}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                          @endif
                      </td>
                      @elseif(Auth::User()->id_position == 'STAFF' || Auth::User()->id_division == 'PMO')
                      <td>
                          <button class="btn btn-xs btn-warning" style="width: 70px" data-target="#modal_status" data-toggle="modal" onclick="Edit_sp('{{$data->id_pro}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                      </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>                  
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                    <th></th>
                    <th></th>
                    @endif
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if(Auth::User()->id_division == 'FINANCE' || Auth::User()->id_division == 'PMO')
                    <th></th>
                    @endif
                  </tfoot>
              </table>
            </div>

            <div class="tab-pane" id="tab_2">
              <div class="box-header">
                <div class="row">
                  <div class="col-md-8">
                    @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
                    <a href="{{action('SalesController@export_msp')}}" class="btn btn-warning btn-sm pull-left" style="margin-right: 10px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    <button class="btn btn-sm btn-primary pull-left" data-target="#salesprojectmsp" style="width: 150px;display: none" data-toggle="modal"><i class="fa fa-plus"> </i>  &nbspAdd ID Project</button>
                    @else
                    <a href="{{action('SalesController@export_msp')}}" class="btn btn-warning btn-sm pull-left" style="margin-right: 10px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                    @endif
                  </div>
                  <div class="col-md-4">
                    <div class="input-group pull-right" style="margin-left: 10px">
                      <div class="input-group-btn">
                <button type="button" id="btnShowEntryTicketmsp" style="width: 110px" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Show 10 entries
                  <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu" id="selectShowEntryTicket">
                  <li><a href="#" onclick="changeNumberEntriesmsp(3)">10</a></li>
                  <li><a href="#" onclick="changeNumberEntriesmsp(25)">25</a></li>
                  <li><a href="#" onclick="changeNumberEntriesmsp(50)">50</a></li>
                  <li><a href="#" onclick="changeNumberEntriesmsp(100)">100</a></li>
                </ul>
              </div>
              <input id="searchBarTicketmsp" type="text" class="form-control" style="height: 30px" placeholder="Search Anything">
              <span class="input-group-btn">
                <button id="applyFilterTablePerformancemsp" type="button" class="btn btn-default btn-sm" style="width: 40px">
                  <i class="fa fa-fw fa-search"></i>
                </button>
              </span>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped display no-wrap dataTable" id="msp-data" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>ID Project</th>
                      <th>Lead ID</th>
                      <th>NO. PO Customer</th>
                      <th>NO. Quotation</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                      <th>Amount IDR</th>
                      <th>Amount Before Tax</th>
                      @else
                      @endif
                      <th>Note</th>
                      <th>Invoice</th>
                      <th>Sales</th>
                      @if(Auth::User()->id_division == 'FINANCE')
                      <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($salesmsp as $data)
                    <tr>
                      <td>{{date( "d-m-Y", strtotime($data->date))}}</td>
                      <td>
                        @if($data->status == 'SP')
                      <a href="{{ url ('/detail_sales_project', $data->id_pro) }}">{{$data->id_project}}</a><i class="fa fa-umbrella"></i>
                      @else
                      {{$data->id_project}}
                      @endif
                      </td>
                      <td>{{$data->lead_id}}</td>
                      <td>
                        @if($data->lead_id == "MSPPO")
                        {{$data->no_po_customer}}
                        @else
                        {{$data->no_po}}
                        @endif
                      </td>
                      <td>
                        @if($data->lead_id == "MSPQUO")
                        {{$data->no_po_customer}}
                        @else
                        {{$data->quote_number}}
                        @endif
                      </td>
                      <td>
                        @if($data->lead_id == 'MSPQUO' || $data->lead_id == 'MSPPO')
                        {{$data->customer_name}}
                        @else
                        {{$data->customer_legal_name}}
                        @endif
                      </td>
                      <td>
                        @if($data->lead_id == 'MSPQUO' || $data->lead_id == 'MSPPO')
                        {{$data->name_project}}
                        @else
                        {{$data->opp_name}}
                        @endif
                      </td>
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                      <td><i class="">{{$data->amount_idr}}</i></td>
                      <td><i class="">{{$data->amount_idr_before_tax}}</i></td>
                      @else
                      @endif
                      <td>{{$data->note}}</td>
                      <td>
                      @if($data->invoice == 'H')
                        Setengah Bayar
                      @elseif($data->invoice == 'F')
                        Sudah Bayar
                      @elseif($data->invoice == 'N')
                        Belum Bayar
                      @endif
                      </td>
                      <td>
                        @if($data->lead_id == 'MSPQUO' || $data->lead_id == 'MSPPO')
                        {{$data->sales_name}}
                        @else
                        {{$data->name}}
                        @endif
                      </td>
                    @if(Auth::User()->id_division == 'FINANCE')
                    <td>
                        @if(Auth::User()->id_position == 'MANAGER')
                        <button class="btn btn-xs btn-warning" style="width: 70px" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->name_project}}','{{$data->amount_idr}}','{{$data->note}}','{{$data->invoice}}','{{$data->lead_id}}','{{$data->opp_name}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                        <button class="btn btn-xs btn-danger" style="width: 70px" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                        @else
                        <button class="btn btn-xs btn-warning" style="width: 70px" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->name_project}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                        @endif
                    </td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                    <th></th>
                    <th></th>
                    @endif
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if(Auth::User()->id_division == 'FINANCE' || Auth::User()->id_division == 'PMO')
                    <th></th>
                    @endif
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="tab-pane" id="tab_3">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped display no-wrap dataTable" id="request_id" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Created</th>
                          <th>Company</th>
                          <th>Quote No.</th>
                          <th>Project</th>
                          <th>Sales</th>
                          <th>Date</th>
                          <th>Amount</th>
                          <th>Note</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($pid_request_lead as $pids)
                          <tr>
                            <td>{{$pids->created_at}}</td>
                            <td>{{$pids->code_company}}</td>
                            <td>
                              @if($pids->no_po == '')
                              {{$pids->quote_number}}
                              @else
                              {{$pids->no_po}}
                              @endif
                            </td>
                            <td>{{$pids->opp_name}}</td>
                            <td>{{$pids->name}}</td>
                            <td>
                              @if($pids->date_po == '')
                              {{$pids->date}}
                              @else
                              {{$pids->date_po}}
                              @endif
                            </td>
                            <td>
                              @if($pids->amount_pid == '')
                              <i class="money">{{$pids->amount}}</i>
                              @else
                              <i class="money">{{$pids->amount_pid}}</i>
                              @endif
                            </td>
                            <td>{{$pids->note}}</td>
                            <td>
                              <button class="btn btn-xs btn-primary" data-target="#showRequestProjectID" style="width: 100%" data-toggle="modal" onclick="acceptProjectID('{{$pids->id_pid}}')">Show</button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
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
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="tab_4">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped display no-wrap dataTable" id="history_pid" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Quote No.</th>
                          <th>Date</th>
                          <th>Amount</th>
                          <th>Note</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($pid_request_done as $pid)
                          <tr>
                            <td>{{$pid->no_quotation}}</td>
                            <td>{{$pid->date_quotation}}</td>
                            <td><i class="money">{{$pid->amount}}</i></td>
                            <td>{{$pid->note}}</td>
                            <td>
                              <small class="label label-success"><i class="fa fa-clock-o"></i>Done</small>
                            </td>
                          </tr>
                        @endforeach
                        @foreach($pid_request_lead_done as $pid)
                          <tr>
                            <td>{{$pid->no_po}}</td>
                            <td>{{$pid->date_po}}</td>
                            <td><i class="money">{{$pid->amount_pid}}</i></td>
                            <td>{{$pid->note}}</td>
                            <td>
                              <small class="label label-success"><i class="fa fa-clock-o"></i>Done</small>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
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
    </div>
  </div>

  <!--modal-->

<div class="modal fade" id="showRequestProjectID" role="dialog">
  <div class="modal-dialog">
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
        <div class="">Project ID Sedang diProses. . .</div>
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
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
  <!-- <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.20/sorting/numeric-comma.js"></script> -->
  <script type="text/javascript">

    /*function showRequestProjectID(id){
      $.ajax({
        type:"GET",
        url:"{{url('/salesproject/getRequestProjectID')}}",
        data:{
          id:id
        },
        success:function(result){
          $("#code_name").val(result.id_customer)
          $("#inputPO").val(result.no_po)
          $("#inputProject").val(result.project)
          $("#inputSales").val(result.name)
          $("#inputQuo").val(result.no_quotation)
          $("#inputDate").val(result.date)
          $("#inputAmount").val(result.amount)
          $("#inputNote").val(result.note)
        }
      })
    }*/

    function acceptProjectID(id){
      $.ajax({
        type:"GET",
        url:"{{url('/salesproject/getAcceptProjectID')}}",
        data:{
          id:id
        },
        success:function(result){
          $("#code_name").val(result.code)
          $("#inputCustomer").val(result.lead_id)
          $("#inputPO").val(result.no_po)
          $("#inputProject").val(result.opp_name)
          $("#inputSales").val(result.name)
          $("#inputQuo").val(result.quote_number)
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
    }

    function submitRequestID(){
      if($("#inputCustomer").val() == ""){
        customer_name = "MSPQUO"
      } else {
        customer_name = $("#inputCustomer").val()
      }
      $.ajax({
        type:"GET",
        url:"{{url('/store_sp')}}",
        data:{
          _token: "{{ csrf_token() }}",
          customer_name:customer_name,
          // sales:$("#inputCustomer").val(),
          date:moment($("#inputDate").val()).format('L'),
          amount:$("#inputAmount").val(),
          note:$("#inputNote").val(),
          p_order:$("#inputPO").val(),
          quote:$("#inputQuo").val(),
          id_cus:$("#code_name").val(),
          // id_customer_quotation:$("#code_name").val(),
          // payungs:$("#inputCustomer").val(),
        },
        success:function(result){
          location.reload()
        }
      })
    }

    $(".check-reset").click(function(){
        $('input[type=radio]').prop('checked', false);
    });

    $('#btn_submit').click(function(){
      $('#tunggu').modal('show')
      $('#showRequestProjectID').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 10000);
    });

    $('.date').datepicker("setDate",new Date());
    $('.money2').mask("000,000,000,000,000", {reverse: true});
    $('.money3').mask("000,000,000,000,000.00", {reverse: true});
    $('.money').mask('000,000,000,000,000', {reverse: true});

    var datasip = $('#sip-data').dataTable({
        "order": [[ 1, "desc" ]],
        "pageLength": 25,
        "bLengthChange": false,
        fixedColumns:   {
          leftColumns: 2
        },
        "autoWidth": false,
        responsive:true,
        "scrollX":true,
         "fnInitComplete": function(){
              // Disable TBODY scoll bars
              $('.dataTables_scrollBody').css({
                  'overflow': 'hidden',
                  'border': '0'
              });
              
              // Enable TFOOT scoll bars
              $('.dataTables_scrollFoot').css('overflow', 'auto');
              
              // Sync TFOOT scrolling with TBODY
              $('.dataTables_scrollFoot').on('scroll', function () {
                  $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
              });                    
          },
          @if (Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
        "footerCallback": function( row, data, start, end, display ) {
              var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

              var api = this.api(),data;  
              // Remove the formatting to get integer data for summation

              var total = api.column(6, {page:'current'}).data().sum();

              var filtered = api.column(6, {"filter": "applied"} ).data().sum();

              var totalpage = api.column(6).data().sum();

              var filteredgrand = api.column(7, {"filter": "applied"} ).data().sum();

              var totalpagegrand = api.column(7).data().sum();

              $( api.column( 5 ).footer() ).html("Total Amount");

              $( api.column( 6 ).footer() ).html(totalpage);

              $( api.column( 6 ).footer() ).html(filtered);

              $( api.column( 7 ).footer() ).html(totalpagegrand);

              $( api.column( 7 ).footer() ).html(filteredgrand);

        },
        @endif
    });

    $('#searchBarTicket').keyup(function(){
    datasip.search($('#searchBarTicket').val()).draw();
    })

    $('#applyFilterTablePerformance').click(function(){
      datasip.search($('#searchBarTicket').val()).draw();
    })

    function changeNumberEntries(number){
      $("#btnShowEntryTicket").html('Show ' + number + ' entries <span class="fa fa-caret-down"></span>')
      $("#sip-data").DataTable().page.len( number ).draw();
    }

    var datamsp = $('#msp-data').DataTable({
      "order": [[ 1, "desc" ]],
        "pageLength": 25,
        "bLengthChange": false, 
        "scrollX":true,
        "retrive" : true,
        fixedColumns:   {
          leftColumns: 2
        },
        "fnInitComplete": function(){
                  // Disable TBODY scoll bars
                  $('.dataTables_scrollBody').css({
                      'overflow': 'hidden',
                      'border': '0'
                  });
                  
                  // Enable TFOOT scoll bars
                  $('.dataTables_scrollFoot').css('overflow', 'auto');
                  
                  // Sync TFOOT scrolling with TBODY
                  $('.dataTables_scrollFoot').on('scroll', function () {
                      $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                  });                    
          },
          @if (Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
          "footerCallback": function( row, data, start, end, display ) {
                // var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

                var api = this.api(),data;
                // Remove the formatting to get integer data for summation

                var total = api.column(6, {page:'current'}).data().sum();

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
           @endif
    });

    $('#searchBarTicketmsp').keyup(function(){
      datamsp.search($('#searchBarTicketmsp').val()).draw();
    })

    $('#applyFilterTablePerformancemsp').click(function(){
      datamsp.search($('#searchBarTicketmsp').val()).draw();
    })

    $('#request_id').DataTable({
      "order": [[ 0, "desc" ]],
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
    });

    $('#history_pid').DataTable({
      "order": [[ 0, "desc" ]],
      "footerCallback": function( row, data, start, end, display ) {
              var numFormat = $.fn.dataTable.render.number('\,', '.',2).display;

              var api = this.api(),data;
              // Remove the formatting to get integer data for summation

              var total = api.column(2, {page:'current'}).data().sum();

              var filtered = api.column(2, {"filter": "applied"} ).data().sum();

              var totalpage = api.column(2).data().sum();

              $( api.column( 1 ).footer() ).html("Total Amount");

              $( api.column( 2 ).footer() ).html(numFormat(totalpage));

              $( api.column( 2 ).footer() ).html(numFormat(filtered));

        },
    })


    // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
   //      $($.fn.dataTable.tables(true)).DataTable()
   //         .columns.adjust()
   //         .responsive.recalc();
   //  });

    function changeNumberEntriesmsp(number){
    $("#btnShowEntryTicketmsp").html('Show ' + number + ' entries <span class="fa fa-caret-down"></span>')
    $("#msp-data").DataTable().page.len( number ).draw();
  }

    @if(Auth::User()->id_position == 'STAFF')
    function Edit_sp(id_project,no_po_customer,name_project,note,invoice,lead_id,opp_name)
    {
      $('#id_project_edit').val(id_project);
      $('#po_customer_edit').val(no_po_customer);
      $('#name_project_edit').val(name_project);
      $('#note_edit').val(note);
      if (invoice == 'H') {
        $('#invoice_edit_h').prop('checked', true);
      }
      else if (invoice == 'F') {
        $('#invoice_edit_f').prop('checked', true);
      }else if (invoice == 'N') {
        $('#invoice_edit_n').prop('checked', true);
      }
    }
    @else
    function Edit_sp(id_project,no_po_customer,name_project,amount_idr,note,invoice,lead_id,opp_name)
    {
      $('#id_project_edit').val(id_project);
      $('#po_customer_edit').val(no_po_customer);
      $('#name_project_edit').val(name_project);
      $('#amount_edit').val(amount_idr);
      $('#note_edit').val(note);
      if (invoice == 'H') {
        $('#invoice_edit_h').prop('checked', true);
      }
      else if (invoice == 'F') {
        $('#invoice_edit_f').prop('checked', true);
      }else if (invoice == 'N') {
        $('#invoice_edit_n').prop('checked', true);
      }
    }
    @endif

  function delete_project(lead_id,id_pro)
  {
     $('#id_pro').val(lead_id);
     $('#id_delete_pro').val(id_pro);
  }

  function status(id_pro)
  {
    $('#id_pro_status').val(id_pro);
  }

  $('#customer_name').select2();
  $('#contact_msp').select2();

  $("#alert").fadeTo(5000, 500).slideUp(500, function(){
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

  $(".cn").change(function(){
    // var x = document.getElementById("customer_name").value;
  //       console.log(x);

        var cn = this.value;
        console.log(cn);

        $.ajax({
          type:"GET",
          url:'/getleadpid',
          data:{
            lead_sp:cn,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $('.date').val(moment(value.date_po).format('L'));
              $('.amount_pid').val(value.amount_pid);
            }); 
          },
        }); 
  }) 

  </script>
@endsection