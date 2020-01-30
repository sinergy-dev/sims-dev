@extends('template.template_admin-lte')
@section('content')
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

    .modalIcontgl input[type=text]{
      padding-left:115px;
    }

    .modalIcontgl.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIcontgl.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIcontgl{
      position:relative;
    }

   .modalIcontgl i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }
</style>

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
          <li class="nav-item active">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#kategori" role="tab" aria-controls="kategori" aria-selected="false">Kategori</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="home" aria-selected="true">List Asset</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#peminjaman" role="tab" aria-controls="profile" aria-selected="false">Peminjaman Asset</a>
          </li>
          @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
          <div class="pull-right">
          	<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"> </i>&nbsp Add Asset</button>
          	<button class="btn btn-sm btn-primary" style="width: 110px; margin-left: 30px; " data-toggle="modal" data-target="#add_kategori"><i class="fa fa-plus"> </i>&nbsp Add Kategori</button>
          </div>
          @else
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#peminjaman_modal" style="width: 150px;"><i class="fa fa-plus"> </i>&nbsp Peminjaman</button>
          @endif
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered nowrap " id="data_Table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Serial Number</th>
                    <th>Keterangan</th>
                    <th>Total Pinjam</th>
                    <th>Status</th>
                    @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
                    <th>Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($asset2 as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" value="{{$data->id_barang}}" hidden></td>
                    <td>{{$data->kategori}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->serial_number}}</td>
                    <td>{{$data->description}}</td>
                    <td>{{$data->qty_pinjam}}</td>
                    <td>
                      @if($data->status == 'AVAILABLE')
                        <label class="status-open">AVAILABLE</label>
                      @elseif($data->status == 'UNAVAILABLE')
                        <label class="status-tp" style="width: 110px;">UNAVAILABLE</label>
                      @endif
                    </td>
                    @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
                    <td>
                      <a href="{{url('/detail_asset_peminjaman', $data->id_barang) }}"><button class="btn btn-sm" style="width: 80px; height: 25px; background-color: black;color: white ">Detail</button></a>
                      @if($data->status == 'AVAILABLE')
                      <button class="btn btn-sm btn-primary" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#modaledit" onclick="edit_asset('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->serial_number}}', '{{$data->description}}')">Edit</button>
                      <button class="btn btn-sm btn-danger" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#delete_modal" onclick="delete_asset('{{$data->id_barang}}', '{{$data->id_kat}}')">Hapus</button>
                      @else
                      <button class="btn btn-sm btn-primary" style="width: 70px; height: 25px;" disabled>Edit</button>
                      <button class="btn btn-sm btn-danger" style="width: 70px; height: 25px;" disabled>Hapus</button>
                      @endif
                      <!-- {{url('delete_asset', $data->id_barang)}} -->
                    </td>
                    @endif
                  </tr>
                  @endforeach
                  @foreach($asset3 as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" value="{{$data->id_barang}}" hidden></td>
                    <td>{{$data->kategori}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->serial_number}}</td>
                    <td>{{$data->description}}</td>
                    <td>0</td>
                    <td>
                      @if($data->status == 'AVAILABLE')
                        <label class="status-open">AVAILABLE</label>
                      @elseif($data->status == 'UNAVAILABLE')
                        <label class="status-tp" style="width: 110px;">UNAVAILABLE</label>
                      @endif
                    </td>
                    @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
                    <td>
                      <a href="{{url('/detail_asset_peminjaman', $data->id_barang) }}"><button class="btn btn-sm" style="width: 80px; height: 25px; background-color: black;color: white ">Detail</button></a>
                      @if($data->status == 'AVAILABLE')
                      <button class="btn btn-sm btn-primary" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#modaledit" onclick="edit_asset('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->serial_number}}', '{{$data->description}}')">Edit</button>=
                      <button class="btn btn-sm btn-danger" style="width: 70px; height: 25px;" data-toggle="modal" data-target="#delete_modal" onclick="delete_asset('{{$data->id_barang}}', '{{$data->id_kat}}')">Hapus</button>
                      @else
                      <button class="btn btn-sm btn-primary" style="width: 70px; height: 25px;" disabled>Edit</button>
                      <button class="btn btn-sm btn-danger" style="width: 70px; height: 25px;" disabled>Hapus</button>
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
          @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
          <div class="tab-pane fade" id="peminjaman" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered DataTable" id="datatable1" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Keterangan</th>
                    <th>Keperluan</th>
                    <th>Nama Peminjam</th>
                    <th>Tgl Peminjaman</th>
                    <th>Tgl Pengembalian</th>
                    <th>Tgl Pengembalian(Real)</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($assetsd as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    @if($data->status == 'PENDING')
                    <td>{{$data->kategori}}</td>
                    @else
                    <td><button class="btn btn-sm btn-primary" id="btn_detail" name="btn_detail" data-target="#serial_number" data-toggle="modal" value="{{$data->id_transaction}}" onclick="serial_number('{{$data->id_transaction}}', '{{$data->name}}', '{{$data->no_peminjaman}}', '{{$data->tgl_peminjaman}}', '{{$data->tgl_pengembalian}}')">{{$data->kategori}}</button></td>
                    @endif
                    <td>{{$data->qty_akhir}}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->keperluan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->tgl_peminjaman}}</td>
                    <td>{{$data->tgl_pengembalian}}</td>
                    @if($data->status != 'RETURN')
                    <td>-</td>
                    @elseif($data->status == 'RETURN')
                    <td>{!!substr($data->updated_at,0,10)!!}</td>
                    @endif
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px;height: 25px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px;" onclick="reject('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'AMBIL')
                       <label class="status-lose" style="width: 150px;background-color: #7735a3;height: 25px">SUDAH DI AMBIL</label>
                      @elseif($data->status == 'RETURN')
                       <label class="status-win" style="width: 90px;height: 25px">RETURNED</label>
                      @endif
                    </td>
                    <td>
                    @if($data->status == 'PENDING' || $data->status == 'ACCEPT')
                    <input type="" name="" value="{{$data->id_kat}}" hidden>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-md btn-success" id="btn_accept" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaction}}', '{{$data->nik_peminjam}}', '{{$data->id_kat}}', '{{$data->qty_akhir}}')" value="{{$data->id_kat}}">ACCEPT</button>
                      <button class="btn btn-md btn-danger" id="btn_reject" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaction}}', '{{$data->id_kat}}', '{{$data->nik_peminjam}}', '{{$data->qty_akhir}}')" value="{{$data->id_transaction}}"><i hidden>{{$data->id_transaction}}</i>REJECT</button>
                      @elseif($data->status == 'ACCEPT')
                      <button class="btn btn-md btn-danger" id="btn_kembali" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_transaction}}', '{{$data->id_kat}}', '{{$data->qty_akhir}}')" value="{{$data->id_transaction}}" style="text-align: center;width: 125px"><i hidden>{{$data->id_transaction}}</i> KEMBALI</button>
                      @else
                      <button class="btn btn-md btn-success disabled">ACCEPT</button>
                      <button class="btn btn-md btn-danger disabled">REJECT</button>
                      @endif
                    @else
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
          <div class="tab-pane fade" id="peminjaman" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered DataTable" id="datatable2" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Keterangan</th>
                    <th>Keperluan</th>
                    @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
                    <th>Nama Peminjam</th>
                    @endif
                    <th>Tgl Peminjaman</th>
                    <th>Tgl Pengembalian</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($pinjaman as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    @if($data->status == 'PENDING')
                    <td>{{$data->kategori}}</td>
                    @else
                    <td><button class="btn btn-sm btn-primary" id="btn_detail" name="btn_detail" data-target="#serial_number" data-toggle="modal" value="{{$data->id_transaction}}" onclick="serial_number('{{$data->id_transaction}}', '{{$data->name}}', '{{$data->no_peminjaman}}', '{{$data->tgl_pengembalian}}', '{{$data->tgl_pengembalian}}')">{{$data->kategori}}</button></td>
                    @endif
                    <td>{{$data->qty_akhir}}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->keperluan}}</td>
                    @if($data->nik_peminjam != Auth::User()->nik)
                    <td>{{$data->name}}</td>
                    @endif
                    <td>{{$data->tgl_peminjaman}}</td>
                    <td>{{$data->tgl_pengembalian}}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px;" onclick="reject('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'AMBIL')
                       <label class="status-lose" style="width: 150px;background-color: #7735a3">SUDAH DI AMBIL</label>
                      @elseif($data->status == 'RETURN')
                       <label class="status-win" style="width: 90px">RETURNED</label>
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
          @endif
          <div class="tab-pane active" id="kategori" role="tabpanel" aria-labelledby="kategori-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered DataTable" id="datatable3" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Description</th>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1; ?>
                  @foreach($kategori as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->kategori}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->desc}}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          <!-- <div class="tab-pane fade" id="kembaliin" role="tabpanel" aria-labelledby="kembaliin-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered" id="datas" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Action</th>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1; ?>
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" value="{{$data->id_barang}}" hidden></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->description}}</td>
                    <td>
                      <a href="{{url('/detail_asset_peminjaman', $data->id_barang) }}"><button class="btn btn-sm sho">Detail</button></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div> -->
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

