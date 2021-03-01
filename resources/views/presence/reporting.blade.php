@extends('template.template_admin-lte')
@section('head_css')
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
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-block btn-default">
								<i class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							{{-- <div class="col-md-12">
								<p class="text-center">
									<strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
								</p>

								<div class="chart">
									<!-- Sales Chart Canvas -->
									<canvas id="salesChart" style="height: 380px; width: 657px;" height="360" width="1314"></canvas>
								</div>
							</div>
							<div class="col-md-12">
								<div class="progress-group">
									<span class="progress-text">On-Time Presence</span>
									<span class="progress-number"><b>160</b>/200</span>

									<div class="progress sm">
										<div class="progress-bar progress-bar-success" style="width: 80%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Injury-Time Presence</span>
									<span class="progress-number"><b>160</b>/200</span>

									<div class="progress sm">
										<div class="progress-bar progress-bar-warning" style="width: 80%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Late Presence</span>
									<span class="progress-number"><b>160</b>/200</span>

									<div class="progress sm">
										<div class="progress-bar progress-bar-danger" style="width: 80%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Uncheck-Out Presence</span>
									<span class="progress-number"><b>160</b>/200</span>

									<div class="progress sm">
										<div class="progress-bar progress-bar-primary" style="width: 80%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Absent</span>
									<span class="progress-number"><b>160</b>/200</span>

									<div class="progress sm">
										<div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
									</div>
								</div>
							</div> --}}
							<div class="col-md-12 table-responsive">
								<table class="table table-hover">
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
								</table>
							</div>
						</div>
					</div>
					{{-- <div class="box-footer">
						<div class="row">
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
									<h5 class="description-header">$35,210.43</h5>
									<span class="description-text">TOTAL REVENUE</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
									<h5 class="description-header">$10,390.90</h5>
									<span class="description-text">TOTAL COST</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
									<h5 class="description-header">$24,813.53</h5>
									<span class="description-text">TOTAL PROFIT</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-3 col-xs-6">
								<div class="description-block">
									<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
									<h5 class="description-header">1200</h5>
									<span class="description-text">GOAL COMPLETIONS</span>
								</div>
								<!-- /.description-block -->
							</div>
						</div>
						<!-- /.row -->
					</div> --}}
				</div>
			</div>
		</div>
	</section>
