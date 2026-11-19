<?php

namespace App\Exceptions;

use Exception;

class OutOfStockException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            __('exception.out_of_stock')
        );
    }
}
