@extends('template.main')
@section('tittle')
Report Presales
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
      Report Presales
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
      <li class="active">Report Presales</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box">
          <div class="box-header with-border">
            <div class="pull-left">
              <label style="margin-top: 5px;margin-right: 5px">Filter Year</label>
              <select style="margin-right: 5px;width: 100px" class="form-control fa" id="year_filter">
                @foreach($years as $data)
                  <option value="{{$data->year}}">{{$data->year}}</option>
                @endforeach
              </select>
            </div>
            <div class="pull-right">
              <!-- <a href="{{url('/report_excel_presales')}}"> -->
                <button class="btn btn-success" onclick="exportExcel()">Excel</button>
              <!-- </a> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Total Lead</i></h3>
          </div>
          <div class="box-body">
            <?php $no_sip = 1; ?>
            <table class="table table-bordered table-striped" width="100%" cellspacing="0" id="table_lead_presales">
              <thead>
                <tr>
                  <!-- <th width="5%"><center>No.</center></th> -->
                  <th><center>Presales Name</center></th>
                  <th ><center>Total INITIAL</center></th>
                  <th ><center>Total OPEN</center></th>
                  <th ><center>Total SD</center></th>
                  <th ><center>Total TP</center></th>
                  <th ><center>Total WIN</center></th>
                  <th ><center>Total LOSE</center></th>
                  <th ><center>Total HOLD</center></th>
                  <th ><center>Total CANCEL</center></th>
                  <th ><center>Total SPESIAL</center></th>
                  <th ><center>Total LEAD</center></th>
                </tr>
              </thead>
              <tbody id="body_sip" name="body_sip">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Solution Design</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_sd" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Presales Name</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_sd" name="report_sd">
                  <?php $no = 1; ?>
                  @foreach($lead_sd as $sds)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $sds->name }}</td>
                      <td align="right">
                        @if($sds->amounts != NULL)
                          <i class="money">{{ $sds->amounts }}</i>
                        @endif
                      </td>
                      <td><center>{{ $sds->leads }}</center></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="box box-warning">
          <div class="box-header with-border">
          <h3 class="box-title">Tender Process</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_tp" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Presales Name</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_tp" name="report_tp">
                  <?php $no = 1; ?>
                  @foreach($lead_tp as $tps)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $tps->name }}</td>
                      <td align="right">
                        @if($tps->amounts != NULL)
                          <i class="money">{{ $tps->amounts }}</i>
                        @endif
                      </td>
                      <td><center>{{ $tps->leads }}</center></td>
                    </tr>
                  @endforeach
                </tbody>
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
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_win" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Presales Name</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_win" name="report_win">
                  <?php $no = 1; ?>
                  @foreach($lead_win as $wins)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $wins->name }}</td>
                      <td align="right">
                        @if($wins->amounts != NULL)
                          <i class="money">{{ $wins->deal_prices }}</i>
                        @endif
                      </td>
                      <td><center>{{ $wins->leads }}</center></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Lose</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_lose" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%"><center>No.</center></th>
                    <th><center>Presales Name</center></th>
                    <th width="20%"><center>Total Amount</center></th>
                    <th width="10%"><center>Total</center></th>
                  </tr>
                </thead>
                <tbody id="report_lose" name="report_lose">
                  <?php $no = 1; ?>
                  @foreach($lead_lose as $loses)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $loses->name }}</td>
                      <td align="right">
                        @if($loses->amounts != NULL)
                          <i class="money">{{ $loses->amounts }}</i>
                        @endif
                      </td>
                      <td><center>{{ $loses->leads }}</center></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Lead Register</h3>
          </div>

          <div class="box-body">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs" id="myTab">
                @foreach($users as $user)
                    @if($user->name == 'Ganjar Pramudya Wijaya')
                        <li class="nav-item active">
                            <a class="nav-link active" id="{{ $user }}-tab" data-toggle="tab" href="#{{ $user->nik }}" role="tab" aria-controls="{{ $user }}" aria-selected="true" onclick="changeLeadPresales('{{$user->nik}}')">
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="{{ $user }}-tab" data-toggle="tab" href="#{{ $user->nik }}" role="tab" aria-controls="{{ $user }}" aria-selected="true" onclick="changeLeadPresales('{{$user->nik}}')">
                    @endif
                                {{ $user->name }}
                            </a>
                        </li>
                @endforeach
              </ul>
              <div class="tab-content">
                <div class="tab-pane active"  role="tabpanel">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th><center>Lead Id</center></th>
                          <th><center>Customer</center></th>
                          <th><center>Opty Name</center></th>
                          <th><center>Owner</center></th>
                          <th><center>Amount</center></th>
                          <th><center>Amount</center></th>
                          <th><center>Status</center></th>
                        </tr>
                      </thead>
                    </table>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
