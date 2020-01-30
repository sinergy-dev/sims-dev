@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}

.radios {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 14px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.radios input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On radiosmouse-over, add a grey background color */
.radios:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.radios input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.radios input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.radios .checkmark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
</style>

<section class="content-header">
  <h1>
    Delivery Order MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Delivery Order</li>
    <li class="active">MSP</li>
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

  @if ($message = Session::get('warning'))
    <div class="alert alert-warning notification-bar"><span>warning: </span> {{ $message }}.<button   type="button" class="dismisbar transparant pull-right"><i class="fa fa-times"></i></button></div>
  @endif

  <div class="box">
    <div class="box-header with-border">
      <a href="{{url('/add/project_delivery')}}"><button class="btn btn-xs btn-primary pull-right" style="width: 110px"><i class="fa fa-plus"> </i>&nbsp Delivery Order</button></a>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Detail</th>
              <th>Date</th>
              <th>To</th>
              <th>From</th>
              <th>Subject</th>
              <th>No. DO</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            <?php $no = 1;?>
            @foreach($datas as $data)
            <tr>
              <td>{{$no++}}</td>
              <td><a href="{{url('/detail/do/msp',$data->id_transaction)}}"><button class="btn btn-xs btn-primary">Detail</button></a></td>
              <td>{{$data->date}}</td>
              <td>{{$data->to_agen}}</td>
              <td>{{$data->from}}</td>
              <td>{{$data->subj}}</td>
              <td>{{$data->no_do}}</td>
              <td>
              <!--  <button style="width: 30px" class="btn btn-sm btn-success" data-target="#modal_pr_product_edit" data-toggle="modal" value="" onclick="edit_product('{{$data->id_transaction}}','{{$data->no_do}}')" name=""><i class="fa fa-plus"></i></button> -->
                <a href="{{url('')}}"><button style="width: 70px" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                <i class="fa fa-trash"></i>&nbspDelete</button></a>
                <a href="{{action('WarehouseProjectController@downloadPdfDO',$data->id_transaction)}}" target="_blank" onclick="print()"><button class="btn btn-xs btn-info" style="width: 80px"><b><i class="fa fa-print"></i> To PDF </b></button></a>  
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

<!--Modal Add Project-->
<div class="modal fade" id="modal_do" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Delivery Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/inventory/store/do/msp')}}" id="modal_pr_asset" name="modal_pr_asset">
            @csrf 
            <div>
              <div class="form-group">
                <label for="">To</label>
                <input type="text" name="to_agen" id="to_agen" class="form-control" placeholder="Enter To">
              </div>

              <div class="form-group">
                <label for="">Address</label>
                <input type="text" name="add" id="add" class="form-control" placeholder="Enter Address">
              </div>

              <div class="form-group">
                <label for="">Telp.</label>
                <input type="number" name="telp" id="telp" class="form-control" placeholder="Enter No. Telp">
              </div>

              <div class="form-group">
                <label for="">Fax.</label>
                <input type="text" name="fax" id="fax" class="form-control" placeholder="Enter Fax.">
              </div>

              <div class="form-group">
                <label for="">Attention.</label>
                <input type="text" name="att" id="att" class="form-control" placeholder="Enter Attention">
              </div>

              <div class="form-group">
                <label for="">From.</label>
                <input type="text" name="from" id="from" class="form-control" placeholder="Enter From">
              </div>

              <div class="form-group">
                <label for="">Subject.</label>
                <input type="text" name="subj" id="subj" class="form-control" placeholder="Enter Subject">
              </div>

              <div class="form-group">
                <label for="">Date.</label>
                <input type="date" class="form-control" placeholder="Select Date" name="date" id="date" >
              </div>

              <div class="form-group">
                <label for="">ID Project.</label>
                <input type="text" class="form-control" placeholder="Enter ID Project" name="id_project" id="id_project" >
              
              </<div class="form-group"><br>
                <label for="">Kirim dengan Ekspedisi.(Harus Diisi)</label><br>
                <label class="radios">Ya
                  <input type="radio" name="invoice" id="yesCheck" value="Y" onclick="javascript:yesnoCheck();">
                  <span class="checkmark"></span>
                </label>
                <label class="radios">Tidak
                  <input type="radio" name="invoice" id="noCheck" value="T" onclick="javascript:yesnoCheck();">
                  <span class="checkmark"></span>
                </label>
              </div>

            <legend>Add Product</legend>

            <table id="product-add" style="display: none">
              <input type="" name="id_pam_set" id="id_pam_set" hidden>
              <tr class="tr-header">
                <th>Product</th>
                <th>Stock</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Description</th>
                <th>
                  <div style="display: none" id="kg-header">Kg</div>
                </th>
                <th>
                  <div style="display: none" id="vol-header">Vol</div>
                </th>
                <th>
                  <div style="display: none" id="pluss">
                  <a href="javascript:void(0);" style="font-size:18px;display: none;" id="addMore"><span class="fa fa-plus"></span></a>
                  </div>
                  <div style="display: none" id="plusss">
                  <a href="javascript:void(0);" style="font-size:18px;display: none;" id="addMoreYa"><span class="fa fa-plus"></span></a>
                  </div>
                </th>
              </tr>
              <tr>
                <td style="margin-bottom: 50px;">
                  <br><select class="form-control" name="product[]" id="" style="font-size: 14px">
                    <option>-- Select Product --</option>
                    @foreach($barang as $data)
                    <option value="{{$data->id_product}}" >{{$data->nama}} </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <br>
                  <input type="text" name="ket_aja[]" id="ket0" class="form-control" readonly style="width: 50px">
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="number" class="form-control" placeholder="Qty" name="qty[]" id="qty" style="width: 100px" required>
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="text" class="form-control" placeholder="Unit" name="unit[]" id="unit0" readonly style="width: 100px">
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information" >
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <div id="ifYes" style="display: none">
                  <input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control">
                  </div>
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <div id="volYes" style="display: none">
                  <input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control">
                  </div>
                </td>
                <td>
                  <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
                </td>
              </tr>
            </table>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
              <!-- <input type="button" class="btn btn-primary" name="" value="Save" id="btn-save"> -->
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Add Project lagi-->
<div class="modal fade" id="modal_pr_product_edit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-body">
          <form method="POST" action="{{url('/store/product/do/msp')}}" id="modal_pr_asset" name="modal_pr_asset">
            @csrf 
          <div>
            <input type="" name="id_transaction_edit" id="id_transaction_edit" value="" >
            <input type="" name="no_do_edit" id="no_do_edit" value="" hidden>
            <legend>Add Product</legend>

            <table id="product-add-lagi">
              <input type="" name="id_pam_set" id="id_pam_set" hidden>
              <tr class="tr-header">
                <th>Product</th>
                <th>Stock</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Description</th>
              <th>
                  <div id="kg-header-edit">Kg</div>
                </th>
                <th>
                  <div id="vol-header-edit">Vol</div>
                </th>
                <th><a href="javascript:void(0);" style="font-size:18px;display: none;" id="addMorelagi"><span class="fa fa-plus"></span></a></th>
              </tr>
              <tr>
                <td style="margin-bottom: 50px;">
                  <br><select class="form-control" name="product[]" id="product0" data-rowid="0" style="font-size: 14px">
                    <option>-- Select Product --</option>
                    @foreach($barang as $data)
                    <option value="{{$data->id_product}}" >{{$data->nama}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <br>
                  <input type="text" name="ket_aja[]" id="ket0" class="form-control ket" data-rowid="0" readonly style="width: 50px">
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty" data-rowid="0" style="width: 100px" required>
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="text" class="form-control unit" placeholder="Unit" name="unit[]" id="unit0" data-rowid="0" readonly style="width: 100px">
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information" >
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <div >
                  <input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control">
                  </div>
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <div>
                  <input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control">
                  </div>
                </td>
                <td>
                  <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
                </td>
              </tr>
            </table>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
              <!-- <input type="button" class="btn btn-primary" name="" value="Save" id="btn-save"> -->
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Edit-->
<div class="modal fade" id="modal_edit_category" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Category</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_category')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" name="id_category_edit" id="id_category_edit" hidden>
      
          <div class="form-group">
            <label for="">Category</label>
            <input type="text" class="form-control" placeholder="Enter Category" name="category_edit" id="category_edit">
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
  
</section>

<style type="text/css">
   .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }

</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function showMe(e) {
  // i am spammy!
      alert(e.value);
    }

    function yesnoCheck() {
      if (document.getElementById('yesCheck').checked) {
      document.getElementById('product-add').style.display = 'block'; 
      document.getElementById('plusss').style.display = 'block'; 
      document.getElementById('pluss').style.display = 'none';  
      document.getElementById('kg-header').style.display = 'block';
      document.getElementById('vol-header').style.display = 'block';
      document.getElementById('ifYes').style.display = 'block';
      document.getElementById('volYes').style.display = 'block';
      }
      else {
      document.getElementById('product-add').style.display = 'block'; 
      document.getElementById('pluss').style.display = 'block';
      document.getElementById('plusss').style.display = 'none';
      document.getElementById('kg-header').style.display = 'none';
      document.getElementById('vol-header').style.display = 'none';
      document.getElementById('ifYes').style.display = 'none'; 
      document.getElementById('volYes').style.display = 'none'; 
      }
    }

    function edit_product(id_transaction,no_do){
        $('#id_transaction_edit').val(id_transaction);
        $('#no_do_edit').val(no_do);
    }


    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_Table').DataTable( {
     "scrollX": true,
    });

      $(document).on('change',"select[id^='product']",function(e) {
        document.getElementById('addMorelagi').style.display = 'block'; 
        document.getElementById('addMore').style.display = 'block'; 
        document.getElementById('addMoreYa').style.display = 'block'; 
        var rowid = $(this).attr("data-rowid");

         $.ajax({
          type:"GET",
          url:'/dropdownQty',
          data:{
            product:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
               $(".ket[data-rowid='"+rowid+"']").val(value.qty);
               $(".unit[data-rowid='"+rowid+"']").val(value.unit);
            });

          }
        });
      });

    var i = 0;
    $('#addMore').click(function(){  
           i++;  
           $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control" name="product[]" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_product}}" >{{$data->nama}}</option>@endforeach</select></td><td><br><input type="text" name="ket_aja[]" id="ket'+i+'" class="form-control" readonly style="width: 50px"></td><td><br><input type="number" class="form-control" placeholder="Qty" name="qty[]" id="qty" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Unit" name="unit[]" id="unit'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td><br><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
    });

    $('#addMoreYa').click(function(){  
           i++;  
           $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control" name="product[]" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_product}}" >{{$data->nama}}</option>@endforeach</select></td><td><br><input type="text" name="ket_aja[]" id="ket'+i+'" class="form-control" readonly style="width: 50px"></td><td><br><input type="number" class="form-control" placeholder="Qty" name="qty[]" id="qty" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Unit" name="unit[]" id="unit'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td style="margin-bottom: 50px"><br><div id="ifYes"><input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control"></div></td><td style="margin-bottom: 50px"><br><div id="volYes"><input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control"></div></td><td><br><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
    });

    $('#addMorelagi').click(function(){  
          i++;  
          $('#product-add-lagi').append('<tr id="row'+i+'"><td><br><select class="form-control" name="product[]" data-rowid="'+i+'" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_product}}" >{{$data->nama}}</option>@endforeach</select></td><td><br><input type="text" name="ket_aja[]" id="ket'+i+'" data-rowid="'+i+'" class="form-control ket" readonly style="width: 50px"></td><td><br><input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty" data-rowid="'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control unit" placeholder="Unit" name="unit[]" id="unit'+i+'" data-rowid="'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td style="margin-bottom: 50px"><br><div id="ifYes"><input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control"></div></td><td style="margin-bottom: 50px"><br><div id="volYes"><input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control"></div></td><td><br><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
    });

    $('#product').on('change',function(e){
      console.log($('#product').val());
      var product = $('#po-number').val();
    });

    $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
    });

    $(document).on('keyup keydown', ".qty", function(e){
    var rowid = $(this).attr("data-rowid");
    var qty_before = $("input[data-rowid='"+rowid+"']").val();
    console.log(qty_before);
        if ($(this).val() > parseFloat(qty_before)
            && e.keyCode != 46
            && e.keyCode != 8
           ) {
           e.preventDefault();     
           $(this).val(qty_before);
        }
    });

  /*  $(document).on('click', '.send', function() {
        var coba = $("select#product").val();
        console.log(coba);
    });*/

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    });

    function print()
    {
      window.print();
    }
  </script>
@endsection