<div class="modal fade" id="serial_number" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="modalProgress" name="modalProgress">
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman" readonly>
              <i class="" aria-hidden="true">No Peminjaman &nbsp</i>
            </div>
            <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="nama_peminjam" id="nama_peminjam" readonly>
              <i class="" aria-hidden="true">Nama Peminjam</i>
            </div>
            <div class="form-group modalIcontgl inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="tgl_pinjam" id="tgl_pinjam" readonly>
              <i class="" aria-hidden="true">Tgl Pinjam &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</i>
            </div>
            <div class="form-group modalIcontgl inputIconBg" style="padding-left: 10px">
              <input type="text" class="form-control money" name="tgl_kembali" id="tgl_kembalian" readonly>
              <i class="" aria-hidden="true">Tgl Kembali &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</i>
            </div>
            <legend></legend>
            <table>
              <input type="" name="id_transaksi" id="id_transaksi" hidden>
              <tr class="tr-header">
                <th style="border-left: 10px; width: 270px;">Nama Barang</th>
                <th style="border-left: 10px; width: 270px;">Serial Number</th>
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
          <form method="POST" action="{{url('store_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="">Kategori</label><br>
            <select type="text" class="form-control" style="width: 270px;" placeholder="Select Kategori" name="kategori" id="kategori2" required>
            	<option>Select Kategori</option>
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
            <textarea name="keterangan" id="keterangan" class="form-control" required></textarea>
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

