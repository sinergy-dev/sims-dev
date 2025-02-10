@extends('template.main')
@section('tittle')
Presence
@endsection
@section('head_css')
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
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
@endsection
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
				
			<center>
			<div class="row">
				
				<div class="col-md-4 col-md-offset-4 text-center">
				@if($presenceStatus == "not-yet")
					<button type="button" class="btn btn-success" onclick="checkIn()">Check In</button>
				@elseif($presenceStatus == "done-checkin")
					@if($presenceStatusDetail == "On-Time")
						<div class="alert alert-success" role="alert">Check-In Complete (On-Time)</div>
					@elseif($presenceStatusDetail == "Injury-Time")
						<div class="alert alert-warning" role="alert">Check-In Complete (Injury-Time)</div>
					@elseif($presenceStatusDetail == "Late")
						<div class="alert alert-danger" role="alert">Check-In Complete (Late)</div>
					@endif
					<button type="button" class="btn btn-danger" onclick="checkOut()">Check Out</button>
				@elseif($presenceStatus == "not-schedule")
					<div class="alert alert-info" role="alert">Shifting - Not Yet Scheduled</div>
				@elseif($presenceStatus == "libur")
					<div class="alert alert-info" role="alert">Shifting - Libur</div>
				@else
					<h3>Thank you for your hard work today</h3>
				@endif
				</div>
			</div>
			</center>
			<br>
			<div style="padding: 10px;">
				<span style="flex-grow: 1;margin-right: 5px;"><i class="fa fa-desktop"></i></span><span style="flex-grow: 2;">Accessed : </span><span id="current_time">{{ now()->timezone('Asia/Jakarta')->format('l, d M Y H:i:s') }}</span><br>
				<span style="flex-grow: 1;margin-right: 5px;"><i class="fa fa-map-pin"></i></span><span>Current Location : </span><span id="current_location"></span>
			</div>
		</div> 
	</section>
