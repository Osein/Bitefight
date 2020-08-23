@extends('index')

@section('content')
    <!-- box-model START -->
    <div id="shop">
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
                <h2>{{user_race_logo_small()}}The merchant</h2>
                <div class="buildingDesc">
                    <img class="npc-logo" src="{{asset('img/city/npc/0_1.jpg')}}" align="left" />
                    <h3>The merchant welcomes you {{user()->getName()}}</h3>
                    <p>Welcome to my modest shop. How can I serve you? I can offer you some good weapons and items that can improve your abilities! Please make your selection...</p>
                    <p>You have {{$user_item_available_slot}} free spaces in your inventory (and {{$user_item_max_count}} inventory spaces in total).</p>
                </div>

                <div style="clear: both;width:100%;">
                    <h2>{{user_race_logo_small()}}Which goods are you interested in?</h2>
                    <div id="shopMenu">
                        <a href="{{url('/city/shop?model=weapons')}}">Weapons</a> |
                        <a href="{{url('/city/shop?model=potions')}}">Potions</a> |
                        <a href="{{url('/city/shop?model=helmets')}}">Helmets</a> |
                        <a href="{{url('/city/shop?model=armour')}}">Armour</a> |
                        <a href="{{url('/city/shop?model=jewellery')}}">Jewellery</a> |
                        <a href="{{url('/city/shop?model=gloves')}}">Gloves</a> |
                        <a href="{{url('/city/shop?model=boots')}}">Boots</a> |
                        <a href="{{url('/city/shop?model=shields')}}">Shields</a>
                    </div>
                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                        <tr>
                            <td>
                                <form method="get" name="filterForm">
                                    @if($iModel != 'weapons')
                                        <input type="hidden" name="model" value="{{$iModel}}">
                                    @endif
                                    <div style="float:left;">
                                        Level: <input class="inputblack" type="text" name="lvlfrom" size="3" value="{{$iLevelFrom}}"> - <input class="inputblack" type="text" name="lvlto" size="3" value="{{$iLevelTo}}">
                                        <select name="premiumfilter">
                                            <option value="all" @if($iPFilter == 'all') selected @endif >Show all items</option>
                                            <option value="premium" @if($iPFilter == 'premium') selected @endif >Only show premium items</option>
                                            <option value="nonpremium" @if($iPFilter == 'nonpremium') selected @endif >Only show normal items</option>
                                        </select>
                                    </div>
                                    <div class="btn-left left"><div class="btn-right"><input type="submit" value="Filter"  class="btn"></div></div>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="tdh" align="center">Overview</td>
                        </tr>
                    </table>
                    {{ $items->links() }}
                    <br>
                    <table id="shopOverview" cellpadding="2" cellspacing="2" border="0" width="100%" style="margin-top:-2px;padding-top:0px;">
                        @foreach($items as $i)
                        <tr>
                            <td class='no-bg' style="text-align:center;">
                                <div style="position:relative;width:300px;">
                                    <img src="{{asset('img/items/'.$i->model.'/'.$i->id.'.jpg')}}" @if($i->scost > 0) style="border: 1px solid #6f86a9;" @endif alt="{{$i->name}}">
                                    <div style="position:absolute;right:90px;top:15px;z-index:9999">
                                    </div>
                                </div>
                            </td>
                            <td class='tdn'>
                                {{printItemDetails($i, true)}}
                            </td>
                            <td class="tdn" style="width:150px;" align="center">
                                @if($i->gcost <= user()->getGold() && $i->scost <= user()->getHellstone() && $user_item_available_slot > 0)
                                <div class="btn-left left">
                                    <div class="btn-right">
                                        <form method="post" action="{{url('/city/shop/item/buy/'.$i->id)}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="model" value="{{$iModel}}">
                                            <input type="hidden" name="page" value="{{$iPage}}">
                                            <input type="hidden" name="lvlfrom" value="{{$iLevelFrom}}">
                                            <input type="hidden" name="lvlto" value="{{$iLevelTo}}">
                                            <input type="hidden" name="premiumfilter" value="{{$iPFilter}}">
                                            <input type="hidden" name="volume" value="1">
                                            @if($i->model == 2)
                                            <button class="btn">1x buy now</button>
                                            @else
                                            <button class="btn">buy now</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                                @if($i->gcost * 5 <= user()->getGold() && $i->scost * 5 <= user()->getHellstone() &&
                                    5 <= $user_item_available_slot && $i->model == 2)
                                <br/>
                                <div class="btn-left left">
                                    <div class="btn-right">
                                        <form method="post" action="{{url('/city/shop/item/buy/'.$i->id)}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="model" value="{{$iModel}}">
                                            <input type="hidden" name="page" value="{{$iPage}}">
                                            <input type="hidden" name="lvlfrom" value="{{$iLevelFrom}}">
                                            <input type="hidden" name="lvlto" value="{{$iLevelTo}}">
                                            <input type="hidden" name="premiumfilter" value="{{$iPFilter}}">
                                            <input type="hidden" name="volume" value="5">
                                            <button class="btn">5x buy now</button>
                                        </form>
                                    </div>
                                </div>

                                @if($i->gcost * 10 <= user()->getGold() && $i->scost * 10 <= user()->getHellstone() &&
                                10 <= $user_item_available_slot && $i->model == 2)

                                <br/>
                                <div class="btn-left left">
                                    <div class="btn-right">
                                        <form method="post" action="{{url('/city/shop/item/buy/'.$i->id)}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="model" value="{{$iModel}}">
                                            <input type="hidden" name="page" value="{{$iPage}}">
                                            <input type="hidden" name="lvlfrom" value="{{$iLevelFrom}}">
                                            <input type="hidden" name="lvlto" value="{{$iLevelTo}}">
                                            <input type="hidden" name="premiumfilter" value="{{$iPFilter}}">
                                            <input type="hidden" name="volume" value="10">
                                            <button class="btn">10x buy now</button>
                                        </form>
                                    </div>
                                </div>

                                @endif
                                @endif
                                @elseif(!$i->volume)
                                ---
                                @endif

                                @if($i->volume)
                                <div id="usescreen_1_{{$i->id}}">
                                    <div class="btn-left left">
                                        <div class="btn-right">
                                            <button class="btn" onclick="confirmUserPremium('1_{{$i->id}}')">Sell</button>
                                        </div>
                                    </div>
                                </div>


                                <div style="clear:both;">
                                    <div id="confirmscreen_1_{{$i->id}}" style="display:none;">
                                        <table class="noBackground">
                                            <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <span style="color:#FFCC33;">Do you really want to sell?</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%">
                                                    <div class="btn-left left">
                                                        <div class="btn-right">
                                                            <form method="post" action="{{url('/city/shop/item/sell/'.$i->id)}}">
                                                                {{csrf_field()}}
                                                                <input type="hidden" name="model" value="{{$iModel}}">
                                                                <input type="hidden" name="page" value="{{$iPage}}">
                                                                <input type="hidden" name="lvlfrom" value="{{$iLevelFrom}}">
                                                                <input type="hidden" name="lvlto" value="{{$iLevelTo}}">
                                                                <input type="hidden" name="premiumfilter" value="{{$iPFilter}}">
                                                                <button class="btn">Yes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="50%">
                                                    <div class="btn-left left">
                                                        <div class="btn-right">
                                                            <button class="btn" onclick="notConfirm('1_{{$i->id}}')">No</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <script>
                            function confirmUserPremium(nr)
                            {
                                $('#usescreen_' + nr).css('display', 'none');
                                $('#confirmscreen_' + nr).css('display', 'block');
                            }

                            function notConfirm(nr)
                            {
                                $('#usescreen_' + nr).css('display', '');
                                $('#confirmscreen_' + nr).css('display', 'none');
                            }
                        </script>
                    </table>

                    {{ $items->links() }}
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