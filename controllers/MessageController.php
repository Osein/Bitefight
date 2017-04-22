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
        $this->view->pick('message/index');
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

    public function jsonWriteMessage()
    {
        $token = $this->request->get('__token');
        $tokenKey = $this->request->get('__tkey');
        $receiverName = $this->request->get('receivername', Filter::FILTER_STRING, '');
        $messageText = $this->request->get('message', Filter::FILTER_STRING, '');
        $subject = $this->request->get('subject', Filter::FILTER_STRING, '');
        $responseData = new \stdClass();
        $responseData->errorstatus = 0;
        $responseData->error = 'message sent';
        $response = new Response();

        if(!$this->security->checkToken($tokenKey, $token, false)) {
            $responseData->errorstatus = 1;
            $responseData->error = 'Token error';
        }

        if(!empty($receiverName)) {
            $receiver = ORM::for_table('user')->select('id')->where('name', $receiverName)->find_one();

            if(!$receiver) {
                $responseData->errorstatus = 1;
                $responseData->error = 'This player doesn`t exist';
            }
        } else {
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

        if(!$responseData->errorstatus) {
            $message = ORM::for_table('message')->create();
            $message->sender_id = $this->user->id;
            $message->receiver_id = $receiver->id;
            $message->type = MESSAGE_TYPE_USER_MESSAGE;
            $message->subject = $subject;
            $message->message = $messageText;
            $message->save();
        }

        $response->setJsonContent($responseData);
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
            ORM::raw_execute('DELETE FROM user_message_folder WHERE user_id = ? AND id = ?',
                [$this->user->id, $this->request->get('folderid')]);
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

        $userMessageFolders = array(-1, 0);
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
                    if($settingObj->setting == $settingType) {
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