@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Report Lead
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Report Lead</li>
  </ol>
  
</section>

<section class="content">
  
  <div class="box">
    
    <div class="box-header with-border">
          <div class="pull-right">
            <button type="button" class="btn btn-sm btn-warning dropdown-toggle float-right" data-toggle="dropdown" >
            Export <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a class="dropdown-item" href="{{action('ReportController@downloadPdflead')}}"> PDF </a></li>
              <li><a class="dropdown-item" href="{{action('ReportController@exportExcelLead')}}"> EXCEL </a></li>
            </ul>
            <!-- <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b><i class="fa fa-download"></i> Export</b>
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item" href="{{action('ReportController@downloadPdflead')}}"> PDF </a>
              <a class="dropdown-item" href="{{action('ReportController@exportExcelLead')}}"> EXCEL </a>
            </div> -->
          </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped dataTable" id="datas" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Lead ID</th>
              <th>Customer</th>
              <th>Opty Name</th>
              <th width="10%">Create Date</th>
              <th>Owner</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
            <tr id="status">
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
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
                @if($data->deal_price == NULL)
                  <td align="right" >
                    <i class="money">{{ $data->amount }}</i>
                  </td>
                @else
                  <td align="right" >
                    <i class="money">{{ $data->deal_price }}</i>
                  </td>
                @endif
              <td>
                @if($data->result == 'OPEN')
                  <label class="btn-xs status-initial">INITIAL</label>
                @elseif($data->result == '')
                  <label class="btn-xs status-open">OPEN</label>
                @elseif($data->result == 'SD')
                  <label class="btn-xs status-sd">SD</label>
                @elseif($data->result == 'TP')
                  <label class="btn-xs status-tp">TP</label>
                @elseif($data->result == 'WIN')
                  <label class="btn-xs status-win">WIN</label>
                @elseif($data->result == 'LOSE')
                  <label class="btn-xs status-lose">LOSE</label>
                @elseif($data->result == 'CANCEL')
                  <label class="btn-xs status-lose" style="background-color: #071108">CANCEL</label>
                @elseif($data->result == 'HOLD')
                  <label class="btn-xs status-initial" style="background-color: #919e92">HOLD</label>
                @elseif($data->result == 'SPECIAL')
                  <label class="btn-xs status-initial" style="background-color: #ddc23b">SPECIAL</label>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <th colspan="4"></th>
            <th></th>
            <th></th>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>

@endsection

@section('script')
   <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
   <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
   <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
   <script type="text/javascript" src="{{asset('js/sum().js')}}"></script>

   <script type="text/javascript">
     {{--  $('.money').mask('000,000,000,000,000.00', {reverse: true});  --}}
     $('.money').mask('000,000,000,000,000,000', {reverse: true});

     $('#datas').DataTable({
        "pagination":true,
        "ordering":false,
        // "scrollX": true,
        "order": [[ 3, "desc" ]],        
        "footerCallback": function( row, data, start, end, display ) {
        
          var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'Rp' ).display;

          var api = this.api(),data;
            // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
          typeof i === 'number' ?
            i : 0;
          };

          var total = api.column(5, {"filter": "applied"} ).data().sum();

          $( api.column( 4 ).footer() ).html("Total Amount");
         
          $( api.column( 5 ).footer() ).html(numFormat(total));

        },

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="form-control kat_drop" id="kat_drop" style="width:100%" name="kat_drop"><option value=""></option></select>')
                    .appendTo($("#status").find("th").eq(column.index()))
                    .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val());                                     

                    column.search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
                
                console.log(select);

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option>' + d + '</option>')
                });

                initkat();
            });
        }

     });

      
      function initkat()
      {
        $('.kat_drop').select2();
      }
   </script>
@endsection