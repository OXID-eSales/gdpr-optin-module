<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\Eshop\Application\Controller\ReviewController as EshopReviewController;
use OxidEsales\GdprOptinModule\Service\ModuleSettings;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

/**
 * Class ReviewController
 * Extends \OxidEsales\Eshop\Application\Controller\ArticleDetailsController.
 *
 * @package OxidEsales\GdprOptinModule\Controller
 * @see \OxidEsales\Eshop\Application\Controller\ArticleDetailsController
 */
class ReviewController extends ReviewController_parent
{
    use ServiceContainer;

    /**
     * Saves user ratings and review text (oxReview object)
     *
     * @return null
     */
    public function saveReview()
    {
        if (!$this->validateOptIn()) {
            Registry::get(UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE');
            return false;
        }

        return parent::saveReview();
    }

    /**
     * Check if opt in validation for review is required.
     *
     * @return bool
     */
    public function isReviewOptInValidationRequired(): bool
    {
        $moduleSettings = $this->getServiceFromContainer(ModuleSettings::class);

        return $moduleSettings->showReviewOptIn();
    }

    /**
     * Validate current request data, regardless if form was submitted or not
     *
     * @return bool
     */
    public function validateOptIn()
    {
        $optInValue = Registry::getRequest()->getRequestParameter('rvw_oegdproptin');
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
        $formSent = Registry::getRequest()->getRequestParameter('rvw_oegdproptin') !== null;
        $review = oxNew(EshopReviewController::class);
        $result = false;

        if ($formSent && !$review->validateOptIn()) {
            $result = true;
        }

        return $result;
    }
}
