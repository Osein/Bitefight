<?php

namespace App\Http\Controllers;

use Database\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{
    public function getIndex()
	{
		return view('home.index');
	}

	public function registerAjaxCheck()
	{
		if(!Request::ajax()) {
			throw new \Exception("Invalid request");
		}

		$name = Input::get('name');
		$email = Input::get('email');

		$result = ['status' => false, 'messages' => ['Invalid request']];

		if (strlen($name) > 0) {
			if (strlen($name) < 3) {
				$result = [
					'status' => false,
					'messages' => [__('validation.custom.validation_ajax_username_short')]
				];
			} else {
				$userCount = User::where('name', $name)->count();

				if ($userCount) {
					$result = [
						'status' => false,
						'messages' => [__('validation.custom.validation_ajax_username_used')]
					];
				} else {
					$result = [
						'status' => true,
						'messages' => null
					];
				}
			}
		} elseif (strlen($email) > 0) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = [
					'status' => false,
					'messages' => [__('validation.custom.validation_ajax_email_invalid')]
				];
			} else {
				$userCount = User::where('email', $email)->count();

				if ($userCount) {
					$result = [
						'status' => false,
						'messages' => [__('validation.custom.validation_ajax_email_used')]
					];
				} else {
					$result = [
						'status' => true,
						'messages' => null
					];
				}
			}
		}

		return $result;
	}
}
