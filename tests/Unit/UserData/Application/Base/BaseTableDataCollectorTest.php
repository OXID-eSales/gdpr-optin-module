<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base;

class BaseTableDataCollectorTest extends BaseTestCase
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\AddressDataCollector';
    protected const TABLE_NAME = 'oxaddress';
    protected const COLUMN_NAME = 'OXUSERID';
    protected const COLUMN_VALUE = null;

    public function testCollectReturnsExpectedTableData(): void
    {
        $class = static::DATA_COLLECTOR_CLASS;
        $columnValue = $columnValue ?? uniqid();

        $expectedUserData = $this->expectedUserData();
        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: $expectedUserData,
            method: 'getDataFromTable',
            withParams: [static::TABLE_NAME, static::COLUMN_NAME, $columnValue]
        );

        $sut = new $class($userDataRepositoryMock);
        $actualResult = $sut->collect(recordId: $columnValue);

        $this->assertCount(2, $expectedUserData);
        $this->assertSame($expectedUserData, $actualResult);
    }
}
