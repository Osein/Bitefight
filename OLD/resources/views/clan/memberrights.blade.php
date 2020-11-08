@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">back</a>
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
				<div class="table-wrap">
					<form action="{{url('/clan/memberrights/editrights')}}" method="POST">
						{{csrf_field()}}
						<table width="100%">
							<tbody>
							@foreach($users as $clan_user)
							<tr>
								<td><a class="member-name" href="{{url('/profile/player/'.$clan_user->id)}}">{{$clan_user->name}}</a></td>
								<td>
									@if($user_rank->add_members && $clan_user->id != user()->getId())
									<div class="btn-left left"><div class="btn-right"><a class="btn" href="{{url('/clan/memberrights/kickuser/'.$clan_user->id.'?_token='.csrf_token())}}">kick</a></div></div>
									@endif
									@if(user()->getClanRank() == 1 && $clan_user->id != user()->getId())
									<div class="btn-left left">
										<div class="btn-right">
											@if($setOwnerId == $clan_user->id)
												<a class="btn" href="{{url('/clan/memberrights/setmaster/'.$clan_user->id.'?_token='.csrf_token())}}"><font color="yellow"> ---&gt;Declare a master&lt;--- </font></a>
											@else
												<a class="btn" href="{{url('/clan/memberrights/setowner/'.$clan_user->id.'?_token='.csrf_token())}}">Declare a master</a>
											@endif
										</div>
									</div>
									@endif
								</td>
								<td>
									<select name="users[{{$clan_user->id}}]" size="1" @if($clan_user->rank_id == 1) disabled @endif >
										@foreach($ranks as $rank)
										<option value="{{$rank->id}}" @if($clan_user->rank_id == $rank->id) selected @endif >
											{{$rank->rank_name}}
										</option>
										@endforeach
									</select>
								</td>
							</tr>
							@endforeach
							</tbody></table>
						<div class="btn-left right"><div class="btn-right"><input class="btn" type="submit" value="Save"></div></div>
						<br class="clearfloat">
					</form>
				</div>
				<h3>add rank</h3>
				<form id="addRank" class="clearfix" action="{{url('/clan/memberrights/addrank')}}" method="POST">
					{{csrf_field()}}
					<label>Name</label>
					<input type="text" name="newRank" size="30" maxlength="30">
					<div class="btn-left left"><div class="btn-right"><input class="btn" type="submit" value="add"></div></div>
				</form>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>

	<div id="handleRank">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}rank options</h2>
				<form action="{{url('clan/memberrights/editranks')}}" method="POST">
					{{csrf_field()}}
					<div class="info">
						<b>Please remember that seals can only belong to strong clans:</b><br>
						A clan needs at least 3 members to be able to own a seal.<br>
						A clan needs at least 3 members to be able to fight for a seal.<br>
						A clan needs at least 6 members to be able to carry out seal rituals.
					</div>
					<table width="100%">
						<tbody>
						<tr>
							<td class="c">Name</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/newsread.gif')}}" alt="read messages" title="read messages">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/newswrite.gif')}}" alt="Write message" title="Write message">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/clannewsread.gif')}}" alt="Read Clan Messages" title="Read Clan Messages">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/useradd.gif')}}" alt="add members" title="add members">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/newsdel.gif')}}" alt="delete message" title="delete message">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/clannewswrite.gif')}}" alt="send a message to your clan" title="send a message to your clan">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/gold.gif')}}" alt="Spend gold" title="Spend gold">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/warlord.gif')}}" alt="War minister" title="War minister">
							</td>
							<td class="c" align="center">
								<img src="{{asset('img/clan/clanritus.gif')}}" alt="Vocalise a clan ritual" title="Vocalise a clan ritual">
							</td>
						</tr>
						@foreach($ranks as $rank)
						<tr>
							<th>
								{{$rank->rank_name}}
								@if($rank->id > 3)
								<br>
								<a href="{{url('/clan/memberrights/deleterank/'.$rank->id.'?_token='.csrf_token())}}">(delete)</a>
								@endif
							</th>
							<th><input type="checkbox" @if($rank->read_message) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][read_message]"></th>
							<th><input type="checkbox" @if($rank->write_message) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][write_message]"></th>
							<th><input type="checkbox" @if($rank->read_clan_message) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][read_clan_message]"></th>
							<th><input type="checkbox" @if($rank->add_members) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][add_members]"></th>
							<th><input type="checkbox" @if($rank->delete_message) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][delete_message]"></th>
							<th><input type="checkbox" @if($rank->send_clan_message) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][send_clan_message]"></th>
							<th><input type="checkbox" @if($rank->spend_gold) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][spend_gold]"></th>
							<th><input type="checkbox" @if($rank->war_minister) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][war_minister]"></th>
							<th><input type="checkbox" @if($rank->vocalise_ritual) checked @endif @if($rank->clan_id == 0) disabled @endif name="ranks[{{$rank->id}}][vocalise_ritual]"></th>
						</tr>
						@endforeach
						</tbody>
					</table>
					<div class="btn-left right"><div class="btn-right"><input class="btn" name="rs" type="submit" value="Save"></div></div>
					<br class="clearfloat">
				</form>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection