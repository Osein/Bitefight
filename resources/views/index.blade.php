<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{env('APP_NAME')}}</title>
	<link rel="stylesheet" href="{{asset('css/game.css')}}">
	<link rel="stylesheet" href="{{asset('css/tipped.css')}}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

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
	<script src="{{asset('js/jquery-plugin.js')}}"></script>
	<script src="{{asset('js/jquery-countdown-2.1.0.js')}}"></script>
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
	<div id="copyright">{{__('general.footer_string', ['version' => env('VERSION'), 'time' => (microtime(true) - LARAVEL_START)])}}</div>
</div>

</body>
</html>