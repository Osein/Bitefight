<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 24/01/17
 * Time: 21:12
 */

namespace Bitefight\Controllers;

use Bitefight\Library\Translate;
use ORM;
use Phalcon\Filter;

class CityController extends GameController
{
    public function initialize()
    {
        $this->view->menu_active = 'city';
        return parent::initialize();
    }

    public function getIndex()
    {
        $this->view->pick('city/index');
    }

    public function getLibrary()
    {
        $this->view->pick('city/library');
    }

    public function postLibrary()
    {
        $method = $this->request->get('method');
        $name = $this->request->get('name');

        if (strlen($name) < 3) {
            $this->flashSession->warning(Translate::_('validation_username_character_error'));
            return $this->response->redirect(getUrl('city/library'));
        }

        $userCheck = ORM::for_table('user')
            ->where('name', $name)
            ->find_one();

        if ($userCheck) {
            $this->flashSession->warning(Translate::_('validation_username_used'));
            return $this->response->redirect(getUrl('city/library'));
        }

        if ($method == 1) {
            $gcost = getNameChangeCost($this->user->name_change, $this->user->exp);

            if ($this->user->gold < $gcost) {
                $this->flashSession->warning(Translate::_('city_library_not_enough_gold'));
            } else {
                $this->user->name = $name;
                $this->user->gold -= $gcost;
                $this->user->s_booty = $this->user->s_booty * 9 / 10;
                $this->user->name_change++;
                $this->flashSession->warning(Translate::_('city_library_name_change_success'));
            }

            return $this->response->redirect(getUrl('city/library'));
        } else {
            if ($this->user->hellstone < 10) {
                $this->flashSession->warning(Translate::_('city_library_not_enough_hellstone'));
            } else {
                $this->user->name = $name;
                $this->user->hellstone -= 10;
                $this->flashSession->warning(Translate::_('city_library_name_change_success'));
            }

            return $this->response->redirect(getUrl('city/library'));
        }
    }

    public function getChurch()
    {
        $activity = ORM::for_table('user_activity')
            ->where('activity_type', ACTIVITY_TYPE_CHURCH)
            ->where('user_id', $this->user->id)
            ->find_one();

        $this->view->delta = max($activity?$activity->end_time - time():0, 0);
        $this->view->usedTimes = ceil($this->view->delta / 3600);
        $this->view->requiredAp = 5 * pow(2, $this->view->usedTimes);
        $this->view->pick('city/church');
    }

    public function postChurch()
    {
        if ($this->user->hp_now >= $this->user->hp_max) {
            return $this->response->redirect(getUrl('city/church'));
        }

        $activity = ORM::for_table('user_activity')
            ->where('activity_type', ACTIVITY_TYPE_CHURCH)
            ->where('user_id', $this->user->id)
            ->find_one();

        $delta = max($activity?$activity->end_time - time():0, 0);
        $usedTimes = ceil($delta / 3600);
        $requiredAp = 5 * pow(2, $usedTimes);

        if ($this->user->ap_now < $requiredAp) {
            return $this->response->redirect(getUrl('city/church'));
        }

        if (!$activity) {
            $activity = ORM::for_table('user_activity')->create();
            $activity->user_id = $this->user->id;
            $activity->activity_type = ACTIVITY_TYPE_CHURCH;
            $activity->end_time = time() + 3600;
        } else {
            if ($activity->end_time < time()) {
                $activity->end_time = time() + 3600;
            } else {
                $activity->end_time = $activity->end_time + 3600;
            }
        }

        $activity->save();

        $this->user->hp_now = $this->user->hp_max;
        $this->user->ap_now -= $requiredAp;

        $this->flashSession->warning(Translate::_('city_church_healing_success'));
        return $this->response->redirect(getUrl('city/church'));
    }

