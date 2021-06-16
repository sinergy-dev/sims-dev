<header class="main-header">
	<a href="{{url('/')}}" class="logo">
		<span class="logo-mini"><img src="{{asset('/img/siplogooke.png')}}" alt="cobaaa" width="30px" height="40px"></img></span>
		<span class="logo-lg"><b>SIMS</b>APP</span>
	</a>

	<nav class="navbar navbar-static-top">
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">
				Toggle navigation
			</span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" >	
						<i id="bell-id"></i>
						<span class="label label-warning" id="notificationCount"></span>					
					</a>
					<ul class="dropdown-menu" id="">
						<li class="header">New Notifications:</li>
						<li>
							<ul class="menu" id="notificationContent">
								<!-- <li>
									<a href="#">
										<i class="fa fa-users text-aqua"></i> 5 new members joined today
									</a>
								</li>
								<li>
									<a href="#">
										<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
										page and may cause design problems
									</a>
								</li>
								<li>
									<a href="#">
										<i class="fa fa-users text-red"></i> 5 new members joined
									</a>
								</li>
								<li>
									<a href="#">
										<i class="fa fa-shopping-cart text-green"></i> 25 sales made
									</a>
								</li>
								<li>
									<a href="#">
										<i class="fa fa-user text-red"></i> You changed your username
									</a>
								</li> -->
							</ul>
						</li>
						<li class="footer">
							<a href="#">View all</a>
						</li>
					</ul>
				</li>
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
							<img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="user-image" alt="Yuki">
						@else
							<img src="{{asset('image/'.Auth::User()->gambar)}}" class="user-image" alt="User Image">
						@endif
						<span class="hidden-xs">{{ Auth::User()->name }}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
								<img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="img-circle" alt="Yuki">
							@else
								<img src="{{asset('image/'.Auth::User()->gambar)}}" class="img-circle" alt="User Image">
							@endif

							<p>
								{{ Auth::User()->name }} - 
								@if(Auth::user()->id_division == 'HR' && Auth::user()->id_position == 'HR MANAGER')
									{{ Auth::user()->id_position }}
								@elseif(Auth::user()->id_position == 'EXPERT SALES')
									{{ Auth::user()->id_position}}
								@else
									@if(Auth::user()->nik == 100000000003)
									SALES OPERATIONAL
									@elseif(Auth::user()->id_division == 'TECHNICAL' && Auth::user()->id_position == 'MANAGER')
									OPERATIONAL DIRECTOR
									@else
									{{ Auth::user()->id_division }} {{ Auth::user()->id_position }}
									@endif
								@endif
								<small>
									@if(Auth::User()->id_company == '1') 
										Sinergy Informasi Pratama
									@else
										Multi Solusindo Perkasa
									@endif
								</small>
								<small>Member since {{ Auth::User()->date_of_entry }}</small>
							</p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<a href="{{url('profile_user')}}" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
										{{ __('Logout') }}
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
									<input type="hidden" name="nik" value="{{Auth::User()->nik}}">
								</form>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-database.js"></script>
