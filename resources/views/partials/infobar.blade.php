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
				{{prettyNumber(user()->getGold())}}&nbsp;{{gold_image_tag()}}&nbsp;&nbsp;
				{{prettyNumber(user()->getHellstone())}}&nbsp;{{hellstone_image_tag()}}&nbsp;&nbsp;
				{{prettyNumber(user()->getFragment())}}&nbsp;{{fragment_image_tag()}}&nbsp;&nbsp;
				{{prettyNumber(floor(user()->getApNow()))}}&nbsp;/&nbsp;{{prettyNumber(user()->getApMax())}}&nbsp;{{action_point_image_tag()}}&nbsp;&nbsp;
				{{prettyNumber(floor(user()->getHpNow()))}}&nbsp;/&nbsp;{{prettyNumber(user()->getHpMax())}}&nbsp;{{health_image_tag()}}&nbsp;&nbsp;
				{{level_image_tag()}}&nbsp;{{prettyNumber(getLevel(user()->getExp()))}}&nbsp;&nbsp;
				{{battle_value_image_tag()}}&nbsp;{{prettyNumber(user()->getBattleValue())}}
			</div>
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