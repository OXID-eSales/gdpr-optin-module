<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Service;

interface ModuleSettingsInterface
{
    public function showInvoiceOptIn(): bool;

    public function showDeliveryOptIn(): bool;

    public function showRegistrationOptIn(): bool;

    public function showReviewOptIn(): bool;

    public function getContactOptIn(): string;
}
