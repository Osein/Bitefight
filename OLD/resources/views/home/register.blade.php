@extends('index')

@section('content')
    @if($showRaceSelection)
		<script language="JavaScript">
		    p1 = new Image();
		    p1.src = "{{asset('img/symbols/race1loginen.gif')}}";
		    p1x = new Image();
		    p1x.src = "{{asset('img/symbols/race1loginhoveren.gif')}}";
		    p2 = new Image();
		    p2.src = "{{asset('img/symbols/race2loginen.gif')}}";
		    p2x = new Image();
		    p2x.src = "{{asset('img/symbols/race2loginhoveren.gif')}}";
		</script>
		<div id="chooseRace">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<div class="table-wrap">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="40%" align="left">
									<a href="{{route('register', ['race' => 1])}}" onfocus="if(this.blur)this.blur()"
									   target="_top" onmouseover="document.pic1.src = p1x.src"
									   onmouseout="document.pic1.src = p1.src">
										<img src="{{asset('img/symbols/race1loginen.gif')}}" alt="{{__('general.vampire')}}"
											 name="pic1" border="0">
									</a>
								</td>
								<td width="20%">{{__('home.home_register_select_race')}}</td>
								<td width="40%" align="right">
									<a href="{{route('register', ['race' => 2])}}" onfocus="if(this.blur)this.blur()"
									   target="_top" onmouseover="document.pic2.src = p2x.src"
									   onmouseout="document.pic2.src = p2.src">
										<img src="{{asset('img/symbols/race2loginen.gif')}}" alt="{{__('general.werewolf')}}"
											 name="pic2" border="0">
									</a>
								</td>
							</tr>
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
    @else
		<div id="register">
			<div class="wrap-top-left clearfix">
				<div class="wrap-top-right clearfix">
					<div class="wrap-top-middle clearfix"></div>
				</div>
			</div>
			<div class="wrap-left clearfix">
				<div class="wrap-content wrap-right clearfix">
					<h2><img src="{{asset('img/symbols/race'.$race.'small.gif')}}" alt=""/>{{__('general.menu_register')}}</h2>
					<div class="table-wrap">
						<script type="text/javascript">
						    $(function () {
							    var timer;
							    $("#name").keyup(function () {
								    if(timer) {
									    clearTimeout(timer);
									    timer = null
								    }
								    timer = setTimeout(function() {
									    ajaxCheck("name");
								    }, 500)
							    });

							    $("#email").keyup(function () {
								    if(timer) {
									    clearTimeout(timer);
									    timer = null
								    }
								    timer = setTimeout(function() {
									    ajaxCheck("email");
								    }, 500)
							    });

							    $("#agb").change(function () {
								    if ($("#agb").is(':checked')){
									    $("#agb_status").html('<img src="{{asset('img/symbols/tick.gif')}}"/>');
								    } else {
									    $("#agb_status").html('<img src="{{asset('img/symbols/cross.gif')}}"/>');
								    }
							    });
						    });

						    function ajaxCheck(name){
							    $.getJSON("{{url('/ajax/register')}}",
								    $("#"+name).serialize(),
								    function(data){
									    if (data.status){
										    $("#"+name+"_status").html('<img src="{{asset('img/symbols/tick.gif')}}" style="vertical-align:middle;"/>');
									    } else {
										    $("#"+name+"_status").html('<img src="{{asset('img/symbols/cross.gif')}}" style="vertical-align:middle;"/><b style="color:yellow" class="fontsmall">'+data.messages+'</b>');
									    }
								    })
						    }
						</script>
						<h3>{{__('home.home_register_header')}}</h3>
						<p>{!! __('home.home_register_unnecessary_info') !!}</p>

						@foreach($errors->all() as $error)
							<div class="error">{{$error}}</div>
						@endforeach

						<br>
						<form id="registerForm" name="registerForm"  method="POST">
							<input type="hidden" name="race" value="{{$race}}">
							{{csrf_field()}}
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td align="center" valign="top">
										<table cellpadding="2" cellspacing="2" border="0" width="100%">
											<tr>
												<td>{{__('home.home_register_name_label')}}:</td>
												<td>
													<input class="input" type="text" id="name" name="name" size="32" MAXLENGTH="32" value="{{old('name')}}" autofocus autocomplete="username">
													<span id="name_status">
                                                    <img src="{{asset('img/symbols/cross.gif')}}" style="vertical-align:middle;"/>
                                                </span>
												</td>
											</tr>
											<tr>
												<td>{{__('home.home_register_password_label')}}:</td>
												<td>
													<input class="input" type="password" name="pass" id="pass" maxlength="20" size="20" autocomplete="current-password">
												</td>
											</tr>
											<tr>
												<td>{{__('home.home_register_mail_label')}}:</td>
												<td>
													<input class="input" type="text" id="email" name="email" size="128" maxlength="128" value="{{old('email')}}">
													<span id="email_status">
                                                    <img src="{{asset('img/symbols/cross.gif')}}" style="vertical-align:middle;"/>
                                                </span>
												</td>
											</tr>
											<tr>
                                            	<td></td>
                                            	<td>{{__('home.home_register_activation_info')}}</td>
                                        	</tr>
											<tr>
												<td colspan="2">
                                                <span class="fontsmall">
                                                    <input type="checkbox" id="agb" name="agb" value="ok" {{old('agb') ? 'checked' : ''}}>{{__('home.home_register_accept_agb')}}
													<span id="agb_status" style="margin-left:12px;">
                                                        <img src="{{asset('img/symbols/cross.gif')}}" style="vertical-align:middle;"/>
                                                    </span>
                                                </span>
												</td>
											</tr>
											<tr>
												<td align="center" colspan="2">
													<div class="btn-left left">
														<div class="btn-right">
															<input type="submit" id="registerButton" class="btn" value="{{__('general.send')}}">
														</div>
													</div>
												</td>
											</tr>
										</table>
									</td>
									<td valign="top">
										<img src="{{asset('img/symbols/race'.$race.'.gif')}}" alt="{{$race == 1 ? __('vampire') : __('werewolf')}}" >
									</td>
								</tr>
							</table>
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
    @endif
@endsection
