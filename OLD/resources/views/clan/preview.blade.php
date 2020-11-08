@extends('index')

@section('content')
	<div id="clanExtern">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>
					{{user_race_logo_small()}}Clan [{{$clan->tag}}]
				</h2>
				<div class="table-wrap">
					<table width="100%">
						<tbody>
						<tr>
							<td class="clan-logo no-bg" rowspan="10">
								<img src="{{asset('img/clan/'.$clan->logo_bg.'-'.$clan->logo_sym.'.png')}}" border="0">
							</td>
						</tr>
						<tr>
							<td>Name</td>
							<td>{{$clan->name}}</td>
						</tr>
						<tr>
							<td>Homepage</td>
							<td>
								@if(strlen($clan->website))
								<a href="{{url('/clan/view/homepage/'.$clan->id.'?_token='.csrf_token())}}" target="_blank">{{$clan->website}}</a>	({{$clan->website_counter}} Visitors)
								@else
								no clan page available
								@endif
							</td>
						</tr>
						<tr>
							<td>Total booty</td>
							<td>{{prettyNumber($clan->total_booty)}} Meat</td>
						</tr>
						<tr>
							<td>Members</td>
							<td>
								<a href="{{'/clan/memberlistExt/'.$clan->id}}">{{$clan->member_count}} / {{$clan->max_members}} Member</a>
							</td>
						</tr>
						<tr>
							<td>Capital</td>
							<td>{{prettyNumber($clan->gold_count)}} {{gold_image_tag()}}</td>
						</tr>
						@if(user() && user()->getClanId() < 1 && $clan->member_count < $clan->max_members)
						<tr>
							<td class="no-bg" colspan="2">
								<div class="btn-left left"><div class="btn-right">
										<a class="btn" href="{{'/clan/apply/'.$clan->id}}">apply to a clan</a>
									</div>
								</div>
							</td>
						</tr>
						@endif
						</tbody>
					</table>
					<h2>{{user_race_logo_small()}}View of the clan hideout</h2>
					<p>
					</p>
					<table cellpadding="0" cellspacing="0" border="0" width="584" align="center">
						<tbody><tr>
							<td style="background: url({{asset('img/clan/hideout/bg1.jpg')}}) no-repeat center;" align="center">
								<img src="{{asset('img/clan/hideout/1_'.$clan->stufe.'.jpg')}}">
							</td>
						</tr>
						</tbody>
					</table>
					<br>
					<h2>
						{{--Todo: report link--}}
						{{user_race_logo_small()}}Clan description <!--<a href="https://s202-en.bitefight.gameforge.com:443/msg/complain/cprofile?id=9305&amp;cc=db627f2" class="copyright">(report)</a>-->
					</h2>
					<p style="text-align:center">
						{!! $clan->descriptionHtml !!}
					</p>
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