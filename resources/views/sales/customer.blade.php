@extends('template.main')
@section('tittle')
Customer
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
  }

  .dataTables_paging {
     display: none;
  }

  .dataTables_filter {
    display: none;
  }

  a:hover{
    cursor: pointer;
  }

  a:active{
    cursor: pointer;
  }

  .nav-tabs .badge{
      position: absolute;
      top: -10px;
      right: -10px;
      background: red;
  }

  .placeholder-code::-webkit-input-placeholder {
    color: red
  }
</style>
@endsection
@section('content')
  <section class="content-header">
      <h1>
        Customer Data
      </h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customer Data</li>
      </ol>
  </section>

  <section class="content">
      @if (session('update'))
      <div class="alert alert-warning" style="background-color: yellow" id="alert">
          {{ session('update') }}
      </div>
      @endif

      @if (session('success'))
      <div class="alert alert-primary" style="background-color: green;color: white" id="alert">
          {{ session('success') }}
          Please Waiting for <span class="AccName"></span> Accept this Request!
      </div>
      @endif

      @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
      @endif
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-table"></i> Customer Data</h3>
        </div>
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a id="list-tab"  data-toggle="tab" onclick="changeTab('list')">List</a>
              </li>
              <li>
                <a id="request-tab" data-toggle="tab" onclick="changeTab('request')">Request</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active">
                <div class="row">
                  <div class="col-md-8">
                    <button style="width: 100px;display: none;" class="btn btn-success btn-md" id="btn_add_customer" data-target="#add_customer" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspCustomer</button>
                  </div>
                  <dir class="col-md-4 text-right" style="margin-bottom: 0px; margin-top: 0px;">
                    <div class="input-group pull-right">
                      <input id="searchBar" type="text" class="form-control" onkeyup="searchCustom('data-table','searchBar')" placeholder="Search Anything">
                      
                      <div class="input-group-btn">
                        <button type="button" id="btnShowEntryFeature" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          Show 10 entries
                        </button>
                        <ul class="dropdown-menu">
                          <li><a href="#" onclick="$('#data-table').DataTable().page.len(10).draw();$('#btnShowEntryFeature').html('Show 10 entries')">10</a></li>
                          <li><a href="#" onclick="$('#data-table').DataTable().page.len(25).draw();$('#btnShowEntryFeature').html('Show 25 entries')">25</a></li>
                          <li><a href="#" onclick="$('#data-table').DataTable().page.len(50).draw();$('#btnShowEntryFeature').html('Show 50 entries')">50</a></li>
                          <li><a href="#" onclick="$('#data-table').DataTable().page.len(100).draw();$('#btnShowEntryFeature').html('Show 100 entries')">100</a></li>
                        </ul>
                      </div>
                      <span class="input-group-btn">
                        <button onclick="searchCustom('data-table','searchBar')" type="button" class="btn btn-default btn-flat">
                          <i class="fa fa-fw fa-search"></i>
                        </button>
                      </span>
                    </div>
                  </dir>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dataTable" id="data-table">
                    <thead>
                      <tr>
                        <th>Code</th>
                        <th>Customer Legal Name</th>
                        <th>Brand Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>             
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

          <!--MODAL ADD CUSTOMER-->
      <div class="modal fade" id="add_customer" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content modal-md">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">&nbspAdd Customer</h4>
              </div>
              <div class="modal-body">
                <!-- <form method="POST" action="{{url('/customer/storeRequest')}}" id="modalCustomer" name="modalCustomer"> -->
                  @csrf
                <div class="form-group" id="codeName" style="display:none;">
                  <label for="code_name">Code Name *must 4 digit</label>
                  <input type="text" class="form-control" id="code_name" name="code_name" maxlength="4" minlength="4" placeholder="fill code name!" style="text-transform: uppercase;">
                </div>
                      
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <label for="name_contact">Customer Legal Name</label>
                      <input type="text" class="form-control" id="name_contact" name="name_contact" placeholder="Customer Legal Name" required>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <label for="brand_name">Brand Name</label>
                      <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Brand Name" required>
                    </div>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="office_building">Office Building</label>
                  <!-- <input type="text" class="form-control" id="office_building" name="office_building" placeholder="Office Building"> -->
                  <textarea class="form-control" id="office_building" name="office_building" placeholder="Office Building"></textarea>
                </div>
                <div class="form-group">
                  <label for="street_address">Street Address</label>
                  <textarea class="form-control" id="street_address" name="street_address" placeholder="Street Address"></textarea>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="city">City</label>
                      <input type="text" class="form-control" id="city" name="city" placeholder="City">
                    </div>
                    <div class="col-md-6">
                      <label for="province">Province</label>
                      <input type="text" class="form-control" id="province" name="province" placeholder="Province">
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="postal">Postal</label>
                      <input type="number" class="form-control" id="postal" name="postal" placeholder="Postal">
                    </div>
                    <div class="col-md-6">
                      <label for="phone">Phone</label>
                      <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone">
                    </div>
                  </div>
                </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                    <button type="button" id="btnAddCustomer" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspAdd</button>
                  </div>
              <!-- </form> -->
              </div>
            </div>
          </div>
      </div>

      <!--MODAL EDIT CUSTOMER-->
      <div class="modal fade" id="edit_customer" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content modal-md">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Customer</h4>
              </div>
              <div class="modal-body">
                <!-- <form method="POST" action="{{url('update_customer')}}" id="modalCustomer" name="modalCustomer"> -->
                  @csrf
                <input type="" name="id_contact" id="id_contact" hidden> 
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <label for="code_name">Code Name</label>
                        <input type="text" class="form-control" id="code_name_edit" name="code_name" maxlength="4" minlength="4" placeholder="code name" title="Fill Code Name!" required style="text-transform: uppercase;">
                        <span>*must 4 digit</span>
                     </div>
                     <div class="col-md-6 col-xs-12">
                        <label for="name_contact">Customer Legal Name</label>
                        <input type="text" class="form-control" id="name_contact_edit" name="name_contact" placeholder="Customer Legal Name" required>
                     </div>                     
                     <div class="col-md-4 col-xs-12">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name_edit" name="brand_name" placeholder="Brand Name" required>
                     </div>
                   </div>
                </div>
                <div class="form-group">
                  <label for="office_building">Office Building</label>
                  <!-- <input type="text" class="form-control" id="office_building" name="office_building" placeholder="Office Building"> -->
                  <textarea class="form-control" id="office_building_edit" name="office_building" placeholder="Office Building"></textarea>
                </div>
                <div class="form-group">
                  <label for="street_address">Street Address</label>
                  <textarea class="form-control" id="street_address_edit" name="street_address" placeholder="Street Address"></textarea>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <label for="city">City</label>
                      <input type="text" class="form-control" id="city_edit" name="city" placeholder="City">
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <label for="province">Province</label>
                      <input type="text" class="form-control" id="province_edit" name="province" placeholder="Province">
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <label for="postal">Postal</label>
                      <input type="number" class="form-control" id="postal_edit" name="postal" placeholder="Postal">
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <label for="phone">Phone</label>
                      <input type="number" class="form-control" id="phone_edit" name="phone" placeholder="Phone">
                    </div>
                  </div>
                </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btnClose" style="display:none;"><i class=" fa fa-times"></i>&nbspClose</button>
                    <button type="button" class="btn btn-danger" id="btnReject" style="display:none;"><i class="fa fa-times"> </i>&nbspReject</button>                    
                    <button type="button" class="btn btn-success" id="btnAccept" ><i class="fa fa-check"> </i>&nbspUpdate</button>
                  </div>
              <!-- </form> -->
              </div>
            </div>
          </div>
      </div>

      <div id="popUp" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content modal-style">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">ANNOUNCEMENT</h4>
            </div>
            <div class="modal-body">
              <center><h4 class="box-title"><b>SALES APP<b><br><i>(Customer Data)</i></h4></center>
              <div class="row">
                <div class="col-md-12">
                  <h5>
                    <span id="nameRequest"></span><br><br>
                    <!-- Terdapat penyesuaian untuk penambahan data customer, untuk penambahan data hanya dapat dilakukan oleh Rizki Nugroho, jika ada keperluan terkait hal tersebut, harap hubungi kontak dibawah ini:<br><br> -->
                    Terdapat penyesuaian untuk penambahan data customer, data yang telah ditambah statusnya akan menjadi request dan menunggu <span class="AccName"></span> untuk melakukan <i>ACC</i>. Jika ada keperluan terkait hal tersebut, harap hubungi kontak dibawah ini:<br><br>
                    <ul>
                      <li>Email: <span class="AccEmail"></span><br></li>
                      <li>Phone: <span class="AccPhone"></span><br><br></li>
                    </ul>
                    Terima kasih.
                  </h5>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>

      <div id="popUp2" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content modal-style">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">ANNOUNCEMENT</h4>
            </div>
            <div class="modal-body">
              <center><h4 class="box-title"><b>SALES APP<b><br><i>(Customer Data)</i></h4></center>
              <div class="row">
                <div class="col-md-12">
                  <h5>
                    <span id="nameAcc"></span>,<br><br>
                    Terdapat penyesuaian untuk penambahan data customer, dimohon untuk memeriksa tab Request untuk melakukan <i>ACC</i> Request data customer!<br><br>
                    Terima kasih.
                  </h5>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>

  </section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

