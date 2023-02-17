<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Codeception;

use Codeception\Scenario;
use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Page\Home;
use OxidEsales\Codeception\Step\Basket;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\Codeception\Page\Account\UserAddress;

/**
 * @group gdproptin_module
 * @group gdproptin_module_address
 */
final class UserAddressOptinCest extends BaseCest
{
    public function testNoInvoiceAddressOptinNecessary(AcceptanceTester $I): void
    {
        $I->wantToTest('that user does not see invoice address optin');

        //delivery optin must not interfere, so let' set it to required
        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);

        /** @var UserAddress $userAddress */
        $userAddress = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openAccountPage()
            ->openUserAddressPage();

        $userAddress->openUserBillingAddressForm();

        $I->dontSeeElement('#oegdproptin_invoiceaddress');
        $I->dontSee(Translator::translate('OEGDPROPTIN_STORE_INVOICE_ADDRESS'));

        $I->retryClick($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS'));
        $I->dontSeeElement($userAddress->billCountryId);
    }

    public function testInvoiceAddressOptinRequired(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to invoice address optin');

        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        /** @var UserAddress $userAddress */
        $userAddress = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openAccountPage()
            ->openUserAddressPage();

        $userAddress->openUserBillingAddressForm();

        $I->seeElementInDOM('#oegdproptin_invoiceaddress');
        $I->see(Translator::translate('OEGDPROPTIN_STORE_INVOICE_ADDRESS'));
        $I->retryClick($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS'));

        $I->click('#oegdproptin_invoiceaddress');
        $I->seeCheckboxIsChecked('#oegdproptin_invoiceaddress');
        $I->click($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS'));
        $I->dontSeeElement($userAddress->billCountryId);
    }

    public function testNoDeliveryAddressOptinNecessary(AcceptanceTester $I): void
    {
        $I->wantToTest('that user does not see delivery address optin');

        //billing optin must not interfere, so let' set it to required
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, true);

        /** @var UserAddress $userAddress */
        $userAddress = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openAccountPage()
            ->openUserAddressPage()
            ->openShippingAddressForm()
            ->enterShippingAddressData($this->getUserAddressFormData());

        $I->dontSeeElementInDOM('#oegdproptin_deliveryaddress');
        $I->dontSee(Translator::translate('OEGDPROPTIN_STORE_DELIVERY_ADDRESS'));
        $I->retryClick($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));
        $I->dontSeeElement($userAddress->shipAddressForm);
    }

    public function testDeliveryAddressOptinRequired(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to delivery address optin');

        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, true);

        /** @var UserAddress $userAddress */
        $userAddress = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openAccountPage()
            ->openUserAddressPage()
            ->openShippingAddressForm()
            ->enterShippingAddressData($this->getUserAddressFormData());

        $I->seeElementInDOM('#oegdproptin_deliveryaddress');
        $I->see(Translator::translate('OEGDPROPTIN_STORE_DELIVERY_ADDRESS'));
        $I->retryClick($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));

        $I->click('#oegdproptin_deliveryaddress');
        $I->seeCheckboxIsChecked('#oegdproptin_deliveryaddress');
        $I->click($userAddress->saveUserAddressButton);

        $I->waitForPageLoad();
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS'));
        $I->dontSeeElement($userAddress->shipAddressForm);
    }
}
