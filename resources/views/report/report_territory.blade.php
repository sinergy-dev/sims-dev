@extends('template.main')
@section('tittle')
Report Customer
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
  <style type="text/css">
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
            background-color: #7c74a6 !important;
        }

        tr.group-end,
        tr.group-end:hover {
            font-style: bold;
            background-color: #18113d !important;
        }
        
        /*.dataTables_filter {display: none;}*/
  </style>
@endsection
@section('content')  
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
            <h3 class="box-title"><i class="fa fa-table"></i> Report Customer</h3>
          </div>   

          <div class="box-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control dates" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                    <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
                  </div>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <select class="select2 form-control" style="width:100%!important;" id="select2Customer" name="select2Customer">
                  </select>
                </div>
              </div>
              <div class="col-md-2" id="divDropdownFilterSales" style="display:none;">
                <div class="form-group">
                  <select class="select2 form-control" style="display: none;width:100%!important;" id="select2Sales" name="select2Sales">
                  </select>
                  <select class="select2 form-control" style="display: none;width:100%!important;" id="select2Direktor" name="select2SalesDirektor">
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <button class="btn btn-primary btnApply"><i class="fa fa-check-circle"></i> Apply</button>
                  <button class="btn btn-info reload-table"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
              </div> 
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="tabTerritory" style="display: none;">
                  @foreach($territory_loop as $data)
                   @if($data->id_territory == "TERRITORY 1")
                    <li class="nav-item active">
                      <a class="nav-link" id="{{ $data->code_ter }}" data-toggle="tab" href="#{{ $data->code_ter }}" role="tab" aria-controls="{{ $data->code_ter }}" aria-selected="true" onclick="changeTerritory('{{$data->id_territory}}')">{{ $data->id_territory }}</a>
                    </li>
                   @else
                   <li class="nav-item">
                        <a class="nav-link" id="{{ $data->code_ter }}" data-toggle="tab" href="#{{ $data->code_ter }}" role="tab" aria-controls="{{ $data->code_ter }}" aria-selected="true" onclick="changeTerritory('{{$data->id_territory}}')">{{ $data->id_territory }}</a>
                    </li>
                   @endif
                  @endforeach
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#msp" role="tab" aria-controls="msp" aria-selected="true" onclick="changeTerritory('msp')">MSP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ter_opp" data-toggle="tab" href="#ter_opp" role="tab" aria-controls="ter_opp" aria-selected="true" onclick="changeTerritory('OPERATION')">OPERATION</a>
                    </li>
                </ul>
              
                <div class="tab-content">
                  <div class="tab-pane active"  role="tabpanel" id="sip">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                        <thead>
                          <tr class="header">
                            <th>Customer - Sales (SIP)</th>
                            <th>territory</th>
                            <th></th>
                            <th><center>INITIAL</center></th>
                            <th></th>
                            <th><center>OPEN</center></th>
                            <th></th>
                            <th><center>SD</center></th>
                            <th></th>
                            <th><center>TP</center></th>
                            <th></th>
                            <th><center>WIN</center></th>
                            <th></th>
                            <th><center>LOSE</center></th>
                            <th></th>
                            <th><center>TOTAL</center></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                  <div id="msp" class="tab-pane"  role="tabpanel" >
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="data_leadmsp" width="100%" cellspacing="0">
                        <thead>
                          <tr class="header">
                            <th>Customer - Sales (MSP)</th>
                            <th>territory</th>
                            <th><center>INITIAL</center></th>
                            <th><center>OPEN</center></th>
                            <th><center>SD</center></th>
                            <th><center>TP</center></th>
                            <th><center>WIN</center></th>
                            <th><center>LOSE</center></th>
                            <th><center>TOTAL</center></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                
                </div>
            </div>
          </div>
        </div>  

        <div class="box">
          <div class="box-header">
              <h3 class="box-title">Customer per Territory</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="tbCusByTer">
                <thead>
                  <tr>
                      <th>Customer Name</th>
                      <th>Territory</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
