@extends('template.template')
@section('content')
<style type="text/css">
.transparant{
  background-color: Transparent;
  background-repeat:no-repeat;
  border: none;
  cursor:pointer;
  overflow: hidden;
  outline:none;
  width: 25px;
}
.alert-box {
    color:#555;
    border-radius:10px;
    font-family:Tahoma,Geneva,Arial,sans-serif;font-size:14px;
    padding:10px 36px;
    margin:10px;
}
.alert-box span {
    font-weight:bold;
    text-transform:uppercase;
}
.error {
    background:#ffecec;
    border:1px solid #f5aca6;
}
.success {
    background:#e9ffd9 ;
    border:1px solid #a6ca8a;
}
.warning {
    background:#fff8c4 ;
    border:1px solid #f2c779;
}
.notice {
    background:#e3f7fc;
    border:1px solid #8ed9f6;
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
div div ol li a{font-size: 14px;}
div div i{font-size: 14px;}
background-color:dodgerBlue;}
.inputWithIconn.inputIconBg i{
  background-color:#aaa;
  color:#fff;
  padding:7px 4px;
  border-radius:4px 0 0 4px;
}
.inputWithIconn{
  position:relative;
}
.inputWithIconn i{
  position:absolute;
  left:0;
  top:28px;
  padding:9px 8px;
  color:#aaa;
  transition:.3s;
}
.inputWithIconn input[type=text]{
  padding-left:40px;
}
label.status-lose:hover{
  border-radius: 10%;
  background-color: grey;
  text-align: center;
  width: 75px;
  height: 30px;
  color: white;
  padding-top: 3px;
  cursor: zoom-in;
}
table.center{
  text-align: center;
}
.stats_item_number {
  white-space: nowrap;
  font-size: 2.25rem;
  line-height: 2.5rem;
  
  &:before {
    display: none;
  }
}

.txt_success {
  color: #2EAB6F;
}

.txt_warn {
  color: #f2562b;
}

.txt_sd {
  color: #04dda3;
}

.txt_tp{
  color: #f7e127;
}

.txt_win{
  color: #246d18;
}

.txt_lose{
  color: #e5140d;
}

.txt_smaller {
  font-size: .75em;
}

.flipY {
  transform: scaleY(-1);
  border-bottom-color: #fff;
}

.txt_faded {
  opacity: .65;
}

