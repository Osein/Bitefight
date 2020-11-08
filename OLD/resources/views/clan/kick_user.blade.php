@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a href="{{url('/clan/index')}}" class="btn">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="handleMembers">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}manage members</h2>
				<b>{{$kick_user->name}}</b> Do you really want to kick this member?<br>
				<a href="{{url('/clan/kick/'.$kick_user->id.'?_token='.csrf_token())}}">kick</a>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection