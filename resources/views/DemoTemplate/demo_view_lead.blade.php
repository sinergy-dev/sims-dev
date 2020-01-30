@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/view_lead')}}">Report</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12 form-group">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Lead Table
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <b><i class="fa fa-download"></i> Export</b>
          </button>
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{action('ReportController@downloadPdflead')}}"> PDF </a>
            <a class="dropdown-item" href="{{action('ReportController@exportExcelLead')}}"> EXCEL </a>
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
                @foreach($lead as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{!!substr($data->created_at,0,10)!!}</td>
                  <td>{{ $data->name }}</td>
                  @if($data->amount != NULL)
                  <td><i  class="money">{{ $data->amount }},00</i></td>
                  @else
                  <td></td>
                  @endif
                  <td>
                    @if($data->result == 'OPEN')
                      <label class="status-initial">INITIAL</label>
                    @elseif($data->result == '')
                      <label class="status-open">OPEN</label>
                    @elseif($data->result == 'SD')
                      <label class="status-sd">SD</label>
                    @elseif($data->result == 'TP')
                      <label class="status-tp">TP</label>
                    @elseif($data->result == 'WIN')
                      <label class="status-win">WIN</label>
                    @else
                      <label class="status-lose">LOSE</label>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                @if(Auth::User()->id_territory != NULL)
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_position == 'DIRECTOR')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
                  <th colspan="5" style="text-align: right;">Total Amount:</th>
                  <th><i>Rp</i><i  class="money">{{$total_ter}},00</i></p></th>
                  <th colspan="2"></th>
                @else
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