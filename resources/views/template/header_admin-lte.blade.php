<header class="main-header">
  <style type="text/css">

    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
  </style>

  <!-- Logo -->
  <a href="{{url('/')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><img src="../img/siplogooke.png" alt="cobaaa" width="30px" height="40px"></img></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>SIMS</b>APP</span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Notifications: style can be found in dropdown.less -->
        @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'FINANCE')
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning"><small><i class="fa fa-fw fa-circle"></i></small></span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">New Notifications:</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu">
                <li>
                  @foreach($notifClaim as $data)
                    @if(Auth::User()->id_position == 'ADMIN')
                      <a class="dropdown-item" href="{{ url('/esm') }}">
                        <span class="text-lose">
                          <strong>
                            <i class="fa fa-long-arrow-up fa-fw"></i>New Claim!</strong>
                            <br>
                        </span>
                        <span>
                          <strong hidden> {{ $data->nik_admin }} </strong>
                          <strong hidden> {{ $data->personnel }} </strong>
                          <strong> <i class="fa fa-circle"></i> {{ $data->type }} </strong>
                        </span><br>
                        <div class="dropdown-message small"></div>
                      </a>
                    @elseif(Auth::User()->id_position == 'HR MANAGER')
                      <a class="dropdown-item" href="{{ url('/esm') }}">
                        <span class="text-initial">
                          <strong>
                            <i class="fa fa-long-arrow-up fa-fw"></i>New Claim!</strong>
                            <br>
                        </span>
                        <span>
                          <strong hidden> {{ $data->nik_admin }} </strong>
                          <strong hidden> {{ $data->personnel }} </strong>
                          <strong> <i class="fa fa-circle"></i> {{ $data->type }} </strong>
                        </span><br>
                        <div class="dropdown-message small"></div>
                      </a>
                    @elseif(Auth::User()->id_division == 'FINANCE')
                      <a class="dropdown-item" href="{{ url('/esm') }}">
                        <span class="text-open">
                          <strong>
                            <i class="fa fa-long-arrow-up fa-fw"></i>New Claim!</strong>
                            <br>
                        </span>
                        <span>
                          <strong hidden> {{ $data->nik_admin }} </strong>
                          <strong hidden> {{ $data->personnel }} </strong>
                          <strong> <i class="fa fa-circle"></i> {{ $data->type }} </strong>
                        </span><br>
                        <div class="dropdown-message small"></div>
                      </a>
                    @endif
                  @endforeach
                </li>
              </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
          </ul>
        </li>
        @elseif(Auth::user()->id_position != 'HR' && Auth::user()->id_position != 'ENGINEER' )
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning"><small><i class="fa fa-fw fa-circle"></i></small></span>
            {{-- @if(Auth::User()->email == 'tech@sinergy.co.id')
            	<span class="label label-warning">{{$notif->count()}}</span>
            @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
              <span class="label label-warning">{{$notif->count()}}</span>
            @else
              <span class="label label-warning">{{$notif->where('nik', Auth::User()->nik)->count()}}</span>
            @endif --}}
          </a>
          <ul class="dropdown-menu">
            <li class="header">New Notifications:</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu">
                <li>
                  @if(Auth::User()->id_territory != 'DVG')

                  @foreach($notif as $data)
	                  @if($data->nik == Auth::User()->nik && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
	                  <a class="dropdown-item" href="{{ url('/project') }}">
	                    <span class="text-initial">
	                      <strong>
	                        <i class="fa fa-long-arrow-up fa-fw"></i>Created Lead Register</strong>
	                        <br>
	                    </span>
	                    <span>
	                      <strong hidden>{{$data->nik}}</strong>
	                      <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                    </span><br>
	                    <div class="dropdown-message small"></div>
	                  </a>
	                  @elseif($data->nik == Auth::User()->nik && Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
	                  <a class="dropdown-item" href="{{ url('/project') }}">
	                    <span class="text-initial">
	                      <strong>
	                        <i class="fa fa-long-arrow-up fa-fw"></i>Created Lead Register</strong>
	                        <br>
	                    </span>
	                    <span>
	                      <strong hidden>{{$data->nik}}</strong>
	                      <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                    </span><br>
	                    <div class="dropdown-message small"></div>
	                  </a>
	                  @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                  <a class="dropdown-item" href="{{ url('/project') }}">
	                    <span class="text-initial">
	                      <strong>
	                        <i class="fa fa-long-arrow-up fa-fw"></i>Created Lead Register</strong>
	                        <br>
	                    </span>
	                    <span>
	                      <strong hidden>{{$data->nik}}</strong>
	                      <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                    </span><br>
	                    <div class="dropdown-message small"></div>
	                  </a>
	                  @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
	                  <a class="dropdown-item" href="{{ url('/project') }}">
	                    <span class="text-initial">
	                      <strong>
	                        <i class="fa fa-long-arrow-up fa-fw"></i>Created Lead Register</strong>
	                        <br>
	                    </span>
	                    <span>
	                      <strong hidden>{{$data->nik}}</strong>
	                      <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                    </span><br>
	                    <div class="dropdown-message small"></div>
	                  </a>
	                  @elseif(Auth::User()->id_position == 'DIRECTOR')
	                  <a class="dropdown-item" href="{{ url('/project') }}">
	                    <span class="text-initial">
	                      <strong>
	                        <i class="fa fa-long-arrow-up fa-fw"></i>Created Lead Register</strong>
	                        <br>
	                    </span>
	                    <span>
	                      <strong hidden>{{$data->nik}}</strong>
	                      <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                    </span><br>
	                    <div class="dropdown-message small"></div>
	                  </a>
	                  @endif
                  @endforeach
                  
                  @foreach($notifOpen as $data)
	                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-open">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Open Status</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-open">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Open Status</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-open">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Open Status</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-open">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Open Status</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong><i  class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}...</strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @endif
                  @endforeach

                  @foreach($notifsd as $data)
	                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-sd">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Solution Design</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-sd">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Solution Design</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,16)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-sd">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Solution Design</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-sd">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Solution Design</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,16)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @endif
                  @endforeach

                  @foreach($notiftp as $data)
	                    @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-tp">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Tender Project</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'TECHNICAL PRESALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-tp">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Tender Project</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,16)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-tp">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Tender Project</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,18)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES')
	                      @if($data->nik == Auth::User()->nik)
	                        <a class="dropdown-item" href="{{url('detail_project',$data->lead_id)}}">
	                          <span class="text-tp">
	                            <strong>
	                              <i class="fa fa-long-arrow-up fa-fw"></i>Tender Project</strong>
	                              <br>
	                          </span>
	                          <span>
	                            <strong hidden>{{$data->nik}},{{$data->lead_id}}</strong>
	                            <strong> <i class="fa fa-circle"></i>&nbsp {!!substr($data->opp_name,0,16)!!}... </strong>
	                          </span><br>
	                          <div class="dropdown-message small"></div>
	                        </a>
	                      @endif
	                    @endif
                  @endforeach

                  @endif
                </li>
              </ul>
              <!-- <ul class="menu">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                    page and may cause design problems
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-users text-red"></i> 5 new members joined
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-user text-red"></i> You changed your username
                  </a>
                </li>
              </ul> -->
            </li>
            <li class="footer"><a href="{{url('project')}}">View all</a></li>
          </ul>
        </li>
        @endif
        <!-- Tasks: style can be found in dropdown.less -->
        <!-- <li class="dropdown tasks-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-flag-o"></i>
            <span class="label label-danger">9</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 9 tasks</li>
            <li>
              <ul class="menu">
                <li>
                  <a href="#">
                    <h3>
                      Design some buttons
                      <small class="pull-right">20%</small>
                    </h3>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                           aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">20% Complete</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <h3>
                      Create a nice theme
                      <small class="pull-right">40%</small>
                    </h3>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"
                           aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">40% Complete</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <h3>
                      Some task I need to do
                      <small class="pull-right">60%</small>
                    </h3>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"
                           aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">60% Complete</span>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <h3>
                      Make beautiful transitions
                      <small class="pull-right">80%</small>
                    </h3>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
                           aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">80% Complete</span>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer">
              <a href="#">View all tasks</a>
            </li>
          </ul>
        </li> -->
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if(Auth::User()->gambar == NULL)
              <img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="user-image" alt="Yuki">
            @else
              <img src="{{asset('image/'.Auth::User()->gambar)}}" class="user-image" alt="User Image">
            @endif
            <span class="hidden-xs">{{ Auth::User()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              {{-- <img src="{{asset('template2/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image"> --}}
              @if(Auth::User()->gambar == NULL)
                <img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="img-circle" alt="Yuki">
              @else
                <img src="{{asset('image/'.Auth::User()->gambar)}}" class="img-circle" alt="User Image">
              @endif

              <p>
                {{ Auth::User()->name }} - 
                @if(Auth::user()->id_division == 'HR' && Auth::user()->id_position == 'HR MANAGER')
                  {{ Auth::user()->id_position }}
                @elseif(Auth::user()->id_position == 'EXPERT SALES')
                  {{ Auth::user()->id_position}}
                @else
                  {{ Auth::user()->id_division }} {{ Auth::user()->id_position }}
                @endif
                <small>
                  @if(Auth::User()->id_company == '1') 
                    Sinergy Informasi Pratama
                  @else
                    Multi Solusindo Perkasa
                  @endif
                </small>
                <small>Member since {{ Auth::User()->date_of_entry }}</small>
              </p>
            </li>
            <!-- Menu Body -->
            <!-- <li class="user-body">
              <div class="row">
                <div class="col-xs-4 text-center">
                  <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Friends</a>
                </div>
              </div>
            </li> -->
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{url('profile_user')}}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <!-- <a href="#" class="btn btn-default btn-flat">Sign out</a> -->
                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        {{-- <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li> --}}
      </ul>
    </div>

  </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if(Auth::User()->gambar == NULL)
          <img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="img-circle" alt="Yuki">
        @else
          <img src="{{asset('image/'.Auth::User()->gambar)}}" class="img-circle" alt="User Image">
        @endif
      </div>
      <div class="pull-left info" >
        <p>{{ Auth::User()->name }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i>@if(Auth::User()->id_division == 'TECHNICAL PRESALES')
        TECH. PRESALES @elseif(Auth::User()->id_division == 'TECHNICAL') TECH. @elseif(Auth::User()->id_division == 'HR') @else {{ Auth::user()->id_division }} @endif &nbsp {{ Auth::user()->id_position }} </a>
      </div>
    </div>
    <!-- search form -->
    <!-- <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                <i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form> -->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
          <i class="fa fa-fw fa-dashboard"></i>
          <span class="nav-link-text" style="font-size: 14px">Dashboard</span>
        </a>
      </li>
      @if(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position != 'ADMIN')
      <li class="treeview">
        <a href="#DVGPages" data-parent="#exampleAccordion">
          <i class="fa fa-fw fa-folder-open"></i>
          <span class="nav-link-text" style="font-size: 14px">DVG</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="DVGPages">
          <li>
            <a href="{{ url('/config_management') }}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>Config Management</a>
          </li>
          <li>
            <a href="{{ url('/incident_management') }}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>Incident Management</a>
          </li>
          <li>
            <a href="{{ url('/app_incident') }}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>App Incident Management</a>
          </li>
        </ul>
      </li>
      @endif





      <li class="treeview">
      	<a href="#SalesPages" data-parent="#exampleAccordion">
      		<i class="fa fa-fw fa-calendar"></i>
      		<span class="nav-link-text" style="font-size: 14px">Sales</span>
      		<span class="pull-right-container">
      			<i class="fa fa-angle-left pull-right"></i>
      		</span>
      	</a>
      	<ul class="treeview-menu" id="SalesPages">
      		@if(Auth::User()->id_position == 'DIRECTOR')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@elseif(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@elseif(Auth::User()->id_division == 'SALES')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@elseif(Auth::User()->id_division == 'TECHNICAL PRESALES')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		
      		@elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_territory == 'OPERATION')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@elseif(Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'ENGINEER STAFF')
      		<li>
      			<a href="{{url('/project')}}" style="font-size: 14px">Lead Register</a>
      		</li>
      		@endif
      		@if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
      		<li class="treeview">
      			<a href="#ReportLead" data-parent="#exampleAccordion">
      				<span class="nav-link-text" style="font-size: 14px">Report Lead Register</span>
      				<span class="pull-right-container">
      					<i class="fa fa-angle-left pull-right"></i>
      				</span>
      			</a>
      			<ul class="treeview-menu" id="ReportLead">
      			<li>
      				<a href="{{url('/report_range')}}" style="font-size: 14px">Report Range</a>
      			</li>
      			@if(Auth::User()->email == 'tech@sinergy.co.id')
      			<li>
      				<a href="{{url('/report_deal_price')}}" style="font-size: 14px">Report Range by Deal Price</a>
      			</li>
      			@endif
      			@if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
      			<li>
      				<a href="{{url('/report_sales')}}" style="font-size: 14px">Report Sales</a>
      			</li>
      			<li>
      				<a href="{{url('/report_presales')}}" style="font-size: 14px">Report Presales</a>
      			</li>
      			@endif
      		</ul>
      		</li>
      		@endif
      		@if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' || Auth::User()->id_position == 'ADMIN')
      		@else
      		<li>
      			<a href="{{('/sho')}}" style="font-size: 14px">Sales Handover</a>
      		</li>
      		@endif
      		@if(Auth::User()->id_company == '1')
      		<li>
      			<a href="{{url('/partnership')}}" style="font-size: 14px">Partnership</a>
      		</li>
      		@endif
      		@if(Auth::User()->id_position != 'HR MANAGER')
      		<li>
      			<a href="{{url('/customer')}}" style="font-size: 14px">Customer Data</a>
      		</li>
      		@endif
      	</ul>
      </li>





      @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(HEAD)' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(STAFF)' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'HR' || Auth::User()->name == 'Felicia Debi Noor')
      <li class="treeview">
        <a href="#ADMINPages" data-parent="#exampleAccordion">
          <i class="fa fa-fw fa-user"></i>
          <span class="nav-link-text" style="font-size: 14px">Admin</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="ADMINPages">
          @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR')
          <li>
            <a href="{{url('/pr')}}" style="font-size: 14px"></i>Purchase Request</a>
          </li>
          @endif

          @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(HEAD)' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(STAFF)' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR')
          <li>
            <a href="{{url('/letter')}}" style="font-size: 14px"></i>Letter</a>
          </li>
          @endif

          @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'STAFF GA' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'MSM')
          <li>
            <a href="{{url('/quote')}}" style="font-size: 14px"></i>Quote Number</a>
          </li>
          @endif

          @if(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'HR' || Auth::User()->id_position == 'ADMIN' && Auth::User()->id_division == 'MSM' || Auth::User()->name == 'Felicia Debi Noor')
          <li>
            <a href="{{url('admin_hr')}}" style="font-size: 14px"></i>HR</a>
          </li>
          @endif

          @if(Auth::User()->name == 'Felicia Debi Noor' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position == 'ADMIN' && Auth::User()->id_company == '1')
          <li>
            <a href="{{url('po')}}" style="font-size: 14px"></i>Purchase Order</a>
          </li>
          @endif
          @if(Auth::User()->id_position == 'ADMIN')
          <li>
            <a href="{{url('pr_asset')}}" style="font-size: 14px"></i>PR Asset Management</a>
          </li>
          @endif
        </ul>
      </li>
      @elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
      <li class="treeview">
        <a href="#ADMINPages" data-parent="#exampleAccordion">
          <i class="fa fa-user"></i>
          <span class="nav-link-text" style="font-size: 14px">Admin</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="ADMINPages">
          <li>
            <a href="{{url('/pr')}}" style="font-size: 14px"></i>Purchase Request</a>
          </li>
          <li>
            <a href="{{url('/po')}}" style="font-size: 14px"></i>Purchase Order</a>
          </li>
          <li>
            <a href="{{url('/letter')}}" style="font-size: 14px"></i>Letter</a>
          </li>
          <li>
            <a href="{{url('/quote')}}" style="font-size: 14px"></i>Quote Number</a>
          </li>
          <li>
            <a href="{{url('admin_hr')}}" style="font-size: 14px"></i>HR</a>
          </li>
        </ul>
      </li>
      @endif





      <li class="treeview">
      	<a href="#Finance" data-parent="#exampleAccordion">
      		<i class="fa fa-fw fa-credit-card"></i>
      		<span class="nav-link-text" style="font-size: 14px">Finance</span>
      		<span class="pull-right-container">
      			<i class="fa fa-angle-left pull-right"></i>
      		</span>
      	</a>
      	<ul class="treeview-menu" id="HumanResource">
      	  @if(Auth::User()->id_position == 'HR MANAGER')
      	  <li>
            <a href="{{url('/esm')}}" style="font-size: 14px"></i>Claim Management</a>
          </li>
          @elseif(Auth::User()->id_position == 'ADMIN')
          <li>
            <a href="{{url('/esm')}}" style="font-size: 14px"></i>Claim Management</a>
          </li>
          @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1')
          <li class="nav-item">
        	<a class="nav-link" href="{{url('/esm')}}">
          		<span class="nav-link-text" style="font-size: 14px">Claim Management</span>
        	</a>
      	  </li>
          @endif

          @if(Auth::User()->id_position != 'HR MANAGER')
          <li>
            <a href="{{url('/salesproject')}}" style="font-size: 14px"></i>ID Project</a>
          </li>
          @endif
      	</ul>
      </li>




      <li class="treeview">
      	<a href="#PMO" data-parent="#exampleAccordion">
      		<i class="fa fa-user"></i>
      		<span class="nav-link-text" style="font-size: 14px">PMO</span>
      		<span class="pull-right-container">
      			<i class="fa fa-angle-left pull-right"></i>
      		</span>
      	</a>
      	<ul class="treeview-menu" id="PMO">
      		<li>
      			<a href="{{url('PMO/index')}}" style="font-size: 14px">Project</a>
      		</li>
      	</ul>
      </li>




      @if(Auth::User()->id_position == 'HR MANAGER')
      <li class="treeview">
        <a href="#HRPages" data-parent="#exampleAccordion">
          <i class="fa fa-users"></i>
          <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="HRPages">
          <li>
            <a href="{{url('/hu_rec')}}" style="font-size: 14px"></i>Employees</a>
          </li>
          <li>
            <a href="{{url('/show_cuti')}}" style="font-size: 14px"></i>Leaving Permit</a>
          </li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#HRPages" data-parent="#exampleAccordion">
          <i class="fa fa-book"></i>
          <span class="nav-link-text" style="font-size: 14px">General Affair</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="HRPages">
          <li>
            <a href="{{url('/asset_hr')}}" style="font-size: 14px"></i>Asset</a>
          </li>
          <li>
            <a href="" style="font-size: 14px"></i>ATK</a>
          </li>
        </ul>
      </li>
      
      @elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '1')
      <li class="treeview">
        <a href="#HRPages" data-parent="#exampleAccordion">
          <i class="fa fa-users"></i>
          <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="HRPages">
          <li>
            <a href="{{url('/hu_rec')}}" style="font-size: 14px"></i>Employees</a>
          </li>
          <li>
            <a href="{{url('/show_cuti')}}" style="font-size: 14px"></i>Leaving Permit</a>
          </li>
        </ul>
      </li>
      @else
      <li class="treeview">
        <a href="#HRPages" data-parent="#exampleAccordion">
          <i class="fa fa-users"></i>
          <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" id="HRPages">
          <li>
            <a href="{{url('/show_cuti')}}" style="font-size: 14px"></i>Leaving Permit</a>
          </li>
        </ul>
      </li>
      @endif
      <li class="treeview">
        <ul class="treeview-menu" id="HRPages">
          <li>
            <a href="{{url('/show_cuti')}}" style="font-size: 14px"></i>Leaving Permit</a>
          </li>
        </ul>
      </li>


      @if(Auth::User()->id_position == 'INTERNAL IT' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position != 'MANAGER' && Auth::User()->id_territory != '' || Auth::User()->id_division == 'TECHNICAL PRESALES' || Auth::User()->id_territory == 'DVG')
      <li class="nav-item">
        <a class="nav-link" href="{{url('/asset_pinjam')}}">
          <i class="fa fa-fw fa-book"></i>
          <span class="nav-link-text" style="font-size: 14px">Tech Asset</span>
        </a>
      </li>
      @endif

      @if(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
      <li class="nav-item">
        <a class="nav-link" href="{{url('/hu_rec')}}">
          <i class="fa fa-fw fa-book"></i>
          <span class="nav-link-text" style="font-size: 14px">Employees</span>
        </a>
      </li>
      @endif

      

      @if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'HR')
      <li class="nav-item">
        <a class="nav-link" href="{{url('/bank_garansi')}}">
          <i class="fa fa-fw fa-folder-open"></i>
          <span class="nav-link-text" style="font-size: 14px">Bank Garansi</span>
        </a>
      </li>
      @endif

      <!-- @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(HEAD)' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT')
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Letter">
        <a class="nav-link" href="{{url('/letter')}}">
          <i class="fa fa-fw fa-folder-open"></i>
          <span class="nav-link-text" style="font-size: 14px">Letter</span>
        </a>
      </li>
      @endif -->

      <!-- @if(Auth::User()->name == 'Felicia Debi Noor')
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
        <a class="nav-link" href="{{url('/po')}}">
          <i class="fa fa-fw fa-laptop"></i>
          <span class="nav-link-text" style="font-size: 14px">Purchase Order</span>
        </a>
      </li>
      @endif -->

      @if(Auth::User()->id_division == 'WAREHOUSE')
        <li class="treeview">
          <a href="#INPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-folder-open"></i>
            <span class="nav-link-text" style="font-size: 14px">Warehouse</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" id="INPages">
            <li>
              <a href="{{url('/inventory')}}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>&nbspInventory</a>
            </li>
            <li>
              <a href="{{url('/inventory/project')}}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>&nbspDelivery Order</a>
            </li>
            <li>
              <a href="{{url('/asset')}}" style="font-size: 14px"><i class="fa fa-fw fa-circle-o"></i>&nbspAsset Management</a>
            </li>
          </ul>
        </li>
      @endif
      

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
