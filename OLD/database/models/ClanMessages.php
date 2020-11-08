<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int clan_id
 * @property int user_id
 * @property string clan_message
 * @property int message_time
 */
class ClanMessages extends Model
{

	public $timestamps = false;

	protected $table = 'clan_message';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ClanMessages
	 */
	public function setId(int $id): ClanMessages
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getClanId(): int
	{
		return $this->clan_id;
	}

	/**
	 * @param int $clan_id
	 * @return ClanMessages
	 */
	public function setClanId(int $clan_id): ClanMessages
	{
		$this->clan_id = $clan_id;
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
	 * @return ClanMessages
	 */
	public function setUserId(int $user_id): ClanMessages
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getClanMessage(): string
	{
		return $this->clan_message;
	}

	/**
	 * @param string $clan_message
	 * @return ClanMessages
	 */
	public function setClanMessage(string $clan_message): ClanMessages
	{
		$this->clan_message = $clan_message;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMessageTime(): int
	{
		return $this->message_time;
	}

	/**
	 * @param int $message_time
	 * @return ClanMessages
	 */
	public function setMessageTime(int $message_time): ClanMessages
	{
		$this->message_time = $message_time;
		return $this;
	}

}
