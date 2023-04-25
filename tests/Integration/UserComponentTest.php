<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Component\UserComponent;
use OxidEsales\Eshop\Application\Controller\RegisterController;
use OxidEsales\Eshop\Application\Controller\UserController;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Exception\ConnectionException;
use OxidEsales\Eshop\Core\Exception\InputException;
use OxidEsales\Eshop\Core\Exception\UserException;
use OxidEsales\Eshop\Core\InputValidator;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

final class UserComponentTest extends BaseTestCase
{
    use ServiceContainer;

    public function providerDeliveryAddressOptin(): array
    {
        //Optin will only be required on changed or new address.
        $addAddress = ['oxaddressid' => '-1'];
        $changedAddress = ['oxaddressid' => 'someuniqueid', 'oegdproptin_changeDelAddress' => '1'];

        return [
            'optin_true_checkbox_true_show_true_new' => [true, true, 'assertFalse', true, $addAddress],
            'optin_true_checkbox_true_show_true_change' => [true, true, 'assertFalse', true, $changedAddress],
            'optin_true_checkbox_true_show_true' => [true, true, 'assertFalse', true, []],

            'optin_true_checkbox_false_show_true_new' => [true, false, 'assertTrue', true, $addAddress],
            'optin_true_checkbox_false_show_true_change' => [true, false, 'assertTrue', true, $changedAddress],
            'optin_true_checkbox_false_show_true' => [true, false, 'assertTrue', true, []],

            'optin_false_checkbox_true_show_true' => [false, true, 'assertFalse', true, []],
            'optin_false_checkbox_false_show_true' => [false, false, 'assertFalse', true, []],

            'optin_true_checkbox_true_show_false' => [true, true, 'assertFalse', false, []],
            'optin_true_checkbox_false_show_false' => [true, false, 'assertFalse', false, []],

            'optin_false_checkbox_true_show_false' => [false, true, 'assertFalse', false, []],
            'optin_false_checkbox_false_show_false' => [false, false, 'assertFalse', false, []],
        ];
    }

    /**
     * Test checkbox validation.
     *
     * @dataProvider providerDeliveryAddressOptin
     */
    public function testDeliveryAddressOptinValidationCheckoutUser(
        bool $requireGdprOptinDeliveryAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        bool $showShip,
        array $parameters
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::DELIVERY_OPT_IN,
            $requireGdprOptinDeliveryAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_deliveryaddress'] = (int) $checkboxChecked;
        $parameters['blshowshipaddress'] = (int) $showShip;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $cmpUser->changeuser();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_deliveryaddress', $displayErrors));
    }

    /**
     * @dataProvider providerDeliveryAddressOptin
     */
    public function testDeliveryAddressOptinValidationAccountUser(
        bool $requireGdprOptinDeliveryAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        bool $showShip,
        array $parameters
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::DELIVERY_OPT_IN,
            $requireGdprOptinDeliveryAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_deliveryaddress'] = (int) $checkboxChecked;
        $parameters['blshowshipaddress'] = (int) $showShip;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $cmpUser->changeuser_testvalues();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_deliveryaddress', $displayErrors));
    }

    public function providerInvoiceAddressOptin(): array
    {
        //Optin will be required on changed invoice address
        $changedAddress = ['oegdproptin_changeInvAddress' => '1'];

        return [
            'optin_true_checkbox_true_change' => [true, true, 'assertFalse', $changedAddress],
            'optin_true_checkbox_true_no_change' => [true, true, 'assertFalse', []],

            'optin_true_checkbox_false_change' => [true, false, 'assertTrue', $changedAddress],
            'optin_true_checkbox_false_no_change' => [true, false, 'assertFalse', []],

            'optin_false_checkbox_false_change' => [false, false, 'assertFalse', $changedAddress],
            'optin_false_checkbox_false_no_change' => [false, false, 'assertFalse', []],

            'optin_false_checkbox_true_change' => [false, true, 'assertFalse', $changedAddress],
            'optin_false_checkbox_true_no_change' => [false, true, 'assertFalse', []],
        ];
    }

