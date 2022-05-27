@extends('template.main')
@section('tittle')
Partnership
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link type="text/css" rel="stylesheet" href="{{asset('css/simplePagination.css')}}"/>
<style type="text/css">
	input {
	  -webkit-user-select: none;
	  -moz-user-select: none;
	  -ms-user-select: none;
	  -o-user-select: none;
	  user-select: none;          
	}

	input[readonly]{
		background-color:rgba(0,0,0,0) !important;
    border:none !important;
	}

	input.transparent-input {
		outline: 0;
	  border-width: 0 0 2px;
	  border-color: #00c0ef
	}

	input.transparent-input:focus {
		outline: 0;
	  border-width: 0 0 2px;
	  border-color: #00c0ef
	}

	table#table-detail td
	{
	    border: none !important;
	}

	.avatar-upload {
	  position: relative;
	  max-width: 205px;
	  margin: 20px auto;
	}
	.avatar-upload .avatar-edit {
	  position: absolute;
	  right: 12px;
	  z-index: 1;
	  top: 10px;
	}
	.avatar-upload .avatar-edit input {
	  display: none;
	}
	.avatar-upload .avatar-edit input + label {
	  display: inline-block;
	  width: 34px;
	  height: 34px;
	  margin-bottom: 0;
	  border-radius: 100%;
	  background: #FFFFFF;
	  border: 1px solid transparent;
	  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
	  cursor: pointer;
	  font-weight: normal;
	  transition: all 0.2s ease-in-out;
	}
	.avatar-upload .avatar-edit input + label:hover {
	  background: #f1f1f1;
	  border-color: #d6d6d6;
	}
	.avatar-upload .avatar-edit input + label:after {
	  content: "\f040";
	  font-family: 'FontAwesome';
	  color: #757575;
	  position: absolute;
	  top: 10px;
	  left: 0;
	  right: 0;
	  text-align: center;
	  margin: auto;
	}
	.avatar-upload .avatar-preview {
	  width: 192px;
	  height: 192px;
	  position: relative;
	  border-radius: 100%;
	  border: 6px solid #F8F8F8;
	  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
	}
	.avatar-upload .avatar-preview > div {
	  width: 100%;
	  height: 100%;
	  border-radius: 100%;
	  background-size: cover;
	  background-repeat: no-repeat;
	  background-position: center;
	}

	.simple-pagination ul {
	  margin: 0 0 20px;
	  padding: 0;
	  list-style: none;
	  text-align: center;
	}

	.simple-pagination li {
	  display: inline-block;
	  margin-right: 5px;
	}

	.simple-pagination li a,
	.simple-pagination li span {
	  color: #666;
	  text-decoration: none;
	  border: 1px solid #EEE;
	  background-color: #3c8dbc;
	  box-shadow: 0px 0px 10px 0px #EEE;
	}

	.simple-pagination .current {
	  color: #FFF;
	  background-color: #3c8dbc;
	  border-color: #3c8dbc;
	}

	.simple-pagination .prev.current,
	.simple-pagination .next.current {
	  background: #3c8dbc;
	}
