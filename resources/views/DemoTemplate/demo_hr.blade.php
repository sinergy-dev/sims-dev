@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Daftar Buku Admin (HR)</a>
        </li>
      </ol>
    
      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number HR :<h4> {{$pops->no_letter}}</h4></div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Daftar Buku Admin (HR)</b>
           <div class="pull-right">
            <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 150px; height: 40px; color: white"><i class="fa fa-plus"> </i>&nbsp Penomoran HR</button>
            <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item disabled" href="{{url('/downloadPdfESM')}}"> PDF </a> -->
              <!-- <button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><a href="{{url('/downloadExcelPr')}}"> EXCEL </a></button> -->
            <!-- </div> -->
            <button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><a href="{{url('/downloadExcelAdminHR')}}"> EXCEL </a></button>
           </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered nowrap" id="data_Table" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Type of Letter</th>
                  <th>Division</th>
                  <th>PT</th>
                  <th>Month</th>
                  <th>Date</th>
                  <th>To</th>
                  <th>Attention</th>
                  <th>Title</th>
                  <th>Project</th>
                  <th>Description</th>
                  <th>From</th>
                  <th>Division</th>
                  <th>Project ID</th>
                  <th>Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                @foreach($datas as $data)
                <tr>
                  <td>{{$data->no_letter}}</td>
                  <td>{{$data->type_of_letter}}</td>
                  <td>{{$data->divsion}}</td>
                  <td>{{$data->pt}}</td>
                  <td>{{$data->month}}</td>
                  <td>{{$data->date}}</td>
                  <td>{{$data->to}}</td>
                  <td>{{$data->attention}}</td>
                  <td>{{$data->title}}</td>
                  <td>{{$data->project}}</td>
                  <td>{{$data->description}}</td>
                  <td>{{$data->name}}</td>
                  <td>{{$data->division}}</td>
                  <td>{{$data->project_id}}</td>
                  <td>{{$data->note}}</td>
                  <td>
                    @if(Auth::User()->nik == $data->from)
                    <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width:50px; height:20px;vertical-align: top;" onclick="edit_hr_number('{{$data->no}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->from}}','{{$data->project_id}}', '{{$data->note}}')">&nbsp Edit
                    </button>
                    @else
                    <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" data-target="#modaledit" data-toggle="modal" style="width:50px; height:20px;vertical-align: top;" onclick="edit_hr_number('{{$data->no}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->from}}','{{$data->project_id}}', '{{$data->note}}')">&nbsp Edit
                    </button>
                    @endif
                    <!-- <a href="{{url('/delete_admin_hr', $data->no)}}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 60px;height: 20px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')"> &nbsp Delete
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
          <h4 class="modal-title">Add Number HR</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_admin_hr')}}" id="modal_pr" name="modal_pr">
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
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="PKWT">PKWT</option>
                <option value="SK">SK</option>
                <option value="SP">SP</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">PT</label>
            <select type="text" class="form-control" placeholder="Select PT" name="pt" id="pt" required>
                <option>SIP</option>
                <option>MSP</option>
            </select>
          </div>
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
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>TEC</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FIN</option>
                <option>HRD</option>
                <option>TAM</option>
            </select>
          </div>
          <!-- <div class="form-group">
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
          <h4 class="modal-title">Edit Purchase Request</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_admin_hr')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" placeholder="Enter No PR" name="edit_no_letter" id="edit_no_letter" hidden>
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
    function edit_hr_number(no,to,attention,title,project,description,from,project_id,note) {
      $('#edit_no_letter').val(no);
      $('#edit_to').val(to);
      $('#edit_attention').val(attention);
      $('#edit_title').val(title);
      $('#edit_project').val(project);
      $('#edit_description').val(description);
      $('#edit_from').val(from);
      $('#edit_project_id').val(project_id);
      $('#edit_note').val(note);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_Table').DataTable( {
      "scrollX": true,
      "order": [[ 0, "desc" ]],
      fixedColumns:   {
        leftColumns: 2
      },
    });

    $(".dismisbar").click(function(){
         $(".notification-bar").slideUp(300);
        }); 
  </script>
@endsection