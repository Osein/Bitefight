@extends('index')

@section('content')

    <script type="text/javascript">
		$('#header').find('h1').text('{{__('general.lost_password')}}');
    </script>
    <div id="login">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{__('home.home_forgot_pass_header')}}</h2>
                <form action="{{ route('password.request') }}" method="POST">
                    {{csrf_field()}}

                    <input type="hidden" name="token" value="{{ $token }}">

                    @foreach($errors->all() as $error)
                        <div class="error">
                            {{$error}}
                        </div>
                    @endforeach
                    <div class="table-wrap">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tbody><tr>
                                <td align="center" valign="top"><img src="{{asset('img/symbols/race1.gif')}}" alt="{{__('general.vampire')}}"></td>
                                <td valign="top">
                                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td>{{__('general.e-mail')}}</td>
                                            <td><input class="input" type="text" name="email" size="30" maxlength="130" value="{{ $email or old('email') }}" required autofocus></td>
                                        </tr>
                                        <tr>
                                            <td>Password</td>
                                            <td>
                                                <input id="password" type="password" class="form-control" name="password" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Confirm Password</td>
                                            <td>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><input type="submit" class="btn-small" value="{{__('general.send')}}"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td align="center" valign="top"><img src="{{asset('img/symbols/race2.gif')}}" alt="{{__('general.werewolf')}}"></td>
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
