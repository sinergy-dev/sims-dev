@extends('template.template_admin-lte')
@section('content')	
<section class="content-header">
	<h1>
		Personal Presence History
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{url('presence')}}">
				<i class="fa fa-clock-o"></i>Presence
			</a>
		</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-4 col-xs-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">My Attendance Summary</h3>			
				</div>
				<div class="box-body">
					<canvas id="pieChart" style="height:300px"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-8 col-xs-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">My Attendance Detail</h3>			
				</div>

				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Date</th>
								<th>Schedule</th>
								<th>Checkin</th>
								<th>Checkout</th>
								<th>Condition</th>
							</tr>
						</thead>
						<tbody>
							@foreach($presenceHistoryDetail as $presence)
							<tr>
								<td>{{$presence->date}}</td>
								<td>{{$presence->schedule}}</td>
								<td>{{$presence->checkin}}</td>
								@if($presence->checkin == $presence->checkout)
								<td>-</td>
								@else
								<td>{{$presence->checkout}}</td>
								@endif
								<td class="text-center">
									@if($presence->condition == "On-Time")
										<span class="label label-success">{{$presence->condition}}</span>
									@elseif($presence->condition == "Injury-Time")
										<span class="label label-warning">{{$presence->condition}}</span>
									@else
										<span class="label label-danger">{{$presence->condition}}</span>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('script')
<script type="text/javascript">
	var config = {
		type: 'doughnut',
		data: {
			labels: @json($presenceHistoryCounted->keys()),
			datasets: [{
				data: @json($presenceHistoryCounted->pluck('count')),
				backgroundColor: @json($presenceHistoryCounted->pluck('color'))
			}]
		},
		options: {
			responsive: true,
			legend: {
				position:'bottom',
				display: true,
				labels: {
					generateLabels: function(chart) {
						var data = chart.data;
						if (data.labels.length && data.datasets.length) {
							return data.labels.map(function(label, i) {
								var meta = chart.getDatasetMeta(0);
								var ds = data.datasets[0];
								var arc = meta.data[i];
								var custom = arc && arc.custom || {};
								var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
								var arcOpts = chart.options.elements.arc;
								var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
								var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
								var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

								// We get the value of the current label
								var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

								return {
									// Instead of `text: label,`
									// We add the value to the string
									text: label + " : " + value,
									fillStyle: fill,
									strokeStyle: stroke,
									lineWidth: bw,
									hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
									index: i
								};
							});
						} else {
							return [];
						}
					}
				}
			}
		},
		centerText: {
			display: true,
			text: ""
		}
	};

	var ctx = document.getElementById("pieChart").getContext("2d");
	window.myDoughnut = new Chart(ctx, config);
	console.log(@json($presenceHistoryCounted->values()))
</script>
@endsection