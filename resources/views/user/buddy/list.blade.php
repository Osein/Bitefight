@extends('index')

@section('content')
    <div id="buddyList">
        <!-- box HEADER START -->
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <!-- box HEADER END -->
        <!-- box CONTENT START -->
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <!-- CONTENT START -->
                <h2>{{user_race_logo_small()}}Buddy list</h2>
                <div class="table-wrap">
                    <table cellpadding="2" cellspacing="2" border="0" width="100%" align="center">
                        <tbody>
                        <tr>
                            <td class="tdh" align="center">Open requests</td>
                        </tr>
                        @foreach($receivedRequests as $request)
                            <tr>
                                <td class="no-bg" align="center">
                                    <form method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="buddy_id" value="{{$request->from_id}}">
                                        <table border="0" cellpadding="2" cellspacing="2" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" align="left">{{date('d-m-Y H:i')}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="left">
                                                        <a href="{{url('/preview/user/'.$request->from_id)}}">{{$request->name}}</a>
                                                        @if($request->clan_id)
                                                            &nbsp;<a href="{{url('/preview/clan/'.$request->clan_id)}}">[{{$request->clan_tag}}]</a>
                                                        @endif
                                                    </td>
                                                    {{--<td align="right">
                                                        <a href="/msg/complain/buddy/?id=83450&amp;cc=56e5353" class="copyright">(report)</a>
                                                    </td>--}}
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="left">
                                                        <textarea rows="4" cols="55" disabled="">test</textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="no-bg" align="center">
                                                        <div class="btn-left left">
                                                            <div class="btn-right">
                                                                <input class="btn" name="accept" type="submit" value="OK, accepted">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="no-bg" align="center">
                                                        <div class="btn-left right">
                                                            <div class="btn-right">
                                                                <input class="btn" name="deny" type="submit" value="No way">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="tdh" align="center">Own requests</td>
                        </tr>
                        @foreach($ownRequests as $request)
                        <tr>
                            <td class="tdn" align="center">
                                <form method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="buddy_id" value="{{$request->to_id}}">
                                    <table border="0" cellpadding="2" cellspacing="2" width="90%">
                                        <tbody>
                                            <tr>
                                                <td class="no-bg" align="left">{{date('d-m-Y H:i', $request->request_time)}}</td>
                                                <td class="no-bg" align="left">
                                                    <a href="{{url('/preview/user/'.$request->to_id)}}">{{$request->name}}</a>
                                                    @if($request->clan_id)
                                                    &nbsp;<a href="{{url('/preview/clan/'.$request->clan_id)}}">[{{$request->clan_tag}}]</a>
                                                    @endif
                                                </td>
                                                <td class="no-bg">
                                                    <div class="btn-left right">
                                                        <div class="btn-right">
                                                            <input name="takeback" type="submit" class="btn" value="Withdraw">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="tdh" align="center">Buddy list</td>
                        </tr>
                        <tr>
                            <td class="no-bg" align="center">
                                @if(!$buddies->count())
                                    No entries
                                @else
                                    <table border="0" cellpadding="2" cellspacing="2" width="100%">
                                        <tbody>
                                        <tr>
                                            <td><a href="{{urlGetParams('/buddy', ['sort' => 'race', 'order' => $sort == 'race' && $order == 'desc' ? 'down' : 'up'])}}">Race</a></td>
                                            <td><a href="{{urlGetParams('/buddy', ['sort' => 'name', 'order' => $sort == 'name' && $order == 'desc' ? 'down' : 'up'])}}">Name</a></td>
                                            <td><a href="{{urlGetParams('/buddy', ['sort' => 'clan', 'order' => $sort == 'clan' && $order == 'desc' ? 'down' : 'up'])}}">Clan</a></td>
                                            <td><a href="{{urlGetParams('/buddy', ['sort' => 'level', 'order' => $sort == 'level' && $order == 'desc' ? 'down' : 'up'])}}">Level</a></td>
                                            <td><a href="{{urlGetParams('/buddy', ['sort' => 'status', 'order' => $sort == 'status' && $order == 'desc' ? 'down' : 'up'])}}">Status</a></td>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        @foreach($buddies as $buddy)
                                            <tr>
                                                <td align="center"><img src="{{asset('/img/symbols/race'.$buddy->race.'small.gif')}}" alt=""></td>
                                                <td><a href="{{url('/preview/user/'.$buddy->user_id)}}">{{$buddy->user_name}}</a></td>
                                                <td>@if($buddy->clan_id && $buddy->clan_tag)&nbsp;<a href="{{url('/preview/clan/'.$buddy->clan_id)}}">[{{$buddy->clan_tag}}]</a>@endif</td>
                                                <td>{{prettyNumber(getLevel($buddy->exp))}}</td>
                                                <td><font color="{{getUserStatusColor($buddy->last_activity)}}">online</font></td>
                                                <td valign="middle">
                                                    <div class="btn-left left">
                                                        <div class="btn-right">
                                                            <a href="#" class="btn" onclick="writeMessageSplash('Xyir')">Write message</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td valign="middle">
                                                    <form method="post">
                                                        <input type="hidden" name="buddy_id" value="{{$buddy->user_id}}">
                                                        {{csrf_field()}}
                                                        <div class="btn-left left">
                                                            <div class="btn-right">
                                                                <input class="btn" name="delete" type="submit" value="delete">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                        </tr>
                        </tbody></table>
                </div>
                <!-- CONTENT END -->
            </div>
        </div>
        <!-- box CONTENT END -->
        <!-- box FOOTER START -->
        <div class="wrap-bottom-left">
            <div class="wrap-bottom-right">
                <div class="wrap-bottom-middle"></div>
            </div>
        </div>
        <!-- box CONTENT END -->
    </div>
@endsection