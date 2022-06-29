@extends('template.main')
@section('tittle')
Report Brands
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

  </style>
@endsection
@section('content')
  <section class="content-header">
	<h1>
	  Report Brands
	</h1>
	<ol class="breadcrumb">
	  <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
	  <li class="active">Report</li>
	  <li class="active">Report Brands</li>
	</ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Report Brands</i></h3>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control dates" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                      <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
                    </div>
                  </div>
                  <div class="col-md-2 form-group">
                    <label>Brand</label>
                    <select class="select2 form-control" style="width:100%;" id="select2Product" name="select2Product">                 
                    </select>
                  </div>
                  <div class="col-md-1 form-group" style="margin-top:5px">
                    <br>
                    <button class="btn btn-primary btnApply"><i class="fa fa-check-circle"></i> Apply</button> 
                  </div>
                    
                  <div class="col-md-4" style="margin-bottom: 0px; margin-top: 0px;">
                  <label>Search</label>
                  <div class="input-group pull-right">
                    <input id="searchBrand" type="text" class="form-control" onkeyup="searchCustom('data_product','searchBrand')" placeholder="Search Anything">
                    
                    <div class="input-group-btn">
                      <button type="button" id="btnShowEntryRoleUser" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Show 10 entries
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#data_product').DataTable().page.len(10).draw();$('#btnShowEntryRoleUser').html('Show 10 entries')">10</a></li>
                        <li><a href="#" onclick="$('#data_product').DataTable().page.len(25).draw();$('#btnShowEntryRoleUser').html('Show 25 entries')">25</a></li>
                        <li><a href="#" onclick="$('#data_product').DataTable().page.len(50).draw();$('#btnShowEntryRoleUser').html('Show 50 entries')">50</a></li>
                        <li><a href="#" onclick="$('#data_product').DataTable().page.len(100).draw();$('#btnShowEntryRoleUser').html('Show 100 entries')">100</a></li>
                      </ul>
                    </div>
                    <span class="input-group-btn">
                      <button onclick="searchCustom('data_product','searchBrand')" type="button" class="btn btn-default btn-flat">
                        <i class="fa fa-fw fa-search"></i>
                      </button>
                    </span>
                  </div>
                </div>
                <div class="col-md-2" style="margin-top:5px">
                  <br>
                  <button class="btn btn-info" onclick="reloadTable()"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
                              
            </div>
          </div>         

          <div class="box-body">              
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_product" width="100%" cellspacing="0">
                <thead>
                  <tr class="header">
                    <th>BRANDS</th>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
  function initproduct(){
      $('.dates').daterangepicker({
        startDate: moment().startOf('year'),
        endDate  : moment().endOf('year'),
        locale: {
          format: 'DD/MM/YYYY'
        }
      },function (start, end) {
          start: moment();
          end  : moment();

          start_date  = start.format("YYYY-MM-DD 00:00:00");
          end_date    = end.format("YYYY-MM-DD 00:00:00");

          // $('#report_territory').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
          $('#data_product').DataTable().ajax.url("{{url('/getFilterProduct')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "name_product=" + $("#select2Product").val()).load();


          // $('#data_product').DataTable().ajax.url("{{url('filter_presales_each_year')}}?nik=" + nik + "&" + "year=" + $('#year_filter').val()).load();

      });

      $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'This Month'   : [moment().startOf('month'), moment().endOf('month')],
            'Last 3 Month' : [moment().startOf('month').subtract(3, 'months'), moment().endOf('month')],
            'Last 6 Month' : [moment().startOf('month').subtract(6, 'months'), moment().endOf('month')],
            'Last Year'    : [moment().startOf('year').subtract(1, 'year'),moment().endOf('year').subtract(1, 'year')],
            'This Year'    : [moment().startOf('year'),moment().endOf('year')],
          },
          locale: {
            format: 'DD/MM/YYYY'
          }
        },
        function (start, end) {
          $('#reportrange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))

          start_date  = start.format("YYYY-MM-DD 00:00:00");
          end_date    = end.format("YYYY-MM-DD 00:00:00");

          $('#data_product').DataTable().ajax.url("{{url('/getFilterProduct')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "name_product=" + $("#select2Product").val()).load();
        }
      )

      var tables = $('#data_product').dataTable({
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
            "lengthChange": false,
            "paging": true,
            sDom: 'lrtip',
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

                return $('<tr><td>'+ '<b>' + 'Total Amount : ' + '</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter1 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter2 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter3 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter4 ) + '</b></center>' +'</td>' + '<td>' + '<center><b>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_ter5 ) + '</b></center>' +'</td>' + '</tr>');
              }
          }
      });
  }
  
  function searchCustom(id_table,id_seach_bar){
    $("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
  }

  function reloadTable(){
    $('#data_product').DataTable().search("").draw()
    $('#data_product').DataTable().ajax.url("{{url('/getreportproduct')}}").load();
    $('#select2Product').empty();
    initSelectCustomer()
  }

  function initSelectCustomer(){
    $.ajax({
      url: "{{url('/project/getProductTag')}}",
      type: "GET",
      success: function(result) {
        var arr = result.results;
        var selectOption = [];
        var otherOption;

        var data = {
          id: -1,
          text: 'All Brand'
        };

        selectOption.push(data)
        $.each(arr,function(key,value){
          selectOption.push(value)
        })

        $("#select2Product").select2({
          placeholder:"Select Customer",
          // multiple:true,
          data:selectOption
        })
      }
    })
  }

  $(document).ready(function (){  
    initproduct()
    reloadTable()
    initSelectCustomer()
    $.ajax({
      url: "{{url('/getTerritory')}}",
      type: "GET",
      success: function(result) {
        $("#select2Territory").select2({
          placeholder:"Select Sales",
          // multiple:true,
          data:result.data
        })
      }
    })

     

    start_date = moment().startOf('year').format("YYYY-MM-DD 00:00:00")
    end_date = moment().endOf('year').format("YYYY-MM-DD 00:00:00")

    $(".btnApply").click(function(){
      $(".btnApply").attr("onclick",ApplyFilter($("#select2Product").val()))
    })

    function ApplyFilter(val){
      if (val == -1) {
        $('#data_product').DataTable().ajax.url("{{url('/getreportproduct')}}").load();
      }else{
        $('#data_product').DataTable().ajax.url("{{url('/getFilterProduct')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "name_product=" + $("#select2Product").val()).load();
      }
      
    }    
  })
</script>
@endsection