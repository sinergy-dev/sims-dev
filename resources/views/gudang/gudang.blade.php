@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
  input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}

.inputWithIcon input[type=text]{
    padding-left:40px;
}

input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(3); /* IE */
  -moz-transform: scale(3); /* FF */
  -webkit-transform: scale(3); /* Safari and Chrome */
  -o-transform: scale(3); /* Opera */
      margin-top: 13px;
    margin-right: 8px;
  
}
input[type=text]:focus{
    border-color:dodgerBlue;
    box-shadow:0 0 8px 0 dodgerBlue;
  }

 .inputWithIcon.inputIconBg input[type=text]:focus + i{
    color:#fff;
    background-color:dodgerBlue;
  }

 .inputWithIcon.inputIconBg i{
    background-color:#aaa;
    color:#fff;
    padding:10px 9px;
    border-radius:4px 0 0 4px;
  }

 .inputWithIcon{
    position:relative;
  }

 .inputWithIcon i{
    position:absolute;
    left:0;
    top:0;
    padding:9px 9px;
    color:#aaa;
    transition:.3s;
  }

  div.box-header{
    height: 50px;
  }
</style>

<section class="content-header">
  <h1>
    SIP Inventory
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Warehouse - Inventory</li>
    <li class="active">SIP</li>
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
      <button class="btn btn-sm btn-primary margin-bottom pull-left" id="" data-target="#modal_product" data-toggle="modal" style="width: 150px;"><i class="fa fa-plus"> </i>&nbsp Penerimaan Barang</button>

      <div class="form-group inputWithIcon inputIconBg pull-right" style="width: 200px">
      <input class="form-control" id="searchbox" type="text" placeholder="Search Box" />
      <i class="fa fa-search"></i>
      </div>

    </div>

    <div class="box-body">
      <div>
        <h5><i class="fa fa-table"></i>&nbsp Table Inventory</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Serial Number</th>
              <th>Name</th>
              <th>Category</th>
              <th>Type</th>
              <th>Qty</th>
              <th>No. Purchase Order</th>
              <th>Description</th>
              {{-- <th>Action</th> --}}
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            <?php $no = 1; ?>
            @foreach($data as $data)
            <tr>
              <td>{{$no++}}</td>
              @if($data->status != '')
              <td><a href="{{url('detail_inventory',$data->id_barang)}}"><button class="btn btn-sm btn-primary">Detail</button></a></td>
              @else
              <!-- <form method="POST" action="{{url('/inventory/store_detail_produk')}}">
                @csrf
                <td><button class="btn btn-sm btn-default" style="opacity: 0.5" name="id_barang_detail" value="{{$data->id_barang}}" type="submit"><input type="" name="qty" value="{{$data->qty}}" hidden><input type="" name="po_detail" value="{{$data->id_po}}" hidden>Detail</button></td>
              </form> -->
              <td><button class="btn btn-sm btn-default" style="opacity: 0.5" type="submit">Detail</button></td>
              @endif
              <td>{{$data->nama}}</td>
              <td>{{$data->kategori}}</td>
              <td>{{$data->tipe}}</td>
              <td>{{$data->qty}}</td>
              <td>{{$data->no_po}}</td>
              <td>{{$data->note}}</td>
              {{-- <td>
                <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 40px;text-align: center;" onclick="warehouse('{{$data->id_barang}}','{{$data->nama}}','{{$data->qty}}','{{$data->note}}','{{$data->id_po}}')">
                </button>
                <a href="{{ url('/warehouse/destroy_produk', $data->id_barang) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                </button></a>
              </td> --}}
            </tr>
            @endforeach
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>

