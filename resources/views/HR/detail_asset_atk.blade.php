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
        <h3 class="box-title">Summary Transaksi</h3>
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
                </tbody>
              </table>
          </div>  
        </div>
      </div>

      <div class="box box-success">
        <div class="box-header with-border">
        <h3 class="box-title">Summary Quantity</h3>
        </div>

        <div class="box-body">
          <div class="table-responsive">
              <table class="table table-bordered display no-wrap" id="summary_quantity" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Month</th>
                    <th>In</th>
                    <th>Out</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                </tbody>
              </table>
          </div>  
        </div>
      </div>

      <div class="box box-success">
        <div class="box-header with-border">
        <h3 class="box-title">Most Requested</h3>
        </div>

        <div class="box-body">
          <div class="table-responsive">
              <table class="table table-bordered display no-wrap" id="request_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Quantity</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
  <script type="text/javascript" src="http://cdn.datatables.net/plug-ins/1.10.15/dataRender/datetime.js"></script>
@endsection

@section('script')
<script type="text/javascript">
    $('#data_Table').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/asset_atk/getSaldoAtk')}}",
          "data":{
            "id_barang":window.location.href.split("/")[5]
          }
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { 
            render: function (data,type,row){
              return row.created_at.substring(0,10)
            } 
          },
          {
            render: function (data, type, row) {
              if(row.status == 'In'){
               return '+ ' + row.qty + ' ' + row.unit
              }else {
               return '- ' + row.qty + ' ' + row.unit
              }
            }  
          },
          { "data": "name"},
        ],
        columnDefs:[{targets:1, render:function(data){
          return moment(data).format('MMMM');
        }}],
        "order":[],
        "pageLength": 25
    })

    $("#summary_table").dataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/asset_atk/getSummaryAtk')}}",
          "data":{
            "id_barang":window.location.href.split("/")[5]
          }
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "month"},
          { "data": "sum_in"},
          { "data": "sum_out"},
        ],
        columnDefs:[{targets:1, render:function(data){
          return moment(data).format('MMMM');
        }}],
        "order":[]
    })

    $("#summary_quantity").dataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/asset_atk/getSummaryQty')}}",
          "data":{
            "id_barang":window.location.href.split("/")[5]
          }
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "month"},
          { "data": "sum_in"},
          { "data": "sum_out"},
        ],
        columnDefs:[{targets:1, render:function(data){
          return moment(data).format('MMMM');
        }}],
        "order":[]
    })

    $('#request_table').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('/asset_atk/getMostRequest')}}",
          "data":{
            "id_barang":window.location.href.split("/")[5]
          }
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "name"},
          { "data": "qty"},
        ],
        "order":[],
      pageLength: 25,
    })
</script>
@endsection