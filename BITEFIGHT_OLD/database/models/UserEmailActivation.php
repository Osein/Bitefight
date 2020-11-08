<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserEmailActivation
 * @package Database\Models
 *
 * @property int id
 * @property int user_id
 * @property string email
 * @property string token
 * @property int expire
 * @property boolean first_time
 * @property boolean activated
 */
class UserEmailActivation extends Model
{
    protected $table = 'user_email_activation';

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
     * @return UserEmailActivation
     */
    public function setId(int $id): UserEmailActivation
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
     * @return UserEmailActivation
     */
    public function setUserId(int $user_id): UserEmailActivation
    {
        $this->user_id = $user_id;
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
     * @return UserEmailActivation
     */
    public function setEmail(string $email): UserEmailActivation
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return UserEmailActivation
     */
    public function setToken(string $token): UserEmailActivation
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     * @return UserEmailActivation
     */
    public function setExpire(int $expire): UserEmailActivation
    {
        $this->expire = $expire;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstTime(): bool
    {
        return $this->first_time;
    }

    /**
     * @param bool $first_time
     * @return UserEmailActivation
     */
    public function setFirstTime(bool $first_time): UserEmailActivation
    {
        $this->first_time = $first_time;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     * @return UserEmailActivation
     */
    public function setActivated(bool $activated): UserEmailActivation
    {
        $this->activated = $activated;
        return $this;
    }

}
