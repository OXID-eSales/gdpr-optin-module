<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Codeception;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;

/**
 * @group gdproptin_module
 * @group gdproptin_module_contact
 */
final class ContactCest extends BaseCest
{
    public function testDeletionContactMessage(AcceptanceTester $I): void
    {
        $I->wantToTest('that user sees gdpr delete data message');

        $I->openShop();
        $I->retryClick(Translator::translate('CONTACT'));
        $I->waitForPageLoad();

        $I->see(Translator::translate('DD_CONTACT_PAGE_HEADING'));
        $I->see(Translator::translate('OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION'));
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL'));

        $I->fillField('#contactEmail', $this->username);
        $I->retryClick(Translator::translate('SEND'));
        $I->waitForPageLoad();

        $I->see(Translator::translate('PAGE_DETAILS_THANKYOUMESSAGE1'));
        $I->see(Translator::translate('PAGE_DETAILS_THANKYOUMESSAGE2'));
    }

    public function testOptinContactMessage(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to contact optin');

        $I->setModuleSettingString(ModuleSettings::CONTACT_CHOICE, 'statistical');

        $I->openShop();
        $I->retryClick(Translator::translate('CONTACT'));
        $I->waitForPageLoad();

        $I->see(Translator::translate('DD_CONTACT_PAGE_HEADING'));
        $I->dontSee(Translator::translate('OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION'));
        $I->see(Translator::translate('OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL'));

        $I->fillField('#contactEmail', $this->username);
        $I->retryClick(Translator::translate('SEND'));
        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE'));

        $I->seeElement('#c_oegdproptin');
        $I->retryClick('#c_oegdproptin');
        $I->seeCheckboxIsChecked('#c_oegdproptin');
        $I->retryClick(Translator::translate('SEND'));
        $I->waitForPageLoad();

        $I->see(Translator::translate('PAGE_DETAILS_THANKYOUMESSAGE1'));
        $I->see(Translator::translate('PAGE_DETAILS_THANKYOUMESSAGE2'));
    }
}
