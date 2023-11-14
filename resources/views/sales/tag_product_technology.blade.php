@extends('template.main')
@section('tittle')
Tag Customer
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')
	<section class="content-header">
		<h1>
		  Category Tagging
		</h1>
		<ol class="breadcrumb">
		  <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		  <li class="active">Tag Brand & Technology</li>
		</ol>
	</section>

	<section class="content">
		@if (session('success'))
		<div class="alert alert-success" id="alert">
	        {{ session('success') }}
	    </div>
	    @endif

	    @if (session('update'))
	    <div class="alert alert-warning" id="alert">
	        {{ session('update') }}
	    </div>
	    @endif
		<div class="row">
			<div class="col-md-6">
				<div class="box">
			  		<div class="box-header">
			      		<div class="pull-left"><h4><i class="fa fa-table"></i> Brand Table</h4></div>
			  		</div>

			  		<div class="box-body">
			  			<button class="btn btn-sm btn-primary btn-add-product" style="margin-bottom: 10px"><i class="fa fa-plus"></i>  Brand</button>
			  			<div class="table-responsive">
			              <table class="table table-bordered table-striped display nowrap" id="dataTableProduct" width="100%" cellspacing="0">
				              <thead>
				                <tr>
				                  <th>Name</th>
				                  <th>Description</th>
				                  <th>Action</th>
				                </tr>
				              </thead>
				              <tbody>
				              	@foreach($product as $data)
				              	<tr>
				              		<td>{{$data->name_product}}</td>
				              		<td>{{$data->description_product}}</td>
				              		<td><button class="btn btn-warning btn-sm btn-detail-product" onclick="detailProduct('{{$data->id}}')" style="align-content: center;">Detail</button></td>
				              	</tr>
				              	@endforeach
				              </tbody>
				            </table>
			      		</div>
			  		</div>
			  	</div>
			</div>
			<div class="col-md-6">
				<div class="box">
			  		<div class="box-header">
			      		<div class="pull-left"><h4><i class="fa fa-table"></i> Technology Table </h4></div>
			  		</div>

			  		<div class="box-body">
			  			<button class="btn btn-sm btn-primary btn-add-tech" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Technology</button>
			  			<div class="table-responsive">
			              <table class="table table-bordered table-striped display nowrap" id="dataTableTechnology" width="100%" cellspacing="0">
				              <thead>
				                <tr>
				                  <th>Name</th>
				                  <th>Description</th>
				                  <th>Action</th>
				                </tr>
				              </thead>
				              <tbody>
				              	@foreach($technology as $data)
				              	<tr>
				              		<td>{{$data->name_tech}}</td>
				              		<td>{{$data->description_tech}}</td>
				              		<td><button class="btn btn-warning btn-sm btn-detail-tech" onclick="detailTech('{{$data->id}}')" style="align-content: center;">Detail</button></td>
				              	</tr>
				              	@endforeach
				              </tbody>
				            </table>
			      		</div>
			  		</div>
			  	</div>
			</div>
		</div>
		

		<!--Modal Add Product-->
		<div class="modal fade"  id="add_product" role="dialog">
	      <div class="modal-dialog modal-sm">
	        <!-- Modal content-->
	        <div class="modal-content">
	          <div class="modal-header">
	            <h4 class="modal-title">Add Product</h4>
	          </div>
	          <div class="modal-body">
	            <form method="POST" action="{{url('sales/store/product')}}">
	              @csrf
		           	<div class="form-group">
		              <label for="">Product Name</label>
		              <input type="text" class="form-control" id="name_product" name="name_product" placeholder="" required>
		            </div>
		            <div class="form-group">
		              <label for="top">Product Description</label>
		              <textarea type="text" class="form-control" id="desc_product" name="desc_product" placeholder="" required> </textarea>
		            </div>

			        <div class="modal-footer">
			           <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			           <button type="submit" class="btn btn-primary btn-submit-product"><i class="fa fa-check"></i> Submit</button>
			        </div>
	          </form>
	          </div>
	        </div>
	      </div>
	    </div>

	    <!--Modal Add Product-->
		<div class="modal fade"  id="add_technology" role="dialog">
	      <div class="modal-dialog modal-sm">
	        <!-- Modal content-->
	        <div class="modal-content">
	          <div class="modal-header">
	            <h4 class="modal-title">Add Technology</h4>
	          </div>
	          <div class="modal-body">
	            <form method="POST" action="{{url('sales/store/tech')}}">
	              @csrf
		           	<div class="form-group">
		              <label for="">Technology Name</label>
		              <input type="text" class="form-control" id="name_tech" name="name_tech" placeholder="" required>
		            </div>
		            <div class="form-group">
									<label for="">About</label>
									<select name="about" id="about" class="form-control">
										<option value="1">Enterprise Network Infrastructure</option>
										<option value="2">Data Center & Cloud</option>
										<option value="3">Cyber Security</option>
										<option value="4">Collaboration & Facility</option>
									</select>
								</div>
		            <div class="form-group">
		              <label for="top">Technology Description</label>
		              <textarea type="text" class="form-control" id="desc_tech" name="desc_tech" placeholder="" required> </textarea>
		            </div>

			        <div class="modal-footer">
			           <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			           <button type="submit" class="btn btn-warning btn-submit-product"><i class="fa fa-check"></i> Submit</button>
			        </div>
	          </form>
	          </div>
	        </div>
	      </div>
	    </div>

	    <!--Detail Product-->
		<div class="modal fade"  id="detail_product" role="dialog">
	      <div class="modal-dialog modal-sm">
	        <!-- Modal content-->
	        <div class="modal-content">
	          <div class="modal-header">
	            <h4 class="modal-title">Detail Product</h4>
	          </div>
	          <div class="modal-body">
	            <form method="POST" action="{{url('sales/update/product')}}">
	              @csrf
	              	<input type="text" class="form-control hidden" name="id_product_edit" id="id_product_edit">
	                <div class="form-group">
	                	<label>Create Date</label>
	                	<input type="text" class="form-control" id="date_add_product" readonly>
	                </div>
		           	<div class="form-group">
		              <label for="">Product Name</label>
		              <input type="text" class="form-control" name="name_product_edit" id="name_product_edit" placeholder="" required>
		            </div>
		            <div class="form-group">
		              <label for="top">Product Description</label>
		              <textarea type="text" class="form-control" name="desc_product_edit" id="desc_product_edit" placeholder="" required> </textarea>
		            </div>

			        <div class="modal-footer">
			           <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			           <button type="submit" class="btn btn-primary btn-submit-product"><i class="fa fa-check"></i> Submit</button>
			        </div>
	          </form>
	          </div>
	        </div>
	      </div>
	    </div>

	    <!--Detail Technology-->
		<div class="modal fade"  id="detail_technology" role="dialog">
	      <div class="modal-dialog modal-sm">
	        <!-- Modal content-->
	        <div class="modal-content">
	          <div class="modal-header">
	            <h4 class="modal-title">Detail Technology</h4>
	          </div>
	          <div class="modal-body">
	            <form method="POST" action="{{url('sales/update/technology')}}">
	              @csrf
	              <input type="text" class="form-control hidden" name="id_tech_edit" id="id_tech_edit">
	                <div class="form-group">
	                	<label>Create Date</label>
	                	<input type="text" class="form-control" id="date_add_technology" readonly>
	                </div>
		           	<div class="form-group">
		              <label for="">Product Name</label>
		              <input type="text" class="form-control" id="name_tech_edit" name="name_tech_edit" placeholder="" required>
		            </div>
		            <div class="form-group">
									<label for="">About</label>
									<select name="about_edit" id="about_edit" class="form-control">
										<option value="1">Enterprise Network Infrastructure</option>
										<option value="2">Data Center & Cloud</option>
										<option value="3">Cyber Security</option>
										<option value="4">Collaboration & Facility</option>
									</select>
								</div>
		            <div class="form-group">
		              <label for="top">Product Description</label>
		              <textarea type="text" class="form-control" id="desc_tech_edit" name="desc_tech_edit" placeholder="" required> </textarea>
		            </div>

			        <div class="modal-footer">
			           <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			           <button type="submit" class="btn btn-primary btn-submit-product"><i class="fa fa-check"></i> Submit</button>
			        </div>
	          </form>
	          </div>
	        </div>
	      </div>
	    </div>
	    
	  	
	</section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
	$("#dataTableProduct").DataTable();

	$("#dataTableTechnology").DataTable();

	$(".btn-add-product").click(function(){
		$("#add_product").modal("toggle");
	})

	$(".btn-add-tech").click(function(){
		$("#add_technology").modal("toggle");
	})

	function detailProduct(id){
		$.ajax({
          type:"GET",
          url:'/sales/detail_product',
          data:{
            id_product_tag:id,
          },
          success: function(result){
          	$('#id_product_edit').val(result[0].id)
           	$('#date_add_product').val(moment(result[0].date_add).format('L'));
           	$('#name_product_edit').val(result[0].name_product);
           	$('#desc_product_edit').val(result[0].description_product);
           
          }
        }); 
		$("#detail_product").modal("toggle");
	}

	function detailTech(id){
		$.ajax({
          type:"GET",
          url:'/sales/detail_tech',
          data:{
            id_tech_tag:id,
          },
          success: function(result){
          	$('#id_tech_edit').val(result[0].id)
           	$('#date_add_technology').val(moment(result[0].date_add).format('L'));
           	$('#name_tech_edit').val(result[0].name_tech);
           	$('#about_edit').val(result[0].about);
           	$('#desc_tech_edit').val(result[0].desc_tech);
           
          }
        }); 
		$("#detail_technology").modal("toggle");
	}

	// $(".btn-detail-product").click(function(){
	// 	$.ajax({
 //          type:"GET",
 //          url:'/sales/detail_product',
 //          data:{
 //            id_product_tag:this.value,
 //          },
 //          success: function(result){
 //           	$('#date_add_product').val(moment(result[0].date_add).format('L'));
 //           	$('#name_product_edit').val(result[0].name_product);
 //           	$('#desc_product_edit').val(result[0].description_product);
           
 //          }
 //        }); 
	// 	$("#detail_product").modal("toggle");
	// })

	$(".btn-detail-tech").click(function(){

	})

	$("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });
</script>
@endsection