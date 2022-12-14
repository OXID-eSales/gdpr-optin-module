<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component\Widget;

use OxidEsales\GdprOptinModule\Traits\ReviewOptIn;

/**
 * Class Review
 * Extends \OxidEsales\Eshop\Application\Component\Widget\Review.
 *
 * @package OxidEsales\GdprOptinModule\Component\Widget
 * @see \OxidEsales\Eshop\Application\Component\Widget\Review
 */
class Review extends Review_parent
{
    use ReviewOptIn;
}
