@extends('template.template_admin-lte')
@section('content')

<style type="text/css">
  .row:before, .row:after{
    display: inline-block; !important;
  }

  .icon{
    width: 90px;
  }
</style>

<section class="content-header">
    <h1>
      Dashboard Abal Abal Abal Abal Abal Abal Abal Abal Abal Abal Abal Abal
    </h1>
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
                <h3>0</h3>
                <p>Lead Register</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ReportController@view_lead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ESMController@claim_admin')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        @elseif(Auth::User()->id_division == 'FINANCE')
          @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
          
           <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{ $counts }}</h3>
                <p>Lead Register</p>
              </div>
              <div class="icon">
                  <i class="fa fa-fw fa-list"></i>
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
                <i class="ion ion-bag"></i>
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
                <h3>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>0</h3>
                <p>Open</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <i class="ion ion-bag"></i>
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
                  0 
                </h3>

                <p>Open</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @else
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3>
                  {{$opens+$sds+$tps}}
                </h3>

                <p> Open</p>
              </div>
              <div class="icon">
                <i class="fa fa-book"></i>
              </div>
              <a href="{{action('ReportController@view_open')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @endif

        <!-- WIN -->

        @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
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
                <i class="ion ion-bag"></i>
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
                  0 
                </h3>

                <p>Win</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ReportController@view_win')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ESMController@claim_pending')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <h3>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ESMController@claim_transfer')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
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
                <i class="ion ion-bag"></i>
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
                <i class="ion ion-bag"></i>
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
                  0 
                </h3>

                <p>Lose</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ReportController@view_lose')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{action('ReportController@view_lose')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @else
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>
                  @if(Auth::User()->id_company == '1')
          <div class="mr-5">{{$loses}}</div>
          @else
          <div class="mr-5">{{$losemsp}}</div>
          @endif
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

            <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Total Amount Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <canvas id="AreaChart2" width="200" height="90"></canvas>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($counts >= 1)
                <div class="card-body">
                  <canvas id="DoughnutchartAdmin" width="100%" height="100"></canvas>
                </div>
              @else
                <div class="card-body">
                  <canvas id="DoughnutchartHR" width="100%" height="100"></canvas>
                </div>
              @endif
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
              <h3 class="box-title">Total Claim</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                  <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 my-auto">
                <canvas id="myBarChart3" width="200" height="100"></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-warning">
            <div class="box-header with-border">
            <h3 class="box-title">Status Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($counts >= 1)
                <div class="card-body">
                  <canvas id="myPieChart2" width="100%" height="100"></canvas>
                </div>
              @else
                <div class="card-body">
                  <canvas id="myPieChartEmpty" width="100%" height="100"></canvas>
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
          
          <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Total Amount Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <canvas id="AreaChart2" width="200" height="90"></canvas>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($counts >= 1)
                <div class="card-body">
                  <canvas id="DoughnutchartAdmin" width="100%" height="100"></canvas>
                </div>
              @else
                <div class="card-body">
                  <canvas id="Chartempty" width="100%" height="100"></canvas>
                </div>
              @endif
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
              <h3 class="box-title">Total Claim</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                  <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 my-auto">
                <canvas id="myBarChart3" width="200" height="100"></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-warning">
            <div class="box-header with-border">
            <h3 class="box-title">Status Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
            <div class="box-body">
              <canvas id="myPieChart2" width="100%" height="100"></canvas>
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

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">

                <li class="active"><a href="#pills-profile" data-toggle="tab">2019</a></li>
                <li><a href="#pills-home" data-toggle="tab">2018</a></li>

              </ul>

              <div class="tab-content">

                <div class="tab-pane active" id="pills-profile">
                  @if($counts >= 1)
                    <canvas id="AreaChart2" width="200" height="90"></canvas>
                  @else
                    <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                  @endif
                </div>

                <div class="tab-pane" id="pills-home">
                  @if($counts >= 1)
                    <canvas id="AreaChart2018" width="200" height="90"></canvas>
                  @else
                    <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($wins == 0)
                <canvas id="Chartempty" width="100%" height="100"></canvas>
              @else
                <canvas id="DoughnutchartFinance" width="100%" height="100"></canvas>
              @endif
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

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                  <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 my-auto">
                <canvas id="myBarChart" width="200" height="100"></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-warning">
            <div class="box-header with-border">
            <h3 class="box-title">Status Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
            <div class="box-body">
              <canvas id="myPieChart2" width="100%" height="100"></canvas>
            </div>
          <!-- ./box-body -->
          </div>
        </div>

          </div>
        @else
            <div class="row">

              <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Total Amount Claim</h3>

              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <canvas id="AreaChart2" width="200" height="90"></canvas>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-danger">
          <div class="box-header with-border">
          <h3 class="box-title">Claim Pending/Claim Transfer</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($wins == 0)
                <canvas id="Chartempty" width="100%" height="100"></canvas>
              @else
                <canvas id="DoughnutchartFinance" width="100%" height="100"></canvas>
              @endif
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
              <h3 class="box-title">Total Claim</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                  <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12 my-auto">
                <canvas id="myBarChart3" width="200" height="100"></canvas>
              </div>
            </div>
          </div>
          <!-- ./box-body -->
          </div>
        </div>

        <div class="col-lg-4">
          <div class="box box-warning">
          <div class="box-header with-border">
          <h3 class="box-title">Status Claim</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
            <div class="box-body">
              @if($counts >= 1)
                <div class="card-body">
                  <canvas id="myPieChart2" width="100%" height="100"></canvas>
                </div>
              @else
                <div class="card-body">
                  <canvas id="myPieChartEmpty" width="100%" height="100"></canvas>
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
      <div class="col-lg-8">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount Lead Register</h3>

            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
              <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-wrench"></i></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">

          <ul class="nav nav-tabs">

            <li class="active"><a href="#pills-profile" data-toggle="tab">2019</a></li>
            <li><a href="#pills-home" data-toggle="tab">2018</a></li>

          </ul>

            <div class="tab-content">

              <div class="tab-pane active" id="pills-profile">
                @if($counts == 0)
                  <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                @else
                  <canvas id="AreaChart2019" width="200" height="90"></canvas>
                @endif
              </div>

              <div class="tab-pane" id="pills-home">
                @if($counts == 0)
                  <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                @else
                  <canvas id="AreaChart" width="200" height="90"></canvas>
                @endif
              </div>

            </div>
          </div>
        </div>
        <!-- ./box-body -->
        </div>
      </div>

      <div class="col-lg-4">
        <div class="box box-danger">
        <div class="box-header with-border">
        <h3 class="box-title">Win/Lose</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
              <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-wrench"></i></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
          <div class="box-body">
            @if(Auth::User()->id_company == '1')
              @if($wins + $loses == 0)
                <canvas id="Chartempty" width="100%" width="200" height="103"></canvas>
              @elseif($win2 + $lose2 == 0)
                <canvas id="Chartempty" width="100%" width="200" height="103"></canvas>
              @else
                <canvas id="myDoughnutChart" width="100%" width="200" height="103"></canvas>
              @endif
            @else
              <canvas id="myDoughnutChart" width="100%" width="200" height="103"></canvas>
            @endif
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

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
                <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12 my-auto">
              <canvas id="myBarChart" width="200" height="100"></canvas>
            </div>
          </div>
        </div>
        <!-- ./box-body -->
        </div>
      </div>

      <div class="col-lg-4">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Status Lead Register</h3>

            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
              <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-wrench"></i></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
        <!-- /.box-header -->
          <div class="box-body">
            <canvas id="myPieChart" width="100%" height="100"></canvas>
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

