<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:18
 */

namespace Bitefight;

class Config_Example
{
    const DEBUG = true;
    const CDN_URL = 'http://localhost:8000/';
    const BASE_URL = 'http://localhost:8000/';
    const EMAIL_FROM = 'noreply@osein.net';
    const EMAIL_REPLY_TO = 'noreply@osein.net';
    const VERSION = '0.2a1';
    const DB_ADAPTER = 'mysql';
    const DB_HOST = '127.0.0.1';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'bitefight';
    const SERVER_NAME = 'Bitefight Private Server';
    const BASIC_HP = 20000;
    const DEF_HP_RATIO = 300;
    const BASIC_REGEN = 8000;
    const END_REGEN_RATIO = 125;
    const BASIC_ATTACK = 5;
    const BASIC_DAMAGE = 50;
    const BONUS_DAMAGE = 150;
    const HIT_CHANCE = 50;
    const BONUS_HIT_CHANCE = 60;
    const BASIC_TALENT = 20;
    const BONUS_TALENT = 80;
    const CHURCH_HEAL_TIME = 3600;
    const AP_PER_HOUR = 8;
}