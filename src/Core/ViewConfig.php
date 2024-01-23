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
    public function showGdprInvoiceOptIn(): bool
    {
        return $this->getService(ModuleSettingsInterface::class)->showInvoiceOptIn();
    }

    public function showGdprDeliveryOptIn(): bool
    {
        return $this->getService(ModuleSettingsInterface::class)->showDeliveryOptIn();
    }

    public function showGdprRegistrationOptIn(): bool
    {
        return $this->getService(ModuleSettingsInterface::class)->showRegistrationOptIn();
    }

    public function showGdprReviewOptIn(): bool
    {
        return $this->getService(ModuleSettingsInterface::class)->showReviewOptIn();
    }

    public function getGdprContactOptIn(): string
    {
        return $this->getService(ModuleSettingsInterface::class)->getContactOptIn();
    }

    public function getInvoiceOptIn(): bool
    {
        return $this->getService(OptInRequestInterface::class)->getInvoiceAddressOptIn();
    }

    public function getDeliveryOptIn(): bool
    {
        return $this->getService(OptInRequestInterface::class)->getDeliveryAddressOptIn();
    }
}
