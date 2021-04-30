@extends('template.main')
@section('head_css')
<!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<style type="text/css">
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
    background-color: #eee;
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

  textarea{
    white-space: pre-line; 
    white-space: pre-wrap
  }
  .alert-box {
    color:#555;
    border-radius:10px;
    font-family:Tahoma,Geneva,Arial,sans-serif;font-size:14px;
    padding:10px 36px;
    margin:10px;
  }
  .alert-box span {
      font-weight:bold;
      text-transform:uppercase;
  }
  .error {
      background:#ffecec;
      border:1px solid #f5aca6;
  }
  .success {
      background:#e9ffd9 ;
      border:1px solid #a6ca8a;
  }
  .form-control-medium{
      display: block;
      width: 60%;
      padding: .375rem .75rem;
      padding-top: 0.375rem;
      padding-right: 0.75rem;
      padding-bottom: 0.375rem;
      padding-left: 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: .40rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }
  .form-control-produk{
      display: block;
      width: 140%;
      padding: .375rem .75rem;
      padding-top: 0.375rem;
      padding-right: 0.75rem;
      padding-bottom: 0.375rem;
      padding-left: 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: .40rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }
  /*for modal*/
    input[type=text]:focus{
      border-color:dodgerBlue;
      box-shadow:0 0 8px 0 dodgerBlue;
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
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .newIcon{
      position:relative;
    }

   .newIcon i{
      position:absolute;
      left:0px;
      top:28px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }
</style>
@endsection


@section('content')
<section class="content-header">
  <h1>Bank Garansi</h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
</section>

<section class="content">

  @if(session('success'))
    <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
  @endif

  <div class="box">
    <div class="box-header with-border">
      <div class="pull-right">
          <a href="{{url('/add_bgaransi')}}">
            <button class="btn btn-sm btn-success pull-right float-right margin-left-custom" style="display: none;" id="btnAdd"><i class="fa fa-plus"> </i>&nbsp Add</button>
          </a>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="datasmu" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Kode Proyek</th>
              <th>Nama Proyek</th>
              <th>No Proyek</th>
              <th>Perusahaan</th>
              <th>Alamat</th>
              <th>Jenis</th>
              <th>Penerbit</th>
              <th>Dokumen</th>
              <th>Action</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($datas as $data)
            <tr>
              <td><input type="" name="id_bg" value="{{$data->id_bank_garansi}}" hidden>{{$data->kode_proyek}}</td>
              <td>{{$data->nama_proyek}}</td>
              <td>{{$data->no_proyek}}</td>
              <td>{{$data->perusahaan}}</td>
              <td>{{$data->alamat}}</td>
              <td>{{$data->jenis}}</td>
              <td>{{$data->penerbit}}</td>
              <td>{{$data->dok_ref}} <i>&nbsp&nbsp&nbsp{{$data->no_dok}}</i></td>
              <td>
                  @if($data->status == 'new' && $data->nik == Auth::User()->nik)
                  <a href="{{url('/edit_bg',$data->id_bank_garansi)}}"><button class="btn btn-xs btn-primary" style="width: 70px; height: 30px;float: left">Edit</button></a>
                  @elseif($data->status == 'done' || $data->status == 'proses' && $data->nik == Auth::User()->nik)
                  <button class="btn btn-xs btn-primary" style="width: 70px; height: 30px;float: left;" disabled>Edit</button>
                  @else
                  <a href="{{url('/edit_bg',$data->id_bank_garansi)}}"><button class="btn btn-xs btn-primary" id="btnEdit" style="width: 70px; height: 30px;float: left">Edit</button></a>
                  @endif
                  @if($data->status == 'new' && $data->nik != Auth::User()->nik)
                  <button class="btn btn-xs btn-default" style="width: 70px; height: 30px;float: left; margin-top: 5px" id="btnAccept" data-toggle="modal" data-target="#modal_accept" onclick="accept('{{$data->id_bank_garansi}}')">Accept</button>
                  @endif
                  @if($data->status != 'new' && $data->nik != Auth::User()->nik)
                  <div class="dropdown" style="float: left">
                    <button class="btn btn-warning-eksport dropdown-toggle margin-left-customt" style="width: 70px; height: 30px; margin-top: 5px" id="btnExport" type="button" data-toggle="dropdown" >Pdf
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="{{action('BGaransiController@pdf',$data->id_bank_garansi)}}" target="_blank" onclick="print()">Form Request</a></li>
                      <li><a href="{{action('BGaransiController@downloadpdfsk', $data->id_bank_garansi)}}" target="_blank" onclick="print()">Surat Kuasa</a></li>
                    </ul>
                  </div>
                  @endif
                  @if($data->status == 'proses' && $data->nik != Auth::User()->nik)
                  <button class="btn btn-xs btn-success" style="width: 70px; height: 30px;float: left; margin-top: 5px" id="btnSubmit" data-toggle="modal" data-target="#modal_submit" onclick="submit('{{$data->id_bank_garansi}}')">Submit</button>
                  @endif
                </div>
              </td>
              <td>
                @if($data->status == 'new')
                  <i style="opacity: 0.01">A</i><label class="status-initial">NEW</label>
                @elseif($data->status == 'done')
                  <i style="opacity: 0.01">C</i><label class="status-open btn-success" >DONE</label> &nbsp {!!substr($data->updated_at,0,10)!!}
                @elseif($data->status == 'proses')
                  <i style="opacity: 0.01">B</i><label class="status-tp">PENDING</label>
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


<div class="modal fade" id="modal_submit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Submit</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_status')}}" id="" name="">
            @csrf
          <input id="id_bg_submit" type="text" name="id_bg_submit" hidden>
          <div class="form-group">
            <div style="width: 250px">
              <h4><b>Are you sure to submit?</b></h4>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal_accept" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Accept</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('accept_status')}}" id="" name="">
            @csrf
          <input id="id_bg_accept" type="text" name="id_bg_accept" hidden>
          <div class="form-group">
            <div style="width: 250px">
              <h4><b>Are you sure to accept?</b></h4>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/lins/jqeury/1.12.0/jqeury.min.js"></script>
<script src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript">
   

      @if (Auth::User()->id_division != 'HR') {
        $('#datasmu').DataTable({
          pageLength: 25,
        });
      }@elseif(Auth::User()->id_division == 'HR'){
        $('#datasmu').DataTable({
          pageLength: 25,
        "columnDefs":[
           {"width": "12%", "targets":8},
        ],
          });
        } 
      @endif   

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    function submit(id_bank_garansi){
      $('#id_bg_submit').val(id_bank_garansi);
    }

    function accept(id_bank_garansi){
      $('#id_bg_accept').val(id_bank_garansi);
    }

    function print()
    {
      window.print();
    }
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector(".today").value = today;
        
</script>
@endsection

