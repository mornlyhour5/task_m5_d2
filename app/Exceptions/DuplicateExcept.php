<?php

namespace App\Exceptions;

use Exception;

class DuplicateExcept extends Exception
{
    public function __construct($message = "Duplicated")
    {
        return parent::__construct($message, 409);
    }
}