<!-- bootstrap datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
@endsection
@section('script')
<script>
    $('.money').mask('000,000,000,000,000,000', {reverse: true});
    $('.total').mask('000,000,000,000,000,000.00', {reverse: true});

    numeral.register('locale', 'id', {
        delimiters: {
            thousands: '.',
            decimal: ','
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        currency: {
            symbol: 'Rp '
        }
    });

    $("#startdate").on('change',function(){
	    $("#enddate").attr('disabled',false)
	    
	    $("#enddate").on('change',function(){
	        $("#filter_submit").attr('disabled',false)
	    });
    });

    $("#data_sd").DataTable({
    })

    $("#data_tp").DataTable({
    })

    $("#data_win").DataTable({
    })

    $("#data_lose").DataTable({
    })

    initLeadTable();

    initPresalesTable();

    function initPresalesTable() {
      $("#data_lead").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('get_lead_init_presales')}}",
          "dataSrc": function (json){

            // switch between locales
            numeral.locale('id');

            json.data.forEach(function(data,index){
              data.amount_formated = numeral(data.amount).format('$0,0.00');
            });
            return json.data;
          }
        },
        "columns": [
          // { "data": "name" },
          { "data": "lead_id" },
          { "data": "brand_name" },
          { "data": "opp_name" },
          { "data": "name" },
          { 
            "data": "amount_formated",
            "className": "text-right",
            "orderData" : [ 5 ],
          },
          { 
            "data": "amount",
            "targets":[4],
            "visible": false,
          },
          /*{ 
            "data": "amount",
            "targets": [ 5 ] ,
            "visible": false ,
            "searchable": true
          },*/
          // { "data": "amount" },
          { "data": "results" },
        ],
        "searching": true,
        "lengthChange": false,
        // "paging": false,
        "info":false,
        "scrollX": false,
        "order": [[ 5, "asc" ]]
      })
    }

  	function initLeadTable(){

  		$("#table_lead_presales").DataTable({
  			"ajax":{
  				"type":"GET",
  				"url":"{{url('getdatalead')}}",
  			},
  			"columns": [
  				// { "data": "name" },
  				{ "data": "name" },
  				{ "data": "INITIAL" },
  				{ "data": "OPEN" },
  				{ "data": "SD" },
  				{ "data": "TP" },
  				{ "data": "WIN" },
  				{ "data": "LOSE" },
  				{ "data": "HOLD" },
  				{ "data": "CANCEL" },
  				{ "data": "SPESIAL" },
  				{ "data": "All" },
  			],
  			"searching": true,
  			"lengthChange": false,
  			// "paging": false,
  			"info":false,
  			"scrollX": false,
  			"order": [[ 1, "desc" ]]
  		})

	  }

    function changeLeadPresales(nik) {
      $('#data_lead').DataTable().ajax.url("{{url('filter_presales_each_year')}}?nik=" + nik + "&" + "year=" + $('#year_filter').val()).load();
    }

    $('#enddate').datepicker({
        autoclose: true
    })

    $('#startdate').datepicker({
        autoclose: true
    })

    var url = {!! json_encode(url('/')) !!}

    function exportExcel() {
      type = encodeURI($("#year_filter").val())
      myUrl = url+"/report_excel_presales?year=" + type
      location.assign(myUrl)
    }

    $('#filter_submit').click(function() {
	    var type = this.value;
	    console.log(this.value);
	      $.ajax({
	        type:"GET",
	        url:"/getfiltersdpresales",
	        data:{
	          data:this.value,
	          type:type,
	          start:moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD 00:00:00"),
	          end:moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD 23:59:59")
	        },
	        success: function(result){
	          $('#report_sd').empty();

	          var table = "";
	          var no = 1;

	          $.each(result, function(key, value){
	            table = table + '<tr>';
	            table = table + '<td>'+ no++ +'</td>';
	            table = table + '<td>' +value.name+ '</td>';
	            table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	            table = table + '<td><center>(' +value.leads+ ')</center></td>';
	            table = table + '</tr>';

	          });
	          $('#report_sd').append(table);
	          
	        },
	      });

      $.ajax({
        type:"GET",
        url:"/getfiltertppresales",
        data:{
          data:this.value,
          type:type,
          start:moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD 00:00:00"),
          end:moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD 23:59:59")
        },
        success: function(result){
          $('#report_tp').empty();

          var table = "";
          var no = 1;

          $.each(result, function(key, value){
            table = table + '<tr>';
            table = table + '<td>'+ no++ +'</td>';
            table = table + '<td>' +value.name+ '</td>';
            if(value.amounts == null) {
              table = table + '<td><center> - </center></td>';
            } else {
              table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
            }
            table = table + '<td><center>(' +value.leads+ ')</center></td>';
            table = table + '</tr>';

          });
          $('#report_tp').append(table);
          
        },
      });

      $.ajax({
        type:"GET",
        url:"/getfilterwinpresales",
        data:{
          data:this.value,
          type:type,
          start:moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD 00:00:00"),
          end:moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD 23:59:59")
        },
        success: function(result){
          $('#report_win').empty();

          var table = "";
          var no = 1;

          $.each(result, function(key, value){
            table = table + '<tr>';
            table = table + '<td>'+ no++ +'</td>';
            table = table + '<td>' +value.name+ '</td>';
            table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
            table = table + '<td><center>(' +value.leads+ ')</center></td>';
            table = table + '</tr>';

          });
          $('#report_win').append(table);
          
        },
      });

      $.ajax({
        type:"GET",
        url:"/getfilterlosepresales",
        data:{
          data:this.value,
          type:type,
          start:moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD 00:00:00"),
          end:moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD 23:59:59")
        },
        success: function(result){
          $('#report_lose').empty();

          var table = "";
          var no = 1;

          $.each(result, function(key, value){
            table = table + '<tr>';
            table = table + '<td>'+ no++ +'</td>';
            table = table + '<td>' +value.name+ '</td>';
            table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
            table = table + '<td><center>(' +value.leads+ ')</center></td>';
            table = table + '</tr>';

          });
          $('#report_lose').append(table);
          
        },
      });

    });

    $('#year_filter2').change(function(){
	    var type = this.value;
	    console.log(this.value);

	    $.ajax({
	      type:"GET",
	      url:"/getfiltersdyearpresales",
	      data:{
	        data:this.value,
	        type:type,
	      },
	      success: function(result){
	        $('#report_sd').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_sd').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"/getfiltertpyearpresales",
	      data:{
	        data:this.value,
	        type:type,
	      },
	      success: function(result){
	        $('#report_tp').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          if(value.amounts == null) {
	            table = table + '<td><center> - </center></td>';
	          } else {
	            table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          }
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_tp').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"/getfilterwinyearpresales",
	      data:{
	        data:this.value,
	        type:type,
	      },
	      success: function(result){
	        $('#report_win').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_win').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"/getfilterloseyearpresales",
	      data:{
	        data:this.value,
	        type:type,
	      },
	      success: function(result){
	        $('#report_lose').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_lose').append(table);
	        
	      },
	    });

    });

    $('#data_summary').DataTable();
    $('#data_all_sales').DataTable();

    $('#data_all').DataTable({
	    "retrive" : true,
	    "order": [[ 2, "desc" ]],
	    "orderCellsTop": true,

	    "footerCallback": function( row, data, start, end, display ) {

	      var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'Rp ' ).display;

	      var api = this.api(),data;

	      var total = api.column(5, {page:'current'}).data().sum();

	      var filtered = api.column( 5, {"filter": "applied"} ).data().sum();

	      var totalpage = api.column(6).data().sum();

	          $( api.column( 4 ).footer() ).html("<p align='right'>Total Amount: </p>");

	          $( api.column( 5 ).footer() ).html("<p align='right'>"+ numFormat(totalpage) + "</p>");

	          $( api.column( 5 ).footer() ).html("<p align='right'>"+ numFormat(filtered) + "</p>" +'');
	    },

	    initComplete: function () {
	      this.api().columns([[4],[6]]).every( function () {
	          var column = this;
	          var select = $('<select class="form-control kat_drop" id="kat_drop" style="width:100%" name="kat_drop"><option value="">Filter</option></select>')
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

  	function initkat(){
  	    $('.kat_drop').select2();
  	}

  	$('#dropdown').select2();


  	$('#year_filter').change(function(){
      $('#data_lead').DataTable().ajax.url("{{url('filter_presales_each_year')}}?nik=1110492070&year=" + this.value).load();
	    console.log(this.value);
	    var tahun = this.value;
	    $.ajax({
	      type:"GET",
	      url:"filter_lead_presales",
	      data:{
	        data:this.value,
	        type:tahun,
	      },
	      success: function(result){
	        $('#body_sip').empty();
	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){

	          table = table + '<tr>';
	          // table = table + '<td>' + no++ + '</td>';
	          table = table + '<td>' + value.name + '</td>';
	          table = table + '<td>' + value.INITIAL + '</td>';
	          table = table + '<td>' + value.OPEN + '</td>';
	          table = table + '<td>' + value.SD + '</td>';
	          table = table + '<td>' + value.TP + '</td>';
	          table = table + '<td>' + value.WIN + '</td>';
	          table = table + '<td>' + value.LOSE + '</td>';
	          table = table + '<td>' + value.HOLD + '</td>';
	          table = table + '<td>' + value.CANCEL + '</td>';
	          table = table + '<td>' + value.SPESIAL + '</td>';
	          table = table + '<td>' + value.All + '</td>';
	          table = table + '</tr>';

	        });

	        $('#body_sip').append(table);
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"getfiltersdyearpresales",
	      data:{
	        data:this.value,
	        type:tahun,
	      },
	      success: function(result){
	        $('#report_sd').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_sd').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"getfiltertpyearpresales",
	      data:{
	        data:this.value,
	        type:tahun,
	      },
	      success: function(result){
	        $('#report_tp').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          if(value.amounts == null) {
	            table = table + '<td><center> - </center></td>';
	          } else {
	            table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          }
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_tp').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"getfilterwinyearpresales",
	      data:{
	        data:this.value,
	        type:tahun,
	      },
	      success: function(result){
	        $('#report_win').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_win').append(table);
	        
	      },
	    });

	    $.ajax({
	      type:"GET",
	      url:"getfilterloseyearpresales",
	      data:{
	        data:this.value,
	        type:tahun,
	      },
	      success: function(result){
	        $('#report_lose').empty();

	        var table = "";
	        var no = 1;

	        $.each(result, function(key, value){
	          table = table + '<tr>';
	          table = table + '<td>'+ no++ +'</td>';
	          table = table + '<td>' +value.name+ '</td>';
	          table = table + '<td align="right"><i>' +value.amounts.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")+ '.00</i></td>';
	          table = table + '<td><center>(' +value.leads+ ')</center></td>';
	          table = table + '</tr>';

	        });
	        $('#report_lose').append(table);
	        
	      },
	    });
  	});  
</script>
@endsection