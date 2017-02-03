<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 03/02/17
 * Time: 19:48
 */

namespace Models;

/**
 * Class User
 * @package Models
 */
class User extends \ORM
{
    public $id;
    public $name;
    public $pass;
    public $mail;
    public $race;
    public $gender;
    public $image_type;
    public $clan_id;
    public $clan_rank;
    public $exp;
    public $battle_value;
    public $gold;
    public $hellstone;
    public $fragment;
    public $ap_now;
    public $ap_max;
    public $hp_now;
    public $hp_max;
    public $str;
    public $def;
    public $dex;
    public $end;
    public $cha;
    public $s_booty;
    public $s_fight;
    public $s_victory;
    public $s_defeat;
    public $s_draw;
    public $s_gold_captured;
    public $s_gold_lost;
    public $s_damage_caused;
    public $s_hp_lost;
    public $talent_points;
    public $talent_resets;
    public $h_treasure;
    public $h_royal;
    public $h_gargoyle;
    public $h_book;
    public $h_domicile;
    public $h_wall;
    public $h_path;
    public $h_land;
    public $last_activity;
    public $last_update;
    public $name_change;
}