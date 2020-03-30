@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
    .dataTables_filter {
      display: none;
    }

    .header th:first-child{
      background-color: #dddddd;
    }

    .header th:nth-child(2){
      color: white;
      background-color: #7735a3;
    }

    .header th:nth-child(3){
      color: white;
      background-color: #f2562b;
    }

    .header th:nth-child(4){
      color: white;
      background-color: #04dda3;
    }

    .header th:nth-child(5){
      color: white;
      background-color: #f7e127;
    }

    .header th:nth-child(6){
      color: white;
      background-color: #246d18;
    }

    .header th:nth-child(7){
      color: white;
      background-color: #e5140d;
    }

    .header-child th{
      background-color: #f5f3ed;
    }

    tr.group,
    tr.group:hover {
        font-style: bold;
        background-color: #ddd !important;
    }
  </style>
  
  <section class="content-header">
    <h1>
      Report Customer
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
      <li class="active">Report Customer</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Report Customer By Territory</i></h3>
          </div>

          <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
              <div class="col-md-8">
                <div class="pull-left">
                  <div class="input-group" style="float: left">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control dates" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                    <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
                    <button class="btn btn-info reload-table" style="float: right;margin-left: 5px"><i class="fa fa-refresh"></i> Refresh</button>
                  </div>
                </div> 
              </div>
            </div>         
          </div>
          

          <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="nav-item active">
                        <a class="nav-link" id="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true" onclick="changeTerritory('all')">
                            All
                        </a>
                    </li>
                  @foreach($territory_loop as $data)
                    <li class="nav-item">
                        <a class="nav-link" id="{{ $data->code_ter }}" data-toggle="tab" href="#{{ $data->code_ter }}" role="tab" aria-controls="{{ $data->code_ter }}" aria-selected="true" onclick="changeTerritory('{{$data->id_territory}}')">
                            {{ $data->id_territory }}
                        </a>
                    </li>
                  @endforeach
                </ul>

                <div class="tab-content">
                  <div class="tab-pane active"  role="tabpanel" >
                  
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                        <thead>
                          <tr class="header">
                            <th>Customer - Sales</th>
                            <th>territory</th>
                            <th>INITIAL</th>
                            <th>OPEN</th>
                            <th>SD</th>
                            <th>TP</th>
                            <th>WIN</th>
                            <th>LOSE</th>
                            <th>TOTAL</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered display nowrap" id="report_territory" width="100%" cellspacing="0">
                  <thead>
                    <tr class="header">
                      <th>Customer - Sales</th>
                      <th>territory</th>
                      <th>INITIAL</th>
                      <th>OPEN</th>
                      <th>SD</th>
                      <th>TP</th>
                      <th>WIN</th>
                      <th>LOSE</th>
                      <th>TOTAL</th>
                    </tr>
                  </thead>
                  <tbody id="territory" name="territory">
                      
                  </tbody>
              </table>
            </div>  
          </div>
        </div>  
      </div>
    </div>
  </section>
@endsection
@section('script')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script type="text/javascript">
    initReportTerritory();

    function initReportTerritory(){
      $("#data_lead").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getreportterritory')}}",
        },
        "columns": [
          // { "data": "name" },
          {
            render: function ( data, type, row ) {
              return '<b>' + row.brand_name + '</b>' + '<br>(' + row.name + ')';
            }
          },
          { "data": "id_territory" },
          { "data": "INITIAL" },
          { "data": "OPEN" },
          { "data": "SD" },
          { "data": "TP" },
          { "data": "WIN" },
          { "data": "LOSE" },
          { "data": "All" },
          
        ],
        "searching": true,
        "lengthChange": false,
        // "paging": false,
        "info":false,
        "scrollX": false,
        "order": [[ 1, "asc" ]],
        "processing": true,
        "columnDefs": [
            { "visible": false, "targets": 1},
            { 
              "width": "5%", "targets": 2,
              "width": "5%", "targets": 3,
              "width": "5%", "targets": 4,
              "width": "5%", "targets": 5,
              "width": "5%", "targets": 6,
              "width": "5%", "targets": 7,
              "width": "5%", "targets": 8
            }
        ],
        "drawCallback": function ( settings ) {

          var api = this.api(),data;

          var rows = api.rows( {page:'current'} ).nodes();

          var last=null;

          api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">'+'<b>'+group+'</b>'+'</td></tr>'
                    );
 
                    last = group;
                }
          });

        }
      })


      $("#report_territory").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getreportterritory')}}",
        },
        "columns": [
          // { "data": "name" },
          {
            render: function ( data, type, row ) {
              return '<b>' + row.brand_name + '</b>' + '<br>(' + row.name + ')';
            }
          },
          { "data": "id_territory" },
          { "data": "INITIAL" },
          { "data": "OPEN" },
          { "data": "SD" },
          { "data": "TP" },
          { "data": "WIN" },
          { "data": "LOSE" },
          { "data": "All" },
          
        ],
        "searching": true,
        "lengthChange": false,
        // "paging": false,
        "info":false,
        "scrollX": false,
        "order": [[ 1, "asc" ]],
        "processing": true,
        "columnDefs": [
            { "visible": false, "targets": 1},
            { 
              "width": "5%", "targets": 2,
              "width": "5%", "targets": 3,
              "width": "5%", "targets": 4,
              "width": "5%", "targets": 5,
              "width": "5%", "targets": 6,
              "width": "5%", "targets": 7,
              "width": "5%", "targets": 8
            }
        ],
        "drawCallback": function ( settings ) {

          var api = this.api(),data;

          var rows = api.rows( {page:'current'} ).nodes();

          var last=null;

          api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">'+'<b>'+group+'</b>'+'</td></tr>'
                    );
 
                    last = group;
                }
          });

        }
      })

      $('.dates').daterangepicker({
      },function (start, end) {
          start: moment();
          end  : moment();

          start_date  = start.format("YYYY-MM-DD HH:mm:ss");
          end_date    = end.format("YYYY-MM-DD HH:mm:ss");

          $('#report_territory').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();

          // $('#data_lead').DataTable().ajax.url("{{url('filter_presales_each_year')}}?nik=" + nik + "&" + "year=" + $('#year_filter').val()).load();

      });

      $('.reload-table').click(function(){
        console.log("clicked")
        $('#report_territory').DataTable().ajax.url("{{url('getreportterritory')}}").load();
      })

      $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate  : moment()
        },
        function (start, end) {
          console.log(start)
          $('#reportrange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'))

          start_date  = start.format("YYYY-MM-DD HH:mm:ss");
          end_date    = end.format("YYYY-MM-DD HH:mm:ss");

          $('#report_territory').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
        }
      )
    }

    function changeTerritory(id_territory) {
      $('#data_lead').DataTable().ajax.url("{{url('getFilterTerritoryTabs')}}?id_ter='" + id_territory + "'").load();
    }
    
  </script>
@endsection