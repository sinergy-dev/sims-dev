@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">
    <h1>
      Detail Claim - {{ $tampilkan->id_project }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Claim Management</li>
      <li class="active">Detail Claim - {{ $tampilkan->no }}</li>
    </ol>
  </section>

  <section class="content">
    <a href="{{url('/esm')}}"><button class="btn btn-primary-back-en pull-left"><i class="fa fa-arrow-circle-o-left"></i>&nbspback to Claim Management</button></a> <p>&nbsp</p>
    <br>

    <div class="box">
      <div class="box-header">
        <div class="box-header with-border">
          <h3 class="box-title">Detail Claim - {{ $tampilkan->no }}</h3>
        </div>
      </div>

      <div class="box-body">
        <div class="box-body">
          <h6 class="pull-left">{{ $tampilkan->no }}</h6>
          <h6 class="pull-right" id="date_create" name="date_create">{!!substr( $tampilkan->created_at,0,10 )!!}</h6><br><br>
          <h4 class="pull-left">{{ $tampilkan->description }}</h4>
          <h5 class="pull-right">Personnel : <i>{{$tampilkan->name}}</i></h5><br><br>
          
          <h4>ID Project : <i>{{$tampilkan->id_project}}</i></h4>
          <h5>Type : <i>{{$tampilkan->type}}</i></h5>
          <h6 >Amount : Rp <i class="money">{{ $tampilkan->amount }}</i></h6>
          <h6>Remarks : <i>{{$tampilkan->remarks}}</i></h6>
        </div>
        <div class="box-footer">Posted {{ $tampilkan->created_at }}</div>
      </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Progress</h3>
          
          <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
            <div class="card-header">
              <i class="fa fa-table"></i>&nbsp Engineer Spent Management Table
            <div class="pull-right">
          <div class="pull-right">
          @if(Auth::User()->id_position == 'HR MANAGER' && Auth::User()->id_division == 'HR' || Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'FINANCE')
            @if($tampilkan->status == 'HRD' || $tampilkan->status == 'FINANCE')
                <button class="btn btn-xs btn-warning pull-right" style="width: 100px" data-target="#keterangan" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspReturn</button>
            @endif
          @endif
          </div>
        </div>
      </div>
    </div>

      <div class="box-body">
      @if(Auth::User()->id_position == 'HR MANAGER' && Auth::User()->id_division == 'HR')
      <div class="card mb-3">
        
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $number = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_esm as $data)
                      <tr>
                        <td>{{$number++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td><i class="money"></i>
                          @if($data->amount == NULL)
                          -
                          @elseif($data->amount != NULL)
                          {{$data->amount}}
                          @endif
                        </td>
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
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Revised Amount</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $number = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_esm as $data)
                      <tr>
                        <td>{{$number++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td><i class="money"></i>
                          @if($data->amount == NULL)
                          -
                          @elseif($data->amount != NULL)
                          {{$data->amount}}
                          @endif
                        </td>
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
      <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Keterangan</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Submit Oleh</th>
                </tr>
              </thead>
              <?php $number = 1; ?>
              <tbody id="products-list" name="products-list">
                @foreach($detail_esm as $data)
                      <tr>
                        <td>{{$number++}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->keterangan}}</td>
                        <td><i class="money"></i>
                          @if($data->amount == NULL)
                          -
                          @elseif($data->amount != NULL)
                          {{$data->amount}}
                          @endif
                        </td>
                        <td>{{$data->status}}</td>
                        <td>{{$data->name}}</td>
                      </tr>
                @endforeach
              </tbody>
            </table>
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
          <form method="POST" action="{{url('tambah_return_hr')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="no_return_hr" name="no_return_hr" value="{{$nomor->no}}" hidden>
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
          <form method="POST" action="{{url('tambah_return_fnc')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="no_return_fnc" name="no_return_fnc" value="{{$nomor->no}}" hidden>
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
@section('content')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript">
   $('.money').mask('000,000,000,000,000', {reverse: true});
</script>
@endsection