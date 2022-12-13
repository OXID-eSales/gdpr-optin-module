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

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateEngineInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererBridgeInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use PHPUnit\Framework\TestCase;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

abstract class IntegrationBaseTest extends TestCase
{
    use ServiceContainer;

    protected const TEST_USER_ID = '_gdprtest';

    protected function setUp(): void
    {
        parent::setUp();

        GdprOptinModule::clearCache();
        $this->createTestUser();
        $this->disableOptins();
    }

    /**
     * Create a test user.
     */
    protected function createTestUser()
    {
        $user = oxNew(User::class);
        $user->setId(self::TEST_USER_ID);
        $user->assign(
            [
                'oxfname'             => 'Max',
                'oxlname'             => 'Mustermann',
                'oxusername'          => 'gdpruser@oxid.de',
                'oxpassword'          => md5('agent'),
                'oxactive'            => 1,
                'oxshopid'            => 1,
                'oxcountryid'         => 'a7c40f631fc920687.20179984',
                'oxboni'              => '600',
                'oxstreet'            => 'Teststreet',
                'oxstreetnr'          => '101',
                'oxcity'              => 'Hamburg',
                'oxzip'               => '22769'
            ]
        );
        $user->save();

        //Ensure we have it in session and as active user
        $this->ensureActiveUser();
    }

    /**
     * Make sure we have the test user as active user.
     */
    protected function ensureActiveUser()
    {
        $this->setSessionParam('usr', self::TEST_USER_ID);
        $this->setSessionParam('auth', self::TEST_USER_ID);

        $user = oxNew(User::class);
        $user->load(self::TEST_USER_ID);
        Registry::getSession()->setUser($user);
        $user->setUser($user);
        $this->assertTrue($user->loadActiveUser());
    }

    /**
     * Test helper for setting request parameters.
     *
     * @param array $parameters
     */
    protected function addRequestParameters($additionalParameters = [])
    {
        $address = 'a:13:{s:16:"oxaddress__oxsal";s:2:"MR";s:18:"oxaddress__oxfname";s:4:"Moxi";' .
            's:18:"oxaddress__oxlname";s:6:"Muster";s:20:"oxaddress__oxcompany";s:0:"";' .
            's:20:"oxaddress__oxaddinfo";s:0:"";s:19:"oxaddress__oxstreet";s:10:"Nicestreet";' .
            's:21:"oxaddress__oxstreetnr";s:3:"666";s:16:"oxaddress__oxzip";s:5:"12345";' .
            's:17:"oxaddress__oxcity";s:9:"Somewhere";s:22:"oxaddress__oxcountryid";' .
            's:26:"a7c40f631fc920687.20179984";s:20:"oxaddress__oxstateid";s:0:"";' .
            's:16:"oxaddress__oxfon";s:0:"";s:16:"oxaddress__oxfax";s:0:"";}';

        $deliveryAddress = unserialize($address);
        $parameters = ['deladr' => $deliveryAddress,
            'stoken' => Registry::getSession()->getSessionChallengeToken()];

        $parameters = array_merge($parameters, $additionalParameters);

        $map = [];
        foreach ($parameters as $key => $value) {
            $map[] = [$key, null, $value];
        }

        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getRequestParameter'])
            ->getMock();
        $request->expects($this->any())
            ->method('getRequestParameter')
            ->willReturnMap($map);

        Registry::set(Request::class, $request);
    }

    protected function setSessionParam($name, $value): void
    {
        Registry::getSession()->setVariable($name, $value);
    }

    protected function disableOptins(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);

        $settings = [
            ModuleSettings::INVOICE_OPT_IN,
            ModuleSettings::DELIVERY_OPT_IN,
            ModuleSettings::REGISTRATION_OPT_IN,
            ModuleSettings::REVIEW_OPT_IN
        ];
        foreach ($settings as $name) {
            $settingsService->saveBoolean(
                $name,
                false,
                GdprOptinModule::MODULE_ID
            );
        }
    }
}
