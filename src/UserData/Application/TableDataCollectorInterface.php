<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

interface TableDataCollectorInterface
{
    public function collect(string $recordId): array;
    public function getTableName(): string;
}
