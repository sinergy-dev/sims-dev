@extends('template.template_admin-lte')
@section('content')

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

<section class="content-header">
  <h1>Purchase Request Asset Management</h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">PR Asset Management</li>
    </ol>
</section>

<section class="content">

  @if(session('success'))
    <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
  @endif

  <div class="box">
    <div class="box-header with-border">
      <div class="pull-right">
          <a href="{{url('/add_pam')}}">
            <button class="btn btn-success btn-md pull-right float-right margin-left-custom" id=""><i class="fa fa-plus"> </i>&nbsp PR Asset</button>
          </a>
          <div class="dropdown  float-right ">
            <button class="btn btn-warning btn-md dropdown-toggle margin-left-customt" type="button" data-toggle="dropdown" >Export
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="{{action('PAMController@downloadPDF')}}">PDF</a></li>
              <li><a href="{{action('PAMController@exportExcel')}}">Excel</a></li>
            </ul>
          </div>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped display nowrap" id="datasmu" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No.</th>
              <th>Created Date</th>
              <th>No. Purchase Request</th>
              <th>To</th>
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
                  <td>{{$data->date}}</td>
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
                    @if($data->type_of_letter == 'EPR')
                    <div class="dropdown ">
                      <button class="btn btn-sm btn-warning dropdown-toggle float-right margin-left-customt" type="button" data-toggle="dropdown" ><b><i class="fa fa-plus"></i>Produk</b>
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-target="#modal_produk" data-toggle="modal" onclick="id_pam_set('{{$data->id_pam}}')">Produk Supplier</a></li>
                        <li><a class="dropdown-item" data-target="#modal_product_customer" data-toggle="modal" onclick="id_pam_cus('{{$data->id_pam}}')">Produk Customer</a></li>
                      </ul>
                    </div>
                    @elseif($data->type_of_letter == 'IPR')
                    <button type="button" class="btn btn-warning-eksport margin-left-customt" data-toggle="modal" data-target="#modal_produk" aria-haspopup="true" aria-expanded="false" style="width: 90px;height: 30px;" onclick="id_pam_set('{{$data->id_pam}}')">
                    <b><i class="fa fa-plus"></i> Produk</b>
                    </button>
                    @endif
                  </td>
                  @else
                  <td>
                    @if($data->type_of_letter == 'EPR')
                    <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right margin-left-customt disabled"  aria-haspopup="true" aria-expanded="false" style="width: 90px;height: 30px;">
                    <b><i class="fa fa-plus"></i> Produk</b>
                    </button>
                    @elseif($data->type_of_letter == 'IPR')
                    <button type="button" class="btn btn-warning-eksport float-right margin-left-customt disabled" aria-haspopup="true" aria-expanded="false" style="width: 90px;height: 30px;">
                    <b><i class="fa fa-plus"></i> Produk</b>
                    </button>
                    @endif
                  </td>
                  @endif
                  @if($data->status == 'ADMIN')
                  <td>
                  <a href="{{url('downloadPdfPR2',$data->id_pam)}}" target="_blank"><button class="btn btn-md btn-info btn-print"  style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button></a>  
                  </td>
                  @elseif($data->status != 'ADMIN')
                  <td>
                  <button class="btn btn-md btn-info disabled" style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button>  
                  </td>
                  @endif
                  
                  @if($data->status == 'ADMIN')
                    <td>
                      <button data-target="#keterangan" data-toggle="modal" name="assign_to_hrd" id="assign_to_hrd" class="btn btn-warning btn-sm" onclick="pam_assign('{{$data->id_pam}}', '{{$data->id_po_asset}}')">Submit</button>
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

              <!-- @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
              @foreach($pam as $data)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{$data->date}}</td>
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
              @endif -->

              @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
              @foreach($pam as $data)
              @if($data->status == 'HRD' || $data->status == 'FINANCE' || $data->status == 'TRANSFER')
              <tr>
                  <td>{{$no++}}</td>
                  <td>{{$data->date}}</td>
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
</section>

