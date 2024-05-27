@extends('template.main')
@section('tittle')
  Asset
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
  <style type="text/css">
    @media screen and (max-width: 1805px) {
      .searchBarBox{
        width: 70%!important;
      }

      .btn-dropdown-menu{
        width: 80px;
      }

      .btn-dropdown-menu::before{
        content: "Column";
      }

      .dropdown-menu-normal{
        padding-left:0px;
        padding-right:0px;
      }
    }

    @media screen and (min-width: 1805px) {
      .searchBarBox{
        width: 50%!important;
      }

      .btn-dropdown-menu{
        width: 200px;
      }

      .btn-dropdown-menu::before{
        content: "Displayed Column";
      }

      .dropdown-menu-normal{
        padding-left:45px;
        padding-right: 5px;
        min-width: 220px;
      }
    }

    .dataTables_filter {display: none;}

    .dataTables_length {
      display: none;
    }

    select + span{
      width: 100%!important;
    }

    textarea{
      resize:vertical;
    }
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Asset
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{url('asset/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Asset</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3 id="countAll" class="counter"></h3>
            <p>Total Assets</p>
          </div>
          <div class="icon">
            <i class="fa fa-table"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3 id="countInstalled" class="counter">0</h3>
            <p>Installed</p>
          </div>
        <div class="icon">
          <i class="fa fa-gear"></i>
        </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3 id="countAvailable" class="counter">0</h3>
            <p>Available</p>
          </div>
          <div class="icon">
            <i class="fa fa-archive"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-red">
          <div class="inner">
            <h3 id="countRma" class="counter">0</h3>
            <p>RMA</p>
          </div>
          <div class="icon">
            <i class="fa fa-list"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2 col-xs-12" id="box-filter" style="display:none">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Asset Owner</label>
              <select class="form-control" id="selectFilterAssetOwner" name="selectFilterAssetOwner" style="width: 100%!important;">
                <option></option>
              </select>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select class="form-control" id="selectFilterCategory" name="selectFilterCategory" style="width: 100%!important;">
                <option></option>
              </select>
            </div>
            <div class="form-group">
              <label>Client</label>
              <select class="form-control" id="selectFilterClient" name="selectFilterClient" style="width: 100%!important;"><option></option></select>
            </div>
            <div class="form-group">
              <label>PID</label>
              <select class="form-control" id="selectFilterPID" name="selectFilterPID" style="width: 100%!important;"><option></option></select>
            </div>
          </div>
          <div class="box-footer">
            <button class="btn btn-sm btn-block bg-purple" onclick="filterAsset()">Filter</button>
            <button class="btn btn-sm btn-block btn-danger" onclick="filterResetAsset()">Reset Filter</button>
          </div>
        </div>
      </div>

      <div class="col-md-10 col-xs-12" id="box-table-asset">
        <div class="box box-primary">
          <div class="box-header">
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <button class="btn btn-sm bg-purple pull-left" onclick="btnAddAsset(0)" style="display:none" id="btnAddAsset"><i class="fa fa-plus"></i> Asset</button>
                  <button class="btn btn-warning btn-sm btnAssignEngineer" onclick="btnAssignEngineer()" style="margin-left:5px;display: none;"><i class="fa fa-cog"></i> Assign Engineer</button>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="input-group">
                  <input id="searchBar" type="text" class="form-control" placeholder="Search Anything..." onkeyup="searchBarEntries('tableAsset','searchBar')">
                  <div class="input-group-btn">
                    <button type="button" id="btnShowEntryAsset" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      Show 10 
                      <span class="fa fa-caret-down"></span>
                    </button>
                    <ul class="dropdown-menu" id="selectShowEntryAsset">
                      <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                      <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                      <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                      <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-xs-12">
                <div class="input-group">
                  <span class="input-group-btn">
                    <button type="button" id="btnShowColumnAsset" class="btn btn-default btn-flat dropdown-toggle btn-dropdown-menu" data-toggle="dropdown" aria-expanded="false">
                      <span class="fa fa-caret-down"></span>
                    </button>
                    <ul class="dropdown-menu" style="padding-left:45px;padding-right: 5px;min-width: 220px;" id="selectShowColumnTicket">
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">ID Asset</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">Type Device</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="6"><span class="text">Serial Number</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="7"><span class="text">Spesifikasi</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="8"><span class="text">RMA</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="9"><span class="text">Current PID</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="10"><span class="text">Notes</span></li>
                    </ul>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table" id="tableAsset" style="width:100%">
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <div class="modal fade" id="ModalAddAsset" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST">
          @csrf  
            <div class="tab-add">
              <div class="form-group">
                <label for="">Choose Asset*</label>
                <select id="selectAsset" name="selectAsset" class="form-control" onchange="fillInput('selectAsset')">
                  <option></option>
                </select>
                <span class="help-block" style="display:none;">Please fill Asset!</span>
              </div> 

              <div class="form-group divPeripheral" style="display:none;">
                <label for="">Category Peripheral*</label>
                <select id="selectPeripheral" name="selectPeripheral" class="form-control divPeripheral" onchange="fillInput('selectPeripheral')" style="display:none;">
                  <option></option>
                </select>
                <span class="help-block" style="display:none;">Please fill Category Peripheral!</span>
              </div>

              <div class="form-group divPeripheral" style="display:none;">
                <label for="">Assign</label>
                <select id="selectAssigntoPeripheral" name="selectAssigntoPeripheral divPeripheral" class="form-control" onchange="fillInput('selectAssigntoPeripheral')" style="display:none;">
                  <option></option>
                </select>
              </div>

              <div class="form-group">
                <label for="">Asset Owner*</label>
                <select id="selectAssetOwner" name="selectAssetOwner" class="form-control" onchange="fillInput('selectAssetOwner')">
                  <option></option>
                </select>
                <span class="help-block" style="display:none;">Please fill Asset Owner!</span>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Category*</label>
                    <select  type="text" class="form-control" onchange="fillInput('selectCategory')" id="selectCategory" name="selectCategory">
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please fill Category!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Status*</label>
                    <select autocomplete="off" type="" class="form-control" id="selectStatus" name="selectStatus" onchange="fillInput('selectStatus')"><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Status!</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Vendor*</label>
                <select id="selectVendor" name="selectVendor" class="form-control" onchange="fillInput('selectVendor')"><option></option></select>
                <span class="help-block" style="display:none;">Please fill Vendor!</span>
              </div>

              <div class="form-group">
                <label for="">Type Device*</label>
                <select id="selectTypeDevice" name="selectTypeDevice" class="form-control" onchange="fillInput('selectTypeDevice')"><option></option></select>
                <span class="help-block" style="display:none;">Please fill Type Device!</span>
              </div>

              <div class="form-group">
                <label for="">Serial Number*</label>
                <input id="inputSerialNumber" name="inputSerialNumber" class="form-control" onkeyup="fillInput('inputSerialNumber')">
                <span class="help-block" style="display:none;">Please fill Serial Number!</span>
              </div>

              <div class="form-group">
                <label for="">Spesifikasi*</label>
                <input id="inputSpesifikasi" name="inputSpesifikasi" class="form-control" onkeyup="fillInput('inputSpesifikasi')">
                <span class="help-block" style="display:none;">Please fill Spesifikasi!</span>
              </div>

              <div class="form-group">
                <label for="">RMA</label>
                <input id="inputRMA" name="inputRMA" class="form-control" onkeyup="fillInput('inputRMA')">
                <span class="help-block" style="display:none;">Please fill RMA!</span>
              </div>

              <div class="form-group">
                <label for="">Notes</label>
                <textarea id="txtAreaNotes" name="txtAreaNotes" class="form-control" onkeyup="fillInput('txtAreaNotes')"></textarea>
                <span class="help-block" style="display:none;">Please fill Notes!</span>
              </div>

            </div>  
            <div class="tab-add" style="display:none">
              <div class="form-group">
                <label for="">PID*</label>
                <select id="selectPID" name="selectPID" class="form-control" onchange="fillInput('selectPID')"><option></option></select>
                <span class="help-block" style="display:none;">Please fill PID!</span>
              </div>      

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Client*</label>
                    <input type="text" class="form-control" onkeyup="fillInput('inputClient')" id="inputClient" name="inputClient" disabled>
                    <span class="help-block" style="display:none;">Please fill Client!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">ID Device Customer*</label>
                    <input autocomplete="off" type="" class="form-control" id="inputIdDeviceCustomer" name="inputIdDeviceCustomer" onkeyup="fillInput('inputIdDeviceCustomer')">
                    <span class="help-block" style="display:none;">Please fill ID Device Customer!</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">City*</label>
                <select autofocus type="text" class="form-control" onchange="fillInput('selectCity')" name="selectCity" id="selectCity">
                  <option></option>
                </select>
                <span class="help-block" style="display:none;">Please fill City!</span>
              </div>

              <div class="form-group">
                <label for="">Address Location*</label>
                <textarea onkeyup="fillInput('txtAddressLocation')" id="txtAddressLocation" name="txtAddressLocation" class="form-control"></textarea>
                <span class="help-block" style="display:none;">Please fill Address Location!</span>
              </div>

              <div class="form-group">
                <label for="">Detail Location*</label>
                <textarea onkeyup="fillInput('txtAreaLocation')" id="txtAreaLocation" name="txtAreaLocation" class="form-control"></textarea>
                <span class="help-block" style="display:none;">Please fill Detail Location!</span>
              </div>
            </div>
            <div class="tab-add" style="display:none">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="">IP Address</label>
                    <input data-inputmask="'alias': 'ip'" data-mask class="form-control" id="inputIPAddress" name="inputIPAddress" onkeyup="fillInput('ip_address')">
                    <span class="help-block" style="display:none;">Please fill Phone!</span>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="">Server</label>
                    <input data-inputmask="'alias': 'ip'" data-mask class="form-control" name="inputServer" id="inputServer" onkeyup="fillInput('inputServer')">
                    <span class="help-block" style="display:none;">Please fill Server!</span>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="">Port</label>
                    <input autocomplete="off" type="text" class="form-control"  name="inputPort" id="inputPort" onkeyup="fillInput('inputPort')">
                    <span class="help-block" style="display:none;">Please fill Port!</span>
                  </div>
                </div>
              </div>     

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Status Customer*</label>
                    <select id="selectStatusCustomer" name="selectStatusCustomer" class="form-control" onchange="fillInput('selectStatusCustomer')"><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Status Customer!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">2nd Level Support*</label>
                    <select autofocus class="form-control" name="type" id="selectLevelSupport" name="selectLevelSupport" onchange="fillInput('selectLevelSupport')">
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please fill 2nd Level Support!</span>
                  </div>
                </div>
              </div>     

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Operating System*</label>
                    <input autofocus type="text" class="form-control" onchange="fillInput('inputOS')" id="inputOS" name="inputOS">
                    <span class="help-block" style="display:none;">Please fill Operating System!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Version*</label>
                    <input autocomplete="off" type="" class="form-control" id="inputVersion" name="inputVersion" onkeyup="fillInput('inputVersion')">
                    <span class="help-block" style="display:none;">Please fill Version!</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Installed Date*</label>
                <div class="input-group">
                  <input autocomplete="off" class="form-control" id="inputInstalledDate" name="inputInstalledDate" onchange="fillInput('inputInstalledDate')"/>
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                <span class="help-block" style="display:none;">Please fill Installed Date!</span>
              </div>

              <div class="form-group">
                <label for="">License*</label>
                <input autocomplete="off" class="form-control" id="inputLicense" name="inputLicense" onkeyup="fillInput('inputLicense')"/>
                <span class="help-block" style="display:none;">Please fill License!</span>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">License Start*</label>
                    <div class="input-group">
                      <input autofocus type="text" class="form-control" onchange="fillInput('inputLicenseStart')" id="inputLicenseStart" name="inputLicenseStart">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill License Start!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">License End*</label>
                    <div class="input-group">
                      <input autocomplete="off" type="" class="form-control" id="inputLicenseEnd" name="inputLicenseEnd" onchange="fillInput('inputLicenseEnd')">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill License End!</span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Maintenance Start*</label>
                    <div class="input-group">
                      <input autofocus type="text" class="form-control" onchange="fillInput('inputMaintenanceStart')" id="inputMaintenanceStart" name="inputMaintenanceStart">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill Maintenance Start!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Maintenance End*</label>
                    <div class="input-group">
                      <input autocomplete="off" type="" class="form-control" id="inputMaintenanceEnd" name="inputMaintenanceEnd" onchange="fillInput('inputMaintenanceEnd')">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill Maintenance End!</span>
                  </div>
                </div>
              </div>
            </div>      
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="prevBtnAdd">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtnAdd">Next</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-assign-engineer-atm">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Assign Engineer ATM</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="row listEngineerAssign">
              <div class="col-sm-5">
                <div class="form-group">
                  <label>Engineer*</label>
                  <select class="form-control assignEngineerAtm" style="width:100%!important" name="assignEngineerAtm"><option></option></select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>ATM Id*</label>
                  <select class="form-control selectAssignAtmId" style="width:100%!important" name="selectAssignAtmId" multiple="multiple"></select>
                </div>
              </div>
              <div class="col-sm-0">
                <div class="form-group">
                  <label>Action</label>
                  <button class="btn btn-flat btn-danger deleteRowAssign" style="width:40px" disabled><i class="fa fa-trash"></i></button>
                </div>
              </div>
            </div>
          </form>
          <div class="form-group">
            <button class="btn btn-md bg-purple" style="margin:0 auto;display: block;" onclick="addRowAssignEngineerAtm()"><i class="fa fa-plus" style="margin-right:5px"></i>Assign</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" onclick="submitAssignEngineerAtm()" id="atmAddFormButton">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
  <script type="text/javascript">
    var accesable = @json($feature_item);
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

    InitiateCountDashboard("{{url('asset/getCountDashboard')}}")

    if (!accesable.includes('box-filter')) {
      $("#box-table-asset").removeClass("col-md-10")
      $("#box-table-asset").addClass("col-md-12")
    }

    var tableAsset = $("#tableAsset").DataTable({
      "aaSorting": [],
      "ajax":{
        "type":"GET",
        "url":"{{url('asset/getDataAsset')}}"
      },
      "columns": [
        { 
          title:"ID Asset",
          render: function (data, type, row, meta){
            let id_asset = row.id_asset

            if (row.id_asset == null) {
              id_asset == '-'
            }
            
            return id_asset
          }
        },
        {
          title:"Asset Owner",
          render: function (data, type, row, meta){
            let asset_owner = row.asset_owner

            if (row.asset_owner == null) {
              asset_owner == '-'
            }
            
            return asset_owner
          }
        },
        {
          title:"Category",
          render: function (data, type, row, meta){
            let category = row.category

            if (row.category == null) {
              category == '-'
            }

            return category
          }
        },
        {
          title:"Status",
          render: function (data, type, row, meta){
            var bgColor = ''
            if (row.status == "Installed") {
              bgColor = "bg-green"
            }else if (row.status == "Available") {
              bgColor = "bg-aqua"
            }else if (row.status == 'RMA') {
              bgColor = "bg-red"
            }

            return '<span class="label '+ bgColor +'" style="font-size:80%!important">'+ row.status +'</span>'

          }
        },
        {
          title:"Vendor",
          render: function (data, type, row, meta){
            let vendor = row.vendor

            if (row.vendor == null) {
              vendor == '-'
            }

            return vendor
          }
        },
        {
          title:"Type Device",
          render: function (data, type, row, meta){
            let type_device = row.type_device

            if (row.type_device == null) {
              type_device == '-'
            }
            
            return type_device
          }
        },
        {
          title:"Serial Number",
          render: function (data, type, row, meta){
            let serial_number = row.serial_number

            if (row.serial_number == null) {
              serial_number == '-'
            }

            return serial_number
          }
        },
        {
          title:"Spesifikasi",
          render: function (data, type, row, meta){
            let spesifikasi = row.spesifikasi

            if (row.spesifikasi == null) {
              spesifikasi == '-'
            }

            return spesifikasi
          }
        },
        {
          title:"RMA",
          render: function (data, type, row, meta){
            let rma = row.rma

            if (row.rma == null) {
              rma == '-'
            }

            return rma
          }
        },
        {
          title:"Current PID",
          render: function (data, type, row, meta){
            let current_pid = row.pid

            if (row.pid == null) {
              current_pid == '-'
            }

            return current_pid
          }
        },
        {
          title:"Notes",
          render: function (data, type, row, meta){
            let notes = row.notes

            if (row.notes == null) {
              notes == '-'
            }

            return notes
          }
        },
        {
          title:"Action",
          render: function (data, type, row, meta){
           return "<a href='{{url('asset/detail')}}?id_asset="+ row.id_asset +"' class='btn btn-sm btn-warning' target='_blank'>Detail</a>"
          }
        },
      ],
      "columnDefs": [
        {
            "targets": [0,5,6,7,8,9,10], // Index of the column you want to hide (0-based index)
            "visible": false,
            "searchable": false // Optional: if you don't want the column to be searchable
        }
      ]
    })

    function changeColumnTable(data){
      var column = $("#tableAsset").DataTable().column($(data).attr("data-column"))
      column.visible(!column.visible())
    }

    function btnAddAsset(n){
      var x = document.getElementsByClassName("tab-add");
      x[n].style.display = "inline";

      if (n == (x.length - 1)) {
        $("#nextBtnAdd").attr('onclick','saveAsset("asset")')
        $("#prevBtnAdd").attr('onclick','nextPrev(-1)')        
        document.getElementById("prevBtnAdd").style.display = "inline";
        document.getElementById("nextBtnAdd").innerHTML = "Save";
        document.getElementById("prevBtnAdd").innerHTML = "Back";
      }else{
        if (n != 0) {
          document.getElementById("prevBtnAdd").innerHTML = "Back";
          $("#prevBtnAdd").attr('onclick','nextPrev(-1)')        
        }else{
          var select2Element = $('#selectCity');
          if (select2Element.find('option').length < 2) {
            $.ajax({
              url: '{{url("asset/getProvince")}}',
              type: 'GET',
              beforeSend: function() {
                // Show loading indicator before AJAX request starts
                $("#select2-selectCity-container").html("loading...")
              },
              success: function(response) {
                $("#selectCity").select2({
                  placeholder:"Select City",
                  data:response
                })
              },
              error: function(xhr, status, error) {
                  // Handle errors
                  console.error(status, error);
              }
            });
          }
          document.getElementById("prevBtnAdd").innerHTML = "Cancel";
          $("#prevBtnAdd").attr('onclick','closeModal()')   
        }

        $("#nextBtnAdd").attr('onclick','nextPrev(1)')
        document.getElementById("prevBtnAdd").style.display = "inline";
        document.getElementById("nextBtnAdd").innerHTML = "Next";
      }
      // $("#ModalAddAsset").modal({backdrop: 'static', keyboard: false})  
      $("#ModalAddAsset").modal("show")
    }

    function btnAssignEngineer(){
      $("#modal-assign-engineer-atm").modal("show")
      settingListAtmId()
      settingListEngineerAssign("add",null,"modal-assign-engineer-atm")
    }

    function settingListAtmId(){
      $(".selectAssignAtmId").select2({
        placeholder: 'Select ATM Id',
        ajax: {
            url: '{{url("/asset/getIdAtm")}}',
            dataType: 'json',
            delay: 250,
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 10) < data.count_filtered
                    }
                };
            },
        },
      }).on("change", function () {
         var selectedValues = [];
          $('.selectAssignAtmId').not(this).each(function() {
            selectedValues = selectedValues.concat($(this).val() || []);
          });

          // Check if any selected value is selected in another Select
          var currentSelect = $(this);
          var alertShown = false; 
          $(this).find('option:selected').each(function() {
            var value = $(this).val();
            var text = $(this).text();

            if (selectedValues.includes(value)) {
              // Unselect the value in the current Select
              currentSelect.find('option[value="' + value + '"]').prop('selected', false);
              currentSelect.trigger('change');
              alert('ATM Id ' + text + ' cannot duplicate Assign!');
              alertShown = true;
            }
          });
      })

      $(".selectAssignAtmId").next().next().remove()
    }

    function addRowAssignEngineerAtm(){
      var cloneRow = $(".listEngineerAssign:last").clone()
      cloneRow.find(".deleteRowAssign").removeAttr("disabled").end()
      cloneRow.children("select")
              .select2("destroy")
              .val("")
              .end()

      $(".listEngineerAssign").last().after(cloneRow)

      $(".deleteRowAssign").click(function(){
        $(this).closest(".listEngineerAssign").remove()
      })

      settingListEngineerAssign("add",null,"modal-assign-engineer-atm")
      settingListAtmId()
    }

    function settingListEngineerAssign(status,name,id_modal){
      $.ajax({
        type:"GET",
        url:"{{url('/asset/getEngineer')}}",
        success:function(result){
          var selectEngineer = $(".assignEngineerAtm")

          selectEngineer.select2({
            placeholder: 'Select Engineer Name',
            dropdownParent: $("#"+id_modal),
            data:result
          })

          if (status == "edit") {
            if (name != null) {
              selectEngineer.val(name).trigger('change');
            }else{
              selectEngineer
            }
          }else{
            selectEngineer
          }

          $(".assignEngineerAtm").next().next().remove()
        }
      })    
    }

    function submitAssignEngineerAtm(){
      var inputs = document.querySelectorAll('.listEngineerAssign .form-control');
      var arrListEnginner = [],engineer = [], atm_id = [];
      // Iterate over each input element

      var isEmptyField = true, InputLengthEmpty = 0,inputLength = inputs.length
      
      inputs.forEach(function(input) {
          if ($(input).val() == "") {
            isEmptyField = true
            InputLengthEmpty-=1
          }else{
            InputLengthEmpty+=1
            if (InputLengthEmpty < inputLength) {
              isEmptyField = true
            }else{
              isEmptyField = false
            }
          }
          // Push the value of each input to the arrListEnginner array
          if(input.name == 'assignEngineerAtm'){
              engineer.push(input.value);
          }

          if(input.name == 'selectAssignAtmId'){
              atm_id.push($(input).val());
          }
          
      });

      for (var i = 0; i < engineer.length; i++) {
          // Construct object with elements from both arrays
          var combinedObject = {
              engineer: engineer[i],
              id_asset: atm_id[i]
          };
          // Push the combined object into the resulting array
          arrListEnginner.push(combinedObject);
      }

      if (isEmptyField == false) {
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")        
        formData.append("arrListEngineerAssign",JSON.stringify(arrListEnginner)) 

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Submit Assign Engineer ATM",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Assign Engineer Successfully!',
            text: 'Click Ok to reload page',
        }

        Swal.fire(swalFireCustom).then((result) => {
          if (result.value) {
            $.ajax({
              type:"POST",
              url:"{{url('/asset/assignEngineer')}}",
              processData: false,
              contentType: false,
              data:formData,
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
      }else{
        alert("Fill Empty Input Field!")
      }
      
    }

    $("select").select2()

    //select2 filter
    $("#selectFilterAssetOwner").select2({
      placeholder:"Select Asset Owner",
      data:[
        {id:"SIP",text:"SIP"},
        {id:"Distributor",text:"Distributor"},
        {id:"Principal",text:"Principal"}
      ]
    })

    $("#selectFilterCategory").select2({
      placeholder:"Select Category",
      data:[
        {id:"ATM",text:"ATM"},
        {id:"Network",text:"Network"},
        {id:"CRM",text:"CRM"},
        {id:"Security",text:"Security"},
        {id:"Peripheral",text:"Peripheral"},
      ]
    })

    $("#selectFilterClient").select2({
      ajax: {
        url: '{{url("asset/getClient")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select Client',
    }).on("select2:select",function(e){
      $("#selectFilterPID").empty("")

      let client = e.params.data.id

      $("#selectFilterPID").select2({
        ajax: {
          url: '{{url("asset/getPidByClient")}}',
          data: function (params) {
            return {
              client:client,
            };
          },
          processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
              results: data
            };
          },
        },
        placeholder: 'Select PID',
      })
    })

    InitiateFilterPID()
    function InitiateFilterPID(){
      $("#selectFilterPID").select2({
        ajax: {
          url: '{{url("asset/getPidForFilter")}}',
          processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
              results: data
            };
          },
        },
        placeholder: 'Select PID',
      })
    }
    
    //select2 modal add
    $("#selectAsset").select2({
      placeholder:"Select Asset",
      data:[
        {id:"asset",text:"Main Asset"},
        {id:"peripheral",text:"Peripheral"},
      ]
    }).val("asset").trigger("change")

    $("#selectAssetOwner").select2({
      placeholder:"Select Asset Owner",
      data:[
        {id:"SIP",text:"SIP"},
        {id:"Distributor",text:"Distributor"},
        {id:"Principal",text:"Principal"},
      ]
    })

    $("#selectCategory").select2({
      placeholder:"Select Category",
      data:[
        {id:"ATM",text:"ATM"},
        {id:"Network",text:"Network"},
        {id:"CRM",text:"CRM"},
        {id:"Security",text:"Security"},
      ]
    })

    $("#selectStatus").select2({
      placeholder:"Select Status",
      data:[
        {id:"Installed",text:"Installed"},
        {id:"Available",text:"Available"},
        {id:"RMA",text:"RMA"},
      ]
    })

    $("#selectVendor").select2({
      ajax: {
        url: '{{url("asset/getVendor")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Vendor",
      tags:true,
    })

    $("#selectPeripheral").select2({
      ajax: {
        url: '{{url("asset/getCategoryPeripheral")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Category Peripheral",
      tags:true
    })

    $("#selectTypeDevice").select2({
      ajax: {
        url: '{{url("asset/getTypeDevice")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Type Device",
      tags:true
    })

    $("#selectLevelSupport").select2({
      ajax: {
        url: '{{url("asset/getLevelSupport")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select 2nd Level Support",
      tags:true
    })

    $("#selectStatusCustomer").select2({
      placeholder:"Select Status Customer",
      tags:true,
      data:[
        {id:"Beli",text:"Beli"},
        {id:"Sewa",text:"Sewa"},
      ]
    }) 

    $("#selectAssigntoPeripheral").select2({
      ajax: {
        url: '{{url("asset/getAssetToAssign")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select ID Assign to Assign",
    })

    $("#selectPID").select2({
      ajax: {
        url: '{{url("asset/getPid")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select PID',
    }).on('select2:select', function (e) {
      let pid = e.params.data.id
      $.ajax({
        type:"GET",
        url:"{{url('asset/getClientByPid')}}",
        data:{
          pid:pid
        },
        success:function(result){
          $("#inputClient").val(result).prop("disabled",true)
        }
      })
    });     

    $('#inputInstalledDate').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    })

    $('#inputLicenseStart').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    })

    $('#inputLicenseEnd').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    })

    $('#inputMaintenanceStart').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    })

    $('#inputMaintenanceEnd').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    })

    // $("#inputServer").inputmask("ip")
    // $("#inputIPAddress").inputmask("ip")
    currentTab = 0
    function nextPrev(n){
      if (currentTab == 0) {
        $.each($(".tab-add:first").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if ($(data).val() == "") {
                  $("select[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                  $("select[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
              }
          }
        })

        $.each($(".tab-add:first").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputRMA") {
                if ($(data).val() == "") {
                  $("input[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                  $("input[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                }
              }
              
          }
        })

        if ($(".tab-add:first").find(".form-group").hasClass("has-error") == false) {
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }

          btnAddAsset(currentTab);
        }
      }else if (currentTab == 1) {
        $.each($(".tab-add:nth(1)").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").show()
                  $("#"+data.id).closest(".form-group").addClass("has-error")
              }
          }
        })

        $.each($(".tab-add:nth(1)").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            if (data.id != "inputClient") {
              if ($(data).val() == "") {
                $("#"+data.id).closest(".form-group").find(".help-block").show()
                $("#"+data.id).closest(".form-group").addClass("has-error")
              }
            }
          }
        })

        $.each($(".tab-add:nth(1)").find("textarea"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            if ($(data).val() == "") {
              $("#"+data.id).closest(".form-group").find(".help-block").show()
              $("#"+data.id).closest(".form-group").addClass("has-error")
            }
          }
        })

        if ($(".tab-add:nth(1)").find(".form-group").hasClass("has-error") == false) {
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }

          btnAddAsset(currentTab);
        }
      }else if (currentTab == 2){
        if ($(".tab-add:nth(2)").find(".form-group").hasClass("has-error") == false) {
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }

          btnAddAsset(currentTab);
        }
      }
    }

    function saveAsset(type){
      let checkInput = ""

      if (type == "peripheral") {
        $.each($(".tab-add:first").find("select"),function(item,data){
          if ($(data).val() == "") {
              $("select[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
              $("select[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
          }
          // var $el = $(this);
          // if ($el.css("display") !== "none") {
              
          // }
        })

        $.each($(".tab-add:first").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputRMA") {
                if ($(data).val() == "") {
                  $("input[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                  $("input[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                }
              }
              
          }
        })

        checkInput = $(".tab-add:first").find(".form-group").hasClass("has-error")
      }else{
        $.each($(".tab-add:nth(2)").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").show()
                  $("#"+data.id).closest(".form-group").addClass("has-error")
              }
          }
        })

        $.each($(".tab-add:nth(2)").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer") {
                if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").show()
                  $("#"+data.id).closest(".form-group").addClass("has-error")
                }
              }
          }
        })

        checkInput = $(".tab-add:nth(2)").find(".form-group").hasClass("has-error")
      }
      
      if (checkInput == false) {
        Swal.fire({
          title: 'Are you sure?',
          text: "Save New Asset",
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
                didOpen: () => {
                    Swal.showLoading()
                }
            })
            $.ajax({
              type:"POST",
              url:"{{url('asset/storeAsset')}}",
              data:{
                _token:"{{csrf_token()}}",
                idDeviceCustomer:$("#inputIdDeviceCustomer").val(),
                client:$("#inputClient").val(),
                pid:$("#selectPID").val(),
                kota:$("#selectCity").val(),
                alamatLokasi:$("#txtAddressLocation").val(),
                detailLokasi:$("#txtAreaLocation").val(),
                ipAddress:$("#inputIPAddress").val(),
                ipServer:$("#inputServer").val(),
                port:$("#inputPort").val(),
                statusCust:$("#selectStatusCustomer").val(),
                secondLevelSupport:$("#selectLevelSupport").val(),
                operatingSystem:$("#inputOS").val(),
                versionOs:$("#inputVersion").val(),
                installedDate:moment(($("#inputInstalledDate").val()), "DD/MM/YYYY").format("YYYY-MM-DD"),
                license:$("#inputLicense").val(),
                licenseStartDate:moment(($("#inputLicenseStart").val()), "DD/MM/YYYY").format("YYYY-MM-DD"),
                licenseEndDate:moment(($("#inputLicenseEnd").val()), "DD/MM/YYYY").format("YYYY-MM-DD"),
                maintenanceStart:moment(($("#inputMaintenanceStart").val()), "DD/MM/YYYY").format("YYYY-MM-DD"),
                maintenanceEnd:moment(($("#inputMaintenanceEnd").val()), "DD/MM/YYYY").format("YYYY-MM-DD"),
                status:$("#selectStatus").val(),
                vendor:$("#selectVendor").val(),
                typeDevice:$("#selectTypeDevice").val(),
                serialNumber:$("#inputSerialNumber").val(),
                spesifikasi:$("#inputSpesifikasi").val(),
                rma:$("#inputRMA").val(),
                notes:$("#txtAreaNotes").val(),
                categoryPeripheral:$("#selectPeripheral").val(),
                typeAsset:$("#selectAsset").val(),
                assetOwner:$("#selectAssetOwner").val(),
                category:$("#selectCategory").val(),
                assignTo:$("#selectAssigntoPeripheral").val()
              },
              success: function(result){
                Swal.close()
                Swal.fire({
                  title: 'Add Asset Successsfully!',
                  type: 'success',
                  icon: 'success',
                  confirmButtonText: 'Reload',
                }).then((result) => {
                  $("#ModalAddAsset").modal("hide")
                  $('#tableAsset').DataTable().ajax.url("{{url('asset/getDataAsset')}}").load();
                  InitiateCountDashboard("{{url('asset/getCountDashboard')}}")
                })
              }
            })
          }
        })
      }
    }

    function filterAsset(){
      var tempAssetOwner = 'assetOwner=', tempCategory = 'category=', tempClient = 'client=', tempPID = 'pid='   

      if ($("#selectFilterAssetOwner").val() != "" && $("#selectFilterAssetOwner").val() != null) {
        tempAssetOwner = "assetOwner="+$("#selectFilterAssetOwner").val()   
      }

      if ($("#selectFilterCategory").val()  != "" && $("#selectFilterCategory").val() != null) {
        tempCategory = "category=" + $("#selectFilterCategory").val() 

        //show button
        if ($("#selectFilterCategory").val() == "ATM") {
          if(accesable.includes('btnAssignEngineer')){
            $(".btnAssignEngineer").show()
          }else{
            $(".btnAssignEngineer").hide()
          }
        }else{
          $(".btnAssignEngineer").hide()
        }
      }

      if ($("#selectFilterClient").val() != "" && $("#selectFilterClient").val() != null) {
        tempClient = "client=" + $("#selectFilterClient").val() 
      }

      if ($("#selectFilterPID").val() != "" && $("#selectFilterPID").val() != null) {
        tempPID = "pid=" + $("#selectFilterPID").val() 
      }     

      var temp = '?' + tempAssetOwner + '&' + tempCategory + '&' + tempClient + '&' + tempPID

      var url = "{{url('asset/getFilterCount')}}"+temp

      $('#tableAsset').DataTable().ajax.url("{{url('asset/getFilterData')}}"+temp).load();

      InitiateCountDashboard(url)
    }

    function filterResetAsset(){
      $("#selectFilterAssetOwner").val("").trigger("change")
      $("#selectFilterCategory").val("").trigger("change")
      $("#selectFilterClient").val("").trigger("change")
      $("#selectFilterPID").val("").trigger("change")
      $("#selectFilterPID").empty("")
      InitiateFilterPID()
      $('#tableAsset').DataTable().ajax.url("{{url('asset/getDataAsset')}}").load();
      $(".btnAssignEngineer").hide()
    }

    function changeNumberEntries(number){
      $("#btnShowEntryAsset").html('Show ' + number + ' <span class="fa fa-caret-down"></span>')
      $("#tableAsset").DataTable().page.len( number ).draw();
    }

    function searchBarEntries(id_table,id_search_bar){
      $('#'+id_table).DataTable().ajax.url("{{url('asset/getSearchData')}}?search="+$('#' + id_search_bar).val()).load();
      // $("#" + id_table).DataTable().search($('#' + id_search_bar).val()).draw();
    }

    function closeModal(){
      $("#ModalAddAsset").modal("hide")  
    }

    function fillInput(argument) {
      if (argument == "selectAsset") {
        let assetVal = $("#selectAsset").val()
        if (assetVal == 'asset') {
          $(".tab-add").find(".form-group.divPeripheral").css('display','none') 

          $("#nextBtnAdd").attr('onclick','nextPrev(1)')
          document.getElementById("prevBtnAdd").style.display = "inline";
          document.getElementById("prevBtnAdd").innerHTML = "Cancel";
          document.getElementById("nextBtnAdd").innerHTML = "Next";
        }else if (assetVal == 'peripheral') {
          $(".tab-add").find(".form-group.divPeripheral").css('display','block') 

          document.getElementById("prevBtnAdd").style.display = "inline";
          $("#prevBtnAdd").attr('onclick','closeModal()')   
          document.getElementById("prevBtnAdd").innerHTML = "Cancel";
          $("#nextBtnAdd").attr('onclick','saveAsset("peripheral")')
          document.getElementById("nextBtnAdd").innerHTML = "Save";
        }
      }

      if ($("#"+argument).val() != "") {
        $("#"+argument).closest(".form-group").removeClass("has-error")
        $("#"+argument).closest(".form-group").find(".help-block").hide()
      }
    }

    $('#ModalAddAsset').on('hidden.bs.modal', function () {
      currentTab = 0
      $(".tab-add").css("display","none")
      $(".tab-add").find("select").val("").trigger("change")
      $(".tab-add").find("input").val("").trigger("change")
      $(".tab-add").find("textarea").val("").trigger("change")
      $(".tab-add").find(".form-group").removeClass("has-error")
      $(".tab-add").find(".form-group").find(".help-block").hide()
    });

    function InitiateCountDashboard(url){
      $.ajax({
        url:url,
        type:"GET",
        success:function(response){
          $("#countAll").text(response.countAll)
          $("#countInstalled").text(response.countInstalled)
          $("#countAvailable").text(response.countAvailable)
          $("#countRma").text(response.countRma)

          $('.counter').each(function () {
            var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
            $(this).prop('Counter', 0).animate({
              Counter: $(this).text()
            }, {
              duration: 1000,
              step: function (func) {
                 $(this).text(parseFloat(func).toFixed(size));
              }
            });
          });
        }
      })
    }
  </script>
@endsection