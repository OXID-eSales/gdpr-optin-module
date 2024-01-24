<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Core;

use OxidEsales\GdprOptinModule\Service\ModuleSettingsInterface;
use OxidEsales\GdprOptinModule\Transput\OptInRequestInterface;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    public function getInvoiceOptIn(): bool
    {
        return $this->getService(OptInRequestInterface::class)->getInvoiceAddressOptIn();
    }

    public function getDeliveryOptIn(): bool
    {
        return $this->getService(OptInRequestInterface::class)->getDeliveryAddressOptIn();
    }

    public function getGdprModuleSettings(): ModuleSettingsInterface
    {
        return $this->getService(ModuleSettingsInterface::class);
    }
}
