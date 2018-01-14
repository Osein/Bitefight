<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 9:35 PM
 */

/**
 * @param int $number
 * @return string
 */
function prettyNumber($number) {
	return number_format($number, 0, ',', '.');
}