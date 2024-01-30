<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<!-- <meta name="viewport" content="viewport-fit=cover"> -->
	<meta name="theme-color" content="#000000">
	<title>
		@hasSection('tittle')
			SIMS - @yield('tittle')
		@else
			SIP - SIMS
		@endif
	</title>
	<!-- PWA  -->
	<link rel="manifest" href="{{ asset('/manifest.json') }}">
	<!-- <link rel="manifest" href="{{url('public/manifest.json')}}"> -->
	<link rel="icon" type="image/png" href="{{url('img/siplogooke.png')}}">
	<!-- Tell the browser to be responsive to screen width -->
	<!-- Bootstrap 3.3.7 -->
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<!-- Font Awesome -->
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"> -->
	<!-- Ionicons -->
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
	@yield('head_css')	
	<!-- Theme style -->
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.17/css/AdminLTE.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.17/css/AdminLTE.min.css"> -->
	@yield('head_css_end')
	<!-- AdminLTE Skins -->
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/skins/skin-blue.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/skins/skin-blue.min.css"> -->
	<!-- Google Font -->
	<link rel="preload" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->

	<!-- Hotjar Tracking Code for SIMS App - prod -->
	<script>
	    (function(h,o,t,j,a,r){
	        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
	        h._hjSettings={hjid:3568234,hjsv:6};
	        a=o.getElementsByTagName('head')[0];
	        r=o.createElement('script');r.async=1;
	        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
	        a.appendChild(r);
	    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
	</script>

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
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.17/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.17/js/demo.js"></script>
	<script>
		$(document).ready(function () {
			$(".activeable_group").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
			$(".activeable_menu").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
		})
	</script>
	<script src="{{ asset('/sw.js') }}"></script>
	<script>
	   if ("serviceWorker" in navigator) {
	      // Register a service worker hosted at the root of the
	      // site using the default scope.
	      navigator.serviceWorker.register("/sw.js").then(
	      (registration) => {
	         console.log("Service worker registration succeeded:", registration);
	      },
	      (error) => {
	         console.error(`Service worker registration failed: ${error}`);
	      },
	    );
	  } else {
	     console.error("Service workers are not supported.");
	  }
	</script>
	@yield('script')
</body>
</html>
