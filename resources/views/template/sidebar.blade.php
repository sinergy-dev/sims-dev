<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				@if(Auth::User()->avatar != NULL)
					<img src="{{Auth::User()->avatar}}" class="img-circle">
				@else
					@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
						<img src="{{asset('image/default-user.png')}}" class="img-circle" alt="User Image">
					@else
						<img src="{{asset('image') . '/' . Auth::User()->gambar}}" class="img-circle" alt="User Image">
					@endif
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
			@foreach($initView['listMenu'] as $key => $group)
				<li class="treeview activeable_group">
					<a href="#">
						<i class="{{$group[0]->icon_group}}"></i>
						<span>{{$key}}</span>
					</a>
					<ul class="treeview-menu">
						@foreach($group as $keys => $childGroup)
							@if($group[$keys]->count == 0)
								<li class="activeable_menu">
									<a href="{{url($group[$keys]->url)}}">
										<i class="fa fa-circle-o"></i>{{$group[$keys]->name}}
										@if($group[$keys]->name == "Lead Register")
										<span class="pull-right-container">
											<small class="label pull-right bg-red" id="Lead_Register"></small>
										</span>
										@endif
									</a>
								</li>
							@else
								@if($group[$keys]->name == "Consumable")
								<li class="treeview">
									<a href="#">
										<i class="fa fa-circle-o"></i>
										<span>{{$group[$keys]->name}}</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right" id=""></i>
										</span>
									</a>
									<ul class="treeview-menu">
									@foreach($group[$keys]->child as $childRow)
										@foreach($childRow as $childRowData)
										<li class="activeable_menu">
											<a href="{{url($childRowData->url)}}">
												<i class="fa fa-circle-o"></i>{{$childRowData->name}}
											</a>
										</li>
										@endforeach
									@endforeach
									</ul>
								</li>
								@endif
							@endif
						@endforeach
					</ul>
				</li>
			@endforeach
		</ul>
	</section>
</aside>

@section('scriptNotificationSidebar')
@parent
<!-- From Sidebar Blade for notification -->
<!-- Firebase-app 8.6.3-->
<!-- <script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-app.js"></script> -->
<!-- Firebase-database 8.6.3-->
<!-- <script src="https://www.gstatic.com/firebasejs/8.6.3/firebase-database.js"></script> -->
<!-- MomentJS -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->

<script type="text/javascript">
	//Disabled push notif
	// var firebaseConfigSidebar = {
	//     apiKey: "{{env('FIREBASE_APIKEY')}}",
	//     authDomain: "{{env('FIREBASE_AUTHDOMAIN')}}",
	//     projectId: "{{env('FIREBASE_PROJECTID')}}",
	//     storageBucket: "{{env('FIREBASE_STORAGEBUCKET')}}",
	//     messagingSenderId: "{{env('FIREBASE_MESSAGINGSENDERID')}}",
	//     appId: "{{env('FIREBASE_APPID')}}",
	//     measurementId: "{{env('FIREBASE_MEASUREMENTID')}}"
	// };
	
 //  	firebase.initializeApp(firebaseConfigSidebar);

	// var firebaseRootRef = firebase.database().ref();

 //   	if ("{{Auth::User()->id_division}}" == 'SALES' || "{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
 // 		var personale_Ref = firebaseRootRef.child('notif/Lead_Register');
 //   	} else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
 // 		var personale_Ref = firebaseRootRef.child('notif/ID_Project');
 //   	}   	

 //   	if (personale_Ref != null) {
 //   		personale_Ref.orderByChild("to").equalTo("{{Auth::User()->email}}").on("value", function(snapshot) {
 //   		if (true) {}
	// 	console.log(snapshot.val() + 'hello')

	//     	if (snapshot.exists()) {
	//     		snapshot_key = snapshot.key
	//     		console.log(snapshot.key)
	// 	      	snapshot_child = snapshot.val() 
	// 		    $(".label").each(function(){
	// 		    	var id 	= $(this).attr("id")
	// 				if (id == snapshot_key) {
	// 		         var keys = Object.keys(snapshot.val())
	// 		         for (var i = 0; i < keys.length; i++) {
	// 		         	if (snapshot_child[keys[i]].to == "{{Auth::User()->email}}" && snapshot_child[keys[i]].total != 0) {
	// 		         		$("#"+id).show()
	// 						$("#"+id).text(snapshot_child[keys[i]].total)
	// 						$("#"+id+"_arrow-angle").hide()
	// 		         	}

	// 		         	if(snapshot_child[keys[i]].to != "{{Auth::User()->email}}" && snapshot_child[keys[i]].total == 0){
	// 		         		$("#"+id).hide()
	// 						$("#"+id+"_arrow-angle").show()
	// 		         	}
	// 		         }
	// 				}
	// 			});
	//     	}
	//     });	
 //   	}
</script>
@endsection