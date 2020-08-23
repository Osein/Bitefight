@extends('index')

@section('content')
	<div class="btn-left left">
		<div class="btn-right">
			<a class="btn" href="{{url('/clan/index')}}" >back</a>
		</div>
	</div>
	<br class="clearfloat" />
	<div id="changeDescription">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}Here you can describe your clan and its goals</h2>
				<div class="clearfix">
					<form name="allydesc" method="POST">
						{{csrf_field()}}
						<h3>Description (<span id="charcount">4000</span> Characters)</h3>
						<div style="width:600px; margin:0 auto;">
							<div class="bbcode_toolbar">
								<div style="float:left;">
									<img title="bold" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_bold.gif')}}" onclick="insertBBCode('ally_description', '[b]', '[/b]')"/>
									<img title="italics" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_italic.gif')}}" onclick="insertBBCode('ally_description', '[i]', '[/i]')"/>

									<div id="toggleContainerLink" style="float:left;">
										<a id="toggleLinkLink" href="#">
											<img title="internal link" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_link.gif')}}"/>
										</a>

										<div id="togglePanelLink" class="linkPickerTogglePanel">
											<div class="linkPicker">
												<div class="linkPickerTitle">internal link</div>
												<div>
													<a href='javascript:insertBBCode("ally_description", "!S:\"", "\"!")'>Player                            - Name</a></div>
												<div>
													<a href='javascript:insertBBCode("ally_description", "!N:\"", "\"!")'>Clan                            - Name</a></div>
												<div>
													<a href='javascript:insertBBCode("ally_description", "!A:\"", "\"!")'>Clan                            - Clan tag</a></div>
											</div>
										</div>
									</div>

									<div id="toggleContainerColor" style="float:left;">
										<a id="toggleLinkColor" href="#">
											<img title="Font colour" class="bbcode_button" src="{{asset('img/symbols/bbcode/bbcode_color.gif')}}"/>
										</a>

										<div id="togglePanelColor" class="colorPickerTogglePanel">
											<div class="colorPicker">
												<ul>
													<li>
														<a title="#000000" style="background-color: #000000;" href='javascript:insertBBCode("ally_description", "[f c=#000000]", "[/f]")'></a>
													</li>
													<li>
														<a title="#222222" style="background-color: #222222;" href='javascript:insertBBCode("ally_description", "[f c=#222222]", "[/f]")'></a>
													</li>
													<li>
														<a title="#444444" style="background-color: #444444;" href='javascript:insertBBCode("ally_description", "[f c=#444444]", "[/f]")'></a>
													</li>
													<li>
														<a title="#666666" style="background-color: #666666;" href='javascript:insertBBCode("ally_description", "[f c=#666666]", "[/f]")'></a>
													</li>
													<li>
														<a title="#999999" style="background-color: #999999;" href='javascript:insertBBCode("ally_description", "[f c=#999999]", "[/f]")'></a>
													</li>
													<li>
														<a title="#cccccc" style="background-color: #cccccc;" href='javascript:insertBBCode("ally_description", "[f c=#cccccc]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ffffff" style="background-color: #ffffff;" href='javascript:insertBBCode("ally_description", "[f c=#ffffff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#000066" style="background-color: #000066;" href='javascript:insertBBCode("ally_description", "[f c=#000066]", "[/f]")'></a>
													</li>
													<li>
														<a title="#006666" style="background-color: #006666;" href='javascript:insertBBCode("ally_description", "[f c=#006666]", "[/f]")'></a>
													</li>
													<li>
														<a title="#006600" style="background-color: #006600;" href='javascript:insertBBCode("ally_description", "[f c=#006600]", "[/f]")'></a>
													</li>
													<li>
														<a title="#666600" style="background-color: #666600;" href='javascript:insertBBCode("ally_description", "[f c=#666600]", "[/f]")'></a>
													</li>
													<li>
														<a title="#663300" style="background-color: #663300;" href='javascript:insertBBCode("ally_description", "[f c=#663300]", "[/f]")'></a>
													</li>
													<li>
														<a title="#660000" style="background-color: #660000;" href='javascript:insertBBCode("ally_description", "[f c=#660000]", "[/f]")'></a>
													</li>
													<li>
														<a title="#660066" style="background-color: #660066;" href='javascript:insertBBCode("ally_description", "[f c=#660066]", "[/f]")'></a>
													</li>
													<li>
														<a title="#000099" style="background-color: #000099;" href='javascript:insertBBCode("ally_description", "[f c=#000099]", "[/f]")'></a>
													</li>
													<li>
														<a title="#009999" style="background-color: #009999;" href='javascript:insertBBCode("ally_description", "[f c=#009999]", "[/f]")'></a>
													</li>
													<li>
														<a title="#009900" style="background-color: #009900;" href='javascript:insertBBCode("ally_description", "[f c=#009900]", "[/f]")'></a>
													</li>
													<li>
														<a title="#999900" style="background-color: #999900;" href='javascript:insertBBCode("ally_description", "[f c=#999900]", "[/f]")'></a>
													</li>
													<li>
														<a title="#993300" style="background-color: #993300;" href='javascript:insertBBCode("ally_description", "[f c=#993300]", "[/f]")'></a>
													</li>
													<li>
														<a title="#990000" style="background-color: #990000;" href='javascript:insertBBCode("ally_description", "[f c=#990000]", "[/f]")'></a>
													</li>
													<li>
														<a title="#990099" style="background-color: #990099;" href='javascript:insertBBCode("ally_description", "[f c=#990099]", "[/f]")'></a>
													</li>
													<li>
														<a title="#0000ff" style="background-color: #0000ff;" href='javascript:insertBBCode("ally_description", "[f c=#0000ff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#00ffff" style="background-color: #00ffff;" href='javascript:insertBBCode("ally_description", "[f c=#00ffff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#00ff00" style="background-color: #00ff00;" href='javascript:insertBBCode("ally_description", "[f c=#00ff00]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ffff00" style="background-color: #ffff00;" href='javascript:insertBBCode("ally_description", "[f c=#ffff00]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ff6600" style="background-color: #ff6600;" href='javascript:insertBBCode("ally_description", "[f c=#ff6600]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ff0000" style="background-color: #ff0000;" href='javascript:insertBBCode("ally_description", "[f c=#ff0000]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ff00ff" style="background-color: #ff00ff;" href='javascript:insertBBCode("ally_description", "[f c=#ff00ff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#9999ff" style="background-color: #9999ff;" href='javascript:insertBBCode("ally_description", "[f c=#9999ff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#99ffff" style="background-color: #99ffff;" href='javascript:insertBBCode("ally_description", "[f c=#99ffff]", "[/f]")'></a>
													</li>
													<li>
														<a title="#99ff99" style="background-color: #99ff99;" href='javascript:insertBBCode("ally_description", "[f c=#99ff99]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ffff99" style="background-color: #ffff99;" href='javascript:insertBBCode("ally_description", "[f c=#ffff99]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ffcc99" style="background-color: #ffcc99;" href='javascript:insertBBCode("ally_description", "[f c=#ffcc99]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ff9999" style="background-color: #ff9999;" href='javascript:insertBBCode("ally_description", "[f c=#ff9999]", "[/f]")'></a>
													</li>
													<li>
														<a title="#ff99ff" style="background-color: #ff99ff;" href='javascript:insertBBCode("ally_description", "[f c=#ff99ff]", "[/f]")'></a>
													</li>
												</ul>
											</div>
										</div>
									</div>

								</div>
								<div style="float:right;">
									<select name="fontsize" class="bbcode_dropdown" onchange='bbDropdown("ally_description", this, "[f s=$var]", "[\/f]")'>
										<option value="none" selected="selected">Font size</option>
										<option value="8" style="font-size: 8pt;">8</option>
										<option value="10" style="font-size: 10pt;">10</option>
										<option value="12" style="font-size: 12pt;">12</option>
										<option value="14" style="font-size: 14pt;">14</option>
										<option value="18" style="font-size: 18pt;">18</option>
										<option value="24" style="font-size: 24pt;">24</option>
										<option value="36" style="font-size: 36pt;">36</option>
									</select>
									<select name="fontface" class="bbcode_dropdown" onchange='bbDropdown("ally_description", this, "[f f=\"$var\"]", "[\/f]")'>
										<option value="none" selected="selected">Font</option>
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
							<textarea id="ally_description" name="description" cols="70" rows="20" onkeyup="CheckLen(this)">@if($description) {{$description}} @endif</textarea>
							<div class="btn-left left"><div class="btn-right">
									<input type="submit" class="btn" name="save" value="save">
								</div></div>
							<div class="btn-left left"><div class="btn-right">
									<input type="submit" class="btn" name="delete" value="delete">
								</div></div>
							<br class="clearfloat"/>
						</div>
					</form>
					<script language="JavaScript">
						function CheckLen(Target)
						{
							var maxlength = 4000; //die maximale Zeichenl�nge
							var StrLen=Target.value.replace(/\r\n?/g, "\n").length;
							var CharsLeft;
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

						var description = document.getElementById('ally_description');
						CheckLen(description);
					</script>
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