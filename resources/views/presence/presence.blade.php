@extends('template.template_admin-lte')
@section('content')

<style>
	#analog-clock {
		position: relative;
		width: 300px;
		height: 300px;
		margin: auto;
	}

	#clock-dial {
		width: 100%;
		height: 100%;
		background: #1d3030;
		border-radius: 50%;
	}

	#clock-dial-circle {
		position: absolute;
		width: 3%;
		height: 3%;
		border-radius: 50%;
		background: white;
		top: 48.5%;
		left: 48.5%;
	}

	.clock-dial-stick {
		position: absolute;
		top: 5%;
		left: 49.8%;
		width: 0.7%;
		height: 7%;
		opacity 0.5;
		background: lightgray;
		-webkit-transform-origin: 50% 640%;
	}
	#clock-dial-12 {
		-webkit-transform: rotate(0deg);
	}
	#clock-dial-1 {
		-webkit-transform: rotate(30deg);
	}
	#clock-dial-2 {
		-webkit-transform: rotate(60deg);
	}
	#clock-dial-3 {
		-webkit-transform: rotate(90deg);
	}
	#clock-dial-4 {
		-webkit-transform: rotate(120deg);
	}
	#clock-dial-5 {
		-webkit-transform: rotate(150deg);
	}
	#clock-dial-6 {
		-webkit-transform: rotate(180deg);
	}
	#clock-dial-7 {
		-webkit-transform: rotate(210deg);
	}
	#clock-dial-8 {
		-webkit-transform: rotate(240deg);
	}
	#clock-dial-9 {
		-webkit-transform: rotate(270deg);
	}
	#clock-dial-10 {
		-webkit-transform: rotate(300deg);
	}
	#clock-dial-11 {
		-webkit-transform: rotate(330deg);
	}

	#hour-hand {
		position: absolute;
		width: 1.5%;
		height: 25%;
		background: white;
		top: 24%;
		left: 49.25%;
		-webkit-transform-origin: 50% 110%;
	}

	#min-hand {
		position: absolute;
		width: 1.5%;
		height: 35%;
		background: white;
		top: 12%;
		left: 49.25%;
		-webkit-transform-origin: 50% 110%;
	}

	#sec-hand {
		position: absolute;
		width: 1%;
		height: 35%;
		background: red;
		top: 12%;
		left: 49.5%;
		-webkit-transform-origin: 50% 110%;
	}

	/* apply a natural box layout model to all elements */
	*, *:before, *:after {
		-moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
	}
</style>

  <section class="content-header">
    <h1>
      Presence
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Presence</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">

    	<section class="content-header" >
		<!-- <img src="{{ url('/img/labelaogy.png')}}" width="120" height="40"> -->
		<ol class="breadcrumb" style="font-size: 15px;">
			<li><a href="{{url('precense/myhistory')}}"><i class="fa fa-book"></i>My Absent History</a></li>
			
				<li><a href="{{url('precense/teamhistory')}}"><i class="fa fa-users"></i>My Team Attendance</a></li>
				<li><a href="{{url('precense/reporting')}}"><i class="fa fa-users"></i>Reporting</a></li>
			
		</ol>
	</section>

      	<div class="box-body">
        	<div id="analog-clock">
				<div id="clock-dial">
					<div id="clock-dial-circle"></div>	
					<div id="clock-dial-12" class="clock-dial-stick"></div>
					<div id="clock-dial-1" class="clock-dial-stick"></div>
					<div id="clock-dial-2" class="clock-dial-stick"></div>		
					<div id="clock-dial-3" class="clock-dial-stick"></div>
					<div id="clock-dial-4" class="clock-dial-stick"></div>
					<div id="clock-dial-5" class="clock-dial-stick"></div>		
					<div id="clock-dial-6" class="clock-dial-stick"></div>
					<div id="clock-dial-7" class="clock-dial-stick"></div>
					<div id="clock-dial-8" class="clock-dial-stick"></div>		
					<div id="clock-dial-9" class="clock-dial-stick"></div>
					<div id="clock-dial-10" class="clock-dial-stick"></div>
					<div id="clock-dial-11" class="clock-dial-stick"></div>		
				</div>
	
				<div id="clock-hands">
					<div id="hour-hand"></div>
					<div id="min-hand"></div>
					<div id="sec-hand"></div>
				</div>
			</div>
    	</div>

    	<br>
			<center><h3>{{date("l, d M Y H:i:s")}}</h3></center>
		<br>

		<center><button type="button" class="btn btn-success " data-toggle="modal" data-target="#myModal" id="absen">ABSEN</button></center><br>

    <div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">

								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Absen Location</h4>
									</div>
									<div class="modal-body">
										<p>Please go to your area, and login on there</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-success" id="absenLocation">Absen Location</button>
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
									</div>
								</div>

							</div>
						</div>

						<div class="modal fade" id="myModal2" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Login on Position Success</h4>
									</div>
									<div class="modal-body">
										<p>You have been login on your area. Keep spirit for our bussines</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="close">Close</button>
									</div>
								</div>

							</div>
						</div>

						<div class="modal fade" id="modalPulang" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Absen Pulang</h4>
									</div>
									<div class="modal-body">
										<p>Please go to your area, and login on there</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal" id="pulang">Pulang</button>
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
									</div>
								</div>

							</div>
						</div>

  </div>
   
