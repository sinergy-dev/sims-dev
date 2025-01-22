@extends('template.main')
@section('tittle')
SBE Dashboard
@endsection
@section('head_css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')
	<section class="content-header">
        <h1>
            SBE Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SBE Dashboard</li>
        </ol>
    </section>

    <section class="content">
    	<div class="row" style="margin-bottom:20px">
    		<div class="col-md-6 pull-right">
    			<div class="row">
    				<div class="col-md-6">
    					<span class="pull-right" style="margin-top: 5px;">
		    				<b>Filter Year</b>
		    			</span>
	    			</div>
	    			<div class="col-md-6">
	    				<div class="input-group">
		    				<div class="input-group-addon">
		    					<i class="fa fa-calendar"></i>
		    				</div>
		    				<select id="filterYear" name="filterYear" class="form-control">
							    <option value="">Select a Year</option>
							    @foreach($year as $item)
							        <option value="{{ $item->year }}">
							            {{ $item->year }}
							        </option>
							    @endforeach
							</select>
		    			</div>
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-4">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">Total SBE by Status</h3>
	    			</div>
	    			<div class="box-body">
                        <canvas id="totalSbeByStatus" width="100%" height="100%"></canvas>
	    			</div>
	    		</div>
	    	</div>	
	    	<div class="col-md-4">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">Total Nominal SBE by Status</h3>
	    			</div>
	    			<div class="box-body">
                        <canvas id="sumSbeByStatus" width="100%" height="100%"></canvas>
	    			</div>
	    		</div>
	    	</div>	
	    	<div class="col-md-4">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">Total SBE by Type Project</h3>
	    			</div>
	    			<div class="box-body">
                        <canvas id="totalSbeByType" width="100%" height="100%"></canvas>
	    			</div>
	    		</div>
	    	</div>	
    	</div>

    	<div class="row">
    		<div class="col-md-6">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">TOP 5 SBE Project Supply Only</h3>
	    			</div>
	    			<div class="box-body" style="min-height:375px">
	    				<table id="tb_project_supply_only" class="table" width="100%">
	    					
	    				</table>
	    			</div>
	    		</div>
	    	</div>	
    		
	    	<div class="col-md-6">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">TOP 5 SBE Project Implementation</h3>
	    			</div>
	    			<div class="box-body" style="min-height:375px">
	    				<table id="tb_project_implementation" class="table" width="100%">
	    					
	    				</table>
	    			</div>
	    		</div>
	    	</div>	
    	</div>

    	<div class="row">
    		<div class="col-md-6">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">TOP 5 SBE Project Maintenance <i class="fa fa-"></i></h3>
	    			</div>
	    			<div class="box-body" style="min-height:375px">
	    				<table id="tb_project_maintenance" class="table" width="100%">
	    					
	    				</table>
	    			</div>
	    		</div>
	    	</div>	
    		<div class="col-md-6">
	    		<div class="box">
	    			<div class="box-header">
	    				<h3 class="box-title">TOP 5 SBE Project Implementation + Maintenance</h3>
	    			</div>
	    			<div class="box-body" style="min-height:375px">
	    				<table id="tb_project_implementation_maintenance" class="table" width="100%">
	    					
	    				</table>
	    			</div>
	    		</div>
	    	</div>	
	    	
    	</div>
    </section>
