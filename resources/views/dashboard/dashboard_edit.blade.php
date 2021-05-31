@extends('template.main')
@section('head_css')
<style type="text/css">
/*  .row:before, .row:after{
    display: inline-block; !important;
  }*/

  .icon{
    width: 90px;
  }
  
  .table-sip tbody tr:first-child td {
      background-color: #ffd324;
  }

  .table-msp tbody tr:first-child td {
      background-color: #ffd324;
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
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active" >Dashboard</li>
  </ol>
</section>
<section class="content">
	<!--Box-->
	<div class="row" id="BoxId">
        <!-- ./col -->
    </div>

    <!--Chart-->
  @if(Auth::User()->id_division == 'HR')
    @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'STAFF GA')
	    <div class="row">
	        <div class="col-lg-8 col-xs-12">
	          <div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Amount Claim</h3>
	            </div>
	            <div class="box-body">
	              <canvas id="AreaChart2"></canvas>
	            </div>
	          </div>
	        </div>

	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-danger">
	            <div class="box-header with-border">
	              <h3 class="box-title">Claim Pending/Claim Transfer</h3>
	            </div>
	            <div class="box-body">
	              <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
	            </div>
	          </div>
	        </div>
	    </div>
	    <div class="row">
        <div class="col-lg-8 col-xs-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Total Claim</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12 col-xs-12">
                  <canvas id="myBarChart3"></canvas>
                </div>
              </div>
            </div>
          </div>
    	</div>

        <div class="col-lg-4 col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Status Claim</h3>
            </div>
            <div class="box-body">
              @if($counts >= 1)
                <div class="card-body">
                  <canvas id="myPieChart2"></canvas>
                </div>
              @else
                <div class="card-body">
                  <canvas id="myPieChartEmpty"></canvas>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif
  @elseif(Auth::User()->id_position == 'ADMIN')
	    <div class="row">
	      <div class="col-lg-8 col-xs-12">
	        <div class="box box-primary">
	          <div class="box-header with-border">
	            <h3 class="box-title">Total Amount Claim</h3>
	          </div>
	          <div class="box-body">
	            <canvas id="AreaChart2"></canvas>
	          </div>
	        </div>
	      </div>

	      <div class="col-lg-4 col-xs-12">
	        <div class="box box-danger">
	          <div class="box-header with-border">
	            <h3 class="box-title">Claim Pending/Claim Transfer</h3>
	          </div>
	          <div class="box-body">
	            <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="row">
	      <div class="col-lg-8 col-xs-12">
	        <div class="box box-success">
	          <div class="box-header with-border">
	            <h3 class="box-title">Total Claim</h3>
	          </div>
	          <div class="box-body">
	            <div class="row">
	              <div class="col-sm-12 col-xs-12">
	                <canvas id="myBarChart3"></canvas>
	              </div>
	            </div>
	          </div>
	        </div>
	      </div>
	      <div class="col-lg-4 col-xs-12">
	        <div class="box box-warning">
	          <div class="box-header with-border">
	            <h3 class="box-title">Status Claim</h3>
	          </div>
	          <div class="box-body">
	            <canvas id="myPieChart2" width="100%" height="100%"></canvas>
	          </div>
	        </div>
	      </div>
	    </div>
  @elseif(Auth::User()->id_division == 'FINANCE')
    @if(Auth::User()->id_position == 'MANAGER')
	    <div class="row">
	        <div class="col-lg-8">
	          <div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Amount Claim</h3>
	            </div>
	            <div class="box-body">
	              <canvas id="AreaChart2"></canvas>
	            </div>
	          </div>
	        </div>
	        <div class="col-lg-4">
	          <div class="box box-danger">
	            <div class="box-header with-border">
	              <h3 class="box-title">Claim Pending/Claim Transfer</h3>
	            </div>
	            <div class="box-body">
	              <div class="col-lg-12 col-xs-12">
	                <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
	              </div>
	            </div>
	          </div>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-lg-8">
	          <div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Lead Register</h3>
	            </div>
	            <div class="box-body">
	            <div class="row">
	              <div class="col-lg-12 col-xs-12">
	                <canvas id="myBarChart" ></canvas>
	                </div>
	              </div>
	            </div>
	          </div>
	        </div>
	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-warning">
	            <div class="box-header with-border">
	              <h3 class="box-title">Status Claim</h3>
	            </div>
	            <div class="box-body">
	              <div class="col-lg-12 col-xs-12">
	                <canvas id="myPieChart2" width="100%" height="100%"></canvas>
	              </div>
	            </div>
	          </div>
	        </div>
	    </div>
    @else
	  <div class="row mb-3">
	        <div class="col-lg-8 col-xs-12">
	          <div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Amount Claim</h3>
	            </div>
	            <div class="col-lg-12 col-xs-12">
	              <canvas id="AreaChart2"></canvas>
	            </div>
	          </div>
	        </div>
	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-danger">
	            <div class="box-header with-border">
	              <h3 class="box-title">Claim Pending/Claim Transfer</h3>
	            </div>
	            <div class="col-lg-12 col-xs-12">
	              <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
	            </div>
	          </div>
	        </div>
	  </div>
	  <div class="row">
	        <div class="col-lg-8 col-xs-12">
	          <div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Claim</h3>
	            </div>
	            <div class="box-body">
	              <div class="col-lg-12 col-xs-12">
	                <canvas id="myBarChart3"></canvas>
	              </div>
	            </div>
	          </div>
	        </div>
	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-warning">
	            <div class="box-header with-border">
	              <h3 class="box-title">Status Claim</h3>
	            </div>
	            <div class="box-body">
	              @if($counts >= 1)
	                <div class="col-lg-12 col-xs-12">
	                  <canvas id="myPieChart2"></canvas>
	                </div>
	              @else
	                <div class="col-lg-12 col-xs-12">
	                  <canvas id="myPieChartEmpty"></canvas>
	                </div>
	              @endif
	            </div>
	          </div>
	        </div>
	  </div>
    @endif
  @else
    <div class="row">
		@if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
        <div class="col-lg-6 col-xs-12">
          	<div class="box box-primary">
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
                      <td>{{ $tops->name }}</td>
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
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
        <div class="col-lg-12 col-xs-12">
        	<div class="box box-primary">
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
                      <td>{{ $tops->name }}</td>
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
        @endif
      @endif

      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
        <div class="col-lg-6 col-xs-12">
          <div class="box box-primary">
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
                        <td>{{ $topm->name }}</td>
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
      @endif    	      
    </div>
    <div class="row">
      	<div class="col-lg-6 col-xs-12">
	        <div class="box box-primary">
	          <div class="box-header with-border">
	            <h3 class="box-title">Total Amount Lead Register (Deal Price)</h3>
	          </div>
	          <div class="box-body">
	            <canvas id="AreaChart2019"></canvas>
	          </div>
	        </div>
      	</div>

      	<div class="col-lg-6 col-xs-12">
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
    <div class="row">
    	<div class="col-lg-6 col-xs-12">
	        <div class="box box-warning">
	          <div class="box-header with-border">
	            <h3 class="box-title">Total Amount Lead Register (By Status)</h3>
	          </div>
	          <div class="box-body">
	            	<canvas id="barChartByStatus"></canvas>
			  </div>
          </div>
        </div>

      	<div class="col-lg-3 col-xs-12">
        	<div class="box box-danger">
          		<div class="box-header with-border">
            		<h3 class="box-title">Win/Lose</h3>
          		</div>
	          	<div class="box-body">
	            @if(Auth::User()->id_company == '1')
	              @if($wins + $loses == 0)
	                <canvas id="Chartempty" height="100%"></canvas>
	              @elseif($win2 + $lose2 == 0)
	                <canvas id="Chartempty" height="100%"></canvas>
	              @else
	                <canvas id="myDoughnutChart" width="100%" height="100%"></canvas>
	              @endif
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
  @endif
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
  	$('.money').mask('000,000,000,000,000', {reverse: true});

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
		    		{name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"view_lead"},
		    		{name:'Open',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"view_open"},
		    		{name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"view_win"},
		    		{name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"view_lose"}
		    	]
		    	colors.push(ArrColors)
			}else{
				var ArrColors = [
					{name:'Lead Register',color:'bg-aqua',icon:'fa fa-list',count:result.lead,url:"view_lead"},
					{name:'Open',color:'bg-orange',icon:'fa fa-book',count:result.open,url:"view_open"},
					{name:'Win',color:'bg-green',icon:'fa fa-calendar-check-o',count:result.win,url:"view_win"},
					{name:'Lose',color:'bg-red',icon:"fa fa-calendar-times-o",count:result.lose,url:"view_lose"}]
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

   	var ctx = document.getElementById("AreaChart");
    var ctx2 = document.getElementById("myBarChart");
    var ctx3 = document.getElementById("myBarChart2");
    var ctx4 = document.getElementById("myBarChart3");
    var ctx5 = document.getElementById("myPieChart");
    var ctx6 = document.getElementById("myPieChart2");
    var ctx7 = document.getElementById("myDoughnutChart");
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
    		console.log(result)
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

    		var babarChartByStatus = new Chart(ctx17, {
			    type: 'bar',
			    data: {
		            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
		            labels2:[amount_INITIAL,amount_OPEN,amount_SD,amount_TP,amount_WIN,amount_LOSE],	    	
				    datasets: [{
				        label: "INITIAL",
			            backgroundColor: "#7735a3",
			            borderColor: "#7735a3",
				        data: INITIAL,
				    },
				    {
				        label: "OPEN",
			            backgroundColor: "#f2562b",
			            borderColor: "#f2562b",
				        data: OPEN
				    },
				    {
				        label: "SD",
			            backgroundColor: "#04dda3",
			            borderColor: "#04dda3",
				        data: SD
				    },
				    {
				        label: "TP",
			            backgroundColor: "#f7e127",
			            borderColor: "#f7e127",
				        data: TP
				    },
				    {
				        label: "WIN",
			            backgroundColor: "#246d18",
			            borderColor: "#246d18",
				        data: WIN
				    },
				    {
				        label: "LOSE",
			            backgroundColor: "#e5140d",
			            borderColor: "#e5140d",
				        data: LOSE
				    }
				    ]
				},
				options: {
				    tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          // return data.datasets[tooltipItem.datasetIndex].label
				          // return data['datasets'][tooltipItem['index']];
				        },
				        label: function(tooltipItem, data) {
				          console.log(data)
				          // return data['labels2'][tooltipItem.datasetIndex][tooltipItem['index']];
				          // return data['labels2'][tooltipItem.datasetIndex][tooltipItem['index']].label;
				          return data.datasets[tooltipItem.datasetIndex].label + ' : Rp.' + data['labels2'][tooltipItem.datasetIndex][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ; Total : ' + data['datasets'][tooltipItem.datasetIndex]['data'][tooltipItem['index']]
				        },
				        afterLabel: function(tooltipItem, data) {
				          // var dataset = data['datasets'][0];
				          // var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				          // return '(' + percent + '%)';
				        }
				      }
				    },
				    scales: {
				          	xAxes: [{
				          		barPercentage: 0.10,
				             	barThickness: 7,
				             	gridLines: {
									display:false
								}
				        }]
			        }
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
	          }
	        });
        }
    })


    @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'FINANCE' || Auth::User()->id_division == 'HR')
      $.ajax({
            type:"GET",
            url:"getPieChartAFH",
            success:function(result){
                var myPieChart2 = new Chart(ctx6, {
            type: 'pie',
            data: {
              labels: ["ADMIN", "HRD","FINANCE","TRANSFER"],
              indexLabel: "#percent%",
              percentFormatString: "#0.##",
              toolTipContent: "{y}(#percent%)",
              datasets: [{
                data: result,
                backgroundColor: ['#dc3545', '#6f42c1', '#fd7e14', '#04dda3'],
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
          url:"getDoughnutChartAFH",
          success:function(result){
              var DoughnutchartClaim = new Chart(ctx8, {
          type: 'doughnut',
          data: {
            labels: ["Claim Pending", "Claim Transfer"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            datasets: [{
              data: result,
              backgroundColor: ['#e5140d','#04dda3'],
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
                    return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';},},
            },
          },
          });
        }
      });

      @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'FINANCE')

        $.ajax({
              type:"GET",
              url:"getChartAdmin",
              success:function(result){
                  var myBarChart3 = new Chart(ctx4, {
              type: 'bar',
              data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
                datasets: [{
                  label: "Claim",
                  backgroundColor: "#00a65a",
                  borderColor: "#00a65a",
                  data: result,
                }],
              },
            });
          }
        })


        $.ajax({
              type:"GET",
              url:"getAreaChartClaim",
              success:function(result){
                  var AreaChart2 = new Chart(ctx9, {
              type: 'line',
              height:500,
              data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
                datasets: [{
                  label: "Amount Claim"+' '+Date("YYYY").substring(11,15),
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
               label: 'mylabel',
               callbacks: {
                   label: function(tooltipItem, data) {
                       return "Rp." + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }, },
                },
              },
            });
          }
        })

        $.ajax({
          type:"GET",
          url:"getDoughnutChartAFH",
          success:function(result){
              var DoughnutchartClaim = new Chart(ctx8, {
          type: 'doughnut',
          data: {
            labels: ["Claim Pending", "Claim Transfer"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            datasets: [{
              data: result,
              backgroundColor: ['#e5140d','#04dda3'],
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
                    return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';},},
            },
          },
          });
        }
      });
      @elseif(Auth::User()->id_position == 'HR')
      @endif

      
    @else 

    // $.ajax({
    //   type:"GET",
    //   url:"getAreaChart",
    //   success:function(result){
    //     var AreaChart = new Chart(ctx, {
    //       type: 'line',
    //       data: {
    //         labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
    //         datasets: [{
    //           label: "Amount 2018",
    //           lineTension: 0.3,
    //           backgroundColor: "rgba(2,117,216,0.2)",
    //           borderColor: "rgba(2,117,216,1)",
    //           pointRadius: 5,
    //           pointBackgroundColor: "rgba(2,117,216,1)",
    //           pointBorderColor: "rgba(255,255,255,0.8)",
    //           pointHoverRadius: 5,
    //           pointHoverBackgroundColor: "rgba(2,117,216,1)",
    //           pointHitRadius: 20,
    //           pointBorderWidth: 2,
    //           data: result,
    //         }],
    //       },
    //       options: {
    //         legend: {
    //           display: true
    //         },
    //         tooltips: {
    //           mode: 'label',
    //           label: 'Rp',
    //           callbacks: {
    //             label: function(tooltipItem, data) {
    //               return "Rp." + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
    //             }
    //           },
    //         },
    //         scales: {
    //           yAxes: [{
    //             ticks: {
    //               beginAtZero:true,
    //               userCallback: function(value, index, values) {
    //                 // Convert the number to a string and splite the string every 3 charaters from the end
    //                 value = value.toString();
    //                 value = value.split(/(?=(?:...)*$)/);
    //                 value = value.join(',');
    //                 return value;
    //               }
    //             }
    //           }],
    //           xAxes: [{
    //             ticks: {
    //             }
    //           }]
    //         },
    //       },
    //     });
    //   }
    // })

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

    
    // $.ajax({
    //       type:"GET",
    //       url:"getChart",
    //       success:function(result){
    //           var myBarChart2 = new Chart(ctx3, {
    //       type: 'bar',
    //       data: {
    //         labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
    //         datasets: [{
    //           label: "Lead Register",
    //           backgroundColor: "#00a65a",
    //           borderColor: "#00a65a",
    //           data: result,
    //         }],
    //       },
    //       options: {
    //       }
    //     });
    //   }
    // })


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
          legend: {
            display: true
            },
          tooltips: {
           mode: 'label',
           label: 'mylabel',
           callbacks: {
            label: function(tooltipItem, data) {
                    return data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';},},
            },
          },
        });
      }
    });
    @endif

    @if (Auth::User()->id_division == 'SALES') {
      if (sessionStorage.getItem('dontLoad') == null){
          $("#popUp").modal("show");
        }
        sessionStorage.setItem('dontLoad', 'true');
      }
    @endif

    $(document).keyup(function(e) {
      if (e.keyCode == 27) {
          $('#popUp').modal('hide');
      }
    });

</script>
@endsection