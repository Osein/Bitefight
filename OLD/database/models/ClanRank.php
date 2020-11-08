<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int clan_id
 * @property string rank_name
 * @property bool read_message
 * @property bool write_message
 * @property bool read_clan_message
 * @property bool add_members
 * @property bool delete_message
 * @property bool send_clan_message
 * @property bool spend_gold
 * @property bool war_minister
 * @property bool vocalise_ritual
 */
class ClanRank extends Model
{

	public $timestamps = false;

	protected $table = 'clan_rank';

	public static $properties = [
        'read_message', 'write_message', 'read_clan_message',
        'add_members', 'delete_message', 'send_clan_message',
        'spend_gold', 'war_minister', 'vocalise_ritual'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     */
    public function setClanId(int $clan_id)
    {
        $this->clan_id = $clan_id;
    }

    /**
     * @return string
     */
    public function getRankName(): string
    {
        return $this->rank_name;
    }

    /**
     * @param string $rank_name
     */
    public function setRankName(string $rank_name)
    {
        $this->rank_name = $rank_name;
    }

    /**
     * @return bool
     */
    public function isReadMessage(): bool
    {
        return $this->read_message;
    }

    /**
     * @param bool $read_message
     */
    public function setReadMessage(bool $read_message)
    {
        $this->read_message = $read_message;
    }

	/**
	 * @return bool
	 */
	public function isWriteMessage(): bool
	{
		return $this->write_message;
	}

	/**
	 * @param bool $write_message
	 * @return ClanRank
	 */
	public function setWriteMessage(bool $write_message): ClanRank
	{
		$this->write_message = $write_message;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isReadClanMessage(): bool
	{
		return $this->read_clan_message;
	}

	/**
	 * @param bool $read_clan_message
	 * @return ClanRank
	 */
	public function setReadClanMessage(bool $read_clan_message): ClanRank
	{
		$this->read_clan_message = $read_clan_message;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAddMembers(): bool
	{
		return $this->add_members;
	}

	/**
	 * @param bool $add_members
	 * @return ClanRank
	 */
	public function setAddMembers(bool $add_members): ClanRank
	{
		$this->add_members = $add_members;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDeleteMessage(): bool
	{
		return $this->delete_message;
	}

	/**
	 * @param bool $delete_message
	 * @return ClanRank
	 */
	public function setDeleteMessage(bool $delete_message): ClanRank
	{
		$this->delete_message = $delete_message;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSendClanMessage(): bool
	{
		return $this->send_clan_message;
	}

	/**
	 * @param bool $send_clan_message
	 * @return ClanRank
	 */
	public function setSendClanMessage(bool $send_clan_message): ClanRank
	{
		$this->send_clan_message = $send_clan_message;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSpendGold(): bool
	{
		return $this->spend_gold;
	}

	/**
	 * @param bool $spend_gold
	 * @return ClanRank
	 */
	public function setSpendGold(bool $spend_gold): ClanRank
	{
		$this->spend_gold = $spend_gold;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isWarMinister(): bool
	{
		return $this->war_minister;
	}

	/**
	 * @param bool $war_minister
	 * @return ClanRank
	 */
	public function setWarMinister(bool $war_minister): ClanRank
	{
		$this->war_minister = $war_minister;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isVocaliseRitual(): bool
	{
		return $this->vocalise_ritual;
	}

	/**
	 * @param bool $vocalise_ritual
	 * @return ClanRank
	 */
	public function setVocaliseRitual(bool $vocalise_ritual): ClanRank
	{
		$this->vocalise_ritual = $vocalise_ritual;
		return $this;
	}

    /**
     * @param string $column_name
     * @return bool
     */
    public static function checkColumnExists($column_name)
    {
        return in_array($column_name, self::$properties);
    }

}
