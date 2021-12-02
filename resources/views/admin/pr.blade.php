@extends('template.main')
@section('tittle')
  Purchase Request Number
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">
  <style type="text/css">
    th {
      text-align: center;
    }

    td>.truncate{
      word-break:break-all;
      white-space: normal;
      width:200px;  
    }

    .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }

    .btnPR{
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
      width: 170px;
      padding-top: 4px;
      padding-left: 10px;
    }

    .DTFC_LeftBodyLiner {
      overflow: hidden;
    }

    .pace .pace-progress {
      background: #ffffff;
      position: fixed;
      z-index: 2000;
      top: 0;
      right: 100%;
      width: 100%;
      height: 2px;
    }

    .select2-selection__choice {
      color: white;
    }
  </style>
@endsection
@section('content')

<section class="content-header">
  <h1>
    Daftar Buku Admin (PR)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Purchase Request</li>
  </ol>
</section>

<section class="content">

  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number PR :<h4> {{$pops->no_pr}}</h4></div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success" id="alert">
      {{ session('alert') }}
    </div>
  @endif

  <div class="row" id="BoxId">
      <!--box id-->
  </div>

  <div class="box">
    <div class="box-header with-border">
      <div class="pull-left">
        <select style="margin-right: 5px;width: 100px" class="form-control btn-primary fa" id="year_filter">
          <option value="{{$tahun}}">&#xf073 &nbsp{{$tahun}}</option>
          @foreach($year_before as $years)
            @if($years->year != $tahun)
              <option value="{{$years->year}}">&#xf073 &nbsp{{$years->year}}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 200px;color: white"><i class="fa fa-plus"> </i>&nbsp Number Purchase Request</button>
          <button class="btn btn-warning" onclick="exportPr('{{action('PrController@downloadExcelPr')}}')" style="margin-right: 10px;"><i class="fa fa-print"></i>Excel</button>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped dataTable nowrap" id="data_pr" width="100%" cellspacing="0">
          <thead>
            <tr  style="text-align: center;">
              <th>No</th>
              <th></th>
              <th></th>
              <th></th>
              <th>Date</th>
              <th>Category</th>
              <th>To</th>
              <th><div class="truncate">Attention</div></th>
              <th><div class="truncate">Title/Subject</div></th>
              <th><div class="truncate">Description</div></th>
              <th>From</th>
              <th></th>
              <th>Issuance</th>
              <th>Project ID</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
            <tr id="status">
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!--MODAL ADD PROJECT-->
  <div class="modal fade" id="modal_pr" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Number Purchase Request</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/store_pr')}}" id="modal_pr" name="modal_pr">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Type of Letter</label>
                  <select type="text" class="form-control" name="type" id="type" required onchange="selectType(this.value)">
                      <option value="">Select Type of Letter</option>
                      <option value="IPR">IPR (Internal Purchase Request)</option>
                      <option value="EPR">EPR (Eksternal Purchase Request)</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Category</label>
                  <select type="text" class="form-control select2" name="category" id="category_pr" required style="width: 100%">
                      <option value="">Select Category</option>
                      <option value="Barang dan Jasa">Barang dan Jasa</option>
                      <option value="Barang">Barang</option>
                      <option value="Jasa">Jasa</option>
                      <option value="Bank Garansi">Bank Garansi</option>
                      <option value="Service">Service</option>
                      <option value="Pajak Kendaraan">Pajak Kendaraan</option>
                      <option value="ATK">ATK</option>
                      <option value="Aset">Aset</option>
                      <option value="Tinta">Tinta</option>
                      <option value="Training">Training</option>
                      <option value="Ujian">Ujian</option>
                      <option value="Tiket">Tiket</option>
                      <option value="Akomodasi">Akomodasi</option>
                      <option value="Swab Test">Swab Test</option>
                      <option value="Iklan">Iklan</option>
                      <option value="Ekspedisi">Ekspedisi</option>
                      <option value="Legal">Legal</option>
                      <option value="Other">Other</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group" id="pid" style="display: none;">
              <label for="">Project ID</label>                
              <select type="text" class="form-control select2" name="project_id" id="project_id" style="width: 100%">
                <option value="">Select project id</option>
                @foreach($pid as $data)
                <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                @endforeach
              </select>
              <span id="makeId" style="cursor: pointer;">other?</span>
            </div>

            <div class="form-group" id="project_idNew" style="display: none;">
              <div class="input-group">
                <input type="text" class="form-control pull-left col-md-8" placeholder="input Project ID" name="project_idInputNew" id="projectIdInputNew">
                <span class="input-group-addon" style="cursor: pointer;" id="removeNewId"><i class="glyphicon glyphicon-remove"></i></span>
              </div>
            </div> 

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>From</label>
                  <select type="text" class="form-control" name="from_user" required id="from_user" style="width: 100%">
                    <option value="">Select From</option>
                    @foreach($user as $data)
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Position</label>
                  <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                      <option>PMO</option>
                      <option>PRE</option>
                      <option>MSM</option>
                      <option>SAL</option>
                      <option>FIN</option>
                      <option>HRD</option>
                      <option>WHO</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Date</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right date" name="date" id="date_pr" required>
              </div>
            </div>

            <div class="form-group">
              <label for="">To (Customer, Distributor, Pihak External)</label>
              <input type="text" class="form-control" placeholder="ex. PT. Sinergy Informasi Pratama" name="to" id="to" required>
            </div> 

            <div class="form-group">
              <label for="">Attention/PIC (Customer, DIstributor, Pihak External)</label>
              <input type="text" class="form-control" placeholder="ex. Rama Agastya" name="attention" id="attention" >
            </div> 

            <div class="form-group">
              <label for="">Title/Subject</label>
              <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" >
            </div>
            <!-- <div class="form-group">
              <label for="">Project</label>
              <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project" >
            </div> -->
            <div class="form-group">
              <label for="">Description</label>
              <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
            </div>
            <!-- <div class="form-group">
              <label for="">Division</label>
              <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                  <option>PMO</option>
                  <option>TECHNICAL</option>
                  <option>MSM</option>
                  <option>SAL</option>
                  <option>FIN</option>
                  <option>HRD</option>
                  <option>WHO</option>
              </select>
            </div> -->
            <div class="form-group">
              <label for="">Amount</label>
              <input type="text" class="form-control" value="0.00" name="amount" id="amount" required>
            </div>            
                         
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div>

  <!--Modal Edit-->
  <div class="modal fade" id="modaledit" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Purchase Request</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/update_pr')}}" id="modaledit" name="modaledit">
              @csrf
            <input type="text" placeholder="Enter No PR" name="edit_no_pr" id="edit_no_pr" hidden>
            <div class="form-group">
              <label for="">To</label>
              <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to" >
            </div>
            <div class="form-group">
              <label for="">Attention</label>
              <input type="text" class="form-control" placeholder="Enter Attention" name="edit_attention" id="edit_attention" >
            </div> 
            <div class="form-group">
              <label for="">Title</label>
              <input type="text" class="form-control" placeholder="Enter Title" name="edit_title" id="edit_title" >
            </div> 
            <div class="form-group">
              <label for="">Description</label>
              <textarea type="text" class="form-control" placeholder="Enter Description" name="edit_description" id="edit_description" > </textarea>
            </div>
            <div class="form-group">
              <label for="">Amount</label>
              <input type="text" class="form-control" placeholder="Enter Amount" name="edit_amount" id="edit_amount">
            </div>
            <div class="form-group">
              <label for="">Project ID</label>
              <input type="text" class="form-control" placeholder="Enter Project Id" name="edit_project_id" id="edit_project_id">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select type="text" class="form-control" name="edit_status" id="edit_status" required>
                  <option value="On Progress">On Progress</option>
                  <option value="Pending">Pending</option>
                  <option value="Done">Done</option>
                  <option value="Cancel">Cancel</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
          </form>
          </div>
        </div>
      </div>
  </div>
    
