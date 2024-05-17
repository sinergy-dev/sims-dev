@extends('template.main')
@section('tittle')
  Detail Asset
@endsection
@section('head_css')
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <style type="text/css">
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
    <a href="{{url('asset/index')}}" class="btn btn-sm btn-danger" style="display:inline;"><i class="fa fa-arrow-left"></i> Back</a>
    <h1 style="display:inline;vertical-align: middle;">
      ID Asset
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{url('asset/index')}}"><i class="fa fa-table"></i> Asset</a></li>
    <li class="active">Detail Asset</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <div class="row" style="margin-bottom:50px">
          <div class="col-lg-4 col-xs-12">
            <div class="form-group">
              <label>Asset Owner</label>
              <select id="selectAssetOwner" name="selectAssetOwner" class="form-control">
              </select>
            </div>

            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Category</label>
                  <select id="selectCategory" name="selectCategory" class="form-control"></select>
                </div>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Status</label>
                  <select id="selectStatus" name="selectStatus" class="form-control"></select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Vendor</label>
              <input class="form-control" id="inputVendor" name="inputVendor">
            </div>

            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Type Device</label>
                  <input id="inputTypeDevice" name="inputTypeDevice" class="form-control">
                </div>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Serial Number</label>
                  <input id="inputSerialNumber" name="inputSerialNumber" class="form-control">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Spesifikasi</label>
              <input class="form-control" id="inputSpesifikasi" name="inputSpesifikasi">
            </div>

            <div class="form-group">
              <label>Accessoris</label>
              <input class="form-control" id="inputAccessoris" name="inputAccessoris">
            </div>

            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Peripheral</label>
                  <input id="inputTypeDevice" name="inputTypeDevice" class="form-control">
                </div>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>RMA</label>
                  <input id="inputSerialNumber" name="inputSerialNumber" class="form-control">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 20px">
              <label>Notes</label>
              <textarea class="form-control" id="txtAreaNotes" name="txtAreaNotes"></textarea>
            </div>

          </div>
          <div class="col-lg-4 col-xs-12">
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Client</label>
                  <select id="selectClient" name="selectClient" class="form-control"></select>
                </div>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="form-group">
                  <label>ID Device Customer</label>
                  <input id="inputIdDeviceCustomer" name="inputIdDeviceCustomer" class="form-control">
                </div>
              </div>
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
              <label>Address Location</label>
              <textarea class="form-control" id="txtAreaAddress" name="txtAreaAddress"></textarea>
            </div>

            <div class="form-group">
              <label>Detail Location</label>
              <textarea class="form-control" id="txtAreaLocation" name="txtAreaLocation"></textarea>
            </div>

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

            <div class="form-group">
              <label>2nd Level Support</label>
              <select class="form-control" id="selectLevelSupport" name="selectLevelSupport"></select>
            </div>

          </div>
          <div class="col-lg-4 col-xs-12">
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
              <input class="form-control" id="inputInstalledDate" name="inputInstalledDate">
            </div>

            <div class="form-group">
              <label>License</label>
              <input class="form-control" id="inputLicense" name="inputLicense">
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">License Start*</label>
                  <div class="input-group">
                    <input type="text" class="form-control" onkeyup="fillInput('license_start')" id="inputLicenseStart" name="inputLicenseStart">
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
                    <input type="text" class="form-control" onkeyup="fillInput('maintenance_start')" id="inputMaintenanceStart" name="inputMaintenanceStart">
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

            <div class="row" style="display:none;" id="divSLA">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">SLA Uptime</label>
                  <input type="text" class="form-control" onkeyup="fillInput('SLA')" id="inputSLAUptime" name="inputSLAUptime">
                  <span class="help-block" style="display:none;">Please fill License Start!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Total Ticket*</label>
                  <input autocomplete="off" type="" class="form-control" id="inputTotalTicket" name="inputTotalTicket" onkeyup="fillInput('total_ticket')">
                  <span class="help-block" style="display:none;">Please fill Total Ticket!</span>
                </div>
              </div>
            </div>

            <div id="divPeripheral" style="display: none;">
              <strong style="font-size: 20px;">Peripheral</strong>
              <table class="table" style="border-collapse: collapse;">
                <tr>
                  <td style="border-top: none;padding-left: 0px;width: 100%;">
                    <div class="form-group">
                      <label>(Peripheral Name)</label>
                      <input class="form-control" id="inputSerialNumber" name="inputSerialNumber">
                    </div>
                  </td>
                  <td style="border-top: none;">
                    <button class="btn btn-sm btn-danger" style="width: 35px;height: 33px;margin-top: 25px;float: right;"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td style="border-top: none;padding-left: 0px;width: 100%;">
                    <div class="form-group">
                      <label>(Peripheral Name)</label>
                      <input class="form-control" id="inputSerialNumber" name="inputSerialNumber">
                    </div>
                  </td>
                  <td style="border-top: none;">
                    <button class="btn btn-sm btn-danger" style="width: 35px;height: 33px;margin-top: 25px;float: right;"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div style="position: absolute;bottom: 0;left: 0;padding-left: 15px;padding-bottom: 20px;">
          <button class="btn btn-sm btn-primary" style="width:90px;" onclick="AssignPeripheral()">Assign</button>
        </div>

        <div style="position: absolute;bottom: 0;right: 0;padding-right: 15px;padding-bottom: 20px;">
          <button class="btn btn-sm btn-danger" style="width:90px;margin-right: 10px;">Cancel</button>
          <button class="btn btn-sm btn-warning" style="width:90px;">Update Asset</button>
        </div>
      </div>
    </div>

    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Change Log</h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <th>Location</th>
              <th>Customer</th>
              <th>PID</th>
              <th>Period</th>
            </thead>
            <tbody>
              <tr>
                <td>Jakarta</td>
                <td>BPJS</td>
                <td>BPJS/VII/2024</td>
                <td>II</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="modalAssignPeripheral" role="dialog">
    <div class="modal-dialog modal-sm">
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
              <select class="form-control"></select>
            </div>
          </form>
          <div class="modal-footer">
            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
            <button class="btn btn-sm btn-primary">Save</button>
          </div>
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
    $("select").select2()

    function AssignPeripheral(){
      $("#modalAssignPeripheral").modal("show")
    }

    if (window.location.href.split("=")[1] == 'asset') {
      $("#divSLA").show()
      $("#divPeripheral").show()

    }else if (window.location.href.split("=")[1] == 'peripheral') {
      $("#divSLA").hide()
      $("#divPeripheral").hide()
    }
  </script>
@endsection