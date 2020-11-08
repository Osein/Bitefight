<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBuddy
 * @package Database\Models
 *
 * @property int id
 * @property int user_from_id
 * @property int user_to_id
 */
class UserBuddy extends Model
{

    protected $table = 'user_buddy';

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
     * @return UserBuddy
     */
    public function setId(int $id): UserBuddy
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserFromId(): int
    {
        return $this->user_from_id;
    }

    /**
     * @param int $user_from_id
     * @return UserBuddy
     */
    public function setUserFromId(int $user_from_id): UserBuddy
    {
        $this->user_from_id = $user_from_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserToId(): int
    {
        return $this->user_to_id;
    }

    /**
     * @param int $user_to_id
     * @return UserBuddy
     */
    public function setUserToId(int $user_to_id): UserBuddy
    {
        $this->user_to_id = $user_to_id;
        return $this;
    }

}
