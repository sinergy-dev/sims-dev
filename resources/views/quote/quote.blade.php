@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
    .DTFC_LeftBodyLiner {
      overflow: hidden;
  }
  </style>

  <section class="content-header">
    <h1>
      Daftar Buku Admin (Quote Number)
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">Quote Number</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
        @endif

        @if (session('success'))
          <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops->quote_number}}</h4></div>
        @endif

        @if (session('alert'))
      <div class="alert alert-success" id="alert">
          {{ session('alert') }}
      </div>
    @endif

    <div class="box">
      <div class="box-header with-border">
          <div class="pull-left">
            <label style="margin-top: 5px;margin-right: 5px">Filter Year</label>
            <select style="margin-right: 5px;width: 100px" class="form-control fa" id="year_filter">
                <option value="2020">&#xf073 &nbsp2020</option>
                <option value="2019">&#xf073 &nbsp2019</option>
            </select>
          </div>
          <div class="pull-right">
            @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL'  || Auth::User()->id_position == 'STAFF GA' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'MSM' || Auth::User()->id_position == 'OPERATION DIRECTOR' && Auth::User()->id_division == 'PMO')
            <button type="button" class="btn btn-success pull-right" style="width: 100px" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd Quote</button>
            @endif
            @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'STAFF GA' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'MSM'  || Auth::User()->id_position == 'OPERATION DIRECTOR' && Auth::User()->id_division == 'PMO')
            @if($counts)
            <button type="button" class="btn btn-success pull-right" id="" data-target="#letter_backdate" data-toggle="modal" style="margin-right: 10px;width: 100px"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
            @else
            <button type="button" class="btn btn-success pull-right disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="margin-right: 10px;width: 100px"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
            @endif
            @endif
          </div>
      </div>
      <div class="box-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs" id="myTab">
            @foreach($status_quote as $data)
                @if($data->status_backdate == 'A')
                    <li class="nav-item active">
                        <a class="nav-link active" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')">All</a>
                @else
                    <li class="nav-item">
                        <a class="nav-link" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status_backdate }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status_backdate}}')"> Backdate
                @endif
                        </a>
                    </li>
            @endforeach
          </ul>

          <div class="tab-content">

            <div class="tab-pane active" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-bordered nowrap table-striped dataTable data" id="data_all" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Quote Number</th>
                      <th>Position</th>
                      <th>Type of Letter</th>
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
                      <th>Project Type</th>
                      <th>Note</th>
                      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'OPERATION DIRECTOR' && Auth::User()->id_division == 'PMO')
                        <th>Action</th>
                      @endif
                    </tr>
                  </thead>
                </table>
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
                  
                  <div class="form-group">
                      <label>Position</label>
                      <select class="form-control" id="position" name="position" required>
                          <option value="TAM">TAM</option>
                          <option value="DIR">DIR</option>
                          <option value="MSM">MSM</option>
                      </select>
                  </div>
                  <div class="form-group">
                      <label>Date</label>
                      <input type="date" class="form-control" id="date" name="date" required>
                  </div>

                  <div class="form-group">
                    <label>Customer</label>
                    <select class="form-control" id="customer_quote" name="customer_quote" required style="width: 100%">
                      @foreach($customer as $data)
                      <option value="{{$data->id_customer}}">{{$data->customer_legal_name}}</option>
                      @endforeach
                    </select>
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
                  <div class="form-group">
                    <label>Project Type</label>
                    <select class="form-control" id="project_type" name="project_type" required style="width: 100%">
                      <option value="">--Choose Project Type--</option>
                      <option value="Supply Only">Supply Only</option>
                      <option value="Maintenance">Maintenance</option>
                      <option value="Implementation">Implementation</option>
                    </select>
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
              <h4 class="modal-title">Add Quote (Backdate)</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('/store_quotebackdate')}}" id="quote_backdate" name="quote_backdate">
                @csrf

              <div class="form-group">
                <label>Backdate Number</label>
                <select type="text" class="form-control" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" required>
                  @foreach($backdate_num as $data)
                  <option value="{{$data->quote_number}}">{{$data->quote_number}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="">Position</label>
                <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                    <option value="TAM">TAM</option>
                    <option value="DIR">DIR</option>
                    <option value="MSM">MSM</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Date</label>
                <input type="date" class="form-control" name="date" id="date" required>
              </div>
              <!-- <div class="form-group">
                <label for="">To</label>
                <input type="text" class="form-control" placeholder="Enter To" name="to" id="to" required>
              </div>  -->
              <div class="form-group">
                <label>Customer</label>
                <select class="form-control" id="customer_quote_backdate" name="customer_quote_backdate" required style="width: 100%">
                  @foreach($customer as $data)
                  <option value="{{$data->id_customer}}">{{$data->customer_legal_name}}</option>
                  @endforeach
                </select>
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
              <div class="form-group">
                <label>Project Type</label>
                <select class="form-control" id="project_type" name="project_type" required style="width: 100%">
                  <option value="">--Choose Project Type--</option>
                  <option value="Supply Only">Supply Only</option>
                  <option value="Maintenance">Maintenance</option>
                  <option value="Implementation">Implementation</option>
                </select>
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
              <div class="form-group" hidden>
                  <label>Quote Number</label>
                  <input class="form-control" id="edit_quote_number" name="quote_number">
              </div>
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
                <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
                <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspUpdate</button> -->
              </div>
          </form>
          </div>
        </div>
      </div>
    </div>
    
</section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script type="text/javascript">
    $('#customer_quote').select2();
    $("#backdate_num").select2();
    $("#customer_quote_backdate").select2();
    function edit_quote(quote_number,customer_legal_name,attention,title,project,description, project_id,note) {
      $('#modalEdit').modal('show');
      $('#edit_quote_number').val(quote_number);
      if (customer_legal_name == "null") {
        ''
      } else {
        $('#edit_to').val(customer_legal_name);
      }
      if (attention == "null") {
        '';
      } else {
        $('#edit_attention').val(attention);
      }

      if (title == "null") {
        '';
      } else {
        $('#edit_title').val(title);
      }

      if (project == "null") {
        '';
      } else {
        $('#edit_project').val(project);
      }

      if (description == "null") {
        '';
      } else {
        $('#edit_description').val(description);
      }

      if (project_id == "null") {
        '';
      } else {
        $('#edit_project_id').val(project_id);
      }

      if (note == "null") {
        '';
      } else {
        $('#edit_note').val(note);
      }
    }

    initQuoTable();

    function initQuoTable() {
      $("#data_all").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdataquote')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.nik) {
                var x = '"' + data.quote_number + '","' + data.customer_legal_name + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.project_id+ '","' +data.note+ '"'
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_quote(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "quote_number" },
          { "data": "position" },
          { "data": "type_of_letter" },
          { "data": "month" },
          { "data": "date" },
          { "data": "customer_legal_name" },
          { "data": "attention"},
          { "data": "title" },
          { "data": "project" },
          { "data": "description" },
          { "data": "name" },
          { "data": "division" },
          { "data": "project_id" },
          { "data": "project_type" },
          { "data": "note" },
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },
        ],
        "searching": true,
        "lengthChange": false,
        "info":false,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 20,
      })
    }

    function changetabPane(status_backdate) {
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=" + status_backdate + "&year=" + $('#year_filter').val()).load();
    }

    $("#year_filter").change(function(){
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearquote')}}?status=A&year=" + this.value).load();
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#alert").slideUp(300);
    });

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    }); 

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    $('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');

  </script>
@endsection