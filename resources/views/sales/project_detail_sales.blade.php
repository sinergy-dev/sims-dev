@extends('template.main')
@section('tittle')
Detail Lead Register
@endsection
@section('head_css')
<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

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

	.modal-dialog-centered{
		min-height: calc(100% - (1.75rem * 2));
	}

	/*.modal-changeReq{
		display: flex;
		position: fixed;
		z-index: 1060;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		padding: .625em;
		overflow-x: hidden;
		transition: background-color .1s;
	}*/

	.active-tab{
	  display: block;
	}

	.non-active-tab{
	  display: none;
	}

	.circle-container .dot{
	  height: 25px;
	  width: 25px;
	  background-color: #939a9b;
	  border-radius: 50%;
	  display: inline-block;
	}

	.circle-container {
	    position: relative;
	    width: 17em;
	    height: 17em;
	    padding: 2.8em;
	    border: solid 3px;
	    border-radius: 50%;
	    margin: 0.90em auto 0;
	    border-color:#6dcae8;
	}

	.circle-container a {
	    display: block;
	    position: absolute;
	    top: 50%; left: 50%;
	    width: 4em; height: 4em;
	    margin: -2em;
	}

	.circle-container span {display: block; width: 100%;}

	.circle-container .dot:after{
	  transform: translate(8.1em, -2em );
	  color: #1b3868; 
	}

	.circle-container .dot.active{
	  background-color: #939a9b;
	}

	.circle-container .dot.active1{
	  background-color: #f2562b;
	}

	.circle-container .dot.active2{
	  background-color: #04dda3;
	}

	.circle-container .dot.active3{
	  background-color: #f7e127;
	}

	.circle-container .dot.active4{
	  background-color: #246d18;
	}

	.circle-container .dot.active5{
	  background-color: #0e0f0f;
	}

	.circle-container .dot.active6{
	  background-color: #FF0000;
	}

	.deg45 { transform: rotate(-21deg) translate(9.9em) rotate(-60deg); }
	.deg135 { transform: rotate(185deg) translate(8.1em) rotate(-135deg); }
	.deg180 { transform: rotate(399deg) translate(7.1em) rotate(-185deg);; }
	.deg225 { transform: rotate(126deg) translate(7.0em) rotate(-185deg); }
	.deg315 { transform: rotate(280deg) translate(7.3em) rotate(-185deg); }

	.deginitial
	{
	  transform: translate(0.5em, -7.3em );
	  color: #1b3868;
	}

	.circle-container .dot:after{
	  transform: translate(8.1em, -2em );
	  color: #1b3868; 
	}

	.degopen
	{
	transform: translate(9.8em, 2.8em);
	color: #1b3868; 
	}

	.degTP
	{
	transform:  translate(-4em, 6.4em);
	color:  #1b3868;  
	}

	.degSD
	{
	transform: translate(8.1em,8.0em);
	color:  #1b3868;  
	}

	.degwin
	{
	transform: translate(-2.5em,-0.5em);
	color:  #1b3868; 
	}

	.circle-container .box-shadow{
	  box-shadow: 0 0 0 5px #dfe6f2;
	  height: 25px;
	  width: 25px;
	  background-color: #939a9b;
	  border-radius: 50%;
	  display: inline-block;
	}

	.spin-shape{
	  -webkit-animation:spin 4s linear infinite;
	  -moz-animation:spin 4s linear infinite;
	  animation:spin 4s linear infinite
	}

	@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
	@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
	@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

	.dot {
	  height: 50px;
	  width: 50px;
	  border: solid black;
	  border-radius: 50%;
	  display: inline-block;
	  margin: auto;
	}
	.dot:nth-child(1){
		margin-bottom: 10px;
		/*background-color: #605ca8;*/
		vertical-align: middle;
		padding-top: 45px;
		text-align: center;
		color: white;
	}

	.dot:nth-child(3){
		margin-bottom: 10px;
		/*background-color: #FF851B;*/
		vertical-align: middle;
		padding-top: 45px;
		text-align: center;
		color: white;
	}

	.dot:nth-child(5){
		margin-bottom: 10px;
		/*background-color: #00c0ef;*/
		vertical-align: middle;
		padding-top: 35px;
		text-align: center;
		color: white;
	}

	.dot:nth-child(7){
		margin-bottom: 10px;
		/*background-color: #f39c12;*/
		vertical-align: middle;
		padding-top: 35px;
		text-align: center;
		color: white;
	}

	.dot:last-child{
		margin-bottom: 10px;
		/*background-color: #dedbd3;*/
		vertical-align: middle;
		padding-top: 45px;
		text-align: center;
		color: white;
	/*	margin-bottom: 10px;
		background-color: #dedbd3;
		vertical-align: middle;
		padding-top: 45px;
		text-align: center;
		color: #b5b3ac;
		border-color: #dedbd3;*/
	}


	
