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
      Partnership
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Partnership</li>
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
        
          <div class="pull-right">
            @if(Auth::User()->email == 'tech@sinergy.co.id' || Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER')
              <button type="button" class="btn btn-md btn-success pull-right float-right margin-left-custom" data-target="#modalAddPartnership" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspDok
              </button>
                <!-- <button type="button" class="btn btn-md btn-warning dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="fa fa-download"></i> Export</b>
                </button>
                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                  <a class="dropdown-item" href="{{action('PartnershipController@downloadpdf')}}"> PDF </a>
                  <a class="dropdown-item" href="{{action('PartnershipController@downloadExcel')}}"> EXCEL </a>
                </div> -->
            @endif
          </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered nowrap table-striped dataTable" id="datastable" style="width: 100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Dokumen</th>
                  <th>Deskripsi</th>
                  <th>Dokumen</th>
                  @if(Auth::User()->email == 'tech@sinergy.co.id' || Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER')
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                @foreach($datas as $data)
                <tr>
                  <td>{{ $no++}}</td>
                  <td>{{ $data->nama }}</td>
                  <td>{{ $data->deskripsi }}</td>
                  <td>
                      @if(Auth::User()->email == 'tech@sinergy.co.id' || Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER')
                        <button type="button" data-target="#uploadFile" data-toggle="modal" onclick="upload('{{$data->id_dokumen}}', '{{$data->dokumen}}')" class="btn btn-xs btn-submit" style="vertical-align: top; width: 80px"><i class="fa fa-upload"></i>&nbspUpload</button>
                      @endif
                      @if($data->dokumen == NULL)
                        <button class="btn btn-xs btn-submit disabled" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button>
                      @else
                        <a href="{{ url('download_doc', $data->dokumen) }}" target="_blank" style="color: black"><button class="btn btn-xs btn-submit" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button></a>
                      @endif
                  </td>
                  @if(Auth::User()->email == 'tech@sinergy.co.id' || Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER')
                  <td>

                    <button class="btn btn-xs btn-primary" data-target="#modalEdit" data-toggle="modal" style="vertical-align: top; width: 60px" onclick="partnership('{{$data->id_dokumen}}', '{{$data->nama}}', '{{$data->deskripsi}}')"><i class="fa fa-search"></i>&nbspEdit</button>

                    <a href="{{ url('delete_dok_repo', $data->id_dokumen) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top;width: 60px" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i>&nbspDelete
                    </button></a>

                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>      

    <!--MODAL-->

    <div class="modal fade" id="uploadFile" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Upload File .pdf</h4>
          </div>

          <div class="modal-body">
            <form action="/upload_proses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <input type="text" id="upload_id_dok" name="upload_id_dok" hidden>
                <input type="text" id="upload_doc" name="upload_doc" hidden>
              </div>
              <div class="form-group">
                <label>Current Document</label>
                <input type="text" name="upload_nama_dok" id="upload_nama_dok" class="form-control" disabled>
              </div>
              <div class="form-group">
                <input type="file" id="file" name="file">
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

    <!--MODAL ADD INCIDENT-->
    <div class="modal fade" id="modalAddPartnership" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Dokumen</h4>
        </div>
        <div class="modal-body">
          <form action="/store_dok" method="POST" id="modalAdd" name="modalAdd" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Dokumen</label>
                <input class="form-control" placeholder="Enter Nama Dokumen" id="nama_dok" name="nama_dok" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <input class="form-control" placeholder="Enter Deskripsi" id="deskripsi" name="deskripsi" required>
            </div>   

            <div class="form-group">
                <input type="file" id="file" name="file">
            </div>  
             
            <div class="modal-footer">
              <button type="button" class="btn btn-md btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
              <!-- <input type="button" name="add_incident" id="add_incident" class="btn btn-sm btn-primary" value="Submit" /> -->
            </div>
        </form>
        </div>
      </div>
    </div>
</div>
  
  <!--MODAL EDIT INCIDENT-->
  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_dok')}}" id="modalEditPartnership" name="modalEditPartnership">
            @csrf

            <input type="text" name="edit_id" id="edit_id" hidden>

            <div class="form-group">
                <label>Nama Dokumen</label>
                <input class="form-control" id="edit_nama_dok" name="edit_nama_dok">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <input class="form-control" id="edit_deskripsi" name="edit_deskripsi">
            </div>
             
            <div class="modal-footer">
              <button type="button" class="btn btn-md btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-md btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

  </section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">
    
     $('#datastable').DataTable({
      "scrollX": 200,
      pageLength:25,
     });

     $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
      });

     $('.money').mask('000,000,000,000,000.00', {reverse: true});

     function partnership(id_dokumen,nama,deskripsi) {
      $('#edit_id').val(id_dokumen);
      $('#edit_nama_dok').val(nama);
      $('#edit_deskripsi').val(deskripsi);
    }

    function upload(id_dokumen, dokumen) {
      $('#upload_id_dok').val(id_dokumen);
      $('#upload_doc').val(dokumen);
      $('#upload_nama_dok').val(dokumen);
    }

  </script>
@endsection