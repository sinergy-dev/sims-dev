@extends('template.template')
@section('content')
<style type="text/css">
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

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Purchase Request Asset Managememt</a>
        </li>
      </ol>
      @if(session('success'))
      <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Table PR Asset Management</b>
           @if(Auth::User()->id_position == 'ADMIN')
           <div class="pull-right">
              <button class="btn btn-success-sales pull-right float-right margin-left-custom" id="" data-target="#modal_pr_asset" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp PR Asset</button>
            <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b><i class="fa fa-download"></i> Export</b>
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                <a class="dropdown-item" href="{{action('PAMController@downloadPDF')}}"> PDF </a>
                <a class="dropdown-item" href="{{action('PAMController@exportExcel')}}"> EXCEL </a>
            </div>  
          </div>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="datasmu" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Created Date</th>
                  <th>No. Purchase Request</th>
                  <th>To</th><!-- 
                  <th>Nominal</th> -->
                  <th>From</th>
                  <th>Subject</th>
                  @if(Auth::User()->id_position == 'ADMIN')
                  <th>Action</th>
                  <th>Action</th>
                  @endif
                  
                  @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                  <th>Action</th>
                  @endif

                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
            <?php $no = 1; ?>

                  @if(Auth::User()->id_position == 'ADMIN')
                  @foreach($pam as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->date_handover}}</td>
                      @if($data->status == 'NEW')
                      <td>{{$data->no_pr}}</td>
                      @else
                      <td><a href="{{url('detail_pam',$data->id_pam)}}">{{$data->no_pr}}</a></td>
                      @endif
                      <td>{{$data->to_agen}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->subject}}</td>
                      @if($data->status == 'NEW' || $data->status == 'ADMIN')
                      <td>
                        <button 
                        class="btn btn-sm btn-primary fa fa-search-plus fa-lg" style="width: 40px;height: 40px;text-align: center;" data-target="#modal_pr_asset_edit" data-toggle="modal" onclick="pam_edit('{{$data->id_pam}}','{{$data->to_agen}}','{{$data->date_handover}}','@foreach($produks as $produk) @if($data->id_pam == $produk->id_pam){{'\n'.$produk->name_product}} @endif @endforeach','@foreach($produks as $produk) @if ($data->id_pam == $produk->id_pam){{'\n'.$produk->qty}}@endif @endforeach','@foreach($produks as $produk) @if ($data->id_pam == $produk->id_pam){{'\n'.$produk->nominal}}@endif @endforeach','{{$data->note_pr}}')" value="{{$data->id_pam}}"></button>
                        <button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" data-toggle="modal" data-target="#modal_delete" onclick="delete_asset('{{$data->id_pam}}','{{$data->no_pr}}')">
                        </button>
                        @if($sum >= 1)
                        <button class="btn btn-success" style="width: 90px;height: 40px;" data-target="#modal_product" data-toggle="modal" onclick="id_pam_set('{{$data->id_pam}}')"><i class="fa fa-plus"></i>&nbspProduct</button>
                        @endif
                      </td>
                      @else
                      <td>
                        <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg disabled" style="width: 40px;height: 40px;text-align: center;">
                        </button>
                        <a><button class="btn btn-sm btn-danger fa fa-trash fa-lg disabled" style="width: 40px;height: 40px;text-align: center;">
                        </button></a>
                      </td>
                      @endif
                      @if($data->status == 'ADMIN')
                      <td>
                      <a href="{{action('PAMController@downloadPDF2',$data->id_pam)}}"><button class="btn btn-md btn-info" style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button></a>  
                      </td>
                      @elseif($data->status != 'ADMIN')
                      <td>
                      <button class="btn btn-md btn-info disabled" style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button>  
                      </td>
                      @endif
                      
                      @if($data->status == 'ADMIN')
                        <td>
                          <button data-target="#keterangan" data-toggle="modal" name="assign_to_hrd" id="assign_to_hrd" class="btn btn-warning btn-sm" onclick="pam_assign('{{$data->id_pam}}')">Submit</button>
                        </td>
                      @elseif($data->status != 'ADMIN')
                        <td>
                          <button class="btn btn-warning btn-sm disabled" style="color: white">Submit</button>
                        </td>
                      @endif

                      <td>
                        @if($data->status == 'NEW')
                        <label class="status-lose">PENDING</label>
                        @elseif($data->status == 'ADMIN')
                        <label class="status-lose">PENDING</label>
                        @elseif($data->status == 'HRD')
                        <label class="status-initial">HRD</label>
                        @elseif($data->status == 'FINANCE')
                        <label class="status-open">FINANCE</label>
                        @elseif($data->status == 'TRANSFER')
                        <label class="status-sd">TRANSFER</label>
                        @endif
                      </td>

                    </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
                  @foreach($pam as $data)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->date_handover}}</td>
                      @if($data->status == 'NEW')
                      <td>{{$data->no_pr}}</td>
                      @else
                      <td><a href="{{url('detail_pam',$data->id_pam)}}">{{$data->no_pr}}</a></td>                   
                      @endif
                      <td>{{$data->to_agen}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->subject}}</td>
                      
                      @if($data->status == 'HRD')
                        <td>
                          <button data-target="#keterangan" data-toggle="modal" name="assign_to_fnc"  class="btn btn-warning btn-sm" onclick="assign_to_fnc('{{$data->id_pam}}','{{$data->amount}}')">Submit</button>
                        </td>
                      @elseif($data->status != 'HRD')
                        <td>
                          <button class="btn btn-warning btn-sm disabled" style="color: white">Submit</button>
                        </td>
                      @endif

                      <td>
                        @if($data->status == 'NEW')
                        <label class="status-lose">ADMIN</label>
                        @elseif($data->status == 'ADMIN')
                        <label class="status-lose">ADMIN</label>
                        @elseif($data->status == 'HRD')
                        <label class="status-initial">PENDING</label>
                        @elseif($data->status == 'FINANCE')
                        <label class="status-open">FINANCE</label>
                        @elseif($data->status == 'TRANSFER')
                        <label class="status-sd">TRANSFER</label>
                        @endif
                      </td>

                    </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                  @foreach($pam as $data)
                  @if($data->status == 'HRD' || $data->status == 'FINANCE' || $data->status == 'TRANSFER')
                  <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->date_handover}}</td>
                      @if($count_product >= 1)
                      <td><a href="{{url('detail_pam',$data->id_pam)}}">{{$data->no_pr}}</a></td>
                      @else
                      <td>{{$data->no_pr}}</td>
                      @endif
                      <td>{{$data->to_agen}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->subject}}</td>

                      @if($data->status == 'FINANCE')
                        <td>
                          <button data-target="#keterangan" data-toggle="modal" name="assign_to_adm" class="btn btn-warning btn-sm" 
                          onclick="assign_to_adm('{{$data->id_pam}}','{{$data->amount}}')">Submit</button>
                        </td>
                      @elseif($data->status != 'FINANCE')
                        <td>
                          <button  class="btn btn-warning btn-sm disabled" style="color: white">Submit</button>
                        </td>
                      @endif

                      <td>
                        @if($data->status == 'ADMIN')
                        <label class="status-lose">PENDING</label>
                        @elseif($data->status == 'HRD')
                        <label class="status-initial">HRD</label>
                        @elseif($data->status == 'FINANCE')
                        <label class="status-open">FINANCE</label>
                        @elseif($data->status == 'TRANSFER')
                        <label class="status-sd">TRANSFER</label>
                        @endif
                      </td>

                  </tr>
                  @endif
                  @endforeach
                  @endif

              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
  </div>  
