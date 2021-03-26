@extends('template.template_admin-lte')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
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
	        	@if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR Manager')
	            <button class="btn btn-sm btn-success pull-right float-right margin-left-custom" id="AddGuide"><i class="fa fa-plus"> </i>&nbsp Guide</button>
	            @endif
	        </div>
	    </div>

	    <div class="box-body">
	    	<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs" id="myTab">
	              <li class="active" id="tab_1"><a href="#kebijakan" data-toggle="tab" onclick="changeTab('kebijakan')" aria-expanded="false">Kebijakan</a></li>
	              <li class="" id="tab_2"><a href="#peraturan" data-toggle="tab" onclick="changeTab('peraturan')" aria-expanded="true">Peraturan</a></li>
	              <li class="" id="tab_3"><a href="#other" data-toggle="tab" onclick="changeTab('other')" aria-expanded="false">Other</a></li>
	            </ul>
	            <div class="tab-content">
	              <div class="tab-pane active" id="kebijakan">
	              	<div class="table-responsive">
				        <table class="table table-bordered table-striped" id="tableIndex" width="100%" cellspacing="0">
				          <thead>
				            <tr>
				            <th width="5%">No</th>
				            <th width="20%">Title</th>
				            <th>Description</th>
				            <th width="30%">Source of Information</th>
				            <th width="15%">Action</th>
				            </tr>
				          </thead>
				          <tbody>
				          </tbody>
				          <tfoot>
				          </tfoot>
				        </table>
				    </div>
	              </div>
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
      <div class="modal-body" id="modal-body">
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<script type="text/javascript">
	autosizeWow() 

	function autosizeWow(){
		autosize(document.getElementById("description"));
		autosize(document.getElementById("link"));
	}	

	$("#AddGuide").click(function(){
		$('#titleModal').text('Add Guide Line')
		$('#submitGuide').show().attr("onclick","submitGuide('','submit')")

		$('#efective-date-form').show()

		$('#AddGuideModal').modal('show')		
		$('#modal-body').empty()
		prepend = ''
		prepend = prepend + '<div class="form-group" id="select-type">'
      		prepend = prepend + '<label>Type</label>'
      		prepend = prepend + '<select id="type" name="type" class="select2 form-control" style="width: 100%!important">'
      			prepend = prepend + '<option value="">Select Type...</option>'
      			prepend = prepend + '<option value="kebijakan">Kebijakan</option>'
      			prepend = prepend + '<option value="peraturan">Peraturan</option>'
      			prepend = prepend + '<option value="other">Other</option>'
      		prepend = prepend + '</select>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="title-form">'
      		prepend = prepend + '<label>Title</label>'
      		prepend = prepend + '<textarea class="form-control" id="title" name="title" height></textarea>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="desc-form">'
      		prepend = prepend + '<label>Description</label>'
      		prepend = prepend + '<textarea class="form-control" id="description" name="description" height></textarea>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="source-form">'
      		prepend = prepend + '<label>Source</label>'
      		prepend = prepend + '<textarea class="form-control" id="link" name="link"></textarea>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="efective-date-form">'
      		prepend = prepend + '<label>Efective Date</label>'
      		prepend = prepend + '<div class="input-group">'
	          prepend = prepend + '<div class="input-group-addon">'
	            prepend = prepend + '<i class="fa fa-calendar"></i>'
	          prepend = prepend + '</div>'
	      	  prepend = prepend + '<input type="text" class="form-control" name="efective_date" id="efective_date">'
	        prepend = prepend + '</div>'
      	prepend = prepend + '</div>'

      	$('#modal-body').html(prepend)

		title = 'Buat Kebijakan dan Peraturan'
		initDate()
		initSelect()
	})

	function viewGuide(id){
		$('#AddGuideModal').modal('show')

		$('#modal-body').empty()
		
		$.ajax({
	        url:"getGuideIndexById",
	        type:"GET",
	        data:{
	        	id:id,
	        },
	        success:function(result){
	        	console.log(result)
	        	prepend = ''
				$('#titleModal').html('<label>'+result[0].title_guide+'</label>')
				prepend = prepend + '<div class="form-group"><label>Description</label>'
				prepend = prepend + '<div>' + result[0].description + '</div>'
				prepend = prepend + '</div>'

				prepend = prepend + '<div class="form-group"><label>Efective Date</label>' + '<div>' + moment(result[0].efective_date).format('LL') + '</div>' + '</div>'
				prepend = prepend + '<hr>'
				prepend = prepend + '<div class="form-group">'
				prepend = prepend + '<label>Source of Information</label>'
				prepend = prepend + '<div><a href='+result[0].link_url+' target="_blank"><i class="fa fa-external-link fa-2x"></i></a>&nbsp' +result[0].link_url + '</div>'
				prepend = prepend + '</div>'

				$('#modal-body').html(prepend)
        	},
    	})
		$('#submitGuide').hide()
	}

	function editGuide(id){		
		$('#titleModal').text('Update Guide Line')
		$('#AddGuideModal').modal('show')

		$('#modal-body').empty()
		prepend = ''
      	prepend = prepend + '<div class="form-group" id="title-form">'
      		prepend = prepend + '<label>Title</label>'
      		prepend = prepend + '<textarea class="form-control" id="title" name="title" height></textarea>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="desc-form">'
      		prepend = prepend + '<label>Description</label>'
      		prepend = prepend + '<textarea class="form-control" id="description" name="description" height></textarea>'
      	prepend = prepend + '</div>'
      	prepend = prepend + '<div class="form-group" id="source-form">'
      		prepend = prepend + '<label>Source</label>'
      		prepend = prepend + '<textarea class="form-control" id="link" name="link"></textarea>'
      	prepend = prepend + '</div>'
      	$('#modal-body').html(prepend)

		$.ajax({
	        url:"getGuideIndexById",
	        type:"GET",
	        data:{
	        	id:id,
	        },
	        success:function(result){
	        	console.log(result)
	        	$('#description').val(result[0].description)
				$('#efective_date').val(result[0].efective_date)
				$('#link').val(result[0].link_url)
				$('#title').val(result[0].title_guide)
        	},
    	})
		
		initDate()
		initSelect()
		autosizeWow()
		$('#submitGuide').show().attr("onclick","submitGuide('"+id+"','update')")
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
	                    datatable.columns.adjust().draw();
	                    if (window.location.hash.replace('#','') == "") {
      						datatable.ajax.url("{{url('getGuideIndex')}}?type=kebijakan").load();
	                    }else{
      						datatable.ajax.url("{{url('getGuideIndex')}}?type="+window.location.hash.replace('#','')).load();
	                    }
	                  }
	                })
	              },
	            });
          	}	
		})
	}
	
	function initDate(){
		$("#efective_date").datepicker({
			autoclose: true,
		})
	}
	
	function initSelect(){
		$(".select2").select2({

		})
	}

	datatable = $("#tableIndex").DataTable({
		"processing": true,
		"ajax":{
		    "type":"GET",
		    "url":"{{url('getGuideIndex')}}",
	    },
	    "columns": [
	      { 
	      	render: function (data,type,row,meta){
	      		return meta.row+1
	      	}
	      },
	      { "data": "title_guide" },
	      {
	      	render: function(data,type,row,meta){
	      		return row.description.substring(0, 50) + ". . .";
	      	}
	      },
	      { 
	      	render: function(data,type,row,meta){
	      		return "<a href="+ row.link_url+"><u><i>"+ row.link_url +"</i></u></a>"

	      	}
	      },
	      { 
	      	render: function ( data, type, row, meta ) {
	      		if ('{{Auth::User()->id_division == "HR" && Auth::User()->id_position == "HR MANAGER"}}') {
	      			return '<button class="btn btn-sm btn-primary" style="width:35px;margin-right:5px" onclick="viewGuide('+row.id+')"><i class="fa fa-eye"></i></button><button class="btn btn-sm btn-warning" style="width:35px;margin-right:5px" onclick="editGuide('+row.id+')"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger" style="width:35px;margin-right:5px"  onclick="deleteGuide('+row.id+')"><i class="fa fa-trash"></i></button>'
	      		}else{
	      			return '<button class="btn btn-sm btn-primary" style="width:35px;margin-right:5px" onclick="viewGuide('+row.id+')"><i class="fa fa-eye"></i></button>'
	      		}   	            
	        }
	      },
	    ],
	})

	function submitGuide(id,status){
	    type = $('#type').val()

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
	        console.log(url)
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
	                link:$('#link').val(),
	                title:$('#title').val(),	                
	                type:$('#type').val(),
	                efective_date:moment($('#efective_date').val()).format('YYYY-MM-DD')
	              },
	              success: function(result){
	                Swal.showLoading()
	                Swal.fire(
	                  'Successfully!',
	                  'success'
	                ).then((result) => {
	                  if (result.value) {
	                    $("#AddGuideModal").modal('toggle')
	                    datatable.columns.adjust().draw();
      					datatable.ajax.url("{{url('getGuideIndex')}}?type="+type).load();
      					if (type == 'kebijakan') {
      						$('#tab_1').addClass('active')
      						$('#tab_2').removeClass('active')
      						$('#tab_3').removeClass('active')
      					}else if (type == 'peraturan') {
      						$('#tab_1').removeClass('active')
      						$('#tab_2').addClass('active')
      						$('#tab_3').removeClass('active')
      					}else{
      						$('#tab_1').removeClass('active')
      						$('#tab_2').removeClass('active')
      						$('#tab_3').addClass('active')
      					}
	                  }
	                })
	              },
	            });
          	}	        
        })
	}

	function changeTab(id){
		console.log(id)
		datatable.clear().draw();
      	datatable.ajax.url("{{url('getGuideIndex')}}?type="+id).load();
	}

    $('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');

    if (window.location.hash.replace('#','') == "") {
		datatable.ajax.url("{{url('getGuideIndex')}}?type=kebijakan").load();
	}else{
      	datatable.ajax.url("{{url('getGuideIndex')}}?type="+window.location.hash.replace('#','')).load();
	}
  </script>
</script>
@endsection
