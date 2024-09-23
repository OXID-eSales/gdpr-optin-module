<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

interface RelatedTableDataCollectorInterface
{
    public function collectRelatedData(
        string $primaryTable,
        string $primaryKey,
        string $primaryColumn,
        string $primaryValue
    ): array;

    public function getTableName(): string;
}
