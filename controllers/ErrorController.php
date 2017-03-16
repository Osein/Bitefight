<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 13/01/17
 * Time: 22:22
 */

namespace Bitefight\Controllers;

use Phalcon\Mvc\View;

class ErrorController extends BaseController
{
    public function show404()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('404');
    }
}
