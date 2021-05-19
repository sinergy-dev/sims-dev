@extends('template.main')
@section('content')
	<section class="content-header">
		<h1>
			Presence
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
				<a href="{{url('presence')}}">Personal</a>
			</li>
			@else
			<li>
				<a href="{{url('presence/history/personal')}}">
					<i class="fa fa-user"></i>Personal History
				</a>
			</li>
			@endrole
		</ol>
	</section>

	<section class="content">
		<div class="box">
			<div class="box-body">
			</div>
		</div> 
	</section>
@endsection
@section('script')
@endsection