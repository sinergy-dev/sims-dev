@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">
    <h1>
      ID Project
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">ID Project</li>
    </ol>
  </section>

  <section class="content">
  <!--  -->
    <div class="box">
        <div class="box-header">
        <h3 class="box-title"><i class="fa fa-table"></i>&nbspDetail ID Project</h3>
          <div class="pull-right">
            
          </div>
        </div>

        <div class="box-body">
            <a href="{{url('/salesproject')}}"><button class="btn btn-danger margin-bottom" style="width: 150px"><i class="fa fa-home">&nbspBack to Home</i></button></a>
                <div class="table-responsive">
                @if (session('success'))
                  <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your PID :<h4> {{$pops->id_project}}</h4></div>
                @elseif (session('error'))
                  <div class="alert alert-danger notification-bar" id="alert"><span>notice: </span> {{ session('error') }}.</div>
                @endif
                    <span>
                      <h4>ID Project Induk : <b>{{$induk->id_project}}</b></h4>
                    </span>
                    <span>
                      <button class="btn btn-primary btn-payung margin-bottom" data-target="#payung" data-toggle="modal" onclick="add_payung('{{$induk->id_pro}}','{{$induk->lead_id}}')">Add</button>
                    </span>
                    <table class="table table-bordered table-striped dataTable" id="sip-data" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                          <th>Date</th>
                          <th>ID Project</th>
                          <th>NO. PO</th>
                          <th>Lead ID</th>
                          <th>Project Name</th>
                          @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                          <th>Amount</th>
                          @endif
                          <th>Sales</th>
                        </tr>
                    </thead>
                    <tbody id="products-list" name="products-list">
                      @foreach($detail_salessp as $detail)
                        <tr>
                            <td>{{ $detail->date }}</td>
                            <td>{{ $detail->id_project }}</td>
                            <td>{{ $detail->no_po_customer }}</td>
                            <td>{{ $detail->lead_id }}</td>
                            <td>{{ $detail->opp_name }}</td>
                            @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN'|| Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position != 'STAFF')
                            <td><i class="money">{{ $detail->amount_idr }}</i></td>
                            @endif
                            <td>{{ $detail->name }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                    </table>
                </div>
        </div>
    </div>


    <!--payung-->

<div class="modal fade" id="payung" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add ID Project</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_sp')}}">
            @csrf
            <input type="" name="payung_id" id="payung_id" hidden>
            <input type="" name="customer_name" id="customer_payung" hidden>
            <div class="form-group">
              <label for="">Date</label>
              <input type="text" name="date" id="date" class="form-control date" required>
            </div>

            <div class="form-group">
              <label for="">No. PO Customer</label>
              <input type="text" name="po_customer" id="po_customer" class="form-control">
            </div>

            <div class="form-group" hidden>
              <label for="">Sales</label>
              <input type="text" name="sales" id="sales" class="form-control" readonly>
            </div>

            <div class="form-group  modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true" style="margin-bottom: 24px">Rp.</i>
            </div>

            <div class="form-group">
              <label for="">Note</label>
              <input type="text" placeholder="Enter Note" name="note" id="note" class="form-control">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary" ><i class="fa fa-check">&nbsp</i>Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
    $('#sip-data').DataTable({
        "scrollX": true,
        "order": [[ 1, "desc" ]],
    });

    $('.date').datepicker("setDate",new Date());

    $('.money').mask('#.##0,00' , {reverse: true});

    function add_payung(id_pro,lead_id)
    {
      $('#payung_id').val(id_pro);
      $('#customer_payung').val(lead_id);
    }

    $(".dismisbar").click(function(){
       $(".notification-bar").slideUp(300);
    }); 
  </script>
@endsection