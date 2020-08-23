<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 9:35 PM
 */

/**
 * @param \Database\Models\User $user
 * @return string
 */
function getGraveyardRank($user) {
    $level = getLevel($user->getExp());

    if ($level < 10) {
        return __('city.city_graveyard_gravedigger');
    } elseif ($level < 25) {
        return __('city.city_graveyard_graveyard_gardener');
    } elseif ($level < 55) {
        return __('city.city_graveyard_corpse_predator');
    } elseif ($level < 105) {
        return __('city.city_graveyard_graveyard_guard');
    } elseif ($level < 195) {
        return __('city.city_graveyard_employee_manager');
    } elseif ($level < 335) {
        return __('city.city_graveyard_tombstone_designer');
    } elseif ($level < 511) {
        return __('city.city_graveyard_crypt_designer');
    } elseif ($level < 1024) {
        return __('city.city_graveyard_graveyard_manager');
    } else {
        return __('city.city_graveyard_graveyard_master');
    }
}

/**
 * @param \Database\Models\User $user
 * @return int
 */
function getBonusGraveyardGold($user)
{
    $userTalentStr = \Database\Models\UserTalent::leftJoin('talents', 'talents.id', '=', 'user_talents.talent_id')
        ->select(\Illuminate\Support\Facades\DB::raw('SUM(talents.str) as totalTalentStr'))
        ->where('user_talents.user_id', $user->getId())
        ->first();

    $userTotalStr = $user->getStr() + $userTalentStr->totalTalentStr;

    $bonusWithStr = $userTotalStr * 0.5;
    $level = getLevel($user->getExp());

    if ($level > 19) {
        $bonusWithStr = $userTotalStr * 2;
    } elseif ($level > 14) {
        $bonusWithStr = $userTotalStr * 1.5;
    } elseif ($level > 4) {
        $bonusWithStr = $userTotalStr * 1;
    }

    $bonusWithLevel = ($level * (0.1035 * $level));

    return ceil($bonusWithLevel + $bonusWithStr);
}

function highscoreShowToName($val) {
	if($val == 'level') {return __('general.level');}
	elseif($val == 'raid') {return __('general.booty');}
	elseif($val == 'fightvalue') {return __('general.battle_value');}
	elseif($val == 'fights') {return __('general.fights');}
	elseif($val == 'fight1') {return __('general.victories');}
	elseif($val == 'fight2') {return __('general.defeats');}
	elseif($val == 'fight0') {return __('general.draw');}
	elseif($val == 'lanter') {return __('general.lanterns');}
	elseif($val == 'goldwin') {return __('general.looted_gold');}
	elseif($val == 'goldlost') {return __('general.lost_gold');}
	elseif($val == 'hits1') {return __('general.damage_caused');}
	elseif($val == 'hits2') {return __('general.hit_points_lost');}
	elseif($val == 'trophypoints') {return __('general.trophy_points');}
	elseif($val == 'castle') {return __('general.level');}
	elseif($val == 'warraid') {return __('general.war_booty');}
	elseif($val == 'members') {return __('general.members');}
	elseif($val == 'ppm') {return __('general.average_booty');}
	elseif($val == 'seals') {return __('general.seals');}
	elseif($val == 'gatesopen') {return __('general.gate_openings');}
	elseif($val == 'lastgateopen') {return __('general.last_gate_opening');}
	else {return __('general.henchmen_power');}
}

function isUserPremiumActivated($user = null)
{
	/**
	 * @var \Database\Models\User $user
	 */
	if(!$user) {
		if(!user()) return false;
		else return user()->getApMax() > 130;
	} else {
		return $user->getApMax() > 130;
	}

}

function getPreviousExpNeeded($level): int
{
	return getExpNeeded($level - 1);
}

function getRaceString($race = 1) {
	return $race === 1 ? __('general.vampire') : __('general.werewolf');
}

function getExpNeeded($level): int
{
	return ((pow( $level, 2 ) * 5) + (5 * floor($level / 5)));
}

function getLevel($exp): int
{
	return floor( sqrt( $exp / 5 ) ) + 1;
}

