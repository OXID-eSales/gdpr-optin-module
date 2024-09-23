<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Application;

use OxidEsales\GdprOptinModule\UserData\Infrastructure\UserDataRepositoryInterface;

abstract class AbstractAggregateTableDataCollector implements TableDataCollectorInterface
{
    public function __construct(
        protected readonly UserDataRepositoryInterface $repository,
        protected iterable $collectors
    ) {
    }

    abstract public function getTableName(): string;

    abstract protected function getColumnName(): string;

    public function collect(string $recordId): array
    {
        $orderData = $this->repository->getDataFromTable(
            table: $this->getTableName(),
            columnName: $this->getColumnName(),
            columnValue: $recordId
        );

        $relatedData = [];
        if ($orderData) {
            foreach ($this->collectors as $collector) {
                if ($collector instanceof RelatedTableDataCollectorInterface) {
                    $relatedData[$collector->getTableName()] =
                        $collector->collectRelatedData(
                            primaryTable: $this->getTableName(),
                            primaryKey: $this->getColumnName(),
                            primaryColumn: $this->getColumnName(),
                            primaryValue: $recordId
                        );
                }
            }
        }

        return [
            $this->getTableName() => $orderData,
            'related_data' => $relatedData,
        ];
    }
}
