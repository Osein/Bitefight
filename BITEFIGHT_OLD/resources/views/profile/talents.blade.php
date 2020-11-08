@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right"><a href="{{url('/profile/index#tabs-5')}}" class="btn">{{__('general.back')}}</a></div>
	</div>
	<br class="clearfloat">
	<div id="specialSkills">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{__('user.profile_talent_talents')}}</h2>
				<div class="table-wrap">
					<table id="talentsOptions" width="100%" border="0">
						<tbody><tr>
							<td colspan="2">
                            <span class="gold">
                                {{__('general.gold')}}: {{prettyNumber(user()->getGold())}} {{gold_image_tag()}}
                            </span>&nbsp;&nbsp;<span class="gold">
                                <a href="{{url('/voodoo')}}">{{__('general.hellstones')}}</a>: {{prettyNumber(user()->getHellstone())}} {{hellstone_image_tag()}}
                            </span>
							</td>
						</tr>
						<tr>
							<td valign="top" align="left">
								<table cellpadding="2" cellspacing="2" border="0" class="noBackground">
									<tbody><tr>
										<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_display_filter')}}:</strong></td>
										<td>
											<select size="1" onchange="location = this.options[this.selectedIndex].value;">
												<option value="talents?filter=1" @if($filter == 1) selected @endif >{{__('user.profile_talent_display_filter_learned')}}</option>
												<option value="talents?filter=2" @if($filter == 2) selected @endif >{{__('user.profile_talent_display_filter_learnable')}}</option>
												<option value="talents?filter=3" @if($filter == 3) selected @endif >{{__('user.profile_talent_display_filter_all')}}</option>
											</select>
										</td>
									</tr>
									<tr class="next_point_info">
										<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_available_points')}}:</strong></td>
										<td style="vertical-align:middle;"><center><b>{{$available}}</b></center></td>
									</tr>
									<tr class="next_point_info">
										<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_used_points')}}:</strong></td>
										<td style="vertical-align:middle;"><center><b>{{$used_points}}</b></center></td>
									</tr>
									<tr class="next_point_info">
										<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_max_points')}}:</strong></td>
										<td style="vertical-align:middle;"><center><b>{{$max_points}}</b></center></td>
									</tr>
									<tr class="next_point_info">
										<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_next_points')}}</strong><br>{{__('general.level')}}: {{$next_talent_level}}</td>
										<td style="vertical-align:middle;"><center><b>+2</b></center></td>
									</tr>
									</tbody></table>
							</td>
							<td>&nbsp;</td>
							<td valign="top" align="right">
								<form method="POST">
									{{csrf_field()}}
									<input type="hidden" name="filter" value="{{$filter}}">
									<table cellpadding="2" cellspacing="2" border="0">
										<tbody><tr>
												<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_form_next_point')}}:</strong></td>
												<td><input type="submit" class="input" name="buypoint" value="buy now" @if($max_points - user()->getTalentPoints() == 0 || user()->getGold() < $new_talent_price) disabled @endif ></td>
												<td style="vertical-align:middle;" nowrap="">{{prettyNumber($new_talent_price)}} {{gold_image_tag()}}</td>
										</tr>
										<tr>
												<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_form_reset_gold_and_talents')}}:</strong></td>
												<td><input type="submit" class="input" name="resetpoinths" value="Reset" @if(user()->getHellstone() < 19) disabled @endif></td>
												<td style="vertical-align:middle;" nowrap="">19 {{hellstone_image_tag()}}</td>
										</tr>
										<tr>
												<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_form_reset_all_points')}}:</strong></td>
												<td><input type="submit" class="input" name="resetpointg" value="Reset" @if(user()->getGold() < $talent_reset_price || user()->getTalentPoints() == $available) disabled @endif></td>
												<td style="vertical-align:middle;" nowrap="">{{prettyNumber($talent_reset_price)}} {{gold_image_tag()}}</td>
										</tr>
										<tr>
											<td style="vertical-align:middle;"><strong>{{__('user.profile_talent_form_reset_single_talent')}}:</strong></td>
											<td style="vertical-align:middle;" colspan="2" nowrap="">2 {{hellstone_image_tag()}}</td>
										</tr>
										</tbody>
									</table>
								</form>
							</td>
						</tr>
						</tbody>
					</table>
					<h2>{{user_race_logo_small()}}{{__('general.talents')}}</h2>
					<table border="0" cellpadding="0" cellspacing="2" width="100%" class="talents_bg">
						<tbody><tr class="talents_headline">
							<th>{{__('general.level')}}</th>
							<th colspan="2" align="left">&nbsp;{{__('user.profile_talent_talents')}}</th>
						</tr>
						@foreach($talents as $talent)
						<tr>
							<td align="center" class="talent_level"><b>{{$talent[0]->level}}</b></td>
							<td class="@if($talent[0]->user_id) talent_buyed @elseif(getLevel(user()->getExp()) >= $talent[0]->level && (!isset($talent[1]) || (isset($talent[1]) && !$talent[1]->user_id))) talent_buyable @else talent_inactive @endif">
								<table width="100%" border="0">
									<tbody>
									<tr>
										<td class="no-bg" align="left" width="25"><img src="{{asset('/img/symbols/talent_'.($talent[0]->active?'active':'passive').'.png')}}" title="{{__('user.profile_talent_talent_type_'.$talent[0]->active)}}"></td>
										<td class="no-bg" align="left">
											<div class="tooltip" title='@include('partials.talent_tooltip_content', ['talent_obj' => $talent[0]])'><b>{{__('talents.talent_id_'.$talent[0]->id.'_name')}}</b></div>
										</td>
										<td class="no-bg align-right" align="right">
											@if($talent[0]->user_id && user()->getHellstone() >= 2 && $talent[0]->id != 1)
											<form id="talent_reset{{$talent[0]->id}}" action="{{url('/profile/talents/reset/single')}}" method="post">
												{{csrf_field()}}
												<input type="hidden" name="talent_id" value="{{$talent[0]->id}}">
												<input type="hidden" name="filter" value="{{$filter}}">
											</form>
											<a onclick="document.forms['talent_reset{{$talent[0]->id}}'].submit();" class="buytalent">
												<img src="{{asset('img/symbols/reset.png')}}" alt="{{__('user.profile_talent_reset_single')}}" title="{{__('user.profile_talent_reset_single')}}"/>
											</a>
											@endif

											@if(!$talent[0]->user_id && $available && getLevel(user()->getExp()) >= $talent[0]->level && (!isset($talent[1]) || (isset($talent[1]) && !$talent[1]->user_id)))
											<form id="talent_buy{{$talent[0]->id}}" action="{{url('/profile/talents/use')}}" method="post">
												{{csrf_field()}}
												<input type="hidden" name="talent_id" value="{{$talent[0]->id}}">
												<input type="hidden" name="filter" value="{{$filter}}">
											</form>
											<a onclick="document.forms['talent_buy{{$talent[0]->id}}'].submit();" class="buytalent">
												<img src="{{asset('img/symbols/iconplus.png')}}" alt="{{__('user.profile_talent_buy_now')}}" title="{{__('user.profile_talent_buy_now')}}">
											</a>
											@endif
										</td>
									</tr>
									</tbody>
								</table>
							</td>
							@if(isset($talent[1]))
							<td class="@if($talent[1]->user_id) talent_buyed @elseif(getLevel(user()->getExp()) >= $talent[1]->level && !$talent[0]->user_id) talent_buyable @else talent_inactive @endif">
								<table width="100%" border="0">
									<tbody>
									<tr>
										<td class="no-bg" align="left" width="25"><img src="{{asset('/img/symbols/talent_'.($talent[1]->active?'active':'passive').'.png')}}" title="{{__('user.profile_talent_talent_type_'.$talent[1]->active)}}"></td>
										<td class="no-bg" align="left">
											<div class="tooltip" title='@include('partials.talent_tooltip_content', ['talent_obj' => $talent[1]])'><b>{{__('talents.talent_id_'.$talent[1]->id.'_name')}}</b></div>
										</td>
										<td class="no-bg align-right" align="right">

											@if($talent[1]->user_id && user()->getHellstone() > 1)
											<form id="talent_reset{{$talent[1]->id}}" action="{{url('/profile/talents/reset/single')}}" method="post">
												{{csrf_field()}}
												<input type="hidden" name="talent_id" value="{{$talent[1]->id}}">
												<input type="hidden" name="filter" value="{{$filter}}">
											</form>
											<a onclick="document.forms['talent_reset{{$talent[1]->id}}'].submit();" href="javascript:;" class="buytalent">
												<img src="{{asset('img/symbols/reset.png')}}" alt="{{__('user.profile_talent_reset_single')}}" title="{{__('user.profile_talent_reset_single')}}"/>
											</a>
											@endif
											@if(!$talent[0]->user_id && $available && getLevel(user()->getExp()) >= $talent[1]->level && !$talent[1]->user_id)
											<form id="talent_buy{{$talent[1]->id}}" action="{{url('/profile/talents/use')}}" method="post">
												{{csrf_field()}}
												<input type="hidden" name="talent_id" value="{{$talent[1]->id}}">
												<input type="hidden" name="filter" value="{{$filter}}">
											</form>
											<a onclick="document.forms['talent_buy{{$talent[1]->id}}'].submit();" href="javascript:;" class="buytalent">
												<img src="{{asset('img/symbols/iconplus.png')}}" alt="{{__('user.profile_talent_buy_now')}}" title="{{__('user.profile_talent_buy_now')}}">
											</a>
											@endif
										</td>
									</tr>
									</tbody>
								</table>
							</td>
							@endif
						</tr>
						@endforeach
						</tbody>
					</table>
					<br class="cleafloat">
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