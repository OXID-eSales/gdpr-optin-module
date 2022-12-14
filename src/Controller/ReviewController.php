<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\GdprOptinModule\Traits\ReviewOptIn;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;
use OxidEsales\GdprOptinModule\Service\ReviewOptIn as ReviewOptInService;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Controller\ReviewController
 */
class ReviewController extends ReviewController_parent
{
    use ReviewOptIn;

    /**
     * Saves user ratings and review text (oxReview object)
     */
    public function saveReview(): bool|null
    {
        $service = $this->getServiceFromContainer(ReviewOptInService::class);
        if (!$service->validateOptIn()) {
            Registry::get(UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE');
            return false;
        }

        return parent::saveReview();
    }
}
