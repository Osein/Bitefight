@extends('index')

@section('content')
    <div id="login">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{__('home.home_login_header')}}</h2>
                <div class="table-wrap">
                    <table width="100%">
                        <tr>
                            <td><img src="{{asset('img/symbols/race1.gif')}}" alt="{{__('general.vampire')}}" ></td>
                            <td>
                                <form id="loginForm" name="loginForm" method="POST">
									{{csrf_field()}}
                                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                        <tr>
                                            <td>{{__('general.name')}}:</td>
                                            <td><input class="input" type="text" name="user" MAXLENGTH="32" value="{{ old('user') }}" autofocus></td>
                                        </tr>
                                        <tr>
                                            <td>{{__('general.password')}}:</td>
                                            <td><input class="input" type="password" name="pass" MAXLENGTH="20">
                                                <br />
                                                <a href="{{ route('password.request') }}" target="_top" id="pwlostLink">{{__('home.home_login_forgot_password')}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><input type="submit" class="btn-small" value="{{__('general.login')}}"></td>
                                    </table>
                                </form>
                            </td>
                            <td><img src="{{asset('img/symbols/race2.gif')}}" alt="{{__('general.werewolf')}}" ></td>
                        </tr>
                    </table>

					@if ($errors->any())
						<div class="error">
							{{__('validation.custom.invalid_login')}}
						</div>
					@endif

                    <br class="clearfloat" />
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
