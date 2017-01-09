<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

class HomeController extends BaseController
{

    public function getIndex() {

        $this->view->pick('home/index');
    }

}