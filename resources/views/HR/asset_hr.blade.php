@extends('template.main')
@section('tittle')
GA Asset
@endsection
@section('head_css')
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">
<link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.css">
<link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<style type="text/css">
  .select2{
    width: 100%!important;
  }

  #tbRequestModal tr td{
    text-align: center;
  }

  .nav-tabs .badge{
      position: absolute;
      top: -10px;
      right: -10px;
      background: red;
  }

  .padding{
    padding: 10px
  }

  .active-tab{
    display: block;
  }

  .no-active-tab{
    display: none;
  }

  td>.truncate{
    word-break:break-all;
    white-space: normal; 
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

/*  @media screen and (max-width: 768px) {
    .btn-action-asset{
      float: left!important;
    }
  }*/
/*
  #request_table_wrapper {
    margin: 0;
    padding: 0;
  }
*/
  .copy-icon {
      cursor: pointer;
  }

  .copy-icon:hover {
      cursor: pointer;
      background-color: white!important;
      border: 1px solid #00c0ef!important;
      color: #00c0ef !important;
  }

  .copy-icon:focus {
    background-color: slategrey;
  }

  textarea{
    overflow: hidden;
    overflow-y: scroll;
  }

</style>
@endsection
@section('content')
<section class="content-header">
  <h1>General Affair - Asset</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">General Affair - Asset</li>
    </ol>
</section>
  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
    @endif

    @if (session('danger'))
      <div class="alert alert-danger" id="alert">
          {{ session('danger') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success" id="alert">
        {{ session('success') }}
      </div>
    @endif

    @if (session('alert'))
      <div class="alert alert-success" id="alert">
        {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-body">
        <div class="nav-tabs-custom" id="asset" role="tabpanel">
          <ul class="nav nav-tabs" id="myTab" role="tablist"> 
            <li class="nav-item" style="display: none;">
              <a class="nav-link" id="list_asset" data-toggle="tab" href="#asset_list" role="tab" aria-controls="current" aria-selected="false">List Asset</a>
            </li>
     <!--        <li class="nav-item">
              <a class="nav-link" id="kategori_list" style="display: none;" data-toggle="tab" href="#kategori_asset" role="tab" aria-controls="kategori" aria-selected="false">Kategori</a>
            </li> -->
            <li class="nav-item active">
              <a class="nav-link" id="request_list" data-toggle="tab" href="#request_asset" role="tab" aria-controls="asset" aria-selected="false">Request</a>
            </li>
              <!--     <button class="btn btn-sm btn-primary pull-right" style="display: none;width: 120px;margin-left: 5px;" id="addEvents"><i class="fa fa-plus"></i>&nbsp Calendar event</button>  --> 
              <!-- <a href="{{action('AssetHRController@export')}}" id="btnExport" class="btn btn-info btn-sm pull-right" style="margin-right: 5px;display: none;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a> -->
              <!-- <div class="box-body">
                <form action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="file" name="file" class="form-control">
                  <br>
                  <button class="btn btn-warning btn-sm pull-right">Import Data</button>
                </form>
              </div> -->
              <li class="nav-item" style="display: none;">
                <a class="nav-link" id="occ_asset" data-toggle="tab" href="#current_asset" role="tab" aria-controls="occurance" aria-selected="false"> Occurance</a>
              </li>          
            <li class="nav-item">
              <a class="nav-link" id="history_asset" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
            </li>
            <div class="form-group btn-action-asset" style="float: right;">
              <!-- <button class="btn btn-sm btn-success" data-toggle="modal" id="btnAdd" style="display: none;"><i class="fa fa-plus"> </i>&nbsp Asset</button> -->
           <!--    <button onclick="exportExcel()" id="btnExport" class="btn btn-info btn-sm" style="margin-right: 5px;display: none;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</button>
              <button id="btnImport" onclick="importData()" class="btn btn-warning btn-sm" style="margin-right: 5px;"><i class="fa fa-cloud-upload"></i>&nbsp&nbspImport</button> -->
              <button class="btn btn-sm btn-success" style="width: 100px;margin-right: 5px;display: none;" id="btnRequest">Request Asset</button>
              <button class="btn btn-sm btn-info" style="width: 100px;margin-right: 5px;display: none;" id="btnPinjam">Borrow Asset</button>
            </div>
          </ul>
          <div class="tab-content" id="myTabContent">           
            <div class="tab-pane" id="asset_list" role="tabpanel" aria-labelledby="home-tab">       
              <div class="table-responsive" >
                <table class="table table-bordered table-striped" id="data_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th width="5%">Code</th>
                      <th width="10%">Product Name</th>
                      <th width="20%">Specification</th>
                      <th width="15%">Latest person</th>
                      <th width="5%">Status</th>
                      <th width="5%">Location</th>
                      <th width="5%" style="text-align: center;">Action</th>
                      <th width="5%" style="text-align: center;">Action</th>                      
                      <th width=""></th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  @foreach($asset as $item => $value)
                    @foreach($value as $data)
                      <tr>
                          <td width="5%">{{$data->code_name}}<input type="" name="id_barang_update" hidden></td>
                          <td width="10%">{{$data->nama_barang}}</td>
                          <td width="20%">{{$data->description}} - {{$data->serial_number}}</td>
                          <td width="15%">
                              @foreach(explode(',', $data->name) as $key => $latest_pinjam) 
                                {{$latest_pinjam}}
                              @endforeach<br>
                              @if($data->date_of_entrys > 365)
                                <label class="label label-info"><litle>Masa kerja : {{floor($data->date_of_entrys / 365)}} Tahun {{floor($data->date_of_entrys % 365 / 30)}} Bulan </litle></label>
                                <label class="label label-warning" style="margin-left: 5px;"><litle>Status karyawan : {{$data->status_kerja}}</litle></label>
                              @elseif($data->date_of_entrys > 31)
                                <label class="label label-info"><litle>Masa kerja : {{floor($data->date_of_entrys / 30)}} Bulan</litle></label>
                                <label class="label label-warning" style="margin-left: 5px;"><litle>Status karyawan : {{$data->status_kerja}}</litle></label>
                              @else
                                <label class="label label-info"><litle>Masa kerja : {{floor($data->date_of_entrys)}} Hari </litle></label>
                                <label class="label label-warning" style="margin-left: 5px;"><litle>Status karyawan : {{$data->status_kerja}}</litle></label>
                              @endif
                          </td>
                          <td width="5%">
                            @if($data->status == "UNAVAILABLE")
                            <span class="label label-default">UNAVAILABLE</span>
                            @elseif($data->status == "AVAILABLE")
                            <span class="label label-info">AVAILABLE</span>
                            @elseif($data->status == "SERVICE")
                            <span class="label label-primary">SERVICE</span>
                            @elseif($data->status == "RUSAK")
                            <span class="label label-danger">RUSAK</span>
                            @elseif($data->status == "PENDING")
                            <span class="label label-warning">PENDING</span>
                            @endif
                          </td>
                          <td width="5%">{{$data->lokasi}}</td>
                          <td width="5%">                      
                            @if($data->status == "UNAVAILABLE")
                            <button class="btn btn-xs btn-default btn-pengembalian" value="{{$data->id_barang}}" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning barang_asset_edit" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" value="{{$data->id_barang}}"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                            <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                            @elseif($data->status == "AVAILABLE")                      
                            <button class="btn btn-xs btn-success btn-peminjaman" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning barang_asset_edit" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="barang_asset_edit" value="{{$data->id_barang}}"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                            <button class="btn btn-xs btn-danger btn-hapus" onclick="hapus('{{$data->id_barang}}','{{$data->nama_barang}}')" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                            @elseif($data->status == "PENDING" || $data->status == "RUSAK" || $data->status == "SERVICE")
                            <button class="btn btn-xs btn-default" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                            <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                            @else
                            <button class="btn btn-xs btn-default" disabled  style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                            <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                            @endif                      
                            <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-info" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                          </td>
                          <td width="5%" style="text-align: center;">
                            @if($data->status == "UNAVAILABLE" || $data->status == "PENDING" || $data->status == "RUSAK" || $data->status == "SERVICE")
                            <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-info" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                            @else                      
                            <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-info" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                            @endif
                          </td>
                          <td>0</td>
                      </tr>
                    @endforeach
                  @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="kategori_asset">
              <div class="row">
                <div class="col-lg-12 col-xs-12">
                  <div class="table-responsive" style="margin-top: 15px">
                    <table class="table table-bordered nowrap DataTable" id="kategori_table" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th width="5">No</th>
                          <th>Code</th>
                          <th>Category</th>
                          <th width="5">Qty</th>
                          <th width="5">Action</th>
                        </tr>
                      </thead>
                      <tbody id="products-list" name="products-list">
                        <?php $no = 1?>
                        @foreach($kategori_asset as $data)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>{{$data->code_kat}}</td>
                            <td>{{$data->kategori}}</td>
                            <td>{{$data->qty_kat}}</td>
                            <td>
                              <button class="btn btn-xs btn-warning" onclick="editKategori('{{$data->code_kat}}','{{$data->kategori}}','{{$data->id}}','edit')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Update" data-placement="bottom"><i class="fa fa-edit"></i></button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="tab-pane active" id="request_asset">
              <div class="table-responsive">
                <table class="table table-bordered requestTable" id="request_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th >Request By</th>
                      <th >Request Date</th>
                      <th >Name</th>
                      <th >Category</th>
                      <th >Merk</th>
                      <th >Description</th>
                      <th >Used For</th>
                      <th >Duration</th>
                      <th >Reason</th>
                      <th width="5%">Status</th>  
                      <th >Action</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>
                    @foreach($current_request as $datas)
                      <tr>
                        <td>{{$datas->name_requestor}}</td>
                        <td>{{$datas->created_at}}</td>                        
                        <td>{{$datas->nama}}</td>
                        <td>{{$datas->kategori}}</td>
                        <td>{{$datas->merk}}</td>
                        <td>
                          @if(str_contains($datas->link, 'https://'))
                            <span class="label label-info copy-icon" onclick="copyLinkUrl('{{$datas->link}}')">Salin</span><span class="link_data" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="{{$datas->link}}">{{str_limit($datas->link,25)}}</span>
                          @else
                            <span class="link_data" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="{{$datas->link}}">{{str_limit($datas->link,25)}}</span>
                          @endif
                        </td>  
                        <td>{{$datas->used_for}}</td>
                        <td>{{$datas->duration}}</td> 
                        <td>{{$datas->reason}}</td>                                                                       
                        <td width="5%">
                          @if($datas->status == 'REQUEST' || $datas->status == 'ON PROGRESS')
                          <label class="label label-info">{{$datas->status}}</label>
                          @else
                          <label class="label label-warning">{{$datas->status}}</label>
                          @endif
                        </td>
                        <td>
                          @if($datas->status == 'ON PROGRESS')
                            <button class="btn btn-primary btn-sm"  onclick="requestAssetAccept('{{$datas->id_request}}','ACCEPT')">Accept</button>
                            <button class="btn btn-danger btn-sm"  onclick="requestAssetAccept('{{$datas->id_request}}','REJECT')">Reject</button>
                          @else
                            <button class="btn btn-warning btn-sm" onclick="requestAssetAccept('{{$datas->id_request}}','ACCEPT')">Update</button>
                          @endif  
                        </td>
                        <td>
                          @if($datas->status == 'PENDING')
                            <button class="btn btn-warning btn-sm" onclick="requestAssetAccept('{{$datas->id_request}}','ACCEPT')">Update</button>
                          @else
                            @if(App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','Financial Director')->exists() || App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','President Director')->exists())
                              N/A
                            @elseif($datas->name_requestor != Auth::User()->name)
                              @if($datas->status == "ON PROGRESS")
                                N/A
                              @else
                                <button class="btn btn-primary btn-sm"  onclick="requestAssetAccept('{{$datas->id_request}}','ACCEPT')">Accept</button>
                                <button class="btn btn-danger btn-sm"  onclick="requestAssetAccept('{{$datas->id_request}}','REJECT')">Reject</button>
                              @endif
                            @else
                              N/A
                            @endif
                          @endif  
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="current_asset" role="tabpanel" aria-labelledby="current">
              <div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered collapsed" id="datatable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th width="5%">Code</th>
                      <th width="20%">Name</th>
                      <th width="20%">Specification</th>
                      <th width="40%">Note</th>
                      <th width="5%">Status</th>
                      <th width="5%">Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>                  
                    @foreach($pinjam_request as $data)
                      <tr>
                        <td width="5%">{{$data->no_transac}}</td>
                        <td width="5%"> - </td>
                        <td width="20%">{{$data->note}} - {{$data->serial_number}}</td>
                        <td width="20%">{!! nl2br($data->keterangan) !!}</td>
                        <td width="40%"> - </td> 
                        <td width="5%"><label class="label label-warning">PENDING</label></td>                     
                        <td width="5%">
                          <button class="btn btn-xs btn-info" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" onclick="btnEditRequestAsset('{{$data->id_transaction}}','pinjam')"><i class="fa fa-edit" style="color: white" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    @endforeach
                    @foreach($current_request as $datas)
                      <tr>
                        <td width="5%" >{{$datas->id_request}}</td>
                        <td width="5%" >{{$datas->code_kat}}</td>
                        <td width="20%" >{{$datas->nama}}</td>
                        <td width="20%" >{{$datas->merk}}</td>
                        <td width="40%" >
                          <div class="truncate">
                             {{$datas->link}}
                          </div>
                        </td> 
                        <td width="5%" >
                          @if($datas->status == 'REQUEST')
                          <label class="label label-info">{{$datas->status}}</label>
                          @else
                          <label class="label label-warning">{{$datas->status}}</label>                        
                          @endif
                        </td>                     
                        <td width="5%">
                          <button class="btn btn-xs btn-info" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" onclick="btnEditRequestAsset('{{$datas->id_request}}','request')"><i class="fa fa-edit" style="color: white" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    @endforeach
                    @foreach($current_borrowed as $data)
                      <tr>
                        <td  width="5%">{{$data->no_transac}}</td>
                        <td  width="5%">{{$data->code_name}}</td>
                        <td width="20%">{{$data->nama_barang}}</td>
                        <td width="20%">{{$data->description}} - {{$data->serial_number}}</td>
                        <td width="40%">{{$data->keterangan}}</td> 
                        <td  width="5%"><label class="label label-success">BORROWING</label></td>                     
                        <td  width="5%">
                          <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset_transac" value="{{$data->id_transaction}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="history" role="tabpanel">
            	<div class="table-responsive">
                <table class="table table-bordered DataTable" id="history_table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th >Request By</th>
                      <th >Request Date</th>
                      <th >Approve Date</th>
                      <th >Name</th>
                      <th >Category</th>
                      <th >Merk</th>
                      <th >Description</th>
                      <th >Used For</th>
                      <th >Duration</th>
                      <th >Reason</th>
                      <th width="5%">Status</th>  
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>
                    @foreach($history_request as $datas)
                      <tr>
                        <td>{{$datas->name_requestor}}</td>
                        <td>{{$datas->created_at}}</td> 
                        <td>{{$datas->updated_at}}</td>                        
                        <td>{{$datas->nama}}</td>
                        <td>{{$datas->kategori}}</td>
                        <td>{{$datas->merk}}</td>
                        <td>
                          @if(str_contains($datas->link, 'https://'))
                            <span class="label label-info copy-icon" onclick="copyLinkUrl('{{$datas->link}}')">Salin</span><span class="link_data" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="{{$datas->link}}">{{str_limit($datas->link,25)}}</span>
                          @else
                            <span class="link_data" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="{{$datas->link}}">{{str_limit($datas->link,25)}}</span>
                          @endif
                        </td>  
                        <td>{{$datas->used_for}}</td>
                        <td>{{$datas->duration}}</td> 
                        <td>{{$datas->reason}}</td>                                                                       
                        <td width="5%">
                          @if($datas->status == 'ACCEPT')
                          <label class="label label-success">{{$datas->status}}</label>
                          @elseif($datas->status == 'REJECT')
                          <label class="label label-danger">{{$datas->status}}</label>
                          @else
                          <label class="label label-warning">{{$datas->status}}</label>
                          @endif
                        </td>
                        <td>
                          <button class="btn btn-primary btn-sm"  onclick="requestAssetAccept('{{$datas->id_request}}','DETAIL')">Detail</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--add asset-->
  <div class="modal fade" id="add_asset" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">        
            <div class="modal-header">
              <h4 class="modal-title">Add Asset HR/GA</h4>
            </div>
            <div class="modal-body">
              <!-- <form method="POST" action="{{url('store_asset_hr')}}" id="modal_add_asset" name="modalProgress">           -->
                @csrf
                <div class="tab active-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Asset Code</label>
                        <input name="kode_asset" id="kode_asset" class="form-control hidden" value="{{$nomor}}" hidden>
                        <input name="asset_code" id="asset_code" class="d-block form-control" value="{{$nomor}}" readonly>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Category</label>
                        <select class="d-block form-control category_asset" id="category_asset" name="category_asset" required>
                        </select>
                        <input type="text" name="category_id" id="category_id" hidden>
                        <input type="text" name="category_id_req" id="category_id_req" hidden>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Company</label>
                        <select class="form-control" id="company_asset" name="company_asset" required>
                          <option value="">Select Company</option>
                          <option value="SIP">PT. SIP</option>
                          <option value="MSP">PT. MSP</option>
                        </select>
                      </div>

                      <div class="col-md-6 form-group">
                        <label>Merk</label>
                        <input type="" class="form-control" name="merk_barang" id="merk_barang" placeholder="Input Merk">
                      </div>
                    </div> 
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Name</label>
                        <input name="nama_barang" id="nama_barang" placeholder="Input Product Name" class="form-control" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Serial Number</label>
                        <input name="asset_sn" id="asset_sn" class="form-control" placeholder="Input Serial Number">
                      </div>                   
                    </div>
                  </div>
                        
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Type</label>
                        <input name="type_asset" id="type_asset" placeholder="Input Type Asset" class="form-control">
                      </div>
                      
                      <div class="col-md-6 form-group">
                        <label for="sow">Note</label>
                        <textarea name="note" id="note" placeholder="Input Note" class="form-control" ></textarea>            
                      </div>            
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Purchase Price</label>
                        <input name="purchase_price" id="purchase_price" class="form-control money" placeholder="Input Price Asset" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Specification</label>
                        <textarea name="keterangan" id="keterangan" placeholder="Input Specification" class="form-control" required=""></textarea>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Date of Purchase</label>
                        <input type="text" name="asset_date" id="asset_date" placeholder="Input Date" class="form-control" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Location</label>
                        <textarea name="lokasi" id="lokasi" class="form-control" placeholder="Input Location"></textarea>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group" style="display: none;" id="peminjams">
                        <label for="sow">Request By</label>
                        <input type="text" name="requestBy" id="requestBy" class="form-control" readonly="">
                        <input type="text" name="requestNik" id="requestNik" class="form-control hidden">
                        <input type="text" name="id_requestNewAsset" id="id_requestNewAsset" class="form-control hidden">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="tab" style="display: none;">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="" name="" class="form-control">
                  </div>

                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control"></textarea>
                  </div>

                  <div class="form-group">
                    <label>Date & Time</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="eventsCalendar">
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Guest</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-user-plus"></i>
                      </div>
                      <select class="form-control" id="guestEmail" name="guestEmail" multiple="multiple">
                        <option value="ladinarnanda@gmail.com">Ladinar Nanda Aprilia</option>
                        <option value="faiqoh11.fa@gmail.com">faiqoh</option>
                      </select>
                    </div>
                  </div>
                </div> -->                             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>                       
                <button type="submit" class="btn btn-sm btn-success" id="btnSubmit"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
          <!-- </form> -->
        </div>
      </div>
  </div>

  <div class="modal fade" id="modalCalender">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Events Calender</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Date & Time</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
              <input type="text" class="form-control pull-right" id="eventsCalendar">
            </div>
          </div>
          
          <div class="form-group">
            <label>Title</label>
            <input type="" name="summaryCal" class="form-control" id="summaryCal">
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" id="descCal" name="descCal"></textarea>
          </div>        

          <div class="form-group">
            <label>Guest</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user-plus"></i>
              </div>
              <select class="form-control" id="guestEmail" name="guestEmail" multiple="multiple">
                <option value="ladinarnanda@gmail.com">Ladinar Nanda Aprilia</option>
                <option value="faiqoh11.fa@gmail.com">faiqoh</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Group calendar</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <select class="form-control" id="groupCalendar" name="groupCalendar">
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            <button type="button" class="btn btn-sm btn-info" id="submitCalendar"><i class="fa fa-check"></i>&nbsp Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!--edit asset-->

  <!--edit asset-->
  <div class="modal fade" id="modaledit" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Update Asset HR/GA</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('edit_asset')}}" id="modal_update" name="modalProgress">
              @csrf
            <input type="" name="id_barang_asset_edit" id="id_barang_asset_edit" hidden>
            <div class="form-group">
              <label for="sow">Name</label>
              <input class="form-control" type="text" name="nama_barang_asset_edit" id="nama_barang_asset_edit" readonly placeholder="asset name">
            </div>

            <div class="form-group">
              <label for="sow">Specifiation</label>
              <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required="" placeholder="specification"></textarea>
            </div>

            <div class="form-group">
              <label for="sow">Serial Number</label>
              <input class="form-control" type="text" name="asset_sn_edit" id="asset_sn_edit" readonly placeholder="serial number">
            </div>

            <div class="form-group">
              <label for="sow">Status</label>
              <select id="select-status" name="select-status">
                <option value="">Select Status</option>
                <option value="AVAILABLE">AVAILABLE</option>              
                <option value="UNAVAILABLE">UNAVAILABLE</option>
                <option value="RUSAK">RUSAK</option>
                <option value="SERVICE">SERVICE</option>              
              </select>
            </div>

            <div class="form-group">
              <label for="sow">Location</label>
              <textarea name="lokasi_edit" id="lokasi_edit" class="form-control" required="" placeholder="input location"></textarea>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-sm btn-warning"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="peminjaman" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Request Asset</h3>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('peminjaman_hr')}}" id="modal_peminjaman" name="modalProgress">
            	@csrf
            <input type="text" name="id_barang" id="id_barang" hidden>
            <div class="form-group">
              <label>Nama Peminjam</label>
              <select name="users" id="users" class="form-control" style="width: 270px;" required >
                <option>Select Name</option>
                @foreach($users as $user)
                  <option value="{{$user->nik}}">{{$user->name}}</option>
                @endforeach
              </select>
            </div>              

            <div class="form-group">
              <label>Specification</label>
              <textarea class="form-control" name="description" placeholder="input specification of asset"></textarea>
            </div>          

            <div class="form-group">
              <label>Used for</label>
              <textarea class="form-control" name="keperluan" placeholder="input used for, location"></textarea>
            </div>

      <!--       <div class="form-group">
              <label>Location</label>
              <textarea class="form-control" name="lokasi" placeholder="input location"></textarea>
            </div> -->

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
            </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="peminjaman_request" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Request Asset</h3>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('requestPeminjaman')}}">
              @csrf
            <div class="form-group">
              <label>Category</label>
              <select class="form-control" id="category_pinjam" name="category_pinjam" data-rowid="1" required> <input id="cat_pinjam_id" name="cat_pinjam_id" hidden></select>
            </div>                

            <div class="form-group">
              <label>Specification</label>
              <textarea class="form-control" name="description" placeholder="input specification of asset"></textarea>
            </div>          

            <div class="form-group">
              <label>Used for</label>
              <textarea class="form-control" name="keperluan" placeholder="input used for, location"></textarea>
            </div>

      <!--       <div class="form-group">
              <label>Location</label>
              <textarea class="form-control" name="lokasi" placeholder="input location"></textarea>
            </div> -->

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
            </form>
          </div>
        </div>
      </div>
  </div>

  <!--tambah request asset-->
  <div class="modal fade" id="requestAsset" role="dialog">
      <div class="modal-dialog modal-lg" style="width:950px">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Request Asset</h4>           
          </div>
          <div class="modal-body">
            <form method="POST" id="formRequestAsset" action="{{url('/storeRequestAsset')}}" enctype="multipart/form-data">
                @csrf
                  <!-- <div class="table-responsive" style="overflow-x: scroll;width: 100%;"> -->
                  <!-- <table class="table" id="tbRequestModal" style="width: 100%;">
                    <thead>
                      <tr>
                        <th width="20%">Name</th>
                        <th width="20%">Category</th>
                        <th width="20%">Merk</th>
                        <th width="20%">Description</th>
                        <th width="20%">Used for</th>
                        <th width="20%">Duration</th>
                        <th width="20%">Reason</th>
                        <th width="20%">File</th>
                        <th><button class="btn btn-xs btn-success" type="button" id="btnAddRowReq" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" ><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="tbody_request">
                      <tr>
                        <td>
                          <input name="items[0][nama_barang_request]" id="nama_barang_request" class="form-control" placeholder="Input Product Name" style="width:150px" required>
                        </td>
                        <td>
                          <input id="cat_req_id" data-rowid="0" hidden><select class="form-control" id="category_asset_request" name="items[0][category_asset_request]" data-rowid="0" required style="width:150px"></select>
                        </td>
                        <td>
                          <input name="items[0][merk_barang_request]" id="merk_barang_request" class="form-control" placeholder="Input Merk" style="width:150px">
                        </td>
                        <td>
                          <textarea class="form-control" id="link_barang_request" name="items[0][link_barang_request]" placeholder="Input Specification,*Suggest link for buy" required style="width:200px"></textarea>
                        </td>                  
                        <td>
                          <select name="items[0][keperluan_barang_request]" id="keperluan_barang_request" class="form-control" required style="width:150px" data-rowid="0"><option></option></select>
                        </td>
                        <td>
                          <div class="divDuration" data-rowid="0">
                            <select name="items[0][duration_barang_request]" id="duration_barang_request" class="form-control" required style="width:200px" data-rowid="0"></select>
                          </div>
                          <div class="divDurationDate" data-rowid="0" style="display:none;">
                            <input type="text" class="form-control" name="items[0][duration_date_range]" id="duration_date_range" data-rowid="0" style="width:200px;">
                          </div>
                        </td>
                        <td>
                          <textarea name="items[0][reason_barang_request]" id="reason_barang_request" class="form-control" placeholder="Reason" required style="width:250px"></textarea>
                        </td>  
                        <td>
                          <input id="file_barang_request" name="items[0][file_barang_request]" type="file" multiple="multiple" class="form-control" style="width: 150px;">
                        </td> 
                        <td>
                          <button class="btn btn-xs btn-danger remove" type="button" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;float: right;" ><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
 -->
                <!-- </div> -->
                
                <div style="margin-bottom: 20px;" class="divItem">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Name</label>
                        <input name="items[0][nama_barang_request]" id="nama_barang_request" class="form-control" placeholder="Input Product Name" style="width:100%!important" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Category</label>
                        <input id="cat_req_id" data-rowid="0" hidden><select class="form-control" id="category_asset_request" name="items[0][category_asset_request]" data-rowid="0" required style="width:100%!important"></select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label>Merk</label>
                        <input name="items[0][merk_barang_request]" id="merk_barang_request" class="form-control" placeholder="Input Merk" style="width:100%!important">
                    </div>
                    <div class="col-md-3">
                      <label>Used for</label>
                      <select name="items[0][keperluan_barang_request]" id="keperluan_barang_request" class="form-control keperluan_barang_request" required style="width:100%!important" data-rowid="0"><option></option></select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Duration</label>
                      <div class="divDuration" data-rowid="0">
                        <select name="items[0][duration_barang_request]" id="duration_barang_request" class="form-control" required style="width:100%!important" data-rowid="0"></select>
                      </div>
                      <div class="divDurationDate" data-rowid="0" style="display:none;">
                        <input type="text" class="form-control" name="items[0][duration_date_range]" id="duration_date_range" data-rowid="0" style="width:100%!important">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label>Description</label>
                        <textarea class="form-control" id="link_barang_request" name="items[0][link_barang_request]" placeholder="Input Specification,*Suggest link for buy" required style="width:100%!important"></textarea>
                    </div>
                    <div class="col-md-3">
                      <label>Reason</label>
                      <textarea name="items[0][reason_barang_request]" id="reason_barang_request" class="form-control" placeholder="Reason" required style="width:100%!important"></textarea>
                    </div>
                    <div class="col-md-3">
                      <label>File</label>
                      <input id="file_barang_request" name="items[0][file_barang_request]" type="file" multiple="multiple" class="form-control" style="width:100%!important">
                    </div>
                  </div>
                </div>

                <div style="text-align: center;margin-bottom: 10px;" id="divBtnAddItem">
                  <button class="btn btn-sm bg-purple" type="button" id="btnAddRowReq" data-toggle="tooltip"><i class="fa fa-plus"></i> Add Item</button>
                  <button class="btn btn-sm btn-danger remove" type="button" data-toggle="tooltip" style="width:35px;height:30px;outline: none;margin-left: 5px;" disabled><i class="fa fa-trash-o"></i></button>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                    <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-check" id="submitReq"></i>&nbsp Submit</button>
                </div>
            </form>
          </div>
        </div>
      </div>
  </div>

  <!--edit req asset-->
  <div class="modal fade" id="requestAssetEdit" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail Request Asset</h4>           
          </div>
          <div class="modal-body">
            @csrf
            <div class="form-group">
              <div id="tbody_requestEdit" style="font-size: 16px">
                
              </div>
              <b><p>Note</p></b>
              <textarea class="form-control" id="notes" name="notes" placeholder="input for notes"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                <button type="button" class="btn btn-sm btn-info" id="btnAddNoteReq"><i class="fa fa-check"></i>&nbsp Submit</button>
                <button type="button" class="btn btn-sm btn-danger" id="btnBatalReq"><i class="fa fa-trash"></i>&nbsp Batalkan</button>
            </div>
          </div>
        </div>
      </div>
  </div>


  <div class="modal fade" id="importAsset" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Import Asset</h4>           
          </div>
          <div class="modal-body">
            <form action="{{ url('importAssetHR') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <input type="file" name="file" class="form-control">
              <br>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning btn-sm">Import Data</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="penghapusan" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete Asset</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('penghapusan_hr')}}" name="modalProgress">
              @csrf
              <input type="text" name="id_barang" id="id_barang_hapus" hidden>
              <div class="form-group">
              <label>Are you sure to delete asset?</label>
              <input name="nama_barang" id="nama_barang_hapus" class="form-control" readonly></input>
            </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-check"></i>&nbsp Hapus</button>
              </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <!--modal accept-->
  <!-- <div class="modal fade" id="accept_modal" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-body">
            <form method="POST" action="{{url('accept_pinjam_hr')}}" id="modal_accept" name="modalProgress">
              @csrf
            <input type="text" name="id_barang_update" id="id_barang_update" hidden>
            <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
            <div class="form-group">
            	<h3 style="text-align: center;"><b>ACCEPT NOW!</b></h3>
            </div>
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman" readonly>
              <i class="" aria-hidden="true">No Peminjaman &nbsp</i>
            </div>
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="nama_peminjam" id="nama_peminjam" readonly>
              <i class="" aria-hidden="true">Nama Peminjam</i>
            </div>
            <legend></legend>
              <table>
                <input type="" name="id_transaksi" id="id_transaksi" hidden>
                <tr class="tr-header">
                  <th style="border-left: 10px; width: 270px;">Nama Barang</th>
                  <th style="border-left: 10px; width: 270px;">Jumlah Pinjam</th>
                </tr>
                <tbody id="mytable">
                  <tr>
                    <td style="margin-bottom: 75px;  width: 270px;">
                    </td>
                    <td style="margin-bottom: 75px;  width: 270px;">
                    </td>
                  </tr>
                </tbody>
              </table>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  REJECT
  <div class="modal fade" id="reject_modal" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-body">
            <form method="POST" action="{{url('reject_pinjam_hr')}}" id="modal_reject" name="modalProgress">
              @csrf
            <input type="text" name="id_barang_reject" id="id_barang_reject" hidden>
            <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
            <div class="form-group">
            	<h3 style="text-align: center;"><b>REJECT NOW!</b></h3>
            </div>
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman2" readonly>
              <i class="" aria-hidden="true">No Peminjaman &nbsp</i>
            </div>
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="nama_peminjam" id="nama_peminjam2" readonly>
              <i class="" aria-hidden="true">Nama Peminjam</i>
            </div>
            <legend></legend>
              <table>
                <input type="" name="id_transaksi" id="id_transaksi2" hidden>
                <tr class="tr-header">
                  <th style="border-left: 10px; width: 270px;">Nama Barang</th>
                  <th style="border-left: 10px; width: 270px;">Jumlah Pinjam</th>
                </tr>
                <tbody id="mytable2">
                  <tr>
                    <td style="margin-bottom: 75px;  width: 270px;">
                    </td>
                    <td style="margin-bottom: 75px;  width: 270px;">
                    </td>
                  </tr>
                </tbody>
              </table>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div> -->
    
    <!--kembali-->
  <div class="modal fade" id="kembali_modal" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-body">
            <form method="POST" action="{{url('kembali_pinjam_hr')}}" id="modal_kembali" name="modalProgress">
              @csrf
            <input type="text" name="id_barang_kembali" id="id_barang_kembali" hidden>
            <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" hidden>
            <div class="form-group">
              <h3 style="text-align: center;">Return Asset</h3>
              <table class="table table-bordered">
                <tr>
                  <th>Name</th>
                  <th>Borrowing person</th>
                  <th>Date return</th>
                  <th>Location</th>
                </tr>
                <tr>
                  <td>
                    <span id="nama_barang_kembali"></span>
                  </td>
                  <td>
                    <span id="nama_peminjam_kembali"></span>
                  </td>
                  <td>
                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" readonly class="form-control">
                  </td>
                  <td>
                    <textarea class="form-control"  type="text" name="lokasi_kembali" id="lokasi_kembali" required></textarea>
                    <!-- <input type="text" name="lokasi_kembali" id="lokasi_kembali" required class="form-control"> -->
                  </td>
                </tr>
              </table>
            </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp Submit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <!--add kategori-->
  <div class="modal fade" id="add_kategori" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Category</h4>
          </div>
          <div class="modal-body">
            <form id="modalAddKategori" name="modalAddKategori" method="POST" action="{{url('store_kategori_asset')}}" >
              @csrf
            <input type="" id="status_kategori" name="status_kategori" hidden> 
            <input type="" name="id_kategori" id="id_kategori" value="" hidden>
            <div class="form-group">
              <label for="">Code</label>
              <input type="text" name="kode_kategori" id="kode_kategori" class="form-control" maxlength="3" minlength="3" required="" style="text-transform:uppercase">
              <small>Must haves 3 character</small>
            </div>
            <div class="form-group">
              <label for="">category</label>
              <input type="text" name="kategori" id="kategori" class="form-control" required="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <!--modal info-->
  <div class="modal fade" tabindex="-1" role="dialog" id="info_all">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Information</h5>
        </div>
        <div class="modal-body" id="detailInfo">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal accept request-->
  <div class="modal fade" tabindex="-1" role="dialog" id="acceptModalPinjam">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Asset Request Detail</h4>
        </div>
        <div class="modal-body" id="">
          <div class="form-group">
            <table class="table">
              <input type="" name="id_request_input" id="id_request_input" style="display: none;">
              <tr>
                <th>Category</th>
                <td>:</td>
                <td><span id="katModal" style="overflow-wrap:break-word"></span></td>
              </tr>
              <tr>
                <th>Used for</th>
                <td>:</td>
                <td><span id="usedForModal" style="overflow-wrap:break-word"></span></td>
              </tr>
              <tr>
                <th>Description</th>
                <td>:</td>
                <td><span id="noteModal" style="overflow-wrap:break-word"></span></td>
              </tr>
            </table>
          </div>

          <div class="form-group">
            <label>Notes</label><small>(optional)</small>
            <textarea class="form-control" id="notes_accept" style="overflow-y: scroll!important;resize: none !important;"></textarea>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="resolve_notes" id="resolve_notes" checked>
                Resolve notes <small> (With checking this resolve notes, asset automate to accepted)</small>
              </label>
            </div>
          </div>

          <div class="box-container" style="max-height: 400px;display:none;">
            <div class="box-footer box-comments" style="overflow-y: scroll;max-height: 400px;">
            </div>
          </div> 
          <div class="box-footer" style="display: none;">
              <img class="img-responsive img-circle img-sm" alt="Alt Text">
              <div class="img-push">
                <div class="input-group">
                  <input type="text" class="form-control input-sm input-comment-accept-request" placeholder="Press enter to post comment">
                  <span class="input-group-addon"><a href="#" class="btn-comment-accept-request" title="Send Notes"><i class="fa fa-paper-plane"></i></a></span>
                </div>
              </div>
          </div>       	
        </div>
        <div class="modal-footer">
        	<button type="button" id="btnAcceptRequest" class="btn btn-primary">Submit</button>
          <button type="button" id="btnResolveRequest" class="btn btn-success">Resolve</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
  <!--   <script src="https://momentjs.com/downloads/moment-timezone.min.js"></script>
  <script type="text/javascript" src="https://momentjs.com/downloads/moment-timezone-with-data.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <!--fixed column-->
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.dataTables.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.dataTables.min.js"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/roman.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')  
  <script type="text/javascript">
    $(document).ready(function(){
        $("#btnAdd").attr('data-target','#add_asset') 

        // $("#submitReq").on('click',function(){ 
        //   $("#submitReq").prop("disabled",true)         
        //   $('#requestAsset').modal('hide')
        // })

        document.getElementById('formRequestAsset').addEventListener('submit', function() {
            // Disable the submit button to prevent multiple clicks
          Swal.fire({
              title: 'Please Wait..!',
              text: "It's sending..",
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false,
              customClass: {
                  popup: 'border-radius-0',
              },
              didOpen: () => {
                  Swal.showLoading()
              }
          })
          document.getElementById('submitReq').disabled = true;
        });
        
        var accesable = @json($feature_item);
        accesable.forEach(function(item,index){
          $("#" + item).show()          
        })  

        initCategory(0) 
        initKeperluan(0)

        //buat btn add asset di disable dulu
        // if (accesable.includes('btnAdd')) {
        //   var column1 = table.column(7);
        //   column1.visible(!column1.visible());

        //   if (requestTable.rows().count()) {
        //     $('#request_list').append('<span class="badge">'+ requestTable.rows().count() +'</span>')
        //     $('#request_asset').addClass('active')
        //     // $('#asset_listset').removeClass('active')        
        //     activeTab('request_asset')  
        //     $('#btnAdd').hide()
        //     $('#btnExport').hide() 
        //     $('#btnImport').hide()
        //   }else{
        //     // activeTab('asset_list') 
        //     // $('#asset_list').addClass('active')
        //     $('#request_asset').removeClass('active')
        //   }

        //   $('#myTab .nav-link').click(function(e) {
        //     // if ($(e.target).attr("id") == "list_asset") {
        //     //   $("#btnAdd").show();
        //     //   $("#btnExport").show();
        //     //   $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspAsset');
        //     //   $("#btnAdd").attr('data-target','#add_asset');
        //     //   $("#btnAdd").removeAttr('onclick');
        //     //   $("#btnAdd").attr('data-toggle','modal');
        //     //   $("#company_asset").val("")
        //     //   $("#nama_barang").val("")
        //     //   $("#asset_sn").val("")
        //     //   $("#keterangan").val("")
        //     //   $("#peminjams").hide()
        //     //   $("#category_asset").val("")
        //     //   $("#merk_barang").val("")
        //     //   $("#asset_date").val("")
        //     //   $("#keterangan").val("")
        //     //   $("#lokasi").val("")
        //     // }else if ($(e.target).attr("id") == "kategori_list") {
        //     //   $("#btnAdd").show();
        //     //   $("#btnAdd").removeAttr('data-toggle');
        //     //   $("#btnAdd").attr('onclick','addKategori()');
        //     //   $("#btnExport").show();
        //     //   $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspKategori');
        //     //   // $("#btnAdd").attr('data-target','#add_kategori');
        //     //   $("#btnAdd").attr('onclick','addKategori()');
        //     //   $("#btnAdd").removeAttr('data-toggle');
        //     //   $('#btnImport').hide();
        //     // }
        //     if ($(e.target).attr("id") == "request_list") {
        //       $("#btnAdd").hide();
        //       $("#btnExport").hide();
        //       $('#btnImport').hide();
        //     }
        //   })
        // }else{
        //   var column1 = table.column(6);
        //   column1.visible(!column1.visible());
        //   // activeTab('asset_list')
        //   // $('#asset_list').addClass('active')
        //   $('#request_asset').removeClass('active')
        //   $('#btnAdd').hide()
        //   $('#btnExport').hide() 
        //   $('#btnImport').hide();
        // }
    })

    // $('#btnImport').onclick(function({
    function importData(){
      $("#importAsset").modal('show');
    // }))
  }

    // $.ajax({
    //   type:"GET",
    //   url: "/testgetCalendarList",
    //   success:function(result){
    //     $('#groupCalendar').select2({
    //       placeholder: "Select a category",
    //       data: $.map(result.items, function (item) {
    //         return {
    //           id:item.id,
    //           text:item.summary
    //         }
    //       })
    //     })        
    //   },
    // })

    $("#company_asset").select2()
    $("#select-status").select2()
    $("#guestEmail").select2()

    // $("#eventsCalendar").daterangepicker({
    //   timePicker: true,
    //   start: moment().startOf('hour'),
    //   end: moment().startOf('hour').add(32, 'hour'),
    //   locale: {
    //     format: 'DD/MM/YYYY hh:mm A'
    //   }
    // })

    // $("#submitCalendar").click(function(){
    //   var startDate = $('#eventsCalendar').val().slice(0,19)
    //   var endDate = $('#eventsCalendar').val().slice(22,41)
    //   $.ajax({
    //     type:"GET",
    //     url: "testPostEventCalendar",
    //     data:{
    //       summary:$("#summaryCal").val(),
    //       description:$("#descCal").val(),
    //       startDateTime:moment.tz(startDate, "DD/MM/YYYY hh:mm A", "Asia/Jakarta").format(),
    //       endDateTime:moment.tz(endDate, "DD/MM/YYYY hh:mm A", "Asia/Jakarta").format(),
    //       email:$("#guestEmail").val(),
    //       group:$("#groupCalendar").val()
    //     },
    //     success:function(result){
    //       alert("event created")      
    //     },
    //   })
    // })

    $('.money').mask('000,000,000,000,000', {reverse: true});

    $("#addEvents").click(function(){
      $("#modalCalender").modal("show")

    })

    $("#category_asset").on('change',function(){
      $("#category_id").val($('#category_asset').select2('data')[0].no)
    })

    $("#category_pinjam").on('change',function(){
      $("#cat_pinjam_id").val($('#category_pinjam').select2('data')[0].text)
    })

    var url = {!! json_encode(url('/')) !!}

    function exportExcel() {
      myUrl       = url+"/exportExcelAsset"
      location.assign(myUrl)
    }

    function initCategory(i){
      // var datas_kat = [];
      // var datas_katPinjam = [];
      // $.ajax({
      //   type:"GET",
      //   url: "{{url('/getAssetCategoriHR')}}",
      //   success:function(result){
      //     var arr = result.results;        
      //       var data = {
      //         id: -1,
      //         text: 'Select Category...'
      //       };

      //       for(var x in arr)
      //         arr[x].id == 'OTH' ?
      //       arr.push(arr.splice(x,1)[0]) : 0

      //       datas_kat.push(data)
      //       $.each(arr,function(key,value){
      //         datas_kat.push(value)
      //       })

      //     $("#category_asset").select2({
      //       placeholder: "Select a category",
      //       // theme: 'bootstrap4',
      //       data: datas_kat
      //     });

      //     $("#category_asset_request[data-rowid]").select2({
      //       placeholder: "Select a category",
      //       // theme: 'bootstrap4',
      //       data: datas_kat
      //     });
      //   }
      // })  

      // $.ajax({
      //   type:"GET",
      //   url: "{{url('/getCategoryPinjam')}}",
      //   success:function(result){
      //     console.log("---ini result" + result)
      //     var arr = result.results;        
      //       var data = {
      //         id: -1,
      //         text: 'Select Category...'
      //       };

      //       for(var x in arr)
      //         arr[x].id == 'OTH' ?
      //       arr.push(arr.splice(x,1)[0]) : 0

      //       datas_katPinjam.push(data)
      //       $.each(arr,function(key,value){
      //         datas_katPinjam.push(value)
      //       })

      //     	$("#category_pinjam").select2({
      //     		placeholder: "Select a category",
      //       	// theme: 'bootstrap4',
      //       	data: datas_katPinjam
      //     	})
      //   }
      // })  

      $("#category_asset_request[data-rowid='"+ i +"']").select2({
        ajax:{
          url: '{{url("getAssetCategoriHR")}}',
          processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
              results: data
            };
          },
        },
        placeholder:"Select Category",
        dropdownPosition: 'below'
      })
    }

    function initKeperluan(i){
      var data = [
        {
          id:"Internal",
          text:"Internal",
        },
        {
          id:"External",
          text:"External",
        },
      ]

      $(".keperluan_barang_request[data-rowid="+ i +"]").val("")
      $(".keperluan_barang_request[data-rowid="+ i +"]").select2({
        placeholder:"Select keperluan",
        data:data,
        dropdownPosition: 'below'
      }).change(function(){
        if ($(this).val() == "Internal") {
          // $(".divDuration[data-rowid='"+ i +"']").show()
          $(".divDurationDate[data-rowid='"+ i +"']").hide()
          $("#duration_barang_request[data-rowid='"+ i +"']").select2({
            placeholder:"Select keperluan",
            data:[
              {
                id:"Lifetime",
                text:"Lifetime",
              },
              {
                id:"Select Date",
                text:"Select Date",
              },
            ],
            dropdownPosition: 'below'
          }).change(function(){
            if ($(this).val() == 'Select Date') {
              // $(".divDuration[data-rowid='"+ i +"']").show()
              $(".divDurationDate[data-rowid='"+ i +"']").show()
              $("#duration_date_range[data-rowid='"+ i +"']").daterangepicker({
                opens: 'center',
                ranges: {
                  'Today'       : [moment(), moment()],
                  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                  'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('year'),
                endDate: moment().endOf('year')
              })
            }else{
              $(".divDurationDate[data-rowid='"+ i +"']").hide()
            }
          })

          $('#duration_barang_request[data-rowid="'+ i +'"] option[value="Lifetime"]').prop('disabled', false);
          $('#duration_barang_request[data-rowid="'+ i +'"]').select2();
        }else{
          $("#duration_barang_request[data-rowid='"+ i +"']").select2({
            placeholder:"Select keperluan",
            data:[
              {
                id:"Lifetime",
                text:"Lifetime",
              },
              {
                id:"Select Date",
                text:"Select Date",
              },
            ],
            dropdownPosition: 'below'
          }).change(function(){
            if ($(this).val() == 'Select Date') {
              // $(".divDuration[data-rowid='"+ i +"']").show()
              $(".divDurationDate[data-rowid='"+ i +"']").show()
              $("#duration_date_range[data-rowid='"+ i +"']").daterangepicker({
                opens: 'center',
                ranges: {
                  'Today'       : [moment(), moment()],
                  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                  'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('year'),
                endDate: moment().endOf('year')
              })
            }else{
              $(".divDurationDate[data-rowid='"+ i +"']").hide()
            }
          })
          $("#duration_barang_request[data-rowid='"+ i +"']").val("Select Date").trigger("change")
          $('#duration_barang_request[data-rowid="'+ i +'"] option[value="Lifetime"]').prop('disabled', true);
          $('#duration_barang_request[data-rowid="'+ i +'"]').select2();
          // $(".divDurationDate[data-rowid='"+ i +"']").show()
          // $("#duration_date_range[data-rowid='"+ i +"']").daterangepicker({
          //   opens: 'center',
          //   ranges: {
          //     'Today'       : [moment(), moment()],
          //     'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          //     'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          //     'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          //     'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          //   },
          //   startDate: moment().startOf('year'),
          //   endDate: moment().endOf('year')
          // })
        }
      })
    }


    $(document).on('change',"select[id^='category_asset_request']",function(e) { 
      var rowid = $(this).attr("data-rowid");
      $("#cat_req_id[data-rowid='"+rowid+"']").val($("#category_asset_request[data-rowid='"+rowid+"']").select2('data')[0].no)
    })    

    var i = 1;
    $("#btnAddRowReq").click(function(){     
      ++i;  
      var append = ""
      // append =  append + '<tr id="row'+i+'">'
      // append =  append + '<td><input name="items[${i}][nama_barang_request]" data-rowid="'+i+'" id="nama_barang_request" class="form-control" placeholder="Input Product Name" required></td>'
      // append =  append + '<td><select class="form-control category_asset_request" id="category_asset_request" data-rowid="'+i+'" name="items[${i}][category_asset_request]" required></select>'
      // append =  append + '<input id="cat_req_id" name="cat_req_id[]" data-rowid="'+i+'" hidden></td>'
      // append =  append + '<td><input name="items[${i}][merk_barang_request]" id="merk_barang_request" data-rowid="'+i+'" class="form-control" placeholder="Input Merk"></td>'
      // append =  append + '<td><textarea id="link_barang_request" name="items[${i}][link_barang_request]" data-rowid="'+i+'" class="form-control" placeholder="Input Specification,*Suggest link for buy" required></textarea></td>'
      // append = append + '<td>'
      //   append = append + '<select name="items[${i}][keperluan_barang_request]" data-rowid="'+i+'" id="keperluan_barang_request" class="form-control" required style="width:150px"><option></option></select>'
      // append = append + '</td>'
      // append = append + '<td>'
      //   append = append + '<div class="divDuration" data-rowid="'+i+'"><select name="items[${i}][duration_barang_request]" data-rowid="'+i+'" id="duration_barang_request" class="form-control" required style="width:200px"></select></div>'
      //   append = append + '<div class="divDurationDate" data-rowid="'+i+'" style="display:none;"><input type="text" class="form-control" name="items[${i}][duration_date_range]" id="duration_date_range" data-rowid="'+ i +'" style="width:200px;"></div>'
      // append = append + '</td>'
      // append = append + '<td>'
      //   append = append + '<textarea name="items[${i}][reason_barang_request]" data-rowid="'+i+'" id="reason_barang_request" class="form-control" placeholder="Reason" required style="width:250px"></textarea>'
      // append = append + '</td> '
      // append = append + '<td>'
      //   append = append + '<input name="items[${i}][file_barang_request]" type="file" data-rowid="'+i+'" id="file_barang_request" class="form-control" placeholder="input file" style="width:150px">'
      // append = append + '</td> '
      // append =  append + '<td><button class="btn btn-xs btn-danger remove" data-rowid="'+i+'" type="button" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;float: right;" ><i class="fa fa-trash-o"></i></button>'
      // append =  append + '</td>'
      // append =  append + '</tr>'

      // $('#tbRequestModal > tbody:last-child').append(append);

      append = append + '<div style="margin-bottom: 20px;margin-top:20px" class="divItem">'
        append = append + '<div class="row">'
          append = append + '<div class="col-md-3">'
            append = append + '<div class="form-group">'
              append = append + '<label>Name</label>'
               append = append + '<input name="items['+ i +'][nama_barang_request]" id="nama_barang_request" class="form-control" placeholder="Input Product Name" style="width:100%!important" required>'
            append = append + '</div>'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<div class="form-group">'
              append = append + '<label>Category</label>'
              append = append + '<input id="cat_req_id" data-rowid="'+ i +'" hidden><select class="form-control" id="category_asset_request" name="items['+ i +'][category_asset_request]" data-rowid="'+ i +'" required style="width:100%!important"></select>'
              append = append + '</div>'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<label>Merk</label>'
              append = append + '<input name="items['+ i +'][merk_barang_request]" id="merk_barang_request" class="form-control" placeholder="Input Merk" style="width:100%!important">'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<label>Used for</label>'
            append = append + '<select name="items['+ i +'][keperluan_barang_request]" id="keperluan_barang_request" class="form-control keperluan_barang_request" required style="width:100%!important" data-rowid="'+ i +'"><option></option></select>'
          append = append + '</div>'
        append = append + '</div>'
        append = append + '<div class="row">'
          append = append + '<div class="col-md-3">'
            append = append + '<label>Duration</label>'
            append = append + '<div class="divDuration" data-rowid="'+ i +'">'
              append = append + '<select name="items['+ i +'][duration_barang_request]" id="duration_barang_request" class="form-control" required style="width:100%!important" data-rowid="'+ i +'"></select>'
            append = append + '</div>'
            append = append + '<div class="divDurationDate" data-rowid="'+ i +'" style="display:none;">'
              append = append + '<input type="text" class="form-control" name="items['+ i +'][duration_date_range]" id="duration_date_range" data-rowid="'+ i +'" style="width:100%!important">'
            append = append + '</div>'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<label>Description</label>'
              append = append + '<textarea class="form-control" id="link_barang_request" name="items['+ i +'][link_barang_request]" placeholder="Input Specification,*Suggest link for buy" required style="width:100%!important"></textarea>'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<label>Reason</label>'
            append = append + '<textarea name="items['+ i +'][reason_barang_request]" id="reason_barang_request" class="form-control" placeholder="Reason" required style="width:100%!important"></textarea>'
          append = append + '</div>'
          append = append + '<div class="col-md-3">'
            append = append + '<label>File</label>'
            append = append + '<input id="file_barang_request" name="items['+ i +'][file_barang_request]" type="file" multiple="multiple" class="form-control" style="width:100%!important">'
          append = append + '</div>'
        append = append + '</div>'
      append = append + '</div>'

      $(".divItem:last").append(append)

      if ($(".divItem").length > 1) {
        $(".remove").prop("disabled",false)
      }
      initCategory(i) 
      initKeperluan(i)
    })

    $(document).on('click', '.remove', function() {
      // var trIndex = $(this).closest("tr").index();
      //   if(trIndex>0) {
      //     $(this).closest("tr").remove();
      //   } else {
      //     alert("Sorry!! Can't remove first row!");
      // }
      $(".divItem:last").remove()
      if ($(".divItem").length = 1) {
        $(".remove").prop("disabled",true)
      }
    });

    var hari_libur_nasional = []
    var hari_libur_nasional_tooltip = []
    $.ajax({
      type:"GET",
      url:"https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key={{env('GOOGLE_API_KEY_APP')}}",
      success: function(result){
        $.each(result.items,function(key,value){
          hari_libur_nasional.push(moment( value.start.date).format("MM/DD/YYYY"))
          hari_libur_nasional_tooltip.push(value.summary)
        })
      }
    })     

    $('#asset_date').datepicker({
      weekStart: 1,
      daysOfWeekDisabled: "0,6",
      daysOfWeekHighlighted: [0,6],
      todayHighlight: true,
      autoclose: true,
      beforeShowDay: function(date){
        var index = hari_libur_nasional.indexOf(moment(date).format("MM/DD/YYYY"))
        if(index > 0){
          return {
            enabled: false,
            tooltip: hari_libur_nasional_tooltip[index],
            classes: 'hari_libur'
          };
        }
      }
    });

    var now = new Date();
 
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $('#tanggal_kembali').val(today);

    $(document).on('click',".barang_asset_edit",function(e) { 
        $.ajax({
            type:"GET",
            url:"{{url('/getEditAsset')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $('#id_barang_asset_edit').val(result[0].id_barang);
              $('#nama_barang_asset_edit').val(result[0].nama_barang);
              $('#asset_sn_edit').val(result[0].serial_number);
              $('#keterangan_edit').val(result[0].description);
              $('#lokasi_edit').val(result[0].lokasi);
              $("#select-status").val(result[0].status).trigger("change");  
              // $('#select-status').select2().val(result[0].status)
            },
        });

        $('#modaledit').modal('show')
    });

    $(document).on('click',"#btn_info_asset",function(e) { 
      $("#info_all").modal("show")
      var append = "";
      $.ajax({
          type:"GET",
          url:"{{url('/getEditAsset')}}",
            data:{
              id_barang:this.value,
          },
          success: function(result){
            append = append + '<h5>Detail Asset <b>' + result[0].nama_barang + '</b></h5>'
            append = append + '<table style="width:100%;border-collapse:collapse">' 
            append = append + '<tr>' 
            append = append + '<th style="width:50%;text-align:left">Umur Barang</th>' 
            if (result[0].umur_asset == null) {
              append = append + '<td th style="width:50%;text-align:left">: - </td>'  
            }else{
              append = append + '<td style="text-align:leftt;width:50%">: '+ result[0].umur_asset +' hari </td>'  
            }    
            append = append + '</tr>'   
            append = append + '<tr style="width:50%;text-align:left">' 
            append = append + '<th>Serial Number</th>'  
            append = append + '<td style="width:50%"> : </td>'                            
            append = append + '</tr>'
            append = append + '<tr>'
            if (result[0].serial_number == null) {
              append = append + '<td style="text-align:left"> - </td>'  
            }else{
              append = append + '<td style="text-align:left"> <ul style="list-style-type:square;margin-left:0px"><li>'+ result[0].serial_number +'</li></ul></td>' 
            }   
            append = append + '</tr>'
            append = append + '</table>'

            $("#detailInfo").html(append)
          },
      });
    });

    $(document).on('click',"#btn_info_asset_transac",function(e) { 
      $("#info_all").modal("show")
      var append = "";
      $.ajax({
          type:"GET",
          url:"{{url('/getDetailBorrowed')}}",
          data:{
            id_transaction:this.value,
          },
          success: function(result){
            append = append + '<h5>Transaction <b>' + result[0].no_transac + '</b></h5>'
            append = append + '<table style="width:100%;border-collapse:collapse">'
            append = append + '<tr>' 
            append = append + '<th>Tanggal Pinjam:</th>' 
            append = append + '<td>'+ result[0].tgl_peminjaman +'</td>'         
            append = append + '</tr>'
            append = append + '<tr>' 
            append = append + '<th>Tanggal Kembali:</th>'    
            if (result[0].tgl_pengembalian == null) {
              append = append + '<td> - </td>'  
            }else{
              append = append + '<td> '+ result[0].tgl_pengembalian +'</td>'  
            }                
            append = append + '</tr>'
            append = append + '<tr>' 
            append = append + '<th>Serial Number: </th>'  
            // append = append + '<td>'
            if (result[0].serial_number != null) {
              append = append + ' <td> '+ result[0].serial_number +'</td>'
            }
            append = append + '</tr>'
            append = append + '<tr>'
            append = append + '<th>Merk: </th>'  
            if (result[0].merk != null) {
              append = append + '<td>'+ result[0].merk  +'</td>'
            } 
            append = append + '</tr>'
            append = append + '<tr>'
            append = append + '<th>Keterangan: </th>'  
            if (result[0].keterangan != null) {
              append = append + '<td>'+ result[0].keterangan +'</td>' 
            }                   
            append = append + '</tr>'
            append = append + '</table>'

            $("#detailInfo").html(append)
          },
      });      
    })

    //tambahhhhh
    $("#btnRequest").on('click',function(){
      $('#requestAsset').modal('show')
    })  

    $("#btnPinjam").on('click',function(){
      $('#peminjaman_request').modal('show')
    })      

    //editDeleteRequestAsset  
    function btnEditRequestAsset(id,status){ 
      $('#requestAssetEdit').modal('show')     
      var append = ""
      $("#tbody_requestEdit").html('')
      $.ajax({
        type:"GET",
        url:"/getRequestAssetBy",
        data:{
          id:id,
          status:status
        },
        success:function(result){  
          if (status == 'pinjam') {
            append = append + '<ul><li>Category : '+ result[0].note +'</li>'
            append = append + '<li>Note : <br>'+ result[0].keterangan +'</li>'
            append = append + '<li>Date Request : '+ result[0].tgl_peminjaman +'</li>'
            append = append + '</ul>'
          }else{
            append = append + '<ul><li>Nama Barang : '+ result[0].nama +'</li>'
            append = append + '<li>Merk : '+ result[0].merk +'</li>'
            append = append + '<li>Qty : '+ result[0].qty +'</li>'
            append = append + '<li>Category : '+ result[0].kategori +'</li>'
            append = append + '<li>Specification : '+ result[0].link +'</li></ul>'
          }
          
          // append = append + '<tr><th width="25%">Nama Barang</th><th width="5%">Qty</th><th width="15%">Merk</th>/<th width="15%">Kategori</th><th width="20%">Link</th></tr>'
          // append = append + '<tr><td>'+ result[0].nama +'</td><td>'+ result[0].qty +'</td><td>'+ result[0].merk +'</td><td>' + result[0].kategori +'</td><td><a href="'+ result[0].link +'" target="_blank">'+ result[0].link +'</a></td></tr>' 

          $("#tbody_requestEdit").append(append)
          if (result[0].status == 'PENDING') {
            $("#btnBatalReq").hide()
          }else{
            $("#btnBatalReq").show()
          }

          $("#btnAddNoteReq").click(function(){
            if ($("#notes").val().length == 0) {
              alert('fill note for submit')
            }else{
              if (status == 'pinjam') {
                submitNoteReq(result[0].id_transaction,status)
              }else{
                submitNoteReq(result[0].id_request,status)
              }              
            }
          })  

          $("#btnBatalReq").click(function(){
            if (status == 'pinjam') {
              batalkanReq(result[0].id_transaction,status)
            }else{
              batalkanReq(result[0].id_request,status)
            }
          })          
        }
      })
    }

    //batalRequest
    function batalkanReq(id_request,status){
      Swal.fire({
        title: 'Batalkan Request Asset',
        text: "kamu yakin?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type:"GET",
            url:"{{url('batalkanReq')}}",
            data:{
              id_request:id_request,
              status:status
            },
            beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
                  didOpen: () => {
                      Swal.showLoading()
                  }
              })
            },  
            success: function(result){
              Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                  location.reload()
                  $("#requestAssetEdit").modal('toggle')
                }
              })
            },
          });
        }        
      })
    }

    //AddNoteReq  
    function submitNoteReq(id_request,status){
      $.ajax({
          type:"GET",
          url:"{{url('AddNoteReq')}}",
          data:{
            id_request:id_request,
            notes:$("#notes").val(),
            status:status
          },
          beforeSend:function(){
                  Swal.fire({
                      title: 'Please Wait..!',
                      text: "It's sending..",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                          popup: 'border-radius-0',
                      },
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
          success: function(result){
            Swal.showLoading()
            Swal.fire(
              'Successfully!',
              'success'
            ).then((result) => {
              if (result.value) {
                location.reload()
              }
            })
          },
      });
    }    

    //request asset baru
    function requestAssetAccept(id_request,status){
      var swalAccept,textTitle = '',append = '',notes = '';
      if (status === "ACCEPT" || status === "DETAIL") {
        if (status == "ACCEPT") {
          var titleStatus = 'Accept Request New Asset'  
          var status_notes = 'ACCEPT'
        }else if (status == "REJECT") {
          var titleStatus = 'Reject Request New Asset'
          var status_notes = "REJECT"  
        }
        $("#acceptModalPinjam").modal('show')

        $.ajax({
          type:"GET",
          url:"{{url('asset/getDetailAcceptRequest')}}",
          data:{
            id_request:id_request
          },
          success:function(result){
            var accesable = @json($feature_item);
            $(".box-comments").empty()
            $("#id_request_input").val(result[0].id_request)
            $("#katModal").text(result[0].category)
            $("#noteModal").text(result[0].reason)
            $("#usedForModal").text(result[0].used_for)

            if (result[0].notes.length > 0) {
              $("#btnAcceptRequest").hide()
              if (status == "ACCEPT" || status == "REJECT") {
                if (accesable.includes('request_list') || "{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','like','%Manager%')->exists()}}") {
                  $("#btnResolveRequest").show();
                }else{
                  $("#btnResolveRequest").hide();
                }

                $("#notes_accept").closest(".form-group").next(".box-container").next(".box-footer").show()
                $("#notes_accept").closest(".form-group").next(".box-container").next(".box-footer").find('img').attr('src','{{Auth::User()->avatar_original}}')
              }else{
                  $("#btnResolveRequest").hide();
                  $("#btnAcceptRequest").hide();
              }

              $("#notes_accept").closest(".form-group").hide()
              $("#notes_accept").closest(".form-group").next(".box-container").show()

              $.each(result[0].notes,function(key,value) {
                append = append + '<div class="box-comment" data-rowid="'+ key +'">'
                  append = append + '<img class="img-circle img-sm" src="'+ value.image +'" alt="User Image">'
                  append = append + '<div class="comment-text">'
                  append = append + '<span class="username">'
                  append = append + value.name
                  
                  const date = moment(value.created_at);
                  const formattedTime = date.format('h:mm A'); // Format time as "8:03 PM"

                  if (date.isSame(moment(), 'day')) {
                      // If the date is today
                      append = append + '<span class="text-muted pull-right">'+ formattedTime +' Today</span>'
                  } else if (date.isSame(moment().subtract(1, 'days'), 'day')) {
                      // If the date is yesterday
                      append = append + '<span class="text-muted pull-right">'+ formattedTime +' Yesterday</span>'
                  } else {
                      // For dates earlier than yesterday
                      append = append + '<span class="text-muted pull-right">'+ formattedTime + ' '+ date.format('DD/MM/YYYY') + '</span>'
                  }

                  append = append + '</span>'
                  append = append + value.notes
                  append = append + '</div>'
                append = append + '</div>'
              })

              $(".box-comments").append(append)
            }else{
              if (status == "ACCEPT" || status == "REJECT") {
                $("#notes_accept").closest(".form-group").show()
                $("#btnAcceptRequest").show()
                $("#btnResolveRequest").hide()
              }else{
                $("#notes_accept").closest(".form-group").hide()
                $("#btnAcceptRequest").hide()
                $("#btnResolveRequest").hide()
              }

              $("#notes_accept").closest(".form-group").next(".box-container").hide()
              $("#notes_accept").closest(".form-group").next(".box-container").next(".box-footer").hide()
            }
          }
        })

        console.log(status)
        console.log(status_notes)

        $("#btnAcceptRequest").click(function(){
          if ($("#notes_accept").val() != "") {
            if ($("#resolve_notes").is(":checked")) {
              status = status
              textTitle = 'Including notes, and resolve will finished all process!'
            }else{
              status = 'PENDING'
              textTitle = 'Including notes, you should resolve it later to finish all process!'
            }
            notes = $("#notes_accept").val()
          }else{
            status = 'ACCEPT'
            textTitle = 'Accepting without notes, all process will be finished!'
            notes = notes
          }
          swalAccept = Swal.fire({
            title: titleStatus,
            text: textTitle,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
          })


          swalAccept.then((result) => {
            if (result.value) {
              $.ajax({
                type:"GET",
                url:"{{url('acceptNewAsset')}}",
                data:{
                  id_request:id_request,
                  status:status,
                  reason:result.value,
                  notes:notes,
                  status_notes:status_notes
                },
                beforeSend:function(){
                  Swal.fire({
                      title: 'Please Wait..!',
                      text: "It's sending..",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                          popup: 'border-radius-0',
                      },
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
                success: function(result){
                  Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                  })
                },
              }) 
            }        
          })
        })

        $("#btnResolveRequest").click(function(){
          status = 'ACCEPT'
          textTitle = 'Resolve Notes Accept Request!'
          notes = notes

          swalAccept = Swal.fire({
            title: titleStatus,
            text: textTitle,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
          })

          swalAccept.then((result) => {
            if (result.value) {
              $.ajax({
                type:"GET",
                url:"{{url('acceptNewAsset')}}",
                data:{
                  id_request:id_request,
                  status:status,
                  reason:result.value,
                  notes:notes,
                },
                beforeSend:function(){
                  Swal.fire({
                      title: 'Please Wait..!',
                      text: "It's sending..",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                          popup: 'border-radius-0',
                      },
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
                success: function(result){
                  Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                  })
                },
              }) 
            }        
          })
        })
             
      }else{
        var titleStatus = 'Reject Request Asset Baru'
        swalAccept = Swal.fire({
            title: titleStatus,
            text: "Reason for rejecting:",
            input: 'text',
            icon: 'warning',
            showCancelButton: true        
        })

        swalAccept.then((result) => {
          if (result.value) {
            $.ajax({
              type:"GET",
              url:"{{url('acceptNewAsset')}}",
              data:{
                id_request:id_request,
                status:status,
                reason:result.value
              },
              beforeSend:function(){
                  Swal.fire({
                      title: 'Please Wait..!',
                      text: "It's sending..",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                          popup: 'border-radius-0',
                      },
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
              success: function(result){
                Swal.showLoading()
                Swal.fire(
                  'Successfully!',
                  'success'
                ).then((result) => {
                  if (result.value) {
                    location.reload()
                  }
                })
              },
            }) 
          }        
        })
      }   
    }

    //create asset baru
    function requestAssetDone(nik,id_request){
      $("#add_asset").modal('show')
      $("#modal_add_asset").attr("action", "{{url('/createNewAsset')}}");
      $.ajax({
        type:"GET",
        url:"{{url('/getRequestAssetBy')}}",
        data:{
          id:id_request,
          status:"baru"
        },
        success: function(result){
          $('#requestBy').val(result[0].name)
          $('#nama_barang').val(result[0].nama)
          $('#merk_barang').val(result[0].merk)
          $('#category_id_req').val(result[0].id)
          // $('#category_id').val(result[0].id)
          $('#requestNik').val(nik)
          $('#id_requestNewAsset').val(id_request)
          // var CatSelect = $('#category_asset');

          // var option = new Option(result[0].kategori, result[0].code_kat, true, true)
          // CatSelect.append(option).trigger('change')
          $('#category_asset').val(result[0].code_kat);
          $('#category_asset').select2().trigger('change');

          if (result[0].id_company == '1') {
            comVal = 'SIP'
            comValName = 'PT. SIP'
          }else{
            comVal = 'MSP'
            comValName = 'PT. MSP'
          }

          var ComSelect = $("#company_asset")
          $('#company_asset').val(comVal);
          $('#company_asset').select2().trigger('change');

          // var option = new Option(comValName, comVal, true, true);
          // ComSelect.append(option).trigger('change')
        },
      })

      $("#peminjams").show()
    }

    $(document).on('click',".btn-pengembalian",function(e) { 
        $.ajax({
            type:"GET",
            url:"{{url('/getPengembalian')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $('#id_transaction_kembali').val(result[0].id_transaction);
              $('#id_barang_kembali').val(result[0].id_barang);
              $('#nama_barang_kembali').text(result[0].nama_barang);
              $('#nama_peminjam_kembali').text(result[0].name);
            },
        });

        $('#kembali_modal').modal('show')
    });

    $(document).on('click',".btn-peminjaman",function(e) { 
      $('#peminjaman').modal('show')
    });

    $(document).on('click',".btn-hapus",function(e) { 

        $('#penghapusan').modal('show')
    });

    $('#users').select2();

    var table = $('#data_table').DataTable({
      pageLength: 20,
      order: [[0, 'asc']],
      columnDefs: [
        { targets: 8, "visible": false}
      ],
    });
    

    $('#datatable').DataTable({
      pageLength: 20,    
      "order": [[ 5, "desc" ]],
      // columnDefs: [
      //    { "width": "10%", "targets": 0 }
      // ],
    });

    $('#kategori_table').DataTable({
      pageLength: 20,
      // columnDefs: [
      //    { "width": "5%", "targets": 0 }
      // ], 
    })

    var requestTable = $('#request_table').DataTable({
      pageLength: 20,
      "ColumnDefs":[
        { targets: 'no-sort', orderable: false }
      ],
      "aaSorting": [],
    });

    var accesable = @json($feature_item);
    if (accesable.includes('request_list')) {
      requestTable.column(11).visible(false);
    }else{
      requestTable.column(10).visible(false);
    }

    $('#history_table').DataTable({
      pageLength: 20,
    })

    function activeTab(tab){
      $('#myTab a[href="#' + tab + '"]').tab('show');
    }
    

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    function pinjam(id_barang,nama_barang){
      $('#id_barang').val(id_barang);
      $('#nama_barang_pinjam').val(nama_barang);
    }

    function hapus(id_barang,nama_barang){
      $('#id_barang_hapus').val(id_barang);
      $('#nama_barang_hapus').val(nama_barang);
    }

    function kembali(id_transaction,id_barang,nama_barang,name){
      $('#id_transaction_kembali').val(id_transaction);
      $('#id_barang_kembali').val(id_barang);
      $('#nama_barang_kembali').text(nama_barang);
      $('#nama_peminjam_kembali').text(name);
    } 

    function edit(id_barang,nama_barang,description,serial_number){
      $('#id_barang_asset_edit').val(id_barang);
      $('#nama_barang_asset_edit').val(nama_barang);
      $('#keterangan_edit').val(description);
      $('#asset_sn_edit').val(serial_number);
    } 

    function addKategori(){
      $("#add_kategori").modal('show')

      $('#kode_kategori').val('')
      $('#kategori').val('')
      $('#id_kategori').val('')
      $('#status_kategori').val('')
    }

    function editKategori(kode,kategori,id,status){
      $("#add_kategori").modal('show')

      $('#kode_kategori').val(kode)
      $('#kategori').val(kategori)
      $('#id_kategori').val(id)
      $('#status_kategori').val(status)

    }

    $(document).on('change',"#category_asset",function(e) { 
        if ($("#category_asset").val() == "-1") {
          $("#btnSubmit").prop("disabled",true)
        }
        
        var code = $("#kode_asset").val();
        var company = $("#company_asset").val();
        var asset_date = $("#asset_date").val();

        $(document).on('change',"#company_asset",function(e) { 
          var code = $("#kode_asset").val();
          var category = $("#category_asset").val();
          var asset_date = $("#asset_date").val();

          if (asset_date == "") {
            $('#asset_code').val(code + "/" + category + "/" + this.value + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
          }else{
            $('#asset_code').val(code + "/" + category + "/" + this.value + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));
          }

          $(document).on('change',"#asset_date",function(e) {            

            var code = $("#kode_asset").val();
            var category = $("#category_asset").val();
            var company = $("#company_asset").val();
            var asset_date = $("#asset_date").val();

            $('#asset_code').val(code + "/" + category + "/" + company + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));

          });

        });

        if (company == "" && asset_date == "") {
          $('#asset_code').val(code + "/" + this.value + "/" + "-" + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
        }else if(asset_date != ""){
          $('#asset_code').val(code + "/" + this.value + "/" + company + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));
        }else{
          $('#asset_code').val(code + "/" + this.value + "/" + company + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
        }
        
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

    function copyLinkUrl(url) {
        // Get the link element
        var linkElement = url;
        console.log(url)

         if (linkElement) {
            // Use the Clipboard API for better cross-browser support
            navigator.clipboard.writeText(linkElement)
              .then(function() {
                  // Successful copy

                  // Reset the icon color after a short delay (optional)
                  setTimeout(function () {
                    alert('link copied!')
                  }, 1000);
              })
              .catch(function(err) {
                  // Handle errors
                  console.error('Unable to copy link:', err);
              });
        } else {
            // Handle the case where the link or its href property is undefined
            console.error('Link or its href property is undefined.');
        }
    }

    $(".btn-comment-accept-request").click(function(){
      $.ajax({
        type:"POST",
        url:"{{url('/asset/storeNotesAssetTransaction')}}",
        data:{
          _token:"{{ csrf_token() }}",
          notes:$('.input-comment-accept-request').val(),
          id_request:$("#id_request_input").val()
        },
        beforeSend:function(){
          Swal.fire({
              title: 'Please Wait..!',
              text: "It's sending..",
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false,
              customClass: {
                  popup: 'border-radius-0',
              },
              didOpen: () => {
                  Swal.showLoading()
              }
          })
        },
        success:function(result){
          Swal.close()
          var inc = $(".box-comments").find(".box-comment").length + 1

          var append = ""

          append = append + '<div class="box-comment" data-rowid="'+ inc +'">'
            append = append + '<img class="img-circle img-sm" src="{{Auth::User()->avatar_original}}" alt="User Image">'
            append = append + '<div class="comment-text">'
            append = append + '<span class="username">'
            append = append + '{{Auth::User()->name}}'
            const date = moment(result.created_at);
              const formattedTime = date.format('h:mm A'); // Format time as "8:03 PM"

              if (date.isSame(moment(), 'day')) {
                  // If the date is today
                  append = append + '<span class="text-muted pull-right">'+ formattedTime +' Today</span>'
              } else if (date.isSame(moment().subtract(1, 'days'), 'day')) {
                  // If the date is yesterday
                  append = append + '<span class="text-muted pull-right">'+ formattedTime +' Yesterday</span>'
              } else {
                  // For dates earlier than yesterday
                  append = append + '<span class="text-muted pull-right">'+ formattedTime + date.fromNow() + '</span>'
              }
            append = append + '</span>'
              // append = append + '<span class="pull-right" onclick="deleteComment()"><i class="fa fa-trash-o"></i></span>'
              append = append + result.notes
            append = append + '</div>'
          append = append + '</div>'

          $(".box-comments").find(".box-comment:first").before(append) 
          $('.input-comment-accept-request').val("")
        }
      })
    })

    $('.input-comment-accept-request').on('keydown', function(event) {
          // Check if the Enter key (key code 13) is pressed
        if (event.key === 'Enter' || event.which === 13) {
          $.ajax({
            type:"POST",
            url:"{{url('/asset/storeNotesAssetTransaction')}}",
            data:{
              _token:"{{ csrf_token() }}",
              notes:$('.input-comment-accept-request').val(),
              id_request:$("#id_request_input").val()
            },beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
                  didOpen: () => {
                      Swal.showLoading()
                  }
              })
            },
            success:function(result){
              Swal.close()
              var inc = $(".box-comments").find(".box-comment").length + 1
                // Perform your desired action
                var append = ""

                append = append + '<div class="box-comment" data-rowid="'+ inc +'">'
                  append = append + '<img class="img-circle img-sm" src="{{Auth::User()->avatar_original}}" alt="User Image">'
                  append = append + '<div class="comment-text">'
                  append = append + '<span class="username">'
                  append = append + '{{Auth::User()->name}}'
                  const date = moment(result.created_at);
                  const formattedTime = date.format('h:mm A'); // Format time as "8:03 PM"

                  if (date.isSame(moment(), 'day')) {
                      // If the date is today
                      append = append + '<span class="text-muted pull-right">'+ formattedTime +' Today</span>'
                  } else if (date.isSame(moment().subtract(1, 'days'), 'day')) {
                      // If the date is yesterday
                      append = append + '<span class="text-muted pull-right">'+ formattedTime +' Yesterday</span>'
                  } else {
                      // For dates earlier than yesterday
                      append = append + '<span class="text-muted pull-right">'+ formattedTime + date.fromNow() + '</span>'
                  }

                  append = append + '</span>'
                  // append = append + '<span class="pull-right" onclick="deleteComment('+ inc +')"><i class="fa fa-trash-o"></i></span>'
                    append = append + result.notes
                  append = append + '</div>'
                append = append + '</div>'

                $(".box-comments").find(".box-comment:first").before(append) 
                $('.input-comment-accept-request').val("")
              }
          })
        }
    })

    function deleteComment(id){
      $(".box-comment[data-rowid='"+ id +"']").remove()
    }
  </script>
@endsection