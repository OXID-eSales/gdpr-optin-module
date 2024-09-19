<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class UserDataCollector extends AbstractAggregateTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxuser';
    }

    public function getColumnName(): string
    {
        return 'OXUSERID';
    }
}
