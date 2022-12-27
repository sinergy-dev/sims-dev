@extends('template.main')
@section('tittle')
Partnership
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
  <style type="text/css">
    .pace .pace-progress {
      background: #ffffff;
      position: fixed;
      z-index: 2000;
      top: 0;
      right: 100%;
      width: 100%;
      height: 2px;
    }

    .dataTables_filter {
     display: none;
    }

    .nav-tabs .badge{
      position: absolute;
      top: -10px;
      right: -10px;
      background: red;
    }

    .small-box h2 {
      font-size: 50px;
      font-weight: bold;
      margin: 20px 0 0 0;
      /*white-space: nowrap;*/
      padding: 0;
    }

    .small-box h4 {
      word-break: break-all;
      word-spacing: 999px;
      font-size: 30px;
      font-weight: bold;
    }

    .vcenter {
      display: flex;
      vertical-align: middle;
      float: right;
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }

    .dt-aligment-middle{
      vertical-align: middle;
      text-align: center;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 120px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {background-color: #ddd;}

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {display: block;}

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {background-color: #F0AD4E;}    
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Partnership
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Partnership</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-primary" id="alert">
          {{ session('success') }}
      </div>
    @endif

    @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-table"></i> Partnership</h3>
<!--           <div class="pull-left">
            <button type="button" class="btn btn-primary pull-right float-right margin-left-custom" id="btnAdd" onclick="showTabAdd(0)" style="display: none;" style="width: 120px;"><i class="fa fa-plus"> </i> &nbspPartnership
              </button>  
            <div class="pull-right dropdown" style="margin-right: 5px;display: none;" id="divExport">
              <button class="btn btn-success"><i class="fa fa-download"> </i>&nbspExport</button>
              <div class="dropdown-content">
                <a href="{{action('PartnershipController@downloadpdf')}}" style="cursor:pointer">PDF</a>
                <a onclick="exportExcel()" style="cursor:pointer">Excel</a>
              </div>
            </div> 
          </div> -->
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" id="tabDashboard" data-toggle="tab" aria-expanded="true">Dashboard</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" id="tabList_partnership" aria-expanded="false">List Partnership</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                  <div class="row" id="BoxId">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Total Certificate per Partner</h3>
                        </div>
                        <div class="chart-container" style="position: relative;padding: 20px;border-radius: 5px;">
                          <canvas id="brandPieChart"></canvas>
                        </div>
                      </div>
                      
                    </div>
                    <div class="col-md-6">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Total Sales/Engineer Certification</h3>
                        </div>
                        <div class="chart-container" style="position: relative;padding: 20px;border-radius: 5px;">
                          <canvas id="certPieChart"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">                     
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <i class="ion ion-arrow-graph-up-right"></i> 
                          <h3 class="box-title">Need Attention</h3>
                        </div>
                        <div class="box-body">
                          <ul class="attention-list products-list product-list-in-box">
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">                      
                      <div class="box box-primary">
                        <div class="box-header">
                          <i class="ion ion-clipboard"></i>

                          <h3 class="box-title">To Do List</h3>
                        </div>
                        <div class="box-body">
                          <ul class="todo-list">
                          </ul>
                        </div>
                      </div> 
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tab_2">
                  <div class="row">
                    <div class="col-md-8 pull-left" style="margin-bottom: 0px; margin-top: 0px;"> 
                      <button type="button" class="btn btn-primary margin-left-custom" id="btnAdd" onclick="showTabAdd(0)" style="display: none;" style="width: 120px;"><i class="fa fa-plus"> </i> &nbspPartnership
                        </button>  
                      <div class="dropdown" style="margin-right: 5px;display: none;" id="divExport">
                        <button class="btn btn-success"><i class="fa fa-download"> </i>&nbspExport</button>
                        <div class="dropdown-content">
                          <!-- <a href="{{action('PartnershipController@downloadpdf')}}" style="cursor:pointer">PDF</a> -->
                          <a onclick="exportExcel()" style="cursor:pointer">Excel</a>
                        </div>
                      </div>           
                    </div>
                    <div class="col-md-4 text-right" style="margin-bottom: 0px; margin-top: 0px;">
                      <div class="input-group pull-right">
                        <input id="searchPartnership" type="text" class="form-control" onkeyup="searchCustom('tablePartnerhip','searchPartnership')" placeholder="Search Anything">             
                        <div class="input-group-btn">
                          <button type="button" id="btnShowPartnership" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Show 10 entries
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" onclick="$('#tablePartnerhip').DataTable().page.len(10).draw();$('#btnShowPartnership').html('Show 10 entries')">10</a></li>
                            <li><a href="#" onclick="$('#tablePartnerhip').DataTable().page.len(25).draw();$('#btnShowPartnership').html('Show 25 entries')">25</a></li>
                            <li><a href="#" onclick="$('#tablePartnerhip').DataTable().page.len(50).draw();$('#btnShowPartnership').html('Show 50 entries')">50</a></li>
                            <li><a href="#" onclick="$('#tablePartnerhip').DataTable().page.len(100).draw();$('#btnShowPartnership').html('Show 100 entries')">100</a></li>
                          </ul>
                        </div>
                        <span class="input-group-btn">
                          <button onclick="searchCustom('tablePartnerhip','searchPartnership')" type="button" class="btn btn-default btn-flat">
                            <i class="fa fa-fw fa-search"></i>
                          </button>
                        </span>
                      </div>
                    </div>  
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="tablePartnerhip" style="width: 100%;" cellspacing="0">
                      <thead>
                        <tr>
                          <th hidden>No</th>
                          <th>Partnership Name</th>
                          <th>Type (Distributor or Principal)</th>
                          <th>Level</th>
                          <th>Renewal Date</th>
                          <th>Target Progress</th>
                          <th>Number of Certification</th>
                          <th hidden></th>
                          <th id="th-action">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>      

    <!--MODAL-->

    <div class="modal fade" id="uploadFile" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Upload File .pdf</h4>
          </div>

          <div class="modal-body">
            <form action="/upload/proses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <input type="text" id="upload_id_partnership" name="upload_id_partnership" hidden>
                <input type="text" id="upload_doc" name="upload_doc" hidden>
              </div>
              <div class="form-group">
                <input type="file" id="file" name="file">
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

    <!--MODAL ADD INCIDENT-->
    <div class="modal fade" id="modalAddPartnership" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content modal-md">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" class="close" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Partnership</h4>
            </div>
            <div class="modal-body">
              <form enctype="multipart/form-data" id="modalAdd" name="modalAdd">
                @csrf

                <div class="tab" style="display: none;">
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Basic Information</h4>
                      <hr>
                    </div>                    
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Type<span style="color:red">*</span></label>
                        <select class="form-control" id="type" name="type" onchange="checkType()">
                            <option value="">Select Type</option>
                            <option value="Distributor">Distributor</option>
                            <option value="Principal">Principal</option>
                            <!-- <option value="Other">Other</option> -->
                        </select>
                        <span class="help-block" style="display:none;">Please Choose Type!</span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Partner<span style="color:red">*</span></label>
                        <input class="form-control" placeholder="ex: Cisco" id="partner" name="partner" onkeyup="fillInput('partner')">
                        <span class="help-block" style="display:none;">Please Fill Partner Name!</span>
                      </div>  
                    </div>
                  </div>           

                  <div class="form-group">
                      <label>List Level<span style="color:red">*</span></label>
                      <input class="form-control" placeholder="ex: Beginner, Intermediate, Advanced" id="levelling" name="levelling" onkeyup="fillInput('levelling')">
                      <span class="help-block" style="display:none;">Please Fill Levelling!</span>
                  </div>

                  <div class="form-group">
                      <label>Current Level<span style="color:red">*</span></label>
                      <input class="form-control" placeholder="ex: Beginner" id="level" name="level" onkeyup="fillInput('level')">
                      <span class="help-block" style="display:none;">Please Fill Level!</span>
                  </div>

                  <div class="form-group">
                      <label>Renewal Date<span style="color:red">*</span></label>
                      <input type="date" class="form-control" id="renewal_date" name="renewal_date" onchange="fillInput('renewal')">
                      <span class="help-block" style="display:none;">Please Fill Renewal Date!</span>
                  </div>

                  <div class="form-group">
                      <label>Annual Fee</label>
                      <input class="form-control" placeholder="ex: USD 2.000" id="annual_fee" name="annual_fee">
                  </div>

                  <div class="form-group">
                    <label>Partner Portal URL</label>
                    <input class="form-control" placeholder="ex: https://www.cisco.com/c/en_id/partners.html" id="portal_partner" name="portal_partner">
                  </div>

                  <div class="form-group">
                    <label>ID Mitra</label>
                    <input class="form-control" placeholder="ex: PartnerId 12345" id="id_mitra" name="id_mitra">
                  </div>

                  <div class="form-group">
                    <label>Technology</label>
                    <select class="form-control" id="id_technology" name="id_technology" style="width: 100%;"></select>
                  </div>

                 <!--  <div class="form-group">
                      <label>Sales Target</label>
                      <input class="form-control" placeholder="Enter Sales Target" id="sales_target" name="sales_target">
                  </div> -->
                </div>
                <div class="tab" style="display:none;">
                  <div class="row">
                    <div class="col-md-12">
                      <h4>CAM (Country Account Manager) Information</h4>
                      <hr>
                    </div>                    
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM</label>
                          <input class="form-control" placeholder="Enter CAM" id="cam" name="cam">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM Phone</label>
                          <input class="form-control" placeholder="Enter CAM Phone" id="cam_phone" name="cam_phone">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM Email</label>
                          <input class="form-control" placeholder="Enter CAM Email" id="cam_email" name="cam_email">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>Email Support</label>
                          <input class="form-control" placeholder="Enter Email Support" id="email_support" name="email_support">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab" style="display: none;">
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Certification & Target/Requirement</h4>
                      <hr>
                    </div>                    
                  </div>
                  <i class="fa fa-table"></i><label>&nbspCertification list</label> 
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Certificate Level</th>
                        <th>Certificate Name</th>
                        <th>Person</th>
                        <td class="text-center">
                          <button class="btn btn-xs btn-primary" onclick="addListCert()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
                            <i class="fa fa-plus"></i>
                          </button> 
                        </td>
                      </tr>
                    </thead>
                    <tbody id="tbListCert">
                    </tbody>
                  </table>
                  <i class="fa fa-table"></i><label>&nbspTarget</label> 
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Target</th>
                        <th>Countable</th>
                        <!-- <th>Description</th> -->
                        <td class="text-center">
                          <button class="btn btn-xs btn-primary" onclick="addSalesTarget()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
                            <i class="fa fa-plus"></i>
                          </button> 
                        </td>
                      </tr>
                    </thead>
                    <tbody id="tbSalesTarget">
                    </tbody>
                  </table>
                </div>         
                 
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev()">Back</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev()">Next</button>
                </div>
            </form>
            </div>
          </div>
        </div>
    </div>
  
  <!--MODAL EDIT INCIDENT-->
  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Partnership</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_partnership')}}" id="modalEditPartnership" name="modalEditPartnership">
            @csrf

            <input type="text" name="edit_id" id="edit_id" hidden>

            <div class="form-group">
                <label>Type</label>
                <select class="form-control" id="edit_type" name="edit_type" required>
                      <option value="Distributor">Distributor</option>
                      <option value="Principal">Principal</option>
                      <!-- <option value="Other">Other</option> -->
                  </select>
            </div>

            <div class="form-group">
                <label>Partner</label>
                <input class="form-control" id="edit_partner" name="edit_partner" required>
            </div>

            <div class="form-group">
                <label>Level</label>
                <input class="form-control" id="edit_level" name="edit_level" required>
            </div>

            <div class="form-group">
                <label>Renewal Date</label>
                <input type="date" class="form-control" id="edit_renewal_date" name="edit_renewal_date">
            </div>

            <div class="form-group">
                <label>Annual Fee</label>
                <input class="form-control" id="edit_annual_fee" name="edit_annual_fee">
            </div>

            <div class="form-group">
                <label>Target</label>
                <input class="form-control" id="edit_sales_target" name="edit_sales_target">
            </div>

            <div class="form-group">
                <label>Sales Certification</label>
                <input class="form-control" id="edit_sales_certification" name="edit_sales_certification">
            </div>

            <div class="form-group">
                <label>Engineer Certification</label>
                <input class="form-control" id="edit_engineer_certification" name="edit_engineer_certification">
            </div>
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  </section>

@endsection

@section('scriptImport')
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
 <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
 <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
 <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
@endsection
@section('script')  
  <script type="text/javascript">
    $(document).ready(function(){
      var accesable = @json($feature_item);
      accesable.forEach(function(item,index){
        $("#" + item).show()
      })
      Pace.restart();
      Pace.track(function() {
          showTablePartnership(accesable)
      })
      chart()
      box()
      showTodo()
      showAttention()

      $('.nav-tabs a[href="#tab_2"]').tab('show');

    })

    function chart(){
      var pieBrand = document.getElementById("brandPieChart");
      var pieCertificate = document.getElementById("certPieChart");

    
      $.ajax({
        type:"GET",
        url:"{{url('/partnership/getCertByBrand')}}",
        success:function(result){
          var totalCertBrand = new Chart(pieBrand, {
            type: 'pie',
            data: {
              labels: result.label,
              indexLabel: "#percent%",
              percentFormatString: "#0.##",
              toolTipContent: "{y} (#percent%)",
              datasets: [{
                data: result.percentage,
                backgroundColor: ["#4589ff","#a56eff","#002d9c","#009d9a","#fa4d56","#9f1853","#f1c21b","#ff832b","#570408","#8a3800"],
              }],
            },
            options: {
              legend: {
                display: true
              },
              tooltips: {
                mode: 'label',
                label: 'mylabel',
                callbacks: {
                  label: function(tooltipItem, data) {
                    var percent = data['datasets'][0]['data'][tooltipItem['index']]
                    return percent + "%"
                    // return console.log(tooltipItem)
                  },
                },
              },
            },
          });
        }
      }) 

      $.ajax({
        type:"GET",
        url:"{{url('/partnership/getCertByCategory')}}",
        success:function(result){
          var totalCertBrand = new Chart(pieCertificate, {
            type: 'pie',
            data: {
              labels: result.label,
              indexLabel: "#percent%",
              percentFormatString: "#0.##",
              toolTipContent: "{y} (#percent%)",
              datasets: [{
                data: result.percentage,
                backgroundColor: ["#4589ff","#a56eff","#002d9c","#009d9a","#fa4d56","#9f1853","#f1c21b","#ff832b","#570408","#8a3800"],
              }],
            },
            options: {
              legend: {
                display: true
              },
              tooltips: {
                mode: 'label',
                label: 'mylabel',
                callbacks: {
                  label: function(tooltipItem, data) {
                    var percent = data['datasets'][0]['data'][tooltipItem['index']]
                    return percent + "%"
                    // return console.log(tooltipItem)
                  },
                },
              },
            },
          });
        }
      })
    }

    function box(){
      $.ajax({
        type:"GET",
        url:"{{url('/partnership/getCountDashboard')}}",
        success: function(result){
          // console.log(colors)
          var prepend = ''
          $.each(result, function(key, value){
            console.log(value)
            prepend = prepend + '<div class="col-lg-4 col-xs-6">'
              prepend = prepend + '<div class="small-box '+ value.color +'" style="height: 100px;border-radius: 5px;">'
                prepend = prepend + '<div class="row">'
                  prepend = prepend + '<div class="col-md-7">'
                  prepend = prepend + '<h4 style="margin-left: 20px;font-size: x-large;font-weight: 700;">'+ value.title +'</h4>'
                  prepend = prepend + '</div>'
                  prepend = prepend + '<div class="col-md-5 vcenter">'
                  prepend = prepend + '<h2 style="font-size: xxx-large;font-weight: bold;">'+ value.count+'</h2>'
                  prepend = prepend + '</div>'
                prepend = prepend + '</div>'
              prepend = prepend + '</div>'
            prepend = prepend + '</div>'
          })

          $("#BoxId").prepend(prepend)

          // $('.counter').each(function () {
          //   var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
          //   $(this).prop('Counter', 0).animate({
          //     Counter: $(this).text()
          //   }, {
          //     duration: 5000,
          //     step: function (func) {
          //        $(this).text(parseFloat(func).toFixed(size));
          //     }
          //   });
          // });
        }
      })
    }

    function showTodo(){
      var appendList = ""
      $.ajax({
        type:"GET",
        url:"{{url('/partnership/getToDoList')}}",
        success: function(result){
          $('.todo-list').empty("")
          $.each(result.data, function(key,value){
              if(value.status == 'Done'){
                status = 'done'
              }else{
                status = ''
              }

              appendList = appendList + '<li class="' + status + ' ">'
                appendList = appendList + ' <span class="handle">'
                  appendList = appendList + '   <i class="fa fa-ellipsis-v"></i>'
                    appendList = appendList + '   <i class="fa fa-ellipsis-v"></i>'
                    appendList = appendList + ' </span>'
                    appendList = appendList + ' <input style="display:inline" id="checklistBox" type="checkbox" class="checked-'+ key + '" data-value="'+ value.id +'">'
                    appendList = appendList + ' <span style="display:inline-block;word-wrap:break-word;width:1060px" class="text" id="textList" data-value='+key+'>' + '<b>[' + value.partner + ']</b> - ' + value.target + ' - ' + value.countable + '</span>'
                    appendList = appendList + ' <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span > '+ ' created at ' + value.created_at +' </span> '
                  appendList = appendList + ' <small class="label label-warning status-'+ key + ' pull-right">'+ value.status +'</small>'
              appendList = appendList + '</li>'

              return value.id
          })            
          $('.todo-list').append(appendList)

          var accesable = @json($feature_item);

          $.each(result.data, function(key,value){
            if(accesable.includes('checklistBox')){
              $("#checklistBox[data-value='"+ value.id +"']").prop('disabled',false)

            }else{
              $("#checklistBox[data-value='"+ value.id +"']").prop('disabled',true)
            }
          })

          $.each(result.data,function(key,value){
            if(value.status == "Done"){
              $(".checked-"+key).attr("checked","true")
              $(".checked-"+key).prop("disabled",true)
            }

            $('.todo-list').todoList({
              onCheck  : function () {
                $.ajax({
                  type:"POST",
                  url:"{{url('/partnership/updateStatusTarget')}}",
                  data:{
                    _token:"{{csrf_token()}}",
                    id:$(this).data('value')
                  }
                })
                $(".checked-"+key).prop("disabled",true)
                $("small.status-"+key).text("Done")

                window.console.log($(this), 'The element has been checked');

              },
              onUnCheck: function () {
                window.console.log($(this), 'The element has been unchecked');
              }
            });
          })        

          $('.todo-list').sortable({
            placeholder         : 'sort-highlight',
            handle              : '.handle',
            forcePlaceholderSize: true,
            zIndex              : 999999
          });

        },
      }) 
    }

    function showAttention(){
      $.ajax({
        type:"GET",
        url:"{{url('/partnership/getNeedAttention')}}",
        success: function(result){
          // console.log(colors)
          var prepend = ''
          $.each(result.data, function(key, value){
            console.log(value)
            prepend = prepend + '<li class="item">'
              prepend = prepend + ' <div class="product-img">'
                prepend = prepend + '<img class="img-responsive img-circle" style="border:solid #3c8dbc 1.5px" src="{{asset("image/logo_partnership")}}/'+ value.logo+'" alt="Yuki" style="width: 50px;height:50px;position: relative;">'
                  prepend = prepend + '</div>'
                  prepend = prepend + '<div class="product-info">'
                  prepend = prepend + '<a href="{{url("partnership_detail/")}}/'+ value.id_partnership +'" class="product-title">' + value.partner 
                  prepend = prepend + '<h4>'
                  prepend = prepend + '<span class="label label-warning pull-right">'+ value.level +'</span></a>'
                  prepend = prepend + '</h4>'
                  prepend = prepend + '<span class="product-description">'
                  prepend = prepend + moment(value.renewal_date).format("Do MMMM YYYY")
                prepend = prepend + '</span>'
              prepend = prepend + '</div>'
            prepend = prepend + '</li>'
          })

          $(".attention-list").prepend(prepend)

          if (result.data.length > 0) {
            $("#tabDashboard").append('<span class="badge">'+ result.data.length +'</span>')
          }
        }
      })
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
       $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
    });

    function searchCustom(id_table,id_seach_bar){
      $("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
    }

    function exportExcel(){
      Swal.fire({
        title: 'Are you sure?',
        text: "Make sure there is nothing wrong to get this report!",
        icon: "warning",
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        timer: 2000,
      }).then((result) => {
        if (result.value){
          Swal.fire({
            title: 'Please Wait..!',
            text: "Prossesing Data Report",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              popup: 'border-radius-0',
            },
            onOpen: () => {
              Swal.showLoading()
            }
          })

          $.ajax({
            type:"GET",
            url:"{{url('/downloadExcelPartnership')}}",
            success: function(result){
              Swal.hideLoading()
              if(result == 0){
                Swal.fire({
                  //icon: 'error',
                  title: 'Success!',
                  text: "The file is unavailable",
                  type: 'error',
                  //confirmButtonText: '<a style="color:#fff;" href="report/' + result.slice(1) + '">Get Report</a>',
                })
              }else{
                Swal.fire({
                  title: 'Success!',
                  text: "You can get your file now",
                  type: 'success',
                  confirmButtonText: '<a style="color:#fff;" href="report/partnership/' + result + '">Get Report</a>',
                })
              }
            }
          })        
        }
      })
    }

    function showTablePartnership(accesable){
      var table = $('#tablePartnerhip').DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('/partnership/getDataPartnership')}}",
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            },
            "visible":false
          },
          { "data": "partner" },
          { "data": "type" },
          { "data": "level"},
          { "data": "renewal_date"},
          { "data": "target"},
          { 
            render: function (data, type, row, meta){
              var append = ""
              $.each(row.total_cert,function(key,value){
                append += "<span>"+row.total_cert[key].combine+"</span>"
              })
              return append;         
            },
            "orderData":[7]
          },
          {
            "data":"total_cert_integer.total_cert",
            "targets":[6],
            "visible":false,
            "searchable":true
          },//number of certification
          { 
            render: function (data, type, row, meta){
              return "<td><a href='{{url('/partnership_detail')}}/"+row.id_partnership+"'><button class='btn btn-primary btn-xs'><i class='fa fa-search-plus'></i> Detail</button></a>&nbsp<button class='btn btn-xs btn-danger btnDeletePartnership' style='vertical-align: top;width: 60px;display:none' onclick='deletePartnership("+ row.id_partnership +")'><i class='fa fa-trash'></i>&nbspDelete</button></td>"            
            }
          },//action  
        ],
        "initComplete": function () {
          accesable.forEach(function(item,index){
            $("." + item).show()
          })                    
        },
        columnDefs: [
          { 
            className: "dt-aligment-middle", targets: [1,2,3,4,5,6] 
          }
        ],
        "order": [7, 'desc'],
        "scrollX": true,
        "pageLength":25,
        "visible":false,
        "searchable":true,
        // "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        // "bInfo": false,
        "fixedHeader": true
      })

      if (!(accesable.includes('th-action'))) {
        var column1 = table.column(7);
        column1.visible(!column1.visible());
      }else{
        var column1 = table.column(7);
        column1.visible(column1.visible());
      }
    }  
    
    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
       $("#alert").slideUp(300);
    });

    $('.money').mask('000,000,000,000,000.00', {reverse: true});

    function deletePartnership(id){
      Swal.fire({
        title: 'Delete Certificate User',
        text: "Are you sure?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: 'Please Wait..!',
            text: "It's updating..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              popup: 'border-radius-0',
            },
            onOpen: () => {
              Swal.showLoading()
            }
          })
          $.ajax({
              url: "{{url('/delete_partnership')}}",
              type: 'post',
              data:{
                "_token": "{{ csrf_token() }}",
                id:id
              },
            success: function(data)
            {
                Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                })
            }
          }); 
        }    
      })
    }

    function partnership(id_partnership,type,partner,level,renewal_date,annual_fee,sales_target,sales_certification,engineer_certification) {
      $('#edit_id').val(id_partnership);
      $('#edit_type').val(type);
      $('#edit_partner').val(partner);
      $('#edit_level').val(level);
      $('#edit_renewal_date').val(renewal_date);
      $('#edit_annual_fee').val(annual_fee);
      $('#edit_sales_target').val(sales_target);
      $('#edit_sales_certification').val(sales_certification);
      $('#edit_engineer_certification').val(engineer_certification);
    }

    function upload(id_partnership, doc) {
      $('#upload_id_partnership').val(id_partnership);
      $('#upload_doc').val(doc);
    }

    var i = 0;
    function addListCert(){
      i++;
      var append = ""
      append = append + "<tr class='new-list'>"
      append = append + " <td>"
      append = append + " <input data-value='" + i + "' name='cert_type[]' id='cert_type' class='form-control' type='text' placeholder='Ex: Engineer - Profesional'>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + "<input data-value='" + i + "' name='cert_name[]' id='cert_name' class='form-control' type='text' placeholder='Ex: 350-401 ENCOR'>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <select class='select2 form-control select2-person' data-value='" + i + "' id='select2-person' style='width: 100%!important' name='cert_person[]'></select> "
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash-list'>"
      append = append + " <i class='fa fa-trash'></i>"
      append = append + " </button>"
      append = append + " </td>"
      append = append + "</tr>"
      
      $("#tbListCert").append(append)
      $.ajax({
        url: "{{url('/partnership/getUser')}}",
        type: "GET",
        success: function(result) {
          var arr = result.data;
          var selectOption = [];
          var otherOption;

          var data = {
            id: '',
            text: 'Select Person'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          $("#select2-person[data-value='" + i + "']").select2({
              dropdownParent: $('#modalAddPartnership'),
              data: selectOption
          })
        }
      }) 
    }

    $.ajax({
      url: "{{url('/project/getTechTag')}}",
      type: "GET",
      success: function(result) {
        $("#id_technology").select2({
            dropdownParent: $('#modalAddPartnership'),
            placeholder: "Select Technology",
            data: result.results,
            multiple:true
        })
      }
    }) 

    function addSalesTarget(){
      i++;
      var append = ""
      append = append + "<tr class='new-list'>"
      append = append + " <td>"
      append = append + " <input data-value='" + i + "' name='sales_target[]' id='sales_target' class='form-control' type='text' placeholder='ex: Renewal Cisco Gold Partner'>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + "<input data-value='" + i + "' name='countable[]' id='countable' class='form-control' type='text' placeholder='es: USD 2.00 or 4 Specialist'>"
      append = append + " </td>"
      append = append + " </td>"
      // append = append + " <td>"
      // append = append + "<input data-value='" + i + "' name='description[]' id='description' class='form-control' type='text' placeholder='Enter description'>"
      // append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash-list'>"
      append = append + " <i class='fa fa-trash'></i>"
      append = append + " </button>"
      append = append + " </td>"
      append = append + "</tr>"
      
      $("#tbSalesTarget").append(append)
    }

    $(document).on('click', '.btn-trash-list', function() {
      $(this).closest("tr").remove();
    });

    var currentTab = 0

    $('#btnAdd').click(function(n){
      showTabAdd(0)
      $('#modalAddPartnership').modal({
          backdrop: 'static',
          keyboard: false
      });
    });

    function showTabAdd(n){
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "inline";
      for (var i = 0; i < x.length; i++){
          x[n].style.display = "inline";
      }


      if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";  
        $("#nextBtn").attr('onclick','submitBtnAdd()');
        for (var i = 0; i < x.length; i++){
          x[n].style.display = "inline";
        }       
      } else {
        $("#nextBtn").attr('onclick','nextPrev(1)');
        $("#prevBtn").attr('onclick','nextPrev(-1)')
        document.getElementById("nextBtn").innerHTML = "Next";
        $("#nextBtn").prop("disabled",false)
      }
    } 

    function nextPrev(n){
      if ($("#type").val() == "") {
        $("#type").closest('.form-group').addClass('has-error')
        $("#type").closest('select').next('span').show();
        $("#type").prev('.input-group-addon').css("background-color","red");
      }else if ($("#partner").val() == "") {
        $("#partner").closest('.form-group').addClass('has-error')
        $("#partner").closest('input').next('span').show();
        $("#partner").prev('.input-group-addon').css("background-color","red");
      }else if ($("#levelling").val() == "") {
        $("#levelling").closest('.form-group').addClass('has-error')
        $("#levelling").closest('input').next('span').show();
        $("#levelling").prev('.input-group-addon').css("background-color","red");
      }else if ($("#level").val() == "") {
        $("#level").closest('.form-group').addClass('has-error')
        $("#level").closest('input').next('span').show();
        $("#level").prev('.input-group-addon').css("background-color","red");
      }else if($("#renewal_date").val() == "") {
        $("#renewal_date").closest('.form-group').addClass('has-error')
        $("#renewal_date").closest('input').next('span').show();
        $("#renewal_date").prev('.input-group-addon').css("background-color","red");
      }else{
        var x = document.getElementsByClassName("tab");

        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
          x[n].style.display = "none";
          currentTab = 0;
        } 
        showTabAdd(currentTab);
      }
      
    }

    function checkType(val){
      if (val != "") {
        $("#type").closest('.form-group').removeClass('has-error')
        $("#type").closest('select').next('span').hide();
        $("#type").prev('.input-group-addon').css("background-color","red");
      }
    }

    function fillInput(val){
      if (val == "levelling") {
        $("#levelling").closest('.form-group').removeClass('has-error')
        $("#levelling").closest('input').next('span').hide();
        $("#levelling").prev('.input-group-addon').css("background-color","red");
      } else if (val == "partner") {
        $("#partner").closest('.form-group').removeClass('has-error')
        $("#partner").closest('input').next('span').hide();
        $("#partner").prev('.input-group-addon').css("background-color","red");
      } else if (val == "level") {
        $("#level").closest('.form-group').removeClass('has-error')
        $("#level").closest('input').next('span').hide();
        $("#level").prev('.input-group-addon').css("background-color","red");
      } else if (val == "renewal") {
        $("#renewal_date").closest('.form-group').removeClass('has-error')
        $("#renewal_date").closest('input').next('span').hide();
        $("#renewal_date").prev('.input-group-addon').css("background-color","red");
      }
    }

    function submitBtnAdd(){
      Swal.fire({
        title: 'Add New Partnership',
        text: "Are you sure?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: 'Please Wait..!',
            text: "It's updating..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              popup: 'border-radius-0',
            },
            onOpen: () => {
              Swal.showLoading()
            }
          })
          var tagCertList = []
          var select2 = ""
          if ($('#tbListCert tr').length != 0) {
            $('#tbListCert tr').each(function() {
              tagCertList.push({
                cert_type:$("#cert_type").val(),
                cert_name:$("#cert_name").val(),
                cert_person:$("#select2-person").select2('data')[0].text
              })
            });
          }

          var tagSalesTarget = []
          if ($('#tbSalesTarget tr').length != 0) {
            $('#tbSalesTarget tr').each(function() {
              tagSalesTarget.push({
                sales_target:$("#sales_target").val(),
                countable:$("#countable").val(),
                description:$("#description").val()
              })
            });
          }

          var tagData = {
            tagCertList:tagCertList,
            tagSalesTarget:tagSalesTarget,
          }

          $.ajax({
              url:"{{'/store_partnership'}}",
              type:'post',
              data:{
                type:$("#type").val(),
                partner:$("#partner").val(),
                levelling:$("#levelling").val(),
                level:$("#level").val(),
                renewal_date:$("#renewal_date").val(),
                annual_fee:$("#annual_fee").val(),
                cam:$("#cam").val(),
                cam_phone:$("#cam_phone").val(),
                cam_email:$("#cam_email").val(),
                email_support:$("#email_support").val(),
                id_mitra:$("#id_mitra").val(),
                tagData:tagData,
                portal_partner:$("#portal_partner").val(),
                _token:"{{ csrf_token() }}",
                id_technology:$("#id_technology").val()
              }, // serializes the form's elements.
            success: function(response)
            { 
                Swal.showLoading()
                Swal.fire({
                  title: 'Success!',
                  text: 'Lanjutkan ke halaman detail partner',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'OK',
                  cancelButtonText: 'NO',
                }).then((result) => {
                  if (result.value) {
                    window.location.href = "{{url('partnership_detail')}}/" + response
                  }else{
                    location.reload()
                  }
                });
            }
          }); 
        }    
      })
    }

  </script>
@endsection