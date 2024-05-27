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
        <fieldset id="fieldsetDivAssetDetail">
          <div class="row divAsset" style="margin-bottom:50px;display:none;" >
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
                    <select id="selectCategory" name="selectCategory" class="form-control" readonly></select>
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
                <select id="selectVendor" name="selectVendor" class="form-control"><option></option></select>
              </div>

              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <div class="form-group">
                    <label>Type Device</label>
                      <select id="selectTypeDevice" name="selectTypeDevice" class="form-control"><option></option></select>
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

             <!--  <div class="form-group">
                <label>Accessoris</label>
                <input class="form-control" id="inputAccessoris" name="inputAccessoris">
              </div> -->

              <div class="form-group">
                <label>RMA</label>
                <input id="inputRMA" name="inputRMA" class="form-control">
              </div>

              <div class="form-group">
                <label>Notes</label>
                <textarea class="form-control" id="txtAreaNotes" name="txtAreaNotes"></textarea>
              </div>

              <div class="form-group">
                <h3><strong>Engineer Assigned</strong></h3>
                <select class="form-control" fdprocessedid="qzhe4c" id="selectEngAssign" name="selectEngAssign"><option></option></select>
              </div>
            </div>
            <div class="col-lg-4 col-xs-12">
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
                <div class="input-group">
                  <input class="form-control" id="inputInstalledDate" name="inputInstalledDate" onkeyup="fillInput('installed_date')">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
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

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">SLA Uptime</label>
                    <input type="text" class="form-control" onkeyup="fillInput('SLA')" id="inputSLAUptime" name="inputSLAUptime" readonly>
                    <span class="help-block" style="display:none;">Please fill License Start!</span>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Total Ticket*</label>
                    <input autocomplete="off" type="" class="form-control" id="inputTotalTicket" name="inputTotalTicket" onkeyup="fillInput('total_ticket')" readonly>
                    <span class="help-block" style="display:none;">Please fill Total Ticket!</span>
                  </div>
                </div>
              </div>

    
              <strong style="font-size: 20px;">Peripheral</strong>
              <table class="table" id="tb_peripheral" style="border-collapse: collapse;">
                <!-- <tr>
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
                </tr> -->
              </table>
            </div>
          </div>

          <div class="row divPeripheral" style="margin-bottom:50px;display:none;" >
            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>Category Peripheral</label>
                <select id="selectCatPeripheral" name="selectCatPeripheral" class="form-control" disabled>
                </select>
              </div>

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
                    <select id="selectCategoryPeripheral" name="selectCategoryPeripheral" class="form-control" readonly>
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
            </div>
            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                <label>Vendor</label>
                <select class="form-control" id="selectVendorPeripheral" name="selectVendorPeripheral"><option></option></select>
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
                <label>Spesifikasi</label>
                <input class="form-control" id="inputSpesifikasiPeripheral" name="inputSpesifikasiPeripheral">
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

              <div class="form-group" style="padding-bottom: 20px">
                <label>Notes</label>
                <textarea class="form-control" id="txtAreaNotesPeripheral" name="txtAreaNotesPeripheral"></textarea>
              </div>
            </div>
          </div>
        </fieldset>

        <div style="position: absolute;bottom: 0;left: 0;padding-left: 15px;padding-bottom: 20px;display: none;" class="divPeripheral" id="divBtnAssign">
          <button class="btn btn-sm btn-primary" style="width:90px;" onclick="AssignPeripheral()" id="btnAssignPeripheral">Assign</button>
        </div>

        <div style="position: absolute;bottom: 0;right: 0;padding-right: 15px;padding-bottom: 20px;display: none;" id="divBtnUpdate">
          <button class="btn btn-sm btn-danger" style="width:90px;margin-right: 10px;" onclick="resetData()">Cancel</button>
          <button class="btn btn-sm btn-warning" style="width:90px;" id="updateAsset">Update Asset</button>
        </div>
      </div>
    </div>

    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Change Log</h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table" id="tb_logAsset" style="width: 100%">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    $("select").select2()
    
    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

    if (!accesable.includes('divBtnUpdate')) {
      $("select").prop('disabled', true)
      $("#fieldsetDivAssetDetail").prop('disabled',true)
    }

    InitiateLogAsset()
    function InitiateLogAsset(){
      if(!$.fn.DataTable.isDataTable('#tb_logAsset')){
        $("#tb_logAsset").DataTable({
          "aaSorting": [],
          "ajax":{
            "type":"GET",
            "url":"{{url('asset/getLogById')}}",
            "data":{
              id_asset:window.location.href.split("=")[1]
            }
          },
          "columns": [
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
          ],
        })
      }else{
        $('#tb_logAsset').DataTable().ajax.url("{{url('asset/getLogById')}}").load()
      } 
    }
    
    function AssignPeripheral(){
      $("#modalAssignPeripheral").modal("show")
    }

    function showPeripheral(id_asset){
      $("#tb_peripheral").empty("")

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
                    append = append +'<label>'+ data.category_peripheral +'</label>'
                    let text = data.text
                    if (data.text == null) {
                      text = "-"
                    }
                    append = append +'<input class="form-control" id="inputSerialNumber" name="inputSerialNumber" readonly value="'+ text +'">'
                  append = append +'</div>'
                append = append +'</td>'
                append = append +'<td style="border-top: none;">'
                  append = append +'<button class="btn btn-sm btn-danger" style="width: 35px;height: 33px;margin-top: 10px;float: right;" onclick="deletePeripheral(' + "'" + data.id_asset + "'" +')"><i class="fa fa-trash"></i></button>'
                append = append +'</td>'
              append = append +'</tr>'
            })

            $("#tb_peripheral").append(append)
            $("#tb_peripheral").prev('strong').show()
          }else{
            $("#tb_peripheral").prev('strong').hide()
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
          InitiateLogAsset()
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
                type: 'success',
                icon: 'success',
                confirmButtonText: 'Reload',
              }).then((result) => {
                InitiateDetailPage()
                InitiateLogAsset()
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
          $("#titleDetailIdAsset").text(result.id_asset)
          if (result.type == 'asset') {
            $("#inputSLAUptime").val(result.slaUptime)
            $("#inputTotalTicket").val(result.countTicket)
            $(".divAsset").show()
            $(".divPeripheral").hide()
            showPeripheral(result.id_asset)

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
            
            $("#updateAsset").click(function(){
              $("#updateAsset").attr("onclick",UpdateAsset(result.id_asset,"asset"))
            })

            $("#selectEngAssign").select2({
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
            })

            // Fetch the preselected item, and add to the control
            if (result.engineer_atm != null) {
              var engAssignSelect = $("#selectEngAssign");
              var option = new Option(result.engineer_atm, result.engineer_atm, true, true);
              engAssignSelect.append(option).trigger('change');
            }            
          }else if (result.type == 'peripheral') {
            $(".divAsset").hide()
            $(".divPeripheral").show()
            $("#saveAssignPeripheral").click(function(){
              $("#saveAssignPeripheral").attr("onclick",SaveAssignPeripheral(result.id_asset))
            })

            $("#updateAsset").click(function(){
              $("#updateAsset").attr("onclick",UpdateAsset(result.id_asset,"peripheral"))
            })
          }
          
          if(result.status == "Available"){
            $(".divPeripheral").find("#btnAssignPeripheral").show()
          }else if(result.status == "Installed"){
            $(".divPeripheral").find("#btnAssignPeripheral").hide()
          }

          $(".divBtnUpdate").show()
          //select2 detail asset
          $("select[name='selectAssetOwner'],select[name='selectAssetOwnerPeripheral']").select2({
            placeholder:"Select Asset Owner",
            data:[
              {id:"SIP",text:"SIP"},
              {id:"Distributor",text:"Distributor"},
              {id:"Principal",text:"Principal"},
            ]
          }).val(result.asset_owner).trigger("change")

          $("select[name='selectCategory'],select[name='selectCategoryPeripheral']").select2({
            placeholder:"Select Category",
            data:[
              {id:"ATM",text:"ATM"},
              {id:"Network",text:"Network"},
              {id:"CRM",text:"CRM"},
              {id:"Security",text:"Security"},
            ]
          }).val(result.category).trigger("change").attr("disabled",true)

          $("select[name='selectStatus'],select[name='selectStatusPeripheral']").select2({
            placeholder:"Select Status",
            data:[
              {id:"Installed",text:"Installed",disabled: true},
              {id:"Available",text:"Available"},
              {id:"RMA",text:"RMA"},
            ]
          }).val(result.status).trigger("change")

          $('#selectPID').select2('data', {id: result.pid, text: result.pid});

          $.ajax({
            "type":"GET",
            "url":"{{url('asset/getPid')}}",
            success:function(response){
              $("#selectPID").select2({
                data:response,
                placeholder:"Select PID"
              }).val(result.pid).trigger("change").on('select2:select', function (e) {
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

          $("#selectStatusCustomer").select2({
            placeholder:"Select Status Customer",
            tags:true,
            data:[
              {id:"Beli",text:"Beli"},
              {id:"Sewa",text:"Sewa"},
            ]
          }).val(result.status_cust).trigger("change") 

          $("#selectVendor,#selectVendorPeripheral").select2({
            ajax:{
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

          // Fetch the preselected item, and add to the control
          var vendorSelect = $("#selectVendor,#selectVendorPeripheral");
          var option = new Option(result.vendor, result.vendor, true, true);
          vendorSelect.append(option).trigger('change');

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
                console.log(data)
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              },
            },
            placeholder:"Select Type Device",
            tags:true
          })

          var typeDeviceSelect = $("#selectTypeDevice,#selectTypeDevicePeripheral");
          var optionTypeDevice = new Option(result.type_device, result.type_device, true, true);
          typeDeviceSelect.append(optionTypeDevice).trigger('change');

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

          var levelSupportSelect = $("#selectLevelSupport");
          var optionLevelSupport = new Option(result.second_level_support, result.second_level_support, true, true);
          levelSupportSelect.append(optionLevelSupport).trigger('change');

          $("#inputClient").val(result.client).prop("disabled",true)
          $("input[name='inputSerialNumber']").val(result.serial_number)
          $("input[name='inputSpesifikasi']").val(result.spesifikasi)
          $("input[name='inputRMA']").val(result.rma)
          $("input[name='inputVendorPeripheral']").val(result.vendor)
          $("input[name='inputTypeDevicePeripheral']").val(result.type_device)
          $("input[name='inputSerialNumberPeripheral']").val(result.serial_number)
          $("input[name='inputSpesifikasiPeripheral']").val(result.spesifikasi)
          $("input[name='inputRMAPeripheral']").val(result.rma)
          $("textarea[name='txtAreaNotes']").val(result.notes)
          $("textarea[name='txtAreaNotesPeripheral']").val(result.notes)
          $("input[name='inputIdDeviceCustomer']").val(result.id_device_customer)
          $("#txtAreaAddress").val(result.alamat_lokasi)
          $("#txtAreaLocation").val(result.detail_lokasi)
          $("#inputIPAddress").val(result.ip_address)
          $("#inputServer").val(result.server)
          $("#inputPort").val(result.port)
          $("#inputOS").val(result.operating_system)
          $("#inputVersion").val(result.version_os)
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
          })
          $('#inputMaintenanceEnd').datepicker("setDate",result.maintenance_end)
          $("#inputLicense").val(result.license)
          $("#selectStatus").val()
          // $("#inputAccessoris").val()
          $("#inputSLAUptime").val()
          $("#inputTotalTicket").val()
        }
      })
    }

    function SaveAssignPeripheral(id_asset){
      Swal.fire({
        title: 'Are you sure?',
        text: "Assign this Peripheral!",
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
                type: 'success',
                icon: 'success',
                confirmButtonText: 'Reload',
              }).then((result) => {
                $("#modalAssignPeripheral").modal("hide")
                InitiateDetailPage()
                InitiateLogAsset()
              })
            }
          })          
        }
      })
    }

    function UpdateAsset(id_asset,type){
      let rma = "", notes = "", assetOwner = "", vendor = "", typeDevice = "", serialNumber = "", spesifikasi = ""
      if (type == "peripheral") {
        rma = $("input[name='inputRMAPeripheral']").val()
        notes = $("textarea[name='txtAreaNotesPeripheral']").val()
        assetOwner = $("select[name='selectAssetOwnerPeripheral']").val()
        vendor = $("select[name='selectVendorPeripheral']").val()
        typeDevice = $("select[name='selectTypeDevicePeripheral']").val()
        serialNumber = $("input[name='inputSerialNumberPeripheral']").val()
        spesifikasi = $("input[name='inputSpesifikasiPeripheral']").val()
        status = $("select[name='selectStatusPeripheral']").val()
      }else{
        rma = $("input[name='inputRMA']").val()
        notes = $("textarea[name='txtAreaNotes']").val()
        assetOwner = $("select[name='selectAssetOwner']").val()
        vendor = $("select[name='selectVendor']").val()
        typeDevice = $("select[name='selectTypeDevice']").val()
        serialNumber = $("input[name='inputSerialNumber']").val()
        spesifikasi = $("input[name='inputSpesifikasi']").val()
        status = $("select[name='selectStatus']").val()
      }

      Swal.fire({
        title: 'Are you sure?',
        text: "Update Asset",
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
            url:"{{url('asset/updateAsset')}}",
            data:{
              _token:"{{csrf_token()}}",
              id_asset:id_asset,
              idDeviceCustomer:$("#inputIdDeviceCustomer").val(),
              client:$("#inputClient").val(),
              pid:$("#selectPID").val(),
              kota:$("#selectCity").val(),
              alamatLokasi:$("#txtAreaAddress").val(),
              detailLokasi:$("#txtAreaLocation").val(),
              ipAddress:$("#inputIPAddress").val(),
              ipServer:$("#inputServer").val(),
              port:$("#inputPort").val(),
              statusCust:$("#selectStatusCustomer").val(),
              secondLevelSupport:$("#selectLevelSupport").val(),
              operatingSystem:$("#inputOS").val(),
              versionOs:$("#inputVersion").val(),
              installedDate:$("#inputInstalledDate").val(),
              license:$("#inputLicense").val(),
              licenseStartDate:$("#inputLicenseStart").val(),
              licenseEndDate:$("#inputLicenseEnd").val(),
              maintenanceStart:$("#inputMaintenanceStart").val(),
              maintenanceEnd:$("#inputMaintenanceEnd").val(),
              status:status,
              vendor:vendor,
              typeDevice:typeDevice,
              serialNumber:serialNumber,
              spesifikasi:spesifikasi,
              rma:rma,
              notes:notes,
              categoryPeripheral:$("#selectPeripheral").val(),
              typeAsset:$("#selectAsset").val(),
              assetOwner:assetOwner,
              assignTo:$("#selectAssigntoPeripheral").val(),
              engineer:$("#selectEngAssign").val()
            },
            success: function(result){
              Swal.fire({
                title: 'Update Asset Successsfully!',
                type: 'success',
                icon: 'success',  
                confirmButtonText: 'Reload',
              }).then((result) => {
                InitiateDetailPage()
                InitiateLogAsset()
              })
            }
          })
        }
      })
    }
  </script>
@endsection