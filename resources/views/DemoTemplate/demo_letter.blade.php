@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Daftar Buku Admin (Letter)</a>
        </li>
      </ol>
    
      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Letter Number :<h4> {{$pops->no_letter}}</h4></div>
      @endif

      @if (session('sukses'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('sukses') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Letter Number :<h4> {{$pops2->no_letter}}</h4></div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i>&nbsp<b>Daftar Buku Admin (Letter)</b>
          <div class="pull-right">
            <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 100px; height: 40px; color: white"><i class="fa fa-plus"> </i>&nbsp Letter</button>
            @if($counts)
            <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; height: 40px; color: white; margin-right: 10px;"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
            @else
            <button type="button" class="btn btn-success margin-bottom pull-right disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; height: 40px; color: white; margin-right: 10px;" disabled><i class="fa fa-plus"> </i>&nbsp Back Date</button>
            @endif
            <button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><a href="{{url('/downloadExcelLetter')}}"> EXCEL </a></button>
          </div>
        </div>
        <div class="card-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="sip-tab" aria-selected="true">ALL</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="backdate-tab" data-toggle="tab" href="#backdate" role="tab" aria-controls="msp-tab" aria-selected="false">BackDate</a>
              </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active margin-top" id="all" role="tabpanel" aria-lebelledby="all-tab">
              <div class="table-responsive">
                <table class="table table-bordered nowrap" id="data_all" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No Letter</th>
                      <th>Position</th>
                      <th>Type</th>
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
                      <td>{{$data->position}}</td>
                      <td>{{$data->type_of_letter}}</td>
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
                        @if(Auth::User()->nik == $data->nik)
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width:50px; height:20px;vertical-align: top;" onclick="edit_po('{{$data->no}}','{{$data->type_of_letter}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->project_id}}', '{{$data->note}}')">&nbsp Edit
                        </button>
                        @else
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" data-target="#modaledit" data-toggle="modal" style="width:50px; height:20px;vertical-align: top;" onclick="edit_po('{{$data->no}}','{{$data->type_of_letter}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->project_id}}', '{{$data->note}}')">&nbsp Edit
                        </button>
                        @endif
                        <!-- <a href="{{url('/delete_letter', $data->no)}}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button></a> -->
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade margin-top" id="backdate" role="tabpanel" aria-lebelledby="backdate-tab">
              <div class="table-responsive">
                <table class="table table-bordered nowrap DataTable" id="data_backdate"  cellspacing="0">
                  <thead>
                    <tr>
                      <th>No Letter</th>
                      <th>Position</th>
                      <th>Type</th>
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
                    @if($data->status == 'F')
                   <tr>
                      <td>{{$data->no_letter}}</td>
                      <td>{{$data->position}}</td>
                      <td>{{$data->type_of_letter}}</td>
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
                        @if(Auth::User()->nik == $data->nik)
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="edit_po('{{$data->no}}','{{$data->type_of_letter}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->project_id}}','{{$data->note}}')">&nbsp Edit
                        </button>
                        @else
                        <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" data-target="#modaledit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="edit_po('{{$data->no}}','{{$data->type_of_letter}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->project_id}}','{{$data->note}}')">&nbsp Edit
                        </button>
                        @endif
                        <!-- <a href="{{url('/delete_letter', $data->no)}}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button></a> -->
                      </td>
                    </tr>
                    @endif
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
  </div>
</div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Letter</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_letter')}}" id="modal_pr" name="modal_pr">
            @csrf
          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option>PMO</option>
                <option>TEC</option>
                <option>MSM</option>
                <option>DIR</option>
                <option>TAM</option>
                <option>HRD</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="LTR">LTR (Surat Umum)</option>
                <option value="PKS">PKS (Perjanjian Kerja Sama)</option>
                <option value="BST">BST (Berita Acara Serah Terima)</option>
                <option value="ADM">ADM (Surat Administrasi & Backdate)</option>
                <option value="SGB">SGB (Surat Garansi Bank)</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Type of Letter</label>
            <input type="text" class="form-control" placeholder="Enter Type of Letter" name="type" id="type" required>
          </div> -->
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="to" id="to" required>
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
          <!-- <div class="form-group">
            <label for="">From</label>
            <input type="text" class="form-control" id="from" name="from" placeholder="Enter From" required>
          </div> -->
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>HRD</option>
                <option>TEC</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
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
<!-- BACKDATE -->
<div class="modal fade" id="letter_backdate" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Letter (Backdate)</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_letterbackdate')}}" id="letter_backdate" name="letter_backdate">
            @csrf
          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option>PMO</option>
                <option>TEC</option>
                <option>MSM</option>
                <option>DIR</option>
                <option>TAM</option>
                <option>HR</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="LTR">LTR (Surat Umum)</option>
                <option value="PKS">PKS (Perjanjian Kerja Sama)</option>
                <option value="BST">BST (Berita Acara Serah Terima)</option>
                <option value="ADM">ADM (Surat Administrasi & Backdate)</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Type of Letter</label>
            <input type="text" class="form-control" placeholder="Enter Type of Letter" name="type" id="type" required>
          </div> -->
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="to" id="to" required>
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
          <!-- <div class="form-group">
            <label for="">From</label>
            <input type="text" class="form-control" id="from" name="from" placeholder="Enter From" required>
          </div> -->
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>HRD</option>
                <option>PRE</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
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
          <h4 class="modal-title">Edit Letter</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_letter')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" placeholder="Enter No Letter" name="edit_no_letter" id="edit_no_letter" hidden> 
          <!-- <div class="form-group">
            <label for="">Type Of Letter</label>
            <input type="text" class="form-control" placeholder="Enter Type of letter" name="edit_type" id="edit_type">
          </div> -->
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to">
          </div>
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="edit_attention" id="edit_attention">
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="edit_title" id="edit_title">
          </div> 
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="edit_project" id="edit_project">
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea type="text" class="form-control" placeholder="Enter Description" name="edit_description" id="edit_description"> </textarea>
          </div>
          <!-- <div class="form-group">
            <label for="">From</label>
            <input type="text" class="form-control" placeholder="Enter From" name="edit_from" id="edit_from">
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
    function edit_po(no,type_of_letter,to,attention,title,project,description,project_id,note) {
      $('#edit_no_letter').val(no);
      $('#edit_type').val(type_of_letter);
      $('#edit_to').val(to);
      $('#edit_attention').val(attention);
      $('#edit_title').val(title);
      $('#edit_project').val(project);
      $('#edit_description').val(description);
      $('#edit_project_id').val(project_id);
      $('#edit_note').val(note);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_all').DataTable({
      "retrieve": true,
      "order": [[ 0, "desc" ]],
      "scrollX": true,
      fixedColumns:   {
            leftColumns: 1
      },
    });

    $('#data_backdate').DataTable({
      "retrieve": true,
      "autowidth": true,
      "responsive": true,
      "order": [[ 0, "desc" ]],
      fixedColumns:   {
            leftColumns: 1
      },
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 
  </script>
@endsection