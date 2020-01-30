@extends('template.template')
@section('content')
<script src="https://ajax.googleapis.com/ajax/lins/jqeury/1.12.0/jqeury.min.js"></script>
<script src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
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
          <a href="#">Purchase Order Asset Managememt</a>
        </li>
      </ol>
      @if(session('success'))
      <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
      @elseif(session('update'))
      <div class="alert alert-warning" id="alert">{{ session('update') }}</div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Table PO Asset Management</b>
           @if(Auth::User()->id_position == 'ADMIN')
           <div class="pull-right">
           		<!-- <button class="btn btn-success-sales pull-right float-right margin-left-custom" id="" data-target="#modal_pr_asset" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp PR Asset</button> -->
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
                  <th hidden>ID PAM</th>
                  <th>No. Purchase Request</th>
                  <th>No. Purchase Order</th>
                  <th>Created Date</th>
                  <th>To</th><!-- 
                  <th>Nominal</th> -->
                  <th>From</th>
                  <th>Project ID</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
      		  <?php $no = 1; ?>

                  @if(Auth::User()->id_position == 'ADMIN')
                  @foreach($pam as $data)
                    <tr>
                      <td hidden>{{$data->id_po_asset}}</td>
                      <!-- <td>{{$no++}}</td> -->
                      <td>{{$data->no_pr}}</td>
                      <td>{{$data->no_po}}</td>
                      <td>{{$data->date}}</td>
                      <td>{{$data->to_agen}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->project_id}}</td>
                      @if($data->term != NULL)
                      <td>
                      <a href="{{action('POAssetMSPController@downloadPDF2',$data->id_po_asset)}}" target="_blank"><button class="btn btn-md btn-info" style="width: 100%" id="btnprint"><b><i class="fa fa-print"></i> Print to PDF </b></button></a>	
                      </td>
                      @elseif($data->term == NULL)
                      <td>
                      <button class="btn btn-md btn-info disabled" style="width: 100%"><b><i class="fa fa-print"></i> Print to PDF </b></button>	
                      </td>
                      @endif
                      <td>
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#update_po" data-toggle="modal" style="width: 70px;height: 30px;text-align: center;" onclick="update_po('{{$data->id_po_asset}}','{{$data->term}}')">&nbsp Update
                        </button>
                      </td>
                    </tr>
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

  <div class="modal fade" id="update_po" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Purchase Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_term_po_msp')}}" id="modalProgress" name="modalProgress">
            @csrf
          <div class="form-group">
            <input type="" id="id_pam" name="id_pam" class="form-control" hidden>
          </div>
          <!-- <div class="form-group newIcon inputIconBg">
            <label for="sow">Amount</label>
            <input name="amount" id="amount" class="form-control money" readonly></input>
            <i class="" aria-hidden="true">Rp.</i>
          </div> -->
          <div class="form-group">
            <label for="sow">Terms & Condition</label>
            <textarea name="term_edit" id="term_edit" class="form-control" required=""></textarea>
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

      function update_po(id_po_asset,term){
        $('#id_pam').val(id_po_asset);
        $('#term_edit').val(term);
      }      

      $("#alert").fadeTo(2000, 500).slideUp(500, function(){
       $("#alert").slideUp(300);
      });  

      $(document).ready(function(){
        $("#btnprint").printPage();
      });
</script>
@endsection

