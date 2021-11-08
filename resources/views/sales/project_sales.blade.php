@extends('template.main')
@section('tittle')
Lead Register
@endsection
@section('head_css')
<!--datepicker-->
<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<style type="text/css">
	.pace .pace-progress {
		background: #ffffff;
		position: fixed;
		z-index: 2000;
		top: 0;
		right: 100%;
		width: 100%;
		height: 2px;
	}

	input[type=text]:focus{
	    border-color:dodgerBlue;
	    box-shadow:0 0 8px 0 dodgerBlue;
	}

	input[type=number]:focus{
	    border-color:dodgerBlue;
	    box-shadow:0 0 8px 0 dodgerBlue;
	}

	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	  -webkit-appearance: none;
	  margin: 0;
	}

	/* Firefox */
	input[type=number] {
	  -moz-appearance: textfield;

	}

	.dataTables_filter {
		display: none;
	}

	.has-error .select2-selection {
	    border-color: rgb(185, 74, 72) !important;
	}

	.select2{
		width: 100%;
	}

	.btn-width-custom{
		width: 50px;
		margin-right: 5px;
		margin-bottom: 5px;
	}

	.status-initial{
		padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #7735a3;
	    text-align: center;
	    width: 50px;
	    color: white;
	}

	.status-open{
	    padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #f2562b;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
	.status-pending{
		padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #ced2d9;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
	.status-sd{
	    padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #04dda3;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
	.status-tp{
	    padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #f7e127;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
	.status-win{
	    padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #246d18;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
	.status-lose{
	    padding: .35em;
	    font-size: 75%;
	    font-weight: 700;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: center;
	    border-radius: .25em;
	    background-color: #e5140d;
	    text-align: center;
	    width: 50px;
	    color: white;
	}
</style>
@endsection
@section('content')
	<section class="content-header">
		<h1>
		Lead Register
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<section class="content">
		<div class="row" id="BoxId" style="display:none;">
        	<!--box id-->
  	</div>
  	<div class="row">
  		<div class="col-lg-2 col-xs-12">
  			<section class="sidebar">
  				<div class="box box-primary">
	  				<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-filter"></i></i>Filter</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse" id="collapse-left"><i class="fa fa-minus"></i></button>
						</div>
						</div>
	  				<div class="box-body" id="filter-body">
	  					<div class="form-group">
								<label>Tahun</label>
								<select class="select2 form-control" style="width:100%" id="year_dif" onchange="searchCustom()">
									@foreach($year as $years)
			              @if($years->year < $year_now)
			                <option value="{{$years->year}}">{{$years->year}}</option>
			              @endif
			            @endforeach
			            <option selected value="{{$year_now}}">{{$year_now}}</option>
								</select>
							</div>
							<div class="form-group" id="filter-com" style="display:none;">								
							</div>
							<div class="form-group" id="filter-territory" style="display:none;">								
							</div>
							<div class="form-group" id="filter-sales" style="display:none;">
								<label>Sales</label>
							  <select class="form-control select2" style="width: 100%;" id="filter_sales"  name="filter_sales" onchange="searchCustom()">
	              </select>
							</div>
							<div class="form-group" id="filter-sales-manager" style="display:none;">
								<label>Sales</label>
							  <select class="form-control select2" style="width: 100%;" id="filter_sales_manager"  name="filter_sales_manager" onchange="searchCustom()">
	              </select>
							</div>
							<div class="form-group" id="filter-presales" style="display:none;">
								<label>Presales</label>
							  <select class="form-control select2" style="width: 100%;" id="filter_presales"  name="filter_presales" onchange="searchCustom()">
	              </select>
							</div>
							<div class="form-group" id="filter-customer">
								<label>Customer</label>
								<select class="form-control select2" style="width: 100%" id="filter_customer" name="filter_customer" onchange="searchCustom()"></select>
							</div>
							<div class="form-group" id="filter-result">
							</div>
							<div class="form-group">
								<label>Tag Product & Technology</label>
							  <select class="form-control select2" style="width: 100%;" id="searchTags"  name="searchTags" onchange="searchCustom()">
	              </select>
							</div>
						</div>  				
	  			</div>	
  			</section>
  		</div>
  		<div class="col-lg-10 col-xs-12">
  			<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-table"></i> Lead Register</h3>
			    </div>
			    <div class="box-body">
			    	<div class="row">
			    		<dir class="col-md-8" style="margin-bottom: 0px; margin-top: 0px;">	
			    			<button type="button" class="btn btn-sm btn-primary" style="height:30px;display: none;" id="btn_add_sales" onclick="add_lead()"><i class="fa fa-plus"> </i>&nbsp Lead Register</button>		    		
				    	</dir>
							<dir class="col-md-4 text-right" style="margin-bottom: 0px; margin-top: 0px;">
								<div class="input-group pull-right">
									<input id="searchLead" type="text" class="form-control" onkeyup="searchCustom('tableLead','searchLead')" placeholder="Search Anything">							
									<div class="input-group-btn">
										<button type="button" id="btnShowEntryLead" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											Show 10 entries
										</button>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="$('#tableLead').DataTable().page.len(10).draw();$('#btnShowEntryLead').html('Show 10 entries')">10</a></li>
											<li><a href="#" onclick="$('#tableLead').DataTable().page.len(25).draw();$('#btnShowEntryLead').html('Show 25 entries')">25</a></li>
											<li><a href="#" onclick="$('#tableLead').DataTable().page.len(50).draw();$('#btnShowEntryLead').html('Show 50 entries')">50</a></li>
											<li><a href="#" onclick="$('#tableLead').DataTable().page.len(100).draw();$('#btnShowEntryLead').html('Show 100 entries')">100</a></li>
										</ul>
									</div>
									<span class="input-group-btn">
										<button onclick="searchCustom('tableLead','searchLead')" type="button" class="btn btn-default btn-flat">
											<i class="fa fa-fw fa-search"></i>
										</button>
									</span>
								</div>
							</dir>			    		
			    	</div>			    	
			    	<div class="table-responsive">
			    		<table id="tableLead" class="table table-bordered table-striped dataTables_wrapper" role="grid" aria-describedby="example1_info">
			        	<thead>
									<tr>
										<th>Lead ID</th>
										<th>Customer</th>
										<th>Opty Name</th>
										<th>Create Date</th>
										<th>Closing Date</th>
										<th>Owner</th>
										<th>Presales</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tfoot>
			            <tr>
			                <th colspan="6" style="text-align:right">Total:</th>
			                <th></th>
			                <th colspan="3"></th>
			            </tr>
				        </tfoot>
			        </table>
			    	</div>			        	
					</div>		    
				</div> 
  		</div>
  		 		
  	</div> 		
	</section>

	<div class="modal fade" id="modal_lead" role="dialog">
	    <div class="modal-dialog modal-md">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Add Lead Register</h4>
	        </div>
	        <div class="modal-body">
	          <!-- <form method="POST" action="{{url('store')}}" id="modalSalesLead" name="modalSalesLead"> -->
	            @csrf
            <div class="form-group" style="display:none" id="ownerSales">
              <label for="">Owner</label>
              <select class="form-control select2" style="width: 100%;" id="owner_sales"  name="owner_sales">
                <option value="">-- Select Sales --</option>
              </select>
              <span class="help-block" style="display:none">Please Choose Owner Sales!</span>
            </div>

	          <div class="form-group">
	            <label for="">Customer (Brand Name)</label>
	             <select class="form-control select2" style="width: 100%;" id="contact" onkeyup="copytextbox();" name="contact" required>
	              <option value="">-- Select Contact --</option>
	            </select>
	            <span class="help-block" style="display:none">Please Choose Customer!</span>
	          </div>

	          <div class="form-group">
	            <label for="">Opportunity Name</label>
	            <input type="text" class="form-control" placeholder="Enter Opportunity Name" name="opp_name" id="opp_name">
	            <span class="help-block" style="display:none">Please Fill Opportunity Name!</span>
	          </div>

	          <div class="form-group">
	            <label for="">Amount</label>
	            <div class="input-group">
	              <div class="input-group-addon" style="background-color:#aaa;color:white">
	                <b><i>Rp</i></b>
	              </div>
	              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" pattern= "[0-9]">
	            </div>
	            <span class="help-block" style="display:none">Please Fill Amount!</span>
	          </div>

	          <div class="form-group">
	            <label for="">Closing Date</label>
	            <div class="input-group date">
	              <div class="input-group-addon" style="background-color:#aaa;color:white">
	                <i class="fa fa-calendar"></i>
	              </div>
	              <input type="text" class="form-control pull-right" name="closing_date" id="closing_date">
	            </div>
	            <span class="help-block" style="display:none">Please Select Date!</span>

	          </div>

	          <div class="form-group">
	            <label for="">Note</label>
	            <input type="text" class="form-control" placeholder="Enter Note" name="note" id="note">
	          </div>
	            <div class="modal-footer">
	              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
	              <button type="button" onclick="submitLead()" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
	            </div>
	        </form>
	        </div>
	      </div>
	    </div>
	</div>

	<div class="modal fade" id="edit_lead_register" role="dialog">
	    <div class="modal-dialog modal-md">
	        <!-- Modal content-->
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">Edit Lead Register</h4>
	            </div>
	            <div class="modal-body">
	                <!-- <form method="POST" action="{{url('update_lead_register')}}" id="modal_edit_saleslead" name="modal_edit_saleslead"> -->
	                    @csrf

	                    <input type="" name="lead_id_edit" id="lead_id_edit" hidden>

	                    <div class="form-group">
	                        <label for="">Opportunity Name</label>
	                        <textarea type="text" class="form-control" placeholder="Enter Opportunity Name" name="opp_name_edit" id="opp_name_edit">
		          						</textarea>
	                    </div>

	                    <div class="form-group">
						            <label for="">Amount</label>
						            <div class="input-group">
						              <div class="input-group-addon">
						                <b><i>Rp</i></b>
						              </div>
						              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount_edit" id="amount_edit" pattern= "[0-9]">
						            </div>
						            <span class="help-block" style="display:none">Please Fill Amount!</span>
						          </div>

	                    <div class="form-group">
	                        <label for="">Closing Date</label>
	                        <div class="input-group">
	                            <div class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </div>
	                            <input type="text" class="form-control pull-right" name="closing_date_edit" id="closing_date_edit">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="">Note (jika perlu)</label>
	                        <input type="text" class="form-control" placeholder="Enter Note" name="note_edit" id="note_edit">
	                    </div>

	                    <div class="form-group">
	                        <label>Product Tag</label>
	                        <select class="js-product-multiple select2" style="width:100%" name="product_edit[]" id="product_edit" multiple="multiple">

	                        </select>
	                    </div>

	                    <div>
	                        <label>Technology Tag</label>
	                        <select class="js-technology-multiple select" style="width:100%" name="technology_edit[]" id="technology_edit" multiple="multiple">

	                        </select>
	                    </div>

	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
	                        <button type="submit" class="btn btn-primary" onclick="editLeadRegister()"><i class="fa fa-check"> </i>&nbspSubmit</button>
	                    </div>
	                <!-- </form> -->
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal fade" id="assignModal" role="dialog">
	  <div class="modal-dialog modal-md">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title">Presales Assignment</h3>
	      </div>
	      <div class="modal-body">
	        <input type="text" name="lead_id_presales" id="lead_id_presales" value="" hidden>
          <input type="text" name="status_presales" id="status_presales" value=""  hidden>
	          @csrf
	        <div class="form-group">
	          <label for="">Choose Presales</label>
	          <select class="form-control" id="select2-presales" name="select2-presales" style="width:100%" required>
	            <option value="">-- Choose --</option>
	          </select>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
	          <button type="button" class="btn btn-primary" id="btnSubmitAssignPresales" onclick="submitAssignPresales()"><i class="fa fa-plus"> </i>&nbspSubmit</button>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="request_id" role="dialog">
	    <div class="modal-dialog modal-md">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Request ID Project</h4>
	        </div>
	        <div class="modal-body">
	          <!-- <form method="POST" action="{{url('update_result_idpro')}}"> -->
	            @csrf
	          <div class="form-group">
	            <label for="">Lead ID</label>
	            <input type="text" id="lead_req_pid" name="" class="form-control" readonly>
	          </div>

	          <div class="form-group">
	            <label for="">Project Name</label>
	            <textarea  name="opp_name_req_pid" id="opp_name_req_pid" readonly class="form-control"></textarea>
	          </div>

	          <div class="form-group">
	            <label for="">Amount PO</label>
	            <div class="input-group">
	              <div class="input-group-addon" style="background-color:#aaa;color:white">
	                <b><i>Rp</i></b>
	              </div>
	            	<input type="text" class="form-control money" placeholder="Enter Amount" name="amount_req_pid" id="amount_req_pid" pattern= "[0-9]" required>
	            </div>
	            <span class="help-block" style="display:none">Please Fill Amount!</span>
	          </div>

	          <div class="form-group">
	            <label for="">Date PO</label>
	            <input type="text" name="date_po_req_pid" id="date_po_req_pid" class="form-control date" required>
	          </div>
	   
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="button" class="btn btn-primary" id="submitRequestID"><i class="fa fa-check">&nbsp</i>Submit</button>
            </div>
	        <!-- </form> -->
	        </div>
	      </div>
	    </div>
	</div>
@endsection
@section('scriptImport')
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.1/select2.min.js"></script> -->
<!-- <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
	var accesable = @json($feature_item);
	accesable.forEach(function(item,index){
  	$("#" + item).show()
	})

	$(".date").datepicker({
  	todayHighlight: true,
  	autoclose:true
  })

 	function initMoney(){
    $('.money').mask('000.000.000.000', {reverse: true});
  }

	var table = $('#tableLead').DataTable({
		"ajax":{
        "type":"GET",
        "url":"{{url('project/getDataLead')}}",
      },
      "columns": [
        { 
        	render: function (data, type, row){
        		if (row.result_modif == 'INITIAL') {
        			return row.lead_id	
        		}else{
        			return '<a href="{{url("project/detailSales")}}/'+row.lead_id+'">'+ row.lead_id + '</a>'
        		}
        		
        	}
        },
        { "data": "brand_name","width":"100px"},
        { "data": "opp_name"},
        { 
        	render: function (data,type,row){
        		return row.created_at.substring(0,10)
        	}	
        },
        { "data": "closing_date"},
        { "data": "name"},
        { "data": "name_presales"},
        {
	        render: function ( data, type, row ) {
	          return new Intl.NumberFormat('id').format(row.amount)
	        }
	      },
        {
          render: function (data, type, row) {
            if(row.result_modif == 'INITIAL'){
             return '<label class="status-initial" style="width">INITIAL</label>'
            }else if(row.result_modif == 'OPEN'){
             return '<label class="status-open">OPEN</label>'
            }else if(row.result_modif == 'SD'){
             return '<label class="status-sd">SD</label>'
            }else if(row.result_modif == 'TP') {
             return '<label class="status-tp">TP</label>'
            }else if(row.result_modif == 'WIN') {
             return '<label class="status-win">WIN</label>'
            }else if(row.result_modif == 'LOSE') {
             return '<label class="status-lose">LOSE</label>'
            }else {
             return '<label class="status-pending">'+ row.result_modif +'</label>'

            }
          } 
        },   
        {
        	render: function (data, type, row){

        		btnEdit = '<button class="btn btn-xs btn-width-custom btn-warning btnEdit" id="btnEdit" style="display:none;width:50px"><i class="fa fa-edit"></i> Edit</button>'
        		btnDelete = '<button class="btn btn-xs btn-width-custom btn-danger btnDelete" id="btnDelete" style="display:none;width:55px" ><i class="fa fa-trash"></i> Delete</button>'
        		btnIdProject = '<button class="btn btn-xs btn-primary btnReqIdProject" id="btnReqIdProject" style="display:none;width:70px"><i class="fa fa-plus-square"></i> ID Project</button>'

   
      			if (row.result_modif == 'INITIAL') {
      				title_assign = 'Assign'
      				onclickAssign = "onclick=btnAssign('assign',"+row.lead_id+")"
      				status = 'assign'
      				btnAssign = 'btnAssign'

      				return '<button class="btn btn-xs btn-primary '+btnAssign+'" id="btnAssign" value="'+ row.lead_id+','+status+'" style="display:none">'+ title_assign +'</button>'+btnEdit + btnDelete
      				
      			}else  {
      				title_assign = 'Re-Assign'
      				onclickAssign = "onclick=btnAssign('reassign',"+row.lead_id+")"
      				status = 'reassign'
      				if (row.result_modif == 'WIN' || row.result_modif == 'LOSE' || row.result_modif == 'CANCEL') {
      					console.log(row.status == 'pending')
      					if (row.status == 'pending') {
  								return btnIdProject      								
  							}else{
  								return ''
  							}
      					btnAssign = ''          					
      				}else{
      					btnAssign = 'btnAssign'
  							return '<button class="btn btn-xs btn-primary '+btnAssign+'" id="btnAssign" value="'+ row.lead_id+','+status+'" style="display:none">'+ title_assign +'</button>'+ btnEdit     								
      				}
      			}       			
      			
      		}
        	
        },
      ],
      footerCallback: function( row, data, start, end, display ) {
      	  var api = this.api(), data;

          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          var total = api
            .data()
            .pluck('amount')
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

	          var filtered = api.column(6, ).data().sum();

	          var totalpage = api.column(6).data().sum();

	          $( api.column( 5).footer() ).addClass('text-right');
	          $( api.column( 5 ).footer() ).html("Total Amount");

	          $( api.column(7).footer() ).html(new Intl.NumberFormat('id').format(total));
	    },
      initComplete: function () {
      	accesable.forEach(function(item,index){
	    		$("." + item).show()
	  		})        			  		
      },
      "order": [],
      lengthChange:false,
      pageLength:50,
	})

	$(document).on('click','.paginate_button', function() {
	 	accesable.forEach(function(item,index){
  		$("." + item).show()
		})
	});

	table.on( 'draw', function () {
    accesable.forEach(function(item,index){
  		$("." + item).show()
		})
	});

	$.ajax({
	    url: "{{url('/project/getPresales')}}",
	    type: "GET",
	    success: function(result) {
	        $("#select2-presales").select2({
	        	data:result.data
	        })

	        $("#filter_presales").select2({
	        	placeholder:"Select Presales",
	        	multiple:true,
	        	data:result.data
	        })
	    }
	})

	$.ajax({
	    url: "{{url('/project/getCustomerByLead')}}",
	    type: "GET",
	    success: function(result) {
        $("#filter_customer").select2({
	      	placeholder: "Select Customer",
			  	multiple:true,
			  	data:result.data
			  })       
	    }
	})

	$.ajax({
		url: "{{url('/project/getSales')}}",
		type: "GET",
		success:function(result){

			$("#owner_sales").select2({
      	placeholder: "Select sales",
		  	data:result.data
		  })
		}
	})	

	$.ajax({
		url: "{{url('/project/getUserByTerritory')}}",
		type: "GET",
		data:{
			territory:"{{Auth::User()->id_territory}}"
		},
		success:function(result){

			$("#filter_sales_manager").select2({
      	placeholder: "Select sales",
		  	multiple:true,
		  	data:result.data
		  })
		}
	})	

	$.ajax({
	    url: "{{url('/project/getCustomer')}}",
	    type: "GET",
	    success: function(result) {
	        $("#contact").select2({
	        	data:result.data
	        })
	    }
	})

	function add_lead(){
		initMoney()
		$("#modal_lead").modal('show')
	}

	function submitLead(){
		if ("{{Auth::User()->id_division}}" == "PRESALES") {
			var owner_sales = $("#owner_sales").val()
		}else{
			var owner_sales = 'bukan presales'
		}
		if (owner_sales == '') {
			$("#owner_sales").closest('.form-group').addClass('has-error .select2-selection')
			$("#owner_sales").nextAll("span").eq(1).show()

		}else if ($("#contact").val() == '') {
			$("#contact").closest('.form-group').addClass('has-error .select2-selection')
			$("#contact").nextAll("span").eq(1).show()

		}else if ($("#opp_name").val() == '') {
			$("#opp_name").closest('.form-group').addClass('has-error')
			$("#opp_name").next('span').show()


		}else if ($("#amount").val() == '') {
			$("#amount").closest('.form-group').addClass('has-error')
			$("#amount").closest('div div').next('span').show();


		}else if ($("#closing_date").val() == '') {
			$("#closing_date").closest('.form-group').addClass('has-error')
			$("#closing_date").closest('div div').next('span').show();

		}else{
			Swal.fire({
			    title: 'Are you sure?',  
			    text: "Submit Lead Register",
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
		            type: "POST",
		            url: "{{url('/project/storeLead')}}",
		            data: {
		              _token: "{{ csrf_token() }}",
									owner_sales:$("#owner_sales").val(),
									contact:$("#contact").val(),
									opp_name:$("#opp_name").val(),
									closing_date:$("#closing_date").val(),
									amount:$("#amount").val(),
									note:$("#note").val(),
		            },
		            success: function(result) {
		                Swal.showLoading()
		                Swal.fire(
		                    'Successfully!',
		                   	'Lead Register Created.',
		                    'success'
		                ).then((result) => {
		                    if (result.value) {
		                    	location.reload()
		                    	$("#modal_lead").modal('hide')
		                    }
		                })
		            }
		        })		      
			  }
			})
		}
	}

	table.on('click','#btnAssign',function(){
		lead_id = this.value.split(",")[0]
		status = this.value.split(",")[1]
	
		$("#lead_id_presales").val(lead_id)
		$("#status_presales").val(status)

		if (status != "assign") {
			$.ajax({
				type:"GET",
				url:"{{url('/project/getPresalesAssign')}}",
				data:{
					lead_id:lead_id
				},
				success:function(result){
					$("#select2-presales").select2().val("")
					$("#select2-presales").val(result.data[0].id).trigger("change")
				}
			})
		}

		$("#assignModal").modal('show')		
	})

	function submitAssignPresales(){
		Swal.fire({
		    title: 'Are you sure?',
		    text: "Submit for assigned presales",
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
		        if ($("#status_presales").val() == "assign") {
		        	url = "{{url('/project/assignPresales')}}"
		        }else{
		        	url = "{{url('/project/reassignPresales')}}"
		        }

		        $.ajax({
		            type: "POST",
		            url: url,
		            data: {
		                _token: "{{ csrf_token() }}",
		                lead_id:$("#lead_id_presales").val(),
		                nik_presales:$("#select2-presales").select2("data")[0].id,
		                name_presales:$("#select2-presales").select2("data")[0].text
		            },
		            success: function(result) {
		                Swal.showLoading()
		                Swal.fire(
		                    'Successfully!',
		                    'Presales has been Assigned.',
		                    'success'
		                ).then((result) => {
		                    if (result.value) {
		                    	location.reload()
		                    	$("#assignModal").modal('hide')
		                    }
		                })
		            }
		        })
		    }
		})
	}

	table.on('click', '#btnEdit', function () {
    var tr = $(this).closest('tr');
    var value = $(tr).find('td').eq(0)[0].innerHTML;

    if (value.length == 10) {
    	value = value
    }else{
    	value = $(value).text()
    }
     
    $('#edit_lead_register').modal('show')
		$.ajax({
			type:"GET",
			url:"{{url('/project/showEditLead')}}",
			data:{
				lead_id:value,
			},
			success:function(result){
				initMoney()

				$("#lead_id_edit").val(result.data[0].lead_id)
				$("#opp_name_edit").val(result.data[0].opp_name)
				$("#closing_date_edit").datepicker({
					format: 'yyyy-mm-dd',
					// autoclose:true,
				}).datepicker('setDate', result.data[0].closing_date)
				$("#amount_edit").mask('000.000.000.000', {reverse: true})
				$("#amount_edit").val(result.data[0].amount.toString()).trigger("input")
				$("#note_edit").val(result.data[0].keterangan)
				$("#product_edit").select2().val("");
				$('#product_edit').val(result.data[0].id_product_tag).trigger('change')
				$("#technology_edit").select2().val("");
				$("#technology_edit").val(result.data[0].id_tech).trigger("change")

			}
		})
	});

	function editLeadRegister(){
		Swal.fire({
		    title: 'Are you sure?',  
		    text: "Update this Lead",
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
		            type: "POST",
		            url: "{{url('/project/update_lead_register')}}",
		            data: {
		              _token: "{{ csrf_token() }}",
		              lead_id_edit:$("#lead_id_edit").val(),
									opp_name_edit:$("#opp_name_edit").val(),
									closing_date_edit:$("#closing_date_edit").val(),
									amount_edit:$("#amount_edit").val(),
									note_edit:$("#note_edit").val(),
									product_edit:$("#product_edit").val(),
									technology_edit:$("#technology_edit").val(),
		            },
		            success: function(result) {
		                Swal.showLoading()
		                Swal.fire(
		                    'Successfully!',
		                   	'Lead Register Updated.',
		                    'success'
		                ).then((result) => {
		                    if (result.value) {
		                    	location.reload()
		                    	$("#edit_lead_register").modal('hide')
		                    }
		                })
		            }
		        })		      
		  }
		})
	}

	table.on('click','#btnDelete',function(){
		var tr = $(this).closest('tr');
    var value = $(tr).find('td').eq(0)[0].innerHTML;

    if (value.length == 10) {
    	value = value
    }else{
    	value = $(value).text()
    }

		Swal.fire({
		    title: 'Are you sure?',  
		    text: "Delete this Lead",
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
		            type: "GET",
		            url: "{{url('project/deleteLead')}}",
		            data: {
		            	lead_id:value,
		            },
		            success: function(result) {
		                Swal.showLoading()
		                Swal.fire(
		                    'Successfully!',
		                    'Lead Register Deleted.',
		                    'success'
		                ).then((result) => {
		                    if (result.value) {
		                    	location.reload()
		                    }
		                })
		            }
		        })
		    }
		})
	})

	table.on('click','#btnReqIdProject',function(){
		var tr = $(this).closest('tr')
		var value = $(tr).find('td').eq(0)[0].innerHTML

		if (value.length == 10) {
    	value = value
    }else{
    	value = $(value).text()
    }

    console.log(value)

    $.ajax({
    	type:"GET",
    	url:"{{url('/project/getPid')}}",
    	data:{
    		lead_id:value
    	},success:function(result){
    		console.log(result)
    		$("#lead_req_pid").val(result.data[0].lead_id)
    		$("#opp_name_req_pid").val(result.data[0].opp_name)
    		$("#amount_req_pid").val(result.data[0].amount_pid).mask('000.000.000.000', {reverse: true})
    		$("#date_po_req_pid").val(result.data[0].date_po)


		    $("#submitRequestID").click(function(){
		    	$("#submitRequestID").attr("onclick",submitReqPID(result.data[0].lead_id))
		    })
    	}
    })

		$("#request_id").modal("show")
	})

	function submitReqPID(value){
		Swal.fire({
		    title: 'Are you sure?',  
		    text: "Submit Request PID",
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
	            type: "POST",
	            url: "{{url('/project/updateResultRequestPid')}}",
	            data: {
	              _token: "{{ csrf_token() }}",
	              lead_id:value,
	            },
	            success: function(result) {
	                Swal.showLoading()
	                Swal.fire(
	                    'Successfully!',
	                   	'Request PID Successfully.',
	                    'success'
	                ).then((result) => {
	                    if (result.value) {
	                    	location.reload()
	                    	$("#request_id").modal('hide')
	                    }
	                })
	            }
	        })		      
		  }
		})
	}

	$.ajax({
    url:"{{url('/project/getProductTag')}}",
    type:"GET",
    success:function(result){
      var selectProduct = $("#product_edit").select2().val("");
      var arr = result.results;
      var selectOption = [];
      var otherOption;
      $.each(arr,function(key,value){
        if (value.text != "Others") {
          selectOption.push(value)
        }else{
          otherOption = value
        }
      })
      selectOption.push(otherOption)
      $("#product_edit").select2({
        multiple:true,
        data:selectOption
      })
    }
  })

  $.ajax({
    url:"{{url('/project/getTechTag')}}",
    type:"GET",
    success:function(result){
      var selectTechnology = $("#technology_edit").select2().val("");
      var arr = result.results;
      var selectOption = [];
      var otherOption;
      $.each(arr,function(key,value){
        if (value.text != "Others") {
          selectOption.push(value)
        }else{
          otherOption = value
        }
      })
      selectOption.push(otherOption)
      $("#technology_edit").select2({
        multiple:true,
        data:selectOption
      })
    }
  })

  $.ajax({
		url: "{{url('/project/getSalesByTerritory')}}",
		type: "GET",
		success:function(result){
			console.log(result)
			$("#filter_sales").select2({
      	placeholder: "Select sales",
		  	multiple:true,
		  	data:result.results
		  })
		}
	})

  $("#year_dif").select2({
  	multiple:true
  })

	var countLead = []
	var sumAmount = []
	
	$(document).ready(function(){  	
		var year = $('#year_dif').val();
    var i = 0
		var colors = []
		var prepend = ""
  	var ArrColors = [{
	        name: 'Lead Register', color: 'bg-purple', icon: 'fa fa-list',index: 0, url: "view_lead"
	    },
	    {
	        name: 'Open', color: 'bg-orange', icon: 'fa fa-briefcase',index: 1, url: "view_open"
	    },
	    {
	        name: 'Solution Design', color: 'bg-aqua', icon: 'fa fa-file-text-o',index: 2, url: "view_open"
	    },
	    {
	        name: 'Tender Process', color: 'bg-yellow', icon: 'fa fa-file-text-o',index: 3, url: "view_open"
	    },
	    {
	        name: 'Win', color: 'bg-green', icon: 'fa fa-calendar-check-o',index: 4, url: "view_win"
	    },
	    {
	        name: 'Lose', color: 'bg-red', icon: "fa fa-calendar-times-o",index: 5, url: "view_lose"
	    }
		]

		colors.push(ArrColors)

		$.each(colors[0], function(key, value){
			prepend = prepend + '<div class="col-lg-2 col-xs-12">'
			    prepend = prepend + '<div class="small-box ' + value.color + '">'
			        prepend = prepend + '<div class="inner">'
			            prepend = prepend + '<div class="txt_serif stats_item_number">'
			                prepend = prepend + '<center>'
			                prepend = prepend + '<h4><b>'+ value.name +'</b></h4>'
			                    prepend = prepend + '<h3 class="counter" id="count_lead_'+value.index+'"></h3>'
			                prepend = prepend + '</center>'
			            prepend = prepend + '</div>'
			            prepend = prepend + '<center>'
			                prepend = prepend + '<h4>Rp.<span id="sum_amount_'+value.index+'"></span></h4>'
			            prepend = prepend + '</center>'
			        prepend = prepend + '</div>'
			        prepend = prepend + '<div class="icon">'
			            prepend = prepend + '<i class="'+ value.icon +'"></i>'
			            prepend = prepend + '</div>'
			        prepend = prepend + '<div class="small-box-footer"></div>'
			        prepend = prepend + '</div>'
			    prepend = prepend + '</div>'
			prepend = prepend + '</div>'	

			id = "count_lead_"+value.index
			sumAm = "sum_amount_"+value.index
			countLead.push(id)
			sumAmount.push(sumAm)
			initMoney()

		})

		$("#BoxId").prepend(prepend)

    	dashboardCount(year)

		if (accesable.includes('searchTags')) {
			$.ajax({
			    url: "{{url('/project/getProductTechTag')}}",
			    type: "GET",
			    success: function(result) {
			        $("#searchTags").select2().val("");
			        var arr = result;
			        var selectOption = [];
			        var otherOption;
			        $.each(arr, function(key, value) {
			            if (value.text != "Others") {
			                selectOption.push(value)
			            } else {
			                otherOption = value
			            }
			        })

			        selectOption.push(otherOption)
			        var TagProduct = $("#searchTags").select2({
			            placeholder: " Select Tags",
			            allowClear: true,
			            multiple: true,
			            data: selectOption,
			            templateSelection: function(selection, container) {
			                var selectedOption = selection.id.slice(0, 1);
			                if (selectedOption == 'p') {
			                    $(container).css("background-color", "#32a852");
			                    $(container).css("border-color", "#32a852");
			                    return selection.text;
			                } else {
			                    return $.parseHTML('<span>' + selection.text + '</span>');
			                }
			            }
			        })
			    }

			})
		}

		if (!accesable.includes('columnPresales')) {
			var column1 = table.column(6);
      		column1.visible(!column1.visible() );
		}

		var prependFilterCom = ""
		prependFilterCom = prependFilterCom + '<label>Company</label>'
		$.ajax({
			type:"GET",
			url:'{{url("/project/getCompany")}}',
			success:function(result){
				$.each(result,function(key,value){
					prependFilterCom = prependFilterCom + '<div>'
				  	prependFilterCom = prependFilterCom + '<input type="checkbox" class="cb-company" name="cb-filter" value="'+value.id_company+'"> '
				    prependFilterCom = prependFilterCom + value.company
				  prependFilterCom = prependFilterCom + '</div>'
				})

				$("#filter-com").append(prependFilterCom)
				$(".cb-company").click(function(){
					
					searchCustom()

				})
			}
		})

		var prependFilterTer = ""
		prependFilterTer = prependFilterTer + '<label>Territory</label>'
		$.ajax({
			type:"GET",
			url:'{{url("/project/getTerritory")}}',
			success:function(result){
				$.each(result,function(key,value){
					prependFilterTer = prependFilterTer + '<div>'
				  	prependFilterTer = prependFilterTer + '<input type="checkbox" class="cb-territory" name="cb-filter" value="'+value.id_territory+'"> '
				    prependFilterTer = prependFilterTer + value.id_territory
				  prependFilterTer = prependFilterTer + '</div>'
				})

				$("#filter-territory").append(prependFilterTer)
				$(".cb-territory").click(function(){
					var tempTer = []
					$.each($(".cb-territory:checked"),function(key,value){
						tempTer = tempTer + '&territory[]=' + value.value
					})

					if (tempTer.length !== 0) {
						var salesFilterTer = "{{url('/project/getSalesByTerritory')}}?"+tempTer
							
					}else{
						var salesFilterTer = "{{url('/project/getSalesByTerritory')}}"
					}

					// $("filter_sales").select2("destroy").select2()

					$.ajax({
						url: salesFilterTer,
						type: "GET",
						beforeSend:function(){
							$("#filter_sales").empty('')
						},
						success:function(result){
							console.log(result)
							$("#filter_sales").select2({
				      	placeholder: "Select sales",
						  	multiple:true,
						  	data:result.results
						  })
						}
					})					
					searchCustom()
				})


			}
		})


		var prependFilterStatus = ""
		prependFilterStatus = prependFilterStatus + '<label>Status Lead</label>'
		$.ajax({
			type:"GET",
			url:'{{url("/project/getResult")}}',
			success:function(result){
				$.each(result,function(key,value){
					prependFilterStatus = prependFilterStatus + '<div>'
				  	prependFilterStatus = prependFilterStatus + '<input type="checkbox" class="cb-result" name="cb-filter" value="'+value.result_value+'"> '
				    prependFilterStatus = prependFilterStatus + value.result_modif
				  prependFilterStatus = prependFilterStatus + '</div>'
				})

				$("#filter-result").append(prependFilterStatus)
				$(".cb-result").click(function(){
					searchCustom()

				})
			}
		})
	  
  })	
	
	var timer
	function searchCustom(id_table,id_seach_bar){
		var temp = [], tempCom = [], tempSales = [], tempPresales = [], tempTer = [], tempResult = [], tempCustomer = [], tempTech = [], tempProduct = [], tempSearch = ''

		$.each($(".cb-territory:checked"),function(key,value){
			tempTer = tempTer + '&territory[]=' + value.value
		})

	  $.each($("#filter_sales").val(),function(key,value){
			tempSales = tempSales + '&sales_name[]='+ value
		})

		$.each($("#filter_sales_manager").val(),function(key,value){
			tempSales = tempSales + '&sales_name[]='+ value
		})

		$.each($("#year_dif").val(),function(key,value){
			temp = temp + '&year[]='+ value
		})

		if ($("#year_dif").val() == '') {
			temp = temp + '&year[]='+ new Date().getFullYear()
		}

		$.each($("#filter_presales").val(),function(key,value){
			tempPresales = tempPresales + '&presales_name[]='+ value
		})

	  $.each($('#searchTags').val(),function(key, value) {
	    if (value.substr(0,1) == 'p') {
				tempProduct = tempProduct + '&product_tag[]='+ value.substr(1,1)
	    }
	    if (value.substr(0,1) == 't') {
				tempTech = tempTech + '&tech_tag[]='+ value.substr(1,1)
	    }
	  });

	  $.each($('#filter_customer').val(),function(key,value){
	  	tempCustomer = tempCustomer + '&customer[]=' + value
	  })
		
		$.each($(".cb-company:checked"),function(key,value){
			tempCom = tempCom + '&company[]=' + value.value
		})

		var checklist = false
		$.each($(".cb-result:checked"),function(key,value){
			tempResult = tempResult + '&result[]=' + value.value
			checklist = true
		})

		console.log(checklist)


		tempSearch = tempSearch + '&search=' + $('#searchLead').val()

		if (id_table != undefined) {
			clearTimeout(timer);
		  timer = setTimeout(function() {
		  	$("#" + id_table).DataTable().ajax.url("{{url('project/getSearchLead')}}?search=" + $('#' + id_seach_bar).val() +  temp + tempSales + tempPresales + tempTer + tempCom + tempResult + tempProduct + tempTech + tempCustomer).load();

		  	dashboardCount(temp)
		  }, 800);
		}else{
			$("#tableLead").DataTable().ajax.url("{{url('project/getSearchLead')}}?=" + tempSearch +  temp + tempSales + tempPresales + tempTer + tempCom + tempResult + tempProduct + tempTech + tempCustomer).load();
			if (checklist == false) {
				dashboardCountFilter(temp,tempSearch,tempSales,tempPresales,tempTer,tempCom,tempProduct,tempTech,tempCustomer)
			}
		}


		
	}

	//dashboard count
	function initMoneyHeader(){
		$("#sum_amount_0").mask('000.000.000.000', {reverse: true})
		$("#sum_amount_1").mask('000.000.000.000', {reverse: true})
		$("#sum_amount_2").mask('000.000.000.000', {reverse: true})
		$("#sum_amount_3").mask('000.000.000.000', {reverse: true})
		$("#sum_amount_4").mask('000.000.000.000', {reverse: true})
		$("#sum_amount_5").mask('000.000.000.000', {reverse: true})
	}

	function initRemoveMask(){
		$("#sum_amount_0").unmask('000.000.000.000', {reverse: true})
		$("#sum_amount_1").unmask('000.000.000.000', {reverse: true})
		$("#sum_amount_2").unmask('000.000.000.000', {reverse: true})
		$("#sum_amount_3").unmask('000.000.000.000', {reverse: true})
		$("#sum_amount_4").unmask('000.000.000.000', {reverse: true})
		$("#sum_amount_5").unmask('000.000.000.000', {reverse: true})
	}

	function dashboardCount(year){	
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type:"GET",
				url:"{{url('/project/getCountLead')}}",
				data:{
					year:year,
				},
				success:function(result){
					$.each(result,function(){						
						$("#"+countLead[0]).text(result.lead)
						$("#"+countLead[1]).text(result.open)
						$("#"+countLead[2]).text(result.sd)
						$("#"+countLead[3]).text(result.tp)
						$("#"+countLead[4]).text(result.win)
						$("#"+countLead[5]).text(result.lose)
						$("#"+sumAmount[0]).text(result.amount_lead)
						$("#"+sumAmount[1]).text(result.amount_open)
						$("#"+sumAmount[2]).text(result.amount_sd)
						$("#"+sumAmount[3]).text(result.amount_tp)
						$("#"+sumAmount[4]).text(result.amount_win)
						$("#"+sumAmount[5]).text(result.amount_lose)

					})

					initMoneyHeader()

					$('.counter').each(function () {
					    var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
					    $(this).prop('Counter', 0).animate({
					      Counter: $(this).text()
					    }, {
					      duration: 5000,
					      step: function (func) {
					         $(this).text(parseFloat(func).toFixed(size));
					      }
					    });
					});
				}
			})
		})
	}

	function dashboardCountFilter(temp,tempSearch,tempSales,tempPresales,tempTer,tempCom,tempProduct,tempTech,tempCustomer){	
		initRemoveMask()
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type:"GET",
				url:"{{url('/project/filterCountLead')}}?=" + tempSearch +  temp + tempSales + tempPresales + tempTer + tempCom  + tempProduct + tempTech + tempCustomer,
				success:function(result){
					$.each(result,function(){						
						$("#"+countLead[0]).text(result.lead)
						$("#"+countLead[1]).text(result.open)
						$("#"+countLead[2]).text(result.sd)
						$("#"+countLead[3]).text(result.tp)
						$("#"+countLead[4]).text(result.win)
						$("#"+countLead[5]).text(result.lose)
						$("#"+sumAmount[0]).text(result.amount_lead)
						$("#"+sumAmount[1]).text(result.amount_open)
						$("#"+sumAmount[2]).text(result.amount_sd)
						$("#"+sumAmount[3]).text(result.amount_tp)
						$("#"+sumAmount[4]).text(result.amount_win)
						$("#"+sumAmount[5]).text(result.amount_lose)

					})

					initMoneyHeader()

					$('.counter').each(function () {
					    var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
					    $(this).prop('Counter', 0).animate({
					      Counter: $(this).text()
					    }, {
					      duration: 5000,
					      step: function (func) {
					         $(this).text(parseFloat(func).toFixed(size));
					      }
					    });
					});
				}
			})
		})
	}

	$(".select2").select2()

	if (localStorage.getItem('status') == "unread") {
    table.search(localStorage.getItem('lead_id')).draw()
  }


  if (localStorage.getItem('status') == 'read') {
    table.search("").draw()
  }
</script>
@endsection