<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class UserObject2PaymentDataCollector extends AbstractRelatedTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxobject2payment';
    }

    protected function getColumnName(): string
    {
        return 'OXOBJECTID';
    }
}
