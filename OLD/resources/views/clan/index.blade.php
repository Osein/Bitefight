@extends('index')

@section('content')
	@if(user()->getClanId() == 0)
	<div id="createClan">
		<div class="wrap-top-left clearfix"><div class="wrap-top-right clearfix"><div class="wrap-top-middle clearfix"></div></div></div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Clan</h2>
				<div class="clearfix">
					<div class="btn-left left"><div class="btn-right"><a href="{{url('/clan/create')}}" class="btn">Found a clan</a></div></div>
					<div class="btn-left left"><div class="btn-right"><a href="{{url('/search')}}" class="btn">Search clans</a></div></div>
					<br class="clearfloat">
				</div>
			</div>
		</div>
		<div class="wrap-bottom-left"><div class="wrap-bottom-right"><div class="wrap-bottom-middle"></div></div></div>
	</div>
	@else
	<div id="clanOverview">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}} Clan [{{$clan->tag}}] (since {{date('d.m.Y H:i', $clan->found_date)}})</h2>
				<div class="table-wrap">
					<table width="100%">
						<tbody>
						<tr>
							<td class="no-bg" style="text-align:center;" rowspan="10" align="center">
								<a href="{{url('/clan/logo/background')}}"><img src="{{asset('img/clan/'.$clan->logo_bg.'-'.$clan->logo_sym.'.png')}}" border="0"></a>
							</td>
						</tr>
						<tr>
							<td>Name</td>
							<td>{{$clan->name}}</td>
						</tr>
						<tr>
							<td>Homepage</td>
							<td>@if(strlen($clan->website) > 0) <a href="{{url('/clan/view/homepage/'.$clan->id)}}" target="_blank">{{$clan->website}}</a>	({{prettyNumber($clan->website_counter)}} Visitors) @else no clan page available @endif </td>
						</tr>
						<tr>
							<td>Total booty</td>
							<td>{{prettyNumber($totalBlood)}} Blood</td>
						</tr>
						<tr>
							<td>Members</td>
							<td><a href="{{url('/clan/memberlist')}}">{{$member_count}} / {{$clan->stufe == 0 ? 1 : $clan->stufe * 3}} Member</a></td>
						</tr>
						<tr>
							<td>Capital</td>
							<td>{{prettyNumber($clan->capital)}}{{gold_image_tag()}}</td>
						</tr>
						<tr>
							<td>Your rank</td>
							<td>{{$rank->rank_name}}</td>
						</tr>
						</tbody>
					</table>

					@if($rank->id == 1 || $rank->id == 2 || $rank->send_clan_message || $rank->spend_gold)
					<h2>{{user_race_logo_small()}} Clan settings</h2>
					<p></p>
					<table width="100%">
						<tbody>
						@if($rank->id == 1 || $rank->id == 2)
						<tr><th colspan="2"><a href="{{url('/clan/description')}}">add description</a></th></tr>
						<tr><th colspan="2"><a href="{{url('/clan/logo/background')}}">edit clan symbol</a></th></tr>
						<tr><th colspan="2"><a href="{{url('/clan/change/homepage')}}">set as homepage</a></th></tr>
						<tr><th colspan="2"><a href="{{url('/clan/change/name')}}">rename clan</a></th></tr>
						@endif

						@if($rank->send_clan_message)
						<tr><th colspan="2"><a href="{{url('/clan/mail')}}">send a message to your clan</a></th></tr>
						@endif

						@if($rank->id == 1 || $rank->id == 2)
						<tr><th colspan="2"><a href="{{url('/clan/memberrights')}}">manage members</a></th></tr>
						@endif

						@if($rank->spend_gold)
						<tr><th colspan="2"><a href="{{url('/clan/donationlist')}}">view the donations made to your clan</a></th></tr>
						@endif
						</tbody>
					</table>
					<p></p>
					@endif

				<!--
                <h2>{{user_race_logo_small()}} Clan chat</h2>
                <script>
                    function ajaxLoadContent(targetSelector, targetAction, requestData, reloadCheck)
                    {
                        if ($(targetSelector).html() != '' && reloadCheck)
                        {
                            return;
                        }
                        $(targetSelector).html(
                            '<img src="/img/ajax/ajax_loader_large.gif" alt="loading.." />'
                        );

                        var successFunc = function(htmlContent)
                        {
                            $(targetSelector).html(htmlContent);

                            activateTooltipsForSelector(targetSelector);
                        };

                        ajaxSendRequestHtml(targetAction, requestData, successFunc);
                    }

                    function ajaxReplaceContent(targetSelector, targetAction, requestData)
                    {
                        var successFunc = function(htmlContent)
                        {
                            $(targetSelector).html(htmlContent);

                            activateTooltipsForSelector(targetSelector);
                        };

                        ajaxSendRequestHtml(targetAction, requestData, successFunc);
                    }

                    function ajaxSendRequestHtml(targetAction, requestData, successFunc)
                    {
                        $.get('/ajax/' + targetAction,
                            requestData,
                            successFunc,
                            'html');
                    }

                    function activateTooltipsForSelector(selector)
                    {
                        $(selector + ' .triggerTooltip').tooltip({
                            effect: 'toggle',
                            delay: 0,
                            position: 'center right',
                            relative: true
                        }).dynamic({ bottom: { direction: 'down', bounce: true } });
                    }
                </script>
                <div class="btn-left center">
                    <div class="btn-right">
                        <input type="button" id="clanChat" class="btn" href="#" value="Start Clan chat">
                    </div>
                </div>
                -->

				<!--
                <h2>{{user_race_logo_small()}} Clan fights</h2>
                <p></p>
                <table id="clanwarsOverview" width="100%">
                    <tbody><tr>
                        <th colspan="2">The clan can only take part in clan fights when it has reached level 1 and has at least 3 members.</th>
                    </tr>
                    </tbody>
                </table>
                <p></p>
                <br>
                -->

				<!--
                <h2>{{user_race_logo_small()}} Clan rituals</h2>
                <table width="100%" id="ritusOverview">
                    <tbody>
                    <tr>
                        <th>
                            <div class="btn-left center">
                                <div class="btn-right">
                                    <a class="btn" href="/clan/ritus/">
                                        Vocalise a clan ritual                            </a>
                                </div>
                            </div>
                        </th>
                    </tr>
                    </tbody>
                </table>
                There are currently no active rituals
                -->

					@if($rank->add_members)
					<h2>{{user_race_logo_small()}} Clan applications</h2>
					<p></p>
					<table width="100%">
						<tbody>
						<tr>
							@if($clan_application_count)
							<th colspan="2"><a href="{{url('/clan/applications')}}">Application({{$clan_application_count}})</a></th>
							@else
							<th colspan="2">No Applications</th>
							@endif
						</tr>
						</tbody>
					</table>
					<p></p>
					@endif


					<h2>{{user_race_logo_small()}} View of the clan hideout</h2>
					<div style="background: url('{{url('img/clan/hideout/bg1.jpg')}}') no-repeat center;">
						<div style="position: relative; height: 584px;">
							<div style="position: absolute; width:100%; text-align: center;">
								<img src="{{asset('img/clan/hideout/1_'.$clan->stufe.'.jpg')}}">
							</div>
						</div>
					</div>

					@if($rank->spend_gold)
					<h2>{{user_race_logo_small()}} Clan hideout</h2>
					<p class="gold">Capital: {{prettyNumber($clan->capital)}}{{gold_image_tag()}}</p>
					<p></p>
					<table width="100%">
						<tbody>
						@for($i = 1; $i <= $clan->stufe; $i++)
						<tr>
							<th align="right">Level: {{$i}}</th>
							<th align="right">Members: {{$i * 3}}</th>
							<th align="center">OK</th>
						</tr>
						@endfor
						@if($clan->stufe < 18)
						<tr>
							<th align="right">Level: {{$clan->stufe + 1}}</th>
							<th align="right">Members: {{($clan->stufe + 1) * 3}}</th>
							<th align="center">
								@if($clan->capital >= getClanHideoutCost($clan->stufe + 1))
									<a href="{{url('/clan/hideout/upgrade?_token='.csrf_token())}}">
								@endif
									The next level costs<br>{{prettyNumber(getClanHideoutCost($clan->stufe + 1))}}{{gold_image_tag()}}
								@if($clan->capital >= getClanHideoutCost($clan->stufe + 1))
									</a>
								@endif
							</th>
						</tr>
						@endif
						</tbody>
					</table>
					<p></p>
					@endif

					<h2>{{user_race_logo_small()}} Donate into the clan account</h2>
					<p class="gold">Your gold: {{prettyNumber(user()->getGold())}} {{gold_image_tag()}}</p>
					<p></p>
					<form action="{{url('/clan/donate')}}" method="POST">
						{{csrf_field()}}
						<table width="50%" align="center">
							<tbody>
							<tr>
								<th>How much would you like to donate?</th>
								<th align="right">
									<input type="text" name="donation" size="10" maxlength="10">{{gold_image_tag()}}
								</th>
							</tr>
							<tr>
								<th colspan="2">
									<div class="btn-left center">
										<div class="btn-right">
											<input class="btn" type="submit" name="donate" value="donate">
										</div>
									</div>
								</th>
							</tr>
							</tbody>
						</table>
					</form>
					<p></p>

					<h2>{{user_race_logo_small()}} Clan description</h2>
					<p style="text-align:center">
						@if(strlen($clan->description) > 0)
							{!! $clan->descriptionHtml !!}
						@else
							no description
						@endif
					</p>

					@if($rank->read_message)
					<h2>{{user_race_logo_small()}} messages</h2>
					<p></p>
					<table width="100%">
						<tbody>
						@if(count($clan_messages) > 0)
						<tr>
							<th align="left">Sender</th>
							<th align="left">Content</th>
							@if($rank->delete_message)
							<th></th>
							@endif
						</tr>
						@foreach($clan_messages as $message)
						<tr>
							<td width="30%" align="left"><a href="{{url('/preview/player/'.$message->user_id)}}">{{$message->name}}</a> ({{$message->rank_name}})<br>{{date('D, d.m.Y - H:i:s', $message->message_time)}}</td>
							<td width="40%" align="left"><div style="width:300px;overflow-x:auto;">{{$message->clan_message}}</div></td>
							@if($rank->delete_message)
							<td>
								<div class="btn-left left">
									<div class="btn-right">
										<a class="btn" href="{{url('/clan/deletemessage?message_id='.$message->id.'&_token='.csrf_token())}}">delete message</a>
									</div>
								</div>
							</td>
							@endif
						</tr>
						@endforeach
						@else
						<tr>
							<th colspan="3">no messages</th>
						</tr>
						@endif
						</tbody>
					</table>
					<p></p>
					@endif

					@if($rank->write_message)
					<h2>{{user_race_logo_small()}} new message (<span id="charcount" style="display:inline-block; margin-bottom:0px;">2000</span> Characters)</h2>
					<p>
						<script language="JavaScript">
							function CheckLen(Target)
							{
								var maxlength = 2000;
								var StrLen = Target.value.length;
								var CharsLeft;
								if (StrLen == 1 && Target.value.substring(0, 1) == " ")
								{
									Target.value = "";
									StrLen = 0;
								}
								if (StrLen > maxlength)
								{
									Target.value = Target.value.substring(0, maxlength);
									CharsLeft = 0;
								}
								else
								{
									CharsLeft = maxlength - StrLen;
								}
								document.getElementById('charcount').innerHTML = CharsLeft;
							}
						</script>
					</p>
					<table width="100%">
						<tbody><tr>
							<th colspan="2">
								<form action="{{url('/clan/newmessage')}}" method="POST">
									{{csrf_field()}}
									<textarea name="message" rows="6" cols="60" onkeydown="CheckLen(this)" onkeyup="CheckLen(this)" onfocus="CheckLen(this)" onchange="CheckLen(this)"></textarea>
									<br class="clearfloat">

									<div class="btn-left center">
										<div class="btn-right">
											<input class="btn" type="submit" value="insert">
										</div>
									</div>
								</form>
							</th>
						</tr>
						</tbody></table>
					<p></p>
					@endif

					<h2>{{user_race_logo_small()}} {{user()->getClanRank() == 1 ? 'Disband' : 'Leave'}} clan</h2>
					<div class="btn-left center">
						<div class="btn-right">
							<a href="{{url('/clan/leave')}}" class="btn">{{user()->getClanRank() == 1 ? 'Disband' : 'Leave'}} clan</a>
						</div>
					</div>
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
	@endif
@endsection