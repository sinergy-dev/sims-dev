@extends('template.main')
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
  
  <style type="text/css">
    .hari_libur {
      color: red !important;
    }
    .swal-wide{
        width:850px !important;
    }
    
    #tooltip{
      display: none;
      position: absolute;
      cursor: pointer;
      left: 100px;
      top: 35px;
      border: solid 1px #eee;
      background-color: #ffffdd;
      padding: 20px;
      z-index: 1000;
    }

    time.icon
    {
      font-size: 1em;
      display: block;
      position: relative;
      width: 10em;
      height: 10em;
      background-color: #fff;
      border-radius: 0.6em;
      box-shadow: 0 1px 0 #bdbdbd, 0 2px 0 #fff, 0 3px 0 #bdbdbd, 0 4px 0 #fff, 0 5px 0 #bdbdbd, 0 0 0 1px #bdbdbd;
      overflow: hidden;
    }

    time.icon *
    {
      display: block;
      width: 100%;
      font-size: 1em;
      font-weight: bold;
      font-style: normal;
      text-align: center;
    }

    time.icon strong
    {
      position: absolute;
      top: 0;
      padding: 0.4em 0;
      color: #fff;
      background-color: #f7024c;
      border-bottom: 1px dashed #ffffff;
      box-shadow: 0 2px 0 #ffffff;
    }

    time.icon em
    {
      position: absolute;
      bottom: 0.2em;
      color: #f7024c;
    }

    time.icon span
    {
      font-size: 3em;
      letter-spacing: -0.05em;
      padding-top: 0.8em;
      color: #2f2f2f;
    }

    .dataTables_paging {
      display: none;
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
      Leaving Permit {{ $year}}
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Human Resource</li>
      <li class="active">Leaving Permit</li>
    </ol>
  </section>

  <section class="content">
  	@if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
     @endif

    {{-- <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Hari Libur Nasional Tahun {{$year}}</h3>
        <div class="box-tools pull-right">
          <i class="fa fa-fw fa-lg fa-angle-left field-icon toggle-arrow"></i>
        </div>
      </div>
        <div class="box-body div-libur" style="display: none;"></div>
    </div> --}}

    <div class="box">
      <div class="box-header">
        <div class="pull-right">
          @if($cek_cuti->status_karyawan == 'cuti')
            @if($total_cuti > 0)
            	@if($cek->status == null)
                <button type="button" class="btn btn-sm bg-navy pull-right add_cuti" value="{{Auth::User()->nik}}" style="margin-left: 10px;width: 100px">
                  <i class="fa fa-plus" style="margin-right: 5px"> </i> Permission
                </button>
                <button class="btn btn-sm bg-maroon show-sisa-cuti" style="width: 100px">
                  Show Sisa Cuti
                </button>
	            @elseif($cek_cuti->status == 'v' || $cek_cuti->status == 'd')
    	          <button type="button" class="btn btn-sm bg-navy pull-right add_cuti" value="{{Auth::User()->nik}}" style="margin-left: 10px;width: 100px">
                  <i class="fa fa-plus" style="margin-right: 5px"> </i> Permission
                </button>
  	            <button class="btn btn-sm bg-maroon show-sisa-cuti" style="width: 100px">
                  Show Sisa Cuti
                </button>
	            @else
                <button type="button" class="btn btn-sm bg-navy pull-right disabled disabled-permission" style="margin-left: 10px;width: 100px">
                  <i class="fa fa-plus" style="margin-right: 5px"> </i> Permission
                </button>
            	@endif
            @else
              <button type="button" class="btn btn-sm bg-navy pull-right disabled disabled-permission" style="margin-left: 10px;width: 100px">
                <i class="fa fa-plus" style="margin-right: 5px"> </i> Permission
              </button>
            @endif
          @endif
            
          <!-- <a href="{{action('HRGAController@cutipdf')}}" target="_blank" onclick="print()">
            <button class="btn btn-sm btn-danger disabled" style="width: 120px">
              <i class="fa fa-file-pdf-o" style="margin-right: 5px"></i>Preview PDF
            </button>
          </a>  -->
          <button class="btn btn-sm bg-purple" id="btnSetCuti" style="margin-left: 10px; display: none;" data-toggle="modal" data-target="#setting_cuti">
            <i class="fa fa-wrench" style="margin-right: 5px"></i>Total Cuti
          </button>
          <select class="btn btn-sm bg-blue pull-left" style="width: 70px; margin-right: 10px; display: none;" id="filter_com">
            <option value="all">All</option>
            <option value="1">SIP</option>
            <option value="2">MSP</option>
          </select>
        </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">          
          <div class="nav-tabs-custom">

            <ul class="nav nav-tabs" style="margin-top: 10px" id="cutis">
              <li class="tabs_item">
                <a href="#bos" style="display: none;" id="tab-list-cuti" data-toggle="tab" onclick="changeTabs('all_lis')">List Cuti Karyawan</a>
              </li>
              <li class="tabs_item">
                <a href="#cuti" id="cuti_tab" data-toggle="tab" onclick="changeTabs('request')">Request Cuti {{$bulan}}</a>
              </li>
              <li class="tabs_item">
                {{-- @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
                  <a href="#staff" data-toggle="tab" onclick="changeTabs('report_')">Report Cuti</a>
                @else
                  <a href="#staff" data-toggle="tab" onclick="changeTabs('history')">History Cuti</a>
                @endif --}}
                <a href="#staff" data-toggle="tab" onclick="changeTabs('history')">History Cuti</a>
              </li>
            </ul>

            <div class="tab-content">

              <div class="tab-pane" id="bos"> 
                <table class="table table-bordered table-striped dataTable" id="datatables" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th rowspan="2"><center>Employees Name</center></th>
                      <th rowspan="2"><center>Email</center></th>
                      <th rowspan="2"><center>Division</center></th>
                      <th rowspan="2"><center>Tanggal Masuk Kerja</center></th>
                      <th rowspan="2"><center>Lama Bekerja</center></th>
                      <th rowspan="2"><center>Cuti sudah diambil</center></th>
                      <th colspan="2"><center>Sisa Cuti</center></th>
                    </tr>
                      <tr>
                        {{-- <th>{{$tahun_lalu}}<small>(*s/d 31 maret {{$tahun_ini}})</small></th> --}}
                        <th>{{$tahun_lalu}}</th>
                        <th>{{$tahun_ini}}</th>
                      </tr>
                  </thead>
                  <tbody id="all_cuti" name="all_cuti">
                  </tbody>
                </table>
              </div>

              <div class="tab-pane" id="cuti">
                <table class="table table-bordered table-striped dataTable" id="datatablew" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Employees Name</th>
                      <th>Division</th>
                      <th id="col-cuti-request">Cuti Request</th>
                      <th>Request Date</th>
                      <!-- <th>Date of Request</th>
                      <th>Time Off</th> -->
                      <th>Status</th>
                      <th>action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane" id="staff">
                <div class="row" style="margin-bottom: 10px; display: none;" id="div_filter">
                  <div class="input-group date" style="width: 300px;margin-left: 15px;float: right;">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" id="datesReport" name="dates">
                  </div>

                  <div  class="input-group date disabled" style="width: 300px;margin-left: 15px;float: right;">
                    <div class="input-group-addon">
                      <i class="fa fa-filter"></i>
                    </div>
                    <select class="form-control" id="division_cuti" name="division_cuti">
                      <option value="alldeh">ALL DIVISION</option>
                      @foreach($division as $data)
                        @if($data->id_division != 'NULL')
                          @if($data->id_division == '-')
                            <option value="{{$data->id_division}}">WAREHOUSE</option>
                          @else
                            <option value="{{$data->id_division}}">{{$data->id_division}}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>

                  <button class="btn btn-sm bg-olive" style="float: left;margin-left: 15px" onclick="exportExcel()">&nbspExport to <i class="fa fa-file-excel-o"></i></button>
                </div>
                <table class="table table-bordered table-striped dataTable" id="datatableq" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Employees Name</th>
                      <th>Division</th>
                      <th>Request Date</th>
                      <th>Date Off</th>
                      <th>Approved Date</th>
                      <th>Approved By</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="report" name="report">
                  </tbody>
                </table>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>


    <!--MODAL ADD-->  
    <div class="modal fade" id="modalCuti" role="dialog">
      <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Leaving Permit</h4>
          </div>
          <div class="modal-body">
              @csrf
              <form id="form-submit-cuti">
              <div class="form-group hidden">
                <label>Sisa Cuti : </label>
                <span name="sisa_cuti" id="sisa_cuti"></span><!-- 
                <input type="text" name="sisa_cuti" id="sisa_cuti" style="width: 50px;color: black;text-decoration: bold" class="form-control sisa_cuti" value="" readonly=""> -->
              </div>
              <div class="row">
                <div class="col-md-9">
                  <div class="form-group">
                      <label>Date</label>
                      <input type="text" class="form-control" id="date_start" name="date_start" autocomplete="Off" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label>Available Days</label>
                      <input type="text" class="form-control" id="avaliableDays" readonly="true">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Jenis Cuti</label><br>
                  <input type="radio" name="jenis_cuti" value="tahunan" required=""> Tahunan<br>
                  <input type="radio" name="jenis_cuti" value="melahirkan"> Melahirkan<br>
                  <input type="radio" name="jenis_cuti" value="other"> Other<br> 
              </div>

              <div id="tooltip">
              Kamu melewati batas sisa cuti.
              </div>

              <div class="form-group">
                  <label>Note</label>
                  <textarea class="form-control" type="text" id="reason" name="reason"></textarea>
              </div>

              <input type="" name="lihat_hasil" id="lihat_hasil" class="lihat_hasil" hidden>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
               <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
                <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
                <button type="button" class="btn btn-primary btn-submit" disabled data-placement="top" id="btn-submit"><i class="fa fa-check"> </i>&nbspSubmit</button>
              </div>
              </form>
          </div>
        </div>
      </div>
    </div>

    @foreach($cuti as $data)
    @if(Auth::User()->nik == $data->nik)
        <!--MODAL EDIT-->  
    <div class="modal fade" id="modalCuti_edit" role="dialog">
        <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Leaving Permit</h4>
            </div>
            <div class="modal-body">
                @csrf
                <input type="" name="id_cuti" id="id_cuti" value="" hidden>

                <div class="form-group">
                    <label>Reason For Leave</label>
                    <textarea class="form-control" type="text" id="reason_edit" name="reason_edit" required></textarea>
                </div>  

                <div class="form-group">
                    <label>Date Off Before</label>

                    <div class="input-group date form-group" id="datepicker">
                      <input type="text" class="form-control" id="Dates_update" name="Dates" autocomplete="off" placeholder="Select days" readonly/>
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>
                    </div> 
                </div> 

                <div class="form-group">
                    <label>Date Off After</label>
                    <div class="input-group date form-group" id="datepicker">
                      <input type="text" class="form-control" id="Dates" name="Dates" id="date-tooltip" data-toggle="tooltip" title="Jumlah hari yang kamu masukkan melebihi Date Off Before!" autocomplete="off" placeholder="Select days" required />
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>
                    </div> 
                </div>
                 
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                  <button type="submit" class="btn btn-primary btn-sm btn-submit-update" id="btn-submit-update"><i class="fa fa-check"> </i>&nbspSubmit</button>
                </div>
            </div>
          </div>
        </div>
    </div>
    @endif
    @endforeach


    <!--Modal Detail-->
    <div class="modal fade" id="detail_cuti" role="dialog">
        <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content modal-md">
            <div class="modal-header">
              <h4 class="modal-title">Leaving Permit</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('approve_cuti')}}">
                @csrf
                <input type="" name="id_cuti_detil" id="id_cuti_detil" hidden="">
                <input type="" name="nik_cuti" id="nik_cuti" hidden="">
                <div class="form-group">
                    <label>Date Of Request</label>
                    <input type="text" class="form-control" id="date_request_detil" name="date_request_detil" readonly>
                </div>

                <div class="form-group">
                    <label>List Request Date Off</label>
                    <table class="table table-bordered" id="detil_cuy" style="margin-top: 10px">
                      <tbody id="tanggal_cuti" class="tanggal_cuti">
                        
                      </tbody>
                    </table>

                    <input type="text" id="cuti_fix" name="cuti_fix" hidden>
                </div>

                <div class="form-group" style="display: none;" id="alasan_reject">
                	<span style="color: red"><sup>*harus diisi</sup></span>
                	<label>Notes Reject Cuti (Pengurangan tanggal cuti)</label>
                	<textarea class="form-control" class="reason_reject" name="reason_reject" id="reason_reject"></textarea>
                </div>

                <div class="form-group">
                    <label>Jenis Cuti/Keterangan</label>
                    <textarea class="form-control" type="text" id="reason_detil" name="reason_detil" readonly></textarea>
                </div>      
                 
                <div class="modal-footer">
                  <button type="submit" id="submit_approve" class="btn btn-success"><i class=" fa fa-check"></i>&nbspApprove</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                </div>
            </form>
            </div>
          </div>
        </div>
    </div>


    <!--Modal Detail-->
    <div class="modal fade" id="details_cuti" role="dialog">
        <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content modal-md">
            <div class="modal-header">
              <h4 class="modal-title">Detail Leaving Permit</h4>
            </div>
            <div class="modal-body">
              <form>
                @csrf
                <input type="" name="id_cuti_detil" hidden="">
                <div class="form-group">
                    <label>Date Of Request</label>
                    <input type="text" class="form-control" id="date_request_detils" name="date_request_detil" readonly>
                </div>

                <div class="form-group">
                    <label>List Request Date Off</label>
                      <table class="table table-bordered" style="margin-top: 10px">
                        <tbody id="tanggal_cutis" class="tanggal_cuti">
                          
                        </tbody>
                      </table>
                </div>
                
                <div class="form-group">
                    <label>Jenis Cuti/Keterangan</label>
                    <textarea class="form-control" type="text" id="reason_detils" name="reason_detil" readonly></textarea>
                </div>   

                <div class="form-group" style="display: none;" id="alasan_reject_detail">
                	<label>Notes <span style="color: red">(Pengurangan jumlah cuti)</span></label>
                	<textarea class="form-control" class="reason_reject" readonly="" id="reason_reject_detil"></textarea>
                </div>
   
                 
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                </div>
            </form>
            </div>
          </div>
        </div>
    </div>

    <!--Modal Set Total Cuti-->
    <div class="modal fade" id="setting_cuti" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Setting Penggunaan Cuti</h4>
            </div>
            <div class="modal-body">
              <!-- <form method="POST" action="{{url('/set_total_cuti')}}">
                  @csrf
                  <div class="form-group">
                      <label>Masukkan Pengurangan Jatah Cuti Tahun ini (optional)</label>
                      <input type="" name="pengurangan_cuti" id="pengurangan_cuti" class="form-control" style="width: 60px">
                  </div>
                  <button class="btn btn-primary btn-xs" style="width: 60px">Submit</button>
                  <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" style="width: 60px"><i class=" fa fa-times"></i>&nbspClose</button>
              </form> -->
              <form method="POST" action="{{url('/setting_total_cuti')}}">
              <div class="form-group">
                  <label>Masukkan Pengurangan Jatah Cuti Tahun ini (optional)</label>
                  <input type="" name="pengurangan_cuti" id="pengurangan_cuti" class="form-control" style="width: 60px">
              </div>
            </div>
            <div class="modal-body">
              @csrf
              <div class="form-group" style="margin-top: 20px">
                  <div class="row">
                    <div class="col-md-4">
                      <label>Employees</label>
                        <select class="form-control users" id="users" name="users" style="width: 100%!important">
                          <option value="all_emp">All Employees</option>
                          @foreach($owner as $data)
                            @if($data->status_karyawan != 'dummy')
                            <option value="{{$data->nik}}">{{$data->name}}</option>
                            @endif
                          @endforeach
                        </select>
                        <label >Status Karyawan</label>
                        <select class="form-control users" id="status" name="status" style="width: 100%!important" required>
                          <option selected="" disabled="">Pilih Status!</option>
                          <option value="belum_cuti">1 tahun</option>
                          <option value="cuti"> < 1 tahun</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                      <label>Lama Kerja</label>
                      <input readonly="" type="" name="lama_kerja" id="lama_kerja" class="form-control">
                      <label>Tahun Masuk Kerja</label>
                      <input readonly="" type="" name="tahun_masuk" id="tahun_masuk" class="form-control">
                    </div>
                    <div class="col-md-2">
                      <label>Sisa Cuti</label>
                      <input type="" readonly="" name="current_cuti" id="current_cuti" class="form-control" style="width: 60px">
                    </div>
                    <div class="col-md-2">
                      <label>Set Cuti</label>
                      <input type="" name="set_cuti" id="set_cuti" class="form-control" style="width: 60px">
                    </div>
                  </div>
                </div>
                <button class="btn btn-primary btn-xs" style="width: 60px">Submit</button>
              <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" style="width: 60px"><i class=" fa fa-times"></i>&nbspClose</button>
              </form>
            </div>
          </div>
        </div>
    </div>


    <div class="modal fade" id="reason_decline" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Decline Information</h4>
            </div>
            <div class="modal-body">
              <!-- <form method="POST" action="{{url('/decline_cuti')}}" id="reason_decline" name="reason_decline"> -->
                @csrf
              <input type="" name="id_cuti_decline" id="id_cuti_decline" hidden="">
              <div class="form-group">
                <label for="sow">Decline reason</label>
                <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
              </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                  <button type="submit" class="btn btn-success-absen" onclick="submitDecline()"><i class="fa fa-check"></i>&nbsp Decline</button>
                </div>
            <!-- </form> -->
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="decline_reason" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Decline Information</h4>
            </div>
            <div class="modal-body">
              <form method="" action="" id="decline_reason" name="decline_reason">
                @csrf
              <div class="form-group">
                <label for="sow">Decline reason</label>
                <textarea name="keterangan_decline" id="keterangan_decline" class="form-control" readonly></textarea>
              </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button><!-- 
                  <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Decline</button> -->
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{asset('js/fullcalendar.js')}}"></script>
<script type='text/javascript' src="{{asset('js/gcal.js')}}"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

