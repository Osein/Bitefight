<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string setting
 * @property int folder_id
 * @property bool mark_read
 */
class UserMessageSettings extends Model
{

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

	const FOLDER_ID_DELETE_IMMEDIATELY = -2;
	const FOLDER_ID_INBOX = 0;


	public $timestamps = false;

	protected $table = 'user_message_settings';

	/**
	 * @param string $type
	 * @return string
	 */
	public static function getMessageSettingStringByType($type) {
		return __('user.message_setting_string_'.$type);
	}

	/**
	 * @return array
	 */
	public static function getUserSettings() {
		$settings = UserMessageSettings::where('user_id', user()->getId())->get();
		$settingIds = array();

		foreach ($settings as $setting) {
			/**
			 * @var UserMessageSettings $setting
			 */
			$settingViewId = self::getMessageSettingViewIdFromType($setting->getSetting());

			if($settingViewId > 0) {
				$settingIds[$settingViewId] = $setting;
			}
		}

		foreach (self::getSettingConstants() as $key => $val) {
			if(!array_key_exists($val, $settingIds)) {
				$settingObj = new UserMessageSettings;
				$settingObj->setUserId(user()->getId());
				$settingObj->setSetting(strtolower($key));
				$settingObj->setFolderId(0);
				$settingObj->setMarkRead(0);
				$settingIds[$val] = $settingObj;
			}
		}

		return $settingIds;
	}

	/**
	 * @param int $type
	 * @param User $user
	 * @return UserMessageSettings
	 */
	public static function getUserSetting($type, $user = null)
	{
		$userId = $user == null ? user()->getId() : $user->getId();
		$key = self::getMessageSettingTypeFromSettingViewId($type);

		$setting = UserMessageSettings::where('user_id', $userId)
			->where('setting', $key)
			->first();

		if(!$setting) {
			$setting = new UserMessageSettings;
			$setting->setUserId($userId);
			$setting->setSetting(strtolower($key));
			$setting->setFolderId(0);
			$setting->setMarkRead(0);
		}

		return $setting;
	}

	/**
	 * @param string $settingType
	 * @return int
	 */
	public static function getMessageSettingViewIdFromType($settingType) {
		$settings = self::getSettingConstants();

		foreach ($settings as $name => $val) {
			if(strtolower($name) == $settingType) {
				return $val;
			}
		}

		return 0;
	}

	/**
	 * @param int $settingId
	 * @return string
	 */
	public static function getMessageSettingTypeFromSettingViewId($settingId) {
		$settings = self::getSettingConstants();
		$key = array_search($settingId, $settings);
		($key !== false) ? $retVal = $key : $retVal = '';
		return $retVal;
	}

	/**
	 * @return array
	 */
	private static function getSettingConstants()
	{
		$class = new \ReflectionClass(__CLASS__);
		return array_filter($class->getConstants(), function($val) {
			return $val >= 2 && $val <= 240;
		});
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserMessageSettings
	 */
	public function setId(int $id): UserMessageSettings
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 * @return UserMessageSettings
	 */
	public function setUserId(int $user_id): UserMessageSettings
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSetting(): string
	{
		return $this->setting;
	}

	/**
	 * @param string $setting
	 * @return UserMessageSettings
	 */
	public function setSetting(string $setting): UserMessageSettings
	{
		$this->setting = $setting;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFolderId(): int
	{
		return $this->folder_id;
	}

	/**
	 * @param int $folder_id
	 * @return UserMessageSettings
	 */
	public function setFolderId(int $folder_id): UserMessageSettings
	{
		$this->folder_id = $folder_id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isMarkRead(): bool
	{
		return $this->mark_read;
	}

	/**
	 * @param bool $mark_read
	 * @return UserMessageSettings
	 */
	public function setMarkRead(bool $mark_read): UserMessageSettings
	{
		$this->mark_read = $mark_read;
		return $this;
	}

}
