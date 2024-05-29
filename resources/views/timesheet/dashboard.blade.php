@extends('template.main')
@section('tittle')
  Dashboard Timesheet
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
  <link rel="preload" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-barber-shop.min.css" integrity="sha512-7qRUmettmzmL6BrHrw89ro5Ki8CZZQSC/eBJTlD3YPHVthueedR4hqJyYqe1FJIA4OhU2mTes0yBtiRMCIMkzw==" crossorigin="anonymous" referrerpolicy="no-referrer"  as="style" onload="this.onload=null;this.rel='stylesheet'"/>
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
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

    .containerBoxRemaining{
      width:100%;
      max-width: 100%;
      overflow-x: scroll;
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
            <div class="form-group" id="filter-month-timesheet" style="display:none;">
              <label>Filter by Month</label>
              <div id="monthFilter">
              </div>
            </div>

            <!-- <div class="form-group" id="form_filter_pic" style="display:none">
              <label>Filter by PIC</label>
              <select type="select" class="select2 form-control" id="selectPic" name="selectPic" onchange="customFilter(this.value,'selectPic')">
              </select>
            </div>

            <div class="form-group">
              <label>Filter by Status</label>
              <select type="select" class="select2 form-control" id="selectStatus" name="selectStatus" onchange="customFilter(this.value,'selectStatus')">
              </select>
            </div>

            <div class="form-group">
              <label>Filter by Task</label>
              <select type="select" class="select2 form-control" id="selectTask" name="selectTask" onchange="customFilter(this.value,'selectTask')">
              </select>
            </div>

            <div class="form-group">
              <label>Filter by Year</label>
              <select type="select" class="select2 form-control" id="selectYear" name="selectYear" onchange="customFilter(this.value,'selectYear')"></select>
            </div>

            <div class="form-group">
              <label>Filter by Schedule</label>
              <select type="select" class="select2 form-control" id="selectSchedule" name="selectSchedule" onchange="customFilter(this.value,'selectSchedule')">
              </select>
            </div> -->

            <div class="form-group" id="filter-division-timesheet" style="display:none">
              <label>Filter by Division</label>
              <select type="select" class="select2 form-control" id="selectDiv" name="selectDiv">
              <option></option>
              </select>
            </div>

            <div class="form-group" id="form_filter_pic" style="display:none">
              <label>Filter by PIC</label>
              <select type="select" class="select2 form-control" id="selectPic" name="selectPic">
              </select>
            </div>

            <div class="form-group" id="filter-status-timesheet" style="display:none">
              <label>Filter by Status</label>
              <select type="select" class="select2 form-control" id="selectStatus" name="selectStatus">
              </select>
            </div>

            <div class="form-group" id="filter-task-timesheet" style="display:none">
              <label>Filter by Task</label>
              <select type="select" class="select2 form-control" id="selectTask" name="selectTask">
              </select>
            </div>

            <div class="form-group" id="filter-phase-timesheet" style="display:none">
              <label>Filter by Phase</label>
              <select type="select" class="select2 form-control" id="selectPhase" name="selectPhase">
              </select>
            </div>

            <div class="form-group">
              <label>Filter by Year</label>
              <select type="select" class="select2 form-control" id="selectYear" name="selectYear"><option></option></select>
            </div>

            <div class="form-group" id="filter-schedule-timesheet" style="display:none">
              <label>Filter by Schedule</label>
              <select type="select" class="select2 form-control" id="selectSchedule" name="selectSchedule">
              </select>
            </div>

            <button id="" class="btn btn-sm btn-primary btn-block" onclick="customFilter()"><i class="fa fa-filter"></i> Filter</button>
            <button id="" class="btn btn-sm btn-info btn-block" onclick="resetFilter()"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
      <div class="col-md-10 col-xs-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active" id="nav-tab-table"><a href="#table" data-toggle="tab">Table</a></li>
            <li><a href="#chart" data-toggle="tab">Chart</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="table">
              <div class="box box-primary" id="box_mandays" style="display:none">
                <div class="box-header bg-primary with-border">
                  <h3 class="box-title" style="color: white;">Summary of Mandays <span id="title_summary_year"></span></h3>
                  <span id="filterSumPoint" class="pull-right"><i class="fa fa-circle" style="color: red;"></i> <span style="color: white">not ready to filter..</span></span>
                </div>
                <div class="box-body">
                  <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6 col-xs-12">
                      <div class="input-group">
                        <input id="searchBarMandays" type="text" class="form-control" placeholder="ex: Search Name..." onkeyup="searchCustom('tbSummaryMandays','searchBarMandays')">
                        <div class="input-group-btn">
                          <button type="button" id="btnShowEntry" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Show 10 
                            <span class="fa fa-caret-down"></span>
                          </button>
                          <ul class="dropdown-menu" id="selectShowEntry">
                            <li><a href="#" onclick="changeNumberEntries('tbSummaryMandays',10)">10</a></li>
                            <li><a href="#" onclick="changeNumberEntries('tbSummaryMandays',25)">25</a></li>
                            <li><a href="#" onclick="changeNumberEntries('tbSummaryMandays',50)">50</a></li>
                            <li><a href="#" onclick="changeNumberEntries('tbSummaryMandays',100)">100</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <button style="margin-left: 10px;float: right;" title="Clear Filter" id="clearFilterTable" onclick="clearFilterTable('tbSummaryMandays','searchBarMandays')" type="button" class="btn btn-default btn-flat">
                        <i class="fa fa-fw fa-remove"></i>
                      </button>
                      <button style="margin-left: 10px;float: right;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Displayed Column
                        <span class="fa fa-caret-down"></span>
                      </button>
                      <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;float: right;" id="selectShowColumnTicket">
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="1"><span class="text">Planned</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="2"><span class="text">Actual</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="3"><span class="text">Threshold</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="4"><span class="text">Billable</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="5"><span class="text">%Billable</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable('tbSummaryMandays',this)" data-column="6"><span class="text">Deviation</span></li>
                      </ul>
                      <button style="margin-left: 10px;float: right;" title="Refresh Table" id="reloadTable" onclick="reloadTable('tbSummaryMandays')" type="button" class="btn btn-default btn-flat">
                        <i class="fa fa-fw fa-refresh"></i>
                      </button>
                      <a style="margin-left: 10px;display: none;float: right;" id="btn_export_sum_mandays" target="_blank" onclick="customFilter('{{action('TimesheetController@exportExcel')}}','export')" class="btn btn-md btn-success"><i class="fa fa-file-excel-o"></i> Export</a>
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

              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="box box-primary" id="box_sbe" style="display:none;">
                    <div class="box-header bg-primary with-border">
                      <h3 class="box-title" style="color: white;">Summary of SBE</h3>
                    </div>
                    <div class="box-body">
                      <div class="row">
                          <div class="col-md-12 col-xs-12 pull-right">
                            <b>Search Anything</b>
                              <div class="input-group pull-right">
                                <input id="searchBarSbe" type="text" class="form-control" onkeyup="searchCustom('tbSummarySbe','searchBarSbe')" placeholder="ex: Search Name..">
                                <div class="input-group-btn">
                                  <button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Show 10 
                                    <span class="fa fa-caret-down"></span>
                                  </button>
                                  <ul class="dropdown-menu" id="selectShowEntryTicket">
                                    <li><a href="#" onclick="changeNumberEntries('tbSummarySbe',10)">10</a></li>
                                    <li><a href="#" onclick="changeNumberEntries('tbSummarySbe',25)">25</a></li>
                                    <li><a href="#" onclick="changeNumberEntries('tbSummarySbe',50)">50</a></li>
                                    <li><a href="#" onclick="changeNumberEntries('tbSummarySbe',100)">100</a></li>
                                  </ul>
                                </div>
                                <span class="input-group-btn">
                                  <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" onclick="clearFilterTable('tbSummarySbe','searchBarSbe')" type="button" class="btn btn-default btn-flat">
                                    <i class="fa fa-fw fa-remove"></i>
                                  </button>
                                </span>
                                <span class="input-group-btn">
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
                </div>
                <div class="col-md-6 col-xs-12">
                  <div class="box box-primary" id="box_pid" style="display:none">
                    <div class="box-header bg-primary with-border">
                      <h3 class="box-title" style="color: white;">Assign PID</h3>
                    </div>
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12 col-xs-12 pull-right">
                            <b>Search Anything</b>
                            <div class="input-group pull-right">
                              <input id="searchBarAssignPID" onkeyup="searchCustom('tbAssignPID','searchBarAssignPID')" type="text" class="form-control" placeholder="ex: search Name...">
                              <div class="input-group-btn">
                                <button type="button" id="btnShowEntry" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                  Show 10 
                                  <span class="fa fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu" id="selectShowEntry">
                                  <li><a href="#" onclick="changeNumberEntries('tbAssignPID',10)">10</a></li>
                                  <li><a href="#" onclick="changeNumberEntries('tbAssignPID',25)">25</a></li>
                                  <li><a href="#" onclick="changeNumberEntries('tbAssignPID',50)">50</a></li>
                                  <li><a href="#" onclick="changeNumberEntries('tbAssignPID',100)">100</a></li>
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
                      <div class="table-responsive">
                        <table class="table" id="tbAssignPID">
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" id="box_definition" style="display:none;">
              </div>
            </div>

            <div class="tab-pane" id="chart">
              <div class="alert alert-info alert-dismissible" id="alert-for-direktor" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Alert!</h4>
                Please filter <b>division</b> first to show the data!.
              </div>
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
                  <div class="containerBoxRemaining">
                    <div id="box-remaining">
                    </div>
                  </div>

                  <div id="pagination" style="margin-top:20px" class="pull-right">
                    
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 col-xs-12" style="display: none;" id="box-level-timesheet">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Level <span id="textLevel"></span></h3>
                    </div>
                    <div class="box-body">
                      <canvas id="levelChart" width="400" height="200"></canvas>
                      <div id="definitionLevel"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12" style="display: none;" id="box-status-timesheet">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Status <span id="textStatus"></span></h3>
                    </div>
                    <div class="box-body">
                      <canvas id="statusChart" width="400" height="200"></canvas>
                      <div id="definitionStatus"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12" style="display: none;" id="box-schedule-timesheet">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Schedule <span id="textSchedule"></span></h3>
                    </div>
                    <div class="box-body">
                      <canvas id="scheduleChart" width="400" height="200"></canvas>
                      <div id="definitionSchedule"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 col-xs-12" style="display: none;" id="box-task-timesheet">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Task <span id="textTask"></span></h3>
                    </div>
                    <div class="box-body">
                      <canvas id="taskChart" width="400" height="200"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12" style="display: none;" id="box-phase-timesheet">
                  <div class="box box-primary">
                    <div class="box-header bg-primary" style="color:white">
                      <h3 class="box-title">Phase <span id="textPhase"></span></h3>
                    </div>
                    <div class="box-body">
                      <canvas id="phaseChart" width="400" height="200"></canvas>
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

    <div class="modal fade" id="modalDetailActual" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detail Actual Activity</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div class="table-responsive">
                    <table id="tbDetailActual" class="table table-bordered display no-wrap" width="100%" cellspacing="0">
                      
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>          
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modalManagePID" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input class="form-control" id="manageNamePID" disabled>
              </div>
              <div class="form-group">
                <label>Role</label>
                <select class="form-control select2" id="manageRolePID">
                </select>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="deleteManageAssign">Delete Assign</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveManageAssign">Save</button>
              </div>          
            </div>
          </div>
        </div>
    </div>
@endsection
@section('scriptImport')
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js" integrity="sha512-2cbsQGdowNDPcKuoBd2bCcsJky87Mv0LEtD/nunJUgk6MOYTgVMGihS/xCEghNf04DPhNiJ4DZw5BxDd1uyOdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })
    
    if ($("#tbSummaryMandays").is(":visible") == false) {
      // $(".nav-tabs").find("li").last().addClass("active")
      // $(".tab-pane").last().addClass("active")
      // $(".tab-pane").first().removeClass("active")
      $("#selectYear").val(moment().year()).trigger('change')
    }else{
      var datatableSummary = ""
      function initializeDataTable(tabId) {
        datatableSummary = $(tabId).find('#tbSummaryMandays').DataTable({
          responsive: true,
          "ajax":{
            type:"GET",
            url:"{{url('/timesheet/sumPointMandays')}}",
          },
          columns: [
            { title: 
              'Name',
              render: function (data, type, row, meta){
                return '<a href="{{url("/timesheet/timesheet?nik=")}}'+ row.nik +'" style="cursor:pointer" target="_blank">'+ row.name +'</a>'
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
            { title: 'Activity Total', 
              data:'total_task'
            },
          ],
          lengthChange: false,
          "pageLength": 50,
          initComplete: function () {
            isTbSummary = true
            $('#loadingIndicator').hide();
            $.each($("#selectShowColumnTicket li input"),function(index,item){
              var column = $("#tablePerformance").DataTable().column(index)
              // column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
              $(item).prop('checked', column.visible())
            })
          },
        })

        return datatableSummary = datatableSummary 
      }

      // Initialize the DataTable for the active tab
      var activeTab = $('.tab-pane.active');
      var idTab = $('.tab-pane.active').attr('id');

      if (idTab == 'table') {
        initializeDataTable(activeTab);
        // datatableSummary.columns.adjust().draw()
      }
    }

    $(document).ready(function(){
      localStorage.removeItem('arrFilter');
      $("#textLevel").text(moment().format('YYYY'))
      $("#textStatus").text(moment().format('YYYY'))
      $("#textTask").text(moment().format('YYYY'))
      $("#textPhase").text(moment().format('YYYY'))
      $("#textSchedule").text(moment().format('YYYY'))
      $("#title_summary_year").text(moment().year())
      $("#span-remaining").text(moment().format('MMMM'))

      if ($("#tbSummaryMandays").is(":visible") == true) {
        duplicateCanvasRemaining("/timesheet/getRemainingChart","")
        levelChart("/timesheet/getLevelChart","")
        statusChart("/timesheet/getStatusChart","")
        scheduleChart("/timesheet/getScheduleChart","")
        taskChart("/timesheet/getTaskChart","")
        phaseChart("/timesheet/getPhaseChart","")
        cummulativeChart(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],"timesheet/getCummulativeMandaysChart","")
      }
    })
    
    if (accesable.includes('box_pid') || accesable.includes('box_sbe')) {
      if ($("#tbSummaryMandays").is(":visible")) {
        initiateSumSbe(id="{{Auth::User()->id_division}}")
        initiateAssignPid(id="{{Auth::User()->id_division}}")
      }else{
        initiateSumSbe(id='')
        initiateAssignPid(id='')
      }
    }

    function initiateSumSbe(id){
      var isPMO = false
      if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.group','Project Management')->exists()}}") {
        isPMO = true
        var colspan = 4
        var enabledClick = true
      }else{
        var colspan = 3
        var enabledClick = false
      }

      var tbSummarySbe = $("#tbSummarySbe").DataTable({
        "ajax":{
          type:"GET",
          url:"{{url('/timesheet/sumPointSbe')}}",
        },
        columns: [
          { title: 'PID', data:'pid'},
          { title: 'Name', data:'name'},
          { title: 'Planned',
            render: function (data, type, row, meta){
              return row.planned
            },
          },
          { title: 'Actual',
            render: function (data, type, row, meta){
              if (enabledClick) {
                return '<a onclick="showDetailActual('+ row.nik + ',' + "'" + row.project_id + "'" + ')" style="cursor:pointer">'+ row.actual +'</a>'
              }else{
                return row.actual
              }
            },
          },
          {
            title: 'Remaining',
            data: 'remaining'
          },
          {
            title: 'Finish Date',
            render: function (data, type, row, meta){
              if (row.estimated_end_date == null) {
                return '-'
              }else{
                return row.estimated_end_date
              }
            },
          },
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
            .column([0], { page: 'current' })
            .data()
            .each(function (group, i) {
                if (last !== group) {
                    $(rows)
                        .eq(i)
                        .before('<tr class="group"><td colspan="'+ colspan +'"><b>' + group + '</b></td><td></td></tr>');
                    last = group;
                    // <b>Total Mandays : '+ api.column(2).data()[i] +'</b>
                }
            });

            var lastValueActual = null, lastValueRemaining = null;
            $('table#tbSummarySbe tbody tr:not(.group)').each(function() {
              var dataActual = $(this).find('td:nth-child(2)').text();
              var dataRemaining = $(this).find('td:nth-child(4)').text();

              if (dataActual === lastValueActual) {
                 $(this).find('td:nth-child(2)').text('');
              } else {
                 lastValueActual    = dataActual;
              }

              if (dataRemaining === lastValueRemaining) {
                 $(this).find('td:nth-child(4)').text('');
              } else {
                 lastValueRemaining = dataRemaining;
              }
            });
        },
      }) 

      if (!isPMO) {
        tbSummarySbe.columns(4).visible(false)
      }
    }
      
    function initiateAssignPid(id){
      const dataSet = [
          ['System Architect','Tiger Nixon', 'Main' ],
          ['Accountant','Garrett Winters', 'Support' ],
          ['Junior Technical Author','Ashton Cox', 'Support'],
          ['Senior Javascript Developer','Cedric Kelly', 'Main' ],
          ['Accountant','Airi Satou', 'Main' ],
      ];

      var tbPID = $("#tbAssignPID").DataTable({
        "ajax":{
          type:"GET",
          url:"{{url('/timesheet/getAllAssignPidByDivision')}}",
        },
        // data:dataSet,
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
          { title: 'Action',
            render: function (data, type, row, meta){
              return "<button class='btn btn-sm bg-purple' value='"+ row.id +"'>Manage</button"
            },
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
                            .before('<tr class="group"><td colspan="3"><b>' + group + '</b></td></tr>');
                        last = group;
                    }
                });
        },
      })

      $('#tbAssignPID tbody').on( 'click', 'button', function () {
        var id = this.value
        $.ajax({
          type:"GET",
          url:"{{url('/timesheet/getRolePID')}}",
          data:{
            id:id
          },
          success:function(result){
            $("#modalManagePID").modal("show")
            $("#manageNamePID").val(result.name)
            $("#deleteManageAssign").val(result.id)
            $("#saveManageAssign").val(result.id)

            $("#manageRolePID").select2({
              data:[
                {
                  id:"Main",text:"Main"
                },
                {
                  id:"Support",text:"Support"
                },
              ]
            }).val(result.role).trigger("change")

            $("#modalManagePID .modal-title").text("Manage "+result.pid)
          }
        })

      });
    }

    $("#deleteManageAssign").click(function(){
      const id = this.value
      Swal.fire({
        title: 'Are you sure?',
        text: "Delete this Assign PID!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $.ajax({
            type:"POST",
            url:"{{url('/timesheet/deleteRolePID')}}",
            data:{
              _token:"{{csrf_token()}}",
              id:id
            },
            beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
              })
              Swal.showLoading()
            },
            success: function(results)
            {
              Swal.fire({
                icon: 'success',
                title: 'Successfully delete data!',
                text: 'Click Ok to reload page',
              }).then((result,data) => {
                if (result.value) {
                  Swal.close()
                  $("#modalManagePID").modal("hide")
                  $('#tbAssignPID').DataTable().ajax.url("{{url('/timesheet/getAllAssignPidByDivision')}}").load()        
                }
              })
            }
          })
        } else if (result.isDenied) {
          Swal.fire("Changes are not saved", "", "info");
        }
      });
    })

    $("#saveManageAssign").click(function(){
      const id = this.value
      Swal.fire({
        title: 'Are you sure?',
        text: "Update this Role!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          $.ajax({
            type:"POST",
            url:"{{url('/timesheet/updateRolePID')}}",
            data:{
              _token:"{{csrf_token()}}",
              id:id,
              role:$("#manageRolePID").val()
            },
            beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
              })
              Swal.showLoading()
            },
            success: function(results)
            {
              Swal.fire({
                icon: 'success',
                title: 'Successfully update data!',
                text: 'Click Ok to reload page',
              }).then((result,data) => {
                if (result.value) {
                  Swal.close()
                  $("#modalManagePID").modal("hide")
                  $('#tbAssignPID').DataTable().ajax.url("{{url('/timesheet/getAllAssignPidByDivision')}}").load()              
                }
              })
            }
          })
        } else if (result.isDenied) {
          Swal.fire("Changes are not saved", "", "info");
        }
      });
    })

    isTbSummary = false

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

    $.ajax({
      type:"GET",
      url:"{{url('timesheet/getPhaseByDivision')}}",
      success:function(result) {
        $("#selectPhase").select2({
          placeholder:"Select Phase",
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
            // appendMonth = appendMonth + '  <input type="checkbox" id="cbMonth" class="cbMonth" value="'+ values +'" onchange="customFilter(this)">'
            appendMonth = appendMonth + '  <input type="checkbox" id="cbMonth" class="cbMonth" value="'+ values +'">'
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
  var dataStatus = [
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
      text:"Not-Done"
    },
  ]

  $("#selectStatus").select2({
    data:dataStatus,
    placeholder:"Select Status",
    multiple:true
  })

  $.ajax({
    type:"GET",
    url:"{{url('timesheet/getListOperation')}}",
    success:function(result) {
      $("#selectDiv").select2({
        placeholder:"Select Division",
        data:result,
      })
    }
  })

  var dataSchedule = [
    {
      id:"Planned",
      text:"Planned"
    },
    {
      id:"Unplanned",
      text:"Unplanned"
    },
  ]

  $("#selectSchedule").select2({
    placeholder:"Select Schedule",
    data:dataSchedule,
    multiple:true
  })

  arrFilterYear = []
  var currentDate = new Date();
  var currentYear = currentDate.getFullYear();

  arrFilterYear.push({
    id:currentYear,
    text:currentYear
  })

  arrFilterYear.push({id:moment().year()-1,text:moment().year()-1})

  $("#selectYear").select2({
    placeholder:"Select Year",
    data:arrFilterYear,
  })

  function customFilter(val,id="",page){
    var arrFilterMonth = "month[]=", arrMonth = [], selectPic = 'pic[]=', selectStatus = 'status[]=', selectTask = 'task[]=', selectPhase = 'phase[]=', selectYear = 'year=', selectSchedule = 'schedule[]=', selectRoles = 'roles='

    arrFilterMonth = []
    arrMonth = []
    arrMonthNumber = []

    $(".cbMonth").each(function(idx,values){
      if ($(values).is(":checked") == true) {
        if(arrFilterMonth == 'month[]=') {
          arrFilterMonth = arrFilterMonth + values.value
        }else{
          arrFilterMonth = arrFilterMonth + '&month[]=' + values.value
        }

        arrMonth.push(values.value)
        arrMonthNumber.push(++idx)
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

    $.each($('#selectPhase').val(),function(key,val){
      if(selectTask == 'phase[]=') {
        selectTask = selectTask + val
      }else{
        selectTask = selectTask + '&phase[]=' + val
      }
    })

    if(selectYear == 'year=') {
      if ($('#selectYear').val() == '') {
        selectYear = selectYear + moment().year()
      }else{
        selectYear = selectYear + $('#selectYear').val()
      }
    }else{
      if ($('#selectYear').val() == '') {
        selectYear = selectYear + '&year=' + moment().year()
      }else{
        selectYear = selectYear + '&year=' + $('#selectYear').val()
      }
    }

    if(selectRoles == 'roles=') {
      selectRoles = selectRoles + $('#selectDiv').val()
    }else{
      selectRoles = selectRoles + '&roles=' + $('#selectDiv').val()
    }

    $.each($('#selectSchedule').val(),function(key,val){
      if(selectSchedule == 'schedule[]=') {
        selectSchedule = selectSchedule + val
      }else{
        selectSchedule = selectSchedule + '&schedule[]=' + val
      }
    })

    if ($(".cbMonth").is(":checked") == false) {
      arrMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      $.each(arrMonth,function(idx,valueMonth){
        if(arrFilterMonth == 'month[]=') {
          arrFilterMonth = arrFilterMonth + valueMonth
        }else{
          arrFilterMonth = arrFilterMonth + '&month[]=' + valueMonth
        }
      })
    }

    if(id == "changePageRemaining"){
      var arrFilter = '?month_select=' + val + '&' + selectRoles + '&' + selectYear
      showDataFilter(arrFilter,arrMonth,"remainingChart",val)
    }else{
      if (cummulativeLineChart){
        cummulativeLineChart.destroy()
      }

      var arrFilter = arrFilterMonth + '&' +selectPic + '&' + selectStatus + '&' + selectPhase + '&' + selectTask + '&' + selectYear + '&' + selectSchedule + '&' + selectRoles
      if (id == "export") {
        window.open(val + '?' + arrFilter, "_blank")
        // window.location = val + arrFilter;
      }else{
        showDataFilter(arrFilter,arrMonth,"all",selectYear)

        localStorage.setItem("arrFilter",arrFilter)
      }
    }
  }

  var colors = [
    "#008000","#FF0000","#00FFFF","#FF4500","#FFA500","#FFD700","#FFFF00","#ADFF2F","#00FF00","#32CD32","#00FF7F","#00CED1","#1E90FF","#4169E1","#0000FF","#8A2BE2","#9932CC","#8B008B","#FF00FF","#FF69B4","#FF1493","#FFB6C1","#FFC0CB","#DC143C","#A52A2A","#D2691E","#B22222","#FFDAB9","#FF8C00","#FF7F50","#FF4500","#FF6347","#FF0000","#800000","#8B4513","#A0522D","#F0E68C","#DAA520","#BDB76B","#556B2F","#006400","#2E8B57","#3CB371","#20B2AA","#00FF7F","#008080","#00CED1","#00FFFF","#40E0D0","#7FFFD4","#4682B4","#5F9EA0","#6495ED","#1E90FF","#4169E1","#0000FF","#8A2BE2","#9932CC","#800080","#9370DB"
  ];
  //chart
  const ctx  = document.getElementById('cummulativeMandaysChart');
  const ctx2 = document.getElementById('remainingChart');
  const ctx3 = document.getElementById('levelChart');
  const ctx4 = document.getElementById('statusChart');
  const ctx5 = document.getElementById('scheduleChart');
  const ctx6 = document.getElementById('taskChart');
  const ctx7 = document.getElementById('phaseChart');

  let cummulativeLineChart = '',levelPieChart = '',statusPieChart = '',schedulePieChart = '',taskPieChart = '',phasePieChart = '', remainingBarChart = []

  function cummulativeChart(labelChartLineByFilter,url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
      success:function(results) {
        cummulativeArr = []
        $.each(results,function(idx,value){
          array_result = []
          $.each(value.month_array,function(idxs,values){
            if (idxs == value.name) {
              $.each(labelChartLineByFilter,function(id,valueLabel){
                month_filter = moment().month(valueLabel).format("M")
                array_result.push(value.month_array[idxs][month_filter-1])
              })
              var bgColorArr_idx = [colors[idx]]
              cummulativeArr.push({"label":value.name,"data":array_result,"backgroundColor":bgColorArr_idx,"borderWidth":1,"tension":0.5})
            }
          })
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
            },
          }
        });

        return cummulativeLineChart = myChart
      }
    })
  }    

  function remainingChart(idCtx,value){
    var datasetRemaining = [], arrConfig = [], labels = []
    remainingBarChart = []
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
          }else if (index == 'Over') {
            arrConfig.push({"label":"Over","data":valueLabel,"borderColor":"#FFC300","backgroundColor":"#FFC300",borderWidth:1,minBarLength:2,barThickness:30})
          }
      })
      datasetRemaining.push({"datasets":arrConfig})
    }else{
      arrConfig.push({"label":"","data":[0],"borderColor":'',"backgroundColor":'',borderWidth:1,minBarLength:2,barThickness:30})

      datasetRemaining.push({"datasets":arrConfig})
      labels = labels
    }

    const myChart2 = new Chart(idCtx, {
      type: 'bar',
      data: {
          labels: labels,
          datasets:datasetRemaining[0].datasets
      },
      options: {
        maintainAspectRation: false,
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

    const containerBodyRemaining = document.querySelector('#box-remaining');
    const totalLabels = myChart2.data.labels.length;

    if (myChart2.data.labels.length > 7) {
      const newWidth = 700 + ((totalLabels - 7) * 30)
      containerBodyRemaining.style.width  = `${newWidth}px`;
    }

    return remainingBarChart.push(myChart2);
    // Create a new canvas element
    // var duplicateCanvas = document.createElement('canvas');
    // $("#remainingChart").after(duplicateCanvas)
  }

  function duplicateCanvasRemaining(url,param,position){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
      success:function(results){
        $("#box-remaining").empty("")
        $.each(results,function(index,value){
          if (typeof(value) == "object") {
            $.each(value,function(idx,values){
              var duplicateCanvas = document.createElement('canvas');
              duplicateCanvas.width = 400;
              duplicateCanvas.height = 200;
              duplicateCanvas.id = 'remainingChart'+moment().month(idx-1).format('MMMM')
              $("#box-remaining").append(duplicateCanvas)
              const ctxvalue = document.getElementById('remainingChart'+moment().month(idx-1).format('MMMM'))
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

        var $myDiv        = $("#box-remaining");
        var itemsPerPage  = 1; // Number of items per page

        var $pagination   = $('#pagination');
        var items         = $myDiv.children();
        var totalPages    = Math.ceil(items.length / itemsPerPage);

        // if (!$(".textWarningRemaining").length) {
        //   $myDiv.after("<p class='textWarningRemaining'>Filter by month</p>")
        // }

        if (position) {
          showItems(position,items,itemsPerPage);
        }else{
          //set current month
          var arrMonth = [0,0,0,0,0,0,0,0,0,0,0,0,0]
          if (totalPages == 12) {           
            for (var i = 1; i <= totalPages; i++) {
              if ($("#cbMonth:checked").length) {
                $.each($("#cbMonth:checked"),function(idx,item){
                    var monthAsMoment = moment(item.value, 'MMMM');
                    var numericMonth = monthAsMoment.month() + 1;
                    if (idx == 0) {
                      showItems(numericMonth,items,itemsPerPage);

                      var monthAsMoment = moment().month(parseInt(numericMonth)-1);
                      $("#span-remaining").text(moment(monthAsMoment).format('MMMM')) 
                    }

                    arrMonth[numericMonth] = numericMonth
                })

                if (arrMonth[i] == i) {
                  $pagination.append('<a href="#" class="pagination-link">' + i + '</a>');
                }else{
                  $pagination.append('<a href="#" class="pagination-link" style="color:#ccc!important;pointer-events: none!important;cursor: not-allowed;">' + i + '</a>');
                }

              }else{
                showItems(moment().month() + 1,items,itemsPerPage);

                if (i === moment().month() + 1) {
                  $pagination.append('<a href="#" class="pagination-link active">' + i + '</a>');
                }else{
                  //bedakan dengan filter year
                  if (i > moment().month() + 1) {
                    $pagination.append('<a href="#" class="pagination-link">' + i + '</a>');
                  }else{
                    $pagination.append('<a href="#" class="pagination-link active">' + i + '</a>');
                  }
                }
              }
            }

          }else{
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
            showItems(moment().month() + 1,items,itemsPerPage);
          }
          // Generate pagination links
          
        }
      }
    })
  }

  // Handle pagination link click event
  $("#pagination").on('click', '.pagination-link', function(e) {
    var $myDiv        = $("#box-remaining");
    var $pagination   = $('#pagination');
    var itemsPerPage  = 1; // Number of items per page

    var items         = $myDiv.children();
    var totalPages    = Math.ceil(items.length / itemsPerPage);

    e.preventDefault();
    var page = parseInt($(this).text()); // Get the clicked page number
    const monthName = moment().month(page-1).format('MMMM')
    $("#span-remaining").text(monthName)
    $.each($(".pagination-link"),function(idx,value){
      if (value.text == page) {
        customFilter(page,"changePageRemaining")
        $(value).addClass('active')
      }else{
        $(value).removeClass('active')
      }
    })
    
    //destroy reinitiate remaining chart
    showItems(page,items,itemsPerPage); 
    // Display items for the clicked page
  });

  function showItems(page,items,itemsPerPage) {
    var startIndex = (page - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;
    
    items.hide() // Hide all items
         .slice(startIndex, endIndex) // Show only items for the current page
         .show();
  }
  ///////
  
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

  function levelChart(url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
      success:function(result){
        const myChart3 = new Chart(ctx3, {
            type: 'pie',
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

        return levelPieChart = myChart3
      }
    })
  }

  function statusChart(url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
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

        return statusPieChart = myChart4
      }
    })
  }
  
  function scheduleChart(url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
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

        return schedulePieChart = myChart5
      } 
    })
  }

  function taskChart(url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
      success:function(result){
        arrColor = []
        $.each(result.label,function(itemKey,value){
          arrColor.push(colors[itemKey])
        })
        const myChart6 = new Chart(ctx6, {
            type: 'pie',
            data: {
              labels: result.label,
              datasets: [{
                label: result.label,
                data: result.data,
                backgroundColor: arrColor,
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
              },
              legend: {
                display: true,
                position:'right', 
              },
            }
          },
        })

        return taskPieChart = myChart6
      } 
    })
  }

  function phaseChart(url,param){
    $.ajax({
      type:"GET",
      url:"{{url('/')}}/"+url+param,
      success:function(result){
        arrColor = []
        $.each(result.label,function(itemKey,value){
          arrColor.push(colors[itemKey])
        })
        const myChart7 = new Chart(ctx7, {
            type: 'pie',
            data: {
              labels: result.label,
              datasets: [{
                label: result.label,
                data: result.data,
                backgroundColor: arrColor,
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
                },
                legend: {
                  display: true,
                  position:'right', 
                },
              },
            },
        })

        return phasePieChart = myChart7
      } 
    })
  }

  function showDataFilter(arrFilter,arrMonth,nameChart,val){
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

    if (nameChart == "remainingChart") {
      //remaining chart update
      $.each(remainingBarChart,function(idx,value){
          value.destroy()
      })
      duplicateCanvasRemaining("timesheet/getFilterRemainingChart",arrFilter,val) 
    }else{
      if (isTbSummary == true) {
        $("#loadingIndicator").show()
        $("#filterSumPoint").find("i").css("color","red")
        $("#filterSumPoint").find("span").text("not ready to filter...")
        Pace.restart();
        Pace.track(function(){
          $("#tbSummaryMandays").DataTable().ajax.url("{{url('timesheet/getFilterSumPointMandays')}}?"+arrFilter).load();
          if (arrMonth.length > 1) {
            $("#monthly_status").text("cumulative")
          }else{
            $("#monthly_status").text(arrMonth[0])
          }
        })
      }

      //filter table
      if ($('#selectDiv').is(":visible")) {
        $("#tbAssignPID").DataTable().ajax.url("{{url('/timesheet/getAllAssignPidByDivision')}}?"+arrFilter).load();
        $("#tbSummarySbe").DataTable().ajax.url("{{url('/timesheet/sumPointSbe')}}?"+arrFilter).load();
      }

      //cummulative mandays chart update
      if (cummulativeLineChart) {
        cummulativeLineChart.destroy()
      }

      cummulativeChart(arrMonth,"timesheet/getFilterCummulativeMandaysChart?",arrFilter)

      //level mandays chart update
      if (accesable.includes('nav-tab-table')) {
        levelPieChart.destroy()
        levelChart("/timesheet/getFilterLevelChart?",arrFilter)

        //status mandays chart update
        statusPieChart.destroy()
        statusChart("/timesheet/getFilterStatusChart?",arrFilter)

        //schedule mandays chart update
        schedulePieChart.destroy()
        scheduleChart("/timesheet/getFilterScheduleChart?",arrFilter)

        //task chart update
        taskPieChart.destroy()
        taskChart("/timesheet/getFilterTaskChart?",arrFilter)

        //phase chart update
        phasePieChart.destroy()
        phaseChart("/timesheet/getFilterPhaseChart?",arrFilter)
      }
      
      //remaining chart update
      const yearRegex = /\b\d{4}\b/; // Matches a 4-digit number
      const yearMatch = arrFilter.match(yearRegex);
      if (yearMatch) {
        const year = yearMatch[0];
        $.each(remainingBarChart,function(idx,value){
          value.destroy()
        })
        $("#pagination").empty("")
        $("#box-remaining").empty("")
        duplicateCanvasRemaining("timesheet/getFilterRemainingChart?",arrFilter)
      } 
    }

    if (val) {
      $("#title_summary_year").text($("#selectYear").val())
      $("#textLevel").text($("#selectYear").val())
      $("#textStatus").text($("#selectYear").val())
      $("#textSchedule").text($("#selectYear").val())
      $("#textTask").text($("#selectYear").val())
      $("#textPhase").text($("#selectYear").val())
    }   

    if ($("#cbMonth:checked").length == 1) {
      var monthAsMoment = moment($("#cbMonth:checked").val(), 'MMMM');
      var numericMonth = monthAsMoment.month();
      var monthAsMoment = moment().month(parseInt(numericMonth));
      $("#span-remaining").text(moment(monthAsMoment).format('MMMM'))
    }
    // else{
    //   var numericMonth = '1'; // Replace this with your numeric month value
    //   var monthAsMoment = moment().month(parseInt(numericMonth) - 1);
    //   var monthFullName = monthAsMoment.format('MMMM');
    //   $("#span-remaining").text(monthFullName) 
    // } 
  }
    // else{
    //   var numericMonth = '1'; // Replace this with your numeric month value
    //   var monthAsMoment = moment().month(parseInt(numericMonth) - 1);
    //   var monthFullName = monthAsMoment.format('MMMM');
    //   $("#span-remaining").text(monthFullName) 
    // }

  $('#tbSummaryMandays').on('xhr.dt', function (e, settings, json, xhr) {
    // AJAX reload is complete
    $("#filterSumPoint").find("i").css("color","#80ff80")
    $("#filterSumPoint").find("span").text("ready to filter")
    $("#loadingIndicator").hide()
  });

  function resetFilter(){
    location.reload()    
  }

  function changeNumberEntries(id_table,num){
    $('#'+id_table).DataTable().page.len(num).draw()
  }

  function showDetailActual(nik,pid){
    $("#modalDetailActual").modal("show")

    if ($.fn.DataTable.isDataTable('#tbDetailActual')) {
      $('#tbDetailActual').DataTable().ajax.url("{{url('/timesheet/detailActivitybyPid')}}?nik="+nik+"&pid="+pid).load();
    }else{
      var table = $('#tbDetailActual').DataTable({
        "ajax":{
            "type":"GET",
            "url":"{{url('/timesheet/detailActivitybyPid')}}?nik="+nik+"&pid="+pid,
          },
          "columns": [
            {
              className: 'dt-control',
              orderable: false,
              data: null,
              defaultContent: '',
              "width":"10%"
            },
            { 
              "title":"Name",
              "data": "name",
              "width":"50%"
            },
            { 
              "title":"Date",
              "data": "start_date",
              "width":"40%"
            },
          ],
      })

      $('#tbDetailActual tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
 
          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row
              row.child(format(row.data())).show();
              tr.addClass('shown');
          }
      });

      function format(d) {
          // `d` is the original data object for the row
        var append = ""
        append = append +'<table class="table table-bordered table-striped" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' 
        append = append +'<tr>' 
        append = append +  '<td>Schedule</td>' 
        append = append +  '<td>Type</td>' 
        append = append +  '<td>PID</td>' 
        append = append +  '<td>Task</td>' 
        append = append +  '<td>Phase</td>' 
        append = append +  '<td>Level</td>'
        append = append +  '<td>Activity</td>' 
        append = append +  '<td>Duration</td>' 
        append = append +  '<td>Status</td>' 
        d.activity.forEach((item) => {
        //You can perform your desired function out here
          append = append + '<tr>' 
            append = append +   '<td>'+ item.schedule +'</td>' 
            append = append +   '<td>'+ item.type +'</td>' 
            append = append +   '<td>'+ item.pid +'</td>'
            append = append +   '<td>'+ item.task +'</td>' 
            append = append +   '<td>'+ item.phase +'</td>' 
            append = append +   '<td>'+ item.level +'</td>'
            append = append +   '<td>'+ item.activity +'</td>' 
            append = append +   '<td>'+ item.duration +' Menit</td>' 
            append = append +   '<td>'+ item.status +'</td>'
          append = append + '</tr>'
        })
        append = append +'</table>' 

        return append;
      }
    }    
  }
</script>
@endsection