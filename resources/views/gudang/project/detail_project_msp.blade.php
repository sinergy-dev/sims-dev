@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
  }
</style>

<section class="content-header">
  <h1>
    Detail Delivery Order MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{url('project')}}"><i class="fa fa-dashboard"></i> Home </a></li>
    <li class=""><a href="{{url('inventory/do/msp')}}">Delivery Order</a></li>
    <li class="active">MSP</li>
    <li class="active">Detail</li>
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
    <div class="box-header with-border">
      @if($cek_product > 0)
        <div class="col-md-12">
          <div class="col-md-6">
            <h4><b>To   : {{$to->to_agen}}</b></h4>
            <h4><b>From : {{$to->from}}</b></h4>
          </div>
          <div class="col-md-6">
            {{-- <a href="javascript:void(0);" style="width: 100px" id="addMorelagi" onclick="edit_product('{{$details->id_transaction}}','{{$details->no_do}}')" class="btn btn-xs btn-primary"><span class="fa fa-plus">&nbsp&nbspBarang</span></a> --}}
            <a href="javascript:void(0);" id="addMorelagi" onclick="edit_product('{{$details->id_transaction}}','{{$details->no_do}}')"><button class="btn btn-xs btn-primary pull-right" style="width: 100px"><i class="fa fa-plus"> </i>&nbsp Barang</button></a>
          </div>
        </div>
      @endif
    </div>

    <div class="box-body">
      <div class="table-responsive">    
        <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Deskripsi</th>
              <th>Stock Out</th>
              <th>Stock</th>
              <th>Qty</th>
              <th></th>
              <th></th>
              <th>Unit</th>
              <th>No. PO</th>
              <th>Tanggal Keluar</th>
              <th>Action Stock</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            @foreach($detail as $data)
            <tr>
              <td>{{$data->kode_barang}}</td>
              <td>{{$data->nama}}</td>
              <td>{{$data->note}}</td>
                <form action="{{url('return_do_product_msp')}}" method="POST">
                  <td><input type="" name="qty_before" data-rowid="{{$data->kode_barang}}" style="border-style: none;width: 30px" class="qty_before" value="{{$data->qty_transac}}" readonly></td>
                  <td><input type="" name="" style="border-style: none;width: 30px" class="" value="{{$data->qty}}" readonly></td>
                  <td>
                      @csrf
                      <input type="" value="{{$data->id_product}}" name="id_product_edit" hidden><input type="" value="{{$data->id_detail_do_msp}}" name="id_detail_do_edit" hidden><input type="" value="{{$data->id_transaction}}" name="id_transaction_edit" hidden><input type="number" name="qty_back" id="qty_back" data-rowid="{{$data->kode_barang}}" placeholder="Entry" class="form-control qty_back" style="width: 65px" disabled><input type="number" name="qty_edit" id="qty_edit" data-rowid="{{$data->kode_barang}}" placeholder="Entry" class="form-control qty_edit" style="width: 65px;display: none">
                  </td>
                  <td>
                    <input type="submit" id="submit-qty" name="" disabled data-rowid="{{$data->kode_barang}}" class="btn btn-success btn-sm submit-qty" value="submit">
                    </form>
                    <form action="{{url('edit_qty_do')}}" method="POST">
                      @csrf
                      <input type="" value="{{$data->id_product}}" name="id_product_edit" hidden><input type="" value="{{$data->id_detail_do_msp}}" name="id_detail_do_edit" hidden><input type="" value="{{$data->id_transaction}}" name="id_transaction_edit" hidden>
                      <input type="" name="qty_edit_clone" id="qty_edit_clone" value="" data-rowid="{{$data->kode_barang}}" class="qty_edit_clone" hidden>
                      <input type="" name="qty_produk" data-rowid="{{$data->kode_barang}}" style="display: none;" class="qty_produk" value="{{$data->qty}}">
                      <input style="display: none" type="submit" id="e-submit-qty" name="" disabled data-rowid="{{$data->kode_barang}}" class="btn btn-primary btn-sm e-submit-qty" value="submit">
                    </form>
                  </td>
              <td><input type="button" value="cancel" class="btn btn-danger btn-sm cancel-qty" name="" id="cancel-qty" disabled data-rowid="{{$data->kode_barang}}">
                <input type="button" value="cancel" class="btn btn-danger btn-sm e-cancel-qty" name="" id="e-cancel-qty" disabled data-rowid="{{$data->kode_barang}}" style="display: none">
              </td>
              <td>{{$data->unit}}</td>
              <td>{{$data->id_po}}</td>
              <td>{{$data->created_at}}</td>
              <td>
              @if($data->qty_transac == 0)
              @else
              <!-- <button data-toggle="modal" data-target="#modal_return" onclick="Return('{{$data->id_product}}','{{$data->id_transaction}}','{{$data->qty}}','{{$data->id_detail_do_msp}}')" class="btn btn-sm btn-danger">Return Stock</button> -->
              <input data-rowid="{{$data->kode_barang}}" type="button" id="return"  class="btn btn-sm btn-danger" value="Return">
              <input data-rowid="{{$data->kode_barang}}" type="button" id="edit"  class="btn btn-sm btn-warning edit" value="Edit">
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

