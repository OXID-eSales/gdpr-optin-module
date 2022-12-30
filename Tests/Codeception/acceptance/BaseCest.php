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

class BaseCest
{
    protected string $username;
    protected string $password;

    public function _before(AcceptanceTester $I, Scenario $scenario): void
    {
        $this->prepareModuleConfiguration($I);
        $this->username = Fixtures::get('existingUser')['userLoginName'];
        $this->password = Fixtures::get('existingUser')['userPassword'];
    }

    protected function prepareModuleConfiguration(AcceptanceTester $I): void
    {
        $I->setModuleSettingBoolean(ModuleSettings::REVIEW_OPT_IN, false);
        $I->setModuleSettingBoolean(ModuleSettings::REGISTRATION_OPT_IN, false);
        $I->setModuleSettingBoolean(ModuleSettings::DELIVERY_OPT_IN, false);
        $I->setModuleSettingBoolean(ModuleSettings::INVOICE_OPT_IN, false);
        $I->setModuleSettingString(ModuleSettings::CONTACT_CHOICE, 'deletion');
    }

    protected function getUserAddressFormData(): array
    {
        return [
            'userSalutation' => 'Mrs',
            'userFirstName' => 'some-name',
            'userLastName' => 'some-last-name',
            'street' => 'some-street',
            'streetNr' => '1',
            'ZIP' => 'zip-1234',
            'city' => 'some-city',
            'countryId' => 'Germany',
            'companyName' => '',
            'additionalInfo' => '',
            'fonNr' => '',
            'faxNr' => '',
        ];
    }

    protected function getUserLoginData(string $name): array
    {
        return [
            'userLoginNameField' => $name . '_gdpr@oxid-esales.dev',
            'userPasswordField' => 'useruser'
        ];
    }

    protected function getUserAddressData(): array
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
