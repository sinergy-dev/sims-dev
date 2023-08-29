@extends('template.main')
@section('tittle')
Record Log History
@endsection
@section('head_css')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<!-- Select2 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')
<section class="content-header">
	<h1>
    	Record Log History
  	</h1>
  	<ol class="breadcrumb">
	    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
	    <li class="active">Report</li>
	    <li class="active">Record Log History</li>
  	</ol>
</section>
<section class="content">
	<div class="row">
	    <div class="col-md-12">
	      	<div class="box">
		        <div class="box-header with-border">
		          <h3 class="box-title"><i class="fa fa-table"></i> Last Logged In Users</h3>
		        </div>
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="col-md-3" id="divFilterByPerson" style="display: none;">
			              <label>Filter by Person</label>
			              <select class="form-control capitalize" style="width: 100%;max-width: 250px" id="searchTagsPerson"></select>
			            </div>

			            <div class="col-md-3" id="divFilterByDate" style="display: none;">
				          <label>Filter by Date</label>
				          <div class="input-group">
				            <div class="input-group-addon">
				              <i class="fa fa-calendar"></i>
				            </div>
				            <input type="text" class="form-control" style="width: 100%" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
				            <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
				          </div>
				        </div>			            
				        <div class="col-md-3">
				          	<button class="btn btn-primary btn-sm" id="apply-btn" style="margin-top: 25px"><i class="fa   fa-check-circle"></i> Apply</button>
				           	<button class="btn btn-info btn-sm reload-table" id="reload-table" style="margin-top: 25px"><i class="fa fa-refresh"></i> Refresh</button>
				        </div>
				    </div>
		        </div>
		        <div class="box-body">
	            	<div class="table-responsive">
		              	<table class="table table-bordered table-striped" id="table_login_today" width="100%" cellspacing="0">
		                <thead>
		                  <tr class="header">
		                    <th>Name</th>
		                    <th>Email</th>                  
		                    <th>Last Login at</th>
		                    <th>IP Adress</th>
		                  </tr>
		                </thead>
		                <tbody>
		                	<tr>
		                		<td>Faiqoh</td>
		                		<td>Faiqoh@sinergy.co.id</td>
		                		<td>2020-09-23 13:50:22</td>
		                		<td>192.168.2.57</td>
		                	</tr>
		                </tbody>
		              	</table>
	            	</div>
	          	</div>     
	        </div>
	    </div>  
	    
<!-- 	    <div class="col-md-6">
	        <div class="box">
		        <div class="box-header with-border">
		          <h3 class="box-title"><i class="fa fa-table"></i> Last Logged In Users</h3>
		        </div>
		        <div class="box-body">
		           <div class="table-responsive">
		              <table class="table table-bordered table-striped" id="table_login_7_days" width="100%" cellspacing="0">
		                <thead>
		                  <tr class="header">
		                    <th>Name</th>
		                    <th>Email</th>                  
		                    <th>Last Login at</th>
		                    <th>IP Adress</th>
		                  </tr>
		                </thead>
		              </table>
		           </div>
		        </div>       
		    </div>
	    </div>  --> 
	</div>