    /**
     * @dataProvider providerInvoiceAddressOptin
     */
    public function testInvoiceAddressOptinValidationCheckoutUser(
        bool $requireGdprOptinInvoiceAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        array $parameters
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            $requireGdprOptinInvoiceAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_invoiceaddress'] = (int) $checkboxChecked;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $cmpUser->changeuser();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_invoiceaddress', $displayErrors));
    }

    /**
     * @dataProvider providerInvoiceAddressOptin
     */
    public function testInvoiceAddressOptinValidationAccountUser(
        bool $requireGdprOptinInvoiceAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        array $parameters
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            $requireGdprOptinInvoiceAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_invoiceaddress'] = (int) $checkboxChecked;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $cmpUser->changeuser_testvalues();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_invoiceaddress', $displayErrors));
    }

    public function testAccountUserSessionChallengeFail(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            true,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_invoiceaddress'] = 1;
        $this->addRequestParameters($parameters, false); //we will fail session challenge

        $cmpUser = oxNew(UserComponent::class);
        $this->assertNull($cmpUser->changeuser_testvalues());
    }

    public function testAccountNoSessionUser(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::INVOICE_OPT_IN,
            true,
            GdprOptinModule::MODULE_ID
        );

        $parameters['oegdproptin_invoiceaddress'] = 1;
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $cmpUser->setUser(false);
        $this->assertNull($cmpUser->changeuser_testvalues());
    }

    public function providerUserRegistrationOptin(): array
    {
        return [
            'enable_true_optin_true_register' => [true, true, 'assertFalse'],
            'enable_true_optin_false_register' => [true, false, 'assertTrue'],
            'enable_false_optin_true_register' => [false, true, 'assertFalse'],
            'enable_false_optin_false_register' => [false, false, 'assertFalse']
        ];
    }

    /**
     * @dataProvider providerUserRegistrationOptin
     */
    public function testUserRegistrationOptinValidation(
        bool $oeGdprUserRegistrationAddress,
        bool $checkboxChecked,
        string $assertDisplayExc
    ): void {
        Registry::getSession()->setUser(null);

        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REGISTRATION_OPT_IN,
            $oeGdprUserRegistrationAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters = ['oegdproptin_userregistration' => (int) $checkboxChecked,
                       'option' => 3];
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $parentView = oxNew(RegisterController::class);
        $cmpUser->setParent($parentView);
        $cmpUser->createUser();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_userregistration', $displayErrors));
    }

    public function providerUserCheckoutRegistrationOptin(): array
    {
        return [
             'enable_true_optin_true_guestbuy' => [true, true, 'assertFalse', 1],
             'enable_true_optin_false_guestbuy' => [true, false, 'assertFalse', 1],
             'enable_false_optin_true_guestbuy' => [false, true, 'assertFalse', 1],
             'enable_false_optin_false_guestbuy' => [false, false, 'assertFalse', 1],
             'enable_true_optin_true_createuser' => [true, true, 'assertFalse', 3],
             'enable_true_optin_false_createuser' => [true, false, 'assertTrue', 3],
             'enable_false_optin_true_createuser' => [false, true, 'assertFalse', 3],
             'enable_false_optin_false_createuser' => [false, false, 'assertFalse', 3]
        ];
    }
    /**
     * @dataProvider  providerUserCheckoutRegistrationOptin
     */
    public function testUserRegistrationOptinValidationCheckoutUser(
        bool $oeGdprUserRegistrationAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        int $option //(1 = guest buy/no optin, 3 = create account)
    ): void {
        Registry::getSession()->setUser(null);

        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REGISTRATION_OPT_IN,
            $oeGdprUserRegistrationAddress,
            GdprOptinModule::MODULE_ID
        );

        $parameters = ['oegdproptin_userregistration' => (int) $checkboxChecked,
                       'option' => $option];
        $this->addRequestParameters($parameters);

        $cmpUser = oxNew(UserComponent::class);
        $parentView = oxNew(UserController::class);
        $cmpUser->setParent($parentView);
        $cmpUser->createUser();

        $displayErrors = Registry::getSession()->getVariable('Errors');
        $this->$assertDisplayExc(array_key_exists('oegdproptin_userregistration', $displayErrors));
    }

    public function providerChangeUserDataExceptions(): array
    {
        return [
            'input_exception' => [
                new InputException('testBlockedUser', 123)
            ],
            'user_exception' => [
                new UserException('testBlockedUser', 123)
            ],
            'connection_exception' => [
                new ConnectionException('testBlockedUser', 123)
            ],
        ];
    }

    /**
     * @dataProvider  providerChangeUserDataExceptions
     */
    public function testChangeUserDataExceptions(\Exception $exception): void
    {
        $mssMock = $this->createPartialMock(User::class, ['changeUserData']);
        $mssMock->method('changeUserData')->will($this->throwException($exception));
        $oSession = $this->createPartialMock(Session::class, ['checkSessionChallenge']);
        $oSession->expects($this->atLeastOnce())->method('checkSessionChallenge')->will($this->returnValue(true));
        Registry::set(Session::class, $oSession);
        $oUserView = $this->createPartialMock(UserComponent::class, ['getUser', 'getDelAddressData']);
        $oUserView->expects($this->atLeastOnce())->method('getDelAddressData');
        $oUserView->expects($this->atLeastOnce())->method('getUser')->will($this->returnValue(new User()));
        $this->assertNull($oUserView->changeuser_testvalues());
    }

    public function testChangeUserAddress()
    {
        Registry::set(InputValidator::class, oxNew(InputValidator::class));
        $parameters['blnewssubscribed'] = false;
        $parameters['blshowshipaddress'] = true;
        $parameters['oegdproptin_deliveryaddress'] = true;
        $parameters['invadr'] = [
            // Existing fields which users should not be able to change.
            'oxuser__oxid'        => 'newId',
            'oxid'                => 'newId',
            'oxuser__oxpoints'    => 'newPoints',
            'oxpoints'            => 'newPoints',
            'oxuser__oxboni'      => 'newBoni',
            'oxboni'              => 'newBoni',

            // By default, user should not be capable to change new fields.
            'oxaddress__newfield' => 'newId',
            'newfield'            => 'newId',

            // Fields which users should be capable to change.
            'oxuser__oxusername'  => 'gdpruser@oxid.de',
            // values have to be trimmed
            'oxuser__oxfname'     => ' fname ',
            'oxuser__oxlname'     => ' lname ',
            'oxuser__oxstreetnr'  => 'nr',
            'oxuser__oxstreet'    => ' street ',
            'oxuser__oxzip'       => 'zip',
            'oxuser__oxcity'      => 'city',
            'oxuser__oxcountryid' => 'a7c40f631fc920687.20179984'
        ];

        $parameters['deladr'] = [
            'oxaddress__oxid'            => 'newId',
            'oxid'                       => 'newId',
            'oxaddress__oxuserid'        => 'newId',
            'oxuserid'                   => 'newId',
            'oxaddress__oxaddressuserid' => 'newId',
            'oxaddressuserid'            => 'newId',

            // By default, user should not be capable to change new fields.
            'oxaddress__newfield'        => 'newId',
            'newfield'                   => 'newId',

            // Fields which users should be capable to change.
            'oxuser__oxusername'         => 'gdpruser@oxid.de',
            // values have to be trimmed
            'oxaddress__oxfname'         => ' fname ',
            'oxaddress__oxlname'         => ' lname ',
            'oxaddress__oxstreetnr'      => 'nr',
            'oxaddress__oxstreet'        => ' street ',
            'oxaddress__oxzip'           => 'zip',
            'oxaddress__oxcity'          => 'city',
            'oxaddress__oxcountryid'     => 'a7c40f631fc920687.20179984',
        ];
        $this->addRequestParameters($parameters);
        $user = oxNew(User::class);
        $user->load(self::TEST_USER_ID);
        $oSession = $this->createPartialMock(Session::class, ['checkSessionChallenge']);
        $oSession->expects($this->atLeastOnce())->method('checkSessionChallenge')->will($this->returnValue(true));
        Registry::set(Session::class, $oSession);

        $cmpUser = oxNew(UserComponent::class);
        $this->assertEquals('account_user', $cmpUser->changeuser_testvalues());

        $user->load(self::TEST_USER_ID);
        //User cannot change black listed data
        $this->assertEquals('_gdprtest', $user->getFieldData('oxid'));
        //Street is changed and trimmed
        $this->assertEquals('street', $user->getFieldData('oxstreet'));
    }
}
