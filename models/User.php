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
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $pass;

    /**
     * @var string
     */
    public $mail;

    /**
     * @var int
     */
    public $race;

    /**
     * @var int
     */
    public $gender;

    /**
     * @var int
     */
    public $image_type;

    /**
     * @var int
     */
    public $clan_id;

    /**
     * @var int
     */
    public $clan_rank;

    /**
     * @var int
     */
    public $exp;

    /**
     * @var int
     */
    public $battle_value;

    /**
     * @var int
     */
    public $gold;

    /**
     * @var int
     */
    public $hellstone;

    /**
     * @var int
     */
    public $fragment;

    /**
     * @var float
     */
    public $ap_now;

    /**
     * @var int
     */
    public $ap_max;

    /**
     * @var float
     */
    public $hp_now;

    /**
     * @var int
     */
    public $hp_max;

    /**
     * @var int
     */
    public $str;

    /**
     * @var int
     */
    public $def;

    /**
     * @var int
     */
    public $dex;

    /**
     * @var int
     */
    public $end;

    /**
     * @var int
     */
    public $cha;

    /**
     * @var int
     */
    public $s_booty;

    /**
     * @var int
     */
    public $s_fight;

    /**
     * @var int
     */
    public $s_victory;

    /**
     * @var int
     */
    public $s_defeat;

    /**
     * @var int
     */
    public $s_draw;

    /**
     * @var int
     */
    public $s_gold_captured;

    /**
     * @var int
     */
    public $s_gold_lost;

    /**
     * @var int
     */
    public $s_damage_caused;

    /**
     * @var int
     */
    public $s_hp_lost;

    /**
     * @var int
     */
    public $talent_points;

    /**
     * @var int
     */
    public $talent_resets;

    /**
     * @var int
     */
    public $h_treasure;

    /**
     * @var int
     */
    public $h_royal;

    /**
     * @var int
     */
    public $h_gargoyle;

    /**
     * @var int
     */
    public $h_book;

    /**
     * @var int
     */
    public $h_domicile;

    /**
     * @var int
     */
    public $h_wall;

    /**
     * @var int
     */
    public $h_path;

    /**
     * @var int
     */
    public $h_land;

    /**
     * @var int
     */
    public $last_activity;

    /**
     * @var int
     */
    public $last_update;

    /**
     * @var int
     */
    public $name_change;
}