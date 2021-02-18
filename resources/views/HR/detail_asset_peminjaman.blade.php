@extends('template.template_admin-lte')
@section('content')
<section class="content-header">
  <h1>
    Detail Asset Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="{{url('/asset_hr')}}">HR/GA - Asset Management</a></li>
    <li class="active">SIP</li>
    <li class="active">Detail</li>
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
  <div class="box box-solid box-default">
    <div class="box-header">      
      <div class="pull-right">
        <span class="label label-primary">Info Asset</span>
      </div>
    </div>

    <div class="box-body">
      <div class="col-md-6">
        <table class="table table-responsive" width="100%">
          <tr>
            <th width="20%">Code</th>
            <th width="2%"> : </th>
            <td>{{$detailAsset->code_name}}</td>
          </tr>
          <tr>
            <th>Umur</th>
            <th> : </th>
            <td>{{$detailAsset->umur_asset}}</td>
          </tr>
          <tr>
            <th>Nama</th>
            <th> : </th>
            <td>{{$detailAsset->nama_barang}}</td>
          </tr>
          <tr>
            <th>Serial Number</th>
            <th> : </th>
            <td>{{$detailAsset->serial_number}}</td>
          </tr>
          <tr>
            <th>Status</th>
            <th> : </th>
            <td>
              @if($detailAsset->status == "UNAVAILABLE")
              <label class="label label-default">UNAVAILABLE</label>
              @elseif($detailAsset->status == "AVAILABLE")
              <label class="label label-info">AVAILABLE</label>
              @elseif($detailAsset->status == "SERVICE")
              <label class="label label-primary">SERVICE</label>
              @elseif($detailAsset->status == "RUSAK")
              <label class="label label-danger">RUSAK</label>
              @elseif($detailAsset->status == "PENDING")
              <label class="label label-warning">PENDING</label>
              @endif
            </td>
          </tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-responsive" width="100%">
          <tr>
            <th width="20%">Tanggal Beli</th>
            <th width="2%"> : </th>
            <td>{{$detailAsset->tgl_tambah}}</td>
          </tr>
          <tr>
            <th>Merk</th>
            <th> : </th>
            <td>{{$detailAsset->merk}}</td>
          </tr>
          <tr>
            <th>Spesifikasi</th>
            <th> : </th>
            <td>{{$detailAsset->description}}</td>
          </tr>
          <tr>
            <th>Note</th>
            <th> : </th>
            <td>{{$detailAsset->note}}</td>
          </tr>
          <tr>
            <th>Lokasi</th>
            <th> : </th>
            <td>{{$detailAsset->lokasi}}</td>
          </tr>
      </table>
      </div>
    </div>
  </div>

  <div class="box box-solid box-default">
    <div class="box-header">
      <div class="pull-left">
        <label>Total : {{$total_pinjam}}</label>
      </div>
      <div class="pull-right">
        <span class="label label-primary">History Peminjaman</span>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <div class="col-md-12">
          <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>No Peminjaman</th>
                <th>Nama</th>
                <th>Peminjam</th>
                <th>Tgl Peminjaman</th>
                <th>Tgl Pengembalian</th>
                <th>Note</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
              <?php $no = 1; ?>
              @foreach($asset as $data)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$data->no_transac}}</td>
                <td>{{$data->nama_barang}}</td>
                <td>{{$data->name}}</td>
                <td>{{$data->tgl_peminjaman}}</td>
                <td>{{$data->tgl_pengembalian}}</td>
                <td>{{$data->keterangan}}</td>
                <td>
                 @if($data->tgl_pengembalian == "")
                  <span class="label label-danger">SUDAH DI AMBIL</span>
                  @else
                  <span class="label label-success">SUDAH KEMBALI</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>        
      </div>  
    </div>
  </div>
</section>

      <!--Ambil-->
    <div class="modal fade" id="ambil_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('ambil_pinjam')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_transaction_ambil" id="id_transaction_ambil" >
          <input type="text" name="id_barang_ambil" id="id_barang_ambil" >
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
          <form method="POST" action="{{url('kembali_pinjam')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_kembali" id="id_barang_kembali" >
          <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" >
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
@endsection
@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript">
     $('.money').mask('000,000,000,000,00', {reverse: true});

     function return_hr(id_pam){
      $('#no_return_hr').val(id_pam);
     }

     function return_finance(id_pam){
      $('#no_return_fnc').val(id_pam);
     }

     function ambil(id_transaction,id_barang){
      $('#id_transaction_ambil').val(id_transaction);
      $('#id_barang_ambil').val(id_barang);
    }

    function kembali(id_transaction,id_barang){
      $('#id_transaction_kembali').val(id_transaction);
      $('#id_barang_kembali').val(id_barang);
    }

    $('#data_Table').DataTable({})
</script>
@endsection