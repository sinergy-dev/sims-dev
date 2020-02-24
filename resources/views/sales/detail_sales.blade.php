@extends('template.template_admin-lte')
@section('content')

<style type="text/css">
  div div ol li{
    font-size: 14px;
  }
  button.btn{
    font-size: 14px;
  }
  .circle-container {
      position: relative;
      width: 17em;
      height: 17em;
      padding: 2.8em;
      border-radius: 50%;
      margin: 0.90em auto 0;
      background: #fff;
      border: 1px double #d5d5da;
      vertical-align:top;
  }
  .circle-container .dot
  {
    height: 25px;
    width: 25px;
    background-color: #939a9b;
    border-radius: 50%;
    display: inline-block;
    border: 3px solid #FFF;
    box-shadow: 0 0 2px #888;
  }

  .inputsp i{
    top: 58px;
  }

  .purchase tr:nth-child(1){
    counter-reset: rowNumber;
    }
  .purchase tr {
        counter-increment: rowNumber;
    }
  .purchase tr td:first-child::before {
        content: counter(rowNumber);
        min-width: 1em;
        margin-left: 1.5em;
        text-align: center;
  }


</style>

  <section class="content-header">
  	<a href="{{url('/project')}}"><button button class="btn btn-s btn-danger pull-left"><i class="fa fa-arrow-left"></i>&nbsp Back</button></a>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><a href="/project">Lead Register</a></li>
      <li class="active">Detail - {{ $tampilkan->lead_id }}</li>
    </ol>
  </section>

  <section class="content">
    <div class="callout callout-success" style="margin-top: 30px!important;background-color:">
	    <h4>(<i class="fa fa-info"></i>) <b>Lead id :</b></h4>
	    <h4>{{$tampilkan->lead_id}} - {{$tampilkan->opp_name}}</h4>
	</div>

    <div class="row">
      <div class="col-md-6">
      	<div class="box box-solid box-default">
      		<div class="box-header">
      			<h3 class="box-title">Status</h3>
      		</div>
	          
	        <div class="box-body">
	          	<div class='circle-container'>
	              <a>
	                <span class="deg315 dot" id="init"></span>
	                <span class="deginitial" id="s_init"><b style="opacity: 0.4">INITIAL</b></span> 
	              </a>
	              <a href='#' class='deg45'><span class="dot" id="open"></span></a>
	              <span class="degopen" id="s_open"><b style="opacity: 0.4">OPEN</b></span>
	              <a href='#' class='deg180'><span class="dot" id="sd"></span></a>
	              <span class="degSD" id="s_sd"><b style="opacity: 0.4">SOLUTION DESIGN</b></span>
	              <a href='#' class='deg225'><span class="dot" id="tp"></span></a>
	              <span class="degTP" id="s_tp"><b style="opacity: 0.4">TENDER PROCESS</b></span>
	              <a href='#' class='deg135'><span class="dot" id="win_lose"></span></a>
	              <span class="degwin" id="s_winlose"><b style="opacity: 0.4">WIN/LOSE</b></span>
	              <div class="step-content">
	              </div>
		        </div>
	    	</div>
        </div>
      </div>

      <div class="col-md-6">
      	<div class="box box-solid box-default">
      		<div class="box-header">
      			<h3 class="box-title">Details</h3>
      		</div>
      		<div class="box-body">
      		  <table class="table table-bordered">
      		  	<tr>
      		  		<th>Owner</th>
      		  		<td>{{$tampilkan->name}}</td>
      		  	</tr>
      		  	<tr>
      		  		<th>Customer</th>
      		  		<td>{{$tampilkan->customer_legal_name}}</td>
      		  	</tr>
      		  	<tr>
      		  		<th>Opty Name</th>
      		  		<td>{{$tampilkan->opp_name}}</td>
      		  	</tr>
      		  	<tr>
      		  		<th>Current Presales</th>
      		  		<td>    
      		  		@if($tampilkan_com->id_company == '1')
		              <h5>{{$tampilkans->name}}</h5>
		            @else
		            <h5>Presales</h5>
		            @endif
		        	</td>
      		  	</tr>
      		  	<tr>
      		  		<th>Amount</th>
      		  		@if($tampilkan->deal_price == null)
      		  		<td>Rp <b class="money">{{ $tampilkan->amount }}</b></td>
      		  		@else
      		  		<td>Rp <b class="money">{{ $tampilkan->deal_price }}</b></td>
      		  		@endif
      		  	</tr>
      		  	<tr>
      		  		<th>Closing date</th>
      		  		<td>{{ $tampilkan->closing_date }}</td>
      		  	</tr>
      		  </table>
	         <!--  <div class="">
	            <h5 class="pull-right">Owner : <i>{{$tampilkan->name}}</i></h5>
	            <h5 class="pull-left">{{ $tampilkan->customer_legal_name }}</h5><br><br>
	            <h5>{{$tampilkan->opp_name}}</h5>
	          </div>
	          <div class="">
	          	@if(Auth::User()->id_company == '1')
	              @if($tampilkan_com->id_company == '1')
	                <h5>Current Presales : <i>{{$tampilkans->name}}</i></h5>
	              @elseif($tampilkan_com->id_company == '2')
	              <h5>Current Presales : <i>Presales</i></h5>
	              @endif
	            @else
	            <h5>Current Presales : <i>Presales</i></h5>
	            @endif
	            @if(Auth::User()->id_position == 'ENGINEER STAFF')
	            <h5>Current Engineer : <i>{{$current_eng->name}}</i></h5>
	            @elseif(Auth::User()->id_position == 'ENGINEER MANAGER')
	            <h5>Current Engineer : <i>{{$current_eng->name}}</i></h5>
	            @endif
	            <h6 >Amount : Rp <b class="money">{{ $tampilkan->amount }}</b></h6>
	            <h6 >Closing Date : {{ $tampilkan->closing_date }}</h6>
	          </div> -->
	        </div>
      	</div>
      	
      </div>
    </div>

    	@if(Auth::User()->id_division != 'PMO' &&  Auth::User()->id_position != 'ENGINEER MANAGER' && Auth::User()->id_position != 'ENGINEER STAFF')
        <div class="row margin-top">
	      <div class="col-md-6">
	            	<div class="box box-solid box-default">
	              <div class="box-header with-border">
	                <h3 class="box-title">Solution Design</h3>
	                  @if($tampilkanc->result == 'SD' && Auth::User()->id_division == 'SALES')
	                    <div class="pull-right">
	                      <button type="button" class="btn btn-md btn-success float-right margin-bottom" data-toggle="modal" data-target="#formResult">Result</button>
	                    </div>
	                  @endif
	              </div>

	              <div class="box-body">
	              @csrf
	              <form action="{{ url('update_sd', $tampilkans->lead_id)}}" method="POST">
	                {!! csrf_field() !!}
	              @if(Auth::User()->id_company == '1')
	                @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && $tampilkans->status != 'closed' && Auth::User()->name == $tampilkans->name)
	                <fieldset>
	                @elseif(Auth::User()->id_division == 'TECHNICAL' && $tampilkans->status != 'closed')
	                <fieldset>
	                @elseif(Auth::User()->id_position == 'DIRECTOR' && $tampilkans->status != 'closed')
	                <fieldset>
	                @elseif(Auth::User()->id_division == 'TECHNICAL PRESALES' && $tampilkans->status != 'closed' && Auth::User()->id_position == 'MANAGER')
	                <fieldset>
	                @else
	                <fieldset disabled>
	                @endif
	              @elseif(Auth::User()->id_company == '2')
	                @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && $tampilkans->status != 'closed' && Auth::User()->name == $tampilkans->name)
	                <fieldset>
	                @elseif(Auth::User()->id_division == 'TECHNICAL' && $tampilkans->status != 'closed')
	                <fieldset>
	                @elseif(Auth::User()->id_position == 'DIRECTOR' && $tampilkans->status != 'closed')
	                <fieldset>
	                @else
	                <fieldset disabled>
	                @endif
	        		<!-- 
	                <fieldset disabled> -->
		              @endif
		                <input type="" name="lead_id" id="lead_id" value="{{$tampilkans->lead_id}}" hidden>
		                <div class="form-group margin-left-right">
			                  <label for="assesment">-- Assessment --</label>
			                  <input type="" name="assesment_before" id="assesment_before" value="{{$tampilkans->assessment}}" hidden>
			                  <input type="" name="assesment_date_before" id="assesment_date_before" value="{{$tampilkans->assessment_date}}" hidden>
			                  <textarea class="form-control" type="text" aria-describedby="emailHelp" placeholder="Enter assesment" name="assesment" id="assesment" >{{$tampilkans->assessment}}</textarea>
			                 <!--  <input type="checkbox" class="float-right" onclick="var input = document.getElementById('assesment'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}"/> -->
			                  @if($tampilkans->assessment_date == '')
			                  <h6>Last Date Updated : -- / -- / ---- </h6>
			                  @else
			                  <h6>Last Date Updated : {{date('d / m / Y', strtotime($tampilkans->assessment_date))}}</h6>
			                  @endif
			                  <h6>Last Time Updated : {!!substr($tampilkans->assessment_date,11)!!}</h6>
		                </div>

		                <div class="form-group margin-left-right">
			                 <label for="proof of value">-- Proposed Design--</label>
			                 <input type="" name="pd_before" id="pd_before" value="{{$tampilkans->pd}}" hidden>
			                 <input type="" name="pd_date_before" id="pd_date_before" value="{{$tampilkans->pd_date}}" hidden>
		                   	 <textarea class="form-control" type="email" aria-describedby="" placeholder="Enter Propossed Design" name="propossed_design"  id="propossed_design">{{$tampilkans->pd}}</textarea>
			                 <!--  <input type="checkbox" class="float-right" onclick="var input = document.getElementById('propossed_design'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" /> -->
			                 @if($tampilkans->pd_date == '')
			                 <h6>Last Date Updated : -- / -- / ---- </h6>
			                 @else
			                 <h6>Last Date Updated : {{date('d / m / Y', strtotime($tampilkans->pd_date))}}</h6>
			                 @endif
			                 <h6>Last Time Updated : {!!substr($tampilkans->pd_date,11)!!}</h6>
			            </div>

			            <div class="form-group margin-left-right">
			                <label for="propossed_design" class="margin-top-form">--Proof Of Value --</label>
			                <input type="" name="pov_before" id="pov_before" value="{{$tampilkans->pov}}" hidden>
			                <input type="" name="pov_date_before" id="pov_date_before" value="{{$tampilkans->pov_date}}" hidden>
			                <textarea class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Proof Of Value" name="pov"  id="pov" >{{$tampilkans->pov}}</textarea>
			                 <!--  <input type="checkbox" class="float-right" onclick="var input = document.getElementById('pov'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" /> -->
			                @if($tampilkans->pov_date == '')
			                <h6>Last Date Updated : -- / -- / ---- </h6>
			                @else
			                <h6>Last Date Updated : {{date('d / m / Y', strtotime($tampilkans->pov_date))}}</h6>
			                @endif
			                <h6>Last Time Updated : {!!substr($tampilkans->pov_date,11)!!}</h6>
			                </div>

			                <div class="form-group margin-left-right">
			                  @if ($message = Session::get('warning'))
			                  <div class="alert alert-warning alert-block">
			                    <button type="button" class="close" data-dismiss="alert">×</button> 
			                    <strong>{{ $message }}</strong>
			                  </div>
			                  @endif
			                </div>

			                <div class="form-group margin-left-right inputWithIcon inputIconBg">
			                  <label for="project_budget" class="margin-top-form">-- Project Budget --</label>
			                  <input type="text" name="project_budget_before" id="project_budget_before" value="{{$tampilkans->pb}}" hidden>
			                  <input type="text" name="amount_check" id="amount_check" value="{{$tampilkan->amount}}" hidden>
			                  <input class="form-control float-left money" type="text" aria-describedby="emailHelp" placeholder="Enter Project Budget" name="project_budget"  id="project_budget" value="{{$tampilkans->pb}}" />
			                  <i class="" style="margin-top: -7px" aria-hidden="true">Rp.</i>
			                 <!--  <input type="checkbox" class="float-right" onclick="var input = document.getElementById('project_budget'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" /> -->
		                </div>

		                <div class="form-group margin-left-right">
		                  <label for="priority" class="margin-top-form">-- Priority --</label>
		                  
		                  <select class="form-control float-left" id="priority"  name="priority">
		                    @if($tampilkans->priority == 'Contribute')
		                    <option value="">-- Choose Priority --</option>
		                    <option value="Contribute" selected>Contribute</option>
		                    <option value="Fight" >Fight</option>
		                    <option value="Foot Print" >Foot Print</option>
		                    <option value="Guided" >Guided</option>
		                    @elseif($tampilkans->priority == 'Fight')
		                    <option value="">-- Choose Priority --</option>
		                    <option value="Contribute">Contribute</option>
		                    <option value="Fight" selected>Fight</option>
		                    <option value="Foot Print" >Foot Print</option>
		                    <option value="Guided" >Guided</option>
		                    @elseif($tampilkans->priority == 'Foot Print')
		                    <option value="">-- Choose Priority --</option>
		                    <option value="Contribute" >Contribute</option>
		                    <option value="Fight" >Fight</option>
		                    <option value="Foot Print" selected>Foot Print</option>
		                    <option value="Guided" >Guided</option>
		                    @elseif($tampilkans->priority == 'Guided')
		                    <option value="">-- Choose Priority --</option>
		                    <option value="Contribute" >Contribute</option>
		                    <option value="Fight" >Fight</option>
		                    <option value="Foot Print" >Foot Print</option>
		                    <option value="Guided" selected>Guided</option>
		                    @else
		                    <option value="" >-- Choose Priority --</option>
		                    <option value="Contribute" >Contribute</option>
		                    <option value="Fight" >Fight</option>
		                    <option value="Foot Print" >Foot Print</option>
		                    <option value="Guided" >Guided</option>
		                    @endif
		                  </select>
		                </div>

		                <div class="form-group margin-left-right ">
		                  <label for="proyek_size" class="margin-top-form">-- Project size --</label>
		                    <select class="form-control float-left margin-bottom" id="proyek_size"  name="proyek_size" >

		                    @if($tampilkans->project_size == 'Small')
		                    <option value="">-- Choose Project Size --</option>
		                    <option value="Small" selected>Small</option>
		                    <option value="Medium" >Medium</option>
		                    <option value="Advance" >Advance</option>

		                    @elseif($tampilkans->project_size == 'Medium')
		                    <option value="">-- Choose Project Size --</option>
		                    <option value="Small">Small</option>
		                    <option value="Medium" selected>Medium</option>
		                    <option value="Advance" >Advance</option>
		                    @elseif($tampilkans->project_size == 'Advance')
		                    <option value="">-- Choose Project Size --</option>
		                    <option value="Small">Small</option>
		                    <option value="Medium">Medium</option>
		                    <option value="Advance" selected>Advance</option>

		                    @else
		                    <option value="">-- Choose Project Size --</option>
		                    <option value="Small">Small</option>
		                    <option value="Medium">Medium</option>
		                    <option value="Advance">Advance</option>

		                    @endif
		                    </select>
		                </div>

		                <div class="margin-left-right margin-top">
		                  @if($tampilkans->status != 'closed' && Auth::User()->id_division != 'SALES' && Auth::User()->name == $tampilkans->name)
		                  <button class="btn btn-md btn-sd btn-primary float-left margin-bottom" type="submit">Submit</button>
		                  @elseif(Auth::User()->id_division == 'TECHNICAL' && $tampilkans->status != 'closed')
		                  <button class="btn btn-md btn-sd btn-primary float-left margin-bottom" type="submit">Submit</button>
		                  @elseif(Auth::User()->id_position == 'DIRECTOR' && $tampilkans->status != 'closed')
		                  <button class="btn btn-md btn-sd btn-primary float-left margin-bottom" type="submit">Submit</button>
		                  @endif
		              	  </form>
		                  @if($tampilkans->status != 'closed' && Auth::User()->id_division != 'SALES' && Auth::User()->name == $tampilkans->name)
		                  <!-- <form action="{{url('raise_to_tender')}}" method="POST"> -->
		                     <!-- {!! csrf_field() !!} -->
		    			  <!--                <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
		                    <button class="btn btn-md btn-sd btn-success float-right margin-bottom" type="button" data-target="#modal_raise" data-toggle="modal">Raise To Tender</button> -->
		                  <!-- </form> -->
		                    <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
		                    <button class="btn btn-md btn-sd btn-success float-right margin-bottom" type="button" data-target="#modal_raise" data-toggle="modal">Raise To Tender</button>
		                  @elseif(Auth::User()->id_division == 'TECHNICAL' && $tampilkans->status != 'closed')
		                    <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
		                    <button class="btn btn-md btn-sd btn-success float-right margin-bottom" type="button" data-target="#modal_raise" data-toggle="modal">Raise To Tender</button>
		                  @elseif(Auth::User()->id_position == 'DIRECTOR' && $tampilkans->status != 'closed')
		                    <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
		                    <button class="btn btn-md btn-sd btn-success float-right margin-bottom" type="button" data-target="#modal_raise" data-toggle="modal">Raise To Tender</button>
		                  @elseif(Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' && $tampilkans->status != 'closed')
		                    <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
		                    <button class="btn btn-md btn-sd btn-success float-right margin-bottom" type="button" data-target="#modal_raise" data-toggle="modal">Raise To Tender</button>
		                  @endif
		                </div>

		                </fieldset>
		              </div>
		            </div>
	      </div>

          <div class="col-md-6">
            <div class="box box-solid box-default">
              <div class="box-header with-border">
                <h3 class="box-title">Tender Process</h3>
              </div>

              <div class="box-body">
                @csrf
              <form action="{{ url('update_tp', $tampilkanc->lead_id)}}"  method="POST" >
                {!! csrf_field() !!}
              @if(Auth::User()->id_company == '1')
                @if(Auth::User()->id_division == 'SALES' && $tampilkanc->status == 'ready' && Auth::User()->nik == $tampilkanc->nik || Auth::User()->id_division == 'SALES' && $tampilkanc->result == 'HOLD' && Auth::User()->nik == $tampilkanc->nik || Auth::User()->id_division == 'SALES' && $tampilkanc->result == 'WIN' && Auth::User()->nik == $tampilkanc->nik)
                <fieldset>
                @elseif(Auth::User()->id_division == 'TECHNICAL' || $tampilkanc->status == 'ready')
                <fieldset>
                @elseif(Auth::User()->id_position == 'DIRECTOR' || $tampilkanc->status == 'ready')
                <fieldset>
                @elseif(Auth::User()->id_division == 'SALES' && $tampilkanc->status == 'closed')
                <fieldset disabled>
                @else
                <fieldset disabled>
                @endif
              @else
                @if(Auth::User()->id_division == 'SALES' && Auth::User()->nik == $tampilkanc->nik || Auth::User()->id_division == 'SALES' && Auth::User()->id_position == 'MANAGER')
                <fieldset>
                @elseif(Auth::User()->id_division == 'TECHNICAL' || $tampilkanc->status == 'ready')
                <fieldset>
                @elseif(Auth::User()->id_position == 'DIRECTOR' || $tampilkanc->status == 'ready')
                <fieldset>
                @else
                <fieldset disabled>
                @endif
              @endif
                <input type="" name="lead_id" id="lead_id" value="{{$tampilkanc->lead_id}}" hidden>
                <div class="form-group margin-left-right">
                  <label for="assesment">--No Doc. Lelang--</label>
                  <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter No Doc. Lelang" name="lelang" id="lelang" onkeypress="" value="{{$tampilkanc->auction_number}}" />
                </div>
                <div class="form-group margin-left-right inputsp inputIconBg">
                  <label for="submitted price" class="margin-top-form">--Submitted Price--</label>
                  <input type="text" name="submit_price_before" class="money" id="submit_price_before" value="{{$tampilkanc->submit_price}}" hidden>
                  <input type="text" name="amount_before" id="amount_before" value="{{$tampilkanc->amount}}" hidden>
                  <input class="form-control float-left money" type="text" aria-describedby="" placeholder="Enter Submitted Price" name="submit_price" id="submit_price" value="{{$tampilkanc->submit_price}}" />
                  <i class="" aria-hidden="true">Rp.</i>
                </div>
                <div class="form-group margin-left-right ">
                  <label for="proyek_class" class="margin-top-form">-- Project Class --</label>
                  @if($tampilkanc->project_class == 'multiyears')
                    <select class="form-control float-left" id="project_class" name="project_class" >
                      <option value="">-- Choose Project Class --</option>
                      <option value="multiyears" selected>Multiyears</option>
                      <option value="blanket">Blanket</option>
                      <option value="normal">Normal</option>
                    </select>
                  @elseif($tampilkanc->project_class == 'blanket')
                    <select class="form-control float-left" id="project_class" name="project_class" >
                      <option value="">-- Choose Project Class --</option>
                      <option value="multiyears">Multiyears</option>
                      <option value="blanket" selected>Blanket</option>
                      <option value="normal">Normal</option>
                    </select>
                  @elseif($tampilkanc->project_class == 'normal')
                    <select class="form-control float-left" id="project_class" name="project_class" >
                      <option value="">-- Choose Project Class --</option>
                      <option value="multiyears">Multiyears</option>
                      <option value="blanket">Blanket</option>
                      <option value="normal" selected>Normal</option>
                    </select>
                  @else
                    <select class="form-control float-left" id="project_class" name="project_class" >
                      <option value="">-- Choose Project Class --</option>
                      <option value="multiyears">Multiyears</option>
                      <option value="blanket" >Blanket</option>
                      <option value="normal" >Normal</option>
                    </select>
                  @endif
                </div>
                <div class="form-group margin-top">
                  @if($tampilkanc->project_class == 'multiyears' || $tampilkanc->project_class == 'blanket')
                    <div class="form-group margin-left-right" id="tahun_jumlah">
                  @else
                    <div class="form-group margin-left-right" id="tahun_jumlah" style="display: none">
                  @endif
                    <label class="margin-top-form">--Jumlah Tahun--</label>
                    @if($tampilkanc->jumlah_tahun == '1')
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    @elseif($tampilkanc->jumlah_tahun == '2')
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    @elseif($tampilkanc->jumlah_tahun == '3')
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                      @elseif($tampilkanc->jumlah_tahun == '4')
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4" selected>4</option>
                        <option value="5">5</option>
                      </select>
                      @elseif($tampilkanc->jumlah_tahun == '5')
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5" selected>5</option>
                      </select>
                    @else
                      <select class="form-control float-left jumlah_tahun" name="jumlah_tahun" id="jumlah_tahun">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    @endif
                  </div>
                  @if($tampilkanc->project_class == 'multiyears' || $tampilkanc->project_class == 'blanket')
                    <div class="form-group margin-left-right inputsp inputIconBg" id="total_price_deal">
                  @else
                    <div class="form-group margin-left-right inputsp inputIconBg" id="total_price_deal" style="display: none">
                  @endif
                      <label class="margin-top-form">--Deal Price Total--</label>
                      <input class="form-control float-left money" type="text" aria-describedby="" placeholder="Enter Deal Price Total" name="deal_price_total" id="deal_price_total" value="{{$tampilkanc->deal_price_total}}" />
                      <i class="" aria-hidden="true">Rp.</i>
                    </div>
                </div>
                <input type="text" name="amount_cek_tp" value="{{$tampilkanc->amount}}" hidden>
                <div class="form-group margin-left-right inputsp inputIconBg">
                  <label class="margin-top-form">--Deal Price--</label>
                  <input class="form-control float-left money" type="text" aria-describedby="" placeholder="Enter Deal Price" name="deal_price" id="deal_price" value="{{$tampilkanc->deal_price}}" />
                  <i class="" aria-hidden="true">Rp.</i>
                  <div class="form-group">
                  @if ($message = Session::get('submit-price'))
                  <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong style="">{{ $message }}</strong>
                  </div>
                  @endif
                  </div>
                </div>
                <div class="form-group margin-left-right  percentageIcon inputIconBg">
                  <label for="win probability" class="margin-top-form">--Win Probability--</label>
                  <!-- <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Win Probability" name="win_prob" id="win_prob" value="{{$tampilkanc->win_prob}}" maxlength="3"/>
                  <i class="" aria-hidden="true">%</i> -->
                  @if($tampilkanc->win_prob == 'HIGH')
                  <select class="form-control float-left" id="win_prob"  name="win_prob" >
                    <option value="">-- Choose Win Probability --</option>
                    <option value="HIGH" selected>HIGH</option>
                    <option value="MEDIUM" >MEDIUM</option>
                    <option value="LOW" >LOW</option>
                  </select>
                  @elseif($tampilkanc->win_prob == 'MEDIUM')
                   <select class="form-control float-left" id="win_prob"  name="win_prob" >
                    <option value="">-- Choose Win Probability --</option>
                    <option value="HIGH" >HIGH</option>
                    <option value="MEDIUM" selected>MEDIUM</option>
                    <option value="LOW" >LOW</option>
                  </select>
                  @elseif($tampilkanc->win_prob == 'LOW')
                   <select class="form-control float-left" id="win_prob"  name="win_prob" >
                    <option value="">-- Choose Win Probability --</option>
                    <option value="HIGH" >HIGH</option>
                    <option value="MEDIUM" >MEDIUM</option>
                    <option value="LOW" selected>LOW</option>
                  </select>
                  @else
                   <select class="form-control float-left" id="win_prob"  name="win_prob" >
                    <option value="">-- Choose Win Probability --</option>
                    <option value="HIGH" >HIGH</option>
                    <option value="MEDIUM" >MEDIUM</option>
                    <option value="LOW" >LOW</option>
                  </select>
                  @endif
                </div>
                 <div class="form-group margin-left-right">
                  <label for="project_name" class="margin-top-form">--Project Name--</label>
                  <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Project Name" name="project_name" id="project_name" value="{{$tampilkanc->project_name}}"/>
                </div>
                <div class="form-group margin-left-right">
                  <label for="date" class="margin-top-form">--Submit Date--</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control float-left hidden" type="date" aria-describedby="emailHelp" placeholder="Enter Submit Date" name="submit_date"  id="submit_date_before" value="{{$tampilkanc->submit_date}}" />
                    <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Submit Date" name="submit_date"  id="submit_date" value="{{$tampilkanc->submit_date}}"/>
                  </div>
                  @if(Auth::User()->email == 'tech@sinergy.co.id')
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="submit_date2" id="submit_date2">
                  </div>
                  @endif
                </div>
                <!-- <div class="form-group margin-left-right  percentageIcon inputIconBg">
                  <label for="assigned by" class="margin-top-form">--Assigned By--</label>
                  @if($tampilkanc->assigned_by == 'DIR')
                  <select class="form-control float-left" id="assigned_by"  name="assigned_by" >
                    <option value="">-- Assigned By --</option>
                    <option value="DIR" selected>DIR</option>
                    <option value="AM" >AM</option>
                  </select>
                  @elseif($tampilkanc->assigned_by == 'AM')
                  <select class="form-control float-left" id="assigned_by"  name="assigned_by" >
                    <option value="">-- Assigned By --</option>
                    <option value="DIR">DIR</option>
                    <option value="AM" selected>AM</option>
                  </select>
                  @else
                  <select class="form-control float-left" id="assigned_by"  name="assigned_by" >
                    <option value="">-- Assigned By --</option>
                    <option value="DIR">DIR</option>
                    <option value="AM" >AM</option>
                  </select>
                  @endif
                </div> -->

                <div class="form-group margin-left-right">
                  @if ($message = Session::get('warning-quote'))
                  <div class="alert alert-warning alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
                  </div>
                  @endif
                </div>

                <div class="form-group margin-left-right">
                  <label for="quote number" class="margin-top-form">--Quote Number--</label>

                  @foreach($get_quote_number as $gqb)
                    <input type="text" id="get_quote_number" name="get_quote_number" value="{{$gqb->id_quote}}" hidden>
                  @endforeach

                  <!-- <select class="form-control float-left margin-bottom" id="quote_number" name="quote_number">
                    <option value="">-- Select Quote Number --</option>
                    @foreach($quote as $data)
                    @if($data->status == 'F')
                      <option 
                      value="{{$data->id_quote}}"
                        @if($data->id_quote === $tampilkanc->quote_number)
                          selected
                        @endif
                      > 
                      {{$data->quote_number}}
                     </option>
                     @endif
                     @if($data->status == 'T')
                      <option disabled 
                      value="{{$data->id_quote}}"
                        @if($data->id_quote === $tampilkanc->quote_number)
                          selected
                        @endif
                      > 
                      {{$data->quote_number}} "This Quote has Selected"
                     </option>
                     @endif
                    @endforeach
                  </select> -->
                  <input class="form-control float-left" type="text" aria-describedby="emailHelp" placeholder="Enter Quote Number" name="quote_number" id="quote_number" value="{{$tampilkanc->quote_number2}}"/>
                  <input type="quote_before" id="quote_before" name="quote_before" value="{{$q_num->quote_number}}" hidden>
                </div>

                <br>
                
                <div class="margin-left-right margin-top">
                  @if($tampilkanc->status != 'closed' && Auth::User()->id_division == 'SALES' || $tampilkanc->result == 'HOLD' && Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'SALES' && $tampilkanc->result == 'WIN' && Auth::User()->nik == $tampilkanc->nik)
                  <button type="submit" class="btn btn-md btn-primary  margin-bottom" >Submit</button>
                  @elseif(Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  <button type="submit" class="btn btn-md btn-primary  margin-bottom" >Submit</button>
                  @elseif($tampilkanc->status != 'closed' && $tampilkan->name == 'Presales')
                  <button type="submit" class="btn btn-md btn-primary  margin-bottom" >Submit</button>
                  @endif
                  @if($tampilkanc->status != 'closed' && Auth::User()->id_division == 'SALES' || $tampilkanc->result == 'HOLD' && Auth::User()->id_division == 'SALES')
                  <button type="button" class="btn btn-md btn-success float-right margin-bottom" data-toggle="modal" data-target="#formResult">Result</button>
                  @elseif(Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  <button type="button" class="btn btn-md btn-success float-right margin-bottom" data-toggle="modal" data-target="#formResult">Result</button>
                  @elseif($tampilkanc->status != 'closed' && $tampilkan->name == 'Presales')
                  <button type="button" class="btn btn-md btn-success float-right margin-bottom" data-toggle="modal" data-target="#formResult">Result</button>
                  @endif
                </div>
              </form>
              </div>
            </div>
          </div>

          <div class="col-md-6">
          		<!--Contribute-->
	            <div class="box box-body">
	              <table class="table table-bordered" id="data_Table" width="100%" cellspacing="0">
	                <tr>
	                  <div for="assessment" style="background-color: blue">
	                    <div class="box box-header with-border">
	                      <h3 class="box-title">As Contribute</h3>
	                      @if(Auth::User()->id_division == 'TECHNICAL PRESALES' && $tampilkans->status != 'closed' && Auth::User()->name == $tampilkans->name)
	                        <button class="btn btn-xs btn-primary margin-bottom float-right" style="width: 100px" id="btn_add_sales" data-target="#contributeModal" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
	                      @elseif(Auth::User()->id_division == 'TECHNICAL' && $tampilkans->status != 'closed')
	                        <button class="btn btn-xs btn-primary margin-bottom float-right" style="width: 100px" id="btn_add_sales" data-target="#contributeModal" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
	                      @elseif(Auth::User()->id_position == 'DIRECTOR' && $tampilkans->status != 'closed')
	                        <button class="btn btn-xs btn-primary margin-bottom float-right" style="width: 100px" id="btn_add_sales" data-target="#contributeModal" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
	                      </div>
	                    
	                    @endif
	                  </div>
	                </tr>
	                 @foreach($tampilkana as $data)
	                 @if($data->name != $sd_id->name)
	                  <tr>
	                    <td>
	                      <i class="fa fa-user"></i>&nbsp {{ $data->name }}
	                      @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES' || Auth::User()->name == $tampilkans->name)  
	                      <a href="{{ url('delete_contribute_sd?id_sd='. $data->id_sd) }}"><button class="transparant pull-right" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-times fa-lg" style="color: red"></i></button></a>
	                      @endif
	                    </td>
	                  </tr>
	                 @endif
	                 @endforeach
	              </table>
	            </div>
          </div>
    	@endif


        <div class="col-md-6">
        </div>

        @if(Auth::User()->id_division == 'PMO' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
          <div class="col-md-12">
          <div class="card mb-3" style="margin-top: 20px;">
            <div class="card-header">
               <i class="fa fa-table"></i>&nbsp<b>PMO Table</b>
                 @if(Auth::User()->name == $pmo_id->name || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
                  <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspProgress</button>
                 @endif
                 @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
                  <button class="btn btn-primary pull-right" id="" data-target="#formResult2" data-toggle="modal"><i class="fa fa-circle-o-notch"></i>&nbspStatus</button>
                 @endif
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="table_pmo" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody id="products-list" name="products-list">
                      @foreach($tampilkan_progress as $data)
                      <tr>
                        <td>{{$data->tanggal}}</td>
                        <td>{{$data->ket}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
          </div>

          <div class="col-md-6">
          </div>

          <div class="col-md-12">
	           <div class="col-md-6">
	              <!--Contribute-->
	            <div class="card-mb-3">
	                <table  class="table table-bordered" id="data_Table" width="100%" cellspacing="0">
		                <tr>
		                  <div for="assessment" style="background-color: blue">
		                    <b class="float-left"><legend>Contribute</legend></b>
		                    @if(Auth::User()->name == $pmo_id->name || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
		                    <button class="btn btn-primary-sd margin-bottom float-right" id="btn_add_sales" data-target="#contributeModalPMO" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
		                    @endif
		                  </div>
		                </tr>
		                @foreach($pmo_contribute as $data)
		                    @if($data->name != $pmo_id->name)
		                    <tr>
		                      <td>
		                        <i class="fa fa-user"></i>&nbsp{{ $data->name }}
		                        @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' || Auth::User()->name == $pmo_id->name)  
		                        <a href="{{ url('delete_contribute_pmo', $data->id_pmo) }}"><button class="transparant pull-right" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-times fa-lg" style="color: red"></i></button>
		                        @endif
		                      </td>
		                    </tr>
		                    @endif
		                @endforeach
	                </table>
	            </div>
	           </div>
          </div>
        @endif

        @if(Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'ENGINEER STAFF' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v')
          <div class="col-md-12">
          <div class="card mb-3" style="margin-top: 20px;">
          <div class="card-header">
             <i class="fa fa-table"></i>&nbsp<b>Engineer Table</b>
             @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_territory == 'DPG')
              @if(Auth::User()->id_position == 'ENGINEER MANAGER')
              <button class="btn btn-warning pull-right" style="width: 125px" data-target="#engineer_progress" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspProgress</button>
              @elseif(Auth::User()->id_position == 'ENGINEER STAFF')
                @if(Auth::User()->name == $current_eng->name)
                <button class="btn btn-warning pull-right" style="width: 125px" data-target="#engineer_progress" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspProgress</button>
                @endif
              @endif
             @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v')
             <button class="btn btn-warning pull-right" style="width: 125px" data-target="#engineer_progress" data-toggle="modal"><i class="fa fa-spinner" ></i>&nbspProgress</button>
             <button class="btn btn-primary pull-right" id="" data-target="#formResult3" data-toggle="modal"><i class="fa fa-circle-o-notch"></i>&nbspStatus</button>
             @endif
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="table_pmo" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Lead ID</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($tampilkan_progress_engineer as $data)
                    <tr>
                      <td>{{$data->lead_id}}</td>
                      <td>{{$data->ket}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-md-6">
              <!--Contribute-->
            <div class="card-mb-3">
              <table  class="table table-bordered" id="data_Table" width="100%" cellspacing="0">
                <tr>
                  <div for="assessment" style="background-color: blue">
                    <b class="float-left"><legend>Contribute</legend></b>
                    @if(Auth::User()->name == $engineer_id->name || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
                    <button class="btn btn-primary-sd margin-bottom float-right" id="btn_add_sales" data-target="#contributeModalEngineer" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Contribute</button>
                    @endif
                  </div>
                </tr>
                @foreach($engineer_contribute as $data)
                   @if($data->name != $engineer_id->name)
                    <tr>
                      <td>
                        <i class="fa fa-user"></i>&nbsp{{ $data->name }}
                        @if(Auth::User()->id_position == 'ENGINEER MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->name == $engineer_id->name)  
                        <a href="{{ url('delete_contribute_engineer',$data->id_engineer)}}"><button class="transparant pull-right" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"><i class="fa fa-times fa-lg" style="color: red"></i></button></a>
                        @endif
                      </td>
                    </tr>
                  @endif
                @endforeach
              </table>
            </div>
          </div>
          </div>
          @endif

          
	    <div class="col-md-12">
	          @if($tampilkanc->project_class != 'blanket')
	          <tooltip title="Please Submit TP For Fill this Form!" style="font-size: 14px" placement="top">
	          <fieldset disabled="">
	          <div class="box" id="purchase-order" style="display: none">
	          </tooltip>
	          @else
	          <fieldset >
			      <div class="box" id="purchase-order">
			          @endif
				      <div class="box-header with-border">
				          <h3 class="box-title">Purchase Order Customer</h3>
				            <div class="box-tools pull-right">
				              @if($tampilkanc->project_class != 'blanket')
				              <button type="button" class="btn btn-primary" style="width: 150px" data-target="#modal_add_po" data-toggle="modal"><i class="fa fa-plus"></i> Purchase Order</button>
				              </button>
				              @else
				              <button type="button" class="btn btn-primary" style="width: 150px" disabled><i class="fa fa-plus"></i> Purchase Order</button>
				              </button>
				              @endif
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
					              @foreach($tampilkan_po as $data)
					              <tr>
					                <td></td>
					                <td><center>{{$data->date}}</center></td>
					                <td><center>{{$data->no_po}}</center></td>
					                <td class="money"><center>{{$data->nominal}}</center></td>
					                <td><center>{{$data->note}}</center></td>
					                <td><center><button class="btn btn-sm btn-warning" style="width: 30px;margin-right: 10px" data-target="#modal_edit_po" data-toggle="modal" onclick="edit_po('{{$data->id_tb_po_cus}}','{{$data->no_po}}','{{$data->nominal}}','{{$data->date}}','{{$data->note}}')"><i class="fa fa-pencil"></i></button><a href="{{url('delete_po_customer',$data->id_tb_po_cus)}}"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this data?')" style="width: 30px"><i class="fa fa-trash"></i></button></a></center></td>
					              </tr>
					              @endforeach
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
          

        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Change Log</h3>
              <div class="box-tools pull-right">
              	@if(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_territory == 'OPERATION')
                @else
                <button type="button" class="btn btn-primary" style="width: 100px" data-target="#modal_changelog_progress" data-toggle="modal"><i class="fa fa-plus"></i> Progress</button>
                </button>
                @endif
              </div>
            </div>
            <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width: 20px"><center>No</center></th>
                    <th style="width: 150px"><center>Date</center></th>
                    <th><center>Status</center></th>
                    <th><center>Submit Oleh</center></th>
                  </tr>
                </thead>
                <?php $number = 1; ?>
                <tbody id="products-list" name="products-list">
                  @foreach($change_log as $log)
                  <tr>
                    <td>{{$number++}}</td>
                    <td><center>{{$log->created_at}}</center></td>
		                  @if($log->status == 'Update Lead with Amount ' || $log->status == 'Create Lead with Amount ')
                        <td>{{$log->status}} <i class="money">{{$log->submit_price}}</i></td>
                      @elseif($log->status == 'Update TP')
                        <td>{{$log->status}} - Submitted Price [<i class="money">{{$log->submit_price}}</i>] - Deal Price [<i class="money">{{$log->deal_price}}</i>]</td>
                      @elseif($log->progress_date != NULL)
                        <td>{{$log->status}} - [{{$log->progress_date}}]</td>
		                  @elseif($log->status != 'Update Lead with Amount ' || $log->status == 'Create Lead with Amount ')
		                    <td>{{$log->status}}</td>
		                  @endif
                    <td>{{$log->name}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>  
            </div>
          </div>
        </div>

 <!-- Modal PO Blanket-->
  <div class="modal fade" id="modal_add_po" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Order Customer</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('add_po_customer')}}" id="modalResult" name="modalResult">
            @csrf
          <div class="form-group">
            <input type="" name="lead_id_po" id="lead_id_po" value="{{$tampilkan->lead_id}}" hidden="">
            <input type="" name="id_tp_po" id="id_tp_po" value="{{$tampilkanc->id_tp}}" hidden>
            <div class="col-md-6">
              <label>Date</label>
              <input type="text" class="form-control" name="date_po" id="date_po" placeholder="Entry Date">
            </div>

            <div class="col-md-6">
              <label>No. Purchase Order</label>
              <input type="text" class="form-control" name="no_po" id="no_po" placeholder="Entry Number PO">
            </div>
          </div>
          <div class="form-group col-md-12 inputWithIcon inputIconBg">
              <label>Nominal</label>
              <input class="form-control money" type="text" aria-describedby="emailHelp" placeholder="Enter Nominal" name="nominal_po"  id="nominal_po"/>
                  <i class="" style="margin-top: -23px;margin-left: 15px;" aria-hidden="true">Rp.</i>
          </div>
            
          <div class="form-group col-md-12">
              <label>Note</label>
              <textarea class="form-control" name="note_po" type="text" id="note_po" placeholder="Entry Note"></textarea>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
        </div>
      </div>
  </div>

<!-- Modal edit PO Blanket-->
  <div class="modal fade" id="modal_edit_po" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Purchase Order Customer</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_po_customer')}}" id="modalResult" name="modalResult">
            @csrf
          <div class="form-group">
            <input type="" name="id_po_customer_edit" id="id_po_customer_edit" value="" hidden>
            <div class="col-md-6">
              <label>Date</label>
              <input type="text" class="form-control" name="date_po_edit" id="date_po_edit" placeholder="Entry Date">
            </div>

            <div class="col-md-6">
              <label>No. Purchase Order</label>
              <input type="text" class="form-control" name="no_po_edit" id="no_po_edit" placeholder="Entry Number PO">
            </div>
          </div>
          <div class="form-group col-md-12 inputWithIcon inputIconBg">
              <label>Nominal</label>
              <input class="form-control money" type="text" aria-describedby="emailHelp" placeholder="Enter Nominal" name="nominal_po_edit"  id="nominal_po_edit"/>
              <i class="" style="margin-top: -23px;margin-left: 15px;" aria-hidden="true">Rp.</i>
          </div>
            
          <div class="form-group col-md-12">
              <label>Note</label>
              <textarea class="form-control" name="note_po_edit" type="text" id="note_po_edit" placeholder="Entry Note"></textarea>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
        </div>
      </div>
  </div>


  <!--MOdal Result-->

  <div class="modal fade" id="formResult" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Choose Result</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_result')}}" id="modalResult" name="modalResult">
            @csrf
          <div class="form-group">
            <input type="" name="submit_price_result" id="submit_price_result" value="{{$tampilkanc->submit_price}}" hidden>
            <input type="" name="deal_price_result" id="deal_price_result" value="{{$tampilkanc->deal_price}}" hidden>
            <input type="" name="lead_id_result" id="lead_id_result" value="{{$tampilkan->lead_id}}" hidden>
            <h5><b>Opp Name : <i>{{$tampilkan->opp_name}}</i></b></h5><br>
            <div>
              <select class="form-control" id="result" name="result" required>
                  <option value="">-- Choose Result --</option>
                    <option value="WIN" data-target="win">WIN</option>
                    <option value="LOSE" data-target="lose">LOSE</option>
                    <option value="HOLD">HOLD</option>
                    <option value="CANCEL">CANCEL</option>
                    <option value="SPECIAL">SPECIAL</option>
              </select>
            </div>
            <br>
          </div>

          <div class="form-group" id="result-win" style="display: none;">
            <label><u>Mohon Isi input di bawah sebelum submit!</u></label>

            <label>Date PO</label>
            <input type="text" name="date_po" id="date_po" class="form-control date" ><br>
            
            <label>No. PO</label>
            <input type="text" name="no_po" id="no_po" class="form-control"><br>
            
            <label>Amount PO <sup><b>(Grand Total)</b></sup></label>
            <input type="text" name="amount_pid" id="amount_pid" class="form-control money" ><br>
            
            <label>Project Type</label>
            <select class="form-control" id="project_type" name="project_type" >
              <option value="">-- Choose Result --</option>
              <option value="Supply Only">Supply Only</option>
              <option value="Implementation">Implementation</option>
              <option value="Maintenance">Maintenance</option>
              <option value="Managed-Services">Managed-Services</option>
              <option value="Services">Services</option>
            </select> 

            <label style="padding-top: 15px;">No. Quote</label>
            <select class="form-control" id="quote_number_final" name="quote_number_final" style="width: 100%; ">
            	<option value="">Choose Quote</option>
              @foreach($get_quote_number as $data)
              <option value="{{$data->quote_number}}">{{$data->quote_number}} - {{$data->customer_legal_name}}</option>
              @endforeach
            </select> 

            <label class="checkbox" style="padding-left: 25px; padding-top: 10px;">
              <input type="checkbox" name="request_id" id="request_id" style="width: 7px;height: 7px">
              <span>Request ID Project <sup>(Optional)</sup></span>
            </label>

          </div>

          <div class="form-group">
            <label><b>Closing Date</b></label>
            <input type="text" class="form-control" name="update_closing_date" id="update_closing_date" required>
          </div>

          <!-- <div class="form-group" style="padding-left: 25px">
            <label class="checkbox">
              <input type="checkbox" name="request_id" id="request_id" value="pending" style="width: 7px;height: 7px">
              <span>Request ID Project <sup>(Optional)</sup></span>
            </label>
          </div> -->
          
          <div class="form-group" id="result-lose" style="display: none;">
            <label><b>Description</b></label>
            <textarea type="text" class="form-control" placeholder="Enter Description" name="Description" id="keterangan" required> </textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!--status PMO-->
  <div class="modal fade" id="formResult2" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Result</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_next_status')}}" id="modalResult2" name="modalResult2">
            @csrf
          <div class="form-group row">
            <input type="" name="lead_id_result2" id="lead_id_result2" value="{{$tampilkan->lead_id}}" hidden>
            <label for="">Result</label><br>
            <select class="form-control-small margin-left-custom" id="result2" name="result2" required>
              @if($tampilkan->result2 == 'INITIAL')
              <option value="">-- Choose Result --</option>
                    <option value="RUNNING" selected="">INITIAL</option>
                    <option value="RUNNING">RUNNING</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="BAST">BAST</option>
                    <option value="TRAINING">TRAINING</option>
              @elseif($tampilkan->result2 == 'RUNNING')
              <option value="">-- Choose Result --</option>
                    <option value="INITIAL">INITIAL</option>
                    <option value="RUNNING" selected="">RUNNING</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="BAST">BAST</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result2 == 'TRAINING')
              <option value="">-- Choose Result --</option>
                    <option value="INITIAL">INITIAL</option>
                    <option value="RUNNING">RUNNING</option>
                    <option value="TRAINING" selected="">TRAINING</option>
                    <option value="BAST">BAST</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result2 == 'BAST')
              <option value="">-- Choose Result --</option>
                    <option value="INITIAL">INITIAL</option>
                    <option value="RUNNING">RUNNING</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="BAST"  selected="">BAST</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result2 == 'DONE')
              <option value="">-- Choose Result --</option>
                    <option value="INITIAL">INITIAL</option>
                    <option value="RUNNING">RUNNING</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="BAST">BAST</option>
                    <option value="DONE" selected="">DONE</option>
              @else
              <option value="">-- Choose Result --</option>
                    <option value="INITIAL">INITIAL</option>
                    <option value="RUNNING">RUNNING</option>
                    <option value="TRAINING">TRAINING</option>
                    <option value="BAST">BAST</option>
                    <option value="DONE">DONE</option>
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!--status Engineer-->
   <div class="modal fade" id="formResult3" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Result</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_status_eng')}}" id="modalResult3" name="modalResult3">
            @csrf
          <div class="form-group">
            <input type="" name="lead_result" id="lead_result" value="{{$tampilkan->lead_id}}" hidden>
            <h2 for="">Result</h2>
            <div style="width: 500px">
              <select class="form-control-small margin-left-custom" id="result3" name="result3" required>
              @if($tampilkan->result3 == 'STAGING')
              <option value="">-- Choose Result --</option>
                    <option value="STAGING" selected="">STAGING</option>
                    <option value="INSTALASI">INSTALASI</option>
                    <option value="MIGRASI">MIGRASI</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result3 == 'INSTALASI')
              <option value="">-- Choose Result --</option>
                    <option value="STAGING">STAGING</option>
                    <option value="INSTALASI" selected="">INSTALASI</option>
                    <option value="MIGRASI">MIGRASI</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result3 == 'MIGRASI')
              <option value="">-- Choose Result --</option>
                    <option value="STAGING">STAGING</option>
                    <option value="INSTALASI">INSTALASI</option>
                    <option value="MIGRASI" selected="">MIGRASI</option>
                    <option value="DONE">DONE</option>
              @elseif($tampilkan->result3 == 'DONE')
              <option value="">-- Choose Result --</option>
                    <option value="STAGING">STAGING</option>
                    <option value="INSTALASI">INSTALASI</option>
                    <option value="MIGRASI">MIGRASI</option>
                    <option value="DONE" selected="">DONE</option>
              @else
              <option value="">-- Choose Result --</option>
                    <option value="STAGING">STAGING</option>
                    <option value="INSTALASI">INSTALASI</option>
                    <option value="MIGRASI">MIGRASI</option>
                    <option value="DONE">DONE</option>
              @endif
            </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- raise to tender -->
  <div class="modal fade" id="modal_raise" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <form action="{{url('raise_to_tender')}}" method="POST">
            {!! csrf_field() !!}
            <input type="" name="lead_id" id="lead_id" value="{{$tampilkan->lead_id}}" hidden>
            <div style="text-align: center;">
              <h3>Are you sure?</h3><br><h3>RAISE TO TENDER</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Change Log Progress -->
  <div class="modal fade" id="modal_changelog_progress" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Progress</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('add_changelog_progress')}}" id="modalChangeLog_Progress" name="modalChangeLog_Progress">
            @csrf

            <div class="form-group">
              <label for="">Progress Date</label>
              <input type="text" id="changelog_date" name="changelog_date" class="form-control">
            </div>
          
          <div class="form-group">
            <label for="">Description</label>
            <input type="" name="changelog_lead_id" id="changelog_lead_id" value="{{$tampilkan->lead_id}}" hidden>
            <textarea name="changelog_progress" id="changelog_progress" class="form-control"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Contribute Presales Assignment -->
  <div class="modal fade" id="contributeModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Contribute Presales</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('add_contribute') }}" id="modalContribute" name="modalContribute">
            @csrf
          <div class="form-group">
            <input type="text" name="coba_lead_contribute" id="coba_lead_contribute" value="{{ $tampilkan->lead_id }}" hidden>
            <div style="250px">
              <select class="form-control" id="add_contribute" name="add_contribute" required>
              <option>-- Choose Contribute --</option>
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL PRESALES' && $data->id_company == 1)
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
            </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Contribute PMO Assignment -->
  <div class="modal fade" id="contributeModalPMO" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Contribute PMO</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('add_contribute_pmo') }}" id="modalContributePMO" name="modalContributePMO">
            @csrf
          <div class="form-group row">
            <input type="text" name="coba_lead_contribute_pmo" id="coba_lead_contribute_pmo" value="{{ $tampilkan->lead_id }}" hidden>
            <label for="">Add Contribute</label><br>
            <select class="form-control-small margin-left-custom" id="add_contribute_pmo" name="add_contribute_pmo" required>
              <option>-- Choose Contribute --</option>
              @if(Auth::User()->id_division == 'PMO')
                @foreach($owner as $data)
                  @if($data->id_division == 'PMO' && $data->name != $pmo_id->name)
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endif
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Contribute Engineer Assignment -->
  <div class="modal fade" id="contributeModalEngineer" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Contribute Engineer</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('add_contribute_engineer') }}" id="modalContributeEngineer" name="modalContributeEngineer">
            @csrf
          <div class="form-group row">
            <input type="text" name="coba_lead_contribute_engineer" id="coba_lead_contribute_engineer" value="{{ $tampilkan->lead_id }}" hidden>

            <label for="">Add Contribute</label><br>
            <select class="form-control-small margin-left-custom" id="add_contribute_engineer" name="add_contribute_engineer" required>
              <option>-- Choose Contribute --</option>
              @if(Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_territory == 'DPG' && Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'ENGINEER STAFF')
                @foreach($owner as $data)
                  @if($data->id_division == 'TECHNICAL' && $data->id_territory == 'DPG' && $data->id_position == 'ENGINEER MANAGER' || $data->id_position == 'ENGINEER STAFF')
                    @if($data->name != $engineer_id->name)
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endif
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp Close</button>

            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Progress PMO-->

  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Progress</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('pmo_progress') }}" id="modalProgress" name="modalProgress">
            @csrf
          @if(Auth::User()->id_division == 'PMO' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO')
          <input type="" id="id_pmo" name="id_pmo" value="{{$pmo_id->id_pmo}}" hidden>
          @endif
          
          <div class="form-group">
            <label for="tanggal">Tanggal</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal">
          </div>

          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>


   <!-- Add Progress ENGINEER-->

  <div class="modal fade" id="engineer_progress" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Progress</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('engineer_progress')}}" id="modalProgress" name="modalProgress">
            @csrf
          @if(Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v' || Auth::User()->id_position == 'DIRECTOR' && $tampilkan->result == 'WIN' && $tampilkan->status_sho == 'PMO' && $tampilkan->status_engineer == 'v')
          <input type="" id="id_engineer" name="id_engineer" value="{{$current_eng->id_engineer}}" hidden>
          @endif
          @if(Auth::User()->id_position == 'ENGINEER STAFF')
          <input type="" id="id_engineer" name="id_engineer" value="{{$current_eng->id_engineer}}" hidden>
          @endif
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
      
  </section>

@endsection

  @section('script')

    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
    <script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
    <script src="{{asset("template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
    <script type="text/javascript">

      $("#quote_number_final").select2();

      function edit_po(id_tb_po_cus,no_po,nominal,date,note){
      	$('#id_po_customer_edit').val(id_tb_po_cus);
      	$('#no_po_edit').val(no_po);
      	$('#nominal_po_edit').val(nominal);
      	$("#date_po_edit").datepicker({format: 'yyyy-mm-dd',autoclose: true}).datepicker('setDate', date);
      	$('#note_po_edit').val(note);
      }

      $('#update_closing_date').datepicker({
        autoclose: true
      })
       
      $('#submit_date2').datepicker({
        autoclose: true
      })

      $('#date_po').datepicker({
        autoclose: true
      })

      $('#submit_date').datepicker({
        autoclose: true
      })

      $('#changelog_date').datepicker({
        autoclose: true
      });

      $('.date').datepicker({
        autoclose: true
      });

      $(document).ready(function(){
          $('#result').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
          });
      });

          console.log('{{$tampilkan->result}}');      
          if ('{{$tampilkan->result}}' == 'LOSE') {
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#win_lose:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active6');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
                $('#s_winlose').html("<b> LOSE </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('win_lose');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');
              $('#win_lose').addClass('active');
              $('#s_winlose').html("<b> LOSE </b>");*/
          } else if('{{$tampilkan->result}}' == 'WIN'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#win_lose:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active4');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
                $('#s_winlose').html("<b> WIN </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('win_lose');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');
              $('#win_lose').addClass('active4');
              $('#s_winlose').html("<b> WIN </b>");*/
          }else if('{{$tampilkan->result}}' == 'HOLD'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#win_lose:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
                $('#s_winlose').html("<b> HOLD </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('win_lose');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');
              $('#win_lose').addClass('active4');
              $('#s_winlose').html("<b> WIN </b>");*/
          }else if('{{$tampilkan->result}}' == 'SPECIAL'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#win_lose:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
                $('#s_winlose').html("<b> SPECIAL </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('win_lose');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');
              $('#win_lose').addClass('active4');
              $('#s_winlose').html("<b> WIN </b>");*/
          }else if('{{$tampilkan->result}}' == 'CANCEL'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#win_lose:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
                $('#s_winlose').html("<b> CANCEL </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('win_lose');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');
              $('#win_lose').addClass('active4');
              $('#s_winlose').html("<b> WIN </b>");*/
          } else if('{{$tampilkan->result}}' == ''){
              var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('open');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);

              /*
              setInterval(function (){
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
              })*//*
              $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');*/
          } else if('{{$tampilkan->result}}' == 'SD'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('sd');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
              /*$('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');*/
          } else if('{{$tampilkan->result}}' == 'TP'){
            var i = 0;
              setInterval(function (){
                $('#init:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active5');
                i++
                $('#open:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active1');
                $('#sd:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active2');
                $('#tp:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('active3');
                $('#s_init').html("<b> INITIAL </b>");
                $('#s_open').html("<b> OPEN </b>");
                $('#s_sd').html("<b> SOLUTION DESIGN </b>");
                $('#s_tp').html("<b> TENDER PROCCESS </b>");
              },1000)
              var kedipan = 500; 
              var dumet = setInterval(function () {
                  var ele = document.getElementById('tp');
                  ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
              }, kedipan);
            /*  $('#init').addClass('active5');
              $('#open').addClass('active1');
              $('#sd').addClass('active2');
              $('#tp').addClass('active3');*/
          } else if ('{{$tampilkan->result}}' == 'OPEN') {
              $('#init').addClass('active5');
          }
 
          $('.money').mask('000,000,000,000,000', {reverse: true});

          function updatequote(quote_number){
            $('#quote_number').val(quote_number);
          }

          function progress(id_pmo){
            $('#pmo_id').val(lead_id);
          }

          $('#datastable').dataTable();

          $('#data_po').dataTable({
          	"footerCallback": function( row, data, start, end, display ) {
			  
  			var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'Rp' ).display;

            var api = this.api(),data;
  	        // Remove the formatting to get integer data for summation
  	        var intVal = function ( i ) {
    		  return typeof i === 'string' ?
    		  i.replace(/[\$,]/g, '')*1 :
    		  typeof i === 'number' ?
    		  i : 0;
    		};

			var filtered = api.column( 3, {"filter": "applied"} ).data().sum();
	           
	        $( api.column( 3 ).footer() ).html(numFormat(filtered) + '');
		   },
          });

          $('#table_pmo').dataTable( {
            "scrollY": true
          });

          $('#assigned_by').change(function(){
              /*var url = $(this).attr("action");*/
              $.ajax({
              type:"GET",
              url:'/assign_quote',
              data:{
                id_assign:this.value,
              },
              success: function(result){
            /*    var append = "";*/
                $('#quote_number').html(append)
                var append = "<option selected='selected'>Select Option</option>";

                if (result[1] == 'DIR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.id_quote + "</option>";
                });
                } else if (result[1] == 'AM') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.id_quote + "</option>";
                });
                }

                $('#quote_number').html(append);
              },
          });
      });

      function showKeterangan() {
        var selectBox = document.getElementById('result');
        var UserInput = selectBox.options[selectBox.selectedIndex].value;
        if (UserInput == 'lose') {
          document.getElementById('result_lose').style.visibility = 'visible';
        } else {
          document.getElementById('result_lose').style.visibility = 'hidden';
        }

        return false;
      }

      $(function() {
        $('#project_class').change(function(){
          if ($(this).val() == 'multiyears') {
            $("#tahun_jumlah").css("display", "block");
            $("#total_price_deal").css("display", "block");
            $("#price_deal").css("display", "block");
          }else if($(this).val() == 'blanket'){
            $("#tahun_jumlah").css("display", "block");
            $("#total_price_deal").css("display", "block");
            $("#price_deal").css("display", "block");
            $("#purchase-order").css("display", "block");
          }else if($(this).val() == 'normal'){
            $("#tahun_jumlah").css("display", "none");
            $("#total_price_deal").css("display", "none");
          }else{
          	$("#tahun_jumlah").css("display", "none");
            $("#total_price_deal").css("display", "none");
            $("#price_deal").css("display", "none");
            $("#purchase-order").css("display", "none");
          }
        console.log($(this).val());
        });

        {{-- var $select = $(".jumlah_tahun");
        for (i=1;i<=10;i++){
          $select.append($('<option></option>').val(i).html(i))
        } --}}
      });
    </script>  
    <style type="text/css">
    .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }
  </style>

@endsection