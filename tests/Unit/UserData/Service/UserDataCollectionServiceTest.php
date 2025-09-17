<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;
use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollectionInterface;
use OxidEsales\GdprOptinModule\UserData\Service\CollectionAggregationServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\CollectionSerializerServiceInterface;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataCollectionService;
use OxidEsales\GdprOptinModule\UserData\Service\UserDataCollectionServiceInterface;
use PHPUnit\Framework\TestCase;

class UserDataCollectionServiceTest extends TestCase
{
    public function testGetUserDataAsFilesList(): void
    {
        $userId = uniqid();

        $collectionAggregationServiceMock = $this->createMock(CollectionAggregationServiceInterface::class);
        $collectionAggregationServiceMock->method('collectUserData')
            ->with($userId)->willReturn([
                $tableCollection1Stub = $this->createStub(TableDataCollectionInterface::class),
                $tableCollection2Stub = $this->createStub(TableDataCollectionInterface::class),
            ]);

        $collectionSerializerServiceMock = $this->createMock(CollectionSerializerServiceInterface::class);
        $resultFile1 = $this->createStub(ResultFileInterface::class);
        $resultFile2 = $this->createStub(ResultFileInterface::class);
        $collectionSerializerServiceMock->expects($matcher = $this->exactly(2))
            ->method('serializeCollection')
            ->willReturnCallback(function (TableDataCollectionInterface $tableCollection)
 use ($matcher, $tableCollection1Stub, $tableCollection2Stub, $resultFile1, $resultFile2) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($tableCollection1Stub, $tableCollection),
                    2 => $this->assertEquals($tableCollection2Stub, $tableCollection),
                };

                return match ($matcher->numberOfInvocations()) {
                    1 => $resultFile1,
                    2 => $resultFile2,
                    default => throw new \Exception('Unexpected match value'),
                };
            });

        $sut = $this->getSut(
            collectionAggregationService: $collectionAggregationServiceMock,
            collectionSerializerService: $collectionSerializerServiceMock,
        );

        $result = $sut->getUserDataAsFilesList($userId);
        $this->assertEquals([$resultFile1, $resultFile2], $result);
    }

    protected function getSut(
        ?CollectionAggregationServiceInterface $collectionAggregationService,
        ?CollectionSerializerServiceInterface $collectionSerializerService,
    ): UserDataCollectionServiceInterface {
        $collectionAggregationService ??= $this->createStub(CollectionAggregationServiceInterface::class);
        $collectionSerializerService ??= $this->createStub(CollectionSerializerServiceInterface::class);

        return new UserDataCollectionService(
            collectionAggregationService: $collectionAggregationService,
            collectionSerializerService: $collectionSerializerService,
        );
    }
}
