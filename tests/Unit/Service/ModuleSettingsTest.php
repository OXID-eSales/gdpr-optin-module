<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
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
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->expects($this->once())
            ->method($systemMethod)
            ->with($key, Module::MODULE_ID)
            ->willReturn($systemValue);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($expectedValue, $sut->$method());
    }

    public static function gettersDataProvider(): array
    {
        return [
            self::prepareStringTestItem(
                'getContactOptIn',
                ModuleSettings::CONTACT_CHOICE
            ),
            self::prepareBoolTestItem(
                'showInvoiceOptIn',
                ModuleSettings::INVOICE_OPT_IN,
                true
            ),
            self::prepareBoolTestItem(
                'showInvoiceOptIn',
                ModuleSettings::INVOICE_OPT_IN,
                false
            ),
            self::prepareBoolTestItem(
                'showDeliveryOptIn',
                ModuleSettings::DELIVERY_OPT_IN,
                true
            ),
            self::prepareBoolTestItem(
                'showDeliveryOptIn',
                ModuleSettings::DELIVERY_OPT_IN,
                false
            ),
            self::prepareBoolTestItem(
                'showRegistrationOptIn',
                ModuleSettings::REGISTRATION_OPT_IN,
                true
            ),
            self::prepareBoolTestItem(
                'showRegistrationOptIn',
                ModuleSettings::REGISTRATION_OPT_IN,
                false
            ),
            self::prepareBoolTestItem(
                'showReviewOptIn',
                ModuleSettings::REVIEW_OPT_IN,
                true
            ),
            self::prepareBoolTestItem(
                'showReviewOptIn',
                ModuleSettings::REVIEW_OPT_IN,
                false
            ),
        ];
    }

    private static function prepareBoolTestItem(string $method, string $key, bool $value): array
    {
        return [
            'method' => $method,
            'systemMethod' => 'getBoolean',
            'key' => $key,
            'systemValue' => $value,
            'expectedValue' => $value
        ];
    }

    private static function prepareStringTestItem(string $method, string $key): array
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
