<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;
use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Controller\ContactController;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;

/**
 * Class ContactControllerTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class ContactControllerTest extends IntegrationBaseTest
{
    use ServiceContainer;

    /**
     * Test checkbox validation.
     *
     * @dataProvider dataProviderOptInValidationRequired
     */
    public function testOptInValidationRequired($configValue, $expected)
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
     * @return array
     */
    public function dataProviderOptInValidationRequired()
    {
        return [
            'formMethod-deletion' => ['deletion', false],
            'formMethod-statistical' => ['statistical', true],
        ];
    }

    /**
     * Test validation error appears if needed
     */
    public function testSendError()
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveString(
            ModuleSettings::CONTACT_CHOICE,
            'statistical',
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ContactController::class);

        $this->assertFalse($controller->isOptInError());
        $this->assertFalse($controller->send());
        $this->assertTrue($controller->isOptInError());
    }
}
