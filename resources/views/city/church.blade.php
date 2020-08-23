@extends('index')

@section('content')
	<div id="church">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Church</h2>
				<div class="buildingDesc">
					<img class="npc-logo" src="{{asset('img/city/npc/0_9.jpg')}}" align="left">
					<h3>Enter and let go of your sins {{user()->getName()}}</h3>
					<p>In this Holy house of penance and prayer, every dark figure is welcome. The Lord loves every one of his creatures. You can find comfort for your wounds but also for your oppressed soul here.</p>
					@if(session()->has('churchUsed'))
						<p style="text-align:justify"><font color="#ed9100">{{__('city.city_church_healing_success')}}</font></p>
					@endif
					<p></p>
				</div>
				<br class="clearfloat">
				<h2>{{user_race_logo_small()}}What would you like?</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="2" border="0" width="100%">
						<tbody>
						<tr>
							<td align="left">
								<p><b>Blessing and healing of wounds</b><br>
									Heals 100% of maximum health for {{$requiredAp}} AP                </p>
							</td>
							<td align="center" valign="middle">
								@if(user()->getApNow() >= $requiredAp && user()->getHpNow() < user()->getHpMax())
									<form method="POST">
										{{csrf_field()}}
										<div class="btn-left left">
											<div class="btn-right">
												<input type="submit" name="heal" class="btn" value="Heal">
											</div>
										</div>
									</form>
								@else
									<div class="btn-left left">
										<div class="btn-right">
											<input type="submit" name="heal" class="btndisable" value="Heal">
										</div>
									</div>
								@endif
							</td>

						</tr>
						@if($delta > 0)
						<tr>
							<td colspan="2">
								Cooldown time until the healing cost has been reduced back to the initial cost:&nbsp;
								<span id="church_healing_countdown"></span>
								<script type="text/javascript">
									function twoDigits(value) {
										return (value < 10 ? '0' : '') + value;
									}

									$(function () {
										$("#church_healing_countdown").countdown({
											until: +{{$delta}},
											compact: true,
											compactLabels: ['y', 'm', 'w', 'd'],
											description: '',
											onExpiry: function() {
												setTimeout('window.location = "/city/church"',3000);
											},
											onTick: function(periods) {
												var days = '';
												if (periods[3] > 0) {
													days = periods[3]+'d ';
												}
												document.title =  days+periods[4] + ':' + twoDigits(periods[5]) + ':' + twoDigits(periods[6])+' BiteFight';
											}});
									});
								</script>
							</td>
						</tr>
						@endif
						<tr>
							<td colspan="2">When you have prayed, you have to wait 01:00:00 before you can pray again. If you would like to pray for healing again during this time, it will cost you double the actions points and it will increase the cooldown time by another 01:00:00. During this increased cooldown time, the AP cost will be doubled again. The cost will only return to normal once the entire cooldown time has expired.</td>
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
@endsection