<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int clan_id
 * @property int user_id
 * @property string note
 * @property int application_time
 */
class ClanApplications extends Model
{

	public $timestamps = false;

	protected $table = 'clan_applications';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ClanApplications
	 */
	public function setId(int $id): ClanApplications
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
	 * @return ClanApplications
	 */
	public function setClanId(int $clan_id): ClanApplications
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
	 * @return ClanApplications
	 */
	public function setUserId(int $user_id): ClanApplications
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote(): string
	{
		return $this->note;
	}

	/**
	 * @param string $note
	 * @return ClanApplications
	 */
	public function setNote(string $note): ClanApplications
	{
		$this->note = $note;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getApplicationTime(): int
	{
		return $this->application_time;
	}

	/**
	 * @param int $application_time
	 * @return ClanApplications
	 */
	public function setApplicationTime(int $application_time): ClanApplications
	{
		$this->application_time = $application_time;
		return $this;
	}

}
