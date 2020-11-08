@extends('index')

@section('content')
    <div id="grotte">
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
                <h2>{{user_race_logo_small()}}Grotto</h2>
                <div class="buildingDesc clearfix">
                    <img class="npc-logo" src="{{asset('img/city/npc/0_4.jpg')}}" align="left">
                    <h3>The entrance to the underworld is calling you,  Osein</h3>
                    <p>The entrance to the grotto gapes like a wound in the earth. Ailing thorn bushes surround the entrance and the wind whistles an unholy melody. Invisible eyes appear to be staring out at you from the black hole and you feel a hate-filled hunger spill out of the opening.</p>
                </div>
                <h2><img alt="" src="/img/symbols/race1small.gif">Demon hunt ( 1 <img src="https://s30-en.bitefight.gameforge.com:443/img/symbols/ap.gif" title="Action point" style="float: none; vertical-align: middle; margin: 0;"> )</h2>
                <div class="clearfix">
                    <div class="clearfix">
                        <table class="noBackground" width="100%" border="0">
                            <tbody><tr>
                                <td>
                                    <form action="https://s30-en.bitefight.gameforge.com:443/city/grotte/?__token=150c7ec3aeea9de0bfcf477b6010c153" class="clearfix" method="POST">
                                        <div class="clearfix" style="line-height:60px;">
                                            <input type="submit" class="btn-small left" name="difficulty" value="Easy">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form action="https://s30-en.bitefight.gameforge.com:443/city/grotte/?__token=150c7ec3aeea9de0bfcf477b6010c153" class="clearfix" method="POST">
                                        <div class="clearfix" style="line-height:60px;">
                                            <input type="submit" class="btn-small left" name="difficulty" value="Medium">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form action="https://s30-en.bitefight.gameforge.com:443/city/grotte/?__token=150c7ec3aeea9de0bfcf477b6010c153" class="clearfix" method="POST">
                                        <div class="clearfix" style="line-height:60px;">
                                            <input type="submit" class="btn-small left" name="difficulty" value="Difficult">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- CONTENT END -->
        </div>
        <div class="wrap-bottom-left">
            <div class="wrap-bottom-right">
                <div class="wrap-bottom-middle"></div>
            </div>
        </div>
    </div>
@endsection