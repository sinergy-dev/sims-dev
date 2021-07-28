<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
					<img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="img-circle">
				@else
					<img src="{{asset('image/'.Auth::User()->gambar)}}" class="img-circle" alt="User Image">
				@endif
			</div>
			<div class="pull-left info" >
				<p>{{ Auth::User()->name }}</p>
				<a href="#"><i class="fa fa-circle text-success"></i>
					{{$initView['userRole']->name}}
				</a>
			</div>
		</div>
		<ul class="sidebar-menu tree" data-widget="tree">
			<li class="header">MAIN NAVIGATION</li>
			@foreach($initView['listMenu'] as $group)
				<li class="treeview activeable_group">
					<a href="#">
						<i class="{{$group['icon_group']}}"></i>
						<span>{{$group['text']}}</span>
						<span class="pull-right-container">
							<small class="label pull-right bg-red" id="{!! str_replace(' ', '_', $group['name']) !!}"></small>
							<i class="fa fa-angle-left pull-right" id="{!! str_replace(' ', '_', $group['name']) !!}_arrow-angle"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						@foreach($group['children'] as $chid)
							<li class="activeable_menu">
								<a href="{{url($chid->url)}}">
									<i class="fa fa-circle-o"></i>{{$chid->name}}									
									@if($chid->notif_status == "on")
									<span class="pull-right-container">
							    		<!-- <small class="label pull-right bg-red" id="{!! str_replace(' ', '_', $chid->name) !!}"></small> -->
							    	</span>
									@endif
								</a>
							</li>
						@endforeach
					</ul>
				</li>
			@endforeach
		</ul>
	</section>
</aside>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-database.js"></script>
<script type="text/javascript">
	var firebaseConfig = {
	    apiKey: "{{env('FIREBASE_APIKEY')}}",
	    authDomain: "{{env('FIREBASE_AUTHDOMAIN')}}",
	    projectId: "{{env('FIREBASE_PROJECTID')}}",
	    storageBucket: "{{env('FIREBASE_STORAGEBUCKET')}}",
	    messagingSenderId: "{{env('FIREBASE_MESSAGINGSENDERID')}}",
	    appId: "{{env('FIREBASE_APPID')}}",
	    measurementId: "{{env('FIREBASE_MEASUREMENTID')}}"
	};
  	// Initialize Firebase
  	firebase.initializeApp(firebaseConfig);

  	// var db = firebase.database();
  	// var notifRef = db.ref('notif');

  	// notifRef.on('value', showData, callbackError);

   var firebaseRootRef = firebase.database().ref();

   	if ("{{Auth::User()->id_division}}" == 'SALES' || "{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
	 		var personale_Ref = firebaseRootRef.child('notif/Lead_Register');
   	}else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
	 		var personale_Ref = firebaseRootRef.child('notif/ID_Project');
   	}

   	if (personale_Ref != null) {
   		personale_Ref.orderByChild("to").equalTo("{{Auth::User()->email}}").on("value", function(snapshot) {
   		if (true) {}
		console.log(snapshot.val() + 'hello')

	    	if (snapshot.exists()) {
	    		snapshot_key = snapshot.key
	    		console.log(snapshot.key)
		      	snapshot_child = snapshot.val() 
			    $(".label").each(function(){
			    	var id 	= $(this).attr("id")
					if (id == snapshot_key) {
			         var keys = Object.keys(snapshot.val())
			         for (var i = 0; i < keys.length; i++) {
			         	if (snapshot_child[keys[i]].to == "{{Auth::User()->email}}" && snapshot_child[keys[i]].total != 0) {
			         		$("#"+id).show()
							$("#"+id).text(snapshot_child[keys[i]].total)
							$("#"+id+"_arrow-angle").hide()
			         	}

			         	if(snapshot_child[keys[i]].to != "{{Auth::User()->email}}" && snapshot_child[keys[i]].total == 0){
			         		$("#"+id).hide()
							$("#"+id+"_arrow-angle").show()
			         	}
			         }
					}
				});
	    	}
	    });	
   	}
	
    
    
  // $("#Lead_Register").text("haha")

  // function showData(items){
  // 	$("#Lead_Register").text(items.val()["ID Project"].total)
  // 	console.log(items.val()["ID Project"].total)
  // 	console.log("upss")

  // }

  // firebase.database().ref('notif/Lead_Register/territory_3').set({
  // 		to:"andre@sinergy.co.id",
  //     total:77
  // });

  // function callbackError(err){
  // 	console.log(err)
  // }
</script>