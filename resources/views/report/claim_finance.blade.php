@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Claim
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Report Open</li>
  </ol>
</section>

<section class="content">
  <div class="box">
    <div class="box-header">
      
    </div>
    <div class="box-body">
      <table class="table table-bordered table-striped dataTable" id="data_Table" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>No</th>
            <th>Date</th>
            <th>Personnel</th>
            <th>Type</th>
            <th>Description</th>
            <th>Amount</th>
            <th>ID Project</th>
            <th>Remarks</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="products-list" name="products-list">
          @foreach($datas as $data)
          <tr>
            <td>{{ $data->no }}</td>
            <td>{{$data->date}}</td>
            <td>{{$data->name}}</td>
            <td>{{$data->type}}</td>
            <td>{{$data->description}}</td>
            <td class="money">{{$data->amount}}</td>
            <td>{{$data->id_project}}</td>
            <td>{{$data->remarks}}</td>

            @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE')
            <td>
              @if($data->status == 'FINANCE')
              <label class="status-open">PENDING</label>
              @elseif($data->status == 'TRANSFER')
              <label class="status-sd">TRANSFER</label>
              @elseif($data->status == 'ADMIN')
              <label class="status-lose">PENDING</label>
              @elseif($data->status == 'HRD')
              <label class="status-lose">PENDING</label>
              @endif
            </td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
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
     $('.money').mask('000,000,000,000,000', {reverse: true});

     $('#data_Table').DataTable();
   </script>
@endsection