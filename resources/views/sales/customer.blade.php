@extends('template.main')
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
      </div>
      @endif

      @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
      @endif
      <div class="box">
        <div class="box-header">
            <div class="pull-right">
              <button style="width: 100px;display: none;" class="btn btn-success btn-md margin-bottom float-right" id="btn_add_customer" data-target="#modal_customer" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspCustomer</button>
            </div>
        </div>
        <!---->
        <!---->

        <div class="box-body">
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
                <tbody>
                  @foreach($data as $datas)
                  <tr>
                    <td>{{ $datas->code }}</td>
                    <td>{{ $datas->customer_legal_name }}</td>
                    <td>{{ $datas->brand_name }}</td>
                    <td>
                      <button class="btn btn-xs btn-primary btn-editan" data-id="{{$datas->code}}" style="vertical-align: top; width: 60px;" value="{{$datas->id_customer}}" name="edit_cus" id="edit_cus"><i class="fa fa-search"></i>&nbspEdit</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
      </div>

          <!--MODAL ADD CUSTOMER-->
      <div class="modal fade" id="modal_customer" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content modal-md">
              <div class="modal-header">
                <h4 class="modal-title">&nbspAdd Customer</h4>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{url('customer/store')}}" id="modalCustomer" name="modalCustomer">
                  @csrf
                <div class="form-group">
                  <label for="code_name">Code Name *Max 4 digit</label>
                  <input type="text" class="form-control" id="code_name" name="code_name" maxlength="4" minlength="4" placeholder="Code Name" required style="text-transform: uppercase;">
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                  <label for="name_contact">Customer Legal Name</label>
                  <input type="text" class="form-control" id="name_contact" name="name_contact" placeholder="Customer Legal Name" required>
                </div>
                <div class="form-group">
                  <label for="brand_name">Brand Name</label>
                  <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Brand Name" required>
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
                  <label for="city">City</label>
                  <input type="text" class="form-control" id="city" name="city" placeholder="City">
                </div>
                <div class="form-group">
                  <label for="province">Province</label>
                  <input type="text" class="form-control" id="province" name="province" placeholder="Province">
                </div>
                <div class="form-group">
                  <label for="postal">Postal</label>
                  <input type="number" class="form-control" id="postal" name="postal" placeholder="Postal">
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone">
                </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                   <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
                    <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspAdd</button>
                  </div>
              </form>
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
                <h4 class="modal-title">Edit Customer</h4>
              </div>
              <div class="modal-body">
                <form method="POST" action="{{url('update_customer')}}" id="modalCustomer" name="modalCustomer">
                  @csrf
                 <input type="" name="id_contact" id="id_contact" hidden>
                <div class="form-group">
                  <label for="code_name">Code Name *Max 4 digit</label>
                  <input type="text" class="form-control" id="code_name_edit" name="code_name" maxlength="4" minlength="4" placeholder="Code Name" required style="text-transform: uppercase;">
                </div>
                <div class="form-group">
                  <label for="name_contact">Customer Legal Name</label>
                  <input type="text" class="form-control" id="name_contact_edit" name="name_contact" placeholder="Customer Legal Name" required>
                </div>
                <div class="form-group">
                  <label for="brand_name">Brand Name</label>
                  <input type="text" class="form-control" id="brand_name_edit" name="brand_name" placeholder="Brand Name" required>
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
                  <label for="city">City</label>
                  <input type="text" class="form-control" id="city_edit" name="city" placeholder="City">
                </div>
                <div class="form-group">
                  <label for="province">Province</label>
                  <input type="text" class="form-control" id="province_edit" name="province" placeholder="Province">
                </div>
                <div class="form-group">
                  <label for="postal">Postal</label>
                  <input type="number" class="form-control" id="postal_edit" name="postal" placeholder="Postal">
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="number" class="form-control" id="phone_edit" name="phone" placeholder="Phone">
                </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                   <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
                    <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
                    <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"> </i>&nbspUpdate</button>
                  </div>
              </form>
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
                    Dear all,<br><br>
                    Terdapat penyesuaian untuk penambahan data customer, untuk penambahan data hanya dapat dilakukan oleh Rizki Nugroho, jika ada keperluan terkait hal tersebut, harap hubungi kontak dibawah ini:<br><br>
                    <ul>
                      <li>Email: nugroho@sinergy.co.id<br></li>
                      <li>Phone: 0812-1860-0150<br><br></li>
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

  </section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    @if (Auth::User()->id_division == 'SALES') {
      if (sessionStorage.getItem('dontLoad') == null){
          $("#popUp").modal("show");
        }
        sessionStorage.setItem('dontLoad', 'true');
      }
    @endif

    $(document).keyup(function(e) {
      if (e.keyCode == 27) {
          $('#popUp').modal('hide');
      }
    }); 

    $(document).ready(function(){
        // var column1 = table.column(3);
        // column1.visible(!column1.visible() );
        var accesable = @json($feature_item);
        accesable.forEach(function(item,index){
          console.log(accesable.length)
          $("#" + item).show()
          
          
          // new_display_value = "block";
          // var allElements= document.querySelectorAll('[id="edit_cus"]');
          // console.log(allElements)
          // for(i=0;i<allElements.length;i++){
          //    allElements[i].style.display=new_display_value;
          // }      
        })  

        if(!(accesable.length)){
          console.log(accesable)
          var column1 = table.column(3);
          column1.visible(!column1.visible() );
        }else{
          var column1 = table.column(3);
          column1.visible(column1.visible() );
        }
    })

    $('.btn-editan').click(function(){
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

         
    var table = $('#data-table').DataTable({
      pageLength:25
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });
  </script>
@endsection