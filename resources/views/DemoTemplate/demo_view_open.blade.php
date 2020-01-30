@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/view_open')}}">Report</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Open Table
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <b><i class="fa fa-download"></i> Export</b>
          </button>
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{action('ReportController@downloadPdfopen')}}"> PDF </a>
            <a class="dropdown-item" href="{{action('ReportController@exportExcelOpen')}}"> EXCEL </a>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Lead ID</th>
                  <th>Customer</th>
                  <th>Opty Name</th>
                  <th>Create Date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                @foreach($open as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{{ $data->created_at}}</td>
                  <td>{{ $data->name }}</td>
                  <td><i>Rp</i><i  class="money">{{ $data->amount }},00</i></td>
                  @if(Auth::User()->id_divison != 'FINANCE')
                  <td>
                    <label class="status-open">Open</label>
                  </td>
                  @endif
                </tr>
                @endforeach
                @foreach($sd as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{{ $data->created_at}}</td>
                  <td>{{ $data->name }}</td>
                  <td><i  class="money">{{ $data->amount }},00</i></td>
                  <td>
                    <label class="status-sd">SD</label>
                  </td>
                </tr>
                @endforeach
                @foreach($tp as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{{ $data->created_at}}</td>
                  <td>{{ $data->name }}</td>
                  <td><i>Rp</i><i  class="money">{{ $data->amount }},00</i></td>
                  <td>
                    <label class="status-tp">TP</label>
                  </td>
                </tr>
                @endforeach
              </tbody>
              @if(Auth::User()->id_division != 'FINANCE')
              <tfoot>
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter_open+$total_ter_sd+$total_ter_tp}},00</i></p></th>
                  <th colspan="2"></th>
              </tfoot>
              @endif
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
     $('.money').mask('000,000,000,000,000.00', {reverse: true});
   </script>
@endsection