@endsection
@section('scriptImport')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
@endsection
@section('script')
	<script type="text/javascript">
		var formatter = new Intl.NumberFormat(['ban', 'id']);
		var currentYear = new Date().getFullYear();
    	let initChartTotalSbeByStatus = null, initChartSumSbeByStatus = null, initChartTotalSbeByType = null

		initDashboard(currentYear)

		$("#filterYear").select2().val(currentYear).trigger('change')
		$("#filterYear").on('change', function() {
			initDashboard(this.value)
		})

		function initDashboard(year) {
			$.ajax({
			"type":"GET",
			"url":"{{url('/sbe/getDataChartSbe')}}",
			"data":{
				year:year
			},success:function (result) {
				$("#tb_project_supply_only").empty("")
				//supply only
				appendSupplyOnly = ''
				appendSupplyOnly = appendSupplyOnly + '<thead>'
					appendSupplyOnly = appendSupplyOnly + '<tr>'
						appendSupplyOnly = appendSupplyOnly + '<th>No</th>'
						appendSupplyOnly = appendSupplyOnly + '<th>Created By</th>'
						appendSupplyOnly = appendSupplyOnly + '<th>Project Name</th>'
						appendSupplyOnly = appendSupplyOnly + '<th>Nominal</th>'
					appendSupplyOnly = appendSupplyOnly + '<tr>'
				appendSupplyOnly = appendSupplyOnly + '<tbody>'
					if (result.top5SbeByStatus.length == 0) {
						appendSupplyOnly = appendSupplyOnly + '<tr>'
							appendSupplyOnly = appendSupplyOnly + '<td colspan="4">'
								appendSupplyOnly = appendSupplyOnly + '<span style="display:flex;justify-content:center;vertical-align:center">'
									appendSupplyOnly = appendSupplyOnly + 'No Data..'
								appendSupplyOnly = appendSupplyOnly + '</span>'
							appendSupplyOnly = appendSupplyOnly + '<td>'
						appendSupplyOnly = appendSupplyOnly + '</tr>'
					}else{
						$.each(result.top5SbeByStatus,function(key,value){
							if (value.project_type == 'Supply Only') {
								if (value.top_nominals.length == 0) {
									appendSupplyOnly = appendSupplyOnly + '<tr>'
										appendSupplyOnly = appendSupplyOnly + '<td colspan="4">'
											appendSupplyOnly = appendSupplyOnly + '<span style="display:flex;justify-content:center;vertical-align:center">'
												appendSupplyOnly = appendSupplyOnly + 'No Data..'
											appendSupplyOnly = appendSupplyOnly + '</span>'
										appendSupplyOnly = appendSupplyOnly + '<td>'
									appendSupplyOnly = appendSupplyOnly + '</tr>'
								}else{
									$.each(value.top_nominals,function(key,values){
										appendSupplyOnly = appendSupplyOnly + '<tr>'
											appendSupplyOnly = appendSupplyOnly + '<td>'+ ++key  +'</td>'
											appendSupplyOnly = appendSupplyOnly + '<td>'+ values.name +'</td>'
											appendSupplyOnly = appendSupplyOnly + '<td style="text-align:justify">'+ values.opp_name +'</td>'
											appendSupplyOnly = appendSupplyOnly + '<td>'+ formatter.format(values.nominal) +'</td>'
										appendSupplyOnly = appendSupplyOnly + '</tr>'
									})
								}
							}
						})
					}
				appendSupplyOnly = appendSupplyOnly + '</tbody>'

				$("#tb_project_supply_only").append(appendSupplyOnly)

				$("#tb_project_implementation").empty("")
				//implementation
				appendImplementation = ''
				appendImplementation = appendImplementation + '<thead>'
					appendImplementation = appendImplementation + '<tr>'
						appendImplementation = appendImplementation + '<th>No</th>'
						appendImplementation = appendImplementation + '<th>Created By</th>'
						appendImplementation = appendImplementation + '<th>Project Name</th>'
						appendImplementation = appendImplementation + '<th>Nominal</th>'
					appendImplementation = appendImplementation + '<tr>'
				appendImplementation = appendImplementation + '</thead>'
				appendImplementation = appendImplementation + '<tbody>'
					if (result.top5SbeByStatus.length == 0) {
						appendImplementation = appendImplementation + '<tr>'
							appendImplementation = appendImplementation + '<td colspan="4">'
								appendImplementation = appendImplementation + '<span style="display:flex;justify-content:center;vertical-align:center">'
									appendImplementation = appendImplementation + 'No Data..'
								appendImplementation = appendImplementation + '</span>'
							appendImplementation = appendImplementation + '<td>'
						appendImplementation = appendImplementation + '</tr>'
					}else{
						$.each(result.top5SbeByStatus,function(key,value){
							if (value.project_type == 'Implementation') {
								if (value.top_nominals.length == 0) {
									appendImplementation = appendImplementation + '<tr>'
										appendImplementation = appendImplementation + '<td colspan="4">'
											appendImplementation = appendImplementation + '<span style="display:flex;justify-content:center;vertical-align:center">'
												appendImplementation = appendImplementation + 'No Data..'
											appendImplementation = appendImplementation + '</span>'
										appendImplementation = appendImplementation + '<td>'
									appendImplementation = appendImplementation + '</tr>'
								}else{
									$.each(value.top_nominals,function(key,values){
										appendImplementation = appendImplementation + '<tr>'
											appendImplementation = appendImplementation + '<td>'+ ++key  +'</td>'
											appendImplementation = appendImplementation + '<td>'+ values.name +'</td>'
											appendImplementation = appendImplementation + '<td style="text-align:justify">'+ values.opp_name +'</td>'
											appendImplementation = appendImplementation + '<td>'+ formatter.format(values.nominal) +'</td>'
										appendImplementation = appendImplementation + '</tr>'
									})
								}
							}
						})
					}
				appendImplementation = appendImplementation + '</tbody>'

				$("#tb_project_implementation").append(appendImplementation)

				$("#tb_project_maintenance").empty('')
				appendMaintenance = ''

				appendMaintenance = appendMaintenance + '<thead>'
					appendMaintenance = appendMaintenance + '<tr>'
						appendMaintenance = appendMaintenance + '<th>No</th>'
						appendMaintenance = appendMaintenance + '<th>Created By</th>'
						appendMaintenance = appendMaintenance + '<th>Project Name</th>'
						appendMaintenance = appendMaintenance + '<th>Nominal</th>'
					appendMaintenance = appendMaintenance + '<tr>'
				appendMaintenance = appendMaintenance + '</thead>'
				appendMaintenance = appendMaintenance + '<tbody>'
					if (result.top5SbeByStatus.length == 0) {
						appendMaintenance = appendMaintenance + '<tr>'
							appendMaintenance = appendMaintenance + '<td colspan="4">'
								appendMaintenance = appendMaintenance + '<span style="display:flex;justify-content:center;vertical-align:center">'
									appendMaintenance = appendMaintenance + 'No Data..'
								appendMaintenance = appendMaintenance + '</span>'
							appendMaintenance = appendMaintenance + '<td>'
						appendMaintenance = appendMaintenance + '</tr>'
					}else{
						$.each(result.top5SbeByStatus,function(key,value){
							if (value.project_type == 'Maintenance') {
								if (value.top_nominals.length == 0) {
									appendMaintenance = appendMaintenance + '<tr>'
										appendMaintenance = appendMaintenance + '<td colspan="4">'
											appendMaintenance = appendMaintenance + '<span style="display:flex;justify-content:center;vertical-align:center">'
												appendMaintenance = appendMaintenance + 'No Data..'
											appendMaintenance = appendMaintenance + '</span>'
										appendMaintenance = appendMaintenance + '<td>'
									appendMaintenance = appendMaintenance + '</tr>'
								}else{
									$.each(value.top_nominals,function(key,values){
										appendMaintenance = appendMaintenance + '<tr>'
											appendMaintenance = appendMaintenance + '<td>'+ ++key +'</td>'
											appendMaintenance = appendMaintenance + '<td>'+ values.name +'</td>'
											appendMaintenance = appendMaintenance + '<td>'+ values.opp_name +'</td>'
											appendMaintenance = appendMaintenance + '<td style="text-align:justify">'+ formatter.format(values.nominal) +'</td>'
										appendMaintenance = appendMaintenance + '</tr>'
									})
								}
							}
						})
					}
				appendMaintenance = appendMaintenance + '</tbody>'		

				$("#tb_project_maintenance").append(appendMaintenance)

				$("#tb_project_implementation_maintenance").empty("")
				//implementation + maintenance
				appendImplementationMain = ''
				appendImplementationMain = appendImplementationMain + '<thead>'
					appendImplementationMain = appendImplementationMain + '<tr>'
						appendImplementationMain = appendImplementationMain + '<th>No</th>'
						appendImplementationMain = appendImplementationMain + '<th>Created By</th>'
						appendImplementationMain = appendImplementationMain + '<th>Project Name</th>'
						appendImplementationMain = appendImplementationMain + '<th>Nominal</th>'
					appendImplementationMain = appendImplementationMain + '<tr>'
				appendImplementationMain = appendImplementationMain + '</thead>'
				appendImplementationMain = appendImplementationMain + '<tbody>'
					if (result.top5SbeByStatus.length == 0) {
						appendImplementationMain = appendImplementationMain + '<tr>'
							appendImplementationMain = appendImplementationMain + '<td colspan="4">'
								appendImplementationMain = appendImplementationMain + '<span style="display:flex;justify-content:center;vertical-align:center">'
									appendImplementationMain = appendImplementationMain + 'No Data..'
								appendImplementationMain = appendImplementationMain + '</span>'
							appendImplementationMain = appendImplementationMain + '<td>'
						appendImplementationMain = appendImplementationMain + '</tr>'
					}else{
						$.each(result.top5SbeByStatus,function(key,value){
							if (value.project_type == 'Implementation + Maintenance') {
								if (value.top_nominals.length == 0) {
									appendImplementationMain = appendImplementationMain + '<tr>'
										appendImplementationMain = appendImplementationMain + '<td colspan="4">'
											appendImplementationMain = appendImplementationMain + '<span style="display:flex;justify-content:center;vertical-align:center">'
												appendImplementationMain = appendImplementationMain + 'No Data..'
											appendImplementationMain = appendImplementationMain + '</span>'
										appendImplementationMain = appendImplementationMain + '<td>'
									appendImplementationMain = appendImplementationMain + '</tr>'
								}else{
									$.each(value.top_nominals,function(key,values){
										appendImplementationMain = appendImplementationMain + '<tr>'
											appendImplementationMain = appendImplementationMain + '<td>'+ ++key  +'</td>'
											appendImplementationMain = appendImplementationMain + '<td>'+ values.name +'</td>'
											appendImplementationMain = appendImplementationMain + '<td style="text-align:justify">'+ values.opp_name +'</td>'
											appendImplementationMain = appendImplementationMain + '<td>'+ formatter.format(values.nominal) +'</td>'
										appendImplementationMain = appendImplementationMain + '</tr>'
									})
								}
							}
						})
					}
				appendImplementationMain = appendImplementationMain + '</tbody>'

				$("#tb_project_implementation_maintenance").append(appendImplementationMain)

				const labelTotalSbeByStatus = [];
				const dataTotalSbeByStatus = [];
				let grand_total_by_status = 0;

				if (result.totalSbeByStatus.length == 0) {
					$("#totalSbeByStatus").before("<span style='display:flex;justify-content:center;vertical-align:center'>No Data..</span>")
			        $("#totalSbeByStatus").next().remove()
				}else{
					$("#totalSbeByStatus").prev().remove()
					result.totalSbeByStatus.forEach((item, index) => {
					    labelTotalSbeByStatus.push(item.status); // Add the status to labels
					    dataTotalSbeByStatus.push(item.total_status); // Add the sum_nominal to data
					    grand_total_by_status += item.total_status
					});

			        $("#totalSbeByStatus").next().remove()
			        $("#totalSbeByStatus").after("<div style='display:flex;justify-content:center'> <div style='width:50px;height:16px;background-color:grey;'></div> <span style='font-size:14px;color:grey'> &nbsp&nbspGrand Total : "+ grand_total_by_status +"</span></div>")
				}

				if (initChartTotalSbeByStatus) {
		            initChartTotalSbeByStatus.destroy()        
		        }

				var ctx = document.getElementById('totalSbeByStatus').getContext('2d');
		        var chartTotalSbeByStatus = new Chart(ctx, {
		            type: 'pie', // Define the chart type as a Pie chart
		            data: {
		                labels: labelTotalSbeByStatus, // Labels for the slices
		                datasets: [{
		                    label: 'Total Sbe By Status',
		                    data: dataTotalSbeByStatus, // Values for each slice in the first dataset
		                    backgroundColor: ['#5b9bd5', '#ed7d31'], // Slice colors
		                    borderColor: '#fff',
		                    borderWidth: 1
		                }]
		            },
		            options: {
		                responsive: true, // Make the chart responsive to screen size
		                plugins: {
		                    legend: {
		                      position:'bottom',
							  display: true,
							  labels: {
							    generateLabels: function (chart) {
							      const data = chart.data;
							      const arcOpts = chart.options.elements.arc;

							      return data.labels.map((label, i) => {
							        const meta = chart.getDatasetMeta(0);
							        const arc = meta.data[i];
							        const dataset = data.datasets[0];

							        // Use dataset values or defaults
							        const backgroundColor = dataset.backgroundColor[i] || arcOpts.backgroundColor;
							        const hidden = arc.hidden;

							        return {
							          text: `${label} : ${parseFloat(dataset.data[i])}`,
							          fillStyle: backgroundColor,
							          hidden: hidden,
							          index: i,
							        };
							      });
							    },
							  },
							},
		                    tooltip: {
		                        enabled: true, // Enable tooltips
		                    },
		                    datalabels: {
					            formatter: (value, ctx) => {
					              const dataset = ctx.chart.data.datasets[0];
					              const total = dataset.data.reduce((sum, val) => sum + val, 0);
					              const percentage = ((value / total) * 100).toFixed(1);
					           	  return `${percentage}% (${value})`; // Percentage and value
					            },
					            color: '#fff',
					            font: {
					              size: 14,
					            },
					        }
		                }
		            },
		            plugins:[ChartDataLabels]
		    	});

        		initChartTotalSbeByStatus = chartTotalSbeByStatus    

		        const labelSumSbeByStatus = [];
				const dataSumSbeByStatus = [];
				let grand_sum_by_status = 0;

				if (result.sumSbeByStatus.length == 0) {
					$("#sumSbeByStatus").before("<span style='display:flex;justify-content:center;vertical-align:center'>No Data..</span>")
			        $("#sumSbeByStatus").next().remove()
				}else{
					$("#sumSbeByStatus").prev().remove()
					result.sumSbeByStatus.forEach((item, index) => {
					    labelSumSbeByStatus.push(item.status); // Add the status to labels
					    dataSumSbeByStatus.push(item.sum_nominal); // Add the sum_nominal to data
					    grand_sum_by_status += item.sum_nominal
					});

			        $("#sumSbeByStatus").next().remove()
			        $("#sumSbeByStatus").after("<div style='display:flex;justify-content:center'> <div style='width:50px;height:16px;background-color:grey;'></div> <span style='font-size:14px;color:grey'> &nbsp&nbspGrand Total : "+ formatter.format(grand_sum_by_status) +"</span></div>")
				}

				if (initChartSumSbeByStatus) {
		            initChartSumSbeByStatus.destroy()        
		        }

		        var ctx2 = document.getElementById('sumSbeByStatus').getContext('2d');
		        var chartSumSbeByStatus = new Chart(ctx2, {
		            type: 'pie', // Define the chart type as a Pie chart
		            data: {
		                labels: labelSumSbeByStatus, // Labels for the slices
		                datasets: [{
		                    label: 'Dataset 1',
		                    data: dataSumSbeByStatus, // Values for each slice in the first dataset
		                    backgroundColor: ['#5b9bd5', '#ed7d31'], // Slice colors
		                    borderColor: '#fff',
		                    borderWidth: 1
		                }]
		            },
		            options: {
		                responsive: true, // Make the chart responsive to screen size
		                plugins: {
		                    legend: {
		                      position:'bottom',
							  display: true,
							  labels: {
							    generateLabels: function (chart) {
							      const data = chart.data;
							      const arcOpts = chart.options.elements.arc;

							      return data.labels.map((label, i) => {
							        const meta = chart.getDatasetMeta(0);
							        const arc = meta.data[i];
							        const dataset = data.datasets[0];

							        // Use dataset values or defaults
							        const backgroundColor = dataset.backgroundColor[i] || arcOpts.backgroundColor;
							        const hidden = arc.hidden;

							        return {
							          text: `${label} : ${formatter.format(parseFloat(dataset.data[i]))}`,
							          fillStyle: backgroundColor,
							          hidden: hidden,
							          index: i,
							        };
							      });
							    },
							  },
							},
		                    tooltip: {
		                        enabled: true, // Enable tooltips
		                    },
		                    datalabels: {
					            formatter: (value, ctx) => {
					              const dataset = ctx.chart.data.datasets[0];
					              const total = dataset.data.reduce((sum, val) => sum + val, 0);
					              const percentage = ((value / total) * 100).toFixed(1);
					              return `${formatter.format(value)}`; // Percentage and value
					            },
					            color: '#fff',
					            font: {
					              size: 14,
					            },
					        },
		                }
		            },
		            plugins:[ChartDataLabels]
		        });

        		initChartSumSbeByStatus = chartSumSbeByStatus    

		        const labelTotalSbeByType = [];
				const dataTotalSbeByType = [];
				let grand_total_by_type = 0;

				if (result.totalSbeByType.length == 0) {
					$("#totalSbeByType").before("<span style='display:flex;justify-content:center;vertical-align:center'>No Data..</span>")
			        $("#totalSbeByType").next().remove()
				}else{
					$("#totalSbeByType").prev().remove()
					result.totalSbeByType.forEach((item, index) => {
					    labelTotalSbeByType.push(item.project_type); // Add the status to labels
					    dataTotalSbeByType.push(item.count); // Add the sum_nominal to data
					    grand_total_by_type += item.count
					});

			        $("#totalSbeByType").next().remove()
			        $("#totalSbeByType").after("<div style='display:flex;justify-content:center'> <div style='width:50px;height:16px;background-color:grey;'></div> <span style='font-size:14px;color:grey'> &nbsp&nbspGrand Total : "+ grand_total_by_type +"</span></div>")
				}

				if (initChartTotalSbeByType) {
		            initChartTotalSbeByType.destroy()        
		        }

		        var ctx3 = document.getElementById('totalSbeByType').getContext('2d');
		        var chartTotalSbeByStatus = new Chart(ctx3, {
		            type: 'pie', // Define the chart type as a Pie chart
		            data: {
		                labels: labelTotalSbeByType, // Labels for the slices
		                datasets: [{
		                    label: 'Dataset 1',
		                    data: dataTotalSbeByType,
		                    backgroundColor: ['#ffd700','#ff4100','#0097ff','#ed7d31'], // Slice colors
		                    borderColor: '#fff',
		                    borderWidth: 1
		                }]
		            },
		            options: {
		                responsive: true, // Make the chart responsive to screen size
		                plugins: {
		                    legend: {
		                      position:'bottom',
							  display: true,
							  labels: {
							    generateLabels: function (chart) {
							      const data = chart.data;
							      const arcOpts = chart.options.elements.arc;

							      return data.labels.map((label, i) => {
							        const meta = chart.getDatasetMeta(0);
							        const arc = meta.data[i];
							        const dataset = data.datasets[0];

							        // Use dataset values or defaults
							        const backgroundColor = dataset.backgroundColor[i] || arcOpts.backgroundColor;
							        const hidden = arc.hidden;

							        return {
							          text: `${label} : ${parseFloat(dataset.data[i])}`,
							          fillStyle: backgroundColor,
							          hidden: hidden,
							          index: i,
							        };
							      });
							    },
							  },
							},
		                    tooltip: {
		                        enabled: true, // Enable tooltips
		                    },
		                    datalabels: {
					            formatter: (value, ctx) => {
					              const dataset = ctx.chart.data.datasets[0];
					              const total = dataset.data.reduce((sum, val) => sum + val, 0);
					              const percentage = ((value / total) * 100).toFixed(1);
					              return `${percentage}% (${value})`; // Percentage and value
					            },
					            color: '#fff',
					            font: {
					              size: 14,
					            },
					        },
		                }
		            },
		            plugins:[ChartDataLabels]
		        });

        		initChartTotalSbeByType = chartTotalSbeByStatus    

				}
			})
		}
		
	</script>
@endsection
