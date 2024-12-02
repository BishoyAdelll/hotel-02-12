<?php


namespace App\Enums;


enum  Payment : string
{
        case visa = 'Visa';
        case cash = 'Cash';
        case instapay = 'InstaPay';
}
