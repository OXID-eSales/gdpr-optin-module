<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\UserData\Infrastructure;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\GdprOptinModule\UserData\Service\CollectionAggregationServiceInterface;

class SelectorTest extends IntegrationTestCase
{
    public function testConfiguredSelectors(): void
    {
        $aggregate = $this->get(CollectionAggregationServiceInterface::class);
        $aggregate->collectUserData(uniqid());

        $this->addToAssertionCount(1);
    }
}