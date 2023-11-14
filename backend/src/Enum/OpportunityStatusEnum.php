<?php

namespace App\Enum;

enum OpportunityStatusEnum: string
{
    // case DRAFT = 'draft';
    case TRACKED = 'tracked';
    case NEED_ONGOING = 'need_ongoing';
    case NEED_SENT = 'need_sent';
    case TENDER_ONGOING = 'tender_ongoing';
    case TENDER_SENT = 'tender_sent';
    case DEVELOP_ONGOING = 'dev_ongoing';
    case DELIVERED = 'delivered'; // recette
    case BILLED = 'billed';
    case PAYED = 'payed';
    case CANCELED = 'canceled';
}
