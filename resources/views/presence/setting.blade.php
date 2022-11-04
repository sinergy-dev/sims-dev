@extends('template.main')

{{-- Tittle harus di descripsikan sesuai dengan menu yang di buka --}}
@section('tittle')
Presence Setting
@endsection

@section('head_css')
	<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="{{ url('css/dataTables.bootstrap.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
	<style type="text/css">
		/*Buat style bisa mulai di tulis di sini*/
		/*h1 {
			color: red;
		}*/

		.dataTables_filter {
			display: none;
		}

		.pac-container {
			z-index: 1100 !important;
		}
	</style>
@endsection

@section('content')
	<!-- Content Header bisa di isi dengan Title Menu dan breadcrumb -->
	<section class="content-header">
		<h1>
			Precense setting
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="{{url('dashboard')}}">
					<i class="fa fa-fw fa-dashboard"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="{{url('presence')}}">Presence</a>
			</li>
			<li class="active">
				<a href="{{url('presence/setting')}}">Setting</a>
			</li>
		</ol>
	</section>

	<!-- Untuk Content bisa dimulai dengan box -->
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-table"></i> Table Presence Setting</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<dir class="col-md-8" style="margin-bottom: 0px; margin-top: 0px;">
						<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-addNewLocation">
							<i class="fa fa-plus"></i> Location
						</button>
					</dir>
					<dir class="col-md-4 text-right" style="margin-bottom: 0px; margin-top: 0px;">
						<div class="input-group pull-right">
							<input id="searchBar" type="text" class="form-control" onkeyup="searchCustom('table_setting','searchBar')" placeholder="Search Anything">
							
							<div class="input-group-btn">
								<button type="button" id="btnShowEntryFeature" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									Show 10 entries
								</button>
								<ul class="dropdown-menu">
									<li><a href="#" onclick="$('#table_setting').DataTable().page.len(10).draw();$('#btnShowEntryFeature').html('Show 10 entries')">10</a></li>
									<li><a href="#" onclick="$('#table_setting').DataTable().page.len(25).draw();$('#btnShowEntryFeature').html('Show 25 entries')">25</a></li>
									<li><a href="#" onclick="$('#table_setting').DataTable().page.len(50).draw();$('#btnShowEntryFeature').html('Show 50 entries')">50</a></li>
									<li><a href="#" onclick="$('#table_setting').DataTable().page.len(100).draw();$('#btnShowEntryFeature').html('Show 100 entries')">100</a></li>
								</ul>
							</div>
							<span class="input-group-btn">
								<button onclick="searchCustom('table_setting','searchBar')" type="button" class="btn btn-default btn-flat">
									<i class="fa fa-fw fa-search"></i>
								</button>
							</span>
						</div>
					</dir>
				</div>
				<div class="table-responsive">
		    		<table id="table_setting" class="table table-bordered table-striped dataTables_wrapper" role="grid" aria-describedby="example1_info">
			        	<thead>
							<tr>
								<th>NIK</th>
								<th>Name</th>
								<th>Role</th>
								<th>Location</th>
								<th>Work On</th>
								<th>Work Out</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="tbody_setting">
							
						</tbody>
        			</table>
	    		</div>
			</div>
		</div> 
	</section>

	<div class="modal fade" id="edit_schedule" role="dialog">
	    <div class="modal-dialog modal-md">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Edit Schedule</h4>
	        </div>
	        <div class="modal-body">
	        	<p>Change attendance schedule for <b id="changeName"></b></p>
	        	<div class="row">
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Work In Before</label>
			              <input type="text" name="work_in_before" id="work_in_before" class="form-control" readonly>
			            </div>
	        		</div>
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Work In After</label>
			              	<div class="input-group">
								<input type="text" name="work_in_after" id="work_in_after" class="form-control">
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
							</div>

			            </div>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Work Out Before</label>
			         		<input type="text" name="work_out_before" id="work_out_before" class="form-control" readonly>			              
			            </div>
	        		</div>
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Work out After</label>
			              	<div class="input-group">
								<input type="text" name="work_out_after" id="work_out_after" class="form-control">
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
							</div>
			            </div>
	        		</div>
	        	</div>

	            <div class="modal-footer">
	              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i> Close</button>
	              <button type="button" class="btn btn-primary" id="btnUpdateSchedule"><i class="fa fa-check"> </i> Update</button>
	            </div>
	        </div>
	      </div>
	    </div>
	</div>

	<div class="modal fade" id="edit_location" role="dialog">
	    <div class="modal-dialog modal-md">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Edit Location</h4>
	        </div>
	        <div class="modal-body">
	        	<p>Change attendance location for <b id="changeNameForLoc"></b></p>
	        	<!-- <div class="row">
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Location Before</label>
			              <input type="text" name="location_before" id="location_before" class="form-control" readonly>
			              <input type="text" name="location_before_id" id="location_before_id" hidden>
			            </div>
	        		</div>
	        		<div class="col-md-6">
	        			<div class="form-group">
			              <label for="">Location After</label>
			              <select class="form-control select2" style="width: 100%;" id="location_after" >
			              </select>
			            </div>
	        		</div>
	        	</div> -->
    			<div class="form-group">
	              <label for="">Location</label>
	              <select class="form-control select2" style="width: 100%;" id="location_update" >
	              </select>
	            </div>

	            <div class="modal-footer">
	              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i> Close</button>
	              <button type="button" class="btn btn-primary" id="btnUpdateLocation"><i class="fa fa-check"> </i> Update</button>
	            </div>
	        </div>
	      </div>
	    </div>
	</div>

	<div class="modal fade in" id="modal-addNewLocation">
		<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span></button>
						<h4 class="modal-title">Add Location</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Search Location</label>
							<input class="form-control" placeholder="Location" type="text" id="search">
						</div>
						<div class="form-group">
							<div id="map" style="height: 350px;width: 100%;margin:auto;display: block;background-color: #000;"></div>
						</div>
						<div class="form-group">
							<label>Name </label>
							<input class="form-control" placeholder="Yout Must Give Name This Location" type="text" name="pleace" id="place" required>
						</div>
							
						<div class="row">
							<dir class="col-md-6">
								<div class="form-group">
									<label>Latitude</label>
									<input class="form-control" placeholder="" type="text" id="lat" name="lat">
								</div>
							</dir>
							<dir class="col-md-6">
								<div class="form-group">
									<label>Longitude</label>
									<input class="form-control" placeholder="" type="text" id="lng" name="lng">
								</div>
							</dir>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						<button type="button" onclick="submitNewLocation()" class="btn btn-primary" >Add Location</button>
					</div>
				</div>
		</div>	
	</div>
