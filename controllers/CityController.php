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
        $bonusWithStr = $this->user->str * 0.5;
        $level = getLevel($this->user->exp);

        if ($level > 19) {
            $bonusWithStr = $this->user->str * 2;
        } elseif ($level > 14) {
            $bonusWithStr = $this->user->str * 1.5;
        } elseif ($level > 4) {
            $bonusWithStr = $this->user->str * 1;
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
}
