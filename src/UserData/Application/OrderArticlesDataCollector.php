<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class OrderArticlesDataCollector extends AbstractRelatedTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxorderarticles';
    }

    protected function getColumnName(): string
    {
        return 'OXORDERID';
    }
}
