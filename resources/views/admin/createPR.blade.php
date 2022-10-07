@extends('template.main')
@section('tittle')
  Draft Purchase Request
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
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
	<div class="box">
    <div class="box-header with-border">
    	<button class="btn btn-md btn-primary pull-right" style="display:none;" dusk="addDraftPr" id="addDraftPr" onclick="addDraftPr(0)" ><i class="fa fa-plus"></i> Draft PR</button>
    </div>
    <div class="box-body">
      <div class="row" id="filterBox">
        <div class="col-md-2">
          <b>Filter by Type PR : </b>
          <div>
            <select class="form-control select2" id="inputFilterTypePr" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true">
            </select>
          </div>
        </div>

        <div class="col-md-2">
          <b>Filter by Status : </b>
          <div>
            <select class="form-control select2" id="inputFilterStatus" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true"></select>
          </div>
        </div>

        <div class="col-md-2">
          <b>Filter by User : </b>
          <div>
            <select class="form-control select2" id="inputFilterUser" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true"></select>
          </div>
        </div>

        <div class="col-md-2">
          <b>Range Date PR : </b>

          <button type="button" class="btn btn-default btn-flat pull-left" style="width:100%" id="inputRangeDate">
            <i class="fa fa-calendar"></i> Date range picker
            <span>
              <i class="fa fa-caret-down"></i>
            </span>
          </button>
        </div>
        
        <div class="col-md-4">
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
                <li><a href="#" onclick="changeNumberEntries(100)">100</a></li>
              </ul>
            </div>
            <span class="input-group-btn">
              <button style="margin-left: 10px;" title="Clear Filter" id="clearFilterTable" type="button" class="btn btn-default btn-flat">
                <i class="fa fa-fw fa-remove"></i>
              </button>
              
            </span>
            <span class="input-group-btn">
              <button style="margin-left: 10px;" type="button" id="btnShowColumnTicket" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Displayed Column
                <span class="fa fa-caret-down"></span>
              </button>
              <ul class="dropdown-menu" style="padding-left:5px;padding-right: 5px;" id="selectShowColumnTicket">
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="0"><span class="text">No. PR</span></li>
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="1"><span class="text">Created at</span></li>
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="2"><span class="text">Subject</span></li>
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="3"><span class="text">Supplier</span></li>
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="4"><span class="text">Total Price</span></li>
                <li style="cursor: pointer;"><input style="margin: 0 10px 0 5px;" type="checkbox" onclick="changeColumnTable(this)" data-column="5"><span class="text">Status</span></li>
              </ul>
              <button style="margin-left: 10px;" title="Refresh Table" id="reloadTable" type="button" class="btn btn-default btn-flat">
                <i class="fa fa-fw fa-refresh"></i>
              </button>
            </span>
          </div>
        </div>
            
      </div>
    	<div class="table-responsive">
	        <table class="table table-bordered table-striped dataTable nowrap" id="draftPr" width="100%" cellspacing="0">
	          <thead>
	            <tr style="text-align: center;">
	              <th>No. PR</th>
                <th>Created at</th>
	              <th>Subject</th>
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
</section>
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
          <div class="tabGroup">
            <div class="form-group">
              <label for="">To*</label>
              <input type="" class="form-control" placeholder="ex. eSmart Solution" id="inputTo" name="inputTo" onkeyup="fillInput('to')">
              <span class="help-block" style="display:none;">Please fill To!</span>
            </div>      

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Type*</label>
                  <select type="text" class="form-control" name="type" placeholder="ex. Internal Purchase Request" id="selectType" required>
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
                  <label for="">Fax</label>
                  <input type="" id="inputFax" class="form-control" name="inputFax">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Attention*</label>
              <input type="text" class="form-control" placeholder="ex. Marsono" name="inputAttention" id="inputAttention" onkeyup="fillInput('attention')">
              <span class="help-block" style="display:none;">Please fill Attention!</span>
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

            <div class="form-group" id="divNotePembanding" style="display:none;">
              <label for="">Note Pembanding*</label>
              <textarea class="form-control" id="note_pembanding" name="note_pembanding"></textarea>
              <span class="help-block" style="display:none;">Please fill Note Pembanding!</span>
            </div>
          </div>
        </div>
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
            <div class="form-group">
              <label>Product*</label>
              <input type="text" name="" class="form-control" id="inputNameProduct" placeholder="ex. Laptop MSI Modern 14" onkeyup="fillInput('name_product')">
              <span class="help-block" style="display:none;">Please fill Name Product!</span>
            </div>
            <div class="form-group">
              <label>Description*</label> 
              <textarea onkeyup="fillInput('desc_product')" style="resize:vertical;height:150px" id="inputDescProduct" placeholder='ex. Laptop mSI Modern 14, Processor AMD Rayzen 7 5700, Memory 16GB, SSD 512 Gb, Screen 14", VGA vega 8, Windows 11 Home' name="inputDescProduct" class="form-control"></textarea>
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
                  <select class="form-control" id="selectTypeProduct" placeholder="ex. Unit" onchange="fillInput('type_product')">
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
        </div> 
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
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
            <div class="row">
              <div class="col-md-12" id="bottomProducts">
                
              </div>
            </div>
            <div class="form-group" style="display:flex;">
              <button class="btn btn-sm btn-primary" style="margin: 0 auto;" type="button" id="addProduct"><i class="fa fa-plus"></i>&nbsp Add product</button>
            </div>
          </div>
        </div>
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
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
                <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                  <label for="inputSPK" style="margin-bottom: 0px;">
                    <span class="fa fa-cloud-upload" style="display:inline;"></span>
                    <input style="display: inline;" type="file" name="inputSPK" id="inputSPK" onchange="fillInput('spk')" accept="image/*,.pdf" >
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
                    <input style="display: inline;" type="file" name="inputSBE" id="inputSBE" onchange="fillInput('sbe')" accept="image/*,.pdf" >
                  </label>
                </div>
                <span class="help-block" style="display:none;">Please fill SBE!</span>
                <span style="display:none;" id="span_link_drive_sbe"><a id="link_sbe" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label>Quote Supplier*</label>
                    <div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">
                      <!-- <i class="icon" style="display:inline"></i> -->
                      <label for="inputQuoteSupplier" style="margin-bottom: 0px;">
                        <span class="fa fa-cloud-upload" style="display:inline;"></span>
                        <input style="display: inline;" type="file" name="inputQuoteSupplier" id="inputQuoteSupplier" onchange="fillInput('quoteSupplier')" accept="image/*,.pdf" >
                      </label>
                      
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
                    <i class="fa fa-cloud-upload" style="display:inline"></i>
                    <input style="display: inline;" type="file" name="inputPenawaranHarga" id="inputPenawaranHarga" onchange="fillInput('penawaranHarga')">
                  </label>                  
                </div>
                <span class="help-block" style="display:none;">Please fill Penawaran Harga!</span>
                <span style="display:none;" id="span_link_drive_penawaranHarga"><a id="link_penawaran_harga" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a></span>
              </div>
              <div id="docPendukungContainer">
                <label id="titleDoc" style="display:none;">Lampiran Dokumen Pendukung</label>
                <table id="tableDocPendukung" class="border-collapse:collapse" style="border-collapse: separate;border-spacing: 0 15px;">
                  
                </table>
              </div>
              <div class="form-group" style="display: flex;margin-top: 10px;">
                <button type="button" id="btnAddDocPendukung" style="margin:0 auto" class="btn btn-sm btn-primary" onclick="addDocPendukung()"><i class="fa fa-plus"></i>&nbsp Dokumen Pendukung</button>
              </div>
            </div>
          </div>
        </div>   
        <div class="tab-add" style="display:none">
          <div class="tabGroup">
            <div class="box-body pad">
                <textarea onkeyup="fillInput('textArea_TOP')" class="textarea" id="textAreaTOP" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);" placeholder="ex. term of payment"></textarea>
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
       <!--    <div class="form-group divReasonRejectRevision has-error" style="display:none">
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Note Reject PR</label>
            <span class="help-block reason_reject_revision">Help block with error</span>
          </div> -->
         <!--  <div class="form-group divReasonRejectRevision" style="display:none">
            <label for="" class="bg-red">Note Reject PR</label>
            <div class="reason_reject_revision" id="reason_reject_revision" name="reason_reject_revision"></div>
          </div>   -->         	
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
                  <label for="">fax</label>
              	  <input type="" id="inputFaxCek" class="form-control" name="inputFaxCek" readonly>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Attention*</label>
              <div class="input-group">
              	<input type="text" class="form-control" placeholder="ex. Marsono" name="inputAttentionCek" id="inputAttentionCek" readonly>
            		<div class="input-group-addon">
              		<input onchange="checkBoxCek('attention_cek')" id="attention_cek" name="chk[]" type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
            		</div>
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
          		<tbody id="tbodyProductsCek">
          			
          		</tbody>
          	</table>
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
                    <i class="icon" style="display:inline;color: #367fa9;"></i>
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
                    <i class="icon" style="display:inline;color: #367fa9;"></i>
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
                        <i class="icon" style="display:inline;color: #367fa9;"></i>
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
                  <span>
                    <input type="radio" class="minimal radioConfirm" name="radioConfirm" value="approve">
                    Approve
                  </span><br>
                  <span>
                    <input type="radio" class="minimal radioConfirm" name="radioConfirm" value="reject">
                    Reject
                  </span>
                </div>
                <div class="form-group" style="display:none;" id="divReasonReject">
                  <h4>Reason of Reject</h4>
                  <textarea id="textAreaReasonReject" class="form-control" placeholder="ex. [Informasi Supplier - To] Ada Kesalahan Penulisan Nama" style="resize:vertical;"></textarea>
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
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
    $(".money").mask('000,000,000,000,000', {reverse: true})

    $(document).ready(function(){   
      currentTab = 0     
      $('input[type="file"]').change(function(){
        console.log(this.files[0])
        var f=this.files[0]
        var filePath = f;
     
        // Allowing file type
        var allowedExtensions =
        /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

        var ErrorText = []
        if (f.size > 40000000|| f.fileSize > 40000000) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Invalid file size, just allow file with size less than 40MB!',
          }).then((result) => {
            this.value = ''
          })
        }

        console.log(filePath)
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
      //box id
      DashboardCounter()
      InitiateFilterParam()
    })

    function DashboardCounter(){
      $("#BoxId").empty()
      
      var countPr = []

      var i = 0
      var append = ""
      var colors = []
      var ArrColors = [{
            name: 'Need Attention',style: 'color:red', color: 'bg-yellow', icon: 'fa fa-exclamation',index: 0
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
        console.log(value)
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
            name: 'Need Attention',style: 'color:red', color: 'bg-yellow', icon: 'fa fa-exclamation',index: 0
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
        console.log(value)
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

  	var formatter = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });

  	function textAreaAdjust(element) {
  	  element.style.height = "1px";
  	  element.style.height = (25+element.scrollHeight)+"px";
  	}

    $("#draftPr").DataTable({
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
            return '[<b><i>' + row.type_of_letter + '</i></b>] ' + row.title         
          },
        },
        { "data": "to"},
        { 
          render: function (data, type, row, meta){
            return formatter.format(row.nominal)          
          }
        },
        { 
          orderData:[7],
          render: function (data, type, row, meta){
            if (row.status == 'SAVED') {
              return '<span class="label label-primary">'+row.status+'</span>'           
            }else if (row.status == 'DRAFT') {
              return '<span class="label label-primary">'+row.status+'</span>'           
            }else if (row.status == 'REJECT') {
              return '<span class="label label-danger">'+row.status+'</span>' 
            }else if (row.status == 'VERIFIED') {
              return '<span class="label label-success">'+row.status+'</span>'
            }else if (row.status == 'COMPARING') {
              return '<span class="label bg-purple">'+row.status+'</span>'
            }else if (row.status == 'CIRCULAR') {
              return '<span class="label label-warning">'+row.status+'</span>'
            }else if (row.status == 'FINALIZED') {
              return '<span class="label label-success">'+row.status+'</span>'           
            }else if (row.status == 'SENDED') {
              return '<span class="label label-primary">'+row.status+'</span>'           
            }else if (row.status == 'UNAPPROVED') {
              return '<span class="label label-danger">'+row.status+'</span>' 
            }
          },
          className:'text-center'
        },
        { 
          render: function (data, type, row, meta){
            if (row.status == 'DRAFT') {
              return "<td><button class='btn btn-sm btn-primary btnCekDraft btnCekDraftDusk_"+row.id+"' data-value='"+row.id+"' disabled id='btnCekDraft' onclick='cekByAdmin(0,"+ row.id +")'>Verify</button></td>"
            }else if (row.status == 'SAVED') {
              if (row.issuance == '{{Auth::User()->nik}}') {
                status = '"saved"'
                return "<td><button class='btn btn-sm btn-warning' id='btnDraft' data-value='"+row.id+"' value='saved' onclick='unfinishedDraft(0,"+ row.id +","+ status +")'>Draft</button></td>" 
              }else{
                return "<td><button class='btn btn-sm btn-warning' id='btnDraft' disabled>Draft</button></td>" 
              } 
            }else if (row.status == 'REJECT') {
              status = '"reject"'
              if (row.issuance == '{{Auth::User()->nik}}') {
                return "<td><button class='btn btn-sm btn-warning' id='btnDraft' value='reject' onclick='unfinishedDraft(0,"+ row.id +","+ status +")'>Revision</button></td>" 
              }else{
                return "<td><button class='btn btn-sm btn-warning' id='btnDraft' data-value='"+row.id+"' disabled>Revision</button></td>" 
              } 
            }else if(row.status == 'UNAPPROVED'){
              if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}") {
                status = '"revision"'

                return "<td><button class='btn btn-sm btn-warning' data-value='"+row.id+"' onclick='unfinishedDraft(0,"+ row.id +","+ status +")'>Revision</button></td>"
              }else{
                return "<td><button class='btn btn-sm btn-warning' disabled>Revision</button></td>" 
              }
            }else{
              return "<td><a href='{{url('admin/detail/draftPR')}}/"+row.id+"'><button id='btnDetail' class='btn btn-sm btn-primary btnDetailDusk_"+row.id+"'>Detail</button></a></td>" 
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
      "pageLength":10,
      lengthChange:false,
      autoWidth:false,
      initComplete: function () {
        $.each($("#selectShowColumnTicket li input"),function(index,item){
          var column = $("#draftPr").DataTable().column(index)
          // column.visible() ? $(item).addClass('active') : $(item).removeClass('active')
          $(item).prop('checked', column.visible())
        })
      }
    })

    function changeColumnTable(data){
      var column = $("#draftPr").DataTable().column($(data).attr("data-column"))
      column.visible( ! column.visible() );
    }

    function InitiateFilterParam(){
      var tempType = 'type_of_letter[]=', tempStatus = 'status[]=', tempUser = 'user[]=', tempStartDate = 'startDate=', tempEndDate = 'endDate=', tempAnything = 'search='

      var temp = '?' + tempType + '&' + tempStatus + '&' + tempUser + '&' + tempStartDate + '&' + tempEndDate + '&' + tempAnything
      
      $.ajax({
        url:"{{url('/admin/getFilterDraft')}}" + temp,
        type:"GET",
        success:function(result){
          var arrStatus = result.dataStatus;
          var selectOptionStatus = [];

          var selectOptionStatus = [
            {
              text:"Grouped Status", 
              children:[
                {
                  id:"NA",
                  text:"Need Attention",
                },
                {
                  id:"OG",
                  text:"On Going",
                },
                {
                  id:"DO",
                  text:"Done",
                }
              ]
            },{
              text:"All Status", 
              children:arrStatus
            }
          ]
          $("#inputFilterStatus").select2({
            placeholder: " Select Status",
            // allowClear: true,
            multiple:true,
            data:selectOptionStatus,
          })

          // $("#inputFilterUser").select2().val("");
          var arrUser = result.dataUser
          $("#inputFilterUser").select2({
            placeholder: " Select User",
            // allowClear: true,
            multiple:true,
            data:arrUser,
          })

          $("#inputFilterTypePr").select2({
            placeholder: "Select a Type",
            // allowClear: true,
            data:result.data_type_letter,
            multiple:true
          })
        }
      })
    }  

    function showFilterData(temp){
      $("#draftPr").DataTable().ajax.url("{{url('/admin/getFilterDraft')}}" + temp).load()

      $.ajax({
        url:"{{url('/admin/getFilterDraft')}}" + temp,
        type:"GET",
        success:function(result){
          var parameterStatus = new URLSearchParams(temp);
          console.log(parameterStatus.getAll('status'))
          if (parameterStatus.getAll('status[]')[0] == "") {
            $("#inputFilterStatus").empty();

            var arrGrouped = []
            arrGrouped.push({
              id:"NA",
              text:"Need Attention",
            },
            {
              id:"OG",
              text:"On Going",
            },
            {
              id:"DO",
              text:"Done",
            })

            var arrStatus = result.dataStatus;
            var selectOptionStatus = [];

            var selectOptionStatus = [
              {
                text:"Grouped Status", 
                children:arrGrouped
              },{
                text:"All Status", 
                children:arrStatus
              }
            ]

            $("#inputFilterStatus").select2({
              placeholder: " Select Status",
              // allowClear: true,
              multiple:true,
              data:selectOptionStatus,
            })
          }

          if (parameterStatus.getAll('user[]')[0] == "") {
            $("#inputFilterUser").empty();

            $("#inputFilterUser").select2({
              placeholder: " Select User",
              // allowClear: true,
              multiple:true,
              data:result.dataUser,
            })
          }

          if (parameterStatus.getAll('type_of_letter[]')[0] == "") {
            $("#inputFilterTypePr").empty();

            $("#inputFilterTypePr").select2({
              placeholder: " Select User",
              // allowClear: true,
              multiple:true,
              data:result.data_type_letter,
            })
          }
          

        }
      })
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
    }

    $('#clearFilterTable').click(function(){
      $('#inputSearchAnything').val('')
      $("#inputFilterTypePr").empty();
      $("#inputFilterStatus").empty();
      $("#inputFilterUser").empty();
      DashboardCounter()
      InitiateFilterParam()
      $("#inputRangeDate").val("")
      $('#inputRangeDate').html("")
      $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span> Date range picker <i class="fa fa-caret-down"></i></span>');
      $('#draftPr').DataTable().ajax.url("{{url('/admin/getDraftPr')}}").load();
    });

    $('#reloadTable').click(function(){
      $('#inputSearchAnything').val('')
      $("#inputFilterTypePr").empty();
      $("#inputFilterStatus").empty();
      $("#inputFilterUser").empty();
      DashboardCounter()
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
      $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span>' + start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY') + '</span>');

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

    function reasonReject(item,display){
      $(".divReasonRejectRevision").remove()
      
      console.log(display)
      var append = ""

      append = append + '<div class="callout callout-danger divReasonRejectRevision" style="display:none">'
        append = append + '<h4><i class="icon fa fa-cross"></i>Note Reject PR</h4>'
        append = append + '<p class="reason_reject_revision">'+item.replaceAll("\n","<br>")+'</p>'
      append = append + '</div>'

      $(".tabGroup").prepend(append)

      if (display == "block") {
        $(".divReasonRejectRevision").show()
      }
    }

    function unfinishedDraft(n,id_draft,status){
      localStorage.setItem('firstLaunch', false);
      localStorage.setItem('no_pr',id_draft)
      localStorage.setItem('status_unfinished',status)
      $.ajax({
        type: "GET",
        url: "{{url('/admin/getPreviewPr')}}",
        data: {
          no_pr:id_draft,
        },
        success: function(result) {
          console.log("current_tab",n)
          var x = document.getElementsByClassName("tab-add");
          x[n].style.display = "inline";
          if (n == (x.length - 1)) {
            $(".modal-dialog").addClass('modal-lg')
            $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1)')        
            $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1)')
            $(".modal-title").text('')
            document.getElementById("prevBtnAdd").style.display = "inline";
            $("#headerPreviewFinal").empty()
            console.log(n)
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
            if (result.pr.type_of_letter == 'IPR') {
              appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ PRMethode +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><br>"
              appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Address: <span id='textAddress'>"+ result.pr.address +"</span></span<br>"
            }else{
              appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ PRMethode +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ leadRegister +"</span><br>"
              appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
              appendHeader = appendHeader + '<span>Address: <span id="textQuoteNumber" style="display:inline;" class="pull-right">'+ quoteNumber +'</span></span><br>'
              appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br>'
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
                  append = append + '<input readonly class="form-control" style="font-size: 12px; important" type="" name="" value="'+ item.name_product +'">'
                append = append + '</td>'
                append = append + '<td width="35%">'
                  append = append + '<textarea readonly class="form-control" style="height: 250px;resize: none;height: 120px;font-size: 12px; important">' + item.description.replaceAll("<br>","\n") + '</textarea>'
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
              appendBottom = appendBottom + '<div class="row">'
                appendBottom = appendBottom + '<div class="col-md-12">'
                  appendBottom = appendBottom + '<div class="pull-right">'
                    appendBottom = appendBottom + '<span style="display: inline;margin-right: 10px;">Grand Price</span>'
                    appendBottom = appendBottom + '<input readonly type="text" style="width:150px;margin-right:10px;display: inline;" class="form-control inputFinalPageTotalPrice" id="inputFinalPageGrandPrice" name="inputFinalPageTotalPrice">'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '</div>'
            appendBottom = appendBottom + '<hr>'
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
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
            $('.grandTotalPreview').each(function() {
                var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
                sum += temp;
            });
           
            $("#inputFinalPageGrandPrice").val(formatter.format(sum))   

            if (status == 'reject' || status == 'revision') {
              reasonReject(result.activity.reason,"block")
            }                               

            if (window.location.href.split("/")[5] != undefined) {
              if (window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'reject' || window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'revision') {
                reasonReject(result.activity.reason,"block")
              }
            }
            
          } else {
            console.log(n)
            if (n == 0) {
              console.log(status)

              //reinitiate
              $("#inputTo").val("")
              $("#selectType").val("")
              $("#inputEmail").val("")
              $("#inputPhone").val("")
              $("#inputFax").val("")
              $("#inputAttention").val("")
              $("#inputSubject").val("")
              $("#inputAddress").val("")

              $("#inputTo").val(result.pr.to)
              $("#selectType").val(result.pr.type_of_letter)
              $("#inputEmail").val(result.pr.email)
              $("#inputPhone").val(result.pr.phone)
              $("#inputFax").val(result.pr.fax)
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

              const firstLaunch = localStorage.getItem('firstLaunch')
              document.getElementById("prevBtnAdd").style.display = "none";
              $(".modal-title").text('Information Supplier')
              $(".modal-dialog").removeClass('modal-lg')
 
              localStorage.setItem('no_pr',id_draft)
              console.log(status)
              if (status == 'reject') {
                if (result.verify.verify_type_of_letter == 'True'){
                  console.log(result.verify.verify_type_of_letter)
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

                reasonReject(result.activity.reason,"block")

                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,'+ firstLaunch +')')
              } else if (status == 'revision') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").html(result.activity.reason.replaceAll("\n","<br>"))

                reasonReject(result.activity.reason,"block")

                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,'+ firstLaunch +')')
              } else {
                if (firstLaunch == 'true') {
                  $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1,'+ firstLaunch +')')
                }else{
                  $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(2,'+ firstLaunch +')')
                } 
              }
            } else if (n == 1) {
              $(".modal-title").text('Information Product')
              $(".modal-dialog").removeClass('modal-lg')  
              $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1)')
              $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1)')     
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
              if (localStorage.getItem('firstLaunch') == 'false') {
                $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-2)')
                $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1)')
              }else{
                $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1)')
              } 
              document.getElementById("prevBtnAdd").style.display = "inline";
              localStorage.setItem('no_pr',id_draft)
            } else if (n == 3) {
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
                $.ajax({
                  type: "GET",
                  url: "{{url('/admin/getPreviewPr')}}",
                  data: {
                    no_pr:localStorage.getItem("no_pr"),
                  },
                  success:function(result){
                    var selectedLead = result.pr.lead_id

                    $.ajax({
                      url: "{{url('/admin/getLead')}}",
                      type: "GET",
                      success: function(result) {
                        $("#selectLeadId").select2({
                            data: result.data
                        }).on('change', function() {
                          var data = $("#selectLeadId option:selected").text();
                          console.log(data)
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

                        if (status == 'reject' || status == 'revision') {
                          $("#selectLeadId").val(selectedLead).trigger("change")
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

                      reasonReject(result.activity.reason,"block")

                    } else if (status == 'revision') {
                      console.log(status)

                      reasonReject(result.activity.reason,"block")
                    } 

                    if (result.dokumen.length > 0) {
                      console.log(result.dokumen[1])
                      let formData = new FormData();

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

                      if (result.dokumen[2] !== undefined) {
                        const myFileQuote = new File(['{{asset("/")}}"'+ result.dokumen[2].dokumen_location +'"'], '/'+ result.dokumen[2].dokumen_location,{
                            type: 'text/plain',
                            lastModified: new Date(),
                        });

                        // Now let's create a DataTransfer to get a FileList
                        const dataTransferQuote = new DataTransfer();
                        dataTransferQuote.items.add(myFileQuote);
                        fileQuote.files = dataTransferQuote.files;

                        if (result.dokumen[2].link_drive != null) {
                          $("#span_link_drive_quoteSup").show()
                          $("#link_quoteSup").attr("href",result.dokumen[2].link_drive) 
                        }
                      }
                          
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

                $.ajax({
                  type: "GET",
                  url: "{{url('/admin/getPreviewPr')}}",
                  data: {
                    no_pr:localStorage.getItem("no_pr"),
                  },
                  success:function(result){
                    var pdf = "fa fa-fw fa-file-pdf-o"
                    var image = "fa fa-fw fa-file-image-o"

                    if(result.dokumen.length > 0){
                      if (result.dokumen.length > 1) {
                        $("#titleDoc").show()
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


                    $("#tableDocPendukung").empty()

                    appendDocPendukung = ""
                    $.each(result.dokumen,function(value,item){
                      if (value != 0) {
                        appendDocPendukung = appendDocPendukung + '<tr style="height:10px" class="trDocPendukung">'
                          appendDocPendukung = appendDocPendukung + "<td>"
                            appendDocPendukung = appendDocPendukung + '<button type="button" value="'+ item.id_dokumen +'" class="fa fa-times btnRemoveDocPendukung" data-value="remove_'+ value +'" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
                                appendDocPendukung = appendDocPendukung + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;display: inline-block;width:200px">'
                                  appendDocPendukung = appendDocPendukung + "<input type='file' name='inputDocPendukung' id='inputDocPendukung' data-value='"+ item.id_dokumen +"' class='inputDocPendukung_"+value+"'>"
                                 appendDocPendukung = appendDocPendukung + '</div>'
                                 appendDocPendukung = appendDocPendukung + "<br><a style='margin-left: 26px;font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif' href='"+ item.link_drive +"' target='_blank'><i class='fa fa-link'></i>&nbspLink drive</a>"
                            // appendDocPendukung = appendDocPendukung + "<input style='display:inline' class='pull-right inputDocPendukung_"+value+"' type='file' name='inputDocPendukung[]' id='inputDocPendukung' data-value=''+ item.id_dokumen +''><br><a style='margin-left: 26px;font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif' href='"+ item.link_drive +"' target='_blank'><i class='fa fa-link'></i>&nbspLink drive</a>"
                          appendDocPendukung = appendDocPendukung + "</td>"
                          appendDocPendukung = appendDocPendukung + "<td>"
                            appendDocPendukung = appendDocPendukung + '<input style="width:250px;margin-left:20px" class="form-control inputNameDocPendukung_'+value+'" name="inputNameDocPendukung" id="inputNameDocPendukung"><br>'
                          appendDocPendukung = appendDocPendukung + "</td>"
                        appendDocPendukung = appendDocPendukung + "</tr>"
                      }   
                    })
                    $("#tableDocPendukung").append(appendDocPendukung)              

                    $.each(result.dokumen,function(value,item){
                      console.log(item.dokumen_location)
                      console.log(value)
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
                        if($('#tableDocPendukung tr').length == 0){
                          $("#titleDoc").hide()
                        }
                      })
                    })
                  }
                })                  
              }   
    
              $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1)')        
              $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1)')
              document.getElementById("prevBtnAdd").style.display = "inline";
              localStorage.setItem('no_pr',id_draft)
            } else if (n == 4) {
              $(".modal-dialog").removeClass('modal-lg')
              // $('.wysihtml5-toolbar').remove();
              if (status == 'reject') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").val(result.activity.reason.replaceAll("\n","<br>"))
                reasonReject(result.activity.reason,"block")

              }else if (status == 'revision') {
                $(".divReasonRejectRevision").show()
                $(".reason_reject_revision").html(result.activity.reason.replaceAll("\n","<br>"))
                reasonReject(result.activity.reason,"block")

              }
              $(".modal-title").text('Term Of Payment')   
              $(".modal-dialog").removeClass('modal-lg')   
              $("#prevBtnAdd").attr('onclick','nextPrevUnFinished(-1)')        
              $("#nextBtnAdd").attr('onclick','nextPrevUnFinished(1)')
              document.getElementById("prevBtnAdd").style.display = "inline";
              localStorage.setItem('no_pr',id_draft)

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
            $("#addProduct").attr('onclick','nextPrevUnFinished(-1)')
          }
        }
      })
      
      $("#ModalDraftPr").modal('show') 
    }

  	function addDraftPr(n){
  		console.log("current_tab",n)
  		var x = document.getElementsByClassName("tab-add");
  		x[n].style.display = "inline";
  		if (n == (x.length - 1)) {
  			$(".modal-dialog").addClass('modal-lg')
  			$("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')				
  			$("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
  			$(".modal-title").text('')
  			document.getElementById("prevBtnAdd").style.display = "inline";
  			$("#headerPreviewFinal").empty()
  			console.log(n)
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
            if (result.pr.type_of_letter == 'IPR') {
              appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ PRMethode +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><br>"
              appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Address: <span id='textAddress' style='float:right;width:500px'>"+ result.pr.address +"</span></span<br>"
            }else{
              appendHeader = appendHeader + "<span style='display:inline;'>To: <span id='textTo'>"+ result.pr.to +"</span></span><span id='textPRType' style='display:inline;' class='pull-right'>"+ PRType +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline'>Email: <span id='textEmail'>"+ result.pr.email +"</span></span><span style='display:inline;' class='pull-right'><b>Request Methode</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Phone: <span id='textPhone'>"+ result.pr.phone +"</span></span><span id='textTypeMethode' style='display:inline;' class='pull-right'>"+ PRMethode +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Fax: <span id='textFax'>"+ result.pr.fax +"</span> <span id='textDate' style='display:inline;' class='pull-right'>"+ moment(result.pr.created_at).format('DD MMMM') +"</span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>Attention: <span id='textAttention'>"+ result.pr.attention +"</span></span><span style='display:inline;' class='pull-right'><b>Lead Register</b></span></br>"
              appendHeader = appendHeader + "<span style='display:inline;'>From: <span id='textFrom'>"+ result.pr.name +"</span></span><span id='textLeadRegister' style='display:inline;' class='pull-right'>"+ leadRegister +"</span><br>"
              appendHeader = appendHeader + "<span style='display:inline'>Subject: <span id='textSubject'>"+ result.pr.title +"</span></span><span style='display:inline;' class='pull-right'><b>Quote Number</b></span></br>"
              appendHeader = appendHeader + '<span>Address: <span id="textQuoteNumber" style="display:inline;" class="pull-right">'+ quoteNumber +'</span></span><br>'
              appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br>'
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
                  append = append + '<input readonly class="form-control" style="font-size: 12px; important" type="" name="" value="'+ item.name_product +'">'
                append = append + '</td>'
                append = append + '<td width="35%">'
                  append = append + '<textarea readonly class="form-control" style="height: 250px;resize: none;height: 120px;font-size: 12px; important">' + item.description.replaceAll("<br>","\n") + '</textarea>'
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
                  append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal_product) +'" style="width:100px;font-size: 12px; important">'
                append = append + '</td>'
                append = append + '<td width="15%">'
                  append = append + '<input readonly id="grandTotalPreviewFinalPage" class="form-control grandTotalPreviewFinalPage" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px; important">'
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
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
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

            var sum = 0
            $('.grandTotalPreviewFinalPage').each(function() {
                var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
                sum += temp;
            });
           
            $("#inputFinalPageGrandPrice").val(formatter.format(sum))
          }
        })
  										
  		} else {
  			console.log(n)
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
  			}else if (n == 1) {
  				$(".modal-title").text('Information Product')
  				$(".modal-dialog").removeClass('modal-lg')	
  				$("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
  				$("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')				
  				document.getElementById("prevBtnAdd").style.display = "inline";
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
  				$("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')				
  				$("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
  				document.getElementById("prevBtnAdd").style.display = "inline";

          $.ajax({
            url: "{{url('/admin/getLead')}}",
            type: "GET",
            success: function(result) {
              $("#selectLeadId").select2({
                  data: result.data
              }).on('change', function() {
                var data = $("#selectLeadId option:selected").text();
                console.log(data)
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

          $.ajax({
            url: "{{url('/admin/getQuote')}}",
            type: "GET",
            success: function(result) {
              $("#selectQuoteNumber").select2({
                  data: result.data
              })
            }
          }) 

          $.ajax({
            url: "{{url('/admin/getLead')}}",
            type: "GET",
            success: function(result) {
              $("#selectLeadId").select2({
                  data: result.data
              })
            }
          }) 

          $.ajax({
            url: "{{url('/admin/getPid')}}",
            type: "GET",
            success: function(result) {
              $("#selectPid").select2({
                  data: result.data
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

  				$(".modal-title").text('Term Of Payment')		
          $(".modal-dialog").removeClass('modal-lg')   
  				$("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')				
  				$("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
  				document.getElementById("prevBtnAdd").style.display = "inline";

  			}
  			document.getElementById("nextBtnAdd").innerHTML = "Next"
  			$("#nextBtnAdd").prop("disabled",false)
  			$("#addProduct").attr('onclick','nextPrevAdd(-1)')
  		}
  		$("#ModalDraftPr").modal('show') 
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
    }) 

    $('#ModalDraftPrAdmin').on('hidden.bs.modal', function () {
      if (window.location.href.split("/")[5] != undefined) {
        location.replace("{{url('/admin/draftPR')}}/")
      }
      $(".tab-cek").css('display','none')
      currentTab = 0
      n = 0
    })

    function addDraftPrPembanding(n){
      console.log("current_tab",n)
      var x = document.getElementsByClassName("tab-add");
      x[n].style.display = "inline";
      if (n == (x.length - 1)) {
        $(".modal-dialog").addClass('modal-lg')
        $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
        $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
        $(".modal-title").text('')
        document.getElementById("prevBtnAdd").style.display = "inline";
        $("#headerPreviewFinal").empty()
        console.log(n)
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
              appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br>'
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
                  append = append + '<textarea style="font-size: 12px; important;height:250px;resize:none" readonly class="form-control">' + item.description.replaceAll("<br>","\n") + '</textarea>'
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
            appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
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
                var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
                sum += temp;
            });
           
            $("#inputFinalPageGrandPrice").val(formatter.format(sum))
          }
        })
                      
      } else {
        console.log(n)
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

            // if(localStorage.getItem('isPembandingEPR') == 'true'){
            //   $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(2)')
            // }else{
            //   $("#nextBtnAdd").attr('onclick','nextPrevAddPembanding(1)')
            // }
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
              $("#selectLeadId").select2({
                  data: result.data
              }).on('change', function() {
                var data = $("#selectLeadId option:selected").text();
                console.log(data)
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
          // if(localStorage.getItem('isPembandingEPR') == 'true'){
          //   $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-2)')
          // }else{
          //   $("#prevBtnAdd").attr('onclick','nextPrevAddPembanding(-1)')        
          // }       
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

    if (window.location.href.split("/")[5] != undefined) {
      if (window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'saved') {
        cekByAdmin(0,window.location.href.split("/")[5],window.location.href.split("/")[5].split("?")[1].split("=")[1])
      }else if (window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'reject' || window.location.href.split("/")[5].split("?")[1].split("=")[1] == 'revision') {
        unfinishedDraft(0,window.location.href.split("/")[5].split("?")[0],window.location.href.split("/")[5].split("?")[1].split("=")[1])
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
                  console.log("reject")
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
  					console.log(n)
  					if (n == 0) {
  						$("#inputToCek").val(result.pr.to)
  						$("#selectTypeCek").val(result.pr.type_of_letter)
  						$("#inputEmailCek").val(result.pr.email)
  						$("#inputPhoneCek").val(result.pr.phone)
  						$("#inputFax").val(result.pr.fax)
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

  						var append = ""
  						var i = 0
  						$("#tbodyProductsCek").empty()
  						$.each(result.product,function(value,item){
  							i++
  		           append = append + '<tr>'
  								append = append + '<td>'
  									append = append + '<span style="font-size: 12px; important">'+ i +'</span>'
  								append = append + '</td>'
  								append = append + '<td width="20%">'
  									append = append + '<input id="inputNameProductEdit" data-value="" readonly style="font-size: 12px; important" class="form-control" type="" name="" value="'+ item.name_product +'">'
  								append = append + '</td>'
  								append = append + '<td width="30%">'
  									append = append + '<textarea id="textAreaDescProductEdit" readonly data-value="" style="font-size: 12px;resize: none;height: 150px;important" class="form-control">'+ item.description.replaceAll("<br>","\n") +''
  									append = append + '</textarea>'
  								append = append + '</td>'
  								append = append + '<td width="7%">'
  									append = append + '<input id="inputQtyEdit" data-value="" readonly style="font-size: 12px; important" class="form-control" type="" name="" value="'+ item.qty +'">'
  								append = append + '</td>'
  								append = append + '<td width="10%">'
  								append = append + '<select id="inputTypeEdit" readonly data-value="" style="font-size: 12px; important" class="form-control">'
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
  									append = append + '<input id="inputTotalPriceEditCek" readonly data-value="" style="font-size: 12px; important" class="form-control inputTotalPriceEditCek" type="" name="" value="'+ formatter.format(item.grand_total) +'">'
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

  						$("#bottomProductsCek").empty()
  						var appendBottom = ""
  						appendBottom = appendBottom + '<hr>'
  				  	appendBottom = appendBottom + '<div class="form-group">'
  				  		appendBottom = appendBottom + '<div class="row">'
  				    		appendBottom = appendBottom + '<div class="col-md-12">'
  				    			appendBottom = appendBottom + '<div class="pull-right">'
		    				  appendBottom = appendBottom + '<span style="display: inline;margin-right: 10px;">Grand Total</span>'
		        		appendBottom = appendBottom + '<input readonly type="text" style="width:150px;display: inline;" class="form-control" id="inputGrandTotalProductCek" name="inputGrandTotalProductCek">'
		    			appendBottom = appendBottom + '</div>'

  				    $("#bottomProductsCek").append(appendBottom)	

  				    var sum = 0
  			      $('.inputTotalPriceEditCek').each(function() {
  			          var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
  			          sum += temp;
  			      });

        			$("#inputGrandTotalProductCek").val(formatter.format(sum))
  					}else if (n == 2) {
  						$(".modal-dialog").removeClass('modal-lg')
  						if ($("#selectTypeCek").val() == 'EPR') {
  							$(".modal-title").text('External Purchase Request')
  							$("#formForPrExternalCek").show()
  							$("#formForPrInternalCek").hide()		

                $("#formForPrExternalCek").find($("input[type=checkbox]")).attr('name','chk[]')

                $("#selectLeadIdCek").val(result.pr.lead_id)
                $("#selectPidCek").val(result.pr.pid)
                $("#selectQuoteNumCek").val(result.pr.quote_number)

                var pdf = "fa fa-fw fa-file-pdf-o"
                var image = "fa fa-fw fa-file-image-o"
                if (result.dokumen[0].link_drive != null) {
                  if (result.dokumen[0].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_spk_cek").show()
                  $("#link_spkCek").attr("href",result.dokumen[0].link_drive)
                  $("#inputSPKCek").val(result.dokumen[0].dokumen_location)
                  $(".icon").addClass(fa_doc)
                }

                if (result.dokumen[1].link_drive != null) {
                  if (result.dokumen[1].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_sbe_cek").show()
                  $("#link_sbeCek").attr("href",result.dokumen[1].link_drive)
                  $("#inputSBECek").val(result.dokumen[1].dokumen_location)
                  $(".icon").addClass(fa_doc)
                }
                if (result.dokumen[2].link_drive != null) {
                  if (result.dokumen[2].dokumen_location.split(".")[1] == 'pdf') {
                    var fa_doc = pdf
                  }else{
                    var fa_doc = image
                  }
                  $("#span_link_drive_quoteSup_cek").show()
                  $("#link_quoteSupCek").attr("href",result.dokumen[2].link_drive)
                  $("#inputQuoteSupplierCek").val(result.dokumen[2].dokumen_location)
                  $(".icon").addClass(fa_doc)
                }

  						}else{
  							$(".modal-title").text('Internal Purchase Request')
  							$("#formForPrInternalCek").show()
  							$("#formForPrExternalCek").hide()				

                $("#formForPrInternalCek").find($("input[type=checkbox]")).attr('name','chk[]')     

                var appendDokumen = ""
                $("#docPendukungContainerCek").empty()
                $.each(result.dokumen,function(value,item){
                  console.log(item)
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
                    // $(".icon").addClass(fa_doc)
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
                    // appendDokumen = appendDokumen + '<div style="border: 1px solid #dee2e6 !important;padding: 5px;color: #337ab7;">'
                    //   appendDokumen = appendDokumen + '<i class="icon" style="display:inline;"></i>'
                    //   appendDokumen = appendDokumen + '<input style="display:inline;width:50%" readonly value='+ item.dokumen_location +'/>'
                    // appendDokumen = appendDokumen + '</div>'
                    // // appendDokumen = appendDokumen + '<input style="display:inline;width:50%" class="form-control" readonly value='+ item.dokumen_location +'/>'
                    // appendDokumen = appendDokumen + '&nbsp<span style="display:inline" readonly>'+ item.dokumen_name +'</span>&nbsp&nbsp&nbsp'
                    // if (item.link_drive != null) {
                    //   appendDokumen = appendDokumen + '<input style="display:inline" id="doc_'+item.id_dokumen+'_pendukung" class="minimal" type="checkbox" name="chk[]" /><br><a style="font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif;" href="'+item.link_drive+'" target="_blank"><i class="fa fa-link"></i>&nbspLink drive</a>'
                    // }else{
                    //   appendDokumen = appendDokumen + '<input style="display:inline" id="doc_'+item.id_dokumen+'_pendukung" class="minimal" type="checkbox" name="chk[]" />'
                    // } 
                    // appendDokumen = appendDokumen + '</div>'
                    // $(".icon").addClass(fa_doc)
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
  						$(".modal-title").text('Term Of Payment')
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
              console.log(n)
              document.getElementById("nextBtnAddAdmin").innerHTML = "Create";

              if ($("#selectTypeCek").val() == 'IPR') {
                PRType = '<b>Internal Purchase Request</b>'
              }else{
                PRType = '<b>External Purchase Request</b>'
              }

              PRMethode = $("#selectMethode").find(":selected").text()

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
                appendHeader = appendHeader + '<span style="display:inline"><span id="textAddress" style="float:right;width:500px;float: left;">'+ result.pr.address +'</span></span><br>'
              }

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
                    append = append + '<input style="font-size: 12px; important" readonly class="form-control" type="" name="" value="'+ item.name_product +'">'
                  append = append + '</td>'
                  append = append + '<td width="35%">'
                    append = append + '<textarea style="font-size: 12px; important;height:150px;resize:none" readonly class="form-control">' + item.description.replaceAll("<br>","\n") + '</textarea>'
                  append = append + '</td>'
                  append = append + '<td width="10%">'
                    append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px; important">'
                  append = append + '</td>'
                  append = append + '<td width="10%">'
                    append = append + '<select readonly style="width:70px;font-size: 12px; important" class="form-control">'
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
                    append = append + '<input readonly class="form-control grandTotalCek" id="grandTotalCek" type="" name="" value="'+ formatter.format(item.grand_total) +'" style="width:100px;font-size: 12px; important">'
                  append = append + '</td>'
                append = append + '</tr>'
              })

              $("#tbodyFinalPageProductsCek").append(append)

              $("#bottomPreviewFinalCek").empty()  
              appendBottom = ""
              appendBottom = appendBottom + '<hr>'
              appendBottom = appendBottom + '<div class="form-group">'
                appendBottom = appendBottom + '<div class="row">'
                  appendBottom = appendBottom + '<div class="col-md-12">'
                    appendBottom = appendBottom + '<div class="pull-right">'
                      appendBottom = appendBottom + '<span style="display: inline;margin-right: 10px;">Grand Price</span>'
                      appendBottom = appendBottom + '<input readonly type="text" style="width:150px;margin-right:10px;display: inline;" class="form-control " id="inputFinalPageGrandPriceCek" name="inputFinalPageTotalPriceCek">'
                    appendBottom = appendBottom + '</div>'
                  appendBottom = appendBottom + '</div>'
                appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '</div>'
              appendBottom = appendBottom + '<hr>'
              appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms of Payment</b></span>'
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

              var sum = 0
              $('.grandTotalCek').each(function() {
                  var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
                  sum += temp;
              });

              $("#inputFinalPageGrandPriceCek").val(formatter.format(sum))  
            }
  					document.getElementById("nextBtnAddAdmin").innerHTML = "Next"
  					$("#nextBtnAddAdmin").prop("disabled",false)
  					$("#addProduct").attr('onclick','nextPrevAddAdmin(-1,'+ result.pr.id +')')
  				}
  			}
      })
  			
  		$("#ModalDraftPrAdmin").modal('show')  
  	}

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
                data:{
                  _token:'{{ csrf_token() }}',
                  no_pr:no_pr,
                  valuesChecked:arrCheck,
                  rejectReason:$("#textAreaReasonReject").val(),
                  radioConfirm:$("input[type='radio'][name='radioConfirm']:checked").val(),
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
                        'Verify PR Successfully.',
                        'success'
                    ).then((result) => {
                        if (result.value) {
                          location.replace("{{url('admin/draftPR')}}")
                        }
                    })
                }
              })          
            }
          })
        }           
      }else{
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
                data: {
                  _token:'{{ csrf_token() }}',
                  no_pr:no_pr,
                  valuesChecked:arrCheck,
                  radioConfirm:$("input[type='radio'][name='radioConfirm']:checked").val(),
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
                        'Verify PR Successfully.',
                        'success'
                    ).then((result) => {
                        if (result.value) {
                          location.replace("{{url('admin/draftPR')}}")
                        }
                    })
                }
              })          
            }
          })
      }
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

      if (val == "type_product") {
        $("#selectTypeProduct").closest('.col-md-4').removeClass('has-error')
        $("#selectTypeProduct").closest('select').next('span').hide();
        $("#selectTypeProduct").prev('.input-group-addon').css("background-color","red");
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
        $("#inputQuoteNumber").closest('input').next('span').hide();
        $("#inputQuoteNumber").prev('.input-group-addon').css("background-color","red");	
    	}

      if (val == "penawaranHarga") {
        $("#inputPenawaranHarga").closest('.form-group').removeClass('has-error')
        $("#inputPenawaranHarga").closest('div').next('span').hide();
        $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");  
      }

    	if (val == "textArea_TOP") {
    		$("#textAreaTOP").closest('.form-group').removeClass('has-error')
        $("#textAreaTOP").closest('textarea').next('span').hide();
        $("#textAreaTOP").prev('.input-group-addon').css("background-color","red");	
    	}
    }

    // function checkType(val){
    // 	console.log(val)
    // 	if (val != "") {
    // 		$("#selectType").closest('.form-group').removeClass('has-error')
    //     $("#selectType").closest('select').next('span').hide();
    //     $("#selectType").prev('.input-group-addon').css("background-color","red");

    //     $("#selectCategory").closest('.form-group').removeClass('has-error')
    //     $("#selectCategory").closest('select').next('span').hide();
    //     $("#selectCategory").prev('.input-group-addon').css("background-color","red");

    //     $("#selectPosition").closest('.form-group').removeClass('has-error')
    //     $("#selectPosition").closest('select').next('span').hide();
    //     $("#selectPosition").prev('.input-group-addon').css("background-color","red");

    //     if (val == 'lead_id') {
    //       $("#selectLeadId").closest('.form-group').removeClass('has-error')
    //       $("#selectLeadId").closest('select').next('span help-block').hide();
    //       $("#selectLeadId").prev('.input-group-addon').css("background-color","red");
    //     }

    //     if (val == 'pid') {
    //       $("#selectPid").closest('.form-group').removeClass('has-error')
    //       $("#selectPid").closest('select').next('span help-block').hide();
    //       $("#selectPid").prev('.input-group-addon').css("background-color","red");
    //     }

    //     if (val == 'quoteNumber') {
    //       $("#selectQuoteNumber").closest('.form-group').removeClass('has-error')
    //       $("#selectQuoteNumber").closest('select').next('span help-block').hide();
    //       $("#selectQuoteNumber").prev('.input-group-addon').css("background-color","red");
    //     }     

    //     if (val == 'penawaran_harga') {
    //       $("#inputPenawaranHarga").closest('.form-group').removeClass('has-error')
    //       $("#inputPenawaranHarga").closest('input').next('span').hide();
    //       $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");
    //     }
    // 	}
    // }

  	function addTable(n){ 
      if (window.location.href.split("/")[6] == undefined) {
        url = "{{url('/admin/getProductPr')}}"
        no_pr = localStorage.getItem('no_pr')
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
          	$("#tbodyProducts").empty()
  					var append = ""
  					var i = 0
            var value = 0
  					$.each(result.data,function(value,item){
  					 i++
             value = value
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
  						append = append + '<td width="8%">'
  							append = append + '<button type="button" data-value="'+ value +'" class="btn btn-xs btn-warning fa fa-edit" style="width:25px;height:25px;margin-right:5px"></button>'
  							// append = append + '<button type="button" data-value="'+ value +'" class="btn btn-xs btn-danger fa fa-trash" style="width:25px;height:25px"></button>'
  						append = append + '</td>'
  					append = append + '</tr>'		
  				})		

  				$("#tbodyProducts").append(append)

          $("#bottomProducts").empty()

          var appendBottom = ""
          appendBottom = appendBottom + '<hr>'
          appendBottom = appendBottom + '<div class="form-group">'
            appendBottom = appendBottom + '<div class="row">'
              appendBottom = appendBottom + '<div class="col-md-12">'
                appendBottom = appendBottom + '<div class="pull-right">'
                  appendBottom = appendBottom + '<span style="display: inline;margin-right: 10px;">Grand Total</span>'
                  appendBottom = appendBottom + '<input readonly type="text" style="width:150px;display: inline;" class="form-control inputGrandTotalProduct" id="inputGrandTotalProduct" name="inputGrandTotalProduct">'
                appendBottom = appendBottom + '</div>'

          $("#bottomProducts").append(appendBottom) 

          var sum = 0
          $('.inputTotalPriceEdit').each(function() {
              var temp = parseInt(($(this).val() == "" ? "0" : $(this).val()).replace(/\D/g, ""))
              sum += temp;
          });

          $("#inputGrandTotalProduct").val(formatter.format(sum)) 

          $(".fa-edit[data-value='"+ value +"']").click(function(){
            $("#prevBtnAdd").css("display", "none");
            nextPrevUnFinished(-1)
            localStorage.setItem('isEditProduct',true)
            localStorage.setItem('id_product',result.data[value].id_product)
            nominal = result.data[value].nominal_product
            $("#inputNameProduct").val(result.data[value].name_product)
            $("#inputDescProduct").val(result.data[value].description.replaceAll("<br>","\n"))
            $("#inputQtyProduct").val(result.data[value].qty)
            $("#selectTypeProduct").val(result.data[value].unit)
            $("#inputPriceProduct").val(formatter.format(nominal))
            $("#inputSerialNumber").val(result.data[value].serial_number)
            $("#inputPartNumber").val(result.data[value].part_number)
            $("#inputTotalPrice").val(formatter.format(result.data[value].grand_total))
          })
  			}
      })
  	}

    function nextPrevUnFinished(n){
      console.log("value", n)
      console.log("current_tab_next",currentTab)
      if (currentTab == 0) {
        if ($("#inputTo").val() == "") {
          $("#inputTo").closest('.form-group').addClass('has-error')
          $("#inputTo").closest('input').next('span').show();
          $("#inputTo").prev('.input-group-addon').css("background-color","red");
        }else if ($("#selectType").val() == "") {
          $("#selectType").closest('.form-group').addClass('has-error')
          $("#selectType").closest('select').next('span').show();
          $("#selectType").prev('.input-group-addon').css("background-color","red");
        }else if ($("#inputEmail").val() == "") {
          $("#inputEmail").closest('.form-group').addClass('has-error')
          $("#inputEmail").closest('input').next('span').show();
          $("#inputEmail").prev('.input-group-addon').css("background-color","red");
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
          $.ajax({
            type:"POST",
            url:"{{url('/admin/updateSupplier/')}}",
            data:{
              _token:"{{ csrf_token() }}",
              inputTo:$("#inputTo").val(),
              selectType:$("#selectType").val(),
              inputEmail:$("#inputEmail").val(),
              inputPhone:$("#inputPhone").val(),
              inputFax:$("#inputFax").val(),
              inputAttention:$("#inputAttention").val(),
              inputSubject:$("#inputSubject").val(),
              inputAddress:$("#inputAddress").val(),
              selectMethode:$("#selectMethode").val(),
              selectPosition:$("#selectPosition").val(),
              selectCategory:$("#selectCategory").val(),
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
              var x = document.getElementsByClassName("tab-add");
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
                 inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,''),
                 inputTotalPrice:$("#inputTotalPrice").val(),
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
                  unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                  localStorage.setItem('isEditProduct',false)
                  
                  addTable(0)
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
                  unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                  
                  addTable(0)
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
          }else{
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
            addTable()
          }                
        }       
      }else if (currentTab == 3) {
        if ($("#selectType").val() == 'IPR') {
          if ($("#inputPenawaranHarga").val() == "") {
            $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
            $("#inputPenawaranHarga").closest('div').next('span').show();
            $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red"); 
          }else{
            $.ajax({
              type: "GET",
              url: "{{url('/admin/getPreviewPr')}}",
              data: {
                no_pr:localStorage.getItem("no_pr"),
              },
              success:function(result){
                let formData = new FormData();
                const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
                if (result.dokumen.length > 0) {
                  console.log(result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1])
                  if ($('#inputPenawaranHarga').prop('files')[0].name.replace("/","") != result.dokumen[0].dokumen_location.substring(0,15) + '....'+ result.dokumen[0].dokumen_location.split(".")[0].substring(result.dokumen[0].dokumen_location.length -10) + "." + result.dokumen[0].dokumen_location.split(".")[1]) {
                    formData.append('inputPenawaranHarga', filepenawaranHarga)
                  } else {
                    formData.append('inputPenawaranHarga', '-')
                  }
                }else{
                  formData.append('inputPenawaranHarga', filepenawaranHarga);
                }

                var arrInputDocPendukung = []
                $('#tableDocPendukung .trDocPendukung').each(function() {
                  formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
                  arrInputDocPendukung.push({
                    nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                    no_pr:localStorage.getItem('no_pr')
                  })
                });

                formData.append('_token',"{{csrf_token()}}")
                formData.append('arrInputDocPendukung',JSON.stringify(arrInputDocPendukung))
                formData.append('no_pr',localStorage.getItem('no_pr'))

                if (n == 1) {
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
                      unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
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
                  unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                }                 
              }
            })           
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
          }else if ($("#inputQuoteNumber").val() == "") {
            $("#inputQuoteNumber").closest('.col-md-6').addClass('has-error')
            $("#inputQuoteNumber").closest('input').next('span').show();
            $("#inputQuoteNumber").prev('.col-md-6').css("background-color","red");
          }else{
            $.ajax({
              type: "GET",
              url: "{{url('/admin/getPreviewPr')}}",
              data: {
                no_pr:localStorage.getItem("no_pr"),
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
                    if (result.dokumen[0].dokumen_location != $('#inputSPK').prop('files')[0].name.replace("/","")) {
                      formData.append('inputSPK', fileSpk);
                    } else {
                      formData.append('inputSPK', "-");
                    }
                  }else{
                    formData.append('inputSPK', fileSpk);
                  }                  

                  if (result.dokumen[1] !== undefined) {
                    if (result.dokumen[1].dokumen_location != $('#inputSBE').prop('files')[0].name.replace("/","")) {
                      formData.append('inputSBE', fileSbe);
                    } else {
                      formData.append('inputSBE', "-");
                    }
                  }else{
                      formData.append('inputSBE', fileSbe);
                  }

                  if (result.dokumen[2] !== undefined) {
                    if (result.dokumen[2].dokumen_location != $('#inputQuoteSupplier').prop('files')[0].name.replace("/","")) {
                      formData.append('inputQuoteSupplier', fileQuoteSupplier);
                    } else {
                      formData.append('inputQuoteSupplier', "-");
                    }
                  }else{
                      formData.append('inputQuoteSupplier', fileQuoteSupplier);
                  }
                  
                }else{
                  formData.append('inputSPK', fileSpk);
                  formData.append('inputQuoteSupplier', fileQuoteSupplier);
                  formData.append('inputSBE', fileSbe);
                }
                

                formData.append('_token',"{{csrf_token()}}")
                formData.append('no_pr', localStorage.getItem('no_pr'))
                formData.append('selectLeadId', $("#selectLeadId").val())
                formData.append('selectPid', $("#selectPid").val())
                formData.append('inputPid',$("#projectIdInputNew").val())
                formData.append('selectQuoteNumber', $("#selectQuoteNumber").val())

                if(n == 1){
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
                      // localStorage.setItem('isStoreSupplier',true)
                      Swal.close()
                      var x = document.getElementsByClassName("tab-add");
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
                  var x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
                }                
              }
            })            
          } 
        }
      }else if (currentTab == 4) {
        if ($("#textAreaTOP").val() == "") {
          $("#textAreaTOP").closest('.form').addClass('has-error')
          $("#textAreaTOP").closest('textarea').next('span').show();
          $("#textAreaTOP").prev('.form').css("background-color","red");
        }

        $.ajax({
          url: "{{'/admin/storeTermPayment'}}",
          type: 'post',
          data:{
            no_pr:localStorage.getItem('no_pr'),
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
            unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
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
        unfinishedDraft(currentTab,localStorage.getItem('no_pr'),localStorage.getItem("status_unfinished"));
      }
    }

  	function nextPrevAdd(n,value) {
      console.log("value",value)
  		if (currentTab == 0) {
  			if ($("#inputTo").val() == "") {
  	      $("#inputTo").closest('.form-group').addClass('has-error')
  	      $("#inputTo").closest('input').next('span').show();
  	      $("#inputTo").prev('.input-group-addon').css("background-color","red");
  	    }else if ($("#selectType").val() == "") {
  	      $("#selectType").closest('.form-group').addClass('has-error')
  	      $("#selectType").closest('select').next('span').show();
  	      $("#selectType").prev('.input-group-addon').css("background-color","red");
  	    }else if ($("#inputEmail").val() == "") {
  	      $("#inputEmail").closest('.form-group').addClass('has-error')
  	      $("#inputEmail").closest('input').next('span').show();
  	      $("#inputEmail").prev('.input-group-addon').css("background-color","red");
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
          if (value == true) {
            isStoreSupplier = localStorage.getItem('isStoreSupplier')
            if (isStoreSupplier == 'false') {
              console.log("apa ini")
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
                          inputTo:$("#inputTo").val(),
                          selectType:$("#selectType").val(),
                          inputEmail:$("#inputEmail").val(),
                          inputPhone:$("#inputPhone").val(),
                          inputFax:$("#inputFax").val(),
                          inputAttention:$("#inputAttention").val(),
                          inputSubject:$("#inputSubject").val(),
                          inputAddress:$("#inputAddress").val(),
                          selectMethode:$("#selectMethode").val(),
                          selectPosition:$("#selectPosition").val(),
                          selectCategory:$("#selectCategory").val()
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
                          console.log("result",result)
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
        }else if ($("#selectTypeProduct").val() == "" || $("#selectTypeProduct").val() == null) {
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
              url: "{{url('/admin/storeProductPr')}}",
              type: 'post',
              data: {
               _token:"{{ csrf_token() }}",
               no_pr:localStorage.getItem('no_pr'),
               inputNameProduct:$("#inputNameProduct").val(),
               inputDescProduct:$("#inputDescProduct").val().replaceAll("\n","<br>"),
               inputQtyProduct:$("#inputQtyProduct").val(),
               selectTypeProduct:$("#selectTypeProduct").val(),
               inputPriceProduct:$("#inputPriceProduct").val().replace(/\./g,''),
               inputTotalPrice:$("#inputTotalPrice").val(),
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
                  var x = document.getElementsByClassName("tab-add");
                  x[currentTab].style.display = "none";
                  currentTab = currentTab + n;
                  if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                  }
                  addDraftPr(currentTab);
                  
                  addTable(0)
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
            var x = document.getElementsByClassName("tab-add");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
              x[n].style.display = "none";
              currentTab = 0;
            }
            addDraftPr(currentTab);
            unfinishedDraft(currentTab);
            addTable()
          }                
        } 			
  		}else if (currentTab == 3) {
  			if ($("#selectType").val() == 'IPR') {
  				if ($("#inputPenawaranHarga").val() == "") {
  					$("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
  		      $("#inputPenawaranHarga").closest('div').next('span').show();
  		      $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red"); 
  				}else{
            let formData = new FormData();
            const filepenawaranHarga = $('#inputPenawaranHarga').prop('files')[0];
            if (filepenawaranHarga != "") {
              formData.append('inputPenawaranHarga', filepenawaranHarga);
              // formData.append('nama_file_penawaranHarga', nama_file_penawaranHarga);
            }

            $(".tableDocPendukung").empty()

            var arrInputDocPendukung = []
            $('#tableDocPendukung .trDocPendukung').each(function() {
              // formData.append('no_pr',localStorage.getItem('no_pr'))
              formData.append('inputDocPendukung[]',$(this).find('#inputDocPendukung').prop('files')[0])
              // formData.append('inputNameDocPendukung',$(this).find('#inputNameDocPendukung').val())
              arrInputDocPendukung.push({
                nameDocPendukung:$(this).find('#inputNameDocPendukung').val(),
                no_pr:localStorage.getItem('no_pr')
              })
            });

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
  				if ($("#selectLeadId").val() == "") {
  		      $("#selectLeadId").closest('.col-md-6').addClass('has-error')
  		      $("#selectLeadId").closest('select').next('span help-block').show();
  		      $("#selectLeadId").prev('.col-md-6').css("background-color","red");
  		    }else if ($("#selectPid").val() == "") {
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
  		    }else if ($("#inputQuoteNumber").val() == "") {
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

            // const nama_file_quote_number = $('#inputQuoteNumber').prop('files')[0];
            // var nama_file_quote_number = $('#inputQuoteNumber').val();
            // let formData = new FormData();
            // if (nama_file_quote_number!="" && fileQuoteNumber!="") {
            //   formData.append('inputQuoteNumber', fileQuotenumber);
            //   formData.append('nama_file_quote_number', nama_file_quote_number);
            // }

            formData.append('_token',"{{csrf_token()}}")
            formData.append('no_pr', localStorage.getItem('no_pr'))
            formData.append('selectLeadId', $("#selectLeadId").val())
            formData.append('selectPid', $("#selectPid").val())
            formData.append('inputPid',$("#projectIdInputNew").val())
            formData.append('selectQuoteNumber', $("#selectQuoteNumber").val())

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
                  console.log("result",result)
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
  		}else if (currentTab == 4) {
  			if ($("#textAreaTOP").val() == "") {
  				$("#textAreaTOP").closest('.form').addClass('has-error')
  	      $("#textAreaTOP").closest('textarea').next('span').show();
  	      $("#textAreaTOP").prev('.form').css("background-color","red");
  			}

        $.ajax({
          url: "{{'/admin/storeTermPayment'}}",
          type: 'post',
          data:{
            no_pr:localStorage.getItem('no_pr'),
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
            addDraftPr(currentTab);
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
  			addDraftPr(currentTab);
  		}
  		
  		console.log("value", n)
  		console.log("current_tab_next",currentTab)
  	}

    function nextPrevAddPembanding(n,value) {
      console.log("value",value)
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
              console.log("apa ini")
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
                          inputFax:$("#inputFax").val(),
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
            console.log(result)
            if (result.type_of_letter == 'IPR') {
              if ($("#inputPenawaranHarga").val() == "") {
                $("#inputPenawaranHarga").closest('.form-group').addClass('has-error')
                $("#inputPenawaranHarga").closest('div').next('span').show();
                $("#inputPenawaranHarga").prev('.input-group-addon').css("background-color","red");
              }else{
                console.log("submit")
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
                    no_pr:localStorage.getItem('no_pembanding')
                  })
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
                $("#inputQuoteSupplier").closest('input').next('span').show();
                $("#inputQuoteSupplier").prev('.col-md-6').css("background-color","red");
              }else if ($("#inputQuoteNumber").val() == "") {
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
      console.log("value", n)
      console.log("current_tab_next",currentTab)
    } 

    function nextPrevAddAdmin(n,no_pr) {
  		var x = document.getElementsByClassName("tab-cek");
      x[currentTab].style.display = "none";
  		currentTab = currentTab + n;
  		if (currentTab >= x.length) {
  			x[n].style.display = "none";
  			currentTab = 0;
  		}
  		cekByAdmin(currentTab,no_pr);
  		console.log("value", n)
  		console.log("current_tab_next",currentTab)
    }


    var incrementDoc = 0
    function addDocPendukung(){
      console.log("inputDocPendukung_" + incrementDoc)
      $("#titleDoc").show()
    	append = ""
        append = append + "<tr style='height:10px' class='trDocPendukung'>"
          append = append + "<td>"
            append = append + '<button type="button" class="fa fa-times btnRemoveAddDocPendukung" style="display:inline;color:red;background-color:transparent;border:none"></button>&nbsp'
            append = append + '<label for="inputDocPendukung" style="margin-bottom:0px">'
            append = append + '<span class="fa fa-cloud-upload" style="display:inline"></span>'
            append = append + '<input style="display:inline" class=" inputDocPendukung_'+ incrementDoc +'" type="file" name="inputDocPendukung" id="inputDocPendukung" data-value="'+incrementDoc+'">'
            append = append + '</label>'
          append = append + "</td>"
          append = append + "<td>"
            append = append + '<input style="width:250px;margin-left:20px" class="form-control inputNameDocPendukung_'+ incrementDoc+'" name="inputNameDocPendukung" id="inputNameDocPendukung">'
          append = append + "</td>"
        append = append + "</tr>"
    	$("#tableDocPendukung").append(append) 
      incrementDoc++

      // $("input[name='inputDocPendukung']").each(function(){
      //   $('.inputDocPendukung_'+ $(this).data("value") +'').on('input', this, function(){
      //   console.log(this.value)

      //     if (this.value != "") {
      //       $("#inputDocPendukung[data-value='"+ $(this).data("value") +"']").removeClass("hidden")
      //       $("#inputDocPendukung[data-value='"+ $(this).data("value") +"']").closest("input").next("label").hide()
      //     }else{
      //       $("#inputDocPendukung[data-value='"+ $(this).data("value") +"']").addClass("hidden")
      //       $("#inputDocPendukung[data-value='"+ $(this).data("value") +"']").closest("input").next("label").show()
      //     }
      //   });
      // });
    }

    $(document).on('click', '.btnRemoveAddDocPendukung', function() {
      $(this).closest("tr").remove();
      if($('#tableDocPendukung tr').length == 0){
        console.log("0")
        $("#titleDoc").hide()
      }
    });

    function createPR(status){
      console.log(status)
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
                  status_revision:status
                },
                success: function(result){
                  Swal.fire({
                    title: 'Drafting PR Successs',
                    html: "<p style='text-align:center;'>Your PR draft will be verified by Admin/Procurement soon, please wait for further progress</p>",
                    type: 'success',
                    confirmButtonText: 'Reload',
                  }).then((result) => {
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
      addTable()
    }

    $('#makeId').click(function(){
      $('#project_idNew').show()
      $('#project_id').val("").select2().trigger("change")
    })

    $('#removeNewId').click(function(){
      $('#project_idNew').hide('slow')
      $('#projectIdInputNew').val('')
    })

  </script>
@endsection