@extends('index')

@section('content')
	<form method="POST">
		{{csrf_field()}}
		<div id="notes">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<h2>{{user_race_logo_small()}}{{__('general.notepad')}} (<span id="charcount" style="display:inline-block; margin-bottom:0;">{{strlen($user_note)}}</span> {{__('general.characters')}})</h2>
					<textarea name="note" rows="15" cols="84" style="text-align:left;"
							  onkeydown="CheckLen(this)"
							  onkeyup="CheckLen(this)"
							  onchange="CheckLen(this)"
					>{{$user_note}}</textarea>
					<script language="JavaScript">
						function CheckLen(Target) {
							var maxlength = "2000";
							var StrLen = Target.value.replace(/\r\n?/g, "\n").length;
							if (StrLen == 1 && Target.value.substring(0, 1) == " ") {Target.value = ""; StrLen = 0;}
							var CharsLeft = 2000;
							if (StrLen > maxlength) {
								Target.value = Target.value.substring(0, maxlength);
								CharsLeft = 0;
							} else {
								CharsLeft = maxlength - StrLen;
							}
							$('#charcount').text(CharsLeft);
						}
						var notes = document.getElementsByName('note')[0];
						CheckLen(notes);
					</script>
					<div class="btn-left center">
						<div class="btn-right">
							<input name="save" class="btn" type="submit" value="{{__('general.save')}}">
						</div>
					</div>
				</div>
			</div>
			<div class="wrap-bottom-left">
				<div class="wrap-bottom-right">
					<div class="wrap-bottom-middle"></div>
				</div>
			</div>
		</div>
	</form>
@endsection