@extends('index')

@section('content')
    <div id="addBuddy">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{user_race_logo_small()}}Buddy request</h2>
                <div>
                    @if(isset($formSent))
                        <p>Player has been contacted.</p>
                        <div class="table-wrap">
                            <table border="0" width="100%" cellpadding="2" cellspacing="2">
                                <tbody><tr>
                                    <td align="center"><a href="{{url('/buddy')}}">Buddy list</a></td>
                                    <td align="left"><a href="{{url('/preview/user/'.$to_id)}}">back</a></td>
                                </tr>
                                </tbody></table>
                        </div>
                    @else
                        @if($alreadyContacted)
                            <p>You are already in contact with this player</p>
                        @else
                            <p></p>
                            <div class="table-wrap">
                                <form method="post">
                                    {{csrf_field()}}
                                    <table border="0" width="100%" cellpadding="2" cellspacing="2">
                                        <tbody>
                                        <tr>
                                            <td align="center" width="50%">{{$bUser->name . (!empty($bUser->clan_tag) ? ' ' . $bUser->clan_tag : '')}}</td>
                                            <td rowspan="2">Text (max. 2000 Characters)
                                                <br>
                                                <textarea name="note" rows="4" cols="55" style="text-align:left"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <div class="btn-left center">
                                                    <div class="btn-right">
                                                        <input class="btn" name="ask" type="submit" value="Go!">
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <p></p>
                        @endif
                    @endif
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