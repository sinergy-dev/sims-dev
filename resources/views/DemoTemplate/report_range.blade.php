@extends('template.template')
@section('content')
<style type="text/css">
  .btn-warning-export{
    background-color: #ffc107;
    border-color: #ffc107;
  }
</style>
<div class="content-wrapper">
    <div class="container-fluid">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/report_range')}}">Report Range</a>
        </li>
      </ol>

      <div class="row">
        <div class="col-md-12 form-group">
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Report Range Table
          @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
          <!-- <a href="{{action('ReportController@downloadReportPDF')}}" class="btn btn-warning float-right  margin-left-custom"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a> -->
          <tooltip title="Choose Date First" placement="top">
          <button class="btn btn-warning-export float-right  margin-left-custom" id="btnSubmit" disabled onclick="exportPdf()"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</button>
          </tooltip>
           <select class="form-control-report float-right margin-left-custom" id="dropdown2">
           </select>
           <select class="form-control-report pull-right" style="margin-left: 20px;" id="dropdown" disabled>
             <option value="customer">Customer</option>
             <option value="sales">Sales</option>
             <option value="territory">Territory</option>
             <option value="status">Status</option>
             <option value="presales">Presales</option>
             <option value="priority">Priority</option>
             <option value="win">Win Probability</option>
           </select>
           <input type="text" id="enddate" class="form-control-date pull-right" placeholder="DD/MM/YYYY">  
           <p class="pull-right" style="margin-top: 5px">&nbspto&nbsp</p>
           <input type="text" id="startdate" class="form-control-date pull-right" placeholder="DD/MM/YYYY">
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
                  <th>Closing Date</th>
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
                  <td>{{ $data->closing_date }}</td>
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

  <!-- <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Sinergy Informasi Pratama © 2018</small>
        </div>
      </div>
    </footer> -->
  
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script> -->

<script type="text/javascript">
  $('.money').mask('000,000,000,000,000.00', {reverse: true});
  $("#dropdown2").on('change',function(){
   if($(this).find('option:selected').text()=="Select Option")
       $("#btnSubmit").attr('disabled',true)
   else
       $("#btnSubmit").attr('disabled',false)
  });
  var enableDisableSubmitBtn = function(){
     var startVal = $('#startdate').val().trim();
     var endVal = $('#enddate').val().trim();
     var disableBtn =  startVal.length == 0 ||  endVal.length == 0;
     $('#dropdown').attr('disabled',disableBtn);
  }
</script>

<script type="text/javascript"> 
  var url = {!! json_encode(url('/')) !!}

  function exportPdf() {
    type = encodeURI($("#dropdown").val())
    date_start = encodeURI(moment($("#startdate").datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"))
    date_end = encodeURI(moment($("#enddate").datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"))
    dropdown2 = encodeURI($("#dropdown2").val())
    myUrl = url+"/getCustomerbyDate2?type="+type+"&customer="+dropdown2+"&start="+date_start+"&end="+date_end
    location.assign(myUrl)
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $( "#startdate" ).datepicker({
       dateFormat: 'dd-mm-yy',
       onSelect: function(selected) {
          $("#enddate").datepicker("option","10/03/2015", selected);
          enableDisableSubmitBtn();
          }
    });
    $( "#startdate" ).change(function(){
      console.log($( "#startdate" ).datepicker("getDate"));
      console.log(moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"));
    });
    $( "#enddate" ).datepicker({
      dateFormat: 'dd-mm-yy' ,
      onSelect: function(selected) {
         $("#startdate").datepicker("option","09/04/2015", selected);
         enableDisableSubmitBtn();
        }
    });
    $( "#enddate" ).change(function(){
      console.log($( "#enddate" ).datepicker("getDate"));
      console.log(moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"));
    });

      $('#dropdown').change(function(){
          // console.log(this.value);
          // console.log('result');
          var type = this.value;
          $("#dropdown2").change(function(){
            // console.log(this.value);
            $.ajax({
              type:"GET",
              url:"getCustomerbyDate",
              data:{
                customer:this.value,
                type:type,
                start:moment($( "#startdate" ).datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"),
                end:moment($( "#enddate" ).datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss")
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
                  table = table + '<td>' +value.closing_date+ '</td>';
                  table = table + '<td>' +value.name+ '</td>';
                  if (value.amount == null) {
                    table = table + '<td>' +' '+ '</td>';
                  }else{
                    table = table + '<td>' +'<i class="money">'+value.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'.00'+'</i>'+ '</td>';
                  }
                  
                  if (value.result == '') {
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
                var append = "";
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
                  // console.log(territory);
                  $.each(territory, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                }else if (result[1] == 'status') {
                  var status = [null,'SD', 'TP', 'WIN','LOSE'];
                  console.log(status);
                  $.each(status, function(key, value){
                  console.log(value);
                    if (value == null) {
                      append = append + "<option>" + 'OPEN' + "</option>";
                    }else{
                      append = append + "<option>" + value + "</option>";
                    }
                });
                } else if (result[1] == 'presales') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name + "</option>";
                });
                } else if (result[1] == 'priority') {
                  var prio = ['Contribute', 'Fight', 'Foot Print', 'Guided'];
                  // console.log(prio);
                $.each(prio, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                } else if (result[1] == 'win') {
                  var win = ['LOW', 'MEDIUM', 'HIGH'];
                  // console.log(win);
                $.each(win, function(key, value){
                  console.log(value);
                  append = append + "<option>" + value + "</option>";
                });
                }
                $('#dropdown2').html(append);
              },
          });
      });
  });
</script>
@endsection