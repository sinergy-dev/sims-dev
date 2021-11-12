<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		@hasSection('tittle')
			SIMS - @yield('tittle')
		@else
			SIP - SIMS
		@endif
	</title>
	<link rel="icon" type="image/png" href="{{url('img/siplogooke.png')}}">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">	
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	
	@yield('head_css')
	<link rel="stylesheet" href="{{url('css/sims-custom.css')}}">
	
	<!-- Theme style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/AdminLTE.min.css">
	@yield('head_css_and')
	<!-- AdminLTE Skins -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/skins/skin-blue.min.css">
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
@if(isset($sidebar_collapse))
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
@else
<body class="hold-transition skin-blue sidebar-mini">
@endif

	<div class="wrapper">
	@section('header')
	@include('template.header')
	@show

@section('sidebar')
@include('template.sidebar')
@show

		<div class="content-wrapper">
			@yield('content')
		</div>

		@section('footer')
		@include('template.footer')
		@show
	</div>

		<!-- jQuery 3.1.1 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
		@section('scriptNotificationHeader')
		@show
		@section('scriptNotificationSidebar')
		@show
		@yield('scriptImport')

		<!-- SlimScroll -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/js/adminlte.min.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/js/demo.js"></script>

		<script>
			$(document).ready(function () {
				$(".activeable_group").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
				$(".activeable_menu").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
			})
		</script>
		@yield('script')
</body>
</html>
