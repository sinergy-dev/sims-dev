@extends('template.main')
@section('head_css')
<!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<style type="text/css">
  .btn{
      height: 25px;
      width: 100px;
    }
</style>
@endsection

@section('content')
  <section class="content-header">
    <h1>
      SIP Detail Asset Tech
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Tech - Asset Management</li>
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


    <div class="box">
      <div class="box-header">
        <a href="{{url('/asset_pinjam#list_asset')}}"><button button class="btn btn-xs btn-danger pull-left"><i class="fa fa-arrow-circle-o-left"></i>&nbsp back to Asset</button></a>
      </div>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Peminjam</th>
                  <th>Tgl Peminjaman</th>
                  <th>Tgl Pengembalian</th>
                  <th>Keperluan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                <?php $no = 1; ?>
                @foreach($asset as $data)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{$data->nama_barang}}</td>
                  <td>{{$data->name}}</td>
                  <td>{{$data->tgl_peminjaman}}</td>
                  <td>{{$data->tgl_pengembalian}}</td>
                  <td>{{$data->keperluan}}</td>
                  <td>
                    @if($data->status == 'PENDING')
                      <span class="label label-warning">PENDING</span>
                    @elseif($data->status == 'ACCEPT')
                      <span class="label label-success" style="width: 90px">ACCEPTED</span>
                    @elseif($data->status == 'REJECT')
                      <span class="label label-danger" data-target="#reject_note_modal" data-toggle="modal" onclick="reject('{{$data->id_transaction}}', '{{$data->note}}')" style="cursor: zoom-in;"> REJECTED</span>
                    @elseif($data->status == 'AMBIL')
                      <span class="label label-success" style="width: 150px;">SUDAH DI AMBIL</span>
                    @elseif($data->status == 'RETURN')
                      <span class="label label-success" style="width: 90px">RETURNED</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>  
      </div>
    </div>
  </section>
    
  <!--kembali-->
  <div class="modal fade" id="kembali_modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('kembali_pinjam')}}" id="modalProgress" name="modalProgress">
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
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
<script type="text/javascript">

  $('.money').mask('000,000,000,000,00', {reverse: true});

  $("#alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#alert").slideUp(300);
  });

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

  function reject(id_transaksi,note) {
    $('#reject_note').val(note);
    $('#id_transaction_reject_note').val(id_transaksi);
    $('#id_barang_reject_note').val(id_barang);
  }

  $('#data_Table').DataTable({
        // "scrollX": true
      pageLength:25
  });
</script>
@endsection