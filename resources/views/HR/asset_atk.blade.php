@extends('template.main')
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
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

    .nav-tabs .badge{
      position: absolute;
      top: -10px;
      right: -10px;
      background: red;
    }


    .modalIconsubject i{
        position:absolute;
        left:9px;
        top:0px;
        padding:9px 8px;
        color:#aaa;
        transition:.3s;
    }

    /* Dropdown Button */
    .dropbtn {
      background-color: #4CAF50;
      color: white;
      padding: 5px;
      font-size: 13px;
      width: 120px;
      border: none;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 120px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {background-color: #ddd;}

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {display: block;}

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {background-color: #3e8e41;}

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
    <div class="alert alert-primary" id="alert">
      {{ session('success') }}
    </div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success" id="alert">
      {{ session('alert') }}
    </div>
  @endif

  @if ($message = Session::get('qty-done'))
  <div class="alert alert-danger alert-block" id="alert">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong style="">{{ $message }}</strong>
  </div>
  @endif

  <div class="box">
    <div class="box-body">
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="kategori" aria-selected="false">List Asset</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active home-tab" id="home-tab" data-toggle="tab" style="display: none;" href="#peminjaman_asset_atk" role="tab" aria-controls="home" aria-selected="true">Request ATK</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active home-tab-2" id="home-tab-2" data-toggle="tab" style="display: none;" href="#peminjaman_asset_atk_2" role="tab" aria-controls="home" aria-selected="true">Request ATK</a>
          </li>
          <!-- @if(Auth::User()->id_division == 'HR')
          <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#request_pr" role="tab" aria-controls="home" aria-selected="true">Request PR</a>
          </li>
          @endif -->
          <button class="btn btn-sm btn-success pull-right tambah_asset_atk" data-toggle="modal" id="tambah_asset_atk" data-target="#add_asset" style="display: none"><i class="fa fa-plus"> </i>&nbsp Asset</button>
          <div class="pull-right dropdown" style="margin-left: 5px">
            <button class="dropbtn request_atk" id="request_atk" style="display: none;"><i class="fa fa-plus"> </i>&nbspRequest ATK</button>
            <div class="dropdown-content">
              <a data-toggle="modal" data-target="#peminjaman_modal">Available</a>
              <a data-toggle="modal" data-target="#request_modal">Unavailable</a>
            </div>
          </div>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active home-tab" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered nowrap " id="data_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Brand Name</th>
                    <th>Description</th>
                    <th id="col_action" class="col_action" style="display: none;">Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" hidden></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->unit}}</td>
                    <td>{{$data->merk}}</td>
                    <td>{{$data->description}}</td>
                    <td id="col_action_2" class="col_action" style="display: none;">
                      <a href="{{url('/asset_atk/detail_asset_atk', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-history" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="modal" data-target="#modaledit" onclick="edit_asset('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->description}}')"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      <button class="btn btn-xs btn-default btn-peminjaman" data-toggle="modal" data-target="#modalrestock" onclick="update_stok('{{$data->id_barang}}', '{{$data->nama_barang}}', '{{$data->qty}}', '{{$data->description}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Restock" data-placement="bottom"><i class="fa fa-hourglass-start"></i></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="peminjaman_asset_atk" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap requestTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Nama</th>
                    <th>Tgl Request</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($assetsd as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>
                      @if($data->qty_akhir != null)
                      {{$data->qty_akhir}}
                      @else 
                      0
                      @endif
                    </td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>-</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="label label-warning" style="width: 90px">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="label label-primary" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'PROSES')
                        <label class="label label-default" style="width: 90px">PROSES PR</label>
                      @elseif($data->status == 'DONE')
                        <label class="label label-success" style="width: 90px">DONE</label>
                      @endif
                    </td>
                    <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-xs btn-success" id="btn_accept" name="btn_accept" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" onclick="id_accept_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}', '{{$data->nama_barang}}', '{{$data->keterangan}}', '{{$data->nik_peminjam}}', '{{$data->created_at}}')">ACCEPT</button>
                      <button class="btn btn-xs btn-danger" id="btn_reject" name="btn_reject" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;"  onclick="reject_update('{{$data->id_transaction}}')">REJECT</button>
                      @elseif($data->status == 'PROSES')
                      <button class="btn btn-xs btn-primary" id="btn-done" data-target="#done_modal" data-toggle="modal" name="btn_done" value="{{$data->id_transaction}}" style="width: 90px; height: 25px" onclick="update_done_pr('{{$data->id_transaction}}', '{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_request}}', '{{$data->nama_barang}}')">DONE</button>
                      @elseif($data->status == 'DONE')
                      <button class="btn btn-xs btn-primary disabled" style="width: 90px; height: 25px">DONE</button>
                      @else
                      <button class="btn btn-xs btn-success disabled" style="width: 90px; height: 25px;">ACCEPT</button>
                      <button class="btn btn-xs btn-danger disabled" style="width: 90px; height: 25px;">REJECT</button>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                  @foreach($request as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->nama}}</td>
                      <td>{{$data->qty}}</td>
                      <td>{{$data->keterangan}}</td>
                      <td>{{$data->name}}</td>
                      <td>{!!substr($data->created_at,0,10)!!}</td>
                      <td><a href="{{$data->link}}" target="_blank">{!!substr($data->link,0,30)!!}...</a></td>
                      <td>
                        @if($data->status == 'REQUEST' || $data->status == 'PROCESS')
                          <label class="label label-warning" style="width: 90px">PENDING</label>
                        @elseif($data->status == 'ACCEPT')
                          <label class="label label-primary" style="width: 90px">ACCEPTED</label>
                        @elseif($data->status == 'REJECT')
                          <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" > REJECTED</button>
                        @endif
                      </td>
                      <td>
                        @if($data->status == 'REQUEST')
                        <button class="btn btn-xs btn-success" id="btn_accept_request_atk" value="{{$data->id_barang}}" name="btn_accept" style="width: 90px; height: 25px;">ACCEPT</button>
                        <button class="btn btn-xs btn-danger" id="btn_reject" name="btn_reject" style="width: 90px; height: 25px;" onclick="reject_request_atk('{{$data->id_barang}}')">REJECT</button>
                        @elseif($data->status == 'PROCESS')
                        <button class="btn btn-xs btn-primary" id="btn-done" data-target="#done_request_modal" data-toggle="modal" name="btn_done" style="width: 90px; height: 25px" onclick="done_request_atk('{{$data->id_barang}}', '{{$data->nama}}', '{{$data->qty}}', '{{$data->nik}}', '{{$data->keterangan}}')">DONE</button>
                        @elseif($data->status == 'DONE')
                        <button class="btn btn-xs btn-primary" style="width: 90px; height: 25px">DONE</button>
                        @else
                        <button class="btn btn-xs btn-success disabled" style="width: 90px; height: 25px;">ACCEPT</button>
                        <button class="btn btn-xs btn-danger disabled" style="width: 90px; height: 25px;">REJECT</button>
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
          <div class="tab-pane fade" id="peminjaman_asset_atk_2" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatables" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <!-- <th>Qty Available</th> -->
                    <th>Qty Request</th>
                    <th>Description</th>
                    <th>Tgl Request</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($pinjaman as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <!-- <td>
                      @if($data->status == 'PROSES' || $data->status == 'DONE')
                      {{$data->qty_awal}}
                      @elseif($data->status == 'PENDING' || $data->status == 'ACCEPT' || $data->status == 'REJECT')
                      {{$data->qty_akhir}}
                      @elseif($data->qty_awal == 0) 
                      0
                      @endif
                    </td> -->
                    <td> {{$data->qty_akhir}}
                      <!-- @if($data->qty_request != null)
                      {{$data->qty_request}}
                      @else
                      0
                      @endif -->
                    </td>
                    <td>{{$data->keterangan}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="label label-warning" style="width: 90px">PENDING</label>
                      @elseif($data->status == 'ACCEPT')
                        <label class="label label-primary" style="width: 90px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                      @elseif($data->status == 'PROSES')
                        <label class="label label-default" style="width: 90px">PROSES PR</label>
                      @elseif($data->status == 'DONE')
                        <label class="label label-success" style="width: 90px">DONE</label>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                  @foreach($request2 as $data)
                    <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->nama}}</td>
                    <td> {{$data->qty}}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>{!!substr($data->created_at,0,10)!!}</td>
                    <td>
                      @if($data->status == 'REQUEST' || $data->status == 'PROCESS')
                        <label class="label label-warning" style="width: 90px">PENDING</label>
                      @elseif($data->status == 'DONE')
                        <label class="label label-primary">DONE</label>
                      @elseif($data->status == 'REJECT')
                        <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
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
         <!--  @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'WAREHOUSE')
          <div class="tab-pane fade" id="request_pr" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="pr_request" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Qty Request</th>
                    <th>Description</th>
                    <th>Nama</th>
                    <th>Tgl Request</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($pr_request as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->nama_barang}}</td>
                      <td>
                        @if($data->qty_request != null)
                        {{$data->qty_request}}
                        @else 
                        0
                        @endif
                      </td>
                      <td>{{$data->keterangan}}</td>
                      <td>{{$data->name}}</td>
                      <td>{!!substr($data->created_at,0,10)!!}</td>
                      <td>
                        @if($data->status == 'PENDING')
                          <label class="status-open" style="width: 90px">PENDING</label>
                        @elseif($data->status == 'ACCEPT')
                          <label class="status-win" style="width: 90px">ACCEPTED</label>
                        @elseif($data->status == 'REJECT')
                          <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
                        @elseif($data->status == 'PROSES')
                          <label class="status-sd" style="width: 90px">PROSES PR</label>
                        @elseif($data->status == 'DONE')
                          <label class="status-tp" style="width: 90px">DONE</label>
                        @endif
                      </td>
                      <td>
                        @if($data->status == 'PENDING')
                        <button class="btn btn-xs btn-success" id="btn_accept" name="btn_accept" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}', '{{$data->created_at}}')">ACCEPT</button>
                        <button class="btn btn-xs btn-danger" id="btn_reject" name="btn_reject" value="{{$data->id_transaction}}" style="width: 90px; height: 25px;" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_akhir}}')">REJECT</button>
                        @elseif($data->status == 'PROSES')
                        <button class="btn btn-xs btn-primary" id="btn-done" data-target="#done_modal" data-toggle="modal" name="btn_done" value="{{$data->id_transaction}}" style="width: 90px; height: 25px" onclick="update_done_pr('{{$data->id_transaction}}', '{{$data->id_barang}}', '{{$data->qty}}', '{{$data->qty_request}}', '{{$data->nama_barang}}')">DONE</button>
                        @elseif($data->status == 'DONE')
                        <button class="btn btn-xs btn-primary disabled" style="width: 90px; height: 25px">DONE</button>
                        @else
                        <button class="btn btn-xs btn-success disabled" style="width: 90px; height: 25px;">ACCEPT</button>
                        <button class="btn btn-xs btn-danger disabled" style="width: 90px; height: 25px;">REJECT</button>
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
          @endif -->
        </div>
      </div>
    </div>
  </div>
