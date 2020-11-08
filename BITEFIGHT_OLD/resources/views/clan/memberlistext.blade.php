@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/preview/clan/'.$clan->getId())}}">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="membreListExt">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Member list: Clan {{$clan->name}}</h2>
				<div class="table-wrap">
					<table width="100%">
						<tbody><tr>
							<td>Player</td>
							<td>Level</td>
							<td>Rank</td>
							<td align="right">Blood</td>
							<td align="right">captured Gold</td>
							<td align="right">lost Gold</td>
						</tr>
						@foreach($memberList as $member)
						<tr>
							<td><a href="{{'profile/player/'.$member->id}}">{{$member->name}}</a></td>
							<td>{{prettyNumber(getLevel($member->exp))}}</td>
							<td>{{$member->rank_name}}</td>
							<td align="right">{{prettyNumber($member->s_booty)}}</td>
							<td align="right">{{prettyNumber($member->s_gold_captured)}}</td>
							<td align="right">{{prettyNumber($member->s_gold_lost)}}</td>
						</tr>
						@endforeach
						</tbody>
					</table>
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