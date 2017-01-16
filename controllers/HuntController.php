<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 16/01/17
 * Time: 15:49
 */

class HuntController extends GameController
{

    public function getHunt() {
        $this->view->menu_active = 'hunt';

        $this->view->hunt1Chance = $this->getHuntChance(1);
        $this->view->hunt2Chance = $this->getHuntChance(2);
        $this->view->hunt3Chance = $this->getHuntChance(3);
        $this->view->hunt4Chance = $this->getHuntChance(4);
        $this->view->hunt5Chance = $this->getHuntChance(5);
        $this->view->hunt1Exp = $this->getHuntExp(1);
        $this->view->hunt2Exp = $this->getHuntExp(2);
        $this->view->hunt3Exp = $this->getHuntExp(3);
        $this->view->hunt4Exp = $this->getHuntExp(4);
        $this->view->hunt5Exp = $this->getHuntExp(5);
        $this->view->hunt1Reward = $this->getHuntReward(1);
        $this->view->hunt2Reward = $this->getHuntReward(2);
        $this->view->hunt3Reward = $this->getHuntReward(3);
        $this->view->hunt4Reward = $this->getHuntReward(4);
        $this->view->hunt5Reward = $this->getHuntReward(5);

        $this->view->pick('hunt/index');
    }

    public function postHuntHuman() {
        $huntNo = $this->request->getPost('huntTo');
    }

    public function getHuntReward($huntNo)
    {
        $user_level = getLevel($this->user->exp);
        if($huntNo == 1) return ($this->user->cha*2)+($user_level*1)+450;
        if($huntNo == 2) return ($this->user->cha*3)+($user_level*2)+540;
        if($huntNo == 3) return ($this->user->cha*3)+($user_level*3)+609;
        if($huntNo == 4) return ($this->user->cha*4)+($user_level*4)+714;
        if($huntNo == 5) return ($this->user->cha*5)+($user_level*5)+860;
    }

    public function getHuntExp($huntNo)
    {
        $user_level = getLevel($this->user->exp);
        return ($huntNo+(ceil(pow($user_level, 0.1*$huntNo))));
    }

    public function getHuntChance($huntNo)
    {
        $user_level = getLevel($this->user->exp);

        if($huntNo == 1)
        {
            if($user_level < 75){
                return floor(($user_level*0.2)+75);}
            elseif($user_level > 74 && $user_level < 165){
                return floor(($user_level*0.1)+82.5);}
            elseif($user_level > 164){
                return 99;}
        }
        elseif($huntNo == 2)
        {
            if($user_level < 125){
                return floor(($user_level*0.2)+47);}
            elseif($user_level > 124 && $user_level < 289){
                return floor(($user_level*0.083)+72);}
            elseif($user_level > 288){
                return 96;}
        }
        elseif($huntNo == 3)
        {
            if($user_level < 225){
                return floor(($user_level*0.15)+32);}
            elseif($user_level > 224 && $user_level < 420){
                return floor(($user_level*0.065)+65.75);}
            elseif($user_level > 419){
                return 93;}
        }
        elseif($huntNo == 4)
        {
            if($user_level < 350){
                return floor(($user_level*0.09)+31);}
            elseif($user_level > 349 && $user_level < 600){
                return floor(($user_level*0.046)+62.5);}
            elseif($user_level > 599){
                return 90;}
        }
        else
        {
            if($user_level < 550){
                return floor(($user_level*0.06)+21);}
            elseif($user_level > 499 && $user_level < 850){
                return floor(($user_level*0.037)+54);}
            elseif($user_level > 749){
                return 85;}
        }
    }

}