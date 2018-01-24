<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/22/2018
 * Time: 10:41 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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

	public function postVoodoo()
	{
		$user = user();

		if(!empty(Input::get('buy_methamorphosis'))) {
			if($user->getHellstone() < 50) {
				throw new InvalidRequestException();
			}

			$user->setHellstone($user->getHellstone() - 50);
			$user->setRace(0);

			if($user->getClanId() > 0) {
				if($user->getClanRank() == 1) {
					DB::transaction(function() {
						$user = user();

						DB::table('clan')->delete($user->getClanId());
						DB::table('clan_applications')->where('clan_id', $user->getClanId())->delete();
						DB::table('clan_description')->where('clan_id', $user->getClanId())->delete();
						DB::table('clan_donations')->where('clan_id', $user->getClanId())->delete();
						DB::table('clan_message')->where('clan_id', $user->getClanId())->delete();
						DB::table('clan_rank')->where('clan_id', $user->getClanId())->delete();
						DB::table('user')->where('clan_id', $user->getClanId())->update(['clan_id' => 0, 'clan_rank' => 0]);
					});
				}

				$user->setClanId(0);
			}
		}

		if(!empty(Input::get('buy_shadow_lord'))) {
			if($user->getHellstone() < 15) {
				throw new InvalidRequestException();
			}

			$user->setHellstone($user->getHellstone() - 15);
			$time = time();
			$user->setPremium(($user->getPremium() > $time ? $user->getPremium() : time()) + 1209600);
		}

		return redirect(url('/voodoo'));
	}
}