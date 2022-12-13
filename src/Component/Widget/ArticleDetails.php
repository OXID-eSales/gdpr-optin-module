<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component\Widget;

use OxidEsales\Eshop\Application\Controller\ReviewController;

/**
 * Class ArticleDetails
 * Extends \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 *
 * @package OxidEsales\GdprOptinModule\Component\Widget
 * @see \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 */
class ArticleDetails extends ArticleDetails_parent
{
    /**
     * Is optin for product review required.
     *
     * @return bool
     */
    public function isReviewOptInValidationRequired()
    {
        $review = oxNew(ReviewController::class);
        return $review->isReviewOptInValidationRequired();
    }

    /**
     * Was there an error for shop side review optin validation?
     *
     * @return bool
     */
    public function isReviewOptInError()
    {
        $review = oxNew(ReviewController::class);
        return $review->isReviewOptInError();
    }
}
