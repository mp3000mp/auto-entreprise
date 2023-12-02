<?php

namespace App\Enum;

enum TenderStatusEnum: string
{
    // case DRAFT = 'draft';
    case ONGOING = 'En cours';
    case SENT = 'Envoyé';
    case ACCEPTED = 'Accepté';
    case REFUSED = 'Refusé';
    case CANCELED = 'Annulé';
}
