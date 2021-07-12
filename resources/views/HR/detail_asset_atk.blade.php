@extends('template.main')
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection

@section('content')
<section class="content-header">
  <!-- <h1>
    SIP Detail ATK
  </h1> -->
  <a href="{{url('/asset_atk')}}"><button button class="btn btn-s btn-danger"><i class="fa fa-arrow-left"></i>&nbsp Back</button></a>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">HR/GA - Detail ATK</li>
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

  <div class="row">
    <div class="col-lg-6">
      <div class="box">
        <div class="box-header with-border">
          <h3>Detail Barang</h3>
        </div>

        <div class="box-body">
          <table class="table table-bordered">
            <tr>
              <td style="width:30%">Name</td>
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

      <div class="box box-success">
        <div class="box-header with-border">
        <h3 class="box-title">Summary Table</h3>
        </div>

        <div class="box-body">
          <div class="table-responsive">
              <table class="table table-bordered display no-wrap" id="summary_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Month</th>
                    <th>In</th>
                    <th>Out</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1; ?>
                  @foreach($summary as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{date("F",strtotime($data->month))}}</td>
                    <td>{{$data->sum_in}}</td>
                    <td>{{$data->sum_out}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>  
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">Saldo Table</h3>
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
    </div>
  </div>

</section>
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
    $('#data_Table').DataTable({
      pageLength: 25,
    })

    $('#summary_table').DataTable({
      pageLength: 25,
    })
</script>
@endsection