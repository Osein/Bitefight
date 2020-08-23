@extends('index')

@section('content')
	<div class="btn-left left"><div class="btn-right"><a href="{{url('/message/index')}}" class="btn">back</a></div></div>
	<br class="clearfloat">
	<div id="blockList">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}blocked players</h2>
				<form method="POST">
					{{csrf_field()}}
					<div class="table-wrap">
						<table cellpadding="2" cellspacing="2" border="0" width="100%">
							<tbody>
							<tr>
								<td class="no-bg">
									<center>
										<select name="list" size="10" style="width:300px;text-align:center;">
											@foreach($blocked_users as $user)
												<option value="{{$user->id}}">{{$user->name}}</option>
											@endforeach
										</select>
									</center>
								</td>
							</tr>
							<tr>
								<td class="no-bg" align="center">
									<div class="btn-left" style="width:150px; margin:0 auto;">
										<div class="btn-right">
											<input class="btn" type="submit" name="action" style="width:137px;" value="delete">
										</div>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</form>
				<br>
				<form method="POST">
					{{csrf_field()}}
					<div class="table-wrap">
						<table cellpadding="2" cellspacing="2" border="0" width="100%">
							<tbody>
							<tr>
								<td class="tdn" align="center">add player:
									<input class="input" type="text" name="name" size="15" maxlength="15">
								</td>
								<td>
									<div class="btn-left left">
										<div class="btn-right">
											<input class="btn" type="submit" value="add" name="action">
										</div>
									</div>
								</td>
							</tr>
							@if(session()->has('message_block_info'))
								<tr>
									<td colspan="2" class="tdn" align="center">
										<span style="color:yellow;">{{session()->get('message_block_info')}}</span>
									</td>
								</tr>
							@endif
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