    public function getGraveyard()
    {
        $this->view->pick('city/graveyard');
        $activity = ORM::for_table('user_activity')
            ->where('user_id', $this->user->id)
            ->where('activity_type', ACTIVITY_TYPE_GRAVEYARD)
            ->find_one();
        $time = time();

        if ($activity && $activity->end_time >= $time) {
            $this->view->delta = $activity->end_time - $time;
            return;
        } elseif ($activity) {
            if ($activity->end_time > $activity->start_time) {
                $rewardMultiplier = ($activity->end_time - $activity->start_time) / 900;
                $goldReward = $rewardMultiplier * (getLevel($this->user->exp) * 50 + $this->getBonusGraveyardGold());
                $activity->end_time = $activity->start_time;
                $activity->save();
                $this->user->gold += $goldReward;
            }
        }

        $level = getLevel($this->user->exp);

        if ($level < 10) {
            $this->view->work_rank = Translate::_('city_graveyard_gravedigger');
        } elseif ($level < 25) {
            $this->view->work_rank = Translate::_('city_graveyard_graveyard_gardener');
        } elseif ($level < 55) {
            $this->view->work_rank = Translate::_('city_graveyard_corpse_predator');
        } elseif ($level < 105) {
            $this->view->work_rank = Translate::_('city_graveyard_graveyard_guard');
        } elseif ($level < 195) {
            $this->view->work_rank = Translate::_('city_graveyard_employee_manager');
        } elseif ($level < 335) {
            $this->view->work_rank = Translate::_('city_graveyard_tombstone_designer');
        } elseif ($level < 511) {
            $this->view->work_rank = Translate::_('city_graveyard_crypt_designer');
        } elseif ($level < 1024) {
            $this->view->work_rank = Translate::_('city_graveyard_graveyard_manager');
        } else {
            $this->view->work_rank = Translate::_('city_graveyard_graveyard_master');
        }

        $this->view->bonus_gold = $this->getBonusGraveyardGold();
    }

    public function getBonusGraveyardGold()
    {
        $userTalentStr = ORM::for_table('user_talent')
            ->left_outer_join('talent', ['user_talent.talent_id', '=', 'talent.id'])
            ->selectExpr('SUM(talent.str)', 'totalTalentStr')
            ->where('user_talent.user_id', $this->user->id)
            ->find_one();

        $userTotalStr = $this->user->str + $userTalentStr->totalTalentStr;

        $bonusWithStr = $userTotalStr * 0.5;
        $level = getLevel($this->user->exp);

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

    public function postGraveyard()
    {
        $duration = $this->request->get('workDuration');

        if ($duration < 1 || $duration > 8) {
            return $this->notFound();
        }

        $activity = ORM::for_table('user_activity')
            ->where('user_id', $this->user->id)
            ->where('activity_type', ACTIVITY_TYPE_GRAVEYARD)
            ->find_one();

        if (!$activity) {
            $activity = ORM::for_table('user_activity')->create();
            $activity->user_id = $this->user->id;
            $activity->activity_type = ACTIVITY_TYPE_GRAVEYARD;
        }

        $time = time();
        $activity->end_time = $time + $duration * 900;
        $activity->start_time = $time;
        $activity->save();
        return $this->response->redirect(getUrl('city/graveyard'));
    }

    public function postGraveyardCancel()
    {
        $activity = ORM::for_table('user_activity')
            ->where('user_id', $this->user->id)
            ->where('activity_type', ACTIVITY_TYPE_GRAVEYARD)
            ->find_one();

        if (!$activity) {
            return $this->notFound();
        }

        $activity->end_time = $activity->start_time;
        $activity->save();
        return $this->response->redirect(getUrl('city/graveyard'));
    }

    public function getShop()
    {
        $modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');
        $itemModel = $this->request->get('model', Filter::FILTER_STRING);
        $levelFrom = $this->request->get('lvlfrom', Filter::FILTER_INT);
        $levelTo = $this->request->get('lvlto', Filter::FILTER_INT);
        $pfilter = $this->request->get('premiumfilter', Filter::FILTER_STRING);
        $page = $this->request->get('page', Filter::FILTER_INT);
        $modelId = getItemModelIdFromModel($itemModel);
        $userLevel = getLevel($this->user->exp);

        $this->view->setVars([
            'iLevelFrom' => $levelFrom,
            'iLevelTo' => $levelTo,
            'iModel' => $itemModel,
            'iPFilter' => $pfilter,
            'iPage' => $page,
            'rPage' => empty($page)?1:$page
        ]);

        if(empty($levelFrom)) $levelFrom = 1;
        if(empty($levelTo)) $levelTo = getLevel($this->user->exp);
        if(empty($pfilter)) $pfilter = 'all';
        if(empty($itemModel)) $itemModel = 'weapons';
        if(empty($page)) $page = 1;

        if(!in_array($itemModel, $modelArray)) {
            $this->notFound();
        }

        if($levelFrom > $levelTo) {
            return $this->response->redirect(getUrl('city/shop?lvlfrom='.$levelTo.'&lvlto='.$levelFrom.($itemModel!='weapons'?'&model='.$itemModel:'')));
        }

        $pdo = ORM::get_db();
        $stmt = $pdo->prepare('SELECT COUNT(1) AS count FROM item WHERE item.level >= ? AND item.level <= ? AND item.model = ?');
        $stmt->execute([$levelFrom, $levelTo, $modelId]);
        $item_count = intval($stmt->fetchColumn());

        $sql = 'SELECT item.*, user_item.volume FROM item
                LEFT JOIN user_item ON user_item.item_id = item.id AND user_item.user_id = ?
                WHERE item.level >= ? AND item.level <= ? AND item.model = ?';

        if($pfilter == 'premium') {
            $sql .= ' AND item.scost > 0';
        } elseif($pfilter == 'nonpremium') {
            $sql .= ' AND item.scost = 0';
        }

        $sql .= ' ORDER BY item.level DESC LIMIT 20 OFFSET '.(($page - 1) * 20);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->user->id, min($levelFrom, $userLevel), max($userLevel, $levelTo), $modelId]);
        $results = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $user_item_max_count = 3 + ($this->user->h_domicile * 2);

