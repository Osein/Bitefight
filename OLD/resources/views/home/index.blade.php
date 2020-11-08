@extends('index')

@section('content')
	<div id="splashInfo">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<div id="speedserverbig">{{__('home.home_header_big')}}</div>
				<img src="{{asset('img/layout/home_splash.jpg')}}" width="710" height="381" border="0" alt="{{__('game_name')}}" usemap="#home-splash-map"/>
				<map name="home-splash-map" id="home-splash-map">
					<area  alt="{{__('general.werewolf')}}" title="{{__('general.werewolf')}}" href="{{route('register', ['race' => 2])}}" shape="poly" coords="5,36,23,46,52,63,84,76,97,105,120,120,134,142,141,167,152,179,164,203,178,226,190,242,181,265,150,287,3,374" style="outline:none;" />
					<area  alt="{{__('general.vampire')}}" title="{{__('general.vampire')}}" href="{{route('register', ['race' => 1])}}" shape="poly" coords="708,7,641,9,608,21,582,40,559,69,551,95,536,136,526,162,536,187,538,209,543,226,552,246,566,269,573,306,589,338,614,357,641,374,708,376" style="outline:none;" />
				</map>
				<p id="splashText">
					{!! __('home.home_splash_text') !!}
				</p>
				<a href="{{route('register')}}" id="regBtn" target="_top">{{__('home.home_index_play_now')}}</a> <br class="clearfloat">
				<div id="features" class="clearfix">
					<p>{!! __('home.home_index_thumb1') !!}<img src="{{asset('img/home_thumb01.jpg')}}" width="170" height="86"/></p>
					<p>{!! __('home.home_index_thumb2') !!}<img src="{{asset('img/home_thumb02.jpg')}}" width="170" height="86"/></p>
					<p>{!! __('home.home_index_thumb3') !!}<img src="{{asset('img/home_thumb03.jpg')}}" width="170" height="86"/></p>
				</div>
				<br class="clearfloat"/>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection