<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Infrastructure;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepository
 */
final class UserDataRepositoryTest extends TestCase
{
    public function testGetDataFromTable(): void
    {
        $expectedData = [['OXID' => uniqid(), 'OXUSERID' => uniqid(), 'OXBILLLNAME' => uniqid()]];
        $tableName = uniqid();
        $columnName = uniqid();
        $columnValue = uniqid();

        $queryBuilderFactoryMock = $this->getQueryBuilderWithFetchResult($expectedData);

        $sut = $this->getSut(queryBuilderFactory: $queryBuilderFactoryMock);
        $actualResult = $sut->getDataFromTable($tableName, $columnName, $columnValue);

        $this->assertSame($expectedData, $actualResult);
    }

    public function testGetJoinedData(): void
    {
        $expectedData = [['OXID' => uniqid(), 'OXUSERID' => uniqid(), 'OXAMOUNT' => 120.454, 'OXTITLE' => uniqid()]];
        $queryBuilderFactoryMock = $this->getQueryBuilderWithFetchResult($expectedData);

        $sut = $this->getSut(queryBuilderFactory: $queryBuilderFactoryMock);
        $actualResult = $sut->getJoinedData(
            primaryTable: 'primaryTable',
            primaryKey: 'primaryKey',
            foreignTable: 'foreignTable',
            foreignKey: 'foreignKey',
            primaryConditionColumn: 'primaryKey',
            primaryConditionValue: uniqid()
        );

        $this->assertSame($expectedData, $actualResult);
    }

    private function getQueryBuilderWithFetchResult(
        array $fetchResult,
        string $fetchMethod = 'fetchAllAssociative',
    ): QueryBuilderFactoryInterface {
        $queryBuilderFactoryStub = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactoryStub->expects($this->once())
            ->method('create')
            ->willReturn(
                $queryBuilderStub = $this->createPartialMock(QueryBuilder::class, ['execute'])
            );

        $queryBuilderStub->expects($this->once())
            ->method('execute')
            ->willReturn($resultStub = $this->createMock(Result::class));

        $resultStub->expects($this->once())
            ->method($fetchMethod)
            ->willReturn($fetchResult);

        return $queryBuilderFactoryStub;
    }

    private function getSut(
        QueryBuilderFactoryInterface $queryBuilderFactory,
    ): UserDataRepository {
        return new UserDataRepository(
            queryBuilderFactory: $queryBuilderFactory,
        );
    }
}
