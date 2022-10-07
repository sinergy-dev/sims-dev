@extends('template.main')
@section('tittle')
  Draft Purchase Request
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">

<link rel="stylesheet" href="{{ url('css/jquery.emailinput.min.css') }}">
<link rel="stylesheet" href="{{ url('css/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{ url('css/dataTables.bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('/plugins/iCheck/all.css')}}">
<style type="text/css">
  input[type=file]::-webkit-file-upload-button {
   display: none;
  }

  .cbDraft:disabled,
  .cbDraft[disabled]{
    border-color: rgba(118, 118, 118, 0.3);
    background: #3c8dbc !important;
    color: #3c8dbc !important;
  }

  .icheckbox_minimal-blue:disabled{
    background: #3c8dbc !important;
  }
  .icheckbox_minimal-blue:disabled{
    background: #3c8dbc !important;
    border: 1px solid #d4d4d5;
  }
</style>
@endsection
@section('content')
  <section class="content-header">
    <h1>

     <a id="BtnBack"><button class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i>&nbspBack</button></a> Draft Purchase Request Detail
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">Purchase Request Detail</li>
    </ol>
  </section>
  <section class="content">
  	<div class="row" id="showDetail">
  	</div>
  </section>
  <div class="modal fade" id="ModalSirkulasiPr" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Sirkulasi PR</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="sirkulasi_pr" name="sirkulasi_pr">
              <div class="tab-sirkulasi">
                <div class="form-group">
                  <label>Tidak ada pembanding, apakah anda yakin? Berikan alasan</label>
                  <textarea style="resize: vertical;" class="form-control" id="reasonNoPembanding"></textarea>
                  <span class="help-block" style="display:none;">Please fill the reason!</span>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" id="nextBtnSirkulasi" class="btn btn-success">Sirkulasi</button>
                </div>
              </div>
              <div class="tab-sirkulasi" style="display:none">
                <label style="display:block;text-align: center;">Product</label>
                <div class="table-responsive">
                  <table class="table no-wrap">
                    <thead>
                      <th>No</th>
                      <th>Product</th>
                      <th>Description</th>
                      <th>Qty</th>
                      <th>Type</th>
                      <th>Price</th>
                      <th>Total Price</th>
                    </thead>
                    <tbody id="bodyPreviewSirkulasi">
                      
                    </tbody>
                  </table>
                  <div id="bottomPreviewSirkulasi">
                    
                  </div>
                </div>  
                <div class="modal-footer">
                  <a target="_blank" class="btn btn-sm bg-orange pull-left" id="showPdf">Show PDF</a>
                  <button type="button" data-toggle="modal" data-target="#ModalAddNote" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i>&nbspNotes</button>
                  <button type="button" id="btnReject" class="btn btn-danger">Reject</button>
                  <button type="button" id="btnAccept" class="btn btn-success">Accept</button>
                </div>            
              </div>           
            </form>
          </div>
        </div>
      </div>
  </div>
  <div class="modal fade" id="ModalRejectSirkulasi" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Sirkulasi PR - Reject</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="rejectsirkulasi" name="rejectsirkulasi">
                <div class="form-group">
                  <label>Reason</label>
                  <textarea class="form-control" style="resize: vertical;"  id="reasonRejectSirkular" name="reasonRejectSirkular">
                  </textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                  <button type="button" onclick="rejectSirkulasi()" class="btn btn-danger">Reject</button>
                </div>           
            </form>
          </div>
        </div>
      </div>
  </div>
  <div class="modal fade" id="ModalAcceptSirkulasi" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Sirkulasi PR - Accept</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="acceptsirkulasi" name="acceptsirkulasi">
                <div class="form-group">
                  <label>Pilih TTD Digital</label>
                  <br>
                  <input type="radio" name="selectTTD" id="selectTTD">
                  <img id="TTD" src="https://1.bp.blogspot.com/-cTTuRO8QNq0/UOYc_tD75bI/AAAAAAAAAx4/wg-S154-jUA/s1600/tanda-tangan.jpg" style="width:100px;height:100px">
                </div>
                <div class="modal-footer">
                  <button type="button" id="btnUploadNewTTD" class="btn btn-success">Upload New</button>
                  <button type="button" class="btn btn-primary" onclick="submitTTD('ready')">Submit</button>
                </div>          
            </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="ModalUploadNewTTD" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Sirkulasi PR - Accept</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="acceptsirkulasi" name="acceptsirkulasi">
                <div class="form-group">
                  <label>Anda belum memiliki TTD digital, Silahkan input</label>
                  <br>
                  <input type="file" name="inputTTD" id="inputTTD" class="form-control">
                </div>
                <div class="modal-footer">
                  <button type="button" onclick="submitTTD('notReady')" class="btn btn-success">Upload</button>
                </div>          
            </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="ModalAddNote" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Notes</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="" id="notes" name="notes">
                <div class="form-group">
                  <textarea class="form-control" id="inputNotes" style="resize:vertical;height: 200px;" ></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" onclick="btnSubmitNotes()" class="btn btn-success">Saved</button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                </div>          
            </form>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="ModalDraftPr" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Information Supplier</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="modal_pr" name="modal_pr">
          @csrf
          <div class="tab-add" style="display:none;">
            <div class="form-group">
              <label for="">To*</label>
              <input type="" class="form-control" placeholder="ex. eSmart Solution" id="inputTo" name="inputTo" onkeyup="fillInput('to')">
              <span class="help-block" style="display:none;">Please fill To!</span>
            </div>      

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Type*</label>
                  <select type="text" class="form-control" name="type" placeholder="ex. Internal Purchase Request" id="selectType" required >
                      <option selected value="">Select Type</option>
                      <option value="IPR">IPR (Internal Purchase Request)</option>
                      <option value="EPR">EPR (Eksternal Purchase Request)</option>
                  </select>
                  <span class="help-block" style="display:none;">Please fill Type!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Email*</label>
                  <input type="" class="form-control" placeholder="ex. absolut588@gmail.com" id="inputEmail" name="inputEmail" onkeyup="fillInput('email')">
                  <span class="help-block" style="display:none;">Please fill Email!</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Category</label>
              <select type="text" class="form-control select2" name="selectCategory" id="selectCategory" style="width: 100%">
                  <option value="">Select Category</option>
                  <option value="Barang dan Jasa">Barang dan Jasa</option>
                  <option value="Barang">Barang</option>
                  <option value="Jasa">Jasa</option>
                  <option value="Bank Garansi">Bank Garansi</option>
                  <option value="Service">Service</option>
                  <option value="Pajak Kendaraan">Pajak Kendaraan</option>
                  <option value="ATK">ATK</option>
                  <option value="Aset">Aset</option>
                  <option value="Tinta">Tinta</option>
                  <option value="Training">Training</option>
                  <option value="Ujian">Ujian</option>
                  <option value="Tiket">Tiket</option>
                  <option value="Akomodasi">Akomodasi</option>
                  <option value="Swab Test">Swab Test</option>
                  <option value="Iklan">Iklan</option>
                  <option value="Ekspedisi">Ekspedisi</option>
                  <option value="Legal">Legal</option>
                  <option value="Other">Other</option>
              </select>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Phone*</label>
                  <input class="form-control" id="inputPhone" type="" name="" placeholder="ex. 999-999-999-999" onkeyup="fillInput('phone')">
                  <span class="help-block" style="display:none;">Please fill Phone!</span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Attention*</label>
                  <input type="text" class="form-control" placeholder="ex. Marsono" name="inputAttention" id="inputAttention" onkeyup="fillInput('attention')">
                  <span class="help-block" style="display:none;">Please fill Attention!</span>
                </div> 
                <!-- <div class="form-group">
                  <label for="">Fax</label>
                  <input type="" id="inputFax" class="form-control" name="inputFax">
                </div> -->
              </div>
            </div>

            <div class="form-group">
              <label for="">Subject*</label>
              <input type="text" class="form-control" placeholder="ex. Pembelian laptop MSI Modern 14 (Sdri. Faiqoh, Sdr. Oktavian, Sdr. Subchana)" name="inputSubject" id="inputSubject" onkeyup="fillInput('subject')">
              <span class="help-block" style="display:none;">Please fill Subject!</span>
            </div>

            <div class="form-group">
              <label for="">Address*</label>
              <textarea class="form-control" id="inputAddress" name="inputAddress" placeholder="ex. Plaza Pinangsia Lt. 1 No. 7-8 Jl. Pinangsia Raya no.1" onkeyup="fillInput('address')" style="resize: vertical;"></textarea>
              <span class="help-block" style="display:none;">Please fill Address!</span>
            </div>

            <div class="form-group">
              <label for="">Request Methode*</label>
              <select type="text" class="form-control" placeholder="ex. Purchase Order" name="type" id="selectMethode" required >
                  <option selected value="">Select Methode</option>
                  <option value="purchase_order">Purchase Order</option>
                  <option value="payment">Payment</option>
                  <option value="reimbursement">Reimbursement</option>
              </select>
              <span class="help-block" style="display:none;">Please fill Type!</span>
            </div>

            <div class="form-group" id="divNotePembanding">
              <label for="">Note Pembanding*</label>
              <textarea class="form-control" id="note_pembanding" name="note_pembanding"></textarea>
              <span class="help-block" style="display:none;">Please fill Note Pembanding!</span>
            </div>
          </div>
          <div class="tab-add" style="display:none">
            <div class="form-group">
              <label>Product*</label>
              <input type="text" name="" class="form-control" id="inputNameProduct" placeholder="ex. Laptop MSI Modern 14" onkeyup="fillInput('name_product')">
              <span class="help-block" style="display:none;">Please fill Name Product!</span>
            </div>
            <div class="form-group">
              <label>Description*</label> 
              <textarea onkeyup="fillInput('desc_product')" id="inputDescProduct" placeholder='ex. Laptop mSI Modern 14, Processor AMD Rayzen 7 5700, Memory 16GB, SSD 512 Gb, Screen 14", VGA vega 8, Windows 11 Home' name="inputDescProduct" class="form-control" style="resize: vertical;height: 150px;"></textarea>
              <span class="help-block" style="display:none;">Please fill Description!</span>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6"> 
                  <label>Serial Number</label>
                  <input type="text" name="" class="form-control" id="inputSerialNumber">
                </div>
                <div class="col-md-6"> 
                  <label>Part Number</label>
                  <input type="text" name="" class="form-control" id="inputPartNumber">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-4"> 
                  <label>Qty*</label>
                  <input type="number" name="" class="form-control" id="inputQtyProduct" placeholder="ex. 5" onkeyup="fillInput('qty_product')">
                  <span class="help-block" style="display:none;">Please fill Qty!</span>
                </div>
                <div class="col-md-4"> 
                  <label>Type*</label>
                  <select class="form-control" id="selectTypeProduct" placeholder="ex. Unit" >
                    <option value="pcs">Pcs</option>
                    <option selected value="unit">Unit</option>                   
                  </select>
                  <span class="help-block" style="display:none;">Please fill Unit!</span>
                </div>
                <div class="col-md-4"> 
                  <label>Price*</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                    Rp.
                    </div>
                    <input type="text" name="" class="form-control money" id="inputPriceProduct" placeholder="ex. 15.000.000,00" onkeyup="fillInput('price_product')">
                  </div>
                  <span class="help-block" style="display:none;">Please fill Price!</span>
                </div>
              </div>
            </div>              
            <div class="form-group">
              <label>Total Price</label>
              <div class="input-group">
                <div class="input-group-addon">
                Rp.
                </div>
                  <input readonly type="text" name="" class="form-control" id="inputTotalPrice" placeholder="75.000.000,00">
              </div>
            </div>
          </div> 
          <div class="tab-add" style="display:none">
              <table class="table no-wrap">
                <thead>
                  <th>No</th>
                  <th>Product</th>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Type</th>
                  <th>Price</th>
                  <th>Total Price <a class="pull-right" onclick="refreshTable()"><i class="fa fa-refresh"></i>&nbsp</a></th>
                  <!-- <th></th> -->
                </thead>
                <tbody id="tbodyProducts">
                  
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12" id="bottomProducts">
                  
                </div>
              </div>
            <div class="form-group">
              <button class="btn btn-sm btn-primary" style="display:flex;margin: 0 auto;" type="button" id="addProduct"><i class="fa fa-plus"></i>&nbsp Add product</button>
            </div>
          </div>
          <div class="tab-add" style="display:none">
            <div id="formForPrExternal" style="display:none">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label>Lead Register*</label>
                    <select id="selectLeadId" style="width:100%" class="select2 form-control" >
                      
                    </select>
                    <span class="help-block" style="display:none;">Please fill Lead Register!</span>
                  </div>
                  <div class="col-md-6">
                    <label>PID*</label>
                    <select id="selectPid" style="width: 100%;" class="select2 form-control" >
                      
                    </select>
                    <span class="help-block" style="display:none;">Please fill PID!</span>
                    <span id="makeId" style="cursor: pointer;">other?</span>
                    <div class="form-group" id="project_idNew" style="display: none;">
                      <div class="input-group">
                        <input type="text" class="form-control pull-left col-md-8" placeholder="input Project ID" name="project_idInputNew" id="projectIdInputNew">
                        <span class="input-group-addon" style="cursor: pointer;" id="removeNewId"><i class="glyphicon glyphicon-remove"></i></span>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>                
              
              <div class="form-group">
                <label>SPK/Kontrak*</label>
                <div style="border: 1px solid #dee2e6 !important;color: #337ab7;height: 34px;padding: 6px 12px;background-color: #eee;">
                  <input type="file" name="inputSPK" id="inputSPK" class="fa fa-cloud-upload" disabled onkeyup="fillInput('spk')" style="margin-top: 4px;">
                </div>
                <span class="help-block" style="display:none;">Please fill SPK/Kontrak!</span>
                <span style="display:none;" id="span_link_drive_spk"><a id="link_spk" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>

              <div class="form-group">
                <label>SBE*</label>
                <div style="border: 1px solid #dee2e6 !important;color: #337ab7;height: 34px;padding: 6px 12px;background-color: #eee;">
                  <input type="file" name="inputSBE" id="inputSBE" class="fa fa-cloud-upload" disabled onkeyup="fillInput('sbe')" style="margin-top: 4px;">
                </div>
                <span class="help-block" style="display:none;">Please fill SBE!</span>
                <span style="display:none;" id="span_link_drive_sbe"><a id="link_sbe" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label>Quote Supplier*</label>
                    <div style="border: 1px solid #dee2e6 !important;color: #337ab7;height: 34px;padding: 6px 12px;">
                      <input type="file" name="inputQuoteSupplier" id="inputQuoteSupplier" class="fa fa-cloud-upload" onkeyup="fillInput('quoteSupplier')" style="margin-top: 4px;">
                    </div>
                    <span class="help-block" style="display:none;">Please fill Quote Supplier!</span>
                    <span style="display:none;" id="span_link_drive_quoteSup"><a id="link_quoteSup" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
                  </div>
                  <div class="col-md-6">
                    <label>Quote Number*</label>
                    <select name="selectQuoteNumber" class="select2 form-control" id="selectQuoteNumber" >
                      
                    </select>
                    <!-- <input type="file" name="inputQuoteNumber" id="inputQuoteNumber" class="form-control" onkeyup="fillInput('quoteNumber')"> -->
                    <span class="help-block" style="display:none;">Please fill Quote Number!</span>
                  </div>
                </div>
              </div>    
            </div>
              
            <div id="formForPrInternal" style="display:none;">
              <div class="form-group">
                <label>Lampiran Penawaran Harga*</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputPenawaranHarga" style="margin-bottom:0px">
                    <span class="fa fa-cloud-upload" style="display:inline"></span>
                    <input style="display: inline;" type="file" name="inputPenawaranHarga" id="inputPenawaranHarga">
                  </label>
                </div>
                <!-- <input type="file" name="inputPenawaranHarga" id="inputPenawaranHarga" class="form-control"> -->
                <span class="help-block" style="display:none;">Please fill Penawaran Harga!</span>
                <span style="display:none;" id="span_link_drive"><a id="link_penawaran_harga" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>
              <div id="docPendukungContainer">
                <label id="titleDoc" style="display:none;">Lampiran Dokumen Pendukung</label>
                <table id="tableDocPendukung" class="border-collapse:collapse" style="border-collapse: separate;border-spacing: 0 15px;">
                  
                </table>
              </div>
              <div class="form-group" style="display:flex;margin-top: 10px;">
                <a type="button" style="margin:0 auto" id="btnAddDocPendukung" class="btn btn-sm btn-primary" onclick="addDocPendukung(0)"><i class="fa fa-plus"></i>&nbsp Dokumen Pendukung</a>
              </div>
            </div>
          </div>   
          <div class="tab-add" style="display:none">
            <div class="box-body pad">
              <form> 
                <textarea onkeyup="fillInput('textArea_TOP')" class="textarea" id="textAreaTOP" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);" placeholder="ex. term of payment"></textarea>
                <span class="help-block" style="display:none;">Please fill Top of Payment!</span>
              </form>
            </div>
          </div>  
          <div class="tab-add" style="display:none">
            <div class="row">
              <div class="col-md-12" id="headerPreviewFinal">
                
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table" style="white-space: nowrap;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Product</th>
                      <th>Description</th>
                      <th>Qty</th>
                      <th>Type</th>
                      <th>Price</th>
                      <th>Total Price</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyFinalPageProducts">
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" id="bottomPreviewFinal">
                
              </div>
            </div>              
          </div>     
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="prevBtnAdd">Back</button>
              <button type="button" class="btn btn-primary" id="nextBtnAdd">Next</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.5/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>

