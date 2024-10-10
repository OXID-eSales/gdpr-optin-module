<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserDataExportCleanupEvent extends Event implements UserDataExportCleanupEventInterface
{
    public function __construct(
        private readonly string $filePath
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
