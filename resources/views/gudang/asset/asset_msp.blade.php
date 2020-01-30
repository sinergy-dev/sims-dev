@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  .qty {
    width: 40px;
    height: 25px;
    text-align: center;
  }
  input.qtyplus { width:25px; height:25px;}
  input.qtyminus { width:25px; height:25px;}
</style>

<section class="content-header">
  <h1>
    Asset Peminjaman MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Asset Peminjaman</li>
    <li class="active">MSP</li>
  </ol>
</section>

<section class="content">
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

  <div class="box">
    <div class="box-header">
      
    </div>

    <div class="box-body">

      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">List Asset</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Peminjaman Asset</a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive" >
              <table class="table table-bordered nowrap " id="data_Table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>MSP Code</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Action</th>
                    @if(Auth::User()->id_division == 'WAREHOUSE')
                    <th>Action</th>
                    @endif
                    <!-- @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position != 'ENGINEER STAFF' || Auth::User()->id_sub_division == 'DEPT DEVELOPMENT' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'DVG')
                    <th>Action</th>
                    @endif -->
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" value="{{$data->id_barang}}" hidden></td>
                    <td>{{$data->nama}}</td>
                    <td>{{$data->kode_barang}}</td>
                    <td>{{$data->kategori}}</td>
                    <td>{{$data->tipe}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->note}}</td>
                    <td>
                      @if($data->qty == 0)
                      <button class="btn btn-xs disabled" style="background-color: grey">Peminjaman</button>
                      @else
                      <button class="btn btn-xs btn-warning" onclick="pinjam('{{$data->id_barang}}','{{$data->nama}}','{{$data->qty}}')" data-target="#peminjaman" data-toggle="modal" >Peminjaman 
                      </button>
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'WAREHOUSE')
                    <td>
                      <a href="{{url('/detail_asset_msp', $data->id_barang) }}"><button class="btn btn-xs sho" style="width: 150px;">Detail Peminjaman</button></a>
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
          @if(Auth::User()->id_division == 'WAREHOUSE')
            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Barang</th>
                      <th>Qty</th>
                      <th>Description</th>
                      <th>Nama Peminjam</th>
                      <th>Tgl Peminjaman</th>
                      <!-- <th>Tgl Pengembalian</th> -->
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1 ?>
                    @foreach($assetsd as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->nama}}</td>
                      <td>{{$data->qty_pinjam}}</td>
                      <td>{{$data->keterangan}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->tgl_peminjaman}}</td>
                      <!-- <td>{{$data->tgl_pengembalian}}</td> -->
                      <td>
                        @if($data->status == 'PENDING')
                          <label class="status-open">PENDING</label>
                        @elseif($data->status == 'ACCEPT')
                          <label class="status-win" style="width: 90px">ACCEPTED</label>
                        @elseif($data->status == 'REJECT')
                          <label class="status-lose" style="width: 90px">REJECTED</label>
                        @elseif($data->status == 'AMBIL')
                         <label class="status-lose" style="width: 150px;background-color: #7735a3">SUDAH DI AMBIL</label>
                        @elseif($data->status == 'RETURN')
                         <label class="status-win" style="width: 90px">RETURNED</label>
                        @endif
                      </td>
                      <td>
                        @if($data->status == 'PENDING')
                        <button class="btn btn-xs btn-success" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaksi}}','{{$data->id_barang}}')">ACCEPT</button>
                        <button class="btn btn-xs btn-danger" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaksi}}','{{$data->id_barang}}')">REJECT</button>
                        @elseif($data->status == 'ACCEPT')
                        <button class="btn btn-xs btn-danger" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_barang}}','{{$data->id_transaksi}}')" style="text-align: center;width: 125px">KEMBALI</button>
                        @else
                        <button class="btn btn-xs btn-success disabled">ACCEPT</button>
                        <button class="btn btn-xs btn-danger disabled">REJECT</button>
                        @endif
                      </td>
                      <!-- <td>
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;">
                        </button>
                        <a href=""><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;">
                        </button></a>
                      </td> -->
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
            </div>
          @else
            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="table-responsive" style="margin-top: 15px">
                <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Barang</th>
                      <th>Qty</th>
                      <th>Description</th>
                      <th>Nama Peminjam</th>
                      <th>Tgl Peminjaman</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    <?php $no = 1 ?>
                    @foreach($pinjaman as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->nama}}</td>
                      <td>{{$data->qty_pinjam}}</td>
                      <td>{{$data->keterangan}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->tgl_peminjaman}}</td>
                      <!-- <td>{{$data->tgl_pengembalian}}</td> -->
                      <td>
                        @if($data->status == 'PENDING')
                          <label class="status-open">PENDING</label>
                        @elseif($data->status == 'ACCEPT')
                          <label class="status-win" style="width: 90px">ACCEPTED</label>
                        @elseif($data->status == 'REJECT')
                          <button class=" btn btn-xs status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px;" onclick="reject('{{$data->id_transaksi}}', '{{$data->note}}')"> REJECTED</button>
                          <!-- <label class="status-lose" style="width: 90px">REJECTED</label> -->
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
          <div class="tab-pane" id="kembaliin" role="tabpanel" aria-labelledby="kembaliin-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered" id="datas" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>MSP Code</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Action</th>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1; ?>
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$no++}}<input type="" name="id_barang_update" value="{{$data->id_barang}}" hidden></td>
                    <td>{{$data->nama}}</td>
                    <td>{{$data->kode_barang}}</td>
                    <td>{{$data->kategori}}</td>
                    <td>{{$data->tipe}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->note}}</td>
                    <td>
                      <a href="{{url('/detail_asset', $data->id_barang) }}"><button class="btn btn-sm sho">Detail</button></a>
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
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset Warehouse</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_asset_warehouse')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control" required></input>
          </div>
          <div class="form-group">
            <label for="sow">Qty</label>
            <input name="qty" id="qty" type="number" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit asset-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Asset Warehouse</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_asset_warehouse')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" name="id_barang_edit" id="id_barang_edit" hidden>
          <div class="form-group">
            <label for="sow">Qty</label>
            <input name="qty_edit" id="qty_edit" type="number" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-warning"><i class="fa fa-check"></i>&nbsp Update</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="peminjaman" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peminjaman</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_peminjaman_asset_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <div class="form-group">
            <label for="sow">Tgl Peminjaman</label>
            <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="sow">Tgl Pengembalian</label>
            <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control"></input>
          </div>
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang_pinjam" class="form-control" readonly></input>
          </div>
          <div class="form-group">
            <label for="sow">Jumlah Stock</label>
            <input name="qty" id="qty_pinjam" type="number" class="form-control" readonly>
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
            <input type='text' name='quantity' id="quantity" value='0' class='qty' />
            <input type='button' value='+' class='qtyplus' field='quantity' />
          </div>
          <div class="form-group"> 
            <label>Keterangan</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
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
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
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
          <form method="POST" action="{{url('accept_pinjam_warehouse_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_update" id="id_barang_update" hidden>
          <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>ACCEPT NOW!</b></h3>
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
          <form method="POST" action="{{url('reject_pinjam_warehouse_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject" id="id_barang_reject" hidden>
          <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>REJECT NOW!</b></h3>
          </div>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="note" id="note"> </textarea>
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
<div class="modal fade" id="ambil_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('ambil_pinjam_warehouse')}}" id="modalProgress" name="modalProgress">
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
</div>
  
  <!--kembali-->
  <div class="modal fade" id="kembali_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('kembali_pinjam_warehouse_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_kembali" id="id_barang_kembali" hidden>
          <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" hidden>
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

  <!-- Reject -->
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
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function warehouse(item_code,item_code,name_item,quantity,information) {
      $('#edit_item_code_before').val(item_code);
      $('#edit_item_code').val(item_code);
      $('#edit_name').val(name_item);
      $('#edit_quantity').val(quantity);
      $('#edit_information').val(information);
    }

    $('#data_Table').DataTable({
          "scrollX": true
        });

    $('#data_peminjaman').DataTable({
          "retrieve": true,
          "autowidth": true,
          "responsive": true,
        });

    $('#datatable').DataTable({
          "retrieve": true,
          "autowidth": true,
          "responsive": true,
        });

    $('#datas').DataTable({
          "retrieve": true,
          "autowidth": true,
          "responsive": true,
        });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

     $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    })

    function pinjam(id_barang,nama_barang,qty){
      $('#id_barang').val(id_barang);
      $('#nama_barang_pinjam').val(nama_barang);
      $('#qty_pinjam').val(qty);
    }

    function reject(id_transaksi,note) {
      $('#reject_note').val(note);
      $('#id_transaction_reject_note').val(id_transaksi);
      $('#id_barang_reject_note').val(id_barang);
    }

    function id_accept_update(id_transaksi,id_barang){
      $('#id_transaction_update').val(id_transaksi);
      $('#id_barang_update').val(id_barang);
    }

    function id_reject_update(id_transaksi,id_barang){
      $('#id_transaction_reject').val(id_transaksi);
      $('#id_barang_reject').val(id_barang);
    }

    function ambil(id_transaksi,id_barang){
      $('#id_transaction_ambil').val(id_transaksi);
      $('#id_barang_ambil').val(id_barang);
    }

    function kembali(id_transaksi,id_barang){
      $('#id_transaction_kembali').val(id_transaksi);
      $('#id_barang_kembali').val(id_barang);
    }

    function edit_asset(id_barang,qty,description){
      $('#id_barang_edit').val(id_barang);
      $('#qty_edit').val(qty);
      $('#keterangan_edit').val(description);
    }

    $(document).ready(function() {
      $('#peminjam').select2();
    });
  </script>
@endsection