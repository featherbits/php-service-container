<?php

namespace Featherbits\ServiceContainer\Exceptions;

use Exception;
use Featherbits\ServiceContainer\Contract\ContainerException as ContainerExceptionContract;

class ContainerException extends Exception implements ContainerExceptionContract
{
}
