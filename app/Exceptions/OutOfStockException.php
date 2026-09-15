<?php

namespace App\Exceptions;

use Exception;

class OutOfStockException extends Exception
{
    public function __construct()
    {
        parent::__construct(
        "This product is out of stock."
    );
    }
}
