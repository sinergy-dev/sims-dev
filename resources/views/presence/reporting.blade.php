@extends('template.main')
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">

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
</style>
@endsection
@section('content')
	<section class="content-header">
		<h1>
			Presence Reporting
		</h1>
		<ol class="breadcrumb">
			@role('admin')
			<li>
				<a href="{{url('dashboard')}}">
					<i class="fa fa-fw fa-dashboard"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="#">Presence</a>
			</li>
			<li class="active">
				<a href="{{url('presence/report')}}">Reporting</a>
			</li>
			@endrole
		</ol>
	</section>
	<section class="content">
		<div class="row">	
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title"></h3>
						<div class="pull-right">
							<select class="btn bg-blue" style="width: 80px; margin-left: 10px;" id="filter_com">
					            <option value="">All</option>
					            <option value="SIP">SIP (ALL)</option>
					            <option value="SIP-MSM">SIP (MSM)</option>
					            <option value="MSP">MSP</option>
				          	</select>
				          	<input type="hidden" id="startDate">
				          	<input type="hidden" id="endDate">
							<button type="button" class="btn btn-success" style="margin-left: 10px;" onclick="exportExcel('{{action('PresenceController@getExportReport')}}')"><i class='fa fa-download'></i>Export</button>
							<button type="button" class="btn btn-default pull-left" id="daterange-btn">
									<i class="fa fa-calendar"></i> Date range picker
								<span>
									<i class="fa fa-caret-down"></i>
								</span>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12 table-responsive">
								<!-- <table class="table table-hover">
									<thead>
										<tr>
											<th>User</th>
											<th>Schedule</th>
											<th>Checkin</th>
											<th>Checkout</th>
											<th style='text-align:center'>Condition</th>
											<th style='text-align:center'>Valid</th>
											<th>Reason</th>
										</tr>
									</thead>
									<tbody id="tableReport">
									</tbody>
								</table> -->
								<table class="table table-bordered table-striped display" id="report_table">
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
				</div>
			</div>
		</div>
	</section>
@endsection
@section('scriptImport')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
	<script src="https://adminlte.io/themes/AdminLTE/bower_components/chart.js/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js" referrerpolicy="no-referrer"></script>
  	 -->
  	<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
@endsection
@section('script')
	<script type="text/javascript">

		$(document).ready(function(){
			$.ajax({
				type:"GET",
				url:"{{url('/presence/report/getData2')}}",
				success: function(result){
					// console.log(result)
					$(".box-title").text("This period " + result.range)
					var append = ""
					var no = 1
					$.each(result.data,function(index,data){
						append = append + "<tr>"
						append = append + "	<td>" + no++ + "</td>"
						append = append + "	<td>" + data.name + "</td>"
						append = append + "	<td>" + data.where + "</td>"
						append = append + "	<td class='text-center'> <span class='badge bg-green'>" + data.ontime + "</span> </td>"
						append = append + "	<td class='text-center'> <span class='badge bg-yellow'>" + data.injury + "</span> </td>"
						append = append + "	<td class='text-center'> <span class='badge bg-red'>" + data.late + "</span> </td>"
						append = append + "	<td class='text-center'> <span class='badge bg-default'>" + data.absen + "</span> </td>"
						append = append + "	<td class='text-center'> <span class='badge bg-blue'>" + data.all + "</span> </td>"
						append = append + "</tr>"
					})

					$("#table_report").append(append)
				}
			})
		})

		$('#daterange-btn').daterangepicker({
			ranges: {
				'This Period': [moment("16 " + moment().subtract(1,'months').format("MM YYYY"),"DD MM YYYY"), moment("15 " + moment().format("MM YYYY"),"DD MM YYYY")],
			},
			startDate: moment().subtract(29, 'days'),
			endDate: moment()
		},
		function (start, end) {
			$('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));

			var startDay = start.format('YYYY-MM-DD');
			var endDay = end.format('YYYY-MM-DD');

			startDate = start.format('D MMMM YYYY');
			endDate = end.format('D MMMM YYYY');

			$("#table_report").empty();

			Pace.restart();
			Pace.track(function() {
				$.ajax({
					type:"GET",
					url:"{{url('/presence/report/getFilterReport')}}",
					data: {
						'start' : startDay,
						'end' : endDay,
					},
					success: function(result){
						// console.log("abc")
						// console.log(result)
						$(".box-title").text("This period " + result.range)
						var append = ""
						var no = 1
						$.each(result.data,function(index,data){
							append = append + "<tr>"
							append = append + "	<td>" + no++ + "</td>"
							append = append + "	<td>" + data.name + "</td>"
							append = append + "	<td>" + data.where + "</td>"
							append = append + "	<td class='text-center'> <span class='badge bg-green'>" + data.ontime + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-yellow'>" + data.injury + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-red'>" + data.late + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-default'>" + data.absen + "</span> </td>"
							append = append + "	<td class='text-center'> <span class='badge bg-blue'>" + data.all + "</span> </td>"
							append = append + "</tr>"
						})

						$("#table_report").append(append)
					}
				})
			})
		});

		$(document).on('change', '.validationCheck', function() {
		    if(!$(this).is(':checked')) {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',false)
		    } else {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',true)
		    }
		});

		function exportExcel(url){
	    	window.location = url + "?type=" + $("#filter_com").val();
	  	}

		// $("#filter_com").change(function(){
	 //      var filter_com = this.value;
	 //      console.log(filter_com);
	 //      $('#report_table').DataTable().ajax.url("{{url('/presence/report/getFilterCom')}}?filter_com="+filter_com).load();
	 //    });

	</script>
@endsection