@endsection
@section('script')  
  <script type="text/javascript">
    function initSelectCustomer(){
      $.ajax({
        url: "{{url('/project/getCustomer')}}",
        type: "GET",
        success: function(result) {
          var arr = result.data;
          var selectOption = [];
          var otherOption;

          var data = {
            id: -1,
            text: 'All Customer'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          $("#select2Customer").select2({
            placeholder:"Select Customer",
            // multiple:true,
            data:selectOption
          })
        }
      })
    }

    function initSelectSales(){
      var nik_sales,id_customer,start_date,end_date = ''
      var accesable = @json($feature_item);
      console.log(accesable)  
      accesable.forEach(function(item,index){
        $("#" + item).show()

        if (accesable.includes('select2Sales')) {
          $.ajax({
            url: "{{url('/project/getUserByTerritory')}}",
            type: "GET",
            data:{
              territory:"{{Auth::User()->id_territory}}"
            },
            success:function(result){
              var arr = result.data;
              var selectOption = [];
              var otherOption;

              var data = {
                id: -1,
                text: 'All Sales'
              };

              selectOption.push(data)
              $.each(arr,function(key,value){
                selectOption.push(value)
              })

              $("#select2Sales").select2({
                placeholder: "Select sales",
                data:selectOption
              })
            }
          })  

    
        } else if (accesable.includes("select2SalesDirektor")) {
          $.ajax({
            url: "{{url('/project/getSalesByTerritory')}}",
            type: "GET",
            success: function(result) {
              var arr = result.results;
              var selectOption = [];
              var otherOption;

              var data = {
                id: -1,
                text: 'All Sales'
              };

              selectOption.push(data)
              $.each(arr,function(key,value){
                selectOption.push(value)
              })

              $("#select2Sales").select2({
                placeholder:"Select Sales",
                // multiple:true,
                data:selectOption
              })
            }
          })
        }
               
      }) 
    }    
    
    var id_territory = $(".nav-item.active").contents().text().trim() 
    start_date = moment().startOf('year').format("YYYY-MM-DD 00:00:00")
    end_date = moment().endOf('year').format("YYYY-MM-DD 00:00:00")

    $(".btnApply").click(function(){
      $(".btnApply").attr("onclick",ApplyFilter())
    })

    function ApplyFilter(){
      territory = $(".nav-item.active").contents().text().trim();
      if(territory !== "ALL"){
        $('#data_lead').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "id_territory=" + territory + "&" + "id_customer=" + $("#select2Customer").val() + "&" + "nik_sales=" + $("#select2Sales").val()).load();
      } else{
        $('#data_lead').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "id_territory=" + territory + "&" + "id_customer=" + $("#select2Customer").val() + "&" + "nik_sales=" + $("#select2SalesDirektor").val()).load();
      }         
      $('#data_leadmsp').DataTable().ajax.url("{{url('getfiltercustomermsp')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
    }

    initReportTerritory();
    initSelectSales();
    initSelectCustomer();

    function initReportTerritory(){
      var id_territory = $(".nav-item.active").contents().text().trim()
      $("#data_lead").DataTable({
          "ajax":{
            "type":"GET",
            "url":"{{url('getreportterritory')}}?id_territory="+id_territory,
          },
          "columns": [
            // { "data": "name" },
            {
              render: function ( data, type, row ) {
                return '<b>[' + row.brand_name + ']</b>' + '<br>(' + row.name + ')';
              }
            },
            { "data": "id_territory" },
            {
              "data":"amount_INITIAL",
              "targets":[3],
              "searchable":true,
              "visible": false
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_INITIAL == null) {
                  return '<center> <b>[' + row.INITIAL + ']</center> </b>';
                }else{
                  return '<center> <b>[' + row.INITIAL + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_INITIAL) + '</p></center>';
                }
              },
              "orderData":[2],
            },
            {
              "data":"amount_OPEN",
              "targets":[5],
              "searchable":true,
              "visible": false
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_OPEN == null) {
                  return '<center> <b>[' + row.OPEN + ']</b> </center>';
                }else{
                  return '<center> <b>[' + row.OPEN + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_OPEN) + '</p><c/enter>';
                }
              },
              "orderData":[4],
            },
            {
              "data":"amount_SD",
              "searchable":true,
              "visible": false,
              "targets":[7],
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_SD == null) {
                  return '<center><b>[' + row.SD + ']</b></center>';
                }else{
                  return '<center><b>[' + row.SD + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_SD) + '</p></center>';
                }
              },
              "orderData":[6],
            },
            {
              "data":"amount_TP",
              "searchable":true,
              "targets":[9],
              "visible": false
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_TP == null) {
                  return '<center><b>[' + row.TP + ']</b></<center>';
                }else{
                  return '<center><b>[' + row.TP + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_TP) + '</p></center>';
                }
              },
              "orderData":[8],

            },
            {
              "data":"amount_WIN",
              "targets":[11],
              "searchable":true,
              "visible": false
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_WIN == null) {
                  return '<center><b>[' + row.WIN + ']</b></center>';
                }else{
                  return '<center><b>[' + row.WIN + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_WIN) + '</p></center>';
                }
              },
              "orderData":[10],
            },
            {
              "data":"amount_LOSE",
              "targets":[13],
              "searchable":true,
              "visible": false
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_LOSE == null) {
                  return '<center><b>[' + row.LOSE + ']</b></center>';
                }else{
                  return '<center><b>[' + row.LOSE + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_LOSE) + '</p></center>';
                }
              },
              "orderData":[12]
            },
            {
              "data":"amount_All",
              "visible": false,
              "searchable":true,
              "targets":[15],
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_All == null) {
                  return '<center><b>[' + row.All + ']</b></center>';
                }else{
                  return '<center><b>[' + row.All + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_All) + '</p></center>';
                }
              },
              "orderData":[14],

            },            
          ],
          "info":false,
          "scrollX": false,
          "order": [[ 1, "asc" ]],
          "orderFixed": [[1, 'asc']],
          "processing": true,
          "paging": false,
          "columnDefs": [
              { "visible": false, "targets": 1},
              { 
                "width": "10%", "targets": 0,
                "width": "10%", "targets": 3,
                "width": "10%", "targets": 4,
                "width": "10%", "targets": 5,
                "width": "10%", "targets": 6,
                "width": "10%", "targets": 7,
                "width": "10%", "targets": 8
              }
          ],
          rowsGroup:[1],
          "rowGroup" : {
            endRender: function ( rows, group ) {
                var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
                };

                var amount_INITIAL = rows
                    .data()
                    .pluck('amount_INITIAL')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_OPEN = rows
                    .data()
                    .pluck('amount_OPEN')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                var amount_SD = rows
                    .data()
                    .pluck('amount_SD')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_TP = rows
                    .data()
                    .pluck('amount_TP')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_WIN = rows
                    .data()
                    .pluck('amount_WIN')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_LOSE = rows
                    .data()
                    .pluck('amount_LOSE')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_All = rows
                    .data()
                    .pluck('amount_All')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                return $('<tr class="group-end"><td>' + '<b style="color:white;">' + 'Total Amount : ' + '</td>' + '<td>' + '<center><p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_INITIAL ) + '<p style="color:white"></center>' + '</td>' + '<td>' + '<center><p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_OPEN )+ '</p></center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_SD )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_TP ) + '</p>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_WIN )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_LOSE )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_All )+ '</p> </center>' + '</td>' + '</tr>');
            },
            dataSrc: 'id_territory',
            startRender: function(rows, group) {
                return $('<tr class="group"><td colspan="8">' + '<b style="color:white;">' + group + '</b>'+'</td></tr>');
            }
          }
          // "drawCallback": function ( settings ) {
          //   var api = this.api(),data;
          //   var rows = api.rows( {page:'current'} ).nodes();
            
          //   var last=null;
          //   api.column(1, {page:'current'} ).data().each( function ( group, i, row ) {
         //    var datas = row
         //            .data()
         //            .pluck(9);

         //    console.log(datas)
          //         if ( last !== group ) {
          //             $(rows).eq( i ).before(
          //                 '<tr class="group"><td colspan="9">'+'<b style="color:white">'+group+'</b>' + '<p style="text-align:right;color:white">' + "Total Amount : " + datas.amount + '</p>' +'</td></tr>'
          //             );
          //             last = group;
          //         }
          //   });

          // }

      })


      $("#data_leadmsp").DataTable({
          "ajax":{
            "type":"GET",
            "url":"{{url('getreportcustomermsp')}}",
          },
          "columns": [
            {
              render: function ( data, type, row ) {
                return row.brand_name;
              }
            },
            { "data": "name" },
            {
              render: function ( data, type, row ) {
                if (row.amount_INITIAL == null) {
                  return '<center> <b>[' + row.INITIAL + ']</center> </b>';
                }else{
                  return '<center> <b>[' + row.INITIAL + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_INITIAL) + '</p></center>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_OPEN == null) {
                  return '<center> <b>[' + row.OPEN + ']</b> </center>';
                }else{
                  return '<center> <b>[' + row.OPEN + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_OPEN) + '</p><c/enter>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_SD == null) {
                  return '<center><b>[' + row.SD + ']</b></center>';
                }else{
                  return '<center><b>[' + row.SD + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_SD) + '</p></center>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_TP == null) {
                  return '<center><b>[' + row.TP + ']</b></<center>';
                }else{
                  return '<center><b>[' + row.TP + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_TP) + '</p></center>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_WIN == null) {
                  return '<center><b>[' + row.WIN + ']</b></center>';
                }else{
                  return '<center><b>[' + row.WIN + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_WIN) + '</p></center>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_LOSE == null) {
                  return '<center><b>[' + row.LOSE + ']</b></center>';
                }else{
                  return '<center><b>[' + row.LOSE + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_LOSE) + '</p></center>';
                }
              }
            },
            {
              render: function ( data, type, row ) {
                if (row.amount_All == null) {
                  return '<center><b>[' + row.All + ']</b></center>';
                }else{
                  return '<center><b>[' + row.All + ']</b>' + '<br><p style="text-align:center">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount_All) + '</p></center>';
                }
              }
            },  
            
          ],
          // "searching": true,
          // "lengthChange": false,
          // "paging": false,
          "info":false,
          "scrollX": false,
          "order": [[ 1, "asc" ]],
          "orderFixed": [[1, 'asc']],
          "processing": true,
          "paging": false,
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
          rowsGroup:[1],
          "rowGroup" : {
            endRender: function ( rows, group ) {
                var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
                };

                var amount_INITIAL = rows
                    .data()
                    .pluck('amount_INITIAL')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_OPEN = rows
                    .data()
                    .pluck('amount_OPEN')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                var amount_SD = rows
                    .data()
                    .pluck('amount_SD')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_TP = rows
                    .data()
                    .pluck('amount_TP')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_WIN = rows
                    .data()
                    .pluck('amount_WIN')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_LOSE = rows
                    .data()
                    .pluck('amount_LOSE')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var amount_All = rows
                    .data()
                    .pluck('amount_All')
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                return $('<tr class="group-end"><td>' + '<b style="color:white;">' + 'Total Amount : ' + '</td>' + '<td>' + '<center><p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_INITIAL ) + '<p style="color:white"></center>' + '</td>' + '<td>' + '<center><p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_OPEN )+ '</p></center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_SD )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_TP ) + '</p>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_WIN )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_LOSE )+ '</p> </center>' + '</td>' + '<td> <center> <p style="color:white">' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display( amount_All )+ '</p> </center>' + '</td>' + '</tr>');
            },
            dataSrc: 'name',
            startRender: function(rows, group) {
                return $('<tr class="group"><td colspan="8">' + '<b style="color:white;">' + group + '</b>'+'</td></tr>');
            }
          }
          // "drawCallback": function ( settings ) {

          //   var api = this.api(),data;

          //   var rows = api.rows( {page:'current'} ).nodes();

          //   var last=null;

          //   api.column(1, {page:'current'} ).data().each( function ( group, i ) {
          //         if ( last !== group ) {
          //             $(rows).eq( i ).before(
          //                 '<tr class="group"><td colspan="8">'+'<b>'+group+'</b>'+'</td></tr>'
          //             );
   
          //             last = group;
          //         }
          //   });

          // }
      })


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

          territory = $(".nav-item.active").contents().text().trim();
          if(territory !== "ALL"){
            $('#data_lead').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "id_territory=" + territory).load();
          }          
          $('#data_leadmsp').DataTable().ajax.url("{{url('getfiltercustomermsp')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();


          // $('#data_lead').DataTable().ajax.url("{{url('filter_presales_each_year')}}?nik=" + nik + "&" + "year=" + $('#year_filter').val()).load();

      });

      $('.reload-table').click(function(){
        var id_territory = $(".nav-item.active").contents().text().trim()
        $('#select2Sales').empty();
        $('#select2SalesDirektor').empty();
        $('#select2Customer').empty();
        initSelectCustomer()
        initSelectSales()
        $('#data_lead').DataTable().ajax.url("{{url('getreportterritory')}}?id_territory="+id_territory).load();
        $('#data_leadmsp').DataTable().ajax.url("{{url('getreportcustomermsp')}}").load();
      })

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

          // $('#report_territory').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
          territory = $(".nav-item.active").contents().text().trim();
          console.log(territory)
          if(territory !== "ALL"){
            $('#data_lead').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "id_territory=" + territory).load();
          } else {
            $('#data_lead').DataTable().ajax.url("{{url('getFilterDateTerritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
          }
          $('#data_leadmsp').DataTable().ajax.url("{{url('getfiltercustomermsp')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
          
        }
      )
      
    }    

    function changeTerritory(id_territory) {
      start_date  = moment($('#reportrange').val().split(' - ')[0],'DD/MM/YYYY').format("YYYY-MM-DD HH:mm:ss");
      end_date    = moment($('#reportrange').val().split(' - ')[1],'DD/MM/YYYY').format("YYYY-MM-DD HH:mm:ss");
      if (id_territory == "all") {
        $('#data_lead').DataTable().ajax.url("{{url('getreportterritory')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
        $('#msp').css("display","none");
        $('#sip').css("display","block");
      }else if (id_territory == "msp") {
        $('#data_leadmsp').DataTable().ajax.url("{{url('getreportcustomermsp')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
        tableCustByTer.ajax.url("{{url('getCustomerPerTerritory')}}?id_territory=" + id_territory).load();
        $('#msp').css("display","block");
        $('#sip').css("display","none");
      }else{
        $('#data_lead').DataTable().ajax.url("{{url('getFilterTerritoryTabs')}}?start_date=" + start_date + "&" + "end_date=" + end_date + "&" + "id_territory=" + id_territory).load();
        tableCustByTer.ajax.url("{{url('getCustomerPerTerritory')}}?id_territory=" + id_territory).load();
        $('#msp').css("display","none");
        $('#sip').css("display","block");
      }
      // if (id_territory == "all") {
      //   $('#data_lead').DataTable().ajax.url("{{url('getreportterritory')}}").load();
      // }else{
      //   $('#data_lead').DataTable().ajax.url("{{url('getFilterTerritoryTabs')}}?id_territory=" + id_territory).load();
      // }
    }

    var tableCustByTer = $("#tbCusByTer").DataTable({
      "ajax":{
        "type":"GET",
        "url":"{{url('getCustomerPerTerritory')}}?id_territory="+id_territory,
      },
      "columns": [
        { "data": "brand_name" },
        { "data": "id_territory" },
      ],
      "columnDefs": [
        { visible: true, targets:1 },
        {"targets": 0,"orderable": false}
      ],
      "order": [[1, 'asc']],
      displayLength: 25,
      drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;

          api
              .column(1, { page: 'current' })
              .data()
              .each(function (group, i) {
                  if (last !== group) {
                      $(rows)
                          .eq(i)
                          .before('<tr class="group" style="color:white"><td colspan="3">' + group + '</td></tr>');

                      last = group;
                  }
              });
      },
    })
    
  </script>
@endsection