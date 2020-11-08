<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int item_id
 * @property int volume
 * @property bool equipped
 * @property int expire
 */
class UserItems extends Model
{

	public $timestamps = false;

	protected $table = 'user_items';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserItems
	 */
	public function setId(int $id): UserItems
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
	 * @return UserItems
	 */
	public function setUserId(int $user_id): UserItems
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getItemId(): int
	{
		return $this->item_id;
	}

	/**
	 * @param int $item_id
	 * @return UserItems
	 */
	public function setItemId(int $item_id): UserItems
	{
		$this->item_id = $item_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVolume(): int
	{
		return $this->volume;
	}

	/**
	 * @param int $volume
	 * @return UserItems
	 */
	public function setVolume(int $volume): UserItems
	{
		$this->volume = $volume;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEquipped(): bool
	{
		return $this->equipped;
	}

	/**
	 * @param bool $equipped
	 * @return UserItems
	 */
	public function setEquipped(bool $equipped): UserItems
	{
		$this->equipped = $equipped;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExpire(): int
	{
		return $this->expire;
	}

	/**
	 * @param int $expire
	 * @return UserItems
	 */
	public function setExpire(int $expire): UserItems
	{
		$this->expire = $expire;
		return $this;
	}

}
