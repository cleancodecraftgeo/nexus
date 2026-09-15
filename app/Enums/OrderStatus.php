<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label():string
    {
        return match ($this)
        {
            self::Pending=>'Pending',
            self::Confirmed=>'Confirmed',
            self::Processing=>'Processing',
            self::Shipped=>'Shipped',
            self::Completed=>'Completed',
            self::Cancelled=>'Cancelled',

        };
    }
    public function color():string
    {
        return match ($this)
        {
            self::Pending=>'warning',
            self::Confirmed=>'info',
            self::Processing=>'primary',
            self::Shipped=>'gray',
            self::Completed=>'success',
            self::Cancelled=>'danger',
        };
    }
}