</div>

<!-- Modal Keterangan submit -->
  
@if(Auth::User()->id_position == 'ADMIN')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_hrd_pr_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_hrd_edit" name="assign_to_hrd_edit" value="" hidden>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@elseif(Auth::User()->id_position == 'HR MANAGER')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_fnc_pr_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_fnc_edit" name="assign_to_fnc_edit" value="" hidden>
          <div class="form-group newIcon inputIconBg">
            <label for="sow">Amount</label>
            <input name="amount" id="amount" class="form-control" readonly type="text"></input>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_adm_pr_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_adm_edit" name="assign_to_adm_edit" value="" hidden>
          <div class="form-group newIcon inputIconBg">
            <label for="sow">Amount</label>
            <input name="amount" id="amount" class="form-control money" readonly></input>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@endif

<!--Modal Add Produk-->
<div class="modal fade" id="modal_product" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Request Product</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <form name="add_produk" id="add_produk">
                  <table id="mytable">
                      @csrf
                      <input type="" name="id_pam_set" id="id_pam_set" hidden>
                      <tr class="tr-header">
                        <th>Produk</th>
                        <th>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspQty</th>
                        <th>&nbsp&nbspNominal</th>
                              <th>&nbsp&nbspDescription</th>
                        <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore"><span class="fa fa-plus"></span></a></th>
                      </tr>
                      <tr>
                        <td style="margin-bottom: 50px">
                          <br><input type="text" name="produk[]" id="produk" class="form-control-produk">
                        </td>
                        <td style="margin-bottom: 50px">
                          <br><input type="number" name="qty[]" id="qty" class="form-control-medium pull-right">
                        </td>
                        <td style="margin-bottom: 50px">
                        <br>
                          <div class="modalIcon inputIconBg" style="padding-left: 10px">
                          <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required>
                          <i class="" aria-hidden="true">Rp.</i>
                        </div>
                        </td>
                            <td style="margin-bottom: 50px;padding-left: 10px">
                              <textarea name="ket[]" id="ket[]" class="form-control" style="height: 50px" required></textarea>
                            </td>
                        <td>
                          <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;margin-top: 20px;color: red;"></span></a>
                        </td>
                      </tr>
                  </table>
                <hr>
                <div class="float-right">
                  <button class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                  @if($sum >= 1)
                  <input type="button" name="submit" id="submit" class="btn btn-sm btn-primary" value="Submit" /> 
                  @endif
                </div> 
              </form>
             </div>
         </div>
          </div>
      </div>
  </div>
