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
 * @group gdproptin_module_review
 */
final class ReviewCest extends BaseCest
{
    public function testNoReviewOptinNecessary(AcceptanceTester $I): void
    {
        $I->wantToTest('that user does not see review optin');

        $detailsPage = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openCategoryPage(Fixtures::get('category'))
            ->openDetailsPage(1);

        $I->see(Translator::translate('WRITE_REVIEW'));
        $I->see(Translator::translate('NO_REVIEW_AVAILABLE'));
        $I->retryClick(Translator::translate('WRITE_REVIEW'));

        $I->reloadPage();
        $I->scrollTo($detailsPage->reviewTextForm);
        $detailsPage->addReviewAndRating('my shiny new review', 4);
        $I->waitForPageLoad();

        $I->see('my shiny new review');
        $I->dontSee(Translator::translate('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE'));
    }

    public function testReviewOptinRequired(AcceptanceTester $I): void
    {
        $I->wantToTest('that user needs to agree to review optin');

        $I->setModuleSettingBoolean(ModuleSettings::REVIEW_OPT_IN, true);

        $detailsPage = $I->openShop()
            ->loginUser($this->username, $this->password)
            ->openCategoryPage(Fixtures::get('category'))
            ->openDetailsPage(1);

        $I->see(Translator::translate('WRITE_REVIEW'));
        $I->see(Translator::translate('NO_REVIEW_AVAILABLE'));
        $I->retryClick(Translator::translate('WRITE_REVIEW'));

        $I->seeElementInDOM('#rvw_oegdproptin');
        $I->assertStringContainsString(
            'hidden',
            $I->grabAttributeFrom('#rvw_oegdproptin_error', 'class')
        );

        $I->reloadPage();
        $I->scrollTo($detailsPage->reviewTextForm);
        $detailsPage->addReviewAndRating('my shiny other review', 5);
        $I->waitForPageLoad();
        $I->see(Translator::translate('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE'));
        $I->assertStringNotContainsString(
            'hidden',
            $I->grabAttributeFrom('#rvw_oegdproptin_error', 'class')
        );

        $I->seeElementInDOM('#rvw_oegdproptin');
        $I->retryClick('#rvw_oegdproptin');
        $I->click($detailsPage->saveRatingAndReviewButton);

        $I->reloadPage();
        $I->see('my shiny other review');
        $I->assertStringContainsString(
            'hidden',
            $I->grabAttributeFrom('#rvw_oegdproptin_error', 'class')
        );
    }
}
