<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int stern
 * @property int model
 * @property string name
 * @property int level
 * @property int gcost
 * @property int slcost
 * @property int scost
 * @property int str
 * @property int def
 * @property int dex
 * @property int end
 * @property int cha
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
 * @property int apup
 * @property int cooldown
 * @property int duration
 */
class Item extends Model
{

	public $timestamps = false;

	protected $table = 'items';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Item
	 */
	public function setId(int $id): Item
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStern(): int
	{
		return $this->stern;
	}

	/**
	 * @param int $stern
	 * @return Item
	 */
	public function setStern(int $stern): Item
	{
		$this->stern = $stern;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getModel(): int
	{
		return $this->model;
	}

	/**
	 * @param int $model
	 * @return Item
	 */
	public function setModel(int $model): Item
	{
		$this->model = $model;
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
	 * @return Item
	 */
	public function setName(string $name): Item
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLevel(): int
	{
		return $this->level;
	}

	/**
	 * @param int $level
	 * @return Item
	 */
	public function setLevel(int $level): Item
	{
		$this->level = $level;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGcost(): int
	{
		return $this->gcost;
	}

	/**
	 * @param int $gcost
	 * @return Item
	 */
	public function setGcost(int $gcost): Item
	{
		$this->gcost = $gcost;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSlcost(): int
	{
		return $this->slcost;
	}

	/**
	 * @param int $slcost
	 * @return Item
	 */
	public function setSlcost(int $slcost): Item
	{
		$this->slcost = $slcost;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getScost(): int
	{
		return $this->scost;
	}

	/**
	 * @param int $scost
	 * @return Item
	 */
	public function setScost(int $scost): Item
	{
		$this->scost = $scost;
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
	 * @return Item
	 */
	public function setStr(int $str): Item
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
	 * @return Item
	 */
	public function setDef(int $def): Item
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
	 * @return Item
	 */
	public function setDex(int $dex): Item
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
	 * @return Item
	 */
	public function setEnd(int $end): Item
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
	 * @return Item
	 */
	public function setCha(int $cha): Item
	{
		$this->cha = $cha;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHpbonus(): int
	{
		return $this->hpbonus;
	}

	/**
	 * @param int $hpbonus
	 * @return Item
	 */
	public function setHpbonus(int $hpbonus): Item
	{
		$this->hpbonus = $hpbonus;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRegen(): int
	{
		return $this->regen;
	}

	/**
	 * @param int $regen
	 * @return Item
	 */
	public function setRegen(int $regen): Item
	{
		$this->regen = $regen;
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
	 * @return Item
	 */
	public function setSbschc(int $sbschc): Item
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
	 * @return Item
	 */
	public function setSbscdmg(int $sbscdmg): Item
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
	 * @return Item
	 */
	public function setSbsctlnt(int $sbsctlnt): Item
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
	 * @return Item
	 */
	public function setSbnshc(int $sbnshc): Item
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
	 * @return Item
	 */
	public function setSbnsdmg(int $sbnsdmg): Item
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
	 * @return Item
	 */
	public function setSbnstlnt(int $sbnstlnt): Item
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
	 * @return Item
	 */
	public function setEbschc(int $ebschc): Item
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
	 * @return Item
	 */
	public function setEbscdmg(int $ebscdmg): Item
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
	 * @return Item
	 */
	public function setEbsctlnt(int $ebsctlnt): Item
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
	 * @return Item
	 */
	public function setEbnshc(int $ebnshc): Item
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
	 * @return Item
	 */
	public function setEbnsdmg(int $ebnsdmg): Item
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
	 * @return Item
	 */
	public function setEbnstlnt(int $ebnstlnt): Item
	{
		$this->ebnstlnt = $ebnstlnt;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getApup(): int
	{
		return $this->apup;
	}

	/**
	 * @param int $apup
	 * @return Item
	 */
	public function setApup(int $apup): Item
	{
		$this->apup = $apup;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCooldown(): int
	{
		return $this->cooldown;
	}

	/**
	 * @param int $cooldown
	 * @return Item
	 */
	public function setCooldown(int $cooldown): Item
	{
		$this->cooldown = $cooldown;
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
	 * @return Item
	 */
	public function setDuration(int $duration): Item
	{
		$this->duration = $duration;
		return $this;
	}

}
