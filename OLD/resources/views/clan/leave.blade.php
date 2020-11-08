@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a href="{{url('/clan/index')}}" class="btn">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="create">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{user()->getClanRank() == 1 ? 'Disband clan' : 'Leave clan'}}</h2>
				<div>
					<a href="{{url('/clan/clanleave?_token='.csrf_token())}}">Are you sure you want to {{user()->getClanRank() == 1 ? 'disband' : 'leave'}} the clan?</a></div>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection