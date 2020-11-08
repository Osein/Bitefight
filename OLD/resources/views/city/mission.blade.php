@extends('index')

@section('content')
	<div class="wrap-top-left clearfix">
		<div class="wrap-top-right clearfix">
			<div class="wrap-top-middle clearfix"></div>
		</div>
	</div>
	<div class="wrap-left clearfix">
		<div class="wrap-content wrap-right clearfix">
			<!-- CONTENT START -->
			<h2>{{user_race_logo_small()}} Missions</h2>

			<p>You can accept missions and view the status of your accepted missions here. You can also finish completed missions to make space for new missions. You will receive a reward for a mission when you finish it. You can activate a maximum of 3 missions, of which a maximum of 2 can have the same target. You can finish a maximum of 2 missions per day. Everything above the value feeds on your concentration and performance, meaning you need the power of Hellstones. If one of your missions fails, you have to finish it anyway, before accepting a new mission. However, you can make accepted missions disappear from your list by using Hellstones. New missions that you can then accept are announced every 24 hours.</p>

			<p class="gold">Overview:</p>


			<div style="text-align:left;">
				<ul>
					<li>
						A maximum of 5 missions can be active. The first 3 missions are free, the remaining missions each cost 5 Hellstones.                </li>
					<li>
						You can have a maximum of 2 missions with the same target                </li>
					<li>
						A maximum of 5 missions can be finished per day. The first 2 missions are free, the remaining missions each cost 1 Hellstone.                </li>
					<li>
						Failed missions also need to be finished                </li>
					<li>
						Cancelling a mission costs 2 HS                </li>
					<li>
						At 00:00, new missions are generated for non-accepted and finished missions                </li>
				</ul>
			</div>
			<br>

			<div style="clear:both;">
				@foreach($missions as $mission)
				<table style="width:100%;margin-bottom:20px;">
					<tbody>
					<tr>
						<td style="width:40%;vertical-align:top;text-align:left;"><p class="gold">Target: </p>
							<p>@if($mission->type == \Database\Models\UserMissions::TYPE_HUMAN_HUNT) Successful man hunts @endif
							({{$mission->progress}} / {{$mission->count}})</p></td>
						<td style="width:40%;vertical-align:top;text-align:left;">
							<p class="gold">Reward:</p>
							@if($mission->frag > 0)
							<p>{{$mission->frag}}&nbsp;{{fragment_image_tag()}}</p>
							@endif
							@if($mission->ap > 0)
							<p>{{$mission->ap}}&nbsp;{{action_point_image_tag()}}</p>
							@endif
							@if($mission->heal > 0)
							<p>Healing: {{$mission->heal}}%</p>
							@endif
							<p>{{prettyNumber($mission->gold)}}&nbsp;{{gold_image_tag()}}</p>
						</td>
						<td>
							@if(!$mission->accepted && $total_active < 5 && $types[$mission->type]['canAccept'])
							<div class="button" style="width: 205px;">
								<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

								<div class="btn-left button_float" style="margin: 0;"></div>
								<button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="document.location.href='{{url('/city/missions/acceptMission/'.$mission->id.'?_token='.csrf_token())}}'">Accept @if($total_active > 1) (5 {{hellstone_image_tag()}})@endif</button>
								<div class="btn-right button_float"></div>
								<div class="clearfloat"></div>
							</div>
							@elseif($mission->progress == $mission->count && $finished_count < 5 && $mission->status == 0)
							<div class="button" style="width: 205px;">
								<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

								<div class="btn-left button_float" style="margin: 0;"></div>
								<button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="document.location.href='{{url('/city/missions/finishMission/'.$mission->id.'?_token='.csrf_token())}}'">
									Finish @if($finished_count > 1) (1 {{hellstone_image_tag()}})@endif</button>
								<div class="btn-right button_float"></div>
								<div class="clearfloat"></div>
							</div>
							@endif
						</td>
					</tr>
					<tr>
						<td style="vertical-align:top;text-align:left;">
							<p class="gold">Conditions:</p>
							<p>
								@if($mission->time > 0)
								Within {{$mission->time}}:00:00<br>
								@endif
								@if($mission->type == \Database\Models\UserMissions::TYPE_HUMAN_HUNT && $mission->special > 0)
								Man hunt only with difficulty level: {{getHumanHuntNameFromNo($mission->special)}}
								@endif
							</p>
						</td>
						<td style="vertical-align:top;text-align:left;">
							<p class="gold">Status:</p>
							<p>
								@if($mission->status > 0)
									@if($mission->status == 1) Finished (Completed) @endif
									@if($mission->status == 2) Finished (Failed) @endif
                                @else
									@if($mission->accepted) Active
									@elseif($total_active < 5 && $types[$mission->type]['canAccept']) Open
									@else Closed
									@endif
                                @endif
							</p>
						</td>
						<td>
							@if($mission->accepted && $mission->status == 0)
							<div class="button" style="width: 205px;">
								<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

								<div class="btn-left button_float" style="margin: 0;"></div>
								<button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="document.location.href='{{url('/city/missions/cancelMission/'.$mission->id.'?_token='.csrf_token())}}'">Cancel (2 {{hellstone_image_tag()}})</button>
								<div class="btn-right button_float"></div>
								<div class="clearfloat"></div>
							</div>
							@endif
						</td>
					</tr>
					</tbody>
				</table>
				@endforeach
			</div>
			<br class="clearfloat">


			<div class="button left" style="width: 296px;">
				<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

				<div class="btn-left button_float" style="margin: 0;"></div>
				<button class="btn" type="submit" style="margin: 0; width: 270px;" onclick="document.location.href='{{url('/city/missions/replaceOpenMissions?_token='.csrf_token())}}'">Regenerate open missions (6 {{hellstone_image_tag()}})</button>
				<div class="btn-right button_float"></div>
				<div class="clearfloat"></div>
			</div>


			<div class="button left" style="width: 296px;">
				<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

				<div class="btn-left button_float" style="margin: 0;"></div>
				<button class="btn" type="submit" style="margin: 0; width: 270px;" onclick="document.location.href='{{url('/city/missions/replaceOpenMissionsForAp?_token='.csrf_token())}}'">Regenerate open missions (20 {{hellstone_image_tag()}})</button>
				<div class="btn-right button_float"></div>
				<div class="clearfloat"></div>
			</div>

			<br class="clearfloat">
			<!-- CONTENT END -->
		</div>
	</div>
	<div class="wrap-bottom-left">
		<div class="wrap-bottom-right">
			<div class="wrap-bottom-middle"></div>
		</div>
	</div>
@endsection