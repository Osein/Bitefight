<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string folder_name
 * @property int folder_order
 */
class UserMessageFolder extends Model
{

	public $timestamps = false;

	protected $table = 'user_message_folder';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserMessageFolder
	 */
	public function setId(int $id): UserMessageFolder
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
	 * @return UserMessageFolder
	 */
	public function setUserId(int $user_id): UserMessageFolder
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFolderName(): string
	{
		return $this->folder_name;
	}

	/**
	 * @param string $folder_name
	 * @return UserMessageFolder
	 */
	public function setFolderName(string $folder_name): UserMessageFolder
	{
		$this->folder_name = $folder_name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFolderOrder(): int
	{
		return $this->folder_order;
	}

	/**
	 * @param int $folder_order
	 * @return UserMessageFolder
	 */
	public function setFolderOrder(int $folder_order): UserMessageFolder
	{
		$this->folder_order = $folder_order;
		return $this;
	}

}
