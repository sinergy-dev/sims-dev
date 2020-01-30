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

<section class="content-header">
  <h1>
    Barang From DO-Supplier
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Gudang</li>
    <li class="active">DO-Sup</li>
  </ol>
</section>

<section class="content">

  @if(session('success'))
    <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
  @elseif(session('update'))
    <div class="alert alert-warning" id="alert">{{ session('update') }}</div>
  @endif
  
  <div class="box">
    <div class="box-header with-border">
      @if(Auth::User()->id_division == 'WAREHOUSE')
      <div class="pull-right">
        <a href="{{url('/add_gudang')}}"><button class="btn btn-success pull-right float-right margin-left-custom" id=""><i class="fa fa-plus"> </i>&nbsp Barang</button></a>
        <button type="button" class="btn btn-warning dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <b><i class="fa fa-download"></i> Export</b>
        </button>
        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
            <a class="dropdown-item" href="{{action('PAMController@downloadPDF')}}"> PDF </a>
            <a class="dropdown-item" href="{{action('PAMController@exportExcel')}}"> EXCEL </a>
        </div>  
      </div>
      @endif
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="datasmu" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No. Do Supplier</th>
              <th>Created Date</th>
              <th>To</th>
              <th>Subject</th>
              @if(Auth::User()->id_position == 'ADMIN')
              <th>Action</th>
              <th>Action</th>
              @endif
              <th>Action</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">

              @foreach($pam as $data)
                <tr>
                  <td>{{$data->no_do_sup}}</td>
                  <td>{{$data->date_handover}}</td>
                  <td>{{$data->to_agen}}</td>
                  <td>{{$data->subject}}</td>
                  @if($data->status_do_sup == 'NEW')
                  <td>
                    <button class="btn btn-sm btn-warning"  data-target="#modal_approve" data-toggle="modal" onclick="approve('{{$data->id_po_asset}}')"><i class="fa fa-check"></i>&nbspApprove</button> 
                    <a href="" target="_blank"><button class="btn btn-sm btn-info"><b><i class="fa fa-print"></i> Print PDF </b></button></a> 
                  </td>
                  @else
                  <td>
                    <a href="" target="_blank"><button class="btn btn-sm btn-info"><b><i class="fa fa-print"></i> Print PDF </b></button></a> 
                  </td>
                  @endif
                  <td>
                    @if($data->status_do_sup == 'FINANCE')
                    <label class="status-win">APPROVED</label>
                    @else
                    <label class="status-lose" style="width: 105px">NOT APPROVED</label>
                    @endif
                  </td>
           

                </tr>
              @endforeach


          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>

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
                  <table id="mytable" class="table">
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
                      <td>
                        <br>
                        <select class="form-control searchcode" id="msp_code" data-rowid="0" name="msp_code[]" required>
                          <option value="">-- Select --</option>
                          @foreach($msp_code as $data)
                              <option value="{{$data->kode_barang}}">{{$data->kode_barang}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td hidden>
                        
                      <input type="" name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="0" hidden>
                      </td>
                      <!-- <td style="margin-bottom: 50px;">
                        <br><input type="text" name="msp_code[]" class="form-control" placeholder="Enter Product Id" required>
                      </td> -->
                      <td style="margin-bottom: 50px">
                        <br><input type="text" class="form-control name_barangs" data-rowid="0" placeholder="Enter Name" name="name_product[]" id="name" required>
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
</div>

<!--Modal Approve-->
<div class="modal fade" id="modal_approve" role="dialog">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form method="POST" action="{{url('/approve_finance_do')}}">

        @csrf
        <div class="modal-body">
          <input type="text" name="id_po_asset" id="id_po_asset" hidden
          >
          <h3>Are you Sure to <span style="color:red">Approve!</span></h3>
          
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspCancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspYes</button>
        </div>
        </form>
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
      //  $('#assign_to_fnc_edit').val(id_pam);
      //  $('#amount').val(amount);
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

      function approve(id_po_asset){
        $('#id_po_asset').val(id_po_asset);
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

    function initsearchcode() {
      $(".searchcode").select2();
    }

    var i=1; 
    $('#addMore').click(function(){  
         i++;  
         $('#mytable').append('<tr id="row'+i+'"><td style="margin-bottom:50px;"><br><select class="form-control searchcode" id="msp_code" data-rowid="'+i+'" name="msp_code[]" required><option value="">-- Select --</option>@foreach($msp_code as $data)<option value="{{$data->kode_barang}}">{{$data->kode_barang}}</option>@endforeach</select></td> <td hidden><input type="" name="id_barangs[]" id="id_barangs" class="id_barangs" value="" data-rowid="'+i+'"></td><td><br><input type="text" class="form-control name_barangs" data-rowid="'+i+'" placeholder="Enter Name" name="name_product[]" id="name" required></td><td><br> <input type="number" class="form-control" placeholder="qty" name="qty[]" id="quantity" style="width: 70px;font-size: 14px" required></td><td><br><input type="text" class="form-control" placeholder="Enter Unit" name="unit[]" id="po" required></td><td style="margin-bottom: 50px"><br><div class="modalIcon inputIconBg" style="padding-left: 10px"><input type="text" class="form-control money" placeholder="Enter Amount" name="nominal[]" id="nominal[]" required><i class="" aria-hidden="true">Rp.</i></div></td><td><br><input type="text" class="form-control" placeholder="Enter Information" name="ket[]" id="information" required></td><td><a href="javascript:void(0);" id="'+i+'"  class="remove"><span class="fa fa-times" style="font-size: 18px;color:red;margin-top:25px"></span></a></td></tr>');
         initMaskMoney();
         initsearchcode();
    });

    function initMaskMoney() {
        $('input[id^="nominal"]').mask('000,000,000,000,000', {reverse: true});
    }
   

    $('#project_id').select2();   

    $(document).on('change',"select[id^='msp_code']",function(e) {
      var product = $('#msp_code').val();
      var rowid = $(this).attr("data-rowid");

         $.ajax({
          type:"GET",
          url:'/getIDbarang',
          data:{
            product:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
               $(".id_barangs[data-rowid='"+rowid+"']").val(value.id_product);
               $(".name_barangs[data-rowid='"+rowid+"']").val(value.nama);
            });

          }
        });
    });
</script>
@endsection

