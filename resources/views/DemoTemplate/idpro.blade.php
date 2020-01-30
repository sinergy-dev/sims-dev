@extends('template.template')
@section('content')
<style type="text/css">
.radios {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 14px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.radios input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On radiosmouse-over, add a grey background color */
.radios:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.radios input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.radios input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.radios .checkmark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
</style>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">ID Project</a>
        </li>
      </ol>

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your PID :<h4> {{$pops->id_project}}</h4></div>
      @elseif (session('error'))
        <div class="alert alert-danger notification-bar" id="alert"><span>notice: </span> {{ session('error') }}.</div>
      @endif

      <div class="row">
        
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> ID Project Table
     <!--      @if(Auth::User()->id_division == 'FINANCE')
          <a href="{{action('SalesController@export')}}" class="btn btn-warning float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
          @endif -->
        </div>
        <div class="card-body">
          @if(Auth::User()->id_division == 'FINANCE')
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">      
            <li class="nav-item">
              <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-sip" role="tab" aria-controls="pills-profile" aria-selected="true">SIP</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-msp" role="tab" aria-controls="pills-home" aria-selected="false">MSP</a>
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
             <div class="tab-pane fade show active" id="pills-sip" role="tabpanel" aria-labelledby="pills-profile-tab">
              <div class="table-responsive">
                @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
                <div class="col-md-12">
                   <a href="{{action('SalesController@export')}}" class="btn btn-warning btn-sm float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                   <button class="btn btn-primary-lead margin-bottom pull-right" data-target="#salesproject" data-toggle="modal" ><i class="fa fa-plus"> </i> &nbspAdd ID Project</button>
                </div><br><br>
                @endif
                <table class="table table-bordered display nowrap dataTable" id="sip-data" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>ID Project</th>
                      <th>Lead ID</th>
                      <th>NO. PO Customer</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                      <th>Amount IDR</th>
                      @endif
                      <th>Note</th>
                      <th>invoice</th>
                      <th>Sales</th>
                      @if(Auth::User()->id_division == 'FINANCE')
                      <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($salessp as $data)
                    <tr>
                      <td>{{$data->date}}</td>
                      <td>{{$data->id_project}}</td>
                      <td>{{$data->lead_id}}</td>
                      <td>{{$data->no_po_customer}}</td>
                      <td>{{$data->customer_legal_name}}</td>
                      <td>{{$data->opp_name}}</td>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                      <td><i class="money">{{$data->amount_idr}}</i></td>
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
                      <td>{{$data->name}}</td>
                      <td>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                        <!-- <a href="{{ url('delete_project?id_pro='.$data->id_pro) }}"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i>&nbspDelete</button></a> -->
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                      @elseif(Auth::User()->id_position == 'STAFF')
                      <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                      </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
             </div>
             <div class="tab-pane fade" id="pills-msp" role="tabpanel" aria-labelledby="pills-home-tab">
             @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
              <div class="col-md-12">
                  <a href="{{action('SalesController@export_msp')}}" class="btn btn-warning btn-sm float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
                  <button class="btn btn-primary-lead margin-bottom pull-right" data-target="#salesprojectmsp" data-toggle="modal" ><i class="fa fa-plus"> </i> &nbspAdd ID Project</button>
              </div><br><br>
              @endif
              <div class="table-responsive">
                <table class="table table-bordered nowrap dataTable" id="msp-data" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>ID Project</th>
                      <th>Lead ID</th>
                      <th>NO. PO Customer</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      <!-- <th>Amount USD</th> -->
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_division == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                      <th>Amount IDR</th>
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
                      <td>{{$data->date}}</td>
                      <td>{{$data->id_project}}</td>
                      <td>{{$data->lead_id}}</td>
                      <td>{{$data->no_po_customer}}</td>
                      <td>{{$data->customer_legal_name}}</td>
                      <td>{{$data->opp_name}}</td>
                      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_division == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                      <td><i class="money">{{$data->amount_idr}}</i></td>
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
                      <td>{{$data->name}}</td>
                      <td>
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                        <!-- <a href="{{ url('delete_project?id_pro='.$data->id_pro) }}"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i>&nbspDelete</button></a> -->
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                      @elseif(Auth::User()->id_position == 'STAFF')
                      <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                      </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
             </div>
          </div>
          @elseif(Auth::User()->id_division != 'FINANCE')
            @if(Auth::User()->id_company == '1')
              <div class="table-responsive">
                  <table class="table table-bordered display nowrap dataTable" id="sip-prj" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>ID Project</th>
                        <th>Lead ID</th>
                        <th>NO. PO Customer</th>
                        <th>Customer Name</th>
                        <th>Project Name</th>
                        <!-- <th>Amount USD</th> -->
                        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE')
                        <th>Amount IDR</th>
                        @else
                        @endif
                        <th>Note</th>
                        <th>Sales</th>
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <th>Action</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody id="products-list" name="products-list">
                      @foreach($salessp as $data)
                      <tr>
                        <td>{{$data->date}}</td>
                        <td>{{$data->id_project}}</td>
                        <td>{{$data->lead_id}}</td>
                        <td>{{$data->no_po_customer}}</td>
                        <td>{{$data->customer_legal_name}}</td>
                        <td>{{$data->opp_name}}</td>
                        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE')
                        <td><i class="money">{{$data->amount_idr}}</i></td>
                        @else
                        @endif
                        <td>{{$data->note}}</td>
                        <td>{{$data->name}}</td>
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <td>
                         <!--  <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_salessp" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->amount_usd}}')"></button>
                          <a href="{{ url('delete_project?id_project='. $data->id_project) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                          </button></a> -->
                          <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                          <!-- <a href="{{ url('delete_project?id_pro='.$data->id_pro) }}"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i>&nbspDelete</button></a> -->
                          <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                        </td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
            @elseif(Auth::User()->id_company == '2')
              <div class="table-responsive">
                  <table class="table table-bordered display nowrap dataTable" id="msp-prj" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>ID Project</th>
                        <th>Lead ID</th>
                        <th>NO. PO Customer</th>
                        <th>Customer Name</th>
                        <th>Project Name</th>
                        <!-- <th>Amount USD</th> -->
                        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE')
                        <th>Amount IDR</th>
                        @else
                        @endif
                        <th>Note</th>
                        <th>Sales</th>
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <th>Action</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody id="products-list" name="products-list">
                      @foreach($salesmsp as $data)
                      <tr>
                        <td>{{$data->date}}</td>
                        <td>{{$data->id_project}}</td>
                        <td>{{$data->lead_id}}</td>
                        <td>{{$data->no_po_customer}}</td>
                        <td>{{$data->customer_legal_name}}</td>
                        <td>{{$data->opp_name}}</td>
                        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE')
                        <td><i class="money">{{$data->amount_idr}}</i></td>
                        @else
                        @endif
                        <td>{{$data->note}}</td>
                        <td>{{$data->name}}</td>
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE')
                        <td>
                         <!--  <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_salessp" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->amount_usd}}')"></button>
                          <a href="{{ url('delete_project?id_project='. $data->id_project) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                          </button></a> -->
                          <button class="btn btn-sm btn-warning" data-target="#edit_salessp" data-toggle="modal" onclick="Edit_sp('{{$data->id_project}}','{{$data->no_po_customer}}','{{$data->opp_name}}','{{$data->amount_idr}}','{{$data->amount_usd}}','{{$data->note}}','{{$data->invoice}}')"><i class="fa fa-edit"></i>&nbspEdit</button>
                          <!-- <a href="{{ url('delete_project?id_pro='.$data->id_pro) }}"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i>&nbspDelete</button></a> -->
                          <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_delete" onclick="delete_project('{{$data->lead_id}}','{{$data->id_pro}}')"><i class="fa fa-trash"></i>&nbspDelete</button>
                        </td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
            @endif
          @endif
          
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
  </div>
</div>

<!--add project SIP-->
<div class="modal fade" id="salesproject" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add ID Project</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('store_sp')}}">
              @csrf
            <div class="form-group">
              <label for="">Date</label>
              <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="">No. PO Customer</label>
              <input type="text" name="po_customer" id="po_customer" class="form-control">
            </div>

            <div class="form-group">
              <label for="">Lead ID</label>
                <!-- <input list="browsers" name="customer_name" id="customer_name" class="form-control">
                
                <datalist id="browsers">
                </datalist> -->
                <select name="customer_name" id="customer_name" style="width: 100%;" class="form-control">
                  <option>-- Select Lead ID --</option>
                  @foreach($lead_sp as $data)
                  <option value="{{$data->lead_id}}">
                  @if($data->pid == NULL)
                  <b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
                    @else
                  ( {{$data->pid}} )&nbsp<b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
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

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div>

            <div class="form-group">
              <label for="">Note</label>
              <input type="text" placeholder="Enter Note" name="note" id="note" class="form-control">
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
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add ID Project</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('store_sp')}}">
              @csrf
            <div class="form-group">
              <label for="">Date</label>
              <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="">No. PO Customer</label>
              <input type="text" name="po_customer" id="po_customer" class="form-control">
            </div>

            <div class="form-group">
              <label for="">Lead ID</label>
                <!-- <input list="browsers" name="customer_name" id="customer_name" class="form-control">
                
                <datalist id="browsers">
                </datalist> -->
                <select name="customer_name" id="contact_msp" style="width: 100%" class="form-control">
                  <option>-- Select Lead ID --</option>
                  @foreach($lead_msp as $data)
                  <option value="{{$data->lead_id}}">
                  @if($data->pid == NULL)
                  <b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
                  @else
                  ( {{$data->pid}} )&nbsp<b>{{$data->lead_id}}</b> &nbsp {{$data->opp_name}}
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

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div>

            <div class="form-group">
              <label for="">Note</label>
              <input type="text" placeholder="Enter Note" name="note" id="note" class="form-control">
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
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Project</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_sp')}}">
            @csrf
          <input type="" name="id_project_edit" id="id_project_edit" hidden>
          <div class="form-group">
            <label for="">No. PO Customer</label>
            <input type="text" name="po_customer_edit" id="po_customer_edit" class="form-control" @if(Auth::User()->id_position == 'STAFF') readonly @endif>
          </div>

          <div class="form-group">
            <label for="">Project Name</label>
            <input type="text" name="name_project_edit" id="name_project_edit" class="form-control" @if(Auth::User()->id_position == 'STAFF') readonly @endif>
          </div>

          @if(Auth::User()->id_position == 'MANAGER')
          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" placeholder="Enter Amount" name="amount_edit" id="amount_edit" required>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          @endif

          <div class="form-group">
            <label for="">Note</label>
            <input type="text" placeholder="Enter Note" name="note_edit" id="note_edit" class="form-control">
          </div>

          <div class="form-group">
            <label for="">Invoice info</label><br>
            <label class="radios">Sudah Bayar
              <input type="radio" name="invoice" id="invoice_edit_f" value="F">
              <span class="checkmark"></span>
            </label>
            <label class="radios">Setengah Bayar
              <input type="radio" name="invoice" id="invoice_edit_h" value="H">
              <span class="checkmark"></span>
            </label>
            <label class="radios">Belum Bayar
              <input type="radio" name="invoice" id="invoice_edit_n" value="N">
              <span class="checkmark"></span>
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
              <h3>Are you sure?</h3><br><h3>Delete</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success-raise" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">
     $('#sip-data').DataTable({
        "scrollX": true,
        "retrieve": true,
        "order": [[ 1, "desc" ]],
        fixedColumns:   {
          leftColumns: 2
        },
        });

     $('#sip-prj').DataTable({
        "scrollX": true,
        "retrieve": true,
        "order": [[ 1, "desc" ]],
        fixedColumns:   {
          leftColumns: 2
        },
        });

     $('#msp-data').DataTable({
      "order": [[ 1, "desc" ]],
      "paging": false,
        "retrieve": true,
        });

     $('#msp-prj').DataTable({
        "retrieve": true,
        "order": [[ 1, "desc" ]],
        paging: false,
      fixedColumns:   {
          leftColumns: 2
        },
        });
    /* $(document).ready(function() {
        $('#customer_name').select2();
      });*/
        @if(Auth::User()->id_position == 'STAFF')
        function Edit_sp(id_project,no_po_customer,name_project,note,invoice)
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
        function Edit_sp(id_project,no_po_customer,name_project,amount_idr,note,invoice)
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

      $('#customer_name').select2();
      $('#contact_msp').select2();

      $('.money').mask('000,000,000,000,000', {reverse: true});

      $("#alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert").slideUp(300);
      });

      $(".dismisbar").click(function(){
         $(".notification-bar").slideUp(300);
      }); 
  </script>
@endsection