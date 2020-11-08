<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int talent_id
 */
class UserTalent extends Model
{
    protected $table = 'user_talents';

    public $timestamps = false;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserTalent
	 */
	public function setId(int $id): UserTalent
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
	 * @return UserTalent
	 */
	public function setUserId(int $user_id): UserTalent
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTalentId(): int
	{
		return $this->talent_id;
	}

	/**
	 * @param int $talent_id
	 * @return UserTalent
	 */
	public function setTalentId(int $talent_id): UserTalent
	{
		$this->talent_id = $talent_id;
		return $this;
	}

}
