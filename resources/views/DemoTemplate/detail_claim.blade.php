@extends('template.template')
@section('content')
     <div class="content-wrapper">
    <div class="container-fluid">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Detail</a>
        </li>
      </ol>

      <a href="{{url('/esm')}}"><button class="btn btn-primary-back-en pull-left"><i class="fa fa-arrow-circle-o-left"></i>&nbspback to Claim Management</button></a> <p>&nbsp</p>
      <br>

        <div class="col-md-12">
            <div class="card mb-3">
              <div class="card-body">
                <h6 class="card-title mb-1 pull-left">{{ $tampilkan->no }}</h6>
                <h6 class="card-title mb-1 pull-right" id="date_create" name="date_create">{!!substr( $tampilkan->created_at,0,10 )!!}</h6>
              </div>
              <hr class="my-0">
              <div class="card-body py-2 small">
                <h4 class="pull-left">{{ $tampilkan->description }}</h4>
                <h5 class="pull-right">Personnel : <i>{{$tampilkan->name}}</i></h5>
              </div>
              <div class="card-body small bg-faded">
                <div class="media">
                  <div class="media-body">
                    <h4>ID Project : <i>{{$tampilkan->id_project}}</i></h4>
                    <h5>Type : <i>{{$tampilkan->type}}</i></h5>
                    <h6 >Amount : Rp <i class="money">{{ $tampilkan->amount }}</i></h6>
                    <h6>Remarks : <i>{{$tampilkan->remarks}}</i></h6>
                  </div>
                </div>
              </div>
              <div class="card-footer small text-muted">Posted {{ $tampilkan->created_at }}</div>
            </div>
          </div>

      @if(Auth::User()->id_position == 'HR MANAGER' && Auth::User()->id_division == 'HR')
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Engineer Spent Management Table
          <div class="pull-right">
            @if($tampilkan->status == 'HRD')
                <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspReturn</button>
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
        <div class="card-header">
          <i class="fa fa-table"></i> Engineer Spent Management Table
          <div class="pull-right">
            @if($tampilkan->status == 'FINANCE')
                <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspReturn</button>
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
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Engineer Spent Management Table
        </div>
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