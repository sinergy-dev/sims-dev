@extends('template.template_admin-lte')
@section('content')
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

<section class="content-header">
  <h1>
    Add PR Asset MSP
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">PR Asset</li>
    <li class="active">MSP</li>
    <li class="active">Add</li>
  </ol>
</section>

<section class="content">
  

  <div class="box">
    <div class="box-header">
      
    </div>

    <div class="box-body">
      <form method="POST" action="{{url('store_pr_asset')}}" id="modal_pr_asset" name="modal_pr_asset" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="">Created Date</label>
              <input type="date" class="form-control" placeholder="" name="date_supplier" id="today" readonly >
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
                <input type="radio" name="type_of_letter" value="IPR" id="internal_button" onclick="javascript:yesnoCheck();">
                <span class="checkmark">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspInternal</span>
              </label>
              <label class="radios">
                <input type="radio" name="type_of_letter" value="EPR" id="eksternal_button" onclick="javascript:yesnoCheck();">
                <span class="checkmark">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEksternal</span>
              </label>
            </div>
          </div>

          <!-- PR Internal -->

          <div id="internal" class="col-md-12" style="display: none;">
            <div class="col-sm-7">
              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">To</label>
                <div class="col-sm-10">
                  <input class="form-control" name="to_agen_supp_intern" id="to_agen" type="text" placeholder="Enter To" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address_supp_intern" id="add"type="text" placeholder="Enter Address"></textarea>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Fax</label>
                <div class="col-sm-10">
                  <input type="number" name="fax_supp_intern" id="fax" class="form-control" placeholder="Enter Fax.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Telp</label>
                <div class="col-sm-10">
                  <input type="number" name="telp_supp_intern" id="telp" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="text" name="email_supp_intern" id="email" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Attn.</label>
                <div class="col-sm-10">
                  <input type="text" name="attention_supp_intern" id="att" class="form-control" placeholder="Enter Attention">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Subj.</label>
                <div class="col-sm-10">
                  <textarea type="text" name="subject_supp_intern" id="subj" class="form-control" placeholder="Enter Subject" required></textarea>
                </div>
              </div> 
            </div>
            
            <div class="col-sm-5">

              <div class="form-group row">
                <label class="col-sm-4 control-label">From</label>
                <div class="col-sm-8">
                  <select class="form-control" id="owner_pr" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr_supp_intern" >
                  <option value="">-- Select From --</option>
                  @foreach($owner as $data)
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4 control-label">Project</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Enter Project" name="project_supp_intern" id="project">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4 control-label">ID Project</label>
                <div class="col-sm-8">
                  <select class="form-control" id="project_id" name="project_id_supp_intern" required style="width: 100%;">
                    <option value="">-- Select Project ID --</option>
                      @foreach($project_id as $data)
                          <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4 control-label">Terms & Condition</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="term_supp_intern" id="term" placeholder="Enter Terms & Condition"></textarea>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-4">PPn (Harus Diisi)</label>
                <label class="radios margin-top margin-left">
                  <input type="radio" name="ppn_internal" value="YA" style="width: 15px; height: 15px;">
                  <span class="checkmark">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspYa</span>
                </label>
                <label class="radios margin-top">
                  <input type="radio" name="ppn_internal" value="TIDAK" style="width: 15px; height: 15px; ">
                  <span class="checkmark">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTidak</span>
                </label>
              </div>

            </div>
          </div>

          <!-- PR Eksternal -->

          <div id="eksternal" class="col-md-12" style="display: none;">
            <div class="col-sm-6">
              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">To(Supplier)</label>
                <div class="col-sm-10">
                  <input class="form-control" name="to_agen_supplier" id="to_agen" type="text" placeholder="Enter To" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Address(Supplier)</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address_supplier" id="add"type="text" placeholder="Enter Address"></textarea>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Fax(Supplier)</label>
                <div class="col-sm-10">
                  <input type="number" name="fax" id="fax_supplier" class="form-control" placeholder="Enter Fax.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Telp(Supplier)</label>
                <div class="col-sm-10">
                  <input type="number" name="telp_supplier" id="telp" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Email(Supplier)</label>
                <div class="col-sm-10">
                  <input type="text" name="email_supplier" id="email" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Attn.(Supplier)</label>
                <div class="col-sm-10">
                  <input type="text" name="attention_supplier" id="att" class="form-control" placeholder="Enter Attention">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label">From</label>
                <div class="col-sm-10">
                  <select class="form-control" id="owner_pr_supp" style="width: 100%;" onkeyup="copytextbox();" name="owner_pr_supplier" >
                  <option value="">-- Select From --</option>
                  @foreach($owner as $data)
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Subj.(Supplier)</label>
                <div class="col-sm-10">
                  <textarea type="text" name="subject_supplier" id="subj" class="form-control" placeholder="Enter Subject" required></textarea>
                </div>
              </div> 

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Project</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Enter Project" name="project_supplier" id="project">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">ID Project</label>
                <div class="col-sm-10">
                  <select class="form-control" id="project_id_supplier" name="project_id_supplier" style="width: 100%" required>
                    <option value="">-- Select Project ID --</option>
                      @foreach($project_id as $data)
                          <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Terms & Condition</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="term_supplier" id="term" placeholder="Enter Terms & Condition"></textarea>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">PPn (Harus Diisi)</label>
                <label class="radios">
                  <input type="radio" name="ppn" value="YA" style="width: 15px; height: 15px;">Ya
                  <span class="checkmark"></span>
                </label>
                <label class="col-sm-2 control-label radios">
                  <input type="radio" name="ppn" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">To(Customer)</label>
                <div class="col-sm-10">
                  <input class="form-control" name="to_agen_customer" id="to_agen" type="text" placeholder="Enter To" required>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Address(Customer)</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address_customer" id="add"type="text" placeholder="Enter Address"></textarea>
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Fax(Customer)</label>
                <div class="col-sm-10">
                  <input type="number" name="fax" id="fax_customer" class="form-control" placeholder="Enter Fax.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Telp(Customer)</label>
                <div class="col-sm-10">
                  <input type="number" name="telp_customer" id="telp" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Email(Customer)</label>
                <div class="col-sm-10">
                  <input type="text" name="email_customer" id="email" class="form-control" placeholder="Enter Telp.">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">Attn.(Customer)</label>
                <div class="col-sm-10">
                  <input type="text" name="attention_customer" id="att" class="form-control" placeholder="Enter Attention">
                </div>
              </div>

              <div class="form-group row" style="margin-left: -12px">
                <label class="col-sm-2 control-label">PPn (Harus Diisi)</label>
                <label class="radios">
                  <input type="radio" name="ppn_customer" value="YA" style="width: 15px; height: 15px;">Ya
                  <span class="checkmark"></span>
                </label>
                <label class="col-sm-2 control-label radios">
                  <input type="radio" name="ppn_customer" value="TIDAK" style="width: 15px; height: 15px; ">Tidak
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12"  id="btn_submit" style="display: none">
          <br>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
        </div>
      </form>
    </div>
  </div>
</section>

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

</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
  	function showMe(e) {
	// i am spammy!
	  alert(e.value);
	}

    function edit_product(id_transaction,no_do){
        $('#id_transaction_edit').val(id_transaction);
        $('#no_do_edit').val(no_do);
    }

    function yesnoCheck() {
        if (document.getElementById('internal_button').checked) {
        document.getElementById('internal').style.display = 'block'; 
        document.getElementById('eksternal').style.display = 'none';  
        document.getElementById('btn_submit').style.display = 'block'; 
        }
        else {
        document.getElementById('internal').style.display = 'none';
        document.getElementById('eksternal').style.display = 'block'; 
        document.getElementById('btn_submit').style.display = 'block'; 
        }
    }

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

    $('#project_id_supplier').select2();

    $('#owner_pr_supp').select2();

    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;
  </script>
@endsection