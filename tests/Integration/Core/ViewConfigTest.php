<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration\Core;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Tests\Integration\BaseTestCase;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

final class ViewConfigTest extends BaseTestCase
{
    use ServiceContainer;

    public function testGetGdprContactOptIn(): void
    {
        $value = 'the_value';

        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            $value,
            GdprOptinModule::MODULE_ID
        );

        $viewConfig = oxNew(ViewConfig::class);
        $this->assertSame($value, $viewConfig->getGdprContactOptIn());
    }

    public function dataProviderBoolean(): array
    {
        return [
            'on' => [true],
            'off' => [false],
        ];
    }

    /**
     * @dataProvider dataProviderBoolean
     */
    public function testShowGdprInvoiceOptIn(bool $value): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            $value,
            GdprOptinModule::MODULE_ID
        );

        $viewConfig = oxNew(ViewConfig::class);
        $this->assertSame($value, $viewConfig->showGdprInvoiceOptIn());
    }

    /**
     * @dataProvider dataProviderBoolean
     */
    public function testShowGdprDeliveryOptIn(bool $value): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::DELIVERY_OPT_IN,
            $value,
            GdprOptinModule::MODULE_ID
        );

        $viewConfig = oxNew(ViewConfig::class);
        $this->assertSame($value, $viewConfig->showGdprDeliveryOptIn());
    }

    /**
     * @dataProvider dataProviderBoolean
     */
    public function testShowGdprRegistrationOptIn(bool $value): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REGISTRATION_OPT_IN,
            $value,
            GdprOptinModule::MODULE_ID
        );

        $viewConfig = oxNew(ViewConfig::class);
        $this->assertSame($value, $viewConfig->showGdprRegistrationOptIn());
    }

    /**
     * @dataProvider dataProviderBoolean
     */
    public function testShowGdprReviewOptIn(bool $value): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $value,
            GdprOptinModule::MODULE_ID
        );

        $viewConfig = oxNew(ViewConfig::class);
        $this->assertSame($value, $viewConfig->showGdprReviewOptIn());
    }
}
