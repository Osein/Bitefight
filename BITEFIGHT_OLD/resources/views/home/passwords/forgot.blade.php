@extends('index')

@section('content')
	<script type="text/javascript">
		$('#header').find('h1').text('{{__('general.lost_password')}}');
	</script>
	<div id="login">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{__('home.home_forgot_pass_header')}}</h2>
				<form action="{{ route('password.email') }}" method="POST">
					{{csrf_field()}}
					@if (session('status'))
						<div class="error">
							{{ session('status') }}
						</div>
					@endif
					@if ($errors->has('email') || $errors->has('name'))
						<span class="error">
							<strong>{{ $errors->has('email') ? $errors->first('email') : $errors->first('name') }}</strong>
						</span>
					@endif
					<div class="table-wrap">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tbody><tr>
								<td align="center" valign="top"><img src="{{asset('img/symbols/race1.gif')}}" alt="{{__('general.vampire')}}"></td>
								<td valign="top">
									<table cellpadding="2" cellspacing="2" border="0" width="100%">
										<tbody><tr>
											<td>{{__('general.name')}}</td>
											<td><input class="input" type="text" name="name" size="30" maxlength="30"></td>
										</tr>
										<tr>
											<td>{{__('general.e-mail')}}</td>
											<td><input class="input" type="text" name="email" size="30" maxlength="130"></td>
										</tr>
										<tr>
											<td></td>
											<td><input type="submit" class="btn-small" value="{{__('general.send')}}"></td>
										</tr>
										</tbody>
									</table>
								</td>
								<td align="center" valign="top"><img src="{{asset('img/symbols/race2.gif')}}" alt="{{__('general.werewolf')}}"></td>
							</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
@endsection