.txt_primary{
  color: #007bff;
}
</style>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Lead Register</a>
        </li>
      </ol>

        @if(Auth::User()->id_division == "SALES" && Auth::User()->id_position != 'ADMIN' || Auth::User()->id_division == "TECHNICAL PRESALES" || Auth::User()->id_position == "DIRECTOR" || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'OPERATION DIRECTOR' || Auth::User()->id_position == "MANAGER" && Auth::User()->id_division == "MSM")
        <div class="row margin-bottom">
        <div class="col-xs-2 col-sm-2 mb-2">
      <div class="card bg-default o-hidden h-100">
        <div class="card-body">
           <div class="txt_faded">
           <label class="txt_label space_n_b">
             Lead Register
           </label>
           <div id="lead_2018" style="display: none;" class="txt_serif stats_item_number txt_primary">{{$total_leads}}<span class="txt_smaller">&nbsplead</span>
           </div>
           <div id="lead_2019" class="txt_serif stats_item_number txt_primary">{{$total_lead}}<span class="txt_smaller">&nbsplead</span>
           </div>
         </div>
        </div>
      </div>
    </div>

      <div class="col-xs-2 col-sm-2 mb-2">
        <div class="card bg-default o-hidden h-100">
          <div class="card-body">
             <div class="txt_faded">
             <label class="txt_label space_n_b">
               Open
             </label>
             <div id="open_2018" style="display: none;" class="txt_serif stats_item_number txt_warn">{{$total_opens}}<span class="txt_smaller">&nbsplead</span>
             </div>
             <div id="open_2019" class="txt_serif stats_item_number txt_warn">{{$total_open}}<span class="txt_smaller">&nbsplead</span>
             </div>
           </div>
          </div>
        </div>
      </div>  

        <div class="col-xs-2 col-sm-2 mb-2">
          <div class="card bg-default o-hidden h-100">
            <div class="card-body">
               <div class="txt_faded">
               <label class="txt_label space_n_b">
                 Solution Design
               </label>
               <div id="sd_2018" style="display: none;" class="txt_serif stats_item_number txt_sd">{{$total_sds}}<span class="txt_smaller">&nbsplead</span>
               </div>
               <div id="sd_2019" class="txt_serif stats_item_number txt_sd">{{$total_sd}}<span class="txt_smaller">&nbsplead</span>
               </div>
             </div>
            </div>
          </div>
        </div>  

      <div class="col-xs-2 col-sm-2 mb-2">
        <div class="card bg-default o-hidden h-100">
          <div class="card-body">
             <div class="txt_faded">
             <label class="txt_label space_n_b">
               Tender Process
             </label>
             <div id="tp_2018" style="display: none;" class="txt_serif stats_item_number txt_tp">{{$total_tps}}<span class="txt_smaller">&nbsplead</span>
             </div>
             <div id="tp_2019" class="txt_serif stats_item_number txt_tp">{{$total_tp}}<span class="txt_smaller">&nbsplead</span>
             </div>
           </div>
          </div>
        </div>
      </div>

      <div class="col-xs-2 col-sm-2 mb-2">
        <div class="card bg-default o-hidden h-100">
          <div class="card-body">
             <div class="txt_faded">
             <label class="txt_label space_n_b">
               Win
             </label>
             <div id="win_2018" style="display: none;" class="txt_serif stats_item_number txt_win">{{$total_wins}}<span class="txt_smaller">&nbsplead</span>
             </div>
             <div id="win_2019" class="txt_serif stats_item_number txt_win">{{$total_win}}<span class="txt_smaller">&nbsplead</span>
             </div>
           </div>
          </div>
        </div>
      </div>

      <div class="col-xs-2 col-sm-2 mb-2 float-right">
        <div class="card bg-default o-hidden h-100">
          <div class="card-body">
             <div class="txt_faded">
             <label class="txt_label space_n_b">
               Lose
             </label>
             <div id="lose_2018" style="display: none;" class="txt_serif stats_item_number txt_lose">{{$total_loses}}<span class="txt_smaller">&nbsplead</span>
             </div>
             <div id="lose_2019" class="txt_serif stats_item_number txt_lose">{{$total_lose}}<span class="txt_smaller">&nbsplead</span>
             </div>
           </div>
          </div>
        </div>
      </div>
      </div>
      @endif
    
      @if (Auth::User()->id_division != 'TECHNICAL PRESALES' && Auth::User()->id_position != 'STAFF' && session('success'))
    <div class="alert-box notice" id="alert"><span>notice: </span> {{ session('success') }}.</div>
      @elseif (Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'STAFF' && session('success'))
    <div class="alert-box warning notification-bar"><span>warning: </span> {{ session('success') }}.<button   type="button" class="dismisbar transparant pull-right"><i class="fa fa-times"></i></button></div>

      @endif
      
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Lead Table</b>
           @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' || Auth::User()->id_division == 'TECHNICAL PRESALES' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'OPERATION DIRECTOR')
           <div class="dropdown btn btn-md pull-right">
              <button class="dropbtn"><i class="fa fa-filter"></i>&nbspFilter Year</button>
              <div class="dropdown-content">
                <div class="year">
                  <span class="fa fa-calendar"></span>
                  <input type="button" name="answer" value="2018" onclick="show2018()" class="transparant-filter" />
                </div>
                <div class="year">
                  <span class="fa fa-calendar"></span>
                  <input type="button" name="answer" value="2019" onclick="show2019()" class="transparant-filter" />
                </div>
              </div>
              @if(Auth::User()->id_position != 'OPERATION DIRECTOR')
              <button class="btn btn-primary-lead pull-right margin-left" id="btn_add_sales"><i class="fa fa-plus"> </i>&nbsp Lead Register</button>
              @endif
           </div>
           
           @elseif(Auth::User()->id_division == 'PMO')
           <!-- <div class="pull-right">
            <a href="{{action('ReportController@downloadPdfwin')}}" class="btn btn-warning float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport PDF</a>
            <a href="{{action('PMOController@exportExcel')}}" class="btn btn-warning float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport XLS</a>
           </div> -->
           <div class="pull-right">
              <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <b><i class="fa fa-download"></i> Export</b>
              </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item" href="{{action('ReportController@downloadPdfwin')}}"> PDF </a>
              <a class="dropdown-item" href="{{action('PMOController@exportExcel')}}"> EXCEL </a>
            </div>
            </div>
           @endif
        </div>
        <div class="card-body">
          <div id="div_2018" style="display: none">
            <div class="table-responsive">
            <table class="table table-bordered dataTable" id="datas2018" width="100%" cellspacing="0">
              <thead>
                <tr>
                  @if(Auth::User()->id_division == 'PMO')
                  <th>Project ID</th>
                  @else
                  <th>Lead ID</th>
                  @endif
                  <th>Customer</th>
                  <th>Opty Name</th>
                  <th>Create Date</th>
                  <th>Closing Date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                @foreach($lead as $data)
                <tr>
                  <td>
                    @if(Auth::User()->id_division == 'PMO')
                      @if($data->result != 'OPEN')
                        @if($data->status_sho == 'PMO')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{$data->id_project}}</a>
                        @else
                          {{$data->id_project}}
                        @endif
                      @endif
                    @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
                      @if($data->result != 'OPEN')
                        @if($data->status_engineer == 'v')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                        @else
                          {{ $data->lead_id }}
                        @endif
                      @endif
                    @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                      @if($data->result != 'OPEN')
                        @if($data->status_engineer == 'v')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                        @else
                          {{ $data->lead_id }}
                        @endif
                      @endif
                    @else
                      @if(Auth::User()->id_division == 'PMO')
                        @if(Auth::User()->id_division == 'PMO' && $data->status_sho != 'PMO')
                        {{ $data->lead_id }}
                        @elseif(Auth::User()->id_division == 'PMO' && $data->status_handover != 'handover')
                        {{ $data->lead_id }}
                        @elseif($data->result != 'OPEN')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                        @else
                        {{ $data->lead_id }}
                        @endif
                      @else
                        @if(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_sho != 'PMO')
                        {{ $data->lead_id }}
                        @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_handover != 'handover')
                        {{ $data->lead_id }}
                        @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_sho != 'PMO')
                        {{ $data->lead_id }}
                        @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_handover != 'handover')
                        {{ $data->lead_id }}
                        @elseif($data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                            @if($data->status_sho == 'PMO' || $data->status_sho == '' || $data->status_handover == 'handover')
                              <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                            @else
                              {{ $data->lead_id }}
                            @endif
                          @else
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @endif
                        @else
                        {{ $data->lead_id }}
                        @endif
                      @endif
                    @endif
                  </td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name}}</td>
                  <td>{!!substr($data->created_at,0,10)!!}</td>
                  <td>{{ $data->closing_date}}</td>
                  <td>{{ $data->name }}
                  @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'STAFF' )
                    {{$data->pmo_nik}}</td>
                  @endif
                  @if($data->amount == '')
                  <td><i></i><i class="money"></i></td>
                  @elseif($data->amount != '')
                  <td><i></i><i class="money">{{$data->amount}}</i></td>
                  @endif
                  <td>
                    @if($data->result == 'OPEN')
                      <i style="opacity: 0.01">A</i><label class="status-initial">INITIAL</label>
                    @elseif($data->result == '')
                      <i style="opacity: 0.01">B</i><label class="status-open">OPEN</label>
                    @elseif($data->result == 'SD')
                      <i style="opacity: 0.01">C</i><label class="status-sd">SD</label>
                    @elseif($data->result == 'TP')
                      <i style="opacity: 0.01">D</i><label class="status-tp">TP</label>
                    @elseif($data->result == 'WIN')
                      <i style="opacity: 0.01">E</i><label class="status-win">WIN</label>
                    @elseif($data->result == 'LOSE')
                      <i style="opacity: 0.01">G</i><label class="status-lose" data-toggle="modal" data-target="#modal-reason" onclick="lose('{{$data->keterangan}}')">LOSE</label>
                    @elseif($data->result == 'CANCEL')
                      <i style="opacity: 0.01">H</i><label class="status-lose" style="background-color: #071108">CANCEL</label>
                    @elseif($data->result == 'HOLD')
                      <i style="opacity: 0.01">F</i><label class="status-initial" style="background-color: #919e92">HOLD</label>
                    @endif
                  </td>
                  <td>
                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && $data->result == 'WIN' && $data->status_handover != 'handover')
                      <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
                      @if($data->result != 'OPEN')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                        <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                      @endif
                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
                      @if($data->result != 'OPEN')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                        <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                      @endif
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result != 'OPEN')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'WIN')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'LOSE')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'TP')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}','{{$data->name}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                        @endif
                      @else
                        <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                      @endif
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result != 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_sho != 'SHO' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                            @if($data->status_sho != 'PMO')
                              <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                              @if($data->status_handover != 'handover')
                                <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                              @endif
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover == 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover == 'handover')
                            @if($data->status_sho != 'PMO')
                                <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                              @elseif($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                              @else
                                <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                    @if($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                    <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                  @else
                                    <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                                    @endif
                              @endif
                            @endif
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover != 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover != 'handover')
                            <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                          @endif
                        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'LOSE' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'TP' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                        @endif
                      @else
                        <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                      @endif
                    @elseif(Auth::User()->id_position == 'DIRECTOR')
                      @if(Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                        @if(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                        @endif
                      @else
                        <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                      @endif
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                      <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                    @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->result == 'WIN')
                      @if($data->status_sho == 'PMO' && $data->status_engineer == NULL)
                        <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                      @else
                        @if($data->status_engineer == 'v' && Auth::User()->id_position == 'ENGINEER MANAGER')
                        <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                        @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER MANAGER')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER STAFF')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho disabled">Detail</button></a>
                        @endif
                      @endif
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' && $data->result == 'WIN' && $data->status_handover == 'handover' && $data->status_sho != 'PMO')
                      <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                    @elseif(Auth::User()->id_division != 'PMO')
                      @if(Auth::User()->id_position == 'OPERATION DIRECTOR')
                        <button class="btn btn-sm sho disabled">No Action</button>
                      @else
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @endif
                    @else
                      @if($data->status_sho == 'PMO' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO')
                       
                      <button onclick="reassignPMO('{{$data->lead_id}}','@foreach($contributes as $cons) @if($data->lead_id == $cons->lead_id){{$cons->pmo_nik}}@endif @endforeach','@foreach($users as $pmo_owner) {{$pmo_owner->nik}} @endforeach')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO" >Re-Assign</button></a>
                      @elseif($data->status_sho == 'PMO' && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'PMO')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                      <button class="btn btn-sm sho disabled">Detail</button>
                      @endif
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                @if(Auth::User()->id_territory != NULL)
                  @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_territory == 'DPG')
                    <th colspan="5" style="text-align: right;">Total Amount:</th>
                    <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                    <th colspan="2"></th>
                  @else
                    <th colspan="5" style="text-align: right;">Total Amount:</th>
                    <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                    <th colspan="2"></th>
                  @endif
                @elseif(Auth::User()->id_position == 'DIRECTOR')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="3"></th>
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="3"></th>
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_division == 'PMO')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @else
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="total">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @endif
              </tfoot>
            </table>
            </div>
          </div>
          <div id="div_2019">
            <div class="table-responsive">
            <table class="table table-bordered nowrap dataTable" id="datas2019" width="100%" cellspacing="0">
              <thead>
                <tr>
                  @if(Auth::User()->id_division == 'PMO')
                  <th>Project ID</th>
                  @else
                  <th>Lead ID</th>
                  @endif
                  <th>Customer</th>
                  <th>Opty Name</th>
                  <th>Create Date</th>
                  <th>Closing Date</th>
                  <th>Owner</th>
                  @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '2')
                  <th>Presales</th>
                  @endif
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                  @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_company == '1' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_company == '2' && Auth::User()->id_division != 'TECHNICAL')
                    @if($cek_initial > 0)
                    <th>Action</th>
                    @endif
                  @endif 
                  @if($cek_note > 0)                 
                  <th>Note</th>
                  @else
                  @endif
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
        @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER')        
                @foreach($leads as $datas => $data)
                  <tr>
                    <td>
                      @if(Auth::User()->id_division == 'PMO')
                        @if($data->result != 'OPEN')
                          @if($data->status_sho == 'PMO')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{$data->id_project}}</a>
                          @else
                            {{$data->id_project}}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @else
                        @if(Auth::User()->id_division == 'PMO')
                          @if(Auth::User()->id_division == 'PMO' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_division == 'PMO' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @else
                          @if(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              @if($data->status_sho == 'PMO' || $data->status_sho == '' || $data->status_handover == 'handover')
                                <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                              @else
                                {{ $data->lead_id }}
                              @endif
                            @else
                              <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                            @endif
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @endif
                      @endif
                    </td>
                    <td>{{ $data->brand_name}}</td>
                    <td>{{ $data->opp_name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>{{ $data->closing_date}}</td>
                    <td>{{ $data->name }}
                    @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'STAFF' )
                      {{$data->pmo_nik}}</td>
                    @endif
                    @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
                    <td>
                      @if($data->nik == $st->nik)
                        Satria Teguh Sentosa Mulyono
                      @elseif($data->nik == $rk->nik)
                        Muhammad Rizki Kurniawan
                      @elseif($data->nik == $gp->nik)
                        Ganjar Pramudya Wijaya
                      @endif
                    </td>
                    @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '2')
                    <td></td>
                    @endif
                    @if($data->amount == '')
                    <td><i></i><i class="money"></i></td>
                    @elseif($data->amount != '')
                    <td><i></i><i class="money">{{$data->amount}}</i></td>
                    @endif
                    <td>
                      @if($data->result == 'OPEN')
                        <i style="opacity: 0.01">A</i><label class="status-initial">INITIAL</label>
                      @elseif($data->result == '')
                        <i style="opacity: 0.01">B</i><label class="status-open">OPEN</label>
                      @elseif($data->result == 'SD')
                        <i style="opacity: 0.01">C</i><label class="status-sd">SD</label>
                      @elseif($data->result == 'TP')
                        <i style="opacity: 0.01">D</i><label class="status-tp">TP</label>
                      @elseif($data->result == 'WIN')
                        <i style="opacity: 0.01">E</i><label class="status-win">WIN</label>
                      @elseif($data->result == 'LOSE')
                        <i style="opacity: 0.01">G</i><label class="status-lose" data-toggle="modal" data-target="#modal-reason" onclick="lose('{{$data->keterangan}}')">LOSE</label>
                      @elseif($data->result == 'CANCEL')
                        <i style="opacity: 0.01">H</i><label class="status-lose" style="background-color: #071108">CANCEL</label>
                      @elseif($data->result == 'HOLD')
                        <i style="opacity: 0.01">F</i><label class="status-initial" style="background-color: #919e92">HOLD</label>
                      @endif
                    </td>
                    <td>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && $data->result == 'WIN' && $data->status_handover != 'handover')
                      <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}','{{$data->name}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result != 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_sho != 'SHO' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                              @if($data->status_sho != 'PMO')
                                <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                                @if($data->status_handover != 'handover')
                                  <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                                @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover == 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover == 'handover')
                              @if($data->status_sho != 'PMO')
                                  <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                                @elseif($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                  <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                @else
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                      @if($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                      <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                    @else
                                      <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                                      @endif
                                @endif
                              @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover != 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover != 'handover')
                              <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                            @endif
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'LOSE' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'TP' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                        <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->result == 'WIN')
                        @if($data->status_sho == 'PMO' && $data->status_engineer == NULL)
                          <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                        @else
                          @if($data->status_engineer == 'v' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER STAFF')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho disabled">Detail</button></a>
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' && $data->result == 'WIN' && $data->status_handover == 'handover' && $data->status_sho != 'PMO')
                        <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                      @elseif(Auth::User()->id_division != 'PMO')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                        @if($data->status_sho == 'PMO' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO')
                         
                        <button onclick="reassignPMO('{{$data->lead_id}}','@foreach($contributes as $cons) @if($data->lead_id == $cons->lead_id){{$cons->pmo_nik}}@endif @endforeach','@foreach($users as $pmo_owner) {{$pmo_owner->nik}} @endforeach')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO" >Re-Assign</button></a>
                        @elseif($data->status_sho == 'PMO' && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'PMO')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button class="btn btn-sm sho disabled">Detail</button>
                        @endif
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_company == '1' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_company == '2' && Auth::User()->id_division != 'TECHNICAL')
                      @if($cek_initial > 0)
                      <td>
                        @if($data->result == 'OPEN')
                        <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_lead_register" data-toggle="modal" onclick="lead_id('{{$data->lead_id}}','{{$data->id_customer}}','{{$data->opp_name}}','{{$data->amount}}','{{$data->created_at}}','{{$data->closing_date}}','{{$data->keterangan}}')" style="width: 30px;height:30px;text-align: center;"></button>
                        @endif
                        @if(Auth::User()->name == $data->name && $data->result == 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'OPEN' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && $data->result == 'OPEN')
                        <a href="{{ url('delete_sales', $data->lead_id) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 30px;height:30px;text-align: center;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">
                        </button></a>
                        @endif
                      </td>
                      @else
                      @endif
                    @endif
                    @if($cek_note > 0)
                    <td>{{$data->keterangan}}</td>
                    @else
                    @endif
                  </tr>
                @endforeach

                @foreach($leadspre as $datas => $data)
                  <tr>
                    <td>
                      @if(Auth::User()->id_division == 'PMO')
                        @if($data->result != 'OPEN')
                          @if($data->status_sho == 'PMO')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{$data->id_project}}</a>
                          @else
                            {{$data->id_project}}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @else
                        @if(Auth::User()->id_division == 'PMO')
                          @if(Auth::User()->id_division == 'PMO' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_division == 'PMO' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @else
                          @if(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              @if($data->status_sho == 'PMO' || $data->status_sho == '' || $data->status_handover == 'handover')
                                <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                              @else
                                {{ $data->lead_id }}
                              @endif
                            @else
                              <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                            @endif
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @endif
                      @endif
                    </td>
                    <td>{{ $data->brand_name}}</td>
                    <td>{{ $data->opp_name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>{{ $data->closing_date}}</td>
                    <td>{{ $data->name }}
                    @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'STAFF' )
                      {{$data->pmo_nik}}</td>
                    @endif
                    @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
                    <td>
                      @if($data->nik == $st->nik)
                        Satria Teguh Sentosa Mulyono
                      @elseif($data->nik == $rk->nik)
                        Muhammad Rizki Kurniawan
                      @elseif($data->nik == $gp->nik)
                        Ganjar Pramudya Wijaya
                      @endif
                    </td>
                    @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '2')
                    <td></td>
                    @endif
                    @if($data->amount == '')
                    <td><i></i><i class="money"></i></td>
                    @elseif($data->amount != '')
                    <td><i></i><i class="money">{{$data->amount}}</i></td>
                    @endif
                    <td>
                      @if($data->result == 'OPEN')
                        <i style="opacity: 0.01">A</i><label class="status-initial">INITIAL</label>
                      @elseif($data->result == '')
                        <i style="opacity: 0.01">B</i><label class="status-open">OPEN</label>
                      @elseif($data->result == 'SD')
                        <i style="opacity: 0.01">C</i><label class="status-sd">SD</label>
                      @elseif($data->result == 'TP')
                        <i style="opacity: 0.01">D</i><label class="status-tp">TP</label>
                      @elseif($data->result == 'WIN')
                        <i style="opacity: 0.01">E</i><label class="status-win">WIN</label>
                      @elseif($data->result == 'LOSE')
                        <i style="opacity: 0.01">G</i><label class="status-lose" data-toggle="modal" data-target="#modal-reason" onclick="lose('{{$data->keterangan}}')">LOSE</label>
                      @elseif($data->result == 'CANCEL')
                        <i style="opacity: 0.01">H</i><label class="status-lose" style="background-color: #071108">CANCEL</label>
                      @elseif($data->result == 'HOLD')
                        <i style="opacity: 0.01">F</i><label class="status-initial" style="background-color: #919e92">HOLD</label>
                      @endif
                    </td>
                    <td>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && $data->result == 'WIN' && $data->status_handover != 'handover')
                      <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}','{{$data->name}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result != 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_sho != 'SHO' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                              @if($data->status_sho != 'PMO')
                                <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                                @if($data->status_handover != 'handover')
                                  <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                                @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover == 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover == 'handover')
                              @if($data->status_sho != 'PMO')
                                  <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                                @elseif($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                  <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                @else
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                      @if($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                      <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                    @else
                                      <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                                      @endif
                                @endif
                              @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover != 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover != 'handover')
                              <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                            @endif
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'LOSE' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'TP' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                        <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->result == 'WIN')
                        @if($data->status_sho == 'PMO' && $data->status_engineer == NULL)
                          <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                        @else
                          @if($data->status_engineer == 'v' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER STAFF')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho disabled">Detail</button></a>
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' && $data->result == 'WIN' && $data->status_handover == 'handover' && $data->status_sho != 'PMO')
                        <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                      @elseif(Auth::User()->id_division != 'PMO')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                        @if($data->status_sho == 'PMO' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO')
                         
                        <button onclick="reassignPMO('{{$data->lead_id}}','@foreach($contributes as $cons) @if($data->lead_id == $cons->lead_id){{$cons->pmo_nik}}@endif @endforeach','@foreach($users as $pmo_owner) {{$pmo_owner->nik}} @endforeach')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO" >Re-Assign</button></a>
                        @elseif($data->status_sho == 'PMO' && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'PMO')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button class="btn btn-sm sho disabled">Detail</button>
                        @endif
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_company == '1' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_company == '2' && Auth::User()->id_division != 'TECHNICAL')
                      @if($cek_initial > 0)
                      <td>
                        @if($data->result == 'OPEN')
                        <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_lead_register" data-toggle="modal" onclick="lead_id('{{$data->lead_id}}','{{$data->id_customer}}','{{$data->opp_name}}','{{$data->amount}}','{{$data->created_at}}','{{$data->closing_date}}','{{$data->keterangan}}')" style="width: 30px;height:30px;text-align: center;"></button>
                        @endif
                        @if(Auth::User()->name == $data->name && $data->result == 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'OPEN' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && $data->result == 'OPEN')
                        <a href="{{ url('delete_sales', $data->lead_id) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 30px;height:30px;text-align: center;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">
                        </button></a>
                        @endif
                      </td>
                      @else
                      @endif
                    @endif
                    @if($cek_note > 0)
                    <td>{{$data->keterangan}}</td>
                    @else
                    @endif
                  </tr>
                @endforeach
        @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_territory == 'DPG')
      
          @foreach($leads as $datas => $data)
                <tr class="tab-pane fade show active" id="sip" role="tabpanel" aria-labelledby="sip-tab">
                    <td>
                      @if(Auth::User()->id_division == 'PMO')
                        @if($data->result != 'OPEN')
                          @if($data->status_sho == 'PMO')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{$data->id_project}}</a>
                          @else
                            {{$data->id_project}}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                        @if($data->result != 'OPEN')
                          @if($data->status_engineer == 'v')
                            <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                            {{ $data->lead_id }}
                          @endif
                        @endif
                      @else
                        @if(Auth::User()->id_division == 'PMO')
                          @if(Auth::User()->id_division == 'PMO' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_division == 'PMO' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @else
                          @if(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_sho != 'PMO')
                          {{ $data->lead_id }}
                          @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_handover != 'handover')
                          {{ $data->lead_id }}
                          @elseif($data->result != 'OPEN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              @if($data->status_sho == 'PMO' || $data->status_sho == '' || $data->status_handover == 'handover')
                                <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                              @else
                                {{ $data->lead_id }}
                              @endif
                            @else
                              <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                            @endif
                          @else
                          {{ $data->lead_id }}
                          @endif
                        @endif
                      @endif
                    </td>
                    <td>{{ $data->brand_name}}</td>
                    <td>{{ $data->opp_name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>{{ $data->closing_date}}</td>
                    <td>{{ $data->name }}
                    @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'STAFF' )
                      {{$data->pmo_nik}}</td>
                    @endif
                    @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
                    <td>
                      @if($data->nik == $st->nik)
                        Satria Teguh Sentosa Mulyono
                      @elseif($data->nik == $rk->nik)
                        Muhammad Rizki Kurniawan
                      @elseif($data->nik == $gp->nik)
                        Ganjar Pramudya Wijaya
                      @endif
                    </td>
                    @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '2')
                    <td></td>
                    @endif
                    @if($data->amount == '')
                    <td><i></i><i class="money"></i></td>
                    @elseif($data->amount != '')
                    <td><i></i><i class="money">{{$data->amount}}</i></td>
                    @endif
                    <td>
                      @if($data->result == 'OPEN')
                        <i style="opacity: 0.01">A</i><label class="status-initial">INITIAL</label>
                      @elseif($data->result == '')
                        <i style="opacity: 0.01">B</i><label class="status-open">OPEN</label>
                      @elseif($data->result == 'SD')
                        <i style="opacity: 0.01">C</i><label class="status-sd">SD</label>
                      @elseif($data->result == 'TP')
                        <i style="opacity: 0.01">D</i><label class="status-tp">TP</label>
                      @elseif($data->result == 'WIN')
                        <i style="opacity: 0.01">E</i><label class="status-win">WIN</label>
                      @elseif($data->result == 'LOSE')
                        <i style="opacity: 0.01">G</i><label class="status-lose" data-toggle="modal" data-target="#modal-reason" onclick="lose('{{$data->keterangan}}')">LOSE</label>
                      @elseif($data->result == 'CANCEL')
                        <i style="opacity: 0.01">H</i><label class="status-lose" style="background-color: #071108">CANCEL</label>
                      @elseif($data->result == 'HOLD')
                        <i style="opacity: 0.01">F</i><label class="status-initial" style="background-color: #919e92">HOLD</label>
                      @endif
                    </td>
                    <td>
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && $data->result == 'WIN' && $data->status_handover != 'handover')
                      <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
                        @if($data->result != 'OPEN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                          <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}','{{$data->name}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result != 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                            @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_sho != 'SHO' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                              @if($data->status_sho != 'PMO')
                                <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                                @if($data->status_handover != 'handover')
                                  <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                                @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover == 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover == 'handover')
                              @if($data->status_sho != 'PMO')
                                  <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                                @elseif($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                  <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                @else
                                  <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                      @if($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                      <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                                    @else
                                      <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                                      @endif
                                @endif
                              @endif
                            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover != 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover != 'handover')
                              <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                            @endif
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'LOSE' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'TP' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'DIRECTOR')
                        @if(Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                          @if(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                          @endif
                        @else
                          <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                        <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                      @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->result == 'WIN')
                        @if($data->status_sho == 'PMO' && $data->status_engineer == NULL)
                          <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                        @else
                          @if($data->status_engineer == 'v' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER MANAGER')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER STAFF')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                          @else
                          <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho disabled">Detail</button></a>
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' && $data->result == 'WIN' && $data->status_handover == 'handover' && $data->status_sho != 'PMO')
                        <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                      @elseif(Auth::User()->id_division != 'PMO')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                      @else
                        @if($data->status_sho == 'PMO' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO')
                         
                        <button onclick="reassignPMO('{{$data->lead_id}}','@foreach($contributes as $cons) @if($data->lead_id == $cons->lead_id){{$cons->pmo_nik}}@endif @endforeach','@foreach($users as $pmo_owner) {{$pmo_owner->nik}} @endforeach')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO" >Re-Assign</button></a>
                        @elseif($data->status_sho == 'PMO' && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'PMO')
                        <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                        @else
                        <button class="btn btn-sm sho disabled">Detail</button>
                        @endif
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_company == '1' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_company == '2' && Auth::User()->id_division != 'TECHNICAL')
                      @if($cek_initial > 0)
                      <td>
                        @if($data->result == 'OPEN')
                        <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_lead_register" data-toggle="modal" onclick="lead_id('{{$data->lead_id}}','{{$data->id_customer}}','{{$data->opp_name}}','{{$data->amount}}','{{$data->created_at}}','{{$data->closing_date}}','{{$data->keterangan}}')" style="width: 30px;height:30px;text-align: center;"></button>
                        @endif
                        @if(Auth::User()->name == $data->name && $data->result == 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'OPEN' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && $data->result == 'OPEN')
                        <a href="{{ url('delete_sales', $data->lead_id) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 30px;height:30px;text-align: center;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">
                        </button></a>
                        @endif
                      </td>
                      @else
                      @endif
                    @endif
                    @if($cek_note > 0)
                    <td>{{$data->keterangan}}</td>
                    @else
                    @endif
                </tr>
                @endforeach 
          @else 
            @foreach($leads as $datas => $data)
            <tr>
              <td>
                @if(Auth::User()->id_division == 'PMO')
                  @if($data->result != 'OPEN')
                    @if($data->status_sho == 'PMO')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}">{{$data->id_project}}</a>
                    @else
                      {{$data->id_project}}
                    @endif
                  @endif
                @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
                  @if($data->result != 'OPEN')
                    @if($data->status_engineer == 'v')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                    @else
                      {{ $data->lead_id }}
                    @endif
                  @endif
                @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                  @if($data->result != 'OPEN')
                    @if($data->status_engineer == 'v')
                      <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                    @else
                      {{ $data->lead_id }}
                    @endif
                  @endif
                @else
                  @if(Auth::User()->id_division == 'PMO')
                    @if(Auth::User()->id_division == 'PMO' && $data->status_sho != 'PMO')
                    {{ $data->lead_id }}
                    @elseif(Auth::User()->id_division == 'PMO' && $data->status_handover != 'handover')
                    {{ $data->lead_id }}
                    @elseif($data->result != 'OPEN')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                    @else
                    {{ $data->lead_id }}
                    @endif
                  @else
                    @if(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_sho != 'PMO')
                    {{ $data->lead_id }}
                    @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->status_handover != 'handover')
                    {{ $data->lead_id }}
                    @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_sho != 'PMO')
                    {{ $data->lead_id }}
                    @elseif(Auth::User()->id_position == 'ENGINEER STAFF' && $data->status_handover != 'handover')
                    {{ $data->lead_id }}
                    @elseif($data->result != 'OPEN')
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                        @if($data->status_sho == 'PMO' || $data->status_sho == '' || $data->status_handover == 'handover')
                          <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                        @else
                          {{ $data->lead_id }}
                        @endif
                      @else
                        <a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a>
                      @endif
                    @else
                    {{ $data->lead_id }}
                    @endif
                  @endif
                @endif
              </td>
              <td>{{ $data->brand_name}}</td>
              <td>{{ $data->opp_name}}</td>
              <td>{!!substr($data->created_at,0,10)!!}</td>
              <td>{{ $data->closing_date}}</td>
              <td>{{ $data->name }}
              @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'STAFF' )
                {{$data->pmo_nik}}</td>
              @endif
              @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
              <td>
                @if($data->nik == $st->nik)
                  Satria Teguh Sentosa Mulyono
                @elseif($data->nik == $rk->nik)
                  Muhammad Rizki Kurniawan
                @elseif($data->nik == $gp->nik)
                  Ganjar Pramudya Wijaya
                @endif
              </td>
              @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '2')
              <td></td>
              @endif
              @if($data->amount == '')
              <td><i></i><i class="money"></i></td>
              @elseif($data->amount != '')
              <td><i></i><i class="money">{{$data->amount}}</i></td>
              @endif
              <td>
                @if($data->result == 'OPEN')
                  <i style="opacity: 0.01">A</i><label class="status-initial">INITIAL</label>
                @elseif($data->result == '')
                  <i style="opacity: 0.01">B</i><label class="status-open">OPEN</label>
                @elseif($data->result == 'SD')
                  <i style="opacity: 0.01">C</i><label class="status-sd">SD</label>
                @elseif($data->result == 'TP')
                  <i style="opacity: 0.01">D</i><label class="status-tp">TP</label>
                @elseif($data->result == 'WIN')
                  <i style="opacity: 0.01">E</i><label class="status-win">WIN</label>
                @elseif($data->result == 'LOSE')
                  <i style="opacity: 0.01">G</i><label class="status-lose" data-toggle="modal" data-target="#modal-reason" onclick="lose('{{$data->keterangan}}')">LOSE</label>
                @elseif($data->result == 'CANCEL')
                  <i style="opacity: 0.01">H</i><label class="status-lose" style="background-color: #071108">CANCEL</label>
                @elseif($data->result == 'HOLD')
                  <i style="opacity: 0.01">F</i><label class="status-initial" style="background-color: #919e92">HOLD</label>
                @endif
              </td>
              <td>
                @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && $data->result == 'WIN' && $data->status_handover != 'handover')
                <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
                  @if($data->result != 'OPEN')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                  @else
                    <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                  @endif
                @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
                  @if($data->result != 'OPEN')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                  @else
                    <a href="#"><button class="btn btn-sm sho" disabled>Detail</button></a>
                  @endif
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                  @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result != 'OPEN')
                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'WIN')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'LOSE')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && $data->result == 'TP')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @else
                    <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}','{{$data->name}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                    @endif
                  @else
                    <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                  @endif
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result != 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_sho != 'SHO' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                        @if($data->status_sho != 'PMO')
                          <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                          @if($data->status_handover != 'handover')
                            <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                          @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover == 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover == 'handover')
                        @if($data->status_sho != 'PMO')
                            <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                          @elseif($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                            <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                            <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                          @else
                            <button onclick="reassignPMO('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO">Re-Assign</button></a>
                                @if($data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || $data->status_sho == 'PMO' && $data->status_engineer == '' && Auth::User()->id_position == 'DIRECTOR')
                                <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                              @else
                                <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                                @endif
                          @endif
                        @endif
                      @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'WIN' && $data->status_handover != 'handover' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN' && $data->status_handover != 'handover')
                        <button data-target="#modal_sho" data-toggle="modal" class="btn btn-sm sho" onclick="sho('{{$data->lead_id}}')">Handover</button>
                      @endif
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'LOSE' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->result == 'TP' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @else
                    <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                    @endif
                  @else
                    <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}', '{{$data->nik}}', '{{$data->created_at}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                  @endif
                @elseif(Auth::User()->id_position == 'DIRECTOR')
                  @if(Auth::User()->id_position == 'DIRECTOR' && $data->result != 'OPEN')
                    @if(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'WIN')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'LOSE')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif(Auth::User()->id_position == 'DIRECTOR' && $data->result == 'TP')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @else
                    <button type="button" class="btn btn-sm sho" onclick="reassign('{{$data->lead_id}}')" data-toggle="modal" data-target="#reassignModal" >Re-Assign</button>
                    @endif
                  @else
                    <button type="button" class="btn btn-sm sho" onclick="assign('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModal">Assign</button>
                  @endif
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' && $data->result == 'WIN' && $data->status_sho != 'SHO')
                  <button data-target="#salesproject" data-toggle="modal" class="btn btn-sm sho" onclick="id_pro('{{$data->lead_id}}','{{$data->nik}}','{{$data->opp_name}}')">ID Project</button>
                @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' && $data->result == 'WIN')
                  @if($data->status_sho == 'PMO' && $data->status_engineer == NULL)
                    <button type="button" class="btn btn-sm sho" onclick="assignEngineer('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignEngineer">Assign</button>
                  @else
                    @if($data->status_engineer == 'v' && Auth::User()->id_position == 'ENGINEER MANAGER')
                    <button onclick="reassignEngineer('{{$data->lead_id}}')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignEngineer">Re-Assign</button></a>
                    @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER MANAGER')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @elseif($data->status_handover != 'handover' && Auth::User()->id_position == 'ENGINEER STAFF')
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                    @else
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho disabled">Detail</button></a>
                    @endif
                  @endif
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' && $data->result == 'WIN' && $data->status_handover == 'handover' && $data->status_sho != 'PMO')
                  <button type="button" class="btn btn-sm sho" onclick="assignPMO('{{$data->lead_id}}')" data-toggle="modal" data-target="#assignModalPMO">Assign</button>
                @elseif(Auth::User()->id_division != 'PMO')
                  @if(Auth::User()->id_position == 'OPERATION DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'MSM')
                    <button class="btn btn-sm sho disabled">No Action</button>
                  @else
                    <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                  @endif
                @else
                  @if($data->status_sho == 'PMO' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO')
                   
                  <button onclick="reassignPMO('{{$data->lead_id}}','@foreach($contributes as $cons) @if($data->lead_id == $cons->lead_id){{$cons->pmo_nik}}@endif @endforeach','@foreach($users as $pmo_owner) {{$pmo_owner->nik}} @endforeach')" class="btn btn-sm sho" data-toggle="modal" data-target="#reassignModalPMO" >Re-Assign</button></a>
                  @elseif($data->status_sho == 'PMO' && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'PMO')
                  <a href="{{ url ('/detail_project', $data->lead_id) }}"><button class="btn btn-sm sho">Detail</button></a>
                  @else
                  <button class="btn btn-sm sho disabled">Detail</button>
                  @endif
                @endif
              </td>
              @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_company == '1' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_company == '2' && Auth::User()->id_division != 'TECHNICAL')
                @if($cek_initial > 0)
                <td>
                  @if($data->result == 'OPEN')
                  <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_lead_register" data-toggle="modal" onclick="lead_id('{{$data->lead_id}}','{{$data->id_customer}}','{{$data->opp_name}}','{{$data->amount}}','{{$data->created_at}}','{{$data->closing_date}}','{{$data->keterangan}}')" style="width: 30px;height:30px;text-align: center;"></button>
                  @endif
                  @if(Auth::User()->name == $data->name && $data->result == 'OPEN' || Auth::User()->id_position == 'DIRECTOR' && $data->result == 'OPEN' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && $data->result == 'OPEN')
                  <a href="{{ url('delete_sales', $data->lead_id) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 30px;height:30px;text-align: center;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">
                  </button></a>
                  @endif
                </td>
                @else
                @endif
              @endif
              @if($cek_note > 0)
              <td>{{$data->keterangan}}</td>
              @else
              @endif
            </tr>
            @endforeach
          @endif
          </tbody>
          <tfoot>
            @if(Auth::User()->id_territory != NULL)
              @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_territory == 'DPG')
                <th colspan="5" style="text-align: right;">Total Amount:</th>
                <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
                <th colspan="2"></th>
              @else
                <th colspan="5" style="text-align: right;">Total Amount:</th>
                <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
                <th colspan="2"></th>
              @endif
            @elseif(Auth::User()->id_position == 'DIRECTOR')
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="3"></th>
            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="3"></th>
            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="2"></th>
            @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="2"></th>
            @elseif(Auth::User()->id_division == 'PMO')
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="2"></th>
            @else
              <th colspan="5" style="text-align: right;">Total Amount:</th>
              <th><i>Rp</i><i  class="total">{{$total_ters}},00</i></p></th>
              <th colspan="2"></th>
            @endif
          </tfoot>
        </table>
        </div>
      </div>
    </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>

      @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
      <div class="row">
          <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                  <table <table class="table table-bordered center" width="100%" cellspacing="0">
                      <thead style="background-color: #343a40!important">
                        <tr style="color: white">
                          <th>Object Privelege</th>
                          <th>Sales</th>
                          <th>Presales Manager</th>
                          <th>Presales</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th>Lead Register</th>
                          <td>CR</td>
                          <td>R</td>
                          <td>R</td>
                        </tr>
                        <tr>
                          <th>Solution Design</th>
                          <td>R</td>
                          <td>R</td>
                          <td>CRU</td>
                        </tr>
                        <tr>
                          <th>Tender Process</th>
                          <td>CRU</td>
                          <td>R</td>
                          <td>R</td>
                        </tr>
                        <tr>
                          <th>Result Win/Lose</th>
                          <td>RU</td>
                          <td>R</td>
                          <td>R</td>
                        </tr>
                        <tr>
                          <th>Sales Handover</th>
                          <td>CR</td>
                          <td>R</td>
                          <td>R</td>
                        </tr>
                        <tr>
                          <th>Assign Presales</th>
                          <td>-</td>
                          <td>CRU</td>
                          <td>-</td>
                        </tr>
                      </tbody>
                      <tfoot></tfoot>
                  </table>
                  <div>
                    <h6><b>Note :</b></h6>
                    <h6>C : CREATE</h6>
                    <h6>R : READ</h6>
                    <h6>U : UPDATE</h6>
                    <h6>D : DELETE</h6>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                  <table class="table table-bordered" width="100%" cellspacing="0" >
                      <thead style="background-color: #343a40!important">
                        <tr style="color: white">
                          <th>Status</th>
                          <th>Deskripsi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th>Initial</th>
                          <td>Lead Register pertama kali dibuat</td>
                        </tr>
                        <tr>
                          <th>
                            Open
                          </th>
                          <td>Presales Manager telah mengalokasikan presales</td>
                        </tr>
                        <tr>
                          <th>SD</th>
                          <td>(Solution Design) Presales yang di assign mengerjakan solution design</td>
                        </tr>
                        <tr>
                          <th>TP</th>
                          <td>(Tender Process) Proses Solution Design oleh Presales selesai dilakukan dan Sales mengisi form Tender Process</td>
                        </tr>
                        <tr>
                          <th>Win</th>
                          <td>Tender berhasil dimenangkan</td>
                        </tr>
                        <tr>
                          <th>Lose</th>
                          <td>Tender gagal</td>
                        </tr>
                        <tr>
                          <th>Hold</th>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Cancel</th>
                          <td>Tender tidak dilanjutkan</td>
                        </tr>
                      </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>
            </div>
          </div>
      </div>
      @elseif(Auth::User()->id_division != 'SALES' || Auth::User()->id_division != 'TECHNICAL PRESALES')
      <div class="card mb-3">
            <div class="card-body">
              <table class="table table-bordered" width="100%" cellspacing="0" >
                  <thead style="background-color: #343a40!important">
                    <tr style="color: white">
                      <th>Status</th>
                      <th>Deskripsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>Initial</th>
                      <td>Lead Register pertama kali dibuat</td>
                    </tr>
                    <tr>
                      <th>
                        Open
                      </th>
                      <td>Presales Manager telah mengalokasikan presales</td>
                    </tr>
                    <tr>
                      <th>SD</th>
                      <td>(Solution Design) Presales yang di assign mengerjakan solution design</td>
                    </tr>
                    <tr>
                      <th>TP</th>
                      <td>(Tender Process) Proses Solution Design oleh Presales selesai dilakukan dan Sales mengisi form Tender Process</td>
                    </tr>
                    <tr>
                      <th>Win</th>
                      <td>Tender berhasil dimenangkan</td>
                    </tr>
                    <tr>
                      <th>Lose</th>
                      <td>Tender gagal</td>
                    </tr>
                  </tbody>
                  <tfoot></tfoot>
              </table>
            </div>
      </div>
      @endif

  </div>
  