<div class="modal fade" id="modal_return" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title">Return Stock</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('return_do_product_msp')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" name="id_product_edit" id="id_product_edit" hidden>
          <input type="text" class="form-control" name="id_transaction_edit" id="id_transaction_edit" hidden>
          <input type="text" class="form-control" name="id_detail_do_edit" id="id_detail_do_edit" hidden>
          
          <div class="form-group margin-left-right">
            @if ($message = Session::get('warning'))
            <div class="alert alert-warning alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button> 
              <strong>{{ $message }}</strong>
            </div>
            @endif
          </div>

          <div class="form-group" style="margin-left: 70px">
            <label>Quantity </label>
            <input type="number" name="qty" id="qty" class="form-control" style="width: 100px" readonly>
          </div>

          <div class="form-group" style="margin-left: 70px">
            <label>Quantity return</label>
            <input type="number" name="qty_return" id="qty_return" class="form-control" style="width: 100px">
          </div>

       <!--    <div class="form-group">
            <label for="">Note (*Harus Diisi)</label>
            <input type="text" class="form-control" style="height: 70px" placeholder="Enter note" name="note" id="note" required>
          </div> -->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

@if($cek_product > 0)
<div class="modal fade" id="modal_pr_product_edit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-body">
          <form method="POST" action="{{url('/store/product/do/msp')}}" id="modal_pr_asset" name="modal_pr_asset">
            @csrf 
          <div>
            <input type="" name="id_transaction_product" id="id_transaction_product" value="" hidden>
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
                    <option value="{{$data->id_product}}">{{$data->nama}}</option>
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
@else
@endif

</section>

