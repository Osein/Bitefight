<?php
/**
 * Created by PhpStorm.
 * User: Osein
 * Date: 1/27/2018
 * Time: 7:24 PM
 */

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use Database\Models\Message;
use Database\Models\User;
use Database\Models\UserMessageBlock;
use Database\Models\UserMessageFolder;
use Database\Models\UserMessageSettings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class MessageController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getIndex()
	{
        $inbox = new \stdClass();
        $inbox->folder_name = 'Inbox';
        $inbox->id = 0;
        $inbox->newMsgCount = Message::where('receiver_id', user()->getId())
            ->where('status', 1)
            ->where('folder_id', 0)
            ->count();
        $inbox->msgCount = Message::where('receiver_id', user()->getId())
            ->where('folder_id', 0)
            ->count();

        $outbox = new \stdClass();
        $outbox->folder_name = 'Outbox';
        $outbox->id = -1;
        $outbox->newMsgCount = 0;
        $outbox->msgCount = Message::where('sender_id', user()->getId())
            ->where('folder_id', 0)
            ->count();

        $folders = array(
            $inbox
        );

        /**
         * @var Collection $dbFolders
         */
        $dbFolders = UserMessageFolder::select(DB::raw('user_message_folder.folder_name,
              user_message_folder.id,
              COUNT(messages.id) AS newMsgCount,
              (SELECT COUNT(1) FROM messages WHERE folder_id = user_message_folder.id AND receiver_id = user_message_folder.user_id) AS msgCount'))
            ->leftJoin('messages', function($join) {
                $join->on('messages.folder_id', 'user_message_folder.id');
                $join->on('messages.receiver_id', DB::raw(user()->getId()));
                $join->on('messages.status', DB::raw(1));
            })->where('user_message_folder.user_id', user()->getId())
            ->groupBy('user_message_folder.id')
            ->get();

        if(count($dbFolders) > 0) {
            $folders = array_merge($folders, $dbFolders->all());
        }

        $folders[] = $outbox;

		return view('message.index', ['folders' => $folders]);
	}

	public function getRead()
    {
        $folder_id = Input::get('folder', 0);
        $page = Input::get('page', 1);

        /**
         * @var UserMessageFolder $folder
         */
        $folder = UserMessageFolder::find($folder_id);

        if($folder_id == 0) {
            $folder = new UserMessageFolder;
            $folder->setUserId(user()->getId());
            $folder->setFolderName('Inbox');
            $folder->setId(0);
        } elseif($folder_id == -1) {
            $folder = new UserMessageFolder;
            $folder->setUserId(user()->getId());
            $folder->setFolderName('Outbox');
            $folder->setId(-1);
        }

        if($folder->getId() == -1) {
            $messages = Message::where('sender_id', user()->getId())
                ->orderBy('send_time', 'desc')->orderBy('id', 'desc')
                ->skip(($page - 1) * 15)
                ->take(15)
                ->get();

            $messageCount = Message::where('sender_id', user()->getId())
                ->count();
        } else {
            if(user()->getId() != $folder->getUserId())
                throw new InvalidRequestException();

            $messages = Message::select('messages.*', 'users.name')
                ->leftJoin('users', 'users.id', '=', 'messages.sender_id')
                ->where('messages.folder_id', $folder->getId())
                ->where('messages.receiver_id', user()->getId())
                ->orderBy('messages.send_time', 'desc')->orderBy('messages.id', 'desc')
                ->skip(($page - 1) * 15)
                ->take(15)
                ->get();

            $messageCount = Message::where('folder_id', $folder->getId())
                ->where('receiver_id', user()->getId())
                ->count();
        }

        $messageFilteredCount = count($messages);

        for($i = 0; $i < $messageFilteredCount; $i++) {
            if($i > 0) {
                $messages[$i]->previous_id = $messages[$i - 1]->id;
            }

            if($i < $messageFilteredCount - 1) {
                $messages[$i]->next_id = $messages[$i + 1]->id;
            }
        }

        return view('message.read', [
            'folders' => UserMessageFolder::where('user_id', user()->getId())->get(),
            'folder' => $folder,
            'msg_count' => $messageCount,
            'messages' => $messages,
            'page' => $page
        ]);
    }

    public function postRead()
    {
        $select = Input::get('select', 'masked');
        $do = Input::get('do', 'read');
        $folder = Input::get('folder', 0);
        $page = Input::get('page', 1);

        if($select == 'masked') {
            $msgIds = array();

            foreach (Input::get() as $p => $v) {
                if(substr($p, 0, 1) == 'x') {
                    $msgIds[] = intval(substr($p, 1));
                }
            }

            if(count($msgIds)) {
                $messages = Message::where('receiver_id', \user()->getId())
                    ->whereIn('id', $msgIds)
                    ->get();

                if($do == 'read') {
                    foreach ($messages as $msg) {
                        $msg->status = 2;
                        $msg->save();
                    }
                } elseif($do == 'del') {
                    foreach ($messages as $msg) {
                        $msg->delete();
                    }
                } else {
                    $do_exploded = explode('move-to-', $do);

                    if(!isset($do_exploded[1])) {
                        throw new InvalidRequestException();
                    }

                    $folder_id = intval($do_exploded[1]);

                    if($folder_id != 0) {
                        $folder_check = UserMessageFolder::where('user_id', \user()->getId())->find($folder_id);

                        if(!$folder_check) {
                            throw new InvalidRequestException();
                        }
                    }

                    foreach ($messages as $msg) {
                        $msg->folder_id = $folder_id;
                        $msg->save();
                    }
                }
            }
        } elseif($select == 'all') {
            if($do == 'read') {
                DB::statement('UPDATE messages SET status = 2 WHERE receiver_id = ?', [\user()->getId()]);
            } elseif($do == 'del') {
                DB::statement('DELETE FROM messages WHERE receiver_id = ?', [\user()->getId()]);
            } else {
                $do_exploded = explode('move-to-', $do);

                if(!isset($do_exploded[1])) {
                    throw new InvalidRequestException();
                }

                $folder_id = intval($do_exploded[1]);

                if($folder_id != 0) {
                    $folder_check = UserMessageFolder::where('user_id', \user()->getId())->find($folder_id);

                    if(!$folder_check) {
                        throw new InvalidRequestException();
                    }
                }

                DB::statement('UPDATE messages SET folder_id = ? WHERE folder_id = ?', [$folder_id, $folder]);
            }
        } elseif($select == 'unmasked') {
            $msgIds = array();

            foreach (Input::get() as $p => $v) {
                if(substr($p, 0, 1) == 'x') {
                    $msgIds[] = intval(substr($p, 1));
                }
            }

            $messages = Message::where('receiver_id', \user()->getId())
                ->limit(15)
                ->offset(($page - 1) * 15)
                ->find_many();

            foreach ($messages as $msg) {
                if(in_array($msg->id, $msgIds)) {
                    continue;
                }

                if($do == 'read') {
                    $msg->status = 2;
                    $msg->save();
                } elseif($do == 'del') {
                    $msg->delete();
                }
            }
        }

        return redirect(url('/message/read?folder='.$folder.'&page='.$page));
    }

    public function getFolders()
    {
        return view('message.folders', [
            'folders' => UserMessageFolder::where('user_id', user()->getId())
                ->orderBy('folder_order', 'asc')
                ->get(),
            'folder_max_min' => UserMessageFolder::select(DB::raw(
                'MAX(folder_order) AS max, MIN(folder_order) AS min'
            ))->where('user_id', user()->getId())->first(),
            'folder_rename_id' => session()->get('folder_rename_id')
        ]);
    }

    public function postFolders()
    {
        $action = Input::get('action', '');

        if($action == 'create folder') {
            $folder_name = Input::get('name');

            if(empty($folder_name)) {
                return redirect(url('/message/folders'));
            }

            $folder_count = UserMessageFolder::where('user_id', user()->getId())->count();

            if($folder_count > 3) {
                session()->flash('folder_create_error', 'You have reached the folder limit. Please delete a folder in order to create a new one.');
                return redirect(url('/message/folders'));
            }

            $folder_exists = UserMessageFolder::where('folder_name', $folder_name)
                ->where('user_id', user()->getId())
                ->count();

            if($folder_exists) {
                session()->flash('folder_create_error', 'Folder name is already in use');
                return redirect(url('/message/folders'));
            }

            $folder = new UserMessageFolder;
            $folder->setUserId(user()->getId());
            $folder->setFolderName($folder_name);
            $folder->setFolderOrder($folder_count + 1);
            $folder->save();

            session()->flash('folder_create_error', 'Folder created');
        } elseif($action == 'rename') {
            session()->flash('folder_rename_id', Input::get('folderid'));
        } elseif($action == 'delete') {
            $folder_id = Input::get('folderid');
            DB::statement('DELETE FROM user_message_folder WHERE user_id = ? AND id = ?', [user()->getId(), $folder_id]);
            DB::statement('UPDATE messages SET folder_id = 0 WHERE receiver_id = ? AND folder_id = ?', [user()->getId(), $folder_id]);
        } elseif($action == 'save new name') {
            $newName = Input::get('name');

            if(empty($newName)) {
                return redirect(url('/message/folders'));
            }

            $folder_exists = UserMessageFolder::where('user_id', user()->getId())
                ->where('folder_name', $newName)
                ->count();

            if($folder_exists) {
                session()->flash('folder_action_error', 'Folder name is already in use');
                return redirect(url('/message/folders'));
            }

            DB::statement('UPDATE user_message_folder SET folder_name = ? WHERE user_id = ? AND id = ?',
                [$newName, user()->getId(), Input::get('folderid')]);
        } elseif($action == 'move up') {
            $folder = UserMessageFolder::where('user_id', user()->getId())
                ->where('id', Input::get('folderid'))
                ->first();

            if(!$folder) {
                return redirect(url('/message/folders'));
            }

            $upperFolder = UserMessageFolder::where('folder_order', '<', $folder->folder_order)
                ->where('user_id', user()->getId())
                ->orderBy('folder_order', 'desc')
                ->limit(1)
                ->first();

            if(!$upperFolder) {
                return redirect(url('/message/folders'));
            }

            $currentFolderOrder = $folder->folder_order;
            $folder->folder_order = $upperFolder->folder_order;
            $folder->save();
            $upperFolder->folder_order = $currentFolderOrder;
            $upperFolder->save();
        } elseif($action == 'move down') {
            $folder = UserMessageFolder::where('user_id', user()->getId())
                ->where('id', Input::get('folderid'))
                ->first();

            if(!$folder) {
                return redirect(url('/message/folders'));
            }

            $downFolder = UserMessageFolder::where('folder_order', '>', $folder->folder_order)
                ->where('user_id', user()->getId())
                ->orderBy('folder_order', 'asc')
                ->limit(1)
                ->first();

            if(!$downFolder) {
                return redirect(url('/message/folders'));
            }

            $currentFolderOrder = $folder->folder_order;
            $folder->folder_order = $downFolder->folder_order;
            $folder->save();
            $downFolder->folder_order = $currentFolderOrder;
            $downFolder->save();
        }

        return redirect(url('/message/folders'));
    }

    public function getBlockedPlayers()
    {
        return view('message.blocked_players', [
            'blocked_users' => UserMessageBlock::select('users.id', 'users.name')
                ->leftJoin('users', 'users.id', '=', 'user_message_block.blocked_id')
                ->where('user_message_block.user_id', user()->getId())
                ->get()
        ]);
    }

    public function postBlockedPlayers()
    {
        $action = Input::get('action');

        if($action == 'add') {
            $name = Input::get('name');

            if(!empty($name)) {
                $blockUser = User::where('name', $name)->first();

                if($blockUser) {
                    $blockObj = new UserMessageBlock;
                    $blockObj->setUserId(\user()->getId());
                    $blockObj->setBlockedId($blockUser->id);
                    $blockObj->save();
                } else {
                    session()->flash('message_block_info', 'This player doesn`t exist');
                }
            } else {
                session()->flash('message_block_info', 'This player doesn`t exist');
            }
        } elseif($action == 'delete') {
            $user_id = Input::get('list');
            if(!empty($user_id))
                DB::statement('DELETE FROM user_message_block WHERE blocked_id = ?', [$user_id]);
        }

        return redirect(url('/message/block'));
    }

    public function getSettings()
    {
        return view('message.settings', [
            'folders' => UserMessageFolder::where('user_id', \user()->getId())
                ->orderBy('folder_order', 'asc')
                ->get(),
            'msgSettings' => UserMessageSettings::getUserSettings()
        ]);
    }

    public function postSettings()
    {
        $userSettings = UserMessageSettings::where('user_id', \user()->getId())
            ->get();

        $userMessageFolders = array(-2, 0);
        $userMessageFoldersRes = UserMessageFolder::where('user_id', \user()->getId())
            ->get();

        foreach ($userMessageFoldersRes as $folder) {
            $userMessageFolders[] = $folder->id;
        }

        $requestVars = Input::get();

        foreach ($requestVars as $var => $val) {
            if(substr($var, 0, 1) == 'x') {
                $settingId = substr($var, 1);

                isset($requestVars['m'.$settingId])?$settingReadCheckbox = 1: $settingReadCheckbox = 0;

                $settingType = UserMessageSettings::getMessageSettingTypeFromSettingViewId($settingId);

                if(empty($settingType) || (!in_array($val, $userMessageFolders))) {
                    continue;
                }

                $settingExists = false;

                foreach ($userSettings as $settingObj) {
                    if(strtolower($settingObj->setting) == strtolower($settingType)) {
                        $settingObj->folder_id = $val;
                        $settingObj->mark_read = $settingReadCheckbox;
                        $settingObj->save();
                        $settingExists = true;
                    }
                }

                if(!$settingExists) {
                    $setting = new UserMessageSettings();
                    $setting->setUserId(\user()->getId());
                    $setting->setSetting($settingType);
                    $setting->setFolderId($val);
                    $setting->setMarkRead($settingReadCheckbox);
                    $setting->save();
                }
            }
        }

        return redirect(url('/message/settings'));
    }

    public function ajaxCheckRecipient()
    {
        $list = array();

        $name = Input::get('name');

        if(!empty($name)) {
            $users = User::select('users.name')
                ->leftJoin('user_message_block', function($join) {
                    $join->on('users.id', '=', 'user_message_block.user_id');
                    $join->on('user_message_block.blocked_id', '=', DB::raw(\user()->getId()));
                })
                ->whereNull('user_message_block.user_id')
                ->where('users.name', 'LIKE', '%'.$name.'%')
                ->take(10)
                ->get();

            foreach ($users as $user) {
                $list[] = $user->name;
            }
        }

        return json_encode(['list' => $list]);
    }

    public function ajaxReadMessage() {
        $msgnr = Input::get('msgnr');

        $msg = Message::where('receiver_id', \user()->getId())->find($msgnr);

        if(!$msg) {
            throw new InvalidRequestException();
        }

        $msg->status = 2;
        $msg->save();

        $newMessageCount = Message::where('receiver_id', \user()->getId())
            ->where('status', 1)
            ->count();

        return json_encode(['msgicon' => 2, 'msgmenu' => $newMessageCount > 0 ? 'newmessage' : '']);
    }

    public function ajaxWriteMessage()
    {
        $receiverName = Input::get('receivername');
        $messageText = Input::get('message');
        $subject = Input::get('subject');

        return $this->ajaxSendAnswerBase(null, $receiverName, $subject, $messageText, false);
    }

    public function ajaxSendAnswer()
    {
        $receiverid = Input::get('receiverid');
        $messageText = Input::get('message');
        $subject = Input::get('subject');
        $msgnr = Input::get('msgnr');

        DB::statement('UPDATE messages SET status = 3 WHERE receiver_id = ? AND id = ?', [\user()->getId(), $msgnr]);

        return $this->ajaxSendAnswerBase($receiverid, null, $subject, $messageText, true);
    }

    public function ajaxSendAnswerBase($receiverId = null, $receiverName = null, $subject, $messageText, $answer = false)
    {
        $responseData = new \stdClass();
        $responseData->errorstatus = 0;
        $responseData->error = 'message sent';

        if($receiverName != null) {
            $receiver = User::where('name', $receiverName)->first();
        } else {
            $receiver = User::find($receiverId);
        }

        if(!$receiver) {
            $responseData->errorstatus = 1;
            $responseData->error = 'This player doesn`t exist';
        }

        if(strlen($subject) < 2) {
            $responseData->errorstatus = 1;
            $responseData->error = 'Subject must contain at least 2 characters';
        }

        if(strlen($messageText) > 2000) {
            $responseData->errorstatus = 1;
            $responseData->error = 'Message can not have higher than 2000 characters';
        }

        $spamCheck = Message::where('sender_id', \user()->getId())
            ->where('send_time', '>=', time() - 300)
            ->count();

        if($spamCheck > 5) {
            $responseData->errorstatus = 1;
            $responseData->error = 'You can\'t send more than 5 messages in 5 minutes';
        }

        $blockCheck = UserMessageBlock::where('blocked_id', \user()->getId())
            ->where('user_id', $receiver->id)
            ->count();

        if($blockCheck) {
            $responseData->errorstatus = 1;
            $responseData->error = 'You can\'t send message to a person that blocked you';
        }

        if(!$responseData->errorstatus) {
            $message = new Message;
            $message->setSenderId(\user()->getId());
            $message->setReceiverId($receiver->id);
            $message->setSubject($subject);
            $message->setMessage($messageText);
            $message->save();
        }

        if($answer) {
            $responseData->msgmenu = 'newmessage';
            $responseData->msgicon = 3;
        }

        return json_encode($responseData);
    }

}