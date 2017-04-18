<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 18/04/17
 * Time: 20:55
 */

namespace Bitefight\Models;

class ClanRank extends BaseModel
{

    protected $id;

    protected $clan_id;

    protected $rank_name;

    protected $read_message;

    protected $write_message;

    protected $read_clan_message;

    protected $add_members;

    protected $delete_message;

    protected $send_clan_message;

    protected $spend_gold;

    protected $war_minister;

    protected $vocalise_ritual;

    public static function checkColumnExists($column_name)
    {
        return property_exists(__CLASS__, $column_name);
    }

}