@endsection

@section('scriptImport')
<!-- Script yang import dari CDN ato Local ada di sini -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ url('js/dataTables.bootstrap.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY_MAP')}}&libraries=places&callback=initMap" async defer></script>
@endsection

@section('script')
<script type="text/javascript">
	// Script yang import dari CDN ato Local ada di sini
	console.log('Hi')

	$.ajax({
		type:"GET",
		url:"{{url('presence/setting/getListUser')}}",
		success: function(result){
			append = ""

			$.each(result,function(key,value){
				append = append + '<tr>'
				append = append + '<td>' + value.nik
				append = append + '</td>'
				append = append + '<td>' + value.name
				append = append + '</td>'
				append = append + '<td>' + value.role
				append = append + '</td>'
				append = append + '<td>' + value.location_name
				append = append + '</td>'
				append = append + '<td>' + value.setting_on_time
				append = append + '</td>'
				append = append + '<td>' + value.setting_check_out
				append = append + '</td>'
				append = append + '<td>' + '<button class="btn btn-sm btn-primary" onclick="editSchedule('+value.nik+')"><i class="fa fa-edit"></i> Schedule</button> <button class="btn btn-sm btn-warning" id="btnEditLocation" onclick="editLocation('+value.nik+')"><i class="fa fa-edit"></i> Location</button>'
				append = append + '</td>' 
				append = append + '</tr>'

				$("#tbody_setting").html(append)

		    })			

			$("#btnEditLocation").click(function(){
				$("#edit_location").modal('show')
			})

		$("#table_setting").DataTable({
			lengthChange: false
		})

			console.log(result)
		}
	})

	function searchCustom(id_table,id_seach_bar){
		$("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
	}

	function editSchedule(value){
		$("#edit_schedule").modal('show')
		$.ajax({
			type:"GET",
			url:"{{url('presence/setting/showSchedule')}}",
			data:{
				nik:value
			},
			success:function(result){
				changeName
				$("#changeName").html(result.data.name)
				$("#work_in_before").val(result.data.setting_on_time)
				$("#work_out_before").val(result.data.setting_check_out)
				$("#work_in_after").val(result.data.setting_on_time)
				$("#work_out_after").val(result.data.setting_check_out)

				$("#work_in_after").datetimepicker({
					format: 'HH:mm:ss',
					// date: result.data.setting_on_time
				})

				$("#work_out_after").datetimepicker({
					format: 'HH:mm:ss',
					// date: result.data.setting_check_out
				})

				$("#btnUpdateSchedule").click(function(){
					$("#btnUpdateSchedule").attr("onclick",updateSchedule(result.data.nik))
				})

			}
		})
	}

	function editLocation(value){
		$("#edit_location").modal('show')
		$.ajax({
			type:"GET",
			url:"{{url('presence/setting/showLocation')}}",
			data:{
				nik:value
			},
			success:function(result){
				$("#changeNameForLoc").html(result.data[0].name)
				$("#location_update").empty()
				// $("#location_before").val(result.data[0].location_name)
				// $("#location_before_id").val(result.data[0].location_id)
				// $("#location_after").empty()
				console.log(result.data[0].location_id)
				if (result.data[0].location_id != null) {
					var strx = result.data[0].location_id.split(',')
					var array = []
					array = array.concat(strx);

					var location_true = true
				}else{
					var location_true = false
				}

				$.ajax({
					type:"GET",
					url:"{{url('presence/setting/showLocationAll')}}",
					data:{
						nik:value
					},
					success:function(result){
						$("#location_update").select2({
							placeholder:" Select location",
					        multiple:true,
					        data:result.data
					    })

						if (location_true == true) {
							$("#location_update").val(array).trigger('change');
						}
					}
				})


				$("#btnUpdateLocation").click(function(){
					$("#btnUpdateLocation").attr("onclick",updateLocation(result.data[0].nik))
				})

				console.log(result)
			}
		})
	}

	function updateSchedule(nik){
		Swal.fire({
			    title: 'Are you sure?',  
			    text: "Update the Schedule",
			    icon: 'warning',
			    showCancelButton: true,
			    confirmButtonColor: '#3085d6',
			    cancelButtonColor: '#d33',
			    confirmButtonText: 'Yes',
			    cancelButtonText: 'No',
		}).then((result) => {
			if (result.value) {
	        Swal.fire({
	            title: 'Please Wait..!',
	            text: "It's sending..",
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
				url:"{{url('/presence/setting/setSchedule')}}",
				data:{
		            _token: "{{ csrf_token() }}",
					work_out:$("#work_out_after").val(),
					work_in:$("#work_in_after").val(),
					nik:nik
				},
				success:function(){
					Swal.showLoading()
	                Swal.fire(
	                    'Successfully!',
	                   	'Update Schedule.',
	                    'success'
	                ).then((result) => {
	                    if (result.value) {
	                    	location.reload()
	                    	$("#edit_schedule").modal('hide')
	                    }
	                })
				}
			})	      
		  }
		})	
	}

	function updateLocation(nik){
		Swal.fire({
			    title: 'Are you sure?',  
			    text: "Update the Location",
			    icon: 'warning',
			    showCancelButton: true,
			    confirmButtonColor: '#3085d6',
			    cancelButtonColor: '#d33',
			    confirmButtonText: 'Yes',
			    cancelButtonText: 'No',
		}).then((result) => {
			if (result.value) {
	        Swal.fire({
	            title: 'Please Wait..!',
	            text: "It's sending..",
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
				url:"{{url('/presence/setting/setLocation')}}",
				data:{
		            _token: "{{csrf_token()}}",
					// location_before_id:$("#location_before_id").val(),
					// location_after_id:$("#location_after").val(),
					location_update:$("#location_update").val(),
					nik:nik
				},
				success:function(){
					Swal.showLoading()
	                Swal.fire(
	                    'Successfully!',
	                   	'Update Location.',
	                    'success'
	                ).then((result) => {
	                    if (result.value) {
	                    	location.reload()
	                    	$("#edit_location").modal('hide')
	                    }
	                })
				}
			})	      
		  }
		})
	}

	function submitNewLocation(){
		Swal.fire({
            title: 'Please Wait..!',
            text: "It's sending..",
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
			url:"{{url('/presence/setting/addLocation')}}",
			data:{
	            _token: "{{ csrf_token() }}",
				location_name:$("#place").val(),
				location_lat:$("#lat").val(),
				location_lng:$("#lng").val(),
			},
			success:function(){
				Swal.showLoading()
                Swal.fire(
                    'Successfully!',
                   	'Location Added.',
                    'success'
                ).then((result) => {
                    if (result.value) {
                    	location.reload()
                    	$("#modal-addNewLocation").modal('hide')
                    }
                })
			}
		})
	}

	var map;
	function initMap(){
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -6.2297419, lng: 106.759478},
			zoom: 10,
			// zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			rotateControl: false,
			fullscreenControl: false
		});

		var autocomplete = new google.maps.places.Autocomplete((document.getElementById('search')));
		console.log(autocomplete)

		 map.addListener('click', function(result) {
			console.log(result.latLng)
			marker.setVisible(false);
			marker.setPosition(result.latLng);
			marker.setVisible(true);
			$("#lat").val(result.latLng.lat());
			$("#lng").val(result.latLng.lng());
		});

		var marker = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(0, -29),
			draggable: true
		});

		autocomplete.addListener('place_changed', function() {
			google.maps.event.trigger(map, 'resize');
			marker.setVisible(false);
			var place = autocomplete.getPlace();

			if (!place.geometry) {
				window.alert("No details available for input: " + place.name);
				return;
			}

			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);
			$("#lat").val(place.geometry.location.lat());
			$("#lng").val(place.geometry.location.lng());
		});

		// autocomplete.setTypes(["address"]);


		google.maps.event.addListener(marker, 'dragend', function (evt) {
			$("#lat").val(evt.latLng.lat());
			$("#lng").val(evt.latLng.lng());
		});
	}
</script>
@endsection