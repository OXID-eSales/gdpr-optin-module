<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\GdprOptinModule\Core\GdprOptinModule;

final class ModuleSettings
{
    /** @var ModuleSettingServiceInterface */
    private $moduleSettingService;

    private const INVOICE_OPT_IN = 'blOeGdprOptinInvoiceAddress';
    private const DELIVERY_OPT_IN = 'blOeGdprOptinDeliveryAddress';
    private const REGISTRATION_OPT_IN = 'blOeGdprOptinUserRegistration';
    private const REVIEW_OPT_IN = 'blOeGdprOptinProductReviews';
    private const CONTACT_CHOICE = 'OeGdprOptinContactFormMethod';

    public function __construct(
        ModuleSettingServiceInterface $moduleSettingService
    ) {
        $this->moduleSettingService = $moduleSettingService;
    }
    
    public function showInvoiceOptIn(): bool
    {
        return $this->getBoolSettingValue(self::INVOICE_OPT_IN);
    }

    public function showDeliveryOptIn(): bool
    {
        return $this->getBoolSettingValue(self::DELIVERY_OPT_IN);
    }

    public function showRegistrationOptIn(): bool
    {
        return $this->getBoolSettingValue(self::REGISTRATION_OPT_IN);
    }

    public function showReviewOptIn(): bool
    {
        return $this->getBoolSettingValue(self::REVIEW_OPT_IN);
    }

    public function getContactOptIn(): string
    {
        return $this->getStringSettingValue(self::CONTACT_CHOICE);
    }

    protected function getStringSettingValue($key): string
    {
        return $this->moduleSettingService->getString(
            $key,
            GdprOptInModule::MODULE_ID
        )->trim()->toString();
    }

    protected function getBoolSettingValue($key): bool
    {
        return $this->moduleSettingService->getBoolean(
            $key,
            GdprOptInModule::MODULE_ID
        );
    }
}