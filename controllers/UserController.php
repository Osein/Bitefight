<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 14/01/17
 * Time: 03:26
 */

class UserController extends GameController
{

    public function getProfile() {
        $highscorePosition = ORM::for_table('user')
            ->where_gt('s_booty', $this->user->s_booty)
            ->count() + 1;

        $this->view->highscorePosition = $highscorePosition;

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

    public function postTrainingUp() {


        return $this->response->redirect(getUrl('user/profile#tabs-2'));
    }

    public function getProfileLogo() {
        $this->view->pick('user/logo');
    }

    public function postProfileLogo() {
        $gender = $this->request->getPost('gender');
        $type = $this->request->getPost('type');

        $this->user->gender = $gender;
        $this->user->image_type = $type;
        $this->user->save();

        return $this->response->redirect(getUrl('user/profile'));
    }

    public function getNews() {
        $this->view->menu_active = 'news';
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

}