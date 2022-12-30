<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingService;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Service\ReviewOptIn;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OxidEsales\GdprOptinModule\Service\ReviewOptIn
 */
class ReviewOptInServiceTest extends TestCase
{
    /**
     * @dataProvider dataProviderValidateOptIn
     */
    public function testValidateOptIn(bool $configValue, int|null $checkboxStatus, bool $expectedValue)
    {
        $service = $this->prepareService($configValue, $checkboxStatus);

        $this->assertSame($expectedValue, $service->validateOptIn());
    }

    public function dataProviderValidateOptIn(): array
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
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError(bool $configValue, int|null $checkboxStatus, bool $expectedValue): void
    {
        $service = $this->prepareService($configValue, $checkboxStatus);

        $this->assertSame($expectedValue, $service->isReviewOptInError());
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

    private function prepareService(bool $configValue, int|null $checkboxStatus): ReviewOptIn
    {
        $mssMock = $this->createPartialMock(ModuleSettingService::class, ['getBoolean']);
        $mssMock->expects($this->any())
            ->method('getBoolean')
            ->with(ModuleSettings::REVIEW_OPT_IN, GdprOptinModule::MODULE_ID)
            ->willReturn($configValue);

        $settingsService = new ModuleSettings($mssMock);

        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->expects($this->any())
            ->method('getRequestParameter')
            ->willReturnMap([['rvw_oegdproptin', null, $checkboxStatus]]);
        $this->assertSame($checkboxStatus, $request->getRequestParameter('rvw_oegdproptin'));

        return new ReviewOptIn($request, $settingsService);
    }
}