</style>
@endsection
@section('content')
<section class="content-header">
	<!-- <button class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i> &nbspBack</button> -->
  <h1>
    Partnership Detail
  </h1>
  <ol class="breadcrumb">
  	<li><a href="{{url('/partnership')}}"><i class="fa fa-list"></i> Partnership</a></li>
    <li class="active">Partnership detail</li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-3 col-xs-12">
			<div class="box box-primary">
				<div class="box-body">
					<form id="formEdit" enctype="multipart/form-data">
            	@csrf
          <div style="align-items: center;" class="mb-3">
          	<div class="avatar-upload">
				        <div class="avatar-edit" style="display:none">
				            <input type='file' id="imageUpload" name="logo" accept=".png, .jpg, .jpeg" />
				            <label for="imageUpload"></label>
				        </div>
				        <div class="avatar-preview">
				          <!--   <div id="imagePreview" name="logo"  style="background-image: url('https://seeklogo.com/images/C/cisco-networking-academy-logo-0B2566178E-seeklogo.com.png');">
				            </div> -->
				            <div id="imagePreview" name="logo" style="background-image: url('{{ asset('image/logo_partnership/'.$data->logo)}}')">
				            </div>
				            <!-- <img id="imagePreview" name="logo" src="{{ asset('image/logo_partnership/'.$data->logo)}}"> -->
				          <!--   <div id="imagePreview" name="logo"  style="background-image: url('')">
				            </div> -->
				        </div>
				    </div>
          </div>
          <div style="padding-top:10px">
	          	<table class="table" id="table-detail">
		          		<tr>
		          			<input type="" name="id_edit" id="id_edit" value="{{$data->id_partnership}}" style="display:none">
		          			<td style="vertical-align: middle;">Partner Name</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="partner_edit" name="partner_edit" value="{{$data->partner}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">Current Level</td>
		          			<th>
		          				<input class="form-control transparent-input" readonly type="text" id="level_edit" name="level_edit" value="{{$data->level}}">
		          			</th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">Level</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="levelling_edit" name="levelling_edit" value="{{$data->levelling}}"></th>
		          		</tr>		          		
		          		<tr>
		          			<td style="vertical-align: middle;">Type</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="type_edit" name="type_edit" value="{{$data->type}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">Renewal Update</td>
		          			<th><input class="form-control transparent-input" readonly type="date" id="renewal_edit" name="renewal_edit" value="{{$data->renewal_date}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">Annual Fee</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="annual_edit" name="annual_edit" value="{{$data->annual_fee}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">CAM</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="cam_edit" name="cam_edit" value="{{$data->cam_name}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">CAM Phone</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="phone_edit" name="phone_edit" value="{{$data->cam_phone}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">CAM Email</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="email_edit" name="email_edit" value="{{$data->cam_email}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">Email Support</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="support_edit" name="support_edit" value="{{$data->email_support}}"></th>
		          		</tr>
		          		<tr>
		          			<td style="vertical-align: middle;">ID Mitra</td>
		          			<th><input class="form-control transparent-input" readonly type="text" id="mitra_edit" name="mitra_edit" value="{{$data->id_mitra}}"></th>
		          		</tr>
	          	</table>
          	</form>
          	<a class="btn btn-primary btn-block" id="btn-edit" type="button"><b>Edit</b></a>
        		<a href="{{url('show_cuti')}}" class="btn btn-danger btn-block" id="btn-delete" type="button"><b>Delete</b></a>
          </div>
    		</div>
 			</div>
 		</div>
    <div class="col-md-9 col-xs-12">
      	<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_1" data-toggle="tab">Certification List</a>
						</li>
						<li class=""><a href="#tab_2" data-toggle="tab">Certification Partner</a></li>
						<li><a href="#tab_3" data-toggle="tab">Sales Target</a></li>
						<li><a href="#tab_4" data-toggle="tab">Log Activity</a></li>

					<!-- 	<li class="pull-right">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
							<ul class="dropdown-menu">
								<li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="btnAddCert"><i class="fa fa-plus"></i> Certification</a></li>
							</ul>
						</li> -->
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div>
								<a id="btnAddCert" style="cursor:pointer;"><i class="fa fa-plus"></i>&nbspCertification</a>
							</div>
							<div class="col-lg-4 col-xs-6">
						      
					    </div>
							<div id="list-cert">
								
							</div>
						</div>

						<div class="tab-pane " id="tab_2">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="fa fa-image"></i>
										<h3 class="box-title">Certificate Partner</h3>
										<a id="btnAddCert" class="pull-right" style="cursor:pointer;"><i class="fa fa-plus"></i>&nbspCertificate Partner</a>
								</div>

								<div class="box-body">
									<div class="post">
										<div class="row margin-bottom">
												<div class="col-sm-6">
													<img class="img-responsive" style="border: 1px solid " id="img-preview" src="https://www.tcandc.com/images/CiscoCertificate2.jpg" alt="Photo">
												</div>																			
												<div class="paging col-sm-6">
														<div class="row">
															<div class="col-sm-6">																
																<img class="img-responsive" style="cursor: pointer;" src="https://pegu6.files.wordpress.com/2017/08/td-cisco-certificate-of-completion-us-federal-authorization_pegasus-enterprise-holdings-llc.jpg" alt="Photo">
															</div>
														</div>
														<div id="pagination-container" class="text-center" style="margin-top: 10px;">
														</div>
												</div>
										</div>
										<div class="timeline-item">
											<span class="time pull-right"><i class="fa fa-clock-o"></i> 27 mins ago</span>
											<div class="timeline-body">
												Cisco Partner Lorem Ipsum is simply dummy text of the printing and typesetting industry.
												Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
												when an unknown printer took a galley of type and scrambled it to make a type specimen book.
											</div>
											<div class="timeline-footer">
												<a class="btn btn-warning btn-flat btn-xs">Update</a>
											</div>
										</div>
									
									</div>										
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_3">
							Lorem Ipsum is simply dummy text of the printing and typesetting industry.
							Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
							when an unknown printer took a galley of type and scrambled it to make a type specimen book.
							It has survived not only five centuries, but also the leap into electronic typesetting,
							remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
							sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
							like Aldus PageMaker including versions of Lorem Ipsum.
						</div>

						<div class="tab-pane" id="tab_4">
							There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
						</div>

					</div>
				</div>
    </div>
	</div>
	<!--MODAL-->
	<div class="modal fade" id="modalAddCert" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
            <button type="button" data-dismiss="modal" class="close" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Add Partnership</h4>
        </div>
        <div class="modal-body">
        	<form id="formAddCert">  
        		@csrf  
        		<i class="fa fa-table"></i><label>Certificate list</label>    		
	        	<table class="table">
				      <thead>
				        <tr>
				        	<input hidden type="" name="id_partnership" value="{{$data->id_partnership}}">
				          <th>Certificate Level</th>
				          <th>Certificate Name</th>
				          <th>Person</th>
				          <td class="text-center">
				            <button class="btn btn-xs btn-primary" onclick="addListCert()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
				              <i class="fa fa-plus"></i>
				            </button> 
				          </td>
				        </tr>
				      </thead>
				      <tbody id="tbListCert">
				      </tbody>
				    </table>
				    <i class="fa fa-table"></i><label>Sales Target</label> 
				    <table class="table">
				      <thead>
				        <tr>
				          <th>Target</th>
				          <th>Nominal</th>
				          <th>Apaya kemarin</th>
				          <td class="text-center">
				            <button class="btn btn-xs btn-primary" onclick="addSalesTarget()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
				              <i class="fa fa-plus"></i>
				            </button> 
				          </td>
				        </tr>
				      </thead>
				      <tbody id="tbSalesTarget">
				      </tbody>
				    </table>
        	</form>			    
			    <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="btnSubmitCert">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scriptImport')
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
 <script type="text/javascript" src="{{asset('js/jquery.simplePagination.js')}}"></script>
 <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
 <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
