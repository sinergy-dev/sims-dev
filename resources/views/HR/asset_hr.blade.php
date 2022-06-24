@extends('template.main')
@section('tittle')
GA Asset
@endsection
@section('head_css')
<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    width:200px;  
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
@endsection
@section('content')
<section class="content-header">
  <h1>GA Asset</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">GA - Asset</li>
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
            <li class="nav-item">
              <a class="nav-link" id="list_asset" data-toggle="tab" href="#asset_list" role="tab" aria-controls="asset" aria-selected="false"><i class="fa fa-list"></i>&nbspList Asset</a>
            </li>
              <li class="nav-item">
                <a class="nav-link" id="kategori_list" style="display: none;" data-toggle="tab" href="#kategori_asset" role="tab" aria-controls="kategori" aria-selected="false"><i class="fa fa-wrench"></i>&nbspKategori</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="request_list" style="display: none;" data-toggle="tab" href="#request_asset" role="tab" aria-controls="kategori" aria-selected="false"><i class="fa fa- fa-exclamation"></i>&nbspRequest</a>
              </li>
          <!--     <button class="btn btn-sm btn-primary pull-right" style="display: none;width: 120px;margin-left: 5px;" id="addEvents"><i class="fa fa-plus"></i>&nbsp Calendar event</button>  --> 
              <button class="btn btn-sm btn-success pull-right" data-toggle="modal" id="btnAdd" style="display: none;"><i class="fa fa-plus"> </i>&nbsp Asset</button>
              <a href="{{action('AssetHRController@export')}}" id="btnExport" class="btn btn-info btn-sm pull-right" style="margin-right: 5px;display: none;"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
              <li class="nav-item">
                <a class="nav-link" id="my_asset" style="display: none;" data-toggle="tab" href="#current_asset" role="tab" aria-controls="current" aria-selected="false"><i class="fa fa-archive"></i> Occurance</a>
              </li>          
              <button class="btn btn-sm btn-success pull-right" style="width: 100px;margin-right: 5px;display: none;" id="btnRequest"><i class="fa fa-plus"> </i>&nbsp Request Asset</button>
              <button class="btn btn-sm btn-info pull-right" style="width: 100px;margin-right: 5px;display: none;" id="btnPinjam"><i class="fa fa-plus"> </i>&nbsp Borrow Asset</button>
            <li class="nav-item">
              <a class="nav-link" id="history_asset" data-toggle="tab" href="#history" role="tab" aria-controls="current" aria-selected="false"><i class="fa fa-history"></i> History</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">           
            <div class="tab-pane" id="asset_list" role="tabpanel" aria-labelledby="home-tab">
              <br>            
              <div class="table-responsive" >
                <table class="table table-bordered table-striped" id="data_table" width="100%" cellspacing="0">
                  <h4><i class="fa fa-table"></i> Table Asset</h4>
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th width="15%">Name</th>
                      <th width="25%">Specification</th>
                      <th>Latest person</th>
                      <th>Status</th>
                      <th>Location</th>
                      <th style="text-align: center;">Action</th>
                      <th style="text-align: center;">Action</th>                      
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  @foreach($asset as $item => $value)
                    @foreach($value as $data)
                      <tr>
                          <td>{{$data->code_name}}<input type="" name="id_barang_update" hidden></td>
                          <td>{{$data->nama_barang}}</td>
                          <td>{{$data->description}}</td>
                          <td>
                              @foreach(explode(',', $data->name) as $key => $latest_pinjam) 
                                {{$latest_pinjam}}
                              @endforeach
                          </td>
                          <td>
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
                          <td>{{$data->lokasi}}</td>
                          <td>                      
                            @if($data->status == "UNAVAILABLE")
                            <button class="btn btn-xs btn-default btn-pengembalian" value="{{$data->id_barang}}" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                            <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                            @elseif($data->status == "AVAILABLE")                      
                            <button class="btn btn-xs btn-success btn-peminjaman" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-history"></i></button>
                            <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="barang_asset_edit" value="{{$data->id_barang}}"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
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
                          <td style="text-align: center;">
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
            <div class="tab-pane fade" id="kategori_asset" role="tabpanel" aria-labelledby="current">
              <div class="row">
                <div class="col-md-8 col-xs-12">
                  <div class="table-responsive" style="margin-top: 15px">
                    <h4><i class="fa fa-table"></i> Table Kategori</h4>
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
            <div class="tab-pane" id="request_asset">
              <div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered requestTable" id="request_table" width="100%" cellspacing="0">
                  <h4><i class="fa fa-table"></i> Table Request</h4>
                  <thead>
                    <tr>
                      <th>No Transaction</th>
                      <th>Request By</th>
                      <th>Request Date</th>
                      <th>Name/Category</th>
                      <th>Specification</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>
                    @foreach($request_asset as $data)
                      <tr>
                        <td>{{$data->no_transac}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->note}}</td>
                        <td>{!!$data->keterangan!!}</td>
                        <td>
                          <label class="label label-info">Request</label>
                        </td>
                        <td>
                        <button class="btn btn-primary btn-xs" style="width: 50px"  onclick="requestAccept('{{$data->note}}','{{$data->id_transaction}}','ACCEPT')">Accept</button>
                        <button class="btn btn-danger btn-xs" style="width: 50px"  onclick="requestAccept('{{$data->note}}','{!!$data->keterangan!!}','{{$data->id_transaction}}','REJECT')">Reject</button></td>
                      </tr>
                    @endforeach
                    @foreach($current_request as $datas)
                      <tr>
                        <td>{{$datas->id_request}}</td>
                        <td>{{$datas->name}}</td>
                        <td>{{$data->created_at}}</td>                        
                        <td>{{$datas->nama}}</td>
                        <td><div class="truncate">{{$datas->link}}</div></td> 
                        <td style="display: none" class="links{{$datas->id_request}}">{{$datas->link}}</td>
                        <td>
                          @if($datas->status == 'REQUEST')
                          <label class="label label-info">Request</label>
                          @else
                          <label class="label label-warning">Pending</label>
                          @endif
                        </td>                     
                        <td>
                          @if($datas->status == 'REQUEST')
                            <button class="btn btn-primary btn-xs" style="width: 50px" onclick="requestAssetAccept('{{$datas->nama}}','{{$datas->id_request}}','ACCEPT')">Accept</button>
                            <button class="btn btn-danger btn-xs" style="width: 50px" onclick="requestAssetAccept('{{$datas->nama}}','{{$datas->id_request}}','REJECT')">Reject</button>
                          @else
                            <button class="btn btn-success btn-xs" style="width: 100px" onclick="requestAssetDone('{{$datas->nik}}','{{$datas->id_request}}')">Pesanan Diterima</button>
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
            <div class="tab-pane fade" id="current_asset" role="tabpanel" aria-labelledby="current">
              <div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered collapsed DataTable" id="datatable" width="100%" cellspacing="0">
                  <h4><i class="fa fa-table"></i> Table My Asset</h4>
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Specification</th>
                      <th>Note</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>                  
                    @foreach($pinjam_request as $data)
                      <tr>
                        <td>{{$data->no_transac}}</td>
                        <td> - </td>
                        <td>{{$data->note}}</td>
                        <td>{!!$data->keterangan!!}</td>
                        <td> - </td> 
                        <td><label class="label label-warning">PENDING</label></td>                     
                        <td>
                          <button class="btn btn-xs btn-info" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" onclick="btnEditRequestAsset('{{$data->id_transaction}}','pinjam')"><i class="fa fa-edit" style="color: white" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    @endforeach
                    @foreach($current_request as $datas)
                      <tr>
                        <td>{{$datas->id_request}}</td>
                        <td>{{$datas->code_kat}}</td>
                        <td>{{$datas->nama}}</td>
                        <td>{{$datas->merk}}</td>
                        <td><div class="truncate">{{$datas->link}}</div></td> 
                        <td>
                          @if($datas->status == 'REQUEST')
                          <label class="label label-info">{{$datas->status}}</label>
                          @else
                          <label class="label label-warning">{{$datas->status}}</label>                        
                          @endif
                        </td>                     
                        <td>
                          <button class="btn btn-xs btn-info" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" onclick="btnEditRequestAsset('{{$datas->id_request}}','request')"><i class="fa fa-edit" style="color: white" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    @endforeach
                    @foreach($current_borrowed as $data)
                      <tr>
                        <td>{{$data->no_transac}}</td>
                        <td>{{$data->code_name}}</td>
                        <td>{{$data->nama_barang}}</td>
                        <td>{{$data->description}}</td>
                        <td>{{$data->note}}</td> 
                        <td><label class="label label-success">BORROWING</label></td>                     
                        <td>
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
            <div class="tab-pane fade" id="history" role="tabpanel">
            	<div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered collapsed DataTable" id="history_table" width="100%" cellspacing="0">
                  <h4><i class="fa fa-table"></i> Table History</h4>
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Specification</th>
                      <th>Note</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1?>
                    @foreach($historyCancel as $datas)
                      <tr>
                        <td>{{$datas->id_request}}</td>
                        <td>{{$datas->code_kat}}</td>
                        <td>{{$datas->nama}}</td>
                        <td>{{$datas->merk}}</td>
                        <td><div class="truncate">{{$datas->link}}</div></td> 
                        <td>
                          @if($datas->status == 'ACCEPT')
                          <label class="label label-success">{{$datas->status}}</label>
                        	@elseif($datas->status == 'CANCEL')
                          <label class="label label-danger">{{$datas->status}}</label>
                          @else
                          <label class="label label-danger">{{$datas->status}}</label>
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
              <form method="POST" action="{{url('store_asset_hr')}}" id="modal_add_asset" name="modalProgress">          
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
                        <input type="" class="form-control" name="merk_barang" id="merk_barang" placeholder="input merk">
                      </div>
                    </div> 
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Name</label>
                        <input name="nama_barang" id="nama_barang" placeholder="input name" class="form-control" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Serial Number</label>
                        <input name="asset_sn" id="asset_sn" class="form-control" placeholder="input serial number">
                      </div>                   
                    </div>
                  </div>
                        
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Type</label>
                        <input name="type_asset" id="type_asset" placeholder="input type asset" class="form-control">
                      </div>
                      
                      <div class="col-md-6 form-group">
                        <label for="sow">Note</label>
                        <textarea name="note" id="note" placeholder="input note" class="form-control" ></textarea>            
                      </div>            
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Purchase Price</label>
                        <input name="purchase_price" id="purchase_price" class="form-control money" placeholder="input price asset" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Specification</label>
                        <textarea name="keterangan" id="keterangan" placeholder="input Specification" class="form-control" required=""></textarea>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6 form-group">
                        <label for="sow">Date of Purchase</label>
                        <input type="text" name="asset_date" id="asset_date" placeholder="input date" class="form-control" required>
                      </div>

                      <div class="col-md-6 form-group">
                        <label for="sow">Location</label>
                        <textarea name="lokasi" id="lokasi" class="form-control" placeholder="input location"></textarea>
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
          </form>
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
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Request Asset</h4>           
          </div>
          <div class="modal-body">
            <form method="GET" action="{{url('/storeRequestAsset')}}">
                @csrf
                <table class="table nowrap" id="tbRequestModal">
                  <thead>
                    <tr>
                      <td width="20%">Name</td>
                      <td width="20%">Category</td>
                      <td width="20%">Merk</td>
                      <td width="10%">Qty</td>
                      <td width="30%">Description</td>
                      <td><button class="btn btn-xs btn-success" type="button" id="btnAddRowReq" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" ><i class="fa fa-plus"></i></button></td>
                    </tr>
                  </thead>
                  <tbody id="tbody_request">
                    <tr>
                      <td>
                        <input name="nama_barang_request[]" id="nama_barang_request" class="form-control" placeholder="input name" required></input>
                      </td>
                      <td>
                        <select class="form-control" id="category_asset_request" name="category_asset_request" data-rowid="1" required> <input id="cat_req_id" name="cat_req_id[]" data-rowid="1" hidden></select>
                      </td>
                      <td>
                        <input name="merk_barang_request[]" id="merk_barang_request" class="form-control" placeholder="input merk"></input>
                      </td>
                      <td>
                        <input name="qty_barang_request[]" id="qty_barang_request" class="form-control" placeholder="qty" required></input>
                      </td>
                      <td>
                        <textarea class="form-control" id="link_barang_request" name="link_barang_request[]" placeholder="input specification,*suggest link for buy" required></textarea>
                      </td>
                      <td>
                        <button class="btn btn-xs btn-danger remove" type="button" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;float: right;" ><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
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

  <div class="modal fade" id="penghapusan" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete Asset</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('penghapusan_hr')}}" name="modalProgress">
              @csrf
              <input type="text" name="id_barang" id="id_barang_hapus">
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
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><label>Accept Request</label></h5>
        </div>
        <div class="modal-body" id="">
        	<div style="text-align: center;">
        		<i class="fa  fa-info-circle fa-4x"></i>
        		<h4 class="titleWarning">Accept Peminjaman Asset !</h4>
        	</div>
            <div>
              <ul>
                <li>Category : <span id="katModal" style="overflow-wrap:break-word"></span></li>
                <li>Description : <span id="noteModal" style="overflow-wrap:break-word"></span></li>
              </ul>
            </div>
        	
          	<div class="form-group" id="dropdownAsset">
          		<label>Choose Barang</label>
          		<select id="barang_asset" name="barang_asset" style="width: 100%;"></select>
          	</div>
        	
        </div>
        <div class="modal-footer">
        	<button type="button" id="btnAcceptRequestModal" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="https://momentjs.com/downloads/moment-timezone.min.js"></script>
  <script type="text/javascript" src="https://momentjs.com/downloads/moment-timezone-with-data.js"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script> 
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>  
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/roman.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>
@endsection
@section('script')  
  <script type="text/javascript">
    $(document).ready(function(){
        initCategory() 

        var accesable = @json($feature_item);
        accesable.forEach(function(item,index){
          $("#" + item).show()          
        })  

        if (accesable.includes('btnAdd')) {
          var column1 = table.column(7);
          column1.visible(!column1.visible());

          if (requestTable.rows().count()) {
            $('#request_list').append('<span class="badge">'+ requestTable.rows().count() +'</span>')
            $('#request_asset').addClass('active')
            $('#asset_listset').removeClass('active')        
            activeTab('request_asset')  
            $('#btnAdd').hide()
            $('#btnExport').hide() 
          }else{
            activeTab('asset_list') 
            $('#asset_list').addClass('active')
            $('#request_asset').removeClass('active')
          }

          $('#myTab .nav-link').click(function(e) {
            if ($(e.target).attr("id") == "list_asset") {
              $("#btnAdd").show();
              $("#btnExport").show();
              $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspAsset');
              $("#btnAdd").attr('data-target','#add_asset');
              $("#btnAdd").removeAttr('onclick');
              $("#btnAdd").attr('data-toggle','modal');
              $("#company_asset").val("")
              $("#nama_barang").val("")
              $("#asset_sn").val("")
              $("#keterangan").val("")
              $("#peminjams").hide()
              $("#category_asset").val("")
              $("#merk_barang").val("")
              $("#asset_date").val("")
              $("#keterangan").val("")
              $("#lokasi").val("")
            }else if ($(e.target).attr("id") == "kategori_list") {
              $("#btnAdd").show();
              $("#btnAdd").removeAttr('data-toggle');
              $("#btnAdd").attr('onclick','addKategori()');
              $("#btnExport").show();
              $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspKategori');
              // $("#btnAdd").attr('data-target','#add_kategori');
              $("#btnAdd").attr('onclick','addKategori()');
              $("#btnAdd").removeAttr('data-toggle');
            }else if ($(e.target).attr("id") == "request_list") {
              $("#btnAdd").hide();
              $("#btnExport").hide();
            }
          })
        }else{
          var column1 = table.column(6);
          column1.visible(!column1.visible());
          activeTab('asset_list')
          $('#asset_list').addClass('active')
          $('#request_asset').removeClass('active')
          $('#btnAdd').hide()
          $('#btnExport').hide() 
        }
    })

    $.ajax({
      type:"GET",
      url: "/testgetCalendarList",
      success:function(result){
        $('#groupCalendar').select2({
          placeholder: "Select a category",
          data: $.map(result.items, function (item) {
            return {
              id:item.id,
              text:item.summary
            }
          })
        })        
      },
    })

    $("#company_asset").select2()
    $("#select-status").select2()
    $("#guestEmail").select2()

    $("#eventsCalendar").daterangepicker({
      timePicker: true,
      start: moment().startOf('hour'),
      end: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'DD/MM/YYYY hh:mm A'
      }
    })

    $("#submitCalendar").click(function(){
      var startDate = $('#eventsCalendar').val().slice(0,19)
      var endDate = $('#eventsCalendar').val().slice(22,41)
      console.log(moment.tz(startDate, "DD/MM/YYYY hh:mm A", "Asia/Jakarta").format())
      $.ajax({
        type:"GET",
        url: "testPostEventCalendar",
        data:{
          summary:$("#summaryCal").val(),
          description:$("#descCal").val(),
          startDateTime:moment.tz(startDate, "DD/MM/YYYY hh:mm A", "Asia/Jakarta").format(),
          endDateTime:moment.tz(endDate, "DD/MM/YYYY hh:mm A", "Asia/Jakarta").format(),
          email:$("#guestEmail").val(),
          group:$("#groupCalendar").val()
        },
        success:function(result){
          alert("event created")      
        },
      })
    })

    $('.money').mask('000,000,000,000,000', {reverse: true});

    $(document).ready(function(){
      $("#btnAdd").attr('data-target','#add_asset') 

      $("#submitReq").on('click',function(){
        $('#requestAsset').modal('hide')
      })
    })  

    $("#addEvents").click(function(){
      $("#modalCalender").modal("show")

    })

    $("#category_asset").on('change',function(){
      $("#category_id").val($('#category_asset').select2('data')[0].no)
    })

    $("#category_pinjam").on('change',function(){
      $("#cat_pinjam_id").val($('#category_pinjam').select2('data')[0].text)
    })

    function initCategory(){
      var datas_kat = [];
      var datas_katPinjam = [];
      $.ajax({
        type:"GET",
        url: "{{url('/getAssetCategoriHR')}}",
        success:function(result){
          var arr = result.results;        
            var data = {
              id: -1,
              text: 'Select Category...'
            };

            for(var x in arr)
              arr[x].id == 'OTH' ?
            arr.push(arr.splice(x,1)[0]) : 0

            datas_kat.push(data)
            $.each(arr,function(key,value){
              datas_kat.push(value)
            })

          $("#category_asset").select2({
            placeholder: "Select a category",
            // theme: 'bootstrap4',
            data: datas_kat
          });

          $("#category_asset_request[data-rowid]").select2({
            placeholder: "Select a category",
            // theme: 'bootstrap4',
            data: datas_kat
          });
        }
      })  

      $.ajax({
        type:"GET",
        url: "{{url('/getCategoryPinjam')}}",
        success:function(result){
          console.log("---ini result" + result)
          var arr = result.results;        
            var data = {
              id: -1,
              text: 'Select Category...'
            };

            for(var x in arr)
              arr[x].id == 'OTH' ?
            arr.push(arr.splice(x,1)[0]) : 0

            datas_katPinjam.push(data)
            $.each(arr,function(key,value){
              datas_katPinjam.push(value)
            })

          	$("#category_pinjam").select2({
          		placeholder: "Select a category",
            	// theme: 'bootstrap4',
            	data: datas_katPinjam
          	})
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
      initCategory()
      append =  append + '<tr id="row'+i+'">'
      append =  append + '<td><input name="nama_barang_request[]" data-rowid="'+i+'" id="nama_barang_request" class="form-control"></input></td>'
      append =  append + '<td><select class="form-control category_asset_request" id="category_asset_request" data-rowid="'+i+'" name="category_asset_request" required></select>'
      append =  append + '<input id="cat_req_id" name="cat_req_id[]" data-rowid="'+i+'" hidden></td>'
      append =  append + '<td><input name="merk_barang_request[]" id="merk_barang_request" data-rowid="'+i+'" class="form-control"></input></td>'
      append =  append + '<td><input name="qty_barang_request[]" id="qty_barang_request" data-rowid="'+i+'" class="form-control" required></input></td>'
      append =  append + '<td><textarea id="link_barang_request" name="link_barang_request[]" data-rowid="'+i+'" class="form-control" required></textarea></td>'
      append =  append + '<td><button class="btn btn-xs btn-danger remove" data-rowid="'+i+'" type="button" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;float: right;" ><i class="fa fa-trash-o"></i></button>'
      append =  append + '</td>'
      append =  append + '</tr>'

      $('#tbRequestModal > tbody:last-child').append(append);     
      
    })

    $(document).on('click', '.remove', function() {
      var trIndex = $(this).closest("tr").index();
        if(trIndex>0) {
          $(this).closest("tr").remove();
        } else {
          alert("Sorry!! Can't remove first row!");
      }
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

    $(document).on('click',"#barang_asset_edit",function(e) { 
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
            append = append + '<th>Tanggal Pinjam</th>' 
            append = append + '<td>: '+ result[0].tgl_peminjaman +'</td>'         
            append = append + '</tr>'
            append = append + '<tr>' 
            append = append + '<th>Tanggal Kembali</th>'    
            if (result[0].tgl_pengembalian == null) {
              append = append + '<td>: - </td>'  
            }else{
              append = append + '<td>: '+ result[0].tgl_pengembalian +'</td>'  
            }                
            append = append + '</tr>'
            append = append + '<tr>' 
            append = append + '<th>Keterangan</th>'  
            if (result[0].keterangan == null) {
              append = append + '<td>: - </td>'  
            }else{
              append = append + '<td>: '+ result[0].keterangan +'</td>' 
            }                   
            append = append + '</tr>' 
            append = append + '<tr>' 
            append = append + '<th>Note</th>' 
            if (result[0].note == null) {
              append = append + '<td>: - </td>'  
            }else{
              append = append + '<td>: '+ result[0].note +'</td>'  
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
            append = append + '<li>Category : '+ result[0].lokasi +'</li>'
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
          Swal.fire({
            title: 'Please Wait..!',
            text: "It's updating..",
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
            type:"GET",
            url:"{{url('batalkanReq')}}",
            data:{
              id_request:id_request,
              status:status
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
      Swal.fire({
          title: 'Please Wait..!',
          text: "It's updating..",
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
          type:"GET",
          url:"{{url('AddNoteReq')}}",
          data:{
            id_request:id_request,
            notes:$("#notes").val(),
            status:status
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

    // $("#requestAccept").on('click',function(){
    function requestAccept(note,id_transac,status){   	
      var swalAccept;
      if (status == 'ACCEPT') {
      	var myArrayOfThings = [];  	
    	  var titleStatus = 'Accept Peminjaman Asset' 

      	$("#acceptModalPinjam").modal('show')
        $(".titleWarning").text(titleStatus)
        $("#katModal").text(note)
        // $("#noteModal").html('<br>'+keterangan)
        $('#dropdownAsset').show()
    	 
	      $.ajax({
		    type:"GET",
        data:{
          category:note
        },
		    url: "{{url('/getListAsset')}}",
		    success:function(result){
  	      	var arr = result.results;        
  	        var data = {
  	          id: -1,
  	          text: 'Select Category...'
  	        };

  	        myArrayOfThings.push(data)
  	        $.each(arr,function(key,value){
  	          myArrayOfThings.push(value)
  	        })
            console.log(arr)

  		      $("#barang_asset").select2({
  	          	placeholder: "Select a category",
  	            // theme: 'bootstrap4',
  	            data: myArrayOfThings
  	        })	
	       
  		    }
		    })   

  		  $("#btnAcceptRequestModal").click(function(){
    	    swalAccept = Swal.fire({
            title: titleStatus,
            text: "Are you sure?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
          })

          swalAccept.then((result) => {
    		    if (result.value) {
    		        Swal.fire({
                title: 'Please Wait..!',
                text: "It's updating..",
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
                type:"GET",
                url:"{{url('acceptPeminjaman')}}",
                data:{
                  id_transaction:id_transaction,
                  status:status,
                  id_barang:$('#barang_asset').select2('data')[0].id
                },
                success: function(result){
                  Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                      $("#acceptModalPinjam").modal('toggle')
                    }
                  })
                },
              });
            }        
          })
  		  })	

      }else{
      	var swalAccept;
        var titleStatus = 'Reject Peminjaman Asset'
        swalAccept = Swal.fire({
          title: titleStatus,
          text: "Reason for rejecting:",
          input: 'text',
          icon: 'warning',
          showCancelButton: true        
        })

        swalAccept.then((result) => {
  		    if (result.value) {
  		      Swal.fire({
  		        title: 'Please Wait..!',
  		        text: "It's updating..",
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
  		        type:"GET",
  		        url:"{{url('acceptPeminjaman')}}",
  		        data:{
  		          // id_barang:id_barang,
  		          // nik_peminjam:nik_peminjam,
  		          id_transaction:id_transaction,
  		          status:status,
  		          reason:result.value
  		        },
  		        success: function(result){
  		          Swal.showLoading()
  		          Swal.fire(
  		            'Successfully!',
  		            'success'
  		          ).then((result) => {
  		            if (result.value) {
  		              location.reload()
  		              $("#editJob").modal('toggle')
  		            }
  		          })
  		        },
  		      });
  		    }  
		    })
      } 
    }

    $("#btnSubmit").on('click',function(){
      // $("#add_asset").modal('hide')
    })

    //request asset baru
    function requestAssetAccept(nama,id_request,status){
      var swalAccept;
      if (status == 'ACCEPT') {
        var titleStatus = 'Accept Request New Asset'  

        $("#acceptModalPinjam").modal('show')
        $(".titleWarning").text(titleStatus)
        $("#katModal").text(nama)
        // $("#noteModal").html('<br>'+link)
        $("#noteModal").html('<br>' + $(".links"+id_request).text().replace(/(?:\r\n|\r|\n)/g, '<br>'))        

        $('#dropdownAsset').hide()

        $("#btnAcceptRequestModal").click(function(){
          swalAccept = Swal.fire({
            title: titleStatus,
            text: "are you sure?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
          })

          swalAccept.then((result) => {
            if (result.value) {
              Swal.fire({
                title: 'Please Wait..!',
                text: "It's updating..",
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
                type:"GET",
                url:"{{url('acceptNewAsset')}}",
                data:{
                  id_request:id_request,
                  status:status,
                  reason:result.value
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
            Swal.fire({
              title: 'Please Wait..!',
              text: "It's updating..",
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
              type:"GET",
              url:"{{url('acceptNewAsset')}}",
              data:{
                id_request:id_request,
                status:status,
                reason:result.value
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
      columnDefs: [
        // { orderable: false, targets: 0},
        { targets: 8, "visible": false}
      ],
      order: [[8, 'asc']]
      // "scrollX":true,
    });
    

    $('#datatable').DataTable({
      pageLength: 20, 
      columnDefs: [
         { "width": "10%", "targets": 0 }
      ],   
      "order": [[ 5, "desc" ]]
    });

    $('#kategori_table').DataTable({
      pageLength: 20,
      columnDefs: [
         { "width": "5%", "targets": 0 }
      ], 
    })

    var requestTable = $('#request_table').DataTable({
      pageLength: 20,
      "ColumnDefs":[
        { targets: 'no-sort', orderable: false }
      ],
      "aaSorting": [],
    });

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
  </script>
@endsection