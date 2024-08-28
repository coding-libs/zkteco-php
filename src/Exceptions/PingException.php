<?php

namespace CodingLibs\ZktecoPhp\Exceptions;

use Exception;

class PingException extends Exception
{
    // Add a custom property
    private $errorCode;

    // Constructor with custom error code
    public function __construct($message, $errorCode = 0, Exception $previous = null)
    {
        // Call parent constructor
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
    }

    // Custom method to get the error code
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
