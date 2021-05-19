@extends('template.main')

@section('content')
<?php
header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
?>

<section class="content-header">
  <h1>
    Lead Register
  </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Lead Register</li>
  </ol>
</section>

<section class="content">
<div class="box">
  <div class="box-body">
    <div id="div_now">
      <div class="table-responsive">
          <div class="nav-tabs-custom active" id="sales-finance" role="tabpanel" aria-labelledby="sales-finance">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="active"><a href="#tab_1" data-toggle="tab">SIP</a></li>
              <li><a href="#tab_2" data-toggle="tab">MSP</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <table class="table table-bordered table-striped no-wrap" id="tab_sip" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Lead id</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      <th>Sales</th>
                      <th>Amount</th>
                      <th>No PO</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($leads as $data)
                      @if($data->id_company == '1')
                      <tr>
                        <td><a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a></td>
                        <td>{{ $data->brand_name}}</td>
                        <td>{{ $data->opp_name}}</td>
                        <td>{{ $data->name }}</td>
                        <td><i></i><i class="money">{{$data->amount}}</i></td>
                        <td>
                          -
                        </td>
                      </tr>
                      @endif
                    @endforeach
                    @foreach($lead as $data)
                      @if($data->id_company == '1')
                      <tr>
                        <td><a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a></td>
                        <td>{{ $data->brand_name}}</td>
                        <td>{{ $data->opp_name}}</td>
                        <td>{{ $data->name }}</td>
                        <td><i></i><i class="money">{{$data->amount}}</i></td>
                        <td>
                          {{$data->no_po}}
                        </td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tfoot>
                </table>
              </div>
              <div class="tab-pane " id="tab_2">
                <table class="table table-bordered table-striped no-wrap" id="tab_msp" width="100%" cellspacing="0">
                  <thead>
                    <tr >
                      <th>Lead id</th>
                      <th>Customer Name</th>
                      <th>Project Name</th>
                      <th>Sales</th>
                      <th>Amount</th>
                      <th>No PO</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($leads as $data)
                      @if($data->id_company == '2')
                      <tr>
                        <td><a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a></td>
                        <td>{{ $data->brand_name}}</td>
                        <td>{{ $data->opp_name}}</td>
                        <td>{{ $data->name }}</td>
                        <td><i></i><i class="money">{{$data->amount}}</i></td>
                        <td>
                          -
                        </td>
                      </tr>
                      @endif
                    @endforeach
                    @foreach($lead as $data)
                      @if($data->id_company == '2')
                      <tr>
                        <td><a href="{{ url ('/detail_project', $data->lead_id) }}">{{ $data->lead_id }}</a></td>
                        <td>{{ $data->brand_name}}</td>
                        <td>{{ $data->opp_name}}</td>
                        <td>{{ $data->name }}</td>
                        <td><i></i><i class="money">{{$data->amount}}</i></td>
                        <td>
                          {{$data->no_po}}
                        </td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tfoot>
                </table>
              </div>
            </div>
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
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
@endsection
@section('script')

<script type="text/javascript">

  $('.money').mask('000,000,000,000,000', {reverse: true});

  $('#tab_sip').dataTable({
     "responsive":true,
     "order": [[ "4", "desc" ]],
     pageLength: 50,
     "orderCellsTop": true,
     "footerCallback": function( row, data, start, end, display ) {
        var numFormat = $.fn.dataTable.render.number( '\,', 'Rp.' ).display;

        var api = this.api(),data;

        var filtered = api.column( 4, {"filter": "applied"} ).data().sum();

        $( api.column( 3 ).footer() ).html("Total Amount");

        $( api.column( 4 ).footer() ).html(numFormat(filtered)+'');

    },
  });

  $('#tab_msp').dataTable({
     "responsive":true,
     "order": [[ "4", "desc" ]],
     pageLength: 50,
     "orderCellsTop": true,
     "footerCallback": function( row, data, start, end, display ) {
        var numFormat = $.fn.dataTable.render.number( '\,', 'Rp.' ).display;

        var api = this.api(),data;

        var filtered = api.column( 4, {"filter": "applied"} ).data().sum();

        $( api.column( 3 ).footer() ).html("Total Amount");

        $( api.column( 4 ).footer() ).html(numFormat(filtered)+'');

    },
  });
</script>

@endsection