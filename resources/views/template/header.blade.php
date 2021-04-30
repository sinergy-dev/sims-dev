<header class="main-header">
	<a href="{{url('/')}}" class="logo">
		<span class="logo-mini"><img src="{{asset('/img/siplogooke.png')}}" alt="cobaaa" width="30px" height="40px"></img></span>
		<span class="logo-lg"><b>SIMS</b>APP</span>
	</a>

	<nav class="navbar navbar-static-top">
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">
				Toggle navigation
			</span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bell-o"></i>
						<span class="label label-warning">
							<small>
								<i class="fa fa-fw fa-circle"></i>
							</small>
						</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">New Notifications:</li>
						<li>
							<ul class="menu">
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
							</ul>
						</li>
						<li class="footer">
							<a href="#">View all</a>
						</li>
					</ul>
				</li>
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
							<img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="user-image" alt="Yuki">
						@else
							<img src="{{asset('image/'.Auth::User()->gambar)}}" class="user-image" alt="User Image">
						@endif
						<span class="hidden-xs">{{ Auth::User()->name }}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
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
									@if(Auth::user()->nik == 100000000003)
									SALES OPERATIONAL
									@elseif(Auth::user()->id_division == 'TECHNICAL' && Auth::user()->id_position == 'MANAGER')
									OPERATIONAL DIRECTOR
									@else
									{{ Auth::user()->id_division }} {{ Auth::user()->id_position }}
									@endif
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
						<li class="user-footer">
							<div class="pull-left">
								<a href="{{url('profile_user')}}" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
										{{ __('Logout') }}
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
									<input type="hidden" name="nik" value="{{Auth::User()->nik}}">
								</form>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>