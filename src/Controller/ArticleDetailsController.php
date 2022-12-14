<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\GdprOptinModule\Service\ReviewOptIn as ReviewOptInService;
use OxidEsales\GdprOptinModule\Traits\ServiceContainer;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Controller\ArticleDetailsController;
 */
class ArticleDetailsController extends ArticleDetailsController_parent
{
    use ServiceContainer;

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
