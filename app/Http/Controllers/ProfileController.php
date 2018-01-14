<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 9:18 PM
 */

namespace App\Http\Controllers;

class ProfileController extends GameController
{

	public function getIndex()
	{
		return view('profile.index');
	}

}