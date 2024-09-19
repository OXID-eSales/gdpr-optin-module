<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

use OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepositoryInterface;

abstract class AbstractRelatedTableDataCollector implements RelatedTableDataCollectorInterface
{
    public function __construct(
        protected readonly UserDataRepositoryInterface $repository
    ) {
    }

    abstract public function getTableName(): string;

    abstract protected function getColumnName(): string;

    public function collectRelatedData(
        string $primaryTable,
        string $primaryKey,
        string $primaryConditionColumn,
        string $primaryConditionValue
    ): array {
        return $this->repository->getJoinedData(
            $primaryTable,
            $primaryKey,
            $this->getTableName(),
            $this->getColumnName(),
            $primaryConditionColumn,
            $primaryConditionValue
        );
    }
}
