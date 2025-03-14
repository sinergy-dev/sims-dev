@extends('template.main')
@section('tittle')
  Detail Asset
@endsection
@section('head_css')
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <style type="text/css">
    select + span{
      width: 100%!important;
    }

    textarea{
      resize:vertical;
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
  </style>
@endsection
@section('content')
  <section class="content-header">
    <a href="{{url('asset/index')}}" class="btn btn-sm btn-danger" style="display:inline;"><i class="fa fa-arrow-left"></i> Back</a>
    <h1 style="display:inline;vertical-align: middle;" id="titleDetailIdAsset">
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{url('asset/index')}}"><i class="fa fa-table"></i> Asset</a></li>
    <li class="active">Detail Asset</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <fieldset class="fieldsetDivAssetDetail">
          <div class="row divAsset">
            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>Asset Owner</label>
                <select id="selectAssetOwner" name="selectAssetOwner" class="form-control">
                </select>
              </div>

              <div class="form-group">
                <label>Category</label>
                <select id="selectCategory" name="selectCategory" class="form-control" disabled></select>
              </div>

              <div class="form-group">
                <label>Status</label>
                <select id="selectStatus" name="selectStatus" class="form-control"></select>
              </div>

              <div class="form-group">
                <label>Vendor</label>
                <select id="selectVendor" name="selectVendor" class="form-control"><option></option></select>
              </div>

              <div class="form-group">
                <label>Type Device</label>
                  <select id="selectTypeDevice" name="selectTypeDevice" class="form-control"><option></option></select>
              </div>
            </div>

            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>RMA</label>
                <input id="inputRMA" name="inputRMA" class="form-control">
              </div>

              <div class="form-group" id="prContainer"></div>
                <div class="form-group">
                  <label for="inputSerialNumber">Serial Number</label>
                  <input id="inputSerialNumber" name="inputSerialNumber" class="form-control">
                </div>
              <div class="form-group">
                <label for="">Tanggal Pembelian</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input id="inputTglBeli" name="inputTglBeli" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="">Harga</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <strong>RP</strong>
                  </div>
                  <input id="inputHarga" type="text" name="inputHarga" class="form-control money">
                </div>
              </div>

              <div class="form-group">
                <label for="">Nilai Buku</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <strong>RP</strong>
                  </div>
                  <input id="inputNilaiBuku" type="text" name="inputNilaiBuku" class="form-control money" readonly>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>Spesifikasi</label>
                <textarea class="form-control" id="inputSpesifikasi" name="inputSpesifikasi" style="min-height: 100px; /* Set minimum height */
                    max-height: 200px; /* Set maximum height */
                    overflow: scroll; /* Prevent scrollbar */
                    resize: none; /* Prevent manual resizing */"></textarea>
              </div>
              <div class="form-group">
                <label>Notes</label>
                <textarea class="form-control" id="txtAreaNotes" name="txtAreaNotes"></textarea>
              </div>
              <div class="form-group" style="display: none;">
                <label>Reason*</label>
                <textarea class="form-control" id="txtAreaReason" name="txtAreaReason"></textarea>
                <span class="help-block" style="display:none">Please Fill Reason! (for rent/unavailable status)</span>
              </div>
            </div>
          </div>

          <hr>
          <div class="row divAsset" style="display:none;">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label>Primary Engineer</label>
                <select class="form-control select-primary-engineer" fdprocessedid="qzhe4c" name="selectEngAssign"><option></option></select>
              </div>
            </div>
            <div class="col-md-8 col-xs-12">
              <div class="form-group">
                <label style="color: grey;">Secondary Engineer</label>
                <select class="form-control select-secondary-engineer" fdprocessedid="qzhe4c" name="selectEngAssign" multiple="multiple"></select>
              </div>
            </div>
          </div>
          
          <div class="row divPeripheral" style="margin-bottom:50px;display:none;">
            <div class="col-lg-4 col-xs-12">
              <!--   <div class="form-group">
                <label>Category Peripheral</label>
                <select id="selectCatPeripheral" name="selectCatPeripheral" class="form-control" disabled>
                </select>
              </div> -->

              <div class="form-group">
                <label>Asset Owner</label>
                <select id="selectAssetOwnerPeripheral" name="selectAssetOwnerPeripheral" class="form-control">
                  <option></option>
                </select>
              </div>

              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Category</label>
                    <select id="selectCategoryPeripheral" name="selectCategoryPeripheral" class="form-control" disabled>
                      <option></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="selectStatusPeripheral" name="selectStatusPeripheral" class="form-control">
                      <option></option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Type Device</label>
                    <select id="selectTypeDevicePeripheral" name="selectTypeDevicePeripheral" class="form-control"><option></option></select>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Serial Number</label>
                    <input id="inputSerialNumberPeripheral" name="inputSerialNumberPeripheral" class="form-control">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Vendor</label>
                <select class="form-control" id="selectVendorPeripheral" name="selectVendorPeripheral"><option></option></select>
              </div>
            </div>
            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>Spesifikasi</label>
                <input class="form-control" id="inputSpesifikasiPeripheral" name="inputSpesifikasiPeripheral">
              </div>
              
              <div class="form-group">
                <label for="">Tanggal Pembelian</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input id="inputTglBeliPeripheral" name="inputTglBeliPeripheral" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="">Harga</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <strong>RP</strong>
                  </div>
                  <input id="inputHargaPeripheral" type="text" name="inputHargaPeripheral" class="form-control money">
                </div>
              </div>

              <div class="form-group">
                <label for="">Nilai Buku</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <strong>RP</strong>
                  </div>
                  <input id="inputNilaiBukuPeripheral" type="text" name="inputNilaiBukuPeripheral" class="form-control money">
                </div>
              </div>             
            </div>
            <div class="col-lg-4 col-xs-12">
              <!-- <div class="form-group">
                <label>Accessoris</label>
                <input class="form-control" id="inputAccessoris" name="inputAccessoris">
              </div> -->
              <div class="form-group">
                <label>RMA</label>
                <input id="inputRMAPeripheral" name="inputRMAPeripheral" class="form-control">
              </div>

              <div class="form-group">
                <label>Notes</label>
                <textarea class="form-control" id="txtAreaNotesPeripheral" name="txtAreaNotesPeripheral"></textarea>
              </div>
            </div>
          </div>
        </fieldset>

        <!-- <div style="position: absolute;bottom: 0;left: 0;padding-left: 15px;padding-bottom: 20px;display: none;" class="divPeripheral" id="divBtnAssign"> -->
        <div class="form-group" style="margin-top:50px">
          <div style="position: absolute;bottom: 0;left: 0;padding-left: 15px;padding-bottom: 20px;display: none;" id="divBtnAssign">
            <button class="btn btn-sm btn-primary" style="width:90px;" onclick="AssignPeripheral()" id="btnAssignPeripheral">Assign</button>
          </div>

          <div style="position: absolute;bottom: 0;right: 0;padding-right: 15px;padding-bottom: 20px;display: none;" id="divBtnUpdate">
            <button class="btn btn-sm btn-danger" style="width:90px;margin-right: 10px;" onclick="resetData()">Cancel</button>
            <button class="btn btn-sm btn-warning" style="width:90px;" id="updateAsset">Update Asset</button>
          </div>
        </div>
      </div>
    </div>

    <div class="box box-primary" id="boxDetailAsset" style="display:none;">
      <div class="box-header with-border">
        <h3 class="box-title"><strong>Detail Asset</strong></h3>
      </div>
      <div class="box-body">
        <fieldset class="fieldsetDivAssetDetail">
          <div class="row divAsset" style="margin-bottom:50px;display:none;">
            <div class="col-lg-6 col-xs-12">
              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Client</label>
                    <input type="text" name="inputClient" id="inputClient" class="form-control">
                  </div>
                </div>
                
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>ID Device Customer</label>
                    <input id="inputIdDeviceCustomer" name="inputIdDeviceCustomer" class="form-control">
                  </div>
                </div>

                <div class="form-group col-md-6 col-xs-12" id="picContainer"></div>
              </div>

              <div class="form-group">
                <label>PID</label>
                <select class="form-control" id="selectPID" name="selectPID"></select>
              </div>

              <div class="form-group">
                <label>City</label>
                <select class="form-control" id="selectCity" name="selectCity"></select>
              </div>

              <div class="form-group">
                <label>Location</label>
                <textarea class="form-control" id="txtAreaAddress" name="txtAreaAddress"></textarea>
              </div>

              <div class="form-group">
                <label>Detail Address</label>
                <input class="form-control" placeholder="Search Location..." type="text" id="txtAreaLocation" name="txtAreaLocation">
                <span class="help-block" style="display:none;">Please fill Detail Address!</span>
              </div>
              
              <div class="form-group">
                <div id="map" style="height: 200px;width: 100%;margin:auto;display: block;background-color: #000;"></div>
              </div>
                
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Latitude</label>
                    <input class="form-control" placeholder="" type="text" id="lat" name="lat">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Longitude</label>
                    <input class="form-control" placeholder="" type="text" id="lng" name="lng">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Service Point</label>
                <select class="form-control" placeholder="" type="text" id="service_point" name="service_point"><option></option></select>
              </div>

              <div class="form-group">
                <label>Distance</label>
                <div class="input-group">
                  <input class="form-control" placeholder="" type="text" id="distance" name="distance" readonly value="0">
                  <div class="input-group-addon" style="background-color:#eee">
                    <strong>KM</strong>
                  </div>
                </div>
              </div>

              <div id="DocNonInternalContainer">
                <div class="form-group">
                  <label>Bukti Asset</label>
                  <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                    <label for="inputBuktiAsset" style="margin-bottom:0px">
                      <span class="fa fa-cloud-upload" style="display:inline"></span>
                      <input autocomplete="off" style="display: inline;" type="file" name="inputBuktiAsset" class="files" id="inputBuktiAsset">
                    </label>
                  </div>
                  <span class="help-block" style="display:none;">Please fill Penawaran Harga!</span>
                  <span style="display:none;" id="span_link_drive_bukti_asset"><a id="link_bukti_asset" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
                </div>
              </div>
            </div>            

            <div class="col-lg-6 col-xs-12">
              <div class="row">
                <div class="col-md-4 col-xs-12">
                  <div class="form-group">
                    <label>IP Address</label>
                    <input id="inputIPAddress" name="inputIPAddress" class="form-control">
                  </div>
                </div>
                <div class="col-md-4 col-xs-12">
                  <div class="form-group">
                    <label>Server</label>
                    <input id="inputServer" name="inputServer" class="form-control">
                  </div>
                </div>
                <div class="col-md-4 col-xs-12">
                  <div class="form-group">
                    <label>Port</label>
                    <input id="inputPort" name="inputPort" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Status Customer</label>
                <select class="form-control" id="selectStatusCustomer" name="selectStatusCustomer"></select>
              </div>

              <div class="form-group" style="display:none;">
                <label>Accessoris</label>
                <input class="form-control" id="inputAccessoris" name="inputAccessoris">
              </div>

              <div class="form-group">
                <label>2nd Level Support</label>
                <select class="form-control" id="selectLevelSupport" name="selectLevelSupport"></select>
              </div>

              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Operating System</label>
                    <input id="inputOS" name="inputOS" class="form-control">
                  </div>
                </div>
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Version</label>
                    <input id="inputVersion" name="inputVersion" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Installed Date</label>
                <div class="input-group">
                  <input class="form-control" id="inputInstalledDate" name="inputInstalledDate" onchange="clearValidationOnChange(this)">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                <span class="text-red" style="display:none;">Please fill New Installed Date!</span>
              </div>

              <div class="form-group">
                <label>License</label>
                <input class="form-control" id="inputLicense" name="inputLicense">
              </div>

              <div class="form-group" id="licenseContainer"></div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">License Start*</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="inputLicenseStart" name="inputLicenseStart">
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
                      <input autocomplete="off" type="" class="form-control" id="inputLicenseEnd" name="inputLicenseEnd">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill License End!</span>
                  </div>
                </div>
              </div>

              <div id="DocInternalContainer" style="display:none;">
                <div class="form-group">
                  <label>Bukti Asset</label>
                  <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                    <label for="inputBuktiAssetInternal" style="margin-bottom:0px">
                      <span class="fa fa-cloud-upload" style="display:inline"></span>
                      <input autocomplete="off" style="display: inline;" type="file" name="inputBuktiAssetInternal" class="files" id="inputBuktiAssetInternal">
                    </label>
                  </div>
                  <span class="help-block" style="display:none;">Please fill Bukti Asset!</span>
                  <span style="display:none;" id="span_link_drive_bukti_asset_internal"><a id="link_bukti_asset_internal" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
                </div>

                <div class="form-group">
                  <label>Berita Acara</label>
                  <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                    <label for="inputBeritaAcara" style="margin-bottom:0px">
                      <span class="fa fa-cloud-upload" style="display:inline"></span>
                      <input autocomplete="off" style="display: inline;" type="file" name="inputBeritaAcara" class="filesBA" id="inputBeritaAcara" disabled>
                    </label>
                  </div>
                  <span class="help-block" style="display:none;">Please fill Berita Acara!</span>
                  <span style="display:none;" id="span_link_drive_berita_acara"><a id="link_berita_acara" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Maintenance Start*</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="inputMaintenanceStart" name="inputMaintenanceStart" placeholder="yyyy-mm-dd">
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
                      <input autocomplete="off" type="" class="form-control" id="inputMaintenanceEnd" name="inputMaintenanceEnd" placeholder="yyyy-mm-dd">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                    <span class="help-block" style="display:none;">Please fill Maintenance End!</span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">SLA Uptime</label>
                    <input type="text" class="form-control" id="inputSLAUptime" name="inputSLAUptime" readonly>
                    <span class="help-block" style="display:none;">Please fill License Start!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Total Ticket*</label>
                    <input autocomplete="off" type="" class="form-control" id="inputTotalTicket" name="inputTotalTicket" readonly>
                    <span class="help-block" style="display:none;">Please fill Total Ticket!</span>
                  </div>
                </div>
              </div>
    
              <strong style="font-size: 20px;">Related Asset</strong>
              <table class="table" id="tablePeripheral" style="border-collapse: collapse;">
              </table>
            </div>
          </div>
        </fieldset>

        <div style="position: absolute;bottom: 0;right: 0;padding-right: 15px;padding-bottom: 20px;display: none;" id="divBtnUpdateDetail">
          <button class="btn btn-sm btn-danger" style="width:90px;margin-right: 10px;" onclick="resetData()">Cancel</button>
          <button class="btn btn-sm btn-warning" style="width:90px;" id="updateAssetDetail">Update Detail</button>
        </div>
      </div>
    </div>

    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title"><strong>History Project ID</strong></h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table" id="tableHistoryPid" style="width: 100%">
          </table>
        </div>
      </div>
    </div>

    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title"><strong>Change Log</strong></h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table" id="tableChangeLog" style="width: 100%">
          </table>
        </div>
      </div>
    </div>

    <div class="box box-primary" style="display:none;" id="ticketHistoryContainer">
      <div class="box-header">
        <h3 class="box-title"><strong>Ticket History</strong></h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table" id="tableTicketHistory" style="width: 100%">
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="modalAssignPeripheral" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Assign</h4>
        </div>
        <div class="modal-body">
          <form method="POST">
            <div class="form-group">
              <label>Assign</label>
              <select class="form-control" id="selectAssign" name="selectAssign"><option></option></select>
            </div>
          </form>
          <div class="modal-footer">
            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
            <button class="btn btn-sm btn-primary" id="saveAssignPeripheral">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
