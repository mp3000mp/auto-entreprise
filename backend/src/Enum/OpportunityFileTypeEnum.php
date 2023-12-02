<?php

namespace App\Enum;

enum OpportunityFileTypeEnum: string
{
    case INVOICE = 'Facture';
    case ORDER = 'Commande';
    case OTHER = 'Autre';
}
