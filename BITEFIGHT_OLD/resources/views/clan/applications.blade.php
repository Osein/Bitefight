@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="application">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Edit applications</h2>
				<div style="margin:15px auto 0; width:452px;">

					@foreach($applications as $app)
					<p style="text-align:right"><a class="left" href="{{url('/profile/player/'.$app->user_id)}}" target="_new">{{$app->name}}</a>{{date('D, d.m.Y - H:i:s', $app->application_time)}}</p>
					<p>{{$app->note}}</p>

					<form class="clearfix" action="{{'/clan/applications/'.$app->id}}" method="post">
						{{csrf_field()}}
						<p>Reason for rejection:</p>
						<textarea name="abltext" cols="50" rows="5"></textarea><br>
						<div class="btn-left left"><div class="btn-right">
								<input class="btn" type="submit" name="abl" value="No way">
							</div>
						</div>
						<div class="btn-left left"><div class="btn-right">
								<input class="btn" type="submit" name="ann" value="OK, accepted">
							</div>
						</div>
						{{--Todo: open report link--}}
						<!--<div class="btn-left left"><div class="btn-right">
								<a class="btn" href="https://s202-en.bitefight.gameforge.com:443/msg/complain/capplication?__token=111a3ac6ddee6a633dc387b12a37a7fa&amp;id=2908&amp;cc=2c770af">( report )</a>
							</div>
						</div>-->
					</form>
					@endforeach
					<br class="clearfloat">
				</div>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection