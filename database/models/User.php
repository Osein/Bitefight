<?php

namespace Database\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property string rememberToken
 * @property int race
 * @property int gender
 * @property int image_type
 * @property int clan_id
 * @property int clan_rank
 * @property int clan_dtime
 * @property int clan_btime
 * @property int exp
 * @property int battle_value
 * @property int gold
 * @property int hellstone
 * @property int fragment
 * @property float ap_now
 * @property int ap_max
 * @property float hp_now
 * @property int hp_max
 * @property int str
 * @property int def
 * @property int dex
 * @property int end
 * @property int cha
 * @property int s_booty
 * @property int s_fight
 * @property int s_victory
 * @property int s_defeat
 * @property int s_draw
 * @property int s_gold_captured
 * @property int s_gold_lost
 * @property int s_damage_caused
 * @property int s_hp_lost
 * @property int talent_points
 * @property int talent_resets
 * @property int h_treasure
 * @property int h_royal
 * @property int h_gargoyle
 * @property int h_book
 * @property int h_domicile
 * @property int h_wall
 * @property int h_path
 * @property int h_land
 * @property int last_activity
 * @property int name_change
 * @property int vacation
 * @property int show_picture
 */
class User extends Authenticatable
{

	public $timestamps = false;

	protected $guarded = ['id'];

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return User
	 */
	public function setId(int $id): User
	{
		$this->id = $id;
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
	 * @return User
	 */
	public function setName(string $name): User
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public function setEmail(string $email): User
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword(string $password): User
	{
		$this->password = $password;
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
	 * @return User
	 */
	public function setRace(int $race): User
	{
		$this->race = $race;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGender(): int
	{
		return $this->gender;
	}

	/**
	 * @param int $gender
	 * @return User
	 */
	public function setGender(int $gender): User
	{
		$this->gender = $gender;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getImageType(): int
	{
		return $this->image_type;
	}

	/**
	 * @param int $image_type
	 * @return User
	 */
	public function setImageType(int $image_type): User
	{
		$this->image_type = $image_type;
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
	 * @return User
	 */
	public function setClanId(int $clan_id): User
	{
		$this->clan_id = $clan_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getClanRank(): int
	{
		return $this->clan_rank;
	}

	/**
	 * @param int $clan_rank
	 * @return User
	 */
	public function setClanRank(int $clan_rank): User
	{
		$this->clan_rank = $clan_rank;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getClanDtime(): int
	{
		return $this->clan_dtime;
	}

	/**
	 * @param int $clan_dtime
	 * @return User
	 */
	public function setClanDtime(int $clan_dtime): User
	{
		$this->clan_dtime = $clan_dtime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getClanBtime(): int
	{
		return $this->clan_btime;
	}

	/**
	 * @param int $clan_btime
	 * @return User
	 */
	public function setClanBtime(int $clan_btime): User
	{
		$this->clan_btime = $clan_btime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExp(): int
	{
		return $this->exp;
	}

	/**
	 * @param int $exp
	 * @return User
	 */
	public function setExp(int $exp): User
	{
		$this->exp = $exp;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBattleValue(): int
	{
		return $this->battle_value;
	}

	/**
	 * @param int $battle_value
	 * @return User
	 */
	public function setBattleValue(int $battle_value): User
	{
		$this->battle_value = $battle_value;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGold(): int
	{
		return $this->gold;
	}

	/**
	 * @param int $gold
	 * @return User
	 */
	public function setGold(int $gold): User
	{
		$this->gold = $gold;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHellstone(): int
	{
		return $this->hellstone;
	}

	/**
	 * @param int $hellstone
	 * @return User
	 */
	public function setHellstone(int $hellstone): User
	{
		$this->hellstone = $hellstone;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFragment(): int
	{
		return $this->fragment;
	}

	/**
	 * @param int $fragment
	 * @return User
	 */
	public function setFragment(int $fragment): User
	{
		$this->fragment = $fragment;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getApNow(): float
	{
		return $this->ap_now;
	}

	/**
	 * @param float $ap_now
	 * @return User
	 */
	public function setApNow(float $ap_now): User
	{
		$this->ap_now = $ap_now;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getApMax(): int
	{
		return $this->ap_max;
	}

	/**
	 * @param int $ap_max
	 * @return User
	 */
	public function setApMax(int $ap_max): User
	{
		$this->ap_max = $ap_max;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getHpNow(): float
	{
		return $this->hp_now;
	}

	/**
	 * @param float $hp_now
	 * @return User
	 */
	public function setHpNow(float $hp_now): User
	{
		$this->hp_now = $hp_now;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHpMax(): int
	{
		return $this->hp_max;
	}

	/**
	 * @param int $hp_max
	 * @return User
	 */
	public function setHpMax(int $hp_max): User
	{
		$this->hp_max = $hp_max;
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
	 * @return User
	 */
	public function setStr(int $str): User
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
	 * @return User
	 */
	public function setDef(int $def): User
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
	 * @return User
	 */
	public function setDex(int $dex): User
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
	 * @return User
	 */
	public function setEnd(int $end): User
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
	 * @return User
	 */
	public function setCha(int $cha): User
	{
		$this->cha = $cha;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSBooty(): int
	{
		return $this->s_booty;
	}

	/**
	 * @param int $s_booty
	 * @return User
	 */
	public function setSBooty(int $s_booty): User
	{
		$this->s_booty = $s_booty;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSFight(): int
	{
		return $this->s_fight;
	}

	/**
	 * @param int $s_fight
	 * @return User
	 */
	public function setSFight(int $s_fight): User
	{
		$this->s_fight = $s_fight;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSVictory(): int
	{
		return $this->s_victory;
	}

	/**
	 * @param int $s_victory
	 * @return User
	 */
	public function setSVictory(int $s_victory): User
	{
		$this->s_victory = $s_victory;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSDefeat(): int
	{
		return $this->s_defeat;
	}

	/**
	 * @param int $s_defeat
	 * @return User
	 */
	public function setSDefeat(int $s_defeat): User
	{
		$this->s_defeat = $s_defeat;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSDraw(): int
	{
		return $this->s_draw;
	}

	/**
	 * @param int $s_draw
	 * @return User
	 */
	public function setSDraw(int $s_draw): User
	{
		$this->s_draw = $s_draw;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSGoldCaptured(): int
	{
		return $this->s_gold_captured;
	}

	/**
	 * @param int $s_gold_captured
	 * @return User
	 */
	public function setSGoldCaptured(int $s_gold_captured): User
	{
		$this->s_gold_captured = $s_gold_captured;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSGoldLost(): int
	{
		return $this->s_gold_lost;
	}

	/**
	 * @param int $s_gold_lost
	 * @return User
	 */
	public function setSGoldLost(int $s_gold_lost): User
	{
		$this->s_gold_lost = $s_gold_lost;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSDamageCaused(): int
	{
		return $this->s_damage_caused;
	}

	/**
	 * @param int $s_damage_caused
	 * @return User
	 */
	public function setSDamageCaused(int $s_damage_caused): User
	{
		$this->s_damage_caused = $s_damage_caused;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSHpLost(): int
	{
		return $this->s_hp_lost;
	}

	/**
	 * @param int $s_hp_lost
	 * @return User
	 */
	public function setSHpLost(int $s_hp_lost): User
	{
		$this->s_hp_lost = $s_hp_lost;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTalentPoints(): int
	{
		return $this->talent_points;
	}

	/**
	 * @param int $talent_points
	 * @return User
	 */
	public function setTalentPoints(int $talent_points): User
	{
		$this->talent_points = $talent_points;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTalentResets(): int
	{
		return $this->talent_resets;
	}

	/**
	 * @param int $talent_resets
	 * @return User
	 */
	public function setTalentResets(int $talent_resets): User
	{
		$this->talent_resets = $talent_resets;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHTreasure(): int
	{
		return $this->h_treasure;
	}

	/**
	 * @param int $h_treasure
	 * @return User
	 */
	public function setHTreasure(int $h_treasure): User
	{
		$this->h_treasure = $h_treasure;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHRoyal(): int
	{
		return $this->h_royal;
	}

	/**
	 * @param int $h_royal
	 * @return User
	 */
	public function setHRoyal(int $h_royal): User
	{
		$this->h_royal = $h_royal;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHGargoyle(): int
	{
		return $this->h_gargoyle;
	}

	/**
	 * @param int $h_gargoyle
	 * @return User
	 */
	public function setHGargoyle(int $h_gargoyle): User
	{
		$this->h_gargoyle = $h_gargoyle;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHBook(): int
	{
		return $this->h_book;
	}

	/**
	 * @param int $h_book
	 * @return User
	 */
	public function setHBook(int $h_book): User
	{
		$this->h_book = $h_book;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHDomicile(): int
	{
		return $this->h_domicile;
	}

	/**
	 * @param int $h_domicile
	 * @return User
	 */
	public function setHDomicile(int $h_domicile): User
	{
		$this->h_domicile = $h_domicile;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHWall(): int
	{
		return $this->h_wall;
	}

	/**
	 * @param int $h_wall
	 * @return User
	 */
	public function setHWall(int $h_wall): User
	{
		$this->h_wall = $h_wall;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHPath(): int
	{
		return $this->h_path;
	}

	/**
	 * @param int $h_path
	 * @return User
	 */
	public function setHPath(int $h_path): User
	{
		$this->h_path = $h_path;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHLand(): int
	{
		return $this->h_land;
	}

	/**
	 * @param int $h_land
	 * @return User
	 */
	public function setHLand(int $h_land): User
	{
		$this->h_land = $h_land;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLastActivity(): int
	{
		return $this->last_activity;
	}

	/**
	 * @param int $last_activity
	 * @return User
	 */
	public function setLastActivity(int $last_activity): User
	{
		$this->last_activity = $last_activity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNameChange(): int
	{
		return $this->name_change;
	}

	/**
	 * @param int $name_change
	 * @return User
	 */
	public function setNameChange(int $name_change): User
	{
		$this->name_change = $name_change;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVacation(): int
	{
		return $this->vacation;
	}

	/**
	 * @param int $vacation
	 * @return User
	 */
	public function setVacation(int $vacation): User
	{
		$this->vacation = $vacation;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowPicture(): bool
	{
		return $this->show_picture;
	}

	/**
	 * @param bool $show_picture
	 * @return User
	 */
	public function setShowPicture(bool $show_picture): User
	{
		$this->show_picture = $show_picture;
		return $this;
	}

	/**
	 * Returns user level
	 * @param $exp
	 * @return int
	 */
    public static function getLevel($exp): int
	{
		return floor( sqrt( $exp / 5 ) ) + 1;
	}

	/**
	 * Returns required exp to level up
	 * @param $level
	 * @return int
	 */
	public static function getExpNeeded($level): int
	{
		return ((pow( $level, 2 ) * 5) + (5 * floor($level / 5)));
	}

	/**
	 * Returns previous level's needed exp
	 * @param $level
	 * @return int
	 */
	function getPreviousExpNeeded($level): int
	{
		return self::getExpNeeded($level - 1);
	}
}
