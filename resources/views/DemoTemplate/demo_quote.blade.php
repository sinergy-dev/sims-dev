@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Quote Number</a>
        </li>
      </ol>
    
      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops->quote_number}}</h4></div>
      @endif

      @if (session('sukses'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('sukses') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops2->quote_number}}</h4></div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i> <b>Quote Table</b>
           <div class="pull-right">
              @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL'  || Auth::User()->id_position == 'STAFF GA')
              <button type="button" class="btn btn-success-sales pull-right" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd Quote</button>
              @endif
              @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'STAFF GA')
              @if($counts)
              <button type="button" class="btn btn-success-sales pull-right" id="" data-target="#letter_backdate" data-toggle="modal" style="margin-right: 10px;"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
              @else
              <button type="button" class="btn btn-success-sales pull-right disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="margin-right: 10px;"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
              @endif
              @endif
              <!-- <button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><a href="{{url('/downloadExcelQuote')}}"> EXCEL </a></button> -->
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
                      <th>Quote Number</th>
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
                      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                        <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($datas as $data)
                    <tr>
                      <td>{{ $data->quote_number }}</td>
                      <td>{{ $data->position }}</td>
                      <td>{{ $data->type_of_letter }}</td>
                      <td>{{ $data->month }}</td>
                      <td>{{ $data->date }}</td>
                      <td>{{ $data->to }}</td>
                      <td>{{ $data->attention }}</td>
                      <td>{{ $data->title }}</td>
                      <td>{{ $data->project }}</td>
                      <td>{{ $data->description }}</td>
                      <td>{{ $data->name }}</td>
                      <td>{{ $data->division }}</td>
                      <td>{{ $data->project_id }}</td>
                      <td>{{ $data->note }}</td>
                      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                        <td>
                          <!-- <button class="btn btn-sm btn-primary fa fa-search fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">
                          </button> -->
                          @if(Auth::User()->nik == $data->nik)
                          <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">&nbsp Edit
                          </button>
                          @else
                          <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" data-target="#modalEdit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">&nbsp Edit
                          </button>
                          @endif

                          <!--  <a href="{{ url('delete?id_quote='. $data->id_quote) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                          </button></a> -->
                        </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade margin-top" id="backdate" role="tabpanel" aria-lebelledby="backdate-tab">
              <div class="table-responsive">
                <table class="table table-bordered nowrap" id="data_backdate" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Quote Number</th>
                      <th>Position</th>
                      <th>Type</th>
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
                      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                        <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                    @foreach($datas as $data)
                    @if($data->status_backdate == 'F')
                    <tr>
                      <td>{{ $data->quote_number }}</td>
                      <td>{{ $data->position }}</td>
                      <td>{{ $data->type_of_letter }}</td>
                      <td>{{ $data->date }}</td>
                      <td>{{ $data->to }}</td>
                      <td>{{ $data->attention }}</td>
                      <td>{{ $data->title }}</td>
                      <td>{{ $data->project }}</td>
                      <td>{{$data->description}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->division}}</td>
                      <td>{{$data->project_id}}</td>
                      <td>{{$data->note}}</td>
                      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
                        <td>
                          <!-- <button class="btn btn-sm btn-primary fa fa-search fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">
                          </button> -->
                          @if(Auth::User()->nik == $data->nik)
                          <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">&nbsp Edit
                          </button>
                          @else
                          <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" data-target="#modalEdit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="quote('{{$data->quote_number}}','{{$data->position}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}')">&nbsp Edit
                          </button>
                          @endif

                          <!--  <a href="{{ url('delete?id_quote='. $data->id_quote) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                          </button></a> -->
                        </td>
                      @endif
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>

    <!--MODAL ADD-->  
