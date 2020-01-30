@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    SIP Detail Asset Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Warehouse - Asset Management</li>
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
			          <th>No</th>
			          <th>Nama</th>
			          <th>Jumlah dipinjam</th>
			          <th>Peminjam</th>
			          <th>Tgl Peminjaman</th>
			          <th>Tgl Pengembalian</th>
			          <th>Note</th>
			          <th>Status</th>
			        </tr>
			      </thead>
			      <tbody id="products-list" name="products-list">
			        <?php $no = 1; ?>
			        @foreach($detail_asset as $data)
			        <tr>
			          <td>{{$no++}}</td>
			          <td>{{$data->nama}}</td>
			          <td>{{$data->qty_pinjam}}</td>
			          <td>{{$data->name}}</td>
			          <td>{{$data->tgl_peminjaman}}</td>
			          <td>{{$data->tgl_pengembalian}}</td>
			          <td>{{$data->note}}</td>
			          <td>
			            @if($data->status == 'PENDING')
			              <label class="status-open">PENDING</label>
			            @elseif($data->status == 'ACCEPT')
			              <label class="status-win" style="width: 90px">ACCEPTED</label>
			            @elseif($data->status == 'REJECT')
			              <!-- <button class=" btn btn-sm status-lose" data-target="#reject_note_modal" data-toggle="modal" style="width: 90px;" onclick="reject('{{$data->id_transaksi}}', '{{$data->note}}')"> REJECTED</button> -->
			              <label class="status-lose" style="width: 90px">REJECTED</label>
			            @elseif($data->status == 'AMBIL')
			             <label class="status-lose" style="width: 150px;background-color: #7735a3">SUDAH DI AMBIL</label>
			            @elseif($data->status == 'RETURN')
			              <label class="status-win" style="width: 90px">RETURNED</label>
			            @endif
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

<!--Modal Edit SN-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Update Serial Number</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('update_serial_number') }}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" name="id_detail_edit" id="id_detail_edit" hidden>
      
          <div class="form-group">
            <label for="">Serial Number</label>
            <input type="text" class="form-control" placeholder="Enter Serial Number" name="edit_serial_number" id="edit_serial_number">
          </div> 

          <div class="form-group">
            <label for="">Note(Jika Perlu)</label>
            <textarea type="text" class="form-control" name="note_edit" id="note_edit"></textarea>
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

</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function warehouse(id_detail,serial_number,note) {
      $('#id_detail').val(id_detail);
      $('#id_detail_edit').val(id_detail);
      $('#edit_serial_number').val(serial_number);
      $('#note_edit').val(note);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $("#data_Table").DataTable({
     "columnDefs": [
        { "width": "5%", "targets": 6},
        { "width": "5%", "targets": 0}
      ],
    });
  </script>
@endsection