</div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_lead" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Lead Register</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store')}}" id="modalSalesLead" name="modalSalesLead">
            @csrf
          <!-- <div class="form-group">
            <label for="lead_id">Lead Id</label>
            <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder="Lead Id" readonly required>
          </div> -->
          
            @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1')
            <div class="form-group">
            <label for="">Owner</label>
            <select class="form-control" id="owner_sales" onkeyup="copytextbox();" name="owner_sales" required>
              <option value="">-- Select Sales --</option>
              @foreach($owner as $data)
                @if($data->id_division == 'SALES' && $data->id_company == '1' && $data->id_position != 'ADMIN')
                  <option value="{{$data->nik}}">{{$data->name}}</option>
                @endif
              @endforeach
            </select>
            </div>
            @elseif(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '2')
            <div class="form-group">
            <label for="">Owner</label>
            <select class="form-control" id="owner_sales" onkeyup="copytextbox();" name="owner_sales" required>
              <option value="">-- Select Sales --</option>
              @foreach($owner as $data)
                @if($data->id_division == 'SALES' && $data->id_company != '1')
                  <option value="{{$data->nik}}">{{$data->name}}</option>
                @endif
              @endforeach
            </select>
            </div>
            @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
            <div class="form-group">
            <label for="">Owner</label>
            <select class="form-control" id="owner_sales" onkeyup="copytextbox();" name="owner_sales" required>
              <option value="">-- Select Sales --</option>
              @foreach($owner as $data)
                @if($data->id_division == 'SALES' && $data->id_company == '1' && $data->id_position != 'ADMIN')
                  <option value="{{$data->nik}}">{{$data->name}}</option>
                @endif
              @endforeach
            </select>
            </div>
            @elseif(Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '1' )
            <div class="form-group">
            <label for="">Owner</label>
            <select class="form-control" id="owner_sales" onkeyup="copytextbox();" name="owner_sales" required>
              <option value="">-- Select Sales --</option>
              @foreach($owner as $data)
                @if($data->id_division == 'SALES'  && $data->id_position != 'ADMIN' || $data->id_position == 'DIRECTOR' || $data->id_division == 'TECHNICAL' && $data->id_position == 'MANAGER' && $data->id_territory == '' && $data->name != 'TECH HEAD')
                  <option value="{{$data->nik}}">{{$data->name}}</option>
                @endif
              @endforeach
            </select>
            </div>
            @elseif(Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '2')
            <div class="form-group">
            <label for="">Owner</label>
            <select class="form-control" id="owner_sales" onkeyup="copytextbox();" name="owner_sales" required>
              <option value="">-- Select Sales --</option>
              @foreach($owner as $data)
                @if($data->id_division == 'SALES' && $data->id_company != '1')
                  <option value="{{$data->nik}}">{{$data->name}}</option>
                @endif
              @endforeach
            </select>
            </div>
            @endif

          <div class="form-group">
            <label for="">Customer (Brand Name)</label>
             <select class="form-control" style="width: 100%;" id="contact" onkeyup="copytextbox();" name="contact" required>
              <option value="">-- Select Contact --</option>
              @foreach($code as $data)
                <option value="{{$data->id_customer}}">{{$data->brand_name}} </option>
                @endforeach
            </select>
          </div>

         <div class="form-group">
          <label for="">Opportunity Name</label>
          <input type="text" class="form-control" placeholder="Enter Opportunity Name" name="opp_name" id="opp_name" required>
         </div>

          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount">
            <i class="" aria-hidden="true">Rp.</i>
          </div>

          <div class="form-group">
            <label for="">Closing Date</label>
            <input type="date" class="form-control" name="closing_date" id="closing_date" required>
          </div>

          <div class="form-group">
          <label for="">Note (jika perlu)</label>
          <input type="text" class="form-control" placeholder="Enter Note" name="note" id="note">
         </div>

          <!-- <div class="form-group modalIcon inputIconBg">
            <label for="">Kurs To Dollar</label>
            <input type="text" class="form-control" disabled="disabled" placeholder="Kurs">
            <i class="" aria-hidden="true">&nbsp$&nbsp </i>
          </div> -->       
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" id="add_lead" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>


