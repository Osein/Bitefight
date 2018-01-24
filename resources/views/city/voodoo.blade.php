@extends('index')

@section('content')
	<div id="voodoo">
		<!-- box HEADER START -->
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<!-- box HEADER END -->
		<!-- box CONTENT START -->
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<!-- CONTENT START -->
				<h2>{{user_race_logo_small()}} Voodoo Shop</h2>
				<div class="buildingDesc clearfix">
					<img class="npc-logo" src="{{asset('img/city/npc/0_6.jpg')}}" align="left">

					<h3>You feel a magical energy streaming through you...</h3>

					<p>Welcome to the Voodoo Shop, a really wondrous place. With a short look into the shop you can guess what kind of mysterious items might be hidden here. Suddenly a goblin appears behind the dusty bar and bows to you: "What can I do for you, Sir?"</p>

					<p class="gold">Your possessions: {{prettyNumber(user()->getGold())}} {{gold_image_tag()}} + {{prettyNumber(user()->getHellstone())}} {{hellstone_image_tag()}}
				</div>
				<h2>{{user_race_logo_small()}}Become a Shadow-Lord now</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody><tr>
							<td class="no-bg" valign="center"><img src="{{asset('img/voodoo/shadowlord.jpg')}}" alt=""></td>
							<td class="no-bg" valign="top">
								<h3>How do I benefit in becoming a Shadow-Lord?</h3>
								<p>
									You get nothing for now LOLLLLL
									<!--- No advertisements (no banners, popups etc.)<br>-->
									<!--- Per activation, you receive a whole day`s pay in Gold<br>-->
									<!--- Doubled action point regeneration rate<br>-->
									<!--- Maximum number of action points +50%<br>-->
									<!--- Hunt statistics<br>-->
									<!--- Advanced opponent search<br>-->
									<!--- Individual character pictures<br>-->
									<!--- And an additional 100% healing potion<br>-->
									<!--- 5 additional folders for your messaging system<br>-->
								</p>
							</td>
							<td class="no-bg" valign="top" align="center">
								<h3 style="padding:0 10px 15px; text-align:right;"><span style="color:orangered">14 days<br> for only <br>15 hellstones</span></h3>
								<div class="btn-left right">
									<div class="btn-right">
										<form method="post">
											{{csrf_field()}}
											<input type="submit" class="btn" name="buy_shadow_lord" value="buy now" @if(user()->getHellstone() < 15) disabled @endif>
										</form>
									</div>
								</div>
							</td>
						</tr>
						@if(user()->getPremium() > time())
						<tr>
							<td class="no-bg" colspan="3" align="center">
								<p class="info">You will be a Shadow-Lord until: {{date('D, d.m.Y - H:i:s', user()->getPremium())}}</p>
							</td>
						</tr>
						@endif
						</tbody></table>    </div>
				<h2>{{user_race_logo_small()}}Premium items</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody><tr id="premItem_1">
							<td class="no-bg" valign="center" width="150">
								<img src="{{asset('img/voodoo/premium_item1.jpg')}}" alt=""></td>
							<td class="no-bg" valign="top">
								<h3>Metamorphosis stone</h3>
								<p>You can change your race with the metamorphosis stone.<br>(You will automatically leave your clan; if you have founded a clan, it will be dissolved)</p>
								<p class="gold">sale price: 50&nbsp;{{hellstone_image_tag()}}</p>
							</td>
							<td class="no-bg" valign="center" align="center" width="250">
								<div id="confirmscreen_1" style="display:none;">
									<table class="noBackground">
										<tbody><tr>
											<td colspan="2">
												<span style="color:#FFCC33;">Are you sure you want to use the item now?</span>
											</td>
										</tr>
										<tr>
											<td width="50%">
												<div class="btn-left right">
													<div class="btn-right">
														<form method="post">
															{{csrf_field()}}
															<input type="submit" class="btn" name="buy_methamorphosis" value="Yes" @if(user()->getHellstone() < 50) disabled @endif>
														</form>
													</div>
												</div>
											</td>
											<td width="50%">
												<div class="btn-left right">
													<div class="btn-right">
														<button class="btn" onclick="notConfirm(1)">No</button>
													</div>
												</div>
											</td>
										</tr>
										</tbody></table>
								</div>
								<div id="usescreen_1">
									<div class="btn-left right">
										<div class="btn-right">
											<button class="btn" onclick="confirmUserPremium(1)">Buy and use this item</button>
										</div>
									</div>
								</div>
							</td>
						</tr>
						</tbody></table>
				</div>
				<script type="text/javascript">
					function confirmUserPremium(nr)
					{
						$('#usescreen_'+nr).css('display', 'none');
						$('#confirmscreen_'+nr).css('display', 'block');
					}

					function notConfirm(nr)
					{
						$('#usescreen_'+nr).css('display', '');
						$('#confirmscreen_'+nr).css('display', 'none');
					}
				</script>
				<!-- CONTENT END -->
			</div>
		</div>
		<!-- box CONTENT END -->
		<!-- box FOOTER START -->
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
		<!-- box CONTENT END -->
	</div>
@endsection