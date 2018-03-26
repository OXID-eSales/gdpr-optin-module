<?php
/**
 * This file is part of OXID eSales GDPR opt-in module.
 *
 * OXID eSales GDPR opt-in module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales GDPR opt-in module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales GDPR opt-in module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2018
 */

namespace OxidEsales\GdprOptinModule\Controller;

/**
 * Class ReviewController
 * Extends \OxidEsales\Eshop\Application\Controller\ArticleDetailsController.
 *
 * @package OxidEsales\GdprOptinModule\Controller
 * @see \OxidEsales\Eshop\Application\Controller\ArticleDetailsController
 */
class ReviewController extends ReviewController_parent
{
    const REVIEW_OPTIN_PARAM = 'blOeGdprOptinProductReviews';

    /**
     * Saves user ratings and review text (oxReview object)
     *
     * @return null
     */
    public function saveReview()
    {
        if (!$this->validateOptIn()) {
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE');
            return false;
        }

        return parent::saveReview();
    }

    /**
     * Check if opt in validation for review is required.
     *
     * @return bool
     */
    public function isReviewOptInValidationRequired()
    {
        return (bool)\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam(self::REVIEW_OPTIN_PARAM);
    }

    /**
     * Validate current request data, regardless if form was submitted or not
     *
     * @return bool
     */
    public function validateOptIn()
    {
        $optInValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('rvw_oegdproptin');
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
        $formSent = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('rvw_oegdproptin') !== null;
        $review = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        $result = false;

        if ($formSent && !$review->validateOptIn()) {
            $result = true;
        }

        return $result;
    }
}
