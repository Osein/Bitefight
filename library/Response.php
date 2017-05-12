<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 07/05/17
 * Time: 10:09
 */

namespace Bitefight\Library;

class Response
{

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var string[]
     */
    protected $successMessages = array();

    /**
     * @var string[]
     */
    protected $errorMessages = array();

    /**
     * @var string[]
     */
    protected $warningMessages = array();

    /**
     * @var string[]
     */
    protected $infoMessages = array();

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return \string[]
     */
    public function getSuccessMessages()
    {
        return $this->successMessages;
    }

    /**
     * @param \string[] $successMessages
     */
    public function setSuccessMessages($successMessages)
    {
        $this->successMessages = $successMessages;
    }

    /**
     * @return \string[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @param \string[] $errorMessages
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;
    }

    /**
     * @return \string[]
     */
    public function getWarningMessages()
    {
        return $this->warningMessages;
    }

    /**
     * @param \string[] $warningMessages
     */
    public function setWarningMessages($warningMessages)
    {
        $this->warningMessages = $warningMessages;
    }

    /**
     * @return \string[]
     */
    public function getInfoMessages()
    {
        return $this->infoMessages;
    }

    /**
     * @param \string[] $infoMessages
     */
    public function setInfoMessages($infoMessages)
    {
        $this->infoMessages = $infoMessages;
    }

    /**
     * @param string $message
     */
    public function addSuccessMessage($message)
    {
        $this->successMessages[] = $message;
    }

    /**
     * @param string $message
     */
    public function addErrorMessage($message)
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @param string $message
     */
    public function addWarningMessage($message)
    {
        $this->warningMessages[] = $message;
    }

    /**
     * @param string $message
     */
    public function addInfoMessage($message)
    {
        $this->infoMessages[] = $message;
    }

}