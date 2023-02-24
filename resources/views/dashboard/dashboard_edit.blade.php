@extends('template.main')
@section('tittle')
Dashboard
@endsection
@section('head_css')
<style type="text/css">
  .icon{
    width: 90px;
  }
  
  .table-sip tbody tr:first-child td {
      background-color: #ffd324;
  }

  .table-msp tbody tr:first-child td {
      background-color: #ffd324;
  }

  .table-sip-ter tbody tr:first-child td{
  	background-color: dodgerblue;
  	color: white;
  }

  .table-sip-ter tbody tr:first-child td a i{
    color: white;
  }

  .outer-reset {
    position: relative;
    width: 100%;
    height: 150px;
    background: red;
  }

  .inner-reset {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    padding: 2rem;
    font-size: 60px;
  }

</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    Dashboard |
    <small><b id="waktu"></b></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active" >Dashboard</li>
  </ol>
</section>
<section class="content">
	<!--Box-->
	<div class="row" id="BoxId" style="display:none;"><!-- ./col --> </div>

	<!--Chart-->
  <div class="row" id="BoxTopWin" style="display:none;">
  	<div class="col-lg-6 col-xs-12" id="sipTop5" style="display:none;">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i>TOP 5 (WIN Projects)</i></h3>
          <h3 class="box-title pull-right"><b>SIP</b></h3>
        </div>
        <div class="box-body">
          <?php $no_sip = 1; ?>
          <table class="table table-bordered table-striped table-sip" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="5%"><center>No.</center></th>
                <th><center>Sales Name</center></th>
                <th width="20%"><center>Total Amount</center></th>
                <th width="10%"><center>Total</center></th>
              </tr>
            </thead>
            <tbody>
              @foreach($top_win_sip as $tops)
                <tr>
                  <td>{{ $no_sip++ }}</td>
                  <td>{{ $tops->name }}
                    <a href='{{url("/report_range")}}/{{$tops->nik}}' class="linkReportManager" target="_blank" style="color: black;float: right;display: none;"><i class="fa fa-external-link-square" style="text-align:right"></i></a>
                    @if($tops->id_territory == Auth::User()->id_territory)
                    <a href='{{url("/report_range")}}/{{$tops->nik}}' target="_blank" style="color: black;float: right;"><i class="fa fa-external-link-square" style="text-align:right"></i></a>
                    @endif
                  </td>
                  <td align="right">
                  <i class="money">{{ $tops->deal_prices }}</i>
                  </td>
                  <td><center>( {{ $tops->leads }} )</center></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
  	</div>

  	<div class="col-lg-6 col-xs-12" id="mspTop5" style="display:none;">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i>TOP 5 (WIN Projects)</i></h3>
          <h3 class="box-title pull-right"><b>MSP</b></h3>
        </div>
        <div class="box-body">
          <?php $no_msp = 1; ?>
          <table class="table table-bordered table-striped table-msp" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="5%"><center>No.</center></th>
                <th><center>Sales Name</center></th>
                <th width="20%"><center>Total Amount</center></th>
                <th width="10%"><center>Total</center></th>
              </tr>
            </thead>
            <tbody>
              @foreach($top_win_msp as $topm)
                <tr>
                    <td>{{ $no_msp++ }}</td>
                    <td>{{ $topm->name }} <a href='{{url("/report_range")}}/{{$topm->nik}}' target="_blank" style="color: black;float: right;"><i class="fa fa-external-link-square" style="text-align:right"></i></a></td>
                    <td align="right">
                    <i class="money">{{ $topm->deal_prices }}</i>
                    </td>
                    <td><center>( {{ $topm->leads }} )</center></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-xs-12" id="salesWinTerritory" style="display: none;">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i>WIN Projects {{Auth::User()->id_territory}}</i></h3>
          <h3 class="box-title pull-right"><b>SIP</b></h3>
        </div>
        <div class="box-body">
          <?php $no_sip = 1; ?>
          <table class="table table-bordered table-striped table-sip-ter" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="5%"><center>No.</center></th>
                <th><center>Sales Name</center></th>
                <th width="20%"><center>Total Amount</center></th>
                <th width="10%"><center>Total</center></th>
              </tr>
            </thead>
            <tbody id="tbody-table-sip-ter">
             
            </tbody>
          </table>
        </div>
      </div>
    </div>  
  </div>

  <div class="row" id="BoxTopWinTerritory" style="display:none;">
  	<div class="col-lg-6 col-xs-12">
    	<div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i>WIN Projects Per Territory</i></h3>
          <h3 class="box-title pull-right"><b>SIP</b></h3>
        </div>
        <div class="box-body">
          <?php $no_sip = 1; $territory= ""?>
          <table class="table table-bordered table-striped" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="5%"><center>No.</center></th>
                <th><center>Sales Name</center></th>
                <th width="20%" align="right"><center>Total Amount</center></th>
                <th width="10%"><center>Total</center></th>
              </tr>
            </thead>
            <tbody id="table-win-project-territory">
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-12" id="boxCharPietWinLose">
    	<div class="box box-danger">
      		<div class="box-header with-border">
        		<h3 class="box-title">Win/Lose</h3>
      		</div>
        	<div class="box-body">
            @if($wins + $loses == 0)
              <canvas id="Chartempty" height="100%"></canvas>
            @elseif($win2 + $lose2 == 0)
              <canvas id="Chartempty" height="100%"></canvas>
            @else
              <canvas id="myDoughnutChart" width="100%" height="100%"></canvas>
            @endif
        </div>
    	</div>
  	</div>

    <div class="col-lg-3 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Status Lead Register</h3>
          </div>
          <div class="box-body">
            <canvas id="myPieChart" width="100%" height="100%"></canvas>
          </div>
        </div>
    </div>
  </div>      	
  	
  <div class="row" id="BoxTotalLead" style="display:none;">
  	<div class="col-lg-6 col-xs-12" id="boxChartTotalAmountLead">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Total Amount Lead Register (Deal Price)</h3>
        </div>
        <div class="box-body">
          <canvas id="AreaChart2019"></canvas>
        </div>
      </div>
  	</div>

  	<div class="col-lg-6 col-xs-12" id="boxChartTotalLead">
	      <div class="box box-success">
	        <div class="box-header with-border">
	          <h3 class="box-title">Total Lead Register</h3>
	        </div>
	        <div class="box-body">
	          <div class="row">
	            <div class="col-lg-12 col-xs-12">
	              <canvas id="myBarChart"></canvas>
	            </div>
	          </div>
	        </div>
	      </div>
	  </div>
	</div>

  <div class="row" id="BoxTotalLeadByStatus" style="display:none;">
  	<div class="col-lg-12 col-xs-12" id="boxChartTotalAmountLeadByStatus">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">Total Amount Lead Register (By Status)</h3>
          </div>
          <div class="box-body">
            	<canvas id="barChartByStatus"></canvas>
		  		</div>
        </div>
    </div>  
 
    <div class="col-lg-3 col-xs-12" id="boxChartDonutWinLose2" style="display:none;">
    	<div class="box box-danger">
      		<div class="box-header with-border">
        		<h3 class="box-title">Win/Lose</h3>
      		</div>
        	<div class="box-body">
            @if($wins + $loses == 0)
              <canvas id="Chartempty" height="100%"></canvas>
            @elseif($win2 + $lose2 == 0)
              <canvas id="Chartempty" height="100%"></canvas>
            @else
              <canvas id="myDoughnutChart2" width="100%" height="100%"></canvas>
            @endif
        </div>
    	</div>
  	</div>

    <div class="col-lg-3 col-xs-12" id="boxCharPietWinLose2" style="display:none">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Status Lead Register</h3>
          </div>
          <div class="box-body">
            <canvas id="myPieChart2" width="100%" height="100%"></canvas>
          </div>
        </div>
    </div>    	
  </div>

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
	        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">×</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        Select "Logout" below if you are ready to end your current session.
	      </div>
	      <div class="modal-footer">
	        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
	        <a class="btn btn-primary" href="{{ url('/login')}}">Logout</a>
	      </div>
	    </div>
	  </div>
	</div>
	<div id="popUp" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content modal-style">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">
	          &times;
	        </button>
	        <h4 class="modal-title">ANNOUNCEMENT</h4>
	      </div>
	      <div class="modal-body">
	        <h3 class="box-title text-center">
	          <b>SALES APP</b><br><i>(Tender Process)</i>
	        </h3>
	        <div class="row">
	          <div class="col-md-12">
	            <h4>
	              Dear all Sales,<br><br>
	              Terdapat beberapa penyesuaian untuk Lead Register dengan rincian sebagai berikut:<br><br>
	              <ul>
	                <li>Submitted Price adalah harga nego.<br></li>
	                <li>Deal Price adalah harga sesuai PO.<br><br></li>
	              </ul>
	              <b>Penyesuaian untuk Request PID :</b><br>
	              <p>Lead yang memiliki tanggal PO tahun lalu (backdate) harap email manual pada Bu Anee seperti proses manual sebelumnya, re:<i>yuliane@sinergy.co.id</i>. Dikarenakan semua PID yang melalui sistem hanya di peruntukkan untuk tanggal PO di tahun ini</p>
	              <br>
	              Untuk pengisian proses "Tender Process" terdapat beberapa perubahan:<br><br>
	              <ul>
	                <li>Terdapat penambahan status Project Class (Multiyears / Blanket / Normal) yang wajib diisi.<br></li>
	                <li>Project Class Normal untuk project dalam tahun ini, Multiyears project beberapa tahun, dan Blanket adalah project dengan model kontrak payung.<br></li>
	                <li>Jumlah Tahun & Deal Price Total wajib diisi saat memilih Project Class Multiyears / Blanket.<br></li>
	                <li>Untuk status Normal, Deal Price adalah nominal sesuai PO.<br></li>
	                <li>Untuk status Multiyears / Blanket, Deal Price adalah PO tahun ini dan Deal Price Total adalah total nominal PO keseluruhan<br><br></li>
	              </ul>
	              <b>Mohon Deal Price diisi untuk perhitungan dan report.</b><br><br>
	              
	              Terkait perubahan tersebut, Lead Register yang ber-status Win bisa di edit kembali untuk pengisian Deal Price.<br><br>
	              Terimakasih.
	            </h4>
	          </div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
	      </div>
	    </div>
	  </div>
	</div>
	<div id="changePassword" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content modal-style">
	      <div class="outer-reset">
	        <button type="button" class="close pull-right" style="width: 20px;" data-dismiss="modal">&times;</button>
	        <div class="inner-reset">
	          <i class="fa fa-warning fa-7x" style="color: white"></i>
	        </div>
	      </div>
	      <div class="modal-body">
	        <h4 style="text-align: center;"><b>Please change default password to protect your account !</b></h4>
	          <a href="{{url('/profile_user')}}#changePassword">
	            <span class="btn btn-info btn-block" style="border-radius: 24px">Change Password</span>
	          </a>
	        <span data-dismiss="modal" style="cursor: pointer;">
	          <h5 class="text-center" style="color: #00acd6">Skip Now</h5>
	        </span>
	      </div>
	      <div class="modal-footer">
	        <p class="text-center">©SIMS - 2021</p>              
	      </div>
	    </div>
	  </div>
	</div>
