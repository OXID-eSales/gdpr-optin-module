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

/**
 * Class GdprOptinModule
 * Handles module setup, provides additional tools and module related helpers.
 *
 * @codeCoverageIgnore
 *
 * @package OxidEsales\GdprOptinModule\Core
 */
class GdprOptinModule extends \OxidEsales\Eshop\Core\Module\Module
{
    /**
     * Class constructor.
     * Sets current module main data and loads the rest module info.
     */
    public function __construct()
    {
        $moduleId = 'oegdproptin';

        $this->setModuleData(
            [
                'id'          => $moduleId,
                'title'       => 'oegdproptin',
                'description' => 'OE GDPR opt-in Module',
            ]
        );

        $this->load($moduleId);

        \OxidEsales\Eshop\Core\Registry::set('oeGdprOptinModule', $this);
    }
    
    /**
     * Module activation script.
     */
    public static function onActivate()
    {
        self::clearTmp();
    }

    /**
     * Module deactivation script.
     */
    public static function onDeactivate()
    {
        self::clearTmp();
    }

    /**
     * Clean temp folder content.
     *
     * @param string $clearFolderPath Sub-folder path to delete from. Should be a full, valid path inside temp folder.
     *
     * @return boolean
     */
    public static function clearTmp($clearFolderPath = '')
    {
        $folderPath = self::_getFolderToClear($clearFolderPath);
        $directoryHandler = opendir($folderPath);

        if (!empty($directoryHandler)) {
            while (false !== ($fileName = readdir($directoryHandler))) {
                $filePath = $folderPath . DIRECTORY_SEPARATOR . $fileName;
                self::_clear($fileName, $filePath);
            }

            closedir($directoryHandler);
        }

        return true;
    }
    
    /**
     * Check if provided path is inside eShop `tpm/` folder or use the `tmp/` folder path.
     *
     * @param string $clearFolderPath
     *
     * @return string
     */
    protected static function _getFolderToClear($clearFolderPath = '')
    {
        $templateFolderPath = (string) \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sCompileDir');

        if (!empty($clearFolderPath) and (strpos($clearFolderPath, $templateFolderPath) !== false)) {
            $folderPath = $clearFolderPath;
        } else {
            $folderPath = $templateFolderPath;
        }

        return $folderPath;
    }

    /**
     * Check if resource could be deleted, then delete it's a file or
     * call recursive folder deletion if it's a directory.
     *
     * @param string $fileName
     * @param string $filePath
     */
    protected static function _clear($fileName, $filePath)
    {
        if (!in_array($fileName, ['.', '..', '.gitkeep', '.htaccess'])) {
            if (is_file($filePath)) {
                @unlink($filePath);
            } else {
                self::clearTmp($filePath);
            }
        }
    }
}
