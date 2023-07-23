@extends('template.main')
@section('tittle')
  Dashboard Timesheet
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">
  <style type="text/css">
    .dataTables_filter{
      display: none;
    }

    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }

    .select2-container {
      width: 100% !important;
    }

    .datatable-container-hidden {
      position: absolute;
      left: -9999px;
      top: -9999px;
    }

    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .pagination-link {
      display: inline-block;
      padding: 8px 12px;
      margin-right: 5px;
      text-decoration: none;
      color: #333;
      background-color: #f2f2f2;
      border: 1px solid #ddd;
      border-radius: 4px;
      cursor: pointer;
    }

    .pagination-link:hover {
      background-color: #ddd;
    }

    .pagination-link.active {
      background-color: #007bff!important;
      color: #fff!important;
      border-color: #007bff!important;
    }

    /* Styling for disabled anchor elements */
    .pagination-link:disabled {
      color: #ccc!important; /* Color for disabled anchors */
      pointer-events: none!important; /* Disable click events */
      cursor: not-allowed; /* Set cursor to "not-allowed" */
    }

    .loading-indicator {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: rgba(255, 255, 255, 0.8);
      padding: 10px;
      border-radius: 4px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      z-index: 9999;
    }
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
    Dashboard
    <small>Timesheet</small>
    </h1>
    <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-2 col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Filter by Month</label>
              <div id="monthFilter">
              </div>
            </div>
            <div class="form-group">
              <label>Filter by PIC</label>
              <select type="select" class="select2 form-control" id="selectPic" name="selectPic" onchange="customFilter(this.value,'selectPic')">
                <!-- <option></option> -->
              </select>
            </div>
            <div class="form-group">
              <label>Filter by Status</label>
              <select type="select" class="select2 form-control" id="selectStatus" name="selectStatus" onchange="customFilter(this.value,'selectStatus')">
                <!-- <option></option> -->
              </select>
            </div>
            <div class="form-group">
              <label>Filter by Task</label>
              <select type="select" class="select2 form-control" id="selectTask" name="selectTask" onchange="customFilter(this.value,'selectTask')">
                <!-- <option></option> -->
              </select>
            </div>
            <div class="form-group">
              <label>Filter by Year</label>
              <select type="select" class="select2 form-control" id="selectYear" name="selectYear" onchange="customFilter(this.value,'selectYear')"><option value=""></option></select>
            </div>
            <div class="form-group">
              <label>Filter by Schedule</label>
              <select type="select" class="select2 form-control" id="selectSchedule" name="selectSchedule" onchange="customFilter(this.value,'selectSchedule')"><option></option></select>
            </div>
            <button id="" class="btn btn-sm btn-info btn-block" onclick="resetFilter()"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
      <div class="col-md-10 col-xs-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#table" data-toggle="tab">Table</a></li>
            <li><a href="#chart" data-toggle="tab">Chart</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="table">
              <div class="box box-primary" id="box_mandays" >
                <div class="box-header bg-primary with-border">
                  <h3 class="box-title" style="color: white;">Summary of Mandays <span id="title_summary_year"></span></h3>
                  <span id="filterSumPoint" class="pull-right"><i class="fa fa-circle" style="color: red;"></i> <span style="color: white">not ready to filter..</span></span>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-6 col-xs-12 pull-right">
                      <b>Search Anything</b>
                      <div class="input-group pull-right">
                        <input id="searchBarMandays" type="text" class="form-control" placeholder="ex: Search Name...">
                        <div class="input-group-btn">
                          <button type="button" id="btnShowEntry" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Show 10 
                            <span class="fa fa-caret-down"></span>
                          </button>
                          <ul class="dropdown-menu" id="selectShowEntry">
                            <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                            <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                            <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                            <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                          </ul>
                        </div>
                        <span class="input-group-btn">
                          <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" onclick="clearFilterTable('tbSummaryMandays','searchBarMandays')" type="button" class="btn btn-default btn-flat">
                            <i class="fa fa-fw fa-remove"></i>
                          </button>
                        </span>
                        <span class="input-group-btn">
                          <button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Displayed Column
                            <span class="fa fa-caret-down"></span>
                          </button>
                          <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="1"><span class="text">Planned</span></li>
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="2"><span class="text">Actual</span></li>
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="3"><span class="text">Threshold</span></li>
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="4"><span class="text">Billable</span></li>
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="5"><span class="text">%Billable</span></li>
                            <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="6"><span class="text">Deviation</span></li>
                          </ul>
                          <button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" onclick="reloadTable('tbSummaryMandays')" type="button" class="btn btn-default btn-flat">
                            <i class="fa fa-fw fa-refresh"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table" id="tbSummaryMandays">
                    </table>
                  </div>
                  <!-- Loading element (spinner or message) -->
                  <div id="loadingIndicator" class="loading-indicator">
                    <!-- Add your loading content here (e.g., a spinner or loading message) -->
                    Loading...
                  </div>
                </div>
              </div>

              <div class="box box-primary" id="box_sbe" style="display:none;">
                <div class="box-header bg-primary with-border">
                  <h3 class="box-title" style="color: white;">Summary of SBE</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                      <div class="col-md-6 col-xs-12 pull-right">
                        <b>Search Anything</b>
                          <div class="input-group pull-right">
                            <input id="searchBarSbe" type="text" class="form-control" placeholder="ex: Search Name..">
                            <div class="input-group-btn">
                              <button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Show 10 
                                <span class="fa fa-caret-down"></span>
                              </button>
                              <ul class="dropdown-menu" id="selectShowEntryTicket">
                                <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                                <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                                <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                                <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                              </ul>
                            </div>
                            <span class="input-group-btn">
                              <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" onclick="clearFilterTable('tbSummarySbe','searchBarSbe')" type="button" class="btn btn-default btn-flat">
                                <i class="fa fa-fw fa-remove"></i>
                              </button>
                            </span>
                            <span class="input-group-btn">
                              <button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Displayed Column
                                <span class="fa fa-caret-down"></span>
                              </button>
                              <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="1"><span class="text">Planned</span></li>
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="2"><span class="text">Actual</span></li>
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="3"><span class="text">Threshold</span></li>
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="4"><span class="text">Billable</span></li>
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="5"><span class="text">%Billable</span></li>
                                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummarySbe',this)" data-column="6"><span class="text">Deviation</span></li>
                              </ul>
                              <button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" onclick="reloadTable('tbSummarySbe')" type="button" class="btn btn-default btn-flat">
                                <i class="fa fa-fw fa-refresh"></i>
                              </button>
                            </span>
                          </div>
                      </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table" id="tbSummarySbe">
                    </table>
                  </div>
                </div>
              </div>

              <div class="box box-primary" id="box_pid" style="display:none">
                <div class="box-header bg-primary with-border">
                  <h3 class="box-title" style="color: white;">Assign PID</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-6 col-xs-12 pull-right">
                        <b>Search Anything</b>
                        <div class="input-group pull-right">
                          <input id="searchBarAssignPID" onkeyup="searchCustom('tbAssignPID','searchBarAssignPID')" type="text" class="form-control" placeholder="ex: search Name...">
                          <div class="input-group-btn">
                            <button type="button" id="btnShowEntry" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                              Show 10 
                              <span class="fa fa-caret-down"></span>
                            </button>
                            <ul class="dropdown-menu" id="selectShowEntry">
                              <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                              <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                              <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                              <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                            </ul>
                          </div>
                          <span class="input-group-btn">
                            <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" onclick="clearFilterTable('tbAssignPID','searchBarAssignPID')" type="button" class="btn btn-default btn-flat">
                              <i class="fa fa-fw fa-remove"></i>
                            </button>
                            <button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" onclick="reloadTable('tbAssignPID')" type="button" class="btn btn-default btn-flat">
                              <i class="fa fa-fw fa-refresh"></i>
                            </button>
                          </span>
                        </div>
                    </div>
                  </div>
                  <div>
                    <table class="table" id="tbAssignPID">
                    </table>
                  </div>
                </div>
              </div>

              <div class="row" id="box_definition" style="display:none;">
              </div>
            </div>
            <div class="tab-pane" id="chart">
              <div class="box box-primary">
                <div class="box-header bg-primary" style="color:white">
                  <h3 class="box-title">Cummulative Mandays (Status Done)</h3>
                </div>
                <div class="box-body">
                  <canvas id="cummulativeMandaysChart" width="400" height="200"></canvas>
                </div>
              </div>

              <div class="box box-primary">
                <div class="box-header bg-primary" style="color:white">
                  <h3 class="box-title">Remaining <span id="span-remaining"></span> (Status Done)</h3>
                </div>
                <div class="box-body">
                  <div id="box-remaining">
                    <!-- <canvas id="remainingChart" width="400" height="200"></canvas> -->
                  
                  </div>
                  <div id="pagination" style="margin-top:20px" class="pull-right">
                    
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Level</h3>
                    </div>
                    <div class="box-body">
                      <canvas id="levelChart" width="400" height="200"></canvas>
                      <div id="definitionLevel"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Status</h3>
                    </div>
                    <div class="box-body">
                      <canvas id="statusChart" width="400" height="200"></canvas>
                      <div id="definitionStatus"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Schedule</h3>
                    </div>
                    <div class="box-body">
                      <canvas id="scheduleChart" width="400" height="200"></canvas>
                      <div id="definitionSchedule"></div>
                    </div>
                  </div>
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
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <!--datatable-->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
      var accesable = @json($feature_item);
      accesable.forEach(function(item,index){
        $("#" + item).show()
      })

      if (accesable.includes('box_pid')) {
        var tbSummarySbe = $("#tbSummarySbe").DataTable({
          "ajax":{
            type:"GET",
            url:"{{url('/timesheet/sumPointSbe')}}",
          },
          columns: [
            { title: 'PID', data:'pid'},
            { title: 'Name', data:'name'},
            { title: 'Planned',data:'planned'},
            { title: 'Actual',data:'actual'},
            // { title: 'Threshold' },
            // { title: 'Billable' },
            // { title: '%Billable' },
            // { title: 'Deviation' },
          ],
          lengthChange: false,
          columnDefs: [{ visible: false, targets: 0 }],
          order: [[0, 'asc']],
          lengthChange: false,
          drawCallback: function (settings) {
              var api = this.api();
              var rows = api.rows({ page: 'current' }).nodes();
              var last = null;

              api
              .column([0,2], { page: 'current' })
              .data()
              .each(function (group, i) {
                  if (last !== group) {
                      $(rows)
                          .eq(i)
                          .before('<tr class="group"><td colspan="2"><b>' + group + '</b></td><td><b>Total Mandays : '+ api.column(2).data()[i] +'</b></td></tr>');
                      last = group;
                  }
              });
          },
        }) 

        var tbPID = $("#tbAssignPID").DataTable({
          "ajax":{
            type:"GET",
            url:"{{url('/timesheet/getAllAssignPidByDivision')}}",
          },
          columns: [
            { title: 'pid',
              data: 'pid' 
            },
            { title: 'Name',
              data: 'name'
            },
            { title: 'Role',
              data: 'role' 
            },
          ],
          columnDefs: [{ visible: false, targets: 0 }],
          order: [[0, 'asc']],
          lengthChange: false,
          drawCallback: function (settings) {
              var api = this.api();
              var rows = api.rows({ page: 'current' }).nodes();
              var last = null;

              api
                  .column(0, { page: 'current' })
                  .data()
                  .each(function (group, i) {
                      if (last !== group) {
                          $(rows)
                              .eq(i)
                              .before('<tr class="group"><td colspan="2"><b>' + group + '</b></td></tr>');
                          last = group;
                      }
                  });
          },
        })
        $('#tbSummarySbe').removeClass('datatable-container-hidden');
        $("#tbAssignPID").removeClass('datatable-container-hidden');
      }else{
        $('#tbSummarySbe').addClass('datatable-container-hidden');
        $("#tbAssignPID").addClass('datatable-container-hidden');
      }

      isTbSummary = false
      var tbSummary = $("#tbSummaryMandays").DataTable({
        "ajax":{
          type:"GET",
          url:"{{url('/timesheet/sumPointMandays')}}",
        },
        columns: [
          { title: 
            'Name',
            render: function (data, type, row, meta){
              return '<a href="{{url("/timesheet?nik=")}}'+ row.nik +'" style="cursor:pointer">'+ row.name +'</a>'
            } 
          },
          { title: 'Planned',
            data:'planned'
          },
          { title: 'Actual', 
            data:'actual'
          },
          { title: 'Threshold',
            data:'threshold' 
          },
          { title: 'Billable', 
            data:'billable'
          },
          { title: '%Billable', 
            data:'percentage_billable'
          },
          { title: 'Deviation', 
            data:'deviation'
          },
        ],
        lengthChange: false,
        initComplete: function () {
          isTbSummary = true
          $("#filterSumPoint").find("i").css("color","#80ff80")
          $("#filterSumPoint").find("span").text("ready to filter")
          $('#loadingIndicator').hide();
          $.each($("#selectShowColumnTicket li input"),function(index,item){
            var column = $("#tablePerformance").DataTable().column(index)
            // column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
            $(item).prop('checked', column.visible())
          })
        }
      })

      $.ajax({
        type:"GET",
        url:"{{url('timesheet/getAllUser')}}",
        success:function(result) {
          $("#selectPic").select2({
            placeholder:"Select PIC",
            data:result,
            multiple:true
          })
        }
      })

      $.ajax({
        type:"GET",
        url:"{{url('timesheet/getTaskByDivision')}}",
        success:function(result) {
          $("#selectTask").select2({
            placeholder:"Select Task",
            data:result,
            multiple:true
          })
        }
      })

    })

    function changeColumnTable(id,data){
      var column = $("#"+id).DataTable().column($(data).attr("data-column"))
      column.visible( ! column.visible() );
    }

    function reloadTable(id){
      $('#'+id).DataTable().ajax.reload();
    }

    function clearFilterTable(id,id_seach_bar){
      $('#'+id_seach_bar).val('')
      $('#'+id).DataTable().search('').draw();
    }

    function searchCustom(id,id_seach_bar) {
      $("#"+id).DataTable().search($('#'+id_seach_bar).val()).draw(); 
    }

    function loopMonthsInRows() {
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      const rows = [];

      for (let i = 0; i < months.length; i += 3) {
        const row = months.slice(i, i + 3);
        rows.push(row);
      }
      return rows;
    }

    const monthRows = loopMonthsInRows();

    var appendMonth = ""
      $.each(monthRows,function(index,value){
      appendMonth = appendMonth + '<div class="row">'
          $.each(value,function(idx,values){
            appendMonth = appendMonth + '<div class="col-md-4 col-xs-12">'
            appendMonth = appendMonth +  '<div class="form-group">'
              appendMonth = appendMonth + '<div class="checkbox">'
              appendMonth = appendMonth + '  <label>'
              appendMonth = appendMonth + '  <input type="checkbox" id="cbMonth" class="cbMonth" value="'+ values +'" onchange="customFilter(this)">'
              appendMonth = appendMonth + values
              appendMonth = appendMonth + '  </label>'
              appendMonth = appendMonth + '</div>'
            appendMonth = appendMonth +  '</div>'
            appendMonth = appendMonth + '</div>'
          })
      appendMonth = appendMonth + '</div>'    
      })
    $("#monthFilter").append(appendMonth)

    $.ajax({
      type:"GET",
      url:"{{url('/timesheet/getTaskPhaseByDivisionForTable')}}",
      success:function(result){
        var appendDefinition = ""
        $.each(result,function(index,value){
          appendDefinition = appendDefinition + '<div class="col-md-6 col-xs-12">'
          appendDefinition = appendDefinition + '  <div class="box box-primary">'
          appendDefinition = appendDefinition + '    <div class="box-header bg-primary">'
          appendDefinition = appendDefinition + '      <h3 class="box-title" style="color:white">'+ index +'</h3>'
          appendDefinition = appendDefinition + '    </div>'
            appendDefinition = appendDefinition + '    <div class="box-body">'

              appendDefinition = appendDefinition + '   <table class="table" style="border-collapse: separate;border-spacing: 0 10px;">'
              $.each(value,function(idx,values){
                appendDefinition = appendDefinition + '     <tr>'
                  appendDefinition = appendDefinition + '       <td class="bg-info" style="width:200px">'+  values.title
                  appendDefinition = appendDefinition + '       </td>'
                  appendDefinition = appendDefinition + '       <td>'+  values.description
                  appendDefinition = appendDefinition + '       </td>'
                appendDefinition = appendDefinition + '     </tr>'
              })
              appendDefinition = appendDefinition + '   </table>'
            appendDefinition = appendDefinition + '    </div>'

          appendDefinition = appendDefinition + '  </div>'
          appendDefinition = appendDefinition + '</div>'
        })

        $("#box_definition").append(appendDefinition)
      }
    })
   
    //filter
    $("#selectStatus").select2({
      placeholder:"Select Status",
      data:[
        {
          id:"Done",
          text:"Done"
        },
        {
          id:"Cancel",
          text:"Cancel"
        },
        {
          id:"Reschedule",
          text:"Reschedule"
        },
        {
          id:"Undone",
          text:"Undone"
        },
      ],
      multiple:true
    })

    $("#selectSchedule").select2({
      placeholder:"Select Schedule",
      data:[
        {
          id:"Planned",
          text:"Planned"
        },
        {
          id:"Unplanned",
          text:"Unplanned"
        },
      ],
      multiple:true
    })

    arrFilterYear = []
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();

    arrFilterYear.push({
      id:currentYear,
      text:currentYear
    })

    $("#selectYear").select2({
      placeholder:"Select Year",
      data:arrFilterYear,
    })

    function customFilter(val,id=""){
      console.log(id + "wooo")
      var arrFilterMonth = "month[]=", arrMonth = [], selectPic = 'pic[]=', selectStatus = 'status[]=', selectTask = 'task[]=', selectYear = 'year=', selectSchedule = 'schedule='

      
      console.log("aku disini")
      arrFilterMonth = []
      arrMonth = []
      cummulativeLineChart.destroy()
      $(".cbMonth").each(function(idx,values){
        if ($(values).is(":checked") == true) {
          if(arrFilterMonth == 'month[]=') {
            arrFilterMonth = arrFilterMonth + values.value
          }else{
            arrFilterMonth = arrFilterMonth + '&month[]=' + values.value
          }

          arrMonth.push(values.value)
        }
      })
      
      $.each($('#selectPic').val(),function(key,val){
        if(selectPic == 'pic[]=') {
          selectPic = selectPic + val
        }else{
          selectPic = selectPic + '&pic[]=' + val
        }
      })

      $.each($('#selectStatus').val(),function(key,val){
        if(selectStatus == 'status[]=') {
          selectStatus = selectStatus + val
        }else{
          selectStatus = selectStatus + '&status[]=' + val
        }
      })

      $.each($('#selectTask').val(),function(key,val){
        if(selectTask == 'task[]=') {
          selectTask = selectTask + val
        }else{
          selectTask = selectTask + '&task[]=' + val
        }
      })

      if(selectYear == 'year=') {
        selectYear = selectYear + $('#selectYear').val()
      }else{
        selectYear = selectYear + '&year=' + $('#selectYear').val()
      }

      if(selectSchedule == 'schedule=') {
        selectSchedule = selectSchedule + $('#selectSchedule').val()
      }else{
        selectSchedule = selectSchedule + '&schedule=' + $('#selectSchedule').val()
      }

      if ($(".cbMonth").is(":checked") == false) {
        arrMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        $.each(arrMonth,function(idx,valueMonth){
          if(arrFilterMonth == 'month[]=') {
            arrFilterMonth = arrFilterMonth + valueMonth
          }else{
            arrFilterMonth = arrFilterMonth + '&month[]=' + valueMonth
          }
        })
        console.log(arrMonth)
        cummulativeChart(arrMonth)
      }else{
        cummulativeChart(arrMonth)
      }

      var arrFilter = '?' + arrFilterMonth + '&' +selectPic + '&' + selectStatus + '&' + selectTask + '&' + selectYear + '&' + selectSchedule
      showDataFilter(arrFilter)
    }

    var colors = [
      "#FF0000", "#00FF00", "#0000FF", "#FF00FF", "#00FFFF", // Add more colors as needed
      "#FFA500", "#800080", "#008000", "#800000", "#000080", "#008080",
      "#A52A2A", "#FFC0CB", "#000000", "#FFFFFF", "#FFD700", "#808080",
      "#FF4500", "#008B8B", "#556B2F", "#483D8B", "#D2691E", "#DC143C",
      "#4169E1", "#ADFF2F", "#B8860B", "#8B008B", "#9932CC", "#FF1493",
      "#00BFFF", "#FF69B4", "#4B0082", "#7FFF00", "#7CFC00", "#FF6347",
      "#40E0D0", "#9370DB", "#6B8E23", "#BA55D3", "#FF8C00", "#20B2AA",
      "#7B68EE", "#FF00FF", "#228B22", "#F08080", "#87CEEB", "#FFFFE0",
      "#2E8B57", "#FFD700", "#FF4500", "#BDB76B", "#9932CC", "#8B4513",
      "#48D1CC", "#9370DB", "#FF1493", "#00CED1", "#C71585", "#FF8C00",
      "#2F4F4F", "#8A2BE2", "#FFA07A", "#F0E68C", "#DB7093", "#FFB6C1",
      "#66CDAA", "#FF69B4", "#F4A460", "#3CB371", "#BA55D3", "#FA8072",
      "#87CEFA", "#9400D3", "#ADFF2F", "#FF00FF", "#808000", "#800080"
    ];
    //chart
    const ctx  = document.getElementById('cummulativeMandaysChart');
    const ctx2 = document.getElementById('remainingChart');
    const ctx3 = document.getElementById('levelChart');
    const ctx4 = document.getElementById('statusChart');
    const ctx5 = document.getElementById('scheduleChart');

    let cummulativeLineChart = ''

    $(document).ready(function(){
      $("#title_summary_year").text(moment().year())
      duplicateCanvasRemaining()
      cummulativeChart(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
    })

    function cummulativeChart(labelChartLineByFilter){
      $.ajax({
        type:"GET",
        url:"{{url('timesheet/getCummulativeMandaysChart')}}",
        success:function(results) {
          var cummulativeArr = []
          $.each(results,function(idx,value){
            if (value.name == null) {
              cummulativeArr = cummulativeArr
              console.log("ini null")
            }else{
              console.log("ini ga null")
              if (results.length > 1) {
                $.each(value.month_array,function(idxs,values){
                    if (idxs == value.name) {
                      var bgColorArr_idx = [colors[idx]]
                      cummulativeArr.push({"label":value.name,"data":value.month_array[idxs],"backgroundColor":bgColorArr_idx,"borderWidth":1,"tension":0.5})
                    }
                })
              }else{
                // console.log(value)
                for(i=0;i<12;i++){
                  var bgColorArr = []
                  bgColorArr.push(colors[idx])
                }
                cummulativeArr.push({"label":value.name,"data":value.month_array,"backgroundColor":bgColorArr,"borderWidth":1,"tension":0.5})
              }
            }
          })

          const myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels:labelChartLineByFilter,
              datasets:cummulativeArr
            },
            options: {
                scales: {
                    y: {
                        beginAtZero:true,
                        suggestedMax:results[results.length - 1].workdays,
                        ticks: {
                          stepSize: 2,
                          callback: function(value, index, values) {
                              return value;
                          }
                        }
                    }
                }
            }
          });

          return cummulativeLineChart = myChart
        }
      })
    }    

    function remainingChart(idCtx,value){
      // console.log(idCtx)
      // console.log(value)
      var datasetRemaining = [], arrConfig = [], labels = []
      if (typeof(value) == "object") {
        var i = 0, j = 0
        $.each(value,function(index,values){
          $.each(values[0],function(idx,valueName){
            labels.push(valueName)
          })
        })

        $.each(value.label,function(index,valueLabel){
            var borderColorArr = [colors[i++]]
            var backgroundColorArr = [colors[j++]]
            if (index == 'Prosentase') {
              arrConfig.push({"label":index,"data":valueLabel,"borderColor":borderColorArr,"backgroundColor":backgroundColorArr,borderWidth:1,minBarLength:2,barThickness:30})
            }else if(index == 'Remaining'){
              arrConfig.push({"label":index,"data":valueLabel,"borderColor":borderColorArr,"backgroundColor":backgroundColorArr,borderWidth:1,minBarLength:2,barThickness:30})
            }
        })
        datasetRemaining.push({"datasets":arrConfig})
      }else{
        arrConfig.push({"label":"","data":[0],"borderColor":'',"backgroundColor":'',borderWidth:1,minBarLength:2,barThickness:30})

        datasetRemaining.push({"datasets":arrConfig})
        labels = labels
      }

      // console.log(datasetRemaining)
      $("#span-remaining").text(moment().format('MMMM'))
      const myChart2 = new Chart(idCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets:datasetRemaining[0].datasets
        },
        options: {
          scales: {
              x: {
                  stacked: true,
              },
              y: {
                  stacked: true,
                  beginAtZero: true

              }
          },
          plugins: {
            tooltip: {
                callbacks: {
                  label: function(context) {
                    var label = context.label || '';

                    if (label) {
                      label += ': ';
                    }

                    label += context.formattedValue + "%";

                    return label;
                  }
              }
            }
          }
        }
      });

      return myChart2;
      // Create a new canvas element
      // var duplicateCanvas = document.createElement('canvas');
      // $("#remainingChart").after(duplicateCanvas)
    }

    function duplicateCanvasRemaining(){
      $.ajax({
        type:"GET",
        url:"{{url('/timesheet/getRemainingChart')}}",
        success:function(results){
          // console.log(results)
          $.each(results,function(index,value){
            // console.log(typeof(value))
            if (typeof(value) == "object") {
              $.each(value,function(idx,values){
                var duplicateCanvas = document.createElement('canvas');
                duplicateCanvas.width = 400;
                duplicateCanvas.height = 200;
                duplicateCanvas.id = 'remainingChart'+moment().month(idx-1).format('MMMM')

                $("#box-remaining").append(duplicateCanvas)
                // document.getElementById('remainingChart'+value).style.display = 'none';

                // console.log(values)
                const ctxvalue = document.getElementById('remainingChart'+moment().month(idx-1).format('MMMM'));
                remainingChart(ctxvalue,values)
              })
            }else{
              var duplicateCanvas = document.createElement('canvas');
              duplicateCanvas.width = 400;
              duplicateCanvas.height = 200;
              duplicateCanvas.id = 'remainingChart'+moment().month(value-1).format('MMMM')

              $("#box-remaining").append(duplicateCanvas)

              const ctxvalue = document.getElementById('remainingChart'+moment().month(value-1).format('MMMM'));
              remainingChart(ctxvalue,value)
            }
          })

          var itemsPerPage = 1; // Number of items per page
          var $myDiv = $("#box-remaining");
          var $pagination = $('#pagination');
          var items = $myDiv.children(); // Get the items within the div
          var totalPages = Math.ceil(items.length / itemsPerPage);

          console.log(totalPages+"total pages")
          // Generate pagination links
          for (var i = 1; i <= totalPages; i++) {
            if (i === moment().month() + 1) {
              $pagination.append('<a href="#" class="pagination-link active">' + i + '</a>');
            }else{
              //bedakan dengan filter year
              if (i > moment().month() + 1) {
                $pagination.append('<a href="#" class="pagination-link" style="color:#ccc!important;pointer-events: none!important;cursor: not-allowed;">' + i + '</a>');
              }else{
                $pagination.append('<a href="#" class="pagination-link">' + i + '</a>');
              }
            }
          }

          //set current month
          showItems(moment().month() + 1);

          // Handle pagination link click event
          $pagination.on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = parseInt($(this).text()); // Get the clicked page number
            const monthName = moment().month(page-1).format('MMMM')
            $("#span-remaining").text(monthName)
            $.each($(".pagination-link"),function(idx,value){
              if (value.text == page) {
                $(value).addClass('active')
              }else{
                $(value).removeClass('active')
              }
            })

            showItems(page); // Display items for the clicked page
          });

          function showItems(page) {
            var startIndex = (page - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            
            items.hide() // Hide all items
                 .slice(startIndex, endIndex) // Show only items for the current page
                 .show();
          }
        }
      })
      // $.each(arrMonth,function(index,value){
      //   var duplicateCanvas = document.createElement('canvas');
      //   duplicateCanvas.width = 400;
      //   duplicateCanvas.height = 200;
      //   duplicateCanvas.id = 'remainingChart'+value

      //   $("#box-remaining").append(duplicateCanvas)
      //   // document.getElementById('remainingChart'+value).style.display = 'none';

      //   const ctxvalue = document.getElementById('remainingChart'+value);
      //   remainingChart(ctxvalue)
      // })
      // Initially show the first page
    }
    
    appendChartLevel = ""
      appendChartLevel = appendChartLevel + '<table class="table table-striped" style="border-collapse: separate;border-spacing: 0 10px;">'
        appendChartLevel = appendChartLevel + '<tr>'
          appendChartLevel = appendChartLevel + '<td style="width:50px;background-color:#f35512;color:white">A</td>'
          appendChartLevel = appendChartLevel + '<td>Pekerjaan/aktivitas yang bersifat kritikal,rumit, atau pertama kali dilakukan</td>'
        appendChartLevel = appendChartLevel + '</tr>'
        appendChartLevel = appendChartLevel + '<tr>'
          appendChartLevel = appendChartLevel + '<td style="width:50px;background-color:#f39112;color:white">B</td>'
          appendChartLevel = appendChartLevel + '<td>Pekerjaan/aktivitas dengan level sulit, namun sudah pernah dilakukan sebelumnya</td>'
        appendChartLevel = appendChartLevel + '</tr>'
        appendChartLevel = appendChartLevel + '<tr>'
          appendChartLevel = appendChartLevel + '<td style="width:50px;background-color:#f3dc12;color:white">C</td>'
          appendChartLevel = appendChartLevel + '<td>Pekerjaan/aktivitas yang sudah sering dilakukan</td>'
        appendChartLevel = appendChartLevel + '</tr>'
        appendChartLevel = appendChartLevel + '<tr>'
          appendChartLevel = appendChartLevel + '<td style="width:50px;background-color:#00c0ef;color:white">D</td>'
          appendChartLevel = appendChartLevel + '<td>Pekerjaan/aktivitas yang setiap hari dilakukan</td>'
        appendChartLevel = appendChartLevel + '</tr>'
        appendChartLevel = appendChartLevel + '<tr>'
          appendChartLevel = appendChartLevel + '<td style="width:50px;background-color:#00a65a;color:white">E</td>'
          appendChartLevel = appendChartLevel + '<td>Pekerjaan/aktivitas yang membutuhkan usaha sangat sedikit / effortless</td>'
        appendChartLevel = appendChartLevel + '</tr>'
      appendChartLevel = appendChartLevel + '</table>'
    $("#definitionLevel").append(appendChartLevel)

    appendChartStatus = ""
      appendChartStatus = appendChartStatus + '<table class="table table-striped" style="border-collapse: separate;border-spacing: 0 10px;">'
        appendChartStatus = appendChartStatus + '<tr>'
          appendChartStatus = appendChartStatus + '<td style="width:50px;background-color:#00a65a;color:white">Done</td>'
          appendChartStatus = appendChartStatus + '<td>Pekerjaan/aktivitas yang sudah diselesaikan/tuntas</td>'
        appendChartStatus = appendChartStatus + '</tr>'
        appendChartStatus = appendChartStatus + '<tr>'
          appendChartStatus = appendChartStatus + '<td style="width:50px;background-color:#f39c12;color:white">Not-Done</td>'
          appendChartStatus = appendChartStatus + '<td>Pekerjaan/aktivitas yang belum diselesaikan/tuntas</td>'
        appendChartStatus = appendChartStatus + '</tr>'
        appendChartStatus = appendChartStatus + '<tr>'
          appendChartStatus = appendChartStatus + '<td style="width:50px;background-color:#f56954;color:white">Cancel</td>'
          appendChartStatus = appendChartStatus + '<td>Pekerjaan/aktivitas yang sudah dibatalkan</td>'
        appendChartStatus = appendChartStatus + '</tr>'
        appendChartStatus = appendChartStatus + '<tr>'
          appendChartStatus = appendChartStatus + '<td style="width:50px;background-color:#00c0ef;color:white">Reschedule</td>'
          appendChartStatus = appendChartStatus + '<td>Pekerjaan/aktivitas yang ditunda</td>'
        appendChartStatus = appendChartStatus + '</tr>'
      appendChartStatus = appendChartStatus + '</table>'
    $("#definitionStatus").append(appendChartStatus)

    definitionSchedule = ""
      definitionSchedule = definitionSchedule + '<table class="table table-striped" style="border-collapse: separate;border-spacing: 0 10px;">'
        definitionSchedule = definitionSchedule + '<tr>'
          definitionSchedule = definitionSchedule + '<td style="width:50px;background-color:#3c8dbc;color:white">Planned</td>'
          definitionSchedule = definitionSchedule + '<td>Pekerjaan/aktivitas yang dijadwalkan</td>'
        definitionSchedule = definitionSchedule + '</tr>'
        definitionSchedule = definitionSchedule + '<tr>'
          definitionSchedule = definitionSchedule + '<td style="width:50px;background-color:#00c0ef;color:white">Unplanned</td>'
          definitionSchedule = definitionSchedule + '<td>Pekerjaan/aktivtas yang tidak terjadwal</td>'
        definitionSchedule = definitionSchedule + '</tr>'
      definitionSchedule = definitionSchedule + '</table>'
    $("#definitionSchedule").append(definitionSchedule)

    $.ajax({
      type:"GET",
      url:"/timesheet/getLevelChart",
      success:function(result){
        const myChart3 = new Chart(ctx3, {
            type: 'doughnut',
            data: {
              labels: [
                'A',
                'B',
                'C',
                'D',
                'E'
              ],
              datasets: [{
                label: 'My First Dataset',
                data: result,
                backgroundColor: [
                  '#f35512',
                  '#f39112',
                  '#f3dc12',
                  '#00c0ef',
                  '#00a65a'
                ],
                hoverOffset: 4
              }]
            },
            options: {
              scales: {
                    y: {
                        beginAtZero: true
                    }
                },
              plugins: {
                tooltip: {
                    callbacks: {
                      label: function(context) {
                        var label = context.label || '';

                        if (label) {
                          label += ': ';
                        }

                        label += context.formattedValue + "%";

                        return label;
                      }
                  }
                }
              }
            }
        });
      }
    })

    $.ajax({
      type:"GET",
      url:"{{url('timesheet/getStatusChart')}}",
      success:function(result) {
        const myChart4 = new Chart(ctx4, {
          type: 'pie',
          data: {
            labels: [
              'Done',
              'Not-Done',
              'Cancel',
              'Reschedule',
            ],
            datasets: [{
              label: 'My First Dataset',
              data: result,
              backgroundColor: [
                '#00a65a',
                '#f39c12',
                '#f56954',
                '#00c0ef',
              ],
              hoverOffset: 4
            }]
          },
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              },
          plugins: {
              tooltip: {
                  callbacks: {
                    label: function(context) {
                      var label = context.label || '';

                      if (label) {
                        label += ': ';
                      }

                      label += context.formattedValue + "%";

                      return label;
                    }
                }
              }
            }
          }
      });
      }
    })
    
    $.ajax({
      type:"GET",
      url:"{{url('timesheet/getScheduleChart')}}",
      success:function(result){
        const myChart5 = new Chart(ctx5, {
            type: 'pie',
            data: {
              labels: [
                'Planned',
                'Unplanned',
              ],
              datasets: [{
                label: 'My First Dataset',
                data: result,
                backgroundColor: [
                  '#3c8dbc',
                  '#00c0ef',
                ],
                hoverOffset: 4
              }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
            plugins: {
              tooltip: {
                  callbacks: {
                    label: function(context) {
                      var label = context.label || '';

                      if (label) {
                        label += ': ';
                      }

                      label += context.formattedValue + "%";

                      return label;
                    }
                }
              }
            }
          },
        })
      } 
    })

    function showDataFilter(arrFilter){
      if (isTbSummary == true) {
        $("#loadingIndicator").show()
        Pace.restart();
        Pace.track(function(){
          $("#tbSummaryMandays").DataTable().ajax.url("{{url('timesheet/getFilterSumPointMandays')}}"+arrFilter).load();
        })
      }

      // $.ajax({
      //   type:"GET",
      //   url:"{{url('timesheet/getFilterSumPointMandays')}}"+arrFilter,
      //   success:function(result) {
          
      //   }
      // })
    }

    $('#tbSummaryMandays').on('xhr.dt', function (e, settings, json, xhr) {
      // AJAX reload is complete
      $("#loadingIndicator").hide()
    });

    function resetFilter(){
      location.reload()    
    }
</script>
@endsection