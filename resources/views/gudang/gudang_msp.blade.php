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
    Inventory MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Inventory</li>
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

  <div class="box">
    <div class="box-header">
      {{-- <h3 class="box-title">Inventory MSP</h3> --}}
      <div class="pull-right">
        <button class="btn btn-xs btn-primary pull-right" style="width: 130px" id="" data-target="#modal_penerimaan" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Penerimaan Barang</button>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Product Id</th>
              <th>Stock</th>
              <th>Name</th>
              <th>Description</th>
              <th>No. PO</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            <?php $no = 1; ?>
            @foreach($data as $data)
            <tr>
              <td>{{$no++}}</td>
              <td>
                <a href="{{url('detail_inventory_msp',$data->id_product)}}"><button class="btn btn-xs btn-primary">{{$data->kode_barang}}</button></a>
              </td>
              <td>{{$data->qty}}</td>
              <td>{{$data->nama}}</td>
              <td>{{$data->note}}</td>
              <td>{{$data->id_po}}</td>
              <td>
                @if($data->status2 == 'Y')
                  <!-- @if($data->status == '')
                  <form method="POST" action="{{url('/inventory/detail/store/msp')}}">
                  @csrf
                  <button class="btn btn-sm btn-default" style="opacity: 0.5" name="id_barang_detail" value="{{$data->id_barang}}" type="submit"><input type="" name="qty_detil" value="{{$data->qty}}" hidden><input type="" name="note_detil" value="{{$data->id_po}}" hidden><input type="" name="id_product_detil" value="{{$data->id_product}}" hidden>Add SN</button>
                  </form>
                  @elseif($data->status == 'v')
                  <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-sn" onclick="id_product('{{$data->id_product}}')">Add SN</button>
                  @endif -->
                @endif
                <!-- <a href="{{ url('/warehouse/destroy_produk', $data->id_barang) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                </button></a> -->
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

  <!--MODAL ADD PENERIMAAN-->
  <div class="modal fade" id="modal_penerimaan" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Form Penerimaan</h4>
        </div>
        <form method="" action="" id="store_lead" name="store_lead">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>No. Purchase Order</label>
              <select name="po_number" id="po-number" class="form-control">
                <option value="">-- Select Purchase Order --</option>
                @foreach($datas as $data)
                <option value="{{$data->id_pam}}">{{$data->no_po}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Terima Qty</th>
                  <th>Unit</th>
                  <th>Description</th>
                </tr>
                </thead>
                <tbody id="mytable">
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <table class="">
                <tbody id="footer-table">
                  
                </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $(document).on('click', '.btn-submit', function(e){
      var lines = $('textarea').val().split('\n');
      console.log(lines);
      for(var i = 0;i < lines.length;i++){
          
      }

      /*$.ajax({
          type:"POST",
          url:'/store/msp/serial_number',
          data:$('#update_sn').serialize(),
          success: function(result){
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
          },
      });*/
    }); 

    $(document).on('click', '.btn-save', function(e){
      $.ajax({
          type:"POST",
          url:'/inventory/store/msp',
          data:$('#store_lead').serialize(),
          success: function(result){
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
          },
      });
    }); 

    $(document).on('click', '.btn-update', function(e){
      console.log($('#sn').val())
      $.ajax({
          type:"POST",
          url:'/inventory/msp/update',
          data:$('#store_lead').serialize(),
          success: function(result){
              swal({
                  title: "Success!",
                  text:  "You have been Update product",
                  type: "success",
                  timer: 2000,
                  showConfirmButton: false
              });
            setTimeout(function() {
                window.location.href = window.location;
            }, 2000);  
          },
      }); 
    }); 

    $("#data_Table").DataTable({
    });

    $('#po-number').on('change',function(e){
      console.log($('#po-number').val());
      var product = $('#po-number').val();

      $.ajax({
          type:"GET",
          url:'/dropdownPO?product=' + product,
          data:{
            product:this.value,
          },
          success: function(result){
            $('#mytable').empty();

            var table = "";

            $.each(result[0], function(key, value){
              table = table + '<tr>';
              if (value.unit == null) {
                table = table + '<td>' +value.msp_code+ '</td>';
                table = table + '<td>' +value.name_product+ '</td>';
                table = table + '<td>' +value.qty+ '</td>';
                table = table + '<td>' + '<input type="number" name="qty_terima[]" style="width:70px;border:rounded">'+ '</td>';
                table = table + '<td>' + ' '+ '</td>';/*
                table = table + '<td>' + '<input type="checkbox" style="width:10px;height:10px" name="sn[]"'+ '</td>';*/
                table = table + '<td>' +value.description+ '</td>';
              }else{
                table = table + '<td >' +'<input type="text" name="msp_code_edit[]" style="width:60px" class="transparant" value="'+value.msp_code+'" readonly>'+ '</td>';
                table = table + '<td >' +'<input type="text" name="name_product_edit[]" class="transparant" style="width:120px" value="'+value.name_product+'" readonly>'+ '</td>';
                table = table + '<td >' +'<input type="number" name="qty_awal[]" id="qty_awal" data-rowid="'+value.msp_code+'" class="qty_awal" style="width:25px;height:30px;border:none;" value="'+value.qty+'" readonly>'+ '</td>';
                if (value.qty == 0) {
                table = table + '<td >' + '<input type="number" name="qty_terima[]" id="qty_terima" style="width:70px;border:none;" value="0"readonly>'+ '</td>';
                }else{
                table = table + '<td >' + '<input type="number" name="qty_terima[]" id="qty_terima" data-rowid="'+value.msp_code+'" value="0" placeholder="0" class="form-control qty_terima" style="width:70px;border:rounded" required">'+ '</td>';
                }
                table = table + '<td >' +'<input type="text" name="unit_edit[]" class="transparant" value="'+value.unit+'" readonly>'+ '</td>';
                /*if (value.status == 'Y') {
                table = table + '<td >' + 'v'+ '</td>';
                }else if(value.status == 'N'){
                table = table + '<td >' + 'x'+ '</td>';
                }
                else{
                table = table + '<td>' + '<select name="sn_edit[]" id="sn_edit"><option value="Y">Y</option><option value="N">N</option></select>'+ '</td>';
                }*/
                table = table + '<td >' +'<textarea class="transparant" style="width:200px" name="desc_edit[]">'+value.description+'</textarea>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="no_po_edit[]" class="transparant" value="'+value.no_po+'" readonly>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="id_product_edit[]" class="transparant" value="'+value.id_product+'" readonly>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="id_pam[]" class="transparant" value="'+value.id_po_asset+'" readonly>'+ '</td>';

              }
              table = table + '</tr>';
              console.log(value.msp_code);
            });

            $('#mytable').append(table);
             
          }
      });
      
    });

    $('#po-number').on('change',function(e){
      console.log($('#po-number').val());
      var product = $('#po-number').val();

      $.ajax({
          type:"GET",
          url:'/dropdownSubmitPO?product=' + product,
          data:{
            product:this.value,
          },
          success: function(result){
            $('#footer-table').empty();

            var table = "";

            $.each(result[0], function(key, value){
              if (value.status_po == 'PENDING') {
                table = table + '<tr>';
                table = table + '<td colspan="6" >'+'<input type="button" name="update" id="btn-update" class="btn-update btn btn-sm btn-warning" value="update" />'+ '<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left:5px"><i class=" fa fa-times"></i>&nbspClose</button>'+ '</td>';
                table = table + '</tr>';
              }else if (value.status_po == 'FINANCE') {
                table = table + '<tr>';
                table = table + '<td colspan="6" >'+'<input type="button" name="submit" id="btn-save" class="btn-save btn btn-sm btn-primary" value="Submit" />'+ '<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left:5px"><i class=" fa fa-times"></i>&nbspClose</button>'+ '</td>';
                table = table + '</tr>';
              }
            });

            $('#footer-table').append(table);
             
          }
      });
    });

    $(document).on('click', '.show', function(e){
      console.log('haha');
      var rowid = $(this).attr("data-rowid");
      $(".show2[data-rowid='"+rowid+"']").css('display', 'block');
      $(".show[data-rowid='"+rowid+"']").css('display', 'none');
    });

    $(document).on('click', '.show3', function(e){
      console.log('haha');
      var rowid = $(this).attr("data-rowid");
      $(".show4[data-rowid='"+rowid+"']").css('display', 'block');
      $(".show3[data-rowid='"+rowid+"']").css('display', 'none');
    });
    
    function id_product(id_product)
    {
      $('#id_product_edit').val(id_product);
    }


    $(document).on('keyup keydown', "input[id^='qty_terima']", function(e){
    var rowid = $(this).attr("data-rowid");
    var qty_before = $(".qty_awal[data-rowid='"+rowid+"']").val();
    console.log(qty_before);
        if ($(this).val() > parseFloat(qty_before)
            && e.keyCode != 46
            && e.keyCode != 8
           ) {
           e.preventDefault();     
           $(this).val(qty_before);
        }
    });

      
    

  </script>
@endsection