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
<link rel="stylesheet" href="{{asset('template2/bower_components/select2/dist/css/select2.min.css')}}">
	
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="https://sifoma.id/AdminLTE/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="https://sifoma.id/AdminLTE/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="https://sifoma.id/AdminLTE/dist/css/skins/_all-skins.min.css">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
		label.status-lose:hover{
			border-radius: 10%;
			background-color: grey;
			text-align: center;
			width: 75px;
			height: 30px;
			color: white;
			padding-top: 3px;
			cursor: zoom-in;
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
	</style>
	@yield('head_css')

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
			@include('template.sidebar2')
			@show

			<div class="content-wrapper">
				@yield('content')
			</div>

			@section('footer')
			@include('template.footer')
			@show
		</div>
<script src="{{asset('template2/bower_components/jquery/dist/jquery.min.js')}}"></script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>

		<script>
			$(document).ready(function () {
				$(".activeable_group").has("[href='" + location.hash + "']").addClass('active')
				$(".activeable_menu").has("[href='" + location.hash + "']").addClass('active')
			})
		</script>
		@yield('script')
	</body>
</html>
