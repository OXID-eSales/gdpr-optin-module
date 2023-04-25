<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use PHPUnit\Framework\TestCase;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

abstract class BaseTestCase extends TestCase
{
    use ServiceContainer;

    protected const TEST_USER_ID = '_gdprtest';

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestUser();
        $this->disableOptins();
    }

    protected function createTestUser(): void
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
    protected function ensureActiveUser(): void
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
     */
    protected function addRequestParameters(array $additionalParameters = [], bool $addToken = true): void
    {
        $address = 'a:13:{s:16:"oxaddress__oxsal";s:2:"MR";s:18:"oxaddress__oxfname";s:4:"Moxi";' .
            's:18:"oxaddress__oxlname";s:6:"Muster";s:20:"oxaddress__oxcompany";s:0:"";' .
            's:20:"oxaddress__oxaddinfo";s:0:"";s:19:"oxaddress__oxstreet";s:10:"Nicestreet";' .
            's:21:"oxaddress__oxstreetnr";s:3:"666";s:16:"oxaddress__oxzip";s:5:"12345";' .
            's:17:"oxaddress__oxcity";s:9:"Somewhere";s:22:"oxaddress__oxcountryid";' .
            's:26:"a7c40f631fc920687.20179984";s:20:"oxaddress__oxstateid";s:0:"";' .
            's:16:"oxaddress__oxfon";s:0:"";s:16:"oxaddress__oxfax";s:0:"";}';

        $deliveryAddress = $additionalParameters['deladr'] ?: unserialize($address);
        $parameters = ['deladr' => $deliveryAddress];
        if ($addToken) {
            $parameters['stoken'] = Registry::getSession()->getSessionChallengeToken();
        }

        $parameters = array_merge($parameters, $additionalParameters);

        $map = [];
        foreach ($parameters as $key => $value) {
            $map[] = [$key, null, $value];
        }

        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getRequestParameter'])
            ->setMockClassName('Request_' . md5(serialize($parameters)))
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
