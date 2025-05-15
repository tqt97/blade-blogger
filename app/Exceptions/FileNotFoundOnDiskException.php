<?php

namespace App\Exceptions;

use Exception;

class FileNotFoundOnDiskException extends Exception
{
    public function __construct(string $path, string $disk)
    {
        parent::__construct("File [{$path}] does not exist on disk [{$disk}].");
    }
}
