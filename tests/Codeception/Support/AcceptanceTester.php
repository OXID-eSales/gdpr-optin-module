<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Tests\Codeception\Support;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Admin\AdminLoginPage;
use OxidEsales\Codeception\Admin\AdminPanel;
use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Page\Home;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Tests\Traits\ServiceContainer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
final class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    use \Codeception\Lib\Actor\Shared\Retry;

    use ServiceContainer;

    /**
     * Open shop first page.
     */
    public function openShop()
    {
        $I = $this;
        Translator::switchTranslationDomain(Translator::TRANSLATION_DOMAIN_SHOP);
        $homePage = new Home($I);
        $I->amOnPage($homePage->URL);
        return $homePage;
    }

    public function openAdmin(): AdminLoginPage
    {
        $I = $this;
        Translator::switchTranslationDomain(Translator::TRANSLATION_DOMAIN_ADMIN);
        $adminLogin = new AdminLoginPage($I);
        $I->amOnPage($adminLogin->URL);
        return $adminLogin;
    }

    public function loginAdmin(): AdminPanel
    {
        $adminPage = $this->openAdmin();
        $admin = Fixtures::get('adminUser');
        return $adminPage->login($admin['userLoginName'], $admin['userPassword']);
    }

    public function setModuleSettingBoolean(string $name, bool $value): void
    {
        $I = $this;

        $I->getServiceFromContainer(ModuleSettingServiceInterface::class)
            ->saveBoolean(
                $name,
                $value,
                GdprOptinModule::MODULE_ID
            );
    }

    public function setModuleSettingString(string $name, string $value): void
    {
        $I = $this;

        $I->getServiceFromContainer(ModuleSettingServiceInterface::class)
            ->saveString(
                $name,
                $value,
                GdprOptinModule::MODULE_ID
            );
    }
}
