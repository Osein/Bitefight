<?php
use Phalcon\Mvc\View;

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
     * Here to replace the ugly code
     * @return mixed
     */
    public function notFound() {
        return $this->dispatcher->forward(array(
            'controller' => 'error',
            'action'     => 'show404',
        ));
    }

    /**
     * Send an email with view file
     * @param $to
     * @param $subject
     * @param $view
     * @param array $data
     */
    public function sendEmail($to, $subject, $view, $data = array()) {
        $headers = "From: noreply@osein.net\r\n";
        $headers .= "Reply-To: noreply@osein.net\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message =  $this->view->getRender('emails', $view, $data, function ($view) {
            /** @var View $view */
            $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        });

        mail($to, $subject, $message, $headers);
    }

}