</section>

<!--add asset-->
<div class="modal fade" id="add_asset" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset ATK</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/store_asset_atk')}}" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Product Name</label>
            <input name="nama_barang" id="nama_barang" class="form-control" placeholder="Enter name" required></input>
          </div>
          <div class="form-group">
            <label for="sow">Quantity</label>
            <input name="qty" id="qty" type="number" class="form-control" required placeholder="Enter Quantity">
          </div>
          <div class="form-group">
            <label>Unit</label>
            <select class="form-control unit_atk" name="unit" id="unit"style="width: 100%">
              <option value="">Select Unit</option>
              @foreach($unit_assets as $unit_asset)
              <option value="{{$unit_asset->unit}}">{{$unit_asset->unit}}</option>
              @endforeach
            </select>
            <label class="hover-biru" style="color:#002280;">Unit belum ada?</label>
          </div>
          <div class="form-group">
            <label for="sow">Brand</label>
            <input name="merk" id="merk" type="text" class="form-control" required placeholder="Enter Brand Name">
          </div>
          <div class="form-group">
            <label for="sow">Description</label>
            <textarea name="keterangan" id="ket" class="form-control" required placeholder="Enter Description"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Submit</button>
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
          <h4 class="modal-title">Update Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/edit_atk')}}" name="modalProgress">
            @csrf
          <input type="" name="id_barang_edit" id="id_barang_edit" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_edit" id="nama_barang_edit" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="sow">Deskripsi</label>
            <textarea name="deskripsi_edit" id="deskripsi_edit" class="form-control" required></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-warning" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modalrestock" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Stok ATK</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/update_stok')}}" name="modalProgress">
            @csrf
          <input type="" name="id_barang_restok" id="id_barang_restok" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_restok" id="nama_barang_restok" class="form-control" readonly>
          </div>
          <div class="form-group">
           <label>Qty Awal</label>
            <input type="text" name="qty_awal_restok" id="qty_awal_restok" class="form-control" readonly>
          </div>
          <div class="form-group">
           <label>Qty Masuk</label>
            <input type="number" name="qty_masuk_restok" id="qty_masuk_restok" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="sow">Deskripsi</label>
            <textarea name="deskripsi_restok" id="deskripsi_restok" class="form-control" readonly></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-xs btn-warning" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="peminjaman_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Request ATK</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/request_atk')}}" name="modalProgress">
            @csrf
          <div style="overflow: auto">
            <table id="product-add" class="table product-add">
              <tr class="tr-header">
                <th>Product Name</th>
                <th>Stock</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Description</th>
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" class="add"><span class="fa fa-plus"></span></a></th>
              </tr>
              <tr>
              <td style="margin-bottom: 50px;">
                <br><select class="form-control produk"  onchange="initatk()" name="atk[]" id="atk" data-rowid="0" style="font-size: 14px; width: 200px">
                
                </select>
              </td>
                <td hidden>    
                  <input name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="0">
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <input class="form-control stock" placeholder="stock" data-rowid="0" name="stock[]" id="stock" style="width: 70px;font-size: 14px" readonly>
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <input type="number" class="form-control" placeholder="qty" name="qty[]" id="qty_butuh" style="width: 70px;font-size: 14px" required>
                </td>
                <td style="margin-bottom: 50px">
                  <br><input type="text" class="form-control units" data-rowid="0" name="unit[]" style="width: 100px" id="unit_produk" readonly >
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <textarea type="text" class="form-control" placeholder="Enter keterangan" name="keterangan[]" id="keterangan" style="width: 300px;font-size: 14px" required></textarea>
                </td>
                <td>
                  <a href='javascript:void(0);'  class='remove'><span class="fa fa-times" style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
                </td>
              </tr>
            </table>
            <div class="col-md-12 modal-footer" id="btn_submit">
              <br>
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"> </i>&nbspClose</button>
              <button type="submit" id="btn_request" class="btn btn-sm btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
            </div>

        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="request_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Request ATK</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/store_request_atk')}}" name="modalProgress">
            @csrf
          <div style="overflow: auto">
            <table id="product-request" class="table product-add">
              <tr class="tr-header">
                <th>Product Name</th>
                <th>Qty</th>
                <th>Description</th>
                <th>Link Product</th>
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore2" class="add"><span class="fa fa-plus"></span></a></th>
              </tr>
              <tr>
                <td style="margin-bottom: 50px;">
                  <br><input class="form-control" name="atk[]" placeholder="Enter Product Name" id="atk_request" data-rowid="0" style="font-size: 14px; width: 200px" required>
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <input type="number" class="form-control" placeholder="Qty" name="qty[]" id="quantity_request" style="width: 70px;font-size: 14px" required>
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <textarea type="text" class="form-control" placeholder="Enter Description" name="keterangan[]" id="keterangan_request" style="width: 250px;font-size: 14px" required></textarea>
                </td>
                <td style="margin-bottom: 50px;">
                  <br>
                 <textarea type="tetx" class="form-control" placeholder="Enter Link Product" name="link[]" id="link" style="width: 250px;font-size: 14px" required></textarea>
                </td>
                <td>
                  <a href='javascript:void(0);' class='remove'><span class="fa fa-times" style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
                </td>
              </tr>
            </table>
            <div class="col-md-12 modal-footer" id="btn_submit">
              <br>
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"> </i>&nbspClose</button>
              <button type="submit" id="btn_request_asset" class="btn btn-sm btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
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
        <div class="modal-header">
          <h4 style="text-align: center;"><b>Are you sure to accept?</b></h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_accept" id="nama_barang_accept" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="qty_accept" id="qty_accept" readonly class="form-control" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" name="description_accept" id="description_accept" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Tgl Request</label>
            <input type="text" name="tgl_request_accept" id="tgl_request_accept" class="form-control" readonly>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" id="btn_accept_atk" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Accept</button>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="accept_request_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 style="text-align: center;"><b>Are you sure to accept?</b></h4>
        </div>
        <div class="modal-body">
          <input name="id_trans" id="id_trans" hidden>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang_accept" id="nama_barang_accept2" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="qty_accept" id="qty_accept2" readonly class="form-control">
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" name="description_accept" id="description_accept2" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Link Product</label>
            <textarea class="form-control" id="link_product_accept2" readonly></textarea>
          </div>
          <div class="form-group">
            <label>Tgl Request</label>
            <input type="text" name="tgl_request_accept" id="tgl_request_accept2" class="form-control" readonly>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" id="btn_accept_request" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Accept</button>
          </div>
        </div>
      </div>
    </div>
