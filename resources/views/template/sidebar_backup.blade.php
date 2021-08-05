<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				@if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-")
					<img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" class="img-circle">
				@else
					<img src="{{asset('image/'.Auth::User()->gambar)}}" class="img-circle" alt="User Image">
				@endif
			</div>
			<div class="pull-left info" >
				<p>{{ Auth::User()->name }}</p>
				<a href="#"><i class="fa fa-circle text-success"></i>
					{{$initView['userRole']->name}}
				</a>
			</div>
		</div>
		<ul class="sidebar-menu tree" data-widget="tree">
			<li class="header">MAIN NAVIGATION</li>
			@foreach($initView['listMenu'] as $group)
				<li class="activeable_group treeview">
					<a href="#">
						<i class="{{$group['icon_group']}}"></i>
						<span>{{$group['text']}}</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						@foreach($group['children'] as $chid)
							<li class="activeable_menu">
								<a href="#{{$chid->url}}"><i class="fa fa-circle-o"></i>{{$chid->name}}</a>
							</li>
						@endforeach
					</ul>
				</li>
			@endforeach
		</ul>
	</section>
</aside>