@extends('template.main')
@section('tittle')
Presence Report
@endsection
@section('head_css')
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="{{ asset('css/jquery.transfer.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="{{ asset('css/icon_font/css/icon_font.css?v=0.0.3')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<!-- <link rel="preload" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
<style type="text/css">
	.pace .pace-progress {
		background: #ffffff;
		position: fixed;
		z-index: 2000;
		top: 0;
		right: 100%;
		width: 100%;
		height: 2px;
	}

	body{
	zoom: 90%;
	}
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    Presence Report
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Presence Report</li>
  </ol>
</section>
<section class="content">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"></h3>
			<div class="pull-right">
				<!-- 		<select class="btn bg-blue" style="width: 80px; margin-left: 10px;" id="filter_com">
				            <option value="">All</option>
				            <option value="SIP">SIP (ALL)</option>
				            <option value="SIP-MSM">SIP (MSM)</option>
				            <option value="MSP">MSP</option>
			          	</select> -->
	          	<input type="hidden" id="startDate">
	          	<input type="hidden" id="endDate">
				<button type="button" class="btn btn-success" style="margin-left: 10px;" onclick="exportExcel('{{action('PresenceController@getExportReport')}}')"><i class='fa fa-download'></i> Export</button>
				
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="form-group">
						<button type="button" class="btn btn-default" id="daterange-btn">
							<i class="fa fa-calendar"></i> Date range picker
							<span>
								<i class="fa fa-caret-down"></i>
							</span>
						</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-xs-12">
					<div id="shuttle-box" disabled>
				    <div id="transfer3" class="transfer-demo"></div>
					</div><br>
				</div>
				<div class="col-md-9 col-xs-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped display" id="report_table" style="overflow-x:auto;">
							<thead>
								<tr>
									<th style="width: 7px;">No</th>
									<th>Name</th>
									<th>Location</th>
									<th style="width: 70px;" class="text-center">On Time</th>
									<th style="width: 50px;" class="text-center">Injury</th>
									<th style="width: 50px;" class="text-center">Late</th>
									<th style="width: 50px;" class="text-center">Absent</th>
									<th style="width: 50px;" class="text-center">All</th>
								</tr>
							</thead>
							<tbody id="table_report">
							</tbody>
						</table>
					</div>					
				</div>
			</div>						
			<!-- <div class="row mb-3">
				<div class="col-md-12 col-xs-12">
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-4">
							
						</div>									
					</div>
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							
						</div>
							    <div class="col-sm-5">
							    	<label>Items</label>
							        <select name="from" id="optgroup" class="form-control" size="8" multiple="multiple">
							        </select>
							    </div>
							    
							    <div class="col-sm-2">
							    	<br>
							        <button type="button" id="optgroup_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
							        <button type="button" id="optgroup_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
							        <button type="button" id="optgroup_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
							        <button type="button" id="optgroup_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
							    </div>
							    
							    <div class="col-sm-5">
							    	<label>Selected Items</label>
							        <select name="to" id="optgroup_to" class="form-control" size="8" multiple="multiple">
							        </select>
							    </div>
					</div>
				</div>					
				
			</div>
			<div class="row">
				<div class="col-md-12">
					
				</div>
			</div> -->
		</div>
	</div>
</section>	
@endsection
@section('scriptImport')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="{{asset('js/jquery.transfer.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
@endsection
@section('script')
	<script type="text/javascript">
		var selectedUser = []
		var filterUser = []
		var type_date = ""
		var startDay,endDay
		var selectDate = false
		var selectPerson = false

		$(document).ready(function(){  
			Pace.restart();
			Pace.track(function() {
				$.ajax({
					type:"GET",
					url:"{{url('/presence/report/getDataReportPresence')}}",
					beforeSend:function(){
	  				$("#table_report").empty("")
	  				$("#table_report").append("<tr><td colspan='8'> <p style='text-align:center'>Loading . . .</p></td></tr>")
					},
					success: function(result){
	  				$("#table_report").empty("")

						$(".box-title").text("This period " + result.range)
						var append = ""
						var no = 1
						$.each(result.data,function(index,data){
							append = append + "<tr>"
							append = append + "	<td>" + no++ + "</td>"
							var name = data.name.split(" ")
							var finalName = []
							name.forEach(function(item,index){
							  finalName.push(item.charAt(0).toUpperCase() + item.slice(1).toLowerCase())
							})

							append = append + "	<td>" + finalName.toString().replaceAll(","," ") + "</td>"
							append = append + "	<td>" + data.where + "</td>"
							append = append + "	<td class='text-center'> <span class='badge bg-green'>" + data.ontime + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-yellow'>" + data.injury + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-red'>" + data.late + "</span> </td>"
							var absen = 0
							if (data.absen == undefined) {
								absen = absen
							}else{
								absen = data.absen
							}
							append = append + "	<td class='text-center'> <span class='badge bg-default'>" + absen + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-blue'>" + data.all + "</span> </td>"
							append = append + "</tr>"
						})

						if (result.data.length == 0) {
							$("#table_report").append("<tr><td colspan='8'> <p style='text-align:center'>Data is empty!</p></td></tr>")
						}else{
							$("#table_report").append(append)
						}
					}
				})
			})

		})

		$('#daterange-btn').daterangepicker({
			ranges: {
				'This Period HRD': [moment("16 " + moment().subtract(1,'months').format("MM YYYY"),"DD MM YYYY"), moment("15 " + moment().format("MM YYYY"),"DD MM YYYY")],
				'This Period MSM': [moment("26 " + moment().subtract(1,'months').format("MM YYYY"),"DD MM YYYY"), moment("25 " + moment().format("MM YYYY"),"DD MM YYYY")],

			}
		},
		function (start, end) {
			// if ($('#daterange-btn').data('daterangepicker').chosenLabel == 'This Period HRD') {
			// 	type = "HRD"
			// }else if ($('#daterange-btn').data('daterangepicker').chosenLabel == 'This Period MSM') {
			// 	type = 'MSM'
			// }else {
			// 	type = ''
			// }
			$("#shuttle-box").prop("disabled",false)

			$('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));

			startDay = start.format('YYYY-MM-DD');
			endDay = end.format('YYYY-MM-DD');

			$("#startDate").val(startDay)
			$("#endDate").val(endDay)

			startDate = start.format('D MMMM YYYY');
			endDate = end.format('D MMMM YYYY');

			table_presence()
		});

		$(document).on('change', '.validationCheck', function() {
		    if(!$(this).is(':checked')) {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',false)
		    } else {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',true)
		    }
		});

		function exportExcel(url){
			if($("#startDate").val() != "" && $("#endDate") != ""){
				url = url + "?startDate=" + $("#startDate").val() + "&endDate=" + $("#endDate").val() + selectedUser
			}

	    	window.location = url;
	  }

	  $.ajax({
      url:"{{url('/presence/getUser')}}",
      type:"GET",
      success:function(result){
      	var settings = {
	        "groupDataArray": result,
	        "groupItemName": "text",
	        "groupArrayName": "children",
	        "itemName": "text",
	        "valueName": "nik",
	        "callable": function (items) {
	        	selectedUser = []
	        	filterUser = []
	        	$.each(items,function(key,value){
	        			if (!filterUser.includes(value.nik)) {
	            		filterUser.push(value.nik)
	            		selectedUser = selectedUser + '&nik[]=' + value.nik
	        			}
	        	})

	        	// var string = JSON.stringify(selectedUser)
	        	// string.replace (/"/g,'')
						$("#table_report").empty();
	        	table_presence()
	        }
	    };

			$("#transfer3").transfer(settings);
			// table_presence(selectDate = true,selectPerson = true)
  	}
	})

	  function table_presence()
	  {
		  	Pace.restart();
				Pace.track(function() {
					$.ajax({
						type:"GET",
						url:"{{url('/presence/report/getFilterReport')}}",
						data: {
							'start' : startDay,
							'end' : endDay,
							'nik' : filterUser,
						},
						beforeSend:function(){
		  				$("#table_report").empty("")
		  				$("#table_report").append("<tr><td colspan='8'> <p style='text-align:center'>Loading . . .</p></td></tr>")
						},
						success: function(result){
	  					$("#table_report").empty("")

							$(".box-title").text("This period " + result.range)
							var append = ""
							var no = 1
							$.each(result.data,function(index,value){
								append = append + "<tr>"
								append = append + "	<td>" + no++ + "</td>"

								var name = value.name.split(" ")
								var finalName = []
								name.forEach(function(item,index){
								  finalName.push(item.charAt(0).toUpperCase() + item.slice(1).toLowerCase())
								})

								append = append + "	<td>" + finalName.toString().replaceAll(","," ") + "</td>"
								append = append + "	<td>" + value.where + "</td>"
								append = append + "	<td class='text-center'> <span class='badge bg-green'>" + value.ontime + "</span> </td>"
								append = append + "	<td class='text-center'> <span class='badge bg-yellow'>" + value.injury + "</span> </td>"
								append = append + "	<td class='text-center'> <span class='badge bg-red'>" + value.late + "</span> </td>"
								var absen = 0
								if (value.absen == undefined) {
									absen = absen
								}else{
									absen = value.absen
								}
								append = append + "	<td class='text-center'> <span class='badge bg-default'>" + absen + "</span> </td>"
								append = append + "	<td class='text-center'> <span class='badge bg-blue'>" + value.all + "</span> </td>"
								append = append + "</tr>"
							})

							if (result.data.length == 0) {
								$("#table_report").append("<tr><td colspan='8'> <p style='text-align:center'>Data is empty!</p></td></tr>")
							}else{
								$("#table_report").append(append)
							}
						}
					})
				})	  	
	  }
			// $("#filter_com").change(function(){
		 //      var filter_com = this.value;
		 //      console.log(filter_com);
		 //      $('#report_table').DataTable().ajax.url("{{url('/presence/report/getFilterCom')}}?filter_com="+filter_com).load();
		 //    });

	</script>
@endsection