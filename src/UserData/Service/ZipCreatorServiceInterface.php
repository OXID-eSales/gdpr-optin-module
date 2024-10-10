<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\ResultFileInterface;

interface ZipCreatorServiceInterface
{
    /**
     * @param array<ResultFileInterface> $files
     */
    public function createZip(array $files, string $outputFilePath): void;
}
