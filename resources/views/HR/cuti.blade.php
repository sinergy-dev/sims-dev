@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
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
      font-size: 1em; /* change icon size */
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
      
  </style>

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
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Hari Libur Nasional Tahun {{$year}}</h3>
        <div class="box-tools pull-right">
          <i class="fa fa-fw fa-lg fa-angle-left field-icon toggle-arrow"></i>
            </div>
      </div>
        <div class="box-body div-libur" style="display: none;">
          
        </div>
      </div>

    <div class="box">
      <div class="box-header">
          <div class="pull-right">
          @if($cek_cuti->status_karyawan == 'cuti')
            @if($cek_cuti->cuti > 0)
            <button type="button" class="btn btn-sm btn-primary pull-right add_cuti" value="{{Auth::User()->nik}}" style="margin-left: 10px"><i class="fa fa-plus"> </i> &nbspPermission</button>
            @else
            <button type="button" class="btn btn-sm btn-primary pull-right disabled" style="margin-left: 10px"><i class="fa fa-plus"> </i> &nbspPermission</button>
            @endif
          @else
          @endif
            
          @if(Auth::User()->id_position == 'HR MANAGER')
          <div style="width: 170px;margin-right: 10px" class="pull-left">
            <div class="input-group date">
                <select class="form-control" id="pilih" name="pilih">
                  <option value="Select">-- Select Filter By --</option>
                  <option value="date">Filter By Date</option>
                  <option value="div">Filter By Division</option>
                  <option value="all">Filter By Date & Div</option>
                </select>
            </div>
          </div> 

          <div style="width: 300px;margin-right: 10px" class="pull-left">
            <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" id="dates" name="dates" disabled="">
            </div>
          </div> 

          <div style="width: 250px;margin-right: 10px" class="input-group date pull-left disabled">
            <div class="input-group-addon">
              <i class="fa fa-filter"></i>
            </div>
            <select class="form-control" id="division_cuti" name="division_cuti" disabled="">
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
          <a href="{{action('HRGAController@cutipdf')}}" target="_blank" onclick="print()">
          <button class="btn btn-sm btn-danger" style="width: 120px"><i class="fa fa-file-pdf-o"></i>&nbsp Preview PDF</button></a>
          <button class="btn btn-sm btn-success" onclick="exportExcel()"><i class="fa fa-file-excel-o"></i>&nbspExcel</button>
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#setting_cuti"><i class="fa fa-wrench"></i>&nbspTotal Cuti</button>
          @endif
           
          </div>
      </div>



      <div class="box-body">
        <div class="table-responsive">
          
          @if(Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'HR MANAGER')
            <div class="nav-tabs-custom">

              <ul class="nav nav-tabs" id="cutis">
                  <li>
                    @if(Auth::User()->id_position == 'HR MANAGER')
                    <a href="#bos" data-toggle="tab">List Cuti Karyawan</a>
                    @else
                    <a href="#bos" data-toggle="tab">{{Auth::User()->name}}</a>
                    @endif
                  </li>
                  <li  class="active">
                    @if(Auth::User()->id_position == 'HR MANAGER')
                    <a href="#staff" data-toggle="tab">Report Cuti</a>
                    @else
                    <a href="#staff" data-toggle="tab">STAFF</a>
                    @endif
                  </li>
                  @if(Auth::User()->id_position == 'HR MANAGER')
                  <li>
                    <a href="#cuti" data-toggle="tab">Report Cuti {{$bulan}}</a>
                  </li>
                  @endif
              </ul>
          @endif

              <div class="tab-content">

                @if(Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'HR MANAGER')
                <div class="tab-pane" id="bos"> 
                  <table class="table table-bordered table-striped dataTable" id="datatables" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          @if(Auth::User()->id_position == 'HR MANAGER') 
                            <th rowspan="2"><center>Employees Name</center></th>
                            <th rowspan="2"><center>Email</center></th>
                            <th rowspan="2"><center>Division</center></th>
                            <th rowspan="2"><center>Tanggal Masuk Kerja</center></th>
                            <th rowspan="2"><center>Lama Bekerja</center></th>
                            <th rowspan="2"><center>Cuti sudah diambil</center></th>
                            <th colspan="2"><center>Sisa Cuti</center></th>
                          @else  
                            <th>Employees Name</th>
                            <th>Date Of Request</th>
                            <th>Time Off</th>
                            <th>Status</th>
                          @endif
                        </tr>
                        <tr>
                          @if(Auth::User()->id_position == 'HR MANAGER')
                            <th>2019</th>
                            <th>2020</th>
                          @else  
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                          @endif
                        </tr>
                      </thead>
                        <tbody id="all_cuti" name="all_cuti">
                          @if(Auth::User()->id_position == 'HR MANAGER')
                            @foreach($cuti_index as $datas)
                              <tr>
                                <td>{{ucwords(strtolower($datas->name))}}</td>
                                <td>{{$datas->email}}</td>
                                <td>{{$datas->id_division}}</td>
                                <td>{{str_replace('-', '/', $datas->date_of_entry)}}</td>
                                <td>
                                  @if($datas->date_of_entrys > 365)
                                  {{ floor($datas->date_of_entrys / 365) }} Tahun {{ round($datas->date_of_entrys % 365 / 30 )}} Bulan
                                  @elseif($datas->date_of_entrys > 31)
                                  {{ floor($datas->date_of_entrys / 30)}} Bulan
                                  @else
                                  {{$datas->date_of_entrys}} Hari
                                  @endif
                                </td>
                                <td>
                                  @if($datas->niks < 1)
                                  1
                                  @else
                                  {{$datas->niks}}
                                  @endif
                                Hari
                                </td>
                                <td>
                                  @if($datas->cuti == NULL)
                                  -
                                  @else
                                  {{$datas->cuti}} Hari
                                  @endif
                                </td>
                                <td></td>
                              </tr>
                            @endforeach
                            @foreach($cuti_list as $data)
                              <tr>
                                <td>{{ucwords(strtolower($data->name))}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->id_division}}</td>
                                <td>{{str_replace('-', '/', $data->date_of_entry)}}</td>
                                <td>
                                  @if($data->date_of_entrys > 365)
                                  {{ floor($data->date_of_entrys / 365) }} Tahun {{ round($data->date_of_entrys % 365 / 30 )}} Bulan
                                  @elseif($data->date_of_entrys > 31)
                                  {{ floor($data->date_of_entrys / 30)}} Bulan
                                  @else
                                  {{$data->date_of_entrys}} Hari
                                  @endif
                                </td>
                                <td>
                                  0 Hari
                                </td>
                                <td>
                                  @if($data->cuti == NULL)
                                  -
                                  @else
                                  {{$data->cuti}} Hari
                                  @endif
                                </td>
                                <td></td>
                              </tr>
                            @endforeach
                          @else
                            @foreach($cuti as $detail_cuti => $data)
                              @if(Auth::User()->nik == $data->nik)
                                <tr>
                                  <td>{{$data->name}}</td>
                                  <td>
                                    {{$data->date_req}}
                                  </td>
                                  <td>
                                    <button name="date_off" id="date_off" class="date_off" value="{{$data->id_cuti}}" style="outline: none;background-color: transparent;background-repeat:no-repeat;
                                      border: none;">{{$data->days}}
                                    Days<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"></i></button>
                                  </td>
                                  <td>
                                    @if($data->status == 'v')
                                     <label class="btn-sm btn-success">Approved</label>
                                    @elseif($data->status == 'd')
                                     <label class="btn-sm btn-danger" data-target="#decline_reason" data-toggle="modal" onclick="decline('{{$data->id_cuti}}', '{{$data->decline_reason}}')">Declined</label>
                                    @else
                                     <label class="btn-sm btn-warning">Pending</label> 
                                    @endif
                                  </td>

                                </tr>
                               @endif
                            @endforeach
                          @endif
                      </tbody>
                  </table>
                </div>
                @endif 

                  
                @if(Auth::User()->id_position != 'MANAGER')  
                <div class="tab-pane active" id="staff">
                  @else
                  <div class="tab-pane active" id="staff">
                  @endif
                    <table class="table table-bordered table-striped dataTable" id="datatable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Employees Name</th>
                            <th>Division</th>
                            @if(Auth::User()->id_position == 'HR MANAGER')
                            <th>Cuti Request</th>
                            <th>Request Date</th>
                            @else
                            <th>Date of Request</th>
                            <th>Time Off</th>
                            @endif
                            <th>Status</th>
                            @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'HR MANAGER' )
                              <th>
                               Action
                            </th>
                          @else
                              <th>
                               Action
                              </th>
                          @endif
                          </tr>
                        </thead>
                          <tbody id="report" name="report">
                            @foreach($cuti as $data)
                              @if(($data->nik == Auth::User()->nik) == (Auth::User()->id_position != 'ENGINEER MANAGER' && Auth::User()->id_position != 'MANAGER'))
                               <tr>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->name_division}}</td>
                                    <td>
                                      {{$data->date_req}}
                                    </td>
                                    <td>
                                      <button name="date_off" id="date_off" class="date_off" value="{{$data->id_cuti}}" style="outline: none;background-color: transparent;background-repeat:no-repeat;
                                      border: none;">{{$data->days}}
                                    Days<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"></i></button>
                                    </td>
                                    <td>
                                      @if($data->status == 'v')
                                       <label class="btn-sm btn-success">Approved</label>
                                      @elseif($data->status == 'd')
                                       <label class="btn-sm btn-danger" data-target="#decline_reason" data-toggle="modal" onclick="decline('{{$data->id_cuti}}', '{{$data->decline_reason}}')">Declined</label>
                                      @else
                                       <label class="btn-sm btn-warning">Pending</label> 
                                      @endif
                                    </td>
                                    @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER')
                                    <td>
                                        @if($data->status == NULL)
                                          @if(Auth::User()->id_territory == '')
                                            @if($data->id_position == 'MANAGER')
                                            <button tname="approve_date" id="approve_date" class="approve_date btn btn-success btn-xs" style="width: 60px" value="{{$data->id_cuti}}" >Approve</button>
                                            <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px; margin-left: 5px" data-target="#reason_decline" data-toggle="modal" onclick="decline('{{$data->id_cuti}}','{{$data->decline_reason}}')">Decline</button>
                                            @else
                                            <i>no action</i>
                                            @endif
                                          @else
                                          <button tname="approve_date" id="approve_date" class="approve_date btn btn-success btn-xs" style="width: 60px" value="{{$data->id_cuti}}" >Approve</button>
                                          <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px; margin-left: 5px" data-target="#reason_decline" data-toggle="modal" onclick="decline('{{$data->id_cuti}}','{{$data->decline_reason}}')">Decline</button>
                                          @endif  
                                         @else
                                          <i>no action</i>
                                        @endif
                                    </td>
                                    @else
                                        <td>
                                          @if($data->status == NULL)
                                          <button class="btn btn-primary btn-xs" id="btn-edit" style="width: 60px;" value="{{$data->id_cuti}}">Edit</button>
                                          <a href="{{ url('delete_cuti', $data->id_cuti) }}">
                                          <button class="btn btn-xs btn-danger" style="width: 60px;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">&nbspDelete
                                    </button>
                                    </a>
                                          @endif
                                        </td>
                                    @endif
                               </tr>
                            @elseif(Auth::User()->id_position == 'HR MANAGER')
                              <tr>
                                  <td>{{$data->name}}</td>
                                  <td>{{$data->id_division}}</td>
                                  <td>
                                    <button name="date_off" id="date_off" class="date_off" value="{{$data->id_cuti}}" style="outline: none;background-color: transparent;background-repeat:no-repeat;
                                    border: none;">{{$data->days}}
                                  Days<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"></i></button>
                                  </td>

                                  <td>{{$data->date_req}}</td>
                                  <td>
                                    @if($data->status == 'v')
                                     <label class="btn-sm btn-success">Approved</label>
                                    @elseif($data->status == 'd')
                                     <label class="btn-sm btn-danger" data-target="#decline_reason" data-toggle="modal" onclick="decline('{{$data->id_cuti}}', '{{$data->decline_reason}}')">Declined</label>
                                    @else
                                     <label class="btn-sm btn-warning">Pending</label> 
                                    @endif
                                  </td>
                                  @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER')
                                  <td>
                                      @if($data->status == NULL)
                                        <button name="approve_date" id="approve_date" class="approve_date btn btn-success btn-xs" style="width: 60px" value="{{$data->id_cuti}}" >Approve</button>
                                        <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px; margin-left: 5px" data-target="#reason_decline" data-toggle="modal" onclick="decline('{{$data->id_cuti}}','{{$data->decline_reason}}')">Decline</button>
                                      @else
                                        <button class="btn btn-xs btn-success disabled" style="vertical-align: top; width: 60px">Approve</button>
                                        <button class="btn btn-xs btn-danger disabled" style="vertical-align: top; width: 60px; margin-left: 5px">Decline</button>
                                      @endif
                                  </td>
                                  @else
                                      <td>
                                        @if($data->status == NULL)
                                        <button class="btn btn-primary btn-xs" style="width: 60px;" id="btn-edit" value="{{$data->id_cuti}}">Edit</button>
                                        <a href="{{ url('delete_sales', $data->lead_id) }}">
                                          <button class="btn btn-xs btn-danger" style="width: 60px;" onclick="return confirm('Are you sure want to delete this Lead Register? And this data is not used in other table')">&nbspDelete
                                    </button>
                                  </a>
                                        @endif
                                      </td>
                                  @endif
                               </tr>
                              @endif
                            @endforeach
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
            <form method="POST" action="{{url('/store_cuti')}}" id="modalAddCuti" name="modalAddCuti">
              @csrf

              <div class="form-group">
                <label>Sisa Cuti : </label>
                <span name="sisa_cuti" id="sisa_cuti"></span><!-- 
                <input type="text" name="sisa_cuti" id="sisa_cuti" style="width: 50px;color: black;text-decoration: bold" class="form-control sisa_cuti" value="" readonly=""> -->
              </div>

              <div class="form-group">
                  <label>Date</label>
                  <input type="text" class="form-control" id="date_start" name="date_start" autocomplete="Off" required>
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
                <button type="submit" class="btn btn-primary btn-submit" disabled data-placement="top"><i class="fa fa-check"> </i>&nbspSubmit</button>
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
              <form method="POST" action="{{url('/update_cuti')}}" id="modaleditCuti" name="modaleditCuti">
                @csrf
                <input type="" name="id_cuti" id="id_cuti" value="" hidden>

                <div class="form-group">
                    <label>Reason For Leave</label>
                    <textarea class="form-control" type="text" id="reason_edit" name="reason_edit" required></textarea>
                </div>   

                <div class="form-group">
                    <label>Date Off</label>

                    <table class="table table-bordered">
                      <tbody id="tanggal_cuti_coba" class="tanggal_cuti_coba">
                        
                      </tbody>
                    </table>
                </div>

                <div class="input-group date form-group" id="datepicker">
                  <input type="text" class="form-control" id="Dates" name="Dates" autocomplete="off" placeholder="Select days" required />
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>
                </div> 
                 
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                  <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"> </i>&nbspSubmit</button>
                </div>
            </form>
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

                    <input type="text" id="cuti_fix" name="cuti_fix" hidden="">
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
                <input type="" name="id_cuti_detil" id="id_cuti_detil" hidden="">
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
              <form method="POST" action="{{url('/set_total_cuti')}}">
                  @csrf
                  <div class="form-group">
                      <label>Masukkan Pengurangan Jatah Cuti Tahun ini (optional)</label>
                      <input type="" name="pengurangan_cuti" id="pengurangan_cuti" class="form-control" style="width: 60px">
                  </div>
                  <button class="btn btn-primary btn-xs" style="width: 60px">Submit</button>
                  <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" style="width: 60px"><i class=" fa fa-times"></i>&nbspClose</button>
              </form>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('/setting_total_cuti')}}">
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
              <form method="POST" action="{{url('/decline_cuti')}}" id="reason_decline" name="reason_decline">
                @csrf
              <input type="" name="id_cuti_decline" id="id_cuti_decline" hidden="">
              <div class="form-group">
                <label for="sow">Decline reason</label>
                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
              </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                  <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Decline</button>
                </div>
            </form>
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

  </section>

