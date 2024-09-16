<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

interface ZipCreatorInterface
{
    public function createZip(array $files, string $outputPath): string;
}
