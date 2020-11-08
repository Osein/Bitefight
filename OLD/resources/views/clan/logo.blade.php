@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="clanOverview">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}edit clan symbol</h2>
				<div class="table-wrap">
					<table width="100%">
						<tbody><tr><th>&nbsp;</th></tr>
						<tr>
							<th align="center"><img src="{{asset('img/clan/'.$clan->logo_bg.'-'.$clan->logo_sym.'.png')}}" border="0"></th>
						</tr>
						<tr>
							<th>&nbsp;</th>
						</tr>
						<tr>
							<th>
								<table width="100%">
									<tbody><tr>
										<th align="center">
											<a href="{{url('/clan/logo/background')}}">Background</a>&nbsp;
											<a href="{{url('/clan/logo/symbol')}}">Symbol</a>
										</th>
									</tr>
									<tr>
										<th align="center">
											@if($type == 'symbol')
											<table cellpadding="2" cellspacing="2" width="100%">
												<tbody>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="10">
															<input type="image" src="{{asset('img/clan/2_10.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="5">
															<input type="image" src="{{asset('img/clan/2_5.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="12">
															<input type="image" src="{{asset('img/clan/2_12.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="13">
															<input type="image" src="{{asset('img/clan/2_13.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="19">
															<input type="image" src="{{asset('img/clan/2_19.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="4">
															<input type="image" src="{{asset('img/clan/2_4.png')}}" width="60" height="60">

														</form>
													</td>
												</tr>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="17">
															<input type="image" src="{{asset('img/clan/2_17.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="21">
															<input type="image" src="{{asset('img/clan/2_21.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="16">
															<input type="image" src="{{asset('img/clan/2_16.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="7">
															<input type="image" src="{{asset('img/clan/2_7.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="11">
															<input type="image" src="{{asset('img/clan/2_11.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="22">
															<input type="image" src="{{asset('img/clan/2_22.png')}}" width="60" height="60">

														</form>
													</td>
												</tr>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="2">
															<input type="image" src="{{asset('img/clan/2_2.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="18">
															<input type="image" src="{{asset('img/clan/2_18.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="6">
															<input type="image" src="{{asset('img/clan/2_6.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="15">
															<input type="image" src="{{asset('img/clan/2_15.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="20">
															<input type="image" src="{{asset('img/clan/2_20.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="8">
															<input type="image" src="{{asset('img/clan/2_8.png')}}" width="60" height="60">

														</form>
													</td>
												</tr>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="3">
															<input type="image" src="{{asset('img/clan/2_3.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="14">
															<input type="image" src="{{asset('img/clan/2_14.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="24">
															<input type="image" src="{{asset('img/clan/2_24.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="23">
															<input type="image" src="{{asset('img/clan/2_23.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="1">
															<input type="image" src="{{asset('img/clan/2_1.png')}}" width="60" height="60">

														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="symbol" value="9">
															<input type="image" src="{{asset('img/clan/2_9.png')}}" width="60" height="60">

														</form>
													</td>
												</tr>
												</tbody>
											</table>
											@else
											<table cellpadding="2" cellspacing="2" width="100%">
												<tbody>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="7">
															<input type="image" src="{{asset('img/clan/1_7.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="1">
															<input type="image" src="{{asset('img/clan/1_1.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="3">
															<input type="image" src="{{asset('img/clan/1_3.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="5">
															<input type="image" src="{{asset('img/clan/1_5.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="10">
															<input type="image" src="{{asset('img/clan/1_10.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="2">
															<input type="image" src="{{asset('img/clan/1_2.png')}}" width="60" height="60">
														</form>
													</td>
												</tr>
												<tr>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="9">
															<input type="image" src="{{asset('img/clan/1_9.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="4">
															<input type="image" src="{{asset('img/clan/1_4.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="8">
															<input type="image" src="{{asset('img/clan/1_8.png')}}" width="60" height="60">
														</form>
													</td>
													<td class="no-bg">
														<form method="POST">
															{{csrf_field()}}
															<input type="hidden" name="bg" value="6">
															<input type="image" src="{{asset('img/clan/1_6.png')}}" width="60" height="60">
														</form>
													</td>
												</tr>
												</tbody>
											</table>
											@endif
										</th>
									</tr>
									</tbody>
								</table>
							</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
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