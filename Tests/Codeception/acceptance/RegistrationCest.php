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

/**
 * @group gdproptin_module
 * @group gdproptin_module_registration
 */
final class RegistrationCest extends BaseCest
{
    public function testNoRegistrationOptinNecessary(AcceptanceTester $I): void
    {
        $I->wantToTest('that user does not see registration optin');

        $registrationPage = $I->openShop()
            ->openUserRegistrationPage();

        $I->dontSeeElementInDOM('#oegdproptin_userregistration');
        $I->dontSee(Translator::translate('OEGDPROPTIN_USER_REGISTRATION_OPTIN'));

        $userForm = $registrationPage->enterUserLoginData($this->getUserLoginData('first'))
            ->enterAddressData($this->getUserAddressData());

        $I->click($userForm->saveFormButton);
        $I->waitForPageLoad();

        $I->see(Translator::translate('MESSAGE_WELCOME_REGISTERED_USER'));
    }

    public function testRegistrationOptinRequired(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to registration optin');

        $I->setModuleSettingBoolean(ModuleSettings::REGISTRATION_OPT_IN, true);
        $I->updateConfigInDatabase('blShowBirthdayFields', false, 'bool');

        $registrationPage = $I->openShop()
            ->openUserRegistrationPage();

        $I->seeElementInDOM('#oegdproptin_userregistration');
        $I->see(Translator::translate('OEGDPROPTIN_USER_REGISTRATION_OPTIN'));

        $userForm = $registrationPage->enterUserLoginData($this->getUserLoginData('second'))
            ->enterAddressData($this->getUserAddressData());

        $I->click($userForm->saveFormButton);
        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN'));

        $userForm = $registrationPage->enterUserLoginData($this->getUserLoginData('second'))
            ->enterAddressData($this->getUserAddressData());
        $I->click('#oegdproptin_userregistration');
        $I->seeCheckboxIsChecked('#oegdproptin_userregistration');
        $I->click($userForm->saveFormButton);
        $I->waitForPageLoad();

        $I->see(Translator::translate('MESSAGE_WELCOME_REGISTERED_USER'));
    }

    private function getUserLoginData(string $name): array
    {
        return [
            'userLoginNameField' => $name . '_gdpr@oxid-esales.dev',
            'userPasswordField' => 'useruser'
        ];
    }

    private function getUserAddressData(): array
    {
        return [
            'userSalutation' => 'Mrs',
            'userFirstName' => 'first_šÄßüл',
            'userLastName' => 'last_šÄßüл',
            'companyName' => 'company_šÄßüл',
            'street' => 'street_šÄßüл',
            'streetNr' => '123',
            'ZIP' => '12345',
            'city' => 'userregcity_šÄßüл',
            'additionalInfo' => 'userregadditional info_šÄßüл',
            'fonNr' => '111-111-123',
            'faxNr' => '111-111-111-7',
            'countryId' => 'Germany',
            'stateId' => 'Berlin',
        ];
    }
}