@endsection
@section('script')
<script type="text/javascript">
	$(".money").mask('000.000.000.000.000', {reverse: true})
	localStorage.setItem("status","initial")
	$("#btn-edit").click(function(){
	console.log(localStorage.getItem("status"))
		if (localStorage.getItem("status") == "initial") {
			$("#btn-edit").removeClass("btn-primary").addClass("btn-warning").html("Save").css('font-weight', 'bold')
			localStorage.setItem("status", "update");
			$(":input[type=date],:input[type=text][readonly='readonly']").attr("readonly", false);
			$(":input[type=text]").focus()
			$(".avatar-edit").css("display", "block")
		} else {
			$("btn-edit").attr("onclick",btnUpdate())
		}
	})

	function btnUpdate(){
			const fileupload = $('#imageUpload').prop('files')[0];

      var nama_file = $('#imageUpload').val();
			let formData = new FormData();

			if (nama_file!="" && fileupload!="") {
				formData.append('fileupload', fileupload);
      	formData.append('nama_file', nama_file);
			}

			formData.append('_token',"{{csrf_token()}}")
			formData.append('id_edit', $("#id_edit").val())
			formData.append('partner_edit', $("#partner_edit").val())
    	formData.append('level_edit', $("#level_edit").val())
    	formData.append('levelling_edit', $("#levelling_edit").val())
    	formData.append('type_edit', $("#type_edit").val())
    	formData.append('renewal_edit', $("#renewal_edit").val())
    	formData.append('annual_edit', $("#annual_edit").val())
    	formData.append('cam_edit', $("#cam_edit").val())
    	formData.append('phone_edit', $("#phone_edit").val())
    	formData.append('email_edit', $("#email_edit").val())
    	formData.append('support_edit', $("#support_edit").val())
    	formData.append('mitra_edit', $("#mitra_edit").val())      

			Swal.fire({
        title: 'Update partnership detail',
        text: "Are you sure?",
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
            text: "It's updating..",
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
              url: "{{'/update_partnership'}}",
              type: 'post',
              processData: false,
		          contentType: false,
              // dataType: 'application/json',
              data: formData, // serializes the form's elements.
            success: function(data)
            {
                Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                    	localStorage.setItem("status", "initial");
											$("#btn-edit").removeClass("btn-warning").addClass("btn-primary").html("Edit").css('font-weight', 'bold')
                      location.reload()
                    }
                })
            }
          }); 
        }    
      })
	}

	var i = 0;
  function addListCert(){
    i++;
    var append = ""
    append = append + "<tr class='new-list'>"
    append = append + " <td>"
    append = append + " <input data-value='" + i + "' name='cert_type[]' id='cert_type' class='form-control' type='text' placeholder='Enter Certificate Name'>"
    append = append + " </td>"
    append = append + " <td>"
    append = append + "<input data-value='" + i + "' name='cert_name[]' id='cert_name' class='form-control' type='text' placeholder='Enter Certificate Type'>"
    append = append + " </td>"
    append = append + " <td style='white-space: nowrap'>"
    append = append + " <select class='form-control select2-person' data-value='" + i + "' id='select2-person' style='width: 100%!important' name='cert_person[]'></select> "
    append = append + " </td>"
    append = append + " <td class='text-center'>"
    append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash-list'>"
    append = append + " <i class='fa fa-trash'></i>"
    append = append + " </button>"
    append = append + " </td>"
    append = append + "</tr>"
    
    $("#tbListCert").append(append)
    $.ajax({
      url: "{{url('/partnership/getUser')}}",
      type: "GET",
      success: function(result) {
        console.log(result)
        $("#select2-person[data-value='" + i + "']").select2({
            dropdownParent: $('#modalAddCert'),
            placeholder: "Select Person",
            data: result.data
        })
      }
    }) 
  }

	$("#btnAddCert").click(function(){
		$("#modalAddCert").modal("show")
	})

	$("#btnSubmitCert").click(function(){
			Swal.fire({
        title: 'Add New Partnership',
        text: "Are you sure?",
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
            text: "It's updating..",
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
              url: "{{'/partnership/addCertList'}}",
              type: 'post',
              // dataType: 'application/json',
              data: $("#formAddCert").serialize(), // serializes the form's elements.
            success: function(data)
            {
                Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                })
            }
          }); 
        }    
      })
	})

	$(document).ready(function(){
		append = ""
		$.ajax({
		    url: "{{url('/partnership/getCert')}}",
		    data: {
		    	id:"{{$data->id_partnership}}"
		    },
		    type: "GET",
		    success: function(result) {
		    			append = append + "<div class='row'>"
		    				append = append + "<div class='col-md-12'>"
		        	$.each(result.data.cert_user,function(key,value){
		    				append = append + "<h3>" + key +"</h3>"

		    				append = append + "<div class='row'>"
		        		$.each(value,function(key,data){
			    					append = append + "<div class='col-md-4'>"
			        			append = append + '<div class="box box-info">'
									  append = append + '<div class="box-header with-border">'
										append = append + 	'<h3 class="box-title">'+ data.name_certification+'</h3>'
										append = append + '</div>'
										append = append + '<div class="box-body">'
										append = append +   '<div style="float: left">'
										append = append + '<span><b>'+ data.name +'</b></span>'
										append = append +   '</div>'					    
										append = append +	'</div>'
										append = append +	'<div class="box-footer">'
										append = append +	'<button value="'+data.id+'" class="btn btn-xs btn-danger pull-right margin-left btn-delete-cert" style="vertical-align: top; width: 60px">Delete</button>'
										append = append +	' <button class="btn btn-xs btn-primary pull-right btn-edit-cert" value="'+data.id+","+data.nik+","+data.name_certification+'" name="edit_hurec" style="vertical-align: top; width: 60px;margin-right:10px">Edit</button>'
										append = append +	'</div>'
										append = append + '</div>'
			    				append = append + "</div>"

		        		})
		    				append = append + "</div>"

		        	})
		    			append = append + "</div>"
		    		append = append + "</div>"

        	$("#list-cert").html(append)

        	$(".btn-edit-cert").click(function(){
						console.log(this.value.split(","))
						append = append + '			<div class="modal fade" id="myModal" role="dialog">'
    				append = append + '				<div class="modal-dialog">'
      			append = append + '					<div class="modal-content">'
        		append = append + '				<div class="modal-header">'
						append = append + '          <h4>Edit Partnership Certificate</h4>'
						append = append + '       </div>'
						append = append + '   		<div class="modal-body">'
						append = append + '    		<input hidden id="id_cert_edit" value="'+ this.value.split(",")[0] +'">'
						append = append + '				<table class="table table-bordered">'
						append = append + '				<tr>'
						append = append + "					<td>"
						append = append + "						<label>Certification Name</label>"
				    append = append + "						<input id='cert_name_edit' class='form-control' type='text' 																	placeholder='Enter Certificate Type' value='"+ this.value.split(",")[2] +"'>"
				    append = append + " 				</td>"
				    append = append + " 				<td>"
						append = append + "						<label>Person</label>"
				    append = append + " 					<select class='form-control select2' id='cert_user_edit' style='width: 																					100%!important'></select> "
				    append = append + " 				</td>"
				    append = append + '				</tr>'
						append = append + '				</table>'
						append = append + '			<div class="modal-footer">'
						append = append + '		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>'
						append = append + '		<button type="button" class="btn btn-primary" id="btnEditCert">Submit</button>'
						append = append + '	</div>'
						append = append + '        </div>'
						append = append + '       </div>'
						append = append + '   </div>'
						append = append + '</div>'
						$("body").append(append);
						//here you force modal to be open
        		$("#myModal").modal('show')
        		$("#cert_user_edit").val(this.value.split(",")[1]).trigger("change")

        		$.ajax({
				      url: "{{url('/partnership/getUser')}}",
				      type: "GET",
				      success: function(result) {
				        $("#cert_user_edit").select2({
			            dropdownParent: $('#myModal'),
			            placeholder: "Select Person",
			            data: result.data
				        })
				      }
				    })
				    
				    $("#btnEditCert").click(function(){
							Swal.fire({
				        title: 'Update Certification User',
				        text: "Are you sure?",
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
				            text: "It's updating..",
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
				              url: "{{'/partnership/updateCertPerson'}}",
				              type: 'post',
				              data:{
		        						"_token": "{{ csrf_token() }}",
				              	id_cert_edit:$("#id_cert_edit").val(),
				              	cert_name_edit:$("#cert_name_edit").val(),
				              	cert_user_edit:$("#cert_user_edit").val(),
				              },
				            success: function(data)
				            {
				                Swal.showLoading()
				                  Swal.fire(
				                    'Successfully!',
				                    'success'
				                  ).then((result) => {
				                    if (result.value) {
				                      location.reload()
				                    }
				                })
				            }
				          }); 
				        }    
				      })
						})
					})

					$(".btn-delete-cert").click(function(){
						Swal.fire({
			        title: 'Delete Certificate User',
			        text: "Are you sure?",
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
			            text: "It's updating..",
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
			              url: "{{url('/partnership/deleteCertPerson')}}",
			              type: 'post',
			              data:{
		        					"_token": "{{ csrf_token() }}",
			              	id:this.value
			              },
			            success: function(data)
			            {
			                Swal.showLoading()
			                  Swal.fire(
			                    'Successfully!',
			                    'success'
			                  ).then((result) => {
			                    if (result.value) {
			                      location.reload()
			                    }
			                })
			            }
			          }); 
			        }    
			      })
					})
		    }
		})
		
	 	var items = $(".post .row .paging .row");
    var numItems = items.length;
    var perPage = 1;

    items.slice(perPage).hide();

    $('#pagination-container').pagination({
        items: numItems,
        itemsOnPage: perPage,
        prevText: "&laquo;",
        nextText: "&raquo;",
        onPageClick: function (pageNumber) {
            var showFrom = perPage * (pageNumber - 1);
            var showTo = showFrom + perPage;
            items.hide().slice(showFrom, showTo).show();
        }
    });
	})

	function readURL(input) {
    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function(e) {
	            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
	            $('#imagePreview').hide();
	            $('#imagePreview').fadeIn(650);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$("#imageUpload").change(function() {
	    readURL(this);
	    $("#imageUpload").val(this.value)
	});

	$('body').on('click','img',function(){
		var imgs = $(this).attr('src');
		console.log(imgs)
		$("#img-preview").attr("src",imgs);
	})
	$(".image-responsive").click(function(){
		
	})


</script>
@endsection