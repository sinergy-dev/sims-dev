@extends('template.main')
@section('tittle')
Ticketing
@endsection

@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">

<link rel="stylesheet" href="{{ url('css/jquery.emailinput.min.css') }}">
<link rel="stylesheet" href="{{ url('css/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{ url('css/dataTables.bootstrap.css')}}">

<style type="text/css">
	.table2 > tbody > tr > th, .table2 > tbody > tr > td {
		border-color: #141414;border: 1px solid;padding: 3px;}

	.vertical-alignment-helper {
		display:table;
		height: 100%;
		width: 100%;
		pointer-events:none;
	}
	.vertical-align-center {
		display: table-cell;
		vertical-align: middle;
		pointer-events:none;
	}
	.modal-content {
		width:inherit;
		max-width:inherit; 
		height:inherit;
		margin: 0 auto;
		pointer-events: all;
	}

	.table > tbody > tr > td {
		vertical-align: middle;
	}

	.dataTables_filter {display: none;}
	.border-radius-0 {
		border-radius: 0px !important;
	}
	.swal2-margin {
		margin: .3125em;
	}

	.label {
		border-radius: 0px !important;
	}

	.has-error .select2-selection {
		border-color: rgb(185, 74, 72) !important;
	}
	
	body { padding-right: 0 !important }
	.button-edit-periperal {
	 	/*display: none;*/
	 }
	.itemPeriperal:hover {
		background-color: #eaeaea;
		cursor: pointer;
		display: block;
	}
	.severityCounter:hover {
		cursor: pointer;
	} 

	.container {
	  display: block;
	  position: relative;
	  padding-left: 35px;
	  margin-bottom: 12px;
	  cursor: pointer;
	  font-size: 14px;
	  -webkit-user-select: none;
	  -moz-user-select: none;
	  -ms-user-select: none;
	  user-select: none;
	}

	/* Hide the browser's default checkbox */
	.container input {
	  position: absolute;
	  opacity: 0;
	  cursor: pointer;
	  height: 0;
	  width: 0;
	}

	/* Create a custom checkbox */
	.checkmark {
	  position: absolute;
	  top: 0;
	  left: 0;
	  height: 25px;
	  width: 25px;
	  background-color: #eee;
	}

	/* On mouse-over, add a grey background color */
	.container:hover input ~ .checkmark {
	  background-color: #ccc;
	}

	/* When the checkbox is checked, add a blue background */
	.container input:checked ~ .checkmark {
	  background-color: #2196F3;
	}

	/* Create the checkmark/indicator (hidden when not checked) */
	.checkmark:after {
	  content: "";
	  position: absolute;
	  display: none;
	}

	/* Show the checkmark when checked */
	.container input:checked ~ .checkmark:after {
	  display: block;
	}

	/* Style the checkmark/indicator */
	.container .checkmark:after {
	  left: 9px;
	  top: 5px;
	  width: 5px;
	  height: 10px;
	  border: solid white;
	  border-width: 0 3px 3px 0;
	  -webkit-transform: rotate(45deg);
	  -ms-transform: rotate(45deg);
	  transform: rotate(45deg);
	}

	.bg-custom-yellow {
		background-color: #f1c40f;color: #fff !important;
	}

	/*.columnCheck {
		margin: 0 10px 0 5px;
	}*/
</style>
@endsection
@section('content')
	<section class="content-header">
		<h1>
			<img src="{{url('img/tisygy.png')}}" width="120" height="35">
			<small >Ticketing System Sinergy</small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="{{url('dashboard')}}">
					<i class="fa fa-fw fa-dashboard"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="#">Ticketing</a>
			</li>
			<li class="active">
				<a href="{{url('ticketing')}}">Ticketing</a>
			</li>
		</ol>
	</section>

	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active">
					<a href="#dashboard" data-toggle="tab" onclick="getDashboard()">Dashboard</a>
				</li>
				<li>
					<a href="#create" data-toggle="tab" onclick="makeNewTicket()">Create</a>
				</li>
				<li>
					<a href="#performance" data-toggle="tab" id="performanceTab" onclick="getPerformanceAll()">Performance</a>
				</li>
				<li>
					<a href="#setting" data-toggle="tab">Setting</a>
				</li>
				<li>
					<a href="#reporting" data-toggle="tab">Reporting</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="dashboard">
					<div class="row">
						<section class="col-md-6">
							<b>Occurring</b>
							<div class="row">
								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-red"><i class="fa fa-unlock-alt"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">OPEN</span>
											<span class="info-box-number" id="countOpen"></span>
										</div>
									</div>
								</div>
								
								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-aqua"><i class="fa fa-wrench"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">PROGRESS</span>
											<span class="info-box-number" id="countProgress"></span>
										</div>
									</div>
								</div>
								
								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">PENDING</span>
											<span class="info-box-number" id="countPending"></span>
										</div>
									</div>
								</div>
							</div>
							<b>Completed</b>
							<div class="row">
								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-purple"><i class="fa fa-times"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">CANCEL</span>
											<span class="info-box-number" id="countCancel"></span>
										</div>
									</div>
								</div>

								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-green"><i class="fa fa-check-square"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">CLOSE</span>
											<span class="info-box-number" id="countClose"></span>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-6">
									<div class="info-box">
										<span class="info-box-icon bg-navy"><i class="fa fa-archive"></i></span>

										<div class="info-box-content">
											<span class="info-box-text">ALL</span>
											<span class="info-box-number" id="countAll"></span>
										</div>
									</div>
								</div>
							</div>

							<b>Need Attention</b>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>ID</th>
												<th>ATM*</th>
												<th>Location</th>
												<th>Last Update</th>
												<th>Severity</th>
												<th>Operator</th>
											</tr>
										</thead>
										<tbody id="importanTable">
										</tbody>
									</table>
								</div>
							</div>
						</section>
						<section class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<div class="info-box">
										<div class="box-body">
											<canvas id="pieChart" style="height:250px"></canvas>
										</div>
									</div>
								</div>
							</div>
							<b>Severity</b>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="info-box bg-red">
										<span class="info-box-icon severityCounter" title="Show all Critical Ticket" onclick="getSeverity(1)"><i class="fa fa-caret-square-o-up"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Critical</span>
											<span class="info-box-number" id="countCritical"></span>

											<span class="progress-description" title="Critical impact to business operations">
												[< 3 hour] Critical impact to business operations
												
											</span>
										</div>
									</div>
								</div>
								
								<div class="col-md-6 col-sm-6">
									<div class="info-box bg-orange">
										<span class="info-box-icon severityCounter" title="Show all Major Ticket" onclick="getSeverity(2)"><i class="fa fa-caret-square-o-right"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Major</span>
											<span class="info-box-number" id="countMajor"></span>

											<span class="progress-description" title="Significant impact to business operations">
												[< 8 hour] Significant impact to business operations
											</span>
										</div>
									</div>
								</div>

								<div class="col-md-6 col-sm-6">
									<div class="info-box"style="background-color: #f1c40f;color: #fff !important;">
										<span class="info-box-icon severityCounter" title="Show all Moderate Ticket" onclick="getSeverity(3)"><i class="fa fa-caret-square-o-down"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Moderate</span>
											<span class="info-box-number" id="countModerate"></span>

											<span class="progress-description" title="Business operations noticeably impaired ">
												[1x24 hour] Business operations noticeably impaired 
											</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="info-box bg-green" >
										<span class="info-box-icon severityCounter" title="Show all Minor Ticket" onclick="getSeverity(4)"><i class="fa fa-caret-square-o-down"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Minor</span>
											<span class="info-box-number" id="countMinor"></span>

											<span class="progress-description" title="Installation, upgrade, or configuration assistance General product information ">
												[on preventive] Installation, upgrade, or configuration assistance General product information 
											</span>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>

				<div class="tab-pane" id="create">
					<i class="btn btn-flat btn-info" id="createIdTicket" onclick="reserveIdTicket()">Reserve ID Ticket</i>
					<div class="row" id="formNewTicket">
						<div class="col-md-8">
							<form class="form-horizontal">
								<input type="hidden" id="inputID">
								<div class="form-group" id="nomorDiv" style="display: none;">
									<label for="inputNomor" class="col-sm-1 control-label" >ID Ticket</label>
									<div class="col-sm-11">
										<input type="text" class="form-control" id="inputticket" value="" readonly>
									</div>
								</div>
								<div class="form-group" id="clientDiv" style="display: none;">
									<label class="col-sm-1 control-label">Client</label>
									<div class="col-sm-3">
										<select class="form-control" id="inputClient" style="width:100%">
										</select>
									</div>
									<label class="col-sm-1 control-label">Type</label>
									<div class="col-sm-3">
										<select class="form-control" id="inputTypeTicket">
											<option selected="selected" value="none">Chose Type</option>
											<option value="Trouble Ticket">Trouble Ticket</option>
											<option value="Preventive Maintenance">Preventive Maintenance Ticket</option>
											<option value="Permintaan Layanan">Permintaan Layanan Ticket</option>
										</select>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">Severity</label>
										<div class="col-sm-3">
											<select class="form-control" id="inputSeverity">
											</select>
										</div>
									</div>
								</div>

								<hr id="hrLine" style="display: none">
								<div class="form-group" id="refrenceDiv" style="display: none;">
									<label for="inputDescription" class="col-sm-2 control-label">Refrence</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputRefrence" placeholder=""></div>
								</div>
								<!-- <div class="form-group has-error">
									<label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
										error</label>
									<input type="text" class="form-control" id="inputError" placeholder="Enter ...">
									
								</div> -->
								<div class="form-group" id="picDiv" style="display: none;">
									<label for="inputDescription" class="col-sm-2 control-label">PIC*</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputPIC" placeholder="" required>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Person In Charge must be fill!</span>
									</div>
								</div>
								<div class="form-group" id="contactDiv" style="display: none;">
									<label for="inputDescription" class="col-sm-2 control-label">Contact PIC*</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputContact" placeholder="" required>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Contact PIC must be fill!</span>
									</div>
								</div>
								<div class="form-group" id="categoryDiv" style="display: none;">
									<label for="inputCreator" class="col-sm-2 control-label">Category</label>
									<div class="col-sm-10">
										<select class="form-control" id="inputCategory" required>
											<option selected="selected">Chose problem category</option>
											<option value="Aktivasi">Aktivasi</option>
											<option value="Cash Handler Fatal">Cash Handler Fatal</option>
											<option value="Cassette Fatal">Cassette Fatal</option>
											<option value="EJ Fail">EJ Fail</option>
											<option value="Key Fail">Key Fail</option>
											<option value="Listening">Listening</option>
											<option value="Vandalisme">Vandalisme</option>
											<option value="Softkey">Softkey</option>
											<option value="Dispenser">Dispenser</option>
											<option value="Cartreader">Cartreader</option>
											<option value="Printer">Printer</option>
											<option value="Lain-lain">Lain-lain</option>
										</select>
									</div>
								</div>
								<div class="form-group" id="problemDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Problem*</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputProblem" placeholder="" required>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Problem must be fill!</span>
									</div>
								</div>
								<div class="form-group" id="inputATMid" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">ID ATM*</label>
									<div class="col-sm-10">
										<select class="form-control select2" id="inputATM" style="width: 100%" required></select>
										<span class="help-block" style="margin-bottom: 0px; display: none;">ATM must be select!</span>
									</div>
								</div>
								<div class="form-group" id="locationDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Location*</label>
									<div class="col-sm-10">
										<select class="form-control select2" id="inputAbsenLocation" style="width: 100%; display: none"></select>
										<select class="form-control select2" id="inputSwitchLocation" style="width: 100%; display: none"></select>
										<input type="text" class="form-control" id="inputLocation" placeholder="" required>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Location Must be fill!</span>
									</div>
								</div>
								<div class="form-group" id="engineerDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Engineer*</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputEngineerOpen" placeholder="" required>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Engineer must be fill!</span>
									</div>
								</div>
								<div class="form-group" id="serialDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Serial Number</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputSerial" placeholder="">
									</div>
								</div>
								<div class="form-group" id="typeDiv" style="display: none;">
									<label for="inputType" class="col-sm-2 control-label">Device Type</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputType" placeholder="">
									</div>
								</div>
								<div class="form-group" id="ipMechineDiv" style="display: none;">
									<label for="inputType" class="col-sm-2 control-label">IP Address</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputIpMechine" placeholder="">
									</div>
								</div>
								<div class="form-group" id="ipServerDiv" style="display: none;">
									<label for="inputType" class="col-sm-2 control-label">IP Server</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputIpServer" placeholder="">
									</div>
								</div>
								<hr id="hrLine2" style="display: none">
								<div class="form-group" id="reportDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Report Time*</label>
									<div class="col-sm-5 firstReport">
										<div class="input-group">
											<input type="text" class="form-control" id="inputReportingTime" placeholder="ex. 01:11:00">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
										</div>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Time must be set!</span>
									</div>
									<div class="col-sm-5 secondReport">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="inputReportingDate">
										</div>
										<span class="help-block" style="margin-bottom: 0px; display: none;">Date must be set!</span>
										<!-- <input type="text" class="form-control" id="inputReport" placeholder=""> -->
									</div>
								</div>
								<div class="form-group" id="dateDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Date Open</label>
									<div class="col-sm-10">

										<input type="text" class="form-control" id="inputDate" placeholder="" disabled></div>
								</div>
								<div class="form-group" id="noteDiv" style="display: none;">
									<label for="inputEmail" class="col-sm-2 control-label">Note Open</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="inputNote" placeholder=""></div>
								</div>
							</form>
							<i class="btn btn-flat btn-info pull-right" id="createTicket" style="display: none;">Create Ticket</i>
						</div>

						<div class="col-md-4" id="tableTicket" style="display: none;">
							<h1>Preview Ticket : </h1>
							<hr>
							<table class="table table2">
								<tr>
									<th class="bg-primary">Ticket ID</th>
									<td id="holderID"></td>
								</tr>
								<tr>
									<th class="bg-primary">Refrence</th>
									<td id="holderRefrence"></td>
								</tr>
								<tr>
									<th class="bg-primary">Customer</th>
									<td id="holderCustomer"></td>
								</tr>
								<tr>
									<th class="bg-primary">PIC</th>
									<td id="holderPIC"></td>
								</tr>
								<tr>
									<th class="bg-primary">Contact</th>
									<td id="holderContact"></td>
								</tr>
								<tr>
									<th class="bg-primary">Problem</th>
									<td id="holderProblem"></td>
								</tr>
								<tr>
									<th class="bg-primary">Location</th>
									<td id="holderLocation"></td>
								</tr>
								
								<tr id="holderIDATM2" style="display: none;">
									<th class="bg-primary">ID ATM</th>
									<td id="holderIDATM"></td>
								</tr>
								<tr>
									<th class="bg-primary">Date</th>
									<td id="holderDate"></td>
								</tr>
								<tr id="holderEngineer" style="display: none;">
									<th class="bg-primary">Engineer</th>
									<td id="holderEngineerOpen"></td>
								</tr>
								<tr id="holderSerial1">
									<th class="bg-primary">Serial number</th>
									<td id="holderSerial"></td>
								</tr>
								<tr id="holderIDATM3" style="display: none;">
									<th class="bg-primary">Device Type</th>
									<td id="holderType"></td>
								</tr>
								<tr id="holderIPMechine" style="display: none;">
									<th class="bg-primary">IP Address</th>
									<td id="holderIPMechine2"></td>
								</tr>
								<tr id="holderIPServer" style="display: none;">
									<th class="bg-primary">IP Server</th>
									<td id="holderIPServer2"></td>
								</tr>
								<tr>
									<th class="bg-primary">Severity</th>
									<td id="holderSeverity"></td>
								</tr>
								<tr>
									<th class="bg-primary">Status</th>
									<td id="holderStatus" class="text-center bg-red-active" style="border-bottom: none;"></td>
								</tr>
								<tr>
									<th class="bg-primary">Waktu</th>
									<td id="holderWaktu" class="text-center bg-red-active" style="border-top: none;"></td>
								</tr>
								<tr>
									<th class="bg-primary">Note</th>
									<td id="holderNote"></td>
								</tr>
							</table>
							<div class="row">
								<div class="col-md-8">
									<select class="form-control" id="inputTemplateEmail">
									</select>
								</div>
								<div class="col-md-4">
									<i style="width:100%" class="btn btn-flat btn-info" id="createEmailBody" onclick="createEmailBody()" disabled>Create Email</i>
									<!-- <i style="width:100%" class="btn btn-flat btn-info" id="createEmailBodyNormal" onclick="createEmailBody('normal')">Create Email</i>
									<i style="width:100%" class="btn btn-flat btn-success" id="createEmailBodyWincor" onclick="createEmailBody('wincor')">Create Wincor Email</i> -->
								</div>
							</div>
						</div>
					</div>
					<div class="row" id="sendTicket" style="display: none;">
						<div class="col-md-12">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-1 control-label">
										To : 
									</label>
									<div class="col-sm-11">
										<input class="form-control" name="emailTo" id="emailOpenTo">
									</div>
									<div class="col-sm-11 col-sm-offset-1 help-block" style="margin-bottom: 0px;">
										Enter the recipient of this open email!
									</div>
									
								</div>
								<div class="form-group">
									<label class="col-sm-1 control-label">
										Cc :
									</label>
									<div class="col-sm-11">
										<input class="form-control" name="emailCc" id="emailOpenCc">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1 control-label">
										Subject :
									</label>
									<div class="col-sm-11">
										<input class="form-control" name="emailSubject" id="emailOpenSubject">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyOpenMail">
											
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<div>
											<!-- <div class="btn btn-default btn-file">
												<i class="fa fa-paperclip"></i> Attachment
												<input type="file" name="attachment" id="emailOpenAttachment">
											</div> -->
											<button class="btn btn-flat btn-default pull-left" onclick="backOpenEmail()"><i class="fa fa-chevron-left"></i> Back</button>
											<button class="btn btn-flat btn-primary pull-right" onclick="sendOpenEmail()"><i class="fa fa-envelope-o"></i> Send</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="performance">
					<div class="row">
						<div class="col-md-4">
							<b>Filter by Client : </b>
							<div>
								<select class="form-control" id="clientList" style="width:100%" multiple="multiple"></select>
							</div>
						</div>
						<div class="col-md-2" style="display:none">
							<b>Filter by Severity or Type : </b>
							<div>
								<select class="form-control" id="severityFilter" style="width:100%" multiple="multiple"></select>
							</div>
						</div>
						<div class="col-md-2">
							<b>Filter by Type Ticket : </b>
							<div>
								<select class="form-control" id="typeFilter" style="width:100%" multiple="multiple"></select>
							</div>
						</div>
						<div class="col-md-2">
							<b>Range Date : </b>
							<div>
								<input type="hidden" id="startDateFilter">
					          	<input type="hidden" id="endDateFilter">
								<button type="button" class="btn btn-default btn-flat pull-left" style="width:100%" id="dateFilter">
									<i class="fa fa-calendar"></i> Date range picker
									<span>
										<i class="fa fa-caret-down"></i>
									</span>
								</button>
							</div>
						</div>
						<div class="col-md-4">
							<b>Search Anything</b>
							<div class="input-group pull-right">
								<input id="searchBarTicket" type="text" class="form-control" placeholder="ex: Ticket ID">
								
								<div class="input-group-btn">
									<button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										Show 10 
										<span class="fa fa-caret-down"></span>
									</button>
									<ul class="dropdown-menu" id="selectShowEntryTicket">
										<li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
										<li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
										<li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
										<li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
									</ul>
								</div>
								<span class="input-group-btn">
									<!-- <button id="applyFilterTablePerformance" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-search"></i>
									</button> -->
									<button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-remove"></i>
									</button>
									
								</span>
								<span class="input-group-btn">
									<button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										Displayed Column
										<span class="fa fa-caret-down"></span>
									</button>
									<ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
										<!-- <li class="active severityCounter" onclick="changeColumnTable(this)" data-column="0"><a>ID Ticket</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="1"><a>ID ATM</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="2"><a>Ticket Number</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="3"><a>Open</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="4"><a>Location - Problem</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="5"><a>PIC</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="6"><a>Severity</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="7"><a>Status</a></li>
										<li class="active severityCounter" onclick="changeColumnTable(this)" data-column="8"><a>Operator</a></li> -->
										<!-- <li>
											<input type="checkbox" value=""><span class="text">ID ATM</span>
										</li> -->
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">ID Ticket</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="1"><span class="text">ID ATM</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="2"><span class="text">Ticket Number</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="3"><span class="text">Open</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="4"><span class="text">Location - Problem</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">PIC</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="6"><span class="text">Severity</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="7"><span class="text">Status</span></li>
										<li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="8"><span class="text">Operator</span></li>
									</ul>
									<button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-refresh"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive no-padding">
								<table class="table table-bordered table-striped dataTable" id="tablePerformance">
									<thead>
										<th style="width: 120px;text-align:center;vertical-align: middle;">
											ID Ticket
										</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;" class="columnIdAtm">
											ID ATM
										</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;" class="columnTicketNum">
											Ticket Number
										</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;">
											Open
										</th>
										<th style="vertical-align: middle;">
											Location - Problem
										</th>
										<th style="text-align: center;vertical-align: middle;">
											PIC
										</th>
										<!-- <th style="width: 100px;vertical-align: middle;">
											Location
										</th> -->
										<th style="text-align: center;vertical-align: middle;">
											Severity
										</th>
										<th style="text-align: center;vertical-align: middle;">
											Status
										</th>
										<th style="text-align: center;vertical-align: middle;">
											Operator
										</th>
										<th style="text-align: center;vertical-align: middle;">
											Action
										</th>
									</thead>
									<tfoot>
										<th style="width: 120px;text-align:center;vertical-align: middle;">ID Ticket</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;" class="columnIdAtm">ID ATM*</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;" class="columnTicketNum">Ticket Number</th>
										<th style="width: 100px;text-align:center;vertical-align: middle;">Open</th>
										<th style="vertical-align: middle;">Location - Problem</th>
										<th style="text-align: center;vertical-align: middle;">PIC</th>
										<!-- <th style="width: 100px;vertical-align: middle;">Location</th> -->
										<th style="text-align: center;vertical-align: middle;">Severity</th>
										<th style="text-align: center;vertical-align: middle;">Status</th>
										<th style="text-align: center;vertical-align: middle;">Operator</th>
										<th style="text-align: center;vertical-align: middle;">Action</th>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="setting">
					<div class="row form-group">
						<div class="col-md-9">
							<button class="btn btn-flat btn-default" onclick="emailSetting()">
								Email Setting
							</button>
							<button class="btn btn-flat btn-default" onclick="atmSetting()">
								ATM Setting
							</button>
							<button class="btn btn-flat btn-default" onclick="absenSetting()">
								Absen Setting
							</button>
							<button class="btn btn-flat btn-default" onclick="switchSetting()">
								Switch Setting
							</button>
							<button class="btn btn-flat btn-default" onclick="severitySetting()">
								Severity Setting
							</button>
							<button class="btn btn-flat btn-default" onclick="clientSetting()">
								Client Setting
							</button>

							
						</div>
						<div class="col-sm-3 settingComponent" style="display: none" id="addEmail2">
							<div class="input-group">	
								<input id="searchBarEmail" type="text" class="form-control" placeholder="Search Client">
								<span class="input-group-btn">
									<button id="applyFilterTableEmail" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-search"></i>
									</button>
									<button class="btn btn-flat btn-primary" onclick="EmailAdd()" id="addEmail" style="margin-left: 10px;">
										Add Email
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-3 settingComponent" style="display: none" id="addAtm2">
							<div class="input-group">	
								<input id="searchBarATM" type="text" class="form-control" placeholder="Search ATM">
								<span class="input-group-btn">
									<button id="applyFilterTableATM" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-search"></i>
									</button>
									<button class="btn btn-flat btn-primary" onclick="atmAdd()" id="addAtm" style="margin-left: 10px;">
										Add ATM
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-3 settingComponent" style="display: none" id="addAbsen2">
							<div class="input-group">	
								<input id="searchBarAbsen" type="text" class="form-control" placeholder="Search Absen Machine">
								<span class="input-group-btn">
									<button id="applyFilterTableAbsen" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-search"></i>
									</button>
									<button class="btn btn-flat btn-primary" onclick="absenAdd()" id="addAbsen" style="margin-left: 10px;">
										Add
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-3 settingComponent" style="display: none" id="addSwitch2">
							<div class="input-group">	
								<input id="searchBarSwitch" type="text" class="form-control" placeholder="Search Switch">
								<span class="input-group-btn">
									<button id="applyFilterTableSwitch" type="button" class="btn btn-default btn-flat">
										<i class="fa fa-fw fa-search"></i>
									</button>
									<button class="btn btn-flat btn-primary" onclick="switchAdd()" id="addSwitch" style="margin-left: 10px;">
										Add
									</button>
								</span>
							</div>
						</div>
					</div>
					<div style="display: none" id="emailSetting" class="row form-group settingComponent">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered" id="tableClient">
									<thead>
										<tr>
											<th colspan="6" style="vertical-align: middle;text-align: center;">Open</th>
											<th colspan="6" style="vertical-align: middle;text-align: center;">Close</th>
										</tr>
										<tr>
											<th style="vertical-align: middle;text-align: center;">Client</th>
											<th style="vertical-align: middle;text-align: center;">Acronym</th>
											<th style="vertical-align: middle;text-align: center;">Dear</th>
											<th style="vertical-align: middle;text-align: center;">To</th>
											<th style="vertical-align: middle;text-align: center;">Cc</th>
											<th style="vertical-align: middle;text-align: center;">Dear</th>
											<th style="vertical-align: middle;text-align: center;">To</th>
											<th style="vertical-align: middle;text-align: center;">Cc</th>
											<th style="vertical-align: middle;text-align: center;">Action</th>
											
										</tr>
									</thead>
								</table>
							</div>		
						</div>
					</div>
					<div style="display: none" id="atmSetting" class="row form-group settingComponent">
						<div class="col-md-12">
							<table class="table table-striped" id="tableAtm">
								<thead>
									<tr>
										<th style="vertical-align: middle;text-align: center;">Owner</th>
										<th style="vertical-align: middle;text-align: center;">ATM ID</th>
										<th style="vertical-align: middle;text-align: center;">Serial Number</th>
										<th style="vertical-align: middle;text-align: center;">Location</th>
										<th style="vertical-align: middle;text-align: center;">Activation</th>
										<th style="vertical-align: middle;text-align: center;">Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div style="display: none" id="absenSetting" class="row form-group settingComponent">
						<div class="col-md-12">
							<table class="table table-striped" id="tableAbsen">
								<thead>
									<tr>
										<th style="vertical-align: middle;text-align: center;">Nama Cabang</th>
										<th style="vertical-align: middle;text-align: center;">Nama Kantor</th>
										<th style="vertical-align: middle;text-align: center;">Type Machine</th>
										<th style="vertical-align: middle;text-align: center;">IP Machine</th>
										<th style="vertical-align: middle;text-align: center;">IP Server</th>
										<th style="vertical-align: middle;text-align: center;"></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div style="display: none" id="switchSetting" class="row form-group settingComponent">
						<div class="col-md-12">
							<table class="table table-striped" id="tableSwitch">
								<thead>
									<tr>
										<th style="vertical-align: middle;text-align: center;">Location</th>
										<th style="vertical-align: middle;text-align: center;">Cabang</th>
										<th style="vertical-align: middle;text-align: center;">Type</th>
										<th style="vertical-align: middle;text-align: center;">Port</th>
										<th style="vertical-align: middle;text-align: center;">Serial_number</th>
										<th style="vertical-align: middle;text-align: center;">IP Management</th>
										<th style="vertical-align: middle;text-align: center;"></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div style="display: none" id="severitySetting" class="row form-group settingComponent">
						<div class="col-md-12">
							Comming Soon...
						</div>
					</div>
					<div style="display: none" id="clientSetting" class="row form-group settingComponent">
						<div class="col-md-12">
							Comming Soon...
						</div>
					</div>
				</div>

				<div class="tab-pane" id="reporting">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Select Type</label>
								<select id="selectReportingType" class="form-control">
									<option>Chose One</option>
									<option value="1">Finish Report</option>
									<option value="2">Bayu Report</option>
									<option value="3">Denny Report</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 finish-report" style="display:none;">
							<div class="form-group">
								<label>Select Client</label>
								<select id="selectReportingClient" class="form-control">
								</select>
							</div>
						</div>
						<div class="col-md-2 finish-report" style="display:none;">
							<div class="form-group">
								<label>Select Year</label>
								<select id="selectReportingYear" class="form-control">
								</select>
							</div>
						</div>
						<div class="col-md-2 finish-report" style="display:none;">
							<div class="form-group">
								<label>Select Month</label>
								<select id="selectReportingMonth" class="form-control">
								</select>
							</div>
						</div>
						<div class="col-md-4 bayu-report" style="display:none;">
							<div class="form-group">
								<label>Date range button:</label>

								<div class="input-group">
									<button type="button" class="btn btn-default pull-right" id="daterange-btn">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-md-4 denny-report" style="display:none;">
							<div class="form-group">
								<label>Date range button:</label>

								<div class="input-group">
									<button type="button" class="btn btn-default pull-right" id="daterange-btn2">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-10">
							<!-- <a id="ReportingButtonLink" href=""> -->
								<button id="ReportingButtonGo" class="pull-right btn btn-flat btn-primary" style="display: none;" onclick="getReport()">
									Goo..
								</button>
								<button id="ReportingButtonGoNew" class="pull-right btn btn-flat btn-primary" style="display: none;">
									Goo..
								</button>
								<button id="ReportingButtonGoNew2" class="pull-right btn btn-flat btn-primary" style="display: none;">
									Goo..
								</button>
							<!-- </a> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="modal-ticket">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" style="margin-left: 5px;">×</span>
					</button>
					<div class="modal-tools pull-right" style="text-align: right";>
						<div>
							<span class="label label-default" id="ticketType" style="font-size: 15px;"></span>
							<span class="label label-default" id="ticketSeverity" style="font-size: 15px;"></span>
						</div>
						<div style="margin-top: 5px;">
							<span id="ticketLatestStatus"></span> 
							<span class="label label-default" id="ticketStatus"></span>
						</div>
					</div>
					<div>
						<h4 class="modal-title" id="modal-ticket-title">Ticket ID </h4>
						<span id="ticketOperator"></span>
					</div>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" class="form-control" id="ticketID">
						<input type="hidden" class="form-control" id="ticketOpen">
						<div class="row" id="rowGeneral">
							<div class="col-sm-6">
								<div class="form-group">
									<label>ID ATM</label>
									<input type="text" class="form-control" id="ticketIDATM" readonly>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Serial Number</label>
									<input type="text" class="form-control" id="ticketSerial" style="display: none" readonly> 
									<textarea type="text" class="form-control" id="ticketSerialArea" rows="3" style="display: none" readonly></textarea> 
								</div>
							</div>
						</div>
						<div class="row" id="rowAbsen" style="display: none;">
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Machine</label>
									<input type="text" class="form-control" id="ticketIPMachine" readonly>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Server</label>
									<input type="text" class="form-control" id="ticketIPServer" readonly> 
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Machine Type</label>
									<input type="text" class="form-control" id="ticketMachineType" readonly> 
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Problem</label>
							<input type="text" class="form-control" id="ticketProblem" readonly>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>PIC</label>
									<input type="text" class="form-control" id="ticketPIC" readonly>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Location</label>
									<input type="text" class="form-control" id="ticketLocation" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Number Ticket</label>
									<input type="text" class="form-control" id="ticketNumber">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Engineer</label>
									<input type="text" class="form-control" id="ticketEngineer">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Activity</label>
							<ul id="ticketActivity" style="padding-left: 25px;">
							</ul>
						</div>
						<div class="form-group" id="ticketNoteUpdate">
							<label>Note Activity*</label>
							<textarea class="form-control" rows="1" id="ticketNote"></textarea>
						</div>
						<div class="form-group" style="display: none" id="ticketRoute" >
							<label>Root Cause</label>
							<textarea type="text" class="form-control" id="ticketRouteTxt"  readonly></textarea>
						</div>
						<div class="form-group" style="display: none" id="ticketCouter">
							<label>Counter Measure</label>
							<textarea type="text" class="form-control" id="ticketCouterTxt" readonly></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" onclick="exitTicket()">Exit</button>
					<button type="button" class="btn btn-flat btn-danger pull-left" id="escalateButton">Escalate</button>
					<button type="button" class="btn btn-flat btn-success" id="closeButton">Close</button>
					<button type="button" class="btn btn-flat btn-warning" id="pendingButton">Pending</button>
					<button type="button" class="btn btn-flat bg-purple" id="cancelButton" >Cancel</button>
					<button type="button" class="btn btn-flat btn-primary" id="updateButton">Update</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-next-on-progress">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal-ticket-title">Send On Progress Ticket</h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email To : 
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailTo" id="emailOnProgressTo">
								</div>
								<div class="col-sm-10 col-sm-offset-2 help-block" style="margin-bottom: 0px;">
									Enter the recipient of this on progress email!
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email Cc :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailCc" id="emailOnProgressCc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Subject :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailSubject" id="emailOnProgressSubject">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyOnProgressMail">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<i class="btn btn-flat btn-primary" id="sendOnProgressEmail"><i class="fa fa-envelope-o"></i> Send</i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-cancel">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal-ticket-title">Cancel Ticket</h4>
					</div>
					<div class="modal-body">
						<form role="form">
							<div class="form-group">
								<label>Reason</label>
								<textarea type="text" class="form-control" id="saveReasonCancel"></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-flat bg-purple " onclick="prepareCancelEmail()">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-next-cancel">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal-ticket-title">Send Cancel Ticket </h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email To : 
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailTo" id="emailCancelTo">
								</div>
								<div class="col-sm-10 col-sm-offset-2 help-block" style="margin-bottom: 0px;">
									Enter the recipient of this cancel email!
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email Cc :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailCc" id="emailCancelCc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Subject :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailSubject" id="emailCancelSubject">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyCancelMail">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<i class="btn btn-flat btn-primary" onclick="sendCancelEmail()"><i class="fa fa-envelope-o"></i> Send</i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-pending">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal-ticket-title">Pending Ticket</h4>
					</div>
					<div class="modal-body">
						<form role="form">
							<div class="form-group" id="labelPendingReason">
								<label>Reason</label>
								<textarea type="text" class="form-control" id="saveReasonPending"></textarea>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group" style="margin-bottom: 0;" id="labelPendingEstimation">
										<label class="control-label">Estimation pending</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="datePending">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="bootstrap-timepicker">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control timepicker" id="timePending">

												<div class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row" style="display: none" id="estimationPendingHolder">
								<div class="col-sm-12">
									<p>
										The pending estimate for this ticket has been set to the previous pending on 
										<br><b id="estimationPendingText"></b>
									</p>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-flat btn-warning" onclick="preparePendingEmail()">Pending</button>
						<button type="button" id="updatePendingBtn" class="btn btn-flat btn-primary pull-left" onclick="updatePending()">Update Pending</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-next-pending">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modal-ticket-title">Send Pending Ticket </h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email To : 
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailTo" id="emailPendingTo">
								</div>
								<div class="col-sm-10 col-sm-offset-2 help-block" style="margin-bottom: 0px;">
									Enter the recipient of this pending email!
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email Cc :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailCc" id="emailPendingCc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Subject :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailSubject" id="emailPendingSubject">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyPendingMail">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="pull-right">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<i class="btn btn-flat btn-primary" onclick="sendPendingEmail()"><i class="fa fa-envelope-o"></i> Send</i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-close">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						
						<h4 class="modal-title" id="modal-ticket-title">Close Ticket </h4>
					</div>
					<div class="modal-body">
						<form role="form">
							<div class="form-group">
								<label>Root Cause</label>
								<textarea type="text" class="form-control" id="saveCloseRoute"></textarea>
							</div>
							<div class="form-group">
								<label>Counter Measure</label>
								<textarea type="text" class="form-control" id="saveCloseCouter"></textarea>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="bootstrap-timepicker">
										<div class="form-group">
											<label>Time</label>

											<div class="input-group">
												<input type="text" class="form-control timepicker" id="timeClose">

												<div class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Date</label>

										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="dateClose">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-flat btn-success " onclick="prepareCloseEmail()">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-next-close">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modal-ticket-title">Send Close Ticket</h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email To : 
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailTo" id="emailCloseTo">
								</div>
								<div class="col-sm-10 col-sm-offset-2 help-block" style="margin-bottom: 0px;">
									Enter the recipient of this close email!
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email Cc :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailCc" id="emailCloseCc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Subject :
								</label>
								<div class="col-sm-10">
									<input class="form-control" name="emailSubject" id="emailCloseSubject">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyCloseMail">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="pull-right">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<i class="btn btn-flat btn-primary" onclick="sendCloseEmail()"><i class="fa fa-envelope-o"></i> Send</i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-escalate">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						
						<h4 class="modal-title" id="modal-ticket-title">Escalate Ticket </h4>
					</div>
					<div class="modal-body">
						<form role="form">
							<div class="form-group" >
								<label>Root Cause Analysis (RCA)</label>
								<textarea type="text" class="form-control" id="escalateRCA"></textarea>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>Next Engineer</label>
									<p>For escalation, you must determine the next engineer who will work on this ticket.</p>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control" id="escalateNameEngineer">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Contact (Phone)</label>
										<input type="text" class="form-control pull-right" id="escalateContactEngineer">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-flat btn-danger" onclick="prepareEscalateEmail()">Escalate</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-next-escalate">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						
						<h4 class="modal-title" id="modal-ticket-title">Mail Escalate Ticket</h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email To : 
								</label>
								<div class="col-sm-10">
									<input class="form-control" id="emailEscalateTo">
								</div>
								<div class="col-sm-10 col-sm-offset-2 help-block" style="margin-bottom: 0px;">
									Enter the recipient of this escalate email!
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Email Cc :
								</label>
								<div class="col-sm-10">
									<input class="form-control" id="emailEscalateCc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">
									Subject :
								</label>
								<div class="col-sm-10">
									<input class="form-control" id="emailEscalateSubject">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyEscalateMail">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="pull-right">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<i class="btn btn-flat btn-primary" onclick="sendEscalateEmail()"><i class="fa fa-envelope-o"></i> Send</i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-add-email">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"> Add Email </h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<div class="form-group">
							<label>Client Title</label>
							<input type="text" class="form-control" id="clientTitleAdd">
						</div>
						<div class="form-group">
							<label>Client Acronym</label>
							<input type="text" class="form-control" id="clientAcronymAdd" style="text-transform:uppercase" maxlength="4">
						</div>
						<hr>
						<div class="form-group">
							<label>Open Dear</label>
							<input type="text" class="form-control" id="openDearAdd">
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Open To</label>
									<textarea class="form-control" rows="3" id="openToAdd"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Open Cc</label>
									<textarea class="form-control" rows="3" id="openCcAdd"></textarea>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label>Close Dear</label>
							<input type="text" class="form-control" id="closeDearAdd">
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Close To</label>
									<textarea class="form-control" rows="3" id="closeToAdd"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Close Cc</label>
									<textarea class="form-control" rows="3" id="closeCcAdd"></textarea>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="form-group">
								<div class="col-sm-6">
									<label class="container">Banking
									  <input type="checkbox" id="bankingAdd">
									  <span class="checkmark"></span>
									</label>
									<label class="container">Wincor
									  <input type="checkbox" id="wincorAdd">
									  <span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="saveClient('AddClient')">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-email">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal-setting-title">Change Setting for </h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" class="form-control" id="clientId">
						<div class="form-group">
							<label>Client Title</label>
							<input type="text" class="form-control" id="clientTitle">
						</div>
						<div class="form-group">
							<label>Client Acronym</label>
							<input type="text" class="form-control" id="clientAcronym">
						</div>
						<hr>
						<div class="form-group">
							<label>Open Dear</label>
							<input type="text" class="form-control" id="openDear">
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Open To</label>
									<textarea class="form-control" rows="3" id="openTo"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Open Cc</label>
									<textarea class="form-control" rows="3" id="openCc"></textarea>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label>Close Dear</label>
							<input type="text" class="form-control" id="closeDear">
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Close To</label>
									<textarea class="form-control" rows="3" id="closeTo"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Close Cc</label>
									<textarea class="form-control" rows="3" id="closeCc"></textarea>
								</div>
							</div>
						</div>

						<hr>
						<div class="row">
							<div class="form-group">
								<div class="col-sm-6">
									<label class="container">Situation
									  <input type="checkbox" id="situation">
									  <span class="checkmark"></span>
									</label>
									<label class="container">Banking
									  <input type="checkbox" id="banking">
									  <span class="checkmark"></span>
									</label>
									<label class="container">Wincor
									  <input type="checkbox" id="wincor">
									  <span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="saveClient('EditClient')">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-atm-add">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">ATM Add</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" id="idAddAtm">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Owner</label>
									<select class="form-control" id="atmAddOwner"></select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>ATM ID</label>
									<input type="text" class="form-control" id="atmAddID">
									<select class="form-control select2" id="ATMadd" style="width: 100%;display: none"></select>
								</div>
							</div>
						</div>
						<div id="atmAddForm">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label>Serial Number</label>
										<input type="text" class="form-control" id="atmAddSerial">
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group">
										<label>Location ATM</label>
										<input type="text" class="form-control" id="atmAddLocation">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Machine Type</label>
								<input type="text" class="form-control" id="atmAddType">
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea type="text" class="form-control" id="atmAddAddress"></textarea>
							</div>
							<hr>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label>Activation Date</label>
										<input type="text" class="form-control" id="atmAddActivation">
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group">
										<label>Note</label>
										<input type="text" class="form-control" placeholder="ex : Kanwil II" id="atmAddNote">
									</div>
								</div>
							</div>
						</div>
						<div id="peripheralAddForm" style="display: none;">
							<div class="row">
								<!-- <div class="col-sm-4">
									<div class="form-group">
										<label>ID Peripheral</label>
										<input type="text" class="form-control" id="atmAddPeripheralID">
									</div>
								</div> -->
								<div class="col-sm-6">
									<div class="form-group">
										<label>Serial Number</label>
										<input type="text" class="form-control" id="atmAddPeripheralSerial">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Mechine Type</label>
										<input type="text" class="form-control" id="atmAddPeripheralType">
									</div>
								</div>
							</div>
						</div>
						<div id="peripheralAddFormCCTV" style="display: none;">
							<hr>
							<label>DVR</label>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Serial Number</label>
										<input type="text" class="form-control" id="atmAddPeripheralSerialCCTVDVR">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Mechine Type</label>
										<input type="text" class="form-control" id="atmAddPeripheralTypeCCTVDVR">
									</div>
								</div>
							</div>
							<label>CCTV Eksternal</label>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Serial Number</label>
										<input type="text" class="form-control" id="atmAddPeripheralSerialCCTVBesar">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Mechine Type</label>
										<input type="text" class="form-control" id="atmAddPeripheralTypeCCTVBesar">
									</div>
								</div>
							</div>
							<label>CCTV Internal</label>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Serial Number</label>
										<input type="text" class="form-control" id="atmAddPeripheralSerialCCTVKecil">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Mechine Type</label>
										<input type="text" class="form-control" id="atmAddPeripheralTypeCCTVKecil">
									</div>
								</div>
							</div>
						</div>
						<!-- 
						<div class="form-group">
							<label>Serial Number</label>
							<input type="text" class="form-control" id="atmSerial2">
						</div>
						<div class="form-group">
							<label>Location ATM</label>
							<input type="text" class="form-control" id="atmLocation2">
						</div> -->
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-success" onclick="newPeripheral()" id="peripheralAddFormButton" style="display: none;">Add Peripheral</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="newAtm()" id="atmAddFormButton">Add</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-atm">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">Change ATM Detail</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" id="idEditAtm">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Owner</label>
									<select class="form-control" id="atmEditOwner"></select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>ATM ID</label>
									<input type="text" class="form-control" id="atmEditID">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Serial Number</label>
									<input type="text" class="form-control" id="atmEditSerial">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label>Location ATM</label>
									<input type="text" class="form-control" id="atmEditLocation">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Mechine Type</label>
									<input type="text" class="form-control" id="atmEditType">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Address</label>
							<textarea type="text" class="form-control" id="atmEditAddress"></textarea>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Activation Date</label>
									<input type="text" class="form-control" id="atmEditActivation">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label>Note</label>
									<input type="text" class="form-control" placeholder="ex : Kanwil II" id="atmEditNote">
								</div>
							</div>
						</div>
						<div id="atmEditPeripheral" style="display: none;">
							<div class="row">
								<div class="col-sm-12" >
									<div class="form-group">
										<label>ATM Peripheral</label>
										<ul id="atmEditPeripheralField">
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="form-group">
							<label>Serial Number</label>
							<input type="text" class="form-control" id="atmSerial">
						</div>
						<div class="form-group">
							<label>Location ATM</label>
							<input type="text" class="form-control" id="atmLocation">
						</div> -->
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-danger pull-left" onclick="deleteAtm()">Delete</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="saveAtm()">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-absen-add">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">Absen Add</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama Cabang</label>
									<input type="text" class="form-control" id="absenAddNamaCabang">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama Kantor</label>
									<input type="text" class="form-control" id="absenAddNamaKantor">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Machine Type</label>
									<input class="form-control" id="absenAddMachineType">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Machine</label>
									<input type="text" class="form-control" id="absenAddIPMachine">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Server</label>
									<input type="text" class="form-control" id="absenAddIPServer">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="newAbsen()" id="atmAddFormButton">Add</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-absen">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">Change Absen Detail</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" id="idEditAbsen">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama Cabang</label>
									<input type="text" class="form-control" id="absenEditNamaCabang">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama Kantor</label>
									<input type="text" class="form-control" id="absenEditNamaKantor">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Machine Type</label>
									<input class="form-control" id="absenEditMachineType">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Machine</label>
									<input type="text" class="form-control" id="absenEditIPMachine">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>IP Server</label>
									<input type="text" class="form-control" id="absenEditIPServer">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-danger pull-left" onclick="deleteAbsen()">Delete</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="saveAbsen()">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-switch-add">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">Switch Add</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Type Switch</label>
									<input type="text" class="form-control" id="switchAddType" placeholder="ex: Ruckus">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Port</label>
									<input type="text" class="form-control" id="switchAddPort" placeholder="24/48 Port">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Serial Number</label>
									<input class="form-control" id="switchAddSerialNumber" placeholder="FA123123">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>IP Management</label>
									<input type="text" class="form-control" id="switchAddIPManagement" placeholder="192.168.2.xxx">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Location</label>
									<input type="text" class="form-control" id="switchAddLocation" placeholder="Pekanbaru">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Cabang</label>
									<input type="text" class="form-control" id="switchAddCabang" placeholder="Simpang Empat">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Note</label>
									<input type="text" class="form-control" id="switchAddNote">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="newSwitch()" id="switchAddFormButton">Add</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-setting-switch">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-setting-title">Change Switch Detail</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<input type="hidden" id="idEditSwitch">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Type Switch</label>
									<input type="text" class="form-control" id="switchEditType" placeholder="ex: Ruckus">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Port</label>
									<input type="text" class="form-control" id="switchEditPort" placeholder="24/48 Port">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Serial Number</label>
									<input class="form-control" id="switchEditSerialNumber" placeholder="FA123123">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>IP Management</label>
									<input type="text" class="form-control" id="switchEditIPManagement" placeholder="192.168.2.xxx">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Location</label>
									<input type="text" class="form-control" id="switchEditLocation" placeholder="Pekanbaru">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Cabang</label>
									<input type="text" class="form-control" id="switchEditCabang" placeholder="Simpang Empat">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Note</label>
									<input type="text" class="form-control" id="switchEditNote">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-flat btn-danger pull-left" onclick="deleteSwitch()">Delete</button>
					<button type="button" class="btn btn-flat btn-primary" onclick="saveSwitch()">Save changes</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.5/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>

<script src="{{ url('js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ url('js/jquery.slimscroll.min.js')}}"></script>
<script src="{{ url('js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ url('js/jquery.emailinput.min.js')}}"></script>
<script src="{{ url('js/roman.js')}}"></script>


@endsection
@section('script')
<script type="text/javascript">
	var swalWithCustomClass

	$(document).ready(function(){
		$("#startDateFilter").val("")
		$("#endDateFilter").val("")
		getDashboard()

		$("#inputReportingTime").val(moment().format('HH:mm:ss'))
		$('#inputReportingDate').datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy'
		})

		$('#bodyOpenMail').slimScroll({
			height: '600px'
		});
		$('#bodyCloseMail').slimScroll({
			height: '600px'
		});
		
		swalWithCustomClass = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-flat btn-primary swal2-margin',
				cancelButton: 'btn btn-flat btn-danger swal2-margin',
				denyButton: 'btn btn-flat btn-danger swal2-margin',
				popup: 'border-radius-0',
			},
			buttonsStyling: false,
		})

		$("#timeClose").timepicker({
			showInputs: false,
			minuteStep: 1,
			maxHours: 24,
			showMeridian: false,
		});

		$("#timePending").timepicker({
			snapToStep: true,
			showInputs: false,
			minuteStep: 15,
			maxHours: 24,
			showMeridian: false,
		});

		$("#atmAddActivation, #atmEditActivation").inputmask("date");

		$('#searchBarTicket').keydown(function(e){
			// // if(e.keyCode == 13){
				
			// // }
			// if($('#searchBarTicket').val() == ""){
			// 	$("#tablePerformance").DataTable().ajax.reload()
			// } else {
				$("#tablePerformance").DataTable().search($('#searchBarTicket').val()).draw();
			// }
		});

		// $('#searchBarTicket').

		$('#searchBarEmail').keypress(function(e){
			if(e.keyCode == 13){
				$("#tableClient").DataTable().search($('#searchBarEmail').val()).draw();
			}
		});

		$('#searchBarATM').keypress(function(e){
			if(e.keyCode == 13){
				$("#tableAtm").DataTable().search($('#searchBarATM').val()).draw();
			}
		});

		$('#searchBarAbsen').keypress(function(e){
			if(e.keyCode == 13){
				$("#tableAbsen").DataTable().search($('#searchBarAbsen').val()).draw();
			}
		});

		$('#searchBarSwitch').keypress(function(e){
			if(e.keyCode == 13){
				$("#tableSwitch").DataTable().search($('#searchBarSwitch').val()).draw();
			}
		});

		// $('#applyFilterTablePerformance').click(function(){
		// 	$("#tablePerformance").DataTable().search($('#searchBarTicket').val()).draw();
		// })

		$('#applyFilterTableATM').click(function(){
			$("#tableAtm").DataTable().search($('#searchBarATM').val()).draw();
		})

		$('#applyFilterTableAbsen').click(function(){
			$("#tableAbsen").DataTable().search($('#searchBarAbsen').val()).draw();
		})

		$('#applyFilterTableSwitch').click(function(){
			$("#tableSwitch").DataTable().search($('#searchBarSwitch').val()).draw();
		})

		$('#applyFilterTableEmail').click(function(){
			$("#tableClient").DataTable().search($('#searchBarEmail').val()).draw();
		})

		$('#clearFilterTable').click(function(){
			$('#searchBarTicket').val('')
			$('#tablePerformance').DataTable().search('').draw();
		});

		$('#reloadTable').click(function(){
			$('#tablePerformance').DataTable().ajax.reload();
		});

	})

	function getDashboard(){

		$.ajax({
			type:"GET",
			url:"{{url('ticketing/getDashboard')}}",
			success:function(result){

				// console.log(result);
				$("#countOpen").text(result.counter_condition.OPEN);
				$("#countProgress").text(result.counter_condition.PROGRESS);
				$("#countPending").text(result.counter_condition.PENDING);
				$("#countClose").text(result.counter_condition.CLOSE);
				$("#countCancel").text(result.counter_condition.CANCEL); 
				$("#countAll").text(result.counter_condition.ALL);

				$("#countCritical").text(result.counter_severity.Critical);
				$("#countMajor").text(result.counter_severity.Major);
				$("#countModerate").text(result.counter_severity.Moderate);
				$("#countMinor").text(result.counter_severity.Minor);
				// var append = ""
				// $.each(result.customer_list,function(key,value){
				// 	var onclickFunction = "getPerformanceByClient('" + value + "')";
				// 	append = append + '<button class="btn btn-flat btn-default buttonFilter buttonFilter' + value+ '" onclick=' + onclickFunction + '>' + value + '</button> ';
				// });

				// $("#clientList").html(append);
				var clientList = [{id:0,text:"Chose the client"}]

				$.each(result.customer_list,function(key,value){
					clientList.push({
						id:value.id,
						text:value.client_acronym + " - " + value.client_name
					})
				});

				$("#clientList").select2({data:clientList})

				var severityFilter = []

				$.each(result.severity_label,function(key,value){
					severityFilter.push({
						id:value.id,
						text:value.name
					})
				});

				var severityFilterAll = [
					{
						text:"Chose the Severity", 
						children:severityFilter
					},{
						text:"Chose the Type", 
						children:[
							{
								id:"TT",
								text:"TT - Trouble Ticket",
							},
							{
								id:"PM",
								text:"PM - Preventive Maintenance"
							},
							{
								id:"PL",
								text:"PL - Permintaan Layanan"
							}
						]
					}
				]

				$("#severityFilter").select2({data:severityFilterAll})

				var typeFilter = [
					{
						id:"TT",
						text:"Trouble Ticket",
					},
					{
						id:"PM",
						text:"Preventive Maintenance"
					},
					{
						id:"PL",
						text:"Permintaan Layanan"
					}
				]

				$("#typeFilter").select2({data:typeFilter})

				var append = '';
				$("#importanTable").empty(append);
				$.each(result.occurring_ticket,function(key,value){
					append = append + '<tr>';
						append = append + '<td>' + value.id_ticket + '</td>';
						append = append + '<td>' + value.id_atm + '</td>';
						append = append + '<td>' + value.location + '</td>';
						append = append + '<td>' + moment(value.date).format('D MMM HH:mm') + '</td>';
						if(value.severity == 1)
							append = append + '<td><span class="label label-danger">Critical</span></td>';
						else if (value.severity == 2)
							append = append + '<td><span class="label" style="background-color:#e67e22 !important">Major</span></td>';
						else if (value.severity == 3)
							append = append + '<td><span class="label" style="background-color:#f1c40f !important">Moderate</span></td>';
						else if (value.severity == 4)
							append = append + '<td><span class="label label-success">Minor</span></td>';
						else
							append = append + '<td><span class="label label-default">N/A</span></td>';
						append = append + '<td>' + value.operator + '</td>';
					append = append + '</tr>';
				});
				
				$("#importanTable").append(append);

				var config = {
					type: 'doughnut',
					data: {
						labels: result.chart_data.label,
						datasets: [{
							data: result.chart_data.data,
							backgroundColor: [
							"#EA2027",
							"#EE5A24",
							"#F79F1F",
							"#FFC312",
							"#C4E538",
							"#A3CB38",
							"#009432",
							"#006266",
							"#1B1464",
							"#0652DD",
							// "#1289A7",
							// "#12CBC4",
							// "#FDA7DF",
							// "#D980FA",
							// "#9980FA",
							// "#5758BB"
							],
						}]
					},
					options: {
						responsive: true,
						legend: {
							position:'right',
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
			}
		});
	}

	function getSeverity(severity){
		$("#performanceTab").click()
		$("#tablePerformance").DataTable().ajax.url('/ticketing/getPerformanceBySeverity?severity=' + severity).load();
	}

	function reserveIdTicket() {
		if("{Auth::check()}"){
			$.ajax({
				type:"GET",
				url:"{{url('ticketing/create/getReserveIdTicket')}}",
				success: function(result){
					$("#inputticket").val(result);
					$("#inputID").val(result);
					$("#inputDate").val(moment().format("DD-MMM-YY HH:mm"));
					
					$("#nomorDiv").show();
					$("#clientDiv").show();
					$("#formNewTicket").show();

					$("#createIdTicket").hide();
				},
			});
		} else {
			window.location('/login');
		}
	}

	var firstTimeTicket = 0;
	var clientBanking = 0;
	var clientWincor = 0;

	$("#inputClient").change(function(){
		var acronym_client = $("#inputClient option:selected").text().split(" - ")[0];
		if(firstTimeTicket == 0){
			$.ajax({
				beforeSend: function(request) {
					request.setRequestHeader("Accept", "application/json");
				},
				type:"GET",
				url:"{{url('ticketing/create/setReserveIdTicket')}}",
				data:{
					id_ticket:$("#inputticket").val() + "/" + acronym_client + moment().format("/MMM/YYYY"),
					acronym_client:acronym_client,
					id_client:$("#inputClient").val(),
					operator:"{{(Auth::check())?Auth::user()->name:'-'}}",
				},
				success: function(result){
					console.log(result.banking)
					clientBanking = result.banking
					clientWincor = result.wincor
					$("#inputticket").val($("#inputticket").val() + "/" + acronym_client + moment().format("/MMM/YYYY"));
				}
			});
		} else {
			var temp = $("#inputticket").val().split('/')
			temp[1] = acronym_client
			var changeResult = temp.join('/')

			$.ajax({
				type:"GET",
				url:"{{url('ticketing/create/putReserveIdTicket')}}",
				data:{
					id_ticket_before:$("#inputticket").val(),
					id_ticket_after:changeResult,
					id_client:$("#inputClient").val(),
					acronym_client:acronym_client,
				},
				success: function(result){
					console.log(result.banking)
					clientBanking = result.banking
					clientWincor = result.wincor
					$("#inputticket").val(changeResult);
				}
			});
		}


		if($("#inputSeverity").val() != "Chose the severity"){
			showInputDetailTicket();
			getBankAtm(clientBanking);
		}

		firstTimeTicket = 1;
	});

	$("#inputTypeTicket").change(function(){
		$("#inputSeverity").attr("disabled",false)
		$("#problemDiv label").text("Problem*")
		$("#reportDiv label").text("Report Time*")
		$("#engineerDiv").hide();

		if($(this).val() == "Preventive Maintenance"){
			$("#inputSeverity").attr("disabled",true)
			$("#problemDiv label").text("Activity*")
			$("#reportDiv label").text("Schedule PM*")
			$("#engineerDiv").show();

			showInputDetailTicket()
		} else if ($(this).val() == "Permintaan Layanan") {
			$("#problemDiv label").text("Activity*")
			if($("#inputSeverity option").length > 2) {
				$("#inputSeverity option")[1].remove()
				$("#inputSeverity option")[1].remove()
				$("#inputSeverity option")[1].remove()
			}
		} else {
			prepareNewParameter()
		}
	})

	$("#inputSeverity").change(function(){
		showInputDetailTicket()
	});

	function showInputDetailTicket(){
		if($("#inputTypeTicket").val() != "none"){
			$("#hrLine").show();
			$("#hrLine2").show();
			$("#refrenceDiv").show();
			$("#picDiv").show();
			$("#contactDiv").show();
			$("#problemDiv").show();
			$("#locationDiv").show();
			$("#dateDiv").show();
			$("#noteDiv").show();
			$("#serialDiv").show();
			$("#reportDiv").show();

			if($("#inputClient option:selected").text().includes("Absensi")){
				$("#serialDiv").hide();
				$("#typeDiv").show();
				$("#inputAbsenLocation").show();
				$("#inputLocation").remove();
				$("#ipMechineDiv").show();
				$("#ipServerDiv").show();
			} 

			if($("#inputClient option:selected").text().includes("Switch")){
				// $("#serialDiv").hide();
				// $("#inputLocation").remove();
				// $("#typeDiv").show();
				// $("#inputAbsenLocation").show();
				// $("#ipMechineDiv").show();
				// $("#ipServerDiv").show();

				$("#inputLocation").remove();
				$("#typeDiv").show();
				$("#inputSwitchLocation").show();
				$("#ipMechineDiv").show();
				// $("#ipServerDiv").show();

			} 

			if(!$("#inputClient option:selected").text().includes("Switch") && !$("#inputClient option:selected").text().includes("Absensi")) {
				$("#inputAbsenLocation").remove();
				$("#inputSwitchLocation").remove();
				$("#inputLocation").show();
			}
			
			$("#createTicket").show();
			var onclick = "createTicket(" + clientBanking + ")"
			$("#createTicket").attr("onclick",onclick);
			
			getBankAtm(clientBanking);
		}
	}

	function createTicket(clientBanking){
		$(".help-block").hide()
		if($("#inputPIC").val() == "" ){
			$("#picDiv").addClass('has-error')
			$("#picDiv .col-sm-10 .help-block").show()
		} else if($("#inputContact").val() == "" ){
			$("#contactDiv").addClass('has-error')
			$("#contactDiv .col-sm-10 .help-block").show()
		} else if($("#inputProblem").val() == "" ){
			$("#problemDiv").addClass('has-error')
			$("#problemDiv .col-sm-10 .help-block").show()
		} else if($("#inputATMid").is(':visible') && $("#inputATM").select2('data')[0].text === "Select One"){
			$("#inputATMid").addClass('has-error')
			$("#inputATMid .col-sm-10 .help-block").show()
		} else if($("#inputLocation").val() == "" ){
			$("#locationDiv").addClass('has-error')
			$("#locationDiv .col-sm-10 .help-block").show()
		} else if($("#inputReportingTime").val() == "" ){
			$("#reportDiv").addClass('has-error')
			$("#reportDiv .col-sm-5.firstReport .help-block").show()

			$("#inputReportingDate").css("border-color",'#d2d6de')
			$("#reportDiv .col-sm-5.secondReport .input-group .input-group-addon").css("border-color",'#d2d6de')
			$("#reportDiv .col-sm-5.secondReport .input-group .input-group-addon i").css("color",'#555')
		} else if($("#inputTypeTicket").val() != "Preventive Maintenance" && $("#inputSeverity").val() == "Chose the severity"){
			$("#inputSeverity").parent().parent().addClass('has-error')
			// $("#reportDiv").addClass('has-error')
			// $("#reportDiv .col-sm-5.secondReport .help-block").show()

			// $("#inputReportingTime").css("border-color",'#d2d6de')
			// $("#reportDiv .col-sm-5.firstReport .input-group .input-group-addon").css("border-color",'#d2d6de')
			// $("#reportDiv .col-sm-5.firstReport .input-group .input-group-addon i").css("color",'#555')
		} else if($("#inputReportingDate").val() == "" ){
			$("#reportDiv").addClass('has-error')
			$("#reportDiv .col-sm-5.secondReport .help-block").show()

			$("#inputReportingTime").css("border-color",'#d2d6de')
			$("#reportDiv .col-sm-5.firstReport .input-group .input-group-addon").css("border-color",'#d2d6de')
			$("#reportDiv .col-sm-5.firstReport .input-group .input-group-addon i").css("color",'#555')
		} else {
			$(".has-error").removeClass('has-error')
			var waktu = moment(($("#inputDate").val()), "DD-MMM-YY HH:mm").format("D MMMM YYYY");
			var waktu2 = moment(($("#inputDate").val()), "DD-MMM-YY HH:mm").format("HH:mm");
			var schedule_date = moment(($("#inputReportingTime").val() + " " + $("#inputReportingDate").val()), "HH:mm:ss DD/MM/YYYY ").format("D MMMM YYYY");
			var schedule_time = moment(($("#inputReportingTime").val() + " " + $("#inputReportingDate").val()), "HH:mm:ss DD/MM/YYYY ").format("HH:mm");

			$("#tableTicket").show();
			$("#holderID").text($("#inputticket").val());
			$("#holderRefrence").text($("#inputRefrence").val());
			$("#holderCustomer").text($("#inputClient option:selected").text().split(" - ")[1]);
			$("#holderPIC").text($("#inputPIC").val());
			$("#holderContact").text($("#inputContact").val());
			$("#holderProblem").text($("#inputProblem").val());
			$("#holderLocation").text($("#inputLocation").val());
			$("#holderDate").text(waktu);
			$("#holderSerial").html($("#inputSerial").val());
			$("#holderType").html($("#inputType").val());
			
			// $("#holderRoot").text($("#inputticket").val();
			$("#holderNote").text($("#inputNote").val());
			$("#holderProblem").prev().text("Problem")
			$("#holderDate").parent().show()
			$("#holderEngineer").hide();
			$("#holderSeverity").parent().show()
			$("#holderStatus").prev().text("Status")
			$("#holderWaktu").prev().text("Time")

			if($("#inputTypeTicket").val() == "Preventive Maintenance"){
				$("#holderStatus").html("<b>" + schedule_date + "</b>");
				$("#holderWaktu").html("<b>" + schedule_time + "</b>");
				$("#holderEngineer").show();
				$("#holderEngineerOpen").html($("#inputEngineerOpen").val());
				$("#holderSeverity").text($("#inputSeverity").val());

				$("#holderProblem").prev().text("Activity")
				$("#holderStatus").prev().text("Date")
				$("#holderWaktu").prev().text("Time")
				$("#holderDate").parent().hide()
				$("#holderSeverity").parent().hide()
			} else {
				if($("#inputTypeTicket").val() == "Permintaan Layanan"){
					$("#holderProblem").prev().text("Activity")
				}
				$("#holderSeverity").text($("#inputSeverity").val());
				$("#holderStatus").html("<b>OPEN</b>");
				$("#holderWaktu").html("<b>" + waktu2 + "</b>");
			}


			if(clientBanking){
				if($("#inputClient option:selected").text().includes("CCTV")){
					$("#holderIDATM2").insertAfter($("#holderIDATM2").next())
					$("#holderIDATM2").show();
					$("#holderIDATM3").show();

					$("#holderSerial").html($("#inputSerial").val());
					$("#holderType").html($("#inputType").val());
					$("#holderType").html($("#inputType").val());

					$("#holderSerial1 th").text("CCTV Serial")
					// $("#holderIDATM2 th").text("ID CCTV")
					$("#holderIDATM3 th").text("CCTV Mechine Type")
					$("#holderIDATM").text($("#inputATM").select2('data')[0].text.split(' -')[0]);
				} if($("#inputClient option:selected").text().includes("UPS")){
					$("#holderIDATM2").insertAfter($("#holderIDATM2").next())
					$("#holderIDATM2").show();
					$("#holderIDATM3").show();

					$("#holderSerial").html($("#inputSerial").val());
					$("#holderType").html($("#inputType").val());
					$("#holderType").html($("#inputType").val());

					$("#holderSerial1 th").text("UPS Serial")
					// $("#holderIDATM2 th").text("ID UPS")
					$("#holderIDATM3 th").text("UPS Mechine Type")
					$("#holderIDATM").text($("#inputATM").select2('data')[0].text.split(' -')[0]);
				} else {
					$("#holderIDATM2").show();
					$("#holderIDATM3").show();
					$("#holderIDATM").text($("#inputATM").select2('data')[0].text.split(' -')[0]);
					$("#holderType").html($("#inputType").val());
					
				}
			} else {

				if($("#inputClient option:selected").text().includes("Absensi")){
					$("#holderIDATM2").hide();
					$("#holderSerial1").hide();
					$("#holderIDATM3").show();
					$("#holderIPMechine").show();
					$("#holderIPMechine2").text($("#inputIpMechine").val());
					$("#holderIPServer").show();
					$("#holderIPServer2").text($("#inputIpServer").val());
					$("#holderLocation").text($("#inputAbsenLocation").select2('data')[0].text);
				} else if($("#inputClient option:selected").text().includes("Switch")){
					$("#holderIDATM2").hide();
					$("#holderSerial1").show();
					$("#holderIDATM3").show();
					$("#holderIPMechine").show();
					$("#holderIPMechine2").text($("#inputIpMechine").val());
					// $("#holderIPServer").show();
					// $("#holderIPServer2").text($("#inputIpServer").val());
					$("#holderLocation").text($("#inputSwitchLocation").select2('data')[0].text);
				} else {
					$("#holderIDATM2").hide();
					$("#holderIDATM3").hide();
				}
			}
			// if(clientWincor == 1){
			// 	$("#createEmailBodyWincor").show()
			// 	$("#createEmailBodyNormal").hide()
			// } else {
			// 	$("#createEmailBodyWincor").hide()
			// 	$("#createEmailBodyNormal").show()
			// }
		}
	}

	function clearFormNewTicket(){
		$("#inputRefrence").val('');
		$("#inputPIC").val('');
		$("#inputContact").val('');
		$("#inputCategory").val('');
		$("#inputProblem").val('');
		if ($('#inputATM').hasClass("select2-hidden-accessible")) {
			$("#inputATM").select2('destroy');
		}
		$("#inputATM").empty()
		$("#inputLocation").val('');
		$("#inputSerial").val('');
		$("#inputEngineerOpen").val('');
		// $("#inputReportingTime").val('');
		$("#inputReportingTime").val(moment().format('HH:mm:ss'))
		$("#inputReportingDate").val('');
		$("#inputDate").val('');
		$("#inputNote").val('');

		$("#hrLine").show();
		$("#hrLine2").show();

		$("#nomorDiv").hide()
		$("#clientDiv").hide()
		$("#refrenceDiv").hide()
		$("#picDiv").hide()
		$("#contactDiv").hide()
		$("#categoryDiv").hide()
		$("#problemDiv").hide()
		$("#inputATMid").hide()
		$("#locationDiv").hide()
		$("#serialDiv").hide()
		$("#engineerDiv").hide()
		$("#typeDiv").hide()
		$("#reportDiv").hide()
		$("#dateDiv").hide()
		$("#noteDiv").hide()
		$("#createTicket").hide()

		$("#holderID").text('');
		$("#holderRefrence").text('');
		$("#holderCustomer").text('');
		$("#holderPIC").text('');
		$("#holderContact").text('');
		$("#holderProblem").text('');
		$("#holderLocation").text('');
		$("#holderEngineer").text('');
		$("#holderDate").text('');
		$("#holderSerial").html('');
		$("#holderType").html('');
		$("#holderIPServer2").html('');
		$("#holderIPMechine2").html('');
		$("#holderType").html('');
		$("#holderSeverity").text('');
		// $("#holderRoot").text($("#inputticket").val();
		$("#holderNote").text('');
		$("#holderStatus").html('');
		$("#holderWaktu").html('');

		$("#holderIDATM2").hide();
		$("#holderIDATM3").hide();
		$("#holderIPMechine").hide();
		$("#holderIPServer").hide();
		$("#holderIDATM").text('');
		
		$("#tableTicket").hide();

		$('.emailMultiSelector').remove()
		$("#emailOpenTo").val('')
		$("#emailOpenCc").val('')
		$("#emailOpenSubject").val('')
		$("#bodyOpenMail").empty()
		$("#sendTicket").hide()

		$("#formNewTicket").hide();
		$("#createIdTicket").show();
	}

	function makeNewTicket(){
		if(firstTimeTicket !== 0){
			swalWithCustomClass.fire({
				title: 'Are you sure?',
				text: "This information of create ticket will be reset!",
				icon: 'warning',
				showCancelButton: true,
			}).then((result) => {
				firstTimeTicket = 0
				clearFormNewTicket()
				prepareNewParameter()
			})
		} else {
			prepareNewParameter()
		}	
	}

	function prepareNewParameter(){
		$.ajax({
			type:"GET",
			url:"{{url('ticketing/create/getParameter')}}",
			success: function (result){
				var appendClient = "<option selected='selected'>Chose the client</option> ";
				var appendSeverity = "<option selected='selected' val='None'>Chose the severity</option> ";
				var appendEmailTemplate = "<option selected='selected' value='none'>Chose Template Email</option> ";

				var arrayClient = [{id:0,text:"Chose the client"}]
				var arraySeverity = [{id:'None',text:"Chose the severity"}]

				$.each(result.client,function(key,value){
					var getPerformanceAppend = "getPerformance('" + value.client_acronym + "')";
					arrayClient.push({
						id:value.id,
						text:value.client_acronym + " - " + value.client_name
					})
					// console.log(value.client_name)
				});

				$.each(result.severity,function(key,value){
					appendSeverity = appendSeverity + "<option value='" + value.id + " (" + value.name + ")'>" + value.name + " - (" + value.description +")</option>";
				});

				$.each(result.email_template,function(key,value){
					appendEmailTemplate = appendEmailTemplate + "<option value='" + value.name + "'>" + value.name +" - " + value.type + "</option>";
				});

				var temp = "";
				if ($('#inputClient').hasClass("select2-hidden-accessible")) {
					temp = $('#inputClient').val()
					$("#inputClient").select2('destroy');
				}

				$("#inputClient").select2({data:arrayClient});
				// if(temp != ""){
				// 	$("#inputClient").val(temp).trigger('change')
				// }

				$("#inputSeverity").html(appendSeverity);

				$("#inputTemplateEmail").html(appendEmailTemplate)
			},
		});
	}

	function getBankAtm(clientBanking){
		if(clientBanking){
			$.ajax({
				type:"GET",
				url:"{{url('ticketing/create/getAtmId')}}",
				data:{
					acronym:$("#inputClient option:selected").text().split(" - ")[0],
					client_id:$("#inputClient").val()
				},
				success: function(result){
					$("#typeDiv").show();
					$("#inputATMid").show();
					$("#categoryDiv").show();
					if ($('#inputATM').hasClass("select2-hidden-accessible")) {
						$("#inputATM").select2('destroy');
					}
					result.unshift('Select One')
					// console.log(result);
					$("#inputATM").select2({
						data:result
					});
					$("#locationDiv .col-sm-2").text('Location')
				}
			});
		} else {
			if($("#inputClient option:selected").text().includes("Absensi")){
				$.ajax({
					type:"GET",
					url:"{{url('ticketing/create/getAbsenId')}}",
					success: function(result){
						if ($('#inputAbsenLocation').hasClass("select2-hidden-accessible")) {
							$("#inputAbsenLocation").select2('destroy');
						}
						result.unshift('Select One')

						$("#inputAbsenLocation").select2({
							data:result
						});
					}
				});
			} else if($("#inputClient option:selected").text().includes("Switch")){
				$.ajax({
					type:"GET",
					url:"{{url('ticketing/create/getSwitchId')}}",
					success: function(result){
						if ($('#inputSwitchLocation').hasClass("select2-hidden-accessible")) {
							$("#inputSwitchLocation").select2('destroy');
						}
						result.unshift('Select One')

						$("#inputSwitchLocation").select2({
							data:result
						});
					}
				});
			} else {
				$("#locationDiv .col-sm-2").text('Location*')
				$("#inputATM").val("");
				$("#inputSerial").val("");
				$("#inputLocation").val("");
				$("#inputATMid").hide();
			}
		}
	}

	$("#inputATM").change(function(){
		if(this.value === "Select One"){
			$("#inputLocation").val("");
			$("#inputSerial").val("");
			$("#inputType").val("");
		} else {
			if($("#inputClient option:selected").text().includes("CCTV") || $("#inputClient option:selected").text().includes("UPS")){
				if($("#inputClient option:selected").text().includes("CCTV")){
					var type = "CCTV"
				} else if($("#inputClient option:selected").text().includes("UPS")) {
					var type = "UPS"
				}
				$.ajax({
					type:"GET",
					url:"{{url('ticketing/create/getAtmPeripheralDetail')}}",
					data:{
						id_atm:this.value,
						type:type
					},
					success: function(result){
						$("#inputLocation").val("[" + result.type + "] " + result.atm.location);
						$("#inputSerial").val(result.serial_number);
						$("#inputType").val(result.machine_type);
					}
				});
			} else {
				$.ajax({
					type:"GET",
					url:"{{url('ticketing/create/getAtmDetail')}}",
					data:{
						id_atm:this.value
					},
					success: function(result){
						$("#inputLocation").val(result.location);
						$("#inputSerial").val(result.serial_number);
						$("#inputType").val(result.machine_type);
					}
				});
			}

		}
	});

	$("#inputAbsenLocation").change(function(){
		if(this.value === "Select One"){
			$("#inputType").val("");
			$("#inputIpMechine").val("");
			$("#inputIpServer").val("");
		} else {
			$.ajax({
				type:"GET",
				url:"{{url('/ticketing/create/getAbsenDetail')}}",
				data:{
					id_absen:this.value
				},
				success: function(result){
					$("#inputType").val(result.type_machine);
					$("#inputIpMechine").val(result.ip_machine);
					$("#inputIpServer").val(result.ip_server);
				}
			});
		}
	})

	$("#inputSwitchLocation").change(function(){
		if(this.value === "Select One"){
			$("#inputSerial").val("");
			$("#inputType").val("");
			$("#inputIpMechine").val("");
		} else {
			$.ajax({
				type:"GET",
				url:"{{url('/ticketing/create/getSwitchDetail')}}",
				data:{
					id_switch:this.value
				},
				success: function(result){
					$("#inputSerial").val(result.serial_number);
					$("#inputType").val(result.type + " - " + result.port);
					$("#inputIpMechine").val(result.ip_management);
					// $("#inputIpServer").val(result.ip_server);
				}
			});
		}
	})

	$("#inputTemplateEmail").change(function(){
		if($("#inputTemplateEmail").val() != "none"){
			$("#createEmailBody").removeAttr("disabled")
		} else {
			$("#createEmailBody").attr("disabled",true)
		}
	})

	function createEmailBody(){
		if($("#inputTemplateEmail").val() != "none"){
			$("#sendTicket").show();
			$("#formNewTicket").hide();

			$("#createEmailBody").removeAttr("disabled")

			$.ajax({
				url:"{{url('ticketing/mail/getEmailTemplate')}}",
				data:{
					email_type:$("#inputTypeTicket").val(),
					email_name:$("#inputTemplateEmail").val(),
					email_activity:"Open"
				},
				type:"GET",
				success: function (result){
					$("#bodyOpenMail").html(result);
					$.ajax({
						type:"GET",
						url:"{{url('ticketing/mail/getEmailData')}}",
						data:{
							client:$("#inputClient").val()
						},
						success: function(result){
							if($("#inputTemplateEmail").val() != "Wincor Template"){
								if($("#inputClient option:selected").text().includes("Absensi")){
									var subject = "Open Tiket " + $("#inputAbsenLocation").select2('data')[0].text + " [" + $("#inputProblem").val() +"]"
								} else if($("#inputClient option:selected").text().includes("Switch")){
									var subject = "Open Tiket " + $("#inputSwitchLocation").select2('data')[0].text + " [" + $("#inputProblem").val() +"]"
								} else if ($("#inputTemplateEmail").val() == "ATM Template"){
									var subject = "Permohonan Open Tiket " + $("#inputATM").select2('data')[0].text.split(' -')[0] + " " + result.client_name.split(' - ')[0] + " " + $("#inputLocation").val()
								} else {
									var subject = "Open Tiket " + $("#inputLocation").val() + " [" + $("#inputProblem").val() +"]"
								}
							} else {
								var subject = "#ATC - Permohonan Open Ticket"
							}

							$('.emailMultiSelector').remove()
							$("#emailOpenTo").val(result.open_to)
							$("#emailOpenTo").emailinput({ onlyValidValue: true, delim: ';' });
							$("#emailOpenCc").val(result.open_cc)
							$("#emailOpenCc").emailinput({ onlyValidValue: true, delim: ';' });
							
							$("#emailOpenSubject").val(subject);
							if($("#inputClient option:selected").text().includes("Absensi")){
								$("#emailOpenHeader").html("Dear <b>" + result.open_dear + "</b><br>Berikut terlampir Open Tiket untuk <b>" + $("#inputAbsenLocation").select2('data')[0].text + "</b> : ");
							} else if($("#inputClient option:selected").text().includes("Switch")){
								$("#emailOpenHeader").html("Dear <b>" + result.open_dear + "</b><br>Berikut terlampir Open Tiket untuk <b>" + $("#inputSwitchLocation").select2('data')[0].text + "</b> : ");
							} else if($("#inputTemplateEmail").val() == "ATM Template") {
								$("#emailOpenHeader").html("Dear " + result.open_dear + ", ");
							} else {
								$("#emailOpenHeader").html("Dear <b>" + result.open_dear + "</b><br>Berikut terlampir Open Tiket untuk <b>" + $("#inputLocation").val() + "</b> : ");
							}
							$(".holderCustomer").text(result.client_name.split(' - ')[0]);
						}
					});

					if(!$("#inputATM").val()){
						$("#inputATM").val(" - ");
					} else {
						$(".holderIDATM2").show();
						$(".holderIDATM3").show();
						$(".holderIDATM").text($("#inputATM").select2('data')[0].text.split(' -')[0]);
						$(".holderType").html($("#inputType").val());
					}

					if(!$("#inputSerial").val()){
						$("#inputSerial").val(" - ");
					}

					if(!$("#inputRefrence").val()){
						$("#inputRefrence").val(" - ");
					}

					if(!$("#inputNote").val()){
						$("#inputNote").val(" - ");
					}
					
					var waktu = moment(($("#inputDate").val()), "DD-MMM-YY HH:mm").format("D MMMM YYYY");
					var waktu2 = moment(($("#inputDate").val()), "DD-MMM-YY HH:mm").format("HH:mm");
					var schedule = moment(($("#inputReportingTime").val() + " " + $("#inputReportingDate").val()), "HH:mm:ss DD/MM/YYYY ").format("HH:mm - D MMMM YYYY");

					if($("#inputClient option:selected").text().includes("CCTV")){
						$(".holderIDATM2").insertAfter($(".holderIDATM2").next());
						$(".holderIDATM2 th").text("ATM ID");
						$(".holderIDATM3 th").text("CCTV Type");
						$(".holderSerial").prev().text("CCTV Serial")
						$(".holderSerial").html($("#inputSerial").val());
					} else if ($("#inputClient option:selected").text().includes("UPS")){
						$(".holderIDATM2").insertAfter($(".holderIDATM2").next());
						$(".holderIDATM2 th").text("ATM ID");
						$(".holderIDATM3 th").text("UPS Type");
						$(".holderSerial").prev().text("UPS Serial")
						$(".holderSerial").html($("#inputSerial").val());
					} else {
						if($("#inputTemplateEmail").val() != "Wincor Template"){
							$(".holderSerial").html($("#inputSerial").val());
						} else {
							$(".holderSerial").html($("#inputSerial").val() + ";");
						}
					}

					$(".holderID").text($("#inputticket").val());
					
					$(".holderRefrence").text($("#inputRefrence").val());
					if($("#inputTemplateEmail").val() != "Wincor Template"){
						$("#locationProblem").text($("#inputLocation").val())
						$(".holderPIC").text($("#inputPIC").val());
						$(".holderContact").text($("#inputContact").val());
						$(".holderLocation").text($("#inputLocation").val());
						$(".holderProblem").text($("#inputProblem").val());
						$(".holderType").html($("#inputType").val());
					} else {
						$("#locationProblem").text($("#inputLocation").val() + ";")
						$(".holderPIC").text($("#inputPIC").val() + ";");
						$(".holderContact").text($("#inputContact").val() + ";");
						$(".holderLocation").text($("#inputLocation").val() + ";");
						$(".holderProblem").text($("#inputProblem").val() + ";");
						$(".holderType").html($("#inputType").val() + ";");
					}

					if($("#inputClient option:selected").text().includes("Absensi")){
						$(".holderIDATM3").show();
						$(".holderType").html($("#inputType").val());
						$(".holderIPMechine3").show();
						$(".holderIPMechine4").text($("#inputIpMechine").val());
						$(".holderLocation").text($("#inputAbsenLocation").select2('data')[0].text);
						$(".holderIPServer3").show();
						$(".holderIPServer4").text($("#inputIpServer").val());
					} 

					if($("#inputClient option:selected").text().includes("Switch")){
						$(".holderIDATM3").show();
						$(".holderType").html($("#inputType").val());
						$(".holderIPMechine3").show();
						$(".holderIPMechine4").text($("#inputIpMechine").val());
						$(".holderLocation").text($("#inputSwitchLocation").select2('data')[0].text);
						// $(".holderIPServer3").show();
						// $(".holderIPServer4").text($("#inputIpServer").val());
					}
					

					$(".holderSeverity").text($("#inputSeverity").val());
					$(".holderNote").text($("#inputNote").val());
					
					$(".holderDate").text(waktu);
					$(".holderName").html("{{Auth::user()->name}}")
					$(".holderPhone").html("{{Auth::user()->phone}}")

					$(".holderStatus").html("<b>OPEN</b>");
					$(".holderWaktu").html("<b>" + waktu2 + "</b>");

					if($("#inputTypeTicket").val() == "Preventive Maintenance"){
						var schedule_date = moment(($("#inputReportingTime").val() + " " + $("#inputReportingDate").val()), "HH:mm:ss DD/MM/YYYY ").format("D MMMM YYYY");
						var schedule_time = moment(($("#inputReportingTime").val() + " " + $("#inputReportingDate").val()), "HH:mm:ss DD/MM/YYYY ").format("HH:mm");

						$(".holderStatus").html("<b>" + schedule_date + "</b>");
						$(".holderWaktu").html("<b>" + schedule_time + "</b>");

						$(".holderActivity").html($("#inputProblem").val())
						$(".holderEngineer").html($("#inputEngineerOpen").val())
					}
				}
			})
		}
	}

	function backOpenEmail(){
		$("#sendTicket").hide();
		$("#formNewTicket").show();

		$("#createEmailBody").attr("disabled","true")
	}

	function sendOpenEmail(){
		if($("#emailOpenTo").val() == ""){
			$("#emailOpenTo").parent().parent().addClass("has-error")
			$("#emailOpenTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the email to to open a ticket!",'error');
		} else {
			$("#emailOpenTo").parent().parent().removeClass("has-error")
			$("#emailOpenTo").parent().siblings().last().hide()
			var customerAcronym = $("#inputticket").val().split('/')[1];
			// if(
			// 	customerAcronym == "BJBR" 
			// 	|| customerAcronym == "BSBB" 
			// 	|| customerAcronym == "BRKR" 
			// 	|| customerAcronym == "BJTG" 
			// 	|| customerAcronym == "BDIY"
			// 	){
			// 	var id_atm = $("#inputATM").select2('data')[0].text.split(' -')[0]
			// } else {
				var id_atm = $("#inputATM").val()
			// }

			var typeAlert = 'warning'
			var typeActivity = 'Open'
			var typeAjax = "GET"
			var urlAjax = "{{url('ticketing/mail/sendEmailOpen')}}"
			if ($('#inputAbsenLocation').hasClass("select2-hidden-accessible")) {
				var absen = $("#inputAbsenLocation").select2('data')[0].id
				var location = $("#inputAbsenLocation").select2('data')[0].text
			} else if ($('#inputSwitchLocation').hasClass("select2-hidden-accessible")) {
				var switchLocation = $("#inputSwitchLocation").select2('data')[0].id
				var location = $("#inputSwitchLocation").select2('data')[0].text
			} else {
				var absen = "-";
				var switchLocation = "-";
				var location = $("#inputLocation").val();
			}

			var type_ticket = ""
			var problem = $("#inputProblem").val()
			var engineer = ""
			var severity = $("#inputSeverity").val()
			if($("#inputTypeTicket").val() == "Trouble Ticket"){
				type_ticket = "TT"
			}else if($("#inputTypeTicket").val() == "Preventive Maintenance"){
				type_ticket = "PM"
				engineer = $("#inputEngineerOpen").val()
				severity = "0"
			}else if($("#inputTypeTicket").val() == "Permintaan Layanan"){
				type_ticket = "PL"
			}

			var dataAjax = {
				body:$("#bodyOpenMail").html(),
				subject: $("#emailOpenSubject").val(),
				to: $("#emailOpenTo").val(),
				cc: $("#emailOpenCc").val(),
				attachment: $("#emailOpenAttachment").val(),
				id_ticket:$("#inputticket").val(),

				id:$("#inputID").val(),
				client:$("#inputClient option:selected").text().split(" - ")[0],
				clientID:$("#inputClient").select2('data')[0].id,

				id_atm:id_atm,
				refrence:$("#inputRefrence").val(),
				pic:$("#inputPIC").val(),
				contact_pic:$("#inputContact").val(),
				switchLocation:switchLocation,
				location:location,
				absen:absen,
				problem:problem,
				engineer:engineer,
				serial_device:$("#inputSerial").val(),
				note:$("#inputNote").val(),
				report:moment($("#inputReportingDate").val(),'DD/MM/YYYY').format("YYYY-MM-DD") + " " + moment($("#inputReportingTime").val(),'HH:mm:ss').format("HH:mm:ss.000000"),
				severity:severity,
				type_ticket:type_ticket
			}
			var textSwal = ""
			if($("#emailOpenCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure there is nothing wrong to send this " + typeActivity + " ticket!"
			}
			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#performanceTab").click();
				// $("#modal-cancel").modal('toggle');
				// $("#modal-next-cancel").modal('toggle');
				// $("#modal-ticket").modal('toggle');
			})
		}
	}

	function swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,callback){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: textSwal,
			icon: typeAlert,
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					$.ajax({
						type: typeAjax,
						url: urlAjax,
						data: dataAjax,
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
						success: function(resultAjax){
							Swal.hideLoading()
							swalWithCustomClass.fire({
								title: 'Success!',
								text: typeActivity + " Ticket Sended.",
								icon: 'success',
								confirmButtonText: 'Reload',
							}).then((result) => {
								// console.log(resultAjax)
								callback()
								// getPerformanceByClient(resultAjax.client_acronym_filter)
								// getPerformanceByFilter([resultAjax.client_id_filter],[],[],[])
								$("#clientList").val(resultAjax.client_id_filter).trigger('change')
								// $("#clientList").val(resultAjax.client_id_filter)
								// $("#clientList").trigger('change')
							})
						},
						error: function(resultAjax,errorStatus,errorMessage){
							Swal.hideLoading()
							swalWithCustomClass.fire({
								title: 'Error!',
								text: "Something went wrong, please try again!",
								icon: 'error',
								confirmButtonText: 'Try Again',
							}).then((result) => {
								$.ajax(this)
							})
						}
					});
				}
			}
		);
	}

	var dataTicket = [];

	function getPerformanceAll(){
		if($.fn.dataTable.isDataTable("#tablePerformance")){
			$(".buttonFilter").removeClass('btn-primary').addClass('btn-default')
			$("#tablePerformance").DataTable().ajax.url('{{url('ticketing/getPerformanceAll')}}').load();
		} else {
			$("#tablePerformance").DataTable({
				ajax:{
					type:"GET",
					url:"{{url('ticketing/getPerformanceAll')}}",
					dataSrc: function (json){
						json.data.forEach(function(data,idex){
							data.open_time = moment(data.first_activity_ticket.date,'YYYY-MM-DD, HH:mm:ss').format('D MMMM YYYY HH:mm')
							data.pic = data.pic + ' - ' + data.contact_pic
							if(data.lastest_activity_ticket.activity == "OPEN"){
								data.lastest_status_numerical = 1
								data.lastest_status = '<span class="label label-danger">' + data.lastest_activity_ticket.activity + '</span>'
							} else if(data.lastest_activity_ticket.activity == "ON PROGRESS") {
								data.lastest_status_numerical = 2
								data.lastest_status = '<span class="label label-info">' + data.lastest_activity_ticket.activity + '</span>'
							} else if(data.lastest_activity_ticket.activity == "PENDING") {
								data.lastest_status_numerical = 3
								data.lastest_status = '<span class="label label-warning">' + data.lastest_activity_ticket.activity + '</span>'
							} else if(data.lastest_activity_ticket.activity == "CANCEL") {
								data.lastest_status_numerical = 4
								data.lastest_status = '<span class="label bg-purple">' + data.lastest_activity_ticket.activity + '</span>'
							} else if(data.lastest_activity_ticket.activity == "CLOSE") {
								data.lastest_status_numerical = 5
								data.lastest_status = '<span class="label label-success">' + data.lastest_activity_ticket.activity + '</span>'
							} 
							data.lastest_operator = data.lastest_activity_ticket.operator
							data.action = '<button class="btn btn-default btn-flat btn-sm" onclick="showTicket(' + data.id_detail.id + ')">Detail</button>'
							data.problem = "<b>" + data.location + "</b> - " + data.problem

							if(data.type_ticket == "TT"){
								data.type_ticket = "Trouble Ticket"
							} else if (data.type_ticket == "PM"){
								data.type_ticket = "Preventive Maintenance"
							} else if (data.type_ticket == "PL"){
								data.type_ticket = "Permintaan Layanan"
							}

							if(data.severity == 1){
								data.severity_numerical = 1
								data.severity = '<span class="label bg-red">' + data.type_ticket + ' - Critical</span>'
							} else if(data.severity == 2) {
								data.severity_numerical = 2
								data.severity = '<span class="label bg-orange">' + data.type_ticket + ' - Major</span>'
							} else if(data.severity == 3) {
								data.severity_numerical = 3
								data.severity = '<span class="label bg-custom-yellow">' + data.type_ticket + ' - Moderate</span>'
							} else if(data.severity == 4) {
								data.severity_numerical = 4
								data.severity = '<span class="label bg-green">' + data.type_ticket + ' - Minor</span>'
							} else if(data.severity == 0){
								data.severity_numerical = 0
								data.severity = '<span class="label label-default">' + data.type_ticket + '</span>'
							}
						})
						return json.data
					}
				},
				stateSave: true,
				columns:[
					{
						data:'id_ticket',
						width:"12.5%"
					},
					{ 	
						data:'id_atm',
						className:'text-center',
						width:"5%"
					},
					{
						data:'ticket_number_3party',
						className:'text-center',
						width:"5%"
					},
					{ 
						data:'open_time',
						className:'text-center',
						width:"7%"
					},
					{
						data:'problem',
						// width:"25%"
					},
					{ 
						data:'pic',
						className:'text-center',
						width:"10%"
					},
					// {
					// 	data:'location',
					// 	width:"12%"
					// },
					{ 
						data:'severity',
						className:'text-center',
						orderData:[ 11 ],
						width:"3%"
					},
					{ 
						data:'lastest_status',
						className:'text-center',
						orderData:[ 10 ],
						width:"3%"
					},
					{ 
						data:'lastest_operator',
						className:'text-center',
						width:"3%"
					},
					{
						data:'action',
						className:'text-center',
						orderable: false,
						searchable: true,
						width:"3%"
					},
					{ 
						data: "lastest_status_numerical",
						targets: [ 7 ] ,
						visible: false ,
						searchable: true
					},
					{ 
						data: "severity_numerical",
						targets: [ 6 ] ,
						visible: false ,
						searchable: true
					},
				],
				// order: [[10, "DESC" ]],
				autoWidth:false,
				lengthChange: false,
				searching:true,
				initComplete: function () {
					var condition_available = ["OPEN","ON PROGRESS","PENDING","CANCEL","CLOSE"]
					this.api().columns().every( function () {
						if(this.index() == 8){
							// console.log('every colom data')
							var column = this;
							var select = $('<select class="form-control"><option value="">Show All</option></select>')
								.appendTo( $(column.footer()).empty() )
								.on( 'change', function () {
									var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
									);
									column.search( val ? '^'+val+'$' : '', true, false ).draw();
								} );

							
							column.data().unique().each( function ( d, j ) {
								select.append( '<option value="' + d + '">' + d +'</option>' )
							})
						} else if (this.index() == 7){
							var column = this;
							var select = $('<select class="form-control"><option value="">Show All</option></select>')
								.appendTo( $(column.footer()).empty() )
								.on( 'change', function () {
									var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
									);
									// console.log(val);
									column.search( val ? val : '', true, false ).draw();
								} );

							condition_available.forEach( function ( d, j ) {
								select.append( '<option value="' + d + '">' + d +'</option>' )
							})
						}
					})

					$.each($("#selectShowColumnTicket li input"),function(index,item){
						var column = $("#tablePerformance").DataTable().column(index)
						// column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
						$(item).prop('checked', column.visible())
					})
				},
			})

		}
	}

	$("#clientList").change(function(){
		getPerformanceByFilter($(this).val(),[],[],[])
	})

	$("#severityFilter").change(function(){
		getPerformanceByFilter([],$(this).val(),[],[])
	})

	$("#typeFilter").change(function(){
		getPerformanceByFilter([],[],[],$(this).val())
	})

	$('#dateFilter').daterangepicker({
		ranges: {
			'Today'       : [moment(), moment()],
			'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month'  : [moment().startOf('month'), moment().endOf('month')],
			'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate: moment()
	},
	function (start, end) {
		$('#dateFilter').html("")
		$('#dateFilter').html('<i class="fa fa-calendar"></i> <span>' + start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY') + '</span>');

		var startDay = start.format('YYYY-MM-DD');
		var endDay = end.format('YYYY-MM-DD');

		$("#startDateFilter").val(startDay)
		$("#endDateFilter").val(endDay)

		startDate = start.format('D MMMM YYYY');
		endDate = end.format('D MMMM YYYY');

		getPerformanceByFilter([],[],[{start:start,end:start}],[])
	});

	function getPerformanceByClient(client){
		var client_param = jQuery.param({client: client});
		console.log($.fn.dataTable.isDataTable("#tablePerformance"))

		if($.fn.dataTable.isDataTable("#tablePerformance")){
			if(client == "BTNI"){
				$("#tablePerformance").DataTable().column(1).visible(false)
				$("#tablePerformance").DataTable().column(2).visible(false)
			} else {
				$("#tablePerformance").DataTable().column(1).visible(true)
				$("#tablePerformance").DataTable().column(2).visible(true)
			}
		} else {
			if(client == "BTNI"){
				$(".columnIdAtm").hide()
				$(".columnTicketNum").hide()
			} else {
				$(".columnIdAtm").show()
				$(".columnTicketNum").show()
			}
		}
		
		// $('#clientList').find(".buttonFilter" + client).removeClass('btn-default').addClass('btn-primary')
		// $('#clientList').find(":not(.buttonFilter" + client + ")").removeClass('btn-primary').addClass('btn-default')
		$("#tablePerformance").DataTable().clear().draw();
		$("#tablePerformance").DataTable().ajax.url("{{url('/ticketing/getPerformanceByClient?')}}" + client_param).load();
	}

	function getPerformanceByFilter(client,severity,date,type){
		Pace.restart();
		Pace.track(function() {
			var url_ajax = "{{url('/ticketing/getPerformanceByFilter?')}}"

			if($("#clientList").val().length !== 0){
				var match = false;
				$("#clientList").select2('data').forEach(function(item,index){ 
					if(item.text.includes("Absensi")){
						match = true;
					}
				})
				
				var client_param = jQuery.param({client: $("#clientList").val()});

				if($.fn.dataTable.isDataTable("#tablePerformance")){
					if(match){
						$("#tablePerformance").DataTable().column(1).visible(false)
						$("#tablePerformance").DataTable().column(2).visible(false)
					} else {
						$("#tablePerformance").DataTable().column(1).visible(true)
						$("#tablePerformance").DataTable().column(2).visible(true)
					}
				} else {
					if(match){
						$(".columnIdAtm").hide()
						$(".columnTicketNum").hide()
					} else {
						$(".columnIdAtm").show()
						$(".columnTicketNum").show()
					}
				}
				url_ajax = url_ajax + client_param
			} else if(client.length != 0){
				var client_param = jQuery.param({client: client});
				url_ajax = url_ajax + client_param
			}

			if($("#severityFilter").val().length !== 0){
				var severity_param = jQuery.param({severity: $("#severityFilter").val()});
				url_ajax = url_ajax + "&" + severity_param
			}

			if($("#typeFilter").val().length !== 0){
				var type_param = jQuery.param({type: $("#typeFilter").val()});
				url_ajax = url_ajax + "&" + type_param
			}

			if($("#startDateFilter").val() !== "" && $("#endDateFilter").val() !== ""){
				var date_param = "startDate=" + $("#startDateFilter").val() + "&endDate=" + $("#endDateFilter").val();
				url_ajax = url_ajax + "&" + date_param
			}

			
			// $('#clientList').find(".buttonFilter" + client).removeClass('btn-default').addClass('btn-primary')
			// $('#clientList').find(":not(.buttonFilter" + client + ")").removeClass('btn-primary').addClass('btn-default')
			$("#tablePerformance").DataTable().clear().draw();
			$(".dataTables_empty").text("Please wait, the data is being processed...");
			$("#tablePerformance").DataTable().ajax.url(url_ajax).load();
		})
		Pace.on('done',function(){
			$(".select2-selection__choice[title='TT - Trouble Ticket']").css({"background-color": "#dd4b39","border-color": "#c84231"})
			$(".select2-selection__choice[title='PM - Preventive Maintenance']").css({"background-color": "#dd4b39","border-color": "#c84231"})
			$(".select2-selection__choice[title='PL - Permintaan Layanan']").css({"background-color": "#dd4b39","border-color": "#c84231"})
		})
	}

	function changeNumberEntries(number){
		$("#btnShowEntryTicket").html('Show ' + number + ' <span class="fa fa-caret-down"></span>')
		$("#tablePerformance").DataTable().page.len( number ).draw();
	}

	function changeColumnTable(data){
		// console.log($(data).attr("data-column"))
		var column = $("#tablePerformance").DataTable().column($(data).attr("data-column"))
		// console.log(column.visible())
		column.visible( ! column.visible() );
		// $(data).prop('checked', column.visible())
		// column.visible() ? $(data).addClass('active') : $(data).removeClass('active')
	}

	function showTicket(id){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/getPerformanceByTicket')}}",
			data:{
				idTicket:id,
			},
			success: function(result){
				$("#escalateButton").attr("onclick","escalateTicket('" + result.id_ticket + "')")
				$("#updateButton").attr("onclick","updateTicket('" + result.id_ticket + "')");
				$("#cancelButton").attr("onclick","cancelTicket('" + result.id_ticket + "')");
				$("#pendingButton").attr("onclick","pendingTicket('" + result.id_ticket + "')");
				$("#closeButton").attr("onclick","closeTicket('" + result.id_ticket + "')");

				var severityType = "", severityClass = ""
				if(result.severity == 1){
					severityType = "Critical"
					severityClass = "label label-danger"
				} else if(result.severity == 2){
					severityType = "Major"
					severityClass = "label label-warning"
				} else if(result.severity == 3){
					severityType = "Moderate"
					severityClass = "label label-info"
				} else if(result.severity == 4){
					severityType = "Minor"
					severityClass = "label label-success"
				} else {
					severityType = "N/A"
					severityClass = "label label-default"
				}

				if(result.type_ticket == "TT"){
					ticketType = "Trouble Ticket"
					ticketClass = "label label-danger"
				} else if(result.type_ticket == "PM"){
					ticketType = "Preventive Maintenance"
					ticketClass = "label label-warning"
				} else if(result.type_ticket == "PL"){
					ticketType = "Permintaan Layanan"
					ticketClass = "label label-success"
				} else {
					ticketType = "N/A"
					ticketClass = "label label-default"
				}

				$('#ticketID').val(result.id_ticket);
				$('#ticketOpen').val(moment(result.first_activity_ticket.date).format("D MMMM YYYY (HH:mm)"))
				$("#modal-ticket-title").html("Ticket ID <b>" + result.id_ticket + "</b>");
				$("#ticketOperator").html(" latest by: <b>" + result.lastest_activity_ticket.operator + "</b>");
				$("#ticketSeverity").text(severityType);
				$("#ticketType").text(ticketType);
				$("#ticketSeverity").attr('class',severityClass);
				$("#ticketType").attr('class',ticketClass);

				$("#ticketLatestStatus").text(moment(result.lastest_activity_ticket.date).format('D MMMM YYYY (HH:mm)'));
				$("#ticketStatus").text(result.lastest_activity_ticket.activity);
				$("#ticketStatus").attr('style','');

				$("#ticketIDATM").val(result.id_atm);
				var regex = /<br\s*[\/]?>/gi;
				// $("#mydiv").html(str.replace(regex, "\n"));
				$("#rowGeneral").show()
				$("#rowAbsen").hide()
				$("#ticketLocation").val(result.location);

				if($("#inputClient option:selected").text().includes("CCTV")){
					$("#ticketSerialArea").show()
					$("#ticketSerial").hide()
					$("#ticketSerialArea").val(result.serial_device.substring(0, result.serial_device.length - 4).replace(regex, "\n"));
				} else if (result.id_ticket.split("/")[1] == "BTNI" && result.id_detail.id_client == 29) {
					// $("#ticketSerialArea").show()
					$("#rowAbsen").show()
					$("#rowGeneral").hide()
					// console.log(result.machine_absen == null)
					if(result.machine_absen == null){
						swalWithCustomClass.fire(
							'Absen Machine is not found!',
							'This can happen because the absent machine has been deleted or has been edited.',
							'error'
						)
						$("#ticketIPMachine").val("Not Found")
						$("#ticketIPServer").val("Not Found")
						$("#ticketMachineType").val("Not Found")
						$("#ticketLocation").val("Not Found")
					} else {
						$("#ticketIPMachine").val(result.machine_absen.ip_machine)
						$("#ticketIPServer").val(result.machine_absen.ip_server)
						$("#ticketMachineType").val(result.machine_absen.type_machine)
						$("#ticketLocation").val(result.machine_absen.nama_cabang + " - " + result.machine_absen.nama_kantor)
					}


					// $("#ticketSerialArea").val(result.serial_device.substring(0, result.serial_device.length - 4).replace(regex, "\n"));
				} else {
					$("#ticketSerial").show()
					$("#ticketSerialArea").hide()
					$("#ticketSerial").val(result.serial_device);
				}
				$("#ticketProblem").val(result.problem);
				$("#ticketPIC").val(result.pic + ' - ' + result.contact_pic);

				$("#ticketNote").val("");

				$("#ticketEngineer").val(result.engineer);
				$("#ticketNumber").val(result.ticket_number_3party);

				$("#ticketActivity").empty();
				var textColor = "";
				$.each(result.all_activity_ticket,function(key,value){
					if(value.activity == "PENDING"){
						textColor = 'text-yellow'
					} else if(value.activity == "CLOSE"){
						textColor = 'text-green'
					} else if(value.activity == "OPEN"){
						textColor = 'text-red'
					} else if(value.activity == "CANCEL"){
						textColor = 'text-purple'
					} else if(value.activity == "ON PROGRESS"){
						textColor = 'text-primary'
					} else {
						textColor = ''
					}
					$("#ticketActivity").append('<li><b class="' + textColor + '">' + moment(value.date).format("DD MMMM - HH:mm") + ' [' + value.operator + ']</b><br>' + value.note + '</li>');
				});

				if(result.reporting_time != "Invalid date"){
					$("#ticketActivity").append('<li><b class="text-muted">' + moment(result.reporting_time).format("DD MMMM - HH:mm") + ' - Reporting time</b></li>');
				} else {
					$("#ticketActivity").append('<li><b class="text-muted">' + result.reporting_time + ' - Reporting time</b></li>');
				}

				$(".holderCloseSeverity").text(result.severity + " (" + severityType + ")");
				$(".holderPendingSeverity").text(result.severity + " (" + severityType + ")");
				$(".holderCancelSeverity").text(result.severity + " (" + severityType + ")");

				$("#ticketNoteUpdate").show();

				$("#ticketCouter").hide();
				$("#ticketRoute").hide();

				$("#updatePendingBtn").prop('disabled',true);
				if(result.lastest_activity_ticket.activity == "OPEN"){
					$("#ticketStatus").attr('class','label label-danger');
					
					$("#pendingButton").prop('disabled',true);
					$("#closeButton").prop('disabled',true);
					$("#updateButton").prop('disabled',false);
				} else if(result.lastest_activity_ticket.activity == "PENDING") {
					$("#ticketStatus").attr('class','label label-warning');
					
					$("#pendingButton").prop('disabled',false);
					$("#closeButton").prop('disabled',false);
					$("#updatePendingBtn").prop('disabled',false);
					$('#datePending').datepicker({
						autoclose: true,
						startDate: moment().format("MM/DD/YYYY")
					}).on('hide',function(result){
						$('#datePending').val(moment(result.date).format("DD/MM/YYYY"))
					});
					$('#dateClose').datepicker({
						autoclose: true,
						// startDate: moment(result.first_activity_ticket.date).format("MM/DD/YYYY"),
						endDate: moment().format("MM/DD/YYYY")
					}).on('hide',function(result){
						$('#dateClose').val(moment(result.date).format("DD/MM/YYYY"))
					});
				} else if(result.lastest_activity_ticket.activity == "CLOSE"){
					$("#ticketStatus").attr('class','label label-success');
					
					$("#pendingButton").prop('disabled',true);
					$("#closeButton").prop('disabled',true);
					$("#updateButton").prop('disabled',true);
					$("#cancelButton").prop('disabled',true);
					
					$("#ticketNoteUpdate").hide();
					$("#ticketCouter").show();
					$("#ticketRoute").show();
					$("#ticketCouterTxt").val(result.resolve.counter_measure);
					$("#ticketRouteTxt").val(result.resolve.root_couse);
				} else if(result.lastest_activity_ticket.activity == "ON PROGRESS"){
					$("#ticketStatus").attr('class','label label-info');
					
					$("#updateButton").prop('disabled',false);
					$("#closeButton").prop('disabled',false);
					$("#cancelButton").prop('disabled',false);
					$("#pendingButton").prop('disabled',false);
					$('#datePending').datepicker({
						autoclose: true,
						startDate: moment().format("MM/DD/YYYY")
					}).on('hide',function(result){
						$('#datePending').val(moment(result.date).format("DD/MM/YYYY"))
					});
					$('#dateClose').datepicker({
						autoclose: true,
						// startDate: moment(result.first_activity_ticket.date).format("MM/DD/YYYY"),
						endDate: moment().format("MM/DD/YYYY")
					}).on('hide',function(result){
						$('#dateClose').val(moment(result.date).format("DD/MM/YYYY"))
					});
				} else if(result.lastest_activity_ticket.activity == "CANCEL"){
					$("#ticketStatus").attr('class','label label-purple');
					$("#ticketStatus").attr('style','background-color: #555299 !important;');
					$("#ticketNoteUpdate").hide();
					
					$("#pendingButton").prop('disabled',true);
					$("#closeButton").prop('disabled',true);
					$("#updateButton").prop('disabled',true);
					$("#cancelButton").prop('disabled',true);
				}

				$('#modal-ticket').modal('toggle');
			}
		});
	}

	function updateTicket(id){
		// console.log(id);
		if($("#ticketNote").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'Please give you note before Update!',
				'error'
			)
		} else {
			if($("#ticketStatus").text() == "PENDING"){
				swalWithCustomClass.fire({
					title: 'Are you sure?',
					text: "to continue this ticket from pending?",
					icon: 'warning',
					showCancelButton: true,
				}).then((result) => {
					if(result.value){
						swalWithCustomClass.fire({
							title: 'Would you like?',
							text: "to send an on-progress email to notify the user?",
							icon: 'warning',
							showDenyButton: true,
							denyButton: 'btn btn-flat btn-danger swal2-margin',
							confirmButton: 'btn btn-flat btn-success swal2-margin',
							confirmButtonText: 'Yes',
						}).then((result) => {
							if (result.isConfirmed) {
								$("#ticketNote").val("Continue from pending - " + $("#ticketNote").val())
								onProgressTicket()
							} else if (result.isDenied) {
								updateTicketAjax(moment().format("YYYY-MM-DD HH:mm:ss"))
							}
						})
					}
				})
			} else {
				swalWithCustomClass.fire({
					title: 'Are you sure?',
					text: "Are you sure to update this ticket?",
					icon: 'warning',
					showCancelButton: true,
				}).then((result) => {
					if(result.value){
						updateTicketAjax(moment().format("YYYY-MM-DD HH:mm:ss"))
					}
				})
			}
		}
	}

	function updateTicketAjax(timeOnProgress){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setUpdateTicket')}}",
			data:{
				id_ticket:$('#ticketID').val(),
				ticket_number_3party:$("#ticketNumber").val(),
				engineer:$("#ticketEngineer").val(),
				note:$("#ticketNote").val(),
				timeOnProgress:timeOnProgress
			},
			success: function(result){
				$("#ticketActivity").prepend('<li>' + moment(result.date).format("DD MMMM - HH:mm") + ' [' + result.operator + '] - ' + result.note + '</li>');
				$("#ticketNote").val("")
				$("#ticketStatus").attr('class','label label-info');
				$("#ticketStatus").text('ON PROGRESS')
				$("#updateButton").prop('disabled',false);
				$("#closeButton").prop('disabled',false);
				$("#cancelButton").prop('disabled',false);
				$("#pendingButton").prop('disabled',false);
				$("#modal-ticket").modal('toggle')
				$("#clientList").val(result.client_id_filter).trigger('change')
				// getPerformanceByFilter([result.client_id_filter],[],[],[])
				// getPerformanceByClient(result.client_acronym_filter)
				swalWithCustomClass.fire(
					'Success',
					'Update Completed!',
					'success'
				)
			}
		});
	}

	function onProgressTicket(){
		$(".help-block").hide()
		$.ajax({
			url:"{{url('/ticketing/mail/getOnProgressMailTemplate')}}",
			type:"GET",
			success: function (result){
				$("#bodyOnProgressMail").html(result);
			}
		})

		$.ajax({
			url:"{{url('/ticketing/mail/getEmailData')}}",
			type:"GET",
			data:{
				id_ticket:$('#ticketID').val()
			},
			success: function (result){
				// Holder Cancel
				$(".holderOnProgressID").text($('#ticketID').val());
				$(".holderOnProgressRefrence").text(result.ticket_data.refrence);
				$(".holderOnProgressPIC").text(result.ticket_data.pic);
				$(".holderOnProgressContact").text(result.ticket_data.contact_pic);
				$(".holderOnProgressLocation").text(result.ticket_data.location);
				$(".holderOnProgressProblem").text(result.ticket_data.problem);
				$(".holderOnProgressSerial").text(result.ticket_data.serial_device);
				$(".holderOnProgressSeverity").text(result.ticket_data.severity_detail.id + " (" + result.ticket_data.severity_detail.name + ")")

				$(".holderOnProgressIDATM").text(result.ticket_data.id_atm);

				$(".holderOnProgressNote").text("");
				$(".holderOnProgressEngineer").text(result.ticket_data.engineer);

				var waktu = moment((result.ticket_data.first_activity_ticket.date), "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY (HH:mm)");

				$(".holderOnProgressDate").text(waktu);

				$(".holderOnProgressStatus").html("<b>ON PROGRESS</b>");
				$(".holderOnProgressStatus").html("<b>ON PROGRESS</b>");

				$(".holderNumberTicket").text($("#ticketNumber").val());

				// Email Reciver
				$('.emailMultiSelector ').remove()
				$("#emailOnProgressTo").val(result.ticket_reciver.close_to)
				$("#emailOnProgressTo").emailinput({ onlyValidValue: true, delim: ';' });
				$("#emailOnProgressCc").val(result.ticket_reciver.close_cc)
				$("#emailOnProgressCc").emailinput({ onlyValidValue: true, delim: ';' });

				$("#emailOnProgressSubject").val("On Progress Tiket " + $(".holderOnProgressLocation").text() + " [" + $(".holderOnProgressProblem").text() +"]");
				$("#emailOnProgressHeader").html("Dear <b>" + result.ticket_reciver.close_dear + "</b><br>Berikut terlampir On Progress Tiket untuk <b>" + $(".holderOnProgressLocation").text() + "</b> : ");
				$(".holderOnProgressCustomer").text(result.ticket_reciver.client_name);
				var timeOnProgress = moment()
				$(".holderOnProgressWaktu").html("<b>" + timeOnProgress.format("DD MMMM YYYY (HH:mm)") + "</b>");
				
				if(
					result.ticket_reciver.client_acronym  == "BJBR" || 
					result.ticket_reciver.client_acronym  == "BSBB" || 
					result.ticket_reciver.client_acronym  == "BRKR" || 
					result.ticket_reciver.client_acronym  == "BPRKS" || 
					result.ticket_reciver.client_acronym  == "BDIY"
					){
					$(".holderOnProgressIDATM2").show();
					$(".holderNumberTicket2").show();
				} else {
					$(".holderOnProgressIDATM2").hide();
					$(".holderNumberTicket2").hide();
				}
				$(".holderOnProgressNote").text($("#ticketNote").val());
				$("#sendOnProgressEmail").attr('onclick','sendOnProgressEmail("' + timeOnProgress.format("YYYY-MM-DD HH:mm:ss") + '")')
			},
			complete: function(){
				$("#modal-next-on-progress").modal('toggle');
			}
		})
	}

	function sendOnProgressEmail(timeOnProgress){
		if($("#emailOnProgressTo").val() == ""){
			$("#emailOnProgressTo").parent().parent().addClass("has-error")
			$("#emailOnProgressTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the email to to on progress a ticket!",'error');
		} else {
			$("#emailOnProgressTo").parent().parent().removeClass("has-error")
			$("#emailOnProgressTo").parent().siblings().last().hide()
			var typeAlert = 'warning'
			var typeActivity = 'On Progress'
			var typeAjax = "GET"
			var urlAjax = "{{url('/ticketing/setUpdateTicket')}}"
			var dataAjax = {
				email:"true",
				id_ticket:$('#ticketID').val(),
				ticket_number_3party:$("#ticketNumber").val(),
				engineer:$("#ticketEngineer").val(),
				note:$("#ticketNote").val(),
				timeOnProgress:timeOnProgress,
				subject: $("#emailOnProgressSubject").val(),
				to: $("#emailOnProgressTo").val(),
				cc: $("#emailOnProgressCc").val(),
				body:$("#bodyOnProgressMail").html(),
			}
			var textSwal = ""
			if($("#emailOnProgressCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure there is nothing wrong to send this " + typeActivity + " ticket!"
			}
			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#modal-next-on-progress").modal('toggle');
				$("#modal-ticket").modal('toggle');
			})
		}
	}

	function cancelTicket(id){
		$('#saveReasonCancel').val('')
		$('#modal-cancel').modal('toggle');
	}

	function prepareCancelEmail() {
		if($("#saveReasonCancel").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill cancel reason!',
				'error'
			)
		} else {
			swalWithCustomClass.fire({
				title: 'Are you sure?',
				text: "Are you sure to cancel this ticket?",
				icon: 'warning',
				showCancelButton: true,
			}).then((result) => {
				if (result.value) {
					$(".help-block").hide()
					$.ajax({
						url:"{{url('/ticketing/mail/getCancelMailTemplate')}}",
						type:"GET",
						success: function (result){
							$("#bodyCancelMail").html(result);
						}
					})

					$.ajax({
						url:"{{url('/ticketing/mail/getEmailData')}}",
						type:"GET",
						data:{
							id_ticket:$('#ticketID').val()
						},
						success: function (result){
							// Holder Cancel
							$(".holderCancelID").text($('#ticketID').val());
							$(".holderCancelRefrence").text(result.ticket_data.refrence);
							$(".holderCancelPIC").text(result.ticket_data.pic);
							$(".holderCancelContact").text(result.ticket_data.contact_pic);
							$(".holderCancelLocation").text(result.ticket_data.location);
							$(".holderCancelProblem").text(result.ticket_data.problem);
							$(".holderCancelSerial").text(result.ticket_data.serial_device);
							$(".holderCancelSeverity").text(result.ticket_data.severity_detail.id + " (" + result.ticket_data.severity_detail.name + ")")

							$(".holderCancelIDATM").text(result.ticket_data.id_atm);

							$(".holderCancelNote").text("");
							$(".holderCancelEngineer").text(result.ticket_data.engineer);

							var waktu = moment((result.ticket_data.first_activity_ticket.date), "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY (HH:mm)");

							$(".holderCancelDate").text(waktu);

							$(".holderCancelStatus").html("<b>CANCEL</b>");
							$(".holderNumberTicket").text($("#ticketNumber").val());

							// Email Reciver
							$('.emailMultiSelector ').remove()
							$("#emailCancelTo").val(result.ticket_reciver.close_to)
							$("#emailCancelTo").emailinput({ onlyValidValue: true, delim: ';' });
							$("#emailCancelCc").val(result.ticket_reciver.close_cc)
							$("#emailCancelCc").emailinput({ onlyValidValue: true, delim: ';' });

							$("#emailCancelSubject").val("Cancel Tiket " + $(".holderCancelLocation").text() + " [" + $(".holderCancelProblem").text() +"]");
							$("#emailCancelHeader").html("Dear <b>" + result.ticket_reciver.close_dear + "</b><br>Berikut terlampir Cancel Tiket untuk <b>" + $(".holderCancelLocation").text() + "</b> : ");
							$(".holderCancelCustomer").text(result.ticket_reciver.client_name);

							if(
								result.ticket_reciver.client_acronym  == "BJBR" || 
								result.ticket_reciver.client_acronym  == "BSBB" || 
								result.ticket_reciver.client_acronym  == "BRKR" || 
								result.ticket_reciver.client_acronym  == "BPRKS" || 
								result.ticket_reciver.client_acronym  == "BDIY"
								){
								$(".holderCancelIDATM2").show();
								$(".holderNumberTicket2").show();
							} else {
								$(".holderCancelIDATM2").hide();
								$(".holderNumberTicket2").hide();
							}
							$(".holderCancelNote").text($("#saveReasonCancel").val());
						},
						complete: function(){
							$("#modal-next-cancel").modal('toggle');
						}
					})
				}
			})
		}
	}

	function sendCancelEmail(id){
		if($("#emailCancelTo").val() == ""){
			$("#emailCancelTo").parent().parent().addClass("has-error")
			$("#emailCancelTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the email to to cancel a ticket!",'error');
		} else {
			$("#emailCancelTo").parent().parent().removeClass("has-error")
			$("#emailCancelTo").parent().siblings().last().hide()

			var typeAlert = 'warning'
			var typeActivity = 'Cancel'
			var typeAjax = "GET"
			var urlAjax = "{{url('/ticketing/mail/sendEmailCancel')}}"
			var dataAjax = {
				id_ticket:$('#ticketID').val(),
				subject: $("#emailCancelSubject").val(),
				to: $("#emailCancelTo").val(),
				cc: $("#emailCancelCc").val(),
				note_cancel: $("#saveReasonCancel").val(),
				body:$("#bodyCancelMail").html(),
			}
			var textSwal = ""
			if($("#emailCancelCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure there is nothing wrong to send this " + typeActivity + " ticket!"
			}
			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#modal-cancel").modal('toggle');
				$("#modal-next-cancel").modal('toggle');
				$("#modal-ticket").modal('toggle');
			})
		}
	}

	function pendingTicket(id){
		$("#saveReasonPending").val('')
		$(".help-block").hide()
		if($("#ticketStatus").text() == "PENDING"){
			$.ajax({
				url:"{{'/ticketing/getPendingTicketData'}}",
				data:{
					id_ticket:$('#ticketID').val()
				},
				success:function(result){
					$("#estimationPendingHolder").show()
					$("#timePending").val(moment(result.remind_time).format("hh:mm"))	
					$("#datePending").val(moment(result.remind_time).format("DD/MM/YYYY"))
					$("#estimationPendingText").text(moment(result.remind_time).format("D MMMM YYYY") + " at " + moment(result.remind_time).format("HH:mm"))
					$('#modal-pending').modal('toggle');
				}
			})
		} else {
			$('#modal-pending').modal('toggle');
		}
	}

	function preparePendingEmail(){
		if($("#saveReasonPending").val() == "" && $("#datePending").val() == "" && $("#timePending").val() == ""){
			$("#labelPendingReason, #labelPendingEstimation").addClass('has-error')
			$("#datePending").parent().parent().addClass('has-error')
			$("#timePending").parent().parent().addClass('has-error')
			swalWithCustomClass.fire(
				'Error',
				'You must fill in reason and estimation for make this Pending Ticket!',
				'error'
			)
		} else if($("#saveReasonPending").val() == ""){
			$("#labelPendingReason").addClass('has-error')
			swalWithCustomClass.fire(
				'Error',
				'You must fill in reason for make this Pending Ticket!',
				'error'
			)
		} else if($("#datePending").val() == "" || $("#timePending").val() == ""){
			$("#labelPendingEstimation").addClass('has-error')
			$("#datePending").parent().parent().addClass('has-error')
			$("#timePending").parent().parent().addClass('has-error')
			swalWithCustomClass.fire(
				'Error',
				'You must fill in estimation for make this Pending Ticket!',
				'error'
			)
		} else if (moment($("#datePending").val() + " " + $("#timePending").val() + ":00", "DD/MM/YYYY hh:mm:ss").isBefore(moment())){
			$("#labelPendingEstimation").addClass('has-error')
			$("#datePending").parent().parent().addClass('has-error')
			$("#timePending").parent().parent().addClass('has-error')
			swalWithCustomClass.fire(
				'Error',
				"You set time before now! (Can't backdate)",
				'error'
			)
		} else {
			$("#labelPendingReason, #labelPendingEstimation").removeClass('has-error')
			$("#datePending").parent().parent().removeClass('has-error')
			$("#timePending").parent().parent().removeClass('has-error')
			swalWithCustomClass.fire({
				title: 'Are you sure?',
				text: "Are you sure to pending this ticket?",
				icon: 'warning',
				showCancelButton: true,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url:"{{url('/ticketing/mail/getPendingMailTemplate')}}",
						type:"GET",
						success: function (result){
							$("#bodyPendingMail").html(result);
						}
					})

					$.ajax({
						url:"{{url('/ticketing/mail/getEmailData')}}",
						type:"GET",
						data:{
							id_ticket:$('#ticketID').val()
						},
						success: function (result){
							// Holder Pending

							$(".holderPendingID").text(result.ticket_data.id_ticket);
							$(".holderPendingRefrence").text(result.ticket_data.refrence);
							$(".holderPendingPIC").text(result.ticket_data.pic);
							$(".holderPendingContact").text(result.ticket_data.contact_pic);
							$(".holderPendingLocation").text(result.ticket_data.location);
							$(".holderPendingProblem").text(result.ticket_data.problem);
							$(".holderPendingSerial").text(result.ticket_data.serial_device);
							$(".holderPendingSeverity").text(result.ticket_data.severity_detail.id + " (" + result.ticket_data.severity_detail.name + ")")

							$(".holderPendingIDATM").text(result.ticket_data.id_atm);

							$(".holderPendingNote").text("");
							$(".holderPendingEngineer").text(result.ticket_data.engineer);

							var waktu = moment((result.ticket_data.first_activity_ticket.date), "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY (HH:mm)");

							$(".holderPendingDate").text(waktu);

							$(".holderPendingStatus").html("<b>PENDING</b>");
							$(".holderNumberTicket").text($("#ticketNumber").val());

							// Email Reciver
							$('.emailMultiSelector ').remove()
							$("#emailPendingTo").val(result.ticket_reciver.close_to)
							$("#emailPendingTo").emailinput({ onlyValidValue: true, delim: ';' });
							$("#emailPendingCc").val(result.ticket_reciver.close_cc)
							$("#emailPendingCc").emailinput({ onlyValidValue: true, delim: ';' });

							$("#emailPendingSubject").val("Pending Tiket " + $(".holderPendingLocation").text() + " [" + $(".holderPendingProblem").text() +"]");
							$("#emailPendingHeader").html("Dear <b>" + result.ticket_reciver.close_dear + "</b><br>Berikut terlampir Pending Tiket untuk <b>" + $(".holderPendingLocation").text() + "</b> : ");
							$(".holderPendingCustomer").text(result.ticket_reciver.client_name);

							if(
								result.ticket_reciver.client_acronym  == "BJBR" || 
								result.ticket_reciver.client_acronym  == "BSBB" || 
								result.ticket_reciver.client_acronym  == "BRKR" || 
								result.ticket_reciver.client_acronym  == "BPRKS"  || 
								result.ticket_reciver.client_acronym  == "BDIY" 
								){
								$(".holderPendingIDATM2").show();
								$(".holderNumberTicket2").show();
							} else {
								$(".holderPendingIDATM2").hide();
								$(".holderNumberTicket2").hide();
							}
							$(".holderCancelNote").text($("#saveReasonCancel").val());
							$(".holderPendingNote").text($("#saveReasonPending").val());
						},
						complete: function(){
							$("#modal-next-pending").modal('toggle');
						}
					})
				}
			})
		}
	}

	function updatePending(){
		if($("#saveReasonPending").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill pending reason!',
				'error'
			)
		} else {
			swalWithCustomClass.fire({
				title: 'Are you sure?',
				text: "Are you sure to update this pending?",
				icon: 'warning',
				showCancelButton: true,
			}).then((result) => {
				$.ajax({
					url:"{{url('/ticketing/setUpdateTicketPending')}}",
					data:{
						updatePending:$("#saveReasonPending").val(),
						id_ticket:$('#ticketID').val()
					},
					success:function(resultAjax){
						// swalWithCustomClass.fire(
						// 	'Success',
						// 	'Update Completed!',
						// 	'success'
						// )
						swalWithCustomClass.fire({
							title: 'Success!',
							text: 'Update pending success',
							icon: 'success',
							confirmButtonText: 'Reload',
						}).then((result) => {
							$('#modal-pending').modal('toggle');
							$("#modal-ticket").modal('toggle');
							$("#clientList").val(resultAjax.client_id_filter).trigger('change')
							// getPerformanceByClient(resultAjax)
						})
						
					}
				})
			})
		}
	}

	function sendPendingEmail(){
		if($("#emailPendingTo").val() == ""){
			$("#emailPendingTo").parent().parent().addClass("has-error")
			$("#emailPendingTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the email to to pending a ticket!",'error');
		} else {
			$("#emailPendingTo").parent().parent().removeClass("has-error")
			$("#emailPendingTo").parent().siblings().last().hide()
			var typeAlert = 'warning'
			var typeActivity = 'Pending'
			var typeAjax = "GET"
			var urlAjax = "{{url('/ticketing/mail/sendEmailPending')}}"
			var dataAjax = {
				id_ticket:$('#ticketID').val(),
				subject: $("#emailPendingSubject").val(),
				to: $("#emailPendingTo").val(),
				cc: $("#emailPendingCc").val(),
				note_pending: $("#saveReasonPending").val(),
				body:$("#bodyPendingMail").html(),
				estimationPending:moment($("#datePending").val(),"DD/MM/YYYY").format("DD-MM-YYYY") + " " + $("#timePending").val() + ":00",
			}

			var textSwal = ""
			if($("#emailPendingCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure there is nothing wrong to send this " + typeActivity + " ticket!"
			}

			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#modal-next-pending").modal('toggle');
				$("#modal-pending").modal('toggle');
				$("#modal-ticket").modal('toggle');
			})
		}
	}

	function closeTicket(id){
		$("#saveCloseRoute").val('')
		$("#saveCloseCouter").val('')
		$('#modal-close').modal('toggle');
	}

	function prepareCloseEmail() {
		if($("#saveCloseRoute").val() == "" && $("#saveCloseCouter").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill root cause and counter measure!',
				'error'
			)
		} else if($("#saveCloseCouter").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill counter measure!',
				'error'
			)
		} else if($("#saveCloseRoute").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill root cause!',
				'error'
			)
		} else if($("#timeClose").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill time!',
				'error'
			)
		} else if($("#dateClose").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill date!',
				'error'
			)
		} else {
			swalWithCustomClass.fire({
				title: 'Are you sure?',
				text: "Are you sure to close this ticket?",
				icon: 'warning',
				showCancelButton: true,
			}).then((result) => {
				if (result.value) {
					$(".help-block").hide()
					$.ajax({
						url:"{{url('/ticketing/mail/getCloseMailTemplate')}}",
						type:"GET",
						success: function (result){
							$("#bodyCloseMail").html(result);
						}
					})

					$.ajax({
						url:"{{url('/ticketing/mail/getEmailData')}}",
						type:"GET",
						data:{
							id_ticket:$('#ticketID').val()
						},
						success: function (result){
							// Holder Close

							if(result.ticket_data.type_ticket == "PM"){
								$(".holderCloseProblem").siblings().first().text("Action")
							}

							$(".holderCloseID").text(result.ticket_data.id_ticket);
							$(".holderCloseRefrence").text(result.ticket_data.refrence);
							$(".holderClosePIC").text(result.ticket_data.pic);
							$(".holderCloseContact").text(result.ticket_data.contact_pic);
							$(".holderCloseLocation").text(result.ticket_data.location);
							$(".holderCloseProblem").text(result.ticket_data.problem);
							$(".holderCloseSerial").html(result.ticket_data.serial_device);
							if(result.ticket_data.severity_detail != null){
								$(".holderCloseSeverity").text(result.ticket_data.severity_detail.id + " (" + result.ticket_data.severity_detail.name + ")")
							}
							
							$(".holderCloseIDATM").text(result.ticket_data.id_atm);

							$(".holderCloseNote").text("");
							$(".holderCloseEngineer").text(result.ticket_data.engineer);

							var waktu = moment((result.ticket_data.first_activity_ticket.date), "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY (HH:mm)");

							$(".holderCloseDate").text(waktu);

							$(".holderCloseStatus").html("<b>CLOSE</b>");
							$(".holderNumberTicket").text($("#ticketNumber").val());

							// Email Reciver
							$('.emailMultiSelector ').remove()
							$("#emailCloseTo").val(result.ticket_reciver.close_to)
							$("#emailCloseTo").emailinput({ onlyValidValue: true, delim: ';' });
							$("#emailCloseCc").val(result.ticket_reciver.close_cc)
							$("#emailCloseCc").emailinput({ onlyValidValue: true, delim: ';' });

							$("#emailCloseSubject").val("Close Tiket " + $(".holderCloseLocation").text() + " [" + $(".holderCloseProblem").text() +"]");
							$("#emailCloseHeader").html("Dear <b>" + result.ticket_reciver.close_dear + "</b><br>Berikut terlampir Close Tiket untuk <b>" + $(".holderCloseLocation").text() + "</b> : ");
							$(".holderCloseCustomer").text(result.ticket_reciver.client_name);

							if(result.ticket_reciver.banking == 1){
								$(".holderCloseIDATM2").show();
								$(".holderNumberTicket2").show();
							} else {
								$(".holderCloseIDATM2").hide();
								$(".holderNumberTicket2").hide();
							}

							if(result.ticket_reciver.client_name.includes("UPS")) {
								$(".holderCloseIDATM2").show();
								$(".holderCloseUPSSerial2").show()
								$(".holderCloseUPSSerial").text(result.ticket_data.atm_detail.serial_number)
								$(".holderCloseUPSType2").show()
								$(".holderCloseUPSType").text(result.ticket_data.atm_detail.machine_type)
								$(".holderCloseSerial").parent().hide()	
							} else if (result.ticket_reciver.client_name.includes("CCTV")) {

							}

							$(".holderCloseCounter").text($("#saveCloseCouter").val());
							$(".holderCloseRoot").text($("#saveCloseRoute").val());
							$(".holderCloseWaktu").html("<b>" + moment($("#dateClose").val(),'DD/MM/YYYY').format("DD MMMM YYYY") + " " + moment($("#timeClose").val(),'HH:mm:ss').format("(HH:mm)") + "</b>");
						},
						complete: function(){
							$("#modal-next-close").modal('toggle');
						}
					})
				}
			})
		}
	}

	function sendCloseEmail(){
		if($("#emailCloseTo").val() == ""){
			$("#emailCloseTo").parent().parent().addClass("has-error")
			$("#emailCloseTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the email to to close a ticket!",'error');
		} else {
			$("#emailCloseTo").parent().parent().removeClass("has-error")
			$("#emailCloseTo").parent().siblings().last().hide()
			var typeAlert = 'warning'
			var typeActivity = 'Close'
			var typeAjax = "GET"
			var urlAjax = "{{url('/ticketing/mail/sendEmailClose')}}"
			var dataAjax = {
				id_ticket:$('#ticketID').val(),
				root_cause:$("#saveCloseRoute").val(),
				couter_measure:$("#saveCloseCouter").val(),
				finish:moment($("#dateClose").val(),'DD/MM/YYYY').format("YYYY-MM-DD") + " " + moment($("#timeClose").val(),'HH:mm:ss').format("HH:mm:ss.000000"),
				body:$("#bodyCloseMail").html(),
				subject: $("#emailCloseSubject").val(),
				to: $("#emailCloseTo").val(),
				cc: $("#emailCloseCc").val(),
			}

			var textSwal = ""
			if($("#emailCloseCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure there is nothing wrong to send this " + typeActivity + " ticket!"
			}

			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#modal-next-close").modal('toggle');
				$("#modal-close").modal('toggle');
				$("#modal-ticket").modal('toggle');
			})
		}
	}

	function escalateTicket(id){
		$("#modal-escalate").modal('toggle');
	}

	function prepareEscalateEmail() {
		if($("#escalateRCA").val() == "" && $("#escalateNameEngineer").val() == "" && $("#escalateContactEngineer").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill RCA, Name and Contact Engineer!',
				'error'
			)
		} else if($("#escalateRCA").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill RCA!',
				'error'
			)
		} else if($("#escalateNameEngineer").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill Name Next Engineer!',
				'error'
			)
		} else if($("#escalateContactEngineer").val() == ""){
			swalWithCustomClass.fire(
				'Error',
				'You must fill Contact Next  Engineer!',
				'error'
			)
		} else {
			swalWithCustomClass.fire({
				title: 'Would you like?',
				text: "do you want to send this ecalate email?",
				icon: 'warning',
				showDenyButton: true,
				denyButton: 'btn btn-flat btn-danger swal2-margin',
				confirmButton: 'btn btn-flat btn-success swal2-margin',
				confirmButtonText: 'Yes',
			}).then((result) => {
				if (result.isConfirmed) {
					$(".help-block").hide()
					$.ajax({
						url:"{{url('/ticketing/mail/getEscalateMailTemplate')}}",
						type:"GET",
						success: function (result){
							$("#bodyEscalateMail").html(result);
						}
					})

					$.ajax({
						url:"{{url('/ticketing/mail/getEmailData')}}",
						type:"GET",
						data:{
							id_ticket:$('#ticketID').val()
						},
						success: function (result){
							// Holder Escalate

							$(".holderEscalateID").text(result.ticket_data.id_ticket);
							$(".holderEscalateRefrence").text(result.ticket_data.refrence);
							$(".holderEscalatePIC").text(result.ticket_data.pic);
							$(".holderEscalateContact").text(result.ticket_data.contact_pic);
							$(".holderEscalateLocation").text(result.ticket_data.location);
							$(".holderEscalateLocationHeader").text(result.ticket_data.location);
							$(".holderEscalateProblem").text(result.ticket_data.problem);
							$(".holderEscalateProblemHeader").text(result.ticket_data.problem);
							$(".holderEscalateSerial").text(result.ticket_data.serial_device);
							$(".holderEscalateSeverity").text(result.ticket_data.severity_detail.id + " (" + result.ticket_data.severity_detail.name + ")")
							
							$(".holderEscalateIDATM").text(result.ticket_data.id_atm);

							$(".holderEscalateNote").text("");
							$(".holderEscalateEngineer").text(result.ticket_data.engineer);

							var waktu = moment((result.ticket_data.first_activity_ticket.date), "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY (HH:mm)");

							$(".holderEscalateDate").text(waktu);

							$(".holderEscalateStatus").html("<b>ESCALATE</b>");
							$(".holderNumberTicket").text($("#ticketNumber").val());

							// Email Reciver
							$('.emailMultiSelector ').remove()
							// $("#emailEscalateTo").val(result.ticket_reciver.close_to)
							$("#emailEscalateTo").emailinput({ onlyValidValue: true, delim: ';' });
							// $("#emailEscalateCc").val(result.ticket_reciver.close_cc)
							$("#emailEscalateCc").emailinput({ onlyValidValue: true, delim: ';' });

							$("#emailEscalateSubject").val("Escalate Tiket " + $(".holderEscalateProblem").text() + " [" + $(".holderEscalateLocation").text() +"]");
							
							$(".emailEscalateHeader").html($("#escalateNameEngineer").val());
							$(".holderEscalateNote").html("-");
							$(".holderEscalateRCA").html($("#escalateRCA").val());

							$(".holderEscalateCustomer").text(result.ticket_reciver.client_name);

							if(result.ticket_reciver.client_acronym  == "BJBR" || 
								result.ticket_reciver.client_acronym  == "BSBB" || 
								result.ticket_reciver.client_acronym  == "BRKR" || 
								result.ticket_reciver.client_acronym  == "BPRKS"
								){
								$(".holderEscalateIDATM2").show();
								$(".holderNumberTicket2").show();
							} else {
								$(".holderEscalateIDATM2").hide();
								$(".holderNumberTicket2").hide();
							}

							$(".holderEscalateWaktu").html("<b>" + moment().format("DD MMMM YYYY (HH:mm)") + "</b>");
						},
						complete: function(){
							$("#modal-next-escalate").modal('toggle');
						}
					})
				} else if (result.isDenied) {
					 var dataAjax = {
						id_ticket:$("#ticketID").val(),
						contactEngineer:$("#escalateContactEngineer").val(),
						nameEngineer:$("#escalateNameEngineer").val(),
						rca:$("#escalateRCA").val(),
					}

					swalPopUp(
						"warning",
						"Escalate",
						"GET",
						"{{url('/ticketing/saveEscalate')}}",
						dataAjax,
						"Make sure the RCA, Name and Contact for the next engineer are correct and appropriate.",
						function(){
							$("#modal-escalate").modal('toggle')
							$("#modal-ticket").modal('toggle');

						}
					)
				}
			})
		}
	}

	function sendEscalateEmail(){
		if($("#emailEscalateTo").val() == ""){
			$("#emailEscalateTo").parent().parent().addClass("has-error")
			$("#emailEscalateTo").parent().siblings().last().show()
			swalWithCustomClass.fire('Error',"You have to fill in the 'email to' for escalating a ticket!",'error');
		} else {
			$("#emailEscalateTo").parent().parent().removeClass("has-error")
			$("#emailEscalateTo").parent().siblings().last().hide()

			var typeAlert = 'warning'
			var typeActivity = 'Escalate'
			var typeAjax = "GET"
			var urlAjax = "{{url('/ticketing/mail/sendEmailEscalate')}}"
			var dataAjax = {
				id_ticket:$("#ticketID").val(),
				contactEngineer:$("#escalateContactEngineer").val(),
				nameEngineer:$("#escalateNameEngineer").val(),
				rca:$("#escalateRCA").val(),
				body:$("#bodyEscalateMail").html(),
				subject: $("#emailEscalateSubject").val(),
				to: $("#emailEscalateTo").val(),
				cc: $("#emailEscalateCc").val(),
			}

			var textSwal = ""
			if($("#emailEscalateCc").val() == ""){
				textSwal = "This ticket does not have a CC on the email recipient for this " + typeActivity + " ticket!"
			} else {
				textSwal = "Make sure the name and contact for the next engineer are correct and appropriate."
			}

			swalPopUp(typeAlert,typeActivity,typeAjax,urlAjax,dataAjax,textSwal,function(){
				$("#modal-next-escalate").modal('toggle');
				$("#modal-escalate").modal('toggle');
				$("#modal-ticket").modal('toggle');
			})
		}
	}

	function emailSetting(){
		$(".settingComponent").hide()
		$("#emailSetting").show()
		$("#addEmail2").show()

		if($.fn.dataTable.isDataTable("#tableClient")){

		} else {
			$("#tableClient").DataTable({
				ajax:{
					type:"GET",
					url:"{{url('/ticketing/mail/getSettingEmail')}}",
					dataSrc: function (json){
						json.data.forEach(function(data,idex){
							data.action = '<button type="button" class="btn btn-flat btn-block btn-default" onclick="editClient('+ data.id + ')">Edit</button>'
						})
						return json.data
					}
				},
				columns:[
					{
						data:'client_name',
					},
					{ 	
						data:'client_acronym',
					},
					{
						data:'open_dear',
					},
					{ 
						data:'open_to',
					},
					{ 
						data:'open_cc',
					},
					{ 
						data:'close_dear',
					},
					{ 
						data:'close_to',
					},
					{ 
						data:'close_cc',
					},
					{
						data:'action',
						className:'text-center',
						orderable: false,
						searchable: true,
					}
				],
				// order: [[10, "DESC" ]],
				autoWidth:false,
				lengthChange: false,
				searching:true,
				"processing": true,
				"ColumnDefs":[
			        { targets: 'no-sort', orderable: false }
			    ],
			    "aaSorting": [],
			})
		}
	}

	function editClient(id){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/getSettingClient')}}",
			data: {
				id:id,
			},
			success : function(result){
				$('#modal-setting-email').modal('toggle');
				$("#modal-setting-title").html("Change Setting for <b>" + result[0].client_name + "</b>");

				$("#clientId").val("");
				$("#clientTitle").val("");
				$("#clientAcronym").val("");
				$("#openDear").val("");
				$('.emailMultiSelector ').remove()
				$("#openTo").val("");
				$("#openCc").val("");
				$("#closeDear").val("");
				$("#closeTo").val("");

				$("#clientId").val(id);
				$("#clientTitle").val(result[0].client_name);
				$("#clientAcronym").val(result[0].client_acronym);

				if (result[0].situation == 1) {
					$('#situation').prop('checked', true);
				}

				if (result[0].banking == 1) {
					$('#banking').prop('checked', true);
				}

				if (result[0].wincor == 1) {
					$('#wincor').prop('checked', true);
				}
				
				$("#openDear").val(result[0].open_dear);
				$("#openTo").val(result[0].open_to);
				$("#openTo").emailinput({ onlyValidValue: true, delim: ';' })
				$("#openCc").val(result[0].open_cc);
				$("#openCc").emailinput({ onlyValidValue: true, delim: ';' })

				$("#closeDear").val(result[0].close_dear);
				$("#closeTo").val(result[0].close_to);
				$("#closeTo").emailinput({ onlyValidValue: true, delim: ';' })
				$("#closeCc").val(result[0].close_cc);
				$("#closeCc").emailinput({ onlyValidValue: true, delim: ';' })
			},
		});
	}

	function saveClient(value){
		if (value == 'AddClient') {
			if ($('input#bankingAdd').is(':checked')) {
				$('input#bankingAdd').val(1)
			}else{
				$('input#bankingAdd').val(0)
			}

			if ($('input#wincorAdd').is(':checked')) {
				$('input#wincorAdd').val(1)
			}else{
				$('input#wincorAdd').val(0)
			}

			$.ajax({
				type:"POST",
				url:"{{url('/ticketing/mail/storeAddMail')}}",
				data:{
					"_token": "{{ csrf_token() }}",
					client_name:$("#clientTitleAdd").val(),
					client_acronym:$("#clientAcronymAdd").val(),
					open_dear:$("#openDearAdd").val(),
					open_to:$("#openToAdd").val(),
					open_cc:$("#openCcAdd").val(),
					close_dear:$("#closeDearAdd").val(),
					close_to:$("#closeToAdd").val(),
					close_cc:$("#closeCcAdd").val(),
					banking:$("#bankingAdd").val(),
					wincor:$("#wincorAdd").val()
				},
				success : function(){
					swalWithCustomClass.fire({
						title: 'Success!',
						text: "Email Client Add Successfully!",
						icon: 'success',
						confirmButtonText: 'Reload',
					}).then((result) => {
						$('#modal-add-email').modal('toggle');
						$('#tableClient').DataTable().ajax.url("{{url('/ticketing/mail/getSettingEmail')}}").load();
						// getPerformanceByClient(resultAjax.client_acronym_filter)
						getPerformanceByFilter(resultAjax.client_id_filter,[],[],[])
					})
				}
			});
			
		}else{
			if ($('input#banking').is(':checked')) {
				$('input#banking').val(1)
			}else{
				$('input#banking').val(0)
			}

			if ($('input#wincor').is(':checked')) {
				$('input#wincor').val(1)
			}else{
				$('input#wincor').val(0)
			}

			if ($('input#situation').is(':checked')) {
				$('input#situation').val(1)
			}else{
				$('input#situation').val(0)
			}

			$.ajax({
				type:"POST",
				url:"{{url('/ticketing/setting/setSettingClient')}}",
				data:{
					"_token": "{{ csrf_token() }}",
					id:$("#clientId").val(),
					client_name:$("#clientTitle").val(),
					client_acronym:$("#clientAcronym").val(),
					open_dear:$("#openDear").val(),
					open_to:$("#openTo").val(),
					open_cc:$("#openCc").val(),
					close_dear:$("#closeDear").val(),
					close_to:$("#closeTo").val(),
					close_cc:$("#closeCc").val(),
					banking:$("#banking").val(),
					wincor:$("#wincor").val(),
					situation:$("#situation").val()
				},
				success : function(){
					swalWithCustomClass.fire({
						title: 'Success!',
						text: "Email Client Update Successfully!",
						icon: 'success',
						confirmButtonText: 'Reload',
					}).then((result) => {
						$('#modal-setting-email').modal('toggle');
						$('#tableClient').DataTable().ajax.url("{{url('/ticketing/mail/getSettingEmail')}}").load();
						// getPerformanceByClient(resultAjax.client_acronym_filter)
					})
				}
			});
		}
		
	}

	function atmSetting(){
		$(".settingComponent").hide()
		$("#atmSetting").show()
		$("#addAtm").show()
		$("#addAtm2").show()

		if($.fn.dataTable.isDataTable("#tableAtm")){

		} else {
			$("#tableAtm").DataTable({
				ajax:{
					type:"GET",
					url:"{{url('/ticketing/setting/getAllAtm')}}",
					dataSrc: function (json){
						json.data.forEach(function(data,idex){
							data.action = '<button type="button" class="btn btn-flat btn-block btn-default" onclick="editAtm('+ data.id + ')">Edit</button>'
						})
						return json.data
					}
				},
				columns:[
					{
						data:'owner',
						className:'text-center',
					},
					{ 	
						data:'atm_id',
						className:'text-center',
					},
					{
						data:'serial_number',
						className:'text-center',
					},
					{ 
						data:'location',
						className:'text-center',
					},
					{ 
						data:'activation',
						className:'text-center',
					},
					{
						data:'action',
						className:'text-center',
						orderable: false,
						searchable: true,
					}
				],
				// order: [[10, "DESC" ]],
				autoWidth:false,
				lengthChange: false,
				searching:true,
			})
		}

	}

	function EmailAdd(){
		$("#modal-add-email").modal('toggle');
		$("#openToAdd").emailinput({ onlyValidValue: true, delim: ';' })
		$("#openCcAdd").emailinput({ onlyValidValue: true, delim: ';' })
		$("#closeToAdd").emailinput({ onlyValidValue: true, delim: ';' })
		$("#closeCcAdd").emailinput({ onlyValidValue: true, delim: ';' })
	}

	function atmAdd(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/getParameterAddAtm')}}",
			success:function(result){
				$("#atmAddOwner").empty()
				$.each(result, function (key,value){
					$("#atmAddOwner").append("<option value='" + value.id + "'>(" + value.client_acronym + ") " + value.client_name + "</option>")
				});
			},
			complete: function(){
				$("#modal-setting-atm-add input.form-control, #modal-setting-atm-add textarea.form-control").val("")
				$("#modal-setting-atm-add").modal('toggle');
			}
		});
	}

	$("#atmAddOwner").change(function(){
		if(this.value == 26 || this.value == 27){
			$.ajax({
				type:"GET",
				url:"{{url('/ticketing/create/getAtmId')}}",
				data:{
					acronym:$("#atmAddOwner option:selected").text().split("(")[1].split(")")[0],
				},
				success: function(result){
					$("#ATMadd").show()
					$("#ATMadd").select2({
						data:result
					});
					
					$("#atmAddID").hide()
				}
			});
			if(this.value == 26) {
				$("#peripheralAddFormCCTV, #peripheralAddFormButton").show()
				$("#peripheralAddForm").hide()
			} else {
				$("#peripheralAddForm, #peripheralAddFormButton").show()
				$("#peripheralAddFormCCTV").hide()
			}
			$("#atmAddForm, #atmAddFormButton").hide()
		} else {
			$("#peripheralAddForm, #peripheralAddFormCCTV, #peripheralAddFormButton").hide()
			$("#atmAddForm, #atmAddFormButton").show()
			if($('#ATMadd').hasClass("select2-hidden-accessible")){
				$("#ATMadd").select2('destroy')
			}
			$("#ATMadd").hide()
			$("#atmAddID").show()
		}
	})

	function newAtm(){
		var atmType = ($("#atmAddType").val() == "" ? "-" : $("#atmAddType").val())
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/newAtm')}}",
			data:{
				atmOwner:$("#atmAddOwner").val(),
				atmID:$("#atmAddID").val(),
				atmSerial:$("#atmAddSerial").val(),
				atmLocation:$("#atmAddLocation").val(),
				atmType:atmType,
				atmAddress:$("#atmAddAddress").val(),
				atmActivation:$("#atmAddActivation").val(),
				atmNote:$("#atmAddNote").val(),
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'ATM Added',
						'success'
					)
					$("#modal-setting-atm-add").modal('toggle');
					$("#tableAtm").DataTable().ajax.url("/ticketing/setting/getAllAtm").load();
                }
			},
		})
	}

	function newPeripheral(){
		if($("#atmAddOwner").val() == 26){
			$.ajax({
				type:"GET",
				url:"{{url('/ticketing/setting/newAtmPeripheral')}}",
				data:{
					atmOwner:$("#atmAddOwner").val(),
					atmID:$("#ATMadd").select2('data')[0].text.split(' -')[0],
					peripheralID:"-",
					// peripheralMachineType:$("#atmAddPeripheralType").val(),
					// peripheralSerial:$("#atmAddPeripheralSerial").val(),

					peripheral_cctv_dvr_sn:$("#atmAddPeripheralSerialCCTVDVR").val(),
					peripheral_cctv_dvr_type:$("#atmAddPeripheralTypeCCTVDVR").val(),
					peripheral_cctv_besar_sn:$("#atmAddPeripheralSerialCCTVBesar").val(),
					peripheral_cctv_besar_type:$("#atmAddPeripheralTypeCCTVBesar").val(),
					peripheral_cctv_kecil_sn:$("#atmAddPeripheralSerialCCTVKecil").val(),
					peripheral_cctv_kecil_type:$("#atmAddPeripheralTypeCCTVKecil").val()
				},
				success: function (data){
	            	swalWithCustomClass.fire(
						'Success',
						'ATM CCTV Added',
						'success'
					)
					$("#modal-setting-atm-add").modal('toggle');
					$("#tableAtm").DataTable().ajax.url("/ticketing/setting/getAllAtm").load();
				},
			})
		} else {
			$.ajax({
				type:"GET",
				url:"{{url('/ticketing/setting/newAtmPeripheral')}}",
				data:{
					atmOwner:$("#atmAddOwner").val(),
					atmID:$("#ATMadd").select2('data')[0].text.split(' -')[0],
					peripheralID:"-",
					peripheralMachineType:$("#atmAddPeripheralType").val(),
					peripheralSerial:$("#atmAddPeripheralSerial").val(),
				},
				success: function (data){
	            	swalWithCustomClass.fire(
						'Success',
						'ATM UPS Added',
						'success'
					)
					$("#modal-setting-atm-add").modal('toggle');
					$("#tableAtm").DataTable().ajax.url("/ticketing/setting/getAllAtm").load();
				},
			})
		}
	}

	function editAtm(atm_id){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/getDetailAtm')}}",
			data:{
				id_atm:atm_id
			},
			success:function(result){
				$.each(result.client, function (key,value){
					$("#atmEditOwner").append("<option value='" + value.id + "'>(" + value.client_acronym + ") " + value.client_name + "</option>")
				});
				$("#idEditAtm").val(atm_id);
				$("#atmEditOwner").val(result.atm.owner);
				$("#atmEditID").val(result.atm.atm_id);
				$("#atmEditSerial").val(result.atm.serial_number);
				$("#atmEditLocation").val(result.atm.location);
				$("#atmEditAddress").val(result.atm.address);
				$("#atmEditActivation").val(moment(result.atm.activation,'YYYY-MM-DD').format('DD/MM/YYYY'));
				$("#atmEditNote").val(result.atm.note);
				$("#atmEditType").val(result.atm.machine_type);

				if(result.atm.owner == 19){
					console.log("dasfasdfasd")
					var append = ""
					$.each(result.atm.peripheral,function (key,value){
						if(value.type == "CCTV"){
							if(value.cctv_dvr_type != ""){
								append = append + "<li class='itemPeriperal itemPeriperalEach" + value.id + "-" + 1 + "'>"
								append = append + "<span class='pull-right button-edit-periperal'><button onclick='editAtmPeriperal(" + value.id + "," + 1 + ")' class='btn btn-primary btn-flat btn-xs' type='button'>Edit</button></span>"
								append = append + "<span>"
								append = append + "<b>[" + value.type + " DVR] <span class='itemPeriperalEach" + value.id + "-" + 1 + "-type'>" + value.cctv_dvr_type + "</span></b>"
								append = append + "<br>"
								append = append + "Serial Number : <span class='itemPeriperalEach" + value.id + "-" + 1 + "-sn'>" + value.cctv_dvr_sn + "</span>"
								append = append + "</span>"
							}

							if(value.cctv_besar_type != ""){
								append = append + "</li>"
								append = append + "<li class='itemPeriperal itemPeriperalEach" + value.id + "-" + 2 + "'>"
								append = append + "<span class='pull-right button-edit-periperal'><button onclick='editAtmPeriperal(" + value.id + "," + 2 + ")' class='btn btn-primary btn-flat btn-xs' type='button'>Edit</button></span>"
								append = append + "<span>"
								append = append + "<b>[" + value.type + " Exsternal] <span class='itemPeriperalEach" + value.id + "-" + 2 + "-type'>" + value.cctv_besar_type + "</span></b><br>"
								append = append + "Serial Number : <span class='itemPeriperalEach" + value.id + "-" + 2 + "-sn'>" + value.cctv_besar_sn + "</span>"
								append = append + "</span>"
								append = append + "</li>"
							}
							if(value.cctv_kecil_type != ""){
								append = append + "<li class='itemPeriperal itemPeriperalEach" + value.id + "-" + 3 + "'>"
								append = append + "<span class='pull-right button-edit-periperal'><button onclick='editAtmPeriperal(" + value.id + "," + 3 + ")' class='btn btn-primary btn-flat btn-xs' type='button'>Edit</button></span>"
								append = append + "<span>"
								append = append + "<b>[" + value.type + " Internal] <span class='itemPeriperalEach" + value.id + "-" + 3 + "-type'>" + value.cctv_kecil_type + "</span></b><br>"
								append = append + "Serial Number : <span class='itemPeriperalEach" + value.id + "-" + 3 + "-sn'>" + value.cctv_kecil_sn + "</span>"
								append = append + "</span>"
								append = append + "</li>"
							}
						} else {
							if(value.machine_type != ""){
								append = append + "<li class='itemPeriperal itemPeriperalEach" + value.id + "-" + 4 + "'>"
								append = append + "<span class='pull-right button-edit-periperal'><button onclick='editAtmPeriperal(" + value.id + "," + 4 + ")' class='btn btn-primary btn-flat btn-xs' type='button'>Edit</button></span>"
								append = append + "<span>"
								append = append + "	<b>[" + value.type + "] <span class='itemPeriperalEach" + value.id + "-" + 4 + "-type'>" + value.machine_type + "</span></b><br>"
								append = append + "	Serial Number : <span class='itemPeriperalEach" + value.id + "-" + 4 + "-sn'>" + value.serial_number + "</span>"
								append = append + "</span>"
								append = append + "</li>"
							}
						}
					})
					$("#atmEditPeripheralField").empty()
					$("#atmEditPeripheralField").append(append)
					$("#atmEditPeripheral").show()
				} else {
					$("#atmEditPeripheral").hide()
				}

				$("#modal-setting-atm").modal('toggle');
			}
		});
	}

	function saveAtm(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/setAtm')}}",
			data:{
				idAtm:$("#idEditAtm").val(),
				atmOwner:$("#atmEditOwner").val(),
				atmID:$("#atmEditID").val(),
				atmSerial:$("#atmEditSerial").val(),
				atmLocation:$("#atmEditLocation").val(),
				atmAddress:$("#atmEditAddress").val(),
				atmActivation:$("#atmEditActivation").val(),
				atmType:$("#atmEditType").val(),
				atmNote:$("#atmEditNote").val(),
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'ATM Changed',
						'success'
					)
					$("#modal-setting-atm").modal('toggle');
					$("#tableAtm").DataTable().ajax.url("/ticketing/setting/getAllAtm").load();
                }
			}
		})
	}

	function deleteAtm(){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "To delete this ATM?",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "It's Deleting",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type:"GET",
						url:"{{url('/ticketing/setting/deleteAtm')}}",
						data:{
							idAtm:$("#idEditAtm").val(),
						},
						success: function(resultAjax){
							Swal.hideLoading()
							swalWithCustomClass.fire({
								title: 'Success!',
								text: "ATM Deleted",
								icon: 'success',
								confirmButtonText: 'Reload',
							}).then((result) => {
								$("#modal-setting-atm").modal('toggle');
								$("#tableAtm").DataTable().ajax.url("/ticketing/setting/getAllAtm").load();
							})
						}
					});
				}
			}
		);
	}

	function editAtmPeriperal(id,type){
		// console.log('this is from atm periperal')
		var selector = ".itemPeriperalEach" + id + "-" + type
		
		var cancleHolderPeriperal = "$(selector).html()"

		var append = ""
		append = append + '<li class="itemPeriperalEachEdit' + id + '-' + type + '">'
		append = append + '<span class="pull-right button-edit-periperal">'
		selectorHolder = "'" + selector + "'" 
		holder = "'.itemPeriperalEachEdit" + id + "-" + type + "'"
		append = append + '	<button onclick="saveAtmPeriperal(' + selectorHolder +  ',' + holder + ',' + id + ',' + type +')" class="btn btn-success btn-flat btn-xs" type="button">Save</button>'
		append = append + '	<button onclick="deleteAtmPeriperal(' + selectorHolder +  ',' + holder + ',' + id + ',' + type +')" class="btn btn-danger btn-flat btn-xs" type="button">Delete</button>'
		append = append + '	<button onclick="cancelAtmPeriperal(' + selectorHolder +  ',' + holder + ',' + id + ',' + type +')" class="btn btn-default btn-flat btn-xs" type="button">Cancel</button>'
		append = append + '</span>'
		append = append + '<span>'
		type = (type == 1 ? "CCTV DVR" : (type == 2 ? "CCTV Internal" : (type == 3 ? "CCTV Eksternal" : "UPS")))
		append = append + '	<b>[' + type + ']</b> <input type="text" class="from-control editPeripheralType" value="' + $(selector + "-type").text() + '"><br>'
		append = append + '	Serial Number : <input type="text" class="from-control editPeripheralSerial" value="' + $(selector + "-sn").text() + '">'
		append = append + '</span>'
		append = append + '</li>'
		$(selector).hide()
		$(append).insertAfter(selector)
	}

	function cancelAtmPeriperal(selector, holder){
		$(selector).show()
		$(holder).remove()
	}

	function saveAtmPeriperal(selector,holder,id,type){
		console.log($(holder + " input.editPeripheralType").val())
		console.log($(holder + " input.editPeripheralSerial").val())
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "Make sure there is nothing wrong from editing this preriperal!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No'
		}).then((result) => {
			if (result.value){
				Swal.fire({
					title: 'Please Wait..!',
					text: "It's editing..",
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						popup: 'border-radius-0',
					},
					onOpen: () => {
						Swal.showLoading()
					}
				})
				$.ajax({
					type: "GET",
					url: "{{url('/ticketing/setting/editAtmPeripheral')}}",
					data: {
						id:id,
						type:type,
						typeEdit:$(holder + " input.editPeripheralType").val(),
						serialEdit:$(holder + " input.editPeripheralSerial").val(),
					},
					success: function(resultAjax){
						Swal.hideLoading()
						swalWithCustomClass.fire({
							title: 'Success!',
							text: "Periperal save.",
							icon: 'success',
							confirmButtonText: 'Reload',
						}).then((result) => {
							$(selector).show()
							$(selector + "-type").text($(holder + " input.editPeripheralType").val())
							$(selector + "-sn").text($(holder + " input.editPeripheralSerial").val())
							$(holder).remove()
						})
					}
				});
			}
		})
	}

	function deleteAtmPeriperal(selector,holder,id,type){
		console.log($(holder + " input.editPeripheralType").val())
		console.log($(holder + " input.editPeripheralSerial").val())
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "Make sure there is nothing wrong to delete this preriperal!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No'
		}).then((result) => {
			if (result.value){
				Swal.fire({
					title: 'Please Wait..!',
					text: "It's deleting..",
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customClass: {
						popup: 'border-radius-0',
					},
					onOpen: () => {
						Swal.showLoading()
					}
				})
				$.ajax({
					type: "GET",
					url: "{{url('/ticketing/setting/deleteAtmPeripheral')}}",
					data: {
						id:id,
						type:type
					},
					success: function(resultAjax){
						Swal.hideLoading()
						swalWithCustomClass.fire({
							title: 'Success!',
							text: "Periperal deleted.",
							icon: 'success',
							confirmButtonText: 'Reload',
						}).then((result) => {
							$(selector).remove()
							$(holder).remove()
						})
					}
				});
			}
			
		})
	}

	function absenSetting(){
		$(".settingComponent").hide()
		$("#absenSetting").show()
		$("#addAbsen").show()
		$("#addAbsen2").show()

		if($.fn.dataTable.isDataTable("#tableAbsen")){

		} else {
			$("#tableAbsen").DataTable({
				ajax:{
					type:"GET",
					url:"{{url('/ticketing/setting/getAllAbsen')}}",
					dataSrc: function (json){
						json.data.forEach(function(data,idex){
							data.action = '<button type="button" class="btn btn-flat btn-block btn-default" onclick="editAbsen(' + data.id + ')">Edit</button>'
						})
						return json.data
					}
				},
				columns:[
					{
						data:'nama_cabang',
						className:'text-center',
					},
					{ 	
						data:'nama_kantor',
						className:'text-center',
					},
					{
						data:'type_machine',
						className:'text-center',
					},
					{ 
						data:'ip_machine',
						className:'text-center',
					},
					{ 
						data:'ip_server',
						className:'text-center',
					},
					{
						data:'action',
						className:'text-center',
						orderable: false,
						searchable: true,
					}
				],
				// order: [[10, "DESC" ]],
				autoWidth:false,
				lengthChange: false,
				searching:true,
			})
		}
	}

	function absenAdd(){
		$("#modal-setting-absen-add input.form-control").val("")
		$("#modal-setting-absen-add").modal('toggle');
	}

	function newAbsen(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/newAbsen')}}",
			data:{
				absenAddNamaCabang:$("#absenAddNamaCabang").val(),
				absenAddNamaKantor:$("#absenAddNamaKantor").val(),
				absenAddMachineType:$("#absenAddMachineType").val(),
				absenAddIPMachine:$("#absenAddIPMachine").val(),
				absenAddIPServer:$("#absenAddIPServer").val()
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'Absen Added',
						'success'
					)
					$("#modal-setting-absen-add").modal('toggle');
					$("#tableAbsen").DataTable().ajax.url("/ticketing/setting/getAllAbsen").load();
                }
			},
		})
	}

	function editAbsen(absen_id){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/getDetailAbsen')}}",
			data:{
				id_absen:absen_id
			},
			success:function(result){
				$("#idEditAbsen").val(result.absen.id)
				$("#absenEditNamaCabang").val(result.absen.nama_cabang)
				$("#absenEditNamaKantor").val(result.absen.nama_kantor)
				$("#absenEditMachineType").val(result.absen.type_machine)
				$("#absenEditIPMachine").val(result.absen.ip_machine)
				$("#absenEditIPServer").val(result.absen.ip_server)

				$("#modal-setting-absen").modal('toggle');
			}
		});
	}

	function saveAbsen(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/setAbsen')}}",
			data:{
				idAbsen:$("#idEditAbsen").val(),
				absenEditNamaCabang:$("#absenEditNamaCabang").val(),
				absenEditNamaKantor:$("#absenEditNamaKantor").val(),
				absenEditMachineType:$("#absenEditMachineType").val(),
				absenEditIPMachine:$("#absenEditIPMachine").val(),
				absenEditIPServer:$("#absenEditIPServer").val()
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'Absen Changed',
						'success'
					)
					$("#modal-setting-absen").modal('toggle');
					$("#tableAbsen").DataTable().ajax.url("/ticketing/setting/getAllAbsen").load();
                }
			}
		})
	}

	function deleteAbsen(){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "To delete this Absen?",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "It's Deleting",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type:"GET",
						url:"{{url('/ticketing/setting/deleteAbsen')}}",
						data:{
							idAbsen:$("#idEditAbsen").val(),
						},
						success: function(resultAjax){
							Swal.hideLoading()
							swalWithCustomClass.fire({
								title: 'Success!',
								text: "Absen Deleted",
								icon: 'success',
								confirmButtonText: 'Reload',
							}).then((result) => {
								$("#modal-setting-absen").modal('toggle');
								$("#tableAbsen").DataTable().ajax.url("/ticketing/setting/getAllAbsen").load();
							})
						}
					});
				}
			}
		);
	}

	$('#daterange-btn').daterangepicker(
		{
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			startDate: moment().subtract(29, 'days'),
			endDate: moment()
		},
		function (start, end) {
			$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			$("#ReportingButtonGoNew").show()
		}
	);

	$('#daterange-btn2').daterangepicker(
		{
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			startDate: moment().subtract(29, 'days'),
			endDate: moment()
		},
		function (start, end) {
			$('#daterange-btn2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			$("#ReportingButtonGoNew2").show()
		}
	);

	function switchSetting(){
		$(".settingComponent").hide()
		$("#switchSetting").show()
		$("#addSwitch").show()
		$("#addSwitch2").show()

		if($.fn.dataTable.isDataTable("#tableSwitch")){

		} else {
			$("#tableSwitch").DataTable({
				ajax:{
					type:"GET",
					url:"{{url('/ticketing/setting/getAllSwitch')}}",
					dataSrc: function (json){
						json.data.forEach(function(data,idex){
							data.action = '<button type="button" class="btn btn-flat btn-block btn-default" onclick="editSwitch(' + data.id + ')">Edit</button>'
						})
						return json.data
					}
				},
				columns:[
					{
						data:'location',
						className:'text-center',
					},
					{ 	
						data:'cabang',
						className:'text-center',
					},
					{
						data:'type',
						className:'text-center',
					},
					{ 
						data:'port',
						className:'text-center',
					},
					{ 
						data:'serial_number',
						className:'text-center',
					},
					{ 
						data:'ip_management',
						className:'text-center',
					},
					{
						data:'action',
						className:'text-center',
						orderable: false,
						searchable: true,
					}
				],
				// order: [[10, "DESC" ]],
				autoWidth:false,
				lengthChange: false,
				searching:true,
			})
		}
	}

	function switchAdd(){
		$("#modal-setting-switch-add input.form-control").val("")
		$("#modal-setting-switch-add").modal('toggle');
	}

	function newSwitch(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/newSwitch')}}",
			data:{
				switchAddType:$("#switchAddType").val(),
				switchAddPort:$("#switchAddPort").val(),
				switchAddSerialNumber:$("#switchAddSerialNumber").val(),
				switchAddIPManagement:$("#switchAddIPManagement").val(),
				switchAddLocation:$("#switchAddLocation").val(),
				switchAddCabang:$("#switchAddCabang").val(),
				switchAddNote:$("#switchAddNote").val()
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'Absen Added',
						'success'
					)
					$("#modal-setting-switch-add").modal('toggle');
					$("#tableSwitch").DataTable().ajax.url("/ticketing/setting/getAllSwitch").load();
                }
			},
		})
	}

	function editSwitch(switch_id){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/getDetailSwitch')}}",
			data:{
				id_switch:switch_id
			},
			success:function(result){
				$("#switchEditType").val(result.switch.type)
				$("#switchEditPort").val(result.switch.port)
				$("#switchEditSerialNumber").val(result.switch.serial_number)
				$("#switchEditIPManagement").val(result.switch.ip_management)
				$("#switchEditLocation").val(result.switch.location)
				$("#switchEditCabang").val(result.switch.cabang)
				$("#switchEditNote").val(result.switch.note)
				$("#idEditSwitch").val(result.switch.id)

				$("#modal-setting-switch").modal('toggle');
			}
		});
	}

	function saveSwitch(){
		$.ajax({
			type:"GET",
			url:"{{url('/ticketing/setting/setSwitch')}}",
			data:{
				idSwitch:$("#idEditSwitch").val(),
				switchEditType:$("#switchEditType").val(),
				switchEditPort:$("#switchEditPort").val(),
				switchEditSerialNumber:$("#switchEditSerialNumber").val(),
				switchEditIPManagement:$("#switchEditIPManagement").val(),
				switchEditLocation:$("#switchEditLocation").val(),
				switchEditCabang:$("#switchEditCabang").val(),
				switchEditNote:$("#switchEditNote").val(),
			},
			success: function (data){
				if(!$.isEmptyObject(data.error)){
					var errorMessage = ""
					data.error.forEach(function(data,index){
						errorMessage = errorMessage + data + "<br>";
					})
                    swalWithCustomClass.fire(
						'Error',
						errorMessage,
						'error'
					)
                } else {
                	 swalWithCustomClass.fire(
						'Success',
						'Absen Changed',
						'success'
					)
					$("#modal-setting-switch").modal('toggle');
					$("#tableSwitch").DataTable().ajax.url("/ticketing/setting/getAllSwitch").load();
                }
			}
		})
	}

	function deleteSwitch(){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "To delete this Switch?",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "It's Deleting",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type:"GET",
						url:"{{url('/ticketing/setting/deleteSwitch')}}",
						data:{
							idSwitch:$("#idEditSwitch").val(),
						},
						success: function(resultAjax){
							Swal.hideLoading()
							swalWithCustomClass.fire({
								title: 'Success!',
								text: "Switch Deleted",
								icon: 'success',
								confirmButtonText: 'Reload',
							}).then((result) => {
								$("#modal-setting-switch").modal('toggle');
								$("#tableSwitch").DataTable().ajax.url("/ticketing/setting/getAllSwitch").load();
							})
						}
					});
				}
			}
		);
	}

	$.ajax({
		type:"GET",
		url:"{{url('/ticketing/report/getParameter')}}",
		success:function(result){
			$("#selectReportingClient").append("<option>Select Client</option>")
			$("#selectReportingMonth").append("<option>Select Month</option>")
			$("#selectReportingYear").append("<option>Select Year</option>")

			result.client_data.forEach(function(data,index){
				$("#selectReportingClient").append("<option value='" + data.id + "'>[" + data.client_acronym + "] " + data.client_name + "</option>")
			})
			result.ticket_year.forEach(function(data,index){
				$("#selectReportingYear").append("<option value='" + data.year + "'>" + data.year + "</option>")
			})
			moment.months().forEach(function(data,index){
				if(index < moment().format('M')){
					$("#selectReportingMonth").append("<option value='" + index + "'>" + data + "</option>")
				}
			})
		}
	})

	$("#selectReportingType").change(function(){
		$("#ReportingButtonGo, #ReportingButtonGoNew, #ReportingButtonGoNew2").hide()
		if($(this).val() == 1){
			$(".finish-report").show()
			$(".bayu-report").hide()
			$(".denny-report").hide()
		} else if($(this).val() == 2) {
			$(".finish-report").hide()
			$(".bayu-report").show()
			$(".denny-report").hide()
		} else if($(this).val() == 3){
			$(".finish-report").hide()
			$(".bayu-report").hide()
			$(".denny-report").show()
		}
	})

	$("#selectReportingClient, #selectReportingYear, #selectReportingMonth").change(function(){
		if($("#selectReportingClient").val() !== "Select Client" && $("#selectReportingYear").val() !== "Select Year"  && $("#selectReportingMonth").val() !== "Select Month"){
			console.log($("#selectReportingClient").val())
			console.log($("#selectReportingYear").val())
			console.log($("#selectReportingMonth").val())
			
			var urlAjax = '{{url("/ticketing/report/make")}}?client=' + $("#selectReportingClient").val() + '&year=' + $("#selectReportingYear").val() + '&month=' + $("#selectReportingMonth").val()
			$("#ReportingButtonGo").attr('onclick',"getReport('" + urlAjax + "')")
			$("#ReportingButtonGo").show()
		}
		if ($("#selectReportingYear").val() !== moment().format('YYYY') && $("#selectReportingYear").val() !== "Select Year"){
			console.log('true')
			$("#selectReportingMonth").empty()
			$("#selectReportingMonth").append("<option>Select Month</option>")
			moment.months().forEach(function(data,index){
				$("#selectReportingMonth").append("<option value='" + index + "'>" + data + "</option>")
			})
		} else if ($("#selectReportingYear").val() === moment().format('YYYY')){
			console.log('false')
			$("#selectReportingMonth").empty()
			$("#selectReportingMonth").append("<option>Select Month</option>")
			moment.months().forEach(function(data,index){
				if(index < moment().format('M')){
					$("#selectReportingMonth").append("<option value='" + index + "'>" + data + "</option>")
				}
			})
		}
	})

	function getReport(urlAjax){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "Make sure there is nothing wrong to get this report ticket!",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "Prossesing Data Report",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type: "GET",
						url: urlAjax,
						success: function(result){
							Swal.hideLoading()
							if(result == 0){
								swalWithCustomClass.fire({
									//icon: 'error',
									title: 'Success!',
									text: "The file is unavailable",
									type: 'error',
									//confirmButtonText: '<a style="color:#fff;" href="report/' + result.slice(1) + '">Get Report</a>',
								})
							}else{
								swalWithCustomClass.fire({
									title: 'Success!',
									text: "You can get your file now",
									type: 'success',
									confirmButtonText: '<a style="color:#fff;" href="report/' + result + '">Get Report</a>',
								})
							}
						}
					});
				}
			}
		);
	}

	$("#ReportingButtonGoNew").on('click',function(){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "Make sure there is nothing wrong to get this report bayu!",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "Prossesing Data Report",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type:"GET",
						url:"{{url('/ticketing/report/new')}}",
						data:{
							start:$('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD'),
							end:$('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD')
						},
						success: function(result){
							Swal.hideLoading()
							if(result == 0){
								swalWithCustomClass.fire({
									//icon: 'error',
									title: 'Success!',
									text: "The file is unavailable",
									type: 'error',
									//confirmButtonText: '<a style="color:#fff;" href="report/' + result.slice(1) + '">Get Report</a>',
								})
							}else{
								swalWithCustomClass.fire({
									title: 'Success!',
									text: "You can get your file now",
									type: 'success',
									confirmButtonText: '<a style="color:#fff;" href="report/bayu/' + result + '">Get Report</a>',
								})
							}
						}
					})
				}
			}
		);
	})

	$("#ReportingButtonGoNew2").on('click',function(){
		swalWithCustomClass.fire({
			title: 'Are you sure?',
			text: "Make sure there is nothing wrong to get this report denny!",
			icon: "warning",
			showCancelButton: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			}).then((result) => {
				if (result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "Prossesing Data Report",
						allowOutsideClick: false,
						allowEscapeKey: false,
						allowEnterKey: false,
						customClass: {
							popup: 'border-radius-0',
						},
						onOpen: () => {
							Swal.showLoading()
						}
					})

					$.ajax({
						type:"GET",
						url:"{{url('/ticketing/report/newDeny')}}",
						data:{
							start:$('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD 00:00:00'),
							end:$('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD 23:59:59')
						},
						success: function(result){
							Swal.hideLoading()
							if(result == 0){
								swalWithCustomClass.fire({
									//icon: 'error',
									title: 'Success!',
									text: "The file is unavailable",
									type: 'error',
									//confirmButtonText: '<a style="color:#fff;" href="report/' + result.slice(1) + '">Get Report</a>',
								})
							}else{
								swalWithCustomClass.fire({
									title: 'Success!',
									text: "You can get your file now",
									type: 'success',
									confirmButtonText: '<a style="color:#fff;" href="report/denny/' + result + '">Get Report</a>',
								})
							}
						}
					})
				}
			}
		);
	})


</script>

@endsection