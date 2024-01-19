<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Transput;

use OxidEsales\Eshop\Core\Request;

class OptInRequest implements OptInRequestInterface
{
    public const REQUEST_PARAM_INVOICE_ADDRESS_OPT_IN = 'oegdproptin_invoiceaddress';
    public const REQUEST_PARAM_DELIVERY_ADDRESS_OPT_IN = 'oegdproptin_deliveryaddress';

    public function __construct(
        protected Request $shopRequest
    ) {
    }

    public function getInvoiceAddressOptIn(): bool
    {
        return (bool)$this->shopRequest->getRequestEscapedParameter(
            self::REQUEST_PARAM_INVOICE_ADDRESS_OPT_IN
        );
    }

    public function getDeliveryAddressOptIn(): bool
    {
        return (bool)$this->shopRequest->getRequestEscapedParameter(
            self::REQUEST_PARAM_DELIVERY_ADDRESS_OPT_IN
        );
    }
}
