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
    <div class="box-header with-border">
      <h3>Detail Barang</h3>
    </div>

    <div class="box-body">
      <table class="table table-bordered">
        <tr>
          <td style="width:30%">Name of Goods</td>
          <td>: {{$data->nama_barang}}</td>
        </tr>
        <tr>
          <td style="width:30%">Current Stock</td>
          <td>: {{$data->qty}} {{$data->unit}}</td>
        </tr>
        <tr>
          <td style="width:30%">Last Activity</td>
          <td>:
            @if($last_update->status == 'In') 
              Stock Added 
            @else 
              Requested @endif by {{$last_update->name}} at {{date('F, d - Y', strtotime($last_update->created_at))}}
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div class="box">
    <div class="box-header">
      <h3>Saldo Table</h3>
    </div>

    <div class="box-body">
      <div class="table-responsive">
          <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Date</th>
                <th>In/Out</th>
                <th>Requested By</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
              <?php $no = 1; ?>
              @foreach($detail as $data)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$data->created_at}}</td>
                @if($data->status == 'In')
                <td>+ {{$data->qty}} {{$data->unit}}</td>
                @else
                <td>- {{$data->qty}} {{$data->unit}}</td>
                @endif
                <td>{{$data->name}}</td>
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