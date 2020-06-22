<?php

namespace Featherbits\ServiceContainer\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $type)
    {
    }
}
