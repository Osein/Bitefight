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
				<h2>{{user_race_logo_small()}}Send an application to a clan gasgas</h2>
				<script language="JavaScript">
					$(document).ready(function() {
						var applicationText = document.getElementsByName('applicationText')[0];
						CheckLen(applicationText);
					});

					function CheckLen(Target) {
						var maxlength = "2000";
						StrLen=Target.value.replace(/\r\n?/g, "\n").length;
						if (StrLen==1&&Target.value.substring(0,1)==" ") {
							Target.value="";
							StrLen=0;
						}
						if (StrLen>maxlength ) {
							Target.value=Target.value.substring(0,maxlength);
							CharsLeft=0;
						} else {
							CharsLeft=maxlength-StrLen;
						}
						document.getElementById('charcount1').innerHTML=CharsLeft;
					}
				</script>
				<form method="POST">
					{{csrf_field()}}
					<table width="100%">
						<tbody>
						@if(isset($applied))
						<tr><td>Your application is going to be handed in now</td></tr>
						@else
						@if($clan)
						<tr>
							<td class="no-bg">
								<div class="error">Warning:<p>You have already sent in an application to the following clan: {{$clan->name}} [{{$clan->tag}}]</p></div>
							</td>
						</tr>
						@else
						<tr>
							<th>Text<br> ( <span id="charcount1">2000</span> Characters )</th>
							<th><textarea onkeydown="CheckLen(this)" onkeyup="CheckLen(this)" onfocus="CheckLen(this)" onchange="CheckLen(this)" name="applicationText" cols="70" rows="5">Entire booty: {{prettyNumber(user()->getSBooty())}} Blood</textarea></th>
						</tr>
						<tr>
							<td class="no-bg" colspan="2">
								<div class="btn-left left"><div class="btn-right">
										<input type="submit" class="btn" value="send">
									</div>
								</div>
							</td>
						</tr>
						@endif
						@endif
						</tbody>
					</table>
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