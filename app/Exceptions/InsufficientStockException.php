<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(
        public int $requested,
        public int $available
    ){
        parent::__construct(
            "Insufficient stock. Requested: {$requested}, Available: {$available}"
        );
    }
}

