<?php

namespace App\Service\AuditTrail;

enum AuditTrailModifTypeEnum: string
{
    case INSERT = 'insert';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
