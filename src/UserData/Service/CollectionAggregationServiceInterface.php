<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\TableCollectionInterface;

interface CollectionAggregationServiceInterface
{
    /**
     * @return array<TableCollectionInterface>
     */
    public function collectUserData(string $userId): array;
}
