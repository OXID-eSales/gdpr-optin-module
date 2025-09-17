<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Service;

use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollection;
use OxidEsales\GdprOptinModule\UserData\DataType\TableDataCollectionInterface;
use OxidEsales\GdprOptinModule\UserData\Exception\AggregationTypeException;
use OxidEsales\GdprOptinModule\UserData\Infrastructure\DataSelectorInterface;

class CollectionAggregationService implements CollectionAggregationServiceInterface
{
    /** @var array<string, array<DataSelectorInterface>> */
    protected array $groupedCollectors = [];

    /**
     * @param iterable<Object> $collectors
     */
    public function __construct(
        iterable $collectors
    ) {
        foreach ($collectors as $collector) {
            if (!$collector instanceof DataSelectorInterface) {
                throw new AggregationTypeException();
            }

            $this->groupedCollectors[$collector->getCollection()][] = $collector;
        }
    }

    /**
     * @return array<TableDataCollectionInterface>
     */
    public function collectUserData(string $userId): array
    {
        $resultCollections = [];

        foreach ($this->groupedCollectors as $collectionName => $collectors) {
            $collectionData = [];
            foreach ($collectors as $oneCollector) {
                $collectionData[$oneCollector->getSelectionTable()] = $oneCollector->getDataForColumnValue($userId);
            }
            $resultCollections[] = new TableDataCollection($collectionName, $collectionData);
        }

        return $resultCollections;
    }
}
