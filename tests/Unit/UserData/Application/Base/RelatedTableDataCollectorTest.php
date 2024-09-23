<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base;

class RelatedTableDataCollectorTest extends BaseTestCase
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\BasketItemsDataCollector';

    protected const PRIMARY_TABLE = 'somePrimaryTable';
    protected const PRIMARY_KEY = 'somePrimaryKey';
    protected const PRIMARY_CONDITION_COLUMN = 'somePrimaryKey';
    protected const PRIMARY_CONDITION_VALUE = 'somePrimaryKeyValue';

    protected const FOREIGN_TABLE = 'oxuserbasketitems';
    protected const FOREIGN_COLUMN = 'OXBASKETID';
    protected const FOREIGN_COLUMN_VALUE = 'someForeignKeyValue';

    public function testCollectReturnsExpectedUserData(): void
    {
        $expectedUserData = $this->expectedUserData();
        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: $expectedUserData,
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

        $class = static::DATA_COLLECTOR_CLASS;
        $sut = new $class($userDataRepositoryMock);

        $actualResult = $sut->collectRelatedData(
            primaryTable: static::PRIMARY_TABLE,
            primaryKey: static::PRIMARY_KEY,
            primaryColumn: static::PRIMARY_CONDITION_COLUMN,
            primaryValue: static::PRIMARY_CONDITION_VALUE,
        );

        $this->assertCount(2, $expectedUserData);
        $this->assertSame($expectedUserData, $actualResult);
    }
}
