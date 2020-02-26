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
    <div class="box-body">
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <!-- <li class="nav-item active">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">List Asset</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Peminjaman Asset</a>
          </li> -->
          <li class="nav-item active">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="kategori" aria-selected="false">List Asset</a>
          </li>
        <!--   <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#peminjaman_asset" role="tab" aria-controls="home" aria-selected="true">Peminjaman Asset</a>
          </li> -->
          @if(Auth::User()->id_division == 'HR')
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"> </i>&nbsp Add Asset</button>
          <button class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#modaledit" style="margin-right: 5px" ><i class="fa fa-edit"> </i>&nbsp Edit Asset</button>
          @endif
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered nowrap " id="data_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Code Asset</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Status</th>
                    @if(Auth::User()->id_division == 'HR')
                    <th>Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$data->code_name}}<input type="" name="id_barang_update" hidden></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->qty}}</td>
                    <td>{{$data->description}}</td>
                    <td>
                      @if($data->qty == 0)
                      <span class="label label-info">UnAvailable</span>
                      @else
                      <span class="label label-default">Available</span>
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'HR')
                    <td>
                      @if($data->qty == 0)
                      <button class="btn btn-xs btn-danger btn-pengembalian" value="{{$data->id_barang}}">Pengembalian</button>
                      @else
                      <button class="btn btn-xs btn-warning" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}','{{$data->qty}}')" data-target="#peminjaman" data-toggle="modal" >Peminjaman 
                      </button>
                      @endif
                      <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary">History</button></a>
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
          @if(Auth::User()->id_division == 'HR')
          <div class="tab-pane fade" id="peminjaman_asset" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>No Peminjaman</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Nama Peminjam</th>
                    <th>Tgl Peminjaman</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($assetsd as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->no_transac}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->qty_akhir}}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->tgl_peminjaman}}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open" style="width: 150px;">SUDAH DI AMBIL</label>
