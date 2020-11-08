<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'race',
        'gender',
        'image_type',
        'exp',
        'battle_value',
        'gold',
        'hellstone',
        'fragment',
        'ap_now',
        'ap_max',
        'hp_now',
        'hp_max',
        'str',
        'def',
        'dex',
        'end',
        'cha',
        's_booty',
        's_fight',
        's_victory',
        's_defeat',
        's_draw',
        's_gold_captured',
        's_gold_lost',
        's_damage_caused',
        's_hp_lost',
        'talent_points',
        'talent_resets',
        'h_treasure',
        'h_royal',
        'h_gargoyle',
        'h_book',
        'h_domicile',
        'h_wall',
        'h_path',
        'h_land',
        'name_change',
        'vacation',
        'show_picture',
        'premium'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