<script type="text/javascript">
	// $( window ).load(function() {
 //       localStorage.clear()
 //    });
	var firebaseConfig = {
	    apiKey: "AIzaSyB0MWK6KLjhJlY7cL7G6STOCVGnxzjapXU",
	    authDomain: "sims-22e41.firebaseapp.com",
	    projectId: "sims-22e41",
	    storageBucket: "sims-22e41.appspot.com",
	    messagingSenderId: "84983392260",
	    appId: "1:84983392260:web:e10924f37a7a5c189cad51",
	    measurementId: "G-WK8FWEW0TV"
	};
  	// Initialize Firebase
  	firebase.initializeApp(firebaseConfig);

  	firebase.database().ref('notif/web-notif').once('value', function(snapshot) {

  	 	snapshot_dump = snapshot.val()

  	 	var append = ""
  	 	var count = 0

  	 	var keys = Object.keys(snapshot_dump)
  	 	keys = keys.reverse()

  	 	for (var i = 0; i < keys.length; i++) {
  	 		if (snapshot_dump[keys[i]].status == "unread") {
  	 			if (snapshot_dump[keys[i]].to == "{{Auth::User()->email}}") {
  	 				// console.log("a")

		  	 		if ("{{Auth::User()->id_division}}" == 'FINANCE') {
		  	 			append = append + makeNotificationHolder(snapshot_dump[keys[i]],keys[i],"unread","{{url('salesproject')}}#submitIdProject/"+snapshot_dump[keys[i]].id_pid)

		  	 		}else if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
		  	 			if (snapshot_dump[keys[i]].result == 'INITIAL') {
			  	 			append = append + makeNotificationHolder(snapshot_dump[keys[i]],keys[i],"unread",snapshot_dump[keys[i]].lead_id)

		  	 			}else{
						    localStorage.setItem("status","read")

			  	 			append = append + makeNotificationHolder(snapshot_dump[keys[i]],keys[i],"unread","{{url('detail_project')}}/"+snapshot_dump[keys[i]].lead_id)

		  	 			}
			  	 		// console.log(snapshot_dump[keys[i]].lead_id)

		  	 		}else{
			  	 		append = append + makeNotificationHolder(snapshot_dump[keys[i]],keys[i],"unread","{{url('detail_project')}}/"+snapshot_dump[keys[i]].lead_id)

		  	 		}
		  	 	}
	  	 	}

  	 		count++

  	 	}

  	 	$("#notificationContent").append(append)

  	})

  	firebase.database().ref('notif/web-notif').on('value', function(snapshot) {
        snapshot_dump = snapshot.val()
        var append = ""
        var count = 0

        var keys = Object.keys(snapshot_dump)
  	 	keys = keys.reverse()

  	 	// console.log(keys)
  	 	for (var i = 0; i < keys.length; i++) {

  	 		if (snapshot_dump[keys[i]].status == "unread") {

  	 			if (snapshot_dump[keys[i]].to == "{{Auth::User()->email}}") {
  	 				count++

		  	 	}

		  	} 
        }

        if(count != 0){ 	
        	count = count
        	$("#bell-id").addClass('fa fa-bell')
			$("#notificationCount").text(count)

        } else {   
        	// count = "0"
        	$("#bell-id").addClass('fa fa-bell-o')
        }		

        // console.log(count)

    });

    var start = true;

    firebase.database().ref('notif/web-notif').limitToLast(1).on('child_added', function(snapshot) {
    	console.log(snapshot.val())
        if(!start){

            // $("#notificationContent").children().last().remove()
            // $("#notificationContent").children().last().remove()
            // Show latests notification to Browser Notification
            // if(snapshot.val().showed == "false"){
            //     showNotificationBrowser(snapshot.val(),snapshot.key)
            // }

            // Add latests notification

            // $("#notificationContent").prepend(makeNotificationHolder(snapshot_dump[keys[i]],keys[i],"unread",snapshot_dump[keys[i]].lead_id)) 

            if (snapshot.val().to == "{{Auth::User()->email}}") {
  	 				// console.log("a")

	  	 		if ("{{Auth::User()->id_division}}" == 'FINANCE') {
	  	 			$("#notificationContent").prepend(makeNotificationHolder(snapshot.val(),snapshot.key,"unread","{{url('salesproject')}}#submitIdProject/"+snapshot.val().id_pid))

	  	 		}else if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
	  	 			if (snapshot.val().result == 'INITIAL') {
            			$("#notificationContent").prepend(makeNotificationHolder(snapshot.val(),snapshot.key,"unread",snapshot.val().lead_id)) 
	  	 			}else{
					   localStorage.setItem("status","read")
				
		  	 			$("#notificationContent").prepend(makeNotificationHolder(snapshot.val(),snapshot.key,"unread","{{url('detail_project')}}/"+snapshot.val().lead_id))

	  	 			}
	  	 		}else{
		  	 		$("#notificationContent").prepend(makeNotificationHolder(snapshot.val(),snapshot.key,"unread","{{url('detail_project')}}/"+snapshot.val().lead_id))

	  	 		}
	  	 	}
        } else {
            start = false
        }
    })


    function timedRefresh(timeoutPeriod) {
		setTimeout("location.reload(true);",timeoutPeriod);
	}

  	function makeNotificationHolder(data,index,status,url){
        var append = ""

        if(status == "unread"){
        		append = append + '<li>'
				append = append +  '<a class="pointer" onclick="readNotification('+ "'" + index +  "'" + ',' + "'" + url + "'" + ')"><span class="label" style="background-color:'+ data.heximal +'">'+ data.result + '</span> ' + data.opty_name
				append = append +  '</a>'
				append = append +  '</li>'
        	
        } 

        return append
    }

    function readNotification(index,url){
 
        firebase.database().ref('notif/web-notif/' + index).once('value').then(function(snapshot) {
            // console.log(snapshot.val())
            var data = snapshot.val()
            if (data.id_pid == null) {
            	id_pid = ""
            }else{
            	id_pid = data.id_pid 
            }

            firebase.database().ref('notif/web-notif/' + index).set({
                to: data.to,
                lead_id: data.lead_id,
                opty_name: data.opty_name,
                heximal: data.heximal,
                status: "read",
                result : data.result,
                showed : "true",
                id_pid : id_pid
            });

            if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
            	if (snapshot.val().result == 'INITIAL') {
            		location.reload(true);  
            		window.location.href = "{{url('project')}}/"

	            	localStorage.setItem("lead_id",url)
	            	localStorage.setItem("status","unread")
            	}else{
					localStorage.setItem("status","read")

            		location.reload(true);  
            		window.location.href = url
            	}          	           	 
            }else{
            	if (window.location.href.split("/")[3].split("#")[1] == 'submitIdProject') {
	            	location.reload(true);
	            	window.location.href = url
	            }else if (window.location.href.split("/")[3] == 'salesproject') {
	            	window.location.href = url
	            	location.reload(true);
	            }else{
	            	window.location.href = url

	            }
            }

        })
    }

</script>
