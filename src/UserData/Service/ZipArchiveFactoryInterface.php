<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\Exception\ZipCreationException;
use ZipArchive;

interface ZipArchiveFactoryInterface
{
    /**
     * @throws ZipCreationException
     */
    public function create(string $filePath): ZipArchive;
}
