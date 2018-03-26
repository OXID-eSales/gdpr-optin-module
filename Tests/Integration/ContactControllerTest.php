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

/**
 * Class ContactControllerTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class ContactControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test checkbox validation.
     *
     * @dataProvider dataProviderOptInValidationRequired
     */
    public function testOptInValidationRequired($configValue, $expected)
    {
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', $configValue);

        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ContactController::class);
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
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', "statistical");

        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ContactController::class);
        $this->assertFalse($controller->isOptInError());
        $this->assertFalse($controller->send());
        $this->assertTrue($controller->isOptInError());
    }
}
