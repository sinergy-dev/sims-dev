@extends('template.main')
@section('tittle')
  Asset
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <style type="text/css">
    @media screen and (max-width: 1805px) {
      .searchBarBox{
        width: 70%!important;
      }
    }

    @media screen and (min-width: 1805px) {
      .searchBarBox{
        width: 50%!important;
      }
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
            <h3>150</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="fa fa-table"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>
            <p>Bounce Rate</p>
          </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>44</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>65</h3>
            <p>Unique Visitors</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2 col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Asset Owner</label>
              <select class="form-control" style="width: 100%!important;"></select>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select class="form-control" style="width: 100%!important;"></select>
            </div>
            <div class="form-group">
              <label>Client</label>
              <select class="form-control" style="width: 100%!important;"></select>
            </div>
            <div class="form-group">
              <label>PID</label>
              <select class="form-control" style="width: 100%!important;"></select>
            </div>
          </div>
          <div class="box-footer">
            <button class="btn btn-sm btn-block bg-purple">Filter</button>
            <button class="btn btn-sm btn-block btn-danger">Reset Filter</button>
          </div>
        </div>
      </div>
      <div class="col-md-10 col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <button class="btn btn-sm bg-purple pull-left" onclick="btnAddAsset(0)"><i class="fa fa-plus"></i> Asset</button>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="input-group">
                  <input id="searchBar" type="text" class="form-control" placeholder="Search Anything...">
                  <div class="input-group-btn">
                    <button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      Show 10 
                      <span class="fa fa-caret-down"></span>
                    </button>
                    <ul class="dropdown-menu" id="selectShowEntryTicket">
                      <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                      <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                      <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                      <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-xs-12">
                <div class="input-group" style="text-align: right;">
                  <span class="input-group-btn">
                    <button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      Displayed Column
                      <span class="fa fa-caret-down"></span>
                    </button>
                    <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">ID Ticket</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="1"><span class="text">ID ATM</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="2"><span class="text">Ticket Number</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="3"><span class="text">Open</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="4"><span class="text">Location - Problem</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">PIC</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="6"><span class="text">Severity</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="7"><span class="text">Status</span></li>
                      <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="8"><span class="text">Operator</span></li>
                    </ul>
                  </span>
                </div>
              </div>
            </div>
      <!--       <div class="input-group pull-right searchBarBox">
              <input id="searchBar" type="text" class="form-control" placeholder="Search Anything...">
              <div class="input-group-btn">
                <button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Show 10 
                  <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu" id="selectShowEntryTicket">
                  <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                  <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                  <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                  <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                </ul>
              </div>
              <span class="input-group-btn">
                <button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Displayed Column
                  <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">ID Ticket</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="1"><span class="text">ID ATM</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="2"><span class="text">Ticket Number</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="3"><span class="text">Open</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="4"><span class="text">Location - Problem</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">PIC</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="6"><span class="text">Severity</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="7"><span class="text">Status</span></li>
                  <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="8"><span class="text">Operator</span></li>
                </ul>
                <button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" type="button" class="btn btn-default btn-flat">
                  <i class="fa fa-fw fa-refresh"></i>
                </button>
              </span>
            </div> -->
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Asset Owner</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th>Vendor</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <tr>
                    <td>Contoh 1</td>
                    <td>Category</td>
                    <td>Status</td>
                    <td>Vendor</td>
                    <td><a href="{{url('asset/detail')}}?type=asset" class="btn btn-sm btn-warning">Detail</a></td>
                  </tr>
                  <tr>
                    <td>Contoh 2</td>
                    <td>Category</td>
                    <td>Status</td>
                    <td>Vendor</td>
                    <td><a href="{{url('asset/detail')}}?type=peripheral" class="btn btn-sm btn-warning">Detail</a></td>
                  </tr>
                </tbody>
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
              <label for="">Choose Asset</label>
              <select id="selectAsset" name="selectAsset" class="form-control" onchange="fillInput('selectAsset')">
                <option value="asset">Main Asset</option>
                <option value="peripheral">Peripheral</option>
              </select>
              <span class="help-block" style="display:none;">Please fill Asset!</span>
            </div> 

            <div class="form-group divPeripheral" style="display:none;">
              <label for="">Category Peripheral</label>
              <select id="selectPeripheral" name="selectPeripheral" class="form-control" onchange="fillInput('selectPeripheral')"></select>
              <span class="help-block" style="display:none;">Please fill Category Peripheral!</span>
            </div>

            <div class="form-group divPeripheral" style="display:none;">
              <label for="">Assign</label>
              <select id="selectAssigntoPeripheral" name="selectAssigntoPeripheral" class="form-control" onchange="fillInput('selectAssigntoPeripheral')"></select>
            </div>

            <div class="form-group">
              <label for="">Asset Owner</label>
              <select id="selectAssetOwner" name="selectAssetOwner" class="form-control" onchange="fillInput('selectAssetOwner')"></select>
              <span class="help-block" style="display:none;">Please fill Asset Owner!</span>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Category</label>
                  <select  type="text" class="form-control" onchange="fillInput('selectClient')" id="selectClient" name="selectClient">
                  </select>
                  <span class="help-block" style="display:none;">Please fill Category!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Status</label>
                  <select autocomplete="off" type="" class="form-control" id="inputStatus" name="inputStatus" onkeyup="fillInput('inputStatus')"></select>
                  <span class="help-block" style="display:none;">Please fill Status!</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Vendor</label>
              <input id="selectVendor" name="selectVendor" class="form-control" onkeyup="fillInput('selectVendor')"></select>
              <span class="help-block" style="display:none;">Please fill Vendor!</span>
            </div>

            <div class="form-group">
              <label for="">Type Device</label>
              <input id="inputTypeDevice" name="inputTypeDevice" class="form-control" onkeyup="fillInput('inputTypeDevice')">
              <span class="help-block" style="display:none;">Please fill Type Device!</span>
            </div>

            <div class="form-group">
              <label for="">Serial Number</label>
              <input id="inputSerialNumber" name="inputSerialNumber" class="form-control" onkeyup="fillInput('inputSerialNumber')">
              <span class="help-block" style="display:none;">Please fill Serial Number!</span>
            </div>

            <div class="form-group">
              <label for="">Spesifikasi</label>
              <input id="inputSpesifikasi" name="inputSpesifikasi" class="form-control" onkeyup="fillInput('inputSpesifikasi')">
              <span class="help-block" style="display:none;">Please fill Spesifikasi!</span>
            </div>

            <div class="form-group divPeripheral" style="display:none">
              <label for="">Peripheral</label>
              <input id="inputPeripheral" name="inputPeripheral" class="form-control" onkeyup="fillInput('inputPeripheral')">
              <span class="help-block" style="display:none;">Please fill Peripheral!</span>
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
              <select id="selectPID" name="selectPID" class="form-control" onchange="fillInput('pid')"></select>
              <span class="help-block" style="display:none;">Please fill PID!</span>
            </div>      

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Client*</label>
                  <select autofocus type="text" class="form-control" onchange="fillInput('selectClient')" id="selectClient" name="selectClient">
                  </select>
                  <span class="help-block" style="display:none;">Please fill Client!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">ID Device Customer*</label>
                  <input autocomplete="off" type="" class="form-control" id="inputEmail" name="inputEmail" onkeyup="fillInput('email')">
                  <span class="help-block" style="display:none;">Please fill Email!</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">City</label>
              <select autofocus type="text" class="form-control" onchange="fillInput('city')" name="selectCity" id="selectCity">
              </select>
              <span class="help-block" style="display:none;">Please fill City!</span>
            </div>

            <div class="form-group">
              <label for="">Address Location</label>
              <textarea onkeyup="fillInput('address')" id="txtAddressLocation" name="txtAddressLocation" class="form-control"></textarea>
              <span class="help-block" style="display:none;">Please fill Location!</span>
            </div>

            <div class="form-group">
              <label for="">Detail Location</label>
              <textarea onkeyup="fillInput('location')" id="txtAreaLocation" name="txtAreaLocation" class="form-control"></textarea>
              <span class="help-block" style="display:none;">Please fill Location!</span>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">IP Address*</label>
                  <input autocomplete="off" class="form-control" id="inputIPAddress" type="" name="inputIPAddress" onkeyup="fillInput('ip_address')">
                  <span class="help-block" style="display:none;">Please fill Phone!</span>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Server*</label>
                  <input autocomplete="off" type="text" class="form-control" name="inputServer" id="inputServer" onkeyup="fillInput('server')">
                  <span class="help-block" style="display:none;">Please fill Server!</span>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Port*</label>
                  <input autocomplete="off" type="text" class="form-control"  name="inputPort" id="inputPort" onkeyup="fillInput('port')">
                  <span class="help-block" style="display:none;">Please fill Port!</span>
                </div>
              </div>
            </div>           

            <div class="form-group">
              <label for="">Status Customer*</label>
              <select id="selectStatusCustomer" name="selectStatusCustomer" class="form-control" onchange="fillInput('customer')"></select>
              <span class="help-block" style="display:none;">Please fill Status Customer!</span>
            </div>

            <div class="form-group">
              <label for="">2nd Level Support*</label>
              <select autofocus class="form-control" name="type" id="selectLevelSupport" name="selectLevelSupport" onchange="fillInput('level_support')">
              </select>
              <span class="help-block" style="display:none;">Please fill 2nd Level Support!</span>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Operating System*</label>
                  <input autofocus type="text" class="form-control" onchange="fillInput('op_system')" id="inputOS" name="inputOS">
                  <span class="help-block" style="display:none;">Please fill Operating System!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Version*</label>
                  <input autocomplete="off" type="" class="form-control" id="inputVersion" name="inputVersion" onkeyup="fillInput('version')">
                  <span class="help-block" style="display:none;">Please fill Version!</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Installed Date*</label>
              <input autocomplete="off" class="form-control" id="inputInstalledDate" name="inputInstalledDate" onchange="fillInput('installed_date')"/>
              <span class="help-block" style="display:none;">Please fill Installed Date!</span>
            </div>

            <div class="form-group">
              <label for="">License*</label>
              <input autocomplete="off" class="form-control" id="inputLicense" name="inputLicense" onkeyup="fillInput('license')"/>
              <span class="help-block" style="display:none;">Please fill License!</span>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">License Start*</label>
                  <div class="input-group">
                    <input autofocus type="text" class="form-control" onchange="fillInput('license_start')" id="inputLicenseStart" name="inputLicenseStart">
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
                    <input autocomplete="off" type="" class="form-control" id="inputLicenseEnd" name="inputLicenseEnd" onkeyup="fillInput('license_end')">
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
                    <input autofocus type="text" class="form-control" onchange="fillInput('maintenance_start')" id="inputMaintenanceStart" name="inputMaintenanceStart">
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
                    <input autocomplete="off" type="" class="form-control" id="inputMaintenanceEnd" name="inputMaintenanceEnd" onkeyup="fillInput('mainetnance_end')">
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
              <button type="button" class="btn btn-secondary" id="prevBtnAdd">Back</button>
              <button type="button" class="btn btn-primary" id="nextBtnAdd">Next</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scriptImport')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>

@endsection
@section('script')
  <script type="text/javascript">
    function btnAddAsset(n){
      console.log(n)
      var x = document.getElementsByClassName("tab-add");
      x[n].style.display = "inline";
      if (n == (x.length - 1)) {
        $("#nextBtnAdd").attr('onclick','nextPrev(1)')
        $("#prevBtnAdd").attr('onclick','nextPrev(-1)')        
        document.getElementById("prevBtnAdd").style.display = "inline";
        document.getElementById("nextBtnAdd").innerHTML = "Save";
        document.getElementById("prevBtnAdd").innerHTML = "Back";

        $("#nextBtnAdd").attr('onclick','saveAsset()'); 
      }else{
        $("#nextBtnAdd").attr('onclick','nextPrev(1)')
        $("#prevBtnAdd").attr('onclick','closeModal()')   
        document.getElementById("prevBtnAdd").style.display = "inline";
        document.getElementById("prevBtnAdd").innerHTML = "Cancel";
        document.getElementById("nextBtnAdd").innerHTML = "Next";
      }

      $("#ModalAddAsset").modal({backdrop: 'static', keyboard: false})  
    }

    $("select").select2()

    currentTab = 0
    function nextPrev(n){
      let x = document.getElementsByClassName("tab-add");
      x[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        x[n].style.display = "none";
        currentTab = 0;
      }

      btnAddAsset(currentTab);
    }

    function saveAsset(){

    }

    function closeModal(){
      $("#ModalAddAsset").modal("hide")  
    }

    function fillInput(argument) {
      if (argument == "selectAsset") {
        let assetVal = $("#selectAsset").val()
        if (assetVal == 'asset') {
          $(".tab-add").find(".form-group.divPeripheral").css('display','none') 
        }else if (assetVal == 'peripheral') {
          $(".tab-add").find(".form-group.divPeripheral").css('display','block') 
        }
      }
    }
  </script>
@endsection