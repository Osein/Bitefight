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

function getItemModelFromModelNo($modelNo) {
	$modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');

	return $modelArray[$modelNo-1];
}

function getSkillCost($skillLevel) {
	return floor(pow($skillLevel - 4, 2.4));
}