@extends('template.template_admin-lte')
@section('content')
{{-- <style type="text/css">
  .dropdown-year{padding:25px 25px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}

  .dropdown li {
    font: bold 12px/18px sans-serif;
    display: inline-block;
    margin-right: -4px;
    position: relative;
    cursor: pointer;
    -webkit-transition: all 0.2s;
    -moz-transition: all 0.2s;
    -ms-transition: all 0.2s;
    -o-transition: all 0.2s;
    transition: all 0.2s;
  }

  .dropdown li:hover {/*
  background: #555;*/
  color: #fff;
  }

  .dropdown li ul {
  visibility: hidden;
  position: absolute;
  min-width: 140px;
  z-index: 1;
  left: auto;
  left: 0;
  -webkit-transiton: opacity 0.2s;
  -moz-transition: opacity 0.2s;
  -ms-transition: opacity 0.2s;
  -o-transition: opacity 0.2s;
  -transition: opacity 0.2s;
  }

  .dropdown li:last-child ul{
    right: 4px;
    left: auto;
  }

  .dropdown li ul li { 
  background: #555; 
  display: block; 
  color: #fff;
  text-shadow: 0 -1px 0 #000; 
  padding: 15px 20px;
  }

  .dropdown .content li:hover { background: #666; }
  .dropdown li:hover ul {
  display: block;
  opacity: 1;
  visibility: visible;
  }
</style> --}}

<style type="text/css">
  .row:before, .row:after{
    display: inline-block; !important;
  }

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
    background: #fcba03;
  }

  .inner-reset {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    padding: 2rem;
    font-size: 60px;
  }

</style>

<section class="content-header">
  <body onload="startTime()">
    <h4>
      
      <span id="waktu"></span>
    </h4>
  </body>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
</section>