</section>

@endsection

@section('script')


<script>

  $(function () {
    "use strict";

    // AREA CHART
    var area = new Morris.Area({
      element: 'revenue-chart',
      resize: true,
      data: [
        {y: '2011 Q1', item1: 2666, item2: 2666},
        {y: '2011 Q2', item1: 2778, item2: 2294},
        {y: '2011 Q3', item1: 4912, item2: 1969},
        {y: '2011 Q4', item1: 3767, item2: 3597},
        {y: '2012 Q1', item1: 6810, item2: 1914},
        {y: '2012 Q2', item1: 5670, item2: 4293},
        {y: '2012 Q3', item1: 4820, item2: 3795},
        {y: '2012 Q4', item1: 15073, item2: 5967},
        {y: '2013 Q1', item1: 10687, item2: 4460},
        {y: '2013 Q2', item1: 8432, item2: 5713}
      ],
      xkey: 'y',
      ykeys: ['item1', 'item2'],
      labels: ['Item 1', 'Item 2'],
      lineColors: ['#a0d0e0', '#3c8dbc'],
      hideHover: 'auto'
    });

    // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
        {y: '2011 Q1', item1: 2666},
        {y: '2011 Q2', item1: 2778},
        {y: '2011 Q3', item1: 4912},
        {y: '2011 Q4', item1: 3767},
        {y: '2012 Q1', item1: 6810},
        {y: '2012 Q2', item1: 5670},
        {y: '2012 Q3', item1: 4820},
        {y: '2012 Q4', item1: 15073},
        {y: '2013 Q1', item1: 10687},
        {y: '2013 Q2', item1: 8432}
      ],
      xkey: 'y',
      ykeys: ['item1'],
      labels: ['Item 1'],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto'
    });

    //DONUT CHART
    var donut = new Morris.Donut({
      element: 'sales-chart',
      resize: true,
      colors: ["#3c8dbc", "#f56954", "#00a65a"],
      data: [
        {label: "Download Sales", value: 12},
        {label: "In-Store Sales", value: 30},
        {label: "Mail-Order Sales", value: 20}
      ],
      hideHover: 'auto'
    });
    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [
        {y: '2006', a: 100, b: 90},
        {y: '2007', a: 75, b: 65},
        {y: '2008', a: 50, b: 40},
        {y: '2009', a: 75, b: 65},
        {y: '2010', a: 50, b: 40},
        {y: '2011', a: 75, b: 65},
        {y: '2012', a: 100, b: 90}
      ],
      barColors: ['#00a65a', '#f56954'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['CPU', 'DISK'],
      hideHover: 'auto'
    });
  });

    var ctx = document.getElementById("AreaChart");
    var ctx2 = document.getElementById("myBarChart");
    var ctx3 = document.getElementById("myBarChart2");
    var ctx4 = document.getElementById("myBarChart3");
    var ctx5 = document.getElementById("myPieChart");
    var ctx6 = document.getElementById("myPieChart2");
    var ctx7 = document.getElementById("myDoughnutChart");
    var ctx8 = document.getElementById("DoughnutchartAdmin");
    var ctx9 = document.getElementById("AreaChart2");
    var ctx10 = document.getElementById("Chartempty");
    var ctx11 = document.getElementById("DoughnutchartHR");
    var ctx12 = document.getElementById("DoughnutchartFinance");
    var ctx13 = document.getElementById("AreaChartEmpty");
    var ctx14 = document.getElementById("AreaChart2019")
    var ctx15 = document.getElementById("myPieChartEmpty");
    var ctx16 = document.getElementById("AreaChart2018");
  
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
          },
        });
      }
    })

    $.ajax({
          type:"GET",
          url:"getAreaChartAdmin",
          success:function(result){
              var AreaChart2 = new Chart(ctx9, {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
            datasets: [{
              label: "Amount"+' '+Date("YYYY").substring(11,15),
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
          url:"getAreaChartAdmin2018",
          success:function(result){
              var AreaChart2018 = new Chart(ctx16, {
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

    $.ajax({
          type:"GET",
          url:"getDoughnutChartAFH",
          success:function(result){
              var DoughnutchartAdmin = new Chart(ctx8, {
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

    $.ajax({
          type:"GET",
          url:"getDoughnutChartAFH",
          success:function(result){
              var DoughnutchartHR = new Chart(ctx11, {
          type: 'doughnut',
          data: {
            labels: ["Claim Pending", "Claim Transfer"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            datasets: [{
              data: result,
              backgroundColor: ['#6f42c1','#04dda3'],
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
    })


    $.ajax({
          type:"GET",
          url:"getDoughnutChartAFH",
          success:function(result){
              var DoughnutchartFinance = new Chart(ctx12, {
          type: 'doughnut',
          data: {
            labels: ["Claim Pending", "Claim Transfer"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            datasets: [{
              data: result,
              backgroundColor: ['#fd7e14','#04dda3'],
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
    })
 
      new Chart(ctx10, {
          type: 'doughnut',
          data: {
            datasets: [
              {
                label: "",
                backgroundColor: ['grey', 'grey'],
                data: [0,1]
              }
            ]
          },
          options: {
            tooltips: {
                enabled: false,
          },
        },
      });


      new Chart(ctx13, {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "Oktober", "November", "Desember"],
            datasets: [{
              label: "Amount"+' '+Date("YYYY").substring(11,15),
              lineTension: 0.3,
              backgroundColor: "grey",
              borderColor: "grey",
              pointRadius: 5,
              pointBackgroundColor: "grey",
              pointBorderColor: "grey",
              pointHoverRadius: 5,
              pointHoverBackgroundColor: "grey",
              pointHitRadius: 20,
              pointBorderWidth: 2,
              data: [{
                  x: 10,
                  y: 20
              }, {
                  x: 15,
                  y: 10
              }],
            }],
          },
          options: {
            tooltips: {
                enabled: false
          },
        },
      });

      new Chart(ctx15, {
          type: 'pie',
          data: {
            datasets: [
              {
                label: "",
                backgroundColor: ['grey', 'grey'],
                data: [0,1]
              }
            ]
          },
          options: {
            tooltips: {
                enabled: false,
          },
        },
      });

    function show2018(){
      document.getElementById('lead_2018').style.display = "block";
      document.getElementById('lead_2019').style.display = "none";
      document.getElementById('open_2018').style.display = "block";
      document.getElementById('open_2019').style.display = "none";
      document.getElementById('win_2018').style.display = "block";
      document.getElementById('win_2019').style.display = "none";
      document.getElementById('lose_2018').style.display = "block";
      document.getElementById('lose_2019').style.display = "none";
    }

    function show2019(){
      document.getElementById('lead_2018').style.display = "none";
      document.getElementById('lead_2019').style.display = "block";
      document.getElementById('open_2018').style.display = "none";
      document.getElementById('open_2019').style.display = "block";
      document.getElementById('win_2018').style.display = "none";
      document.getElementById('win_2019').style.display = "block";
      document.getElementById('lose_2018').style.display = "none";
      document.getElementById('lose_2019').style.display = "block";
    }
  
</script>

@endsection