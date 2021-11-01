@extends('template.main')

@section('tittle')
Presence Shifting
@endsection

@section('head_css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.print.css" media="print">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
	<style>
		.swal2-margin {
			margin: .3125em;
		}
		.loader {
			border: 16px solid #f3f3f3;
			border-radius: 50%;
			border-top: 16px solid #3498db;
			width: 120px;
			height: 120px;
			-webkit-animation: spin 2s linear infinite;
			animation: spin 2s linear infinite;
			margin: auto;
			position: absolute;
			top:0;
			bottom: 0;
			left: 0;
			right: 0;
		}

		.pagi, .Pagi {
			background-color: #dd4b39 !important;
			border-color: #dd4b39 !important;
			color: #fff !important;
		}

		.Helpdesk {
			background-color: #ca195a !important;
			border-color: #ca195a !important;
			color: #fff !important;
		}

		.sore, .Sore {
			background-color: #f39c12 !important;
			border-color: #f39c12 !important;
			color: #fff !important;
		}

		.malam, .Malam {
			background-color: #0073b7 !important;
			border-color: #0073b7 !important;
			color: #fff !important;
		}

		.libur, .Libur {
			background-color: #00a65a !important;
			border-color: #00a65a !important;
			color: #fff !important;
		}

		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		.cover {
			position: fixed;
			top: 0;
			left: 0;
			background: rgba(0,0,0,0.6);
			z-index: 5000;
			width: 100%;
			height: 100%;
			display: none;
		}

		.fc-time{
		   display : none;
		}

		td.fc-day.fc-past {
			background-color: #EEEEEE;
		}
		td.fc-day.fc-today {
			background-color: #ffeaa7;
		}

		.display-none{
			display: none;
		}

		.display-block{
			display: block;
		}

		.padding-10{
			padding: 10px;
		}

		/* The switch - the box around the slider */
		.switch {
			position: relative;
			display: inline-block;
			width: 40px;
			height: 22px;
		}

		/* Hide default HTML checkbox */
		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		/* The slider */
		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
		}

		.slider:before {
			position: absolute;
			content: "";
			height: 16px;
			width: 16px;
			left: 4px;
			bottom: 3px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
		}

		input:checked + .slider {
			background-color: #2196F3;
		}

		input:focus + .slider {
			box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
			-webkit-transform: translateX(16px);
			-ms-transform: translateX(16px);
			transform: translateX(16px);
		}

		/* Rounded sliders */
		.slider.round {
			border-radius: 34px;
		}

		.slider.round:before {
			border-radius: 50%;
		}
	</style>
@endsection

