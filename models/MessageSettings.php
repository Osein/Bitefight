<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 22/04/17
 * Time: 13:21
 */

namespace Bitefight\Models;

use Bitefight\Library\Translate;
use ORM;
use Phalcon\Di;
use ReflectionClass;

class MessageSettings
{

    private static $settings = [
        'WORK'               => 2,
        //'ATTACKED'           => 4,
        //'GOT_ATTACKED'       => 3,
        //'CLAN_WARS'          => 9,
        //'GROTTO'             => 50,
        //'ADVENTURE'          => 51,
        //'MISSION'            => 80,
        //'CLAN_FOUNDED'       => 100,
        //'LEFT_CLAN'          => 101,
        //'DISBANDED_CLAN'     => 102,
        //'CLAN_DISBANDED'     => 103,
        //'CLAN_MAIL'          => 20,
        //'CLAN_APP_REJECTED'  => 105,
        //'CLAN_APP_ACCEPTED'  => 106,
        //'CLAN_MEMBER_LEFT'   => 110,
        //'REPORT_ANSWER'      => 240,
    ];

    const WORK = 2;
    const ATTACKED = 4;
    const GOT_ATTACKED = 3;
    const CLAN_WARS = 9;
    const GROTTO = 50;
    const ADVENTURE = 51;
    const MISSION = 80;
    const CLAN_FOUNDED = 100;
    const LEFT_CLAN = 101;
    const DISBANDED_CLAN = 102;
    const CLAN_DISBANDED = 103;
    const CLAN_MAIL = 20;
    const CLAN_APP_REJECTED = 105;
    const CLAN_APP_ACCEPTED = 106;
    const CLAN_MEMBER_LEFT = 110;
    const REPORT_ANSWER = 240;

    const FOLDER_ID_DELETE_IMMEDIATELY = 0;
    const FOLDER_ID_INBOX = -1;

    /**
     * @param $type
     * @return bool|ORM|\stdClass
     */
    public static function getUserSetting($type)
    {
        $setting = ORM::for_table('user_message_settings')
            ->where('user_id', Di::getDefault()->get('session')->get('user_id'))
            ->where('setting', self::getMessageSettingTypeFromSettingViewId($type))
            ->find_one();

        if(!$setting) {
            $setting = new \stdClass();
            $setting->user_id = Di::getDefault()->get('session')->get('user_id');
            $setting->setting = strtolower($key);
            $setting->folder_id = 0;
            $setting->mark_read = 0;
            return $setting;
        } else {
            return $setting;
        }
    }

    /**
     * @return array
     */
    private static function get_setting_constants()
    {
        return self::$settings;
    }

    /**
     * @param int $settingId
     * @return string
     */
    public static function getMessageSettingTypeFromSettingViewId($settingId) {
        $settings = self::get_setting_constants();
        $key = array_search($settingId, $settings);
        ($key !== false) ? $retVal = $key : $retVal = '';
        return $retVal;
    }

    /**
     * @param string $settingType
     * @return int
     */
    public static function getMessageSettingViewIdFromType($settingType) {
        $settings = self::get_setting_constants();

        foreach ($settings as $name => $val) {
            if(strtolower($name) == $settingType) {
                return $val;
            }
        }
    }

    /**
     * @return array
     */
    public static function getUserSettings() {
        $settings = ORM::for_table('user_message_settings')
            ->where('user_id', Di::getDefault()->get('session')->get('user_id'))
            ->find_many();

        $settingIds = array();

        foreach ($settings as $setting) {
            $settingViewId = self::getMessageSettingViewIdFromType($setting->setting);

            if($settingViewId > 0) {
                $settingIds[$settingViewId] = $setting;
            }
        }

        foreach (self::get_setting_constants() as $key => $val) {
            if(!array_key_exists($val, $settingIds)) {
                $settingIds[$val] = new \stdClass();
                $settingIds[$val]->user_id = Di::getDefault()->get('session')->get('user_id');
                $settingIds[$val]->setting = strtolower($key);
                $settingIds[$val]->folder_id = 0;
                $settingIds[$val]->mark_read = 0;
            }
        }

        return $settingIds;
    }

    /**
     * @param string $type
     * @return string
     */
    public static function getMessageSettingStringByType($type) {
        return Translate::_('message_setting_string_'.$type);
    }
}