<!--Modal SN-->
<div class="modal fade" id="modal-sn" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content modal-sm">
        <div class="modal-body">
          <div class="form-group">
            <form name="sn-modal" id="sn-modal">
              <label>Add Serial Number</label>
              @csrf
                <input type="" class="id_barangs" name="id_barangs" id="id_barangs" hidden>
                <h6>Masukkan Serial Number sebanyak barang yang baru datang !</h6>
                <h6>Stock baru datang : <input type="" class="qtys" name="qty" id="qtys" style="border-style: none;font-style: bold;font-size:14px; "></h6><br>
                <textarea class="form-control serial_number" rows="10" name="serial_number" id="serial_number"></textarea>
                <hr>
              <input class="btn btn-sm btn-primary float-right btn-sn" id="btn-sn" value="submit" disabled>
              <!-- <button type="submit" class="btn btn-sm btn-primary">Submit</button> -->
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_product" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-lg">
        <div class="modal-header">
          <h4 class="modal-title">Form Penerimaan Barang</h4>
        </div>
        <div class="modal-body">
        <form method="" action="" id="store_lead" name="store_lead">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>No. Purchase Order</label>
              <select name="po_number" id="po-number" class="form-control">
                <option value="">-- Select Purchase Order --</option>
                @foreach($po as $data)
                <option value="{{$data->id_pam}}">{{$data->no_po}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th rowspan="2" style="text-align: center;vertical-align:center;">Nama Barang</th>
                  <th colspan="3" style="text-align: center;vertical-align:center;">Qty</th>
                  <th rowspan="2" style="text-align: center;vertical-align:center;">Kategori</th>
                  <th rowspan="2" style="text-align: center;vertical-align:center;">Tipe</th>
                  <th rowspan="2" style="text-align: center;vertical-align:center;">Description</th>
                </tr>
                <tr>
                  <th style="text-align: center;vertical-align:center;">On Process</th>
                  <th style="text-align: center;vertical-align:center;">Stock</th>
                  <th style="text-align: center;vertical-align:center;">Input</th>
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
</div>

<!--Modal Edit-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Product</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('warehouse/inventory_update') }}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" placeholder="Enter Item Code" name="edit_id_barang" id="edit_id_barang" hidden>
          <input type="text" name="po_detail_edit" id="po_detail_edit" hidden>

          <div class="form-group">
            <label for="">Name</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="edit_name" id="edit_name">
          </div> 
          <div class="form-group">
            <label for="">Quantity</label>
            <input type="number" class="form-control" placeholder="Enter Quantity" name="" id="edit_quantity" disabled>
          </div>
          <div class="form-group">
            <label for="">Tambah stock &nbsp&nbsp&nbsp<input type="checkbox" id="yourBox" style="width: 7px;height: 7px" /> *centang jika ingin menambah</label>
            <input type="number" class="form-control" placeholder="Enter Quantity" name="edit_quantity" id="yourText" disabled>
          </div> 
          <div class="form-group">
            <label for="">Description</label>
            <input type="text" class="form-control" placeholder="Enter Information" name="edit_information" id="edit_information">
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

    .dataTables_paging {
     display: none;
    }

</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function warehouse(id_barang,nama,qty,note,id_po) {
      $('#edit_id_barang').val(id_barang);
      $('#edit_name').val(nama);
      $('#edit_quantity').val(qty);
      $('#edit_information').val(note);
      $('#po_detail_edit').val(id_po);
    }
