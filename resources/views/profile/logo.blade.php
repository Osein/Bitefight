@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right"><a href="{{url('/profile/index')}}"  class="btn" >{{__('general.back')}}</a></div>
	</div>
	<br class="clearfloat"/>
	<div id="editUserpic">
		<div class="wrap-top-left">
			<div class="wrap-top-right">
				<div class="wrap-top-middle"></div>
			</div>
		</div>
		<div class="wrap-left">
			<div class="wrap-content wrap-right">
				<h2>{{user_race_logo_small()}}{{__('user.profile_change_logo')}}</h2>
				<div class="table-wrap">
					<table width="100%" border="0">
						<tr>
							<th align="center">
								<div>
									<div class="wrap-top-left">
										<div class="wrap-top-right">
											<div class="wrap-top-middle"></div>
										</div>
									</div>
									<div class="wrap-left">
										<div class="wrap-content wrap-right">
											<img id="logo" src="{{asset('img/logo/'.user()->getRace().'/'.user()->getGender().'/'.user()->getImageType().'.jpg')}}" alt="playerlogo">
										</div>
									</div>
									<div class="wrap-bottom-left">
										<div class="wrap-bottom-right">
											<div class="wrap-bottom-middle"></div>
										</div>
									</div>
								</div>
							</th>
							<td align="center" style="background:none; padding:0; vertical-align:top;">
								<script type="text/javascript">
									$(function ()
									{
										$("#logoForm").change(function()
										{
											var gender = $("input[name='gender']:checked").val();
											var type = $("input[name='type']:checked").val();

											$("#logo").attr('src', "{{asset('img/logo/'.user()->getRace())}}/"+gender+"/"+type+".jpg");
										});
									});
								</script>
								<form id="logoForm" method="POST">
									{{csrf_field()}}
									<table width="100%" border="0">
										<tr>
											<td class="radio" align="left">
												<strong>{{__('user.profile_gender')}}</strong><br />
												<input type="radio" name="gender" value="1" {{user()->getGender() == 1 ? 'checked' : ''}} /><label>{{__('user.profile_gender_female')}}</label>
												<input type="radio" name="gender" value="2" {{user()->getGender() == 2 ? 'checked' : ''}} /><label>{{__('user.profile_gender_male')}}</label>
											</td>
										</tr>
										<tr>
											<td class="radio" align="left">
												<strong>{{__('user.profile_type')}}</strong><br />
												<input type="radio" name="type" value="1" {{user()->getImageType() == 1 ? 'checked' : ''}} /><label>1</label>
												<input type="radio" name="type" value="2" {{user()->getImageType() == 2 ? 'checked' : ''}} /><label>2</label>
												<input type="radio" name="type" value="3" {{user()->getImageType() == 3 ? 'checked' : ''}} /><label>3</label>
												<input type="radio" name="type" value="4" {{user()->getImageType() == 4 ? 'checked' : ''}} /><label>4</label>
												<input type="radio" name="type" value="5" {{user()->getImageType() == 5 ? 'checked' : ''}} /><label>5</label>
												<input type="radio" name="type" value="6" {{user()->getImageType() == 6 ? 'checked' : ''}} /><label>6</label>
												<input type="radio" name="type" value="7" {{user()->getImageType() == 7 ? 'checked' : ''}} /><label>7</label>
												<input type="radio" name="type" value="8" {{user()->getImageType() == 8 ? 'checked' : ''}} /><label>8</label>
												<input type="radio" name="type" value="9" {{user()->getImageType() == 9 ? 'checked' : ''}} /><label>9</label>
												<input type="radio" name="type" value="10" {{user()->getImageType() == 10 ? 'checked' : ''}} /><label>10</label>
											</td>
										</tr>
										<tr>
											<td align="left" style="width:500px;">
												<div class="btn-left">
													<div class="btn-right">
														<input class="btn" type="submit" name="submit" value="{{__('general.save')}}">
													</div>
												</div>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
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