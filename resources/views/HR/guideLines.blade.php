@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
	body{
		zoom:90%;
	}
	div.dataTables_processing { z-index: 1; 
</style>
<section class="content-header">
  <h1>Kebijakan & Peraturan</h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
</section>

<section class="content">
	<div class="box">
	    <div class="box-header with-border">
	        <div class="pull-right">
	            <button class="btn btn-sm btn-success pull-right float-right margin-left-custom" id="AddGuide"><i class="fa fa-plus"> </i>&nbsp Guide</button>
	        </div>
	    </div>

	    <div class="box-body">
		      <div class="table-responsive">
		        <table class="table table-bordered table-striped" id="tableIndex" width="100%" cellspacing="0">
		          <thead>
		            <tr>
		            <th>Kebijakan & peraturan</th>
		            <th width="40%">Source of Information</th>
		            <th width="20%">Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          	@foreach($data as $data)
		          		<tr>
			          		<td>{!!nl2br(e($data->description))!!}</td>
			          		<td><u><a style="cursor: pointer;"><i>{{$data->link_url}}</i></a></u></td>
			          		<td>
			          			<span class="desc-hidden" hidden></span>
			          			<button class="btn btn-sm btn-warning" onclick="editGuide('{{$data->id}}','{{$data->link_url}}')"><i class="fa fa-edit"></i> edit</button>
			          			<button class="btn btn-sm btn-danger" onclick="deleteGuide('{{$data->id}}')"><i class="fa fa-trash"></i> delete</button>
			          		</td>
			          	</tr>
		          	@endforeach
		          </tbody>
		          <tfoot>
		          </tfoot>
		        </table>
		      </div>
	  	</div>
    </div>
	</div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="AddGuideModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titleModal">Add Guide Line</h3>
      </div>
      <div class="modal-body" id="">
      	<div class="form-group">
      		<label>Kebijakan & peraturan</label>
      		<textarea class="form-control" id="description" name="description" height></textarea>
      	</div>
      	<div class="form-group">
      		<label>Source</label>
      		<textarea class="form-control" id="link" name="link"></textarea>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitGuide">Submit</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
	autosizeWow() 

	function autosizeWow(){
		autosize(document.getElementById("description"));
		autosize(document.getElementById("link"));
	}	

	$("#AddGuide").click(function(){
		$('#titleModal').text('Add Guide Line')		
		$('#AddGuideModal').modal('show')

		$('#submitGuide').attr("onclick","submitGuide('submit')")
		title = 'Buat Kebijakan dan Peraturan'
	})

	function editGuide(id,link){		
		$('#titleModal').text('Update Guide Line')
		$('#AddGuideModal').modal('show')
		$('#description').val($('.desc-hidden').text())
		autosizeWow()
		$('#link').val(link)

		$('#submitGuide').attr("onclick","submitGuide('"+id+"','update')")
		title = 'Update Kebijakan dan Peraturan'
	}

	function deleteGuide(id){
		Swal.fire({
		  title: 'Hapus Kebijakan & Peraturan?',
		  text: "Anda Yakin?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Ya, Hapus!'
		}).then((result) => {
			if (result.value) {
  		        Swal.fire({
	              title: 'Please Wait..!',
	              text: "Deleting..",
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
	              type:"GET",
	              url:"{{url('deleteGuide')}}",
	              data:{
	              	id:id
	              },
	              success: function(result){
	                Swal.showLoading()
	                Swal.fire(
	                  'Successfully!',
	                  'success'
	                ).then((result) => {
	                  if (result.value) {
	                    location.reload()
	                    datatable.columns.adjust().draw();
	                  }
	                })
	              },
	            });
          	}	
		})
	}
	datatable = $("#tableIndex").DataTable({
		 // "processing": true,
	})

	function submitGuide(id,status){
		if (status == 'submit') {
			swalAccept = Swal.fire({
	          title: title,
	          text: "Yakin?",
	          icon: 'warning',
	          showCancelButton: true,
	          confirmButtonColor: '#3085d6',
	          cancelButtonColor: '#d33',
	          confirmButtonText: 'Yes',
	          cancelButtonText: 'No',
	        })

	        url = "{{url('storeGuide')}}"
		}else{
			swalAccept = Swal.fire({
	          title: title,
	          text: "Yakin?",
	          icon: 'warning',
	          showCancelButton: true,
	          confirmButtonColor: '#3085d6',
	          cancelButtonColor: '#d33',
	          confirmButtonText: 'Yes',
	          cancelButtonText: 'No',
	        })

	        url = "{{url('updateGuide')}}"
		}		

        swalAccept.then((result) => {
  		    if (result.value) {
  		    console.log(result.value)
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
	              type:"GET",
	              url:url,
	              data:{
	              	id:id,
	                description:$('#description').val(),
	                link:$('#link').val()
	              },
	              success: function(result){
	                Swal.showLoading()
	                Swal.fire(
	                  'Successfully!',
	                  'success'
	                ).then((result) => {
	                  if (result.value) {
	                    location.reload()
	                    $("#AddGuideModal").modal('toggle')
	                    datatable.columns.adjust().draw();
	                  }
	                })
	              },
	            });
          	}	        
        })
	}
</script>
@endsection
