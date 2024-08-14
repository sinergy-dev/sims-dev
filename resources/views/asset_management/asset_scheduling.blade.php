@extends('template.main')
@section('tittle')
  Asset Scheduling
@endsection
@section('head_css')
<link rel="preload" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<style type="text/css">
  .dataTables_filter {display: none;}

  .dataTables_length {
    display: none;
  }
</style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Asset Scheduling
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{url('asset/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Asset Scheduling</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12 col-xs-12" id="box-table-asset">
        <div class="box box-primary">
          <div class="box-header">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <div class="pull-left">
                  <button class="btn btn-sm bg-purple" onclick="btnAddAssetScheduling()"><i class="fa fa-plus"></i> Asset Scheduling</button>
                </div>
                <div class="pull-right" style="display: flex;">
                  <div class="input-group" style="margin-right:10px">
                    <input id="searchBar" type="text" class="form-control" placeholder="Search Anything..." onkeyup="searchBarEntries('table-asset-scheduling',this.value)">
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
              </div>
            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table" id="table-asset-scheduling" style="width:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>ID Asset</th>
                    <th>Project ID</th>
                    <th>Maintenance Start</th>
                    <th>Maintenance End</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="modal-add-asset-schedule">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Asset Scheduling</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="row listAddAssetSchedule">
              <div class="divPid" style="display:none;">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Project ID Before*</label>
                    <select class="form-control selectPidBefore" style="width:100%!important" name="selectPidBefore"><option></option></select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Project ID After*</label>
                    <select class="form-control selectPidAfter" style="width:100%!important" name="selectPidAfter"><option></option></select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Periode*</label>
                    <select class="form-control selectPeriode" style="width:100%!important" name="selectPeriode"><option></option></select>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <label>Action</label><br>
                    <button class="btn btn-flat btn-danger deleteRowAsset" style="width:40px" disabled><i class="fa fa-trash"></i></button>
                  </div>
                </div>
              </div>
              <div class="divAsset" style="display:none;">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>ID Asset*</label>
                    <select class="form-control selectIdAsset" style="width:100%!important" name="selectIdAsset"><option></option></select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Project ID*</label>
                    <select class="form-control selectPid" style="width:100%!important" name="selectPid"><option></option></select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Maintenance Start*</label>
                    <input class="form-control inputMaintenanceStart" style="width:100%!important" name="inputMaintenanceStart" placeholder="dd/mm/yyyy">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Maintenance End*</label>
                    <input class="form-control inputMaintenanceEnd" style="width:100%!important" name="inputMaintenanceEnd" placeholder="dd/mm/yyyy">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <label>Action</label><br>
                    <button class="btn btn-flat btn-danger deleteRowAsset" style="width:40px" disabled><i class="fa fa-trash"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="form-group">
            <button class="btn btn-md bg-purple" style="margin:0 auto;display: block;" onclick="addRowAddSchedule()"><i class="fa fa-plus" style="margin-right:5px"></i> Asset</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" id="saveSchedule">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-schedulingBy">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Asset Scheduling</h4>
        </div>
        <div class="modal-body">
          <form role="form">
            <div class="form-group">
              <label>Scheduling By</label>
              <select id="schedulingBy" class="form-control" style="width:100%important!" placeholder="Select Option"><option></option></select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-flat btn-primary" onclick="schedulingBy($('#schedulingBy').val())">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
<script type="text/javascript">
  const dataSet = [
    {
      'id_asset':'PSIP-ATM-0918-000001',
      'pid':'106/BRKS/SIP/XI/2023',
      'maintenance_start':'2023-09-12',
      'maintenance_end':'2024-09-11',
      'status':'Pending',
      'get_history':[{
        'pid':'035/BSBB/SIP/V/2023', 
        'maintenance_start':'2023-09-12', 
        'maintenance_end':'2024-09-11',
        'status':'Scheduled'
      }]
    },
    {
      'id_asset':'PSIP-ATM-0918-000002',
      'pid':'106/BRKS/SIP/XI/2023',
      'maintenance_start':'2023-09-12',
      'maintenance_end':'2024-09-11',
      'status':'Pending',
      'get_history':[{
        'pid':'015/BSBB/SIP/III/2023', 
        'maintenance_start':'2023-09-12', 
        'maintenance_end':'2024-09-11',
        'status':'Scheduled'
      }]
    }
  ];

  var table = $("#table-asset-scheduling").DataTable({
    "ajax":{
      "type":"GET",
      "url":"{{url('asset/getDataScheduling')}}"
    },
    columns: [
      {
        className: 'dt-control',
        orderable: false,
        data: null,
        defaultContent: '',
      },
      { 
        data:"id_asset"
      },
      { 
        data:"pid" 
      },
      { 
        data:"maintenance_start"
      },
      { 
        data:"maintenance_end"
      },
      {
        render: function ( data, type, row ) {
          return "<span class='label label-warning'>"+ row.status +"</span>"
        },
      },
      {
        render: function ( data, type, row){
          return "<button onclick='deleteScheduling("+ row.id +")' class='btn btn-sm btn-danger' style='width:35px;height:30px'><i class='fa fa-trash'></></button>"
        }
      }
    ],
    "aaSorting": [],
  })

  $('#table-asset-scheduling tbody').on('click', 'td.dt-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(format(row.data())).show();
        tr.addClass('shown');
    }
  });

  function format(d) {
        // `d` is the original data object for the row
    var append = ""
    append = append +'<table class="table table-bordered table-striped" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;text-align:center">' 
    append = append +'<tr>' 
    append = append +  '<th style="text-align:center">Project ID</th>' 
    append = append +  '<th style="text-align:center">Maintenance Start</th>' 
    append = append +  '<th style="text-align:center">Maintenance End</th>'
    append = append +  '<th style="text-align:center">Status</th>' 
    d.get_history.forEach((item) => {
    //You can perform your desired function out here
      append = append + '<tr>' 
        append = append +   '<td>'+ item.pid +'</td>' 
        append = append +   '<td>'+ item.maintenance_start +'</td>' 
        append = append +   '<td>'+ item.maintenance_end +'</td>'
        append = append +   '<td>'+ "<span class='label label-success'>"+ item.status +"</span>"+'</td>'
      append = append + '</tr>'
    })
    append = append +'</table>' 
    return append;
  }

  function searchBarEntries(id_table,value){
    table.search(value).draw();
    // $('#'+id_table).DataTable().ajax.url("{{url('asset/getSearchData')}}?search="+$('#' + id_search_bar).val()).load();
  }

  function changeNumberEntries(number){
    $("#btnShowEntryAsset").html('Show ' + number + ' <span class="fa fa-caret-down"></span>')
    $("#table-asset-scheduling").DataTable().page.len( number ).draw();
  }

  function addRowAddSchedule(){
    var cloneRow = $(".listAddAssetSchedule:last").clone()
    cloneRow.find(".deleteRowAsset").removeAttr("disabled").end()
    cloneRow.children("select")
        .select2("destroy")
        .val("")
        .end()

    $(".listAddAssetSchedule").last().after(cloneRow)

    $(".deleteRowAsset").click(function(){
      $(this).closest(".listAddAssetSchedule").remove()
    })

    if ($(".divPid").is(":visible")) {
      $(".listAddAssetSchedule:last").find(".selectPidBefore").next("span").find("span span").text("")
      $(".listAddAssetSchedule:last").find(".selectPidAfter").next("span").find("span span").text("")
      $(".listAddAssetSchedule:last").find(".selectPeriode").next("span").find("span span").text("")
      settingPidBefore()
      settingPidAfter()
      settingPeriode()
    }else{
      settingIdAsset("add",null,"modal-add-asset-schedule")
      settingInputDate()
      $(".listAddAssetSchedule:last").find(".selectPid").next("span").find("span span").text("")
      cloneRow.find(".inputMaintenanceStart").val("").end()
      cloneRow.find(".inputMaintenanceEnd").val("").end()
    }    
  }

  function settingIdAsset(status,name,id_modal){
    $(".selectIdAsset").select2({
      ajax: {
        url: '{{url("asset/getIdAssetScheduling")}}',
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select ID Asset',
      dropdownParent: $("#modal-add-asset-schedule")
    }).on("change", function () {
     var selectedValues = [];
      $('.selectIdAsset').not(this).each(function() {
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
              ID asset ${text} cannot duplicate scheduling!
            `,
          })
          alertShown = true;
        }else{
          settingPid(this)
        }
      });
    })

    $(".selectIdAsset").next().next().remove()    
  }

  function settingPid(param){
    var id_asset = $(param).val()
    $(param).closest(".col-md-3").next(".col-md-4").find(".selectPid").select2({
      ajax: {
        url: '{{url("asset/getPidScheduling")}}',
        data: function (params) {
          var query = {
            q: params.term,
            id_asset:id_asset 
          }

          // Query parameters will be ?search=[term]&type=public
          return query;
        },
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select Project Id',
      dropdownParent: $("#modal-add-asset-schedule")
    })

    $(".selectPid").next().next().remove()
  }

  function settingPidBefore(){
    $(".selectPidBefore").select2({
      ajax: {
        url: '{{url("/asset/getPidAsset")}}',
        data: function (params) {
          var query = {
            q: params.term,
          }

          // Query parameters will be ?search=[term]&type=public
          return query;
        },
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select Project Id',
      dropdownParent: $("#modal-add-asset-schedule")
    }).on("change", function () {
        var selectedValues = [];
        $('.selectPidBefore').not(this).each(function() {
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
                'Project Id cannot duplicate assign!'
              `,
            })
            alertShown = true;
          }else{
            settingPidAfter(this)
          }
        });
    })

    $(".selectPidBefore").next().next().remove()
  }

  function settingPidAfter(param){
    var pid = $(param).val()

    $(param).closest(".col-md-4").next(".col-md-4").find(".selectPidAfter").select2({
      ajax: {
        url: '{{url("asset/getPidScheduling")}}',
        data: function (params) {
          var query = {
            q: params.term,
            pid:pid 
          }

          // Query parameters will be ?search=[term]&type=public
          return query;
        },
        processResults: function (data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data
          };
        },
      },
      placeholder: 'Select Project Id',
      dropdownParent: $("#modal-add-asset-schedule")
    }).on("change", function () {
      var selectedValues = [];
      $('.selectPidAfter').not(this).each(function() {
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
              'Project Id cannot duplicate assign!'
            `,
          })
          alertShown = true;
        }
      });
    })

    $(".selectPidAfter").next().next().remove()
  }

  function settingPeriode(){
    $(".selectPeriode").select2({
      placeholder:"Select Periode",
      data:[
        {id:"1",text:"1 Bulan"},
        {id:"2",text:"2 Bulan"},
        {id:"3",text:"3 Bulan"},
        {id:"6",text:"6 Bulan"},
        {id:"12",text:"1 Tahun"},
        {id:"24",text:"2 Tahun"},
        {id:"36",text:"3 Tahun"},
        {id:"48",text:"4 Tahun"},
        {id:"60",text:"5 Tahun"},
      ],
      dropdownParent:$("#modal-add-asset-schedule")
    })

    $(".selectPeriode").next().next().remove()
  }

  function settingInputDate(){
    $('.inputMaintenanceStart').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).change(function(){
      if ($(this).closest(".col-md-2").next(".col-md-2").find(".inputMaintenanceEnd").val() != "") {
        var end_date = $(this).closest(".col-md-2").next(".col-md-2").find(".inputMaintenanceEnd").val()
        if (moment((this.value), "DD/MM/YYYY").format("YYYY-MM-DD") >= moment((end_date), "DD/MM/YYYY").format("YYYY-MM-DD")) {
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

    $(".inputMaintenanceStart").next().next().remove()

    $('.inputMaintenanceEnd').datepicker({
      placeholder:"dd/mm/yyyy",
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).change(function(){
      if ($(this).closest(".col-md-2").prev(".col-md-2").find(".inputMaintenanceStart").val() != "") {
        var start_date = $(this).closest(".col-md-2").prev(".col-md-2").find(".inputMaintenanceStart").val()
        if (moment((start_date), "DD/MM/YYYY").format("YYYY-MM-DD") >= moment((this.value), "DD/MM/YYYY").format("YYYY-MM-DD")) {
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

    $(".inputMaintenanceEnd").next().next().remove() 
  }

  function saveSchedule(type){
    if (type == 'pid') {
      var inputs = document.querySelectorAll('.listAddAssetSchedule .divPid .form-control');
    }else{
      var inputs = document.querySelectorAll('.listAddAssetSchedule .divAsset .form-control');
    }
    var arrListAsset = [],id_asset = [], pidAsset = [], start_date = [], end_date = [];
    var pidBefore = [], pidAfter = [], period = "";
    // Iterate over each input element

    var isEmptyField = true, InputLengthEmpty = 0,inputLength = inputs.length

    if (type == 'asset') {
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
        // Push the value of each input to the arrListIdAsset array
        if(input.name == 'selectIdAsset'){
            id_asset.push(input.value);
        }

        if(input.name == 'selectPid'){
            pid.push($(input).val());
        }

        if(input.name == 'inputMaintenanceStart'){
            start_date.push($(input).val());
        }

        if(input.name == 'inputMaintenanceEnd'){
            end_date.push($(input).val());
        }
        
      });

      for (var i = 0; i < id_asset.length; i++) {
        // Construct object with elements from both arrays
        var combinedObject = {
            id_asset: id_asset[i],
            pid: pid[i],
            date_start: moment((start_date[i]), "DD/MM/YYYY").format("YYYY-MM-DD"),
            date_end: moment((end_date[i]), "DD/MM/YYYY").format("YYYY-MM-DD")
        };
        // Push the combined object into the resulting array
        arrListAsset.push(combinedObject);
      }
    }else{
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
        // Push the value of each input to the arrListIdAsset array
        if(input.name == 'selectPidBefore'){
            pidBefore.push(input.value);
        }

        if(input.name == 'selectPidAfter'){
            pidAfter.push(input.value);
        }

        if(input.name == 'selectPeriode'){
            periode = input.value;
        }        
      });

      for (var i = 0; i < pidBefore.length; i++) {
        // Construct object with elements from both arrays
        var combinedObject = {
            pid_before: pidBefore[i],
            pid_after: pidAfter[i],
            periode: periode[i]
        };
        // Push the combined object into the resulting array
        arrListAsset.push(combinedObject);
      }
    }
    

    if (isEmptyField == false) {
      formData = new FormData
      formData.append("_token","{{ csrf_token() }}")        
      formData.append("arrListAsset",JSON.stringify(arrListAsset)) 
      formData.append("type",type) 

      swalFireCustom = {
        title: 'Are you sure?',
        text: "Save Asset Scheduling",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }

      swalSuccess = {
          icon: 'success',
          title: 'Asset Scheduling Successfully!',
          text: 'Click Ok to reload page',
      }

      Swal.fire(swalFireCustom).then((result) => {
        if (result.value) {
          $.ajax({
            type:"POST",
            url:"{{url('asset/storeScheduling')}}",
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
                  $('#modal-add-asset-schedule').modal('hide')
                  Swal.close()
                  $('#table-asset-scheduling').DataTable().ajax.url("{{url('asset/getDataScheduling')}}").load()
                }
              })
            }
          })
        }
      })
    }else{
      Swal.fire({
        title: "<strong>Oopzz!</strong>",
        icon: "info",
        html: `
          Fill Empty Input Field!
        `,
      })
    }
  }

  $('#modal-add-asset-schedule').on('hidden.bs.modal', function () {
    $("input").val("")
    $("select").empty("")
  });

  function deleteScheduling(id){
    swalFireCustom = {
      title: 'Are you sure?',
      text: "Delete Asset Scheduling",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
    }

    swalSuccess = {
        icon: 'success',
        title: 'Asset Scheduling Deleted!',
        text: 'Click Ok to reload page',
    } 

    Swal.fire(swalFireCustom).then((result) => {
      if (result.value) {
        $.ajax({
          type:"POST",
          url:"{{url('asset/deleteScheduling')}}",
          data:{
            _token:"{{csrf_token()}}",
            id:id
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
            })
            Swal.showLoading()
          },
          success: function(result)
          {
            Swal.fire(swalSuccess).then((result) => {
              if (result.value) {
                $('#modal-add-asset-schedule').modal('hide')
                Swal.close()
                $('#table-asset-scheduling').DataTable().ajax.url("{{url('asset/getDataScheduling')}}").load()
              }
            })
          }
        })
      }
    })
  }

  function btnAddAssetScheduling(){
    $("#modal-schedulingBy").modal("show")
    $("#schedulingBy").val("").trigger("change")
    $("#schedulingBy").select2({
      placeholder:"Select Option",
      dropdownParent:$("#modal-schedulingBy"),
      data:[
        {id:"pid",text:"Project Id"},
        {id:"asset",text:"Asset"},
      ],
    })
  }

  function schedulingBy(id){
    $("#modal-schedulingBy").modal("hide")
    $("#modal-add-asset-schedule").modal("show")
    if(id == 'pid'){
      $(".divPid").show()
      $(".divAsset").hide()
      settingPidBefore()
      settingPidAfter()
      settingPeriode()
      $("#saveSchedule").attr("onclick","saveSchedule('pid')")
    }else{
      $(".divPid").hide()
      $(".divAsset").show()
      settingIdAsset("add",null,"modal-add-asset-schedule")
      settingInputDate()
      $("#saveSchedule").attr("onclick","saveSchedule('asset')")

    }
  }

</script>
@endsection

