<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 9:18 PM
 */

namespace App\Http\Controllers;

use Database\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

	/**
 	* ProfileController constructor.
 	*/
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		$user = Auth::user();

		$hsRow = collect(DB::select('SELECT (SELECT COUNT(1) FROM users WHERE s_booty > ?) AS greater,
                (SELECT COUNT(1) FROM users WHERE id < ? AND s_booty = ?) AS equal', [
			$user->getSBooty(), $user->getId(), $user->getSBooty()
		]))->first();

		$highscorePosition = $hsRow->greater + $hsRow->equal + 1;

		$user_active_items = array();

		$potions = array();
		$potion_count = 0;
		$weapons = array();
		$helmets = array();
		$armour = array();
		$jewellery = array();
		$gloves = array();
		$boots = array();
		$shields = array();
		$totems = array();

		$stat_str_tooltip = array(
			'detail' => array(array('Basic value', $user->getStr()))
		);

		$stat_def_tooltip = array(
			'detail' => array(array('Basic value', $user->getDef()))
		);

		$stat_dex_tooltip = array(
			'detail' => array(array('Basic value', $user->getDex()))
		);

		$stat_end_tooltip = array(
			'detail' => array(array('Basic value', $user->getEnd()))
		);

		$stat_cha_tooltip = array(
			'detail' => array(array('Basic value', $user->getCha()))
		);

		$stat_hp_tooltip = array(
			'detail' => array(
				array('Basic value', env('INITIAL_HP')),
				array('Defence', env('HP_PER_DEFENCE') * $user->getDef())
			)
		);

		$fm_attack_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_ATTACK')))
		);

		$fm_bsc_dmg_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_BASIC_DAMAGE')))
		);

		$fm_bsc_hc_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_HIT_CHANCE')))
		);

		$fm_bsc_tlnt_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_BASIC_TALENT')))
		);

		$fm_bns_dmg_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_BONUS_DAMAGE')))
		);

		$fm_bns_hc_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_BONUS_HIT_CHANCE')))
		);

		$fm_bns_tlnt_tooltip = array(
			'detail' => array(array('Basic value', env('INITIAL_BONUS_TALENT')))
		);

		$fm_regen_tooltip = array(
			'detail' => array(
				array('Basic value', env('INITIAL_REGENERATION')),
				array('Endurance', $user->getEnd() * env('REGEN_PER_ENDURANCE'))
			)
		);

		$fm_attack_total = env('INITIAL_ATTACK');
		$fm_bsc_dmg_total = env('INITIAL_BASIC_DAMAGE');
		$fm_bsc_hc_total = env('INITIAL_HIT_CHANCE');
		$fm_bsc_tlnt_total = env('INITIAL_BASIC_TALENT');
		$fm_bns_dmg_total = env('INITIAL_BONUS_DAMAGE');
		$fm_bns_hc_total = env('INITIAL_BONUS_HIT_CHANCE');
		$fm_bns_tlnt_total = env('INITIAL_BONUS_TALENT');
		$fm_regen_total = env('INITIAL_REGENERATION') + $user->getEnd() * env('REGEN_PER_ENDURANCE');

		$stat_str_total = $user->getStr();
		$stat_def_total = $user->getDef();
		$stat_dex_total = $user->getDex();
		$stat_end_total = $user->getEnd();
		$stat_cha_total = $user->getCha();
		$stat_hp_total = env('INITIAL_HP') + env('HP_PER_DEFENCE') * $stat_def_total;

		$talents = DB::table('user_talents')
			->join('talents', 'user_talents.talent_id', '=', 'talents.id')
			->where('user_id', $user->getId())
			->where('active', 0)
			->get();

		foreach($talents as $t) {
			if($t->attack > 0) {
				$fm_attack_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->attack);
				$fm_attack_total += $t->attack;
			}
			if($t->str > 0) {
				$stat_str_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->str);
				$stat_str_total += $t->str;
			}
			if($t->def > 0) {
				$stat_def_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->def);
				$stat_def_total += $t->def;
			}
			if($t->dex > 0) {
				$stat_dex_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->dex);
				$stat_dex_total += $t->dex;
			}
			if($t->end > 0) {
				$stat_end_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->end);
				$stat_end_total += $t->end;
			}
			if($t->cha > 0) {
				$stat_cha_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->cha);
				$stat_cha_total += $t->cha;
			}
			if($t->hpbonus > 0) {
				$stat_hp_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->hpbonus);
				$stat_hp_total += $t->hpbonus;
			}
			if($t->regen > 0) {
				$fm_regen_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->regen);
				$fm_regen_total += $t->regen;
			}
			if($t->sbscdmg > 0) {
				$fm_bsc_dmg_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbscdmg);
				$fm_bsc_dmg_total += $t->sbscdmg;
			}
			if($t->sbnsdmg > 0) {
				$fm_bns_dmg_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbnsdmg);
				$fm_bns_dmg_total += $t->sbnsdmg;
			}
			if($t->sbschc > 0) {
				$fm_bsc_hc_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbschc);
				$fm_bsc_hc_total += $t->sbschc;
			}
			if($t->sbnshc > 0) {
				$fm_bns_hc_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbnshc);
				$fm_bns_hc_total += $t->sbnshc;
			}
			if($t->sbsctlnt > 0) {
				$fm_bsc_tlnt_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbsctlnt);
				$fm_bsc_tlnt_total += $t->sbsctlnt;
			}
			if($t->sbnstlnt > 0) {
				$fm_bns_tlnt_tooltip['detail'][] = array(__('talent_id_'.$t->id.'_name'), $t->sbnstlnt);
				$fm_bns_tlnt_total += $t->sbnstlnt;
			}
		}

		$items = DB::table('user_items')
			->join('items', 'user_items.item_id', '=', 'items.id')
			->where('user_items.user_id', $user->getId())
			->get();

		foreach($items as $item) {
			if($item->expire > time() && $item->duration > 0)
			{
				$user_active_items[] = $item;
			}

			if($item->volume == 0) {
				continue;
			}

			if($item->model == 2) {
				$potion_count += $item->volume;
			}

			if($item->equipped ||($item->duration > 0 && $item->expire > time())) {
				if($item->str > 0) {
					$stat_str_tooltip['detail'][] = array($item->name, $item->str);
					$stat_str_total += $item->str;
				}

				if($item->def > 0) {
					$stat_def_tooltip['detail'][] = array($item->name, $item->def);
					$stat_def_total += $item->def;
				}

				if($item->dex > 0) {
					$stat_dex_tooltip['detail'][] = array($item->name, $item->dex);
					$stat_dex_total += $item->dex;
				}

				if($item->end > 0) {
					$stat_end_tooltip['detail'][] = array($item->name, $item->end);
					$stat_end_total += $item->end;
				}

				if($item->cha > 0) {
					$stat_cha_tooltip['detail'][] = array($item->name, $item->cha);
					$stat_cha_total += $item->cha;
				}

				if($item->hpbonus > 0) {
					$stat_hp_tooltip['detail'][] = array($item->name, $item->hpbonus);
					$stat_hp_total += $item->hpbonus;
				}

				if($item->regen > 0) {
					$fm_regen_tooltip['detail'][] = array($item->name, $item->regen);
					$fm_regen_total += $item->regen;
				}

				if($item->sbscdmg > 0) {
					$fm_bsc_dmg_tooltip['detail'][] = array($item->name, $item->sbscdmg);
					$fm_bsc_dmg_total += $item->sbscdmg;
				}

				if($item->sbnsdmg > 0) {
					$fm_bns_dmg_tooltip['detail'][] = array($item->name, $item->sbnsdmg);
					$fm_bns_dmg_total += $item->sbnsdmg;
				}

				if($item->sbschc > 0) {
					$fm_bsc_hc_tooltip['detail'][] = array($item->name, $item->sbschc);
					$fm_bsc_hc_total += $item->sbschc;
				}

				if($item->sbnshc > 0) {
					$fm_bns_hc_tooltip['detail'][] = array($item->name, $item->sbnshc);
					$fm_bns_hc_total += $item->sbnshc;
				}

				if($item->sbsctlnt > 0) {
					$fm_bsc_tlnt_tooltip['detail'][] = array($item->name, $item->sbsctlnt);
					$fm_bsc_tlnt_total += $item->sbsctlnt;
				}

				if($item->sbnstlnt > 0) {
					$fm_bns_tlnt_tooltip['detail'][] = array($item->name, $item->sbnstlnt);
					$fm_bns_tlnt_total += $item->sbnstlnt;
				}
			}

			${getItemModelFromModelNo($item->model)}[] = $item;
		}

		$fm_attack_tooltip['total'] = array('Attack', $fm_attack_total);
		$stat_str_tooltip['total'] = array('Strength', $stat_str_total);
		$stat_def_tooltip['total'] = array('Defence', $stat_def_total);
		$stat_dex_tooltip['total'] = array('Dexterity', $stat_dex_total);
		$stat_end_tooltip['total'] = array('Endurance', $stat_end_total);
		$stat_cha_tooltip['total'] = array('Charisma', $stat_cha_total);
		$stat_hp_tooltip['total'] = array('Total Health', $stat_hp_total);
		$fm_regen_tooltip['total'] = array('Regeneration', $fm_regen_total);
		$fm_bsc_dmg_tooltip['total'] = array('Basic Damage', $fm_bsc_dmg_total);
		$fm_bns_dmg_tooltip['total'] = array('Bonus Damage', $fm_bns_dmg_total);
		$fm_bsc_hc_tooltip['total'] = array('Basic Hit Chance', $fm_bsc_hc_total);
		$fm_bns_hc_tooltip['total'] = array('Bonus Hit Chance', $fm_bns_hc_total);
		$fm_bsc_tlnt_tooltip['total'] = array('Basic Talent', $fm_bsc_tlnt_total);
		$fm_bns_tlnt_tooltip['total'] = array('Bonus Talent', $fm_bns_tlnt_total);

		$max_stat = max($stat_str_total, $stat_def_total, $stat_dex_total, $stat_end_total, $stat_cha_total);

		$str_total_long = $stat_str_total / $max_stat * 300;
		$def_total_long = $stat_def_total / $max_stat * 300;
		$dex_total_long = $stat_dex_total / $max_stat * 300;
		$end_total_long = $stat_end_total / $max_stat * 300;
		$cha_total_long = $stat_cha_total / $max_stat * 300;

		$str_red_long = $user->getStr() / $max_stat * 300;
		$def_red_long = $user->getDef() / $max_stat * 300;
		$dex_red_long = $user->getDex() / $max_stat * 300;
		$end_red_long = $user->getEnd() / $max_stat * 300;
		$cha_red_long = $user->getCha() / $max_stat * 300;

		$userLevel = User::getLevel($user->getExp());

		$user_tlnt_lvl_sqrt = floor(sqrt($userLevel));

		$previousLevelExp = User::getPreviousExpNeeded($userLevel);
		$nextLevelExp = User::getExpNeeded($userLevel);
		$levelExpDiff = $nextLevelExp - $previousLevelExp;

		return view('profile.index', [
			'highscore_position' => $highscorePosition,

			'str_total_long' => $str_total_long,
			'def_total_long' => $def_total_long,
			'dex_total_long' => $dex_total_long,
			'end_total_long' => $end_total_long,
			'cha_total_long' => $cha_total_long,

			'str_cost' => getSkillCost($user->getStr()),
			'def_cost' => getSkillCost($user->getDef()),
			'dex_cost' => getSkillCost($user->getDex()),
			'end_cost' => getSkillCost($user->getEnd()),
			'cha_cost' => getSkillCost($user->getCha()),

			'str_red_long' => $str_red_long,
			'def_red_long' => $def_red_long,
			'dex_red_long' => $dex_red_long,
			'end_red_long' => $end_red_long,
			'cha_red_long' => $cha_red_long,

			'stat_str_tooltip' => $stat_str_tooltip,
			'stat_def_tooltip' => $stat_def_tooltip,
			'stat_dex_tooltip' => $stat_dex_tooltip,
			'stat_end_tooltip' => $stat_end_tooltip,
			'stat_cha_tooltip' => $stat_cha_tooltip,
			'stat_hp_tooltip' => $stat_hp_tooltip,

			'stat_str_total' => $stat_str_total,
			'stat_def_total' => $stat_def_total,
			'stat_dex_total' => $stat_dex_total,
			'stat_end_total' => $stat_end_total,
			'stat_cha_total' => $stat_cha_total,
			'stat_hp_total' => $stat_hp_total,

			'fm_attack_tooltip' => $fm_attack_tooltip,
			'fm_bsc_dmg_tooltip' => $fm_bsc_dmg_tooltip,
			'fm_bsc_hc_tooltip' => $fm_bsc_hc_tooltip,
			'fm_bsc_tlnt_tooltip' => $fm_bsc_tlnt_tooltip,
			'fm_bns_dmg_tooltip' => $fm_bns_dmg_tooltip,
			'fm_bns_hc_tooltip' => $fm_bns_hc_tooltip,
			'fm_bns_tlnt_tooltip' => $fm_bns_tlnt_tooltip,
			'fm_regen_tooltip' => $fm_regen_tooltip,

			'fm_attack_total' => $fm_attack_total,
			'fm_bsc_dmg_total' => $fm_bsc_dmg_total,
			'fm_bsc_hc_total' => $fm_bsc_hc_total,
			'fm_bsc_tlnt_total' => $fm_bsc_tlnt_total,
			'fm_bns_dmg_total' => $fm_bns_dmg_total,
			'fm_bns_hc_total' => $fm_bns_hc_total,
			'fm_bns_tlnt_total' => $fm_bns_tlnt_total,
			'fm_regen_total' => $fm_regen_total,

			'user_item_count' => count($items),
			'user_item_max_count' => 3 + $user->getHDomicile() * 2,

			'user_active_items' => $user_active_items,

			'potions' => $potions,
			'potion_count' => $potion_count,
			'weapons' => $weapons,
			'helmets' => $helmets,
			'armour' => $armour,
			'jewellery' => $jewellery,
			'gloves' => $gloves,
			'boots' => $boots,
			'shields' => $shields,
			'totems' => $totems,

			'hp_red_long' => $user->getHpNow() / $stat_hp_total * 400,
			'exp_red_long' => ($user->getExp() - $previousLevelExp) / $levelExpDiff * 400,
			'required_exp' => $nextLevelExp,

			'user_tlnt_max' => $user_tlnt_lvl_sqrt * 2 - 1,
			'next_tlnt_level' => pow($user_tlnt_lvl_sqrt + 1, 2),
			'user_tlnt_used_count' => DB::table('user_talents')->where('user_id', $user->getId())->count()
		]);
	}

}