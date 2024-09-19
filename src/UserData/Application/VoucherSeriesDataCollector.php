<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

class VoucherSeriesDataCollector extends AbstractRelatedTableDataCollector
{
    public function getTableName(): string
    {
        return 'oxvoucherseries';
    }

    protected function getColumnName(): string
    {
        return 'OXSERIENR';
    }
}
