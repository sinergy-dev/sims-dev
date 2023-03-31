@extends('template.main')
@section('tittle')
SBE Detail
@endsection
@section('head_css')
	<style type="text/css">
		.dataTables_filter {
		    display: none;
		}

        textarea{
            resize: vertical;
        }

        input[name='priceItems'],input[name='totalItems'],input[name='inputGrandTotal']  {
            text-align: right;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
	</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">

@endsection
@section('content')
	<section class="content-header">
        <h1>
            <a class='btn btn-sm btn-danger' id="btnBack"><i class="fa fa-arrow-left"></i> &nbspBack</a>
            <span id="spanLeadId"></span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> SBE</a></li>
            <li class="active">SBE Detail</li>
        </ol><br>
    </section>

    <section class="content">
        
    </section>

    <!--modal notes-->
    <div class="modal fade" id="ModalNotes" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Notes</h4>
                </div>
                <div class="modal-body">
                <form action="" id="modal_notes" name="modal_notes">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" name="textareaNotes" id="textareaNotes" placeholder="Enter Notes" onkeyup="validateInput(this)"></textarea>
                        <span class="help-block" style="display:none">Please fill Notes!</span>

                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" onclick="btnSendNotes()">Send</button>
                </div>
              </div>
          </div>
        </div>
    </div>
@endsection
@section('scriptImport')
<!--select2-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
	// var data = "data":
    var formatter = new Intl.NumberFormat(['ban', 'id']);

    var accesable = @json($feature_item);

    $(document).ready(function(){
        if (window.location.href.split("/")[3].split("?")[1] == 'create') {
            showCreateSBE()
            $("#btnBack").attr('href','{{url("/sbe_index")}}/')
        }else{
            showFunctionConfig()
            localStorage.setItem('isConfigPage',false)
            $("#btnBack").click(function(){
                if (localStorage.getItem('isConfigPage') == 'true') {
                    $("#btnBack").removeAttr('href')
                    $("#btnBack").attr('onclick','showFunctionConfig()')
                    localStorage.setItem('isConfigPage',false)
                }else{
                    $("#btnBack").removeAttr('onclick')
                    $("#btnBack").attr('href','{{url("/sbe_index")}}/')
                }
            })
            $("#spanLeadId").html("Lead ID - "+ window.location.href.split("?")[1])
        }

        accesable.forEach(function(item,index){
          $("#" + item).show()
        })
    })


    function cancelConfig(){
        localStorage.setItem('isConfigPage',false)
        $(".content").empty("")
        showFunctionConfig()    
    }

    
    function showFunctionConfig(){
        $(".content").empty("")
        var append = ""
        var appendVersion = ""
        var data = ["PMO","PMO SID","PMO MSM"]
        var dataRadio = ["Ver 1","Ver 2","Ver 3"]

        append = append + '<div class="row" style="display:none">'
            append = append + '<div class="col-md-12 col-xs-12">'
                append = append + ' <div class="alert alert-danger alert-dismissible">'
                    append = append + '      <h4><i class="icon fa fa-ban"></i> Reject Notes!</h4>'
                    append = append + '      <span id="RejectAlertNotes">tes</span>'
                append = append + ' </div>'
            append = append + ' </div>'
        append = append + '</div>'
        append = append + '<div class="row">'
            append = append + '            <div class="col-md-6 col-xs-12">'
            append = append + '                <div class="box" id="temporaryFunction">'
            append = append + '                    <div class="box-header">'
            append = append + '                        <h3 class="box-title">Preview</h3>'
            append = append + '                        <div class="box-tools">'
            append = append + '                         <button class="btn btn-sm btn-primary" style=";display:none" onclick="addNotes()" id="btnAddNotes">Notes</button>'
            append = append + '                        </div>'
            append = append + '                    </div>'
            append = append + '                    <div class="box-body" id="boxConfigTemp">'
            append = append + '                    </div>'
            append = append + '                </div>'
            append = append + '            </div>'
            append = append + '            <div class="col-md-6 col-xs-12">'
            append = append + '                <div class="box" id="configs">'
            append = append + '                    <div class="box-header">'
            append = append + '                        <h3 class="box-title">Temporary Config</h3>'
            append = append + '                        <div class="box-tools">'
            append = append + '                        </div>'
            append = append + '                    </div>'
            append = append + '                    <div class="box-body" id="showConfig" style="padding:20px">'
            Pace.track(function() {
                $.ajax({
                    type:"GET",
                    url:"{{url('/sbe/getVersionConfig')}}",
                    data:{
                        id_sbe:window.location.href.split("/")[4].split("?")[0] 
                    },success:function(item,result){
                        appendVersion = appendVersion + '    <div class="row" style="padding:10px">'
                        var arrChoosed = [], arrChoosedSO = [], arrChoosedImp = [], arrChoosedMnS = []
                        $.each(item,function(item,config){
                            appendVersion = appendVersion + '     <div class="col-md-4 col-xs-12">'
                            appendVersion = appendVersion + '        <div class="form-group">'
                            appendVersion = appendVersion + '            <label>'+ item +'</label>'
                            if (item == "Supply Only") {
                                var type = "SO"
                            }else if(item == "Implementation"){
                                var type = "Imp"
                            }else{
                                var type = "MnS"
                            }

                            var checked = ""
                            $.each(config,function(index,items){
                                if (items.status == "Choosed") {
                                    var checked = "checked"
                                }
                                appendVersion = appendVersion + '     <div class="radio">'
                                appendVersion = appendVersion + '       <label>'
                                appendVersion = appendVersion + '         <input '+ checked +' type="radio" name="radio'+ type +'" id="radio'+ type +'" value="'+ items.id +'">'
                                appendVersion = appendVersion + "Ver " + parseInt(index+1)
                                appendVersion = appendVersion + '          </label>'
                                appendVersion = appendVersion + '            <a onclick="showUpdateConfigSO('+ items.id +')">'
                                appendVersion = appendVersion + '               <i class="fa fa-pencil-square-o"></i>'
                                appendVersion = appendVersion + '            </a>'
                                appendVersion = appendVersion + '      </div>'
                            })  
                            appendVersion = appendVersion + '       </div>'
                            appendVersion = appendVersion + '     </div>'
                        })
                        appendVersion = appendVersion + '   </div>' 
                        $("#showConfig").append(appendVersion)

                        
                        if (!accesable.includes('radioConfig')) {
                            $("input[type='radio']").each(function(index,item){
                                $(item).each(function(indexes,itemRadio){
                                    $(itemRadio).closest("label").next().remove()
                                })
                            })
                        }

                        $("input[type='radio']").each(function(index,item){
                            $(item).each(function(indexes,itemRadio){
                                if (itemRadio.id == 'radioSO') {
                                    if($(itemRadio).is(':checked')){
                                        arrChoosedSO.push(itemRadio.value)
                                    }
                                }

                                if (itemRadio.id == 'radioImp') {
                                    if($(itemRadio).is(':checked')){
                                        arrChoosedImp.push(itemRadio.value)
                                    }
                                }

                                if (itemRadio.id == 'radioMnS') {
                                    if($(itemRadio).is(':checked')){
                                        arrChoosedMnS.push(itemRadio.value)
                                    }
                                }
                            })                           
                        })

                        $("input[type='radio']").change(function(){
                            $("#boxConfigTemp").empty("")
                            
                            if (this.id == 'radioSO') {
                                arrChoosedSO = []
                                if($(this).is(':checked')){
                                    arrChoosedSO.push(this.value)
                                }
                            }

                            if (this.id == 'radioImp') {
                                arrChoosedImp = []
                                if($(this).is(':checked')){
                                    arrChoosedImp.push(this.value)
                                }
                            }

                            if (this.id == 'radioMnS') {
                                arrChoosedMnS = []
                                if($(this).is(':checked')){
                                    arrChoosedMnS.push(this.value)
                                }
                            }
                            // arrChoosed.push($(this).value)
                            setTimeout(dynamicShowTempSbe(window.location.href.split("/")[4].split("?")[0],"/sbe/getConfigChoosed",JSON.stringify(arrChoosedSO.concat(arrChoosedImp, arrChoosedMnS))), 20000)
                            // dynamicShowTempSbe(window.location.href.split("/")[4].split("?")[0],"/sbe/getConfigChoosed",JSON.stringify(arrChoosedSO.concat(arrChoosedImp, arrChoosedMnS)))
                        })
                        
                        // checkInnerHeight()
                    }
                })
            })
            
            append = append + '                     <button class="btn btn-sm btn-warning" style="margin-bottom: 10px;margin-right:50px;position: absolute;right: 10px;bottom: 0;display:none" onclick="resetConfig()" id="btnResetConfig">Reset</button>'
            append = append + '                     <button class="btn btn-sm bg-purple" style="margin-bottom: 10px;position: absolute;right: 10px;bottom: 0;display:none" onclick="saveFuncConf()" id="btnSaveConf">Save</button>'
            append = append + '                    </div>'            
            append = append + '                </div>'            
            append = append + '            </div>'
        append = append + '</div>'
        append = append + '<div class="row">'
            append = append + '<div class="col-md-12 col-xs-12">'
                append = append + '<div class="box">'
                append = append + '    <div class="box-header">'
                append = append + '        <h3>Change Log</h3>'
                append = append + '    </div>'
                append = append + '    <div class="box-body">'
                append = append + '        <div class="table-responsive" style="width:100%">'
                append = append + '            <table id="tbChangeLog" class="table table-striped table-bordered">'
                append = append + '            </table>'
                append = append + '        </div>'
                append = append + '    </div>'
                append = append + '</div>'
            append = append + '</div>'
        append = append + '</div>'
        $(".content").append(append)
        dynamicShowTempSbe(window.location.href.split("/")[4].split("?")[0],"/sbe/getConfigTemporary",arrChoosed="")

        accesable.forEach(function(item,index){
          $("#" + item).show()
        })


        $("#tbChangeLog").DataTable({
            "ajax":{
                "type":"GET",
                "url":"{{url('/sbe/getActivity')}}",
                "data":{
                    id_sbe:window.location.href.split("/")[4].split("?")[0] 
                }
            },
            columns: [
                {
                  title: "Name",
                  data: "name"
                },
                {
                  title: "Roles",
                  data: "role"
                },
                {
                  title: "Activity",
                  data: "activity"
                },
                {
                  title: "Date",
                  data: "date_add"
                },
            ],
            "bFilter": true,
            "bSort":true,
            "bLengthChange": false,
            "bInfo": false,
            drawCallback: function(settings) {
                $.ajax({
                    type:"GET",
                    url:"{{url('/sbe/getActivity')}}",
                    "data":{
                        id_sbe:window.location.href.split("/")[4].split("?")[0] 
                    },success:function(result){
                        if (result.getNotes.length > 0) {
                            $("#temporaryFunction").closest(".row").siblings().first().show()
                            $("#temporaryFunction").closest(".row").siblings().first().find(".alert").find("span").text(result.getNotes[0].notes)
                        }

                        if (result.status[0].status == "Fixed") {
                            $("#btnAddNotes").hide()
                            $("#btnGeneratePdf").hide()
                            $("#btnResetConfig").hide()
                            $("#btnSaveConf").hide()
                            $("input[type='radio']").prop("disabled",true)
                            $("input[type='radio']").each(function(index,item){
                                 $(item).each(function(indexes,itemRadio){
                                    $(itemRadio).closest("label").next().attr("onclick","showTemporarySBE("+ itemRadio.value +")")
                                 })                           
                            })
                        }
                    }
                })
            },
            initComplete: function () {
               
            }
        });
    }

    function dynamicShowTempSbe(id_sbe,url,arrChoosed){
        if (arrChoosed == "") {
            var data = {id_sbe:id_sbe}
        }else{
            var data = {id_sbe:id_sbe,arrChoosed:arrChoosed}
        }
        Pace.restart();
        Pace.track(function() {
            $.ajax({
                type:"GET",
                url:"{{url('/')}}"+url,
                data:data,
                success:function(result){
                    var appendTemporary = ""

                    if (result.length == 0) {
                        showEmptyConfig()
                    }else{
                        $.each(result,function(index,items){
                            if (index == "Implementation") {
                                var bg_color = "#789de5"
                            }else if (index == "Maintenance") {
                                var bg_color = "#ea3323"
                            }else{
                                var bg_color = "#f19e38"
                            }
                            appendTemporary = appendTemporary + '<div class="row">'
                            appendTemporary = appendTemporary + '            <div class="col-md-3 col-xs-12" style="text-align:-webkit-center">'
                            appendTemporary = appendTemporary + '              <a onclick="showTemporarySBE('+ items[0].id +')">'
                            appendTemporary = appendTemporary + '                <div style="width: 100px;height: 100px;background-color: '+ bg_color +';color: white;text-align: center;vertical-align: middle;display: flex;">'
                            appendTemporary = appendTemporary + '                    <span style="'
                            appendTemporary = appendTemporary + '                        margin: auto;'
                            appendTemporary = appendTemporary + '                        display: flex;'
                            appendTemporary = appendTemporary + '                        text-align: center;'
                            appendTemporary = appendTemporary + '                        align-items: center;cursor:pointer">'+ index +'</span>'
                            appendTemporary = appendTemporary + '                </div>'
                            appendTemporary = appendTemporary + '              </a>'
                            appendTemporary = appendTemporary + '            </div>'
                            appendTemporary = appendTemporary + '            <div class="col-md-9 col-xs-12">'
                            appendTemporary = appendTemporary + '                <div class="table-responsive">'
                            appendTemporary = appendTemporary + '                    <table class="table table-bordered" width="100%">'
                            appendTemporary = appendTemporary + '                        <thead style="background-color:'+ bg_color +'">'
                            appendTemporary = appendTemporary + '                            <tr>'
                            appendTemporary = appendTemporary + '                                <th>No</th>'
                            appendTemporary = appendTemporary + '                                <th>Function</th>'
                            appendTemporary = appendTemporary + '                                <th>Total</th>'
                            appendTemporary = appendTemporary + '                            </tr>'
                            appendTemporary = appendTemporary + '                        </thead>'
                            appendTemporary = appendTemporary + '                        <tbody>'
                            $.each(items[0].get_function,function(index,itemsTemp){
                                appendTemporary = appendTemporary + '          <tr>'
                                appendTemporary = appendTemporary + '              <td>'+ ++index +'</td>'
                                appendTemporary = appendTemporary + '              <td>'+ itemsTemp.item +'</td>'
                                appendTemporary = appendTemporary + '              <td>'+ formatter.format(itemsTemp.total_nominal) +'</td>'
                                appendTemporary = appendTemporary + '          </tr>'
                                appendTemporary = appendTemporary + '      </tbody>'

                            })
                            appendTemporary = appendTemporary + '      <tfoot>'
                            appendTemporary = appendTemporary + '          <tr>'
                            appendTemporary = appendTemporary + '              <th colspan="2" style="text-align:right">Grand Total</th>'
                            appendTemporary = appendTemporary + '              <th>'+ formatter.format(items[0].nominal) +'</th>'
                            appendTemporary = appendTemporary + '          </tr>'
                            appendTemporary = appendTemporary + '                        </tfoot>'
                            appendTemporary = appendTemporary + '                    </table>'
                            appendTemporary = appendTemporary + '                </div>'
                            appendTemporary = appendTemporary + '            </div>'
                            appendTemporary = appendTemporary + '        </div>'
                        })
                        
                        appendTemporary = appendTemporary + '     <button class="btn btn-sm btn-success pull-right" onclick="generatePDF()" id="btnGeneratePdf" style="display:none">Generate</button>'
                        $("#boxConfigTemp").append(appendTemporary).fadeIn('slow')
                    }

                    accesable.forEach(function(item,index){
                      $("#" + item).show()
                    })

                    // checkInnerHeight()
                }
            })
        })
    }

    function showCreateSBE(){
        var append = ""

        append = append + '<div class="box">'
        append = append + '    <div class="box-body">'
        append = append + '<div class="row">'
        append = append + '    <div class="col-md-6">'
        append = append + '        <div class="form-group">'
        append = append + '            <label>Lead ID*</label>'
        append = append + '            <select onchange="validateInput(this)" class="form-control select2" name="inputLead" id="inputLead"><option></option></select>'
        append = append + '            <span class="help-block" style="display:none">Please Select Lead Id!</span>'
        append = append + '        </div>'
        append = append + '        <div class="form-group">'
        append = append + '            <label>Project Location*</label>'
        append = append + '            <textarea onkeyup="validateInput(this)" class="form-control" style="resize: vertical;" name="textareaLoc" id="textareaLoc"></textarea>'
        append = append + '            <span class="help-block" style="display:none">Please Fill Project Location!</span>'
        append = append + '        </div>'
        // append = append + '        <div class="form-group">'
        // append = append + '            <label>Duration*</label>'
        // append = append + '            <input onkeyup="validateInput(this)" type="text" class="form-control" name="inputDuration" id="inputDuration">'
        // append = append + '            <span class="help-block" style="display:none">Please Fill Duration!</span>'
        // append = append + '        </div>'
        append = append + '        <div class="form-group">'
        append = append + '            <label>Estimated Running*</label>'
        append = append + '            <input onkeyup="validateInput(this)" type="text" class="form-control" name="inputEstimatedRun" id="inputEstimatedRun">'
        append = append + '            <span class="help-block" style="display:none">Please Fill Estimated Running!</span>'
        append = append + '        </div>'
        append = append + '    </div>'
        append = append + '    <div class="col-md-6">'
        append = append + '        <div class="form-group">'
        append = append + '            <label>Scope Of Work*</label>'
        append = append + '            <textarea onkeyup="validateInput(this)" class="form-control" style="resize: vertical;height: 150px;" name="textareaSOW" id="textareaSOW"></textarea>'
        append = append + '            <span class="help-block" style="display:none">Please Fill Scope of Work!</span>'
        append = append + '        </div>'
        append = append + '        <div class="form-group">'
        append = append + '            <label>Out Of Scope*</label>'
        append = append + '            <textarea onkeyup="validateInput(this)" class="form-control" style="resize: vertical;height: 150px;" row="20" name="textareaScope" id="textareaScope"></textarea>'
        append = append + '            <span class="help-block" style="display:none">Please Fill Out of Scope!</span>'
        append = append + '        </div>'
        append = append + '    </div>'
        append = append + '</div>'
        append = append + '<div class="row">'
        append = append + '    <div class="col-md-12 col-xs-12">'
        append = append + '        <label>Config*</label>'
        append = append + '        <div class="nav-tabs-custom">'
        append = append + '            <ul class="nav nav-tabs">'
        append = append + '                <li class="active"><a href="#tab_1" data-toggle="tab">Supply Only</a><input id="durationSO" hidden></li>'
        append = append + '                <li><a href="#tab_2" data-toggle="tab">Implementation</a><input id="durationImp" hidden></</li>'
        append = append + '                <li><a href="#tab_3" data-toggle="tab">Maintenance & Managed Service</a><input id="durationMnS" hidden></</li>'
        append = append + '                <li style="float: right;display: inline-flex;">'
        append = append + '                     <label style="vertical-align: middle;display: inline;margin-top: 10px;margin-right: 10px;'
        append = append + '                         ">Duration*</label>'
        append = append + '                     <input class="form-control" id="inputDuration" name="inputDuration" onkeyup="validateInput(this)" fdprocessedid="52jb4" style="display: inline;width:400px" placeholder="Input Duration">'
        append = append + '                 </li>'
        append = append + '            </ul>'
        append = append + '            <div class="tab-content">'
        append = append + '                <div class="tab-pane active" id="tab_1">'
        append = append + '                    <table class="table" name="tableSO" id="tableItemsSO_0" style="display:none">'
        append = append + '                        <thead>'
        append = append + '                            <tr>'
        append = append + '                                <th>'
        append = append + '                                    Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Detail Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Qty'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Price'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Manpower'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Total'
        append = append + '                                </th>'
        append = append + '                                <td>'
        append = append + '                                    <button class="btn btn-sm btn-primary" id="btnAddConfigSO" disabled="true" onclick="addConfigSO(0)" style="width: 30px;"><i class="fa fa-plus"></i></button>'
        append = append + '                                </td>'
        append = append + '                            </tr>'
        append = append + '                        </thead>'
        append = append + '                        <tbody>'
        append = append + '                            <tr>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="InputItems" id="InputItems" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <select type="" style="width: 100%" class="select2 form-control detailItemsSO_0" name="detailItemsSO" id="detailItemsSO">'
        append = append + '                                        <option></option>'
        append = append + '                                    </select>'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="number" style="width:60px" class="form-control qtyItems" name="qtyItems" id="qtyItems" onkeyup="changeQtyItems(this)">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="priceItems" id="priceItems" readonly="">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" style="width:60px" class="form-control" name="manpowerItems" id="manpowerItems" onkeyup="changeManPower(this)">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
        append = append + '                                </td>'
        append = append + '                                <td></td>'
        append = append + '                            </tr>'
        append = append + '                        </tbody>'
        append = append + '                        <tfoot>'
        append = append + '                            <tr>'
        append = append + '                                <td colspan="4"></td>'
        append = append + '                                <th>Grand Total</th>'
        append = append + '                                <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
        append = append + '                            </tr>'
        append = append + '                        </tfoot>'
        // append = append + '                    <hr>'
        append = append + '                    </table>'
        append = append + '                    <div style="text-align:center;">'
        append = append + '                <button class="btn btn-sm bg-purple" id="addItemsSO" style="margin: 0 auto;" onclick="addItemsSO(0)"><i class="fa fa-plus"></i>&nbsp Add Items</button>'
        append = append + '                    </div>'
        append = append + '                </div>'
        append = append + '                <div class="tab-pane" id="tab_2">'
        append = append + '                    <table class="table" name="tableImp" id="tableItemsImp_0" style="display:none">'
        append = append + '                        <thead>'
        append = append + '                            <tr>'
        append = append + '                                <th>'
        append = append + '                                    Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Detail Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Qty'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Price'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Manpower'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Total'
        append = append + '                                </th>'
        append = append + '                                <td>'
        append = append + '                        <button class="btn btn-sm btn-primary" disabled id="btnAddConfigImp" onclick="addConfigImp(0)" style="width: 30px;"><i class="fa fa-plus"></i></button>'
        append = append + '                                </td>'
        append = append + '                            </tr>'
        append = append + '                        </thead>'
        append = append + '                        <tbody>'
        append = append + '                            <tr>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="InputItemsImp" id="InputItemsImp" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <select type="" style="width: 100%" class="select2 form-control detailItemsImp_0" name="detailItemsImp" id="detailItemsImp">'
        append = append + '                                        <option></option>'
        append = append + '                                    </select>'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="number" style="width:60px" class="form-control" name="qtyItemsImp" id="qtyItemsImp" onkeyup="changeQtyItems(this)">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="priceItemsImp" id="priceItemsImp" readonly="">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" style="width:60px" class="form-control" name="manpowerItemsImp" onkeyup="changeManPower(this)" id="manpowerItemsImp">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
        append = append + '                                </td>'
        append = append + '                                <td></td>'
        append = append + '                            </tr>'
        append = append + '                        </tbody>'
        append = append + '                        <tfoot>'
        append = append + '                            <tr>'
        append = append + '                                <td colspan="4"></td>'
        append = append + '                                <th>Grand Total</th>'
        append = append + '                                <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
        append = append + '                            </tr>'
        append = append + '                        </tfoot>'
        append = append + '                    </table>'
        // append = append + '                    <hr>'
        append = append + '                    <div style="text-align:center;">'
        append = append + '            <button class="btn btn-sm bg-purple" id="addItemsImp" style="margin: 0 auto;" onclick="addItemsImp(0)"><i class="fa fa-plus"></i>&nbsp Add Items</button>'
        append = append + '                    </div>'
        append = append + '                </div>'
        append = append + '                <div class="tab-pane" id="tab_3">'
        append = append + '                    <table class="table" name="tableMnS" id="tableItemsMnS_0" style="display:none">'
        append = append + '                        <thead>'
        append = append + '                            <tr>'
        append = append + '                                <th>'
        append = append + '                                    Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Detail Items'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Qty'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Price'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Manpower'
        append = append + '                                </th>'
        append = append + '                                <th>'
        append = append + '                                    Total'
        append = append + '                                </th>'
        append = append + '                                <td>'
        append = append + '                              <button class="btn btn-sm btn-primary" disabled id="btnAddConfigMnS" onclick="addConfigMnS(0)" style="width: 30px;"><i class="fa fa-plus"></i></button>'
        append = append + '                                </td>'
        append = append + '                            </tr>'
        append = append + '                        </thead>'
        append = append + '                        <tbody>'
        append = append + '                            <tr>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="InputItemsMnS" id="InputItemsMnS" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <select type="" style="width: 100%" class="select2 form-control detailItemsMnS_0" name="detailItemsMnS" id="detailItemsMnS">'
        append = append + '                                        <option></option>'
        append = append + '                                    </select>'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="number" style="width:60px" class="form-control" name="qtyItemsMnS" id="qtyItemsMnS" onkeyup="changeQtyItems(this)">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="priceItemsMnS" id="priceItemsMnS" readonly="">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" style="width:60px" class="form-control" name="manpowerItemsMnS" id="manpowerItemsMnS" onkeyup="changeManPower(this)">'
        append = append + '                                </td>'
        append = append + '                                <td>'
        append = append + '                                    <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
        append = append + '                                </td>'
        append = append + '                                <td></td>'
        append = append + '                            </tr>'
        append = append + '                        </tbody>'
        append = append + '                        <tfoot>'
        append = append + '                            <tr>'
        append = append + '                                <td colspan="4"></td>'
        append = append + '                                <th>Grand Total</th>'
        append = append + '                                <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
        append = append + '                            </tr>'
        append = append + '                        </tfoot>'
        append = append + '                    </table>'
        // append = append + '                    <hr>'
        append = append + '                    <div style="text-align:center;">'
        append = append + '            <button class="btn btn-sm bg-purple" id="addItemsMns" style="margin: 0 auto;" onclick="addItemsMnS(0)"><i class="fa fa-plus"></i>&nbsp Add Items</button>'
        append = append + '                    </div>'
        append = append + '                </div>'
        append = append + '            </div>'
        append = append + '        </div>'
        append = append + '    </div>'
        append = append + '</div>'
        append = append + '<div class="row">'
        append = append + '    <div class="col-md-12 col-xs-12">'
        append = append + '        <button class="btn btn-sm btn-primary pull-right" onclick="saveConfig()">Save</button>'
        append = append + '        <a class="btn btn-sm btn-danger pull-right" style="margin-right:10px" href="{{url("/sbe_index")}}">Cancel</a>'
        append = append + '    </div>'
        append = append + '</div>'
        append = append + '            </div>'
        append = append + '        </div>'

        $(".content").append(append)
        $('input[name="qtyItems"]','input[name="qtyItemsImp"]','input[name="qtyItemsMnS"]').attr("type","number")         

        $.ajax({
            url: "{{'/sbe/getLead'}}",
            type: 'GET',success:function(result){
                $("#inputLead").select2({
                    data:result,
                    placeholder:'Select Lead Id',
                })
                // .on('select2:select', function (e) {
                //     var data = e.params.data

                    
                //     $.ajax({
                //         url: "{{'/sbe/getSoWbyLeadID'}}",
                //         data:{
                //             lead_id:data.id
                //         },
                //         type: 'GET',
                //         success:function(result){
                            
                //             if (result != null) {
                //                  $("#textareaSOW").val(result.sow)
                //                 $("#textareaScope").val(result.oos)
                //             }
                //         }
                //     })
                // })
            }
        })

        getDropdownDetailItems(0,"SO")
        getDropdownDetailItems(0,"Imp")
        getDropdownDetailItems(0,"MnS")
        
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var inputValue = $('ul li.active').find("input").val()
            $("#inputDuration").val(inputValue)
            cekTableLength(arrTableSO,arrTableImp,arrTableMnS,"tab")
            $("#inputDuration").css("border-color","#d2d6de")
        });
    }

    function checkInnerHeight(){
        // 
        var configs = $("#configs").innerHeight()
        var temporaryFunction = $("#temporaryFunction").innerHeight()

        if (configs > temporaryFunction) {
            $("#temporaryFunction").innerHeight($("#configs").innerHeight())
        }else{
            $("#configs").innerHeight($("#temporaryFunction").innerHeight())
        }
        
        // if (id == "configs") {
        //     $("#temporaryFunction").innerHeight($("#configs").innerHeight())
        // }

        // if (id == "temporaryFunction") {
        //     $("#configs").innerHeight($("#temporaryFunction").innerHeight())
        // }
        
       
    }

    function getDropdownDetailItems(value,type,item){
        $.ajax({
            url:"{{url('/sbe/getDropdownDetailItem')}}",
            type:"GET",
            success:function(result){
                
                $(".detailItems"+ type +"_"+value).select2({
                    placeholder:"Select Detail Items",
                    data:result.data
                }).on("change",function(e){
                
                    // var priceTotal = 0
                    // var priceGrandTotal = 0
                    // priceTotal += parseFloat($(this).closest("tr").find("td").eq(3).find("input").val().replaceAll(".","")) * $(this).closest("tr").find("td").eq(2).find("input").val() == '' ? 1 : parseFloat($(this).closest("tr").find("td").eq(2).find("input").val()) * $(this).closest("tr").find("td").eq(4).find("input").val() == '' ? 1 : parseFloat($(this).closest("tr").find("td").eq(4).find("input").val())

                    // 

                    // $(this).closest("tr").find("td").eq(5).find("input").val(priceTotal)

                    // $(this).closest("table").closest("tbody tr").each(function(item,result){
                    //     $(this).closest("table").find("input[name='inputGrandTotal']").val(priceGrandTotal)
                    // })

                    // 
                }).on('select2:select', function (e) {
                    var data = e.params.data;
                    
                    $(this).closest("tr").find("td").eq(3).find("input").val(formatter.format(data.price))

                    var priceTotal = 0
                    var priceGrandTotal = 0
                    priceTotal += parseInt(data.price)
                    priceTotal *= $(this).closest("tr").find("td").eq(2).find("input").val() == '' ? 1 : parseInt($(this).closest("tr").find("td").eq(2).find("input").val())  
                    priceTotal *= $(this).closest("tr").find("td").eq(4).find("input").val() == '' ? 1 : parseInt($(this).closest("tr").find("td").eq(4).find("input").val())

                    $(this).closest("tr").find("td").eq(5).find("input").val(formatter.format(priceTotal))

                    $(this).closest("table").find("tbody tr").each(function(item,result){
                        $(result).find(".form-control").each(function(itemInput,resultItem){
                            if(resultItem.name == "totalItems"){
                                priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                                
                            }
                        })
                        $(this).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))
                    })

                });

                if (type == "Update") {
                    $("#tableItemsUpdateConfig_"+item).find(".detailItems"+ type +"_"+ value +":last").next().next().remove()
                }else{
                    $(".detailItems"+ type +"_"+ value +":last").next().next().remove()    
                }
            }
        })
    }

    function changeQtyItems(val){
        
        var priceTotal = 0
        var priceGrandTotal = 0
        priceTotal += $(val).closest("tr").find("td").eq(2).find("input").val() == '' ? parseInt($(val).closest("tr").find("td").eq(3).find("input").val().replaceAll(".","")) : parseInt($(val).closest("tr").find("td").eq(2).find("input").val()) * parseInt($(val).closest("tr").find("td").eq(3).find("input").val().replaceAll(".",""))

        priceTotal *= $(val).closest("tr").find("td").eq(4).find("input").val() == '' ? 1 : parseInt($(val).closest("tr").find("td").eq(4).find("input").val()) 

        // priceTotal *= $(val).closest("tr").find("td").eq(4).find("input").val() == '' ? 1 : parseInt($(val).closest("tr").find("td").eq(4).find("input").val())
        
        $(val).closest("tr").find("td").eq(5).find("input").val(formatter.format(priceTotal))

        $(val).closest("table").find("tbody").each(function(item,result){
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt(resultItem.value.replaceAll(".",""))
                    
                }
            })
        })

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

    }

    function changeManPower(val){
        var priceTotal = 0
        var priceGrandTotal = 0

        priceTotal += $(val).closest("tr").find("td").eq(4).find("input").val() == '' ? parseInt($(val).closest("tr").find("td").eq(3).find("input").val().replaceAll(".","")) : parseInt($(val).closest("tr").find("td").eq(4).find("input").val()) * parseInt($(val).closest("tr").find("td").eq(3).find("input").val().replaceAll(".",""))

        priceTotal *= $(val).closest("tr").find("td").eq(2).find("input").val() == '' ? 1 : parseInt($(val).closest("tr").find("td").eq(2).find("input").val())
        
        $(val).closest("tr").find("td").eq(5).find("input").val(formatter.format(priceTotal))

        $(val).closest("table").find("tbody").each(function(item,result){
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt(resultItem.value.replaceAll(".",""))
                    
                }
            })
        })

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

    }

    function showTemporarySBE(value){
        temporarySBE(value)
        localStorage.setItem('isConfigPage',true)
        $("#btnBack").removeAttr('href')
        $("#btnBack").attr('onclick','showFunctionConfig()')
    }

    function temporarySBE(value){
        $(".content").empty("")
        Pace.restart();
        Pace.track(function() {
            $.ajax({
                type:"GET",
                url:"{{url('/sbe/getDetailConfig')}}",
                data:{
                    id_config_sbe:value
                },success:function(result){
                        var append = ""

                        append = append + '<div class="box">'
                        append = append + '    <div class="box-body">'
                        append = append + '<div class="row">'
                        append = append + '    <div class="col-md-6">'
                        append = append + '        <div class="form-group">'
                        append = append + '            <label>Project Location*</label>'
                        append = append + '            <textarea class="form-control" style="resize: vertical;">'+ result.project_location +'</textarea>'
                        append = append + '        </div>'
                        append = append + '        <div class="form-group">'
                        append = append + '            <label>Duration*</label>'
                        append = append + '            <input type="text" class="form-control" name="" value="'+ result.duration +'">'
                        append = append + '        </div>'
                        append = append + '        <div class="form-group">'
                        append = append + '            <label>Estimated Running*</label>'
                        append = append + '            <input type="text" class="form-control" name="" value="'+ result.estimated_running +'">'
                        append = append + '        </div>'
                        append = append + '    </div>'
                        append = append + '    <div class="col-md-6">'
                        append = append + '        <div class="form-group">'
                        append = append + '            <label>Scope Of Work*</label>'
                        append = append + '            <textarea class="form-control" style="resize: vertical;height: 150px;">'+ result.sow +'</textarea>'
                        append = append + '        </div>'
                        append = append + '        <div class="form-group">'
                        append = append + '            <label>Out Of Scope*</label>'
                        append = append + '            <textarea class="form-control" style="resize: vertical;height: 150px;" row="20">'+ result.oos +'</textarea>'
                        append = append + '        </div>'
                        append = append + '    </div>'
                        append = append + '</div>'
                        append = append + '<div class="row">'
                        append = append + ' <div class="col-md-12 col-xs-12">'
                        append = append + ' <label>Config*</label>'                                     
                        var i = 0, z = 0, j = 0
                        $.each(result.detail_config, function(key, results){
                            i++
                            append = append + ' <table class="table" name="tableShowConfig" id="tableShowConfig_'+ i +'">'
                            append = append + '    <thead>'
                            append = append + '        <tr>'
                            append = append + '            <th>'
                            append = append + '                Items'
                            append = append + '            </th>'
                            append = append + '            <th>'
                            append = append + '                Detail Items'
                            append = append + '            </th>'
                            append = append + '            <th>'
                            append = append + '                Qty'
                            append = append + '            </th>'
                            append = append + '            <th>'
                            append = append + '                Price'
                            append = append + '            </th>'
                            append = append + '            <th>'
                            append = append + '                Manpower'
                            append = append + '            </th>'
                            append = append + '            <th>'
                            append = append + '                Total'
                            append = append + '            </th>'
                            append = append + '        </tr>'
                            append = append + '    </thead>'
                            append = append + '<tbody>'
                            $.each(results, function(keys, resultsDetail){
                                j++
                                append = append + ' <tr>'
                                append = append + '     <td>'
                                append = append + '       <input type="" class="form-control" name="inputItemsTemporary" id="" value="'+ key +'" placeholder="Ex: PM Maintenance">'
                                append = append + '     </td>'
                                append = append + '     <td>'
                                append = append + '       <select type="" style="width: 100%" class="select2 form-control detailItemsTemporary_'+ j +'" name="detailItemsTemporary" id="detailItemsTemporary">'
                                append = append + '             <option></option>'
                                append = append + '       </select>'
                                append = append + '     </td>'
                                append = append + '     <td>'
                                append = append + '         <input type="number" style="width:60px" class="form-control" name="" id="" value="'+ resultsDetail.qty +'">'
                                append = append + '     </td>'
                                append = append + '     <td>'
                                append = append + '         <input type="" class="form-control priceItemsUpdate_'+ j +'" name="" id="" readonly="" value="'+ formatter.format(resultsDetail.price) +'">'
                                append = append + '     </td>'
                                append = append + '     <td>'
                                append = append + '         <input type="" style="width:60px" class="form-control" name="manpowerItemsUpdate" id="" value="'+ resultsDetail.manpower +'">'
                                append = append + '     </td>'
                                append = append + '     <td>'
                                append = append + '         <input type="" class="form-control totalItemsTemporary_'+ j +'" name="totalItems" id="totalItemsUpdate" readonly value="'+ formatter.format(resultsDetail.total_nominal) +'">'
                                append = append + '     </td>'
                                append = append + '     <td></td>'
                                append = append + ' </tr>'

                                $.ajax({
                                    url:"{{url('/sbe/getDropdownDetailItem')}}",
                                    type:"GET",
                                    success:function(result){  
                                        $("#tableShowConfig_"+i).each(function(item,results){
                                            z++ 
                                            console.log(z)
                                            $(".detailItemsTemporary_"+z).select2({
                                                placeholder:"Select Detail Items",
                                                data:result.data,
                                                disabled:true
                                            }).val(resultsDetail.detail_item).trigger("change")
                                        })
                                    }
                                })
                            })
                            append = append + '        </tbody>'
                            append = append + '        <tfoot>'
                            append = append + '          <tr>'
                            append = append + '           <td colspan="4"></td>'
                            append = append + '           <th>Grand Total</th>'
                            append = append + '           <td><input type="text" class="form-control" name="inputGrandTotal" id="inputGrandTotal" readonly></td>'
                            append = append + '          </tr>'
                            append = append + '        </tfoot>'
                            append = append + ' </table>'
                        })
                        append = append + '         </div>'
                        append = append + '             </div>'
                        append = append + '      </div>'
                        append = append + '</div>'

                        $(".content").append(append)

                        var j = 0
                        $.each(result.detail_config, function(key, results){
                            j++
                            var priceGrandTotal = 0
                            $("#tableShowConfig_"+j).find("tbody tr").each(function(item,result){
                                $(result).find(".form-control").each(function(itemInput,resultItem){
                                    if(resultItem.name == "totalItems"){
                                        priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                                    }
                                })
                                $("#tableShowConfig_"+ j).find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))
                            })

                            $("#tableShowConfig_"+j).find("tbody tr:not(:first-child)").each(function(item,result){
                                $(result).find(".form-control").each(function(v,w){
                                    if(w.name == "inputItemsTemporary"){
                                        $(w).hide()
                                    }
                                })
                            })  
                        })

                        $("input,select,textarea").attr("readonly",true)  
                }
            })
        })
    }

    //for create SBE
    //Supply Only
    function addConfigSO(val){
        var countable = $("table[name='tableSO']").length
        var cloneTr = $("#tableItemsSO_"+val).find("tbody tr:first").clone()
        // cloneTr
        cloneTr.find(":input[name='qtyItems']").val('').end().find(":input[name='priceItems']").val('').end().find(":input[name='manpowerItems']").val('').end().find(":input[name='totalItems']").val('').end()
        cloneTr.find(":input[name='InputItems']").hide()
        // cloneTr[0].children[0].firstElementChild.hide()
        cloneTr.find("td:last").html("<button onclick='removeItemsDetailSO(this)' class='btn btn-sm btn-danger' style='width:30px'><i class='fa fa-trash-o'></i></button>")

        cloneTr.children("select")
            .select2("destroy")
            .end()

        $("#tableItemsSO_"+val).append(cloneTr)
        getDropdownDetailItems(val,"SO")
    }

    function removeItemsDetailSO(val){
        var whichtr = val.closest("tr");
        var priceGrandTotal = 0
        $(val).closest("table").find("tbody tr:not(:eq("+$(val).closest('tr').index()+"))").each(function(item,result){
            
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                }

                
            })
        })    

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

        whichtr.remove();  
    }

    var arrTableSO = [], arrTableImp = [], arrTableMnS = []
    function addItemsSO(val){
        var countable = $("table[name='tableSO']").length

        if (countable > 0) {
            arrTableSO.push(countable)
            $(".nav-tabs-custom").find(".alert").remove()
        }

        if ($("table[name='tableSO']").is(":visible") == false) {
            $("table[name='tableSO']").show()
        }else{            
            var append = ""
            append = append + '<table class="table" name="tableSO" id="tableItemsSO_'+ countable +'">'
            append = append + '<thead>'
            append = append + '   <tr>'
            append = append + '        <th>'
            append = append + '            Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Detail Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Qty'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Price'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Manpower'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Total'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '           <button class="btn btn-sm btn-primary" disabled="true" id="btnAddConfigSO" onclick="addConfigSO('+ countable +')" style="width: 30px;"><i class="fa fa-plus"></i></button>'
            append = append + '        </th>'
            append = append + '  </tr>'
            append = append + '</thead>'
            append = append + '     <tbody>'
            append = append + '         <tr>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="InputItems" id="InputItems" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <select type="" style="width: 100%" class="select2 form-control detailItemsSO_'+ countable +'" name="detailItemsSO" id="detailItemsSO">'
            append = append + '                     <option></option>'
            append = append + '                 </select>'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="number" style="width:60px" class="form-control" name="qtyItems" id="qtyItems" onkeyup="changeQtyItems(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="priceItems" id="priceItems" readonly="">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" style="width:60px" class="form-control" name="manpowerItems" id="manpowerItems" onkeyup="changeManPower(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
            append = append + '             </td>'
            append = append + '             <td></td>'
            append = append + '         </tr>'
            append = append + '     </tbody>'
            append = append + '     <tfoot>'
            append = append + '         <tr>'
            append = append + '             <td colspan="4"></td>'
            append = append + '             <th>Grand Total</th>'
            append = append + '             <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
            append = append + '         </tr>'
            append = append + '     </tfoot>'
            append = append + '</table>'

            $("table[name='tableSO']:last").after(append)
            getDropdownDetailItems(countable,"SO")
        }   

        if (countable > 0) {
            if ($("#removeItemsSO").length < 1) {
                $("#addItemsSO").after("<button class='btn btn-sm btn-danger' id='removeItemsSO' onclick='removeItemsSO()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
            }
        }       

    }

    function removeItemsSO(){
        var countable = $("table[name='tableSO']").length
    
        if (countable == 1) {
            $("table[name='tableSO']").hide()
            $("#removeItemsSO").remove()
        }else{
            var whichtr = $("table[name='tableSO']:last");
            whichtr.remove();
        } 
    }

    //Implementation
    function addConfigImp(val){
        var cloneTr = $("#tableItemsImp_"+val).find("tbody tr:first").clone()
        // cloneTr
        cloneTr.find(":input[name='qtyItemsImp']").val('').end().find(":input[name='priceItemsImp']").val('').end().find(":input[name='manpowerItemsImp']").val('').end().find(":input[name='totalItems']").val('').end()
        cloneTr.find(":input[name='InputItemsImp']").hide()
        // cloneTr[0].children[0].firstElementChild.remove()
        cloneTr.find("td:last").html("<button onclick='removeItemsDetailImp(this)' class='btn btn-sm btn-danger' style='width:30px'><i class='fa fa-trash-o'></i></button>")

        cloneTr.children("select")
            .select2("destroy")
            .end()

        $("#tableItemsImp_"+val).append(cloneTr)

        getDropdownDetailItems(val,"Imp")
        $(".detailItemsImp_"+val).last().next().next().remove()  
    }

    function removeItemsDetailImp(val){
        var whichtr = val.closest("tr");
        var priceGrandTotal = 0
        $(val).closest("table").find("tbody tr:not(:eq("+$(val).closest('tr').index()+"))").each(function(item,result){
            
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                }

                
            })
        })    

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

        whichtr.remove();   
    }

    function addItemsImp(val){
        // 
        var countable = $("table[name='tableImp']").length

        if (countable > 0) {
            arrTableImp.push(countable)
            $(".nav-tabs-custom").find(".alert").remove()
        }

        if ($("table[name='tableImp']").is(":visible") == false) {
            $("table[name='tableImp']").show()
        }else{
            var append = ""
            append = append + '<table class="table" name="tableImp" id="tableItemsImp_'+ countable +'">'
            append = append + '<thead>'
            append = append + '   <tr>'
            append = append + '    <th>'
            append = append + '            Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Detail Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Qty'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Price'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Manpower'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Total'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '           <button class="btn btn-sm btn-primary" disabled="true" id="btnAddConfigImp" onclick="addConfigImp('+ countable +')" style="width: 30px;"><i class="fa fa-plus"></i></button>'
            append = append + '   </th>'
            append = append + '  </tr>'
            append = append + '</thead>'
            append = append + '     <tbody>'
            append = append + '         <tr>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="InputItemsImp" id="InputItems" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <select type="" style="width: 100%" class="select2 form-control detailItemsImp_'+ countable +'" name="detailItemsImp" id="detailItemsImp">'
            append = append + '                     <option></option>'
            append = append + '                 </select>'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="number" style="width:60px" class="form-control" name="qtyItemsImp" id="qtyItems" onkeyup="changeQtyItems(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="priceItemsImp" readonly="" id="priceItems">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" style="width:60px" class="form-control" name="manpowerItemsImp" id="manpowerItems" onkeyup="changeManPower(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
            append = append + '             </td>'
            append = append + '             <td></td>'
            append = append + '         </tr>'
            append = append + '     </tbody>'
            append = append + '     <tfoot>'
            append = append + '         <tr>'
            append = append + '             <td colspan="4"></td>'
            append = append + '             <th>Grand Total</th>'
            append = append + '             <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
            append = append + '         </tr>'
            append = append + '     </tfoot>'
            append = append + '</table>'

            // $("select").select2()

            $("table[name='tableImp']:last").after(append)
            getDropdownDetailItems(countable,"Imp")
        }

        if (countable > 0) {
            if ($("#removeItemsImp").length < 1) {
                $("#addItemsImp").after("<button class='btn btn-sm btn-danger' id='removeItemsImp' onclick='removeItemsImp()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
            }
        }
        
    }

    function removeItemsImp(){
        var countable = $("table[name='tableImp']").length

        if (countable == 1) {
            $("table[name='tableImp']").hide()
            $("#removeItemsImp").remove()
        }else{
            var whichtr = $("table[name='tableImp']:last");
            whichtr.remove();
        }
    
    }

    //Maintenance
    function addConfigMnS(val){
        var cloneTr = $("#tableItemsMnS_"+val).find("tbody tr:first").clone()
        // cloneTr
        cloneTr.find(":input[name='qtyItemsMnS']").val('').end().find(":input[name='priceItemsMnS']").val('').end().find(":input[name='manpowerItemsMnS']").val('').end().find(":input[name='totalItems']").val('').end()
        cloneTr.find(":input[name='InputItemsMnS']").hide()
        // cloneTr[0].children[0].firstElementChild.remove()
        cloneTr.find("td:last").html("<button onclick='removeItemsDetailMnS(this)' class='btn btn-sm btn-danger' style='width:30px'><i class='fa fa-trash-o'></i></button>")

        cloneTr.children("select")
            .select2("destroy")
            .end()

        $("#tableItemsMnS_"+val).append(cloneTr)

        getDropdownDetailItems(val,"MnS")
        $(".detailItemsMnS_"+val).last().next().next().remove()   
    }

    function removeItemsDetailMnS(val){
        var whichtr = val.closest("tr");
        var priceGrandTotal = 0
        $(val).closest("table").find("tbody tr:not(:eq("+$(val).closest('tr').index()+"))").each(function(item,result){
            
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                }

                
            })
        })    

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

        whichtr.remove();     
    }

    function addItemsMnS(val){
        // conMnSle.log(val)
        var countable = $("table[name='tableMnS']").length

        if (countable > 0) {
            arrTableMnS.push(countable)
            $(".nav-tabs-custom").find(".alert").remove()
        }

        if ($("table[name='tableMnS']").is(":visible") == false) {
            $("table[name='tableMnS']").show()
        }else{
            var append = ""
            append = append + '<table class="table" name="tableMnS" id="tableItemsMnS_'+ countable +'">'
            append = append + '<thead>'
            append = append + '   <tr>'
            append = append + '    <th>'
            append = append + '            Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Detail Items'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Qty'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Price'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Manpower'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '            Total'
            append = append + '        </th>'
            append = append + '        <th>'
            append = append + '           <button class="btn btn-sm btn-primary" disabled="true" id="btnAddConfigMnS" onclick="addConfigMnS('+ countable +')" style="width: 30px;"><i class="fa fa-plus"></i></button>'
            append = append + '   </th>'
            append = append + '  </tr>'
            append = append + '</thead>'
            append = append + '     <tbody>'
            append = append + '         <tr>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="InputItemsMnS" id="InputItems" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <select type="" style="width: 100%" class="select2 form-control detailItemsMnS_'+ countable +'" name="detailItemsMnS" id="detailItemsMnS">'
            append = append + '                     <option></option>'
            append = append + '                 </select>'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="number" style="width:60px" class="form-control" name="qtyItemsMnS" id="qtyItems" onkeyup="changeQtyItems(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="priceItemsMnS" id="priceItems" readonly="">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" style="width:60px" class="form-control" id="manpowerItems" name="manpowerItemsMnS" onkeyup="changeManPower(this)">'
            append = append + '             </td>'
            append = append + '             <td>'
            append = append + '                 <input type="" class="form-control" name="totalItems" id="totalItems" readonly>'
            append = append + '             </td>'
            append = append + '             <td></td>'
            append = append + '         </tr>'
            append = append + '     </tbody>'
            append = append + '     <tfoot>'
            append = append + '         <tr>'
            append = append + '             <td colspan="4"></td>'
            append = append + '             <th>Grand Total</th>'
            append = append + '             <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
            append = append + '         </tr>'
            append = append + '     </tfoot>'
            append = append + '</table>'
            $("table[name='tableMnS']:last").after(append)
            getDropdownDetailItems(countable,"MnS")
        }

        // $("#addItemsMns").after("<button class='btn btn-sm btn-danger' id='removeItemsMnS' onclick='removeItemsMnS()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
        if (countable > 0) {
            if ($("#removeItemsMnS").length < 1) {
                $("#addItemsMns").after("<button class='btn btn-sm btn-danger' id='removeItemsMnS' onclick='removeItemsMnS()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
            }
        }
    }

    function removeItemsMnS(){
        var countable = $("table[name='tableMnS']").length

        if (countable == 1) {
            $("table[name='tableMnS']").hide()
            $("#removeItemsMnS").remove()
        }else{
            var whichtr = $("table[name='tableMnS']:last");
            whichtr.remove();
        }
    }

    //re-initiate show update config
    function showUpdateConfigSO(value){
        updateConfigSBE(value)
        localStorage.setItem('isConfigPage',true)
        $("#btnBack").removeAttr('href')
        $("#btnBack").attr('onclick','showFunctionConfig()')
    }

    function updateConfigSBE(value){
        $(".content").empty("")

        var append = ""
        $.ajax({
            type:"GET",
            url:"{{url('/sbe/getDetailConfig')}}",
            data:{
                id_config_sbe:value
            },success:function(result){
                append = append + '<div class="box">'
                append = append + '    <div class="box-body">'
                append = append + '<div class="row">'
                append = append + '    <div class="col-md-6">'
                append = append + '        <div class="form-group">'
                append = append + '            <label>Project Location*</label>'
                append = append + '            <textarea class="form-control" style="resize: vertical;" name="textareaLocUpdate" id="textareaLocUpdate">'+ result.project_location +'</textarea>'
                append = append + '        </div>'
                append = append + '        <div class="form-group">'
                append = append + '            <label>Duration*</label>'
                append = append + '            <input type="text" class="form-control" name="inputDurationUpdate" id="inputDurationUpdate" value="'+ result.duration +'">'
                append = append + '        </div>'
                append = append + '        <div class="form-group">'
                append = append + '            <label>Estimated Running*</label>'
                append = append + '            <input type="text" class="form-control" name="inputEstimateRunUpdate" id="inputEstimatedRunUpdate" value="'+ result.estimated_running +'">'
                append = append + '        </div>'
                append = append + '    </div>'
                append = append + '    <div class="col-md-6">'
                append = append + '        <div class="form-group">'
                append = append + '            <label>Scope Of Work*</label>'
                append = append + '            <textarea class="form-control" style="resize: vertical;height: 150px;" name="textareaSOWUpdate" id="textareaSOWUpdate">'+ result.sow +'</textarea>'
                append = append + '        </div>'
                append = append + '        <div class="form-group">'
                append = append + '            <label>Out Of Scope*</label>'
                append = append + '            <textarea class="form-control" style="resize: vertical;height: 150px;" row="20" name="textareaScopeUpdate" id="textareaScopeUpdate">'+ result.oos +'</textarea>'
                append = append + '        </div>'
                append = append + '    </div>'
                append = append + '</div>'
                append = append + '<div class="row">'
                append = append + ' <div class="col-md-12 col-xs-12">'
                append = append + ' <label>Config*</label>'                  
                    var i = 0, j = 0, z = 0, tempInc = 0, tempInc2 = 0
                    // var priceGrandTotalUpdate = 0

                    $.each(result.detail_config, function(key, results){
                        i++
                        append = append + ' <table class="table" name="tableUpdateConfig" id="tableItemsUpdateConfig_'+ i +'">'
                        append = append + '    <thead>'
                        append = append + '        <tr>'
                        append = append + '            <th>'
                        append = append + '                Items'
                        append = append + '            </th>'
                        append = append + '            <th>'
                        append = append + '                Detail Items'
                        append = append + '            </th>'
                        append = append + '            <th>'
                        append = append + '                Qty'
                        append = append + '            </th>'
                        append = append + '            <th>'
                        append = append + '                Price'
                        append = append + '            </th>'
                        append = append + '            <th>'
                        append = append + '                Manpower'
                        append = append + '            </th>'
                        append = append + '            <th>'
                        append = append + '                Total'
                        append = append + '            </th>'
                        append = append + '            <td>'
                        append = append + '                <button class="btn btn-sm btn-primary" id="btnAddUpdateItemsDetail" onclick="updateItemsDetail('+ i +')" style="width: 30px;"><i class="fa fa-plus"></i></button>'
                        append = append + '            </td>'
                        append = append + '        </tr>'
                        append = append + '    </thead>'
                        append = append + '<tbody id="tbodyItems">'
                        $.each(results, function(keys, resultsDetail){
                            
                            j++
                            append = append + ' <tr>'
                            append = append + '     <td>'
                            append = append + '       <input type="" class="form-control" name="inputItemsUpdate" id="inputItemsUpdate" value="'+ key +'" placeholder="Ex: PM Maintenance">'
                            append = append + '     </td>'
                            append = append + '     <td>'
                            append = append + '       <select type="" style="width: 100%" class="select2 form-control detailItemsUpdate_'+ j +'" name="detailItemsUpdate" id="detailItemsUpdate">'
                            append = append + '             <option></option>'
                            append = append + '       </select>'
                            append = append + '     </td>'
                            append = append + '     <td>'
                            append = append + '         <input type="number" style="width:60px" class="form-control qtyItemsUpdate_'+ j +'" name="qtyItemsUpdate" id="qtyItemsUpdate" onkeyup="changeQtyItems(this)" value="'+ resultsDetail.qty +'">'
                            append = append + '     </td>'
                            append = append + '     <td>'
                            append = append + '         <input type="" class="form-control priceItemsUpdate_'+ j +'" name="priceItemsUpdate" id="priceItemsUpdate" readonly="" value="'+ formatter.format(resultsDetail.price) +'">'
                            append = append + '     </td>'
                            append = append + '     <td>'
                            append = append + '         <input type="" style="width:60px" class="form-control manpowerItems_'+ j +'" name="manpowerItemsUpdate" id="manpowerItemsUpdate" onkeyup="changeManPower(this)" value="'+ resultsDetail.manpower +'">'
                            append = append + '     </td>'
                            append = append + '     <td>'
                            append = append + '         <input type="" class="form-control totalItems_'+ j +'" name="totalItems" id="totalItemsUpdate" data-value="'+ i +'" readonly value="'+ formatter.format(resultsDetail.total_nominal) +'">'
                            append = append + '     </td>'
                            append = append + '     <td></td>'
                            append = append + ' </tr>'

                            $.ajax({
                                url:"{{url('/sbe/getDropdownDetailItem')}}",
                                type:"GET",
                                success:function(result){                                  
                                    $("#tableItemsUpdateConfig_"+i).each(function(item,results){
                                    z++ 
                                    
                                        $(".detailItemsUpdate_"+z).select2({
                                            placeholder:"Select Detail Items",
                                            data:result.data
                                        }).on('select2:select', function (e) {
                                        var data = e.params.data;
                                        
                                        $(this).closest("tr").find("td").eq(3).find("input").val(formatter.format(data.price))

                                        var priceTotal = 0
                                        var priceGrandTotal = 0
                                        priceTotal += parseInt(data.price)
                                        priceTotal *= $(this).closest("tr").find("td").eq(2).find("input").val() == '' ? 1 : parseInt($(this).closest("tr").find("td").eq(2).find("input").val())  
                                        priceTotal *= $(this).closest("tr").find("td").eq(4).find("input").val() == '' ? 1 : parseInt($(this).closest("tr").find("td").eq(4).find("input").val())

                                        $(this).closest("tr").find("td").eq(5).find("input").val(formatter.format(priceTotal))

                                        $(this).closest("table").find("tbody tr").each(function(item,result){
                                            $(result).find(".form-control").each(function(itemInput,resultItem){
                                                if(resultItem.name == "totalItems"){
                                                    priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                                                }
                                            })
                                                $(this).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))
                                            })
                                                
                                        })

                                        $(".detailItemsUpdate_"+z).val(resultsDetail.detail_item).trigger("change")
                                    })
                                }
                            })
                        })
                        append = append + '        </tbody>'
                        append = append + '        <tfoot>'
                        append = append + '          <tr>'
                        append = append + '           <td colspan="4"></td>'
                        append = append + '           <th>Grand Total</th>'
                        append = append + '           <td><input type="text" class="form-control" name="inputGrandTotal" id="inputGrandTotal" readonly></td>'
                        append = append + '          </tr>'
                        append = append + '        </tfoot>'
                        append = append + ' </table>'
                    })
                    // append = append + '                    <hr>'

                append = append + '  <div style="text-align:center;">'
                append = append + '     <button class="btn btn-sm bg-purple" id="updateItems" style="margin: 0 auto;" onclick="updateItems(0)"><i class="fa fa-plus"></i>&nbsp Add Items</button>'
                append = append + '  </div>'
                append = append + '    </div>'
                append = append + '</div>'
                    append = append + '     <div class="row">'
                    append = append + '         <div class="col-md-12 col-xs-12">'
                    append = append + '             <button class="btn btn-sm btn-warning pull-right" onclick="updateConfig('+ value +')">Update</button>'
                    append = append + '             <button class="btn btn-sm btn-danger pull-right" style="margin-right:10px" onclick="cancelConfig()">Cancel</button>'
                    append = append + '         </div>'
                    append = append + '     </div>'
                append = append + '      </div>'
                append = append + '</div>'
                
                $(".content").append(append)

                $.each(result.detail_config, function(key, results){
                    tempInc++
                    ++tempInc2

                    var grandPriceTotalUpdatetempInc = 0

                    $("#tableItemsUpdateConfig_"+tempInc).find("tbody tr").each(function(item,result){
                        $(result).find(".form-control").each(function(v,w){
                            if ($(w).attr("data-value") == tempInc){
                                if(w.name == "totalItems"){
                                    $("#tableItemsUpdateConfig_"+tempInc).find("input[name='inputGrandTotal']").val(formatter.format(grandPriceTotalUpdatetempInc += parseInt($(w).attr("data-value",$(w).attr("data-value")).val().replaceAll(".",""))))
                                    
                                }
                                // priceGrandTotalUpdate = 0
                           
                            }
                        })
                    })
                    $("#tableItemsUpdateConfig_"+tempInc2).find("tbody tr:not(:first-child)").each(function(item,result){
                        $(result).find(".form-control").each(function(v,w){
                            if(w.name == "inputItemsUpdate"){
                                $(w).hide()
                            }
                        })
                    })
                })

                
                $('table[name="tableUpdateConfig"] tbody tr:not(:first-child)').each(function(item,result){
                    $(this).find("td").eq(6).html("<button onclick='removeUpdateItemsDetail(this)' class='btn btn-sm btn-danger' style='width:30px'><i class='fa fa-trash-o'></i></button>")
                })

                if ($('table[name="tableUpdateConfig"]').length > 1) {
                    $("#updateItems").after("<button class='btn btn-sm btn-danger' id='removeItemsDetailforUpdate' onclick='removeItemsDetailforUpdate()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
                }
                              

            }
        })
    }

    //updateConfig function
    function updateItemsDetail(val){
        
        // var latestItem = $("#tableItemsUpdateConfig_"+val).find("tbody tr").length

        var cloneTr = $("#tableItemsUpdateConfig_"+val).find("tbody tr:first").clone()
        // cloneTr
        cloneTr.find("#qtyItemsUpdate").val('').end().find("#priceItemsUpdate").val('').end().find("#manpowerItemsUpdate").val('')
        cloneTr.find("#totalItemsUpdate").val('').end()
        cloneTr.find(":input[name='inputItemsUpdate']").hide()
        cloneTr.find("#detailItemsUpdate").removeClass("detailItemsUpdate_0").addClass("detailItemsUpdate_"+val)
        cloneTr.find("td:last").html("<button onclick='removeUpdateItemsDetail(this)' class='btn btn-sm btn-danger' style='width:30px'><i class='fa fa-trash-o'></i></button>")

        cloneTr.children("select")
            .select2("destroy")
            .end()

        $("#tableItemsUpdateConfig_"+val).append(cloneTr)
        getDropdownDetailItems(val,"Update",val)
         
    }

    function removeUpdateItemsDetail(val){
        var whichtr = val.closest("tr");
        var priceGrandTotal = 0
        $(val).closest("table").find("tbody tr:not(:eq("+$(val).closest('tr').index()+"))").each(function(item,result){
            
            $(result).find(".form-control").each(function(itemInput,resultItem){
                if(resultItem.name == "totalItems"){
                    priceGrandTotal += parseInt($(this).closest("tr").find("td").eq(5).find("input").val().replaceAll(".",""))
                }

                
            })
        })    

        $(val).closest("table").find("input[name='inputGrandTotal']").val(formatter.format(priceGrandTotal))

        whichtr.remove(); 
    }

    function updateItems(val){
        // conMnSle.log(val)

        var countable = $("table[name='tableUpdateConfig']").length + 1

        var append = ""
        append = append + '<table class="table" name="tableUpdateConfig" id="tableItemsUpdateConfig_'+ countable +'">'
        append = append + '<thead>'
        append = append + '   <tr>'
        append = append + '        <th>'
        append = append + '            Items'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '            Detail Items'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '            Qty'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '            Price'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '            Manpower'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '            Total'
        append = append + '        </th>'
        append = append + '        <th>'
        append = append + '           <button class="btn btn-sm btn-primary" disabled="true" id="btnAddUpdateItemsDetail" onclick="updateItemsDetail('+ val +')" style="width: 30px;"><i class="fa fa-plus"></i></button>'
        append = append + '        </th>'
        append = append + '  </tr>'
        append = append + '</thead>'
        append = append + '     <tbody>'
        append = append + '         <tr>'
        append = append + '             <td>'
        append = append + '                 <input type="" class="form-control" name="inputItemsUpdate" id="inputItemsUpdate" onkeyup="validateInput(this)" placeholder="Ex: PM Maintenance">'
        append = append + '             </td>'
        append = append + '             <td>'
        append = append + '                 <select type="" style="width: 100%" class="select2 form-control detailItemsUpdate_'+ countable +'" name="detailItemsUpdate" id="detailItemsUpdate">'
        append = append + '                     <option></option>'
        append = append + '                 </select>'
        append = append + '             </td>'
        append = append + '             <td>'
        append = append + '                 <input type="number" style="width:60px" class="form-control" name="qtyItemsUpdate" id="qtyItemsUpdate" onkeyup="changeQtyItems(this)">'
        append = append + '             </td>'
        append = append + '             <td>'
        append = append + '                 <input type="" class="form-control" name="priceItemsUpdate" id="priceItemsUpdate" readonly="">'
        append = append + '             </td>'
        append = append + '             <td>'
        append = append + '                 <input type="" style="width:60px" class="form-control" name="manpowerItemsUpdate" id="manpowerItemsUpdate" onkeyup="changeManPower(this)">'
        append = append + '             </td>'
        append = append + '             <td>'
        append = append + '                 <input type="" class="form-control" name="totalItems" id="totalItemsUpdate" readonly>'
        append = append + '             </td>'
        append = append + '             <td></td>'
        append = append + '         </tr>'
        append = append + '     </tbody>'
        append = append + '     <tfoot>'
        append = append + '         <tr>'
        append = append + '             <td colspan="4"></td>'
        append = append + '             <th>Grand Total</th>'
        append = append + '             <td><input type="text" class="form-control" name="inputGrandTotal" readonly></td>'
        append = append + '         </tr>'
        append = append + '     </tfoot>'
        append = append + '</table>'

        $("table[name='tableUpdateConfig']:last").after(append)
        getDropdownDetailItems(countable,"Update")


        $("#tableItemsUpdateConfig_"+val).find("tr").eq(0).find("td").find("#updateItemsDetail").attr("onclick","updateItemsDetail("+ val +")")

        if (countable > 1) {
            if ($("#removeItemsDetailforUpdate").length < 1) {
                $("#updateItems").after("<button class='btn btn-sm btn-danger' id='removeItemsDetailforUpdate' onclick='removeItemsDetailforUpdate()' style='width:30px;margin-left:10px'><i class='fa fa-trash-o'></i></button>")
            }
        }

    }

    function removeItemsDetailforUpdate(){
        var whichtr = $("table[name='tableUpdateConfig']:last");
        whichtr.remove();

        var countable = $("table[name='tableUpdateConfig']").length

        if (countable == 1) {
            $("#removeItemsDetailforUpdate").remove()
        } 
    }

    //saveCOnfig
    var arrItemSO = [], arrItemImp = [], arrItemMnS = []
    var isReadyStore = false

    function saveConfig(){
        if ($("#inputLead").val() == "") {
            $("#inputLead").next().next().show()
            $("#inputLead").closest("div").addClass("has-error")
        }else if ($("#textareaLoc").val() == "") {
            $("#textareaLoc").next().show()
            $("#textareaLoc").closest("div").addClass("has-error")
        }else if ($("#inputEstimatedRun").val() == "") {
            $("#inputEstimatedRun").next().show()
            $("#inputEstimatedRun").closest("div").addClass("has-error")
        }
        else if ($("#textareaSOW").val() == "") {
            $("#textareaSOW").next().show()
            $("#textareaSOW").closest("div").addClass("has-error")
        }else if ($("#textareaScope").val() == "") {
            $("#textareaScope").next().show()
            $("#textareaScope").closest("div").addClass("has-error")
        }else{
            // var append = ""
            // append = append + ' <div class="alert alert-danger alert-dismissible" style="display:none">'
            // append = append + '      <h4><i class="icon fa fa-ban"></i> Alert!</h4>'
            // append = append + '      Please fill the empty row!'
            // append = append + ' </div>'
            var arrFalse = []
            $("table").each(function(items,results){
                if (results.id.split("_")[0] == "tableItemsSO") {
                    $("#"+results.id).find("tbody tr").each(function(itemTbody,resultTbodyTr){
                        var itemsSO = "", detailItemsSO = "", qtyItemsSO = "", manpowerSO = "", priceItemsSO=""

                        $(resultTbodyTr).each(function(itemInput,resultItemTd){
                            $(resultItemTd).find(".form-control").each(function(itemInput,resultItem){ 
                                if ($("#durationSO").val() != '') {
                                    if(resultItem.id != "totalItems"){
                                        if ($(resultItem).val() == "") {
                                            isReadyStore = false
                                            arrFalse.push($(resultItem).val())

                                            $("table[name='tableSO']:first").closest("div").prepend('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>Please fill the empty row!</div>')
                                            $(".alert-danger:not(:first-child)").remove()
                                        }else{
                                            if (resultItem.name == 'InputItems') {
                                                itemsSO = resultItem.value
                                            }

                                            if (resultItem.name == 'detailItemsSO') {
                                                detailItemsSO = resultItem.value
                                            }

                                            if (resultItem.name == 'qtyItems') {
                                                qtyItemsSO = resultItem.value
                                            }

                                            if (resultItem.name == 'priceItems') {
                                                priceItemsSO = resultItem.value
                                            }

                                            if (resultItem.name == "manpowerItems") {
                                                manpowerSO = resultItem.value
                                            }

                                        }
                                    }
                                    
                                }
                            })
                            
                        })

                
                        if ($("#durationSO").val() != "") {
                            arrItemSO.push({items:itemsSO,detailItems:detailItemsSO,qtyItems:qtyItemsSO,priceItems:priceItemsSO,manpower:manpowerSO})                            
                        }
                        
                        // readyToPost(arrItemSO)
                    })
                }

                if (results.id.split("_")[0] == "tableItemsImp") {
                    $("#"+results.id).find("tbody tr").each(function(itemTbody,resultTbodyTr){
                        
                        var itemsImp = "", detailItemsImp = "", qtyItemsImp = "", manpowerImp = "", priceItemsImp=""

                        $(resultTbodyTr).each(function(itemInput,resultItemTd){
                            $(resultItemTd).find(".form-control").each(function(itemInput,resultItem){ 
                                if ($("#durationImp").val() != '') {
                                    if(resultItem.id != "totalItems"){
                                        if ($(resultItem).val() == "") {
                                            isReadyStore = false
                                            arrFalse.push($(resultItem).val())

                                            $("table[name='tableImp']:first").closest("div").prepend('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>Please fill the empty row!</div>')
                                            $(".alert-danger:not(:first-child)").remove()
                                        }else{
                                            if (resultItem.name == 'InputItemsImp') {
                                                itemsImp = resultItem.value
                                            }

                                            if (resultItem.name == 'detailItemsImp') {
                                                detailItemsImp = resultItem.value
                                            }

                                            if (resultItem.name == 'qtyItemsImp') {
                                                qtyItemsImp = resultItem.value
                                            }

                                            if (resultItem.name == 'priceItemsImp') {
                                                priceItemsImp = resultItem.value
                                            }

                                            if (resultItem.name == "manpowerItemsImp") {
                                                manpowerImp = resultItem.value
                                            }
                                        }
                                    }
                                }
                            })
                        })

                        if ($("#durationImp").val() != '') {
                            arrItemImp.push({items:itemsImp,detailItems:detailItemsImp,qtyItems:qtyItemsImp,priceItems:priceItemsImp,manpower:manpowerImp})
                        }                        
                    })
                }

                if(results.id.split("_")[0] == "tableItemsMnS"){
                    $("#"+results.id).find("tbody tr").each(function(itemTbody,resultTbodyTr){
                        var itemsMnS = "", detailItemsMnS = "", qtyItemsMnS = "", manpowerMnS = "", priceItemsMnS=""

                        $(resultTbodyTr).each(function(itemInput,resultItemTd){
                            $(resultItemTd).find(".form-control").each(function(itemInput,resultItem){ 
                                if ($("#durationMnS").val() != '') {
                                    if(resultItem.id != "totalItems"){
                                        console.log($(resultItemTd).find(".form-control").length)
                                        if ($(resultItem).val() == "") {
                                            isReadyStore = false
                                            arrFalse.push($(resultItem).val())
                                            // console.log($(resultItemTd).length)
                                            $("table[name='tableMnS']:first").closest("div").prepend('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>Please fill the empty row!</div>')
                                            $(".alert-danger:not(:first-child)").remove()
                                            console.log("ada yang kosong")
                                        }else{
                                            if (resultItem.name == 'InputItemsMnS') {
                                                itemsMnS = resultItem.value
                                            }

                                            if (resultItem.name == 'detailItemsMnS') {
                                                detailItemsMnS = resultItem.value
                                            }

                                            if (resultItem.name == 'qtyItemsMnS') {
                                                qtyItemsMnS = resultItem.value
                                            }

                                            if (resultItem.name == 'priceItemsMnS') {
                                                priceItemsMnS = resultItem.value
                                            }

                                            if (resultItem.name == "manpowerItemsMnS") {
                                                manpowerMnS = resultItem.value
                                            }

                                        }
                                    }
                                }
                            })
                        })

                        if ($("#durationMnS").val() != '') {
                            arrItemMnS.push({items:itemsMnS,detailItems:detailItemsMnS,qtyItems:qtyItemsMnS,priceItems:priceItemsMnS,manpower:manpowerMnS})                        
                        }                        
                    })
                }
                
            })
            
            if(arrFalse.length == 0){
                isReadyStore = true
            }else{
                arrItemSO = []
                arrItemImp = []
                arrItemMnS = []
            }

            formData = new FormData
            formData.append("_token","{{ csrf_token() }}")      
            // formData.append("id_sbe",window.location.href.split("/")[6].split("?")[0])  
            formData.append("inputLead",$("#inputLead").val())
            formData.append("textareaLoc",$("#textareaLoc").val())     
            formData.append("inputDuration",$("#inputDuration").val())     
            formData.append("inputEstimatedRun",$("#inputEstimatedRun").val())     
            formData.append("textareaSOW",$("#textareaSOW").val())     
            formData.append("textareaScope",$("#textareaScope").val())  
            formData.append("durationSO",$("#durationSO").val())  
            formData.append("durationImp",$("#durationImp").val())  
            formData.append("durationMnS",$("#durationMnS").val())  
            formData.append("arrItemSO",JSON.stringify(arrItemSO)) 
            formData.append("arrItemImp",JSON.stringify(arrItemImp)) 
            formData.append("arrItemMnS",JSON.stringify(arrItemMnS))

            swalFireCustom = {
              title: 'Are you sure?',
              text: "Submit Config for Temporary SBE",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
            }

            swalSuccess = {
                icon: 'success',
                title: 'Config has been created!',
                text: 'Click Ok to reload page',
            }

            var url = "/sbe/createConfig"

            cekTableLength(arrTableSO,arrTableImp,arrTableMnS,isReadyStore,formData,swalFireCustom,swalSuccess,url)
        }
        
    }

    function cekTableLength(arrTableSO,arrTableImp,arrTableMnS,isReadyStore,formData,swalFireCustom,swalSuccess,url){
        if (arrTableSO.length > 0 || arrTableImp.length > 0 || arrTableMnS.length > 0) {
            if ($("#inputDuration").val() == "") {
                $("#inputDuration").css("border-color","red")
            }else{
                if (isReadyStore == true) {
                    createPost(swalFireCustom,formData,swalSuccess,url)
                }
            }
        }else{
            if (isReadyStore != "tab") {
               $(".nav-tabs-custom").prepend('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>Please Add Config!</div>') 
           }
           
        }
    }

    var arrItems = []
    function updateConfig(id_config_sbe){            
        if ($("#textareaLocUpdate").val() == "") {
            $("#textareaLocUpdate").next().show()
            $("#textareaLocUpdate").closest("div").addClass("has-error")
        }else if ($("#inputDurationUpdate").val() == "") {
            $("#inputDurationUpdate").next().show()
            $("#inputDurationUpdate").closest("div").addClass("has-error")
        }else if ($("#inputEstimatedRunUpdate").val() == "") {
            $("#inputEstimatedRunUpdate").next().show()
            $("#inputEstimatedRunUpdate").closest("div").addClass("has-error")
        }else if ($("#textareaSOWUpdate").val() == "") {
            $("#textareaSOWUpdate").next().show()
            $("#textareaSOWUpdate").closest("div").addClass("has-error")
        }else if ($("#textareaScopeUpdate").val() == "") {
            $("#textareaScopeUpdate").next().show()
            $("#textareaScopeUpdate").closest("div").addClass("has-error")
        }else{
            var arrFalse = [], itemsUpdateConfig = "", detailItemsUpdateConfig = "", qtyItemsUpdateConfig = "", manpowerUpdateConfig = "", priceItemsUpdateConfig = ""
            $("table").each(function(items,results){
                if (results.id.split("_")[0] == "tableItemsUpdateConfig") {
                    $("#"+results.id).find("tbody tr").each(function(itemTbody,resultTbodyTr){
                        //
                        $(resultTbodyTr).each(function(itemInput,resultItemTd){
                            $(resultItemTd).find(".form-control").each(function(itemInput,resultItem){ 
                                if (resultItem.name != "totalItems") {
                                    if ($(resultItem).val() == "") {

                                        $("table[name='tableUpdateConfig']:first").closest("div").prepend('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>Please fill the empty row!</div>')
                                        $(".alert-danger:not(:first-child)").remove()

                                        isReadyStore = false
                                        arrFalse.push($(resultItem).val())
                                    }else{
                                        if (resultItem.name == 'inputItemsUpdate') {
                                            itemsUpdateConfig = resultItem.value
                                        }

                                        if (resultItem.name == 'detailItemsUpdate') {
                                            detailItemsUpdateConfig = resultItem.value
                                        }

                                        if (resultItem.name == 'qtyItemsUpdate') {
                                            qtyItemsUpdateConfig = resultItem.value
                                        }

                                        if (resultItem.name == "manpowerItemsUpdate") {
                                            manpowerUpdateConfig = resultItem.value
                                        }

                                        if (resultItem.name == 'priceItemsUpdate') {
                                            priceItemsUpdateConfig = resultItem.value
                                        }
                                    }
                                }
                            })
                        }) 
                        // if ($("table[name='tableUpdateConfig']").is(":visible") == true) {
                            
                        // }
                        arrItems.push({items:itemsUpdateConfig,detailItems:detailItemsUpdateConfig,qtyItems:qtyItemsUpdateConfig,priceItems:priceItemsUpdateConfig,manpower:manpowerUpdateConfig})  
                    })
                }                
            })
            
            console.log(arrFalse)
            if(arrFalse.length == 0){
                isReadyStore = true
                arrTableSO.push(['update'])
            }else{
                arrItems = []
            }

            console.log(isReadyStore)
            cekTableLength(arrTableSO,arrTableImp,arrTableMnS,isReadyStore)
        }
    

        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")      
        formData.append("id_sbe",window.location.href.split("/")[4].split("?")[0])
        formData.append("id_config_sbe",id_config_sbe)  
        // formData.append("inputLead",$("#inputLead").val())
        formData.append("textareaLocUpdate",$("#textareaLocUpdate").val())     
        formData.append("inputDurationUpdate",$("#inputDurationUpdate").val())     
        formData.append("inputEstimatedRunUpdate",$("#inputEstimatedRunUpdate").val())     
        formData.append("textareaSOWUpdate",$("#textareaSOWUpdate").val())     
        formData.append("textareaScopeUpdate",$("#textareaScopeUpdate").val())   
        formData.append("arrItemsUpdate",JSON.stringify(arrItems))
        
        swalFireCustom = {
          title: 'Are you sure?',
          text: "Update Config for Temporary SBE",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Config has been updated!',
            text: 'Click Ok to reload page',
        }           

        url="/sbe/updateConfig"

        cekTableLength(arrTableSO,arrTableImp,arrTableMnS,isReadyStore,formData,swalFireCustom,swalSuccess,url)
    }

    //postEvent
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
                    if (url == "/sbe/createConfig") {
                        window.location.href = "{{url('/sbe_index')}}"
                    }else{
                        location.reload()
                    }
                  }
                })
              }
            })
          }else{
            if (url == "/sbe/resetVersion") {
                $(".content").empty("")
                showFunctionConfig()
            }
          }
      })
    }

    function addNotes(){
        $("#ModalNotes").modal("show")
    }

    function validateInput(val){
        if ($(val).is("select")) {
            if (val.value != "") {
                $("#"+val.id).next().next().hide()
                $("#"+val.id).closest("div").removeClass("has-error")
            }
        }else{
            if (val.value != "") {
                $("#"+val.id).next().hide()
                $("#"+val.id).closest("div").removeClass("has-error")

                console.log(val.value.length)
                if (val.name == "inputDuration") {
                    if (val.value.length == 1) {
                        $('ul li.active').find("input").val("")  
                    }else{
                        $('ul li.active').find("input").val(val.value)  
                    }
                    $(val).css("border-color","#d2d6de")                  
                }

                if (val.name == "InputItems" || val.name == "InputItemsImp" || val.name == "InputItemsMnS" || val.name == "inputItemsUpdate") {
                    // $("#btnAddConfigSO").prop("disabled",false)
                    // 
                    if (val.value.length > 1) {
                        $(val).closest("table").find("tr")[0].cells[6].children[0].disabled = false
                        // $("#btnAddConfigSO").prop("disabled",false)
                    }else{
                        $(val).closest("table").find("tr")[0].cells[6].children[0].disabled = true
                        // $("#btnAddConfigSO").prop("disabled",true)
                    }
                }
            }
        }
    }

    function btnSendNotes(){
        if ($("#textareaNotes").val() == "") {
            $("#textareaNotes").closest("div").find("span").show()
            $("#textareaNotes").closest("div").addClass("has-error")
        }else{
            formData = new FormData
            formData.append("_token","{{ csrf_token() }}")  
            formData.append("inputNotes",$("#textareaNotes").val())
            formData.append("id_sbe",window.location.href.split("/")[4].split("?")[0]) 

            swalFireCustom = {
              title: 'Are you sure?',
              text: "Send Notes",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
            }

            swalSuccess = {
                icon: 'success',
                title: 'Notes has been send!',
                text: 'Click Ok to reload page',
            }           

            createPost(swalFireCustom,formData,swalSuccess,url="/sbe/storeNotes")
        } 
    }

    function resetConfig(){
        $("input[type='radio']:checked").each(function(index,items){
            $(items).prop("checked",false)
        })

        $("#boxConfigTemp").empty("")
        showEmptyConfig()       

        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("id_sbe",window.location.href.split("/")[4].split("?")[0]) 

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Reset Config function, send to Sol Manager!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'Reset Config has been successfully!',
            text: 'Click Ok to reload page',
        }          

        createPost(swalFireCustom,formData,swalSuccess,url="/sbe/resetVersion")
    }

    function showEmptyConfig(){
        var appendTemporary = ""

        appendTemporary = appendTemporary + '<div class="row">'
        appendTemporary = appendTemporary + '            <div class="col-md-3 col-xs-12" style="text-align:-webkit-center">'
        appendTemporary = appendTemporary + '              <a>'
        appendTemporary = appendTemporary + '                <div style="width: 100px;height: 100px;background-color: blue;color: white;text-align: center;vertical-align: middle;display: flex;">'
        appendTemporary = appendTemporary + '                    <span style="'
        appendTemporary = appendTemporary + '                        margin: auto;'
        appendTemporary = appendTemporary + '                        display: flex;'
        appendTemporary = appendTemporary + '                        text-align: center;'
        appendTemporary = appendTemporary + '                        align-items: center;cursor:pointer">X</span>'
        appendTemporary = appendTemporary + '                </div>'
        appendTemporary = appendTemporary + '              </a>'
        appendTemporary = appendTemporary + '            </div>'
        appendTemporary = appendTemporary + '            <div class="col-md-9 col-xs-12">'
        appendTemporary = appendTemporary + '                <div class="table-responsive">'
        appendTemporary = appendTemporary + '                    <table class="table table-bordered" width="100%">'
        appendTemporary = appendTemporary + '                        <thead>'
        appendTemporary = appendTemporary + '                            <tr>'
        appendTemporary = appendTemporary + '                                <th>No</th>'
        appendTemporary = appendTemporary + '                                <th>Function</th>'
        appendTemporary = appendTemporary + '                                <th>Total</th>'
        appendTemporary = appendTemporary + '                            </tr>'
        appendTemporary = appendTemporary + '                        </thead>'
        appendTemporary = appendTemporary + '                        <tbody>'
        appendTemporary = appendTemporary + '          <tr>'
        appendTemporary = appendTemporary + '              <td>-</td>'
        appendTemporary = appendTemporary + '              <td>-</td>'
        appendTemporary = appendTemporary + '              <td>-</td>'
        appendTemporary = appendTemporary + '          </tr>'
        appendTemporary = appendTemporary + '      </tbody>'
        appendTemporary = appendTemporary + '      <tfoot>'
        appendTemporary = appendTemporary + '          <tr>'
        appendTemporary = appendTemporary + '              <th colspan="2" style="text-align:right">Grand Total</th>'
        appendTemporary = appendTemporary + '              <th>-</th>'
        appendTemporary = appendTemporary + '          </tr>'
        appendTemporary = appendTemporary + '                        </tfoot>'
        appendTemporary = appendTemporary + '                    </table>'
        appendTemporary = appendTemporary + '                </div>'
        appendTemporary = appendTemporary + '            </div>'
        appendTemporary = appendTemporary + '        </div>'

        $("#boxConfigTemp").append(appendTemporary)
        // checkInnerHeight()
    }

    function saveFuncConf(){
        var arrVersion = []

        $("input[type='radio']:checked").each(function(index,items){
            arrVersion.push(items.value)
        })

        if (arrVersion.length < 1) {
            Swal.fire(
              'Warning!',
              'Please select minimal 1 config!',
              'warning'
            )
        }else{
            formData = new FormData
            formData.append("_token","{{ csrf_token() }}")
            formData.append("arrVersion",JSON.stringify(arrVersion))    

            swalFireCustom = {
              title: 'Are you sure?',
              text: "Save this config function, send to Sol Manager!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
            }

            swalSuccess = {
                icon: 'success',
                title: 'Config function has been saved!',
                text: 'Click Ok to reload page',
            }           

            createPost(swalFireCustom,formData,swalSuccess,url="/sbe/updateVersion")
        }

        
    }

    function generatePDF(){
        formData = new FormData
        formData.append("_token","{{ csrf_token() }}")
        formData.append("id_sbe",window.location.href.split("/")[4].split("?")[0]) 

        swalFireCustom = {
          title: 'Are you sure?',
          text: "Generate SBE!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }

        swalSuccess = {
            icon: 'success',
            title: 'SBE has been generated!',
            text: 'Click Ok to reload page',
        }           

        createPost(swalFireCustom,formData,swalSuccess,url="/sbe/uploadPdfConfig")
    }
</script>
@endsection