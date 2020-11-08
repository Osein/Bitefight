@extends('index')

@section('content')
	<script type="text/javascript" language="javascript">
		var tips = [];
		tips[1] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_taverne.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Tavern</td></tr></table></center>';
		tips[2] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_shadowbook.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>House of Pain</td></tr></table></center>';
		tips[3] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_market.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Market place</td></tr></table></center>';
		tips[4] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_library.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Library</td></tr></table></center>';
		tips[5] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_shop.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Merchant</td></tr></table></center>';
		tips[6] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_grotte.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Grotto</td></tr></table></center>';
		tips[7] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_voodoo.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Voodoo Shop</td></tr></table></center>';
		tips[8] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_graveyard.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Graveyard</td></tr></table></center>';
		tips[9] = '<center><table id="cityHoverLayer" cellspacing=2 cellpadding=2 border=0 valign=middle><tr><td align=center><img src="{{asset('img/city/symbol_church.gif')}}" alt="" border="0"></td><td align=left style=\'font-size: 20pt\'>Church</td></tr></table></center>';
		function show_light(ele)    {
			if (document.getElementById('light'+ele).style.display==='none'){
				document.getElementById('light'+ele).style.display='';
				document.getElementById('tipbox').innerHTML = tips[ele];
				document.getElementById('tipbox').style.display='';
			}
		}
		function hide_light(ele)    {
			if (document.getElementById('light'+ele).style.display===''){
				document.getElementById('light'+ele).style.display='none';
				document.getElementById('tipbox').innerHTML = '';
				document.getElementById('tipbox').style.display='none';
			}
		}
	</script>
	<div id="addBuddy">
		<div class="wrap-top-left clearfix">
			<div class="wrap-top-right clearfix">
				<div class="wrap-top-middle clearfix"></div>
			</div>
		</div>
		<div class="wrap-left clearfix">
			<div class="wrap-content wrap-right clearfix">
				<h2>{{user_race_logo_small()}}The city is filled with the scent of blood. Welcome, {{user()->getName()}}</h2>
				<p>You can buy items or weapons in the city which can improve your chances in fights with your enemies. You can also look for a job at the graveyard to earn some gold! Click on a building to enter.</p>
				<div style="width:700px; height:400px; margin:0 auto; position:relative;">
					<div style="z-index:100; position:absolute;top:0px;left:0px;">
						<img src="{{asset('img/city/city.jpg')}}" alt="" border="0">
					</div>
					<div style="z-index:101; position:absolute;top:0px;left:0px;">
						<img src="{{asset('img/city/1_over.gif')}}" alt="" border="0" id="light1" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_taverne.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Taverne</td></tr></table>')">
						<img src="{{asset('img/city/8_over.gif')}}" alt="" border="0" id="light8" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_graveyard.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Friedhof</td></tr></table>')">
						<img src="{{asset('img/city/3_over.gif')}}" alt="" border="0" id="light3" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_market.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Marktplatz</td></tr></table>')">
						<img src="{{asset('img/city/4_over.gif')}}" alt="" border="0" id="light4" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_library.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Bibliothek</td></tr></table>')">
						<img src="{{asset('img/city/5_over.gif')}}" alt="" border="0" id="light5" style="display: none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_shop.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Händler</td></tr></table>')">
						<img src="{{asset('img/city/6_over.gif')}}" alt="" border="0" id="light6" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_grotte.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Grotte</td></tr></table>')">
						<img src="{{asset('img/city/7_over.gif')}}" alt="" border="0" id="light7" style="display: none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_voodoo.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>VooDoo Shop</td></tr></table>')">
						<img src="{{asset('img/city/9_over.gif')}}" alt="" border="0" id="light9" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_church.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Kirche</td></tr></table>')">
						<img src="{{asset('img/city/2_over.gif')}}" alt="" border="0" id="light2" style="display:none;" onmouseover="return escape('<table width=250 cellspacing=0 cellpadding=0><tr><td align=right><img src=\'{{asset('img/city/symbol_shadowbook.gif')}}\' alt=\'\' border=\'0\'></td><td align=center style=\'font-size: 20pt\'>Haus des Schmerzes</td></tr></table>')">
					</div>
					<div style="z-index:102; position:absolute;top:0px;left:0px;">
						<img src="{{asset('img/city/0.gif')}}" alt="" border="0" usemap="#city_Map">
						<map name="city_Map">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="289,248,287,194,255,133,246,112,234,112,234,127,219,118,210,141,200,140,198,168,187,192,191,201,192,240,217,259,218,262,232,262" href="{{url('/city/taverne')}}" onmouseover="show_light('1');" onmouseout="hide_light('1');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="546,252,548,289,538,298,546,310,567,308,579,318,599,322,585,343,536,349,500,344,471,323,456,298,460,274,474,260,519,252" href="{{url('/city/graveyard')}}" onmouseover="show_light('8');" onmouseout="hide_light('8');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="354,331,302,292,228,281,140,295,119,305,99,320,128,373,109,391,110,399,314,399" href="{{url('/city/market')}}" onmouseover="show_light('3');" onmouseout="hide_light('3');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="184,253,187,206,159,155,121,147,118,127,93,130,92,142,80,165,91,287" href="{{url('/city/library')}}" onmouseover="show_light('4');" onmouseout="hide_light('4');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="375,236,375,209,393,207,391,183,346,123,322,107,314,85,312,121,278,173,290,197,290,226,309,244" href="{{url('/city/shop')}}" onmouseover="show_light('5');" onmouseout="hide_light('5');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="615,380,577,353,599,324,645,316,672,339,691,367,659,376" href="{{url('/city/grotto')}}" onmouseover="show_light('6');" onmouseout="hide_light('6');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="550,290,550,235,569,183,580,166,583,140,608,74,628,137,627,172,667,174,677,220,668,232,659,233,662,249,648,251,645,267,649,274,651,301,651,312,588,326,567,311,547,302" href="{{url('/city/church')}}" onmouseover="show_light('9');" onmouseout="hide_light('9');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="3,7,21,16,45,56,78,93,93,300,77,274,3,305" href="{{url('/voodoo')}}" onmouseover="show_light('7');" onmouseout="hide_light('7');">
							<area shape="poly" style="z-index:150;" alt="" title="" coords="422,242,421,168,432,122,454,112,470,118,479,130,512,136,534,161,535,203,530,242,475,258" href="{{url('/city/arena')}}" onmouseover="show_light('2');" onmouseout="hide_light('2');">
						</map>
					</div>
					<div id="tipbox" style="z-index: 103; position: absolute; top: 5px; left: 0px; height: 80px; width: 700px; vertical-align: middle; text-align: center; display: none;"></div>
				</div>
				<h2>{{user_race_logo_small()}}Links</h2>
				<div class="table-wrap">
					<table cellpadding="2" cellspacing="2" border="0" width="100%" align="center">
						<tbody>
							<tr>
								<td><img src="{{asset('img/city/symbol_shop.gif')}}" alt="" border="0"></td><td><a href="{{url('/city/shop')}}" target="_top">Merchant</a></td><td>Here you will find everything you need. Weapons, equipment and potions are readily available. Please be sure to have enough gold with you. The shop owner never gives credit or a discount.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_graveyard.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/graveyard')}}" target="_top">Graveyard</a></td><td>You can earn gold by working at the graveyard. You can decide how long you want to work. Your will be paid at the graveyard directly after your laborious shift. Additionally, you will be able to gain experience while you work.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_taverne.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/taverne')}}" target="_top">Tavern</a></td><td>This tavern is definitively the most nasty and shabby place in the whole city. In spite of this, or perhaps because of this, a creature of the Night often finds his way into the society of mortals here. The publican is, according to some rumours, an insider of the Shadows and sometimes has the occasional unusual quest to assign to some of his more unusual guests.</td>
							</tr>
							<tr>
							<td><img src="{{asset('img/city/symbol_grotte.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/grotto')}}" target="_top">Grotto</a></td><td>This is the entrance to a deep and mysterious grotto. A multitude of rumours surround this grotto and those who go in don`t often come out again to tell their tales. Legend has it that the gate itself is the entrance to Hell.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_market.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/market')}}" target="_top">Market place</a></td><td>At the market there is always something going on. Traders sell their goods and try to earn some gold.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_library.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/library')}}" target="_top">Library</a></td><td>These talented forgers can change your name. Keep in mind that it will cost money because the artist will want to be paid for his work.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_church.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/church')}}" target="_top">Church</a></td><td>In these Holy halls you can pray for Godly assistance and heal your wounds with a little meditation.</td>
							</tr>
							<tr>
							<td><img src="{{asset('img/city/symbol_shadowbook.gif')}}" alt="" border="0"> </td><td><a href="{{url('/city/arena')}}" target="_top">House of Pain</a></td><td>He who registers in the Book of Shadows has no fear of pain. These creatures of the night will not shy away from any battle, regardless of level or race.</td>
							</tr>
							<tr>
								<td><img src="{{asset('img/city/symbol_voodoo.gif')}}" alt="" border="0"> </td><td><a href="{{url('/voodoo')}}" target="_top">Voodoo Shop</a></td><td>The Voodoo Shop exudes a strange magic energy. Rumour has it that hellstones can only be bought here.</td>
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