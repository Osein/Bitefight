@extends('index')

@section('content')
    <div id="market">
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
                <h2>{{user_race_logo_small()}}Market place</h2>
                <script type="text/javascript">
                    function calcDues(price,duration)
                    {
                        var factor = 0.0;
                        var price = parseInt(price);
                        if(isNaN(price)) {
                            price = 0;
                        }
                        if(price < 0) {
                            price = 0;
                        }
                        switch(duration)
                        {
                            case '1':	factor = 0.02;
                                break;
                            case '2': factor = 0.03;
                                break;
                            case '3': factor = 0.04;
                                break;
                        }
                        return Math.ceil(price * factor);
                    }
                    $(function () {
                        $(".price").keyup(function(){
                            var pos = $(".price").index(this);
                            $(".gebuehren").eq(pos).html(calcDues($(this).val(),$(".dauer").eq(pos).val()));
                        });
                        $(".dauer").change(function(){
                            var pos = $(".dauer").index(this);
                            $(".gebuehren").eq(pos).html(calcDues($(".price").eq(pos).val(),$(this).val()));
                        });
                    });
                </script>
                <div class="buildingDesc clearfix">
                    <img class="npc-logo" src="{{asset('img/city/npc/0_5.jpg')}}" align="left">
                    <p>You can buy or sell almost anything at the market. Merchants rarely ask after the origin or a reason for buying the goods. If you look long enough, there`s a possibility you might find forbidden or occult items. Quite a few creatures of the night use the market of the mortals to receive or sell equipment.</p>
                    <p class="gold">Gold: 491.265 <img src="/img/symbols/res2.gif" alt="Gold" align="absmiddle" border="0"></p>
                </div>
                <div>
                    <h2>{{user_race_logo_small()}}<a href="/city/market/duration/asc/1">Market Overview</a> | <a href="/city/marketSell">Sell items</a></h2>
                    <table width="100%">
                        <tbody><tr>
                            <td colspan="2" class="tdh" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top" colspan="2">
                                <div id="market_filter">
                                    <form action="https://s30-en.bitefight.gameforge.com:443/city/market?__token=150c7ec3aeea9de0bfcf477b6010c153" method="post" name="filterForm">
                                        <input class="search" type="text" name="query" size="40" value="">
                                        <select class="auswahl" name="filter" size="1">
                                            <option value="1" selected="selected">Weapons</option>
                                            <option value="2">Potions</option>
                                            <option value="3">Helmets</option>
                                            <option value="4">Armour</option>
                                            <option value="5">Jewellery</option>
                                            <option value="6">Gloves</option>
                                            <option value="7">Boots</option>
                                            <option value="8">Shields</option>
                                            <option value="10">Orbs</option>
                                        </select>
                                        Level: <input class="inputblack" type="text" name="lvlFrom" size="3" value=""> - <input class="inputblack" type="text" name="lvlTo" size="3" value="">
                                        <div class="btn-left right">
                                            <div class="btn-right">
                                                <input type="submit" value="Filter" class="btn">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-bg" valign="top" colspan="2">
                                <div id="market_table" style="position:relative;">
                                    <div id="market_nav">No entries</div><table width="100%" cellspacing="2" cellpadding="2">

                                    </table>
                                    <br>
                                </div>
                                <div id="market_nav">
                                    Page 1 / 1		                </div>
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