<!-- MODAL EDIT PROJECT-->
<div class="modal fade" id="edit_lead_register" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Lead Register</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_lead_register')}}" id="modal_edit_saleslead" name="modal_edit_saleslead">
            @csrf

          <input type="" name="lead_id_edit" id="lead_id_edit" hidden>

          <!-- <div class="form-group">
            <label for="">Customer</label>
             <select class="form-control" style="width: 100%;" id="contact_edit" onkeyup="copytextbox();" name="contact_edit" required>
              <option value="">-- Select Contact --</option>
              @foreach($code as $data)
                <option value="{{$data->id_customer}}">{{$data->brand_name}}</option>
                @endforeach
            </select>
          </div> -->

         <div class="form-group">
          <label for="">Opportunity Name</label>
          <textarea type="text" class="form-control" placeholder="Enter Opportunity Name" name="opp_name_edit" id="opp_name_edit">
          </textarea>
         </div>

         <!-- <div class="form-group  modalIcon inputIconBg">
            <label for="">Create Date</label>
            <input type="date" name="create_date_edit" id="create_date_edit" class="form-control">
          </div> -->

          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" placeholder="Enter Amount" name="amount_edit" id="amount_edit">
            <i class="" aria-hidden="true">Rp.</i>
          </div>

          <div class="form-group  modalIcon inputIconBg">
            <label for="">Closing Date</label>
            <input type="date" name="closing_date_edit" id="closing_date_edit" class="form-control">
          </div>

          <div class="form-group">
          <label for="">Note (jika perlu)</label>
          <input type="text" class="form-control" placeholder="Enter Note" name="note_edit" id="note_edit">
         </div>

          <!-- <div class="form-group modalIcon inputIconBg">
            <label for="">Kurs To Dollar</label>
            <input type="text" class="form-control" disabled="disabled" placeholder="Kurs">
            <i class="" aria-hidden="true">&nbsp$&nbsp </i>
          </div> -->     
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!-- Presales Assignment -->
  <div class="modal fade" id="assignModal" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Presales Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('assign_to_presales')}}" id="modalAssign" name="modalAssign">
            @csrf
          <div class="form-group row">
            <input type="text" name="cek_nik" id="cek_nik" value="" hidden>
            <input type="text" name="coba_lead" id="coba_lead" value=""  hidden>
            <input type="text" name="cek_created_at" id="cek_created_at" value=""  hidden>
            <label for="">Choose Presales</label><br>
            <select class="form-control-small margin-left-custom" style="width: 100%" id="owner" name="owner" required>
              <option>-- Choose --</option>
              @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '1' || Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '1')
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '1' || $data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '2')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
              @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '2' || Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '2')
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '2')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

