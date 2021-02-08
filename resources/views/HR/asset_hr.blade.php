@extends('template.template_admin-lte')
@section('content')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap4-select2-theme@1.0.3/src/css/bootstrap4-select2-theme.css" rel="stylesheet" /> -->
<!-- <link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet" /> -->
<style type="text/css">
  .modalIconsubject input[type=text]{
      padding-left:115px;
    }

    .modalIconsubject.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIconsubject.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIconsubject{
      position:relative;
    }

   .modalIconsubject i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }

    .select2{
      width: 100%!important;
    }

    #tbRequestModal tr td{
      text-align: center;
    }

</style>
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
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          <li class="nav-item active">
            <a class="nav-link" id="list_asset" data-toggle="tab" href="#asset_list" role="tab" aria-controls="asset" aria-selected="false"><i class="fa fa-list"></i>&nbspList Asset</a>
          </li>
          @if(Auth::User()->id_division == 'HR' || Auth::User()->id_division == 'WAREHOUSE' && Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_territory == 'OPERATION')
          <li class="nav-item">
            <a class="nav-link" id="kategori_list" data-toggle="tab" href="#kategori_asset" role="tab" aria-controls="kategori" aria-selected="false"><i class="fa fa-wrench"></i>&nbspKategori</a>
          </li>
          <li>
            <a class="nav-link" id="request_list" data-toggle="tab" href="#request_asset" role="tab" aria-controls="kategori" aria-selected="false"><i class="fa fa- fa-exclamation"></i>&nbspRequest</a>
          </li>
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" id="btnAdd"><i class="fa fa-plus"> </i>&nbsp Asset</button>
          <a href="{{url('exportExcelAsset')}}" id="btnExport" class="btn btn-info btn-sm pull-right" style="margin-right: 5px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
          @else
          <li class="nav-item">
            <a class="nav-link" id="my_asset" data-toggle="tab" href="#current_asset" role="tab" aria-controls="current" aria-selected="false"><i class="fa fa-archive"></i> Current Borrowed/Request</a>
          </li>
          <button class="btn btn-sm btn-success pull-right" style="width: 100px" data-toggle="modal" id="btnRequest"><i class="fa fa-plus"> </i>&nbsp Request Asset</button>
          <!-- <button class="btn btn-sm btn-success pull-right" data-toggle="modal" style="width: 100px" id="btnPinjam"><i class="fa fa-plus"> </i>&nbsp Pinjam Asset</button> -->
          @endif
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active" id="asset_list" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered table-striped nowrap" id="data_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Code Asset</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Latest Peminjam</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  @foreach($asset as $data)
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
                    @if(Auth::User()->id_division == 'HR' || Auth::User()->id_division == 'WAREHOUSE' && Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_territory == 'OPERATION')
                    <td>                      
                      @if($data->status == "UNAVAILABLE")
                      <button class="btn btn-xs btn-default btn-pengembalian" value="{{$data->id_barang}}" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-hourglass-end"></i></button>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                      @elseif($data->status == "AVAILABLE")                      
                      <button class="btn btn-xs btn-success btn-peminjaman" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-hourglass-start"></i></button>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="barang_asset_edit" value="{{$data->id_barang}}"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      <button class="btn btn-xs btn-danger btn-hapus" onclick="hapus('{{$data->id_barang}}','{{$data->nama_barang}}')" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                      @elseif($data->status == "PENDING" || $data->status == "RUSAK" || $data->status == "SERVICE")
                      <button class="btn btn-xs btn-default" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-hourglass-end"></i></button>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                      @else
                      <button class="btn btn-xs btn-default" disabled  style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-hourglass-end"></i></button>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" disabled><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      <button class="btn btn-xs btn-danger btn-hapus" disabled style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Hapus" data-placement="bottom"><i class="fa fa-trash-o"></i></button>
                      @endif                      
                      <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-history" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                      <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset" value="{{$data->id_barang}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                    </td>
                    @else
                    <td>
                      @if($data->status == "UNAVAILABLE" || $data->status == "PENDING" || $data->status == "RUSAK" || $data->status == "SERVICE")
                      <button class="btn btn-xs btn-success" disabled data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-hourglass-start"></i></button>
                      <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset" value="{{$data->id_barang}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                      <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-history" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                      @else
                      <button class="btn btn-xs btn-success btn-peminjaman" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-hourglass-start"></i></button>
                      <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset" value="{{$data->id_barang}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                      <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-history" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                      @endif
                      
                    </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @if(Auth::User()->id_division == 'HR' || Auth::User()->id_division == 'WAREHOUSE' && Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_territory == 'OPERATION')
          <div class="tab-pane fade" id="kategori_asset" role="tabpanel" aria-labelledby="current">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="kategori_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Nama Kategori</th>
                    <th>Qty</th>
                    <th>Action</th>
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
          <div class="tab-pane fade" id="request_asset">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap requestTable" id="request_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No Transaksi</th>
                    <th>Request By</th>
                    <th>Nama Asset</th>
                    <th>Description</th>
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
                      <td>{{$data->nama_barang}}</td>
                      <td>{{$data->description}}</td>
                      <td>
                        <label class="label label-info">Request</label>
                      </td>
                      <td>
                      <button class="btn btn-primary btn-xs" style="width: 50px"  onclick="requestAccept('{{$data->id_barang}}','{{$data->id_transaction}}','ACCEPT')">Accept</button>
                      <button class="btn btn-danger btn-xs" style="width: 50px"  onclick="requestAccept('{{$data->id_barang}}','{{$data->id_transaction}}','REJECT')">Reject</button></td>
                    </tr>
                  @endforeach
                  @foreach($current_request as $datas)
                    <tr>
                      <td>{{$datas->id_request}}</td>
                      <td>{{$datas->name}}</td>
                      <td>{{$datas->nama}}</td>
                      <td><a href="{{$datas->link}}" target="_blank">{!!substr($datas->link, 0, 35) !!}...</a></td> 
                      <td>
                        @if($datas->status == 'REQUEST')
                        <label class="label label-info">Request</label>
                        @else
                        <label class="label label-warning">Pending</label>
                        @endif
                      </td>                     
                      <td>
                        @if($datas->status == 'REQUEST')
                          <button class="btn btn-primary btn-xs" style="width: 50px" onclick="requestAssetAccept('{{$datas->id_request}}','ACCEPT')">Accept</button>
                          <button class="btn btn-danger btn-xs" style="width: 50px" onclick="requestAssetAccept('{{$datas->id_request}}','REJECT')">Reject</button>
                        @else
                          <button class="btn btn-primary btn-xs" style="width: 50px" onclick="requestAssetDone('{{$datas->nik}}','{{$datas->id_request}}')">Done</button>
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
          @else
          <div class="tab-pane fade" id="current_asset" role="tabpanel" aria-labelledby="current">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Descrption</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1?>
                  @foreach($current_borrowed as $data)
                    <tr>
                      <td>{{$data->no_transac}}</td>
                      <td>{{$data->code_name}}</td>
                      <td>{{$data->nama_barang}}</td>
                      <td>{{$data->description}}</td>
                      <td>{{$data->note}}</td> 
                      <td><label class="label label-success">Borrowed</label></td>                     
                      <td>
                        <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset_transac" value="{{$data->id_transaction}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  @endforeach
                  @foreach($current_request as $datas)
                    <tr>
                      <td>{{$datas->id_request}}</td>
                      <td>{{$datas->code_kat}}</td>
                      <td>{{$datas->nama}}</td>
                      <td>{{$datas->merk}}</td>
                      <td style="color: blue">{!!substr($datas->link, 0, 35) !!}...</td> 
                      <td><label class="label label-warning">Request</label></td>                     
                      <td>
                        <button class="btn btn-xs btn-info" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="btnEditRequestAsset" value="{{$datas->id_request}}"><i class="fa fa-edit" style="color: white" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

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

</style>

<!--add asset-->
<div class="modal fade" id="add_asset" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_asset_hr')}}" id="modal_add_asset" name="modalProgress">
            @csrf          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="sow">Asset Code</label>
                <input name="kode_asset" id="kode_asset" class="form-control hidden" value="{{$nomor}}" hidden=></input>
                <input name="asset_code" id="asset_code" class="form-control" value="{{$nomor}}" readonly></input>
              </div>

              <div class="form-group">
                <label for="sow">Kategori</label>
                <select class="form-control category_asset" id="category_asset" name="category_asset" required>
                </select>
                <input type="text" name="category_id" id="category_id" hidden>
                <input type="text" name="category_id_req" id="category_id_req" hidden>
              </div>

              <div class="form-group">
                <label for="sow">Company</label>
                <select class="form-control" id="company_asset" name="company_asset" required>
                  <option value="">Select Company</option>
                  <option value="SIP">PT. SIP</option>
                  <option value="MSP">PT. MSP</option>
                </select>
              </div>

              <div class="form-group">
                <label>Merk</label>
                <input type="" class="form-control" name="merk_barang" id="merk_barang" required>
              </div>

              <div class="form-group">
                <label for="sow">Nama Barang</label>
                <input name="nama_barang" id="nama_barang" class="form-control" required></input>
              </div>

              <div class="form-group">
                <label for="sow">Date of Purchase</label>
                <input type="text" name="asset_date" id="asset_date" class="form-control" required></input>
              </div>
            </div>
            <div class="col-md-6">  
              <div class="form-group">
                <label for="sow">Nomor SN</label>
                <input name="asset_sn" id="asset_sn" class="form-control" required></input>
              </div>
              
              <div class="form-group">
                <label for="sow">Description</label>
                <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
              </div>

              <div class="form-group">
                <label for="sow">Note</label>
                <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
              </div>

              <div class="form-group">
                <label for="sow">Lokasi</label>
                <textarea name="lokasi" id="lokasi" class="form-control" required=""></textarea>
              </div>

              <div class="form-group" style="display: none;" id="peminjams">
                <label for="sow">Request By</label>
                <input type="text" name="requestBy" id="requestBy" class="form-control" readonly="">
                <input type="text" name="requestNik" id="requestNik" class="form-control hidden">
                <input type="text" name="id_requestNewAsset" id="id_requestNewAsset" class="form-control hidden">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-sm btn-success" id="btnSubmit" disabled><i class="fa fa-check"></i>&nbsp Submit</button>
          </div>
        </form>
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
            <label for="sow">Nama barang</label>
            <input class="form-control" type="text" name="nama_barang_asset_edit" id="nama_barang_asset_edit">
          </div>

          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required=""></textarea>
          </div>

          <div class="form-group">
            <label for="sow">Nomor SN</label>
            <input class="form-control" type="text" name="asset_sn_edit" id="asset_sn_edit">
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
            <label for="sow">Lokasi</label>
            <textarea name="lokasi_edit" id="lokasi_edit" class="form-control" required=""></textarea>
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
          <h4 class="modal-title">Peminjaman</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('peminjaman_hr')}}" id="modal_peminjaman" name="modalProgress">
              @csrf
              <input type="text" name="id_barang" id="id_barang" hidden>
              @if(Auth::User()->id_division == 'HR')
              <div class="form-group">
                <label>Nama Peminjam</label>
                <select name="users" id="users" class="form-control" style="width: 270px;" required >
                  <option>Select Name</option>
                  @foreach($users as $user)
                    <option value="{{$user->nik}}">{{$user->name}}</option>
                  @endforeach
                </select>
              </div>
              @endif

              <div class="form-group">
                <label for="sow">Nama Barang</label>
                <input name="nama_barang" id="nama_barang_pinjam" class="form-control" readonly></input>
              </div>

              <div class="form-group">
                <label>Keperluan</label>
                <textarea class="form-control" name="keperluan"></textarea>
              </div>

              <div class="form-group">
                <label>Lokasi</label>
                <textarea class="form-control" name="lokasi"></textarea>
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

<div class="modal fade" id="requestAsset" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Request Asset</h4>           
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/storeRequestAsset')}}">
              @csrf
              <table class="table nowrap" id="tbRequestModal">
                <thead>
                  <tr>
                    <td width="20%">Nama barang</td>
                    <td width="20%">Kategori</td>
                    <td width="20%">Merk</td>
                    <td width="10%">Qty</td>
                    <td width="30%">link</td>
                    <td><button class="btn btn-xs btn-success" type="button" id="btnAddRowReq" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" ><i class="fa fa-plus"></i></button></td>
                  </tr>
                </thead>
                <tbody id="tbody_request">
                  <tr>
                    <td>
                      <input name="nama_barang_request[]" id="nama_barang_request" class="form-control" required></input>
                    </td>
                    <td>
                      <select class="form-control" id="category_asset_request" name="category_asset_request" data-rowid="1" required> <input id="cat_req_id" name="cat_req_id[]" data-rowid="1" hidden></select>
                    </td>
                    <td>
                      <input name="merk_barang_request[]" id="merk_barang_request" class="form-control"></input>
                    </td>
                    <td>
                      <input name="qty_barang_request[]" id="qty_barang_request" class="form-control" required></input>
                    </td>
                    <td>
                      <textarea class="form-control" id="link_barang_request" name="link_barang_request[]" required></textarea>
                    </td>
                    <td>
                      <button class="btn btn-xs btn-danger remove" type="button" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;float: right;" ><i class="fa fa-trash-o"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                  <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-check"></i>&nbsp Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="penghapusan" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hapus Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('penghapusan_hr')}}" id="modal_peminjaman" name="modalProgress">
            @csrf
            <input type="text" name="id_barang" id="id_barang_hapus" hidden>
            <div class="form-group">
            <label>Apakah anda yakin untu menghapus Asset Ini?</label>
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
            <h3 style="text-align: center;"><b>RETURN NOW!</b></h3>
            <table class="table table-bordered">
              <tr>
                <th>Nama Barang</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Kembali</th>
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
          <h4 class="modal-title">Kategori</h4>
        </div>
        <div class="modal-body">
          <form id="modalAddKategori" name="modalAddKategori" method="POST" action="{{url('store_kategori_asset')}}" >
            @csrf
          <input type="" id="status_kategori" name="status_kategori" hidden> 
          <input type="" name="id_kategori" id="id_kategori" value="" hidden>
          <div class="form-group">
            <label for="">Kode</label>
            <input type="text" name="kode_kategori" id="kode_kategori" class="form-control" maxlength="3" minlength="3" required="" style="text-transform:uppercase">
            <small>Must haves 3 character</small>
          </div>
          <div class="form-group">
            <label for="">Kategori</label>
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


