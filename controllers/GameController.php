<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:23
 */

class GameController extends BaseController
{

    protected $user;

    public function initialize()
    {
        if(!$this->session->get('user_id')) {
            return $this->response->redirect(getUrl(''));
        }

        $this->user = ORM::for_table('user')->find_one($this->session->get('user_id'));
        $this->view->user = $this->user;
    }

    public function afterExecuteRoute()
    {
        // Idiorm will look for dirty fields.
        // If there is none, it will not send query.
        // So I think this is a good practice for me.
        $this->user->save();
    }

}