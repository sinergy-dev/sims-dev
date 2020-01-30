@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/view_lead')}}">Claim</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12 form-group">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Claim Table
          @if(Auth::User()->id_division == 'FINANCE')
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <b><i class="fa fa-download"></i> Export</b>
          </button>
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{action('ReportController@downloadPdflead')}}"> PDF </a>
            <a class="dropdown-item" href="{{action('ReportController@exportExcelLead')}}"> EXCEL </a>
          </div>
          @elseif(Auth::User()->id_division == 'HR')
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
             <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Personnel</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>ID Project</th>
                  <th>Remarks</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                @foreach($datas as $data)
                <tr>
                  <td>{{ $data->no }}</td>
                  <td>{{$data->date}}</td>
                  <td>{{$data->name}}</td>
                  <td>{{$data->type}}</td>
                  <td>{{$data->description}}</td>
                  <td class="money">{{$data->amount}}</td>
                  <td>{{$data->id_project}}</td>
                  <td>{{$data->remarks}}</td>

                  @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE')
                  <td>
                    @if($data->status == 'FINANCE')
                    <label class="status-open">PENDING</label>
                    @elseif($data->status == 'TRANSFER')
                    <label class="status-sd">TRANSFER</label>
                    @elseif($data->status == 'ADMIN')
                    <label class="status-lose">PENDING</label>
                    @elseif($data->status == 'HRD')
                    <label class="status-lose">PENDING</label>
                    @endif
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
  </div>
</div>
@endsection

@section('script')
   <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
   <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>

   <script type="text/javascript">
     $('.money').mask('000,000,000,000,000', {reverse: true});
   </script>
@endsection