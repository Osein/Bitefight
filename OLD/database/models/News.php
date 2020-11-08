<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int added_user_id
 * @property string title
 * @property int added_time
 * @property string message
 */
class News extends Model
{

	public $timestamps = false;

	protected $table = 'news';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return News
	 */
	public function setId(int $id): News
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAddedUserId(): int
	{
		return $this->added_user_id;
	}

	/**
	 * @param int $added_user_id
	 * @return News
	 */
	public function setAddedUserId(int $added_user_id): News
	{
		$this->added_user_id = $added_user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return News
	 */
	public function setTitle(string $title): News
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAddedTime(): int
	{
		return $this->added_time;
	}

	/**
	 * @param int $added_time
	 * @return News
	 */
	public function setAddedTime(int $added_time): News
	{
		$this->added_time = $added_time;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 * @return News
	 */
	public function setMessage(string $message): News
	{
		$this->message = $message;
		return $this;
	}

}
