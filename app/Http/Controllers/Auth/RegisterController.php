<?php

namespace App\Http\Controllers\Auth;

use Database\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:30|unique:users',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:6',
			'agb' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Database\Models\User
     */
    protected function create(array $data)
    {
    	$initialStr = env('INITIAL_STR');
    	$initialDef = env('INITIAL_DEF');
    	$initialDex = env('INITIAL_DEX');
    	$initialEnd = env('INITIAL_END');
    	$initialCha = env('INITIAL_CHA');

    	$initialExp = env('STARTING_EXP');
    	$initialLevel = User::getLevel($initialExp);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
			'race' => $data['race'],
			'gender' => env('DEFAULT_GENDER'),
			'image_type' => env('DEFAULT_IMAGE_LEVEL'),
			'exp' => $initialExp,
			'battle_value' => $initialStr + $initialDef + $initialDex +
				$initialEnd + $initialCha + ($initialLevel * 4) + 12,
			'gold' => env('STARTING_GOLD'),
			'hellstone' => env('STARTING_HELLSTONE'),
			'fragment' => env('STARTING_FRAGMENTS'),
			'ap_now' => env('INITIAL_AP'),
			'ap_max' => env('INITIAL_AP'),
			'hp_now' => env('INITIAL_HP') + $initialDef * 300,
			'hp_max' => env('INITIAL_HP') + $initialDef * 300,
			'str' => $initialStr,
			'def' => $initialDef,
			'dex' => $initialDex,
			'end' => $initialEnd,
			'cha' => $initialCha,
			's_booty' => env('INITIAL_BOOTY'),
			's_fight' => env('INITIAL_FIGHT_COUNT'),
			's_victory' => env('INITIAL_VICTORIES'),
			's_defeat' => env('INITIAL_DEFEATS'),
			's_draw' => env('INITIAL_DRAWS'),
			's_gold_captured' => env('INITIAL_CAPTURED_GOLD'),
			's_gold_lost' => env('INITIAL_LOST_GOLD'),
			's_damage_caused' => env('INITIAL_DAMAGE_CAUSED'),
			's_hp_lost' => env('INITIAL_DAMAGE_RECEIVED'),
			'talent_points' => env('INITIAL_TALENT_POINTS'),
			'talent_resets' => env('INITIAL_TALENT_RESETS'),
			'h_treasure' => env('INITIAL_TREASURE_TIME') > 0 ? time() + env('INITIAL_TREASURE_TIME') : 0,
			'h_royal' => env('INITIAL_ROYAL_TREASURE_TIME') > 0 ? time() + env('INITIAL_ROYAL_TREASURE_TIME') : 0,
			'h_gargoyle' => env('INITIAL_GARGOYLE_TIME') > 0 ? time() + env('INITIAL_GARGOYLE_TIME') : 0,
			'h_book' => env('INITIAL_BOOK_TIME') > 0 ? time() + env('INITIAL_BOOK_TIME') : 0,
			'h_domicile' => env('INITIAL_DOMICILE'),
			'h_wall' => env('INITIAL_WALL'),
			'h_path' => env('INITIAL_PATH'),
			'h_land' => env('INITIAL_LAND'),
			'name_change' => env('INITIAL_NAME_CHANGES'),
			'vacation' => env('INITIAL_VACATION_TIME') > 0 ? time() + env('INITIAL_VACATION_TIME') : 0,
			'show_picture' => env('INITIAL_SHOW_USER_PICTURE'),
        ]);
    }

	public function showRegistrationForm()
	{
		$race = Input::get('race');

		return view('home.register', ['showRaceSelection' => empty($race) ? true : false, 'race' => $race]);
	}

}
