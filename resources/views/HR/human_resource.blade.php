@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
	    .margin-left-custom-psw{
	      margin-left: 45px;
	    }
		.input-container {
		  display: -ms-flexbox; /* IE10 */
		  display: flex;
		  width: 100%;
		  margin-bottom: 15px;
		}

		.icon {
		  padding: 10px;
		  background: dodgerblue;
		  color: white;
		  min-width: 50px;
		  text-align: center;
		}

		.input-field {
		  width: 100%;
		  padding: 10px;
		  outline: none;
		}

		.input-field:focus {
		  border: 2px solid dodgerblue;
		}

		.current {
		  color: green;
		}

		#pagin li {
		  display: inline-block;
		}

		.prev {
		  cursor: pointer;
		}

		.next {
		  cursor: pointer;
		}

		.last{
		  cursor:pointer;
		  margin-left:5px;
		}

		.first{
		  cursor:pointer;
		  margin-right:5px;
		}

  </style>

  <section class="content-header">
    <h1>
      Employees
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Human Resource</li>
      <li class="active">Employees</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
        @endif

        @if (session('success'))
      <div class="alert alert-success" id="alert">
          {{ session('success') }}
      </div>
        @endif

        @if (session('alert'))
      <div class="alert alert-danger" id="alert">
          {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i>&nbsp<b>SIP Employees</b></h3>

      </div>

      @if(Auth::User()->email == 'tech@sinergy.co.id')
      	<div class="row">
      		<div class="col-md-12">
      			<div class="pull-right" style="margin-right: 20px">
      				<div class="input-container">
					    <i class="fa fa-search icon"></i>
					    <input class="input-field form-control Search" id="search" name="search" type="text" placeholder="Search..." name="email">
					    <!-- <button class="btn btn-primary btn-sm">Cari</button> -->
					</div>
      			</div>
      			<div class="nav-tabs-custom active" id="SIP" role="tabpanel" aria-labelledby="sip-tab">
			      	<ul class="nav nav-tabs" id="myTab" role="tablist">
			      		<li class="nav-item active">
				              <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">ALL</a>
				        </li>
			      		@foreach($division as $datas)
				            @if($datas->id_division != '-' && $datas->id_division != 'NULL')
				            <li class="nav-item">
				              <a class="nav-link" id="all-tab" data-toggle="tab" href="#{{$datas->id_division}}" role="tab" aria-controls="all" aria-selected="true">{{$datas->id_division}}</a>
				            </li>
				            @endif
			            @endforeach
			        </ul>
			    </div>
			    <div class="tab-content" id="myTabContentSIP">
		            <div class="tab-pane active" id="all" role="tabpanel" aria-labelledby="all-tab">
		            	@foreach($hr as $data)
		      				@if($data->code_company == 'SIP')
			      			<div class="col-lg-4 col-xs-6" id="alls2">
					            <div class="box box-info">
								  <div class="box-header with-border">
								  	<h3 class="box-title"></h3>
								    <div class="box-tools pull-right">
								      <span class="label label-primary">
								      		@if($data->id_position == 'DIRECTOR' && $data->id_division == 'NULL')
				                              President Director
				                            @elseif($data->id_division == 'TECHNICAL')
				                              @if($data->id_territory == 'DPG')
				                                @if($data->id_position == 'ENGINEER MANAGER')
				                                  Dept. Implementation Manager
				                                @elseif($data->id_position == 'ENGINEER STAFF')
				                                  Staff. Systems Engineer
				                                @endif
				                              @elseif($data->id_territory == 'DVG')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Development Manager
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Dev Ops
				                                @elseif($data->id_position == 'INTERNAL IT')
				                                  Staff. Internal IT Engineer
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. TEC Admin
				                                @endif
				                              @elseif($data->id_territory == 'SPECIALIST')
				                                @if($data->id_position == 'EXPERT ENGINEER')
				                                  Expert Engineer
				                                @endif
				                              @else
				                                @if($data->id_position == 'MANAGER')
				                                  Div. Technical Head
				                                @elseif($data->id_position == 'INTERNAL IT')
				                                  Staff. Internal IT Engineer
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. TEC Admin
				                                @else
				                                  External
				                                @endif
				                              @endif
				                            @elseif($data->id_division == 'TECHNICAL PRESALES')
				                              @if($data->id_position == 'MANAGER')
				                                Dept. Presales Manager
				                              @elseif($data->id_position == 'STAFF')
				                                Staff. Presales Engineer
				                              @endif
				                            @elseif($data->id_division == 'SALES')
				                              @if($data->id_territory == 'TERRITORY 1')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (First)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM1
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'TERRITORY 2')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (Second)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM2
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'TERRITORY 3')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (Third)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM3
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'TERRITORY 4')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (Fourth)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM4
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'TERRITORY 5')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (Fifth)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM5
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'TERRITORY 6')
				                                @if($data->id_position == 'MANAGER')
				                                  Dept. Account Manager (Sixth)
				                                @elseif($data->id_position == 'STAFF')
				                                  Staff. Account Executive AM6
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. Admin Sales
				                                @endif
				                              @elseif($data->id_territory == 'SPECIALIST')
				                                @if($data->id_position == 'EXPERT SALES')
				                                  Expert Sales
				                                @endif
				                              @endif
				                            @elseif($data->id_division == 'FINANCE')
				                              @if($data->id_position != 'FINANCE DIRECTOR')
				                                @if($data->id_territory == 'FINANCE')
				                                  @if($data->id_position == 'STAFF')
				                                    Staff. Finance
				                                  @elseif($data->id_position == 'COURIER')
				                                    Staff. Courier
				                                  @endif
				                                @elseif($data->id_territory == 'ACC')
				                                  @if($data->id_position == 'MANAGER')
				                                    Div. Accounting
				                                  @elseif($data->id_position == 'STAFF')
				                                    Staff. Accounting
				                                  @endif
				                                @endif
				                              @else
				                                Finance Director
				                              @endif
				                            @elseif($data->id_territory == 'OPERATION')
				                              @if($data->id_division == 'OPERATION')
				                              	Operation Director
				                              @elseif($data->id_division == 'PMO')
				                                @if($data->id_position == 'MANAGER')
				                                  Div. Project Management Office
				                                @elseif($data->id_position == 'PM')
				                                  Staff. Project Manager
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. PMO Admin
				                                @endif
				                              @elseif($data->id_division == 'MSM')
				                                @if($data->id_position == 'MANAGER')
				                                  Div. Managed Services & Maintenance
				                                @elseif($data->id_position == 'ADMIN')
				                                  Staff. MSM Admin
				                                @elseif($data->id_position == 'CALL SO')
				                                  Staff. Call Center Operator
				                                @elseif($data->id_position == 'HELP DESK')
				                                  Staff. Dedicated Help Desk
				                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
				                                  Dept. Technical Support
				                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
				                                  Staff. Support Engineer
				                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
				                                  Dept. Services Project Manager
				                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
				                                  Staff. Services Project Coordinator
				                                @else
				                                  Staff. Support Engineer
				                                @endif
				                              @endif
				                            @elseif($data->id_division == 'HR')
				                              @if($data->id_position == 'HR MANAGER')
				                                Div. Human Resource Head
				                              @elseif($data->id_position == 'STAFF GA')
				                                Staff. General Affair
				                              @elseif($data->id_position == 'STAFF HR')
				                                Staff. Human Resource
				                              @endif
				                            @else
				                              {{ ucwords(strtolower($data->id_position)) }}
				                            @endif
								      </span>
								    </div>
								    <!-- /.box-tools -->
								  </div>
								  <!-- /.box-header -->
								  <div class="box-body">
								    <div style="float: left">
								    	{{$data->nik}}<br>
								    	{{ucwords(strtolower($data->name))}}
								    </div>

								    <div style="float: right;">
							    	 @if($data->gambar == NULL)
						                <img class="profile-user img-responsive" src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" alt="Yuki" style="width: 100px;height:100px;position: relative;">
						              @elseif($data->gambar != NULL)
						                <img class="profile-user img-responsive" src="{{ asset('image/'.$data->gambar)}}" alt="Yuki" style="width: 100px;height:100px;position: relative;border-radius: 50%">
						              @endif
								    </div>
								    
								  </div>
								  <!-- /.box-body -->
								  <div class="box-footer">
								  	<a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger pull-right margin-left" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
				                    <i class="fa fa-trash"></i>&nbspDelete</button></a>
								    <button class="btn btn-xs btn-primary btn-editan pull-right" value="{{$data->nik}}" name="edit_hurec" style="vertical-align: top; width: 60px"><i class="fa fa-search"></i>&nbspEdit</button>
								  </div>
								  <!-- box-footer -->
								</div>
				          	</div>
				          	@endif
		      		  	@endforeach	
		            </div>
		            @foreach($division as $datas)
			            @if($datas->id_division != '-' && $datas->id_division != 'NULL')
			            <div class="tab-pane" id="{{$datas->id_division}}" role="tabpanel" aria-labelledby="all-tab">
		            	 	@foreach($hr as $data)
			      				@if($data->code_company == 'SIP')
			      					@if($data->id_division == $datas->id_division && $data->id_position != '')
					      			<div class="col-lg-4 col-xs-6" id="alls3">
							            <div class="box box-info">
										  <div class="box-header with-border">
										  	<h3 class="box-title"></h3>
										    <div class="box-tools pull-right">
										      <span class="label label-primary">
										      	@if($data->id_position == 'DIRECTOR' && $data->id_division == '')
						                              President Director
						                            @elseif($data->id_division == 'TECHNICAL')
						                              @if($data->id_territory == 'DPG')
						                                @if($data->id_position == 'ENGINEER MANAGER')
						                                  Dept. Implementation Manager
						                                @elseif($data->id_position == 'ENGINEER STAFF')
						                                  Staff. Systems Engineer
						                                @endif
						                              @elseif($data->id_territory == 'DVG')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Development Manager
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Dev Ops
						                                @elseif($data->id_position == 'INTERNAL IT')
						                                  Staff. Internal IT Engineer
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. TEC Admin
						                                @endif
						                              @elseif($data->id_territory == 'SPECIALIST')
						                                @if($data->id_position == 'EXPERT ENGINEER')
						                                  Expert Engineer
						                                @endif
						                              @else
						                                @if($data->id_position == 'MANAGER')
						                                  Div. Technical Head
						                                @elseif($data->id_position == 'INTERNAL IT')
						                                  Staff. Internal IT Engineer
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. TEC Admin
						                                @endif
						                              @endif
						                            @elseif($data->id_division == 'TECHNICAL PRESALES')
						                              @if($data->id_position == 'MANAGER')
						                                Dept. Presales Manager
						                              @elseif($data->id_position == 'STAFF')
						                                Staff. Presales Engineer
						                              @endif
						                            @elseif($data->id_division == 'SALES')
						                              @if($data->id_territory == 'TERRITORY 1')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (First)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM1
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'TERRITORY 2')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (Second)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM2
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'TERRITORY 3')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (Third)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM3
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'TERRITORY 4')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (Fourth)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM4
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'TERRITORY 5')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (Fifth)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM5
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'TERRITORY 6')
						                                @if($data->id_position == 'MANAGER')
						                                  Dept. Account Manager (Sixth)
						                                @elseif($data->id_position == 'STAFF')
						                                  Staff. Account Executive AM6
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. Admin Sales
						                                @endif
						                              @elseif($data->id_territory == 'SPECIALIST')
						                                @if($data->id_position == 'EXPERT SALES')
						                                  Expert Sales
						                                @endif
						                              @endif
						                            @elseif($data->id_division == 'FINANCE')
						                              @if($data->id_position != 'FINANCE DIRECTOR')
						                                @if($data->id_territory == 'FINANCE')
						                                  @if($data->id_position == 'STAFF')
						                                    Staff. Finance
						                                  @elseif($data->id_position == 'COURIER')
						                                    Staff. Courier
						                                  @endif
						                                @elseif($data->id_territory == 'ACC')
						                                  @if($data->id_position == 'MANAGER')
						                                    Div. Accounting
						                                  @elseif($data->id_position == 'STAFF')
						                                    Staff. Accounting
						                                  @endif
						                                @endif
						                              @else
						                                Finance Director
						                              @endif
						                            @elseif($data->id_territory == 'OPERATION')
						                              @if($data->id_division == 'OPERATION')
						                                Operation Director
						                              @elseif($data->id_division == 'PMO')
						                                @if($data->id_position == 'MANAGER')
						                                  Div. Project Management Office
						                                @elseif($data->id_position == 'PM')
						                                  Staff. Project Manager
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. PMO Admin
						                                @endif
						                              @elseif($data->id_division == 'MSM')
						                                @if($data->id_position == 'MANAGER')
						                                  Div. Managed Services & Maintenance
						                                @elseif($data->id_position == 'ADMIN')
						                                  Staff. MSM Admin
						                                @elseif($data->id_position == 'CALL SO')
						                                  Staff. Call Center Operator
						                                @elseif($data->id_position == 'HELP DESK')
						                                  Staff. Dedicated Help Desk
						                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
						                                  Dept. Technical Support
						                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
						                                  Staff. Support Engineer
						                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
						                                  Dept. Services Project Manager
						                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
						                                  Staff. Services Project Coordinator
						                                @else
						                                  Staff. Support Engineer
						                                @endif
						                              @endif
						                            @elseif($data->id_division == 'HR')
						                              @if($data->id_position == 'HR MANAGER')
						                                Div. Human Resource Head
						                              @elseif($data->id_position == 'STAFF GA')
						                                Staff. General Affair
						                              @elseif($data->id_position == 'STAFF HR')
						                                Staff. Human Resource
						                              @endif
						                            @else
						                              {{ ucwords(strtolower($data->id_position)) }}
						                            @endif
										      </span>
										    </div>
										    <!-- /.box-tools -->
										  </div>
										  <!-- /.box-header -->
										  <div class="box-body">
										    <div style="float: left">
										    	{{$data->nik}}<br>
										    	{{ucwords(strtolower($data->name))}}
										    </div>

										    <div style="float: right;">
										    	 @if($data->gambar == NULL)
									                <img class="profile-user img-responsive" src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" alt="Yuki" style="width: 100px;height:100px;position: relative;">
									              @elseif($data->gambar != NULL)
									                <img class="profile-user img-responsive" src="{{ asset('image/'.$data->gambar)}}" alt="Yuki" style="width: 100px;height:100px;position: relative;border-radius: 50%">
									              @endif
										    	
										    </div>
										    
										  </div>
										  <!-- /.box-body -->
										  <div class="box-footer">
										  	<a href=""><button class="btn btn-xs btn-danger pull-right margin-left" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
						                    <i class="fa fa-trash"></i>&nbspDelete</button></a>
										    <button class="btn btn-xs btn-primary btn-editan pull-right" name="edit_hurec" style="vertical-align: top; width: 60px"><i class="fa fa-search"></i>&nbspEdit</button>
										  </div>
										  <!-- box-footer -->
										</div>
						          	</div>
						          	@endif
					          	@endif
			      		  	@endforeach	
		            	</div>
			            @endif
		            @endforeach
		            
		        </div>

      		</div>
      		<div id="pagination" class="col-md-12 margin-left">tes</div>
      	</div>
      @endif

      <div class="box-body">

        <div class="nav-tabs-custom active" id="SIP" role="tabpanel" aria-labelledby="sip-tab">
          
          <div class="pull-right">
            @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
            <button class="btn btn-sm btn-primary" style="margin-bottom: 5px" id="btnAdd" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i>&nbsp Employee</button>
            @endif
          </div>

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">ALL</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tech-tab" data-toggle="tab" href="#tech" role="tab" aria-controls="tech" aria-selected="false">TECHNICAL</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false"> SALES</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="finance-tab" data-toggle="tab" href="#finance" role="tab" aria-controls="finance" aria-selected="false"> FINANCE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="operation-tab" data-toggle="tab" href="#operation" role="tab" aria-controls="operation" aria-selected="false"> OPERATION</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContentSIP">
            <div class="tab-pane active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="data_all" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          <th>Mulai Bekerja</th>
                          <th>Status Karyawan</th>
                          <th>NPWP</th>
                          <!-- <th>NPWP File</th> -->
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_company == '1')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ucwords(strtolower($data->name))}}</td>
                          @if($data->id_position != '')
                          <td>
	                            @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
	                              President Director
	                            @elseif($data->id_division == 'TECHNICAL')
	                              @if($data->id_territory == 'DPG')
	                                @if($data->id_position == 'ENGINEER MANAGER')
	                                  Dept. Implementation Manager
	                                @elseif($data->id_position == 'ENGINEER STAFF')
	                                  Staff. Systems Engineer
	                                @endif
	                              @elseif($data->id_territory == 'DVG')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Development Manager
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Dev Ops
	                                @elseif($data->id_position == 'INTERNAL IT')
	                                  Staff. Internal IT Engineer
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. TEC Admin
	                                @endif
	                              @elseif($data->id_territory == 'SPECIALIST')
	                                @if($data->id_position == 'EXPERT ENGINEER')
	                                  Expert Engineer
	                                @endif
	                              @else
	                                @if($data->id_position == 'MANAGER')
	                                  Div. Technical Head
	                                @elseif($data->id_position == 'INTERNAL IT')
	                                  Staff. Internal IT Engineer
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. TEC Admin
	                                @endif
	                              @endif
	                            @elseif($data->id_division == 'TECHNICAL PRESALES')
	                              @if($data->id_position == 'MANAGER')
	                                Dept. Presales Manager
	                              @elseif($data->id_position == 'STAFF')
	                                Staff. Presales Engineer
	                              @endif
	                            @elseif($data->id_division == 'SALES')
	                              @if($data->id_territory == 'TERRITORY 1')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (First)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM1
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'TERRITORY 2')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (Second)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM2
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'TERRITORY 3')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (Third)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM3
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'TERRITORY 4')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (Fourth)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM4
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'TERRITORY 5')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (Fifth)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM5
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'TERRITORY 6')
	                                @if($data->id_position == 'MANAGER')
	                                  Dept. Account Manager (Sixth)
	                                @elseif($data->id_position == 'STAFF')
	                                  Staff. Account Executive AM6
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. Admin Sales
	                                @endif
	                              @elseif($data->id_territory == 'SPECIALIST')
	                                @if($data->id_position == 'EXPERT SALES')
	                                  Expert Sales
	                                @endif
	                              @endif
	                            @elseif($data->id_division == 'FINANCE')
	                              @if($data->id_position != 'FINANCE DIRECTOR')
	                                @if($data->id_territory == 'FINANCE')
	                                  @if($data->id_position == 'STAFF')
	                                    Staff. Finance
	                                  @elseif($data->id_position == 'COURIER')
	                                    Staff. Courier
	                                  @endif
	                                @elseif($data->id_territory == 'ACC')
	                                  @if($data->id_position == 'MANAGER')
	                                    Div. Accounting
	                                  @elseif($data->id_position == 'STAFF')
	                                    Staff. Accounting
	                                  @endif
	                                @endif
	                              @else
	                                Finance Director
	                              @endif
	                            @elseif($data->id_territory == 'OPERATION')
	                              @if($data->id_division == 'OPERATION')
	                                Operation Director
	                              @elseif($data->id_division == 'PMO')
	                                @if($data->id_position == 'MANAGER')
	                                  Div. Project Management Office
	                                @elseif($data->id_position == 'PM')
	                                  Staff. Project Manager
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. PMO Admin
	                                @endif
	                              @elseif($data->id_division == 'MSM')
	                                @if($data->id_position == 'MANAGER')
	                                  Div. Managed Services & Maintenance
	                                @elseif($data->id_position == 'ADMIN')
	                                  Staff. MSM Admin
	                                @elseif($data->id_position == 'CALL SO')
	                                  Staff. Call Center Operator
	                                @elseif($data->id_position == 'HELP DESK')
	                                  Staff. Dedicated Help Desk
	                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
	                                  Dept. Technical Support
	                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
	                                  Staff. Support Engineer
	                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
	                                  Dept. Services Project Manager
	                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
	                                  Staff. Services Project Coordinator
	                                @endif
	                              @endif
	                            @elseif($data->id_division == 'HR')
	                              @if($data->id_position == 'HR MANAGER')
	                                Div. Human Resource Head
	                              @elseif($data->id_position == 'STAFF GA')
	                                Staff. General Affair
	                              @elseif($data->id_position == 'STAFF HR')
	                                Staff. Human Resource
	                              @endif
	                            @else
	                              {{ $data->id_position }}
	                            @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          <td>{{date('d-m-Y', strtotime($data->date_of_entry))}}</td>
                          <td>
                          	@if($data->status_karyawan == 'cuti')
                          	Karyawan Tetap 
                          	@elseif($data->status_karyawan == 'belum_cuti')
                          	Karyawan Kontrak <i class="fa fa-pencil modal_edit_status" style="color: #f39c12;cursor: pointer;"></i>
                          	@endif
                          </td>
                          <td>{{ $data->no_npwp }}</td>
                          <!-- <td><img src="{{ asset('image/'.$data->npwp_file) }}" style="max-height:200px;max-width:200px;margin-top:10px;"></td> -->
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec" style="vertical-align: top; width: 60px"><i class="fa fa-search"></i>&nbspEdit</button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            <i class="fa fa-trash"></i>&nbspDelete</button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div> 
            </div>
            <div class="tab-pane" id="tech" role="tabpanel" aria-labelledby="tech-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="data_tech" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ucwords(strtolower($data->name))}}</td>
                          @if($data->id_position != '')
                          <td>
                            @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                              President Director
                            @elseif($data->id_division == 'TECHNICAL')
                              @if($data->id_territory == 'DPG')
                                @if($data->id_position == 'ENGINEER MANAGER')
                                  Dept. Implementation Manager
                                @elseif($data->id_position == 'ENGINEER STAFF')
                                  Staff. Systems Engineer
                                @endif
                              @elseif($data->id_territory == 'DVG')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Development Manager
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Dev Ops
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @elseif($data->id_territory == 'SPECIALIST')
                                @if($data->id_position == 'EXPERT ENGINEER')
                                  Expert Engineer
                                @endif
                              @else
                                @if($data->id_position == 'MANAGER')
                                  Div. Technical Head
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @endif
                            @elseif($data->id_division == 'TECHNICAL PRESALES')
                              @if($data->id_position == 'MANAGER')
                                Dept. Presales Manager
                              @elseif($data->id_position == 'STAFF')
                                Staff. Presales Engineer
                              @endif
                            @elseif($data->id_division == 'SALES')
                              @if($data->id_territory == 'TERRITORY 1')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (First)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM1
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 2')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Second)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM2
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 3')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Third)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM3
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 4')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fourth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM4
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 5')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fifth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM5
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 6')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Sixth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM6
                                @endif
                              @endif
                            @elseif($data->id_division == 'FINANCE')
                              @if($data->id_position != 'FINANCE DIRECTOR')
                                @if($data->id_territory == 'FINANCE')
                                  @if($data->id_position == 'STAFF')
                                    Staff. Finance
                                  @elseif($data->id_position == 'COURIER')
                                    Staff. Courier
                                  @endif
                                @elseif($data->id_territory == 'ACC')
                                  @if($data->id_position == 'MANAGER')
                                    Div. Accounting
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Accounting
                                  @endif
                                @endif
                              @else
                                Finance Director
                              @endif
                            @elseif($data->id_territory == 'OPERATION')
                              @if($data->id_division == null)
                                Operation Director
                              @elseif($data->id_division == 'PMO')
                                @if($data->id_position == 'MANAGER')
                                  Div. Project Management Office
                                @elseif($data->id_position == 'PM')
                                  Staff. Project Manager
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. PMO Admin
                                @endif
                              @elseif($data->id_division == 'MSM')
                                @if($data->id_position == 'MANAGER')
                                  Div. Managed Services & Maintenance
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. MSM Admin
                                @elseif($data->id_position == 'CALL SO')
                                  Staff. Call Center Operator
                                @elseif($data->id_position == 'HELP DESK')
                                  Staff. Dedicated Help Desk
                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                  Dept. Technical Support
                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                  Staff. Support Engineer
                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                  Dept. Services Project Manager
                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                  Staff. Services Project Coordinator
                                @endif
                              @endif
                            @elseif($data->id_division == 'HR')
                              @if($data->id_position == 'HR MANAGER')
                                Div. Human Resource Head
                              @elseif($data->id_position == 'STAFF GA')
                                Staff. General Affair
                              @elseif($data->id_position == 'STAFF HR')
                                Staff. Human Resource
                              @endif
                            @else
                              {{ $data->id_position }}
                            @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec"><i class="fa fa-search"></i>&nbspEdit</button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            <i class="fa fa-trash"></i>&nbspDelete</button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="data_finance" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_division == 'FINANCE')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ucwords(strtolower($data->name))}}</td>
                          @if($data->id_position != '')
                          <td>
                            @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                              President Director
                            @elseif($data->id_division == 'TECHNICAL')
                              @if($data->id_territory == 'DPG')
                                @if($data->id_position == 'ENGINEER MANAGER')
                                  Dept. Implementation Manager
                                @elseif($data->id_position == 'ENGINEER STAFF')
                                  Staff. Systems Engineer
                                @endif
                              @elseif($data->id_territory == 'DVG')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Development Manager
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Dev Ops
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @else
                                @if($data->id_position == 'MANAGER')
                                  Div. Technical Head
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @endif
                            @elseif($data->id_division == 'TECHNICAL PRESALES')
                              @if($data->id_position == 'MANAGER')
                                Dept. Presales Manager
                              @elseif($data->id_position == 'STAFF')
                                Staff. Presales Engineer
                              @endif
                            @elseif($data->id_division == 'SALES')
                              @if($data->id_territory == 'TERRITORY 1')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (First)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM1
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 2')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Second)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM2
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 3')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Third)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM3
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 4')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fourth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM4
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 5')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fifth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM5
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 6')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Sixth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM6
                                @endif
                              @endif
                            @elseif($data->id_division == 'FINANCE')
                              @if($data->id_position != 'FINANCE DIRECTOR')
                                @if($data->id_territory == 'FINANCE')
                                  @if($data->id_position == 'STAFF')
                                    Staff. Finance
                                  @elseif($data->id_position == 'COURIER')
                                    Staff. Courier
                                  @endif
                                @elseif($data->id_territory == 'ACC')
                                  @if($data->id_position == 'MANAGER')
                                    Div. Accounting
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Accounting
                                  @endif
                                @endif
                              @else
                                Finance Director
                              @endif
                            @elseif($data->id_territory == 'OPERATION')
                              @if($data->id_division == null)
                                Operation Director
                              @elseif($data->id_division == 'PMO')
                                @if($data->id_position == 'MANAGER')
                                  Div. Project Management Office
                                @elseif($data->id_position == 'PM')
                                  Staff. Project Manager
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. PMO Admin
                                @endif
                              @elseif($data->id_division == 'MSM')
                                @if($data->id_position == 'MANAGER')
                                  Div. Managed Services & Maintenance
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. MSM Admin
                                @elseif($data->id_position == 'CALL SO')
                                  Staff. Call Center Operator
                                @elseif($data->id_position == 'HELP DESK')
                                  Staff. Dedicated Help Desk
                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                  Dept. Technical Support
                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                  Staff. Support Engineer
                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                  Dept. Services Project Manager
                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                  Staff. Services Project Coordinator
                                @endif
                              @endif
                            @elseif($data->id_division == 'HR')
                              @if($data->id_position == 'HR MANAGER')
                                Div. Human Resource Head
                              @elseif($data->id_position == 'STAFF GA')
                                Staff. General Affair
                              @elseif($data->id_position == 'STAFF HR')
                                Staff. Human Resource
                              @endif
                            @else
                              {{ $data->id_position }}
                            @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec"><i class="fa fa-search"></i>&nbspEdit</button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            <i class="fa fa-trash"></i>&nbspDelete</button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="data_sales" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_division == 'SALES')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ucwords(strtolower($data->name))}}</td>
                          @if($data->id_position != '')
                          <td>
                            @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                              President Director
                            @elseif($data->id_division == 'TECHNICAL')
                              @if($data->id_territory == 'DPG')
                                @if($data->id_position == 'ENGINEER MANAGER')
                                  Dept. Implementation Manager
                                @elseif($data->id_position == 'ENGINEER STAFF')
                                  Staff. Systems Engineer
                                @endif
                              @elseif($data->id_territory == 'DVG')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Development Manager
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Dev Ops
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @else
                                @if($data->id_position == 'MANAGER')
                                  Div. Technical Head
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @endif
                            @elseif($data->id_division == 'TECHNICAL PRESALES')
                              @if($data->id_position == 'MANAGER')
                                Dept. Presales Manager
                              @elseif($data->id_position == 'STAFF')
                                Staff. Presales Engineer
                              @endif
                            @elseif($data->id_division == 'SALES')
                              @if($data->id_territory == 'TERRITORY 1')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (First)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM1
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 2')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Second)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM2
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 3')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Third)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM3
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 4')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fourth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM4
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 5')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Fifth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM5
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'TERRITORY 6')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Account Manager (Sixth)
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Account Executive AM6
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. Admin Sales
                                @endif
                              @elseif($data->id_territory == 'SPECIALIST')
                                @if($data->id_position == 'EXPERT SALES')
                                  Expert Sales
                                @endif
                              @endif
                            @elseif($data->id_division == 'FINANCE')
                              @if($data->id_position != 'FINANCE DIRECTOR')
                                @if($data->id_territory == 'FINANCE')
                                  @if($data->id_position == 'STAFF')
                                    Staff. Finance
                                  @elseif($data->id_position == 'COURIER')
                                    Staff. Courier
                                  @endif
                                @elseif($data->id_territory == 'ACC')
                                  @if($data->id_position == 'MANAGER')
                                    Div. Accounting
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Accounting
                                  @endif
                                @endif
                              @else
                                Finance Director
                              @endif
                            @elseif($data->id_territory == 'OPERATION')
                              @if($data->id_division == null)
                                Operation Director
                              @elseif($data->id_division == 'PMO')
                                @if($data->id_position == 'MANAGER')
                                  Div. Project Management Office
                                @elseif($data->id_position == 'PM')
                                  Staff. Project Manager
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. PMO Admin
                                @endif
                              @elseif($data->id_division == 'MSM')
                                @if($data->id_position == 'MANAGER')
                                  Div. Managed Services & Maintenance
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. MSM Admin
                                @elseif($data->id_position == 'CALL SO')
                                  Staff. Call Center Operator
                                @elseif($data->id_position == 'HELP DESK')
                                  Staff. Dedicated Help Desk
                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                  Dept. Technical Support
                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                  Staff. Support Engineer
                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                  Dept. Services Project Manager
                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                  Staff. Services Project Coordinator
                                @endif
                              @endif
                            @elseif($data->id_division == 'HR')
                              @if($data->id_position == 'HR MANAGER')
                                Div. Human Resource Head
                              @elseif($data->id_position == 'STAFF GA')
                                Staff. General Affair
                              @elseif($data->id_position == 'STAFF HR')
                                Staff. Human Resource
                              @endif
                              @elseif($data->id_territory == 'SPECIALIST')
                              @if($data->id_position == 'EXPERT ENGINEER')
                                Expert Engineer
                              @endif
                            @else
                              {{ $data->id_position }}
                            @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec"><i class="fa fa-search"></i>&nbspEdit</button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            <i class="fa fa-trash"></i>&nbspDelete</button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="operation" role="tabpanel" aria-labelledby="operation-tab">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="data_operation" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_territory == 'OPERATION')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ucwords(strtolower($data->name))}}</td>
                          @if($data->id_position != '')
                          <td>
                              @if($data->id_division == null)
                                Operation Director
                              @elseif($data->id_division == 'PMO')
                                @if($data->id_position == 'MANAGER')
                                  Div. Project Management Office
                                @elseif($data->id_position == 'PM')
                                  Staff. Project Manager
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. PMO Admin
                                @endif
                              @elseif($data->id_division == 'MSM')
                                @if($data->id_position == 'MANAGER')
                                  Div. Managed Services & Maintenance
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. MSM Admin
                                @elseif($data->id_position == 'CALL SO')
                                  Staff. Call Center Operator
                                @elseif($data->id_position == 'HELP DESK')
                                  Staff. Dedicated Help Desk
                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                  Dept. Technical Support
                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                  Staff. Support Engineer
                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                  Dept. Services Project Manager
                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                  Staff. Services Project Coordinator
                                @endif
                              @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <!-- <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec"><i class="fa fa-search"></i>&nbspEdit</button> -->

                            <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec"><i class="fa fa-search"></i>&nbspEdit</button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            <i class="fa fa-trash"></i>&nbspDelete</button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i>&nbsp<b>MSP Employees</b></h3>

        <!-- <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div> -->
      </div>

      <div class="box-body">
      	@if(Auth::User()->email == 'tech@sinergy.co.id')

      	<div class="row">
      		<div class="col-md-12">
      			<div class="pull-right" style="margin-right: 20px">
      				<div class="input-container">
					    <i class="fa fa-search icon"></i>
					    <input class="input-field form-control Search" id="search" name="search" type="text" placeholder="Search..." name="email">
					</div>
      			</div>
      			<div class="nav-tabs-custom active" id="SIP" role="tabpanel" aria-labelledby="sip-tab">
			      	<ul class="nav nav-tabs" id="myTab" role="tablist">
			      		<li class="nav-item active">
				              <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">ALL</a>
				        </li>
			        </ul>
			    </div>
			    <div class="tab-content" id="myTabContentSIP">
		            <div class="tab-pane active" id="all" role="tabpanel" aria-labelledby="all-tab">
		            	@foreach($hr_msp as $data)
		      				@if($data->code_company == 'MSP')
			      			<div class="col-lg-4 col-xs-6" id="alls2">
					            <div class="box box-info">
								  <div class="box-header with-border">
								  	<h3 class="box-title"></h3>
								    <div class="box-tools pull-right">
								      <span class="label label-primary">
								      	@if($data->id_position != '')
				                        {{ ucwords(strtolower($data->id_position)) }}
								      	@else
								      	-
								      	@endif
								      </span>
								    </div>
								    <!-- /.box-tools -->
								  </div>
								  <!-- /.box-header -->
								  <div class="box-body">
								    <div style="float: left">
								    	{{$data->nik}}<br>
								    	{{ucwords(strtolower($data->name))}}
								    </div>

								    <div style="float: right;">
							    	 @if($data->gambar == NULL)
						                <img class="profile-user img-responsive" src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" alt="Yuki" style="width: 100px;height:100px;position: relative;">
						              @elseif($data->gambar != NULL)
						                <img class="profile-user img-responsive" src="{{ asset('image/'.$data->gambar)}}" alt="Yuki" style="width: 100px;height:100px;position: relative;border-radius: 50%">
						              @endif
								    </div>
								    
								  </div>
								  <!-- /.box-body -->
								  <div class="box-footer">
								  	<a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger pull-right margin-left" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
				                    <i class="fa fa-trash"></i>&nbspDelete</button></a>
								    <button class="btn btn-xs btn-primary btn-editan pull-right" value="{{$data->nik}}" name="edit_hurec" style="vertical-align: top; width: 60px"><i class="fa fa-search"></i>&nbspEdit</button>
								  </div>
								  <!-- box-footer -->
								</div>
				          	</div>
				          	@endif
		      		  	@endforeach	
		            </div>

		            <br>

		        </div>	
      		</div>
      	</div>
      @endif
        <div class="pull-right">
          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
          <button class="btn btn-sm btn-primary" style="margin-bottom: 20px" id="btnAdd" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i>&nbsp Employee</button>
          @endif
        </div>
        <ul class="nav" id="myTab" role="tablist"></ul>
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable" id="data_all_msp" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>NIK</th>
                  <th>Employees Name</th>
                  <th>Position</th>
                  @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach($hr_msp as $data)
                @if($data->id_company == '2')
                <tr>
                  <td><?=str_replace('/', '', $data->nik)?></td>
                  <td>{{ $data->name }}</td>
                  @if($data->id_position != '')
                  <td>
                    @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                      President Director
                    @elseif($data->id_division == 'TECHNICAL')
                      @if($data->id_territory == 'DPG')
                        @if($data->id_position == 'ENGINEER MANAGER')
                          Dept. Implementation Manager
                        @elseif($data->id_position == 'ENGINEER STAFF')
                          Staff. Systems Engineer
                        @endif
                      @elseif($data->id_territory == 'DVG')
                        @if($data->id_position == 'MANAGER')
                          Dept. Development Manager
                        @elseif($data->id_position == 'STAFF')
                          Staff. Dev Ops
                        @elseif($data->id_position == 'INTERNAL IT')
                          Staff. Internal IT Engineer
                        @elseif($data->id_position == 'ADMIN')
                          Staff. TEC Admin
                        @endif
                      @elseif($data->id_territory == 'SPECIALIST')
                        @if($data->id_position == 'EXPERT ENGINEER')
                          Expert Engineer
                        @endif
                      @else
                        @if($data->id_position == 'MANAGER')
                          Div. Technical Head
                        @elseif($data->id_position == 'INTERNAL IT')
                          Staff. Internal IT Engineer
                        @elseif($data->id_position == 'ADMIN')
                          Staff. TEC Admin
                        @endif
                      @endif
                    @elseif($data->id_division == 'TECHNICAL PRESALES')
                      @if($data->id_position == 'MANAGER')
                        Dept. Presales Manager
                      @elseif($data->id_position == 'STAFF')
                        Staff. Presales Engineer
                      @endif
                    @elseif($data->id_division == 'SALES')
                      @if($data->id_position == 'MANAGER')
                        Dept. MSP Sales
                      @else
                        Staff. Sales Executive
                      @endif
                    @elseif($data->id_division == 'FINANCE')
                      @if($data->id_position != 'FINANCE DIRECTOR')
                        @if($data->id_territory == 'FINANCE')
                          @if($data->id_position == 'STAFF')
                            Staff. Finance
                          @elseif($data->id_position == 'COURIER')
                            Staff. Courier
                          @endif
                        @elseif($data->id_territory == 'ACC')
                          @if($data->id_position == 'MANAGER')
                            Div. Accounting
                          @elseif($data->id_position == 'STAFF')
                            Staff. Accounting
                          @endif
                        @endif
                      @else
                        Finance Director
                      @endif
                    @elseif($data->id_territory == 'OPERATION')
                      @if($data->id_division == null)
                        Operation Director
                      @elseif($data->id_division == 'PMO')
                        @if($data->id_position == 'MANAGER')
                          Div. Project Management Office
                        @elseif($data->id_position == 'PM')
                          Staff. Project Manager
                        @elseif($data->id_position == 'ADMIN')
                          Staff. PMO Admin
                        @endif
                      @elseif($data->id_division == 'MSM')
                        @if($data->id_position == 'MANAGER')
                          Div. Managed Services & Maintenance
                        @elseif($data->id_position == 'ADMIN')
                          Staff. MSM Admin
                        @elseif($data->id_position == 'CALL SO')
                          Staff. Call Center Operator
                        @elseif($data->id_position == 'HELP DESK')
                          Staff. Dedicated Help Desk
                        @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                          Dept. Technical Support
                        @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                          Staff. Support Engineer
                        @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                          Dept. Services Project Manager
                        @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                          Staff. Services Project Coordinator
                        @endif
                      @endif
                    @elseif($data->id_division == 'HR')
                      @if($data->id_position == 'HR MANAGER')
                        Div. Human Resource Head
                      @elseif($data->id_position == 'STAFF GA')
                        Staff. General Affair
                      @elseif($data->id_position == 'STAFF HR')
                        Staff. Human Resource
                      @endif
                    @elseif($data->id_position == 'ADMIN')
                      Staff. Admin MSP
                    @else
                      {{ $data->id_position }}
                    @endif
                  </td>
                  @else
                  <td>&#8212</td>
                  @endif
                  @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                  <td>
                    <button class="btn btn-xs btn-primary btn-editan" value="{{$data->nik}}" name="edit_hurec" style="width: 60px"><i class="fa fa-search"></i>&nbspEdit</button>

                    <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top; width: 60px" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                    <i class="fa fa-trash"></i>&nbspDelete</button></a>
                  </td>
                  @endif
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalAdd" role="dialog">
	    <div class="modal-dialog modal-md">
	    
	      <!-- Modal content-->
	      <div class="modal-content modal-md">
	        <div class="modal-header">
	          <h4 class="modal-title">Add Employees</h4>
	        </div>
	        <div class="modal-body">
	        <form method="POST" action="{{url('hu_rec/store')}}" enctype="multipart/form-data">
	                        @csrf

	                        <!-- <div class="form-group row" hidden>
	                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK') }}</label>

	                            <div class="col-md-8">
	                                <input id="nik" type="text" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ old('nik') }}" readonly required autofocus>

	                                @if ($errors->has('nik'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('nik') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <div class="form-group row">
	                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Employees Name') }}</label>

	                            <div class="col-md-8">
	                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

	                                @if ($errors->has('name'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('name') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

	                            <div class="col-md-8">
	                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

	                                @if ($errors->has('email'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('email') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

	                            <div class="col-md-7">
	                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} float-left" name="password" required>

	                                @if ($errors->has('password'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('password') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                            <div class="col-md-1">
	                              <i toggle="#password-field" style="margin-right:15px" class="fa fa-fw fa-eye field-icon toggle-password"></i>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

	                            <div class="col-md-8">
	                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

	                            <div class="col-md-8">
	                                <select id="company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ old('company') }}" onkeyup="copytextbox();" required autofocus>
	                                    <option value="">-- Select Company --</option>
	                                    <option value="1" data-target="sip" id="1">SIP</option>
	                                    <option value="2" data-target="msp" id="2">MSP</option>
	                                </select>
	                                @if ($errors->has('company'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('company') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!--SIP-->

	                        <div class="form-group row"  style="display:none;"  id="company-sip">
	                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="division" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_sip" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select division --</option>
	                                    <option value="TECHNICAL" data-target="technical" id="technical">TECHNICAL</option>
	                                    <option value="FINANCE" data-target="finance" id="finance">FINANCE and ACCOUNTING</option>
	                                    <option value="HR" data-target="hr" id="hr">HUMAN RESOURCE</option>
	                                    <option value="SALES" data-target="sales" id="sales">SALES</option>
	                                    <option value="OPERATION" data-target="operation" id="operation">OPERATION</option>
	                                    <option value="SPECIALIST" data-target="specialist" id="specialist">OTHER</option>
	                                    <option value="NULL" data-target="director" id="director">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!--DIRECTOR-->
	                        <div class="form-group row"  style="display:none;"  id="division-director">
	                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-dir" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_dir" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!--expert sales-->
	                        <div class="form-group row"  style="display:none;"  id="division-specialist" >

	                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

	                            <div class="col-md-8">
	                                <select id="territory-expert-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory_expert" value="{{ old('expert_sales') }}" autofocus>
	                                    <option value="">-- Select Territory --</option>
	                                </select>
	                                @if ($errors->has('territory'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('territory') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-expert-sales" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" name="pos_expert_sales" value="{{ old('expert_sales') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                    <option value="EXPERT SALES">EXPERT SALES</option>
	                                    <option value="EXPERT ENGINEER">EXPERT ENGINEER</option>
	                                </select>
	                            </div>
	                        </div>
	                        
	                        <!-- Technical -->
	                        <div class="form-group row"  style="display:none;"  id="division-technical">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-tech" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="DPG" data-target="dvg" id="dvg">IMPLEMENTATION</option>
	                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
	                                    <option value="DVG" data-target="dvg" id="dvg">DEVELOPMENT</option>
	                                    <option value="NONE" data-target="dpg" id="dpg">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-tech" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!-- Sales -->
	                        <div class="form-group row"  style="display:none;"  id="division-sales" >

	                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

	                            <div class="col-md-8">
	                                <select id="territory-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Territory --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                    <option value="MANAGER">MANAGER</option>
	                                    <option value="STAFF">STAFF</option>
	                                    <option value="ADMIN">ADMIN</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!-- Finance -->
	                        <div class="form-group row"  style="display:none;"  id="division-finance">
	                            <label for="division" class="col-md-4 col-form-label text-md-right margin-bottom" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8 margin-bottom">
	                                <select id="subdivision-finance" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_finance" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="FINANCE" data-target="dvg" id="dvg">FINANCE</option>
	                                    <option value="ACC" data-target="dpg" id="dpg">ACCOUNTING</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="division" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-finance" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_finance" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!-- Operation -->
	                        <div class="form-group row"  style="display:none;"  id="division-operation">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-operation" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_operation" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="MSM" data-target="MSM" id="MSM">MSM</option>
	                                    <option value="PMO" data-target="PMO" id="PMO">PMO</option>
	                                    <option value="DIR" data-target="DIR" id="PMO">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="division" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-operation" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_operation" autofocus>
	                                  <option value="">-- Select position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!-- HR -->
	                        <div class="form-group row"  style="display:none;"  id="division-hr">
	                            <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-hr" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_hr" value="{{ old('division') }}" autofocus>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!-- MSP -->

	                        <div class="form-group row"  style="display:none;"  id="company-msp">
	                            <label for="division-msp" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="division-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Division --</option>
	                                    <option value="SALES_MSP" data-target="sales_msp" id="sales_msp">SALES</option>
	                                    <option value="TECHNICAL" data-target="TECHNICAL_MSP" id="TECHNICAL_MSP">TECHNICAL</option>
	                                    <option value="WAREHOUSE_MSP" data-target="sales_msp" id="warehouse_msp">WAREHOUSE</option>
	                                    <option value="OPERATION_MSP" data-target="sales_msp" id="operation_msp">OPERATION</option>
	                                    <option value="ADMIN_MSP" data-target="sales_msp">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row"  style="display:none;"  id="division-msp-sales_msp">
	                          <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-sales-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_msp" value="{{ old('division') }}" autofocus>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>


	                        <div class="form-group row"  style="display:none;"  id="division-msp-TECHNICAL_MSP">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-tech-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_msp" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
	                                    <option value="NONE_MSP" data-target="dpg" id="dpg">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-tech-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_msp" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="date_of_entry" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Entry') }}</label>

	                            <div class="col-md-8">
	                                <input id="date_of_entry" type="date" class="form-control{{ $errors->has('date_of_entry') ? ' is-invalid' : '' }}" name="date_of_entry" value="{{ old('date_of_entry') }}" onkeyup="copytextbox();" required autofocus>

	                                @if ($errors->has('date_of_entry'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('date_of_entry') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Birth') }}</label>

	                            <div class="col-md-8">
	                                <input id="date_of_birth" type="date" class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth" value="{{ old('date_of_birth') }}" onkeyup="copytextbox();" required autofocus>

	                                @if ($errors->has('date_of_birth'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

	                            <div class="col-md-8">
	                                <textarea id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" autofocus></textarea>

	                                @if ($errors->has('address'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('address') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

	                            <div class="col-md-8">
	                                <input id="phone_number" type="number" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" autofocus>

	                                @if ($errors->has('phone_number'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('phone_number') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="no_npwp" class="col-md-4 col-form-label text-md-right">{{ __('NPWP') }}</label>

	                            <div class="col-md-8">
	                                <input id="no_npwp" type="text" class="form-control" name="no_npwp" value="{{ old('no_npwp') }}" autofocus>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <div class="col-md-8">
	                                <img src="http://placehold.it/100x100" id="showgambarnpwp" style="max-width: 400px;max-height: 400px;float: left;"/>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="npwp_file" class="col-md-4 col-form-label text-md-right">{{ __('NPWP File') }}</label>

	                            <div class="col-md-8">
	                                <input id="inputgambarnpwp" type="file" class="form-control" name="npwp_file" value="{{ old('npwp_file') }}" class="validate" autofocus>
	                            </div>
	                        </div>

	                <div class="modal-footer">
	                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                  <button type="submit" class="btn btn-primary">
	                      {{ __('Register') }}
	                  </button>
	                </div>
	          </form>
	        </div>
	      </div>
	    </div>
	</div>

	<div class="modal fade" id="modal_update" role="dialog">
	    <div class="modal-dialog modal-md">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Detail Employees</h4>
	        </div>
	        <div class="modal-body">

	          <form method="POST" action="{{url('hu_rec/update') }}" enctype="multipart/form-data">
	                        @csrf
	                        <div class="form-group row hidden">
	                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK') }}</label>

	                            <div class="col-md-8">
	                                <input id="nik_update" type="text" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik_update" value="{{ old('nik') }}" autofocus>

	                                @if ($errors->has('nik'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('nik') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="name" class="col-md-4 col-form-label">{{ __('Employees Name') }}</label>

	                            <div class="col-md-8">
	                                <input id="name_update" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

	                                @if ($errors->has('name'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('name') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

	                            <div class="col-md-8">
	                                <input id="email_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

	                                @if ($errors->has('email'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('email') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

	                            <div class="col-md-4">
	                            	<input id="company_view_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required readonly>
	                        	</div>

	                            <div class="col-md-4">
	                                <select id="company_update" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company_update" value="{{ old('company') }}" onchange="companySelect(this)" required autofocus>
	                                    <option value="">-- Select Company --</option>
	                                    <option value="1" data-target="sip" id="1">SIP</option>
	                                    <option value="2" data-target="msp" id="2">MSP</option>
	                                </select>
	                                @if ($errors->has('company'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('company') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <!--tampilkan divisi berdasarkan divisi-->
	                        <div class="form-group row">
	                            <label for="divisi" class="col-md-4 col-form-label text-md-right">{{ __('Division') }}</label>

	                            <div class="col-md-4">
	                            	<input id="divisi_view_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required readonly>
	                        	</div>

	                            <div class="col-md-4">
	                                <select id="divisi_update" onchange="divisiSelect(this)" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="divisi_update" value="{{ old('company') }}"  required autofocus>
	                                </select>
	                                @if ($errors->has('company'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('company') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>


	                        <!--tampilkan divisi berdasarkan sub-divisi-->
	                        <div class="form-group row">
	                            <label for="divisi" class="col-md-4 col-form-label text-md-right">{{ __('Sub-Division') }}</label>

	                            <div class="col-md-4">
	                            	<input id="subdivisi_view_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  value="{{ old('email') }}" required readonly>
	                        	</div>

	                            <div class="col-md-4">
	                                <select id="sub_divisi_update" onchange="subdivisiSelect(this)" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="sub_divisi_update" value="{{ old('company') }}" required autofocus>
	                                </select>
	                                @if ($errors->has('company'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('company') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>


	                        <!--tampilkan divisi berdasarkan posisi-->
	                        <div class="form-group row">
	                            <label for="posisi" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-4">
	                            	<input id="posisi_view_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  value="{{ old('email') }}" required readonly>
	                        	</div>

	                            <div class="col-md-4">
	                                <select id="posisi_update" onchange="posisiSelect(this)" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="posisi_update" value="{{ old('company') }}"  required autofocus>
	                                </select>
	                                @if ($errors->has('company'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('company') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>
	                      <!--SIP-->

	                        <!-- <div class="form-group row"  style="display:none;"  id="company_update-sip">
	                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="division_update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select division --</option>
	                                    <option value="TECHNICAL" data-target="technical" id="technical">TECHNICAL</option>
	                                    <option value="FINANCE" data-target="finance" id="finance">FINANCE and ACCOUNTING</option>
	                                    <option value="HR" data-target="hr" id="hr">HUMAN RESOURCE</option>
	                                    <option value="SALES" data-target="sales" id="sales">SALES</option>
	                                    <option value="OPERATION" data-target="operation" id="operation">OPERATION</option>
	                                    <option value="NULL" data-target="director" id="director">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!--DIRECTOR-->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-director">
	                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-dir-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_dir_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->
	                        
	                        <!-- Technical -->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-technical">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-tech-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="DPG" data-target="dvg" id="dvg">IMPLEMENTATION</option>
	                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
	                                    <option value="DVG" data-target="dvg" id="dvg">DEVELOPMENT</option>
	                                    <option value="NONE" data-target="dpg" id="dpg">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-tech-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- Sales -->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-sales" >

	                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

	                            <div class="col-md-8">
	                                <select id="territory-sales-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Territory --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-sales-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                    <option value="MANAGER">MANAGER</option>
	                                    <option value="STAFF">STAFF</option>
	                                    <option value="ADMIN">ADMIN</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- Finance -->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-finance">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-finance-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_finance_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="FINANCE" data-target="dvg" id="dvg">FINANCE</option>
	                                    <option value="ACC" data-target="dpg" id="dpg">ACCOUNTING</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="division" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-finance-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_finance_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- Operation -->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-operation">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-operation-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_operation_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="MSM" data-target="MSM" id="MSM">MSM</option>
	                                    <option value="PMO" data-target="PMO" id="PMO">PMO</option>
	                                    <option value="DIR" data-target="DIR" id="PMO">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="division" class="col-md-4 col-form-label text-md-right margin-top">{{ __('Position') }}</label>

	                            <div class="col-md-8 margin-top">
	                                <select id="position-operation-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_operation_update" autofocus>
	                                  <option value="">-- Select position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- HR -->
	                        <!-- <div class="form-group row"  style="display:none;"  id="division_update-hr">
	                            <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-hr-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_hr_update" value="{{ old('division') }}" autofocus>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->


	                        <!-- MSP -->

	                       <!--  <div class="form-group row"  style="display:none;"  id="company_update-msp">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="division-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Division --</option>
	                                    <option value="SALES_MSP" data-target="sales_msp" id="sales_msp">SALES</option>
	                                    <option value="ADMIN_MSP" >NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-sales-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- <div class="form-group row"  style="display:none;"  id="company_update-msp">
	                            <label for="division-msp" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="division-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Division --</option>
	                                    <option value="SALES_MSP" data-target="sales_msp_update" id="sales_msp">SALES</option>
	                                    <option value="TECHNICAL_MSP" data-target="technical_msp_update" id="TECHNICAL_MSP">TECHNICAL</option>
	                                    <option value="WAREHOUSE_MSP" data-target="sales_msp_update" id="warehouse_msp">WAREHOUSE</option>
	                                    <option value="OPERATION_MSP" data-target="sales_msp_update" id="operation_msp">OPERATION</option>
	                                    <option value="ADMIN_MSP" data-target="sales_msp_update">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- <div class="form-group row"  style="display:none;"  id="division-msp-update-sales_msp_update">
	                          <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-sales-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales" value="{{ old('division') }}" autofocus>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <!-- <div class="form-group row"  style="display:none;"  id="division-msp-update-technical_msp_update">
	                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

	                            <div class="col-md-8">
	                                <select id="subdivision-tech-msp_update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_msp_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Sub Division --</option>
	                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
	                                    <option value="NONE_MSP" data-target="dpg" id="dpg">NONE</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>

	                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

	                            <div class="col-md-8">
	                                <select id="position-tech-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_msp_update" value="{{ old('division') }}" autofocus>
	                                    <option value="">-- Select Position --</option>
	                                </select>
	                                @if ($errors->has('division'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('division') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div> -->

	                        <div class="form-group row">
	                            <label for="date_of_entry" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Entry') }}</label>

	                            <div class="col-md-8">
	                                <input id="date_of_entry_update" type="date" class="form-control{{ $errors->has('date_of_entry') ? ' is-invalid' : '' }}" name="date_of_entry_update" value="{{ old('date_of_entry') }}" onkeyup="copytextbox();" required autofocus>

	                                @if ($errors->has('date_of_entry'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('date_of_entry') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Birth') }}</label>

	                            <div class="col-md-8">
	                                <input id="date_of_birth_update" type="date" class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth_update" value="{{ old('date_of_birth') }}" onkeyup="copytextbox();" required autofocus>

	                                @if ($errors->has('date_of_birth'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

	                            <div class="col-md-8">
	                                <textarea id="address_update" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" autofocus></textarea>

	                                @if ($errors->has('address'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('address') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

	                            <div class="col-md-8">
	                                <input id="phone_number_update" type="number" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" autofocus>

	                                @if ($errors->has('phone_number'))
	                                    <span class="invalid-feedback">
	                                        <strong>{{ $errors->first('phone_number') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>


	                        <div class="form-group row">
	                            <label for="no_npwp" class="col-md-4 col-form-label text-md-right">{{ __('NPWP') }}</label>

	                            <div class="col-md-8">
	                                <input id="no_npwp_update" type="text" class="form-control" name="no_npwp" value="{{ old('no_npwp') }}" autofocus>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <div class="col-md-8">
	                                <img src="" id="showgambarnpwp_update" style="max-width:400px;max-height:400px;float:left;" />
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="npwp_file" class="col-md-4 col-form-label text-md-right">{{ __('NPWP File') }}</label>

	                            <div class="col-md-8">
	                                <input id="inputgambarnpwp_update" type="file" class="form-control" name="npwp_file" value="{{ old('npwp_file') }}" class="validate" autofocus>
	                            </div>
	                        </div>


	                <div class="modal-footer">
	                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                  <button type="submit" class="btn btn-primary">
	                      {{ __('Update') }}
	                  </button>
	                </div>
	          </form>
	        </div>
	      </div>
	      
	    </div>

  	</div>

  	<div class="modal fade" id="modal_edit_status" role="dialog">
  		<div class="modal-dialog modal-md">
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Ubah Status Employees</h4>
	        </div>
		        <div class="modal-body">
		        	<div class="form-group row">
                        <label for="Entry" class="col-md-4 col-form-label text-md-right">{{ __('Mulai Bekerja') }}</label>

                        <div class="col-md-8">
                            <input id="mulai_kerja" type="text" class="form-control" name="mulai_kerja" required>
                        </div>
                    </div>
		        </div>
		    </div>
		</div>
  	</div>
    
  </section>f

  <style type="text/css">
    .margin-left-custom2{
    margin-left: 15px;
    }
    .margin-left-custom3{
      margin-left: 17px;
    }
    hr.new4 {
      border: 0.5px solid #007bff!important;
      margin-top: 40px;
    }

    
  </style>

  @endsection
  @section('script')

  	<script src="{{url('js/pagination.js')}}"></script>
  	<script src="{{url('js/pagination.min.js')}}"></script>
    <script type="">

       $(".modal_edit_status").click(function(){
       	$("#modal_edit_status").modal("show");
       })

       function update_HR(nik,name,email,date_of_entry,date_of_birth,address,phone,no_npwp,npwp_file){
         $("#nik_update").val(nik);
         $("#name_update").val(name);
         $("#email_update").val(email);
         $("#date_of_entry_update").val(date_of_entry);
         $("#date_of_birth_update").val(date_of_birth);
         $("#address_update").val(address);
         $("#phone_number_update").val(phone);
         $("#password_update").val(password);
         $("#no_npwp_update").val(no_npwp);
         $("#inputgambarnpwp_update").val(npwp_file);
       } 

       $('.btn-editan').click(function(){
        $.ajax({
          type:"GET",
          url:"{{url('/hu_rec/get_hu')}}",
          data:{
            id_hu:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
               $("#nik_update").val(value.nik);
               $("#name_update").val(value.name);
               $("#email_update").val(value.email);
               $("#date_of_entry_update").val(value.date_of_entry);
               $("#date_of_birth_update").val(value.date_of_birth);
               $("#address_update").val(value.address);
               $("#phone_number_update").val(value.phone);
               $("#no_npwp_update").val(value.no_npwp);
               $("#showgambarnpwp_update").attr("src","image/"+value.npwp_file);
               $("#password_update").val(value.password);
               $("#divisi_view_update").val(value.id_division);
               $("#subdivisi_view_update").val(value.id_territory);
               if (value.id_company == '1') {
               	$("#company_view_update").val("SIP")
               }else{
               	$("#company_view_update").val("MSP")
               }
               $("#posisi_view_update").val(value.id_position);
               
            });

          }
        }); 
        $("#modal_update").modal("show");
    });

        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        });

        $("#alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert").slideUp(300);
        });

      $(document).ready(function(){
        $('#company').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
            $("#division-director").hide();
            $("#division-specialist").hide();
            $("#division-technical").hide();
            $("#division-sales").hide();
            $("#division-finance").hide();
            $("#division-operation").hide();
            $("#division-hr").hide();
          });
      });

      $(document).ready(function(){
          $('#division').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $(document).ready(function(){
          $('#division-msp').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-dir').html(append)
                var append = "<option > </option>";

                if (result[1] == 'NULL') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-dir').html(append);
              },
          });
      });

      $('#subdivision-tech').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech').html(append)
                var append = "<option> </option>";

                if (result[1] == 'DPG') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PRESALES') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DVG') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-tech').html(append);
              },
          });
      });

      $('#subdivision-finance').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-finance').html(append)
                var append = "<option > </option>";

                if (result[1] == 'FINANCE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ACC') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } 

                $('#position-finance').html(append);
              },
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-expert-sales').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SPECIALIST') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-expert-sales').html(append);
              },
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-sales').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-sales').html(append);
              },
          });
      });

      $('#subdivision-operation').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-operation').html(append)
                var append = "<option > </option>";

                if (result[1] == 'MSM') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PMO') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DIR') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-operation').html(append);
              },
          });
      });


      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-hr').html(append)
                var append = "<option > </option>";

                if (result[1] == 'HR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-hr').html(append);
              },
          });
      });


      $('#division-msp').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-sales-msp').html(append)
                var append = "<option> -- Select Option --</option>";

                if (result[1] == 'SALES_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ADMIN_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'WAREHOUSE_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'OPERATION_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-sales-msp').html(append);
              },
          });
      });

      $('#subdivision-tech-msp').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-msp').html(append)
                var append = "<option> -- Select Option --</option>";

                if (result[1] == 'PRESALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-tech-msp').html(append);
              },
          });
      });

      //update
      $(document).ready(function(){
        $('#company_update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
            $("#division_update-director").hide();
            $("#division_update-technical").hide();
            $("#division_update-sales").hide();
            $("#division_update-operation").hide();
            $("#division_update-hr").hide();
          });
      });

      $(document).ready(function(){
          $('#division_update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $(document).ready(function(){
          $('#division-msp-update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-dir-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'NULL') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-dir-update').html(append);
              },
          });
      });

      $('#subdivision-tech-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-update').html(append)
                var append = "<option> </option>";

                if (result[1] == 'DPG') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PRESALES') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DVG') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-tech-update').html(append);
              },
          });
      });

      $('#subdivision-finance-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-finance-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'FINANCE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ACC') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } 

                $('#position-finance-update').html(append);
              },
          });
      });

      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-sales-update').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-sales-update').html(append);
              },
          });
      });

      $('#subdivision-operation-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-operation-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'MSM') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PMO') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DIR') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-operation-update').html(append);
              },
          });
      });


      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-hr-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'HR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-hr-update').html(append);
              },
          });
      });


       $('#division-msp-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-sales-msp-update').html(append)
                var append = "<option>-- Select Option --</option>";

                if (result[1] == 'SALES_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ADMIN_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'WAREHOUSE_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'OPERATION_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-sales-msp-update').html(append);
              },
          });
      });


       $('#subdivision-tech-msp_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-msp-update').html(append)
                var append = "<option>-- Select Option --</option>";

                if (result[1] == 'PRESALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-tech-msp-update').html(append);
              },
          });
      });
      

      $('#data_all').DataTable( {
        } );

      $('#data_all_msp').DataTable( {
        } );

      $('#data_tech').DataTable( {
        } );

      $('#data_finance').DataTable( {
        } );

      $('#data_sales').DataTable( {
        } );

      $('#data_operation').DataTable( {
        } );


      $(".Search").keyup(function(){
      	var dInput = this.value;
      	var dLength = dInput.length;
    	console.log(dInput);
    	if (dLength < 1) {
    		var value = $(this).val().toLowerCase();
		    $("#all #alls2").filter(function() {
		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		    });

		    @foreach($division as $datas)
			    $("#{{$datas->id_division}} #alls3").filter(function() {
			      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			    });
		    @endforeach
    	}else{
    		// $("#all").empty();
    		var value = $(this).val().toLowerCase();
		    $("#all #alls2").filter(function() {
		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		    });

		    @foreach($division as $datas)
			    $("#{{$datas->id_division}} #alls3").filter(function() {
			      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			    });
		    @endforeach

    	}
      })

      //Pagination
		pageSize = 4;
		incremSlide = 5;
		startPage = 0;
		numberPage = 0;

		var pageCount =  $(".line-content").length / pageSize;
		var totalSlidepPage = Math.floor(pageCount / incremSlide);
		    
		for(var i = 0 ; i<pageCount;i++){
		    $("#pagin").append('<li><a href="#">'+(i+1)+'</a></li> ');
		    if(i>pageSize){
		       $("#pagin li").eq(i).hide();
		    }
		}

		var prev = $("<li/>").addClass("prev").html("Prev").click(function(){
		   startPage-=5;
		   incremSlide-=5;
		   numberPage--;
		   slide();
		});

		prev.hide();

		var next = $("<li/>").addClass("next").html("Next").click(function(){
		   startPage+=5;
		   incremSlide+=5;
		   numberPage++;
		   slide();
		});

		$("#pagin").prepend(prev).append(next);

		$("#pagin li").first().find("a").addClass("current");

		slide = function(sens){
		   $("#pagin li").hide();
		   
		   for(t=startPage;t<incremSlide;t++){
		     $("#pagin li").eq(t+1).show();
		   }
		   if(startPage == 0){
		     next.show();
		     prev.hide();
		   }else if(numberPage == totalSlidepPage ){
		     next.hide();
		     prev.show();
		   }else{
		     next.show();
		     prev.show();
		   }
		   
		    
		}

		showPage = function(page) {
			  $(".line-content").hide();
			  $(".line-content").each(function(n) {
			      if (n >= pageSize * (page - 1) && n < pageSize * page)
			          $(this).show();
			  });        
		}
		    
		showPage(1);
		$("#pagin li a").eq(0).addClass("current");

		$("#pagin li a").click(function() {
			 $("#pagin li a").removeClass("current");
			 $(this).addClass("current");
			 showPage(parseInt($(this).text()));
		});

		function companySelect(id)
		{
			console.log(id.value);
			if (id.value == '1') {
				$('#divisi_update').html(append)
                var append = "<option>-- Select Option --</option>";
                
                append = append + "<option value='TECHNICAL'>" + "TECHNICAL" + "</option>";
                append = append + "<option value='FINANCE'>" + "FINANCE/ACCOUNTING" + "</option>";
                append = append + "<option value='HR'>" + "HUMAN RESOURCE" + "</option>";
                append = append + "<option value='SALES'>" + "SALES" + "</option>";
                append = append + "<option value='OPERATION'>" + "OPERATION" + "</option>";
                append = append + "<option value=''>" + "NONE" + "</option>";

                $("#company_view_update").val("SIP");

                $('#divisi_update').html(append);		
			}else{
				$('#divisi_update').html(append)
                var append = "<option>-- Select Option --</option>";
                
                append = append + "<option value='SALES'>" + "SALES" + "</option>";
                append = append + "<option value='TECHNICAL'>" + "TECHNICAL" + "</option>";
                append = append + "<option value='OPERATION'>" + "OPERATION" + "</option>";
                append = append + "<option value='ADMIN'>" + "NONE" + "</option>";

                $('#divisi_update').html(append);

                $("#company_view_update").val("MSP");
			}
		}

		function divisiSelect(id)
		{
			
			$('#sub_divisi_update').html(append)

            if (id.value == 'TECHNICAL') {
            	var append = "<option>-- Select Option --</option>";
                
                append = append + "<option value='DPG'>" + "IMPLEMENTATION" + "</option>";
                append = append + "<option value='PRESALES'>" + "PRESALES" + "</option>";
                append = append + "<option value='DVG'>" + "DEVELOPMENT" + "</option>";
                append = append + "<option value=''>" + "NONE" + "</option>";

            }else if(id.value == 'FINANCE'){
            	var append = "<option>-- Select Option --</option>";

                append = append + "<option value='FINANCE'>" + "FINANCE" + "</option>";
                append = append + "<option value='ACC'>" + "ACCOUNTING" + "</option>";	
				
			}else if(id.value == 'HR'){
				var append = "<option>-- Select Option --</option>";

        		append = append + "<option value='HR MANAGER'>" + "HR MANAGER" + "</option>";
                append = append + "<option value='STAFF HR'>" + "STAFF HR" + "</option>";
                append = append + "<option value='STAFF GA'>" + "STAFF GA" + "</option>";

			}else if(id.value == 'SALES'){
				var append = "<option>-- Select Option --</option>";
                
                append = append + "<option value='TERRITORY 1'>" + "TERRITORY 1" + "</option>";
                append = append + "<option value='TERRITORY 2'>" + "TERRITORY 2" + "</option>";
                append = append + "<option value='TERRITORY 3'>" + "TERRITORY 3" + "</option>";
                append = append + "<option value='TERRITORY 4'>" + "TERRITORY 4" + "</option>";
                append = append + "<option value='TERRITORY 5'>" + "TERRITORY 5" + "</option>";	
                append = append + "<option value='SALES MSP'>" + "SALES MSP" + "</option>";	

				
			}else if(id.value == 'OPERATION'){
				var append = "<option>-- Select Option --</option>";

				if ($("#company_view_update").val() == 'MSP') {
	                append = append + "<option value='PMO'>" + "PMO" + "</option>";
	                append = append + "<option value='WAREHOUSE'>" + "WAREHOUSE" + "</option>";
	                append = append + "<option value='OPERATION'>" + "NONE" + "</option>";
				}else{
					append = append + "<option value='MSM'>" + "MSM" + "</option>";
	                append = append + "<option value='PMO'>" + "PMO" + "</option>";
	                append = append + "<option value='WAREHOUSE'>" + "WAREHOUSE" + "</option>";
	                append = append + "<option value='OPERATION'>" + "NONE" + "</option>";
				}
				
            }

			$('#sub_divisi_update').html(append);

			$('#posisi_update').html(append)

			if(id.value == ''){
				var append = "<option>-- Select Option --</option>";

        		append = append + "<option value='DIRECTOR'>" + "DIRECTOR" + "</option>";

			}else if(id.value == 'WAREHOUSE'){
				var append = "<option>-- Select Option --</option>";

        		append = append + "<option value='MANAGER'>" + "MANAGER" + "</option>";
                append = append + "<option value='STAFF'>" + "STAFF" + "</option>";

                $("#divisi_view_update").val('');
			}
			$('#posisi_update').html(append);

			$("#divisi_view_update").val(id.value);
		}

		function subdivisiSelect(id){
    		$('#posisi_update').html(append)
            var append = "<option>-- Select Option --</option>";
        	if (id.value == '') {

        		append = append + "<option value='HEAD'>" + "HEAD" + "</option>";
                append = append + "<option value='ADMIN'>" + "ADMIN" + "</option>";

        	}else if(id.value == 'MSM'){

        		append = append + "<option value='MANAGER'>" + "MANAGER" + "</option>";
                append = append + "<option value='ADMIN'>" + "ADMIN" + "</option>";
                append = append + "<option value='SERVICE PROJECT(HEAD)'>" + "SERVICE PROJECT (HEAD)" + "</option>";
                append = append + "<option value='SERVICE PROJECT(STAFF)'>" + "SERVICE PROJECT (STAFF)" + "</option>";
                append = append + "<option value='SUPPORT ENGINEER(HEAD)'>" + "SUPPORT ENGINEER (HEAD)" + "</option>";
                append = append + "<option value='SUPPORT ENGINEER(STAFF)'>" + "SUPPORT ENGINEER (STAFF)" + "</option>";
                append = append + "<option value='HELP DESK'>" + "HELP DESK" + "</option>";
                append = append + "<option value='CALL SO'>" + "CALL SO" + "</option>";

        	}else if (id.value == 'PMO') {

        		append = append + "<option value='MANAGER'>" + "MANAGER" + "</option>";
                append = append + "<option value='PM'>" + "PM" + "</option>";
                append = append + "<option value='ADMIN'>" + "ADMIN" + "</option>";

        	}else if (id.value == 'OPERATION') {

        		append = append + "<option value='DIRECTOR'>" + "DIRECTOR" + "</option>";

        	} else{
            
                append = append + "<option value='MANAGER'>" + "MANAGER" + "</option>";
                append = append + "<option value='STAFF'>" + "STAFF" + "</option>";
                append = append + "<option value='ADMIN'>" + "ADMIN" + "</option>";

            }
            
            $('#posisi_update').html(append);

            $("#subdivisi_view_update").val(id.value);

        }

        function posisiSelect(id){
        	$("#posisi_view_update").val(id.value);
        }

        function readURL(input) {
  		if (input.files && input.files[0]) {
  			var reader = new FileReader();

  			reader.onload = function (e) {
  				$('#showgambarnpwp').attr('src', e.target.result);
  			}

  			reader.readAsDataURL(input.files[0]);
  		}
  	}

  	$("#inputgambarnpwp").change(function () {
  		readURL(this);
  	});

    </script>
  @endsection