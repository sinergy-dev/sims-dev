@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Customer Data</a>
        </li>
      </ol>

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

      <!-- @if(Auth::User()->id_position == 'MANAGER')
    
      @elseif(Auth::User()->id_position == 'DIRECTOR')
      <div class="row">
        <div class="col-md-12">
           <button class="btn btn-success-sales pull-left margin-left-sales" id="btn_add_customer" data-target="#modal_customer" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspCustomer</button>
        </div>
      </div><br>
      @endif -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> <b>Customer Table</b>
          @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division != 'FINANCE' || Auth::User()->id_division == 'SALES' && Auth::User()->id_company == '2')
          <button class="btn btn-success-sales margin-bottom float-right" id="btn_add_customer" data-target="#modal_customer" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspCustomer</button>
          @elseif(Auth::User()->id_position == 'DIRECTOR')
          <button class="btn btn-success-sales margin-bottom float-right" id="btn_add_customer" data-target="#modal_customer" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspCustomer</button>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="data-table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Customer Legal Name</th>
                  <th>Brand Name</th>
                  @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division != 'FINANCE')
                  <th>Action</th>
                  @elseif(Auth::User()->id_position == 'DIRECTOR')
                  <th>Action</th>
                  @else
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach($data as $datas)
                <tr>
                  <td>{{ $datas->code }}</td>
                  <td>{{ $datas->customer_legal_name }}</td>
                  <td>{{ $datas->brand_name }}</td>
                  @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division != 'FINANCE')
                  <td>
                    <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg btn-editan" value="{{$datas->id_customer}}" name="edit_cus" id="edit_cus" style="width: 40px;height: 40px;text-align: center;"></button>
                  </td>
                    @elseif(Auth::User()->id_position == 'DIRECTOR')
                  <td>  
                    <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#edit_customer" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="customer('{{$datas->id_customer}}','{{$datas->code}}','{{$datas->customer_legal_name}}','{{$datas->brand_name}}','{{$datas->office_building}}','{{$datas->street_address}}','{{$datas->city}}','{{$datas->province}}','{{$datas->postal}}','{{$datas->phone}}')"></button>

                    <!-- <a href="{{ url('delete_customer', $datas->id_customer) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                    </button></a> -->
                  </td>
                  @else
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
  </div>

  <!-- <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Sinergy Informasi Pratama © 2018</small>
        </div>
      </div>
    </footer> -->
    
</div>

  <!--MODAL ADD CUSTOMER-->
<div class="modal fade" id="modal_customer" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-head  er">
          <h4 class="modal-title">&nbspAdd Customer</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('customer/store')}}" id="modalCustomer" name="modalCustomer">
            @csrf
          <div class="form-group">
            <label for="code_name">Code Name *Max 4 digit</label>
            <input type="text" class="form-control" id="code_name" name="code_name" maxlength="4" minlength="4" placeholder="Code Name" required>
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
            <input type="text" class="form-control" id="code_name_edit" name="code_name" maxlength="4" minlength="4" placeholder="Code Name" required>
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

@endsection

@section('script')
  <script type="text/javascript">
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



    function customer(id_customer,code,customer_legal_name,brand_name,office_building,street_address,city,province,postal,phone) {
      $('#id_contact').val(id_customer);
      $('#code_name_edit').val(code);
      $('#name_contact_edit').val(customer_legal_name);
      $('#brand_name_edit').val(brand_name);
      $('#office_building_edit').val(office_building);
      $('#street_address_edit').val(street_address);
      $('#city_edit').val(city);
      $('#province_edit').val(province);
      $('#postal_edit').val(postal);
      $('#phone_edit').val(phone);
    }

    $('#data-table').DataTable( {
     "scrollX": true,
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });
  </script>
@endsection