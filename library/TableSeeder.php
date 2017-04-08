<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/04/17
 * Time: 15:06
 */

namespace Bitefight\Library;

use Bitefight\Models\User;
use Faker\Factory;
use Faker\Generator;
use ORM;

class TableSeeder
{

    /**
     * @var Generator $faker
     */
    protected static $faker;

    public static function seedTables()
    {
        if(!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

        self::populateUserTable();
        self::populateClanTable();
    }

    public static function populateClanTable($count = 500)
    {
        for($i = 0; $i < $count; $i++) {
            $clan = ORM::for_table('clan')
                ->create();

            $clan->race = rand(1, 2);
            $clan->name = self::$faker->userName;
            $clan->tag = self::$faker->word;
            $clan->logo_bg = rand(1, 10);
            $clan->logo_sym = rand(1, 24);
            $clan->stufe = rand(1, 18);

            $clan->save();

            $user = ORM::for_table('user')
                ->where('clan_id', 0)
                ->find_one();

            if(!$user) {
                self::populateUserTable(1);

                $user = ORM::for_table('user')
                    ->where('clan_id', 0)
                    ->find_one();
            }

            $user->clan_id = $clan->id;
            $user->clan_rank = 1;

            $user->save();
        }
    }

    public static function populateUserTable($count = 500)
    {
        for($i = 0; $i < $count; $i++) {
            /**
             * @var User $user
             */
            $user = ORM::for_table('user')->create();
            $user->name = self::$faker->userName;
            $user->password = self::$faker->password;
            $user->mail = self::$faker->email;
            $user->race = rand(1, 2);
            $user->gender = rand(1, 2);
            $user->image_type = rand(1, 10);
            $user->exp = rand(1, 1000000);
            $user->battle_value = rand(1, 1000000);
            $user->gold = rand(1, 1000000);
            $user->hellstone = rand(1, 1000000);
            $user->fragment = rand(1, 1000000);
            $user->str = rand(1, 1000000);
            $user->def = rand(1, 1000000);
            $user->dex = rand(1, 1000000);
            $user->end = rand(1, 1000000);
            $user->cha = rand(1, 1000000);
            $user->s_booty = rand(1, 1000000);
            $user->s_damage_caused = rand(1, 1000000);
            $user->s_defeat = rand(1, 1000000);
            $user->s_draw = rand(1, 1000000);
            $user->s_fight = rand(1, 1000000);
            $user->s_gold_captured = rand(1, 1000000);
            $user->s_gold_lost = rand(1, 1000000);
            $user->s_hp_lost = rand(1, 1000000);
            $user->s_victory = rand(1, 1000000);
            $user->save();
        }
    }

}