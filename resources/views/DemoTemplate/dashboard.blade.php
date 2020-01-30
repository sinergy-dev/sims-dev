@extends('template.template')
@section('content')
<style type="text/css">
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
</style>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
     <!--  <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/')}}">Dashboard</a>
        </li>
      </ol> -->

      <div class="dropdown-year">
        <div class="pull-left" style="margin-top: -10px;font-size: 14px;"><a href="{{url('/')}}">Dashboard</a></div>
        <div class="dropdown pull-right" style="margin-top: -10px">
            <li><i class='fa fa-ellipsis-v' style='font-size:24px;color:grey;cursor: pointer;'></i>
              <ul class="content">
                <li onclick="show2018()"><i class="fa fa-calendar"></i>&nbsp&nbsp2018</li>
                <li onclick="show2019()"><i class="fa fa-calendar"></i>&nbsp&nbsp2019</li>
              </ul>
           </li>
        </div>
      </div>

        <!-- LEAD REGISTER -->

        <div class="row mb-3">
        @if(Auth::User()->id_division == 'WAREHOUSE')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Lead Register</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lead')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'STAFF GA')
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-fw fa-list"></i>
                  </div>
                  <div class="mr-5"> ?? </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lead')}}">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            @else
          @endif
        @elseif(Auth::User()->id_position == 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{ $counts }} Claim Pending</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_admin')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'FINANCE')
          @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
        <div class="col-xl-4 col-sm-6 mb-4">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{ $counts }} Lead Register</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lead')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
          @else
          <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{ $counts }} Lead Register</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lead')}}"')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
          @endif
        @else
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fa fa-fw fa-list"></i>
                </div>
                @if(Auth::User()->id_company == '1')
                <div class="mr-5">{{ $counts }} Lead Register</div>
                @else
                <div class="mr-5">{{ $countmsp }} Lead Register</div>
                @endif
              </div>
              <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lead')}}">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fa fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        @endif

        <!-- OPEN -->

        @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{$idps}} Current Id Project</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Open</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'SALES' && Auth::User()->id_position == 'STAFF')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Open</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Open</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_open')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{$opens}} Purchase Request</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('PrController@PrAdmin')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_territory == 'DPG' && Auth::User()->id_position == 'ENGINEER MANAGER')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Open</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_open')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_territory == 'DPG' && Auth::User()->id_position == 'ENGINEER STAFF')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">0 Open</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_open')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division != 'FINANCE')
          @if(Auth::User()->id_position == 'HR MANAGER')
          @elseif(Auth::User()->id_position == 'STAFF GA')
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-fw fa-list"></i>
                  </div>
                  <div class="mr-5"> ?? </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_open')}}">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          @else
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-fw fa-list"></i>
                  </div>
                  <div class="mr-5">{{$opens+$sds+$tps}} Open</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_open')}}">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          @endif
        @endif

        <!-- WIN -->

        @if(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">0 Win</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_win')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">0 Win</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_win')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{$sds}} Purchase Order</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('PrController@PoAdmin')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_territory == 'DPG')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">0 Win</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_win')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white o-hidden h-100" style="background-color: #fd7e14!important">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              <div class="mr-5">{{$wins}} Claim Pending</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_pending')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
        <div class="col-xl-4 col-sm-6 mb-4">
          <div class="card text-white o-hidden h-100" style="background-color: #fd7e14!important">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              <div class="mr-5">{{$wins}} Claim Pending</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_pending')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'HR MANAGER')
              <div class="col-xl-6 col-sm-6 mb-6">
                <div class="card text-white o-hidden h-100" style="background-color: #6f42c1!important">
                  <div class="card-body">
                    <div class="card-body-icon">
                      <i class="fa fa-fw fa-close"></i>
                    </div>
                    <div class="mr-5">{{$wins}} Claim Pending</div>
                  </div>
                  @if($wins >= 1)
                  <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_pending')}}">
                  @elseif($wins < 1)
                  <a class="card-footer text-white clearfix small z-1" href="">
                  @endif  
                    <span class="float-left">View Details</span>
                    <span class="float-right">
                      <i class="fa fa-angle-right"></i>
                    </span>
                  </a>
                </div>
              </div>
          @elseif(Auth::User()->id_position == 'STAFF GA')
              <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white o-hidden h-100" style="background-color: #6f42c1!important">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-fw fa-close"></i>
                  </div>
                  <div class="mr-5"> ?? </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          @endif
        @else
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">{{$wins}} Win</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_win')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @endif

        <!-- LOSE -->

        @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">{{$loses}} Claim Transfer</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_transfer')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
        <div class="col-xl-4 col-sm-6 mb-4">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-check"></i>
              </div>
              <div class="mr-5">{{$loses}} Claim Transfer</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_transfer')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'HR')
          @if(Auth::User()->id_position == 'HR MANAGER')
            <div class="col-xl-6 col-sm-6 mb-6">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fa fa-fw fa-check"></i>
                </div>
                <div class="mr-5">{{$loses}} Claim Transfer</div>
              </div>
              @if($loses >= 1)
              <a class="card-footer text-white clearfix small z-1" href="{{action('ESMController@claim_transfer')}}">
              @elseif($loses < 1)
              <a class="card-footer text-white clearfix small z-1" href="">
              @endif
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fa fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
            @elseif(Auth::User()->id_position == 'STAFF GA')
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-fw fa-check"></i>
                  </div>
                  <div class="mr-5"> ?? </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          @endif
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              <div class="mr-5">0 Lose</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lose')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_position == 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">{{$loses}} Quote Number</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('QuoteController@report_quote')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_division == 'WAREHOUSE')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              <div class="mr-5">0 Lose</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lose')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @elseif(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position != 'ADMIN')
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              <div class="mr-5">0 Lose</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lose')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @else
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-close"></i>
              </div>
              @if(Auth::User()->id_company == '1')
              <div class="mr-5">{{$loses}} Lose</div>
              @else
              <div class="mr-5">{{$losemsp}} Lose</div>
              @endif
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{action('ReportController@view_lose')}}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        @endif
      </div>

      <!-- CHART -->

      @if(Auth::User()->id_division == 'HR')
        @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'STAFF GA')
          <div class="row">
            <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-area-chart"></i> Total Amount Claim</div>
                <div class="card-body">
                  <canvas id="AreaChart2" width="200" height="90"></canvas>
                </div>
              </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Claim Pending/Claim Transfer</div>
            <div class="card-body">
              <canvas id="DoughnutchartHR" width="100%" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Total Claim</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">
                  <canvas id="myBarChart3" width="200" height="100"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

            <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-pie-chart"></i> Status Claim</div>
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
            </div>
          </div>
        @endif
      @elseif(Auth::User()->id_position == 'ADMIN')
      <div class="row">
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-area-chart"></i> Total Amount Claim</div>
                <div class="card-body">
                  <canvas id="AreaChart2" width="200" height="90"></canvas>
                </div>
              </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Claim Pending/Claim Transfer</div>
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
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Total Claim</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">
                  <canvas id="myBarChart3" width="200" height="100"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Status Claim</div>
            <div class="card-body">
              <canvas id="myPieChart2" width="100%" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>
      @elseif(Auth::User()->id_division == 'FINANCE')
        @if(Auth::User()->id_position == 'MANAGER')
          <div class="row">
            <div class="col-lg-8">
              <div class="card mb-3">
                    <div class="card-header">
                      <i class="fa fa-area-chart"></i> Total Amount Claim</div>
                    <div class="card-body"> 
                      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        
                        <li class="nav-item">
                          <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">2019</a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">2018</a>
                        </li>

                      </ul>

                      <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                          @if($counts >= 1)
                          <canvas id="AreaChart2" width="200" height="90"></canvas>
                          @else
                          <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                          @endif
                        </div>

                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                           @if($counts >= 1)
                            <canvas id="AreaChart2018" width="200" height="90"></canvas>
                           @else
                            <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                           @endif
                        </div>

                      </div>
                    </div>

              </div>
            </div>

            <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-pie-chart"></i> Claim Pending/Claim Transfer</div>
                <div class="card-body">
                  @if($wins == 0)
                  <canvas id="Chartempty" width="100%" height="100"></canvas>
                  @else
                  <canvas id="DoughnutchartFinance" width="100%" height="100"></canvas>
                  @endif
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8">
              <!-- Example Bar Chart Card-->
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-bar-chart"></i> Total Lead Register</div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12 my-auto">
                      <canvas id="myBarChart" width="200" height="100"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                  <div class="card-header">
                    <i class="fa fa-pie-chart"></i> Status Claim</div>
                  <div class="card-body">
                    <canvas id="myPieChart2" width="100%" height="100"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-pie-chart"></i> Status Lead Register</div>
                <div class="card-body">
                  <canvas id="myPieChart" width="100%" height="100"></canvas>
                </div>
              </div>
            </div>
          </div> -->
        @else
          <div class="row">
            <div class="col-lg-8">
              <!-- Example Bar Chart Card-->
              <div class="card mb-3">
                    <div class="card-header">
                      <i class="fa fa-area-chart"></i> Total Amount Claim</div>
                    <div class="card-body"> 
                      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        
                        <li class="nav-item">
                          <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">2019</a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">2018</a>
                        </li>

                      </ul>

                      <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                          @if($counts >= 1)
                          <canvas id="AreaChart2" width="200" height="90"></canvas>
                          @else
                          <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                          @endif
                        </div>

                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                           @if($counts >= 1)
                            <canvas id="AreaChart2018" width="200" height="90"></canvas>
                           @else
                            <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                           @endif
                        </div>

                      </div>
                    </div>

              </div>
            </div>

            <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-pie-chart"></i> Claim Pending/Claim Transfer</div>
                <div class="card-body">
                  @if($wins == 0)
                  <canvas id="Chartempty" width="100%" height="100"></canvas>
                  @else
                  <canvas id="DoughnutchartFinance" width="100%" height="100"></canvas>
                  @endif
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8">
              <!-- Example Bar Chart Card-->
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-bar-chart"></i> Total Claim
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12 my-auto">
                      <canvas id="myBarChart3" width="200" height="100"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                  <div class="card-header">
                    <i class="fa fa-pie-chart"></i> Status Claim</div>
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
              </div>
            </div>

            <!-- <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fa fa-pie-chart"></i> Status Lead Register</div>
                <div class="card-body">
                  <canvas id="myPieChart" width="100%" height="100"></canvas>
                </div>
              </div>
            </div>
          </div> -->
        @endif
      @else
      <div class="row">
        <div class="col-lg-8">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-area-chart"></i> Total Amount Lead Register
            </div>

            <div class="card-body"> 
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                
                <li class="nav-item">
                  <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">2019</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">2018</a>
                </li>

              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                   @if($counts == 0)
                    <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                   @else
                    <canvas id="AreaChart2019" width="200" height="90"></canvas>
                   @endif
                </div>

                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                   @if($counts == 0)
                    <canvas id="AreaChartEmpty" width="200" height="90"></canvas>
                   @else
                    <canvas id="AreaChart" width="200" height="90"></canvas>
                   @endif
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Win/Lose</div>
            <div class="card-body" >
            @if(Auth::User()->id_company == '1')
              @if($wins + $loses == 0)
              <canvas id="Chartempty" width="100%" width="200" height="110"></canvas>
              @elseif($win2 + $lose2 == 0)
              <canvas id="Chartempty" width="100%" width="200" height="110"></canvas>
              @else
              <canvas id="myDoughnutChart" width="100%" width="200" height="110"></canvas>
              @endif
            @else
             <canvas id="myDoughnutChart" width="100%" width="200" height="110"></canvas>
            @endif
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Total Lead Register</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">
                  <canvas id="myBarChart" width="200" height="100"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Status Lead Register</div>
            <div class="card-body">
              <canvas id="myPieChart" width="100%" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>
      @endif
  </div>

    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

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
</div>
@endsection

@section('script')
<script>

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
              backgroundColor: "rgba(2,117,216,1)",
              borderColor: "rgba(2,117,216,1)",
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
              backgroundColor: "rgba(2,117,216,1)",
              borderColor: "rgba(2,117,216,1)",
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
              backgroundColor: "rgba(2,117,216,1)",
              borderColor: "rgba(2,117,216,1)",
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
                  x: 0,
                  y: 0
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