</div>

<!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr_asset" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Request Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_pr_asset')}}" id="modal_pr_asset" name="modal_pr_asset">
            @csrf
          
          <div class="form-group">
            <label for="">No PR</label>
            <select class="form-control" id="position" name="position" required>
                @foreach($no_pr as $no)
                  <option value="{{$no->no}}">{{$no->no_pr}}</option>
                @endforeach
            </select>
          </div>

           <div class="form-group">
            <label for="">To</label>
            <input type="text" name="to_agen" id="to_agen" class="form-control">
          </div>

           <div class="form-group">
            <label for="">Created Date</label>
            <input type="date" class="form-control" placeholder="" name="date_handover" id="date_handover" required>
           </div>

            <div class="form-group">
            <label for="">From</label>
            <select class="form-control" id="owner_pr" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr" required>
              <option value="">-- Select From --</option>
              @foreach($owner as $data)
                  <option value="{{$data->nik}}">{{$data->name}}</option>
              @endforeach
            </select>
            </div>

      <div class="form-group">
            <label for="">Subject</label>
            <!-- <select class="form-control" id="subject" style="width: 100%;" onkeyup="copytextbox();" name="subject" required>
              <option value="">-- Select From --</option>
              <option value="goods">Goods</option>
              <option value="service">Service</option>
            </select> -->
            <input type="text" name="subject" id="subject" class="form-control" required >
            </div>  
         

 <!--          <div class="form-group">
            <label for="">Batas Akhir Tanggal</label>
            <input type="date" class="form-control" placeholder="" name="due_date" id="due_date" required>
           </div> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Edit-->
<div class="modal fade" id="modal_pr_asset_edit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Purchase Request Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('edit_pr_asset')}}" id="modal_pr_asset_edit" name="modal_pr_asset_edit">
            @csrf
           <input type="text" name="id_pam" id="id_pam" hidden>
           <div class="form-group">
            <label for="">Tanggal Serah Purchase Request</label>
            <input type="date" class="form-control" placeholder="" name="date_handover_edit" id="date_handover_edit" >
           </div>

           <div class="form-group">
            <label for="">To</label>
            <input type="text" name="to_agen_edit" id="to_agen_edit" class="form-control">
          </div>
<!-- 
           <div class="form-group">
            <label for="">Produk</label>
            <table border="1">
            <tr>
              <th style="width: 100%">Name Product</th>
              <th>Qty</th>
              <th>Nominal</th>
            </tr>
              <tr>
                <td><textarea rows="9" name="product" id="product" style="width: 565px" readonly></textarea></td>
                <td><textarea rows="9" name="qty_product" id="qty_product" style="width: 30px" readonly></textarea></td>
                <td><textarea rows="9" name="nominal_product" id="nominal_product" readonly></textarea></td>
              </tr>
            </table>
           </div> -->
        
