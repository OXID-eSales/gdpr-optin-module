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

class GeneralTableDataSelectorTest extends IntegrationTestCase
{
    protected const USER_ID = 'example_user_id';

    public function setUp(): void
    {
        parent::setUp();

        $qb = $this->get(QueryBuilderFactoryInterface::class)->create();
        $qb->insert('oxuser')
            ->values([
                'OXID' => $qb->createNamedParameter(self::USER_ID),
                'OXUSERNAME' => $qb->createNamedParameter('example_user'),
            ])
            ->execute();
    }

    public function testSelectorSelectsDataFromTableFilteredByColumnValue(): void
    {
        $sut = new GeneralTableDataSelector(
            collection: $collectionName = uniqid(),
            selectionTable: 'oxuser',
            filterColumn: 'oxuser.OXID',
            queryBuilderFactory: $this->get(QueryBuilderFactoryInterface::class)
        );

        $result = $sut->getDataForColumnValue(self::USER_ID);

        $user = $result[0];
        $this->assertSame(self::USER_ID, $user['OXID']);
        $this->assertSame('example_user', $user['OXUSERNAME']);

        $this->assertSame($collectionName, $sut->getCollection());
        $this->assertSame('oxuser', $sut->getSelectionTable());
    }
}