<!-- PMO Assignment -->
  <div class="modal fade" id="assignModalPMO" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">PMO Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('assign_to_pmo')}}" id="modalAssignPMO" name="modalAssignPMO">
            @csrf
          <div class="form-group row">
            <input type="text" name="coba_lead_pmo" id="coba_lead_pmo" value="" hidden>
            <label for="">Choose PMO</label><br>
            <select class="form-control-small margin-left-custom" id="pmo_nik" name="pmo_nik" required>
              <option value="">-- Choose --</option>
                @foreach($owner as $data)
                  @if($data->id_division == 'PMO')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div> 

  <div class="modal fade" id="assignEngineer" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Engineer Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('engineer_assign')}}" id="modalAssignPMO" name="modalAssignPMO">
            @csrf
          <div class="form-group row">
            <input type="text" name="engineer_lead" id="engineer_lead" value="" hidden>
            <label for="">Choose Engineer</label><br>
            <select class="form-control-small margin-left-custom" id="engineer_nik" name="engineer_nik" required>
              <option>-- Choose Engineer--</option>
                @foreach($owner as $data)
                  @if($data->id_position == 'ENGINEER MANAGER')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
                @foreach($owner as $data)
                  @if($data->id_position == 'ENGINEER STAFF')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach

            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div> 

  <!-- PMO reAssignment -->
  <div class="modal fade" id="reassignModalPMO" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">PMO Re-Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('reassign_to_pmo')}}" id="modalAssignPMO" name="modalAssignPMO">
            @csrf
          <div class="form-group row">
            <div>
            </div>
            <input type="text" name="pmo_reassign" id="pmo_reassign" value="" hidden>

            <input type="text" name="pmo_nik_update" id="pmo_nik_update" value="" hidden>

            <input type="text" name="owner_pmo" id="owner_pmo" hidden>
             

            <label for="">Choose PMO</label><br>
            <select class="form-control-small margin-left-custom" id="upadte_pmo_nik" name="upadte_pmo_nik" required>
              <option value="">-- Choose PMO --</option>
              @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR')
                @foreach($users as $data)
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div> 

  <div class="modal fade" id="reassignEngineer" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Engineer Re-Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('reassign_to_engineer')}}" id="modalAssignPMO" name="modalAssignPMO">
            @csrf
          <div class="form-group row">
            <input type="text" name="engineer_lead_reassign" id="engineer_lead_reassign" hidden>
            <label for="">Choose Engineer</label><br>
            <select class="form-control-small margin-left-custom" id="nik_engineer" name="nik_engineer" required>
              <option>-- Choose --</option>
                @foreach($owner as $data)
                  @if($data->id_position == 'ENGINEER MANAGER')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
                @foreach($owner as $data)
                  @if($data->id_position == 'ENGINEER STAFF')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>  

  <!-- Re-Presales Assignment -->
  <div class="modal fade" id="reassignModal" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Presales Re-Assignment</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('reassign_to_presales')}}" id="modalreAssign" name="modalreAssign">
            @csrf
          <div class="form-group row">
            <input type="text" name="coba_lead_reassign" id="coba_lead_reassign" value="" hidden>
          <!--   <label for="">Current Presales</label><br>
            <input type="text" class="form-control margin-bottom" name="current_presales_update" id="current_presales_update" value="" readonly> -->
            <label for="">Choose Presales</label><br>
            <select class="form-control-small margin-left-custom" style="width: 100%" id="owner_reassign" name="owner_reassign" required>
              <option>-- Choose Owner --</option>
              @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '1' || Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '1')
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '1' || $data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '2')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
              @elseif(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '2' || Auth::User()->id_position == 'DIRECTOR' && Auth::User()->id_company == '2')
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL PRESALES' && $data->id_company == '2' || $data->id_division == 'TECHNICAL' && $data->id_company == '2')
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
              <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Handover -->
  <div class="modal fade" id="modal_sho" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Sales Handover</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_sho')}}" id="modalCustomer" name="modalCustomer">
            @csrf
          
          <input type="" name="lead_to_sho" id="lead_to_sho" hidden>
          <!-- <div class="form-group">
            <label for="pid">PID</label>
            <input type="text" class="form-control" id="pid" name="pid" placeholder="PID" readonly>
          </div> -->
          <div class="form-group">
            <label for="name_contact">Scope Of Work</label>
            <textarea class="form-control" id="sow" name="sow"></textarea>
          </div>

          <div class="form-group">
            <label for="timeline">Timeline</label>
            <input type="text" class="form-control" id="timeline" name="timeline" placeholder="Enter Timeline">
          </div>

          <div class="form-group">
            <label for="top">Term of Payment</label>
            <textarea type="text" class="form-control" id="top" name="top" placeholder="" required> </textarea>
          </div>

          <div class="form-group inputWithIconn inputIconBg">
            <label for="budget">Budget</label>
            <input class="form-control-medium money" type="text" placeholder="Enter Project Budget" name="pro_budget"  id="pro_budget" value="" />
                <i class="" aria-hidden="true">Rp.</i>
          </div>

          <div class="form-group">
            <label for="meeting">Meeting Date</label>
            <input type="date" class="form-control" id="meeting" name="meeting">
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspAdd</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

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
              <input type="text" id="customer_name" name="customer_name" class="form-control" readonly>
            </div>

            <div class="form-group">
              <label for="">Project Name</label>
              <input type="text" name="name_project" id="name_project" class="form-control" readonly>
            </div>

            <div class="form-group" hidden>
              <label for="">Sales</label>
              <input type="text" name="sales" id="sales" class="form-control" readonly>
               <!-- <select class="form-control" id="sales" name="sales" required o>
                    <option value="{{$data->nik}}">{{$data->name}}</option>
              </select> -->
            </div>

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div>

            <!-- <div class="form-group modalIcon inputIconBg">
              <label for="">Kurs To Dollar</label>
              <input type="text" class="form-control" readonly placeholder="Kurs" name="kurs" id="kurs">
              <i class="" aria-hidden="true">&nbsp$&nbsp </i>
            </div>   -->     
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary-custom"><i class="fa fa-check">&nbsp</i>Submit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal-reason" role="dialog">
    <div class="modal-dialog modal-sd">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reason of Lose</h4>
        </div>
          <div class="modal-body">
            <div class="form-group">
              <textarea class="form-control" readonly id="keterangan_lose" name="keterangan_lose"></textarea>
            </div>
          </div>
        <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
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
              <div class="">Lead Register Anda Akan di Proses. . .</div>
            </div>
          </div>
        </div>
      </div>
  </div>