/*
    $(document).on('click', '.btn-sn', function(e){
      var lines = $('textarea').val().split('\n');
      console.log(lines);
      for(var i = 0;i < lines.length;i++){
          
      }
    });*/

    $(document).on('click', '.btn-sn', function(e){
      var qty           = $("#qtys").val();
      var lines         = $('textarea').val().split('\n');
      var sn            = lines.length;

      if (sn == qty) {
        $.ajax({
          type:"POST",
          url:'/update_serial_number',
          data:$('#sn-modal').serialize(),
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
      } else if (sn == 0) {
        alert('Jumlah tidak sesuai !')
      } else{
        alert('Jumlah tidak sesuai !')
      }
      
    });

    $('.btn_sn').click(function(){
        $.ajax({
          type:"GET",
          url:'/getbtnSN',
          data:{
            product:this.value,
          },
          success: function(result){
              $("#id_barangs").val(result[0].id_barang);
              $("#qtys").val(result[0].qty);
          }
        }); 
        $("#modal-sn").modal("show");
    });


/*    function id_barang(id_barang,qty_sn)
    {
      $('#id_barangs').val(id_barang);
      $('#qtys').val(qty_sn);
    }
*/
    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $("#data_Table").DataTable({
     "columnDefs": [
        { "width": "10%", "targets": 7},
        { "width": "5%", "targets": 0},
        { "width": "10%", "targets": 1},
        { "width": "5%", "targets": 5}
      ],  
      pageLength: 10,
      "bLengthChange": false,
    });

    $(document).ready(function() {
    var dataTable = $('#data_Table').dataTable();
      $("#searchbox").keyup(function() {
          dataTable.fnFilter(this.value);
      });   
    });

    document.getElementById('yourBox').onchange = function() {
      document.getElementById('yourText').disabled = !this.checked;
    };

    $(document).on('keyup keydown', ".serial_number", function(e){
      $("#btn-sn").prop('disabled', false);

    });


    $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
    });

    $('#po-number').on('change',function(e){
      console.log($('#po-number').val());
      var product = $('#po-number').val();

      $.ajax({
          type:"GET",
          url:'/dropdownPoSIP?product=' + product,
          data:{
            product:this.value,
          },
          success: function(result){
            $('#mytable').empty();

            var table = "";

            $.each(result[0], function(key, value){
              table = table + '<tr>';
                table = table + '<td >' +'<input type="text" name="name_product_edit[]" class="transparant" style="width:120px" value="'+value.name_product+'" readonly>'+ '</td>';
                table = table + '<td >' +'<input type="number" name="qty_awal[]" id="qty_awal" data-rowid="'+value.name_product+'" class="qty_awal" style="width:25px;height:30px;border:none;text-aligncenter;vertical-align:center" value="'+value.qty+'" readonly>'+ '</td>';
                if (value.qty == 0) {
                table = table + '<td >' + '<input type="number" class="qty_terima" name="qty_terima[]" id="qty_terima" style="width:25px;height:30px;border:none;" value="0"readonly>'+ '</td>';
                table = table + '<td >' +'<input type="number" class="qty_awal" style="width:25px;height:30px;border:none;text-aligncenter;vertical-align:center" readonly>'+ '</td>';
                table = table + '<td >' +'<input type="number" class="qty_awal" style="width:25px;height:30px;border:none;text-aligncenter;vertical-align:center" readonly>'+ '</td>';
                }else if (value != 0 && value.status_po == 'PENDING') {
                table = table + '<td >' +'<input type="number" name="qty_katalog[]" id="qty_katalog" class="qty_katalog" style="width:25px;height:30px;border:none;" value="'+value.qty_katalog+'" readonly>'+ '</td>';
                table = table + '<td >' + '<input type="number" name="qty_terima[]" id="qty_terima" data-rowid="'+value.name_product+'" value="0" placeholder="0" class="form-control qty_terima" style="width:60px;border:rounded" required">'+ '</td>';
                table = table + '<td >' + '<input type="text" name="kategori[]" id="kategori" data-rowid="'+value.name_product+'" value="'+value.kategori+'" class="form-control kategori" readonly style="width:100px;border:rounded" ">'+ '</td>';
                table = table + '<td >' + '<input type="text" name="tipe[]" id="tipe" data-rowid="'+value.name_product+'" value="'+value.tipe+'"  class="form-control tipe" readonly style="width:100px;border:rounded" ">'+ '</td>';
                }else{
                table = table + '<td >' + '<input type="number" name="qty_terima[]" id="qty_terima" data-rowid="'+value.name_product+'" value="0" placeholder="0" class="form-control qty_terima" style="width:60px;border:rounded" required">'+ '</td>';
                table = table + '<td >' + '<input type="text" name="kategori[]" id="kategori" data-rowid="'+value.name_product+'" class="form-control kategori" readonly style="width:100px;border:rounded" ">'+ '</td>';
                table = table + '<td >' + '<input type="text" name="tipe[]" id="tipe" data-rowid="'+value.name_product+'"  class="form-control tipe" readonly style="width:100px;border:rounded" ">'+ '</td>';
                }

                console.log(value.kategori);

               
                

                
                /*if (value.status == 'Y') {
                table = table + '<td >' + 'v'+ '</td>';
                }else if(value.status == 'N'){
                table = table + '<td >' + 'x'+ '</td>';
                }
                else{
                table = table + '<td>' + '<select name="sn_edit[]" id="sn_edit"><option value="Y">Y</option><option value="N">N</option></select>'+ '</td>';
                }*/
                table = table + '<td >' +'<textarea class="transparant" style="width:180px" name="desc_edit[]">'+value.description+'</textarea>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="no_po_edit[]" class="transparant" value="'+value.no_po+'" readonly>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="id_product_edit[]" class="transparant" value="'+value.id_product+'" readonly>'+ '</td>';
                table = table + '<td hidden>' +'<input type="text" name="id_pam[]" class="transparant" value="'+value.id_po_asset+'" readonly>'+ '</td>';

              table = table + '</tr>';

            console.log(value.id_category)
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
          url:'/dropdownSubmitPoSIP?product=' + product,
          data:{
            product:this.value,
          },
          success: function(result){
            $('#footer-table').empty();

            var table = "";

            $.each(result[0], function(key, value){
              if (value.status_po == 'PENDING') {
                table = table + '<tr>';
                table = table + '<td colspan="6" >'+'<input type="button" name="update" id="btn-update" class="btn-update btn btn-sm btn-warning" value="update" />'+ '<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" style="margin-left:5px"><i class=" fa fa-times"></i>&nbspClose</button>'+ '</td>';
                table = table + '</tr>';
              }else if (value.status_po == 'FINANCE') {
                table = table + '<tr>';
                table = table + '<td colspan="6" >'+'<input type="button" name="submit" id="btn-save" class="btn-save btn btn-sm btn-primary" value="Submit" />'+ '<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" style="margin-left:5px"><i class=" fa fa-times"></i>&nbspClose</button>'+ '</td>';
                table = table + '</tr>';
              }
            });

            $('#footer-table').append(table);
             
          }
      });
    });


  /*  $('#submit').click(function(){            
         $.ajax({  
              url:"/inventory/store",  
              method:"POST",  
              data:$('#add_produk').serialize(),  
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

    $(document).on('keyup keydown', "input[id^='qty_terima']", function(e){
    var rowid = $(this).attr("data-rowid");
    var qty_before  = $(".qty_awal[data-rowid='"+rowid+"']").val();
    var kategori  = $(".kategori[data-rowid='"+rowid+"']").val();
    var tipe    = $(".tipe[data-rowid='"+rowid+"']").val();
    console.log(qty_before);
        if ($(this).val() > parseFloat(qty_before)
            && e.keyCode != 46
            && e.keyCode != 8 
           ) {
           e.preventDefault();     
           $(this).val(qty_before);
        }

        if (this.value != "" || this.value.length > 0) {
        $(".kategori[data-rowid='"+rowid+"']").prop('readonly', false);
        $(".tipe[data-rowid='"+rowid+"']").prop('readonly', false);
     }else{
        $(".kategori[data-rowid='"+rowid+"']").prop('readonly', true);
        $(".tipe[data-rowid='"+rowid+"']").prop('readonly', true);
     }
    });

    $(document).on('click', '.btn-save', function(e){
      var rowid = $(this).attr("data-rowid");
      var cek_qty = $(".qty_terima[data-rowid='"+rowid+"']").val();

      var cekk = cek_qty.length;
      if (cekk = 0) {
        alert('isi qty min 0');
      }else{
        $.ajax({
          type:"POST",
          url:'/inventory/store',
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
      }
      
    });

    $(document).on('click', '.btn-update', function(e){
      var rowid = $(this).attr("data-rowid");
      var cek_qty = $(".qty_terima[data-rowid='"+rowid+"']").val();

      console.log($(".qty_terima[data-rowid='"+rowid+"']").val());
      if (cek_qty = null) {
        alert('isi qty min 0');
      }else{
        $.ajax({
          type:"POST",
          url:'/inventory/update',
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
      }
    }); 

  </script>
@endsection