<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Facts;

/**
 * Class GdprOptinModule
 * Handles module setup, provides additional tools and module related helpers.
 *
 * @codeCoverageIgnore
 *
 * @package OxidEsales\GdprOptinModule\Core
 */
class GdprOptinModule
{
    public const MODULE_ID = 'oegdproptin';

    /**
     * Module activation script.
     */
    public static function onActivate(): void
    {
        self::clearCache();
    }

    /**
     * Module deactivation script.
     */
    public static function onDeactivate(): void
    {
        self::clearCache();
    }

    public static function clearCache(): void
    {
        try {
            $facts = new Facts();
            exec(
                $facts->getCommunityEditionRootPath() .
                '/bin/oe-console oe:cache:clear'
            );
        } catch (\Exception $exception) {
            Registry::getLogger()
                ->error(
                    'GepfOptin Module::clearCachesOnModuleActivateDeactivate failed ' .
                    $exception->getMessage()
                );
        }
    }
}
