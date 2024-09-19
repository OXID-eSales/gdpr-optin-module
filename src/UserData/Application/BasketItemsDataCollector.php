<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class BasketItemsDataCollector extends AbstractRelatedTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxuserbasketitems';
    }

    protected function getColumnName(): string
    {
        return 'OXBASKETID';
    }
}
