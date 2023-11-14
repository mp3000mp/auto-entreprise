<?php

namespace App\Enum;

enum MeanOfPaymentEnum: string
{
    case CHECK = 'check';
    case TRANSFER = 'transfer';
}