<section class="content">

    <!-- LEAD REGISTER -->

        <div class="row mb-3">
        @if(Auth::User()->id_division == 'WAREHOUSE')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <p>
                  @if($counts < 1)
                  0
                  @else
                  {{$counts}}
                  @endif
                </h3>
                <p>Purchase Order</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('PrController@PoAdmin')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'STAFF GA')
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>
                  <div class="mr-5"> ?? </div>
                </h3>

                <p></p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ReportController@view_lead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
            @else
          @endif
        @elseif(Auth::User()->id_position == 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>{{ $counts }}</h3>

                <p> Claim Pending</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('ESMController@claim_admin')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        @elseif(Auth::User()->id_division == 'FINANCE')
          @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
          
           <div class="col-lg-4 col-xs-12">
          <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{ $counts }}</h3>
                <p>Lead Register</p>
              </div>
              <div class="icon">
                  <i class="fa fa-list"></i>
                </div>
              <a href="{{action('ReportController@view_lead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @else
            <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{ $counts }}</h3>
                <p>Lead Register</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('ReportController@view_lead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @else
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3 class="counter">
                  @if(Auth::User()->id_company == '1')
                  {{ $counts }}
                  @else
                  {{ $countmsp }}
                  @endif
                </h3>

                <p>Lead Register</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('ReportController@view_lead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @endif

        <!-- OPEN -->

        @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>
                  {{$idps}} 
                </h3>

                <p>Current Id Project</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3 class="counter">
                  {{$opens+$sds+$tps}}
                </h3>
                <p>Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-briefcase"></i>
              </div>
              <a href="{{action('ReportController@view_open')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'SALES' && Auth::User()->id_position == 'STAFF')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  0 
                </h3>

                <p>Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-briefcase"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  @if($opens < 1)
                  0
                  @else
                  {{$opens}}
                  @endif
                </h3>

                <p>Delivery Order</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('WarehouseProjectController@view_do')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  {{$opens}} 
                </h3>

                <p>Purchase Request</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('PrController@PrAdmin')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_territory == 'DPG' && Auth::User()->id_position == 'ENGINEER MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  0 
                </h3>

                <p>Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-briefcase"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_territory == 'DPG' && Auth::User()->id_position == 'ENGINEER STAFF')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  0 
                </h3>

                <p>Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-briefcase"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division != 'FINANCE')
          @if(Auth::User()->id_position == 'HR MANAGER')
          @elseif(Auth::User()->id_position == 'STAFF GA')
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  ??
                </h3>

                <p></p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @else
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3 class="counter">
                  {{$opens+$sds+$tps}}
                </h3>

                <p> Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-briefcase"></i>
              </div>
              <a href="{{action('ReportController@view_open')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @endif

        <!-- WIN -->

        @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3 class="counter">
                  {{ $wins }}
                </h3>

                <p>Win</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o "></i>
              </div>
              <a href="{{action('ReportController@view_win')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  @if($ba < 1)
                  0
                  @else
                  {{$ba}}
                  @endif
                </h3>

                <p>Inventory</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('WarehouseController@view_inventory')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  {{$sds}} 
                </h3>

                <p>Purchase Order</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('PrController@PoAdmin')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_territory == 'DPG')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  0 
                </h3>

                <p>Win</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o "></i>
              </div>
              <a href="{{action('ReportController@view_win')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  {{$wins}} 
                </h3>

                <p>Claim Pending</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('ESMController@claim_pending')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
          <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  {{$wins}} 
                </h3>

                <p>Claim Pending</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('ESMController@claim_pending')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'HR MANAGER')
            <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h3>
                  {{$wins}} 
                </h3>

                <p>Claim Pending</p>
              </div>
              @if($wins >= 1)
                <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_pending')}}">
              @elseif($wins < 1)
                <a class="card-footer text-white clearfix small z-1" href="#">
              @endif
              <div class="icon">
                <i class="fa-book"></i>
              </div>
              <a href="{{action('ESMController@claim_pending')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @elseif(Auth::User()->id_position == 'STAFF GA')
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h3>
                  ??
                </h3>

                <p></p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @else
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3 class="counter">
                  {{$wins}} 
                </h3>

                <p>Win</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o "></i>
              </div>
              <a href="{{action('ReportController@view_win')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @endif

        <!-- LOSE -->

        @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  {{$loses}} 
                </h3>

                <p>Claim Transfer</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
              </div>
              <a href="{{action('ESMController@claim_transfer')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
          <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  {{$loses}} 
                </h3>

                <p>Claim Transfer</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('ESMController@claim_transfer')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'HR MANAGER')
            <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>
                  {{$loses}} 
                </h3>

                <p>Claim Transfer</p>
              </div>
              @if($loses >= 1)
          <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_transfer')}}">
        @elseif($loses < 1)
          <a class="card-footer text-white clearfix small z-1" href="">
        @endif
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('ESMController@claim_transfer')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @elseif(Auth::User()->id_position == 'STAFF GA')
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>
                  ??
                </h3>

                <p></p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3 class="counter">
                  {{ $loses }}
                </h3>

                <p>Lose</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
              </div>
              <a href="{{action('ReportController@view_lose')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>
                  {{$loses}} 
                </h3>

                <p>Quote Number</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('QuoteController@report_quote')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>
                  @if($co < 1)
                  0
                  @else
                  {{$co}}
                  @endif
                </h3>

                <p>Asset</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <a href="{{action('WarehouseAssetController@view_asset')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position != 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>
                  0 
                </h3>

                <p>Lose</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
              </div>
              <a href="{{action('ReportController@view_lose')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @else
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3 class="counter">
                  <div class="mr-5">{{$loses}}</div>
                </h3>

                <p>Lose</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
              </div>
              <a href="{{action('ReportController@view_lose')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @endif
      </div>
    <!-- CHART -->

    @if(Auth::User()->id_division == 'HR')
        @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'STAFF GA')

          <div class="row">

            <div class="col-lg-8 col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Total Amount Claim</h3>

            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <canvas id="AreaChart2" ></canvas>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4 col-xs-12">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

          </div>
          <!-- /.box-header -->
            <div class="box-body">
              <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
            </div>
          <!-- ./box-body -->
          </div>
        </div>

        </div>
      
        <div class="row">

          <div class="col-lg-8 col-xs-12">
          <!-- Example Bar Chart Card-->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Total Claim</h3>

            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 col-xs-12">
                <canvas id="myBarChart3" ></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4 col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
            <h3 class="box-title">Status Claim</h3>
            </div>
          <!-- /.box-header -->
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
          <!-- ./box-body -->
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
          <!-- /.box-header -->
          <div class="box-body">
            <canvas id="AreaChart2"></canvas>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4 col-xs-12">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
            </div>
          <!-- ./box-body -->
          </div>
        </div>

        </div>
      
        <div class="row">

          <div class="col-lg-8 col-xs-12">
          <!-- Example Bar Chart Card-->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Total Claim</h3>

            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 col-xs-12">
                <canvas id="myBarChart3"></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4 col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
            <h3 class="box-title">Status Claim</h3>

            </div>
          <!-- /.box-header -->
            <div class="box-body">
              <canvas id="myPieChart2" width="100%" height="100%"></canvas>
            </div>
          <!-- ./box-body -->
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
	          <!-- /.box-header -->
	          <div class="box-body">
              <canvas id="AreaChart2"></canvas>
	          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Claim Pending/Claim Transfer</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-lg-12 col-xs-12">
                <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
              </div>
            </div>
          <!-- ./box-body -->
          </div>
        </div>

      </div>
          
      <div class="row">

        <div class="col-lg-8">
        <!-- Example Bar Chart Card-->
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
          <!-- ./box-body -->
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
	          <!-- /.box-header -->
		          <div class="col-lg-12 col-xs-12">
		            <canvas id="AreaChart2"></canvas>
		          </div>
	          <!-- ./box-body -->
	          </div>
	        </div>

	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-danger">
	          <div class="box-header with-border">
	          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

	          </div>
	          <!-- /.box-header -->
	            <div class="col-lg-12 col-xs-12">
	              <canvas id="DoughnutchartClaim" width="100%" height="100%"></canvas>
	            </div>
	          <!-- ./box-body -->
	          </div>
		    </div>
	    </div>
	          
	    <div class="row">

	        <div class="col-lg-8 col-xs-12">
	          <!-- Example Bar Chart Card-->
	          <div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Total Claim</h3>
	            </div>
	          <!-- /.box-header -->
	          <div class="box-body">
	            <div class="col-lg-12 col-xs-12">
	                <canvas id="myBarChart3"></canvas>
	            </div>
	          </div>
	          <!-- ./box-body -->
	          </div>
	        </div>

	        <div class="col-lg-4 col-xs-12">
	          <div class="box box-warning">
		          <div class="box-header with-border">
		          <h3 class="box-title">Status Claim</h3>

		          </div>
		          <!-- /.box-header -->
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
	          <!-- ./box-body -->
	          </div>
	        </div>

        </div>
        @endif
    @else

    <div class="row">
      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
      @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
        <div class="col-lg-6 col-xs-12">
      @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
        <div class="col-lg-12 col-xs-12">
      @endif
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
      <div class="col-lg-8 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount Lead Register (Deal Price)</h3>
          </div>
        <!-- /.box-header -->
        <div class="box-body">
          <canvas id="AreaChart2019"></canvas>
        </div>
        <!-- ./box-body -->
        </div>
      </div>

      <div class="col-lg-4 col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
        <h3 class="box-title">Win/Lose</h3>

        </div>
        <!-- /.box-header -->
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
        <!-- ./box-body -->
        </div>
      </div>
    </div>
      
    <div class="row">
      <div class="col-lg-8 col-xs-12">
        <!-- Example Bar Chart Card-->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Total Lead Register</h3>

          </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-lg-12 col-xs-12">
              <canvas id="myBarChart"></canvas>
            </div>
          </div>
        </div>
        <!-- ./box-body -->
        </div>
      </div>

      <div class="col-lg-4 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Status Lead Register</h3>

          </div>
        <!-- /.box-header -->
          <div class="box-body">
            <canvas id="myPieChart" width="100%" height="100%"></canvas>
          </div>
        <!-- ./box-body -->
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
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
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
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">ANNOUNCEMENT</h4>
            </div>
            <div class="modal-body">
              <center><h3 class="box-title"><b>SALES APP<b><br><i>(Tender Process)</i></h3></center>
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
              <a href="{{url('/profile_user')}}#changePassword"><span class="btn btn-info btn-block" style="border-radius: 24px">Change Password directly</span></a>
              <span data-dismiss="modal" style="cursor: pointer;"><h5 class="text-center" style="color: #00acd6">No, thanks</h5></span>
            </div>
            <div class="modal-footer">
              <p class="text-center">©SIMS - 2021</p>              
            </div>
          </div>
        </div>
      </div>

</section>

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script>

  $('.money').mask('000,000,000,000,000', {reverse: true});

  $(document).ready(function(){
    // $("#changePassword").modal('show')
    console.log("{{Auth::User()->isDefaultPassword}}")
    if("{{Auth::User()->isDefaultPassword}}" == 'true'){
      $("#changePassword").modal('show')
    }
        
  })

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

    $.ajax({
          type:"GET",
          url:"getAreaChart",
          success:function(result){
              var AreaChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
            datasets: [{
              label: "Amount 2018",
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
          url:"getChart",
          success:function(result){
              var myBarChart2 = new Chart(ctx3, {
          type: 'bar',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
            datasets: [{
              label: "Lead Register",
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