@endsection

@section('script')
<script src="{{asset('js/fullcalendar.js')}}"></script>
<script type='text/javascript' src="{{asset('js/gcal.js')}}"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>   
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript">

  @if (Auth::User()->cuti != NULL) {
    $(document).ready(function() {
      $.ajax({
            type:"GET",
            url:"getCutiAuth",
            success: function(result){
                swal({
                  title: "Hello "+result[0].name+" !!!",
            text: "Cuti kamu tahun ini tersisa " + result[0].cuti + " Kamu Mau menggunakan Cuti?",
            confirmButtonColor: "#22542f",
            confirmButtonText: "OK!",
            closeOnConfirm: true,
            type:"warning"

                });
          
            },
          });
        
    });
  }
  @endif

    // $(function() {
    //   $('#calendar').fullCalendar({
    //     googleCalendarApiKey: 'AIzaSyAf8ww4lC-hR6mDPf4RA4iuhhGI2eEoEiI',
    //     events: {
    //       googleCalendarId: 'en.indonesian#holiday@group.v.calendar.google.com',
    //       className: 'gcal-event' // an option!
    //     }
    //   });
    // });

    var tables = $('#datatables').DataTable();
    var table  = $('#datatable').DataTable({
       "columnDefs":[
            {"width": "30%", "targets":0},
            {"width": "10%", "targets":2},
            {"width": "10%", "targets":3},
            {"width": "10%", "targets":4},
           ],
        "order": [[ "5", "desc" ]],
    });

    $(".users").select2();

    
    $('#date_start').datepicker({
      daysOfWeekHighlighted: [0,6],
      multidate: true,
    }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        $('#lihat_hasil').val(' ' + e.dates.length)
        var cutis = $("#sisa_cuti").text();
        var cutiss = $(".lihat_hasil").val();
        if (parseFloat(cutis) >= parseFloat(cutiss)) {
           e.preventDefault();     
           $(".btn-submit").prop('disabled', false);
           $("#tooltip").hide();
        }else if (parseFloat(cutis) < parseFloat(cutiss)) {
           $(".btn-submit").prop('disabled', true);
           $("#tooltip").show();
        }
       ;
    });


    $("#date_end").on("change",function(e){
      var start = $('#date_start').datepicker('getDate');
      var end = $('#date_end').datepicker('getDate');
      if (!start || !end) return;
      var days = (end - start) / 1000 / 60 / 60 / 24;
      $('#lihat_hasil').val(e.dates.length);
    });


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
              table = table + '<td>' + '<input type="checkbox" checked name="check_date[]"' +'</td>';
              table = table + '<td hidden>' + value.date_off +'</td>';
              table = table + '<td>' + moment(value.date_off).format('LL'); +'</td>';
              table = table + '</tr>';
            });

            $('#tanggal_cuti').append(table);

          }
        });

        $("#detail_cuti").modal("show");
     });
    
    $(document).on('click',"button[id^='btn-edit']",function(e) {

      console.log('coba');

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

              console.log(array);

              $("#Dates").datepicker({format: 'yyyy-mm-dd',daysOfWeekHighlighted: [0,6],multidate: true,}).datepicker('setDate', array);
            });

            $('#tanggal_cuti_coba').append(table);

        $("#modalCuti_edit").modal("show");
        }
      });


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
      $("#id_cuti_decline").val(id_cuti);
      $("#keterangan").val(decline_reason);
      $("#keterangan_decline").val(decline_reason);
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

    $('input[name="dates"]').daterangepicker({

    }, function(start, end, label) {

      // table.draw();

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
                    "order": [[ "5", "desc" ]],
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
                  table = table + '<td>' +'<label class="status-win">Approved</label>'+ '</td>';
                  table = table + '<td>' +' '+ '</td>';
                  
                  table = table + '</tr>';

                });
                $('#report').append(table);
                
              },
          });

          $("#division_cuti").change(function(){
            $.ajax({
              type:"GET",
              url:"/getfilterCutiByDateDiv",
              data:{
                division:this.value,
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
              "order": [[ "5", "desc" ]],
              "lengthChange": false,
              "paging": false,
          });
                $('#report').empty();

                var table = "";

                $.each(result, function(key, value){
                  table = table + '<tr>';
                  table = table + '<td>' +value.name+ '</td>';
                  table = table + '<td>' +value.id_division+ '</td>';
                  table = table + '<td>' +'<button name="date_off" id="date_off" class="date_off" value="'+value.id_cuti+'"  style="outline: none;background-color: transparent;background-repeat:no-repeat;border: none;">'+ value.days + ' Hari' + '<i class="glyphicon glyphicon-zoom-in" style="padding-left: 5px"/>'+'</button>'+'</td>';
                  table = table + '<td>' +value.date_req+ '</td>';
                  table = table + '<td>' +'<label class="status-win">Approved</label>'+ '</td>';
                  table = table + '<td>' +' '+ '</td>';
                  
                  table = table + '</tr>';

                });
                $('#report').append(table);
              },
            });
            
          });
          
        // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
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
                  "order": [[ "5", "desc" ]],
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
                      table = table + '<td>' +'<label class="status-win">Approved</label>'+ '</td>';
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
          "order": [[ "5", "desc" ]],
          "lengthChange": true,
          "paging": true,
      });

        $("#division_cuti").change(function(){
          $.ajax({
              type:"GET",
              url:"/getfilterCutiByDiv",
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
                    "order": [[ "5", "desc" ]],
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
                  table = table + '<td>' +'<label class="status-win">Approved</label>'+ '</td>';
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
          "order": [[ "5", "desc" ]],
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
          url:'/detilcuti',
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
              $("#lama_kerja").val(Math.floor(value.date_of_entrys / 365) + ' Tahun ' + value.date_of_entrys % 365 +' Hari');
              $("#current_cuti").val(value.cuti);
              $("#tahun_masuk").val(moment(value.date_of_entry).format('ll'));
            });
          },
        });
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
            $.each(result, function(key, value){
              if (value.cuti == null) {
                $("#sisa_cuti").text(0).style.color = "#ff0000";
              }else{
                $("#sisa_cuti").text(value.cuti);
                if (value.cuti > 5) {
                  document.getElementById("sisa_cuti").style.color = "blue";
                }else{
                  document.getElementById("sisa_cuti").style.color = "#ff0000";
                }
                
              }
            });
          },
        });

        $("#modalCuti").modal("show");
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
    

  </script>
@endsection