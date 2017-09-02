<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 01/09/17
 * Time: 23:17
 */

namespace Bitefight\Models;

use ORM;

class UserMission extends BaseModel
{

    const TYPE_HUMAN_HUNT = 1;

    public static function replaceOpenMissions($user)
    {
        ORM::raw_execute('DELETE FROM user_mission WHERE user_id = ? AND accepted = 0', [$user->id]);
        ORM::raw_execute('UPDATE user_mission SET day = ? WHERE user_id = ? AND accepted = 1', [date('d'), $user->id]);
        $delete_count = 10 - ORM::for_table('user_mission')->where('user_id', $user->id)->count();
        return self::generateUserMissions($user, $delete_count);
    }

    public static function generateUserMissions($user, $count = 10)
    {
        $missions = [];

        for($i = 1; $i <= $count; $i++) {
            $huntType = self::TYPE_HUMAN_HUNT;

            if($huntType == self::TYPE_HUMAN_HUNT) {
                $mission = ORM::for_table('user_mission')->create();
                $mission->user_id = $user->id;
                $mission->type = self::TYPE_HUMAN_HUNT;
                $counts = [10, 20, 40];
                $baseGold = 1800;

                $mission_count = array_rand($counts);
                $mission->count = $counts[$mission_count];
                $gold = $baseGold * ($count + 1) * getLevel($user->exp);

                $healChance = mt_rand(1, 100);
                if($healChance < 11) {
                    $mission->heal = 100;
                    $gold = $gold * 0.87;
                }

                $fragChance = mt_rand(1, 100);
                if($fragChance < 8) {
                    $fragAmount = mt_rand(2, 6);
                    $gold = $gold * (0.87 + (0.01 * (7 - $fragAmount)));
                    $mission->frag = $fragAmount;
                }

                $apChance = mt_rand(1, 100);
                if($apChance < 6) {
                    $apAmount = mt_rand(2, 4);
                    $gold = $gold * (0.80 + (0.1 * (9 - ($apAmount * 0.02))));
                    $mission->ap = $apAmount;
                }

                $timeChance = mt_rand(1, 100);
                if($timeChance < 13) {
                    $timeArr = [12, 10, 8, 6, 4, 2, 1];
                    $timeKey = array_rand($timeArr);
                    $mission->time = $timeArr[$timeKey];
                    $gold *= $timeKey + 1;
                }

                $specialChance = mt_rand(1, 100);
                if($specialChance < 10) {
                    $specialNo = mt_rand(1, 5);
                    $mission->special = $specialNo;
                    $gold  = pow($gold, 0.87);
                }

                $mission->gold = $gold;
                $mission->day = date('d');
                $mission->progress = 0;
                $mission->save();
                $missions[] = $mission;
            }
        }

        return $missions;
    }

}