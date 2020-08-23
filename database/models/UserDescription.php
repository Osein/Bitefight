<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string description
 * @property string descriptionHtml
 */
class UserDescription extends Model
{

	public $timestamps = false;

	protected $table = 'user_description';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return UserDescription
	 */
	public function setId(int $id): UserDescription
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
	 * @return UserDescription
	 */
	public function setUserId(int $user_id): UserDescription
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return UserDescription
	 */
	public function setDescription(string $description): UserDescription
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescriptionHtml(): string
	{
		return $this->descriptionHtml;
	}

	/**
	 * @param string $descriptionHtml
	 * @return UserDescription
	 */
	public function setDescriptionHtml(string $descriptionHtml): UserDescription
	{
		$this->descriptionHtml = $descriptionHtml;
		return $this;
	}

}
