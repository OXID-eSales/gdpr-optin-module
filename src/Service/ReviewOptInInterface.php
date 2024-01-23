<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\Service;

interface ReviewOptInInterface
{
    public function isReviewOptInValidationRequired(): bool;

    /**
     * Validate current request data, regardless if form was submitted or not
     */
    public function validateOptIn(): bool;

    /**
     * Check if form was sent but optin not checked when required
     *
     * @return bool
     */
    public function isReviewOptInError();
}