<div class="modal fade" id="modal_produk" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Request Product(Supplier)</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <form method="POST" action="{{url('store_produk')}}">
                  <table id="mytablee">
                    {{ csrf_field() }}
                    <input type="" name="id_pam_set" id="id_pam" hidden>
                    <tr class="tr-header">
                      <th style="margin-left: 10px;">Produk</th>
                      <th style="margin-left: 10px;">Qty</th>
                      <th style="margin-left: 10px;">Price</th>
                      <th style="margin-left: 10px;">Description</th>
                      <th><a href="javascript:void(0);" style="font-size:18px;margin-left: 10px;" id="addMoree"><span class="fa fa-plus"></span></a></th>
                    </tr>
                    <tr>
                      <td style="margin-bottom: 50px">
                        <br><input type="text"  class="form-control" placeholder="Enter Name Produk" name="name_product[]" id="name" required>
                      </td>
                      <td style="margin-bottom: 50px;">
                        <br>
                       <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px; margin-left: 10px;" required>
                      </td>
                      <td style="margin-bottom: 50px;">
                        <br>
                        <div class="modalIcon inputIconBg" style="padding-left: 10px; margin-left: 10px;">
                        <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required>
                        <i class="" aria-hidden="true">Rp.</i>
                        </div>
                      </td>
                      <td style="margin-bottom: 50px;">
                        <br>
                        <input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" style="width: 250px; margin-left: 10px;" required>
                      </td>
                      <td>
                        <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;margin-top: 20px;color: red; margin-left: 10px;"></span></a>
                      </td>
                    </tr>
                  </table>
                <hr>
                <div class="modal-footer">
                  <button class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                  @if($sum >= 1)
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
                  <!-- <input type="button" name="submit" id="submit" class="btn btn-sm btn-primary" value="Submit" />  -->
                  @endif
                </div> 
              </form>
             </div>
        </div>
      </div>
  </div>
</div>

