<ul id="menuHead">
	@if(!\Illuminate\Support\Facades\Auth::check())
		<li><a href="{{url('news')}}" target="_top" class="newmessage">{{__('general.menu_news')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/register')) ? 'active' : ''}}"><a href="{{ route('register') }}" target="_top">{{__('general.menu_register')}}</a></li>
		<li class="{{\Illuminate\Support\Facades\Request::is('/login') ? 'active': ''}}"><a href="{{url('login')}}" target="_top">{{__('general.menu_login')}}</a></li>
		<li class="{{\Illuminate\Support\Facades\Request::is('/highscore') ? 'active' : ''}}"><a href="{{url('/highscore')}}" target="_top">{{__('general.menu_highscore')}}</a></li>
	@else
		<li class="{{(\Illuminate\Support\Facades\Request::is('/news')) ? 'active' : ''}}"><a href="{{url('/news')}}" target="_top" class="">{{__('general.menu_news')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/profile*')) ? 'active' : ''}}"><a href="{{url('/profile/index')}}" target="_top">{{__('general.menu_overview')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/message*')) ? 'active' : ''}}"><a href="{{url('/message/index')}}" target="_top" class="{{$user_new_message_count ? 'newmessage' : ''}}" id="msgmenu">{{__('general.menu_message')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/hideout')) ? 'active' : ''}}"><a href="{{url('/hideout')}}" target="_top">{{__('general.menu_hideout')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/city*')) ? 'active' : ''}}"><a href="{{url('/city/index')}}" target="_top">{{__('general.menu_city')}}</a></li>
		<li class="free-space {{(\Illuminate\Support\Facades\Request::is('/hunt*')) ? 'active' : ''}}"><a href="{{url('/hunt')}}" target="_top">{{__('general.menu_hunt')}}</a></li>
		<li id="premium" class="{{(\Illuminate\Support\Facades\Request::is('/voodoo')) ? 'active' : ''}}">
			<img border="0" src="{{asset('img/voodoo/res3_rotation.gif')}}">
			<a href="{{url('/voodoo')}}" target="_top">{{__('general.menu_voodoo_shop')}}</a>
		</li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/clan*')) ? 'active' : ''}}"><a href="{{url('/clan/index')}}" class="{{$clan_application_count ? 'newmessage' : ''}}" target="_top">{{__('general.menu_clan')}}</a></li>
	<!--<li class="{{(\Illuminate\Support\Facades\Request::is('/buddy*')) ? 'active' : ''}}"><a href="{{url('/buddy')}}" target="_top">{{__('general.menu_buddy_list')}}</a></li>-->
		<li class="{{(\Illuminate\Support\Facades\Request::is('/notepad')) ? 'active' : ''}}"><a href="{{url('/notepad')}}" target="_top">{{__('general.menu_notepad')}}</a></li>
		<li class="free-space "></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/settings')) ? 'active' : ''}}"><a href="{{url('/settings')}}" target="_top">{{__('general.menu_settings')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/highscore')) ? 'active' : ''}}"><a href="{{url('/highscore')}}" target="_top">{{__('general.menu_highscore')}}</a></li>
		<li class="{{(\Illuminate\Support\Facades\Request::is('/search')) ? 'active' : ''}}"><a href="{{url('/search')}}" target="_top">{{__('general.menu_search')}}</a></li>
		<li><a href="{{url('/logout')}}" target="_top">{{__('general.menu_logout')}}</a></li>
	@endif
	<li id="time">{{date('d.m.Y H:i')}}</li>
</ul>

<script type="text/javascript">
	var $menuHead = $('#menuHead'),
		$active = $menuHead.find('.active');
	var currentPage = $active.text();

	if (currentPage) {
		$('#header').find('h1').text(currentPage);
	}

	$(document).ready(function () {
		$menuHead.find('li').on('click', function () {
			$active.removeClass('active');
			$(this).addClass('active');
		});
	});
</script>