@endsection
@section('script')
<script type="text/javascript">
  $('#data_Table').DataTable({
     "order": [[ 8, "desc" ]],
     "scroll-X":true,
  });
  function Return(id_product,id_transaction,qty_transac,id_detail_do_msp){
      $('#id_product_edit').val(id_product);
      $('#id_transaction_edit').val(id_transaction);
      $('#qty').val(qty_transac);
      $('#id_detail_do_edit').val(id_detail_do_msp);
  }

  $("#alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#alert").slideUp(300);
  });

  $(document).on('click', "#addMorelagi", function(e){
   $("#modal_pr_product_edit").modal();
   var lines = $('textarea').val().split(',');
   console.log(lines);
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

          }
        });
  });

  $(document).on('click', "input[id^='return']", function(e){
    var rowid = $(this).attr("data-rowid");
      $(".qty_back[data-rowid='"+rowid+"']").val('');
      $(".qty_edit[data-rowid='"+rowid+"']").css("display", "none");
      $(".qty_back[data-rowid='"+rowid+"']").css("display", "block");
      $(".qty_back[data-rowid='"+rowid+"']").prop('disabled', false);
      $(".submit-qty[data-rowid='"+rowid+"']").css("display", "block");
      $(".cancel-qty[data-rowid='"+rowid+"']").css("display", "block");
      $(".e-submit-qty[data-rowid='"+rowid+"']").css("display", "none");
      $(".e-cancel-qty[data-rowid='"+rowid+"']").css("display", "none");
      console.log(rowid)
  });

  $(document).on('click', "input[id^='edit']", function(e){
    var rowid = $(this).attr("data-rowid");
      $(".qty_edit[data-rowid='"+rowid+"']").val('');
      $(".qty_edit[data-rowid='"+rowid+"']").css("display", "block");
      $(".qty_edit[data-rowid='"+rowid+"']").prop('disabled', false);
      $(".qty_back[data-rowid='"+rowid+"']").css("display", "none");
      $(".submit-qty[data-rowid='"+rowid+"']").css("display", "none");
      $(".cancel-qty[data-rowid='"+rowid+"']").css("display", "none");
      $(".e-submit-qty[data-rowid='"+rowid+"']").css("display", "block");
      $(".e-cancel-qty[data-rowid='"+rowid+"']").css("display", "block");
      console.log(rowid)
  });

  $(document).on('keyup keydown', "input[id^='qty_back']", function(e){ 

    var rowid = $(this).attr("data-rowid");
    $(".submit-qty[data-rowid='"+rowid+"']").prop('disabled', false);
      $(".cancel-qty[data-rowid='"+rowid+"']").prop('disabled', false);

    var qty_before = $(".qty_before[data-rowid='"+rowid+"']").val();
    console.log(qty_before);
    if ($(this).val() > parseFloat(qty_before)
        && e.keyCode != 46
        && e.keyCode != 8
       ) {
       e.preventDefault();     
      $(this).val(qty_before);
      
    }
  });

  $(document).on('keyup keydown', "input[id^='qty_edit']", function(e){ 

    var rowid = $(this).attr("data-rowid");

    var that = this;
    setTimeout(function(){ 
        $(".qty_edit_clone[data-rowid='"+rowid+"']").val(that.value);
    },10);

    $(".e-submit-qty[data-rowid='"+rowid+"']").prop('disabled', false);
    $(".e-cancel-qty[data-rowid='"+rowid+"']").prop('disabled', false);

    var qty_produk = $(".qty_produk[data-rowid='"+rowid+"']").val();
    console.log(qty_produk);
    if ($(this).val() > parseFloat(qty_produk)
        && e.keyCode != 46
        && e.keyCode != 8
       ) {
       e.preventDefault();     
      $(this).val(qty_produk);
      
    }

  });

  $(document).on('click', "input[id^='cancel-qty']", function(e){
    var rowid = $(this).attr("data-rowid");
      $(".qty_back[data-rowid='"+rowid+"']").val('');
      $(".qty_back[data-rowid='"+rowid+"']").prop('disabled', true);
      $(".submit-qty[data-rowid='"+rowid+"']").prop('disabled', true);
      console.log(rowid)
  });

  $(document).on('click', "input[id^='e-cancel-qty']", function(e){
    var rowid = $(this).attr("data-rowid");
      $(".qty_edit[data-rowid='"+rowid+"']").val('');
      $(".qty_edit[data-rowid='"+rowid+"']").prop('disabled', true);
      $(".e-submit-qty[data-rowid='"+rowid+"']").prop('disabled', true);
      console.log(rowid)
  });

  function edit_product(id_transaction,no_do){
        $('#id_transaction_product').val(id_transaction);
        $('#no_do_edit').val(no_do);
  }


/*  $(document).on('click', "input[id^='submit-qty']", function(e){
    $.ajax({  
      url:"/return_do_product_msp",  
      method:"POST",  
      data:$('#return_produk').serialize(),  
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

</script>

@endsection