function user_race_logo_small()
{
	$race = user() ? user()->getRace() : env('DEFAULT_SERVER_RACE');

	return new \Illuminate\Support\HtmlString(
		'<img src="'.
		asset('img/symbols/race'.$race.'small.gif').'" alt="'.
		getRaceString($race).'" />');
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

function user() {
	return \Illuminate\Support\Facades\Auth::user();
}

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
						<form method="post" action="<?php echo url('/profile/item/equip/'.$i->id); ?>">
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

function urlGetParams($url, $params=array()) {
	$url = url($url);

	foreach ($params as $key => $val) {
		if(empty($val) || ($key == 'premiumfilter' && $val == 'all')) {
			unset($params[$key]);
		}
	}

	if(isset($params['page']) && $params['page'] == 1) {
		unset($params['page']);
	}

	if(empty($params)) return $url;

	if(strpos($url,'?') === false && !empty($params)) $url .= '?';

	foreach($params as $key => $value) {
		if(substr($url, -1) != '?') {
			$url .= '&';
		}

		$url .= $key.'='.$value;
	}

	return $url;
}

function parseBBCodes($text) {

	preg_match_all('~!S:"(.*?)"!~s', $text, $users, PREG_SET_ORDER);

	foreach($users as $user) {
		$userDb = ORM::for_table('user')
			->selectExpr('id')
			->where('name', $user[1])
			->find_one();

		if($userDb) {
			$text = str_replace('S:"'.$user[1].'"', 'S:'.$user[1].':'.$userDb->id, $text);
		} else {
			$text = str_replace('!S:"'.$user[1].'"!', $user[1], $text);
		}
	}

	preg_match_all('~!N:"(.*?)"!~s', $text, $clannames, PREG_SET_ORDER);

	foreach($clannames as $clanname) {
		$clanDb = ORM::for_table('clan')
			->selectExpr('id')
			->where('name', $clanname[1])
			->find_one();

		if($clanDb) {
			$text = str_replace('N:"'.$clanname[1].'"', 'N:'.$clanname[1].':'.$clanDb->id, $text);
		} else {
			$text = str_replace('!N:"'.$clanname[1].'"!', $clanname[1], $text);
		}
	}

	preg_match_all('~!A:"(.*?)"!~s', $text, $clantags, PREG_SET_ORDER);

	foreach($clantags as $clantag) {
		$clanDb = ORM::for_table('clan')
			->selectExpr('id')
			->where('tag', $clantag[1])
			->find_one();

		if($clanDb) {
			$text = str_replace('A:"'.$clantag[1].'"', 'A:'.$clantag[1].':'.$clanDb->id, $text);
		} else {
			$text = str_replace('!A:"'.$clantag[1].'"!', $clantag[1], $text);
		}
	}

	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[f s=(.*?)\](.*?)\[/f\]~s',
		'~\[f c=(.*?)\](.*?)\[/f\]~s',
		'~\[f f="(.*?)"\](.*?)\[/f\]~s',
		'~!S:(.*?):(.*?)!~s',
		'~!N:(.*?):(.*?)!~s',
		'~!A:(.*?):(.*?)!~s',
	);

	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
		'<font face="$1">$2</font>',
		'<a href="'.url('profile/player/$2').'">$1</a>',
		'<a href="'.url('clan/view/$2').'">$1</a>',
		'<a href="'.url('clan/view/$2').'">$1</a>'
	);

	return str_replace(PHP_EOL, '<br>', preg_replace($find,$replace,e($text)));
}

function getClanStatusString($last_activity)
{
	return floor((time() - $last_activity) / 3600).'d '.gmdate("H:i:s", time() - $last_activity);
}

function getNameChangeCost($count, $exp) {
	return pow(2, $count) * getLevel($exp) * 4800;
}

function getClanHideoutCost($stufe) {
	if($stufe == 1) {return 3;} elseif($stufe == 2) {return 296;}
	elseif($stufe == 3) {return 4130;} elseif($stufe == 4) {return 26796;}
	elseif($stufe == 5) {return 114283;} elseif($stufe == 6) {return 375818;}
	elseif($stufe == 7) {return 1018158;} elseif($stufe == 8) {return 2425286;}
	elseif($stufe == 9) {return 5215001;} elseif($stufe == 10) {return 10343751;}
	elseif($stufe == 11) {return 19218989;} elseif($stufe == 12) {return 33834222;}
	elseif($stufe == 13) {return 56925897;} elseif($stufe == 14) {return 92153181;}
	elseif($stufe == 15) {return 144301645;} elseif($stufe == 16) {return 219511858;}
	elseif($stufe == 17) {return 325533800;} else {return 472008025;}
}

function getHideoutCost($type, $level) {
	if($type=='domi') {return pow(4, $level);}
	elseif($type=='wall') {return pow(10, $level);}
	elseif($type=='path') {return pow(9, $level);}
	else {return pow(8, $level);}
}

function getWallEffect($level) {
	if(!$level) return 0;
	if($level == 1) return -1;
	return ($level - 1) * -3;
}

function getLandEffect($level) {
	if(!$level) return 0;
	if($level == 1 || $level == 2) return $level * 2;
	if($level < 6) return ($level - 1) * 2;
	return 12;
}

function getItemModelIdFromModel($model) {
	$model_array = array(
		'weapons' => 1,
		'potions' => 2,
		'helmets' => 3,
		'armour' => 4,
		'jewellery' => 5,
		'gloves' => 6,
		'boots' => 7,
		'shields' => 8
	);

	return isset($model_array[$model])?$model_array[$model]:$model_array['weapons'];
}

function getMessageSenderNameFromMessage($msg) {
	if($msg->sender_id < 1) {
		switch($msg->sender_id) {
			default:
				return 'System';
				break;
			case -2:
				return 'Graveyard employee';
				break;
		}
	} else {
		if(empty($msg->name)) {
			return 'Deleted user';
		} else {
			return $msg->name;
		}
	}
}

function getHumanHuntNameFromNo($huntNo)
{
	if($huntNo == 1) {
		return 'Farm';
	} elseif($huntNo == 2) {
		return 'Village';
	} elseif($huntNo == 3) {
		return 'Small Town';
	} elseif($huntNo == 4) {
		return 'City';
	} else {
		return 'Metropolis';
	}
}

function getUserStatusColor($last_activity) {
	$delta = time() - $last_activity;

	if($delta < 300) {
		return 'lime';
	} elseif($delta < 900) {
		return 'green';
	} else {
		return 'red';
	}
}