<!--                       @elseif($data->status == 'ACCEPT')
                        <label class="status-win" style="width: 90px;height: 25px">ACCEPTED</label>
                      @elseif($data->status == 'REJECT')
                        <label class="status-lose" style="width: 90px;height: 25px">REJECTED</label>
                      @elseif($data->status == 'AMBIL')
                       <label class="status-lose" style="width: 150px;background-color: #7735a3;height: 25px">SUDAH DI AMBIL</label> -->
                      @elseif($data->status == 'RETURN')
                       <label class="status-win" style="width: 90px;height: 25px">RETURNED</label>
                      @endif
                    </td>
                    <!-- <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-md btn-success" id="btn_accept" name="btn_accept" value="{{$data->id_transaction}}" data-target="#accept_modal" data-toggle="modal" onclick="id_accept_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->no_transac}}', '{{$data->name}}', '{{$data->nama_barang}}', '{{$data->qty_akhir}}')">ACCEPT</button>
                      <button class="btn btn-md btn-danger" id="btn_reject" name="btn_reject" value="{{$data->id_transaction}}" data-target="#reject_modal" data-toggle="modal" onclick="id_reject_update('{{$data->id_transaction}}','{{$data->id_barang}}', '{{$data->no_transac}}', '{{$data->name}}', '{{$data->nama_barang}}', '{{$data->qty_akhir}}')">REJECT</button>
                      @elseif($data->status == 'ACCEPT')
                      <button class="btn btn-md btn-danger" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_barang}}','{{$data->id_transaction}}')" style="text-align: center;width: 125px">KEMBALI</button>
                      @else
                      <button class="btn btn-md btn-success disabled">ACCEPT</button>
                      <button class="btn btn-md btn-danger disabled">REJECT</button>
                      @endif
                      @if($data->status == 'PENDING')
                      <button class="btn btn-md btn-danger" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_barang}}','{{$data->id_transaction}}')" style="text-align: center;width: 125px">KEMBALI</button>
                      @else
                      <button class="btn btn-md btn-danger" disabled style="text-align: center;width: 125px">KEMBALI</button>
                      @endif
                    </td> -->
                    <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-md btn-danger" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_barang}}','{{$data->id_transaction}}')" style="text-align: center;width: 125px">KEMBALI</button>
                      @else
                      <button class="btn btn-md btn-danger" disabled style="text-align: center;width: 125px">KEMBALI</button>
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
          <h4 class="modal-title">Add Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_asset_hr')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control"></input>
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
            <label for="sow">Kategori</label>
            <select class="form-control" id="category_asset" name="category_asset" required>
              <option value="LPT">Laptop</option>
              <option value="PRN">Printer</option>
              <option value="HDD">Harddisk</option>
              <option value="PRY">Proyektor</option>
              <option value="OTH">Other</option>
            </select>
          </div>
       <!--    <div class="form-group">
            <label for="sow">Qty</label>
            <input name="qty" id="qty" type="number" class="form-control" required="">
          </div> -->
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
          <h4 class="modal-title">Update Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('edit_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Nama barang</label>
            <select class="form-control" id="barang_asset_edit" name="barang_asset_edit" style="width: 100%!important" required>
              @foreach($asset as $data)
              <option value="{{$data->id_barang}}">{{$data->nama_barang}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required=""></textarea>
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
          <form method="POST" action="{{url('peminjaman_hr')}}" id="modalProgress" name="modalProgress">
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
          <!-- <div class="form-group">
            <label>Masukkan kebutuhan</label><br>
            <input type="text" name="qtys" id="qtys" class="qtys" hidden>
            <input type='number' name='quantity' id="quantity" value='0' class="form-control" style="width: 270px;" />
          </div> -->
          <div class="form-group">
            <label>Keperluan</label>
            <textarea class="form-control" name="keperluan"></textarea>
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

<!-- <div class="modal fade" id="pengembalian" role="dialog">
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
</div> -->

<!--modal accept-->
<div class="modal fade" id="accept_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('accept_pinjam_hr')}}" id="modalProgress" name="modalProgress">
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

<!--REJECT-->
<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('reject_pinjam_hr')}}" id="modalProgress" name="modalProgress">
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
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('kembali_pinjam_hr')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_kembali" id="id_barang_kembali" hidden="">
          <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" hidden="">
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
                  <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control">
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
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">

    $("#barang_asset_edit").select2();

    var now = new Date();
 
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $('#tanggal_kembali').val(today);

    $(document).on('change',"#barang_asset_edit",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:"{{url('/getEditAsset')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $('#keterangan_edit').val(result[0].description);
            },
        });
    });

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

    function id_accept_update(id_transaction,id_barang,nama_barang,no_transac,name){
      $('#id_transaction_update').val(id_transaction);
      $('#id_barang_update').val(id_barang);
      $('#no_peminjaman').val(no_transac);
      $('#nama_peminjam').val(name);
      $('#id_transaksi').val(id_transaction);
    }

    $('#users').select2();

    $(document).on('click',"#btn_accept",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/get_detail_hr',
            data:{
              id_transaction:this.value,
            },
            success: function(result){
              $("#mytable").empty();
              var table = "";

              $.each(result[0], function(key, value){
                table = table + '<tr>';
                table = table + '<td>' +value.nama_barang+ '</td>';
                table = table + '<td>' +value.qty_akhir+ '</td>';
                table = table + '</tr>';
              });

              $("#mytable").html(table);

            },
        });
    });

    $(document).on('click',"#btn_reject",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:'/get_detail_hr2',
            data:{
              id_transaction:this.value,
            },
            success: function(result){
              $("#mytable2").empty();
              var table = "";

              $.each(result[0], function(key, value){
                table = table + '<tr>';
                table = table + '<td>' +value.nama_barang+ '</td>';
                table = table + '<td>' +value.qty_akhir+ '</td>';
                table = table + '</tr>';
              });

              $("#mytable2").html(table);

            },
        });
    });

    $(document).on('keyup keydown', "input[id^='quantity']", function(e){
      var qty_before  = $(".qtys").val();
      console.log(qty_before);
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
    function warehouse(item_code,item_code,name_item,quantity,information) {
      $('#edit_item_code_before').val(item_code);
      $('#edit_item_code').val(item_code);
      $('#edit_name').val(name_item);
      $('#edit_quantity').val(quantity);
      $('#edit_information').val(information);
    }

    $('#data_table').DataTable({
      "order": [[ 0, "asc" ]],
    });

    $('#datatable').DataTable({
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
      $('#qtys').val(qty);
    }

    function id_reject_update(id_transaction,id_barang,nama_barang,no_transac,name){
      $('#id_transaction_reject').val(id_transaction);
      $('#id_barang_reject').val(id_barang);
      $('#no_peminjaman2').val(no_transac);
      $('#nama_peminjam2').val(name);
      $('#id_transaksi2').val(id_transaction);
    }

    function ambil(id_transaction,id_barang){
      $('#id_transaction_ambil').val(id_transaction);
      $('#id_barang_ambil').val(id_barang);
    }

    function kembali(id_transaction,id_barang,nama_barang,name){
      $('#id_transaction_kembali').val(id_transaction);
      $('#id_barang_kembali').val(id_barang);
      $('#nama_barang_kembali').text(nama_barang);
      $('#nama_peminjam_kembali').text(name);
    }

    function edit_asset(id_barang,qty,description){
      $('#id_barang_edit').val(id_barang);
      $('#qty_edit').val(qty);
      $('#keterangan_edit').val(description);
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