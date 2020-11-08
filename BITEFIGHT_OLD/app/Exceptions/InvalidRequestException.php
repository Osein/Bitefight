<?php

namespace App\Exceptions;

use Exception;

class InvalidRequestException extends Exception
{
    protected $message = 'Invalid request or parameters';

    protected $code = 40001;
}
