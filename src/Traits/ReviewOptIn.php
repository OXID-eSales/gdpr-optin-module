<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Traits;

use OxidEsales\GdprOptinModule\Service\ReviewOptIn as ReviewOptInService;

trait ReviewOptIn
{
    use ServiceContainer;

    public function isReviewOptInValidationRequired(): bool
    {
        return $this->getServiceFromContainer(ReviewOptInService::class)
            ->isReviewOptInValidationRequired();
    }

    public function isReviewOptInError(): bool
    {
        return $this->getServiceFromContainer(ReviewOptInService::class)
            ->isReviewOptInError();
    }
}
