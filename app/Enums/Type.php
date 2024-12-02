<?php

namespace App\Enums;

use Essa\APIToolKit\Enum\Enum;

enum Type :string
{
    case Lunch = 'lunch';
    case Breakfast_and_lunch = 'Breakfast and lunch';
}
