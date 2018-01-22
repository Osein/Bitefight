<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 9:35 PM
 */

if (! function_exists('user_race_logo_small')) {
	/**
	 * @return \Illuminate\Support\HtmlString
	 */
	function user_race_logo_small()
	{
		$race = user() ? user()->getRace() : env('DEFAULT_SERVER_RACE');

		return new \Illuminate\Support\HtmlString(
				'<img src="'.
				asset('img/symbols/race'.$race.'small.gif').'" alt="'.
				($race == 1 ? __('general.vampire') : __('general.werewolf')).'" />');
	}
}

function gold_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/res2.gif').'"  alt="'.__('general.gold').'" align="absmiddle" border="0" />');
}

function hellstone_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/res3.gif').'"  alt="'.__('general.hellstone').'" align="absmiddle" border="0" />');
}

function fragment_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/res_splinters.png').'"  alt="'.__('general.fragment').'" align="absmiddle" border="0" />');
}

function action_point_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/ap.gif').'"  alt="'.__('general.menu_infobar_action_points').'" align="absmiddle" border="0" />');
}

function health_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/herz.png').'"  alt="'.__('general.menu_infobar_health').'" align="absmiddle" border="0" />');
}

function level_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/level.gif').'"  alt="'.__('general.menu_infobar_level').'" align="absmiddle" border="0" />');
}

function battle_value_image_tag() {
	return new \Illuminate\Support\HtmlString('<img src="'.asset('img/symbols/fightvalue.gif').'"  alt="'.__('general.menu_infobar_battle_value').'" align="absmiddle" border="0" />');
}

if(! function_exists('user')) {
	/**
	 * @return \Database\Models\User|null
	 */
	function user() {
		return \Illuminate\Support\Facades\Auth::user();
	}
}

/**
 * @param int $number
 * @return string
 */
function prettyNumber($number) {
	return number_format($number, 0, ',', '.');
}

function getItemModelFromModelNo($modelNo) {
	$modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');

	return $modelArray[$modelNo-1];
}

function getSkillCost($skillLevel) {
	return floor(pow($skillLevel - 4, 2.4));
}

function plusSignedNumberString($number) {
	return sprintf("%+d",$number);
}

function printProfileItemRow($i) {
	?>
	<tr>
		<?php printItemImageTd($i); ?>

		<td class='<?php if($i->equipped) echo 'active'; else echo 'tdn'; ?>'>
			<?php printItemDetails($i); ?>

			<?php if($i->cooldown > 0 && $i->expire > time()): ?>
				Cooldown time <span id="item_cooldown2_<?php echo $i->id; ?>" ></span><br/>
				<script type="text/javascript">
					$(function () {
						$("#item_cooldown2_<?php echo $i->id; ?>").countdown({
							until: +<?php echo $i->expire - time(); ?>,
							compact: true,
							compactLabels: ['y', 'm', 'w', 'd'],
							description: '',
							onExpiry: redirectUser
						});
					});
					function redirectUser() {
						setTimeout('window.location = "<?php echo url('profile/index') ?>"',3000);
					}
				</script>
			<?php elseif(!$i->equipped && $i->expire <= time()): ?>
				<div class="btn-left left">
					<div class="btn-right">
						<form method="post" action="<?php echo url('user/profile/item/equip/'.$i->id); ?>">
							<?php echo csrf_field(); ?>
							<button class="btn">Use this item</button>
						</form>
					</div>
				</div>
			<br/>
			<?php endif; ?>
		</td>
	</tr>
	<?php
}

