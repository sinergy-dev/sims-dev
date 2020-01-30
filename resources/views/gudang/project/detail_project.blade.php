@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    SIP Detail Delivery Order
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="{{url('inventory/project')}}">Warehouse - Delivery Order</a></li>
    <li class="active">SIP</li>
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
    <div class="box-header">
      
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered display no-wrap" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Product</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Vol</th>
              <th>Kg</th>
              <th>Tanggal keluar</th>
              <th>Serial Number</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            @foreach($detail as $data)
            <tr>
            <td>{{$data->nama}}</td>
            <td>{{$data->ket}}<input type="" name="fk_id_inventory" value="{{$data->fk_id_inventory}}" hidden></td>
            <td>{{$data->qty}}</td>
            <td>{{$data->vol}}</td>
            <td>{{$data->kg}}</td>
            <td>{{$data->tgl_keluar}}</td>
            <td><!-- <button class="btn btn-sm btn-primary btn-details" id="btn-details" name="btn-details" value="{{$data->id_transaction}}" data-rowid="{{$data->fk_id_barang}}">Details</button> -->
            <table>
                <tbody data-rowid="{{$data->fk_id_barang}}"  id="table-details" name="table-details">
                  @foreach($details as $sn)
                  {{ $loop->first ? '' : ', ' }}
                  {{$sn->serial_number}}
                  @endforeach
                </tbody>
              </table>
            <div class="show2" data-rowid="{{$data->fk_id_barang}}">
              <table>
                <tbody data-rowid="{{$data->fk_id_barang}}" class="table-details" id="table-details" name="table-details">
                </tbody>
              </table>
            </div>
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
</section>

@endsection
@section('script')
<script type="text/javascript">
  $('#data_Table').DataTable();

  $(document).on('click', '.btn-details', function(e){

      var rowid = $(this).attr("data-rowid");
      
      var product = $('#btn-details').val();

      $.ajax({

          type:"GET",
          url:'/showDetail?product='+ product,
          data:{
            product:this.value,
          },
          success: function(result){
            $(".show2[data-rowid='"+rowid+"']").toggle('slow');
            $(".table-details[data-rowid='"+rowid+"']").empty();

            var table = "";

            $.each(result[0], function(key, value){
              table = table + '<tr>';
                table = table + '<td>' +value.serial_number+ '</td>';
              table = table + '</tr>';
            });

            $(".table-details[data-rowid='"+rowid+"']").append(table);
             
          }
      });
  }); 

</script>
@endsection