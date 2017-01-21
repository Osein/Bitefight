<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:18
 */


$config = new \Phalcon\Config(array(
    'cdn' => 'http://bfd.osein.tk/',
    'baseUrl' => 'http:///bfd.osein.tk/',
    'version' => '0.2a1',
    'database' => array(
        'adapter' => 'mysql',
        'host' => 'host',
        'username' => 'user',
        'password' => 'pass',
        'dbname' => 'dbname'
    ),
    'basicHp' => 20000,
    'defHpRatio' => 300,
    'basicRegen' => 8000,
    'endRegenRatio' => 125,
    'basicAttack' => 5,
    'basicDamage' => 50,
    'bonusDamage' => 150,
    'hitChance' => 50,
    'bonusHitChance' => 60,
    'basicTalent' => 20,
    'bonusTalent' => 80,
    'churchHealTime' => 3600,
    'apPerHour' => 8,
    'serverName' => 'Bitefight Private Server'
));