@section('content')
	<!-- Content Header bisa di isi dengan Title Menu dan breadcrumb -->
	<section class="content-header">
		<h1>
			Precense Shifting
		</h1>
		<a href="#" class="pull-right btn-box-tool text-red pull-left" data-toggle="modal" data-target="#modal-addusershifting" id="buttonAddUserShifting" style="display:none">
			<i class="fa fa-plus"></i> Modify User Shifting
		</a>
		<a href="#" class="pull-right btn-box-tool text-green pull-left" data-toggle="modal" data-target="#modal-settingOption" id="buttonEditShiftingOption" style="display:none">
			<i class="fa fa-plus"></i> Modify Shifting Option
		</a>					
		<ol class="breadcrumb">
			<li>
				<a href="{{url('dashboard')}}">
					<i class="fa fa-fw fa-dashboard"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="{{url('presence')}}">Presence</a>
			</li>
			<li class="active">
				<a href="{{url('presence/shifting')}}">Shifting</a>
			</li>
		</ol>
		<br id="newLineModify" style="display:none">
	</section>

	<section class="content">
		<div class="row">
			<section class="col-lg-3 col-xs-12" id="panel_simple">
				
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title" id="indicatorMonth">Shifting Users on {{date('F')}}</h3>
					</div>
					<div class="box-body no-padding" id="listProject">
						<ul class="nav nav-stacked" id="listProjectContent">
							<li>
								<a href="#" onclick="showLog()">Log Activity</a>
							</li>
						</ul>
					</div>
					<div class="box-body" id="listName" style="display: none;">
						<p id="name"></p>
						<ul class="nav nav-stacked" id="ulUser"></ul>
						<br>
						<button class="btn btn-default" id="buttonBack">Back</button>
					</div>

					<div class="box-body" id="external" style="display: none;">
						<p id="name"></p>
						<div id="external-events">
							<p id="name2"></p>
							<input type="hidden" id="nickname">
							<br id="external-event-br">
							<br>
							<button class="btn btn-default" id="buttonBack2">
								Back
							</button>
						</div>
					</div>
				</div>
				<div class="box box-danger box-solid" id="deletePlace" style="display: none;">
					<div class="box-header">
						<div class="box-title">
							Drop here to delete
						</div>
					</div>
				</div>
			</section>

			<section class="col-lg-9 col-xs-12" id="panel_simple2">
				
				<div class="box box-default">
					<div class="box-body no-padding">
						<div id="calendar"></div>
						<div id="log-activity" class="display-none table-responsive padding-10">
							<table id="table-log" class="table DataTable table-stripped">
								<thead>
									<tr>
										<th>No</th>
										<th>Activity</th>
										<th>Date/Time</th>
										<th>Changed by</th>
									</tr>
								</thead>
								<tbody id="log-content">
								</tbody>
							</table>
						</div>
					</div>
				</div>	
			</section>			
		</div>	
	</section>	

	<div class="modal fade" id="modal-addusershifting" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Modify User Shifting</h4>
				</div>
				<form method="POST" action="{{url('presence/shifting/modifyUserShifting')}}" enctype="multipart/form-data">
				<!-- <form method="POST" action="{{url('testaddUserShifting')}}" enctype="multipart/form-data"> -->
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Users Name</label>
									<select class="form-control select2" name="id_user" id="listUsers" style="width: 100%" >
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Project Name</label>
										<select class="form-control select2" name="on_project" id="listProjectForUser" style="width: 100%" >
										</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>						
						<button type="submit" class="btn btn-primary">Modify user</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-settingOption" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Modify Shifting Option</h4>
				</div>
				<div class="modal-body" id="modal-settingOption-body">
					@foreach($shiftingOptions as $shiftingOptionKey => $shiftingOptionValues)
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary collapsed-box">
								<div class="box-header with-border">
									<h3 class="box-title">{{$shiftingOptionKey}}</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" id="tambah">
											<i class="fa fa-gear"></i>
										</button>
										<button type="button" class="btn btn-box-tool" data-widget="collapse">
											<i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body" style="display: none;">
									<form class="form-horizontal">
										<div class="box-body">
											@foreach($shiftingOptionValues as $shiftingOptionValue)
											<div class="form-group">
												<label class="col-sm-3 control-label" style="text-align:center">
													<small class="label bg-{{$shiftingOptionValue['class_shifting']}}" style="font-size: 100%;">{{$shiftingOptionValue["name_option"]}}</small>
												</label>

												<div class="col-sm-3">
													<input type="text" class="form-control checkin-input-{{$shiftingOptionValue['id_project']}} option-{{$shiftingOptionValue['id']}}" value="{{$shiftingOptionValue['start_shifting']}}" placeholder="Start">
												</div>
												<div class="col-sm-3">
													<input type="text" class="form-control checkout-input-{{$shiftingOptionValue['id_project']}} option-{{$shiftingOptionValue['id']}}" value="{{$shiftingOptionValue['end_shifting']}}" placeholder="End">
												</div>

												<div class="col-sm-3 text-center">
													<label class="switch">
														<input class="featureItemCheck checkbox-{{$shiftingOptionValue['id_project']}} option-{{$shiftingOptionValue['id']}}" type="checkbox" {{ $shiftingOptionValue['status'] == 'ACTIVE' ? 'checked' : '' }}>
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											@endforeach
										</div>
										<div class="box-footer">
											<button type="button" class="btn btn-info pull-right" onclick="saveChangeShiftingOption({{$shiftingOptionValue['id_project']}})">Save Change</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scriptImport')
<!-- Script yang import dari CDN ato Local ada di sini -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@endsection

