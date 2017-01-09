<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:14
 */

function getAssetLink($assetLink) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('cdn') . $assetLink;
}

function headLink($file) {
    return '<link rel="stylesheet" type="text/css" href="'.getAssetLink('css/'.$file).'">'.PHP_EOL;
}

function headJs($file) {
    return '<script type="text/javascript" src="'.getAssetLink('js/'.$file).'"></script>'.PHP_EOL;
}

function getUrl($link) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('baseUrl') . $link;
}