<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

use Doctrine\DBAL\ForwardCompatibility\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class GeneralTableDataSelector implements DataSelectorInterface
{
    public function __construct(
        private string $collection,
        private string $selectionTable,
        private string $filterColumn,
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private bool $optional = false,
    ) {
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function getSelectionTable(): string
    {
        return $this->selectionTable;
    }

    public function getDataForColumnValue(string $columnValue): array
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        try {
            $queryBuilder->select('*')
                ->from($this->selectionTable)
                ->where($this->filterColumn . ' = :filterValue')
                ->setParameter('filterValue', $columnValue);

            /** @var Result $result */
            $result = $queryBuilder->execute(); /** @phpstan-ignore missingType.iterableValue */

            return $result->fetchAllAssociative();
        } catch (\Exception $e) {
            if (!$this->optional) {
                throw $e;
            }
        }

        return [];
    }
}
