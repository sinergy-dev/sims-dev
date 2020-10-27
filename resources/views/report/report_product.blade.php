@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
	.header th:first-child{
      background-color: #dbe2ff !important;
    }

    .header th:nth-child(2){
      background-color: #c2c3ff !important;
    }

    .header th:nth-child(3){
      background-color: #d2c2ff !important;
    }

    .header th:nth-child(4){
      background-color: #dcc2ff !important;
    }

    .header th:nth-child(5){
      background-color: #e1c2ff!important;
    }

    .header th:nth-child(6){
       background-color: #e8c2ff !important;
    }

    .header th:nth-child(7){
      background-color: #f8c2ff !important;
    }

    

	.green1-color {
	    background-color: #c2c3ff !important;
	}
	.green2-color {
	    background-color: #d2c2ff !important;
	}
	.green3-color {
	    background-color: #dcc2ff !important;
	}
	.green4-color {
	    background-color: #e1c2ff!important;
	}
	.green5-color {
	    background-color: #e8c2ff !important;
	}
	.green6-color {
	    background-color: #f8c2ff !important;
	}
	.green7-color {
	    background-color: #dbe2ff !important;
	}
	
</style>
<section class="content-header">
	<h1>
	  Report Products
	</h1>
	<ol class="breadcrumb">
	  <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
	  <li class="active">Report</li>
	  <li class="active">Report Products</li>
	</ol>
</section>
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Report Products</i></h3>
          </div>         

          <div class="box-body">              
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                <thead>
                  <tr class="header">
                    <th>PRODUCT</th>
                    @foreach($territory_loop as $data)
                    <th><center>{{$data->id_territory}}</center></th>
                    @endforeach
                    <th><center>TOTAL</center></th>
                  </tr>
                </thead>
                <tfoot>
                	<th></th>
                	<th></th>
                	<th></th>
                	<th></th>
                	<th></th>
                	<th></th>
                </tfoot>
              </table>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </section>
@endsection
@section('script')
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
<script type="text/javascript">
$(document).ready(function (){
	var tables = $('#data_lead').dataTable({
		"ajax":{
            "type":"GET",
            "url":"{{url('/getreportproduct')}}",
          },
          "columns": [
            {
              render: function ( data, type, row ) {
                return '<i>'+ row.name_product +'</i>';
                
              }
            },  
            // { "data": "name_product" },  
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.countTer1 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter1_price) + '</p></center>';
                
              }
            },  
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.countTer2 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter2_price) + '</p></center>';
                
              }
            },  
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.countTer3 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter3_price) + '</p></center>';
                
              }
            },  
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.countTer4 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter4_price) + '</p></center>';
                
              }
            },    
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.countTer5 + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.ter5_price) + '</p></center>';
                
              }
            }, 
            {
              render: function ( data, type, row ) {
                return '<center> <b>[' + row.total_lead + ']</b><br>' + $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.total_price) + '</p></center>';
                
              }
            },       
          ],
          "scrollX": false,
          "ordering": false,
          "processing": true,
          "paging": true,
          "rowCallback": function( row, data ) {
          		$('td', row).eq(0).addClass('green7-color');
		        $('td', row).eq(1).addClass('green1-color');
		        $('td', row).eq(2).addClass('green2-color');
		        $('td', row).eq(3).addClass('green3-color');
		        $('td', row).eq(4).addClass('green4-color');
		        $('td', row).eq(5).addClass('green5-color');
		        $('td', row).eq(6).addClass('green6-color');
		    }
	});
})
</script>

@endsection