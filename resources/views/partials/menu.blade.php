@if(!\Illuminate\Support\Facades\Auth::check())
<ul id="menuHead">
	<li><a href="{{url('news')}}" target="_top" class="newmessage">{{__('general.menu_news')}}</a></li>
	<li class="{{(\Illuminate\Support\Facades\Request::is('/register/0') || \Illuminate\Support\Facades\Request::is('/register/1') || \Illuminate\Support\Facades\Request::is('/register/2')) ? 'active' : ''}}"><a href="{{url('register/0')}}" target="_top">{{__('general.menu_register')}}</a></li>
	<li class="{{\Illuminate\Support\Facades\Request::is('/login') ? 'active': ''}}"><a href="{{url('login')}}" target="_top">{{__('general.menu_login')}}</a></li>
	<li class="{{\Illuminate\Support\Facades\Request::is('/highscore') ? 'active' : ''}}"><a href="{{url('highscore')}}" target="_top">{{__('general.menu_highscore')}}</a></li>
	<li id="time">09.01.2017 12:07</li>
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
</ul>
@endif