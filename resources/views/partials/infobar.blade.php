@if(\Illuminate\Support\Facades\Auth::check())
<div id="infobar">
	<div class="wrap-top-left clearfix">
		<div class="wrap-top-right clearfix">
			<div class="wrap-top-middle clearfix"></div>
		</div>
	</div>
	<div class="wrap-left clearfix">
		<div class="wrap-content wrap-right clearfix">
			<div class="gold" style="margin-bottom:0px;">
				{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getGold())}}&nbsp;<img src="{{asset('img/symbols/res2.gif')}}" alt="{{__('general.menu_infobar_gold')}}" align="absmiddle" border="0">&nbsp;&nbsp;
				{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getHellstone())}}&nbsp;<img src="{{asset('img/symbols/res3.gif')}}" alt="{{__('general.menu_infobar_hellstone')}}" align="absmiddle" border="0">&nbsp;&nbsp;
				{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getFragment())}}&nbsp;<img src="{{asset('img/symbols/res_splinters.png')}}" alt="{{__('general.menu_infobar_fragments')}}" align="absmiddle" border="0">&nbsp;&nbsp;
				{{prettyNumber(floor(\Illuminate\Support\Facades\Auth::user()->getApNow()))}}&nbsp;/&nbsp;{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getApMax())}}&nbsp;<img src="{{asset('img/symbols/ap.gif')}}" alt="{{__('general.menu_infobar_action_points')}}" align="absmiddle" border="0">&nbsp;&nbsp;
				{{prettyNumber(floor(\Illuminate\Support\Facades\Auth::user()->getHpNow()))}}&nbsp;/&nbsp;{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getHpMax())}}&nbsp;<img src="{{asset('img/symbols/herz.png')}}" alt="{{__('general.menu_infobar_health')}}" align="absmiddle" border="0">&nbsp;&nbsp;
				<img src="{{asset('img/symbols/level.gif')}}" alt="{{__('general.menu_infobar_level')}}" align="absmiddle" border="0">&nbsp;{{prettyNumber(\Database\Models\User::getLevel(\Illuminate\Support\Facades\Auth::user()->getExp()))}}&nbsp;&nbsp;
				<img src="{{asset('img/symbols/fightvalue.gif')}}" alt="{{__('general.menu_infobar_battle_value')}}" align="absmiddle" border="0">&nbsp;{{prettyNumber(\Illuminate\Support\Facades\Auth::user()->getBattleValue())}}</div>
		</div>
	</div>
	<div class="wrap-bottom-left">
		<div class="wrap-bottom-right">
			<div class="wrap-bottom-middle"></div>
		</div>
	</div>
</div>
<br/>
@endif