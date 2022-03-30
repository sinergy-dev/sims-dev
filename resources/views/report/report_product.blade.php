@extends('template.main')
@section('tittle')
Report Product
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
    .header th:first-child{
        background-color: #003A95 !important;
        color: white;
      }

      .header th:nth-child(2){
        background-color: #4C4CA6 !important;
        color: white;

      }

      .header th:nth-child(3){
        background-color: #7561B8 !important;
        color: white;

      }

      .header th:nth-child(4){
        background-color: #9A76C9 !important;
        color: white;

      }

      .header th:nth-child(5){
        background-color: #BD8EDB!important;
        color: white;

      }

      .header th:nth-child(6){
        background-color: #DFA6ED !important;
        color: white;

      }

      .header th:nth-child(7){
        background-color: #FFC0FF !important;
        color: #801d0f;

      }

      .green1-color{
        background-color: #003A95 !important;
        color: white;
      }

      .green2-color{
        background-color: #4C4CA6 !important;
        color: white;

      }

      .green3-color{
        background-color: #7561B8 !important;
        color: white;

      }

      .green4-color{
        background-color: #9A76C9 !important;
        color: white;

      }

      .green5-color{
        background-color: #BD8EDB!important;
        color: white;

      }

      .green6-color{
        background-color: #DFA6ED !important;
        color: white;

      }

      .green7-color{
        background-color: #FFC0FF !important;
        color: #801d0f;

      }

  /*  .green1-color {
        background-color: #c2c3ff !important;
    }
    .green2-color {
        background-color: #d2c2ff !important;
    }
    .green3-color {
        background-color: #dcc2ff !important;
    }
    .green4-color {
        background-color: #e1c2ff!important;
    }
    .green5-color {
        background-color: #e8c2ff !important;
    }
    .green6-color {
        background-color: #f8c2ff !important;
    }
    .green7-color {
        background-color: #dbe2ff !important;
    }*/
  </style>
@endsection
@section('content')
  <section class="content-header">
	<h1>
	  Report Products
	</h1>
	<ol class="breadcrumb">
	  <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
	  <li class="active">Report</li>
	  <li class="active">Report Products</li>
	</ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Report Products</i></h3>
          </div>         

          <div class="box-body">              
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                <thead>
                  <tr class="header">
                    <th>PRODUCT</th>
                    @foreach($territory_loop as $data)
                    <th><center>{{$data->id_territory}}</center></th>
                    @endforeach
                    <th><center>TOTAL</center></th>
                  </tr>
                </thead>
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
  </section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function (){
  	var tables = $('#data_lead').dataTable({
  		"ajax":{
              "type":"GET",
              "url":"{{url('/getreportproduct')}}",
            },
            "columns": [
              {
                render: function ( data, type, row ) {
                  return '<i>'+ row.name_product +'</i>';
                  
                }
              },  
              // { "data": "name_product" },  
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.countTer1 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter1_price) + '</p></center>';
                  
                }
              },  
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.countTer2 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter2_price) + '</p></center>';
                  
                }
              },  
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.countTer3 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter3_price) + '</p></center>';
                  
                }
              },  
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.countTer4 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter4_price) + '</p></center>';
                  
                }
              },    
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.countTer5 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter5_price) + '</p></center>';
                  
                }
              }, 
              {
                render: function ( data, type, row ) {
                  return '<center> <b>[' + row.total_lead + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.total_price) + '</p></center>';
                  
                }
              },       
            ],
            "scrollX": false,
            "ordering": false,
            "processing": true,
            "paging": true,
            "rowCallback": function( row, data ) {
            	$('td', row).eq(0).addClass('green1-color');
  		        $('td', row).eq(1).addClass('green2-color');
  		        $('td', row).eq(2).addClass('green3-color');
  		        $('td', row).eq(3).addClass('green4-color');
  		        $('td', row).eq(4).addClass('green5-color');
  		        $('td', row).eq(5).addClass('green6-color');
  		        $('td', row).eq(6).addClass('green7-color');
  		    },
          rowGroup: {
              startRender: null,
              endRender: function ( rows, group ) {
                var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
                };

                var amount_ter1 = rows
                    .data()
                    .pluck('ter1_price')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_ter2 = rows
                    .data()
                    .pluck('ter2_price')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_ter3 = rows
                    .data()
                    .pluck('ter3_price')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_ter4 = rows
                    .data()
                    .pluck('ter4_price')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_ter5 = rows
                    .data()
                    .pluck('ter5_price')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                console.log(amount_ter1)
                return $('<tr><td>'+ '<b>' + 'Total Amount : ' + '</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter1 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter2 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter3 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter4 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter5 ) + '</b></center>' +'</td>' + '</tr>');
              }
          }
  	});
  })
</script>
@endsection