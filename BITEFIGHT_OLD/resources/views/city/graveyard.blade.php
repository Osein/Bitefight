@extends('index')

@section('content')
	@if($working)
	<script type="text/javascript">
		$('#header h1').text('Hint');
	</script>
	<div id="graveyard">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Hint</h2>
				<h3>Graveyard</h3>
				<p>Your work is not done yet. When your shift is over, you will get your wage. Your shift will be over in</p>
				<p class="counter">Hours of work remaining: <span id="graveyardCount" ></span>
					<script type="text/javascript">
						function twoDigits(value) {
							return (value < 10 ? '0' : '') + value;
						}

						$(function () {
							$("#graveyardCount").countdown({
								until: +{{$end_time - time()}},
								compact: true,
								compactLabels: ['y', 'm', 'w', 'd'],
								description: '',
								onExpiry: function() {
									setTimeout('window.location = "{{url('/city/graveyard')}}"',3000);
								},
								onTick: function(periods) {
									var days = '';
									if (periods[3] > 0) {
										days = periods[3]+'d ';
									}
									document.title =  days+periods[4] + ':' + twoDigits(periods[5]) + ':' + twoDigits(periods[6])+' BiteFight';
								}
							});
						});
					</script>
					&nbsp;minutes</p>
				<form action="{{url('/city/graveyard/cancel')}}"  method="POST">
					{{csrf_field()}}
					<div class="btn-left center">
						<div class="btn-right">
							<input type="submit" class="btn" name="abort" value="Cancel">
						</div>
					</div>
				</form>
				<br class="clearfloat" />
				<br class="clearfloat" />
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
	@else
	<div id="graveyard">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Graveyard</h2>
				<div class="buildingDesc clearfix">
					<img class="npc-logo" src="{{asset('img/city/npc/0_2.jpg')}}" align="left">

					<h3> A feeling of familiarity overcomes you at the graveyard. Welcome {{user()->getName()}}</h3>

					<p>Not enough gold? At the graveyard you can work as a graveyard gardener for {{prettyNumber(getLevel(user()->getExp()) * 50)}} {{gold_image_tag()}} + {{prettyNumber($bonus_gold)}} {{gold_image_tag()}} per 15 minutes. If you're particularly strong, you can earn a coin or two extra.</p>

					<form method="POST">
						{{csrf_field()}}
						<p>You work as {{$work_rank}}</p>
						<table cellpadding="2" cellspacing="2" border="0">
							<tbody>
							<tr>
								<td class="no-bg">Work time</td>
								<td class="no-bg">
									<select name="workDuration" size="1" class="input">
										<option value="1">0:15:00 h </option>
										<option value="2">0:30:00 h </option>
										<option value="3">0:45:00 h </option>
										<option value="4">1:00:00 h </option>
										<option value="5">1:15:00 h </option>
										<option value="6">1:30:00 h </option>
										<option value="7">1:45:00 h </option>
										<option value="8">2:00:00 h </option>
									</select>
								</td>
								<td class="no-bg">
									<div class="btn-left left">
										<div class="btn-right">
											<input type="submit" class="btn" name="dowork" value="Go!">
										</div>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</form>
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