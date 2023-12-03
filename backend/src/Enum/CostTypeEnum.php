<?php

namespace App\Enum;

enum CostTypeEnum: string
{
    case BANK = 'bank';
    case TAX = 'tax';
    case SUPPLIES = 'supplies';
}
