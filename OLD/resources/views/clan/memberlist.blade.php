@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="memberList">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Member list Clan {{$clan->name}} [{{$clan->tag}}]</h2>
				<div class="table-wrap">
					<table width="100%">
						<tbody>
						<tr>
							<td><a href="{{'/clan/memberlist/?order=name&type='.($order == 'name' && $type == 'desc' ? 'asc' : 'desc')}}">Player</a></td>
							<td><a href="{{'/clan/memberlist/?order=level&type='.($order == 'level' && $type == 'desc' ? 'asc' : 'desc')}}">Level</a></td>
							<td><a href="{{'/clan/memberlist/?order=rank&type='.($order == 'rank' && $type == 'desc' ? 'asc' : 'desc')}}">Rank</a></td>
							<td align="right"><a href="{{'/clan/memberlist/?order=res1&type='.($order == 'res1' && $type == 'desc' ? 'asc' : 'desc')}}">Blood</a></td>
							<td align="right"><a href="{{'/clan/memberlist/?order=goldwon&type='.($order == 'goldwon' && $type == 'desc' ? 'asc' : 'desc')}}">captured Gold</a></td>
							<td align="right"><a href="{{'/clan/memberlist/?order=goldlost&type='.($order == 'goldlost' && $type == 'desc' ? 'asc' : 'desc')}}">lost Gold</a></td>
							<td align="right"><a href="{{'/clan/memberlist/?order=status&type='.($order == 'status' && $type == 'desc' ? 'asc' : 'desc')}}">Status</a></td>
						</tr>
						@foreach($members as $member)
						<tr>
							<td><a href="{{url('profile/player/'.$member->id)}}">{{$member->name}}</a></td>
							<td>{{prettyNumber(getLevel($member->exp))}}</td>
							<td>{{$member->rank_name}}</td>
							<td align="right">{{prettyNumber($member->s_booty)}}</td>
							<td align="right">{{prettyNumber($member->s_gold_captured)}}</td>
							<td align="right">{{prettyNumber($member->s_gold_lost)}}</td>
							<td align="right"><font color="{{getUserStatusColor($member->last_activity)}}">{{getClanStatusString($member->last_activity)}}</font></td>
						</tr>
						@endforeach
						<!--<tr>
                        <th colspan="7"><br>green display = the member has got more booty since the last update</th>
                    </tr>
                    <tr>
                        <td class="no-bg" colspan="7">
                            <div class="btn-left left"><div class="btn-right">
                                    <a class="btn" href="/clan/memberlist/?action=refresh">refresh</a>
                                </div>
                            </div>
                        </td>
                    </tr>-->
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