</section>
@endsection
@section('scriptImport')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		var accesable = @json($feature_item);
		accesable.forEach(function(item,index){
			$("#" + item).show()
		})
	})

	initTableBefore();

	function initTableBefore(){
		$("#table_login_today").DataTable({
			"ajax":{
	                "type":"GET",
	                "url":"{{url('/get_auth_login_users')}}",
	                "data":{
	                }
	              },
	              "columns": [
	                { "data": "name" },  
	                { "data": "email" },  
	                { "data": "datetime" },
	                { "data": "ip_address" }         
	              ],
	              "scrollX": false,
	              "ordering": false,
	              "processing": true,
		});

	}	

	$.ajax({
        url:"sales/getAllEmployee",
        type:"GET",
        success:function(result){
          $("#searchTagsPerson").select2().val("");
          var arr = result;
          var selectOption = [];
          var data = {
              id: 2,
              text: 'All'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          var TagPersona = $("#searchTagsPerson").select2({
            placeholder: " Select Person",
            allowClear: true,
            multiple:true,
            data:selectOption,
            templateSelection: function(selection,container) {
              console.log(selection)
              if (selection.text == 'All') {
                return $.parseHTML('<span>' + selection.text + '</span>');
              }else{
                var selectedOption = $(selection.element).parent('optgroup').attr('label');
                  if(selectedOption == 'Sales') {
                      $(container).css("background-color", "#e6a715");
                      $(container).css("border-color","#e6a715");
                      return selection.text;
                  }else if (selectedOption == 'Presales') {
                      $(container).css("background-color", "#e0511d");
                      $(container).css("border-color","#e0511d");
                      return $.parseHTML('<span>' + selection.text.toLowerCase() + '</span>');
                  }else{
                      return $.parseHTML('<span>' + selection.text + '</span>');
                  }
              }
            
            }
          })

        }
    
    })

    var start = moment().startOf('year');
    var end = moment().endOf('year');

    function cb(start,end){
        $('#reportrange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))

        start_date  = start.format("YYYY-MM-DD 00:00:00");
        end_date    = end.format("YYYY-MM-DD 00:00:00");
    }

    $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'This Month'   : [moment().startOf('month'), moment().endOf('month')],
            'Last 3 Month' : [moment().startOf('month').subtract(3, 'months'), moment().endOf('month')],
            'Last 6 Month' : [moment().startOf('month').subtract(6, 'months'), moment().endOf('month')],
            'Last Year'    : [moment().startOf('year').subtract(1, 'year'),moment().endOf('year').subtract(1, 'year')],
            'This Year'    : [moment().startOf('year'),moment().endOf('year')],
          },
          locale: {
            format: 'DD/MM/YYYY'
          }
        },
      cb);

    cb(start,end);

    $("#apply-btn").click(function(){
    	var TagsPersona = $("#searchTagsPerson").val();
    	console.log(TagsPersona);

    	if (TagsPersona == "") {
    		$('#table_login_today').DataTable().ajax.url("{{url('getFilterRecordAuth')}}?start_date=" + start_date + "&" + "end_date=" + end_date).load();
    	}else{
    		$('#table_login_today').DataTable().clear().destroy();

    		$("#table_login_today").DataTable({
				"ajax":{
	                "type":"GET",
	                "url":"{{url('/getFilterRecordAuth')}}",
	                "data":{
	                	"TagsPersona":TagsPersona,
                  		"start_date":start_date,
                  		"end_date":end_date
	                }
	              },
	              "columns": [
	                { "data": "name" },  
	                { "data": "email" },  
	                { "data": "datetime" },
	                { "data": "ip_address" }         
	              ],
	              "scrollX": false,
	              "ordering": false,
	              "processing": true,
			});
    	}
    });

    $("#reload-table").click(function(){
      	$('#table_login_today').DataTable().clear().destroy();

      	$("#table_login_today").DataTable({
			"ajax":{
	                "type":"GET",
	                "url":"{{url('/get_auth_login_users')}}",
	                "data":{
	                }
	              },
	              "columns": [
	                { "data": "name" },  
	                { "data": "email" },  
	                { "data": "datetime" },
	                { "data": "ip_address" }         
	              ],
	              "scrollX": false,
	              "ordering": false,
	              "processing": true,
		});

      	$("#searchTagsPerson").val(null).trigger("change");
    })


	// $("#table_login_7_days").DataTable({
	// 	"ajax":{
 //            "type":"GET",
 //            "url":"{{url('/get_auth_login_users')}}",
 //            "data":{
 //            }
 //          },
 //          "columns": [
 //            { "data": "name" },  
 //            { "data": "email" },  
 //            { "data": "datetime" },
 //            { "data": "ip_address" }         
 //          ],
 //          "scrollX": false,
 //          "ordering": false,
 //          "processing": true,
 //          "paging": false,
	// });
</script>
@endsection