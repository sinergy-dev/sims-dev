@extends('template.template')
@section('content')
     <div class="content-wrapper">
    <div class="container-fluid">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Detail</a>
        </li>
      </ol>

      <a href="{{url('/pr_asset')}}"><button class="btn btn-primary-back-en pull-left"><i class="fa fa-arrow-circle-o-left"></i>&nbspback to List PR Asset</button></a> <p>&nbsp</p>
      <br>

            <div class="card mb-3">
              <div class="card-body">
                <h6 class="card-title mb-1 pull-left">{{$tampilkan->no_pr}}</h6>
                <h6 class="card-title mb-1 pull-right">{{$tampilkan->date_handover}}</h6>
              </div>
              <hr class="my-0">
              <div class="card-body py-2 small">
                <h4 class="pull-left">From : {{$tampilkan->name}}</h4>
                <h5 class="pull-right"></i>To  : {{$tampilkan->to_agen}}</h5>
                <h6 class="pull-left" style="clear: both;"><b>List Product</b> :<br> @foreach($produks as $data) @if($tampilkan->id_pam == $data->id_pam)<br>-Produk&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: {{$data->name_product}}<br>&nbsp&nbspQty&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: {{$data->qty}}<br>
                &nbsp&nbspPrice &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: Rp. <i class="money">{{$data->nominal}}00</i><br>
                &nbsp&nbspTotal Price &nbsp&nbsp: Rp. <i class="money">{{$data->total_nominal}}00</i><br>
                &nbsp&nbspDescription&nbsp&nbsp: {{$data->description}}</i><br>@endif @endforeach</h6><br>
                <h6 class="pull-left" style="clear: both;"><b>Total Amount</b> : Rp.&nbsp<i class="money">{{$total_amount}}00</i></h6>
              </div>
              <div class="card-body small bg-faded">
                <div class="media">
                  <div class="media-body">
                    <h4></i></h4>
                    <h5></i></h5>
                    <h6 > <b class="money"></b></h6>
                    <h6></h6>
                  </div>
                </div>
              </div>
            </div>

      <div><legend><b>Changes Log</b></legend><!-- <button class="transparant pull-right"><i class="fa fa-arrow-down fa-3x"></i></button> --></div>
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
        <div class="card-header">
          <i class="fa fa-table"></i> PR Asset Management
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
      @endif
  </div>
</div>

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