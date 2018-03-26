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

namespace OxidEsales\GdprOptinModule\Component\Widget;

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
        $review = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        return $review->isReviewOptInValidationRequired();
    }

    /**
     * Was there an error for shop side review optin validation?
     *
     * @return bool
     */
    public function isReviewOptInError()
    {
        $review = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        return $review->isReviewOptInError();
    }
}
