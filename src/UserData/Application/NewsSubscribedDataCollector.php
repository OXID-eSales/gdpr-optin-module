<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class NewsSubscribedDataCollector extends AbstractTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxnewssubscribed';
    }

    protected function getColumnName(): string
    {
        return 'OXUSERID';
    }
}