</section>

<script>
	$(document).ready(function(){
		var condition;
		$("#absenLocation").click(function () {
			condition = 'masuk';
			initMap();
		});

		$("#tryAgain").click(function(){
			condition = 'masuk';
			initMap();
		});

		$("#pulang").click(function(){
			condition = 'pulang';
			initMap();
		});
		
		var map, infoWindow, pos;
		function initMap() {

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};

					var lat = "1";
					var lang = "1";
					var p1 = new google.maps.LatLng(lat, lang);
					var p2 = new google.maps.LatLng(pos.lat, pos.lng);

					var radius = parseInt("1") / 1000;
					console.log("Lokasi : bpjs");
					console.log("Radius : " + radius);
						
					var calculate = calcDistance(p1, p2);
					if(calculate < radius) {
						$("#berhasil").click();
						$("#myModal").hide();
						$("#absen").hide();
						$("#logined").show();
						$("#close").click(function () {
							$(".modal-backdrop").hide();
						});
						alert(calculate + " km, masuk wilayah");
						$.ajax({
							type: "POST",
							data: {
								"_token": "{{ csrf_token() }}",
							},
							url: "raw/{{Auth::user()->id}}",
							success: function(){
								$.ajax({
									type: "GET",
									data: {
										"lat": lat,
										"lng": lang,
										"condition" : condition,
									},
									url: "createPresenceLocation",
									success: function(){
										location.reload();
									},
								});
							},
						});
						$("#absen").hide();
					} else {
						$.ajax({
							type:"GET",
							data:{
								message: "Gagal Absen - Keluar Wilayah"
							},
							url: "logging/ERROR",
							success: function(){
								$("#gagal").click();
								$("#myModal").hide();
								$(".modal-backdrop").hide();
								alert(calcDistance(p1, p2) + " km, keluar wilayah");
							}
						})
					}
					function calcDistance(p1, p2) {
						return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
					}
					console.log(pos.lat + " , " + pos.lng);
				}, 
				function() {
					console.log("Geolocation error");
				});
			} else {
				handleLocationError(false, infoWindow, map.getCenter());
			}
		}

		function handleLocationError(browserHasGeolocation, infoWindow, pos) {
			infoWindow.setPosition(pos);
			infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
			infoWindow.open(map);
		}
	});
	
	function updateTime () {
		now = new Date ();

		document.getElementById("hour-hand").style.webkitTransform = "rotate(" + (now.getHours() * 30 + now.getMinutes() / 2) + "deg)";
		
		document.getElementById("min-hand").style.webkitTransform = "rotate(" + (now.getMinutes() * 6 + now.getSeconds() / 10) + "deg)";
		
		document.getElementById("sec-hand").style.webkitTransform = "rotate(" + now.getSeconds() * 6 + "deg)";
		
		setTimeout(function () {
			updateTime();
		}, 1000);
	}

	updateTime();
	
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry&key={{env('GOOGLE_API_KEY')}}"></script>


@endsection