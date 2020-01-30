@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Purchase Request
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Purchase Request</li>
  </ol>
</section>

<section class="content">

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

  <div class="box">
    <div class="box-header">
      <div class="btn-group float-right">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
          <b><i class="fa fa-download"></i> Export</b>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">PDF</a></li>
          <li><a href="{{action('PrController@downloadExcelPr')}}">EXCEL</a></li>
        </ul>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="data_Table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
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
              <th>Issuance</th>
              <th>Project ID</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            @foreach($datas as $data)
            <tr>
              <td>{{$data->no_pr}}</td>
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
              <td>{{$data->issuance}}</td>
              <td>{{$data->project_id}}</td>
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
    function edit_pr(no,to,attention,title,project,description,from,issuance,project_id) {
      $('#edit_no_pr').val(no);
      $('#edit_to').val(to);
      $('#edit_attention').val(attention);
      $('#edit_title').val(title);
      $('#edit_project').val(project);
      $('#edit_description').val(description);
      $('#edit_from').val(from);
      $('#edit_issuance').val(issuance);
      $('#edit_project_id').val(project_id);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#data_Table').DataTable( {
        "scrollX": true
        } );
  </script>
@endsection