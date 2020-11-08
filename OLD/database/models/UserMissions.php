<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int type
 * @property int gold
 * @property int frag
 * @property int ap
 * @property int time
 * @property int special
 * @property int day
 * @property int progress
 * @property int count
 * @property int heal
 * @property int accepted
 * @property int status
 * @property int accepted_time
 */
class UserMissions extends Model
{

	const TYPE_HUMAN_HUNT = 1;

	public $timestamps = false;

	protected $table = 'user_missions';

    /**
     * @return int
     */
    public function getAcceptedTime(): int
    {
        return $this->accepted_time;
    }

    /**
     * @param int $accepted_time
     */
    public function setAcceptedTime(int $accepted_time)
    {
        $this->accepted_time = $accepted_time;
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
	 * @return UserMissions
	 */
	public function setId(int $id): UserMissions
	{
		$this->id = $id;
		return $this;
	}

    /**
     * @return int
     */
    public function getAccepted(): int
    {
        return $this->accepted;
    }

    /**
     * @param int $accepted
     */
    public function setAccepted(int $accepted)
    {
        $this->accepted = $accepted;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
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
	 * @return UserMissions
	 */
	public function setUserId(int $user_id): UserMissions
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 * @return UserMissions
	 */
	public function setType(int $type): UserMissions
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->count;
	}

	/**
	 * @param int $count
	 * @return UserMissions
	 */
	public function setCount(int $count): UserMissions
	{
		$this->count = $count;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGold(): int
	{
		return $this->gold;
	}

	/**
	 * @param int $gold
	 * @return UserMissions
	 */
	public function setGold(int $gold): UserMissions
	{
		$this->gold = $gold;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFrag(): int
	{
		return $this->frag;
	}

	/**
	 * @param int $frag
	 * @return UserMissions
	 */
	public function setFrag(int $frag): UserMissions
	{
		$this->frag = $frag;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAp(): int
	{
		return $this->ap;
	}

	/**
	 * @param int $ap
	 * @return UserMissions
	 */
	public function setAp(int $ap): UserMissions
	{
		$this->ap = $ap;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHeal(): int
	{
		return $this->heal;
	}

	/**
	 * @param int $heal
	 * @return UserMissions
	 */
	public function setHeal(int $heal): UserMissions
	{
		$this->heal = $heal;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTime(): int
	{
		return $this->time;
	}

	/**
	 * @param int $time
	 * @return UserMissions
	 */
	public function setTime(int $time): UserMissions
	{
		$this->time = $time;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSpecial(): int
	{
		return $this->special;
	}

	/**
	 * @param int $special
	 * @return UserMissions
	 */
	public function setSpecial(int $special): UserMissions
	{
		$this->special = $special;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDay(): int
	{
		return $this->day;
	}

	/**
	 * @param int $day
	 * @return UserMissions
	 */
	public function setDay(int $day): UserMissions
	{
		$this->day = $day;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getProgress(): int
	{
		return $this->progress;
	}

	/**
	 * @param int $progress
	 * @return UserMissions
	 */
	public function setProgress(int $progress): UserMissions
	{
		$this->progress = $progress;
		return $this;
	}

	public static function replaceOpenMissions()
	{
		UserMissions::where('user_id', user()->getId())->where('accepted', 0)->delete();
		UserMissions::where('user_id', user()->getId())->where('accepted', 1)->update([
			'day' => date('d')
		]);
		$delete_count = 10 - UserMissions::where('user_id', user()->getId())->count();
		return self::generateUserMissions($delete_count);
	}

	public static function generateUserMissions($count = 10)
	{
		$missions = [];

		for($i = 1; $i <= $count; $i++) {
			$huntType = self::TYPE_HUMAN_HUNT;

			if($huntType == self::TYPE_HUMAN_HUNT) {
				$mission = new UserMissions;
				$mission->setUserId(user()->getId());
				$mission->setType(self::TYPE_HUMAN_HUNT);
				$counts = [10, 20, 40];
				$baseGold = 1800;

				$mission_count = array_rand($counts);
				$mission->setCount($counts[$mission_count]);
				$gold = $baseGold * ($count + 1) * getLevel(user()->getExp());

				$healChance = mt_rand(1, 100);
				if($healChance < 11) {
					$mission->setHeal(100);
					$gold = $gold * 0.87;
				}

				$fragChance = mt_rand(1, 100);
				if($fragChance < 8) {
					$fragAmount = mt_rand(2, 6);
					$gold = $gold * (0.87 + (0.01 * (7 - $fragAmount)));
					$mission->setFrag($fragAmount);
				}

				$apChance = mt_rand(1, 100);
				if($apChance < 6) {
					$apAmount = mt_rand(2, 4);
					$gold = $gold * (0.80 + (0.1 * (9 - ($apAmount * 0.02))));
					$mission->setAp($apAmount);
				}

				$timeChance = mt_rand(1, 100);
				if($timeChance < 13) {
					$timeArr = [12, 10, 8, 6, 4, 2, 1];
					$timeKey = array_rand($timeArr);
					$mission->setTime($timeArr[$timeKey]);
					$gold *= $timeKey + 1;
				}

				$specialChance = mt_rand(1, 100);
				if($specialChance < 10) {
					$specialNo = mt_rand(1, 5);
					$mission->setSpecial($specialNo);
					$gold  = pow($gold, 0.87);
				}

				$mission->setGold($gold);
				$mission->setDay(date('d'));
				$mission->setProgress(0);
				$mission->save();
				$missions[] = $mission;
			}
		}

		return $missions;
	}
}
