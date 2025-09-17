<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollection;
use OxidEsales\GdprOptinModule\UserData\Exception\AggregationTypeException;
use OxidEsales\GdprOptinModule\UserData\Infrastructure\DataSelectorInterface;
use OxidEsales\GdprOptinModule\UserData\Service\CollectionAggregationService;
use PHPUnit\Framework\TestCase;

final class CollectionAggregationServiceTest extends TestCase
{
    public function testAggregatorExplodesOnWrongType(): void
    {
        $this->expectException(AggregationTypeException::class);

        $randomObject = new \stdClass();

        $sut = new CollectionAggregationService(
            collectors: [
                $randomObject
            ]
        );
    }

    public function testCollectUserDataReturnsCorrectlyGroupedData(): void
    {
        $userId = uniqid();

        $collection1 = uniqid();
        $collection2 = uniqid();

        $collector1 = $this->createMock(DataSelectorInterface::class);
        $collector1->method('getCollection')->willReturn($collection2);
        $collector1->method('getSelectionTable')->willReturn($collector1Table = uniqid());
        $collector1->method('getDataForColumnValue')->with($userId)
            ->willReturn($collection1Data = $this->getRandomDataArray());

        $collector2 = $this->createMock(DataSelectorInterface::class);
        $collector2->method('getCollection')->willReturn($collection1);
        $collector2->method('getSelectionTable')->willReturn($collector2Table = uniqid());
        $collector2->method('getDataForColumnValue')->with($userId)
            ->willReturn($collection2Data = $this->getRandomDataArray());

        $collector3 = $this->createMock(DataSelectorInterface::class);
        $collector3->method('getCollection')->willReturn($collection1);
        $collector3->method('getSelectionTable')->willReturn($collector3Table = uniqid());
        $collector3->method('getDataForColumnValue')->with($userId)
            ->willReturn($collection3Data = $this->getRandomDataArray());

        $collectors = [
            $collector1,
            $collector2,
            $collector3
        ];

        $sut = new CollectionAggregationService(
            collectors: $collectors
        );

        $collections = $sut->collectUserData($userId);

        $this->assertEquals(
            [
                new TableDataCollection($collection2, [
                    $collector1Table => $collection1Data
                ]),
                new TableDataCollection($collection1, [
                    $collector2Table => $collection2Data,
                    $collector3Table => $collection3Data
                ]),
            ],
            $collections
        );
    }

    private function getRandomDataArray(): array
    {
        return [
            uniqid() => uniqid(),
            uniqid() => uniqid(),
        ];
    }
}
