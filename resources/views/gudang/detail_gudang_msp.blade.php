@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
  }
</style>

<section class="content-header">
  <h1>
    Detail Inventory MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Inventory</li>
    <li class="active">MSP</li>
    <li class="active">Detail</li>
  </ol>
</section>

<section class="content">
  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
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

  @if($cek->status2 == 'Y')
    <div class="box">
      <div class="box-header with-border">
        <h3>Serial Number</h3>
      </div>

      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Serial Number</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
              <?php $no = 1; ?>
              @foreach($sn as $data)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$data->nama}}</td>
                <td>{{$data->serial_number}}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  @endif

  <div class="box">
    <div class="box-header with-border">
      <h3>Detail Barang</h3>
    </div>

    <div class="box-body">
      <table class="table table-bordered">
        <tr>
          <td>Name of Goods</td>
          <td>: {{$datak->nama}}</td>
        </tr>
        <tr>
          <td>Current Stock</td>
          <td>: {{$datak->qty}} </td>
        </tr>
        <tr>
          <td>Last Activity</td>
          <td>:@if($keg->status == 'P' && $notes == 'FA') Stock Received @elseif($keg->status == 'P' && $notes == 'SJ') Stock Returned @elseif($keg->status == 'D' && $notes == 'SJ') Delivery Order @endif at {{date('F, d - Y', strtotime($dating))}}</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="box"> 
    <div class="box-header with-border">
      <h3>Saldo Table</h3>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered display no-wrap" id="saldo_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>In/Out</th>
              <th>Keterangan</th>
              <th>Kode Barang</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            <?php $no = 1; ?>
            @foreach($detail as $data)
            <tr>
              <td>{{$no++}}</td>
              <td>{{$data->created_at}}</td>
              <td>
                @if($data->status == 'P')
                +{{$data->qty}}
                @elseif($data->status == 'D')
                -{{$data->qty}}
                @endif
              </td>
              <td>{{$data->note}}</td>
              <td>{{$data->kode_barang}}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
          </tfoot>
        </table>
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

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function warehouse(id_detail,serial_number,note) {
      $('#id_detail').val(id_detail);
      $('#id_detail_edit').val(id_detail);
      $('#edit_serial_number').val(serial_number);
      $('#note_edit').val(note);
    }

    function hapus(id_detail,id_barang) {
      $('#id_detail_hapus').val(id_detail);
      $('#id_barang_hapus').val(id_barang);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $("#data_Table").DataTable({
      "scrollX": true,
       "order": [[ 1, "desc" ]],
    });

    $("#saldo_Table").DataTable()

  </script>
@endsection