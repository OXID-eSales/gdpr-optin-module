<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Service;

use OxidEsales\Eshop\Core\Request;

final class ReviewOptIn
{
    /** @var Request */
    private $request;

    /** @var ModuleSettings */
    private $moduleSettings;

    public function __construct(
        Request $request,
        ModuleSettings $moduleSettings
    ) {
        $this->request = $request;
        $this->moduleSettings = $moduleSettings;
    }

    public function isReviewOptInValidationRequired(): bool
    {
        return $this->moduleSettings->showReviewOptIn();
    }

    /**
     * Validate current request data, regardless if form was submitted or not
     */
    public function validateOptIn(): bool
    {
        $optInValue = $this->request
            ->getRequestParameter('rvw_oegdproptin');

        if ($this->isReviewOptInValidationRequired() && !$optInValue) {
            return false;
        }
        return true;
    }

    /**
     * Check if form was sent but optin not checked when required
     *
     * @return bool
     */
    public function isReviewOptInError()
    {
        $formSent = $this->request
                ->getRequestParameter('rvw_oegdproptin') !== null;

        if ($formSent && !$this->validateOptIn()) {
            return true;
        }
        return false;
    }
}
