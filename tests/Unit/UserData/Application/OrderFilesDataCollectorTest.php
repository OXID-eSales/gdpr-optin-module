<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\RelatedTableDataCollectorTest;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\OrderFilesDataCollector
 */
final class OrderFilesDataCollectorTest extends RelatedTableDataCollectorTest
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\OrderFilesDataCollector';
    protected const FOREIGN_TABLE = 'oxorderfiles';
    protected const FOREIGN_COLUMN = 'OXORDERID';
}
