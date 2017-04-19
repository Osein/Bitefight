<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 18/04/17
 * Time: 22:20
 */

namespace Bitefight\Controllers;

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

}