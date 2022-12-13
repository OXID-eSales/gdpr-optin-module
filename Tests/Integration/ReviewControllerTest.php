<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

class ReviewControllerTest extends IntegrationBaseTest
{
    use ServiceContainer;

    public function testSendError(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            true,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ReviewController::class);

        $this->assertFalse($controller->saveReview());
    }

    public function testSendNotError(): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            false,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ReviewController::class);

        $this->assertNull($controller->saveReview());
    }

    /**
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired(bool $configValue, bool $expected): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew(ReviewController::class);

        $this->assertSame($expected, $controller->isReviewOptInValidationRequired());
    }

    public function dataProviderReviewOptInValidationRequired(): array
    {
        return [
            'required' => [true, true],
            'not-required' => [false, false]
        ];
    }

    /**
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError(bool $configValue, int|null $checkboxStatus, bool $expectedValue): void
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        //simulate genuine request instead of mocking
        $_POST = ['rvw_oegdproptin' => $checkboxStatus];

        $controller = oxNew(ReviewController::class);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }

    public function dataProviderReviewOptInError(): array
    {
        return [
            'required-checked' => [true, 1, false],
            'required-not-checked' => [true, 0, true],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, false],
            'not-required-not-checked' => [false, 0, false],
            'not-required-not-exits' => [false, null, false]
        ];
    }
}
