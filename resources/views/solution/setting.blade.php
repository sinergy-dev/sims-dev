@extends('template.main')
@section('tittle')
Setting
@endsection
@section('head_css')
  	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
	<style type="text/css">
		.dataTables_filter {
		    display: none;
		}
	</style>
@endsection
@section('content')
	<section class="content-header">
        <h1>
            Setting
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Setting</li>
        </ol><br>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3></h3>
                <div class="box-tools">
                    <button id="btnAddSetting" class="btn btn-sm bg-purple" style="margin-bottom: 10px;display: none;" onclick="addSetting()"><i class="fa fa-plus"></i>&nbsp Detail Item</button>
                </div>
            </div>

            <div class="box-body">
		        <div class="row">
		            <div class="col-md-4 pull-right" id="search-table">
		              <div class="input-group" style="margin-left: 10px">
		                <div class="input-group-btn">
		                  <button type="button" id="btnShowPID" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		                    Show 10 entries
		                  </button>
		                  <ul class="dropdown-menu">
		                    <li><a href="#" onclick="$('#tbListSetting').DataTable().page.len(10).draw();$('#btnShowPID').html('Show 10 entries')">10</a></li>
		                    <li><a href="#" onclick="$('#tbListSetting').DataTable().page.len(25).draw();$('#btnShowPID').html('Show 25 entries')">25</a></li>
		                    <li><a href="#" onclick="$('#tbListSetting').DataTable().page.len(50).draw();$('#btnShowPID').html('Show 50 entries')">50</a></li>
		                    <li><a href="#" onclick="$('#tbListSetting').DataTable().page.len(100).draw();$('#btnShowPID').html('Show 100 entries')">100</a></li>
		                  </ul>
		                </div>
		                <input id="searchBarList" type="text" class="form-control" placeholder="Search Anything" onkeyup="searchCustom('tbListSetting','searchBarList')">
		                <span class="input-group-btn">
		                  <button id="applyFilterTableSearch" type="button" class="btn btn-default btn-md" style="width: 40px" onclick="searchCustom('tbListSetting','searchBarList')">
		                    <i class="fa fa-fw fa-search"></i>
		                  </button>
		                </span>
		              </div>
		            </div>
		        </div>
		        <div class="table-responsive">
                    <table class="table table-striped" width="100%" id="tbListSetting">
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade in" id="modalSetting" style="padding-right: 17px;">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" fdprocessedid="zjrk7i">
					<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Setting</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Detail Item*</label>
						<input type="text" class="form-control" id="InputItem" name="inputItem" onkeyup="validateInput(this.id)">
						<span class="help-block" style="display:none">Please Fill Item Name!</span>
					</div>
					<div class="form-group">
						<label>Price*</label>
						<input type="text" class="form-control money" id="InputPrice" name="inputPrice" onkeyup="validateInput(this.id)">
						<span class="help-block" style="display:none;">Please Fill Item Price!</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal" fdprocessedid="u870yq">Close</button>
					<button type="button" class="btn btn-primary" fdprocessedid="lkeg8g" id="saveSetting" onclick="saveDetailItems('create')">Save</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scriptImport')
