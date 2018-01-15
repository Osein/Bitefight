<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/14/2018
 * Time: 4:12 PM
 */

namespace App\Http\Controllers;

class GameController extends Controller
{
	/**
	 * GameController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');

		view()->share('user_new_message_count', 0);
		view()->share('clan_application_count', 0);
	}
}