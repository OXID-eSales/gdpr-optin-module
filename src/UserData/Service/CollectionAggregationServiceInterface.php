<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollectionInterface;

interface CollectionAggregationServiceInterface
{
    /**
     * @return array<TableDataCollectionInterface>
     */
    public function collectUserData(string $userId): array;
}
