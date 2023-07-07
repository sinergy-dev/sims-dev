@extends('template.main')
@section('tittle')
  Dashboard Timesheet
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
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
              <select type="select" class="select2 form-control" id="selectPic" name="selectPic" onchange="customFilter(this)"><option></option></select>
            </div>
            <div class="form-group">
              <label>Filter by Status</label>
              <select type="select" class="select2 form-control" id="selectStatus" name="selectStatus" onchange="customFilter(this)">
                <option></option>
              </select>
            </div>
            <div class="form-group">
              <label>Filter by Task</label>
              <select type="select" class="select2 form-control" id="selectTask" name="selectTask" onchange="customFilter(this)"><option value=""></option></select>
            </div>
            <div class="form-group">
              <label>Filter by Year</label>
              <select type="select" class="select2 form-control" id="selectYear" name="selectYear" onchange="customFilter(this)"><option value=""></option></select>
            </div>
            <div class="form-group">
              <label>Filter by Schedule</label>
              <select type="select" class="select2 form-control" id="selectSchedule" name="selectSchedule" onchange="customFilter(this)"><option></option></select>
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
                  <h3 class="box-title" style="color: white;">Summary of Mandays</h3>
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

              <div class="row" id="box_definition">
              </div>
            </div>
            <div class="tab-pane" id="chart">
              <div class="box box-primary">
                <div class="box-header bg-primary" style="color:white">
                  <h3 class="box-title">Cummulative Mandays</h3>
                </div>
                <div class="box-body">
                  <canvas id="cummulativeMandaysChart" width="400" height="200"></canvas>
                </div>
              </div>

              <div class="box box-primary">
                <div class="box-header bg-primary" style="color:white">
                  <h3 class="box-title">Remaining</h3>
                </div>
                <div class="box-body">
                  <canvas id="remainingChart" width="400" height="200"></canvas>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!--datatable-->
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
        console.log("gak aku sek lee")

      })

      if (accesable.includes('box_pid')) {
        var tbSummarySbe = $("#tbSummarySbe").DataTable({
          data:[
            [
              "109/BBTN/SIP/X/2022",
              "Salma",
              "256",
              "145",
              "120",
              "130",
              "130%",
              "111"
            ],
            [
              "109/BBTN/SIP/X/2022",
              "Rony",
              "256",
              "145",
              "120",
              "130",
              "130%",
              "111"
            ],
            [
              "108/BMRI/SIP/IX/2022",
              "Paul",
              "256",
              "145",
              "120",
              "130",
              "130%",
              "111"
            ],
            [
              "108/BMRI/SIP/IX/2022",
              "Nabila",
              "256",
              "145",
              "120",
              "130",
              "130%",
              "111"
            ],
          ],
          columns: [
            { title: 'PID'},
            { title: 'Name' },
            { title: 'Planned' },
            { title: 'Actual' },
            { title: 'Threshold' },
            { title: 'Billable' },
            { title: '%Billable' },
            { title: 'Deviation' },
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
                  .column(0, { page: 'current' })
                  .data()
                  .each(function (group, i) {
                      if (last !== group) {
                          $(rows)
                              .eq(i)
                              .before('<tr class="group"><td colspan="6"><b>' + group + '</b></td><td><b>Total Mandays : 254</b></td></tr>');
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
        
        console.log("aku sek lee")
        $('#tbSummarySbe').removeClass('datatable-container-hidden');
        $("#tbAssignPID").removeClass('datatable-container-hidden');
      }else{
        $('#tbSummarySbe').addClass('datatable-container-hidden');
        $("#tbAssignPID").addClass('datatable-container-hidden');
      }

      
      console.log(accesable) 
      
    })

    var tbSummary = $("#tbSummaryMandays").DataTable({
      data:[
        [
          "Salma",
          "256",
          "145",
          "120",
          "130",
          "130%",
          "111"
        ],
        [
          "Rony",
          "256",
          "145",
          "120",
          "130",
          "130%",
          "111"
        ],
      ],
      columns: [
        { title: 
          'Name',
          render: function (data, type, row, meta){
            return '<a href="{{url("/timesheet?nik=1210889030")}}" style="cursor:pointer">'+ data +'</a>'
          } 
        },
        { title: 'Planned' },
        { title: 'Actual' },
        { title: 'Threshold' },
        { title: 'Billable' },
        { title: '%Billable' },
        { title: 'Deviation' },
      ],
      lengthChange: false,
      initComplete: function () {
        $.each($("#selectShowColumnTicket li input"),function(index,item){
          var column = $("#tablePerformance").DataTable().column(index)
          // column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
          $(item).prop('checked', column.visible())
        })
      }
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
      ]
    })

    $("#selectSchedule").select2({
      placeholder:"Select Schedule",
    })

    $("#selectTask").select2({
      placeholder:"Select Status",
    })

    $("#selectSchedule").select2({
      placeholder:"Select Schedule",
    })

    $("#selectPic").select2({
      placeholder:"Select PIC",
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
      data:arrFilterYear
    })

    function customFilter(val){
      var arrFilterMonth = [], selectPic = '', selectStatus = '', selectTask = '', selectYear = '', selectSchedule = ''

      if (val.id = "cbMonth") {
        arrFilterMonth = []
        cummulativeLineChart.destroy()
        $(".cbMonth").each(function(idx,values){
          if ($(values).is(":checked") == true) {
            arrFilterMonth.push(values.value)
          }
        })
      }

      var arrFilter = '?month=' + arrFilterMonth + '&pic=' + selectPic + '&status=' + selectStatus + '&task=' + selectTask + '&year=' + selectYear + '&schedule=' + selectSchedule

      cummulativeChart(arrFilterMonth)

      console.log(arrFilterMonth)
      // showDataFilter(arrFilter)
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
      cummulativeChart(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
    })

    function cummulativeChart(labelChartLineByFilter){
      const myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels:labelChartLineByFilter,
          datasets: [
            {
                label: 'Salma',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000'
                ],
                borderColor: [
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000',
                    '#FF0000'
                ],
                borderWidth: 1,
                tension:0.5,
            },
            {
                label: 'Rony',
                data: [10, 9, 5, 12, 3, 1],
                backgroundColor: [
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                ],
                borderColor: [
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                    colors[2],
                ],
                borderWidth: 1,
                tension:0.4,
            },
            {
                label: 'Paul',
                data: [11, 19, 25, 1, 7, 4],
                backgroundColor: [
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                ],
                borderColor: [
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                    colors[3],
                ],
                borderWidth: 1,
                tension:0.4,
            },
            {
                label: 'Nabila',
                data: [33, 34, 14, 12, 13, 21],
                backgroundColor: [
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                ],
                borderColor: [
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                    colors[4],
                ],
                borderWidth: 1,
                tension:0.4,
            }
          ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
      });

      return cummulativeLineChart = myChart
    }    

    const myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Salma', 'Rony'],
            datasets: [{
                  label: 'Prosentase',
                  data: ['80','70'],
                  backgroundColor: [
                      colors[0],
                      colors[0],
                  ],
                  borderColor: [
                      colors[0],
                      colors[0],                                                 
                  ],
                  borderWidth: 1,
                  minBarLength: 2,
                  barThickness: 30,
                  },
                  {
                      label: 'Remaining',
                      data: ['20','30'],
                      backgroundColor: [
                          colors[1],
                          colors[1],                      
                      ],
                      borderColor: [
                          colors[1],
                          colors[1],
                      ],
                      borderWidth: 1,
                      minBarLength: 2,
                      barThickness: 30,
                  }],
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

    function resetFilter(){
      location.reload()    
    }
</script>
@endsection