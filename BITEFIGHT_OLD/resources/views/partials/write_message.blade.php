<!--suppress ALL -->
<script type="text/javascript">
	var showNewMessage = false;
	var writeDivId = '#newMessageScreen';
	var newMessageContent = '#newMessageContent';
	var blackDiv = '#blackDiv';
	var proposalReceiver = [];

	function writeMessageSplash()
	{
		if (showNewMessage) {
			hideNewMessageSplash();
			return;
		} else {
			showBlackDiv();
			showNewMessage = true;
		}
		var doc_width  = window.innerWidth;
		$(writeDivId).css("display", "block");
		var off = $(writeDivId).offsetWidth;
		var left = Math.floor((doc_width / 2) - (off / 2));
		var top = 100;
		var width = 600;
		$(writeDivId).css("left", left+"px");
		$(writeDivId).css("top", top+"px");
		$(writeDivId).css("width", width+"px");
		$(writeDivId).css("zIndex", "501");

		var content = '';
		content += '<div id="write_error" style="display:none;"></div>';
		content += '<table id="write_form" width="100%" border="0" cellpadding="0" cellspacing="0">';
		content += '<tr>';
		content += '<td><b>Sender</b>:</td>';
		content += '<td>{{user()->getName()}}</td>';
		content += '</tr>';
		content += '<tr>';
		content += '<td><b>Recipient</b>:</td>';
		content += '<td style="padding:1px 5px;">';
		content += '<input id="receivername" style="margin:0;" type="text" name="receiver" size="30" maxlength="30" class="input" value="{{isset($receiverName) ? $receiverName : ''}}" autocomplete="off">';
		content += '</td>';
		content += '</tr>';
		content += '<tr>';
		content += '<td style="padding:1px 5px;"><b>Subject</b>:</td>';
		content += '<td style="padding:1px 5px;"><input id="subject" style="margin:0;" type="text" name="subject" size="30" maxlength="30" class="input" value=""></td>';
		content += '</tr>';
		content += '<tr>';
		content += '<td><b>message</b><br>(<span id="charcount1" style="display:inline-block; margin-bottom:0;">2000</span> Characters)</td>';
		content += '<td><textarea id="message" class="no-bg fakeMsgBorder" style="margin:0;" name="text" rows="9" cols="55" onkeydown="CheckLen2(this)" onkeyup="CheckLen2(this)" onfocus="CheckLen2(this)" onchange="CheckLen2(this)"></textarea></td>';
		content += '</tr>';
		content += '</table>';
		content += '<table id="write_form2" width="100%" border="0" cellpadding="0" cellspacing="0">';
		content += '<tr>';
		content += '<td width="100%" nowrap><center><a href="#" onClick="sendMessage()">( send )</a></center></td>';
		content += '<td nowrap><a href="#" onClick="hideNewMessageSplash()">( Close )</a></td>';
		content += '</tr>';
		content += '</table>';
		content += '<center>';
		content += '<table id="write_form3" width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none;">';
		content += '<tr>';
		content += '<td><center><a href="#" onClick="hideNewMessageSplash()">( Close )</a></center></td>';
		content += '</tr>';
		content += '</table>';
		content += '</center>';
		content += '<div id="senderid" style="display:none;">{{user()->getId()}}</div>';
		content += '<div id="receiverid" style="display:none;"></div>';

		$(newMessageContent).html(content);

		$("#receivername").keyup(function ()
		{
			ajaxCheckReceiver();
		});

		function ajaxCheckReceiver()
		{
			$.getJSON('{{url('/message/ajax/checkreceiver')}}',
				'name='+encodeURIComponent($("#receivername").val())+
				'&_token={{csrf_token()}}',
				function(data){
					if (data.list.length > 0)
					{
						proposalReceiver = [];
						$.each(data.list, function(i, proposal){
							proposalReceiver[i] = proposal;
						});

						$( "#receivername" ).autocomplete({
							source: data.list,
							classes: {
								"ui-autocomplete": "write-message-autocomplete"
							}
						});
					}
					else
					{
						proposalReceiver = [];
					}
				})
		}
	}

	function sendMessage()
	{
		$.getJSON('{{url('/message/ajax/writemessage')}}',
			'receivername='+encodeURIComponent($('#receivername').val())+
			'&subject='+encodeURIComponent($('#subject').val())+
			'&message='+encodeURIComponent($('#message').val())+
			'&_token={{csrf_token()}}',
			function(data)
			{
				if (data.errorstatus === 0)
				{
					msgSendSuccess(data.error);
				}
				else
				{
					msgSendFail(data.error);
				}
			})
	}

	function msgSendSuccess(message)
	{
		$('#write_error').css('display', 'block').html('<p class="info">'+message+'</p>');
		$('#write_form').css('display', 'none');
		$('#write_form2').css('display', 'none');
		$('#write_form3').css('display', 'block');
	}

	function msgSendFail(error)
	{
		$('#write_error').css('display', 'block').html('<p class="info">'+error+'</p>');
	}

	function CheckLen2(Target)
	{
		var maxlength = 2000;
		var StrLen=Target.value.replace(/\r\n?/g, "\n").length;
		var CharsLeft;
		if (StrLen === 1 && Target.value.substring(0,1) === " ")
		{
			Target.value="";
			StrLen=0;
		}
		if (StrLen>maxlength )
		{
			Target.value=Target.value.substring(0,maxlength);
			CharsLeft=0;
		}
		else
		{
			CharsLeft=maxlength-StrLen;
		}
		document.getElementById('charcount1').innerHTML=CharsLeft;
	}

	function showBlackDiv()
	{
		var div=document.createElement('div');
		div.id = 'blackDiv';
		div.className = 'break_div';
		div.style.zIndex = 500;
		div.style.overflow = 'hidden';
		div.style.position='fixed';
		div.style.width='100%';
		div.style.height='100%';
		document.body.appendChild(div);
	}

	function hideNewMessageSplash()
	{
		$(blackDiv).remove();
		$(writeDivId).css('display', 'none');
		$(writeDivId).css('zIndex', '-1');
		$(newMessageContent).html('');
		showNewMessage = false;
	}

</script>

<div id="newMessageScreen" class="message_screen">
	<div id="messageWriter">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<div id="newMessageContent" class="message_content"></div>
			</div>
		</div>
		<div class="wrap-bottom-left">
			<div class="wrap-bottom-right">
				<div class="wrap-bottom-middle"></div>
			</div>
		</div>
	</div>
</div>