<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\Component\Widget;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\GdprOptinModule\Service\ReviewOptInInterface;

/** @covers \OxidEsales\GdprOptinModule\Component\Widget\Review */
class ReviewTest extends IntegrationTestCase
{
    public function testInit(): void
    {
        $sut = oxNew(\OxidEsales\Eshop\Application\Component\Widget\Review::class);
        $sut->init();

        $viewData = $sut->getViewData();
        $this->assertInstanceOf(ReviewOptInInterface::class, $viewData['reviewOptInService']);
    }
}
