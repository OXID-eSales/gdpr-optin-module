<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ContactController;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Tests\Traits\ServiceContainer;

final class ContactControllerTest extends BaseTestCase
{
    use ServiceContainer;

    public static function dataProviderOptInValidationRequired(): array
    {
        return [
            'formMethod-deletion' => ['deletion', false],
            'formMethod-statistical' => ['statistical', true],
        ];
    }

    /**
     * @dataProvider dataProviderOptInValidationRequired
     */
    public function testOptInValidationRequired(string $configValue, bool $expected): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ContactController::class);
        $this->assertSame($expected, $controller->isOptInValidationRequired());
    }

    /**
     * @dataProvider dataProviderOptInValidationRequired
     */
    public function testErrorOnSend(string $configValue, bool $expected): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ContactController::class);

        $this->assertFalse($controller->isOptInError());
        $this->assertFalse($controller->send());
        $this->assertSame($expected, $controller->isOptInError());
    }
}
