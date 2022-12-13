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
use OxidEsales\GdprOptinModule\Traits\ReviewOptIn;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;
use OxidEsales\GdprOptinModule\Service\ReviewOptIn as ReviewOptInService;

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
    use ReviewOptIn;

    /**
     * Saves user ratings and review text (oxReview object)
     *
     * @return null
     */
    public function saveReview()
    {
        $service = $this->getServiceFromContainer(ReviewOptInService::class);
        if (!$service->validateOptIn()) {
            Registry::get(UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE');
            return false;
        }

        return parent::saveReview();
    }
}
