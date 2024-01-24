<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\Controller;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\GdprOptinModule\Service\ReviewOptInInterface;

/** @covers \OxidEsales\GdprOptinModule\Controller\ReviewController */
class ReviewControllerTest extends IntegrationTestCase
{
    public function testInit(): void
    {
        $sut = oxNew(ReviewController::class);
        $sut->init();

        $viewData = $sut->getViewData();
        $this->assertInstanceOf(ReviewOptInInterface::class, $viewData['reviewOptInService']);
    }
}
