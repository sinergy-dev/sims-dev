@extends('template.main')

{{-- Tittle harus di descripsikan sesuai dengan menu yang di buka --}}
@section('tittle')
Ticketing
@endsection

@section('head_css')
	<style type="text/css">
		/*Buat style bisa mulai di tulis di sini*/
		h1 {
			color: red;
		}
	</style>
@endsection

@section('content')
	<!-- Content Header bisa di isi dengan Title Menu dan breadcrumb -->
	<section class="content-header">
		<h1>
			Tiltle Menu
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="{{url('dashboard')}}">
					<i class="fa fa-fw fa-dashboard"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="#">Example Page</a>
			</li>
			<li class="active">
				<a href="{{url('/')}}">Blank Page</a>
			</li>
		</ol>
	</section>

	<!-- Untuk Content bisa dimulai dengan box -->
	<section class="content">
		<div class="box">
			<div class="box-body">
				<h1>This is Box Body</h1>
			</div>
		</div> 
	</section>
@endsection

@section('scriptImport')
<!-- Script yang import dari CDN ato Local ada di sini -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
@endsection

@section('script')
<script type="text/javascript">
	// Script yang import dari CDN ato Local ada di sini
	console.log('Hi')
</script>
@endsection