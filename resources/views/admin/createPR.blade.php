@extends('template.main')
@section('tittle')
  Draft Purchase Request
@endsection
@section('head_css')
<!--select 2-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css"> -->

<!--datatables-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.dataTables.min.css"> -->

<!--datepicker-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-barber-shop.css">

<style type="text/css">
  .modal { overflow: auto !important; }
  .textarea-scrollbar {
      /*overflow:scroll !important;*/    
      overflow-y: scroll !important;
      resize: none !important;
      -ms-overflow-style: scrollbar; 
  }

  .textarea-scrollbar::-webkit-scrollbar {
      width: 12px;
  }

  .textarea-scrollbar::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
    background-color: #555;
  }

  .textarea-scrollbar::-webkit-scrollbar {
    width: 12px;
  }

  .icheckbox_minimal-blue{
    margin-left: 147px;
  }

  input[type=file]::-webkit-file-upload-button{
   display: none;
  }

  input::file-selector-button {
   display: none;
  }

  a{
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
  }

  .dataTables_filter {
    display: none;
  }
</style>
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" type="text/css" href="{{asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('/plugins/iCheck/all.css')}}">
@endsection
@section('content')
<section class="content-header">
  <h1>
    Draft List Purchase Request
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Purchase Request</li>
  </ol>
</section>
<section class="content">
  <div class="row" id="BoxId">
    <!--box id-->
  </div>
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <button class="btn btn-md btn-primary pull-right" style="display:none;" dusk="addDraftPr" id="addDraftPr" onclick="addDraftPr(0)" ><i class="fa fa-plus"></i> Draft PR</button>
        </div>
        <div class="box-body">
          <div class="row" style="margin-bottom:10px" id="filterBox">
            <div class="col-md-2 col-xs-12">
              <b>Filter by Type PR : </b>
              <div>
                <select class="form-control select2" id="inputFilterTypePr" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true">
                </select>
              </div>
            </div>

            <div class="col-md-2 col-xs-12">
              <b>Filter by Status : </b>
              <div>
                <select class="form-control select2" id="inputFilterStatus" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true"></select>
              </div>
            </div>

            <div class="col-md-2 col-xs-12" id="filterUser" style="display:none">
              <b>Filter by User : </b>
              <div>
                <select class="form-control select2" id="inputFilterUser" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true"></select>
              </div>
            </div>

            <div class="col-md-2 col-xs-12">
              <b>Range Date PR : </b>

              <button type="button" class="btn btn-default btn-flat pull-left" style="width:100%" id="inputRangeDate" disabled>
                <i class="fa fa-calendar"></i> Date range picker
                <span>
                  <i class="fa fa-caret-down"></i>
                </span>
              </button>
            </div>
            
            <div class="col-md-4 col-xs-12">
              <b>Search Anything : </b>
              <div class="input-group pull-right">
                <input id="inputSearchAnything" onchange="searchCustom()" type="text" class="form-control" placeholder="ex: PR Id">
                
                <div class="input-group-btn">
                  <button type="button" id="btnShowEntryTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Show 10 
                    <span class="fa fa-caret-down"></span>
                  </button>
                  <ul class="dropdown-menu" id="selectShowEntryTicket">
                    <li><a href="#" onclick="changeNumberEntries(10)">10</a></li>
                    <li><a href="#" onclick="changeNumberEntries(25)">25</a></li>
                    <li><a href="#" onclick="changeNumberEntries(50)">50</a></li>
                    <li class="active"><a href="#" onclick="changeNumberEntries(100)">100</a></li>
                  </ul>
                </div>
                <span class="input-group-btn">
                  <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" type="button" class="btn btn-default btn-flat">
                    <i class="fa fa-fw fa-remove"></i>
                  </button>
                  
                </span>
              </div>
            </div>
                
          </div>
          <div class="table-responsive">
            <table class="table datatable table-striped dataTable" id="draftPr" width="100%" cellspacing="0">
              <thead>
                <tr style="text-align: center;">
                  <th>No. PR</th>
                  <th>Created at</th>
                  <th>Subject</th>
                  <th>Issued By</th>
                  <th>Supplier</th>
                  <th>Total Price</th>
                  <th style="text-align: center;vertical-align: middle;">Status</th>
                  <th style="text-align: center;vertical-align: middle;">Action</th>
                </tr>
              </thead>
              <tbody id="tbodyDraft" name="tbodyDraft">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="ModalDraftPr" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Information Supplier</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="" id="modal_pr" name="modal_pr">
        @csrf
        <!--lagi ngedit-->

        <div class="tab-add" style="display:none;">
          <div class="tabGroup">
            <div class="form-group">
              <label for="">To*</label>
              <select id="selectTo" name="selectTo" class="form-control select2" style="width:100%!important" onchange="fillInput('selectTo')"><option></option></select>
              <a id="otherTo" style="cursor:pointer;">Other</a>
              <div id="divInputTo" class="divInputTo" style="display: none;">
                <button type="button" class="close" aria-hidden="true" style="color:red">×</button>
                <input autocomplete="off" type="" class="form-control" placeholder="ex. PT Multi Solusindo Perkasa" id="inputTo" name="inputTo" onkeyup="fillInput('to')">
                <small>
                  *Sertakan bentuk usaha/badan hukum dari supplier apabila ada (PT/CV)<br>
                  *PT/CV ditulis capital<br>
                  *Nama perusahaan ditulis dengan Capital diawal suku kata(Multi Solusindo Perkasa)
                </small>
              </div>
              <span class="help-block" style="display:none;">Please fill To!</span>
            </div>      

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Type*</label>
                  <select autofocus type="text" class="form-control" name="type" onchange="fillInput('selectType')" placeholder="ex. Internal Purchase Request" id="selectType" required>
                      <option value="">Select Type</option>
                      <option value="IPR">IPR (Internal Purchase Request)</option>
                      <option value="EPR">EPR (Eksternal Purchase Request)</option>
                  </select>
                  <span class="help-block" style="display:none;">Please fill Type!</span>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Email*</label>
                  <input autocomplete="off" type="" class="form-control" placeholder="ex. absolut588@gmail.com" id="inputEmail" name="inputEmail" onkeyup="fillInput('email')">
                  <span class="help-block" style="display:none;">Please fill Email!</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Category</label>
              <select autofocus type="text" class="form-control select2" onchange="fillInput('selectCategory')" name="selectCategory" id="selectCategory" style="width: 100%">
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
              <span class="help-block" style="display:none;">Please fill Category!</span>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Phone*</label>
                  <input autocomplete="off" class="form-control" id="inputPhone" type="" name="" placeholder="ex. 999-999-999-999" onkeyup="fillInput('phone')">
                  <span class="help-block" style="display:none;">Please fill Phone!</span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Attention*</label>
                  <input autocomplete="off" type="text" class="form-control" placeholder="ex. Marsono" name="inputAttention" id="inputAttention" onkeyup="fillInput('attention')">
                  <span class="help-block" style="display:none;">Please fill Attention!</span>
                </div>
              </div>
            </div>           

            <div class="form-group">
              <label for="">Subject*</label>
              <input autocomplete="off" type="text" class="form-control" placeholder="ex. Pembelian laptop MSI Modern 14 (Sdri. Faiqoh, Sdr. Oktavian, Sdr. Subchana)" name="inputSubject" id="inputSubject" onkeyup="fillInput('subject')">
              <span class="help-block" style="display:none;">Please fill Subject!</span>
            </div>

            <div class="form-group">
              <label for="">Address*</label>
              <textarea autocomplete="off" class="form-control" id="inputAddress" name="inputAddress" placeholder="ex. Plaza Pinangsia Lt. 1 No. 7-8 Jl. Pinangsia Raya no.1" onkeyup="fillInput('address')" style="resize: vertical;"></textarea>
              <span class="help-block" style="display:none;">Please fill Address!</span>
            </div>

            <div class="form-group">
              <label for="">Request Methode*</label>
              <select autofocus type="text" class="form-control" placeholder="ex. Purchase Order" name="type" id="selectMethode" required >
                  <option value="">Select Methode</option>
                  <option value="purchase_order">Purchase Order</option>
                  <option value="payment">Payment</option>
                  <option value="reimbursement">Reimbursement</option>
              </select>
              <span class="help-block" style="display:none;">Please fill Type!</span>
            </div>

            <div class="form-group">
              <div style="display: inline;text-align:justify;">                
                <span style="position:absolute;"><input type="checkbox" id="cbCommit" class="minimal" value=""/></span>
                <span style="display:flex;margin-left: 25px;display:flex;">This supplier has been committed with us to supply this product</span>
              </div>
            </div>

            <div class="form-group" id="divNotePembanding" style="display:none;">
              <label for="">Note Pembanding*</label>
              <textarea autocomplete="off" class="form-control" id="note_pembanding" name="note_pembanding"></textarea>
              <span class="help-block" style="display:none;">Please fill Note Pembanding!</span>
            </div>
          </div>
        </div>
        <div class="tab-add" style="display:none">
          <div class="tabGroupInitiateAdd">
            <div class="form-group" style="display:flex">
              <button class="btn btn-primary" id="btnInitiateAddProduct" type="button" style="margin:0 auto;"><i class="fa fa-plus"></i>&nbspAdd Product</button>
            </div>
            <div class="form-group" style="display:flex;">
              <span style="margin:0 auto;">OR</span>
            </div>
            <div class="form-group" style="display: flex;">
              <div style="padding: 7px;
                          border: 1px solid #dee2e6 !important;
                          color: #337ab7;
                          height: 35px;
                          background-color: #eee;
                          display: inline;
                          margin: 0 auto;">
                <i class="fa fa-cloud-upload" style="margin-left:5px">
                <input id="uploadCsv" class="hidden" type="file" name="uploadCsv" style="margin-top: 3px;width: 80px;display: inline;"></i>
                <label for="uploadCsv">Upload CSV</label>
                <i class="fa fa-times hidden" onclick="cancelUploadCsv()" style="display:inline;color: red;"></i>
                <!-- <span class="help-block" style="display:none;">Please Upload File or Add Product!</span> -->
              </div>
            </div>         
            <div style="display: flex;">
              <span style="margin: 0 auto;">You can get format of CSV from this <a href="{{url('draft_pr/Import_product_sample.csv')}}" style="cursor:pointer;">link</a></span>
            </div>
            <div style="display: flex;">
              <span style="margin: 0 auto;">And make sure, the change of template only at row 2, any change on row 1 (header) will be reject</span>
            </div>
          </div>
          <div class="tabGroup" style="display:none">
            <div class="form-group">
              <label>Product*</label>
              <input autocomplete="off" type="text" name="" class="form-control" id="inputNameProduct" placeholder="ex. Laptop MSI Modern 14" onkeyup="fillInput('name_product')">
              <span class="help-block" style="display:none;">Please fill Name Product!</span>
            </div>
            <div class="form-group">
              <label>Description*</label> 
              <textarea onkeyup="fillInput('desc_product')" style="resize:vertical;height:150px" id="inputDescProduct" placeholder='ex. Laptop mSI Modern 14, Processor AMD Rayzen 7 5700, Memory 16GB, SSD 512 Gb, Screen 14", VGA vega 8, Windows 11 Home' name="inputDescProduct" class="form-control"></textarea>
              <span class="help-block" style="display:none;">Please fill Description!</span>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Serial Number</label>
                  <input autocomplete="off" type="text" name="" class="form-control" id="inputSerialNumber">
                </div> 
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                  <label>Part Number</label>
                  <input autocomplete="off" type="text" name="" class="form-control" id="inputPartNumber">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label>Qty*</label>
                  <input autocomplete="off" type="number" name="" class="form-control" id="inputQtyProduct" placeholder="ex. 5" onkeyup="fillInput('qty_product')">
                  <span class="help-block" style="display:none;">Please fill Qty!</span>
                </div> 
              </div>
              <div class="col-md-4" style="margin-bottom:10px"> 
                <label>Type*</label>
                <i class="fa fa-warning" title="If type is undefined, Please contact developer team!" style="display:inline"></i>
                <select style="width:100%;display:inline;" class="form-control" id="selectTypeProduct" placeholder="ex. Unit" onchange="fillInput('type_product')">
                  <option>                  
                </select>
                <span class="help-block" style="display:none;">Please fill Unit!</span>
              </div>
              <div class="col-md-6" style="margin-bottom:10px"> 
                <label>Price*</label>
                <div class="input-group">
                  <div class="input-group-addon">
                  Rp.
                  </div>
                  <input autocomplete="off" type="text" name="" class="form-control money" id="inputPriceProduct" placeholder="ex. 500,000.00" onkeyup="fillInput('price_product')">
                  <div class="input-group-btn">       
                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">         
                      <span class="fa fa-caret-down"></span>       
                    </button>       
                    <ul class="dropdown-menu">       
                      <li><a onclick="changeCurreny('usd')">USD($)</a></li>
                      <li><a onclick="changeCurreny('dollar')">IDR(RP)</a></li>
                    </ul>
                  </div>
                </div>
                <span class="help-block" style="display:none;">Please fill Price!</span>
              </div>
            </div>            
            <div class="form-group">
              <label>Total Price</label>
              <div class="input-group">
                <div class="input-group-addon">
                Rp.
                </div>
                  <input autocomplete="off" readonly type="text" name="" class="form-control" id="inputTotalPrice" placeholder="75.000.000,00">
              </div>
            </div>
          </div>
        </div> 
        <div class="tab-add" style="display:none">
          <div class="tabGroup table-responsive">
            <table class="table no-wrap">
              <thead>
                <th>No</th>
                <th>Product</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Type</th>
                <th>Price</th>
                <th>Total Price</th>
                <th><a class="pull-right" onclick="refreshTable()"><i class="fa fa-refresh"></i>&nbsp</a></th>
              </thead>
              <tbody id="tbodyProducts">
                
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-md-12" id="bottomProducts">
              
            </div>
          </div>
          <div class="form-group" style="display:flex;margin-top: 10px;">
            <button class="btn btn-sm btn-primary" style="margin: 0 auto;" type="button" id="addProduct"><i class="fa fa-plus"></i>&nbsp Add product</button>
          </div>
        </div>
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
            <div id="formForPrExternal" style="display:none">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>PID*</label>
                    <select id="selectPid" style="width: 100%;" class="select2 form-control" onchange="fillInput('selectPID')">
                      <option>
                    </select>
                    <span class="help-block" style="display:none;">Please fill PID!</span>
                    <span id="makeId" style="cursor: pointer;">other?</span>
                    <div class="form-group" id="project_idNew" style="display: none;">
                      <div class="input-group">
                        <input autocomplete="off" type="text" class="form-control pull-left col-md-8" placeholder="input Project ID" name="project_idInputNew" id="projectIdInputNew">
                        <span class="input-group-addon" style="cursor: pointer;" id="removeNewId"><i class="glyphicon glyphicon-remove"></i></span>
                      </div>
                    </div> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Lead Register*</label>
                    <select id="selectLeadId" style="width:100%" class="select2 form-control" onchange="fillInput('selectLeadId')">
                      <option></option>
                    </select>
                    <span class="help-block" style="display:none;">Please fill Lead Register!</span>
                  </div>
                </div>
              </div> 

              <div class="form-group">
                <label>Quote Number*</label>
                <select name="selectQuoteNumber" class="select2 form-control" id="selectQuoteNumber" >
                  <option>
                </select>
                <!-- <input type="file" class="files" name="inputQuoteNumber" id="inputQuoteNumber" class="form-control" onkeyup="fillInput('quoteNumber')"> -->
                <span class="help-block" style="display:none;">Please fill Quote Number!</span>
              </div> 

              <div class="form-group">
                <label>Quote Supplier*</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputQuoteSupplier" style="margin-bottom: 0px;">
                    <span class="fa fa-cloud-upload" style="display:inline;"></span>
                    <input autocomplete="off" style="display: inline;font-family: inherit;" type="file" class="files" name="inputQuoteSupplier" id="inputQuoteSupplier" onchange="fillInput('quoteSupplier')" >
                  </label>
                </div>
                <span class="help-block" style="display:none;">Please fill Quote Supplier!</span>
                <span style="display:none;" id="span_link_drive_quoteSup"><a id="link_quoteSup" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>             
              
              <div class="form-group">
                <label>SPK/Kontrak*</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputSPK" style="margin-bottom: 0px;">
                    <span class="fa fa-cloud-upload" style="display:inline;"></span>
                    <input autocomplete="off" style="display: inline;font-family: inherit;" type="file" class="files" name="inputSPK" id="inputSPK" onchange="fillInput('spk')" >
                  </label>
                </div>
                <span class="help-block" style="display:none;">Please fill SPK/Kontrak!</span>
                <span style="display:none;" id="span_link_drive_spk"><a id="link_spk" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>

              <div class="form-group">
                <label>SBE*</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputSBE" style="margin-bottom: 0px;">
                    <span class="fa fa-cloud-upload" style="display:inline;"></span>
                    <input autocomplete="off" style="display: inline;font-family: inherit;" type="file" class="files" name="inputSBE" id="inputSBE" onchange="fillInput('sbe')" >
                  </label>
                </div>
                <span class="help-block" style="display:none;">Please fill SBE!</span>
                <span style="display:none;" id="span_link_drive_sbe"><a id="link_sbe" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>

              <div id="docPendukungContainer" class="table-responsive">
                <label id="titleDoc_epr" style="display:none;">Lampiran Dokumen Lainnya</label>
                <table id="tableDocPendukung_epr" class="border-collapse:collapse" style="border-collapse: separate;border-spacing: 0 15px;">
                  
                </table>
              </div>
              <div class="form-group" style="display: flex;margin-top: 10px;">
                <button type="button" id="btnAddDocPendukung_epr" style="margin:0 auto" class="btn btn-sm btn-primary" onclick="addDocPendukung('epr')"><i class="fa fa-plus"></i>&nbsp Dokumen Lainnya</button>
              </div>  
            </div>
              
            <div id="formForPrInternal" style="display:none;">
              <div class="form-group">
                <label>Lampiran Penawaran Harga*</label>
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputPenawaranHarga" style="margin-bottom:0px">
                    <i class="fa fa-cloud-upload" style="display:inline"></i>
                    <input autocomplete="off" style="display: inline;" type="file" class="files" name="inputPenawaranHarga" id="inputPenawaranHarga" onchange="fillInput('penawaranHarga')">
                  </label>                  
                </div>
                <span class="help-block" style="display:none;">Please fill Penawaran Harga!</span>
                <span style="display:none;" id="span_link_drive_penawaranHarga"><a id="link_penawaran_harga" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>

              <div id="docPendukungContainer" class="table-responsive">
                <label id="titleDoc_ipr" style="display:none;">Lampiran Dokumen Pendukung</label>
                <table id="tableDocPendukung_ipr" class="border-collapse:collapse" style="border-collapse: separate;border-spacing: 0 15px;">
                  
                </table>
              </div>
              <div class="form-group" style="display: flex;margin-top: 10px;">
                <button type="button" id="btnAddDocPendukung_ipr" style="margin:0 auto" class="btn btn-sm btn-primary" onclick="addDocPendukung('ipr')"><i class="fa fa-plus"></i>&nbsp Dokumen Pendukung</button>
              </div>
            </div>
          </div>
        </div>   
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
            <div class="box-body pad">
              <textarea onkeydown="fillInput('textArea_TOP')" class="textarea" id="textAreaTOP" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);" placeholder="ex. Terms & Condition"></textarea>
              <span class="help-block" style="display:none;">Please fill Top of Payment!</span>
            </div>
          </div>
        </div>  
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
            <div class="row">
              <div class="col-md-12" id="headerPreviewFinal">
                
              </div>
            </div><br>
            <div class="row">
              <div class="col-md-12 table-responsive">
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

<div class="modal fade" id="ModalDraftPrAdmin" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Information Supplier</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="modal_pr" name="modal_pr">
          @csrf
          <div class="tab-cek" style="display:none;">
            <div class="form-group">
              <label for="">To*</label>
              <div class="input-group">                 
                  <input type="text" readonly class="form-control" placeholder="ex. eSmart Solution" id="inputToCek" name="inputToCek">
                <div class="input-group-addon">
                  <input onchange="checkBoxCek('to_cek')" id="to_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                </div>
              </div>
            </div>        

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Type*</label>
                  <div class="input-group">                 
                    <select type="text" readonly class="form-control" placeholder="ex. Internal Purchase Request" id="selectTypeCek">
                        <option selected value="IPR">IPR (Internal Purchase Request)</option>
                        <option value="EPR">EPR (Eksternal Purchase Request)</option>
                    </select>
                    <div class="input-group-addon">
                      <input onchange="checkBoxCek('type_cek')" id="type_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Email*</label>
                  <div class="input-group">                 
                    <input type="" readonly class="form-control" placeholder="ex. absolut588@gmail.com" id="inputEmailCek" name="inputEmailCek">
                    <div class="input-group-addon">
                      <input onchange="checkBoxCek('email_cek')" id="email_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>

            <div class="form-group">
                <label for="">Category</label>
                <div class="input-group">
                  <select readonly type="text" class="form-control select2" name="selectCategoryCek" id="selectCategoryCek"  style="width: 100%">
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
                  <div class="input-group-addon">
                    <input onchange="checkBoxCek('category_cek')" id="category_Cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Phone*</label>
                  <div class="input-group">
                    <input class="form-control" id="inputPhoneCek" type="" name="" placeholder="ex. 999-999-999-999" readonly>
                    <div class="input-group-addon">
                      <input onchange="checkBoxCek('phone_Cek')" id="phone_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Attention*</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="ex. Marsono" name="inputAttentionCek" id="inputAttentionCek" readonly>
                    <div class="input-group-addon">
                      <input onchange="checkBoxCek('attention_cek')" id="attention_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                    </div>
                  </div>
                </div>
               <!--  <div class="form-group">
                  <label for="">fax</label>
                  <input type="" id="inputFaxCek" class="form-control" name="inputFaxCek" readonly>
                </div> -->
              </div>
            </div> 

            <div class="form-group">
              <label for="">Subject*</label>
              <div class="input-group">
                <input type="text" class="form-control" placeholder="ex. Pembelian laptop MSI Modern 14 (Sdri. Faiqoh, Sdr. Oktavian, Sdr. Subchana)" name="inputSubjectCek" id="inputSubjectCek" onkeyup="fillInput('subject')" readonly>
                <div class="input-group-addon">
                  <input onchange="checkBoxCek('subject_cek')" id="subject_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                </div>
              </div>
              
            </div>

            <div class="form-group">
              <label for="">Address*</label>
              <div class="input-group">
                <textarea style="resize: none;height: 150px;" class="form-control" id="inputAddressCek" name="inputAddressCek" placeholder="ex. Plaza Pinangsia Lt. 1 No. 7-8 Jl. Pinangsia Raya no.1" onkeyup="fillInput('address')" readonly></textarea>
                <div class="input-group-addon">
                <input onchange="checkBoxCek('address_cek')" id="address_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                </div>
              </div>
              
            </div>

            <div class="form-group">
              <label for="">Request Methode*</label>
              <div class="input-group">
                <select type="text" class="form-control" placeholder="ex. Purchase Order" id="selectMethodeCek" readonly >
                    <option selected value="purchase_order">Purchase Order</option>
                    <option value="payment">Payment</option>
                    <option value="reimbursement">Reimbursement</option>
                </select>
                <div class="input-group-addon">
                  <input onchange="checkBoxCek('methode_cek')" id="methode_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                </div>
              </div>              
            </div>
          </div>
          <div class="tab-cek" style="display:none;">
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
                  <th><a class="pull-right" id="refreshTableCek"><i class="fa fa-refresh"></i>&nbsp</a></th>
                </thead>
                <tbody id="tbodyProductsCek"> 
                </tbody>
              </table>
            </div>            
            <div class="row">
              <div class="col-md-12" id="bottomProductsCek">
                
              </div>
            </div>
          </div>
          <div class="tab-cek" style="display:none">
            <div id="formForPrExternalCek" style="display:none">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label>Lead Register*</label>
                    <div class="input-group">
                      <input readonly id="selectLeadIdCek" class="form-control"/>
                      <div class="input-group-addon">
                        <input type="checkbox" readonly class="minimal" name="" id="lead_cek" onchange="checkBoxCek('lead_cek')">
                      </div>
                    </div>
                    
                  </div>
                  <div class="col-md-6">
                    <label>PID*</label>
                    <div class="input-group">
                      <input id="selectPidCek" readonly class="form-control"/>
                      <div class="input-group-addon">
                        <input type="checkbox" class="minimal" name="" id="pid_cek" onchange="checkBoxCek('pid_Cek')">
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>                
              
              <div class="form-group">
                <label>SPK/Kontrak*</label>
                <div class="input-group" readonly>
                  <div style="border: 1px solid #dee2e6 !important;padding: 5px;background-color: #EEEEEE;">
                    <i class="icon_spk" style="display:inline;color: #367fa9;"></i>
                    <a target="_blank" href="" id="link_spkCek"><input style="display: inline;background-color: transparent;border: none;" type="text" name="inputSPK" id="inputSPKCek" disabled></a>
                  </div>
                  <div class="input-group-addon">
                    <input type="checkbox" class="minimal" name="" id="spk_cek" onchange="checkBoxCek('spk_cek')">
                  </div>
                </div>
                <!-- <span style="display:none;" id="span_link_drive_spk_cek"><a id="link_spkCek" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span> -->
              </div>

              <div class="form-group">
                <label>SBE*</label>
                <div class="input-group" readonly>
                  <div style="border: 1px solid #dee2e6 !important;padding: 5px;background-color: #EEEEEE;">
                    <i class="icon_sbe" style="display:inline;color: #367fa9;"></i>
                    <a target="_blank" href="" id="link_sbeCek"><input style="display:inline;background-color: transparent;border: none;" type="text" name="inputSBECek" id="inputSBECek" disabled ></a>
                  </div>
                  <div class="input-group-addon">
                    <input type="checkbox" class="minimal" name="" id="sbe_cek" onchange="checkBoxCek('sbe_cek')">
                  </div>
                </div>
                <!-- <span style="display:none;" id="span_link_drive_sbe_cek"><a id="link_sbeCek" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span> -->
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label>Quote Supplier*</label>
                    <div class="input-group" readonly>
                      <div style="border: 1px solid #dee2e6 !important;padding: 5px;background-color: #EEEEEE;">
                        <i class="icon_quo" style="display:inline;color: #367fa9;"></i>
                        <a target="_blank" href="" id="link_quoteSupCek"><input style="display: inline;background-color: transparent;border: none;" type="text" name="inputQuoteSupplierCek" id="inputQuoteSupplierCek" disabled ></a>
                      </div>
                      <div class="input-group-addon">
                        <input type="checkbox" class="minimal" name="" id="quoSup_cek" onchange="checkBoxCek('quoSup_cek')">
                      </div>
                    </div>
                    <!-- <span style="display:none;" id="span_link_drive_quoteSup_cek"><a id="link_quoteSupCek" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span> -->
                  </div>
                  <div class="col-md-6">
                    <label>Quote Number*</label>
                    <div class="input-group">
                      <input readonly id="selectQuoteNumCek" class="form-control" />
                      <div class="input-group-addon">
                        <input type="checkbox" class="minimal" name="" id="quoNum_cek" onchange="checkBoxCek('quoNum_cek')">
                      </div>
                    </div>
                  </div>
                </div>
              </div>  

              <div class="form-group">
                <div id="docPendukungContainerCekEPR">
                </div>
              </div>  
            </div>
              
            <div id="formForPrInternalCek" style="display:none;">
              <div id="docPendukungContainerCek">
                
              </div>
            </div>
          </div>   
          <div class="tab-cek" style="display:none">
            <div class="input-group">
              <div readonly class="textarea-scrollbar" id="textAreaTOPCek" style="width: 100%;height: 210px;  font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);resize: none;overflow: auto;">
              </div>
              <div class="input-group-addon">
                <input type="checkbox" class="minimal" name="chk[]" id="textarea_top_cek" name="" onchange="checkBoxCek('textareaTOP')">
              </div>
            </div>
          </div>  
          <div class="tab-cek" style="display:none">
            <div class="row">
              <div class="col-md-12" id="headerPreviewFinalCek">
                
              </div>
            </div><br>
            <div class="row">
              <div class="col-md-12 table-responsive">
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
                  <tbody id="tbodyFinalPageProductsCek">
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" id="bottomPreviewFinalCek">
                
              </div>
            </div>              
          </div>  
          <div class="tab-cek" style="display:none">
            <div id="AllChecked">
              <div style="display: inline;text-align:justify;">                
                <span style="position:absolute;"><input type="checkbox" id="cbAllChecked" class="minimal" value=""/></span>
                <span style="display:flex;margin-left: 25px;display:flex;">Dengan ini saya menyatakan bahwa Draft PR ini sudah betul baik dari input yang diberikan beserta data pendukung yang dilampirkan dan Draft PR siap untuk dilanjutkan ke proses berikutnya</span>
              </div>
            </div>
            <div id="notAllChecked" style="display:none;">
                <div class="form-group">
                  <label>Approve/Reject*</label><br>
                  <div class="radio">
                    <label>
                      <input type="radio" class="minimal radioConfirm" name="radioConfirm" value="approve">
                      Approve
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" class="minimal radioConfirm" name="radioConfirm" value="reject">
                      Reject
                    </label>
                  </div>
                </div>
                <div class="form-group" style="display:none;" id="divReasonReject">
                  <h4>Reason of Reject</h4>
                  <textarea id="textAreaReasonReject" onkeyup="fillInput('reason_reject')" class="form-control" placeholder="ex. [Informasi Supplier - To] Ada Kesalahan Penulisan Nama" style="resize:vertical;"></textarea>
                  <span class="help-block" style="display:none;">Please fill Reason!</span>
                </div>
            </div>  
          </div> 
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="prevBtnAddAdmin">Back</button>
              <button type="button" class="btn btn-primary" id="nextBtnAddAdmin">Next</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>
