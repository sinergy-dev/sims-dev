@extends('template.main')
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
<style type="text/css">

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
      border-radius:4px 0 0 4px;
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
@section('content')

<section class="content-header">
  <h1>
    Add Bank Garansi
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Bank Garansi</li>
    <!-- <li class="active">MSP</li>
    <li class="active">Add</li> -->
  </ol>
</section>

<section class="content">
  

  <div class="box">
    <div class="box-header">
      
    </div>


    <div class="box-body">
      <form method="POST" action="{{'/store_bgaransi'}}" id="modal_pr_asset" name="modal_pr_asset">
        @csrf
        <div class="row">
          <div class="col-sm-7">
            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Kode Proyek</label>
              <div class="col-sm-10">
                <input class="form-control" name="kode_proyek" id="kode_proyek" type="text" placeholder="Enter Kode Proyek">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Nama Proyek</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="nama_proyek" id="nama_proyek" type="text" placeholder="Enter Nama Proyek"></textarea>
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">No Proyek</label>
              <div class="col-sm-10">
                <input type="text" name="no" id="no" class="form-control" placeholder="Enter No Proyek">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Perusahaan</label>
              <div class="col-sm-10">
                <input type="text" name="perusahaan" id="perusahaan" class="form-control" placeholder="Enter Perusahaan">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">Division</label>
              <div class="col-sm-10">
                <input type="text" name="div" id="div" class="form-control" placeholder="Enter Division">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-2 control-label">No Telp</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" placeholder="Enter No Telepon" name="no_telp" id="no_telp">
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
                <input type="number" class="form-control" placeholder="Enter No Fax" name="no_fax" id="no_fax">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Alamat</label>
              <div class="col-sm-8">
                <textarea class="form-control" placeholder="Enter Alamat" name="alamat" id="alamat"></textarea>
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
                <input type="number" class="form-control" placeholder="Enter Kode Pos" name="kode" id="kode">
              </div>
            </div>

            <div class="form-group row" style="margin-left: -12px">
              <label class="col-sm-4 control-label">Note</label>
              <div class="col-sm-8">
                <textarea class="form-control" placeholder="Enter Note" name="note" id="note"></textarea> 
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
                <select class="form-control" name="jenis" id="jenis">
                  <option>Select Jenis</option>
                  <option value="Jaminan Penawaran">Jaminan Penawaran</option>
                  <option value="Jaminan Pelaksanaan (Standard)">Jaminan Pelaksanaan(Standard)</option>
                  <option value="Jaminan Pelaksanaan (Khusus)">Jaminan Pelaksanaan(Khusus)</option>
                  <option value="Jaminan Pemeliharaan">Jaminan Pemeliharaan</option>
                  <option value="Referensi Bank">Referensi Bank</option>
                  <option value="Surat Dukungan">Surat Dukungan Bank</option>
                </select>
              </td>
              <td style="margin-bottom: 50px;">
                <br>
                <select class="form-control" name="penerbit" id="penerbit">
                  <option>Select Penerbit</option>
                  <option value="Bank">Bank</option>
                  <option value="Asuransi">Asuransi</option>
                </select>
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="tgl_mulai" id="tgl_mulai">
                </div>
                <!-- <input class="form-control" type="text" name="tgl_mulai" id="tgl_mulai"> -->
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
                  <input type="text" class="form-control pull-right" name="tgl_selesai" id="tgl_selesai">
                </div>
                <!-- <input class="form-control" type="text" name="tgl_selesai" id="tgl_selesai"> -->
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <div class="modalIconn inputIconBg" style="padding-left: 10px">
                  <input type="text" class="form-control" name="jangka" id="jangka" >
                  <i class="" aria-hidden="true">Hari</i>
                </div>
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <select class="form-control" name="dokumen" id="dokumen" style="padding-left: 35px">
                  <option >Select Dokumen</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="PO/SPK">PO/SPK</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Berita Acara">Berita Acara</option>
                  <option value="Internet">Internet</option>
                  <option value="Dokumen">Dokumen/Pengadaan</option>
                  <option value="RKS/RFP/TOR">RKS/RFP/TOR</option>
                </select>
              </td>
            </tr>

            <tr class="tr-header">
              <th>No Dokumen</th>
              <th>Valuta</th>
              <th>Nominal</th>
            </tr>
            <tr>
              <td style="margin-bottom: 50px">
                <br><input type="text" class="form-control" placeholder="Enter No Dok" name="no_dok" id="no_dok" >
              </td>
              <td style="margin-bottom: 50px">
                <br>
                <select class="form-control" name="valuta" id="valuta">
                  <option>Select Valuta</option>
                  <option value="USD">USD</option>
                  <option value="IDR">IDR</option>
                </select>
              </td>
              <td style="margin-bottom: 50px">
                <br><div class="modalIcon inputIconBg" style="padding-left: 10px">
                <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal" id="nominal" >
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
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
@endsection

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