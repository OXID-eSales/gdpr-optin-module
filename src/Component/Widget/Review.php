<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component\Widget;

use OxidEsales\GdprOptinModule\Traits\ReviewOptIn;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\Review
 */
class Review extends Review_parent
{
    use ReviewOptIn;
}