function printItemDetails($i, $shop = false) {
	if($i->duration > 0)
	{
		$durationString = '';
		$dur = $i->duration;
		if($dur/3600 < 10) $durationString .= '0' . $dur/3600 . ':'; else $durationString .= $dur/3600 . ':';
		$dur = $dur %3600;
		if($dur/60 < 10) $durationString .= '0' . $dur/60 . ':'; else $durationString .= $dur/60 . ':';
		$dur = $dur%60;
		if($dur < 10) $durationString .= '0' . $dur; else $durationString .= $dur;
	}

	if($i->cooldown > 0) {
		$cooldownString = '';
		$cd = $i->cooldown;
		if($cd/3600 < 10) $cooldownString .= '0' . $cd/3600 . ':'; else $cooldownString .= $cd/3600 . ':';
		$cd = $cd %3600;
		if($cd/60 < 10) $cooldownString .= '0' . $cd/60 . ':'; else $cooldownString .= $cd/60 . ':';
		$cd = $cd%60;
		if($cd < 10) $cooldownString .= '0' . $cd; else $cooldownString .= $cd;
	}
	?>

	<strong><?php echo $i->name; ?> </strong><br>
	(Your inventory: <?php echo intval($i->volume); ?> item(s))<br><br>
	Resale value: <?php echo prettyNumber($i->slcost); ?><?php echo gold_image_tag() ?><br><br>
	<?php if($i->str != 0): ?>        Strenght: <?php echo plusSignedNumberString($i->str); ?><br> <?php endif; ?>
	<?php if($i->def != 0): ?>        Defence: <?php echo plusSignedNumberString($i->def); ?><br> <?php endif; ?>
	<?php if($i->dex != 0): ?>        Dexterity: <?php echo plusSignedNumberString($i->dex); ?><br> <?php endif; ?>
	<?php if($i->end != 0): ?>        Endurance: <?php echo plusSignedNumberString($i->end); ?><br> <?php endif; ?>
	<?php if($i->cha != 0): ?>        Charisma: <?php echo plusSignedNumberString($i->cha); ?><br> <?php endif; ?>
	<?php if($i->hpbonus != 0): ?>    Vitality: <?php if($i->hpbonus > 0) echo '+'; echo prettyNumber($i->hpbonus) ?><br> <?php endif; ?>
	<?php if($i->regen != 0): ?>      Regeneration: <?php if($i->regen > 0) echo '+'; echo prettyNumber($i->regen) ?><br> <?php endif; ?>
	<?php if($i->sbschc > 0): ?>      Basic hit chance: <?php echo plusSignedNumberString($i->sbschc); ?><br> <?php endif; ?>
	<?php if($i->sbscdmg > 0): ?>     Basic damage: <?php echo plusSignedNumberString($i->sbscdmg); ?><br> <?php endif; ?>
	<?php if($i->sbsctlnt > 0): ?>    Basic talent: <?php echo plusSignedNumberString($i->sbsctlnt); ?><br> <?php endif; ?>
	<?php if($i->sbnshc > 0): ?>      Bonus hit chance: <?php echo plusSignedNumberString($i->sbnshc); ?><br> <?php endif; ?>
	<?php if($i->sbnsdmg > 0): ?>     Bonus damage: <?php echo plusSignedNumberString($i->sbnsdmg); ?><br>   <?php endif; ?>
	<?php if($i->sbnstlnt > 0): ?>    Bonus talent: <?php echo plusSignedNumberString($i->sbnstlnt); ?><br> <?php endif; ?>
	<?php if($i->ebschc < 0): ?>      Basic hit chance (on opponent): <?php echo $i->ebschc; ?><br> <?php endif; ?>
	<?php if($i->ebscdmg < 0): ?>     Basic damage (on opponent): <?php echo $i->ebscdmg; ?><br> <?php endif; ?>
	<?php if($i->ebsctlnt < 0): ?>    Basic talent (on opponent): <?php echo $i->ebsctlnt; ?><br> <?php endif; ?>
	<?php if($i->ebnshc < 0): ?>      Bonus hit chance (on opponent): <?php echo $i->ebnshc; ?><br> <?php endif; ?>
	<?php if($i->ebnsdmg < 0): ?>     Bonus damage (on opponent): <?php echo $i->ebnsdmg; ?><br> <?php endif; ?>
	<?php if($i->ebnstlnt < 0): ?>    Bonus talent (on opponent): <?php echo $i->ebnstlnt; ?><br> <?php endif; ?>
	<?php if($i->apup > 0): ?>        Energy: <?php echo $i->apup; ?><br> <?php endif; ?>
	<?php if($i->id == 156): ?>       Man hunt: gold and booty x2<br> <?php endif; ?>
	<?php if($i->duration > 0): ?>    Duration of effect: <?php echo $durationString; ?><br> <?php endif; ?>
	<?php if($i->cooldown > 0): ?>    Cooldown time: <?php echo $cooldownString; ?><br> <?php endif; ?>
	<br>
	<?php if($shop): ?>
		sale price: <?php echo prettyNumber($i->gcost); ?><?php echo gold_image_tag() ?>
		<?php if($i->scost > 0): ?>+ <?php echo prettyNumber($i->scost); ?>&nbsp;<?php echo hellstone_image_tag() ?><?php endif; ?><br>
		Resale value: <?php echo prettyNumber($i->slcost); ?><?php echo gold_image_tag() ?><br>
	<?php endif; ?>
	Requirement: level <?php echo prettyNumber($i->level); ?><br>
	<?php
}

