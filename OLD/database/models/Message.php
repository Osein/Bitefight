<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int sender_id
 * @property int receiver_id
 * @property int folder_id
 * @property int type
 * @property string subject
 * @property string message
 * @property int status
 * @property int send_time
 */
class Message extends Model
{

	const TYPE_USER_MESSAGE = 1;
	const TYPE_RACE_HUNT = 2;
	const TYPE_CLAN_MESSAGE = 3;
	const TYPE_GROTTO = 4;
	const TYPE_SYSTEM = 5;

	const SENDER_GRAVEYARD = -2;
	const SENDER_SYSTEM = 0;

	const STATUS_UNREAD = 1;
	const STATUS_READ = 2;


	public $timestamps = false;

	protected $table = 'messages';

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Message
	 */
	public function setId(int $id): Message
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSenderId(): int
	{
		return $this->sender_id;
	}

	/**
	 * @param int $sender_id
	 * @return Message
	 */
	public function setSenderId(int $sender_id): Message
	{
		$this->sender_id = $sender_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getReceiverId(): int
	{
		return $this->receiver_id;
	}

	/**
	 * @param int $receiver_id
	 * @return Message
	 */
	public function setReceiverId(int $receiver_id): Message
	{
		$this->receiver_id = $receiver_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFolderId(): int
	{
		return $this->folder_id;
	}

	/**
	 * @param int $folder_id
	 * @return Message
	 */
	public function setFolderId(int $folder_id): Message
	{
		$this->folder_id = $folder_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 * @return Message
	 */
	public function setType(int $type): Message
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSubject(): string
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 * @return Message
	 */
	public function setSubject(string $subject): Message
	{
		$this->subject = $subject;
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
	 * @return Message
	 */
	public function setMessage(string $message): Message
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return Message
	 */
	public function setStatus(int $status): Message
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSendTime(): int
	{
		return $this->send_time;
	}

	/**
	 * @param int $send_time
	 * @return Message
	 */
	public function setSendTime(int $send_time): Message
	{
		$this->send_time = $send_time;
		return $this;
	}

}
