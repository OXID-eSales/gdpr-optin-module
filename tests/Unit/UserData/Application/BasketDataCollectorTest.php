<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\BaseAggregateTableDataCollectorTest;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\BasketDataCollector
 */
final class BasketDataCollectorTest extends BaseAggregateTableDataCollectorTest
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\BasketDataCollector';
    protected const PRIMARY_TABLE = 'oxuserbaskets';
}
