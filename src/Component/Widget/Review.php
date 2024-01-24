<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Component\Widget;

use OxidEsales\GdprOptinModule\Service\ReviewOptInInterface as ReviewOptInService;

/**
 * @eshopExtension
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\Review
 */
class Review extends Review_parent
{
    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->addTplParam('reviewOptInService', $this->getService(ReviewOptInService::class));
    }
}
