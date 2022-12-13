<?php

/**
 * This file is part of OXID eSales GDPR opt-in module.
 *
 * OXID eSales GDPR opt-in module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales GDPR opt-in module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales GDPR opt-in module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2018
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
