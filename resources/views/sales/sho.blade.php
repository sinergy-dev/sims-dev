@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">
    <h1>
      Sales Handover
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Sales Handover</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header">
          <div class="pull-right"></div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
              <table class="table table-bordered table-striped display nowrap" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Lead ID</th>
                  <th>Sales Name</th>
                  <th>Scope of Work</th>
                  <th>Timeline</th>
                  <th>Term of Payment</th>
                  <th>Service Budget</th>
                  <th>Meeting Date</th>
                  @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                      <th>Action</th>
                  @endif
                </tr>
              </thead>
                <tbody>
                   @foreach($lead as $data)
                <tr>
                  <td><a href="{{url('/detail_sho', $data->id_sho)}}">{{$data->lead_id}}</td>
                  <td>{{$data->name}}</td>
                  <td>{{($data->sow)}}</td>
                  <td>{{$data->timeline}}</td>
                  <td>{{$data->top}}</td>
                  <td class="money">{{$data->service_budget}}</td>
                  <td>{!!substr($data->meeting_date,0,10)!!}</td>
                  @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  <td>  
                    @if(Auth::User()->name == $data->name && $data->status_sho != 'PID' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && $data->status_sho != 'PID' || Auth::User()->id_position == 'DIRECTOR' && $data->status_sho != 'PID')
                    <button class="btn btn-xs btn-primary" data-target="#edit_sho" data-toggle="modal" style="vertical-align: top; width: 60px" onclick="sho('{{$data->id_sho}}','{{$data->sow}}','{{$data->timeline}}','{{$data->top}}','{{$data->service_budget}}','{{$data->meeting_date}}')"><i class="fa fa-search"></i>&nbspEdit</button>
                    @else
                      <button class="btn btn-xs btn-primary disabled" style="vertical-align: top; width: 60px"></button>
                    @endif
                  </td>
                  @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="edit_sho" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Edit Sales Handover</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('update_sho')}}" id="modalCustomer" name="modalCustomer">
              @csrf
            <input type="" id="id_sho" name="id_sho" value="" hidden>
            <div class="form-group">
              <label for="sow">Scope of Work</label>
              <input type="text" class="form-control" id="sow" name="sow" placeholder="" required>
            </div>
            <div class="form-group">
              <label for="timeline">Timeline</label>
              <input type="text" class="form-control" id="timeline" name="timeline" placeholder="" required>
            </div>
            <div class="form-group">
              <label for="top">Term of Payment</label>
              <textarea type="text" class="form-control" id="top" name="top" placeholder="" required> </textarea>
            </div>
             <div class="form-group modalIcon inputIconBg">
              <label for="pro_budget">Project Budget</label>
              <input type="text" class="form-control money" id="pro_budget" name="pro_budget" placeholder="" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div>
             <div class="form-group">
              <label for="meeting_date">Meeting date</label>
              <input type="date" class="form-control" id="meeting_date" name="meeting_date" placeholder="" required>
            </div>


              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-warning"><i class="fa fa-check"></i>&nbspEdit</button>
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
  <script type="text/javascript">
    function sho(id_sho,sow,timeline,top,service_budget,meeting_date) {
      $("#id_sho").val(id_sho);
      $("#sow").val(sow);
      $("#timeline").val(timeline);
      $("#top").val(top);
      $("#pro_budget").val(service_budget);
      $("#meeting_date").val(meeting_date);
      // body...
    }

    $('#dataTable').DataTable({

    });

    $('.money').mask('000,000,000,000,000.00', {reverse: true});
  </script>
@endsection