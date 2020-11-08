<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/27/2018
 * Time: 7:05 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use Database\Models\Clan;
use Database\Models\ClanApplications;
use Database\Models\ClanDescription;
use Database\Models\ClanDonations;
use Database\Models\ClanMessages;
use Database\Models\ClanRank;
use Database\Models\Message;
use Database\Models\User;
use Database\Models\UserMessageSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ClanController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', [
			'except' => ['getPreview', 'getMemberListExternal']
		]);
	}

	public function getIndex()
	{
		$data = [];

		if (user()->getClanId()) {
			$data['clan'] = Clan::leftJoin('clan_description', 'clan.id', '=', 'clan_description.clan_id')->find(user()->getClanId());

			$data['rank'] = ClanRank::find(user()->getClanRank());

			$tb = User::where('clan_id', \user()->getClanId())
				->select(DB::raw('SUM(s_booty) AS total_booty'))
				->first();

			$data['totalBlood'] = $tb ? $tb->total_booty : 0;

			$data['member_count'] = User::where('clan_id', \user()->getClanId())
				->count();

			if ($data['rank']->read_message) {
				$data['clan_messages'] = ClanMessages::where('clan_message.clan_id', \user()->getClanId())
					->leftJoin('users', 'users.id', '=', 'clan_message.user_id')
					->leftJoin('clan_rank', 'users.clan_rank', '=', 'clan_rank.id')
					->select(DB::raw('users.name, clan_message.*, clan_rank.rank_name'))
					->get();
			}
		}

		return view('clan.index', $data);
	}

	public function getCreate()
	{
		UserMessageSettings::getUserSetting('asd');
		return view('clan.create');
	}

	public function postCreate(Request $request)
	{
		$name = Input::get('name');
		$tag = Input::get('tag');

		$this->validate($request, [
			'name' => 'required|string|min:2|unique:clan',
			'tag' => 'required|string|min:2|unique:clan'
		]);

		$clan = new Clan;
		$clan->setName($name);
		$clan->setTag($tag);
		$clan->setRace(user()->getRace());
		$clan->setFoundDate(time());
		$clan->save();

		$msgSetting = UserMessageSettings::getUserSetting(UserMessageSettings::CLAN_FOUNDED);

		if($msgSetting->getFolderId() != UserMessageSettings::FOLDER_ID_DELETE_IMMEDIATELY) {
			$msg = new Message;
			$msg->setSenderId(Message::SENDER_SYSTEM);
			$msg->setReceiverId(user()->getId());
			$msg->setFolderId($msgSetting->getFolderId());
			$msg->setSubject('Clan information');
			$msg->setMessage('Your clan has been founded: '.$name.' ['.$tag.']');
			$msg->setStatus($msgSetting->isMarkRead() ? 2 : 1);
			$msg->save();
		}

		user()->setClanId($clan->getId());
		user()->setClanRank(1);

		return redirect(url('/clan/index'));
	}

	public function postNewMessage()
	{
		$message = Input::get('message');

		/**
		 * @var ClanRank $rank
		 */
		$rank = ClanRank::find(\user()->getClanRank());

		if(strlen($message) > 2000 || !$rank->isWriteMessage())
			throw new InvalidRequestException();

		$msg = new ClanMessages;
		$msg->setClanId(\user()->getClanId());
		$msg->setUserId(\user()->getId());
		$msg->setClanMessage($message);
		$msg->setMessageTime(time());
		$msg->save();

		return redirect(url('/clan/index'));
	}

	public function postDeleteMessage()
	{
		/**
		 * @var ClanRank $rank
		 */
		$rank = ClanRank::find(\user()->getClanRank());
		$message = Input::get('message_id', 0);

		if(!$rank->isDeleteMessage())
			throw new InvalidRequestException();

		ClanMessages::where('clan_id', \user()->getClanId())
			->delete($message);

		return redirect(url('/clan/index'));
	}

	public function postDonate()
	{
		$amount = Input::get('donation', 0);

		if($amount == 0 || $amount > \user()->getGold())
			throw new InvalidRequestException();

		DB::table('clan')->where('id', \user()->getClanId())->update([
			'capital' => DB::raw('capital + '.$amount)
		]);

		user()->setGold(\user()->getGold() - $amount);

		$donation = new ClanDonations;
		$donation->setUserId(\user()->getId());
		$donation->setClanId(\user()->getClanId());
		$donation->setDonationAmount($amount);
		$donation->setDonationTime(time());
		$donation->save();

		return redirect(url('/clan/index'));
	}

	public function postHideoutUpgrade()
	{
		/**
		 * @var ClanRank $rank
		 */
		$rank = ClanRank::find(\user()->getClanRank());

		if(!$rank->isSpendGold())
			throw new InvalidRequestException();


		$clan = Clan::find(\user()->getClanId());
		$hideoutCost = getClanHideoutCost($clan->getStufe() + 1);

		if($clan->getCapital() < $hideoutCost)
			throw new InvalidRequestException();

		$clan->setCapital($clan->getCapital() - $hideoutCost);
		$clan->setStufe($clan->getStufe() + 1);
		$clan->save();

		return redirect(url('/clan/index'));
	}

	public function getLogoBackground()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		return view('clan.logo', ['type' => 'background', 'clan' => Clan::find(\user()->getClanId())]);
	}

	public function getLogoSymbol()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		return view('clan.logo', ['type' => 'symbol', 'clan' => Clan::find(\user()->getClanId())]);
	}

	public function postLogoBackground()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$background = Input::get('bg', 1);

		Clan::where('id', \user()->getClanId())->update([
			'logo_bg' => $background
		]);

		return redirect(url('/clan/logo/background'));
	}

	public function postLogoSymbol()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$sym = Input::get('symbol', 1);

		Clan::where('id', \user()->getClanId())->update([
			'logo_sym' => $sym
		]);

		return redirect(url('/clan/logo/symbol'));
	}

	public function getMemberList()
	{
		$clan = Clan::find(\user()->getClanId());
		$order = Input::get('order', 'exp');
		$type = Input::get('type', 'desc');

		if(!$clan)
			throw new InvalidRequestException();

		$users = User::select(DB::raw('users.*, clan_rank.rank_name'))
			->leftJoin('clan_rank', 'users.clan_rank', '=', 'clan_rank.id')
			->where('users.clan_id', \user()->getClanId());

		if($order == 'name') {
			$order = 'users.name';
		} elseif($order == 'level') {
			$order = 'users.exp';
		} elseif($order == 'rank') {
			$order = 'clan_rank.rank_name';
		} elseif($order == 'res1') {
			$order = 'users.s_booty';
		} elseif($order == 'goldwon') {
			$order = 'users.s_gold_captured';
		} elseif($order == 'goldlost') {
			$order = 'users.s_gold_lost';
		} elseif($order == 'status') {
			$order = 'users.last_activity';
		}

		if($type == 'desc') {
			$users = $users->orderByDesc($order);
		} else {
			$users = $users->orderByAsc($order);
		}

		$members = $users
			->get();

		return view('clan.memberlist', [
			'order' => $order,
			'type' => $type,
			'members' => $members,
			'clan' => $clan
		]);
	}

	public function getDescription()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		/**
		 * @var ClanDescription $desc
		 */
		$desc = ClanDescription::where('clan_id', \user()->getClanId())->first();
		return view('clan.description', ['description' => $desc ? $desc->getDescription() : '']);
	}

	public function postDescription()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$save = Input::get('save');
		$desc = Input::get('description');

		if($save) {
			$description = ClanDescription::where('clan_id', \user()->getClanId())->first();

			if(!$description) {
				$description = new ClanDescription;
				$description->setClanId(\user()->getClanId());
			}

			$description->setDescription($desc);
			$description->setDescriptionHtml(parseBBCodes($desc));
			$description->save();
		} else {
			ClanDescription::where('clan_id', \user()->getClanId())->delete();
		}

		return redirect(url('/clan/description'));
	}

	public function getChangeHomepage()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$clanObj = Clan::select(DB::raw('clan.*, users.name as website_editor_name'))
			->leftJoin('users', 'users.id', '=', 'clan.website_set_by')
			->find(\user()->getClanId());

		return view('clan.change_homepage', ['clan' => $clanObj]);
	}

	public function getChangeName()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		return view('clan.change_name', ['clan' => Clan::find(\user()->getClanId())]);
	}

	public function postChangeHomepage()
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$clan = Clan::find(\user()->getClanId());
		$delete = Input::get('delete');

		if($delete) {
			$clan->setWebsite('');
			$clan->setWebsiteSetBy(\user()->getId());
			$clan->save();
		} else {
			$homepage = Input::get('homepage');

			if(filter_var($homepage, FILTER_VALIDATE_URL)) {
				$clan->setWebsite($homepage);
				$clan->setWebsiteSetBy(\user()->getId());
				$clan->save();
			}
		}

		return redirect(url('/clan/change/homepage'));
	}

	public function postChangeName(Request $request)
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$name = Input::get('name');
		$tag = Input::get('tag');

		if(empty($name) && empty($tag))
			throw new InvalidRequestException();

		$clan = Clan::find(\user()->getClanId());

		if(!empty($name)) {
			$this->validate($request, [
				'name' => 'string|min:2|unique:clan'
			]);

			$clan->setName($name);
		}

		if(!empty($tag)) {
			$this->validate($request, [
				'tag' => 'string|min:2|unique:clan'
			]);

			$clan->setTag($tag);
		}

		$clan->save();

		return redirect(url('/clan/change/name'));
	}

	public function getLeave()
	{
		return view('clan.leave');
	}

	public function postLeave()
	{
		self::doLeaveClanRoutine(\user());
		return redirect(url('/clan/index'));
	}

	public static function doLeaveClanRoutine($user)
	{
		/**
		 * @var User $user
		 */
		$clan = Clan::find($user->getClanId());

		$msgSetting = UserMessageSettings::getUserSetting(
			$user->getClanRank() == 1 ?
				UserMessageSettings::CLAN_DISBANDED :
				UserMessageSettings::LEFT_CLAN);

		if($msgSetting->getFolderId() != UserMessageSettings::FOLDER_ID_DELETE_IMMEDIATELY) {
			$msg = new Message;
			$msg->setSenderId(Message::SENDER_SYSTEM);
			$msg->setReceiverId($user->getId());
			$msg->setType(Message::TYPE_CLAN_MESSAGE);
			$msg->setSubject('Clan information');
			$msg->setMessage(
				$user->getClanRank() == 1 ?
					'You have successfully disbanded the clan: '.$clan->getName().' ['.$clan->getTag().']':
					'You have left the following clan: '.$clan->getName().' ['.$clan->getTag().']'
			);
			$msg->setFolderId($msgSetting->getFolderId());
			$msg->save();
		}

		$userIds = User::where('clan_id', $user->getClanId())->select('id')->get();
		foreach($userIds as $userId) {
			if($userId->id == $user->getId()) {
				continue;
			}

			$userMessageSetting = UserMessageSettings::getUserSetting(
				$user->getClanRank() == 1 ?
					UserMessageSettings::CLAN_DISBANDED:
					UserMessageSettings::CLAN_MEMBER_LEFT
			);

			if($userMessageSetting->getFolderId() != UserMessageSettings::FOLDER_ID_DELETE_IMMEDIATELY) {
				$userMessage = new Message;
				$userMessage->setSenderId(Message::SENDER_SYSTEM);
				$userMessage->setReceiverId($userId);
				$userMessage->setType(Message::TYPE_CLAN_MESSAGE);
				$userMessage->setSubject('Clan information');
				$userMessage->setMessage(
					$user->getClanRank() == 1 ?
						'Your master disbanded the clan: '.$clan->getName().' ['.$clan->getTag().']':
						'The following player has left your clan: '.$clan->getName().' ['.$clan->getTag().']'
				);
				$userMessage->setFolderId($msgSetting->getFolderId());
				$userMessage->save();
			}
		}

		$user->setClanId(0);
		$user->setClanRank(0);

		if($user->getClanRank() == 1) {
			$clan->delete();
			DB::table('clan_applications')->where('clan_id', $user->getClanId())->delete();
			DB::table('clan_description')->where('clan_id', $user->getClanId())->delete();
			DB::table('clan_donations')->where('clan_id', $user->getClanId())->delete();
			DB::table('clan_message')->where('clan_id', $user->getClanId())->delete();
			DB::table('clan_rank')->where('clan_id', $user->getClanId())->delete();
			DB::table('user')->where('clan_id', $user->getClanId())->update(['clan_id' => 0, 'clan_rank' => 0]);
		}

		return true;
	}

	public function getClanMail()
	{
		/**
		 * @var ClanRank $userRank
		 */
		$userRank = ClanRank::where('id', \user()->getClanRank())->first();

		if(!$userRank->isSendClanMessage())
			throw new InvalidRequestException();

		$mailUsers = User::select(DB::raw('users.id, users.name, clan_rank.rank_name, SUM(users.s_booty) as total_booty'))
			->leftJoin('clan_rank', 'users.clan_rank', '=', 'clan_rank.id')
			->where('users.clan_id', \user()->getClanId())
			->groupBy('users.id')
			->get();

		return view('clan.mail', ['mail_users' => $mailUsers]);
	}

	public function postClanMail()
	{
		/**
		 * @var ClanRank $userRank
		 */
		$userRank = ClanRank::where('id', \user()->getClanRank())->first();

		if(!$userRank->isSendClanMessage())
			throw new InvalidRequestException();

		$receivers = Input::get('receiver', array());
		$text = Input::get('text', '');

		if(strlen($text) > 2000 || empty($receivers))
			throw new InvalidRequestException();

		$users = User::select(DB::raw('users.name, users.id, user_message_settings.folder_id, user_message_settings.mark_read'))
			->leftJoin('user_message_settings', function($join) {
				$join->on('users.id', '=', 'user_message_settings.user_id');
				$join->on(
					'user_message_settings.setting', '=',
					DB::raw('\''.UserMessageSettings::getMessageSettingTypeFromSettingViewId(
						UserMessageSettings::CLAN_MAIL
					).'\'')
				);
			})
			->leftJoin('user_message_block', function($join) {
				$join->on('users.id', '=', 'user_message_block.user_id');
				$join->on('user_message_block.blocked_id', '=', DB::raw(\user()->getId()));
			})
			->where('users.clan_id', \user()->getClanId())
			->whereNull('user_message_block.blocked_id')
			->get();

		foreach ($users as $user) {
			if(is_null($user->folder_id)) {
				$user->folder_id = 0;
				$user->mark_read = 0;
			}

			if($user->folder_id != -2) {
				$mail = new Message;
				$mail->setSenderId(\user()->getId());
				$mail->setReceiverId($user->id);
				$mail->setFolderId($user->folder_id);
				$mail->setSubject('Clan message');
				$mail->setMessage($text);
				$mail->setStatus($user->mark_read == 1 ? 2 : 1);
				$mail->save();
			}

			if($user->id == \user()->getId() && $user->folder_id != -2 && $user->mark_read == 0) {
				view()->share('user_new_message_count', view()->shared('user_new_message_count') + 1);
			}
		}

		return view('clan.mail', ['users' => $users]);
	}

	public function getMemberRights($id = null)
	{
		if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
			throw new InvalidRequestException();

		$users = User::select(DB::raw('users.id, users.name, clan_rank.id as rank_id, clan_rank.rank_name'))
			->leftJoin('clan_rank', 'clan_rank.id', '=', 'users.clan_rank')
			->where('users.clan_id', \user()->getClanId())
			->get();

		$ranks = ClanRank::whereIn('clan_id', [\user()->getClanId(), 0])
			->get();

		$user_rank = ClanRank::find(\user()->getClanRank());

		return view('clan.memberrights', [
			'user_rank' => $user_rank,
			'users' => $users,
			'ranks' => $ranks,
            'setOwnerId' => $id
		]);
	}

	public function postSetMaster($id)
    {
        if(\user()->getClanRank() != 1)
            throw new InvalidRequestException();

        /**
         * @var User $nUser
         */
        $nUser = User::find($id);

        $nUser->setClanRank(1);
        \user()->setClanRank(2);
        $nUser->save();

        return redirect(url('/clan/memberrights'));
    }

    public function postEditRights()
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
            throw new InvalidRequestException();

        $users = Input::get('users', []);

        if(empty($users))
            throw new InvalidRequestException();

        foreach ($users as $user_id => $rank) {
            $rankCheck = ClanRank::where('id', intval($rank))->where('clan_id', \user()->getClanId())->count();

            if($rankCheck || in_array(intval($rank), [1, 2, 3])) {
                $cUser = User::find($user_id);
                $cUser->clan_rank = intval($rank);
                $cUser->save();
            }
        }

        return redirect(url('/clan/memberrights'));
    }

	public function postAddRank()
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
            throw new InvalidRequestException();

        $rank_name = Input::get('newRank');

        if(empty($rank_name))
            throw new InvalidRequestException();

        $clanRank = new ClanRank;
        $clanRank->setClanId(\user()->getClanId());
        $clanRank->setRankName($rank_name);
        $clanRank->save();

        return redirect(url('/clan/memberrights'));
    }

    public function postDeleteRank($id)
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2 || $id < 4)
            throw new InvalidRequestException();

        $userCount = User::where('clan_id', \user()->getClanId())
            ->where('clan_rank', $id)
            ->count();

        if($userCount === 0) {
            DB::statement('DELETE FROM clan_rank WHERE id = ? AND clan_id = ?', [$id, \user()->getClanId()]);
        }

        return redirect(url('/clan/memberrights'));
    }

    public function postEditRanks()
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
            throw new InvalidRequestException();

        $ranks = Input::get('ranks', []);

        foreach ($ranks as $rankId => $properties) {
            $rank = ClanRank::where('id', $rankId)
                ->where('clan_id', \user()->getClanId())
                ->first();

            if($rank) {
                foreach (ClanRank::$properties as $property) {
                    $rank->{$property} = array_key_exists($property, $properties);
                }

                $rank->save();
            }
        }

        return redirect(url('/clan/memberrights'));
    }

    public function getKickUser($id)
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
            throw new InvalidRequestException();

        return view('clan.kick_user', ['kick_user' => User::find($id)]);
    }

    public function postKickUser($id)
    {
        if(\user()->getClanRank() != 1 && \user()->getClanRank() != 2)
            throw new InvalidRequestException();

        /**
         * @var User $kUser
         */
        $kUser = User::find($id);

        /**
         * @var Clan $clan
         */
        $clan = Clan::find($kUser->getClanId());

        if(!$kUser)
            throw new InvalidRequestException();

        $kUser->setClanId(0);
        $kUser->save();

        $leftClanMsgSetting = UserMessageSettings::getUserSetting(UserMessageSettings::LEFT_CLAN);

        if($leftClanMsgSetting->getFolderId() != -2) {
            $mail = new Message;
            $mail->setSenderId(Message::SENDER_SYSTEM);
            $mail->setReceiverId($kUser->getId());
            $mail->setFolderId($leftClanMsgSetting->getFolderId());
            $mail->setSubject('Clan information');
            $mail->setMessage('You have left the following clan: '.$clan->getName().' ['.$clan->getTag().']');
            $mail->setStatus($leftClanMsgSetting->isMarkRead() == 1 ? 2 : 1);
            $mail->save();
        }

        $userIds = User::where('clan_id', $clan->getId())->get();

        foreach ($userIds as $cUser) {
            /**
             * @var User $cUser
             */

            if($cUser->getId() == \user()->getId())
                continue;

            $cMsgSetting = UserMessageSettings::getUserSetting(UserMessageSettings::CLAN_MEMBER_LEFT);

            if($cMsgSetting->getFolderId() != -2) {
                $mail = new Message;
                $mail->setSenderId(Message::SENDER_SYSTEM);
                $mail->setReceiverId($cUser->getId());
                $mail->setFolderId($cMsgSetting->getFolderId());
                $mail->setSubject('Clan information');
                $mail->setMessage('The following player has left your clan: '.$kUser->getName());
                $mail->setStatus($cMsgSetting->isMarkRead() == 1 ? 2 : 1);
                $mail->save();
            }
        }

        return redirect(url('/clan/memberrights'));
    }

	public function getDonationList()
	{
		return view('clan.donationlist');
	}

	public function getPreview($clanId)
	{
		$clan = Clan::leftJoin('users', 'users.clan_id', '=', 'clan.id')
			->leftJoin('clan_description', 'clan.id', '=', 'clan_description.clan_id')
			->select('clan.*', 'clan_description.descriptionHtml')
			->addSelect(DB::raw('IF(clan.stufe = 0, 1, clan.stufe * 3) AS max_members'))
			->addSelect(DB::raw('SUM(users.gold) AS gold_count'))
			->addSelect(DB::raw('COUNT(1) AS member_count'))
			->addSelect(DB::raw('SUM(users.gold) AS gold_count'))
			->addSelect(DB::raw('SUM(users.s_booty) AS total_booty'))
			->where('clan.id', $clanId)
			->first();

		return view('clan.preview', ['clan' => $clan]);
	}

	public function postVisitHomepage($clanId)
	{
		$clan = Clan::find($clanId);

		if(!$clan || !strlen($clan->getWebsite())) {
			return redirect('/preview/clan/'.$clanId);
		}

		$clan->setWebsiteCounter($clan->getWebsiteCounter() + 1);
		$clan->save();

		return redirect($clan->getWebsite());
	}

	public function getMemberListExternal($clanId)
	{
		$clan = Clan::find($clanId);

		if(!$clan) {
			throw new InvalidRequestException();
		}

		$memberList = User::select('users.*', 'clan_rank.rank_name')
			->leftJoin('clan_rank', 'clan_rank.id', '=', 'users.clan_rank')
			->where('users.clan_id', $clan->getId())
			->get();

		return view('clan.memberlistext', [
			'clan' => $clan,
			'memberList' => $memberList
		]);
	}

	public function getDonateList()
    {
        $userRights = ClanRank::find(\user()->getClanRank());

        if(!$userRights->spend_gold) {
            throw new InvalidRequestException();
        }

        if(Input::get('action') == 'refresh') {
            \user()->setClanDtime(time());
        }

        $order = Input::get('order', 'name');
        $type = Input::get('type', 'desc') == 'desc' ? 'desc' : 'asc';

        $viewData = [
            'order' => $order,
            'type' => $type
        ];

        if($order == 'status') {
            $order = 'clan_rank.rank_name';
        } elseif($order == 'amount') {
            $order = 'total_donate';
        } elseif($order == 'time') {
            $order = 'users.last_activity';
        } else {
            $order = 'users.name';
        }

        $query = User::select(DB::raw('users.id,
              users.name,
              clan_rank.rank_name,
              SUM(clan_donations.donation_amount) AS total_donate,
              users.last_activity,
              (SELECT COUNT(1) FROM clan_donations WHERE donation_time >= '.\user()->getClanDtime().' AND user_id = users.id) AS donate_amount'))
            ->leftJoin('clan_rank', 'clan_rank.id', '=', 'users.clan_rank')
            ->leftJoin('clan_donations', 'clan_donations.user_id', '=', 'users.id')
            ->where('users.clan_id', \user()->getClanId())
            ->groupBy('users.id')
            ->orderBy($order, $type)
            ->get();

        $viewData['userList'] = $query;

        $order2 = Input::get('order2', 'time');
        $type2 = Input::get('type2', 'desc') == 'desc' ? 'desc' : 'asc';
        $viewData['order2'] = $order2;
        $viewData['type2'] = $type2;

        if($order2 == 'status') {
            $order2 = 'clan_rank.rank_name';
        } elseif($order2 == 'amount') {
            $order2 = 'clan_donations.donation_amount';
        } elseif($order2 == 'time') {
            $order2 = 'clan_donations.donation_time';
        } else {
            $order2 = 'users.name';
        }

        $viewData['donateList'] = ClanDonations::select('clan_donations.*', 'users.id',
            'users.name', 'clan_rank.rank_name')
            ->leftJoin('users', 'users.id', '=', 'clan_donations.user_id')
            ->leftJoin('clan_rank', 'clan_rank.id', '=', 'users.clan_rank')
            ->where('clan_donations.clan_id', \user()->getClanId())
            ->orderBy($order2, $type2)
            ->get();

        return view('clan.donationlist', $viewData);
    }

    public function getApply($id)
    {
        if(\user()->getClanId() > 0)
            throw new InvalidRequestException();

        $clan = ClanApplications::select(DB::raw('clan.name, clan.tag, clan_applications.id as application_id'))
            ->leftJoin('clan', function($join) {
                $join->on('clan.id', '=', 'clan_applications.id');
                $join->on('clan_applications.user_id', '=', DB::raw(\user()->getId()));
            })
            ->where('clan_applications.id', \user()->getClanId())
            ->first();

        return view('clan.apply', ['clan' => $clan]);
    }

    public function postApply($id)
    {
        $appText = Input::get('applicationText');

        if(strlen($appText) > 2000 || \user()->getClanId() > 0)
            throw new InvalidRequestException();

        $clan = Clan::find($id);

        if(!$clan)
            throw new InvalidRequestException();

        $application = new ClanApplications;
        $application->setClanId($id);
        $application->setUserId(\user()->getId());
        $application->setNote($appText);
        $application->setApplicationTime(time());
        $application->save();

        return view('clan.apply', ['applied' => true]);
    }

    public function getApplications()
    {
        /**
         * @var ClanRank $ranks
         */
        $ranks = ClanRank::find(\user()->getClanRank());

        if(\user()->getClanId() == 0 || !$ranks->isAddMembers())
            throw new InvalidRequestException();

        $applications = ClanApplications::select('clan_applications.*', 'users.name')
            ->leftJoin('users', 'users.id', '=', 'clan_applications.user_id')
            ->where('clan_applications.clan_id', \user()->getClanId())
            ->get();

        if(count($applications) == 0) {
            return redirect(url('/clan/index'));
        }

        return view('clan.applications', ['applications' => $applications]);
    }

    public function postApplications($id)
    {
        /**
         * @var ClanRank $ranks
         */
        $ranks = ClanRank::find(\user()->getClanRank());

        if(\user()->getClanId() == 0 || !$ranks->isAddMembers())
            throw new InvalidRequestException();

        /**
         * @var ClanApplications $application
         */
        $application = ClanApplications::where('clan_id', \user()->getClanId())
            ->find($id);

        if(!$application)
            throw new InvalidRequestException();

        $rejectText = Input::get('abltext');
        $accept = Input::get('ann');

        /**
         * @var Clan $clan
         * @var User $applicationUser
         */
        $clan = Clan::find(\user()->getClanId());
        $applicationUser = User::find($application->getUserId());

        if($accept) {
            $applicationUser->setClanId($clan->getId());
            $applicationUser->setClanRank(3);
            $applicationUser->save();
            $application->delete();

            $applicationUserMessageSettings = UserMessageSettings::getUserSetting(UserMessageSettings::CLAN_APP_ACCEPTED);

            if($applicationUserMessageSettings->getFolderId() != UserMessageSettings::FOLDER_ID_DELETE_IMMEDIATELY) {
                $message = new Message;
                $message->setSenderId(Message::SENDER_SYSTEM);
                $message->setReceiverId($applicationUser->getId());
                $message->setFolderId($applicationUserMessageSettings->getFolderId());
                $message->setSubject('Clan application reply');
                $message->setMessage('You are now a member of the clan '.$clan->getName());
                $message->setStatus($applicationUserMessageSettings->isMarkRead() == 1 ? 2 : 1);
                $message->save();
            }
        } else {
            $applicationUserMessageSettings = UserMessageSettings::getUserSetting(UserMessageSettings::CLAN_APP_REJECTED);

            if($applicationUserMessageSettings->getFolderId() != UserMessageSettings::FOLDER_ID_DELETE_IMMEDIATELY) {
                $message = new Message;
                $message->setSenderId(Message::SENDER_SYSTEM);
                $message->setReceiverId($applicationUser->getId());
                $message->setFolderId($applicationUserMessageSettings->getFolderId());
                $message->setSubject('Clan application reply');
                $message->setMessage('Your application to the clan '.$clan->getName().' has been rejected.'.(strlen($rejectText) > 0 ? ' Reason: '.$rejectText : ''));
                $message->setStatus($applicationUserMessageSettings->isMarkRead() == 1 ? 2 : 1);
                $message->save();
            }

            $application->delete();
        }

        return redirect(url('/clan/applications'));
    }
}