<script src="{{ url('js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ url('js/jquery.slimscroll.min.js')}}"></script>
<script src="{{ url('js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ url('js/jquery.emailinput.min.js')}}"></script>
<script src="{{ url('js/roman.js')}}"></script>
<script src="{{asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript" src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
@endsection
@section('script')
<script type="text/javascript">
  $(".money").mask('000,000,000,000,000', {reverse: true})

  var formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });

  var accesable = @json($feature_item);

  isPembanding = localStorage.setItem('isPembanding',false)
  isLastStorePembanding = localStorage.getItem('isLastStorePembanding')

  $(document).ready(function(){
    Pace.restart()
    Pace.track(function() {
      if (isLastStorePembanding == 'true') {
        pembanding()
      }else{
        showDetail() 
        loadDataSubmitted()   
      } 
    })

    $('input[type="file"]').change(function(){
      
      var f=this.files[0]
      var filePath = f;
   
      // Allowing file type
      var allowedExtensions =
      /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

      var ErrorText = []
      if (f.size > 40000000 || f.fileSize > 40000000) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid file size, just allow file with size less than 40MB!',
        }).then((result) => {
          this.value = ''
        })
      }

      
      var ext = filePath.name.split(".");
      ext = ext[ext.length-1].toLowerCase();      
      var arrayExtensions = ["jpg" , "jpeg", "png", "pdf"];

      if (arrayExtensions.lastIndexOf(ext) == -1) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Invalid file type, just allow png/jpg/pdf',
        }).then((result) => {
          this.value = ''
        })
      }
    })
  })

  isPembanding = localStorage.getItem('isPembanding')
  $("#BtnBack").click(function(){
    if(localStorage.getItem('isPembanding') == 'true') {
      $("#showDetail").empty()
      localStorage.setItem('isPembanding',false)
      localStorage.setItem('isLastStorePembanding',false)
      showDetail()
      loadDataSubmitted()   
    }else if (localStorage.getItem('isEmail') == 'true') {
      $("#showDetail").empty()
      localStorage.setItem('isEmail',false)
      showDetail()
      loadDataSubmitted()  
    }else{
      $("#BtnBack").attr("href", "{{url('/admin/draftPR')}}")
    }
  })

  function reasonReject(item,display){
    var append = ""

    append = append + '<div class="callout callout-danger divReasonRejectRevision" style="display:none">'
      append = append + '<h4><i class="icon fa fa-cross"></i>Note Reject PR</h4>'
      append = append + '<p class="reason_reject_revision">'+ item.replaceAll("\n","<br>") +'</p>'
    append = append + '</div>'

    $(".tabGroup").prepend(append)

    if (display == "block") {
      $(".divReasonRejectRevision").show()
    }
  }

  function showDetail(){
    append = ""
    append = append + '<div class="col-md-9 tabGroup">'
      append = append + '<div class="row">'
        append = append + '<div class="col-md-12">'
          append = append + '<div class="box">'
            append = append + '<div class="box-body">'
              append = append + '<div class="row">'
                append = append + '<div class="col-md-12">'
                  append = append + '<span><b>Action</b></span><br>'
                  append = append + '<button class="btn btn-sm bg-purple" id="btnPembanding" onclick="pembanding()" style="margin-right:5px">Pembanding</button>'
                  append = append + '<button disabled class="btn btn-sm btn-warning" id="btnSirkulasi" style="margin-right:5px" onclick="sirkulasi(0)">Sirkulasi</button>'
                  append = append + '<button class="btn btn-sm btn-success" id="btnFinalize" disabled onclick="finalize()">Finalize</button>'
                  append = append + '<a id="btnShowPdf" target="_blank" href="{{url("/admin/getPdfPRFromLink")}}/?no_pr='+ window.location.href.split("/")[6] +'" class="btn btn-sm bg-orange pull-right">Show PDF</a>'
                  append = append + '<button id="btnAddNotes" class="btn btn-sm btn-primary pull-right" style="margin-right:5px"><i class="fa fa-plus"></i>&nbspNotes</button>'
                append = append + '</div>'
              append = append + '</div>'
              append = append + '<hr>'
              append = append + '<div class="row">'
                append = append + '<div class="col-md-12">'
                  append = append + '<div id="headerPreview">'
                  append = append + '</div>'
                  append = append + '<hr>'
                  append = append + '<div class="table-responsive">'
                    append = append + '<table class="table no-wrap">'
                      append = append + '<thead>'
                        append = append + '<th>No</th>'
                        append = append + '<th width="20%">Product</th>'
                        append = append + '<th width="35%">Description</th>'
                        append = append + '<th width="10%">Qty</th>'
                        append = append + '<th width="10%">Type</th>'
                        append = append + '<th width="15%">Price</th>'
                        append = append + '<th width="15%">Total Price</th>'
                      append = append + '</thead>'
                      append = append + '<tbody id="bodyPreview">'
                      append = append + '</tbody>'
                    append = append + '</table>'
                  append = append + '</div>'
                  append = append + '<div id="bottomPreview">'
                  append = append + '</div>'
                append = append + '</div>              '
              append = append + '</div>'
            append = append + '</div>'
          append = append + '</div>'
        append = append + '</div>'

        append = append + '<div class="col-md-12" id="showResolve">'
        append = append + '</div>'
      append = append + '</div>'
    append = append + '</div>'
    append = append + '<div class="col-md-3">'
    append = append + '<div id="scrollingDiv" style="overflow-y:scroll;height:1000px">'
      append = append + '<ul class="timeline">'
        $.ajax({
          type: "GET",
          url: "{{url('/admin/getActivity')}}",
          data: {
            no_pr: window.location.href.split("/")[6],
          },
          success: function(result) {
            
            //for user privilege

            if (result[2].isCircular == 'True') {   
              if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
                $("#btnSirkulasi").prop('disabled',true)
              }
            }        

            if (accesable.includes('btnSirkulasi','btnPembanding','btnShowPdf') || accesable.includes('btnShowPdf')) {
              $.each(accesable,function(value,item){
                
                if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
                  if(result[1][0].status == 'FINALIZED'){
                    $("#btnFinalize").prop('disabled',false)
                    $("#btnSirkulasi").prop('disabled',true)
                  }else{
                    $("#btnFinalize").prop('disabled',true)
                  }
                }
                // $('#'+item).attr('disabled',false)
              })
            }         
            
            $.each(result[0],function(value,item){
              loadActivity(value,item)
            })
          }
        })
      append = append + '</ul>'
    append = append + '</div>'
    append = append + '</div>'

    $("#showDetail").append(append)

    var no = 0
    var appendResolve = ''
    $.ajax({
      url:"{{url('/admin/getNotes')}}",
      type:"GET",
      data:{
        no_pr:window.location.href.split("/")[6]
      },
      success:function(result){
        $("#showResolve").empty("")
        console.log(result)
        $.each(result,function(item,value){
          no++

          if (value.image != '' && value.image != '-' && value.image != null) {
            image = value.image
          }else{
            image = "place_profile_3.png"
          }

          var span = ''
          var disable = ''
          if (value.resolve == 'True') {
            span = '<span class="pull-right badge bg-green">Resolved</span>'
            disable = 'disabled'
          }else{
            span = '<span class="pull-right badge bg-default">Progress</span>'
            disable
          }

          const cals = moment(value.date_add)
          .calendar(null, {
            lastDay: '[Yesterday]',
            sameDay: '[Today]',
            nextDay: '[Tomorrow]',
            lastWeek: '[last] dddd',
            nextWeek: 'dddd',
            sameElse: 'L'
          })

          appendResolve = appendResolve + '<div class="box box-widget">'
          appendResolve = appendResolve + '<div class="box-header with-border">'
          appendResolve = appendResolve + '<div class="user-block">'
          appendResolve = appendResolve + '<img class="img-circle" src="{{ asset("image/")}}/'+ image +'" alt="User Image">'
          appendResolve = appendResolve + '<span class="username"><a href="#">'+ value.operator +'</a></span>'
          appendResolve = appendResolve + '<span class="description">'+ span +'Note #'+ no +' - '+ value.no_pr +'</span>'
          appendResolve = appendResolve + '</div>'
          appendResolve = appendResolve + '<div class="box-tools">'
          appendResolve = appendResolve + '<button type="button" class="btn btn-box-tool" data-widget="collapse" onclick="btnMinusNotes('+item+')" data-value="'+ item +'"><i class="fa fa-minus"></i>'
          appendResolve = appendResolve + '</button>'
          appendResolve = appendResolve + '</div>'
          appendResolve = appendResolve + '</div>'
          appendResolve = appendResolve + '<div id="bodyCollapse" data-value="'+ item +'">'
          appendResolve = appendResolve + '<div class="box-body" style="">'
          appendResolve = appendResolve + '<p style="display:inline">'+ value.notes +'</p>'
          appendResolve = appendResolve + '<span style="display:inline" class="text-muted pull-right">'+ cals + '&nbspat&nbsp' + moment(value.date_add).format('hh:mm A') +'</span><br>'
          appendResolve = appendResolve + '<button type="button" value="'+ value.id +'" id="btnResolve" '+ disable +' class="pull-right btn btn-success btn-xs"><i class="fa fa-check"></i> Resolve</button>'
          appendResolve = appendResolve + '<button type="button" id="btnReply" onclick="btnShowReply('+ value.id_draft_pr +','+ value.id +')" data-id="'+ value.id_draft_pr +'" data-value="'+ value.id +'" '+ disable +' class="btn btn-default btn-xs"><i class="fa fa-reply"></i> Reply</button>'
          appendResolve = appendResolve + '</div>'
            if (value.reply.length > 0) {
              style = 'display:block'
            }else{
              style = 'display:none'
            }
            appendResolve = appendResolve + '<div class="box-footer box-comments" style="'+ style +'">'
            $.each(value.reply,function(items,values){
              if (values.gambar != '' && values.gambar != '-' && values.gambar != null) {
                gambar = values.gambar
              }else{
                gambar = 'place_profile_3.png'
              }
              appendResolve = appendResolve + '<div class="box-comment">'
              appendResolve = appendResolve + '<img class="img-circle img-sm" src="{{ asset("image")}}/'+ gambar +'" alt="User Image">'
              appendResolve = appendResolve + '<div class="comment-text">'
              const cal = moment(values.date_add)
              .calendar(null, {
                lastDay: '[Yesterday]',
                sameDay: '[Today]',
                nextDay: '[Tomorrow]',
                lastWeek: '[last] dddd',
                nextWeek: 'dddd',
                sameElse: 'L'
              })
              appendResolve = appendResolve + '<span class="username">'+ values.operator +'<span class="text-muted pull-right">'+ cal + '&nbspat&nbsp' + moment(values.date_add).format('hh:mm A') +'</span>'
              appendResolve = appendResolve + '</span>'+ values.reply +'</div>'
              appendResolve = appendResolve + '</div>'
            })            
            appendResolve = appendResolve + '</div>'

            //footer
            if (value.resolve != 'True') {
              appendResolve = appendResolve + '<div class="box-footer" id="showFooter" data-value="'+ value.id +'" style="display:none">'
              appendResolve = appendResolve + '</div>'
            }
            appendResolve = appendResolve + '</div>'
          appendResolve = appendResolve + '</div>'

        })
        $("#showResolve").append(appendResolve)

        // $("#btnReply").click(function(){
        //   var appendFooter = ''
        //   appendFooter = appendFooter + '<img class="img-responsive img-circle img-sm" src="https://media1.popsugar-assets.com/files/thumbor/o3SqeZaXAC1pwWwoxvYBRClWiYA/0x251:3265x3516/fit-in/2048xorig/filters:format_auto-!!-:strip_icc-!!-/2022/03/22/318/n/48559432/c3febcd2623ac05c889a58.03128934_/i/who-is-nam-joo-hyuk-facts.jpg" alt="Alt Text">'
        //   appendFooter = appendFooter + '<div class="img-push">'
        //   appendFooter = appendFooter + '<div class="input-group">'
        //     appendFooter = appendFooter + '<input type="text" id="inputReply" data-id="'+ $(this).data('id') +'" data-value="'+ $(this).data('value') +'" class="form-control input-sm" placeholder="Press enter to post comment">'
        //     appendFooter = appendFooter + '<div class="input-group-addon">'
        //       appendFooter = appendFooter + '<i onclick="pressReply('+ $(this).data('value') +','+ $(this).data('id') +')" class="fa fa-send"></i>'
        //     appendFooter = appendFooter + '</div>'
        //   appendFooter = appendFooter + '</div>'          
        //   appendFooter = appendFooter + '</div>'
        //   $("#showFooter").append(appendFooter)
        //   $("#showFooter").show()

        //   if ($("#inputReply").length == 1) {
        //     $("#btnReply").prop("disabled",true)
        //   }else{
        //     $("#btnReply").prop("disabled",false)
        //   }
        // })

        $("#btnResolve").click(function(){
          Swal.fire({
            title: 'Are you sure?',  
            text: "Resolve this",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
          }).then((result) => {
            if (result.value) {
                $.ajax({
                type: "POST",
                url: "{{url('/admin/storeResolveNotes')}}",
                data: {
                  _token: "{{ csrf_token() }}",
                  id:this.value,
                },beforeSend:function(){
                  Swal.fire({
                      title: 'Please Wait..!',
                      text: "It's sending..",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: false,
                      customClass: {
                          popup: 'border-radius-0',
                      },
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
                success: function(result) {
                  Swal.hideLoading()
                  Swal.fire(
                      'Successfully!',
                      'success',
                      'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                      Swal.close()
                    }
                  })
                  
                }
              })         
            }
          })
        })
      }
    })
     
    $("#showPdf").attr("href","{{url('/admin/getPdfPRFromLink')}}/?no_pr="+window.location.href.split("/")[6]+"")

    $("#btnAddNotes").click(function(){
      $(".modal-dialog").removeClass('modal-lg')
      $("#ModalAddNote").modal("show")
    })
  }  

  function btnShowReply(no_pr,id){
    if ("{{Auth::User()->gambar}}" != null && "{{Auth::User()->gambar}}" != '' && "{{Auth::User()->gambar}}" != '-') {
      gambar = "{{Auth::User()->gambar}}"
    }else{
      gambar = "place_profile_3.png"
    }
    var appendFooter = ''
    appendFooter = appendFooter + '<img class="img-responsive img-circle img-sm" src="{{ asset("image/")}}/'+ gambar +'" alt="Alt Text">'
    appendFooter = appendFooter + '<div class="img-push">'
    appendFooter = appendFooter + '<div class="input-group">'
      appendFooter = appendFooter + '<input type="text" id="inputReply" data-id="'+ no_pr +'" data-value="'+ id +'" class="form-control input-sm" placeholder="Type reply comment">'
      appendFooter = appendFooter + '<span class="input-group-btn">'
        appendFooter = appendFooter + '<button onclick="pressReply('+ no_pr +','+ id +')" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-send"></i></button>'
      appendFooter = appendFooter + '</span>'
    appendFooter = appendFooter + '</div>'          
    appendFooter = appendFooter + '</div>'
    $("#showFooter[data-value='"+ id +"']").append(appendFooter)
    $("#showFooter[data-value='"+ id +"']").show()

    if ($("#inputReply").length == 1) {
      $("#btnReply[data-value='"+ id +"']").prop("disabled",true)
    }else{
      $("#btnReply[data-value='"+ id +"']").prop("disabled",false)
    }
  }

  function pressReply(no_pr,id){
    $.ajax({
      type: "POST",
      url: "{{url('/admin/storeReply')}}",
      data: {
        _token: "{{ csrf_token() }}",
        id_notes:id,
        inputReply:$("#inputReply[data-value='"+ id +"']").val(),
        no_pr:no_pr,
      },beforeSend:function(){
        Swal.fire({
            title: 'Please Wait..!',
            text: "It's sending..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
                popup: 'border-radius-0',
            },
            didOpen: () => {
                Swal.showLoading()
            }
        })
      },
      success: function(result) {
        Swal.hideLoading()
        Swal.fire(
            'Successfully!',
            'success',
            'success'
        ).then((result) => {
          if (result.value) {
            location.reload()
            Swal.close()
          }
        })
        
      }
    })
  }

  function btnSubmitNotes(){
    $.ajax({
      type: "POST",
      url: "{{url('/admin/storeNotes')}}",
      data: {
        _token: "{{ csrf_token() }}",
        no_pr:window.location.href.split("/")[6],
        inputNotes:$("#inputNotes").val(),
      },beforeSend:function(){
        Swal.fire({
            title: 'Please Wait..!',
            text: "It's sending..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
                popup: 'border-radius-0',
            },
            didOpen: () => {
                Swal.showLoading()
            }
        })
      },
      success: function(result) {
        Swal.hideLoading()
        Swal.fire(
            'Successfully!',
            'success',
            'success'
        ).then((result) => {
          $("#ModalAddNote").modal("hide")
          Swal.close()
        })
        
      }
    })
  }

  function loadActivity(value,item){
    append = ""
      append = append + '<li class="time-label">'
        append = append + '<span class="bg-red">'
          append = append + value
        append = append + '</span>'
      append = append + '</li>'
      $.each(item,function(value,item){
      append = append + '<li>'
        if (item.status == 'SAVED') {
          append = append + '<i class="fa fa-lock label-primary"></i>'          
        }else if (item.status == 'DRAFT') {
          append = append + '<i class="fa fa-inbox label-primary"></i>' 
        }else if (item.status == 'REJECT') {
          append = append + '<i class="fa fa-times label-danger"></i>' 
        }else if (item.status == 'VERIFIED') {
          append = append + '<i class="fa fa-check label-success"></i>'
        }else if (item.status == 'COMPARING') {
          append = append + '<i class="fa fa-object-group bg-purple"></i>'
        }else if (item.status == 'CIRCULAR') {
          append = append + '<i class="fa fa-500px label-warning"></i>'
        }else if (item.status == 'DISAPPROVE') {
          append = append + '<i class="fa fa-times label-danger"></i>' 
        }else if (item.status == 'FINALIZED') {
          append = append + '<i class="fa fa-thumbs-o-up label-success"></i>' 
        }else if (item.status == 'SENDED') {
          append = append + '<i class="fa fa-envelope label-primary"></i>'
        }else if (item.status == 'UNAPPROVED') {
          append = append + '<i class="fa fa-times label-danger"></i>' 
        }else if (item.status == 'RESOLVE_NOTES') {
          append = append + '<i class="fa fa-check label-success"></i>' 
        }else if (item.status == 'REPLY_NOTES') {
          append = append + '<i class="fa fa-reply bg-default"></i>' 
        }else if (item.status == 'ADD_NOTES') {
          append = append + '<i class="fa fa-plus label-primary"></i>' 
        }
        append = append + '<div class="timeline-item">'
        append = append + '<span class="time"><i class="fa fa-clock-o"></i>&nbsp'+ moment(item.date_time).format('LT') +'</span>'
        append = append + '<label class="timeline-header">' + item.status +'</label>'
        append = append + '<div class="timeline-body">'
        append = append + '<ul>'
        append = append + '<li>'
        append = append + '<span>'+ item.activity +'</span> by '
        append = append + '<span>'+ item.operator +'</span><br>'
        append = append + '</li>'
        append = append + '</ul>'
        })
          append = append + '</div>'
        append = append + '</div>'
      append = append + '</li>'

    $(".timeline").append(append)
  }

  function pembanding(){    
    localStorage.setItem('isPembanding',true)
    $("#showDetail").empty()
    loadData()
    append = ""
    append = append + '<div class="col-md-6">'
      append = append + '<div class="box">'
        append = append + '<div class="box-header with-border">'
          append = append + '<label style="display:table;margin:0 auto">Draft</label>'
          append = append + '<div class="box-tools pull-right">'
            append = append + '<input value="'+ window.location.href.split("/")[6] +',draft" type="checkbox" class="cbDraft minimal" name="chk" id="cbPriority" style="display:none;"/><label>&nbspPriority <span class="bg-red statusComparisonSubmit"></span></label>'
              append = append + '</button>'
            append = append + '</div>'
          append = append + '</div>'
          append = append + '<div class="box-body">'
              append = append + '<div id="headerPreview">'
              append = append + '</div>'
              append = append + '<hr>'
              append = append + '<div id="table-produk" class="table-responsive">'
                append = append + '<table class="table no-wrap">'
                  append = append + '<thead>'
                    append = append + '<th>No</th>'
                    append = append + '<th width="20%">Product</th>'
                    append = append + '<th width="35%">Description</th>'
                    append = append + '<th width="10%">Qty</th>'
                    append = append + '<th width="15%">Type</th>'
                    append = append + '<th width="15%">Price</th>'
                    append = append + '<th width="15%">Total Price</th>'
                  append = append + '</thead>'
                  append = append + '<tbody id="bodyPreview">'
                  append = append + '</tbody>'
                append = append + '</table>'
              append = append + '</div>'
              append = append + '<div id="bottomPreview">'
              append = append + '</div>'
              append = append + '</div>'
          append = append + '</div>'
        append = append + '</div>'
    append = append + '<div class="col-md-6" >'
    append = append + '<div id="divPembanding"></div>'
    append = append + '<div style="display:table;margin:0 auto"><button style="display:none" id="btnAddPembanding" class="btn btn-sm bg-purple" onclick="addPembanding()"><i class="fa fa-plus"></i>&nbspPembanding</button</div>'        
    append = append + '</div>'
    $("#showDetail").append(append)

    $.ajax({
      type: "GET",
      url: "{{url('/admin/getPembanding')}}",
      data: {
        no_pr:window.location.href.split("/")[6],
      },
      success: function(result) {
        if (result[0].comparison.length == 0) {
          $(".cbDraft").iCheck('check')
        }else{
          var statusSelected = []
          $.each(result[0].comparison,function(value,item){
            if (item.status == 'Selected') {
              statusSelected.push(item.status)
            }
          })

          if (statusSelected.length == 0) {
            $(".cbDraft").iCheck('check')          
          }else{
            $(".cbDraft").iCheck('uncheck')          
          }
        }
        
        $.each(result[0].comparison,function(value,item){
          loadDataPembanding(value,item)
        })

        if (!accesable.includes('cbPriority')) {
          $(".cbDraft").prop('disabled',true)
          $(".cbPriority").prop('disabled',true)
          // $(".cbPriority").closest('div').addClass('disabled',true)
          // $(".cbDraft").closest('div').addClass('disabled',true)         
          // $(".cbDraft").closest('div').css("display","none")
          // $(".cbPriority").closest('div').css("display","none")
        }
      }
    })   

    accesable.forEach(function(item,index){
      console.log(item)
      $("#" + item).show()
    })
  }

  function loadData(){
    $.ajax({
      type: "GET",
      url: "{{url('/admin/getPreviewPr')}}",
      data: {
        no_pr:window.location.href.split("/")[6],
      },
      success: function(result) {
        type_of_letter = result.pr.type_of_letter
        if (type_of_letter == 'IPR') {
          PRType = '<b>Internal Purchase Request</b>'
        }else{
          PRType = '<b>External Purchase Request</b>'
        } 
        var appendHeader = ""
        if (type_of_letter == 'IPR') {
          appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><br>"
          appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Address: <span id='textAddress'>"+ result.pr.address +"</span></span<br>"
        }else{
          appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ result.pr.lead_id +"</span><br>"
          appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
          appendHeader = appendHeader + '<span>Address: <span id="textQuoteNumber" style="display:inline;" class="pull-right">'+ result.pr.quote_number +'</span></span><br>'
          appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br><br>'
        }

        $("#headerPreview").append(appendHeader)
        var append = ""
        var i = 0
        $.each(result.product,function(value,item){
          i++
          append = append + '<tr>'
            append = append + '<td>'
              append = append + '<span>'+ i +'</span>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input style="width:200px;font-size:12px;" class="form-control" type="" name="" value="'+ item.name_product +'" readonly>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<textarea style="width:250px;font-size:12px;resize:none;height:150px" class="form-control" readonly>' + item.description.replaceAll("<br>","\n") + '</textarea>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size:12px">'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<select readonly class="form-control" style="width:80px;font-size:12px">'
              if (item.unit == 'Pcs') {
                append = append + '<option selected>Pcs</option>'
                append = append + '<option>Unit</option>'
              }else{
                append = append + '<option>Pcs</option>'
                append = append + '<option selected>Unit</option>'
              }
              append = append + '</select>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size:12px;">'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control grandTotalPrice" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size:12px">'
            append = append + '</td>'
          append = append + '</tr>'
        })

        $("#bodyPreview").append(append)
        appendBottom = ""
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<div class="row">'
        appendBottom = appendBottom + '  <div class="col-md-12">'
        appendBottom = appendBottom + '    <form class="form-horizontal">'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Total</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control inputGrandTotalProductPreviewData" id="inputGrandTotalProductPreviewData" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail4" class="col-sm-offset-6 col-sm-2 control-label">Vat 11%</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control vat_tax_preview pull-right" id="vat_tax_previewData" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail5" class="col-sm-offset-6 col-sm-2 control-label">Grand Total</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control inputFinalPageTotalPriceData" id="inputFinalPageTotalPriceData" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '    </form>'
        appendBottom = appendBottom + '  </div>'
        appendBottom = appendBottom + '</div>'

        // appendBottom = appendBottom + '<div class="form-group">'
        // appendBottom = appendBottom + '<div class="row">'
        // appendBottom = appendBottom + '  <div class="col-md-12">'
        // appendBottom = appendBottom + '    <div class="pull-right">'
        // appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
        // appendBottom = appendBottom + '      <input readonly="" type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductPreviewData" id="inputGrandTotalProductPreviewData" name="inputGrandTotalProductPreviewData">'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + '  </div>'
        // appendBottom = appendBottom + '</div>'
        // appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
        // appendBottom = appendBottom + ' <div class="col-md-12">'
        // appendBottom = appendBottom + '   <div class="pull-right">'
        // appendBottom = appendBottom + '     <span style="margin-right: -5px;">Vat 11%</span>'
        // appendBottom = appendBottom + '       <div class="input-group margin" style="display: inline;">'
        // appendBottom = appendBottom + '         <input readonly="" type="text" class="form-control vat_tax_preview pull-right" id="vat_tax_previewData" name="vat_tax_previewData" style="width:250px;">'
        // appendBottom = appendBottom + '       </div>'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + ' </div>'
        // appendBottom = appendBottom + '</div>'
        // appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
        // appendBottom = appendBottom + '  <div class="col-md-12">'
        // appendBottom = appendBottom + '    <div class="pull-right">'
        // appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
        // appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control inputFinalPageTotalPriceData" id="inputFinalPageTotalPriceData" name="inputFinalPageTotalPriceData">'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + '  </div>'
        // appendBottom = appendBottom + '</div>'
        // appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<div class="box">'
          appendBottom = appendBottom + '<div class="box-header with-border">'
            appendBottom = appendBottom + '<h3 class="box-title">Terms of Payment</h3>'
            appendBottom = appendBottom + '<div class="box-tools pull-right">'
                appendBottom = appendBottom + '<button type="button" class="btn btn-box-tool btnTerm" data-value="draft"><span class="fa fa-2x fa-angle-right" style="margin-top:-5px"></span>'
                appendBottom = appendBottom + '</button>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<div class="box-body collapse" id="bodyCollapse" data-value="draft">'
             appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="padding:10px;width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid rgb(221, 221);overflow:auto"></div>'
          appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<span><b>Attached Files</b></span>'
        var pdf = "fa fa-fw fa-file-pdf-o"
        var image = "fa fa-fw fa-file-image-o"
        if (result.pr.type_of_letter == 'IPR') {
          if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
            var fa_doc = pdf
          }else{
            var fa_doc = image
          }
          appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
            appendBottom = appendBottom + '<div class="row">'
              appendBottom = appendBottom + '<div class="col-md-6">'
                appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+result.dokumen[0].link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1] +'</a>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="col-md-6">'
                appendBottom = appendBottom + '<div style="padding: 5px;">Penawaan Harga</div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '</div>'
          
          $.each(result.dokumen,function(value,item){
            if (item.dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            if (value != 0) {
              appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                appendBottom = appendBottom + '<div class="row">'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen Pendukung : &nbsp'+ item.dokumen_name +'</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
            }        
          })
        }else{        
          $.each(result.dokumen,function(value,item){
            if (item.dokumen_location.split(".")[1] == 'pdf') {
                var fa_doc = pdf
              }else{
                var fa_doc = image
              }
              appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                appendBottom = appendBottom + '<div class="row">'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen &nbsp'+ item.dokumen_name +'</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'      
          })
        } 

        $("#bottomPreview").append(appendBottom)    

        $("#termPreview").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))
        var tempVat = 0
        var finalVat = 0
        var tempGrand = 0
        var finalGrand = 0
        var tempTotal = 0
        var sum = 0

        $('.grandTotalPrice').each(function() {
            var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
            sum += temp;
        });

        if (result.pr.status_tax == 'True') {
          tempVat = formatter.format((parseFloat(sum) * 11) / 100)
          tempGrand = parseInt(sum) +  parseInt((parseInt(sum) * 11) / 100)
        }else{
          tempVat = tempVat
          tempGrand = parseInt(sum)
        }

        finalVat = tempVat

        finalGrand = tempGrand

        tempTotal = sum
        $("#vat_tax_previewData").val(tempVat)
        $("#inputGrandTotalProductPreviewData").val(formatter.format(sum))
        $("#inputFinalPageTotalPriceData").val(formatter.format(tempGrand))
      }
    })
  } 

  $(document).on('click','.btnTerm[data-value="draft"]', function() {
    if ($("#bodyCollapse[data-value='draft']").is(':visible') == true) {
      $("#bodyCollapse[data-value='draft']").hide("slow")
      $(this).find('span').removeClass("fa-angle-down").addClass("fa-angle-right")

    }
    if ($("#bodyCollapse[data-value='draft']").is(':hidden') == true){
      $("#bodyCollapse[data-value='draft']").show('slow')
      $(this).find('span').removeClass("fa-angle-right").addClass("fa-angle-down")
    }
    
  })

  function loadDataSubmitted(){
    $.ajax({
      type: "GET",
      url: "{{url('/admin/getDetailPr')}}",
      data: {
        no_pr:window.location.href.split("/")[6],
      },
      success: function(result) {
        type_of_letter = result.pr.type_of_letter
        if (type_of_letter == 'IPR') {
          PRType = '<b>Internal Purchase Request</b>'
        }else{
          PRType = '<b>External Purchase Request</b>'
        } 

        if (result.getSign == '{{Auth::User()->name}}') {
          
          $("#btnSirkulasi").prop('disabled',false)
        }

        if (result.pr.status == "UNAPPROVED" || result.pr.status == "CIRCULAR") {
          if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
            $("#btnSirkulasi").prop('disabled',true)
          }
        }else{
          if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
            if (result.pr.status == "FINALIZED" || result.pr.status == "SENDED") {
              $("#btnSirkulasi").prop('disabled',true)
            }else{
              $("#btnSirkulasi").prop('disabled',false)
            }
          }
        }

        if (result.pr.status == "REJECT" || result.pr.status == "UNAPPROVED" || result.pr.activity == 'Updating' && result.pr.status == 'COMPARING'){
          reasonReject(result.activity.reason,"block")
        }

        var appendHeader = ""
        if (type_of_letter == 'IPR') {
          appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'>"+ result.pr.no_pr +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><br>"
          appendHeader = appendHeader + "<span style='display:inline'>Address: <span id='textAddress'>"+ result.pr.address +"</span></span<br>"
        }else{
          appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'>"+ result.pr.no_pr +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
          appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
          appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ result.pr.lead_id +"</span><br>"
          appendHeader = appendHeader + "<span style='display:inline'>Address: </span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
          appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span><span style="display:inline;" class="pull-right">'+ result.pr.quote_number +'</span></span><br><br>'
        }

        $("#headerPreview").append(appendHeader)
        var append = ""
        var i = 0
        $.each(result.product,function(value,item){
          i++
          append = append + '<tr>'
            append = append + '<td>'
              append = append + '<span>'+ i +'</span>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input style="width:200px;font-size:12px" class="form-control" type="" name="" value="'+ item.name_product +'" readonly>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<textarea style="width:250px;font-size:12px;height:150px;resize:none" class="form-control" readonly>' + item.description.replaceAll("<br>","\n") + '</textarea>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size:12px">'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<select readonly class="form-control" style="width:80px;font-size:12px">'
              if (item.unit == 'Pcs') {
                append = append + '<option selected>Pcs</option>'
                append = append + '<option>Unit</option>'
              }else{
                append = append + '<option>Pcs</option>'
                append = append + '<option selected>Unit</option>'
              }
              append = append + '</select>'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size:12px">'
            append = append + '</td>'
            append = append + '<td>'
              append = append + '<input readonly class="form-control grandTotalPrice" id="grandTotalPrice" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size:12px">'
            append = append + '</td>'
          append = append + '</tr>'
        })

        $("#bodyPreview").append(append)
        appendBottom = ""
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<div class="row">'
        appendBottom = appendBottom + '  <div class="col-md-12">'
        appendBottom = appendBottom + '    <form class="form-horizontal">'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Total</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control inputGrandTotalProductPreview" id="inputGrandTotalProductPreview" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail4" class="col-sm-offset-6 col-sm-2 control-label">Vat 11%</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_preview" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '      <div class="form-group">'
        appendBottom = appendBottom + '        <label for="inputEmail5" class="col-sm-offset-6 col-sm-2 control-label">Grand Total</label>'
        appendBottom = appendBottom + '        <div class="col-sm-4">'
        appendBottom = appendBottom + '          <input readonly="" type="text" class="form-control inputFinalPageTotalPrice" id="inputFinalPageTotalPrice" data-value="'+i+'">'
        appendBottom = appendBottom + '        </div>'
        appendBottom = appendBottom + '      </div>'
        appendBottom = appendBottom + '    </form>'
        appendBottom = appendBottom + '  </div>'
        appendBottom = appendBottom + '</div>'
        
        // appendBottom = appendBottom + '<div class="form-group">'
        // appendBottom = appendBottom + '<div class="row">'
        // appendBottom = appendBottom + '  <div class="col-md-12">'
        // appendBottom = appendBottom + '    <div class="pull-right">'
        // appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
        // appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;" class="form-control inputGrandTotalProductPreview" id="inputGrandTotalProductPreview" name="inputGrandTotalProductPreview">'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + '  </div>'
        // appendBottom = appendBottom + '</div>'
        // appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
        // appendBottom = appendBottom + ' <div class="col-md-12">'
        // appendBottom = appendBottom + '   <div class="pull-right">'
        //   appendBottom = appendBottom + '   <span style="margin-right: -5px;">Vat 11%</span>'
        //   appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
        //   appendBottom = appendBottom + '       <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_preview" name="vat_tax_preview" style="width:150px;">'
        //   appendBottom = appendBottom + '     </div>'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + ' </div>'
        // appendBottom = appendBottom + '</div>'
        // appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
        // appendBottom = appendBottom + '  <div class="col-md-12">'
        // appendBottom = appendBottom + '    <div class="pull-right">'
        // appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
        // appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;" class="form-control inputFinalPageTotalPrice" id="inputFinalPageTotalPrice" name="inputFinalPageTotalPrice">'
        // appendBottom = appendBottom + '    </div>'
        // appendBottom = appendBottom + '  </div>'
        // appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<div class="box">'
          appendBottom = appendBottom + '<div class="box-header with-border">'
            appendBottom = appendBottom + '<h3 class="box-title">Terms of Payment</h3>'
            appendBottom = appendBottom + '<div class="box-tools pull-right">'
                appendBottom = appendBottom + '<button type="button" class="btn btn-box-tool btnTerm" data-value="submitted"><span class="fa fa-2x fa-angle-right" style="margin-top:-5px"></span>'
                appendBottom = appendBottom + '</button>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<div class="box-body collapse" id="bodyCollapse" data-value="submitted">'
             appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid rgb(221, 221);overflow:auto"></div>'
          appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '</div>'
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<span><b>Attached Files</b></span>'
        var pdf = "fa fa-fw fa-file-pdf-o"
        var image = "fa fa-fw fa-file-image-o"
        
        if (result.pr.type_of_letter == 'IPR') {
          if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
            var fa_doc = pdf
          }else{
            var fa_doc = image
          }
          appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
            appendBottom = appendBottom + '<div class="row">'
              appendBottom = appendBottom + '<div class="col-md-6">'
                appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+result.dokumen[0].link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1] +'</a>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="col-md-6">'
                appendBottom = appendBottom + '<div style="padding: 5px;">Penawaan Harga</div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '</div>'
          
          $.each(result.dokumen,function(value,item){
            if (item.dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            if (value != 0) {
              appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                appendBottom = appendBottom + '<div class="row">'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen Pendukung : &nbsp'+ item.dokumen_name +'</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
            }        
          })
        }else{        
          $.each(result.dokumen,function(value,item){
            if (item.dokumen_location.split(".")[1] == 'pdf') {
                var fa_doc = pdf
              }else{
                var fa_doc = image
              }
              appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                appendBottom = appendBottom + '<div class="row">'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '<div class="col-md-6">'
                    appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen &nbsp'+ item.dokumen_name +'</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'      
          })
        }
        
        if (result.show_ttd.length > 0) {
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<div>'
          appendBottom = appendBottom + '<span><b>Approver Sign</b></span>'
          appendBottom = appendBottom + '</div>'
          $.each(result.show_ttd,function(value,item){
            appendBottom = appendBottom + '<div class="form-group" style="display:inline;float:left;text-align:center;margin:20px">'
            appendBottom = appendBottom + '<div><img src="{{asset("/")}}'+ item.ttd +'" style="width:100px;height:100px" /></div>'
            appendBottom = appendBottom + '<div><span style="text-align:center" readonly>'+ item.name +'</span></div>'
            appendBottom = appendBottom + '</div>'
          })
        }       

        $("#bottomPreview").append(appendBottom)    

        if (result.pr.term_payment != null) {
          $("#termPreview").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))
        }
        var tempVat = 0
        var finalVat = 0
        var tempGrand = 0
        var finalGrand = 0
        var tempTotal = 0
        var sum = 0

        $('.grandTotalPrice').each(function() {
            var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
            sum += temp;
        });

        if (result.pr.status_tax == 'True') {
          tempVat = formatter.format((parseFloat(sum) * 11) / 100)
          tempGrand = parseInt(sum) +  parseInt((parseInt(sum) * 11) / 100)
        }else{
          tempVat = tempVat
          tempGrand = parseInt(sum)
        }

        finalVat = tempVat

        finalGrand = tempGrand

        tempTotal = sum
        $("#vat_tax_preview").val(tempVat)
        $("#inputGrandTotalProductPreview").val(formatter.format(sum))
        $("#inputFinalPageTotalPrice").val(formatter.format(tempGrand))
      }
    })
  }  

  $(document).on('click','.btnTerm[data-value="submitted"]', function() {
    if ($("#bodyCollapse[data-value='submitted']").is(':visible') == true) {
      $("#bodyCollapse[data-value='submitted']").hide("slow")
      $(this).find('span').removeClass("fa-angle-down").addClass("fa-angle-right")

    }
    if ($("#bodyCollapse[data-value='submitted']").is(':hidden') == true){
      $("#bodyCollapse[data-value='submitted']").show('slow')
      $(this).find('span').removeClass("fa-angle-right").addClass("fa-angle-down")
    }
  })

  $("#TTD").click(function(){
      $("#selectTTD").prop("checked",true)
  })

  function loadDataPembanding(i,item){
    type_of_letter = item.type_of_letter
    if (type_of_letter == 'IPR') {
      PRType = '<b>Internal Purchase Request</b>'
    }else{
      PRType = '<b>External Purchase Request</b>'
    } 
    no = ++i
    append = ""

    append = append + '<div class="box">'
    append = append + '<div class="box-header with-border">'
    append = append + '<label style="display:table;margin:0 auto">Comparisson #'+ no +'</label>'
    append = append + '<div class="box-tools pull-right">'
      append = append + '<input value="'+ item.id +',pembanding,'+ item.id_draft_pr +'" type="checkbox" data-value="'+ no +'" name="chk" id="cbPriority" style="display:none" class="cbPriority minimal"/><label>&nbspPriority <span class="bg-blue statusComparison" data-value="'+ no +'"></span></label>'
        append = append + '<button type="button" class="btn btn-box-tool btnMinus" onclick="btnMinus('+no+')" data-value="pembanding_'+ no +'"><span class="fa fa-2x fa-angle-right" style="margin-top:-5px"></span>'
        append = append + '</button>'
      append = append + '</div>'
    append = append + '</div>'
    append = append + '<div class="box-body">'
    append = append + '<div id="headerPreviewPembanding" data-value="'+i+'">'
    if (type_of_letter == 'IPR') {
      append = append + "<span style='display:inline;'>To: <span id='textTo'>"+ item.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
      append = append + "<span style='display:inline'>Email: <span id='textEmail'>"+ item.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
      append = append + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ item.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ item.request_method +"</span></br>"
      append = append + "<span style='display:inline;'>Fax: <span id='textFax'>"+ item.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(item.created_at).format('DD MMMM') +"</span></br>"
      append = append + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ item.attention +"</span></span></br>"
      append = append + "<span style='display:inline;'>From: <span id='textFrom'>"+ item.issuance +"</span></span><br>"
      append = append + "<span style='display:inline'>Subject: <span id='textSubject'>"+ item.title +"</span></span></br>"
      append = append + "<span style='display:inline'>Address: <span id='textAddress'>"+ item.address +"</span></span<br>"
    }else{
      append = append + "<span style='display:inline;'>To: <span id='textTo'>"+ item.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
      append = append + "<span style='display:inline'>Email: <span id='textEmail'>"+ item.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
      append = append + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ item.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ item.request_method +"</span></br>"
      append = append + "<span style='display:inline;'>Fax: <span id='textFax'>"+ item.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(item.created_at).format('DD MMMM') +"</span></br>"
      append = append + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ item.attention +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
      append = append + "<span style='display:inline;'>From: <span id='textFrom'>"+ item.issuance +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ item.lead_id +"</span><br>"
      append = append + "<span style='display:inline'>Subject: <span id='textSubject'>"+ item.title +"</span></span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
      append = append + '<span>Address: <span id="textQuoteNumber" style="display:inline;" class="pull-right">'+ item.quote_number +'</span></span><br>'
      append = append + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ item.address +'</span></span><br><br>'
    }
    append = append + '</div>'
    append = append + '<div id="bodyPreviewPembanding" data-value="'+i+'" style="display:none;overflow:auto" class="table-responsive">'
    append = append + '<hr>'
      append = append + '<table class="table no-wrap">'
        append = append + '<thead>'
          append = append + '<th>No</th>'
          append = append + '<th width="20%">Product</th>'
          append = append + '<th width="35%">Description</th>'
          append = append + '<th width="10%">Qty</th>'
          append = append + '<th width="15%">Type</th>'
          append = append + '<th width="15%">Price</th>'
          append = append + '<th width="15%">Total Price</th>'
        append = append + '</thead>'
        append = append + '<tbody>'
        var noProduct = 0
          $.each(item.product,function(value,item){
            noProduct++
            append = append + '<tr>'
              append = append + '<td>'
                append = append + '<span>'+ noProduct +'</span>'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<input style="width:200px;font-size:12px" class="form-control" type="" name="" value="'+ item.name_product +'" readonly>'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<textarea style="width:250px;font-size:12px;height:150px;resize:none" class="form-control" readonly>' + item.description.replaceAll("<br>","\n") + '</textarea>'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;;font-size:12px">'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<select readonly class="form-control" style="width:80px;font-size:12px">'
                if (item.unit == 'Pcs') {
                  append = append + '<option selected>Pcs</option>'
                  append = append + '<option>Unit</option>'
                }else{
                  append = append + '<option>Pcs</option>'
                  append = append + '<option selected>Unit</option>'
                }
                append = append + '</select>'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size:12px">'
              append = append + '</td>'
              append = append + '<td>'
                append = append + '<input readonly class="form-control" data-value="'+i+'" id="grandTotalPricePembanding" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size:12px">'
              append = append + '</td>'
            append = append + '</tr>'
          })
        append = append + '</tbody>'
      append = append + '</table>'
    append = append + '</div>'
    append = append + '<div id="bottomPreviewPembanding" data-value="'+i+'" style="display:none">'
    append = append + '<hr>'
    // append = append + '<div class="form-group">'
    append = append + '  <div class="row">'
    append = append + '    <div class="col-md-12">'
    append = append + '      <div class="col-md-6">'
    append = append + '        <div class="form-group">'
    append = append + '          <label>Note Pembanding</label>'
    append = append + '           <textarea readonly class="form-control" style="resize:none;overflow-y:scroll" id="note_pembandingView" rows="4" data-value="'+i+'"></textarea>'
          // append = append + '  <textarea readonly class="form-control" style="width:100%;display:inline;resize:none;overflow-y:scroll" id="note_pembandingView" data-value="'+i+'"></textarea>'
    append = append + '        </div>'
    append = append + '      </div>'
    append = append + '      <div class="col-md-6">'
    append = append + '        <form class="form-horizontal">'
    append = append + '          <div class="form-group">'
    append = append + '            <label for="inputEmail3" class="col-sm-4 control-label">Total</label>'
    append = append + '            <div class="col-sm-8">'
    append = append + '              <input readonly="" type="text" class="form-control inputGrandTotalProductPembanding" id="inputGrandTotalProductPembanding" data-value="'+i+'">'
    append = append + '            </div>'
    append = append + '          </div>'
    append = append + '          <div class="form-group">'
    append = append + '            <label for="inputEmail4" class="col-sm-4 control-label">Vat 11%</label>'
    append = append + '            <div class="col-sm-8">'
    append = append + '              <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_pembanding" data-value="'+i+'">'
    append = append + '            </div>'
    append = append + '          </div>'
    append = append + '          <div class="form-group">'
    append = append + '            <label for="inputEmail5" class="col-sm-4 control-label">Grand Total</label>'
    append = append + '            <div class="col-sm-8">'
    append = append + '              <input readonly="" type="text" class="form-control inputFinalPageTotalPricePembanding" id="inputFinalPageTotalPricePembanding" data-value="'+i+'">'
    append = append + '            </div>'
    append = append + '          </div>'
          // append = append + '    <div class="pull-right">'
          // append = append + '      <span style="display: inline;margin-right: 15px;">Total</span>'
          // append = append + '      <input readonly="" type="text" style="width:150px;display: inline;" class="form-control inputGrandTotalProductPembanding" id="inputGrandTotalProductPembanding" name="inputGrandTotalProductPembanding" data-value="'+i+'">'
          // append = append + '    </div>'
          // append = append + '    <div class="pull-right" style="margin-top:10px">'
          // append = append + '      <span style="margin-right: -5px;">Vat 11%</span>'
          // append = append + '      <input data-value="'+i+'" readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_pembanding" name="vat_tax_preview" style="width:150px;">'
          // append = append + '    </div>'
          // append = append + '    <div class="pull-right" style="margin-top:10px">'
          // append = append + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
          // append = append + '      <input readonly type="text" style="width:150px;display: inline;" class="form-control inputFinalPageTotalPricePembanding" id="inputFinalPageTotalPricePembanding" name="inputFinalPageTotalPricePembanding" data-value="'+i+'">'
          // append = append + '    </div>'
    append = append + '        </form>'
    append = append + '      </div>'
    append = append + '    </div>'
    append = append + '  </div>'
    // append = append + '</div>'
    append = append + '<hr>'
    append = append + '<div class="box">'
      append = append + '<div class="box-header with-border">'
        append = append + '<h3 class="box-title">Terms of Payment</h3>'
        append = append + '<div class="box-tools pull-right">'
            append = append + '<button type="button" class="btn btn-box-tool btnTerm" onclick="btnTerm('+ i +')" data-value="'+i+'"><span class="fa fa-2x fa-angle-right" style="margin-top:-5px"></span>'
            append = append + '</button>'
          append = append + '</div>'
        append = append + '</div>'
        append = append + '<div class="box-body collapse" id="bodyCollapse" data-value="'+i+'">'
         append = append + '<div class="form-control" id="termPreviewPembanding" data-value="'+i+'" style="width: 100%; height: 100%; font-size: 12px; line-height: 18px; border: 1px solid rgb(221, 221)"></div>'
      append = append + '</div>'
    append = append + '</div>'
    append = append + '<hr>'
    append = append + '<span><b>Attached Files</b></span>'
    var pdf = "fa fa-fw fa-file-pdf-o"
    var image = "fa fa-fw fa-file-image-o"
    if (item.document[0].dokumen_location.split(".")[1] == 'pdf') {
      var fa_doc = pdf
    }else{
      var fa_doc = image
    }
    if (item.document.length > 0) {
      if (type_of_letter == 'IPR') {
        append = append + '<div class="form-group" style="font-size: reguler;">'
          append = append + '<div class="row">'
            append = append + '<div class="col-md-6">'
              append = append + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.document[0].link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.document[0].dokumen_location.substring(0,15) + '....'+ item.document[0].dokumen_location.split(".")[0].substring(item.document[0].dokumen_location.length -10) + "." + item.document[0].dokumen_location.split(".")[1] +'</a>'
              append = append + '</div>'
            append = append + '</div>'
            append = append + '<div class="col-md-6">'
              append = append + '<div style="padding: 5px;">Penawaan Harga</div>'
            append = append + '</div>'
          append = append + '</div>'
        append = append + '</div>'
        
        $.each(item.document,function(value,item){
          if (item.dokumen_location.split(".")[1] == 'pdf') {
            var fa_doc = pdf
          }else{
            var fa_doc = image
          }

          
          if (value != 0) {
            
            append = append + '<div class="form-group" style="font-size: reguler;">'
              append = append + '<div class="row">'
                append = append + '<div class="col-md-6">'
                  append = append + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                  append = append + '</div>'
                append = append + '</div>'
                append = append + '<div class="col-md-6">'
                  append = append + '<div style="padding: 5px;">Dokumen Pendukung : &nbsp'+ item.dokumen_name +'</div>'
                append = append + '</div>'
              append = append + '</div>'
            append = append + '</div>'
          }        
        })
      }else{        
        $.each(item.document,function(value,item){
          if (item.dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            append = append + '<div class="form-group" style="font-size: reguler;">'
              append = append + '<div class="row">'
                append = append + '<div class="col-md-6">'
                  append = append + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1]+'</a>'
                  append = append + '</div>'
                append = append + '</div>'
                append = append + '<div class="col-md-6">'
                  append = append + '<div style="padding: 5px;">Dokumen &nbsp'+ item.dokumen_name +'</div>'
                append = append + '</div>'
              append = append + '</div>'
            append = append + '</div>'      
        })
      }
    }
    
    append = append + '</div>'
    append = append + '</div>'
    append = append + '</div>'
    append = append + '</div>'
    $("#divPembanding").append(append)  

    $.ajax({
      type: "GET",
      url: "{{url('/admin/getActivity')}}",
      data: {
        no_pr: window.location.href.split("/")[6],
      },
      success: function(result) {
        if (result[2].isCircular == 'True') {
          console.log("true circular")
          //btn pembanding di procurement disabled
          $("#btnAddPembanding").prop('disabled',true)
          $("#cbPriority[data-value='" + i + "']").prop('disabled',true)
          $(".cbDraft").prop('disabled',true)
        }     
      }
    })

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
    })

    if (item.status == 'Selected') {
      console.log(i)
      $(".statusComparison[data-value='" + i + "']").text(item.status)
      $(".statusComparison[data-value='" + i + "']").show()
      $(".statusComparisonSubmit").text("Unselect")
      $(".statusComparisonSubmit").show()
      $("#cbPriority[data-value='" + i + "']").iCheck('check')
      // $("#cbPriority[data-value='" + i + "']").prop('disabled',true)
      $("#cbPriority[data-value='" + i + "']").click(function() { return false; });
    }else{
      $(".statusComparison[data-value='" + i + "']").hide()
      $(".statusComparisonSubmit").hide()
    }

    $("input#cbPriority").on('ifChanged', function() {
      if (this.checked) {
        localStorage.setItem(isLastStorePembanding,false)
        Swal.fire({
          title: 'Are you sure?',  
          text: "Selecting '"+ $(this).closest('div').parents('div.box-tools').prev('label').text()  +"'" ,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        }).then((result) => {
          if (result.value) {
            $(".cbPriority").prop("checked",false)
              $.ajax({
              type: "POST",
              url: "{{url('/admin/choosedComparison')}}",
              data: {
                _token: "{{ csrf_token() }}",
                no_pr:this.value.split(",")[0],
                status:this.value.split(",")[1],
                id_draft_pr:this.value.split(",")[2],
              },beforeSend:function(){
                Swal.fire({
                    title: 'Please Wait..!',
                    text: "It's sending..",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    customClass: {
                        popup: 'border-radius-0',
                    },
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
              },
              success: function(result) {
                Swal.hideLoading()
                Swal.fire(
                    'Successfully!',
                    'success',
                    'success'
                ).then((result) => {
                  if (result.value) {
                    location.reload()
                    Swal.close()
                  }
                })
                
              }
            })         
          }else{
            $(this).iCheck("uncheck")
          }
        })
      }
    });

    $("#termPreviewPembanding[data-value='" + i + "']").html(item.term_payment)

    $("#note_pembandingView[data-value='" + i + "']").html(item.note_pembanding) 

    var tempVat = 0
    var finalVat = 0
    var tempGrand = 0
    var finalGrand = 0
    var tempTotal = 0
    var sum = 0

    $("#grandTotalPricePembanding[data-value='" + i + "']").each(function() {
        var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
        sum += temp;
    });

    if (item.status_tax == 'True') {
      tempVat = formatter.format((parseFloat(sum) * 11) / 100)
      tempGrand = parseInt(sum) +  parseInt((parseInt(sum) * 11) / 100)
    }else{
      tempVat = tempVat
      tempGrand = parseInt(sum)
    }

    finalVat = tempVat

    finalGrand = tempGrand

    tempTotal = sum
    $("#vat_tax_pembanding[data-value='" + i + "']").val(tempVat)
    $("#inputGrandTotalProductPembanding[data-value='" + i + "']").val(formatter.format(sum))
    $("#inputFinalPageTotalPricePembanding[data-value='" + i + "']").val(formatter.format(tempGrand))

    accesable.forEach(function(item,index){
      
      $("." + item).show()
    })
  }

  function btnTerm(i){
    if ($("#bodyCollapse[data-value='" + i + "']").is(':visible') == true) {
      $("#bodyCollapse[data-value='" + i + "']").hide("slow")
      $(".btnTerm[data-value='" + i + "']").find('span').removeClass("fa-angle-down").addClass("fa-angle-right")

    }
    if ($("#bodyCollapse[data-value='" + i + "']").is(':hidden') == true){
      $("#bodyCollapse[data-value='" + i + "']").show("slow")
      $(".btnTerm[data-value='" + i + "']").find('span').removeClass("fa-angle-right").addClass("fa-angle-down")

    }
  }

  function btnMinus(i){
    if ($("#bottomPreviewPembanding[data-value='" + i + "']").is(':visible') == true) {
      $("#bottomPreviewPembanding[data-value='" + i + "']").hide('slow')
      $("#bodyPreviewPembanding[data-value='" + i + "']").hide('slow')
      $("#bodyCollapse[data-value='" + i + "']").hide("slow")
      $(".btnMinus[data-value='pembanding_" + i + "']").find('span').removeClass("fa-angle-down").addClass("fa-angle-right")
    }
    if ($("#bottomPreviewPembanding[data-value='" + i + "']").is(':hidden') == true){
      $("#bottomPreviewPembanding[data-value='" + i + "']").show('slow')
      $("#bodyPreviewPembanding[data-value='" + i + "']").show('slow')
      $(".btnMinus[data-value='pembanding_" + i + "']").find('span').removeClass("fa-angle-right").addClass("fa-angle-down")
    }
  }  

  function btnMinusNotes(i){
    if ($("#bodyCollapse[data-value='" + i + "']").is(':visible') == true) {
      $("#bodyCollapse[data-value='" + i + "']").hide("slow")

    }
    if ($("#bodyCollapse[data-value='" + i + "']").is(':hidden') == true){
      $("#bodyCollapse[data-value='" + i + "']").show("slow")

    }
  }

  function sirkulasi(n){
    if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getCountComparing')}}",
        data: {
          no_pr:window.location.href.split("/")[6],
        },
        success: function(result) {
          var x = document.getElementsByClassName("tab-sirkulasi");
          x[n].style.display = "inline";

          //nnti juga kasih rolenya siapa
          if (result > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Circulating this PR",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                  $.ajax({
                    url: "{{'/admin/circulerPr'}}",
                    type: 'post',
                    data:{
                      _token:"{{ csrf_token() }}",
                      no_pr:window.location.href.split("/")[6],
                    },
                    beforeSend:function(){
                      Swal.fire({
                          title: 'Please Wait..!',
                          text: "It's sending..",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: false,
                          customClass: {
                              popup: 'border-radius-0',
                          },
                          didOpen: () => {
                              Swal.showLoading()
                          }
                      })
                    },
                    success: function(data)
                    {
                      Swal.showLoading()
                      Swal.fire(
                          'Circulating PR Success by Admin/procurement!',
                          'PR will be signed soon, please wait for further progress',
                          'success'
                      ).then((result) => {
                          if (result.value) {
                            location.reload()
                          }
                      })
                    }
                  });
                }
            })
            
          }else{
            $("#ModalSirkulasiPr").modal('show')  
            $(".modal-dialog").removeClass('modal-lg')
            if (n == 0) {
              $("#nextBtnSirkulasi").click(function(){
                if ($("#reasonNoPembanding").val() == "") {
                  $("#reasonNoPembanding").closest('.form-group').addClass('has-error')
                  $("#reasonNoPembanding").closest('textarea').next('span').show();
                  $("#reasonNoPembanding").prev('.input-group-addon').css("background-color","red");
                }else{
                  $.ajax({
                    url: "{{'/admin/circulerPrTanpaPembanding'}}",
                    type: 'post',
                    data:{
                      _token:"{{ csrf_token() }}",
                      no_pr:window.location.href.split("/")[6],
                      reason:$("#reasonNoPembanding").val()
                    },
                    beforeSend:function(){
                      Swal.fire({
                          title: 'Please Wait..!',
                          text: "It's sending..",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: false,
                          customClass: {
                              popup: 'border-radius-0',
                          },
                          didOpen: () => {
                              Swal.showLoading()
                          }
                      })
                    },
                    success: function(data)
                    {
                      Swal.showLoading()
                      Swal.fire(
                          'Successfully!',
                          'Circulating PR.',
                          'success'
                      ).then((result) => {
                          if (result.value) {
                            location.reload()
                          }
                      })
                    }
                  });
                }
                
              })
            }
          }
        }  
      })
    }else{
      $("#ModalSirkulasiPr").modal('show')
      $(".modal-dialog").addClass('modal-lg')

      n = 1
      currentTab = 0
      var x = document.getElementsByClassName("tab-sirkulasi");
      x[n].style.display = "inline";
      x[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        x[n].style.display = "none";
        currentTab = 0;
      }
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getDetailPr')}}",
        data: {
          no_pr:window.location.href.split("/")[6],
        },
        success: function(result) {
          var append = ""
          var i = 0
          $("#bodyPreviewSirkulasi").empty()

          $.each(result.product,function(value,item){
            i++
            append = append + '<tr>'
              append = append + '<td>'
                append = append + '<span>'+ i +'</span>'
              append = append + '</td>'
              append = append + '<td width="20%">'
                append = append + '<input style="font-size:12px" class="form-control" type="" name="" value="'+ item.name_product +'" readonly>'
              append = append + '</td>'
              append = append + '<td width="35%">'
                append = append + '<textarea style="font-size: 12px; important;height:150px;resize:none" class="form-control" readonly>' + item.description.replaceAll("<br>","\n") + '</textarea>'
              append = append + '</td>'
              append = append + '<td width="10%">'
                append = append + '<input class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size:12px" readonly>'
              append = append + '</td>'
              append = append + '<td width="10%">'
                append = append + '<select style="width:80px;font-size:12px" class="form-control" readonly>'
                if (item.unit == 'Pcs') {
                  append = append + '<option selected>Pcs</option>'
                  append = append + '<option>Unit</option>'
                }else{
                  append = append + '<option>Pcs</option>'
                  append = append + '<option selected>Unit</option>'
                }
                append = append + '</select>'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size:12px" readonly>'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input class="form-control" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size:12px" readonly>'
              append = append + '</td>'
            append = append + '</tr>'
          })

          $("#bodyPreviewSirkulasi").append(append) 

          $("#bottomPreviewSirkulasi").empty()

          appendBottom = ""
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
          appendBottom = appendBottom + '<div id="textareaTOP" readonly class="form-control" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'

          $("#bottomPreviewSirkulasi").append(appendBottom) 

          $("#textareaTOP").html(result.pr.term_payment) 
        }
      })
    }
  }

  function finalize(){
    localStorage.setItem('isEmail',true)
    $("#showDetail").empty()
    showEmail()
  }

  $('#bodyOpenMail').slimScroll({
    height: '600px'
  });

  $('#table-produk').slimScroll({
    width: '600px'
  });

  function showEmail(){
    append = ""
    append = append + '<div class="col-md-12">'
      append = append + '<div class="box">'
        append = append + '<div class="box-body">'
          append = append + '<div class="form-horizontal">'
            append = append + '<div class="form-group">'
              append = append + '<label class="col-sm-1 control-label">'
                append = append + 'To : '
              append = append + '</label>'
              append = append + '<div class="col-sm-11">'
                append = append + '<input class="form-control" name="emailTo" id="emailOpenTo">'
              append = append + '</div>'
              append = append + '<div class="col-sm-11 col-sm-offset-1 help-block" style="margin-bottom: 0px;">'
                append = append + 'Enter the recipient of this open email!'
              append = append + '</div>'
            append = append + '</div>'
            append = append + '<div class="form-group">'
              append = append + '<label class="col-sm-1 control-label">'
                append = append + 'Cc :'
              append = append + '</label>'
              append = append + '<div class="col-sm-11">'
                append = append + '<input class="form-control" name="emailCc" id="emailOpenCc">'
              append = append + '</div>'
            append = append + '</div>'
            append = append + '<div class="form-group">'
              append = append + '<label class="col-sm-1 control-label">'
                append = append + 'Subject :'
              append = append + '</label>'
              append = append + '<div class="col-sm-11">'
                append = append + '<input class="form-control" name="emailSubject" id="emailOpenSubject">'
              append = append + '</div>'
            append = append + '</div>'
            append = append + '<div class="form-group">'
              append = append + '<div class="col-sm-12">'
                append = append + '<div contenteditable="true" class="form-control" style="height: 600px;overflow: auto;" id="bodyOpenMail">'
                append = append + '</div>'
              append = append + '</div>'
            append = append + '</div>'
            append = append + '<div class="form-group">'
              append = append + '<div class="col-sm-12">'
                // append = append + '<div style="margin-top:10px">'
                //   append = append + '<div style="padding: 15px;background-color: #e0dfda;width: fit-content;display:inline">'
                //     append = append + '<span style="display:inline;padding-right: 100px;"><i class="fa fa-file-pdf-o"></i>&nbsp PR - Subject</span>'
                //     append = append + '<span style="display:inline;"><i class="fa fa-times"></i></span>'
                //   append = append + '</div>'
                  append = append + '<button class="btn btn-flat btn-primary pull-right" style="display:inline" onclick="sendOpenEmail()"><i class="fa fa-envelope-o"></i> Send</button>'
                append = append + '</div>'
              append = append + '</div>'
            append = append + '</div>'
          append = append + '</div>'
        append = append + '</div>'
      append = append + '</div>'      
    append = append + '</div>'

    $("#showDetail").append(append)
    createEmailBody()
  }

  function createEmailBody(){
    $('.emailMultiSelector').remove()
    
    $.ajax({
      url:"{{url('/admin/getEmailTemplate')}}",
      data:{
        no_pr:window.location.href.split("/")[6],
      },
      type:"GET",
      success: function (result){
        $("#bodyOpenMail").html("<span style='font-family: Lucida Sans Unicode, sans-serif;'>Dear <b>Mba Felicia</b></span><br><br><span style='font-family: Lucida Sans Unicode, sans-serif;'>Berikut Terlampir PR, Mohon untuk dibuatkan PO</span><br><br>" + result)       
      }
    })

    $.ajax({
      type:"GET",
      url:"{{url('/admin/getDataSendEmail')}}",
      data:{
        no_pr:window.location.href.split("/")[6]
      },
      success: function(result){
        // $('.emailMultiSelector').remove()
        
        arrEmailCc = []
        $.each(result.cc,function(value,item){
          
          arrEmailCc.push(item.email)
        })
        arrEmailCcJoin = arrEmailCc.filter(function(i) {
            if (i != null || i != false)
                return i;
        }).join(";")
       
        $("#emailOpenTo").val(result.to)
        // $("#emailOpenTo").emailinput({ onlyValidValue: true, delim: ';' });
        $("#emailOpenCc").val(arrEmailCcJoin)
        // $("#emailOpenCc").emailinput({ onlyValidValue: true, delim: ';' });
        $("#emailOpenSubject").val(result.subject);
      },complete: function(){
        $("#emailOpenTo").emailinput({ onlyValidValue: true, delim: ';' });
        $("#emailOpenCc").emailinput({ onlyValidValue: true, delim: ';' });
      }
    })
  }

  function sendOpenEmail(){
    Swal.fire({
      title: 'Are you sure?',
      text: 'Make sure there is nothing wrong to send this',
      icon: 'warning',
      showCancelButton: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
      }).then((result) => {
        if (result.value){
          $.ajax({
            type: "POST",
            url: "{{url('/admin/sendMailtoFinance')}}",
            data: {
              _token: "{{ csrf_token() }}",
              no_pr:window.location.href.split("/")[6],
              body:$("#bodyOpenMail").html(),
              subject: $("#emailOpenSubject").val(),
              to: $("#emailOpenTo").val(),
              cc: $("#emailOpenCc").val(),
            },
            beforeSend: function(){
              Swal.fire({
                title: 'Please Wait..!',
                text: "It's sending..",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                customClass: {
                  popup: 'border-radius-0',
                },
                didOpen: () => {
                  Swal.showLoading()
                }
              })
            },
            success: function(resultAjax){
              Swal.hideLoading()
              Swal.fire({
                title: 'Success!',
                text: "Email Sended",
                icon: 'success',
                confirmButtonText: 'Reload',
              }).then((result) => {
                location.reload()
              })
            },
            error: function(resultAjax,errorStatus,errorMessage){
              Swal.hideLoading()
              Swal.fire({
                title: 'Error!',
                text: "Something went wrong, please try again!",
                icon: 'error',
                confirmButtonText: 'Try Again',
              }).then((result) => {
                $.ajax(this)
              })
            }
          });
        }
      }
    );
  }

  function submitTTD(value){
    if (value == 'ready') {
      if ($("input[type='radio'][name='selectTTD']:checked").length == 1) {
        $.ajax({
          type:"POST",
          url:"{{url('/admin/submitTtdApprovePR')}}",
          data:{
            _token: "{{ csrf_token() }}",
            no_pr:window.location.href.split("/")[6]
          },
          beforeSend:function(){
            Swal.fire({
              title: 'Please Wait..!',
              text: "It's sending..",
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false,
              customClass: {
                  popup: 'border-radius-0',
              },
            })
            Swal.showLoading()
          },
          success: function(result){
            Swal.hideLoading()
            Swal.fire(
                'Successfully!',
                'Circulating PR.',
                'success'
            ).then((result) => {
                if (result.value) {
                  location.reload()
                }
            })
          }
        })
      }else{
        alert('Please Select TTD')
      }
    }else{
      let formData = new FormData();
      const inputTTD = $('#inputTTD').prop('files')[0];
      if (inputTTD != "") {
        formData.append('inputTTD', inputTTD)
      }
      formData.append('no_pr', window.location.href.split("/")[6])
      formData.append('_token',"{{ csrf_token() }}")
      $.ajax({
      type:"POST",
      url:"{{url('/admin/uploadTTD')}}",
      data:formData,
      processData: false,
      contentType: false,
      beforeSend:function(){
        Swal.fire({
          title: 'Please Wait..!',
          text: "It's sending..",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          customClass: {
              popup: 'border-radius-0',
          },
        })
        Swal.showLoading()
      },
      success: function(result){
        Swal.hideLoading()
        Swal.fire(
            'Successfully!',
            'Circulating PR.',
            'success'
        ).then((result) => {
            if (result.value) {
              location.reload()
            }
        })
      }
    })
    }
  }

  $("#btnAccept").click(function(){
    $("#ModalSirkulasiPr").modal("hide")
    $(".modal-dialog").removeClass("modal-lg")
    $.ajax({
      type:"GET",
      url:"{{url('/admin/cekTTD')}}",
      data:{
        nik:"{{Auth::User()->nik}}",
      },
      success: function(result){
        if (result.ttd == null) {
          $("#ModalUploadNewTTD").modal("show")
        }else{
          $("#ModalAcceptSirkulasi").modal("show")
          $("#TTD").attr("src","{!! asset('"+result.ttd+"')!!}")
        }
      }
    })
  })

  $("#btnReject").click(function(){
    $("#ModalSirkulasiPr").modal("hide")
    $(".modal-dialog").removeClass("modal-lg")
    $("#ModalRejectSirkulasi").modal("show")
  })

  $('#ModalSirkulasiPr').on('hidden.bs.modal', function () {
    $(".tab-sirkulasi").css('display','none')
    currentTab = 0
    n = 0
  })

  $('#ModalDraftPr').on('hidden.bs.modal', function () {
    $(".tab-add").css('display','none')
    currentTab = 0
    n = 0
  }) 

  $("#btnUploadNewTTD").click(function(){
    $("#ModalAcceptSirkulasi").modal("hide")
    $("#ModalUploadNewTTD").modal("show")
  })

  $("#textAreaTOP").wysihtml5();
  

  function addPembanding(){
    // location.replace("{{url('/admin/draftPR/addPembanding')}}/"+ window.location.href.split("/")[6])
    currentTab = 0
    addDraftPrPembanding(0)
  }

  function addDraftPrPembanding(n){
    const firstLaunch = localStorage.setItem('firstLaunch',true)
    localStorage.setItem('isStoreSupplier',false)

    
    var x = document.getElementsByClassName("tab-add");
    x[n].style.display = "inline";
    if (n == (x.length - 1)) {
      $(".modal-dialog").addClass('modal-lg')
      $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
      $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
      $(".modal-title").text('')
      document.getElementById("prevBtnAdd").style.display = "inline";
      $("#headerPreviewFinal").empty()
      
      document.getElementById("nextBtnAdd").innerHTML = "Create";
      $("#nextBtnAdd").attr('onclick','createPRPembanding()');  

      $.ajax({
        type: "GET",
        url: "{{url('admin/getPreviewPembanding')}}",
        data: {
            no_pr:localStorage.getItem('no_pembanding'),
        },
        success: function(result) {
          if (result.pr.type_of_letter  == 'IPR') {
            PRType = '<b>Internal Purchase Request</b>'
          }else{
            PRType = '<b>External Purchase Request</b>'
          }

          var appendHeader = ""
          if (result.pr.type_of_letter == 'IPR') {
            appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><br>"
            appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span></br>"
            appendHeader = appendHeader + "<span style='display:inline'>Address: <span id='textAddress'>"+ result.pr.address +"</span></span<br>"
          }else{
            appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ result.pr.request_method +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
            appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ result.pr.lead_id +"</span><br>"
            appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
            appendHeader = appendHeader + '<span>Address: <span id="textQuoteNumber" style="display:inline;" class="pull-right">'+ result.pr.quote_number +'</span></span><br>'
            appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br><br>'
          }

          $("#headerPreviewFinal").append(appendHeader)

          $("#tbodyFinalPageProducts").empty()
          var append = ""
          var i = 0
          $.each(result.product,function(value,item){
            i++
            append = append + '<tr>'
              append = append + '<td>'
                append = append + '<span>'+ i +'</span>'
              append = append + '</td>'
              append = append + '<td width="20%">'
                append = append + '<input style="font-size: 12px; important" readonly class="form-control" type="" name="" value="'+ item.name_product +'">'
              append = append + '</td>'
              append = append + '<td width="35%">'
                append = append + '<textarea style="font-size: 12px; important;height:150px;resize:none" readonly class="form-control">' + item.description.replaceAll("<br>","\n") + '</textarea>'
              append = append + '</td>'
              append = append + '<td width="10%">'
                append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px; important">'
              append = append + '</td>'
              append = append + '<td width="10%">'
                append = append + '<select readonly style="width:75px;font-size: 12px; important" class="form-control">'
                if (item.unit == 'Pcs') {
                  append = append + '<option selected>Pcs</option>'
                  append = append + '<option>Unit</option>'
                }else{
                  append = append + '<option>Pcs</option>'
                  append = append + '<option selected>Unit</option>'
                }
                append = append + '</select>'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input style="font-size: 12px; important" readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px">'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input readonly class="form-control grandTotalPreviewPembandingModal" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px; important">'
              append = append + '</td>'
            append = append + '</tr>'
          })

          $("#tbodyFinalPageProducts").append(append)

          $("#bottomPreviewFinal").empty()        
          appendBottom = ""
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<div class="form-group">'
          appendBottom = appendBottom + '<div class="row">'
          appendBottom = appendBottom + '  <div class="col-md-12">'
          appendBottom = appendBottom + '    <div class="pull-right">'
          appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
          appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;" class="form-control inputGrandTotalProductPembandingModal" id="inputGrandTotalProductPembandingModal" name="inputGrandTotalProductPembandingModal">'
          appendBottom = appendBottom + '    </div>'
          appendBottom = appendBottom + '  </div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
          appendBottom = appendBottom + ' <div class="col-md-12">'
          appendBottom = appendBottom + '   <div class="pull-right">'
            appendBottom = appendBottom + '   <span style="margin-right: -5px;">Vat 11%</span>'
            appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
            appendBottom = appendBottom + '       <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_PembandingModal" name="vat_tax_PembandingModal" style="width:150px;">'
            appendBottom = appendBottom + '     </div>'
          appendBottom = appendBottom + '    </div>'
          appendBottom = appendBottom + ' </div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
          appendBottom = appendBottom + '  <div class="col-md-12">'
          appendBottom = appendBottom + '    <div class="pull-right">'
          appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
          appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;" class="form-control inputFinalPageTotalPricePembandingModal" id="inputFinalPageTotalPricePembandingModal" name="inputFinalPageTotalPricePembandingModal">'
          appendBottom = appendBottom + '    </div>'
          appendBottom = appendBottom + '  </div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
          appendBottom = appendBottom + '<div class="form-control" id="termPreviewPembandingModal" style="width: 100%; height: 200px; font-size: 12px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<span><b>Attached Files</b></span>'
          var pdf = "fa fa-fw fa-file-pdf-o"
          var image = "fa fa-fw fa-file-image-o"
          if (result.pr.type_of_letter == 'IPR') {
            appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
              appendBottom = appendBottom + '<div class="row">'
                appendBottom = appendBottom + '<div class="col-md-6">'
                  appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+result.dokumen[0].link_drive+'" target="blank"><i class="fa fa-fw fa-file-pdf-o"></i>'+ result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1] +'</a>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '<div class="col-md-6">'
                  appendBottom = appendBottom + '<div style="padding: 5px;">Penawaan Harga</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            
            $.each(result.dokumen,function(value,item){
              if (item.dokumen_location.split(".")[1] == 'pdf') {
                var fa_doc = pdf
              }else{
                var fa_doc = image
              }
              if (value != 0) {
                appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                  appendBottom = appendBottom + '<div class="row">'
                    appendBottom = appendBottom + '<div class="col-md-6">'
                      appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                      appendBottom = appendBottom + '</div>'
                    appendBottom = appendBottom + '</div>'
                    appendBottom = appendBottom + '<div class="col-md-6">'
                      appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen Pendukung : &nbsp'+ item.dokumen_name +'</div>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              }        
            })
          }else{        
            $.each(result.dokumen,function(value,item){
              if (item.dokumen_location.split(".")[1] == 'pdf') {
                  var fa_doc = pdf
                }else{
                  var fa_doc = image
                }
                appendBottom = appendBottom + '<div class="form-group" style="font-size: reguler;">'
                  appendBottom = appendBottom + '<div class="row">'
                    appendBottom = appendBottom + '<div class="col-md-6">'
                      appendBottom = appendBottom + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                      appendBottom = appendBottom + '</div>'
                    appendBottom = appendBottom + '</div>'
                    appendBottom = appendBottom + '<div class="col-md-6">'
                      appendBottom = appendBottom + '<div style="padding: 5px;">Dokumen &nbsp'+ item.dokumen_name +'</div>'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'      
            })
          }
               
          $("#bottomPreviewFinal").append(appendBottom)   

          $("#termPreviewPembandingModal").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))

          var tempVat = 0
          var finalVat = 0
          var tempGrand = 0
          var finalGrand = 0
          var tempTotal = 0
          var sum = 0

          $('.grandTotalPreviewPembandingModal').each(function() {
              var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
              sum += temp;
          });

          if (result.pr.status_tax == 'True') {
            tempVat = formatter.format((parseFloat(sum) * 11) / 100)
            tempGrand = parseInt(sum) +  parseInt((parseInt(sum) * 11) / 100)
          }else{
            tempVat = tempVat
            tempGrand = parseInt(sum)
          }

          finalVat = tempVat

          finalGrand = tempGrand

          tempTotal = sum
          $("#vat_tax_PembandingModal").val(tempVat)
          $("#inputGrandTotalProductPembandingModal").val(formatter.format(sum))
          $("#inputFinalPageTotalPricePembandingModal").val(formatter.format(tempGrand))
        }
      })
                    
    } else {
      if (n == 0) {
        $("#divNotePembanding").show()
        $("#selectType").attr("disabled",true)
        $("#selectCategory").attr("disabled",true)
        $("#selectMethode").attr("disabled",true)

        const firstLaunch = localStorage.getItem('firstLaunch')
        document.getElementById("prevBtnAdd").style.display = "none";
        $(".modal-title").text('Information Supplier')
        $(".modal-dialog").removeClass('modal-lg')

        $.ajax({
          type:"GET",
          url:"{{url('/admin/getPreviewPr')}}",
          data:{
            no_pr:window.location.href.split("/")[6]
          },success:function(result){
            $("#selectType").val(result.pr.type_of_letter)
            $("#selectCategory").val(result.pr.category)
            if (result.pr.request_method == 'Reimbursement') {
              $("#selectMethode").val('reimbursement')
            }else if (result.pr.request_method == 'Purchase Order') {
              $("#selectMethode").val('purchase_order')
            }else{
              $("#selectMethode").val('payment')
            }
            if(result.pr.type_of_letter == 'EPR'){
              localStorage.setItem('isPembandingEPR',true)
            }
          }
        })

        if (firstLaunch == 'true') {
          $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1,'+ firstLaunch +')')
        }else{
          $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(2,'+ firstLaunch +')')
        }         
      }else if (n == 1) {
        $(".modal-title").text('Information Product')
        $(".modal-dialog").removeClass('modal-lg')  
        $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
        $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
        document.getElementById("prevBtnAdd").style.display = "inline";
      }else if(n == 2){
        $(".modal-title").text('')
        $("#nextBtnAdd").removeAttr('onclick')
        $(".modal-dialog").addClass('modal-lg')

        localStorage.setItem('firstLaunch',false)
        if (localStorage.getItem('firstLaunch') == 'false') {
          $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-2)')
          $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
        }else{
          $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')
        } 
        document.getElementById("prevBtnAdd").style.display = "inline";
      }else if (n == 3) {
        $(".modal-dialog").removeClass('modal-lg')
        $.ajax({
          type: "GET",
          url: "{{url('/admin/getTypePr')}}",
          data: {
            no_pr:window.location.href.split("/")[6],
          },success:function(result){
            if (result[0].type_of_letter == 'EPR') {
              $(".modal-title").text('External Purchase Request')
              $("#formForPrExternal").show()
              $("#formForPrInternal").hide()    
              $.ajax({
                type:"GET",
                url:"{{url('/admin/getPreviewPr')}}",
                data:{
                  no_pr:window.location.href.split("/")[6]
                },success:function(result){
                  $("#selectLeadId").val(result.pr.lead_id).trigger("change").attr("disabled",true)
                  $("#selectQuoteNumber").val(result.pr.quote_number).trigger("change").attr("disabled",true)
                  $("#selectPid").val(result.pr.pid).trigger("change").attr("disabled",true)      
                  $("#inputSPK").attr('disabled',true)
                  $("#inputSBE").attr('disabled',true)

                  const fileSPK   = document.querySelector('input[type="file"][name="inputSPK"]');

                  const fileSBE   = document.querySelector('input[type="file"][name="inputSBE"]');

                  const fileQuote = document.querySelector('input[type="file"][name="inputQuoteSupplier"]');

                  if (result.dokumen.length > 0) {
                    if (result.dokumen[0] !== undefined) {
                      const myFileSpk = new File(['{{asset("/")}}"'+ result.dokumen[0].dokumen_location +'"'], '/'+ result.dokumen[0].dokumen_location,{
                          type: 'text/plain',
                          lastModified: new Date(),
                      });

                      // Now let's create a DataTransfer to get a FileList
                      const dataTransferSpk = new DataTransfer();
                      dataTransferSpk.items.add(myFileSpk);
                      fileSPK.files = dataTransferSpk.files;

                      if (result.dokumen[0].link_drive != null) {
                        $("#span_link_drive_spk").show()
                        $("#link_spk").attr("href",result.dokumen[0].link_drive) 
                      }
                    }

                    if (result.dokumen[1] !== undefined) {
                      const myFileSbe = new File(['{{asset("/")}}"'+ result.dokumen[1].dokumen_location +'"'], '/'+ result.dokumen[1].dokumen_location,{
                        type: 'text/plain',
                        lastModified: new Date(),
                      });
                      // Now let's create a DataTransfer to get a FileList
                      const dataTransferSbe = new DataTransfer();
                      dataTransferSbe.items.add(myFileSbe);
                      fileSBE.files = dataTransferSbe.files;

                      if (result.dokumen[1].link_drive != null) {
                        $("#span_link_drive_sbe").show()
                        $("#link_sbe").attr("href",result.dokumen[1].link_drive)
                      }
                    }
                  }
                }
              })              
              $("#makeId").hide()                 
            }else{
              $(".modal-title").text('Internal Purchase Request')
              $("#formForPrInternal").show()
              $("#formForPrExternal").hide()        
            } 
          }
        })

        $.ajax({
          url: "{{url('/admin/getLead')}}",
          type: "GET",
          success: function(result) {
            $("#selectLeadId").select2({
                data: result.data
            }).on('change', function() {
              var data = $("#selectLeadId option:selected").text();
              
              $.ajax({
                url: "{{url('/admin/getPid')}}",
                type: "GET",
                data: {
                  lead_id:data
                },
                success: function(result) {
                  $("#selectPid").select2({
                      data: result.data
                  })
                }
              }) 

              $.ajax({
                url: "{{url('/admin/getQuote')}}",
                type: "GET",
                data:{
                  lead_id:data
                },
                success: function(result) {
                  $("#selectQuoteNumber").select2({
                      data: result.data
                  })
                }
              }) 
            })
          }
        })
        $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
        $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
        document.getElementById("prevBtnAdd").style.display = "inline";
      }else if (n == 4) {
        $(".modal-title").text('Term Of Payment')   
        $(".modal-dialog").removeClass('modal-lg')   
        $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
        $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
        document.getElementById("prevBtnAdd").style.display = "inline";
      }
      document.getElementById("nextBtnAdd").innerHTML = "Next"
      $("#nextBtnAdd").prop("disabled",false)
      $("#addProduct").attr('onclick','nextPrevAddPembanding(-1)')
    }
    $("#ModalDraftPr").modal("show")  
  }

  function fillInput(val){
    if (val == "to") {
      $("#inputTo").closest('.form-group').removeClass('has-error')
      $("#inputTo").closest('input').next('span').hide();
      $("#inputTo").prev('.input-group-addon').css("background-color","red");
    }else if (val == "email") {
      $("#inputEmail").closest('.form-group').removeClass('has-error')
      $("#inputEmail").closest('input').next('span').hide();
      $("#inputEmail").prev('.input-group-addon').css("background-color","red");
    }else if (val == "phone") {
      $("#inputPhone").inputmask({"mask": "999-999-999-999"})
      $("#inputPhone").closest('.form-group').removeClass('has-error')
      $("#inputPhone").closest('input').next('span').hide();
      $("#inputPhone").prev('.input-group-addon').css("background-color","red");
    }else if(val == "subject") {
      $("#inputSubject").closest('.form-group').removeClass('has-error')
      $("#inputSubject").closest('input').next('span').hide();
      $("#inputSubject").prev('.input-group-addon').css("background-color","red");
    }else if(val == "attention") {
      $("#inputAttention").closest('.form-group').removeClass('has-error')
      $("#inputAttention").closest('input').next('span').hide();
      $("#inputAttention").prev('.input-group-addon').css("background-color","red");
    }else if(val == "from") {
      $("#inputFrom").closest('.form-group').removeClass('has-error')
      $("#inputFrom").closest('input').next('span').hide();
      $("#inputFrom").prev('.input-group-addon').css("background-color","red");
    }else if(val == "address") {
      $("#inputAddress").closest('.form-group').removeClass('has-error')
      $("#inputAddress").closest('input').next('span').hide();
      $("#inputAddress").prev('.input-group-addon').css("background-color","red");  
    }

    if (val == "name_product") {
      $("#inputNameProduct").closest('.form-group').removeClass('has-error')
      $("#inputNameProduct").closest('input').next('span').hide();
      $("#inputNameProduct").prev('.input-group-addon').css("background-color","red");
    }
    if (val == "desc_product") {
      $("#inputDescProduct").closest('.form-group').removeClass('has-error')
      $("#inputDescProduct").closest('textarea').next('span').hide();
      $("#inputDescProduct").prev('.input-group-addon').css("background-color","red");
    }
    if (val == "qty_product") {
      $("#inputTotalPrice").val(formatter.format(parseInt($("#inputQtyProduct").val()) * parseInt($("#inputPriceProduct").val().replace(/\,/g,''))))
      $("#inputQtyProduct").closest('.col-md-4').removeClass('has-error')
      $("#inputQtyProduct").closest('input').next('span').hide();
      $("#inputQtyProduct").prev('.input-group-addon').css("background-color","red");
    }
    if (val == "price_product") {
      formatter.format($("#inputPriceProduct").val())
      $("#inputTotalPrice").val(formatter.format(parseInt($("#inputQtyProduct").val()) * parseInt($("#inputPriceProduct").val().replace(/\,/g,''))))
      $("#inputPriceProduct").closest('.col-md-4').removeClass('has-error')
      $("#inputPriceProduct").closest('input').closest('.input-group').next('span').hide();
      $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
    }
    if (val == "spk") {
      $("#inputSPK").closest('.form-group').removeClass('has-error')
      $("#inputSPK").closest('input').next('span').hide();
      $("#inputSPK").prev('.input-group-addon').css("background-color","red");
    }

    if (val == "sbe") {
      $("#inputSBE").closest('.form-group').removeClass('has-error')
      $("#inputSBE").closest('input').next('span').hide();
      $("#inputSBE").prev('.input-group-addon').css("background-color","red");
    }

    if (val == "quoteSupplier") {
      $("#inputQuoteSupplier").closest('.form-group').removeClass('has-error')
      $("#inputQuoteSupplier").closest('input').next('span').hide();
      $("#inputQuoteSupplier").prev('.input-group-addon').css("background-color","red");  
    }

    if (val == "quoteNumber") {
      $("#inputQuoteNumber").closest('.form-group').removeClass('has-error')
      $("#inputQuoteNumber").closest('input').next('span').hide();
      $("#inputQuoteNumber").prev('.input-group-addon').css("background-color","red");  
    }

    if (val == "textArea_TOP") {
      $("#textAreaTOP").closest('.form-group').removeClass('has-error')
      $("#textAreaTOP").closest('textarea').next('span').hide();
      $("#textAreaTOP").prev('.input-group-addon').css("background-color","red"); 
    }
  }

  function nextPrevAddPembanding(n,value) {
    if (currentTab == 0) {
      if ($("#inputTo").val() == "") {
        $("#inputTo").closest('.form-group').addClass('has-error')
        $("#inputTo").closest('input').next('span').show();
        $("#inputTo").prev('.input-group-addon').css("background-color","red");
      }else if ($("#inputEmail").val() == "") {
        $("#inputEmail").closest('.form-group').addClass('has-error')
        $("#inputEmail").closest('input').next('span').show();
        $("#inputEmail").prev('.input-group-addon').css("background-color","red");
      }else if ($("#selectPosition").val() == "") {
        $("#selectPosition").closest('.form-group').addClass('has-error')
        $("#selectPosition").closest('select').next('span').show();
        $("#selectPosition").prev('.input-group-addon').css("background-color","red");
      }else if ($("#inputPhone").val() == "") {
        $("#inputPhone").closest('.form-group').addClass('has-error')
        $("#inputPhone").closest('input').next('span').show();
        $("#inputPhone").prev('.input-group-addon').css("background-color","red");
      }else if($("#inputAttention").val() == "") {
        $("#inputAttention").closest('.form-group').addClass('has-error')
        $("#inputAttention").closest('input').next('span').show();
        $("#inputAttention").prev('.input-group-addon').css("background-color","red");
      }else if($("#inputFrom").val() == "") {
        $("#inputFrom").closest('.form-group').addClass('has-error')
        $("#inputFrom").closest('input').next('span').show();
        $("#inputFrom").prev('.input-group-addon').css("background-color","red");
      }else if($("#inputSubject").val() == "") {
        $("#inputSubject").closest('.form-group').addClass('has-error')
        $("#inputSubject").closest('input').next('span').show();
        $("#inputSubject").prev('.input-group-addon').css("background-color","red");
      }else if($("#inputAddress").val() == "") {
        $("#inputAddress").closest('.form-group').addClass('has-error')
        $("#inputAddress").closest('textarea').next('span').show();
        $("#inputAddress").prev('.input-group-addon').css("background-color","red");
      }else{
        if (value == true) {
          isStoreSupplier = localStorage.getItem('isStoreSupplier')
          if (isStoreSupplier == 'false') {
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Save info Supplier",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    Swal.showLoading()
                    $.ajax({
                      type:"POST",
                      url:"{{url('/admin/storePembandingSupplier')}}",
                      data:{
                        _token: "{{ csrf_token() }}",
                        inputTo:$("#inputTo").val(),
                        selectType:$("#selectType").val(),
                        inputEmail:$("#inputEmail").val(),
                        inputPhone:$("#inputPhone").val(),
                        // inputFax:$("#inputFax").val(),
                        inputAttention:$("#inputAttention").val(),
                        inputSubject:$("#inputSubject").val(),
                        inputAddress:$("#inputAddress").val(),
                        selectMethode:$("#selectMethode").val(),
                        selectPosition:$("#selectPosition").val(),
                        selectCategory:$("#selectCategory").val(),
                        note_pembanding:$("#note_pembanding").val(),
                        no_pr:window.location.href.split("/")[6]
                      },
                      beforeSend:function(){
                        Swal.fire({
                            title: 'Please Wait..!',
                            text: "It's sending..",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            customClass: {
                                popup: 'border-radius-0',
                            },
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        })
                      },
                      success: function(result){
                        localStorage.setItem('isStoreSupplier',true)
                        Swal.close()
                        var x = document.getElementsByClassName("tab-add");
                        x[currentTab].style.display = "none";
                        currentTab = currentTab + n;
                        if (currentTab >= x.length) {
                          x[n].style.display = "none";
                          currentTab = 0;
                        }
                        addDraftPrPembanding(currentTab);
                        localStorage.setItem('no_pembanding',result)
                        localStorage.setItem('id_draft_pr',window.location.href.split("/")[6])
                      }
                    })
                }
                
            })
          }else{
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            addDraftPrPembanding(currentTab);
          }
        }else{
          var x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          addDraftPrPembanding(currentTab);
        }
      }
    }else if (currentTab == 1) {
      if ($("#inputNameProduct").val() == "") {
        $("#inputNameProduct").closest('.form-group').addClass('has-error')
        $("#inputNameProduct").closest('input').next('span').show();
        $("#inputNameProduct").prev('.input-group-addon').css("background-color","red");
      }else if ($("#inputDescProduct").val() == "") {
        $("#inputDescProduct").closest('.form-group').addClass('has-error')
        $("#inputDescProduct").closest('textarea').next('span').show();
        $("#inputDescProduct").prev('.input-group-addon').css("background-color","red");
      }else if ($("#inputQtyProduct").val() == "") {
        $("#inputQtyProduct").closest('.col-md-4').addClass('has-error')
        $("#inputQtyProduct").closest('input').next('span').show();
        $("#inputQtyProduct").prev('.input-group-addon').css("background-color","red");
      }else if ($("#selectTypeProduct").val() == "") {
        $("#selectTypeProduct").closest('.col-md-4').addClass('has-error')
        $("#selectTypeProduct").closest('select').next('span').show();
        $("#selectTypeProduct").prev('.input-group-addon').css("background-color","red");
      }else if ($("#inputPriceProduct").val() == "") {
        $("#inputPriceProduct").closest('.col-md-4').addClass('has-error')
        $("#inputPriceProduct").closest('input').closest('.input-group').next('span').show();
        $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
      }else{
        if (n == '1') {
          $.ajax({
              url: "{{url('/admin/storePembandingProduct')}}",
              type: 'post',
              data: {
               _token:"{{ csrf_token() }}",
               no_pr:localStorage.getItem('no_pembanding'),
               inputNameProduct:$("#inputNameProduct").val(),
               inputDescProduct:$("#inputDescProduct").val(),
               inputQtyProduct:$("#inputQtyProduct").val(),
               selectTypeProduct:$("#selectTypeProduct").val(),
               inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,''),
               inputTotalPrice:$("#inputTotalPrice").val(),
               inputGrandTotalProduct:$("#inputGrandTotalProduct").val(),
              },beforeSend:function(){
                Swal.fire({
                    title: 'Please Wait..!',
                    text: "It's sending..",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    customClass: {
                        popup: 'border-radius-0',
                    },
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
              },success:function(){
                Swal.close()
                var x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                addDraftPrPembanding(currentTab);

                addTable(0)
                $("#inputNameProduct").val('')
                $("#inputDescProduct").val('')
                $("#inputPriceProduct").val('')
                $("#inputQtyProduct").val('')
                // $("#selectTypeProduct").val('')
                $("#inputSerialNumber").val('')
                $("#inputPartNumber").val('')
                $("#inputTotalPrice").val('')
              }
          })
        }else{
          var x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          addDraftPrPembanding(currentTab);
        }                
      }       
    }else if (currentTab == 3) {
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getTypePr')}}",
        data: {
          no_pr:window.location.href.split("/")[6],
        },success:function(result){
          
          if (result[0].type_of_letter == 'IPR') {
            if ($("#inputPenawaranHarga").val() == "") {
              $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
              $("#inputPenawaranHarga").closest('div').next('span').show();
              $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");
            }else{
              
              let formData = new FormData();
              const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
              if (filepenawaranHarga!="") {
                formData.append('inputPenawaranHarga', filepenawaranHarga);
                // formData.append('nama_file_penawaranHarga', nama_file_penawaranHarga);
              }

              $(".tableDocPendukung").empty()

              var arrInputDocPendukung = []
              $('#tableDocPendukung .trDocPendukung').each(function() {
                formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                arrInputDocPendukung.push({
                  nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                  no_pr:localStorage.getItem('no_pembanding'),
                })
              });

              formData.append('_token',"{{csrf_token()}}")
              formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukung))
              formData.append('no_pr',localStorage.getItem('no_pembanding'))
              formData.append('id_draft_pr',localStorage.getItem('id_draft_pr'))

              if (n == 1) {
                $.ajax({
                  type:"POST",
                  url:"{{url('/admin/storePembandingDokumen')}}",
                  processData: false,
                  contentType: false,
                  data:formData,
                  beforeSend:function(){
                    Swal.fire({
                        title: 'Please Wait..!',
                        text: "It's sending..",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        customClass: {
                            popup: 'border-radius-0',
                        },
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    })
                  },
                  success: function(result){
                    // localStorage.setItem('isStoreSupplier',true)
                    Swal.close()
                    var x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    addDraftPrPembanding(currentTab);
                  }
                }) 
              }else{
                var x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                addDraftPrPembanding(currentTab);
              }
            }
          }else{
            if ($("#selectLeadId").val() == "") {
              $("#selectLeadId").closest('.col-md-6').addClass('has-error')
              $("#selectLeadId").closest('select').next('span help-block').show();
              $("#selectLeadId").prev('.col-md-6').css("background-color","red");
            }else if ($("#selectPid").val() == "") {
              $("#selectPid").closest('.col-md-6').addClass('has-error')
              $("#selectPid").closest('select').next('span help-block').show();
              $("#selectPid").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputQuoteSupplier").val() == "") {
              $("#inputQuoteSupplier").closest('.col-md-6').addClass('has-error')
              $("#inputQuoteSupplier").closest('div').next('span').show();
              $("#inputQuoteSupplier").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputQuoteNumber").val() == "") {
              $("#inputQuoteNumber").closest('.col-md-6').addClass('has-error')
              $("#inputQuoteNumber").closest('input').next('span').show();
              $("#inputQuoteNumber").prev('.col-md-6').css("background-color","red");
            }else{
              let formData = new FormData();
              const fileQuoteSupplier = $('#inputQuoteSupplier').prop('files')[0];
              var nama_file_quote_supplier = $('#inputQuoteSupplier').val();
              if (nama_file_quote_supplier!="" && fileQuoteSupplier!="") {
                formData.append('inputQuoteSupplier', fileQuoteSupplier);
              }

              formData.append('_token',"{{csrf_token()}}")
              formData.append('no_pr', localStorage.getItem('no_pembanding'))
              formData.append('selectLeadId', $("#selectLeadId").val())
              formData.append('selectPid', $("#selectPid").val())
              formData.append('inputPid',$("#projectIdInputNew").val())
              formData.append('selectQuoteNumber', $("#selectQuoteNumber").val())

              if (n == 1) {
                $.ajax({
                  type:"POST",
                  url:"{{url('/admin/storePembandingDokumen')}}",
                  processData: false,
                  contentType: false,
                  data:formData,
                  beforeSend:function(){
                    Swal.fire({
                        title: 'Please Wait..!',
                        text: "It's sending..",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        customClass: {
                            popup: 'border-radius-0',
                        },
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    })
                  },
                  success: function(result){
                    // localStorage.setItem('isStoreSupplier',true)
                    Swal.close()
                    var x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    addDraftPrPembanding(currentTab);
                  }
                })
              }else{
                var x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                addDraftPrPembanding(currentTab);
              }              
            } 
          }
        }
      })
    }else if (currentTab == 4) {
      if ($("#textAreaTOP").val() == "") {
        $("#textAreaTOP").closest('.form').addClass('has-error')
        $("#textAreaTOP").closest('textarea').next('span').show();
        $("#textAreaTOP").prev('.form').css("background-color","red");
      }

      $.ajax({
        url: "{{'/admin/storePembandingTermPayment'}}",
        type: 'post',
        data:{
          no_pr:localStorage.getItem('no_pembanding'),
          _token:"{{csrf_token()}}",
          textAreaTOP:$("#textAreaTOP").val(),
          status_tax:localStorage.getItem('status_tax')
        },
        success: function(data)
        {
          var x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          addDraftPrPembanding(currentTab);
        }
      }); 
    }else{
      var x = document.getElementsByClassName("tab-add");
      x[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        x[n].style.display = "none";
        currentTab = 0;
      }
      addDraftPrPembanding(currentTab);
    }
  }  
  
  function createPRPembanding(){
    Swal.fire({
        title: 'Are you sure?',
        text: "Submit Draft PR",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
    }).then((result) => {
      if (result.value) {
          $.ajax({
            type:"POST",
            url:"{{url('/admin/storeLastStepPembanding')}}",
            data:{
              _token:"{{csrf_token()}}",
              no_pr:localStorage.getItem('no_pembanding'),
              inputGrandTotalProduct:$("#inputFinalPageTotalPricePembandingModal").val(),
            },beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's sending..",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  customClass: {
                      popup: 'border-radius-0',
                  },
                  didOpen: () => {
                      Swal.showLoading()
                  }
              })
              Swal.showLoading()
            },
            success: function(result){
              Swal.close()
              Swal.fire(
                  'Successfully!',
                  'Verify PR Successfully.',
                  'success'
              ).then((result) => {
                  if (result.value) {
                    location.replace("{{url('/admin/detail/draftPR')}}/"+ window.location.href.split("/")[6])
                    localStorage.setItem('isLastStorePembanding',true)
                  }
              })
            }
          })
      }
    })
  } 

  var tempVat = 0
  var finalVat = 0
  var tempGrand = 0
  var finalGrand = 0
  var tempTotal = 0
  var btnVatStatus = true
  localStorage.setItem("status_tax",'True')

  function changeVatValue(){
    if($("#btn-vat").hasClass('btn-default')){
      btnVatStatus = false
      finalVat = 0
      finalGrand = tempTotal
      $("#btn-vat").removeClass('btn-default')
      $("#btn-vat").addClass('btn-danger')
      $("#btn-vat").text('✖')
      $("#vat_tax").val(0)
      $("#inputGrandTotalProductFinal").val(formatter.format(tempTotal))
      localStorage.setItem("status_tax",'False')

    } else {
      btnVatStatus = true
      finalVat = tempVat
      finalGrand = tempGrand
      $("#btn-vat").addClass('btn-default')
      $("#btn-vat").removeClass('btn-danger')
      $("#btn-vat").text('✓')
      $("#vat_tax").val(formatter.format(tempVat))
      $("#inputGrandTotalProductFinal").val(formatter.format(tempGrand))
      localStorage.setItem("status_tax",'True')
    }
  }

  function addTable(n){ 
    $.ajax({
        type: "GET",
        url: "{{url('/admin/getProductPembanding')}}",
        data: {
          no_pr:localStorage.getItem('no_pembanding')
        },
        success: function(result) {
          $("#tbodyProducts").empty()
          var append = ""
          var i = 0
          $.each(result.data,function(value,item){
            i++
           append = append + '<tr>'
            append = append + '<td>'
              append = append + '<span style="font-size: 12px; important">'+ i +'</span>'
            append = append + '</td>'
            append = append + '<td width="20%">'
              append = append + '<input id="inputNameProductEdit" data-value="" readonly style="font-size: 12px; important" class="form-control" type="" name="" value="'+ item.name_product +'">'
            append = append + '</td>'
            append = append + '<td width="30%">'
              append = append + '<textarea id="textAreaDescProductEdit" readonly data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">'+ item.description.replaceAll("<br>","\n") +''
              append = append + '</textarea>'
            append = append + '</td>'
            append = append + '<td width="7%">'
              append = append + '<input id="inputQtyEdit" data-value="" readonly style="font-size: 12px; important;width:70px" class="form-control" type="number" name="" value="'+ item.qty +'">'
            append = append + '</td>'
            append = append + '<td width="10%">'
            append = append + '<select id="inputTypeEdit" readonly data-value="" style="font-size: 12px; important;width:70px" class="form-control">'
            if (item.unit == 'Pcs') {
              append = append + '<option selected>Pcs</option>'
              append = append + '<option>Unit</option>'
            }else{
              append = append + '<option>Pcs</option>'
              append = append + '<option selected>Unit</option>'
            }
            append = append + '</select>' 
            append = append + '</td>'
            append = append + '<td width="15%">'
              append = append + '<input id="inputPriceEdit" readonly data-value="" style="font-size: 12px; important" class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'">'
            append = append + '</td>'
            append = append + '<td width="15%">'
              append = append + '<input id="inputTotalPriceEdit" readonly data-value="" style="font-size: 12px; important" class="form-control inputTotalPriceEdit" type="" name="" value="'+ formatter.format(item.grand_total) +'">'
            append = append + '</td>'
          append = append + '</tr>'   
        })    

        $("#tbodyProducts").append(append)

        $("#bottomProducts").empty()

        var appendBottom = ""
        appendBottom = appendBottom + '<hr>'
        appendBottom = appendBottom + '<div class="row">'
          appendBottom = appendBottom + '  <div class="col-md-12">'
          appendBottom = appendBottom + '    <div class="pull-right">'
          appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
          appendBottom = appendBottom + '      <input readonly="" type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProduct" id="inputGrandTotalProduct" name="inputGrandTotalProduct">'
          appendBottom = appendBottom + '    </div>'
          appendBottom = appendBottom + '  </div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
          appendBottom = appendBottom + ' <div class="col-md-12">'
          appendBottom = appendBottom + '   <div class="pull-right">'
            appendBottom = appendBottom + '   <span style="margin-right: -36px;">Vat 11%</span>'
            appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
              appendBottom = appendBottom + '   <span style="margin-right: 33px;" class="input-group-btn pull-right">'
                appendBottom = appendBottom + ' <button type="button" class="btn btn-flat btn-default" id="btn-vat" onclick="changeVatValue()">✓</button>'
              appendBottom = appendBottom + ' </span>'
            appendBottom = appendBottom + '   <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax" name="vat_tax" style="width:215px;">'
            appendBottom = appendBottom + ' </div>'
          appendBottom = appendBottom + ' </div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '</div>'
          appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
          appendBottom = appendBottom + '  <div class="col-md-12">'
          appendBottom = appendBottom + '    <div class="pull-right">'
          appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
          appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductFinal" id="inputGrandTotalProductFinal" name="inputGrandTotalProductFinal">'
          appendBottom = appendBottom + '    </div>'
          appendBottom = appendBottom + '  </div>'
          appendBottom = appendBottom + '</div>'

        $("#bottomProducts").append(appendBottom) 

        var sum = 0
        $('.inputTotalPriceEdit').each(function() {
            var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
            sum += temp;
        });

        $("#inputGrandTotalProduct").val(formatter.format(sum))

        tempVat = (parseFloat(sum) * 11) / 100

        finalVat = tempVat

        tempGrand = parseInt(sum) +  parseInt((parseInt(sum) * 11) / 100)

        finalGrand = tempGrand

        tempTotal = sum
        $("#vat_tax").val(formatter.format(tempVat))


        $("#inputGrandTotalProductFinal").val(formatter.format(tempGrand))
      }
    })
  }

  var incrementDoc = 0
  function addDocPendukung(i){
    incrementDoc++
    $("#titleDoc").show()
    append = ""
      append = append + "<tr style='height:10px' class='trDocPendukung'>"
        append = append + "<td>"
          append = append + '<button type="button" class="fa fa-times btnRemoveAddDocPendukung" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
          append = append + '<i class="fa fa-cloud-upload"></i>&nbsp<input style="display:inline" class="pull-right inputDocPendukung_'+incrementDoc+'" type="file" name="inputDocPendukung[]" id="inputDocPendukung">'
        append = append + "</td>"
        append = append + "<td>"
          append = append + '<input style="margin-left:20px" class="form-control" name="inputNameDocPendukung" id="inputNameDocPendukung">'
        append = append + "</td>"
      append = append + "</tr>"
    $("#tableDocPendukung").append(append)

    // $(".inputDocPendukung_"+incrementDoc).change(function() {
    // if (this.value != "") {
    //     $(".inputDocPendukung_"+incrementDoc).removeClass("hidden")
    //     $(".inputDocPendukung_"+incrementDoc).closest("input").next("label").hide()
    //   }else{
    //     $(".inputDocPendukung_"+incrementDoc).addClass("hidden")
    //     $(".inputDocPendukung_"+incrementDoc).closest("input").next("label").show()
    //   }
    // });
  }  

  $(document).on('click', '.btnRemoveAddDocPendukung', function() {
    $(this).closest("tr").remove();
    if($('#tableDocPendukung tr').length == 0){
      
      $("#titleDoc").hide()
    }
  });

  function refreshTable(){
    addTable()
  }

  function next(n){
    currentTab = 0
    var x = document.getElementsByClassName("tab-sirkulasi");
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
      x[n].style.display = "none";
      currentTab = 0;
    }
    sirkulasi(currentTab);
  }

  function showPdf(){
    $.ajax({
      type:"GET",
      url:"{{url('/admin/getPdfPRFromLink')}}",
      data:{
        no_pr:window.location.href.split("/")[6],
      },success:function(result){
        window.open(result);
      }
    })
  }

  function rejectSirkulasi(){
    $.ajax({
      type: "POST",
      url: "{{url('/admin/rejectCirculerPR')}}",
      data: {
        _token: "{{ csrf_token() }}",
        no_pr:window.location.href.split("/")[6],
        reasonRejectSirkular:$("#reasonRejectSirkular").val(),
      },beforeSend:function(){
        Swal.fire({
            title: 'Please Wait..!',
            text: "It's sending..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
                popup: 'border-radius-0',
            },
            didOpen: () => {
                Swal.showLoading()
            }
        })
      },
      success: function(result) {
        Swal.hideLoading()
        Swal.fire(
            'Successfully!',
            'success',
            'success'
        ).then((result) => {
          if (result.value) {
            location.replace("{{url('/admin/draftPR')}}/")
            Swal.close()
          }
        })
        
      }
    }) 
  }
</script>
@endsection