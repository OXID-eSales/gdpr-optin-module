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
    public static function onActivate()
    {
        self::clearCache();
    }

    /**
     * Module deactivation script.
     */
    public static function onDeactivate()
    {
        self::clearCache();
    }

    /**
     * Try to clean caches on module activation.
     */
    public static function clearCache()
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
