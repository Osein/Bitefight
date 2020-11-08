<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string note
 */
class UserNote extends Model
{

	public $timestamps = false;

	protected $table = 'user_note';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserNote
	 */
	public function setId(int $id): UserNote
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
	 * @return UserNote
	 */
	public function setUserId(int $user_id): UserNote
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
	 * @return UserNote
	 */
	public function setNote(string $note): UserNote
	{
		$this->note = $note;
		return $this;
	}

}
