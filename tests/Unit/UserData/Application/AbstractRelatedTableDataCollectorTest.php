<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base\BaseTestCase;
use OxidEsales\GdprOptinModule\UserData\Application\AbstractRelatedTableDataCollector;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Application\AbstractRelatedTableDataCollector
 */
final class AbstractRelatedTableDataCollectorTest extends BaseTestCase
{
    protected const PRIMARY_TABLE = 'somePrimaryTable';
    protected const PRIMARY_KEY = 'somePrimaryKey';
    protected const PRIMARY_CONDITION_COLUMN = 'somePrimaryKey';
    protected const PRIMARY_CONDITION_VALUE = 'somePrimaryKeyValue';

    protected const FOREIGN_TABLE = 'someForeignTable';
    protected const FOREIGN_COLUMN = 'someForeignKey';
    protected const FOREIGN_COLUMN_VALUE = 'someForeignKeyValue';

    public function testCollectRelatedData(): void
    {
        $expectedData = $this->expectedUserData();
        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: $expectedData,
            method: 'getJoinedData',
            withParams: [
                static::PRIMARY_TABLE,
                static::PRIMARY_KEY,
                static::FOREIGN_TABLE,
                static::FOREIGN_COLUMN,
                static::PRIMARY_CONDITION_COLUMN,
                static::PRIMARY_CONDITION_VALUE
            ]
        );

        $sut = new class ($userDataRepositoryMock) extends AbstractRelatedTableDataCollector {
            public function getTableName(): string
            {
                return 'someForeignTable';
            }

            protected function getColumnName(): string
            {
                return 'someForeignKey';
            }
        };

        $result = $sut->collectRelatedData(
            primaryTable: self::PRIMARY_TABLE,
            primaryKey: self::PRIMARY_KEY,
            primaryConditionColumn: self::PRIMARY_CONDITION_COLUMN,
            primaryConditionValue: self::PRIMARY_CONDITION_VALUE,
        );
        $this->assertSame($expectedData, $result);
    }
}
