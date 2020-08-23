<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int clan_id
 * @property int user_id
 * @property int donation_amount
 * @property int donation_time
 */
class ClanDonations extends Model
{

	public $timestamps = false;

	protected $table = 'clan_donations';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ClanDonations
	 */
	public function setId(int $id): ClanDonations
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
	 * @return ClanDonations
	 */
	public function setClanId(int $clan_id): ClanDonations
	{
		$this->clan_id = $clan_id;
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
	 * @return ClanDonations
	 */
	public function setUserId(int $user_id): ClanDonations
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDonationAmount(): int
	{
		return $this->donation_amount;
	}

	/**
	 * @param int $donation_amount
	 * @return ClanDonations
	 */
	public function setDonationAmount(int $donation_amount): ClanDonations
	{
		$this->donation_amount = $donation_amount;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDonationTime(): int
	{
		return $this->donation_time;
	}

	/**
	 * @param int $donation_time
	 * @return ClanDonations
	 */
	public function setDonationTime(int $donation_time): ClanDonations
	{
		$this->donation_time = $donation_time;
		return $this;
	}

}
