<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

interface UserDataExportInterface
{
    public function exportUserData(string $userId, string $outputPath): void;
}