@endsection

@section('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script> 
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/roman.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript">
    $("#company_asset").select2()
    $("#select-status").select2()

    $(document).ready(function(){
      $("#btnAdd").attr('data-target','#add_asset') 

      initCategory() 
    })  

    $("#category_asset").on('change',function(){
      $("#category_id").val($('#category_asset').select2('data')[0].no)
    })

    function initCategory(){
      var datas_kat = [];
      $.ajax({
        type:"GET",
        url: "getAssetCategoriHR",
        success:function(result){
          var arr = result.results;        
            var data = {
              id: -1,
              text: 'Select Category...'
            };

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

      $(document).on('change',"select[id^='category_asset_request']",function(e) { 
        var rowid = $(this).attr("data-rowid");
        console.log('gabti')
        $("#cat_req_id[data-rowid='"+rowid+"']").val($("#category_asset_request[data-rowid='"+rowid+"']").select2('data')[0].no)
      })      
    }  

    var i = 1;
    $("#btnAddRowReq").click(function(){     
      ++i;  
      console.log(i)
      console.log('success')
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

    $('#myTab .nav-link').click(function(e) {
      console.log($(e.target).attr("id"))
      if ($(e.target).attr("id") == "list_asset") {
        $("#btnAdd").show();
        $("#btnExport").show();
        $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspAsset');
        $("#btnAdd").attr('data-target','#add_asset');
        $("#btnAdd").removeAttr('onclick');
        $("#btnAdd").attr('data-toggle','modal');
      }else if ($(e.target).attr("id") == "kategori_list") {
        $("#btnAdd").show();
        $("#btnAdd").removeAttr('data-toggle');
        $("#btnAdd").attr('onclick','addKategori()');
        $("#btnExport").show();
        console.log('OK')
        $("#btnAdd").html('<i class="fa fa-plus"></i> &nbspKategori');
        // $("#btnAdd").attr('data-target','#add_kategori');
        $("#btnAdd").attr('onclick','addKategori()');
        $("#btnAdd").removeAttr('data-toggle');
      }else if ($(e.target).attr("id") == "request_list") {
        $("#btnAdd").hide();
        $("#btnExport").hide();
      }
    })

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
        // console.log(this.value);
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
      console.log(this.value)
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

    // $("#requestAccept").on('click',function(){
    function requestAccept(id_barang,id_transaction,status){
      if (status == 'ACCEPT') {
        var titleStatus = 'Accept Peminjaman Asset'
        
      }else{
        var titleStatus = 'Reject Peminjaman Asset'
      }      
      
      Swal.fire({
        title: titleStatus,
        text: "are you sure?",
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
            url:"{{url('acceptPeminjaman')}}",
            data:{
              id_barang:id_barang,
              // nik_peminjam:nik_peminjam,
              id_transaction:id_transaction,
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
                  $("#editJob").modal('toggle')
                }
              })
            },
          });
        }        
      })

    }

    //request asset baru
    function requestAssetAccept(id_request,status){
      if (status == 'ACCEPT') {
        var titleStatus = 'Accept Request Asset Baru'
        
      }else{
        var titleStatus = 'Reject Request Asset Baru'
      }      
      
      Swal.fire({
        title: titleStatus,
        text: "are you sure?",
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
            url:"{{url('acceptNewAsset')}}",
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
                }
              })
            },
          });
        }        
      })
    }

    //create asset baru
    function requestAssetDone(nik,id_request){
      $("#add_asset").modal('show')
      $("#modal_add_asset").attr("action", "{{url('/createNewAsset')}}");
      $.ajax({
        type:"GET",
        url:"{{url('/getRequestAssetBy')}}",
        data:{
          id_request:id_request
        },
        success: function(result){
          $('#requestBy').val(result[0].name)
          $('#nama_barang').val(result[0].nama)
          $('#merk_barang').val(result[0].merk)
          $('#category_id_req').val(result[0].id)
          // $('#category_id').val(result[0].id)
          $('#requestNik').val(nik)
          $('#id_requestNewAsset').val(id_request)
          var CatSelect = $('#category_asset');

          var option = new Option(result[0].kategori, result[0].code_kat, true, true)
          CatSelect.append(option).trigger('change')

          if (result[0].id_company == '1') {
            comVal = 'SIP'
            comValName = 'PT. SIP'
          }else{
            comVal = 'MSP'
            comValName = 'PT. MSP'
          }

          var ComSelect = $("#company_asset")

          var option = new Option(comValName, comVal, true, true);
          ComSelect.append(option).trigger('change')
        },
      })

      $("#peminjams").show()
    }

    $(document).on('click',".btn-pengembalian",function(e) { 
        console.log(this.value);
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

    @if(Auth::User()->id_division == 'HR' || Auth::User()->id_division == 'WAREHOUSE' && Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_territory == 'OPERATION')
      $('#data_table').DataTable({
        "order": [[ 0, "asc" ]],
        pageLength: 20,
        "scrollX":true,
        fixedColumns:   {
          rightColumns: 1
        }
      });
    @else
      $('#data_table').DataTable({
        "order": [[ 0, "asc" ]],
        pageLength: 20,
        "scrollX":true,
      });
    @endif

    $('#datatable').DataTable({
      pageLength: 20,    
      "order": [[ 5, "asc" ]],  
    });

    $('#kategori_table').DataTable({
      pageLength: 20,
    })

    $('#request_table').DataTable({
      pageLength: 20,
      "order": [[ 5, "asc" ]],
    });
    

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

            $("#btnSubmit").prop("disabled",false)

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



    /*$('#myTab a').click(function(e) {
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
    $('#myTab a[href="' + hash + '"]').tab('show');*/
  </script>
@endsection