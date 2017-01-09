<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:18
 */


$config = new \Phalcon\Config(array(
    'cdn' => 'http://bf.127.0.0.1.xip.io/',
    'baseUrl' => 'http://bf.127.0.0.1.xip.io/',
    'version' => '0.1a1',
    "database" => array(
        "adapter" => "Mysql",
        "host" => "localhost",
        "username" => "scott",
        "password" => "cheetah",
        "dbname" => "test_db"
    ),
    "phalcon" => array(
        "controllersDir" => "../app/controllers/",
        "modelsDir" => "../app/models/",
        "viewsDir" => "../app/views/"
    )
));