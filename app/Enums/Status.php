<?php

namespace App\Enums;

enum Status: string
{
    case Booked = 'booked';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
