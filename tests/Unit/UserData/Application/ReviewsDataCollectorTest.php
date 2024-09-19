<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\BaseTableDataCollectorTest;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\ReviewsDataCollector
 */
final class ReviewsDataCollectorTest extends BaseTableDataCollectorTest
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\ReviewsDataCollector';
    protected const TABLE_NAME = 'oxreviews';
}
