@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Purchase Request</a>
        </li>
      </ol>
    
      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
    <div class="alert alert-primary" id="alert">
        {{ session('success') }}
    </div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Delivery Order</b>
           <div class="pull-right">
            <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 200px; height: 40px; color: white"><i class="fa fa-plus"> </i>&nbsp Number Delivery Order</button>
            <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item disabled" href="{{url('/downloadPdfESM')}}"> PDF </a> -->
              <!-- <button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><a href="{{url('/downloadExcelPr')}}"> EXCEL </a></button> -->
            <!-- </div> -->
           </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="data_Table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Type of Letter</th>
                  <th>Month</th>
                  <th>Date</th>
                  <th>To</th>
                  <th>Attention</th>
                  <th>Title</th>
                  <th>Project</th>
                  <th>Description</th>
                  <th>Project ID</th>
                  <th>Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
              	@foreach($datas as $data)
                <tr>
                  <td>{{$data->no_do}}</td>
                  <td>{{$data->type_of_letter}}</td>
                  <td>{{$data->month}}</td>
                  <td>{{$data->date}}</td>
                  <td>{{$data->to}}</td>
                  <td>{{$data->attention}}</td>
                  <td>{{$data->title}}</td>
                  <td>{{$data->project}}</td>
                  <td>{{$data->description}}</td>
                  <td>{{$data->project_id}}</td>
                  <td>{{$data->note}}</td>
                  <td>
                    <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="edit_do('{{$data->no}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->project_id}}', '{{$data->note}}')">
                    </button>
                    <!-- <a href="{{url('/delete_pr', $data->no)}}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                    </button></a> -->
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
  </div>
  
</div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Number Delivery Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_do')}}" id="modal_pr" name="modal_pr">
            @csrf
          <!-- <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option>PMO</option>
                <option>PRE</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FA</option>
                <option>HR</option>
            </select>
          </div> -->
          <!-- <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="IPR">IPR (Internal Purchase Request)</option>
                <option value="EPR">EPR (Eksternal Purchase Request)</option>
            </select>
          </div> -->
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="To" name="to" id="to" required>
          </div> 
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention" >
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" >
          </div>
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project" >
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
          </div>
          <!-- <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>PRE/Tech/Impl</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FA</option>
                <option>HR</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Issuance</label>
            <input type="text" class="form-control" placeholder="Enter Issuance" name="issuance" id="issuance">
          </div> -->
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Project ID" name="project_id" id="project_id">
          </div>
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
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Delivery Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_do')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" placeholder="Enter No PR" name="edit_no_do" id="edit_no_do" hidden>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to" >
          </div>
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="edit_attention" id="edit_attention" >
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="edit_title" id="edit_title" >
          </div> 
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="edit_project" id="edit_project" >
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea type="text" class="form-control" placeholder="Enter Description" name="edit_description" id="edit_description" > </textarea>
          </div>
          <!-- <div class="form-group">
            <label for="">Issuance</label>
            <input type="text" class="form-control" placeholder="Enter Issuance" name="edit_issuance" id="edit_issuance">
          </div> -->
          <!-- <div class="form-group">
            <label>Date</label>
            <input type="date" class="form-control" name="edit_date" id="edit_date">
          </div> -->
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="edit_project_id" id="edit_project_id">
          </div>
          <div class="form-group">
            <label for="">Note</label>
            <input type="text" class="form-control" placeholder="Enter Note" name="edit_note" id="edit_note">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

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

    .btnPR{
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
      width: 170px;
      padding-top: 4px;
      padding-left: 10px;
    }
</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function edit_do(no,to,title,attention,project,description,project_id,note) {
      $('#edit_no_do').val(no);
      $('#edit_to').val(to);
      $('#edit_attention').val(attention);
      $('#edit_title').val(title);
      $('#edit_project').val(project);
      $('#edit_description').val(description);
      // $('#edit_from').val(from);
      // $('#edit_issuance').val(issuance);
      $('#edit_project_id').val(project_id);
      $('#edit_note').val(note);

    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_Table').DataTable( {
        "scrollX": true
        } );
  </script>
@endsection