<?php

namespace App\Exceptions;

use Exception;

class ConfigurationServiceException extends Exception
{
    public const CUSTOMER_NOT_FOUND = 'This customer does not exist';
}