@endsection
@section('script')
  <script async defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY_GLOBAL')}}&libraries=places&callback=initMap"></script>
  <script type="text/javascript">
    $("select").select2()
    preventBack()

    function preventBack() {
      window.onbeforeunload = function(e) {
        e.preventDefault();
        e.returnValue = true;
      };
    }

    function initMoney(){
      $('.money').mask('000.000.000.000', {reverse: true});
    }
    initMoney()

    function InitiateHistoryPid(type){
      var table = ''
      if (type == "asset") {
        columnAsset = [
          { 
            title:"Location",
            render: function (data, type, row, meta){
              return row.lokasi
            },
            width:'40%'
          },
          {
            title:"Customer",
            render: function (data, type, row, meta){
             return row.client
            }
          },
          {
            title:"PID",
            render: function (data, type, row, meta){
             return row.pid
            }
          },
          {
            title:"Period",
            render: function (data, type, row, meta){
             return row.periode
            }
          },
          {
            title:"Operator",
            render: function (data, type, row, meta){
             return row.operator
            }
          },
          {
            title:"Related Id Asset",
            render: function (data, type, row, meta){
             return row.related_id_asset
            }
          },
        ]
      }else if (type == "asset_internal") {
        columnAsset = [
          { 
            title:"PIC - Department",
            render: function (data, type, row, meta){
              return row.pic_name
            },
            width:'40%'
          },
          {
            title:"Periode Penggunaan Asset",
            render: function (data, type, row, meta){
             return row.periode_asset_internal
            }
          },
          {
            title:"Operator",
            render: function (data, type, row, meta){
             return row.operator
            }
          },
          {
            title:"BAST File",
            render: function (data, type, row, meta){
              if (Array.isArray(row.document_name)) {
                for (let i = 0; i < row.document_name.length; i++) {
                  const doc = row.document_name[i];
                  const docBAST = doc.docBAST;
                  const driveBAST = doc.driveBAST;
                  
                  if (docBAST && driveBAST && !(docBAST.includes("Pengembalian"))) {
                    return `<a href="${driveBAST}" target="_blank">
                              <i class="fa fa-link"></i> ${docBAST}
                            </a>`;
                  }
                }
              }
              return '-';
            }
          },
          {
            title:"BAST Pengembalian Asset",
            render: function (data, type, row, meta){
              if (Array.isArray(row.document_name)) {
                for (let i = 0; i < row.document_name.length; i++) {
                  const doc = row.document_name[i];
                  const docBAST = (doc.docBAST);
                  const driveBAST = doc.driveBAST;
                  
                  if (docBAST.includes("Pengembalian")) {
                    return `<a href="${driveBAST}" target="_blank"><i class="fa fa-link"></i> ${docBAST}</a>`;
                  }
                }
              }
              return '-';
            }
          }
        ]
      }

      if(!$.fn.DataTable.isDataTable('#tableHistoryPid')){
        table = $("#tableHistoryPid").DataTable({
          "aaSorting": [],
          "ajax":{
            "type":"GET",
            "url":"{{url('asset/getLogById')}}",
            "data":{
              id_asset:window.location.href.split("=")[1],
              type:type
            }
          },
          "columns": columnAsset
        })
      }else{
        table = $('#tableHistoryPid').DataTable().ajax.url("{{url('asset/getLogById')}}?id_asset="+window.location.href.split("=")[1]).load()
      } 

      // if (type == 'asset') {
      //   var column1 = table.column(5);
      //   column1.visible(!column1.visible());
      // }
    }

    function InitiateHistoryTicket(){
      if(!$.fn.DataTable.isDataTable('#tableTicketHistory')){
        $("#tableTicketHistory").DataTable({
          "aaSorting": [],
          "ajax":{
            "type":"GET",
            "url":"{{url('asset/getTicketId')}}",
            "data":{
              id_asset:window.location.href.split("=")[1]
            }
          },
          "columns": [
            { 
              title:"ID Ticket",
              render: function (data, type, row, meta){
                return row.id_ticket
              },
            },
            {
              title:"Problem",
              render: function (data, type, row, meta){
               return row.concatenate_problem_ticket
              }
            },
            {
              title:"PIC",
              render: function (data, type, row, meta){
               return row.pic
              }
            },
            {
              title:"Type Ticket",
              render: function (data, type, row, meta){
               return row.concatenate_type_ticket
              }
            },
            {
              title:"Open Ticket",
              render: function (data, type, row, meta){
               return row.first_activity_ticket.date
              }
            },
            {
              title:"Close Ticket",
              render: function (data, type, row, meta){
               return row.lastest_activity_ticket.date
              }
            },
          ],
        })
      }else{
        $('#tableTicketHistory').DataTable().ajax.url("{{url('asset/getTicketId')}}?id_asset="+window.location.href.split("=")[1]).load()
      } 
    }

    function InitiateChangeLog(){
      if(!$.fn.DataTable.isDataTable('#tableChangeLog')){
        $("#tableChangeLog").DataTable({
          "aaSorting": [],
          "ajax":{
            "type":"GET",
            "url":"{{url('asset/getChangeLog')}}",
            "data":{
              id_asset:window.location.href.split("=")[1]
            }
          },
          "columns": [
            {
              title:"Operator",
              render: function (data, type, row, meta){
               return row.operator
              }
            },
            {
              title:"Activity",
              render: function (data, type, row, meta){
               return row.activity
              }
            },
            {
              title:"Date Time",
              render: function (data, type, row, meta){
               return row.date_add
              }
            },
          ],
        })
      }else{
        $('#tableChangeLog').DataTable().ajax.url("{{url('asset/getChangeLog')}}?id_asset="+window.location.href.split("=")[1]).load()
      } 
    }
    
    function AssignPeripheral(){
      $("#modalAssignPeripheral").modal("show")

      // $.ajax({
      //   url:"{{url('/asset/getAssetToAssign')}}",
      //   type:"GET",
      //   success:function(results){
      //     console.log(results)
      //     $("#selectAssign").select2({
      //       data:results,
      //       placeholder:"Select ID Assign to Assign",
      //       dropdownParent:$("#modalAssignPeripheral")
      //     })
      //   }
      // })  
    }

    function showPeripheral(id_asset){
      $("#tablePeripheral").empty("")

      $.ajax({
        type:"GET",
        url:"{{url('asset/getPeripheral')}}",
        data:{
          id_asset:id_asset
        },
        success:function(result){
          var append = ""
          if (result.length > 0) {
            $.each(result,function(item,data){
              append = append +'<tr>'
                append = append +'<td style="border-top: none;padding-left: 0px;width: 90%;">'
                  append = append +'<div class="form-group">'
                    append = append +'<label>'+ data.id_asset +'</label>'
                    let text = data.text
                    if (data.text == null) {
                      text = "-"
                    }
                    append = append +'<input class="form-control" id="inputSerialNumber" name="inputSerialNumber" readonly value="'+ text +'">'
                  append = append +'</div>'
                append = append +'</td>'
                append = append +'<td style="border-top: none;">'
                  append = append +'<button class="btn btn-sm btn-danger" style="width: 35px;height: 33px;margin-top: 10px;float: right;" onclick="deletePeripheral(' + "'" + data.id + "'" +')"><i class="fa fa-trash"></i></button>'
                append = append +'</td>'
              append = append +'</tr>'
            })

            $("#tablePeripheral").append(append)
            $("#tablePeripheral").prev('strong').show()
          }else{
            $("#tablePeripheral").prev('strong').hide()
          }
        }
      })
    }
    
    //select2 btn assign id to assign
    $("#selectAssign").select2({
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

    function resetData(){
      Swal.fire({
        title: 'Are you sure?',
        text: "Cancel this process to update asset!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          InitiateDetailPage()
          InitiateHistoryPid()
        }
      })
    }

    function deletePeripheral(id_asset){
      Swal.fire({
        title: 'Are you sure?',
        text: "Delete Peripheral will set asset status to Available!",
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
            url:"{{url('asset/deleteAssignedAsset')}}",
            data:{
              _token:"{{csrf_token()}}",
              id_asset:id_asset,
            },
            success: function(result){
              Swal.fire({
                title: 'Delete Peripheral Asset Successsfully!',
                icon: 'success',
                confirmButtonText: 'Reload',
              }).then((result) => {
                InitiateDetailPage()
                InitiateHistoryPid()
              })
            }
          })
        }
      })
    }

    InitiateDetailPage()
    function InitiateDetailPage(){
      $.ajax({
        type:"GET",
        url:"{{url('asset/getDetailAsset')}}",
        data:{
          id_asset:window.location.href.split("=")[1]
        },
        success:function(result){
          // Change label for inputSerialNumber if its 
          if (result.category_code === 'VHC'){
            $('label[for="inputSerialNumber"]').text('Nomor Polisi');
          } else {
            $('label[for="inputSerialNumber"]').text('Serial Number');
          }
          
          if(result.pid === "INTERNAL"){
            $("#service_point").closest(".form-group").hide()
            $("#selectStatusCustomer").closest(".form-group").hide()
            // $("#selectLevelSupport").closest(".form-group").hide()
            $("#inputMaintenanceStart").closest(".form-group").hide()
            $("#inputMaintenanceEnd").closest(".form-group").hide()
            $("#inputIdDeviceCustomer").closest(".form-group").hide()
            $("#inputRMA").closest(".form-group").hide()
            $("#inputLicense").closest(".form-group").hide()
            $("#inputSLAUptime").closest(".form-group").hide()
            $("#inputTotalTicket").closest(".form-group").hide()
            $("#distance").closest(".form-group").hide()
            $("#DocNonInternalContainer").hide()
            //buat delete id yang duplicate
            $("#DocNonInternalContainer").find("input").attr("id","") 
            $("#picContainer").empty("")
            $("#prContainer").empty("")
            $("#licenseContainer").empty("")
            $("#DocInternalContainer").show()
            $("#tableHistoryPid").closest(".box-primary").find(".box-title").html("<strong>History (PIC - Department)</strong>")
            $("#inputLicenseStart").closest(".form-group").find("label").text("License Start/Garansi*") 
            $("#inputLicenseEnd").closest(".form-group").find("label").text("License End/Garansi*") 
            $(".divAsset:eq(1)").prev("hr").hide()
            $(".divAsset:eq(1)").hide()
            $("#btnAssignPeripheral").hide()
            let picContainer = $("#picContainer");
            let prContainer = $("#prContainer");
            let licenseContainer = $("#licenseContainer");

            InitiateHistoryPid('asset_internal')

            let label = $("<label>",{
              text: "Nama PIC - Department"
            });

            let input = $("<select>",{
              class: "form-control",
              name: "inputPIC",
              id: "inputPIC",
              onchange:"clearValidationOnChange(this)"
            });

            let span = $("<span>",{
              text: "Please fill Name PIC - Department!",
              style: "display:none",
              class:"help-block text-red"
            });

            picContainer.append(label,input,span);

            $("#inputPIC").select2({
              ajax: {
                  url: '{{url("asset/getEmployeeNames")}}',
                  processResults: function (data) {
                      return {
                          results: data
                      };
                  },
              },
              placeholder: 'Select PIC',
              allowClear:true
            }).on('select2:select', function(e) {
              console.log(e.params.data.id)
              if (e.params.data.id == null || e.params.data.id == '') {
                $("#inputInstalledDate").val("")
                $("#inputInstalledDate").closest(".input-group").next("span").hide()
              }else{
                $("#inputInstalledDate").val("")
                $("#inputInstalledDate").closest(".input-group").next("span").show()
              }
            }).on('select2:unselect', function (e) {
                $("#inputInstalledDate").val("")
                $("#inputInstalledDate").closest(".input-group").next("span").hide()
                $("#inputBeritaAcara").val("")
                $("#span_link_drive_berita_acara").hide()
            });


            var pic = $("#inputPIC");
            if (result.pic != null) {
              var option = new Option(result.text_name, result.pic, true, true);
              pic.append(option).trigger('change');
            }

            let labelPr = $("<label>",{
              text: "PR"
            });

            let inputPr = $("<select>",{
              class: "form-control",
              name: "inputPr",
              id: "inputPr",
            });

            prContainer.append(labelPr,inputPr);
            $("#inputPr").attr("disabled",false)

            $("#inputPr").select2({
              ajax: {
                  url: '{{url("asset/getPrByYear")}}',
                  processResults: function (data) {
                      return {
                          results: data
                      };
                  },
              },
              placeholder: 'Select PR'
            })

            var pr = $("#inputPr");
            var option = new Option(result.pr, result.pr, true, true);
            pr.append(option).trigger('change');

            $("#inputPr").on("change", function () {
                var selectedPr = $(this).val();

                if (selectedPr) {
                    $.ajax({
                        url: '{{url("asset/getDateByPr")}}',
                        type: 'GET',
                        data: { no_pr: selectedPr },
                        success: function (response) {
                            $("#inputTglBeli").val(response);
                        },
                        error: function () {
                            console.error('Failed to fetch the date for the selected PR.');
                        }
                    });
                } else {
                    $("#inputTglBeli").val('');
                }
            });

            let labelLicense = $("<label>",{
              text: "License/Garansi"
            });

            let selectLicense = $("<select>",{
              class: "form-control",
              name: "inputLicense",
              id: "inputLicense"
            }).change(function(argument) {
              licenseStart = $("#inputLicenseStart").val()
              $("#inputLicenseEnd").val(moment(licenseStart).add(parseInt($(this).val()), 'years').format('YYYY-MM-DD'))
            })

            const years = Array.from({ length: 10 }, (_, i) => i + 1);
        
            // Map the years to <option> elements and append them to the select element
            const options = years.map(year => {
                const option = document.createElement('option');
                option.value = year;
                option.text = `${year} Year`;
                return option;
            });

            // Append all options to the dropdown
            optionSelect = document.createElement('option');
            optionSelect.value = "";
            optionSelect.text = "Select License/Garansi";

            options.unshift(optionSelect)

            options.forEach(option => selectLicense.append(option));

            licenseContainer.append(labelLicense,selectLicense);
            $('#inputLicense option[value="'+ result.license +'"]').prop('selected', true).trigger('change');

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
            }).val(result.second_level_support).trigger("change")
            $(".divAsset:eq(0)").show()
            $(".divAsset:eq(1)").hide()
            $(".divAsset:eq(2)").show()
          }else{
            $(".divAsset").show()
            $("#DocInternalContainer").find("input").attr("id","") 

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
              createTag: function(params) {
                // Capitalize the first letter of the new tag
                const capitalizedTag = capitalizeFirstLetter(params.term);
                return {
                    id: capitalizedTag,
                    text: capitalizedTag
                };
              }
            })

            if (result.second_level_support != null) {
              var levelSupportSelect = $("#selectLevelSupport");
              var optionLevelSupport = new Option(result.second_level_support, result.second_level_support, true, true);
              levelSupportSelect.append(optionLevelSupport).trigger('change');
            }

            if (result.status == 'Available') {
              $("#btnAssignPeripheral").show()
            }else{
              $("#btnAssignPeripheral").hide()
            }
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

          $('input[class="filesBA"]').change(function(){
            var f=this.files[0]
            var filePath = f;
         
            // Allowing file type
            var allowedExtensions =
            /(\.pdf)$/i;

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
          
          var accesable = @json($feature_item);
          accesable.forEach(function(item,index){
            $("#" + item).show()
          })

          if (!accesable.includes('divBtnUpdate')) {
            $("select").prop('disabled', true)
            $(".fieldsetDivAssetDetail").prop('disabled',true)
          }

          $("#titleDetailIdAsset").text(result.id_asset)
          if(result.status == "Available"){
            $("#txtAreaReason").closest(".form-group").hide()
          }else if(result.status == "Installed"){
            $("#txtAreaReason").closest(".form-group").hide()
          }else if(result.status == "Rent" || result.status == "Unavailable"){
            $("#txtAreaReason").closest(".form-group").show()
            $("#txtAreaReason").val(result.reason_status)
          }else{
            $("#txtAreaReason").closest(".form-group").hide()
          }

          if (result.category_code == 'COM') {
            $("#inputAccessoris").closest('.form-group').show()
            $("#inputAccessoris").val(result.accessoris)
          }else{
            $("#inputAccessoris").closest('.form-group').hide()
            $("#inputAccessoris").val(result.accessoris)
          }
          $("#inputSLAUptime").val(result.slaUptime)
          $("#inputTotalTicket").val(result.countTicket)
          $(".divPeripheral").hide()
          showPeripheral(result.id)
          $("#tableTicketHistory").closest(".box-primary").show()
          InitiateHistoryTicket()
          InitiateHistoryPid('asset')
          $("#boxDetailAsset").show()

          if ($("#selectCity").find('option').length == 0) {
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
                }).val(result.kota).trigger("change")
              },
              error: function(xhr, status, error) {
                  // Handle errors
                  console.error(status, error);
              }
            });
          }

          $("#service_point").select2({
            ajax: {
              url: '{{url("asset/getServicePoint")}}',
              processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              },
            },
            placeholder:"Select Service Point",
          })

          // Fetch the preselected item, and add to the control
          if (result.service_point != null) {
            var service_point = $("#service_point");
            var option = new Option(result.service_point, result.service_point, true, true);
            service_point.append(option).trigger('change');
          }

          if (result.distance != null) {
            $("#distance").val(Math.round(result.distance))
          }else{
            $("#distance").val(0)
          }
          
          $("#updateAsset").click(function(){
            $("#updateAsset").attr("onclick",UpdateAsset(result.id,"asset"))
          })

          $("#saveAssignPeripheral").click(function(){
            $("#saveAssignPeripheral").attr("onclick",SaveAssignPeripheral(result.id))
          })

          $("#updateAssetDetail").click(function(){
            if($("#inputPIC").closest(".form-group").find(".help-block").is(":visible") == true){
              Swal.fire({
                icon:'warning',
                text:'Please Select Name PIC - Department!'
              })
            }else if ($("#inputInstalledDate").closest(".input-group").next("span").is(":visible") == true) {
              Swal.fire({
                icon:'warning',
                text:'Please fill New Installed Date!'
              })
            }else{
              $("#updateAssetDetail").attr("onclick",UpdateAsset(result.id,"detail"))
            }
          })

          $(".select-primary-engineer").select2({
            ajax: {
              url: '{{url("asset/getEngineer")}}',
              processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              },
            },
            placeholder:"Select Engineer",
          }).on("change", function () {
            var selectedValues = [];
            $('.select-secondary-engineer').not(this).each(function() {
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
                    Engineer has been assigned as secondary engineer!
                  `,
                })
                alertShown = true;
              }
            });
          })

          $(".select-secondary-engineer").select2({
            ajax: {
              url: '{{url("asset/getEngineer")}}',
              processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              },
            },
            placeholder:"Select Engineer",
            multiple:true,
          }).on("change", function () {
            var selectedValues = [];
            $('.select-primary-engineer').not(this).each(function() {
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
                    Engineer has been assigned as primary engineer!
                  `,
                })
                alertShown = true;
              }
            });
          })

          // Fetch the preselected item, and add to the control
          if (result.engineers.Primary != null) {
            $(".select-primary-engineer").empty()
            var engAssignSelect = $(".select-primary-engineer");
            var option = new Option(result.engineers.Primary[0].engineer_atm, result.engineers.Primary[0].engineer_atm, true, true);
            engAssignSelect.append(option).trigger('change');
          }

          if (result.engineers.Secondary != null) {
            $(".select-secondary-engineer").empty()
            $.each(result.engineers.Secondary,function(idx,values){
              var engAssignSelect = $(".select-secondary-engineer");
              var option = new Option(values.engineer_atm, values.engineer_atm, true, true);
              engAssignSelect.append(option).trigger('change');
            })
          }

          current_date = moment().format('YYYY-MM-DD')
          if (result.maintenance_end >= current_date) {
            $("#selectPID").prop("disabled",true)
          }else{
            $("#selectPID").prop("disabled",false)
          }   

          $(".divBtnUpdate").show()

          $("select[name='selectAssetOwner'],select[name='selectAssetOwnerPeripheral']").select2({
            ajax : {
              url: '{{url("asset/getAssetOwner")}}',
              processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              },
            },
            placeholder:"Select Asset Owner"
          })

          if (result.asset_owner != null) {
            var vendorSelect = $("#selectAssetOwner,#selectAssetOwnerPeripheral");
            var option = new Option(result.asset_owner, result.asset_owner, true, true);
            vendorSelect.append(option).trigger('change');
          }

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
          })

          // Fetch the preselected item, and add to the control
          if (result.category_code != null) {
            var categorySelect = $("#selectCategory");
            var option = new Option(result.category_text, result.category_code, true, true);
            categorySelect.append(option).trigger('change');

            if ("{{App\RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->where('roles.name','Synergy System & Services Manager')->exists()}}") {
              if (result.pid != null) {
                $("#selectEngAssign").closest(".form-group").show() 
              }else{
                $("#selectEngAssign").closest(".form-group").hide()
              }
            }else{
              $("#selectEngAssign").closest(".form-group").show() 
              // if (result.category_code == "CRM" || result.category_code == "ATM") {
              //   $("#selectEngAssign").closest(".form-group").show() 
              // }else{
              //   $("#selectEngAssign").closest(".form-group").hide() 
              // }
            }
          }
          
          let oldStatus = result.status;

          $("select[name='selectStatus']").select2({
            placeholder:"Select Status",
            data:[
              {id:"Installed",text:"Installed"},
              {id:"Available",text:"Available"},
              {id:"Rent",text:"Rent"},
              {id:"Unavailable",text:"Unavailable"},
            ]
          }).on('select2:select', function (e) { 
            var id = e.params.data.id;
            if (id == "Unavailable" || id == "Rent") {
              $("#txtAreaReason").closest(".form-group").show()
              if (id == 'Unavailable') {
                $("#txtAreaReason").attr("placeholder","RMA, Rusak")
              }else{
                $("#txtAreaReason").attr("placeholder","POC, UAT, Stagging, Lab")
              }
              if ($("#txtAreaReason").val() == "") {
                $("#txtAreaReason").closest(".form-group").addClass("has-error")
              }
            }else if (id == "Available") {
              $("#txtAreaReason").closest(".form-group").hide()
              $("#inputPIC").empty("")
              $("#inputPIC").next("span").next(".help-block").hide()
              $("#inputBeritaAcara").val("")
              $("#span_link_drive_berita_acara").hide()
            }else if (id == "Installed") {
              $("#txtAreaReason").closest(".form-group").hide()
              $("#inputPIC").next("span").next(".help-block").show()
            }else{
              $("#inputPIC").next("span").next(".help-block").hide()
              $("#txtAreaReason").closest(".form-group").hide()
            }
          
            if (oldStatus !== 'Available' && id === 'Available') {
              Swal.fire({
                title: 'Update Asset to Available?',
                text: "Provide notes here",
                input: 'textarea',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                inputValidator: (value) => {
                  if (!value) {
                    return 'Notes are required!';
                  }
                }
              }).then((result) => {
                if (result.isConfirmed && result.value) {
                  const processedNotes = result.value.replace("<br>", "\n");
                  $('#txtAreaNotes').val(processedNotes);
                  
                  Swal.fire({
                    title: 'Status Updated Successfully!',
                    icon: 'success',
                    confirmButtonText: 'Reload'
                  }).then(() => {
                  });
                } 
                else if (result.isDismissed) {
                  $("select[name='selectStatus']").val(oldStatus).trigger('change');
                }
              });
            }
          });

          $("select[name='selectStatus']").val(result.status).trigger("change")

          $("select[name='selectStatusPeripheral']").select2({
            placeholder:"Select Status",
            data:[
              {id:"Installed",text:"Installed",disabled: true},
              {id:"Available",text:"Available"},
            ]
          }).val(result.status).trigger("change")

          $.ajax({
            "type":"GET",
            "url":"{{url('asset/getPid')}}",
            success:function(response){
              $("#selectPID").select2({
                data:response,
                placeholder:"Select PID"
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
            }
          })

          if (result.pid != "" && result.pid != null) {
            // $('#selectPID').select2('data', {id: result.pid, text: result.pid})
            var selectPID = $("#selectPID");
            var optionPID = new Option(result.pid, result.pid, true, true);
            selectPID.append(optionPID).trigger('change');
          }

          $("#selectStatusCustomer").select2({
            placeholder:"Select Status Customer",
            tags:true,
            data:[
              {id:"Beli",text:"Beli"},
              {id:"Sewa",text:"Sewa"},
            ]
          }).val(result.status_cust).trigger("change") 

          $("#selectVendor, #selectVendorPeripheral").select2({
              ajax: {
                  url: '{{url("asset/getVendor")}}',
                  data: function (params) {
                    return {
                      q:params.term
                    };
                  },
                  processResults: function(data) {
                      // Transforms the top-level key of the response object from 'items' to 'results'
                      return {
                          results: data
                      };
                  },
              },
              placeholder: "Select Vendor",
              tags: true,
              createTag: function(params) {
                // Capitalize the first letter of the new tag
                const capitalizedTag = capitalizeFirstLetter(params.term);
                return {
                    id: capitalizedTag,
                    text: capitalizedTag
                };
              }
          });

          // Fetch the preselected item, and add to the control
          if (result.vendor != null) {
            var vendorSelect = $("#selectVendor,#selectVendorPeripheral");
            var option = new Option(result.vendor, result.vendor, true, true);
            vendorSelect.append(option).trigger('change');
          }

          $("#selectCatPeripheral").select2({
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

          var categoryPeripheralSelect = $("#selectCatPeripheral");
          var optionCatPeripheral = new Option(result.category_peripheral, result.category_peripheral, true, true);
          categoryPeripheralSelect.append(optionCatPeripheral).trigger('change');

          $("#selectTypeDevice,#selectTypeDevicePeripheral").select2({
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

          if (result.type_device != null) {
            var typeDeviceSelect = $("#selectTypeDevice,#selectTypeDevicePeripheral");
            var optionTypeDevice = new Option(result.type_device, result.type_device, true, true);
            typeDeviceSelect.append(optionTypeDevice).trigger('change');
          }
          $("#inputClient").val(result.client).prop("disabled",true)
          $("input[name='inputSerialNumber']").val(result.serial_number)
          if(result.spesifikasi){
            $("textarea[name='inputSpesifikasi']").text(result.spesifikasi.replaceAll("<br>","\n"))
          } else {
            $("textarea[name='inputSpesifikasi']").text(result.spesifikasi)
          }
          $("input[name='inputRMA']").val(result.rma)
          $("input[name='inputVendorPeripheral']").val(result.vendor)
          $("input[name='inputTypeDevicePeripheral']").val(result.type_device)
          $("input[name='inputSerialNumberPeripheral']").val(result.serial_number)
          $("input[name='inputSpesifikasiPeripheral']").val(result.spesifikasi)
          $("input[name='inputRMAPeripheral']").val(result.rma)
          $("textarea[name='txtAreaNotes']").val(result.notes || "")
          $("textarea[name='txtAreaNotesPeripheral']").val(result.notes)
          $("#inputIdDeviceCustomer").val(result.id_device_customer)
          $("#txtAreaAddress").val(result.alamat_lokasi)
          $("#txtAreaLocation").val(result.detail_lokasi)
          setLatLngLoc(result.detail_lokasi)
          $("#inputIPAddress").val(result.ip_address)
          $("#inputServer").val(result.server)
          $("#inputPort").val(result.port)
          $("#inputOS").val(result.operating_system);
          $("#inputVersion").val(result.version_os)
          initMoney()
          $("input[name='inputHarga'],input[name='inputHargaPeripheral']").val(result.harga_beli)
          $("input[name='inputHarga'],input[name='inputHargaPeripheral']").unmask().mask('000.000.000.000', {reverse: true})
          $("input[name='inputNilaiBuku'],input[name='inputNilaiBukuPeripheral']").val(result.nilai_buku)
          $("input[name='inputNilaiBuku'],input[name='inputNilaiBukuPeripheral']").unmask().mask('000.000.000.000', {reverse: true})
          $("input[name='inputTglBeli'],input[name='inputTglBeliPeripheral']").datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          })
          $('#inputTglBeli,#inputTglBeliPeripheral').datepicker("setDate",result.tanggal_pembelian)

          $('#inputInstalledDate').datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          })
          $('#inputInstalledDate').datepicker("setDate",result.installed_date)
          $('#inputLicenseStart').datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          })
          $('#inputLicenseStart').datepicker("setDate",result.license_start_date)
          $('#inputLicenseEnd').datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          })
          $('#inputLicenseEnd').datepicker("setDate",result.license_end_date)
          $('#inputMaintenanceStart').datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          })
          $('#inputMaintenanceStart').datepicker("setDate",result.maintenance_start)
          $('#inputMaintenanceEnd').datepicker({
            placeholder:"yyyy-mm-dd",
            autoclose: true,
            format: 'yyyy-mm-dd',
          }).change(function(){
            if ($('#inputMaintenanceStart').val() != "") {
               if ($('#inputMaintenanceStart').val() >= this.value) {
                Swal.fire({
                  title: "<strong>Oopzz!</strong>",
                  icon: "info",
                  html: `
                    Maintenance date must be greater than maintenance start!
                  `,
                })
                $(this).val("")
              }
            }
          })
          $('#inputMaintenanceEnd').datepicker("setDate",result.maintenance_end)
          $("#inputLicense").val(result.license != 'null' ? result.license : '-')
          $("#selectStatus").val()
          // $("#inputAccessoris").val()
          $("#inputSLAUptime").val()
          $("#inputTotalTicket").val()

          const fileAsset = document.querySelector('input[type="file"][name="inputBuktiAsset"]');

          if (result.document_name_asset != '' && result.document_name_asset != null) {
            const myFileBuktiAsset = new File(['{{asset("/")}}"'+ result.document_location_asset +'"'], '/'+ result.document_location_asset,{
              type: 'text/plain',
              lastModified: new Date(),
            });

            // Now let's create a DataTransfer to get a FileList
            const dataTransferBuktiAsset = new DataTransfer();
            dataTransferBuktiAsset.items.add(myFileBuktiAsset);
            fileAsset.files = dataTransferBuktiAsset.files;

            if (result.link_drive_asset != '' && result.link_drive_asset != null) {
              $("#span_link_drive_bukti_asset").show()
              $("#link_bukti_asset").attr("href",result.link_drive_asset)
            }
          }

          const fileAssetInternal = document.querySelector('input[type="file"][name="inputBuktiAssetInternal"]');

          if (result.document_name_asset != '' && result.document_name_asset != null) {
            const myFileBuktiAssetInternal = new File(['{{asset("/")}}"'+ result.document_location_asset +'"'], '/'+ result.document_location_asset,{
              type: 'text/plain',
              lastModified: new Date(),
            });

            // Now let's create a DataTransfer to get a FileList
            const dataTransferBuktiAssetInternal = new DataTransfer();
            dataTransferBuktiAssetInternal.items.add(myFileBuktiAssetInternal);
            fileAssetInternal.files = dataTransferBuktiAssetInternal.files;

            if (result.link_drive_asset != '' && result.link_drive_asset != null) {
              $("#span_link_drive_bukti_asset_internal").show()
              $("#link_bukti_asset_internal").attr("href",result.link_drive_asset)
            }
          }

          const fileAssetBA = document.querySelector('input[type="file"][name="inputBeritaAcara"]');

          if (result.document_name_BA != '' && result.document_name_BA != null) {
            const myFileBeritaAcara = new File(['{{asset("/")}}"'+ result.document_location_BA +'"'], '/'+ result.document_location_BA,{
              type: 'text/plain',
              lastModified: new Date(),
            });

            // Now let's create a DataTransfer to get a FileList
            const dataTransferBeritaAcara = new DataTransfer();
            dataTransferBeritaAcara.items.add(myFileBeritaAcara);
            fileAssetBA.files = dataTransferBeritaAcara.files;

            if (result.link_drive_BA != '' && result.link_drive_BA != null) {
              $("#span_link_drive_berita_acara").show()
              $("#link_berita_acara").attr("href",result.link_drive_BA)
            }
          }
          InitiateChangeLog()
        }
      })
    }

    function SaveAssignPeripheral(id_asset){
      Swal.fire({
        title: 'Are you sure?',
        // text: "Assign this Peripheral!",
        text: "Assign this asset!",
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
            url:"{{url('asset/storeAssign')}}",
            data:{
              _token:"{{csrf_token()}}",
              id_asset:id_asset,
              id_asset_induk:$("#selectAssign").val()
            },
            success: function(result){
              Swal.fire({
                title: 'Assign Successsfully!',
                icon: 'success',
                confirmButtonText: 'Reload',
              }).then((result) => {
                $("#modalAssignPeripheral").modal("hide")
                InitiateDetailPage()
                InitiateHistoryPid()
                InitiateChangeLog()
              })
            }
          })          
        }
      })
    }
    
    function UpdateAsset(id_asset,type){
      let rma = "", notes = "", assetOwner = "", vendor = "", typeDevice = "", serialNumber = "", spesifikasi = "", tanggalBeli = "", hargaBeli = "", nilaiBuku = ""
        if (type == "peripheral") {
          rma = $("input[name='inputRMAPeripheral']").val()
          notes = $("textarea[name='txtAreaNotesPeripheral']").val()
          assetOwner = $("select[name='selectAssetOwnerPeripheral']").val()
          vendor = $("select[name='selectVendorPeripheral']").val()
          typeDevice = $("select[name='selectTypeDevicePeripheral']").val()
          serialNumber = $("input[name='inputSerialNumberPeripheral']").val()
          spesifikasi = $("input[name='inputSpesifikasiPeripheral']").val()
          status = $("select[name='selectStatusPeripheral']").val()
          tanggalBeli = $("input[name='inputTglBeliPeripheral']").val()
          hargaBeli = $("input[name='inputHargaPeripheral']").val()
          nilaiBuku = $("input[name='inputNilaiBukuPeripheral']").val()

          url = "{{url('asset/updateAsset')}}"
        }else{
          rma = $("input[name='inputRMA']").val()
          notes = $("textarea[name='txtAreaNotes']").val()
          assetOwner = $("select[name='selectAssetOwner']").val()
          vendor = $("select[name='selectVendor']").val()
          typeDevice = $("select[name='selectTypeDevice']").val()
          serialNumber = $("input[name='inputSerialNumber']").val()
          spesifikasi = $("textarea[name='inputSpesifikasi']").val()
          status = $("select[name='selectStatus']").val()
          tanggalBeli = $("input[name='inputTglBeli']").val()
          hargaBeli = $("input[name='inputHarga']").val()
          nilaiBuku = $("input[name='inputNilaiBuku']").val()
          inputPr = $("select[name='inputPr']").val()
          if ($("textarea[name='txtAreaReason']").is(":visible") == true) {
            reason = $("textarea[name='txtAreaReason']").val()
          }else{
            reason = ''
          }

          var data = '', url = '', alert = '', alertSuccess = ''

          if ($("#txtAreaReason").is(":visible") == true) {
            if ($("#txtAreaReason").val() == "") {
              $("#txtAreaReason").closest(".form-group").addClass("has-error")
              $("#txtAreaReason").next(".help-block").show()
            }else{
              $("#txtAreaReason").closest(".form-group").removeClass("has-error")
              $("#txtAreaReason").next(".help-block").hide()

              url = "{{url('asset/updateAsset')}}"

              var formData = new FormData
              var arrEng = []

              arrEng.push({"name":$(".select-primary-engineer").val(),"roles":"Primary"})

              $(".select-secondary-engineer").val().map(function(elem,idx) {
                arrEng.push({"name":elem,"roles":"Secondary"})
              })

              formData.append('_token',"{{csrf_token()}}")
              formData.append('id_asset',id_asset)
              formData.append('status',status)
              formData.append('vendor',vendor)
              formData.append('typeDevice',typeDevice)
              formData.append('serialNumber',serialNumber)
              formData.append('spesifikasi',spesifikasi)
              formData.append('rma',rma)
              formData.append('notes',notes)
              formData.append('engineer',JSON.stringify(arrEng))
              formData.append('assetOwner',assetOwner)
              formData.append('tanggalBeli',tanggalBeli)
              formData.append('hargaBeli',hargaBeli)
              formData.append('nilaiBuku',nilaiBuku)
              formData.append('reason',reason)
              formData.append('inputPr',inputPr)
              alert = {
                title: 'Are you sure?',
                text: "Update Asset",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
              }

              alertSuccess = {
                title: 'Update Asset Successsfully!',
                icon: 'success',  
                confirmButtonText: 'Reload',
              }

              Swal.fire(alert).then((result) => {
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
                    url:url,
                    data:formData,
                    processData: false,
                    contentType: false,
                    success: function(result){
                      Swal.fire(alertSuccess).then((result) => {
                        window.onbeforeunload = null;
                        window.location.reload();
                        // InitiateDetailPage()
                        // InitiateHistoryPid()
                        // InitiateChangeLog()
                      })
                    }
                  })
                }
              })
            }
          }else{
            var formData = new FormData

            if (type == 'asset') {
              url = "{{url('asset/updateAsset')}}"

              var arrEng = []

              arrEng.push({"name":$(".select-primary-engineer").val(),"roles":"Primary"})

              $(".select-secondary-engineer").val().map(function(elem,idx) {
                arrEng.push({"name":elem,"roles":"Secondary"})
              })

              formData.append('_token',"{{csrf_token()}}")
              formData.append('id_asset',id_asset)
              formData.append('status',status)
              formData.append('vendor',vendor)
              formData.append('typeDevice',typeDevice)
              formData.append('serialNumber',serialNumber)
              formData.append('spesifikasi',spesifikasi)
              formData.append('rma',rma)
              formData.append('notes',notes)
              formData.append('engineer',JSON.stringify(arrEng))
              formData.append('assetOwner',assetOwner)
              formData.append('tanggalBeli',tanggalBeli)
              formData.append('hargaBeli',hargaBeli)
              formData.append('nilaiBuku',nilaiBuku)
              formData.append('reason',reason)
              formData.append('inputPr',inputPr)

              alert = {
                title: 'Are you sure?',
                text: "Update Asset",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
              }
            
              alertSuccess = {
                title: 'Update Asset Successsfully!',
                icon: 'success',  
                confirmButtonText: 'Reload',
              }
              
            }else if (type == 'detail') {
              url = "{{url('asset/updateDetailAsset')}}"

              formData.append('_token',"{{csrf_token()}}")
              formData.append('id_asset',id_asset)
              formData.append('idDeviceCustomer',$("#inputIdDeviceCustomer").val())
              formData.append('client',$("#inputClient").val())
              formData.append('pid',$("#selectPID").val() == null?'':$("#selectPID").val())
              formData.append('kota',$("#selectCity").val() == null?'':$("#selectCity").val())
              formData.append('alamatLokasi',$("#txtAreaAddress").val())
              formData.append('detailLokasi',$("#txtAreaLocation").val())
              formData.append('latitude',$("#lat").val())
              formData.append('longitude',$("#lng").val())
              formData.append('ipAddress',$("#inputIPAddress").val())
              formData.append('ipServer',$("#inputServer").val())
              formData.append('port',$("#inputPort").val())
              formData.append('statusCust',$("#selectStatusCustomer").val() == null?'':$("#selectStatusCustomer").val())
              formData.append('secondLevelSupport',$("#selectLevelSupport").val())
              formData.append('operatingSystem',$("#inputOS").val())
              formData.append('versionOs',$("#inputVersion").val())
              formData.append('installedDate',$("#inputInstalledDate").val())
              formData.append('license',$("#inputLicense").val())
              formData.append('licenseStartDate',$("#inputLicenseStart").val())
              formData.append('licenseEndDate',$("#inputLicenseEnd").val())
              formData.append('maintenanceStart',$("#inputMaintenanceStart").val())
              formData.append('maintenanceEnd',$("#inputMaintenanceEnd").val())
              formData.append('servicePoint',$("#service_point").val())
              formData.append('inputPic',$("#inputPIC").val() == null?'':$("#inputPIC").val()) //samain buat status yg updateAsset
              formData.append('accessoris',$("#inputAccessoris").val())

              if ($('#inputBuktiAsset').is(":visible")) {
                formData.append('inputDoc',$('#inputBuktiAsset').prop('files')[0])
              }else{
                formData.append('inputDoc',$('#inputBuktiAssetInternal').prop('files')[0])
              }

              if ($('#inputBeritaAcara').is(":visible")) {
                formData.append('inputDocBA',$('#inputBeritaAcara').prop('files')[0])
              }

              alert = {
                title: 'Are you sure?',
                text: "Update Detail Asset",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
              }

              alertSuccess = {
                title: 'Update Detail Asset Successsfully!',
                icon: 'success',  
                confirmButtonText: 'Reload',
              }
            }

            Swal.fire(alert).then((result) => {
              if (result.value) {
                if (result.isConfirmed && typeof result.value === 'string' && status === "Available"){
                  const processedNotes = result.value.replace("<br>", "\n");
                  $('#txtAreaNotes').val(processedNotes);
                  formData.set('notes', processedNotes);
                }
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
                  url:url,
                  data:formData,
                  processData: false,
                  contentType: false,
                  success: function(result){
                    Swal.fire(alertSuccess).then((result) => {
                      window.onbeforeunload = null;
                      window.location.reload();
                        
                        // InitiateDetailPage()
                        // InitiateHistoryPid()
                        // InitiateChangeLog()
                    })
                  }
                })
              }
            })
          }        
        }
      }

      var map, marker, autocomplete;
      var lat = '',lang = ''
      function initMap(lat='',lang='',inputLat=false,inputLang=false){
        if (lat == '') {
          lat = -6.2297419
          inputLat = false
        }else{
          lat = lat
          inputLat = true
        }

        if (lang == '') {
          lang = 106.759478
          inputLang = false
        }else{
          lang = lang
          inputLang = true
        }

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

        map.addListener('click', function(result) {
          marker.setVisible(false);
          marker.setPosition(result.latLng);
          marker.setVisible(true);
          $("#lat").val(result.latLng.lat());
          $("#lng").val(result.latLng.lng());
        });

        marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29),
          draggable: true,
          animation: google.maps.Animation.BOUNCE
        });

        if (inputLat == true && inputLang == true) {
          $.ajax({
            type:"GET",
            url:"https://maps.googleapis.com/maps/api/geocode/json?latlng="+ lat +","+ lang +"&key={{env('GOOGLE_API_KEY_GLOBAL')}}",
            success: function(resultGoogle){

              $("#txtAreaLocation").val(resultGoogle.results[0].formatted_address)
              map.setCenter({lat: lat, lng: lang});
              marker.setPosition({lat:lat , lng: lang});
              marker.setVisible(true);
              map.setZoom(17);
            }
          })
        }

        autocomplete = new google.maps.places.Autocomplete((document.getElementById('txtAreaLocation')));

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
          $("#lat").val(place.geometry.location.lat());
          $("#lng").val(place.geometry.location.lng());
        });

        google.maps.event.addListener(marker, 'dragend', function (evt) {
          $("#lat").val(evt.latLng.lat());
          $("#lng").val(evt.latLng.lng());
        });      
      }

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

      function setLatLngLoc(location){
        if (location != '' && location != null) {
          const geocoder = new google.maps.Geocoder();
          // Make Geocoding request
          geocoder.geocode({ address: location }, function(results, status) {
            if (status === 'OK') {
              const location = results[0].geometry.location;
              const latitude = location.lat();
              const longitude = location.lng();

              $("#lat").val(latitude)
              $("#lng").val(longitude)

              $.ajax({
                type:"GET",
                url:"https://maps.googleapis.com/maps/api/geocode/json?latlng="+ parseFloat($("#lat").val()) +","+ parseFloat($("#lng").val()) +"&key={{env('GOOGLE_API_KEY_GLOBAL')}}",
                success: function(resultGoogle){
                  map.setCenter({lat: parseFloat($("#lat").val()), lng: parseFloat($("#lng").val())});
                  marker.setPosition({lat:parseFloat($("#lat").val()) , lng: parseFloat($("#lng").val())});
                  marker.setVisible(true);
                  map.setZoom(17);
                }
              })
              // initMap(parseFloat($("#lat").val()),parseFloat($("#lng").val()))
            } else {
              console.error("Geocode was not successful for the following reason:", status);
            }
          });
        }
      }

      function clearValidationOnChange(data) {
        if ($(data).val() !== "") {
          $(data).closest(".input-group").next("span").hide();
        }
      }

      function autoResize(textarea) {
        textarea.style.height = 'auto'; // Reset height to auto
        textarea.style.height = textarea.scrollHeight + 'px'; // Set to scroll height
      }
  </script>
@endsection