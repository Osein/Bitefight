<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 14/01/17
 * Time: 03:26
 */

namespace Bitefight\Controllers;

use ORM;

class UserController extends GameController
{
    public function getProfile()
    {
        $this->view->menu_active = 'profile';

        $hsRow = ORM::for_table('')
            ->raw_query(
                'SELECT (SELECT COUNT(1) FROM user WHERE s_booty > ?) AS greater,
                (SELECT COUNT(1) FROM user WHERE id < ? AND s_booty = ?) AS equal',
                array($this->user->s_booty, $this->user->id, $this->user->s_booty)
            )->find_one();

        $this->view->highscorePosition = $hsRow->greater + $hsRow->equal + 1;

        $this->view->str_cost = getSkillCost($this->user->str);
        $this->view->def_cost = getSkillCost($this->user->def);
        $this->view->dex_cost = getSkillCost($this->user->dex);
        $this->view->end_cost = getSkillCost($this->user->end);
        $this->view->cha_cost = getSkillCost($this->user->cha);

        $max_skill = max($this->user->str, $this->user->def, $this->user->dex, $this->user->end, $this->user->cha);

        $this->view->str_red_long = 400 * ($this->user->str / $max_skill);
        $this->view->def_red_long = 400 * ($this->user->def / $max_skill);
        $this->view->dex_red_long = 400 * ($this->user->dex / $max_skill);
        $this->view->end_red_long = 400 * ($this->user->end / $max_skill);
        $this->view->cha_red_long = 400 * ($this->user->cha / $max_skill);

        $userLevel = getLevel($this->user->exp);

        $previousLevelExp = getPreviousExpNeeded($userLevel);
        $nextLevelExp = getExpNeeded($userLevel);
        $levelExpDiff = $nextLevelExp - $previousLevelExp;

        $this->view->exp_red_long = ($this->user->exp - $previousLevelExp) / $levelExpDiff * 400;
        $this->view->required_exp = $nextLevelExp;

        $this->view->hp_red_long = $this->user->hp_now / $this->user->hp_max * 400;

        $this->view->pick('user/profile');
    }

    public function postTrainingUp()
    {
        $stat_type = $this->request->getPost('stat_type');

        if (!in_array($stat_type, array('str', 'def', 'dex', 'end', 'cha'))) {
            return $this->notFound();
        }

        $userStat = $this->user->{$stat_type};
        $cost = getSkillCost($userStat);

        if ($this->user->gold < $cost) {
            return $this->notFound();
        }

        $this->user->gold -= $cost;
        $this->user->{$stat_type}++;

        return $this->response->redirect(getUrl('user/profile#tabs-2'));
    }

    public function getProfileLogo()
    {
        $this->view->pick('user/logo');
    }

    public function postProfileLogo()
    {
        $gender = $this->request->getPost('gender');
        $type = $this->request->getPost('type');

        $this->user->gender = $gender;
        $this->user->image_type = $type;

        return $this->response->redirect(getUrl('user/profile'));
    }

    public function getHideout()
    {
        $this->view->menu_active = 'hideout';

        $this->view->pick('user/hideout');
    }

    public function getHideoutUpgrade()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('hideout'));
        }

        $upgradeId = $this->request->get('id');

        switch ($upgradeId) {
            case 1:
                $upgradeCost = getHideoutCost('domi', $this->user->h_domicile);
                if ($this->user->gold >= $upgradeCost && $this->user->h_domicile < 14) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_domicile++;
                }
                break;
            case 2:
                $upgradeCost = getHideoutCost('wall', $this->user->h_wall);
                if ($this->user->gold >= $upgradeCost && $this->user->h_wall < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_wall++;
                }
                break;
            case 3:
                $upgradeCost = getHideoutCost('path', $this->user->h_path);
                if ($this->user->gold >= $upgradeCost && $this->user->h_path < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_path++;
                    $this->user->ap_max++;
                }
                break;
            case 4:
                $upgradeCost = getHideoutCost('land', $this->user->h_land);
                if ($this->user->gold >= $upgradeCost && $this->user->h_land < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_land++;
                }
                break;
            case 5:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_treasure >= time()) {
                        $this->user->h_treasure += 2419200;
                    } else {
                        $this->user->h_treasure = time() + 2419200;
                    }
                }
                break;
            case 6:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_treasure >= time()) {
                        $this->user->h_treasure += 7257600;
                    } else {
                        $this->user->h_treasure = time() + 7257600;
                    }
                }
                break;
            case 7:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_gargoyle >= time()) {
                        $this->user->h_gargoyle += 2419200;
                    } else {
                        $this->user->h_gargoyle = time() + 2419200;
                    }
                }
                break;
            case 8:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_gargoyle >= time()) {
                        $this->user->h_gargoyle += 7257600;
                    } else {
                        $this->user->h_gargoyle = time() + 7257600;
                    }
                }
                break;
            case 9:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_book >= time()) {
                        $this->user->h_book += 2419200;
                    } else {
                        $this->user->h_book = time() + 2419200;
                    }
                }
                break;
            case 10:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_book >= time()) {
                        $this->user->h_book += 7257600;
                    } else {
                        $this->user->h_book = time() + 7257600;
                    }
                }
                break;
            case 11:
                if ($this->user->hellstone >= 69) {
                    $this->user->hellstone -= 69;
                    if ($this->user->h_royal >= time()) {
                        $this->user->h_royal += 3628800;
                    } else {
                        $this->user->h_royal = time() + 3628800;
                    }
                }
                break;
        }

        return $this->response->redirect(getUrl('hideout'));
    }

    public function getNotepad()
    {
        $this->view->menu_active = 'notepad';
        $note = ORM::for_table('user_note')->where('user_id', $this->user->id)->find_one();
        $this->view->user_note = $note?$note->note:'';
        $this->view->pick('user/notepad');
    }

    public function postNotepad()
    {
        $note = $this->request->getPost('note');
        $dbNote = ORM::for_table('user_note')->where('user_id', $this->user->id)->find_one();
        if (!$dbNote) {
            $dbNote = ORM::for_table('user_note')->create();
            $dbNote->user_id = $this->user->id;
        }
        $dbNote->note = $note;
        $dbNote->save();
        return $this->response->redirect(getUrl('notepad'));
    }

    public function getSettings()
    {
        $this->view->menu_active = 'settings';
        $userDescription = ORM::for_table('user_description')->where('user_id', $this->user->id)->find_one();
        if ($userDescription) {
            $this->view->userDescription = $userDescription->description;
        }
        $this->view->pick('user/settings');
    }

    public function postSettings()
    {
        $parsedBb = parseBBCodes($this->request->get('rpg'));

        $dbDesc = ORM::for_table('user_description')->where('user_id', $this->user->id)->find_one();
        if (!$dbDesc) {
            $dbDesc = ORM::for_table('user_description')->create();
            $dbDesc->user_id = $this->user->id;
        }
        $dbDesc->description = $this->request->get('rpg');
        $dbDesc->descriptionHtml = $parsedBb;
        $dbDesc->save();
        return $this->response->redirect(getUrl('user/settings'));
    }

    public function getLogout()
    {
        $this->session->remove('user_id');
        return $this->response->redirect(getUrl(''));
    }
}
