<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\Eshop\Application\Component\Widget\Review;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

/**
 * @covers \OxidEsales\GdprOptinModule\Controller\ReviewController
 * @covers \OxidEsales\GdprOptinModule\Component\Widget\ArticleDetails
 * @covers \OxidEsales\GdprOptinModule\Component\Widget\Review
 * @covers \OxidEsales\GdprOptinModule\Traits\ReviewOptin
 */
class OptinTraitTest extends BaseTestCase
{
    use ServiceContainer;

    public function dataProviderReviewOptInValidationRequired(): array
    {
        return [
            'required_art' => [true, true, ArticleDetails::class],
            'not_required_art' => [false, false, ArticleDetails::class],
            'required_rev' => [true, true, Review::class],
            'not_required_rev' => [false, false, Review::class],
            'required_controller' => [true, true, ReviewController::class],
            'not_required_controller' => [false, false, ReviewController::class]
        ];
    }

    /**
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired(bool $configValue, bool $expected, string $testClass): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew($testClass);

        $this->assertSame($expected, $controller->isReviewOptInValidationRequired());
    }

    public function dataProviderReviewOptInError(): array
    {
        return [
            'required-checked_art' => [true, 1, false, ArticleDetails::class],
            'required-not-checked_art' => [true, 0, true, ArticleDetails::class],
            'required-not-exist_art' => [true, null, false, ArticleDetails::class],
            'not-required-checked_art' => [false, 1, false, ArticleDetails::class],
            'not-required-not-checked_art' => [false, 0, false, ArticleDetails::class],
            'not-required-not-exits_art' => [false, null, false, ArticleDetails::class],

            'required-checked_rev' => [true, 1, false, Review::class],
            'required-not-checked_rev' => [true, 0, true, Review::class],
            'required-not-exist_rev' => [true, null, false, Review::class],
            'not-required-checked_rev' => [false, 1, false, Review::class],
            'not-required-not-checked_rev' => [false, 0, false, Review::class],
            'not-required-not-exits_rev' => [false, null, false, Review::class],

            'required-checked_controller' => [true, 1, false, ReviewController::class],
            'required-not-checked_controller' => [true, 0, true, ReviewController::class],
            'required-not-exist_controller' => [true, null, false, ReviewController::class],
            'not-required-checked_controller' => [false, 1, false, ReviewController::class],
            'not-required-not-checked_controller' => [false, 0, false, ReviewController::class],
            'not-required-not-exits_controller' => [false, null, false, ReviewController::class]
        ];
    }

    /**
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError(
        bool $configValue,
        int|null $checkboxStatus,
        bool $expectedValue,
        string $testClass
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        //simulate genuine request instead of mocking
        $_POST = ['rvw_oegdproptin' => $checkboxStatus];

        $controller = oxNew($testClass);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }
}
