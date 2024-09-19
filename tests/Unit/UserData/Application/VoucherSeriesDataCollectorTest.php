<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\RelatedTableDataCollectorTest;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\VoucherSeriesDataCollector
 */
final class VoucherSeriesDataCollectorTest extends RelatedTableDataCollectorTest
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\VoucherSeriesDataCollector';
    protected const FOREIGN_TABLE = 'oxvoucherseries';
    protected const FOREIGN_COLUMN = 'OXSERIENR';
}
