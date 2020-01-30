@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    SIP Detail Delivery Order
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Warehouse - Delivery Order</li>
    <li class="active">SIP</li>
    <li class="active">Detail</li>
  </ol>
</section>

<section class="content">

  @if ($message = Session::get('warning'))
    <div class="alert alert-warning notification-bar"><span>warning: </span> {{ $message }}.<button   type="button" class="dismisbar transparant pull-right"><i class="fa fa-times"></i></button></div>
  @endif

  <div class="box">
    <div class="box-header">
      
    </div>

    <div class="box-body">
      <form method="POST" action="{{url('/store_delivery_sip')}}" id="submit-do" name="submit-do">
          @csrf
          <div class="row">
            <div class="col-sm-7">
              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">To</label>
                <div class="col-sm-10">
                  <input class="form-control" name="to_agen" id="to_agen" type="text" placeholder="Enter To" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">From</label>
                <div class="col-sm-10">
                  <input type="text" name="from" id="from" class="form-control" placeholder="Enter From" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="add" id="add"type="text" placeholder="Enter Address" required></textarea>
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

            <legend>Add Product</legend>

            <table id="product-add" class="table">
              <input type="" name="id_pam_set" id="id_pam_set" hidden>
              <tr class="tr-header">
                <th>Product</th>
                <th>SN</th>
                <th>Description</th>
                <th>Kg</th>
                <th>Vol</th>
                <th><a href="javascript:void(0);" id="addMoreYa"><span class="fa fa-plus"></span></a></th>
              </tr>
              <tr>
                <td style="margin-bottom: 50px;">
                  <br><select class="form-control produk" name="product[]" id="product0" data-rowid="0" style="font-size: 14px">
                    <option>-- Select Product --</option>
                    @foreach($barang as $data)
                    <option value="{{$data->id_barang}}" >{{$data->nama}} </option>
                    @endforeach
                  </select>
                  @if($cek_id == 0)
                  <textarea class="id_transac" type="" name="id_transac[]" id="id_transac" data-rowid="0" hidden>1</textarea><!-- 
                  <textarea class="id_transac_b" type="" name="id_transac_b[]" id="id_transac_b" data-rowid="0"></textarea> -->
                  @else
                  <textarea class="id_transac" type="" name="id_transac[]" id="id_transac" data-rowid="0" hidden>{{$getlastid}}</textarea>
                 <!--  <input class="id_transac_b" type="" name="id_transac_b[][]" id="id_transac_b" data-rowid="0"> --><!-- 
                  <textarea class="id_transac_b" type="" name="id_transac_b[]" id="id_transac_b" data-rowid="0"></textarea> -->
                  @endif
                </td>
                <td style="margin-bottom: 50px;">
                    <br>
                    <select class="form-control detail-product" name="detail_product[0][]" id="detail-product0" data-rowid="0" style="font-size: 14px" multiple="multiple" required>
                    <option style="width: 200px">-- Select Product --</option>
                    </select>
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information" >
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control">
                </td>
                <td style="margin-bottom: 50px">
                  <br>
                  <input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control">
                </td>
                <td>
                  <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;color: red;margin-top: 25px"></span></a>
                </td>
              </tr>
            </table>
            <br>
            <div id="submit-btn"><!-- 
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button> -->
              <button type="submit" class="btn btn-primary submit-btn"><i class="fa fa-check"> </i>&nbspSubmit</button>
                <!-- <input type="button" class="btn btn-primary" name="" value="Save" id="btn-save"> -->
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
                  <input type="text" class="form-control" placeholder="Enter ID Project" name="id_project" id="id_project" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4 control-label">Telp</label>
                <div class="col-sm-8">
                  <input type="number" name="telp" id="telp" class="form-control" placeholder="Enter No. Telp">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4 control-label">Subj.</label>
                <div class="col-sm-8">
                  <textarea type="text" name="subj" id="subj" class="form-control" placeholder="Enter Subject"></textarea>
                </div>
              </div>
            </div>
            
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

    .dataTables_filter {
     display: none;
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

    $('.produk').select2();

    function initproduk(){
      $('.produk').select2();
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
     "filter":false,
    });


    var i = 0;

    var id_transac = document.getElementById('id_transac').value;

    $('#addMoreYa').click(function(){  
           i++;  
           id_transac++;
           $('#product-add').append('<tr id="row'+i+'"><td><br><select class="form-control produk" name="product[]" data-rowid="'+i+'" id="product'+i+'" style="font-size: 14px"><option>-- Select Product --</option>@foreach($barang as $data)<option value="{{$data->id_barang}}" >{{$data->nama}}</option>@endforeach<textarea name="id_transac[]" id="id_transac" data-rowid="'+i+'" class="id_transac" hidden>'+id_transac+'</textarea></select></td><td style="margin-bottom: 50px;"><br><select class="form-control detail-product" name="detail_product['+i+'][]" id="detail-product'+i+'" data-rowid="'+i+'" style="font-size: 14px" multiple="multiple" required><option style="width="200px">-- Select Product --</option></select></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="information[]" id="information"></td><td style="margin-bottom: 50px"><br><input type="" name="kg[]" placeholder="Kg" style="width: 50px" class="form-control"></td><td style="margin-bottom: 50px"><br><input type="" name="vol[]" placeholder="Vol" style="width: 50px" class="form-control"></td><td><br><a href="javascript:void(0);" id="'+i+'"class="remove"><span class="fa fa-times" style="font-size: 18px;color:red;margin-top: 5px"></span></a></td></tr>');
            initdetailproduct();
            initproduk();
    });

    function initdetailproduct()
    {
     $(".detail-product").select2({
      closeOnSelect : false,
     }); 
    }

    $(".detail-product").select2({
      closeOnSelect : false,
    });

    /*$(document).on('change',"select[id^='detail-product']",function(e) { 
      var rowid = $(this).attr("data-rowid");

      var values = $(".detail-product[data-rowid='"+rowid+"']").val();

      var cek = values.length;

      var n = $(".id_transac[data-rowid='"+rowid+"']").val();
      $(".id_transac_b[data-rowid='"+rowid+"']").val();

      x = [];

      var text = "";
      var i;
      for (i = 0; i < cek; i++) {
        text += + x.push(n) ;
      }
      xjoin = x.join()
      document.getElementsByClassName("id_transac_b")[rowid].innerHTML = x;

    });*/
/*
    $(document).on('click', '.submit-btn', function() {
      $.ajax({  
        url:"/store_delivery_sip",  
        method:"POST",  
        data:$('#submit-do').serialize(),  
        success:function(data)  
        { 
          swal({
                title: "Success!",
                text:  "You have been add product",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
               setTimeout(function() {
                   window.location.href = window.location;
                }, 2000);                                
        }
       }); 
    });*/


    $(document).on('change',"select[id^='product']",function(e) { 
      console.log();
      var rowid = $(this).attr("data-rowid");
      $.ajax({
          type:"GET",
          url:'/dropdownProject?product=',
          data:{
            product:this.value,
          },
          success: function(result){
            $(".detail-product[data-rowid='"+rowid+"']").html(append)
            var append = append + "<option>"  +"</option>";

            $.each(result[0], function(key, value){
              
              if (value.serial_number == null) {
                append = append + "<option value="+value.id_detail+">" + value.nama + "</option>";              
              }else{
                append = append + "<option value="+value.id_detail+">"+ value.serial_number +"</option>";
              }
              console.log(value.serial_number);
              console.log(rowid);
            });

            $(".detail-product[data-rowid='"+rowid+"']").html(append);

          },
      });
    });


    $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
    });


    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    });

    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;

  </script>
@endsection