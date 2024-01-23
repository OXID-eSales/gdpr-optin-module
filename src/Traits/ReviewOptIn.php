<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Traits;

use OxidEsales\GdprOptinModule\Service\ReviewOptInInterface as ReviewOptInService;

trait ReviewOptIn
{
    public function isReviewOptInValidationRequired(): bool
    {
        return $this->getService(ReviewOptInService::class)->isReviewOptInValidationRequired();
    }

    public function isReviewOptInError(): bool
    {
        return $this->getService(ReviewOptInService::class)->isReviewOptInError();
    }
}
