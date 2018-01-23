<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/22/2018
 * Time: 10:41 PM
 */

namespace App\Http\Controllers;

class CityController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getVoodoo()
	{
		return view('city.voodoo');
	}
}