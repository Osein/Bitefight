<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 02/04/17
 * Time: 16:21
 */

namespace Bitefight\Library;

use Phalcon\Http\Request;
use Phalcon\Translate\Adapter\NativeArray;

class Translate
{

    /**
     * @var NativeArray
     */
    protected static $nativeArray;

    /**
     * @return NativeArray
     */
    public static function getInstance() {
        if(!isset(self::$nativeArray)) {
            $request = new Request();
            $language = $request->getBestLanguage();
            $translationFile = APP_PATH . DIRECTORY_SEPARATOR . 'lang/' . $language . '.php';

            // Check if we have a translation file for that lang
            if (file_exists($translationFile)) {
                /** @noinspection PhpIncludeInspection */
                require $translationFile;
            } else {
                // Fallback to some default
                $messages = require APP_PATH . DIRECTORY_SEPARATOR . 'lang/en.php';
            }

            self::$nativeArray = new NativeArray(
                [
                    "content" => $messages,
                ]
            );
        }

        return self::$nativeArray;
    }

    /**
     * @param string $translateKey
     * @param array $placeholders
     * @return string
     */
    public static function _($translateKey, $placeholders = null) {
        return self::getInstance()->_($translateKey, $placeholders);
    }

    /**
     * returns talent name
     * @param string $talent_id
     * @return string
     */
    public static function _tn($talent_id) {
        return self::getInstance()->_('talent_id_' . $talent_id . '_name');
    }

    /**
     * returns talent description
     * @param string $talent_id
     * @return string
     */
    public static function _td($talent_id) {
        return self::getInstance()->_('talent_id_' . $talent_id . '_description');
    }

}