<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Infrastructure;

use Doctrine\DBAL\ForwardCompatibility\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class UserDataRepository implements UserDataRepositoryInterface
{
    public function __construct(
        private readonly QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
    }

    public function getDataFromTable(string $table, string $columnName, string $columnValue): array
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
            ->from($table)
            ->where($columnName . ' = :' . $columnName)
            ->setParameter($columnName, $columnValue);

        /** @var Result $result */
        $result = $queryBuilder->execute();

        return $result->fetchAllAssociative();
    }

    public function getJoinedData(
        string $primaryTable,
        string $primaryKey,
        string $foreignTable,
        string $foreignKey,
        string $primaryColumn,
        string $primaryValue
    ): array {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
            ->from($primaryTable)
            ->innerJoin($primaryTable, $foreignTable, 'ft', "ft.{$foreignKey} = {$primaryTable}.{$primaryKey}")
            ->where($primaryTable . '.' . $primaryColumn . ' = :' . $primaryColumn)
            ->setParameter($primaryColumn, $primaryValue);

        /** @var Result $result */
        $result = $queryBuilder->execute();

        return $result->fetchAllAssociative();
    }
}