</div>

<!--REJECT-->
<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject" id="id_barang_reject" hidden>
          <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
          <input type="" name="qty_awal_reject" id="qty_awal_reject" hidden>
          <input type="" name="qty_akhir_reject" id="qty_akhir_reject" hidden>
          <div class="form-group">
          	<h4 style="text-align: center;"><b>Are you sure to reject?</b></h4>
          </div>
          <div class="form-group">
          	<label>Note</label>
          	<textarea class="form-control" name="note_reject" id="note_reject" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" id="btn_reject_atk" class="btn btn-xs btn-danger" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Reject</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reject_request_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('asset_atk/reject_request_atk')}}" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject" id="id_barang_reject2" hidden>
          <div class="form-group">
            <h4 style="text-align: center;"><b>Are you sure to reject?</b></h4>
          </div>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="note_reject" id="note_reject" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" class="btn btn-xs btn-danger" id="btn_reject_request" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp Reject</button>
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
          <form name="modalProgress">
            @csrf
          <input type="text" name="id_transaction_reject2" id="id_transaction_reject2" hidden>
          <div class="form-group">
          	<label>Note</label>
          	<textarea class="form-control" name="note_reject" id="note_reject2" readonly></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="done_modal" role="dialog">
 <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <form method="POST" action="{{url('asset_atk/done_request_pr')}}" name="modalProgress">
          @csrf
        <input type="text" name="id_barang_done" id="id_barang_done" hidden>
        <input type="text" name="id_transaction_done" id="id_transaction_done" hidden>
        <input type="text" name="qty_done" id="qty_done" hidden> 
        <input type="text" name="qty_request_done" id="qty_request_done" hidden>
        <div class="form-group">
          <h4 style="text-align: center;"><b>PR Done?</b></h4>
        </div>
        <div class="form-group"> 
          <label>Nama Barang</label>
          <input name="nama_barang_done" id="nama_barang_done" class="form-control" readonly> 
        </div>
        <div class="form-group"> 
          <label>Qty Request</label>
          <input name="qty_request_done_field" class="form-control" id="qty_request_done_field" readonly>
        </div>
        <div class="form-group">
          <label>Qty Now</label>
          <input name="qty_now_pr" id="qty_now_pr" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Qty Restock</label>
          <input type="number" name="qty_restock_pr" id="qty_restock_pr" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
          <button type="submit" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp YES</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="done_request_modal" role="dialog">
 <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <form method="POST" action="{{url('asset_atk/done_request_atk')}}" name="modalProgress">
          @csrf
        <input type="text" name="id_barang_done2" id="id_barang_done2" hidden>
        <input type="text" name="nik_request2" id="nik_request2" hidden>
        <input type="text" name="ket_request" id="ket_request" hidden>
        <div class="form-group">
          <h4 style="text-align: center;"><b>Request Done?</b></h4>
        </div>
        <div class="form-group"> 
          <label>Nama Barang</label>
          <input name="nama_barang_done" id="nama_barang_done2" class="form-control">
        </div>
        <div class="form-group"> 
          <label>Qty Request</label>
          <input name="qty_request_done2" class="form-control" id="qty_request_done2" readonly>
        </div>
        <div class="form-group">
          <label>Qty Restock</label>
          <input type="number" name="qty_restock_atk" id="qty_restock_atk" placeholder="Enter Quantity" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Brand Name</label>
          <input type="text" name="merk_request" id="merk_request" placeholder="Enter Brand Name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Unit</label>
          <select class="form-control unit_atk" name="unit_request" id="unit_request"style="width: 100%" required>
            <option value="">Select Unit</option>
            @foreach($unit_assets as $unit_asset)
            <option value="{{$unit_asset->unit}}">{{$unit_asset->unit}}</option>
            @endforeach
          </select>
          <label class="hover-biru" style="color:#002280;">Unit belum ada?</label>
        </div>
        <div class="form-group">
            <label for="sow">Description</label>
            <textarea name="keterangan_request" id="ket2" class="form-control" required placeholder="Enter Description"></textarea>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-xs btn-default" style="width: 70px; height: 25px;" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
          <button type="submit" id="btn_done_request" class="btn btn-xs btn-success" style="width: 70px; height: 25px;"><i class="fa fa-check"></i>&nbsp YES</button>
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
          <div class="">Sedang diproses. . .</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
  <script type="text/javascript">

    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("." + item).show()
    })

    $(document).ready(function(){
      initatk();
    })

    function initatk() {
      var datas_kat = [];
      $.ajax({
        type:"GET",
        url: "asset_atk/getAssetAtk",
        success:function(result){
          var arr = result.results;        
            var data = {
              id: -1,
              text: 'Select Name...'
            };

            datas_kat.push(data)
            $.each(arr,function(key,value){
              datas_kat.push(value)
            })

          $(".produk").select2({
            placeholder: "Select a state",
            data: datas_kat
          });
        }
      })
    }


    var i = 1;
    $('#addMore').click(function(){  
         i++;  
         $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control produk" name="atk[]" data-rowid="'+i+'" id="atk2"></select></td><td hidden><input type="" name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="'+i+'" ></td><td style="margin-bottom: 50px;"><br><input class="form-control stock" placeholder="stock" data-rowid="'+i+'" name="stock[]" id="stock" style="width: 70px;font-size: 14px" readonly></td><td style="margin-bottom: 50px;"><br><input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required></td><td style="margin-bottom: 50px"><br><input type="text" class="form-control units" data-rowid="'+i+'" style="width: 100px" name="unit[]" id="unit_produk" readonly ></td><td style="margin-bottom: 50px;"><br><textarea type="text" class="form-control" placeholder="Enter keterangan" name="keterangan[]" id="keterangan" style="width: 300px;font-size: 14px" required></textarea></td><td><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red;margin-top: 25px"></span></a></td></tr>');

      initatk();

    });

    $('#addMore2').click(function(){  
         i++;  
         $('#product-request').append('<tr id="row'+i+'"><td style="margin-bottom: 50px;"><br><input class="form-control" name="atk[]" id="atk_request" data-rowid="'+i+'" style="font-size: 14px; width: 200px" placeholder="Enter Product Name" required></td><td style="margin-bottom: 50px;"><br><input type="number" class="form-control" placeholder="Qty" name="qty[]" id="quantity_request" style="width: 70px;font-size: 14px" required></td><td style="margin-bottom: 50px;"><br><textarea type="text" class="form-control" name="keterangan[]" id="keterangan_request" placeholder="Enter Description" style="width: 250px;font-size: 14px" required></textarea></td><td style="margin-bottom: 50px;"><br><textarea type="tetx" placeholder="Enter Link Product" class="form-control" name="link[]" id="link" style="width: 250px;font-size: 14px" required></textarea></td><td><a href="javascript:void(0);" class="remove"><span class="fa fa-times" style="font-size: 18px;margin-top: 20px;color: red;"></span></a></td></tr>');

    });
    

    $(document).on('click', '.remove', function() {
     var trIndex = $(this).closest("tr").index();
      if(trIndex>1) {
        $(this).closest("tr").remove();
      } else {
        alert("Sorry!! Can't remove first row!");
      }
    });

  	$('.unit_atk').select2();

  	function reject_note(id_transaction,note) {
  		$('#id_transaction_reject2').val(id_transaction);
  		$('#note_reject2').val(note);
  	}

    // function id_accept_update(id_transaction,id_barang,qty,qty_akhir,nama_barang,keterangan,nik_peminjam,created_at){
    //   $('#id_transaction_update').val(id_transaction);
    //   $('#id_barang_update').val(id_barang);
    //   $('#qty_awal_accept').val(qty);
    //   $('#qty_akhir_accept').val(qty_akhir);
    //   $('#nama_barang_accept').val(nama_barang);
    //   $('#qty_accept').val(qty_akhir);
    //   $('#description_accept').val(keterangan);
    //   $('#nik_request').val(nik_peminjam);
    //   $('#tgl_request_accept').val(created_at.substring(0, 10));
    // }


    function id_accept_update(id_transaction,id_barang,qty,qty_akhir,nama_barang,keterangan,nik_peminjam,created_at){
      var swalAccept;
      var titleStatus = 'Accept Request ATK'  

      $("#accept_modal").modal('show')   
      $("#nama_barang_accept").val(nama_barang)
      $("#description_accept").val(keterangan)
      $("#qty_accept").val(qty_akhir)   
      $('#tgl_request_accept').val(created_at.substring(0, 10));

      $("#btn_accept_atk").click(function(){
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
              url:"{{url('asset_atk/accept_request')}}",
              data:{
                id_transaction:id_transaction,
                id_barang:id_barang,
                qty:qty,
                qty_akhir:qty_akhir,
                nama_barang:nama_barang,
                nik_peminjam:nik_peminjam
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
    }

    $(document).on('click', '#btn_accept_request_atk', function() {
      var id_barang = this.value
      $.ajax({
        type:"GET",
        url:'{{url("/asset_atk/detail_produk_request")}}',
        data:{
          id_barang:this.value,
        },
        success: function(result){
          $.each(result, function(key, value){
              $('#id_trans').val(value.id_barang);
              $('#nama_barang_accept2').val(value.nama);
              $('#qty_accept2').val(value.qty);
              $('#description_accept2').val(value.keterangan);
              $('#tgl_request_accept2').val(value.created_at.substring(0, 10));
              $('#link_product_accept2').val(value.link);
          });
        }
      });
      $('#accept_request_modal').modal('show')

      var swalAccept;
      var titleStatus = 'Accept Request ATK'
      $("#btn_accept_request").click(function(){
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
              url:"{{url('asset_atk/accept_request_atk')}}",
              data:{
                id_barang:id_barang
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
    });

    function update_done_pr(id_transaction,id_barang,qty,qty_request,nama_barang) {
      $('#id_transaction_done').val(id_transaction);
      $('#id_barang_done').val(id_barang);
      $('#qty_done').val(qty);
      $('#qty_request_done').val(qty_request);
      $('#nama_barang_done').val(nama_barang);
      $('#qty_request_done_field').val(qty_request);
      $('#qty_now_pr').val(qty);
    }

    function done_request_atk(id_barang,nama_barang,qty,nik,keterangan) {
      $('#id_barang_done2').val(id_barang);
      $('#qty_request_done2').val(qty);
      $('#nama_barang_done2').val(nama_barang);
      $('#nik_request2').val(nik);
      $('#ket_request').val(keterangan);
    }

    $(document).on('keyup keydown', "input[id^='qty_butuh']", function(e){
      var qty_before = $("#stock").val();
      if ($(this).val() > parseFloat(qty_before)
          && e.keyCode != 46
          && e.keyCode != 8
         ) {
         e.preventDefault();     
         $(this).val(qty_before);
      }
    });

    $('.hover-biru').click(function(){
      var new_unit = prompt("Enter unit :");
      $("#unit").append($('<option>', { value: new_unit, text: new_unit, selected:true }));
      $("#unit_request").append($('<option>', { value: new_unit, text: new_unit, selected:true }));
    })

    $(".detail-product").select2({
      closeOnSelect : false,
    });

    $('#data_table').DataTable({
      pageLength: 25,

      initComplete: function () {
        accesable.forEach(function(item,index){
          $("." + item).show()
        })
      },
      "order": []
    });

    $(document).on('click','.paginate_button', function() {
      accesable.forEach(function(item,index){
        $("." + item).show()
      })
    });

    $('#datatables').DataTable({
      pageLength: 25,
    });


    $('#pr_request').DataTable({
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#btn_request').click(function(){
      $('#tunggu').modal('show')
      $('#peminjaman_modal').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 5000);
    });

    $('#btn_request_asset').click(function(){
      $('#tunggu').modal('show')
      $('#request_modal').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 5000);
    });

    $('#btn_done_request').click(function(){
      $('#tunggu').modal('show')
      $('#done_request_modal').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 5000);
    });

    var requestTable = $('#datatable').DataTable({
      pageLength: 25,
    });

    if (!requestTable.rows().count()) {
    }else{
        $('#home-tab').append('<span class="badge">'+ requestTable.rows().count() +'</span>')
    }
    

    $(document).on('change',".produk",function(e) {
      var atk = $('.produk').val();
      var rowid = $(this).attr("data-rowid");

         $.ajax({
          type:"GET",
          url:'asset_atk/get_qty_atk',
          data:{
            atk:this.value,
          },
          success: function(result,qty){
            $.each(result[0], function(key, value){
              $(".stock[data-rowid='"+rowid+"']").val(value.qty);
              $(".units[data-rowid='"+rowid+"']").val(value.unit);
              $(".id_barangs[data-rowid='"+rowid+"']").val(value.nama_barang);
            });
          }
        });
    });


    function reject_update(id_transaction){
      var titleStatus = 'Reject Request ATK'
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
            url:"{{url('asset_atk/reject_request')}}",
            data:{
              id_transaction:id_transaction,
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

    function reject_request_atk(id_barang){
      var titleStatus = 'Reject Request ATK'
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
            url:"{{url('asset_atk/reject_request_atk')}}",
            data:{
              id_barang:id_barang,
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

    function edit_asset(id_barang,nama_barang,description){
      $('#id_barang_edit').val(id_barang);
      $('#nama_barang_edit').val(nama_barang);
      $('#deskripsi_edit').val(description);
    }

    function update_stok(id_barang,nama_barang,qty,description){
      $('#id_barang_restok').val(id_barang);
      $('#nama_barang_restok').val(nama_barang);
      $('#qty_awal_restok').val(qty);
      $('#deskripsi_restok').val(description);
    }

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