<!-- 
           <div class="form-group">
            <label for="">Keterangan</label>
            <textarea name="ket_edit" id="ket_edit" class="form-control" ></textarea>
           </div>

           <div class="form-group">
            <label for="">Catatan Lain</label>
            <textarea name="note_edit" id="note_edit" class="form-control"></textarea>
           </div> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <a href="" onclick="document.getElementById('modal_pr_asset_edit').submit();"><button type="submit" name="" class="btn btn-warning"><i class=" fa fa-check"></i>&nbsp Edit</button></a>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

 <div class="modal fade" id="assign_to_hrd_pr_asset" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form action="{{url('assign_to_hrd_pr_asset')}}" method="POST">
            {!! csrf_field() !!}
            <input type=""  value="" name="assign_form_hrd" id="assign_form_hrd" hidden>
            <div style="text-align: center;">
              <h3>Are you sure?</h3><br><h3>Submit To HRD</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success-raise" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_delete" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form action="{{url('delete_pr_asset')}}" method="GET">
            {!! csrf_field() !!}
            <input type="text"  name="id_pam_asset" id="id_pam_asset" hidden>
            <input type="text" name="no_pr_asset" id="no_pr_asset" hidden>
            <div style="text-align: center;">
              <h3>Are you sure?</h3><br><h3>Delete</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success-raise" type="submit"><b>Yes</b></button>
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
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript">
    $('#datasmu').DataTable({
        "scrollX": true
      });

      $('.money').mask('000,000,000,000,000', {reverse: true});

      function pam_edit(id_pam,to_agen,date_handover,name_product,qty,nominal,note_pr){
        $('#id_pam').val(id_pam);  
        $('#to_agen_edit').val(to_agen)
        $('#date_handover_edit').val(date_handover);   
        $('#product').val(name_product);
        $('#qty_product').val(qty);
        $('#nominal_product').val(nominal);
        $('#note_edit').val(note_pr); 
      }

      function pam_assign(id_pam){
        $('#assign_to_hrd_edit').val(id_pam);
      }

      function assign_to_fnc(id_pam,amount){
        $('#assign_to_fnc_edit').val(id_pam);
        $('#amount').val(amount);
      }

      function assign_to_adm(id_pam,amount){
        $('#assign_to_adm_edit').val(id_pam);
        $('#amount').val(amount);
      }

      function delete_asset(id_pam,no_pr){
        $('#id_pam_asset').val(id_pam);
        $('#no_pr_asset').val(no_pr);
      }

    function id_pam_set(id_pam)
    {
    $('#id_pam_set').val(id_pam);
    }

    function removeRow(oButton) {
        var empTab = document.getElementById('mytable');
        empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);       // BUTTON -> TD -> TR.
    }

     $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
          });

    $('#owner_pr').select2();

     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
      });

    /* $("body").on("click", "input", function() {
        alert("My name: " + this.name);
    });*/
    var i=1;  
    $('#addMore').click(function(){  
         i++;  
         $('#mytable').append('<tr id="row'+i+'"><td style="margin-bottom: 5px"><br><input type="text" name="produk[]" id="produk" class="form-control-produk"></td><td style="margin-bottom: 5px"><br><input type="number" name="qty[]" id="qty" class="form-control-medium pull-right"></td><td style="margin-bottom: 5px"><br><div class="modalIcon inputIconBg" style="padding-left: 10px"><input type="text" class="form-control" placeholder="Enter Amount" name="nominal[]" id="nominal" required><i class="money" aria-hidden="true">Rp.</i></div></td><td style="margin-bottom: 5px;padding-left: 10px"><textarea name="ket[]" id="ket[]" style="height: 50px" class="form-control" required></textarea></td><td><a href="javascript:void(0);" id="'+i+'"  class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');  

         initMaskMoney();
    });

    function initMaskMoney() {
      $('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});
    }

    $('#submit').click(function(){            
         $.ajax({  
              url:"/store_produk",  
              method:"POST",  
              data:$('#add_produk').serialize(),  
              success:function(data)  
              { 
                 window.location.reload(true);  
              }
         });  
    });  
        
</script>
@endsection