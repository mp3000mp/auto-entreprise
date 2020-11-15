<?php

namespace App\Service\AuditTrail;

interface AuditrailableInterface
{
    public function getAuditTrailString(): string;

    public function getFieldsToBeIgnored(): array;
}
