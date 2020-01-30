@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    SIP Detail Asset Management
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
      <a href="{{url('/asset_pinjam')}}"><button button class="btn btn-xs btn-danger pull-left" style="width: 150px"><i class="fa fa-arrow-circle-o-left"></i>&nbsp back to Asset</button></a>
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
                <th>Note</th>
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
                <td>{{$data->keterangan}}</td>
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
@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
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