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
          <div class="box-body">
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
  <script type="text/javascript">
    initReportTerritory();
    // $('#report_territory').DataTable({
    //   "bLengthChange": false,
    //   "pageLength": 5,
    //   "columnDefs": [
    //     { "width": "10%", "targets": 1,
    //       "width": "10%", "targets": 2,
    //       "width": "10%", "targets": 3,
    //       "width": "10%", "targets": 4,
    //       "width": "10%", "targets": 5,
    //       "width": "10%", "targets": 6,
    //       "width": "10%", "targets": 7

    //     }
    //   ]
    // });

    function initReportTerritory(){
      $("#report_territory").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getreportterritory')}}",
        },
        "columns": [
          // { "data": "name" },
          { "data": "name" },
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
        "order": [[ 1, "desc" ]],
        "columnDefs": [
            { "visible": false, "targets": 1 }
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
    }

    // $('#ter_2').DataTable({
    //   "bLengthChange": false,
    //   // "ordering":false,
    //   "pageLength": 20,
    //   "columnDefs": [
    //     { "width": "10%", "targets": 1,
    //       "width": "10%", "targets": 2,
    //       "width": "10%", "targets": 3,
    //       "width": "10%", "targets": 4,
    //       "width": "10%", "targets": 5,
    //       "width": "10%", "targets": 6,
    //       "width": "10%", "targets": 7

    //     }
    //   ]
    // });

    
  </script>
@endsection