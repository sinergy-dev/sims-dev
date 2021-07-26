@extends('template.main')
@section('hahaha')
- Ticketing
@endsection

@section('head_css')
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
					<a href="#dashboard" data-toggle="tab">Dashboard</a>
				</li>
				<li>
					<a href="#create" data-toggle="tab">Create</a>
				</li>
				<li>
					<a href="#performance" data-toggle="tab">Performance</a>
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
					Dashboard
				</div>

				<div class="tab-pane" id="create">
					Create
				</div>

				<div class="tab-pane" id="performance">
					Performance
				</div>

				<div class="tab-pane" id="setting">
					Setting
				</div>

				<div class="tab-pane" id="reporting">
					Reporting
				</div>
			</div>
		</div>
	</section>
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
@endsection
@section('script')
@endsection

@section('scriptImport2')
@parent
<!-- Bootstrap 3.3.7 hahahahaha -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
@endsection