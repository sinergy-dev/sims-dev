@extends('template.main')
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Config Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">DVG</li>
        <li class="active">Config Management</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header">
          <div class="pull-right">
            <button type="button" class="btn btn-primary pull-right float-right margin-left-custom" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd CM </button>
          </div>
          <div class="pull-left">
            <button type="button" class="btn btn-warning dropdown-toggle float-left  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download">&nbspExport</i>
            </button>
              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 13px; left: 10px; transform : translate3d(0px, 37px, 30px);">
              <a class="dropdown-item" href="{{action('INCIDENTController@downloadPDF')}}"> PDF </a><br>
              <a class="dropdown-item" href="{{action('INCIDENTController@exportExcelIM')}}"> EXCEL </a>
            </div>
        </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered display nowrap" id="datastable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>PIC</th>
                <th>Hostname</th>
                <th>Perangkat</th>
                <th>Perubahan</th>
                <th>Resiko</th>
                <th>Downtime</th>
                <th>Keterangan</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
              <?php $no = 1; ?>
              @foreach($datas as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->tgl }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->hostname }}</td>
                <td>{{ $data->perangkat }}</td>
                <td>{{ $data->perubahan }}</td>
                <td>{{ $data->resiko }}</td>
                <td>{{ $data->downtime }}</td>
                <td>{{ $data->keterangan }}</td>
                <td>
                  <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="config('{{$data->no}}','{{$data->tgl}}','{{$data->nik_pic}}','{{$data->hostname}}','{{$data->perangkat}}','{{$data->perubahan}}','{{$data->resiko}}','{{$data->downtime}}','{{$data->keterangan}}')">
                  </button>
                  <a href="{{ url('delete_cm', $data->no) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                  </button></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <!-- MODAL ADD -->
    <div class="modal fade" id="modalAdd" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <center><h3 class="modal-title"><b>Add Config Management</b></h3></center>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/store_cm')}}" id="modalAddCM" name="modalAddCM">
              @csrf

              <div class="form-group">
                <label for="">PIC</label>
                  <select class="form-control" id="nik_pic" name="nik_pic">
                    <option value=""> Select PIC </option>
                      @foreach($owner as $data)
                      @if($data->id_territory == 'DVG')
                        <option value="{{$data->nik}}">{{$data->name}}</option>
                      @endif
                    @endforeach
                  </select>
                    <label>Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_config" name="tanggal_config" required>
                    <label>Hostname</label>
                    <input class="form-control" id="hostname" name="hostname" required>
                    <label>Perangkat</label>
                    <input class="form-control" id="perangkat" name="perangkat" required>
                    <label>Perubahan</label>
                    <input class="form-control" id="perubahan" name="perubahan" required>
                    <label>Resiko</label>
                    <input class="form-control" id="resiko" name="resiko" required>
                    <label>Downtime</label>
                    <input class="form-control" id="downtime" name="downtime" required>
                    <label>Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
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

  <!-- MODAL EDIT -->
  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Config Management</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_cm')}}" id="modalEditCM" name="modalEditCM">
            @csrf

            <div class="form-group" hidden>
                <label>No</label>
                <input class="form-control" id="edit_no" name="no" readonly>
            </div>

            <div class="form-group">
              <label for="">PIC</label>
                <select class="form-control" id="edit_nik_pic" name="nik_pic">
                  <option value=""> Select PIC </option>
                  @foreach($owner as $data)
                    @if($data->id_territory == 'DVG')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                </select>
                  <label>Tanggal</label>
                  <input type="date" class="form-control" id="edit_tanggal_config" name="tanggal_config" required>
                  <label>Hostname</label>
                  <input class="form-control" id="edit_hostname" name="hostname" required>
                  <label>Perangkat</label>
                  <input class="form-control" id="edit_perangkat" name="perangkat" required>
                  <label>Perubahan</label>
                  <input class="form-control" id="edit_perubahan" name="perubahan" required>
                  <label>Resiko</label>
                  <input class="form-control" id="edit_resiko" name="resiko" required>
                  <label>Downtime</label>
                  <input class="form-control" id="edit_downtime" name="downtime" required>
                  <label>Keterangan</label>
              <textarea class="form-control" id="edit_keterangan" name="keterangan"></textarea>
            </div>      
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="popUp" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content modal-style">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ANNOUNCEMENT</h4>
        </div>
        <div class="modal-body">
          <center><h3 class="box-title"><b>SALES APP<b><br><i>(Tender Process)</i></h3></center>
          <div class="row">
            <div class="col-md-12">
              <h4>
                Dear all Sales,<br><br>
                Terdapat beberapa penyesuaian untuk Lead Register dengan rincian sebagai berikut:<br><br>
                <ul>
                  <li>Submitted Price adalah harga nego.<br></li>
                  <li>Deal Price adalah harga sesuai PO.<br><br></li>
                </ul>
                Untuk pengisian proses "Tender Process" terdapat beberapa perubahan:<br><br>
                <ul>
                  <li>Terdapat penambahan status Project Class (Multiyears / Blanket / Normal) yang wajib diisi.<br></li>
                  <li>Project Class Normal untuk project dalam tahun ini, Multiyears project beberapa tahun, dan Blanket adalah project dengan model kontrak payung.<br></li>
                  <li>Jumlah Tahun & Deal Price Total wajib diisi saat memilih Project Class Multiyears / Blanket.<br></li>
                  <li>Untuk status Normal, Deal Price adalah nominal sesuai PO.<br></li>
                  <li>Untuk status Multiyears / Blanket, Deal Price adalah PO tahun ini dan Deal Price Total adalah total nominal PO keseluruhan<br><br></li>
                </ul>
                <b>Mohon Deal Price diisi untuk perhitungan dan report.</b><br><br>
                Terkait perubahan tersebut, Lead Register yang ber-status Win bisa di edit kembali untuk pengisian Deal Price.<br><br>
                Terimakasih.
              </h4>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  </section>
    

@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
  <script type="text/javascript">

     $('#datastable').DataTable( {
        "scrollX": true
        } );

    function config(no, tgl,nik_pic, hostname, perangkat, perubahan, resiko, downtime, keterangan) {
      $('#edit_no').val(no);
      $('#edit_tanggal_config').val(tgl);
      $('#edit_nik_pic').val(nik_pic);
      $('#edit_hostname').val(hostname);
      $('#edit_perangkat').val(perangkat);
      $('#edit_perubahan').val(perubahan);
      $('#edit_resiko').val(resiko);
      $('#edit_downtime').val(downtime);
      $('#edit_keterangan').val(keterangan);
    }
  </script>

  <style type="text/css">

div.box-body {
  overflow: auto;
  white-space: nowrap;
  }

/*div.table-responsive a {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 10px;
  text-decoration: none;
}*/

/* width */
::-webkit-scrollbar {
  width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background-color: #ffb523;
}
  </style>
@endsection