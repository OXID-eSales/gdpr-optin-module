<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Tests\Codeception\Support\AcceptanceTester;

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

        $I->retryClick($userForm->saveFormButton);
        $I->waitForPageLoad();

        $I->see(Translator::translate('MESSAGE_WELCOME_REGISTERED_USER'));
    }

    public function testRegistrationOptinRequired(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to registration optin');

        $I->setModuleSettingBoolean(ModuleSettings::REGISTRATION_OPT_IN, true);

        $registrationPage = $I->openShop()
            ->openUserRegistrationPage();

        $I->seeElementInDOM('#oegdproptin_userregistration');
        $I->see(Translator::translate('OEGDPROPTIN_USER_REGISTRATION_OPTIN'));

        $userForm = $registrationPage->enterUserLoginData($this->getUserLoginData('second'))
            ->enterAddressData($this->getUserAddressData());

        $I->retryClick($userForm->saveFormButton);
        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN'));

        $userForm = $registrationPage->enterUserLoginData($this->getUserLoginData('second'))
            ->enterAddressData($this->getUserAddressData());
        $I->scrollTo('header');//scroll to top, scrolling to gdpr checkbox will be still hidden by header
        $I->wait(1);//scroll to top, scrolling to gdpr checkbox will be still hidden by header
        $I->click('#oegdproptin_userregistration');
        $I->seeCheckboxIsChecked('#oegdproptin_userregistration');
        $I->retryClick($userForm->saveFormButton);
        $I->waitForPageLoad();

        $I->see(Translator::translate('MESSAGE_WELCOME_REGISTERED_USER'));
    }
}
