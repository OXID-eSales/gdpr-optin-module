<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

use OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepositoryInterface;

abstract class AbstractTableDataCollector implements TableDataCollectorInterface
{
    public function __construct(
        protected readonly UserDataRepositoryInterface $repository
    ) {
    }

    abstract public function getTableName(): string;

    abstract protected function getColumnName(): string;

    public function collect(string $recordId): array
    {
        return $this->repository->getDataFromTable($this->getTableName(), $this->getColumnName(), $recordId);
    }
}
