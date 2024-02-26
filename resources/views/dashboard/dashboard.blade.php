@extends('template.main')
@section('tittle')
Dashboard
@endsection
@section('head_css')
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
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

  .div-filter-year .btn-flat{
      border-radius: 5px!important;
      color: #999;
      font-weight: 400;
      width: 100%!important;
      background-color: #fff;
  }

  .div-filter-year .btn-flat i {
    color: lightgray;
  }

  .div-filter-year .btn-flat:active{
      color: black;
      font-weight: 500;
      width: 100%!important;
      background-color: #fff;
      border-color: #3c8dbc!important;
  }

  .div-filter-year .btn-flat:hover{
      color: black;
      font-weight: 500;
      width: 100%!important;
      background-color: #fff;
      border: 1px solid #3c8dbc!important;
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
  }

  .div-filter-year .btn-flat:hover i {
    color: slategray;
  }

  .div-filter-year .btn-flat.isClicked {
    color: black;
    font-weight: 500;
    width: 100%!important;
    background-color: #fff;
    border:3px solid #3c8dbc!important;
  }

  .div-filter-year .btn-flat.isClicked i {
    color: slategrey;
  }

  .div-filter-year .select2-container--default .select2-selection--single{
      border-radius: 5px!important;
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
  <div class="row" style="display: none;" id="divSelectYear">
    <div class="col-md-4">
      <div class="div-filter-year form-group">
          <button class="btn btn-flat btn-default" id="btnThisYear" onclick="clickYear(this.value)"><i class="fa fa-filter"></i> This Year</button>
      </div>
    </div>
    <div class="col-md-4">
      <div class="div-filter-year form-group">
          <button class="btn btn-flat btn-default" id="btnLastYear" onclick="clickYear(this.value)"><i class="fa fa-filter"></i> Last Year</button>
      </div>
    </div>
    <div class="col-md-4">
      <div class="div-filter-year form-group">
          <select class="select2 form-control" style="width: 100%!important;" id="selectYear" onchange="clickYear(this.value)"><option></option></select>
      </div>
    </div>
  </div>
 <!--  <div style="margin-bottom: 10px;display: none;" id="divSelectYear">
    <select class="form-control" id="selectYear" style="width: 100%!important;">
      <option></option>
    </select>
  </div> -->
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
            <tbody id="tbody-table-sip-win">
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
            <tbody id="tbody-table-msp-win">
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
            <canvas id="myDoughnutChart" width="100%" height="100%"></canvas>
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
            <canvas id="myDoughnutChart2" width="100%" height="100%"></canvas>
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
	        <p class="text-center">©SIMS - 2023</p>              
	      </div>
	    </div>
	  </div>
	</div>
</section>
@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection
@section('script')
<script type="text/javascript">
  let accesable = @json($feature_item);
  accesable.forEach(function(item,index){
    $("#" + item).show()
    $("." + item).show()
  })

  if("{{Auth::User()->isDefaultPassword}}" == 'true'){
      $("#changePassword").modal('show')
  }

  $(document).ready(function(){
    startTime()
    initiateSmallBox(moment().year(),"initiate")
  });

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
    let i = 0
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
  }


  function initiateSmallBox(year,status){
    $.ajax({
      type:"GET",
      url:"{{url('/getDashboardBox')}}?year="+year,
      success: function(result){
        var colors = []
        var prepend = ""  

        if ("{{Auth::User()->name == 'TECH HEAD'}}") {
            var ArrColors = [
              {name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"report_range/All?year="+ year},
              {name:'Occuring',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"report_range/OPEN?year="+ year},
              {name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"report_range/WIN?year="+ year},
              {name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"report_range/LOSE?year="+ year}
            ]
            colors.push(ArrColors)
        }else{
          var ArrColors = [
            {name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"report_range/ALL?year="+ year},
            {name:'Occuring',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"report_range/OPEN?year="+ year},
            {name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"report_range/WIN?year="+ year},
            {name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"report_range/LOSE?year="+ year}]
            colors.push(ArrColors)
        }

        $.each(colors[0], function(key, value){
          prepend = prepend + '<div class="col-lg-3 col-xs-6 clickDiv" data-value="'+ key +'" onclick="clickableDiv('+"'"+ value.url +"'"+')">'
          prepend = prepend + '<div class="small-box '+value.color+'">'
            prepend = prepend + '<div class="inner">'
              prepend = prepend + '<h3 class="counter" data-value="'+ key +'">'+value.count+'</h3>'
              prepend = prepend + '<p >'+value.name+'</p>'
            prepend = prepend + '</div>'
            prepend = prepend + '<div class="icon">'
              prepend = prepend + '<i class="'+value.icon+'"></i>'
            prepend = prepend + '</div>'
            prepend = prepend + '<a onclick="clickableDiv('+"'"+ value.url +"'"+')" class="small-box-footer clickDiv" data-value="'+ key +'">More info <i class="fa fa-arrow-circle-right"></i></a>'
          prepend = prepend + '</div>'
          prepend = prepend + '</div>'
        })

        if (status == "initiate") {
          $("#BoxId").prepend(prepend)
        }else{
          $.each(colors[0], function(key, value){
            $(".counter[data-value='"+ key +"']").text(value.count)
            $(".clickDiv[data-value='"+ key +"']").attr('onclick','clickableDiv('+"'"+ value.url +"'"+')')
          })
        }

        $('.counter').each(function () {
          var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
          $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
          }, {
            duration: 1000,
            step: function (func) {
               $(this).text(parseFloat(func).toFixed(size));
            }
          });
        });

        var counterValue = $(".counter").text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
        var targetValue = $(".counter").text().split(".")[1] ? $(this).text().split(".")[1].length : 0; // Change this to your desired final value
        var animationDuration = 2000; // Animation duration in milliseconds
        var intervalDuration = 20; // Interval duration for smooth animation

        var interval = setInterval(function() {
            counterValue += Math.ceil(targetValue / (animationDuration / intervalDuration));
            if (counterValue >= targetValue) {
                counterValue = targetValue;
                clearInterval(interval);
            }
            $(".counter").text(counterValue);
        }, intervalDuration);
      }
    })
  }
  
  function clickableDiv(url){
    window.location = "{{url('/project/index')}}/?status=" + url.split('/')[1]
    // localStorage.setItem('status_lead',url.split('/')[1])
  }

  function initiateTopWinTer(year,status){
    $.ajax({
      url:"{{url('/getTopWinSipTer')}}?year="+year,
      type:"GET",
      success:function(result){
        $("#tbody-table-sip-ter").empty()
        var append = ""
        var no = 1

        $.each(result, function(key, value){
          append = append + '<tr>'
            append = append + '<td>'+ no++ +'</td>'
            if ("{{Auth::User()->id_territory}}" == value.id_territory) {
              append = append + '<td>'+value.name+'</td>'
              // append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
            }else{
              append = append + '<td>'+value.name+'</td>'
            }
            append = append + '<td align="right">'
            append = append + '<i>'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
            append = append + '</td>'
            append = append + '<td><center>('+value.leads+')</center></td>'
          append = append + '</tr>'
        })

        $("#tbody-table-sip-ter").html(append)
      }
    })
  }

  function initiateTopWinEachTer(year,status){
    $.ajax({
      url:"{{url('/getTopWinSipTer')}}?year="+year,
      type:"GET",
      success:function(result){
        $("#table-win-project-territory").empty()
        var append = "",
        no = 1,
        territory = ""

        $.each(result, function(key, value){
          $.each(value, function(key, value){
            if(value.id_territory == territory){
              append = append + '<tr>'
                append = append + '<td>'+ no++ +'</td>'
                append = append + '<td>'+value.name+'</td>'
                // append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" target="_blank" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
                append = append + '<td align="right">'
                append = append + '<i>'+ new Intl.NumberFormat('id').format(value.deal_prices)+'</i>'
                append = append + '</td>'
                append = append + '<td><center>('+value.leads+')</center></td>'
              append = append + '</tr>'
            }else{
                territory = value.id_territory
                append = append + '<tr style="background-color:dodgerblue;color: white;">'
                    append = append + '<td colspan="2">'+ value.id_territory +'</td>'
                    append = append + '<td align="right">Rp.<i>'+ new Intl.NumberFormat('id').format(value.sum_total)+'</i></td>'
                    append = append + '<td style="text-align:center;">'+value.leads_total+'</td>'
                append = append + '</tr>'
              if(value.id_territory != "TOTAL"){
                append = append + '<tr>'
                  append = append + '<td>'+ no++ +'</td>'
                  append = append + '<td>'+value.name+'</td>'
                  // append = append + '<td>'+value.name+' <a href="{{url("/report_range")}}/'+value.nik+'" target="_blank" style="float: right;"><i class="fa fa-external-link-square"></i></a></td>'
                  append = append + '<td align="right">'
                  append = append + '<i>'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
                  append = append + '</td>'
                  append = append + '<td><center>('+value.leads+')</center></td>'
                append = append + '</tr>'
              }
            }
          });
        });
        $("#table-win-project-territory").html(append)
      }
    })
  }

  // initiateTableSipWin(moment().year())
  function initiateTableSipWin(year){
    $.ajax({
      url:"{{url('/getTopWinSip')}}?year="+year,
      type:"GET",
      success:function(result){
        $("#tbody-table-sip-win").empty()
          var append = ""
          var no = 1

        $.each(result, function(key, value){
          append = append + '<tr>'
            append = append + '<td>'+ no++ +'</td>'
            append = append + '<td>'+value.name+'</td>'
            append = append + '<td align="right">'
            append = append + '<i>'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
            append = append + '</td>'
            append = append + '<td><center>('+value.leads+')</center></td>'
          append = append + '</tr>'
        });

        $("#tbody-table-sip-win").html(append)
      }
    })
  }

  // initiateTableMspWin(moment().year())
  function initiateTableMspWin(year){
    $.ajax({
      url:"{{url('/getTopWinMsp')}}?year="+year,
      type:"GET",
      success:function(result){
        $("#tbody-table-msp-win").empty()
          var append = ""
          var no = 1

        $.each(result, function(key, value){
          append = append + '<tr>'
            append = append + '<td>'+ no++ +'</td>'
            append = append + '<td>'+value.name+'</td>'
            append = append + '<td align="right">'
            append = append + '<i>'+ new Intl.NumberFormat('id').format(value.deal_prices) +'</i>'
            append = append + '</td>'
            append = append + '<td><center>('+value.leads+')</center></td>'
          append = append + '</tr>'
        });

        $("#tbody-table-msp-win").html(append)
      }
    })
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

    // initiateAmountLead(moment().year())
    var initiateMybarChartByStatus = ''
    function initiateAmountLead(year){
      $.ajax({
        type:"GET",
        url:"getChartByStatus?year="+year,
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

          if (initiateMybarChartByStatus) {
            initiateMybarChartByStatus.destroy()
          }

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

          return initiateMybarChartByStatus = barChartByStatus
        }
      })   
    }

    // initiateTotalLead(moment().year())
    var initiateMyBarChart = ''
    function initiateTotalLead(year){
      if (initiateMyBarChart) {
        initiateMyBarChart.destroy()
      }

      $.ajax({
        type:"GET",
        url:"{{url('/getChart')}}?year="+year,
        success:function(result){
            var myBarChart = new Chart(ctx2, {
            type: 'bar',
            data: {
              labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
              datasets: [{
                label: "Lead Register "+year,
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

          return initiateMyBarChart = myBarChart
        }
      })
    }

    // initiateTotalAmountLead(moment().year())
    var initiateAreaChart = ''
    function initiateTotalAmountLead(year){
      if (initiateAreaChart) {
        initiateAreaChart.destroy()
      }
      
      $.ajax({
        type:"GET",
        url:"{{url('/getAreaChart2019')}}?year="+year,
        success:function(result){
          var AreaChart = new Chart(ctx14, {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
            datasets: [{
              label: "Amount "+year,
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

          return initiateAreaChart = AreaChart
        }
      })
    }
    
    // initiateStatusLead(moment().year())
    var initiateMyPieChart = ''
    function initiateStatusLead(year){
      if (initiateMyPieChart) {
        initiateMyPieChart.destroy()
      }

      $.ajax({
        type:"GET",
        url:"getPieChart?year="+year,
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

          return initiateMyPieChart = myPieChart
        }
      })

      $.ajax({
        type:"GET",
        url:"getPieChart?year="+year,
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
    }

    // initiateChartWinLose(moment().year())
    var initiateMyDoughnutChart = ''
    function initiateChartWinLose(year){
      if (initiateMyDoughnutChart) {
        initiateMyDoughnutChart.destroy()
      }
      $.ajax({
        type:"GET",
        url:"getDoughnutChart?year="+year,
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

          $.ajax({
            type:"GET",
            url:"getDoughnutChart?year="+year,
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

          return initiateMyDoughnutChart = myDoughnutChart
        }
      });
    }

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

    const d = new Date();
    let year = d.getFullYear();
    initiateSelectYear(year)

    function initiateSelectYear(year){
      $("#btnThisYear").val(year)
      $("#btnLastYear").val(year-1)
      clickYear(year)
      $.ajax({
        url:"{{url('/loopYear')}}",
        success:function(result){
          var othYear = []

          result.forEach(function(item){
            if (item.id != year && item.id != year-1) {
              othYear.push({id:item.id,text:item.text})
            }
          })

          $("#selectYear").select2({
            data:othYear,
            placeholder:"Other Year"
          })
        }
      })
    }

    function clickYear(year){
      if (year != "") {
        if ($("#btnThisYear").hasClass("isClicked")) {
            $("#btnThisYear").removeClass("isClicked")
        }else if ($("#btnLastYear").hasClass("isClicked")) {
            $("#btnLastYear").removeClass("isClicked")
        }

        if ($("#selectYear").val() != "") {
            if (year != $("#selectYear").val()) {
                $("#selectYear").val("").trigger("change")
            }

            if ($("#btnThisYear").val() == year) {
                $("#btnThisYear").addClass("isClicked")
            }else if ($("#btnLastYear").val() == year) {
                $("#btnLastYear").addClass("isClicked")
            }
        }else{
            if ($("#btnThisYear").val() == year) {
                $("#btnThisYear").addClass("isClicked")
            }else if ($("#btnLastYear").val() == year) {
                $("#btnLastYear").addClass("isClicked")
            }
        }

        initiateSmallBox(year,"filter")
        initiateTableSipWin(year)
        initiateTableMspWin(year)
        initiateChartWinLose(year)
        initiateStatusLead(year)
        initiateTotalAmountLead(year)
        initiateTotalLead(year)
        initiateAmountLead(year)

        if (accesable.includes('salesWinTerritory')) {
          initiateTopWinTer(year,"initiate")
          // initiateTopWinTer(moment().year(),"initiate")
        }

        if (accesable.includes('BoxTopWinTerritory')) {
          var top_win_sip_ter =  JSON.parse('@json($top_win_sip_ter)')
          initiateTopWinEachTer(year,"initiate")
          // initiateTopWinEachTer(moment().year(),"initiate")
        }
      }
    }
</script>
@endsection