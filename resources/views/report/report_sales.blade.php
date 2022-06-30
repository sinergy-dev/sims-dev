@extends('template.main')
@section('tittle')
Report Sales
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="{{asset('template2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <style type="text/css">
    .btn-warning-export{
      background-color: #ffc107;
      border-color: #ffc107;
    }
    .dataTables_paging {
     display: none;
    }
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Report Sales
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
      <li class="active">Report Sales</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">

              <!-- <select style="margin-right: 5px;width: 100px" class="form-control" id="year_filter">
                @foreach($years as $data)
                <option value="{{$data->year}}"> &nbsp{{$data->year}}</option>
                @endforeach
              </select> -->
              <!-- <select class="form-control" style="width: 300px" id="dropdown" name="dropdown">
                <option >Select Win Probability</option>
                <option value="ALL">ALL</option>
                <option value="HIGH">HIGH</option>
                <option value="MEDIUM">MEDIUM</option>
                <option value="LOW">LOW</option>
              </select> -->
              <h3 class="box-title"><i class="fa fa-table"></i> Report Sales</h3>
          </div>
          <div class="row">
            <div class="col-md-12">
              <!-- <div class="col-md-2" style="margin-bottom: 15px;">
                <label>Filter Year</label>
                <select style="margin-right: 5px;width: 100px" class="form-control" id="year_filter">
                  @foreach($years as $data)
                  <option value="{{$data->year}}"> &nbsp{{$data->year}}</option>
                  @endforeach
                </select>
              </div> -->
              <div class="col-md-4" style="margin-bottom: 15px;">
                <label>Filter by Date</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control" style="width: 100%" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                  <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
                </div>
              </div>
              <div class="col-md-4">
                <!-- <button class="btn btn-primary btn-sm" id="apply-btn" style="margin-top: 25px"><i class="fa   fa-check-circle"></i> Apply</button> -->
                <button class="btn btn-info btn-sm reload-table" id="reload-table" style="margin-top: 25px"><i class="fa fa-refresh"></i> Refresh</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- <div class="col-lg-6" id="col-small"> -->
      <div style="display: none;" id="col-large">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i>TOP 5</i></h3>
            <h3 class="box-title pull-right"><b>SIP</b></h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered table-striped" id="data_top_sip" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th width="5%"><center>No.</center></th>
                  <th><center>Sales Name</center></th>
                  <th width="20%"><center>Total Amount</center></th>
                  <th width="10%"><center>Total</center></th>
                  <th width="10%"><center>Total</center></th>
                  <th width="10%"><center>Total</center></th>
                </tr>
              </thead>
              <tbody id="" name="">
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="box box-primary" id="box-msp" style="display: none;float: right;">
          <div class="box-header with-border">
            <h3 class="box-title"><i>TOP 5</i></h3>
            <h3 class="box-title pull-right"><b>MSP</b></h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered table-striped" id="data_top_msp" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th width="5%"><center>No.</center></th>
                  <th><center>Sales Name</center></th>
                  <th width="20%"><center>Total Amount</center></th>
                  <th width="10%"><center>Total</center></th>
                  <th width="10%"><center>Total</center></th>
                  <th width="10%"><center>Total</center></th>
                </tr>
              </thead>
              <tbody id="" name="">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <!-- <div class="row">
      <div class="col-lg-12">
        <div class="box">
          <div class="box-header with-border">
            <form action="" method="get" class="margin-bottom">
              <div class="row">
                <div class="col-md-12">
                  <select style="width: 100px;float: left;" class="form-control" id="year_filter2">
                  @foreach($years as $data)
                  <option value="{{$data->year}}">{{$data->year}}</option>
                  @endforeach
                  </select>
                  <div class="col-md-2">
                    <input type="text" id="startdate" class="form-control" autocomplete="off" placeholder="DD/MM/YYYY">          
                  </div>
                  <div style="float: left;margin-top: 5px">
                    <small>TO</small>
                  </div>
                  <div class="col-md-2">
                    <input type="text" id="enddate" class="form-control" autocomplete="off" placeholder="DD/MM/YYYY" disabled>
                  </div>
                  <div class="col-md-2">
                    <input type="button" name="filter_submit" id="filter_submit" value="Filter" class="btn btn-primary" disabled>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> -->

    <div class="row">
      <div class="col-lg-6">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Solution Design</h3>

            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_sd" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Sales Name</center></th>
                    <th width="15%"><center>Company</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>                    
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_sd" name="report_sd">
                </tbody>
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

      <div class="col-lg-6">
        <div class="box box-warning">
          <div class="box-header with-border">
          <h3 class="box-title">Tender Process</h3>
  
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_tp" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Sales Name</center></th>
                    <th width="15%"><center>Company</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_tp" name="report_tp">
                </tbody>
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

    <div class="row">
      <div class="col-lg-6">
        <div class="box box-success">
          <div class="box-header with-border">
          <h3 class="box-title">Win</h3>
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_win" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Sales Name</center></th>
                    <th width="15%"><center>Company</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_win" name="report_win">
                </tbody>
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

      <div class="col-lg-6">
        <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Lose</h3>
  
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_lose" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Sales Name</center></th>
                    <th width="15%"><center>Company</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_lose" name="report_lose">
                </tbody>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.24/api/sum().js"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('script')