<div class="modal fade" id="modalAdd" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Add Quote</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/quote/store')}}" id="modalAddQuote" name="modalAddQuote">
              @csrf
            <!-- <div class="form-group">
              <label for="lead_id">Lead Id</label>
              <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder="Lead Id" readonly required>
            </div> -->
              
             <!--  <div class="form-group">
                  <label>Quote Number</label>
                  <input class="form-control" id="quote_number" name="quote_number" required>
              </div> -->
              
              <div class="form-group">
                  <label>Position</label>
                  <select class="form-control" id="position" name="position" required>
                      <option value="AM">AM</option>
                      <option value="DIR">DIR</option>
                      <option value="MSM">MSM</option>
                  </select>
              </div>

              <!-- <div class="form-group">
                  <label>Type of letter</label>
                  <input class="form-control" id="type_of_letter" name="type_of_letter" required>
              </div> -->
              <div class="form-group">
                  <label>Date</label>
                  <input type="date" class="form-control" id="date" name="date" required>
              </div>

              <div class="form-group">
                  <label>To</label>
                  <input class="form-control" placeholder="Enter To" id="to" name="to" required>
              </div>

              <div class="form-group">
                  <label>Attention</label>
                  <input class="form-control" placeholder="Enter Attention" id="attention" name="attention" >
              </div>

              <div class="form-group">
                  <label>Title</label>
                  <input class="form-control" placeholder="Enter Title" id="title" name="title" >
              </div>

              <div class="form-group">
                  <label>Project</label>
                  <input class="form-control" placeholder="Enter Project" id="project" name="project" >
              </div>         
              <div class="form-group">
                  <label for="">Description</label>
                  <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
              </div>
              <!-- <div class="form-group">
                  <label for="">From</label>
                  <input type="text" class="form-control" id="from" name="from" placeholder="Enter From">
              </div> -->
              <div class="form-group">
                <label for="">Division</label>
                <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                    <option>PMO</option>
                    <option>MSM</option>
                    <option>Marketing</option>
                    <option>PRE/Impl/Tech</option>
                </select>
              </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
          </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
               <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
                <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
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
          <h4 class="modal-title">Add Quote (Backdate)</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_quotebackdate')}}" id="quote_backdate" name="quote_backdate">
            @csrf
          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option value="AM">TAM</option>
                <option value="DIR">DIR</option>
                <option value="MSM">MSM</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="LTR">LTR (Surat Umum)</option>
                <option value="PKS">PKS (Perjanjian Kerja Sama)</option>
                <option value="BST">BST (Berita Acara Serah Terima)</option>
                <option value="ADM">ADM (Surat Administrasi & Backdate)</option>
            </select>
          </div>
          <div class="form-group">
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
          <!-- <div class="form-group">
            <label for="lead_id">Lead Id</label>
            <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder="Lead Id" readonly required>
          </div> -->
            <div class="form-group" hidden>
                <label>Quote Number</label>
                <input class="form-control" id="edit_quote_number" name="quote_number">
            </div>
            
            <!-- <div class="form-group">
                <label>Position</label>
                <select class="form-control" id="edit_position" name="position" required>
                    <option value="AM">AM</option>
                    <option value="DIR">DIR</option>
                </select>
            </div>

            <div class="form-group">
                <label>Type of letter</label>
                <input class="form-control" id="edit_type_of_letter" name="type_of_letter" required>
            </div>
            
              
            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" id="edit_date" name="date" required>
            </div> -->
            <div class="form-group">
                <label>To</label>
                <input class="form-control" id="edit_to" placeholder="Enter To" name="edit_to" >
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
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
              <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspUpdate</button> -->
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
  <script type="text/javascript">
    function quote(quote_number,position,to,attention,title,project,description, project_id,note) {
      $('#edit_quote_number').val(quote_number);
      $('#edit_position').val(position);
      $('#edit_to').val(to);
      $('#edit_attention').val(attention);
      $('#edit_title').val(title);
      $('#edit_project').val(project);
      $('#edit_description').val(description);
      $('#edit_project_id').val(project_id);
      $('#edit_note').val(note);
    }

    $('#data_all').DataTable( {
        "retrive" : true,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        fixedColumns:   {
            leftColumns: 1
        },
    });

    $('#data_backdate').DataTable({
      "retrive" : true,
      "autowidth": true,
      "responsive": true,
      "order": [[ 0, "desc" ]],
      fixedColumns:   {
            leftColumns: 1
        },
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#alert").slideUp(300);
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

  </script>
@endsection