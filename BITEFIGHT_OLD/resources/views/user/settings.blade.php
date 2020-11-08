@extends('index')

@section('content')
    <form method="POST">
        {{csrf_field()}}
        <div id="settings">
            <h1>{{__('user.settings_profile_settings_for', ['user' => e(user()->getName())])}}</h1>
            <div id="changeEmail">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}change e-mail address</h2>
                        <div class="table-wrap">
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <colgroup>
                                    <col width="300">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>e-mail address: (Permanent)</td>
                                    <td>{{user()->getEmail()}}</td>
                                </tr>
                                <tr>
                                    <td>e-mail address:</td>
                                    <td><input type="text" name="email" size="30" value="{{$email_activation && $email_activation->email != user()->getEmail() ? $email_activation->getEmail() : ''}}" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">The e-mail address becomes permanent 7 days after the validation. The new e-mail address needs to be validated within 3 days otherwise the change is cancelled.</td>
                                </tr>
                                <tr>
                                    @if(empty($email_activation) || $email_activation->activated)
                                        <td colspan="2">Thank you for activating your e-mail address.</td>
                                    @elseif($activationEmailSent)
                                        <td colspan="2" style="color:yellow">An email containing your activation link has been sent.</td>
                                    @else
                                        <td colspan="2">Your e-mail has not been activated yet. <a href="{{url('/settings?activate=1')}}"> Click here </a> to get an activation e-mail.</td>
                                    @endif
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
            <div id="discribeChar">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}{{__('user.settings_rpg_description')}}</h2>
                        <script language="JavaScript">
                            function init() {
                                var rpgfield = document.getElementsByName('rpg')[0];
                                CheckLen(rpgfield);
                            }

                            function CheckLen(Target) {
                                var maxlength = "4000";
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

                            function char_count(a,value) {
                                return value.split(a).length-1;
                            }
                        </script>
                        <p></p>
                        <div class="bbcode_toolbar">
                            <div style="float:left;">
                                <img title="{{__('user.settings_font_bold')}}" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_bold.gif')}}" onclick="insertBBCode('rpg', '[b]', '[/b]')">
                                <img title="{{__('user.settings_font_italic')}}" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_italic.gif')}}" onclick="insertBBCode('rpg', '[i]', '[/i]')">

                                <div id="toggleContainerLink" style="float:left;">
                                    <a id="toggleLinkLink" href="#" class="toggleHidden">
                                        <img title="{{__('user.settings_internal_link')}}" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_link.gif')}}">
                                    </a>

                                    <div id="togglePanelLink" class="linkPickerTogglePanel" style="display: none;">
                                        <div class="linkPicker">
                                            <div class="linkPickerTitle">{{__('user.settings_internal_link')}}</div>
                                            <div>
                                                <a href='javascript:insertBBCode("rpg", "!S:\"", "\"!")'>{{__('user.settings_link_player')}}</a></div>
                                            <div>
                                                <a href='javascript:insertBBCode("rpg", "!N:\"", "\"!")'>{{__('user.settings_link_clan_name')}}</a></div>
                                            <div>
                                                <a href='javascript:insertBBCode("rpg", "!A:\"", "\"!")'>{{__('user.settings_link_clan_tag')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="toggleContainerColor" style="float:left;">
                                    <a id="toggleLinkColor" href="#" class="toggleHidden">
                                        <img title="{{__('user.settings_font_colour')}}" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_color.gif')}}">
                                    </a>

                                    <div id="togglePanelColor" class="colorPickerTogglePanel" style="display: none;">
                                        <div class="colorPicker">
                                            <ul>
                                                <li>
                                                    <a title="#000000" style="background-color: #000000;" href='javascript:insertBBCode("rpg", "[f c=#000000]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#222222" style="background-color: #222222;" href='javascript:insertBBCode("rpg", "[f c=#222222]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#444444" style="background-color: #444444;" href='javascript:insertBBCode("rpg", "[f c=#444444]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#666666" style="background-color: #666666;" href='javascript:insertBBCode("rpg", "[f c=#666666]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#999999" style="background-color: #999999;" href='javascript:insertBBCode("rpg", "[f c=#999999]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#cccccc" style="background-color: #cccccc;" href='javascript:insertBBCode("rpg", "[f c=#cccccc]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ffffff" style="background-color: #ffffff;" href='javascript:insertBBCode("rpg", "[f c=#ffffff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#000066" style="background-color: #000066;" href='javascript:insertBBCode("rpg", "[f c=#000066]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#006666" style="background-color: #006666;" href='javascript:insertBBCode("rpg", "[f c=#006666]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#006600" style="background-color: #006600;" href='javascript:insertBBCode("rpg", "[f c=#006600]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#666600" style="background-color: #666600;" href='javascript:insertBBCode("rpg", "[f c=#666600]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#663300" style="background-color: #663300;" href='javascript:insertBBCode("rpg", "[f c=#663300]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#660000" style="background-color: #660000;" href='javascript:insertBBCode("rpg", "[f c=#660000]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#660066" style="background-color: #660066;" href='javascript:insertBBCode("rpg", "[f c=#660066]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#000099" style="background-color: #000099;" href='javascript:insertBBCode("rpg", "[f c=#000099]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#009999" style="background-color: #009999;" href='javascript:insertBBCode("rpg", "[f c=#009999]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#009900" style="background-color: #009900;" href='javascript:insertBBCode("rpg", "[f c=#009900]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#999900" style="background-color: #999900;" href='javascript:insertBBCode("rpg", "[f c=#999900]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#993300" style="background-color: #993300;" href='javascript:insertBBCode("rpg", "[f c=#993300]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#990000" style="background-color: #990000;" href='javascript:insertBBCode("rpg", "[f c=#990000]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#990099" style="background-color: #990099;" href='javascript:insertBBCode("rpg", "[f c=#990099]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#0000ff" style="background-color: #0000ff;" href='javascript:insertBBCode("rpg", "[f c=#0000ff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#00ffff" style="background-color: #00ffff;" href='javascript:insertBBCode("rpg", "[f c=#00ffff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#00ff00" style="background-color: #00ff00;" href='javascript:insertBBCode("rpg", "[f c=#00ff00]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ffff00" style="background-color: #ffff00;" href='javascript:insertBBCode("rpg", "[f c=#ffff00]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ff6600" style="background-color: #ff6600;" href='javascript:insertBBCode("rpg", "[f c=#ff6600]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ff0000" style="background-color: #ff0000;" href='javascript:insertBBCode("rpg", "[f c=#ff0000]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ff00ff" style="background-color: #ff00ff;" href='javascript:insertBBCode("rpg", "[f c=#ff00ff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#9999ff" style="background-color: #9999ff;" href='javascript:insertBBCode("rpg", "[f c=#9999ff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#99ffff" style="background-color: #99ffff;" href='javascript:insertBBCode("rpg", "[f c=#99ffff]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#99ff99" style="background-color: #99ff99;" href='javascript:insertBBCode("rpg", "[f c=#99ff99]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ffff99" style="background-color: #ffff99;" href='javascript:insertBBCode("rpg", "[f c=#ffff99]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ffcc99" style="background-color: #ffcc99;" href='javascript:insertBBCode("rpg", "[f c=#ffcc99]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ff9999" style="background-color: #ff9999;" href='javascript:insertBBCode("rpg", "[f c=#ff9999]", "[/f]")'></a>
                                                </li>
                                                <li>
                                                    <a title="#ff99ff" style="background-color: #ff99ff;" href='javascript:insertBBCode("rpg", "[f c=#ff99ff]", "[/f]")'></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div style="float:right;">
                                <select name="fontsize" class="bbcode_dropdown" onchange="bbDropdown('rpg', this, '[f s=$var]', '[\\/f]')">
                                    <option value="none" selected="selected">{{__('general.font_size')}}</option>
                                    <option value="8" style="font-size: 8pt;">8</option>
                                    <option value="10" style="font-size: 10pt;">10</option>
                                    <option value="12" style="font-size: 12pt;">12</option>
                                    <option value="14" style="font-size: 14pt;">14</option>
                                    <option value="18" style="font-size: 18pt;">18</option>
                                    <option value="24" style="font-size: 24pt;">24</option>
                                    <option value="36" style="font-size: 36pt;">36</option>
                                </select>
                                <select name="fontface" class="bbcode_dropdown" onchange="bbDropdown('rpg', this, '[f f=$var]', '[\/f]')">
                                    <option value="none" selected="selected">{{__('general.font')}}</option>
                                    <option value="arial" style="font-family: Arial, Helvetica, sans-serif;">Arial</option>
                                    <option value="chicago" style="font-family: Chicago, Impact, Compacta, sans-serif;">Chicago</option>
                                    <option value="comic_sans_ms" style="font-family: Comic Sans MS, sans-serif;">Comic Sans MS</option>
                                    <option value="courier_new" style="font-family: Courier New, Courier, mono;">Courier New</option>
                                    <option value="geneva" style="font-family: Geneva, Arial, Helvetica, sans-serif;">Geneva</option>
                                    <option value="georgia" style="font-family: Georgia, Times New Roman, Times, serif;">Georgia</option>
                                    <option value="helvetica" style="font-family: Helvetica, Verdana, sans-serif;">Helvetica</option>
                                    <option value="impact" style="font-family: Impact, Compacta, Chicago, sans-serif;">Impact</option>
                                    <option value="lucida_sans" style="font-family: Lucida Sans, Monaco, Geneva, sans-serif;">Lucida Sans</option>
                                    <option value="tahoma" style="font-family: Tahoma, Arial, Helvetica, sans-serif;">Tahoma</option>
                                    <option value="times_new_roman" style="font-family: Times New Roman, Times, Georgia, serif;">Times New Roman</option>
                                    <option value="trebuchet_ms" style="font-family: Trebuchet MS, Arial, sans-serif;">Trebuchet MS</option>
                                    <option value="verdana" style="font-family: Verdana, Helvetica, sans-serif;">Verdana</option>
                                </select>
                            </div>
                        </div>

                        <script type="text/javascript">
                            var panelOverseer = new PanelOverseer();
                            panelOverseer.registerPanelContainer('Color', 1500);
                            panelOverseer.registerPanelContainer('Link', 1500);
                        </script>
                        <textarea name="rpg" id="rpg" rows="15" cols="75" style="text-align:center">{{isset($description) ? $description->getDescription() : ''}}</textarea>
                        <p></p>
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>
            <div id="setLogo">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}show character picture</h2>
                        <div class="clearfix">
                            <input type="checkbox" name="showlogo" @if(user()->isShowPicture()) checked="" @endif >
                            <label>Your character picture will be shown instead of the race logo if you check this box.</label>
                            <br class="clearfloat">
                        </div>
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>
            <div id="setVacation">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}Go into hiding</h2>
                        <div class="clearfix">
                            <input type="checkbox" name="vacation" @if(isset($vacationDays) && $vacationDays < 30) checked @endif>
                            <label>You cannot be attacked while you are in hiding (min. 2 days, max. 30 days).</label>
                            <br class="clearfloat">
                        </div>
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>

            <div id="pwChange">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}change password</h2>
                        <div class="table-wrap">
                            <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                <colgroup>
                                    <col width="300">
                                </colgroup>
                                <tbody><tr>
                                    <td>old password:</td>
                                    <td colspan="2"><input type="password" name="pass0" size="20" maxlength="30"></td>
                                </tr>
                                <tr>
                                    <td>new password:</td>
                                    <td><input id="password1" type="password" name="pass1" size="20" maxlength="30"></td>
                                </tr>
                                <tr>
                                    <td>confirm password:</td>
                                    <td colspan="2"><input type="password" name="pass2" size="20" maxlength="30"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="background:none;">
                                        <div class="pw_check">
                                            <div style="text-align:left;">Password check:</div>
                                            <div class="password-meter">
                                                <span class="password">Low</span><span class="password">Average</span><span class="password">High</span>
                                            </div>
                                            <div id="password-meter">

                                                <span class="password weak"></span>
                                                <span class="password medium"></span>
                                                <span class="password best"></span>
                                            </div>
                                            <div class="pw_arrow">
                                                <span id="password-meter-rating-low" class="password arrow"></span>
                                                <span id="password-meter-rating-medium" class="password"></span>
                                                <span id="password-meter-rating-high" class="password"></span>
                                            </div>

                                        </div>

                                        <div class="password_prop" style="text-align:left;">
                                            <p>The password should contain the following characteristics:</p>
                                            <ul>
                                                <li id="password-meter-status-length">min. 8 characters            <img src="{{asset('img/symbols/tick.gif')}}" class="status-checked" style="visibility: hidden;"></li>
                                                <li id="password-meter-status-mixed-case">Upper and lower case            <img src="{{asset('img/symbols/tick.gif')}}" class="status-checked" style="visibility: hidden;"></li>

                                                <li id="password-meter-status-special-chars">Special characters (e.g.!?:_., )            <img src="{{asset('img/symbols/tick.gif')}}" class="status-checked" style="visibility: hidden;"></li>
                                                <li id="password-meter-status-numbers">Numbers            <img src="{{asset('img/symbols/tick.gif')}}" class="status-checked" style="visibility: hidden;"></li>
                                            </ul>
                                        </div>

                                        <script type="text/javascript">
                                            $(document).ready(function()
                                            {
                                                $('#password1').bind('keyup', function()
                                                {
                                                    var value = $(this).val();
                                                    var length = value.length;
                                                    var hasSpecialChars = value.match(/[^A-Za-z\d]/);
                                                    var hasNumbers = value.match(/\d/);
                                                    var hasMixedCase = value.match(/[a-z]/) && value.match(/[A-Z]/);
                                                    var score = 0;
                                                    var maxScore = 4;
                                                    var fulfilled = {
                                                        'length':        false,
                                                        'mixed-case':    false,
                                                        'special-chars': false,
                                                        'numbers':       false
                                                    };

                                                    if (length >= 8) {
                                                        fulfilled['length'] = true;
                                                        score++;
                                                    }

                                                    if (hasMixedCase) {
                                                        fulfilled['mixed-case'] = true;
                                                        score++;
                                                    }

                                                    if (hasNumbers) {
                                                        fulfilled['numbers'] = true;
                                                        score++;
                                                    }

                                                    if (hasSpecialChars) {
                                                        fulfilled['special-chars'] = true;
                                                        score++;
                                                    }

                                                    for (var name in fulfilled) {
                                                        var isFulfilled = fulfilled[name];
                                                        var element     = $('#password-meter-status-' + name);

                                                        element.find('img.status-checked').css('visibility', isFulfilled ? 'visible' : 'hidden');
                                                    }

                                                    var rating = Math.floor(score / maxScore * 2);
                                                    var levels = {0:'low', 1:'medium', 2:'high'};

                                                    for (var i in levels) {
                                                        if (i != rating) {
                                                            $('#password-meter-rating-' + levels[i]).removeClass('arrow');
                                                        } else {
                                                            $('#password-meter-rating-' + levels[i]).addClass('arrow');
                                                        }
                                                    }
                                                });
                                            });

                                        </script>            </td>
                                </tr>
                                </tbody></table>
                        </div>
                        @if(session()->has('settings_password_change_error'))
                            <div class="error">{{session()->get('settings_password_change_error')}}</div>
                        @endif
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>
            <div id="accDelete">
                <div class="wrap-top-left clearfix">
                    <div class="wrap-top-right clearfix">
                        <div class="wrap-top-middle clearfix"></div>
                    </div>
                </div>
                <div class="wrap-left clearfix">
                    <div class="wrap-content wrap-right clearfix">
                        <h2>{{user_race_logo_small()}}delete account</h2>
                        <div class="clearfix">
                            <input class="check" type="checkbox" name="delete"><label>Check the box to request your account deletion!</label>
                            <br class="clearfloat">
                        </div>
                    </div>
                </div>
                <div class="wrap-bottom-left">
                    <div class="wrap-bottom-right">
                        <div class="wrap-bottom-middle"></div>
                    </div>
                </div>
            </div>

            <br>

            <div class="btn-left center">
                <div class="btn-right"><input type="submit" class="btn" value="{{__('general.save')}}">
                </div>
            </div>
        </div>
    </form>
@endsection