<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\BaseTestCase;
use OxidEsales\GdprOptinModule\UserData\Application\AbstractTableDataCollector;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\AbstractTableDataCollector
 */
final class AbstractTableDataCollectorTest extends BaseTestCase
{
    protected const TABLE_NAME = 'test_table';
    protected const COLUMN_NAME = 'test_column';

    public function testCollectReturnsExpectedTableData(): void
    {
        $userId = uniqid();
        $expectedData = $this->expectedUserData();
        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: $expectedData,
            method: 'getDataFromTable',
            withParams: [static::TABLE_NAME, static::COLUMN_NAME, $userId],
        );

        $sut = new class ($userDataRepositoryMock) extends AbstractTableDataCollector {
            public function getTableName(): string
            {
                return 'test_table';
            }

            protected function getColumnName(): string
            {
                return 'test_column';
            }
        };

        $result = $sut->collect($userId);
        $this->assertSame($expectedData, $result);
    }
}
