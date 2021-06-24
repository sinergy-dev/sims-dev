<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIP - SIMSApp</title>
	<link rel="icon" type="image/png" href="../img/siplogooke.png">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">	
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	
	@yield('head_css')
	<style type="text/css">
		.loader {
			border: 16px solid #f3f3f3;
			border-radius: 50%;
			border-top: 16px solid blue;
			border-right: 16px solid green;
			border-bottom: 16px solid red;
			border-left: 16px solid pink;
			width: 120px;
			height: 120px;
			-webkit-animation: spin 2s linear infinite;
			animation: spin 2s linear infinite;
		}

		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		.dropbtn {
			background-color: #4CAF50;
			color: white;
			font-size: 12px;
			border: none;
			width: 140px;
			height: 30px;
			border-radius: 5px;
		}

		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f1f1f1;
			min-width: 140px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			z-index: 1;
		}


		.dropdown-content .year:hover {background-color: #ddd;}

		.dropdown:hover .dropdown-content {display: block;}

		.dropdown:hover .dropbtn {background-color: #3e8e41;}

		.transparant-filter{
			background-color: Transparent;
			background-repeat:no-repeat;
			border: none;
			cursor:pointer;
			overflow: hidden;
			outline:none;
		}

		.transparant{
			background-color: Transparent;
			background-repeat:no-repeat;
			border: none;
			cursor:pointer;
			overflow: hidden;
			outline:none;
			width: 25px;
		}
		.alert-box {
			color:#555;
			border-radius:10px;
			font-family:Tahoma,Geneva,Arial,sans-serif;font-size:14px;
			padding:10px 36px;
			margin:10px;
		}
		.alert-box span {
			font-weight:bold;
			text-transform:uppercase;
		}
		.error {
			background:#ffecec;
			border:1px solid #f5aca6;
		}
		.success {
			background:#e9ffd9 ;
			border:1px solid #a6ca8a;
		}
		.warning {
			background:#fff8c4 ;
			border:1px solid #f2c779;
		}
		.notice {
			background:#e3f7fc;
			border:1px solid #8ed9f6;
		}
		div div ol li a{
			font-size: 14px;
		}

		div div i{
			font-size: 14px;
		}

		color:#fff;
			background-color:dodgerBlue;
		}

		.inputWithIconn.inputIconBg i{
			background-color:#aaa;
			color:#fff;
			padding:7px 4px;
			border-radius:4px 0 0 4px;
		}

		.inputWithIconn{
			position:relative;
		}

		.inputWithIconn i{
			position:absolute;
			left:0;
			top:28px;
			padding:9px 8px;
			color:#aaa;
			transition:.3s;
		}

		.inputWithIconn input[type=text]{
			padding-left:40px;
		}
		table.center{
			text-align: center;
		}

		.stats_item_number {
			white-space: nowrap;
			font-size: 2.25rem;
			line-height: 2.5rem;
			
			&:before {
				display: none;
			}
		}

		.txt_success {
			color: #2EAB6F;
		}

		.txt_warn {
			color: #f2562b;
		}

		.txt_sd {
			color: #04dda3;
		}

		.txt_tp{
			color: #f7e127;
		}

		.txt_win{
			color: #246d18;
		}

		.txt_lose{
			color: #e5140d;
		}

		.txt_smaller {
			font-size: .75em;
		}

		.flipY {
			transform: scaleY(-1);
			border-bottom-color: #fff;
		}

		.txt_faded {
			opacity: .65;
		}

		.txt_primary{
			color: #007bff;
		}

		.card {
			position: relative;
			margin-bottom: 24px;
			background-color: #fff;
			-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
		}
		::-webkit-scrollbar {
			width: 10px;
		}

		::-webkit-scrollbar-track {
			background: #f1f1f1; 
		}
		 
		::-webkit-scrollbar-thumb {
			background: #888; 
		}

		::-webkit-scrollbar-thumb:hover {
			background: #555; 
		}

		.user-panel>.image>img {
			width: 100%;
			max-width: 45px;
			max-height: 45px;
			object-fit: cover;
		}

		.navbar-nav>.user-menu .user-image {
			object-fit: cover;
			float: left;
			width: 25px;
			height: 25px;
			border-radius: 50%;
			margin-right: 10px;
			margin-top: -2px;
		}

		li div a.btn{
			width: 70px;
			height: 36px;
		}
	</style>
	<!-- Theme style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/AdminLTE.min.css">
	@yield('head_css_and')
  <style type="text/css">
    .navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>h4 {
      margin-left: 50px;
    }
    .navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>p {
      margin-left: 50px;
    }
  </style>
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
			@show
			@section('header')
			@include('template.header')
			@show

			@show
			@section('sidebar')
			@include('template.sidebar')
			@show

			<div class="content-wrapper">
				@yield('content')
			</div>

			@show
			@section('footer')
			@include('template.footer')
			@show
		</div>

		<!-- jQuery 3.1.1 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
		@yield('scriptImport')
		<!-- SlimScroll -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/js/adminlte.min.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/js/demo.js"></script>

{{-- <script src="{{asset('template2/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="{{asset('template2/bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{asset('template2/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('template2/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('template2/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('template2/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('template2/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('template2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('template2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('template2/bower_components/chart.js/Chart.js')}}"></script>
<script src="{{asset('template2/dist/js/pages/dashboard2.js')}}"></script> -->
<script src="{{asset('template2/dist/js/demo.js')}}"></script>
<script src="{{asset('template2/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script> --}}
<script>
	$(document).ready(function () {
		// $(".activeable_group").has("[href='" + location.hash + "']").addClass('active')
		// $(".activeable_menu").has("[href='" + location.hash + "']").addClass('active')
		$(".activeable_group").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
        $(".activeable_menu").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
	})
</script>
@yield('script')
</body>
</html>
