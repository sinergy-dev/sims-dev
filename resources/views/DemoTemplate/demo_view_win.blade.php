@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/view_win')}}">Report</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Win Table
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <b><i class="fa fa-download"></i> Export</b>
          </button>
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{action('ReportController@downloadPdfwin')}}"> PDF </a>
            <a class="dropdown-item" href="{{action('ReportController@exportExcelWin')}}"> EXCEL </a>
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
                @foreach($win as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{{ $data->created_at}}</td>
                  <td>{{ $data->name }}</td>
                  @if($data->amount != NULL)
                  <td><i  class="money">{{ $data->amount }},00</i></td>
                  @else
                  <td></td>
                  @endif
                  <td>
                    <label class="status-win">Win</label>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                @if(Auth::User()->id_division != 'FINANCE')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @endif
              </tfoot>
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