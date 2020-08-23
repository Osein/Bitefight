@extends('index')

@section('content')
    <div id="taverne">
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
                <h2>{{user_race_logo_small()}}Tavern</h2>
                <div class="buildingDesc">
                    <img class="npc-logo" src="{{asset('img/city/npc/0_0.jpg')}}" align="left">

                    <h3>Welcome to the Drunken Knights` Tavern {{user()->getName()}}</h3>

                    <p>The tavern is comprised of a large and dark room in which, more often than not, too many people hang out in. An old cart wheel is fastened to the ceiling with chains and the candles on it envelop the room in an eerie glow. Normally you will find gamblers, pick pockets and throat slitters here, as well as the occasional rich citizen whose lust for adventure in the lowland has brought him into the tavern.</p>

                    <p style="padding:0 15px;">In the tavern you can accept quests and get information about accepted quests.</p>
                </div>
                <br class="clearfloat">
                <div id="newQuest">
                    <h2>{{user_race_logo_small()}}Accept Quest</h2>
                    <div>
                        <p class="gold">
                            Accept missions here:        </p>

                        <div class="button" style="width: 205px;">
                            <div class="buttonOverlay" title="" onmouseover="$(this).next().next('button').trigger('mouseover');" onclick="$(this).next().next('button').trigger('click');"></div>

                            <div class="btn-left button_float" style="margin: 0;"></div>
                            <button class="btn" type="submit" style="margin: 0; width: 179px;" onclick="document.location.href='{{url('/city/missions')}}'">
                                Missions    </button>
                            <div class="btn-right button_float"></div>
                            <div class="clearfloat"></div>
                        </div>
                    </div>
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