<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Codeception;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Page\Checkout\UserCheckout;
use OxidEsales\Codeception\Step\Basket;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;

/**
 * @group gdproptin_module
 * @group gdproptin_module_checkout
 */
final class CheckoutCest extends BaseCest
{
    public function testNoOptinRequiredDuringCheckoutRegistration(AcceptanceTester $I): void
    {
        $I->wantToTest('that user can register during checkout without optin');

        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        $I->openShop();
        $basket = new Basket($I);

        /** @var UserCheckout $userCheckout */
        $userCheckout = $basket
            ->addProductToBasketAndOpenUserCheckout('1000', 1)
            ->selectOptionRegisterNewAccount();

        $userCheckout->enterUserLoginData($this->getUserLoginData('checkout1'))
            ->enterAddressData($this->getUserAddressFormData())
            ->goToNextStep();

        $I->see(Translator::translate('SELECT_SHIPPING_METHOD'));
    }

    public function testOptinNeverRequiredDuringGuestCheckout(AcceptanceTester $I): void
    {
        $I->wantToTest('that user can guest buy without optin');

        $I->setModuleSettingBoolean(ModuleSettings::REGISTRATION_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        $I->openShop();
        $basket = new Basket($I);

        /** @var UserCheckout $userCheckout */
        $userCheckout = $basket
            ->addProductToBasketAndOpenUserCheckout('1000', 1)
            ->selectOptionNoRegistration();

        $userCheckout->enterUserLoginName($this->getUserLoginData('checkout2')['userLoginNameField'])
            ->enterAddressData($this->getUserAddressFormData())
            ->goToNextStep();

        $I->see(Translator::translate('SELECT_SHIPPING_METHOD'));
    }

    public function testOptinRequiredDuringCheckoutRegistration(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to optin when registering during checkout');

        //set all optins to tru, only registration one will be needed
        $I->setModuleSettingBoolean(ModuleSettings::REGISTRATION_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        $I->openShop();
        $basket = new Basket($I);

        /** @var UserCheckout $userCheckout */
        $userCheckout = $basket
            ->addProductToBasketAndOpenUserCheckout('1000', 1)
            ->selectOptionRegisterNewAccount();

        $I->seeElementInDOM('#oegdproptin_userregistration');
        $I->dontSeeElementInDOM('#oegdproptin_invoiceaddress');
        $I->dontSeeElementInDOM('#oegdproptin_deliveryaddress');
        $I->seeElementInDOM('#oegdproptin_userregistration');
        $I->see(Translator::translate('OEGDPROPTIN_USER_REGISTRATION_OPTIN'));

        $userCheckout->enterUserLoginData($this->getUserLoginData('checkout3'))
            ->enterAddressData($this->getUserAddressFormData());
        $I->retryClick($userCheckout->nextStepButton);

        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN'));

        $I->click('#oegdproptin_userregistration');
        $I->seeCheckboxIsChecked('#oegdproptin_userregistration');

        $userCheckout->openShippingAddressForm();
        $userCheckout->enterUserLoginData($this->getUserLoginData('checkout3'))
            ->enterShippingAddressData($this->getUserAddressFormData())
            ->goToNextStep();

        $I->see(Translator::translate('SELECT_SHIPPING_METHOD'));
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN'));
    }

    public function testOptinsForAddressChangeInCheckout(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to optin address changes during checkout');

        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        $I->openShop()
            ->loginUser($this->username, $this->password);

        $basket = new Basket($I);

        /** @var UserCheckout $userCheckout */
        $userCheckout = $basket
            ->addProductToBasketAndOpenUserCheckout('1000', 1)
            ->openShippingAddressForm()
            ->openUserBillingAddressForm();

        $I->seeElementInDOM('#oegdproptin_invoiceaddress');
        $I->see(Translator::translate('OEGDPROPTIN_STORE_INVOICE_ADDRESS'));

        $I->seeElementInDOM('#oegdproptin_deliveryaddress');
        $I->see(Translator::translate('OEGDPROPTIN_STORE_DELIVERY_ADDRESS'));

        $userCheckout
            ->enterAddressData($this->getUserAddressFormData())
            ->enterShippingAddressData($this->getUserAddressFormData());
        $I->retryClick($userCheckout->nextStepButton);

        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS'));
        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));

        $I->retryClick('#oegdproptin_invoiceaddress');
        $I->seeCheckboxIsChecked('#oegdproptin_invoiceaddress');
        $I->retryClick($userCheckout->nextStepButton);

        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));

        $I->seeCheckboxIsChecked('#oegdproptin_invoiceaddress');
        $I->retryClick('#oegdproptin_deliveryaddress');
        $I->seeCheckboxIsChecked('#oegdproptin_deliveryaddress');
        $userCheckout
            ->goToNextStep();

        $I->see(Translator::translate('SELECT_SHIPPING_METHOD'));
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));
    }
}