<div class="modal fade" id="add_kategori" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Kategori</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_kategori_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
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

<!--edit asset-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Asset Engineer</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('edit_pinjam')}}" id="modalProgress" name="modalProgress">
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
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required></textarea>
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

<div class="modal fade" id="peminjaman_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peminjaman</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <div class="form-group">
            <label for="sow">Tgl Peminjaman</label>
            <input type="text" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="sow">Tgl Pengembalian</label>
            <input type="text" name="tgl_kembali" id="tgl_kembali" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="">Kategori</label><br>
            <select type="text" class="form-control" style="width: 270px;" placeholder="Select Kategori" name="kategori3" id="kategori3" required>
              <option>Select Kategori</option>
              @foreach($kategori2 as $data)
              <option value="{{$data->id_kat}}">{{$data->kategori}} <i> - {{$data->qty}}</i></option>
              @endforeach
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Nama Barang</label><br>
            <select type="text" class="form-control barangs" style="width: 570px;" placeholder="Select Barang" name="barang" id="barang" required>
              <option>Select Barang</option>
            </select>
          </div> -->
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
            <input type="text" name="qtys" id="qtys" class="qtys" hidden>
            <input type='number' name='quantity' id="quantity" value='0' class="form-control" style="width: 270px;" />
          </div>
          <div class="form-group"> 
            <label>Keperluan</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
          </div>
          <div class="form-group"> 
            <label>Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
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

