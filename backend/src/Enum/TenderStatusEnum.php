<?php

namespace App\Enum;

enum TenderStatusEnum: string
{
    // case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REFUSED = 'refused';
    case CANCELED = 'canceled';
}
