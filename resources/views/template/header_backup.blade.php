 <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <img src="../img/logopng.png" width="30px" height="40px"></img><a class="navbar-brand" href="{{ url('/') }}" style="font-size: 16px">
    @if(Auth::user()->id_division == 'HR' && Auth::user()->id_position == 'HR MANAGER')
      {{ Auth::user()->id_position }}
    @elseif(Auth::user()->id_position == 'EXPERT SALES')
      {{ AUth::user()->id_position}}
    @else
      {{ Auth::user()->id_division }} {{ Auth::user()->id_position }} 
    @endif
    |
    @if(Auth::User()->id_company == '1') 
    Sinergy Informasi Pratama
    @else
    Multi Solusindo Perkasa
    @endif</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="{{ url('/') }}">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text" style="font-size: 14px">Dashboard</span>
          </a>
        </li>
        @if(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position != 'ADMIN')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="DVG">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#DVGPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text">DVG</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="DVGPages">
            <li>
              <a href="{{ url('/config_management') }}" style="font-size: 14px">Config Management</a>
            </li>
            <li>
              <a href="{{ url('/incident_management') }}" style="font-size: 14px">Incident Management</a>
            </li>
          </ul>
        </li>
        @endif
        @if(Auth::User()->id_position == 'DIRECTOR')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'OPERATION DIRECTOR')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_division == 'SALES')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_division == 'TECHNICAL PRESALES')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text" style="font-size: 14px">Lead Register</span>
          </a>
        </li>
        @elseif(Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text" style="font-size: 14px">Report</span>
          </a>
        </li>
        @elseif(Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'ENGINEER STAFF')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/project')}}">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text" style="font-size: 14px">Report</span>
          </a>
        </li>
        @endif

        @if(Auth::User()->id_position == 'HR MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#HRPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="HRPages">
            <li>
              <a href="{{url('/hu_rec')}}" style="font-size: 14px">Employees</a>
            </li>
            <li>
              <a href="{{url('/show_cuti')}}" style="font-size: 14px">Leaving Permit</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Engineer Spentr">
          <a class="nav-link" href="{{url('/esm')}}">
            <i class="fa fa-credit-card"></i>
            <span class="nav-link-text" style="font-size: 14px">Claim Management</span>
          </a>
        </li>
        @elseif(Auth::User()->id_position == 'DIRECTOR')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#HRPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="HRPages">
            <li>
              <a href="{{url('/hu_rec')}}" style="font-size: 14px">Employees</a>
            </li>
            <li>
              <a href="{{url('/show_cuti')}}" style="font-size: 14px">Leaving Permit</a>
            </li>
          </ul>
        </li>
        @elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_company == '1')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#HRPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text" style="font-size: 14px">Human Resource</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="HRPages">
            <li>
              <a href="{{url('/hu_rec')}}" style="font-size: 14px">Employees</a>
            </li>
            <li>
              <a href="{{url('/show_cuti')}}" style="font-size: 14px">Leaving Permit</a>
            </li>
          </ul>
        </li>
        @endif

        @if(Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employees">
          <a class="nav-link" href="{{url('/hu_rec')}}">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text" style="font-size: 14px">Employees</span>
          </a>
        </li>
        @endif

        @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_company == '1')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Engineer Spentr">
          <a class="nav-link" href="{{url('/esm')}}">
            <i class="fa fa-credit-card"></i>
            <span class="nav-link-text" style="font-size: 14px">Claim Management</span>
          </a>
        </li>
        @endif

        @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(HEAD)' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(STAFF)' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'HR' || Auth::User()->name == 'Felicia Debi Noor')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#ADMINPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-folder-open"></i>
            <span class="nav-link-text" style="font-size: 14px">Admin</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="ADMINPages">
            @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'STAFF GA')
            <li>
              <a href="{{url('/pr')}}" style="font-size: 14px">Purchase Request</a>
            </li>
            @endif

            @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'PMO' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_division == 'TECHNICAL PRESALES' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(HEAD)' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'SERVICE PROJECT(STAFF)' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'STAFF GA')
            <li>
              <a href="{{url('/letter')}}" style="font-size: 14px">Letter</a>
            </li>
            @endif

            @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN' && Auth::User()->id_company == '1' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'STAFF GA')
            <li>
              <a href="{{url('/quote')}}" style="font-size: 14px">Quote Number</a>
            </li>
            @endif

            @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
            <li>
              <a href="{{url('admin_hr')}}" style="font-size: 14px">HR</a>
            </li>
            @endif

            @if(Auth::User()->name == 'Felicia Debi Noor')
            <li>
              <a href="{{url('po')}}" style="font-size: 14px">Purchase Order</a>
            </li>
            @endif
            @if(Auth::User()->id_position == 'ADMIN')
            <li>
              <a href="{{url('/esm')}}" style="font-size: 14px">Claim Management</a>
            </li>
            @endif

            @if(Auth::User()->id_position == 'ADMIN')
            <li>
              <a href="{{url('pr_asset')}}" style="font-size: 14px">PR Asset Management</a>
            </li>
            @endif
          </ul>
        </li>
        @elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER' && Auth::User()->id_company == '1')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="HR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#ADMINPages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-folder-open"></i>
            <span class="nav-link-text" style="font-size: 14px">Admin</span>
          </a>
          <ul class="sidenav-second-level collapse submenu" id="ADMINPages">
            <li>
              <a href="{{url('/pr')}}" style="font-size: 14px">Purchase Request</a>
            </li>
            <li>
              <a href="{{url('/po')}}" style="font-size: 14px">Purchase Order</a>
            </li>
            <li>
              <a href="{{url('/letter')}}" style="font-size: 14px">Letter</a>
            </li>
            <li>
              <a href="{{url('/quote')}}" style="font-size: 14px">Quote Number</a>
            </li>
            <li>
              <a href="{{url('admin_hr')}}" style="font-size: 14px">HR</a>
            </li>
            <!-- <li>
              <a href="{{url('/esm')}}" style="font-size: 14px">Claim Management</a>
            </li> -->
            <!-- <li>
              <a href="{{url('pr_asset')}}" style="font-size: 14px">PR Asset Management</a>
            </li> -->
          </ul>
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
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/do')}}">
            <i class="fa fa-fw fa-laptop"></i>
            <span class="nav-link-text" style="font-size: 14px">Delivery Order</span>
          </a>
        </li>
        @endif

        @if(Auth::User()->id_position != 'HR MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Customer Data">
          <a class="nav-link" href="{{url('/customer')}}">
            <i class="fa fa-fw fa-folder-open"></i>
            <span class="nav-link-text" style="font-size: 14px">Customer Data</span>
          </a>
        </li>
        
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Lead Register">
          <a class="nav-link" href="{{url('/salesproject')}}">
            <i class="fa fa-fw fa-laptop"></i>
            <span class="nav-link-text" style="font-size: 14px">ID Project</span>
          </a>
        </li>
        @endif

        @if(Auth::User()->id_company == '1')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Partnership Summary">
          <a class="nav-link" href="{{url('/partnership')}}">
            <i class="fa fa-fw fa-folder-open"></i>
            <span class="nav-link-text" style="font-size: 14px">Partnership</span>
          </a>
        </li>
        @endif
        
        <!-- @if(Auth::User()->id_position == 'HR MANAGER'|| Auth::User()->id_division == 'FINANCE')
        @elseif(Auth::User()->id_position != 'HR MANAGER' && Auth::User()->id_company == '1'|| Auth::User()->id_division != 'FINANCE' && Auth::User()->id_company == '1')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Quote Number">
          <a class="nav-link" href="{{url('/quote')}}">
            <i class="fa fa-fw fa-ticket"></i>
            <span class="nav-link-text" style="font-size: 14px">Quote Number</span>
          </a>
        </li>
        @endif -->
        
        <!-- Header ID Project -->

        <!-- @if(Auth::User()->id_division != 'HR' && Auth::User()->id_division != 'WAREHOUSE')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ID Project">
          <a class="nav-link" href="{{url('/salesproject')}}">
            <i class="fa fa-fw fa-laptop"></i>
            <span class="nav-link-text" style="font-size: 14px">ID Project</span>
          </a>
        </li>
        @endif -->

        <!-- @if(Auth::User()->id_division == 'WAREHOUSE' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Products">
          <a class="nav-link" href="{{url('/warehouse')}}">
            <i class="fa fa-fw fa-cubes"></i>
            <span class="nav-link-text">Warehouse</span>
          </a>
        </li>
        @endif -->

        @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' || Auth::User()->id_position == 'ADMIN')
        @else
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sales Handover">
          <a class="nav-link" href="{{('/sho')}}">
            <i class="fa fa-fw fa-mail-forward"></i>
            <span class="nav-link-text" style="font-size: 14px">Sales Handover</span>
          </a>
        </li>
        @endif
        <!-- @if(Auth::User()->id_position == 'DIRECTOR')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Report">
          <a class="nav-link" href="{{url('/report')}}">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text">Report</span>
          </a>
        </li>
        @endif
 -->
        <!-- Report -->

        <!-- @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Report">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text" style="font-size: 14px">Report</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseExamplePages">
            <li>
              <a href="{{url('/report')}}" style="font-size: 14px">Report</a>
            </li>
            <li>
              <a href="{{url('/report_range')}}" style="font-size: 14px">Report Range</a>
            </li>
          </ul>
        </li>
        @endif -->

        @if(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'TECHNICAL PRESALES')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sales Handover">
          <a class="nav-link" href="{{url('/report_range')}}">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text" style="font-size: 14px">Report Range</span>
          </a>
        </li>
        @endif

        <!-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Timesheet">
          <a class="nav-link" href="{{url('/timesheet')}}">
            <i class="fa fa-fw fa-calendar"></i>
            <span class="nav-link-text">Timesheet</span>
          </a>
        </li> -->
      </ul>
      <ul class="navbar-nav ml-auto">
        <!-- NOTIF -->
        <!-- @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'FINANCE')
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning"></span>
            </span>
              <span class="indicator text-warning d-none d-lg-block">
          <i class="fa fa-fw fa-circle"></i>
        </span>
          </a>
          <div class="dropdown-menu gridcontainer"  aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">New Alerts:</h6>
            <div class="dropdown-divider"></div>

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

          </div>
        </li>
        @elseif(Auth::user()->id_position != 'HR' && Auth::user()->id_position != 'ENGINEER' )
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning"></span>
            </span>
            	<span class="indicator text-warning d-none d-lg-block">
					<i class="fa fa-fw fa-circle"></i>
				</span>
          </a>
          <div class="dropdown-menu gridcontainer"  aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">New Alerts:</h6>
            <div class="dropdown-divider"></div>

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

          </div>
        </li>
        @endif -->
        
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="font-size: 14px">
               <i class="fa fa-user"></i>&nbsp
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{url('profile_user')}}">
                  <i class="fa fa-user-circle-o"></i>
                    {{ __('Profil') }}
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i>
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
      </ul>

      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
    </div>
  </nav>

    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Sinergy Informasi Pratama © 2018</small>
        </div>
      </div>
    </footer>

    <style type="text/css">
      .submenu a:hover {
        background: #b63b4d;
        color: #FFF;
      }
    </style>