function printItemImageTd($i) {
	?>
	<td class='<?php if($i->equipped) echo 'active'; else echo 'inactive'; ?> itemslot' style="text-align:center;">
		<div style="position:relative;width:300px;">
			<img src="<?php echo asset('img/items/'.$i->model.'/'.$i->id.'.jpg') ?>" <?php if($i->scost > 0) echo 'style="border: 1px solid #6f86a9;"'; ?> alt="<?php echo $i->name ?>">
			<div style="position: absolute; right: 20px; top: 15px; z-index: 9999;">
				<?php for($y = 0; $y < $i->stern; $y++): ?>
					<img src="<?php echo asset('img/symbols/stern.png'); ?>" style="border: 0 none;">
				<?php endfor; ?>
			</div>
		</div>
	</td>
	<?php
}

function getTalentPropertyArray($obj) {
	$properties = array();

	if($obj->str != 0) {
		$properties[] = array('Strength', $obj->str);
	}
	if($obj->def != 0) {
		$properties[] = array('Defence', $obj->def);
	}
	if($obj->dex != 0) {
		$properties[] = array('Dexterity', $obj->dex);
	}
	if($obj->end != 0) {
		$properties[] = array('Endurance', $obj->end);
	}
	if($obj->cha != 0) {
		$properties[] = array('Charisma', $obj->cha);
	}
	if($obj->hpbonus != 0) {
		$properties[] = array('Health', $obj->hpbonus);
	}
	if($obj->regen != 0) {
		$properties[] = array('Regeneration', $obj->regen);
	}
	if($obj->sbschc != 0) {
		$properties[] = array('Basic hit chance', $obj->sbschc);
	}
	if($obj->sbscdmg != 0) {
		$properties[] = array('Basic damage', $obj->sbscdmg);
	}
	if($obj->sbsctlnt != 0) {
		$properties[] = array('Basic talent', $obj->sbsctlnt);
	}
	if($obj->sbnshc != 0) {
		$properties[] = array('Bonus hit chance', $obj->sbnshc);
	}
	if($obj->sbnsdmg != 0) {
		$properties[] = array('Bonus damage', $obj->sbnsdmg);
	}
	if($obj->sbnstlnt != 0) {
		$properties[] = array('Bonus talent', $obj->sbnstlnt);
	}
	if($obj->ebschc != 0) {
		$properties[] = array('Basic hit chance (on opponent)', $obj->ebschc);
	}
	if($obj->ebscdmg != 0) {
		$properties[] = array('Basic damage (on opponent)', $obj->ebscdmg);
	}
	if($obj->ebsctlnt != 0) {
		$properties[] = array('Basic talent (on opponent)', $obj->ebsctlnt);
	}
	if($obj->ebnshc != 0) {
		$properties[] = array('Bonus hit chance (on opponent)', $obj->ebnshc);
	}
	if($obj->ebnsdmg != 0) {
		$properties[] = array('Bonus damage (on opponent)', $obj->ebnsdmg);
	}
	if($obj->ebnstlnt != 0) {
		$properties[] = array('Bonus talent (on opponent)', $obj->ebnstlnt);
	}
	if($obj->estr != 0) {
		$properties[] = array('Strength (on opponent)', $obj->estr);
	}
	if($obj->edef != 0) {
		$properties[] = array('Defence (on opponent)', $obj->edef);
	}
	if($obj->edex != 0) {
		$properties[] = array('Dexterity (on opponent)', $obj->edex);
	}
	if($obj->eend != 0) {
		$properties[] = array('Endurance (on opponent)', $obj->eend);
	}
	if($obj->echa != 0) {
		$properties[] = array('Charisma (on opponent)', $obj->echa);
	}
	if($obj->attack != 0) {
		$properties[] = array('Attack', $obj->attack);
	}
	if($obj->eattack != 0) {
		$properties[] = array('Attack (on opponent)', $obj->eattack);
	}
	if($obj->id == 26) {
		$properties[] = array('Mana effect', 2);
	}
	if($obj->id == 76) {
		$properties[] = array('Health points after victory', 12500);
	}
	if($obj->id == 77) {
		$properties[] = array('Active talent effect duration', 1);
	}
	if($obj->id == 86) {
		$properties[] = array('Health extraction', '100%');
	}

	return $properties;
}

