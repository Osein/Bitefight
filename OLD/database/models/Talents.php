<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property bool active
 * @property int paid
 * @property int duration
 * @property int attack
 * @property int eattack
 * @property int str
 * @property int def
 * @property int dex
 * @property int end
 * @property int cha
 * @property int estr
 * @property int edef
 * @property int edex
 * @property int eend
 * @property int echa
 * @property int hpbonus
 * @property int regen
 * @property int sbschc
 * @property int sbscdmg
 * @property int sbsctlnt
 * @property int sbnshc
 * @property int sbnsdmg
 * @property int sbnstlnt
 * @property int ebschc
 * @property int ebscdmg
 * @property int ebsctlnt
 * @property int ebnshc
 * @property int ebnsdmg
 * @property int ebnstlnt
 */
class Talents extends Model
{

	public $timestamps = false;

	protected $table = 'talents';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Talents
	 */
	public function setId(int $id): Talents
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * @param bool $active
	 * @return Talents
	 */
	public function setActive(bool $active): Talents
	{
		$this->active = $active;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPaid(): int
	{
		return $this->paid;
	}

	/**
	 * @param int $paid
	 * @return Talents
	 */
	public function setPaid(int $paid): Talents
	{
		$this->paid = $paid;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDuration(): int
	{
		return $this->duration;
	}

	/**
	 * @param int $duration
	 * @return Talents
	 */
	public function setDuration(int $duration): Talents
	{
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAttack(): int
	{
		return $this->attack;
	}

	/**
	 * @param int $attack
	 * @return Talents
	 */
	public function setAttack(int $attack): Talents
	{
		$this->attack = $attack;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEattack(): int
	{
		return $this->eattack;
	}

	/**
	 * @param int $eattack
	 * @return Talents
	 */
	public function setEattack(int $eattack): Talents
	{
		$this->eattack = $eattack;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStr(): int
	{
		return $this->str;
	}

	/**
	 * @param int $str
	 * @return Talents
	 */
	public function setStr(int $str): Talents
	{
		$this->str = $str;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDef(): int
	{
		return $this->def;
	}

	/**
	 * @param int $def
	 * @return Talents
	 */
	public function setDef(int $def): Talents
	{
		$this->def = $def;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDex(): int
	{
		return $this->dex;
	}

	/**
	 * @param int $dex
	 * @return Talents
	 */
	public function setDex(int $dex): Talents
	{
		$this->dex = $dex;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEnd(): int
	{
		return $this->end;
	}

	/**
	 * @param int $end
	 * @return Talents
	 */
	public function setEnd(int $end): Talents
	{
		$this->end = $end;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCha(): int
	{
		return $this->cha;
	}

	/**
	 * @param int $cha
	 * @return Talents
	 */
	public function setCha(int $cha): Talents
	{
		$this->cha = $cha;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEstr(): int
	{
		return $this->estr;
	}

	/**
	 * @param int $estr
	 * @return Talents
	 */
	public function setEstr(int $estr): Talents
	{
		$this->estr = $estr;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEdef(): int
	{
		return $this->edef;
	}

	/**
	 * @param int $edef
	 * @return Talents
	 */
	public function setEdef(int $edef): Talents
	{
		$this->edef = $edef;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEdex(): int
	{
		return $this->edex;
	}

	/**
	 * @param int $edex
	 * @return Talents
	 */
	public function setEdex(int $edex): Talents
	{
		$this->edex = $edex;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEend(): int
	{
		return $this->eend;
	}

	/**
	 * @param int $eend
	 * @return Talents
	 */
	public function setEend(int $eend): Talents
	{
		$this->eend = $eend;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEcha(): int
	{
		return $this->echa;
	}

	/**
	 * @param int $echa
	 * @return Talents
	 */
	public function setEcha(int $echa): Talents
	{
		$this->echa = $echa;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbschc(): int
	{
		return $this->sbschc;
	}

	/**
	 * @param int $sbschc
	 * @return Talents
	 */
	public function setSbschc(int $sbschc): Talents
	{
		$this->sbschc = $sbschc;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbscdmg(): int
	{
		return $this->sbscdmg;
	}

	/**
	 * @param int $sbscdmg
	 * @return Talents
	 */
	public function setSbscdmg(int $sbscdmg): Talents
	{
		$this->sbscdmg = $sbscdmg;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbsctlnt(): int
	{
		return $this->sbsctlnt;
	}

	/**
	 * @param int $sbsctlnt
	 * @return Talents
	 */
	public function setSbsctlnt(int $sbsctlnt): Talents
	{
		$this->sbsctlnt = $sbsctlnt;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbnshc(): int
	{
		return $this->sbnshc;
	}

	/**
	 * @param int $sbnshc
	 * @return Talents
	 */
	public function setSbnshc(int $sbnshc): Talents
	{
		$this->sbnshc = $sbnshc;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbnsdmg(): int
	{
		return $this->sbnsdmg;
	}

	/**
	 * @param int $sbnsdmg
	 * @return Talents
	 */
	public function setSbnsdmg(int $sbnsdmg): Talents
	{
		$this->sbnsdmg = $sbnsdmg;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSbnstlnt(): int
	{
		return $this->sbnstlnt;
	}

	/**
	 * @param int $sbnstlnt
	 * @return Talents
	 */
	public function setSbnstlnt(int $sbnstlnt): Talents
	{
		$this->sbnstlnt = $sbnstlnt;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbschc(): int
	{
		return $this->ebschc;
	}

	/**
	 * @param int $ebschc
	 * @return Talents
	 */
	public function setEbschc(int $ebschc): Talents
	{
		$this->ebschc = $ebschc;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbscdmg(): int
	{
		return $this->ebscdmg;
	}

	/**
	 * @param int $ebscdmg
	 * @return Talents
	 */
	public function setEbscdmg(int $ebscdmg): Talents
	{
		$this->ebscdmg = $ebscdmg;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbsctlnt(): int
	{
		return $this->ebsctlnt;
	}

	/**
	 * @param int $ebsctlnt
	 * @return Talents
	 */
	public function setEbsctlnt(int $ebsctlnt): Talents
	{
		$this->ebsctlnt = $ebsctlnt;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbnshc(): int
	{
		return $this->ebnshc;
	}

	/**
	 * @param int $ebnshc
	 * @return Talents
	 */
	public function setEbnshc(int $ebnshc): Talents
	{
		$this->ebnshc = $ebnshc;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbnsdmg(): int
	{
		return $this->ebnsdmg;
	}

	/**
	 * @param int $ebnsdmg
	 * @return Talents
	 */
	public function setEbnsdmg(int $ebnsdmg): Talents
	{
		$this->ebnsdmg = $ebnsdmg;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEbnstlnt(): int
	{
		return $this->ebnstlnt;
	}

	/**
	 * @param int $ebnstlnt
	 * @return Talents
	 */
	public function setEbnstlnt(int $ebnstlnt): Talents
	{
		$this->ebnstlnt = $ebnstlnt;
		return $this;
	}

}
