@extends('template.main')
@section('tittle')
    Quotation List
@endsection
@section('head_css')
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">
    <link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.css">
    <link as="style" onload="this.onload=null;this.rel='stylesheet'" rel="preload" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
    <link rel="preload" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
    <link rel="preload" href="{{asset('/plugins/iCheck/all.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-flash.min.css" />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.min.css" integrity="sha512-Bhi4560umtRBUEJCTIJoNDS6ssVIls7oiYyT3PbhxZV+9uBbLVO/mWo56hrBNNbIfMXKvtIPJi/Jg+vpBpA7sg==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>

    <style type="text/css">
        .DTFC_LeftBodyLiner {
            overflow: hidden;
        }
        th{
            text-align: center;
        }
        td>.truncate{
            /*word-wrap: break-word; */
            word-break:break-all;
            white-space: normal;
            width:200px;
        }
        @media screen and (max-width: 768px) {
            .btn-action-letter{
                float: left!important;
            }
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Quotation List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin</li>
            <li class="active">Quote Number</li>
        </ol>
    </section>

    <section class="content">
        <div class="row" id="BoxId">
            <!--box id-->
        </div>
        @if (session('update'))
            <div class="alert alert-warning" id="alert">
                {{ session('update') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops->quote_number}}</h4></div>
        @endif

        @if (session('sukses'))
            <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops2->quote_number}}</h4></div>
        @endif

        @if (session('alert'))
            <div class="alert alert-success" id="alert">
                {{ session('alert') }}
            </div>
        @endif

        <div class="box">
{{--            <div class="box-header with-border">--}}
{{--                <h3 class="box-title"><i class="fa fa-table"></i> Quote Number</h3>--}}
{{--            </div>--}}
            <div class="box-body">
                <div class="row">
{{--                    <div class="col-md-2 col-xs-12">--}}
{{--                        <div class="form-group">--}}
{{--                            <select style="margin-right: 5px;width: 100px" class="form-control btn-primary btn-flat" id="year_filter">--}}
{{--                                <option value="{{$tahun}}"> &nbsp{{$tahun}}</option>--}}
{{--                                @foreach($year_before as $years)--}}
{{--                                    @if($years->year != $tahun)--}}
{{--                                        <option value="{{$years->year}}"> &nbsp{{$years->year}}</option>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    @if($role->name == 'Sales Staff' || $role->name == 'Technology Alliance')
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group btn-action-letter" style="float:right;">
                            <button type="button" class="btn btn-success btn-flat" style="width: 100px" data-target="#modalAdd" data-toggle="modal" onclick="addQuote(0)"><i class="fa fa-plus"> </i> &nbspAdd Quote</button>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row" style="margin-bottom:10px" id="filterBox">
                    <div class="col-md-2 col-xs-12">
                        <div class="form-group">
                            <label>Filter by Status : </label>
                            <select class="form-control select2" id="inputFilterStatus" onchange="searchCustom()" style="width:100%" tabindex="-1" aria-hidden="true">
                                <option value="" selected></option>
                                @foreach($quoteStatus as $status)
                                    <option value="{{$status}}">{{$status}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <div class="form-group">
                            <label>Range Date Quote : </label>
                            <button type="button" class="btn btn-default btn-flat pull-left" style="width:100%" id="inputRangeDate">
                                <i class="fa fa-calendar"></i> Date range picker
                                <span>
                                    <i class="fa fa-caret-down"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="nav-tabs-custom">
{{--                    <ul class="nav nav-tabs" id="myTab">--}}
{{--                        @foreach($status_quote as $data)--}}
{{--                            @if($data->status_backdate == 'A')--}}
{{--                                <li class="nav-item active">--}}
{{--                                    <a class="nav-link active" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')">All</a>--}}
{{--                            @elseif($data->status_backdate == 'F')--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')"> Backdate--}}
{{--                            @endif--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}

                    <div class="tab-content">

                        <div class="tab-pane active" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered nowrap table-striped dataTable data" id="data_all" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Quote Number</th>
{{--                                        <th>Position</th>--}}
{{--                                        <th>Month</th>--}}
                                        <th>Date</th>
                                        <th class="truncate">To</th>
                                        <th class="truncate">Attention</th>
                                        <th class="truncate">Title</th>
{{--                                        <th class="truncate">Project</th>--}}
{{--                                        <th class="truncate">Description</th>--}}
                                        <th>From</th>
{{--                                        <th>Division</th>--}}
                                        <th>Status</th>
                                        <th>Quotation Type</th>
                                        <th>Grand Total</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--MODAL ADD-->
        <div class="modal fade" id="modalAdd" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload()">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Add Quote</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="" id="modalAddQuote" name="modalAddQuote">
                            @csrf
                            <div class="tab-add" style="display: none;">
                                <div class="tabGroup">
                                    <div class="form-group">
                                        <label for="">Lead ID*</label>
                                        <select name="lead_id" id="leadId" class="form-control" required style="width: 100%">
                                            <option value="">--Choose Lead ID--</option>
                                            @foreach($leadId as $lead)
                                                <option value="{{$lead->lead_id}}">{{$lead->lead_id . ' - ' . $lead->opp_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block" style="display:none;">Please fill Lead ID!</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <input type="text" class="form-control" name="customer" id="customer" required disabled>
                                        <span class="help-block" style="display:none;">Please fill Customer!</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Telp*</label>
                                                <input type="text" name="no_telp" id="no_telp" class="form-control" required>
                                                <span class="help-block" style="display:none;">Please fill Telp!</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Email*</label>
                                                <input type="text" name="email" id="email" class="form-control" placeholder="ex: johndoe@gmail.com" onkeyup="fillInput('email')" required>
                                                <span class="help-block" style="display:none;">Please fill Email!</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Date*</label>
                                        <input type="date" class="form-control datepicker" name="date" id="date" required>
                                        <span class="help-block" style="display:none;">Please fill Date!</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Subject*</label>
                                        <input type="text" class="form-control" name="subject" id="subject" required>
                                        <span class="help-block" style="display:none;">Please fill Subject!</span>
                                    </div>
{{--                                    <div class="form-group">--}}
{{--                                        <label for="">Address*</label>--}}
{{--                                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Building Name&#10;Street&#10;City - Province"></textarea>--}}
{{--                                        <span class="help-block" style="display:none;">Please fill Address!</span>--}}
{{--                                    </div>--}}
                                    <div class="form-group">
                                        <label for="">Building Name</label>
                                        <input type="text" class="form-control" name="building" id="building" placeholder="ex: Gedung Inlingua">
                                        <span class="help-block" style="display: none;">Please fill Building Name!</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Street Name*</label>
                                        <input type="text" class="form-control" name="street" id="street" placeholder="ex: Jl. Puri Kencana Blok K6 No. 2M-2L" required>
                                        <span class="help-block" style="display: none;">Please fill Street Name!</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">City - Postal Code*</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="ex: Jakarta - 11610" required>
                                        <span class="help-block" style="display: none;">Please fill City - Postal Code!</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Attention*</label>
                                        <input class="form-control" placeholder="ex: John Doe" id="attention" name="attention" required>
                                        <span class="help-block" style="display:none;">Please fill Attention!</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Quotation Type</label>
                                        <input type="text" name="quote_type" id="quote_type" class="form-control" placeholder="ex: Supply Only, Maintenance">
                                        <span class="help-block" style="display:none;">Please fill Quotation Type!</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-add" style="display: none;">
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
                                        </div>
                                    </div>
                                    <div style="display: flex;">
                                    <!--              <span style="margin: 0 auto;">You can get format of CSV from this <a href="{{url('https://drive.google.com/uc?export=download&id=1IDI8NVdVskSl__qQVfsrugEamr01W4IA')}}" style="cursor:pointer;">link</a></span> -->
                                        <span style="margin: 0 auto;">You can get format of CSV from this <a href="{{url('https://drive.google.com/uc?export=download&id=1Hwpgo-RcVkmQdND7159f5l4Ah-qgcNwK')}}" style="cursor:pointer;">link</a></span>
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Qty*</label>
                                                <input autocomplete="off" type="number" name="" class="form-control" id="inputQtyProduct" placeholder="ex. 5" onkeyup="fillInput('qty_product')">
                                                <span class="help-block" style="display:none;">Please fill Qty!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom:10px">
                                            <label>Type*</label>
{{--                                            <i class="fa fa-warning" title="If type is undefined, Please contact developer team!" style="display:inline"></i>--}}
                                            <select style="width:100%;display:inline;" class="form-control" id="selectTypeProduct" placeholder="ex. Unit" onchange="fillInput('type_product')">
                                                <option>
                                            </select>
                                            <span class="help-block" style="display:none;">Please fill Unit!</span>
                                        </div>
                                        <div class="col-md-4" style="margin-bottom:10px">
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
                                                        <li><a onclick="changeCurreny('dollar')">IDR(RP)</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <span class="help-block" style="display:none;">Please fill Price!</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Price List</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    Rp.
                                                </div>
                                                <input autocomplete="off" type="text" name="" class="form-control money" id="inputPriceList" placeholder="ex. 500,000.00" onkeyup="fillInput('price_list')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Price List</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        Rp.
                                                    </div>
                                                    <input autocomplete="off" readonly type="text" name="" class="form-control" id="inputTotalPriceList" placeholder="75.000.000,00">
                                                </div>
                                            </div>
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
                                        <th>Price List</th>
                                        <th>Total Price List</th>
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
                                                    <th>Price List</th>
                                                    <th>Total Price List</th>
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
                                <button type="button" class="btn btn-default" id="prevBtnAdd">Back</button>
                                <button type="button" class="btn btn-primary" id="nextBtnAdd">Next</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div
        >

        <!-- BACKDATE -->
        <div class="modal fade" id="letter_backdate" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Quote (Backdate)</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{url('/store_quotebackdate')}}" id="quote_backdate" name="quote_backdate">
                            @csrf
                            <div class="form-group">
                                <label for="">Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right date" name="date" id="date_backdate">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Backdate Number</label>
                                <select type="text" class="form-control disabled" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" disabled>
                                </select>
                                <span id="errorname" style="color:red"></span>
                                <span class="pull-right" style="display:none;cursor: pointer;" id="addBackdateNum"><i class="fa fa-plus"></i> backdate number</span>
                            </div>
                            <div class="form-group">
                                <label for="">Position</label>
                                <select type="text" class="form-control" name="position" id="position" required>
                                    <option value="">--Choose Position--</option>
                                    <option value="TAM">TAM</option>
                                    <option value="DIR">DIR</option>
                                    <option value="MSM">MSM</option>
                                    <option value="PMS">PMS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Customer</label>
                                <select class="form-control" id="customer_quote_backdate" name="customer_quote_backdate" required style="width: 100%">
                                    @foreach($customer as $data)
                                        <option value="{{$data->id_customer}}">{{$data->customer_legal_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Attention</label>
                                <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention">
                            </div>
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title">
                            </div>
                            <div class="form-group">
                                <label for="">Project</label>
                                <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Division</label>
                                <select type="text" class="form-control" name="division" id="division" required>
                                    <option value="">--Choose Division--</option>
                                    <option>PMO</option>
                                    <option>MSM</option>
                                    <option>SAL</option>
                                    <option>PMS</option>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                              <label for="">Project ID</label>
                              <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
                            </div> -->
                            <div class="form-group" id="pid">
                                <label for="">Project ID</label>
                                <select type="text" class="form-control select2" name="project_id_backdate" id="project_id_backdate" style="width: 100%">
                                    <option value="">--Choose Project Id--</option>
                                    @foreach($pid as $data)
                                        <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Project Type</label>
                                <select class="form-control" id="project_type" name="project_type" required style="width: 100%">
                                    <option value="">--Choose Project Type--</option>
                                    <option value="Supply Only">Supply Only</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Implementation">Implementation</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                                <button type="submit" class="btn btn-primary" id="addBackdate"><i class="fa fa-check"> </i>&nbspSubmit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--MODAL EDIT-->
        <div class="modal fade" id="modalEdit" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Quote</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{url('/quote/update')}}" id="modalEditQuote" name="modalQuote">
                            @csrf
                            <div class="form-group" hidden>
                                <label>Quote Number</label>
                                <input class="form-control" id="edit_quote_number" name="quote_number">
                            </div>
                            <div class="form-group">
                                <label>Position</label>
                                <select class="form-control" id="edit_position" name="edit_position" required>
                                    <option value="TAM">TAM</option>
                                    <option value="DIR">DIR</option>
                                    <option value="MSM">MSM</option>
                                    <option value="PMS">PMS</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="date" class="form-control pull-right" name="edit_date" id="edit_date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>To</label>
                                <select id="edit_to" name="edit_to" class="form-control" style="width:100%"></select>
                                <!-- <input class="form-control" id="edit_to" placeholder="Enter To" name="edit_to" > -->
                            </div>

                            <div class="form-group">
                                <label>Attention</label>
                                <input class="form-control" id="edit_attention" placeholder="Enter Attention" name="edit_attention" >
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" id="edit_title" placeholder="Enter Title" name="edit_title" >
                            </div>

                            <div class="form-group">
                                <label>Project</label>
                                <input class="form-control" id="edit_project" name="edit_project" placeholder="Enter Project">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea class="form-control" id="edit_description" name="edit_description" placeholder="Enter Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Project ID</label>
                                <input class="form-control" id="edit_project_id" name="edit_project_id" placeholder="Enter Project ID">
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <input class="form-control" id="edit_note" name="edit_note" placeholder="Enter Note">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
                                <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspUpdate</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scriptImport')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/fixedColumns.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    <script type="text/javascript" src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.all.min.js" integrity="sha512-ng0ComxRUMJeeN1JS62sxZ+eSjoavxBVv3l7SG4W/gBVbQj+AfmVRdkFT4BNNlxdDCISRrDBkNDxC7omF0MBLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.js" integrity="sha512-SSQo56LrrC0adA0IJk1GONb6LLfKM6+gqBTAGgWNO8DIxHiy0ARRIztRWVK6hGnrlYWOFKEbSLQuONZDtJFK0Q==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#addBackdate').prop("disabled",true)

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
        })
        $('#customer_quote').select2({
            dropdownParent:$("#modalAdd")
        });
        // $("#backdate_num").select2();
        $("#customer_quote_backdate").select2({
            dropdownParent:$("#letter_backdate")
        });
        $('#leadId').select2({
           dropdownParent:$("#modalAdd")
        });
        $('#date_backdate').datepicker({
            autoclose: true,
        }).css('background-color','#fff');

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

        function searchCustom(startDate,endDate){
            var tempStatus = 'status[]=', tempUser = 'user[]=', tempStartDate = 'startDate=', tempEndDate = 'endDate='

            $.each($('#inputFilterStatus').val(),function(key,value){
                if (tempStatus == 'status[]=') {
                    tempStatus = tempStatus + value
                }else{
                    tempStatus = tempStatus + '&status[]=' + value
                }

                if(value == ''){
                    localStorage.removeItem("arrFilterBack")
                }
            })

            if (startDate != undefined) {
                tempStartDate = tempStartDate + startDate
            }else{
                localStorage.removeItem("arrFilterBack")
            }

            if (endDate != undefined) {
                tempEndDate = tempEndDate + endDate
            }else{
                localStorage.removeItem("arrFilterBack")
            }

            var temp = "?" + tempStatus + '&' + tempStartDate + '&' + tempEndDate
            showFilterData(temp)
            // DashboardCounterFilter(temp)

            if (tempStatus || tempType) {
                localStorage.setItem('isTemp',true)
                // localStorage.setItem("arrFilterBack",true)
            }else{
                localStorage.setItem('isTemp',false)
                // localStorage.removeItem("arrFilterBack",true)
            }

            return localStorage.setItem("arrFilter", temp)
        }

        function showFilterData(temp,arrStatusBack,arrTypeBack){
            Pace.restart();
            Pace.track(function() {
                $("#data_all").DataTable().ajax.url("{{url('/quote/getDataQuoteFilter')}}" + temp).load()

                // InitiateFilterParam(arrStatusBack,arrTypeBack)
            })
        }

        $("#inputFilterStatus").select2({
            placeholder: " Select Status",
            // allowClear: true,
            multiple:true,
            closeOnSelect:true,
        })

        function InitiateFilterParam(arrStatusBack,arrTypeBack){
            Pace.restart();
            Pace.track(function() {
                $.ajax({
                    url:"{{url('/quote/getDropdownFilterQuote')}}",
                    type:"GET",
                    success:function(result){
                        var arrStatus = result.dataStatus;


                        //  if ($.fn.DataTable.isDataTable('#data_all')) {
                        //     $('#data_all').DataTable().destroy();
                        // }
                        //
                        // // Kosongkan tabel jika diperlukan
                        // $('#data_all').empty();
                    }
                })
            })
        }

        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $('#project_id').select2({
            dropdownParent:$("#modalAdd")
        })

        $('#project_id_backdate').select2({
            dropdownParent:$("#letter_backdate")
        })

        $('#leadId').on('change', function(){
            $.ajax({
                url: '/sales/getDetailLead',
                type: 'GET',
                data:{
                    lead_id: $(this).val()
                },
                success: function (result) {
                    $('#customer').val(result.customer)
                    // $('#address').text(result.office_building + ' ' + result.street_address + ' ' + result.city)
                    $('#no_telp').val(result.phone)
                    $('#subject').val(result.subject)
                    $('#building').val(result.office_building)
                    $('#street').val(result.street_address)
                    $('#city').val(result.city + ' - ' + result.postal)
                    localStorage.setItem('id_customer_quote', result.id_customer)
                }
            })
        })

        $('#leadId').on('click', function(){
            $.ajax({
                url: '/sales/getDetailLead',
                type: 'GET',
                data:{
                    lead_id: $(this).val()
                },
                success: function (result) {
                    $('#customer').val(result.customer)
                    // $('#address').text(result.office_building + ' ' + result.street_address + ' ' + result.city)
                    $('#no_telp').val(result.phone)
                    $('#subject').val(result.subject)
                    // $('#building').val(result.office_building)
                    // $('#street').val(result.street_address)
                    // $('#city').val(result.city + ' - ' + result.postal)
                    localStorage.setItem('id_customer_quote', result.id_customer)
                }
            })
        })

        function cancelUploadCsv(){
            $("input[type='file'][name='uploadCsv']").val('')
            $("#uploadCsv").next('label').show()
            $("input[type='file'][name='uploadCsv']").addClass('hidden')
            $("input[type='file'][name='uploadCsv']").prev('i').show()
            $("#uploadCsv").next('label').next('i').addClass('hidden')
            $("#btnInitiateAddProduct").prop("disabled",false)
        }

        function select2TypeProduct(value){
            $.ajax({
                type:"GET",
                dataType:"json",
                url:"{{asset('/json/typePrProduct.json')}}",
                success: function(result){
                    $('#selectTypeProduct').select2({
                        data:result,
                        placeholder:'Ex. Unit',
                        dropdownParent: $('#modalAdd')
                    })
                }
            })

            if (value != undefined) {
                $('#selectTypeProduct').val(value.toLowerCase()).trigger('change')
            }
        }

        function addQuote(n){
            let x = document.getElementsByClassName("tab-add");
            x[n].style.display = "inline";
            if(n == 0){
                document.getElementById("prevBtnAdd").style.display = "none";
                $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
            }else if(n == 1){
                select2TypeProduct();
                $("nextBtnAdd").attr('onclick', 'nextPrevAdd(1)')
                $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')
                document.getElementById('prevBtnAdd').innerText = "Back";
                document.getElementById("prevBtnAdd").style.display = "inline";
                $('.money').mask('#.##0,00', {reverse: true})
                $("#btnInitiateAddProduct").click(function(){
                    $(".tabGroupInitiateAdd").hide()
                    x[n].children[1].style.display = 'inline'
                })
            }else if (n == 2){
                $("#nextBtnAdd").removeAttr('onclick')
                $(".modal-dialog").addClass('modal-lg')

                $("#prevBtnAdd").attr('onclick','nextPrevAdd(-1)')
                $("#nextBtnAdd").attr('onclick','nextPrevAdd(1)')
                document.getElementById("prevBtnAdd").style.display = "inline";

            }else if (n == 3) {
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
                document.getElementById("nextBtnAdd").innerText = "Next";
            } else {
                $(".modal-dialog").addClass('modal-lg')
                $("#prevBtnAdd").attr('onclick','nextPrevUnfinished(-1)')
                $(".modal-title").text('')
                document.getElementById("prevBtnAdd").style.display = "inline";
                $("#headerPreviewFinal").empty()
                document.getElementById("nextBtnAdd").innerText = "Create";
                $("#nextBtnAdd").attr('onclick','createQuote("saved")');

                $.ajax({
                    type: "GET",
                    url: "{{url('/sales/quote/getPreview')}}",
                    data: {
                        id_quote:localStorage.getItem('id_quote'),
                    },
                    success: function(result) {
                        var appendHeader = ""
                        appendHeader = appendHeader + '<div class="row">'
                        appendHeader = appendHeader + '    <div class="col-md-6">'
                        appendHeader = appendHeader + '        <div class="">To: '+ result.quote.to +'</div>'
                        appendHeader = appendHeader + '        <div class="">Email: ' + result.quote.email + '</div>'
                        appendHeader = appendHeader + '        <div class="">Phone: ' + result.quote.no_telp + '</div>'
                        appendHeader = appendHeader + '        <div class="">Attention: '+ result.quote.attention +'</div>'
                        appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ result.quote.address +'</div>'
                        appendHeader = appendHeader + '        <div class="">From: '+ result.quote.from +'</div>'
                        appendHeader = appendHeader + '        <div class="">Subject: '+ result.quote.title +'</div>'

                        appendHeader = appendHeader + '    </div>'
                        if (window.matchMedia("(max-width: 768px)").matches)
                        {
                            appendHeader = appendHeader + '    <div class="col-md-6">'
                            // The viewport is less than 768 pixels wide

                        } else {
                            appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                            // The viewport is at least 768 pixels wide

                        }
                        appendHeader = appendHeader + '    </div>'
                        appendHeader = appendHeader + '</div>'

                        $("#headerPreviewFinal").append(appendHeader)

                            $("#tbodyFinalPageProducts").empty()
                        var append = ""
                        var i = 0
                        var valueGrandTotal = 0;
                        function formatCurrency(value) {
                            let numericValue = parseFloat(value);
                            if (isNaN(numericValue)) numericValue = 0;
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(numericValue);
                        }
                        $.each(result.product,function(value,item){
                            i++
                            valueGrandTotal += parseFloat(item.grand_total);
                            append = append + '<tr>'
                            append = append + '<td>'
                            append = append + '<span>'+ i +'</span>'
                            append = append + '</td>'
                            append = append + '<td width="20%">'
                            append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name + "'>"
                            append = append + '</td>'
                            append = append + '<td width="35%">'
                            append = append + '<textarea readonly class="form-control" style="height: 250px;resize: none;height: 120px;font-size: 12px;">' + item.description.replaceAll("<br>","\n") + '</textarea>'
                            append = append + '</td>'
                            append = append + '<td width="10%">'
                            append = append + '<input readonly class="form-control" type="" name="" value="'+ item.qty +'" style="width:45px;font-size: 12px;">'
                            append = append + '</td>'
                            append = append + '<td width="10%">'
                            append = append + '<select disabled style="width:75px;font-size: 12px;" class="form-control">'
                            append = append + '<option>'+ item.unit.charAt(0).toUpperCase() + item.unit.slice(1) +'</option>'
                            append = append + '</select>'
                            append = append + '<td width="15%">'
                            append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.price_list) +'" style="width:100px;font-size: 12px;">'
                            append = append + '</td>'
                            append = append + '<td width="15%">'
                            append = append + '<input readonly id="grandTotalPreviewFinalPage" class="form-control " type="" name="" value="'+ formatter.format(item.total_price_list) +'" style="width:100px;font-size: 12px;">'
                            append = append + '</td>'
                            append = append + '</td>'
                            append = append + '<td width="15%">'
                            append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal) +'" style="width:100px;font-size: 12px;">'
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
                        appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;text-align:right" class="form-control inputTotalPriceFinal" id="inputTotalPriceFinal" name="inputTotalPriceFinal">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom + '</div>'

                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right" style="display:flex">'
                        appendBottom = appendBottom + '      <span> DPP Nilai Lainnya</span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;margin-left:15px;text-align:right" class="form-control" id="dpp_final" name="dpp_final">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom  + '</div>'

                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right" style="display:flex">'
                        appendBottom = appendBottom + '      <span> PPN <span id="vat_value"></span> <span class="title_service"></span></span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;margin-left:15px;text-align:right" class="form-control" id="vat_tax_final" name="vat_tax_final">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom  + '</div>'

                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right">'
                        appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;text-align:right" class="form-control inputFinalPageGrandPrice" id="inputFinalPageGrandPrice" name="inputFinalPageGrandPrice">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom + '</div>'
                        appendBottom = appendBottom + '</div>'
                        appendBottom = appendBottom + '<hr>'
                        appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
                        appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'

                        $("#bottomPreviewFinal").append(appendBottom)

                        $("#termPreview").html(result.quote.term_payment.replaceAll("&lt;br&gt;","<br>"))

                        var tempVat = 0
                        var finalVat = 0
                        var tempGrand = 0
                        var finalGrand = 0
                        var tempTotal = 0
                        var sum = 0
                        var tempDiscount = 0
                        var dpp = 0

                        sum += valueGrandTotal;

                        if (result.config.tax_vat == null || result.config.tax_vat == 0 || result.config.tax_vat == 'null') {
                            valueVat = 'false'
                        }else{
                            valueVat = result.config.tax_vat
                        }

                        if (!isNaN(valueVat)) {
                            if (valueVat == 12){
                                dpp = (parseFloat(sum)) * 11 / 12
                                tempVat = dpp * valueVat / 100
                            }else{
                                tempVat = (parseFloat(sum) * (parseFloat(result.config.tax_vat)/100))
                            }
                            finalVat = tempVat

                            tempGrand = parseFloat(sum) +  parseFloat(tempVat)

                            tempTotal = sum

                            $('#vat_value').text(valueVat + '%')
                        }else{
                            tempGrand = sum

                            $('#vat_value').text("")
                        }

                        // if (data.config.tax_vat > 0){
                        //     tempVat = formatter.format((parseFloat(sum) * data.config.tax_vat) / 100)
                        //     tempGrand =  parseFloat(sum) + parseFloat((parseFloat(sum) * data.config.tax_vat) / 100)
                        // }else{
                        //     tempGrand = parseFloat(sum)
                        // }

                        finalVat = tempVat

                        finalGrand = tempGrand

                        tempTotal = sum

                        $('#dpp_final').val(formatCurrency(dpp))
                        $("#vat_tax_final").val(formatCurrency(tempVat))
                        $("#inputTotalPriceFinal").val(formatCurrency(sum))
                        $("#inputFinalPageGrandPrice").val(formatCurrency(tempGrand))
                        $("#inputDiscountFinal").val(tempDiscount)
                    }
                })

            }
            document.getElementById("nextBtnAdd").innerHTML = "Next"
            $("#nextBtnAdd").prop("disabled",false)
            $("#addProduct").attr('onclick','nextPrevAdd(-1)')
        }

        function unfinishedDraft(n,id_quote,status){
            localStorage.setItem('id_quote', id_quote)
            $.ajax({
                type: 'GET',
                url: '/sales/quote/getPreview',
                data: {
                    id_quote: id_quote
                },
                success: function (data) {
                    let x = document.getElementsByClassName("tab-add");
                    x[n].style.display = "inline";
                    if(n == 0){
                        document.getElementById("prevBtnAdd").style.display = "none";
                        if(data.product.length > 0){
                            $("#nextBtnAdd").attr('onclick','nextPrevUnfinished(2)')
                        }else{
                            $("#nextBtnAdd").attr('onclick','nextPrevUnfinished(1)')
                        }
                        $('#leadId').val(data.quote.lead_id).trigger('change')
                        // $('#leadId').val(data.quote.lead_id).trigger('click')
                        $('#subject').val(data.quote.title)
                        $('#date').val(data.quote.date)
                        $('#email').val(data.quote.email)
                        $('#building').val(data.quote.building)
                        $('#street').val(data.quote.street)
                        $('#city').val(data.quote.city)
                        $('#attention').val(data.quote.attention)
                        $('#quote_type').val(data.quote.project_type)
                    }else if(n == 1){
                        select2TypeProduct();
                        $("nextBtnAdd").attr('onclick', 'nextPrevUnfinished(1)')
                        $("#prevBtnAdd").attr('onclick','nextPrevUnfinished(-1)')
                        document.getElementById('prevBtnAdd').innerText = "Back";
                        document.getElementById("prevBtnAdd").style.display = "inline";
                        $('.money').mask('#.##0,00', {reverse: true})
                        $("#btnInitiateAddProduct").click(function(){
                            $(".tabGroupInitiateAdd").hide()
                            x[n].children[1].style.display = 'inline'
                        })
                    }else if (n == 2){
                        if(data.config == null || data.config == 'null'){
                            localStorage.setItem('store_tax', '')
                            localStorage.setItem('status_tax', 12)
                        }else{
                            localStorage.setItem('store_tax', data.config.id)
                            localStorage.setItem('status_tax', data.config.tax_vat)
                        }

                        $("#nextBtnAdd").removeAttr('onclick')
                        $(".modal-dialog").addClass('modal-lg')
                        addTable(0, localStorage.getItem('status_tax'))
                        $('#addProduct').attr('onclick', 'nextPrevUnfinished(-1)')

                        $("#prevBtnAdd").attr('onclick','nextPrevUnfinished(-1)')
                        $("#nextBtnAdd").attr('onclick','nextPrevUnfinished(1)')
                        document.getElementById("prevBtnAdd").style.display = "inline";

                    }else if (n == 3) {
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

                        if(data.quote.term_payment != null ){
                            $("#textAreaTOP").val(data.quote.term_payment)
                        }
                        $(".modal-title").text('Terms & Condition')
                        $(".modal-dialog").removeClass('modal-lg')
                        $("#prevBtnAdd").attr('onclick','nextPrevUnfinished(-1)')
                        $("#nextBtnAdd").attr('onclick','nextPrevUnfinished(1)')
                        document.getElementById("prevBtnAdd").style.display = "inline";
                        document.getElementById("nextBtnAdd").innerText = "Next";
                    } else {
                        $(".modal-dialog").addClass('modal-lg')
                        $("#prevBtnAdd").attr('onclick','nextPrevUnfinished(-1)')
                        $(".modal-title").text('')
                        document.getElementById("prevBtnAdd").style.display = "inline";
                        $("#headerPreviewFinal").empty()
                        document.getElementById("nextBtnAdd").innerText = "Create";
                        $("#nextBtnAdd").attr('onclick','createQuote("saved")');

                        var appendHeader = ""
                        appendHeader = appendHeader + '<div class="row">'
                        appendHeader = appendHeader + '    <div class="col-md-6">'
                        appendHeader = appendHeader + '        <div class="">To: '+ data.quote.to +'</div>'
                        appendHeader = appendHeader + '        <div class="">Email: ' + data.quote.email + '</div>'
                        appendHeader = appendHeader + '        <div class="">Phone: ' + data.quote.no_telp + '</div>'
                        appendHeader = appendHeader + '        <div class="">Attention: '+ data.quote.attention +'</div>'
                        appendHeader = appendHeader + '        <div class="" style="width:fit-content;word-wrap: break-word;">Address: '+ data.quote.address +'</div>'
                        appendHeader = appendHeader + '        <div class="">From: '+ data.quote.from +'</div>'
                        appendHeader = appendHeader + '        <div class="">Subject: '+ data.quote.title +'</div>'

                        appendHeader = appendHeader + '    </div>'
                        if (window.matchMedia("(max-width: 768px)").matches)
                        {
                            appendHeader = appendHeader + '    <div class="col-md-6">'
                            // The viewport is less than 768 pixels wide

                        } else {
                            appendHeader = appendHeader + '    <div class="col-md-6" style="text-align:end">'
                            // The viewport is at least 768 pixels wide

                        }
                        appendHeader = appendHeader + '    </div>'
                        appendHeader = appendHeader + '</div>'

                        $("#headerPreviewFinal").append(appendHeader)

                        $("#tbodyFinalPageProducts").empty()
                        var append = ""
                        var i = 0
                        var valueGrandTotal = 0;
                        function formatCurrency(value) {
                            let numericValue = parseFloat(value);
                            if (isNaN(numericValue)) numericValue = 0;
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(numericValue);
                        }
                        $.each(data.product,function(value,item){
                            i++
                            valueGrandTotal += parseFloat(item.grand_total)
                            append = append + '<tr>'
                            append = append + '<td>'
                            append = append + '<span>'+ i +'</span>'
                            append = append + '</td>'
                            append = append + '<td width="20%">'
                            append = append + "<input data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name + "'>"
                            append = append + '</td>'
                            append = append + '<td width="35%">'
                            append = append + '<textarea readonly class="form-control" style="height: 250px;resize: none;height: 120px;font-size: 12px;">' + item.description.replaceAll("<br>","\n") + '</textarea>'
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
                            append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.price_list) +'" style="width:100px;font-size: 12px;">'
                            append = append + '</td>'
                            append = append + '<td width="15%">'
                            append = append + '<input readonly id="grandTotalPreviewFinalPage" class="form-control " type="" name="" value="'+ formatter.format(item.total_price_list) +'" style="width:100px;font-size: 12px;">'
                            append = append + '</td>'
                            append = append + '<td width="15%">'
                            append = append + '<input readonly class="form-control" type="" name="" value="'+ formatter.format(item.nominal) +'" style="width:100px;font-size: 12px;">'
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
                        appendBottom = appendBottom + '      <input readonly="" type="text" style="width:150px;display: inline;text-align:right" class="form-control inputTotalPriceFinal" id="inputTotalPriceFinal" name="inputTotalPriceFinal">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom + '</div>'


                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right" style="display:flex">'
                        appendBottom = appendBottom + '      <span> DPP Nilai Lainnya</span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;margin-left:15px;text-align:right" class="form-control" id="dpp_final" name="dpp_final">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom  + '</div>'

                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right" style="display:flex">'
                        appendBottom = appendBottom + '      <span> PPN <span id="vat_value"></span> <span class="title_service"></span></span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;margin-left:15px;text-align:right" class="form-control" id="vat_tax_final" name="vat_tax_final">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom  + '</div>'

                        appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                        appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                        appendBottom = appendBottom + '    <div class="pull-right">'
                        appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
                        appendBottom = appendBottom + '      <input readonly type="text" style="width:150px;display: inline;text-align:right" class="form-control inputFinalPageGrandPrice" id="inputFinalPageGrandPrice" name="inputFinalPageGrandPrice">'
                        appendBottom = appendBottom + '    </div>'
                        appendBottom = appendBottom + '  </div>'
                        appendBottom = appendBottom + '</div>'
                        appendBottom = appendBottom + '</div>'
                        appendBottom = appendBottom + '<hr>'
                        appendBottom = appendBottom + '<span style="display:block;text-align:center"><b>Terms & Condition</b></span>'
                        appendBottom = appendBottom + '<div class="form-control" id="termPreview" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221);overflow:auto"></div>'

                        $("#bottomPreviewFinal").append(appendBottom)

                        $("#termPreview").html(data.quote.term_payment.replaceAll("&lt;br&gt;","<br>"))

                        var tempVat = 0
                        var finalVat = 0
                        var tempGrand = 0
                        var finalGrand = 0
                        var tempTotal = 0
                        var sum = 0
                        var tempDiscount = 0
                        var dpp = 0
                        sum += valueGrandTotal;

                        if (data.config.tax_vat == null || data.config.tax_vat == 0 || data.config.tax_vat == 'null') {
                            valueVat = 'false'
                        }else{
                            valueVat = data.config.tax_vat
                        }

                        if (!isNaN(valueVat)) {
                            if (valueVat == 12){
                                dpp = (parseFloat(sum)) * 11 / 12
                                tempVat = dpp * valueVat / 100
                            }else{
                                tempVat = (parseFloat(sum) * (parseFloat(result.config.tax_vat)/100))
                            }

                            finalVat = tempVat

                            tempGrand = parseFloat(sum) +  parseFloat(tempVat)

                            tempTotal = sum

                            $('#vat_value').text(valueVat + '%')
                        }else{
                            tempGrand = sum

                            $('#vat_value').text("")
                        }

                        // if (data.config.tax_vat > 0){
                        //     tempVat = formatter.format((parseFloat(sum) * data.config.tax_vat) / 100)
                        //     tempGrand =  parseFloat(sum) + parseFloat((parseFloat(sum) * data.config.tax_vat) / 100)
                        // }else{
                        //     tempGrand = parseFloat(sum)
                        // }

                        finalVat = tempVat

                        finalGrand = tempGrand

                        tempTotal = sum

                        $('#dpp_final').val(formatCurrency(dpp))
                        $("#vat_tax_final").val(formatCurrency(tempVat))
                        $("#inputTotalPriceFinal").val(formatCurrency(sum))
                        $("#inputFinalPageGrandPrice").val(formatCurrency(tempGrand))
                        $("#inputDiscountFinal").val(tempDiscount)
                    }
                }
            })
        }



        $('#modalAdd').on('hidden.bs.modal', function () {
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
            localStorage.setItem('isStoreCustomer',false);
            localStorage.setItem('isEditProduct',false)
            localStorage.setItem('status_quote','')
            localStorage.setItem('id_quote', '')
        })

        currentTab = 0;
        function nextPrevAdd(n, value) {
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
                        url: "{{url('/sales/quote/getProductById')}}",
                        data: {
                            id:valueEdit,
                        },
                        success: function(result) {
                            $.each(result,function(value,item){
                                $("#prevBtnAdd").css("display", "none");
                                localStorage.setItem('isEditProduct',true)
                                localStorage.setItem('id_product',item.id)
                                nominal = item.nominal
                                $("#inputNameProduct").val(item.name)
                                $("#inputDescProduct").val(item.description.replaceAll("<br>","\n"))
                                $("#inputQtyProduct").val(item.qty)
                                $('#selectTypeProduct').val(item.unit)
                                select2TypeProduct(item.unit)
                                $("#inputPriceProduct").val(formatter.format(nominal))
                                $("#inputPriceList").val(formatter.format(item.price_list))
                                $("#inputTotalPriceList").val(formatter.format(item.total_price_list))
                                $("#inputTotalPrice").val(formatter.format(item.grand_total))
                                $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
                            })
                        }
                    })
                }
            }

            if(currentTab === 0){
                if($('#leadId').val() === ""){
                    $('#leadId').closest('.form-group').addClass('has-error')
                    $('#leadId').closest('.form-group').find('span').show();
                    $('#leadId').prev('.input-group-addon').css("background-color","red");
                }else if($('#customer').val() === ""){
                    $('#customer').closest('.form-group').addClass('has-error')
                    $('#customer').closest('.form-group').find('span').show();
                    $('#customer').prev('.input-group-addon').css("background-color","red");
                }else if($('#no_telp').val() === ""){
                    $('#no_telp').closest('.form-group').addClass('has-error')
                    $('#no_telp').closest('.form-group').find('span').show();
                    $('#no_telp').prev('.input-group-addon').css("background-color","red");
                }else if($('#email').val() === ""){
                    $('#email').closest('.form-group').addClass('has-error')
                    $('#email').closest('.form-group').find('span').show();
                    $('#email').prev('.input-group-addon').css("background-color","red");
                }else if($('#subject').val() === ""){
                    $('#subject').closest('.form-group').addClass('has-error')
                    $('#subject').closest('.form-group').find('span').show();
                    $('#subject').prev('.input-group-addon').css("background-color","red");
                }else if($('#street').val() === ""){
                    $('#street').closest('.form-group').addClass('has-error')
                    $('#street').closest('.form-group').find('span').show();
                    $('#street').prev('.input-group-addon').css("background-color","red");
                }else if($('#city').val() === ""){
                    $('#city').closest('.form-group').addClass('has-error')
                    $('#city').closest('.form-group').find('span').show();
                    $('#city').prev('.input-group-addon').css("background-color","red");
                }else if($('#attention').val() === ""){
                    $('#attention').closest('.form-group').addClass('has-error')
                    $('#attention').closest('.form-group').find('span').show();
                    $('#attention').prev('.input-group-addon').css("background-color","red");
                }else if($('#quote_type').val() === ""){
                    $('#quote_type').closest('.form-group').addClass('has-error')
                    $('#quote_type').closest('.form-group').find('span').show();
                    $('#quote_type').prev('.input-group-addon').css("background-color","red");
                }else if($('#date').val() === ""){
                    $('#date').closest('.form-group').addClass('has-error')
                    $('#date').closest('.form-group').find('span').show();
                    $('#date').prev('.input-group-addon').css("background-color","red");
                }else{
                    isStoreCustomer = localStorage.getItem('isStoreCustomer')
                    if (isStoreCustomer == 'false' || isStoreCustomer == null) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Save info Quotation",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                        }).then((result) => {
                            if(result.isConfirmed){
                                $.ajax({
                                    type: "POST",
                                    url: "/sales/storeQuotation",
                                    data: {
                                        _token:'{{ csrf_token() }}',
                                        lead_id: $('#leadId').val(),
                                        customer: $('#customer').val(),
                                        telp: $('#no_telp').val(),
                                        email: $('#email').val(),
                                        building: $('#building').val(),
                                        street: $('#street').val(),
                                        city: $('#city').val(),
                                        subject: $('#subject').val(),
                                        attention: $('#attention').val(),
                                        quote_type: $('#quote_type').val(),
                                        id_customer: localStorage.getItem('id_customer_quote'),
                                        date: $('#date').val()
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
                                    success: function (result) {
                                        Swal.close();
                                        localStorage.setItem('id_quote', result);
                                        localStorage.setItem('isEditProduct', false);
                                        localStorage.setItem('isStoreCustomer', true);
                                        var x = document.getElementsByClassName("tab-add");
                                        x[currentTab].style.display = "none";
                                        currentTab = currentTab + n;
                                        addQuote(currentTab);
                                    }, error: function () {
                                        Swal.close()
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went wrong, please try again',
                                            icon: 'error'
                                        })
                                    }
                                })
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
                        addQuote(currentTab);
                    }

                }
            }else if(currentTab == 1){
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
                                    url: "{{url('/sales/updateProductQuote')}}",
                                    type: 'post',
                                    data: {
                                        _token:"{{ csrf_token() }}",
                                        id:localStorage.getItem('id_product'),
                                        id_quote: localStorage.getItem('id_quote'),
                                        nameProduct: $("#inputNameProduct").val(),
                                        descProduct: $("#inputDescProduct").val().replaceAll("\n", "<br>"),
                                        qtyProduct: $("#inputQtyProduct").val(),
                                        typeProduct: $("#selectTypeProduct").val(),
                                        priceProduct: $("#inputPriceProduct").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        priceList: $("#inputPriceList").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        totalPrice: $("#inputTotalPrice").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        inputGrandTotalProduct: $("#inputGrandTotalProduct").val(),
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
                                        addQuote(currentTab);
                                        addTable(0,localStorage.getItem('status_tax'))
                                        localStorage.setItem('isEditProduct',false)
                                        localStorage.setItem('status_quote','draft')
                                        $(".tabGroupInitiateAdd").show()
                                        $(".tab-add")[1].children[1].style.display = 'none'
                                        document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                                        $("#inputNameProduct").val('')
                                        $("#inputDescProduct").val('')
                                        $("#inputPriceProduct").val('')
                                        $("#inputQtyProduct").val('')
                                        $("#inputTotalPrice").val('')
                                        $("#inputPriceList").val('')
                                        $("#selectTypeProduct").val('')
                                    }, error: function () {
                                        Swal.close()
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went wrong, please try again',
                                            icon: 'error'
                                        })
                                    }
                                })
                            }else {
                                $.ajax({
                                    url: "{{url('/sales/storeProductQuote')}}",
                                    type: 'post',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id_quote: localStorage.getItem('id_quote'),
                                        nameProduct: $("#inputNameProduct").val(),
                                        descProduct: $("#inputDescProduct").val().replaceAll("\n", "<br>"),
                                        qtyProduct: $("#inputQtyProduct").val(),
                                        typeProduct: $("#selectTypeProduct").val(),
                                        priceProduct: $("#inputPriceProduct").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        priceList: $("#inputPriceList").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        totalPrice: $("#inputTotalPrice").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        inputGrandTotalProduct: $("#inputGrandTotalProduct").val(),
                                    },
                                    beforeSend: function () {
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
                                    }, success: function () {
                                        Swal.close()
                                        let x = document.getElementsByClassName("tab-add");
                                        x[currentTab].style.display = "none";
                                        currentTab = currentTab + n;
                                        if (currentTab >= x.length) {
                                            x[n].style.display = "none";
                                            currentTab = 0;
                                        }
                                        addQuote(currentTab);
                                        localStorage.setItem('status_quote', 'draft')
                                        localStorage.setItem('store_tax', '')
                                        addTable(0, localStorage.getItem('status_tax'))
                                        $("#inputNameProduct").val('')
                                        $("#inputDescProduct").val('')
                                        $("#inputPriceProduct").val('')
                                        $("#inputPriceList").val('')
                                        $("#inputQtyProduct").val('')
                                        $("#inputTotalPrice").val('')
                                        $("#selectTypeProduct").val('')
                                        $(".tabGroupInitiateAdd").show()
                                        x[n].children[1].style.display = 'none'
                                        document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                                    }, error: function () {
                                        Swal.close()
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went wrong, please try again',
                                            icon: 'error'
                                        })
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
                        addQuote(currentTab);
                    }else{
                        var dataForm = new FormData();
                        dataForm.append('csv_file',$('#uploadCsv').prop('files')[0]);
                        dataForm.append('_token','{{ csrf_token() }}');
                        dataForm.append('id_quote',localStorage.getItem('id_quote'));

                        $.ajax({
                            processData: false,
                            contentType: false,
                            url: "{{url('/sales/quote/uploadCSV')}}",
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
                                    addQuote(currentTab);
                                    addTable(0,localStorage.getItem('status_tax'))
                                    // localStorage.setItem('status_pr','draft')
                                }
                            }, error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
                            }
                        })
                    }
                }
            } else if(currentTab == 2){
                if (n == 1){
                    isStoreTax = localStorage.getItem('store_tax');
                    if(isStoreTax == null || isStoreTax == ""){
                        $.ajax({
                            type:"POST",
                            url:"{{url('/sales/quote/storeTax')}}",
                            data:{
                                _token:"{{csrf_token()}}",
                                id_quote:localStorage.getItem('id_quote'),
                                status_tax:localStorage.getItem('status_tax'),
                                discount:localStorage.getItem('discount')== 0 ? 0 :localStorage.getItem('discount'),
                                grand_total: $('#inputGrandTotalProductFinal').val()
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
                                localStorage.setItem('store_tax', result);
                                var x = document.getElementsByClassName("tab-add");
                                x[currentTab].style.display = "none";
                                currentTab = currentTab + n;
                                if (currentTab >= x.length) {
                                    x[n].style.display = "none";
                                    currentTab = 0;
                                }
                                addQuote(currentTab);
                                localStorage.setItem('status_quote','draft')
                            }, error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
                            }
                        })
                    }else{
                        $.ajax({
                            type:"POST",
                            url:"{{url('/sales/quote/updateTax')}}",
                            data:{
                                _token:"{{csrf_token()}}",
                                id: localStorage.getItem('store_tax'),
                                id_quote:localStorage.getItem('id_quote'),
                                discount:localStorage.getItem('discount')== 0 ?0:localStorage.getItem('discount'),
                                status_tax:localStorage.getItem('status_tax'),
                                grand_total: $('#inputGrandTotalProductFinal').val()
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
                                localStorage.setItem('store_tax', result);
                                var x = document.getElementsByClassName("tab-add");
                                x[currentTab].style.display = "none";
                                currentTab = currentTab + n;
                                if (currentTab >= x.length) {
                                    x[n].style.display = "none";
                                    currentTab = 0;
                                }
                                addQuote(currentTab);
                                localStorage.setItem('status_quote','draft')
                            }, error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
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
                    addQuote(currentTab);
                }

            } else if(currentTab == 3){
                if (n == 1) {
                    if ($("#textAreaTOP").val() == "") {
                        $("#textAreaTOP").closest('textarea').closest('div').closest('form').addClass('has-error')
                        $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').show()
                    }else{
                        $("#textAreaTOP").closest('textarea').closest('div').closest('form').removeClass('has-error')
                        $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').hide()

                        $.ajax({
                            url: "{{'/sales/quote/storeTermPayment'}}",
                            type: 'post',
                            data:{
                                id_quote:localStorage.getItem('id_quote'),
                                _token:"{{csrf_token()}}",
                                term_payment:$("#textAreaTOP").val(),
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
                                addQuote(currentTab);
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
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
                    addQuote(currentTab);
                }
            }else{
                var x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                }
                addQuote(currentTab);
            }
        }

        var isStartScroll = false

        function nextPrevUnfinished(n, value) {
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
                        url: "{{url('/sales/quote/getProductById')}}",
                        data: {
                            id:valueEdit,
                        },
                        success: function(result) {

                            $.each(result,function(value,item){
                                isStartScroll = false
                                $("#prevBtnAdd").css("display", "none");
                                localStorage.setItem('isEditProduct',true)
                                localStorage.setItem('id_product',item.id)
                                nominal = item.nominal
                                $("#inputNameProduct").val(item.name)
                                $("#inputDescProduct").val(item.description.replaceAll("<br>","\n"))
                                $("#inputQtyProduct").val(item.qty)
                                $('#selectTypeProduct').val(item.unit)
                                select2TypeProduct(item.unit)
                                $("#inputPriceProduct").val(formatter.format(nominal))
                                $("#inputTotalPrice").val(formatter.format(item.grand_total))
                                $("#inputPriceList").val(formatter.format(item.price_list))
                                $("#inputTotalPriceList").val(formatter.format(item.total_price_list))
                                $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
                            })
                        }
                    })
                }
            }

            if(currentTab === 0){
                isStartScroll = true
                if($('#leadId').val() === ""){
                    $('#leadId').closest('.form-group').addClass('has-error')
                    $('#leadId').closest('.form-group').find('span').show();
                    $('#leadId').prev('.input-group-addon').css("background-color","red");
                }else if($('#customer').val() === ""){
                    $('#customer').closest('.form-group').addClass('has-error')
                    $('#customer').closest('.form-group').find('span').show();
                    $('#customer').prev('.input-group-addon').css("background-color","red");
                }else if($('#no_telp').val() === ""){
                    $('#no_telp').closest('.form-group').addClass('has-error')
                    $('#no_telp').closest('.form-group').find('span').show();
                    $('#no_telp').prev('.input-group-addon').css("background-color","red");
                }else if($('#email').val() === ""){
                    $('#email').closest('.form-group').addClass('has-error')
                    $('#email').closest('.form-group').find('span').show();
                    $('#email').prev('.input-group-addon').css("background-color","red");
                }else if($('#subject').val() === ""){
                    $('#subject').closest('.form-group').addClass('has-error')
                    $('#subject').closest('.form-group').find('span').show();
                    $('#subject').prev('.input-group-addon').css("background-color","red");
                }else if($('#street').val() === "") {
                    $('#street').closest('.form-group').addClass('has-error')
                    $('#street').closest('.form-group').find('span').show();
                    $('#street').prev('.input-group-addon').css("background-color", "red");
                }else if($('#city').val() === ""){
                    $('#city').closest('.form-group').addClass('has-error')
                    $('#city').closest('.form-group').find('span').show();
                    $('#city').prev('.input-group-addon').css("background-color","red");
                }else if($('#attention').val() === ""){
                    $('#attention').closest('.form-group').addClass('has-error')
                    $('#attention').closest('.form-group').find('span').show();
                    $('#attention').prev('.input-group-addon').css("background-color","red");
                }else if($('#quote_type').val() === ""){
                    $('#quote_type').closest('.form-group').addClass('has-error')
                    $('#quote_type').closest('.form-group').find('span').show();
                    $('#quote_type').prev('.input-group-addon').css("background-color","red");
                }else if($('#date').val() === ""){
                    $('#date').closest('.form-group').addClass('has-error')
                    $('#date').closest('.form-group').find('span').show();
                    $('#date').prev('.input-group-addon').css("background-color","red");
                }else{
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Save info Quotation",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if(result.isConfirmed){
                            $.ajax({
                                type: "POST",
                                url: "/sales/updateQuotation",
                                data: {
                                    _token:'{{ csrf_token() }}',
                                    lead_id: $('#leadId').val(),
                                    customer: $('#customer').val(),
                                    telp: $('#no_telp').val(),
                                    email: $('#email').val(),
                                    building: $('#building').val(),
                                    street: $('#street').val(),
                                    city: $('#city').val(),
                                    subject: $('#subject').val(),
                                    attention: $('#attention').val(),
                                    quote_type: $('#quote_type').val(),
                                    id_customer: localStorage.getItem('id_customer_quote'),
                                    date: $('#date').val(),
                                    id_quote: localStorage.getItem('id_quote')
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
                                success: function (result) {
                                    Swal.close();
                                    localStorage.setItem('id_quote', result);
                                    localStorage.setItem('isEditProduct', false);
                                    var x = document.getElementsByClassName("tab-add");
                                    x[currentTab].style.display = "none";
                                    currentTab = currentTab + n;
                                    unfinishedDraft(currentTab, result);
                                }, error: function () {
                                    Swal.close()
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong, please try again',
                                        icon: 'error'
                                    })
                                }
                            })
                        }
                    })

                }
            }else if(currentTab == 1){
                isStartScroll = true
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
                                    url: "{{url('/sales/updateProductQuote')}}",
                                    type: 'post',
                                    data: {
                                        _token:"{{ csrf_token() }}",
                                        id:localStorage.getItem('id_product'),
                                        id_quote: localStorage.getItem('id_quote'),
                                        nameProduct: $("#inputNameProduct").val(),
                                        descProduct: $("#inputDescProduct").val().replaceAll("\n", "<br>"),
                                        qtyProduct: $("#inputQtyProduct").val(),
                                        typeProduct: $("#selectTypeProduct").val(),
                                        priceProduct: $("#inputPriceProduct").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        priceList: $("#inputPriceList").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        totalPrice: $("#inputTotalPrice").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        inputGrandTotalProduct: $("#inputGrandTotalProduct").val(),
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
                                        unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                                        addTable(0,localStorage.getItem('status_tax'))
                                        localStorage.setItem('isEditProduct',false)
                                        localStorage.setItem('status_quote','draft')
                                        $(".tabGroupInitiateAdd").show()
                                        $(".tab-add")[1].children[1].style.display = 'none'
                                        document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                                        $("#inputNameProduct").val('')
                                        $("#inputDescProduct").val('')
                                        $("#inputPriceProduct").val('')
                                        $("#inputPriceList").val('')
                                        $("#inputQtyProduct").val('')
                                        $("#inputTotalPrice").val('')
                                        $("#selectTypeProduct").val('')
                                    },
                                    error: function () {
                                        Swal.close()
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went wrong, please try again',
                                            icon: 'error'
                                        })
                                    }
                                })
                            }else {
                                $.ajax({
                                    url: "{{url('/sales/storeProductQuote')}}",
                                    type: 'post',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id_quote: localStorage.getItem('id_quote'),
                                        nameProduct: $("#inputNameProduct").val(),
                                        descProduct: $("#inputDescProduct").val().replaceAll("\n", "<br>"),
                                        qtyProduct: $("#inputQtyProduct").val(),
                                        typeProduct: $("#selectTypeProduct").val(),
                                        priceProduct: $("#inputPriceProduct").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        priceList: $("#inputPriceList").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        totalPrice: $("#inputTotalPrice").val().replace(/\./g, '').replace(',', '.').replace(' ', ''),
                                        inputGrandTotalProduct: $("#inputGrandTotalProduct").val(),
                                    },
                                    beforeSend: function () {
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
                                    }, success: function () {
                                        Swal.close()
                                        let x = document.getElementsByClassName("tab-add");
                                        x[currentTab].style.display = "none";
                                        currentTab = currentTab + n;
                                        if (currentTab >= x.length) {
                                            x[n].style.display = "none";
                                            currentTab = 0;
                                        }
                                        unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                                        localStorage.setItem('status_quote', 'draft')
                                        addTable(0, localStorage.getItem('status_tax'))
                                        $("#inputNameProduct").val('')
                                        $("#inputDescProduct").val('')
                                        $("#inputPriceProduct").val('')
                                        $("#inputPriceList").val('')
                                        $("#inputQtyProduct").val('')
                                        $("#inputTotalPrice").val('')
                                        $("#selectTypeProduct").val('')
                                        $(".tabGroupInitiateAdd").show()
                                        x[n].children[1].style.display = 'none'
                                        document.getElementsByClassName('tabGroupInitiateAdd')[0].childNodes[1].style.display = 'flex'
                                    }, error: function () {
                                        Swal.close()
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went wrong, please try again',
                                            icon: 'error'
                                        })
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
                        unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                    }else{
                        var dataForm = new FormData();
                        dataForm.append('csv_file',$('#uploadCsv').prop('files')[0]);
                        dataForm.append('_token','{{ csrf_token() }}');
                        dataForm.append('id_quote',localStorage.getItem('id_quote'));

                        $.ajax({
                            processData: false,
                            contentType: false,
                            url: "{{url('/sales/quote/uploadCSV')}}",
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
                                    unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                                    addTable(0,localStorage.getItem('status_tax'))
                                    // localStorage.setItem('status_pr','draft')
                                }
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
                            }
                        })
                    }
                }
            } else if(currentTab == 2){
                if (n == 1){
                    isStoreTax = localStorage.getItem('store_tax');
                    if(isStoreTax == null || isStoreTax == ""){
                        $.ajax({
                            type:"POST",
                            url:"{{url('/sales/quote/storeTax')}}",
                            data:{
                                _token:"{{csrf_token()}}",
                                id_quote:localStorage.getItem('id_quote'),
                                status_tax:localStorage.getItem('status_tax'),
                                discount:localStorage.getItem('discount')==0?0:localStorage.getItem('discount'),
                                grand_total: $('#inputGrandTotalProductFinal').val()
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
                                localStorage.setItem('store_tax', result);
                                var x = document.getElementsByClassName("tab-add");
                                x[currentTab].style.display = "none";
                                currentTab = currentTab + n;
                                if (currentTab >= x.length) {
                                    x[n].style.display = "none";
                                    currentTab = 0;
                                }
                                unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                                localStorage.setItem('status_quote','draft')
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
                            }
                        })
                    }else{
                        $.ajax({
                            type:"POST",
                            url:"{{url('/sales/quote/updateTax')}}",
                            data:{
                                _token:"{{csrf_token()}}",
                                id: localStorage.getItem('store_tax'),
                                id_quote:localStorage.getItem('id_quote'),
                                discount:localStorage.getItem('discount')==0?0:localStorage.getItem('discount'),
                                status_tax:localStorage.getItem('status_tax'),
                                grand_total: $('#inputGrandTotalProductFinal').val()
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
                                localStorage.setItem('store_tax', result);
                                var x = document.getElementsByClassName("tab-add");
                                x[currentTab].style.display = "none";
                                currentTab = currentTab + n;
                                if (currentTab >= x.length) {
                                    x[n].style.display = "none";
                                    currentTab = 0;
                                }
                                unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                                localStorage.setItem('status_quote','draft')
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
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
                    unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                }

            } else if(currentTab == 3){
                if (n == 1) {
                    if ($("#textAreaTOP").val() == "") {
                        $("#textAreaTOP").closest('textarea').closest('div').closest('form').addClass('has-error')
                        $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').show()
                    }else{
                        $("#textAreaTOP").closest('textarea').closest('div').closest('form').removeClass('has-error')
                        $("#textAreaTOP").closest('textarea').next('input').next('iframe').next('span').hide()

                        $.ajax({
                            url: "{{'/sales/quote/storeTermPayment'}}",
                            type: 'post',
                            data:{
                                id_quote:localStorage.getItem('id_quote'),
                                _token:"{{csrf_token()}}",
                                term_payment:$("#textAreaTOP").val(),
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
                                unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
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
                    unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
                }
            }else{
                var x = document.getElementsByClassName("tab-add");
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                if (currentTab >= x.length) {
                    x[n].style.display = "none";
                    currentTab = 0;
                }
                unfinishedDraft(currentTab, localStorage.getItem('id_quote'));
            }
        }

        function createQuote(status){
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
                    text: "Submit Quotation",
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
                            url:"{{url('/sales/quote/storeLastStepQuote')}}",
                            data:{
                                _token:"{{csrf_token()}}",
                                id_quote:localStorage.getItem('id_quote'),
                                inputGrandTotalProduct:$("#inputFinalPageGrandPrice").val(),
                                status_revision:status,
                                isRupiah:localStorage.getItem("isRupiah"),
                            },
                            success: function(result){
                                localStorage.setItem('id_quote', '')
                                localStorage.setItem('isStoreCustomer',false);
                                Swal.fire({
                                    title: 'Add Quotation Successs',
                                    html: "<p style='text-align:center;'>Your Quotation will be verified by manager soon, please wait for further progress</p>",
                                    type: 'success',
                                    confirmButtonText: 'Reload',
                                }).then((result) => {
                                    localStorage.setItem('status_quote','')
                                    location.replace("{{url('sales/quote')}}")
                                })
                            },
                            error: function () {
                                Swal.close()
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong, please try again',
                                    icon: 'error'
                                })
                            }
                        })
                    }
                })
            }
        }

        function addTable(n,status,results=""){
            $.ajax({
                type: "GET",
                url: '{{url('/sales/quote/getProductQuote')}}',
                data: {
                    id_quote:localStorage.getItem('id_quote'),
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
                        append = append + "<input id='inputNameProductEdit' data-value='' readonly style='font-size: 12px; important' class='form-control' type='' name='' value='"+ item.name + "'>"
                        append = append + '</td>'
                        append = append + '<td width="30%">'
                        append = append + '<textarea id="textAreaDescProductEdit" readonly data-value="" style="font-size: 12px; important;resize:none;height:150px;" class="form-control">'+ item.description.replaceAll("<br>","\n") + '&#10;'
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
                        append = append + '<input id="inputPriceList" readonly data-value="" style="font-size: 12px;width:100px" class="form-control" type="" name="" value="'+ formatter.format(item.price_list) +'">'
                        append = append + '</td>'
                        append = append + '<td width="15%">'
                        append = append + '<input id="inputTotalPriceList" readonly data-value="" style="font-size: 12px;width:100px" class="form-control   " type="" name="" value="'+ formatter.format(item.total_price_list) +'">'
                        append = append + '</td>'
                        append = append + '<td width="15%">'
                        append = append + '<input id="inputPriceEdit" readonly data-value="" style="font-size: 12px;width:100px" class="form-control" type="" name="" value="'+ formatter.format(item.nominal) +'">'
                        append = append + '</td>'
                        append = append + '<td width="15%">'
                        append = append + '<input id="inputTotalPriceEdit" readonly data-value="" style="font-size: 12px;width:100px" class="form-control inputTotalPriceEdit" type="" name="" value="'+ formatter.format(item.grand_total) +'">'
                        append = append + '</td>'
                        append = append + '<td width="8%">'
                        if (localStorage.getItem('status_quote') == 'draft') {
                            btnNext = 'nextPrevAdd(-1,'+ item.id +')'
                        }else{
                            btnNext = 'nextPrevUnfinished(-1,'+ item.id +')'
                        }
                        append = append + '<button type="button" onclick="'+ btnNext +'" id="btnEditProduk" data-id="'+ value +'" data-value="'+ valueEdit +'" class="btn btn-xs btn-warning fa fa-edit btnEditProduk" style="width:25px;height:25px;margin-bottom:5px"></button>'
                        append = append + '<button id="btnDeleteProduk" type="button" data-id="'+ item.id+'" data-value="'+ value +'" class="btn btn-xs btn-danger fa fa-trash" style="width:25px;height:25px"></button>'
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
                    appendBottom = appendBottom + '      <input readonly="" type="text" style="width:250px;display: inline;" class="form-control inputTotalProduct" id="inputGrandTotalProduct" name="inputGrandTotalProduct">'
                    appendBottom = appendBottom + '    </div>'
                    appendBottom = appendBottom + '  </div>'
                    appendBottom = appendBottom + '</div>'

                    // appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                    // appendBottom = appendBottom + '<div class="col-md-12 col-xs-12">'
                    // appendBottom = appendBottom + ' <div class="pull-right">'
                    // appendBottom = appendBottom + '  <span style="margin-right: 15px;">Vat <span class="title_tax"></span>'
                    // appendBottom = appendBottom + '  </span>'
                    // appendBottom = appendBottom + '  <div class="input-group" style="display: inline-flex;">'
                    // appendBottom = appendBottom + '   <input readonly="" type="text" class="form-control vat_tax" id="vat_tax" name="vat_tax" style="width:217px;display:inline">'
                    // appendBottom = appendBottom + '  <div class="input-group-btn">'
                    // appendBottom = appendBottom + '       <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                    // appendBottom = appendBottom + '         <span class="fa fa-caret-down"></span>'
                    // appendBottom = appendBottom + '       </button>'
                    // appendBottom = appendBottom + '       <ul class="dropdown-menu">'
                    // appendBottom = appendBottom + '       <li>'
                    // appendBottom = appendBottom + '        <a onclick="changeVatValue(false)">Without Vat</a>'
                    // appendBottom = appendBottom + '       </li>'
                    // appendBottom = appendBottom + '       <li>'
                    // appendBottom = appendBottom + '        <a onclick="changeVatValue(11)">Vat 11%</a>'
                    // appendBottom = appendBottom + '       </li>'
                    // appendBottom = appendBottom + '       <li>'
                    // appendBottom = appendBottom + '        <a onclick="changeVatValue(12)">Vat 12%</a>'
                    // appendBottom = appendBottom + '       </li>'
                    // appendBottom = appendBottom + '      </ul>'
                    // appendBottom = appendBottom + '     </div>'
                    // appendBottom = appendBottom + '    </div>'
                    // appendBottom = appendBottom + '  </div>'
                    // appendBottom = appendBottom + '</div>'
                    // appendBottom = appendBottom + '</div>'

                    appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                    appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                    appendBottom = appendBottom + '    <div class="pull-right">'
                    appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">DPP Nilai Lainnya</span>'
                    appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control dpp" id="dpp" name="dpp">'
                    appendBottom = appendBottom + '    </div>'
                    appendBottom = appendBottom + '  </div>'
                    appendBottom = appendBottom  + '</div>'

                    appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                    appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                    appendBottom = appendBottom + '    <div class="pull-right">'
                    appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">PPN <span class="title_tax"></span></span>'
                    appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control vat_tax" id="vat_tax" name="vat_tax">'
                    appendBottom = appendBottom + '    </div>'
                    appendBottom = appendBottom + '  </div>'
                    appendBottom = appendBottom  + '</div>'

                    appendBottom = appendBottom + '<div class="row" style="margin-top: 10px;">'
                    appendBottom = appendBottom + '  <div class="col-md-12 col-xs-12">'
                    appendBottom = appendBottom + '    <div class="pull-right">'
                    appendBottom = appendBottom + '      <span style="display: inline;margin-right: 10px;">Grand Total</span>'
                    appendBottom = appendBottom + '      <input readonly type="text" style="width:250px;display: inline;" class="form-control inputGrandTotalProductFinal" id="inputGrandTotalProductFinal" name="inputGrandTotalProductFinal">'
                    appendBottom = appendBottom + '    </div>'
                    appendBottom = appendBottom + '  </div>'
                    appendBottom = appendBottom  + '</div>'

                    $("#bottomProducts").append(appendBottom)

                    if (status != "") {
                        changeVatValue(status)
                    }
                    if(localStorage.getItem('store_tax') == "" || localStorage.getItem('store_tax') == null){
                        toggleIcheckPajak(false)
                    }else{
                        $.ajax({
                            type: 'GET',
                            url: '{{url('/sales/quote/getTax')}}',
                            data:{
                                id_tax: localStorage.getItem('store_tax')
                            },
                            success: function (results) {
                                setTimeout(function(argument) {
                                    // if (results.data.nominal != null){
                                    //     changeValueGrandTotal(results.data.nominal)
                                    // }
                                },500)
                                if (results.data.discount == 0 || results.data.discount == null) {
                                    toggleIcheckPajak(false)
                                }else{
                                    $("#inputDiscountNominal").val(formatter.format(($("#inputGrandTotalProduct").val() == ""?0:parseFloat($("#inputGrandTotalProduct").val().replace(/\./g,'').replace(',','.').replace(' ','')) * results.data.discount / 100)))
                                    setTimeout(function(){
                                        $("#inputDiscountProduct").val(parseFloat(results.data.discount).toFixed(2))
                                    },500)
                                    toggleIcheckPajak(true)
                                    $("#cbInputDiscountProduct").iCheck('check');
                                }
                                localStorage.setItem('discount',parseFloat(results.data.discount))
                            }
                        })
                    }

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
                                    url: "{{url('/sales/quote/deleteProduct')}}",
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

                    $("#inputDiscountProduct").inputmask({
                        alias:"percentage",
                        integerDigits:2,
                        digits:2,
                        allowMinus:false,
                        digitsOptional: false,
                        placeholder: "0"
                    });
                }
            })
        }

        function refreshTable(){
            addTable(0,localStorage.getItem('status_tax'))
        }

        function toggleIcheckPajak(value){

            $('#cbInputDiscountProduct').on('ifChecked', function(event){
                $("#inputDiscountNominal").prop("disabled",false)
            });

            $('#cbInputDiscountProduct').on('ifUnchecked', function(event){
                $("#inputDiscountNominal").prop("disabled",true)
                if (value == false) {
                    $("#inputDiscountNominal").val("")
                    changeVatValue("discount")
                }
            });
        }

        function changeVatValue(value=false){
            var tempVat = 0
            var finalVat = 0
            var tempGrand = 0
            var tempDiscount = 0
            var finalGrand = 0
            var tempTotal = 0
            var sum = 0
            var dpp = 0

            $('.inputTotalPriceEdit').each(function() {
                var temp = parseFloat($(this).val() == "" ? "0" : parseFloat($(this).val().replace(/\./g,'').replace(',','.').replace(' ','')))
                sum += temp;
            });

            $("#inputGrandTotalProduct").val(formatter.format(sum))

            if (value == false) {
                valueVat = ''
                // if ($("#inputDiscountNominal").val() != "") {
                //     tempDiscount = $("#inputDiscountNominal").val() == 0?false:parseFloat($("#inputDiscountNominal").val().replace(/\./g,'').replace(',','.').replace(' ','') / parseFloat(sum) * 100)
                // }
            }else{
                if (value == 'discount') {
                    // if ($("#inputDiscountNominal").val() == "") {
                    //     tempDiscount = tempDiscount
                    // } else {
                    //     tempDiscount = $("#inputDiscountNominal").val() == 0?false:parseFloat($("#inputDiscountNominal").val().replace(/\./g,'').replace(',','.').replace(' ','') / parseFloat(sum) * 100)
                    // }
                }else{
                    valueVat = value
                    // if ($("#inputDiscountNominal").val() != "") {
                    //     tempDiscount = $("#inputDiscountNominal").val() == 0?false:parseFloat($("#inputDiscountNominal").val().replace(/\./g,'').replace(',','.').replace(' ','') / parseFloat(sum) * 100)
                    // }
                }
            }
            $('.money').mask('#.##0,00', {reverse: true})

            if (!isNaN(valueVat)) {
                setTimeout(function(){
                    if (valueVat == 12){
                        dpp = (parseFloat(sum)) * (11 / 12)
                        tempVat = dpp * valueVat / 100
                    }else{
                        tempVat = (parseFloat(sum)) * (valueVat == false?0:parseFloat(valueVat) / 100)
                    }

                    finalVat = tempVat

                    finalGrand = tempGrand

                    tempTotal = parseFloat(sum)

                    $('.title_tax').text(valueVat == '' || valueVat == null ?"":valueVat + '%')

                    $("#vat_tax").val(formatter.format(isNaN(tempVat)?0:tempVat.toFixed(2)))
                    $("#dpp").val(formatter.format(isNaN(dpp)?0:dpp.toFixed(2)))
                },500)
            }else{
                tempVat = 0
                $("#vat_tax").val(formatter.format(tempVat.toFixed(2)))

                finalVat = tempVat

                finalGrand = tempGrand

                tempTotal = parseFloat(sum)

                $('.title_tax').text($("#vat_tax").val() == "" ||$("#vat_tax").val() == 0?"":$('.title_tax').text().replace("%","") + '%')
            }

            setTimeout(function(){
                // tempDiscNominal = isNaN(parseFloat($("#inputDiscountNominal").val().replace(/\./g,'').replace(',','.').replace(' ','')))?0:parseFloat($("#inputDiscountNominal").val().replace(/\./g,'').replace(',','.').replace(' ',''))

                $("#inputDiscountProduct").val(tempDiscount)

                tempGrand = tempTotal + tempVat //- tempDiscNominal

                changeValueGrandTotal(isNaN(tempGrand)?0:tempGrand.toFixed(2))
            },500)

            localStorage.setItem('status_tax',valueVat)
            localStorage.setItem('discount',tempDiscount == ''?0:tempDiscount)
        }

        function changeValueGrandTotal(grandTotal){
            console.log(grandTotal)
            $("#inputGrandTotalProductFinal").val(formatter.format(grandTotal))
        }


        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $('#date_backdate').datepicker({
            autoclose: true,
        }).attr('readonly','readonly').css('background-color','#fff');
        initQuoTable();

        function initQuoTable(temp) {
            localStorage.setItem('status_tax', 12)
            var temp = ''
            if (temp == undefined) {
                temp = '?' + temp
            }else{
                temp = ''
            }
            // InitiateFilterParam();
            DashboardCounter(temp)
            let roleName = "";
            $("#data_all").DataTable({
                "ajax":{
                    "type":"GET",
                    "url":"{{url('/quote/getDataQuoteFilter')}}" + temp,
                    "dataSrc": function (json){
                        roleName = json.role;
                        json.data.forEach(function(data,index){
                            if("{{Auth::User()->nik}}" == data.nik) {
                                var x = '"' + data.quote_number + '","' + data.id_customer + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.project_id+ '","' +data.note+ '","' +data.date+ '","' +data.position+ '"'
                                data.btn_edit = "<button class='btn btn-sm btn-primary' onclick='edit_quote(" + x + ")'>&nbsp Edit</button>";
                            } else {
                                data.btn_edit = "<button class='btn btn-sm btn-primary disabled'>&nbsp Edit</button>";
                            }
                        });
                        return json.data;

                    }
                },
                "columns": [
                    { "data": "quote_number","width": "20%" },
                    { "data": "date","width": "80px" },
                    {
                        "render": function ( data, type, row, meta ) {
                            return '<div class="truncate">' + row.customer_legal_name + '</div>'

                            // if(row.id_customer == null){
                            //   return '<div class="truncate"> - </div>'
                            // } else {
                            // }
                        },
                        width: "150px"
                    },
                    {
                        "render": function ( data, type, row, meta ) {
                            if(row.attention == null){
                                return '<div class="truncate"> - </div>'
                            } else {
                                return '<div class="truncate">' + row.attention + '</div>'
                            }
                        },
                        width: "70px"
                    },
                    {
                        "render": function ( data, type, row, meta ) {
                            if (row.title == null) {
                                return '<div class="truncate"> - </div>'
                            } else {
                                return '<div class="truncate">' + row.title + '</div>'
                            }
                        },
                        width: "250px"
                    },
                    { "data": "name","width": "20%" },
                    {   data: "status",
                        render: function (data, type, row) {
                            if (data == 'SAVED'){
                                return `<p class="btn btn-xs btn-primary" > ON GOING </p>`;
                            }else if(data == 'ON GOING'){
                                return `<p class="btn btn-xs btn-primary"> ${data} </p>`;
                            }else if(data == 'APPROVED'){
                                return `<p class="btn btn-xs btn-success"> ${data} </p>`;
                            }else if(data == 'REJECTED'){
                                return `<p class="btn btn-xs btn-danger"> ${data} </p>`;
                            }
                        },
                        className: "text-center",
                        width: "100px"
                       },
                    { "data": "project_type","width": "20%" },
                    {
                            data: "nominal",
                            render: function (data, type, row) {
                                if (type === 'display') {
                                    if (data == null || data === "") {
                                        return "-";
                                    }
                                    let numericValue = parseFloat(data.toString().replace(/\./g, '').replace(',', '.'));

                                    if (isNaN(numericValue)) {
                                        return "-";
                                    }

                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }).format(numericValue);
                                }
                                return data;
                            },
                        width: "20%"
                    },
                    {
                        "render": function (data, type, row) {
                            if (row.status == 'SAVED'){
                                var status = 'saved';
                                if(row.nik == "{{Auth::user()->nik}}"){
                                    return `<button style='width:70px' class="btn btn-xs btn-warning btnDraft" data-target="#modalAdd" data-toggle="modal" onclick="unfinishedDraft(0, ${row.id_quote}, '${status}')">Draft</button>`;
                                }else{
                                    return `<button style='width:70px' class="btn btn-xs btn-warning btnDraft" disabled >Draft</button>`;
                                }
                            }else{
                                if (roleName === 'President Director' && row.role_name === 'Sales Manager' && row.status === 'ON GOING'){
                                    return `<a href="/sales/quote/detail/${row.id_quote}" target="_blank" style="width:70px; white-space:normal; word-wrap:break-word; text-align:center; min-height:40px;" class="btn btn-xs btn-warning btnDetail">Need Attention</a>`;
                                }else if(roleName === 'Operations Director' && row.role_name === 'VP Product Management & Development Solution' && row.status === 'ON GOING'){
                                    return `<a href="/sales/quote/detail/${row.id_quote}" target="_blank" style="width:70px; white-space:normal; word-wrap:break-word; text-align:center; min-height:40px;" class="btn btn-xs btn-warning btnDetail">Need Attention</a>`;
                                }else if(roleName === 'VP Product Management & Development Solution' && row.role_name === 'Technology Alliance' && row.status === 'ON GOING'){
                                    return `<a href="/sales/quote/detail/${row.id_quote}" target="_blank" style="width:70px; white-space:normal; word-wrap:break-word; text-align:center; min-height:40px;" class="btn btn-xs btn-warning btnDetail">Need Attention</a>`;
                                }else if(roleName === 'Sales Manager' && row.role_name === 'Sales Staff' && row.status === 'ON GOING'){
                                    return `<a href="/sales/quote/detail/${row.id_quote}" target="_blank" style="width:70px; white-space:normal; word-wrap:break-word; text-align:center; min-height:40px;" class="btn btn-xs btn-warning btnDetail">Need Attention</a>`;
                                }else{
                                    return `<a href="/sales/quote/detail/${row.id_quote}" target="_blank" style='width:70px' class="btn btn-xs btn-primary btnDetail">Detail</a>`;
                                }
                            }
                        },
                    },
                ],
                "searching": true,
                // "scrollX": true,
                // "order": [[0, "desc"]],
                "ordering": false,
                "fixedColumns":   {
                    leftColumns: 1
                },
                "pageLength": 20,
            })
        }

        function changetabPane(status_backdate) {
            $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=" + status_backdate + "&year=" + $('#year_filter').val()).load();
        }

        $("#year_filter").change(function(){
            $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=A&year=" + this.value).load();
            DashboardCounterFilter(this.value);
        });

        $("#alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#alert").slideUp(300);
        });

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $($.fn.dataTable.tables( true ) ).css('width', '100%');
            $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
        });

        $(".dismisbar").click(function(){
            $(".notification-bar").slideUp(300);
        });

        $('#myTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        });

        // on load of the page: switch to the currently selected tab
        var hash = window.location.hash;
        $('#myTab a[href="' + hash + '"]').tab('show');

        function DashboardCounter(temp){
            var temp = ''
            if (temp == undefined) {
                temp = '?' + temp
            }else{
                temp = ''
            }
            $("#BoxId").empty()

            var countQuot = []

            var i = 0
            var append = ""
            var colors = []
            @if($role->name == 'President Director' || $role->name == 'Operations Director' )
                var ArrColors = [
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
            @else
                var ArrColors = [
                    {
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

            @endif
            colors.push(ArrColors)
            @if($role->name == 'President Director' || $role->name == 'Operations Director' )
                $.each(colors[0], function(key, value){
                    var status = "'"+ value.status +"'"
                    append = append + '<div class="col-lg-4 col-xs-12">'
                    append = append + '<div class="small-box ' + value.color + '">'
                    append = append + '<div class="inner">'
                    append = append + '<h3 style="'+ value.style +'" class="counter" id="count_quot_'+value.index+'"</h3>'
                    append = append + '<h4 style="'+ value.style +'"><b>'+ value.name +'</b></h4>'

                    append = append + '</div>'
                    append = append + '<div class="icon">'
                    append = append + '<i class="'+ value.icon +'" style="'+ value.style +';opacity:0.4"></i>'
                    append = append + '</div>'
                    append = append + '</div>'
                    append = append + '</div>'
                    id = "count_quot_"+value.index
                    countQuot.push(id)
                })

            $("#BoxId").append(append)

            $.ajax({
                type:"GET",
                url:"{{url('/quote/getCount')}}" + temp,
                success:function(result){
                    $("#"+countQuot[0]).text(result.count_ongoing)
                    $("#"+countQuot[1]).text(result.count_done)
                    $("#"+countQuot[2]).text(result.count_all)
                    // $("#"+countQuot[3]).text(result.count_all)

                    $('.counter').each(function () {
                        var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 1000,
                            step: function (func) {
                                $(this).text(parseFloat(func).toFixed(size));
                            }
                        });
                    });
                },
            })
            @else
                $.each(colors[0], function(key, value){
                    var status = "'"+ value.status +"'"
                    append = append + '<div class="col-lg-3 col-xs-12">'
                    append = append + '<div class="small-box ' + value.color + '">'
                    append = append + '<div class="inner">'
                    append = append + '<h3 style="'+ value.style +'" class="counter" id="count_quot_'+value.index+'"</h3>'
                    append = append + '<h4 style="'+ value.style +'"><b>'+ value.name +'</b></h4>'

                    append = append + '</div>'
                    append = append + '<div class="icon">'
                    append = append + '<i class="'+ value.icon +'" style="'+ value.style +';opacity:0.4"></i>'
                    append = append + '</div>'
                    append = append + '</div>'
                    append = append + '</div>'
                    id = "count_quot_"+value.index
                    countQuot.push(id)
                })

            $("#BoxId").append(append)

            $.ajax({
                type:"GET",
                url:"{{url('/quote/getCount')}}" + temp,
                success:function(result){
                    $("#"+countQuot[0]).text(result.count_need_attention)
                    $("#"+countQuot[1]).text(result.count_ongoing)
                    $("#"+countQuot[2]).text(result.count_done)
                    $("#"+countQuot[3]).text(result.count_all)

                    $('.counter').each(function () {
                        var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 1000,
                            step: function (func) {
                                $(this).text(parseFloat(func).toFixed(size));
                            }
                        });
                    });
                },
            })
            @endif

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

                emails = validateEmail($("#email").val())

                if ($("#email").val() == '-') {
                    $("#email").closest('.form-group').removeClass('has-error')
                    $("#email").closest('input').next('span').hide()
                    $("#email").prev('.input-group-addon').css("background-color","red")
                }else{
                    switch(emails){
                        case null:
                            $("#email").closest('.form-group').addClass('has-error')
                            $("#email").closest('input').next('span').show();
                            $("#email").prev('.input-group-addon').css("background-color","red");
                            $("#email").closest('input').next('span').text("Enter a Valid Email Address!")
                            break;
                        default:
                            $("#email").closest('.form-group').removeClass('has-error')
                            $("#email").closest('input').next('span').hide()
                            $("#email").prev('.input-group-addon').css("background-color","red")
                    }
                }
            }else if (val == "phone") {
                $("#no_telp").inputmask({"mask": "999-999-999-999"})
                $("#no_telp").closest('.form-group').removeClass('has-error')
                $("#no_telp").closest('input').next('span').hide();
                $("#no_telp").prev('.input-group-addon').css("background-color","red");
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
                    $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
                    $("#inputTotalPriceList").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceList").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
                }else{
                    $("#inputTotalPriceList").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceList").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
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
                if (localStorage.getItem('isRupiah') == 'true') {
                    $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
                }else{
                    $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
                }
                $("#inputPriceProduct").closest('.col-md-4').removeClass('has-error')
                $("#inputPriceProduct").closest('input').closest('.input-group').next('span').hide();
                $("#inputPriceProduct").prev('.col-md-4').css("background-color","red");
            }
            if (val == "price_list"){
                $("#inputTotalPriceList").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceList").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
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
        var formatter = new Intl.NumberFormat(['ban', 'id']);
        localStorage.setItem("isRupiah",true)
        function changeCurreny(value){
            if (value == "usd") {
                $("#inputPriceProduct").closest("div").find(".input-group-addon").text("$")
                $("#inputTotalPrice").closest("div").find("div").text("$")
                localStorage.setItem("isRupiah",false)
                $('.money').mask('#0,00,00', {reverse: true})

                // $(".money").mask('000.000.000.000.000', {reverse: true})
            }else{
                $("#inputPriceProduct").closest("div").find(".input-group-addon").text("Rp.")
                $("#inputTotalPrice").closest("div").find("div").text("Rp.")

                localStorage.setItem("isRupiah",true)

                $('.money').mask('#.##0,00', {reverse: true})
            }

            if (localStorage.getItem('isRupiah') == 'true') {
                $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
            }else{
                $("#inputTotalPrice").val(formatter.format(Number($("#inputQtyProduct").val()) * parseFloat($("#inputPriceProduct").val().replace(/\./g,'').replace(',','.').replace(' ',''))))
            }
        }

        function scrollTopModal(){
            var savedScrollPosition = localStorage.getItem('scrollPosition');
            var scrollableElement = document.getElementById('modalAdd');
            scrollableElement.scrollTop = savedScrollPosition;
        }

        $("#modalAdd").on('scroll', function() {
            if (isStartScroll == true) {
                var scrollPosition = $("#modalAdd").scrollTop();
                localStorage.setItem('scrollPosition', scrollPosition);
            }
            // Update the scroll position variable with the latest scroll position
            // If a saved scroll position exists, set the scroll position to the saved value
        })
    </script>
@endsection