@endsection
@section('scriptImport')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <!--datatables-->
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>

  <!--fixed column-->
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script src="{{asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.js" integrity="sha512-SSQo56LrrC0adA0IJk1GONb6LLfKM6+gqBTAGgWNO8DIxHiy0ARRIztRWVK6hGnrlYWOFKEbSLQuONZDtJFK0Q==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    $('.money').mask('#.##0,00', {reverse: true})

    // $(".money").mask('000,000,000,000,000', {reverse: true})

    $(document).ready(function(){ 
      currentTab = 0     
      $('input[class="files"]').change(function(){
        var f=this.files[0]
        var filePath = f;
     
        // Allowing file type
        var allowedExtensions =
        /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

        var ErrorText = []
        // 
        if (f.size > 30000000|| f.fileSize > 30000000) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file size, just allow file with size less than 30MB!',
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
            text: 'Invalid file type, just allow png/jpg/pdf file',
          }).then((result) => {
            this.value = ''
          })
        }
      }) 

      $('input[type="file"][name="uploadCsv"]').change(function(){
        var f=this.files[0]
        var filePath = f;

        var ext = filePath.name.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["csv"];


        if (arrayExtensions.lastIndexOf(ext) == -1) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file type, just allow csv file',
          }).then((result) => {
            this.value = ''
          })
        }else{
          $("#uploadCsv").next('label').hide()
          $("input[type='file'][name='uploadCsv']").removeClass('hidden')
          $("input[type='file'][name='uploadCsv']").prev('i').hide()
          $("#uploadCsv").next('label').next('i').removeClass('hidden') 
          $("#btnInitiateAddProduct").prop("disabled",true)
        }
      })

      //box id
        DashboardCounter()
        // InitiateFilterParam()
    })

    function select2TypeProduct(value){
      var dataTypeProduct = []
      dataTypeProduct = {
        "results": [
          {
            "id": "pcs",
            "text": "Pcs"
          },
          {
            "id": "unit",
            "text": "Unit"
          },
          {
            "id": "lot",
            "text": "Lot"
          },
          {
            "id": "pax",
            "text": "Pax"
          },
          {
            "id": "node",
            "text": "Node"
          },
          {
            "id": "kg",
            "text": "Kg"
          },
          {
            "id": "meter",
            "text": "Meter"
          },
          {
            "id": "paket",
            "text": "Paket"
          },
          {
            "id": "tahun",
            "text": "Tahun"
          },
          {
            "id": "rell",
            "text": "Rell"
          },
          {
            "id": "roll",
            "text": "Roll"
          },
          {
            "id": "ea",
            "text": "Ea"
          },
          {
            "id": "box",
            "text": "Box"
          },
          {
            "id": "rim",
            "text": "Rim"
          },
          {
            "id": "pad",
            "text": "Pad"
          },
          {
            "id": "set",
            "text": "Set"
          },
          {
            "id": "pack",
            "text": "Pack"
          },
          {
            "id": "core",
            "text": "Core"
          },
          {
            "id": "bh",
            "text": "bh"
          },
          {
            "id": "mandays",
            "text": "Mandays"
          }
        ]
      }

      $('#selectTypeProduct').select2({
        data:dataTypeProduct.results,
        placeholder:'Ex. Unit',
        dropdownParent: $('#ModalDraftPr')
      })

      if (value != undefined) {
        $('#selectTypeProduct').val(value.toLowerCase()).trigger('change')
      }
    }

    function cancelUploadCsv(){
      $("input[type='file'][name='uploadCsv']").val('')
      $("#uploadCsv").next('label').show()
      $("input[type='file'][name='uploadCsv']").addClass('hidden')
      $("input[type='file'][name='uploadCsv']").prev('i').show()
      $("#uploadCsv").next('label').next('i').addClass('hidden') 
      $("#btnInitiateAddProduct").prop("disabled",false)
    }

    function DashboardCounter(){
      $("#BoxId").empty()
      
      var countPr = []

      var i = 0
      var append = ""
      var colors = []
      var ArrColors = [{
            name: 'Need Attention',style: 'color:white', color: 'bg-yellow', icon: 'fa fa-exclamation',status:"NA",index: 0
        },
        {
            name: 'Ongoing',style: 'color:white', color: 'bg-primary', icon: 'fa fa-edit',status:"OG",index: 1
        },
        {
            name: 'Done',style: 'color:white', color: 'bg-green', icon: 'fa fa-check',status:"DO",index: 2
        },
        {
            name: 'All',style: 'color:white', color: 'bg-purple', icon: 'fa fa-list-ul',status:"ALL",index: 3
        },
      ]

      colors.push(ArrColors)
      $.each(colors[0], function(key, value){
        var status = "'"+ value.status +"'"
        append = append + '<div class="col-lg-3 col-xs-12">'
          append = append + '<div class="small-box ' + value.color + '">'
            append = append + '<div class="inner">'
              append = append + '<h3 style="'+ value.style +'" class="counter" id="count_pr_'+value.index+'"</h3>'
              append = append + '<h4 style="'+ value.style +'"><b>'+ value.name +'</b></h4>'

            append = append + '</div>'
            append = append + '<div class="icon">'
              append = append + '<i class="'+ value.icon +'" style="'+ value.style +';opacity:0.4"></i>'
            append = append + '</div>'
            append = append + '<a href="#" onclick="sortingByDashboard('+ status +')" class="small-box-footer">Sorting <i class="fa fa-filter"></i></a>'
          append = append + '</div>'
        append = append + '</div>'
      id = "count_pr_"+value.index
      countPr.push(id)
      })

      $("#BoxId").append(append)
  
      $.ajax({
        type:"GET",
        url:"{{url('/admin/getCount')}}",
        success:function(result){
            $("#"+countPr[0]).text(result.count_need_attention)
            $("#"+countPr[1]).text(result.count_ongoing)
            $("#"+countPr[2]).text(result.count_done)
            $("#"+countPr[3]).text(result.count_all)

          $('.counter').each(function () {
              var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
              $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
              }, {
                duration: 5000,
                step: function (func) {
                   $(this).text(parseFloat(func).toFixed(size));
                }
              });
          });
        },
      })
    }

    function DashboardCounterFilter(temp){
      $("#BoxId").empty()
      var countPr = []
      var i = 0
      var append = ""
      var colors = []
      var ArrColors = [{
            name: 'Need Attention',style: 'color:white', color: 'bg-yellow', icon: 'fa fa-exclamation',index: 0
        },
        {
            name: 'Ongoing',style: 'color:white', color: 'bg-primary', icon: 'fa fa-edit',index: 1
        },
        {
            name: 'Done',style: 'color:white', color: 'bg-green', icon: 'fa fa-check',index: 2
        },
        {
            name: 'All',style: 'color:white', color: 'bg-purple', icon: 'fa fa-list-ul',index: 3
        },
      ]

      colors.push(ArrColors)
      $.each(colors[0], function(key, value){
        append = append + '<div class="col-lg-3 col-xs-6">'
          append = append + '<div class="small-box ' + value.color + '">'
            append = append + '<div class="inner">'
              append = append + '<h3 style="'+ value.style +'" class="counter" id="count_pr_'+value.index+'"</h3>'
              append = append + '<h4 style="'+ value.style +'"><b>'+ value.name +'</b></h4>'
            append = append + '</div>'
            append = append + '<div class="icon">'
              append = append + '<i class="'+ value.icon +'" style="'+ value.style +';opacity:0.4"></i>'
            append = append + '</div>'
          append = append + '</div>'
        append = append + '</div>'
      id = "count_pr_"+value.index
      countPr.push(id)
      })

      $("#BoxId").append(append)

      $.ajax({
        type:"GET",
        url:"{{url('/admin/getFilterCount')}}" + temp,
        success:function(result){
            $("#"+countPr[0]).text(result.count_need_attention)
            $("#"+countPr[1]).text(result.count_ongoing)
            $("#"+countPr[2]).text(result.count_done)
            $("#"+countPr[3]).text(result.count_all)

          $('.counter').each(function () {
              var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
              $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
              }, {
                duration: 5000,
                step: function (func) {
                   $(this).text(parseFloat(func).toFixed(size));
                }
              });
          });
        },
      })
    }

    var accesable = @json($feature_item);
    accesable.forEach(function(item,index){
      $("#" + item).show()
    })

    var formatter = new Intl.NumberFormat(['ban', 'id']);

    function textAreaAdjust(element) {
      element.style.height = "1px";
      element.style.height = (25+element.scrollHeight)+"px";
    }

    Pace.restart();
    Pace.track(function() {
      $("#draftPr").DataTable({
          processing:true,
          serverSide:true,
          "ajax":{
            "type":"GET",
            "url":"{{url('/admin/getDraftPr')}}",
            "dataSrc": function (json){
              json.data.forEach(function(data,index){
                if (data.status == 'REJECT') {
                  data.status_numerical = 1
                }else if (data.status == 'UNAPPROVED') {
                  data.status_numerical = 2
                }else if (data.status == 'SAVED') {
                  data.status_numerical = 3
                }else if (data.status == 'DRAFT') {
                  data.status_numerical = 4
                }else if (data.status == 'VERIFIED') {
                  data.status_numerical = 5
                }else if (data.status == 'COMPARING') {
                  data.status_numerical = 6
                }else if (data.status == 'CIRCULAR') {
                  data.status_numerical = 7
                }else if (data.status == 'FINALIZED') {
                  data.status_numerical = 8
                }else if (data.status == 'SENDED') {
                  data.status_numerical = 9
                }else if (data.status == 'CANCEL') {
                  data.status_numerical = 10
                }
              })
              return json.data
            }
          },
          "columns": [
            { 
              render: function (data, type, row, meta){
                if (row.status == "SAVED" || row.status == "DRAFT") {
                  return " - "           
                }else{
                  return row.no_pr         
                }
              }
            },
            {
              orderData:[8],
              render: function (data, type, row, meta){
               return moment(row.date).format("D MMM YYYY");   
              }
            },
            { 
              render: function (data, type, row, meta){
                if (row.attention_notes == "False") {
                  return '<span class="label label-primary"><b><i>' + row.type_of_letter + '</i></b></span>&nbsp<i title="Pay Attention to the Notes!" class="fa fa-warning" style="color:red"></i> ' + row.title         
                }else{
                  return '<span class="label label-primary"><b><i>' + row.type_of_letter + '</i></b></span> ' + row.title         
                }
              },
              width:'300px'
            },
            { "data": "name"},
            { "data": "to"},
            { 
              render: function (data, type, row, meta){
                if (isNaN(row.nominal) == true) {
                  return formatter.format(row.nominal.replace(/\./g,'').replace(',','.').replace(' ',''))       
                }else{
                  return formatter.format(row.nominal)          
                }
              },
              className:'text-right'
            },
            { 
              orderData:[7],
              render: function (data, type, row, meta){
                if (row.status == 'SAVED') {
                  return '<span class="label label-primary">'+row.status+'</span>'           
                }else if (row.status == 'DRAFT') {
                  return '<span class="label label-primary">'+row.status+'</span>'           
                }else if (row.status == 'VERIFIED') {
                  return '<span class="label label-success">'+row.status+'</span>'
                }else if (row.status == 'COMPARING') {
                  return '<span class="label bg-purple">'+row.status+'</span>'
                }else if (row.status == 'CIRCULAR') {
                  if (row.circularby == "-") {
                    return '<span class="label label-warning">'+row.status+'</span><br><small>On Procurement<small>'
                  }else{
                    return '<span class="label label-warning">'+row.status+'</span><br><small>On '+ row.circularby +'<small>'
                  }
                }else if (row.status == 'FINALIZED') {
                  return '<span class="label label-success">'+row.status+'</span>'           
                }else if (row.status == 'SENDED') {
                  return '<span class="label label-primary">'+row.status+'</span>'           
                }else if (row.status == 'UNAPPROVED' || row.status == 'REJECT' || row.status == 'CANCEL') {
                  return '<span class="label label-danger">'+row.status+'</span>' 
                }
              },
              className:'text-center'
            },
            { 
              render: function (data, type, row, meta){
                let onclick = ""
                let title = ""
                let btnClass = ""
                let isDisabled = ""
                let isDisabledCancel = ""
                let btnId = ""
                let status = ""
                let value = ""

                if (row.status == 'DRAFT') {
                  onClick = ""
                  title = "Verify"
                  btnClass = "btnCekDraft btn-primary"
                  if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}" || "{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Manager")->exists()}}") {
                    isDisabled = ""
                    onclick = "cekByAdmin(0,"+ row.id +")"
                  }else{
                    isDisabled = "disabled"
                  }
                  btnId = "btnCekDraft"
                }else if (row.status == 'SAVED') {
                  btnClass = "btn-warning"
                  title = "Draft"
                  btnId = "btnDraft"
                  if (row.issuance == '{{Auth::User()->nik}}') {
                    status = '"saved"'
                    value = status
                    onclick = "unfinishedDraft(0,"+ row.id +","+ status +")"
                  }else{
                    isDisabled = "disabled"
                  } 
                }
                else if (row.status == 'REJECT') {
                  title = "Revision"
                  btnClass = "btn-warning"
                  // btnId = "btnDraft"
                  if (row.issuance == '{{Auth::User()->nik}}') {
                    status = '"reject"'
                    value = status
                    onclick = "unfinishedDraft(0,"+ row.id +","+ status +")"
                  }else{
                    isDisabled = "disabled"
                  } 
                }
                else if(row.status == 'UNAPPROVED'){
                  title = "Revision"
                  btnClass = "btn-warning"
                  if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}" || "{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Manager")->exists()}}") {
                    status = '"revision"'
                    value = status
                    onclick = "unfinishedDraft(0,"+ row.id +","+ status +")"
                  }else{
                    isDisabled = "disabled"
                  }
                }else{
                  title = "Detail"
                  btnClass = "btn-primary"
                  btnId = "btnDetail"
                  onclick = "{{url('admin/detail/draftPR')}}/"+row.id
                } 

                if (row.issuance == '{{Auth::User()->nik}}') {
                  if (row.status == 'CANCEL') {
                    isDisabledCancel = 'disabled'
                  }else{
                    isDisabledCancel = ''
                  }
                }else{
                  if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}" || "{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Manager")->exists()}}") {
                    isDisabledCancel = ''
                  }else{
                    isDisabledCancel = 'disabled'
                  }
                }

                if (title == 'Detail') {
                  return "<td><a href="+ onclick +" class='btn btn-sm "+ btnClass +" btnCekDraftDusk_"+row.id+"' data-value='"+row.id+"' "+isDisabled+" id='"+ btnId +"'>"+ title +"</a>" + " " + "<button class='btn btn-sm btn-danger' "+ isDisabledCancel +" onclick='btnCancel("+ row.id +")' value='"+ value +"'>Cancel</button></td>"
                }else {
                  return "<td><a onclick='"+ onclick +"' "+isDisabled+" class='btn btn-sm "+ btnClass +" btnCekDraftDusk_"+row.id+"' data-value='"+row.id+"' id='"+ btnId +"'>"+ title +"</a>" + " " + "<button "+isDisabled+" class='btn btn-sm btn-danger' "+ isDisabledCancel +" onclick='btnCancel("+ row.id +")' value='"+ value +"'>Cancel</button></td>"
                }
                                    
              },
              className:'text-center'
            },//action
            {
              "data":"status_numerical",
              "visible":false,
              "targets":[5]
            },
            {
              "data":"created_at",
              "visible":false,
              "targets":[1],
            },
          ],
          drawCallback: function(settings) {
            if (accesable.includes("btnCekDraft")) {
              $(".btnCekDraft").prop("disabled",false)
            }
          },
          "pageLength":100,
          lengthChange:false,
          // autoWidth:true,
          scrollX:        true,
          scrollCollapse: true,
          // paging:         false,
          fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
          },
          processing:true,
          'language': {
              'loadingRecords': '&nbsp;',
              'processing': 'Loading...'
          },
          initComplete: function () {
            InitiateFilterParam()
            $("#inputRangeDate").prop('disabled',false)
            $.each($("#selectShowColumnTicket li input"),function(index,item){
              var column = $("#draftPr").DataTable().column(index)
              // column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
              $(item).prop('checked', column.visible())
            })
          }
      })

      if ("{{Auth::User()->id_territory}}" == 'TERRITORY 4') {
        $("#addDraftPr").show()
      }
    })

    function changeColumnTable(data){
      var column = $("#draftPr").DataTable().column($(data).attr("data-column"))
      column.visible( ! column.visible() );
    }

    function InitiateFilterParam(arrStatusBack,arrTypeBack){
      Pace.restart();
      Pace.track(function() {
        // var tempType = 'type_of_letter[]=', tempStatus = 'status[]=', tempUser = 'user[]=', tempStartDate = 'startDate=', tempEndDate = 'endDate=', tempAnything = 'search='

        // var temp = '?' + tempType + '&' + tempStatus + '&' + tempUser + '&' + tempStartDate + '&' + tempEndDate + '&' + tempAnything
        
        // $.ajax({
        //   url:"{{url('/admin/getFilterDraft')}}" + temp,
        //   type:"GET",
        //   success:function(result){
        //     var arrStatus = result.dataStatus;
        //     var selectOptionStatus = [];

        //     var selectOptionStatus = [
        //       {
        //         text:"Grouped Status", 
        //         children:[
        //           {
        //             id:"NA",
        //             text:"Need Attention",
        //           },
        //           {
        //             id:"OG",
        //             text:"On Going",
        //           },
        //           {
        //             id:"DO",
        //             text:"Done",
        //           }
        //         ]
        //       },{
        //         text:"All Status", 
        //         children:arrStatus
        //       }
        //     ]

        //     if (arrStatusBack == undefined) {
        //       $("#inputFilterStatus").select2({
        //         placeholder: " Select Status",
        //         allowClear: true,
        //         multiple:true,
        //         data:selectOptionStatus,
        //       })
        //     }else{
        //       $("#inputFilterStatus").select2({
        //         placeholder: " Select Status",
        //         allowClear: true,
        //         multiple:true,
        //         data:selectOptionStatus,
        //       }).val(arrStatusBack).change()
        //     }
            

        //     // $("#inputFilterUser").select2().val("");
        //     var arrUser = result.dataUser
        //     $("#inputFilterUser").select2({
        //       placeholder: " Select User",
        //       allowClear: true,
        //       multiple:true,
        //       data:arrUser,
        //     })

        //     if (arrTypeBack == undefined) {
        //       $("#inputFilterTypePr").select2({
        //         placeholder: "Select a Type",
        //         allowClear: true,
        //         data:result.data_type_letter,
        //         multiple:true
        //       })
        //     }else{
        //       $("#inputFilterTypePr").select2({
        //         placeholder: "Select a Type",
        //         allowClear: true,
        //         data:result.data_type_letter,
        //         multiple:true
        //       }).val(arrTypeBack).change()
        //     }
        //   }
        // })
        $.ajax({
          url:"{{url('/admin/getDropdownFilterPr')}}",
          type:"GET",
          success:function(result){
            var arrStatus = result.dataStatus;
            // var selectOptionStatus = [];

            // var selectOptionStatus = [
            //   {
            //     text:"Grouped Status", 
            //     children:[
            //       {
            //         id:"NA",
            //         text:"Need Attention",
            //       },
            //       {
            //         id:"OG",
            //         text:"On Going",
            //       },
            //       {
            //         id:"DO",
            //         text:"Done",
            //       }
            //     ]
            //   },{
            //     text:"All Status", 
            //     children:arrStatus
            //   }
            // ]

            if (arrStatusBack == undefined) {
              $("#inputFilterStatus").select2({
                placeholder: " Select Status",
                // allowClear: true,
                multiple:true,
                data:arrStatus,
                closeOnSelect:true,
              })
            }else{
              $("#inputFilterStatus").select2({
                placeholder: " Select Status",
                // allowClear: true,
                multiple:true,
                data:arrStatus,
                closeOnSelect:true,
              }).val(arrStatusBack).change()
            }

            // $("#inputFilterUser").select2().val("");
            var arrUser = result.dataUser
            $("#inputFilterUser").select2({
              placeholder: " Select User",
              // allowClear: true,
              multiple:true,
              data:arrUser,
              closeOnSelect:true,
            })

            if (arrTypeBack == undefined) {
              $("#inputFilterTypePr").select2({
                placeholder: "Select a Type",
                // allowClear: true,
                data:result.data_type_letter,
                multiple:true,
                closeOnSelect:true,
              })
            }else{
              $("#inputFilterTypePr").select2({
                placeholder: "Select a Type",
                // allowClear: true,
                data:result.data_type_letter,
                multiple:true,
                closeOnSelect:true,
              }).val(arrTypeBack).change()
            }
          }
        })
      })
    }  

    function showFilterData(temp,arrStatusBack,arrTypeBack){
      Pace.restart();
      Pace.track(function() {
        // var table = $("#draftPr").DataTable()
        // table.destroy()
        $("#draftPr").DataTable().ajax.url("{{url('/admin/getFilterDraft')}}" + temp).load()
        InitiateFilterParam(arrStatusBack,arrTypeBack)
        // $.ajax({
        //   url:"{{url('/admin/getFilterDraft')}}" + temp,
        //   type:"GET",
        //   success:function(result){
        //     var parameterStatus = new URLSearchParams(temp);
        //     if (parameterStatus.getAll('status[]')[0] == "") {
        //       $("#inputFilterStatus").empty();

        //       var arrGrouped = []
        //       arrGrouped.push({
        //         id:"NA",
        //         text:"Need Attention",
        //       },
        //       {
        //         id:"OG",
        //         text:"On Going",
        //       },
        //       {
        //         id:"DO",
        //         text:"Done",
        //       })

        //       var arrStatus = result.dataStatus;
        //       var selectOptionStatus = [];

        //       var selectOptionStatus = [
        //         {
        //           text:"Grouped Status", 
        //           children:arrGrouped
        //         },{
        //           text:"All Status", 
        //           children:arrStatus
        //         }
        //       ]

        //       $("#inputFilterStatus").select2({
        //         placeholder: " Select Status",
        //         // allowClear: true,
        //         multiple:true,
        //         data:selectOptionStatus,
        //       })
        //     }

        //     if (parameterStatus.getAll('user[]')[0] == "") {
        //       $("#inputFilterUser").empty();

        //       $("#inputFilterUser").select2({
        //         placeholder: " Select User",
        //         // allowClear: true,
        //         multiple:true,
        //         data:result.dataUser,
        //       })
        //     }

        //     if (parameterStatus.getAll('type_of_letter[]')[0] == "") {
        //       // $("#inputFilterTypePr").empty();

        //       $("#inputFilterTypePr").select2({
        //         placeholder: " Select a Type",
        //         // allowClear: true,
        //         multiple:true,
        //         data:result.data_type_letter,
        //       })
        //     }
            
        //   }
        // })
      })
    }  

    function sortingByDashboard(value){
      
      var tempType = 'type_of_letter[]=', tempStatus = 'status[]=', tempUser = 'user[]=', tempStartDate = 'startDate=', tempEndDate = 'endDate=', tempAnything = 'search='

      if (tempStatus == 'status[]=') {
        tempStatus = tempStatus + value
      }else{
        tempStatus = tempStatus + 'status[]=' + value
      }

      var temp = '?' + tempType + '&' + tempStatus + '&' + tempUser + '&' + tempStartDate + '&' + tempEndDate + '&' + tempAnything
      
      showFilterData(temp)
    }

    function searchCustom(startDate,endDate){
      var tempType = 'type_of_letter[]=', tempStatus = 'status[]=', tempUser = 'user[]=', tempStartDate = 'startDate=', tempEndDate = 'endDate=', tempAnything = 'search='

      $.each($('#inputFilterStatus').val(),function(key,value){
        if (tempStatus == 'status[]=') {
          tempStatus = tempStatus + value
        }else{
          tempStatus = tempStatus + '&status[]=' + value
        }
      })

      $.each($('#inputFilterUser').val(),function(key,value){
        if (tempUser == 'user[]=') {
          tempUser = tempUser + value
        }else{
          tempUser = tempUser + '&user[]=' + value
        }
      })

      $.each($('#inputFilterTypePr').val(),function(key,value){
        if (tempType == 'type_of_letter[]=') {
          tempType = tempType + value
        }else{
          tempType = tempType + '&type_of_letter[]=' + value
        }
      })

      tempAnything = tempAnything + $("#inputSearchAnything").val()

      if (startDate != undefined) {
        tempStartDate = tempStartDate + startDate
      }

      if (endDate != undefined) {
        tempEndDate = tempEndDate + endDate
      }

      var temp = '?' + tempType + '&' + tempStatus + '&' + tempUser + '&' + tempStartDate + '&' + tempEndDate + '&' + tempAnything
      showFilterData(temp)
      DashboardCounterFilter(temp)

      // if (tempStatus || tempType) {
      //   localStorage.setItem('isTemp',true)
      //   // localStorage.setItem("arrFilterBack",true)
      // }else{
      //   localStorage.setItem('isTemp',false)
      //   // localStorage.removeItem("arrFilterBack",true)
      // }

      return localStorage.setItem("arrFilter", temp) 
    }

    // window.onload = function() {
    //   console.log(localStorage.getItem("arrFilter"))
    //   localStorage.setItem("arrFilterBack",localStorage.getItem("arrFilter"))

    //   var arrStatus = [], arrType = [], arr = []
    //   if (localStorage.getItem("arrFilter")) {
    //     // 
    //     arr = localStorage.getItem("arrFilter").split("?")[1].split("&")

    //     if (localStorage.getItem("arrFilter").split("?")[1].split("&")[0].split('=')[1] != '') {
    //         $.each(arr,function(item,value){
    //         if(value.indexOf("type") != -1){
    //           // showFilterData(localStorage.getItem("arrFilter"),arrStatus,arrType)
    //           arrType.push(value.split("=")[1])
    //         }
    //       })
    //     }

    //     if(localStorage.getItem("arrFilter").split("?")[1].split("&")[1].split('=')[1] != ''){
    //         $.each(arr,function(item,value){
    //         if(value.indexOf("status") != -1){
    //           // showFilterData(localStorage.getItem("arrFilter"),arrStatus,arrType)
    //           arrStatus.push(value.split("=")[1])
    //         }
    //       })
    //     }

    //   }

      // if (localStorage.getItem('isTemp') === 'true') {
      //   localStorage.setItem("arrFilterBack",localStorage.getItem("arrFilter"))
      //   // var returnArray = searchCustom()
      //   // localStorage.setItem("arrFilter", returnArray);
      // }else{
      //   localStorage.removeItem("arrFilterBack")
      // }

      // console.log(localStorage.getItem("arrFilterBack")+"testt")
      // if(localStorage.getItem("arrFilterBack") != undefined && localStorage.getItem("arrFilterBack") != null && localStorage.getItem("arrFilterBack") != ''){
      //   // window.history.pushState(null,null,location.protocol + '//' + location.host + location.pathname + localStorage.getItem("arrFilterBack"))
      //   // DashboardCounterFilter(localStorage.getItem("arrFilterBack"))
      //   var arr = localStorage.getItem("arrFilterBack").split("?")[1].split("&")
      //   var arrStatus = [], arrType = []

      //   $.each(arr,function(item,value){
      //     if(value.indexOf("status") != -1){
      //         arrStatus.push(value.split("=")[1])
      //     }

      //     if(value.indexOf("type") != -1){
      //         arrType.push(value.split("=")[1])
      //     }
      //   })
      //   InitiateFilterParam(arrStatus,arrType)
      // }else{
      //   InitiateFilterParam()
      // }     
    // }

    $('#clearFilterTable').click(function(){
      localStorage.setItem('isTemp',false)
      $('#inputSearchAnything').val('')
      $("#inputFilterTypePr").empty();
      $("#inputFilterStatus").empty();
      $("#inputFilterUser").empty();
      DashboardCounter()
      localStorage.removeItem("arrFilterBack");
      InitiateFilterParam()
      $("#inputRangeDate").val("")
      $('#inputRangeDate').html("")
      $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span> Date range picker <i class="fa fa-caret-down"></i></span>');
      $('#draftPr').DataTable().ajax.url("{{url('/admin/getDraftPr')}}").load();
    });

    $('#reloadTable').click(function(){
      localStorage.setItem('isTemp',false)  
      $('#inputSearchAnything').val('')
      $("#inputFilterTypePr").empty();
      $("#inputFilterStatus").empty();
      $("#inputFilterUser").empty();
      DashboardCounter()
      localStorage.removeItem("arrFilterBack");
      InitiateFilterParam()
      $("#inputRangeDate").val("")
      $('#inputRangeDate').html("")
      $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span> Date range picker <i class="fa fa-caret-down"></i></span>');
      $('#draftPr').DataTable().ajax.url("{{url('/admin/getDraftPr')}}").load();
    });

    function changeNumberEntries(number){
      $("#btnShowEntryTicket").html('Show ' + number + ' <span class="fa fa-caret-down"></span>')
      $("#draftPr").DataTable().page.len( number ).draw();
    }

    $('#inputRangeDate').daterangepicker({
      ranges: {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate: moment()
    },function (start, end) {
      $('#inputRangeDate').html("")
      $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span>' + start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY') + '</span>&nbsp<i class="fa fa-caret-down"></i>');

      var startDay = start.format('YYYY-MM-DD');
      var endDay = end.format('YYYY-MM-DD');

      $("#startDateFilter").val(startDay)
      $("#endDateFilter").val(endDay)

      startDate = start.format('D MMMM YYYY');
      endDate = end.format('D MMMM YYYY');

      if (startDay != undefined && endDay != undefined) {
        searchCustom(startDay,endDay)
      }
    });

    localStorage.setItem('firstLaunch', true);
    localStorage.setItem('isStoreSupplier',false);

    //ini buat alert diatas
    function reasonReject(item,display,nameClass){
      $(".divReasonRejectRevision").remove()

      var textTitle = ""
      var className = ""

      if (nameClass == 'tabGroup') {
        textTitle = "Note Reject PR"
        className = "tabGroup"
      }else{
        textTitle = "Alert Error!"
        className = nameClass
      }
      
      var append = ""

      append = append + '<div class="callout callout-danger divReasonRejectRevision" style="display:none">'
        append = append + '<h4><i class="icon fa fa-cross"></i>'+ textTitle +'</h4>'
        append = append + '<p class="reason_reject_revision">'+item.replaceAll("\n","<br>")+'</p>'
      append = append + '</div>'

      $("." + nameClass).prepend(append)

      if (display == "block") {
        $(".divReasonRejectRevision").show()
      }
    }

    function unfinishedDraft(n,id_draft,status){
      localStorage.setItem('firstLaunch', false);
      localStorage.setItem('no_pr',id_draft)
      localStorage.setItem('status_unfinished',status)

      if (status == 'revision') {
        url = "{{url('/admin/getDetailPr')}}"
      }else{
        url = "{{url('/admin/getPreviewPr')}}"
      }
      $.ajax({
        type: "GET",
        url: url,
        data: {
          no_pr:id_draft,
        },
        success: function(result) {
          if (status == 'revision') {
            localStorage.setItem("id_compare_pr",result.id_compare_pr)
          }else{
            localStorage.setItem('no_pr',id_draft)
          }

          var x = document.getElementsByClassName("tab-add");
          x[n].style.display = "inline";
          if (n == (x.length - 1)) {
            $(".modal-dialog").addClass('modal-lg')
            $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')        
            $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
            $(".modal-title").text('')
            document.getElementById("prevBtnAdd").style.display = "inline";
            $("#headerPreviewFinal").empty()
            document.getElementById("nextBtnAdd").innerHTML = "Create";
            $("#nextBtnAdd").attr('onclick','createPR("'+ status +'")');  

            if ($("#selectType").val() == 'IPR') {
              PRType = '<b>Internal Purchase Request</b>'
            }else{
              PRType = '<b>External Purchase Request</b>'
            }

            PRMethode = $("#selectMethode").find(":selected").text()
            leadRegister = $("#selectLeadId").val()
            quoteNumber = $("#selectQuoteNumber").val() 
            
            var appendHeader = ""
            appendHeader = appendHeader + '<div class="row">'
            appendHeader = appendHeader + '    <div class="col-md-6">'
            appendHeader = appendHeader + '        <div class="">To: '+ result.pr.to +'</div>'
            appendHeader = appendHeader + '        <div class="">Email: ' + result.pr.email + '</div>'
            appendHeader = appendHeader + '        <div class="">Phone: ' + result.pr.phone + '</div>'
            appendHeader = appendHeader + '        <div class="">Fax: '+ result.pr.fax +' </div>'
            appendHeader = appendHeader + '        <div class="">Attention: '+ result.pr.attention +'</div>'
            appendHeader = appendHeader + '        <div class="">From: '+ result.pr.name +'</div>'
            appendHeader = appendHeader + '        <div class="">Subject: '+ result.pr.title +'</div>'
            appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ result.pr.address +'</div>'

            appendHeader = appendHeader + '    </div>'
            if (window.matchMedia("(max-width: 768px)").matches)
            {
                appendHeader = appendHeader + '    <div class="col-md-6">'
                // The viewport is less than 768 pixels wide
                
            } else {
                appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                // The viewport is at least 768 pixels wide
                
            }
            appendHeader = appendHeader + '        <div>'+ PRType +'</div>'
            appendHeader = appendHeader + '        <div><b>Request Methode</b></div>'
            appendHeader = appendHeader + '        <div>'+ result.pr.request_method +'</div>'
            appendHeader = appendHeader + '        <div>'+ moment(result.pr.created_at).format('DD MMMM') +'</div>'
            if (PRType == 'EPR') {
              appendHeader = appendHeader + '        <div><b>Lead Register</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.lead_id +'</div>'
              appendHeader = appendHeader + '        <div><b>Quote Number</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.quote_number +'</div>'
            }
            appendHeader = appendHeader + '    </div>'
            appendHeader = appendHeader + '</div>'

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
                append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name_product + "'>"
                append = append + '</td>'
                append = append + '<td width="40%">'
                  append = append + '<textarea readonly class="form-control" style="resize: none;height: 120px;font-size: 12px; important">' + item.description.replaceAll("<br>","\n") + '&#10;&#10;' + 'SN : ' + item.serial_number + '&#10;PN : ' + item.part_number +  '</textarea>'
                append = append + '</td>'
                append = append + '<td width="5%">'
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px; important">'
                append = append + '</td>'
                append = append + '<td width="5%">'
                  append = append + '<select readonly style="width:75px;font-size: 12px; important" class="form-control">'
                  append = append + '<option>' + item.unit.charAt(0).toUpperCase() + item.unit.slice(1) + '</option>'
                  append = append + '</select>'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size: 12px; important">'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly id="grandTotalPreview" class="form-control grandTotalPreview" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px; important">'
                append = append + '</td>'
              append = append + '</tr>'
            })

            $("#tbodyFinalPageProducts").append(append)

            $("#bottomPreviewFinal").empty()        
            appendBottom = ""
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<div class="form-group">'
            appendBottom = appendBottom + '  <div class="row">'
            appendBottom = appendBottom + '    <div class="col-md-12 col-xs-12">'
            appendBottom = appendBottom + '      <div class="pull-right">'
            appendBottom = appendBottom + '        <span style="display: inline;margin-right: 15px;">Total</span>'
            appendBottom = appendBottom + '        <input readonly="" type="text" style="width:250px;display: inline;" id="inputGrandTotalProduct_unfinishPreview" class="form-control inputGrandTotalProduct_unfinishPreview">'
            appendBottom = appendBottom + '      </div>'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'
            appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
            appendBottom = appendBottom + ' <div class="col-md-12 col-xs-12">'

            appendBottom = appendBottom + ' <div class="pull-right">'
              appendBottom = appendBottom + '  <span style="margin-right: 15px;">Vat <span class="title_tax"></span>'
            appendBottom = appendBottom + '  </span>'
            appendBottom = appendBottom + '  <div class="input-group" style="display: inline-flex;">'
            appendBottom = appendBottom + '   <input readonly="" type="text" class="form-control vat_tax" id="vat_tax_unfinishPreview" name=""vat_tax_unfinishPreview" style="width:250px;display:inline">'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'



            // appendBottom = appendBottom + '   <div class="pull-right">'
            //   appendBottom = appendBottom + '   <span>Vat <span class="title_tax"></span></span>'
            //   appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
            //     // appendBottom = appendBottom + '   <span style="margin-right: 33px;" class="input-group-btn pull-right">'
            //     //   if(btnVatStatus) {
            //     //     appendBottom = appendBottom + '            <button type="button" class="btn btn-flat btn-default" disabled="true" id="btn-vat2">✓</button>'
            //     //   }
            //     //   else {
            //     //     appendBottom = appendBottom + '            <button type="button" class="btn btn-flat btn-danger" disabled="true" id="btn-vat2">✖</button>'
            //     //   }
            //     // appendBottom = appendBottom + ' </span>'
            //   appendBottom = appendBottom + '   <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_unfinishPreview" name="vat_tax_unfinishPreview" style="width:250px;">'
            //   appendBottom = appendBottom + ' </div>'
            appendBottom = appendBottom + ' </div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '  <div class="row" style="margin-top: 10px;">'
            appendBottom = appendBottom + '    <div class="col-md-12 col-xs-12">'
            appendBottom = appendBottom + '      <div class="pull-right">'
            appendBottom = appendBottom + '        <span style="display: inline;margin-right: 15px;">Grand Price</span>'
            appendBottom = appendBottom + '        <input readonly type="text" style="width:250px;display: inline;" class="form-control inputFinalPageGrandPrice_unfinishPreview" id="inputFinalPageGrandPrice" name="inputFinalPageGrandPrice_unfinishPreview">'
            appendBottom = appendBottom + '      </div>'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
            appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span><b>Attached Files</b><span>'
            var pdf = "fa fa-fw fa-file-pdf-o"
            var image = "fa fa-fw fa-file-image-o"
            if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            if (result.pr.type_of_letter == 'IPR') {
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

            $("#bottomPreviewFinal").append(appendBottom)
                            

            $("#termPreview").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))

            var tempVat = 0
            var finalVat = 0
            var tempGrand = 0
            var finalGrand = 0
            var tempTotal = 0
            var sum = 0
            var valueVat = ""

            $('.grandTotalPreview').each(function() {
                var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
                sum += temp;
            });

            // if (result.pr.status_tax == 'True') {
            //   tempVat = formatter.format((parseFloat(sum) * 11) / 100)
            //   tempGrand = parseFloat(sum) +  parseFloat((parseFloat(sum) * 11) / 100)
            // }else{
            //   tempVat = tempVat
            //   tempGrand = parseFloat(sum)
            // }

            if (result.pr.status_tax == false) {
              valueVat = 'false'
            }else{
              valueVat = result.pr.status_tax
            }
            if (!isNaN(valueVat)) {
              tempVat = Math.round((parseFloat(sum) * parseFloat(valueVat)) / 100)

              finalVat = tempVat

              tempGrand = Math.round((parseFloat(sum) +  parseFloat((parseFloat(sum) * parseFloat(valueVat)) / 100)))

              finalGrand = tempGrand

              tempTotal = sum

              $('.title_tax').text(valueVat + '%')
            }else{
              tempVat = 0

              finalGrand = sum

              $('.title_tax').text("")
            }  

            $("#vat_tax_unfinishPreview").val(formatter.format(tempVat))
            $("#inputGrandTotalProduct_unfinishPreview").val(formatter.format(sum))
            $("#inputFinalPageGrandPrice").val(formatter.format(finalGrand))

            if (status == 'reject' || status == 'revision') {
              reasonReject(result.activity.reason,"block","tabGroup")
            }                               

            if (window.location.href.split("/")[5] != undefined) {
              if (window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'reject' || window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'revision') {
                reasonReject(result.activity.reason,"block","tabGroup")
              }
            }
            
          } else {
            if (n == 0) {
              //reinitiate
              $("#inputTo").val("")
              $("#selectType").val("")
              $("#inputEmail").val("")
              $("#inputPhone").val("")
              // $("#inputFax").val("")
              $("#inputAttention").val("")
              $("#inputSubject").val("")
              $("#inputAddress").val("")

              $.ajax({
                url:"{{url('/admin/getSupplier')}}",
                type:"GET",
                success:function(results){
                  $("#selectTo").select2({
                    data:results,
                    placeholder:"Select Supplier"
                  }).val(result.pr.to).trigger("change")
                }
              })   

              $("#otherTo").click(function(){
                $("#divInputTo").show("slow")
              })

              $(".close").click(function(){
                $("#divInputTo").hide("slow")
              }) 

              // $("#inputTo").val(result.pr.to)
              $("#selectType").val(result.pr.type_of_letter)
              $("#inputEmail").val(result.pr.email)
              $("#inputPhone").val(result.pr.phone)
              // $("#inputFax").val(result.pr.fax)
              $("#inputAttention").val(result.pr.attention)
              $("#inputSubject").val(result.pr.title)
              $("#inputAddress").val(result.pr.address)
              if (result.pr.request_method == 'Reimbursement') {
                $("#selectMethode").val('reimbursement')
              }else if (result.pr.request_method == 'Purchase Order') {
                $("#selectMethode").val('purchase_order')
              }else{
                $("#selectMethode").val('payment')
              }
              $("#selectCategory").val(result.pr.category)

              if (result.pr.isCommit == 'True') {
                $("#cbCommit").prop('checked',true)
              }

              const firstLaunch = localStorage.getItem('firstLaunch')
              document.getElementById("prevBtnAdd").style.display = "none";
              $(".modal-title").text('Information Supplier')
              $(".modal-dialog").removeClass('modal-lg')
 
              localStorage.setItem('no_pr',id_draft)
              if (status == 'reject') {
                if (result.verify.verify_type_of_letter == 'True'){
                  $("#selectType").prop("disabled",true)
                }
                if (result.verify.verify_category == 'True'){
                  $("#selectCategory").prop("disabled",true)
                }
                if (result.verify.verify_to == 'True'){
                  $("#inputTo").prop("disabled",true)
                }
                if (result.verify.verify_email == 'True'){
                  $("#inputEmail").prop("disabled",true)
                }
                if (result.verify.verify_phone == 'True'){
                  $("#inputPhone").prop("disabled",true)
                }
                if (result.verify.verify_attention == 'True'){
                  $("#inputAttention").prop("disabled",true)
                }
                if (result.verify.verify_title == 'True'){
                  $("#inputSubject").prop("disabled",true)
                }
                if (result.verify.verify_address == 'True'){
                  $("#inputAddress").prop("disabled",true)
                }
                if (result.verify.verify_request_method == 'True'){
                  $("#selectMethode").prop("disabled",true)
                }

                reasonReject(result.activity.reason,"block","tabGroup")

                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,"saved")')
              } else if (status == 'revision') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").html(result.activity.reason.replaceAll("\n","<br>"))

                reasonReject(result.activity.reason,"block","tabGroup")

                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,"saved")')
              } else {
                if (firstLaunch == 'true') {
                  $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
                }else{
                  $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,"saved")')
                } 
              }
            } else if (n == 1) {
              select2TypeProduct()

              $(".modal-title").text('Information Product')
              $(".modal-dialog").removeClass('modal-lg')  

              //button add initiate product show form-group
              $("#btnInitiateAddProduct").click(function(){
                $(".tabGroupInitiateAdd").hide()
                x[n].children[1].style.display = 'inline'
                $("#inputNameProduct").val('')
                $("#inputDescProduct").val('')
                $("#inputQtyProduct").val('')
                $("#selectTypeProduct").val('')
                $("#inputPriceProduct").val('')
                $("#inputSerialNumber").val('')
                $("#inputPartNumber").val('')
                $("#inputTotalPrice").val('')
              })

              if (status == 'admin') {
                $("#nextBtnAdd").attr('onclick','nextPrevAddAdmin(1,'+ id_draft +')')
              }else{
                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
                $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')     
              }
              if (localStorage.getItem('isEditProduct') == 'true') {
                document.getElementById("prevBtnAdd").style.display = "none";
              } else {
                document.getElementById("prevBtnAdd").style.display = "inline";
              }  
              localStorage.setItem('no_pr',id_draft)
            } else if(n == 2){
              $(".modal-title").text('')
              $("#nextBtnAdd").removeAttr('onclick')
              $(".modal-dialog").addClass('modal-lg')
              localStorage.setItem('firstLaunch',false)

              

              addTable(0,result.pr.status_tax)
              if (localStorage.getItem('firstLaunch') == 'false') {
                $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-2,"saved")')
                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
              }else{
                $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')
              } 
              document.getElementById("prevBtnAdd").style.display = "inline";
              localStorage.setItem('no_pr',id_draft)
            } else if (n == 3) {
              localStorage.setItem('status_draft_pr',result.pr.status_draft_pr)
              $(".modal-dialog").removeClass('modal-lg')
              if ($("#selectType").val() == 'EPR') {
                $(".modal-title").text('External Purchase Request')
                $("#formForPrExternal").show()
                $("#formForPrInternal").hide()

                fillInput('spk')
                fillInput('sbe')
                fillInput('quoteSupplier')

                const fileSPK   = document.querySelector('input[type="file"][name="inputSPK"]');

                const fileSBE   = document.querySelector('input[type="file"][name="inputSBE"]');

                const fileQuote = document.querySelector('input[type="file"][name="inputQuoteSupplier"]');

                // Create a new File object
                if (status == 'revision') {
                  url = "{{url('/admin/getDetailPr')}}"                  
                }else{
                  url = "{{url('/admin/getPreviewPr')}}"
                }
                $.ajax({
                  type: "GET",
                  url: url,
                  data: {
                    no_pr:localStorage.getItem('no_pr'),
                  },
                  success:function(result){
                    var selectedPid     = result.pr.pid
                    var selectedLeadId  = result.pr.lead_id
                    // $("#selectLeadId").val(result.pr.lead_id).trigger("change")
                    // $("#selectQuoteNum").val(result.pr.quote_number).trigger("change")

                    $.ajax({
                      url: "{{url('/admin/getPidAll')}}",
                      type: "GET",
                      success: function(result) {
                        if (selectedPid) {
                          $("#selectPid").val(selectedPid).trigger("change")
                        }

                        if (selectedLeadId) {
                          $("#selectPid").val(selectedPid).trigger("change")
                        }

                        $("#selectPid").select2({
                            data: result.data,
                            placeholder: "Select Pid",
                            dropdownParent: $('#ModalDraftPr')
                        }).on('change', function() {
                          var data = $("#selectPid option:selected").text();
                          $("#selectLeadId").empty()
                          $.ajax({
                            url: "{{url('/admin/getLeadByPid')}}",
                            type: "GET",
                            data: {
                              pid:data
                            },
                            success: function(result) {
                              $("#selectLeadId").select2({
                                  data: result.data,
                                  placeholder: "Select Lead Register",
                                  dropdownParent: $('#ModalDraftPr')
                              })

                              var lead_id = $("#selectLeadId option:selected").text();
                              $("#selectQuoteNumber").empty()

                              $.ajax({
                                url: "{{url('/admin/getQuote')}}",
                                type: "GET",
                                data:{
                                  lead_id:lead_id
                                },
                                success: function(result) {
                                  $("#selectQuoteNumber").select2({
                                      data: result.data,
                                      placeholder: "Select Quote Number",
                                      dropdownParent: $('#ModalDraftPr')
                                  })
                                }
                              }) 

                              if (result.linkSbe.length > 0) {
                                const myFileSbe = new File(['{{asset("/")}}'+result.linkSbe[0].document_location], '/'+result.linkSbe[0].document_location,{
                                  type: 'text/plain',
                                  lastModified: new Date(),
                                });
                                // Now let's create a DataTransfer to get a FileList
                                const dataTransferSbe = new DataTransfer();
                                dataTransferSbe.items.add(myFileSbe);
                                fileSBE.files = dataTransferSbe.files;

                                $("#inputSBE").attr("disabled",true).css("cursor","not-allowed")
                                $("#inputSBE").closest(".form-group").find("#span_link_drive_sbe").show()
                                $("#link_sbe").attr("href",result.linkSbe[0].link_drive)
                              }
                            }
                          }) 

                         
                        })

                        if (status == 'reject' || status == 'revision' || status == 'saved') {
                          $("#selectPid").val(selectedPid).trigger("change")
                        }
                      }
                    })

                    if (status == 'reject') {
                      if (result.verify.verify_pid == 'True'){
                        $("#selectPid").prop("disabled",true)
                      }
                      if (result.verify.verify_lead_id == 'True'){
                        $("#selectLeadId").prop("disabled",true)
                      }
                      if (result.verify.verify_quote_number == 'True'){
                        $("#selectQuoteNumber").prop("disabled",true)
                      }

                      reasonReject(result.activity.reason,"block","tabGroup")

                    } else if (status == 'revision') {
                      reasonReject(result.activity.reason,"block","tabGroup")
                    } 

                    if (result.dokumen.length > 0) {
                      let formData = new FormData();

                      if (result.dokumen[0] !== undefined) {
                        const myFileQuote = new File(['{{asset("/")}}"'+ result.dokumen[0].dokumen_location +'"'], '/'+ result.dokumen[0].dokumen_location,{
                            type: 'text/plain',
                            lastModified: new Date(),
                        });

                        // Now let's create a DataTransfer to get a FileList
                        const dataTransferQuote = new DataTransfer();
                        dataTransferQuote.items.add(myFileQuote);
                        fileQuote.files = dataTransferQuote.files;

                        if (result.dokumen[0].link_drive != null) {
                          $("#span_link_drive_quoteSup").show()
                          $("#link_quoteSup").attr("href",result.dokumen[0].link_drive) 
                        }
                      }

                      if (result.dokumen[1] !== undefined) {
                        const myFileSpk = new File(['{{asset("/")}}"'+ result.dokumen[1].dokumen_location +'"'], '/'+ result.dokumen[1].dokumen_location,{
                            type: 'text/plain',
                            lastModified: new Date(),
                        });

                        // Now let's create a DataTransfer to get a FileList
                        const dataTransferSpk = new DataTransfer();
                        dataTransferSpk.items.add(myFileSpk);
                        fileSPK.files = dataTransferSpk.files;

                        if (result.dokumen[1].link_drive != null) {
                          $("#span_link_drive_spk").show()
                          $("#link_spk").attr("href",result.dokumen[1].link_drive) 
                        }
                      }

                      if (result.dokumen[2] !== undefined) {
                        const myFileSbe = new File(['{{asset("/")}}"'+ result.dokumen[2].dokumen_location +'"'], '/'+ result.dokumen[2].dokumen_location,{
                          type: 'text/plain',
                          lastModified: new Date(),
                        });
                        // Now let's create a DataTransfer to get a FileList
                        const dataTransferSbe = new DataTransfer();
                        dataTransferSbe.items.add(myFileSbe);
                        fileSBE.files = dataTransferSbe.files;

                        if (result.dokumen[2].link_drive != null) {
                          $("#span_link_drive_sbe").show()
                          $("#link_sbe").attr("href",result.dokumen[2].link_drive)
                        }
                      }                        

                      $("#tableDocPendukung_epr").empty()

                      if (result.dokumen.length > 3) {
                        $("#titleDoc_epr").css("display",'block')
                      }
                      appendDocPendukung = ""
                      $.each(result.dokumen,function(value,item){
                        if (value != 0 &&  value != 1 && value != 2) {
                          appendDocPendukung = appendDocPendukung + '<tr style="height:10px" class="trDocPendukung">'
                            appendDocPendukung = appendDocPendukung + "<td>"
                              appendDocPendukung = appendDocPendukung + '<button type="button" value="'+ item.id_dokumen +'" class="fa fa-times btnRemoveDocPendukung" data-value="remove_'+ value +'" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
                                  appendDocPendukung = appendDocPendukung + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;display: inline-block;width:200px;background-color:darkgrey;cursor:not-allowed">'
                                    appendDocPendukung = appendDocPendukung + "<input style='font-family: inherit;width: 90px;color:grey' type='file' name='inputDocPendukung' id='inputDocPendukung' data-value='"+ item.id_dokumen +"' class='inputDocPendukung_"+value+"' disabled>"
                                   appendDocPendukung = appendDocPendukung + '</div>'
                                   appendDocPendukung = appendDocPendukung + "<br><a style='margin-left: 26px;font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif' href='"+ item.link_drive +"' target='_blank'><i class='fa fa-link'></i>&nbspLink drive</a>"
                            appendDocPendukung = appendDocPendukung + "</td>"
                            appendDocPendukung = appendDocPendukung + "<td>"
                              appendDocPendukung = appendDocPendukung + '<input style="width:250px;margin-left:20px" class="form-control inputNameDocPendukung_'+value+'" value="'+ item.dokumen_name +'" name="inputNameDocPendukung" id="inputNameDocPendukung" placeholder="ex : faktur pajak"><br>'
                            appendDocPendukung = appendDocPendukung + "</td>"
                          appendDocPendukung = appendDocPendukung + "</tr>"
                        }   
                      })
                      $("#tableDocPendukung_epr").append(appendDocPendukung)

                      $('#inputNameDocPendukung').keydown(function(){
                        
                        if ($('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung')).data('value').val() == "") {
                          $("#btnAddDocPendukung_epr").prop("disabled",true)
                          $("#btnAddDocPendukung_ipr").prop("disabled",true)
                        }else{
                          $("#btnAddDocPendukung_epr").prop("disabled",false)
                          $("#btnAddDocPendukung_ipr").prop("disabled",false)
                        }
                      })

                      $.each(result.dokumen,function(value,item){
                        if (value != 0 &&  value != 1 && value != 2) {
                          const filedocpendukung = document.querySelector('.inputDocPendukung_'+value);

                          const FilePendukung = new File(['{{asset("/")}}"'+ item.dokumen_location +'"'], '/'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1],{
                              type: 'text/plain',
                              lastModified: new Date(),
                          });

                          // Now let's create a DataTransfer to get a FileList
                          const dataTransfer = new DataTransfer();
                          dataTransfer.items.add(FilePendukung);
                          filedocpendukung.files = dataTransfer.files;

                          $('.inputNameDocPendukung_'+value).val(item.dokumen_name)
                        }

                        $(".btnRemoveDocPendukung[data-value='remove_" + value + "']").click(function(){
                          Swal.fire({
                            title: 'Are you sure?',
                            text: "Deleting document",
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
                                  url:"{{url('/admin/deleteDokumen/')}}",
                                  data:{
                                    _token:"{{ csrf_token() }}",
                                    id:this.value
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
                                  success: function(data)
                                  {
                                    Swal.showLoading()
                                    Swal.fire(
                                        'Document has been deleted!',
                                        'You can adding another document files',
                                        'success'
                                    ).then((result) => {
                                      if (result.value) {
                                        $(".btnRemoveDocPendukung[data-value='remove_" + value + "']").closest("tr").remove();
                                      }
                                    })
                                  }
                                })
                              }
                          })
                          if($('#tableDocPendukung_epr tr').length == 0){
                            $("#titleDoc_epr").hide()
                          }
                        })
                      })
                      
                    }
                  }
                })
              }else{
                $(".modal-title").text('Internal Purchase Request')
                $("#formForPrInternal").show()
                $("#formForPrExternal").hide()  

                fillInput('penawaranHarga')

                // Get a reference to our file input
                const fileInput = document.querySelector('input[type="file"][name="inputPenawaranHarga"]');
                if (status == 'revision') {
                  url = "{{url('/admin/getDetailPr')}}"
                }else{
                  url = "{{url('/admin/getPreviewPr')}}"
                }
                $.ajax({
                  type: "GET",
                  url: url,
                  data: {
                    no_pr:localStorage.getItem("no_pr"),
                  },
                  success:function(result){
                    if (status == 'reject') {
                      reasonReject(result.activity.reason,"block","tabGroup")

                    }else if (status == 'revision') {
                      reasonReject(result.activity.reason,"block","tabGroup")
                    }

                    var pdf = "fa fa-fw fa-file-pdf-o"
                    var image = "fa fa-fw fa-file-image-o"

                    if(result.dokumen.length > 0){
                      if (result.dokumen.length > 1) {
                        $("#titleDoc_ipr").show()
                      }
                      const myFile = new File(['{{asset("/")}}"'+ result.dokumen[0].dokumen_location +'"'], '/'+ result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1],{
                          type: 'text/plain',
                          lastModified: new Date(),
                      });

                      $("#span_link_drive_penawaranHarga").show()
                      $("#link_penawaran_harga").attr("href",result.dokumen[0].link_drive)                
                      // Now let's create a DataTransfer to get a FileList
                      const dataTransfer = new DataTransfer();
                      dataTransfer.items.add(myFile);
                      // dataTransfer.items.add(myFile);
                      fileInput.files = dataTransfer.files;
                    }    


                    $("#tableDocPendukung_ipr").empty()

                    appendDocPendukung = ""
                    $.each(result.dokumen,function(value,item){
                      if (value != 0) {
                        appendDocPendukung = appendDocPendukung + '<tr style="height:10px" class="trDocPendukung">'
                          appendDocPendukung = appendDocPendukung + "<td>"
                            appendDocPendukung = appendDocPendukung + '<button type="button" value="'+ item.id_dokumen +'" class="fa fa-times btnRemoveDocPendukung" data-value="remove_'+ value +'" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
                                appendDocPendukung = appendDocPendukung + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;display: inline-block;width:200px;background-color:darkgrey;cursor:not-allowed">'
                                  appendDocPendukung = appendDocPendukung + "<input style='font-family: inherit;width: 90px;color:grey' type='file' name='inputDocPendukung' id='inputDocPendukung' data-value='"+ item.id_dokumen +"' class='inputDocPendukung_"+value+"' disabled>"
                                 appendDocPendukung = appendDocPendukung + '</div>'
                                 appendDocPendukung = appendDocPendukung + "<br><a style='margin-left: 26px;font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif' href='"+ item.link_drive +"' target='_blank'><i class='fa fa-link'></i>&nbspLink drive</a>"
                          appendDocPendukung = appendDocPendukung + "</td>"
                          appendDocPendukung = appendDocPendukung + "<td>"
                            appendDocPendukung = appendDocPendukung + '<input style="width:250px;margin-left:20px" class="form-control inputNameDocPendukung_'+value+'" name="inputNameDocPendukung" data-value='+ value +' id="inputNameDocPendukung" placeholder="ex : faktur pajak"><br>'
                          appendDocPendukung = appendDocPendukung + "</td>"
                        appendDocPendukung = appendDocPendukung + "</tr>"
                      }   
                    })
                    $("#tableDocPendukung_ipr").append(appendDocPendukung)                            

                    $.each(result.dokumen,function(value,item){
                      if (value != 0) {
                        const filedocpendukung = document.querySelector('.inputDocPendukung_'+value);

                        const FilePendukung = new File(['{{asset("/")}}"'+ item.dokumen_location +'"'], '/'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1],{
                            type: 'text/plain',
                            lastModified: new Date(),
                        });

                        // Now let's create a DataTransfer to get a FileList
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(FilePendukung);
                        filedocpendukung.files = dataTransfer.files;

                        $('.inputNameDocPendukung_'+value).val(item.dokumen_name)
                      }

                      $(".btnRemoveDocPendukung[data-value='remove_" + value + "']").click(function(){
                        Swal.fire({
                          title: 'Are you sure?',
                          text: "Deleting document",
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
                                url:"{{url('/admin/deleteDokumen/')}}",
                                data:{
                                  _token:"{{ csrf_token() }}",
                                  id:this.value
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
                                success: function(data)
                                {
                                  Swal.showLoading()
                                  Swal.fire(
                                      'Document has been deleted!',
                                      'You can adding another document files',
                                      'success'
                                  ).then((result) => {
                                    if (result.value) {
                                      $(".btnRemoveDocPendukung[data-value='remove_" + value + "']").closest("tr").remove();
                                    }
                                  })
                                }
                              })
                            }
                        })
                        if($('#tableDocPendukung_ipr tr').length == 0){
                          $("#titleDoc").hide()
                        }
                      })
                    })
                  }
                })                  
              }   
    
              $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')        
              $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
              document.getElementById("prevBtnAdd").style.display = "inline";
            } else if (n == 4) {
              $(".modal-dialog").removeClass('modal-lg')
              $('.wysihtml5-toolbar').remove();
              if (status == 'reject') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").val(result.activity.reason.replaceAll("\n","<br>"))
                reasonReject(result.activity.reason,"block","tabGroup")

              }else if (status == 'revision') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").html(result.activity.reason.replaceAll("\n","<br>"))
                reasonReject(result.activity.reason,"block","tabGroup")
              }
              $(".modal-title").text('Terms & Condition')   
              $(".modal-dialog").removeClass('modal-lg')   
              $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')        
              $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
              document.getElementById("prevBtnAdd").style.display = "inline";

              if ($('.wysihtml5-toolbar').length == 0) {
                $("#textAreaTOP").html(result.pr.term_payment)
                $("#textAreaTOP").wysihtml5({
                  toolbar: {
                    "font-styles": true, // Font styling, e.g. h1, h2, etc.
                    "emphasis": true, // Italics, bold, etc.
                    "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
                    "html": false, // Button which allows you to edit the generated HTML.
                    "link": false, // Button to insert a link.
                    "image": false, // Button to insert an image.
                    "color": false, // Button to change color of font
                    "blockquote": false, // Blockquote
                    "size": true // options are xs, sm, lg
                  }
                })
              }else{
                $("#textAreaTOP").html(result.pr.term_payment)
              }
            }
            document.getElementById("nextBtnAdd").innerHTML = "Next"
            $("#nextBtnAdd").prop("disabled",false)
            $("#addProduct").attr('onclick','nextPrevUnFinished(-1,"saved")')
          }
        }
      })
      
      $("#ModalDraftPr").modal({backdrop: 'static', keyboard: false})  
    }

    function btnCancel(id){
      Swal.fire({
        title: 'Are you sure to cancel this pr?',
        icon: 'warning',
        input: 'textarea',
        // inputLabel: 'Cancelation reason',
        inputPlaceholder: 'Type reason here...',
        inputAttributes: {
          'aria-label': 'Type reason here'
        },
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value == "") {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please fill the reason to cancel this pr!'
          }).then(() => {
            btnCancel(id)
          })
        }else if (result.value != undefined) {
          $.ajax({
            type: "POST",
            url: "{{url('/admin/cancelDraftPr')}}",
            data: {
              _token: "{{ csrf_token() }}",
              no_pr:id,
              notes:result.value
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
    }

    localStorage.setItem('status_pr','')
    function addDraftPr(n){
      
      localStorage.setItem('status_pr','')
      let x = document.getElementsByClassName("tab-add");
      x[n].style.display = "inline";
      if (n == (x.length - 1)) {
        $(".modal-dialog").addClass('modal-lg')
        $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')        
        $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
        $(".modal-title").text('')
        document.getElementById("prevBtnAdd").style.display = "inline";
        $("#headerPreviewFinal").empty()
        document.getElementById("nextBtnAdd").innerHTML = "Create";
        $("#nextBtnAdd").attr('onclick','createPR("saved")'); 

        if ($("#selectType").val() == 'IPR') {
          PRType = '<b>Internal Purchase Request</b>'
        }else{
          PRType = '<b>External Purchase Request</b>'
        }

        PRMethode = $("#selectMethode").find(":selected").text()
        leadRegister = $("#selectLeadId").val()
        quoteNumber = $("#selectQuoteNumber").val() 
        $.ajax({
          type: "GET",
          url: "{{url('/admin/getPreviewPr')}}",
          data: {
              no_pr:localStorage.getItem('no_pr'),
          },
          success: function(result) {
            var appendHeader = ""
            appendHeader = appendHeader + '<div class="row">'
            appendHeader = appendHeader + '    <div class="col-md-6">'
            appendHeader = appendHeader + '        <div class="">To: '+ result.pr.to +'</div>'
            appendHeader = appendHeader + '        <div class="">Email: ' + result.pr.email + '</div>'
            appendHeader = appendHeader + '        <div class="">Phone: ' + result.pr.phone + '</div>'
            appendHeader = appendHeader + '        <div class="">Fax: '+ result.pr.fax +' </div>'
            appendHeader = appendHeader + '        <div class="">Attention: '+ result.pr.attention +'</div>'
            appendHeader = appendHeader + '        <div class="">From: '+ result.pr.name +'</div>'
            appendHeader = appendHeader + '        <div class="">Subject: '+ result.pr.title +'</div>'
            appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ result.pr.address +'</div>'

            appendHeader = appendHeader + '    </div>'
            if (window.matchMedia("(max-width: 768px)").matches)
            {
                appendHeader = appendHeader + '    <div class="col-md-6">'
                // The viewport is less than 768 pixels wide
                
            } else {
                appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                // The viewport is at least 768 pixels wide
                
            }
            appendHeader = appendHeader + '        <div>'+ PRType +'</div>'
            appendHeader = appendHeader + '        <div><b>Request Methode</b></div>'
            appendHeader = appendHeader + '        <div>'+ result.pr.request_method +'</div>'
            appendHeader = appendHeader + '        <div>'+ moment(result.pr.created_at).format('DD MMMM') +'</div>'
            if (PRType == 'EPR') {
              appendHeader = appendHeader + '        <div><b>Lead Register</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.lead_id +'</div>'
              appendHeader = appendHeader + '        <div><b>Quote Number</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.quote_number +'</div>'
            }
            appendHeader = appendHeader + '    </div>'
            appendHeader = appendHeader + '</div>'

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
                append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name_product + "'>"
                append = append + '</td>'
                append = append + '<td width="35%">'
                  append = append + '<textarea readonly class="form-control" style="height: 250px;resize: none;height: 120px;font-size: 12px;">' + item.description.replaceAll("<br>","\n") + '</textarea>'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="text" name="" value="'+ item.serial_number +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="text" name="" value="'+ item.part_number +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<select disabled style="width:75px;font-size: 12px;" class="form-control">'
                  append = append + '<option>'+ item.unit.charAt(0).toUpperCase() + item.unit.slice(1) +'</option>'
                  append = append + '</select>'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly id="grandTotalPreviewFinalPage" class="form-control grandTotalPreviewFinalPage" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
              append = append + '</tr>'
            })

            $("#tbodyFinalPageProducts").append(append)

            $("#bottomPreviewFinal").empty()        
            appendBottom = ""
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<div class="form-group">'
              appendBottom = appendBottom + '<div class="row">'
              appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '    <div class="pull-right">'
              appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
              appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;" class="form-control inputTotalPriceFinal" id="inputTotalPriceFinal" name="inputTotalPriceFinal">'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + '  </div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
              appendBottom = appendBottom + ' <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '   <div class="pull-right">'
                appendBottom = appendBottom + '   <span style="margin-right: -5px;">Vat <span class="title_tax"></span></span>'
                appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
                appendBottom = appendBottom + '       <input readonly="" autocomplete="off" type="text" class="form-control vat_tax pull-right" id="vat_tax_final" name="vat_tax_final" style="width:150px;">'
                appendBottom = appendBottom + '     </div>'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + ' </div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
              appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '    <div class="pull-right">'
              appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
              appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;" class="form-control inputFinalPageGrandPrice" id="inputFinalPageGrandPrice" name="inputFinalPageGrandPrice">'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + '  </div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
            appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span><b>Attached Files</b><span>'
            var pdf = "fa fa-fw fa-file-pdf-o"
            var image = "fa fa-fw fa-file-image-o"
            if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            if (result.pr.type_of_letter == 'IPR') {
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
                if (value != 0) {
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
                            

            $("#termPreview").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))

            var tempVat = 0
            var finalVat = 0
            var tempGrand = 0
            var finalGrand = 0
            var tempTotal = 0
            var sum = 0

            $('.grandTotalPreviewFinalPage').each(function() {
                var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
                sum += temp;
            });

            if (result.pr.status_tax == 'True') {
              tempVat = formatter.format((parseFloat(sum) * 11) / 100)
              tempGrand = parseFloat(sum) +  parseFloat((parseFloat(sum) * 11) / 100)
            }else{
              tempVat = tempVat
              tempGrand = parseFloat(sum)
            }

            finalVat = tempVat

            finalGrand = tempGrand

            tempTotal = sum
            $("#vat_tax_final").val(tempVat)
            $("#inputTotalPriceFinal").val(formatter.format(sum))
            $("#inputFinalPageGrandPrice").val(formatter.format(tempGrand))
          }
        })
                      
      } else {
        if (n == 0) {
          const firstLaunch = localStorage.getItem('firstLaunch')
          document.getElementById("prevBtnAdd").style.display = "none";
          $(".modal-title").text('Information Supplier')
          $(".modal-dialog").removeClass('modal-lg')

          if (firstLaunch == 'true') {
            $("#nextBtnAdd").attr('onclick','nextPrevAdd(1,'+ firstLaunch +')')
          }else{
            $("#nextBtnAdd").attr('onclick','nextPrevAdd(2,'+ firstLaunch +')')
          }   

          $.ajax({
            url:"{{url('/admin/getSupplier')}}",
            type:"GET",
            success:function(result){
              $("#selectTo").select2({
                data:result,
                placeholder:"Select Supplier"
              })
            }
          })   

          $("#otherTo").click(function(){
            $("#divInputTo").show("slow")
          })

          $(".close").click(function(){
            $("#divInputTo").hide("slow")
          })   
        }else if (n == 1) {
          select2TypeProduct()

          $(".modal-title").text('Information Product')
          $(".modal-dialog").removeClass('modal-lg')  
          $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
          $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')        
          document.getElementById("prevBtnAdd").style.display = "inline";

          //button add initiate product show form-group
          $("#btnInitiateAddProduct").click(function(){
            $(".tabGroupInitiateAdd").hide()
            x[n].children[1].style.display = 'inline'
          })
          
        } 
        else if(n == 2){
          $(".modal-title").text('')
          $("#nextBtnAdd").removeAttr('onclick')
          $(".modal-dialog").addClass('modal-lg')

          localStorage.setItem('firstLaunch',false)
          if (localStorage.getItem('firstLaunch') == 'false') {
            $("#prevBtnAdd").attr('onclick','nextPrevAdd(-2)')
            $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
          }else{
            $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')
          } 
          document.getElementById("prevBtnAdd").style.display = "inline";
        }else if (n == 3) {
          $(".modal-dialog").removeClass('modal-lg')
          if ($("#selectType").val() == 'EPR') {
            $(".modal-title").text('External Purchase Request')
            $("#formForPrExternal").show()
            $("#formForPrInternal").hide()  
            fillInput('spk')
            fillInput('sbe')
            fillInput('quoteSupplier')      
          }else{
            $(".modal-title").text('Internal Purchase Request')
            $("#formForPrInternal").show()
            $("#formForPrExternal").hide()      

            fillInput('penawaranHarga')
          }         
          $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1,"saved")')       
          $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,"saved")')
          document.getElementById("prevBtnAdd").style.display = "inline";

          $.ajax({
            url: "{{url('/admin/getPidAll')}}",
            type: "GET",
            success: function(result) {
              $("#selectPid").select2({
                  data: result.data,
                  placeholder: "Select Pid",
                  dropdownParent: $('#ModalDraftPr')
              }).on('select2:select', function() {
                var data = $("#selectPid option:selected").text();
                var lead_id = $("#selectLeadId option:selected").text();

                $("#selectLeadId").empty()
                $.ajax({
                  url: "{{url('/admin/getLeadByPid')}}",
                  type: "GET",
                  data: {
                    pid:data
                  },
                  success: function(result) {
                    let fileSPK   = document.querySelector('input[type="file"][name="inputSPK"]');

                    let fileSBE   = document.querySelector('input[type="file"][name="inputSBE"]');

                    let fileQuote = document.querySelector('input[type="file"][name="inputQuoteSupplier"]');

                    $("#selectLeadId").select2({
                        data: result.data,
                        placeholder: "Select Lead Register",
                        dropdownParent: $('#ModalDraftPr')
                    })

                    if (result.linkSbe.length > 0) {
                      const myFileSbe = new File(['{{asset("/")}}'+result.linkSbe[0].document_location], '/'+result.linkSbe[0].document_location,{
                        type: 'text/plain',
                        lastModified: new Date(),
                      });
                      // Now let's create a DataTransfer to get a FileList
                      const dataTransferSbe = new DataTransfer();
                      dataTransferSbe.items.add(myFileSbe);
                      fileSBE.files = dataTransferSbe.files;

                      $("#inputSBE").attr("disabled",true).css("cursor","not-allowed")
                      $("#inputSBE").closest(".form-group").find("#span_link_drive_sbe").show()
                      $("#link_sbe").attr("href",result.linkSbe[0].link_drive)
                    }else{
                      $("#inputSBE").attr("disabled",false).css("cursor","")
                      $("#inputSBE").val("")
                      $("#inputSBE").closest(".form-group").find("#span_link_drive_sbe").hide()
                    }
                  }
                }) 

                $("#selectQuoteNumber").empty()
                $.ajax({
                  url: "{{url('/admin/getQuote')}}",
                  type: "GET",
                  data:{
                    lead_id:lead_id
                  },
                  success: function(result) {
                    $("#selectQuoteNumber").select2({
                        data: result.data,
                        placeholder: "Select Quote Number",
                        dropdownParent: $('#ModalDraftPr')
                    })
                  }
                }) 
              })
            }
          })

          $.ajax({
            url: "{{url('/admin/getQuote')}}",
            type: "GET",
            success: function(result) {
              $("#selectQuoteNumber").select2({
                  data: result.data,
                  placeholder: "Select Quote Number"
              })
            }
          }) 

          $.ajax({
            url: "{{url('/admin/getLead')}}",
            type: "GET",
            success: function(result) {
              // result.data.unshift({"id" : "-","text" : "Select Lead Register"})
              $("#selectLeadId").select2({
                  data: result.data,
                  placeholder: "Select Lead Register"
              })
            }
          }) 

          $.ajax({
            url: "{{url('/admin/getPid')}}",
            type: "GET",
            success: function(result) {
              $("#selectPid").select2({
                  data: result.data,
                  placeholder: "Select PID"
              })
            }
          }) 

        }else if (n == 4) {
          if ($('.wysihtml5-toolbar').length == 0) {
            $("#textAreaTOP").wysihtml5({
              toolbar: {
                  "font-styles": true, // Font styling, e.g. h1, h2, etc.
                  "emphasis": true, // Italics, bold, etc.
                  "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
                  "html": false, // Button which allows you to edit the generated HTML.
                  "link": false, // Button to insert a link.
                  "image": false, // Button to insert an image.
                  "color": false, // Button to change color of font
                  "blockquote": false, // Blockquote
                  "size": true // options are xs, sm, lg
                }
            });
          }

          $(".modal-title").text('Terms & Condition')   
          $(".modal-dialog").removeClass('modal-lg')   
          $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')        
          $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
          document.getElementById("prevBtnAdd").style.display = "inline";

        }
        document.getElementById("nextBtnAdd").innerHTML = "Next"
        $("#nextBtnAdd").prop("disabled",false)
        $("#addProduct").attr('onclick','nextPrevAdd(-1)')
      }
      $("#ModalDraftPr").modal({backdrop: 'static', keyboard: false})  
    }

    $('#ModalDraftPr').on('hidden.bs.modal', function () {
      $(".tab-add").css('display','none')
      currentTab = 0
      n = 0
      $(".divReasonRejectRevision").hide()
      $(this)
      .find("input,textarea,select")
         .val('')
         .prop("disabled",false)
         .end()
      .find("input[type=checkbox], input[type=radio]")
         .prop("checked", "")
         .end();
      localStorage.setItem('firstLaunch', true);
      localStorage.setItem('isStoreSupplier',false);
      $("#span_link_drive_spk").hide()
      $("#span_link_drive_sbe").hide()
      $("#span_link_drive_quoteSup").hide()
      $("#span_link_drive_penawaranHarga").hide()
      // $("#tableDocPendukung").remove()
      localStorage.setItem('isEditProduct',false)
      localStorage.setItem('status_pr','') 
    }) 

    $('#ModalDraftPrAdmin').on('hidden.bs.modal', function () {
      // if (window.location.href.split("/")[4] != undefined) {
      //   location.replace("{{url('/admin/draftPR')}}/")
      // }
      // localStorage.setItem('isEditProduct',false)
      window.history.pushState(null,null,location.protocol + '//' + location.host + location.pathname)
      $(".tab-cek").css('display','none')
      currentTab = 0
      n = 0
    })

    function addDraftPrPembanding(n){
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
            appendHeader = appendHeader + '<div class="row">'
            appendHeader = appendHeader + '    <div class="col-md-6">'
            appendHeader = appendHeader + '        <div class="">To: '+ result.pr.to +'</div>'
            appendHeader = appendHeader + '        <div class="">Email: ' + result.pr.email + '</div>'
            appendHeader = appendHeader + '        <div class="">Phone: ' + result.pr.phone + '</div>'
            appendHeader = appendHeader + '        <div class="">Fax: '+ result.pr.fax +' </div>'
            appendHeader = appendHeader + '        <div class="">Attention: '+ result.pr.attention +'</div>'
            appendHeader = appendHeader + '        <div class="">From: '+ result.pr.name +'</div>'
            appendHeader = appendHeader + '        <div class="">Subject: '+ result.pr.title +'</div>'
            appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ result.pr.address +'</div>'

            appendHeader = appendHeader + '    </div>'
            if (window.matchMedia("(max-width: 768px)").matches)
            {
                appendHeader = appendHeader + '    <div class="col-md-6">'
                // The viewport is less than 768 pixels wide
                
            } else {
                appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                // The viewport is at least 768 pixels wide
                
            }
            appendHeader = appendHeader + '        <div>'+ PRType +'</div>'
            appendHeader = appendHeader + '        <div><b>Request Methode</b></div>'
            appendHeader = appendHeader + '        <div>'+ result.pr.request_method +'</div>'
            appendHeader = appendHeader + '        <div>'+ moment(result.pr.created_at).format('DD MMMM') +'</div>'
            if (PRType == 'EPR') {
              appendHeader = appendHeader + '        <div><b>Lead Register</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.lead_id +'</div>'
              appendHeader = appendHeader + '        <div><b>Quote Number</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.quote_number +'</div>'
            }
            appendHeader = appendHeader + '    </div>'
            appendHeader = appendHeader + '</div>'

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
                append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name_product + "'>"
                append = append + '</td>'
                append = append + '<td width="35%">'
                  append = append + '<textarea style="font-size: 12px; important;resize:none" readonly class="form-control">' + item.description.replaceAll("<br>","\n") + '</textarea>'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="text" name="" value="'+ item.serial_number +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="text" name="" value="'+ item.part_number +'" style="width:100px;font-size: 12px;">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px; important">'
                append = append + '</td>'
                append = append + '<td width="10%">'
                  append = append + '<select disabled style="width:75px;font-size: 12px; important" class="form-control">'
                  append = append + '<option>'+ item.unit.charAt(0).toUpperCase() + item.unit.slice(1) +'</option>'
                  append = append + '</select>'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input style="font-size: 12px; important" readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px">'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly id="grandTotalPreview" class="form-control" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px; important">'
                append = append + '</td>'
              append = append + '</tr>'
            })

            $("#tbodyFinalPageProducts").append(append)

            $("#bottomPreviewFinal").empty()        
            appendBottom = ""
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<div class="form-group">'
              appendBottom = appendBottom + '<div class="row">'
                appendBottom = appendBottom + '<div class="col-md-12">'
                  appendBottom = appendBottom + '<div class="pull-right">'
                    appendBottom = appendBottom + '<span style="display: inline;margin-right: 10px;">Grand Price</span>'
                    appendBottom = appendBottom + '<input readonly type="text" style="width:150px;margin-right:10px;display: inline;" class="form-control" id="inputFinalPageGrandPrice" name="inputFinalPageTotalPrice">'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
            appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span><b>Attached Files</b><span>'
            var pdf = "fa fa-fw fa-file-pdf-o"
            var image = "fa fa-fw fa-file-image-o"
            if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
              var fa_doc = pdf
            }else{
              var fa_doc = image
            }
            if (result.pr.type_of_letter == 'IPR') {
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

            $("#bottomPreviewFinal").append(appendBottom)   

            $("#termPreview").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))

            var sum = 0
            $('#grandTotalPreview').each(function() {
                var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
                sum += temp;
            });
           
            $("#inputFinalPageGrandPrice").val(formatter.format(sum))
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
              if (result.type_of_letter == 'EPR') {
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
              // result.data.unshift({"id" : "-","text" : "Select Lead Register"})
              $("#selectLeadId").select2({
                  data: result.data,
                  placeholder: "Select Lead Register"
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
          $(".modal-title").text('Terms & Condition')   
          $(".modal-dialog").removeClass('modal-lg')   
          $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
          $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
          document.getElementById("prevBtnAdd").style.display = "inline";
        }
        document.getElementById("nextBtnAdd").innerHTML = "Next"
        $("#nextBtnAdd").prop("disabled",false)
        $("#addProduct").attr('onclick','nextPrevAddPembanding(-1)')
      }
      $("#ModalDraftPr").modal({backdrop: 'static', keyboard: false})  
    }

    if (window.location.href.split("/")[4].split("?")[1] != undefined) {
      if (window.location.href.split("/")[4].split("?")[1].split("&")[0].split("=")[1]  == 'draft') {
        cekByAdmin(0,window.location.href.split("/")[4].split("?")[1].split("&")[1].split("=")[1])
      }else if (window.location.href.split("/")[4].split("?")[1].split("&")[0].split("=")[1]  == 'reject' || window.location.href.split("/")[4].split("?")[1].split("&")[0].split("=")[1]  == 'revision') {
        unfinishedDraft(0,window.location.href.split("/")[4].split("?")[1].split("&")[1].split("=")[1],window.location.href.split("/")[4].split("?")[1].split("&")[0].split("=")[1])
      }
    }

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
    })

    function cekByAdmin(n,no_pr){
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getPreviewPr')}}",
        data: {
          no_pr:no_pr,
        },
        success: function(result) {
          var x = document.getElementsByClassName("tab-cek");
          x[n].style.display = "inline";
          if (n == (x.length - 1)) {
            countCheck = $('input[name="chk[]"]').length
            if ($('input[name="chk[]"]:checked').length < countCheck) {
              $("input[type='radio'][name='radioConfirm']").click(function(){
                if ($("input[type='radio'][name='radioConfirm']:checked").val() == 'reject' ) {
                  $("#divReasonReject").show()
                  $("#nextBtnAddAdmin").attr('onclick','ConfirmDraftPr(' +no_pr+', "reject")')
                }else{
                  $("#divReasonReject").hide()
                  $("#nextBtnAddAdmin").attr('onclick','ConfirmDraftPr(' +no_pr+',"approve")')
                }
              })

              $("#notAllChecked").show()
              $("#AllChecked").hide()
            }else{
              $("#notAllChecked").hide()
              $("#AllChecked").show()

              $("#nextBtnAddAdmin").attr('onclick','ConfirmDraftPr(' +no_pr+',"approve")')
            }
            
            $(".modal-title").text('Confirm Draft PR')
            $(".modal-dialog").removeClass('modal-lg')
            document.getElementById("nextBtnAddAdmin").innerHTML = "Verify"
          } else {
            if (n == 0) {
              $("#inputToCek").val(result.pr.to)
              $("#selectTypeCek").val(result.pr.type_of_letter)
              $("#inputEmailCek").val(result.pr.email)
              $("#inputPhoneCek").val(result.pr.phone)
              // $("#inputFax").val(result.pr.fax)
              $("#inputAttentionCek").val(result.pr.attention)
              $("#inputSubjectCek").val(result.pr.title)
              $("#inputAddressCek").val(result.pr.address)
              if (result.pr.request_method == 'Reimbursement') {
                $("#selectMethodeCek").val('reimbursement')
              }else if (result.pr.request_method == 'Purchase Order') {
                $("#selectMethodeCek").val('purchase_order')
              }else{
                $("#selectMethodeCek").val('payment')
              }
              $("#selectCategoryCek").val(result.pr.category)

              document.getElementById("prevBtnAddAdmin").style.display = "none";
              $(".modal-title").text('Information Supplier')
              $(".modal-dialog").removeClass('modal-lg')
              $("#nextBtnAddAdmin").attr('onclick','nextPrevAddAdmin(1,'+ result.pr.id +')')
              
            }
            else if(n == 1){
              $(".modal-title").text('')
              $("#nextBtnAddAdmin").removeAttr('onclick')
              $(".modal-dialog").addClass('modal-lg')
              $("#nextBtnAddAdmin").attr('onclick','nextPrevAddAdmin(1,'+ result.pr.id +')')
              $("#prevBtnAddAdmin").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')
              document.getElementById("prevBtnAddAdmin").style.display = "inline";
              cekTable(no_pr)

              $("#refreshTableCek").click(function(){
                cekTable(no_pr)
              })

              $('input[type="checkbox"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
              })

              $("#bottomProductsCek").empty()
              var appendBottom = ""
              appendBottom = appendBottom + '<hr>'
                appendBottom = appendBottom + '<div class="row">'
                appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                appendBottom = appendBottom + '    <div class="pull-right">'
                appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
                appendBottom = appendBottom + '      <input readonly="" type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductCek" id="inputGrandTotalProductCek" name="inputGrandTotalProductCek">'
                appendBottom = appendBottom + '    </div>'
                appendBottom = appendBottom + '  </div>'
                appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                appendBottom = appendBottom + ' <div class="col-md-12 col-xs-12">'
                appendBottom = appendBottom + '   <div class="pull-right">'
                  appendBottom = appendBottom + '   <span style="margin-right: -5px;">Vat <span class="title_tax"></span></span>'
                  appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
                  appendBottom = appendBottom + '       <input readonly="" type="text" class="form-control vat_tax_cek pull-right" id="vat_tax_cek" name="vat_tax_cek" style="width:250px;">'
                  appendBottom = appendBottom + '     </div>'
                appendBottom = appendBottom + '    </div>'
                appendBottom = appendBottom + ' </div>'
                appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                appendBottom = appendBottom + '    <div class="pull-right">'
                appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
                appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductFinalCek" id="inputGrandTotalProductFinalCek" name="inputGrandTotalProductFinalCek">'
                appendBottom = appendBottom + '    </div>'
                appendBottom = appendBottom + '  </div>'
              appendBottom = appendBottom + '</div>'

              $("#bottomProductsCek").append(appendBottom)  

              $(document).on("click", "#btnEditProdukCek", function() {
                $("#ModalDraftPrAdmin").modal('hide')
                currentTab = 1
                unfinishedDraft(currentTab,no_pr,"admin")
                $(".tabGroupInitiateAdd").hide()
                $(".tab-add")[1].children[1].style.display = 'inline'
                localStorage.setItem('isEditProduct',true)
                localStorage.setItem('id_product',result.product[$(this).data("value")].id_product)
                nominal = result.product[$(this).data("value")].nominal_product
                $("#inputNameProduct").val(result.product[$(this).data("value")].name_product)
                $("#inputDescProduct").val(result.product[$(this).data("value")].description.replaceAll("<br>","\n"))
                $("#inputQtyProduct").val(result.product[$(this).data("value")].qty)
                select2TypeProduct(result.product[$(this).data("value")].unit)
                $("#inputPriceProduct").val(formatter.format(nominal))
                $("#inputSerialNumber").val(result.product[$(this).data("value")].serial_number)
                $("#inputPartNumber").val(result.product[$(this).data("value")].part_number)
                $("#inputTotalPrice").val(formatter.format(result.product[$(this).data("value")].grand_total))
              })

              $(document).on("click", "#btnDeleteProdukCek", function() {
                Swal.fire({
                  title: 'Are you sure?',  
                  text: "Deleting Product",
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
                      url: "{{url('/admin/deleteProduct')}}",
                      data:{
                        _token:'{{ csrf_token() }}',
                        id:$(this).data("id")
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
                      success: function(result) {
                        Swal.fire(
                            'Successfully!',
                            'Delete Product.',
                            'success'
                        ).then((result) => {
                          // $("#tbodyProductsCek").empty()
                          cekTable(no_pr)
                        })
                      }
                    })          
                  }
                })
              })
            }else if (n == 2) {
              $(".modal-dialog").removeClass('modal-lg')
              if ($("#selectTypeCek").val() == 'EPR') {
                $(".modal-title").text('External Purchase Request')
                $("#formForPrExternalCek").show()
                $("#formForPrInternalCek").hide()   

                $("#formForPrExternalCek").find($("input[type=checkbox]")).attr('name','chk[]')
                $("#selectPidCek").val(result.pr.pid)
                $("#selectLeadIdCek").val(result.pr.lead_id)
                $("#selectQuoteNumCek").val(result.pr.quote_number)

                var pdf = "fa fa-fw fa-file-pdf-o"
                var image = "fa fa-fw fa-file-image-o"
                
                if (result.dokumen[0].link_drive != null) {
                  if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_quoteSup_cek").show()
                  $("#link_quoteSupCek").attr("href",result.dokumen[0].link_drive)
                  $("#inputQuoteSupplierCek").val(result.dokumen[0].dokumen_location)
                  $(".icon_quo").addClass(fa_doc)
                }

                if (result.dokumen[2].link_drive != null) {
                  if (result.dokumen[2].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_sbe_cek").show()
                  $("#link_sbeCek").attr("href",result.dokumen[2].link_drive)
                  $("#inputSBECek").val(result.dokumen[2].dokumen_location)
                  $(".icon_sbe").addClass(fa_doc)
                }

                if (result.dokumen[1].link_drive != null) {
                  if (result.dokumen[1].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_spk_cek").show()
                  $("#link_spkCek").attr("href",result.dokumen[1].link_drive)
                  $("#inputSPKCek").val(result.dokumen[1].dokumen_location)
                  $(".icon_spk").addClass(fa_doc)
                }

                var appendDokumen = ""
                $("#docPendukungContainerCekEPR").empty()

                $.each(result.dokumen,function(value,item){
                  var pdf = "fa fa-fw fa-file-pdf-o"
                  var image = "fa fa-fw fa-file-image-o"
                  if (item.dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }

                  if (value != 0 && value != 1 && value != 2) {
                    appendDokumen = appendDokumen + '<div class="form-group">'
                    appendDokumen = appendDokumen + '<label>Lampiran Dokumen Pendukung</label>'
                    
                    appendDokumen = appendDokumen + '<div class="form-group" style="font-size: reguler;">'
                      appendDokumen = appendDokumen + '<div class="row">'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;background-color: #EEEEEE;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                          appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="padding: 5px;display:inline"> Dokumen Pendukung : ' + item.dokumen_name + '</div><input style="display:inline" id="doc_'+item.id_dokumen+'_pendukung" class="minimal" type="checkbox" name="chk[]" />'
                        appendDokumen = appendDokumen + '</div>'
                      appendDokumen = appendDokumen + '</div>'
                    appendDokumen = appendDokumen + '</div>'
                  }

                })
                $("#docPendukungContainerCekEPR").append(appendDokumen)

                $('input[type="checkbox"].minimal').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                })

              }else{
                $(".modal-title").text('Internal Purchase Request')
                $("#formForPrInternalCek").show()
                $("#formForPrExternalCek").hide()       

                $("#formForPrInternalCek").find($("input[type=checkbox]")).attr('name','chk[]')     

                var appendDokumen = ""
                $("#docPendukungContainerCek").empty()
                $.each(result.dokumen,function(value,item){
                  var pdf = "fa fa-fw fa-file-pdf-o"
                  var image = "fa fa-fw fa-file-image-o"
                  if (item.dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  if (item.dokumen_name == 'Penawaran Harga') {                    
                    appendDokumen = appendDokumen + '<div class="form-group">'
                    appendDokumen = appendDokumen + '<label>Lampiran Penawaran Harga*</label>'
                    appendDokumen = appendDokumen + '<div class="form-group" style="font-size: reguler;">'
                      appendDokumen = appendDokumen + '<div class="row">'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'" style="color:#367fa9"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                          appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="padding: 5px;display:inline">Penawaan Harga</div><input style="display:inline" id="doc_'+item.id_dokumen+'_pendukung" class="minimal" type="checkbox" name="chk[]" />'
                        appendDokumen = appendDokumen + '</div>'
                      appendDokumen = appendDokumen + '</div>'
                    appendDokumen = appendDokumen + '</div>'         
                    appendDokumen = appendDokumen + '</div>'
                  }else{
                    appendDokumen = appendDokumen + '<div class="form-group">'
                    if (value == 1) {
                      appendDokumen = appendDokumen + '<label>Lampiran Dokumen Pendukung</label>'
                    }
                    appendDokumen = appendDokumen + '<div class="form-group" style="font-size: reguler;">'
                      appendDokumen = appendDokumen + '<div class="row">'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;"><a href="'+item.link_drive+'" target="blank"><i class="'+ fa_doc +'"></i>'+ item.dokumen_location.substring(0,15) + '....'+ item.dokumen_location.split(".")[0].substring(item.dokumen_location.length -10) + "." + item.dokumen_location.split(".")[1] +'</a>'
                          appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '</div>'
                        appendDokumen = appendDokumen + '<div class="col-md-6">'
                          appendDokumen = appendDokumen + '<div style="padding: 5px;display:inline"> Dokumen Pendukung : ' + item.dokumen_name + '</div><input style="display:inline" id="doc_'+item.id_dokumen+'_pendukung" class="minimal" type="checkbox" name="chk[]" />'
                        appendDokumen = appendDokumen + '</div>'
                      appendDokumen = appendDokumen + '</div>'
                    appendDokumen = appendDokumen + '</div>'
                  }  

                })
                $("#docPendukungContainerCek").append(appendDokumen)
                                

                $('input[type="checkbox"].minimal').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                })
              }     

              $("#prevBtnAddAdmin").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')       
              $("#nextBtnAddAdmin").attr('onclick','nextPrevAddAdmin(1,'+ result.pr.id +')')
              document.getElementById("prevBtnAddAdmin").style.display = "inline";

            }else if (n == 3) {
              $("#textAreaTOPCek").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))
              $(".modal-title").text('Terms & Condition')
              $(".modal-dialog").removeClass('modal-lg')
              $("#prevBtnAddAdmin").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')       
              $("#nextBtnAddAdmin").attr('onclick','nextPrevAddAdmin(1,'+ result.pr.id +')')
              document.getElementById("prevBtnAddAdmin").style.display = "inline";

            }else if (n == 4) {
              $("#headerPreviewFinalCek").empty()

              $(".modal-dialog").addClass('modal-lg')
              $("#prevBtnAddAdmin").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')        
              $("#nextBtnAddAdmin").attr('onclick','nextPrevAddAdmin(1,'+ result.pr.id +')')
              $(".modal-title").text('')
              document.getElementById("prevBtnAddAdmin").style.display = "inline";
              document.getElementById("nextBtnAddAdmin").innerHTML = "Create";

              if ($("#selectTypeCek").val() == 'IPR') {
                PRType = '<b>Internal Purchase Request</b>'
              }else{
                PRType = '<b>External Purchase Request</b>'
              }

              PRMethode = $("#selectMethode").find(":selected").text()

              var appendHeader = ""
              appendHeader = appendHeader + '<div class="row">'
              appendHeader = appendHeader + '    <div class="col-md-6">'
              appendHeader = appendHeader + '        <div class="">To: '+ result.pr.to +'</div>'
              appendHeader = appendHeader + '        <div class="">Email: ' + result.pr.email + '</div>'
              appendHeader = appendHeader + '        <div class="">Phone: ' + result.pr.phone + '</div>'
              appendHeader = appendHeader + '        <div class="">Fax: '+ result.pr.fax +' </div>'
              appendHeader = appendHeader + '        <div class="">Attention: '+ result.pr.attention +'</div>'
              appendHeader = appendHeader + '        <div class="">From: '+ result.pr.name +'</div>'
              appendHeader = appendHeader + '        <div class="">Subject: '+ result.pr.title +'</div>'
              appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ result.pr.address +'</div>'

              appendHeader = appendHeader + '    </div>'
              if (window.matchMedia("(max-width: 768px)").matches)
              {
                  appendHeader = appendHeader + '    <div class="col-md-6">'
                  // The viewport is less than 768 pixels wide
                  
              } else {
                  appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                  // The viewport is at least 768 pixels wide
                  
              }
              appendHeader = appendHeader + '        <div>'+ PRType +'</div>'
              appendHeader = appendHeader + '        <div><b>Request Methode</b></div>'
              appendHeader = appendHeader + '        <div>'+ result.pr.request_method +'</div>'
              appendHeader = appendHeader + '        <div>'+ moment(result.pr.created_at).format('DD MMMM') +'</div>'
              if (PRType == 'EPR') {
                appendHeader = appendHeader + '        <div><b>Lead Register</b></div>'
                appendHeader = appendHeader + '        <div>'+ result.pr.lead_id +'</div>'
                appendHeader = appendHeader + '        <div><b>Quote Number</b></div>'
                appendHeader = appendHeader + '        <div>'+ result.pr.quote_number +'</div>'
              }
              appendHeader = appendHeader + '    </div>'
              appendHeader = appendHeader + '</div>'

              $("#headerPreviewFinalCek").append(appendHeader)

              $("#tbodyFinalPageProductsCek").empty()
              var append = ""
              var i = 0
              $.each(result.product,function(value,item){
                i++
                append = append + '<tr>'
                  append = append + '<td>'
                    append = append + '<span>'+ i +'</span>'
                  append = append + '</td>'
                  append = append + '<td width="20%">'
                  append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name_product + "'>"
                  append = append + '</td>'
                  append = append + '<td width="40%">'
                    append = append + '<textarea style="font-size: 12px;height:150px;resize:none" readonly class="form-control">' + item.description.replaceAll("<br>","\n") + '&#10;&#10;SN : ' + item.serial_number + '&#10;PN : ' + item.part_number + '</textarea>'
                  append = append + '</td>'
                  append = append + '<td width="5%">'
                    append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px;">'
                  append = append + '</td>'
                  append = append + '<td width="5%">'
                    append = append + '<select disabled style="width:70px;font-size: 12px;" class="form-control">'
                    append = append + '<option>'+ item.unit.charAt(0).toUpperCase() + item.unit.slice(1) +'</option>'
                    append = append + '</select>'
                  append = append + '</td>'
                  append = append + '<td width="15%">'
                    append = append + '<input style="font-size: 12px;" readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px">'
                  append = append + '</td>'
                  append = append + '<td width="15%">'
                    append = append + '<input readonly class="form-control grandTotalCek" id="grandTotalCek" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px;">'
                  append = append + '</td>'
                append = append + '</tr>'
              })

              $("#tbodyFinalPageProductsCek").append(append)

              $("#bottomPreviewFinalCek").empty()  
              appendBottom = ""
              appendBottom = appendBottom + '<hr>'
              appendBottom = appendBottom + '<div class="form-group">'
              appendBottom = appendBottom + '<div class="row">'
              appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '    <div class="pull-right">'
              appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
              appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;" class="form-control inputGrandTotalProductPreviewCek" id="inputGrandTotalProductPreviewCek" name="inputGrandTotalProductPreviewCek">'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + '  </div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
              appendBottom = appendBottom + ' <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '   <div class="pull-right">'
                appendBottom = appendBottom + '   <span style="margin-right: -5px;">Vat <span class="title_tax"></span></span>'
                appendBottom = appendBottom + '     <div class="input-group margin" style="display: inline;">'
                appendBottom = appendBottom + '       <input readonly="" type="text" class="form-control vat_tax pull-right" id="vat_tax_PreviewCek" name="vat_tax_PreviewCek" style="width:150px;">'
                appendBottom = appendBottom + '     </div>'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + ' </div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
              appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
              appendBottom = appendBottom + '    <div class="pull-right">'
              appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
              appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;" class="form-control inputFinalPageGrandPricePreviewCek" id="inputFinalPageGrandPricePreviewCek" name="inputFinalPageGrandPricePreviewCek">'
              appendBottom = appendBottom + '    </div>'
              appendBottom = appendBottom + '  </div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<hr>'
              appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
              appendBottom = appendBottom + '<div readonly id="termPreviewCek" class="form-control" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'
              appendBottom = appendBottom + '<hr>'
              appendBottom = appendBottom + '<span><b>Attached Files</b><span>'
              var pdf = "fa fa-fw fa-file-pdf-o"
              var image = "fa fa-fw fa-file-image-o"
              if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
                var fa_doc = pdf
              }else{
                var fa_doc = image
              }
              if (result.pr.type_of_letter == 'IPR') {
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

              $("#bottomPreviewFinalCek").append(appendBottom)

              $("#termPreviewCek").html(result.pr.term_payment.replaceAll("&lt;br&gt;","<br>"))

              var tempVat = 0
              var finalVat = 0
              var tempGrand = 0
              var finalGrand = 0
              var tempTotal = 0
              var sum = 0
              var valueVat = ""
              // if (result.pr.status_tax == 'True') {
              //   tempVat = formatter.format((parseFloat(sum) * 11) / 100)
              //   tempGrand = parseFloat(sum) +  parseFloat((parseFloat(sum) * 11) / 100)
              // }else{
              //   tempVat = tempVat
              //   tempGrand = parseFloat(sum)
              // }

              $('.grandTotalCek').each(function() {
                var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
                sum += temp;
              });

              if (result.pr.status_tax == false) {
                valueVat = 'false'
              }else{
                valueVat = result.pr.status_tax
              }
              if (!isNaN(valueVat)) {

                tempVat = Math.round((parseFloat(sum) * parseFloat(valueVat)) / 100)

                finalVat = tempVat

                tempGrand = Math.round(parseFloat(sum) +  parseFloat((parseFloat(sum) * parseFloat(valueVat)) / 100))

                finalGrand = tempGrand

                tempTotal = sum

                $('.title_tax').text(valueVat + '%')
              }else{
                tempVat = 0

                tempGrand = sum

                $('.title_tax').text("")
              }

              $("#vat_tax_PreviewCek").val(formatter.format(tempVat))
              $("#inputGrandTotalProductPreviewCek").val(formatter.format(sum))
              $("#inputFinalPageGrandPricePreviewCek").val(formatter.format(tempGrand))
            }
            document.getElementById("nextBtnAddAdmin").innerHTML = "Next"
            $("#nextBtnAddAdmin").prop("disabled",false)
            $("#addProduct").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')
          }
        }
      })
        
      $("#ModalDraftPrAdmin").modal({backdrop: 'static', keyboard: false})  
    }

    function cekTable(no_pr){
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getPreviewPr')}}",
        data: {
          no_pr:no_pr,
        },
        success: function(result) {
          var append = ""
          var i = 0
          var value = 0
          $("#tbodyProductsCek").empty()

          $.each(result.product,function(item,value){
            i++
            append = append + '<tr>'
              append = append + '<td>'
                append = append + '<span style="font-size: 12px;">'+ i +'</span>'
              append = append + '</td>'
              append = append + '<td width="20%">'
              append = append + "<input id='inputNameProductEdit' data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ value.name_product + "'>"
              append = append + '</td>'
              append = append + '<td width="30%">'
                append = append + '<textarea id="textAreaDescProductEdit" readonly data-value="" style="font-size: 12px;resize: none;height: 150px;" class="form-control">'+ value.description.replaceAll("<br>","\n") + '&#10;&#10;' + 'SN : ' + value.serial_number + '&#10;PN : ' + value.part_number
                append = append + '</textarea>'
              append = append + '</td>'
              append = append + '<td width="7%">'
                append = append + '<input id="inputQtyEdit" data-value="" readonly style="font-size: 12px;" class="form-control" type="" name="" value="'+ value.qty +'">'
              append = append + '</td>'
              append = append + '<td width="15%">'
              append = append + '<select id="inputTypeProductEdit" disabled data-value="" style="font-size: 12px;" class="form-control">'
              append = append + '<option>'+ value.unit.charAt(0).toUpperCase() + value.unit.slice(1) +'</option>'
              append = append + '</select>' 
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input id="inputPriceEdit" readonly data-value="" style="font-size: 12px;width:100px" class="form-control money" type="" name="" value="'+ formatter.format(value.nominal_product) +'">'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input id="inputTotalPriceEditCek" readonly data-value="" style="font-size: 12px;width:100px" class="form-control inputTotalPriceEditCek" type="" name="" value="'+ formatter.format(value.grand_total) +'">'
              append = append + '</td>'
              append = append + '<td width="8%">'
                append = append + '<button type="button" id="btnEditProdukCek" data-value="'+ item +'" class="btn btn-xs btn-warning fa fa-edit fa-2xs" style="width:25px;height:25px;margin-bottom:5px"></button>'
                append = append + '<button type="button" id="btnDeleteProdukCek" data-id="'+ value.id_product +'" data-value="'+ item +'" class="btn btn-xs btn-danger fa fa-trash fa-2xs" style="width:25px;height:25px;"></button>'
              append = append + '</td>'
              append = append + '<td width="5%">'
                append = append + '<input type="checkbox" id="product_'+i+'_cek" name="chk[]" class="minimal">'
              append = append + '</td>'
            append = append + '</tr>' 

          })

          $("#tbodyProductsCek").append(append)

          $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
          })

          var tempVat = 0
          var finalVat = 0
          var tempGrand = 0
          var finalGrand = 0
          var tempTotal = 0
          var btnVatStatus = true
          var valueVat = ''
          var sum = 0

          finalVat = tempVat

          finalGrand = tempGrand

          tempTotal = sum

          // if (result.pr.status_tax == 'True') {
          //   tempVat = formatter.format((parseFloat(sum) * 11) / 100)
          //   tempGrand = parseFloat(sum) +  parseFloat((parseFloat(sum) * 11) / 100)
          // }else{
          //   tempVat = tempVat
          //   tempGrand = parseFloat(sum)
          // }

          if (result.pr.status_tax == false) {
            valueVat = 'false'
          }else{
            valueVat = result.pr.status_tax
          }

          $('.inputTotalPriceEditCek').each(function() {
            var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
            sum += temp;
          });

          if (!isNaN(valueVat)) {

            tempVat = Math.round((parseFloat(sum) * parseFloat(valueVat)) / 100)

            finalVat = tempVat

            tempGrand = sum + finalVat

            tempTotal = sum

            $('.title_tax').text(valueVat + '%')
          }else{
            tempVat = 0

            tempGrand = sum

            $('.title_tax').text("")
          }

          $("#vat_tax_cek").val(formatter.format(tempVat))
          $("#inputGrandTotalProductCek").val(formatter.format(sum))
          $("#inputGrandTotalProductFinalCek").val(formatter.format(tempGrand))

        }
      })
      
    }

    //button confirm draft
    function ConfirmDraftPr(no_pr,status){
      var arrCheck = [];
      $('input[name="chk[]"]:checked').each(function () {
        // valuesChecked[valuesChecked.length] = (this.checked ? $(this).attr('id') : "");
        arrCheck.push($(this).attr('id'))
      });

      if (status == "reject") {
        if ($("#textAreaReasonReject").val() == "") {
          $("#textAreaReasonReject").closest('.form-group').addClass('has-error')
          $("#textAreaReasonReject").closest('textarea').next('span').show();
          $("#textAreaReasonReject").prev('.input-group-addon').css("background-color","red");
        } else {
          var data = {
            _token:'{{ csrf_token() }}',
            no_pr:no_pr,
            valuesChecked:arrCheck,
            rejectReason:$("#textAreaReasonReject").val(),
            radioConfirm:$("input[type='radio'][name='radioConfirm']:checked").val(),
          }

          verifyDraft(data)

        }           
      }else{
        var data = {
          _token:'{{ csrf_token() }}',
          no_pr:no_pr,
          valuesChecked:arrCheck,
          radioConfirm:$("input[type='radio'][name='radioConfirm']:checked").val(),
        }

        verifyDraft(data)
      }
    }

    //ajax post verify draft
    function verifyDraft(data){
      Swal.fire({
        title: 'Are you sure?',  
        text: "Admin verify PR",
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
            url: "{{url('/admin/verifyDraft')}}",
            data:data,
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
            success: function(result) {
                Swal.fire(
                    'Successfully!',
                    'Verify PR Successfully.',
                    'success'
                ).then((result) => {
                    if (result.value) {
                      location.replace("{{url('admin/draftPR')}}")
                    }
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
          })          
        }
      })
    }

    function fillInput(val){
      if (val == "selectTo") {
        $("#selectTo").closest('.form-group').removeClass('has-error')
        $("#selectTo").closest('.form-group').find('.help-block').hide();
        $("#selectTo").prev('.input-group-addon').css("background-color","red");
      }else if (val == "to") {
        $("#inputTo").closest('.divInputTo').closest('.form-group').removeClass('has-error')
        $("#inputTo").closest('.divInputTo').find('.help-block').hide();
        $("#inputTo").prev('.input-group-addon').css("background-color","red");
      }else if (val == "email") {
        const validateEmail = (email) => {
          return email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          )
        }

        emails = validateEmail($("#inputEmail").val())

        if ($("#inputEmail").val() == '-') {
          $("#inputEmail").closest('.form-group').removeClass('has-error')
          $("#inputEmail").closest('input').next('span').hide()
          $("#inputEmail").prev('.input-group-addon').css("background-color","red")
        }else{
          switch(emails){
            case null:
              $("#inputEmail").closest('.form-group').addClass('has-error')
              $("#inputEmail").closest('input').next('span').show();
              $("#inputEmail").prev('.input-group-addon').css("background-color","red");
              $("#inputEmail").closest('input').next('span').text("Enter a Valid Email Address!")
            break;
            default:
              $("#inputEmail").closest('.form-group').removeClass('has-error')
              $("#inputEmail").closest('input').next('span').hide()
              $("#inputEmail").prev('.input-group-addon').css("background-color","red")
          }
        }
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

      if (val == "selectLeadId") {
        $("#selectLeadId").closest('.form-group').removeClass('has-error')
        $("#selectLeadId").closest('select').next('span').next("span").hide(); 
        $("#selectLeadId").prev('.col-md-6').css("background-color","red");
      }

      if (val == "selectPID") {
        $("#selectPid").closest('.form-group').removeClass('has-error')
        $("#selectPid").closest('select').next('span').next("span").hide(); 
        $("#selectPid").prev('.col-md-6').css("background-color","red");
      }

      if (val == "selectType") {
        $("#selectType").closest('.form-group').removeClass('has-error')
        $("#selectType").closest('select').next('span').hide();
        $("#selectType").prev('.input-group-addon').css("background-color","red");
      }

      if (val == "selectCategory") {
        $("#selectCategory").closest('.form-group').removeClass('has-error')
        $("#selectCategory").closest('select').next('span').hide();
        $("#selectCategory").prev('.input-group-addon').css("background-color","red");
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
        if (localStorage.getItem('isRupiah') == 'true') {
          $("#inputTotalPrice").val(formatter.format(Math.round(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ','')))))
        }else{
          $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
        }
        $("#inputQtyProduct").closest('.col-md-4').removeClass('has-error')
        $("#inputQtyProduct").closest('input').next('span').hide();
        $("#inputQtyProduct").prev('.input-group-addon').css("background-color","red");
      }

      if (val == "type_product") {
        $("#selectTypeProduct").closest('.col-md-4').removeClass('has-error')
        $("#selectTypeProduct").closest('select').next('span').next('span').hide();
        $("#selectTypeProduct").prev('.input-group-addon').css("background-color","red");
      }

      if (val == "price_product") {
        // formatter.format($("#inputPriceProduct").val())
        if (localStorage.getItem('isRupiah') == 'true') {
          $("#inputTotalPrice").val(formatter.format(Math.round(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ','')))))
        }else{
          $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
        }
        $("#inputPriceProduct").closest('.col-md-4').removeClass('has-error')
        $("#inputPriceProduct").closest('input').closest('.input-group').next('span').hide();
        $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
      }
      if (val == "spk") {
        $("#inputSPK").closest('.form-group').removeClass('has-error')
        $("#inputSPK").closest('div').next('span').hide();
        $("#inputSPK").prev('.input-group-addon').css("background-color","red");
      }

      if (val == "sbe") {
        $("#inputSBE").closest('.form-group').removeClass('has-error')
        $("#inputSBE").closest('div').next('span').hide();
        $("#inputSBE").prev('.input-group-addon').css("background-color","red");
      }

      if (val == "quoteSupplier") {
        $("#inputQuoteSupplier").closest('.form-group').removeClass('has-error')
        $("#inputQuoteSupplier").closest('div').next('span').hide();
        $("#inputQuoteSupplier").prev('.input-group-addon').css("background-color","red");  
      }

      if (val == "quoteNumber") {
        $("#inputQuoteNumber").closest('.form-group').removeClass('has-error')
        $("#inputQuoteNumber").closest('select').next('span').next("span").hide(); 
        $("#inputQuoteNumber").prev('.input-group-addon').css("background-color","red");  
      }

      if (val == "penawaranHarga") {
        $("#inputPenawaranHarga").closest('.form-group').removeClass('has-error')
        $("#inputPenawaranHarga").closest('div').next('span').hide();
        $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");  
      }

      if (val == "textArea_TOP") {
        $("#textAreaTOP").closest('textarea').closest('div').closest('form').removeClass('has-error')
        $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').hide()  
      }

      if (val == "reason_reject") {
        $("#textAreaReasonReject").closest('.form-group').removeClass('has-error')
        $("#textAreaReasonReject").closest('textarea').next('span').hide();
        $("#textAreaReasonReject").prev('.input-group-addon').css("background-color","red"); 
      }
    }

    // var tempVat = 0
    // var finalVat = 0
    // var tempGrand = 0
    // var finalGrand = 0
    // var tempTotal = 0
    // var sum = 0
    // var btnVatStatus = true
    // var valueVat = ""
    localStorage.setItem('status_tax',false)
    function changeVatValue(value=false){
      var tempVat = 0
      var finalVat = 0
      var tempGrand = 0
      var finalGrand = 0
      var tempTotal = 0
      var sum = 0

      if (value == false) {
        valueVat = 'false'
      }else{
        valueVat = value
      }
      
      // btnVatStatus = true
      localStorage.setItem('status_tax',valueVat)

      $('.inputTotalPriceEdit').each(function() {
        var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
        sum += temp;
      });
      $("#inputGrandTotalProduct").val(formatter.format(sum))

      // $('.inputTotalPriceEdit').each(function() {
      //     var temp = parseFloat(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
      //     sum += temp;
      // });
      // $("#inputGrandTotalProduct").val(formatter.format(sum))

      if (!isNaN(valueVat)) {
        tempVat = Math.round((parseFloat(sum) * parseFloat(valueVat)) / 100)

        finalVat = tempVat

        tempGrand = Math.round((parseFloat(sum) +  parseFloat((parseFloat(sum) * parseFloat(valueVat)) / 100)))

        finalGrand = tempGrand

        tempTotal = sum

        $('.title_tax').text(valueVat + '%')
      }else{
        tempVat = 0

        tempGrand = sum

        $('.title_tax').text("")
      }

      $("#vat_tax").val(formatter.format(tempVat))

      $("#inputGrandTotalProductFinal").val(formatter.format(tempGrand))
      // if($("#btn-vat").hasClass('btn-default')){
      //   btnVatStatus = false
      //   finalVat = 0 
      //   finalGrand = tempTotal
      //   $("#btn-vat").removeClass('btn-default')
      //   $("#btn-vat").addClass('btn-danger')
      //   $("#btn-vat").text('✖')
      //   $("#vat_tax").val(0)
      //   $("#inputGrandTotalProductFinal").val(formatter.format(tempTotal))
      //   localStorage.setItem('status_tax','False')

      // } else {
      //   btnVatStatus = true
      //   finalVat = tempVat
      //   finalGrand = tempGrand
      //   $("#btn-vat").addClass('btn-default')
      //   $("#btn-vat").removeClass('btn-danger')
      //   $("#btn-vat").text('✓')
      //   $("#vat_tax").val(formatter.format(tempVat))
      //   $("#inputGrandTotalProductFinal").val(formatter.format(tempGrand))
      //   localStorage.setItem('status_tax','True')
      // }
    }
    localStorage.setItem("isRupiah",true)
    function changeCurreny(value){
      if (value == "usd") {
        $("#inputPriceProduct").closest("div").find(".input-group-addon").text("$")
        $("#inputTotalPrice").closest("div").find("div").text("$")
        localStorage.setItem("isRupiah",false)
        $('.money').mask('#0,00', {reverse: true})

        // $(".money").mask('000.000.000.000.000', {reverse: true})
      }else{
        $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
        $("#inputTotalPrice").closest("div").find("div").text("Rp.")

        localStorage.setItem("isRupiah",true)

        $('.money').mask('#.##0,00', {reverse: true})
      }

      if (localStorage.getItem('isRupiah') == 'true') {
        $("#inputTotalPrice").val(formatter.format(Math.round(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ','')))))
      }else{
        $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
      }
    }

    currentTab = 0
    var isStartScroll = false
    function nextPrevUnFinished(n,valueEdit){
      if(localStorage.getItem('status_draft_pr') == 'pembanding'){
        url = "{{url('/admin/getDetailPr')}}"
        urlDokumen = "{{url('/admin/storePembandingDokumen')}}"
        urlProduct = "{{url('/admin/storePembandingProduct')}}"
        urlGetProduct = "{{url('/admin/getProductPembanding')}}"
        no_pr = localStorage.getItem("id_compare_pr")
      }else{
        url = "{{url('/admin/getPreviewPr')}}"
        urlDokumen = "{{url('/admin/storeDokumen')}}"
        urlProduct = "{{url('/admin/storeProductPr')}}"
        urlGetProduct = "{{url('/admin/getProductPr')}}"
        no_pr = localStorage.getItem("no_pr")
      }

      if (valueEdit == undefined) {
        if (valueEdit == 0) {
          $(".tabGroupInitiateAdd").hide()
          $(".tab-add")[1].children[1].style.display = 'inline'
        }
      }else{
        valueEdit = valueEdit
        if (valueEdit == true) {
          valueEdit = 'true'
        }else if (valueEdit == false) {
          valueEdit = 'false'
        }else{
          valueEdit = parseFloat(valueEdit)
        }

        //ini false kalau nilainya angka
        if (!isNaN(valueEdit)) {
          $(".tabGroupInitiateAdd").hide()
          $(".tab-add")[1].children[1].style.display = 'inline'
          $.ajax({
            type: "GET",
            url: "{{url('/admin/getProductById')}}",
            data: {
              id_product:valueEdit,
            },
            success: function(result) {
              $.each(result,function(value,item){
                isStartScroll = false

                $("#prevBtnAdd").css("display", "none");
                localStorage.setItem('isEditProduct',true)
                localStorage.setItem('id_product',item.id_product)
                nominal = item.nominal_product
                $("#inputNameProduct").val(item.name_product)
                $("#inputDescProduct").val(item.description.replaceAll("<br>","\n"))
                $("#inputQtyProduct").val(item.qty)
                select2TypeProduct(item.unit)
                $("#inputPriceProduct").val(formatter.format(nominal))
                $("#inputSerialNumber").val(item.serial_number)
                $("#inputPartNumber").val(item.part_number)
                $("#inputTotalPrice").val(formatter.format(item.grand_total))
                if (item.isRupiah == "false") {
                  $("#inputPriceProduct").closest("div").find(".input-group-addon").text("$")
                }else{
                  $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
                }
              })
            }
          })
          // $.ajax({
          //   type: "GET",
          //   url: urlGetProduct,
          //   data: {
          //     no_pr:no_pr,
          //   },
          //   success: function(result) {
          //     $.each(result.data,function(value,item){
          //       $("#prevBtnAdd").css("display", "none");
          //       localStorage.setItem('isEditProduct',true)
          //       localStorage.setItem('id_product',result.data[valueEdit].id_product)
          //       nominal = result.data[valueEdit].nominal_product
          //       $("#inputNameProduct").val(result.data[valueEdit].name_product)
          //       $("#inputDescProduct").val(result.data[valueEdit].description.replaceAll("<br>","\n"))
          //       $("#inputQtyProduct").val(result.data[valueEdit].qty)
          //       select2TypeProduct(result.data[valueEdit].unit)
          //       $("#inputPriceProduct").val(formatter.format(nominal))
          //       $("#inputSerialNumber").val(result.data[valueEdit].serial_number)
          //       $("#inputPartNumber").val(result.data[valueEdit].part_number)
          //       $("#inputTotalPrice").val(formatter.format(result.data[valueEdit].grand_total))
          //     })
          //   }
          // })
        }
      }

      if (currentTab == 0) {
        // if ($("#selectTo").val() == "") {
        //   $("#selectTo").closest('.form-group').addClass('has-error')
        //   $("#selectTo").closest('.form-group').find('.help-block').show()
        //   $("#selectTo").css("background-color","red");
        //   if ($("#inputTo").val() == "") {
        //     $("#inputTo").closest('.form-group').addClass('has-error')
        //     $("#inputTo").closest('input').next('span').show();
        //     $("#inputTo").prev('.input-group-addon').css("background-color","red");
        //   }
        // }else 
        isStartScroll = true
        if ($("#selectType").val() == "") {
          $("#selectType").closest('.form-group').addClass('has-error')
          $("#selectType").closest('select').next('span').show();
          $("#selectType").prev('.input-group-addon').css("background-color","red");
        }else if ($("#inputEmail").val() == "") {
          $("#inputEmail").closest('.form-group').addClass('has-error')
          $("#inputEmail").closest('input').next('span').show();
          $("#inputEmail").prev('.input-group-addon').css("background-color","red");
          $("#inputEmail").closest('input').next('span').text("Please fill an Email!")
        }else if ($("#selectCategory").val() == "") {
          $("#selectCategory").closest('.form-group').addClass('has-error')
          $("#selectCategory").closest('select').next('span').show();
          $("#selectCategory").prev('.input-group-addon').css("background-color","red");
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
        }else if($("#selectMethode").val() == ""){
          $("#selectMethode").closest('.form-group').addClass('has-error')
          $("#selectMethode").closest('select').next('span').show();
          $("#selectMethode").prev('.input-group-addon').css("background-color","red");
        }else{
          let commitValue = ''
          if ($("#cbCommit").is(':checked')){
            commitValue = 'True'
          }else{
            commitValue = 'False'
          }

          let inputTo = ""
          if ($("#selectTo").val() == ""  || $('#selectTo').val() == null) {
            inputTo = $("#inputTo").val()
          }else{
            inputTo = $("#selectTo").val()
          }

          $.ajax({
            type:"POST",
            url:"{{url('/admin/updateSupplier/')}}",
            data:{
              _token:"{{ csrf_token() }}",
              inputTo:inputTo,
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
              cbCommit:commitValue,
              no_pr:localStorage.getItem('no_pr')
            },beforeSend:function(){
              Swal.fire({
                  title: 'Please Wait..!',
                  text: "It's saving..",
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
              Swal.close()
              let x = document.getElementsByClassName("tab-add");
              x[currentTab].style.display = "none";
              currentTab = currentTab + n;
              if (currentTab >= x.length) {
                x[n].style.display = "none";
                currentTab = 0;
              }
              unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
            }
          })          
        }         
      }else if (currentTab == 1) {
        isStartScroll = true
        
        if (($(".tab-add")[1].children[1].style.display == 'inline' ) == true) {
          if (n == 1) {
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
              $("#selectTypeProduct").closest('select').next('span').next('span').show();
              $("#selectTypeProduct").prev('.input-group-addon').css("background-color","red");
            }else if ($("#inputPriceProduct").val() == "") {
              $("#inputPriceProduct").closest('.col-md-4').addClass('has-error')
              $("#inputPriceProduct").closest('input').closest('.input-group').next('span').show();
              $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
            }else{
              if (localStorage.getItem('isEditProduct') == 'true') {
                $.ajax({
                  url: "{{url('/admin/updateProductPr')}}",
                  type: 'post',
                  data: {
                   _token:"{{ csrf_token() }}",
                   id_product:localStorage.getItem('id_product'),
                   inputNameProduct:$("#inputNameProduct").val(),
                   inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
                   inputQtyProduct:$("#inputQtyProduct").val(),
                   selectTypeProduct:$("#selectTypeProduct").val(),
                   inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputTotalPrice:$("#inputTotalPrice").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputSerialNumber:$("#inputSerialNumber").val(),
                   inputPartNumber:$("#inputPartNumber").val(),
                   inputGrandTotalProduct:$("#inputFinalPageTotalPrice").val(),
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
                    let x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                    localStorage.setItem('isEditProduct',false)
                    $(".tabGroupInitiateAdd").show()
                    $(".tab-add")[1].children[1].style.display = 'none'
                    document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex' 
                    $("#inputNameProduct").val('')
                    $("#inputDescProduct").val('')
                    $("#inputPriceProduct").val('')
                    $("#inputQtyProduct").val('')
                    $("#inputSerialNumber").val('')
                    $("#inputPartNumber").val('')
                    $("#inputTotalPrice").val('')
                  }
                })
              }else{
                $.ajax({
                  url: urlProduct,
                  type: 'post',
                  data: {
                   _token:"{{ csrf_token() }}",
                   no_pr:no_pr,
                   inputNameProduct:$("#inputNameProduct").val(),
                   inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
                   inputQtyProduct:$("#inputQtyProduct").val(),
                   selectTypeProduct:$("#selectTypeProduct").val(),
                   inputSerialNumber:$("#inputSerialNumber").val(),
                   inputPartNumber:$("#inputPartNumber").val(),
                   inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputTotalPrice:$("#inputTotalPrice").val().replace(/\./g,'').replace(',','.').replace(' ',''),
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
                    let x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                    $(".tabGroupInitiateAdd").show()
                    $(".tab-add")[1].children[1].style.display = 'none'
                    document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                    $("#inputNameProduct").val('')
                    $("#inputDescProduct").val('')
                    $("#inputPriceProduct").val('')
                    $("#inputQtyProduct").val('')
                    $("#inputSerialNumber").val('')
                    $("#inputPartNumber").val('')
                    $("#inputTotalPrice").val('')
                  }
                })
              }               
            } 
          }else{
            $(".tabGroupInitiateAdd").show()
            $(".tab-add")[1].children[1].style.display = 'none'
            document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex' 
          }
           
        }else{

          if ($('#uploadCsv').val() == "") {
            let x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
          }else{
            var dataForm = new FormData();
            dataForm.append('csv_file',$('#uploadCsv').prop('files')[0]);
            dataForm.append('_token','{{ csrf_token() }}');
            dataForm.append('no_pr',localStorage.getItem('no_pr'));

            $.ajax({
              processData: false,
              contentType: false,
              url: "{{url('/admin/uploadCSV')}}",
              type: 'POST',
              data: dataForm,
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
              },success:function(result){
                Swal.close()
                cancelUploadCsv()

                if (result.status == "Error") {
                  reasonReject(result.text,"block","tabGroupInitiateAdd")
                }else{
                  let x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                  $(".divReasonRejectRevision").remove()
                  //nge reset upload csv
                  // cancelUploadCsv()
                }
              }
            })
          }
        }             
      }else if (currentTab == 3) {
        $("#btnAddDocPendukung_epr").prop("disabled",false)
        $("#btnAddDocPendukung_ipr").prop("disabled",false)
        if (n == 1) {
          if ($("#selectType").val() == 'IPR') {
            $.ajax({
                type: "GET",
                url: url,
                data: {
                  no_pr:localStorage.getItem('no_pr'),
                },
                success:function(result){
                  let formData = new FormData();
                  const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
                  var arrInputDocPendukung = []

                  if (result.dokumen.length > 0) {
                    if ($('#inputPenawaranHarga').prop('files')[0].name.replace("/","") != result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1]) {
                      formData.append('inputPenawaranHarga', filepenawaranHarga)
                    } else {
                      formData.append('inputPenawaranHarga', '-')
                    }

                    if (result.dokumen[1] != undefined) {
                      if (!(result.dokumen.slice(1).length == $('#tableDocPendukung_ipr .trDocPendukung').length)) {
                        $('#tableDocPendukung_ipr .trDocPendukung').slice(result.dokumen.slice(1).length).each(function(){
                          var fileInput = $(this).find('#inputDocPendukung').prop('files').length
                          if (fileInput == 0) { 
                            

                            formData.append('inputDocPendukung[]','-')
                          }else{
                          

                            formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                            arrInputDocPendukung.push({
                              nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                              no_pr:no_pr
                            })
                          }
                        })

                      }else{
                        
                        var fileInput = $(this).find('#inputDocPendukung').val()
                        if (fileInput && fileInput !== '') { 
                          formData.append('inputDocPendukung[]','-')
                        }
                      }  
                    }else{
                      $('#tableDocPendukung_ipr .trDocPendukung').each(function() {
                        var fileInput = $(this).find('#inputDocPendukung').val()
                        if (fileInput && fileInput !== '') { 
                          formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                          arrInputDocPendukung.push({
                            nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                            no_pr:no_pr
                          })
                        }
                      });
                    }
                                    
                  }else{
                    formData.append('inputPenawaranHarga', filepenawaranHarga);
                    $('#tableDocPendukung_ipr .trDocPendukung').each(function() {
                      var fileInput = $(this).find('#inputDocPendukung').val()
                      if (fileInput && fileInput !== '') { 
                        formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                        arrInputDocPendukung.push({
                          nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                          no_pr:no_pr
                        }) 
                      }
                    });
                    
                  }               

                  formData.append('_token',"{{csrf_token()}}")
                  formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukung))
                  formData.append('no_pr',no_pr)

                  function storeIPR(urlDokumen,formData){
                    if (n == 1) {
                      $.ajax({
                        url: urlDokumen,
                        type: 'post',
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
                              didOpen: () => {
                                  Swal.showLoading()
                              }
                          })
                        },
                        success: function(data)
                        {
                          Swal.close()
                          let x = document.getElementsByClassName("tab-add");
                          x[currentTab].style.display = "none";
                          currentTab = currentTab + n;
                          if (currentTab >= x.length) {
                            x[n].style.display = "none";
                            currentTab = 0;
                          }
                          unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                        }
                      });
                    }else{
                      let x = document.getElementsByClassName("tab-add");
                      x[currentTab].style.display = "none";
                      currentTab = currentTab + n;
                      if (currentTab >= x.length) {
                        x[n].style.display = "none";
                        currentTab = 0;
                      }
                      unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                    } 
                  }

                  if ($("#inputPenawaranHarga").val() == "") {
                    $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
                    $("#inputPenawaranHarga").closest('div').next('span').show();
                    $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red"); 
                  }else if($("#tableDocPendukung_ipr .trDocPendukung").length > 0){
                    
                    if (result.dokumen[1] != undefined) {
                      if (!(result.dokumen.slice(1).length == $('#tableDocPendukung_ipr .trDocPendukung').length)) {
                        $('#tableDocPendukung_ipr .trDocPendukung').slice(result.dokumen.slice(1).length).each(function(){
                          if ($(this).find('.inputDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() != "") {
                            if ($(this).find('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() == "") {
                              
                              $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).next('span').show()
                              
                              $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).css("border-color","red");
                            }else{
                              storeIPR(urlDokumen,formData)
                            }
                          }else{
                            storeIPR(urlDokumen,formData)
                          }
                        })
                      }else{
                        
                        var fileInput = $(this).find('#inputDocPendukung').val()
                        if (fileInput && fileInput !== '') { 
                          formData.append('inputDocPendukung[]','-')
                        }

                        storeIPR(urlDokumen,formData)
                      }  
                    }else{
                      $('#tableDocPendukung_ipr .trDocPendukung').slice(result.dokumen.slice(1).length).each(function(){
                        if ($(this).find('.inputDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() != "") {
                          if ($(this).find('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() == "") {
                            
                            $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).next('span').show()
                            
                            $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).css("border-color","red");
                          }else{
                            storeIPR(urlDokumen,formData)
                          }
                        }else{
                          storeIPR(urlDokumen,formData)
                        }
                      })
                    }
                  }else{
                    storeIPR(urlDokumen,formData)
                  }               
                }
            })
          }else{
            if ($("#projectIdInputNew").is(":visible") == false) {
              if ($("#selectPid").val() == "") {
                $("#selectPid").closest('.form-group').addClass('has-error')
                $("#selectPid").closest('select').next('span').next("span").show(); 
                $("#selectPid").prev('.col-md-6').css("background-color","red");
              }
            }

            if ($("#selectLeadId").val() == "") {
              $("#selectLeadId").closest('.form-group').addClass('has-error')
              $("#selectLeadId").closest('select').next('span').next("span").show(); 
              $("#selectLeadId").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputQuoteNumber").val() == "") {
              $("#inputQuoteNumber").closest('.form-group').addClass('has-error')
              $("#inputQuoteNumber").closest('select').next('span').next("span").show(); 
              $("#inputQuoteNumber").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputQuoteSupplier").val() == "") {
              $("#inputQuoteSupplier").closest('.form-group').addClass('has-error')
              $("#inputQuoteSupplier").closest('div').next('span').show();
              $("#inputQuoteSupplier").prev('.input-group-addon').css("background-color","red");
            }else if ($("#inputSPK").val() == "") {
              $("#inputSPK").closest('.form-group').addClass('has-error')
              $("#inputSPK").closest('div').next('span').show();
              $("#inputSPK").prev('.input-group-addon').css("background-color","red");
            }else if ($("#inputSBE").val() == "") {
              $("#inputSBE").closest('.form-group').addClass('has-error')
              $("#inputSBE").closest('div').next('span').show();
              $("#inputSBE").prev('.input-group-addon').css("background-color","red");
            }else{
              $.ajax({
                type: "GET",
                url: url,
                data: {
                  no_pr:localStorage.getItem('no_pr'),
                },
                success:function(result){
                  let formData = new FormData();

                  const fileSpk = $('#inputSPK').prop('files')[0];
                  var nama_file_spk = $('#inputSPK').val();

                  const fileQuoteSupplier = $('#inputQuoteSupplier').prop('files')[0];
                  var nama_file_quote_supplier = $('#inputQuoteSupplier').val();

                  const fileSbe = $('#inputSBE').prop('files')[0];
                  var nama_file_sbe = $('#inputSBE').val();          

                  if (result.dokumen.length > 0) {
                    if (result.dokumen[0] !== undefined) {
                      if (result.dokumen[0].dokumen_location != $('#inputQuoteSupplier').prop('files')[0].name.replace("/","") || $('#inputQuoteSupplier').prop('files').length == 0) {
                        formData.append('inputQuoteSupplier', fileQuoteSupplier);
                      } else {
                        formData.append('inputQuoteSupplier', "-");
                      }
                    }else{
                        formData.append('inputQuoteSupplier', fileQuoteSupplier);
                    }

                    if (result.dokumen[1] !== undefined) {
                      if (result.dokumen[1].dokumen_location != $('#inputSPK').prop('files')[0].name.replace("/","") || $('#inputSPK').prop('files').length == 0) {
                        formData.append('inputSPK', fileSpk);
                      } else {
                        formData.append('inputSPK', "-");
                      }
                    }else{
                      formData.append('inputSPK', fileSpk);
                    }                  

                    if (result.dokumen[2] !== undefined) {
                      if (result.dokumen[2].dokumen_location != $('#inputSBE').prop('files')[0].name.replace("/","") || $('#inputSBE').prop('files').length == 0) {
                        formData.append('inputSBE', fileSbe);
                      } else {
                        formData.append('inputSBE', "-");
                      }
                    }else{
                        formData.append('inputSBE', fileSbe);
                    }

                  }else{
                    formData.append('inputSPK', fileSpk);
                    formData.append('inputQuoteSupplier', fileQuoteSupplier);
                    formData.append('inputSBE', fileSbe);
                  }

                  formData.append('_token',"{{csrf_token()}}")
                  formData.append('no_pr',no_pr)
                  formData.append('selectLeadId', $("#selectLeadId").val())
                  formData.append('selectPid', $("#selectPid").val())
                  formData.append('inputPid',$("#projectIdInputNew").val())
                  formData.append('selectQuoteNumber', $("#selectQuoteNumber").val())

                  function storeEPR(urlDokumen,formData){
                    if(n == 1){
                      $.ajax({
                        type:"POST",
                        url:urlDokumen,
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
                          Swal.close()
                          let x = document.getElementsByClassName("tab-add");
                          x[currentTab].style.display = "none";
                          currentTab = currentTab + n;
                          if (currentTab >= x.length) {
                            x[n].style.display = "none";
                            currentTab = 0;
                          }
                          unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                        }
                      })
                    } else {
                      let x = document.getElementsByClassName("tab-add");
                      x[currentTab].style.display = "none";
                      currentTab = currentTab + n;
                      if (currentTab >= x.length) {
                        x[n].style.display = "none";
                        currentTab = 0;
                      }
                      unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                    }
                  }
                  
                  arrInputDocPendukungEPR = []
                  if($("#tableDocPendukung_epr .trDocPendukung").length > 0){
                      if (!(result.dokumen.slice(3).length == $('#tableDocPendukung_epr .trDocPendukung').length)) {

                        $('#tableDocPendukung_epr .trDocPendukung').slice(result.dokumen.slice(3).length).each(function(){
                          if ($(this).find('.inputDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() != "") {
                            if ($(this).find('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() == "") {
                              
                              $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).next('span').show()
                              
                              $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).css("border-color","red");
                            }else{
                              arrInputDocPendukungEPR.push({
                                nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                                no_pr:no_pr
                              })

                              formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukungEPR))
                              formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0]) 
                              // arrInputDocPendukungEPR.push({
                              //   nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                              //   no_pr:no_pr
                              // })

                              // formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukungEPR))
                              // formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                              // storeEPR(urlDokumen,formData)
                            }
                          }else{
                            // storeEPR(urlDokumen,formData)
                          }
                        })
                      }else{
                        // arrInputDocPendukungEPR.push({
                        //   nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                        //   no_pr:no_pr
                        // })
                        formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukungEPR))
                        formData.append('inputDocPendukung[]','-')

                        // storeEPR(urlDokumen,formData)
                      } 


                  }else{
                    formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukungEPR))
                    formData.append('inputDocPendukung[]','-')                    
                  }
                  storeEPR(urlDokumen,formData)
                  // if (result.dokumen.length > 0) {
                  //   if (!(result.dokumen.slice(3).length == $('#tableDocPendukung_epr .trDocPendukung').length)) {
                  //     $('#tableDocPendukung_epr .trDocPendukung').slice(result.dokumen.slice(3).length).each(function(){
                  //         

                  //       if ($(this).find('.inputDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() != "") {
                  //         if ($(this).find('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val() == "") {
                  //           
                  //           $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).next('span').show()
                  //           
                  //           $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).css("border-color","red");
                  //         }else{
                  //           
                  //           formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                  //           arrInputDocPendukung.push({
                  //             nameDocPendukung:$(this).find('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).val(),
                  //             no_pr:no_pr
                  //           })
                  //           storeEPR(urlDokumen,formData)
                  //         }
                  //       }else{
                  //         
                  //         storeEPR(urlDokumen,formData)
                  //       }
                        
                  //     })

                  //   }else{
                  //     var fileInput = $(this).find('#inputDocPendukung').prop('files').length
                  //     if (fileInput == 0) { 
                  //       

                  //       formData.append('inputDocPendukung[]','-')
                  //       storeEPR(urlDokumen,formData)
                  //     }

                  //   }                                 
                  // }else{
                  //   $('#tableDocPendukung_epr .trDocPendukung').each(function() {
                  //     var fileInput = $(this).find('#inputDocPendukung').prop('files').length
                  //     if (fileInput !== 0) { 

                  //       formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                  //       arrInputDocPendukung.push({
                  //         nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                  //         no_pr:no_pr
                  //       })

                  //       storeEPR(urlDokumen,formData)
                  //     }else{
                  //       formData.append('inputDocPendukung[]','-')

                  //       storeEPR(urlDokumen,formData)
                  //     }
                  //   })
                  // } 
                          
                }
              })            
            } 
          }
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          
          unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
        }
      }else if (currentTab == 4) {
        if (n == 1) {
          if ($("#textAreaTOP").val() == "") {
            $("#textAreaTOP").closest('textarea').closest('div').closest('form').addClass('has-error')
            $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').show()
          }else{
            $("#textAreaTOP").closest('textarea').closest('div').closest('form').removeClass('has-error')
            $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').hide()

            $.ajax({
              url: "{{'/admin/storeTermPayment'}}",
              type: 'post',
              data:{
                no_pr:localStorage.getItem('no_pr'),
                _token:"{{csrf_token()}}",
                textAreaTOP:$("#textAreaTOP").val(),
              },
              success: function(data)
              {
                let x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                  x[n].style.display = "none";
                  currentTab = 0;
                }
                unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
              }
            }); 
          }
        }else{
          let x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
        }
                
      }else{
        $.ajax({
          type:"POST",
          url:"{{url('/admin/storeTax')}}",
            data:{
              _token:"{{csrf_token()}}",
              no_pr:localStorage.getItem('no_pr'),
              isRupiah:localStorage.getItem('isRupiah'),
              status_tax:localStorage.getItem('status_tax'),
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
              Swal.close()
              $(".divReasonRejectRevision").remove()
              let x = document.getElementsByClassName("tab-add");
              x[currentTab].style.display = "none";
              currentTab = currentTab + n;
              if (currentTab >= x.length) {
                x[n].style.display = "none";
                currentTab = 0;
              }

              unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
          }
        })
      }
    }

    function addTable(n,status){ 
      if (window.location.href.split("/")[6] == undefined) {
        if (localStorage.getItem('status_pr') == 'revision') {
          url = "{{url('/admin/getProductPembanding')}}"
          no_pr = localStorage.getItem('id_compare_pr')
        }else{
          url = "{{url('/admin/getProductPr')}}"
          no_pr = localStorage.getItem('no_pr')
        }
      }else{
        url = "{{url('/admin/getProductPembanding')}}"
        no_pr = localStorage.getItem('no_pembanding')
      }
      $.ajax({
        type: "GET",
        url: url,
        data: {
          no_pr:no_pr,
        },
        success: function(result) {
          var i = 0
          var valueEdit = 0
          var append = ""
          $("#tbodyProducts").empty()
          $.each(result.data,function(value,item){
             i++;
             valueEdit++;
             append = append + '<tr>'
              append = append + '<td>'
                append = append + '<span style="font-size: 12px; important">'+ i +'</span>'
              append = append + '</td>'
              append = append + '<td width="20%">'
                append = append + "<input id='inputNameProductEdit' data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name_product + "'>"
              append = append + '</td>'
              append = append + '<td width="30%">'
                append = append + '<textarea id="textAreaDescProductEdit" readonly data-value="" style="font-size: 12px; important;resize:none;height:150px;" class="form-control">'+ item.description.replaceAll("<br>","\n") + '&#10;&#10;SN : ' + item.serial_number + '&#10;PN : ' + item.part_number 
                append = append + '</textarea>'
              append = append + '</td>'
              append = append + '<td width="7%">'
                append = append + '<input id="inputQtyEdit" data-value="" readonly style="font-size: 12px; important;width:70px" class="form-control" type="number" name="" value="'+ item.qty +'">'
              append = append + '</td>'
              append = append + '<td width="10%">'
              append = append + '<select id="inputTypeProductEdit" disabled data-value="" style="font-size: 12px; important;width:70px" class="form-control">'
              append = append + '<option>'+ item.unit.charAt(0).toUpperCase() + item.unit.slice(1) +'<option>'
              append = append + '</select>' 
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input id="inputPriceEdit" readonly data-value="" style="font-size: 12px;width:100px" class="form-control money" type="" name="" value="'+ formatter.format(item.nominal_product) +'">'
              append = append + '</td>'
              append = append + '<td width="15%">'
                append = append + '<input id="inputTotalPriceEdit" readonly data-value="" style="font-size: 12px;width:100px" class="form-control inputTotalPriceEdit" type="" name="" value="'+ formatter.format(item.grand_total) +'">'
              append = append + '</td>'
              append = append + '<td width="8%">'
                if (localStorage.getItem('status_pr') == 'draft') {
                  btnNext = 'nextPrevAdd(-1,'+ item.id_product +')'
                }else{
                  btnNext = 'nextPrevUnFinished(-1,'+ item.id_product +')'
                }
                append = append + '<button type="button" onclick="'+ btnNext +'" id="btnEditProduk" data-id="'+ value +'" data-value="'+ valueEdit +'" class="btn btn-xs btn-warning fa fa-edit btnEditProduk" style="width:25px;height:25px;margin-bottom:5px"></button>'
                append = append + '<button id="btnDeleteProduk" type="button" data-id="'+ item.id_product +'" data-value="'+ value +'" class="btn btn-xs btn-danger fa fa-trash" style="width:25px;height:25px"></button>'
              append = append + '</td>'
            append = append + '</tr>'   
          })    

          $("#tbodyProducts").append(append)

          scrollTopModal()

          $("#bottomProducts").empty()

          var appendBottom = ""
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<div class="row">'
            appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
            appendBottom = appendBottom + '    <div class="pull-right">'
            appendBottom = appendBottom + '      <span style="display: inline;margin-right: 15px;">Total</span>'
            appendBottom = appendBottom + '      <input readonly="" type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProduct" id="inputGrandTotalProduct" name="inputGrandTotalProduct">'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
            appendBottom = appendBottom + '<div class="col-md-12 col-xs-12">'
            appendBottom = appendBottom + ' <div class="pull-right">'
            appendBottom = appendBottom + '  <span style="margin-right: 15px;">Vat <span class="title_tax"></span>'
            appendBottom = appendBottom + '  </span>'
            appendBottom = appendBottom + '  <div class="input-group" style="display: inline-flex;">'
            appendBottom = appendBottom + '   <input readonly="" type="text" class="form-control vat_tax" id="vat_tax" name="vat_tax" style="width:217px;display:inline">'
            appendBottom = appendBottom + '  <div class="input-group-btn">'
            appendBottom = appendBottom + '       <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
            appendBottom = appendBottom + '         <span class="fa fa-caret-down"></span>'
            appendBottom = appendBottom + '       </button>'
            appendBottom = appendBottom + '       <ul class="dropdown-menu">'
            appendBottom = appendBottom + '       <li>'
            appendBottom = appendBottom + '        <a onclick="changeVatValue(false)">Without Vat</a>'
            appendBottom = appendBottom + '       </li>'
            appendBottom = appendBottom + '       <li>'
            appendBottom = appendBottom + '        <a onclick="changeVatValue(11)">Vat 11%</a>'
            appendBottom = appendBottom + '       </li>'
            appendBottom = appendBottom + '       <li>'
            appendBottom = appendBottom + '        <a onclick="changeVatValue('+ parseFloat(1.1) +')">Vat 1,1 %</a>'
            appendBottom = appendBottom + '       </li>'
            appendBottom = appendBottom + '      </ul>'
            appendBottom = appendBottom + '     </div>'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
            appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
            appendBottom = appendBottom + '    <div class="pull-right">'
            appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
            appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductFinal" id="inputGrandTotalProductFinal" name="inputGrandTotalProductFinal">'
            appendBottom = appendBottom + '    </div>'
            appendBottom = appendBottom + '  </div>'
          appendBottom = appendBottom + '</div>'

          $("#bottomProducts").append(appendBottom)

          $(document).on("click", "#btnDeleteProduk", function() {
            Swal.fire({
              title: 'Are you sure?',  
              text: "Deleting Product",
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
                  url: "{{url('/admin/deleteProduct')}}",
                  data:{
                    _token:'{{ csrf_token() }}',
                    id:$(this).data("id")
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
                  success: function(result) {
                    Swal.fire(
                        'Successfully!',
                        'Delete Product.',
                        'success'
                    ).then((result) => {
                      refreshTable()
                    })
                  }
                })          
              }
            })
          })

          $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
          })

          if (status != "") {
            
            changeVatValue(status)
          }

          // var sum = 0
          // $('.inputTotalPriceEdit').each(function() {
          //     var temp = parseFloat(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
          //     sum += temp;
          // });

          // $("#inputGrandTotalProduct").val(formatter.format(sum))



          // // tempVat = (parseFloat(sum) * 11) / 100

          // // finalVat = tempVat

          // tempGrand = parseFloat(sum)

          // // finalGrand = tempGrand

          // // tempTotal = sum
          // $("#vat_tax").val(0)

          // $("#inputGrandTotalProductFinal").val(formatter.format(tempGrand))
        }
      })
    }

    function scrollTopModal(){
      var savedScrollPosition = localStorage.getItem('scrollPosition');
      var scrollableElement = document.getElementById('ModalDraftPr');
      scrollableElement.scrollTop = savedScrollPosition;
    }

    $("#ModalDraftPr").on('scroll', function() {
      if (isStartScroll == true) {
        var scrollPosition = $("#ModalDraftPr").scrollTop();
        localStorage.setItem('scrollPosition', scrollPosition);
      }
      // Update the scroll position variable with the latest scroll position
      // If a saved scroll position exists, set the scroll position to the saved value
    })

    var isFilledPenawaranHarga = true
    var isFilledDocPendukung = true
    var arrInputDocPendukung = []

    var nama_file_sbe = ""
    var nama_file_spk = ""
    var nama_file_quote_supplier = ""

    function nextPrevAdd(n,value) {
      valueEdit = value
      if (valueEdit == undefined) {
        if (valueEdit == 0) {
          $(".tabGroupInitiateAdd").hide()
          $(".tab-add")[1].children[1].style.display = 'inline'
        }
      }else{
        valueEdit = valueEdit
        if (valueEdit == true) {
          valueEdit = 'true'
        }else if (valueEdit == false) {
          valueEdit = 'false'
        }else{
          valueEdit = parseFloat(valueEdit)
        }
        if (!isNaN(valueEdit)) {
          $(".tabGroupInitiateAdd").hide()
          $(".tab-add")[1].children[1].style.display = 'inline'
          $.ajax({
            type: "GET",
            url: "{{url('/admin/getProductById')}}",
            data: {
              id_product:valueEdit,
            },
            success: function(result) {
              $.each(result,function(value,item){
                $("#prevBtnAdd").css("display", "none");
                localStorage.setItem('isEditProduct',true)
                localStorage.setItem('id_product',item.id_product)
                nominal = item.nominal_product
                $("#inputNameProduct").val(item.name_product)
                $("#inputDescProduct").val(item.description.replaceAll("<br>","\n"))
                $("#inputQtyProduct").val(item.qty)
                select2TypeProduct(item.unit)
                $("#inputPriceProduct").val(formatter.format(nominal))
                $("#inputSerialNumber").val(item.serial_number)
                $("#inputPartNumber").val(item.part_number)
                $("#inputTotalPrice").val(formatter.format(item.grand_total))
                if (item.isRupiah == "false") {
                  $("#inputPriceProduct").closest("div").find(".input-group-addon").text("$")
                }else{
                  $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
                }
              })
            }
          })          
        }
      }

      if (currentTab == 0) {
        // if ($("#selectTo").val() == "") {
        //   if ($("#inputTo").val() != "") {
        //     if ($("#inputTo").val() == "") {
        //       $("#inputTo").closest('.form-group').addClass('has-error')
        //       $("#inputTo").closest('input').next('span').show();
        //       $("#inputTo").prev('.input-group-addon').css("background-color","red");
        //     }
        //   }else{
        //     $("#selectTo").closest('.form-group').addClass('has-error')
        //     $("#selectTo").closest('.form-group').find('.help-block').show()
        //     $("#selectTo").css("background-color","red");
        //   }
        // }else 
        if ($("#selectType").val() == "") {
          $("#selectType").closest('.form-group').addClass('has-error')
          $("#selectType").closest('select').next('span').show();
          $("#selectType").prev('.input-group-addon').css("background-color","red");
        }else if ($("#inputEmail").val() == "") {
          $("#inputEmail").closest('.form-group').addClass('has-error')
          $("#inputEmail").closest('input').next('span').show();
          $("#inputEmail").prev('.input-group-addon').css("background-color","red");
          $("#inputEmail").closest('input').next('span').text("Please fill an Email!")
        }else if ($("#selectCategory").val() == "") {
          $("#selectCategory").closest('.form-group').addClass('has-error')
          $("#selectCategory").closest('select').next('span').show();
          $("#selectCategory").prev('.input-group-addon').css("background-color","red");
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
        }else if($("#selectMethode").val() == ""){
          $("#selectMethode").closest('.form-group').addClass('has-error')
          $("#selectMethode").closest('select').next('span').show();
          $("#selectMethode").prev('.input-group-addon').css("background-color","red");
        }else{
          let inputTo = ""
          if ($("#selectTo").val() == "") {
            inputTo = $("#inputTo").val()
          }else{
            inputTo = $("#selectTo").val()
          }

          if (value == true) {
            isStoreSupplier = localStorage.getItem('isStoreSupplier')
            if (isStoreSupplier == 'false') {
              let commitValue = ''
              if ($("#cbCommit").is(':checked')){
                commitValue = 'True'
              }else{
                commitValue = 'False'
              }
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
                        url:"{{url('/admin/storeDraftPr')}}",
                        data:{
                          _token: "{{ csrf_token() }}",
                          inputTo:inputTo,
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
                          cbCommit:commitValue,
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
                          addDraftPr(currentTab);
                          localStorage.setItem('no_pr',result)
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
              addDraftPr(currentTab);
            }
          }else{
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            addDraftPr(currentTab);
          }
        }
      }else if (currentTab == 1) {
        if (($(".tab-add")[1].children[1].style.display == 'inline' ) == true) {
          if (n == 1) {
            if ($("#inputNameProduct").val() == "") {
              $("#inputNameProduct").closest('.form-group').addClass('has-error')
              $("#inputNameProduct").closest('input').next('span').show();
              $("#inputNameProduct").prev('.input-group-addon').css("background-color","red");
            } else if ($("#inputDescProduct").val() == "") {
              $("#inputDescProduct").closest('.form-group').addClass('has-error')
              $("#inputDescProduct").closest('textarea').next('span').show();
              $("#inputDescProduct").prev('.input-group-addon').css("background-color","red");
            } else if ($("#inputQtyProduct").val() == "") {
              $("#inputQtyProduct").closest('.col-md-4').addClass('has-error')
              $("#inputQtyProduct").closest('input').next('span').show();
              $("#inputQtyProduct").prev('.input-group-addon').css("background-color","red");
            } else if ($("#selectTypeProduct").val() == "" || $("#selectTypeProduct").val() == null) {
              $("#selectTypeProduct").closest('.col-md-4').addClass('has-error')
              $("#selectTypeProduct").closest('select').next('span').next('span').show();
              $("#selectTypeProduct").prev('.input-group-addon').css("background-color","red");
            } else if ($("#inputPriceProduct").val() == "") {
              $("#inputPriceProduct").closest('.col-md-4').addClass('has-error')
              $("#inputPriceProduct").closest('input').closest('.input-group').next('span').show();
              $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
            } else{
              if (localStorage.getItem('isEditProduct') == 'true') {
                $.ajax({
                  url: "{{url('/admin/updateProductPr')}}",
                  type: 'post',
                  data: {
                   _token:"{{ csrf_token() }}",
                   id_product:localStorage.getItem('id_product'),
                   inputNameProduct:$("#inputNameProduct").val(),
                   inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
                   inputQtyProduct:$("#inputQtyProduct").val(),
                   selectTypeProduct:$("#selectTypeProduct").val(),
                   inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputTotalPrice:$("#inputTotalPrice").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputSerialNumber:$("#inputSerialNumber").val(),
                   inputPartNumber:$("#inputPartNumber").val(),
                   inputGrandTotalProduct:$("#inputFinalPageTotalPrice").val(),
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
                    addDraftPr(currentTab);
                    addTable(0,localStorage.getItem('status_tax'))
                    localStorage.setItem('isEditProduct',false)
                    localStorage.setItem('status_pr','draft')
                    $(".tabGroupInitiateAdd").show()
                    $(".tab-add")[1].children[1].style.display = 'none'
                    document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                    $("#inputNameProduct").val('')
                    $("#inputDescProduct").val('')
                    $("#inputPriceProduct").val('')
                    $("#inputQtyProduct").val('')
                    $("#inputSerialNumber").val('')
                    $("#inputPartNumber").val('')
                    $("#inputTotalPrice").val('')
                  }
                })
              }else{
                $.ajax({
                  url: "{{url('/admin/storeProductPr')}}",
                  type: 'post',
                  data: {
                   _token:"{{ csrf_token() }}",
                   no_pr:localStorage.getItem('no_pr'),
                   inputNameProduct:$("#inputNameProduct").val(),
                   inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
                   inputSerialNumber:$("#inputSerialNumber").val(),
                   inputPartNumber:$("#inputPartNumber").val(),
                   inputQtyProduct:$("#inputQtyProduct").val(),
                   selectTypeProduct:$("#selectTypeProduct").val(),
                   inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputTotalPrice:$("#inputTotalPrice").val().replace(/\./g,'').replace(',','.').replace(' ',''),
                   inputGrandTotalProduct:$("#inputGrandTotalProduct").val(),
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
                  },success:function(){
                      Swal.close()
                      let x = document.getElementsByClassName("tab-add");
                      x[currentTab].style.display = "none";
                      currentTab = currentTab + n;
                      if (currentTab >= x.length) {
                        x[n].style.display = "none";
                        currentTab = 0;
                      }
                      addDraftPr(currentTab);
                      localStorage.setItem('status_pr','draft')
                      addTable(0,localStorage.getItem('status_tax'))
                      $("#inputNameProduct").val('')
                      $("#inputDescProduct").val('')
                      $("#inputPriceProduct").val('')
                      $("#inputQtyProduct").val('')
                      $("#inputSerialNumber").val('')
                      $("#inputPartNumber").val('')
                      $("#inputTotalPrice").val('')

                      $(".tabGroupInitiateAdd").show()
                      x[n].children[1].style.display = 'none'
                      document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex' 
                    }
                })
              } 
            }
          }else{
            $(".tabGroupInitiateAdd").show()
            let x = document.getElementsByClassName("tab-add");
            x[1].children[1].style.display = 'none'
            document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex' 
          }
        }else{

          if ($('#uploadCsv').val() == "") {
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            addDraftPr(currentTab);
          }else{
            var dataForm = new FormData();
            dataForm.append('csv_file',$('#uploadCsv').prop('files')[0]);
            dataForm.append('_token','{{ csrf_token() }}');
            dataForm.append('no_pr',localStorage.getItem('no_pr'));

            $.ajax({
              processData: false,
              contentType: false,
              url: "{{url('/admin/uploadCSV')}}",
              type: 'POST',
              data: dataForm,
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
              },success:function(result){
                Swal.close()
                //nge reset upload csv
                cancelUploadCsv()
                if (result.status == "Error") {
                  reasonReject(result.text,"block","tabGroupInitiateAdd")
                }else{
                  var x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  addDraftPr(currentTab);
                  addTable(0,localStorage.getItem('status_tax'))
                  localStorage.setItem('status_pr','draft')
                }
              }
            })
          }
        }       
      }else if (currentTab == 3) {
        if (n == 1) {
          if ($("#selectType").val() == 'IPR') {
            if ($("#inputPenawaranHarga").val() == "") {
              $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
              $("#inputPenawaranHarga").closest('div').next('span').show();
              $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red"); 
            }else{
              let formData = new FormData();
              const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
              if (isFilledPenawaranHarga) {
                formData.append('inputPenawaranHarga', filepenawaranHarga);
                isFilledPenawaranHarga = false
                // formData.append('nama_file_penawaranHarga', nama_file_penawaranHarga);
              } else {
                formData.append('inputPenawaranHarga', "-");
              }

              $(".tableDocPendukung").empty()


              if($('#tableDocPendukung .trDocPendukung').length != arrInputDocPendukung.length){
                if(arrInputDocPendukung.length != 0){
                  var lengthArrInputDocPendukung = $('#tableDocPendukung .trDocPendukung').length
                  arrInputDocPendukung = []
                  var i = 1;
                  $('#tableDocPendukung .trDocPendukung').each(function() {
                    if(i >= lengthArrInputDocPendukung){
                      var fileInput = $(this).find('#inputDocPendukung').val()
                      if (fileInput && fileInput !== '') { 
                        formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])

                        arrInputDocPendukung.push({
                          nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                          no_pr:localStorage.getItem('no_pr')
                        })
                      }
                    }
                    i++
                  });
                } else {
                  $('#tableDocPendukung .trDocPendukung').each(function() {
                    var fileInput = $(this).find('#inputDocPendukung').val()
                    if (fileInput && fileInput !== '') {
                      formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])

                      arrInputDocPendukung.push({
                        nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                        no_pr:localStorage.getItem('no_pr')
                      })
                    }                  
                  });
                }
              } else {
                var fileInput = $(this).find('#inputDocPendukung').val()
                if (fileInput && fileInput !== '') {
                  formData.append('inputDocPendukung[]',"-")
                }
              }
              

              isFilledDocPendukung = false

              formData.append('_token',"{{csrf_token()}}")
              formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukung))
              formData.append('no_pr',localStorage.getItem('no_pr'))

              $.ajax({
                url: "{{'/admin/storeDokumen'}}",
                type: 'post',
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
                      didOpen: () => {
                          Swal.showLoading()
                      }
                  })
                },
                success: function(data)
                {
                  Swal.close()
                  var x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  addDraftPr(currentTab);
                }
              });           
            }         
          }else{
            if ($("#selectLeadId").val() == "-") {
              $("#selectLeadId").closest('.col-md-6').addClass('has-error')
              $("#selectLeadId").closest('select').siblings('span.help-block').show();
              $("#selectLeadId").prev('.col-md-6').css("background-color","red");
            }else if ($("#selectPid").val() == "-") {
              $("#selectPid").closest('.col-md-6').addClass('has-error')
              $("#selectPid").closest('select').next('span help-block').show();
              $("#selectPid").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputSPK").val() == "") {
              $("#inputSPK").closest('.form-group').addClass('has-error')
              $("#inputSPK").closest('div').next('span').show();
              $("#inputSPK").prev('.input-group-addon').css("background-color","red");
            }else if ($("#inputSBE").val() == "") {
              $("#inputSBE").closest('.form-group').addClass('has-error')
              $("#inputSBE").closest('div').next('span').show();
              $("#inputSBE").prev('.input-group-addon').css("background-color","red");
            }else if ($("#inputQuoteSupplier").val() == "") {
              $("#inputQuoteSupplier").closest('.col-md-6').addClass('has-error')
              $("#inputQuoteSupplier").closest('div').next('span').show();
              $("#inputQuoteSupplier").prev('.col-md-6').css("background-color","red");
            }else if ($("#inputQuoteNumber").val() == "-") {
              $("#inputQuoteNumber").closest('.col-md-6').addClass('has-error')
              $("#inputQuoteNumber").closest('input').next('span').show();
              $("#inputQuoteNumber").prev('.col-md-6').css("background-color","red");
            }else{
              let formData = new FormData();
              arrInputDocPendukungEPR = []

              const fileSpk = $('#inputSPK').prop('files')[0];              
              if ($('#inputSPK').val() !="") {
                if(nama_file_spk == ""){
                  nama_file_spk = $('#inputSPK').val();
                  formData.append('inputSPK', fileSpk);
                } else if (nama_file_spk == $('#inputSPK').val()){
                  formData.append('inputSPK', "-");
                }
              }
              const fileSbe = $('#inputSBE').prop('files')[0];
              
              if ($('#inputSBE').val() !="") {
                if(nama_file_sbe == ""){
                  nama_file_sbe = $('#inputSBE').val();
                  formData.append('inputSBE', fileSbe);
                } else if (nama_file_sbe == $('#inputSBE').val()){
                  formData.append('inputSBE', "-");
                }
              }
              const fileQuoteSupplier = $('#inputQuoteSupplier').prop('files')[0];
              
              if ($('#inputQuoteSupplier').val() !="") {
                if(nama_file_quote_supplier == ""){
                  nama_file_quote_supplier = $('#inputQuoteSupplier').val();
                  formData.append('inputQuoteSupplier', fileQuoteSupplier);
                } else if (nama_file_quote_supplier == $('#inputQuoteSupplier').val()){
                  formData.append('inputQuoteSupplier', "-");
                }
              }

              // $('#tableDocPendukung .trDocPendukung').each(function() {
              //   var fileInput = $(this).find('#inputDocPendukung').val()
              //   if (fileInput && fileInput !== '') {
              //     formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])

              //     arrInputDocPendukung.push({
              //       nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
              //       no_pr:localStorage.getItem('no_pr')
              //     })
              //   }                  
              // });
              $('#tableDocPendukung_epr .trDocPendukung').each(function() {
                var fileInput = $(this).find('#inputDocPendukung').val()
                if (fileInput && fileInput !== '') {
                  formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                  formData.append('nameDocPendukung[]',$(this).find('#inputNameDocPendukung').val())
                  formData.append('no_pr',localStorage.getItem('no_pr'))
                  // arrInputDocPendukungEPR.push({
                  //   nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                  //   no_pr:localStorage.getItem('no_pr')
                  // })
                }                  
              });

              formData.append('_token',"{{csrf_token()}}")
              formData.append('no_pr', localStorage.getItem('no_pr'))
              formData.append('selectLeadId', $("#selectLeadId").val())
              formData.append('selectPid', $("#selectPid").val())
              formData.append('inputPid',$("#projectIdInputNew").val())
              formData.append('selectQuoteNumber', $("#selectQuoteNumber").val())
              // formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukungEPR))

              $.ajax({
                  type:"POST",
                  url:"{{url('/admin/storeDokumen')}}",
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
                    localStorage.setItem('isStoreSupplier',true)
                    Swal.close()
                    var x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    addDraftPr(currentTab);
                  }
              })                        
            } 
          }
        }else{
          var x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          addDraftPr(currentTab);
        }
        
      }else if (currentTab == 4) {
        if (n == 1) {
          if ($("#textAreaTOP").val() == "") {
            $("#textAreaTOP").closest('textarea').closest('div').closest('form').addClass('has-error')
            $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').show()
          }else{
            $("#textAreaTOP").closest('textarea').closest('div').closest('form').removeClass('has-error')
            $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').hide()

            $.ajax({
              url: "{{'/admin/storeTermPayment'}}",
              type: 'post',
              data:{
                no_pr:localStorage.getItem('no_pr'),
                _token:"{{csrf_token()}}",
                textAreaTOP:$("#textAreaTOP").val(),
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
                addDraftPr(currentTab);
              }
            });
          }  
        }else{
          var x = document.getElementsByClassName("tab-add");
          x[currentTab].style.display = "none";
          currentTab = currentTab + n;
          if (currentTab >= x.length) {
            x[n].style.display = "none";
            currentTab = 0;
          }
          addDraftPr(currentTab);
        }  
      }else{
        $.ajax({
          type:"POST",
          url:"{{url('/admin/storeTax')}}",
            data:{
              _token:"{{csrf_token()}}",
              no_pr:localStorage.getItem('no_pr'),
              isRupiah:localStorage.getItem('isRupiah'),
              status_tax:localStorage.getItem('status_tax'),
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
              Swal.close()
              $(".divReasonRejectRevision").remove()

              var x = document.getElementsByClassName("tab-add");
              x[currentTab].style.display = "none";
              currentTab = currentTab + n;
              if (currentTab >= x.length) {
                x[n].style.display = "none";
                currentTab = 0;
              }
              addDraftPr(currentTab);
              localStorage.setItem('status_pr','draft')
          }
        })
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
              addDraftPr(currentTab);
            }
          }else{
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            addDraftPr(currentTab);
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
          $("#selectTypeProduct").closest('select').next('span').next('span').show();
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

                  addTable(0,localStorage.getItem('status_tax'))
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
            if (result.type_of_letter == 'IPR') {
              if ($("#inputPenawaranHarga").val() == "") {
                $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
                $("#inputPenawaranHarga").closest('div').next('span').show();
                $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");
              }else{
                let formData = new FormData();
                const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
                if (filepenawaranHarga!="") {
                  formData.append('inputPenawaranHarga', filepenawaranHarga);
                }

                $(".tableDocPendukung").empty()

                var arrInputDocPendukung = []
                $('#tableDocPendukung .trDocPendukung').each(function() {
                  var fileInput = $(this).find('#inputDocPendukung').val()
                  if (fileInput && fileInput !== '') { 
                    formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                    arrInputDocPendukung.push({
                      nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                      no_pr:localStorage.getItem('no_pembanding')
                    })
                  }
                });

                formData.append('_token',"{{csrf_token()}}")
                formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukung))
                formData.append('no_pr',localStorage.getItem('no_pembanding'))

                $.ajax({
                  url: "{{'/admin/storePembandingDokumen'}}",
                  type: 'post',
                  data:formData,
                  processData: false,
                  contentType: false,
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
              }
            }else{
              if ($("#selectLeadId").val() == "-") {
                $("#selectLeadId").closest('.col-md-6').addClass('has-error')
                $("#selectLeadId").closest('select').siblings('span.help-block').show();
                $("#selectLeadId").prev('.col-md-6').css("background-color","red");
              }else if ($("#selectPid").val() == "-") {
                $("#selectPid").closest('.col-md-6').addClass('has-error')
                $("#selectPid").closest('select').next('span help-block').show();
                $("#selectPid").prev('.col-md-6').css("background-color","red");
              }else if ($("#inputQuoteSupplier").val() == "") {
                $("#inputQuoteSupplier").closest('.col-md-6').addClass('has-error')
                $("#inputQuoteSupplier").closest('input').next('span').show();
                $("#inputQuoteSupplier").prev('.col-md-6').css("background-color","red");
              }else if ($("#inputQuoteNumber").val() == "-") {
                $("#inputQuoteNumber").closest('.col-md-6').addClass('has-error')
                $("#inputQuoteNumber").closest('input').next('span').show();
                $("#inputQuoteNumber").prev('.col-md-6').css("background-color","red");
              }else{
                const fileSpk = $('#inputSPK').prop('files')[0];
                var nama_file_spk = $('#inputSPK').val();
                let formData = new FormData();
                if (nama_file_spk!="" && fileSpk!="") {
                  formData.append('inputSPK', fileSpk);
                }

                const fileSbe = $('#inputSBE').prop('files')[0];
                var nama_file_sbe = $('#inputSBE').val();
                if (nama_file_sbe!="" && fileSbe!="") {
                  formData.append('inputSBE', fileSbe);
                }

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
                    localStorage.setItem('isStoreSupplier',true)
                    Swal.close()
                    let x = document.getElementsByClassName("tab-add");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    if (currentTab >= x.length) {
                      x[n].style.display = "none";
                      currentTab = 0;
                    }
                    addDraftPrPembanding(currentTab);
                  }
                })
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
            textAreaTOP:$("#textAreaTOP").val()
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
    
    localStorage.setItem('isEditProduct',false)
    function nextPrevAddAdmin(n,no_pr) {
      if (localStorage.getItem('isEditProduct') == 'true') {
        $.ajax({
          url: "{{url('/admin/updateProductPr')}}",
          type: 'post',
          data: {
           _token:"{{ csrf_token() }}",
           id_product:localStorage.getItem('id_product'),
           inputNameProduct:$("#inputNameProduct").val(),
           inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
           inputQtyProduct:$("#inputQtyProduct").val(),
           selectTypeProduct:$("#selectTypeProduct").val(),
           inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''),
           inputTotalPrice:$("#inputTotalPrice").val().replace(/\./g,'').replace(',','.').replace(' ',''),
           inputSerialNumber:$("#inputSerialNumber").val(),
           inputPartNumber:$("#inputPartNumber").val(),
           inputGrandTotalProduct:$("#inputFinalPageTotalPrice").val(),
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
            localStorage.setItem('isEditProduct',false)
            cekByAdmin(currentTab,no_pr);
            $("#ModalDraftPr").modal("hide")
            Swal.close()
            currentTab = 1

            $("#inputNameProduct").val('')
            $("#inputDescProduct").val('')
            $("#inputPriceProduct").val('')
            $("#inputQtyProduct").val('')
            $("#inputSerialNumber").val('')
            $("#inputPartNumber").val('')
            $("#inputTotalPrice").val('')
          }
        })
      }

      if (n == -1) {
        $(".radioConfirm").prop('checked', false);
      }

      var x = document.getElementsByClassName("tab-cek");
      x[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        x[n].style.display = "none";
        currentTab = 0;
      }
      cekByAdmin(currentTab,no_pr);
    }

    var incrementDoc = 0
    function addDocPendukung(value){
      $("#titleDoc_"+value).show()
      append = ""
        append = append + "<tr style='height:10px' class='trDocPendukung'>"
          append = append + "<td>"
            append = append + '<button type="button" class="fa fa-times btnRemoveAddDocPendukung" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
            append = append + '<label for="inputDocPendukung" style="margin-bottom:0px">'
            append = append + '<span class="fa fa-cloud-upload" style="display:inline"></span>'
            append = append + '<input style="display:inline;font-family: inherit;width: 90px;" class="inputDocPendukung_'+ incrementDoc +' files" type="file" name="inputDocPendukung" id="inputDocPendukung" data-value="'+incrementDoc+'">'
            append = append + '</label>'
          append = append + "</td>"
          append = append + "<td>"
            append = append + '<input style="width:250px;margin-left:20px" data-value='+ incrementDoc +' class="form-control inputNameDocPendukung_'+ incrementDoc+'" name="inputNameDocPendukung" id="inputNameDocPendukung" placeholder="ex : faktur pajak"><span class="help-block" style="display:none;margin-left:20px">Please fill Document Name!</span>'
          append = append + "</td>"
        append = append + "</tr>"
      $("#tableDocPendukung_"+value).append(append) 
      incrementDoc++

      $("#btnAddDocPendukung_epr").prop("disabled",true)
      $("#btnAddDocPendukung_ipr").prop("disabled",true)

      $("#tableDocPendukung_"+ value +" .trDocPendukung").each(function(){
        
        $('.inputNameDocPendukung_'+$(this).find('#inputDocPendukung').data('value')).keydown(function(){
          
          if (this.value == "") {
            $("#btnAddDocPendukung_epr").prop("disabled",true)
            $("#btnAddDocPendukung_ipr").prop("disabled",true)
          }else{
            $("#btnAddDocPendukung_epr").prop("disabled",false)
            $("#btnAddDocPendukung_ipr").prop("disabled",false)
          }
        })   
      })   
    }

    $(document).on('click', '.btnRemoveAddDocPendukung', function() {
      $(this).closest("tr").remove();
      if($('#tableDocPendukung tr').length == 0){
        $("#titleDoc").hide()
      }
    });

    function createPR(status){
      if ($("#inputFinalPageGrandPrice").val() == '0') {
        Swal.fire({
          title: 'Alert',
          text: "Please to add some products.",
          icon: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
        })
      }else{
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
                $.ajax({
                  type:"POST",
                  url:"{{url('/admin/storeLastStepDraftPr')}}",
                  data:{
                    _token:"{{csrf_token()}}",
                    no_pr:localStorage.getItem('no_pr'),
                    inputGrandTotalProduct:$("#inputFinalPageGrandPrice").val(),
                    status_revision:status,
                    isRupiah:localStorage.getItem("isRupiah"),
                  },
                  success: function(result){
                    Swal.fire({
                      title: 'Drafting PR Successs',
                      html: "<p style='text-align:center;'>Your PR draft will be verified by Admin/Procurement soon, please wait for further progress</p>",
                      type: 'success',
                      confirmButtonText: 'Reload',
                    }).then((result) => {
                      localStorage.setItem('status_pr','') 
                      if (status == 'revision') {
                        location.replace("{{url('/admin/detail/draftPR')}}/"+ localStorage.getItem('no_pr'))
                      }else{
                        location.replace("{{url('admin/draftPR')}}")
                      }
                    })
                  }
                })
            }
        })
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
              $.ajax({
                type:"POST",
                url:"{{url('/admin/storeLastStepPembanding')}}",
                data:{
                  _token:"{{csrf_token()}}",
                  no_pr:localStorage.getItem('no_pembanding'),
                  inputGrandTotalProduct:$("#inputFinalPageTotalPrice").val(),
                },
                success: function(result){
                  location.replace("{{url('/admin/detail/draftPR')}}/"+ window.location.href.split("/")[6])
                  localStorage.setItem('isLastStorePembanding',true)
                }
              })
          }
      })
    }

    function cekPRbyAdmin(){
    }

    function refreshTable(){
      addTable(0,localStorage.getItem('status_tax'))
    }

    $('#makeId').click(function(){
      $('#project_idNew').show()
      $('#project_id').val("").select2().trigger("change")

      $("#selectPid").closest('.form-group').removeClass('has-error')
      $("#selectPid").closest('select').next('span').next("span").hide(); 
      $("#selectPid").prev('.col-md-6').css("background-color","red");
    })

    $('#removeNewId').click(function(){
      $('#project_idNew').hide('slow')
      $('#projectIdInputNew').val('')
    })

  </script>
@endsection