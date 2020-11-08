@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}">back</a>
		</div>
	</div>
	<br class="clearfloat">
	<div id="clanMail">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}send a message to your clan</h2>
				<div class="table-wrap">
					<script language="JavaScript">
						function CheckLen(Target)
						{
							var maxlength = "2000";
							StrLen=Target.value.replace(/\r\n?/g, "\n").length;
							if (StrLen==1&&Target.value.substring(0,1)==" ")
							{
								Target.value="";
								StrLen=0;
							}
							if (StrLen>maxlength )
							{
								Target.value=Target.value.substring(0,maxlength);
								CharsLeft=0;
							}else
							{
								CharsLeft=maxlength-StrLen;
							}
							document.getElementById('charcount').innerHTML=CharsLeft;
						}
					</script>
					<form method="POST">
						{{csrf_field()}}
						<div class="table-wrap">
							<h3>Message these members...</h3>
							@if(isset($users))
							<table width="100%">
								<tbody>
								@foreach($users as $suser)
								<tr>
									<td class="tdn" colspan="2" align="center">{{$suser->name}}: message sent</td>
								</tr>
								@endforeach
								</tbody>
							</table>
							@else
							<table width="100%">
								<tbody>
								<tr>
									<td>&nbsp;</td>
									<td>Name</td>
									<td>Rank</td>
									<td>Entire booty</td>
								</tr>
								@foreach($mail_users as $muser)
								<tr>
									<td><input type="checkbox" name="receiver[]" value="{{$muser->id}}" checked=""></td>
									<td><a href="{{url('profile/player/'.$muser->id)}}">{{$muser->name}}</a>
									</td><td>{{$muser->rank_name}}</td>
									<td>{{prettyNumber($muser->total_booty)}}</td>
								</tr>
								@endforeach
								<tr>
									<td>Text (<span id="charcount" style="display:inline-block; margin-bottom:0px;">2000</span> Characters)</td>
									<td colspan="4"><textarea name="text" cols="70" rows="5" onkeydown="CheckLen(this)" onkeyup="CheckLen(this)" onfocus="CheckLen(this)" onchange="CheckLen(this)"></textarea></td>
								</tr>
								<tr>
									<td colspan="5">
										<div class="btn-left left"><div class="btn-right">
												<input class="btn" type="submit" value="send">
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="5">Here you can send circular messages to multiple addresses.</td>
								</tr>
								</tbody>
							</table>
							@endif
						</div>
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
@endsection