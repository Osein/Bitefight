<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

class BaseController extends \Phalcon\Mvc\Controller
{

    /**
     * Called before the execution of handler
     *
     * @return bool
     */
    public function beforeExecuteRoute()
    {
        if ($this->request->isPost() && !$this->security->checkToken()) {
            $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));

            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function notFound() {
        return $this->dispatcher->forward(array(
            'controller' => 'error',
            'action'     => 'show404',
        ));
    }

}