<script type="text/javascript">
    $(".show-sisa-cuti").click(function(){
      $.ajax({
        type:"GET",
        url:"getCutiAuth",
        success: function(result){
          var d = new Date().getFullYear() - 1;
          var dd = new Date().getFullYear();
          var swal_html = '<div class="panel" style="background:aliceblue;font-weight:bold"><div class="panel-heading panel-info text-center btn-info"> <b>Berikut Info total cuti : </b> </div> <div class="panel-body"><table class="text-center"><b><p style="font-weight:bold">Total cuti '+ d +' (*digunakan s/d 31 Maret) : '+result[0].cuti+'</p><p style="font-weight:bold">Total cuti '+ dd +' : '+result[0].cuti2+'</p></b></div></div></div>';
          swal.fire({title: "Hai "+result[0].name+" !!", html: swal_html})
        },
      });
    });

    // var tables = $('#datatables').DataTable();
    // var tablew = $("#datatablew").DataTable({
    //   "columnDefs":[
    //         {"width": "20%", "targets":0},
    //         {"width": "10%", "targets":2},
    //         {"width": "10%", "targets":3},
    //         {"width": "10%", "targets":4},
    //        ],
    //     "order": [[ "4", "desc" ]],
    // });

    var table  = $('#datatable').DataTable({
       "columnDefs":[
            {"width": "30%", "targets":0},
            {"width": "10%", "targets":2},
            {"width": "10%", "targets":3},
            {"width": "10%", "targets":4},
           ],
        "order": [[ "2", "desc" ]],
        // // "bPaginate": false,
        // "pageLength": 25,
        // "paging": false,
         // "bFilter": false,
    });

    $(".users").select2();

    $("#date_end").on("change",function(e){
      var start = $('#date_start').datepicker('getDate');
      var end = $('#date_end').datepicker('getDate');
      if (!start || !end) return;
      var days = (end - start) / 1000 / 60 / 60 / 24;
      $('#lihat_hasil').val(e.dates.length);
    });

    $(".add_cuti").click(function(){
      console.log(this.value)
      $.ajax({
        type:"GET",
        url:"getCutiUsers",
        data:{
          nik:this.value,
        },
        success: function(result){
          $("#form-submit-cuti").trigger("reset");
          if (result.parameterCuti.total_cuti == 0) {
            $("#sisa_cuti").text(0).style.color = "#ff0000";
          } else {
            $("#sisa_cuti").text(result.parameterCuti.total_cuti);
            if (result.parameterCuti.total_cuti > 5) {
              document.getElementById("sisa_cuti").style.color = "blue";
            } else {
              document.getElementById("sisa_cuti").style.color = "#ff0000";
            }
          }

          var disableDate = []
          $.each(result.allCutiDate,function(key,value){
            disableDate.push(moment( value).format("MM/DD/YYYY"))
          })

          $('#date_start').datepicker({
            weekStart: 1,
            daysOfWeekDisabled: "0,6",
            daysOfWeekHighlighted: [0,6],
            startDate: moment().format("MM/DD/YYYY"),
            todayHighlight: true,
            multidate: true,
            datesDisabled: disableDate,
            beforeShowDay: function(date){
              var index = hari_libur_nasional.indexOf(moment(date).format("MM/DD/YYYY"))
              if(index > 0){
                return {
                  // enabled: false,
                  tooltip: hari_libur_nasional_tooltip[index],
                  classes: 'hari_libur'
                };
              } else if(disableDate.indexOf(moment(date).format("MM/DD/YYYY")) > 0) {
                return {
                  enabled: false,
                  tooltip: 'Cuti Pribadi',
                };
              }
            },
          }).on('changeDate', function(e) {
            $('#lihat_hasil').val(' ' + e.dates.length)
            var cutis = $("#sisa_cuti").text();
            var cutiss = $(".lihat_hasil").val();
            // console.log(cutis + " " + cutiss)

            $("#avaliableDays").val(result.parameterCuti.total_cuti - cutiss)
            if (parseFloat(cutis) >= parseFloat(cutiss)) {
              e.preventDefault();     
              $(".btn-submit").prop('disabled', false);
              $("#tooltip").hide();
            } else if (parseFloat(cutis) < parseFloat(cutiss)) {
              $(".btn-submit").prop('disabled', true);
              $("#tooltip").show();
            }
          });

          
        },
      });

      $("#modalCuti").modal("show");

      $(document).on('click',"button[id^='btn-submit']",function(e){
          if($("input[name='jenis_cuti']:checked").val()){
            Swal.fire({
            title: 'Are you sure?',
            text: "to submit your leaving permite",
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
                  type:"POST",
                  url:"{{url('/store_cuti')}}",
                  data:{
                     _token: "{{ csrf_token() }}",
                    reason:$("#reason").val(),
                    jenis_cuti:$("input[name='jenis_cuti']:checked").val(),
                    date_start:$("#date_start").val(),
                    reason_edit:$("#reason_edit").val(),
                    status_update:'R',
                  },
                  success: function(result){
                    Swal.showLoading()
                    Swal.fire(
                      'Successfully!',
                      'Leaving permite has been created.',
                      'success'
                    ).then((result) => {
                      if (result.value) {
                        $("#modalCuti").modal('hide');
                        location.reload();
                      }
                    })
                  }
                })
              }else if(
                 result.dismiss === Swal.DismissReason.cancel
                ){
                $("#modalCuti").modal('hide');
              }
            }) 
          }else{
            // $("input[name='jenis_cuti']").prop('required',true);
            Swal.fire(
              'canceled',
              'Silahkan pilih jenis cuti lebih dahulu!',
              'error'
              )
          }
      })
    })

    $(document).on('click',"button[class^='approve_date']",function(e) {
        $.ajax({
          type:"GET",
          url:'{{url("/detilcuti")}}',
          data:{
            cuti:this.value,
          },
          success: function(result){
            var table = "";

            $.each(result[0], function(key, value){
              $("#id_cuti_detil").val(value.id_cuti);
              $("#nik_cuti").val(value.nik);
              $("#date_request_detil").val(moment(value.date_req).format('LL'));
              $("#reason_detil").val(value.reason_leave);
              $("#time_off").val(value.days);
              $('#tanggal_cuti').empty();
              table = table + '<tr>';
              table = table + '<td>' + '<input type="checkbox" class="check_date" checked name="check_date[]"' +'</td>';
              table = table + '<td hidden>' + value.date_off +'</td>';
              table = table + '<td>' + moment(value.date_off).format('LL'); +'</td>';
              table = table + '</tr>';
              
            });

            console.log(result[0].length);
            var date_check = result[0].length;

            $('#tanggal_cuti').append(table);

            var countChecked = function() {
			  var n = $( ".check_date:checked" ).length;
			  console.log( n + (n === 1 ? " is" : " are") + " checked!")

			  if (date_check != $( ".check_date:checked" ).length) {
			  	$("#alasan_reject").css("display", "block");
			  	$("#reason_reject").prop('required',true);
			  }else{
			  	$("#alasan_reject").css("display", "none");
			  	$("#reason_reject").prop('required',false);
			  }

			};
			countChecked();
			 
			$( ".check_date" ).on( "click", countChecked );

          }
        });

        $("#detail_cuti").modal("show");
    });
    
    $(document).on('click',"button[id^='btn-edit']",function(e) {
      $.ajax({
          type:"GET",
          url:'{{url("/detilcuti")}}',
          data:{
            cuti:this.value,
          },
          success: function(result){
            var table = "";

            var array = [];

            $.each(result[0], function(key, value){
              $("#id_cuti").val(value.id_cuti);
              $("#reason_edit").val(value.reason_leave);
              array.push(value.date_off);

              $("#Dates_update").val(array);

              console.log(array);

              $('#Dates').tooltip("disable");

              var disableDate = []
                $.each(result.allCutiDate,function(key,value){
                  disableDate.push(moment( value).format("MM/DD/YYYY"))
              })

              $("#Dates").datepicker({
                weekStart: 1,
                daysOfWeekDisabled: "0,6",
                daysOfWeekHighlighted: [0,6],
                startDate: moment().format("MM/DD/YYYY"),
                todayHighlight: true,
                multidate: true,
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
                      tooltip: 'Cuti Pribadi',
                    };
                  }
                },
              }).datepicker('setDate', array).on('changeDate', function(e) {
                  if (parseFloat(array.length) >= parseFloat(e.dates.length)) {
                    e.preventDefault();     
                    $(".btn-submit-update").prop('disabled', false);
                    $('#Dates').tooltip("disable");
                  } else if (parseFloat(array.length) < parseFloat(e.dates.length)) {
                    $(".btn-submit-update").prop('disabled', true);
                    $('#Dates').tooltip("enable");
                  }
              });
            });

        $("#modalCuti_edit").modal("show");
        }
      });

      $(document).on('click',"button[id^='btn-submit-update']",function(e){
        
        if ($("#Dates").val() == '') {
          var dates_after = 'kosong';
        }else{
          var dates_after = $("#Dates").val();
        }

        Swal.fire({
          title: 'Are you sure?',
          text: "to update your leaving permite",
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
              type:"POST",
              url:"{{url('/update_cuti')}}",
              data:{
                 _token: "{{ csrf_token() }}",
                id_cuti:$("#id_cuti").val(),
                dates_after:dates_after,
                dates_before:$("#Dates_update").val(),
                reason_edit:$("#reason_edit").val(),
                status_update:'R',
              },
              success: function(result){
                Swal.showLoading()
                Swal.fire(
                  'Updated!',
                  'Leaving permite has been update.',
                  'success'
                ).then((result) => {
                  if (result.value) {
                    $("#modalCuti_edit").modal('hide');
                    location.reload();
                  }
                })
              }
            })
          }
        })        
      })

    });


    function edit_cuti(id_cuti,date_start,date_end,reason_leave){
      $("#id_cuti").val(id_cuti);
      $("#date_start_edit").val(date_start);
      $("#date_end_edit").val(date_end);
      $("#reason_edit").val(reason_leave);
    }

    function detil_cuti(id_cuti){
      $("#reason_detil").val(id_cuti);
    }

    function decline(id_cuti,decline_reason){
      $.ajax({
        type:"GET",
        url:'{{url("/detilcuti")}}',
        data:{
          cuti:id_cuti,
        },
        success: function(result){
          var table = "";
          $.each(result[0], function(key, value){
            $("#keterangan_decline").val(value.decline_reason);
          });
        }
      });
      $('#decline_reason').modal('show')
    }

    function decline_cuti(id_cuti,decline_reason){
      $.ajax({
        type:"GET",
        url:'{{url("/detilcuti")}}',
        data:{
          cuti:id_cuti,
        },
        success: function(result){
          console.log(decline_reason)
          var table = "";
          $.each(result[0], function(key, value){
            $("#id_cuti_decline").val(value.id_cuti);
            $("#keterangan").val(value.decline_reason);
          });
        }
      });
      $('#reason_decline').modal('show')
    }

    $('#cutis a').click(function(e) {
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
    $('#cutis a[href="' + hash + '"]').tab('show');


    $('#submit_approve').click(function(){
      var updates = [];
      var selector = '#detil_cuy tr input:checked'; 
      $.each($(selector), function(idx, val) {
        var id = $(this).parent().siblings(":first").text();
        updates.push(id);
      });

      $("#cuti_fix").val(updates.join(","));

      // document.getElementById("cuti_fix").innerHTML = updates.join(",") ;
    })

    var url = {!! json_encode(url('/')) !!}

    function exportExcel() {
      filter      = encodeURI($("#pilih").val())
      division    = encodeURI($("#division_cuti").val())
      date_start  = encodeURI(moment($('#dates').val().slice(0,10)).format("YYYY-MM-DD"))
      date_end    = encodeURI(moment($('#dates').val().slice(13,23)).format("YYYY-MM-DD"))
      myUrl       = url+"/downloadCutiReport?division="+division+"&date_start="+date_start+"&date_end="+date_end+"&filter="+filter
      location.assign(myUrl)
    }

    var start_date = moment().startOf('year');
    var end_date = moment().endOf('year');

    var monthCuti;

    get_list_cuti();
    get_cuti_byMonth();
    get_history_cuti(); 

    function get_cuti_byMonth(){
      monthCuti = $("#datatablew").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('get_cuti_byMonth')}}",
        },
        "columns": [
          { 
            render: function ( data, type, row ) {
              return row.name.toUpperCase()
            } 
          },
          { 
            render: function ( data, type, row ) {
              if (row.id_division == '-') {
                  return 'Admin';
              }else{
                  return row.id_division;  
              }
            } 
          },
          { "data": "date_req"},
          { 
            render: function (data, type, row) {
              return '<button name="date_off" id="date_off" class="date_off" value="'+row.id_cuti+'" style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">' + row.days +' Days <i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"></i></button>'
            }
          },
          {
            render: function (data, type, row) {
              if(row.status == 'v'){
               return '<span class="label label-success">Approved</span>'
              }else if(row.status == 'd'){
               return '<span class="label label-danger" onclick="decline('+ row.id_cuti +')">Declined</span>'
              }else{
               return '<span class="label label-warning">Pending</span>'
              }
            } 
          },
          {
            render: function (data, type, row) {
              if({{Auth::User()->nik}} == row.nik){
                if( row.status == 'n'){
                  return '<button class="btn btn-sm btn-primary fa fa-edit" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="btn-edit" data-toggle="tooltip" title="Edit" data-placement="bottom" value="'+row.id_cuti+'" type="button"></button>' + ' ' +
                  '<button class="btn btn-sm btn-danger fa fa-trash btn_delete" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Delete" data-placement="bottom" value="'+row.id_cuti+'" type="button"></button>' + ' ' +
                  '<button class="btn btn-sm btn-success fa fa-paper-plane btn_fu" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Follow Up Cuti" data-placement="bottom" value="'+row.id_cuti+'" type="button"></button>'
                }else{
                  return ''
                }
              }else{
                if(row.status == 'n' || row.status == 'R'){
                  return '<button name="approve_date" id="approve_date" class="approve_date btn btn-success btn-xs" style="width: 60px" value="'+row.id_cuti+'" >Approve</button>' + ' ' +
                    '<button class="btn btn-xs btn-danger btn_decline" style="vertical-align: top; width: 60px; margin-left: 5px" value="'+row.id_cuti+'" onclick="decline_cuti('+row.id_cuti+')" >Decline</button>'
                }else{
                    return '<button class="btn btn-xs btn-success disabled" style="vertical-align: top; width: 60px">Approve</button>' + ' ' +
                    '<button class="btn btn-xs btn-danger disabled" style="vertical-align: top; width: 60px; margin-left: 5px">Decline</button>'
                }
              }
            } 
          },
        ],
        initComplete: function() {
          if ("{{Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'HR MANAGER'}}") {
            if (this.api().data().length) {
              $('#cuti_tab').append('<span class="badge">'+ this.api().data().length +'</span>')
              activeTab('cuti')
            }else{
              activeTab('cuti') 
            }
          }else{
            activeTab('cuti')
          }
        },
        "searching": true,
        "lengthChange": true,
        "order": [[ 0, "asc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 25,
      })
    }

    function get_history_cuti(){
      $("#datatableq").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('get_history_cuti')}}",
        },
        "columns": [
          { 
            render: function ( data, type, row ) {
              return row.name.toUpperCase()
            } 
          },
          { "data": "id_division" },
          { "data": "date_req"},
          { 
            render: function (data, type, row) {
              return '<button name="date_off" id="date_off" class="date_off" value="'+row.id_cuti+'" style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">' + row.days +' Days <i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"></i></button>'
            }
          },
          { "data": "updated_at" },
          { "data": "pic"},
          {
            render: function (data, type, row) {
              if(row.status == 'v'){
               return '<span class="label label-success">Approved</span>'
              }else if(row.status == 'd'){
               return '<span class="label label-danger" onclick="decline('+ row.id_cuti +')">Declined</span>'
              }else{
               return '<span class="label label-warning">Pending</span>'
              }
            } 
          },
        ],
        "searching": true,
        "lengthChange": true,
        "order": [[ 2, "desc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 10,
      })
    }

    function activeTab(tab){
      $('#cutis a[href="#' + tab + '"]').tab('show');
    }

    function submitDecline(){

      $('#tunggu').modal('show');
      $('#reason_decline').modal('hide')

      $.ajax({
        type:"POST",
        url:"{{url('/decline_cuti')}}",
        data:{
          _token: "{{ csrf_token() }}",
          id_cuti:($("#id_cuti_decline").val()),
          decline_reason:($("#keterangan").val()),
        },
        success:function(result){
          $('#tunggu').modal('hide');
          $('#reason_decline').modal('hide')
          location.reload()
        }
      })
    }

    $('#datatablew').on('click', '.btn_delete', function(e){
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
          var id_cuti = this.value;
          $('#tunggu').modal('show');
            $.ajax({
              type:"GET",
              url:"{{url('delete_cuti/')}}/"+id_cuti,
              success: function(result){
                $('#tunggu').modal('hide');
                Swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            ),
                setTimeout(function(){
                    $('#datatablew').DataTable().ajax.url("{{url('get_cuti_byMonth')}}").load();
                },2000);
              }
            })
        }
      })
        
    })


    $('#datatablew').on('click', '.btn_fu', function(e){
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
          var id_cuti = this.value;
          $('#tunggu').modal('show');
            $.ajax({
              type:"GET",
              url:"{{url('follow_up/')}}/"+id_cuti,
              success: function(result){
                $('#tunggu').modal('hide');
                Swal.fire(
              'Successfully!',
              'Your request has been sent.',
              'success'
            ),
                setTimeout(function(){
                    $('#datatablew').DataTable().ajax.url("{{url('get_cuti_byMonth')}}").load();
                },2000);
              }
            })
        }
      })
        
    })

    function get_list_cuti(){
      $("#datatables").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('get_list_cuti')}}",
        },
        "columns": [
          { 
            render: function ( data, type, row ) {
              return row.name.toUpperCase()
            } 
          },
          { "data": "email" },
          { 
            render: function ( data, type, row ) {
              if (row.id_division == '-') {
                  return 'Admin';
              }else{
                  return row.id_division;  
              }
            } 
          },
          { 
            render: function (data, type, row) {
              return moment(row.date_of_entry).format('L');
            } 
          },
          { 
            render: function (data, type, row) {
              if(row.date_of_entrys > 365){
                return Math.floor(row.date_of_entrys / 365) + ' Tahun ' + Math.floor(row.date_of_entrys % 365 / 30) + ' Bulan';
              }else if(row.date_of_entrys > 31){
                return Math.floor(row.date_of_entrys / 30) + ' Bulan';
              }else{
                return row.date_of_entrys + ' Hari';
              }
            }
          },
          {
            render: function (data, type, row) {
              if(row.niks < 1){
                return '1 Hari';
              }else if(row.niks == undefined){
                return '-'
              }else{
                return row.niks + ' Hari';
              }
            } 
          },
          {
            render: function (data, type, row) {
              if(row.cuti == null){
                return '-';
              }else{
                return row.cuti + ' Hari';
              }
            } 
          },
          {
            render: function (data, type, row) {
              if(row.status_karyawan == 'belum_cuti'){
                return '-';
              }else{
                return row.cuti2 + ' Hari';
              }
            } 
          },
        ],
        "searching": true,
        "lengthChange": true,
        "order": [[ 0, "asc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 10,
      })
    }

    function cb(start_date,end_date,url,division){
        start  = start_date.format("YYYY-MM-DD 00:00:00");
        end    = end_date.format("YYYY-MM-DD 00:00:00");

        $.ajax({
              type:"GET",
              url:url,
              data:{
                division:division,
                start:start,
                end:end
              },
              success: function(result){
                $('#datatable').DataTable({
                   "destroy": true,
                   "columnDefs":[
                        {"width": "30%", "targets":0},
                        {"width": "10%", "targets":2},
                        {"width": "10%", "targets":3},
                        {"width": "10%", "targets":4},
                       ],
                    "order": [[ "2", "desc" ]],
                    "lengthChange": false,
                    "paging": false,
                });
                $('#report').empty();

                var table = "";

                $.each(result, function(key, value){
                  table = table + '<tr>';
                  table = table + '<td>' +value.name+ '</td>';
                  table = table + '<td>' +value.id_division+ '</td>';
                  table = table + '<td>' +value.date_req+ '</td>';
                  table = table + '<td>' +'<button name="date_off" id="date_off" class="date_off" value="'+value.id_cuti+'" style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">'+ value.days + ' Hari' + '<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"/>'+'</button>'+'</td>';
                  if (value.updated_at == null) {
                    table = table + '<td> - </td>';
                    table = table + '<td> - </td>';
                  }else{
                    table = table + '<td>' +value.updated_at+ '</td>';
                    table = table + '<td>' +value.pic+ '</td>';
                  }
                  if (value.status == 'v') {
                    table = table + '<td>' +'<label class="label label-success">Approved</label>'+ '</td>';
                  }else if (value.status == 'd') {
                    table = table + '<td>' +'<label class="label label-danger" onclick="decline('+value.id_cuti+')">Declined</label>'+ '</td>';
                  }else{
                    table = table + '<td>' +'<label class="label label-warning">Pending</label>'+ '</td>';
                  }
                  // table = table + '<td>' +' '+ '</td>';
                  
                  table = table + '</tr>';

                });
                $('#report').append(table);
                
              },
        });

        
    }

    cb(start_date,end_date,"{{url('getFilterCom')}}?filter_com="+$("#filter_com").val(),$("#division_cuti").val());

    $('input[name="dates"]').daterangepicker({
      startDate: start_date,
      endDate: end_date,
      locale: {
        format: 'MM/DD/YYYY'
      },
      }, function(start, end, label) {
      cb(start,end,"{{url('getFilterCom')}}?filter_com="+$("#filter_com").val(),$("#division_cuti").val())
    });

    $("#pilih").change(function(){
      console.log(this.value)
      if(this.value == 'date'){
        table.draw();
        $("#dates").prop('disabled', false);
        $("#division_cuti").prop('disabled', true);
         $('input[name="dates"]').daterangepicker({

        }, function(start, end, label) {
            $.ajax({
                  type:"GET",
                  url:"/getfilterCutiByDate",
                  data:{
                    start:start.format('YYYY-MM-DD'),
                    end:end.format('YYYY-MM-DD')
                  },
                  success: function(result){
                    $('#datatable').DataTable({
                       "destroy": true,
                 "columnDefs":[
                      {"width": "30%", "targets":0},
                      {"width": "10%", "targets":2},
                      {"width": "10%", "targets":3},
                      {"width": "10%", "targets":4},
                     ],
                  "order": [[ "2", "desc" ]],
                  "lengthChange": false,
                  "paging": false,
              });
                    $('#report').empty();

                    var table = "";

                    $.each(result, function(key, value){
                      table = table + '<tr>';
                      table = table + '<td>' +value.name+ '</td>';
                      table = table + '<td>' +value.id_division+ '</td>';
                      table = table + '<td>' +'<button name="date_off" id="date_off" class="date_off" value="'+value.id_cuti+'" style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">'+ value.days + ' Hari' + '<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"/>'+'</button>'+'</td>';
                      table = table + '<td>' +value.date_req+ '</td>';
                      if (value.updated_at == null) {
                        table = table + '<td> - </td>';
                        table = table + '<td> - </td>';
                      }else{
                        table = table + '<td>' +value.updated_at+ '</td>';
                        table = table + '<td>' +value.pic+ '</td>';
                      }
                      if (value.status == 'v') {
                        table = table + '<td>' +'<label class="label-success">Approved</label>'+ '</td>';
                      }else if (value.status == 'd') {
                        table = table + '<td>' +'<label class="label-danger">Declined</label>'+ '</td>';
                      }else{
                        table = table + '<td>' +'<label class="label-warning">Pending</label>'+ '</td>';
                      }
                      
                      table = table + '<td>' +' '+ '</td>';
                      
                      table = table + '</tr>';

                    });
                    $('#report').append(table);
                    
                  },
              });
        });

      }else if (this.value == 'div') {
        $("#dates").prop('disabled', true);
        $("#division_cuti").prop('disabled', false);

        table.draw();
        $('#datatable').DataTable({
           "destroy": true,
         "columnDefs":[
              {"width": "30%", "targets":0},
              {"width": "10%", "targets":2},
              {"width": "10%", "targets":3},
              {"width": "10%", "targets":4},
             ],
          "order": [[ "2", "desc" ]],
          "lengthChange": true,
          "paging": true,
      });

        $("#division_cuti").change(function(){
          $.ajax({
              type:"GET",
              url:"{{url('/getfilterCutiByDiv')}}",
              data:{
                division:this.value,
              },
              success: function(result){
                $('#datatable').DataTable({
                   "destroy": true,
                   "columnDefs":[
                        {"width": "30%", "targets":0},
                        {"width": "10%", "targets":2},
                        {"width": "10%", "targets":3},
                        {"width": "10%", "targets":4},
                       ],
                    "order": [[ "2", "desc" ]],
                    "lengthChange": false,
                    "paging": false,
                });

                $('#report').empty();

                var table = "";

                $.each(result, function(key, value){
                  table = table + '<tr>';
                  table = table + '<td>' +value.name+ '</td>';
                  table = table + '<td>' +value.id_division+ '</td>';
                  table = table + '<td>' +'<button name="date_off" id="date_off" class="date_off" value="'+value.id_cuti+'" style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">'+ value.days + ' Hari' + '<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"/>'+'</button>'+'</td>';
                  table = table + '<td>' +value.date_req+ '</td>';
                  if (value.updated_at == null) {
                    table = table + '<td> - </td>';
                    table = table + '<td> - </td>';
                  }else{
                    table = table + '<td>' +value.updated_at+ '</td>';
                    table = table + '<td>' +value.pic+ '</td>';
                  }
                 if (value.status == 'v') {
                    table = table + '<td>' +'<label class="label-success">Approved</label>'+ '</td>';
                  }else if (value.status == 'd') {
                    table = table + '<td>' +'<label class="label-danger">Declined</label>'+ '</td>';
                  }else{
                    table = table + '<td>' +'<label class="label-warning">Pending</label>'+ '</td>';
                  }
                  table = table + '<td>' +' '+ '</td>';
                  
                  table = table + '</tr>';

                });
                $('#report').append(table);
              },
          });
            
      });

        $("#dates").daterangepicker(
           $("#dates").val('')
        )
      }else{
        $("#dates").prop('disabled', false);
        $("#division_cuti").prop('disabled', false);

        // table.draw();
        $('#datatable').DataTable({
           "destroy": true,
         "columnDefs":[
              {"width": "30%", "targets":0},
              {"width": "10%", "targets":2},
              {"width": "10%", "targets":3},
              {"width": "10%", "targets":4},
             ],
          "order": [[ "2", "desc" ]],
          "lengthChange": true,
          "paging": true,
      });

      }
    })

    @if(Auth::User()->id_position == 'HR MANAGER') {
      $(document).on('click',"button[class^='date_off']",function(e) {
      console.log($(".date_off").val());
        $.ajax({
          type:"GET",
          url:'{{url("/detilcuti")}}',
          data:{
            pilih:$("#pilih").val(),
            date_start:moment($('#dates').val().slice(0,10)).format("YYYY-MM-DD"),
            date_end:moment($('#dates').val().slice(13,23)).format("YYYY-MM-DD"),
            cuti:this.value,
          },
          success: function(result){
            var table = "";

            $.each(result[0], function(key, value){
              $("#date_request_detils").val(moment(value.date_req).format('LL'));
              $("#reason_detils").val(value.reason_leave);
              $('#tanggal_cutis').empty();
              table = table + '<tr>';
              table = table + '<td>' + moment(value.date_off).format('LL'); +'</td>';
              table = table + '</tr>';
            });

            $('#tanggal_cutis').append(table);

          }
        });

        $("#details_cuti").modal("show");
     });
    }@else{
      $(document).on('click',"button[class^='date_off']",function(e) {
      console.log($(".date_off").val());
        $.ajax({
          type:"GET",
          url:'{{url("/detilcuti")}}',
          data:{
            cuti:this.value,
          },
          success: function(result){
            var table = "";

            $.each(result[0], function(key, value){
              $("#date_request_detils").val(moment(value.date_req).format('LL'));
              $("#reason_detils").val(value.reason_leave);
              $('#tanggal_cutis').empty();
              table = table + '<tr>';
              table = table + '<td>' + moment(value.date_off).format('LL'); +'</td>';
              table = table + '</tr>';

              if (value.decline_reason != null) {
              	$("#alasan_reject_detail").css("display","block");
              	$("#reason_reject_detil").val(value.decline_reason);
              }else if(value.status == 'v'){
              	$("#alasan_reject_detail").css("display","none");
              }
            });

            $('#tanggal_cutis').append(table);

          }
        });

        $("#details_cuti").modal("show");
     });
    }
    @endif
    
    
    $("#users").change(function(){
      $.ajax({
        type:"GET",
        url:"getCutiUsers",
        data:{
          nik:this.value,
        },
        success: function(result){
          $.each(result, function(key, value){
            $("#lama_kerja").val(Math.floor(value.parameterCuti.date_of_entrys / 365) + ' Tahun ' + value.parameterCuti.date_of_entrys % 365 +' Hari');
            $("#current_cuti").val(value.parameterCuti.total_cuti);
            $("#tahun_masuk").val(moment(value.parameterCuti.date_of_entry).format('ll'));
          });
        },
      });
    });

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

    $(".toggle-arrow").click(function(){
      $(this).toggleClass("fa-angle-down");

      $(".div-libur").toggle('1000');
    })

    $(".toggle-password").click(function(){
      $(this).toggleClass("fa-angle-down");
    })

    function print()
    {
      window.print();
    }


    $("#filter_com").change(function(){
      var filter_com = this.value;
      var companyString = $(".tabs_item.active").children().attr('onclick').slice(12,19)
      console.log(filter_com)
      if (companyString == "all_lis") {
        $('#datatables').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com="+filter_com+"&id=" + companyString).load();
      } else if (companyString == "request") {
        $('#datatablew').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com="+filter_com+"&id=" + companyString).load();
      } else if (companyString == "report_"){
        var start_date = $('input[name="dates"]').data('daterangepicker').startDate
        var end_date = $('input[name="dates"]').data('daterangepicker').endDate
        cb(start_date,end_date,"{{url('getFilterCom')}}?filter_com="+filter_com+"&id=" + companyString,$("#division_cuti").val());
      } else {
        $('#datatableq').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com=1&id="+id).load();
      }
    });

    $("#division_cuti").change(function(){
        var companyString = $(".tabs_item.active").children().attr('onclick').slice(12,19)
        var start_date = $('input[name="dates"]').data('daterangepicker').startDate
        var end_date = $('input[name="dates"]').data('daterangepicker').endDate
        cb(start_date,end_date,"{{url('getFilterCom')}}?filter_com="+$("#filter_com").val()+"&id="+companyString,this.value);
    });

    function changeTabs(id) {
      com = $("#filter_com").val()
      console.log(id)
      if (id == "all_lis") {
        $('#datatables').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com="+com+"&id="+id).load();
      } else if(id == "request"){
        @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
          $('#datatablew').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com="+com+"&id="+id).load();
        @else 
          $('#datatablew').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com=1&id="+id).load();
        @endif
      } else if(id == "report_"){
        var start_date = $('input[name="dates"]').data('daterangepicker').startDate
        var end_date = $('input[name="dates"]').data('daterangepicker').endDate
        cb(start_date,end_date,"{{url('getFilterCom')}}?filter_com="+com+"&id="+id,$("#division_cuti").val());
      } else {
        $('#datatableq').DataTable().ajax.url("{{url('getFilterCom')}}?filter_com=1&id="+id).load();
      }
    }

    $(".disabled-permission").hover(function(){
      swal({
		  text: "Not Allowed to Leaving Permit Access!",
  	  });
      });

    $(document).ready(function(){
  	  $('[data-toggle="tooltip"]').tooltip(); 
  	});

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
      $(".alert").slideUp(300);
    });
    

  </script>
@endsection