<div class="modal fade" id="modal_product_customer" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Request Product(Customer)</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <form method="POST" action="{{url('store_produk_cus')}}">
                  <table id="mytable">
                    {{ csrf_field() }}
                    <input type="" name="id_pam_cus" id="id_pam_cus" hidden>
                    <tr class="tr-header">
                      <th style="border-left: 10px;">Produk</th>
                      <th style="border-left: 10px;">Qty</th>
                      <th style="border-left: 10px;">Price</th>
                      <th style="border-left: 10px;">Description</th>
                      <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore"><span class="fa fa-plus"></span></a></th>
                    </tr>
                    <tr>
                      <td style="margin-bottom: 75px">
                        <br><input type="text" class="form-control" placeholder="Enter Name Produk" name="name_product_cus[]" id="name" required>
                      </td>
                      <td style="margin-bottom: 75px;">
                        <br>
                       <input type="number" class="form-control" placeholder="qty" name="qty_cus[]" id="quantity" style="width: 70px;font-size: 14px; margin-left: 10px;" required>
                      </td>
                      <td style="margin-bottom: 75px">
                        <br><div class="modalIcon inputIconBg" style="padding-left: 10px; margin-left: 10px;">
                        <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal_cus[]" id="nominal[]" required>
                        <i class="" aria-hidden="true">Rp.</i>
                        </div>
                      </td>
                      <td style="margin-bottom: 75px">
                        <br>
                        <input type="text" class="form-control" placeholder="Enter Information" name="ket_cus[]" id="information" style="width: 250px; margin-left: 10px;" required>
                      </td>
                      <td>
                        <a href='javascript:void(0);'  class='remove'><span class='fa fa-times' style="font-size: 18px;margin-top: 20px;color: red; margin-left: 10px;"></span></a>
                      </td>
                    </tr>
                  </table>
                <hr>
                <div class="modal-footer">
                  <button class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                  @if($sum >= 1)
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
                  <!-- <input type="button" name="submit" id="submit" class="btn btn-sm btn-primary" value="Submit" />  -->
                  @endif
                </div> 
              </form>
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
            <label for="">Created Date</label>
            <input type="date" class="form-control" placeholder="" name="date_supplier" id="date" >
          </div>

          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" >
                <option>PMO</option>
                <option>PRE</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FIN</option>
                <option>HRD</option>
            </select>
          </div>

          <div class="form-group">
            <label>Purchase Request (Harus Diisi)</label>
            <label class="radios">
              <input type="radio" name="type_of_letter" value="IPR" id="internal_button" style="width: 15px; height: 15px;" onclick="javascript:yesnoCheck();">Internal
              <span class="checkmark"></span>
            </label>
            <label class="radios">
              <input type="radio" name="type_of_letter" value="EPR" id="eksternal_button" style="width: 15px; height: 15px; " onclick="javascript:yesnoCheck();">Eksternal
              <span class="checkmark"></span>
            </label>
          </div>

          <div id="internal" style="display: none;">

            <div class="form-group">
              <label for="">To</label>
              <input type="text" name="to_agen_supp_intern" id="to_agen" class="form-control" placeholder="Enter To">
            </div>

            <div class="form-group">
              <label>Address</label>
              <textarea class="form-control" name="address_supp_intern" id="address"></textarea>
            </div>

            <div class="form-group"> 
              <label>Telp</label>
              <input type="telp" name="telp_supp_intern" class="form-control" placeholder="Enter Telp">
            </div>

            <div class="form-group">
              <label>Fax</label>
              <input type="fax" name="fax_supp_intern" class="form-control" placeholder="Enter Fax">
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email_supp_intern" class="form-control" placeholder="Enter Email">
            </div>

            <div class="form-group">
              <label>Attention</label>
              <input type="attention" name="attention_supp_intern" class="form-control" placeholder="Enter Attention">
            </div>

            <div class="form-group">
            <label for="">From</label>
            <select class="form-control" id="owner_pr" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr_supp_intern" >
              <option value="">-- Select From --</option>
              @foreach($owner as $data)
                  <option value="{{$data->nik}}">{{$data->name}}</option>
              @endforeach
            </select>
            </div>

            <div class="form-group">
              <label for="">Subject</label>
              <input type="text" name="subject_supp_intern" id="subject" class="form-control" placeholder="Enter Subject"  >
            </div>

            <div class="form-group">
              <label>Project</label>
              <input type="project" name="project_supp_intern" placeholder="Enter Project" class="form-control">
            </div>

            <!-- <div class="form-group">
              <label>Project ID</label>
              <input type="project_id" name="project_id_supp_intern" class="form-control" placeholder="Enter Project ID">
            </div> -->

            <div class="form-group">
            <label for="">Project ID</label>
            <select class="form-control" id="project_id_supp_intern" style="width: 100%;" onkeyup="copytextbox();" name="project_id_supp_intern" >
              <option value="">-- Select Project ID --</option>
              @foreach($project_id as $data)
                  <option value="{{$data->id_project}}">{{$data->id_project}}</option>
              @endforeach
            </select>
            </div>

            <div class="form-group">
              <label>Terms & Condition</label>
              <textarea class="form-control" name="term_supp_intern" id="term" placeholder="Enter Terms & Condition"></textarea>
            </div>

            <div class="form-group">
              <label>PPn (Harus Diisi)</label>
              <label class="radios">
                <input type="radio" name="ppn_internal" value="YA" style="width: 15px; height: 15px;">Ya
                <span class="checkmark"></span>
              </label>
              <label class="radios">
                <input type="radio" name="ppn_internal" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                <span class="checkmark"></span>
              </label>
            </div>

          </div>
          <div id="eksternal" style="display: none;">
            <div class="col-md-6" style="float: left;">

              <div class="form-group">
                <label for="">To(Supplier)</label>
                <input type="text" name="to_agen_supplier" id="to_agen" class="form-control" placeholder="Enter To">
              </div>

              <div class="form-group">
                <label>Address(Supplier)</label>
                <textarea class="form-control" name="address_supplier" id="address"></textarea>
              </div>

              <div class="form-group"> 
                <label>Telp(Supplier)</label>
                <input type="telp" name="telp_supplier" class="form-control" placeholder="Enter Telp">
              </div>

              <div class="form-group">
                <label>Fax(Supplier)</label>
                <input type="fax" name="fax_supplier" class="form-control" placeholder="Enter Fax">
              </div>

              <div class="form-group">
                <label>Email(Supplier)</label>
                <input type="email" name="email_supplier" class="form-control" placeholder="Enter Email">
              </div>

              <div class="form-group">
                <label>Attention(Supplier)</label>
                <input type="attention" name="attention_supplier" class="form-control" placeholder="Enter Attention">
              </div>

              <div class="form-group">
              <label for="">From</label>
              <select class="form-control" id="owner_pr" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr_supplier" >
                <option value="">-- Select From --</option>
                @foreach($owner as $data)
                    <option value="{{$data->nik}}">{{$data->name}}</option>
                @endforeach
              </select>
              </div>

              <div class="form-group">
                <label for="">Subject</label>
                <input type="text" name="subject_supplier" id="subject" class="form-control" placeholder="Enter Subject"  >
              </div>

              <div class="form-group">
                <label>Project</label>
                <input type="project" name="project_supplier" placeholder="Enter Project" class="form-control">
              </div>

              <!-- <div class="form-group">
                <label>Project ID</label>
                <input type="project_id" name="project_id_supplier" class="form-control" placeholder="Enter Project ID">
              </div> -->

              <div class="form-group">
              <label for="">Project ID</label>
              <select class="form-control" id="project_id_supplier" style="width: 100%;" onkeyup="copytextbox();" name="project_id_supplier" >
                <option value="">-- Select Project ID --</option>
                @foreach($project_id as $data)
                    <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                @endforeach
              </select>
              </div>

              <div class="form-group">
                <label>Terms & Condition</label>
                <textarea class="form-control" name="term_supplier" id="term" placeholder="Enter Terms & Condition"></textarea>
              </div>

              <div class="form-group">
                <label>PPn (Harus Diisi)</label>
                <label class="radios">
                  <input type="radio" name="ppn" value="YA" style="width: 15px; height: 15px;">Ya
                  <span class="checkmark"></span>
                </label>
                <label class="radios">
                  <input type="radio" name="ppn" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>

            <div class="col-md-6" style="float: right;">
              <div class="form-group">
              <label for="">To(Customer)</label>
              <input type="text" name="to_agen_customer" id="to_agen" class="form-control" placeholder="Enter To">
              </div>

              <div class="form-group">
                <label>Address(Customer)</label>
                <textarea class="form-control" name="address_customer" id="address"></textarea>
              </div>

              <div class="form-group"> 
                <label>Telp(Customer)</label>
                <input type="telp" name="telp_customer" class="form-control" placeholder="Enter Telp">
              </div>

              <div class="form-group">
                <label>Fax(Customer)</label>
                <input type="fax" name="fax_customer" class="form-control" placeholder="Enter Fax">
              </div>

              <div class="form-group">
                <label>Email(Customer)</label>
                <input type="email" name="email_customer" class="form-control" placeholder="Enter Email">
              </div>

              <div class="form-group">
                <label>Attention(Customer)</label>
                <input type="attention" name="attention_customer" class="form-control" placeholder="Enter Attention">
              </div>

              <div class="form-group">
                <label>PPn (Harus Diisi)</label>
                <label class="radios">
                  <input type="radio" name="ppn_customer" value="YA" style="width: 15px; height: 15px;">Ya
                  <span class="checkmark"></span>
                </label>
                <label class="radios">
                  <input type="radio" name="ppn_customer" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
        </div>
        </form>
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

