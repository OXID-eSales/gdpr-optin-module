<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\DataType;

class TableCollection implements TableCollectionInterface
{
    /**
     * @param array<string, array<array<string, string>>> $dataCollection
     */
    public function __construct(
        protected string $collectionName,
        protected array $dataCollection
    ) {
    }

    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    public function getCollection(): array
    {
        return $this->dataCollection;
    }
}