</div>


@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="">
    $('#add_lead').click(function(){
      $('#tunggu').modal('show')
      $('#modal_lead').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 1000);
    });

    function assign(lead_id,nik,created_at){
      $('#coba_lead').val(lead_id);
      $('#cek_nik').val(nik);
      $('#cek_created_at').val(created_at);
    }
    function sho(lead_id,id_project){
      $('#lead_to_sho').val(lead_id);  
      $('#id_project_sho').val(id_project);     
    }
    function reassignEngineer(lead_id){
       $('#engineer_lead_reassign').val(lead_id);  
    }
    function assignEngineer(lead_id){
       $('#engineer_lead').val(lead_id);  
    }
    function id_pro(lead_id,nik,opp_name){
      $('#customer_name').val(lead_id);
      $('#sales').val(nik);
      $('#name_project').val(opp_name);
    }
    function assignPMO(lead_id){
      $('#coba_lead_pmo').val(lead_id);
    }
    function reassignPMO(lead_id,pmo_nik,nik){
      $('#pmo_reassign').val(lead_id);
      $('#pmo_nik_update').val(pmo_nik);
      $('#owner_pmo').val(nik);

      var b = document.getElementById("pmo_nik_update").value;
      var c = document.getElementById("owner_pmo").value;

    }
    function reassign(lead_id,nik,name){
      $('#coba_lead_reassign').val(lead_id);
      $('#current_presales_update').val(nik);
      $('#owner_reassign').val(users.name);
    }

    function lead_id(lead_id,id_customer,opp_name,amount,created_at,closing_date,keterangan){
      $('#lead_id_edit').val(lead_id);
      $('#contact_edit').val(id_customer);
      $('#opp_name_edit').val(opp_name);
      $('#amount_edit').val(amount);
      $('#create_date_edit').val(created_at.substring(0,10));
      $('#closing_date_edit').val(closing_date);
      $('#note_edit').val(keterangan);
    }

    function lose(keterangan){
      $('#keterangan_lose').val(keterangan);
    }

      $('.money').mask('000,000,000,000,000', {reverse: true});
      $('.total').mask('000,000,000,000,000,000.00', {reverse: true});
      $(document).ready(function() {
          $('#contact').select2();
          $('#contact_edit').select2();
          $('#owner').select2();
          $('#owner_reassign').select2();
      });

        $('#datatabels').DataTable( {
      });

      $('#datatabelc').DataTable( {
      });

       $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
          });

       $(".dismisbar").click(function(){
         $(".notification-bar").slideUp(300);
        }); 

      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1') {
        $('#datas2019').DataTable( {
           "scrollX": true,
           "responsive":true,
           "order": [[ 8, "asc" ]],
           fixedColumns:   {
              leftColumns: 2
            },
        });

      }@elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '2') {
        $('#datas2019').DataTable( {
           "scrollX": true,
           "responsive":true,
           "order": [[ 8, "asc" ]],
           fixedColumns:   {
              leftColumns: 2
            },
        });

      }@else{
        $('#datas2019').DataTable( {
           "scrollX": true,
           "responsive":true,
           "order": [[ 7, "asc" ]],
           fixedColumns:   {
              leftColumns: 2
            },
        })

      }
      @endif

      function show2018() {
       document.getElementById('div_2018').style.display = "inherit";
       document.getElementById('div_2019').style.display = "none";
       document.getElementById('lead_2018').style.display = "inherit";
       document.getElementById('lead_2019').style.display = "none";
       document.getElementById('open_2018').style.display = "inherit";
       document.getElementById('open_2019').style.display = "none";
       document.getElementById('sd_2018').style.display = "inherit";
       document.getElementById('sd_2019').style.display = "none";
       document.getElementById('tp_2018').style.display = "inherit";
       document.getElementById('tp_2019').style.display = "none";
       document.getElementById('win_2018').style.display = "inherit";
       document.getElementById('win_2019').style.display = "none";
       document.getElementById('lose_2018').style.display = "inherit";
       document.getElementById('lose_2019').style.display = "none";
       $('#datas2018').DataTable( {
         "scrollX": true,
         "retrieve": true,
         "order": [[ 7, "asc" ]]

        })
      }

      function show2019() {
       document.getElementById('div_2018').style.display = "none";
       document.getElementById('div_2019').style.display = "inherit";
       document.getElementById('lead_2018').style.display = "none";
       document.getElementById('lead_2019').style.display = "inherit";
       document.getElementById('open_2018').style.display = "none";
       document.getElementById('open_2019').style.display = "inherit";
       document.getElementById('sd_2018').style.display = "none";
       document.getElementById('sd_2019').style.display = "inherit";
       document.getElementById('tp_2018').style.display = "none";
       document.getElementById('tp_2019').style.display = "inherit";
       document.getElementById('win_2018').style.display = "none";
       document.getElementById('win_2019').style.display = "inherit";
       document.getElementById('lose_2018').style.display = "none";
       document.getElementById('lose_2019').style.display = "inherit";
      } 
  </script>
@endsection