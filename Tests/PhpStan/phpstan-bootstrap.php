<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

class_alias(
    \OxidEsales\Eshop\Core\ViewConfig::class,
    \OxidEsales\GdprOptinModule\Core\ViewConfig_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\ReviewController::class,
    OxidEsales\GdprOptinModule\Controller\ReviewController_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\ContactController::class,
    OxidEsales\GdprOptinModule\Controller\ContactController_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class,
    OxidEsales\GdprOptinModule\Controller\ArticleDetailsController_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Component\Widget\Review::class,
    OxidEsales\GdprOptinModule\Component\Widget\Review_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class,
    OxidEsales\GdprOptinModule\Component\Widget\ArticleDetails_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Component\UserComponent::class,
    OxidEsales\GdprOptinModule\Component\UserComponent_parent::class
);
