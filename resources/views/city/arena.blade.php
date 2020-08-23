@extends('index')

@section('content')
    <div id="arena">
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
                <h2><img src="/img/symbols/race1small.gif" alt="">House of Pain</h2>
                <div class="buildingDesc">
                    <img class="npc-logo" src="{{asset('img/city/npc/0_10.jpg')}}" align="left">
                    <h3>Welcome to the House of Pain, Osein</h3>
                    <p>
                        So you`re here to register in the Book of Shadows? Well I`ll explain the rules to you first:<br>
                        Rule 1: Do not talk about the Book of Shadows.<br>
                        Rule 2: DO NOT talk about the Book of Shadows.<br>
                        Rule 3: Every person who registers here can attack every other registered creature.<br>
                        Rule 4: You can only register in the Book of Shadows from level 15.<br>
                        Rule 5: At the end of the month you have to leave the house, but you can come back again in the next month.<br>
                        Rule 6: If you achieve the most victories in a month, you`ll receive a big reward.<br><br>
                    </p>
                </div>
                <p style="color:#f00;">If you enter your name in the Book of Shadows you can fight against every player who is also registered in the book - regardless of which race or level he or she has. If you randomly search for an opponent, you cannot decide to change your opponent at last minute, as the fight will start directly. You can only de-register from the book again after 24 hours.</p>
                <br class="clearfloat">

                <h2><img alt="" src="/img/symbols/race1small.gif">The Book of Shadows</h2>
                <div class="table-wrap">
                    <table cellpadding="2" cellspacing="2" border="0" width="100%">
                        <tbody><tr>
                            <td class="no-bg" colspan="2" align="center">
                                <p>You can only search for opponents once you have registered yourself in the Book of Shadows.</p>
                                <form action="https://s30-en.bitefight.gameforge.com:443/city/arena/?__token=150c7ec3aeea9de0bfcf477b6010c153" method="POST">
                                    <div class="btn-left left">
                                        <div class="btn-right">
                                            <input type="submit" class="btn" name="join" value="register">
                                        </div>
                                    </div>
                                </form>
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