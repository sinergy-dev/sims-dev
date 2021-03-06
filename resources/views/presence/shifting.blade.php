@extends('template.template_admin-lte')
@section('head_css')
@endsection
@section('content')
	<section class="content-header">
		<h1>
			Presence Shifting
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
				<a href="{{url('presence/shifting')}}">Shifting</a>
			</li>
			@endrole
		</ol>
	</section>

	<section class="content">
		<div class="box">
			<div class="box-body">
				<p>To Be Added</p>
			</div>
		</div> 
	</section>
@endsection
@section('script')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	
@endsection