@endsection
@section('script')
  <script type="text/javascript">
    $(document).keyup(function(e) {
      if (e.keyCode == 27) {
          $('#popUp').modal('hide');
      }
    }); 

    var accesable = @json($feature_item);
    console.log(accesable)


    if (accesable.includes('popUp')) {
      $("#popUp").modal("show");

      $("#nameRequest").text("Dear {{Auth::User()->name}}")
    }

    if (accesable.includes('popUp2')) {
      $("#popUp2").modal("show");

      $("#nameAcc").text("Dear {{Auth::User()->name}}")
    }

    function changeTab(val){
      if (val == 'list') {
        $("#request-tab").prev('li').removeClass('active')
        $("#list-tab").prev('li').addClass('active')
        $('#data-table').DataTable().destroy();
        $('#data-table').empty() 
        initTable()
        // table.ajax.url("{{url('/customer/getCustomerData')}}").load();
      }else{
        $("#list-tab").prev('li').removeClass('active')
        $("#request-tab").prev('li').addClass('active')
        if(!accesable.includes('edit_cus')){
          $('#data-table').DataTable().ajax.url("{{url('/customer/getCustomerDataRequest')}}").load();
        }else{
          $('#data-table').DataTable().destroy();
          $('#data-table').empty() 
          $('#data-table').DataTable({
            "ajax":{
            "type":"GET",
            "url":"{{url('/customer/getCustomerDataRequest')}}",
            },
            "columns":[
                { "data": "customer_legal_name", "title":"Custome Legal Name"},
                { "data": "brand_name","title":"Brand Name"},
                { "data": "name","title":"Request By"},
                {
                  render: function ( data, type, row ) {
                    if (row.status == 'New') {
                      return '<button class="btn btn-xs btn-warning btn-editan" data-id="'+row.code+'" value="'+row.id_customer+'" name="edit_cus" id="edit_cus"><i class="fa fa-search"></i>&nbspPending</button>'
                    }else{
                      return '<button class="btn btn-xs btn-primary btn-editan" data-id="'+row.code+'" value="'+row.id_customer+'" name="edit_cus" id="edit_cus"><i class="fa fa-search"></i>&nbspEdit</button>'
                    }
                  },
                  "title":"Action",
                  "width":"5%"
                },
              ],
              pageLength:25,
              lengthChange: false,
              "processing": true,
              "language": {
                'loadingRecords': '&nbsp;',
                'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
              },
            });        
          }                     
      }
    }

    $("#btnAddCustomer").click(function(){
      if ($('#name_contact').val() == '') {
        Swal.fire("Please Fill Customer Legal Name of Contact!");
      }else if($('#brand_name').val() == ''){
        Swal.fire("Please Fill Brand Name of Contact!");
      }else if ($('#office_building').val() == '') {
        Swal.fire("Please Fill Office Building of Contact!");
      }else if($('#street_address').val() == ''){
        Swal.fire("Please Fill Street Address of Contact!");
      }else{
        Swal.fire({
          title: 'Please Wait..!',
          text: "It's sending..",
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
        fd = new FormData()
        fd.append('_token',"{{csrf_token()}}")
        fd.append("name_contact",$('#name_contact').val());
        fd.append("brand_name",$('#brand_name').val());
        fd.append("office_building",$('#office_building').val());
        fd.append("street_address",$('#street_address').val());
        fd.append("city",$('#city').val());
        fd.append("province",$('#province').val());
        fd.append("postal",$('#postal').val());
        fd.append("phone",$('#phone').val()); 

        $.ajax({
            type: "POST",
            url:"{{url('/customer/storeRequest')}}",
            data: fd,
            contentType: false,
            processData: false,
            success: function(result) {
                Swal.showLoading()
                Swal.fire(
                    'Successfully!',
                    'Please Waiting for Rily Accept this Request!.',
                    'success'
                ).then((result) => {
                    if (result.value) {
                      // location.reload()
                      $("#add_customer").modal('hide')
                      $("#list-tab").closest('li').removeClass('active')
                      $("#request-tab").closest('li').addClass('active')
                      $('#data-table').DataTable().ajax.url("{{url('/customer/getCustomerDataRequest')}}").load();
                    }
                })
            }
        }) 
      }
       
    })
    $('#data-table').on('click', '.btn-editan', function(e){
        var fd = new FormData()

        if (!($(this).data("id"))) {
          $("#code_name_edit").tooltip('show');
          console.log('kosong')
          $("#btnAccept").text('Accept')
          $("#btnAccept").click(function(){
            var check_codes =  @json($data);
            console.log($("#code_name_edit").val().trim().toUpperCase())
            if ($("#code_name_edit").val() == '' || $("#code_name_edit").val().length < 4) {
              Swal.fire("Please Fill Code Name with 4 digit alphabetic!");
            }else if(check_codes.includes($("#code_name_edit").val().trim().toUpperCase())){
              Swal.fire("Code name has been already, please change!");
            }else{
              var url = "{{url('/customer/acceptRequest')}}"
              var status = "Request Aceoted!"

              fd.append('_token',"{{csrf_token()}}")
              fd.append("id_customer",$('#id_contact').val());
              fd.append("code_name",$('#code_name_edit').val());
              $("#btnAccept").attr("onclick",Update(fd,url,status))
            }

          })
          $("#btnReject").show()
          $("#btnClose").hide()

          $("#btnReject").click(function(){
            var url = "{{url('/customer/rejectRequest')}}"
            var status = "Request Rejected!!"

            fd.append('_token',"{{csrf_token()}}")
            fd.append("id_customer",$('#id_contact').val());
            fd.append("code_name",$('#code_name_edit').val());

            $("#btnReject").attr("onclick",Update(fd,url,status))
          })

        }else{
          $("#btnAccept").click(function(){
            var url = "{{url('/customer/update')}}"
            var status = "Contact Updated!"


            fd.append('_token',"{{csrf_token()}}")
            fd.append("id_customer",$('#id_contact').val());
            fd.append("code_name",$('#code_name_edit').val());
            fd.append("name_contact",$('#name_contact_edit').val());
            fd.append("brand_name",$('#brand_name_edit').val());
            fd.append("office_building",$('#office_building_edit').val());
            fd.append("street_address",$('#street_address_edit').val());
            fd.append("city",$('#city_edit').val());
            fd.append("province",$('#province_edit').val());
            fd.append("postal",$('#postal_edit').val());
            fd.append("phone",$('#phone_edit').val());              
                
            $("#btnAccept").attr("onclick",Update(fd,url,status))
          })
          $("#btnReject").hide()
          $("#btnClose").show()
        }

        $.ajax({
          type:"GET",
          url:'/customer/getcus',
          data:{
            id_cus:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $('#id_contact').val(value.id_customer);
              $('#code_name_edit').val(value.code);
              $('#name_contact_edit').val(value.customer_legal_name);
              $('#brand_name_edit').val(value.brand_name);
              $('#office_building_edit').val(value.office_building);
              $('#street_address_edit').val(value.street_address);
              $('#city_edit').val(value.city);
              $('#province_edit').val(value.province);
              $('#postal_edit').val(value.postal);
              $('#phone_edit').val(value.phone);
            });

          }
        }); 
        $("#edit_customer").modal("show");
    });

    function Update(value,url,status){
      Swal.fire({
          title: 'Are you sure?',  
          text: "for Submit this",
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
                onOpen: () => {
                    Swal.showLoading()
                }
            })
            $.ajax({
                type: "POST",
                url: url,
                data: value,
                contentType: false,
                processData: false,
                success: function(result) {
                    Swal.showLoading()
                    Swal.fire(
                        'Successfully!',
                        status,
                        'success'
                    ).then((result) => {
                        if (result.value) {
                          location.reload()
                          $("#edit_customer").modal('hide')
                        }
                    })
                }
            })          
        }
      })
    }
    
    function initTable(){
      var table = $('#data-table').DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('/customer/getCustomerData')}}",
        },
        "columns":[
          // { "data": "code"},
          {
            render: function ( data, type, row ) {
              if (row.code == null) {
                return '- - - -'
              }else{
                return row.code
              }
            },
            "title":"Code Name"
          },
          { "data": "customer_legal_name","title":"Custome Legal Name"},
          { "data": "brand_name","title":"Brand Name"},
          {
            render: function ( data, type, row ) {
              if (row.status == 'New') {
                return '<button class="btn btn-xs btn-warning btn-editan" data-id="'+row.code+'" value="'+row.id_customer+'" name="edit_cus" id="edit_cus"><i class="fa fa-search"></i>&nbspPending</button>'
              }else{
                return '<button class="btn btn-xs btn-primary btn-editan" data-id="'+row.code+'" value="'+row.id_customer+'" name="edit_cus" id="edit_cus"><i class="fa fa-search"></i>&nbspEdit</button>'
              }
            },
            "title":"Action"
          },
        ],
        pageLength:25,
        lengthChange: false,
        "processing": true,
        "language": {
          'loadingRecords': '&nbsp;',
          'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
        },
      });

      if(!accesable.includes('edit_cus')){
        var column1 = table.column(3);
        column1.visible(!column1.visible());
      }else{
        var column1 = table.column(3);
        column1.visible(column1.visible());         
      }
    }  

    $(document).ready(function(){
      accesable.forEach(function(item,index){
        $("#" + item).show()   
      })
      
      initTable()
      cRequest =  JSON.parse('@json($count_request)')

      if (accesable.includes('popUp')) {
          // console.log(value)
          $('.AccName').text(@json($roles).name)
          $('.AccEmail').text(@json($roles).email)
          $('.AccPhone').text('+62' + @json($roles).phone)
      }

      if (cRequest > 0) {
        $('#request-tab').append('<span class="badge">'+ cRequest +'</span>')
      }
    })

    
    

    function searchCustom(id_table,id_seach_bar){
      $("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });


  </script>
@endsection