</section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
	var accesable = @json($feature_item);
	accesable.forEach(function(item,index){
  	$("#" + item).show()
    $("." + item).show()
	})

  $('.money').mask('000,000,000,000,000', {reverse: true});

  function initMoney(){
  	$('.money').mask('000,000,000,000,000', {reverse: true});
  }

	if("{{Auth::User()->isDefaultPassword}}" == 'true'){
      $("#changePassword").modal('show')
  }

	startTime()

	function startTime() {
	    var today = new Date();
	    var time = moment(today).format('MMMM Do YYYY, h:mm:ss a');
	    var h = today.getHours();
	    var m = today.getMinutes();
	    var s = today.getSeconds();
	    m = checkTime(m);
	    s = checkTime(s);
	    document.getElementById('waktu').innerHTML =  time;
	    var t = setTimeout(startTime, 500);
	}

	function checkTime(i) {
	    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	    return i;
	}

	var i = 0
	var colors = []
	var prepend = ""	

	$.ajax({
    type:"GET",
    url:"{{url('/getDashboardBox')}}",
    success: function(result){
    	if ("{{Auth::User()->name == 'TECH HEAD'}}") {
		    	var ArrColors = [
		    		{name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"report_range/All"},
		    		{name:'Occuring',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"report_range/OPEN"},
		    		{name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"report_range/WIN"},
		    		{name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"report_range/LOSE"}
		    	]
		    	colors.push(ArrColors)
			}else{
				var ArrColors = [
					{name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"report_range/ALL"},
					{name:'Occuring',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"report_range/OPEN"},
					{name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"report_range/WIN"},
					{name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"report_range/LOSE"}]
		    	colors.push(ArrColors)
			}

			$.each(colors[0], function(key, value){
		  	prepend = prepend + '<div class="col-lg-3 col-xs-6">'
				prepend = prepend + '<div class="small-box '+value.color+'">'
		     	prepend = prepend + '<div class="inner">'
		       	prepend = prepend + '<h3 class="counter">'+value.count+'</h3>'
		       	prepend = prepend + '<p>'+value.name+'</p>'
		     	prepend = prepend + '</div>'
		     	prepend = prepend + '<div class="icon">'
		       	prepend = prepend + '<i class="'+value.icon+'"></i>'
		     	prepend = prepend + '</div>'
		     	// prepend = prepend + '<a href="' + '{{action("ReportController@view_open")}}' + '" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>'
		     	prepend = prepend + '<a href="/' + value.url +'" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>'
		   	prepend = prepend + '</div>'
		 		prepend = prepend + '</div>'
		  })

    	$("#BoxId").prepend(prepend)

    	$('.counter').each(function () {
		    var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
		    $(this).prop('Counter', 0).animate({
		      Counter: $(this).text()
		    }, {
		      duration: 5000,
		      step: function (func) {
		         $(this).text(parseFloat(func).toFixed(size));
		      }
		    });
			});
    }
	})

	if (accesable.includes('salesWinTerritory')) {
		var top_win_sip_ter =  JSON.parse('@json($top_win_sip_ter)')
		var append = ""
		var no = 1
		$.each(top_win_sip_ter, function(key, value){
			append = append + '<tr>'
		    append = append + '<td>'+ no++ +'</td>'
        if ("{{Auth::User()->id_territory}}" == value.id_territory) {
          append = append + 'woy'
          append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
        }else{
          append = append + '<td>'+value.name+'</td>'
        }
		    append = append + '<td align="right">'
		    append = append + '<i class="money">'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
		    append = append + '</td>'
		    append = append + '<td><center>('+value.leads+')</center></td>'
		  append = append + '</tr>'
		})
		$("#tbody-table-sip-ter").html(append)
	}

	if (accesable.includes('BoxTopWinTerritory')) {
		var territory = ""
		var no = 1;
		var top_win_sip_ter =  JSON.parse('@json($top_win_sip_ter)')
		var append = ""	
		$.each(top_win_sip_ter, function(key, value){
		  $.each(value, function(key, value){
        console.log(value)
        console.log(value.id_territory != "TOTAL" )
		    if(value.id_territory == territory){
		    	append = append + '<tr>'
				    append = append + '<td>'+ no++ +'</td>'
				    append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" target="_blank" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
				    append = append + '<td align="right">'
				    append = append + '<i class="money">'+ new Intl.NumberFormat('id').format(value.deal_prices)+'</i>'
				    append = append + '</td>'
				    append = append + '<td><center>('+value.leads+')</center></td>'
				  append = append + '</tr>'
		    }else{
		    	  territory = value.id_territory
            append = append + '<tr style="background-color:dodgerblue;color: white;">'
                append = append + '<td colspan="2">'+ value.id_territory +'</td>'
                append = append + '<td align="right">Rp.<i class="money">'+ new Intl.NumberFormat('id').format(value.sum_total)+'</i></td>'
                append = append + '<td style="text-align:center;">'+value.leads_total+'</td>'
            append = append + '</tr>'
          if(value.id_territory != "TOTAL"){
            append = append + '<tr>'
					    append = append + '<td>'+ no++ +'</td>'
					    append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" target="_blank" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
					    append = append + '<td align="right">'
					    append = append + '<i class="money">'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
					    append = append + '</td>'
					    append = append + '<td><center>('+value.leads+')</center></td>'
					  append = append + '</tr>'
          }
		    }
		  });
		});
		$("#table-win-project-territory").html(append)
	}

	var barThickness = "15"
	if (accesable.includes('boxChartDonutWinLose2')) {
		barThickness = "8"
		$("#boxChartTotalAmountLeadByStatus").removeClass('col-lg-12 col-xs-12').addClass('col-lg-6 col-xs-12')
	}

   	var ctx = document.getElementById("AreaChart");
    var ctx2 = document.getElementById("myBarChart");
    var ctx3 = document.getElementById("myBarChart2");
    var ctx4 = document.getElementById("myBarChart3");
    var ctx5 = document.getElementById("myPieChart");
    var ctx6 = document.getElementById("myPieChart2");
    var ctx7 = document.getElementById("myDoughnutChart");
    var ctx18 = document.getElementById("myDoughnutChart2");
    var ctx8 = document.getElementById("DoughnutchartClaim");
    var ctx9 = document.getElementById("AreaChart2");
    var ctx10 = document.getElementById("Chartempty");
    var ctx13 = document.getElementById("AreaChartEmpty");
    var ctx14 = document.getElementById("AreaChart2019")
    var ctx15 = document.getElementById("myPieChartEmpty");
    var ctx16 = document.getElementById("AreaChart2018");
    var ctx17 = document.getElementById("barChartByStatus");

    $.ajax({
    	type:"GET",
    	url:"getChartByStatus",
    	success:function(result){
    		var INITIAL = result.data.map(function(e) {
		    	return e.INITIAL
			})

			var OPEN = result.data.map(function(e) {
		    	return e.OPEN
			})

			var SD = result.data.map(function(e) {
		    	return e.SD
			})

			var TP = result.data.map(function(e) {
		    	return e.TP
			})

			var WIN = result.data.map(function(e) {
		    	return e.WIN
			})

			var LOSE = result.data.map(function(e) {
		    	return e.LOSE
			})

			var amount_INITIAL = result.data.map(function(e) {
		    	return e.amount_INITIAL
			})

			var amount_OPEN = result.data.map(function(e) {
		    	return e.amount_OPEN
			})

			var amount_SD = result.data.map(function(e) {
		    	return e.amount_SD
			})

			var amount_TP = result.data.map(function(e) {
		    	return e.amount_TP
			})

			var amount_WIN = result.data.map(function(e) {
		    	return e.amount_WIN
			})

			var amount_LOSE = result.data.map(function(e) {
		    	return e.amount_LOSE
			})

  		var barChartByStatus = new Chart(ctx17, {
		    type: 'bar',
		    data: {
	            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
	            labels2:[amount_INITIAL,amount_OPEN,amount_SD,amount_TP,amount_WIN,amount_LOSE],	    	
			    datasets: [{
			        label: "INITIAL",
		          backgroundColor: "#7735a3",
		          borderColor: "#7735a3",
			        data: INITIAL,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    {
			        label: "OPEN",
		            backgroundColor: "#f2562b",
		            borderColor: "#f2562b",
			        data: OPEN,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    {
			        label: "SD",
		            backgroundColor: "#04dda3",
		            borderColor: "#04dda3",
			        data: SD,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    {
			        label: "TP",
		            backgroundColor: "#f7e127",
		            borderColor: "#f7e127",
			        data: TP,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    {
			        label: "WIN",
		            backgroundColor: "#246d18",
		            borderColor: "#246d18",
			        data: WIN,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    {
			        label: "LOSE",
		            backgroundColor: "#e5140d",
		            borderColor: "#e5140d",
			        data: LOSE,
              barPercentage: 0.10,
              barThickness: barThickness,
			    },
			    ]
				},
				options: {
				    tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return ['Rp.' + data['labels2'][tooltipItem[0].datasetIndex][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")]
				        },
				        label: function(tooltipItem, data) {
				          return data.datasets[tooltipItem.datasetIndex].label
				        },
				        footer: function(tooltipItem, data) {
						      return ['Total : ' + data['datasets'][tooltipItem[0].datasetIndex]['data'][tooltipItem[0]['index']]];
						    },
				        afterLabel: function(tooltipItem, data) {
				        }
				      }
				    },
				}
			});
    	}
    })   

    $.ajax({
        type:"GET",
        url:"getChart",
        success:function(result){
              var myBarChart = new Chart(ctx2, {
	          type: 'bar',
	          data: {
	            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
	            datasets: [{
	              label: "Lead Register"+' '+Date("YYYY").substring(11,15),
	              backgroundColor: "#00a65a",
	              borderColor: "#00a65a",
	              data: result,
	            }],
	          },
	          options: {
              tooltips: {
              callbacks: {
                title: function(tooltipItem, data) {
                  return [tooltipItem[0].label]
                },
                label: function(tooltipItem, data) {
                 return ['Total : ' + tooltipItem.value]
                },
                footer: function(tooltipItem, data) {
                  
                },
                afterLabel: function(tooltipItem, data) {
                }
              }
            },
	          }
	        });
        }
    })

   	$.ajax({
        type:"GET",
        url:"getAreaChart2019",
        success:function(result){
            var AreaChart = new Chart(ctx14, {
        type: 'line',
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
          datasets: [{
            label: "Amount"+' '+Date("2018").substring(11,15),
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 20,
            pointBorderWidth: 2,
            data: result,
          }],
        },
        options: {
        legend: {
          display: true
          },
        tooltips: {
         mode: 'label',
         label: 'Rp',
         callbacks: {
             label: function(tooltipItem, data) {
                 return "Rp." + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }, },
        },
        scales: {
            yAxes: [{
              ticks: {
                beginAtZero:true,
                userCallback: function(value, index, values) {
                  // Convert the number to a string and splite the string every 3 charaters from the end
                  value = value.toString();
                  value = value.split(/(?=(?:...)*$)/);
                  value = value.join(',');
                  return value;
                }
              }
            }],
            xAxes: [{
              ticks: {
              }
            }]
          },
        },
      });
    	}
  	})
    
    $.ajax({
          type:"GET",
          url:"getPieChart",
          success:function(result){
              var myPieChart = new Chart(ctx5, {
          type: 'pie',
          data: {
            labels: ["INITIAL", "OPEN", "SD", "TP", "WIN", "LOSE"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            toolTipContent: "{y} (#percent%)",
            datasets: [{
              data: result,
              backgroundColor: ['#7735a3', '#f2562b', '#04dda3', '#f7e127', '#246d18', '#e5140d'],
            }],
          },
          options: {
          legend: {
            display: true
            },
          tooltips: {
           mode: 'label',
           label: 'mylabel',
           callbacks: {
            label: function(tooltipItem, data) {
                    return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + ' %';},},
            },
          },
        });
      }
    })

    $.ajax({
          type:"GET",
          url:"getPieChart",
          success:function(result){
              var myPieChart = new Chart(ctx6, {
          type: 'pie',
          data: {
            labels: ["INITIAL", "OPEN", "SD", "TP", "WIN", "LOSE"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            toolTipContent: "{y} (#percent%)",
            datasets: [{
              data: result,
              backgroundColor: ['#7735a3', '#f2562b', '#04dda3', '#f7e127', '#246d18', '#e5140d'],
            }],
          },
          options: {
          legend: {
            display: true
            },
          tooltips: {
           mode: 'label',
           label: 'mylabel',
           callbacks: {
            label: function(tooltipItem, data) {
                    return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + ' %';},},
            },
          },
        });
      }
    })

    $.ajax({
      type:"GET",
      url:"getDoughnutChart",
      success:function(result){
      var myDoughnutChart = new Chart(ctx7, {
	        type: 'doughnut',
	        data: {
	          labels: ["WIN", "LOSE"],
	          indexLabel: "#percent%",
	          percentFormatString: "#0.##",
	          datasets: [{
	            data: result,
	            backgroundColor: ['#246d18', '#e5140d'],
	          }],
	        },
	        options: {
	        showTooltips: true,
	        legend: {
	          display: true
	          },
	        tooltips: {
	         mode: 'label',
	         label: 'mylabel',
	         callbacks: {
	          	label: function(tooltipItem, data) {
	            	return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
	            },
	          },
	        },
	      },
	      });
	    }
    });

    $.ajax({
      type:"GET",
      url:"getDoughnutChart",
      success:function(result){
      var myDoughnutChart2 = new Chart(ctx18, {
	        type: 'doughnut',
	        data: {
	          labels: ["WIN", "LOSE"],
	          indexLabel: "#percent%",
	          percentFormatString: "#0.##",
	          datasets: [{
	            data: result,
	            backgroundColor: ['#246d18', '#e5140d'],
	          }],
	        },
	        options: {
	        showTooltips: true,
	        legend: {
	          display: true
	          },
	        tooltips: {
	         mode: 'label',
	         label: 'mylabel',
	         callbacks: {
	          	label: function(tooltipItem, data) {
	            	return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
	            },
	          },
	        },
	      },
	      });
	    }
    });

    if("{{Auth::User()->id_division}}" == 'SALES'){ 
      if (sessionStorage.getItem('dontLoad') == null){
          $("#popUp").modal("show");
      }
      sessionStorage.setItem('dontLoad', 'true');
    }

    $(document).keyup(function(e) {
      if (e.keyCode == 27) {
          $('#popUp').modal('hide');
      }
    });

</script>
@endsection