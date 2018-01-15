@extends('index')

@section('content')
	<!--
	<p id="upgrademsg">
		<a href="http://s202.en.bitefight.gameforge.com/city/voodoo" >Become a Shadow-Lord now! Hunt for longer and much more...</a>
	</p>
	-->

	<script type="text/javascript">
		jQuery().ready(function(){
			// show div that is hidden on load to hide the graphics from showing on top of the loading page
			$('#tabs-6').show();

			$('#tabs').tabs();
		});
	</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Character</a></li>
			<li><a href="#tabs-2">Attributes</a></li>
			<li><a href="#tabs-3">Statistics</a></li>
			<li><a href="#tabs-4">Fight modifications</a></li>
			<li><a href="#tabs-5">Talents</a></li>
			<!--<li><a href="#tabs-6">Aspects</a></li>
			<li><a href="#tabs-7">Orbs</a></li>-->
		</ul>
		<div id="tabs-1">
			<div id="character_tab">
				<div class="wrap-top-left clearfix">
					<div class="wrap-top-right clearfix">
						<div class="wrap-top-middle clearfix"></div>
					</div>
				</div>
				<div class="wrap-left clearfix">
					<div class="wrap-content wrap-right clearfix">
						<div id="character_tab">
							<div id="userPic">
								<div class="wrap-top-left">
									<div class="wrap-top-right">
										<div class="wrap-top-middle"></div>
									</div>
								</div>
								<div class="wrap-left">
									<div class="wrap-content wrap-right">
										<a id="userLogo" href="{{url('/profile/logo')}}" >
											<img src="{{asset('img/logo/'.\Illuminate\Support\Facades\Auth::user()->getRace().'/'.\Illuminate\Support\Facades\Auth::user()->getGender().'/'.\Illuminate\Support\Facades\Auth::user()->getImageType().'.jpg')}}" border="0" width="168" alt="playerlogo">
											<br>
											<span>edit picture</span>
										</a>
									</div>
								</div>
								<div class="wrap-bottom-left">
									<div class="wrap-bottom-right">
										<div class="wrap-bottom-middle"></div>
									</div>
								</div>
							</div>
							<table cellpadding="2" cellspacing="2" border="0" width="520px" style="float: right;">
								<tr>
									<td nowrap>County:</td>
									<td nowrap width="90%">{{env('APP_NAME')}}</td>
								</tr>
								<tr>
									<td nowrap>Race:</td>
									<td nowrap>{{\Database\Models\User::getRaceString(\Illuminate\Support\Facades\Auth::user()->getRace())}}<!--&nbsp;&nbsp;<a href="/city/voodoo#premItem_1" title="To the metamorphosis stone"><img src="http://s202.en.bitefight.gameforge.com/img/symbols/metastone.gif"></a>--></td>
								</tr>
								<tr>
									<td nowrap>Player ID:</td>
									<td nowrap>{{\Illuminate\Support\Facades\Auth::user()->getId()}}</td>
								</tr>
								<tr>
									<td nowrap>Player name:</td>
									<td nowrap><a href="{{url('/player/'.\Illuminate\Support\Facades\Auth::user()->getId())}}">{{\Illuminate\Support\Facades\Auth::user()->getName()}}</a></td>
								</tr>
								<tr>
									<td nowrap>Level:</td>
									<td nowrap>{{prettyNumber(\Database\Models\User::getLevel(\Illuminate\Support\Facades\Auth::user()->getExp()))}}</td>
								</tr>
								<tr>
									<td nowrap>Battle value:</td>
									<td nowrap>{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getBattleValue())}}</td>
								</tr>
								<tr>
									<td nowrap>Highscore position:</td>
									<td nowrap>{{prettyNumber($highscore_position)}}</td>
								</tr>
								@if(\Illuminate\Support\Facades\Auth::user()->getClanId() > 0)
								<tr>
									<td nowrap>Clan Highscore position:</td>
									<td nowrap>fix</td>
								</tr>
								@endif
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
		</div>
		<!--
		<div id="tabs-6" style="display:none;">
			<div id="aspects_tab">
				<div class="wrap-top-left clearfix">
					<div class="wrap-top-right clearfix">
						<div class="wrap-top-middle clearfix"></div>
					</div>
				</div>
				<div class="wrap-left clearfix">
					<div class="wrap-content wrap-right clearfix">
						<div style="padding:5px;">
							<div class="aspect_overview clearfix" style="height:700px;">
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_1.gif"  alt="Human" style="position:absolute;left:309px;top:125px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/human.gif" class="triggerTooltip" style="position:absolute;left:313px;top:28px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Human</b><br/>
									974<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_2.gif" style="position:absolute;left:359px;top:184px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/knowledge.gif" class="triggerTooltip"  style="position:absolute;left:529px;top:95px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Knowledge</b><br/>
									1009<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_3.gif" style="position:absolute;left:377px;top:309px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/regularity.gif" class="triggerTooltip"  style="position:absolute;left:598px;top:314px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Order</b><br/>
									991<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_4.gif" style="position:absolute;left:357px;top:353px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/nature.gif" class="triggerTooltip"  style="position:absolute;left:528px;top:527px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Nature</b><br/>
									996<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_5.gif" style="position:absolute;left:307px;top:379px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/beast.gif" class="triggerTooltip"  style="position:absolute;left:312px;top:599px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Beast</b><br/>
									1015<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_6.gif" style="position:absolute;left:188px;top:354px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/destruction.gif" class="triggerTooltip"  style="position:absolute;left:97px;top:527px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Destruction</b><br/>
									992<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_7.gif" style="position:absolute;left:125px;top:308px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/chaos.gif" class="triggerTooltip"  style="position:absolute;left:26px;top:315px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Chaos</b><br/>
									1000<br/>
									Effect: -        </div>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/Aspekte_pfeil_grau_8.gif" style="position:absolute;left:188px;top:186px;"/>
								<img src="http://s202.en.bitefight.gameforge.com/img/story/aspects/corruption.gif" class="triggerTooltip" style="position:absolute;left:97px;top:96px;"/>
								<div class="tooltip" style="text-align:left;">
									<b>Corruption</b><br/>
									1023<br/>
									Effect: -        </div>
							</div>
							<br/>
							<div style="text-align:left;">
								<table width="100%">
									<tr><td>Human</td><td>974</td><td width="100%">-</td></tr><tr><td>Knowledge</td><td>1009</td><td width="100%">-</td></tr><tr><td>Order</td><td>991</td><td width="100%">-</td></tr><tr><td>Nature</td><td>996</td><td width="100%">-</td></tr><tr><td>Beast</td><td>1015</td><td width="100%">-</td></tr><tr><td>Destruction</td><td>992</td><td width="100%">-</td></tr><tr><td>Chaos</td><td>1000</td><td width="100%">-</td></tr><tr><td>Corruption</td><td>1023</td><td width="100%">-</td></tr>        </table>

								<br/>
								There are 8 aspects that describe the player character`s nature. Each aspect has 2 related aspects (e.g. Corruption - Human - Knowledge) and an opposite aspect (e.g. Human <> Beast). Every player has a total of 8,000 aspect points. A new player starts off with 1,000 aspect points on every aspect.
								Aspects are changed by actions in quests. If, for example, you win points for the human aspect, you will simultaneously lose points in the beast aspect (its opposite aspect).
								If an aspect increases, the related aspects will also increase. The opposite aspect will decrease a total of just as much as the three opposite aspects together!
								If you increase an aspect above a certain threshold value, you will receive different bonuses on attributes or fight modifications.        <br/><br/>
							</div>
						</div>
					</div>
				</div>
				<div class="wrap-bottom-left">
					<div class="wrap-bottom-right">
						<div class="wrap-bottom-middle"></div>
					</div>
				</div>
			</div>
		</div>
		-->
		<!--
		<div id="tabs-7">
			<div id="orbs_tab">
				<div class="wrap-top-left clearfix">
					<div class="wrap-top-right clearfix">
						<div class="wrap-top-middle clearfix"></div>
					</div>
				</div>
				<div class="wrap-left clearfix">
					<div class="wrap-content wrap-right clearfix">
						<script type="text/javascript">
							function submitOrbGenerationFormWithSubmitType(sender, type)
							{
								if ($(sender).hasClass('btndisable'))
								{
									return false;
								}

								$('#orb_generation_form [name=action]').val(type);
								$('#orb_generation_form').submit();

								return true;
							}

							function setDefaultFeedbackState()
							{
								var defaultOrbGenerationFeedbackText = '(minimum 20, maximum 100)';
								$(".generate_orb_button").removeClass("btn");
								$(".generate_orb_button").addClass("btndisable");
								$(".extra_costs_text").html(0);
								$("#generate_splinters_amount_feedback").html(defaultOrbGenerationFeedbackText);
							}

							function orbGenerationRequirementsCheck()
							{
								var timer;
								var value;
								var minSplinters = 20;
								var maxSplinters = 100;
								var playerTs = 3;
								var playerSplinters = 3213;

								value = parseInt($("#splinterAmount").val());
								if (timer)
								{
									clearTimeout(timer);
									timer = null
								}

								timer = setTimeout(function()
								{
									if (isNaN(value)) {
										setDefaultFeedbackState();
										return;
									}

									if (value < minSplinters) {
										setDefaultFeedbackState();
										$("#generate_splinters_amount_feedback").html('<span style="color:#C00C0C;">Minimum of 20 fragments</span>');
										return;
									}

									if (value > maxSplinters) {
										setDefaultFeedbackState();
										$("#generate_splinters_amount_feedback").html('<span style="color:#C00C0C;">Maximum 100 fragments</span>');
										return;
									}

									if (value > playerSplinters) {
										setDefaultFeedbackState();
										$("#generate_splinters_amount_feedback").html('<span style="color:#C00C0C;">not enough fragments</span>');
										return;
									}

									if (!$("input[@name='orbType']:checked").val()) {
										setDefaultFeedbackState();
										return;
									}

									if (value <= playerSplinters)
									{
										$.getJSON("/profile/ajaxCalculateOrbGenerationExtraCosts",
											'splinter='+value,
											function(data)
											{
												if (data.status)
												{
													$("#increased_gold_gold_costs").html(data.messages.increasedGoldPrice);
													$("#full_gold_gold_costs").html(data.messages.fullGoldPrice);
													$("#full_premium_premium_costs").html(data.messages.fullPremiumPrice);

													$("#generate_splinters_button_standard, #generate_splinters_button_increased_gold, #generate_splinters_button_full_gold, #generate_splinters_button_full_premium").removeClass("btndisable");
													$("#generate_splinters_button_standard, #generate_splinters_button_increased_gold, #generate_splinters_button_full_gold, #generate_splinters_button_full_premium").addClass("btn");
												}
												else
												{
													setDefaultFeedbackState();
												}
											}
										);
									}

								}, 500);
							}

							jQuery().ready(function()
							{
								$('.accordion').accordion({ autoHeight: false });

								setDefaultFeedbackState();
								var canCreate = 1;
								if (canCreate)
								{
									$("#splinterAmount").keyup(function ()
									{
										orbGenerationRequirementsCheck();
									});
									$('#orb_generation_form [name=orbType]').click(function ()
									{
										orbGenerationRequirementsCheck();
									});
								}
							});
						</script>

						<h3><div id="orb_generation_info_icon" onclick="$('#orb_generation_info_content').toggle();"><img src="http://s202.en.bitefight.gameforge.com/img/symbols/info.png"/></div>Create mighty orbs that make you stronger and more powerful.<div id="orb_generation_info_content">
								<h3>Information about the orbs</h3>
								<p>Orbs are crystal balls with which you can temporarily improve items. During its effect duration, an orb effects every equipped item in the concerned category. If you switch your weapon while a weapon orb is in effect, the effect will then work for the new weapon.<br/>
									Orbs take up space and you can only have as many as the level of your own domicile in the hideout has.<br/>
									You can use a maximum of 4 Orbs at the same time, but they need to belong to different categories. You can't, for example, use two weapon orbs at the same time.<br/>
									Orbs can be sold at the market place. Once they have been sold, they are soul bound.<br/>
									Some orbs give you access to talents: orb aura works like transparency, orb song works like the Negation Master, orb rage works like pure power and orb life works like the great feast. You can use the auras provided by the orbs independently of your level.<br/>
									You need at least 20 fragments and a maximum of 100 fragments per orb. The more fragments you use, the mightier the orb becomes.<br/>
									Normally there is a 25% chance that you can successfully produce an orb.<br/>
									You can find fragments everywhere: in the grotto, whilst hunting humans, whilst experiencing adventures or as a reward in the mission system.<br/>
									You can also get a fragment when you defeat other players that are stronger than you. The fragments are not taken away from other players, but newly created instead.<br/>
									Orbs bestow great power onto you, so don't hesitate to use them!</p></div></h3>
						<form id="orb_generation_form" action="http://s202.en.bitefight.gameforge.com/profile/index#tabs-7" method="POST">
							<div id="firstStep">
								<h4><span>1.</span>Choose the number of fragments!</h4>
								<p class="choose_splinters">Your fragments: 3.213 <img src="http://s202.en.bitefight.gameforge.com/img/symbols/res_splinters.png" alt="Fragments" align="absmiddle" border="0">           <span class="generate_to">»</span>Used fragments:<input type="text" name="splinterAmount" id="splinterAmount" /></p>
								<p id="generate_splinters_amount_feedback"></p>
							</div>
							<div id="secondStep">
								<h4><span>2.</span>Choose the item category that the orb is meant to work for!</h4>
								<input type="radio" name="orbType" id="orbType" value="weapon">Weapon&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="armor">Armour&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="shield">Shield&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="boots">Boots&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="gloves">Gloves&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="helmet">Helmets&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="orbType" id="orbType" value="items">Jewellery&nbsp;&nbsp;&nbsp;&nbsp;    </div>
							<div id="thirdStep">
								<h4><span>3.</span>Choose a chance of success and generate your orb!</h4>
								<p>
								<div class="btn-left">
									<div class="btn-right">
										<a id="generate_splinters_button_standard" class="generate_orb_button btndisable" href="#" onclick="return submitOrbGenerationFormWithSubmitType(this, 'standard');">
											<strong>50</strong>% chance for 0 <img src="http://s202.en.bitefight.gameforge.com/img/symbols/res2.gif" alt="Gold" align="absmiddle" border="0">                	</a>
									</div>
								</div>
								<div class="btn-left">
									<div class="btn-right">
										<a id="generate_splinters_button_increased_gold" class="generate_orb_button btndisable" href="#" onclick="return submitOrbGenerationFormWithSubmitType(this, 'increasedgold');">
											<strong>75</strong>% chance for <span id="increased_gold_gold_costs" class="extra_costs_text">0</span> <img src="http://s202.en.bitefight.gameforge.com/img/symbols/res2.gif" alt="Gold" align="absmiddle" border="0">					</a>
									</div>
								</div>
								<div class="btn-left">
									<div class="btn-right">
										<a id="generate_splinters_button_full_gold" class="generate_orb_button btndisable" href="#" onclick="return submitOrbGenerationFormWithSubmitType(this, 'fullgold');">
											<strong>100</strong>% chance for <span id="full_gold_gold_costs" class="extra_costs_text">0</span> <img src="http://s202.en.bitefight.gameforge.com/img/symbols/res2.gif" alt="Gold" align="absmiddle" border="0">                    </a>
									</div>
								</div>
								<div class="btn-left">
									<div class="btn-right">
										<a id="generate_splinters_button_full_premium" class="generate_orb_button btndisable" href="#" onclick="return submitOrbGenerationFormWithSubmitType(this, 'fullpremium');">
											<strong>100</strong>% chance for <span id="full_premium_premium_costs" class="extra_costs_text">0</span> <img src="http://s202.en.bitefight.gameforge.com/img/symbols/res3.gif" alt="Hellstones" align="absmiddle" border="0">                    </a>
									</div>
								</div>
							</div>

							<br/>

							<input type="hidden" name="action" value=""/>

							<div style="clear:both"></div>
						</form>
						<div class="accordion">
							<h3><a href="#" title="">Active orbs ( 0 / 4 )</a></h3>
							<div>
							</div>
							<h3><a href="#">Available orbs ( 0 / 11 )</a></h3>
							<div>
							</div>
						</div>
					</div>
				</div>
				<div class="wrap-bottom-left">
					<div class="wrap-bottom-right">
						<div class="wrap-bottom-middle"></div>
					</div>
				</div>
			</div>
		</div>
		-->
	</div>
@endsection