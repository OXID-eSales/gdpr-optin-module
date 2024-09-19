<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class ReviewsDataCollector extends AbstractTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxreviews';
    }

    protected function getColumnName(): string
    {
        return 'OXUSERID';
    }
}
