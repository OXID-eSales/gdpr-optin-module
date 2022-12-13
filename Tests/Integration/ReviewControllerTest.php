<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

class ReviewControllerTest extends IntegrationBaseTest
{
    use ServiceContainer;

    /**
     * Test validation error appears if needed
     */
    public function testSendError()
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

    /**
     * Test validation error appears if needed
     */
    public function testSendNotError()
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
     * Test if validation is required.
     *
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired($configValue, $expected)
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

    /**
     * @return array
     */
    public function dataProviderReviewOptInValidationRequired()
    {
        return [
            'required' => [true, true],
            'not-required' => [false, false]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderValidateOptIn
     */
    public function testValidateOptIn($configValue, $checkboxStatus, $expectedValue)
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );
        $this->addRequestParameters(['rvw_oegdproptin' => $checkboxStatus]);

        $controller = oxNew(ReviewController::class);

        $this->assertSame($expectedValue, $controller->validateOptIn());
    }

    /**
     * @return array
     */
    public function dataProviderValidateOptIn()
    {
        return [
            'required-checked' => [true, 1, true],
            'required-not-checked' => [true, 0, false],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, true],
            'not-required-not-checked' => [false, 0, true],
            'not-required-not-exits' => [false, null, true]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError($configValue, $checkboxStatus, $expectedValue)
    {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );
        $this->addRequestParameters(['rvw_oegdproptin' => $checkboxStatus]);

        $controller = oxNew(ReviewController::class);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInError()
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
