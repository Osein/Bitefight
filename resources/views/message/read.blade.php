@extends('index')

@section('content')
    <div class="btn-left left">
        <div class="btn-right">
            <a href="{{url('/message/index')}}" class="btn">back</a>    </div>
    </div>
    @include('partials.write_message')
    <div class="btn-left left">
        <div class="btn-right">
            <a href="#" class="btn" onclick="writeMessageSplash()">Write message</a>
        </div>
    </div>
    <br class="clearfloat">
    <script type="text/javascript">
        var showMessage = false;
        var dialogDivId = '#messageScreen';
        var messageContent = '#messageContent';
        var breakDiv = '#breakDiv';
        var content = '';

        function showAnswerMessageSplash(msgnr)
        {
            if (navigator.userAgent.indexOf('MSIE 6.0') >= 0)
            {
            }
            else
            {
                if (!$(breakDiv))
                {
                    showBreakDiv();
                }
            }
            $(dialogDivId).css('display', 'none');
            $(dialogDivId).css('zIndex', '-1');
            $(messageContent).html('');
            showMessage = false;
            content = '';
            showAnswerForm(msgnr);
        }

        function showAnswerForm(msgnr)
        {
            var doc_width = navigator.userAgent.indexOf('MSIE') >= 0 ? document.documentElement.clientWidth : window.innerWidth;
            $(dialogDivId).css("display", "block");

            var off = $(dialogDivId).offsetWidth;
            var left = Math.floor((doc_width / 2) - (off / 2));
            var top = 100;
            var width = 600;
            $(dialogDivId).css("left", left+"px");
            $(dialogDivId).css("top", top+"px");
            $(dialogDivId).css("width", width+"px");
            $(dialogDivId).css("zIndex", "501");

            content += '<div id="answer_error" style="display:none;"></div>';
            content += '<table id="answer_form" width="100%" border="0" cellpadding="0" cellspacing="0">';
            content += '<tr>';
            content += '<td>' + $('#title_sender').html() + ':</td>';
            content += '<td>{{user()->getName()}}</td>';
            content += '</tr>';
            content += '<tr>';
            content += '<td><b>Recipient</b>:</td>';
            content += '<td>' + $('#message_'+msgnr+'_sender').html() + '</td>';
            content += '</tr>';
            content += '<tr>';
            content += '<td style="padding:1px 5px;">' + $('#title_subject').html() + ':</td>';
            content += '<td style="padding:1px 5px;"><input id="subject" style="margin:0px;" type="text" name="subject" size="30" maxlength="30" class="input" value="Re:' + $('#message_'+msgnr+'_content_subject').html() + '"></td>';
            content += '</tr>';
            content += '<tr>';
            content += '<td><b>message</b><br>(<span id="charcount1" style="display:inline-block; margin-bottom:0px;">2000</span> Characters)</td>';
            content += '<td><textarea id="message" class="no-bg fakeMsgBorder" style="margin:0px;" name="text" rows="9" cols="55" onkeydown="CheckLen(this)" onkeyup="CheckLen(this)" onfocus="CheckLen(this)" onchange="CheckLen(this)"></textarea></td>';
            content += '</tr>';
            content += '</table>';
            content += '<table id="answer_form2" width="100%" border="0" cellpadding="0" cellspacing="0">';
            content += '<td nowrap width="100%"><center><a href="#" onClick="sendAnswer()">( send )</a></center></td>';
            content += '<td nowrap><center><a href="#" onClick="hideMessageSplash()">( Close )</a></center></td>';
            content += '</tr>';
            content += '</table>';
            content += '<center>';
            content += '<table id="answer_form3" width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none;">';
            content += '<tr>';
            content += '<td><center><a href="#" onClick="hideMessageSplash()">( Close )</a></center></td>';
            content += '</tr>';
            content += '</table>';
            content += '</center>';
            content += '<div id="msgnr" style="display:none;">'+msgnr+'</div>';
            content += '<div id="receiverid" style="display:none;">'+$('#message_'+msgnr+'_receiver_id').html()+'</div>';
            $(messageContent).html(content);
        }

        function sendAnswer()
        {
            $.getJSON('{{url('/message/ajax/sendanswer')}}',
                '&receiverid='+$('#receiverid').html()+
                '&subject='+encodeURIComponent($('#subject').val())+
                '&message='+encodeURIComponent($('#message').val())+
                '&msgnr='+$('#msgnr').html()+
                '&_token={{csrf_token()}}',
                function(data)
                {
                    if (data.errorstatus === 0)
                    {
                        showMsgSendSuccess($('#msgnr').html(), data.error, data.msgicon, data.msgmenu);
                    }
                    else
                    {
                        showMsgSendFail(data.error);
                    }
                })
        }

        function showMsgSendSuccess(msgnr, message, msgicon, menuicon)
        {
            $('#answer_error').css('display', 'block').html('<p class="info">'+message+'</p>');
            $('#answer_form').css('display', 'none');
            $('#answer_form2').css('display', 'none');
            $('#answer_form3').css('display', 'block');
            if (msgicon === 3)
            {
                $('#msg_status_'+msgnr).attr('src', '{{asset('img/symbols/mail_status3.png')}}');
            }
            $('#msgmenu').attr('class', menuicon);
        }

        function showMsgSendFail(error)
        {
            $('#answer_error').css('display', 'block').html('<p class="info">'+error+'</p>');
        }

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
            }
            else
            {
                CharsLeft=maxlength-StrLen;
            }
            document.getElementById('charcount1').innerHTML=CharsLeft;
        }

        function showMessageSplash(msgnr)
        {
            if (showMessage)
            {
                hideMessageSplash();
                return;
            }
            else
            {
                if (navigator.userAgent.indexOf('MSIE 6.0') >= 0)
                {
                }
                else
                {
                    showBreakDiv();
                }
                showMessage = true;
            }
            if ($('#message_'+msgnr+'_overlay_marks_as_read').length > 0) {
                ajaxMailRead(msgnr, false, true);
            }
            var doc_width = navigator.userAgent.indexOf('MSIE') >= 0 ? document.documentElement.clientWidth : window.innerWidth;
            $(dialogDivId).css("display", "block");

            var off = $(dialogDivId).offsetWidth;
            var left = Math.floor((doc_width / 2) - (off / 2));
            var top = 100;
            var width = 600;
            $(dialogDivId).css("left", left+"px");
            $(dialogDivId).css("top", top+"px");
            $(dialogDivId).css("width", width+"px");
            $(dialogDivId).css("zIndex", "501");

            content += '<div id="message_previous_next_container">';
            addPreviousAndNextLinksToContent(msgnr);
            content += '</div>';

            content += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
            content += '<tr>';
            if ($('#message_'+msgnr+'_receiver_id').length > 0 && $('#message_'+msgnr+'_announce').length > 0)
            {
                content += '<td width="100%">' + $('#title_date').html() + ': ' + $('#message_'+msgnr+'_time').html() + '</td>';
                content += '<td nowrap>' + $('#message_'+msgnr+'_announce').html() + '</td>';
            }
            else
            {
                content += '<td colspan="2">' + $('#title_date').html() + ': ' + $('#message_'+msgnr+'_time').html() + '</td>';
            }
            content += '</tr>';
            content += '</table>';
            content += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
            content += '<tr>';
            content += '<td colspan="2">' + $('#title_sender').html() + ': ' + $('#message_'+msgnr+'_sender').html() + '</td>';
            content += '</tr>';
            content += '<tr>';
            content += '<td colspan="2">' + $('#title_subject').html() + ': ' + $('#message_'+msgnr+'_content_subject').html() + '</td>';
            content += '</tr>';
            content += '<tr>';
            content += '<td colspan="2" class="no-bg fakeMsgBorder">' + $('#message_'+msgnr+'_content_text').html() + '</td>';
            content += '</tr>';
            content += '</table>';
            content += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
            content += '<tr>';
            if ($('#message_'+msgnr+'_receiver_id').length > 0 && $('#message_'+msgnr+'_answer').length > 0)
            {
                content += '<td nowrap><center>'+$('#message_'+msgnr+'_answer').html()+'</center></td>';
                content += '<td nowrap><center><a href="#" onClick="hideMessageSplash()">( Close )</a></center></td>';
            }
            else
            {
                content += '<td nowrap colspan="2"><center><a href="#" onClick="hideMessageSplash()">( Close )</a></center></td>';
            }
            content += '</tr>';
            content += '</table>';
            $(messageContent).html(content);
        }

        function addPreviousAndNextLinksToContent(msgnr) {
            if ($('#message_'+msgnr+'_previous_id').length > 0) {
                content += '<a href="#" onclick="hideMessageSplash();showMessageSplash(' + $('#message_'+msgnr+'_previous_id').html() + ')"><</a>';
            }
            else {
                content += '&nbsp;&nbsp;';
            }
            content += '&nbsp;&nbsp;&nbsp;';
            if ($('#message_'+msgnr+'_next_id').length > 0) {
                content += '<a href="#" onclick="hideMessageSplash();showMessageSplash(' + $('#message_'+msgnr+'_next_id').html() + ')">></a>';
            }
            else {
                content += '&nbsp;&nbsp;';
            }
        }

        function hideMessageSplash()
        {
            if (navigator.userAgent.indexOf('MSIE 6.0') >= 0)
            {
            }
            else
            {
                hideBreakDiv();
            }
            $(dialogDivId).css('display', 'none');
            $(dialogDivId).css('zIndex', '-1');
            $(messageContent).html('');
            showMessage = false;
            content = '';
        }

        function showBreakDiv()
        {
            var div=document.createElement('div');
            div.id = 'breakDiv';
            div.className = 'break_div';
            div.onmousedown = function() {hideMessageSplash();};
            div.style.zIndex = 500;
            div.style.overflow = 'hidden';
            if (navigator.userAgent.indexOf('MSIE 6.0') >= 0)
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
        }

        function hideBreakDiv()
        {
            if ($(breakDiv))
            {
                $(breakDiv).remove();
            }
        }

        function ajaxMailRead(msgnr, isSynchronous, deleteMarkReadDiv)
        {
            isSynchronous = typeof(isSynchronous) !== 'undefined' ? isSynchronous : false;
            deleteMarkReadDiv = typeof(deleteMarkReadDiv) !== 'undefined' ? deleteMarkReadDiv : false;

            $.ajax({
                type: 'GET',
                url: '{{url('/message/ajax/readmessage')}}',
                dataType: 'json',
                success: function(data)
                {
                    if (data.msgicon === 2)
                    {
                        $('#msg_status_'+msgnr).attr('src', '{{asset('img/symbols/mail_status2.png')}}');
                    }

                    $('#msgmenu').prop('class', data.msgmenu);

                    if (deleteMarkReadDiv) {
                        $('#message_'+msgnr+'_overlay_marks_as_read').remove();
                    }
                },
                data: {
                    _token: "{{csrf_token()}}",
                    msgnr: msgnr
                },
                async: !isSynchronous
            });
        }
    </script>
    <div id="inbox">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{user_race_logo_small()}}Folder {{$folder->getFolderName()}} ({{$msg_count}})</h2>

                <div id="messageScreen" class="message_screen">
                    <div id="messageReader">
                        <div class="wrap-top-left clearfix">
                            <div class="wrap-top-right clearfix">
                                <div class="wrap-top-middle clearfix"></div>
                            </div>
                        </div>
                        <div class="wrap-left clearfix">
                            <div class="wrap-content wrap-right clearfix">
                                <div id="messageContent" class="message_content"></div>
                            </div>
                        </div>
                        <div class="wrap-bottom-left">
                            <div class="wrap-bottom-right">
                                <div class="wrap-bottom-middle"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    <!--
                    function AllMessages(form)
                    {
                        for (var x = 0; x< form.elements.length; x++) {
                            var y = form.elements[x];
                            if (y.name != 'ALLMSGS') {
                                y.checked = form.ALLMSGS.checked;
                            }
                        }
                    }
                    //-->
                </script>
                <form method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="page" value="{{$page}}">
                    <input type="hidden" name="folder" value="{{$folder->getId()}}">
                    <br class="clearfloat">
                    <div class="table-wrap">
                        <div id="msgOptions" class="clearfix">
                            <select name="select" size="1">
                                <option value="masked">tagged messages</option>
                                <option value="all">all messages</option>
                                <option value="unmasked">untagged messages</option>
                            </select>
                            <select name="do" size="1">
                                <option value="del">delete</option>
                                <option value="read" selected="selected">mark as read</option>
                                @if($folder->id != 0)
                                <option value="move-to-0">move to inbox</option>
                                @endif
                                @foreach($folders as $f)
                                    @if($folder->id == $f->id)
                                        @continue
                                    @endif
                                <option value="move-to-{{$f->id}}">move to {{$f->folder_name}}</option>
                                @endforeach
                            </select>
                            <div class="btn-left left">
                                <div class="btn-right">
                                    <input type="submit" class="btn" name="edit" value="Go!">
                                </div>
                            </div>
                        </div>
                        <table id="message_overview" width="100%" cellspacing="0">
                            <tbody>
                            <tr>
                                <td class="tdn" colspan="5" style="padding:0px 5px;">
                                    <input class="check" type="checkbox" name="ALLMSGS" value="ALLMSGS" onclick="AllMessages(this.form);">all messages
                                </td>
                            </tr>
                            <tr class="noBackground">
                                <td colspan="2">&nbsp;</td>
                                <td id="title_date"><b>Date</b></td>
                                <td id="title_sender"><b>Sender</b></td>
                                <td id="title_subject"><b>Subject</b></td>
                            </tr>
                            @foreach ($messages as $msg)
                            <tr>
                                <td class="tdn" width="15" valign="top" style="padding:1px 5px;">
                                    <input class="check" type="checkbox" name="x{{$msg->id}}" value="y" style="margin:0px;">
                                </td>
                                <td class="tdn" width="30" valign="top" style="padding:1px 5px;">
                                    <img id="msg_status_{{$msg->id}}" alt="" src="{{asset('img/symbols/mail_status'.$msg->status.'.png')}}" style="display:inline; margin:0px;">
                                </td>
                                <td width="100" class="tdn" valign="top" style="padding:1px 5px;">
                                    <span id="message_{{$msg->id}}_time">{{date('d.m.Y H:i', strtotime($msg->send_time))}}</span>
                                </td>
                                <td class="tdn" width="20%" valign="top" style="padding:1px 5px;">
                                    <span id="message_{{$msg->id}}_sender">{{$folder->id == -1 ? user()->getName() : getMessageSenderNameFromMessage($msg)}}</span>
                                    <div id="message_{{$msg->id}}_receiver_id" style="display:none;">{{$msg->sender_id}}</div>
                                </td>
                                <td class="tdn" valign="top" style="padding:1px 5px;">
                                <span id="message_{{$msg->id}}_subject">
                                    <a @if($msg->report_id > 0) href="{{url('/hunt/report/fightreport/'.$msg->report_id.'?to=msgfolder&folderid='.$folder->id)}}" @endif
                                       @if($msg->report_id == 0) onclick="showMessageSplash('{{$msg->id}}'); return false;"  @else onclick="ajaxMailRead({{$msg->id}}, true);" @endif
                                       {{--class="fight_won fight_lost"--}}
                                    >{{$msg->subject}}</a>
                                    <div id="message_{{$msg->id}}_content" style="display:none;">
                                        <div id="message_{{$msg->id}}_content_subject">{{$msg->subject}}</div>
                                    <!--<div id="message_{{$msg->id}}_announce">
                                            <a class="copyright" href="/msg/complain/cmail?id={{$msg->id}}&amp;cc=2b55453">( report )</a>
                                        </div>-->
                                        <div id="message_{{$msg->id}}_content_text">
                                            @if($msg->report_id > 0)
                                            <a @if($msg->report_id > 0) href="{{url('/hunt/report/fightreport/'.$msg->report_id.'?to=msgfolder&folderid='.$folder->id)}}" @endif onclick="ajaxMailRead({{$msg->id}}, true);">Battle Report</a>
                                            @elseif($msg->gy_reward > 0)
                                            After successful shift working as the you get a salary of {{prettyNumber($msg->gy_reward)}} {{gold_image_tag()}} and {{prettyNumber($msg->gy_exp)}} experience points!
                                            @else
                                                @if($msg->sender_id == \Database\Models\Message::SENDER_SYSTEM)
                                                    {!! $msg->message !!}
                                                @else
                                                    {{ $msg->message }}
                                                @endif
                                            @endif
                                        </div>
                                        @if($msg->sender_id > 0 && !empty($msg->name))
                                        <div id="message_{{$msg->id}}_answer"><a href="#" onclick="showAnswerMessageSplash('{{$msg->id}}')">( answer )</a></div>
                                        <div id="message_{{$msg->id}}_answer_key">{{$msg->sender_id}}|{{user()->getId()}}|{{$msg->id}}</div>
                                        @endif

                                        @if($msg->status == 1)
                                        <div id="message_{{$msg->id}}_overlay_marks_as_read">true</div>
                                        @endif

                                        @if(isset($msg->previous_id)) <div id="message_{{$msg->id}}_previous_id">{{$msg->previous_id}}</div> @endif
                                        @if(isset($msg->next_id)) <div id="message_{{$msg->id}}_next_id">{{$msg->next_id}}</div> @endif
                                    </div>
                                </span>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td align="center" class="no-bg" colspan="4">
                                    Page:
                                    @for($i = 1; $i <= ceil($msg_count / 15); $i++)
                                        @if($page == $i)
                                            {{$i}}
                                        @else
                                        <a href="{{url('message/read/?folder='.$folder->id.'&page='.$i)}}">{{$i}}</a>
                                        @endif
                                    @endfor
                                    <script>
                                        function redirectToPage(option) {
                                            var pageNumber = option.value;
                                            window.location.href = "{{url('/message/read/?folder='.$folder->id.'&page=')}}" + pageNumber;
                                        }
                                    </script>

                                    <select size="1" onchange="redirectToPage(this)">
                                        @for($i = 1; $i <= ceil($msg_count / 15); $i++)
                                        <option value="{{$i}}" @if($page == $i) selected @endif >{{$i}}</option>
                                        @endfor
                                    </select>

                                </td>
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