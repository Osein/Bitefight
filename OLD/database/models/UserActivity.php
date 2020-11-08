<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int activity_type
 * @property int start_time
 * @property int end_time
 */
class UserActivity extends Model
{

	protected $table = 'user_activity';

	public $timestamps = false;

	const ACTIVITY_TYPE_CHURCH = 3;
	const ACTIVITY_TYPE_GRAVEYARD = 4;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserActivity
	 */
	public function setId(int $id): UserActivity
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
	 * @return UserActivity
	 */
	public function setUserId(int $user_id): UserActivity
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getActivityType(): int
	{
		return $this->activity_type;
	}

	/**
	 * @param int $activity_type
	 * @return UserActivity
	 */
	public function setActivityType(int $activity_type): UserActivity
	{
		$this->activity_type = $activity_type;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStartTime(): int
	{
		return $this->start_time;
	}

	/**
	 * @param int $start_time
	 * @return UserActivity
	 */
	public function setStartTime(int $start_time): UserActivity
	{
		$this->start_time = $start_time;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEndTime(): int
	{
		return $this->end_time;
	}

	/**
	 * @param int $end_time
	 * @return UserActivity
	 */
	public function setEndTime(int $end_time): UserActivity
	{
		$this->end_time = $end_time;
		return $this;
	}

}
