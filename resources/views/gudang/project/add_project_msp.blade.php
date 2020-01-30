@extends('template.template_admin-lte')
@section('content')
<style type="text/css">

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

.container-form {
  border-radius: 5px;
  background-color: #fff;
  padding: 20px;
  border-style: solid;
  border-color: rgb(212, 217, 219);
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}



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
  background-color: #0d1b33;
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
    Add Delivery Order MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Delivery Order</li>
    <li class="active">MSP</li>
    <li class="active">Add</li>
  </ol>
</section>

<section class="content">
  

  <div class="box">
    <div class="box-header">
      
    </div>

    <div class="box-body">
      <form method="POST" action="{{url('inventory/store/do/msp')}}" id="modal_pr_asset" name="modal_pr_asset">
        @csrf
        <div class="row">
          <div class="col-sm-7">
            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">To</label>
              <div class="col-sm-10">
                <input class="form-control" name="to_agen" id="to_agen" type="text" placeholder="Enter To">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">From</label>
              <div class="col-sm-10">
                <input type="text" name="from" id="from" class="form-control" placeholder="Enter From">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Address</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="add" id="add"type="text" placeholder="Enter Address"></textarea>
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Fax</label>
              <div class="col-sm-10">
                <input type="text" name="fax" id="fax" class="form-control" placeholder="Enter Fax.">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Attn.</label>
              <div class="col-sm-10">
                <input type="text" name="att" id="att" class="form-control" placeholder="Enter Attention">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Subj.</label>
              <div class="col-sm-10">
                <textarea type="text" name="subj" id="subj" class="form-control" placeholder="Enter Subject"></textarea>
              </div>
            </div> 
          </div>
          <div class="col-sm-5">
            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Date</label>
              <div class="col-sm-8">
                <input class="form-control" id="today" type="date" readonly>
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">ID Project</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Enter ID Project" name="id_project" id="id_project">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Telp</label>
              <div class="col-sm-8">
                <input type="number" name="telp" id="telp" class="form-control" placeholder="Enter No. Telp">
              </div>
            </div>

            <div class="form-group">
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

          </div>
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
                <br><select class="form-control" name="product[]" id="product0" data-rowid="0" style="font-size: 14px">
                  <option>-- Select Product --</option>
                  @foreach($barang as $data)
                  <option value="{{$data->id_product}}" >{{$data->nama}} </option>
                  @endforeach
                </select>
              </td>
              <td>
                <br>
                <input type="text" name="ket_aja[]" id="ket0" class="form-control ket" data-rowid="0" readonly style="width: 50px">
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty"  data-rowid="0" style="width: 60px" required>
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
                <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;color: red;"></span></a>
              </td>
            </tr>
          </table>
          <div class="col-md-12" style="display: none;" id="submit-btn">
            <br>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
          </div>
      </form>
    </div>
  </div>
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
      document.getElementById('submit-btn').style.display = 'block';
      }
      else {
      document.getElementById('product-add').style.display = 'block'; 
      document.getElementById('pluss').style.display = 'block';
      document.getElementById('plusss').style.display = 'none';
      document.getElementById('kg-header').style.display = 'none';
      document.getElementById('vol-header').style.display = 'none';
      document.getElementById('ifYes').style.display = 'none'; 
      document.getElementById('volYes').style.display = 'none';
      document.getElementById('submit-btn').style.display = 'block'; 
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

	      	document.getElementById('addMore').style.display = 'block'; 
	      	document.getElementById('addMoreYa').style.display = 'block';

          }
        });
      });

    var i = 0;
    $('#addMore').click(function(){  
           i++;  
           $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control" name="product[]" data-rowid="'+i+'" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_product}}" >{{$data->nama}}</option>@endforeach</select></td><td><br><input type="text" name="ket_aja[]" id="ket'+i+'" data-rowid="'+i+'" class="form-control ket" readonly style="width: 50px"></td><td><br><input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty" data-rowid="'+i+'" style="width:60px"></td><td><br><input type="text" class="form-control unit" placeholder="Unit" name="unit[]" id="unit'+i+'" data-rowid="'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td style="margin-bottom: 50px"><br><div id="ifYes" style="display: none"><input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control"></div></td><td style="margin-bottom: 50px"><br><div id="volYes" style="display: none"><input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control"></div></td><td><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
    });

    $('#addMoreYa').click(function(){  
           i++;  
           $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control" name="product[]" data-rowid="'+i+'" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_product}}" >{{$data->nama}}</option>@endforeach</select></td><td><br><input type="text" name="ket_aja[]" id="ket'+i+'" data-rowid="'+i+'" class="form-control ket" readonly style="width: 50px"></td><td><br><input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty" data-rowid="'+i+'" style="width:60px"></td><td><br><input type="text" class="form-control unit" placeholder="Unit" name="unit[]" id="unit'+i+'" data-rowid="'+i+'" style="width:100px"></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td style="margin-bottom: 50px"><br><div id="ifYes"><input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control"></div></td><td style="margin-bottom: 50px"><br><div id="volYes"><input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control"></div></td><td><br><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
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
    var qty_before = $(".ket[data-rowid='"+rowid+"']").val();
    console.log(qty_before);
        if ($(this).val() > parseFloat(qty_before)
            && e.keyCode != 46
            && e.keyCode != 8
           ) {
           e.preventDefault();     
           $(this).val(qty_before);
        }
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    });

    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;
  </script>
@endsection