@extends('template.template_admin-lte')
@section('content')
<section class="content-header">
  <h1>
    SIP Detail Asset Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">HR/GA - Asset Management</li>
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
      <a href="{{url('/asset_atk')}}"><button button class="btn btn-xs btn-danger pull-left" style="width: 150px"><i class="fa fa-arrow-circle-o-left"></i>&nbsp back to Asset</button></a>
    </div>

    <div class="box-body">
      <div class="table-responsive">
          <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <!-- <th>No Peminjaman</th> -->
                <th>Nama Barang</th>
                <th>Nama</th>
                <th>Tgl Request</th>
                <th>Keterangan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
              <?php $no = 1; ?>
              @foreach($asset as $data)
              <tr>
                <td>{{$no++}}</td>
                <!-- <td>{{$data->no_transac}}</td> -->
                <td>{{$data->nama_barang}}</td>
                <td>{{$data->name}}</td>
                <td>{!!substr($data->created_at,0,10)!!}</td>
                <td>{{$data->keterangan}}</td>
                <td>
                  @if($data->status == 'PENDING')
                    <label class="status-open">PENDING</label>
                  @elseif($data->status == 'ACCEPT')
                    <label class="status-win" style="width: 90px">ACCEPTED</label>
                  @elseif($data->status == 'REJECT')
                    <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px; color: white;" onclick="reject_note('{{$data->id_transaction}}', '{{$data->note}}')"> REJECTED</button>
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
@endsection
@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript">
    $('#data_Table').DataTable({})
</script>
@endsection