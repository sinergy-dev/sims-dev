@extends('template.main')
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
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

  .modalIconn input[type=text]{
    padding-left:10px;
  }

  .modalIconn i{
    position:absolute;
    right:0px;
    top:0px;
    padding:9px 8px;
    color:#aaa;
    transition:.3s;
  }

  .modalIconn.inputIconBg input[type=text]:focus + i{
    color:#fff;
    background-color:dodgerBlue;
  }

  .modalIconn.inputIconBg i{
    background-color:#aaa;
    color:#fff;
    padding:7px 4px ;
    border-radius:0px 4px 4px 0px;
  }

  .modalIconn{
    position:relative;
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
@endsection

@section('content')

<section class="content-header">
  <h1>
    Bank Garansi
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{url('/bank_garansi')}}"><i class="fa fa-dashboard"></i> Bank garansi</a></li>
    <li class="active"> Edit Bank garansi </li>
    <!-- <li class="active">PR Asset</li>
    <li class="active">MSP</li>
    <li class="active">Add</li> -->
  </ol>
</section>

<section class="content">
  

  <div class="box">
    <div class="box-header">
    </div>

    <div class="box-body">
      <form method="POST" action="{{url('/update_bg')}}" id="modal_pr_asset" name="modal_pr_asset">
        @csrf
      <input type="" name="id" value="{{$datas->id_bank_garansi}}" hidden>
        <div class="row">
          <div class="col-sm-7">
            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Kode Proyek</label>
              <div class="col-sm-10">
                <input class="form-control" name="kode_proyek" id="kode_proyek" type="text" placeholder="Enter Kode Proyek" value="{{$datas->kode_proyek}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Nama Proyek</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="nama_proyek" id="nama_proyek" type="text" placeholder="Enter Nama Proyek">{{$datas->nama_proyek}}</textarea>
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">No Proyek</label>
              <div class="col-sm-10">
                <input type="text" name="no" id="no" class="form-control" placeholder="Enter No Proyek" value="{{$datas->no_proyek}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Perusahaan</label>
              <div class="col-sm-10">
                <input type="text" name="perusahaan" id="perusahaan" class="form-control" placeholder="Enter Perusahaan" value="{{$datas->perusahaan}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Division</label>
              <div class="col-sm-10">
                <input type="text" name="div" id="div" class="form-control" placeholder="Enter Division" value="{{$datas->division}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">No Telp</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" placeholder="Enter No Telp" name="no_telp" id="no_telp" value="{{$datas->telp}}">
              </div>
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
              <label class="col-sm-4 control-label">No Fax</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" placeholder="Enter No Fax" name="no_fax" id="no_fax" value="{{$datas->fax}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Alamat</label>
              <div class="col-sm-8">
                <textarea class="form-control" placeholder="Enter Alamat" name="alamat" id="alamat">{{$datas->alamat}}</textarea>
              </div>
            </div>

            <!-- <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Kota</label>
              <div class="col-sm-8">
                <input class="form-control" name="kota" id="kota" placeholder="Enter Kota">
              </div>
            </div> -->

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Kode Pos</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Enter Kode Pos" name="kode" id="kode" value="{{$datas->kode_pos}}">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Note</label>
              <div class="col-sm-8">
                <textarea class="form-control" placeholder="Enter Note" name="note" id="note">{{$datas->note}}</textarea>
              </div>
            </div>

          </div>
        </div>
        <legend></legend>
        <table id="product-add" class="table">
            <tr class="tr-header">
              <th>Jenis</th>
              <th>Penerbit</th>
              <th>Tanggal mulai</th>
            </tr>
            <tr>
              <td style="margin-bottom: 50px">
                <br>
                @if($datas->jenis == 'Jaminan Penawaran')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran" selected>Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
                @elseif($datas->jenis == 'Jaminan Pelaksanaan (Standard)')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)" selected>Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
                @elseif($datas->jenis == 'Jaminan Pelaksanaan (Khusus)')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)" selected>Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
                @elseif($datas->jenis == 'Jaminan Pemeliharaan')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan" selected>Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
                @elseif($datas->jenis == 'Referensi Bank')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank" selected>Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
                @elseif($datas->jenis == 'Surat Dukungan Bank')
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan" selected>Surat Dukungan Bank</option>
                </select>
                @endif
              </td>
              <td style="margin-bottom: 50px;">
                <br>
                @if($datas->penerbit == 'Bank')
                <select class="form-control" name="penerbit" id="penerbit">
                  <option>Select Penerbit</option>
                  <option value="Bank" selected>Bank</option>
                  <option value="Asuransi">Asuransi</option>
                </select>
                @elseif($datas->penerbit == 'Asuransi')
                <select class="form-control" name="penerbit" id="penerbit">
                  <option>Select Penerbit</option>
                  <option value="Bank">Bank</option>
                  <option value="Asuransi" selected>Asuransi</option>
                </select>
                @endif
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="tgl_mulai" id="tgl_mulai" value="{{$datas->tgl_mulai}}">
                </div>
                <!-- <input class="form-control" type="date" name="tgl_mulai" value="{{$datas->tgl_mulai}}"> -->
              </td>
            </tr>
            <tr class="tr-header">
              <th>Tanggal Selesai </th>
              <th>Jangka Waktu </th>
              <th>Dokumen</th>
            </tr>
            <tr>
              <td style="margin-bottom: 50px">
                <br>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="tgl_selesai" id="tgl_selesai" value="{{$datas->tgl_selesai}}">
                </div>
                <!-- <input class="form-control" type="date" name="tgl_selesai" value="{{$datas->tgl_selesai}}"> -->
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <div class="modalIconn inputIconBg" style="padding-left: 10px">
                  <input type="text" class="form-control" name="jangka" id="jangka" value="{{$datas->jangka_waktu}}">
                  <i class="" aria-hidden="true">Hari</i>
                </div>
                <!-- <input type="text" class="form-control" name="jangka" id="jangka" value="{{$datas->jangka_waktu}}"> -->
              </td>
              <td style="margin-bottom: 50px">
                <br>
                @if($datas->dok_ref == 'Pengumuman')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman" selected>Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'PO/SPK')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK" selected>PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'Kontrak')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak" selected>Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'Berita Acara')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara" selected>Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'Internet')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet" selected>Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'Dokumen')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen" selected>Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
                @elseif($datas->dok_ref == 'Dokumen')
                <select class="form-control" name="dokumen" id="dokumen">
                  <option>Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR" selected>RKS/RFP/TOR</option>
                </select>
                @endif
              </td>
            </tr>
            <tr class="tr-header">
              <th>No Dokumen</th>
              <th>Valuta</th>
              <th>Nominal</th>
            </tr>
            <tr>
              <td style="margin-bottom: 50px">
                <br><input type="text" class="form-control" placeholder="Enter No Dok" name="no_dok" id="no_dok" value="{{$datas->no_dok}}">
              </td>
              <td style="margin-bottom: 50px">
                <br>
                @if($datas->valuta == 'USD')
                <select class="form-control" name="valuta" id="valuta">
                  <option>Select Valuta</option>
                  <option value="USD" selected>USD</option>
                  <option value="IDR">IDR</option>
                </select>
                @elseif($datas->valuta == 'IDR')
                <select class="form-control" name="valuta" id="valuta">
                  <option>Select Valuta</option>
                  <option value="USD">USD</option>
                  <option value="IDR" selected>IDR</option>
                </select>
                @endif
              </td>
              <td style="margin-bottom: 50px">
                <br><div class="modalIcon inputIconBg" style="padding-left: 10px">
                <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal" id="nominal" value="{{$datas->nominal}}">
                <i class="" aria-hidden="true">Rp.</i>
                </div>
              </td>
            </tr>
          </table>
        <div class="col-md-12"  id="btn_submit">
          <br>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
        </div>
      </form>
    </div>
  </div>
</section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection()

@section('script')
  
  <script type="text/javascript">
  	function showMe(e) {
	// i am spammy!
	  alert(e.value);
	}

  $('.money').mask('000,000,000,000,000', {reverse: true});

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_Table').DataTable( {
     "scrollX": true,
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    });

    $('#project_id').select2();  

    $('#owner_pr').select2();

    $('#tgl_mulai').datepicker({
      autoclose: true
    })

    $('#tgl_selesai').datepicker({
      autoclose: true
    })

    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;
  </script>
@endsection