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
}