</section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">

    $("#amount").click(function() {
      var inputLength = $("#amount").val().length;
      setCaretToPos($("#amount")[0], inputLength)
    });

    var options = {
      onKeyPress: function(cep, e, field, options){
          if (cep.length<=6)
          {
           
            var inputVal = parseFloat(cep);
            jQuery('#amount').val(inputVal.toFixed(2));
          }
        
          // setCaretToPos(jQuery('#money')[0], 4);
                       
          var masks = ['#,##0.00', '0.00'];
          mask = (cep == 0) ? masks[1] : masks[0];
          $('#amount').mask(mask, options);
      },
      reverse: true
    };

    $('#amount').mask('#,##0.00', options);

    function setCaretToPos(input, pos) {
      setSelectionRange(input, pos, pos);
    }

    function selectType(val){
      console.log(val)
      if (val == 'IPR') {
        $("#pid").css("display", "none");
      }else if(val == 'EPR'){
        $("#pid").css("display", "block");
      } else {
        $("#pid").css("display", "none");
      }
    }

    $('#makeId').click(function(){
      $('#project_idNew').show()
      $('#project_id').val("").select2().trigger("change")
    })

    $('#removeNewId').click(function(){
      $('#project_idNew').hide('slow')
      $('#projectIdInputNew').val('')
    })

    $('#project_id').select2({
      dropdownParent:$("#modal_pr")
    })

    $('#category_pr').select2({
      dropdownParent:$("#modal_pr")
    });

    $('#from_user').select2({
      dropdownParent:$("#modal_pr")
    });

    $('#date_pr').datepicker({
      autoclose: true,
    }).attr('readonly','readonly').css('background-color','#fff');

    function edit_pr(no,to,attention,title,description,amount,project_id,status) {
      console.log(amount)
      $('#modaledit').modal('show');
      $('#edit_no_pr').val(no);
      $('#edit_to').val(to);
      if (attention == "null") {
        '';
      } else {
        $('#edit_attention').val(attention);
      }

      if (title == "null") {
        '';
      } else {
        $('#edit_title').val(title);
      }

      if (description == "null") {
        '';
      } else {
        $('#edit_description').val(description);
      }

      if (amount == "null") {
        '';
      } else {
        $("#edit_amount").mask('000,000,000,000.00', {reverse: true})
        $('#edit_amount').val(amount.toString()).trigger("input")
      }

      if (project_id == "null") {
        '';
      } else {
        $('#edit_project_id').val(project_id);
      }

      $('#edit_status').val(status);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });


    function initfilter(){
      $('.search_filter').select2();
    }


    $("#data_pr").DataTable({
      "ajax":{
        "type":"GET",
        "url":"{{url('getdatapr')}}",
        "dataSrc": function (json){

          json.data.forEach(function(data,index){
            if("{{Auth::User()->nik}}" == data.issuance_nik && data.status != 'Done') {
              var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.description+ '","' +data.amount+ '","' +data.project_id+ '","' +data.status+ '"'
              data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_pr(" + x + ")'>&nbsp Edit</button>";
            } else {
              data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
            }
              
          });
          return json.data;
          
        }
      },
      "columns": [
        { "data": "no_pr" },
        { "data": "type_of_letter"},
        { "data": "category"},
        { "data": "to"},
        { "data": "date" },
        { "data": "category"},
        { "data": "to" },
        {
           "render": function ( data, type, row, meta ) {
              if(row.attention == null){
                return '<div class="truncate"> - </div>'
              } else {
                return '<div class="truncate">' + row.attention + '</div>'
              }
            }
        },
        {
           "render": function ( data, type, row, meta ) {
              if (row.title == null) {
                return '<div class="truncate"> - </div>'
              } else {
                return '<div class="truncate">' + row.title + '</div>'                  
              }
            }
        },
        {
           "render": function ( data, type, row, meta ) {
              if (row.description == null) {
                return '<div class="truncate"> - </div>'
              } else {
                return '<div class="truncate">' + row.description + '</div>'                  
              }
            }
        },
        { "data": "user_from" },
        { "data": "status" },
        { "data": "issuance" },
        {
           "render": function ( data, type, row, meta ) {
              if (row.project_id == null) {
                return '<div class="truncate"> - </div>'
              } else {
                return '<div class="truncate">' + row.project_id + '</div>'                  
              }
            }
        },
        { 
          render: function ( data, type, row ) {
            return new Intl.NumberFormat('id').format(row.amount)
          }
        },
        
        { "data": "status" },
        {
          "className": 'btn_edit',
          "orderable": false,
          "data": "btn_edit",
          "defaultContent": ''
        },
      ],
      'columnDefs' : [
          { 'visible': false, 'targets': [1,2,3,11] }
      ],
      "order": [[ 0, "desc" ]],
      "responsive":true,
      "orderCellsTop": true,
      "pageLength": 20,
      initComplete: function () {
        this.api().columns([[1],[2],[3],[11]]).every( function () {
          var column = this;
          var title = $(this).text();
          var select = $('<select class="form-control search_filter" id="kat_drop" style="width:100%" name="kat_drop" ><option value="" selected>Show All '+ title +'</option></select>')
              .appendTo($("#status").find("th").eq(column.index()))
              .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
              $(this).val());                                     
              column.search(val ? '^' + val + '$' : '', true, false).draw();
          });

          console.log(select);
          column.data().unique().sort().each(function (d, j) {
              select.append('<option>' + d + '</option>')
          });

        });
        initfilter();
      },
      "scrollX": true,
      fixedColumns:   {
        leftColumns: 1
      },
    })

    $($.fn.dataTable.tables( true ) ).css('width', '100%');
    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    
    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    var countPr = []
    var nominalPr = []

    $(document).ready(function(){   
      var year = $('#year_filter').val();
      console.log(year);
      var i = 0
      var colors = []
      var prepend = ""
      var ArrColors = [
        // {
        //     name: 'PR', color: 'bg-purple', icon: 'fa fa-list-ul',index: 0
        // },
        // {
        //     name: 'IPR', color: 'bg-orange', icon: 'fa fa-list-ul',index: 1
        // },
        {
            name: 'PR', color: 'bg-aqua', icon: 'fa fa-list-ul',index: 2
        },
        {
            name: 'IPR', color: 'bg-yellow', icon: 'fa fa-signal',index: 3
        },
        {
            name: 'EPR', color: 'bg-green', icon: 'fa fa-files-o',index: 4
        },
        {
            name: '-', color: 'bg-red', icon: "fa fa-plus",index: 5
        }
      ]

      colors.push(ArrColors)

      // $.each(colors[0], function(key, value){
      //   prepend = prepend + '<div class="col-lg-2 col-xs-12">'
      //       prepend = prepend + '<div class="info-box ' + value.color + '">'
      //           prepend = prepend + '<span class="info-box-icon">'
      //               prepend = prepend + '<i class="'+ value.icon +'"></i>'
      //           prepend = prepend + '</span>'
      //           prepend = prepend + '<div class="info-box-content">'
      //               prepend = prepend + '<span class="info-box-text">'
      //                   prepend = prepend + '<h4>'+ value.name +'</h4>'
      //               prepend = prepend + '</span>'
      //               prepend = prepend + '<span class="info-box-number">'
      //                   prepend = prepend + '<h4 class="counter" id="count_pr_'+value.index+'"></h4>'
      //               prepend = prepend + '</span>'
      //           prepend = prepend + '</div>'
      //       prepend = prepend + '</div>'
      //   prepend = prepend + '</div>'  

      //   id = "count_pr_"+value.index
      //   $("#count_pr_"+value.index).mask("000.000.000.000,00", {reverse: true});
      //   countPr.push(id)
      // })

      $.each(colors[0], function(key, value){
        prepend = prepend + '<div class="col-lg-3 col-xs-6">'
            prepend = prepend + '<div class="small-box ' + value.color + '">'
                prepend = prepend + '<div class="inner">'
                    prepend = prepend + '<div class="txt_serif stats_item_number">'
                    prepend = prepend + '<h3>'+ value.name +' <small style="color:white" id="count_pr_'+value.index+'">-</small></h3>'
                        prepend = prepend + '<p>Rp. <span class="counter" id="nominal_pr_'+value.index+'"></span></p>'
                    prepend = prepend + '</div>'
                prepend = prepend + '</div>'
                prepend = prepend + '<div class="icon">'
                    prepend = prepend + '<i class="'+ value.icon +'"></i>'
                    prepend = prepend + '</div>'
                prepend = prepend + '<div class="small-box-footer"> . </div>' 
                prepend = prepend + '</div>'
            prepend = prepend + '</div>'
        prepend = prepend + '</div>'  

        id = "count_pr_"+value.index
        nominal = "nominal_pr_"+value.index
        $("#count_pr_"+value.index).mask("000.000.000.000.000,00", {reverse: true});
        countPr.push(id)
        nominalPr.push(nominal)
      })

      $("#BoxId").prepend(prepend)

      dashboardCount(year)
    })

    $("#money").mask("000.000.000.000.000,00", {reverse: true});

    function dashboardCount(year){  
      Pace.restart();
      Pace.track(function() {
        $.ajax({
          type:"GET",
          url:"{{url('/getCountPr')}}",
          data:{
            year:year,
          },
          success:function(result){
              $("#"+countPr[0]).text(result.all[0])
              $("#"+countPr[1]).text(result.ipr[0])
              $("#"+countPr[2]).text(result.epr[0])
              $("#"+nominalPr[0]).text(new Intl.NumberFormat('id').format(result.all[1]));
              $("#"+nominalPr[1]).text(new Intl.NumberFormat('id').format(result.ipr[1]));
              $("#"+nominalPr[2]).text(new Intl.NumberFormat('id').format(result.epr[1]));
          }
        })
      })
    }

    $("#year_filter").change(function(){
      $('#data_pr').DataTable().ajax.url("{{url('getfilteryearpr')}}?data=" + this.value).load();
      dashboardCount(this.value);
    });

    function exportPr(url){
      window.location = url + "?year=" + $("#year_filter").val();
    }
  </script>
@endsection
