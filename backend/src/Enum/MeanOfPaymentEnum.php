<?php

namespace App\Enum;

enum MeanOfPaymentEnum: string
{
    case CHECK = 'Chèque';
    case TRANSFER = 'Virement';
}