@section('script')
<script type="text/javascript">
	// Script yang import dari CDN ato Local ada di sini
	var globalIdUser = 0;
	var globalProject = 0;

	var globalIdUser = 0;
	var globalProject = 0;

	swalWithCustomClass = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-flat btn-primary swal2-margin',
			cancelButton: 'btn btn-flat btn-danger swal2-margin',
			denyButton: 'btn btn-flat btn-danger swal2-margin',
			popup: 'border-radius-0',
		},
		buttonsStyling: false,
	})
	
	$.ajax({
		url:"{{url('presence/shifting/getProject')}}",
		success: function(result){
			result.forEach( function ( data, key ) {
				var project =  "'" + data.project_name + "','" + data.id + "'"
				$("#listProjectForUser").prepend('<option value="' + data.id + '">' + data.project_name + '</option>')
				$("#listProjectContent").prepend('<li><a href="#" onclick="showProject(' + project + ')">' + data.project_name + '</a></li>')
			})
			$("#listProjectForUser").append('<option value="0">--Set For Not Shifting--</option>')
		}
	})

	$.ajax({
		url:"{{url('presence/shifting/getOption')}}",
		success: function(result){
			result.forEach( function ( data, key ) {
				$("#external-event-br").after('<div style="display: none; cursor: default;" class="external-event bg-' + data.class_shifting + ' project-' + data.id_project + '">' + data.name_option + ' <span class="pull-right">' + data.start_shifting + ' - ' + data.end_shifting + '</span></div>')
			})

			$('#external-events div.external-event').each(function () {
				var str = $(this).text();
				var shift = str.substr(0,str.indexOf(" "));
				var strip = str.indexOf("-");
				var start1 = strip - 6;
				var end1 = strip + 2;
				var start = str.substr(start1,5);
				var end = str.substr(end1,5);
				
				if(shift == "Libur"){
					var eventObject = {
						title: $.trim($(this).text()), 
						startShift: "00:00",
						endShift: "23:59",
						Shift: shift,
					};
				} else {
					var eventObject = {
						title: $.trim($(this).text()), 
						startShift: start,
						endShift: end,
						Shift: shift,
					};
				}

				$(this).data('eventObject', eventObject);

				$(this).draggable({
					zIndex: 1070,
					revert: true, 
					revertDuration: 0 
				});
			});

		}
	})

	$.ajax({
		url:"{{url('presence/shifting/getUsers')}}",
		success: function(result){
			// $("#listUsers").append('<option value="12341234124">sdafadsfa</option>')
			result.forEach( function ( data, key ) {
				$("#listUsers").append('<option value="' + data.nik + '">' + data.name + '</option>')
			})

			$("#listUsers").select2()
		}
	})

	$.ajax({
		url:"{{url('presence/shifting/getOptionGrouped')}}",
		success:function(result){
			for(var key in result){
				console.log(key)
				result[key].forEach( function ( data, key ) {

				})
			}
			console.log(result)
		}
	})

	function showProject(name,idProject){
		$("#listProject").fadeOut(function (){
			$("#listName").fadeIn();
			$("#name").text("for " + name);
			$("#calendar").removeClass('display-none').addClass('display-block');
			$("#log-activity").removeClass('display-block').addClass('display-none');
			$("#table-log").dataTable().fnDestroy();
			$("#calendar").fullCalendar('removeEventSources');
			$("#calendar").fullCalendar('addEventSource', "{{url('schedule/getThisProject')}}?project=" + idProject + "&month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			$("." + idProject).show();
			globalProject = idProject;
			$("#buttonBack").attr("onclick","backListProject(" + idProject + ")");
		});
	};

	var shift_user = [], shift_time = [], shift_date = [];
	var i = 0;
	$('#calendar').fullCalendar({
		header: {
			left: '',
			center: 'title',
		},
		
		editable: false,
		droppable: false,
		events: "{{url('presence/shifting/getThisMonth')}}",
			
		drop: function (date, allDay) { 

			var originalEventObject = $(this).data('eventObject');
			var name3 = $("#nickname").val();
			console.log(name3)
			var copiedEventObject = $.extend({}, originalEventObject);

			copiedEventObject.start = date;
			var waktu = date._d;
			waktu = new Date(waktu);

			console.log(originalEventObject)
			var day = moment(waktu).toISOString(true);
			var startShift2 = moment(waktu).format('YYYY-MM-DD') + "T" + originalEventObject.startShift + ":00.000Z";
			var endShift2 = moment(waktu).format('YYYY-MM-DD') + "T" + originalEventObject.endShift + ":00.000Z";
			var start_before = moment(waktu).format('YYYY-MM-DD') + " " + originalEventObject.startShift + ":00";
			var end_before = moment(waktu).format('YYYY-MM-DD') + " " + originalEventObject.endShift + ":00";
			
			var ketemu = 0;

			var date = $('#calendar').fullCalendar('getCalendar').view
			var start = date.start.format("YYYY-MM-DD")
			var end = date.end.format("YYYY-MM-DD")

			$.ajax({
				type:"GET",
				dataType:"json",
				url:"{{url('presence/shifting/getThisMonth')}}?start=" + start + "&end=" + end,
				success: function(result2){
					for (var i = 0; i < result2.length; i++) {
						if (startShift2 == result2[i].start) {
							var str = result2[i].title;
							var str2 = result2[i].start;
							var shift = str.substr(0,str.indexOf(" "));
							
							if(shift == originalEventObject.Shift){
								if(name3.substr(1,name3.length - 1) == str.substr(str.indexOf(" ") + 3, str.length)){
									ketemu = 1;
								}
							} 
						}
					};

					if(ketemu == 1){
						alert("tanggal sama");
					} else {
						var idEvent = 0;

						$.ajax({
							type: "GET",
							url: "{{url('presence/shifting/createSchedule')}}",
							data:{
								title: originalEventObject.Shift +" - " +  name3,
								name:name3,
								start: startShift2,
								end: endShift2,
								start_before:start_before,
								end_before:end_before,
								shift: originalEventObject.Shift,
								id_project: globalProject,
								nik:globalIdUser

							},
							success: function(result){
								idEvent = result;
								copiedEventObject.id = idEvent;
								refresh_calendar();
							},
						});
					}
				},
			});
		},

		eventDrop: function(event, delta, revertFunc) {
			alert(event.title + " can't move!");
			revertFunc();
		},

		eventDragStop: function(event,jsEvent) {
			var trashEl = $('#deletePlace');
			var ofs = trashEl.offset();

			var x1 = ofs.left;
			var x2 = ofs.left + trashEl.outerWidth(true);
			var y1 = ofs.top;
			var y2 = ofs.top + trashEl.outerHeight(true);

			if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
				jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
				if (confirm("Are you sure to delete this events?")) {
					$.ajax({
						type: "GET",
						url: "{{url('presence/shifting/deleteSchedule')}}",
						data:{
							id:event.id
						},
						success: function(result){
							$('#calendar').fullCalendar('removeEvents', event.id);
						},
					});
				}
			}
		},

		viewRender: function (view, element) {
			$("#indicatorMonth").text("Shifting Users on " + moment(view.intervalStart).format("MMMM"));
			
			$.ajax({
				type: "GET",
				// url: "{{url('schedule/changeMonth')}}",
				url: "{{url('presence/shifting/getSummaryThisMonth')}}",
				data: {
					start:moment(view.intervalStart).format("YYYY-MM")
				},
				beforeSend:function(){
					$("#calendar").fullCalendar('removeEventSources');
				},
				success: function(result){
					$("#ulUser").empty();
					var append = "";
					for (var i = 0; i < result.length; i++) {
						var showDetail = "showDetail('" + result[i].name + "','" + result[i].nickname + "','" +result[i].id + "','" + result[i].on_project + "')";
						append = append + '	<li class="' + result[i].on_project + '" style="display:none;padding-bottom:10px">';
						append = append + '		<a onclick="' + showDetail + '">' + result[i].name;
						append = append + '			<br>';
						append = append + '			<small class="label label-success pull-right" style="margin-right: 5px;">' + result[i].shift_libur + ' </small>';
						append = append + '			<small class="label label-primary pull-right" style="margin-right: 5px;">' + result[i].shift_malam + ' </small>';
						append = append + '			<small class="label label-warning pull-right" style="margin-right: 5px;">' + result[i].shift_sore + ' </small>';
						append = append + '			<small class="label label-danger pull-right" style="margin-right: 5px;">' + result[i].shift_pagi + ' </small>';
						append = append + '		</a>';
						append = append + '	</li>';
					};
					$("#ulUser").append(append);
					$("." + globalProject).show();
				},
			});
			var date = $('#calendar').fullCalendar('getCalendar').view
			var start = date.start.format("YYYY-MM-DD")
			var end = date.end.format("YYYY-MM-DD")
			if($("#listProject").is(":visible")){
				$("#calendar").fullCalendar('addEventSource', "{{url('presence/shifting/getThisMonth')}}?start=" + start + "&end=" + end);
			}
			if($("#listName").is(":visible")){
				$("#calendar").fullCalendar('addEventSource', '{{url("presence/shifting/getThisProject")}}?project=' + globalProject + '&month=' + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			} else {
				$("#calendar").fullCalendar('addEventSource', '{{url("presence/shifting/getThisUser")}}?idUser=' + globalIdUser +'&idProject=' + globalProject + "&month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			}
		}
	});

	function showProject(name,idProject){
		$("#listProject").fadeOut(function (){
			$("#listName").fadeIn();
			$("#name").text("for " + name);
			$("#calendar").removeClass('display-none').addClass('display-block');
			$("#log-activity").removeClass('display-block').addClass('display-none');
			$("#table-log").dataTable().fnDestroy();
			$("#calendar").fullCalendar('removeEventSources');
			$("#calendar").fullCalendar('addEventSource', "{{url('presence/shifting/getThisProject')}}?project=" + idProject + "&month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			$("." + idProject).show();
			globalProject = idProject;
			$("#buttonBack").attr("onclick","backListProject(" + idProject + ")");
		});
	};

	function backListProject(idProject){
		$("#listName").fadeOut(function (){
			$("#calendar").fullCalendar('removeEventSources');
			var date = $('#calendar').fullCalendar('getCalendar').view
			var start = date.start.format("YYYY-MM-DD")
			var end = date.end.format("YYYY-MM-DD")
			$("#calendar").fullCalendar('addEventSource', "{{url('presence/shifting/getThisMonth')}}?start=" + start + "&end=" + end);
			$("." + idProject).hide();
			$("#listProject").fadeIn();
		});
	}

	function showDetail(name,nickname,idUser,idProject){
		$("#listName").fadeOut(function (){
			
			var external2 = ".project-" + idProject;
			$("#external").fadeIn(function(){
				$(external2).show();
			});

			$("#name2").text("for " + name);
			$("#nickname").val(nickname);
			$("#calendar").fullCalendar('removeEventSources');
			$("#calendar").fullCalendar('addEventSource', '{{url("presence/shifting/getThisUser")}}?idUser=' + idUser + '&idProject=' + globalProject + "&month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			globalIdUser = idUser;
			$("." + idProject).show();
			$("#buttonBack2").attr("onclick","backListDetail(" + idProject + ")")
			$("#deletePlace").show();
			$("#calendar").fullCalendar('option', {
				editable: true,
				droppable: true,
			});
		});
	}

	function backListDetail(idProject){
		$("#external").fadeOut(function (){
			$(".project-" + idProject).fadeOut();
			$("#calendar").fullCalendar('removeEventSources');
			$("#calendar").fullCalendar('addEventSource', "{{url('presence/shifting/getThisProject')}}?month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
			$("#buttonBack").attr("onclick","backListProject(" + idProject + ")");
			globalIdUser = 0;
			$("#listName").fadeIn();
			$("#deletePlace").hide();
			$("#calendar").fullCalendar('option', {
				editable: false,
				droppable: false,
			});
		});
	}

	function refresh_calendar(){
		$("#calendar").fullCalendar('removeEventSources');
		$("#calendar").fullCalendar('addEventSource', '{{url("presence/shifting/getThisUser")}}?idUser=' + globalIdUser +'&idProject=' + globalProject + "&month=" + moment($("#indicatorMonth").text().split(" ")[3],"MMMM").format('MM'));
	}

	function saveChangeShiftingOption(id_project){

		var optionId = []
		var checkInValue = []
		var checkOutValue = []
		var optionStatus = []

		$(".checkin-input-" + id_project).each(function(index){
			optionId.push($(this).attr('class').split(" option-")[1])
			checkInValue.push($(this).val())
		})
		$(".checkout-input-" + id_project).each(function(index){
			checkOutValue.push($(this).val())
		})

		$(".checkbox-" + id_project).each(function(index){
			console.log($(this).is(":checked"))
			if($(this).is(":checked")){
				optionStatus.push("ACTIVE")
			} else {
				optionStatus.push("NON-ACTIVE")
			}
		})

		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "To Save this shifting option change?",
			icon: 'warning',
			showCancelButton: true,
		}).then((result) => {
			$.ajax({
				type:"GET",
				url:"{{url('/presence/shifting/modifyOptionShifting')}}",
				data:{
					option_id:optionId,
					checkin_value:checkInValue,
					checkout_value:checkOutValue,
					status_value:optionStatus,
				},
				beforeSend: function(){
					Swal.fire({
						title: 'Please Wait..!',
						text: "It's sending..",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						didOpen: () => {
							Swal.showLoading()
						}
					})
				},
				success: function(result){
					Swal.hideLoading()
					swalWithCustomClass.fire({
						title: 'Success!',
						text: "Shifting option changed",
						icon: 'success',
						confirmButtonText: 'Reload',
					}).then((result) => {
						location.reload()
					})	
				}
			})
		})
	}
</script>
@endsection