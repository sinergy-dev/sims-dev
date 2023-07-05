@extends('template.main')
@section('tittle')
  Config
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.5/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
    .select2-container {
      width: 100% !important;
    }

    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }
  </style>
@endsection
@section('content')
<section class="content-header">
    <h1>
        Config
        <small>Timesheet</small>
    </h1>
    <ol class="breadcrumb">
        <!-- <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li> -->
        <li class="active"><i class="fa fa-gear"></i> Config</li>
    </ol><br>
</section>

<section class="content">
  <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">
              Config
            </h3>
            <div class="box-tools pull-right">
              <button class="btn btn-sm bg-purple" data-toggle="modal" data-target="#ModalAddPhase" id="modalAddPhase" style="display:none;">Add Phase</button>
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ModalAddTask" id="modalAddTask" style="display:none;">Add Task</button>
            </div>
          </div>
          <div class="box-body">
            <div class="config-container">
            </div>
          </div>
          <div class="box-footer">
            <div  style="display:flex;justify-content:center;">
              <button class="btn btn-sm bg-purple" id="btnAddConfig" style="display: none;" onclick="addConfig(this)"><i class="fa fa-plus"></i>&nbspConfig</button>  
            </div>
            <div class="pull-right">
                <button class="btn btn-sm btn-danger" id="btn_cancel_add_config" style="display: none;">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" id="btn_save_config" onclick="saveConfig()" style="display: none;">Save</button>
            </div>
          </div>

          <div class="row" style="padding:10px">
            <div class="col-md-12 col-xs-12">
              <div class="box box-primary">
                <div class="box-header bg-primary" style="color: white;">
                  <h3 class="box-title">
                    Definition
                  </h3>
                  <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse" fdprocessedid="lyslic"><i class="fa fa-minus" style="color:white;"></i>
                  </button></div>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="tbConfDef" class="table table-striped"></table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">
              Lock Activity Duration
            </h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Lock Duration*</label>
              <select class="form-control select2" id="selectLock" name="selectLock" placeholder="Select Lock Duration">
                <option value="1">1 Week</option>
                <option value="2">2 Week</option>
                <option value="3">3 Week</option>
                <option value="4">1 Month</option>
              </select>
            </div>
          </div>
          <div class="box-footer">
            <div class="pull-right">
                <button class="btn btn-sm btn-danger" id="btn_cancel_lock" style="display:none">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="saveDuration()" id="btn_save_lock" style="display:none;">Save</button>
            </div>
          </div>
        </div>

        <div class="box box-primary" id="box_assign_pid" style="display:none">
          <div class="box-header">
            <h3 class="box-title">
              Assign PID
            </h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Name*</label>
              <select class="form-control select2" id="selectPICAssign" name="selectPICAssign" onchange="validateInput(this)"><option></option></select>
              <span class="help-block" style="display:none">Please Select PIC!</span>
            </div>
            <div class="form-group">
              <label>PID*</label>
              <select class="form-control select2" multiple id="selectPIDAssign" multiple="multiple" name="selectPIDAssign" onchange="validateInput(this)"><option></option></select>
              <span class="help-block" style="display:none">Please Select PID!</span>
            </div>
            <div class="form-group">
              <label>Role*</label>
              <select class="form-control select2" id="selectRoleAssign" name="selectRoleAssign" placeholder="Select Role" onchange="validateInput(this)">
                <option value="Main">Main</option>
                <option value="Support">Support</option>
              </select>
              <span class="help-block" style="display:none">Please Select Role!</span>
            </div>
          </div>
          <div class="box-footer">
            <div class="pull-right">
                <button class="btn btn-sm btn-danger">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="savePIC()">Save</button>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="ModalAddPhase" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Phase</h4>
            </div>
            <div class="modal-body">
            <form action="" id="modal_phase" name="modal_phase">
                @csrf
                <div class="form-group">
                  <label>Task*</label>
                  <input class="form-control" name="inputPhase" id="inputPhase" placeholder="Enter Phase" onkeyup="validateInput(this)"/>
                  <span class="help-block" style="display:none">Please fill Phase!</span>
                </div>
                <div class="form-group">
                  <label>Description*</label>
                  <textarea class="form-control" name="inputPhaseDesc" id="inputPhaseDesc" placeholder="Enter Description" onkeyup="validateInput(this)"></textarea>
                  <span class="help-block" style="display:none">Please fill Description!</span>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="savePhaseConfig()">Save</button>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalAddTask" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Task</h4>
            </div>
            <div class="modal-body">
            <form action="" id="modal_task" name="modal_task">
                @csrf
                <div class="form-group">
                  <label>Task*</label>
                  <input class="form-control" name="inputTask" id="inputTask" placeholder="Enter Task" onkeyup="validateInput(this)"/>
                  <span class="help-block" style="display:none">Please fill Task!</span>
                </div>
                <div class="form-group">
                  <label>Description*</label>
                  <textarea class="form-control" name="inputTaskDesc" id="inputTaskDesc" placeholder="Enter Description" onkeyup="validateInput(this)"></textarea>
                  <span class="help-block" style="display:none">Please fill Description!</span>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="saveTaskConfig()">Save</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scriptImport')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <!-- <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script> -->
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    {{--  Calendar  --}}
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('script')
  <script type="text/javascript">
    var accesable = @json($feature_item);
    console.log(accesable)
    accesable.forEach(function(item,index){
      $("#" + item).show()
      console.log(item)
    })

    $("#tbConfDef").DataTable({
      "ajax":{
        "type":"GET",
        "url":"{{url('/timesheet/getAllPhaseTask')}}",
      },
      "columns": [
        { title: 'title',
          data: 'title'
        },
        { title: 'Name',
          data: 'name' 
        },
        { title: 'Description',
          data: 'description' 
        },
      ],
      lengthChange: false,
      columnDefs: [{ visible: false, targets: 0 }],
      order: [[1, 'asc']],
      lengthChange: false,
      drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;

          api
          .column(0, { page: 'current' })
          .data()
          .each(function (group, i) {
              if (last !== group) {
                  $(rows)
                      .eq(i)
                      .before('<tr class="group"><td colspan="2"><b>' + group + '</b></td></tr>');
                  last = group;
              }
          });
      },
    })

    function getConfigbyDivision(){
      $.ajax({
        url:"{{url('timesheet/getConfigByDivision')}}",
        type:"GET",
        success:function(result){
          var appendBox = ""

          if (result.length > 0) {
            $.each(result,function(idx,value){
              appendBox = appendBox + '<div class="box-add-config">'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Unit*</label>'
                appendBox = appendBox + '  <select class="form-control selectUnit" data-value="'+ idx +'" id="selectUnit" name="selectUnit" onchange="validateInput(this)"><option></option></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Unit!</span>'
                appendBox = appendBox + '</div>'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Phase*</label>'
                appendBox = appendBox + '  <select class="form-control selectPhase" data-value="'+ idx +'" id="selectPhase" multiple="multiple" onchange="validateInput(this)" name="selectPhase"></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Phase!</span>'
                appendBox = appendBox + '</div>'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Task*</label>'
                appendBox = appendBox + '  <select class="form-control selectTask" data-value="'+ idx +'" id="selectTask" multiple="multiple" onchange="validateInput(this)" name="selectTask"></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Task!</span>'
                appendBox = appendBox + '</div>'
              appendBox = appendBox + '</div>'

              selectUnit(value.roles,idx)
              selectPhase(JSON.parse(value.phase),idx)
              selectTask(JSON.parse(value.task),idx)
            })

            $(".config-container").append(appendBox)
          }else{
              appendBox = appendBox + '<div class="box-add-config">'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Unit*</label>'
                appendBox = appendBox + '  <select class="form-control selectUnit" id="selectUnit" name="selectUnit" onchange="validateInput(this)"><option></option></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Unit!</span>'
                appendBox = appendBox + '</div>'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Phase*</label>'
                appendBox = appendBox + '  <select class="form-control selectPhase" id="selectPhase" multiple="multiple" onchange="validateInput(this)" name="selectPhase"></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Phase!</span>'
                appendBox = appendBox + '</div>'
                appendBox = appendBox + '<div class="form-group">'
                appendBox = appendBox + '  <label>Task*</label>'
                appendBox = appendBox + '  <select class="form-control selectTask" id="selectTask" multiple="multiple" onchange="validateInput(this)" name="selectTask"></select>'
                appendBox = appendBox + '  <span class="help-block" style="display:none">Please Select Task!</span>'
                appendBox = appendBox + '</div>'
              appendBox = appendBox + '</div>'

              $(".config-container").append(appendBox)

              selectUnit('')
              selectPhase('')
              selectTask('')
          }

          if (accesable.includes('btnAddConfig') == false) {
            console.log("woyyyy")
            $('.config-container').find('.box-add-config').find('select').prop('disabled',true)
            $("#selectLock").prop("disabled",true)
          }

        }
      })
    }
    
    function addConfig(items){
      var cloneBody = $(items).closest('div').closest('.box-footer').prev('.box-body').find('.config-container').find('.box-add-config').last().clone()

      cloneBody.find("select")
            .select2().val("")
            .end()

      $(items).closest('div').closest('.box-footer').prev('.box-body').find('.config-container').find('.box-add-config').last().after(cloneBody)
      
      cloneBody.find("select").next().next().remove()

      if ($("#btnTrash").length === 0) {
        $(items).after('<button class="btn btn-sm btn-danger fa fa-trash" style="width:35px;margin-left:5px" id="btnTrash" onclick="btnTrash()"></button>')
      }
    }

    function btnTrash(){
      var whichtr = $("#btnAddConfig").closest('div').closest('.box-footer').prev('.box-body').find('.config-container').find('.box-add-config').last().last()
      whichtr.remove() 

      if ($("#btnAddConfig").closest('div').closest('.box-footer').prev('.box-body').find('.config-container').find('.box-add-config').length === 1){
        $("#btnTrash").remove()
      }
    }

    $.ajax({
      url: "{{'/timesheet/getAllPid'}}",
      type: 'GET',
      success:function(result){
          $("#selectPIDAssign").select2({
              multiple:true,
              data:result,
              placeholder:'Select PID',
          })
      }
    })

    $.ajax({
      url: "{{'/timesheet/getLockDurationByDivision'}}",
      type: 'GET',
      success:function(result){
          $("#selectLock").select2().val(result[0].lock_duration).trigger('change')
      }
    })

    function selectUnit(val="",idx=""){
      $.ajax({
        url: "{{'/timesheet/getRoles'}}",
        type: 'GET',success:function(result){
            var selectUnit = $(".selectUnit").select2({
                placeholder:"Select Unit",
                data:result,
            })

            if (val != "") {
              $(".selectUnit[data-value='"+ idx +"']").select2({
                  placeholder:"Select Unit",
                  data:result,
              }).val(val).trigger('change')
            }else{
              selectUnit
            }
        }
      })
    }

    function selectPhase(val="",idx=""){
      $.ajax({
        url: "{{'/timesheet/getAllPhase'}}",
        type: 'GET',success:function(result){
          var selectPhase = $(".selectPhase").select2({
              multiple:true,
              data:result,
              placeholder:'Select Phase',
          })

          if (val != "") {
            $(".selectPhase[data-value='"+ idx +"']").select2({
              multiple:true,
              data:result,
              placeholder:'Select Phase',
            }).val(val).trigger('change')
          }else{
            selectPhase
          }
            
        }
      })
    }

    function selectTask(val="",idx=""){
      $.ajax({
        url: "{{'/timesheet/getAllTask'}}",
        type: 'GET',success:function(result){
          var selectTask = $(".selectTask").select2({
              multiple:true,
              data:result,
              placeholder:'Select Task',
          })

          if (val != "") {
            $(".selectTask[data-value='"+ idx +"']").select2({
              multiple:true,
              data:result,
              placeholder:'Select Task',
            }).val(val).trigger('change')
          }else{
            selectTask
          }
            
        }
      })
    }
    
    $.ajax({
      url: "{{'/timesheet/getAllUser'}}",
      type: 'GET',success:function(result){
          $("#selectPICAssign").select2({
              placeholder:"Select PIC",
              data:result,
          })
      }
    })

    function validateInput(val){
      if ($(val).is("select")) {
          if (val.value != "") {
              $(val).next().next().hide()
              $(val).closest("div").removeClass("has-error")
          }
      }else{
        $("#"+val.id).next().hide()
        $("#"+val.id).closest("div").removeClass("has-error")
      }
    }

    function saveTaskConfig(){
      if ($("#inputTask").val() == "") {
         $("#inputTask").closest("div").find("span").show()
         $("#inputTask").closest("div").addClass("has-error")
      }else if($("#inputTaskDesc").val() == ""){
        $("#inputTaskDesc").closest("div").find("span").show()
        $("#inputTaskDesc").closest("div").addClass("has-error")
      }else{
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("inputTask",$("#inputTask").val())
        formData.append("inputTaskDesc",$("#inputTaskDesc").val())    

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this task config!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Add task config has been successfully!',
            text: 'Click Ok to reload page',
        } 

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/storeTaskConfig")
      }
    }

    function savePhaseConfig(){
      if ($("#inputPhase").val() == "") {
         $("#inputPhase").closest("div").find("span").show()
         $("#inputPhase").closest("div").addClass("has-error")
      }else if($("#inputPhaseDesc").val() == ""){
        $("#inputPhaseDesc").closest("div").find("span").show()
        $("#inputPhaseDesc").closest("div").addClass("has-error")
      }else{
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("inputPhase",$("#inputPhase").val())
        formData.append("inputPhaseDesc",$("#inputPhaseDesc").val())    

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this phase config!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Add phase config has been successfully!',
            text: 'Click Ok to reload page',
        } 

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/storePhaseConfig")
      }
    }

    function saveConfig(){
      var arrConfig = [], unit = '', phase = '', task = '', isReadyStore = false    
      $(".box-add-config").each(function(idxI,item){
          $(item).find("#selectUnit").each(function(idx,itemsUnit){
              if ($(itemsUnit).val() == '') {
                $(itemsUnit).closest("div").find("span").show()
                $(itemsUnit).closest("div").addClass("has-error")
              }else{
                unit = itemsUnit.value
              }

          })

          $(item).find("#selectPhase").each(function(idxP,itemsPhase){
              if ($(itemsPhase).val() == '') {
                $(itemsPhase).closest("div").find("span").show()
                $(itemsPhase).closest("div").addClass("has-error")
              }else{
                phase = $(itemsPhase).select2("val")
              }
          })

          $(item).find("#selectTask").each(function(idx,itemsTask){
              if ($(itemsTask).val() == '') {
                $(itemsTask).closest("div").find("span").show()
                $(itemsTask).closest("div").addClass("has-error")
              }else{
                task = $(itemsTask).select2("val")
              }
          })   
          arrConfig.push({"unit":unit,"phase":phase,"task":task})
      })

      if ($(".help-block").is(":visible") == false) {
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("arrConfig",JSON.stringify(arrConfig))
        // formData.append("selectPhase",$("#selectPhase").val())
        // formData.append("inputTaskDesc",$("#inputTaskDesc").val())    

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this task config!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Add task config has been successfully!',
            text: 'Click Ok to reload page',
        } 

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/addConfig")
      }
    }

    function saveDuration(){
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("selectLock",$("#selectLock").val())    

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this lock duration config!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Add lock duration config has been successfully!',
            text: 'Click Ok to reload page',
        } 

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/storeLockDuration")
    }

    function savePIC(){
      if ($("#selectPICAssign").val() == "") {
         $("#selectPICAssign").closest("div").find("span").show()
         $("#selectPICAssign").closest("div").addClass("has-error")
      }else if($("#selectPIDAssign").val() == ""){
        $("#selectPIDAssign").closest("div").find("span").show()
        $("#selectPIDAssign").closest("div").addClass("has-error")
      }else if($("#selectRoleAssign").val() == ""){
        $("#selectRoleAssign").closest("div").find("span").show()
        $("#selectRoleAssign").closest("div").addClass("has-error")
      }else{
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("selectPICAssign",$("#selectPICAssign").val())
        formData.append("selectPIDAssign",JSON.stringify($("#selectPIDAssign").val()))
        formData.append("selectRoleAssign",$("#selectRoleAssign").val())        

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Save this assign PIC!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Assign PIC been successfully, you can view list of PIC on dashboard page!',
            text: 'Click Ok to reload page',
        } 

        createPost(swalFireCustom,formData,swalSuccess,url="/timesheet/assignPidConfig")
      }
    }

    function createPost(swalFireCustom,data,swalSuccess,url){
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
                    location.reload()
                  }
                })
              }
            })
          }
      })
    }

    $(document).ready(function(){
      getConfigbyDivision()
    })

  </script>
@endsection