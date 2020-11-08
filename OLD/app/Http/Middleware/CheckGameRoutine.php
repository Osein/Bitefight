<?php

namespace App\Http\Middleware;

use Closure;
use Database\Models\ClanApplications;
use Database\Models\ClanRank;
use Database\Models\Message;
use Database\Models\User;
use Database\Models\UserActivity;
use Database\Models\UserBuddyRequest;
use Database\Models\UserEmailActivation;
use Database\Models\UserMessageSettings;
use Illuminate\Support\Facades\Request;

class CheckGameRoutine
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user = user();

		if($user) {
			/**
			 * @var UserEmailActivation $userEmailActivation
			 */
			$userEmailActivation = UserEmailActivation::where('user_id', $user->getId())->first();

			view()->share('user_email_activated', empty($userEmailActivation) || $userEmailActivation->isActivated());

			if($userEmailActivation) {
                if(!$userEmailActivation->isActivated()) {
                    view()->share('user_email_activation_expire', $userEmailActivation->getExpire());

                    if($userEmailActivation->getExpire() < time() && !Request::is('settings')) {
                        return redirect(url('/settings'));
                    }
                } else {
                    if($userEmailActivation && $userEmailActivation->getExpire() + 60*60*24*4 < time()) {
                        $user->setEmail($userEmailActivation->getEmail());
                        $userEmailActivation->delete();
                    }
                }
            }

			$lastUpdate = $user->getLastActivity();
			$timeNow = time();
			$timeDiff = $timeNow - $lastUpdate;
			$userLevel = getLevel($user->getExp());
			$user->setLastActivity(time());

			$user_new_message_count = Message::where('receiver_id', $user->getId())
				->where('status', Message::STATUS_UNREAD)
				->count();

			view()->share('user_new_message_count', $user_new_message_count);

			$user_buddy_request_count = UserBuddyRequest::where('to_id', $user->getId())
                ->count();

			view()->share('user_buddy_request_count', $user_buddy_request_count);

			$clan_application_count = 0;

			if($user->getClanId() > 0) {
				/**
				 * @var ClanRank $userRank
				 */
				$userRank = ClanRank::find($user->getClanRank());

				if($userRank->isAddMembers()) {
					$clan_application_count = ClanApplications::where('clan_id', $user->getClanId())
						->count();
				}
			}

			view()->share('clan_application_count', $clan_application_count);

			if(isUserPremiumActivated() && $user->getPremium() < time()) {
				// Premium end, downgrade
				$user->setApMax($user->getApMax() - 60);
				$user->setApNow(min($user->getApMax(), $user->getApNow()));
			} else if(!isUserPremiumActivated() && $user->getPremium() > time()) {
				// Activated premium, upgrade
				$user->setApMax($user->getApMax() + 60);

				$bonusGold = getBonusGraveyardGold($user);
				$goldReward = $userLevel * 50 + $bonusGold * 4 * 24;
				$user->setGold($user->getGold() + $goldReward);
			}

			if ($timeDiff > 0) {
				if ($user->getApNow() < $user->getApMax()) {
					$apPerSecond = env('INITIAL_AP_PER_HOUR') * ($user->getPremium() > $timeNow ? env('PREMIUM_AP_MULTIPLIER') : 1) / 3600;
					$apDelta = $apPerSecond * $timeDiff;
					$user->setApNow(min($apDelta + $user->getApNow(), $user->getApMax()));
				}

				if ($user->getHpNow() < $user->getHpMax()) {
					$hpPerSecond = (env('INITIAL_REGENERATION') + env('REGEN_PER_ENDURANCE') * $user->getEnd()) / 3600;
					$hpDelta = $hpPerSecond * $timeDiff;
					$user->setHpNow(min($hpDelta + $user->getHpNow(), $user->getHpMax()));
				}
			}

			/**
			 * @var UserActivity $graveyardActivity
			 */
			$graveyardActivity = UserActivity::where('user_id', $user->getId())
				->where('activity_type', UserActivity::ACTIVITY_TYPE_GRAVEYARD)
				->where('end_time', '<=', $timeNow)
				->first();

			if($graveyardActivity) {
				$rewardMultiplier = ($graveyardActivity->getEndTime() - $graveyardActivity->getStartTime()) / 900;
				$bonusGold = getBonusGraveyardGold($user);
				$goldReward = $rewardMultiplier * $userLevel * 50;
				$totalReward = $goldReward + $bonusGold;
				$expReward = ceil(pow($user->getExp(), 0.25));

				$graveyardMessageFolderSetting = UserMessageSettings::getUserSetting(UserMessageSettings::WORK);

				if($graveyardMessageFolderSetting->getFolderId() != -2) {
					$graveyardMessage = new Message;
					$graveyardMessage->setSenderId(Message::SENDER_GRAVEYARD);
					$graveyardMessage->setReceiverId($user->getId());
					$graveyardMessage->setFolderId($graveyardMessageFolderSetting->getFolderId());
					$graveyardMessage->setSubject('Work finished');
					$graveyardMessage->setMessage('After successful shift working as the '.getGraveyardRank($user).' you get a salary of '.prettyNumber($totalReward).' '.gold_image_tag().' and '.$expReward.' experience points!');
					$graveyardMessage->setStatus($graveyardMessageFolderSetting->isMarkRead() == 1 ? 2 : 1);
					$graveyardMessage->save();
				}

				$user->processExpIfLevelUp($expReward);
				$user->setGold($user->getGold() + $totalReward);

				$graveyardActivity->delete();
			}
		}

		return $next($request);
	}
}
