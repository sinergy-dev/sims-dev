@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Report</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12 form-group">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Report Table
          @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
          <!-- <a href="" class="btn btn-success float-right margin-left-custom" id="btnShow">Show</a> -->
          <!-- <a href="{{action('ReportController@downloadPdfreport')}}" class="btn btn-warning float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a> -->
           <select class="form-control-report float-right margin-left-custom" id="dropdown2">
           </select>
           <select class="form-control-report pull-right" id="dropdown">
             <option value="customer">Customer</option>
             <option value="sales">Sales</option>
             <option value="territory">Territory</option>
             <option value="status">Status</option>
             <option value="presales">Presales</option>
             <option value="priority">Priority</option>
             <option value="win">Win Probability</option>
           </select>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Lead ID</th>
                  <th>Customer</th>
                  <th>Opty Name</th>
                  <th>Create Date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="report" name="report">
                @foreach($lead as $data)
                <tr>
                  <td>{{ $data->lead_id }}</td>
                  <td>{{ $data->brand_name }}</td>
                  <td>{{ $data->opp_name }}</td>
                  <td>{!!substr($data->created_at,0,10)!!}</td>
                  <td>{{ $data->name }}</td>
                  @if($data->amount != NULL)
                  <td><i  class="money">{{ $data->amount }},00</i></td>
                  @else
                  <td></td>
                  @endif
                  <td>
                    @if($data->result == 'OPEN')
                      <label class="status-initial">INITIAL</label>
                    @elseif($data->result == '')
                      <label class="status-open">OPEN</label>
                    @elseif($data->result == 'SD')
                      <label class="status-sd">SD</label>
                    @elseif($data->result == 'TP')
                      <label class="status-tp">TP</label>
                    @elseif($data->result == 'WIN')
                      <label class="status-win">WIN</label>
                    @else
                      <label class="status-lose">LOSE</label>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
  </div>
  
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript"> 
  $('.money').mask('000,000,000,000,000.00', {reverse: true});
</script>
<script type="text/javascript">
  $(document).ready(function(){
      $('#dropdown').change(function(){
          /*console.log(this.value);
          console.log('result');*/
          var type = this.value;
          $("#dropdown2").change(function(){
            console.log(this.value);
            $.ajax({
              type:"GET",
              url:"getCustomer",
              data:{
                customer:this.value,
                type:type
              },
              success: function(result){
                $('#report').empty();

                var table = "";

                $.each(result, function(key, value){
                  table = table + '<tr>';
                  table = table + '<td>' +value.lead_id+ '</td>';
                  table = table + '<td>' +value.brand_name+ '</td>';
                  table = table + '<td>' +value.opp_name+ '</td>';
                  table = table + '<td>' +value.created_at.substring(0,10)+ '</td>';
                  table = table + '<td>' +value.name+ '</td>';
                  table = table + '<td>' + value.amount + '</td>';
                  if (value.result == 'OPEN') {
                    table = table + '<td><label class="status-initial">INITIAL</label> </td>';
                  } else if (value.result == '') {
                    table = table + '<td><label class="status-open">OPEN</label> </td>';
                  } else if (value.result == 'SD') {
                    table = table + '<td><label class="status-sd">SD</label> </td>';
                  } else if (value.result == 'TP') {
                    table = table + '<td><label class="status-tp">TP</label> </td>';
                  } else if (value.result == 'WIN') {
                    table = table + '<td><label class="status-win">WIN</label> </td>';
                  } else if (value.result == 'LOSE') {
                    table = table + '<td><label class="status-lose">LOSE</label> </td>';
                  }
                  table = table + '</tr>';

                });
                $('#report').append(table);
              },
            });
          });
          $.ajax({
              type:"GET",
              url:"client",
              data:{
                id_client:this.value,
              },
              success: function(result){
            /*    var append = "";*/
                $('#dropdown2').html(append)
                var append = "<option selected='selected'>Select Option</option>";

                if (result[1] == 'customer') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.brand_name + "</option>";
                });
                } else if (result[1] == 'sales') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name + "</option>";
                });
                } else if (result[1] == 'territory') {
                  var territory = ['TERRITORY 1', 'TERRITORY 2', 'TERRITORY 3', 'TERRITORY 4', 'TERRITORY 5', 'TERRITORY 6'];
                  console.log(territory);
                $.each(territory, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                } else if (result[1] == 'status') {
                  var status = ['OPEN', 'SD', 'TP', 'WIN', 'LOSE'];
                  console.log(status);
                $.each(status, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                } else if (result[1] == 'presales') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name + "</option>";
                });
                } else if (result[1] == 'priority') {
                  var prio = ['Contribute', 'Fight', 'Foot Print', 'Guided'];
                  console.log(prio);
                $.each(prio, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                } else if (result[1] == 'win') {
                  var win = ['LOW', 'MEDIUM', 'HIGH'];
                  console.log(win);
                $.each(win, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                }
                $('#dropdown2').html(append);
              },
          });
      });
    
    $('#assigned_by').change(function(){
        $.ajax({
              type:"GET",
              url:"client",
              data:{
                id_client:this.value,
              },
              success: function(result){
            /*    var append = "";*/
                $('#quote_number').html(append)
                var append = "<option selected='selected'>Select Option</option>";

                if (result[1] == 'DIR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.quote_number + "</option>";
                });
                } else if (result[1] == 'AM') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.quote_number + "</option>";
                });
                }

                $('#quote_number').html(append);
              },
          });
    });

  });

$('#dataTables').DataTable( {
    "scrollX": true,
    "autoWidth": true,
    "order": [[ 6, "asc" ]],
    "buttons": [
        {
            extend: 'pdf',
            text: 'Save current page',
            exportOptions: {
                modifier: {
                    page: 'current'
                }
            }
        }
    ]

  });


</script>


@endsection