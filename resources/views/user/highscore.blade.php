@extends('index')

@section('content')
    <div id="highscore">
        <div class="wrap-top-left clearfix">
            <div class="wrap-top-right clearfix">
                <div class="wrap-top-middle clearfix"></div>
            </div>
        </div>
        <div class="wrap-left clearfix">
            <div class="wrap-content wrap-right clearfix">
                <h2>{{user_race_logo_small()}} Highscore</h2>
                <div class="table-wrap">
                    @if(user() && ($type == 'player' || $type == 'clan' && user()->getClanId() > 0))
                    <form action="{{$myPosLink}}" method="POST">
                        {{csrf_field()}}
                        <div class="btn-left left">
                            <div class="btn-right">
                                <input class="btn" type="submit" name="mypos" value="{{__('home.highscore_my_position')}}">
                            </div>
                        </div>
                    </form>
                    @endif
                    <script language="JavaScript">
                        $(document).ready(function() {
                            $('.JScheckbox').on('click', function() {
                                if($('.JScheckbox:checked').length >= 5) {
                                    $('.JScheckbox:not(:checked)').attr('disabled', true);
                                } else {
                                    $('.JScheckbox:not(:checked)').attr('disabled', false);
                                }
                            });

                            if($('.JScheckbox:checked').length == 5) {
                                $('.JScheckbox:not(:checked)').attr('disabled', true);
                            }

                            $('#selectClan').on('change', function() {
                                window.location.href = '{{url('highscore?type=')}}'+$(this).val();
                            });
                        });
                    </script>
                    <form method="GET">
                        <p></p>
                        <table cellpadding="2" cellspacing="2" border="0" width="100%" id="navigationTable">
                            <tbody>
                            <tr>
                                <td class="no-bg" colspan="2">
                                    <select name="type" id="selectClan" size="1">
                                        <option value="player" @if($type == 'player') selected @endif>{{__('home.highscore_player_highscore')}}</option>
                                        <option value="clan" @if($type == 'clan') selected @endif>{{__('home.highscore_clan_highscore')}}</option>
                                    </select>
                                    <select name="race" size="1">
                                        <option value="0" @if($race == 0) selected @endif>{{__('general.all_races')}}</option>
                                        <option value="1" @if($race == 1) selected @endif>{{__('general.vampires')}}</option>
                                        <option value="2" @if($race == 2) selected @endif>{{__('general.werewolves')}}</option>
                                    </select>
                                </td>
                                <td class="no-bg" colspan="2" style="line-height:45px;">
                                    <div class="btn-left left">
                                        <div class="btn-right">
                                            <input class="btn" type="submit" value="{{__('general.show')}}">
                                        </div>
                                    </div>&nbsp;{{__('home.highscore_show_limit')}}
                                </td>
                            </tr>
                            @if($type == 'player')
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="level" @if(in_array('level', $show)) checked @endif >{{__('general.level')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="raid" @if(in_array('raid', $show)) checked @endif >{{__('general.booty')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fightvalue" @if(in_array('fightvalue', $show)) checked @endif >{{__('general.battle_value')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fights" @if(in_array('fights', $show)) checked @endif >{{__('general.fights')}}</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight1" @if(in_array('fight1', $show)) checked @endif >{{__('general.victories')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight2" @if(in_array('fight2', $show)) checked @endif >{{__('general.defeats')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight0" @if(in_array('fight0', $show)) checked @endif >{{__('general.draw')}}</td>
                            <!--<td><input type="checkbox" class="JScheckbox" name="show[]" value="lanter" @if(in_array('lanter', $show)) checked @endif >{{__('general.lanterns')}}</td>-->
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="goldwin" @if(in_array('goldwin', $show)) checked @endif >{{__('general.looted_gold')}}</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="goldlost" @if(in_array('goldlost', $show)) checked @endif >{{__('general.lost_gold')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="hits1" @if(in_array('hits1', $show)) checked @endif >{{__('general.damage_caused')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="hits2" @if(in_array('hits2', $show)) checked @endif >{{__('general.hit_points_lost')}}</td>
                            </tr>
                            <!--
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="trophypoints" @if(in_array('trophypoints', $show)) checked @endif >{{__('general.trophy_points')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="henchmanlevels" @if(in_array('henchmanlevels', $show)) checked @endif >{{__('general.henchmen_power')}}</td>
                            </tr>
                            -->
                            @else
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="castle" @if(in_array('castle', $show)) checked @endif >{{__('general.level')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="raid" @if(in_array('raid', $show)) checked @endif >{{__('general.booty')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="warraid" @if(in_array('warraid', $show)) checked @endif >{{__('general.war_booty')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="members" @if(in_array('members', $show)) checked @endif >{{__('general.members')}}</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fights" @if(in_array('fights', $show)) checked @endif >{{__('general.fights')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight0" @if(in_array('fight0', $show)) checked @endif >{{__('general.draw')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight1" @if(in_array('fight1', $show)) checked @endif >{{__('general.victories')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="fight2" @if(in_array('fight2', $show)) checked @endif >{{__('general.defeats')}}</td>
                            </tr>
                            <!--
                            <tr>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="ppm" @if(in_array('ppm', $show)) checked @endif >{{__('general.average_booty')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="seals" @if(in_array('seals', $show)) checked @endif >{{__('general.seals')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="gatesopen" @if(in_array('gatesopen', $show)) checked @endif >{{__('general.gate_openings')}}</td>
                                <td><input type="checkbox" class="JScheckbox" name="show[]" value="lastgateopen" @if(in_array('lastgateopen', $show)) checked @endif >{{__('general.last_gate_opening')}}</td>
                            </tr>
                            -->
                            @endif
                            </tbody>
                        </table>
                        <p></p>
                    </form>
                    {{$results->links()}}
                    <p></p>
                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td align="center"> # </td>
                            <td align="center">{{__('general.race')}}</td>
                            <td align="center">{{__('general.name')}}</td>
                            @foreach($show as $s)
                            <td align="center">
                                <a href="{{urlGetParams($showHeadLink, ['order' => $s])}}">{{$s}}</a>
                            </td>
                            @endforeach
                        </tr>
                        @foreach($results as $result)
                        <tr @if(user() && $type == 'player' && user()->getId() == $result->id || $type == 'clan' && $result->id == user()->getClanId()) class="own" @endif>
                            <td align="right">{{$startRank}}</td>
                            <td><img src="{{asset('img/symbols/race'.$result->race.'small.gif')}}" alt="@if($result->race == 1) {{__('general.vampire')}} @else {{__('general.werewolf')}} @endif"></td>
                            <td><a href="{{$type == 'clan' ? url('/preview/clan/'.$result->id) : url('/preview/user/'.$result->id)}}">{{$result->name}}</a></td>
                            @foreach($show as $s)
                            <td align="right">{{ (int)$result->{$s} }}</td>
                            @endforeach
                        </tr>
                        @php($startRank++)
                        @endforeach
                        </tbody>
                    </table>
                    <p></p>
                    {{$results->links()}}
                    <br>
                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td class="no-bg" align="center" width="50%">
                                <img src="{{asset('img/symbols/race1.gif')}}" alt="{{__('general.vampires')}}">
                                <br><br>
                                {{prettyNumber($vampireCount)}} {{__('general.vampires')}}<br>
                                {{__('general.booty')}}: {{prettyNumber($vampireBlood)}} {{__('general.blood')}}<br>
                                {{__('general.battles')}}: {{prettyNumber($vampireBattle)}}<br>
                                {{__('general.gold')}}: {{prettyNumber($vampireGold)}} {{gold_image_tag()}}<br>
                            </td>
                            <td class="no-bg" align="center" width="50%">
                                <img src="{{asset('img/symbols/race2.gif')}}" alt="{{__('general.werewolves')}}">
                                <br><br>
                                {{prettyNumber($werewolfCount)}} {{__('general.werewolves')}}<br>
                                {{__('general.booty')}}: {{prettyNumber($werewolfBlood)}} {{__('general.meat')}}<br>
                                {{__('general.battles')}}: {{prettyNumber($werewolfBattle)}}<br>
                                {{__('general.gold')}}: {{prettyNumber($werewolfGold)}} {{gold_image_tag()}}<br>
                            </td>
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
@endsection