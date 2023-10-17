<?php

namespace App\Enum;

enum OpportunityStatusEnum: string
{
    // case DRAFT = 'draft';
    case TRACKED = 'tracked';
    case TENDER_ONGOING = 'tender_ongoing';
    case DEVELOP_ONGOING = 'develop_ongoing';
    case DELIVERED = 'delivered'; // recette
    case BILLED = 'billed';
    case PAYED = 'payed';
    case CANCELED = 'canceled';
    case TENDER_SENT = 'tender_sent';
    case NEED_ONGOING = 'need_ongoing';
    case NEED_SENT = 'need_sent';
}
