@extends('template.template')
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

    .modalIconpr input[type=text]{
      padding-left:50px;
    }

    .modalIconpr.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIconpr.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIconpr{
      position:relative;
    }

   .modalIconpr i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }

    .modalIconsubject input[type=text]{
      padding-left:60px;
    }

    .modalIconsubject.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIconsubject.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIconsubject{
      position:relative;
    }

   .modalIconsubject i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }
</style>

      <!-- @if (session('update'))
        <div class="alert alert-warning" id="alert">
            {{ session('update') }}
        </div>
      @endif -->

      <!-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('update') }}          
        </div>
      @endif -->

      <!-- @if (session('alert'))
        <div class="alert alert-success" id="alert">
            {{ session('alert') }}
        </div>
      @endif
 -->
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
      @elseif(session('update'))
      <div class="alert alert-warning" id="alert">{{ session('update') }}</div>
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
                  <!-- <th>No.</th> -->
                  <th>No. Purchase Request</th>
                  <th>Created Date</th>
                  <th>To</th><!-- 
                  <th>Nominal</th> -->
                  <th>From</th>
                  <th>Subject</th>
                  @if(Auth::User()->id_position == 'ADMIN')
                  <th>Action</th>
                  <th>Action</th>
                  @endif
                  
                  @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
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
                      <!-- <td>{{$no++}}</td> -->
                      @if($data->status == 'NEW')
                      <td>{{$data->no_pr}}</td>
                      @else
                      <td><a href="{{url('detail_pam_msp',$data->id_pam)}}">{{$data->no_pr}}</a></td>
                      @endif
                      <td>{{$data->date}}</td>
                      <td>{{$data->to_agen}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->subject}}</td>
                      @if($data->status == 'NEW' || $data->status == 'ADMIN')
                      <td>
                        <!-- <button 
                        class="btn btn-sm btn-primary fa fa-search-plus fa-lg" style="width:50px; height:30px;text-align: center;vertical-align: top;" data-target="#modal_pr_asset_edit" data-toggle="modal" onclick="pam_edit('{{$data->id_pam}}','{{$data->to_agen}}', '{{$data->attention}}', '{{$data->subject}}', '{{$data->project}}', '{{$data->project_id}}', '{{$data->terms}}')">&nbsp Edit</button> -->
                        <!-- <a href="{{url('')}}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 55px;height: 30px;text-align: center;vertical-align: top;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">&nbspDelete
                        </button></a> -->
                        @if($sum >= 1)
                        <button class="btn btn-success" style="width: 75px;height: 30px;text-align: center;" data-target="#modal_product" data-toggle="modal" onclick="id_pam_set('{{$data->id_pam}}', '{{$data->no_pr}}', '{{$data->subject}}')"><i class="fa fa-plus"></i>&nbspProduct</button>
                        @endif
                      </td>
                      @else
                      <td>
                        <!-- <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg disabled" style="width: 50px;height: 30px;text-align: center;">&nbspEdit
                        </button> -->
                        <!-- <a><button class="btn btn-sm btn-danger fa fa-trash fa-lg disabled" style="width: 40px;height: 40px;text-align: center;">&nbspDelete
                        </button></a> -->
                        <button class="btn btn-success disabled" style="width: 75px;height: 30px;text-align: center;"><i class="fa fa-plus"></i>&nbspProduct</button>
                      </td>
                      @endif
                      <td>
                      <a href="{{action('PAMMSPController@downloadPDF2',$data->id_pam)}}" target="_blank"><button class="btn btn-md btn-info" style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button></a>	
                      </td>
                      
                      @if($data->status == 'ADMIN')
                        <td>
                          <button data-target="#keterangan" data-toggle="modal" name="assign_to_fnc" id="assign_to_fnc" class="btn btn-warning btn-sm" onclick="pam_assign('{{$data->id_pam}}','{{$data->id_po_asset}}')">Submit</button>
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
                  @if($data->status == 'FINANCE' || $data->status == 'TRANSFER')
                  <tr>
                      <td>{{$no++}}</td>
                      <td>{{$data->date}}</td>
                      @if($count_product >= 1)
                      <td><a href="{{url('detail_pam_msp',$data->id_pam)}}">{{$data->no_pr}}</a></td>
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
                        @if($data->status == 'FINANCE')
                        <label class="status-open">PENDING</label>
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

<!-- modal submit -->
@if(Auth::User()->id_position == 'ADMIN')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_fnc_pr_asset_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_fnc_edit" name="assign_to_fnc_edit" value="" hidden>
          <input type="" id="id_po_asset_msp_edit" name="id_po_asset_msp_edit" value="" hidden>
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
<!-- @elseif(Auth::User()->id_position == 'HR MANAGER')
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
  </div> -->
@elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_adm_pr_asset_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_adm_edit" name="assign_to_adm_edit" value="" hidden>
          <!-- <div class="form-group newIcon inputIconBg">
            <label for="sow">Amount</label>
            <input name="amount" id="amount" class="form-control money" readonly></input>
            <i class="" aria-hidden="true">Rp.</i>
          </div> -->
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
                <form method="POST" action="{{url('store_produk_msp')}}">
                  <table id="mytable">
                    {{ csrf_field() }}
                    <input type="" name="id_pam_set" id="id_pam_set" hidden>
                    <div class="form-group modalIconpr inputIconBg" style="padding-left: 10px">
                      <input type="text" class="form-control money" name="no_pr" id="no_pr" readonly>
                      <i class="" aria-hidden="true">No PR</i>
                    </div>
                    <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
                      <input type="text" class="form-control money" name="subject" id="subject" readonly>
                      <i class="" aria-hidden="true">Subject</i>
                    </div>
                    <tr class="tr-header">
                      <th>MSP Code</th>
                      <th>Name</th>
                      <th>Qty</th>
                      <th>Unit</th>
                      <th>Nominal</th>
                      <th>Description</th>
                      <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore"><span class="fa fa-plus"></span></a></th>
                    </tr>
                    <tr>
                      <td style="margin-bottom: 50px;">
                        <br><input type="text" name="msp_code[]" class="form-control" placeholder="Enter Product Id" required>
                      </td>
                      <td style="margin-bottom: 50px">
                        <br><input type="text" class="form-control" placeholder="Enter Name" name="name_product[]" id="name" required>
                      </td>
                      <td style="margin-bottom: 50px;">
                        <br>
                       <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required>
                      </td>
                      <td style="margin-bottom: 50px">
                        <br><input type="text" class="form-control" placeholder="Enter unit" name="unit[]" id="unit" required>
                      </td>
                      <td style="margin-bottom: 50px">
                        <br><div class="modalIcon inputIconBg" style="padding-left: 10px">
                        <input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required>
                        <i class="" aria-hidden="true">Rp.</i>
                        </div>
                      </td>
                      <td style="margin-bottom: 50px">
                        <br>
                        <input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" required>
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
</div>

<!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr_asset" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Purchase Request Asset</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_pr_asset_msp')}}" id="modal_pr_asset" name="modal_pr_asset">
            @csrf

           <div class="form-group">
            <label for="">To</label>
            <input type="text" name="to_agen" id="to_agen" class="form-control" placeholder="Enter To">
          </div>

           <!-- <div class="form-group">
            <label for="">Created Date</label>
            <input type="date" class="form-control" placeholder="" name="date" id="date" required>
           </div> -->

           <div class="form-group">
             <label>Address</label>
             <textarea class="form-control" name="address" id="address"></textarea>
           </div>

           <div class="form-group"> 
             <label>Telp</label>
             <input type="telp" name="telp" class="form-control" placeholder="Enter Telp">
           </div>

           <div class="form-group">
             <label>Fax</label>
             <input type="fax" name="fax" class="form-control" placeholder="Enter Fax">
           </div>

           <div class="form-group">
             <label>Email</label>
             <input type="email" name="email" class="form-control" placeholder="Enter Email">
           </div>

           <div class="form-group">
             <label>Attention</label>
             <input type="attention" name="attention" class="form-control" placeholder="Enter Attention">
           </div>

            <div class="form-group">
            <label for="">From</label>
            <select class="form-control" id="owner_pr" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr" required>
              <option value="">-- Select From --</option>
              @foreach($from as $data)
                  <option value="{{$data->nik}}">{{$data->name}}</option>
              @endforeach
            </select>
            </div>

			     <div class="form-group">
            <label for="">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Subject" required >
            </div>

            <div class="form-group">
              <label>Project</label>
              <input type="project" name="project" placeholder="Enter Project" class="form-control">
            </div>

            <!-- <div class="form-group">
              <label>Project ID</label>
              <input type="project_id" name="project_id" class="form-control" placeholder="Enter Project ID">
            </div> -->

            <div class="form-group">
              <label for="">Project ID</label>
              <select class="form-control" id="project_id" style="width: 100%;" onkeyup="copytextbox();" name="project_id" >
                <option value="">-- Select Project ID --</option>
                @foreach($project_id as $data)
                    <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Terms & Condition</label>
              <textarea class="form-control" name="term" id="term" placeholder="Enter Terms & Condition"></textarea>
            </div>

            <div class="form-group">
              <label>PPn (Harus Diisi)</label>
              <label class="radios">
                <input type="radio" name="radiobutton" value="YA" style="width: 15px; height: 15px;">Ya
                <span class="checkmark"></span>
              </label>
              <label class="radios">
                <input type="radio" name="radiobutton" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                <span class="checkmark"></span>
              </label>
            </div>

            <!-- <div class="form-group newIcon inputIconBg">
              <label for="">PPh &nbsp&nbsp&nbsp<input type="checkbox" id="yourBox" style="width: 7px;height: 7px" /> *centang jika menggunakan PPh</label>
              <input type="text" class="form-control" placeholder="Enter PPh" name="pph" id="yourText" disabled>
              <i class="" aria-hidden="true">%</i>
            </div>  -->

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
          <form method="POST" action="{{url('edit_pr_asset_msp')}}" id="modal_pr_asset_edit" name="modal_pr_asset_edit">
            @csrf
           <input type="text" name="id_pam_edit" id="id_pam_edit" hidden>
           <!-- <div class="form-group">
            <label for="">Tanggal Serah Purchase Request</label>
            <input type="date" class="form-control" placeholder="" name="date_edit" id="date_edit" >
           </div> -->

          <div class="form-group">
            <label for="">To</label>
            <input type="text" name="to_agen_edit" id="to_agen_edit" class="form-control">
          </div>

          <div class="form-group">
             <label>Attention</label>
             <input type="attention" name="attention_edit" id="attention_edit" class="form-control" placeholder="Enter Attention">
          </div>

          <div class="form-group">
            <label for="">Subject</label>
            <input type="text" name="subject_edit" id="subject_edit" class="form-control" placeholder="Enter Subject" >
          </div>

          <div class="form-group">
            <label>Project</label>
            <input type="text" name="project_edit" id="project_edit" placeholder="Enter Project" class="form-control">
          </div>

          <div class="form-group">
            <label>Project ID</label>
            <input type="text" name="project_id_edit" id="project_id_edit" class="form-control" placeholder="Enter Project ID">
          </div>

          <div class="form-group">
            <label>Terms & Condition</label>
            <textarea type="text" name="term_edit" id="term_edit" class="form-control" placeholder="Enter Terms & Condition"></textarea>
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

