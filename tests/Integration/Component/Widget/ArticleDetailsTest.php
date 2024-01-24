<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\Component\Widget;

use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\GdprOptinModule\Service\ReviewOptInInterface;

/** @covers \OxidEsales\GdprOptinModule\Component\Widget\ArticleDetails */
class ArticleDetailsTest extends IntegrationTestCase
{
    public function testInit(): void
    {
        $sut = oxNew(ArticleDetails::class);
        $sut->init();

        $viewData = $sut->getViewData();
        $this->assertInstanceOf(ReviewOptInInterface::class, $viewData['reviewOptInService']);
    }
}
