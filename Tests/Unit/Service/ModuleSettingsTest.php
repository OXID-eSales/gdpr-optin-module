<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingService;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule as Module;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

/**
 * @covers \OxidEsales\GdprOptinModule\Service\ModuleSettings
 */
final class ModuleSettingsTest extends TestCase
{
    /**
     * @dataProvider gettersDataProvider
     */
    public function testGetters($method, $systemMethod, $key, $systemValue, $expectedValue): void
    {
        $mssMock = $this->createPartialMock(ModuleSettingService::class, [$systemMethod]);
        $mssMock->expects($this->once())->method($systemMethod)->with(
            $key,
            Module::MODULE_ID
        )->willReturn($systemValue);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($expectedValue, $sut->$method());
    }

    public function gettersDataProvider(): array
    {
        return [
            $this->prepareStringTestItem(
                'getContactOptIn',
                ModuleSettings::CONTACT_CHOICE
            ),
            $this->prepareBoolTestItem(
                'showInvoiceOptIn',
                ModuleSettings::INVOICE_OPT_IN,
                true
            ),
            $this->prepareBoolTestItem(
                'showInvoiceOptIn',
                ModuleSettings::INVOICE_OPT_IN,
                false
            ),
            $this->prepareBoolTestItem(
                'showDeliveryOptIn',
                ModuleSettings::DELIVERY_OPT_IN,
                true
            ),
            $this->prepareBoolTestItem(
                'showDeliveryOptIn',
                ModuleSettings::DELIVERY_OPT_IN,
                false
            ),
            $this->prepareBoolTestItem(
                'showRegistrationOptIn',
                ModuleSettings::REGISTRATION_OPT_IN,
                true
            ),
            $this->prepareBoolTestItem(
                'showRegistrationOptIn',
                ModuleSettings::REGISTRATION_OPT_IN,
                false
            ),
            $this->prepareBoolTestItem(
                'showReviewOptIn',
                ModuleSettings::REVIEW_OPT_IN,
                true
            ),
            $this->prepareBoolTestItem(
                'showReviewOptIn',
                ModuleSettings::REVIEW_OPT_IN,
                false
            ),
        ];
    }

    private function prepareBoolTestItem(string $method, string $key, bool $value): array
    {
        return [
            'method' => $method,
            'systemMethod' => 'getBoolean',
            'key' => $key,
            'systemValue' => $value,
            'expectedValue' => $value
        ];
    }

    private function prepareStringTestItem(string $method, string $key): array
    {
        $exampleValue = 'exampleValue';
        return [
            'method' => $method,
            'systemMethod' => 'getString',
            'key' => $key,
            'systemValue' => new UnicodeString($exampleValue),
            'expectedValue' => $exampleValue
        ];
    }
}