        $this->view->setVars([
            'user_item_max_count' => $user_item_max_count,
            'user_item_available_slot' => $user_item_max_count - ORM::for_table('user_item')->where('user_id', $this->user->id)->sum('volume'),
            'items' => $results,
            'item_count' => $item_count,
            'total_pages' => ceil($item_count / 20)
        ]);

        $this->view->pick('city/shop');
    }

    public function postShopItemBuy($id) {
        $model = $this->request->get('model');
        $page = $this->request->get('page');
        $lvlfrom = $this->request->get('lvlfrom');
        $lvlto = $this->request->get('lvlto');
        $premiumfilter = $this->request->get('premiumfilter');
        $volume = $this->request->get('volume');

        $redirect_link = getLinkWithParams(getUrl('city/shop'), array('page' => $page, 'lvlto' => $lvlto, 'lvlfrom' => $lvlfrom, 'premiumfilter' => $premiumfilter, 'model' => $model));

        $item = ORM::for_table('item')->find_one($id);

        if(!$item && !in_array($volume, [1, 5, 10])) {
            return $this->notFound();
        }

        $user_item_count = ORM::for_table('user_item')->where('user_id', $this->user->id)->sum('volume');
        $user_max_item_count = $this->user->h_domicile * 2 + 3;

        if($item->gcost * $volume > $this->user->gold || $item->scost * $volume > $this->user->hellstone || $user_item_count + $volume > $user_max_item_count)
        {
            return $this->notFound();
        }

        $this->user->gold -= $item->gcost * $volume;
        $this->user->hellstone -= $item->scost * $volume;

        $user_item_rst = ORM::for_table('user_item')
            ->where('user_id', $this->user->id)
            ->where('item_id', $item->id)
            ->find_one();

        if($user_item_rst) {
            $user_item_rst->volume += $volume;
            $user_item_rst->save();
        } else {
            $user_item_rst = ORM::for_table('user_item')->create();
            $user_item_rst->user_id = $this->user->id;
            $user_item_rst->item_id = $item->id;
            $user_item_rst->volume = $volume;
            $user_item_rst->equipped = 0;
            $user_item_rst->expire = 0;
            $user_item_rst->save();
        }

        return $this->response->redirect($redirect_link);
    }

    public function postShopItemSell($id) {
        $model = $this->request->get('model');
        $page = $this->request->get('page');
        $lvlfrom = $this->request->get('lvlfrom');
        $lvlto = $this->request->get('lvlto');
        $premiumfilter = $this->request->get('premiumfilter');

        $redirect_link = getLinkWithParams(getUrl('city/shop'), array('page' => $page, 'lvlto' => $lvlto, 'lvlfrom' => $lvlfrom, 'premiumfilter' => $premiumfilter, 'model' => $model));

        $user_item = ORM::for_table('user_item')
            ->where('user_id', $this->user->id)
            ->where('item_id', $id)
            ->find_one();

        if(!$user_item) return $this->notFound();

        $item = ORM::for_table('item')->find_one($id);

        if($user_item->volume > 0)
        {
            $this->user->gold += $item->slcost;
            $user_item->volume -= 1;
            $user_item->save();
        }

        return $this->response->redirect($redirect_link);
    }

}
