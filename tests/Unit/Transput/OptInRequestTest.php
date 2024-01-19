<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Tests\Unit\Transput;

use OxidEsales\GdprOptinModule\Transput\OptInRequest;
use PHPUnit\Framework\TestCase;

class OptInRequestTest extends TestCase
{
    public static function getBooleanValueDataProvider(): array
    {
        return [
            ['', false],
            ['0', false],
            [0, false],
            ['1', true],
            [1, true],
            [uniqid(), true],
        ];
    }

    /** @dataProvider getBooleanValueDataProvider */
    public function testGetInvoiceAddressOptIn($requestValue, $expectedValue): void
    {
        $shopRequest = $this->createMock(\OxidEsales\Eshop\Core\Request::class);
        $shopRequest->method('getRequestEscapedParameter')
            ->with(OptInRequest::REQUEST_PARAM_INVOICE_ADDRESS_OPT_IN)
            ->willReturn($requestValue);

        $sut = new OptInRequest(
            shopRequest: $shopRequest
        );

        $this->assertSame($expectedValue, $sut->getInvoiceAddressOptIn());
    }

    /** @dataProvider getBooleanValueDataProvider */
    public function testGetDeliveryAddressOptIn($requestValue, $expectedValue): void
    {
        $shopRequest = $this->createMock(\OxidEsales\Eshop\Core\Request::class);
        $shopRequest->method('getRequestEscapedParameter')
            ->with(OptInRequest::REQUEST_PARAM_DELIVERY_ADDRESS_OPT_IN)
            ->willReturn($requestValue);

        $sut = new OptInRequest(
            shopRequest: $shopRequest
        );

        $this->assertSame($expectedValue, $sut->getDeliveryAddressOptIn());
    }
}
