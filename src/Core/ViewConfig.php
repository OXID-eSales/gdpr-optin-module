<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    public function showGdprInvoiceOptIn(): bool
    {
        return $this->getService(ModuleSettings::class)->showInvoiceOptIn();
    }

    public function showGdprDeliveryOptIn(): bool
    {
        return $this->getService(ModuleSettings::class)->showDeliveryOptIn();
    }

    public function showGdprRegistrationOptIn(): bool
    {
        return $this->getService(ModuleSettings::class)->showRegistrationOptIn();
    }

    public function showGdprReviewOptIn(): bool
    {
        return $this->getService(ModuleSettings::class)->showReviewOptIn();
    }

    public function getGdprContactOptIn(): string
    {
        return $this->getService(ModuleSettings::class)->getContactOptIn();
    }

    public function getInvoiceOptIn(): bool
    {
        return (bool)Registry::getRequest()->getRequestEscapedParameter('oegdproptin_invoiceaddress');
    }

    public function getDeliveryOptIn(): bool
    {
        return (bool)Registry::getRequest()->getRequestEscapedParameter('oegdproptin_deliveryaddress');
    }
}
