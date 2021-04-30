@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">

  <h1>
    Report Open
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Report Open</li>
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
              <li><a class="dropdown-item" href="{{action('ReportController@downloadPdfopen')}}"> PDF </a></li>
              <li><a class="dropdown-item" href="{{action('ReportController@exportExcelOpen')}}"> EXCEL </a></li>
            </ul>
        </div>
      </div>
    
      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data_Table" width="100%" cellspacing="0">
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
                @foreach($open as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name}}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{{ $data->created_at}}</td>
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
                    <label class="status-tp">TP</label>
                  </td>
                </tr>
                @endforeach
              </tbody>
              @if(Auth::User()->id_division != 'FINANCE')
              <tfoot>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tfoot>
              @endif
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

     // $('#data_Table').DataTable( {
     //  "order": [[ 0, "desc" ]],
     //    // scrollX:        true,
     //    scrollCollapse: true,
     //    fixedColumns:   {
     //        leftColumns: 4
     //    },
     //  });

      $('#data_Table').DataTable({
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

          var total = api.column(5,  {"filter": "applied"}  ).data().sum();

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


