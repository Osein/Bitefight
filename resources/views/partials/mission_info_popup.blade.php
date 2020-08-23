<script type="text/javascript">
	var blackoutDialogDivId;

	function blackoutDialog(switchOn, dialogDivId, msieAbsPosY, abortFunction, canCancel)
	{
		if (dialogDivId != undefined)
		{
			var dialogDivObj = document.getElementById(dialogDivId);
			if (!dialogDivObj.parentNode.tagName.match(/^body$/i))
			{
				dialogDivObj.parentNode.removeChild(dialogDivObj);
				document.body.appendChild(dialogDivObj);
			}
		}

		if (switchOn==true && document.getElementById('breakDiv'))
			blackoutDialog(false, blackoutDialogDivId, msieAbsPosY);

		if (switchOn==true)
		{
			blackoutDialogDivId=dialogDivId;
			var div = document.createElement('div');
			div.id = 'breakDiv';

			if (canCancel != undefined && canCancel == false)
			{
				div.className = 'blackoutdialog_break_div blackoutdialog_break_div2';
			}
			else
			{
				div.onmousedown = function()
				{
					blackoutDialog(false);
					if (abortFunction)
					{
						eval(abortFunction);
					}
				};
				div.className = 'blackoutdialog_break_div';
			}

			if (navigator.userAgent.indexOf('MSIE')>=0)
			{
				var width=document.documentElement.clientWidth;
				var height=document.documentElement.clientHeight;
			}
			else
			{
				var width=window.innerWidth;
				var height=window.innerHeight;
			}
			div.style.zIndex=500;
			div.style.overflow='hidden';
			if (navigator.userAgent.indexOf('MSIE 6.0')>=0)
			{
				document.body.style.width='100%';
				document.body.style.height='100%';
				div.style.width=document.body.offsetWidth+'px';
				div.style.height=document.body.offsetHeight+'px';
			}
			else
			{
				div.style.position='fixed';
				div.style.width='100%';
				div.style.height='100%';
			}
			document.body.appendChild(div);
			document.getElementById(dialogDivId).style.display='block';

			// Fuer IE6-7
			if ((navigator.userAgent.indexOf("MSIE") >= 0) && (navigator.userAgent.indexOf("MSIE 8") < 0))
			{
				var windowHeight = document.documentElement.clientHeight;
				var popupHeight = document.getElementById(dialogDivId).offsetHeight;
				var scrolled = document.documentElement.scrollTop;
				var top = Math.floor((windowHeight / 2) - (popupHeight / 2)) + scrolled;
			}
			else
				var top=Math.floor(height / 2)-Math.floor(document.getElementById(dialogDivId).offsetHeight/2);

			var left=Math.floor((width-document.getElementById(dialogDivId).offsetWidth)/2);
			if($(document.body).css('direction') == 'rtl')
				document.getElementById(dialogDivId).style.right=left+'px';
			else
				document.getElementById(dialogDivId).style.left=left+'px';
			document.getElementById(dialogDivId).style.top=top+'px';
			document.getElementById(dialogDivId).style.zIndex=501;
		}
		else
		{
			if (document.getElementById('breakDiv'))
				document.body.removeChild(document.getElementById('breakDiv'));
			document.getElementById(blackoutDialogDivId).style.display='none';
			document.getElementById(blackoutDialogDivId).style.zIndex = -1;
		}
	}

	$(document).ready(function() {blackoutDialog(true, 'infoPopup');});

</script>
<div id="infoPopup" class="message_screen blackoutdialog">
	<div class="wrap-top-left clearfix">
		<div class="wrap-top-right clearfix">
			<div class="wrap-top-middle clearfix"></div>
		</div>
	</div>
	<div class="wrap-left clearfix">
		<div class="wrap-content wrap-right clearfix">
			<h2>{{user_race_logo_small()}} Mission accomplished</h2>
			<div class="blackoutdialog_content">
				<div class="blackoutdialog_float">
					<p style="margin-left: 10px; margin-right: 10px;">You have completed your mission(s).<br><br>
						<strong>
							@foreach($missions as $mission)
								@if($mission->type == \Database\Models\UserMissions::TYPE_HUMAN_HUNT)
									Successful man hunts
								@endif
                                ({{$mission->count}} / {{$mission->count}})<br>
							@endforeach
						</strong>
					</p>
				</div>
				<div class="clearfloat"></div>
				<div style="width: 100%; text-align: center;">
					<div class="button left" style="width: 205px;">
						<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>
						<div class="btn-left button_float" style="margin: 0;"></div>
						<button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="document.location.href='{{url('city/missions')}}'">To the tavern</button>
						<div class="btn-right button_float"></div>
						<div class="clearfloat"></div>
					</div>
					<div class="button right" style="width: 205px;">
						<div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>
						<div class="btn-left button_float" style="margin: 0;"></div>
						<button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="blackoutDialog(false, 'infoPopup')">Close</button>
						<div class="btn-right button_float"></div>
						<div class="clearfloat"></div>
					</div>
					<div class="clearfloat"></div>
				</div>
				<div class="clearfloat"></div>
			</div>
		</div>
	</div>
	<div class="wrap-bottom-left">
		<div class="wrap-bottom-right">
			<div class="wrap-bottom-middle"></div>
		</div>
	</div>
</div>