</style>
@endsection
@section('content')
<section class="content-header">
	<a href="{{url('/project/index')}}"><button button class="btn btn-s btn-danger"><i class="fa fa-arrow-left"></i>&nbsp Back</button></a>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active"><a href="/project">Lead Register</a></li>
		<li class="active">Detail</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-6">
	      	<div class="box box-default" id="box-status" >
	      		<div class="box-header">
	      			<h3 class="box-title">Status</h3>
	      		</div>
	      		<div class="box-body">
	      			<div style="text-align: center;margin: auto;padding-top: 15%;padding-bottom: 15%;" id="stageID">
							  <span class="dot">INITIAL</span>
							  <span class="arrow fa-2x fa fa-arrow-right"></span>
							  <span class="dot"></span>

							  <span class="arrow fa-2x fa fa-arrow-right"></span>
							  <span class="dot"></span>

							  <span class="arrow fa-2x fa fa-arrow-right"></span>
							  <span class="dot"></span>

							  <span class="arrow fa-2x fa fa-arrow-right"></span>
							  <span class="dot"></span>
							</div>
	      		</div>

	      		
		          
		       <!--  <div class="box-body" style="padding:32px">
		          	<div class='circle-container'>
		              <a>
		                <span class="deg315 dot" id="init"></span>
		                <span class="deginitial" id="s_init"><b style="opacity: 0.4">INITIAL</b></span> 
		              </a>
		              <a href='#' class='deg45'><span><span class="dot" id="open"></span></span></a>
		              <span class="degopen" id="s_open"><b style="opacity: 0.4">OPEN</b></span>
		              <a href='#' class='deg180'><span><span class="dot" id="sd"></span></span></a>
		              <span class="degSD" id="s_sd"><b style="opacity: 0.4">SOLUTION DESIGN</b></span>
		              <a href='#' class='deg225'><span><span class="dot" id="tp"></span></span></a>
		              <span class="degTP" id="s_tp"><b style="opacity: 0.4">TENDER PROCESS</b></span>
		              <a href='#' class='deg135'><span><span class="dot" id="win_lose"></span></span></a>
		              <span class="degwin" id="s_winlose"><b style="opacity: 0.4">WIN/LOSE</b></span>
		              <div class="step-content">
		              </div>
				        </div>
			    	</div> -->
	        </div>
	    </div>
		
		<div class="col-md-6">
  		<div class="box box-default" id="box-detail">
    		<div class="box-header">
    			<h3 class="box-title" id="box-title-detail">
    			</h3>
    		</div>
    		<div class="box-body">
    		  <table class="table table-bordered">
    		  	<tbody id="tbody-detail">
    		  		
    		  	</tbody>
    		  </table>
        </div>
  		</div>
  	</div>
	</div>

    <div class="row">
    	<div class="col-md-6">
    		<div class="box box-solid box-info" id="box-SD">
          <div class="box-header with-border">
            <h3 class="box-title" style="color: white;">Solution Design</h3>
            <button class="btn btn-primary btn-xs pull-right" id="btnAddContPre" style="display:none;" data-target="#contributeModal" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
          </div>
          <div class="box-body">
          	<fieldset id="formSD" disabled>
          		<fieldset id="endSD">
          			<input type="" name="lead_id" id="lead_id" value="" hidden>
	              <div class="form-group">
	                <label for="assesment">Assessment</label>
	                <textarea class="form-control" type="text" placeholder="Enter assesment" onkeyup="textAreaAdjust(this)" name="assesment" id="assesment" style="overflow: hidden;resize: none;"></textarea>
	                <span id="assessment_last_update"><small>Last Update  : yy/mm/dd HH:ii::ss</small></span>
	              </div>
	              <div class="form-group">
	               	<label for="proof of value">Proposed Design</label>
	               	 <textarea class="form-control" type="email" style="overflow: hidden;resize: none;" onkeyup="textAreaAdjust(this)" placeholder="Enter Propossed Design" name="propossed_design" id="propossed_design"></textarea>
	                <span id="pDesign_last_update"><small>Last Update  : yy/mm/dd HH:ii::ss</small></span>
	          		</div>
	          		<div class="form-group">
	                <label for="propossed_design">Proof Of Value</label>
	                <textarea class="form-control float-left" type="text" aria-describedby="emailHelp" style="overflow: hidden;resize: none;" onkeyup="textAreaAdjust(this)" placeholder="Enter Proof Of Value" name="pov"  id="pov" ></textarea>
	                	<span id="pov_last_update"><small>Last Update  : yy/mm/dd HH:ii::ss</small></span>
	          		</div>
	              <div class="form-group">
	                <label for="project_budget"> Project Budget </label>
	                <div class="input-group">
			              <div class="input-group-addon" style="background-color:#aaa;color:white">
			                <b><i>Rp</i></b>
			              </div>
			              <input type="text" class="form-control money" placeholder="Enter Amount" name="project_budget" id="project_budget" pattern= "[0-9]">
			            </div>
	          			<span class="help-block" style="display:none">Project Budget Melebihi Amount!</span>
	              </div>
	              <div class="row">
	              	<div class="form-group col-md-6">
	                		<label for="priority">Priority</label>                  
		                <select class="form-control float-left" id="priority"  name="priority">
		                  	<option value="">Choose Priority</option>
		                    <option value="Contribute" selected>Contribute</option>
		                    <option value="Fight" >Fight</option>
		                    <option value="Foot Print" >Foot Print</option>
		                    <option value="Guided" >Guided</option>
		                </select>
	              	</div>

	                <div class="form-group col-md-6">
	                  <label for="proyek_size" class="margin-top-form">Project size</label>
	                    <select class="form-control float-left margin-bottom" id="proyek_size"  name="proyek_size">
		                    <option value="">-- Choose Project Size --</option>
		                    <option value="Small" selected>Small</option>
		                    <option value="Medium" >Medium</option>
		                    <option value="Advance" >Advance</option>
	                    </select>
	                </div>
	              </div> 
	              <div class="row">
	              	<div class="col-lg-12 col-xs-12">
	              		<label>Brand Tagging <span style="color: red;">(required)</span></label>
	              		<div class="table-responsive">
	              			<table class="table" id="table-tagging">
		                    <thead>
		                      <tr>
		                      	<th hidden></th>
		                        <th title="Product Tagging is Required!">Brand Tag</th>
		                        <th>Technology Tag</th>
		                        <th>Price</th>
		                        <td class="text-center">
		                          <button class="btn btn-xs btn-primary" id="btn-addTagging" type="button" style="border-radius:50%;width: 25px;height: 25px;">
								              	<i class="fa fa-plus"></i>
								              </button> 
		                        </td>
		                      </tr>
			                  </thead>
			                  <tbody id="tbtagging">
			                  </tbody>
			                </table>
	              		</div>

	              		<label>SBE Tagging <span style="color: red;">(required)</span></label>
	              		<div class="table-responsive">
	              			<table class="table" id="table-tagging-SBE">
		                    <thead>
		                      <tr>
		                      	<th hidden></th>
		                        <th title="Product Tagging is Required!">SBE Tag</th>
		                        <th>Price</th>
		                        <td class="text-center">
		                          <button class="btn btn-xs btn-primary" id="btn-addSBE" onclick="addSBE()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
								              	<i class="fa fa-plus"></i>
								              </button> 
		                        </td>
		                      </tr>
			                  </thead>
			                  <tbody id="tbSBE">
			                  </tbody>
			                </table>
	              		</div>              		
	              	</div>
	              </div>  
          		</fieldset>          		            
              <div class="form-group">
              	<button class="btn btn-md btn-sd btn-primary" id="btnSubmitSD" style="float: left;" type="button">Submit</button>
              	<button class="btn btn-md btn-sd btn-success" id="btnRaiseTP" onclick="btnRaiseTP()" style="float: right;" type="button" disabled onclick="" data-toggle="modal">Raise To Tender</button>
              	<!-- <input style="float: right;margin-right: 5px;margin-top: 10px;" type="checkbox" name="" id="cbSubmitSD"> -->
              </div>
          	</fieldset>
          </div>
      	</div>
    	</div>

    	<div class="col-md-6">
    		<div class="box box-solid box-warning" id="box-TP">
          <div class="box-header with-border">
            <h3 class="box-title" style="color: white;">Tender Process</h3>
          </div>
          <div class="box-body">
          	<fieldset id="formTP" disabled>
          		<input type="" name="lead_id" id="lead_id" value="" hidden>
              <div class="form-group margin-left-right">
                <label for="assesment">No Doc. Lelang</label>
                <input class="form-control" type="text" aria-describedby="emailHelp" placeholder="Enter No Doc. Lelang" name="lelang" id="lelang" onkeypress="" value="" />
              </div>

              <div class="form-group">
                <label for="project_name">Project Name</label>
                <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Project Name" name="project_name" id="project_name" value=""/>
              </div>

              <div class="form-group">
                <label for="submitted price" class="margin-top-form">Submitted Price</label>
                <div class="input-group">
			              <div class="input-group-addon" style="background-color:#aaa;color:white">
			                <b><i>Rp</i></b>
			              </div>
			              <input type="text" class="form-control money" placeholder="Enter Amount" name="submit_price" id="submit_price" pattern= "[0-9]">
			          </div>
              </div>

              <div class="form-group">
                <label for="proyek_class">Project Class</label>
                  <select class="form-control" id="project_class" name="project_class" onchange="selectProjectClass(this.value)">
                    <option value="" selected>-- Choose Project Class --</option>
                    <option value="multiyears">Multiyears</option>
                    <option value="blanket">Blanket</option>
                    <option value="normal">Normal</option>
                  </select>
              </div>
            	<div class="form-group" id="tahun_jumlah" style="display: none">
              	<label class="margin-top-form">Jumlah Tahun</label>
                  <select class="form-control jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                    <option value="">-- Choose Year --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
            	</div>
          		<div class="form-group" id="total_price_deal" style="display: none;">
                	<label class="margin-top-form">Deal Price Total</label>
                	<div class="input-group">
			              <div class="input-group-addon" style="background-color:#aaa;color:white">
			                <b><i>Rp</i></b>
			              </div>
			              <input type="text" class="form-control money" placeholder="Enter Amount" name="deal_price_total" id="deal_price_total" pattern= "[0-9]">
			          	</div>
            		<input type="text" name="amount_cek_tp" value="" hidden>
            	</div>
            	
            	<div class="row">
            		<div class="form-group col-md-6">
                  <label for="win probability">Win Probability</label>
                  <select class="form-control" id="win_prob"  name="win_prob" >
                    <option value="">-- Choose Win Probability --</option>
                    <option value="HIGH" selected>HIGH</option>
                    <option value="MEDIUM" >MEDIUM</option>
                    <option value="LOW" >LOW</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="date">Submit Date</label>
                  <div class="input-group date">
                    <div class="input-group-addon" style="background-color:#aaa;color:white">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control" type="text" aria-describedby="emailHelp" placeholder="Enter Submit Date" name="submit_date"  id="submit_date" value=""/>
                  </div>
                </div>
            	</div> 

              <div class="form-group">
                <label>Deal Price</label>
                <div class="input-group">
			              <div class="input-group-addon" style="background-color:#aaa;color:white">
			                <b><i>Rp</i></b>
			              </div>
			              <input type="text" class="form-control" placeholder="Enter Amount" name="deal_price" id="deal_price" pattern= "[0-9]">
			          </div>
          			<span class="help-block" style="display:none">Please Fill and Submit Deal Price Before Result!</span>
              </div>

              <div class="form-group">
                <label for="quote number">Quote Number</label>
                <input class="form-control" type="text" placeholder="Enter Quote Number" name="quote_number" id="quote_number" value=""/>
              </div>

              <div class="form-group">
              	<button type="submit" class="btn btn-md btn-primary" id="btnSubmitTP" style="float: left;">Submit</button>
             	 	<button type="button" class="btn btn-md btn-success" id="btnResult" style="float:right;" onclick="" >Result</button>
              </div>
          	</fieldset>
          </div>
      	</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-12" id="formPurchaseOrder" style="display:none;">
	          <tooltip title="Please Submit TP For Fill this Form!" style="font-size: 14px" placement="top">
	          </tooltip>
	          <fieldset>
			      <div class="box" id="purchase-order">
				      <div class="box-header with-border">
				          <h3 class="box-title">Purchase Order Customer</h3>
				            <div class="box-tools pull-right">
				              <button type="button" class="btn btn-primary" style="width: 150px" data-target="#modal_add_po" data-toggle="modal"><i class="fa fa-plus"></i> Purchase Order</button>
				              <button type="button" class="btn btn-primary" style="width: 150px" disabled><i class="fa fa-plus"></i> Purchase Order</button>
				            </div>
				      </div>			       
				      <div class="box-body">
				      	  <div class="table-responsive">
					          <table class="table table-bordered display nowrap" id="data_po" width="100%" cellspacing="0">
					            <thead>
					              <tr>
					                <th width="5%"><center>No</center></th>
					                <th><center>Date</center></th>
					                <th><center>No. Purchase Order</center></th>
					                <th><center>Nominal IDR</center></th>
					                <th><center>Keterangan</center></th>
					                <th><center>Action</center></th>
					              </tr>
					            </thead>
					            <tbody id="products-list" name="products-list" class="purchase">
					              <tr>
					                <td></td>
					                <td><center></center></td>
					                <td><center></center></td>
					                <td class="money"><center></center></td>
					                <td><center></center></td>
					                <td></td>
					              </tr>
					            </tbody>
					            <tfoot>
					              <td colspan="3"><center>Total Nominal</center></td>
					              <th></th>
					              <td colspan="2"></td>
					            </tfoot>
					          </table>
				          </div>  
				      </div>
			      </div>
	          </fieldset>
	    </div>
    </div>

    <div class="row">
    	<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Change Log</h3>
              <div class="pull-right">
                <button type="button" class="btn btn-primary btn-sm" id="addChangeLog" style="display:none;" data-target="#modal_changelog_progress" data-toggle="modal"><i class="fa fa-plus"></i> Progress</button>
              </div>
            </div>
            <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered display" id="datastable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th><center>No</center></th>
                    <th><center>Tanggal</center></th>
                    <th><center>Status</center></th>
                    <th><center>Disubmit Oleh</center></th>
                  </tr>
                </thead>
                <tbody id="tbody-changeLog">
                	
                </tbody>
              </table>
            </div>  
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="formResult" tabindex="-1" role="dialog" aria-hidden="true">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h4 class="modal-title">Choose Result</h4>
		            </div>
		            <div class="modal-body">
		                <div class="alert alert-danger" id="alert_result" role="alert" style="display: none">
		                    <ul id="ul-warning-result">

		                    </ul>
		                </div>
		                    @csrf
		                    <div class="tab active-tab">
		                        <div class="form-group">
		                            <input type="" name="submit_price_result" id="submit_price_result" value="" hidden>
		                            <input type="" name="deal_price_result" id="deal_price_result" value="" hidden>
		                            <input type="" name="lead_id_result" id="lead_id_result" value="" hidden>
		                            <h5 id="opp_name_result"></h5>
		                            <div>
		                                <select class="form-control" id="result" name="result" required>
		                                    <option value="">-- Choose Result --</option>
		                                    <option value="WIN">WIN</option>
		                                    <option value="LOSE">LOSE</option>
		                                    <option value="HOLD">HOLD</option>
		                                    <option value="CANCEL">CANCEL</option>
		                                    <option value="SPECIAL">SPECIAL</option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group" id="result-win" style="display: none;">
		                        		<div class="form-group">
		                        			<label>Project Type</label>
			                            <select class="form-control" id="project_type" name="project_type">
			                                <option value="">-- Choose Result --</option>
			                                <option value="Supply Only">Supply Only</option>
			                                <option value="Implementation">Implementation</option>
			                                <option value="Maintenance">Maintenance</option>
			                                <option value="Managed-Services">Managed-Services</option>
			                                <option value="Services">Services</option>
			                            </select>
		                        		</div>
		                            
		                            <div class="form-group">
		                            	<label >No. Quote</label>
			                            <select class="form-control select2" id="quote_number_final" name="quote_number_final" style="width: 100%; ">
			                                <option value="">Choose Quote</option>
			                            </select>
		                            </div>

		                            <div class="form-group">
		                            	<label>Date PO</label>
		                            	<input type="text" name="date_po" id="date_po_result" class="form-control" autocomplete="off">
		                            </div> 

		                            <div class="form-group">
		                            	<label>No. PO</label>
		                            	<input type="text" name="no_po" id="no_po_result" class="form-control" disabled="">
		                            </div>		                            

		                            <div class="form-group">
		                            	<label>Amount PO <sup><b>(Grand Total)</b></sup></label>
		                            	<input type="text" name="amount_pid" id="amount_pid_result" class="form-control money" disabled="" autocomplete="off">
		                            </div>

		                            <div class="form-group">
		                            	<label class="checkbox" style="padding-left: 25px; padding-top: 10px;" id="checkbox_result">
		                                <input type="checkbox" name="request_id" id="request_id">
		                                <span>Request ID Project <sup>(Optional)</sup></span>
		                            	</label>
		                            </div>	
		                        </div>

		                        <div class="form-group" id="result-lose" style="display: none;">
		                            <label><b>Description</b></label>
		                            <textarea type="text" class="form-control" placeholder="Enter Description" name="Description" id="keterangan" required> </textarea>
		                        </div>
		                    </div>
		                    <div class="tab non-active-tab">
		                        <div class="form-group">
		                            <table class="table" id="table-product">
		                                <thead>
		                                    <tr>
		                                        <th>Product Tag</th>
		                                        <th>Technology Tag</th>
		                                        <th>Price</th>
		                                        <th class="text-center">
		                                            <button class="btn btn-xs btn-primary" type="button" style="border-radius:50%;width: 25px;height: 25px;" id="addProductTech">
		                                                <i class="fa fa-plus"></i>
		                                            </button>
		                                        </th>
		                                    </tr>
		                                </thead>
		                                <tbody id="tbtagprice">
		                                </tbody>
		                            </table>
		                            <table class="table" id="table-sbe">
		                                <thead>
		                                    <tr>
		                                        <th>Sbe Tag</th>
		                                        <th>Price</th>
		                                        <th class="text-center">
		                                            <button class="btn btn-xs btn-primary" type="button" style="border-radius:50%;width: 25px;height: 25px;" id="addSbe" onclick="addSBEResult()">
		                                                <i class="fa fa-plus"></i>
		                                            </button>
		                                        </th>
		                                    </tr>
		                                </thead>
		                                <tbody id="tbtagsbe">
		                                </tbody>
		                            </table>
		                            <table class="table" id="table-service">
		                                <thead>
		                                    <tr>
		                                        <th>Service Type</th>
		                                        <th>Price</th>
		                                        <th class="text-center">
		                                            <button class="btn btn-xs btn-primary" type="button" style="border-radius:50%;width: 25px;height: 25px;" id="addService">
		                                                <i class="fa fa-plus"></i>
		                                            </button>
		                                        </th>
		                                    </tr>
		                                </thead>
		                                <tbody id="tbserviceprice">
		                                </tbody>
		                            </table>
		                            <table class="table" id="table-grand-total" style="display: none;">
		                                <tbody id="tbgrantotal">
		                                    <tr>
		                                        <td style='white-space: nowrap;width: 20%;vertical-align: middle;'>
		                                            <b>Grand Total</b>
		                                        </td>
		                                        <td style='white-space: nowrap;width: 80%'>
		                                            <div class='input-group'>
		                                                <span class='input-group-addon' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>
		                                                <input class='form-control' type='text' id="input_gran_total" readonly="true">
		                                            </div>
		                                        </td>
		                                    </tr>
		                                </tbody>
		                            </table>
		                        </div>
		                    </div>
		                    <div class="modal-footer">
		                        <button type="button" class="btn btn-secondary" id="prevBtn" onclick="closeClick()">Close</button>
		                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev()">Submit</button>
		                    </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="modal fade" id="modal_changelog_progress" role="dialog">
	    <div class="modal-dialog modal-sm">
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Add Progress</h4>
	        </div>
	        <div class="modal-body">
	            @csrf
	            <div class="form-group">
	              <label for="">Progress Date</label>
	              <input type="text" id="changelog_date" name="changelog_date" class="form-control date">
	            </div>
	          
	          <div class="form-group">
	            <label for="">Description</label>
	            <textarea name="changelog_progress" id="changelog_progress" onkeyup="textAreaAdjust(this)" class="form-control"></textarea>
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
	            <button type="button" class="btn btn-sm btn-primary" onclick="submitChangeLog()"><i class="fa fa-plus"> </i>&nbspSubmit</button>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>

	  <div class="modal fade" id="contributeModal" role="dialog">
		  <div class="modal-dialog modal-sm">
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        	<h4 class="modal-title">Add Contribute Presales</h4>
		      </div>
		      <div class="modal-body">
		        <div class="form-group">
		          <label for="">Choose Presales</label>
		          <select class="form-control" id="contribute_presales" name="contribute_presales" style="width:100%" required>
		          </select>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
		          <button type="button" class="btn btn-primary" onclick="submitContributePresales()"><i class="fa fa-plus"> </i>&nbspSubmit</button>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="changeReqModal" role="dialog">
		  <div class="modal-dialog modal-dialog-centered modal-sm">
		    <div class="modal-content">
		      <div class="modal-body divReqChange">		        
		      </div>
		      <div class="modal-footer">
	          <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
	          <button type="button" class="btn btn-primary btn-submit-change" id="btn-submit-change">Yes</button>
	        </div>
		    </div>
		  </div>
		</div>
