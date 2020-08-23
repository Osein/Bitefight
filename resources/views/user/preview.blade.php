@extends('index')

@section('content')
	<div class="table-wrap">
		<div id="playerUserPic">
			<div class="wrap-top-left">
				<div class="wrap-top-right">
					<div class="wrap-top-middle"></div>
				</div>
			</div>
			<div class="wrap-left">
				<div class="wrap-content wrap-right">
					<img src="{{$puser->show_picture ? asset('img/logo/'.$puser->race.'/'.$puser->gender.'/'.$puser->image_type.'.jpg') : asset('img/symbols/race'.$puser->race.'.gif')}}" border="0" width="168">
					@if($puser->clan_id > 0)
					<a href="{{'/preview/clan/'.$puser->clan_id}}"><img src="{{asset('img/clan/'.$puser->logo_bg.'-'.$puser->logo_sym.'.png')}}" border="0"></a>
					@endif
				</div>
			</div>
			<div class="wrap-bottom-left">
				<div class="wrap-bottom-right">
					<div class="wrap-bottom-middle"></div>
				</div>
			</div>
		</div>
		<div id="player">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<h2>{{user_race_logo_small()}}{{getRaceString($puser->race)}} <span>{{$puser->name}}</span></h2>
					<div class="table-wrap">
						<table width="100%">
							<tbody>
							<tr>
								<td class="no-bg">
									<h3>{{__('general.entire_booty')}}:</h3>
									<p>{{__('general.entire_booty_count', ['count' => prettyNumber($puser->s_booty)])}}</p>
									@if($puser->clan_id > 0)
									<h3>{{__('general.clan')}}:</h3>
									<p><a href="{{url('/preview/clan/'.$puser->clan_id)}}">{{$puser->clan_name}} [{{$puser->clan_tag}}]</a></p>
									<h3>{{__('general.rank')}}:</h3>
									<p>{{$puser->rank_name}}@if($puser->war_minister){{__('general.war_minister')}} @endif</p>
									@endif
								</td>
								<td class="no-bg">
									@if(user() && $puser->id != user()->getId() && !$friendRequestSent && !$isFriend)
										<br class="clearfloat">
										<div class="center">
											<div class="btn-left center">
												<div class="btn-right">
													<a class="btn" href="{{url('/buddy/request/'.$puser->id)}}">Buddy request</a>
												</div>
											</div>
										</div>
									@elseif(isset($friendRequestSent) && $friendRequestSent)
										<br class="clearfloat">
										<div class="center">
											<div class="btn-left center">
												<div class="btn-right">
													<a class="btndisable">Friend request sent</a>
												</div>
											</div>
										</div>
									@elseif(isset($isFriend) && $isFriend)
										<br class="clearfloat">
										<div class="center">
											<div class="btn-left center">
												<div class="btn-right">
													<a class="btndisable">Already friend</a>
												</div>
											</div>
										</div>
									@endif
									<!--<br class="clearfloat">
									<div class="center">
										<div class="btn-left center">
											<div class="btn-right">
												<button type="submit" class="btndisable">Attack</button>
											</div>
										</div>
									</div>-->
									<?php if(user()): ?>
									<br class="clearfloat">
									<div class="center">
										@include('partials/write_message', ['receiverName' => $puser->name])
										<div class="btn-left left">
											<div class="btn-right">
												<a href="#" class="btn" onclick="writeMessageSplash()">Write message</a>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td class="no-bg" colspan="2">
									<h3>{{__('general.character_description')}}:<!-- &nbsp;<a href="/msg/complain/pprofile?id=77625&amp;cc=c3fdd5d" class="copyright">(report)</a>--></h3>

									<p class="char-desc">
										{!! $puser->descriptionHtml !!}
									</p>
								</td>
							</tr>
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
		<br class="clearfloat">
		<div id="otherPlayerStats" style="margin-top: 1rem;">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<h2>{{user_race_logo_small()}}{{__('general.characteristics_of_user', ['user' => e($puser->name)])}}</h2>
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody><tr>
							<td class="tdn">Level:</td>
							<td class="tdn">{{prettyNumber(getLevel($puser->exp))}}</td>
						</tr>
						<tr>
							<td class="tdn">Battle value:</td>
							<td class="tdn">{{prettyNumber($puser->battle_value)}}</td>
						</tr>
						<tr>
							<td class="tdn">Strength:</td>
							<td class="tdn" colspan="2">
								<img src="{{asset('img/b1.gif')}}" alt=""><img src="{{asset('img/b2.gif')}}" alt="" height="12" width="{{$str_red_long}}"><img src="{{asset('img/b3.gif')}}" alt="">
								<span class="fontsmall">({{prettyNumber($puser->str)}})</span>
							</td>
						</tr>
						<tr>
							<td class="tdn">Defence:</td>
							<td class="tdn" colspan="2">
								<img src="{{asset('img/b1.gif')}}" alt=""><img src="{{asset('img/b2.gif')}}" alt="" height="12" width="{{$def_red_long}}"><img src="{{asset('img/b3.gif')}}" alt="">
								<span class="fontsmall">({{prettyNumber($puser->def)}})</span>
							</td>
						</tr>
						<tr>
							<td class="tdn">Dexterity:</td>
							<td class="tdn" colspan="2">
								<img src="{{asset('img/b1.gif')}}" alt=""><img src="{{asset('img/b2.gif')}}" alt="" height="12" width="{{$dex_red_long}}"><img src="{{asset('img/b3.gif')}}" alt="">
								<span class="fontsmall">({{prettyNumber($puser->dex)}})</span>
							</td>
						</tr>
						<tr>
							<td class="tdn">Endurance:</td>
							<td class="tdn" colspan="2">
								<img src="{{asset('img/b1.gif')}}" alt=""><img src="{{asset('img/b2.gif')}}" alt="" height="12" width="{{$end_red_long}}"><img src="{{asset('img/b3.gif')}}" alt="">
								<span class="fontsmall">({{prettyNumber($puser->end)}})</span>
							</td>
						</tr>
						<tr>
							<td class="tdn">Charisma:</td>
							<td class="tdn" colspan="2">
								<img src="{{asset('img/b1.gif')}}" alt=""><img src="{{asset('img/b2.gif')}}" alt="" height="12" width="{{$cha_red_long}}"><img src="{{asset('img/b3.gif')}}" alt="">
								<span class="fontsmall">({{prettyNumber($puser->cha)}})</span>
							</td>
						</tr>
						<tr>
							<td class="tdn">Experience:</td>
							<td class="tdn">
								<img src="{{asset('img/b1.gif')}}"><img src="{{asset('img/b2.gif')}}" height="12" width="{{$exp_red_long}}"><img src="{{asset('img/b4.gif')}}" height="12" width="{{400 - $exp_red_long}}"><img src="{{asset('img/b5.gif')}}"><span class="fontsmall"> ({{prettyNumber($puser->exp)}} / {{prettyNumber(getExpNeeded(getLevel($puser->exp)))}})</span>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="wrap-bottom-left">
				<div class="wrap-bottom-right">
					<div class="wrap-bottom-middle"></div>
				</div>
			</div>
		</div>
		<br class="clearfloat">
		<div id="playerStatistic">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<h2>{{user_race_logo_small()}}Statistics</h2>
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody><tr>
							<td class="tdn"><strong>Entire booty:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_booty)}} Blood</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Fights:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_fight)}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Victories:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_victory)}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Defeats:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_defeat)}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Draws:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_draw)}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Gold captured:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_gold_captured)}} {{gold_image_tag()}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Gold lost:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_gold_lost)}} {{gold_image_tag()}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Damage caused:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_damage_caused)}}</td>
						</tr>
						<tr>
							<td class="tdn"><strong>Hit points lost:</strong></td>
							<td class="tdn">{{prettyNumber($puser->s_hp_lost)}}</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="wrap-bottom-left">
				<div class="wrap-bottom-right">
					<div class="wrap-bottom-middle"></div>
				</div>
			</div>
		</div>
		<br class="clearfloat">
	</div>
@endsection