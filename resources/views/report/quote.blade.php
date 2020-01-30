@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Quote Number
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Quote Number</li>
  </ol>
</section>

<section class="content">
  <div class="box">
    <div class="box-header">
      <div class="btn-group float-right">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
          <b><i class="fa fa-download"></i> Export</b>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">PDF</a></li>
          <li><a href="#">EXCEL</a></li>
        </ul>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Quote Number</th>
              <th>Position</th>
              <th>Type of Letter</th>
              <th>Date</th>
              <th>To</th>
              <th>Attention</th>
              <th>Title</th>
              <th>Project</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
            <?php $no = 1; ?>
            @foreach($datas as $data)
            <tr>
              <td>{{ $data->quote_number }}</td>
              <td>{{ $data->position }}</td>
              <td>{{ $data->type_of_letter }}</td>
              <td>{{ $data->date }}</td>
              <td>{{ $data->to }}</td>
              <td>{{ $data->attention }}</td>
              <td>{{ $data->title }}</td>
              <td>{{ $data->project }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

@endsection

@section('script')
   <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
   <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>

   <script type="text/javascript">
     $('.money').mask('000,000,000,000,000.00', {reverse: true});

     $('#dataTable').DataTable( {
      "scrollX": true
      } );
   </script>
@endsection