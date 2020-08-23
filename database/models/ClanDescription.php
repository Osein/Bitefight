<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int clan_id
 * @property string description
 * @property string descriptionHtml
 */
class ClanDescription extends Model
{

	public $timestamps = false;

	protected $table = 'clan_description';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ClanDescription
	 */
	public function setId(int $id): ClanDescription
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
	 * @return ClanDescription
	 */
	public function setClanId(int $clan_id): ClanDescription
	{
		$this->clan_id = $clan_id;
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
	 * @return ClanDescription
	 */
	public function setDescription(string $description): ClanDescription
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
	 * @return ClanDescription
	 */
	public function setDescriptionHtml(string $descriptionHtml): ClanDescription
	{
		$this->descriptionHtml = $descriptionHtml;
		return $this;
	}

}
