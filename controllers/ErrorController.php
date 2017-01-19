<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 13/01/17
 * Time: 22:22
 */

class ErrorController extends BaseController
{

    public function show404() {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404');
    }

}