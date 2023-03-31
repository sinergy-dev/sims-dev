@extends('template.main')
@section('tittle')
SBE
@endsection
@section('head_css')
	<style type="text/css">
		.dataTables_filter {
		    display: none;
		}
	</style>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')
	<section class="content-header">
        <h1>
            SBE
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SBE</li>
        </ol><br>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3></h3>
                <div class="box-tools">
              		<a class='btn btn-sm bg-purple' style='margin-bottom: 10px;display: none;' href='{{url("/sbe_create?create")}}/' id="btnAddConfig"><i class="fa fa-plus"></i>&nbsp Create Config</a>
                </div>
            </div>

            <div class="box-body">
		        <div class="row">
		            <div class="col-md-4 pull-right" id="search-table">
		              <div class="input-group" style="margin-left: 10px">
		                <div class="input-group-btn">
		                  <button type="button" id="btnShowPID" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		                    Show 10 entries
		                  </button>
		                  <ul class="dropdown-menu">
		                    <li><a href="#" onclick="$('#table-pid').DataTable().page.len(10).draw();$('#btnShowPID').html('Show 10 entries')">10</a></li>
		                    <li><a href="#" onclick="$('#table-pid').DataTable().page.len(25).draw();$('#btnShowPID').html('Show 25 entries')">25</a></li>
		                    <li><a href="#" onclick="$('#table-pid').DataTable().page.len(50).draw();$('#btnShowPID').html('Show 50 entries')">50</a></li>
		                    <li><a href="#" onclick="$('#table-pid').DataTable().page.len(100).draw();$('#btnShowPID').html('Show 100 entries')">100</a></li>
		                  </ul>
		                </div>
		                <input id="searchBarList" type="text" class="form-control" placeholder="Search Anything">
		                <span class="input-group-btn">
		                  <button id="applyFilterTableSearch" type="button" class="btn btn-default btn-md" style="width: 40px">
		                    <i class="fa fa-fw fa-search"></i>
		                  </button>
		                </span>
		              </div>
		            </div>
		        </div>
		        <div class="table-responsive">
                    <table class="table table-striped" width="100%" id="tbListSBE">
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('script')
<script type="text/javascript">
    var formatter = new Intl.NumberFormat(['ban', 'id']);
	// var data = "data":
    var accesable = @json($feature_item);

    accesable.forEach(function(item,index){
      
      $("#" + item).show()
    })

	var table = $('#tbListSBE').DataTable({	
        "ajax":{
            "type":"GET",
            "url":"{{url('/sbe/getDataSbe')}}",
        },
        columns: [
            {
              title: "Lead ID",
              data: "lead_id"
            },
            {
              title: "Project Name",
              data: "opp_name"
            },
            {
              title: "SOL Staff",
              data: "presales"
            },
            {
              title: "Status",
              // data: "nominal",
              render:function(data,type,row){
                if (row.status == 'Temporary') {
                    var label_bg = 'label-info'
                }else{
                    var label_bg = 'label-success'
                }
                return "<span class='label "+ label_bg +"'>"+ row.status +"</span>"
              }
            },
            {
              title: "Amount",
              // data: "nominal",
              render:function(data,type,row){
                return formatter.format(row.nominal)
              }
            },
            {
              title: "Action",
              render:function(data, type, row)
	          {
	          	return '<a class="btn btn-sm btn-primary btnDetail" style="margin-right:5px" data-value="'+ row.id +'" href="{{url("/sbe_detail")}}/'+ row.id +'?'+ row.lead_id +'" id="btnDetail">Detail</a>'
	          },
              data: null
            },
        ],
        "drawCallback": function( settings ) {
            var api = this.api();
            
            $.each(api.rows({page:'current'}).data(),function(index,item){
                
                if (item.status == "Fixed") {
                    
                    $(".btnDetail[data-value='"+ item.id +"']").next().remove()
                    $(".btnDetail[data-value='"+ item.id +"']").after('<a class="btn btn-sm btn-success" href="'+ item.link_document +'" target="_blank">Show PDF</a>')
                }
            })
            // Output the data for the visible rows to the browser's console
            
        },
        initComplete:function(){
            if (!accesable.includes('colSolStaff')) {
                table.columns(2).visible(false);
            }
        },
        "bFilter": true,
        "bSort":true,
        "bLengthChange": false,
        "bInfo": false
    });
</script>
@endsection