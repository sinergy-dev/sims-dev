@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">
    <h1>
      PR Asset Management - {{ $tampilkan->no_pr }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">PR Asset Management</li>
      <li class="active">Detail - {{ $tampilkan->no_pr }}</li>
    </ol>
  </section>

  <section class="content">
    <a href="{{url('/pr_asset')}}"><button class="btn btn-xs btn-danger pull-left" style="width: 150px"><i class="fa fa-arrow-circle-o-left"></i>&nbspback to List PR Asset</button></a> <p>&nbsp</p>
    <br>
    <div class="box">

      <div class="box-header with-border">
        <h3 class="box-title">PR Asset Management Detail</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
      </div>

      <div class="box-body">
        <div class="box-header with-border">
          <h6 class="box-title pull-left">{{$tampilkan->no_pr}}</h6>
          <h6 class="box-title pull-right">{{$tampilkan->date}}</h6>
        </div>
        <div class="box-body small">
          <h6 class="pull-left">
                  <div>
                  <table class="table table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th colspan="6" style="text-align: center;">LIST PRODUK SUPPLIER</th>
                    </tr>
                    <tr>
                      <th>NO</th>
                      <th>Nama Produk</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Total Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1;?>
                    @foreach($produks as $data)
                    <tr data-id="{{$data->id_product}}">
                      <!-- <td><input type="" class="transparant" name="" value="{{$data->id_product}}" readonly></td> -->
                      <td>{{$no++}}</td>
                      <td>{{$data->name_product}}</td>
                      <td>{{$data->qty}}</td>
                      <td><b class="money">{{$data->nominal}}00</b></td>
                      <td><b class="money">{{$data->total_nominal}}00</b></td>
                      <td>
                        <!-- <button class="btn btn-sm btn-primary" data-target="#update_produk" onclick="produk('{{$data->id_product}}','{{$data->name_product}}', '{{$data->qty}}', '{{$data->nominal}}')" data-toggle="modal" style="width: 30px;height: 30px"><i class="fa fa-edit fa-lg"></i></button> -->
                        @if($count_pam <= 1)
                        @else
                        <a href="{{ url('delete_produk?id_product='. $data->id_product) }}"> <button style="width: 30px;height: 30px" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i></button></a>
                         @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
                </h6>

                <h6 class="pull-right">
                  <div>
                  <table class="table table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th colspan="6" style="text-align: center;">LIST PRODUK CUSTOMER</th>
                    </tr>
                    <tr>
                      <th>NO</th>
                      <th>Nama Produk</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Total Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1;?>
                    @foreach($produks_cus as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->name_product_customer}}</td>
                      <td>{{$data->qty_customer}}</td>
                      <td><b class="money">{{$data->nominal_customer}}00</b></td>
                      <td><b class="money">{{$data->total_nominal_customer}}00</b></td>
                      <td>
                        @if($count_pam <= 1)
                        @else
                        <a href="{{ url('delete_produk?id_product='. $data->id_product) }}"> <button style="width: 30px;height: 30px" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-trash"></i></button></a>
                         @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
                </h6>
        </div>
        <div class="card-body small bg-faded">
          <div class="media">
            <div class="media-body">
              <h4></i></h4>
              <h5></i></h5>
              <h6><b class="money"></b></h6>
              <h6></h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Changes Log</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>

      <div class="box-body">
        
        @if(Auth::User()->id_position == 'HR MANAGER' && Auth::User()->id_division == 'HR')
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> PR Asset Management
          <div class="pull-right">
            @if($tampilkan->status == 'HRD')
                <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal" onclick="return_hr('{{$data->id_pam}}')"><i class="fa fa-spinner" ></i>&nbspReturn</button>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $no = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_pam as $data)
                      <tr>
                        <td>{{$no++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td>{{$data->status}}</td>
                        <td>{{$data->name}}</td>
                      </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'FINANCE')
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> PR Asset Management
          <div class="pull-right">
            @if($tampilkan->status == 'FINANCE')
                <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal" onclick="return_finance('{{$data->id_pam}}')"><i class="fa fa-spinner" ></i>&nbspReturn</button>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $no = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_pam as $data)
                      <tr>
                        <td>{{$no++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td>{{$data->status}}</td>
                        <td>{{$data->name}}</td>
                      </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @else
      <div class="card mb-3">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $no = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_pam as $data)
                      <tr>
                        <td>{{$no++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td>{{$data->status}}</td>
                        <td>{{$data->name}}</td>
                      </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif

      </div>
    </div>
  </section>

@if(Auth::User()->id_position == 'HR MANAGER')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Return</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{'/tambah_return_hr_pr_asset'}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="no_return_hr" name="no_return_hr" >
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
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
@elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
<div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Return</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{'/tambah_return_fnc_pr_asset'}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="no_return_fnc" name="no_return_fnc" >
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
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
@endif
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
</script>
@endsection