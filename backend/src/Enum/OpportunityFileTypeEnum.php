<?php

namespace App\Enum;

enum OpportunityFileTypeEnum: string
{
    case INVOICE = 'invoice';
    case ORDER = 'order';
    case OTHER = 'other';
}