<div class="modal fade" id="pengembalian" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pengembalian</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <input type="text" name="id_barang" id="id_barang" hidden>
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
              <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--modal accept-->
<div class="modal fade" id="accept_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form id="modal_accept" name="modal_accept">
            @csrf
          <input type="text" name="id_kat_accept" id="id_kat_accept" hidden>
          <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
          <input type="text" name="nik_peminjam_accept" id="nik_peminjam_accept" hidden>
          <input type="text" name="qty_akhir" id="qty_akhir" hidden>
          <div class="form-group">
          	<!-- <h6 style="text-align: center;">Please Select Serial Number <input type="text" style="border-style: none;" id="qty" readonly></h6> -->
          	<h4>Please Select Serial Number: <input type="" class="qtysn" name="qty" id="qty" style="border-style: none;width: 25px;" readonly></h4><br>
          	<select class="form-control detail-product" name="detail_product[]" id="detail_product" multiple="multiple" style="width: 270px;">
          		<option>-- Select Serial Number --</option>
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
          <form method="POST" action="{{url('delete_asset/{id_barang}')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_delete" id="id_barang_delete" hidden>
          <input type="text" name="id_kat_delete" id="id_kat_delete" hidden>
          <!-- <input type="text" name="id_transaction_update" id="id_transaction_update" hidden> -->
          <div class="form-group">
            <h4 style="text-align: center;"><b>Are you sure to delete product?</b></h4>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
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
          <form method="POST" action="{{url('reject_pinjam')}}" id="modalProgress" name="modalProgress">
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
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
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
          <form method="POST" action="{{url('ambil_pinjam')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_ambil" id="id_barang_ambil" hidden>
          <input type="text" name="id_transaction_ambil" id="id_transaction_ambil" hidden>
          <div class="form-group">
            <h3 style="text-align: center;"><b>PICK UP NOW!</b></h3>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
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
          <form method="POST" action="{{url('kembali_pinjam')}}" id="modalProgress" name="modalProgress">
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
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
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
          <form method="POST" action="" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject_note" id="id_barang_reject_note" hidden>
          <input type="text" name="id_transaction_reject_note" id="id_transaction_reject_note" hidden>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="reject_note" id="reject_note" readonly></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCLOSE</button>
              <!-- <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button> -->
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

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <!-- <script src="{{asset("template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script type="text/javascript">

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

    $(".detail-product").select2({
      closeOnSelect : false,
    });

    
    $('#kategori2').select2();

    $('#kategori3').select2();

    $('#data_Table').DataTable({
      pageLength: 25,
    });

    $('#datatable1').DataTable({
      pageLength: 25,
    });

    $('#datatable2').DataTable({
      pageLength: 25,
    });

    $('#datatable3').DataTable({
      pageLength: 25,
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    function pinjam(id_barang,nama_barang,qty){
      $('#id_barang').val(id_barang);
      $('#nama_barang_pinjam').val(nama_barang);
      $('#qty_pinjam').val(qty);
    }

    function id_accept_update(id_transaction,nik_peminjam,id_kat,qty_akhir){
      $('#id_transaction_update').val(id_transaction);
      $('#nik_peminjam_accept').val(nik_peminjam);
      $('#id_kat_accept').val(id_kat);
      $('#qty_akhir').val(qty_akhir);
      $('#qty').val(qty_akhir);
    }

    function id_reject_update(id_transaction,id_kat,nik_peminjam,qty_akhir, id_barang){
      $('#id_transaction_reject').val(id_transaction);
      $('#id_kat').val(id_kat);
      $('#nik_peminjam_reject').val(nik_peminjam);
      $('#qty_total_reject').val(qty_akhir);
      $('#id_barang_reject').val(id_barang);
    }

    function ambil(id_transaction,id_barang){
      $('#id_transaction_ambil').val(id_transaction);
      $('#id_barang_ambil').val(id_barang);
    }

    function serial_number(id_transaction,name,no_peminjaman,tgl_peminjaman,tgl_pengembalian){
      $('#id_transaksi').val(id_transaction);
      $('#nama_peminjam').val(name);
      $('#no_peminjaman').val(no_peminjaman);
      $('#tgl_pinjam').val(tgl_peminjaman);
      $('#tgl_kembalian').val(tgl_pengembalian);
    }

    function kembali(id_transaction,id_kat,qty_akhir){
      $('#id_transaction_kembali').val(id_transaction);
      $('#id_kat_kembali').val(id_kat);
      $('#total_qty_kembali').val(qty_akhir);
    }

    function edit_asset(id_barang,nama_barang,serial_number,description){
      $('#id_barang_edit').val(id_barang);
      $('#edit_nama').val(nama_barang);
      $('#serial_number_edit').val(serial_number);
      $('#keterangan_edit').val(description);
    }

    function reject(id_transaksi,note) {
      $('#reject_note').val(note);
      $('#id_transaction_reject_note').val(id_transaksi);
      $('#id_barang_reject_note').val(id_barang);
    }

    function delete_asset(id_barang, id_kat) {
      $('#id_barang_delete').val(id_barang);
      $('#id_kat_delete').val(id_kat);
    }

    function add_sn(id_barang) {
      $('#id_barang_sn').val(id_barang);
    }

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
    });

    $('#tgl_kembali').datepicker({
        onSelect: function(dateText, inst){
            $('#tgl_peminjaman').datepicker('option', 'maxDate', new Date(dateText));
        }
    });

    $(document).on('click',"#btn_accept",function(e) { 
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/dropdownSerialNumberAsset?kategori=',
            data:{
              kategori:this.value,
            },
            success: function(result){
              $(".detail-product").html(append)
              var append = append + "<option>"  +"</option>";

              $.each(result[0], function(key, value){
                  // append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";   
                append = append + "<option value="+value.id_barang+">"+ value.nama_barang + " - " + value.serial_number +"</option>";
                // console.log(value.id_barang);
              });

              $(".detail-product").html(append);

            },
        });
    });

    $(document).on('click',"#btn_kembali",function(e) { 
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
    });

    $(document).on('click',"#btn_reject",function(e) { 
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/dropdownid_barang_reject?kategori=',
            data:{
              id_transaction:this.value,
            },
            success: function(result){
              $("#id_barang_reject").html(append)
              var append = append + "<option>"  +"</option>";

              $.each(result[0], function(key, value){
                  // append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";   
                  append = append + "<option value="+value.id_barang+" selected>"+ value.id_barang +"</option>";
                // console.log(value.serial_number);
              });

              $("#id_barang_reject").html(append);

            },
        });
    });

    $(document).on('click',"#btn_detail",function(e) { 
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/dropdownsn',
            data:{
              id_transaction:this.value,
            },
            success: function(result){
              $("#mytable").empty();
              var table = "";

              $.each(result[0], function(key, value){
                table = table + '<tr>';
                table = table + '<td>' +value.nama_barang+ '</td>';
                table = table + '<td>' +value.serial_number+ '</td>';
                table = table + '</tr>';
              });

              $("#mytable").html(table);

            },
        });
    });

    $(document).on('click', "#btn_submit", function(e){
      var qty           = $(".qtysn").val();
      var sn            = $("#detail_product").val();
      var total_sn      = sn.length;
      /*console.log(qty);
      console.log(qty);
      console.log(sn);
      console.log(sn);*/

      if (total_sn == qty) {
        $.ajax({
          type:"POST",
          url:'/accept_pinjam',
          data:$('#modal_accept').serialize(),
          success: function(result){
              swal({
                  title: "Success!",
                  text:  "You have been add serial number",
                  type: "success",
                  timer: 2000,
                  showConfirmButton: false
              });
            setTimeout(function() {
                window.location.href = window.location;
            }, 2000);  
          },
        });
      } else if (total_sn == 0) {
        alert('Jumlah tidak sesuai !')
      } else{
        alert('Jumlah tidak sesuai !')
      }
    });

    $(document).on('change',"select[id^='detail_product']",function(e) {
      var id_barang = $('#detail_product').val();
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