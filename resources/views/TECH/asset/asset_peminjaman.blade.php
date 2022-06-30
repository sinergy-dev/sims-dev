@extends('template.main')
@section('tittle')
Technical Asset
@endsection
@section('head_css')
  <link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css"> -->
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
    tr.group,
    tr.group:hover {
      font-size: 14px;
      font-style: italic;
      color: #f02424;
      background-color: white !important;
    }

    .datatablelog tbody tr:first-child td {
      background-color: #f02424;
      color: white;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 55px;
      height: 28px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #d1523f;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2d9c43;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2d9c43;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }

    .available-on{
      color: #2d9c43;
    }

    .available-off{
      color: #d1523f;
    }

    .color-switch{
      color: #dfebe1;
    }

    .display-none{
      display: none;
    }

    .display-block{
      display: block;
    }

    /* Firefox */

  </style>
@endsection
@section('content')

<section class="content-header">
  <h1>
    Tech Asset Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Tech - Asset Management</li>
    <li class="active">SIP</li>
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
    <div class="alert alert-primary" id="alert">
      {{ session('alert') }}
    </div>
  @endif

  <div class="box">
    <div class="box-body">
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" id="kategori-tab" data-toggle="tab" href="#kategori_tab" role="tab" aria-controls="kategori" aria-selected="false">Kategori</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="list-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="home" aria-selected="true">List </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="peminjaman_asset" data-toggle="tab" href="#peminjaman_tab" role="tab" aria-controls="profile" aria-selected="false">Peminjaman</a>
          </li>
          <a href="{{action('AssetController@exportExcelTech')}}"><button id="export-excel" style="margin-left: 5px" class="btn btn-sm btn-success pull-right display-none"><i class="fa fa-cloud-download"></i> Excel</button></a>
        	<button class="btn btn-sm btn-warning pull-right display-none" id="list-asset"  data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"> </i>&nbspAsset</button>
        	<button class="btn btn-sm btn-info pull-right" id="kategori-asset" style="margin-right: 5px;display:none;" data-toggle="modal" data-target="#add_kategori"><i class="fa fa-plus"> </i>&nbspKategori</button>
          <button class="btn btn-block btn-primary btn-sm pull-right btn-add-peminjaman" style="width: 100px;margin-right: 5px;" id="btn_add_peminjaman"><i class="fa fa-plus" > </i>&nbsp Peminjaman</button>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane" id="kategori_tab" role="tabpanel" aria-labelledby="kategori-tab">
             <div class="box box-primary">
              <div class="box-header with-border">
                <h5 class="box-title"><i class="fa fa-table"></i> Kategori Asset </h5>
                <div class="box-tools pull-right">
                  <!-- Collapse Button -->
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <div class="table-responsive" style="margin-top: 15px">
                  <table class="table table-bordered DataTable" id="datatable3" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Acronym</th>
                        <th>Kategori</th>
                        <th>Qty</th>
                        <th>Deskripsi</th>
                        <th>Action</th>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            
          </div>
          <div class="tab-pane" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h5 class="box-title"><i class="fa fa-table"></i> Warehouse Asset </h5>
                <div class="box-tools pull-right">
                  <!-- Collapse Button -->
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body table-responsive">
                <table class="table table-bordered nowrap " id="data_Table" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No. Transaksi</th>
                      <th>Id Asset</th>
                      <th>Nama Barang/Type</th>
                      <th>Serial Number</th>
                      <th>Keterangan</th>
                      <th>Lokasi Barang</th>
                      <th>Status</th>
                      <th id="col_action" style="display: none;">Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="box box-primary">
              <div class="box-header with-border">
                <h5 class="box-title"><i class="fa fa-table"></i> Activity log </h5>
                <div class="box-tools pull-right">
                  <!-- Collapse Button -->
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body table-responsive">
                <table class="table table-bordered nowrap datatablelog" id="dataTableLog" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Aktivitas</th>
                      <th>Waktu</th>
                      <th>Oleh</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>    
          </div>
          <div class="tab-pane" id="peminjaman_tab" role="tabpanel" aria-labelledby="profile-tab">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h5 class="box-title"><i class="fa fa-table"></i> Peminjaman Asset </h5>
                <div class="box-tools pull-right">
                  <!-- Collapse Button -->
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <div class="nav-tabs-custom" id="project_tab" role="tabpanel" aria-labelledby="project-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="tabs_item active" id="tabs_occurance"><a href="#occurance" id="occurance" data-toggle="tab" onclick="changeTabs('occurance')">Occurance</a></li>
                  <li class="tabs_item" id="tabs_history"><a href="#history" id="history" data-toggle="tab" onclick="changeTabs('history')">History</a></li>
                </ul>
                <div class="tab-content">
                  <div class="table-responsive" style="margin-top: 15px:">
                      <table class="table table-bordered table-striped DataTable" id="datatable1" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>No. Transaksi</th>
                            <th>Kategori</th>
                            <th>Qty</th>
                            <th>Keterangan</th>
                            <th>Keperluan</th>
                            <th>Nama Peminjam</th>
                            <th>Tgl Peminjaman</th>
                            <!-- <th>Tgl Pengembalian</th> -->
                            <th>Tgl Pengembalian(Aktual)</th>
                            <th>Status</th>
                            <th style="text-align: center;" id="col_action">Action</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                </div>                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="serial_number" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="modalProgress" name="modalProgress">
            <div class="form-group inputIconBg" style="padding-left: 10px">
              <label>No Transaksi</label>
              <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman" readonly>
            </div>
            <div class="form-group inputIconBg" style="padding-left: 10px">
              <label>Nama Peminjam</label>
              <input type="text" class="form-control" name="nama_peminjam" id="nama_peminjam" readonly>
            </div>
            <div class="form-group modalIcontgl inputIconBg" style="padding-left: 10px">
              <label>Tanggal Peminjaman</label>
              <input type="text" class="form-control money" name="tgl_pinjam" id="tgl_pinjam" readonly>
            </div>
            <div class="form-group modalIcontgl inputIconBg" style="padding-left: 10px">
              <label>Tanggal Pengembalian(Estimasi)</label>
              <input type="text" class="form-control money" name="tgl_kembali" id="tgl_kembalian" readonly>
            </div>
            <div class="form-group modalIcontgl inputIconBg" style="padding-left: 10px">
              <label>Tanggal Pengembalian(Aktual)</label>
              <input type="text" class="form-control money" name="tgl_kembali_aktual" id="tgl_kembalian_aktual" readonly>
            </div>
            <div class="form-group modalIcontgl inputIconBg" id="keterangan_reject" style="padding-left: 10px;display: none;">
              <label>Alasan Reject</label>
              <input type="text" class="form-control money" name="inpKeterangan_reject" id="inpKeterangan_reject" readonly>
            </div>
            <legend></legend>
            <table style="margin-left:5px" class="table table-bordered table-striped">
              <input type="" name="id_transaksi" id="id_transaksi" hidden>
              
              <tr class="tr-header">
                <th style="border-left: 15px; width: 270px;">Id Aset</th>
                <th style="border-left: 15px; width: 50%">Nama Aset</th>
                <th style="border-left: 15px; width: 270px; margin-left: ;">Serial Number</th>
              </tr>
              <tbody id="mytable">
                <tr>
                  <th style="margin-bottom: 75px;  width: 270px;">
                  </th>
                  <td style="margin-bottom: 75px;  width: 50%;">
                  </td>
                  <td style="margin-bottom: 75px;  width: 270px;">
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--add asset-->
<div class="modal fade" id="add_asset" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset Engineer</h4>
        </div>
        <div class="modal-body">
          <form id="modalAddAsset" name="modalAddAsset">
            @csrf
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="">Kategori</label><br>
            <select type="text" class="form-control" style="width: 270px;" placeholder="Select Kategori" name="kategori" id="kategori2" required>
            	<option value="">Select Kategori</option>
              @foreach($kategori as $data)
              <option value="{{$data->id_kat}}">{{$data->kategori}}</option>
              @endforeach
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="sow">Qty</label>
            <input name="qty" id="qty" type="number" class="form-control" required="">
          </div> -->
          <div class="form-group">
            <label for="sow">Serial Number</label>
            <textarea name="sn" id="sn" class="form-control" required></textarea>
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan_add" class="form-control" required></textarea>
          </div>
          <div>
            <label for="sow">Lokasi Barang</label>
            <textarea name="letak_barang" id="letak_barang" class="form-control" required></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-sm btn-success btn-Add-Asset"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="add_kategori" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Kategori</h4>
        </div>
        <div class="modal-body">
          <form id="modalAddKategori" name="modalAddKategori">
            @csrf
          <div class="form-group">
            <label for="">Code</label>
            <input type="text" placeholder="Input Acronym max 4 digit" maxlength="4" style="text-transform:uppercase" name="code_kat" id="code_kat" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="">Kategori</label>
            <input type="text" placeholder="Input Kategori" name="kategori" id="kategori" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" placeholder="Input Keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-sm btn-success btn-add-kategori"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit asset-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Asset Engineer</h4>
        </div>
        <div class="modal-body">
          <form id="modalEdit" name="modalEdit" >
            @csrf
            <input type="" name="id_barang_edit" id="id_barang_edit" hidden>
            <!-- <div class="form-group">
              <label for="sow">Qty</label>
              <input name="qty_edit" id="qty_edit" type="number" class="form-control" required="">
            </div> -->
            <div class="form-group">
              <label>Nama Barang</label>
              <input type="text" name="edit_nama" id="edit_nama" class="form-control">
            </div>

            <div class="form-group">
              <label>Serial Number</label>
              <input type="text" name="serial_number_edit" id="serial_number_edit" class="form-control">
            </div>

            <div>
              <label>Status Barang</label>
            </div>
            <div class="form-group" style="display:inline-block; ">
              <span class="label-unavailable available-off" style="margin-right: 5px;margin-top: 5px;"><b>Unavailable</b></span>
              <label class="switch">
              <input type="checkbox" class="cb-value" value="yes" id="switch-checkbox" name="switch-checkbox">
              <!-- <input type="checkbox" value="no" id="switch-checkbox-unvisible" name="switch-checkbox-unvisible"> -->
              <span class="slider round"></span>
              </label>
              <span class="label-available color-switch" style="margin-left: 5px;margin-top: 5px;"><b>Available</b></span>
            </div>

            <div class="form-group">
              <label for="sow">Keterangan</label>
              <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required></textarea>
            </div>

            <div class="form-group">
              <label for="sow">Lokasi Barang</label>
              <textarea name="lokasi_edit" id="lokasi_edit" class="form-control" required></textarea>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-sm btn-warning btn-update-asset"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit kategori-->
<div class="modal fade" id="edit_kategori" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <label>Kategori</label> : 
          <span style="color: blue;"><b id="kategori_name"></b></span> 
        </div>
        <div class="modal-body">
            @csrf
          <div class="form-group">
            <label>Acronym</label>
            <input type="text" class="form-control" name="acronym" maxlength="4" placeholder="input max 4 code" style="text-transform:uppercase;" id="acronym">
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea type="text" class="form-control" name="description" id="description_edit" placeholder="input description"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="button" id="update_kategori" class="btn btn-warning btn-sm">Update</button>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="peminjaman_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peminjaman</h4>
        </div>
        <div class="modal-body">
          <form id="modalPeminjaman" name="modalPeminjaman">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-danger alert-dismissible" id="alertPeminjaman" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                <span id="textAlertPeminjaman"></span>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="sow">Tgl Peminjaman</label>
              <input type="text" name="tgl_peminjaman" id="tgl_peminjaman" autocomplete="off" class="form-control" placeholder="Input Date"></input>
            </div>
            <div class="form-group col-md-6">
              <label for="sow">Tgl Pengembalian</label>
              <input type="text" name="tgl_kembali" id="tgl_kembali" class="form-control" autocomplete="off" placeholder="Input Date"></input>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-md-6">
              <label for="">Kategori</label><br>
              <select type="text" class="form-control kategori3 select2" style="width: 100%;" placeholder="Select Kategori" name="kategori3" id="kategori3" required>
              </select>
            </div>
     <!--        <div class="form-group">
                @if ($message = Session::get('warning'))
                <div class="alert alert-warning alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button> 
                  <strong>{{ $message }}</strong>
                </div>
                @endif
            </div> -->
            <div class="form-group col-md-6">
              <label>Masukkan kebutuhan</label><br>
              <input type="text" name="qtys" id="qtys" class="qtys" hidden>
              <input type='number' name='quantity' id="quantity" placeholder="0" class="form-control" style="width: 270px;" />
            </div>
          </div>
          <div class="form-group"> 
            <label>Keperluan</label>
            <textarea name="description" placeholder="Input Keperluan" id="description" class="form-control" required></textarea>
          </div>
          <div class="form-group"> 
            <label>Lokasi Peminjaman</label>
            <textarea name="keterangan" id="keterangan_peminjaman" placeholder="Input Lokasi Peminjaman" class="form-control"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-sm btn-success btn-submit-peminjaman"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="pengembalian" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pengembalian</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_asset')}}" id="modalPengembalian" name="modalPengembalian">
            @csrf
          <input type="text" name="id_barang" id="id_barang_pengembalian" hidden>
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang_kembali" class="form-control" readonly></input>
          </div>
          <div class="form-group">
            <label for="sow">Jumlah Stock</label>
            <input name="qty" id="qty_kembali" type="number" class="form-control" readonly>
          </div>
          <div class="form-group margin-left-right">
              @if ($message = Session::get('warning'))
              <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{{ $message }}</strong>
              </div>
              @endif
            </div>
          <div class="form-group">
            <label>Masukkan kebutuhan</label><br>
            <input type='button' value='-' class='qtyminus' field='quantity' />
            <input type='text' name='quantity' id="quantity_kembali" value='0' class='qty' />
            <input type='button' value='+' class='qtyplus' field='quantity' />
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--modal accept-->
<div class="modal fade" id="accept_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modal_accept" name="modal_accept">
            @csrf
          <input type="text" name="id_kat_accept" id="id_kat_accept" hidden>
          <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
          <input type="text" name="nik_peminjam_accept" id="nik_peminjam_accept" hidden>
          <input type="text" name="qty_akhir" id="qty_akhir" hidden>
          <input type="text" name="location_update" id="location_update" hidden>
          <div class="form-group">
          	<!-- <h6 style="text-align: center;">Please Select Serial Number <input type="text" style="border-style: none;" id="qty" readonly></h6> -->
          	<h4>Please Select Serial Number: <input type="" class="qtysn" name="qty" id="qty" style="border-style: none;width: 250px;" readonly></h4><br>
          	<select class="form-control detail-product" name="detail_product[]" id="detail_product" placeholder="Select Serial Number" multiple="multiple" style="width:100%">
          	</select>
            <select class="form-control hidden" name="id_barang_accept[]" id="id_barang_accept" multiple="multiple" style="width: 270px;" hidden>
            </select>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <input type="button" value="YES" class="btn btn-sm btn-success float-right " data-dismiss="modal" id="btn_submit" style="width: 70px;">
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!-- modal delete -->
<div class="modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modalDelete" name="modalDelete">
            @csrf
          <input type="text" name="id_barang_delete" id="id_barang_delete" hidden>
          <input type="text" name="id_kat_delete" id="id_kat_delete" hidden>
          <!-- <input type="text" name="id_transaction_update" id="id_transaction_update" hidden> -->
          <div class="form-group">
            <h4 style="text-align: center;"><b>Are you sure to delete product?</b></h4>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="button" class="btn btn-success btn-sm btn-delete-asset"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--REJECT-->
<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modalReject" name="modalReject">
            @csrf
          <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
          <input type="text" name="id_kat" id="id_kat" hidden>
          <input type="text" name="nik_peminjam_reject" id="nik_peminjam_reject" hidden>
          <input type="text" name="qty_total_reject" id="qty_total_reject" hidden>
          <select class="form-control hidden" name="id_barang_reject[]" id="id_barang_reject" multiple="multiple" style="width: 270px;">
              <!-- <option>-- Select Serial Number --</option> -->
          </select>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>Are you sure to reject?</b></h3>
          </div>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="reject_note" id="" required></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="button" class="btn btn-danger btn-sm btn-reject-modal"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--ambil-->
<!-- <div class="modal fade" id="ambil_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('ambil_pinjam')}}" id="modalProgressambil" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_ambil" id="id_barang_ambil" hidden>
          <input type="text" name="id_transaction_ambil" id="id_transaction_ambil" hidden>
          <div class="form-group">
            <h3 style="text-align: center;"><b>PICK UP NOW!</b></h3>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div> -->
  
<!--kembali-->
<div class="modal fade" id="kembali_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modalKembali" name="modalKembali">
            @csrf
          <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" hidden>
          <input type="text" name="id_kat_kembali" id="id_kat_kembali" hidden>
          <input type="text" name="total_qty_kembali" id="total_qty_kembali" hidden>
          <!-- <input type="text" name="id_barang_kembali" id="id_barang_kembali"> -->
          <select class="form-control hidden" name="id_barang_kembali[]" id="id_barang_kembali" multiple="multiple" style="width: 270px;" hidden>
              <!-- <option>-- Select Serial Number --</option> -->
          </select>
          <div class="form-group">
            <h3 style="text-align: center;"><b>RETURN NOW!</b></h3>
            <label>Lokasi Pengembalian</label>
            <textarea id="location_return" name="location_return" class="form-control"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="button" class="btn btn-danger btn-sm btn-kembali-modal"><i class="fa fa-check"></i>&nbsp YES</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reject_note_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="" id="modalNote" name="modalNote">
            @csrf
          <input type="text" name="id_barang_reject_note" id="id_barang_reject_note" hidden>
          <input type="text" name="id_transaction_reject_note" id="id_transaction_reject_note" hidden>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="reject_note" id="reject_note" readonly></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCLOSE</button>
              <!-- <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button> -->
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal-sn" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content modal-sm">
        <div class="modal-body">
          <div class="form-group">
            <form name="sn-modal" id="sn-modal">
              <label>Add Serial Number</label>
              @csrf
                <input type="" class="id_barangs" name="sn_barang" id="sn_barang" hidden>
                <h6>Masukkan Serial Number sebanyak barang </h6>
                <!-- <h6>Stock tanpa SN : <input class="qtys" name="qty" id="qty" style="border-style: none;font-style: bold;font-size:14px;" readonly></h6><br> -->
                <textarea class="form-control serial_number" style="resize: none" rows="10" name="serial_number" id="serial_number"></textarea>
            </form>
          </div>
        </div>
        <div class="modal-footer">
            <input class="btn btn-sm btn-primary float-right btn-sn" id="btn-sn" type="button" value="submit" disabled>
        </div>
      </div>
    </div>
</div>
@endsection

@section('scriptImport')
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> -->

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    var accesable = @json($feature_item);

    //datatable kategori
    $("#datatable3").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('/getKategori')}}",
        },
        "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            }
          },
          { "data": "acronym"},
          { "data": "kategori" },  
          { "data": "qty" }, 
          { "data": "description" },   
          { 
            render: function ( data, type, row, meta ) {
              return "<button class='btn btn-sm btn-primary btn-icon edit_kategori' value='"+row.id_kat+"' margin-right:5px'><i class='fa fa-pencil'></i></button> <button class='btn btn-sm btn-danger btn-icon hapus_kategori' value='"+row.id_kat+"'><i class='fa fa-trash'></i></button>";

            }
          },
        ],
        "scrollX": false,
        "ordering": false,
        "processing": true,
        "paging": true,
        "pageLength":25,
    })

    //datatable list asset
    var groupColumn = 0;
    var table = $("#data_Table").DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/getAssetTech')}}",
        },
        "columns": [
          { "data": "kategori" }, 
          {
            "data": "code_asset"
          }, 
          { "data": "nama_barang" },  
          { "data": "serial_number" },
          { "data": "description" },
          { "data": "location"},
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'AVAILABLE') {
                return "<span class='label label-warning'>AVAILABLE</span>";
              }else{
                return "<span class='label label-danger'>UNAVAILABLE</span>";
              }
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'AVAILABLE') {
                if (row.status_pinjam == 'PERNAH') {
                  return "<a href='{{url('/detail_asset_peminjaman')}}/"+row.id_barang+"'><button style='width:32.29px' class='btn btn-info btn-sm btn-icon'><i class='fa fa-info'></i></button></a> <button class='btn btn-sm btn-primary btn-icon edit_asset' id='btn_edit' value='"+row.id_barang+"' margin-right:5px'><i class='fa fa-pencil'></i></button> <button class='btn btn-sm btn-danger btn-icon hapus_asset' value='"+row.id_barang+"'><i class='fa fa-trash'></i></button>";
                }else{
                  return "<button style='width:32.29px' class='btn btn-info btn-sm btn-icon disabled' style='cursor:not-allowed'><i class='fa fa-info'></i></button> <button class='btn btn-sm btn-primary btn-icon edit_asset' value='"+row.id_barang+"' margin-right:5px'><i class='fa fa-pencil'></i></button> <button class='btn btn-sm btn-danger btn-icon hapus_asset' value='"+row.id_barang+"'><i class='fa fa-trash'></i></button>";
                }
              }else{
                if (row.status_pinjam == 'PERNAH') {
                  return "<a href='{{url('/detail_asset_peminjaman')}}/"+row.id_barang+"'> <button style='width:32.29px' class='btn btn-info btn-sm btn-icon'><i class='fa fa-info'></i></button></a> <button class='btn btn-sm btn-primary edit_asset btn-icon' value='"+row.id_barang+"'><i class='fa fa-pencil'></i></button> <button class='btn btn-sm btn-danger btn-icon' disabled><i class='fa fa-trash'></i></button>"
                }else{
                  return "<button style='width:32.29px' class='btn btn-info btn-sm btn-icon disabled'><i class='fa fa-info'></i></button> <button class='btn btn-sm btn-primary edit_asset btn-icon' value='"+row.id_barang+"'><i class='fa fa-pencil'></i></button> <button class='btn btn-sm btn-danger btn-icon' disabled><i class='fa fa-trash'></i></button>"
                }
              }
            }
          },      
        ],
        "order": [[ groupColumn, 'asc' ]],
        "scrollX": false,
        "paging": true,
        "pageLength":25,
        "columnDefs": [
          { "visible": false, "targets": groupColumn }
        ],
        "drawCallback": function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
   
        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
          if ( last !== group ) {
            $(rows).eq( i ).before(
              '<tr class="group">' + 
              ' <th>' + 
                  '<span class="group">' +
                    group + 
                '</th>' +
              '</tr>'
            );
            last = group;
          }
        });
      }
    })

    function changeTabs(value){
      if (value == 'occurance') {
        $('#datatable1').DataTable().destroy();
        $('#datatable1').empty() 
        $('#datatable1').DataTable({
          "ajax":{
            "type":"GET",
            "url":"{{url('/getAssetTransactionModerator')}}",
            "data":{
              status:['PENDING','ACCEPT']
            }
          },
          "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            },
             "title":"No."
          },
          {
            render: function ( data, type, row, meta ) {
              return  row.no_peminjaman;
            },
             "title":"No Transaksi"
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  row.kategori
              }else {
                return  row.kategori
              }
            },
             "title":"Kategori"
          },  
          { "data": "qty_akhir", "title":"Qty" }, 
          { "data": "keterangan", "title":"Keterangan" }, 
          { "data": "keperluan", "title":"Keperluan"}, 
          { "data": "name","title":"Nama Peminjam"}, 
          { "data": "tgl_peminjaman","title":"Tgl Peminjaman"}, 
          // { "data": "tgl_pengembalian" }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status != 'RETURN') {
                return  " - ";
              }else{
                return  row.updated_at.substr(0,10);
              }
            },
            "title":"Tgl Pengembalian (Aktual)"
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  "<span class='label label-warning'>PENDING</span>";
              }else if (row.status == 'ACCEPT') {
                return "<span class='label label-success'>SUDAH DIAMBIL</span>"
              }
            },
            "title":"Status"
          }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  "<button class='btn btn-sm btn-success btn_accept' style='margin-right:5px' value="+row.id_transaction+" id='btn_accept' value=''>ACCEPT</button><button class='btn btn-sm btn-danger btn_reject' id='btn_reject' value="+row.id_transaction+">REJECT</button>";
              }else if (row.status == 'ACCEPT') {
                return "<button class='btn btn-sm btn-primary' style='margin-right:5px' onclick='btn_detail("+row.no_peminjaman+")'>INFO</button><button class='btn btn-sm btn-danger btn_kembali' value="+row.id_transaction+" id='btn_kembali'> RETURN</button>"
              }
            },
            "title":"Action"
          },      
        ],
        "scrollX": false,
        "ordering": false,
        "processing": true,
        "paging": true,
        "pageLength":25,
        });
      }else{
        $('#datatable1').DataTable().destroy();
        $('#datatable1').empty() 
        $('#datatable1').DataTable({
          "ajax":{
            "type":"GET",
            "url":"{{url('/getAssetTransactionModerator')}}",
            "data":{
              status:['RETURN','REJECT']
            }
          },
          "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            },
            "title":"No."
          },
          {
            render: function ( data, type, row, meta ) {
              return  row.no_peminjaman;
            },
            "title":"No. Transaksi"
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  row.kategori
              }else {
                return  row.kategori
              }
            },
            "title":"Kategori"
          },  
          { "data": "qty_akhir","title":"Qty" }, 
          { "data": "keterangan", "title":"Keterangan"}, 
          { "data": "keperluan", "title":"Keperluan"}, 
          { "data": "name","title":"Nama Peminjam" }, 
          { "data": "tgl_peminjaman","title":"Tgl Peminjaman"}, 
          // { "data": "tgl_pengembalian" }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status != 'RETURN') {
                return  " - ";
              }else{
                return  row.updated_at.substr(0,10);
              }
            },
            "title":"Tgl Pengembalian (Aktual)"
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'REJECT') {
                return "<span class='label label-danger' style='cursor: zoom-in;' data-target='#reject_note_modal' data-toggle='modal'> REJECTED</span>"
              }else if (row.status == 'RETURN') {
                return "<span class='label label-success' >RETURNED</span>"
              }
            },
            "title":"Status"
          }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'KEMBALI') {
                return "<button class='btn btn-sm btn-primary' style='margin-right:5px' onclick='btn_detail("+row.no_peminjaman+")'>INFO</button><button class='btn btn-sm btn-success disabled'>ACCEPT</button><button class='btn btn-sm btn-danger disabled'>REJECT</button>"
              }else{
                return "<button class='btn btn-sm btn-primary' style='margin-right:5px' onclick='btn_detail("+row.no_peminjaman+")'>INFO</button>"
              }

            },
            "title":"Action"
          },      
        ],
        "scrollX": false,
        "ordering": false,
        "processing": true,
        "paging": true,
        "pageLength":25,
        });
      }
    }

    //datatble peminjaman moderator
    var table1 = $("#datatable1").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('/getAssetTransactionModerator')}}",
          "data":{
            status:['PENDING','ACCEPT']
          }
        },
        "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  row.no_peminjaman;
              }else if (row.status == 'REJECT') {
                return  "<span style='cursor:not-allowed;color:red' id='btn_detail' name='btn_detail'>"+ row.no_peminjaman +"</span>";
              }else{
                return  "<span class='btn_detail' id='btn_detail' name='btn_detail' data-value='"+row.no_peminjaman+"'> "+ row.no_peminjaman +"</span>";
              }
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  row.kategori
              }else {
                return  row.kategori
              }
            }
          },  
          { "data": "qty_akhir" }, 
          { "data": "keterangan" }, 
          { "data": "keperluan" }, 
          { "data": "name" }, 
          { "data": "tgl_peminjaman" }, 
          // { "data": "tgl_pengembalian" }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status != 'RETURN') {
                return  " - ";
              }else{
                return  row.updated_at.substr(0,10);
              }
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  "<span class='label label-warning'>PENDING</span>";
              }else if (row.status == 'ACCEPT') {
                return "<span class='label label-success'>SUDAH DIAMBIL</span>"
              }
            }
          }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  "<button class='btn btn-sm btn-success btn_accept' style='margin-right:5px' value="+row.id_transaction+" id='btn_accept' value=''>ACCEPT</button><button class='btn btn-sm btn-danger btn_reject' id='btn_reject' value="+row.id_transaction+">REJECT</button>";
              }else if (row.status == 'ACCEPT') {
                return "<button class='btn btn-sm btn-primary' style='margin-right:5px' onclick='btn_detail("+row.no_peminjaman+")'>INFO</button><button class='btn btn-sm btn-danger btn_kembali' value="+row.id_transaction+" id='btn_kembali'> RETURN</button>"
              }

            }
          },      
        ],
        "scrollX": false,
        "ordering": false,
        "processing": true,
        "paging": true,
        "pageLength":25,    
      })

      if (!(accesable.includes('col_action'))) {
        // btn action di selain mas franky
        var column1 = table.column(7);
        column1.visible(!column1.visible() );

        var column2 = table1.column(10);
        column2.visible(!column2.visible() );
      }


    $(document).ready(function(){
      if (window.location.href.split("/")[3].split("#")[1] == 'list_asset') {
        $("#list_asset").addClass("active")
      }else if (window.location.href.split("/")[3].split("#")[1] == 'kategori_tab') {
        $("#kategori_tab").addClass('active')
      }else if (window.location.href.split("/")[3].split("#")[1] == 'peminjaman_tab'){
        $("#peminjaman_tab").addClass('active')
        $("#peminjaman_asset").closest('li').addClass('active')
      }else{
        $("#kategori_tab").addClass('active')
        $("#kategori-tab").closest('li').addClass('active')
      }

      accesable.forEach(function(item,index){
        $("#" + item).show()
      })

      console.log(accesable)
    })

    //datatable peminjaman staff
    $('#datatable2').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/getAssetTransaction')}}",
        },
        "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  row.no_peminjaman;
              }else if (row.status == 'REJECT') {
                return  "<span style='cursor:not-allowed;color:red' id='btn_detail' name='btn_detail'>" + row.no_peminjaman +"</span>";
              }else{
                return  "<span class='btn_detail' style='cursor:pointer;color:blue' onclick='btn_detail("+row.no_peminjaman+")' id='btn_detail' name='btn_detail' data-value='"+row.no_peminjaman+"'> "+ row.no_peminjaman +"</span>";
              }
            }
          },  
          { "data": "qty_akhir" }, 
          { "data": "keterangan" }, 
          { "data": "keperluan" }, 
          { "data": "tgl_peminjaman" }, 
          { "data": "tgl_pengembalian" }, 
          {
            render: function ( data, type, row, meta ) {
              if (row.status == 'PENDING') {
                return  "<span class='label label-warning'>PENDING</span>";
              }else if (row.status == 'ACCEPT') {
                return "<span class='label label-success'>ACCEPTED</span>"
              }else if (row.status == 'REJECT') {
                return "<span class='label label-danger' style='cursor: zoom-in;' data-target='#reject_note_modal' data-toggle='modal'> REJECTED</span>"
              }else if (row.status == 'AMBIL') {
                return "<span class='label label-success' >SUDAH DI AMBIL</span>"
              }else if (row.status == 'RETURN') {
                return "<span class='label label-success' >RETURNED</span>"
              }
            }
          },    
        ],
        "scrollX": false,
        "ordering": false,
        "processing": true,
        "paging": true,
        "pageLength":25,
    })

    //datatable log aktivitas
    $('#dataTableLog').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/getLogAssetTech')}}",
        },
        "columns": [
          {
            render: function ( data, type, row, meta ) {
               return  meta.row+1;
            }
          },
          { "data": "keterangan" },  
          {
            render: function ( data, type, row, meta ) {
               return  moment(row.created_at).format('MMMM Do YYYY, h:mm:ss a');
            }
          },
          {
            render: function ( data, type, row, meta ) {
              if (row.nik == 'System') {
                return 'System';
              }else{
                return row.name;
              }
            }
          }, 

        ],
        "scrollX": false,
        "ordering": true,
        "processing": true,
        "paging": true,
        "pageLength":10,
    })

    //ajax add kategori    
    $(document).on('click', ".btn-add-kategori", function(e){
      Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's sending..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"GET",
        url:'/store_kategori_asset',
        data:$('#modalAddKategori').serialize(),
        success: function(result){
          Swal.fire({
              title: "Success!",
              text:  "You have been Added Kategori.",
              type: "success",
              timer: 3000,
              showConfirmButton: false
            });
          setTimeout(function() {
            window.location.href = "https://dev-app.sifoma.id/asset_pinjam#kategori"
            location.reload()
          }, 2000);  
        },
      });
    });

    //ajax add asset kategori
    $(document).on('click', ".btn-Add-Asset", function(e){
      Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's sending..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"POST",
        url:'/store_asset',
        data:$('#modalAddAsset').serialize(),
        success: function(result){
          Swal.fire({
              title: "Success!",
              text:  "You have been Added Asset.",
              type: "success",
              timer: 3000,
              showConfirmButton: false
            });
          setTimeout(function() {
            location.href = "https://dev-app.sifoma.id/asset_pinjam#list_asset"
            location.reload();
          }, 2000);  
        },
      });
    });

    //ajax edit asset kategori
    $(document).on('click',".edit_asset",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/getAsset',
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $.each(result[0], function(key, value){
                $('#id_barang_edit').val(value.id_barang);
                $('#edit_nama').val(value.nama_barang);
                $('#serial_number_edit').val(value.serial_number);
                $('#keterangan_edit').val(value.description);  
                $("#lokasi_edit").val(value.location)
                if(value.status == "AVAILABLE"){
                  if(!$("#switch-checkbox").prop('checked')){
                    $("#switch-checkbox").click()
                  }
                } else {
                  if($("#switch-checkbox").prop('checked')){
                    $("#switch-checkbox").click()
                  }
                }
              })        
            },
        });

        $("#modaledit").modal("show");

        $('.cb-value').click(function() {
          var mainParent = $(this).parent('.switch');
          if($(mainParent).find('input.cb-value').is(':checked')) {
            console.log("available")
            $("#switch-checkbox").val("AVAILABLE");
            $(".label-available").addClass("available-on");
            $(".label-available").removeClass("color-switch");
            $(".label-unavailable").addClass("color-switch");
          } else {
            $("#switch-checkbox").val("UNAVAILABLE");
            $(".label-unavailable").addClass("available-off");
            $(".label-unavailable").removeClass("color-switch");
            $(".label-available").addClass("color-switch");
            console.log("unavailable")
          }

        })
    });

    $(document).on('click', ".btn-update-asset", function(e){
      Swal.fire({
          title: 'Please Wait..!',
          html: "<p style='text-align:center;'>It's sending..</p>",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          onOpen: () => {
            Swal.showLoading()
          }
        })
      $.ajax({
        type:"GET",
        url:'/edit_pinjam',
        // data:$('#modalEdit').serialize(),
        data:{
          id_barang_edit:$("#id_barang_edit").val(),
          edit_nama:$("#edit_nama").val(),
          serial_number_edit:$("#serial_number_edit").val(),
          keterangan_edit:$("#keterangan_edit").val(),
          status_asset:$("#switch-checkbox").val(),
          lokasi_edit:$("#lokasi_edit").val(),
        },
        success: function(result){
            Swal.fire({
                title: "Success!",
                text:  "You Have been Update Asset",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
          setTimeout(function() {
            location.reload();
          }, 1000);  
        },
      });
    });

    //ajax delete asset
    $(document).on('click',".hapus_asset",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/getAsset',
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $.each(result[0], function(key, value){  
                $('#id_barang_delete').val(value.id_barang);
                $('#id_kat_delete').val(value.id_kat)
              });              
            },
        });

        $("#delete_modal").modal("show");
    });

    $(document).on('click', ".btn-delete-asset", function(e){
      $("#delete_modal").modal("hide");
      Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's sending..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"GET",
        data:$('#modalDelete').serialize(),
        url:"{{url('/delete_asset')}}"+"/"+ $('#id_barang_delete').val(),
        success: function(result){
            Swal.fire({
                title: "Deleted!",
                text:  "You Have been delete Asset",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
          setTimeout(function() {
            location.reload();
          }, 1000);  
        },
      })
    })

    //ajax update/delete kategori
    $(document).on('click',".edit_kategori",function(e) {
      $.ajax({
        type:"GET",
        url:'/asset_pinjam/getKategoriById',
        data:{
          id_kat:this.value
        },
        success: function(result){
            $("#kategori_name").text(result.data.kategori)
            $("#acronym").text(result.data.acronym)
            $("#description_edit").text(result.data.description)
            $("#update_kategori").click(function(){
              $("#update_kategori").attr('onclick',updateKategori(result.data.id_kat))
            })
        }
      });
      $("#edit_kategori").modal('show')
    })

    function updateKategori(id_kat){
      Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's sending..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"POST",
        url:'/asset_pinjam/updateKategori',
        data:{
          id_kat:id_kat,
          _token: "{{ csrf_token() }}",
          acronym:$("#acronym").val(),
          description:$("#description_edit").val()
        },
        success: function(result){
            Swal.fire({
                title: "Success!",
                text:  "Successfully Request.",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
          setTimeout(function() {
            location.reload();
            $("#edit_kategori").modal('hide')
          }, 1000);  
        },
      });
    }

    $(document).on('keyup keydown', "input[id^='quantity']", function(e){
      var qty_before  = $(".qtys").val();
      // console.log(qty_before);
          if ($(this).val() > parseFloat(qty_before)
              && e.keyCode != 46
              && e.keyCode != 8 
             ) {
             e.preventDefault();     
             $(this).val(qty_before);
          }
    });

    //ajax peminjaman
    $(".btn-add-peminjaman").click(function(){
      $.ajax({
        type:"GET",
        url:'/getKategori2',
        success: function(result){
          
            $('#kategori3').html(append)
              var append = "<option>-- Select Option --</option>";
              $.each(result, function(key, value){
                append = append + "<option value='"+value.id_kat+"'>" + value.kategori +  " (" + value.qty + ") "+ "</option>";
              });
            $('#kategori3').html(append);
          
        }
      });

      $("#peminjaman_modal").modal("show");
    })
    
    $(document).on('click', ".btn-submit-peminjaman", function(e){      if($("#tgl_peminjaman").val() == "" || $("#tgl_kembali").val() == "" || $("#quantity").val() == "" || $("#kategori3").val() == ""){
        $("#alertPeminjaman").show()
        $("#textAlertPeminjaman").text("Please fill all inputs !!!")
      }else{
        $("#peminjaman_modal").modal("hide");
        Swal.fire({
          title: 'Please Wait..!',
          html: "<p style='text-align:center;'>It's sending..</p>",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          onOpen: () => {
            Swal.showLoading()
          }
        })
        $.ajax({
          type:"POST",
          url:'/update_asset',
          data:$('#modalPeminjaman').serialize(),
          success: function(result){
              Swal.fire({
                  title: "Success!",
                  text:  "Successfully Request.",
                  type: "success",
                  timer: 2000,
                  showConfirmButton: false
              });
            setTimeout(function() {
              window.location.href = "https://dev-app.sifoma.id/asset_pinjam#peminjaman_tab"   
              location.reload()           
            }, 1000);  
          },
        });
      }
    });

    //ajax peminjaman accept
    $(document).on('click',".btn_accept",function(e) { 
        // console.log(this.value);
        id_transaction = this.value;
        $.ajax({
          type:"GET",
          url:'/getdetailAsset',
          data:{
            id_transaction:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $('#id_transaction_update').val(id_transaction);
              $('#nik_peminjam_accept').val(value.nik_peminjam);
              $('#id_kat_accept').val(value.id_kat);
              $('#qty_akhir').val(value.qty_akhir);
              $('#qty').val(value.qty_akhir);
              $('#location_update').val(value.keterangan)

              $("#detail_product").change(function(){
                countSelect = $("#detail_product option:selected").length
                var count = value.qty_akhir - countSelect
                if (count < 0) {
                  $("#qty").val(count + " (Kurangi Pilihan Item Anda!)").css("color", "red");
                }else{
                  $("#qty").val(count).css("color", "black");
                }
              })
            })
          },
        })


        $.ajax({
            type:"GET",
            url:'/dropdownSerialNumberAsset?kategori=',
            data:{
              id_transaction:this.value,
            },
            success: function(result){

              $(".detail-product").html(append)
              var append = append + "<option disabled> Select Serial Number"  +"</option>";

              $.each(result[0], function(key, value){
                  // append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";   
                append = append + "<option value="+value.id_barang+">" + value.nama_barang + " - " + value.serial_number +"</option>";
                // console.log(value.id_barang);
              });

              $(".detail-product").html(append);

            },
        });

        $("#accept_modal").modal("show");
    });

    $(document).on('click', "#btn_submit", function(e){
      var qty           = $(".qtysn").val();
      var sn            = $(".detail-product").val();
      var total_sn      = sn.length;
      /*console.log(qty);
      console.log(qty);
      console.log(sn);
      console.log(sn);*/

      if (qty == 0) {
        Swal.fire({
          title: 'Please Wait..!',
          html: "<p style='text-align:center;'>It's sending..</p>",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          onOpen: () => {
            Swal.showLoading()
          }
        })
        $.ajax({
          type:"POST",
          url:'/accept_pinjam',
          data:$('#modal_accept').serialize(),
          success: function(result){
              Swal.fire({
                  title: "Success!",
                  text:  "You have been Accepted the progress.",
                  type: "success",
                  timer: 2000,
                  showConfirmButton: false
              });
            setTimeout(function() {
              location.reload();
            }, 1000);  
          },
        });
      } else if (sn == "") {
        alert('Kamu belum memilih SN !')
      } else {
        alert('Jumlah tidak sesuai !')
      }
    });

    //ajax peminjaman kembali
    $(document).on('click',"#btn_kembali",function(e) { 
        var id_transaction = this.value;
        $.ajax({
          type:"GET",
          url:'/getdetailAsset',
          data:{
            id_transaction:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $('#id_transaction_kembali').val(id_transaction);
              $('#id_kat_kembali').val(value.id_kat);
              $('#total_qty_kembali').val(value.qty_akhir);
            })
          },
        })
      
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/dropdownid_barang?kategori=',
            data:{
              id_transaction:this.value,
            },
            success: function(result){
              $("#id_barang_kembali").html(append)
              var append = append + "<option>"  +"</option>";

              $.each(result[0], function(key, value){
                  // append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";   
                  append = append + "<option value="+value.id_barang+" selected>"+ value.id_barang +"</option>";
                // console.log(value.id_barang);
              });

              $("#id_barang_kembali").html(append);

            },
        });

        $("#kembali_modal").modal("show");
    });

    $(document).on('click', ".btn-kembali-modal", function(e){
      if ($("#location_return").val() == "") {
        alert("Please fill your return location!")
        $("#kembali_modal").modal("hide");
      }else{
        $("#kembali_modal").modal("hide");
        Swal.fire({
          title: 'Please Wait..!',
          html: "<p style='text-align:center;'>It's sending..</p>",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          onOpen: () => {
            Swal.showLoading()
          }
        })
        $.ajax({
          type:"POST",
          url:'/kembali_pinjam',
          data:$('#modalKembali').serialize(),
          success: function(result){
            Swal.fire({
                title: "Success!",
                text:  "Asset has benn Returned.",
                type: "success",
                timer: 1000,
                showConfirmButton: false
              });
            setTimeout(function() {
              location.reload();
            }, 2000);  
          },
        });
      }
      
    });
    

    //ajax peminjaman reject
    $(document).on('click',".btn_reject",function(e) { 
        // console.log(this.value);
        // $.ajax({
        //     type:"GET",
        //     url:'/dropdownid_barang_reject?kategori=',
        //     data:{
        //       id_transaction:this.value,
        //     },
        //     success: function(result){
        //       console.log(result)
        //       $("#id_barang_reject").html(append)
        //       var append = append + "<option>"  +"</option>";

        //       $.each(result[0], function(key, value){
        //           // append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";   
        //           append = append + "<option value="+value.id_barang+" selected>"+ value.id_barang +"</option>";
        //           $('#id_barang_reject').val(value.id_barang);
        //         // console.log(value.serial_number);
        //       });
        //     },
        // });

        id_transaction = this.value;
        $.ajax({
          type:"GET",
          url:'/getdetailAsset',
          data:{
            id_transaction:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $('#id_transaction_reject').val(id_transaction);
              $('#id_kat').val(value.id_kat);
              $('#nik_peminjam_reject').val(value.nik_peminjam);
              $('#qty_total_reject').val(value.qty_akhir);   
              // $("#id_barang_reject").html(append);           
            })
          },
        })

        $("#reject_modal").modal('show');
    });

    $(document).on('click', ".btn-reject-modal", function(e){
      Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's sending..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"POST",
        url:'/reject_pinjam',
        data:$('#modalReject').serialize(),
        success: function(result){
            Swal.fire({
                title: "Success!",
                text:  "You have been rejected the progress.",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
          setTimeout(function() {
            location.reload();
          }, 1000);  
        },
      });
    });
    //////////////////////////////

    $(".detail-product").select2({
      closeOnSelect : false,
    });
    
    $('#kategori2').select2();

    $('#kategori3').select2();

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $(document).on('change',"select[id^='kategori3']",function(e) {
      var kategori = $('#kategori3').val();
      // console.log(kategori);

         $.ajax({
          type:"GET",
          url:'/getidkategori',
          data:{
            kategori:this.value,
          },
          success: function(result,qty){
          	$.each(result[0], function(key, value){
          		$(".qtys").val(value.qty);
            });
          }
        });
    });

    $('#tgl_peminjaman').datepicker({
        onSelect: function(dateText, inst){
            $('#tgl_kembali').datepicker('option', 'minDate', new Date(dateText));
        },
        autoclose:true,
        todayHighlight: true
    });

    $('#tgl_kembali').datepicker({
        onSelect: function(dateText, inst){
            $('#tgl_peminjaman').datepicker('option', 'maxDate', new Date(dateText));
        },
        autoclose:true,
        todayHighlight: true
    });


    function btn_detail(n){
      console.log(n)
      $.ajax({
            type:"GET",
            url:'/getdetailAssetPeminjaman',
            data:{
              id_transaction:n,
            },
            success: function(result){
              $.each(result[0], function(key, value){  
                $('#id_transaksi').val(value.id_transaction);
                $('#nama_peminjam').val(value.name);
                $('#no_peminjaman').val(value.no_peminjaman);
                $('#tgl_pinjam').val(value.tgl_peminjaman);
                $('#tgl_kembalian').val(value.tgl_pengembalian);
                if (value.status == 'PENDING' || value.status == 'ACCEPT' || value.status == 'REJECT') {
                  $('#tgl_kembalian_aktual').val('-');
                }else if (value.status == 'REJECT') {
                  $("#keterangan_reject").show()
                  $("#inpKeterangan_reject").val(note)
                }else{
                  $('#tgl_kembalian_aktual').val(value.updated_at.substr(0,10));
                }
              });              
            },
        });
        
        $.ajax({
            type:"GET",
            url:'{{url("/dropdownsn")}}',
            data:{
              id_transaction:n,
            },
            success: function(result){
              $("#mytable").empty();
              var table = "";

              $.each(result[0], function(key, value){
                table = table + '<tr>';
                table = table + '<th>' +value.code_asset+ '</th>';
                table = table + '<td>' +value.nama_barang+ '</td>';
                table = table + '<td style="color:orange">' +value.serial_number+ '</td>';
                table = table + '</tr>';
              });

              $("#mytable").html(table);

            },
        });

        $("#serial_number").modal("show");
    }

    $('a.reload').click(function() {
        window.location.reload();                       
    });

    $(document).on('change',"select[class^='detail-product']",function(e) {
      var id_barang = $('.detail-product').val();
      // console.log(id_barang);

         /*$.ajax({
          type:"GET",
          url:'/getidbarangaccept',
          data:{
            kategori:this.value,
          },
          success: function(result){
            $("#id_barang_accept").html(append)
              var append = append + "<option>"  +"</option>";

              $.each(result[0], function(key, value){  
                  append = append + "<option value="+value.id_barang+">"+ value.id_barang +"</option>";
              });

            $("#id_barang_accept").html(append);
          }
        });*/
    });

    // $('#myTab .nav-link').click(function(e) {
    //   e.preventDefault();
    //   $(this).tab('show');
    //   if ($(this).tab('show').text() == 'Peminjaman Asset') {
    //     $("#kategori-asset").removeClass('display-block').addClass('display-none');

    //     $("#list-asset").removeClass('display-block').addClass('display-none');

    //     $("#export-excel").removeClass('display-block')
    //                       .addClass('display-none');
    //   }else if ($(this).tab('show').text() == 'List Asset') {
    //     $("#export-excel").removeClass('display-none')
    //                       .addClass('display-block');
    //     $("#list-asset").addClass('display-block')
    //                     .removeClass('display-none');
    //     $("#kategori-asset").removeClass('display-block')
    //                         .addClass('display-none');
    //   }else{
    //     $("#kategori-asset").removeClass('display-none').addClass('display-block');

    //     $("#list-asset").removeClass('display-block').addClass('display-none');

    //     $("#export-excel").removeClass('display-block')
    //                       .addClass('display-none');
    //   }
    //   console.log($(this).tab('show').text())
    // });

    $('#export-excel').click(function(event) {
        $.ajax({
            url: this.href,
            type: 'GET',
            success: function(result) {
              Swal.fire({
                 title: "Success!",
                 text:  "You have been Export to Excel.",
                 type: "success",
                 timer: 3000,
                 showConfirmButton: false
               });
             setTimeout(function() {
               location.reload();
             }, 2000);  
            }
        });
        // $.ajax({
        // url: '@Url.Action("exportExcelTech", "AssetController")',
        // type: "GET",
        // success: function(result){
        // // Swal({
        // //       title: "Success!",
        // //       text:  "You have been Export Excel.",
        // //       type: "success",
        // //       timer: 3000,
        // //       showConfirmButton: false
        // //     });
        // //   setTimeout(function() {
        // //     location.reload();
        // //   }, 2000);  
        // }, 
        // });
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;

      console.log(id)
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');

  </script>
@endsection