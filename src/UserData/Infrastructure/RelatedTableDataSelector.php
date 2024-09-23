<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

use Doctrine\DBAL\ForwardCompatibility\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class RelatedTableDataSelector implements DataSelectorInterface
{
    public function __construct(
        private string $primaryTable,
        private string $selectionTable,
        private string $relationCondition,
        private string $filterColumn,
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    public function getDataForColumnValue(string $columnValue): array
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select($this->selectionTable . '.*')
            ->from($this->primaryTable)
            ->innerJoin($this->primaryTable, $this->selectionTable, $this->selectionTable, $this->relationCondition)
            ->where($this->filterColumn . ' = :filterValue')
            ->setParameter('filterValue', $columnValue);

        /** @var Result $result */
        $result = $queryBuilder->execute();

        return $result->fetchAllAssociative();
    }
}
