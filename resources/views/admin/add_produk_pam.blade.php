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

.modalIcon input[type=text]{
      padding-left:40px;
    }


    .modalIcon.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIcon.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIcon{
      position:relative;
    }

   .modalIcon i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }


    .newIcon input[type=text]{
      padding-left:34px;
    }

    .newIcon.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .newIcon.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:6px 6px ;
      border-radius:4px 0 0 4px;
    }

  .newIcon{
      position:relative;
    }

   .newIcon i{
      position:absolute;
      left:0px;
      top:34px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
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
    Add PR Asset MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">PR Asset</li>
    <li class="active">MSP</li>
    <li class="active">Add</li>
  </ol>
</section>

<section class="content">
  

  <div class="box">
    <div class="box-header">
      
    </div>

    <div class="box-body">
      <form method="POST" action="{{url('store_produk_msp')}}" id="modal_pr_asset" name="modal_pr_asset">
        @csrf
          <legend>Add Product</legend>

          <table id="product-add" class="table">

            <input type="id_pam" name="id_pam" value="{{$datas->id_pam}}" hidden>

            <div class="row">
              <div class="col-sm-11">
                <div class="form-group row" style="margin-left: -12px">
                  <label class="col-sm-2 control-label">No PR</label>
                  <div class="col-sm-10">
                    <input class="form-control" name="to_agen" id="to_agen" value="{{$datas->no_pr}}" type="text" readonly>
                  </div>
                </div>

                <div class="form-group row" style="margin-left: -12px">
                  <label class="col-sm-2 control-label">Subj.</label>
                  <div class="col-sm-10">
                    <input class="form-control" name="subject" id="add" value="{{$datas->subject}}" type="text"  readonly>
                  </div>
                </div>
              </div>
            </div>

            <tr class="tr-header">
              <th>MSP Code</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Unit</th>
              <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" class="add"><span class="fa fa-plus"></span></a></th>
            </tr>
            <tr>
            <td style="margin-bottom: 50px;">
              <br><select class="form-control produk" name="msp_code[]" id="msp_code" data-rowid="0" style="font-size: 14px;width: 400px">
                <option>-- Select MSP Code --</option>
                @foreach($msp_code as $data)
                <option value="{{$data->kode_barang}}" >{{$data->kode_barang}} - {{$data->nama}}</option>
                @endforeach
              </select>
            </td>
              <td hidden>    
                <input type="" name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="0" hidden>
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <textarea type="text" class="form-control name_barangs" style="width:500px" data-rowid="0" name="name_product[]" id="information" readonly></textarea>
              </td>
              <td style="margin-bottom: 50px;">
                <br>
               <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" >
              </td>
              <td style="margin-bottom: 50px">
                <br><input type="text" class="form-control units" data-rowid="0" placeholder="Enter unit" name="unit[]" id="unit" readonly >
              </td>
              <td>
                <a href='javascript:void(0);'  class='remove'><span class="fa fa-times" style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
              </td>
            </tr>
          </table>
          <div class="col-md-12" id="btn_submit">
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
    var i = 0;
    $(document).on('click', '.add',function(){  
         i++;  
         $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control produk" name="msp_code[]" data-rowid="'+i+'" id="msp_code'+i+'" style="font-size: 14px;width: 400px"><option>-- Select MSPCode --</option>@foreach($msp_code as $data)<option value="{{$data->kode_barang}}" >{{$data->kode_barang}}</option>@endforeach</select></td><<td hidden><input type="" name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="'+i+'" hidden></td><td style="margin-bottom: 50px"><br><textarea type="text" class="form-control name_barangs" style="width:500px" data-rowid="'+i+'" name="name_product[]" id="information" readonly></textarea></td><td style="margin-bottom: 50px;"><br><input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" ></td><td style="margin-bottom: 50px"><br><input type="text" class="form-control units" data-rowid="'+i+'" placeholder="Enter unit" name="unit[]" id="unit" readonly ></td><td><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red;margin-top: 25px"></span></a></td></tr>');
         initMaskMoney();
         initproduk();
    });
    $('.produk').select2();
    $('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});

    function initMaskMoney() {
        $('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});
    }

    function initproduk(){
      $('.produk').select2();
    }

    $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
    });

    $(document).on('change',"select[id^='msp_code']",function(e) {
      var product = $('#msp_code').val();
      var rowid = $(this).attr("data-rowid");

         $.ajax({
          type:"GET",
          url:'/getIDbarang',
          data:{
            product:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
               $(".id_barangs[data-rowid='"+rowid+"']").val(value.id_product);
               $(".name_barangs[data-rowid='"+rowid+"']").val(value.nama);
               $(".units[data-rowid='"+rowid+"']").val(value.unit);
            });

          }
        });
    });
  </script>
@endsection