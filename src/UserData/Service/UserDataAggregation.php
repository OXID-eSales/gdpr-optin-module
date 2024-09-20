<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\Application\TableDataCollectorInterface;

class UserDataAggregation implements UserDataAggregationInterface
{
    public function __construct(
        protected iterable $collectors
    ) {
    }

    public function collectUserData(string $userId): array
    {
        $userData = [];
        foreach ($this->collectors as $collector) {
            if ($collector instanceof TableDataCollectorInterface) {
                $userData[$collector->getTableName()] = $collector->collect($userId);
            }
        }

        return $userData;
    }
}
