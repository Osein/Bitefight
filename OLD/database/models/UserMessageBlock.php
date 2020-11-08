<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int blocked_id
 */
class UserMessageBlock extends Model
{

	public $timestamps = false;

	protected $table = 'user_message_block';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserMessageBlock
	 */
	public function setId(int $id): UserMessageBlock
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
	 * @return UserMessageBlock
	 */
	public function setUserId(int $user_id): UserMessageBlock
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBlockedId(): int
	{
		return $this->blocked_id;
	}

	/**
	 * @param int $blocked_id
	 * @return UserMessageBlock
	 */
	public function setBlockedId(int $blocked_id): UserMessageBlock
	{
		$this->blocked_id = $blocked_id;
		return $this;
	}

}