<script>
  var accesable = @json($feature_item);
    if (accesable.includes('col-small')) {
      $('#box-msp').show()
      $('#col-large').show().addClass('col-lg-6')
    }else{
      $('#col-large').addClass('col-lg-12')

    } 

    accesable.forEach(function(item,index){
    $("#" + item).show() 

            
  })
  $("#startdate").on('change',function(){
    $("#enddate").attr('disabled',false)
    
    $("#enddate").on('change',function(){
        $("#filter_submit").attr('disabled',false)
    });
  });

  $('#enddate').datepicker({
    autoclose: true
  })

  $('#startdate').datepicker({
    autoclose: true
  })

  $('#reportrange').daterangepicker({
    startDate: moment().startOf('year'),
    endDate  : moment().endOf('year'),
    locale: {
      format: 'DD/MM/YYYY'
    }
  },function (start, end) {
      start: moment();
      end  : moment();

      start  = start.format("YYYY-MM-DD 00:00:00");
      end    = end.format("YYYY-MM-DD 00:00:00");

      $('#data_sd').DataTable().ajax.url("{{url('getfiltersd')}}?start=" + start + "&end=" + end).load();
      $('#data_tp').DataTable().ajax.url("{{url('getfiltertp')}}?start=" + start + "&end=" + end).load();
      $('#data_win').DataTable().ajax.url("{{url('getfilterwin')}}?start=" + start + "&end=" + end).load();
      $('#data_lose').DataTable().ajax.url("{{url('getfilterlose')}}?start=" + start + "&end=" + end).load();
      $('#data_top_sip').DataTable().ajax.url("{{url('get_filter_top_win_sip')}}?start=" + start + "&end=" + end).load();
      $('#data_top_msp').DataTable().ajax.url("{{url('get_filter_top_win_msp')}}?start=" + start + "&end=" + end).load();

  });

  var currentYear = moment().year(); // This Year

  var currentYearStart = moment({
    years: currentYear,
    months: '0',
    date: '1'
  }); // 1st Jan this year

  var currentYearEnd = moment({
    years: currentYear,
    months: '11',
    date: '31'
  });

  var appendDateRange = ''
  var dateRange = {};
  dateRange["Today"] = [moment(), moment()];
  dateRange["This Month"] = [moment().startOf('month'), moment().endOf('month')];
  $.each(JSON.parse('@json($years)'), function(key, value){
    if (value.year == currentYear) {
      dateRange["<i class='fa fa-calendar'></i> " + value.year] = [moment(currentYearStart), moment(currentYearEnd)];
    }else{
      dateRange["<i class='fa fa-calendar'></i> " + value.year] = [moment(currentYearStart.subtract(1, "year")), moment(currentYearEnd.subtract(1, "year"))];
    }
  })


  $('#daterange-btn').daterangepicker({
    ranges : dateRange,
    locale: {
      format: 'DD/MM/YYYY'
    }
  }, 
  function (start, end) {
    $('#reportrange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))

    start  = start.format("YYYY-MM-DD 00:00:00");
    end    = end.format("YYYY-MM-DD 00:00:00");

    $('#data_sd').DataTable().ajax.url("{{url('getfiltersd')}}?start=" + start + "&end=" + end).load();
    $('#data_tp').DataTable().ajax.url("{{url('getfiltertp')}}?start=" + start + "&end=" + end).load();
    $('#data_win').DataTable().ajax.url("{{url('getfilterwin')}}?start=" + start + "&end=" + end).load();
    $('#data_lose').DataTable().ajax.url("{{url('getfilterlose')}}?start=" + start + "&end=" + end).load();
    $('#data_top_sip').DataTable().ajax.url("{{url('get_filter_top_win_sip')}}?start=" + start + "&end=" + end).load();
    $('#data_top_msp').DataTable().ajax.url("{{url('get_filter_top_win_msp')}}?start=" + start + "&end=" + end).load();
  })

  // $('#filter_submit').click(function() {
  //   var data = this.value;
  //   var start = moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD 00:00:00");
  //   var end = moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD 23:59:59");
  //   $('#data_sd').DataTable().ajax.url("{{url('getfiltersd')}}?start=" + start + "&end=" + end).load();
  //   $('#data_tp').DataTable().ajax.url("{{url('getfiltertp')}}?start=" + start + "&end=" + end).load();
  //   $('#data_win').DataTable().ajax.url("{{url('getfilterwin')}}?start=" + start + "&end=" + end).load();
  //   $('#data_lose').DataTable().ajax.url("{{url('getfilterlose')}}?start=" + start + "&end=" + end).load();
  // })

  // $('#year_filter2').change(function() {
  //   var data = this.value;
  //   $('#data_sd').DataTable().ajax.url("{{url('getfiltersdyear')}}?data="+data+"&type=" + data).load();
  //   $('#data_tp').DataTable().ajax.url("{{url('getfiltertpyear')}}?data="+data+"&type=" + data).load();
  //   $('#data_win').DataTable().ajax.url("{{url('getfilterwinyear')}}?data="+data+"&type=" + data).load();
  //   $('#data_lose').DataTable().ajax.url("{{url('getfilterloseyear')}}?data="+data+"&type=" + data).load();
  // })

  $('#reload-table').click(function(){
    $('#reportrange').daterangepicker({
      startDate: moment().startOf('year'),
      endDate  : moment().endOf('year'),
      locale: {
        format: 'DD/MM/YYYY'
      }
    },function (start, end) {
        start: moment();
        end  : moment();

        start  = start.format("YYYY-MM-DD 00:00:00");
        end    = end.format("YYYY-MM-DD 00:00:00");
    });
    $('#data_sd').DataTable().ajax.url("{{url('get_data_sd_report_sales')}}").load();
    $('#data_tp').DataTable().ajax.url("{{url('get_data_tp_report_sales')}}").load();
    $('#data_win').DataTable().ajax.url("{{url('get_data_win_report_sales')}}").load();
    $('#data_lose').DataTable().ajax.url("{{url('get_data_lose_report_sales')}}").load();
    $('#data_top_sip').DataTable().ajax.url("{{url('get_top_win_sip')}}").load();
    $('#data_top_msp').DataTable().ajax.url("{{url('get_top_win_msp')}}").load();
  })

  // $('#data_sd').DataTable();
  var data_sd = $('#data_sd').DataTable({
     "responsive":true,
     // "orderCellsTop": true,
    "ajax":{
        "type":"GET",
        "url":"{{url('get_data_sd_report_sales')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { "data": "code_company" },
      { 
        data: null,
        className: "sum",
        render: function ( data, type, row ) {
          // return new Intl.NumberFormat('id').format(row.amounts)
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[4]
      },
      {
        "data":"amounts",
        "targets":[3],
        "searchable":true
      },
      {
        className: "sum2",
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        className: "sum3",
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[4,6],
        "visible":false
      },
      { targets: 'no-sort', orderable: false }
    ],
    "aaSorting": [],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 3, "desc" ]],
    "footerCallback": function(row, data, start, end, display) {
        var api = this.api();

        api.columns('.sum3', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $("th.sum").last().html($.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(sum));
        });

        api.columns('.sum2', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $(this.footer()).html(sum);
        });
    }
  });

  data_sd.on( 'order.dt search.dt', function () {
      data_sd.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  var data_tp = $('#data_tp').DataTable({
     "responsive":true,
     "orderCellsTop": true,
     
    "ajax":{
        "type":"GET",
        "url":"{{url('get_data_tp_report_sales')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { "data": "code_company" },
      { 
        data: null,
        className: "sum4",
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[4]
      },
      {
        "data":"amounts",
        "targets":[3],
        "searchable":true
      },
      {
        className: "sum5",
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        className: "sum6",
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[4,6],
        "visible":false
      },
      {
        targets:"no-sort",orderable:false
      }
    ],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 3, "desc" ]],
    "footerCallback": function(row, data, start, end, display) {
        var api = this.api();

        api.columns('.sum6', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $("th.sum4").last().html($.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(sum));
        });

        api.columns('.sum5', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $(this.footer()).html(sum);
        });
    }
  });

  data_tp.on( 'order.dt search.dt', function () {
      data_tp.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  var data_win = $('#data_win').DataTable({
     "responsive":true,
     "orderCellsTop": true,
     
    "ajax":{
        "type":"GET",
        "url":"{{url('get_data_win_report_sales')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { "data": "code_company" },
      { 
        data: null,
        className: "sum7",
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[4]
      },
      {
        "data":"amounts",
        "targets":[3],
        "searchable":true
      },
      {
        className: "sum8",
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        className: "sum9",
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[4,6],
        "visible":false
      },
      {
        targets:'no-sort',orderable:false
      }
    ],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 3, "desc" ]],
    "footerCallback": function(row, data, start, end, display) {
        var api = this.api();

        api.columns('.sum9', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $("th.sum7").last().html($.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(sum));
        });

        api.columns('.sum8', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $(this.footer()).html(sum);
        });
    }
  });

  data_win.on( 'order.dt search.dt', function () {
      data_win.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  var data_lose = $('#data_lose').DataTable({
     "responsive":true,
     "orderCellsTop": true,
     
    "ajax":{
        "type":"GET",
        "url":"{{url('get_data_lose_report_sales')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { "data": "code_company" },
      { 
        data: null,
        className: "sum10",
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[4]
      },
      {
        "data":"amounts",
        "targets":[3],
        "searchable":true
      },
      {
        className: "sum11",
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        className: "sum12",
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[4,6],
        "visible":false
      },
      {
        targets:"no-sort",orderable:false
      }
    ],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 3, "desc" ]],
    "footerCallback": function(row, data, start, end, display) {
        var api = this.api();

        api.columns('.sum12', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $("th.sum10").last().html($.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(sum));
        });

        api.columns('.sum11', { page: 'current' }).every(function () {
            var sum = api
                .cells( null, this.index(), { page: 'current'} )
                .render('display')
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            $(this.footer()).html(sum);
        });
    }
  });

  data_lose.on( 'order.dt search.dt', function () {
      data_lose.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  var data_top_sip = $('#data_top_sip').DataTable({
    "ajax":{
        "type":"GET",
        "url":"{{url('get_top_win_sip')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { 
        data: null,
        className: "sum10",
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[3]
      },
      {
        "data":"amounts",
        "targets":[2],
        "searchable":true
      },
      {
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[3,5],
        "visible":false
      },
      {
        targets:"no-sort",orderable:false
      }
    ],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 2, "desc" ]],
  });

  data_top_sip.on( 'order.dt search.dt', function () {
      data_top_sip.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  var data_top_msp = $('#data_top_msp').DataTable({
    "ajax":{
        "type":"GET",
        "url":"{{url('get_top_win_msp')}}",
    },
    "columns": [
      { "data": "leads" },
      { "data": "name" },
      { 
        data: null,
        className: "sum10",
        render: function ( data, type, row ) {
          return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amounts)
        },
        "orderData":[3]
      },
      {
        "data":"amounts",
        "targets":[2],
        "searchable":true
      },
      {
        data: null,
        render: function ( data, type, row ) {
           return row.leads 
        }
      },
      {
        data: null,
        render: function ( data, type, row ) {
           return row.amounts 
        }
      }
    ],
    "columnDefs":[
      {
        "targets":[3,5],
        "visible":false
      },
      {
        targets:"no-sort",orderable:false
      }
    ],
    "scrollX": true,
    "pageLength": 25,
    "order": [[ 2, "desc" ]],
  });

  data_top_msp.on( 'order.dt search.dt', function () {
      data_top_msp.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  }).draw();

  function initkat(){
    $('.kat_drop').select2();
  }

  $('#dropdown').select2();

  $('.money').mask('000,000,000,000,000,000', {reverse: true});
  $('.total').mask('000,000,000,000,000,000.00', {reverse: true});
</script>
@endsection