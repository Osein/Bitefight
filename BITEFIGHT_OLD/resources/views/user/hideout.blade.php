@extends('index')

@section('content')
	<div id="fightreport">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}{{__('user.user_hideout_header', ['user' => e(user()->getName())])}}</h2>

				<div id="hideoutPic">
					<div id="hideout_overlay_container">
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/b/bg'.user()->getHLand().'.jpg')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/weg/s'.user()->getHPath().'.gif')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/u/stufe'.user()->getHDomicile().'.gif')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/m/stufe'.user()->getHWall().'.gif')}}');"></div>

						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/chest/stufe'.(user()->getHTreasure() > time()?1:0).'.gif')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/bigchest/stufe'.(user()->getHRoyal() > time()?1:0).'.gif')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/guard/stufe'.(user()->getHGargoyle() > time()?1:0).'.gif')}}');"></div>
						<div class="overlay" style="background-image:url('{{asset('img/hideout/1/book/stufe'.(user()->getHBook() > time()?1:0).'.gif')}}');"></div>
					</div>
				</div>

				<table style="width:100%">
					<tbody>
					<tr>
						<td class="no-bg" style="padding:0;">
							<h2>{{user_race_logo_small()}}{{__('user.user_hideout_upgrade_hideout')}}</h2>
						</td>
					</tr>
					<tr>
						<td class="no-bg" style="padding:0;">
							<table class="upgrade" align="center" cellpadding="2" cellspacing="2" border="0" style="width:100%">
								<tbody>
								<tr>
									<td class="tdn" width="100">
										<img src="{{asset('img/hideout/1/palace5.jpg')}}" alt="{{__('general.treasure_chest')}}">
									</td>
									<td class="tdn" width="150">
										<a href="#hint">{{__('general.treasure_chest')}}<br>({{prettyNumber(getLevel(user()->getExp()) * 4800)}} {{gold_image_tag()}})</a>
									</td>
									<td class="tdn" width="100">
										@if(user()->getHTreasure() < time())
                                        	{{__('general.not_active')}}
										@else
											<span id="treasure_countdown"></span>
											<script type="text/javascript">
												$(function () {
													$("#treasure_countdown").countdown({
														until: +{{user()->getHTreasure() - time()}},
														compact: true,
														compactLabels: ['y', 'm', 'w', 'd'],
														description: '',
														onExpiry: function() {
															setTimeout('window.location = "{{url('/hideout')}}"',3000);
														}
													});
												});
											</script>
										@endif
									</td>
									<td class="center-text">
										<form id="hideout_buy_ht_4" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="treasure">
											<input type="hidden" name="week" value="4">
										</form>
										<a @if(user()->getHellstone() >= 20) onclick="document.forms['hideout_buy_ht_4'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 4])}} 20 {{hellstone_image_tag()}}</a>

										<br>

										<form id="hideout_buy_ht_12" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="treasure">
											<input type="hidden" name="week" value="12">
										</form>
										<a @if(user()->getHellstone() >= 55) onclick="document.forms['hideout_buy_ht_12'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 12])}} 55 {{hellstone_image_tag()}}</a>
									</td>
								</tr>
								<tr>
									<td class="tdn" width="100">
										<img src="{{asset('img/hideout/1/palace8.jpg')}}" alt="{{__('general.royal_chest')}}">
									</td>
									<td class="tdn" width="150">
										<a href="#hint">{{__('general.royal_chest')}}<br>({{prettyNumber(getLevel(user()->getExp()) * 4800 * 4)}} {{gold_image_tag()}})</a>
									</td>
									<td class="tdn" width="100">
										@if(user()->getHRoyal() < time())
                                        	{{__('general.not_active')}}
                                    	@else
										<span id="royal_countdown"></span>
										<script type="text/javascript">
											$(function () {
												$("#royal_countdown").countdown({
													until: +{{user()->getHRoyal() - time()}},
													compact: true,
													compactLabels: ['y', 'm', 'w', 'd'],
													description: '',
													onExpiry: function() {
														setTimeout('window.location = "/bf/user/hideout"',3000);
													}
												});
											});
										</script>
										@endif
									</td>
									<td class="center-text">
										<form id="hideout_buy_rc_6" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="royal">
											<input type="hidden" name="week" value="6">
										</form>
										<a @if(user()->getHellstone() >= 69) onclick="document.forms['hideout_buy_rc_6'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 6])}} 69 {{hellstone_image_tag()}}</a>
									</td>
								</tr>

								<tr>
									<td class="tdn" width="100">
										<img src="{{asset('img/hideout/1/palace6.jpg')}}" alt="{{__('general.gargoyle_guardian')}}">
									</td>
									<td class="tdn" width="150">
										<a href="#hint">{{__('general.gargoyle_guardian')}}</a>
									</td>
									<td class="tdn" width="100">
										@if(user()->getHGargoyle() < time())
											{{__('general.not_active')}}
										@else
											<span id="gargoyle_countdown"></span>
											<script type="text/javascript">
												$(function () {
													$("#gargoyle_countdown").countdown({
														until: +{{user()->getHGargoyle() - time()}},
														compact: true,
														compactLabels: ['y', 'm', 'w', 'd'],
														description: '',
														onExpiry: function() {
															setTimeout('window.location = "/bf/user/hideout"',3000);
														}
													});
												});
											</script>
										@endif
									</td>
									<td class="center-text">
										<form id="hideout_buy_gg_4" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="gargoyle">
											<input type="hidden" name="week" value="4">
										</form>
										<a @if(user()->getHellstone() >= 20) onclick="document.forms['hideout_buy_gg_4'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 4])}} 20 {{hellstone_image_tag()}}</a>

										<br>

										<form id="hideout_buy_gg_12" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="gargoyle">
											<input type="hidden" name="week" value="12">
										</form>
										<a @if(user()->getHellstone() >= 55) onclick="document.forms['hideout_buy_gg_12'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 12])}} 55 {{hellstone_image_tag()}}</a>
									</td>
								</tr>

								<tr>
									<td class="tdn" width="100">
										<img src="{{asset('img/hideout/1/palace7.jpg')}}" alt="{{__('general.book_of_the_damned')}}">
									</td>
									<td class="tdn" width="150">
										<a href="#hint">{{__('general.book_of_the_damned')}}</a>
									</td>
									<td class="tdn" width="100">
										@if(user()->getHBook() < time())
											{{__('general.not_active')}}
										@else
											<span id="book_countdown"></span>
											<script type="text/javascript">
												$(function () {
													$("#book_countdown").countdown({
														until: +{{user()->getHBook() - time()}},
														compact: true,
														compactLabels: ['y', 'm', 'w', 'd'],
														description: '',
														onExpiry: function() {
															setTimeout('window.location = "/bf/user/hideout"',3000);
														}
													});
												});
											</script>
										@endif
									</td>
									<td class="center-text">
										<form id="hideout_buy_botd_4" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="book">
											<input type="hidden" name="week" value="4">
										</form>
										<a @if(user()->getHellstone() >= 20) onclick="document.forms['hideout_buy_botd_4'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 4])}} 20 {{hellstone_image_tag()}}</a>

										<br>

										<form id="hideout_buy_botd_12" method="post">
											{{csrf_field()}}
											<input type="hidden" name="structure" value="book">
											<input type="hidden" name="week" value="12">
										</form>
										<a @if(user()->getHellstone() >= 55) onclick="document.forms['hideout_buy_botd_12'].submit();" @else href="{{url('/voodoo')}}" @endif target="_top" style="display:block; width:260px; text-align:center;">{{__('user.user_hideout_xweek_costs', ['week' => 12])}} 55 {{hellstone_image_tag()}}</a>
									</td>
								</tr>
								<tr>
									<td class="tdn" width="100"><img src="{{asset('img/hideout/1/palace1.jpg')}}" alt="{{__('general.domicile')}}"></td>
									<td class="tdn" width="150"><a href="#hint">{{__('general.domicile')}}</a>
									</td>
									<td class="tdn" width="100">
										{{__('general.level')}} {{user()->getHDomicile()}} / 14
									</td>
									<td class="center-text">
										@if(user()->getHDomicile() == 14)
										-
										@elseif(getHideoutCost('domi', user()->getHDomicile()) <= user()->getGold())
											<form id="hideout_buy_domi" method="post">
												{{csrf_field()}}
												<input type="hidden" name="structure" value="domicile">
											</form>
											<a onclick="document.forms['hideout_buy_domi'].submit();" target="_top">{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('domi', user()->getHDomicile()))}} {{gold_image_tag()}}</a>
										@else
											{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('domi', user()->getHDomicile()))}} {{gold_image_tag()}}
										@endif
									</td>
								</tr>
								<tr>
									<td class="tdn" width="100"><img src="{{asset('img/hideout/1/palace2.jpg')}}" alt="{{__('general.wall')}}"></td>
									<td class="tdn" width="150"><a href="#hint">{{__('general.wall')}}</a></td>
									<td class="tdn" width="100">
										{{__('general.level')}} {{user()->getHWall()}} / 6
									</td>
									<td class="center-text">
										@if(user()->getHWall() == 6)
											-
										@elseif(getHideoutCost('wall', user()->getHWall()) <= user()->getGold())
											<form id="hideout_buy_wall" method="post">
												{{csrf_field()}}
												<input type="hidden" name="structure" value="wall">
											</form>
											<a onclick="document.forms['hideout_buy_wall'].submit();" target="_top">{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('wall', user()->getHWall()))}} {{gold_image_tag()}}</a>
										@else
											{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('wall', user()->getHWall()))}} {{gold_image_tag()}}
										@endif
									</td>
								</tr>
								<tr>
									<td class="tdn" width="100"><img src="{{asset('img/hideout/1/palace3.jpg')}}" alt="{{__('general.path')}}"></td>
									<td class="tdn" width="150"><a href="#hint">{{__('general.path')}}</a></td>
									<td class="tdn" width="100">
										{{__('general.level')}} {{user()->getHPath()}} / 6
									</td>
									<td class="center-text">
										@if(user()->getHPath() == 6)
											-
										@elseif(getHideoutCost('path', user()->getHPath()) <= user()->getGold())
											<form id="hideout_buy_path" method="post">
												{{csrf_field()}}
												<input type="hidden" name="structure" value="path">
											</form>
											<a onclick="document.forms['hideout_buy_path'].submit();" target="_top">{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('path', user()->getHPath()))}} {{gold_image_tag()}}</a>
										@else
											{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('path', user()->getHPath()))}} {{gold_image_tag()}}
										@endif
									</td>
								</tr>
								<tr>
									<td class="tdn" width="100"><img src="{{asset('img/hideout/1/palace4.jpg')}}" alt="{{__('general.landscape')}}"></td>
									<td class="tdn" width="150"><a href="#hint">{{__('general.landscape')}}</a></td>
									<td class="tdn" width="100">
										{{__('general.level')}} {{user()->getHLand()}} / 6
									</td>
									<td class="center-text">
										@if(user()->getHLand() == 6)
											-
										@elseif(getHideoutCost('land', user()->getHLand()) <= user()->getGold())
											<form id="hideout_buy_land" method="post">
												{{csrf_field()}}
												<input type="hidden" name="structure" value="land">
											</form>
											<a onclick="document.forms['hideout_buy_land'].submit();" target="_top">{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('land', user()->getHLand()))}} {{gold_image_tag()}}</a>
										@else
											{{__('user.user_hideout_next_level_costs')}} {{prettyNumber(getHideoutCost('land', user()->getHLand()))}} {{gold_image_tag()}}
										@endif
									</td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td class="tdh"><a name="hint">{{__('general.tip')}}:</a></td>
					</tr>
					<tr>
						<td class="tdn" style="text-align:justify">
                            <span class="text">
                                {!! __('user.user_hideout_info_header') !!}<br><br>
								{!! __('user.user_hideout_info_treasure') !!}<br><br>
								{!! __('user.user_hideout_info_royal') !!}<br><br>
								{!! __('user.user_hideout_info_gargoyle') !!}<br><br>
								{!! __('user.user_hideout_info_book') !!}<br><br>
								{!! __('user.user_hideout_info_domi') !!}<br><br>
								{!! __('user.user_hideout_info_wall') !!}@if(user()->getHWall() > 0)<br>{{__('user.user_hideout_info_wall_personal', ['level' => user()->getHWall(), 'effect' => getWallEffect(user()->getHWall())])}}@endif<br><br>
								{!! __('user.user_hideout_info_path') !!}<br><br>
								{!! __('user.user_hideout_info_land') !!}@if(user()->getHLand() > 0)<br>{{__('user.user_hideout_info_land_personal', ['level' => user()->getHLand(), 'effect' => getLandEffect(user()->getHLand())])}}@endif<br><br>
                            </span>
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
@endsection