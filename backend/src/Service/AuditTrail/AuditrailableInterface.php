<?php

namespace App\Service\AuditTrail;

interface AuditrailableInterface
{
    public function getAuditTrailString(): string;

    /**
     * @return string[]
     */
    public function getFieldsToBeIgnored(): array;
}