@endsection
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			// $(".box-title").text("This period " + moment().subtract(1,'month').format("YYYY-MM") + "-15 to " + moment().format("YYYY-MM") + "-14")

			// var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
			// // This will get the first returned node in the jQuery collection.
			// var salesChart       = new Chart(salesChartCanvas);

			// var salesChartData = {
			// 	labels  : ["15-Dec","16-Dec","17-Dec","18-Dec","19-Dec","20-Dec","21-Dec","22-Dec","23-Dec","24-Dec","25-Dec","26-Dec","27-Dec","28-Dec","29-Dec","30-Dec","31-Dec","1-Jan","2-Jan","3-Jan","4-Jan","5-Jan","6-Jan","7-Jan","8-Jan","9-Jan","10-Jan","11-Jan","12-Jan","13-Jan","14-Jan"],
			// 	datasets: [
			// 		{
			// 			label               : 'On-Time',
			// 			fillColor           : 'rgb(210, 214, 222)',
			// 			strokeColor         : 'rgb(210, 214, 222)',
			// 			pointColor          : 'rgb(210, 214, 222)',
			// 			pointStrokeColor    : '#c1c7d1',
			// 			pointHighlightFill  : '#fff',
			// 			pointHighlightStroke: 'rgb(220,220,220)',
			// 			data                : [10,14,7,12,0,0,16,24,22,21,13,0,0,7,22,18,20,6,0,0,4,14,19,16,14,0,0,21,21,20,17]
			// 		},
			// 		{
			// 			label               : 'Injury-Time',
			// 			fillColor           : 'rgba(60,141,188,0.9)',
			// 			strokeColor         : 'rgba(60,141,188,0.8)',
			// 			pointColor          : 'rgb(60,141,188)',
			// 			pointStrokeColor    : 'rgba(60,141,188,1)',
			// 			pointHighlightFill  : '#fff',
			// 			pointHighlightStroke: 'rgba(60,141,188,1)',
			// 			data                : [13,9,15,12,0,0,9,1,3,3,11,0,0,18,1,7,3,18,0,0,20,11,5,7,7,0,0,3,3,5,8]
			// 		},
			// 		{
			// 			label               : 'Late',
			// 			fillColor           : 'rgba(46, 204, 113,0.9)',
			// 			strokeColor         : 'rgba(46, 204, 113,0.8)',
			// 			pointColor          : 'rgb(46, 204, 113)',
			// 			pointStrokeColor    : 'rgba(46, 204, 113,1)',
			// 			pointHighlightFill  : '#fff',
			// 			pointHighlightStroke: 'rgba(46, 204, 113,1)',
			// 			data                : [1,1,2,0,0,0,0,0,0,1,1,0,0,0,0,0,2,1,0,0,1,0,0,0,1,0,0,1,1,0,0]
			// 		},
			// 		{
			// 			label               : 'Uncheck-Out',
			// 			fillColor           : 'rgba(230, 126, 34,0.9)',
			// 			strokeColor         : 'rgba(230, 126, 34,0.8)',
			// 			pointColor          : 'rgb(230, 126, 34)',
			// 			pointStrokeColor    : 'rgba(230, 126, 34,1)',
			// 			pointHighlightFill  : '#fff',
			// 			pointHighlightStroke: 'rgba(230, 126, 34,1)',
			// 			data                : [1,2,1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,2,0,3,0,0,0,3,0,0,1]
			// 		},
			// 		{
			// 			label               : 'Absent',
			// 			fillColor           : 'rgba(155, 89, 182,0.9)',
			// 			strokeColor         : 'rgba(155, 89, 182,0.8)',
			// 			pointColor          : 'rgb(155, 89, 182)',
			// 			pointStrokeColor    : 'rgba(155, 89, 182,1)',
			// 			pointHighlightFill  : '#fff',
			// 			pointHighlightStroke: 'rgba(155, 89, 182,1)',
			// 			data                : [1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,2,1,0,0,0,0,0,0]
			// 		}
			// 	]
			// };

			// var salesChartOptions = {
			// 	// Boolean - If we should show the scale at all
			// 	showScale               : true,
			// 	// Boolean - Whether grid lines are shown across the chart
			// 	scaleShowGridLines      : false,
			// 	// String - Colour of the grid lines
			// 	scaleGridLineColor      : 'rgba(0,0,0,.05)',
			// 	// Number - Width of the grid lines
			// 	scaleGridLineWidth      : 1,
			// 	// Boolean - Whether to show horizontal lines (except X axis)
			// 	scaleShowHorizontalLines: true,
			// 	// Boolean - Whether to show vertical lines (except Y axis)
			// 	scaleShowVerticalLines  : true,
			// 	// Boolean - Whether the line is curved between points
			// 	bezierCurve             : true,
			// 	// Number - Tension of the bezier curve between points
			// 	bezierCurveTension      : 0.3,
			// 	// Boolean - Whether to show a dot for each point
			// 	pointDot                : false,
			// 	// Number - Radius of each point dot in pixels
			// 	pointDotRadius          : 4,
			// 	// Number - Pixel width of point dot stroke
			// 	pointDotStrokeWidth     : 1,
			// 	// Number - amount extra to add to the radius to cater for hit detection outside the drawn point
			// 	pointHitDetectionRadius : 20,
			// 	// Boolean - Whether to show a stroke for datasets
			// 	datasetStroke           : true,
			// 	// Number - Pixel width of dataset stroke
			// 	datasetStrokeWidth      : 2,
			// 	// Boolean - Whether to fill the dataset with a color
			// 	datasetFill             : true,
			// 	// String - A legend template
			// 	legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
			// 	// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
			// 	maintainAspectRatio     : true,
			// 	// Boolean - whether to make the chart responsive to window resizing
			// 	responsive              : true
			// };

			// // Create the line chart
			// salesChart.Line(salesChartData, salesChartOptions);

			$.ajax({
				type:"GET",
				url:"{{url('/presence/report/getData')}}",
				success: function(result){
					console.log(result)
					$(".box-title").text("This period " + result.range)
					var append = ""
					$.each(result.data,function(index,data){
						append = append + "<tr>"
						append = append + "	<td>" + data.name + "</td>"
						append = append + "	<td>" + data.schedule + "</td>"
						append = append + "	<td>" + data.checkin + "</td>"
						append = append + "	<td>" + data.checkout + "</td>"
						if(data.condition == "Late"){
							append = append + "	<td style='text-align:center'><span class='label label-danger'>" + data.condition + "</span></td>"
						} else if (data.condition == "Uncheckout"){
							append = append + "	<td style='text-align:center'><span class='label label-primary'>" + data.condition + "</span></td>"
						} else if (data.condition == "Absent"){
							append = append + "	<td style='text-align:center'><span class='label label-default'>" + data.condition + "</span></td>"
						} else {
							append = append + "	<td style='text-align:center'>" + data.condition + "</td>"
						}
						append = append + "	<td style='text-align:center'><input type='checkbox' class='validationCheck' value='" + data.nik + "-" + data.checkin.slice(0,10) + "' checked></td>"
						append = append + "	<td><input type='text' class='form-control' disabled=''></td>"
						append = append + "</tr>"
					})

					$("#tableReport").append(append)
				}
			})
		})

		$(document).on('change', '.validationCheck', function() {
		    if(!$(this).is(':checked')) {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',false)
		    } else {
		    	$(this).closest('tr').find("input[type=text]").prop('disabled',true)
		    }
		});

	</script>
	<script src="https://adminlte.io/themes/AdminLTE/bower_components/chart.js/Chart.js"></script>
@endsection