function getItemPropertyArray($obj) {
	$properties = array();

	if($obj->str != 0) {
		$properties[] = array('Strength', $obj->str);
	}
	if($obj->def != 0) {
		$properties[] = array('Defence', $obj->def);
	}
	if($obj->dex != 0) {
		$properties[] = array('Dexterity', $obj->dex);
	}
	if($obj->end != 0) {
		$properties[] = array('Endurance', $obj->end);
	}
	if($obj->cha != 0) {
		$properties[] = array('Charisma', $obj->cha);
	}
	if($obj->hpbonus != 0) {
		$properties[] = array('Health', $obj->hpbonus);
	}
	if($obj->regen != 0) {
		$properties[] = array('Regeneration', $obj->regen);
	}
	if($obj->sbschc != 0) {
		$properties[] = array('Basic hit chance', $obj->sbschc);
	}
	if($obj->sbscdmg != 0) {
		$properties[] = array('Basic damage', $obj->sbscdmg);
	}
	if($obj->sbsctlnt != 0) {
		$properties[] = array('Basic talent', $obj->sbsctlnt);
	}
	if($obj->sbnshc != 0) {
		$properties[] = array('Bonus hit chance', $obj->sbnshc);
	}
	if($obj->sbnsdmg != 0) {
		$properties[] = array('Bonus damage', $obj->sbnsdmg);
	}
	if($obj->sbnstlnt != 0) {
		$properties[] = array('Bonus talent', $obj->sbnstlnt);
	}
	if($obj->ebschc != 0) {
		$properties[] = array('Basic hit chance (on opponent)', $obj->ebschc);
	}
	if($obj->ebscdmg != 0) {
		$properties[] = array('Basic damage (on opponent)', $obj->ebscdmg);
	}
	if($obj->ebsctlnt != 0) {
		$properties[] = array('Basic talent (on opponent)', $obj->ebsctlnt);
	}
	if($obj->ebnshc != 0) {
		$properties[] = array('Bonus hit chance (on opponent)', $obj->ebnshc);
	}
	if($obj->ebnsdmg != 0) {
		$properties[] = array('Bonus damage (on opponent)', $obj->ebnsdmg);
	}
	if($obj->ebnstlnt != 0) {
		$properties[] = array('Bonus talent (on opponent)', $obj->ebnstlnt);
	}

	return $properties;
}