@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
@endsection
@section('script')
	<script>
		var presenceLocation;
		$(document).ready(function(){
			updateTime();
			$.ajax({
				type:"GET",
				url:"{{url('/presence/getPresenceParameter')}}",
				data:{
					nik:'{{Auth::User()->nik}}'
				},
				success: function(result){	
					presenceLocation = result
					// isOnLocation(presenceLocation)
				}
			})
		})

		function updateTime () {
			now = new Date ();
			// console.log(now);
			document.getElementById("hour-hand").style.webkitTransform = "rotate(" + (now.getHours() * 30 + now.getMinutes() / 2) + "deg)";
			document.getElementById("min-hand").style.webkitTransform = "rotate(" + (now.getMinutes() * 6 + now.getSeconds() / 10) + "deg)";
			document.getElementById("sec-hand").style.webkitTransform = "rotate(" + now.getSeconds() * 6 + "deg)";
			setTimeout(function () {
				updateTime();
			}, 1000);
		}
		
		function checkIn(){	
			if(isLocationSet(presenceLocation)){
				isOnLocation(presenceLocation).then((result) => {
					var id_location = result
					if(result != 0){
						$.ajax({
							type:"GET",
							url:"{{url('/presence/getLocationByUser')}}",
							data:{
								id_location:id_location,
							},
							success: function(result){
								var curLoc = result
								Swal.fire({
									title: 'Are you sure?',
									html: '<h6>Check-In at <b>'+ curLoc  +'</b></h6>',
									icon: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: 'Yes',
									cancelButtonText: 'No',
								}).then((result) => {
									if(result.value){
										Swal.fire({
											title: 'Please Wait..!',
											text: "It's checking..",
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
											type:"POST",
											url:"{{url('/presence/checkIn')}}",
											data:{
												presence_actual:moment().format("YYYY-MM-DD HH:mm:ss"),
												id_location:id_location,
												_token: "{{ csrf_token() }}"
											},
											success: function(result){
												Swal.fire(
													'Check-in success',
													"Don't forget to checkout later",
													'success'
												).then((result) => {
													location.reload();
												})
											},
											error: function(e,textStatus,errorThrown){
												console.log(e.responseJSON.message)
												// console.log(textStatus)
												// console.log(errorThrown)
												Swal.hideLoading(); 
												Swal.fire(
													'Check-in error',
													"Your shifting is not Something error on server.",
													'error'
												)
											}
										})
									}
								})
							}
						})
					} else {
						setTimeout(function(){ 
							Swal.hideLoading(); 
							Swal.fire(
								'Check-in error',
								"You are now detected to be out of location.",
								'error'
							)
						}, 1000);
						
					}
				}).catch((error) => {
					console.error(error);
					setTimeout(function(){ 
						Swal.hideLoading(); 
						Swal.fire(
							'Checking error',
							"You don't allow the app to access the location<br>Requested to grant location access permit",
							'error'
						)
					}, 1000);
					
					// console.log(Swal.isLoading())
				})
			} else {
				setTimeout(function(){ 
					Swal.hideLoading(); 
					Swal.fire(
						'Check-in error',
						"Your location hasn't been set.<br>Try calling Admin for details.",
						'error'
					)
				}, 1000);
				
			}
		}

		function checkOut(){
			Swal.fire({
				title: 'Are you sure?',
				text: "to checkout your Presence now?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
			}).then((result) => {
				if(result.value){
					Swal.fire({
						title: 'Please Wait..!',
						text: "It's checking..",
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
						type:"POST",
						url:"{{url('/presence/checkOut')}}",
						data:{
							presence_actual:moment().format("YYYY-MM-DD HH:mm:ss"),
							_token: "{{ csrf_token() }}"
						},
						success: function(){
							Swal.hideLoading()
							Swal.fire(
								'Check-out success',
								"Thank you for your hard work today",
								'success'
							).then((result) => {
								location.reload();
							})
						}
					})
				}
			})
		}

		
		var isOnLocation = function(presenceLocation) {
			return new Promise(function(resolve, reject) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var actuals_position = new google.maps.LatLng(
						position.coords.latitude,position.coords.longitude
					)

					var onLocation = false 
					var locationNow = 0 
					presenceLocation.forEach(function(data){ 
						console.log(data)

						var compare_position = new google.maps.LatLng(
							data.location_lat,
							data.location_lng
						) 

						console.log("Distance now to " + data.location_name+ " : "+ google.maps.geometry.spherical.computeDistanceBetween(actuals_position, compare_position))
						if(google.maps.geometry.spherical.computeDistanceBetween(actuals_position, compare_position) < (data.location_radius) ){
							console.log('Raidus : ' + data.location_radius + ' Im in location now')	 
							console.log(onLocation) 
							onLocation = true 
							locationNow = data.location_id 

						} else {
							console.log('Raidus : ' + data.location_radius + ' Im not in location now')
							console.log(onLocation)
						} 
					}) 
					if (onLocation) { 
						resolve(locationNow); 
					} else {
						resolve(locationNow); 
					} 
				} , function(error){ 
					reject(Error("Location Permission Denied")) 
				}); 
			});
		} 

		function isLocationSet(presenceLocation){
			if(presenceLocation.length == 0){
				return false
			} else {
				return true
			}
		}


		function reverseGeocodeLatLng() {
			if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition,showError);
	      	} else {
	        	console.error("Geolocation is not supported by this browser.");
	      	}
	    }

	    function showPosition(position) {
	      	const latitude = position.coords.latitude;
	      	const longitude = position.coords.longitude;
	      	$.ajax({
				type:"POST",
				url:"{{url('/presence/getLocationNameFromLatLng')}}",
				data:{
					latitude:latitude,
					longitude:longitude,
					_token: "{{ csrf_token() }}"
				},
				success: function(result){
					$("#current_location").text(result)
					return addressLocation = result
				},
			})

	 	 // Use the retrieved latitude and longitude values as needed (e.g., display on a map, perform reverse geocoding, etc.)
	    }

	    function showError(error) {
	      switch (error.code) {
	        case error.PERMISSION_DENIED:
	          console.error("User denied the request for Geolocation.");
	          break;
	        case error.POSITION_UNAVAILABLE:
	          console.error("Location information is unavailable.");
	          break;
	        case error.TIMEOUT:
	          console.error("The request to get user location timed out.");
	          break;
	        case error.UNKNOWN_ERROR:
	          console.error("An unknown error occurred.");
	          break;
	      }
	    }

	    function onLoad() {
	      // Call reverseGeocodeLatLng function when the page loads
	      reverseGeocodeLatLng()
	    }

	 //    // Attach the onLoad function to the window.onload event
	    window.onload = onLoad;

	    startTime()
	    function checkTime(i) {
		  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
		  return i;
		}

		function startTime() {
	      	var today = new Date();
	      	var time = moment(today).format('dddd, D MMM YYYY HH:mm:ss');
	      	var h = today.getHours();
	      	var m = today.getMinutes();
	      	var s = today.getSeconds();
	      	m = checkTime(m);
	      	s = checkTime(s);
	      	// document.getElementById('current_time').innerHTML =  time;
	      	var t = setTimeout(startTime, 500);
	  	}
	</script>
	{{-- <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry&key={{env('GOOGLE_API_KEY_NEW')}}"></script> --}}
	<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry&key={{env('GOOGLE_API_KEY_GLOBAL')}}"></script>
@endsection