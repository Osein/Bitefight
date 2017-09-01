<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 18/04/17
 * Time: 22:20
 */

namespace Bitefight\Controllers;

use Bitefight\Models\MessageSettings;
use ORM;
use PDO;
use Phalcon\Filter;
use Phalcon\Http\Response;

class MessageController extends GameController
{

    public function initialize()
    {
        $this->view->menu_active = 'message';
        return parent::initialize();
    }

    public function getIndex()
    {
        $inbox = new \stdClass();
        $inbox->folder_name = 'Inbox';
        $inbox->id = 0;
        $inbox->newMsgCount = ORM::for_table('message')
            ->where('receiver_id', $this->user->id)
            ->where('status', 1)
            ->where('folder_id', 0)
            ->count();
        $inbox->msgCount = ORM::for_table('message')
            ->where('receiver_id', $this->user->id)
            ->where('folder_id', 0)
            ->count();

        $outbox = new \stdClass();
        $outbox->folder_name = 'Outbox';
        $outbox->id = -1;
        $outbox->newMsgCount = 0;
        $outbox->msgCount = ORM::for_table('message')
            ->where('sender_id', $this->user->id)
            ->where('folder_id', 0)
            ->count();

        $folders = array(
            $inbox
        );

        $pdo = ORM::getDb();
        $stmt = $pdo->prepare('
            SELECT
              umf.folder_name,
              umf.id,
              COUNT(m.id) AS newMsgCount,
              (SELECT COUNT(1) FROM message WHERE folder_id = umf.id AND receiver_id = umf.user_id) AS msgCount
            FROM user_message_folder umf
            LEFT JOIN message m ON m.folder_id = umf.id AND m.receiver_id = ? AND m.status = 1
            WHERE umf.user_id = ?
            GROUP BY umf.id
        ');

        $stmt->execute([$this->user->id, $this->user->id]);
        $dbFolders = $stmt->fetchAll(PDO::FETCH_OBJ);

        $folders = array_merge($folders, $dbFolders);
        $folders[] = $outbox;

        $this->view->folders = $folders;
        $this->view->pick('message/index');
    }

    public function getRead()
    {
        $folder_id = $this->request->get('folder', Filter::FILTER_INT, 0);
        $page = $this->request->get('page', Filter::FILTER_INT, 1);
        $folder = ORM::for_table('user_message_folder')
            ->find_one($folder_id);

        if($folder_id == 0) {
            $folder = new \stdClass();
            $folder->user_id = $this->user->id;
            $folder->folder_name = 'Inbox';
            $folder->id = 0;
        } elseif($folder_id == -1) {
            $folder = new \stdClass();
            $folder->user_id = $this->user->id;
            $folder->folder_name = 'Outbox';
            $folder->id = -1;
        }

        if($folder_id == -1) {
            $messages = ORM::for_table('message')
                ->where('sender_id', $this->user->id)
                ->offset(($page - 1) * 15)
                ->limit(15)
                ->orderByDesc('sent_at')
                ->orderByDesc('id')
                ->find_many();

            $messageCount = ORM::for_table('message')
                ->where('sender_id', $this->user->id)
                ->count();
        } else {
            if($folder->user_id !== $this->user->id) {
                return $this->response->redirect(getUrl('message'));
            }

            $messages = ORM::for_table('message')
                ->select_many('message.*', 'user.name')
                ->where('folder_id', $folder_id)
                ->where('receiver_id', $this->user->id)
                ->left_outer_join('user', ['user.id', '=', 'message.sender_id'])
                ->offset(($page - 1) * 15)
                ->orderByDesc('sent_at')
                ->orderByDesc('id')
                ->limit(15)
                ->find_many();

            $messageCount = ORM::for_table('message')
                ->where('folder_id', $folder_id)
                ->where('receiver_id', $this->user->id)
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

        $this->view->folders = ORM::for_table('user_message_folder')->where('user_id', $this->user->id)->find_many();
        $this->view->folder = $folder;
        $this->view->msg_count = $messageCount;
        $this->view->messages = $messages;
        $this->view->page = $page;
        $this->view->pick('message/read');
    }

    public function postRead()
    {
        $select = $this->request->get('select', Filter::FILTER_STRING, 'masked');
        $do = $this->request->get('do', Filter::FILTER_STRING, 'read');
        $folder = $this->request->get('folder', Filter::FILTER_INT, 0);
        $page = $this->request->get('page', Filter::FILTER_INT, 1);

        if($select == 'masked') {
            $msgIds = array();

            foreach ($this->request->get() as $p => $v) {
                if(substr($p, 0, 1) == 'x') {
                    $msgIds[] = intval(substr($p, 1));
                }
            }

            if(count($msgIds)) {
                $messages = ORM::for_table('message')
                    ->where('receiver_id', $this->user->id)
                    ->where_in('id', $msgIds)
                    ->find_many();

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
                        return $this->notFound();
                    }

                    $folder_id = intval($do_exploded[1]);

                    if($folder_id != 0) {
                        $folder_check = ORM::for_table('user_message_folder')->find_one($folder_id);

                        if(!$folder_check) {
                            return $this->notFound();
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
                ORM::raw_execute('UPDATE message SET status = 2 WHERE receiver_id = ?', [$this->user->id]);
            } elseif($do == 'del') {
                ORM::raw_execute('DELETE FROM message WHERE receiver_id = ?', [$this->user->id]);
            } else {
                $do_exploded = explode('move-to-', $do);

                if(!isset($do_exploded[1])) {
                    return $this->notFound();
                }

                $folder_id = intval($do_exploded[1]);

                if($folder_id != 0) {
                    $folder_check = ORM::for_table('user_message_folder')->find_one($folder_id);

                    if(!$folder_check) {
                        return $this->notFound();
                    }
                }

                ORM::raw_execute('UPDATE message SET folder_id = ? WHERE folder_id = ?', [$folder_id, $folder]);
            }
        } elseif($select == 'unmasked') {
            $msgIds = array();

            foreach ($this->request->get() as $p => $v) {
                if(substr($p, 0, 1) == 'x') {
                    $msgIds[] = intval(substr($p, 1));
                }
            }

            $messages = ORM::for_table('message')
                ->where('receiver_id', $this->user->id)
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

        return $this->response->redirect(getUrl('message/read?folder='.$folder.'&page='.$page));
    }

    public function jsonCheckReceiver()
    {
        $token = $this->request->get('__token');
        $tokenKey = $this->request->get('__tkey');
        $response = new Response();
        $list = array();

        if ($this->security->checkToken($tokenKey, $token, false)) {
            $name = $this->request->get('name', Filter::FILTER_STRING, '');

            if(!empty($name)) {
                $users = ORM::for_table('user')
                    ->select('name')
                    ->where_like('name', '%'.$name.'%')
                    ->limit(10)
                    ->find_many();

                foreach ($users as $user) {
                    $list[] = $user->name;
                }
            }
        }

        $response->setJsonContent(array('list' => $list));
        return $response;
    }

    private function pWriteMessage($receiverId, $text, $subject)
    {
        $responseData = new \stdClass();
        $responseData->errorstatus = 0;
        $responseData->error = 'message sent';
        $response = new Response();

        $receiver = ORM::for_table('user')->find_one($receiverId);

        if(!$receiver) {
            $responseData->errorstatus = 1;
            $responseData->error = 'This player doesn`t exist';
        }

        if(strlen($subject) < 2) {
            $responseData->errorstatus = 1;
            $responseData->error = 'Subject must contain at least 2 characters';
        }

        if(strlen($text) > 2000) {
            $responseData->errorstatus = 1;
            $responseData->error = 'Message can not have higher than 2000 characters';
        }

        $spamCheck = ORM::for_table('message')
            ->where('sender_id', $this->user->id)
            ->where_gte('sent_at', time() - 300)
            ->count();

        if($spamCheck > 5) {
            $responseData->errorstatus = 1;
            $responseData->error = 'You can\'t send more than 5 messages in 5 minutes';
        }

        if(!$responseData->errorstatus) {
            $message = ORM::for_table('message')->create();
            $message->sender_id = $this->user->id;
            $message->receiver_id = $receiver->id;
            $message->type = MESSAGE_TYPE_USER_MESSAGE;
            $message->subject = $subject;
            $message->message = $text;
            $message->save();
        }

        $response->setJsonContent($responseData);
        return $response;
    }

    public function jsonWriteMessage()
    {
        $token = $this->request->get('__token');
        $tokenKey = $this->request->get('__tkey');
        $receiverName = $this->request->get('receivername', Filter::FILTER_STRING, '');
        $messageText = $this->request->get('message', Filter::FILTER_STRING, '');
        $subject = $this->request->get('subject', Filter::FILTER_STRING, '');

        if(!$this->security->checkToken($tokenKey, $token, false)) {
            $responseData = new \stdClass();
            $responseData->errorstatus = 1;
            $responseData->error = 'Token error';
            $response = new Response();
            $response->setJsonContent($responseData);
            return $response;
        }

        $user = ORM::for_table('user')->where('name', $receiverName)->find_one();
        return $this->pWriteMessage($user ? $user->id : 0, $messageText, $subject);
    }

    public function jsonSendAnswer()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');
        $receiverid = $this->request->get('receiverid', Filter::FILTER_STRING, '');
        $messageText = $this->request->get('message', Filter::FILTER_STRING, '');
        $subject = $this->request->get('subject', Filter::FILTER_STRING, '');

        if(!$this->security->checkToken($tokenKey, $token, false)) {
            $responseData = new \stdClass();
            $responseData->errorstatus = 1;
            $responseData->error = 'Token error';
            $response = new Response();
            $response->setJsonContent($responseData);
            return $response;
        }

        $response = $this->pWriteMessage($receiverid, $messageText, $subject);
        $jsonContent = $response->getContent();
        $responseObj = json_decode($jsonContent);
        $responseObj->msgmenu = 'newmessage';

        if($responseObj->errorstatus == 0) {
            $msgnr = $this->request->get('msgnr', Filter::FILTER_INT, 0);
            $msg = ORM::for_table('message')->find_one($msgnr);

            if($msg && $msg->receiver_id == $this->user->id) {
                $msg->status = 3;
                $msg->save();
                $responseObj->msgicon = 3;
            }
        }

        $response = new Response();
        $response->setJsonContent($responseObj);
        return $response;
    }

    public function jsonReadMessage() {
        $this->view->disable();
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');
        $msgnr = $this->request->get('msgnr', Filter::FILTER_INT, 0);

        $msg = ORM::for_table('message')->where('receiver_id', $this->user->id)->find_one($msgnr);

        if(!$this->security->checkToken($tokenKey, $token, false) || !$msg) {
            return $this->notFound();
        }

        $msg->status = 2;
        $msg->save();

        $newMessageCount = ORM::for_table('message')
            ->where('receiver_id', $this->user->id)
            ->where('status', 1)
            ->count();

        $response = new Response();
        $response->setJsonContent(['msgicon' => 2, 'msgmenu' => $newMessageCount > 0 ? 'newmessage' : '']);
        return $response;
    }

    public function getFolders()
    {
        $this->view->folder_action_info = $this->getFlashData('folder_action_error');
        $this->view->folder_create_info = $this->getFlashData('folder_create_error');
        $this->view->folder_rename_id = $this->getFlashData('folder_rename_id');
        $this->view->folders = ORM::for_table('user_message_folder')
            ->where('user_id', $this->user->id)
            ->orderByAsc('folder_order')
            ->find_many();
        $this->view->folder_max_min = ORM::for_table('user_message_folder')
            ->selectExpr('MAX(folder_order)', 'max')
            ->selectExpr('MIN(folder_order)', 'min')
            ->where('user_id', $this->user->id)
            ->find_one();
        $this->view->pick('message/folders');
    }

    public function postFolders()
    {
        $action = $this->request->get('action', Filter::FILTER_STRING, '');

        if($action == 'create folder') {
            $folder_name = $this->request->get('name', Filter::FILTER_STRING, '');
            //Folder created
            if(empty($folder_name)) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $folder_count = ORM::for_table('user_message_folder')->where('user_id', $this->user->id)->count();

            if($folder_count > 3) {
                $this->setFlashData('folder_create_error', 'You have reached the folder limit. Please delete a folder in order to create a new one.');
                return $this->response->redirect(getUrl('message/folders'));
            }

            $folder_exists = ORM::for_table('user_message_folder')
                ->where('folder_name', $folder_name)
                ->where('user_id', $this->user->id)
                ->count();

            if($folder_exists) {
                $this->setFlashData('folder_create_error', 'Folder name is already in use');
                return $this->response->redirect(getUrl('message/folders'));
            }

            $folder = ORM::for_table('user_message_folder')->create();
            $folder->user_id = $this->user->id;
            $folder->folder_name = $folder_name;
            $folder->folder_order = $folder_count + 1;
            $folder->save();

            $this->setFlashData('folder_create_error', 'Folder created');
            return $this->response->redirect(getUrl('message/folders'));
        } elseif($action == 'delete') {
            $folder_id = $this->request->get('folderid');

            ORM::raw_execute('DELETE FROM user_message_folder WHERE user_id = ? AND id = ?',
                [$this->user->id, $this->request->get('folderid')]);

            ORM::raw_execute('UPDATE user_message_settings SET folder_id = 0 WHERE user_id = ? AND folder_id = ?',
                [$this->user->id, $folder_id]);

            return $this->response->redirect(getUrl('message/folders'));
        } elseif($action == 'rename') {
            $this->setFlashData('folder_rename_id', $this->request->get('folderid'));
            return $this->response->redirect(getUrl('message/folders'));
        } elseif($action == 'save new name') {
            $newName = $this->request->get('name', Filter::FILTER_STRING, '');

            if(empty($newName)) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $folder_exists = ORM::for_table('user_message_folder')
                ->where('user_id', $this->user->id)
                ->where('folder_name', $newName)
                ->count();

            if($folder_exists) {
                $this->setFlashData('folder_action_error', 'Folder name is already in use');
                return $this->response->redirect(getUrl('message/folders'));
            }

            ORM::raw_execute('UPDATE user_message_folder SET folder_name = ? WHERE user_id = ? AND id = ?',
                [$newName, $this->user->id, $this->request->get('folderid')]);

            return $this->response->redirect(getUrl('message/folders'));
        } elseif($action == 'move up') {
            $folder = ORM::for_table('user_message_folder')
                ->where('user_id', $this->user->id)
                ->where('id', $this->request->get('folderid'))
                ->find_one();

            if(!$folder) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $upperFolder = ORM::for_table('user_message_folder')
                ->where_lt('folder_order', $folder->folder_order)
                ->where('user_id', $this->user->id)
                ->orderByDesc('folder_order')
                ->limit(1)
                ->find_one();

            if(!$upperFolder) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $currentFolderOrder = $folder->folder_order;
            $folder->folder_order = $upperFolder->folder_order;
            $folder->save();
            $upperFolder->folder_order = $currentFolderOrder;
            $upperFolder->save();
            return $this->response->redirect(getUrl('message/folders'));
        } elseif($action == 'move down') {
            $folder = ORM::for_table('user_message_folder')
                ->where('user_id', $this->user->id)
                ->where('id', $this->request->get('folderid'))
                ->find_one();

            if(!$folder) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $downFolder = ORM::for_table('user_message_folder')
                ->where_gt('folder_order', $folder->folder_order)
                ->where('user_id', $this->user->id)
                ->orderByAsc('folder_order')
                ->limit(1)
                ->find_one();

            if(!$downFolder) {
                return $this->response->redirect(getUrl('message/folders'));
            }

            $currentFolderOrder = $folder->folder_order;
            $folder->folder_order = $downFolder->folder_order;
            $folder->save();
            $downFolder->folder_order = $currentFolderOrder;
            $downFolder->save();
            return $this->response->redirect(getUrl('message/folders'));
        }
    }

    public function getSettings()
    {
        $this->view->folders = ORM::for_table('user_message_folder')
            ->where('user_id', $this->user->id)
            ->orderByAsc('folder_order')
            ->find_many();

        $this->view->msgSettings = MessageSettings::getUserSettings();

        $this->view->pick('message/settings');
    }

    public function postSettings()
    {
        $userSettings = ORM::for_table('user_message_settings')
            ->where('user_id', $this->user->id)
            ->find_many();

        $userMessageFolders = array(-2, 0);
        $userMessageFoldersRes = ORM::for_table('user_message_folder')
            ->where('user_id', $this->user->id)
            ->find_many();

        foreach ($userMessageFoldersRes as $folder) {
            $userMessageFolders[] = $folder->id;
        }

        $requestVars = $this->request->get();

        foreach ($requestVars as $var => $val) {
            if(substr($var, 0, 1) == 'x') {
                $settingId = substr($var, 1);

                isset($requestVars['m'.$settingId])?$settingReadCheckbox = 1: $settingReadCheckbox = 0;

                $settingType = MessageSettings::getMessageSettingTypeFromSettingViewId($settingId);

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
                    $setting = ORM::for_table('user_message_settings')
                        ->create();
                    $setting->user_id = $this->user->id;
                    $setting->setting = $settingType;
                    $setting->folder_id = $val;
                    $setting->mark_read = $settingReadCheckbox;
                    $setting->save();
                }
            }
        }

        return $this->response->redirect(getUrl('message/settings'));
    }

    public function getBlockedPlayers()
    {
        $this->view->bottom_info = self::getFlashData('message_block_info');
        $this->view->blocked_users = ORM::for_table('user_message_block')
            ->select('user.id')->select('user.name')
            ->left_outer_join('user', ['user.id', '=', 'user_message_block.blocked_id'])
            ->where('user_message_block.user_id', $this->user->id)
            ->find_many();
        $this->view->pick('message/blocked_players');
    }

    public function postBlockedPlayers()
    {
        $action = $this->request->get('action', Filter::FILTER_STRING, '');

        if($action == 'add') {
            $name = $this->request->get('name', Filter::FILTER_STRING, '');

            if(!empty($name)) {
                $blockUser = ORM::for_table('user')
                    ->select('id')
                    ->where('name', $name)
                    ->find_one();

                if($blockUser) {
                    $blockObj = ORM::for_table('user_message_block')->create();
                    $blockObj->user_id = $this->user->id;
                    $blockObj->blocked_id = $blockUser->id;
                    $blockObj->save();
                } else {
                    $this->setFlashData('message_block_info', 'This player doesn`t exist');
                }
            } else {
                $this->setFlashData('message_block_info', 'This player doesn`t exist');
            }
        } elseif($action == 'delete') {
            $user_id = $this->request->get('list', Filter::FILTER_INT, 0);
            ORM::raw_execute('DELETE FROM user_message_block WHERE blocked_id = ?', [$user_id]);
        }

        return $this->response->redirect(getUrl('message/block'));
    }

}