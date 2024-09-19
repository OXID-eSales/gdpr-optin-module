<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\BaseTableDataCollectorTest;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\RecommListDataCollector
 */
final class RecommListDataCollectorTest extends BaseTableDataCollectorTest
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\RecommListDataCollector';
    protected const TABLE_NAME = 'oxrecommlists';
}