</section>
@endsection
@section('scriptImport')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
@endsection
@section('script')
<script type="text/javascript">
		var isMobile = false; //initiate as false
		console.log(isMobile)
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
		    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
		    isMobile = true;
				$("#box-TP").height()
				$("#box-SD").height()		  	
		}

		function initmoney(){
			$(".money").mask('000.000.000.000', {reverse: true})
		}

		function textAreaAdjust(element) {
		  element.style.height = "1px";
		  element.style.height = (25+element.scrollHeight)+"px";
		  if(accesable.includes('formSD') || accesable.includes('formTP')){
				var heightSD = parseInt($("#box-SD").innerHeight())
				// $("#box-TP").height(heightSD)		      	
			}	
		}

		var accesable = @json($feature_item);
  	accesable.forEach(function(item,index){
    	$("#" + item).show()
  	})

  	$(document).ready(function(){
  		Pace.restart();
			Pace.track(function() {
				$("#pov,#assesment,#propossed_design").height( $("#pov,#assesment,#propossed_design")[0].scrollHeight)

	  		$.ajax({
					type:"GET",
					url:"{{url('/project/getDetailLead')}}",
					data:{
						lead_id:window.location.href.split("/")[5]
					},
					success:function(result){
						showSbe()

						if (result.data[0].result == 'LOSE') {
							    var i = 0;
							    $(".dot:eq(0)").first().animate({
						        backgroundColor: "#605ca8!important",
						      	color: "#fff",
						        width: 110,
						        height:110
						      }, 200 ).text('INITIAL');
							    $(".dot:eq(1)").first().animate({
						        backgroundColor: "#FF851B!important",
						      	color: "#fff",
						        width: 110,
						        height:110
						      }, 400 ).text('OPEN');
								 	$(".dot:eq(2)").first().animate({
						        backgroundColor: "#04dda3!important",
						      	color: "#fff",
						        width: 110,
						        height:110
						      }, 600 ).text('SOLUTION DESIGN');
									$(".dot:eq(3)").first().animate({
						        backgroundColor: "#f7e127!important",
						      	color: "#fff",
						        width: 110,
						        height:110
						      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
						        backgroundColor: "#e5140d!important",
						      	color: "#fff",
						        width: 110,
						        height:110
						      }, 1000 ).text("LOSE");
						} else if (result.data[0].result == 'HOLD') {
						    $(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#f7e127!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#dbd5c5!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					    	}, 1000 ).text("HOLD");
						} else if (result.data[0].result == 'SPECIAL') {
						    $(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#f7e127!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#dbd5c5!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					    	}, 1000 ).text("SPECIAL");
						} else if (result.data[0].result == 'CANCEL') {
						    $(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#f7e127!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#dbd5c5!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					    	}, 1000 ).text("CANCEL");
						} else if (result.data[0].result == '') {
						    $(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 800 ).text('TENDER PROCESS');
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 1000 ).text("WIN/LOSE");
					      accesable.forEach(function(item,index){
						  		if (item.includes('formSD')) {
						    		$("#" + item).prop('disabled',false)
						  		}
						  	})
						} else if (result.data[0].result == 'SD') {
								$(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
							$(".dot:eq(4)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 1000 ).text("WIN/LOSE");
						    accesable.forEach(function(item,index){
						  		if (item.includes('formSD')) {
						    		$("#" + item).prop('disabled',false)
						  		}
						  	})
						} else if (result.data[0].result == 'TP') {
								accesable.forEach(function(item,index){
						  		if (item.includes('formTP')) {
						    		$("#" + item).prop('disabled',false)
						  		}
						  	})
								$(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#f7e127!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#c6cccf!important",
					      	color: "#86898a",
					        width: 110,
					        height:110
					      }, 1000 ).text("WIN/LOSE");
						} else if (result.data[0].result == 'OPEN') {
								$(".dot:eq(0)").first().animate({
					        backgroundColor: "#aa0000!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 1000 );
						    $('#init').addClass('active5');
						} else if (result.data[0].result == "WIN"){
								$(".dot:eq(0)").first().animate({
					        backgroundColor: "#605ca8!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 200 ).text('INITIAL');
						    $(".dot:eq(1)").first().animate({
					        backgroundColor: "#FF851B!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 400 ).text('OPEN');
							 	$(".dot:eq(2)").first().animate({
					        backgroundColor: "#04dda3!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 600 ).text('SOLUTION DESIGN');
								$(".dot:eq(3)").first().animate({
					        backgroundColor: "#f7e127!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 800 ).text("TENDER PROCESS");
								$(".dot:eq(4)").first().animate({
					        backgroundColor: "#246d18!important",
					      	color: "#fff",
					        width: 110,
					        height:110
					      }, 1000 ).text("WIN");
	      				var i = 0;
	      		}

	  				if (result.data[0].result == "") {
	  					lead_id = '<span class="label" style="background-color: #f2562b;color: white">'+result.data[0].lead_id+'</span>'

	  				}else if (result.data[0].result == "SD") {
	  					$("#addChangeLog").show()
	  					lead_id = '<span class="label" style="background-color: #04dda3;color: white">'+result.data[0].lead_id+'</span>'

	  				}else if (result.data[0].result == "TP") {
	  					$("#addChangeLog").show()
	  					lead_id = '<span class="label" style="background-color: #f7e127;color: white">'+result.data[0].lead_id+'</span>'

	  				}else if (result.data[0].result == "WIN") {
	  					lead_id = '<span class="label" style="background-color: #246d18;color: white">'+result.data[0].lead_id+'</span>'

	  				}else if (result.data[0].result == "LOSE") {
	  					lead_id = '<span class="label" style="background-color: #e5140d;color: white">'+result.data[0].lead_id+'</span>'

	  				}else if (result.data[0].result == "HOLD") {
	  					lead_id = '<span class="label" style="background-color: #919e92;color: white">'+result.data[0].lead_id+'</span>'
	  					$("#addChangeLog").show()	  					

	  				}else if (result.data[0].result == "CANCEL") {
	  					lead_id = '<span class="label" style="background-color: #071108;color: white">'+result.data[0].lead_id+'</span>'

	  				}
						$("#box-title-detail").html('Detail - '+ lead_id)
						var append = ""

						append = append + '<tr>'
	  		  		append = append + '<th>Owner</th>'
	  		  		append = append + '<td>'+ result.data[0].name +'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Customer</th>'
	  		  		append = append + '<td>'+result.data[0].customer_legal_name+'<button class="btn btn-warning btn-edit-customer btn-xs pull-right">Edit</button></td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Opty Name</th>'
	  		  		append = append + '<td>'+result.data[0].opp_name+'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Current Presales</th>'
	  		  		append = append + '<td>'+result.data[0].name_presales+'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Amount</th>'
	  		  		append = append + '<td>'+ new Intl.NumberFormat('id').format(result.data[0].amount) +'<button class="btn btn-warning btn-edit-amount btn-xs pull-right" style="display:none">Edit</button></td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Closing date</th>'
	  		  		append = append + '<td>'+ result.data[0].closing_date+'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Product</th>'
	  		  		var str_array = result.data[0].name_product_tag.split(',')
	  		  		append = append + '<td>' 
	  		  		for(var i = 0; i < str_array.length; i++) {
	  		  			str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
	  		  			append = append + '<span class="badge bg-blue">' + str_array[i] + '</span> '
	  		  		}
	  		  		append = append +'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Technology</th>'
	  		  		var str_array_tech = result.data[0].name_tech.split(',')
	  		  		append = append + '<td>' 
	  		  		for(var i = 0; i < str_array_tech.length; i++) {
	  		  			str_array_tech[i] = str_array_tech[i].replace(/^\s*/, "").replace(/\s*$/, "");
	  		  			append = append + '<span class="badge bg-green">' + str_array_tech[i] + '</span> '
	  		  		}
	  		  		append = append +'</td>'
	  		  	append = append + '</tr>'
	  		  	append = append + '<tr>'
	  		  		append = append + '<th>Note</th>'
	  		  		append = append + '<td>'+result.data[0].keterangan+'</td>'
	  		  	append = append + '</tr>'

	  		  	$("#tbody-detail").append(append)

			      current_presales = []
			      current_presales.push(result.data[0].name_presales)

			      $.ajax({
					    url: "{{url('/project/getPresales')}}",
					    type: "GET",
					    success: function(result) {
					    	var newArr = []
					    	$.each(result.data,function(key,value){
					        if (current_presales[0].split(",").includes(value.text)) {
					        	newValue = $.grep(value, function(value) {
										  return value != current_presales;
										})
					        	// newValue = value.remove(current_presales)
					          newArr.push(newValue)
					        }else{
					        	newArr.push(value)
					        }
					      })
				        $("#contribute_presales").select2({
				        	placeholder:"Select Contribute Presales",
				        	multiple:true,
				        	data:newArr
				        })
					    }
						})

			      id_customer = result.data[0].id_customer
						$(".btn-edit-customer").click(function(){
							$("#changeReqModal").modal("show")
							$(".divReqChange").empty("")

							var append = ""
							append = append + "<div><center><i class='fa fa-warning fa-5x' style='color:#f1c40f'></i></center></div>"

							append = append + "<div><i></i><h3><center>Are you sure to change this customer?</center></h3><center>By change this customer, an email will be sent to your supervisor as permission for this activity.Then you must write down your reasons why the customer in must be changed below.</center></div>"
							append = append + "<div class='form-group'>"
							append = append + "<label>Reason :</label>"
							append = append + "<textarea class='form-control' id='textareaChangeCus'></textarea>"
							append = append + "</div>"
							append = append + "<div class='form-group'>"
							append = append + "<label>Customer :</label>"
							append = append + "<select class='select2 form-control selectChangeCus' id='select2ChangeCus' style='width:100%'></select>"
							append = append + "</div"							

							$(".divReqChange").append(append)

							$.ajax({
								url: "{{url('/project/getCustomer')}}",
								type: "GET",
								success:function(result){
									$(".selectChangeCus").select2({
						      	placeholder: "Select Customer",
								  	data:result.data,
									  dropdownParent: $("#changeReqModal"),
								  })

									$('.selectChangeCus').val(id_customer).trigger('change')

								}
							})

							$(".btn-submit-change").click(function(){
								$(".btn-submit-change").attr("onclick",submitChangeCustomer($("#textareaChangeCus").val(),$("#select2ChangeCus").select2('data')[0].id))
							})
							

						})	

						amount = result.data[0].amount
						if (result.data[0].result == 'WIN') {
							$(".btn-edit-amount").css("display","block")
						}
						$(".btn-edit-amount").click(function(){
							$("#changeReqModal").modal("show")
							$(".divReqChange").empty("")

							var append = ""
							append = append + "<div><center><i class='fa fa-warning fa-5x' style='color:#f1c40f'></i></center></div>"
							
							append = append + "<div><i></i><h3><center>Are you sure to change this customer?</center></h3><center>By change this customer, an email will be sent to your supervisor as permission for this activity.Then you must write down your reasons why the customer in must be changed below.</center></div>"
							append = append + "<div class='form-group'>"
							append = append + "<label>Reason :</label>"
							append = append + "<textarea id='textareaChangeAmount' class='form-control'></textarea>"
							append = append + "</div>"
							append = append + "<div class='form-group'>"
							append = append + "<label>Amount :</label>"
								append = append + "<div class='input-group'><span class='input-group-addon'>Rp.</span>"
									append = append + "<input id='inputChangeAmount' class='form-control' value='"+amount+"'/>"
								append = append + "</div>"
							append = append + "</div"							

							$(".divReqChange").append(append)

							$("#inputChangeAmount").unmask().mask('000.000.000.000', {reverse: true})

							$(".btn-submit-change").click(function(){
								$(".btn-submit-change").attr("onclick",submitChangeAmount($("#textareaChangeAmount").val(),$("#inputChangeAmount").val()))
							})
							

						})					

					}
				})

				function submitChangeCustomer(textarea,input){
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
						url:"{{url('/project/changeCustomer')}}",
						data:{
							_token: "{{ csrf_token() }}",
							lead_id:window.location.href.split("/")[5],
							input_cus:input,
							input_reason:textarea
						},
						success: function(result) {
                Swal.showLoading()
                Swal.fire(
                    'Successfully!',
                   	'Send Request Change.',
                    'success'
                ).then((result) => {
                    if (result.value) {
                    	location.reload()
                    	$("#changeReqModal").modal('hide')
                    }
                })
            }
					})
					
				}

				function submitChangeAmount(textarea,input){
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
							url:"{{url('/project/changeNominal')}}",
							data:{
								_token: "{{ csrf_token() }}",
								lead_id:window.location.href.split("/")[5],
								input_amount:input.replace(/\./g,''),
								input_reason:textarea
							},
							success: function(result) {
	                Swal.showLoading()
	                Swal.fire(
	                    'Successfully!',
	                   	'Send Request Change.',
	                    'success'
	                ).then((result) => {
	                    if (result.value) {
	                    	location.reload()
	                    	$("#changeReqModal").modal('hide')
	                    }
	                })
	            }
						})
				}

				$.ajax({
					type:"GET",
					url:"{{url('/project/getLeadSd')}}",
					data:{
						lead_id:window.location.href.split("/")[5]
					},
					success:function(result){
						if (result.data != null) {
							$("#assesment").val(result.data.assessment)
							if (result.data.assessment_date != '-') {
								$("#assessment_last_update").html('<small> Last Update : '+result.data.assessment_date+'</small>')
							}
							$("#propossed_design").val(result.data.pd)
							if (result.data.pd_date != '-') {
								$("#pDesign_last_update").html('<small> Last Update : '+result.data.pd_date+'</small>')
							}
							$("#pov").val(result.data.pov)
							if (result.data.pov_date != '-') {
								$("#pov_last_update").html('<small> Last Update : '+result.data.pov_date+'</small>')
							}
							$("#amount_check").val(result.data.pb)
							$("#priority").val(result.data.priority)
							$("#proyek_size").val(result.data.project_size)
							$("#project_budget").val(result.data.pb).unmask().mask('000.000.000.000', {reverse: true})		

							$("#pov").height( $("#pov")[0].scrollHeight)
							$("#propossed_design").height( $("#propossed_design")[0].scrollHeight)
							$("#assesment").height( $("#assesment")[0].scrollHeight)
						}
						

						var fd = new FormData()			
	
						$("#btnSubmitSD").click(function(){
							if (parseInt($("#project_budget").val().replaceAll(".", "")) > result.data.amount) {
			  				$("#project_budget").closest('.form-group').addClass('has-error')
								$("#project_budget").closest('div div').next('span').show();
								$("#project_budget").prev('.input-group-addon').css("background-color","red");
			  			}else{
			  				if($("#assesment").val() != result.data.assessment){
									assessment = $("#assesment").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Assessment ' + assessment
					            },
					        })	

									fd.append('assessment_date',moment().format('YYYY-MM-DD hh:mm:ss'))
								}

								if ($("#propossed_design").val() != result.data.pd) {
									propossed_design = $("#propossed_design").val()	
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Propossed Design ' + propossed_design
					            },
					        })		

									fd.append('pd_date',moment().format('YYYY-MM-DD hh:mm:ss'))			
								}

								if ($("#pov").val() != result.data.pov) {
									pov = $("#pov").val()	
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Proof Of Value ' + pov
					            },
					        })

									fd.append('pov_date',moment().format('YYYY-MM-DD hh:mm:ss'))					
								}

								if ($("#project_budget").val().replaceAll(".","") != result.data.pb) {
									project_budget = $("#project_budget").val()	
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Project Budget ' + project_budget
					            },
					        })					
								}

								if ($("#priority").val() != result.data.priority) {
									priority = $("#priority").val()	
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Priority ' + priority
					            },
					        })					
								}

								if ($("#proyek_size").val() != result.data.project_size) {
									proyek_size = $("#proyek_size").val()	
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelog_sd')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Project Size ' + proyek_size
					            },
					        })					
								}							

								fd.append('assessment',$("#assesment").val())	
								fd.append('propossed_design',$("#propossed_design").val())	
								fd.append('pov',$("#pov").val())	
	              fd.append('priority',$("#priority").val())
	              fd.append('project_budget',$("#project_budget").val())
	              fd.append('proyek_size',$("#proyek_size").val())
	              fd.append('_token',"{{csrf_token()}}")
	              fd.append('lead_id',window.location.href.split("/")[5])
	              

								$("btnSubmitSD").attr("onclick",btnSubmit(fd,'SD'))
			  			}							
						})	
					}
				})

				$.ajax({
					type:"GET",
					url:"{{url('/project/getLeadTp')}}",
					data:{
						lead_id:window.location.href.split("/")[5]
					},
					success:function(result){
						$("#lelang").val(result.data.auction_number)
						$("#submit_price").val(result.data.submit_price).mask('000.000.000.000', {reverse: true})	
						$("#project_class").val(result.data.project_class)
						if (result.data.project_class == 'multiyears' || result.data.project_class == 'blanket') {
							$("#tahun_jumlah").css("display", "block").val()
       				$("#total_price_deal").css("display", "block").val()
        			$("#price_deal").css("display", "block").val()
						}
						$("#jumlah_tahun").val(result.data.jumlah_tahun)
						$("#deal_price_total").unmask().mask('000.000.000.000', {reverse: true});
   					$("#deal_price_total").val(result.data.deal_price).trigger("input");
						$("#deal_price").unmask().mask('000.000.000.000', {reverse: true});
   					$("#deal_price").val(result.data.deal_price).trigger("input");	
						$("#win_prob").val(result.data.win_prob)
						$("#project_name").val(result.data.project_name)
						$("#submit_date").val(result.data.submit_date)

						var fd = new FormData()

						var i = 0;

						$("#btnSubmitTP").click(function(){
								if($("#lelang").val() != result.data.auction_number){
									auction_number = $("#lelang").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'No Lelang ' + auction_number
					       			},					
									})
								}

								if($("#submit_date").val() != result.data.submit_date){
									submit_date = $("#submit_date").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Submit Date ' + submit_date
					            },
					        })						
								}

								if($("#project_name").val() != result.data.project_name){
									project_name = $("#project_name").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Project Name ' + project_name
					            },
					        })						
								}

								if($("#deal_price").val().replaceAll(".","") != result.data.deal_price){
									deal_price = $("#deal_price").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Deal Price ' + deal_price
					            },
					        })						
								}

								if($("#win_prob").val() != result.data.win_prob){
									win_prob = $("#win_prob").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Win Probability ' + win_prob
					            },
					        })						
								}

								if($("#deal_price_total").val().replaceAll(".","") != result.data.deal_price_total){
									deal_price_total = $("#deal_price_total").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Deal Price Total ' + deal_price_total
					            },
					        })						
								}

								if($("#submit_price").val().replaceAll(".","") != result.data.submit_price){
									submit_price = $("#submit_price").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Submit Price ' + submit_price
					            },
					        })						
								}

								if($("#project_class").val() != result.data.project_class){
									project_class = $("#project_class").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Project Class ' + project_class
					            },
					        })						
								}

								if($("#quote_number").val() != result.data.quote_number){
									quote_number = $("#quote_number").val()
									$.ajax({
					            type: "POST",
					            url: "{{url('/project/changelogTp')}}",
					            data: {
					              _token: "{{ csrf_token() }}",
					              lead_id:window.location.href.split("/")[5],
					              status:'Quote Number ' + quote_number
					            },
					        })						
								}

		            fd.append('_token',"{{csrf_token()}}")
								fd.append("lead_id",window.location.href.split("/")[5])
		            fd.append("submit_price",$("#submit_price").val())
		            fd.append("lelang",$("#lelang").val())
		            fd.append("win_prob",$("#win_prob").val())
		            fd.append("project_name",$("#project_name").val())
		            fd.append("submit_date",$("#submit_date").val())
		            fd.append("assigned_by",$("#assigned_by").val())
		            fd.append("quote_number",$("#quote_number").val())
		            fd.append("deal_price",$("#deal_price").val())
		            fd.append("project_class",$("#project_class").val())
		            fd.append("jumlah_tahun",$("#jumlah_tahun").val())
		            fd.append("deal_price_total",$("#deal_price_total").val())
									
								$("btnSubmitTP").attr("onclick",btnSubmit(fd,'TP'))
						})

						$("#btnResult").click(function(){
							if (result.data.deal_price == "") {
								$("#deal_price").closest('.form-group').addClass('has-error')
								$("#deal_price").closest('div div').next('span').show();
								$("#deal_price").prev('.input-group-addon').css("background-color","red");
							}else{
								$("#btnResult").attr("onclick",btnResult(result.data.lead_id,result.data.id_customer,result.data.opp_name))
							}
						})			

					}
  			})

				// if ($("#btnRaiseTP").is(":disabled")) {
				// 	$("#btnRaiseTP").hover(function(){
				// 		$("#btnRaiseTP").attr("data-toggle", "tooltip").attr('data-original-title', "Please Submit First!").tooltip('show')
				// 	})					
				// }
				
			})
		})

		function grandTotal(){
    	var sum = 0
      $('.new-price-win').each(function() {
          var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
          sum += temp;
      });
      $('.price_sbe_win').each(function() {
          var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
          sum += temp;
      });
     
      $("#table-grand-total").show()
      var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      });

      $("#input_gran_total").val(formatter.format(sum))
    }

    var arrSbe = []
    arrSbe = [
	    {
	      "id": 1,
	      "text": "Supply Only"
	    },
	    {
	      "id": 2,
	      "text": "Maintenance"
	    },
	    {
	      "id": 3,
	      "text": "Implement Maintenance"
	    },
	    {
	      "id": 4,
	      "text": "Implement"
	    }
	  ]

    function addSBE(){
    	i = 0
    	i++;    	

      var append = ""
      append = append + "<tr class='new-SBE'>"
      append = append + " <td><input hidden class='id' name='id_sbe' data-value='"+ i +"' />"
      append = append + " <select class='form-control select2-customSBE tag_sbe' id='searchSBE' name='tag_SBE' data-value='" + i + "' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon price-tooltip' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input data-value='" + i + "' class='money form-control price_sbe' name='price_SBE' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' data-value='"+i+"' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging-sbe'><i class='fa fa-trash'></i></button><button type='button' data-value='"+i+"' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat disabled'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"

      $("#tbSBE").append(append)
      initmoney();
      $(".btn-edit-tagging-sbe").prop("disabled",true)

      $("#searchSBE[data-value='" + i + "']").select2({
			  data: arrSbe
      })
    }

    function addSBEResult(){
    	i = 0
    	i++;    	

      var append = ""
      append = append + "<tr class='tag-sbe-win'>"
      append = append + " <td><input hidden class='id' name='id_sbe' data-value='"+ i +"' />"
      append = append + " <select class='form-control select2-customSBE tag_sbe_win' id='searchSBE' name='tag_sbe_win' data-value='" + i + "' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon price-tooltip' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input data-value='" + i + "' class='money form-control price_sbe_win' name='price_sbe_win' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' data-value='"+i+"' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging-sbe-win'><i class='fa fa-trash'></i></button><button type='button' data-value='"+i+"' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat disabled'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"

      $("#tbtagsbe").append(append)
      initmoney();
      $(".btn-edit-tagging-sbe").prop("disabled",true)

      $("#searchSBE[data-value='" + i + "']").select2({
			  data: arrSbe
      })
    }

    var i;
    var idExist = []
    $.ajax({
    	url: "{{url('/project/showTagging')}}",
      type: "GET",
      data: {
          lead_id: window.location.href.split('/')[5],
      },success: function(result){
    		var i = 0;
      	$.each(result, function(key,value){
      		addTaggingNotEmpty(value.id,value.id_product_tag,value.id_technology_tag,value.price,i)
    			idExist.push(value.id)
      		++i
      	})
				$("#btn-addTagging").click(function(){
      		$("#btn-addTagging").attr('onclick',addTagging(i++))
				})

      	if (result.length > 0){
      		if ($('#tbtagging tr').length < 0) {
		  			$("#btnRaiseTP").prop("disabled",true)
		  		}else{
		  			$("#btnRaiseTP").prop("disabled",false)
		  		}
      	}     		

      }
    })
		
  	function addTagging(i){
  		if ($('#tbtagging tr').length < 0) {
  			$("#btnRaiseTP").prop("disabled",false)
  		}else{
  			$("#btnRaiseTP").prop("disabled",true)
  		}

  		$.ajax({
        url: "{{url('/project/getProductTechTagDetail')}}",
        type: "GET",
        data: {
            lead_id: window.location.href.split('/')[5],
        },
        success: function(result) {
            $("#searchTagsProductSol[data-value='" + i + "']").empty("");
            $("#searchTagsTechnologySol[data-value='" + i + "']").empty("");
            var product_tag = result.product_tag;
            var product_tag_selectOption = [];

            var technology_tag = result.technology_tag;
            var technology_tag_selectOption = [];

            $.each(product_tag, function(key, value) {
                product_tag_selectOption.push(value)
            })

            $.each(technology_tag, function(key, value) {
                technology_tag_selectOption.push(value)
            })

            var TagProduct = $("#searchTagsProductSol[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Product",
                data: product_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
            var TagProduct = $("#searchTagsTechnologySol[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Technology",
                data: technology_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
        }
    	})

      i++;
      var append = ""
      append = append + "<tr class='new-tagging'>"
      append = append + " <td><input hidden class='id' data-value='"+ i +"' />"
      append = append + " <select class='form-control select2-customProductSol' data-value='" + i + "' id='searchTagsProductSol' name='searchTagsProductSol' style='width: 100%!important' required></select>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + " <select class='form-control select2-customTechnologySol' data-value='" + i + "' id='searchTagsTechnologySol' name='searchTagsTechnologySol' style='width: 100%!important' required></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon price-tooltip' data-toggle='tooltip' title='50000' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input data-value='" + i + "' class='money form-control new-price-sol' name='new-price-sol' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' data-value='"+i+"' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging' value='"+i+"'><i class='fa fa-trash'></i></button><button type='button' data-value='"+i+"' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat disabled'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"
      
      $("#tbtagging").append(append)
      initmoney();
      $(".btn-edit-tagging").prop("disabled",true)
    }

    function showSbe(status){
    	$.ajax({   		
	    	url: "{{url('/project/showSbeTagging')}}",
	      type: "GET",
	      data: {
	          lead_id: window.location.href.split('/')[5],
	      },success: function(result){
	    		i = 0
	      	$.each(result, function(key,value){
	      		addSbeNotEmpty(value.id,value.tag_sbe,value.price_sbe,i)
	    			idExist.push(value.id)
	      		++i
	      	})
	      	if (result.length > 0){
	      		if ($('#tbtagging tr').length < 0) {
			  			$("#btnRaiseTP").prop("disabled",true)
			  		}else{
			  			$("#btnRaiseTP").prop("disabled",false)
			  		}
	      	}	

	      }
	    })
    }    

    $(document).on('keypress', '.new-price-sol', function() {
    	if (isMobile == true) {
    		console.log('oke')
			  Swal.fire({
				  title: 'product price',
				  input: 'text',
				  inputAttributes: {
				    autocapitalize: 'off',
				    id: 'price'
				  },
				  onOpen: function(el) {
	        	var container = $(el);
	        	container.find('#price').mask('000.000.000.000', {reverse: true});
	    		},
				  showCancelButton: true,
				  confirmButtonText: 'oke',
				}).then((result) => {
				  if (result.isConfirmed) {
				    $(".new-price-sol[data-value='" + i + "']").val(result.value)
				  }
				})
    	}		
		})   

    function addTaggingNotEmpty(id,id_product,id_tech,price,i){
    	var append = ""
      append = append + "<tr class='exist-tagging'>"
      append = append + "<td hidden><input id='tagging_status' data-value='"+ i +"'/><input class='id' name='id' data-value='"+i+"'/></td>"
      append = append + " <td>"
      append = append + " <select disabled class='form-control col-xs-12 select2-customProductSol' data-value='" + i + "' id='searchTagsProductSol' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + " <select disabled class='form-control col-xs-12 select2-customTechnologySol' data-value='" + i + "' id='searchTagsTechnologySol' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon price-tooltip' data-toggle='tooltip'  style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input disabled data-value='" + i + "' class='money form-control col-xs-12 new-price-sol' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging'><i class='fa fa-trash'></i></button><button data-value='"+ i +"' type='button' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat btn-edit-tagging'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"

      $("#tbtagging").append(append)	      

  		$.ajax({
        url: "{{url('/project/getProductTechTagDetail')}}",
        type: "GET",
        success: function(result) {
            $("#searchTagsProductSol[data-value='" + i + "']").empty("");
            $("#searchTagsTechnologySol[data-value='" + i + "']").empty("");
            var product_tag = result.product_tag;
            var product_tag_selectOption = [];

            var technology_tag = result.technology_tag;
            var technology_tag_selectOption = [];

            $.each(product_tag, function(key, value) {

            	if (value.id == "p"+id_product) {
            		value.selected = true
            	}
                product_tag_selectOption.push(value)
            })
            $.each(technology_tag, function(key, value) {
            	if (value.id == "t"+id_tech) {
            		value.selected = true
            	}
                technology_tag_selectOption.push(value)
            })

            var TagProduct = $("#searchTagsProductSol[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Product",
                data: product_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
            
            var TagProduct = $("#searchTagsTechnologySol[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Technology",
                data: technology_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
        }
    	})

  		$("[data-toggle=tooltip][data-value='" + i + "']").mouseenter(function(){
    		var $this = $(this);
        $this.attr('title', 'uang');
    	})  
    	$(".id[data-value='" + i + "']").val(id) 
  		$(".new-price-sol[data-value='" + i + "']").val(price)  

      initmoney();
    }

    function addProductTechInitiate(id,id_product,id_tech,price,i){
    	var append = ""
      append = append + "<tr class='exist-product'>"
      append = append + " <td><input id='tagging-win-status' hidden data-value='"+i+"'/><input hidden class='idWinTagging' name='id-win' data-value='"+ i +"'/>"
      append = append + " <select disabled class='form-control select2-customProduct' data-value='" + i + "' id='searchTagsProduct' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + " <select disabled class='form-control select2-customTechnology' data-value='" + i + "' id='searchTagsTechnology' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input disabled data-value='" + i + "' class='money form-control new-price-win' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' data-value='"+ i +"' style='width: auto !important;vertical-align:sub' class='btn btn-sm btn-danger btn-flat btn-trash'><i class='fa fa-trash'></i></button><button data-value='"+ i +"' type='button' style='width: auto !important;vertical-align:sub;margin-left:5px' class='btn btn-sm btn-primary btn-flat btn-edit-taggingWin'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"

      $("#tbtagprice").append(append)	      

  		$.ajax({
        url: "{{url('/project/getProductTechTagDetail')}}",
        type: "GET",
        success: function(result) {
            $("#searchTagsProduct[data-value='" + i + "']").empty("");
            $("#searchTagsTechnology[data-value='" + i + "']").empty("");
            var product_tag = result.product_tag;
            var product_tag_selectOption = [];

            var technology_tag = result.technology_tag;
            var technology_tag_selectOption = [];

            $.each(product_tag, function(key, value) {

            	if (value.id == "p"+id_product) {
            		value.selected = true
            	}
                product_tag_selectOption.push(value)
            })
            $.each(technology_tag, function(key, value) {
            	if (value.id == "t"+id_tech) {
            		value.selected = true
            	}
                technology_tag_selectOption.push(value)
            })

            var TagProduct = $("#searchTagsProduct[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Product",
                data: product_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
            
            var TagProduct = $("#searchTagsTechnology[data-value='" + i + "']").select2({
                dropdownParent: $('#formSD'),
                placeholder: " Select #Tags#Technology",
                data: technology_tag_selectOption,
                templateSelection: function(selection, container) {
                    return $.parseHTML('<span>' + selection.text + '</span>');
                }
            })
        }
    	})

    	$(".new-price-win[data-value='" + i + "']").val(price)	    

    	$(".idWinTagging[data-value='" + i +"']").val(id)  

      initmoney();
    }

    function addSbeNotEmpty(id,tag_sbe,price,i){
    	var append = ""
      append = append + "<tr class='exist-tagging-sbe'>"
      append = append + " <td><input hidden id='sbe_tagging_status' data-value='" + i +"'><input hidden class='id_sbe' name='id_sbe' data-value='"+ i +"' />"
      append = append + " <select class='form-control select2-customSBE tag_sbe' disabled id='searchSBE' name='tag_SBE' data-value='" + i + "' style='width: 100%!important'></select>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <div class='input-group'>"
      append = append + " <span class='input-group-addon price-tooltip' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
      append = append + " <input data-value='" + i + "' class='money form-control price_sbe' disabled name='price_SBE' type='text' placeholder='Enter Product Price'>"
      append = append + " </div>"
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging-sbe'><i class='fa fa-trash'></i></button><button data-value='"+ i +"' type='button' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat btn-edit-tagging-sbe'><i class='fa fa-pencil'></i></button>"
      append = append + " </td>"
      append = append + "</tr>"

      $("#tbSBE").append(append)	 
      $("#searchSBE[data-value='" + i + "']").select2({
			  data: arrSbe
      })    

    	$(".id_sbe[data-value='" + i + "']").val(id) 
  		$(".price_sbe[data-value='" + i + "']").val(price) 
  		$(".tag_sbe[data-value='" + i + "']").val(tag_sbe).trigger('change')   

      initmoney();
    }

    function addSbeResultNotEmpty(id,tag_sbe,price,i){
	    	var append = ""
	      append = append + "<tr class='exist-sbe-win'>"
	      append = append + " <td><input id='tagging-sbe-win-status' hidden data-value='"+i+"'/><input hidden class='id_sbe_win' name='id_sbe' data-value='"+ i +"' />"
	      append = append + " <select class='form-control select2-customSBE tag_sbe_win' disabled id='searchSBE' name='tag_sbe_win' data-value='" + i + "' style='width: 100%!important'></select>"
	      append = append + " </td>"
	      append = append + " <td style='white-space: nowrap'>"
	      append = append + " <div class='input-group'>"
	      append = append + " <span class='input-group-addon price-tooltip' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
	      append = append + " <input data-value='" + i + "' class='money form-control price_sbe_win' disabled name='price_sbe_win' type='text' placeholder='Enter Product Price'>"
	      append = append + " </div>"
	      append = append + " </td>"
	      append = append + " <td class='text-center'>"
	      append = append + " <button type='button' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash-tagging-sbe-win'><i class='fa fa-trash'></i></button><button data-value='"+ i +"' type='button' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat btn-edit-tagging-sbe-win'><i class='fa fa-pencil'></i></button>"
	      append = append + " </td>"
	      append = append + "</tr>"

	      $("#tbtagsbe").append(append)	 
	      $("#searchSBE[data-value='" + i + "']").select2({
				  data: arrSbe
	      })    

	    	$(".id_sbe_win[data-value='" + i + "']").val(id) 
	  		$(".price_sbe_win[data-value='" + i + "']").val(price) 
	  		$(".tag_sbe_win[data-value='" + i + "']").val(tag_sbe).trigger('change')   

	      initmoney();
    }

		function submitContributePresales(){
			var concat_name = []
			$.each($("#contribute_presales").select2("data"),function(key,value){
				var StringName = value.text;
    		concat_name.push(StringName);
			})

			Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's processing..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
			$.ajax({
				type:"POST",
				url:"{{url('/project/addContribute')}}",
				data:{
					lead_cont:window.location.href.split("/")[5],
					nik_cont:$("#contribute_presales").val(),
					concat_name:concat_name.join(','),
					_token:"{{csrf_token()}}"
				},success:function(){
					Swal.showLoading()
          Swal.fire(
              'Successfully!',
             	'Contribute Presales Added.',
              'success'
          ).then((result) => {
              if (result.value) {
              	location.reload()
              	$("#contributeModal").modal('hide')
              }
          })
				}
			})
		}

  	function selectProjectClass(val){
			$("#box-TP").height()
			
  		if (val == 'blanket') {
  			$("#tahun_jumlah").css("display", "block");
        $("#total_price_deal").css("display", "block");
        $("#price_deal").css("display", "block");
        $("#purchase-order").css("display", "block");
  			$("#box-SD").height($("#box-TP").innerHeight())

  		}else if (val == 'multiyears') {
  			$("#tahun_jumlah").css("display", "block");
       	$("#total_price_deal").css("display", "block");
        $("#price_deal").css("display", "block");
  			$("#box-SD").height($("#box-TP").innerHeight())

  		}else if (val == 'normal') {
  			$("#tahun_jumlah").css("display", "none");
        $("#total_price_deal").css("display", "none");
        $("#box-SD").height("")
  		}else{
  			$("#tahun_jumlah").css("display", "none");
        $("#total_price_deal").css("display", "none");
        $("#price_deal").css("display", "none");
        $("#purchase-order").css("display", "none");
        $("#box-SD").height("")
  		}  		
  	}

  	function btnRaiseTP(){
  		Swal.fire({
        title: 'RAISE TO TENDER?',
        text: "are you sure!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
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
            url:"{{url('/project/raiseTender')}}",
            data:{
              lead_id:window.location.href.split("/")[5],
              _token:"{{ csrf_token() }}"
            },
            success: function(result){
              Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'Raise to Tender has been success.',
                'success'
              ).then((result) => {
                if (result.value) {
                  location.reload();
                }
              })
            }
          })
        }
      })
  	}

  	var deletedProduct = []
  	var deletedProductWin = []
  	var deleteSbe = []
  	var deleteSbeWin = []
  	var nameSbe = []
  	var priceSbe = []
  	var objWin = {};

    $(document).on('click', '.btn-trash', function() {
      $(this).closest("tr").remove();
      if($(this).closest("tr.new-service").length > 0){
        $("#addService").show()
      }

      grandTotal()

      row = $(this).parents("tr").find("input[name='id-win']").val();
    	deletedProductWin.push(row)
    });

		$(document).on('click', '.btn-trash-tagging', function() {
      $(this).closest("tr").remove();
      if ($('#tbtagging tr').length < 0) {
  			$("#btnRaiseTP").prop("disabled",false)
  		}else{
  			$("#btnRaiseTP").prop("disabled",true)
  		}
		  row = $(this).parents("tr").find("input[name='id']").val();
    	deletedProduct.push(row)

    	$(".btn-edit-tagging").prop("disabled",false)
    });

    $(document).on('click', '.btn-trash-tagging-sbe', function() {
      $(this).closest("tr").remove();
      if ($('#tbSbe tr').length < 0) {
  			$("#btnRaiseTP").prop("disabled",false)
  		}else{
  			$("#btnRaiseTP").prop("disabled",true)
  		}
		  rowId = $(this).parents("tr").find("input[name='id_sbe']").val();
		  rowName = $(this).parents("tr").find(".tag_sbe option:selected").text();
		  rowPrice = $(this).parents("tr").find(".price_sbe").val();

    	deleteSbe.push(rowId)
    	nameSbe.push(rowName)
    	priceSbe.push(rowPrice)

    	$(".btn-edit-tagging-sbe").prop("disabled",false)
    });

    $(document).on('click', '.btn-trash-tagging-sbe-win', function() {
      $(this).closest("tr").remove();
      if($(this).closest("tr.new-service").length > 0){
        $("#addService").show()
      }
			grandTotal()

      rowId = $(this).parents("tr").find("input[name='id_sbe']").val();
		  rowName = $(this).parents("tr").find(".tag_sbe_win option:selected").text();
		  rowPrice = $(this).parents("tr").find(".price_sbe_win").val();

		  objWin["id"] = rowId
		  objWin["name"] = rowName
			objWin["price"] = rowPrice

    	deleteSbeWin.push(objWin)
    });

    $(document).on('click', '.btn-edit-tagging', function() {
    	$(this).parents("tr").find(".select2-customProductSol").prop("disabled",false)
    	$(this).parents("tr").find(".select2-customTechnologySol").prop("disabled",false)
    	$(this).parents("tr").find(".new-price-sol").prop("disabled",false)
    	$(this).parents("tr").find(".btn-edit-tagging").removeClass('btn-primary').addClass('btn-warning')
    	$(this).parents("tr").find(".btn-edit-tagging").find('i').removeClass('fa-pencil').addClass('fa-check')
		  id_exist = $(this).parents("tr").find("input[name='id']").val()
    	product = $(this).parents("tr").find(".select2-customProductSol").val().substr(1)
    	techno = $(this).parents("tr").find(".select2-customTechnologySol").val().substr(1)
    	price = $(this).parents("tr").find(".new-price-sol").val()
    	dataValue = $(this).parents("tr").find(".new-price-sol").data("value")
    	id_val = $(this).parents("tr").find("#tagging_status").val()
    	
    	if (id_val == '') {
    		$("#tagging_status[data-value='"+ dataValue +"']").val("pencil"+dataValue)
    	}else if (id_val == "pencil"+dataValue) {
	  		$(this).parents("tr").find(".btn-edit-tagging").attr("onclick",updateTagging(id_exist,product,techno,price,dataValue))
    		$("#tagging_status[data-value='"+ dataValue +"']").val('')
    	}

    	$("#btn-addTagging").prop("disabled",true).attr("data-toggle", "tooltip").attr('title', "You in edit mode!").show()

    	$("#btnSubmitSD").prop("disabled",true)
    	$("#btnRaiseTP").prop("disabled",true)
    })

    $(document).on('click','.btn-edit-tagging-sbe',function(){
    	$(this).parents("tr").find(".tag_sbe").prop("disabled",false)
    	$(this).parents("tr").find(".price_sbe").prop("disabled",false)
    	$(this).parents("tr").find(".btn-edit-tagging-sbe").removeClass('btn-primary').addClass('btn-warning')
    	$(this).parents("tr").find(".btn-edit-tagging-sbe").find('i').removeClass('fa-pencil').addClass('fa-check')
		  id_exist = $(this).parents("tr").find(".id_sbe").val()
    	tag_sbe = $(this).parents("tr").find(".tag_sbe").val()
    	name_sbe = $(this).parents("tr").find(".tag_sbe option:selected").text()
    	price = $(this).parents("tr").find(".price_sbe").val()
    	dataValue = $(this).parents("tr").find(".price_sbe").data("value")
    	id_val = $(this).parents("tr").find("#sbe_tagging_status").val()
    	
    	if (id_val == '') {
    		$("#sbe_tagging_status[data-value='"+ dataValue +"']").val("pencil"+dataValue)
    	}else if (id_val == "pencil"+dataValue) {
	  		$(this).parents("tr").find(".btn-edit-tagging-sbe").attr("onclick",updateTaggingSbe(id_exist,tag_sbe,name_sbe,price,dataValue))
    		$("#sbe_tagging_status[data-value='"+ dataValue +"']").val('')
    	}
    	$("#btn-addSBE").prop("disabled",true).attr("data-toggle", "tooltip").attr('title', "You in edit mode!").show()

    	$("#btnSubmitSD").prop("disabled",true)
    	$("#btnRaiseTP").prop("disabled",true)
    })
    
    var activeBtnEdit = 0 

    $(document).on('click', '.btn-edit-taggingWin', function() {
    	$(this).parents("tr").find(".select2-customProduct").prop("disabled",false)
    	$(this).parents("tr").find(".select2-customTechnology").prop("disabled",false)
    	$(this).parents("tr").find(".new-price-win").prop("disabled",false)
    	$(this).parents("tr").find(".btn-edit-taggingWin").removeClass('btn-primary').addClass('btn-warning')
    	$(this).parents("tr").find(".btn-edit-taggingWin").find('i').removeClass('fa-pencil').addClass('fa-check')
		  var id_exist = $(this).parents("tr").find("input[name='id-win']").val()
    	var product = $(this).parents("tr").find(".select2-customProduct").val().substr(1)
    	var techno = $(this).parents("tr").find(".select2-customTechnology").val().substr(1)
    	var price = $(this).parents("tr").find(".new-price-win").val()
    	var dataValue = $(this).parents("tr").find(".idWinTagging").data("value")
    	var lead_id = window.location.href.split("/")[5]

    	id_val = $(this).parents("tr").find("#tagging-win-status").val()
    	console.log(activeBtnEdit)
    	$("#nextBtn").prop("disabled",true)

    	if (id_val == '') {
    		// $("#tagging-win-status[data-value="+dataValue+"]").val("pencil"+dataValue) 
    		$("#tagging-win-status[data-value='"+dataValue+"']").val("pencil"+dataValue)    		
    		activeBtnEdit++
    	}else if (id_val == "pencil"+dataValue) {
    		if (price == '') {
    			Swal.fire({
	          title: 'Can`t Submit Result!',
	          icon: 'error',
	          html: "<p style='text-align:center;'>Column Price is Required!</p>",
	          confirmButtonText: 'Oke',
	        })
    		}else{
    			$(this).parents("tr").find(".btn-edit-taggingWin").attr("onclick",updateTaggingWin(id_exist,product,techno,price,dataValue,lead_id))
    			$("#tagging-win-status[data-value='"+dataValue+"']").val("")  
    		} 
    	}

    }) 

    $(document).on('click','.btn-edit-tagging-sbe-win',function(){
    	$(this).parents("tr").find(".tag_sbe_win").prop("disabled",false)
    	$(this).parents("tr").find(".price_sbe_win").prop("disabled",false)
    	$(this).parents("tr").find(".btn-edit-tagging-sbe-win").removeClass('btn-primary').addClass('btn-warning')
    	$(this).parents("tr").find(".btn-edit-tagging-sbe-win").find('i').removeClass('fa-pencil').addClass('fa-check')
		  id_exist = $(this).parents("tr").find(".id_sbe_win").val()
    	tag_sbe = $(this).parents("tr").find(".tag_sbe_win").val()
    	name_sbe = $(this).parents("tr").find(".tag_sbe_win option:selected").text()
    	price = $(this).parents("tr").find(".price_sbe_win").val()
    	dataValue = $(this).parents("tr").find(".price_sbe_win").data("value")
    	id_val = $(this).parents("tr").find("#tagging-sbe-win-status").val()
    	$("#nextBtn").prop("disabled",true)

    	if (id_val == '') {
    		$("#tagging-sbe-win-status[data-value='"+dataValue+"']").val("pencil"+dataValue)  
    		activeBtnEdit++
    	}else if (id_val == "pencil"+dataValue) {
    		$(this).parents("tr").find(".btn-edit-tagging-sbe-win").attr("onclick",updateTaggingSbeWin(id_exist,tag_sbe,name_sbe,price,dataValue))
    		$("#tagging-sbe-win-status[data-value='"+dataValue+"']").val("")  
    	}
    	$("#btn-addSBE").prop("disabled",true).attr("data-toggle", "tooltip").attr('title', "You in edit mode!").show()

    	$("#btnSubmitSD").prop("disabled",true)
    	$("#btnRaiseTP").prop("disabled",true)
    })

    function updateTagging(id_exist,product,techno,price,dataValue){
  		$.ajax({
          url: "{{url('/project/updateProductTag')}}",
          type: 'post',
          data: {
          	_token:"{{ csrf_token() }}",
          	id_exist:id_exist,
          	id_product:product,
          	id_techno:techno,
          	price:price,
          	lead_id:window.location.href.split("/")[5]
          },
        success: function()
        {
            Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                	localStorage.setItem("status_tagging", "pencil");
                	$(".select2-customProductSol[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".select2-customTechnologySol[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".new-price-sol[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".btn-edit-tagging[data-value='" + dataValue + "']").removeClass('btn-warning').addClass('btn-primary')
						    	$(".btn-edit-tagging[data-value='" + dataValue + "']").find("i").removeClass('fa-check').addClass('fa-pencil')
						    	$("#btn-addTagging").prop('disabled',false).tooltip('disable')
    							$("#btnSubmitSD").prop("disabled",false)
                }
            })
        }
      })
    }

    function updateTaggingSbe(id_exist,tag_sbe,name_sbe,price,dataValue){
    	$.ajax({
          url: "{{url('/project/updateSbeTag')}}",
          type: 'post',
          data: {
          	_token:"{{ csrf_token() }}",
          	id_exist:id_exist,
          	id_sbe:tag_sbe,
          	name_sbe:name_sbe,
          	price:price,
          	lead_id:window.location.href.split("/")[5]
          },
        success: function()
        {
            Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                	localStorage.setItem("status_tagging_sbe", "pencil");
                	$(".tag_sbe[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".price_sbe[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".btn-edit-tagging-sbe[data-value='" + dataValue + "']").removeClass('btn-warning').addClass('btn-primary')
						    	$(".btn-edit-tagging-sbe[data-value='" + dataValue + "']").find("i").removeClass('fa-check').addClass('fa-pencil')
						    	// $("#btn-addSBE").prop('disabled',false).tooltip('disable')
    							$("#btnSubmitSD").prop("disabled",false)
                }
            })
        }
      })
    }

    function updateTaggingSbeWin(id_exist,tag_sbe,name_sbe,price,dataValue){
    	$.ajax({
          url: "{{url('/project/updateSbeTag')}}",
          type: 'post',
          data: {
          	_token:"{{ csrf_token() }}",
          	id_exist:id_exist,
          	id_sbe:tag_sbe,
          	name_sbe:name_sbe,
          	price:price,
          	lead_id:window.location.href.split("/")[5]
          },
        success: function()
        {
            Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                	$(".tag_sbe_win[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".price_sbe_win[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".btn-edit-tagging-sbe-win[data-value='" + dataValue + "']").removeClass('btn-warning').addClass('btn-primary')
						    	$(".btn-edit-tagging-sbe-win[data-value='" + dataValue + "']").find("i").removeClass('fa-check').addClass('fa-pencil')
						    	// $("#btn-addSBE").prop('disabled',false).tooltip('disable')
						    	activeBtnEdit--
						    	if (activeBtnEdit == 0) {
    								$("#nextBtn").prop("disabled",false)
						    	}
                }
            })
        }
      })
    }

    var id_exist,product,techno,price,dataValue,lead_id = '' 	 

    function updateTaggingWin(id_exist,product,techno,price,dataValue,lead_id){
    	console.log(activeBtnEdit)
  		$.ajax({
          url: "{{url('/project/updateProductTag')}}",
          type: 'post',
          data: {
          	_token:"{{ csrf_token() }}",
          	id_exist:id_exist,
          	id_product:product,
          	id_techno:techno,
          	price:price,
          	lead_id:lead_id
          },
        success: function()
        {
            Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                	localStorage.setItem("status_tagging", "pencil");
                	$(".select2-customProduct[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".select2-customTechnology[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".new-price-win[data-value='" + dataValue + "']").prop("disabled",true)
						    	$(".btn-edit-taggingWin[data-value='" + dataValue + "']").removeClass('btn-warning').addClass('btn-primary')
						    	$(".btn-edit-taggingWin[data-value='" + dataValue + "']").find("i").removeClass('fa-check').addClass('fa-pencil')
						    	activeBtnEdit--
						    	if (activeBtnEdit == 0) {
    								$("#nextBtn").prop("disabled",false)
						    	}
                }
            })
        }
      })
    }  

  	function btnSubmit(data,val){  		
  		if (val == 'SD') {
  			var i = 0
  			var tagProduct = []
	      $('#table-tagging #tbtagging .new-tagging').each(function() {
	        data.append("tagData[tagProduct]["+i+"][tag_price]",$(this).find(".new-price-sol").val().replace(/\D/g, ""))
	        data.append("tagData[tagProduct]["+i+"][tag_product][productTag]",$(this).find('.select2-customProductSol').select2("data")[0].id.substr(1))
	        data.append("tagData[tagProduct]["+i+"][tag_product][productTagText]",$(this).find('.select2-customProductSol').select2("data")[0].text)
	        data.append("tagData[tagProduct]["+i+"][tag_product][techTag]",$(this).find('.select2-customTechnologySol').select2("data")[0].id.substr(1))
	         data.append("tagData[tagProduct]["+i+"][tag_product][techTagText]",$(this).find('.select2-customTechnologySol').select2("data")[0].text)
	        i++
	      });

	      $('#table-tagging-SBE #tbSBE .new-SBE').each(function() {
	        data.append("tagData[tagSBE]["+i+"][price_sbe]",$(this).find(".price_sbe").val().replace(/\D/g, ""))
	        data.append("tagData[tagSBE]["+i+"][tag_sbe]",$(this).find('.tag_sbe').select2("data")[0].id)
	        data.append("tagData[tagSBE]["+i+"][sbeText]",$(this).find('.tag_sbe').select2("data")[0].text)
	        i++
	      });

	      data.append("id",deletedProduct)
	      data.append("id_sbe_delete",deleteSbe)
	      data.append("name_sbe_delete",nameSbe)
	      data.append("price_sbe_delete",priceSbe)
	      data.append("id_exist",idExist)

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
		        url:"{{url('project/update_sd')}}",
		        data:data,
		        contentType: false,
						processData: false,
		        success: function(result){
		          Swal.showLoading()
		          Swal.fire(
		            'Successfully!',
		            'You`re updated Solution Design.',
		            'success'
		          ).then((result) => {
		            if (result.value) {
		              location.reload();
		            }
		          })
		        }
		      })

  		}else{
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
          url:"{{url('project/update_tp')}}",
          data:data,
          contentType: false,
					processData: false,
          success: function(result){
            Swal.showLoading()
            Swal.fire(
              'Successfully!',
              'You`re updated Tender Process.',
              'success'
            ).then((result) => {
              if (result.value) {
                location.reload();
              }
            })
          }
        })  			
  		}  		
  	}

  	function btnResult(lead_id,id_cus,opp_name){
  		$("#formResult").modal('show') 
		  initmoney(); 

  		$("#opp_name_result").html("<b>Opp Name : "+opp_name+"<i></i></b>")

  		$.ajax({
  			type:"GET",
  			url:"{{url('/project/getQuote')}}",
  			data:{
  				id_customer:id_cus
  			},
  			success:function(result){
  				$("#quote_number_final").select2({
	        	data:result.data,
        		dropdownParent: $('#formResult')
	        })
  			}
  		})

  		$('#date_po_result').datepicker({
        autoclose: true,
	      }).on('changeDate', function(date) {

	    	// if (moment(date.date).format("YYYY") < new Date().getFullYear()) {
	    	// 	Swal.fire("<h3>Warning Pembuatan PID!!!</h3>", "<h4>lead dengan tanggal PO tahun lalu (backdate) harap kirim email manual pada Bu Anne untuk pembuatan PID seperti proses manual terdahulu! Dikarenakan semua PID yang melalui sistem hanya di peruntukkan untuk tanggal PO di tahun ini</h4>")

	    	// 	if ($('.date').datepicker('setDate', null)) {
	    	// 		$("#no_po_result").prop( "disabled", true );
	     //  		$("#amount_pid_result").prop( "disabled", true );
	     //  		$("#quote_number_final").prop( "disabled", true );
	     //  		$("#checkbox_result").css("display", "none");
	    	// 	}
	    	// 	console.log($('#date').val())
	    	// }else{
	    		$("#no_po_result").prop( "disabled", false );
	      	$("#amount_pid_result").prop( "disabled", false );
	      	$("#checkbox_result").css("display", "block");
	      	$("#quote_number_final").prop( "disabled", false );
	    	// }
	    });

      $(document).on('input', '.new-price-win', function() {
        grandTotal()
      });

      $(document).on('input', '.price_sbe_win', function() {
        grandTotal()
      });

			$('#result').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
          if (valueSelected == "WIN") {
            document.getElementById("nextBtn").innerHTML = "Next";
            $("#result-win").css("display", "block");
            $("#result-lose").css("display", "none");
            $("#nextBtn").attr('onclick','nextPrev()');
          } 
          else {
            document.getElementById("nextBtn").innerHTML = "Submit";
            $("#result-win").css("display","none");
            $("#result-lose").css("display", "block");
            
            
            $("#nextBtn").attr('onclick','submitBtn(1)');
            $("#alert_result").css("display","none");
          }
        
      })
  	}

  	function submitBtn(){
  		Swal.fire({
        title: 'Please Wait..!',
        html: "<p style='text-align:center;'>It's processing..</p>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
          Swal.showLoading()
        }
      })
      $.ajax({
        type:"POST",
        url:"{{url('/project/updateResult')}}",
        data:{
          update_closing_date:$('#update_closing_date').val(),
          request_id:$("#request_id").is(":checked"),
          date_po:$("#date_po_result").val(),
          amount_pid:$("#amount_pid_result").val(),
          no_po:$("#no_po_result").val(),
          project_type:$("#project_type").val(),
          keterangan:$("#keterangan").val(),
          deal_price_result:$("#deal_price_result").val(),
          result:$("#result").val(),
          // quote_number_final:$("#quote_number_final").val(),
          quote_number_final:$("#quote_number_final").select2("data")[0].text.split("-")[0].trim(""),
          lead_id_result:window.location.href.split("/")[5],
          _token:"{{ csrf_token() }}"
        },
        success: function(){
          Swal.hideLoading()
          Swal.fire({
            title: 'Success!',
            html: "<p style='text-align:center;'>You've set result to "+$("#result").val()+"</p>",
            type: 'success',
            confirmButtonText: 'Reload',
          }).then((result) => {
            $("#formResult").modal('hide')
            location.reload();
          })
        }
      })
    }

  	function nextPrev() {
		    if ($("#update_closing_date").val() == "" || $("#date_po_result").val() == "" || $("#no_po_result").val() == "" || $("#amount_pid_result").val() == "") {
		        var append = "";
		        if ($("#update_closing_date").val() == "") {
		            append = append + " <li > Please fill closing date! </li>"
		            $("#update_closing_date").parent().addClass("has-error has-feedback")
		        } else if ($("#date_po_result").val() == "") {
		            append = append + " <li > Please fill date PO! </li>"
		        } else if ($("#no_po_result").val() == "") {
		            append = append + " <li > Please fill no PO! </li>"
		        } else if ($("#amount_pid_result").val() == "") {
		            append = append + " <li > Please fill amount PO! </li>"
		        }
		        $("#alert_result").css("display", "block");
		        $("#ul-warning-result").empty();
		        $("#ul-warning-result").append(append);
		    } else {
		        $("#alert_result").css("display", "none");
		        $("#prevBtn").attr('onclick', 'prevClick("' + window.location.href.split('/')[5] + '")');

		        if ($('.active-tab').next('.tab').length) {
		            $('.active-tab').removeClass('active-tab')
		                .addClass('non-active-tab')
		                .next('.tab')
		                .addClass('active-tab')
		                .removeClass('non-active-tab');

		            document.getElementById("prevBtn").innerHTML = "Prev";
		            document.getElementById("nextBtn").innerHTML = "Submit";
		        }
		        initmoney();

		        $("#tbtagprice").empty()	

		        $.ajax({
				    	url: "{{url('/project/showTagging')}}",
				      type: "GET",
				      data: {
				          lead_id: window.location.href.split('/')[5],
				      },success: function(result){
				      	$.each(result, function(key,value){
				      		addProductTechInitiate(value.id,value.id_product_tag,value.id_technology_tag,value.price,key)
				      	})
				      	grandTotal()
				      }
				    })

				    $.ajax({
				    	url: "{{url('/project/showSbeTagging')}}",
				      type: "GET",
				      data: {
				          lead_id: window.location.href.split('/')[5],
				      },success: function(result){
				    		i = 0
				    		console.log("tes"+result)
	 							$("#tbtagsbe").empty()	 
				      	$.each(result, function(key,value){
				      		addSbeResultNotEmpty(value.id,value.tag_sbe,value.price_sbe,i)
				      		i++
				      	})
				      	grandTotal()
				      }
				    })

		        $("#addService").click(function() {
		            $("#nextBtn").prop("disabled", false);
		            $(this).hide()

		            var append = ""
		            append = append + "<tr class='new-product new-service'>"
		            append = append + " <td style='vertical-align:middle'>"
		            append = append + " <b>PM Cost</b>"
		            append = append + " </td>"
		            append = append + " <td style='white-space: nowrap'>"
		            append = append + " <div class='input-group'>"
		            append = append + " <span class='input-group-addon' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
		            append = append + " <input data-value='" + i + "' class='money form-control new-price-win' type='text' placeholder='Enter Service Price'>"
		            append = append + " </div>"
		            append = append + " </td>"
		            append = append + " <td class='text-center'>"
		            append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash'>"
		            append = append + " <i class='fa fa-trash'></i>"
		            append = append + " </button>"
		            append = append + " </td>"
		            append = append + "</tr>"

		            $("#tbserviceprice").append(append)
		            i++;
		            initmoney();
		        })

      			$("#nextBtn").attr('onclick','submitBtnWin(1)')
		    }
		}

		$("#addProductTech").click(function() {
			addNewTag()
    })

		function addNewTag(){
  	 	$("#nextBtn").prop("disabled", false);
      $.ajax({
          url: "{{url('/project/getProductTechTagDetail')}}",
          type: "GET",
          data: {
              lead_id: window.location.href.split('/')[5],
          },
          beforeSend: function(){
          	i++;
            var append = ""
            append = append + "<tr class='new-product'>"
            append = append + " <td>"
            append = append + " <select class='form-control select2-customProduct' data-value='" + i + "' id='searchTagsProduct' style='width: 100%!important'></select>"
            append = append + " </td>"
            append = append + " <td>"
            append = append + " <select class='form-control select2-customTechnology' data-value='" + i + "' id='searchTagsTechnology' style='width: 100%!important'></select>"
            append = append + " </td>"
            append = append + " <td style='white-space: nowrap'>"
            append = append + " <div class='input-group'>"
            append = append + " <span class='input-group-addon' style='background-color: #aaa; color:white;font-style: italic;'>Rp.</span>"
            append = append + " <input data-value='" + i + "' class='money form-control new-price-win' type='text' placeholder='Enter Product Price' name='project_budget'>"
            append = append + " </div>"
            append = append + " </td>"
            append = append + " <td class='text-center'>"
            append = append + " <button data-value='"+i+"' type='button' style='width: auto !important;' class='btn btn-sm btn-danger btn-flat btn-trash'>"
            append = append + " <i class='fa fa-trash'></i></button><button type='button' data-value='"+i+"' style='width: auto !important;margin-left:5px' class='btn btn-sm btn-primary btn-flat disabled'><i class='fa fa-pencil'></i></button>"
            append = append + " </td>"
            append = append + "</tr>"

            $("#tbtagprice").append(append)
            initmoney();
          },
          success: function(result) {
              $("#searchTagsProduct[data-value='" + i + "']").empty("");
              $("#searchTagsTechnology[data-value='" + i + "']").empty("");
              var product_tag = result.product_tag;
              var product_tag_selectOption = [];

              var technology_tag = result.technology_tag;
              var technology_tag_selectOption = [];

              $.each(product_tag, function(key, value) {
                  product_tag_selectOption.push(value)
              })

              $.each(technology_tag, function(key, value) {
                  technology_tag_selectOption.push(value)
              })

              var TagProduct = $("#searchTagsProduct[data-value='" + i + "']").select2({
                  dropdownParent: $('#formResult'),
                  placeholder: " Select #Tags#Product",
                  data: product_tag_selectOption,
                  templateSelection: function(selection, container) {
                      return $.parseHTML('<span>' + selection.text + '</span>');
                  }
              })
              var TagProduct = $("#searchTagsTechnology[data-value='" + i + "']").select2({
                  dropdownParent: $('#formResult'),
                  placeholder: " Select #Tags#Technology",
                  data: technology_tag_selectOption,
                  templateSelection: function(selection, container) {
                      return $.parseHTML('<span>' + selection.text + '</span>');
                  }
              })
          }
      })
  	}

		function submitBtnWin(n){  
      var rowCount = $('#tbtagprice tr').length + $('#tbserviceprice tr').length
      console.log(rowCount)
      if (rowCount == 0) {
        Swal.fire({
          title: 'Are you sure?',
          icon: 'warning',
          html: "<p style='text-align:center;'>To change this status of this lead without adding price?</p>",
          confirmButtonText: 'Yes',
          showCancelButton: true,
          cancelButtonText: 'No'
        }).then((result) => {
          if (result.isConfirmed) {
            submitWinStep2(deletedProductWin,deleteSbeWin)
          } else {
            Swal.close()
          }
        })
      } else {
        submitWinStep2(deletedProductWin,deleteSbeWin)
      }       
    }

    function submitWinStep2(id,arr_sbe){
      var emptyProduct = $("#table-product #tbtagprice tr input").filter(function() {
        return !this.value.trim();
      })
      var itemsProduct = emptyProduct.map(function() {
        return this.placeholder;
      }).get();

      var emptyService = $("#table-service #tbserviceprice tr input").filter(function() {
        return !this.value.trim();
      })
      var itemsSerice = emptyService.map(function() {
        return this.placeholder;
      }).get();

      var emptySbe = $("#table-sbe #tbtagsbe tr input").filter(function() {
        return !this.value.trim();
      })
      var itemsSbe = emptySbe.map(function() {
        return this.placeholder;
      }).get();

      if (itemsProduct.includes("Enter Product Price")) {
        Swal.fire({
          title: 'Can`t Submit Result!',
          icon: 'error',
          html: "<p style='text-align:center;'>Please Select Product and fill Price!!</p>",
          confirmButtonText: 'Oke',
        })
      } else if (itemsSerice.includes("Enter Service Price")) {
        Swal.fire({
          title: 'Can`t Submit Result!',
          icon: 'error',
          html: "<p style='text-align:center;'>Please Select Service and fill Price!!</p>",
          confirmButtonText: 'Oke',
        })
      } else if (itemsSbe.includes("Enter Sbe Price")) {
        Swal.fire({
          title: 'Can`t Submit Result!',
          icon: 'error',
          html: "<p style='text-align:center;'>Please Select Sbe and fill Price!!</p>",
          confirmButtonText: 'Oke',
        })
      } else {
      	$("#nextBtn").prop("disabled",true);
      	$("#prevBtn").prop("disabled",true);
        var tagProduct = []
        $('#table-product #tbtagprice .new-product').each(function() {
          tagProduct.push({
            tag_price:$(this).find(".new-price-win").val().replace(/\D/g, ""),
            tag_product:{
              productTag:$(this).find('.select2-customProduct').select2("data")[0].id.substr(1),
              productTagText:$(this).find('.select2-customProduct').select2("data")[0].text,
              techTag:$(this).find('.select2-customTechnology').select2("data")[0].id.substr(1),
              techTagText:$(this).find('.select2-customTechnology').select2("data")[0].text,
            }
          })
        });

        var tagSbe = []
        $('#table-sbe #tbtagsbe .tag-sbe-win').each(function() {
          tagSbe.push({
            tag_price:$(this).find(".price_sbe_win").val(),
            tag_sbe_id:$(this).find('.tag_sbe_win').select2("data")[0].id,
            tag_sbe_text:$(this).find('.tag_sbe_win').select2("data")[0].text,
          })
        });

        var tagService = []
        $('#table-service #tbserviceprice .new-product').each(function() {
          tagService.push({
            tag_price:$(this).find(".new-price-win").val().replace(/\D/g, ""),
            // tag_service:$(this).find('.select2-customService').select2("data")[0].id,
            tag_service:1,
          })
        });

        var tagData = {
          tagProduct:tagProduct,
          tagService:tagService,
          tagSbe:tagSbe,
          id:id,
          arr_sbe:arr_sbe

        }

        Swal.fire({
          title: 'Please Wait..!',
          html: "<p style='text-align:center;'>It's processing..</p>",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          onOpen: () => {
            Swal.showLoading()
          }
        })
        $.ajax({
          type:"POST",
          url:"{{url('/project/updateResult')}}",
          data:{
            update_closing_date:$('#update_closing_date').val(),
            request_id:$("#request_id").is(":checked"),
            date_po:$("#date_po_result").val(),
            amount_pid:$("#amount_pid_result").val(),
            no_po:$("#no_po_result").val(),
            project_type:$("#project_type").val(),
            keterangan:$("#keterangan").val(),
            deal_price_result:$("#deal_price_result").val(),
            result:$("#result").val(),
            quote_number_final:$("#quote_number_final").select2("data")[0].text.split("-")[0].trim(""),
            lead_id_result:window.location.href.split("/")[5],
            // tagsOld:tagsOld,
            // tagsNew:tagsNew,
            tagData:tagData,
            _token:"{{ csrf_token() }}"
          },
          success: function(){
            Swal.hideLoading()
            Swal.fire({
              title: 'Success!',
              html: "<p style='text-align:center;'>You've set result to " + $("#result").val() + "</p>",
              type: 'success',
              confirmButtonText: 'Reload',
            }).then((result) => {
              $("#formResult").modal('hide')
              location.reload();
            })
          }
        })
      }
    }

		function prevClick() {
		    Swal.fire({
		        title: 'Are you sure?',
		        text: "You'll lost your activity in this tab!",
		        icon: 'warning',
		        showCancelButton: true,
		        confirmButtonColor: '#3085d6',
		        cancelButtonColor: '#d33',
		        confirmButtonText: 'Yes!'
		    }).then((result) => {
		        if (result.isConfirmed) {
		            if ($('.active-tab').prev('.tab').length) {
		                $('.active-tab').removeClass('active-tab')
		                    .addClass('non-active-tab')
		                    .prev('.tab')
		                    .addClass('active-tab')
		                    .removeClass('non-active-tab');
		                document.getElementById("nextBtn").innerHTML = "Next";
		                document.getElementById("prevBtn").innerHTML = "Close";
		                $("#prevBtn").attr('onclick', 'closeClick()');
		                $("#nextBtn").attr('onclick', 'nextPrev("' + window.location.href.split('/')[5] + '")');
		            }
		        }
		    })
		}

		function closeClick(){
      $("#formResult").modal("hide"); 
    }

    

    function submitChangeLog(){
    	Swal.fire({
			    title: 'Are you sure?',
			    text: "Submit Change Log Progress",
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
			            url: "{{url('/project/add_changelog_progress')}}",
			            data: {
			                _token: "{{ csrf_token() }}",
			                changelog_lead_id:window.location.href.split("/")[5],
			                changelog_progress:$("#changelog_progress").val(),
			                changelog_date:$('#changelog_date').val()
			            },
			            success: function(result) {
			                Swal.showLoading()
			                Swal.fire(
			                    'Successfully!',
			                    'Progress Change Log Added.',
			                    'success'
			                ).then((result) => {
			                    if (result.value) {
			                    	location.reload()
			                    	$("#modal_changelog_progress").modal('hide')
			                    }
			                })
			            }
			        })
			    }
			})
    }

    $(".date").datepicker({
    	todayHighlight: true,
    	autoclose:true
    })

    $("#datastable").dataTable({
    	"ajax":{
          "type":"GET",
          "url":"{{url('/project/getChangeLog')}}",
          "data":{
          	"lead_id":window.location.href.split("/")[5]
          }
        },
        "columns": [
          { 
          	render: function (data, type, row, meta){
          		return ++meta.row         		
          	}
          },
          { "data": "created_at" },
          { 
          	render : function (data, type, row){
          		var check = row.status.split(", ")
          		if(row.status == 'Update Lead with Amount ' || row.status == 'Create Lead with Amount '){
          			return row.status + '<i class="money">' + new Intl.NumberFormat('id').format(row.submit_price) + '</i>'
          		}else if (row.status == 'Update TP') {
          			return row.status + '-' + 'Submitted Price [<i>'+ new Intl.NumberFormat('id').format(row.submit_price) + '</i>] - Deal Price [<i>'+ new Intl.NumberFormat('id').format(row.deal_price) +'</i>]'
          		}else if (row.progress_date != null) {
          			return row.status + ' - ' + '[' + row.progress_date + ']'
          		}else if ($.isNumeric(String(check[2]).split(" ")[2])){
          			return check[0] + ", " + check[1] + ", " + String(check[2]).split(" ")[0] + " " + String(check[2]).split(" ")[1] + " " + new Intl.NumberFormat('id').format(String(check[2]).split(" ")[2])
          		}else if($.isNumeric(String(check[1]).split(" ")[2])){
          			return check[0] + ", " + String(check[1].split(" ")[0] + " " + check[1].split(" ")[1] + " " + new Intl.NumberFormat('id').format(String(check[1]).split(" ")[2]))
          		}else {
          			//String(check[2]).split(" ")[2]
          			return row.status 

          		}
          	}
          },
          { "data": "name"},
        ],
        "columnDefs": [
        {
			    "targets": 0,
			    "width":"5%"
			  },
			  {
			    "targets": 1,
			    "width":"10%"
			  },
			  {
			    "targets": 2,
			    "width":"65%"
			  },
			  {
			    "targets": 3,
			    "width":"20%"
			  }
			  ],
			  "order":[]

    })

</script>
@endsection