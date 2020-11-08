<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/22/2018
 * Time: 10:41 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use Database\Models\Item;
use Database\Models\Message;
use Database\Models\UserActivity;
use Database\Models\UserItems;
use Database\Models\UserMessageSettings;
use Database\Models\UserMissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
		return view('city.index');
	}

	public function getChurch()
	{
		$activity = UserActivity::where('activity_type', UserActivity::ACTIVITY_TYPE_CHURCH)
			->where('user_id', user()->getId())
			->first();

		$delta = max($activity?$activity->end_time - time():0, 0);
		$usedTimes = ceil($delta / 3600);

		return view('city.church', [
			'delta' => $delta,
			'usedTimes' => $usedTimes,
			'requiredAp' => 5 * pow(2, $usedTimes)
		]);
	}

	public function postChurch()
	{
		$user = user();

		if($user->getHpNow() == $user->getHpMax())
			throw new InvalidRequestException();

		$activity = UserActivity::where('activity_type', UserActivity::ACTIVITY_TYPE_CHURCH)
			->where('user_id', user()->getId())
			->first();

		$delta = max($activity?$activity->end_time - time():0, 0);
		$usedTimes = ceil($delta / 3600);
		$requiredAp = 5 * pow(2, $usedTimes);

		if($user->getApNow() < $requiredAp)
			throw new InvalidRequestException();

		$time = time();

		if(!$activity) {
			$activity = new UserActivity;
			$activity->setUserId($user->getId());
			$activity->setActivityType(UserActivity::ACTIVITY_TYPE_CHURCH);
			$activity->setEndTime(0);
			$activity->setStartTime($time);
		}

		if($activity->getEndTime() < $time) {
			$activity->setEndTime($time + 3600);
		} else {
			$activity->setEndTime($activity->getEndTime() + 3600);
		}

		$activity->save();

		$user->setHpNow($user->getHpMax());
		$user->setApNow($user->getApNow() - $requiredAp);

		session()->flash('churchUsed', true);
		return redirect(url('/city/church'));
	}

	public function getGraveyard()
	{
		$activity = UserActivity::where('activity_type', UserActivity::ACTIVITY_TYPE_GRAVEYARD)
			->where('user_id', user()->getId())
			->first();

		return view('city.graveyard', [
			'working' => $activity && $activity->end_time > time(),
			'end_time' => $activity ? $activity->end_time : 0,
			'work_rank' => getGraveyardRank(user()),
			'bonus_gold' => getBonusGraveyardGold(user())
		]);
	}

	public function postGraveyard()
	{
		$duration = Input::get('workDuration');

		if ($duration < 1 || $duration > 8)
			throw new InvalidRequestException();

		$activity = UserActivity::where('activity_type', UserActivity::ACTIVITY_TYPE_GRAVEYARD)
			->where('user_id', user()->getId())
			->first();
		$time = time();

		if (!$activity) {
			$activity = new UserActivity;
			$activity->setUserId(user()->getId());
			$activity->setActivityType(UserActivity::ACTIVITY_TYPE_GRAVEYARD);
		}

		$activity->setStartTime($time);
		$activity->setEndTime($time + $duration * 900);
		$activity->save();
		return redirect(url('/city/graveyard'));
	}

	public function postGraveyardCancel()
	{
		$activity = UserActivity::where('activity_type', UserActivity::ACTIVITY_TYPE_GRAVEYARD)
			->where('user_id', user()->getId())
			->first();

		if(!$activity)
			throw new InvalidRequestException();

		$activity->setEndTime($activity->getStartTime() - 1);
		$activity->save();
		return redirect(url('/city/graveyard'));
	}

	public function getVoodoo()
	{
		return view('city.voodoo');
	}

	public function postVoodoo()
	{
		$user = user();

		if(!empty(Input::get('buy_methamorphosis'))) {
			if($user->getHellstone() < 50) {
				throw new InvalidRequestException();
			}

			$user->setHellstone($user->getHellstone() - 50);
			$user->setRace(0);

			if($user->getClanId() > 0) {
				ClanController::doLeaveClanRoutine($user);
			}
		}

		if(!empty(Input::get('buy_shadow_lord'))) {
			if($user->getHellstone() < 15) {
				throw new InvalidRequestException();
			}

			$user->setHellstone($user->getHellstone() - 15);
			$time = time();
			$user->setPremium(($user->getPremium() > $time ? $user->getPremium() : time()) + 1209600);
		}

		return redirect(url('/voodoo'));
	}

	public function getLibrary()
	{
		return view('city.library');
	}

	public function postLibrary(Request $request)
	{
		$method = Input::get('method', 1);
		$name = Input::get('name');

		$validator = Validator::make($request->all(), [
			'name' => 'required|unique:users',
			'method' => 'required|in:1,2',
		]);

		if($validator->fails())
			throw new InvalidRequestException();

		if($method == 1) {
			$gcost = getNameChangeCost(user()->getNameChange(), user()->getExp());

			if (user()->getGold() < $gcost) {
				throw new InvalidRequestException();
			} else {
				user()->setName($name);
				user()->setGold(user()->getGold() - $gcost);
				user()->setSBooty(user()->getSBooty() * 9 / 10);
				user()->setNameChange(user()->getNameChange() + 1);
			}
		} else {
			if (user()->getHellstone() < 10) {
				throw new InvalidRequestException();
			} else {
				user()->setName($name);
				user()->setHellstone(user()->getHellstone() - 10);
			}
		}

		return view('city.library', ['nameChanged' => true]);
	}

	public function getShop()
	{
		$userLevel = getLevel(user()->getExp());
		$modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');
		$itemModel = Input::get('model', 'weapons');
		$levelFrom = Input::get('lvlfrom', 1);
		$levelTo = Input::get('lvlto', $userLevel);
		$pfilter = Input::get('premiumfilter', 'all');
		$page = Input::get('page', 1);
		$modelId = getItemModelIdFromModel($itemModel);

		if(!in_array($itemModel, $modelArray)) {
			throw new InvalidRequestException();
		}

		if($levelFrom > $levelTo) {
			$tmp = $levelFrom;
			$levelFrom = $levelTo;
			$levelTo = $tmp;
		}

		$dbSql = Item::select(DB::raw('items.*, user_items.volume'))
			->leftJoin('user_items', function($join) {
				$join->on('user_items.item_id', 'items.id');
				$join->on('user_items.user_id', DB::raw(user()->getId()));
			})
			->where('items.level', '>=', min($levelFrom, $userLevel))
			->where('items.level', '<=', max($userLevel, $levelTo))
			->where('items.model', $modelId);

		if($pfilter == 'premium') {
			$dbSql = $dbSql->where('items.scost', '>', 0);
		} elseif($pfilter == 'nonpremium') {
			$dbSql = $dbSql->where('items.scost', 0);
		}

		$results = $dbSql->orderBy('items.level', 'desc')
			->skip(($page - 1) * 20)
			->paginate(20)
			->appends([
				'model' => $itemModel,
				'page' => $page,
				'premiumfilter' => $pfilter,
				'lvlto' => $levelTo,
				'lvlfrom' => $levelFrom
			]);

		$user_item_max_count = env('INITIAL_ITEM_SLOTS') + (user()->getHDomicile() * 2);
		$user_item_available_slot = $user_item_max_count - UserItems::where('user_id', user()->getId())
				->sum('volume');

		return view('city.shop', [
			'user_item_max_count' => $user_item_max_count,
			'user_item_available_slot' => $user_item_available_slot,
			'items' => $results,
			'iLevelFrom' => $levelFrom,
			'iLevelTo' => $levelTo,
			'iModel' => $itemModel,
			'iPFilter' => $pfilter,
			'iPage' => $page,
		]);
	}

	public function postShopItemBuy($itemId)
	{
		$model = Input::get('model');
		$page = Input::get('page');
		$lvlfrom = Input::get('lvlfrom');
		$lvlto = Input::get('lvlto');
		$premiumfilter = Input::get('premiumfilter');
		$volume = Input::get('volume');

		/**
		 * @var Item $item
		 */
		$item = Item::find($itemId);

		if(!$item && !in_array($volume, [1, 5, 10])) {
			throw new InvalidRequestException();
		}

		$user_item_count = UserItems::where('user_id', user()->getId())->sum('volume');
		$user_max_item_count = user()->getHDomicile() * 2 + env('INITIAL_ITEM_SLOTS');

		if($item->gcost * $volume > user()->getGold() ||
			$item->scost * $volume > user()->getHellstone() ||
			$user_item_count + $volume > $user_max_item_count)
		{
			throw new InvalidRequestException();
		}

		user()->setGold(user()->getGold() - $item->gcost * $volume);
		user()->setHellstone(user()->getHellstone() - $item->scost * $volume);

		$user_item_rst = UserItems::where('user_id', \user()->getId())
			->where('item_id', $item->getId())
			->first();

		if($user_item_rst) {
			$user_item_rst->volume += $volume;
		} else {
			$user_item_rst = new UserItems;
			$user_item_rst->setUserId(\user()->getId());
			$user_item_rst->setItemId($item->getId());
			$user_item_rst->setVolume($volume);
			$user_item_rst->setEquipped(false);
			$user_item_rst->setExpire(0);
			$user_item_rst->save();
		}

		$user_item_rst->save();

		return redirect(urlGetParams('/city/shop', [
			'model' => $model,
			'page' => $page,
			'premiumfilter' => $premiumfilter,
			'lvlto' => $lvlto,
			'lvlfrom' => $lvlfrom
		]));
	}

	public function postShopItemSell($itemId)
	{
		$model = Input::get('model');
		$page = Input::get('page');
		$lvlfrom = Input::get('lvlfrom');
		$lvlto = Input::get('lvlto');
		$premiumfilter = Input::get('premiumfilter');

		/**
		 * @var UserItems $user_item
		 */
		$user_item = UserItems::where('user_id', \user()->getId())
			->where('item_id', $itemId)
			->first();

		if(!$user_item)
			throw new InvalidRequestException();

		$item = Item::find($itemId);

		if($user_item->getVolume() > 0) {
			\user()->setGold(\user()->getGold() + $item->slcost);
			$user_item->setVolume($user_item->getVolume() - 1);
			$user_item->save();
		}

		return redirect(urlGetParams('/city/shop', [
			'model' => $model,
			'page' => $page,
			'premiumfilter' => $premiumfilter,
			'lvlto' => $lvlto,
			'lvlfrom' => $lvlfrom
		]));
	}

	public function getTaverne()
	{
		return view('city.taverne');
	}

	public function getMissions()
	{
		/**
		 * @var UserMissions[] $missions
		 */
		$missions = UserMissions::where('user_id', \user()->getId())
			->orderBy('accepted', 'desc')
			->get();

		if(count($missions) < 10 || $missions[0]->getDay() != date('d')) {
			$missions = UserMissions::replaceOpenMissions();
		}

		$totalActiveMissions = 0;
		$finishedMissionCount = 0;

		$missionTypes = [
			UserMissions::TYPE_HUMAN_HUNT => [
				'accepted' => 0,
				'canAccept' => true
			]
		];

		foreach ($missions as $mission) {
			if($mission->getAccepted()) {
				$missionTypes[$mission->getType()]['accepted']++;
				$totalActiveMissions++;

				if($missionTypes[$mission->getType()]['accepted'] == 2) {
					$missionTypes[$mission->getType()]['canAccept'] = false;
				}
			}

			if($mission->getTime() > 0) {
				if($mission->getAcceptedTime() != 0 &&
					$mission->getAcceptedTime() + 3600 * $mission->getTime() < time()
				) {
					$mission->setStatus(2);
					$mission->save();
				}
			}

			if($mission->getStatus() > 0) {
				$finishedMissionCount++;
			}
		}

		return view('city.mission', [
			'missions' => $missions,
			'total_active' => $totalActiveMissions,
			'types' => $missionTypes,
			'finished_count' => $finishedMissionCount
		]);
	}

	public function postAcceptMission($missionId)
	{
		/**
		 * @var UserMissions $mission
		 */
		$mission = UserMissions::where('user_id', \user()->getId())->find($missionId);
		$mission->setAccepted(true);
		$mission->setAcceptedTime(time());
		$mission->save();
		return redirect(url('/city/missions'));
	}

	public function postFinishMission($missionId)
	{
		/**
		 * @var UserMissions $mission
		 */
		$mission = UserMissions::where('user_id', \user()->getId())
			->where('accepted', 1)
			->where('status', 0)
			->find($missionId);

		if(!$mission)
			throw new InvalidRequestException();

		$time = time();

		if($mission->getTime() > 0 && $mission->getAcceptedTime() + 3600 * $mission->getTime() < $time) {
			$mission->setStatus(2);
			$mission->save();
			return redirect(url('/city/missions'));
		}

		if($mission->getProgress() == $mission->getCount()) {
			$msgsetting = UserMessageSettings::getUserSetting(UserMessageSettings::MISSION);

			if ($msgsetting->getFolderId() != -2) {
				$huntTypeString = 'Successful man hunts';

				$msg = new Message;
				$msg->setSenderId(Message::SENDER_SYSTEM);
				$msg->setReceiverId(\user()->getId());
				$msg->setFolderId($msgsetting->getFolderId());
				$msg->setSubject('Mission accomplished');
				$msg->setMessage('You have completed one of your missions.<br><br>'.$huntTypeString.' ('.$mission->getProgress().' / '.$mission->getCount().')');
				$msg->setStatus($msgsetting->isMarkRead() == 1 ? 2 : 1);
				$msg->save();
			}

			\user()->setGold(\user()->getGold() + $mission->getGold());
			\user()->setApNow(min(\user()->getApMax(), \user()->getApNow() + $mission->getAp()));
			\user()->setFragment(\user()->getFragment() + $mission->getFrag());
			\user()->setHpNow(min(\user()->getHpMax(), \user()->getHpNow() + \user()->getHpMax() * $mission->getHeal()));

			$mission->setStatus(1);
			$mission->save();
		}

		return redirect(url('/city/missions'));
	}

	public function postCancelMission($missionId)
	{
		if(\user()->getHellstone() < 2)
			throw new InvalidRequestException();

		\user()->setHellstone(\user()->getHellstone() - 2);

		/**
		 * @var UserMissions $mission
		 */
		$mission = UserMissions::where('user_id', \user()->getId())
			->find($missionId);
		$mission->setAccepted(0);
		$mission->save();

		return redirect(url('/city/missions'));
	}

	public function postReplaceOpenMissions()
	{
		if(\user()->getHellstone() < 6)
			throw new InvalidRequestException();

		\user()->setHellstone(\user()->getHellstone() - 6);

		UserMissions::replaceOpenMissions();

		return redirect(url('/city/missions'));
	}

	public function postReplaceOpenMissionsForAp()
	{
		if(\user()->getApNow() < 20)
			throw new InvalidRequestException();

		\user()->setApNow(\user()->getApNow() - 20);

		UserMissions::replaceOpenMissions();

		return redirect(url('/city/missions'));
	}

	public function getMarket()
    {
        return view('city.market');
    }

    public function getGrotto()
    {
        return view('city.grotto');
    }

    public function getArena()
    {
        return view('city.arena');
    }
}