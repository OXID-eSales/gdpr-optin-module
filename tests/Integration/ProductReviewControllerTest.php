<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\Eshop\Application\Controller\ArticleDetailsController;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptInModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

class ProductReviewControllerTest extends BaseTestCase
{
    use ServiceContainer;

    public function dataProviderReviewSave(): array
    {
        return [
            'required_art' => [true, 'assertFalse', ArticleDetailsController::class],
            'not_require_art' => [false, 'assertNull', ArticleDetailsController::class],
            'required_rev' => [true, 'assertFalse', ReviewController::class],
            'not_require_rev' => [false, 'assertNull', ReviewController::class],
        ];
    }

    /**
     * @dataProvider dataProviderReviewSave
     */
    public function testErrorOnReviewSave(
        bool $configValue,
        string $assertMethod,
        string $testClass
    ): void {
        $settingsService = $this->getServiceFromContainer(ModuleSettingServiceInterface::class);
        $settingsService->saveBoolean(
            ModuleSettings::REVIEW_OPT_IN,
            $configValue,
            GdprOptinModule::MODULE_ID
        );

        $controller = oxNew($testClass);

        $this->$assertMethod($controller->saveReview());
    }
}
