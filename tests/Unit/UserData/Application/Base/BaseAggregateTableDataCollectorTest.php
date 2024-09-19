<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Application\Base;

use OxidEsales\GdprOptinModule\UserData\Application\RelatedTableDataCollectorInterface;

class BaseAggregateTableDataCollectorTest extends BaseTestCase
{
    protected const DATA_COLLECTOR_CLASS = 'OxidEsales\GdprOptinModule\UserData\Application\UserDataCollector';

    protected const FOREIGN_TABLE = 'foreignTable';
    protected const FOREIGN_COLUMN = 'someForeignId';
    protected const FOREIGN_COLUMN_VALUE = 'someForeignIdValue';

    protected const PRIMARY_TABLE = 'oxuser';
    protected const PRIMARY_KEY = 'OXUSERID';
    protected const PRIMARY_COLUMN_VALUE = 'somePrimaryKeyValue';


    public function testCollectReturnsPrimaryTableDataAndRelatedData(): void
    {
        $classToBeTested = static::DATA_COLLECTOR_CLASS;

        $expectedPrimaryTableData = $this->expectedUserData();
        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: $expectedPrimaryTableData,
            method: 'getDataFromTable',
            withParams: [static::PRIMARY_TABLE, static::PRIMARY_KEY, static::PRIMARY_COLUMN_VALUE],
        );

        $relatedTableCollectorMock = $this->createMock(RelatedTableDataCollectorInterface::class);
        $relatedTableCollectorMock->expects($this->once())
            ->method('getTableName')
            ->willReturn(static::FOREIGN_TABLE);

        $expectedRelatedData = [
            ['OXID' => uniqid(), 'OXOBJECTID' => uniqid(), 'OXGROUPSID' => uniqid()],
            ['OXID' => uniqid(), 'OXOBJECTID' => uniqid(), 'OXGROUPSID' => uniqid()]
        ];
        $relatedTableCollectorMock->expects($this->once())
            ->method('collectRelatedData')
            ->with(static::PRIMARY_TABLE, static::PRIMARY_KEY, static::PRIMARY_KEY, static::PRIMARY_COLUMN_VALUE)
            ->willReturn($expectedRelatedData);

        $sut = new $classToBeTested(
            repository: $userDataRepositoryMock,
            collectors: [$relatedTableCollectorMock]
        );

        $actualResult = $sut->collect(id: static::PRIMARY_COLUMN_VALUE);

        $expectedResult = [
            static::PRIMARY_TABLE => $expectedPrimaryTableData,
            'related_data' => [
                static::FOREIGN_TABLE => $expectedRelatedData
            ]
        ];

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testCollectReturnsEmptyRelatedDataIfNoPrimaryTableData(): void
    {
        $classToBeTested = static::DATA_COLLECTOR_CLASS;

        $userDataRepositoryMock = $this->createUserDataRepositoryMock(
            expectedUserData: [],
            method: 'getDataFromTable',
            withParams: [static::PRIMARY_TABLE, static::PRIMARY_KEY, static::PRIMARY_COLUMN_VALUE],
        );

        $userDataRepositoryMock->expects($this->once())
            ->method('getDataFromTable')
            ->with(static::PRIMARY_TABLE, static::PRIMARY_KEY, static::PRIMARY_COLUMN_VALUE)
            ->willReturn([]);

        $relatedTableCollectorMock = $this->createMock(RelatedTableDataCollectorInterface::class);
        $relatedTableCollectorMock->expects($this->never())
            ->method('collectRelatedData');

        $sut = new $classToBeTested(
            repository: $userDataRepositoryMock,
            collectors: [$relatedTableCollectorMock]
        );

        $actualResult = $sut->collect(id: static::PRIMARY_COLUMN_VALUE);

        $expectedResult = [
            static::PRIMARY_TABLE => [],
            'related_data' => []
        ];

        $this->assertSame($expectedResult, $actualResult);
    }
}
