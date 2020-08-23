<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static Clan find($id)
 *
 * @property int id
 * @property int race
 * @property string name
 * @property string tag
 * @property int logo_bg
 * @property int website_counter
 * @property int capital
 * @property int logo_sym
 * @property string website
 * @property int website_set_by
 * @property int stufe
 * @property int found_date
 */
class Clan extends Model
{

	public $timestamps = false;

	protected $table = 'clan';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Clan
	 */
	public function setId(int $id): Clan
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRace(): int
	{
		return $this->race;
	}

	/**
	 * @param int $race
	 * @return Clan
	 */
	public function setRace(int $race): Clan
	{
		$this->race = $race;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Clan
	 */
	public function setName(string $name): Clan
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTag(): string
	{
		return $this->tag;
	}

	/**
	 * @param string $tag
	 * @return Clan
	 */
	public function setTag(string $tag): Clan
	{
		$this->tag = $tag;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLogoBg(): int
	{
		return $this->logo_bg;
	}

	/**
	 * @param int $logo_bg
	 * @return Clan
	 */
	public function setLogoBg(int $logo_bg): Clan
	{
		$this->logo_bg = $logo_bg;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWebsiteCounter(): int
	{
		return $this->website_counter;
	}

	/**
	 * @param int $website_counter
	 * @return Clan
	 */
	public function setWebsiteCounter(int $website_counter): Clan
	{
		$this->website_counter = $website_counter;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCapital(): int
	{
		return $this->capital;
	}

	/**
	 * @param int $capital
	 * @return Clan
	 */
	public function setCapital(int $capital): Clan
	{
		$this->capital = $capital;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLogoSym(): int
	{
		return $this->logo_sym;
	}

	/**
	 * @param int $logo_sym
	 * @return Clan
	 */
	public function setLogoSym(int $logo_sym): Clan
	{
		$this->logo_sym = $logo_sym;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWebsite(): string
	{
		return $this->website;
	}

	/**
	 * @param string $website
	 * @return Clan
	 */
	public function setWebsite(string $website): Clan
	{
		$this->website = $website;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWebsiteSetBy(): int
	{
		return $this->website_set_by;
	}

	/**
	 * @param int $website_set_by
	 * @return Clan
	 */
	public function setWebsiteSetBy(int $website_set_by): Clan
	{
		$this->website_set_by = $website_set_by;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStufe(): int
	{
		return $this->stufe;
	}

	/**
	 * @param int $stufe
	 * @return Clan
	 */
	public function setStufe(int $stufe): Clan
	{
		$this->stufe = $stufe;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFoundDate(): int
	{
		return $this->found_date;
	}

	/**
	 * @param int $found_date
	 * @return Clan
	 */
	public function setFoundDate(int $found_date): Clan
	{
		$this->found_date = $found_date;
		return $this;
	}

}
