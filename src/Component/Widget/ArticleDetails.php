<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component\Widget;

use OxidEsales\Eshop\Application\Controller\ReviewController;
use OxidEsales\GdprOptinModule\Service\ReviewOptIn as ReviewOptInService;
use OxidEsales\GdprOptinModule\Traits\ReviewOptIn;

/**
 * Class ArticleDetails
 * Extends \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 *
 * @package OxidEsales\GdprOptinModule\Component\Widget
 * @see \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 */
class ArticleDetails extends ArticleDetails_parent
{
    use ReviewOptIn;
}
