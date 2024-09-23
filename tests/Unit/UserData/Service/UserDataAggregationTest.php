<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service\Base\BaseTestCase;
use OxidEsales\GdprOptinModule\UserData\Application\TableDataCollectorInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataAggregationService;

final class UserDataAggregationTest extends BaseTestCase
{
    public function testCollectUserDataReturnsUserData(): void
    {
        $userId = uniqid();

        $tableName1 = uniqid();
        $expectedCollectorData1 = $this->expectedUserData();
        $collectorMock1 = $this->createCollectorMock(
            tableName: $tableName1,
            userId: $userId,
            expectedData:  $expectedCollectorData1
        );

        $tableName2 = uniqid();
        $expectedCollectorData2 = $this->expectedUserData();
        $collectorMock2 = $this->createCollectorMock(
            tableName: $tableName2,
            userId: $userId,
            expectedData: $expectedCollectorData2
        );

        $sut = new UserDataAggregationService(collectors: [$collectorMock1, $collectorMock2]);
        $actualResult = $sut->collectUserData(userId: $userId);

        $expectedUserResult = [
            $tableName1 => $expectedCollectorData1,
            $tableName2 => $expectedCollectorData2
        ];

        $this->assertSame($expectedUserResult, $actualResult);
    }

    public function testCollectUserDataWithEmptyData(): void
    {
        $userId = uniqid();
        $tableName = uniqid();

        $collectorMock = $this->createCollectorMock(
            tableName: $tableName,
            userId: $userId,
            expectedData:  []
        );

        $sut = new UserDataAggregationService([$collectorMock]);
        $actualResult = $sut->collectUserData($userId);

        $expectedResult = [
            $tableName => []
        ];

        $this->assertSame($expectedResult, $actualResult);
    }

    private function createCollectorMock($tableName, $userId, $expectedData): TableDataCollectorInterface
    {
        $tableDataCollectorMock = $this->createMock(TableDataCollectorInterface::class);
        $tableDataCollectorMock
            ->expects($this->once())
            ->method('collect')
            ->with($userId)
            ->willReturn($expectedData);

        $tableDataCollectorMock
            ->expects($this->once())
            ->method('getTableName')
            ->willReturn($tableName);

        return $tableDataCollectorMock;
    }
}
