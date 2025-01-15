@extends('template.main')
@section('tittle')
  Asset
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
  <style type="text/css">
    /*@media screen and (max-width: 1805px) {
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
    }*/

    /*@media screen and (min-width: 1805px) {
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
    }*/

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

    .pac-container {
      z-index: 1100 !important;
    }

    .image-upload>input {
      display: none;
    }

    input[type=file]::-webkit-file-upload-button{
     display: none;
    }

    input::file-selector-button {
     display: none;
    }

    .select2-selection__choice {
      white-space: normal !important; /* Allow text to wrap */
      word-wrap: break-word; /* Break long words */
    }

    .select2-container--open .select2-dropdown {
      top: 100% !important; /* Forces the dropdown to open below */
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
        <div class="small-box bg-purple">
          <div class="inner">
            <h3 id="countAll" class="counter"></h3>
          </div>
          <div class="icon">
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3 id="countInstalled" class="counter"></h3>
          </div>
        <div class="icon">
        </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3 id="countAvailable" class="counter"></h3>
          </div>
          <div class="icon">
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
      </div>

      <div class="col-lg-3 col-xs-12">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3 id="countRent" class="counter"></h3>
          </div>
          <div class="icon">
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
              <div class="col-md-12 col-xs-12">
                <div class="pull-left">
                  <button class="btn btn-sm bg-purple" onclick="btnAddAsset(0)" style="display:none" id="btnAddAsset"><i class="fa fa-plus"></i> Asset</button>
                  <button class="btn btn-sm btn-primary" onclick="btnAddServicePoint()" id="btnAddServicePoint" style="display:none"><i class="fa fa-plus"></i> Service Point</button>
                  <button class="btn btn-sm bg-maroon" onclick="btnAddCategory()" id="btnAddCategory" style="display:none"><i class="fa fa-plus"></i> Category</button>
                  <button class="btn btn-sm btn-warning btnAssignEngineer" id="btnAssignEngineer" onclick="btnAssignEngineer()" style="display: none;"><i class="fa fa-cog"></i> Assign Engineer</button>
                </div>
                <div class="pull-right" style="display: flex;">
                  <div class="input-group" style="margin-right:10px">
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
                  <div class="input-group">
                    <span class="input-group-btn">
                      <button type="button" id="btnShowColumnAsset" class="btn btn-default btn-flat dropdown-toggle btn-dropdown-menu" data-toggle="dropdown" aria-expanded="false">
                        Displayed Column
                        <span class="fa fa-caret-down"></span>
                      </button>
                      <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">ID Asset</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">Type Device</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="6"><span class="text">ID Device Customer</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="7"><span class="text">Serial Number</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="8"><span class="text">Spesifikasi</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="9"><span class="text">RMA</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="10"><span class="text">Client</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="11"><span class="text">Current PID</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="12"><span class="text">PIC - Department</span></li>
                        <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="13"><span class="text">Notes</span></li>
                      </ul>
                    </span>
                  </div>
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
  
  <div class="modal fade" id="modal-add-asset" role="dialog">
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
              <div class="form-group divPeripheral" style="display:none;">
                <label for="">Category Peripheral*</label>
                <select id="selectPeripheral" name="selectPeripheral" class="form-control divPeripheral" onchange="fillInput('selectPeripheral')" style="display:none;">
                  <option></option>
                </select>
                <span class="help-block" style="display:none;">Please fill Category Peripheral!</span>
              </div>

              <div class="form-group">
                <label for="">Assign</label>
                <select id="selectAssigntoPeripheral" name="selectAssigntoPeripheral" class="form-control" onchange="fillInput('selectAssigntoPeripheral')" style="display:none;">
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

              <div class="form-group">
                <label for="">PID*</label>
                <select id="selectPID" name="selectPID" class="form-control" onchange="fillInput('selectPID')"><option></option></select>
                <span class="help-block" style="display:none;">Please fill PID!</span>
              </div> 

              <div class="form-group">
                <label for="">Client*</label>
                <input type="text" class="form-control" onkeyup="fillInput('inputClient')" id="inputClient" name="inputClient" disabled>
                <span class="help-block" style="display:none;">Please fill Client!</span>
              </div>
                
              <div class="form-group" id="clientContainer"></div>

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
                <!-- ini buat milih status -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Status*</label>
                    <select autocomplete="off" type="" class="form-control" id="selectStatus" name="selectStatus" onchange="fillInput('selectStatus')"><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Status!</span>
                  </div>
                </div>
              </div>

              <div class="form-group" style="display:none;">
                <label for="">Reason*</label>
                <textarea id="txtAreaReason" name="txtAreaReason" class="form-control" onkeyup="fillInput('txtAreaReason')"></textarea>
                <span class="help-block" style="display:none;">Please fill Reason!</span>
              </div>

              <div class="row" id="vendorContainer">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Vendor*</label>
                    <select id="selectVendor" name="selectVendor" class="form-control" onchange="fillInput('selectVendor')" onkeyup=""><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Vendor!</span>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Type Device*</label>
                    <select id="selectTypeDevice" name="selectTypeDevice" class="form-control" onchange="fillInput('selectTypeDevice')"><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Type Device!</span>
                  </div>
                </div>
              </div>

              <div class="form-group" id='serialNumberGroup'>
                <label for="">Serial Number*</label>
                <input id="inputSerialNumber" name="inputSerialNumber" class="form-control" onkeyup="fillInput('inputSerialNumber')">
                <span class="help-block" style="display:none;">Please fill Serial Number!</span>
              </div>

              <div class="row">
                <div id="spesifikasiContainer">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Spesifikasi</label>
                      <input id="inputSpesifikasi" name="inputSpesifikasi" class="form-control" onkeyup="fillInput('inputSpesifikasi')">
                      <span class="help-block" style="display:none;">Please fill Spesifikasi!</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-add" style="display:none">
              <div class="row" id="rmaContainer">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">RMA</label>
                    <input id="inputRMA" name="inputRMA" class="form-control" onkeyup="fillInput('inputRMA')">
                    <span class="help-block" style="display:none;">Please fill RMA!</span>
                  </div>
                </div>
              </div>

              <!-- <div class="form-group" id="tanggalPembelianContainer">
                <label for="">Tanggal Pembelian</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input id="inputTglBeli" name="inputTglBeli" class="form-control" onchange="fillInput('inputTglBeli')">
                </div>
              </div> -->

              <div class="row" id="hargaContainer">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Harga</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <strong>RP</strong>
                      </div>
                      <input id="inputHarga" type="text" name="inputHarga" class="form-control money" onkeyup="fillInput('inputHarga')">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Nilai Buku</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <strong>RP</strong>
                      </div>
                      <input id="inputNilaiBuku" type="text" name="inputNilaiBuku" class="form-control money" onkeyup="fillInput('inputNilaiBuku')">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group" id="notesContainer">
                <label for="">Notes</label>
                <textarea id="txtAreaNotes" name="txtAreaNotes" class="form-control" onkeyup="fillInput('txtAreaNotes')"></textarea>
                <span class="help-block" style="display:none;">Please fill Notes!</span>
              </div>

              <div class="form-group">
                <label>Bukti Asset</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <span for="inputBuktiAsset" style="margin-bottom:0px">
                    <i class="fa fa-cloud-upload" style="display:inline"></i>
                    <input autocomplete="off" style="display: inline;" type="file" class="files" name="inputBuktiAsset" id="inputBuktiAsset" onchange="fillInput('inputBuktiAsset')">
                  </span>                  
                </div>
              </div>
            </div>   
            <div class="tab-add" style="display:none"> 
              <div class="row" id="deviceCustomerContainer">
                <div class="col-sm-12">
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

              <div class="form-group" id="locationContainer"></div>

              <div class="form-group" id="addressLocationContainer">
                <label for="">Location*</label>
                <textarea onkeyup="fillInput('txtAddressLocation')" id="txtAddressLocation" name="txtAddressLocation" class="form-control"></textarea>
                <span class="help-block" style="display:none;">Please fill Location!</span>
              </div>

              <div class="form-group" id="detailLocationContainer">
                <label>Detail Address</label>
                <input class="form-control" placeholder="Search Location..." type="text" onkeyup="fillInput('txtAreaLocation')" id="txtAreaLocation" name="txtAreaLocation">
                <span class="help-block" style="display:none;">Please fill Detail Address!</span>
              </div>

              <div class="form-group">
                <div id="map" style="height: 350px;width: 100%;margin:auto;display: block;background-color: #000;"></div>
              </div>
                
              <div class="row">
                <dir class="col-md-6">
                  <div class="form-group">
                    <label>Latitude</label>
                    <input class="form-control" placeholder="" type="text" id="lat" name="lat" onkeyup="fillInput('lat')" >
                  </div>
                </dir>
                <dir class="col-md-6">
                  <div class="form-group">
                    <label>Longitude</label>
                    <input class="form-control" placeholder="" type="text" id="lng" name="lng" onkeyup="fillInput('lng')" >
                  </div>
                </dir>
              </div>

              <div class="form-group" id="servicePointContainer">
                <label>Service Point</label>
                <select class="form-control" placeholder="" type="text" id="service_point" name="service_point" onchange="fillInput('service_point')"><option></option></select>
              </div>
            </div>
            <div class="tab-add" style="display:none">
              <div class="row" id="hardwareDetailContainer">
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

              <div class="row" id="customerSupportContainer">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="">Status Customer*</label>
                    <select id="selectStatusCustomer" name="selectStatusCustomer" class="form-control" onchange="fillInput('selectStatusCustomer')"><option></option></select>
                    <span class="help-block" style="display:none;">Please fill Status Customer!</span>
                  </div>
                </div>
              </div>     

              <div class="row" id="softwareDetailContainer">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Operating System</label>
                    <input autofocus type="text" class="form-control" onchange="fillInput('inputOS')" id="inputOS" name="inputOS">
                    <span class="help-block" style="display:none;">Please fill Operating System!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Version</label>
                    <input autocomplete="off" type="" class="form-control" id="inputVersion" name="inputVersion" onkeyup="fillInput('inputVersion')">
                    <span class="help-block" style="display:none;">Please fill Version!</span>
                  </div>
                </div>
              </div>

              <div class="form-group" id="prContainer"></div>

              <div class="form-group" id="tanggalPembelianContainer">
                  <label for="">Tanggal Pembelian</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input id="inputTglBeli" name="inputTglbeli" class="form-control" onchange="fillInput('inputTglBeli')">
                  </div>
              </div>

              <div class="row">
                <div class="col-sm-6 col-xs-12">
                  <div class="form-group">
                    <label for="">2nd Level Support*</label>
                    <select autofocus class="form-control" name="type" id="selectLevelSupport" name="selectLevelSupport" onchange="fillInput('selectLevelSupport')">
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please fill 2nd Level Support!</span>
                  </div>
                </div>

                <div class="col-sm-6 col-xs-12">
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
                </div>
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

              <div class="row" id="maintenanceContainer">
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

  <div class="modal fade" id="modal-assignBy">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Assign Engineer</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="form-group">
              <label>Assign Engineer By</label>
              <select id="assignBy" class="form-control" style="width:100%important!" placeholder="Select Option"><option></option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" onclick="assignBy()">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-assign-engineer-atm">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Assign Engineer</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="row listEngineerAssign">
              <div class="col-sm-4">
                <div class="form-group divAssetToggle" style="display:none;">
                  <label>Asset*</label>
                  <select class="form-control selectAssignAtmId" style="width:100%!important;display: none;" name="selectAssignAtmId" multiple="multiple"></select>
                </div>

                <div class="form-group divPidToggle" style="display:none">
                  <label>Project Id*</label>
                  <select class="form-control selectPidAssignEng" style="width:100%!important;display: none;" name="selectPidAssignEng"><option></option></select>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Engineer*</label>
                  <select class="form-control assignEngineerAtm" style="width:100%!important" name="assignEngineerAtm"><option></option></select>
                </div>
              </div>
              
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Roles*</label>
                  <select class="form-control selectRolesEngineer" style="width:100%!important" name="selectRolesEngineer"><option></option></select>
                </div>
              </div>
              <div class="col-sm-1">
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
          <button type="button" class="btn btn-flat btn-primary" id="submitAssignEngineer">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-add-service-point">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Service Point</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="form-group">
              <label>Service Point</label>
              <input class="form-control" name="inputServicePoint" id="inputServicePoint" placeholder="Service Point">
            </div>
            <div class="form-group">
              <label>Detail Location</label>
              <input class="form-control" name="inputDetailLocationSP" id="inputDetailLocationSP" placeholder="Search Location...">
            </div>
            <div class="form-group">
              <div id="map-sp" style="height: 350px;width: 100%;margin:auto;display: block;background-color: #000;"></div>
            </div>
            <div class="row">
              <dir class="col-md-6">
                <div class="form-group">
                  <label>Latitude</label>
                  <input class="form-control" placeholder="" type="text" id="lat-sp" name="lat-sp">
                </div>
              </dir>
              <dir class="col-md-6">
                <div class="form-group">
                  <label>Longitude</label>
                  <input class="form-control" placeholder="" type="text" id="lng-sp" name="lng-sp">
                </div>
              </dir>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" onclick="saveServicePoint()">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-add-category">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="form-group">
              <label>Category Code</label>
              <input class="form-control" name="inputCatCode" id="inputCatCode" placeholder="Input Code (MAX 3 variable)" maxlength="3" style="text-transform:uppercase">
              <span class="help-block" style="display:none;color: red;"></span>
            </div>
            <div class="form-group">
              <label>Category Name</label>
              <input class="form-control" name="inputCatName" id="inputCatName" placeholder="Input Category Name">
              <span class="help-block" style="display:none;color: red;"></span>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" onclick="saveCategory()">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-show-Qr">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">QR Code</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div id="print-area">
              <div id="divShowQr" style="text-align: center;">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-default pull-left" id="printQr"><i class="fa fa-print"></i> Print QR</button>
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
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
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY_GLOBAL')}}&libraries=places" async defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    var accesable = @json($feature_item);
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

    $('.money').mask('000.000.000.000', {reverse: true});
    
    InitiateCountDashboard("{{url('asset/getCountDashboard')}}")

    if (!accesable.includes('box-filter')) {
      $("#box-table-asset").removeClass("col-md-10")
      $("#box-table-asset").addClass("col-md-12")
    }

    $('input[class="files"]').change(function(){
      var f=this.files[0]
      var filePath = f;
   
      // Allowing file type
      var allowedExtensions =
      /(\.jpg|\.jpeg|\.png)$/i;

      var ErrorText = []
      // 
      if (f.size > 50000000|| f.fileSize > 50000000) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid file size, just allow file with size less than 50MB!',
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
            }else if (row.status == 'Rent') {
              bgColor = "bg-yellow"
            }else if (row.status == 'Unavailable') {
              bgColor = "bg-grey"
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
          title:"ID Device Customer",
          render: function (data, type, row, meta){
            let id_device_customer = row.id_device_customer

            if (row.id_device_customer == null) {
              id_device_customer == '-'
            }
            
            return id_device_customer
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
          title:"Client",
          render: function (data, type, row, meta){
            let client = row.client

            if (row.client == null) {
              client == '-'
            }

            return client
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
          title:"PIC - Department",
          render: function (data, type, row, meta){
            let pic_name = row.pic_name

            if (row.pic_name == null) {
              pic_name == '-'
            }

            return pic_name
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
           return "<a href='{{url('asset/detail')}}?id_asset="+ row.id +"' class='btn btn-sm btn-primary' target='_blank'>Detail</a><button class='btn btn-sm bg-purple' onclick='showQRDetail("+ row.id +","+ '"' + row.id_asset + '"' + ")' style='margin-left:5px'>Show QR</button>"
          }
        },
      ],
      "columnDefs": [
        {
            "targets": [0,5,6,7,8,9,10,11,12,13], // Index of the column you want to hide (0-based index)
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
          if ($("#lat").val() != "" && $("#lng").val() != "") {
            initMap(parseFloat($("#lat").val()),parseFloat($("#lng").val()))
          }
          initMap()
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
                  data:response,
                  dropdownParent:$("#modal-add-asset")
                })
              },
              error: function(xhr, status, error) {
                  // Handle errors
                  console.error(status, error);
              }
            });
          }

          $.ajax({
            url: '{{url("asset/getServicePoint")}}',
            type: 'GET',
            success: function(response) {
              $("#service_point").select2({
                placeholder:"Select Service Point",
                data:response
              })
            },
          });
          
          document.getElementById("prevBtnAdd").innerHTML = "Cancel";
          $("#prevBtnAdd").attr('onclick','closeModal()')   
        }

        if ($("select[id=txtAddressLocation]").is(":visible")) {
          $("#inputIdDeviceCustomer").remove()
          $("#service_point").remove()
          $("textarea[id=txtAddressLocation]").remove()
          $("select[id=txtAddressLocation]").select2({
            placeholder:"Select Location"
          })
        }

        $("#nextBtnAdd").attr('onclick','nextPrev(1)')
        document.getElementById("prevBtnAdd").style.display = "inline";
        document.getElementById("nextBtnAdd").innerHTML = "Next";
      }
      // $("#modal-add-asset").modal({backdrop: 'static', keyboard: false})  
      $("#modal-add-asset").modal("show")
    }

    function btnAssignEngineer(){
      $("#modal-assignBy").modal("show")
      $("#assignBy").select2({
        placeholder:"Select Option",
        data:[
          {id:"pid",text:"Project Id"},
          {id:"asset",text:"Asset"},
        ],
        dropdownParent:$("#modal-assignBy")
      })
    }

    function assignBy(){
      $("#modal-assignBy").modal("hide")
      $("#modal-assign-engineer-atm").modal("show")
      if ($("#assignBy").val() == "pid") {
        settingPidAssignEngineer("modal-assign-engineer-atm")
        $("#submitAssignEngineer").attr("onclick","submitAssignEngineerAtm('pid')")
      }else{
        settingListAtmId("modal-assign-engineer-atm")
        $("#submitAssignEngineer").attr("onclick","submitAssignEngineerAtm('asset')")
        // settingListEngineerAssign("add",null,"modal-assign-engineer-atm","asset")
        // settingRolesEngineer("modal-assign-engineer-atm","asset")
      }
    }

    function settingListAtmId(id_modal){
      $(".divAssetToggle").show()
      $(".selectAssignAtmId").show()
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
        dropdownParent:$("#"+id_modal)
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
            Swal.fire({
              title: "<strong>Oopzz!</strong>",
              icon: "info",
              html: `
                'Asset -  ${text} cannot duplicate Assign!'
              `,
            })
            alertShown = true;
          }
        });
        settingListEngineerAssign("add",null,"modal-assign-engineer-atm","asset",$(this))
      })
      $(".selectAssignAtmId").next().next().remove()
    }

    function addRowAssignEngineerAtm(){
      var cloneRow = $(".listEngineerAssign:last").clone()
      cloneRow.find(".deleteRowAssign").removeAttr("disabled")

      $(".listEngineerAssign").last().after(cloneRow)
      $(".listEngineerAssign:last").find(".assignEngineerAtm").next("span").find("span span").text("")
      $(".listEngineerAssign:last").find(".selectRolesEngineer ").next("span").find("span span").text("")
      destroySelect2Instances()

      $(".deleteRowAssign").click(function(){
        $(this).closest(".listEngineerAssign").remove()
      })

      if ($("#assignBy").val() == 'pid') {
        settingPidAssignEngineer("modal-assign-engineer-atm")
        // settingListEngineerAssign("add",null,"modal-assign-engineer-atm","pid")
        // settingRolesEngineer("modal-assign-engineer-atm","asset")
      }else{
        settingListAtmId("modal-assign-engineer-atm")
        // settingListEngineerAssign("add",null,"modal-assign-engineer-atm","asset")
        // settingRolesEngineer("modal-assign-engineer-atm","asset")
      }
    }

    function destroySelect2Instances() {
      $(".listEngineerAssign:last").find('select').each(function() {
        if ($(this).data('select2')) {
          $(this).select2('destroy');
        }
      });
    }

    function settingListEngineerAssign(status,name,id_modal,type,param){
      var id = param.val()
      var url = ''
      if (type == "pid") {
        url = "{{url('/asset/getEngineerById?pid=')}}"+id
      }else{
        url = "{{url('/asset/getEngineerById?id_asset=')}}"+id
      }
      $.ajax({
        type:"GET",
        url:url,
        success:function(result){
          // var selectEngineer = param.closest(".col-sm-4").next(".col-sm-4").find("select")
          // var selectEngineer = $(".assignEngineerAtm")
          param.closest(".col-sm-4").next(".col-sm-4").find("select").select2({
            placeholder: 'Select Engineer Name',
            dropdownParent: $("#"+id_modal),
            data:result
          }).on("change", function () {
            var selectedValuesEngineer = [], selectedValuesPid = [];

            var pidValue = $(this).closest(".col-sm-4").prev(".col-sm-4").find(".divPidToggle").find(".selectPidAssignEng ") 

            $('.selectPidAssignEng').not($(pidValue)).each(function() {
              selectedValuesPid = selectedValuesPid.concat($(this).val() || []);
            });

            $('.assignEngineerAtm').not(this).each(function() {
              selectedValuesEngineer = selectedValuesEngineer.concat($(this).val() || []);
            });

            let count = countOccurrences(selectedValuesPid, $(pidValue).val());

            if (count == 1) {
              var currentSelect = $(this);
              var alertShown = false; 

              //Check if any selected value is selected in another Select
              $(this).find('option:selected').each(function() {
                var value = $(this).val();
                var text = $(this).text();

                if (selectedValuesEngineer.includes(value)) {
                  // Unselect the value in the current Select
                  currentSelect.find('option[value="' + value + '"]').prop('selected', false);
                  currentSelect.trigger('change');
                  Swal.fire({
                    title: "<strong>Oopzz!</strong>",
                    icon: "info",
                    html: `
                      'Engineer -  ${text} cannot duplicate Assign!'
                    `,
                  })
                  alertShown = true;
                }
              });
            }

            if (type == "pid") {
              settingRolesEngineer("modal-assign-engineer-atm","pid",param,$(this).val())
            }else{
              settingRolesEngineer("modal-assign-engineer-atm","asset",param,$(this).val())
            }
          })

          // if (status == "edit") {
          //   if (name != null) {
          //     selectEngineer.val(name).trigger('change');
          //   }else{
          //     selectEngineer
          //   }
          // }else{
          //   selectEngineer
          // }

          $(".assignEngineerAtm").next().next().remove()
        }
      })    
    }

    function settingRolesEngineer(id_modal,type,param,engineer){
      var id = param.val()

      var url = ''
      if (type == "pid") {
        url = "{{url('/asset/getRolesById?pid=')}}"+id+"&engineer="+engineer
      }else{
        url = "{{url('/asset/getRolesById?id_asset=')}}"+id+"&engineer="+engineer
      }
      $.ajax({
        type:"GET",
        url:url,
        success:function(result){
          // $(".selectRolesEngineer")
          param.closest(".col-sm-4").next(".col-sm-4").next(".col-sm-3").find("select").select2({
            placeholder: 'Select Engineer Roles',
            dropdownParent: $("#"+id_modal),
            data:result
          }).on("change", function () {
            var selectedValuesRoles = [], selectedValuesPid = [];

            var pidValue = $(this).closest(".col-sm-3").prev(".col-sm-4").prev(".col-sm-4").find(".divPidToggle").find(".selectPidAssignEng ") 

            $('.selectPidAssignEng').not($(pidValue)).each(function() {
              selectedValuesPid = selectedValuesPid.concat($(this).val() || []);
            });

            $('.selectRolesEngineer').not(this).each(function() {
              selectedValuesRoles = selectedValuesRoles.concat($(this).val() || []);
            });

            let count = countOccurrences(selectedValuesPid, $(pidValue).val());

            if (count == 1) {
              var currentSelect = $(this);
              var alertShown = false; 

              //Check if any selected value is selected in another Select
              $(this).find('option:selected').each(function() {
                var value = $(this).val();
                var text = $(this).text();

                if (selectedValuesRoles.includes(value)) {
                  // Unselect the value in the current Select
                  currentSelect.find('option[value="' + value + '"]').prop('selected', false);
                  currentSelect.trigger('change');
                  Swal.fire({
                    title: "<strong>Oopzz!</strong>",
                    icon: "info",
                    html: `
                      'Engineer roles cannot duplicate Assign!'
                    `,
                  })
                  alertShown = true;
                }
              });
            }
          })
          
          $(".selectRolesEngineer").next().next().remove()
        }
      }) 
    }

    function settingPidAssignEngineer(id_modal){
      $(".divPidToggle").show()
      $(".selectPidAssignEng").show()
      $(".selectPidAssignEng").select2({
        ajax: {
          url: '{{url("/asset/getPidAsset")}}',
          processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
              results: data
            };
          },
        },
        placeholder: 'Select Project Id',
        dropdownParent: $("#"+id_modal)
      }).on("change", function () {
        var selectedValues = [];
        $('.selectPidAssignEng').not(this).each(function() {
          selectedValues = selectedValues.concat($(this).val() || []);
        });

        //Check if any selected value is selected in another Select
        var currentSelect = $(this);
        var alertShown = false; 
        $(this).find('option:selected').each(function() {
          var value = $(this).val();
          var text = $(this).text();

          let count = countOccurrences(selectedValues, value);

          if (count > 1) {
            // Unselect the value in the current Select
            currentSelect.find('option[value="' + value + '"]').prop('selected', false);
            currentSelect.trigger('change');
            Swal.fire({
              title: "<strong>Oopzz!</strong>",
              icon: "info",
              html: `
                PID cannot be added more than twice!
              `,
            })
            alertShown = true;
          }
        });

        settingListEngineerAssign("add",null,"modal-assign-engineer-atm","pid",$(this))
      })
      $(".selectPidAssignEng").next().next().remove()
    }

    function countOccurrences(arr, variable) {
      return arr.filter(item => item === variable).length;
    }

    function submitAssignEngineerAtm(type){
      var inputs = document.querySelectorAll('.listEngineerAssign .form-control');
      var arrListEnginner = [],engineer = [], atm_id = [], roles = [];
      // Iterate over each input element

      var isEmptyField = true, InputLengthEmpty = 0
      
      inputs.forEach(function(input) {
        if ($(input).is(":visible") == true) {
          inputLength = inputs.length

          if ($(input).val() == "") {
            isEmptyField = true
            InputLengthEmpty-=1
          }else{
            InputLengthEmpty+=1
            if (InputLengthEmpty < inputLength-$('.listEngineerAssign').length) {
              isEmptyField = true
            }else{
              isEmptyField = false
            }
          }
          // Push the value of each input to the arrListEnginner array
          if(input.name == 'assignEngineerAtm'){
              engineer.push(input.value);
          }

          if (type == 'pid') {
            if(input.name == 'selectPidAssignEng'){
                atm_id.push($(input).val());
            }
          }else{
            if(input.name == 'selectAssignAtmId'){
                atm_id.push($(input).val());
            }
          }

          if(input.name == 'selectRolesEngineer'){
              roles.push(input.value);
          }
        }
      });

      if (type == 'pid') {
        for (var i = 0; i < engineer.length; i++) {
          // Construct object with elements from both arrays
          var combinedObject = {
              engineer: engineer[i],
              pid: atm_id[i],
              role:roles[i]
          };
          // Push the combined object into the resulting array
          arrListEnginner.push(combinedObject);
        }
      }else{
        for (var i = 0; i < engineer.length; i++) {
          // Construct object with elements from both arrays
          var combinedObject = {
              engineer: engineer[i],
              id_asset: atm_id[i],
              role:roles[i]
          };
          // Push the combined object into the resulting array
          arrListEnginner.push(combinedObject);
        }
      }

      if (isEmptyField == false) {
        formData = new FormData()
        formData.append("_token","{{ csrf_token() }}")
        formData.append("type",type)                
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
      ajax : {
        url: '{{url("asset/getAssetOwner")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Asset Owner",
    })

    $("#selectFilterCategory").select2({
      ajax:{
        url: '{{url("asset/getCategory")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Category",
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
    // $("#selectAsset").select2({
    //   placeholder:"Select Asset",
    //   data:[
    //     {id:"asset",text:"Main Asset"},
    //     {id:"peripheral",text:"Peripheral"},
    //   ]
    // }).val("asset").trigger("change")

    $("#selectAssetOwner").select2({
      ajax : {
        url: '{{url("asset/getAssetOwner")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Asset Owner",
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    }).on('change', function(event) {
      event.stopPropagation(); // Prevents the event from bubbling up and potentially affecting other elements
    });

    $("#selectCategory").select2({
      ajax:{
        url: '{{url("asset/getCategory")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Category",
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    }).on('select2:select', function (e) {
      e.stopPropagation();
      handleSpesifikasi(e.params.data);
      if (e.params.data.text == "Computer" || e.params.data.text == "Furniture" || e.params.data.text == "Vehicle" || e.params.data.text == "Electronic") {
        $.each($(".tab-add").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
            $("#"+data.id).closest(".form-group").find("label").text(label_changed)
          }
        })

        $.each($(".tab-add").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
            $("#"+data.id).closest(".form-group").find("label").text(label_changed)
          }
        })

        $.each($(".tab-add").find("textarea"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
            $("#"+data.id).closest(".form-group").find("label").text(label_changed)
          }
        })

        $.each($(".tab-add:nth(0)").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer") {
                if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").hide()
                  $("#"+data.id).closest(".form-group").removeClass("has-error")
                }
              }
          }
        })

        $.each($(".tab-add:nth(0)").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer") {
                if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").hide()
                  $("#"+data.id).closest(".form-group").removeClass("has-error")
                }
              }
          }
        })

        $.each($(".tab-add:nth(0)").find("textarea"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
              if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer") {
                if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").hide()
                  $("#"+data.id).closest(".form-group").removeClass("has-error")
                }
              }
          }
        })
      }else{
        $.each($(".tab-add").find("select"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
            $("#"+data.id).closest(".form-group").find("label").text(label_changed+"*")
          }
        })

        $.each($(".tab-add").find("input"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            if ($(data)[0].id != "inputSpesifikasi" && $(data)[0].id != "inputRMA" && $(data)[0].id != "inputIPAddress" && $(data)[0].id != "inputServer" && $(data)[0].id != "inputPort" && $(data)[0].id != "inputTglBeli" && $(data)[0].id != "inputHarga" && $(data)[0].id != "inputNilaiBuku"&& $(data)[0].id != "inputOS" && $(data)[0].id != "inputVersion" && $(data)[0].id != "inputBuktiAsset") {
              label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
              $("#"+data.id).closest(".form-group").find("label").text(label_changed+"*")
            }
          }
        })

        $.each($(".tab-add").find("textarea"),function(item,data){
          var $el = $(this);
          if ($el.css("display") !== "none") {
            if ($(data)[0].id != "txtAreaNotes") {
              label_changed = $("#"+data.id).closest(".form-group").find("label").text().split("*")[0]
              $("#"+data.id).closest(".form-group").find("label").text(label_changed+"*")
            }
            else {
            }
          }
        })
      }
    })

    function handleSpesifikasi(selectedData){
      var category = selectedData.id;
      var spesifikasiContainer = $("#spesifikasiContainer");
      var spesifikasiDetailContainer = $('#spesifikasiDetailContainer');
      spesifikasiContainer.empty();
      spesifikasiDetailContainer.empty();

      const fieldsHideCOM = ['Memory Slot', 'Additional Disk Type', 'Additional Disk Capacity'];

      $.ajax({
        url: '{{url("asset/getSpesifikasi")}}',
        data: {
          id: category
        },
        type: 'GET',
        success: function(data){
          if(category === 'COM' || category === 'RTR' || category === 'SWT' ){

            if (category === 'COM') {
              var appendAccessoris = ""
              appendAccessoris = appendAccessoris + '<div class="form-group">'
                appendAccessoris = appendAccessoris + '<label for="">Accessoris</label>'
                appendAccessoris = appendAccessoris + '<input id="inputAccessoris" name="inputAccessoris" class="form-control")>'
              appendAccessoris = appendAccessoris + '</div>'
              $("#inputSerialNumber").closest(".form-group").after(appendAccessoris)
            }else{
              $("#inputAccessoris").closest(".form-group").remove()
            }
            
            $("#vendorContainer").show();
            data.forEach(function(item) {
              var div = $('<div class="col-md-4 form-group">');
              var label = $('<label>', {
                  text: item.name
              });
              div.append(label);

              var select = $('<select>', {
                  id: 'inputSpesifikasi_' + item.name,
                  name: 'inputSpesifikasi_' + item.name,
                  class: 'form-control',
                  onchange: function() {
                      fillInput('inputSpesifikasi_' + item.name);
                  }
              });
              
              $('<option>', {
                  value: '',
                  text: 'Select ' + item.name
              }).appendTo(select);

              div.append(select);
              var alert = $('<span>',{
                class : 'help-block',
                style: 'display: none',
                text: 'Please fill ' + item.name + '!'
              });
              div.append(alert);
              spesifikasiContainer.append(div);

              fetchSpesifikasiDetails(item.id, select);
              
              if (category === 'COM' && fieldsHideCOM.includes(item.name)) {
                div.hide();
              }

              select.select2({
                placeholder: 'Select ' + item.name,
                allowClear: true
              });

            });
          }else if(category === 'FNT' || category === 'ELC'){
            $("#inputAccessoris").closest(".form-group").remove()
            $("#vendorContainer").show();
            clearValidationOnChange();
            data.forEach(function(item) {
                    var div = $('<div class="col-md-6 form-group">');
                    
                    var input = $('<input>', {
                        id: 'inputSpesifikasi_' + item.name,
                        name: 'inputSpesifikasi_' + item.name,
                        class: 'form-control',
                        
                    });
                    
                    var label = $('<label>', {
                        text: item.name
                    });

                    var unitSpan = $('<span>', {
                        class: 'input-group-addon',
                        text: item.satuan
                      });
                    
                    div.append(label).append(input);
            
                    input.on('keyup', function() {
                        fillInput('inputSpesifikasi_' + item.name);
                    });

                    if (item.satuan) {
                        var inputGroup = $('<div>', { class: 'input-group' });
                        inputGroup.append(input).append(unitSpan);
                        div.append(inputGroup);
                    } else {
                        div.append(input);
                    }

                    var alert = $('<span>',{
                    class : 'help-block',
                    style: 'display: none',
                    text: 'Please fill ' + item.name + '!'

                    });
                    div.append(alert);

                    spesifikasiContainer.append(div);
            });
          }
          else if (category === 'VHC'){ 
            $("#serialNumberGroup").hide();
            var row = $(`
              <div class="col-md-6">
                <div class="form-group" id="serialNumberGroupVehicle">
                  <label>Nomor Polisi</label>
                  <input id="inputSerialNumber" name="inputSerialNumber" class="form-control" ...>
                  <span class="help-block" style="display:none;">Please fill Nomor Polisi!</span>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group" id="warnaGroup">
                  <label>Warna</label>
                  <input
                    id="inputSpesifikasi_Warna"
                    name="inputSpesifikasi_Warna"
                    class="form-control"
                    onkeyup="fillInput('inputSpesifikasi_Warna')">
                  <span class="help-block" style="display:none;">Please fill Warna!</span>
                </div>
              </div>
            `);
            
            $("#spesifikasiContainer").append(row);
            clearValidationOnChange();
            $("#inputAccessoris").closest(".form-group").remove();
            $("#vendorContainer").show();
            data.forEach(function(item) {
                    if (item.name === 'Warna') {
                      return;
                    }

                    var div = $('<div class="col-md-6 form-group">');
                    
                    var input = $('<input>', {
                        id: 'inputSpesifikasi_' + item.name,
                        name: 'inputSpesifikasi_' + item.name,
                        class: 'form-control',
                        
                    });
                    
                    var label = $('<label>', {
                        text: item.name
                    });

                    var unitSpan = $('<span>', {
                        class: 'input-group-addon',
                        text: item.satuan
                      });
                    
                    div.append(label).append(input);
            
                    input.on('keyup', function() {
                        fillInput('inputSpesifikasi_' + item.name);
                    });

                    input.on('input', function() {
                      $(this).closest('.form-group').find('.help-block').hide();
                      $(this).closest('.form-group').removeClass('has-error');
                    });

                    if (item.satuan) {
                        var inputGroup = $('<div>', { class: 'input-group' });
                        inputGroup.append(input).append(unitSpan);
                        div.append(inputGroup);
                    } else {
                        div.append(input);
                    }

                    var alert = $('<span>',{
                    class : 'help-block',
                    style: 'display: none',
                    text: 'Please fill ' + item.name + '!'

                    });
                    div.append(alert);

                    spesifikasiContainer.append(div);
            });
          }
          else {
            $("#inputAccessoris").closest(".form-group").remove()
            $("#vendorContainer").show();
            var div = $('<div class="col-md-12 form-group">');
            var label = $('<label>', {
              text: 'Spesifikasi',
            });
            div.append(label);
            var select = $('<input>', {
                id: 'inputSpesifikasi',
                name: 'inputSpesifikasi',
                class: 'form-control',
                onkeyup: function(){
                  fillInput('inputSpesifikasi');
                }
            });
            div.append(select);

            var alert = $('<span>',{
                    class : 'help-block',
                    style: 'display: none',
                    text: 'Please fill Spesifikasi!'
                  });
                  div.append(alert);

            spesifikasiContainer.append(div);

          }
        }
      });
    }

    function fetchSpesifikasiDetails(spesifikasiId, selectElement){
            $.ajax({
          url: '{{url("asset/getSpesifikasiDetail")}}',
          data: {
            id: spesifikasiId
          },
          type: 'GET',
          success: function(data){
            data.forEach(function(item) {
                    $('<option>', {
                        value: item.name,
                        text: item.name
                    }).appendTo(selectElement);
                });
          },
          error: function(error) {
                console.error('Error fetching spesifikasi details:', error);
            }
        });
    }

    function collectSpesifikasiValues() {
    var spesifikasiContainer = $("#spesifikasiContainer");
    var spesifikasiValues = [];

    spesifikasiContainer.find('.form-group').each(function() {
        var label = $(this).find('label').text().trim();
        var value;
        var spanText = '';

        if ($(this).find('input').length > 0) {
            value = $(this).find('input').val().trim();
        } else if ($(this).find('select').length > 0) {
            value = $(this).find('select').val().trim();
        }

        if ($(this).find('.input-group-addon').length > 0) {
          var span = $(this).find('span').not('.help-block');
          if (span.length > 0) {
              spanText = ' ' + span.text().trim();
          }
        }

        if (value) {
            spesifikasiValues.push(label + ' : ' + value + spanText);
        }
    });
    var concatenatedValues = spesifikasiValues.join('<br>');
    return concatenatedValues;
    }
  
    $("#selectStatus").select2({
      placeholder:"Select Status",
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below',
      data:[
        {id:"Installed",text:"Installed"},
        {id:"Available",text:"Available"},
        {id:"Rent",text:"Rent"},
        {id:"Unavailable",text:"Unavailable"},
      ]
    }).on('select2:select', function (e) { 
      var id = e.params.data.id
      if (id == "Unavailable" || id == "Rent") {
        $("#txtAreaReason").closest(".form-group").show()
      }else{
        $("#txtAreaReason").closest(".form-group").hide()
      } 
      if (id == "Available") {
        $("#inputInstalledDate").closest(".form-group").hide();
      }
    })

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $("#selectVendor").select2({
      ajax: {
        url: '{{url("asset/getVendor")}}',
        data: function (params) {
          return {
            q:params.term
          };
        },
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select Vendor",
      tags:true,
      createTag: function(params) {
          // Capitalize the first letter of the new tag
          const capitalizedTag = capitalizeFirstLetter(params.term);
          return {
              id: capitalizedTag,
              text: capitalizedTag
          };
      },
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    }).on('select2:select', function(e) {
      const selectedOption = e.params.data;
      const capitalizedOption = capitalizeFirstLetter(selectedOption.text);
      
      // Update the displayed text and value
      $('#selectVendor option[value="' + selectedOption.id + '"]').text(capitalizedOption).val(capitalizedOption);
      
      // Trigger a change event to update the Select2 display
      $('#selectVendor').trigger('change');
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
      tags:true,
      dropdownParent: $("#modal-add-asset"),
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
      tags:true,
      createTag: function(params) {
          // Capitalize the first letter of the new tag
          const capitalizedTag = capitalizeFirstLetter(params.term);
          return {
              id: capitalizedTag,
              text: capitalizedTag
          };
      },
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    }).on('select2:select', function(e) {
      const selectedOption = e.params.data;
      const capitalizedOption = capitalizeFirstLetter(selectedOption.text);
      
      // Update the displayed text and value
      $('#selectTypeDevice option[value="' + selectedOption.id + '"]').text(capitalizedOption).val(capitalizedOption);
      
      // Trigger a change event to update the Select2 display
      $('#selectTypeDevice').trigger('change');
    }) 

    $("#selectLevelSupport").select2({
      ajax: {
        url: '{{url("asset/getLevelSupport")}}',
        data: function (params) {
          return {
            q:params.term
          };
        },
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder:"Select 2nd Level Support",
      tags:true,
      dropdownParent: $("#modal-add-asset"),
    })

    $("#selectStatusCustomer").select2({
      placeholder:"Select Status Customer",
      dropdownParent: $("#modal-add-asset"),
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
      allowClear:true,
      placeholder:"Select ID Assign to Assign",
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    })

    $("#selectPID").select2({
      ajax: {
        url: '{{url("asset/getPid")}}',
        processResults: function (data) {
          return {
            results: data
          };
        },
      },
      placeholder: 'Select PID',
      dropdownParent: $('#modal-add-asset'), // optional if dropdown is inside a modal or a specific container
      dropdownPosition: 'below'
    }).on('select2:select', function (e) {
      let pid = e.params.data.id
      let clientContainer = $("#clientContainer"); 
      let locationContainer = $("#locationContainer");
      let prContainer = $("#prContainer");
      locationContainer.empty();
      clientContainer.empty();
      prContainer.empty();

      if (pid === 'INTERNAL') {
        $("#rmaContainer").hide();
        $("#tanggalPembelianContainer").show();
        $("#hargaContainer").show();
        $("#notesContainer").show();
        $("#deviceCustomerContainer").hide();
        $("#servicePointContainer").hide();
        $("#addressLocationContainer").hide();
        $("#softwareDetailContainer").hide();
        $("#hardwareDetailContainer").hide();
        $("#customerSupportContainer").hide();
        $("#maintenanceContainer").hide();
        $("#clientContainer").show();
        $("#prContainer").show();

        $("#inputClient").val("PT. Sinergy Informasi Pratama")

        $("#hargaContainer").find("label").first().text("Harga Perolehan") 

        $("#selectLevelSupport").select2({
          data:[
            {
              "id": "SIP IT",
              "text": "SIP IT"
            },
            {
              "id": "SIP Facility",
              "text": "SIP Facility"
            }
          ],          
          placeholder:"Select 2nd Level Support",
          tags:true,
          dropdownParent: $("#modal-add-asset"),
        })

        let selectLicense = $('<select>', {
            id: 'inputLicense',
            name: 'inputLicense',
            class: 'form-control',
            onchange: function() {
              fillInput('inputLicense');
            }
        });

        let defaultOption = $('<option>', {
          value: '',
          text: 'Select License',
          disabled: true,
          selected: true
        });

        // Create an array of years from 1 to 10
        const years = Array.from({ length: 10 }, (_, i) => i + 1);
        
        // Map the years to <option> elements and append them to the select element
        const options = years.map(year => {
            const option = document.createElement('option');
            option.value = year;
            option.text = `${year} Year`;
            return option;
        });

        // Append all options to the dropdown
        options.forEach(option => inputLicense.append(option));

        selectLicense.append(defaultOption,options);
        $('#inputLicense').replaceWith(selectLicense);

        $('#inputLicense').on('change', function(event) {
          $('#inputLicenseStart').datepicker('setDate', moment($("#inputInstalledDate").val(),'DD/MM/YYYY').format('DD/MM/YYYY')).attr('readonly',true);
          $('#inputLicenseEnd').datepicker('setDate', moment($("#inputInstalledDate").val(),'DD/MM/YYYY').add(parseInt($("#inputLicense").val()), 'years').format("DD/MM/YYYY")).attr('readonly',true);
        })

        let prLabel = $('<label>',{
          text: "PR"
        });

        prContainer.append(prLabel);

        let prInput = $('<select>',{
          id: "inputPr",
          name: "inputPr",
          style: "width: 100%!important",
          class: "form-control"
        });

        prContainer.append(prInput);

        let prSpan = $('<span>',{
          class: 'help-block',
          style: 'display: none;',
          text: 'Please fill PR!'
        });

        prContainer.append(prInput);

        $("#inputPr").select2({
          ajax: {
            url: '{{url("asset/getPrByYear")}}',
            processResults: function (data) {
              // Transforms the top-level key of the response object from 'items' to 'results'
              return {
                results: data
              };
            },
          },
          allowClear:true,
          placeholder:"Select PR",
          dropdownParent: $("#modal-add-asset"),
        })

        $("#inputPr").on("change", function () {
            var selectedPr = $(this).val();

            if (selectedPr) {
                $.ajax({
                    url: '{{url("asset/getDateByPr")}}',
                    type: 'GET',
                    data: { no_pr: selectedPr },
                    success: function (response) {
                        $("#inputTglBeli").val(response);
                    }
                });
            } else {
                $("#inputTglBeli").val('');
            }
        });

        let locationLabel = $('<label>',{
          text: "Location*"
        });

        locationContainer.append(locationLabel);

        $.ajax({
          type:"GET",
          url:"{{url('asset/getLocationAddress')}}",
          success:function(result){
            let locationSelect = $('<select>', {
              id: 'txtAddressLocation',
              class: 'form-control'
            });

            // let defaultOption = $('<option>', {
            //     value: '',
            //     text: 'Select Location',
            //     disabled: true,
            //     selected: true
            // });

            let defaultOption = $('<option>');
            locationSelect.append(defaultOption);

            $.each(result, function(index, location) {
              let option = $('<option>', {
                  value: location.name, 
                  text: location.name 
              });
              locationSelect.append(option);
            });

            locationContainer.append(locationSelect);

            locationSelect.on('change', function() {
              let selectedIndex = $(this).prop('selectedIndex') - 1; // Adjusting index for the default option
              if (selectedIndex >= 0) {
                  let selectedLocation = result[selectedIndex];
                  $('#lat').val(selectedLocation.lat); 
                  $('#lng').val(selectedLocation.long);   
                  $('#txtAreaLocation').val(selectedLocation.lokasi);
                  initMap(parseFloat(selectedLocation.lat),parseFloat(selectedLocation.long))
                  $("#lat").closest(".form-group").find(".help-block").hide()
                  $("#lat").closest(".form-group").removeClass("has-error")
                  $("#lng").closest(".form-group").find(".help-block").hide()
                  $("#lng").closest(".form-group").removeClass("has-error")
                  $(this).closest(".form-group").find(".help-block").hide()
                  $(this).closest(".form-group").removeClass("has-error")
              }
            });
          }
        });

        let picContainer = $('<div>', { id: 'picContainer' });
        let picLabel = $('<label>',{
          text: 'Nama PIC - Department*'
        });
        picContainer.append(picLabel);

        let selectPIC = $('<select>', {
          id: 'inputPIC',
          name: 'inputPIC',
          class: 'form-control'
        });
        picContainer.append(selectPIC);

        clientContainer.append(picContainer);

        $("#inputPIC").select2({
          ajax: {
              url: '{{url("asset/getEmployeeNames")}}',
              processResults: function (data) {
                  return {
                    results: data
              };
          },
          placeholder: 'Select PIC',
          dropdownParent: $("#modal-add-asset")
        }}).on('select2:select', function (e) {
          e.stopPropagation();
        });
        
        $("#selectStatus").on('select2:select', function(e) {
          let selectedStatus = e.params.data.id;
          let selectedPID = $("#selectPID").val();
          togglePicContainer(selectedStatus, selectedPID);
        });

        function togglePicContainer(status, pid) {
          if (pid === 'INTERNAL') {
            if (status === 'Available') {
              $("#picContainer").hide();
              // $("#picContainer").find(".form-group").removeClass("has-error");
              // $("#picContainer").find(".help-block").hide();
              // $("#inputPIC").val(null).trigger('change');
            } else {
              $("#picContainer").show();
            }
            }
        }

      } else {
          $("#rmaContainer").show();
          $("#tanggalPembelianContainer").show();
          $("#hargaContainer").show();
          $("#notesContainer").show();
          $("deviceCustomerContainer").show();
          $("servicePointContainer").show();
          $("addressLocationContainer").show();
          $("#softwareDetailContainer").show();
          $("#hardwareDetailContainer").show();
          $("#customerSupportContainer").show();
          $("#maintenanceContainer").show();
          $('#inputLicenseStart').attr('readonly',false)
          $('#inputLicenseEnd').attr('readonly',false)
          $("#clientContainer").hide();
          $("#prContainer").show();

          $("#hargaContainer").find("label").first().text("Harga") 

          let inputLicense = $('<input>', {
              autocomplete: 'off',
              class: 'form-control',
              id: 'inputLicense',
              name: 'inputLicense',
              onkeyup: function() {
                  fillInput('inputLicense');
              }
          });

          $('#inputLicense').replaceWith(inputLicense);

        let label = $('<label>',{
            text: 'Client*'
          });
          
          clientContainer.append(label);

        let inputClient = $('<input>', {
              type: 'text',
              class: 'form-control',
              id: 'inputClient',
              name: 'inputClient',
              disabled: true
          });
        clientContainer.append(inputClient);

        $.ajax({
          type:"GET",
          url:"{{url('asset/getClientByPid')}}",
          data:{
            pid:pid
          },
          success:function(result){
            $("#inputClient").val(result).prop("disabled",true)
          }
        });
      }
    })   

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

    $('#inputTglBeli').datepicker({
      placeholder:"yyyy-mm-dd",
      autoclose: true,
      format: 'yyyy-mm-dd'
    })

    function clearValidationOnChange() {
      $("input").on("input", function() {
          if ($(this).val() !== "") {
              $(this).closest(".form-group").find(".help-block").hide();
              $(this).closest(".form-group").removeClass("has-error");
          }
      });

      $("select").on("change", function() {
          if ($(this).val() !== "") {
              $(this).closest(".form-group").find(".help-block").hide();
              $(this).closest(".form-group").removeClass("has-error");
          }
      });
    }

    // $("#inputServer").inputmask("ip")
    // $("#inputIPAddress").inputmask("ip")
    currentTab = 0
    function nextPrev(n){
      //console.log(n)
      //console.log(currentTab)
      if (currentTab == 0) {
        if ($("#selectCategory").val() != "COM") {
          if($("#selectCategory").val() === "FNT" || $("#selectCategory").val() === "VHC" || $("#selectCategory").val() === "ELC"){
            $.each($(".tab-add:first").find("select"), function(item, data) {
              var $el = $(this);
              var id = data.id;
              if ($el.css("display") !== "none" && id !== "selectVendor" && id !== "selectStatus" && id !== "selectTypeDevice") {
                  if ($(data).val() == "") {
                      $("select[name='"+ id +"']").closest(".form-group").find(".help-block").show();
                      $("select[name='"+ id +"']").closest(".form-group").addClass("has-error");
                  }
              }
           });

           $.each($(".tab-add:first").find("input"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                if (data.id != "inputRMA" && data.id != "inputSpesifikasi" && data.id != "inputServer" && data.id != "inputTglBeli" && data.id != "inputHarga" && data.id != "inputNilaiBuku" && data.id != "inputBuktiAsset" && data.id != "inputSerialNumber" && data.id != "inputClient") {
                  if ($(data).val() == "") {
                    $("input[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                    $("input[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                  }
                }
              }
            })

          } else {   
            $.each($(".tab-add:first").find("select"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                if (data.id != "inputSpesifikasi_Port" && data.id != "inputSpesifikasi_Speed") {
                  if ($(data).val() == "") {
                    $("select[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                    $("select[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                  }
                }                
              }
            });

            $.each($(".tab-add:first").find("input"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                if (data.id != "inputRMA" && data.id != "inputSpesifikasi" && data.id != "inputServer" && data.id != "inputTglBeli" && data.id != "inputHarga" && data.id != "inputNilaiBuku" && data.id != "inputBuktiAsset" && data.id != 'inputClient') {
                  if ($(data).val() == "") {
                    $("input[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                    $("input[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                  }
                }
              }
            })
          }

        }

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
      }else if(currentTab == 1){
        let x = document.getElementsByClassName("tab-add");
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
          x[n].style.display = "none";
          currentTab = 0;
        }

        btnAddAsset(currentTab);
      }else if (currentTab == 2) {
        if ($("#selectCategory").val() != "COM") {
          if($("#selectCategory").val() === "FNT" || $("#selectCategory").val() === "VHC" || $("#selectCategory").val() === "ELC"){
            $.each($(".tab-add:nth(2)").find("input"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                if (data.id != "inputIdDeviceCustomer") {
                  if ($(data).val() == "") {
                    $("#"+data.id).closest(".form-group").find(".help-block").show()
                    $("#"+data.id).closest(".form-group").addClass("has-error")
                  }
                }
              }
            })

            $.each($(".tab-add:nth(2)").find("select"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none" && data.id != "service_point") {
                  if ($(data).val() == "") {
                      $("#"+data.id).closest(".form-group").find(".help-block").show()
                      $("#"+data.id).closest(".form-group").addClass("has-error")
                  }
              }
            })
          } else {
            $.each($(".tab-add:nth(2)").find("select"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                  if ($(data).val() == "") {
                      $(data).closest(".form-group").find(".help-block").show()
                      $(data).closest(".form-group").addClass("has-error")
                  }
              }
            })
  
            $.each($(".tab-add:nth(2)").find("input"),function(item,data){
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
  
            $.each($(".tab-add:nth(2)").find("textarea"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                if ($(data).val() == "") {
                  $("#"+data.id).closest(".form-group").find(".help-block").show()
                  $("#"+data.id).closest(".form-group").addClass("has-error")
                }
              }
            })
          }
        }
        
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
      }else if (currentTab == 3){
        if ($(".tab-add:nth(3)").find(".form-group").hasClass("has-error") == false) {
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
        if ($("#selectCategory").val() != "COM") {
          $.each($(".tab-add:first").find("select"),function(item,data){
            if ($(data).val() == "") {
              $("select[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
              $("select[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
            }
          })

          $.each($(".tab-add:first").find("input"),function(item,data){
            var $el = $(this);
            if ($el.css("display") !== "none") {
              if (data.id != "inputRMA" && data.id != "inputSpesifikasi" && data.id != "inputTglBeli" && data.id != "inputHarga" && data.id != "inputNilaiBuku") {
                if ($(data).val() == "") {
                  $("input[name='"+ data.id +"']").closest(".form-group").find(".help-block").show()
                  $("input[name='"+ data.id +"']").closest(".form-group").addClass("has-error")
                }
              } 
            }
          })

          checkInput = $(".tab-add:first").find(".form-group").hasClass("has-error")
        }
      }else{
        if ($("#selectCategory").val() != "COM") {
          if($("#selectCategory").val() === "FNT" || $("#selectCategory").val() === "VHC" || $("#selectCategory").val() === "ELC" ){
            $.each($(".tab-add:nth(2)").find("select"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none" && data.id != "selectStatusCustomer" && data.id != "selectLevelSupport") {
                  if ($(data).val() == "") {
                      $("#"+data.id).closest(".form-group").find(".help-block").show()
                      $("#"+data.id).closest(".form-group").addClass("has-error")
                  }
              }
            });
            $.each($(".tab-add:nth(2)").find("input"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                  if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer" && data.id != "inputOS" && data.id != "inputVersion" && data.id != "inputMaintenanceStart" && data.id != "inputMaintenanceEnd") {
                    if($("inputLicense").val() === "Yes"){
                      if ($(data).val() == "") {
                        $("#"+data.id).closest(".form-group").find(".help-block").show()
                        $("#"+data.id).closest(".form-group").addClass("has-error")
                      }
                    }else{
                      if(data.id != "inputLicenseStart" && data.id != "inputLicenseEnd"){
                        if ($(data).val() == "") {
                        $("#"+data.id).closest(".form-group").find(".help-block").show()
                        $("#"+data.id).closest(".form-group").addClass("has-error")
                      }
                      }
                    }
                    
                  }
              }
            })
          } else {
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
                  if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer" && data.id != "inputOS" && data.id != "inputVersion") {
                    if ($(data).val() == "") {
                      $("#"+data.id).closest(".form-group").find(".help-block").show()
                      $("#"+data.id).closest(".form-group").addClass("has-error")
                    }
                  }
              }
            })

            $.each($(".tab-add:nth(3)").find("select"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                  if ($(data).val() == "") {
                      $("#"+data.id).closest(".form-group").find(".help-block").show()
                      $("#"+data.id).closest(".form-group").addClass("has-error")
                  }
              }
            })
  
            $.each($(".tab-add:nth(3)").find("input"),function(item,data){
              var $el = $(this);
              if ($el.css("display") !== "none") {
                  if (data.id != "inputIPAddress" && data.id != "inputPort" && data.id != "inputServer" && data.id != "inputOS" && data.id != "inputVersion") {
                    if ($(data).val() == "") {
                      $("#"+data.id).closest(".form-group").find(".help-block").show()
                      $("#"+data.id).closest(".form-group").addClass("has-error")
                    }
                  }
              }
            })
          }
          if ($(".tab-add:first").is(":visible")) {
            checkInput = $(".tab-add:first").find(".form-group").hasClass("has-error")
          }else{
            checkInput = $(".tab-add:nth(3)").find(".form-group").hasClass("has-error")
          }
        }
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
            var dataForm = new FormData();
            
            if ($("#selectCategory").val() === "COM") {
              let spesifikasi = collectSpesifikasiValues().replaceAll("<br>", "\n");
              const pattern = /^\s*OS\s*Version\s*:\s*(.*)$/gim;
              const match = pattern.exec(spesifikasi);

              if (match) {
                const osValue = match[1].trim();
              } 
            } else {
              osValue = "";
            }

            dataForm.append('operatingSystem', osValue);

            // if its vehicle remove Nomor Polisi in spesifikasi
            if ($("#selectCategory").val() === "VHC") {
              let spesifikasi = collectSpesifikasiValues().replaceAll("<br>", "\n");
              const match = spesifikasi.match(/^Nomor Polisi\s*:\s*(.*)$/m);
              dataForm.append("serialNumber", match[1].trim());
              spesifikasi = spesifikasi.replace(/^Nomor Polisi\s*:\s*.*$/m, "").trim();
              dataForm.append("spesifikasi", spesifikasi);
            }
            else {
              dataForm.append('serialNumber',$("#inputSerialNumber").val());
              dataForm.append('spesifikasi',collectSpesifikasiValues().replaceAll("<br>", "\n"));

            }

            dataForm.append('_token','{{ csrf_token() }}');
            dataForm.append('idDeviceCustomer',$("#inputIdDeviceCustomer").val())
            dataForm.append('client',$("#inputClient").val())
            dataForm.append('pid',$("#selectPID").val())
            dataForm.append('kota',$("#selectCity").val())
            dataForm.append('alamatLokasi',$("#txtAddressLocation").val())
            dataForm.append('detailLokasi',$("#txtAreaLocation").val())
            dataForm.append('ipAddress',$("#inputIPAddress").val())
            dataForm.append('ipServer',$("#inputServer").val())
            dataForm.append('port',$("#inputPort").val())
            dataForm.append('statusCust',$("#selectStatusCustomer").val())
            dataForm.append('secondLevelSupport',$("#selectLevelSupport").val())

            
            dataForm.append('versionOs',$("#inputVersion").val())
            dataForm.append('installedDate',moment(($("#inputInstalledDate").val()),"DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('license',$("#inputLicense").val())
            dataForm.append('licenseStartDate',moment(($("#inputLicenseStart").val()), "DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('licenseEndDate',moment(($("#inputLicenseEnd").val()), "DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('maintenanceStart',moment(($("#inputMaintenanceStart").val()), "DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('maintenanceEnd',moment(($("#inputMaintenanceEnd").val()), "DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('status',$("#selectStatus").val())
            dataForm.append('vendor',$("#selectVendor").val())
            dataForm.append('typeDevice',$("#selectTypeDevice").val())

            // dataForm.append('serialNumber',$("#inputSerialNumber").val())
            // dataForm.append('spesifikasi',collectSpesifikasiValues().replaceAll("<br>", "\n"));

            dataForm.append('rma',$("#inputRMA").val())
            dataForm.append('notes', ($("#txtAreaNotes").val() || "").replace("<br>", "\n"));
            dataForm.append('categoryPeripheral',$("#selectPeripheral").val())
            dataForm.append('typeAsset',$("#selectAsset").val())
            dataForm.append('assetOwner',$("#selectAssetOwner").val())
            dataForm.append('category',$("#selectCategory").val())
            dataForm.append('category_text',$("#selectCategory").select2("data")[0].text)
            dataForm.append('assignTo',$("#selectAssigntoPeripheral").val())
            dataForm.append('latitude',$("#lat").val())
            dataForm.append('longitude',$("#lng").val())
            dataForm.append('servicePoint',$("#service_point").val())
            //dataForm.append('tanggalBeli',moment(($("#inputTglBeli").val()), "DD/MM/YYYY").format("YYYY-MM-DD"))
            dataForm.append('tanggalBeli', $("#inputTglBeli").val())
            dataForm.append('hargaBeli',$("#inputHarga").val())
            dataForm.append('nilaiBuku',$("#inputNilaiBuku").val())
            dataForm.append('reason',$("#txtAreaReason").val())
            dataForm.append('inputDoc',$('#inputBuktiAsset').prop('files')[0])
            dataForm.append('pr',$("#inputPr").val())
            dataForm.append('pic',$("#inputPIC").val())
            dataForm.append('accessoris',$("#inputAccessoris").val())

            $.ajax({
              type:"POST",
              url:"{{url('asset/storeAsset')}}",
              data:dataForm,
              processData: false,
              contentType: false,
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
                  didOpen: () => {
                      Swal.showLoading()
                  }
                })
              },
              success: function(result){
                Swal.close()
                Swal.fire({
                  title: 'Add Asset Successsfully!',
                  icon: 'success',
                  confirmButtonText: 'Reload',
                }).then((result) => {
                  $("#modal-add-asset").modal("hide")
                  window.location.reload()
                  // $('#tableAsset').DataTable().ajax.url("{{url('asset/getDataAsset')}}").load();
                  // InitiateCountDashboard("{{url('asset/getCountDashboard')}}")
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
        // if ($("#selectFilterCategory").val() == "ATM" || $("#selectFilterCategory").val() == "CRM") {
        //   if(accesable.includes('btnAssignEngineer')){
        //     $(".btnAssignEngineer").show()
        //   }else{
        //     $(".btnAssignEngineer").hide()
        //   }
        // }else{
        //   $(".btnAssignEngineer").hide()
        // }
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
      // $(".btnAssignEngineer").hide()
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
      $("#modal-add-asset").modal("hide")  
    }

    function fillInput(argument) {
      // if (argument == "selectAsset") {
      //   let assetVal = $("#selectAsset").val()
      //   if (assetVal == 'asset') {
      //     $(".tab-add").find(".form-group.divPeripheral").css('display','none') 

      //     $("#nextBtnAdd").attr('onclick','nextPrev(1)')
      //     document.getElementById("prevBtnAdd").style.display = "inline";
      //     document.getElementById("prevBtnAdd").innerHTML = "Cancel";
      //     document.getElementById("nextBtnAdd").innerHTML = "Next";
      //   }else if (assetVal == 'peripheral') {
      //     $(".tab-add").find(".form-group.divPeripheral").css('display','block') 

      //     document.getElementById("prevBtnAdd").style.display = "inline";
      //     $("#prevBtnAdd").attr('onclick','closeModal()')   
      //     document.getElementById("prevBtnAdd").innerHTML = "Cancel";
      //     $("#nextBtnAdd").attr('onclick','saveAsset("peripheral")')
      //     document.getElementById("nextBtnAdd").innerHTML = "Save";
      //   }
      // }

      if (argument == "selectAssigntoPeripheral") {
        let assetVal = $("#selectAssigntoPeripheral").val()
        if (assetVal != "") {
          document.getElementById("prevBtnAdd").style.display = "inline";
          $("#prevBtnAdd").attr('onclick','closeModal()')   
          document.getElementById("prevBtnAdd").innerHTML = "Cancel";
          $("#nextBtnAdd").attr('onclick','saveAsset("asset")')
          document.getElementById("nextBtnAdd").innerHTML = "Save";
        }
      }else if(argument == "inputLicense") {
        
        $("#inputLicense").val()
      }

      if ($("#"+argument).val() != "") {
        $("#"+argument).closest(".form-group").removeClass("has-error")
        $("#"+argument).closest(".form-group").find(".help-block").hide()
      }
    }

    $('#modal-add-asset').on('hidden.bs.modal', function () {
      currentTab = 0
      $(".tab-add").css("display","none")
      $(".tab-add").find("select").val("").trigger("change")
      $(".tab-add").find("input").val("")
      $(".tab-add").find("textarea").val("")
      $(".tab-add").find(".form-group").removeClass("has-error")
      $(".tab-add").find(".form-group").find(".help-block").hide()
      $(".tab-add").find("textarea[id='txtAreaReason']").closest(".form-group").hide()
      $(".tab-add").find("textarea[id='txtAreaReason']").val("")
    });

    $('#modal-assign-engineer-atm').on('hidden.bs.modal', function () {
      $(".divAssetToggle").hide()
      $(".divPidToggle").hide()
      $(".selectPidAssignEng").hide()
      $(".selectAssignAtmId").hide()
      $(".divPidToggle").hide()
      $(this).find("select").val("").trigger("change")
      $("#assignBy").val("").trigger("change")
      if ($(".listEngineerAssign").length > 1) {
        $(".listEngineerAssign").last().remove()
      }
    });

    $('#modal-add-service-point').on('hidden.bs.modal', function () {
      $("input").val("")
    });

    function InitiateCountDashboard(url){
      $.ajax({
        url:url,
        type:"GET",
        success:function(response){
          $("#countAll").text(response.countAll)
          $("#countAll").next().remove()
          $("#countAll").after("<p>Total Assets</p>")
          $("#countAll").closest("div").next(".icon").html("<i class='fa fa-table'></i>")

          $("#countInstalled").text(response.countInstalled)
          $("#countInstalled").next().remove()
          $("#countInstalled").after("<p>Installed</p>")
          $("#countInstalled").closest("div").next(".icon").html("<i class='fa fa-gear'></i>")

          $("#countAvailable").text(response.countAvailable)
          $("#countAvailable").next().remove()
          $("#countAvailable").after("<p>Available</p>")
          $("#countAvailable").closest("div").next(".icon").html("<i class='fa fa-archive'></i>")

          $("#countRent").text(response.countRent)
          $("#countRent").next().remove()
          $("#countRent").after("<p>Rent</p>")
          $("#countRent").closest("div").next(".icon").html("<i class='fa fa-list'></i>")

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

    var map, marker, map_sp, marker_sp;
    var lat = '',lang = '', lat_sp = '', lang_sp = ''
    function initMap(lat='',lang='',inputLat=false,inputLang=false){
      if (lat == '') {
        lat = -6.2297419
        lat_sp = -6.2297419
        inputLat = false
      }else{
        lat = lat
        lat_sp = -6.2297419
        inputLat = true
      }

      if (lang == '') {
        lang = 106.759478
        lang_sp = 106.759478
        inputLang = false
      }else{
        lang = lang
        lang_sp = 106.759478
        inputLang = true
      }

      //map add asset
      map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: lat, lng: lang},
        zoom: 10,
        // zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false
      });

      // //map add service point
      map_sp = new google.maps.Map(document.getElementById('map-sp'), {
        center: {lat: lat_sp, lng: lang_sp},
        zoom: 10,
        // zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false
      });

      //add asset
      map.addListener('click', function(result) {
        marker.setVisible(false);
        marker.setPosition(result.latLng);
        marker.setVisible(true);
        $("#lat").val(result.latLng.lat());
        $("#lng").val(result.latLng.lng());
      });

      //marker asset
      marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29),
        draggable: true,
        animation: google.maps.Animation.BOUNCE
      });

      //add service point
      map_sp.addListener('click', function(result) {
        marker_sp.setVisible(false);
        marker_sp.setPosition(result.latLng);
        marker_sp.setVisible(true);
        $("#lat-sp").val(result.latLng.lat());
        $("#lng-sp").val(result.latLng.lng());
      });

      //marker service point
      marker_sp = new google.maps.Marker({
        map: map_sp,
        anchorPoint: new google.maps.Point(0, -29),
        draggable: true,
        animation: google.maps.Animation.BOUNCE
      });

      if (inputLat == true && inputLang == true) {
        $.ajax({
          type:"GET",
          url:"https://maps.googleapis.com/maps/api/geocode/json?latlng="+ lat +","+ lang +"&key={{env('GOOGLE_API_KEY_GLOBAL')}}",
          success: function(resultGoogle){
            if ($("#inputDetailLocationSP").is(":visible")) {
              $("#inputDetailLocationSP").val(resultGoogle.results[0].formatted_address)
              map_sp.setCenter({lat: lat_sp, lng: lang_sp});
              marker_sp.setPosition({lat:lat_sp , lng: lang_sp});
              marker_sp.setVisible(true);
              map_sp.setZoom(17);
            }else{
              $("#txtAreaLocation").closest(".form-group").find(".help-block").hide()
              $("#txtAreaLocation").closest(".form-group").removeClass("has-error")
              $("#txtAreaLocation").val(resultGoogle.results[0].formatted_address)
              map.setCenter({lat: lat, lng: lang});
              marker.setPosition({lat:lat , lng: lang});
              marker.setVisible(true);
              map.setZoom(17);
            }
          }
        })
      }
      
      // const myTimeout = setTimeout(initiateLocSP, 1000);

      // console.log($("#modal-add-service-point").find("#inputDetailLocationSP").is(":visible"))

      // function initiateLocSP() {
      //   if ($("#modal-add-service-point").find("#inputDetailLocationSP").is(":visible")) {
      //     return true
      //   }
      // }

      setTimeout(function() {
        if ($("#modal-add-service-point").find("#inputDetailLocationSP").is(":visible")) {
          var autocomplete_sp = new google.maps.places.Autocomplete((document.getElementById('inputDetailLocationSP')));

          autocomplete_sp.addListener('place_changed', function() {
            google.maps.event.trigger(map_sp, 'resize');
            marker_sp.setVisible(false);
            var place_sp = autocomplete_sp.getPlace();

            if (!place_sp.geometry) {
              window.alert("No details available for input: " + place_sp.name);
              return;
            }

            if (place_sp.geometry.viewport) {
              map_sp.fitBounds(place_sp.geometry.viewport);
            } else {
              map_sp.setCenter(place_sp.geometry.location);
              map_sp.setZoom(17);
            }
            marker_sp.setPosition(place_sp.geometry.location);
            marker_sp.setVisible(true);
            $("#lat-sp").val(place_sp.geometry.location.lat());
            $("#lng-sp").val(place_sp.geometry.location.lng());
          });

          google.maps.event.addListener(marker_sp, 'dragend', function (evt) {
            $("#lat-sp").val(evt.latLng.lat());
            $("#lng-sp").val(evt.latLng.lng());
          });
        }else{
          var autocomplete = new google.maps.places.Autocomplete((document.getElementById('txtAreaLocation')));

          autocomplete.addListener('place_changed', function() {
            google.maps.event.trigger(map, 'resize');
            marker.setVisible(false);
            var place = autocomplete.getPlace();

            if (!place.geometry) {
              window.alert("No details available for input: " + place.name);
              return;
            }

            if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
            } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            $("#lat").closest(".form-group").find(".help-block").hide()
            $("#lat").closest(".form-group").removeClass("has-error")

            $("#lng").closest(".form-group").find(".help-block").hide()
            $("#lng").closest(".form-group").removeClass("has-error")

            $("#lat").val(place.geometry.location.lat());
            $("#lng").val(place.geometry.location.lng());
          });

          google.maps.event.addListener(marker, 'dragend', function (evt) {
            $("#lat").val(evt.latLng.lat());
            $("#lng").val(evt.latLng.lng());
          });
        }
      }, 1000);
      // if (myTimeout == 27) {
      //   var autocomplete_sp = new google.maps.places.Autocomplete((document.getElementById('inputDetailLocationSP')));

      //   autocomplete_sp.addListener('place_changed', function() {
      //     google.maps.event.trigger(map_sp, 'resize');
      //     marker_sp.setVisible(false);
      //     var place_sp = autocomplete_sp.getPlace();

      //     if (!place_sp.geometry) {
      //       window.alert("No details available for input: " + place_sp.name);
      //       return;
      //     }

      //     if (place_sp.geometry.viewport) {
      //       map_sp.fitBounds(place_sp.geometry.viewport);
      //     } else {
      //       map_sp.setCenter(place_sp.geometry.location);
      //       map_sp.setZoom(17);
      //     }
      //     marker_sp.setPosition(place_sp.geometry.location);
      //     marker_sp.setVisible(true);
      //     $("#lat-sp").val(place_sp.geometry.location.lat());
      //     $("#lng-sp").val(place_sp.geometry.location.lng());
      //   });

      //   google.maps.event.addListener(marker_sp, 'dragend', function (evt) {
      //     $("#lat-sp").val(evt.latLng.lat());
      //     $("#lng-sp").val(evt.latLng.lng());
      //   });
      // }else{
      //   var autocomplete = new google.maps.places.Autocomplete((document.getElementById('txtAreaLocation')));

      //   autocomplete.addListener('place_changed', function() {
      //     google.maps.event.trigger(map, 'resize');
      //     marker.setVisible(false);
      //     var place = autocomplete.getPlace();

      //     if (!place.geometry) {
      //       window.alert("No details available for input: " + place.name);
      //       return;
      //     }

      //     if (place.geometry.viewport) {
      //       map.fitBounds(place.geometry.viewport);
      //     } else {
      //       map.setCenter(place.geometry.location);
      //       map.setZoom(17);
      //     }
      //     marker.setPosition(place.geometry.location);
      //     marker.setVisible(true);

      //     $("#lat").closest(".form-group").find(".help-block").hide()
      //     $("#lat").closest(".form-group").removeClass("has-error")

      //     $("#lng").closest(".form-group").find(".help-block").hide()
      //     $("#lng").closest(".form-group").removeClass("has-error")

      //     $("#lat").val(place.geometry.location.lat());
      //     $("#lng").val(place.geometry.location.lng());
      //   });

      //   google.maps.event.addListener(marker, 'dragend', function (evt) {
      //     $("#lat").val(evt.latLng.lat());
      //     $("#lng").val(evt.latLng.lng());
      //   });
      // }
    }

    $("#lat-sp").keyup(function(){
      if ($("#lng-sp").val() == '') {
        initMap(parseFloat($("#lat-sp").val()),'')
      }else{
        initMap(parseFloat($("#lat-sp").val()),parseFloat($("#lng-sp").val()))
      }
    })

    $("#lng-sp").keyup(function(){
      if ($("#lat-sp").val() == '') {
        initMap('',parseFloat($("#lng-sp").val()))
      }else{
        initMap(parseFloat($("#lat-sp").val()),parseFloat($("#lng-sp").val()))
      }
    })

    $("#lat").keyup(function(){
      if ($("#lng").val() == '') {
        initMap(parseFloat($("#lat").val()),'')
      }else{
        initMap(parseFloat($("#lat").val()),parseFloat($("#lng").val()))
      }
    })

    $("#lng").keyup(function(){
      if ($("#lat").val() == '') {
        initMap('',parseFloat($("#lng").val()))
      }else{
        initMap(parseFloat($("#lat").val()),parseFloat($("#lng").val()))
      }
    })

    function btnAddServicePoint(){
      initMap()
      $("#modal-add-service-point").modal("show")
    }

    function btnAddCategory(){
      $("#modal-add-category").modal("show")
      $("#inputCatCode").val("")
      $("#inputCatName").val("")
    }

    function saveServicePoint(){
      Swal.fire({
          title: 'Are you sure?',  
          text: "Save Service Point",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url:"{{url('/asset/storeServicePoint')}}",
            data: {
              _token:"{{csrf_token()}}",
              servicePoint:$("#inputServicePoint").val(),
              latitude:$("#lat-sp").val(),
              longitude:$("#lng-sp").val(),
              detailLokasi:$("#inputDetailLocationSP").val()
            },
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
                  didOpen: () => {
                      Swal.showLoading()
                  }
              })
            },
            success: function(result) {
              Swal.showLoading()
              Swal.fire(
                  'Successfully!',
                  'Added Service Point.',
                  'success'
              ).then((result) => {
                  if (result.value) {
                    $("#modal-add-service-point").modal("hide")
                  }
              })
            }
          })          
        }
      })
    }

    function saveCategory(){
      Swal.fire({
          title: 'Are you sure?',  
          text: "Save Category",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
      }).then((result) => {
        $("#inputCatCode").next("span").hide()
        $("#inputCatName").next("span").hide()
        
        if (result.value) {
          $.ajax({
            type: "POST",
            url:"{{url('/asset/storeCategory')}}",
            data: {
              _token:"{{csrf_token()}}",
              id_category:$("#inputCatCode").val(),
              name:$("#inputCatName").val(),
            },
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
                  didOpen: () => {
                      Swal.showLoading()
                  }
              })
            },
            success: function(result) {
              Swal.showLoading()
              Swal.fire(
                  'Successfully!',
                  'Added Category.',
                  'success'
              ).then((result) => {
                  if (result.value) {
                    $("#modal-add-category").modal("hide")
                  }
              })
            },
            error:function (xhr, ajaxOptions, thrownError) {
              Swal.close()
              $.each(xhr.responseJSON,function(idx,value){
                $.each(value,function(idxs,values){
                  $("#"+values.split(":")[0].replace(/{|"/g, '')).next("span").show()
                  $("#"+values.split(":")[0].replace(/{|"/g, '')).next("span").text(values.split(":")[1].replace(/}|"/g, ''))
                })
              })
              
            }
          })          
        }
      })
    }

    function showQRDetail(id_detail,id_asset){
      $("#modal-show-Qr").modal('show')
      $("#printQr").attr("onclick","printQRCode("+ '"' + id_asset + '"' + ")")
      var url = "{{url('asset/detail')}}?id_asset="+ id_detail
      // if (!url) {
      //   alert("Please enter a valid URL.");
      //   return;
      // }

      // var qrcodeContainer = document.getElementById('divShowQr');
      // qrcodeContainer.innerHTML = ""; // Clear previous QR code

      // new QRCode(qrcodeContainer, {
      //   text: url,
      //   width: 250,
      //   height: 250,
      //   colorDark: "#000000",
      //   colorLight: "#ffffff",
      //   correctLevel: QRCode.CorrectLevel.H
      // });

      var qr = qrcode(0, 'L');
      qr.addData(url);
      qr.make();
      
      var canvas = document.createElement('canvas');
      canvas.width = 200;
      canvas.height = 200;
      // canvas.style.margin = "0 auto"; 

      var context = canvas.getContext('2d');
      var cellSize = 200 / qr.getModuleCount();
      for (var row = 0; row < qr.getModuleCount(); row++) {
        for (var col = 0; col < qr.getModuleCount(); col++) {
            context.fillStyle = qr.isDark(row, col) ? '#000000' : '#ffffff';
            context.fillRect(col * cellSize, row * cellSize, cellSize, cellSize);
        }
      }

      document.getElementById('divShowQr').innerHTML = '';
      document.getElementById('divShowQr').appendChild(canvas);
      $(canvas).after("<br><label style='font-size:20px'>"+ id_asset +"</label>")
    }

    function printQRCode(id_asset){
      var qrCodeDiv = document.getElementById('divShowQr');
      var canvas = qrCodeDiv.querySelector('canvas');
      var dataUrl = canvas.toDataURL();

      var printWindow = window.open('', '_blank');
      printWindow.document.open();
      printWindow.document.write(`
          <html>
              <head>
                  <title>Print QR Code</title>
                  <style>
                      body {
                          text-align: center;
                      }
                      img {
                          width: 100px;
                          height: 100px;
                      }
                  </style>
              </head>
              <body onload="window.print(); window.close();">
                  <img src="${dataUrl}" alt="QR Code">
                  <br>
                  <label style="font-size:10px">${id_asset}</label>
              </body>
          </html>
      `);
      printWindow.document.close();
    }
  </script>
@endsection