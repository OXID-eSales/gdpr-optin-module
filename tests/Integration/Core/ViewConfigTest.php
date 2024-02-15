<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\Core;

use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Service\ModuleSettingsInterface;
use OxidEsales\GdprOptinModule\Tests\Integration\BaseTestCase;
use OxidEsales\GdprOptinModule\Tests\Traits\ServiceContainer;
use OxidEsales\GdprOptinModule\Transput\OptInRequestInterface;

final class ViewConfigTest extends BaseTestCase
{
    public function testGetGdprModuleSettings(): void
    {
        $moduleSettingsStub = $this->createStub(ModuleSettingsInterface::class);

        $sut = $this->createPartialMock(ViewConfig::class, ['getService']);
        $sut->method('getService')->willReturnMap([
            [ModuleSettingsInterface::class, $moduleSettingsStub]
        ]);

        $this->assertSame($moduleSettingsStub, $sut->getGdprModuleSettings());
    }

    public function testGetInvoiceOptIn(): void
    {
        $expectedValue = (bool)rand(0, 1);
        $optInRequestSpy = $this->createMock(OptInRequestInterface::class);
        $optInRequestSpy->expects($this->once())->method('getInvoiceAddressOptIn')->willReturn($expectedValue);

        $sut = $this->createPartialMock(\OxidEsales\GdprOptinModule\Core\ViewConfig::class, ['getService']);
        $sut->method('getService')->willReturnMap([
            [OptInRequestInterface::class, $optInRequestSpy]
        ]);

        $this->assertSame($expectedValue, $sut->getInvoiceOptIn());
    }

    public function testGetDeliveryOptIn(): void
    {
        $expectedValue = (bool)rand(0, 1);
        $optInRequestSpy = $this->createMock(OptInRequestInterface::class);
        $optInRequestSpy->expects($this->once())->method('getDeliveryAddressOptIn')->willReturn($expectedValue);

        $sut = $this->createPartialMock(\OxidEsales\GdprOptinModule\Core\ViewConfig::class, ['getService']);
        $sut->method('getService')->willReturnMap([
            [OptInRequestInterface::class, $optInRequestSpy]
        ]);

        $this->assertSame($expectedValue, $sut->getDeliveryOptIn());
    }
}