<!--  <div class="modal fade" id="assign_to_hrd_pr_asset" role="dialog">
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
  </div> -->

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript">
  	  $('#datasmu').DataTable({
          "scrollX": true,
          "order": [[ 0, "desc" ]],
        });

      $('.money').mask('000,000,000,000,000', {reverse: true});

      function pam_edit(id_pam,to_agen,attention,subject,project,project_id,term){
      	$('#id_pam_edit').val(id_pam);  
        $('#to_agen_edit').val(to_agen)
        $('#attention_edit').val(attention); 
        $('#subject_edit').val(subject); 
        $('#project_edit').val(project); 
        $('#project_id_edit').val(project_id);
        $('#term_edit').val(term);
      }

      function pam_assign(id_pam,id_po_asset){
        $('#assign_to_fnc_edit').val(id_pam);
        $('#id_po_asset_msp_edit').val(id_po_asset);
      }

      // function assign_to_fnc(id_pam,amount){
      // 	$('#assign_to_fnc_edit').val(id_pam);
      // 	$('#amount').val(amount);
      // }

      function assign_to_adm(id_pam,amount){
      	$('#assign_to_adm_edit').val(id_pam);
      	$('#amount').val(amount);
      }

  	  function id_pam_set(id_pam,no_pr,subject){
  		  $('#id_pam_set').val(id_pam);
        $('#no_pr').val(no_pr);
        $('#subject').val(subject);
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

    var i=1;  
    $('#addMore').click(function(){  
         i++;  
         $('#mytable').append('<tr id="row'+i+'"><td><br><input type="text" name="msp_code[]" class="form-control" placeholder="Enter Id Barang" required></td><td><br><input type="text" class="form-control" placeholder="Enter Name" name="name_product[]" id="name" required></td><td><br> <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required></td><td><br><input type="text" class="form-control" placeholder="Enter Unit" name="unit[]" id="po" required></td><td style="margin-bottom: 50px"><br><div class="modalIcon inputIconBg" style="padding-left: 10px"><input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required><i class="" aria-hidden="true">Rp.</i></div></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" required></td><td><a href="javascript:void(0);" id="'+i+'"  class="remove"><span class="fa fa-times" style="font-size: 18px;color:red"></span></a></td></tr>');
         initMaskMoney();
    });

    function initMaskMoney() {
      	$('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});
  	}

    document.getElementById('yourBox').onchange = function() {
      document.getElementById('yourText').disabled = !this.checked;
    };     

    $('#project_id').select2();   
</script>
@endsection

