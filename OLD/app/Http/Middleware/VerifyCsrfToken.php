<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

	/**
	 * The URIs that should be included for CSRF verification.
	 *
	 * @var array
	 */
    protected $included = [
        '/message/ajax/checkreceiver',
		'/hunt/human',
        '/clan/view/homepage',
		'/clan/hideout/upgrade',
		'/clan/deletemessage',
		'/clan/clanleave',
        '/clan/memberrights/setowner',
        '/clan/memberrights/deleterank',
        '/clan/memberrights/kickuser',
        '/clan/kick',
        '/city/missions/replaceOpenMissions',
        '/city/missions/cancelMission',
        '/city/missions/acceptMission',
        '/city/missions/finishMission',
        '/message/ajax'
	];

	/**
	 * Determine if the request should be checked against csrf attack.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return bool
	 */
	protected function isReading($request)
	{
		foreach($this->included as $url) {
			if(strpos($request->getRequestUri(), $url) !== false) {
				return false;
			}
		}

		return parent::isReading($request);
	}

}