<!--datatable-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<!--mask js-->
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
<script type="text/javascript">
    $(".money").mask('000.000.000.000.000', {reverse: true})
    var formatter = new Intl.NumberFormat(['ban', 'id']);

    var accesable = @json($feature_item);

    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

	$(document).ready(function(){	
		var table = $('#tbListSetting').DataTable({
	      	"ajax":{
	            "type":"GET",
	            "url":"{{url('/sbe/getDetailItem')}}",
	        },
	        "columns": [
		        {
		        	title: "No",
		        	width: "5px",
		        	render: function (data, type, row, meta){
		               return ++meta.row  
		            }
		        },
	            {
	              title: "Detail Item",
	              data: "detail_item"
	            },
	            {
	              	title: "Price",
	            	render: function (data, type, row, meta){
		               return "IDR " + formatter.format(row.price)
		            }
	            },
	            {
	              title: "Action",
	              render:function(data, type, row)
		          {
		          	// return '<button class="btn btn-sm btn-danger" onclick="updateSetting('+ row.id +')">Delete</button>'
		          	return '<button class="btn btn-sm btn-danger" onclick="deleteSetting('+ row.id +')">Delete</button>'

		          },
	              data: null
	            },
	        ],
	        initComplete:function(){
	            if (!accesable.includes('colUpdateSetting')) {
	                table.columns(3).visible(false);
	            }
	        },
	        "bFilter": true,
	        "bSort":true,
	        // "bLengthChange": false,
	        "pageLength":10,
	        "bInfo": false
	    });

	})

	function searchCustom(id_table,id_seach_bar){
		$("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
	}

    function addSetting(){
    	$("#modalSetting").modal("show")
    	$("#saveSetting").attr("onclick","saveDetailItems('create')")   

    	$("input").val("")	
    	$("#saveSetting").addClass("btn-primary")
    	$("#saveSetting").removeClass("btn-warning")
    	$("#saveSetting").text("Create")

    }

    function updateSetting(id){
    	$("#modalSetting").modal("show")

    	$.ajax({
	        url:"{{url('/sbe/getDetailItem')}}",
	        type:"GET",
	        success:function(result){
	        	$.each(result.data,function(item,value){
	        		console.log(value)
	        		if (value.id == id) {
	        			$("#InputItem").next().after("<input id='id_items' hidden>")
	        			$("#id_items").val(id)
		        		$("#InputItem").val(value.detail_item)
		        		$("#InputPrice").val(formatter.format(value.price))
		        	}
	        	})	

	        	validateInput("InputItem")
    			validateInput("InputPrice")        	
	        }
      	})

    	$("#saveSetting").removeClass("btn-primary")
    	$("#saveSetting").addClass("btn-warning")
    	$("#saveSetting").text("Update")
    	$("#saveSetting").attr("onclick","saveDetailItems('update')")    	
    }

    function deleteSetting(id){
    	formData = new FormData
        formData.append("_token","{{ csrf_token() }}")      
        formData.append("id",id)

    	swalFireCustom = {
	      title: 'Are you sure?',
	      text: "Delete Items",
	      icon: 'warning',
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No',
	    }

	    swalSuccess = {
	        icon: 'success',
	        title: 'Items has been deleted!',
	        text: 'Click Ok to reload page',
	    }           

	    createPost(swalFireCustom,formData,swalSuccess,url="/sbe/deleteDetailItem",type="POST")
    }

    function saveDetailItems(status){
    	if ($("#InputItem").val() == "") {
    		$("#InputItem").next().show()
    		$("#InputItem").closest("div").addClass("has-error")
    	}else if ($("#InputPrice").val() == "") {
    		$("#InputPrice").next().show()
    		$("#InputPrice").closest("div").addClass("has-error")
    	}else{
    		formData = new FormData
            formData.append("_token","{{ csrf_token() }}")      
            formData.append("InputItem",$("#InputItem").val())
            formData.append("InputPrice",$("#InputPrice").val())

            if (status == 'create') {
            	var title = "Items has been created!"
            	var url = "/sbe/storeDetailItem"
            }else{
            	formData.append("id",$("#id_items").val())
            	var title = "Items has been Updated!"
            	var url = "/sbe/updateDetailItem"
            }

            swalFireCustom = {
              title: 'Are you sure?',
              text: "Submit Items",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
            }

            swalSuccess = {
                icon: 'success',
                title: title,
                text: 'Click Ok to reload page',
            }           

            createPost(swalFireCustom,formData,swalSuccess,url=url,type="POST")
    	}
    }

    function createPost(swalFireCustom,data,swalSuccess,url,type){
    	Swal.fire(swalFireCustom).then((result) => {
          if (result.value) {
            $.ajax({
              type:type,
              url:"{{url('/')}}"+url,
              processData: false,
              contentType: false,
              data:data,
              beforeSend:function(){
                Swal.fire({
                    title: 'Please Wait..!',
                    text: "It's sending..",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    customClass: {
                        popup: 'border-radius-0',
                    },
                })
                Swal.showLoading()
              },
              success: function(result)
              {
                Swal.fire(swalSuccess).then((result) => {
                  if (result.value) {
                    location.reload()
                  }
                })
              }
            })
          }
      })
    }

    function validateInput(val){
    	console.log(val)
    	if ($("#"+val).val() != "") {
    		$("#"+val).next().hide()
    		$("#"+val).closest("div").removeClass("has-error")
    	}
    }
</script>
@endsection