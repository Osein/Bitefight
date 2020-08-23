<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/25/2018
 * Time: 10:21 AM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Mail\ConfirmEmail;
use Database\Models\Clan;
use Database\Models\ClanApplications;
use Database\Models\Message;
use Database\Models\User;
use Database\Models\UserActivity;
use Database\Models\UserBuddy;
use Database\Models\UserBuddyRequest;
use Database\Models\UserDescription;
use Database\Models\UserEmailActivation;
use Database\Models\UserItems;
use Database\Models\UserMessageBlock;
use Database\Models\UserMessageFolder;
use Database\Models\UserMessageSettings;
use Database\Models\UserMissions;
use Database\Models\UserNote;
use Database\Models\UserTalent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', [
			'except' => ['getNews', 'getHighscore', 'getPreview']
		]);
	}

	public function getNews()
	{
		$news = DB::table('news')->get();
		return view('home.news', ['news' => $news]);
	}

	public function getHideout()
	{
		return view('user.hideout');
	}

	public function postHideout()
	{
		$structure = Input::get('structure');
		$week = Input::get('week');
		$hellstoneCost = $week == 4 ? 20 : ($week == 6 ? 69 : 55);
		$duration = $week == 4 ? 2419200 : ($week == 6 ? 3628800 : 7257600);
		$user = user();

		if(in_array( $structure, ['treasure', 'royal', 'gargoyle', 'book'])) {
			if($user->getHellstone() < $hellstoneCost) throw new InvalidRequestException();
			$user->{'h_'.$structure} = $user->{'h_'.$structure} > time() ? $user->{'h_'.$structure} + $duration : time() + $duration;
			$user->setHellstone($user->getHellstone() - $hellstoneCost);
		} else {
			if(!in_array($structure, ['domicile', 'wall', 'land', 'path'])) throw new InvalidRequestException();

			$goldCost = getHideoutCost($structure, $user->{'h_'.$structure});
			if($user->getGold() < $goldCost) throw new InvalidRequestException();
			$user->setGold($user->getGold() - $goldCost);
			if($structure == 'path') $user->setApMax($user->getApMax() + 1);
			$user->{'h_'.$structure}++;
		}

		return redirect(url('/hideout'));
	}

	public function getNotepad()
	{
		$note = DB::table('user_note')->where('user_id', user()->getId())->first();
		return view('user.notepad', ['user_note' => $note?$note->note:'']);
	}

	public function postNotepad()
	{
		$note = Input::get('note');

		$dbNote = DB::table('user_note')->where('user_id', user()->getId())->first();
		if (!$dbNote) {
			DB::table('user_note')->insert([
				'user_id' => user()->getId(),
				'note' => $note
			]);
		} else {
			DB::table('user_note')
				->where('user_id', user()->getId())
				->update(['note' => $note]);
		}

		return redirect(url('/notepad'));
	}

	public function getSearch()
	{
		return view('user.search');
	}

	public function postSearch()
	{
		$searchType = Input::get('searchtyp');
		$search = Input::get('text');
		$exact = Input::get('exakt');

		if ($searchType == 'name') {
			$results = User::select('id', 'name', 'race', 's_booty')
				->where('name', $exact ? '=' : 'LIKE', $exact ? $search : '%'.$search.'%')
				->paginate(25);
		} else {
			$results = Clan::select(
				'id', 'name', 'tag', 'stufe', 'race',
				DB::raw('(SELECT SUM(s_booty) FROM users WHERE clan_id = clan.id) AS booty'),
				DB::raw('(SELECT COUNT(1) FROM users WHERE clan_id = clan.id) AS members')
			)->where($searchType == 'clan' ? 'name' : 'tag',
				$exact ? '=' : 'LIKE',
				$exact ? $search : '%'.$search.'%')
				->paginate(25);
		}

		return view('user.search', [
			'searchType' => $searchType,
			'searchString' => $search,
			'exact' => $exact,
			'results' => $results
		]);
	}

	public function getHighscore()
	{
		$race = Input::get('race', 0);
		$type = Input::get('type', 'player');
		$page = Input::get('page', 1);
		$order = Input::get('order', 'raid');
		$showArr = Input::get('show', []);

		if ($type == 'player') {
			$showArr = array_slice(
				empty($showArr) ? ['level', 'raid', 'fightvalue'] : $showArr,
				0,
				5
			);

			if (!in_array($order, $showArr))
				$order = in_array('raid', $showArr) ? 'raid' : $showArr[0];

			$result = User::select('name', 'id', 'race');

			if ($race > 0 && $race < 3)
				$result->where('race', $race);

			foreach ($showArr as $show) {
				if ($show == 'level') {
					$result->addSelect(DB::raw('FLOOR(SQRT(exp / 5)) + 1 AS level'));
				} elseif ($show == 'raid') {
					$result->addSelect('s_booty AS raid');
				} elseif ($show == 'fightvalue') {
					$result->addSelect('battle_value AS fightvalue');
				} elseif ($show == 'fights') {
					$result->addSelect('s_fight AS fights');
				} elseif ($show == 'fight1') {
					$result->addSelect('s_victory AS fight1');
				} elseif ($show == 'fight2') {
					$result->addSelect('s_defeat AS fight2');
				} elseif ($show == 'fight0') {
					$result->addSelect('s_draw AS fight0');
				} elseif ($show == 'goldwin') {
					$result->addSelect('s_gold_captured AS goldwin');
				} elseif ($show == 'goldlost') {
					$result->addSelect('s_gold_lost AS goldlost');
				} elseif ($show == 'hits1') {
					$result->addSelect('s_damage_caused AS hits1');
				} elseif ($show == 'hits2') {
					$result->addSelect('s_hp_lost AS hits2');
				} elseif ($show == 'trophypoints') {
					// I dont know what is this lol
				} elseif ($show == 'henchmanlevels') {
					// I dont know this too, but oh lol well find out later
				}
			}
		} else {
			$showArr = array_slice(
				empty($showArr) ? ['castle', 'raid', 'warraid'] : $showArr,
				0,
				5
			);

			if (!in_array($order, $showArr))
				$order = in_array('raid', $showArr) ? 'raid' : $showArr[0];

			$result = Clan::select('clan.id', 'clan.name', 'clan.tag', 'clan.race')
				->leftJoin('users', 'users.clan_id', '=', 'clan.id')
				->groupBy('clan.id');

			if ($race > 0 && $race < 3)
				$result->where('race', $race);

			foreach ($showArr as $show) {
				if ($show == 'castle') {
					$result->addSelect('clan.stufe AS castle');
				} elseif ($show == 'raid') {
					$result->addSelect(DB::raw('SUM(users.s_booty) AS raid'));
				} elseif ($show == 'warraid') {
					$result->addSelect(DB::raw('SUM(users.battle_value) AS warraid'));
				} elseif ($show == 'fights') {
					$result->addSelect(DB::raw('SUM(users.s_fight) AS fights'));
				} elseif ($show == 'fight1') {
					$result->addSelect(DB::raw('SUM(users.s_victory) AS fight1'));
				} elseif ($show == 'fight2') {
					$result->addSelect(DB::raw('SUM(users.s_defeat) AS fight2'));
				} elseif ($show == 'fight0') {
					$result->addSelect(DB::raw('SUM(users.s_draw) AS fight0'));
				} elseif ($show == 'members') {
					$result->addSelect(DB::raw('COUNT(1) AS members'));
				} elseif ($show == 'ppm') {
					// Average booty
				} elseif ($show == 'seals') {
					// Seals
				} elseif ($show == 'gatesopen') {
					// Opened gates
				} elseif ($show == 'lastgateopen') {
					// Last gate opening
				}
			}
		}

		/**
		 * @var LengthAwarePaginator $result
		 */
		$result = $result->orderBy($order, 'desc')
			->paginate(50);

		$startRank = ($page - 1) * 50 + 1;

		$vampireCount = User::where('race', 1)->count();
		$werewolfCount = User::where('race', 2)->count();
		$vampireBlood = User::where('race', 1)
			->select(DB::raw('SUM(s_booty) AS booty'))
			->first()->booty;
		$werewolfBlood = User::where('race', 2)
			->select(DB::raw('SUM(s_booty) AS booty'))
			->first()->booty;
		$vampireBattle = User::where('race', 1)
			->select(DB::raw('SUM(s_fight) AS fights'))
			->first()->fights;
		$werewolfBattle = User::where('race', 2)
			->select(DB::raw('SUM(s_fight) AS fights'))
			->first()->fights;
		$vampireGold = User::where('race', 1)
			->select(DB::raw('SUM(gold) AS gold'))
			->first()->gold;
		$werewolfGold = User::where('race', 2)
			->select(DB::raw('SUM(gold) AS gold'))
			->first()->gold;

		$linkExtras_show = '';

		foreach ($showArr as $show) {
			$linkExtras_show .= '&show[]='.$show;
		}

		$myPosLink = urlGetParams('/highscore/mypos', ['type' => $type, 'race' => $race, 'order' => $order]) . $linkExtras_show;
		$showHeadLink = urlGetParams('/highscore', ['type' => $type, 'race' => $race]) . $linkExtras_show;


		return view('user.highscore', [
			'race' => $race,
			'type' => $type,
			'page' => $page,
			'order' => $order,
			'show' => $showArr,
			'results' => $result,
			'startRank' => $startRank,
			'vampireCount' => $vampireCount,
			'werewolfCount' => $werewolfCount,
			'vampireBlood' => $vampireBlood,
			'werewolfBlood' => $werewolfBlood,
			'vampireBattle' => $vampireBattle,
			'werewolfBattle' => $werewolfBattle,
			'vampireGold' => $vampireGold,
			'werewolfGold' => $werewolfGold,
			'myPosLink' => $myPosLink,
			'showHeadLink' => $showHeadLink
		]);
	}

	public function postHighscoreMyPosition()
	{
		$race = Input::get('race', 0);
		$type = Input::get('type', 'player');
		$page = Input::get('page', 1);
		$order = Input::get('order', 'raid');
		$showArr = Input::get('show', []);

		if($type == 'player') {
			$showArr = array_slice(
				empty($showArr) ? ['level', 'raid', 'fightvalue'] : $showArr,
				0,
				5
			);

			$result = DB::table('users');

			if ($race > 0 && $race < 3)
				$result->where('race', $race);

			foreach ($showArr as $show) {
				if ($show == 'level') {
					$result = $result->where('exp', '>=', \user()->getExp())->orderBy('exp', 'desc');
				} elseif ($show == 'raid') {
					$result = $result->where('s_booty', '>=', \user()->getSBooty())->orderBy('s_booty', 'desc');
				} elseif ($show == 'fightvalue') {
					$result = $result->where('battle_value', '>=', \user()->getBattleValue())->orderBy('battle_value', 'desc');
				} elseif ($show == 'fights') {
					$result = $result->where('s_fight', '>=', \user()->getSFight())->orderBy('s_fight', 'desc');
				} elseif ($show == 'fight1') {
					$result = $result->where('s_victory', '>=', \user()->getSVictory())->orderBy('s_victory', 'desc');
				} elseif ($show == 'fight2') {
					$result = $result->where('s_defeat', '>=', \user()->getSDefeat())->orderBy('s_defeat', 'desc');
				} elseif ($show == 'fight0') {
					$result = $result->where('s_draw', '>=', \user()->getSDraw())->orderBy('s_draw', 'desc');
				} elseif ($show == 'goldwin') {
					$result = $result->where('s_gold_captured', '>=', \user()->getSGoldCaptured())->orderBy('s_gold_captured', 'desc');
				} elseif ($show == 'goldlost') {
					$result = $result->where('s_gold_lost', '>=', \user()->getSGoldLost())->orderBy('s_gold_lost', 'desc');
				} elseif ($show == 'hits1') {
					$result = $result->where('s_damage_caused', '>=', \user()->getSDamageCaused())->orderBy('s_damage_caused', 'desc');
				} elseif ($show == 'hits2') {
					$result = $result->where('s_hp_lost', '>=', \user()->getSHpLost())->orderBy('s_hp_lost', 'desc');
				} elseif ($show == 'trophypoints') {
					// I dont know what is this lol
				} elseif ($show == 'henchmanlevels') {
					// I dont know this too, but oh lol well find out later
				}
			}

			$resultCount = $result->count();
		} else {
			$showArr = array_slice(
				empty($showArr) ? ['castle', 'raid', 'warraid'] : $showArr,
				0,
				5
			);

			if (!in_array($order, $showArr))
				$order = in_array('raid', $showArr) ? 'raid' : $showArr[0];

			$clanObj = Clan::leftJoin('users', 'users.clan_id', '=', 'clan.id')
				->groupBy('clan.id')->where('clan.id', \user()->getClanId());

			if ($order == 'castle') {
				$clanObj->select('clan.stufe AS castle');
			} elseif ($order == 'raid') {
				$clanObj->select(DB::raw('SUM(users.s_booty) AS raid'));
			} elseif ($order == 'warraid') {
				$clanObj->select(DB::raw('SUM(users.battle_value) AS warraid'));
			} elseif ($order == 'fights') {
				$clanObj->select(DB::raw('SUM(users.s_fight) AS fights'));
			} elseif ($order == 'fight1') {
				$clanObj->select(DB::raw('SUM(users.s_victory) AS fight1'));
			} elseif ($order == 'fight2') {
				$clanObj->select(DB::raw('SUM(users.s_defeat) AS fight2'));
			} elseif ($order == 'fight0') {
				$clanObj->select(DB::raw('SUM(users.s_draw) AS fight0'));
			} elseif ($order == 'members') {
				$clanObj->select(DB::raw('COUNT(1) AS members'));
			} elseif ($order == 'ppm') {
				// Average booty
			} elseif ($order == 'seals') {
				// Seals
			} elseif ($order == 'gatesopen') {
				// Opened gates
			} elseif ($order == 'lastgateopen') {
				// Last gate opening
			}

			$clanObj = $clanObj->first();

			if($order == 'castle') {
				$countQuery = Clan::where('stufe', '>', $clanObj->castle)
					->orWhere(function($q) use($clanObj) {
						$q->where('stufe', $clanObj->castle);
						$q->where('id', '>', \user()->getClanId());
					});
			} else {
				$countQuery = Clan::leftJoin('users', 'users.clan_id', '=', 'clan.id')
					->groupBy('clan.id');

				if ($order == 'raid') {
					$countQuery->havingRaw('SUM(users.s_booty) >= '.$clanObj->{$order});
				} elseif ($order == 'warraid') {
					$countQuery->havingRaw('SUM(users.battle_value) >= '.$clanObj->{$order});
				} elseif ($order == 'fights') {
					$countQuery->havingRaw('SUM(users.s_fight) >= '.$clanObj->{$order});
				} elseif ($order == 'fight1') {
					$countQuery->havingRaw('SUM(users.s_victory) >= '.$clanObj->{$order});
				} elseif ($order == 'fight2') {
					$countQuery->havingRaw('SUM(users.s_defeat) >= '.$clanObj->{$order});
				} elseif ($order == 'fight0') {
					$countQuery->havingRaw('SUM(users.s_draw) >= '.$clanObj->{$order});
				} elseif ($order == 'members') {
					$countQuery->havingRaw('SUM(users.id) >= '.$clanObj->{$order});
				} elseif ($order == 'ppm') {
					// Average booty
				} elseif ($order == 'seals') {
					// Seals
				} elseif ($order == 'gatesopen') {
					// Opened gates
				} elseif ($order == 'lastgateopen') {
					// Last gate opening
				}
			}

			$resultCount = $countQuery->count();
		}

		$page = ceil($resultCount / 50);

		$linkExtras_show = '';

		foreach ($showArr as $show) {
			$linkExtras_show .= '&show[]='.$show;
		}

		$myPosLink = urlGetParams('/highscore', ['type' => $type, 'race' => $race, 'order' => $order, 'page' => $page]) . $linkExtras_show;

		return redirect($myPosLink);
	}

	public function getPreview($userId)
	{
		$user = User::select(
			'users.*', 'user_description.descriptionHtml', 'clan_rank.rank_name',
			'clan_rank.war_minister', 'clan.logo_sym', 'clan.logo_bg',
			'clan.id AS clan_id', 'clan.name AS clan_name', 'clan.tag AS clan_tag'
		)->leftJoin('clan', 'clan.id', '=', 'users.clan_id')
			->leftJoin('clan_rank', 'clan_rank.id', '=', 'users.clan_rank')
			->leftJoin('user_description', 'users.id', '=', 'user_description.user_id')
            ->where('users.id', $userId)
			->first();

		if (!$user) {
			throw new InvalidRequestException();
		}

		$stat_max = max($user->str, $user->dex, $user->dex, $user->end, $user->cha);
		$userLevel = getLevel($user->exp);
		$previousLevelExp = getPreviousExpNeeded($userLevel);
		$nextLevelExp = getExpNeeded($userLevel);
		$levelExpDiff = $nextLevelExp - $previousLevelExp;

		$viewData = [
            'puser' => $user,
            'exp_red_long' => ($user->exp - $previousLevelExp) / $levelExpDiff * 400,
            'str_red_long' => $user->str / $stat_max * 400,
            'def_red_long' => $user->def / $stat_max * 400,
            'dex_red_long' => $user->dex / $stat_max * 400,
            'end_red_long' => $user->end / $stat_max * 400,
            'cha_red_long' => $user->cha / $stat_max * 400
        ];

		if(\user()) {
            $viewData['friendRequestSent'] = UserBuddyRequest::where('from_id', \user()->getId())
                ->where('to_id', $user->id)
                ->count();

            $viewData['isFriend'] = UserBuddy::where(function($q) use ($user) {
                $q->where('user_from_id', \user()->getId())
                    ->where('user_to_id', $user->id);
            })->orWhere(function($q) use ($user) {
                $q->where('user_to_id', \user()->getId())
                    ->where('user_from_id', $user->id);
            })->count();
        }

		return view('user.preview', $viewData);
	}

	public function getSettings()
	{
		/**
		 * @var UserEmailActivation $userEmailActivation
		 */
		$userEmailActivation = UserEmailActivation::where('user_id', \user()->getId())->first();

		if(!empty(Input::get('activate'))) {
			Mail::to($userEmailActivation->getEmail())->send(new ConfirmEmail(\user(), $userEmailActivation->getToken()));
		}

		$userDescription = UserDescription::where('user_id', \user()->getId())->first();

		return view('user.settings', [
			'email_activation' => $userEmailActivation,
			'description' => $userDescription,
			'vacationDays' => floor((time() - \user()->getVacation()) / (60 * 60 * 24)),
            'activationEmailSent' => !empty(Input::get('activate'))
		]);
	}

	public function postSettings()
	{
		$email = Input::get('email');

		if($email) {
			/**
			 * @var UserEmailActivation $userConfirmMail
			 */
			$userConfirmMail = UserEmailActivation::where('user_id', \user()->getId())->first();

			if(!$userConfirmMail) {
				$userConfirmMail = new UserEmailActivation;
			}

			if($userConfirmMail->email != $email) {
				$userConfirmMail->setEmail($email);
				$userConfirmMail->setExpire(time() + 60*60*24*3);
				$userConfirmMail->save();
				if(empty($userConfirmMail->token)) {
				    $userConfirmMail->setToken($token = str_random(64));
                }
                Mail::to($userConfirmMail->getEmail())->send(new ConfirmEmail(\user(), $userConfirmMail->getToken()));
			}
		}

		$rpg = Input::get('rpg', '');
		$rpg = is_null($rpg) ? '' : $rpg;

        /**
         * @var UserDescription $userDesc
         */
        $userDesc = UserDescription::where('user_id', \user()->getId())->first();

        if(!$userDesc) {
            $userDesc = new UserDescription;
            $userDesc->setUserId(\user()->getId());
        }

        $userDesc->setDescription($rpg);
        $userDesc->setDescriptionHtml(parseBBCodes($rpg));
        $userDesc->save();

		$showLogo = Input::get('showlogo');
		\user()->setShowPicture(!!$showLogo);

		$vacation = Input::get('vacation');
		$vacationDiffDay = floor((time() - \user()->getVacation()) / 60 * 60 * 24);

		if($vacation && $vacationDiffDay >= 30) {
			\user()->setVacation(time());
		} elseif(!$vacation && $vacationDiffDay >= 2) {
			\user()->setVacation(time() - 31 * 60 * 60 * 24);
		}

		$pass0 = Input::get('pass0');
		$pass1 = Input::get('pass1');
		$pass2 = Input::get('pass2');

		if(!empty($pass1) && !empty($pass2) && !empty($pass0)) {
			if($pass0 != \user()->getPassword()) {
				session()->flash('settings_password_change_error', 'Your password is incorrect');
			} elseif($pass1 != $pass2) {
				session()->flash('settings_password_change_error', 'The passwords you entered do not match each other.');
			} elseif(strlen($pass1) < 3) {
				session()->flash('settings_password_change_error', 'The password must have at least 3 characters');
			} else {
				\user()->setPassword($pass1);
			}
		}

		$delete = Input::get('delete');

		if($delete) {
			ClanController::doLeaveClanRoutine(user());
			Message::where('receiver_id', \user()->getId())->delete();
			Message::where('sender_id', \user()->getId())->delete();
			UserActivity::where('user_id', \user()->getId())->delete();
			UserDescription::where('user_id', \user()->getId())->delete();
			UserEmailActivation::where('user_id', \user()->getId())->delete();
			UserItems::where('user_id', \user()->getId())->delete();
			UserMessageBlock::where('user_id', \user()->getId())->delete();
			UserMessageSettings::where('user_id', \user()->getId())->delete();
			UserMessageFolder::where('user_id', \user()->getId())->delete();
			UserMissions::where('user_id', \user()->getId())->delete();
			UserNote::where('user_id', \user()->getId())->delete();
			UserTalent::where('user_id', \user()->getId())->delete();
			DB::table('user_password_reset')->where('email', \user()->getEmail())->delete();
			\user()->delete();
		}

		return redirect(url('/settings'));
	}

	public function postVerifyUser($token)
	{
		/**
		 * @var UserEmailActivation $verifyMail
		 */
		$verifyMail = UserEmailActivation::where('token', $token)->first();

		if(!$verifyMail) {
			throw new InvalidRequestException();
		}

		if(!$verifyMail->isActivated()) {
            /**
             * @var User $user
             */
            $user = User::find($verifyMail->getUserId());

            if($verifyMail->isFirstTime() && time() < $verifyMail->getExpire() - 60 * 60 * 24) {
                /**
                 * @var UserItems $userItem
                 */
                $userItem = UserItems::where('user_id', $user->getId())
                    ->where('item_id', 1)
                    ->first();

                if(!$userItem) {
                    $userItem = new UserItems;
                    $userItem->setUserId($user->getId());
                    $userItem->setItemId(1);
                } else {
                    $userItem->setVolume($userItem->getVolume() + 1);
                }

                $userItem->save();
            }

            if(time() < $verifyMail->getExpire()) {
                $user->setEmail($verifyMail->getEmail());
                $user->save();
                $verifyMail->setActivated(true);
                $verifyMail->save();
            }
        }

		return redirect(url('/settings'));
	}

	public function postItemEquip($id)
	{
		$item = UserItems::leftJoin('items', 'items.id', '=', 'user_items.item_id')
			->where('user_items.item_id', $id)->where('user_items.user_id', \user()->getId())
			->first();

		if(!$item || $item->equipped || $item->volume < 1) {
			throw new InvalidRequestException();
		}

		if($item->model != 2) {
			DB::statement('UPDATE user_items LEFT JOIN items ON items.id = user_items.item_id SET equipped = 0 WHERE items.model = :model AND user_items.user_id = :user_id', [
				'model' => $item->model,
				'user_id' => \user()->getId()
			]);

			DB::statement('UPDATE user_items SET equipped = 1 WHERE user_id = :user_id AND item_id = :item_id', [
				'user_id' => \user()->getId(),
				'item_id' => $id
			]);
		} else {
			$duration = $item->duration > 0 ? $item->duration : $item->cooldown;
			$expire = $item->expire > time() ? $item->expire + $duration : time() + $duration;

			DB::statement('UPDATE user_items SET volume = volume - 1, expire = :expire WHERE user_id = :user_id AND item_id = :item_id', [
				'expire' => $expire,
				'user_id' => \user()->getId(),
				'item_id' => $id
			]);
		}

		return redirect(url('/profile/index'));
	}

	public function getBuddy()
    {
        $sort = Input::get('sort', 'name');
        $order = Input::get('order', 'up') == 'up' ? 'desc' : 'asc';

        if(!in_array($sort, ['name', 'race', 'clan', 'level', 'status']))
            throw new InvalidRequestException();

        $viewData = [
            'sort' => $sort,
            'order' => $order
        ];

        if($sort == 'name') {
            $sort = 'users.name';
        } elseif($sort == 'race') {
            $sort = 'users.race';
        } elseif($sort == 'clan') {
            $sort = 'clan.tag';
        } elseif($sort == 'level') {
            $sort = 'users.exp';
        } elseif($sort == 'status') {
            $sort = 'users.last_activity';
        }

        $bq1 = UserBuddy::select(
                'users.race',
                'users.name as user_name',
                'users.id as user_id',
                'clan.id as clan_id',
                'clan.tag as clan_tag',
                'users.exp',
                'users.last_activity'
            )->where('user_from_id', \user()->getId())
            ->leftJoin('users', 'users.id', '=', 'user_buddy.user_to_id')
            ->leftJoin('clan', 'users.clan_id', '=', 'clan.id')
            ->orderBy($sort, $order);

        $viewData['buddies'] = UserBuddy::select(
                'users.race',
                'users.name as user_name',
                'users.id as user_id',
                'clan.id as clan_id',
                'clan.tag as clan_tag',
                'users.exp',
                'users.last_activity'
            )->where('user_to_id', \user()->getId())
            ->leftJoin('users', 'users.id', '=', 'user_buddy.user_from_id')
            ->leftJoin('clan', 'users.clan_id', '=', 'clan.id')
            ->orderBy($sort, $order)
            ->union($bq1)
            ->get();

        $viewData['ownRequests'] = UserBuddyRequest::select('clan.tag as clan_tag', 'clan.id as clan_id', 'users.name', 'user_buddy_request.*')
            ->leftJoin('users', 'users.id', '=', 'user_buddy_request.to_id')
            ->leftJoin('clan', 'clan.id', '=', 'users.clan_id')
            ->where('from_id', \user()->getId())
            ->get();

        $viewData['receivedRequests'] = UserBuddyRequest::select('clan.tag as clan_tag', 'clan.id as clan_id', 'users.name', 'user_buddy_request.*')
            ->leftJoin('users', 'users.id', '=', 'user_buddy_request.from_id')
            ->leftJoin('clan', 'clan.id', '=', 'users.clan_id')
            ->where('to_id', \user()->getId())
            ->get();

        return view('user.buddy.list', $viewData);
    }

    public function postBuddy()
    {
        $buddyId = Input::get('buddy_id');

        if(empty($buddyId))
            throw new InvalidRequestException();

        if(!empty(Input::get('accept'))) {
            $deleted = UserBuddyRequest::where(function($q) use ($buddyId) {
                $q->where('from_id', \user()->getId())
                    ->where('to_id', $buddyId);
            })->orWhere(function($q) use ($buddyId) {
                $q->where('to_id', \user()->getId())
                    ->where('from_id', $buddyId);
            })->delete();

            if($deleted) {
                $buddy = new UserBuddy;
                $buddy->setUserFromId($buddyId);
                $buddy->setUserToId(\user()->getId());
                $buddy->save();

                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId($buddyId);
                $mail->setSubject('Buddy list');
                $mail->setMessage(\user()->getName() . ' has accepted your buddy request');
                $mail->save();
            }
        }

        if(!empty(Input::get('deny'))) {
            $deleted = UserBuddyRequest::where('to_id', \user()->getId())
                ->where('from_id', $buddyId)
                ->delete();

            if($deleted) {
                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId($buddyId);
                $mail->setSubject('Buddy list');
                $mail->setMessage(\user()->getName() . ' has declined your buddy request');
                $mail->save();

                $buddy = User::find($buddyId);

                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId(\user()->getId());
                $mail->setSubject('Buddy list');
                $mail->setMessage($buddy->name . ' has been deleted from the buddy list');
                $mail->save();
            }
        }

        if(!empty(Input::get('takeback'))) {
            $deleted = UserBuddyRequest::where('to_id', $buddyId)
                ->where('from_id', \user()->getId())
                ->delete();

            if($deleted) {
                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId($buddyId);
                $mail->setSubject('Buddy list');
                $mail->setMessage(\user()->getName() . ' has deleted you from the buddy list');
                $mail->save();

                $buddy = User::find($buddyId);

                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId(\user()->getId());
                $mail->setSubject('Buddy list');
                $mail->setMessage($buddy->name . ' has been deleted from the buddy list');
                $mail->save();
            }
        }

        if(!empty(Input::get('delete'))) {
            $deleted = UserBuddy::where(function($q) use ($buddyId) {
                $q->where('user_from_id', \user()->getId())
                    ->where('user_to_id', $buddyId);
            })->orWhere(function($q) use ($buddyId) {
                $q->where('user_to_id', \user()->getId())
                    ->where('user_from_id', $buddyId);
            })->delete();

            if($deleted) {
                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId($buddyId);
                $mail->setSubject('Buddy list');
                $mail->setMessage(\user()->getName() . ' has deleted you from the buddy list');
                $mail->save();

                $buddy = User::find($buddyId);

                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId(\user()->getId());
                $mail->setSubject('Buddy list');
                $mail->setMessage($buddy->name . ' has been deleted from the buddy list');
                $mail->save();
            }
        }

        return redirect(url('/buddy'));
    }

    public function getBuddyRequest($id)
    {
        if($id == \user()->getId())
            throw new InvalidRequestException();

        $user = User::select('users.*', 'clan.tag as clan_tag')
            ->leftJoin('clan', 'clan.id', '=', 'users.clan_id')
            ->find($id);

        if(!$user) {
            throw new InvalidRequestException();
        }

        $requestedBefore = UserBuddyRequest::where('to_id', $id)
            ->where('from_id', \user()->getId())
            ->count();

        $isAlreadyBuddies = UserBuddy::where(function($query) use ($id) {
            $query->where('user_from_id', \user()->getId())
                ->where('user_to_id', $id);
        })->orWhere(function($query) use ($id) {
            $query->where('user_from_id', $id)
                ->where('user_to_id', \user()->getId());
        })->count();

        return view('user.buddy.request', ['bUser' => $user, 'alreadyContacted' => $requestedBefore > 0 || $isAlreadyBuddies > 0]);
    }

    public function postBuddyRequest($id)
    {
        if($id == \user()->getId())
            throw new InvalidRequestException();

        /**
         * @var User $user
         */
        $user = User::select('users.*', 'clan.name as clan_name')
            ->leftJoin('clan', 'clan.id', '=', 'users.clan_id')
            ->find($id);

        if(!$user) {
            throw new InvalidRequestException();
        }

        $buddyRequest = new UserBuddyRequest;
        $buddyRequest->setFromId(user()->getId());
        $buddyRequest->setToId($user->getId());
        $buddyRequest->setMessage(Input::get('note', ''));
        $buddyRequest->setRequestTime(time());
        $buddyRequest->save();

        return view('user.buddy.request', ['formSent' => true, 'to_id' => $user->getId()]);
    }

}