<div class="modal fade" id="modal_print" role="dialog">
  <div class="modal-dialog modal-sm">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>
      <div class="moda-body">
        {!! csrf_field() !!}
        <input type=""  value="" name="assign_form_hrd" id="assign_form_hrd" hidden>
        <div style="text-align: center;">
          <button class="btn btn-default btn-sm" id="btn-print">Print</button>
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
          <form method="POST" action="{{url('/assign_to_fnc_pr_asset')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_fnc_edit" name="assign_to_fnc_edit" value="" hidden>
          <input type="" id="status_po" name="status_po" value="" hidden>
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
{{--  @elseif(Auth::User()->id_position == 'HR MANAGER')
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
  </div>  --}}
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

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/lins/jqeury/1.12.0/jqeury.min.js"></script>
<script src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript">
      $('#datasmu').DataTable({
          "scrollX": true
        });

      function yesnoCheck() {
        if (document.getElementById('internal_button').checked) {
        document.getElementById('internal').style.display = 'block'; 
        document.getElementById('eksternal').style.display = 'none';  
        }
        else {
        document.getElementById('internal').style.display = 'none';
        document.getElementById('eksternal').style.display = 'block'; 
        }
      }

      $('.money').mask('000,000,000,000,000', {reverse: true});

      function pam_edit(id_pam,to_agen,date,name_product,qty,nominal,note_pr){
        $('#id_pam').val(id_pam);  
        $('#to_agen_edit').val(to_agen)
        $('#date_handover_edit').val(date);   
        $('#product').val(name_product);
        $('#qty_product').val(qty);
        $('#nominal_product').val(nominal);
        $('#note_edit').val(note_pr); 
      }

      function pam_assign(id_pam,id_po_asset){
        $('#assign_to_fnc_edit').val(id_pam);
        $('#status_po').val(id_po_asset);
      }

      /*function assign_to_fnc(id_pam,amount){
        $('#assign_to_fnc_edit').val(id_pam);
        $('#amount').val(amount);
      }*/

      function assign_to_adm(id_pam,amount){
        $('#assign_to_adm_edit').val(id_pam);
        $('#amount').val(amount);
      }

    function id_pam_set(id_pam)
    {
      $('#id_pam').val(id_pam);
    }

    function id_pam_cus(id_pam)
    {
      $('#id_pam_cus').val(id_pam);
    }

    function removeRow(oButton) {
        var empTab = document.getElementById('mytable');
        empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);       // BUTTON -> TD -> TR.
    }

     $("#alert").fadeTo(2000, 500).slideUp(500, function(){
       $("#alert").slideUp(300);
      });

    $('#owner_pr').select2();
    $('#project_id_supp_intern').select2();
    $('#project_id_supplier').select2();

     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
      });

   /*  $("body").on("click", "input", function() {
        alert("My name: " + this.name);
    });*/
    var i=1;  
    $('#addMore').click(function(){  
         i++;  
         $('#mytable').append('<tr id="row'+i+'"><td><br><input type="text" class="form-control" placeholder="Enter Name" name="name_product[]" id="name" required></td><td><br> <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required></td><td style="margin-bottom: 50px"><br><div class="modalIcon inputIconBg" style="padding-left: 10px"><input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required><i class="" aria-hidden="true">Rp.</i></div></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" style="width: 250px;" required></td><td><a href="javascript:void(0);" id="'+i+'" class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
         initMaskMoney();
    });

    var i=1;  
    $('#addMoree').click(function(){  
         i++;  
         $('#mytablee').append('<tr id="row'+i+'"><td><br><input type="text" class="form-control" placeholder="Enter Name" name="name_product[]" id="name" required></td><td><br> <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required></td><td style="margin-bottom: 50px"><br><div class="modalIcon inputIconBg" style="padding-left: 10px"><input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required><i class="" aria-hidden="true">Rp.</i></div></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" style="width: 250px;" required></td><td><a href="javascript:void(0);" id="'+i+'" class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
         initMaskMoney();
    });

    function initMaskMoney() {
        $('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});
    }

    $(document).ready(function(){
      $('#btn-print').printPage('_blank');
    });

/*    @foreach($pam as $data)
    $('.btn-print').click(function(){
      window.open("url('downloadPdfPR2','+{{$data->id_pam}}+')",'_blank');
      $("#modal-print").modal()
    })
    @endforeach
*/
    /*$('#submit').click(function(){            
         $.ajax({  
              url:"/store_produk",  
              method:"POST",  
              data:$('#add_produk').serialize(),  
              success:function(data)  
              { 
                swal({
                      title: "Success!",
                      text:  "You have been add product",
                      type: "success",
                      timer: 2000,
                      showConfirmButton: false
                  });
                     setTimeout(function() {
                         window.location.href = window.location;
                      }, 2000);                                
              }
         });  
    });  */
        
</script>
@endsection

