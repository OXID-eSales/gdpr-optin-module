<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\UserData\Infrastructure;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\GdprOptinModule\UserData\Infrastructure\GeneralTableDataSelector;
use OxidEsales\GdprOptinModule\UserData\Infrastructure\RelatedTableDataSelector;

class RelatedTableDataSelectorTest extends IntegrationTestCase
{
    protected const USER_ID = 'example_user_id';

    public function setUp(): void
    {
        parent::setUp();

        $orderId = uniqid();
        $qbOrder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $qbOrder->insert('oxorder')
            ->values([
                'OXID' => $qbOrder->createNamedParameter($orderId),
                'OXUSERID' => $qbOrder->createNamedParameter(self::USER_ID),
                'OXTOTALORDERSUM' => $qbOrder->createNamedParameter(12345),
            ])
            ->execute();

        $qbOrderFiles = $this->get(QueryBuilderFactoryInterface::class)->create();
        $qbOrderFiles->insert('oxorderfiles')
            ->values([
                'OXID' => $qbOrderFiles->createNamedParameter(uniqid()),
                'OXORDERID' => $qbOrderFiles->createNamedParameter($orderId),
                'OXFILENAME' => $qbOrderFiles->createNamedParameter('example_filename.txt'),
            ])
            ->execute();
    }

    public function testSelectorSelectsDataFromTableFilteredByColumnValue(): void
    {
        $sut = new RelatedTableDataSelector(
            primaryTable: 'oxorder',
            selectionTable: 'oxorderfiles',
            relationCondition: 'oxorderfiles.OXORDERID = oxorder.OXID',
            filterColumn: 'oxorder.OXUSERID',
            queryBuilderFactory: $this->get(QueryBuilderFactoryInterface::class)
        );

        $result = $sut->getDataForColumnValue(self::USER_ID);

        $orderFile = $result[0];
        $this->assertSame('example_filename.txt', $orderFile['OXFILENAME']);
    }
}