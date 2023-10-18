@extends('template.main')
@section('tittle')
PMO
@endsection
@section('head_css')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
  <style type="text/css">
      .select2{
          width:100%!important;
      }
      .selectpicker{
          width:100%!important;
      }

      .dataTables_filter {
        display: none;
      }

      textarea{
        resize: vertical;
      }

      input[type=number] {
        -moz-appearance: textfield;
      }

      input[type=file]::-webkit-file-upload-button{
       display: none;
      }

      #tableUploadDoc td{
        padding-right: 0px!important;
        padding-left: 8px!important;
      }

      #tbInternalStakeholderRegister tr td select, #tbInternalStakeholderRegister tr td input{
        width: 140px!important;
        font-size: 12px;
      }

      label > input[name="cbImpelementType"]{
        content: "";
        display: inline-block;
        vertical-align: bottom;
        width: 18px;
        height: 18px;
        margin-right: 0.3rem;
        border-radius: 0%;
        border-style: solid;
        border-width: 0.1rem;
        border-color: #d2d6de!important;
        flex-shrink: 0;
        cursor: pointer;
      }

      .select2-selection__rendered, input[type=number], input[type=text], input[type=file], textarea.form-control { 
        font-size: 13px;
      }
  </style>
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" type="text/css" href="{{asset('/plugins/iCheck/all.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            PMO Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PMO Dashboard</li>
        </ol><br>
        <div class="alert alert-warning alert-dismissible" id="alert" style="display:none">
			<h4><i class="icon fa fa-warning"></i> Alert!</h4>
			Please upload your sign on <a href="{{url('/profile_user')}}" target='_blank' style='color:navy!important'>profile page</a> first, for enable project charter button!
		</div>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3></h3>
                <div class="box-tools">
                    <button class="btn btn-sm bg-purple" id="btnAssign" style="display:none" onclick="AssignMember()"><i class="fa fa-plus"></i>&nbspAssign</button>
                </div>
            </div>

            <div class="box-body">
              <div class="row">
                <div class="col-md-4 pull-right" id="search-table">
                  <div class="input-group" style="margin-left: 10px">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Show 10 entries
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#" onclick="changeNumberEntries('tbListProject',10)">10</a></li>
                        <li><a href="#" onclick="changeNumberEntries('tbListProject',25)">25</a></li>
                        <li><a href="#" onclick="changeNumberEntries('tbListProject',50)">50</a></li>
                        <li><a href="#" onclick="changeNumberEntries('tbListProject',100)">100</a></li>
                      </ul>
                    </div>
                    <input id="searchBarList" type="text" class="form-control" placeholder="Search Anything">
                    <span class="input-group-btn">
                      <button id="applyFilterTableSearch" type="button" class="btn btn-default btn-md" style="width: 40px">
                        <i class="fa fa-fw fa-search"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" id="tbListProject">
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="ModalAssign" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Assign Project</h4>
          </div>
          <div class="modal-body">
            <form action="" id="modal_Assign" name="modal_Assign">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Project ID*</label>
                    <select id="selectPIDAssign" name="selectPIDAssign" class="select2 form-control" onchange="validationCheck(this)" required>
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please select Project ID!</span>
                  </div> 
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Project Title*</label>
                    <input autocomplete="off" type="text" class="form-control" placeholder="Project Title" id="inputProjectTitle" name="inputProjectTitle" onkeyup="validationCheck(this)" readonly>
                    <span class="help-block" style="display:none;">Please fill Project Title!</span>
                  </div> 
                </div>
              </div>
              <div class="form-group">
                <label>Project Type*</label>
                  <div style="padding:10px;border:solid 1px #cccc;">
                    <label style="margin-right: 15px;"><input onchange="validationCheck(this)" autocomplete="off" type="checkbox" name="cbProjectType" class="minimal" id="" value="supply_only">&nbspSupply Only</label>
                    <label style="margin-right: 15px;"><input onchange="validationCheck(this)" autocomplete="off" type="checkbox" name="cbProjectType" class="minimal" id="" value="implementation">&nbspImplementation</label>
                    <label><input onchange="validationCheck(this)" autocomplete="off" type="checkbox" name="cbProjectType" class="minimal" id="" value="maintenance">&nbspMaintenance & Managed Service</label>
                  </div>
                <span class="help-block" style="display:none;">Please Select Project Type!</span>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Project Manager*</label>
                    <select id="selectPM" name="selectPM" class="select2 form-control" onchange="validationCheck(this)">
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please select Project Manager!</span>
                  </div> 
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Project Coordinator</label>
                    <select id="selectPC" name="selectPC" class="select2 form-control" onchange="validationCheck(this)" disabled>
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please Select Project Coordinator!</span>
                  </div> 
                </div>
              </div>
                           
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn bg-purple" onclick="saveAssign()">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ModalProjectCharter" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Customer Information</h4>
          </div>
          <div class="modal-body">
            <form action="" id="modal_project_charter" name="modal_project_charter">
            @csrf
              <div class="tab-add" style="display:none;">
                <div class="tabGroup">
                  <div class="form-group">
                    <label for="">Customer*</label>
                    <input autocomplete="off" type="text" class="form-control" placeholder="Customer" id="inputCustomer" name="inputCustomer" onkeyup="validationCheck(this)">
                    <span class="help-block" style="display:none;">Please fill Customer!</span>
                  </div> 

                  <div class="form-group">
                    <label for="">Address*</label>
                    <textarea class="form-control" placeholder="Address" id="textAreaAddress" name="textAreaAddress" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Customer Address!</span>
                  </div> 

                  <div class="form-group">
                    <label for="">Phone*</label>
                    <input autocomplete="off" type="number" class="form-control" placeholder="Customer Phone" id="inputPhone" name="inputPhone" onkeyup="validationCheck(this)">
                    <span class="help-block" style="display:none;">Please fill Customer Phone!</span>
                  </div>    

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="">Contact Person*</label>
                        <input autocomplete="off" class="form-control" id="inputContactPerson" type="text" name="inputContactPerson" placeholder="Contact Person" onkeyup="validationCheck(this)">
                        <span class="help-block" style="display:none;">Please fill Contact Person!</span>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="">Email*</label>
                        <input autocomplete="off" type="email" class="form-control" placeholder="Email" name="inputEmail" id="inputEmail" onkeyup="validationCheck(this)">
                        <span class="help-block" style="display:none;">Please fill Email!</span>
                      </div>
                    </div>
                  </div>  

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="">CP Phone*</label>
                        <input autocomplete="off" class="form-control" id="inputCpPhone" type="number" name="inputCpPhone" placeholder="Contact Person Phone" onkeyup="validationCheck(this)">
                        <span class="help-block" style="display:none;">Please fill Contact Person Phone!</span>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="">CP Title*</label>
                        <input autocomplete="off" type="text" class="form-control" placeholder="Contact Person Title" name="inputCpTitle" id="inputCpTitle" onkeyup="validationCheck(this)">
                        <span class="help-block" style="display:none;">Please fill Contact Person Title!</span>
                      </div>
                    </div>
                  </div> 

                  <div class="form-group">
                    <label>Customer Company's Logo*</label>
                    <input type="file" name="inputCompanyLogo" id="inputCompanyLogo" class="form-control files" onchange="validationCheck(this,'logo')">
                    <span class="help-block" style="display:none;">Please upload company's logo</span>
                  </div>
                </div>
              </div>
              <div class="tab-add" style="display:none">
                <div class="tabGroup">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Project ID Number*</label>
                        <select autocomplete="off" type="text" name="inputPID" class="form-control" id="inputPID" placeholder="" onkeyup="validationCheck(this)">
                          <option/>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>PO/SPK Number*</label> 
                        <input readonly type="text" name="inputPoNumber" id="inputPoNumber" class="form-control" placeholder="PO/SPK Number">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Project Name*</label>
                    <input readonly autocomplete="off" placeholder="Project Name" type="text" name="inputProjectName" class="form-control" id="inputProjectName">
                  </div>

                  <div class="form-group">
                    <label>Project Type*</label>
                      <div style="padding:10px;border:solid 1px #cccc;background-color: #eee;">
                        <label style="margin-right: 15px;"><input readonly autocomplete="off" type="checkbox" value="supply_only" name="cbProjectTypeAddProjectCharter" class="minimal" id=""> Supply Only</label>
                        <label style="margin-right: 15px;"><input readonly autocomplete="off" type="checkbox" value="implementation" name="cbProjectTypeAddProjectCharter" class="minimal" id=""> Implementation</label>
                        <label><input readonly autocomplete="off" type="checkbox" name="cbProjectTypeAddProjectCharter" value="maintenance" class="minimal" id=""> Maintenance & Managed Service</label>
                      </div>
                    <!-- <span class="help-block" style="display:none;">Please Select Project Type!</span> -->
                  </div>

                  <div class="form-group">
                    <label>Project Owner*</label>
                    <input readonly autocomplete="off" type="text" placeholder="Project Owner" name="inputProjectOwner" class="form-control" id="inputProjectOwner">
                    <span class="help-block" style="display:none;">Please fill Project Owner!</span>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Project Manager*</label>
                        <select class="form-control" id="selectPmProjectCharter" readonly></select>
                        <span class="help-block" style="display:none;">Please fill Project Manager!</span>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Project Coordinator*</label>
                        <select class="form-control" id="selectPcProjectCharter" readonly></select>
                        <span class="help-block" style="display:none;">Please fill Project Coordinator!</span>
                      </div> 
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Project Description*</label>
                    <textarea  autocomplete="off" type="number" name="textAreaProjectDesc" class="form-control" id="textAreaProjectDesc" placeholder="Project Description" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Project Description!</span>
                  </div> 

                  <div class="form-group">
                    <label>Project Objectives*</label>
                    <textarea  autocomplete="off" type="number" name="textAreaProjectObj" class="form-control" id="textAreaProjectObj" placeholder="Project Objectives" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Project Objective!</span>
                  </div> 

                  <div class="form-group">
                    <label>Technology Use*</label>
                      <div class="col-md-12 col-xs-12" style="border:solid 1px #cccc;padding-left:5px!important;padding: 10px;margin-bottom: 15px;">
                        <div class="col-md-3 col-xs-12" style="padding-left:5px!important;">
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Data Center" class="minimal form-control" id="" onclick="validationCheck(this)"> Data Center</label>
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Security" class="minimal form-control" id="" onclick="validationCheck(this)"> Security</label><br>
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="IoT" class="minimal form-control" id="" onclick="validationCheck(this)"> IoT</label>
                          
                        </div>
                        <div class="col-md-5 col-xs-12" style="padding-left:5px!important">   
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="ATM/CRM" class="minimal form-control" id="" onclick="validationCheck(this)"> ATM/CRM</label>
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Application Development" class="minimal form-control" id="" onclick="validationCheck(this)"> Application Development</label>
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Cloud Computing" class="minimal form-control" id="" onclick="validationCheck(this)"> Cloud Computing</label>
                        </div>
                        <div class="col-md-4 col-xs-12" style="padding-left:5px!important;">
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Borderless Network" class="minimal form-control" id="" onclick="validationCheck(this)"> Borderless Network</label>
                          <label><input autocomplete="off" type="checkbox" name="cbTechUse" value="Collaboration" class="minimal form-control" id="" onclick="validationCheck(this)"> Collaboration</label>
                          
                        </div>
                      </div>
                    <span class="help-block" style="display:none;">Please Select Technology Use!</span>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                          <label>Estimated Start Date*</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-calendar" style="display:inline"></i></span>
                              <input type="text" name="inputStartDate" id="inputStartDate" placeholder="Select Start Date" class="form-control" style="display:inline" onchange="validationCheck(this)">
                          </div>
                        <span class="help-block" style="display:none;">Please Select Start Date!</span>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Estimated Finish Date*</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar" style="display:inline"></i></span>
                            <input type="text" name="inputFinishDate" id="inputFinishDate" placeholder="Select Finish Date" class="form-control" style="display:inline" onchange="validationCheck(this)">
                        </div>
                        <span class="help-block" style="display:none;">Please Select Finish Date!</span>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Flexibility*</label>
                        <select name="selectFlexibility" id="selectFlexibility" class="form-control" placeholder="Select Flexibility" onchange="validationCheck(this)">
                          <option value="Flexible">Flexible</option>
                          <option value="TightSchedule">Tight Schedule</option>
                        </select>
                        <span class="help-block" style="display:none;">Please Select Flexibility!</span>

                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Market Segment*</label>
                    <select name="selectMarketSegment" id="selectMarketSegment" class="form-control" placeholder="Select Market Segment" onchange="validationCheck(this)">
                      <option value="Finance / Banking">Finance / Banking</option>
                      <option value="Government">Government</option>
                      <option value="Power / Energy">Power / Energy</option>
                      <option value="Telecommunication">Telecommunication</option>
                      <option value="System Integrator">System Integrator</option>
                      <option value="Expedition / Courier">Expedition / Courier</option>
                      <option value="Transportation">Transportation</option>
                      <option value="Insurance">Insurance</option>
                      <option value="Cement / Building">Cement / Building</option>
                      <option value="Stock / Exchange">Stock / Exchange</option>
                      <option value="Education">Education</option>
                    </select>
                    <span class="help-block" style="display:none;">Please Select Market Segment!</span>

                  </div>
              
                </div>
              </div> 
              <div class="tab-add" style="display:none">
                <div class="tabGroup">
                  <div class="form-group">
                    <label>Scope of Work*</label>
                    <textarea class="form-control" id="textAreaSOW" name="textAreaSOW" placeholder="Scope of Work" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Scope of Work!</span>
                  </div>

                  <div class="form-group">
                    <label>Out Of Scope*</label> 
                    <textarea class="form-control" id="textAreaOutOfScope" name="textAreaOutOfScope" placeholder="Out of Scope" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Out of Scope!</span>
                  </div>

                  <div class="form-group">
                    <label>Customer Requirement*</label> 
                    <textarea class="form-control" id="textAreaCustomerRequirement" name="textAreaCustomerRequirement" placeholder="Customer Requirement" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Customer Requirement!</span>
                  </div>

                  <div class="form-group">
                    <label>Term of Payment*</label>
                    <textarea class="form-control" id="textAreaTOP" name="textAreaTOP" placeholder="Term of Payment" onkeyup="validationCheck(this)"></textarea>
                    <span class="help-block" style="display:none;">Please fill Term of Payment!</span>
                  </div>

                  <div class="form-group">
                    <label>Internal Stakeholder Register*</label>
                    <div class="table-responsive">
                      <table class="table" id="tbInternalStakeholderRegister" style="width:100%">
                        <thead>
                          <tr>
                            <th width="30%">Name</th>
                            <th width="25%">Role</th>    
                            <th width="25%">Email</th>    
                            <th width="20%">Phone</th>    
                            <th><button type="button" onclick="btnPlusIStakeholder(0)" style="background-color: transparent;border: none;"><i class="fa fa-plus" style="color:#3c8dbc"></i></button></th>
                          </tr>
                        </thead>
                        <tbody id="tbodyInternalStakeholderRegister"></tbody>
                      </table>
                    </div>
                    
                    <!-- <textarea class="form-control" id="textAreaStakeholder" name="textAreaStakeholder" placeholder="Stakeholder Register"></textarea> -->
                    <span class="help-block" style="display:none;">Please fill Internal Stakeholder Register!</span>
                  </div>
              
                </div>
              </div> 
              <div class="tab-add" style="display:none">
                  <div class="tabGroup">
                    <table class="table " style="width:100%;border-collapse: separate;border-spacing: 0;" >
                      <thead>

                      </thead>
                      <tbody id="tbodyIdentifiedRisk">
                        <tr id="firstTr">
                          <td>
                            <div class="form-group">
                              <label>Risk Description*</label>
                              <textarea class="form-control" id="textAreaRisk" name="textAreaRisk" placeholder="Risk Description" data-value="0" onkeyup="validationCheck(this)"></textarea>
                              <span class="help-block" style="display:none;">Please fill Risk Description!</span>
                            </div>
                            <div class="row">
                              <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                  <label>Owner*</label> 
                                  <input type="text" class="form-control" id="inputOwner" name="inputOwner" placeholder="Owner" data-value="0" onkeyup="validationCheck(this)"/>
                                  <span class="help-block" style="display:none;">Please fill Owner!</span>
                                </div>
                              </div>
                              <div class="col-md-3 col-xs-12">
                                <div class="form-group">
                                  <label>Impact*&nbsp<i style="color:#f39c12;" class="fa fa-info-circle help-btn-impact" value="impact"></i></label> 
                                  <input max="5" min="1" type="number" class="form-control" id="inputImpact" name="inputImpact" placeholder="1-5" data-value="0" onkeyup="validationCheck(this)"/>
                                  <span class="help-block" style="display:none;">Please fill Impact!</span>
                                </div>
                              </div>
                              <div class="col-md-3 col-xs-12">
                                <div class="form-group">
                                  <label>Likelihood*&nbsp<i style="color:#f39c12;" class="fa fa-info-circle help-btn-likelihood" value="likelihood"></i></label> 
                                  <input max="5" min="1" type="number" class="form-control" id="inputLikelihood" name="inputLikelihood" placeholder="1-5" data-value="0" onkeyup="validationCheck(this)"/>
                                  <span class="help-block" style="display:none;">Please fill Likelihood!</span>
                                </div>
                              </div>
                            </div>
                   <!--          <div class="form-group">
                              <label>Rank*</label>
                              <input class="form-control" placeholder="Rank" id="inputRank" name="inputRank" data-value="0" onkeyup="validationCheck(this)"/>
                              <span class="help-block" style="display:none;">Please fill Rank!</span>
                            </div>
                            <div class="form-group">
                              <label>Description*</label>
                              <textarea class="form-control" placeholder="Description" id="textareaDescription" name="textareaDescription" data-value="0" onkeyup="validationCheck(this)"></textarea>
                              <span class="help-block" style="display:none;">Please fill Description!</span>
                            </div> -->
                            <div class="row">
                              <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Response*</label> 
                                  <textarea class="form-control" placeholder="Response" id="textareaResponse" name="textareaResponse" data-value="0" onkeyup="validationCheck(this)"></textarea>
                                  <span class="help-block" style="display:none;">Please fill Response!</span>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                  <label>Due Date*</label>
                                  <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="due_date" class="form-control" placeholder="Select Due Date" id="due_date" data-value="0" onkeyup="validationCheck(this)"/>
                                  </div>
                                  <span class="help-block" style="display:none;">Please fill Due Date!</span>
                                </div>
                              </div>
                              <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                  <label>Review Date*</label>
                                  <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="review_date" class="form-control" id="review_date" placeholder="Select Review Date" data-value="0" onkeyup="validationCheck(this)"/>
                                  </div>
                                  <span class="help-block" style="display:none;">Please fill Review Date!</span>
                                </div>
                              </div>
                              <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                  <label>Status*</label>
                                  <select class="form-control select2" id="selectStatusProjectCharter" name="selectStatusProjectCharter" data-value="0" onkeyup="validationCheck(this)">
                                    <option></option>
                                  </select>
                                  <span class="help-block" style="display:none;">Please fill Status!</span>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group" style="display: flex;margin-top: 10px;">
                    <button type="button" id="btnAddIdentifiedRisk" style="margin:0 auto" class="btn btn-sm bg-purple"><i class="fa fa-plus"></i>&nbsp Identified Risk</button>
                  </div>
              </div>
              <div class="tab-add" style="display:none">
                <div class="tabGroup">
                  <div class="form-group">
                    <label>PO/SPK/PKS*</label>
                    <input type="file" class="form-control document" name="inputPO" id="inputPO" onchange="validationCheck(this)">
                    <span class="help-block" style="display:none;">Please Fill Document PO/SPK/PKS!</span>

                    <i class="fa fa-folder" style="display:none;color: #3c8dbc;"></i>&nbsp<a style="display:none" href="" target="_blank" id="link_input_po" name="link_input_po"></a>
                  </div>

                  <div class="form-group">
                    <label>ToR/RKS*</label>
                    <input type="file" class="form-control document" name="inputToR" id="inputToR" onchange="validationCheck(this)">
                    <span class="help-block" style="display:none;">Please Fill Document ToR/RKS!</span>

                    <i class="fa fa-folder" style="display:none;color: #3c8dbc;"></i>&nbsp<a href="" style="display:none" id="link_input_tor" name="link_input_tor" target="_blank"></a>
                  </div>

                  <div class="form-group">
                    <label>SBE*</label>
                    <input type="file" class="form-control document" name="inputSbe" id="inputSbe" onchange="validationCheck(this)">
                    <span class="help-block" style="display:none;">Please Fill Document SBE!</span>

                    <i class="fa fa-folder" style="display:none;color: #3c8dbc;"></i>&nbsp<a href="" style="display:none" id="link_input_sbe" name="link_input_sbe" target="_blank"></a>
                  </div>

                  <div class="table-responsive">
                  <table id="tableUploadDoc" class="table" style="width:100%;white-space: nowrap;">
                    <tbody>
                      <!-- <tr class="trDoc"> -->
                      <!--   <td>
                          <div style="padding:5px;border:solid 1px #cccc;width: 300px;">
                            <label for="inputDoc_0" class="fa fa-upload" id="title_doc_0" data-value="0">&nbsp; <span>Upload Document</span>
                              <input type="file" name="inputDoc" id="inputDoc_0" data-value="0" style="display: none;">
                            </label>
                          </div>
                        </td>
                        <td>
                          <input id="inputDocTitle_0" data-value="0" type="" name="" class="form-control" style="width: 200px;">
                        </td>
                      </tr> -->
                    </tbody>
                  </table>
                  </div>
                  <div class="form-group" style="display: flex;margin-top: 20px;">
                    <button type="button" id="btnAddDoc" style="margin:0 auto" class="btn btn-sm bg-purple" onclick="addDocPendukung()"><i class="fa fa-plus"></i>&nbsp Document</button>
                  </div> 
                </div>
              </div>              
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="prevBtnAdd">Cancel</button>
                <button type="button" class="btn bg-purple" id="nextBtnAdd">Next</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      console.log(item)
      $("#" + item).show()
    })

    $(document).ready(function(){
      // history.pushState(null, null, window.location.href);
      // history.back();
      // window.onpopstate = () => history.forward();
      // setTimeout("preventBack()", 0);
      // window.onunload = function () { null };
    })

    // function preventBack() {
    //     window.history.forward();
    // }

    $('input[class="document"]').change(function(){
      console.log("okeee")
      var f=this.files[0]
      var filePath = f;
   
      // Allowing file type
      var allowedExtensions =
      /(\.pdf)$/i;

      var ErrorText = []
      // 
      if (f.size > 30000000|| f.fileSize > 30000000) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid file size, just allow file with size less than 30MB!',
        }).then((result) => {
          this.value = ''
        })
      }

      var ext = filePath.name.split(".");
      ext = ext[ext.length-1].toLowerCase();      
      var arrayExtensions = ["pdf"];

      if (arrayExtensions.lastIndexOf(ext) == -1) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid file type, just allow pdf file',
        }).then((result) => {
          this.value = ''
        })
      }
    })

    $('input[class="files"]').change(function(){
        var f=this.files[0]
        var filePath = f;
     
        // Allowing file type
        var allowedExtensions =
        /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

        var ErrorText = []
        // 
        if (f.size > 30000000|| f.fileSize > 30000000) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file size, just allow file with size less than 30MB!',
          }).then((result) => {
            this.value = ''
          })
        }

        var ext = filePath.name.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file type, just allow png/jpg file',
          }).then((result) => {
            this.value = ''
          })
        }
      }) 

    
    var table = $('#tbListProject').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
          url:"{{url('/PMO/getListDataProject')}}",
          dataSrc:"data",
        },
        "bFilter": true,
        "bSort":true,
        "bLengthChange": false,
        "bInfo": false,
        "columns": [
          {
            title: "No",
            render:function(data,type,row,meta)
            { 
              return ++meta.row 
            },
          },
          {
            title: "Project ID",
            data: "project_id"
          },
          {
            title: "Name Project",
            data: "name_project",
            render:function(data, type, row)
            {
              let warning = ''

              if (row.status == 'Reject') {
                if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                  warning = '<br><small style="color:red">Please wait update from PM/PC, this project charter has been rejected</small>'
                }else{
                  warning = ''
                }
              }else{
                if (row.project_type != 'supply_only') {
                  if(row.status == 'New'){
                    if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Staff')->exists()}}" || "{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Project Coordinator')->exists()}}") {
                      warning = '<br><small style="color:red">Project Charter will processed soon, please wait for further progress</small>'
                    }else{
                      warning = ''
                    }
                  }
                }else{
                  warning = ''
                }
              }

              if (row.sign != "-") {
                return row.name_project + "<br><small class='label label-info'>next approver of Project Charter on " + row.sign + "</small>" + warning 
              }else{
                return row.name_project + warning 
              }
            },
          },
          {
            title: "Project Type",
            render:function(data,type,row)
            {
              return row.type_project
              // if (row.project_type == 'maintenance') {
              //   return 'Maintenance & Managed Service'
              // }else if (row.project_type == 'supply_only') {
              //   return 'Supply Only'
              // }else{
              //   return row.project_type.charAt(0).toUpperCase() +  row.project_type.slice(1)
              // }
            },
          },
          {
            title: "PM/PC",
            render:function(data,type,row)
            {
              // return row.type_project
              if (row.project_type == 'maintenance') {
                return row.project_pc
              }else if (row.project_type == 'supply_only' || row.project_type == 'implementation') {
                return row.project_pm
              }
            },
          },
          {
              "title":"Status",
              "data": null,
              render:function(data, type, row)
              {
                if (row.current_phase == 'New') {
                  return '<label class="label label-info">New</label>'
                }else if (row.current_phase == 'Reject') {
                  return '<label class="label label-danger">Reject</label>'
                }else if (row.current_phase == 'Initiating') {
                  return '<label class="label label-primary">Initiating</label>'
                }else if (row.current_phase == 'Planning') {
                  return '<label class="label label-warning">Planning</label>'
                }else if (row.current_phase == 'Executing') {
                  return '<label class="label label-danger">Executing</label>'
                }else if (row.current_phase == 'Closing') {
                  return '<label class="label label-success">Closing</label>'
                }else{
                  return '<label class="label label-success">'+ row.current_phase +'</label>'
                }
              },
          },
          {
            "title":"Project Indicator",
            render:function(data, type, row)
            {
              if (row.project_type == "supply_only") {
                return " - "
              }else{
                if (row.indicator_project == "-") {
                  return "TBA"
                }else{
                  if (row.indicator_project == "onTrack") {
                    return '<span><i class="fa fa-circle" style="color:#257a33"></i>&nbspon Track</span>'
                  }else if (row.indicator_project == "delay") {
                    return '<span><i class="fa fa-circle" style="color:#b52f2f"></i>&nbspDelay</span>'
                  }else{
                    return '<span><i class="fa fa-circle" style="color:#faea39"></i>&nbspMight Delay</span>'
                  }
                }
              }                
            }
          },
          {
              "title":"Action",
              "data": null,
              render:function(data, type, row)
              {
                if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.group','sales')->exists()}}" || "{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','BCD Manager')->exists()}}" || "{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','Operations Director')->exists()}}" || "{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','President Director')->exists()}}") {
                    if (row.current_phase == 'New') {
                      if (row.project_type == 'supply_only') {
                        return '<button class="btn btn-sm bg-purple disabled" style="width:110px"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                      }else{
                        if (row.status == 'Approve') {
                          	if ("{{Auth::User()->ttd}}" == "") {
                          		$("#alert").show()
                          		$("button[name='btnShowProjectCharter']").prop("disabled",true)
                          		// $("button[name='btnShowProjectCharter']").attr("title","Please upload your sign on profile page first, for show this project charter!")
                          		// console.log("{{Auth::User()->ttd_digital}}")
                          	}

                          	return '<button class="btn btn-sm btn-primary" style="width:110px" id="btnShowProjectCharter" name="btnShowProjectCharter" onclick="btnShowProjectCharter('+ "'" + row.id + "'" +')"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        }else if (row.status == 'Reject') {
                          return '<button class="btn btn-sm btn-danger disabled" style="width:110px;"><i class="fa fa-wrench"></i>&nbsp Revision</button>'                            
                        }else if (row.status == 'Done'){
                          return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                        }else{
                          if (row.type_project == "Implementation + Maintenance & Managed Service") {
                            return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                          }else{
                            return '<button class="btn btn-sm btn-primary disabled" style="width:110px" id="btnShowProjectCharter" name="btnShowProjectCharter"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                          }
                          
                        }
                      }
                    }else if(row.current_phase == "Waiting"){
                      return '<button class="btn btn-sm bg-purple" style="width:110px" disabled><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                    }else{
                      return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                    }
                    // return 'okee'
                }else{
                  if (row.current_phase == 'New') {
                    if (row.type_project == 'Supply Only') {
                      if (row.status == null) {
                        return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button><button id="btnDeleteAssign" name="btnDeleteAssign" onclick="deleteAssign('+ "'" + row.id + "'" +')" class="btn btn-sm btn-danger" style="width:110px;display:none"><i class="fa fa-trash"></i> Delete</button>'
                      }else{
                        return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                      }
                    }else{
                      if (row.status == null) {
                          	if ("{{Auth::User()->ttd}}" == "") {
                          		$("#alert").show()
                          		$("button[name='btnAddProjectCharter']").prop("disabled",true)
                          		$("button[name='btnAddProjectCharter']").attr("title","Please upload your sign on profile page first, for enable this project charter button!")
                          	}
                          		console.log("{{Auth::User()->ttd}}" == "")

                        		if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                              if (row.type_project == "Implementation + Maintenance & Managed Service") {
                                if (row.project_type == "maintenance") {
                                    return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                                  }else{
                                    return '<button class="btn btn-sm btn-primary" style="width:110px" id="btnAddProjectCharter" name="btnAddProjectCharter" disabled><i class="fa fa-plus"></i>&nbsp Project Charter</button><button id="btnDeleteAssign" name="btnDeleteAssign" onclick="deleteAssign('+ "'" + row.id + "'" +')" class="btn btn-sm btn-danger" style="width:110px;display:none"><i class="fa fa-trash"></i> Delete</button>'
                                  }
                                }else{
                                 return '<button class="btn btn-sm btn-primary" style="width:110px" id="btnAddProjectCharter" name="btnAddProjectCharter" disabled><i class="fa fa-plus"></i>&nbsp Project Charter</button><button id="btnDeleteAssign" name="btnDeleteAssign" onclick="deleteAssign('+ "'" + row.id + "'" +')" class="btn btn-sm btn-danger" style="width:110px;display:none"><i class="fa fa-trash"></i> Delete</button>'
                                }
                        		}else{
                                return '<button class="btn btn-sm btn-primary" style="width:110px" id="btnAddProjectCharter" name="btnAddProjectCharter" onclick="btnAddProjectCharter(0,' + "'" + row.id + "'" +','+ "'create'" +')"><i class="fa fa-plus"></i>&nbsp Project Charter</button>'
                          		
                        	}  
                      }else if(row.status == 'New'){
                        	if ("{{Auth::User()->ttd}}" == "") {
                          	$("#alert").show()
                        		$("button[name='btnShowProjectCharter']").prop("disabled",true)
                        		$("button[name='btnShowProjectCharter']").attr("title","Please upload your sign on profile page first, for show this project charter!")
                        	}

                        	if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                          	return '<button class="btn btn-sm btn-primary" style="width:110px" id="btnShowProjectCharter" name="btnShowProjectCharter" onclick="btnShowProjectCharter('+ "'" + row.id + "'" +')"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        	}else{
                          	return '<button class="btn btn-sm btn-primary disabled" style="width:110px" id="btnAddProjectCharter" 	name="btnAddProjectCharter"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        	}                          
                      }else if(row.status == 'Reject'){
                        if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                          return '<button class="btn btn-sm btn-danger disabled" style="width:110px;" id="btnRevisionProjectCharter" name="btnRevisionProjectCharter"><i class="fa fa-wrench"></i>&nbsp Revision</button>'
                        }else{
                          return '<button class="btn btn-sm btn-danger" style="width:110px;" id="btnRevisionProjectCharter" name="btnRevisionProjectCharter" onclick="btnAddProjectCharter(0,' + "'" + row.id + "'" +','+ "'revision'" +')"><i class="fa fa-wrench"></i>&nbsp Revision</button>'
                        }                                 
                      }else if(row.status == 'Draft'){
                        	if ("{{Auth::User()->ttd}}" == "") {
                          	$("#alert").show()
                        		$("button[name='btnAddProjectCharter']").prop("disabled",true)
                        		$("button[name='btnAddProjectCharter']").attr("title","Please upload your sign on profile page first, for enable this project charter button!")
                          		console.log("uwoo")
                        	}

                        	if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                          	return '<button class="btn btn-sm btn-primary disabled" style="width:110px;" id="btnAddProjectCharter" name="btnAddProjectCharter"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        	}else{
                          	return '<button class="btn btn-sm btn-primary" style="width:110px;" id="btnAddProjectCharter" name="btnAddProjectCharter" onclick="btnAddProjectCharter(0,' + "'" + row.id + "'" +','+ "'draft'" +')"><i class="fa fa-wrench"></i>&nbsp Project Charter</button>'
                        	} 
                      }else if (row.status == 'Approve') {
                        if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                          return '<button class="btn btn-sm btn-primary disabled" style="width:110px;" id="btnAddProjectCharter" name="btnAddProjectCharter"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        }else{
                          return '<button class="btn btn-sm bg-purple" style="width:110px" disabled><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                        } 
                      }else if (row.status == 'Done'){
                        return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                      }else{
                        if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','PMO Manager')->exists()}}") {
                          return '<button class="btn btn-sm btn-primary disabled" style="width:110px;" id="btnAddProjectCharter" name="btnAddProjectCharter"><i class="fa fa-eye"></i>&nbsp Project Charter</button>'
                        }else{
                          return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                        } 
                      }
                    }
                  }else if(row.current_phase == "Waiting"){
                      return '<button class="btn btn-sm bg-purple" style="width:110px" disabled><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                  }else{
                    return '<button class="btn btn-sm bg-purple" style="width:110px" onclick="detailProject(' + "'" + row.id + "'" +',' + "'" + row.project_type + "'" +')"><i class="fa fa-arrow-circle-up"></i>&nbsp Detail</button>'
                  }
                }
              },
          }
        ],
        order: [[0, 'asc']],
        "rowCallback": function( row, data ) {
            if (data.status == "Approve") {
              console.log("testtt")
              if ("{{Auth::User()->name}}" != data.sign) {
                $('td:eq(7)', row).html('<button class="btn btn-sm btn-primary disabled" style="width:110px" id="btnShowProjectCharter" name="btnShowProjectCharter" disabled><i class="fa fa-eye"></i>&nbsp Project Charter</button>');
              }else{
                $('td:eq(7)', row).html('<button class="btn btn-sm btn-primary" style="width:110px" id="btnShowProjectCharter" name="btnShowProjectCharter" onclick="btnShowProjectCharter('+ "'" + data.id + "'" +')"><i class="fa fa-eye"></i>&nbsp Project Charter</button>');           
              }
            }
            // if (table.row(0).data().milestone == "Submit Final Project Closing Report") {
            //   $("#btnFinalProject").prop("disabled",false)
            // }
        },
        drawCallback: function(settings) {
          if (accesable.includes("btnDeleteAssign")) {
            $("button[name='btnDeleteAssign']").show()
          }

          if (accesable.includes("btnAddProjectCharter")) {
            $("button[name='btnAddProjectCharter']").prop("disabled",false)
          }

          if (accesable.includes("btnShowProjectCharter")) {
            $("button[name='btnShowProjectCharter']").prop("disabled",false)
          }

          if (accesable.includes("btnRevisionProjectCharter")) {
            $("button[name='btnRevisionProjectCharter']").prop("disabled",false)
            // table.columns(4).visible(false);
          }
        },
    });

    $('#applyFilterTableSearch').click(function(){
      $('#tbListProject').DataTable().ajax.url("{{url('/PMO/getListDataProject')}}?searchFor="+$("#searchBarList").val()).load();
      // table.clear().search($('#searchBarList').val()).draw();
    })  

    $('#searchBarList').keypress(function(e){
      if (e.which == 13) {
        $('#tbListProject').DataTable().ajax.url("{{url('/PMO/getListDataProject')}}?searchFor="+$("#searchBarList").val()).load();

        return false;    //<---- Add this line
      }
    })

    function validationCheck(data,value){
      if ($(data).attr('type') == 'email') {
        const validateEmail = (email) => {
          return email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          )
        }

        emails = validateEmail($("#"+ $(data).attr('id')).val())

        if ($("#"+ $(data).attr('id')).val() == '-') {
         $("#"+ $(data).attr('id')).closest('.form-group').removeClass('has-error')
         $("#"+ $(data).attr('id')).closest('input').next('span').hide()
         $("#"+ $(data).attr('id')).prev('.input-group-addon').css("background-color","red")
        }else{
          switch(emails){
            case null:
              $("#"+ $(data).attr('id')).closest('.form-group').addClass('has-error')
              $("#"+ $(data).attr('id')).closest('input').next('span').show();
              $("#"+ $(data).attr('id')).prev('.input-group-addon').css("background-color","red");
              $("#"+ $(data).attr('id')).closest('input').next('span').text("Enter a Valid Email Address!")
            break;
            default:
              $("#"+ $(data).attr('id')).closest('.form-group').removeClass('has-error')
              $("#"+ $(data).attr('id')).closest('input').next('span').hide()
              $("#"+ $(data).attr('id')).prev('.input-group-addon').css("background-color","red")
          }
        }
      }else{
        if (data.className.split(" ")[1] == "document" || data.className == "document") {
          var f=data.files[0]
          var filePath = f;
       
          // Allowing file type
          var allowedExtensions =
          /(\.pdf)$/i;

          var ErrorText = []
          // 
          if (f.size > 30000000|| f.fileSize > 30000000) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Invalid file size, just allow file with size less than 30MB!',
            }).then((result) => {
              data.value = ''
            })
          }

          var ext = filePath.name.split(".");
          ext = ext[ext.length-1].toLowerCase();      
          var arrayExtensions = ["pdf"];

          if (arrayExtensions.lastIndexOf(ext) == -1) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Invalid file type, just allow pdf file',
            }).then((result) => {
              data.value = ''
            })
          }
        }

        if($("#"+ $(data).attr('id')).val() != ""){
          $("#"+ $(data).attr('id')).closest(".form-group").removeClass("has-error")
          $("#"+ $(data).attr('id')).next("span.help-block").hide()

          $("#"+ $(data).attr('id')).closest(".form-group").removeClass("has-error")
          $("#"+ $(data).attr('id')).closest(".form-group").find("span.help-block").hide()
        }

        if ($("#textAreaRisk[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#textAreaRisk[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#textAreaRisk[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if($("#inputOwner[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#inputOwner[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#inputOwner[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#inputImpact[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#inputImpact[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#inputImpact[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#inputLikelihood[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#inputLikelihood[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#inputLikelihood[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#inputRank[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#inputRank[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#inputRank[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#textareaDescription[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#textareaDescription[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#textareaDescription[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#textareaResponse[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#textareaResponse[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#textareaResponse[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }

        if ($("#due_date[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#due_date[data-value='"+ $(data).data("value") +"']").closest(".form-group").removeClass("has-error")
          $("#due_date[data-value='"+ $(data).data("value") +"']").closest(".form-group").find("span.help-block").hide()
        }

        if ($("#review_date[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#review_date[data-value='"+ $(data).data("value") +"']").closest(".form-group").removeClass("has-error")
          $("#review_date[data-value='"+ $(data).data("value") +"']").closest(".form-group").find("span.help-block").hide()
        }

        if ($("#selectStatusProjectCharter[data-value='"+ $(data).data("value") +"']").val() != "") {
          $("#selectStatusProjectCharter[data-value='"+ $(data).data("value") +"']").closest("div").removeClass("has-error")
          $("#selectStatusProjectCharter[data-value='"+ $(data).data("value") +"']").next("span.help-block").hide()
        }
        
      }   

      if (value == 'logo') {
        var f=data.files[0]
        var filePath = f;
     
        // Allowing file type
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

        var ErrorText = []
        // 
        if (f.size > 30000000|| f.fileSize > 30000000) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file size, just allow file with size less than 30MB!',
          }).then((result) => {
            data.value = ''
          })
        }

        var ext = filePath.name.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file type, just allow png/jpg file',
          }).then((result) => {
            data.value = ''
          })
        }
      }   

    }

    function selectStatusProjectCharter(inc,item){
      var data = [
        {
          id: "Active",
          text: "Active"
        },
        {
          id: "Obsolete",
          text: "Obsolete"
        },
        {
          id: "Accepted",
          text: "Accepted"
        },
      ];
      $("#selectStatusProjectCharter[data-value='"+ inc +"']").select2({
          data:data,
        placeholder:"Select Status"
      })
      // if (inc != 0) {
      //   console.log(item+"piyee")

      //   if (item) {
      //     console.log("increment"+inc)

      //     $("#selectStatusProjectCharter[data-value='"+ inc +"']").select2({
      //       data:data,
      //       placeholder:"Select Status"
      //     }).val(item).trigger("change")
      //   }else{
      //     $("#selectStatusProjectCharter[data-value='"+ inc +"']").select2({
      //       data:data,
      //       placeholder:"Select Status"
      //     })
      //   }
      // }else{
      //   console.log(item+"piyee")

        
      // }
    }

    let currentTab = 0;
    function btnAddProjectCharter(n,id_pmo,status){
      $("#btnShowPC").remove()

      let data_pid = []
      let id_project = id_pmo

      if (status == "create") {
        url = "{{url('/PMO/getListforProjectCharterById')}}"
      }else{
        url = "{{url('/PMO/showProjectCharter')}}"
      }
      
      $.ajax({
        url:url,
        type:"GET",
        data:{
          id_pmo:id_pmo
        },success:function(result){
          $("#selectPmProjectCharter").empty()
          $("#selectPcProjectCharter").empty()

          if (status != "create") {
            if (status == 'revision') {
              reasonReject(result[0].note_reject,"block","tabGroup")
            }else{
              $(".divReasonRejectRevision").remove()
            }

            $('#inputProjectName').val(result[0].project_id.name_project)
            data_pid.push({'id':result[0].project_id.project_id,'text':result[0].project_id.project_id})
            $('#inputPID').select2()
            $('#inputPID').select2({
                data:[{'id':result[0].project_id.project_id,'text':result[0].project_id.project_id}]
            }).attr("disabled",true).val(result[0].project_id.project_id).trigger('change');
            $('#inputCustomer').val(result[0].customer_name)
            $('#textAreaAddress').val(result[0].customer_address)
            $('#inputPhone').val(result[0].customer_phone)
            $('#inputContactPerson').val(result[0].customer_cp)
            $('#inputEmail').val(result[0].customer_email)
            $('#inputCpPhone').val(result[0].customer_cp_phone)
            $('#inputCpTitle').val(result[0].customer_cp_title)
            $("#inputCompanyLogo").closest(".form-group").next(".form-group").remove()
            // $("#inputCompanyLogo").closest(".form-group").after("<div class='form-group'><label>Logo Preview</label><br><img src='{{asset('/')}}"+ result[0].logo_company +"' style='width:100px;height:100px'></div>")
            $('#textAreaProjectDesc').val(result[0].project_description)
            $('#textAreaProjectObj').val(result[0].project_objectives)
            $('#inputStartDate').val(moment(result[0].estimated_start_date).format('MM/DD/YYYY'))
            $('#inputFinishDate').val(moment(result[0].estimated_end_date).format('MM/DD/YYYY'))

            $('#selectFlexibility').val(result[0].flexibility)
            $('#selectMarketSegment').val(result[0].market_segment).trigger('change')
            $('#textAreaSOW').val(result[0].scope_of_work)
            $('#textAreaOutOfScope').val(result[0].out_of_scope)
            $('#textAreaCustomerRequirement').val(result[0].customer_requirement)
            $('#textAreaTOP').val(result[0].terms_of_payment)
            
            const filePO   = document.querySelector('input[type="file"][name="inputPO"]');

            const fileToR   = document.querySelector('input[type="file"][name="inputToR"]');

            const fileSBE = document.querySelector('input[type="file"][name="inputSbe"]');

            const companyLogo = document.querySelector('input[type="file"][name="inputCompanyLogo"]');

            const myFileLogo = new File(['{{asset("/")}}"'+ result[0].logo_company +'"'], "/" + result[0].logo_company,{
                type: 'text/plain',
                lastModified: new Date(),
            });

            // Now let's create a DataTransfer to get a FileList
            const dataTransferLogo = new DataTransfer();
            dataTransferLogo.items.add(myFileLogo);
            companyLogo.files = dataTransferLogo.files;
            $('#inputCompanyLogo').attr("href","/" + result[0].logo_company)
            

            if (result[0].dokumen[0] !== undefined) {
              const myFilePO = new File(['{{asset("/")}}"'+ result[0].dokumen[0].document_location +'"'], '/'+ result[0].dokumen[0].document_location,{
                  type: 'text/plain',
                  lastModified: new Date(),
              });

              // Now let's create a DataTransfer to get a FileList
              const dataTransferPO = new DataTransfer();
              dataTransferPO.items.add(myFilePO);
              filePO.files = dataTransferPO.files;

              if (result[0].dokumen[0].link_drive != null) {
                $('#link_input_po').text(result[0].dokumen[0].document_location)
                $('#link_input_po').attr("href",result[0].dokumen[0].link_drive)
                $("#link_input_po").show()
                $("#link_input_po").closest(".form-group").find("i").show()
              }
            }

            if (result[0].dokumen[1] !== undefined) {
              const myFileToR = new File(['{{asset("/")}}"'+ result[0].dokumen[1].document_location +'"'], '/'+ result[0].dokumen[1].document_location,{
                  type: 'text/plain',
                  lastModified: new Date(),
              });

              // Now let's create a DataTransfer to get a FileList
              const dataTransferToR = new DataTransfer();
              dataTransferToR.items.add(myFileToR);
              fileToR.files = dataTransferToR.files;

              if (result[0].dokumen[1].link_drive != null) {
                $('#link_input_tor').text(result[0].dokumen[1].document_location)
                $('#link_input_tor').attr("href",result[0].dokumen[1].link_drive)
                $("#link_input_tor").show()
                $("#link_input_tor").closest(".form-group").find("i").show()
              }
            }

            if (result[0].dokumen[2] !== undefined) {
              const myFileSbe = new File(['{{asset("/")}}"'+ result[0].dokumen[2].document_location +'"'], '/'+ result[0].dokumen[2].document_location,{
                  type: 'text/plain',
                  lastModified: new Date(),
              });

              // Now let's create a DataTransfer to get a FileList
              const dataTransferSbe = new DataTransfer();
              dataTransferSbe.items.add(myFileSbe);
              fileSBE.files = dataTransferSbe.files;

              if (result[0].dokumen[2].link_drive != null) {
                $('#link_input_sbe').text(result[0].dokumen[2].document_location)  
                $('#link_input_sbe').attr("href",result[0].dokumen[2].link_drive)
                $("#link_input_sbe").show()
                $("#link_input_sbe").closest(".form-group").find("i").show()
              }
            }

            if (result[0].dokumen.length > 2) {
              $("#tableUploadDoc").empty("")
              $.each(result[0].dokumen,function(item,value){
                if (item > 2) {
                  incrementDoc++
                  appendDoc = ""
                  appendDoc = appendDoc + '<tr class="trDoc" style="margin-top:5px">'
                  appendDoc = appendDoc + '  <td><span style="'
                  appendDoc = appendDoc + '    display: inline;'
                  appendDoc = appendDoc + '"'
                  appendDoc = appendDoc + 'class="btnRemoveDoc_'+ incrementDoc +'"'
                  appendDoc = appendDoc + '><i class="fa fa-times" style="'
                  appendDoc = appendDoc + '    margin-top: 10px;'
                  appendDoc = appendDoc + '    color: red;'
                  appendDoc = appendDoc + '"></i>'
                  appendDoc = appendDoc + '</span>'
                  appendDoc = appendDoc + '    &nbsp;<div style="display: inline;float: right;padding:5px;border:solid 1px #cccc;width: 280px;background-color: #cccc;"> <label for="inputDoc_'+ incrementDoc +'" class="fa fa-upload" id="title_doc_'+ incrementDoc +'" data-value="'+ incrementDoc +'">&nbsp; <span>Upload Document</span> <input disabled type="file" name="inputDoc" id="inputDoc_'+ incrementDoc +'" data-value="'+ incrementDoc +'" style="display: none;" onchange="validationCheck(this)" class="inputDoc_'+ incrementDoc +'"></label></div>'  
                  appendDoc = appendDoc + '  </td>'
                  appendDoc = appendDoc + '  <td>'
                  appendDoc = appendDoc + '   <input placeholder="Enter Document Name" type="text" name="inputDocTitle" id="inputDocTitle_'+ incrementDoc +'" class="form-control" style="width:250px;margin-bottom: 23px;" data-value="'+ incrementDoc +'" value="'+ value.document_name +'">'
                  appendDoc = appendDoc + '  </td>'
                  appendDoc = appendDoc + '</tr><br>'
                  $("#tableUploadDoc").append(appendDoc) 

                  const filedocpendukung = document.querySelector('.inputDoc_'+incrementDoc);

                  const FilePendukung = new File(['{{asset("/")}}"'+ value.document_location +'"'], '/'+ value.document_location,{
                      type: 'text/plain',
                      lastModified: new Date(),
                  });

                  // Now let's create a DataTransfer to get a FileList
                  const dataTransfer = new DataTransfer();
                  dataTransfer.items.add(FilePendukung);
                  filedocpendukung.files = dataTransfer.files;

                  $('#inputDoc_'+ incrementDoc).closest("div").after("<div class='form-group' style='margin-top: 5px;margin-bottom:5px'><i class='fa fa-folder' style='color:rgb(60, 141, 188)'></i><a href='"+ value.link_drive +"' target='_blank'>&nbsp"+ value.document_name +"</a></div>")
                }
              })

              $("#tableUploadDoc .trDoc").each(function(){
                let inputData = $(this)
                
                $("#inputDoc_"+ $(this).find('input[name="inputDoc"]').attr('data-value')).change(function(){          
                  if (this.value != "") {
                    $("#title_doc_"+ inputData.find('input[name="inputDoc"]').attr('data-value')).find("span").remove()
                    $("#inputDoc_"+ inputData.find('input[name="inputDoc"]').attr('data-value')).css("display","inline")
                  }
                })

                $('#inputDocTitle_'+ inputData.find('input[name="inputDoc"]').attr('data-value')).keydown(function(){          
                  if (this.value == "") {
                    $("#btnAddDoc").prop("disabled",true)
                  }else{
                    $("#btnAddDoc").prop("disabled",false)
                  }
                })  

                $(".btnRemoveDoc_"+inputData.find('input[name="inputDoc"]').attr('data-value')).click(function(){
                  let incrementDocBefore = parseInt(inputData.find('input[name="inputDoc"]').data('value'))-1
                  
                  $(".btnRemoveDoc_"+inputData.find('input[name="inputDoc"]').data('value')).closest("tr").remove();
                  if ($("#inputDocTitle_"+ incrementDocBefore).val() == "") {
                    $("#btnAddDoc").prop("disabled",true)
                  }else{
                    $("#btnAddDoc").prop("disabled",false)
                  }
                })
              }) 
            }

            var append = ''
            $.each(result[0].internal_stakeholder,function(index,item){
                $('#tbodyInternalStakeholderRegister').empty("")
                // incIstakeholder = ++index
                append = append +'<tr>'
                append = append +'  <td><select id="selectNameStakeholder" name="selectNameStakeholder" class="select2 form-control selectNameStakeholder" data-value="'+ index +'"><option></option></select></td>'
                append = append +'  <td><select style="font-size:12px" id="selectRoleStakeholder" class="select2 form-control" data-value="'+ index +'">'
                append = append + ' <option value="Project Steering Committee">Project Steering Committee</option>'
                append = append + ' <option value="Project Owner">Project Owner</option>'
                append = append + ' <option value="Project Advisor">Project Advisor</option>'
                append = append + ' <option value="Project Manager">Project Manager</option>'
                append = append + ' <option value="Co-Project Manager">Co - Project Manager</option>'
                append = append + ' <option value="Project Coordinator">Project Coordinator</option>'
                append = append + ' <option value="Project Administrator">Project Administrator</option>'
                append = append + ' <option value="Site Manager">Site Manager</option>'
                append = append + ' <option value="HSE">HSE</option>'
                append = append + ' <option value="Drafter">Drafter</option>'
                append = append + ' <option value="Technical Writer">Technical Writer</option>'
                append = append + ' <option value="Solution Architect">Solution Architect</option>'
                append = append + ' <option value="Technical Lead Engineer">Technical Lead Engineer</option>'
                append = append + ' <option value="IT Network Engineer">IT Network Engineer</option>'
                append = append + ' <option value="IT Network Security Engineer">IT Network Security Engineer</option>'
                append = append + ' <option value="IT System Engineer">IT System Engineer</option>'
                append = append + ' <option value="Cabling Engineer">Cabling Engineer</option>'
                append = append + ' <option value="MSM Technical Lead Engineer">MSM Technical Lead Engineer</option>'
                append = append + ' <option value="MSM Engineer">MSM Engineer</option>'
                append = append + ' <option value="Helpdesk">Helpdesk</option>'
                append = append + ' <option value="Procurement">Procurement</option>'
                append = append + ' <option value="WH Delivery Team">WH Delivery Team</option>'
                append = append + ' <option value="Legal">Legal</option>'
                append = append +'</select></td>'
                append = append +'  <td><input id="inputEmailStakeholder" style="width:90px" class="form-control disabled" disabled data-value="'+ index +'"/></td>'
                append = append +'  <td><input id="inputPhoneStakeholder" style="width:90px" class="form-control disabled" disabled data-value="'+ index +'"/></td>'
                append = append +'  <td><button type="button" class="fa fa-trash" style="color:red;background-color:transparent;border:none;margin-top:10px" id="btnDeleteRowIStakeholder" class="form-control"data-value="'+ index +'"/></td>'
                append = append +'</tr>'

                $('#tbodyInternalStakeholderRegister').append(append)
                $.ajax({
                  url:"{{url('/PMO/getUser')}}",
                  type:"GET",
                  success:function(result){
                    $("#selectNameStakeholder[data-value='"+ index +"']").select2({
                      data:result.data,
                      placeholder:"Select Name Stakeholder",
                      dropdownCssClass: "myFont" 
                    }).val(item.nik).trigger('change')

                    $("#selectNameStakeholder[data-value='"+ index +"']").select2({
                      data:result.data,
                      placeholder:"Select Name Stakeholder",
                      dropdownCssClass: "myFont" 
                    }).on('select2:select', function (e) {
                      let filteredEmailPhone = filterByStakeholderName(e.params.data.id)
                      // let filteredPhone = filterByStakeholderName(e.params.data.id)
                      console.log(e.params)
                      console.log($(this).closest("td"))
                      $(this).closest("td").next("td:nth-child(2)").next("td:nth-child(3)").find("input").val(filteredEmailPhone[0])
                      $(this).closest("td").next("td:nth-child(2)").next("td:nth-child(3)").next("td:nth-child(4)").find("input").val(filteredEmailPhone[1])

                    });
                  }
                })

                function filterByStakeholderName(nik){
                  let email = "", phone = ""

                  $.ajax({
                    async: false,
                    type:"GET",
                    url:"{{url('/PMO/getUser')}}",
                    data:{
                     nik:nik 
                    },
                    success: function(data)
                    { 
                      $.each(data,function(index,result){
                        email = result[0].email
                        phone = result[0].phone
                        console.log(result[0].email)
                      })
                    }      
                  })

                  return [email,phone]
                }
            }) 

            $.each(result[0].internal_stakeholder,function(index,item){
              $("#inputEmailStakeholder[data-value='"+ index +"']").val(item.email)
              $("#inputPhoneStakeholder[data-value='"+ index +"']").val(item.phone)
              $("#selectRoleStakeholder[data-value='"+ index +"']").select2().val(item.role).trigger("change")
            })

            var appendRisk = ''
            $.each(result[0].risk,function(idx,item){

                if (idx == 0) {
                  $('#textAreaRisk[data-value='+ idx +']').val(item.risk_description)
                  $('#inputOwner[data-value='+ idx +']').val(item.risk_owner)
                  $('#inputImpact[data-value='+ idx +']').val(item.impact)
                  $('#inputLikelihood[data-value='+ idx +']').val(item.likelihood)
                  $('#textareaResponse[data-value='+ idx +']').val(item.risk_response)
                  $('#due_date[data-value='+ idx +']').val(moment(item.due_date).format('MM/DD/YYYY'))
                  $('#review_date[data-value='+ idx +']').val(moment(item.review_date).format('MM/DD/YYYY'))
                  $('#selectStatusProjectCharter[data-value='+ idx +']').select2({
                    data:[
                      {
                        id: "Active",
                        text: "Active"
                      },
                      {
                        id: "Obsolete",
                        text: "Obsolete"
                      },
                      {
                        id: "Accepted",
                        text: "Accepted"
                      },
                    ],
                    placeholder:"Select Status"
                  }).val(item.status).trigger('change')
                }else{
                  console.log(idx)
                  $("#tbodyIdentifiedRisk tr:not(:first-child").remove("")

                  appendRisk = appendRisk + '<tr>'
                  appendRisk = appendRisk + '  <td>'
                  appendRisk = appendRisk + '     <i class="fa fa-trash pull-right" style="color:red" id="btnRemoveIdentifiedRisk"></i>'
                  appendRisk = appendRisk + '     <div class="form-group">'
                  appendRisk = appendRisk + '      <label>Risk Description*</label>'
                  appendRisk = appendRisk + '      <textarea class="form-control" id="textAreaRisk" name="textAreaRisk" placeholder="Scope of Work" data-value="'+ idx +'" onkeyup="validationCheck(this)">'+ item.risk_description +'</textarea>'
                  appendRisk = appendRisk + '      <span class="help-block" style="display:none;">Please fill Risk Description!</span>'
                  appendRisk = appendRisk + '    </div>'
                  appendRisk = appendRisk + '    <div class="row">'
                  appendRisk = appendRisk + '      <div class="col-md-6 col-xs-12">'
                  appendRisk = appendRisk + '        <div class="form-group">'
                  appendRisk = appendRisk + '          <label>Owner*</label> '
                  appendRisk = appendRisk + '          <input type="text"  value="'+ item.risk_owner +'" class="form-control" id="inputOwner" name="inputOwner" placeholder="Owner" data-value="'+ idx +'" onkeyup="validationCheck(this)"/>'
                  appendRisk = appendRisk + '          <span class="help-block" style="display:none;">Please fill Owner!</span>'
                  appendRisk = appendRisk + '        </div>'
                  appendRisk = appendRisk + '      </div>'
                  appendRisk = appendRisk + '      <div class="col-md-3 col-xs-12">'
                  appendRisk = appendRisk + '        <div class="form-group">'
                  appendRisk = appendRisk + '          <label>Impact*</label> '
                  appendRisk = appendRisk + '          <input max="5" min="1" type="number" value="'+ item.impact +'" class="form-control" id="inputImpact" name="inputImpact" placeholder="1-5" data-value="'+ idx +'" onkeyup="validationCheck(this)"/>'
                  appendRisk = appendRisk + '          <span class="help-block" style="display:none;">Please fill Impact!</span>'
                  appendRisk = appendRisk + '        </div>'
                  appendRisk = appendRisk + '      </div>'
                  appendRisk = appendRisk + '      <div class="col-md-3 col-xs-12">'
                  appendRisk = appendRisk + '        <div class="form-group">'
                  appendRisk = appendRisk + '          <label>Likelihood*</label> '
                  appendRisk = appendRisk + '          <input max="5" min="1" type="number" value="'+ item.likelihood +'" class="form-control" id="inputLikelihood" name="inputLikelihood" placeholder="1-5" data-value="'+ idx +'" onkeyup="validationCheck(this)"/>'
                  appendRisk = appendRisk + '          <span class="help-block" style="display:none;">Please fill Probability!</span>'
                  appendRisk = appendRisk + '        </div>'
                  appendRisk = appendRisk + '      </div>'
                  appendRisk = appendRisk + '    </div>'
                  appendRisk = appendRisk + '    <div class="row">'
                  appendRisk = appendRisk + '        <div class="col-md-12 col-xs-12">'
                  appendRisk = appendRisk + '          <div class="form-group">'
                  appendRisk = appendRisk + '            <label>Response*</label> '
                  appendRisk = appendRisk + '            <textarea class="form-control" id="textareaResponse" name="textareaResponse" placeholder="Response" data-value="'+ idx +'" onkeyup="validationCheck(this)">' + item.risk_response
                  appendRisk = appendRisk + '            </textarea><span class="help-block" style="display:none;">Please fill Risk Response!</span>'
                  appendRisk = appendRisk + '          </div>'
                  appendRisk = appendRisk + '        </div>'
                  appendRisk = appendRisk + '    </div>'   
                  appendRisk = appendRisk + '    <div class="row">'      
                  appendRisk = appendRisk + '      <div class="col-md-4 col-xs-12">'      
                  appendRisk = appendRisk + '        <div class="form-group">'      
                  appendRisk = appendRisk + '          <label>Due Date*</label>'      
                  appendRisk = appendRisk + '          <div class="input-group">'      
                  appendRisk = appendRisk + '            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>'      
                  appendRisk = appendRisk + '            <input type="text" name="due_date" class="form-control" id="due_date" value="'+ moment(item.due_date).format('MM/DD/YYYY') +'" placeholder="Select Due Date" data-value="'+ idx +'" onkeyup="validationCheck(this)"/>'      
                  appendRisk = appendRisk + '          </div><span class="help-block" style="display:none;">Please fill Due Date!</span>'      
                  appendRisk = appendRisk + '        </div>'      
                  appendRisk = appendRisk + '      </div>'      
                  appendRisk = appendRisk + '      <div class="col-md-4 col-xs-12">'      
                  appendRisk = appendRisk + '        <div class="form-group">'      
                  appendRisk = appendRisk + '          <label>Review Date*</label>'      
                  appendRisk = appendRisk + '          <div class="input-group">'      
                  appendRisk = appendRisk + '            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>'      
                  appendRisk = appendRisk + '            <input type="text" name="review_date" value="'+ moment(item.review_date).format('MM/DD/YYYY') +'" class="form-control" id="review_date" placeholder="Select Review Date" data-value="'+ idx +'" onkeyup="validationCheck(this)"/>'      
                  appendRisk = appendRisk + '          </div><span class="help-block" style="display:none;">Please fill Review Date!</span>'      
                  appendRisk = appendRisk + '        </div>'      
                  appendRisk = appendRisk + '      </div>'      
                  appendRisk = appendRisk + '      <div class="col-md-4 col-xs-12">'      
                  appendRisk = appendRisk + '        <div class="form-group">'      
                  appendRisk = appendRisk + '          <label>Status*</label>'      
                  appendRisk = appendRisk + '          <select class="form-control select2" id="selectStatusProjectCharter_'+ idx +'" data-value="'+ idx +'" onchange="validationCheck(this)">'      
                  appendRisk = appendRisk + '            <option value="Active">Active</option>'
                  appendRisk = appendRisk + '            <option value="Obsolete">Obsolete</option>'      
                  appendRisk = appendRisk + '            <option value="Accepted">Accepted</option>'            
                  appendRisk = appendRisk + '          </select><span class="help-block" style="display:none;">Please fill Status!</span>'      
                  appendRisk = appendRisk + '        </div>'      
                  appendRisk = appendRisk + '      </div>'      
                  appendRisk = appendRisk + '    </div>'      
                  appendRisk = appendRisk + '  </td>'
                  appendRisk = appendRisk + '</tr>'

                  $("#tbodyIdentifiedRisk").append(appendRisk)
                  $('#selectStatusProjectCharter_'+idx).select2().val(item.status).trigger('change')
                  // selectStatusProjectCharter(idx,item.status)


                }
            })
          }else{
            $('#inputPID').select2()
            $(".divReasonRejectRevision").remove()
            if (status == "draft") {

            }else{
              data_pid.push({'id':result[0].project_id,'text':result[0].project_id})
              $('#inputPID').select2({
                  data:[{'id':result[0].project_id,'text':result[0].project_id}]
              }).attr("disabled",true).val(result[0].project_id).trigger('change');

              // $('#tbodyInternalStakeholderRegister').empty("")
              // $("#tbodyIdentifiedRisk").find("tr:not(:first-child)").empty("")
              // $("#link_input_sbe").hide()
              // $("#link_input_sbe").closest(".form-group").find("i").hide()
              // $("#link_input_po").hide()
              // $("#link_input_po").closest(".form-group").find("i").hide()
              // $("#link_input_tor").hide()
              // $("#link_input_tor").closest(".form-group").find("i").hide()
              $("input[name='cbTechUse']").iCheck('uncheck')
              $('#inputProjectName').val(result[0].name_project)
            } 
          }

          var optPM = $("<option>").val(result[0].project_pm).text(result[0].project_pm);
          var optPC = $("<option>").val(result[0].project_pc).text(result[0].project_pc);

          //append option to the select element
          $("#selectPmProjectCharter").append(optPM);
          $("#selectPcProjectCharter").append(optPC);
          $('#inputPoNumber').val(result[0].no_po_customer)
          $("input[name='cbProjectTypeAddProjectCharter']").iCheck('uncheck')
          $.each(result[0].type_project_array,function(item,value){
            $("input[name='cbProjectTypeAddProjectCharter'][value='"+ value.project_type +"']").iCheck('check')
          })
          $("#inputProjectOwner").val(result[0].owner)

          let x = document.getElementsByClassName("tab-add");
          x[n].style.display = "inline";
          if (n == (x.length - 1)) {
            $(".modal-title").text('Upload Document')

            $("#inputDoc_0").change(function(){
              if (this.value != "") {
                  $("#title_doc_0").find("span").remove()
                  $("#inputDoc_0").css("display","inline")
                }
              })

              $('#inputDocTitle_0').keydown(function(){
              if (this.value == "") {
                $("#btnAddDoc").prop("disabled",true)
              }else{
                $("#btnAddDoc").prop("disabled",false)
              }
            })

            $("#prevBtnAdd").before('<button type="button" class="btn btn-sm btn-primary" style="float:left" id="btnShowPC"><i class="fa fa-eye"></i> &nbspProject Charter</button>')
            $("#btnShowPC").click(function(){
              console.log("clicked")
              btnShowProjectCharter(id_pmo,"pm")
            })
            document.getElementById("prevBtnAdd").style.display = "inline";
            document.getElementById("nextBtnAdd").innerHTML = "Save";
            $("#nextBtnAdd").attr('onclick','saveProject('+ id_pmo +',"'+ status +'")');
          }else{
            if (n == 0) {
              document.getElementById("prevBtnAdd").innerHTML = "Cancel";
              document.getElementById("nextBtnAdd").innerHTML = "Next";
              $("#nextBtnAdd").attr('onclick','nextPrevAdd(1,"'+ id_project +'","'+ status +'")')
              $("#prevBtnAdd").attr('onclick','cancelModal()')

              $(".modal-title").text('Customer Information')
              $(".modal-dialog").removeClass('modal-lg')

              
            }else if (n == 1) {
              $(".modal-title").text('Project Information')
              $(".modal-dialog").removeClass('modal-lg')

              $("#selectFlexibility").select2({
                placeholder:"Select Flexibility",
                cache:false
              })

              $("#selectMarketSegment").select2({
                placeholder: "Select Market Segment",
                cache:false
              })

              $("input[name='inputStartDate'],input[name='inputFinishDate'],input[name='due_date'],input[name='review_date']").datepicker({
                autoclose:true
              })

              let newArrTechnologyUse = []

              $.each($("input[name='cbTechUse']:checked"),function(idx,item){
                newArrTechnologyUse.push(item)
              })

              if (newArrTechnologyUse.length == 0) {
                $.each(result[0].technology_used,function(idx,item){
                  $("input[name='cbTechUse'][value='"+ item.technology_used +"']").iCheck('check')
                })
              }else{
                $.each(newArrTechnologyUse,function(item){
                  $("input[name='cbTechUse'][value='"+ item +"']").iCheck('check')
                })
              }

              $("#inputProjectName").closest(".form-group").next(".form-group").find("input").prop('disabled',true).closest('div').css('cursor','not-allowed')

              $("input[name='cbTechUse']").on('ifChecked', function(event) {
                $("input[name='cbTechUse']").closest("div").closest("label").closest(".form-group").removeClass("has-error")
                $("input[name='cbTechUse']").closest("div").closest("label").closest(".form-group").find("span").hide()
              })

              document.getElementById("prevBtnAdd").innerHTML = "Back";
              document.getElementById("nextBtnAdd").innerHTML = "Next";
              $("#nextBtnAdd").attr('onclick','nextPrevAdd(1,"'+ id_project +'","'+ status +'")')
              $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1,'+ id_project +',"'+ status +'")')

            }else if (n == 2) {
              $(".modal-title").text('Project Information')
              $(".modal-dialog").removeClass('modal-lg')   

              document.getElementById("prevBtnAdd").innerHTML = "Back";
              $("#nextBtnAdd").attr('onclick','nextPrevAdd(1,"'+ id_project +'","'+ status +'")')
              $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1,'+ id_project +',"'+ status +'")')

            }else if (n == 3) {
              $(".modal-title").text('Initial Identified Risk')
              $(".modal-dialog").removeClass('modal-lg')

              $("#inputImpact").keyup(function(){
                if (this.value > 5) {
                  $("#inputImpact").val("")
                }
              })

              $("#inputLikelihood").keyup(function(){
                if (this.value > 5) {
                  $("#inputLikelihood").val("")
                }
              })

              document.getElementById("prevBtnAdd").innerHTML = "Back";
              document.getElementById("nextBtnAdd").innerHTML = "Next";

              $("#nextBtnAdd").attr('onclick','nextPrevAdd(1,"'+ id_project +'","'+ status +'")')
              $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1,'+ id_project +',"'+ status +'")')

              selectStatusProjectCharter(0)

            }

          }
        }
      })
      
      $("#ModalProjectCharter").modal("show") 
    }

    let arrReason = []
    function reasonReject(item,display,nameClass,typeCallout=""){
      $(".divReasonRejectRevision").remove()
      arrReason.push(item)
      

      var textTitle = ""
      var className = ""

      if (nameClass == 'tabGroup') {
        textTitle = "Note Project Charter!"
        className = "tabGroup"
      }

      var append = ""
      append = append + '<div class="callout callout-danger divReasonRejectRevision" style="display:none">'
        append = append + '<h4><i class="icon fa fa-cross"></i>'+ textTitle +'</h4>'
        $.each(arrReason,function(item,value){
          
          // append = append + '<p class="reason_reject_revision">'+ value.split(":")[0] + ":<br> - " + value.split(":")[1].replaceAll("\n","<br> - ")+'</p>'
          append = append + '<p class="reason_reject_revision">'+ value.replaceAll(":","<br>").replaceAll("\n","<br>") + '</p>'
        })
      append = append + '</div>'

      $("." + nameClass).prepend(append)
      

      if (display == "block") {
        $(".divReasonRejectRevision").show()
      }
    }

    function btnShowProjectCharter(id_pmo,privilege){
      if (privilege == 'pm') {
        window.open("{{url('PMO/project/detail')}}/"+id_pmo + "?showProject", "_blank");
      }else{
        window.location.href = "{{url('PMO/project/detail')}}/"+id_pmo + "?showProject";
      }
    }

    function checkDocUpload(element){
        var f= element[0].files[0]
        var filePath = f;
     
        // Allowing file type
        var allowedExtensions =
        /(\.pdf)$/i;

        var ErrorText = []
        // 
        if (f.size > 30000000|| f.fileSize > 30000000) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file size, just allow file with size less than 30MB!',
          }).then((result) => {
            element[0].value = ''
          })
        }

        var ext = filePath.name.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["pdf"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file type, just allow pdf file',
          }).then((result) => {
            element[0].value = ''
          })
        }

        return false
    }

    function saveProject(id_pmo,status){
      if ($("#inputPO").val() == "") {
        $("#inputPO").closest("div").addClass("has-error")
        $("#inputPO").next("span").show()
      }else if ($("#inputToR").val() == "") {
        $("#inputToR").closest("div").addClass("has-error")
        $("#inputToR").next("span").show()
      }else if ($("#inputSbe").val() == "") {
        $("#inputSbe").closest("div").addClass("has-error")
        $("#inputSbe").next("span").show()
      }else{
        swalFireCustom = {
          title: 'Are you sure?',
          text: "Submit Project Charter",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        let arrCbTechUse = [], arrInternalStakeHolder = [], arrIdentifiedRisk = [], arrInputDocPendukung = []
        $("input[name='cbTechUse']:checked").each(function(idx,item){
          arrCbTechUse.push(item.value)
        })

        $("#tbodyInternalStakeholderRegister tr").each(function(){
          arrInternalStakeHolder.push({
            "nik":$(this).find("#selectNameStakeholder").val(),
            "role":$(this).find("#selectRoleStakeholder").val(),
            "email":$(this).find("#inputEmailStakeholder").val(),
            "phone":$(this).find("#inputPhoneStakeholder").val()})
        })

        $("#tbodyIdentifiedRisk tr").each(function(){
          arrIdentifiedRisk.push({
            "risk":$(this).find("#textAreaRisk").val(),
            "owner":$(this).find("#inputOwner").val(),
            "impact":$(this).find("#inputImpact").val(),
            "likelihood":$(this).find("#inputLikelihood").val(),
            "rank":$(this).find("#inputRank").val(),
            "description":$(this).find("#textareaDescription").val(),
            "response":$(this).find("#textareaResponse").val(),
            "due_date":$(this).find("#due_date").val(),
            "review_date":$(this).find("#review_date").val(),
            "status":$(this).find("#selectStatusProjectCharter").val(),
          })

        })

        formData = new FormData

        $('#tableUploadDoc .trDoc').each(function() {
          formData.append('inputDoc[]',$(this).find('input[name="inputDoc"]').prop('files')[0])
          arrInputDocPendukung.push({
            nameDocPendukung:$(this).find('input[name="inputDocTitle"]').val(),
          }) 
        })

        formData.append("_token","{{ csrf_token() }}")
        formData.append("project_id",$("#inputPID").val())
        formData.append("id_pmo",id_pmo)
        formData.append("inputCustomer",$("#inputCustomer").val())
        formData.append("textAreaAddress",$("#textAreaAddress").val())
        formData.append("inputPhone",$("#inputPhone").val())
        formData.append("inputContactPerson",$("#inputContactPerson").val())
        formData.append("inputEmail",$("#inputEmail").val())
        formData.append("inputCpPhone",$("#inputCpPhone").val())
        formData.append("inputCpTitle",$("#inputCpTitle").val())
        formData.append("inputCompanyLogo",$('#inputCompanyLogo').prop('files')[0])
        formData.append("textAreaProjectDesc",$("#textAreaProjectDesc").val())
        formData.append("textAreaProjectObj",$("#textAreaProjectObj").val())
        formData.append("arrCbTechUse",JSON.stringify(arrCbTechUse))
        formData.append("inputStartDate",$("#inputStartDate").val())
        formData.append("inputFinishDate",$("#inputFinishDate").val())
        formData.append("selectFlexibility",$("#selectFlexibility").val())
        formData.append("selectMarketSegment",$("#selectMarketSegment").val())
        formData.append("textAreaSOW",$("#textAreaSOW").val())
        formData.append("textAreaOutOfScope",$("#textAreaOutOfScope").val())
        formData.append("textAreaCustomerRequirement",$("#textAreaCustomerRequirement").val())
        formData.append("textAreaTOP",$("#textAreaTOP").val())
        formData.append("arrInternalStakeHolder",JSON.stringify(arrInternalStakeHolder))
        formData.append("arrIdentifiedRisk",JSON.stringify(arrIdentifiedRisk))
        formData.append("inputPO",$("#inputPO").prop('files')[0])
        formData.append("inputToR",$("#inputToR").prop('files')[0])
        formData.append("inputSbe",$("#inputSbe").prop('files')[0])
        formData.append("arrInputDocPendukung",JSON.stringify(arrInputDocPendukung))

        swalSuccess = {
          icon: 'success',
          title: 'Document Project Charter has been created!',
          text: 'Project Charter will processed soon, please wait for further progress',
        }
        url = '/PMO/updateProjectCharter'
        createPost(swalFireCustom,formData,swalSuccess,url,redirect="{{url('/PMO/project')}}")

        // if(checkDocUpload($("#inputPO")) && checkDocUpload($("#inputToR")) && checkDocUpload($("#inputSbe"))){  
        // }
      }
    }

    function saveAssign(){
      let elementPM = document.getElementById('selectPM')
      let elementPC = document.getElementById('selectPC')
      if ($("#selectPIDAssign").val() == "") {
        $("#selectPIDAssign").closest("div").addClass("has-error")
        $("#selectPIDAssign").closest("div").find(".help-block").show()
      }else if($("#inputProjectTitle").val() == ""){
        $("#inputProjectTitle").closest("div").addClass("has-error")
        $("#inputProjectTitle").next("span").show()
      }else if($("input[name='cbProjectType']:checked").length == 0){
        $("input[name='cbProjectType']").closest("div").closest("label").closest(".form-group").addClass("has-error")
        $("input[name='cbProjectType']").closest("div").closest("label").closest("div").next("span").show()      
      }else if (!elementPM.disabled && !elementPC.disabled) {
        switch($("#selectPM").val()){
          case "":
            $("#selectPM").closest("div").addClass("has-error")
            $("#selectPM").next("span").next("span").show()
          break;
          default:
          switch($("#selectPC").val()){
            case "":
              $("#selectPC").closest("div").addClass("has-error")
              $("#selectPC").next("span").next("span").show()
            break;
            default:
            postAssign()
          }
        }
      }else if (!elementPM.disabled) {
        switch($("#selectPM").val()){
          case "":
            $("#selectPM").closest("div").addClass("has-error")
            $("#selectPM").next("span").next("span").show()
          break;
          default:
          postAssign()
        }
      }else if (!elementPC.disabled) {
        switch($("#selectPC").val()){
          case "":
            $("#selectPC").closest("div").addClass("has-error")
            $("#selectPC").next("span").next("span").show()
          break;
          default:
          postAssign()
        }
      }
    }

    function postAssign(){
        swalFireCustom = {
          title: 'Are you sure?',
          text: "Project Assignment",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        let cbProjectType = [], cbImpelementType = []

        $("input[name='cbProjectType']:checked").each(function () {
          cbProjectType.push(this.value)
        })

        $("input[name='cbImpelementType']:checked").each(function () {
          cbImpelementType.push(this.value)
        })

        formData = new FormData
        // data = {
        //   _token:"{{csrf_token()}}",
        //   selectPIDAssign:$("#selectPIDAssign").val(),
        //   selectPM:$("#selectPM").val(),
        //   selectPC:$("#selectPC").val(),
        //   cbProjectType:cbProjectType,
        //   cbImpelementType:cbImpelementType
        // }

        formData.append("_token","{{csrf_token()}}")
        formData.append("selectPIDAssign",$("#selectPIDAssign").val())
        formData.append("selectPM",$("#selectPM").val())
        formData.append("selectPC",$("#selectPC").val())
        formData.append("cbProjectType",JSON.stringify(cbProjectType))
        formData.append("cbImpelementType",JSON.stringify(cbImpelementType))

        swalSuccess = {
          icon: 'success',
          title: 'Project Charter has been Assigned!',
          text: 'Click Ok to reload page',
        }

        createPost(swalFireCustom,formData,swalSuccess,url="/PMO/assignProject",redirect="{{url('/PMO/project')}}")
    }

    $('#ModalProjectCharter').on('hidden.bs.modal', function () {
      arrReason = []
      $(this).find('form').find("input[type=text],input[type=number],input[type=email],input[type=file],textarea").val("")
      $(".tab-add").css('display','none')
      currentTab = 0
      n = 0
    })

    function cancelModal(){
      $(this).find('form').find("input[type=text],input[type=number],input[type=email],input[type=file],textarea").val("")

      $("#ModalProjectCharter").modal("hide")
      currentTab = 0
    }

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
    })

    function nextPrevAdd(n,id_project,status){
      if (status == 'revision') {
        arrReason = []
        if (currentTab == 0) {
          url = "/PMO/updateCustomerInfoProjectCharter"
        }else if(currentTab == 1){
          url = "/PMO/updateProjectInformationProjectCharter"
        }else if (currentTab == 2) {
          url = "/PMO/updateInternalStakholder"
        }else if (currentTab == 3) {
          url = "/PMO/updateIdentifiedRisk"
        }
      }else{
        if (currentTab == 0) {
          if (status == 'create') {
            url = "/PMO/storeCustomerInfoProjectCharter"
          }else{
            url = "/PMO/updateCustomerInfoProjectCharter"
          }
        }else if(currentTab == 1){
          url = "/PMO/updateProjectInformationProjectCharter"
        }else if (currentTab == 2) {
          url = "/PMO/updateInternalStakholder"
        }else if (currentTab == 3) {
          url = "/PMO/updateIdentifiedRisk"
        }
      }

      let arrCbTechUse = [], arrInternalStakeHolder = [], arrIdentifiedRisk = [], arrInputDocPendukung = []
      if (currentTab == 0) {
        if (n == 1) {
          if ($("#inputCustomer").val() == "") {
            $("#inputCustomer").closest("div").addClass("has-error")
            $("#inputCustomer").next("span").show()
          }else if($("#textAreaAddress").val() == ""){
            $("#textAreaAddress").closest("div").addClass("has-error")
            $("#textAreaAddress").next("span").show()
          }else if($("#inputPhone").val() == ""){
            $("#inputPhone").closest("div").addClass("has-error")
            $("#inputPhone").next("span").show()
          }else if($("#inputContactPerson").val() == ""){
            $("#inputContactPerson").closest("div").addClass("has-error")
            $("#inputContactPerson").next("span").show()
          }else if($("#inputEmail").val() == ""){
            $("#inputEmail").closest("div").addClass("has-error")
            $("#inputEmail").next("span").show()
          }else if($("#inputCpPhone").val() == ""){
            $("#inputCpPhone").closest("div").addClass("has-error")
            $("#inputCpPhone").next("span").show()
          }else if($("#inputCpTitle").val() == ""){
            $("#inputCpTitle").closest("div").addClass("has-error")
            $("#inputCpTitle").next("span").show()
          }else if($("#inputCompanyLogo").val() == ""){
            $("#inputCompanyLogo").closest("div").addClass("has-error")
            $("#inputCompanyLogo").next("span").show()
          }else{
            const validateEmail = (email) => {
              return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
              )
            }

            if (validateEmail($("#inputEmail").val()) != null) {
              formData = new FormData;

              formData.append("_token","{{csrf_token()}}")
              formData.append("id_pmo",id_project)
              formData.append("inputCustomer",$("#inputCustomer").val())
              formData.append("textAreaAddress",$("#textAreaAddress").val())
              formData.append("inputPhone",$("#inputPhone").val())
              formData.append("inputContactPerson",$("#inputContactPerson").val())
              formData.append("inputEmail",$("#inputEmail").val())
              formData.append("inputCpPhone",$("#inputCpPhone").val())
              formData.append("inputCpTitle",$("#inputCpTitle").val())
              formData.append("inputCompanyLogo",$('#inputCompanyLogo').prop('files')[0])

              $.ajax({
                url:"{{url('/')}}"+url,
                type:"POST",
                processData: false,
                contentType: false,
                data:formData,
                success:function(){
                  localStorage.setItem("isStoreProject",true)

                  let x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  btnAddProjectCharter(currentTab,id_project,status);
                }
              })
            }else{
              $("#inputEmail").closest('.form-group').addClass('has-error')
              $("#inputEmail").closest('input').next('span').show();
              $("#inputEmail").prev('.input-group-addon').css("background-color","red");
              $("#inputEmail").closest('input').next('span').text("Enter a Valid Email Address!")
            }
          }
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          btnAddProjectCharter(currentTab,id_project,status);
        }
      }else if(currentTab == 1){
        if (n == 1) {
          if ($("#inputPID").val() == "") {
            $("#inputPID").closest("div").addClass("has-error")
            $("#inputPID").next("span").show()
          }else if($("#textAreaProjectDesc").val() == ""){
            $("#textAreaProjectDesc").closest("div").addClass("has-error")
            $("#textAreaProjectDesc").next("span").show()
          }else if($("#textAreaProjectObj").val() == ""){
            $("#textAreaProjectObj").closest("div").addClass("has-error")
            $("#textAreaProjectObj").next("span").show()
          }else if($("input[name='cbTechUse']:checked").length == 0){
            $("input[name='cbTechUse']").closest("div").closest("label").closest(".form-group").addClass("has-error")
            $("input[name='cbTechUse']").closest("div").closest("label").closest(".form-group").find("span").show()
          }else if($("#inputStartDate").val() == ""){
            $("#inputStartDate").closest(".form-group").addClass("has-error")
            $("#inputStartDate").closest(".form-group").find("span.help-block").show()
          }else if($("#inputFinishDate").val() == ""){
            $("#inputFinishDate").closest(".form-group").addClass("has-error")
            $("#inputFinishDate").closest(".form-group").find("span.help-block").show()
          }else if($("#selectFlexibility").val() == ""){
            $("#selectFlexibility").closest("div").addClass("has-error")
            $("#selectFlexibility").next("span").show()
          }else if($("#selectMarketSegment").val() == ""){
            $("#selectMarketSegment").closest("div").addClass("has-error")
            $("#selectMarketSegment").next("span").show()
          }else{
            $("input[name='cbTechUse']:checked").each(function(idx,item){
              arrCbTechUse.push(item.value)
            })

            formData = new FormData;

            formData.append("_token","{{csrf_token()}}")
            formData.append("id_pmo",id_project)
            formData.append("textAreaProjectDesc",$("#textAreaProjectDesc").val())
            formData.append("textAreaProjectObj",$("#textAreaProjectObj").val())
            formData.append("arrCbTechUse",JSON.stringify(arrCbTechUse))
            formData.append("inputStartDate",$("#inputStartDate").val())
            formData.append("inputFinishDate",$("#inputFinishDate").val())
            formData.append("selectFlexibility",$("#selectFlexibility").val())
            formData.append("selectMarketSegment",$("#selectMarketSegment").val())

            $.ajax({
              url:"{{url('/')}}"+url,
              type:"POST",
              processData: false,
              contentType: false,
              data:formData,
              beforeSend:function(){
                $("#nextBtnAdd").prop("disabled",true)
              },success:function(){
                let x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                btnAddProjectCharter(currentTab,id_project,status);
                $("#nextBtnAdd").prop("disabled",false)

              }
            })
          }         
          
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          btnAddProjectCharter(currentTab,id_project,status);
        }
      }else if (currentTab == 2) {
        if (n == 1) {
          if ($("#textAreaSOW").val() == "") {
            $("#textAreaSOW").closest("div").addClass("has-error")
            $("#textAreaSOW").next("span").show()
          }else if($("#textAreaOutOfScope").val() == ""){
            $("#textAreaOutOfScope").closest("div").addClass("has-error")
            $("#textAreaOutOfScope").next("span").show()
          }else if($("#textAreaCustomerRequirement").val() == ""){
            $("#textAreaCustomerRequirement").closest("div").addClass("has-error")
            $("#textAreaCustomerRequirement").next("span").show()
          }else if($("#textAreaTOP").val() == ""){
            $("#textAreaTOP").closest("div").addClass("has-error")
            $("#textAreaTOP").next("span").show()
          }else if($("#tbodyInternalStakeholderRegister tr").length == 0){
            $("#tbInternalStakeholderRegister").closest(".form-group").addClass("has-error")
            $("#tbInternalStakeholderRegister").closest(".form-group").find("span").show()
          }else{
            let arrInternalStakeHolder = []

            $("#tbodyInternalStakeholderRegister tr").each(function(){
              arrInternalStakeHolder.push({
                "nik":$(this).find("#selectNameStakeholder").val(),
                "role":$(this).find("#selectRoleStakeholder").val(),
                "email":$(this).find("#inputEmailStakeholder").val(),
                "phone":$(this).find("#inputPhoneStakeholder").val()})
            })

            formData = new FormData;

            formData.append("_token","{{csrf_token()}}")
            formData.append("id_pmo",id_project)
            formData.append("textAreaSOW",$("#textAreaSOW").val())
            formData.append("textAreaOutOfScope",$("#textAreaOutOfScope").val())
            formData.append("textAreaCustomerRequirement",$("#textAreaCustomerRequirement").val())
            formData.append("textAreaTOP",$("#textAreaTOP").val())
            formData.append("arrInternalStakeHolder",JSON.stringify(arrInternalStakeHolder))

            $.ajax({
              url:"{{url('/')}}"+url,
              type:"POST",
              processData: false,
              contentType: false,
              data:formData
              ,success:function(){
                let x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                btnAddProjectCharter(currentTab,id_project,status);
                console.log(status)
              }
            })
          }
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          btnAddProjectCharter(currentTab,id_project,status);
        }            
      }else if(currentTab == 3){
        if (n == 1) {
          $numItems = $("#tbodyIdentifiedRisk tr").length;
          $i = 0;

          $("#tbodyIdentifiedRisk tr").each(function() {
            if ($("#textAreaRisk[data-value='"+ $(this).find('#textAreaRisk').data("value") +"']").val() == "") {
              $("#textAreaRisk[data-value='"+ $(this).find('#textAreaRisk').data("value") +"']").closest("div").addClass("has-error")
              $("#textAreaRisk[data-value='"+ $(this).find('#textAreaRisk').data("value") +"']").next("span").show()
            }else if ($("#inputOwner[data-value='"+ $(this).find('#inputOwner').data("value") +"']").val() == "") {
              $("#inputOwner[data-value='"+ $(this).find('#inputOwner').data("value") +"']").closest("div").addClass("has-error")
              $("#inputOwner[data-value='"+ $(this).find('#inputOwner').data("value") +"']").next("span").show()
            }else if ($("#inputImpact[data-value='"+ $(this).find('#inputImpact').data("value") +"']").val() == "") {
              $("#inputImpact[data-value='"+ $(this).find('#inputImpact').data("value") +"']").closest("div").addClass("has-error")
              $("#inputImpact[data-value='"+ $(this).find('#inputImpact').data("value") +"']").next("span").show()
            }else if ($("#inputLikelihood[data-value='"+ $(this).find('#inputLikelihood').data("value") +"']").val() == "") {
              $("#inputLikelihood[data-value='"+ $(this).find('#inputLikelihood').data("value") +"']").closest("div").addClass("has-error")
              $("#inputLikelihood[data-value='"+ $(this).find('#inputLikelihood').data("value") +"']").next("span").show()
            }else if ($("#inputRank[data-value='"+ $(this).find('#inputRank').data("value") +"']").val() == "") {
              $("#inputRank[data-value='"+ $(this).find('#inputRank').data("value") +"']").closest("div").addClass("has-error")
              $("#inputRank[data-value='"+ $(this).find('#inputRank').data("value") +"']").next("span").show()
            }else if ($("#textareaDescription[data-value='"+ $(this).find('#textareaDescription').data("value") +"']").val() == "") {
              $("#textareaDescription[data-value='"+ $(this).find('#textareaDescription').data("value") +"']").closest("div").addClass("has-error")
              $("#textareaDescription[data-value='"+ $(this).find('#textareaDescription').data("value") +"']").next("span").show()
            }else if ($("#textareaResponse[data-value='"+ $(this).find('#textareaResponse').data("value") +"']").val() == "") {
              $("#textareaResponse[data-value='"+ $(this).find('#textareaResponse').data("value") +"']").closest("div").addClass("has-error")
              $("#textareaResponse[data-value='"+ $(this).find('#textareaResponse').data("value") +"']").next("span").show()
            }else if ($("#due_date[data-value='"+ $(this).find('#due_date').data("value") +"']").val() == "") {
              $("#due_date[data-value='"+ $(this).find('#due_date').data("value") +"']").closest(".form-group").addClass("has-error")
              $("#due_date[data-value='"+ $(this).find('#due_date').data("value") +"']").closest(".form-group").find("span").show()
            }else if ($("#review_date[data-value='"+ $(this).find('#review_date').data("value") +"']").val() == "") {
              $("#review_date[data-value='"+ $(this).find('#review_date').data("value") +"']").closest(".form-group").addClass("has-error")
              $("#review_date[data-value='"+ $(this).find('#review_date').data("value") +"']").closest(".form-group").find("span").show()
            }else if ($("#selectStatusProjectCharter[data-value='"+ $(this).find('#selectStatusProjectCharter').data("value") +"']").val() == "") {
              $("#selectStatusProjectCharter[data-value='"+ $(this).find('#selectStatusProjectCharter').data("value") +"']").closest("div").addClass("has-error")
              $("#selectStatusProjectCharter[data-value='"+ $(this).find('#selectStatusProjectCharter').data("value") +"']").next("span").show()
            }else{
              arrIdentifiedRisk.push({
                "risk":$(this).find("#textAreaRisk").val(),
                "owner":$(this).find("#inputOwner").val(),
                "impact":$(this).find("#inputImpact").val(),
                "likelihood":$(this).find("#inputLikelihood").val(),
                "rank":$(this).find("#inputRank").val(),
                "description":$(this).find("#textareaDescription").val(),
                "response":$(this).find("#textareaResponse").val(),
                "due_date":$(this).find("#due_date").val(),
                "review_date":$(this).find("#review_date").val(),
                "status":$(this).find("#selectStatusProjectCharter").val(),
              })
              if(++$i === $numItems) {
               saveTab(arrIdentifiedRisk)
              }
            }
          })
          
          function saveTab(arrIdentifiedRisk){
            // let arrIdentifiedRisk = []
            $.ajax({
              url:"{{url('/')}}"+url,
              type:"POST",
              data:{
                _token:"{{csrf_token()}}",
                id_pmo:id_project,
                arrIdentifiedRisk:JSON.stringify(arrIdentifiedRisk)
              },success:function(){
                let x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                btnAddProjectCharter(currentTab,id_project,status);
              }
            })
          }
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          btnAddProjectCharter(currentTab,id_project,status);
        }
      }else{
        let x = document.getElementsByClassName("tab-add");
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
          x[n].style.display = "none";
          currentTab = 0;
        }
        btnAddProjectCharter(currentTab,id_project,status);
      }      
    } 

    var incrementDoc = 0
    function addDocPendukung(){
      incrementDoc++

      append = ""
      append = append + '<tr class="trDoc" style="margin-top:5px">'
      append = append + '  <td><span style="'
      append = append + '    display: inline;'
      append = append + '"'
      append = append + 'class="btnRemoveDoc_'+ incrementDoc +'"'
      append = append + '><i class="fa fa-times" style="'
      append = append + '    margin-top: 10px;'
      append = append + '    color: red;'
      append = append + '"></i>'
      append = append + '</span>'
      append = append + '    &nbsp;<div style="display: inline;float: right;padding:5px;border:solid 1px #cccc;width: 280px;"> <label for="inputDoc_'+ incrementDoc +'" class="fa fa-upload" id="title_doc_'+ incrementDoc +'" data-value="'+ incrementDoc +'">&nbsp; <span>Upload Document</span> <input type="file" class="document" name="inputDoc" id="inputDoc_'+ incrementDoc +'" data-value="'+ incrementDoc +'" style="display: none;" onchange="validationCheck(this)"></label></div>'  
      append = append + '  </td>'
      append = append + '  <td>'
      append = append + '   <input placeholder="Enter Document Name" type="text" name="inputDocTitle" id="inputDocTitle_'+ incrementDoc +'" class="form-control" style="width:250px" data-value="'+ incrementDoc +'">'
      append = append + '  </td>'
      append = append + '</tr>'
      $("#tableUploadDoc").append(append) 

      $("#btnAddDoc").prop("disabled",true) 
      $("#tableUploadDoc .trDoc").each(function(){
        let inputData = $(this)
        
        $("#inputDoc_"+ $(this).find('input[name="inputDoc"]').attr('data-value')).change(function(){          
          if (this.value != "") {
            $("#title_doc_"+ inputData.find('input[name="inputDoc"]').attr('data-value')).find("span").remove()
            $("#inputDoc_"+ inputData.find('input[name="inputDoc"]').attr('data-value')).css("display","inline")
          }
        })

        $('#inputDocTitle_'+ inputData.find('input[name="inputDoc"]').attr('data-value')).keydown(function(){          
          if (this.value == "") {
            $("#btnAddDoc").prop("disabled",true)
          }else{
            $("#btnAddDoc").prop("disabled",false)
          }
        })  

        $(".btnRemoveDoc_"+inputData.find('input[name="inputDoc"]').attr('data-value')).click(function(){
          let incrementDocBefore = parseInt(inputData.find('input[name="inputDoc"]').data('value'))-1
          
          $(".btnRemoveDoc_"+inputData.find('input[name="inputDoc"]').data('value')).closest("tr").remove();
          if ($("#inputDocTitle_"+ incrementDocBefore).val() == "") {
            $("#btnAddDoc").prop("disabled",true)
          }else{
            $("#btnAddDoc").prop("disabled",false)
          }
        })

      })   
    }

    function AssignMember(){
      //select2 communion
      $.ajax({
        type:"GET",
        url:"{{url('/PMO/getListDataPid')}}",
        success: function(data)
        {
          $("#selectPIDAssign").select2({
            placeholder:"Select Project ID Number",
            dropdownParent: $('#ModalAssign'),
            data: data,
            selectOnClose: true
          }).on('select2:select', function (e) {
            let filteredName = filterOppName(e.params.data.id)
            $("#inputProjectTitle").val(filteredName)
          });  
        }
      })

      function filterOppName(pid){
        let opty = ""
        $.ajax({
          async: false,
          type:"GET",
          url:"{{url('/PMO/getListDataPid')}}",
          data:{
           pid:pid 
          },
          success: function(data)
          { 
            $.each(data,function(index,result){
              opty = result.opp_name
              // console.log(result.opp_name)
            })
          }      
        })

        return opty
      }
      $("#ModalAssign").modal("show")
      let arrChecked = []

      $("input[name='cbProjectType']").on('ifChecked', function(event) {
          arrChecked = []

          $("input[name='cbProjectType']:checked").each(function(index,value){
            arrChecked.push(value.value)
          })

          append = ""
          if(this.value == 'implementation'){
            append = append + "<div class='form-group'><label>Implementation type*</label>"
            append = append + "  <div style='padding:10px;border:solid 1px #cccc;'>"
            append = append + "    <label style='margin-right: 15px;'><input checked autocomplete='off' type='checkbox' name='cbImpelementType' class='minimal' id='' value='hardware'>Hardware</label>"
            append = append + "    <label style='margin-right: 15px;'><input checked autocomplete='off' type='checkbox' name='cbImpelementType' class='minimal' id='' value='service'>Service</label>"
            append = append + "    <label style='margin-right: 15px;'><input checked autocomplete='off' type='checkbox' name='cbImpelementType' class='minimal' id='' value='license'>License</label>"
            append = append + "  </div>"
            append = append + "  <span class='help-block' style='display:none;'>Please Select Implementation Type!</span>"
            append = append + "</div>"

            $(this).closest("div .form-group").after(append)

            if ($(this).closest(".form-group").siblings('.form-group').length > 1) {
              $(this).closest("div .form-group").next("div .form-group:last").remove()
            }

            if (arrChecked.filter(x => x == "maintenance").length == 1) {
              $('#selectPM').prop("disabled",false)
              $('#selectPC').prop("disabled",false)
            }else if(arrChecked.filter(x => x == "supply_only").length == 1){
              $("#selectPM").val("").trigger("change")
              $("#selectPC").val("").trigger("change")

              $('#selectPM').prop("disabled",true)
              $('#selectPC').prop("disabled",true)
            }else{
              $('#selectPM').prop("disabled",false)
              $('#selectPC').prop("disabled",true)
            }
          }else if (this.value == 'maintenance') {
            if (arrChecked.filter(x => x == "implementation").length == 1) {
              $('#selectPM').prop("disabled",false)
              $('#selectPC').prop("disabled",false)
            }else if(arrChecked.filter(x => x == "supply_only").length == 1){
              $("#selectPM").val("").trigger("change")
              $("#selectPC").val("").trigger("change")

              $('#selectPM').prop("disabled",true)
              $('#selectPC').prop("disabled",true)
            }else{
              console.log("maintenance aja")
              $('#selectPM').prop("disabled",true)
              $('#selectPC').prop("disabled",false)
            }
          }else if (this.value == 'supply_only') {
             if (arrChecked.filter(x => x == "implementation").length == 1) {
              $("#selectPM").val("").trigger("change")
              $("#selectPC").val("").trigger("change")

              $('#selectPM').prop("disabled",true)
              $('#selectPC').prop("disabled",true)
            }else if(arrChecked.filter(x => x == "maintenance").length == 1){
              $("#selectPM").val("").trigger("change")
              $("#selectPC").val("").trigger("change")

              $('#selectPM').prop("disabled",true)
              $('#selectPC').prop("disabled",true)
            }else{
              $('#selectPM').prop("disabled",false)
              $('#selectPC').prop("disabled",true)
            }
          }else{
            $('#selectPM').prop("disabled",true)
            $('#selectPC').prop("disabled",true)
          }    

      })

      $("input[name='cbProjectType']").on('ifUnchecked', function (event) {
        if (this.value == 'implementation') {
          arrChecked.splice(arrChecked.indexOf(this.value), this.value)
          $("#selectPM").val("").trigger("change")
          $(this).closest("div .form-group").closest("div").next("div").text("")
          if (arrChecked.filter(x => x == "maintenance").length == 1) {
            $('#selectPM').prop("disabled",true)
            $('#selectPC').prop("disabled",false)
          }else if(arrChecked.filter(x => x == "supply_only").length == 1){
            $('#selectPM').prop("disabled",false)
            $('#selectPC').prop("disabled",true)
          }else{
            console.log("")
            $('#selectPC').prop("disabled",true)
          }
        }

        if (this.value == 'maintenance') {
          arrChecked.splice(arrChecked.indexOf(this.value), this.value)
          $("#selectPC").val("").trigger("change")
          if (arrChecked.filter(x => x == "implementation").length == 1) {
            $('#selectPM').prop("disabled",false)
            $('#selectPC').prop("disabled",true)
          }else if(arrChecked.filter(x => x == "supply_only").length == 1){
            $('#selectPM').prop("disabled",false)
            $('#selectPC').prop("disabled",true)
          }else{
            $('#selectPC').prop("disabled",true)
            $('#selectPM').prop("disabled",true)
          }
        }

        if (this.value == 'supply_only') {
          if (arrChecked.filter(x => x == "implementation").length == 1) {
            $('#selectPM').prop("disabled",false)
            $('#selectPC').prop("disabled",true)
          }else if(arrChecked.filter(x => x == "maintenance").length == 1){
            $('#selectPM').prop("disabled",true)
            $('#selectPC').prop("disabled",false)
          }else{
            $("#selectPM").val("").trigger("change")
            $("#selectPC").val("").trigger("change")

            $('#selectPM').prop("disabled",true)
            $('#selectPC').prop("disabled",true)
          }
        }

      });   

      $.ajax({
        type:"GET",
        url:"{{url('/PMO/getPCStaff')}}",
        success:function(result){
          $('#selectPC').select2({
            data:result.data,
            placeholder:"Select PC",
            allowClear: true
          });
        }
      })

      $.ajax({
        type:"GET",
        url:"{{url('/PMO/getPMStaff')}}",
        success:function(result){
          $('#selectPM').select2({
            data:result.data,
            placeholder:"Select PM",
            allowClear: true
          });
        }
      })
    }

    function detailProject(id_pmo,type){
      window.open("{{url('/PMO/project/detail')}}/"+id_pmo+"?project_type="+type,"_blank")
    }

    function deleteAssign(id){
        swalFireCustom = {
            title: 'Are you sure?',
            text: "Delete Assigned Project - New",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }

        formData = new FormData

        formData.append("_token","{{ csrf_token() }}")
        formData.append("id_pmo",id)

        swalSuccess = {
          icon: 'success',
          title: 'Assigned Project - New, has been deleted!',
          text: 'Successfully',
        }
        url = '/PMO/deleteAssign'
        createPost(swalFireCustom,formData,swalSuccess,url,redirect="{{url('/PMO/project')}}")          
    }

    let incIstakeholder = 0
    function btnPlusIStakeholder(){
      $("#tbInternalStakeholderRegister").closest(".form-group").removeClass("has-error")
      $("#tbInternalStakeholderRegister").closest(".form-group").find("span").hide()

      append = ""
      append = 
      append = append +'<tr>'
      append = append +'  <td><select id="selectNameStakeholder" name="selectNameStakeholder" class="select2 form-control selectNameStakeholder" data-value="'+ incIstakeholder +'"><option></option></select></td>'
      append = append +'  <td><select style="font-size:12px" id="selectRoleStakeholder" class="select2 form-control" data-value="'+ incIstakeholder +'">'
      append = append + ' <option value="Project Steering Committee">Project Steering Committee</option>'
      append = append + ' <option value="Project Owner">Project Owner</option>'
      append = append + ' <option value="Project Advisor">Project Advisor</option>'
      append = append + ' <option value="Project Manager">Project Manager</option>'
      append = append + ' <option value="Co-Project Manager">Co - Project Manager</option>'
      append = append + ' <option value="Project Coordinator">Project Coordinator</option>'
      append = append + ' <option value="Project Administrator">Project Administrator</option>'
      append = append + ' <option value="Site Manager">Site Manager</option>'
      append = append + ' <option value="HSE">HSE</option>'
      append = append + ' <option value="Drafter">Drafter</option>'
      append = append + ' <option value="Technical Writer">Technical Writer</option>'
      append = append + ' <option value="Solution Architect">Solution Architect</option>'
      append = append + ' <option value="Technical Lead Engineer">Technical Lead Engineer</option>'
      append = append + ' <option value="IT Network Engineer">IT Network Engineer</option>'
      append = append + ' <option value="IT Network Security Engineer">IT Network Security Engineer</option>'
      append = append + ' <option value="IT System Engineer">IT System Engineer</option>'
      append = append + ' <option value="Cabling Engineer">Cabling Engineer</option>'
      append = append + ' <option value="MSM Technical Lead Engineer">MSM Technical Lead Engineer</option>'
      append = append + ' <option value="MSM Engineer">MSM Engineer</option>'
      append = append + ' <option value="Helpdesk">Helpdesk</option>'
      append = append + ' <option value="Procurement">Procurement</option>'
      append = append + ' <option value="WH Delivery Team">WH Delivery Team</option>'
      append = append + ' <option value="Legal">Legal</option>'
      append = append +'</select></td>'
      append = append +'  <td><input id="inputEmailStakeholder" style="width:90px" class="form-control disabled" disabled data-value="'+ incIstakeholder +'"/></td>'
      append = append +'  <td><input id="inputPhoneStakeholder" style="width:90px" class="form-control disabled" disabled data-value="'+ incIstakeholder +'"/></td>'
      append = append +'  <td><button type="button" class="fa fa-trash" style="color:red;background-color:transparent;border:none;margin-top:10px" id="btnDeleteRowIStakeholder" class="form-control"data-value="'+ incIstakeholder +'"/></td>'
      append = append +'</tr>'

      $("#tbodyInternalStakeholderRegister").append(append)

      $.ajax({
        url:"{{url('/PMO/getUser')}}",
        type:"GET",
        success:function(result){
          $(".selectNameStakeholder").select2({
            data:result.data,
            placeholder:"Select Name Stakeholder",
            dropdownCssClass: "myFont" 
          }).on('select2:select', function (e) {
            let filteredEmailPhone = filterByStakeholderName(e.params.data.id)
            // let filteredPhone = filterByStakeholderName(e.params.data.id)
            $(this).closest("td").next("td:nth-child(2)").next("td:nth-child(3)").find("input").val(filteredEmailPhone[0])
            $(this).closest("td").next("td:nth-child(2)").next("td:nth-child(3)").next("td:nth-child(4)").find("input").val(filteredEmailPhone[1])

          });
        }
      })

      function filterByStakeholderName(nik){
        let email = "", phone = ""

        $.ajax({
          async: false,
          type:"GET",
          url:"{{url('/PMO/getUser')}}",
          data:{
           nik:nik 
          },
          success: function(data)
          { 
            $.each(data,function(index,result){
              email = result[0].email
              phone = result[0].phone
            })
          }      
        })

        return [email,phone]
      }

      $(".select2").select2({
        dropdownCssClass: "myFont" 
      })

      incIstakeholder++
    }

    $(document).on('click', '#btnDeleteRowIStakeholder', function() {
      console.log("error")
      $(this).closest("tr").remove();
    })


    let incIdentifiedRisk = 0
    $("#btnAddIdentifiedRisk").click(function(){
      incIdentifiedRisk++

      append = ""
      append = append + '<tr>'
      append = append + '  <td>'
      append = append + '<i class="fa fa-trash pull-right" style="color:red" id="btnRemoveIdentifiedRisk"></i>'
      append = append + '    <div class="form-group">'
      append = append + '      <label>Risk Description*</label>'
      append = append + '      <textarea class="form-control" id="textAreaRisk" name="textAreaRisk" placeholder="Risk Description" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"></textarea>'
      append = append + '      <span class="help-block" style="display:none;">Please fill Risk Description!</span>'
      append = append + '    </div>'
      append = append + '    <div class="row">'
      append = append + '      <div class="col-md-6 col-xs-12">'
      append = append + '        <div class="form-group">'
      append = append + '          <label>Owner*</label> '
      append = append + '          <input type="text" class="form-control" id="inputOwner" name="inputOwner" placeholder="Owner" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'
      append = append + '          <span class="help-block" style="display:none;">Please fill Owner!</span>'
      append = append + '        </div>'
      append = append + '      </div>'
      append = append + '      <div class="col-md-3 col-xs-12">'
      append = append + '        <div class="form-group">'
      append = append + '          <label>Impact*&nbsp<i style="color:#f39c12;" class="fa fa-info-circle help-btn-impact" value="impact" data-value="'+ incIdentifiedRisk +'"></i></label> '
      append = append + '          <input max="5" min="1" type="number" class="form-control" id="inputImpact" name="inputImpact" placeholder="1-5" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'
      append = append + '          <span class="help-block" style="display:none;">Please fill Impact!</span>'
      append = append + '        </div>'
      append = append + '      </div>'
      append = append + '      <div class="col-md-3 col-xs-12">'
      append = append + '        <div class="form-group">'
      append = append + '          <label>Likelihood*&nbsp<i style="color:#f39c12;" class="fa fa-info-circle help-btn-likelihood" value="likelihood" data-value="'+ incIdentifiedRisk +'"></i></label> '
      append = append + '          <input max="5" min="1" type="number" class="form-control" id="inputLikelihood" name="inputLikelihood" placeholder="1-5" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'
      append = append + '          <span class="help-block" style="display:none;">Please fill Probability!</span>'
      append = append + '        </div>'
      append = append + '      </div>'
      append = append + '    </div>'
      // append = append + '    <div class="form-group">'      
      // append = append + '      <label>Rank*</label>'      
      // append = append + '      <input class="form-control" placeholder="Rank" id="inputRank" name="inputRank" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'      
      // append = append + '    </div>'      
      // append = append + '    <div class="form-group">'      
      // append = append + '      <label>Description*</label>'      
      // append = append + '      <textarea class="form-control" placeholder="Description" id="textareaDescription" name="textareaDescription" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"></textarea><span class="help-block" style="display:none;">Please fill Description!</span>'      
      // append = append + '    </div>'   
      append = append + '    <div class="row">'
      append = append + '        <div class="col-md-12 col-xs-12">'
      append = append + '          <div class="form-group">'
      append = append + '            <label>Response*</label> '
      append = append + '            <textarea class="form-control" id="textareaResponse" name="textareaResponse" placeholder="Response" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'
      append = append + '            </textarea><span class="help-block" style="display:none;">Please fill Risk Response!</span>'
      append = append + '          </div>'
      append = append + '        </div>'
      append = append + '    </div>'   
      append = append + '    <div class="row">'      
      append = append + '      <div class="col-md-4 col-xs-12">'      
      append = append + '        <div class="form-group">'      
      append = append + '          <label>Due Date*</label>'      
      append = append + '          <div class="input-group">'      
      append = append + '            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>'      
      append = append + '            <input type="text" name="due_date" class="form-control" id="due_date" onclick="showDatepicker("due_date")" placeholder="Select Due Date" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'      
      append = append + '          </div><span class="help-block" style="display:none;">Please fill Due Date!</span>'      
      append = append + '        </div>'      
      append = append + '      </div>'      
      append = append + '      <div class="col-md-4 col-xs-12">'      
      append = append + '        <div class="form-group">'      
      append = append + '          <label>Review Date*</label>'      
      append = append + '          <div class="input-group">'      
      append = append + '            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>'      
      append = append + '            <input type="text" name="review_date" class="form-control" id="review_date" onclick="showDatepicker("review_date")" placeholder="Select Review Date" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)"/>'      
      append = append + '          </div><span class="help-block" style="display:none;">Please fill Review Date!</span>'      
      append = append + '        </div>'      
      append = append + '      </div>'      
      append = append + '      <div class="col-md-4 col-xs-12">'      
      append = append + '        <div class="form-group">'      
      append = append + '          <label>Status*</label>'      
      append = append + '          <select class="form-control select2" id="selectStatusProjectCharter" data-value="'+ incIdentifiedRisk +'" onkeyup="validationCheck(this)">'      
      append = append + '            <option></option>'      
      append = append + '          </select><span class="help-block" style="display:none;">Please fill Status!</span>'      
      append = append + '        </div>'      
      append = append + '      </div>'      
      append = append + '    </div>'      
      append = append + '  </td>'
      append = append + '</tr>'

      $("#tbodyIdentifiedRisk").append(append)

      $("input[name='due_date'],input[name='review_date']").datepicker()

      selectStatusProjectCharter(incIdentifiedRisk)

      $(".help-btn-likelihood[data-value='"+ incIdentifiedRisk +"'],.help-btn-impact[data-value='"+ incIdentifiedRisk +"']").click(function() {
      // Animation complete.
        if ($("#divInfoRisk").length == 0 || $("#divInfoImpact").length == 0) {
            if ($("#divInfoImpact").length == 0) {
              $("#selectStatusProjectCharter[data-value='"+ incIdentifiedRisk +"']").closest(".row").after("<div class='form-group' id='divInfoImpact'> (5) <b>Critical</b> - Disaster with potential to lead to business failure.<br>(4) <b>Major</b> - Major event which will be endured with proper management.<br>(3) <b>Moderate</b> - Significant event which can be managed under normal circumstances.<br>(2) <b>Minor</b> - Event with consequences which can be readily absorbed but requires management effort to minimise the impact.<br>(1) <b>Insignificant</b> - Low level risk. Existing controls and procedures will cope with event.</div>")
            
              
            }
            
            if ($("#divInfoRisk").length == 0) {
              $("#selectStatusProjectCharter[data-value='"+ incIdentifiedRisk +"']").closest(".row").after("<div class='form-group' id='divInfoRisk'>(5) <b>Almost certain</b> - The event is expected to occur in most circumstances (daily / weekly)High level of known incidents (records / experiences)Strong likelihood of re-occurring, with high opportunities / means to re-occur<br>(4) <b>Likely</b> - The event will probably occur in most circumstances (monthly) Regular incidents known (recorded / experienced) Considerable opportunity, means to occur<br>(3) <b>Moderate</b> - The event should occur at some time (over 12 months) Few infrequent, random occurrences (recorded / experienced)Some opportunity or means to occur<br>(2) <b>Unlikely</b> - The event could occur at some time (2-5 years)No known incidents recorded or experienced Little opportunity, mean or reason to occur<br>(1) <b>Rare</b> - The event may occur only in exceptional circumstances (10 years) Highly unheard of Almost no opportunity to occur</div>")

            }


            if ($(this).attr('value') == 'impact') {
              $("#divInfoRisk").hide()
            }

            if ($(this).attr('value') == 'likelihood') {
              $("#divInfoImpact").hide()
            }
            
          

          // $("#inputRatingIssue").closest(".row").after("<div class='form-group' id='divInfoIssue'> (5)<b>Critical</b> - Disaster with potential to lead to business failure.<br>(4) <b>Major</b> - Major event which will be endured with proper management.<br>(3) <b>Moderate</b> - Significant event which can be managed under normal circumstances.<br>(2)<b>Minor</b> - Event with consequences which can be readily absorbed but requires management effort to minimise the impact.<br>(1) <b>Insignificant</b> - Low level risk. Existing controls and procedures will cope with event.</div>")
        }else{
          if ($(this).attr('value') == 'impact') {
            if ($('#divInfoImpact').is(':hidden')) {
              $("#divInfoImpact").show()
              $("#divInfoRisk").hide()
            }else{
              $("#divInfoImpact").hide()
            }
          }else if($(this).attr('value') == 'likelihood'){
            if ($('#divInfoRisk').is(':hidden')) {
              $("#divInfoRisk").show()
              $("#divInfoImpact").hide()
            }else{
              $("#divInfoRisk").hide()
            }
          }
        }
      })
    })  

    function showDatepicker(name){
      $("input[name="+ name +"]").datepicker({
        autoclose:true
      })
    }

    $(document).on('click', '#btnRemoveIdentifiedRisk', function() {
      console.log("error")
      $(this).closest("tr").remove();
    })

    function createPost(swalFireCustom,data,swalSuccess,url,redirect){
      Swal.fire(swalFireCustom).then((result) => {
          if (result.value) {
            $.ajax({
              type:"POST",
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
                    window.location.href = redirect
                  }
                })
              }
            })
          }
      })
    }

    $(".help-btn-likelihood,.help-btn-impact").click(function() {
    // Animation complete.
      if ($("#divInfoRisk").length == 0 || $("#divInfoImpact").length == 0) {
          if ($("#divInfoImpact").length == 0) {
            $("#selectStatusProjectCharter[data-value='"+ $(this).closest("label").closest(".form-group").find("input").data("value")+"']").closest(".row").after("<div class='form-group' id='divInfoImpact'> (5) <b>Critical</b> - Disaster with potential to lead to business failure.<br>(4) <b>Major</b> - Major event which will be endured with proper management.<br>(3) <b>Moderate</b> - Significant event which can be managed under normal circumstances.<br>(2) <b>Minor</b> - Event with consequences which can be readily absorbed but requires management effort to minimise the impact.<br>(1) <b>Insignificant</b> - Low level risk. Existing controls and procedures will cope with event.</div>")
          
            
          }
          
          if ($("#divInfoRisk").length == 0) {
            $("#selectStatusProjectCharter[data-value='"+ $(this).closest("label").closest(".form-group").find("input").data("value") +"']").closest(".row").after("<div class='form-group' id='divInfoRisk'>(5) <b>Almost certain</b> - The event is expected to occur in most circumstances (daily / weekly)High level of known incidents (records / experiences)Strong likelihood of re-occurring, with high opportunities / means to re-occur<br>(4) <b>Likely</b> - The event will probably occur in most circumstances (monthly) Regular incidents known (recorded / experienced) Considerable opportunity, means to occur<br>(3) <b>Moderate</b> - The event should occur at some time (over 12 months) Few infrequent, random occurrences (recorded / experienced)Some opportunity or means to occur<br>(2) <b>Unlikely</b> - The event could occur at some time (2-5 years)No known incidents recorded or experienced Little opportunity, mean or reason to occur<br>(1) <b>Rare</b> - The event may occur only in exceptional circumstances (10 years) Highly unheard of Almost no opportunity to occur</div>")

          }


          if ($(this).attr('value') == 'impact') {
            $("#divInfoRisk").hide()
          }

          if ($(this).attr('value') == 'likelihood') {
            $("#divInfoImpact").hide()
          }
          
        

        // $("#inputRatingIssue").closest(".row").after("<div class='form-group' id='divInfoIssue'> (5)<b>Critical</b> - Disaster with potential to lead to business failure.<br>(4) <b>Major</b> - Major event which will be endured with proper management.<br>(3) <b>Moderate</b> - Significant event which can be managed under normal circumstances.<br>(2)<b>Minor</b> - Event with consequences which can be readily absorbed but requires management effort to minimise the impact.<br>(1) <b>Insignificant</b> - Low level risk. Existing controls and procedures will cope with event.</div>")
      }else{
        if ($(this).attr('value') == 'impact') {
          if ($('#divInfoImpact').is(':hidden')) {
            $("#divInfoImpact").show()
            $("#divInfoRisk").hide()
          }else{
            $("#divInfoImpact").hide()
          }
        }else if($(this).attr('value') == 'likelihood'){
          if ($('#divInfoRisk').is(':hidden')) {
            $("#divInfoRisk").show()
            $("#divInfoImpact").hide()
          }else{
            $("#divInfoRisk").hide()
          }
        }
      }
    }); 

    function changeNumberEntries(id_table,num){
      $('#'+id_table).DataTable().page.len(num).draw()
    }
</script>
@endsection