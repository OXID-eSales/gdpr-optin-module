<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Transput;

interface OptInRequestInterface
{
    public function getInvoiceAddressOptIn(): bool;

    public function getDeliveryAddressOptIn(): bool;
}
