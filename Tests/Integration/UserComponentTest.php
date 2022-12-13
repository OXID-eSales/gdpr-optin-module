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

use OxidEsales\Eshop\Application\Component\UserComponent;
use OxidEsales\Eshop\Application\Controller\RegisterController;
use OxidEsales\Eshop\Application\Controller\UserController;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

final class UserComponentTest extends IntegrationBaseTest
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
     * Test checkbox validation.
     *
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

    /**
     * @return array
     */
    public function providerInvoiceAddressOptin()
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
     * Test checkbox validation.
     *
     * @dataProvider providerInvoiceAddressOptin
     *
     * @param bool   $requireGdprOptinInvoiceAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param array  $parameters
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
     * Test checkbox validation.
     *
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

    /**
     * @return array
     */
    public function providerUserRegistrationOptin()
    {
        return [
            'enable_true_optin_true_register' => [true, true, 'assertFalse'],
            'enable_true_optin_false_register' => [true, false, 'assertTrue'],
            'enable_false_optin_true_register' => [false, true, 'assertFalse'],
            'enable_false_optin_false_register' => [false, false, 'assertFalse']
        ];
    }

    /**
     * Test checkbox validation.
     *
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
     * Test checkbox validation.
     *
     * @dataProvider  providerUserCheckoutRegistrationOptin
     *
     * @param bool   $oeGdprUserRegistrationAddress
     * @param bool   $checkboxChecked
     * @param string $assertDisplayExc
     * @param int    $option (1 = guest buy/no optin, 3 = create account)
     */
    public function testUserRegistrationOptinValidationCheckoutUser(
        bool $oeGdprUserRegistrationAddress,
        bool $checkboxChecked,
        string $assertDisplayExc,
        $option
    ) {
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
}
