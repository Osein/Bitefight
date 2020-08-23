<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBuddyRequest
 * @package Database\Models
 *
 * @property int id
 * @property int from_id
 * @property int to_id
 * @property string message
 * @property int request_time
 */
class UserBuddyRequest extends Model
{

    protected $table = 'user_buddy_request';

    public $timestamps = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserBuddyRequest
     */
    public function setId(int $id): UserBuddyRequest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromId(): int
    {
        return $this->from_id;
    }

    /**
     * @param int $from_id
     * @return UserBuddyRequest
     */
    public function setFromId(int $from_id): UserBuddyRequest
    {
        $this->from_id = $from_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getToId(): int
    {
        return $this->to_id;
    }

    /**
     * @param int $to_id
     * @return UserBuddyRequest
     */
    public function setToId(int $to_id): UserBuddyRequest
    {
        $this->to_id = $to_id;
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
     * @return UserBuddyRequest
     */
    public function setMessage(string $message): UserBuddyRequest
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequestTime(): int
    {
        return $this->request_time;
    }

    /**
     * @param int $request_time
     * @return UserBuddyRequest
     */
    public function setRequestTime(int $request_time): UserBuddyRequest
    {
        $this->request_time = $request_time;
        return $this;
    }
}
