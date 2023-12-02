<?php

namespace App\Enum;

enum OpportunityStatusEnum: string
{
    // case DRAFT = 'draft';
    case TRACKED = 'Piste';
    case NEED_ONGOING = 'Expr. besoin en cours';
    case NEED_SENT = 'Expr. besoin envoyé';
    case TENDER_ONGOING = 'Devis en cours';
    case TENDER_SENT = 'Devis envoyé';
    case DEVELOP_ONGOING = 'Dev en cours';
    case DELIVERED = 'Recette';
    case BILLED = 'Facture envoyée';
    case PAYED = 'Payé';
    case CANCELED = 'Annulé';
}
