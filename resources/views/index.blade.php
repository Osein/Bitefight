<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>{{env('APP_NAME')}}</title>
	<link rel="stylesheet" href="{{asset('css/game.css')}}">
	<link rel="stylesheet" href="{{asset('css/tipped.css')}}">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="cache-control" content="no-cache"/>
	<meta http-equiv="cache-control" content="no-store"/>
	<meta http-equiv="cache-control" content="max-age=0"/>
	<meta http-equiv="cache-control" content="must-revalidate"/>
	<meta http-equiv="expires" content="0"/>
	<meta http-equiv="pragma" content="no-cache"/>

	<script>
		var bf = {};
		bf.page = {};
		<?php if(isset($user)): ?>
			bf.user = {
			id: <?php echo $user->id; ?>
		};
		<?php endif; ?>
	</script>

	<script src="{{asset('js/jquery-3.2.1.js')}}"></script>
	<script src="{{asset('jquery-plugin.js')}}"></script>
	<script src="{{asset('jquery-countdown-2.1.0.js')}}"></script>
	<script src="{{asset('js/jquery-ui-1.12.1.js')}}"></script>
	<script src="{{asset('js/jquery-ui-chatbox.js')}}"></script>
	<script src="{{asset('js/tipped.js')}}"></script>
	<script src="{{asset('js/tipped-spinners.js')}}"></script>
	<script src="{{asset('js/init-game.js')}}"></script>
    <!--[if lt IE 9]><script src="{{asset('js/if-ie.js')}}"></script><![endif]-->
</head>

<body>

<div id="header">
	<!-- if gates of underworld are open <div id="feuerheader"></div> <div id="decoLampGateOpen"></div> -->
	<h1>{{__('general.menu_header_bitefight')}}</h1>
	<div id="decoLamp"></div>
</div>

<div id="container" class="clearfix">
	<div id="menu">
		@include('partials.menu')
	</div>
	<div id="content">
		@include('partials.infobar')
		@yield('content')
	</div>
</div>

<?php if(isset($missionInfo)): ?>
        <?php $this->partial('partials/info_popup', ['mission' => $missionInfo]); ?>
        <?php endif; ?>

<div id="footer">
	<div id="beast"></div>
	<div id="skull"></div>
	<div id="copyright">{{__('general.footer_string', ['version' => '0.1', 'time' => 0.5])}}</div>
</div>

</body>
</html>