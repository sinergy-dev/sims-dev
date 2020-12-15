@extends('template.template_admin-lte')
@section('content')	
		<!-- Content Header (Page header) -->
		<section class="content-header" >
			<a href="{{url('absen')}}">
				<img src="img/labelaogy.png" width="120" height="40">
			</a>
			<ol class="breadcrumb" style="font-size: 15px;">
				<li><a href="{{url('ehistory')}}"><i class="fa fa-book"></i>My Attendance</a></li>
				<li><a href="{{url('eteamhistory')}}"><i class="fa fa-users"></i>My Team Attendance</a></li>
			</ol>
		</section>
		<section class="content">

			<!-- Simple box -->
			<div class="box" id="panel_simple">
				<div class="box-header with-border">
					<h3 class="box-title">My Attendance	on {{date('F')}}</h3>			
				</div>

				<div class="col-md-4">
					<div class="box box-solid">

						<!-- /.box-header -->

						<div class="box-body text-center">




						</div>
						
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<!-- /.col -->
								<div class="col-md-4">
									<ul class="chart-legend clearfix">
										<li><i class="fa fa-circle-o text-green"></i> Ontime</li>
										<li><i class="fa fa-circle-o text-yellow"></i> Injury</li>
										<li><i class="fa fa-circle-o text-red"></i> Late</li>
										<li><i class="fa fa-circle-o text-blue"></i> Absent</li>


									</ul>

									<!-- /.col -->
								</div>
								<!-- /.row -->
							</div>
							<!-- /.box-body -->
							<!-- /.footer -->
						</div>

						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>



				<div class="box-body col-md-8">

					<table class="table table-bordered">
						<tbody>
							<tr>
								<th>My Schedule</th>
								<th>My Present Time</th>
								<th>Date</th>
								<th>Where</th>
								<th style="width: 40px">Status</th>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<a href="{{url('downloadPDF',Auth::user()->id)}}">
						<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
							<i class="fa fa-download"></i> Download Report
						</button>
					</a>
					<p class="text-center">For more other history information. Click <b id="detail" style="cursor:pointer">here</b></p>
					<br>




				</div>

				<!-- /.box-footer-->
			</div>

			<!-- Detail box -->
			<div class="box" id="panel_detail" style="display: none;">
				<div class="box-header with-border">
					<h3 class="box-title">My Attendance	</h3>			
				</div>
				<div class="box-body">

					<table class="table table-bordered" id="detail_table">
						<thead>
							<tr>
								<th>My Schedule</th>
								<th>Time</th>
								<th>Date</th>
								<th>Where</th>
								<th style="width: 40px">Status</th>
							</tr>
						</thead>	
					</table>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<p class="pull-right">For more other history information. Click <b id="simple" style="cursor:pointer">here</b></p>
				</div>
				<!-- /.box-footer-->
			</div>
			<!-- /.box -->

		</section>
		<!-- /.tab-content -->

</div>
</section>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		console.log('asdfasdf');

		// Click to Detail
		$("#detail").click(function () {
			console.log('asdfasdf');
			$("#panel_simple").fadeOut(function () {
				$("#panel_detail").fadeIn();
			});
		});

		//Click to Simple
		$("#simple").click(function () {
			console.log('asdfasdf');
			$("#panel_detail").fadeOut(function () {
				$("#panel_simple").fadeIn();
			});
		});
	});

	//Init for DataTable
	$('#detail_table').dataTable();
</script>

@endsection